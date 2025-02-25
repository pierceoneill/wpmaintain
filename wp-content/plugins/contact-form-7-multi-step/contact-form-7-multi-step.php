<?php
/*
Plugin Name: Contact Form 7 Multi Step Pro
Plugin URI: http://ninjateam.org
Description: Break your long form into user-friendly steps.
Version: 2.7.5
Author: NinjaTeam
Author URI: http://ninjateam.org
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( function_exists( 'cf7mls_plugin_init' ) ) {
	require_once plugin_dir_path( __FILE__ ) . 'inc/Fallback.php';
	add_action(
		'admin_init',
		function() {
			deactivate_plugins( plugin_basename( __FILE__ ) );
		}
	);
	return;
}

// if (!defined('WPCF7_AUTOP')) {
// define('WPCF7_AUTOP', false);
// }

if ( ! defined( 'CF7MLS_PLUGIN_DIR' ) ) {
	define( 'CF7MLS_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
}
if ( ! defined( 'CF7MLS_PLUGIN_URL' ) ) {
	define( 'CF7MLS_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
}
if ( ! defined( 'CF7MLS_NTA_VERSION' ) ) {
	define( 'CF7MLS_NTA_VERSION', '2.7.5' );
}

spl_autoload_register(
	function ( $class ) {
		$prefix   = __NAMESPACE__;
		$base_dir = __DIR__ . '/inc';

		$len = strlen( $prefix );
		if ( strncmp( $prefix, $class, $len ) !== 0 ) {
			return;
		}

		$relative_class_name = substr( $class, $len );

		$file = $base_dir . str_replace( '\\', '/', $relative_class_name ) . '.php';

		if ( file_exists( $file ) ) {
			require $file;
		}
	}
);

if ( ! function_exists( 'cf7mls_plugin_init' ) ) {
	function cf7mls_plugin_init() {
		// load text domain
		require_once plugin_dir_path( __FILE__ ) . 'inc/I18n.php';
		// CF7DB
		require_once plugin_dir_path( __FILE__ ) . 'inc/cf7db.php';
		// backend
		require_once plugin_dir_path( __FILE__ ) . 'inc/admin/init.php';
		require_once plugin_dir_path( __FILE__ ) . 'inc/admin/settings.php';
		require_once plugin_dir_path( __FILE__ ) . 'inc/admin/review.php';
		// frontend
		require_once plugin_dir_path( __FILE__ ) . 'inc/frontend/init.php';
		require_once plugin_dir_path( __FILE__ ) . 'inc/frontend/validation.php';
	}
}

add_action( 'plugins_loaded', 'cf7mls_plugin_init' );

