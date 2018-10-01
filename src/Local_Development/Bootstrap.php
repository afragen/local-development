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

		// Plugin namespace root.
		$root = array( 'Fragen\\Local_Development' => $dir . '/src/Local_Development' );

		// Plugin extra classes.
		$extra_classes = array( 'Fragen\Singleton' => $dir . '/src/Singleton.php' );

		// Load Autoloader.
		require_once $dir . '/src/Autoloader.php';
		new \Fragen\Autoloader( $root, $extra_classes );

		new Init();
	}
}
