<?php
/**
 * Local Development
 *
 * @package local-development
 * @author Andy Fragen <andy@thefragens.com>
 * @license GPLv2
 * @link https://github.com/afragen/local-development
 */

/**
 * Plugin Name:       Local Development
 * Plugin URI:        https://wordpress.org/plugins/local-development
 * Author:            Andy Fragen
 * Author URI:        http://thefragens.com/
 * Description:       Places development notice for plugins or themes that are in local development. Prevents updating of selected plugins and themes. Automatically adds plugins and themes under version control. Allows for using a local file server and bypassing the WordPress 5.2 WSOD Shutdown Handler.
 * Version:           2.10.0
 * Domain Path:       /languages
 * Text Domain:       local-development
 * License:           GNU General Public License v2
 * License URI:       https://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 * Network:           true
 * GitHub Plugin URI: https://github.com/afragen/local-development
 * Requires PHP:      5.6
 * Requires at least: 4.6
 */

namespace Fragen\Local_Development;

/*
 * Exit if called directly.
 * PHP version check and exit.
 */
if ( ! defined( 'WPINC' ) ) {
	die;
}

// Load Autoloader.
require_once __DIR__ . '/vendor/autoload.php';
( new Bootstrap() )->run( __FILE__ );
