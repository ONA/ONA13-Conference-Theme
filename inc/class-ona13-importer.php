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
					'comparison_callback'=> 'self::compare_to_term_slug',
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
					'comparison_callback'=> 'self::compare_to_term_slug',
				),
			array(
					'csv_field'          => 'Session Format',
					'object_field'       => 'session_format',
					'sanitize_callback'  => 'sanitize_title',
					'comparison_callback'=> 'self::compare_to_term_slug',
				),
			array(
					'csv_field'          => 'Tags',
					'object_field'       => 'tags',
					'sanitize_callback'  => 'sanitize_text_field',
					'comparison_callback'=> 'self::compare_to_array_values',
				),
			array(
					'csv_field'          => 'Speakers',
					'object_field'       => 'speakers',
					'pre_sanitize_callback' => 'self::prepare_speakers_field',
					'sanitize_callback'  => false,
				),
			array(
					'csv_field'          => 'Start Time',
					'object_field'       => 'start_time',
					'pre_sanitize_callback' => 'self::prepare_time_field',
					'sanitize_callback'  => 'strtotime',
				),
			array(
					'csv_field'          => 'End Time',
					'object_field'       => 'end_time',
					'pre_sanitize_callback' => 'self::prepare_time_field',
					'sanitize_callback'  => 'strtotime',
				),
			array(
					'csv_field'          => 'RebelMouse',
					'object_field'       => 'rebelmouse',
					'sanitize_callback'  => 'sanitize_key',
				),
		);

		static public $speaker_fields = array(
			array(
					'csv_field'          => 'Speaker Name',
					'object_field'       => 'name',
					'sanitize_callback'  => 'sanitize_text_field',
				),
			array(
					'csv_field'          => 'Title',
					'object_field'       => 'title',
					'sanitize_callback'  => 'sanitize_text_field',
				),
			array(
					'csv_field'          => 'Company/Organization',
					'object_field'       => 'organization',
					'sanitize_callback'  => 'sanitize_text_field',
				),
			array(
					'csv_field'          => 'Email',
					'object_field'       => 'email',
					'sanitize_callback'  => 'sanitize_text_field',
				),
			array(
					'csv_field'          => 'Twitter',
					'object_field'       => 'twitter',
					'sanitize_callback'  => 'sanitize_text_field',
				),
			array(
					'csv_field'          => 'Website',
					'object_field'       => 'website',
					'sanitize_callback'  => 'esc_url_raw',
				),
			array(
					'csv_field'          => 'Bio',
					'object_field'       => 'bio',
					'sanitize_callback'  => 'wp_filter_nohtml_kses',
				),
		);

	/**
	 * Import or update sessions from CSV.
	 */
	public static function import_sessions( $file, $output_callback ) {

		$data = self::get_file_data( $file );

		$output_callback();

		$csv_rows = self::parse_csv_from_string( $data );

		foreach ( $csv_rows as $i => $csv_session ) {

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

					$value = str_replace( '\"', '"', $value );
					$value = str_replace( "\'", "'", $value );

					if ( isset( $session_field['pre_sanitize_callback'] ) && is_callable( $session_field['pre_sanitize_callback'] ) )
						$value = call_user_func_array( $session_field['pre_sanitize_callback'], array( $value, $csv_session ) );

					if ( is_callable( $session_field['sanitize_callback'] ) )
						$new_value = call_user_func_array( $session_field['sanitize_callback'], array( $value ) );
					else
						$new_value = $value;

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
	 * Import or update speakers from CSV.
	 */
	public static function import_speakers( $file, $output_callback ) {

		$data = self::get_file_data( $file );

		$output_callback();

		$csv_rows = self::parse_csv_from_string( $data );

		foreach ( $csv_rows as $i => $csv_speaker ) {

			// Uh oh, someone messed up the rows
			if ( empty( $csv_speaker['Speaker Name'] ) )
				continue;

			// If the speaker doesn't exist, let's create it first.
			$speaker_name = sanitize_text_field( $csv_speaker['Speaker Name'] );
			if ( false === ( $speaker = ONA_Speaker::get_by_name( $speaker_name ) ) ) {
				$post_id = wp_insert_post( array( 'post_type' => ONA_Speaker::$post_type, 'post_title' => $speaker_name, 'post_status' => 'publish' ) );
				$speaker = new ONA_Speaker( $post_id );
				$output_callback( sprintf( "Inserting speaker '%s' as post #%d", $speaker_name, $speaker->get_id() ) );
			} else {
				$output_callback( sprintf( "Updating speaker '%s' (post #%d)", $speaker_name, $speaker->get_id() ) );
			}

			foreach( $csv_speaker as $key => $value ) {

				foreach( self::$speaker_fields as $speaker_field ) {

					if ( $speaker_field['csv_field'] != $key )
						continue;

					$value = str_replace( '\"', '"', $value );
					$value = str_replace( "\'", "'", $value );

					if ( isset( $speaker_field['pre_sanitize_callback'] ) && is_callable( $speaker_field['pre_sanitize_callback'] ) )
						$value = call_user_func_array( $speaker_field['pre_sanitize_callback'], array( $value, $csv_speaker ) );

					if ( is_callable( $speaker_field['sanitize_callback'] ) )
						$new_value = call_user_func_array( $speaker_field['sanitize_callback'], array( $value ) );
					else
						$new_value = $value;

					$get_method = 'get_' . $speaker_field['object_field'];
					$set_method = 'set_' . $speaker_field['object_field'];

					// See whether the values should be updated
					if ( isset( $speaker_field['comparison_callback'] ) && is_callable( $speaker_field['comparison_callback'] ) )
						$update = call_user_func_array( $speaker_field['comparison_callback'], array( $new_value, $speaker->$get_method() ) );
					else
						$update = ( $new_value != $speaker->$get_method() );

					if ( $update ) {
						$speaker->$set_method( $new_value );
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
	 * Compare a value to a term slug
	 * 
	 * @return bool     True to update, false to not
	 */
	public static function compare_to_term_slug( $new_value, $old_value ) {
		if ( ! is_object( $old_value ) )
			return true;

		if ( $new_value != $old_value->slug )
			return true;
		else
			return false;
	}

	/**
	 * Compare two arrays of values
	 * 
	 * @return bool     True to update, false to not
	 */
	public static function compare_to_array_values( $new_value, $old_value ) {
		if ( ! is_array( $old_value ) )
			return true;

		if ( ! is_array( $new_value ) )
			$new_value = array_map( 'trim', explode( ',', $new_value ) );

		if ( array_diff( $old_value, $new_value ) )
			return true;
		else
			return false;
	}

	protected function get_file_data( $file ) {
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
		return $data;
	}

	/**
	 * Parse a string into its CSV
	 * Ugly hack to split the CSV into rows
	 */
	protected static function parse_csv_from_string( $data) {

		$rows = array();
		$do_split = true;
		$last_split = 0;
		$data_array = str_split( $data );
		foreach( $data_array as $i => $character ) {

			if ( '"' == $character && ',' == $data_array[$i-1] )
				$do_split = false;
			else if ( '"' == $character && ',' == $data_array[$i+1] )
				$do_split = true;

			if ( $do_split && PHP_EOL == $character ) {
				$rows[] = ltrim( substr( $data, $last_split, $i - $last_split ) );

				$last_split = $i;
			}
		}

		$keys = str_getcsv( array_shift( $rows ) );

		foreach( $rows as $i => $row ) {
			$values = str_getcsv( $row );

			while( count( $values ) < count( $keys ) ) {
				$values[] = '';
			}

			$rows[$i] = array_combine( $keys, $values );
		}
		return $rows;
	}

	/**
	 * Output any change made to a Session.
	 */
	private static function output_diff( $key, $new_value = false, $output_callback ) {
		if ( is_array( $new_value ) )
			$new_value = implode( ',', $new_value );
		if ( false !== $new_value )
			$output_callback( sprintf( " - %s: %s", $key, $new_value ) );
		else
			$output_callback( sprintf( " - %s: NO CHANGE", $key ) );
	}

	/**
	 * Prepare the speakers field
	 */
	public static function prepare_speakers_field( $new_value, $csv_row ) {

		$speakers = array_map( 'sanitize_text_field', explode( ';', $new_value ) );
		return $speakers;
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