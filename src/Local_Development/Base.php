<?php

namespace Fragen\Local_Development;

/*
 * Exit if called directly.
 */
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Class Base
 *
 * @package Fragen\Local_Development
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
	 * Local_Development constructor.
	 *
	 * @param $config
	 */
	public function __construct( $config ) {
		self::$plugins = isset( $config['plugins'] ) ? $config['plugins'] : null;
		self::$themes  = isset( $config['themes'] ) ? $config['themes'] : null;
		self::$message = esc_html__( 'In Local Development', 'local-development' );

		add_filter( 'plugin_row_meta', array( &$this, 'row_meta' ), 15, 2 );
		add_filter( 'site_transient_update_plugins', array( &$this, 'hide_update_nag' ), 15, 1 );

		add_filter( 'theme_row_meta', array( &$this, 'row_meta' ), 15, 2 );
		add_filter( 'site_transient_update_themes', array( &$this, 'hide_update_nag' ), 15, 1 );

		if ( ! is_multisite() ) {
			add_filter( 'wp_prepare_themes_for_js', array( &$this, 'set_theme_description' ), 15, 1 );
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
		if ( ( ! empty( self::$plugins) && array_key_exists( $file, self::$plugins ) ) ||
		     ( ! empty( self::$themes ) && array_key_exists( $file, self::$themes ) )
		) {
			$links[] = '<strong>' . self::$message . '</strong>';
		}

		return $links;
	}

	/**
	 * Sets the description for the single install theme action.
	 *
	 * @param $prepared_themes
	 *
	 * @return array
	 */
	public function set_theme_description( $prepared_themes ) {
		foreach ( $prepared_themes as $theme ) {
			if ( array_key_exists( $theme['id'], (array) self::$themes ) ) {
				$message = wp_get_theme( $theme['id'] )->get( 'Description' );
				$message .= '<p><strong>' . self::$message . '</strong></p>';
				$prepared_themes[ $theme['id'] ]['description'] = $message;
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
		switch ( current_filter() ) {
			case 'site_transient_update_plugins':
				$repos = self::$plugins;
				break;
			case 'site_transient_update_themes':
				$repos = self::$themes;
				break;
			default:
				return $value;
		}

		if ( ! empty( $repos ) ) {
			foreach ( array_keys( $repos ) as $repo ) {
				if ( 'update_nag' === $repo ) {
					continue;
				}
				if ( isset( $value->response[ $repo ] ) ) {
					unset( $value->response[ $repo ] );
				}
			}
		}

		return $value;
	}

}
