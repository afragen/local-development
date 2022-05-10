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
 * Class Themes
 */
class Themes extends Settings {
	/**
	 * Let's get started.
	 *
	 * @return void
	 */
	public function run() {
		add_action( 'admin_init', [ $this, 'page_init' ] );
		$this->init();
		$this->add_settings();
		add_filter( 'local_development_update_settings_themes', [ $this, 'save_tab_settings' ], 10, 2 );
	}

	/**
	 * Add settings for themes.
	 *
	 * @return void
	 */
	public function add_settings() {
		add_action(
			'local_development_add_settings_tabs',
			function ( $tabs ) {
				$install_tabs = [ 'local_dev_settings_themes' => esc_html__( 'Themes', 'local-development' ) ];

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
	 * Register theme settings.
	 *
	 * @return void
	 */
	public function page_init() {
		register_setting(
			'local_development_settings',
			'local_dev_themes',
			[ $this, 'sanitize' ]
		);

		add_settings_section(
			'local_dev_themes',
			esc_html__( 'Themes', 'local-development' ),
			[ $this, 'print_section_themes' ],
			'local_dev_themes'
		);

		foreach ( $this->themes as $id => $name ) {
			add_settings_field(
				$id,
				null,
				[ $this, 'token_callback_checkbox' ],
				'local_dev_themes',
				'local_dev_themes',
				[
					'id'   => $id,
					'type' => 'themes',
					'name' => $name,
				]
			);
		}
	}

	/**
	 * Print the theme text.
	 */
	public function print_section_themes() {
		esc_html_e( 'Select the locally developed themes.', 'local-development' );
	}

	/**
	 * Add settings page data.
	 *
	 * @param  mixed $tab Admin page tab.
	 * @param  mixed $action Admin page action.
	 * @return void
	 */
	public function add_admin_page( $tab, $action ) {
		if ( 'local_dev_settings_themes' === $tab ) {
			$action = add_query_arg( 'tab', $tab, $action ); ?>
			<form method="post" action="<?php echo esc_attr( $action ); ?>">
			<?php
			settings_fields( 'local_development_settings' );
			do_settings_sections( 'local_dev_themes' );
			submit_button();
			echo '</form>';
		}
	}
}
