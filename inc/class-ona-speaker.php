<?php
/**
 * An ONA Speaker
 */
class ONA_Speaker {

	public static $post_type = 'ona_speaker';

	private $_post_id;

	public function __construct( $post_id ) {

		$post = get_post( $post_id );
		if ( ! $post || self::$post_type != $post->post_type )
			return new WP_Error( 'invalid-speaker', "Speaker doesn't exist." );

		$this->_post_id = $post->ID;
	}

	/**
	 * Get a speaker object by its name
	 * 
	 * @param string
	 * @return ONA_Speaker object on success, false on failure
	 */
	public static function get_by_name( $name ) {
		return self::get_by_value( 'post_title', $name );
	}

	/**
	 * Get a speaker object by its slug
	 * 
	 * @param string
	 * @return ONA_Speaker object on success, false on failure
	 */
	public static function get_by_slug( $slug ) {
		return self::get_by_value( 'post_name', $slug );
	}

	/**
	 * Get a speaker object by its post value
	 */
	private static function get_by_value( $key, $value ) {
		global $wpdb;

		$post_id = $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE {$key}=%s AND post_type=%s", $value, self::$post_type ) );
		if ( ! $post_id || count( $post_id ) > 1 )
			return false;

		$speaker = new ONA_Speaker( $post_id );
		if ( is_wp_error( $speaker ) )
			return false;

		return $speaker;
	}

	/**
	 * Get a speaker object by its meta value
	 */
	private static function get_by_meta( $key, $value ) {
		global $wpdb;

		$post_id = $wpdb->get_var( $wpdb->prepare( "SELECT post_id FROM $wpdb->postmeta WHERE meta_key=%s AND meta_value=%s", $key, $value ) );
		if ( ! $post_id || count( $post_id ) > 1 )
			return false;

		$speaker = new ONA_Speaker( $post_id );
		if ( is_wp_error( $speaker ) )
			return false;

		return $speaker;
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
			return new WP_Error( 'invalid-speaker', "Speaker doesn't exist." );
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
			return new WP_Error( 'invalid-speaker', "Speaker doesn't exist." );

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
			return new WP_Error( 'invalid-speaker', "Speaker doesn't exist." );
	}

	/**
	 * Set a postmeta value for a post
	 */
	private function set_meta( $key, $value ) {
		if ( ! empty( $this->_post_id ) )
			return update_post_meta( $this->_post_id, $key, $value );
		else
			return new WP_Error( 'invalid-speaker', "Speaker doesn't exist." );
	}

	/**
	 * Get the post ID of a speaker
	 * 
	 * @return int
	 */
	public function get_id() {
		return $this->get_value( 'ID' );
	}

	/**
	 * Get the name of a speaker.
	 * 
	 * @return string
	 */
	public function get_name() {
		return $this->get_value( 'post_title' );
	}

	/**
	 * Set the name of a speaker
	 */
	public function set_name( $name ) {
		return $this->set_value( 'post_title', $name );
	}

	/**
	 * Get the title of a speaker.
	 * 
	 * @return string
	 */
	public function get_title() {
		return $this->get_meta( 'title' );
	}

	/**
	 * Set the title of a speaker
	 */
	public function set_title( $title ) {
		return $this->set_meta( 'title', $title );
	}

	/**
	 * Get the organization of a speaker.
	 * 
	 * @return string
	 */
	public function get_organization() {
		return $this->get_meta( 'organization' );
	}

	/**
	 * Set the organization of a speaker
	 */
	public function set_organization( $organization ) {
		return $this->set_meta( 'organization', $organization );
	}

	/**
	 * Get the email of a speaker.
	 * 
	 * @return string
	 */
	public function get_email() {
		return $this->get_meta( 'email' );
	}

	/**
	 * Set the email of a speaker
	 */
	public function set_email( $email ) {
		return $this->set_meta( 'email', $email );
	}

	/**
	 * Get the Twitter of a speaker.
	 * 
	 * @return string
	 */
	public function get_twitter() {
		return $this->get_meta( 'twitter' );
	}

	/**
	 * Set the twitter of a speaker
	 */
	public function set_twitter( $twitter ) {
		return $this->set_meta( 'twitter', $twitter );
	}

	/**
	 * Get the website of a speaker.
	 * 
	 * @return string
	 */
	public function get_website() {
		return $this->get_meta( 'website' );
	}

	/**
	 * Set the website of a speaker
	 */
	public function set_website( $website ) {
		return $this->set_meta( 'website', $website );
	}

	/**
	 * Get the unique slug identifier for a speaker
	 * 
	 * @return string
	 */
	public function get_slug() {
		return $this->get_value( 'post_name' );
	}

	/**
	 * Set the unique slug identifier for a speaker
	 * 
	 * @param string
	 */
	public function set_slug( $slug ) {
		$this->set_value( 'post_name', $slug );
	}

	/**
	 * Get the bio of a speaker.
	 * 
	 * @return string
	 */
	public function get_bio() {
		return $this->get_value( 'post_content' );
	}

	/**
	 * Set the bio of a speaker.
	 */
	public function set_bio( $bio ) {
		return $this->set_value( 'post_content', $bio );
	}

	/**
	 * Get the profile url of a speaker.
	 * 
	 * @return string
	 */
	public function get_profile_url() {
		if ( $src = wp_get_attachment_image_src( get_post_thumbnail_id( $this->get_id() ), 'full' ) )
			return $src[0];
		else
			return '';
	}

	/**
	 * Set the profile url of a speaker.
	 */
	public function set_profile_url( $file ) {

		if ( empty( $file ) ) {
			delete_post_thumbnail( $this->get_id() );
			return;
		}

		require_once(ABSPATH . 'wp-admin/includes/media.php');
		require_once(ABSPATH . 'wp-admin/includes/file.php');
		require_once(ABSPATH . 'wp-admin/includes/image.php');

		$tmp = download_url( $file );

		// Set variables for storage
		// fix file filename for query strings
		preg_match( '/[^\?]+\.(jpe?g|jpe|gif|png)\b/i', $file, $matches );
		$file_array['name'] = basename($matches[0]);
		$file_array['tmp_name'] = $tmp;

		// If error storing temporarily, unlink
		if ( is_wp_error( $tmp ) ) {
			@unlink($file_array['tmp_name']);
			$file_array['tmp_name'] = '';
		}
	
		// do the validation and storage stuff
		$id = media_handle_sideload( $file_array, $this->get_id(), '' );
		// If error storing permanently, unlink
		if ( is_wp_error($id) ) {
			@unlink($file_array['tmp_name']);
			return $id;
		}
		set_post_thumbnail( $this->get_id(), $id );
	}
	
}