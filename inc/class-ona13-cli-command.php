<?php
/**
 * A CLI command for ONA13
 */
class ONA13_CLI_Command extends WP_CLI_Command {

	/**
	 * Import or update sessions from CSV.
	 * 
	 * @subcommand import-sessions
	 * @synopsis <file>
	 */
	public function import_sessions( $args ) {

		list( $file ) = $args;

		ONA13_Importer::import_sessions( $file, array( $this, 'output') );
	}

	/**
	 * Output some shiz
	 */
	static public function output( $message = '', $type = 'normal' ) {

		switch ( $type ) {
			case 'error':
			case 'success':
				WP_CLI::$type( $message );
				break;
			
			default:
				WP_CLI::line( $message );
				break;
		}

	}

}
WP_CLI::add_command( 'ona13', 'ONA13_CLI_Command' );