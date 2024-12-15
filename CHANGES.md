#### [unreleased]
* update for Git Updater rebrand to not duplicate git icons

#### 2.10.0 / 2024-12-11
* some reorganization to fix `_load_textdomain_just_in_time`
* set `WP_ENVIRONMENT_TYPE` variable

#### 2.9.2 / 2024-12-05
* don't use `init` hook to in startup

#### 2.9.1 / 2024-12-02
* add GA to generate POT
* linting
* more fixing for `_load_textdomain_just_in_time`

#### 2.9.0 / 2024-11-01
* remove `load_plugin_textdomain()`
* fix setting to show/hide git host icon
* composer update

#### 2.8.4 / 2024-07-11
* composer update
* update GitHub Actions
* update tested to
* linting

#### 2.8.3
* add nonce check for `update_settings()`, bad Andy

#### 2.8.2 / 2023-03-20
* PHP 8.1 compatibilty updates

#### 2.8.1 / 2022-05-10
* only use `esc_attr_e` for translating strings
* use `sanitize_title_with_dashes()` as `sanitize_file_name()` maybe have attached filter that changes output
* update for Local.app adding `WP_ENVIRONMENT_TYPE` constant in `local-bootstrap.php` file

#### 2.8.0 / 2021-07-07
* update WPConfigTransformer to use alternate anchor if default not present
* add @10up GitHub Actions for WordPress SVN

#### 2.7.3 / 2021-03-05
* update docblocks
* update tested to 5.7

#### 2.7.2 / 2020-11-21
* remove 'Edit' action link
* fix PHP warning when some settings not yet saved

#### 2.7.1 / 2020-10-05
* try to correcty display defined `WP_ENVIRONMENT_TYPE` in Settings
* remove branch switch list from GitHub Updater plugins/themes and add _In Local Development_ item

#### 2.7.0 / 2020-099-15
* set custom admin bar to display environment type
* remove `WP_ENVIRONMENT_TYPE` on deactivation
* remove a line of jQuery for removing `update` row class as unnecessary and now [conflicting with GHU](https://github.com/afragen/github-updater/pull/898)
* disable the auto-update link from WP 5.5

#### 2.6.2 / 2020-09-01
* only set `WP_ENVIRONMENT_TYPE` when changed

#### 2.6.1 / 2020-09-01
* fix for saving `WP_ENVIRONMENT_TYPE`

#### 2.6.0 / 2020-09-01
* add setting for `WP_ENVIRONMENT_TYPE` in WP 5.5+
* refactor plugin startup a bit

#### 2.5.7 / 2020-07-20
* set disabled checked option if `WP_DISABLE_FATAL_ERROR_HANDLER` is true

#### 2.5.6 / 2020-07-10
* fix logic for no setting

#### 2.5.5 / 2020-07-09
* skip mu-plugins and drop-ins
* filter options to save to remove unchecked and VCS repos
* add git host icon for Gists
* defer to GitHub Updater to show git host icons

#### 2.5.4 / 2020-06-01
* sanitizing, escaping & ignoring

#### 2.5.3 / 2020-03-28
* move `Settings` action link to front

#### 2.5.2 / 2020-02-03
* use `is_localhost()` for local git server setting and make automatic

#### 2.5.1 / 2020-01-24
* run if no settings yet saved

#### 2.5.0 / 2020-01-23
* update `Requires at least` in plugin file
* add VCS checkouts automatically, thanks @Raruto
* add git host icons as default setting for plugins and themes
* add localhost admin bar coloring when on localhost, default is on
* some now settings only display when running in localhost
* add repositories that are added from [GitHub Updater Additions](https://github.com/afragen/github-updater-additions)

#### 2.4.1 / 2019-05-09
* a11y updates for settings tabs

#### 2.4.0 / 2019-01-30
* remove language pack updates for selected plugins/themes
* only add WSOD bypass when appropriate

#### 2.3.0 / 2019-01-22
* add bypass for WordPress 5.2 WSOD Shutdown Handler
* move loading hooks out of constructors
* pass saved options to class constructors

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

#### 1.5.0 / 2017-11-11
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
* initial commit
