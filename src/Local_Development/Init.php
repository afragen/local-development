<?php

namespace Fragen\Local_Development;

/*
 * Exit if called directly.
 */
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Class Init
 *
 * @package Fragen\Local_Development
 */
class Init {

	/**
	 * Init constructor.
	 */
	public function __construct() {

		$config = get_site_option( 'local_development' );
		new Settings();

		/*
		 * Skip on heartbeat or if no saved settings.
		 */
		if ( ( isset( $_POST['action'] ) && 'heartbeat' === $_POST['action'] ) || ! $config ) {
			return false;
		}
		new Base( $config );
	}
}
