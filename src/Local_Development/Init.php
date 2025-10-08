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
	 * Options variable.
	 *
	 * @var array
	 */
	private $config;

	/**
	 * Init constructor.
	 */
	public function __construct() {
		$config       = get_site_option( 'local_development', [] );
		$this->config = $config;

		// Skip on heartbeat or if no saved settings.
		// phpcs:ignore WordPress.Security.NonceVerification.Missing
		if ( ( isset( $_POST['action'] ) && 'heartbeat' === $_POST['action'] ) ) {
			return false;
		}

		return $this;
	}

	/**
	 * Load hooks.
	 *
	 * @return $this
	 */
	public function load_hooks() {
		$config = $this->config;
		Singleton::get_instance( 'Settings', $this, $config )->load_hooks();

		$config       = $this->get_vcs_checkouts( $config );
		$config       = $this->modify_options( $config );
		$this->config = $config;

		Singleton::get_instance( 'Plugins', $this, $config )->run();
		Singleton::get_instance( 'Themes', $this, $config )->run();
		Singleton::get_instance( 'Extras', $this, $config )->run();

		return $this;
	}

	/**
	 * Set wp-config environment constant.
	 *
	 * @return void
	 */
	private function set_environment() {
		// Get `WP_ENVIRONMENT_TYPE`.
		$environment_type = defined( 'WP_ENVIRONMENT_TYPE' ) ? \WP_ENVIRONMENT_TYPE : false;

		// For WP 5.5 setting environment type.
		if ( isset( $this->config['extras']['environment_type'] )
			&& $environment_type !== $this->config['extras']['environment_type']
		) {
			$config_args = [
				'raw'       => false,
				'normalize' => true,
			];
			if ( false === strpos( file_get_contents( $this->get_config_path() ), "/* That's all, stop editing!" ) ) {
				$config_args = array_merge(
					$config_args,
					[
						'anchor'    => "dirname( __FILE__ ) . '/' );\n}",
						'placement' => 'after',
					]
				);
			}
			try {
				$config_transformer = new \WPConfigTransformer( $this->get_config_path() );
				$config_transformer->remove( 'constant', 'WP_ENVIRONMENT_TYPE' );

				// Local.app adds constant in local-bootstrap.php file.
				if ( ! defined( 'WP_ENVIRONMENT_TYPE' ) ) {
					$config_transformer->update( 'constant', 'WP_ENVIRONMENT_TYPE', $this->config['extras']['environment_type'], $config_args );
				}
			} catch ( \Exception $e ) {
				$messsage = 'Caught Exception: \Fragen\Local_Development\Init::__construct() - ' . $e->getMessage();
				// error_log( $messsage );
				wp_die( esc_html( $messsage ) );
			}
		}
	}

	/**
	 * Remove constants from wp-config.php file.
	 *
	 * @uses https://github.com/wp-cli/wp-config-transformer
	 *
	 * @param  array $remove Constants to remove from wp-config.php.
	 * @return void
	 */
	public function remove_constants( $remove ) {
		try {
			$config_transformer = new \WPConfigTransformer( $this->get_config_path() );
			foreach ( array_keys( $remove ) as $constant ) {
				$config_transformer->remove( 'constant', strtoupper( $constant ) );
			}
		} catch ( \Exception $e ) {
			$messsage = 'Caught Exception: \Fragen\Local_Development\Init::remove_constants() - ' . $e->getMessage();
			// error_log( $messsage );
			wp_die( esc_html( $messsage ) );
		}
	}

	/**
	 * Let's get started.
	 *
	 * @return void
	 */
	public function run() {
		$this->set_environment();
		( new Base( $this->config ) )->load_hooks();
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
					// phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged
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
		if ( is_plugin_active( 'git-updater/git-updater.php' ) ) {
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
	public function get_config_path() {
		$config_path = ABSPATH . 'wp-config.php';

		if ( ! file_exists( $config_path ) ) {
			// phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged
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
