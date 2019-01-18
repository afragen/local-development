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
	 * Holds plugin directory.
	 *
	 * @var string $dir
	 */
	protected $dir;

	/**
	 * Constructor.
	 *
	 * @param string $dir Plugin directory.
	 * @return void
	 */
	public function __construct( $dir ) {
		$this->dir = $dir;
	}
	/**
	 * Let's get started.
	 *
	 * @param  string $dir Main plugin directory.
	 * @return void
	 */
	public function run() {
		add_action(
			'init',
			function () {
				load_plugin_textdomain( 'local-development' );
			}
		);

		// Load Autoloader.
		require_once $this->dir . '/vendor/autoload.php';

		new Init();
	}
}
