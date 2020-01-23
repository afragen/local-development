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
		$config = get_site_option( 'local_development', [] );
		$config = $this->get_vcs_checkouts( $config );
		add_action(
			'init',
			function () use ( $config ) {
				Singleton::get_instance( 'Settings', $this, $config )->load_hooks();
				Singleton::get_instance( 'Plugins', $this, $config )->run();
				Singleton::get_instance( 'Themes', $this, $config )->run();
				Singleton::get_instance( 'Extras', $this, $config )->run();
			}
		);

		// Skip on heartbeat or if no saved settings.
		// phpcs:ignore WordPress.Security.NonceVerification.Missing
		if ( ( isset( $_POST['action'] ) && 'heartbeat' === $_POST['action'] ) || ! $config ) {
			return false;
		}
		( new Base( $config ) )->load_hooks();
	}

	/**
	 * Get VCS checkouts and add automatically to config.
	 *
	 * @param array $config Plugins options.
	 */
	private function get_vcs_checkouts( $config ) {
		$plugins_themes = Singleton::get_instance( 'Settings', $this )->init();
		$vcs_dirs       = [ '.git', '.svn', '.hg', '.bzr' ];

		foreach ( [ 'plugins', 'themes' ] as $type ) {
			foreach ( array_keys( $plugins_themes[ $type ] ) as $file ) {
				$wp_path  = 'plugins' === $type ? wp_normalize_path( WP_PLUGIN_DIR ) : wp_normalize_path( get_theme_root() );
				$slug     = 'plugins' === $type ? dirname( $file ) : $file;
				$filepath = untrailingslashit( "$wp_path/$slug" );

				foreach ( $vcs_dirs as $vcs_dir ) {
					$is_vcs = @is_dir( "$filepath/$vcs_dir" );
					if ( $is_vcs ) {
						$config[ $type ][ $file ] = '-1';
						break;
					}
				}
			}
		}

		return $config;
	}
}
