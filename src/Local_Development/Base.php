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
 * Class Base
 */
class Base {
	/**
	 * Static to hold slugs of plugins under development.
	 *
	 * @var $plugins
	 */
	protected static $plugins;

	/**
	 * Static to hold slugs themes under development.
	 *
	 * @var $themes
	 */
	protected static $themes;

	/**
	 * Static to hold message.
	 *
	 * @var $message
	 */
	protected static $message;

	/**
	 * Holds plugin settings.
	 *
	 * @var $options
	 */
	protected static $options;

	/**
	 * Local_Development constructor.
	 *
	 * @param array $config Configuration parameters.
	 */
	public function __construct( $config ) {
		self::$options = $config;
		self::$plugins = isset( $config['plugins'] ) ? $config['plugins'] : null;
		self::$themes  = isset( $config['themes'] ) ? $config['themes'] : null;
		self::$message = esc_html__( 'In Local Development', 'local-development' );
	}

	/**
	 * Let's get going.
	 *
	 * @return void
	 */
	public function load_hooks() {
		add_filter( 'plugin_row_meta', [ $this, 'row_meta' ], 15, 2 );
		add_filter( 'site_transient_update_plugins', [ $this, 'hide_update_nag' ], 99, 1 );
		add_filter( 'plugin_action_links', [ $this, 'action_links' ], 15, 2 );
		add_filter( 'network_admin_plugin_action_links', [ $this, 'action_links' ], 15, 2 );

		add_filter( 'theme_row_meta', [ $this, 'row_meta' ], 15, 2 );
		add_filter( 'site_transient_update_themes', [ $this, 'hide_update_nag' ], 99, 1 );
		add_filter( 'theme_action_links', [ $this, 'action_links' ], 15, 2 );
		add_filter( 'wp_prepare_themes_for_js', [ $this, 'set_theme_description' ], 15, 1 );

		if ( ! isset( static::$options['extras']['disable_git_icons'] ) ) {
			add_filter( 'plugin_row_meta', [ $this, 'row_meta_icons' ], 15, 2 );
			add_filter( 'theme_row_meta', [ $this, 'row_meta_icons' ], 15, 2 );
		}
	}

	/**
	 * Add an additional element to the row meta links.
	 *
	 * @param array  $links Row meta links.
	 * @param string $file  Row meta file name.
	 *
	 * @return array
	 */
	public function row_meta( $links, $file ) {
		if ( ( ! empty( self::$plugins ) && array_key_exists( $file, self::$plugins ) ) ||
			( ! empty( self::$themes ) && array_key_exists( $file, self::$themes ) )
		) {
			$links[] = '<strong style="color:red; font-weight:700;">' . self::$message . '</strong>';
			add_action( "after_plugin_row_{$file}", [ $this, 'remove_update_row' ], 15, 1 );
			add_action( "after_theme_row_{$file}", [ $this, 'remove_update_row' ], 15, 1 );
		}

		return $links;
	}

	/**
	 * Remove 'delete' action link.
	 *
	 * @param  array  $actions Row meta actions.
	 * @param  string $file    Row meta file name.
	 * @return array  $actions Row meta actions.
	 */
	public function action_links( $actions, $file ) {
		$file  = $file instanceof \WP_Theme ? $file->stylesheet : $file;
		$repos = array_merge( (array) self::$plugins, (array) self::$themes );
		if ( array_key_exists( $file, $repos ) ) {
			unset( $actions['delete'] );
		}

		return $actions;
	}

	/**
	 * For single site.
	 * Sets the description for the non-network admin theme window.
	 * Removes the delete option.
	 * Removes the update and branch switch ability.
	 *
	 * @param array $prepared_themes Array of themes.
	 *
	 * @return array $prepared_themes
	 */
	public function set_theme_description( $prepared_themes ) {
		foreach ( $prepared_themes as $theme ) {
			if ( array_key_exists( $theme['id'], (array) self::$themes ) ) {
				$message = '<strong style="color:red; font-weight:700;">' . self::$message . '</strong>';
				unset( $prepared_themes[ $theme['id'] ]['actions']['delete'] );

				// Remove branch switcher.
				$description                                    = substr( $prepared_themes[ $theme['id'] ]['description'], 0, strpos( $prepared_themes[ $theme['id'] ]['description'], '<p>Current branch' ) );
				$prepared_themes[ $theme['id'] ]['description'] = $description;

				// Remove update notice.
				$prepared_themes[ $theme['id'] ]['hasUpdate'] = false;
			}
			$icon = $this->row_meta_icons( [], $theme['id'] );

			if ( isset( $icon[0] ) ) {
				$message = ! empty( $message ) ? $message .= ' | ' . $icon[0] : $icon[0];
			}
			if ( ! empty( $message ) ) {
				$message = '<p>' . $message . '</p>';
				$prepared_themes[ $theme['id'] ]['description'] .= $message;
			}
			$message = '';
			$icon    = [];
		}

		return $prepared_themes;
	}

