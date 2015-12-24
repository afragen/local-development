<?php

namespace Fragen\Local_Development_Upgrade_Warning;

/*
 * Exit if called directly.
 */
if ( ! defined( 'WPINC' ) ) {
	die;
}

class Settings {

	protected $plugins;
	protected $themes;

	public function __construct() {
		add_action( 'init', array( &$this, 'init' ) );

	}

	public function init() {
		$this->plugins = get_plugins();
		$this->themes  = wp_get_themes( array( 'errors' => null ) );

		foreach ( array_keys( $this->plugins ) as $slug ) {
			$plugin_arr[ $slug ] = $this->plugins[ $slug ]['Name'];
		}

		$this->plugins = $plugin_arr;
	}

}
