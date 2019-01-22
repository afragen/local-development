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

use Fragen\Singleton;

/*
 * Exit if called directly.
 */
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Class Init
 */
class Init {
	/**
	 * Init constructor.
	 */
	public function __construct() {
		$config = get_site_option( 'local_development' );
		add_action(
			'init',
			function () use ( $config ) {
				Singleton::get_instance( 'Settings', $this, $config )->load_hooks();
				Singleton::get_instance( 'Plugins', $this, $config )->run();
				Singleton::get_instance( 'Themes', $this, $config )->run();
				Singleton::get_instance( 'Extras', $this, $config )->run();
			}
		);

		/*
		 * Skip on heartbeat or if no saved settings.
		 */
		if ( ( isset( $_POST['action'] ) && 'heartbeat' === $_POST['action'] ) || ! $config ) {
			return false;
		}
		( new Base( $config ) )->load_hooks();
	}
}
