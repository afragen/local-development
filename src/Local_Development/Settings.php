<?php

namespace Fragen\Local_Development;

/*
 * Exit if called directly.
 */
if (! defined('WPINC')) {
	die;
}

class Settings {
	/**
	 * Holds plugin data.
	 *
	 * @var
	 */
	protected $plugins;

	/**
	 * Holds theme data.
	 *
	 * @var
	 */
	protected $themes;

	/**
	 * Holds plugin settings.
	 *
	 * @var mixed|void
	 */
	protected static $options;

	/**
	 * Holds the plugin basename.
	 *
	 * @var string
	 */
	private $plugin_slug = 'local-development/local-development.php';

	/**
	 * Settings constructor.
	 */
	public function __construct() {
		self::$options = get_site_option('local_development');
	}

	public function load_hooks() {
		add_action('init', [$this, 'init']);
		add_action(is_multisite() ? 'network_admin_menu' : 'admin_menu', [$this, 'add_plugin_page']);
		add_action('network_admin_edit_local-development', [$this, 'update_network_settings']);
		add_action('admin_init', [$this, 'update_settings']);
		add_action('admin_head-settings_page_local-development', [$this, 'style_settings']);

		add_filter(
			is_multisite() ? 'network_admin_plugin_action_links_' . $this->plugin_slug : 'plugin_action_links_' . $this->plugin_slug,
			[$this, 'plugin_action_links']
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
		$this->themes  = wp_get_themes([ 'errors' => null ]);

		foreach (array_keys($this->plugins) as $slug) {
			$plugins[$slug] = $this->plugins[$slug]['Name'];
		}
		$this->plugins = $plugins;

		foreach (array_keys($this->themes) as $slug) {
			$themes[$slug] = $this->themes[$slug]->get('Name');
		}
		$this->themes = $themes;
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
		return apply_filters('local_development_add_settings_tabs', $tabs);
	}

	/**
	 * Add options page.
	 */
	public function add_plugin_page() {
		if (is_multisite()) {
			add_submenu_page(
				'settings.php',
				esc_html__('Local Development Settings', 'local-development'),
				esc_html__('Local Development', 'local-development'),
				'manage_network',
				'local-development',
				[ $this, 'create_admin_page' ]
			);
		} else {
			add_options_page(
				esc_html__('Local Development Settings', 'local-development'),
				esc_html__('Local Development', 'local-development'),
				'manage_options',
				'local-development',
				[ $this, 'create_admin_page' ]
			);
		}
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
		$current_tab = isset($_GET['tab']) ? $_GET['tab'] : 'local_dev_settings_plugins';
		echo '<h2 class="nav-tab-wrapper">';
		foreach ($this->settings_tabs() as $key => $name) {
			$active = ($current_tab == $key) ? 'nav-tab-active' : '';
			echo '<a class="nav-tab ' . $active . '" href="?page=local-development&tab=' . $key . '">' . $name . '</a>';
		}
		echo '</h2>';
	}

	/**
	 * Options page callback.
	 */
	public function create_admin_page() {
		$action = is_multisite() ? 'edit.php?action=local-development' : 'options.php';
		$tab    = isset($_GET['tab']) ? $_GET['tab'] : 'local_dev_settings_plugins'; ?>
		<div class="wrap">
			<h2>
				<?php esc_html_e('Local Development Settings', 'local-development'); ?>
			</h2>
			<p>Selected repositories will not display an update notice.</p>
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
		do_action('local_development_add_admin_page', $tab, $action);
		echo'</div>';
	}

	/**
	 * Display settings page notices.
	 *
	 * @return void
	 */
	public function admin_page_notices() {
		if (isset($_GET['updated']) && true == $_GET['updated']) {
			echo '<div class="updated"><p><strong>' . esc_html_e('Saved.', 'local-development') . '</strong></p></div>';
		}
	}

	/**
	 * Sanitize each setting field as needed.
	 *
	 * @param array $input Contains all settings fields as array keys
	 *
	 * @return array
	 */
	public static function sanitize($input) {
		$new_input = [];
		foreach (array_keys((array) $input) as $id) {
			$new_input[sanitize_text_field($id)] = sanitize_text_field($input[$id]);
		}

		return $new_input;
	}

	/**
	 * Get the settings option array and print one of its values.
	 *
	 * @param $args
	 */
	public function token_callback_checkbox($args) {
		$checked = isset(self::$options[$args['type']][$args['id']]) ? esc_attr(self::$options[$args['type']][$args['id']]) : null; ?>
		<label for="<?php esc_attr_e($args['id']); ?>">
			<input type="checkbox" name="local_dev[<?php esc_attr_e($args['id']); ?>]" value="1" <?php checked('1', $checked, true); ?> >
			<?php esc_html_e($args['name']); ?>
		</label>
		<?php
	}

	/**
	 * Update settings for single install.
	 */
	public function update_settings() {
		if (! isset($_POST['_wp_http_referer']) || is_multisite()) {
			return false;
		}
		$query = parse_url($_POST['_wp_http_referer'], PHP_URL_QUERY);
		parse_str($query, $arr);
		if (empty($arr['tab'])) {
			$arr['tab'] = 'local_dev_settings_plugins';
		}

		if (isset($_POST['option_page']) &&
			'local_development_settings' === $_POST['option_page']
		) {
			if ('local_dev_settings_plugins' === $arr['tab']) {
				self::$options['plugins'] = self::sanitize($_POST['local_dev']);
			}
			if ('local_dev_settings_themes' === $arr['tab']) {
				self::$options['themes'] = self::sanitize($_POST['local_dev']);
			}
			if ('local_dev_settings_extras' === $arr['tab']) {
				self::$options['extras'] = self::sanitize($_POST['local_dev']);
			}
			update_site_option('local_development', self::$options);
		}
	}

	/**
	 * Update network settings.
	 * Used when plugin is network activated to save settings.
	 *
	 * @link http://wordpress.stackexchange.com/questions/64968/settings-api-in-multisite-missing-update-message
	 * @link http://benohead.com/wordpress-network-wide-plugin-settings/
	 */
	public function update_network_settings() {
		$query = parse_url($_POST['_wp_http_referer'], PHP_URL_QUERY);
		parse_str($query, $arr);
		if (empty($arr['tab'])) {
			$arr['tab'] = 'local_dev_settings_plugins';
		}

		if ('local_development_settings' === $_POST['option_page']) {
			if ('local_dev_settings_plugins' === $arr['tab']) {
				self::$options['plugins'] = self::sanitize($_POST['local_dev']);
			}
			if ('local_dev_settings_themes' === $arr['tab']) {
				self::$options['themes'] = self::sanitize($_POST['local_dev']);
			}
			update_site_option('local_development', self::$options);
		}

		$location = add_query_arg(
			[
				'page'    => 'local-development',
				'updated' => 'true',
				'tab'     => $arr['tab'],
			],
			network_admin_url('settings.php')
		);
		wp_redirect($location);
		exit;
	}

	/**
	 * Add setting link to plugin page.
	 * Applied to the list of links to display on the plugins page (beside the activate/deactivate links).
	 *
	 * @link http://codex.wordpress.org/Plugin_API/Filter_Reference/plugin_action_links_(plugin_file_name)
	 *
	 * @param $links
	 *
	 * @return array
	 */
	public function plugin_action_links($links) {
		$settings_page = is_multisite() ? 'settings.php' : 'options-general.php';
		$link          = [ '<a href="' . esc_url(network_admin_url($settings_page)) . '?page=local-development">' . esc_html__('Settings', 'local-development') . '</a>' ];

		return array_merge($links, $link);
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
