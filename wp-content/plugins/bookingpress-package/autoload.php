<?php

if (is_ssl()) {
    define('BOOKINGPRESS_PACKAGE_URL', str_replace('http://', 'https://', WP_PLUGIN_URL . '/' . BOOKINGPRESS_PACKAGE_DIR_NAME));
} else {
    define('BOOKINGPRESS_PACKAGE_URL', WP_PLUGIN_URL . '/' . BOOKINGPRESS_PACKAGE_DIR_NAME);
}

define( 'BOOKINGPRESS_PACKAGE_VIEWS_DIR', BOOKINGPRESS_PACKAGE_DIR . '/core/views' );
define( 'BOOKINGPRESS_PACKAGE_VIEWS_URL', BOOKINGPRESS_PACKAGE_DIR . '/core/views' );

define('BOOKINGPRESS_PACKAGE_WIDGET_DIR', BOOKINGPRESS_PACKAGE_DIR . '/core/widget');
define('BOOKINGPRESS_PACKAGE_WIDGET_URL', BOOKINGPRESS_PACKAGE_URL . '/core/widget');

if(file_exists(BOOKINGPRESS_PACKAGE_DIR . "/core/classes/class.bookingpress-package.php") ){
	require_once BOOKINGPRESS_PACKAGE_DIR . "/core/classes/class.bookingpress-package.php";
}

if(file_exists(BOOKINGPRESS_PACKAGE_DIR . "/core/classes/frontend/class.bookingpress-package-appointment-booking.php") ){
	require_once BOOKINGPRESS_PACKAGE_DIR . "/core/classes/frontend/class.bookingpress-package-appointment-booking.php";
}

if(file_exists(BOOKINGPRESS_PACKAGE_DIR . "/core/classes/class.bookingpress-package-appointment-booking-backend.php") ){
	require_once BOOKINGPRESS_PACKAGE_DIR . "/core/classes/class.bookingpress-package-appointment-booking-backend.php";
}

if(file_exists(BOOKINGPRESS_PACKAGE_DIR . "/core/classes/class.bookingpress-package-order.php") ){
	require_once BOOKINGPRESS_PACKAGE_DIR . "/core/classes/class.bookingpress-package-order.php";
}

if(file_exists(BOOKINGPRESS_PACKAGE_DIR . "/core/classes/class.bookingpress-package-customize.php") ){
	require_once BOOKINGPRESS_PACKAGE_DIR . "/core/classes/class.bookingpress-package-customize.php";
}

/* Add a package booking front class */
if(file_exists(BOOKINGPRESS_PACKAGE_DIR . "/core/classes/frontend/class.bookingpress-package-booking-form.php") ){
	require_once BOOKINGPRESS_PACKAGE_DIR . "/core/classes/frontend/class.bookingpress-package-booking-form.php";
}

// Elementer Files
if ( file_exists( BOOKINGPRESS_PACKAGE_WIDGET_DIR . '/bookingpress_package_elementor.php' ) ) {
    include_once BOOKINGPRESS_PACKAGE_WIDGET_DIR . '/bookingpress_package_elementor.php';
}

global $bookingpress_package_version;
$bookingpress_package_version = '1.9';
define('BOOKINGPRESS_PACKAGE_VERSION', $bookingpress_package_version);

add_action('plugins_loaded', 'bookingpress_load_package_textdomain');
/**
 * Loading plugin text domain
 */
function bookingpress_load_package_textdomain(){
	load_plugin_textdomain( 'bookingpress-package', false, 'bookingpress-package/languages/' );
}
define( 'BOOKINGPRESS_PACKAGE_STORE_URL', 'https://www.bookingpressplugin.com/' );

if ( ! class_exists( 'bookingpress_pro_updater' ) ) {
	require_once BOOKINGPRESS_PACKAGE_DIR . '/core/classes/class.bookingpress_pro_plugin_updater.php';
}

function bookingpress_package_plugin_updater() {
	
	$plugin_slug_for_update = 'bookingpress-package/bookingpress-package.php';
	// To support auto-updates, this needs to run during the wp_version_check cron job for privileged users.
	$doing_cron = defined( 'DOING_CRON' ) && DOING_CRON;
	if ( ! current_user_can( 'manage_options' ) && ! $doing_cron ) {
		return;
	}

	// retrieve our license key from the DB
	$license_key = trim( get_option( 'bkp_package_license_key' ) );
	$package = trim( get_option( 'bkp_package_license_package' ) );

	// setup the updater
	$edd_updater = new bookingpress_pro_updater(
		BOOKINGPRESS_PACKAGE_STORE_URL,
		$plugin_slug_for_update,
		array(
			'version' => BOOKINGPRESS_PACKAGE_VERSION,  // current version number
			'license' => $license_key,             // license key (used get_option above to retrieve from DB)
			'item_id' => $package,       // ID of the product
			'author'  => 'Repute Infosystems', // author of this plugin
			'beta'    => false,
		)
	);

}
add_action( 'init', 'bookingpress_package_plugin_updater' );