<?php

namespace Fragen\Local_Development;

/*
 * Exit if called directly.
 */
if ( ! defined( 'WPINC' ) ) {
	die;
}

class Plugins extends Settings {
	/**
	 * Let's get going.
	 *
	 * @return void
	 */
	public function run() {
		add_action( 'admin_init', [ $this, 'page_init' ] );
		$this->init();
		$this->add_settings();
		add_filter( 'local_development_update_settings_plugins', [ $this, 'save_tab_settings' ], 10, 2 );
	}

	/**
	 * Add plugins settings page.
	 *
	 * @return void
	 */
	public function add_settings() {
		add_filter(
			'local_development_add_settings_tabs',
			function ( $tabs ) {
				$install_tabs = [ 'local_dev_settings_plugins' => esc_html__( 'Plugins', 'local-development' ) ];

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
	 * Register plugins page settings.
	 *
	 * @return void
	 */
	public function page_init() {
		register_setting(
			'local_development_settings',
			'local_dev_plugins',
			[ $this, 'sanitize' ]
		);

		add_settings_section(
			'local_dev_plugins',
			esc_html__( 'Plugins', 'local-development' ),
			[ $this, 'print_section_plugins' ],
			'local_dev_plugins'
		);

		foreach ( $this->plugins as $id => $name ) {
			add_settings_field(
				$id,
				null,
				[ $this, 'token_callback_checkbox' ],
				'local_dev_plugins',
				'local_dev_plugins',
				[
					'id'   => $id,
					'type' => 'plugins',
					'name' => $name,
				]
			);
		}
	}

	/**
	 * Print the plugin text.
	 */
	public function print_section_plugins() {
		esc_html_e( 'Select the locally developed plugins.', 'local-development' );
	}

	/**
	 * Add plugins page data.
	 *
	 * @param  mixed $tab
	 * @param  mixed $action
	 * @return void
	 */
	public function add_admin_page( $tab, $action ) {
		if ( 'local_dev_settings_plugins' === $tab ) {
			$action = add_query_arg( 'tab', $tab, $action ); ?>
			<form method="post" action="<?php esc_attr_e( $action ); ?>">
			<?php
			settings_fields( 'local_development_settings' );
			do_settings_sections( 'local_dev_plugins' );
			submit_button();
			echo '</form>';
		}
	}
}
