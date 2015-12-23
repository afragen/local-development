=== Local Development Upgrade Warning ===
Contributors: afragen
Tags: upgrade plugin theme
Requires at least: 4.0
Tested up to: 4.4
Stable tag: trunk
License: GPLv2
Network: true

A plugin to place warning notices for plugins or themes that are in active development. Requires PHP 5.3 or greater.

== Description ==
A plugin to place warning notices for plugins or themes that are in active development. Provide a warning notice so plugins or themes that are under local development and are updatable from the WordPress repo or other location show a warning message. Updating a plugin or theme under local development may cause data loss. Hopefully this warning prevents that occurrence.

== Installation ==
In the usual fashion.

== Frequently Asked Questions ==
= Developers Only =
You will have to pass a configuration array to this plugin. You will need to add your plugins or themes in the following manner. Place code similar to that below in a mu-plugin or other plugin or functions.php file that is used in your local development.

<pre><code>
add_action( 'plugins_loaded', function() {
	if ( class_exists( 'Local_Development_Upgrade_Warning' ) ) {
		/**
		 * Create array for plugins/themes that are being locally developed but might be forks.
		 */
		$config['plugins'] = array(
			'airplane-mode',
			'github-updater',
			'pods',
			'test-plugin',
		);

		$config['themes'] = array(
			'test-theme',
			'my-underscores',
		);

		new Local_Development_Upgrade_Warning( $config );
	}

} );
</code></pre>

== Changelog ==
= 0.1 =
* initial release
