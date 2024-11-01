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
	 * @param string $file File name.
	 *
	 * @return void
	 */
	public function run( $file ) {
		( new Init() )->load_hooks()->run();
		\register_deactivation_hook( $file, [ $this, 'deactivate' ] );
	}

	/**
	 * Remove constants on deactive.
	 *
	 * @return void
	 */
	public function deactivate() {
		( new Init() )->remove_constants( [ 'wp_environment_type' => null ] );
	}
}
