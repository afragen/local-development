<?php
/**
 * Plugin Name:       Local Development
 * Plugin URI:        https://wordpress.org/plugins/local-development
 * Author:            Andy Fragen
 * Author URI:        http://thefragens.com/
 * Description:       Places development notice for plugins or themes that are in local development. Prevents updating of selected plugins and themes.
 * Version:           1.4.0
 * Domain Path:       /languages
 * Text Domain:       local-development
 * License:           GNU General Public License v2
 * License URI:       http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 * Network:           true
 * GitHub Plugin URI: https://github.com/afragen/local-development
 * Requires PHP:      5.3
 */

/*
 * Exit if called directly.
 * PHP version check and exit.
 */
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( version_compare( '5.3.0', PHP_VERSION, '>=' ) ) {
	?>
	<div class="error notice is-dismissible">
		<p>
			<?php esc_html_e( 'Local Development cannot run on PHP versions older than 5.3.0. Please contact your hosting provider to update your site.', 'local-development' ); ?>
		</p>
	</div>
	<?php

	return false;
}

// Load textdomain
load_plugin_textdomain( 'local-development', false, basename( __DIR__ ) . '/languages' );

// Plugin namespace root
$root = array( 'Fragen\\Local_Development' => __DIR__ . '/src/Local_Development' );

// Load Autoloader
require_once( __DIR__ . '/src/Autoloader.php' );
$loader = 'Fragen\\Autoloader';
new $loader( $root );

// Instantiate
$instantiate = 'Fragen\\Local_Development\\Init';
new $instantiate;
