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
	 * Get a session object by its slug
	 * 
	 * @param string
	 * @return ONA_Session object on success, false on failure
	 */
	public static function get_by_slug( $slug ) {
		return self::get_by_value( 'post_name', $slug );
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
	 * Get a session object by its post value
	 */
	private static function get_by_value( $key, $value ) {
		global $wpdb;

		$post_id = $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE {$key}=%s AND post_type=%s", $value, self::$post_type ) );
		if ( ! $post_id || count( $post_id ) > 1 )
			return false;

		$session = new ONA_Session( $post_id );
		if ( is_wp_error( $session ) )
			return false;

		return $session;
	}

	/**
	 * Get a session object by its meta value
	 */
	private static function get_by_meta( $key, $value ) {
		global $wpdb;

		$post_id = $wpdb->get_var( $wpdb->prepare( "SELECT post_id FROM $wpdb->postmeta WHERE meta_key=%s AND meta_value=%s", $key, $value ) );
		if ( ! $post_id || count( $post_id ) > 1 )
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
	 * Get the unique slug identifier for a session
	 * 
	 * @return string
	 */
	public function get_slug() {
		return $this->get_value( 'post_name' );
	}

	/**
	 * Set the unique slug identifier for a session
	 * 
	 * @param string
	 */
	public function set_slug( $slug ) {
		$this->set_value( 'post_name', $slug );
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
	 * Get the RebelMouse value
	 * 
	 * @return string
	 */
	public function get_rebelmouse() {
		return $this->get_meta( 'rebelmouse' );
	}

	/**
	 * Set the RebelMouse value
	 * 
	 * @param string
	 */
	public function set_rebelmouse( $rebelmouse ) {
		$this->set_meta( 'rebelmouse', $rebelmouse );
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

	/**
	 * Get the type for this session
	 * 
	 * @return object|false
	 */
	public function get_session_type(){
		$types = get_the_terms( $this->get_id(), 'session-type' );
		if ( ! empty( $types ) && ! is_wp_error( $types ) )
			return array_shift( $types );
		else
			return false;
	}

	/**
	 * Get the type name for this session
	 * 
	 * @return string
	 */
	public function get_session_type_name() {
		if ( $type = $this->get_session_type() )
			return $type->name;
		else
			return '';
	}

	/**
	 * Set the type for this session
	 * 
	 * @param string
	 */
	public function set_session_type( $slug ) {
		wp_set_object_terms( $this->get_id(), array( $slug ), 'session-type' );
	}

	/**
	 * Get the format for this session
	 * 
	 * @return object|false
	 */
	public function get_session_format(){
		$formats = get_the_terms( $this->get_id(), 'session-format' );
		if ( ! empty( $formats ) && ! is_wp_error( $formats ) )
			return array_shift( $formats );
		else
			return false;
	}

	/**
	 * Get the format name for this session
	 * 
	 * @return string
	 */
	public function get_session_format_name() {
		if ( $format = $this->get_session_format() )
			return $format->name;
		else
			return '';
	}

	/**
	 * Set the format for this session
	 * 
	 * @param string
	 */
	public function set_session_format( $slug ) {
		wp_set_object_terms( $this->get_id(), array( $slug ), 'session-format' );
	}

	/**
	 * Get the room for this session
	 * 
	 * @return object|false
	 */
	public function get_room(){
		$rooms = get_the_terms( $this->get_id(), 'session-room' );
		if ( ! empty( $rooms ) && ! is_wp_error( $rooms ) )
			return array_shift( $rooms );
		else
			return false;
	}

	/**
	 * Get the room name for this session
	 * 
	 * @return string
	 */
	public function get_room_name() {
		if ( $room = $this->get_room() )
			return $room->name;
		else
			return '';
	}

	/**
	 * Set the room for this session
	 * 
	 * @param string
	 */
	public function set_room( $slug ) {
		wp_set_object_terms( $this->get_id(), array( $slug ), 'session-room' );
	}

	/**
	 * Get the hashtag for a session
	 * 
	 * @return string
	 */
	public function get_hashtag() {
		return $this->get_meta( 'hashtag' );
	}

	/**
	 * Set the hashtag for a session
	 * 
	 * @param string
	 */
	public function set_hashtag( $hashtag ) {
		return $this->set_meta( 'hashtag', $hashtag );
	}

	/**
	 * Get the Speakers for a session
	 * 
	 * @return string
	 */
	public function get_speakers() {
		if ( $speakers = $this->get_meta( 'speakers' ) )
			return $speakers;
		else
			return array();
	}

	/**
	 * Set the Speakers for a session
	 * 
	 * @param string
	 */
	public function set_speakers( $speakers ) {
		$this->set_meta( 'speakers', $speakers );
	}

	/**
	 * Get the tags for a session
	 * 
	 * @return array
	 */
	public function get_tags() {
		return wp_list_pluck( $this->get_tag_objects(), 'name' );
	}

	/**
	 * Get the tag objects for a session
	 * 
	 * @return array
	 */
	public function get_tag_objects() {
		if ( $tags = get_the_terms( $this->get_id(), 'post_tag' ) )
			return $tags;
		else
			return array();
	}

	/**
	 * Set the tags for a session
	 */
	public function set_tags( $tag_names ) {
		wp_set_post_terms( $this->get_id(), $tag_names, 'post_tag' );
	}
	
}