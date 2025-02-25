<?php
define( 'WP_CACHE', true ); // Added by WP Rocket

// BEGIN A2 CRON DISABLE
define('DISABLE_WP_CRON', true);
// END A2 CRON DISABLE


/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the website, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'vxwhqvbg_a2wp383' );

/** Database username */
define( 'DB_USER', 'vxwhqvbg_a2wp383' );

/** Database password */
define( 'DB_PASSWORD', 'g0IpS((1s9' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'dzygrzbrr8j1x6ec6t4ruokbpzc2unkx9glv8plel4avw6t2lj8mbhho2pfq7zmm' );
define('SECURE_AUTH_KEY',  '}H1-Po-S,neic]0B3v69ph+4.NG[.?7B+C2^X@has3+d<j*^KOB^Nt6ryl-O<-v@');
define('LOGGED_IN_KEY',    '{q+_V2f,R%j=jrr-i*V[S|wXhZD}X>0ym,_Q1h+-=RIK6+Ner4]5.+ RDX~~i{&b');
define('NONCE_KEY',        'pWIE0D,vJF% zci8ej3O}$1yUc^w6OI|LCx1uje.yB/[g_qo}{xTfMw@Y +aSUb]');
define( 'AUTH_SALT',        'lhslebs1cujjbpka3xvkqc8ghxrojucvlyetf6qaslmlkzytmzhwzddaasg2cgmg' );
define('SECURE_AUTH_SALT', 'z?_A:nl8oYbgfJWJ*_k9A|a~G[Ha#IIRwgmtaH)Z_ceG|asAx8+-_b+`/yj$p1:,');
define('LOGGED_IN_SALT',   'ik--r#]4!Ka0O+=D{vR}0s|m,.Fl`z]p|E?ziYE(sUa:2hO?:x_])7x{B[<5(FXI');
define('NONCE_SALT',       '`%XKBLp3~)@`$9tmI] }-9h$t~DJDOAxBt2kNNNR5G+qN#gHG+y{.Qo9Y<8H=+Cd');

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 *
 * At the installation time, database tables are created with the specified prefix.
 * Changing this value after WordPress is installed will make your site think
 * it has not been installed.
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/#table-prefix
 */
$table_prefix = 'wpgs_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://developer.wordpress.org/advanced-administration/debug/debug-wordpress/
 */
define( 'WP_DEBUG', false );
define('WP_MEMORY_LIMIT', '512M');

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