	/**
	 * Hide the update nag.
	 *
	 * @param object $transient site_transient_update_{plugins|themes}.
	 *
	 * @return object $transient Modified site_transient_update_{plugins|themes}.
	 */
	public function hide_update_nag( $transient ) {
		if ( ! is_object( $transient ) ) {
			return $transient;
		}
		switch ( current_filter() ) {
			case 'site_transient_update_plugins':
				$repos = self::$plugins;
				break;
			case 'site_transient_update_themes':
				$repos = self::$themes;
				break;
			default:
				return $transient;
		}

		if ( ! empty( $repos ) ) {
			foreach ( array_keys( $repos ) as $repo ) {
				if ( isset( $transient->response[ $repo ] ) ) {
					unset( $transient->response[ $repo ] );
				}
				if ( isset( $transient->translations ) ) {
					foreach ( $transient->translations as $key => $translation ) {
						if ( dirname( $repo ) === $translation['slug'] ) {
							unset( $transient->translations[ $key ] );
						}
					}
				}
			}
		}

		return $transient;
	}

	/**
	 * Hide update messages.
	 */
	public function hide_update_message() {
		global $pagenow;
		if ( 'plugins.php' === $pagenow && ! empty( self::$options['plugins'] ) ) {
			foreach ( array_keys( self::$options['plugins'] ) as $plugin ) {
				$this->remove_update_row( $plugin );
			}
		}
		if ( 'themes.php' === $pagenow && ! empty( self::$options['themes'] ) ) {
			foreach ( array_keys( self::$options['themes'] ) as $theme ) {
				$this->remove_update_row( $theme );
			}
		}
	}

	/**
	 * Write out inline style to hide the update row notice.
	 * Removes checkbox for bulk actions.
	 *
	 * @param string $repo_name Repository file name.
	 */
	public function remove_update_row( $repo_name ) {
		print '<script>';
		print 'jQuery("tr.plugin-update-tr[data-plugin=\'' . esc_attr( $repo_name ) . '\']").remove();';
		print 'jQuery(".update[data-plugin=\'' . esc_attr( $repo_name ) . '\']").removeClass("update");';
		print 'jQuery("input[value=\'' . esc_attr( $repo_name ) . '\']").remove();';
		print '</script>';
	}

	/**
	 * Add git host based icons.
	 *
	 * @param array  $links Row meta action links.
	 * @param string $file  Plugin or theme file.
	 *
	 * @return array $links
	 */
	public function row_meta_icons( $links, $file ) {
		$type     = false !== strpos( current_filter(), 'plugin' ) ? 'plugin' : 'theme';
		$type_cap = ucfirst( $type );
		$filepath = 'plugin' === $type ? WP_PLUGIN_DIR . "/$file" : get_theme_root() . "/$file/style.css";

		$git_headers = [
			"GitHub{$type_cap}URI"    => "GitHub {$type_cap} URI",
			"GitLab{$type_cap}URI"    => "GitLab {$type_cap} URI",
			"Bitbucket{$type_cap}URI" => "Bitbucket {$type_cap} URI",
			"Gitea{$type_cap}URI"     => "Gitea {$type_cap} URI",
		];
		$git_icons   = [
			'github'    => 'github-logo.svg',
			'gitlab'    => 'gitlab-logo.svg',
			'bitbucket' => 'bitbucket-logo.svg',
			'gitea'     => 'gitea-logo.svg',
		];
		$file_data   = get_file_data( $filepath, $git_headers );

		/**
		 * Insert repositories added via GitHub Updater Additions plugin.
		 *
		 * @see GitHub Updater's Plugin or Theme class for definition.
		 * @link https://github.com/afragen/github-updater-additions
		 */
		$additions = apply_filters( 'github_updater_additions', null, [], $type );
		foreach ( (array) $additions as $slug => $headers ) {
			if ( $slug === $file ) {
				$file_data = array_merge( $file_data, $headers );
				break;
			}
		}

		foreach ( $file_data as $key => $value ) {
			if ( ! empty( $value ) ) {
				$githost = str_replace( "{$type_cap}URI", '', $key );
				$icon    = sprintf(
					'<img src="%s" style="vertical-align:text-bottom;" height="16" width="16" alt="%s" />',
					plugins_url( '/local-development/assets/' . $git_icons[ strtolower( $githost ) ] ),
					$githost
				);
				break;
			}
		}

		isset( $icon ) ? $links[] = $icon : null;

		return $links;
	}
}
