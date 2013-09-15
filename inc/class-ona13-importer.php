<?php
/**
 * An importer for Google Docs
 */
class ONA13_Importer {

	static public $session_fields = array(
			array(
					'csv_field'          => 'L-S-M Type',
					'object_field'       => 'session_type',
					'sanitize_callback'  => 'sanitize_title',
					'comparison_callback'=> 'ONA13_Importer::compare_to_term',
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
					'comparison_callback'=> 'ONA13_Importer::compare_to_term',
				),
			array(
					'csv_field'          => 'Session Format',
					'object_field'       => 'session_format',
					'sanitize_callback'  => 'sanitize_title',
					'comparison_callback'=> 'ONA13_Importer::compare_to_term',
				),
			array(
					'csv_field'          => 'Start Time',
					'object_field'       => 'start_time',
					'pre_sanitize_callback' => 'ONA13_Importer::prepare_time_field',
					'sanitize_callback'  => 'strtotime',
				),
			array(
					'csv_field'          => 'End Time',
					'object_field'       => 'end_time',
					'pre_sanitize_callback' => 'ONA13_Importer::prepare_time_field',
					'sanitize_callback'  => 'strtotime',
				),
			array(
					'csv_field'          => 'RebelMouse',
					'object_field'       => 'rebelmouse',
					'sanitize_callback'  => 'sanitize_key',
				),
		);

	/**
	 * Import or update sessions from CSV.
	 */
	public static function import_sessions( $file, $output_callback ) {

		// The file might actually be a remote CSV (aka Google Doc)
		if ( ! file_exists( $file ) ) {
			$file_data = wp_remote_get( $file );
			if ( ! wp_remote_retrieve_body( $file_data ) ) {
				$output_callback( sprintf( "Couldn't retrieve: %s", $file ), 'error' );
			} else {
				$data = wp_remote_retrieve_body( $file_data );
			}
		} else {
			$data = file_get_contents( $file );
		}

		$output_callback();

		$rows = explode( ',' . PHP_EOL, $data );
		$keys = str_getcsv( array_shift( $rows ) );

		foreach ( $rows as $i => $csv_session ) {

			$values = str_getcsv( $csv_session );
			while( count( $values ) < count( $keys ) ) {
				$values[] = '';
			}

			$csv_session = array_combine( $keys, $values );

			// Uh oh, someone messed up the rows
			if ( empty( $csv_session['Session Slug'] ) )
				continue;

			// If the session doesn't exist, let's create it first.
			$session_slug = sanitize_key( $csv_session['Session Slug'] );
			if ( false === ( $session = ONA_Session::get_by_slug( $session_slug ) ) ) {
				$post_id = wp_insert_post( array( 'post_type' => ONA_Session::$post_type, 'post_name' => $session_slug, 'post_status' => 'publish' ) );
				$session = new ONA_Session( $post_id );
				$output_callback( sprintf( "Inserting session '%s' as post #%d", $session_slug, $session->get_id() ) );
			} else {
				$output_callback( sprintf( "Updating session '%s' (post #%d)", $session_slug, $session->get_id() ) );
			}

			foreach( $csv_session as $key => $value ) {

				foreach( self::$session_fields as $session_field ) {

					if ( $session_field['csv_field'] != $key )
						continue;

					if ( ! is_callable( $session_field['sanitize_callback'] ) )
						continue;

					$value = stripslashes( $value );
					if ( isset( $session_field['pre_sanitize_callback'] ) && is_callable( $session_field['pre_sanitize_callback'] ) )
						$value = call_user_func_array( $session_field['pre_sanitize_callback'], array( $value, $csv_session ) );


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
						self::output_diff( $key, $new_value, $output_callback );
					} else {
						self::output_diff( $key, false, $output_callback );
					}

				}
	
			}

			$output_callback();
			$output_callback();
		}

		if ( ! empty( $tmp_file ) )
			@unlink( $tmp_file );

		$output_callback( "Import complete", 'success' );
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
	private static function output_diff( $key, $new_value = false, $output_callback ) {
		if ( false !== $new_value )
			$output_callback( sprintf( " - %s: %s", $key, $new_value ) );
		else
			$output_callback( sprintf( " - %s: NO CHANGE", $key ) );
	}

	/**
	 * Prepare a time field to include the date as well
	 */
	public static function prepare_time_field( $new_value, $csv_row ) {
		$dates = array(
				'Thursday' => 'October 17',
				'Friday' => 'October 18',
				'Saturday' => 'October 19',
			);
		if ( isset( $dates[$csv_row['Day']] ) )
			return $csv_row['Day'] . ', ' . $dates[$csv_row['Day']] . ' ' . $new_value;
		else
			return $new_value;
	}

}