<?php
/**
 * A CLI command for ONA13
 */
class ONA13_CLI_Command extends WP_CLI_Command {

	private $session_fields = array(
			array(
					'csv_field'          => 'Session Name',
					'object_field'       => 'title',
					'sanitize_callback'  => 'sanitize_text_field',
				),
			array(
					'csv_field'          => 'Session Description',
					'object_field'       => 'description',
					'sanitize_callback'  => 'wp_filter_post_kses',
				),
			array(
					'csv_field'          => 'Start Date & Time',
					'object_field'       => 'start_time',
					'sanitize_callback'  => 'strtotime',
				),
			array(
					'csv_field'          => 'End Date & Time',
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

		WP_CLI::line();

		foreach ( new \WP_CLI\Iterators\CSV( $file ) as $i => $csv_session ) {

			// Uh oh, someone messed up the rows
			if ( empty( $csv_session['ID'] ) )
				continue;

			// If the session doesn't exist, let's create it first.
			if ( false === ( $session = ONA_Session::get_by_session_id( (int)$csv_session['ID'] ) ) ) {
				$post_id = wp_insert_post( array( 'post_type' => ONA_Session::$post_type ) );
				$session = new ONA_Session( $post_id );
				$session->set_session_id( $csv_session['ID'] );
				WP_CLI::line( sprintf( "Inserting session #%d as post #%d", $csv_session['ID'], $session->get_id() ) );
			} else {
				WP_CLI::line( sprintf( "Updating session #%d (post #%d)", $csv_session['ID'], $session->get_id() ) );
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

					if ( $new_value != $session->$get_method() ) {
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
		WP_CLI::success( "Import complete" );
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