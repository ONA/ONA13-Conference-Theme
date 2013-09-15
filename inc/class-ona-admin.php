<?php
/**
 * Admin enhancements
 */
class ONA_Admin {

	function __construct() {

		add_action( 'admin_bar_menu', array( $this, 'action_admin_bar_menu' ), 99 );
		add_action( 'wp_ajax_ona_run_importer', array( $this, 'handle_ajax_run_importer' ) );

	}

	/**
	 * Add a "Run Import" link to the admin bar
	 */
	public function action_admin_bar_menu() {
		global $wp_admin_bar;

		if ( ! current_user_can( 'manage_options' ) )
			return;

		$query_args = array(
			'action' => 'ona_run_importer',
			'nonce'  => wp_create_nonce( 'ona-importer' ),
			);
		$href = add_query_arg( $query_args, admin_url( 'admin-ajax.php' ) );

		$args = array(
			'id'      => 'ona-run-importer',
			'title'   => "Run Importer",
			'href'    => $href,
			);
		$wp_admin_bar->add_node( $args );
	}

	/**
	 * Handle an AJAX request to run the importer
	 */
	public function handle_ajax_run_importer() {

		if ( ! wp_verify_nonce( $_GET['nonce'], 'ona-importer' )
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