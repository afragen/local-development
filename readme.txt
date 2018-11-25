# Local Development
Contributors: afragen
Tags: localhost, development, upgrade, plugin, theme
Requires at least: 4.0
Tested up to: 5.0
Requires PHP: 5.6
Stable tag: 2.2.0
License: GPLv2

Places development notice for plugins or themes that are in local development. Prevents updating of selected plugins and themes. Allows for using a local file server.

## Description

Places development notice for plugins or themes that are in local development. Notices are placed on the plugins page and the themes page. Prevents updating of selected plugins and themes. Allows for using a local file server.

Pull Requests are welcome at https://github.com/afragen/local-development

Requires PHP 5.6 or greater.

## Screenshots
1. Plugin Settings
2. Plugins Page
3. Themes Page
4. Multisite Themes Page

## Changelog

#### 2.2.0 / 2018-11-25
* use composer for dependencies and autoloader

#### 2.1.0 / 2018-10-01
* updated error handling in Singleton factory
* added `class Bootstrap` to allow for easier time with main plugin file

#### 2.0.0
* refactored to have each group in its own class, much more OOPy ;)
* use wpcs codesniffer
* removed checkboxes and delete links for checked plugins/themes

#### 1.6.0 / 2018-05-26
* added abilty to use local file server on LAN during development
* refactored hiding of update row to use jQuery and remove row, not CSS dislay none
* update to PSR-2

#### 1.5.0
* stopped creation of generic global variables

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
