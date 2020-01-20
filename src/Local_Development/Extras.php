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
 * Class Extras
 */
class Extras extends Settings {
	/**
	 * Let's get started.
	 *
	 * @return void
	 */
	public function run() {
		add_action( 'admin_init', [ $this, 'page_init' ] );
		$this->init();
		$this->add_settings();
		$this->load_extras();
		add_filter( 'local_development_update_settings_extras', [ $this, 'save_tab_settings' ], 10, 2 );
	}

	/**
	 * Add settings tabs and admin page.
	 *
	 * @return void
	 */
	public function add_settings() {
		add_filter(
			'local_development_add_settings_tabs',
			function ( $tabs ) {
				$install_tabs = [ 'local_dev_settings_extras' => esc_html__( 'Extras', 'local-development' ) ];

				return array_merge( $tabs, $install_tabs );
			}
		);

		add_filter(
			'local_development_add_admin_page',
			function ( $tab, $action ) {
				$this->add_admin_page( $tab, $action );
			},
			10,
			2
		);
	}

	/**
	 * Register settings.
	 *
	 * @return void
	 */
	public function page_init() {
		register_setting(
			'local_development_settings',
			'local_dev_extras',
			[ $this, 'sanitize' ]
		);

		add_settings_section(
			'local_dev_extras',
			esc_html__( 'Extras', 'local-development' ),
			[ $this, 'print_section_extras' ],
			'local_dev_extras'
		);

		add_settings_field(
			'local_git_server',
			null,
			[ $this, 'token_callback_checkbox' ],
			'local_dev_extras',
			'local_dev_extras',
			[
				'id'   => 'local_servers',
				'type' => 'extras',
				'name' => esc_html__( 'Enable Local Git Servers (192.168.x.x)', 'local-development' ),
			]
		);

		if ( version_compare( get_bloginfo( 'version' ), '5.2', '>=' ) ) {
			add_settings_field(
				'fatal_error_handler',
				null,
				[ $this, 'token_callback_checkbox' ],
				'local_dev_extras',
				'local_dev_extras',
				[
					'id'   => 'bypass_fatal_error_handler',
					'type' => 'extras',
					'name' => esc_html__( 'Bypass WordPress 5.1 WSOD protection.', 'local-development' ),
				]
			);
		}
	}

	/**
	 * Print the plugin text.
	 */
	public function print_section_extras() {
		esc_html_e( 'Select the extra options.', 'local-development' );
	}

	/**
	 * Add settings page data.
	 *
	 * @param  mixed $tab Admin page tab.
	 * @param  mixed $action Admin page action.
	 * @return void
	 */
	public function add_admin_page( $tab, $action ) {
		if ( 'local_dev_settings_extras' === $tab ) {
			$action = add_query_arg( 'tab', $tab, $action ); ?>
			<form method="post" action="<?php esc_attr_e( $action ); ?>">
			<?php
			settings_fields( 'local_development_settings' );
			do_settings_sections( 'local_dev_extras' );
			submit_button();
			echo '</form>';
		}
	}

	/**
	 * Load extras.
	 *
	 * @return void|Shutdown_Handler
	 */
	protected function load_extras() {
		if ( isset( static::$options['extras']['local_servers'] ) ) {
			$this->allow_local_servers();
		}
		if ( isset( self::$options['extras']['bypass_fatal_error_handler'] ) ) {
			add_filter( 'wp_fatal_error_handler_enabled', '__return_false' );
		}
	}

	/**
	 * In case the developer is running a local instance of a git server.
	 *
	 * @return void
	 */
	public function allow_local_servers() {
		add_filter(
			'http_request_args',
			function ( $r, $url ) {
				if ( ! $r['reject_unsafe_urls'] ) {
					return $r;
				}
				$host = parse_url( $url, PHP_URL_HOST );
				if ( preg_match( '#^(([1-9]?\d|1\d\d|25[0-5]|2[0-4]\d)\.){3}([1-9]?\d|1\d\d|25[0-5]|2[0-4]\d)$#', $host ) ) {
					$ip = $host;
				} else {
					return $r;
				}

				$parts = array_map( 'intval', explode( '.', $ip ) );
				if ( 127 === $parts[0] || 10 === $parts[0] || 0 === $parts[0]
				|| ( 172 === $parts[0] && 16 <= $parts[1] && 31 >= $parts[1] )
				|| ( 192 === $parts[0] && 168 === $parts[1] )
				) {
					$r['reject_unsafe_urls'] = false;
				}

				return $r;
			},
			10,
			2
		);
	}
}
