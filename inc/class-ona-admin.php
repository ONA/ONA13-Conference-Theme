<?php
/**
 * Admin enhancements
 */
class ONA_Admin {

	function __construct() {

		add_action( 'admin_bar_menu', array( $this, 'action_admin_bar_menu' ), 99 );
		add_action( 'wp_ajax_ona_run_speaker_importer', array( $this, 'handle_ajax_run_speaker_importer' ) );
		add_action( 'wp_ajax_ona_run_session_importer', array( $this, 'handle_ajax_run_session_importer' ) );

	}

	/**
	 * Add a "Run Import" link to the admin bar
	 */
	public function action_admin_bar_menu() {
		global $wp_admin_bar;

		if ( ! current_user_can( 'manage_options' ) )
			return;

		$args = array(
			'id'      => 'ona-importer',
			'title'   => 'Importer',
			);
		$wp_admin_bar->add_node( $args );

		$query_args = array(
			'action' => 'ona_run_speaker_importer',
			'nonce'  => wp_create_nonce( 'ona-speaker-importer' ),
			);
		$href = add_query_arg( $query_args, admin_url( 'admin-ajax.php' ) );

		$args = array(
			'id'      => 'ona-speaker-importer',
			'title'   => "Import Speakers",
			'href'    => $href,
			'parent'  => 'ona-importer',
			);
		$wp_admin_bar->add_node( $args );

		$query_args = array(
			'action' => 'ona_run_session_importer',
			'nonce'  => wp_create_nonce( 'ona-session-importer' ),
			);
		$href = add_query_arg( $query_args, admin_url( 'admin-ajax.php' ) );

		$args = array(
			'id'      => 'ona-session-importer',
			'title'   => "Import Sessions",
			'href'    => $href,
			'parent'  => 'ona-importer',
			);
		$wp_admin_bar->add_node( $args );
	}

	/**
	 * Handle an AJAX request to run the speaker importer
	 */
	public function handle_ajax_run_speaker_importer() {

		if ( ! wp_verify_nonce( $_GET['nonce'], 'ona-speaker-importer' )
		|| ! current_user_can( 'manage_options' ) )
			wp_die( "You shouldn't be here..." );

		ONA13_Importer::import_speakers( 'https://docs.google.com/spreadsheet/pub?key=0AgtyAD_1PrgVdHdtZnRnTDR0Wk53eElicUMyRnZTNFE&single=true&gid=0&output=csv' , array( $this, 'output') );
		exit;
	}

	/**
	 * Handle an AJAX request to run the session importer
	 */
	public function handle_ajax_run_session_importer() {

		if ( ! wp_verify_nonce( $_GET['nonce'], 'ona-session-importer' )
		|| ! current_user_can( 'manage_options' ) )
			wp_die( "You shouldn't be here..." );

		ONA13_Importer::import_sessions( 'https://docs.google.com/spreadsheet/pub?key=0AgtyAD_1PrgVdFU4OGtsdUc5cHRSYlBGVE9qdUF4N3c&single=true&gid=0&output=csv' , array( $this, 'output') );
		exit;
	}

	/**
	 * Render output for the importer
	 */
	public function output( $message = '', $type = '' ) {
		echo $message . '<br />';
	}


}

global $ona_admin;
$ona_admin = new ONA_Admin;