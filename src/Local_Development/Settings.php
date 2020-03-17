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

/*
 * Exit if called directly.
 */
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Class Settings
 */
class Settings {
	/**
	 * Holds plugin data.
	 *
	 * @var $plugins
	 */
	protected $plugins;

	/**
	 * Holds theme data.
	 *
	 * @var $themes
	 */
	protected $themes;

	/**
	 * Holds plugin settings.
	 *
	 * @var $options
	 */
	protected static $options;

	/**
	 * Holds the plugin basename.
	 *
	 * @var string $plugin_slug
	 */
	private $plugin_slug = 'local-development/local-development.php';

	/**
	 * Settings constructor.
	 *
	 * @param array $config Saved options.
	 */
	public function __construct( $config ) {
		self::$options = $config;
	}

	/**
	 * Load hooks.
	 *
	 * @return void
	 */
	public function load_hooks() {
		add_action( 'init', [ $this, 'init' ] );
		add_action( is_multisite() ? 'network_admin_menu' : 'admin_menu', [ $this, 'add_plugin_menu' ] );
		add_action( 'network_admin_edit_local-development', [ $this, 'update_settings' ] );
		add_action( 'admin_init', [ $this, 'update_settings' ] );
		add_action( 'admin_head-settings_page_local-development', [ $this, 'style_settings' ] );

		add_filter(
			is_multisite() ? 'network_admin_plugin_action_links_' . $this->plugin_slug : 'plugin_action_links_' . $this->plugin_slug,
			[ $this, 'plugin_action_links' ]
		);
	}

	/**
	 * Initialize plugin/theme data. Needs to be called in the 'init' hook.
	 */
	public function init() {
		$plugins = [];
		$themes  = [];

		// Ensure get_plugins() function is available.
		include_once ABSPATH . '/wp-admin/includes/plugin.php';

		$this->plugins = get_plugins();
		$this->themes  = wp_get_themes( [ 'errors' => null ] );

		foreach ( array_keys( $this->plugins ) as $slug ) {
			$plugins[ $slug ] = $this->plugins[ $slug ]['Name'];
		}
		$this->plugins = $plugins;

		foreach ( array_keys( $this->themes ) as $slug ) {
			$themes[ $slug ] = $this->themes[ $slug ]->get( 'Name' );
		}
		$this->themes = $themes;

		return [
			'plugins' => $plugins,
			'themes'  => $themes,
		];
	}

	/**
	 * Define tabs for Settings page.
	 * By defining in a method, strings can be translated.
	 *
	 * @access private
	 *
	 * @return array
	 */
	private function settings_tabs() {
		$tabs = [];
		/**
		 * Filter settings tabs.
		 *
		 * @since 2.0.0
		 *
		 * @param array $tabs Array of default tabs.
		 */
		return apply_filters( 'local_development_add_settings_tabs', $tabs );
	}

	/**
	 * Add options page.
	 */
	public function add_plugin_menu() {
		$parent     = is_multisite() ? 'settings.php' : 'options-general.php';
		$capability = is_multisite() ? 'manage_network_options' : 'manage_options';

		add_submenu_page(
			$parent,
			esc_html__( 'Local Development Settings', 'local-development' ),
			esc_html_x( 'Local Development', 'Menu item', 'local-development' ),
			$capability,
			'local-development',
			[ $this, 'create_admin_page' ]
		);
	}

	/**
	 * Renders setting tabs.
	 *
	 * Walks through the object's tabs array and prints them one by one.
	 * Provides the heading for the settings page.
	 *
	 * @access private
	 */
	private function options_tabs() {
		// phpcs:ignore WordPress.Security.NonceVerification
		$current_tab = isset( $_GET['tab'] ) ? esc_attr( $_GET['tab'] ) : 'local_dev_settings_plugins';
		echo '<nav class="nav-tab-wrapper" aria-label="Secondary menu">';
		foreach ( $this->settings_tabs() as $key => $name ) {
			$active = ( $current_tab === $key ) ? 'nav-tab-active' : '';
			echo '<a class="nav-tab ' . $active . '" href="?page=local-development&tab=' . $key . '">' . $name . '</a>';
		}
		echo '</nav>';
	}

	/**
	 * Options page callback.
	 */
	public function create_admin_page() {
		$action = is_multisite() ? 'edit.php?action=local-development' : 'options.php';
		// phpcs:ignore WordPress.Security.NonceVerification
		$tab = isset( $_GET['tab'] ) ? esc_attr( $_GET['tab'] ) : 'local_dev_settings_plugins'; ?>
		<div class="wrap">
			<h2>
				<?php esc_html_e( 'Local Development Settings', 'local-development' ); ?>
			</h2>
			<p><?php esc_html_e( 'Selected repositories will not display an update notice.', 'local-development' ); ?></p>
			<?php
			$this->options_tabs();
			$this->admin_page_notices();

			/**
			 * Action hook to add admin page data to appropriate $tab.
			 *
			 * @since 8.0.0
			 *
			 * @param string $tab    Name of tab.
			 * @param string $action Save action for appropriate WordPress installation.
			 *                       Single site or Multisite.
			 */
			do_action( 'local_development_add_admin_page', $tab, $action );
			echo '</div>';
	}

