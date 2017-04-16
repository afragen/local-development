# Local Development
Contributors: afragen
Tags: localhost, development, upgrade, plugin, theme
Requires at least: 4.0
Tested up to: 4.7
Stable tag: 1.4.0
License: GPLv2

Places development notice for plugins or themes that are in local development. Prevents updating of selected plugins and themes.

## Description

Places development notice for plugins or themes that are in local development. Notices are placed on the plugins page and the themes page. Prevents updating of selected plugins and themes.

Pull Requests are welcome at https://github.com/afragen/local-development

Requires PHP 5.3 or greater.

## Installation
If you use this plugin you really shouldn't need these.

1. Upload the entire `/local-development` folder to the `/wp-content/plugins/` directory.
1. Activate the plugin.

## Screenshots
1. Plugin Settings
2. Plugins Page
3. Themes Page
4. Multisite Themes Page

## Changelog

#### 1.4.0 / 2017-04-16
* redesigned Settings to put checkbox in front of plugin/theme name
* updated screenshots

#### 1.3.1 / 2017-04-13
* move Autoloader to new location
* cleanup

#### 1.3.0 / 2016-11-06
* fixed PHP notice on settings page
* correctly load translations
* added our own PHP version check

#### 1.2.4
* cast `self::$themes` as array when empty, fixes PHP notice

#### 1.2.3
* fixed saving on single install when nothing selected

#### 1.2.2
* fix PHP notices on initial install and no saved settings

#### 1.2.1
* fix PHP notice

#### 1.2
* add hiding of update messages for GitHub Updater

#### 1.1
* specify `admin_head-settings_page_local-development` to add styles

#### 1.0
* rebrand as **Local Development**

#### 0.2
* hide update nag for selected repositories to prevent updating

#### 0.1
* initial release
