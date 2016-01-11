<?php

namespace Fragen\Local_Development_Upgrade_Warning;

/*
 * Exit if called directly.
 */
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Class Base
 *
 * @package Fragen\Local_Development_Upgrade_Warning
 */
class Base {

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
		self::$plugins = isset( $config['plugins'] ) ? $config['plugins'] : null;
		self::$themes  = isset( $config['themes'] ) ? $config['themes'] : null;
		self::$message = esc_html__( 'This is a local development directory.', 'local-development-upgrade-warning' );

		add_filter( 'plugin_row_meta', array( &$this, 'row_meta' ), 15, 2 );
		add_filter( 'site_transient_update_plugins', array( &$this, 'hide_update_nag' ), 15, 1 );

		add_filter( 'theme_row_meta', array( &$this, 'row_meta' ), 15, 2 );
		add_filter( 'site_transient_update_themes', array( &$this, 'hide_update_nag' ), 15, 1 );

		if ( ! is_multisite() ) {
			add_filter( 'wp_prepare_themes_for_js', array( &$this, 'append_theme_description' ), 15, 1 );
		}
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
		if ( array_key_exists( $file, self::$plugins ) ||
		     array_key_exists( $file, self::$themes )
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
			if ( array_key_exists( $theme['id'], self::$themes ) ) {
				$prepared_themes[ $theme['id'] ]['description'] .= '<p><strong>' . self::$message . '</strong></p>';
			}
		}

		return $prepared_themes;
	}

	/**
	 * Hide the update nag.
	 *
	 * @param $value
	 *
	 * @return mixed
	 */
	public function hide_update_nag( $value ) {
		$repos          = null;
		$current_filter = current_filter();

		if ( 'site_transient_update_plugins' === $current_filter ) {
			$repos = self::$plugins;
		}
		if ( 'site_transient_update_themes' === $current_filter ) {
			$repos = self::$themes;
		}

		foreach ( array_keys( $repos ) as $repo ) {
			if ( isset( $value->response[ $repo ] ) ) {
				unset( $value->response[ $repo ] );
			}
		}

		return $value;
	}

}
