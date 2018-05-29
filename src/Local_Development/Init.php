<?php

namespace Fragen\Local_Development;

use Fragen\Singleton;

/*
 * Exit if called directly.
 */
if (! defined('WPINC')) {
	die;
}

/**
 * Class Init
 *
 */
class Init {
	/**
	 * Init constructor.
	 */
	public function __construct() {
		$config = get_site_option('local_development');
		add_action( 'init', function(){
			Singleton::get_instance('Settings', $this)->load_hooks();
			Singleton::get_instance('Plugins', $this)->run();
			Singleton::get_instance('Themes', $this)->run();
			Singleton::get_instance('Extras', $this)->run();
		});

		/*
		 * Skip on heartbeat or if no saved settings.
		 */
		if ((isset($_POST['action']) && 'heartbeat' === $_POST['action']) || ! $config) {
			return false;
		}
		new Base($config);
	}
}