	/**
	 * Display settings page notices.
	 *
	 * @return void
	 */
	public function admin_page_notices() {
		// phpcs:ignore WordPress.Security.NonceVerification
		if ( isset( $_GET['updated'] ) && '1' === $_GET['updated'] && is_multisite() ) {
			echo '<div class="updated"><p><strong>' . esc_html__( 'Settings saved.', 'local-development' ) . '</strong></p></div>';
		}
	}

	/**
	 * Sanitize each setting field as needed.
	 *
	 * @param array $input Contains all settings fields as array keys.
	 *
	 * @return array $new_input
	 */
	public static function sanitize( $input ) {
		$new_input = [];
		foreach ( array_keys( (array) $input ) as $id ) {
			$new_input[ sanitize_text_field( $id ) ] = sanitize_text_field( $input[ $id ] );
		}

		return $new_input;
	}

	/**
	 * Get the settings option array and print one of its values.
	 *
	 * @param array $args Args for checkbox.
	 */
	public function token_callback_checkbox( $args ) {
		$checked = isset( self::$options[ $args['type'] ][ $args['id'] ] ) ? esc_attr( self::$options[ $args['type'] ][ $args['id'] ] ) : null;
		?>
		<label for="<?php esc_attr_e( $args['id'] ); ?>">
			<input type="checkbox" name="local_dev[<?php esc_attr_e( $args['id'] ); ?>]" value="1" <?php checked( '1', abs( $checked ), true ); ?> <?php disabled( '-1', $checked, true ); ?> >
			<?php esc_html_e( $args['name'] ); ?>
		</label>
		<?php
	}

	/**
	 * Update single site and network settings.
	 * Used when plugin is network activated to save settings.
	 *
	 * @link http://wordpress.stackexchange.com/questions/64968/settings-api-in-multisite-missing-update-message
	 * @link http://benohead.com/wordpress-network-wide-plugin-settings/
	 */
	public function update_settings() {
		if ( ! isset( $_POST['_wp_http_referer'] ) ) {
			return false;
		}
		$query = parse_url( $_POST['_wp_http_referer'], PHP_URL_QUERY );
		parse_str( $query, $arr );
		$arr['tab'] = ! empty( $arr['tab'] ) ? $arr['tab'] : 'local_dev_settings_plugins';

		if ( isset( $_POST['option_page'] ) &&
			'local_development_settings' === $_POST['option_page']
		) {
			$options = isset( $_POST['local_dev'] ) ? $_POST['local_dev'] : [];
			$tab     = explode( '_', $arr['tab'] );
			$tab     = array_pop( $tab );

			/**
			 * Filter options from added classes.
			 *
			 * @since 2.0.0
			 *
			 * @param array $options Array of options returned from Save.
			 * @param array $arr     Array of setting page info.
			 *
			 * @return array $options Array of options.
			 */
			$options       = apply_filters( "local_development_update_settings_{$tab}", $options, $tab );
			self::$options = array_merge( (array) self::$options, $options );
			update_site_option( 'local_development', self::$options );
		}

		$this->redirect_on_save();
	}

	/**
	 * Save added class settings.
	 *
	 * @param array  $options Settings of specific tab.
	 * @param string $tab     Unique part of tab, should correspond to class.
	 *
	 * @return array
	 */
	public function save_tab_settings( $options, $tab ) {
		return [ $tab => self::sanitize( $options ) ];
	}

	/**
	 * Redirect to correct Settings tab on Save.
	 */
	protected function redirect_on_save() {
		$update = false;

		if ( ( isset( $_POST['action'] ) && 'update' === $_POST['action'] ) &&
			( isset( $_POST['option_page'] ) && 'local_development_settings' === $_POST['option_page'] )
		) {
			$update = true;
		}

		$redirect_url = is_multisite() ? network_admin_url( 'settings.php' ) : admin_url( 'options-general.php' );

		if ( $update ) {
			$query = isset( $_POST['_wp_http_referer'] ) ? parse_url( $_POST['_wp_http_referer'], PHP_URL_QUERY ) : null;
			parse_str( $query, $arr );
			$arr['tab'] = ! empty( $arr['tab'] ) ? $arr['tab'] : 'local_dev_settings_plugins';

			$location = add_query_arg(
				[
					'page'    => 'local-development',
					'tab'     => $arr['tab'],
					'updated' => $update,
				],
				$redirect_url
			);
			wp_safe_redirect( $location );
			exit;
		}
	}

	/**
	 * Add setting link to plugin page.
	 * Applied to the list of links to display on the plugins page (beside the activate/deactivate links).
	 *
	 * @param array $links plugins.php plugin row links.
	 *
	 * @return array
	 */
	public function plugin_action_links( $links ) {
		$settings_page = is_multisite() ? 'settings.php' : 'options-general.php';
		$link          = [ '<a href="' . esc_url( network_admin_url( $settings_page ) ) . '?page=local-development">' . esc_html__( 'Settings', 'local-development' ) . '</a>' ];

		return array_merge( $link, $links  );
	}

	/**
	 * Style settings.
	 */
	public function style_settings() {
		?>
		<!-- Local Development -->
		<style>
			.form-table th[scope='row']:empty {
				display: none;
			}
		</style>
		<?php
	}
}
