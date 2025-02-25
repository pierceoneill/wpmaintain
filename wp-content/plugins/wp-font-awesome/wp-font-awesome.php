<?php
   /*
   Plugin Name: WP Font Awesome
   Plugin URI: https://wordpress.org/plugins/wp-font-awesome/
   Description: This plugin allows the easily embed Font Awesome to your site.
   Version: 1.8.0
   Author: Zayed Baloch
   Author URI: https://www.zayedbaloch.com/
   License: GPL2
   */

defined('ABSPATH') or die("No script kiddies please!");

define('ZB_FAWE_VERSION', '1.8.0');
define('ZB_FAWE_URL', plugins_url('', __FILE__));
define('ZB_FAWE_TEXTDOMAIN', 'zb_font_awesome');

function zb_load_font_awesome_textdomain() {
    load_plugin_textdomain(ZB_FAWE_TEXTDOMAIN, false, dirname(plugin_basename(__FILE__)) . '/languages');
}
add_action('init', 'zb_load_font_awesome_textdomain');


function zb_enqueue_font_awesome_styles() {
    wp_register_style('fontawesome-css-6', ZB_FAWE_URL . '/font-awesome/css/fontawesome-all.min.css', array(), ZB_FAWE_VERSION);
    wp_enqueue_style('fontawesome-css-6');

    wp_register_style('fontawesome-css-4', ZB_FAWE_URL . '/font-awesome/css/v4-shims.min.css', array(), ZB_FAWE_VERSION);
    wp_enqueue_style('fontawesome-css-4');
}
add_action('wp_enqueue_scripts', 'zb_enqueue_font_awesome_styles');

function wp_font_awesome_style_admin() {
  $current_screen = get_current_screen();
  if ( $current_screen && $current_screen->id === 'edit' ) {
      wp_enqueue_style('wp-font-awesome-script-admin', ZB_FAWE_URL.'/style.css');
  }
}
add_action('admin_enqueue_scripts', 'wp_font_awesome_style_admin');


function wp_fa_shortcode( $atts ) {
  extract( shortcode_atts( array(
    'icon' => 'home',
    'size' => '',
    'color' => '',
    'sup' => ''
  ), $atts ) );

  $size_class = $size ? ' fa-' . esc_attr($size) : '';
  $color_style = $color ? ' style="color: ' . esc_attr($color) . '"' : '';

  $icon_class = 'fa fa-' . str_replace('fa-', '', $icon);

  if ( strtolower($sup) === 'yes' ) {
    return '<sup><i class="' . esc_attr($icon_class) . $size_class . '"' . $color_style . '></i></sup>';
  } else {
    return '<i class="' . esc_attr($icon_class) . $size_class . '"' . $color_style . '></i>';
  }
}


function wp_fa5s_shortcode( $atts ) {
  extract( shortcode_atts( array(
    'icon' => 'home',
    'size' => '',
    'color' => '',
    'sup' => ''
  ), $atts ) );

  $size_class = $size ? ' fa-' . esc_attr($size) : '';
  $color_style = $color ? ' style="color: ' . esc_attr($color) . '"' : '';

  $icon_class = 'fas fa-' . str_replace('fa-', '', $icon);

  if ( strtolower($sup) === 'yes' ) {
    return '<sup><i class="' . esc_attr($icon_class) . $size_class . '"' . $color_style . '></i></sup>';
  } else {
    return '<i class="' . esc_attr($icon_class) . $size_class . '"' . $color_style . '></i>';
  }
}


function wp_fa5r_shortcode( $atts ) {
  extract( shortcode_atts( array(
    'icon' => 'home',
    'size' => '',
    'color' => '',
    'sup' => ''
  ), $atts ) );

  $size_class = $size ? ' fa-' . esc_attr($size) : '';
  $color_style = $color ? ' style="color: ' . esc_attr($color) . '"' : '';

  $icon_class = 'far fa-' . str_replace('fa-', '', $icon);

  if ( strtolower($sup) === 'yes' ) {
    return '<sup><i class="' . esc_attr($icon_class) . $size_class . '"' . $color_style . '></i></sup>';
  } else {
    return '<i class="' . esc_attr($icon_class) . $size_class . '"' . $color_style . '></i>';
  }
}


