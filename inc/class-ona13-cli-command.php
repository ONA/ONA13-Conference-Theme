<?php
/**
 * A CLI command for ONA13
 */
class ONA13_CLI_Command extends WP_CLI_Command {

	private $session_fields = array(
			array(
					'csv_field'          => 'L-S-M Type',
					'object_field'       => 'type',
					'sanitize_callback'  => 'sanitize_title',
					'comparison_callback'=> 'ONA13_CLI_Command::compare_to_term',
				),
			array(
					'csv_field'          => 'Session Name',
					'object_field'       => 'title',
					'sanitize_callback'  => 'sanitize_text_field',
				),
			array(
					'csv_field'          => 'Session Slug',
					'object_field'       => 'slug',
					'sanitize_callback'  => 'sanitize_key',
				),
			array(
					'csv_field'          => 'Session Description',
					'object_field'       => 'description',
					'sanitize_callback'  => 'wp_filter_post_kses',
				),
			array(
					'csv_field'          => 'Room',
					'object_field'       => 'room',
					'sanitize_callback'  => 'sanitize_title',
					'comparison_callback'=> 'ONA13_CLI_Command::compare_to_term',
				),
			array(
					'csv_field'          => 'Start Time',
					'object_field'       => 'start_time',
					'sanitize_callback'  => 'strtotime',
				),
			array(
					'csv_field'          => 'End Time',
					'object_field'       => 'end_time',
					'sanitize_callback'  => 'strtotime',
				),
		);

	/**
	 * Import or update sessions from CSV.
	 * 
	 * @subcommand import-sessions
	 * @synopsis <file>
	 */
	public function import_sessions( $args ) {

		list( $file ) = $args;

		// The file might actually be a remote CSV (aka Google Doc)
		if ( ! file_exists( $file ) ) {
			$file_data = wp_remote_get( $file );
			if ( ! wp_remote_retrieve_body( $file_data ) ) {
				WP_CLI::error( sprintf( "Couldn't retrieve: %s", $file ) );
			} else {
				$data = wp_remote_retrieve_body( $file_data );
				$tmp_file = 'session-import-temp.csv';
				file_put_contents( $tmp_file, $data );
				$file = $tmp_file;
			}
		}

		WP_CLI::line();

		foreach ( new \WP_CLI\Iterators\CSV( $file ) as $i => $csv_session ) {

			// Uh oh, someone messed up the rows
			if ( empty( $csv_session['Session Slug'] ) )
				continue;

			// If the session doesn't exist, let's create it first.
			$session_slug = sanitize_key( $csv_session['Session Slug'] );
			if ( false === ( $session = ONA_Session::get_by_slug( $session_slug ) ) ) {
				$post_id = wp_insert_post( array( 'post_type' => ONA_Session::$post_type, 'post_name' => $session_slug, 'post_status' => 'publish' ) );
				$session = new ONA_Session( $post_id );
				WP_CLI::line( sprintf( "Inserting session '%s' as post #%d", $session_slug, $session->get_id() ) );
			} else {
				WP_CLI::line( sprintf( "Updating session '%s' (post #%d)", $session_slug, $session->get_id() ) );
			}

			foreach( $csv_session as $key => $value ) {

				foreach( $this->session_fields as $session_field ) {

					if ( $session_field['csv_field'] != $key )
						continue;

					if ( ! is_callable( $session_field['sanitize_callback'] ) )
						continue;

					$value = stripslashes( $value );
					$new_value = call_user_func_array( $session_field['sanitize_callback'], array( $value ) );

					$get_method = 'get_' . $session_field['object_field'];
					$set_method = 'set_' . $session_field['object_field'];

					// See whether the values should be updated
					if ( isset( $session_field['comparison_callback'] ) && is_callable( $session_field['comparison_callback'] ) )
						$update = call_user_func_array( $session_field['comparison_callback'], array( $new_value, $session->$get_method() ) );
					else
						$update = ( $new_value != $session->$get_method() );

					if ( $update ) {
						$session->$set_method( $new_value );
						self::output_diff( $key, $new_value );
					} else {
						self::output_diff( $key );
					}

				}
	
			}

			WP_CLI::line();
			WP_CLI::line();
		}

		if ( ! empty( $tmp_file ) )
			@unlink( $tmp_file );

		WP_CLI::success( "Import complete" );
	}

	/**
	 * Compare a value to a term
	 * 
	 * @return bool     True to update, false to not
	 */
	public static function compare_to_term( $new_value, $old_value ) {
		if ( ! is_object( $old_value ) )
			return true;

		if ( $new_value != $old_value->slug )
			return true;
		else
			return false;
	}

	/**
	 * Output any change made to a Session.
	 */
	private static function output_diff( $key, $new_value = false ) {
		if ( false !== $new_value )
			WP_CLI::line( sprintf( " - %s: %s", $key, $new_value ) );
		else
			WP_CLI::line( sprintf( " - %s: NO CHANGE", $key ) );
	}

}
WP_CLI::add_command( 'ona13', 'ONA13_CLI_Command' );