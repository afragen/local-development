<?php

namespace Fragen\Local_Development_Upgrade_Warning;

/*
 * Exit if called directly.
 */
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Class Init
 *
 * @package Fragen\Local_Development_Upgrade_Warning
 */
class Init {

	/**
	 * Init constructor.
	 */
	public function __construct() {
		$config = get_site_option( 'local_development_upgrade_warning' );

		new Settings();
		new Base( $config );
	}
}
