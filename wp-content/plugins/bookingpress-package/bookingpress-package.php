<?php
/*
Plugin Name: BookingPress - Service Package Addon
Description: Extension for the BookingPress plugin to create packages of services.
Version: 1.9
Requires at least: 5.0
Requires PHP:      5.6
Plugin URI: https://www.bookingpressplugin.com/
Author: Repute InfoSystems
Author URI: https://www.bookingpressplugin.com/
Text Domain: bookingpress-package
Domain Path: /languages
*/

define('BOOKINGPRESS_PACKAGE_DIR_NAME', 'bookingpress-package');
define('BOOKINGPRESS_PACKAGE_DIR', WP_PLUGIN_DIR . '/' . BOOKINGPRESS_PACKAGE_DIR_NAME);

if (file_exists( BOOKINGPRESS_PACKAGE_DIR . '/autoload.php')) {
    require_once BOOKINGPRESS_PACKAGE_DIR . '/autoload.php';
}