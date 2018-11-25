<?php

namespace Fragen\Local_Development;

/*
 * Exit if called directly.
 */
if ( ! defined( 'WPINC' ) ) {
	die;
}

( new Bootstrap() )->run( LOCAL_DEVELOPMENT_DIR );

/**
 * Class Bootstrap
 */
class Bootstrap {

	/**
	 * Let's get started.
	 *
	 * @param string $dir Main plugin directory.
	 * @return void
	 */
	public function run( $dir ) {
		add_action(
			'init',
			function() {
				load_plugin_textdomain( 'local-development' );
			}
		);

		// Load Autoloader.
		require_once $dir . '/vendor/autoload.php';

		new Init();
	}
}