function wp_fa5b_shortcode( $atts ) {
  extract( shortcode_atts( array(
    'icon' => 'home',
    'size' => '',
    'color' => '',
    'sup' => ''
  ), $atts ) );

  $size_class = $size ? ' fa-' . esc_attr($size) : '';
  $color_style = $color ? ' style="color: ' . esc_attr($color) . '"' : '';

  $icon_class = 'fab fa-' . str_replace('fa-', '', $icon);

  if ( strtolower($sup) === 'yes' ) {
    return '<sup><i class="' . esc_attr($icon_class) . $size_class . '"' . $color_style . '></i></sup>';
  } else {
    return '<i class="' . esc_attr($icon_class) . $size_class . '"' . $color_style . '></i>';
  }
}


add_shortcode( 'wpfa', 'wp_fa_shortcode' );
add_shortcode( 'wpfa5s', 'wp_fa5s_shortcode' );
add_shortcode( 'wpfa5r', 'wp_fa5r_shortcode' );
add_shortcode( 'wpfa5b', 'wp_fa5b_shortcode' );

add_filter('wp_nav_menu_items', 'do_shortcode');
add_filter('widget_text', 'do_shortcode');
add_filter('widget_title', 'do_shortcode');

function wpfa_add_shortcode_to_title( $title ){
  return do_shortcode($title);
}
add_filter( 'the_title', 'wpfa_add_shortcode_to_title' );



function wpfontawesome_register_setting_page() {
  add_options_page('WP Font Awesome', 'WP Font Awesome', 'manage_options', 'wpFontAwesome', 'wpfontawesome_setting_page');
}
add_action('admin_menu', 'wpfontawesome_register_setting_page');

function wpfontawesome_setting_page(){
?>

  <h2>WP Font Awesome &nbsp;&nbsp;<span style="font-size:50%;font-weight: normal;">Version: 1.7</span></h2>
  <p>This plugin allows you to easily embed Font Awesome icon to your site with simple shortcodes.</p>

  <h2>Shortcodes</h2>
  <p>Introduced three new shortcode for Font Awesome support.</p>

  <strong>Font Awesome 5</strong>
  <p><code>[wpfa5s icon="home" size="3x" color="#336699"]</code> for Solid style.</p>
  <p><code>[wpfa5r icon="user" color="red"]</code> for Regular style. <em>support only in few icon</em>.</p>
  <p><code>[wpfa5b icon="wordpress" size="5x" color="#3B5998"]</code> for Brands.</p>
  <br/>
  <strong>Font Awesome 4.7</strong>
  <p><code>[wpfa icon="gear" color="green"]</code>.</p>

  <br/>
  <h2>Size</h2>
  <p><code>xs</code>, <code>sm</code>, <code>lg</code>, <code>2x</code>, <code>3x</code>, <code>5x</code>, <code>7x</code>, <code>10x</code></p>

  <br/><hr/>
  <p><strong>Note</strong>: The <code>fa</code> prefix has been deprecated in version 5. The new default is the <code>fas</code> solid style <code>far</code> regular style and the <code>fab</code> style for brands.</p>

  <em>WP Font Awesome plugin still support Font Awesome version 4</em>

<?php
}

// Add custom action links
add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'wpfontawesome_zb_action_link' );

function wpfontawesome_zb_action_link( $links ) {
  $plugin_links = array(
    '<a href="' . admin_url( 'options-general.php?page=wpFontAwesome' ) . '">' . __( 'Help', 'zb_font_awesome' ) . '</a>',
  );
  return array_merge( $plugin_links, $links );
}

function wp_font_awesome_add_mce_button() {
  // Check user permissions
  if ( ! current_user_can( 'edit_posts' ) && ! current_user_can( 'edit_pages' ) ) {
    return;
  }
  
  // Check if WYSIWYG is enabled
  if ( 'true' == get_user_option( 'rich_editing' ) ) {
    add_filter( 'mce_external_plugins', 'wp_font_awesome_add_tinymce_plugin' );
    add_filter( 'mce_buttons', 'wp_font_awesome_register_mce_button' );
  }
}
add_action( 'admin_head', 'wp_font_awesome_add_mce_button' );

// Register new button in the editor
function wp_font_awesome_register_mce_button( $buttons ) {
  array_push( $buttons, 'shortcode_wp_font_awesome_insert' );
  return $buttons;
}

// Declare a script for the new button
// The script will insert the shortcode on the click event
function wp_font_awesome_add_tinymce_plugin( $plugin_array ) {
  $plugin_array['shortcode_wp_font_awesome_insert'] = ZB_FAWE_URL .'/script.js';
  return $plugin_array;
}
