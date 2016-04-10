<?php
/**
 * Plugin Name:       Local Development
 * Plugin URI:        https://wordpress.org/plugins/local-development
 * Author:            Andy Fragen
 * Author URI:        http://thefragens.com/
 * Description:       Places development notice for plugins or themes that are in local development. Prevents updating of selected plugins and themes.
 * Version:           1.2
 * Text Domain:       local-development
 * License:           GNU General Public License v2
 * License URI:       http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 * Network:           true
 * GitHub Plugin URI: https://github.com/afragen/local-development
 * Requires PHP:      5.3
 */

/**
 * Exit if called directly.
 * PHP version check and exit.
 */
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'WPUpdatePhp' ) ) {
	require_once ( plugin_dir_path( __FILE__ ) . '/vendor/wp-update-php/src/WPUpdatePhp.php' );
}
$updatePhp = new WPUpdatePhp( '5.3.0' );
if ( method_exists( $updatePhp, 'set_plugin_name' ) ) {
	$updatePhp->set_plugin_name( 'Local Development' );
}
if ( ! $updatePhp->does_it_meet_required_php_version( PHP_VERSION ) ) {
	return false;
}

// Load textdomain
load_plugin_textdomain( 'local-development', false, __DIR__ . '/languages' );

// Plugin namespace root
$root = array( 'Fragen\\Local_Development' => __DIR__ . '/src/Local_Development' );

// Add extra classes
$extra_classes = array(
	'WPUpdatePHP' => __DIR__ . '/vendor/wp-update-php/src/WPUpdatePhp.php',
);

// Load Autoloader
require_once( __DIR__ . '/src/Local_Development/Autoloader.php' );
$loader = 'Fragen\\Local_Development\\Autoloader';
new $loader( $root, $extra_classes );

// Instantiate
$instantiate = 'Fragen\\Local_Development\\Init';
new $instantiate;
