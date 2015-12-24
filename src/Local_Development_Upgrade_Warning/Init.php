<?php

namespace Fragen\Local_Development_Upgrade_Warning;

/*
 * Exit if called directly.
 */
if ( ! defined( 'WPINC' ) ) {
	die;
}

class Init {

	public function __construct() {
		/**
		 * Create array for plugins/themes that are being locally developed but might be forks.
		 */
		$config['plugins'] = array(
			'airplane-mode',
			'category-colors-options',
			'github-updater',
			'losrobles-governance',
			'drmc-medical-staff-governance',
			'pods',
			'the-events-calendar-category-colors',
			'the-events-calendar-pro-alarm',
			'the-events-calendar-user-css',
			'wp-polls',
			'local-development-upgrade-warning',
			'test-plugin2',
		);

		$config['themes'] = array(
			'losrobles-theme',
			'drmcmedstaff',
			'ipanema-theme',
		);

		new Settings();
		new Base( $config );
	}
}
