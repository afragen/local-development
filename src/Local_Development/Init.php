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
		$config = $this->modify_options( $config );
		add_action(
			'init',
			function () use ( $config ) {
				Singleton::get_instance( 'Settings', $this, $config )->load_hooks();
				Singleton::get_instance( 'Plugins', $this, $config )->run();
				Singleton::get_instance( 'Themes', $this, $config )->run();
				Singleton::get_instance( 'Extras', $this, $config )->run();
			}
		);

		// For WP 5.5 setting environment type.
		if ( isset( $config['extras']['environment_type'] ) ) {
			$config_args        = [
				'raw'       => false,
				'normalize' => true,
			];
			$config_transformer = new \WPConfigTransformer( $this->get_config_path() );
			$config_transformer->update( 'constant', 'WP_ENVIRONMENT_TYPE', $config['extras']['environment_type'], $config_args );
		}

		// Skip on heartbeat or if no saved settings.
		// phpcs:ignore WordPress.Security.NonceVerification.Missing
		if ( ( isset( $_POST['action'] ) && 'heartbeat' === $_POST['action'] ) ) {
			return false;
		}
		( new Base( $config ) )->load_hooks();
	}

	/**
	 * Get VCS checkouts and add automatically to config.
	 *
	 * @param array $config Plugin options.
	 */
	private function get_vcs_checkouts( $config ) {
		$plugins_themes = Singleton::get_instance( 'Settings', $this )->init();
		$vcs_dirs       = [ '.git', '.svn', '.hg', '.bzr' ];

		foreach ( [ 'plugins', 'themes' ] as $type ) {
			foreach ( array_keys( $plugins_themes[ $type ] ) as $file ) {
				$wp_path  = 'plugins' === $type ? wp_normalize_path( WP_PLUGIN_DIR ) : wp_normalize_path( get_theme_root() );
				$slug     = 'plugins' === $type ? dirname( $file ) : $file;
				$filepath = untrailingslashit( "{$wp_path}/{$slug}" );

				foreach ( $vcs_dirs as $vcs_dir ) {
					$is_vcs = @is_dir( "{$filepath}/{$vcs_dir}" );
					if ( $is_vcs ) {
						$config[ $type ][ $file ] = '-1';
						break;
					}
				}
			}
		}

		return $config;
	}

	/**
	 * Modify options.
	 *
	 * @param array $config Plugin options.
	 *
	 * @return array
	 */
	private function modify_options( $config ) {
		if ( is_plugin_active( 'github-updater/github-updater.php' ) ) {
			$config['extras']['enable_git_icons'] = '-1';
		}
		if ( defined( 'WP_DISABLE_FATAL_ERROR_HANDLER' ) && WP_DISABLE_FATAL_ERROR_HANDLER ) {
			$config['extras']['bypass_fatal_error_handler'] = '-1';
		}
		return $config;
	}

	/**
	 * Get the `wp-config.php` file path.
	 *
	 * The config file may reside one level above ABSPATH but is not part of another installation.
	 *
	 * @see wp-load.php#L26-L42
	 *
	 * @return string $config_path
	 */
	private function get_config_path() {
		$config_path = ABSPATH . 'wp-config.php';

		if ( ! file_exists( $config_path ) ) {
			if ( @file_exists( dirname( ABSPATH ) . '/wp-config.php' ) && ! @file_exists( dirname( ABSPATH ) . '/wp-settings.php' ) ) {
				$config_path = dirname( ABSPATH ) . '/wp-config.php';
			}
		}


		/**
		 * Filter the config file path.
		 *
		 * @since 2.5.8
		 *
		 * @param string $config_path
		 */
		return apply_filters( 'local_development_config_path', $config_path );
	}
}
