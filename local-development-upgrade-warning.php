<?php
/**
 * Plugin Name:       Local Development Upgrade Warning
 * Plugin URI:        https://wordpress.org/plugins/local-development-upgrade-warning
 * Author:            Andy Fragen
 * Author URI:        http://thefragens.com/
 * Description:       A plugin to place warning notices for plugins or themes that are in active development.
 * Version:           0.1
 * Text Domain:       local-development-upgrade-warning
 * License:           GNU General Public License v2
 * License URI:       http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 * Network:           true
 * GitHub Plugin URI: https://github.com/afragen/local-development-upgrade-warning
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
	$updatePhp->set_plugin_name( 'Local Development Upgrade Warning' );
}
if ( ! $updatePhp->does_it_meet_required_php_version( PHP_VERSION ) ) {
	return false;
}

// Load textdomain
load_plugin_textdomain( 'local-development-upgrade-warning', false, __DIR__ . '/languages' );

// Plugin namespace root
$root = array( 'Fragen\\Local_Development_Upgrade_Warning' => __DIR__ . '/src/Local_Development_Upgrade_Warning' );

// Add extra classes
$extra_classes = array(
	'WPUpdatePHP' => __DIR__ . '/vendor/wp-update-php/src/WPUpdatePhp.php',
);

// Load Autoloader
require_once( __DIR__ . '/src/Local_Development_Upgrade_Warning/Autoloader.php' );
$loader = 'Fragen\\Local_Development_Upgrade_Warning\\Autoloader';
new $loader( $root, $extra_classes );

// Instantiate
$instantiate = 'Fragen\\Local_Development_Upgrade_Warning\\Init';
new $instantiate;
