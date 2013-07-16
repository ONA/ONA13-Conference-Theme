<?php
/**
 * An ONA session
 */
class ONA_Session {

	public static $post_type = 'ona_session';

	private $_post_id;

	public function __construct( $post_id ) {

		$post = get_post( $post_id );
		if ( ! $post || self::$post_type != $post->post_type )
			return new WP_Error( 'invalid-session', "Session doesn't exist." );

		$this->_post_id = $post->ID;
	}

	/**
	 * Get a session object by its session ID
	 * 
	 * @param int
	 * @return ONA_Session object on success, false on failure
	 */
	public static function get_by_session_id( $session_id ) {
		return self::get_by_meta( 'session_id', $session_id );
	}

	/**
	 * Get a session object by its meta value
	 */
	private static function get_by_meta( $key, $value ) {
		global $wpdb;

		$post_id = $wpdb->get_var( $wpdb->prepare( "SELECT post_id FROM $wpdb->postmeta WHERE meta_key=%s AND meta_value=%s", $key, $value ) );
		if ( ! $post_id )
			return false;

		$session = new ONA_Session( $post_id );
		if ( is_wp_error( $session ) )
			return false;

		return $session;
	}

	/**
	 * Get a value for a post.
	 * 
	 * @param string      $key
	 */
	private function get_value( $key ) {
		if ( ! empty( $this->_post_id ) )
			return get_post( $this->_post_id )->$key;
		else
			return new WP_Error( 'invalid-session', "Session doesn't exist." );
	}

	/**
	 * Set a value for a post.
	 * 
	 * @param string      $key
	 * @param string      $value
	 */
	private function set_value( $key, $value ) {
		global $wpdb;
		if ( empty( $this->_post_id ) )
			return new WP_Error( 'invalid-session', "Session doesn't exist." );

		$wpdb->update( $wpdb->posts, array( $key => $value ), array( 'ID' => $this->_post_id ) );
		clean_post_cache( $this->_post_id );
		return true;
	}

	/**
	 * Get a postmeta value for a post
	 * 
	 * @param $string     $key        Key to look up.
	 * @return mixed      Post meta value
	 */
	private function get_meta( $key ) {
		if ( ! empty( $this->_post_id ) )
			return get_post_meta( $this->_post_id, $key, true );
		else
			return new WP_Error( 'invalid-session', "Session doesn't exist." );
	}

	/**
	 * Set a postmeta value for a post
	 */
	private function set_meta( $key, $value ) {
		if ( ! empty( $this->_post_id ) )
			return update_post_meta( $this->_post_id, $key, $value );
		else
			return new WP_Error( 'invalid-session', "Session doesn't exist." );
	}

	/**
	 * Get a time value from the database
	 *
	 * @param string       $key        Post meta key
	 * @param string       $format     Format for the start time to be returned
	 * @param bool         $gmt        GMT value, instead of localized
	 */
	private function get_meta_time( $key, $format, $gmt ) {

		$timestamp = $this->get_meta( $key );
		if ( is_wp_error( $timestamp ) )
			return $timestamp;

		// time is stored as the GMT timestamp
		if ( false === $gmt )
			$timestamp = $timestamp + ( get_option( 'gmt_offset' ) * 60 * 60 );

		return date( $format, $timestamp );
	}

	/**
	 * Set a time value from the database
	 *
	 * @param string       $key        Post meta key
	 * @param string       $time       New meta time (any strtotime-readable format)
	 * @param bool         $gmt        Whether the time has been adjusted to GMT
	 */
	private function set_meta_time( $key, $time, $gmt ) {

		if ( is_int( $time ) )
			$timestamp = $time;
		else
			$timestamp = strtotime( $time );

		if ( false === $gmt )
			$timestamp = $timestamp - ( get_option( 'gmt_offset' ) * 60 * 60 );

		$this->set_meta( $key, $timestamp );
	}

	/**
	 * Get the post ID of a session
	 * 
	 * @return int
	 */
	public function get_id() {
		return $this->get_value( 'ID' );
	}

	/**
	 * Get the title of a session.
	 * 
	 * @return string
	 */
	public function get_title() {
		return $this->get_value( 'post_title' );
	}

	/**
	 * Set the title of a session
	 */
	public function set_title( $title ) {
		return $this->set_value( 'post_title', $title );
	}

	/**
	 * Get the unique identifer for a session.
	 * 
	 * @return int
	 */
	public function get_session_id() {
		return (int)$this->get_meta( 'session_id' );
	}

	/**
	 * Set the unique identifer for a session
	 *
	 * @param int      $session_id     The session ID
	 */
	public function set_session_id( $session_id ) {
		$this->set_meta( 'session_id', (int)$session_id );
	}

	/**
	 * Get the description of a session.
	 * 
	 * @return string
	 */
	public function get_description() {
		return $this->get_value( 'post_content' );
	}

	/**
	 * Set the description of a session.
	 */
	public function set_description( $description ) {
		return $this->set_value( 'post_content', $description );
	}

	/**
	 * Get the start time of a session
	 * 
	 * @param string       $format     Format for the start time to be returned
	 * @param bool         $gmt        GMT value, instead of localized
	 * @return string      Start time of the session.
	 */
	public function get_start_time( $format = 'U', $gmt = false ) {
		return $this->get_meta_time( 'start_time', $format, $gmt );
	}

	/**
	 * Set the start time of a session
	 *
	 * @param string       $time       New start time for a session
	 * @param bool         $gmt        Whether the time has been adjusted to GMT
	 */
	public function set_start_time( $time, $gmt = false ) {
		$this->set_meta_time( 'start_time', $time, $gmt );
	}

	/**
	 * Get the end time of a session
	 * 
	 * @param string       $format     Format for the end time to be returned
	 * @param bool         $gmt        GMT value, instead of localized
	 * @return string      End time of the session.
	 */
	public function get_end_time( $format = 'U', $gmt = false ) {
		return $this->get_meta_time( 'end_time', $format, $gmt );
	}

	/**
	 * Set the end time of a session
	 *
	 * @param string       $time       New end time for a session
	 * @param bool         $gmt        Whether the time has been adjusted to GMT
	 */
	public function set_end_time( $time, $gmt = false ) {
		$this->set_meta_time( 'end_time', $time, $gmt );
	}
	
}