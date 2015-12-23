<?php
/**
 * Plugin Name:       Local Development Upgrade Warning
 * Plugin URI:        https://github.com/afragen/local-development-upgrade-warning
 * Author:            Andy Fragen
 * Author URI:        http://thefragens.com/
 * Description:       A plugin to place warning notices for plugins or themes that are in active development.
 * Version:           0.1
 * Text Domain:       local-development-upgrade-warning
 * License:           GNU General Public License v2
 * License URI:       http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 * Network:           true
 * GitHub Plugin URI: https://github.com/afragen/local-development-upgrade-warning
 * Requires PHP:      5.3
 */

// Load textdomain
load_plugin_textdomain( 'local-development-upgrade-warning', false, dirname( __FILE__ ) . '/languages' );

/**
 * Class Local_Development_Upgrade_Warning
 */
class Local_Development_Upgrade_Warning {

	/**
	 * Static to hold slugs of plugins under development.
	 * @var
	 */
	protected static $plugins;

	/**
	 * Static to hold slugs themes under development.
	 * @var
	 */
	protected static $themes;

	/**
	 * Static to hold message.
	 * @var
	 */
	protected static $message;

	/**
	 * Local_Development_Upgrade_Warning constructor.
	 *
	 * @param $config
	 */
	public function __construct( $config ) {
		self::$plugins = $config['plugins'];
		self::$themes  = $config['themes'];
		self::$message = esc_html__( 'This is a local development directory.', 'local-development-upgrade-warning' );

		add_filter( 'pre_set_site_transient_update_plugins', array( &$this, 'transient_update' ), 15, 1 );
		add_filter( 'plugin_row_meta', array( &$this, 'row_meta' ), 15, 2 );

		add_filter( 'pre_set_site_transient_update_themes', array( &$this, 'transient_update' ), 15, 1 );
		add_filter( 'theme_row_meta', array( &$this, 'row_meta' ), 15, 2 );

		if ( ! is_multisite() ) {
			add_filter( 'wp_prepare_themes_for_js', array( &$this, 'append_theme_description' ), 15, 1 );
		}
	}

	/**
	 * Add upgrade_notice to transient array.
	 *
	 * @param $transient
	 *
	 * @return mixed
	 */
	public function transient_update( $transient ) {
		$plugin = null;
		$theme  = null;

		if ( empty( $transient->response ) ) {
			return $transient;
		}

		foreach ( $transient->response as $update ) {
			if ( is_object( $update ) && isset( $update->slug ) &&
			     ( in_array( $update->slug, self::$plugins ) )
			) {
				$plugin = $update->plugin;
				$transient->response[ $plugin ]->upgrade_notice = self::$message;
			}

			if ( is_array( $update ) && isset( $update['theme'] ) &&
			     in_array( $update['theme'], self::$themes )
			) {
				$theme = $update['theme'];
				$transient->response[ $theme ]['upgrade_notice'] = self::$message;
			}
		}

		return $transient;
	}

	/**
	 * Add an additional element to the row meta links.
	 *
	 * @param $links
	 * @param $file
	 *
	 * @return array
	 */
	public function row_meta( $links, $file ) {
		if ( in_array( dirname( $file ), self::$plugins ) ||
		     in_array( $file, self::$themes )
		) {
			$links[] = '<strong>' . self::$message . '</strong>';
		}

		return $links;
	}

	/**
	 * Add a message to the single install theme action.
	 *
	 * @param $prepared_themes
	 *
	 * @return array
	 */
	public function append_theme_description( $prepared_themes ) {
		foreach ( $prepared_themes as $theme ) {
			if ( in_array( $theme['id'], self::$themes ) ) {
				$prepared_themes[ $theme['id'] ]['description'] .= '<p><strong>' . self::$message . '</strong></p>';
			}
		}

		return $prepared_themes;
	}

}
