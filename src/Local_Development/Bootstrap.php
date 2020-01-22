<?php
/**
 * Local Development
 *
 * @package local-development
 * @author Andy Fragen <andy@thefragens.com>
 * @license GPLv2
 * @link https://github.com/afragen/local-development
 */

namespace Fragen\Local_Development;

/*
 * Exit if called directly.
 */
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Class Bootstrap
 */
class Bootstrap {
	/**
	 * Let's get started.
	 *
	 * @return void
	 */
	public function run() {
		add_action(
			'init',
			function () {
				load_plugin_textdomain( 'local-development' );
			}
		);

		new Init();
	}
}
