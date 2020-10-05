# Local Development
* Contributors: afragen, raruto
* Tags: localhost development upgrade plugin theme
* Requires at least: 4.6
* Requires PHP: 5.6
* Stable tag: master
* License: GPLv2
* Network: true

Places development notice for plugins or themes that are in local development. Prevents updating of selected plugins and themes. Automatically adds plugins and themes under version control. Allows for using a local file server and bypassing the WordPress 5.2 WSOD Shutdown Handler.

## Description
Places development notice for plugins or themes that are in local development. Notices are placed on the plugins page and the themes page. Prevents updating of selected plugins and themes.

* Automatically adds plugins and themes under version control.
* Automatically allows for using a local file server.
* Allows for bypassing the WordPress 5.2 WSOD Shutdown Handler.
* Add a warning color to admin bar when running under localhost. Default is on.
* Add a git host icon to the plugins page. Default is off. No option if GitHub Updater is running.
* Allows setting of `WP_ENVIRONMENT_TYPE` in `wp-config.php`.

Pull Requests are welcome against the `develop` branch.

Requires PHP 5.6 or greater.

## Screenshots

### 1. Local Development Settings
![Local Development Settings](./.wordpress-org/screenshot-1.png)

### 2. Plugins Page
![Plugins Page](./.wordpress-org/screenshot-2.png)

### 3. Themes Page
![Themes Page](./.wordpress-org/screenshot-3.png)

### 4. Multisite Themes Page
![Multisite Themes Page](./.wordpress-org/screenshot-4.png)
