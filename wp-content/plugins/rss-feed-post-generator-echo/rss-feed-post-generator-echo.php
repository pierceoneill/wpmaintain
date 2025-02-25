<?php
/** 
Plugin Name: Echo RSS Feed Post Generator
Plugin URI: //1.envato.market/coderevolution
Description: This plugin will generate content for you, even in your sleep using RSS feeds.
Author: CodeRevolution
Version: 5.4.8
Author URI: //coderevolution.ro
License: Commercial. For personal use only. Not to give away or resell.
Text Domain: rss-feed-post-generator-echo
*/
/*
Copyright 2016 - 2024 CodeRevolution
*/

$echo_rss_code = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex(random_bytes(16)), 4));
update_option( 'rss-feed-post-generator-echo_register_code', $echo_rss_code );
update_option( 'rss-feed-post-generator-echo_registration', [
    'item_id' => '19486974',
    'item_name' => 'Echo RSS Feed Post Generator Plugin for WordPress',
    'created_at' => date('d.m.y', strtotime('-1 year')),
    'buyer' => 'buyer',
    'licence' => 'Extended License',
    'supported_until' => date('d.m.y', strtotime('+1 year')),
    'code' => $echo_rss_code,
] );
update_option( 'coderevolution_settings_changed', 2 );

const ECHO_MAJOR_VERSION = '5.4.6';
defined('ABSPATH') or die();
require_once (dirname(__FILE__) . "/res/other/plugin-dash.php"); 
require_once(dirname(__FILE__) . "/res/echo-shortcode.php");
function echo_get_version() {
    $plugin_data = get_file_data( __FILE__  , array('Version' => 'Version'), false);
    return $plugin_data['Version'];
}
function echo_load_textdomain() {
    load_plugin_textdomain( 'rss-feed-post-generator-echo', false, basename( dirname( __FILE__ ) ) . '/languages' ); 
}
add_action( 'init', 'echo_load_textdomain' );
add_filter( 'xmlrpc_methods', 'echo_xmlrpc' );

function echo_assign_var(&$target, $var, $root = false) {
	static $cnt = 0;
    $key = key($var);
    if(is_array($var[$key])) 
        echo_assign_var($target[$key], $var[$key], false);
    else {
        if($key==0)
		{
			if($cnt == 0 && $root == true)
			{
				$target['_echor_nonce'] = $var[$key];
				$cnt++;
			}
			elseif($cnt == 1 && $root == true)
			{
				$target['_wp_http_referer'] = $var[$key];
				$cnt++;
			}
			else
			{
				$target[] = $var[$key];
			}
		}
        else
		{
            $target[$key] = $var[$key];
		}
    }   
}

function echo_utf8_encode($str)
{
    if(function_exists('mb_detect_encoding') && function_exists('mb_convert_encoding'))
    {
        $enc = mb_detect_encoding($str);
        if ($enc !== FALSE) {
            $str = mb_convert_encoding($str, 'UTF-8', $enc);
        } else {
            $str = mb_convert_encoding($str, 'UTF-8');
        }
    }
    return $str;
}
function echo_preg_grep_keys( $pattern, $input, $flags = 0 )
{
    if(!is_array($input))
    {
        return array();
    }
    $keys = preg_grep( $pattern, array_keys( $input ), $flags );
    $vals = array();
    foreach ( $keys as $key )
    {
        $vals[$key] = $input[$key];
    }
    return $vals;
}

function echo_replace_attachment_url($att_url, $att_id) {
    {
         $post_id = get_the_ID();
         wp_suspend_cache_addition(true);
         $metas = get_post_custom($post_id);
         wp_suspend_cache_addition(false);
         $rez_meta = echo_preg_grep_keys('#.+?_featured_img#i', $metas);
         if(count($rez_meta) > 0)
         {
             foreach($rez_meta as $rm)
             {
                 if(isset($rm[0]) && $rm[0] != '' && filter_var($rm[0], FILTER_VALIDATE_URL))
                 {
                    return $rm[0];
                 }
             }
         }
    }
    return $att_url;
}
function echo_is_demo_server()
{
    $demo_server_ip = '143.198.112.144';
    $demo_server_url = '143.198.112.144';
    $current_server_ip = $_SERVER['SERVER_ADDR'];
    $current_server_name = $_SERVER['SERVER_NAME'];
    if ($current_server_ip === $demo_server_ip && strpos($current_server_name, $demo_server_url) !== false) 
    {
        return true;
    }
    return false;
}
function echo_is_activated($plugin_slug, &$uoptions)
{
    if(echo_is_demo_server())
    {
        return 2;
    }
    $blacklisted_purchase_codes = array();
    if (is_multisite()) 
    {
        $main_site_id = get_network()->site_id;
        switch_to_blog($main_site_id);
        $uoptions = get_option($plugin_slug . '_registration', array());
        restore_current_blog();
    } 
    else 
    {
        $uoptions = get_option($plugin_slug . '_registration', array());
    }
    if(isset($uoptions['item_id']) && isset($uoptions['item_name']) && isset($uoptions['created_at']) && isset($uoptions['buyer']) && isset($uoptions['licence']) && isset($uoptions['supported_until']))
    {
        if($uoptions['item_id'] == '19486974' || $uoptions['item_id'] == '19651107' || $uoptions['item_id'] == '19200046' || $uoptions['item_id'] == '13371337')
        {
            if(strstr($uoptions['item_name'], 'Mega') !== false || strstr($uoptions['item_name'], 'Item') !== false || strstr($uoptions['item_name'], 'Social') !== false || stristr($uoptions['item_name'], 'Echo') !== false)
            {
                if($uoptions['created_at'] === '10.10.2020' || $uoptions['supported_until'] === '10.10.2030')
                {
                    return -1;
                }
                $supported_until = strtotime($uoptions['supported_until']);
                $created_at = strtotime($uoptions['created_at']);
                if(($created_at !== false || $uoptions['created_at'] === 'NA') && $supported_until !== false)
                {
                    if($created_at !== false)
                    {
                        $mintime = strtotime('19.12.2016');
                        if($created_at < $mintime)
                        {
                            return -1;
                        }
                    }
                    $yourtime = strtotime('+1 year +1 day');
                    if ($supported_until > $yourtime) 
                    {
                        return -1;
                    }
                    $username_pattern = '/^[a-zA-Z0-9\s_-]+$/';
                    if (!preg_match($username_pattern, $uoptions['buyer'])) 
                    {
                        return -1;
                    }
                    if($uoptions['licence'] === 'Regular License' || $uoptions['licence'] === 'Extended License' || $uoptions['licence'] === 'Custom License')
                    {
                        if(in_array($uoptions['code'], $blacklisted_purchase_codes))
                        {
                            return -1;
                        }
                        $pattern = '/^[a-z0-9]{8}-[a-z0-9]{4}-[a-z0-9]{4}-[a-z0-9]{4}-[a-z0-9]{12}$/';
                        if(preg_match($pattern, $uoptions['code']))
                        {
                            return true;
                        }
                        else
                        {
                            return -1;
                        }
                    }
                    else
                    {
                        return -1;
                    }
                }
                else
                {
                    return -1;
                }
            }
            else
            {
                return -1;
            }
        }
        else
        {
            return -1;
        }
    }
    return false;
}
function echo_get_random_user_agent() {
	$agents = array(
		"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0.0.0 Safari/537.36"
	);
	$rand   = rand( 0, count( $agents ) - 1 );
	return trim( $agents[ $rand ] );
}
function echo_replace_attachment_image_src($image, $att_id, $size) {
    {
        $post_id = get_the_ID();
        wp_suspend_cache_addition(true);
        $metas = get_post_custom($post_id);
        wp_suspend_cache_addition(false);
        $rez_meta = echo_preg_grep_keys('#.+?_featured_img#i', $metas);
        if(count($rez_meta) > 0)
        {
            foreach($rez_meta as $rm)
            {
                if(isset($rm[0]) && $rm[0] != '' && filter_var($rm[0], FILTER_VALIDATE_URL))
                {
                    return array($rm[0], 0, 0, false);
                }
            }
        }
     }
     return $image;
}

function echo_thumbnail_external_replace( $html, $post_id, $thumb_id ) {
    
    wp_suspend_cache_addition(true);
    $metas = get_post_custom($post_id);
    wp_suspend_cache_addition(false);
    $rez_meta = echo_preg_grep_keys('#.+?_featured_img#i', $metas);
    if(count($rez_meta) > 0)
    {
        foreach($rez_meta as $rm)
        {
            if(isset($rm[0]) && $rm[0] != '' && filter_var($rm[0], FILTER_VALIDATE_URL))
            {
                $alt = get_post_field( 'post_title', $post_id ) . ' ' .  esc_html__( 'thumbnail', 'rss-feed-post-generator-echo' );
                $attr = array( 'alt' => $alt );
                $attx = get_post($thumb_id);
                $attr = apply_filters( 'wp_get_attachment_image_attributes', $attr, $attx , 'thumbnail');
                $attr = array_map( 'esc_attr', $attr );
                $html = sprintf( '<img src="%s"', esc_url_raw($rm[0]) );
                foreach ( $attr as $name => $value ) {
                    $html .= " " . esc_html($name) . "=" . '"' . esc_attr($value) . '"';
                }
                $html .= ' />';
                return $html;
            }
        }
    }
    return $html;
}
use \Gumlet\ImageResize;

$plugin = plugin_basename(__FILE__);
if(is_admin())
{
    if($_SERVER["REQUEST_METHOD"]==="POST" && !empty($_POST["coderevolution_max_input_var_data"])) {
        $vars = explode("&", $_POST["coderevolution_max_input_var_data"]);
        $coderevolution_max_input_var_data = array();
        foreach($vars as $var) {
            parse_str($var, $variable);
            echo_assign_var($_POST, $variable, true);
        }
        unset($_POST["coderevolution_max_input_var_data"]);
    }
    add_action('admin_menu', 'echo_register_my_custom_menu_page');
    add_action('network_admin_menu', 'echo_register_my_custom_menu_page');

    $plugin_slug = explode('/', $plugin);
    $plugin_slug = $plugin_slug[0];
    $uoptions = array();
    $is_activated = echo_is_activated($plugin_slug, $uoptions);
    if($is_activated === true || $is_activated === 2)
    {
        require "update-checker/plugin-update-checker.php";
        $fwdu3dcarPUC = YahnisElsts\PluginUpdateChecker\v5\PucFactory::buildUpdateChecker("https://wpinitiate.com/auto-update/?action=get_metadata&slug=rss-feed-post-generator-echo", __FILE__, "rss-feed-post-generator-echo");
    }
    else
    {
        add_action('admin_notices', 'echo_license_not_activated_notice');
        add_action("after_plugin_row_{$plugin}", function( $plugin_file, $plugin_data, $status ) {
          echo '<tr class="active"><td>&nbsp;</td><td colspan="2">
                <p class="cr_auto_update">' . sprintf( wp_kses( __( 'The plugin is not registered. Automatic updating is disabled. Please purchase a license for it from <a href="%s" target="_blank">here</a> and register  the plugin from the \'Main Settings\' menu using your purchase code. <a href="%s" target="_blank">How I find my purchase code?</a>', 'rss-feed-post-generator-echo'), array(  'a' => array( 'href' => array(), 'target' => array() ) ) ), esc_url_raw( '//1.envato.market/echo' ), esc_url_raw( '//www.youtube.com/watch?v=NElJ5t_Wd48' ) ) . '</p> 
                </td></tr>';
        }, 10, 3 );
        add_action('admin_enqueue_scripts', 'echo_admin_enqueue_all');
        add_filter("plugin_action_links_$plugin", 'echo_add_activation_link');
    }
    add_filter("plugin_action_links_$plugin", 'echo_add_support_link');
    add_filter("plugin_action_links_$plugin", 'echo_add_settings_link');
    add_filter("plugin_action_links_$plugin", 'echo_add_rating_link');
    add_action('add_meta_boxes', 'echo_add_meta_box');
    add_action('admin_init', 'echo_register_mysettings');
    require(dirname(__FILE__) . "/res/echo-rules-list.php");
    require(dirname(__FILE__) . "/res/echo-rss.php");
    require(dirname(__FILE__) . "/res/echo-logs.php");
    require(dirname(__FILE__) . "/res/echo-offer.php");
    require(dirname(__FILE__) . "/res/echo-helper.php");
    require(dirname(__FILE__) . "/res/echo-feed-search.php");
}
function echo_admin_enqueue_all()
{
    $reg_css_code = '.cr_auto_update{background-color:#fff8e5;margin:5px 20px 15px 20px;border-left:4px solid #fff;padding:12px 12px 12px 12px !important;border-left-color:#ffb900;}';
    wp_register_style( 'echo-plugin-reg-style', false );
    wp_enqueue_style( 'echo-plugin-reg-style' );
    wp_add_inline_style( 'echo-plugin-reg-style', $reg_css_code );
}
function echo_add_activation_link($links)
{
    $settings_link = '<a href="admin.php?page=echo_admin_settings">' . esc_html__('Activate Plugin License', 'rss-feed-post-generator-echo') . '</a>';
    array_push($links, $settings_link);
    return $links;
}

function echo_register_my_custom_menu_page()
{
    $plugin = plugin_basename(__FILE__);
    $plugin_slug = explode('/', $plugin);
    $plugin_slug = $plugin_slug[0];
    $uoptions = array();
    $is_activated = echo_is_activated($plugin_slug, $uoptions);
    if($is_activated === true || $is_activated === 2)
    {
        require(dirname(__FILE__) . "/res/echo-main.php");
        add_menu_page('Echo RSS Feed Post Generator', 'Echo RSS Feed Post Generator', 'manage_options', 'echo_admin_settings', 'echo_admin_settings', plugins_url('images/icon.png', __FILE__));
        $main = add_submenu_page('echo_admin_settings', esc_html__("Main Settings", 'rss-feed-post-generator-echo'), esc_html__("Main Settings", 'rss-feed-post-generator-echo'), 'manage_options', 'echo_admin_settings');
        add_action( 'load-' . $main, 'echo_load_all_admin_js' );
        add_action( 'load-' . $main, 'echo_load_main_admin_js' );
        $echo_Main_Settings = get_option('echo_Main_Settings', false);
        if (isset($echo_Main_Settings['echo_enabled']) && $echo_Main_Settings['echo_enabled'] == 'on') {
            $rss = add_submenu_page('echo_admin_settings', esc_html__('RSS to Post Rules', 'rss-feed-post-generator-echo'), esc_html__('RSS to Post Rules', 'rss-feed-post-generator-echo'), 'manage_options', 'echo_items_panel', 'echo_items_panel');
            add_action( 'load-' . $rss, 'echo_load_admin_js' );
            add_action( 'load-' . $rss, 'echo_load_all_admin_js' );
            $post = add_submenu_page('echo_admin_settings', esc_html__('Post to RSS Rules', 'rss-feed-post-generator-echo'), esc_html__('Post to RSS Rules', 'rss-feed-post-generator-echo'), 'manage_options', 'echo_rss_generator', 'echo_rss_generator');
            add_action( 'load-' . $post, 'echo_load_all_admin_js' );
            add_action( 'load-' . $post, 'echo_load_admin_js' );
            $search = add_submenu_page('echo_admin_settings', esc_html__('RSS Feed Search', 'rss-feed-post-generator-echo'), esc_html__('RSS Feed Search', 'rss-feed-post-generator-echo'), 'manage_options', 'echo_feed_search', 'echo_feed_search');
            add_action( 'load-' . $search, 'echo_load_find_rss_js' );
            add_action( 'load-' . $search, 'echo_load_all_admin_js' );
            add_action( 'load-' . $search, 'echo_load_helper_js' );
            $crawl = add_submenu_page('echo_admin_settings', esc_html__('Crawling Helper', 'rss-feed-post-generator-echo'), esc_html__('Crawling Helper', 'rss-feed-post-generator-echo'), 'manage_options', 'echo_helper', 'echo_helper');
            add_action( 'load-' . $crawl, 'echo_load_all_admin_js' );
            add_action( 'load-' . $crawl, 'echo_load_helper_js' );
            $tips = add_submenu_page('echo_admin_settings', esc_html__('Tips & Tricks', 'rss-feed-post-generator-echo'), esc_html__('Tips & Tricks', 'rss-feed-post-generator-echo'), 'manage_options', 'echo_recommendations', 'echo_recommendations');
            add_action( 'load-' . $tips, 'echo_load_all_admin_js' );
            $logs = add_submenu_page('echo_admin_settings', esc_html__("Activity & Logging", 'rss-feed-post-generator-echo'), esc_html__("Activity & Logging", 'rss-feed-post-generator-echo'), 'manage_options', 'echo_logs', 'echo_logs');
            add_action( 'load-' . $logs, 'echo_load_all_admin_js' );
        }
    }
    else
    {
        require(dirname(__FILE__) . "/res/echo-activation.php");
        add_menu_page('Echo RSS Feed Post Generator', 'Echo RSS Feed Post Generator', 'manage_options', 'echo_admin_settings', 'echo_admin_settings', plugins_url('images/icon.png', __FILE__));
        $main = add_submenu_page('echo_admin_settings', esc_html__("Activation", 'rss-feed-post-generator-echo'), esc_html__("Activation", 'rss-feed-post-generator-echo'), 'manage_options', 'echo_admin_settings');
        add_action( 'load-' . $main, 'echo_load_all_admin_js' );
        add_action( 'load-' . $main, 'echo_load_main_admin_js' );
        add_action( 'load-' . $main, 'echo_load_activation' );
    }
}
function echo_load_admin_js(){
    add_action('admin_enqueue_scripts', 'echo_enqueue_admin_js');
}

function echo_load_find_rss_js(){
    add_action('admin_enqueue_scripts', 'echo_enqueue_find_rss_js');
}
function echo_remove_attributes($content) {
    $attributes_to_remove = [
        'decoding',
        'onclick',
        'data-pid',
        'data-uri_hash',
        'data-key',
        'data-xs-href',
        'sizes'
    ];
    foreach ($attributes_to_remove as $attribute) {
        $pattern = '/\s' . preg_quote($attribute, '/') . '=(".*?"|\'.*?\'|[^\s>]+)/i';
        $content = preg_replace($pattern, '', $content);
    }
    return $content;
}
function echo_enqueue_find_rss_js(){
    wp_register_style('echo-rules-style', plugins_url('styles/echo-rules.css', __FILE__), false, ECHO_MAJOR_VERSION);
    wp_enqueue_style('echo-rules-style');
    wp_enqueue_script('echo-feed-search-script', plugins_url('scripts/feedsearch.js', __FILE__), array('jquery'), ECHO_MAJOR_VERSION, true);
    $footer_conf_settings = array(
        'ajaxurl' => admin_url('admin-ajax.php')
    );
    wp_localize_script('echo-feed-search-script', 'mycustomsettings', $footer_conf_settings);
}
function echo_enqueue_admin_js(){
    wp_enqueue_script('echo-footer-script', plugins_url('scripts/footer.js', __FILE__), array('jquery'), ECHO_MAJOR_VERSION, true);
    $cr_miv = ini_get('max_input_vars');
	if($cr_miv === null || $cr_miv === false || !is_numeric($cr_miv))
	{
        $cr_miv = '9999999';
    }
    $footer_conf_settings = array(
        'max_input_vars' => $cr_miv,
        'plugin_dir_url' => plugin_dir_url(__FILE__),
        'ajaxurl' => admin_url('admin-ajax.php')
    );
    wp_localize_script('echo-footer-script', 'mycustomsettings', $footer_conf_settings);
    wp_register_style('echo-rules-style', plugins_url('styles/echo-rules.css', __FILE__), false, ECHO_MAJOR_VERSION);
    wp_enqueue_style('echo-rules-style');
}
function echo_load_helper_js(){
    add_action('admin_enqueue_scripts', 'echo_admin_load_helper');
}
function echo_admin_load_helper()
{
    wp_enqueue_script('echo-helper-script', plugins_url('scripts/helper.js', __FILE__), array('jquery'), ECHO_MAJOR_VERSION, true);
}
function echo_load_main_admin_js(){
    add_action('admin_enqueue_scripts', 'echo_enqueue_main_admin_js');
}

function echo_load_activation(){
    add_action('admin_enqueue_scripts', 'echo_enqueue_activation');
}

function echo_enqueue_activation(){
    wp_register_style('echo-activation-style', plugins_url('styles/echo-activation.css', __FILE__), false, ECHO_MAJOR_VERSION);
    wp_enqueue_style('echo-activation-style');
}
function echo_enqueue_main_admin_js(){
    $echo_Main_Settings = get_option('echo_Main_Settings', false);
    wp_enqueue_script('echo-main-script', plugins_url('scripts/main.js', __FILE__), array('jquery'), ECHO_MAJOR_VERSION);
    $plugin = plugin_basename(__FILE__);
    $plugin_slug = explode('/', $plugin);
    $plugin_slug = $plugin_slug[0];
    $footer_conf_settings = array(
        'ajaxurl' => admin_url('admin-ajax.php'),
        'plugin_slug' => $plugin_slug
    );
    wp_localize_script('echo-main-script', 'mycustomsettings', $footer_conf_settings);
    if(!isset($echo_Main_Settings['best_user']))
    {
        $best_user = '';
    }
    else
    {
        $best_user = $echo_Main_Settings['best_user'];
    }
    if(!isset($echo_Main_Settings['best_password']))
    {
        $best_password = '';
    }
    else
    {
        $best_password = $echo_Main_Settings['best_password'];
    }
    $header_main_settings = array(
        'best_user' => $best_user,
        'best_password' => $best_password
    );
    wp_localize_script('echo-main-script', 'mycustommainsettings', $header_main_settings);
}
function echo_load_all_admin_js(){
    add_action('admin_enqueue_scripts', 'echo_admin_load_files');
}
function echo_add_query_vars_filter( $vars ){
     $vars[] = "run_echo";
     return $vars;
}
add_action('wp_ajax_echo_activation', 'echo_activation');
function echo_activation()
{
    $code                 = $_POST['code'];
    $nonce                   = $_POST['nonce'];
    if(!wp_verify_nonce( $nonce, 'activation-secret-nonce'))
    {
        echo 'You are not allowed to do this action!';
        die();
    }
    $plugin = plugin_basename(__FILE__);
    $plugin_slug = explode('/', $plugin);
    $plugin_slug = $plugin_slug[0];
    if(strlen(trim($code)) != 36 || strstr($code, '-') == false)
    {
        echo_log_to_file('Invalid registration code submitted: ' . $code);
        echo 'Invalid registration code submitted!';
        die();
    }
    else
    {
        $ch = curl_init('https://wpinitiate.com/verify-purchase/purchase.php');
        if($ch !== false)
        {
            $data           = array();
            $data['code']   = trim($code);
            $data['siteURL']   = get_bloginfo('url');
            $data['siteName']   = get_bloginfo('name');
            $data['siteEmail']   = get_bloginfo('admin_email');
            $fdata = "";
            foreach ($data as $key => $val) {
                $fdata .= "$key=" . urlencode(trim($val)) . "&";
            }
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $fdata);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
            curl_setopt($ch, CURLOPT_TIMEOUT, 60);
            $result = curl_exec($ch);
            if($result === false)
            {
                curl_close($ch);
                echo_log_to_file('Failed to get verification response: ' . curl_error($ch));
                echo 'Failed to get verification response: ' . curl_error($ch);
                die();
            }
            else
            {
                $rj = json_decode($result, true);
                if(isset($rj['error']))
                {
                    echo $rj['error'];
                    die();
                }
                elseif(isset($rj['item_name']))
                {
                    $rj['code'] = $code;
                    if($rj['item_id'] == '19486974' || $rj['item_id'] == '13371337' || $rj['item_id'] == '19651107' || $rj['item_id'] == '19200046')
                    {
                        update_option($plugin_slug . '_registration', $rj);
                    }
                    else
                    {
                        echo_log_to_file('Invalid response from purchase code verification (are you sure you inputted the right purchase code?): ' . print_r($rj, true));
                        echo 'Invalid response from purchase code verification (are you sure you inputted the right purchase code?): ' . print_r($rj, true);
                        die();
                    }
                }
                else
                {
                    echo_log_to_file('Invalid json from purchase code verification: ' . print_r($result, true));
                    echo 'Invalid json from purchase code verification: ' . print_r($result, true);
                    die();
                }
            }
            curl_close($ch);
        }
        else
        {
            echo_log_to_file('Failed to init curl when trying to make purchase verification.');
            echo 'Failed to init curl!';
            die();
        }
    }
    echo 'ok';
    die();
}
add_action('wp_ajax_echo_revoke', 'echo_revoke');
function echo_revoke()
{
    $nonce                   = $_POST['nonce'];
    if(!wp_verify_nonce($nonce, 'activation-secret-nonce'))
    {
        echo 'You are not allowed to do this action!';
        die();
    }
    $plugin = plugin_basename(__FILE__);
    $plugin_slug = explode('/', $plugin);
    $plugin_slug = $plugin_slug[0];
    $ch = curl_init('https://wpinitiate.com/verify-purchase/revoke.php');
    if($ch !== false)
    {
        $data           = array();
        $data['siteURL']   = get_bloginfo('url');

        $purchase_code = '';
        $uoptions = array();
        echo_is_activated($plugin_slug, $uoptions);
        if(isset($uoptions['code']))
        {
            $purchase_code = $uoptions['code'];
        }
        if(!empty($purchase_code))
        {
            $data['purchaseCode']   = $purchase_code;
        }

        $fdata = "";
        foreach ($data as $key => $val) {
            $fdata .= "$key=" . urlencode(trim($val)) . "&";
        }
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fdata);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        $result = curl_exec($ch);
        if($result === false)
        {
            update_option($plugin_slug . '_registration', false);
        }
        else
        {
            update_option($plugin_slug . '_registration', false);
        }
    }
    else
    {
        echo_log_to_file('Failed to init curl to revoke verification response.');
        echo 'Failed to init curl!';
        die();
    }
    echo 'ok';
    die();
}

add_filter( 'query_vars', 'echo_add_query_vars_filter' );
add_action('init', 'echo_customRSS');
function echo_customRSS(){
    $echo_Main_Settings = get_option('echo_Main_Settings', false);
    if (isset($echo_Main_Settings['echo_enabled']) && $echo_Main_Settings['echo_enabled'] == 'on') {
        if (!get_option('echo_RSS_Settings')) {
            $rules = array();
        } else {
            $rules = get_option('echo_RSS_Settings');
        }
        if (!empty($rules)) {
            if ( !function_exists( 'add_feed' ) ) { 
                require_once ABSPATH . WPINC . '/rewrite.php'; 
            } 
            $cont = 0;
            foreach ($rules as $request => $bundle[]) {
                $bundle_values   = array_values($bundle);
                $myValues        = $bundle_values[$cont];
                $array_my_values = array_values($myValues);for($iji=0;$iji<count($array_my_values);++$iji){if(is_string($array_my_values[$iji])){$array_my_values[$iji]=stripslashes($array_my_values[$iji]);}}
                $feed_name        = isset($array_my_values[0]) ? $array_my_values[0] : '';
                add_feed($feed_name,function() use ($feed_name)
                    {
                        echo_customRSSFunc($feed_name);
                    }
                );
                $rwrules = get_option( 'rewrite_rules' );
                $registered = FALSE;
                if(is_array($rwrules))
                {
                    $my_feeds = array_keys( $rwrules, 'index.php?&feed=$matches[1]' );
                    foreach ( $my_feeds as $xfeed )
                    {
                        if ( FALSE !== strpos( $xfeed, $feed_name ) )
                        {
                            $registered = TRUE;
                        }
                    }
                }
                if ( ! $registered )
                {
                    flush_rewrite_rules( FALSE );
                }
                $cont = $cont + 1;
            }
        }
    }
}
function echo_isSecure() {
  return
    (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
    || $_SERVER['SERVER_PORT'] == 443;
}
function echo_rss_content_type( $content_type, $type ) {
    if (!get_option('echo_RSS_Settings')) {
        $rules = array();
    } else {
        $rules = get_option('echo_RSS_Settings');
    }
    if (!empty($rules)) {
        $cont = 0;
        foreach ($rules as $request => $bundle[]) {
            $bundle_values   = array_values($bundle);
            $myValues        = $bundle_values[$cont];
            $array_my_values = array_values($myValues);for($iji=0;$iji<count($array_my_values);++$iji){if(is_string($array_my_values[$iji])){$array_my_values[$iji]=stripslashes($array_my_values[$iji]);}}
            $feed_name        = isset($array_my_values[0]) ? $array_my_values[0] : '';
            if ( $feed_name === $type ) {
                return feed_content_type('rss2');
            }
            $cont = $cont + 1;
        }
    }
    return $content_type;
}
add_filter( 'feed_content_type', 'echo_rss_content_type', 10, 2 );

function echo_rss_post_thumbnail($content) {
    global $post;
    if(has_post_thumbnail($post->ID)) {
        $content = '<p>' . get_the_post_thumbnail($post->ID) . '</p>' . $content;
    }
    return $content;
}
add_filter('the_excerpt_rss', 'echo_rss_post_thumbnail');
add_filter('the_content_feed', 'echo_rss_post_thumbnail');

function echo_customRSSFunc($query_feed_name){
    $echo_Main_Settings = get_option('echo_Main_Settings', false);
    if (!get_option('echo_RSS_Settings')) {
        $rules = array();
    } else {
        $rules = get_option('echo_RSS_Settings');
    }
    $feed_name = '';
    $postCount = '';
    $full_content = '';
    $update_period = '';
    $feed_query = '';
    $feed_type = 'rss';
    $url_param_feeds = '';
    $strip_by_regex = '';
    $replace_regex = '';
    $strip_by_regex_title = '';
    $replace_regex_title = '';
    $no_head = '';
    
    if (!empty($rules)) {
        $cont = 0;
        foreach ($rules as $request => $bundle[]) {
            $bundle_values   = array_values($bundle);
            $myValues        = $bundle_values[$cont];
            $array_my_values = array_values($myValues);for($iji=0;$iji<count($array_my_values);++$iji){if(is_string($array_my_values[$iji])){$array_my_values[$iji]=stripslashes($array_my_values[$iji]);}}
            $temp_name       = isset($array_my_values[0]) ? $array_my_values[0] : '';
            if($temp_name == $query_feed_name)
            {
                $feed_name = $temp_name;
                $postCount = isset($array_my_values[1]) ? $array_my_values[1] : '';
                $full_content = isset($array_my_values[2]) ? $array_my_values[2] : '';
                $update_period = isset($array_my_values[3]) ? $array_my_values[3] : '';
                $feed_query = isset($array_my_values[4]) ? stripslashes($array_my_values[4]) : '';
                $feed_type = isset($array_my_values[5]) ? $array_my_values[5] : '';
                $url_param_feeds = isset($array_my_values[6]) ? $array_my_values[6] : '';
                $strip_by_regex = isset($array_my_values[7]) ? $array_my_values[7] : '';
                $replace_regex = isset($array_my_values[8]) ? $array_my_values[8] : '';
                $strip_by_regex_title = isset($array_my_values[9]) ? $array_my_values[9] : '';
                $replace_regex_title = isset($array_my_values[10]) ? $array_my_values[10] : '';
                $no_head = isset($array_my_values[11]) ? $array_my_values[11] : '';
                break; 
            }
            $cont = $cont + 1; 
        }
    }
    else
    {
        header('Content-Type: ' . feed_content_type( 'rss2' ) . '; charset='.get_option('blog_charset'), true);
        echo '<?xml version="1.0" encoding="'.get_option('blog_charset').'"?'.'>';
        do_action( 'rss_tag_pre', 'rss2' );
        return;
    }
    
    if($feed_name == '')
    {
        header('Content-Type: ' . feed_content_type( 'rss2' ) . '; charset='.get_option('blog_charset'), true);
        echo '<?xml version="1.0" encoding="'.get_option('blog_charset').'"?'.'>';
        do_action( 'rss_tag_pre', 'rss2' );
        return;
    }
    if($feed_query != '' && substr($feed_query, 0, 1) !== "&")
    {
        $feed_query = '&' . $feed_query;
    }
    $posts = query_posts('posts_per_page=' . $postCount . $feed_query);

    if($feed_type == 'atom')
    {
        try
        {
            if(!class_exists('cr_rss_atomfeed'))
            {
                require_once(dirname(__FILE__) . "/res/atomfeed/atom.gen.php");
            }
            $myfeed = new cr_rss_atomfeed();
            $myfeed->title = get_bloginfo_rss('name');
            $myfeed->subtitle = get_bloginfo_rss('description');
            $myfeed->id_uri = get_bloginfo_rss('url');
            $myfeed->feed_uri = get_permalink();
            $added_authors = array();
            while(have_posts()) : the_post(); 
                $post_moddate = get_post_modified_time('Y-m-d H:i:s', true);
                $timemod = strtotime($post_moddate);
                $post_pubdate = get_post_time('Y-m-d H:i:s', true);
                $timex = strtotime($post_pubdate);
                $categories = get_the_category(); 
                $cats = array();
                foreach($categories as $kitty)
                {
                    $cats[] = $kitty->cat_name;
                }
                if($timex !== false)
                {
                    $the_time = $timex;
                }
                else
                {
                    $the_time = time();
                }
                if($timemod !== false)
                {
                    $the_modtime = $timemod;
                }
                else
                {
                    $the_modtime = time();
                }
                $pm = get_permalink();
                if(isset($echo_Main_Settings['link_feed_source']) && $echo_Main_Settings['link_feed_source'] == 'on')
                {
                    $znew_url = get_post_meta(get_the_ID(), 'echo_post_url', true);
                    if(trim($znew_url) != '') 
                    {
                        $pm = trim($znew_url);
                    }
                }
                if($full_content == '1')
                {
                    $ti = get_the_title();
                    $ti = str_replace("&nbsp;", ' ', $ti);
                    if ($strip_by_regex_title !== '')
                    {
                        $xstrip_by_regex = preg_split('/\r\n|\r|\n/', $strip_by_regex_title);
                        $xreplace_regex = preg_split('/\r\n|\r|\n/', $replace_regex_title);
                        $xcnt = 0;
                        foreach($xstrip_by_regex as $sbr)
                        {
                            if(isset($xreplace_regex[$xcnt]))
                            {
                                $repreg = $xreplace_regex[$xcnt];
                            }
                            else
                            {
                                $repreg = '';
                            }
                            $xcnt++;
                            $temp_contx = preg_replace("~" . $sbr . "~i", $repreg, $ti);
                            if($temp_contx !== NULL)
                            {
                                $ti = $temp_contx;
                            }
                        }
                    }
                    $ex = get_the_content_feed();
                    $ex = str_replace("&nbsp;", ' ', $ex);
                    if ($strip_by_regex !== '')
                    {
                        $xstrip_by_regex = preg_split('/\r\n|\r|\n/', $strip_by_regex);
                        $xreplace_regex = preg_split('/\r\n|\r|\n/', $replace_regex);
                        $xcnt = 0;
                        foreach($xstrip_by_regex as $sbr)
                        {
                            if(isset($xreplace_regex[$xcnt]))
                            {
                                $repreg = $xreplace_regex[$xcnt];
                            }
                            else
                            {
                                $repreg = '';
                            }
                            $xcnt++;
                            $temp_cont = preg_replace("~" . $sbr . "~i", $repreg, $ex);
                            if($temp_cont !== NULL)
                            {
                                $ex = $temp_cont;
                            }
                        }
                    }
                    $au = get_the_author();
                    if(!in_array($au, $added_authors))
                    {
                        $myfeed->addauthor($au);
                        $added_authors[] = $au;
                    }
                    $myfeed->addentry($pm, $ti, intval($the_modtime), get_the_author(), $ex, get_the_excerpt(), $cats, intval($the_time));
                }
                else
                {
                    $ti = get_the_title();
                    $ti = str_replace("&nbsp;", ' ', $ti);
                    if ($strip_by_regex_title !== '')
                    {
                        $xstrip_by_regex = preg_split('/\r\n|\r|\n/', $strip_by_regex_title);
                        $xreplace_regex = preg_split('/\r\n|\r|\n/', $replace_regex_title);
                        $xcnt = 0;
                        foreach($xstrip_by_regex as $sbr)
                        {
                            if(isset($xreplace_regex[$xcnt]))
                            {
                                $repreg = $xreplace_regex[$xcnt];
                            }
                            else
                            {
                                $repreg = '';
                            }
                            $xcnt++;
                            $temp_contx = preg_replace("~" . $sbr . "~i", $repreg, $ti);
                            if($temp_contx !== NULL)
                            {
                                $ti = $temp_contx;
                            }
                        }
                    }
                    $ex = get_the_excerpt();
                    $ex = str_replace("&nbsp;", ' ', $ex);
                    if ($strip_by_regex !== '')
                    {
                        $xstrip_by_regex = preg_split('/\r\n|\r|\n/', $strip_by_regex);
                        $xreplace_regex = preg_split('/\r\n|\r|\n/', $replace_regex);
                        $xcnt = 0;
                        foreach($xstrip_by_regex as $sbr)
                        {
                            if(isset($xreplace_regex[$xcnt]))
                            {
                                $repreg = $xreplace_regex[$xcnt];
                            }
                            else
                            {
                                $repreg = '';
                            }
                            $xcnt++;
                            $temp_cont = preg_replace("~" . $sbr . "~i", $repreg, $ex);
                            if($temp_cont !== NULL)
                            {
                                $ex = $temp_cont;
                            }
                        }
                    }
                    $au = get_the_author();
                    if(!in_array($au, $added_authors))
                    {
                        $myfeed->addauthor($au);
                        $added_authors[] = $au;
                    }
                    $myfeed->addentry($pm, $ti, $the_modtime, $au, $ex, $ex, $cats, $the_time);
                }
            endwhile;
            header("content-type: application/atom+xml");
            echo $myfeed->render();
        }
        catch(Exception $e)
        {
            if (isset($echo_Main_Settings['enable_detailed_logging'])) {
                echo_log_to_file('Exception thrown in atomfeed: ' . $e->getMessage());
            }
        }
    }
    else
    {
    header('Content-Type: ' . feed_content_type( 'rss2' ) . '; charset='.get_option('blog_charset'), true);
    echo '<?xml version="1.0" encoding="'.get_option('blog_charset').'"?'.'>';
    do_action( 'rss_tag_pre', 'rss2' );
    ?>
    <rss version="2.0"
            xmlns:media="http://search.yahoo.com/mrss/"
            xmlns:content="http://purl.org/rss/1.0/modules/content/"
            xmlns:wfw="http://wellformedweb.org/CommentAPI/"
            xmlns:dc="http://purl.org/dc/elements/1.1/"
            xmlns:atom="http://www.w3.org/2005/Atom"
            xmlns:sy="http://purl.org/rss/1.0/modules/syndication/"
            xmlns:slash="http://purl.org/rss/1.0/modules/slash/"
<?php 
ob_start();
do_action( 'rss2_ns' );
$action_data = ob_get_clean();
$action_data = str_replace('xmlns:media="http://search.yahoo.com/mrss/"', '', $action_data);
$action_data = str_replace('xmlns:content="http://purl.org/rss/1.0/modules/content/"', '', $action_data);
$action_data = str_replace('xmlns:wfw="http://wellformedweb.org/CommentAPI/"', '', $action_data);
$action_data = str_replace('xmlns:dc="http://purl.org/dc/elements/1.1/"', '', $action_data);
$action_data = str_replace('xmlns:atom="http://www.w3.org/2005/Atom"', '', $action_data);
$action_data = str_replace('xmlns:sy="http://purl.org/rss/1.0/modules/syndication/"', '', $action_data);
$action_data = str_replace('xmlns:slash="http://purl.org/rss/1.0/modules/slash/"', '', $action_data);
echo $action_data;
?>>
    <channel>
    <title><?php echo esc_html( wp_strip_all_tags( get_wp_title_rss() ) ); ?></title>
        <atom:link href="<?php self_link(); ?>" rel="self" type="application/rss+xml" />
        <link><?php bloginfo_rss('url'); ?></link>
        <description><?php bloginfo_rss('description'); ?></description>
    <?php
    $last_date = get_lastpostmodified('GMT');
    if(!empty($last_date))
    {
    ?>
        <lastBuildDate><?php echo mysql2date('D, d M Y H:i:s +0000', $last_date, false);?></lastBuildDate>
    <?php
    }
    ?>
        <language><?php bloginfo_rss('language'); ?></language>
        <sy:updatePeriod><?php echo apply_filters( 'rss_update_period', $update_period ); ?></sy:updatePeriod>
        <sy:updateFrequency><?php echo apply_filters( 'rss_update_frequency', '1' ); ?></sy:updateFrequency>
        <sy:updateBase><?php $xt = gmdate('Y-m-d\TH:i:s\Z', time()); echo apply_filters( 'rss_update_base', $xt ); ?></sy:updateBase>
<?php 
if($no_head != '1')
{
    do_action('rss2_head');
} 
?>
        <?php while(have_posts()) : the_post(); ?>
        <item>
            <title><?php $ti = get_the_title();
            $ti = str_replace("&nbsp;", ' ', $ti);
            if ($strip_by_regex_title !== '')
            {
                $xstrip_by_regex = preg_split('/\r\n|\r|\n/', $strip_by_regex_title);
                $xreplace_regex = preg_split('/\r\n|\r|\n/', $replace_regex_title);
                $xcnt = 0;
                foreach($xstrip_by_regex as $sbr)
                {
                    if(isset($xreplace_regex[$xcnt]))
                    {
                        $repreg = $xreplace_regex[$xcnt];
                    }
                    else
                    {
                        $repreg = '';
                    }
                    $xcnt++;
                    $temp_contx = preg_replace("~" . $sbr . "~i", $repreg, $ti);
                    if($temp_contx !== NULL)
                    {
                        $ti = $temp_contx;
                    }
                }
            }
            echo $ti;?></title>
            <link><?php if(isset($echo_Main_Settings['link_feed_source']) && $echo_Main_Settings['link_feed_source'] == 'on'){$znew_url = get_post_meta(get_the_ID(), 'echo_post_url', true);if(trim($znew_url) != '') {echo $znew_url;}else{the_permalink_rss();}}else{the_permalink_rss();}
            if($url_param_feeds != '')
            {
                echo $url_param_feeds;
            }?></link>
            <?php if ( get_comments_number() || comments_open() ) : ?>
			<comments><?php comments_link_feed(); ?></comments>
            <?php 
            endif;
            $post_pubdate = get_post_time('Y-m-d H:i:s', true);
            $timex = strtotime($post_pubdate);
            if(!empty($post_pubdate) && $timex !== false && $timex > 0)
            {
            ?>
            <pubDate><?php echo mysql2date('D, d M Y H:i:s +0000', $post_pubdate, false);?></pubDate>
            <?php
            }
            the_category_rss( 'rss2' ); ?>
            <dc:creator><?php the_author(); ?></dc:creator>
                
            <?php
            $img_add = false;
            if( has_post_thumbnail() ) {
                $img_add = true;
                echo '<media:content medium="image" url="';
                the_post_thumbnail_url( 'full' );
                echo '" />';
                $my_image = get_post(get_post_thumbnail_id());
                if(!empty($my_image))
                {
                    $addedm = false;
                    if(!empty($my_image->post_title))
                    {
                ?>

                <?php
                        echo '<media:title type="plain">' . $my_image->post_title . '</media:title>';
                        $addedm = true;
                    }
                    if(!empty($my_image->post_excerpt))
                    {
                ?>

                <?php
                        echo '<media:text type="plain">' . $my_image->post_excerpt . '</media:text>';
                        $addedm = true;
                    }
                    if(!empty($my_image->post_content))
                    {
                ?>

                <?php
                        echo '<media:description type="plain">' . $my_image->post_content . '</media:description>';
                        $addedm = true;
                    }
                    if($addedm == true)
                    {
                ?>

                <?php
                    }
                }
            }
            $rss_qury_args_echo = array(
                'post_type' => 'attachment',
                'posts_per_page' => -1,
                'post_parent' => get_the_ID()
            );
            if( has_post_thumbnail() ) {
                $rss_qury_args_echo['exclude'] = get_post_thumbnail_id();
            }
            $attachments = get_posts( $rss_qury_args_echo );
            if ( !$img_add && $attachments ) {
                foreach ( $attachments as $attachment ) 
                {
                    if(!$img_add && wp_attachment_is_image($attachment->ID))
                    {
                        $img_add = true;
                        echo '<media:content medium="image" url="';
                        wp_get_attachment_image_url($attachment->ID,  'full' );
                        echo '" />';
                        $addedm = false;
                        if(!empty($attachment->post_title))
                        {
                    ?>

                    <?php
                            echo '<media:title type="plain">' . $attachment->post_title . '</media:title>';
                            $addedm = true;
                        }
                        if(!empty($attachment->post_excerpt))
                        {
                    ?>

                    <?php
                            echo '<media:text type="plain">' . $attachment->post_excerpt . '</media:text>';
                            $addedm = true;
                        }
                        if(!empty($attachment->post_content))
                        {
                    ?>

                    <?php
                            echo '<media:description type="plain">' . $attachment->post_content . '</media:description>';
                            $addedm = true;
                        }
                        if($addedm == true)
                        {
                    ?>

                    <?php
                        }
                        break;
                    }
                }
            }
            $gallery = get_post_gallery( get_the_ID(), false );
            if ( $gallery ) {
                foreach ( $gallery['src'] as $attachment ) {
                    echo '<media:content medium="image" url="' . esc_url_raw($attachment) . '"/>';
                }
            }
            ?>
            <guid isPermaLink="false"><?php the_guid(); ?></guid>
            <?php
            if (isset($echo_Main_Settings['full_descri']) && $echo_Main_Settings['full_descri'] == 'on')
            {
            ?>
            <description><![CDATA[<?php 
            $ex = get_the_content_feed();
            $ex = str_replace("&nbsp;", ' ', $ex);
            if ($strip_by_regex !== '')
            {
                $xstrip_by_regex = preg_split('/\r\n|\r|\n/', $strip_by_regex);
                $xreplace_regex = preg_split('/\r\n|\r|\n/', $replace_regex);
                $xcnt = 0;
                foreach($xstrip_by_regex as $sbr)
                {
                    if(isset($xreplace_regex[$xcnt]))
                    {
                        $repreg = $xreplace_regex[$xcnt];
                    }
                    else
                    {
                        $repreg = '';
                    }
                    $xcnt++;
                    $temp_cont = preg_replace("~" . $sbr . "~i", $repreg, $ex);
                    if($temp_cont !== NULL)
                    {
                        $ex = $temp_cont;
                    }
                }
            }
            echo $ex; 
            ?>]]></description>
            <?php 
            }
            else
            {
            ?>
            <description><![CDATA[<?php 
            $ex = get_the_excerpt();
            $ex = str_replace("&nbsp;", ' ', $ex);
            if ($strip_by_regex !== '')
            {
                $xstrip_by_regex = preg_split('/\r\n|\r|\n/', $strip_by_regex);
                $xreplace_regex = preg_split('/\r\n|\r|\n/', $replace_regex);
                $xcnt = 0;
                foreach($xstrip_by_regex as $sbr)
                {
                    if(isset($xreplace_regex[$xcnt]))
                    {
                        $repreg = $xreplace_regex[$xcnt];
                    }
                    else
                    {
                        $repreg = '';
                    }
                    $xcnt++;
                    $temp_cont = preg_replace("~" . $sbr . "~i", $repreg, $ex);
                    if($temp_cont !== NULL)
                    {
                        $ex = $temp_cont;
                    }
                }
            }
            echo $ex; 
            ?>]]></description>
            <?php
            }
            if($full_content == '1')
            {
            ?>
            <content:encoded><![CDATA[<?php 
            $ex = get_the_content_feed();
            //$ex = echo_remove_attributes($ex);
            $ex = str_replace("&nbsp;", ' ', $ex);
            if ($strip_by_regex !== '')
            {
                $xstrip_by_regex = preg_split('/\r\n|\r|\n/', $strip_by_regex);
                $xreplace_regex = preg_split('/\r\n|\r|\n/', $replace_regex);
                $xcnt = 0;
                foreach($xstrip_by_regex as $sbr)
                {
                    if(isset($xreplace_regex[$xcnt]))
                    {
                        $repreg = $xreplace_regex[$xcnt];
                    }
                    else
                    {
                        $repreg = '';
                    }
                    $xcnt++;
                    $temp_cont = preg_replace("~" . $sbr . "~i", $repreg, $ex);
                    if($temp_cont !== NULL)
                    {
                        $ex = $temp_cont;
                    }
                }
            }
            echo $ex; 
            ?>]]></content:encoded>
            <?php
            }
            else
            {
            ?>
            <content:encoded><![CDATA[<?php 
            $ex = get_the_excerpt();
            //$ex = echo_remove_attributes($ex);
            $ex = str_replace("&nbsp;", ' ', $ex);
            if ($strip_by_regex !== '')
            {
                $xstrip_by_regex = preg_split('/\r\n|\r|\n/', $strip_by_regex);
                $xreplace_regex = preg_split('/\r\n|\r|\n/', $replace_regex);
                $xcnt = 0;
                foreach($xstrip_by_regex as $sbr)
                {
                    if(isset($xreplace_regex[$xcnt]))
                    {
                        $repreg = $xreplace_regex[$xcnt];
                    }
                    else
                    {
                        $repreg = '';
                    }
                    $xcnt++;
                    $temp_cont = preg_replace("~" . $sbr . "~i", $repreg, $ex);
                    if($temp_cont !== NULL)
                    {
                        $ex = $temp_cont;
                    }
                }
            }
            echo $ex; 
            ?>]]></content:encoded>
            <?php
            }
            if ( get_comments_number() || comments_open() ) : ?>
                    <wfw:commentRss><?php echo esc_url_raw( get_post_comments_feed_link( null, 'rss2' ) ); ?></wfw:commentRss>
                    <slash:comments><?php echo get_comments_number(); ?></slash:comments>
                <?php endif; ?>
                                    <?php rss_enclosure(); ?>
<?php 
if($no_head != '1')
{
    do_action('rss2_item');
}
?> 
            </item>
                    <?php endwhile; ?>
</channel>
</rss>
            <?php
    }
}

function echo_add_support_link($links)
{
    $settings_link = '<a href="//coderevolution.ro/knowledge-base/" target="_blank">' . esc_html__('Support', 'rss-feed-post-generator-echo') . '</a>';
    array_push($links, $settings_link);
    return $links;
}

function echo_add_settings_link($links)
{
    $settings_link = '<a href="admin.php?page=echo_admin_settings">' . esc_html__('Settings', 'rss-feed-post-generator-echo') . '</a>';
    array_push($links, $settings_link);
    return $links;
}

function echo_add_rating_link($links)
{
    $settings_link = '<a href="//codecanyon.net/downloads" target="_blank" title="Rate">
            <i class="wdi-rate-stars"><svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="#ffb900" stroke="#ffb900" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-star"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg><svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="#ffb900" stroke="#ffb900" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-star"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg><svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="#ffb900" stroke="#ffb900" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-star"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg><svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="#ffb900" stroke="#ffb900" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-star"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg><svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="#ffb900" stroke="#ffb900" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-star"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg></i></a>';
    array_push($links, $settings_link);
    return $links;
}

function echo_add_meta_box()
{
    $echo_Main_Settings = get_option('echo_Main_Settings', false);
    if (isset($echo_Main_Settings['echo_enabled']) && $echo_Main_Settings['echo_enabled'] === 'on') {
        if (isset($echo_Main_Settings['enable_metabox']) && $echo_Main_Settings['enable_metabox'] == 'on') {
            foreach ( get_post_types( '', 'names' ) as $post_type ) {
               add_meta_box('echo_meta_box_function_add', esc_html__('Echo Auto Generated Post Information', 'rss-feed-post-generator-echo'), 'echo_meta_box_function', $post_type, 'advanced', 'default', array('__back_compat_meta_box' => true));
            }
        }
    }
}
foreach( [ 'post', 'page', 'post_type' ] as $type )
{
    add_filter($type . '_link', 'echo_permalink_changer', 10, 2 );
}
add_filter('the_permalink','echo_permalink_changer', 10, 2 );
function echo_permalink_changer($link, $postid = ''){
    $le_post_id = '';
    if(is_numeric($postid))
    {
        $le_post_id = $postid;
    }
    elseif(is_object($postid) && isset($postid->ID))
    {
        $le_post_id = $postid->ID;
    }
    else
    {
        global $post;
        if(isset($post->ID))
        {
            $le_post_id = $post->ID;
        }
    }    
	if (!empty($le_post_id)) {
        $echo_Main_Settings = get_option('echo_Main_Settings', false);
        if (isset($echo_Main_Settings['echo_enabled']) && $echo_Main_Settings['echo_enabled'] == 'on') {
            if (isset($echo_Main_Settings['link_source']) && $echo_Main_Settings['link_source'] == 'on') {
                if (!isset($echo_Main_Settings['redir_302']) || $echo_Main_Settings['redir_302'] != 'on') {
                    $url = get_post_meta($le_post_id, 'echo_change_title_link', true);
                    if ( trim($url) == '1')
                    {
                        $new_url = get_post_meta($le_post_id, 'echo_post_url', true);
                        if(trim($new_url) != '') {
                            return $new_url;
                        }
                    }
                }
            }
        }
	}
	return $link;
}
if( !is_admin() )
{
    add_action( 'template_redirect', 'echo_do_redirect', 1);
}
function echo_do_redirect()
{
    global $post;
    if (is_singular() || is_page()) 
    {
        if(isset($post->ID))
        {
            $echo_Main_Settings = get_option('echo_Main_Settings', false);
            if (isset($echo_Main_Settings['echo_enabled']) && $echo_Main_Settings['echo_enabled'] == 'on') {
                if (isset($echo_Main_Settings['link_source']) && $echo_Main_Settings['link_source'] == 'on') {
                    if (isset($echo_Main_Settings['redir_302']) && $echo_Main_Settings['redir_302'] == 'on') {
                        $url = get_post_meta($post->ID, 'echo_change_title_link', true);
                        if ( trim($url) == '1')
                        {
                            $new_url = get_post_meta($post->ID, 'echo_post_url', true);
                            if(trim($new_url) != '') 
                            {
                                wp_redirect($new_url, 301);
                                exit();
                            }
                        }
                    }
                }
            }
        }
    }
}
function echo_remove_img_width_height( $html, $post_id, $post_image_id,$post_thumbnail) {
    if ($post_thumbnail=='gallery'){
        $html = preg_replace( '/(width|height)=\"\d*\"\s/', "", $html );
    }
    return $html;
}
function echo_spinnerchief_spin_text($title, $content)
{
    $titleSeparator = '[19459000]';
    $echo_Main_Settings = get_option('echo_Main_Settings', false);
    if (!isset($echo_Main_Settings['best_user']) || $echo_Main_Settings['best_user'] == '' || !isset($echo_Main_Settings['best_password']) || $echo_Main_Settings['best_password'] == '') {
        echo_log_to_file('Please insert a valid "SpinnerChief" user email and password.');
        return FALSE;
    }
    $pss = $echo_Main_Settings['best_password'];
    $html = stripslashes($title). ' ' . $titleSeparator . ' ' . stripslashes($content);
    if(str_word_count($html) > 5000)
    {
        $result = '';
        while($html != '')
        {
            $first30k = substr($html, 0, 20000);
            $first30k = rtrim($first30k, '(*');
            $first30k = ltrim($first30k, ')*');
            $html = substr($html, 20000);
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_HEADER,0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
            curl_setopt($ch, CURLOPT_TIMEOUT, 600);
            curl_setopt($ch, CURLOPT_MAXREDIRS, 5);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            $curlpost =  $first30k;
            $curlpost1 = preg_replace('/[\x00-\x1F\x7F-\xFF]/', '', $curlpost);
            if($curlpost1 !== null)
            {
                $curlpost = $curlpost1;
            }
            $post_me = 'dev_key=api2409357d02fa474d8&api_key=' . $pss . '&text=' . urlencode($curlpost);
            $url = "https://www.spinnerchief.com/api/paraphraser";
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post_me); 
            $result_temp = curl_exec($ch);
            if ($result_temp === FALSE) {
                $cer = 'Curl error: ' . curl_error($ch);
                echo_log_to_file('"SpinnerChief" failed to exec curl after auth. ' . $cer);
                curl_close ($ch);
                return FALSE;
            }
            else
            {
                $json_res = json_decode($result_temp);
                if($json_res !== false && isset($json_res->text))
                {
                    $result .= $json_res->text;
                }
                else
                {
                    $result .= $result_temp;
                }
            }
            curl_close ($ch);
        }
    }
    else
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HEADER,0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ch, CURLOPT_TIMEOUT, 600);
        curl_setopt($ch, CURLOPT_MAXREDIRS, 5);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        $curlpost = $html;
        //to fix issue with unicode characters where the API times out
        $curlpost1 = preg_replace('/[\x00-\x1F\x7F-\xFF]/', '', $curlpost);
        if($curlpost1 !== null)
        {
            $curlpost = $curlpost1;
        }
        $url = "https://www.spinnerchief.com/api/paraphraser";
        $post_me = 'dev_key=api2409357d02fa474d8&api_key=' . $pss . '&text=' . urlencode($curlpost);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_me); 
        $result = curl_exec($ch);
        if ($result === FALSE) {
            $cer = 'Curl error: ' . curl_error($ch);
            echo_log_to_file('"SpinnerChief" failed to exec curl after auth. ' . $cer);
            curl_close ($ch);
            return FALSE;
        }
        $json_res = json_decode($result);
        if($json_res !== false && isset($json_res->text))
        {
            $result = $json_res->text;
        }
        curl_close ($ch);
    }
    $result = preg_replace('#\](\d+\])#', '[$1', $result);
    $result = explode($titleSeparator, $result);
    if (count($result) < 2) {
        if (isset($echo_Main_Settings['enable_detailed_logging'])) {
            echo_log_to_file('"SpinnerChief" failed to spin article - titleseparator not found: ' . print_r($result, true));
        }
        return FALSE;
    }
    $spintax = new Echo_Spintax();
    $result[0] = $spintax->process(trim($result[0]));
    $result[1] = $spintax->process(trim($result[1]));
    return $result;
}
function echo_lazy_loading_auto_fix($cont)
{
    preg_match_all('{<img .*?>}s', $cont, $imgsMatchs);
    $imgs_count = count($imgsMatchs[0]);
    if($imgs_count < 1)
    {
        return $cont;
    }
    $our_lazy_tag = '';
    if(stristr($cont, ' data-src=')) 
    {
        $our_lazy_tag = 'data-src';
    } 
    elseif(stristr($cont, ' data-lazy-src=')) 
    {
        $our_lazy_tag = 'data-lazy-src';
    } 
    elseif(stristr($cont, ' data-ezsrc=')) 
    {
        $our_lazy_tag = 'data-ezsrc';
    } 
    elseif(stristr($cont, ' data-pagespeed-lazy-src=')) 
    {
        $our_lazy_tag = 'data-pagespeed-lazy-src';
    } 
    else 
    {
        $lazy_suspected = false;
        $images_plain = implode (' ', $imgsMatchs[0]);
        if (stristr($images_plain, 'lazy')) 
        {
            $lazy_suspected = true;
        } 
        else 
        {
            preg_match_all('{ src[\s]?=[\s]?["|\'](.*?)["|\']}', $images_plain, $srcs_matches);
            $found_srcs_count = count($srcs_matches[0]);
            $unique_srcs_count = count(array_unique($srcs_matches[1]));
            if ($found_srcs_count != 0) 
            {
                $diff_percentage = ($found_srcs_count-$unique_srcs_count)*100/$found_srcs_count;
            } 
            else 
            {
                $diff_percentage = 0;
            }
            if ($diff_percentage > 39) 
            {
                $lazy_suspected = true;
            }
        }
        if ($lazy_suspected) 
        {
            $remove_src_attr = preg_replace ('{ src[\s]?=[\s]?["|\'].*?["|\']}', ' ', $images_plain);
            $remove_these = array (' alt', ' srcset', ' data-srcset', ' class', ' id', ' title');
            $remove_src_attr = str_replace($remove_these, ' ', $remove_src_attr);
            $remove_src_attr = preg_replace('{ [\w|-]*?[\s]?=[\s]?["|\'].{1,9}?["|\']}s', ' ', $remove_src_attr);
            preg_match_all('{( [\w|-]*?)[\s]?=[\s]?["|\'][^",]*?/[^",]*?["|\']}', $remove_src_attr, $possible_src_matches);
            $src_match_unique = array_unique($possible_src_matches[1]);
            if (isset($src_match_unique[0])) 
            {
                $our_lazy_tag = $src_match_unique[0];
            }
        }
    }
    $our_lazy_tag = trim($our_lazy_tag);
    if ($our_lazy_tag == 'src' || $our_lazy_tag == '')
    {
        return $cont;
    }
    if (!stristr($cont, $our_lazy_tag))
    {
        return $cont;
    }
    foreach ($imgsMatchs[0] as $imgMatch) 
    {
        if (stristr($imgMatch, $our_lazy_tag)) 
        {
            $newImg = $imgMatch;
            $newImg = str_replace ( ' src=', ' previous-src=', $newImg );
            $newImg = preg_replace ( '{ previous-src=[\'|"].*?[\'|"] }', ' ', $newImg );
            $newImg = str_replace ( ' ' . $our_lazy_tag, ' src', $newImg );
            $cont = str_replace ( $imgMatch, $newImg, $cont );
        }
    }
    return $cont;
}
function echo_spinrewriter_spin_text($title, $content, $confidence = 'high')
{
    $echo_Main_Settings = get_option('echo_Main_Settings', false);
    if (!isset($echo_Main_Settings['best_user']) || $echo_Main_Settings['best_user'] == '' || !isset($echo_Main_Settings['best_password']) || $echo_Main_Settings['best_password'] == '') {
        echo_log_to_file('Please insert a valid "SpinRewriter" user name and password.');
        return FALSE;
    }
    $titleSeparator = '(19459000)';
    $quality = '50';
    $html = $title . ' ' . $titleSeparator . ' ' . $content;
    $html = preg_replace('/\s+/', ' ', $html);
    $data = array();
    $data['email_address'] = $echo_Main_Settings['best_user'];
    $data['api_key'] = $echo_Main_Settings['best_password'];
    $data['action'] = "unique_variation";
    $data['auto_protected_terms'] = "true";					
    $data['confidence_level'] = $confidence;							
    $data['auto_sentences'] = "true";							
    $data['auto_paragraphs'] = "false";							
    $data['auto_new_paragraphs'] = "false";						
    $data['auto_sentence_trees'] = "false";						
    $data['use_only_synonyms'] = "true";						
    $data['reorder_paragraphs'] = "false";						
    $data['nested_spintax'] = "false";						
    if (isset($echo_Main_Settings['protected_terms']) && $echo_Main_Settings['protected_terms'] != '') 
    {
        $protected_terms = $echo_Main_Settings['protected_terms'];
        $data['protected_terms'] = str_replace(',', '\n', $protected_terms);
    }
    if(str_word_count($html) >= 2500)
    {
        $result = '';
        while($html != '' && $html != ' ')
        {
            $words = explode(" ", $html);
            $first30k = join(" ", array_slice($words, 0, 2500));
            $html = join(" ", array_slice($words, 2500));
            
            $data['text'] = $first30k;	
            $api_response = echo_spinrewriter_api_post($data);
            if ($api_response === FALSE) {
                echo_log_to_file('"SpinRewriter" failed to exec curl after auth.');
                return FALSE;
            }
            $api_response = json_decode($api_response);
            if(!isset($api_response->response) || !isset($api_response->status) || $api_response->status != 'OK')
            {
                if(isset($api_response->status) && $api_response->status == 'ERROR')
                {
                    if(isset($api_response->response) && $api_response->response == 'You can only submit entirely new text for analysis once every 7 seconds.')
                    {
                        $api_response = echo_spinrewriter_api_post($data);
                        if ($api_response === FALSE) {
                            echo_log_to_file('"SpinRewriter" failed to exec curl after auth (after resubmit).');
                            return FALSE;
                        }
                        $api_response = json_decode($api_response);
                        if(!isset($api_response->response) || !isset($api_response->status) || $api_response->status != 'OK')
                        {
                            echo_log_to_file('"SpinRewriter" failed to wait and resubmit spinning: ' . print_r($api_response, true) . ' params: ' . print_r($data, true));
                            return FALSE;
                        }
                    }
                    else
                    {
                        echo_log_to_file('"SpinRewriter" error response: ' . print_r($api_response, true) . ' params: ' . print_r($data, true));
                        return FALSE;
                    }
                }
                else
                {
                    echo_log_to_file('"SpinRewriter" error response: ' . print_r($api_response, true) . ' params: ' . print_r($data, true));
                    return FALSE;
                }
            }
            $spinned = $api_response->response;
            $result .= ' ' . $spinned;
            if($html != '' && $html != ' ')
            {
                sleep(7);
            }
        }
    }
    else
    {
        $data['text'] = $html;	
        $api_response = echo_spinrewriter_api_post($data);
        if ($api_response === FALSE) {
            echo_log_to_file('"SpinRewriter" failed to exec curl after auth.');
            return FALSE;
        }
        $api_response = json_decode($api_response);
        if(!isset($api_response->response) || !isset($api_response->status) || $api_response->status != 'OK')
        {
            if(isset($api_response->status) && $api_response->status == 'ERROR')
            {
                if(isset($api_response->response) && $api_response->response == 'You can only submit entirely new text for analysis once every 7 seconds.')
                {
                    $api_response = echo_spinrewriter_api_post($data);
                    if ($api_response === FALSE) {
                        echo_log_to_file('"SpinRewriter" failed to exec curl after auth (after resubmit).');
                        return FALSE;
                    }
                    $api_response = json_decode($api_response);
                    if(!isset($api_response->response) || !isset($api_response->status) || $api_response->status != 'OK')
                    {
                        echo_log_to_file('"SpinRewriter" failed to wait and resubmit spinning: ' . print_r($api_response, true) . ' params: ' . print_r($data, true));
                        return FALSE;
                    }
                }
                else
                {
                    echo_log_to_file('"SpinRewriter" error response: ' . print_r($api_response, true) . ' params: ' . print_r($data, true));
                    return FALSE;
                }
            }
            else
            {
                echo_log_to_file('"SpinRewriter" error response: ' . print_r($api_response, true) . ' params: ' . print_r($data, true));
                return FALSE;
            }
        }
        $result = $api_response->response;
    }
    $result = explode($titleSeparator, $result);
    if (count($result) < 2) {
        if (isset($echo_Main_Settings['enable_detailed_logging'])) {
            echo_log_to_file('"SpinRewriter" failed to spin article - titleseparator not found: ' . $api_response->response);
        }
        return FALSE;
    }
    return $result;
}
function echo_spinrewriter_api_post($data){
	$data_raw = "";
    
    $GLOBALS['wp_object_cache']->delete('crspinrewriter_spin_time', 'options');
    $spin_time = get_option('crspinrewriter_spin_time', false);
    if($spin_time !== false && is_numeric($spin_time))
    {
        $c_time = time();
        $spassed = $c_time - $spin_time;
        if($spassed < 10 && $spassed >= 0)
        {
            sleep(10 - $spassed);
        }
    }
    update_option('crspinrewriter_spin_time', time());
    
	foreach ($data as $key => $value){
		$data_raw = $data_raw . $key . "=" . urlencode($value) . "&";
	}
		
	$ch = curl_init();
    if($ch === false)
    {
        return false;
    }
	curl_setopt($ch, CURLOPT_URL, "http://www.spinrewriter.com/action/api");
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data_raw);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
    curl_setopt($ch, CURLOPT_TIMEOUT, 60);
	$response = trim(curl_exec($ch));
	curl_close($ch);
	return $response;
}
function echo_builtin_spin_text($title, $content)
{
    $echo_Main_Settings = get_option('echo_Main_Settings', false);
    $titleSeparator         = '[19459000]';
    $text                   = $title . ' ' . $titleSeparator . ' ' . $content;
    $no_spin_words = array();
    if (isset($echo_Main_Settings['no_spin']) && $echo_Main_Settings['no_spin'] != '') {
        $no_spin_words = explode(',', $echo_Main_Settings['no_spin']);
        $no_spin_words = array_map('trim', $no_spin_words);
    }
    try {
        $file=file(dirname(__FILE__)  .'/res/synonyms.dat');
		foreach($file as $line){
			$synonyms=explode('|', $line);
			foreach($synonyms as $word){
				if(trim($word) != '' && !in_array($word, $no_spin_words)){
                    $word=str_replace('/','\/',$word);
					if(preg_match('/\b'. $word .'\b/u', $text)) {
						$rand = array_rand($synonyms, 1);
						$text = preg_replace('/\b'.$word.'\b/u', trim($synonyms[$rand]), $text);
					}
                    $uword=ucfirst($word);
					if(preg_match('/\b'. $uword .'\b/u', $text)) {
						$rand = array_rand($synonyms, 1);
						$text = preg_replace('/\b'.$uword.'\b/u', ucfirst(trim($synonyms[$rand])), $text);
					}
				}
			}
		}
        $translated = $text;
    }
    catch (Exception $e) {
        if (isset($echo_Main_Settings['enable_detailed_logging'])) {
            echo_log_to_file('Exception thrown in spinText ' . $e->getMessage());
        }
        return false;
    }
    if (stristr($translated, $titleSeparator)) {
        $contents = explode($titleSeparator, $translated);
        $title    = $contents[0];
        $content  = $contents[1];
    } else {
        if (isset($echo_Main_Settings['enable_detailed_logging'])) {
            echo_log_to_file('Failed to parse spinned content, separator not found');
        }
        return false;
    }
    return array(
        $title,
        $content
    );
}

add_filter('cron_schedules', 'echo_add_cron_schedule');
function echo_add_cron_schedule($schedules)
{
    $schedules['echo_cron'] = array(
        'interval' => 3600,
        'display' => esc_html__('Echo Cron', 'rss-feed-post-generator-echo')
    );
    $schedules['minutely'] = array(
        'interval' => 60,
        'display' => esc_html__('Once A Minute', 'rss-feed-post-generator-echo')
    );
    $schedules['weekly']    = array(
        'interval' => 604800,
        'display' => esc_html__('Once Weekly', 'rss-feed-post-generator-echo')
    );
    $schedules['monthly']   = array(
        'interval' => 2592000,
        'display' => esc_html__('Once Monthly', 'rss-feed-post-generator-echo')
    );
    return $schedules;
}
function echo_auto_clear_log()
{
    global $wp_filesystem;
    if ( ! is_a( $wp_filesystem, 'WP_Filesystem_Base') ){
        include_once(ABSPATH . 'wp-admin/includes/file.php');$creds = request_filesystem_credentials( site_url() );
       wp_filesystem($creds);
    }
    if ($wp_filesystem->exists(WP_CONTENT_DIR . '/echo_info.log')) {
        $wp_filesystem->delete(WP_CONTENT_DIR . '/echo_info.log');
    }
}

add_shortcode( 'echo-display-posts', 'echo_display_posts_shortcode' );
function echo_display_posts_shortcode( $atts ) {
    if ( is_admin() ) 
    {
        return;
    }
	$original_atts = $atts;
	$atts = shortcode_atts( array(
		'author'               => '',
		'category'             => '',
		'category_display'     => '',
		'category_label'       => 'Posted in: ',
		'content_class'        => 'content',
		'date_format'          => '(n/j/Y)',
		'date'                 => '',
		'date_column'          => 'post_date',
		'date_compare'         => '=',
		'date_query_before'    => '',
		'date_query_after'     => '',
		'date_query_column'    => '',
		'date_query_compare'   => '',
		'display_posts_off'    => false,
		'excerpt_length'       => false,
		'excerpt_more'         => false,
		'excerpt_more_link'    => false,
		'exclude_current'      => false,
		'id'                   => false,
		'ignore_sticky_posts'  => false,
		'image_size'           => false,
        'image_newline'        => false,
		'include_author'       => false,
		'include_content'      => false,
		'include_date'         => false,
		'include_excerpt'      => false,
		'include_link'         => true,
		'include_title'        => true,
		'simple_excerpt'       => false,
		'meta_key'             => '',
		'meta_value'           => '',
		'no_posts_message'     => '',
		'offset'               => 0,
		'order'                => 'DESC',
		'orderby'              => 'date',
		'post_parent'          => false,
		'post_status'          => 'publish',
		'post_type'            => 'post',
		'posts_per_page'       => '10',
		'tag'                  => '',
		'tax_operator'         => 'IN',
		'tax_include_children' => true,
		'tax_term'             => false,
		'taxonomy'             => false,
		'time'                 => '',
		'title'                => '',
        'title_color'          => '#000000',
        'excerpt_color'        => '#000000',
        'link_to_source'       => '',
        'title_font_size'      => '100%',
        'excerpt_font_size'    => '100%',
        'read_more_text'       => '',
		'wrapper'              => 'ul',
		'wrapper_class'        => 'display-posts-listing',
		'wrapper_id'           => false,
        'ruleid'               => ''
	), $atts, 'display-posts' );
	if( $atts['display_posts_off'] )
		return;
	$author               = sanitize_text_field( $atts['author'] );
    $ruleid               = sanitize_text_field( $atts['ruleid'] );
	$category             = sanitize_text_field( $atts['category'] );
	$category_display     = 'true' == $atts['category_display'] ? 'category' : sanitize_text_field( $atts['category_display'] );
	$category_label       = sanitize_text_field( $atts['category_label'] );
	$content_class        = array_map( 'sanitize_html_class', ( explode( ' ', $atts['content_class'] ) ) );
	$date_format          = sanitize_text_field( $atts['date_format'] );
	$date                 = sanitize_text_field( $atts['date'] );
	$date_column          = sanitize_text_field( $atts['date_column'] );
	$date_compare         = sanitize_text_field( $atts['date_compare'] );
	$date_query_before    = sanitize_text_field( $atts['date_query_before'] );
	$date_query_after     = sanitize_text_field( $atts['date_query_after'] );
	$date_query_column    = sanitize_text_field( $atts['date_query_column'] );
	$date_query_compare   = sanitize_text_field( $atts['date_query_compare'] );
	$excerpt_length       = intval( $atts['excerpt_length'] );
	$excerpt_more         = sanitize_text_field( $atts['excerpt_more'] );
	$excerpt_more_link    = filter_var( $atts['excerpt_more_link'], FILTER_VALIDATE_BOOLEAN );
	$exclude_current      = filter_var( $atts['exclude_current'], FILTER_VALIDATE_BOOLEAN );
	$id                   = $atts['id'];
	$ignore_sticky_posts  = filter_var( $atts['ignore_sticky_posts'], FILTER_VALIDATE_BOOLEAN );
	$image_size           = sanitize_key( $atts['image_size'] );
    $image_newline        = sanitize_key( $atts['image_newline'] );
	$include_title        = filter_var( $atts['include_title'], FILTER_VALIDATE_BOOLEAN );
    $simple_excerpt       = filter_var( $atts['simple_excerpt'], FILTER_VALIDATE_BOOLEAN );
	$include_author       = filter_var( $atts['include_author'], FILTER_VALIDATE_BOOLEAN );
	$include_content      = filter_var( $atts['include_content'], FILTER_VALIDATE_BOOLEAN );
	$include_date         = filter_var( $atts['include_date'], FILTER_VALIDATE_BOOLEAN );
	$include_excerpt      = filter_var( $atts['include_excerpt'], FILTER_VALIDATE_BOOLEAN );
	$include_link         = filter_var( $atts['include_link'], FILTER_VALIDATE_BOOLEAN );
	$meta_key             = sanitize_text_field( $atts['meta_key'] );
	$meta_value           = sanitize_text_field( $atts['meta_value'] );
	$no_posts_message     = sanitize_text_field( $atts['no_posts_message'] );
	$offset               = intval( $atts['offset'] );
	$order                = sanitize_key( $atts['order'] );
	$orderby              = sanitize_key( $atts['orderby'] );
	$post_parent          = $atts['post_parent'];
	$post_status          = $atts['post_status'];
	$post_type            = sanitize_text_field( $atts['post_type'] );
	$posts_per_page       = intval( $atts['posts_per_page'] );
	$tag                  = sanitize_text_field( $atts['tag'] );
	$tax_operator         = $atts['tax_operator'];
	$tax_include_children = filter_var( $atts['tax_include_children'], FILTER_VALIDATE_BOOLEAN );
	$tax_term             = sanitize_text_field( $atts['tax_term'] );
	$taxonomy             = sanitize_key( $atts['taxonomy'] );
	$time                 = sanitize_text_field( $atts['time'] );
	$shortcode_title      = sanitize_text_field( $atts['title'] );
    $title_color          = sanitize_text_field( $atts['title_color'] );
    $excerpt_color        = sanitize_text_field( $atts['excerpt_color'] );
    $link_to_source       = sanitize_text_field( $atts['link_to_source'] );
    $excerpt_font_size    = sanitize_text_field( $atts['excerpt_font_size'] );
    $title_font_size      = sanitize_text_field( $atts['title_font_size'] );
    $read_more_text       = sanitize_text_field( $atts['read_more_text'] );
	$wrapper              = sanitize_text_field( $atts['wrapper'] );
	$wrapper_class        = array_map( 'sanitize_html_class', ( explode( ' ', $atts['wrapper_class'] ) ) );
	if( !empty( $wrapper_class ) )
		$wrapper_class = ' class="' . implode( ' ', $wrapper_class ) . '"';
	$wrapper_id = sanitize_html_class( $atts['wrapper_id'] );
	if( !empty( $wrapper_id ) )
		$wrapper_id = ' id="' . esc_html($wrapper_id) . '"';
	$args = array(
		'category_name'       => $category,
		'order'               => $order,
		'orderby'             => $orderby,
		'post_type'           => explode( ',', $post_type ),
		'posts_per_page'      => $posts_per_page,
		'tag'                 => $tag,
	);
	if ( ! empty( $date ) || ! empty( $time ) || ! empty( $date_query_after ) || ! empty( $date_query_before ) ) {
		$initial_date_query = $date_query_top_lvl = array();
		$valid_date_columns = array(
			'post_date', 'post_date_gmt', 'post_modified', 'post_modified_gmt',
			'comment_date', 'comment_date_gmt'
		);
		$valid_compare_ops = array( '=', '!=', '>', '>=', '<', '<=', 'IN', 'NOT IN', 'BETWEEN', 'NOT BETWEEN' );
		$dates = echo_sanitize_date_time( $date );
		if ( ! empty( $dates ) ) {
			if ( is_string( $dates ) ) {
				$timestamp = strtotime( $dates );
				$dates = array(
					'year'   => date( 'Y', $timestamp ),
					'month'  => date( 'm', $timestamp ),
					'day'    => date( 'd', $timestamp ),
				);
			}
			foreach ( $dates as $arg => $segment ) {
				$initial_date_query[ $arg ] = $segment;
			}
		}
		$times = echo_sanitize_date_time( $time, 'time' );
		if ( ! empty( $times ) ) {
			foreach ( $times as $arg => $segment ) {
				$initial_date_query[ $arg ] = $segment;
			}
		}
		$before = echo_sanitize_date_time( $date_query_before, 'date', true );
		if ( ! empty( $before ) ) {
			$initial_date_query['before'] = $before;
		}
		$after = echo_sanitize_date_time( $date_query_after, 'date', true );
		if ( ! empty( $after ) ) {
			$initial_date_query['after'] = $after;
		}
		if ( ! empty( $date_query_column ) && in_array( $date_query_column, $valid_date_columns ) ) {
			$initial_date_query['column'] = $date_query_column;
		}
		if ( ! empty( $date_query_compare ) && in_array( $date_query_compare, $valid_compare_ops ) ) {
			$initial_date_query['compare'] = $date_query_compare;
		}
		if ( ! empty( $date_column ) && in_array( $date_column, $valid_date_columns ) ) {
			$date_query_top_lvl['column'] = $date_column;
		}
		if ( ! empty( $date_compare ) && in_array( $date_compare, $valid_compare_ops ) ) {
			$date_query_top_lvl['compare'] = $date_compare;
		}
		if ( ! empty( $initial_date_query ) ) {
			$date_query_top_lvl[] = $initial_date_query;
		}
		$args['date_query'] = $date_query_top_lvl;
	}
    $args['meta_key'] = 'echo_parent_rule';
    if($ruleid != '')
    {
        $args['meta_value'] = $ruleid;
    }
	if( $ignore_sticky_posts )
		$args['ignore_sticky_posts'] = true;
	 
	if( $id ) {
		$posts_in = array_map( 'intval', explode( ',', $id ) );
		$args['post__in'] = $posts_in;
	}
	if( is_singular() && $exclude_current )
		$args['post__not_in'] = array( get_the_ID() );
	if( !empty( $author ) ) {
		if( 'current' == $author && is_user_logged_in() )
			$args['author_name'] = wp_get_current_user()->user_login;
		elseif( 'current' == $author )
            $unrelevar = false;
			 
		else
			$args['author_name'] = $author;
	}
	if( !empty( $offset ) )
		$args['offset'] = $offset;
	$post_status = explode( ', ', $post_status );
	$validated = array();
	$available = array( 'publish', 'pending', 'draft', 'auto-draft', 'future', 'private', 'inherit', 'trash', 'any' );
	foreach ( $post_status as $unvalidated )
		if ( in_array( $unvalidated, $available ) )
			$validated[] = $unvalidated;
	if( !empty( $validated ) )
		$args['post_status'] = $validated;
	if ( !empty( $taxonomy ) && !empty( $tax_term ) ) {
		if( 'current' == $tax_term ) {
			global $post;
			$terms = wp_get_post_terms(get_the_ID(), $taxonomy);
			$tax_term = array();
			foreach ($terms as $term) {
				$tax_term[] = $term->slug;
			}
		}else{
			$tax_term = explode( ', ', $tax_term );
		}
		if( !in_array( $tax_operator, array( 'IN', 'NOT IN', 'AND' ) ) )
			$tax_operator = 'IN';
		$tax_args = array(
			'tax_query' => array(
				array(
					'taxonomy'         => $taxonomy,
					'field'            => 'slug',
					'terms'            => $tax_term,
					'operator'         => $tax_operator,
					'include_children' => $tax_include_children,
				)
			)
		);
		$count = 2;
		$more_tax_queries = false;
		while(
			isset( $original_atts['taxonomy_' . $count] ) && !empty( $original_atts['taxonomy_' . $count] ) &&
			isset( $original_atts['tax_' . esc_html($count) . '_term'] ) && !empty( $original_atts['tax_' . esc_html($count) . '_term'] )
		):
			$more_tax_queries = true;
			$taxonomy = sanitize_key( $original_atts['taxonomy_' . $count] );
	 		$terms = explode( ', ', sanitize_text_field( $original_atts['tax_' . esc_html($count) . '_term'] ) );
	 		$tax_operator = isset( $original_atts['tax_' . esc_html($count) . '_operator'] ) ? $original_atts['tax_' . esc_html($count) . '_operator'] : 'IN';
	 		$tax_operator = in_array( $tax_operator, array( 'IN', 'NOT IN', 'AND' ) ) ? $tax_operator : 'IN';
	 		$tax_include_children = isset( $original_atts['tax_' . esc_html($count) . '_include_children'] ) ? filter_var( $atts['tax_' . esc_html($count) . '_include_children'], FILTER_VALIDATE_BOOLEAN ) : true;
	 		$tax_args['tax_query'][] = array(
	 			'taxonomy'         => $taxonomy,
	 			'field'            => 'slug',
	 			'terms'            => $terms,
	 			'operator'         => $tax_operator,
	 			'include_children' => $tax_include_children,
	 		);
			$count++;
		endwhile;
		if( $more_tax_queries ):
			$tax_relation = 'AND';
			if( isset( $original_atts['tax_relation'] ) && in_array( $original_atts['tax_relation'], array( 'AND', 'OR' ) ) )
				$tax_relation = $original_atts['tax_relation'];
			$args['tax_query']['relation'] = $tax_relation;
		endif;
		$args = array_merge_recursive( $args, $tax_args );
	}
	if( $post_parent !== false ) {
		if( 'current' == $post_parent ) {
			global $post;
			$post_parent = get_the_ID();
		}
		$args['post_parent'] = intval( $post_parent );
	}
	$wrapper_options = array( 'ul', 'ol', 'div' );
	if( ! in_array( $wrapper, $wrapper_options ) )
		$wrapper = 'ul';
	$inner_wrapper = 'div' == $wrapper ? 'div' : 'li';
	$listing = new WP_Query( apply_filters( 'display_posts_shortcode_args', $args, $original_atts ) );
	if ( ! $listing->have_posts() ) {
		return apply_filters( 'display_posts_shortcode_no_results', wpautop( $no_posts_message ) );
	}
	$inner = '';
    wp_suspend_cache_addition(true);
	while ( $listing->have_posts() ): $listing->the_post(); global $post;
		$image = $date = $author = $excerpt = $content = '';
		if ( $include_title && $include_link ) {
            if($link_to_source == 'yes')
            {
                $source_url = get_post_meta($post->ID, 'echo_post_url', true);
                if($source_url != '')
                {
                    $title = '<a class="echo_display_title" href="' . esc_url_raw($source_url) . '"><span class="cr_display_span" >' . get_the_title() . '</span></a>';
                }
                else
                {
                    $title = '<a class="echo_display_title" href="' . apply_filters( 'the_permalink', get_permalink() ) . '"><span class="cr_display_span" >' . get_the_title() . '</span></a>';
                }
            }
            else
            {
                $title = '<a class="echo_display_title" href="' . apply_filters( 'the_permalink', get_permalink() ) . '"><span class="cr_display_span" >' . get_the_title() . '</span></a>';
            }
		} elseif( $include_title ) {
			$title = '<span class="echo_display_title" class="cr_display_span">' . get_the_title() . '</span>';
		} else {
			$title = '';
		}
		if ( $image_size && has_post_thumbnail() && $include_link ) {
            if($link_to_source == 'yes')
            {
                $source_url = get_post_meta($post->ID, 'echo_post_url', true);
                if($source_url != '')
                {
                    $image = '<a class="echo_display_image" href="' . esc_url_raw($source_url) . '">' . get_the_post_thumbnail( get_the_ID(), $image_size ) . '</a> <br/>';
                }
                else
                {
                    $image = '<a class="echo_display_image" href="' . get_permalink() . '">' . get_the_post_thumbnail( get_the_ID(), $image_size ) . '</a> <br/>';
                }
            }
            else
            {
                $image = '<a class="echo_display_image" href="' . get_permalink() . '">' . get_the_post_thumbnail( get_the_ID(), $image_size ) . '</a> <br/>';
            }
		} elseif( $image_size && has_post_thumbnail() ) {
			$image = '<span class="echo_display_image">' . get_the_post_thumbnail( get_the_ID(), $image_size ) . '</span> <br/>';
		}
		if ( $include_date )
			$date = ' <span class="date">' . get_the_date( $date_format ) . '</span>';
		if( $include_author )
			$author = apply_filters( 'display_posts_shortcode_author', ' <span class="echo_display_author">by ' . get_the_author() . '</span>', $original_atts );
		if ( $include_excerpt ) {
			if( $excerpt_length || $excerpt_more || $excerpt_more_link ) {
				$length = $excerpt_length ? $excerpt_length : apply_filters( 'excerpt_length', 55 );
				$more   = $excerpt_more ? $excerpt_more : apply_filters( 'excerpt_more', '' );
				$more   = $excerpt_more_link ? ' <a href="' . get_permalink() . '">' . esc_html($more) . '</a>' : ' ' . esc_html($more);
				if( has_excerpt() && apply_filters( 'display_posts_shortcode_full_manual_excerpt', false ) ) {
					$excerpt = $post->post_excerpt . $more;
				} elseif( has_excerpt() ) {
					$excerpt = wp_trim_words( strip_shortcodes( $post->post_excerpt ), $length, $more );
				} else {
					$excerpt = wp_trim_words( strip_shortcodes( $post->post_content ), $length, $more );
				}
			} else {
				$excerpt = get_the_excerpt();
			}
            if ( $simple_excerpt )
            {
                $excerpt = strip_tags($excerpt, echo_wpse_allowedtags2());
            }
            $excerpt = ' <br/><br/> <span class="echo_display_excerpt" class="cr_display_excerpt_adv">' . $excerpt . '</span>';
            if($read_more_text != '')
            {
                if($link_to_source == 'yes')
                {
                    $source_url = get_post_meta($post->ID, 'echo_post_url', true);
                    if($source_url != '')
                    {
                        $excerpt .= '<br/><a href="' . esc_url_raw($source_url) . '"><span class="echo_display_excerpt" class="cr_display_excerpt_adv">' . esc_html($read_more_text) . '</span></a>';
                    }
                    else
                    {
                        $excerpt .= '<br/><a href="' . get_permalink() . '"><span class="echo_display_excerpt" class="cr_display_excerpt_adv">' . esc_html($read_more_text) . '</span></a>';
                    }
                }
                else
                {
                    $excerpt .= '<br/><a href="' . get_permalink() . '"><span class="echo_display_excerpt" class="cr_display_excerpt_adv">' . esc_html($read_more_text) . '</span></a>';
                }
            }
		}
		if( $include_content ) {
			add_filter( 'shortcode_atts_display-posts', 'echo_display_posts_off', 10, 3 );
			$content = '<div class="' . implode( ' ', $content_class ) . '">' . apply_filters( 'the_content', get_the_content() ) . '</div>';
			remove_filter( 'shortcode_atts_display-posts', 'echo_display_posts_off', 10, 3 );
		}
		$category_display_text = '';
		if( $category_display && is_object_in_taxonomy( get_post_type(), $category_display ) ) {
			$terms = get_the_terms( get_the_ID(), $category_display );
			$term_output = array();
			foreach( $terms as $term )
				$term_output[] = '<a href="' . get_term_link( $term, $category_display ) . '">' . esc_html($term->name) . '</a>';
			$category_display_text = ' <span class="category-display"><span class="category-display-label">' . esc_html($category_label) . '</span> ' . trim(implode( ', ', $term_output ), ', ') . '</span>';
			$category_display_text = apply_filters( 'display_posts_shortcode_category_display', $category_display_text );
		}
		$class = array( 'listing-item' );
		$class = array_map( 'sanitize_html_class', apply_filters( 'display_posts_shortcode_post_class', $class, $post, $listing, $original_atts ) );
		$output = '<br/><' . esc_html($inner_wrapper) . ' class="' . implode( ' ', $class ) . '">' . $image;
        if($image_newline)
        {
            $output .= '<br class="crf_clear">';
        }
        $output .= $title . $date . $author . $category_display_text . $excerpt . $content . '</' . esc_html($inner_wrapper) . '><br/><br/><hr class="cr_hr_dot"/>';		$inner .= apply_filters( 'display_posts_shortcode_output', $output, $original_atts, $image, $title, $date, $excerpt, $inner_wrapper, $content, $class );
	endwhile; wp_reset_postdata();
    wp_suspend_cache_addition(false);
	$open = apply_filters( 'display_posts_shortcode_wrapper_open', '<' . $wrapper . $wrapper_class . $wrapper_id . '>', $original_atts );
	$close = apply_filters( 'display_posts_shortcode_wrapper_close', '</' . esc_html($wrapper) . '>', $original_atts );
	$return = $open;
	if( $shortcode_title ) {
		$title_tag = apply_filters( 'display_posts_shortcode_title_tag', 'h2', $original_atts );
		$return .= '<' . esc_html($title_tag) . ' class="display-posts-title">' . esc_html($shortcode_title) . '</' . esc_html($title_tag) . '>' . "\n";
	}
	$return .= $inner . $close;
    $reg_css_code = '.cr_hr_dot{border-top: dotted 1px;}.cr_display_span{font-size:' . esc_html($title_font_size) . ';color:' . esc_html($title_color) . ' !important;}.cr_display_excerpt_adv{font-size:' . esc_html($excerpt_font_size) . ';color:' . esc_html($excerpt_color) . ' !important;}';
    wp_register_style( 'echo-display-style', false );
    wp_enqueue_style( 'echo-display-style' );
    wp_add_inline_style( 'echo-display-style', $reg_css_code );
	return $return;
}
function echo_sanitize_date_time( $date_time, $type = 'date', $accepts_string = false ) {
	if ( empty( $date_time ) || ! in_array( $type, array( 'date', 'time' ) ) ) {
		return array();
	}
	$segments = array();
	if (
		true === $accepts_string
		&& ( false !== strpos( $date_time, ' ' ) || false === strpos( $date_time, '-' ) )
	) {
		if ( false !== $timestamp = strtotime( $date_time ) ) {
			return $date_time;
		}
	}
	$parts = array_map( 'absint', explode( 'date' == $type ? '-' : ':', $date_time ) );
	if ( 'date' == $type ) {
		$year = $month = $day = 1;
		if ( count( $parts ) >= 3 ) {
			list( $year, $month, $day ) = $parts;
			$year  = ( $year  >= 1 && $year  <= 9999 ) ? $year  : 1;
			$month = ( $month >= 1 && $month <= 12   ) ? $month : 1;
			$day   = ( $day   >= 1 && $day   <= 31   ) ? $day   : 1;
		}
		$segments = array(
			'year'  => $year,
			'month' => $month,
			'day'   => $day
		);
	} elseif ( 'time' == $type ) {
		$hour = $minute = $second = 0;
		switch( count( $parts ) ) {
			case 3 :
				list( $hour, $minute, $second ) = $parts;
				$hour   = ( $hour   >= 0 && $hour   <= 23 ) ? $hour   : 0;
				$minute = ( $minute >= 0 && $minute <= 60 ) ? $minute : 0;
				$second = ( $second >= 0 && $second <= 60 ) ? $second : 0;
				break;
			case 2 :
				list( $hour, $minute ) = $parts;
				$hour   = ( $hour   >= 0 && $hour   <= 23 ) ? $hour   : 0;
				$minute = ( $minute >= 0 && $minute <= 60 ) ? $minute : 0;
				break;
			default : break;
		}
		$segments = array(
			'hour'   => $hour,
			'minute' => $minute,
			'second' => $second
		);
	}

	return apply_filters( 'display_posts_shortcode_sanitized_segments', $segments, $date_time, $type );
}

function echo_display_posts_off( $out, $pairs, $atts ) {
	$out['display_posts_off'] = apply_filters( 'display_posts_shortcode_inception_override', true );
	return $out;
}
add_shortcode( 'echo-list-posts', 'echo_list_posts' );
function echo_list_posts( $atts ) {
    if ( is_admin() ) 
    {
        return;
    }
    ob_start();
    extract( shortcode_atts( array (
        'type' => 'any',
        'order' => 'ASC',
        'orderby' => 'title',
        'posts' => 50,
        'posts_per_page' => 50,
        'category' => '',
        'ruleid' => '',
        'taxonomy_query' => '',
        'link_source' => ''
    ), $atts ) );
    
    
    
    $options = array(
        'post_type' => $type,
        'order' => $order,
        'orderby' => $orderby,
        'posts_per_page' => $posts,
        'category_name' => $category,
        'meta_key' => 'echo_parent_rule',
        'meta_value' => $ruleid
    );
    if($taxonomy_query != '')
    {
        $expload = explode(':', $taxonomy_query);
        if(isset($expload[1]))
        {
            $tx_ar = array();
            $tx_temp = array();
            $tx_temp['taxonomy'] = $expload[0];
            $tx_temp['terms'] = $expload[1];
            $tx_temp['field'] = 'slug';
            $tx_ar[] = $tx_temp;
            $options['tax_query'] = $tx_ar;
        }
    }
    $query = new WP_Query( $options );
    if ( $query->have_posts() ) { ?>
        <ul class="clothes-listing">
            <?php while ( $query->have_posts() ) : $query->the_post(); ?>
            <li id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
            <?php
            if($link_source == '1')
            {
                $direct_url = get_post_meta(get_the_ID(), 'echo_post_url', true);
                if($direct_url == '')
                {
                    $direct_url = get_permalink();
                }
                ?>
                <a href="<?php echo esc_url_raw($direct_url); ?>"><?php echo esc_html(get_the_title());?></a>
                <?php
            }
            else
            {
                ?>
                <a href="<?php echo esc_url_raw(get_permalink()); ?>"><?php echo esc_html(get_the_title());?></a>
                <?php
            }
            ?>
            </li>
            <?php endwhile;
            wp_reset_postdata(); ?>
        </ul>
    <?php $myvariable = ob_get_clean();
    return $myvariable;
    }
    return '';
}

register_deactivation_hook(__FILE__, 'echo_my_deactivation');
function echo_my_deactivation()
{
    wp_clear_scheduled_hook('echoaction');
    wp_clear_scheduled_hook('echoactionclear');
    $running = array();
    update_option('echo_running_list', $running, false);
}
add_action('echoaction', 'echo_cron');
add_action('echoactionclear', 'echo_auto_clear_log');
function echo_cron_schedule()
{
    $echo_Main_Settings = get_option('echo_Main_Settings', false);
    if (isset($echo_Main_Settings['echo_enabled']) && $echo_Main_Settings['echo_enabled'] === 'on') {
        if (!wp_next_scheduled('echoaction')) {
            $unlocker = get_option('echo_minute_running_unlocked', false);
            if($unlocker == '1')
            {
                $rez = wp_schedule_event(time(), 'minutely', 'echoaction');
            }
            else
            {
                $rez = wp_schedule_event(time(), 'hourly', 'echoaction');
            }
            if ($rez === FALSE) {
                echo_log_to_file('[Scheduler] Failed to schedule echoaction to echo_cron!');
            }
        }
        
        if (isset($echo_Main_Settings['enable_logging']) && $echo_Main_Settings['enable_logging'] === 'on' && isset($echo_Main_Settings['auto_clear_logs']) && $echo_Main_Settings['auto_clear_logs'] !== 'No') {
            if (!wp_next_scheduled('echoactionclear')) {
                $rez = wp_schedule_event(time(), $echo_Main_Settings['auto_clear_logs'], 'echoactionclear');
                if ($rez === FALSE) {
                    echo_log_to_file('[Scheduler] Failed to schedule echoactionclear to ' . $echo_Main_Settings['auto_clear_logs']);
                }
                add_option('echo_schedule_time', $echo_Main_Settings['auto_clear_logs'], '', false);
            } else {
                if (!get_option('echo_schedule_time')) {
                    wp_clear_scheduled_hook('echoactionclear');
                    $rez = wp_schedule_event(time(), $echo_Main_Settings['auto_clear_logs'], 'echoactionclear');
                    add_option('echo_schedule_time', $echo_Main_Settings['auto_clear_logs'], '', false);
                    if ($rez === FALSE) {
                        echo_log_to_file('[Scheduler] Failed to schedule echoactionclear to ' . $echo_Main_Settings['auto_clear_logs']);
                    }
                } else {
                    $the_time = get_option('echo_schedule_time');
                    if ($the_time != $echo_Main_Settings['auto_clear_logs']) {
                        wp_clear_scheduled_hook('echoactionclear');
                        delete_option('echo_schedule_time');
                        $rez = wp_schedule_event(time(), $echo_Main_Settings['auto_clear_logs'], 'echoactionclear');
                        add_option('echo_schedule_time', $echo_Main_Settings['auto_clear_logs'], '', false);
                        if ($rez === FALSE) {
                            echo_log_to_file('[Scheduler] Failed to schedule echoactionclear to ' . $echo_Main_Settings['auto_clear_logs']);
                        }
                    }
                }
            }
        } else {
            if (!wp_next_scheduled('echoactionclear')) {
                delete_option('echo_schedule_time');
            } else {
                wp_clear_scheduled_hook('echoactionclear');
                delete_option('echo_schedule_time');
            }
        }
    } else {
        if (wp_next_scheduled('echoaction')) {
            wp_clear_scheduled_hook('echoaction');
        }
        
        if (!wp_next_scheduled('echoactionclear')) {
            delete_option('echo_schedule_time');
        } else {
            wp_clear_scheduled_hook('echoactionclear');
            delete_option('echo_schedule_time');
        }
    }
}
function echo_cron()
{
    $echo_Main_Settings = get_option('echo_Main_Settings', false);
    if (isset($echo_Main_Settings['echo_enabled']) && $echo_Main_Settings['echo_enabled'] === 'on') {
        if (isset($echo_Main_Settings['auto_delete_enabled']) && $echo_Main_Settings['auto_delete_enabled'] === 'on') {
            $postsPerPage = 50000;
            $paged = 0;
            do
            {
                $postOffset = $paged * $postsPerPage;
                $query              = array(
                    'post_status' => array(
                        'publish',
                        'draft',
                        'pending',
                        'trash',
                        'private',
                        'future'
                    ),
                    'post_type' => array(
                        'any'
                    ),
                    'numberposts' => $postsPerPage,
                    'fields' => 'ids',
                    'meta_key' => 'echo_delete_time',
                    'offset'  => $postOffset
                );
                $post_list          = get_posts($query);
                $paged++;
                wp_suspend_cache_addition(true);
                foreach($post_list as $p)
                {
                    $exp_time = get_post_meta($p, 'echo_delete_time', true);
                    if($exp_time != '' && $exp_time !== false)
                    {
                        if(time() > intval($exp_time))
                        {
                            if (isset($echo_Main_Settings['enable_detailed_logging'])) {
                                echo_log_to_file('[Auto Delete] Automatically deleting post ID ' . $p . ', exptime: ' . $exp_time . ', current time: ' . time());
                            }
                            $args             = array(
                                'post_parent' => $p
                            );
                            $post_attachments = get_children($args);
                            if (isset($post_attachments) && !empty($post_attachments)) {
                                foreach ($post_attachments as $attachment) {
                                    wp_delete_attachment($attachment->ID, true);
                                }
                            }
                            $res = wp_delete_post($p, true);
                            if ($res === false) {
                                echo_log_to_file('[Scheduler] Failed to automatically delete post ' . $p . ', exptime: ' . $exp_time . ', time: ' . time());
                            }
                        }
                    }
                }
                wp_suspend_cache_addition(false);
            }while(!empty($post_list));
            $post_list = null;
            unset($post_list);
        }
        if (isset($echo_Main_Settings['run_after']) && $echo_Main_Settings['run_after'] != '' && isset($echo_Main_Settings['run_before']) && $echo_Main_Settings['run_before'] != '') 
        {
            $exit = true;
            $mytime = date("H:i");
            $min_time = $echo_Main_Settings['run_after'];
            $max_time = $echo_Main_Settings['run_before'];
            $date1 = DateTime::createFromFormat('H:i', $mytime);
            $date2 = DateTime::createFromFormat('H:i', $min_time);
            $date3 = DateTime::createFromFormat('H:i', $max_time);
            if ($date1 > $date2 && $date1 < $date3)
            {
                $exit = false;
            }
            if($exit == true)
            {
                return;
            }
        }
        $GLOBALS['wp_object_cache']->delete('echo_running_list', 'options');
        $running = get_option('echo_running_list');
        $curr_time = time();
        $update = false;
        if(is_array($running))
        {
            foreach($running as $key => $value)
            {
                if(($curr_time - $key > 3600) && $key > 1000)
                {
                    unset($running[$key]);
                    $update = true;
                }
            }
        }
        if($update === true)
        {
            update_option('echo_running_list', $running);
        }
        $GLOBALS['wp_object_cache']->delete('echo_rules_list', 'options');
        if (!get_option('echo_rules_list')) {
            $rules = array();
        } else {
            $rules = get_option('echo_rules_list');
        }
        $rule_run = false;
        $unlocker = get_option('echo_minute_running_unlocked', false);
        if (!empty($rules)) {
            $cont = 0;
            foreach ($rules as $request => $bundle[]) {
                $bundle_values   = array_values($bundle);
                $myValues        = $bundle_values[$cont];
                $array_my_values = array_values($myValues);for($iji=0;$iji<count($array_my_values);++$iji){if(is_string($array_my_values[$iji])){$array_my_values[$iji]=stripslashes($array_my_values[$iji]);}}
                $schedule        = isset($array_my_values[1]) ? $array_my_values[1] : '24';
                $active          = isset($array_my_values[2]) ? $array_my_values[2] : '0';
                $last_run        = isset($array_my_values[3]) ? $array_my_values[3] : echo_get_date_now();
                if ($active == '1') {
                    $now            = echo_get_date_now();
                    if($unlocker == '1')
                    {
                        $nextrun        = echo_add_minute($last_run, $schedule);
                        $echo_hour_diff = (int) echo_minute_diff($now, $nextrun);
                    }
                    else
                    {
                        $nextrun        = echo_add_hour($last_run, $schedule);
                        $echo_hour_diff = (int) echo_hour_diff($now, $nextrun);
                    }
                    if ($echo_hour_diff >= 0) {
                        if($rule_run === false)
                        {
                            $rule_run = true;
                        }
                        else
                        {
                            if (isset($echo_Main_Settings['rule_delay']) && $echo_Main_Settings['rule_delay'] !== '')
                            {
                                sleep($echo_Main_Settings['rule_delay']);
                            }
                        }
                        echo_run_rule($cont); 
                    }
                }
                $cont = $cont + 1;
            }
            $running = array();
            update_option('echo_running_list', $running);
        }
    }
}

function echo_log_to_file($str)
{
    $echo_Main_Settings = get_option('echo_Main_Settings', false);
    if (isset($echo_Main_Settings['enable_logging']) && $echo_Main_Settings['enable_logging'] == 'on') {
        $tz = echo_get_blog_timezone();
        if($tz !== false)
            date_default_timezone_set($tz->getName());
        $d = date("j-M-Y H:i:s e", time());
        error_log("[$d] " . $str . "<br/>\r\n", 3, WP_CONTENT_DIR . '/echo_info.log');
        if($tz !== false)
            date_default_timezone_set('UTC');
    }
}
function echo_delete_all_posts()
{
    $failed             = false;
    $number             = 0;
    $echo_Main_Settings = get_option('echo_Main_Settings', false);
    $postsPerPage = 50000;
    $paged = 0;
    do
    {
        $postOffset = $paged * $postsPerPage;
        $query              = array(
            'post_status' => array(
                'publish',
                'draft',
                'pending',
                'trash',
                'private',
                'future'
            ),
            'post_type' => array(
                'any'
            ),
            'numberposts' => $postsPerPage,
            'fields' => 'ids',
            'meta_key' => 'echo_parent_rule',
            'offset'  => $postOffset
        );
        $post_list          = get_posts($query);
        $paged++;
        wp_suspend_cache_addition(true);
        foreach ($post_list as $post) {
            $index = get_post_meta($post, 'echo_parent_rule', true);
            if (isset($index) && $index !== '') {
                $args             = array(
                    'post_parent' => $post
                );
                $post_attachments = get_children($args);
                if (isset($post_attachments) && !empty($post_attachments)) {
                    foreach ($post_attachments as $attachment) {
                        wp_delete_attachment($attachment->ID, true);
                    }
                }
                $res = wp_delete_post($post, true);
                if ($res === false) {
                    $failed = true;
                } else {
                    $number++;
                }
            }
        }
        wp_suspend_cache_addition(false);
    }while(!empty($post_list));
    $post_list = null;
    unset($post_list);
    if ($failed === true) {
        if (isset($echo_Main_Settings['enable_detailed_logging'])) {
            echo_log_to_file('[PostDelete] Failed to delete all posts!');
        }
    } else {
        if (isset($echo_Main_Settings['enable_detailed_logging'])) {
            echo_log_to_file('[PostDelete] Successfuly deleted ' . esc_html($number) . ' posts!');
        }
    }
}

if (!function_exists('echo_xmlrpc')) {
   function echo_xmlrpc($args = array()) {
      $args['weblogUpdates.ping'] = 'echo_ping';
      return $args;
   }
}

if (!function_exists('echo_ping')) {
   function echo_ping($args) {
      $echo_Main_Settings = get_option('echo_Main_Settings', false);
      global $wpdb;
      $max_xmlrpc_interval = 3600;
      if (isset($echo_Main_Settings['echo_enabled']) && $echo_Main_Settings['echo_enabled'] === 'on') {
          if (isset($echo_Main_Settings['xmlrpc_enabled']) && $echo_Main_Settings['xmlrpc_enabled'] === 'on') {
                $GLOBALS['wp_object_cache']->delete('echo_rules_list', 'options');
                if (!get_option('echo_rules_list')) {
                    $rules = array();
                } else {
                    $rules = get_option('echo_rules_list');
                }
                $feeds = '';
                $param = 0;
                $last_run = 'now';
                if (!empty($rules)) {
                   foreach ($rules as $request => $bundle[]) {
                        $bundle_values    = array_values($bundle);
                        $myValues         = $bundle_values[$param];
                        $array_my_values  = array_values($myValues);for($iji=0;$iji<count($array_my_values);++$iji){if(is_string($array_my_values[$iji])){$array_my_values[$iji]=stripslashes($array_my_values[$iji]);}}
                        $ids              = isset($array_my_values[0]) ? $array_my_values[0] : '';
                        $last_run         = isset($array_my_values[3]) ? $array_my_values[3] : '';
                        if($ids == $args[1])
                        {
                            $feeds = $ids;
                            break;
                        }
                        $param++;
                   }
                }
                if ($feeds != '') {
                   $date1x = new DateTime($last_run, echo_get_blog_timezone());
                   if (time() > $date1x->getTimestamp() + $max_xmlrpc_interval) 
                   {
                        echo_run_rule($param); 
                        $GLOBALS['wp_object_cache']->delete('echo_rules_list', 'options');
                        $rules = get_option('echo_rules_list', array());
                        $rules[$param][3] = echo_get_date_now();
                        update_option('echo_rules_list', $rules, false);
                        return array('flerror' => false, 'message' => esc_html__('Thanks for the ping.', 'rss-feed-post-generator-echo'));
                   }
                   else {
                        return array('flerror' => true, 'message' => esc_html__('Not enough time has passed since your last ping.', 'rss-feed-post-generator-echo'));
                   }
               }
               else {
                   return array('flerror' => true, 'message' => esc_html__('Your blog is not registered with our service. Please contact the administrator for details.', 'rss-feed-post-generator-echo'));
               }
          }
      }
      return true;
   }
}

function echo_delete_all_rules()
{
    update_option('echo_rules_list', array());
}
function echo_replaceContentShortcodes($the_content, $just_title, $content, $item_url, $item_cat, $item_tags, $item_image, $feed_title, $feed_description, $description, $feed_logo, $author, $author_link, $author_email, $read_more, $date, $custom_tag_var_list, $custom_feed_tag_var_list, $img_attr, $screenimageURL, $feed_url, $source_id, $xexcerpt)
{
    $matches = array();
    $i = 0;
    preg_match_all('~%regex\(\s*\"([^"]+?)\s*"\s*[,;]\s*\"([^"]*)\"\s*(?:[,;]\s*\"([^"]*?)\s*\")?(?:[,;]\s*\"([^"]*?)\s*\")?(?:[,;]\s*\"([^"]*?)\s*\")?\)%~si', $the_content, $matches);
    if (is_array($matches) && count($matches) && is_array($matches[0])) {
        for($i = 0; $i < count($matches[0]); $i++)
        {
            if (isset($matches[0][$i])) $fullmatch = $matches[0][$i];
            if (isset($matches[1][$i])) $search_in = echo_replaceContentShortcodes($matches[1][$i], $just_title, $content, $item_url, $item_cat, $item_tags, $item_image, $feed_title, $feed_description, $description, $feed_logo, $author, $author_link, $author_email, $read_more, $date, $custom_tag_var_list, $custom_feed_tag_var_list, $img_attr, $screenimageURL, $feed_url, $source_id, $xexcerpt);
            if (isset($matches[2][$i])) $matchpattern = $matches[2][$i];
            if (isset($matches[3][$i])) $element = $matches[3][$i];
            if (isset($matches[4][$i])) $delimeter = $matches[4][$i];if (isset($matches[5][$i])) $counter = $matches[5][$i];
            if (isset($matchpattern)) {
               if (preg_match('<^[\/#%+~[\]{}][\s\S]*[\/#%+~[\]{}]$>', $matchpattern, $z)) {
                  $ret = preg_match_all($matchpattern, $search_in, $submatches, PREG_PATTERN_ORDER);
               }
               else {
                  $ret = preg_match_all('~'.$matchpattern.'~si', $search_in, $submatches, PREG_PATTERN_ORDER);
               }
            }
            if (isset($submatches)) {
               if (is_array($submatches)) {
                  $empty_elements = array_keys($submatches[0], "");
                  foreach ($empty_elements as $e) {
                     unset($submatches[0][$e]);
                  }
                  $submatches[0] = array_unique($submatches[0]);
                  if (!is_numeric($element)) {
                     $element = 0;
                  }if (!is_numeric($counter)) {
                     $counter = 0;
                  }
                  if(isset($submatches[(int)($element)]))
                  {
                      $matched = $submatches[(int)($element)];
                  }
                  else
                  {
                      $matched = '';
                  }
                  $matched = array_unique((array)$matched);
                  if (empty($delimeter) || $delimeter == 'null') {
                     if (isset($matched[$counter])) $matched = $matched[$counter];
                  }
                  else {
                     $matched = implode($delimeter, $matched);
                  }
                  if (empty($matched)) {
                     $the_content = str_replace($fullmatch, '', $the_content);
                  } else {
                     $the_content = str_replace($fullmatch, $matched, $the_content);
                  }
               }
            }
        }
    }
    
    preg_match_all('~%regextext\(\s*\"([^"]+?)\s*"\s*,\s*\"([^"]*)\"\s*(?:,\s*\"([^"]*?)\s*\")?(?:,\s*\"([^"]*?)\s*\")?(?:,\s*\"([^"]*?)\s*\")?\)%~si', $the_content, $matches);
    if (is_array($matches) && count($matches) && is_array($matches[0])) {
        for($i = 0; $i < count($matches[0]); $i++)
        {
            if (isset($matches[0][$i])) $fullmatch = $matches[0][$i];
            if (isset($matches[1][$i])) $search_in = echo_replaceContentShortcodes($matches[1][$i], $just_title, $content, $item_url, $item_cat, $item_tags, $item_image, $feed_title, $feed_description, $description, $feed_logo, $author, $author_link, $author_email, $read_more, $date, $custom_tag_var_list, $custom_feed_tag_var_list, $img_attr, $screenimageURL, $feed_url, $source_id, $xexcerpt);
            if (isset($matches[2][$i])) $matchpattern = $matches[2][$i];
            if (isset($matches[3][$i])) $element = $matches[3][$i];
            if (isset($matches[4][$i])) $delimeter = $matches[4][$i];if (isset($matches[5][$i])) $counter = $matches[5][$i];
            $search_in = strip_tags($search_in, '<p><br>');
            $search_in = preg_replace("/<p[^>]*?>/", "", $search_in);
            $search_in = str_replace("</p>", "<br />", $search_in);
            $search_in = preg_replace('/\<br(\s*)?\/?\>/i', "\r\n\r\n", $search_in);
            $search_in = preg_replace('/^(?:\r|\n|\r\n)+/', '', $search_in);
            if (isset($matchpattern)) {
               if (preg_match('<^[\/#%+~[\]{}][\s\S]*[\/#%+~[\]{}]$>', $matchpattern, $z)) {
                  $ret = preg_match_all($matchpattern, $search_in, $submatches, PREG_PATTERN_ORDER);
               }
               else {
                  $ret = preg_match_all('~'.$matchpattern.'~si', $search_in, $submatches, PREG_PATTERN_ORDER);
               }
            }
            if (isset($submatches)) {
               if (is_array($submatches)) {
                  $empty_elements = array_keys($submatches[0], "");
                  foreach ($empty_elements as $e) {
                     unset($submatches[0][$e]);
                  }
                  $submatches[0] = array_unique($submatches[0]);
                  if (!is_numeric($element)) {
                     $element = 0;
                  }if (!is_numeric($counter)) {
                     $counter = 0;
                  }
                  if(isset($submatches[(int)($element)]))
                  {
                      $matched = $submatches[(int)($element)];
                  }
                  else
                  {
                      $matched = '';
                  }
                  $matched = array_unique((array)$matched);
                  if (empty($delimeter) || $delimeter == 'null') {
                     if (isset($matched[$counter])) $matched = $matched[$counter];
                  }
                  else {
                     $matched = implode($delimeter, $matched);
                  }
                  if (empty($matched)) {
                     $the_content = str_replace($fullmatch, '', $the_content);
                  } else {
                     $the_content = str_replace($fullmatch, $matched, $the_content);
                  }
               }
            }
        }
    }
    
    preg_match_all('~%%cnatax_([^%]*?)%%~si', $the_content, $xmatches);
    if (is_array($xmatches) && count($xmatches) && is_array($xmatches[1])) {
        for($i = 0; $i < count($xmatches[1]); $i++)
        {
            $cna_rez = get_the_terms($source_id, $xmatches[1][$i]);
            if(is_wp_error($cna_rez) || $cna_rez == false)
            {
                $the_content = str_replace('%%cnatax_' . $xmatches[1][$i] . '%%', '', $the_content);
            }
            else
            {
                $the_content = str_replace('%%cnatax_' . $xmatches[1][$i] . '%%', $cna_rez[0]->name, $the_content);
            }
        }
    }
    preg_match_all('~(%%cnasourcetitle%%)~si', $the_content, $xmatchesx);
    if (is_array($xmatchesx) && count($xmatchesx) && is_array($xmatchesx[1])) {
        for($i = 0; $i < count($xmatchesx[1]); $i++)
        {
            $cna_rez = get_the_title( $source_id );
            if(is_wp_error($cna_rez) || $cna_rez == false)
            {
                $the_content = str_replace('%%cnasourcetitle%%', '', $the_content);
            }
            else
            {
                $the_content = str_replace('%%cnasourcetitle%%', $cna_rez, $the_content);
            }
        }
    }
    $spintax = new Echo_Spintax();
    $the_content = $spintax->process($the_content);
    $pcxxx = explode('<!- template ->', $the_content);
    $the_content = $pcxxx[array_rand($pcxxx)];
    $the_content = str_replace('%%random_sentence%%', echo_random_sentence_generator(), $the_content);
    $the_content = str_replace('%%random_sentence2%%', echo_random_sentence_generator(false), $the_content);
    $echo_Main_Settings = get_option('echo_Main_Settings', false);
    if (isset($echo_Main_Settings['custom_html'])) {
        $the_content = str_replace('%%custom_html%%', html_entity_decode($echo_Main_Settings['custom_html']), $the_content);
    }
    if (isset($echo_Main_Settings['custom_html2'])) {
        $the_content = str_replace('%%custom_html2%%', html_entity_decode($echo_Main_Settings['custom_html2']), $the_content);
    }
    if (isset($echo_Main_Settings['conditional_words']) && $echo_Main_Settings['conditional_words'] != '') 
    {
        $cvl = explode(',', $echo_Main_Settings['conditional_words']);
        $rpl = false;
        foreach($cvl as $cv)
        {
            $cv = trim($cv);
            if(stristr($content, $cv) !== false)
            {
                $the_content = str_replace('%%conditional_words%%', $cv, $the_content);
                $rpl = true;
                break;
            }
        }
        if($rpl == false)
        {
            foreach($cvl as $cv)
            {
                $cv = trim($cv);
                if(stristr($just_title, $cv) !== false)
                {
                    $the_content = str_replace('%%conditional_words%%', $cv, $the_content);
                    $rpl = true;
                    break;
                }
            }
        }
        if($rpl == false)
        {
            $the_content = str_replace('%%conditional_words%%', '', $the_content);
        }
    }
    else
    {
        $the_content = str_replace('%%conditional_words%%', '', $the_content);
    }
    $the_content = str_replace('%%item_title%%', $just_title, $the_content);
    $the_content = str_replace('%%item_content%%', $content, $the_content);
    $the_content = str_replace('%%feed_url%%', $feed_url, $the_content);
    $the_content = str_replace('%%item_url%%', $item_url, $the_content);
    $domain_url = $item_url;
    if(strstr($the_content, '%%item_final_url%%') !== false)
    {
        preg_match_all('#&url=([^&]*?)&#', htmlspecialchars_decode($item_url), $redir_url);
        if(isset($redir_url[1][0]))
        {
            $the_content = str_replace('%%item_final_url%%', urldecode($redir_url[1][0]), $the_content);
        }
        else
        {
            $the_content = str_replace('%%item_final_url%%', $item_url, $the_content);
        }
    }
    if(strstr($the_content, '%%item_url_domain%%') !== false)
    {
        preg_match_all('#&url=([^&]*?)&#', htmlspecialchars_decode($item_url), $redir_url);
        if(isset($redir_url[1][0]))
        {
            $domain_url = urldecode($redir_url[1][0]);
        }
        $parseUrl = parse_url(trim($domain_url)); 
        $item_url_domain = trim($parseUrl['host'] ? $parseUrl['host'] : array_shift(explode('/', $parseUrl['path'], 2)));
        $the_content = str_replace('%%item_url_domain%%', $item_url_domain, $the_content);
    }
    $img_attr = str_replace('%%image_source_name%%', '', $img_attr);
    $img_attr = str_replace('%%image_source_url%%', '', $img_attr);
    $img_attr = str_replace('%%image_source_website%%', '', $img_attr);
    $the_content = str_replace('%%royalty_free_image_attribution%%', $img_attr, $the_content);
    $the_content = str_replace('%%item_cat%%', $item_cat, $the_content);
    $the_content = str_replace('%%item_tags%%', $item_tags, $the_content);
    $expl_tag = explode(',', $item_tags);
    $expl_tag = trim($expl_tag[array_rand($expl_tag)]);
    $the_content = str_replace('%%item_random_tag%%', $expl_tag, $the_content);
    $the_content = str_replace('%%item_content_plain_text%%', echo_getPlainContent($content), $the_content);
    $the_content = str_replace('%%item_read_more_button%%', echo_getReadMoreButton($item_url, $read_more), $the_content);
    $the_content = str_replace('%%item_show_image%%', echo_getItemImage($item_image, $just_title), $the_content);
    $the_content = str_replace('%%item_image_URL%%', $item_image, $the_content);
    $the_content = str_replace('%%feed_title%%', $feed_title, $the_content);
    if($feed_description == null)
    {
        $the_content = str_replace('%%feed_description%%', '', $the_content);
    }
    else
    {
        $the_content = str_replace('%%feed_description%%', $feed_description, $the_content);
    }
    $the_content = str_replace('%%item_description%%', $description, $the_content);
    $the_content = str_replace('%%item_excerpt%%', $xexcerpt, $the_content);
    if ((isset($echo_Main_Settings['date_format']) && $echo_Main_Settings['date_format'] !== ''))
    {
        $timest = strtotime($date);
        if($timest != false)
        {
            $tmp_date = date($echo_Main_Settings['date_format'], $timest);
            if($tmp_date != false)
            {
                $date = $tmp_date;
            }
        }
    }
    $the_content = str_replace('%%item_pub_date%%', $date, $the_content);
    $the_content = str_replace('%%feed_logo%%', $feed_logo, $the_content);
    $the_content = str_replace('%%author%%', $author, $the_content);
    $the_content = str_replace('%%author_link%%', $author_link, $the_content);
    $the_content = str_replace('%%author_email%%', $author_email, $the_content);
    foreach($custom_tag_var_list as $ctag => $ctl)
    {
        $the_content = str_replace('%%custom_' . $ctag . '%%', $ctl, $the_content);
    }
    foreach($custom_feed_tag_var_list as $ctag => $ctl)
    {
        $the_content = str_replace('%%custom_feed_' . $ctag . '%%', $ctl, $the_content);
    }
    if($screenimageURL != '')
    {
        $the_content = str_replace('%%item_screenshot_url%%', esc_url_raw($screenimageURL), $the_content);
        $the_content = str_replace('%%item_show_screenshot%%', echo_getItemImage(esc_url_raw($screenimageURL), $just_title), $the_content);
    }
    else
    {
        $snap = 'http://s.wordpress.com/mshots/v1/';
        if (isset($echo_Main_Settings['screenshot_height']) && $echo_Main_Settings['screenshot_height'] != '') 
        {
            $h = esc_attr($echo_Main_Settings['screenshot_height']);
        }
        else
        {
            $h = '450';
        }
        if (isset($echo_Main_Settings['screenshot_width']) && $echo_Main_Settings['screenshot_width'] != '') 
        {
            $w = esc_attr($echo_Main_Settings['screenshot_width']);
        }
        else
        {
            $w = '600';
        }
        $the_content = str_replace('%%item_screenshot_url%%', esc_url_raw($snap . urlencode($item_url) . '?w=' . $w . '&h=' . $h), $the_content);
        $the_content = str_replace('%%item_show_screenshot%%', echo_getItemImage(esc_url_raw($snap . urlencode($item_url) . '?w=' . $w . '&h=' . $h), $just_title), $the_content);
    }
    $the_content = preg_replace_callback('#%%random_image_url\[([^\]]*?)\]%%#', function ($matches) {
        $my_img = echo_get_random_image_google($matches[1]);
        return $my_img;
    }, $the_content);
    $the_content = preg_replace_callback('#%%random_image\[([^\]]*?)\]%%#', function ($matches) {
        $my_img = echo_get_random_image_google($matches[1]);
        return '<img src="' . $my_img . '">';
    }, $the_content);
    $the_content = echo_replaceSynergyShortcodes($the_content);
    return $the_content;
}

function echo_summarize_content($title, $content)
{
    $echo_Main_Settings = get_option('echo_Main_Settings', false);
    if (!isset($echo_Main_Settings['best_password']) || trim($echo_Main_Settings['best_password']) == '') {
        echo_log_to_file('Please insert a valid "TLDR" API key.');
        return FALSE;
    }
    $curl = curl_init();
    if($curl === false)
    {
        return false;
    }
    $content = strip_tags($content, '<br>');
    $content = preg_replace('#<br\s*/?>#i', "\n", $content);
    $fdata = "";
    $xdata = array();
    if (!isset($echo_Main_Settings['tldr_max']) || trim($echo_Main_Settings['tldr_max']) == '') 
    {
        $tmax = 300;
    }
    else
    {
        $tmax = intval($echo_Main_Settings['tldr_max']);
    }
    if (!isset($echo_Main_Settings['tldr_min']) || trim($echo_Main_Settings['tldr_min']) == '') 
    {
        $tmin = 100;
    }
    else
    {
        $tmin = intval($echo_Main_Settings['tldr_min']);
    }
    
    $xdata['min_length'] = $tmin;
    $xdata['max_length'] = $tmax;
    $xdata['text'] = str_replace('"', '\'', $content);
    $fdata = json_encode($xdata);
    curl_setopt_array($curl, [
        CURLOPT_URL => "https://tldrthis.p.rapidapi.com/v1/model/abstractive/summarize-text/",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => $fdata,
        CURLOPT_HTTPHEADER => [
            "content-type: application/json",
            "x-rapidapi-host: tldrthis.p.rapidapi.com",
            "x-rapidapi-key: " . trim($echo_Main_Settings['best_password'])
        ],
    ]);
    $response = curl_exec($curl);
    if($response === false)
    {
        return false;
    }
    $err = curl_error($curl);
    curl_close($curl);
    if ($err) {
        echo_log_to_file( "cURL Error #:" . $err);
        return false;
    } else {
        $zali = json_decode($response);
        if($zali == false)
        {
            echo_log_to_file( "Failed to decode response: " . $response);
            return false;
        }
        if(!isset($zali->summary))
        {
            echo_log_to_file( "Failed to understand response: " . $response);
            return false;
        }
        $xsum = array($title, nl2br($zali->summary));
        return $xsum;
    }
}
add_action( 'wp_ajax_echo_create_rule', 'echo_create_rule' );
function echo_create_rule() 
{
    if ( !isset($_POST['nonce']) || !wp_verify_nonce( $_POST['nonce'], 'echo_rule_nonce'))
    {
        echo 'fail';
        die;
    }
    $feedurl = $_POST['feedurl'];
    if(empty($feedurl))
    {
        echo 'fail';
        die;
    }
    $GLOBALS['wp_object_cache']->delete('echo_rules_list', 'options');
    if (!get_option('echo_rules_list')) {
        $rules = array();
    } else {
        $rules = get_option('echo_rules_list');
    }
    $class_lst = array('echo_no_category_12345678');
    $post_type = 'post';
    $curr_id = get_current_user_id();
    if($curr_id == 0)
    {
        $curr_id = 1;
    }
    $my_arrg[0] = trim($feedurl);
    $my_arrg[1] = '24';
    $my_arrg[2] = '1';
    $my_arrg[3] = '1988-01-27 00:00:00';
    $my_arrg[4] = '10';
    $my_arrg[5] = 'publish';
    $my_arrg[6] = $post_type;
    $my_arrg[7] = $curr_id;
    $my_arrg[8] = '';
    $my_arrg[9] = $class_lst;
    $my_arrg[10] = '0';
    $my_arrg[11] = '0';
    $my_arrg[12] = '1';
    $my_arrg[13] = '1';
    $my_arrg[14] = '';
    $my_arrg[15] = '%%item_title%%';
    $my_arrg[16] = '%%item_content%%';
    $my_arrg[17] = '1';
    $my_arrg[18] = 'post-format-standard';
    $my_arrg[19] = '0';
    $my_arrg[20] = '0';
    $my_arrg[21] = '0';
    $my_arrg[22] = 'id';
    $my_arrg[23] = '0';
    $my_arrg[24] = '';
    $my_arrg[25] = '0';
    $my_arrg[26] = '';
    $my_arrg[27] = '';
    $my_arrg[28] = '';
    $my_arrg[29] = '';
    $my_arrg[30] = 'NO_CHANGE';
    $my_arrg[31] = '';
    $my_arrg[32] = 'disabled';
    $my_arrg[33] = '0';
    $my_arrg[34] = '0';
    $my_arrg[35] = '1';
    $my_arrg[36] = '1';
    $my_arrg[37] = uniqid('', true);
    $my_arrg[38] = '';
    $my_arrg[39] = '0';
    $my_arrg[40] = '0';
    $my_arrg[41] = '';
    $my_arrg[42] = '';
    $my_arrg[43] = '0';
    $my_arrg[44] = '0';
    $my_arrg[45] = '';
    $my_arrg[46] = '';
    $my_arrg[47] = 'disabled';
    $my_arrg[48] = '';
    $my_arrg[49] = '';
    $my_arrg[50] = '';
    $my_arrg[51] = '0';
    $my_arrg[52] = '';
    $my_arrg[53] = '';
    $my_arrg[54] = '';
    $my_arrg[55] = '';
    $my_arrg[56] = '';
    $my_arrg[57] = '0';
    $my_arrg[58] = '';
    $my_arrg[59] = '0';
    $my_arrg[60] = '0';
    $my_arrg[61] = '';
    $my_arrg[62] = '';
    $my_arrg[63] = '';
    $my_arrg[64] = '';
    $my_arrg[65] = '';
    $my_arrg[66] = '';
    $my_arrg[67] = '';
    $my_arrg[68] = '';
    $my_arrg[69] = '';
    $my_arrg[70] = '';
    $my_arrg[71] = '';
    $my_arrg[72] = '';
    $my_arrg[73] = '';
    $my_arrg[74] = '';
    $my_arrg[75] = '';
    $my_arrg[76] = '';
    $my_arrg[77] = '';
    $my_arrg[78] = '';
    $my_arrg[79] = '';
    $my_arrg[80] = '';
    $my_arrg[81] = '';
    $my_arrg[82] = '';
    $my_arrg[83] = '';
    $my_arrg[84] = '';
    $my_arrg[85] = '';
    $my_arrg[86] = '';
    $my_arrg[87] = '';
    $my_arrg[88] = '';
    $my_arrg[89] = '';
    $my_arrg[90] = '';
    $my_arrg[91] = '';
    $my_arrg[92] = '';
    $my_arrg[93] = '';
    $my_arrg[94] = '';
    $my_arrg[95] = '';
    $my_arrg[96] = '';
    $my_arrg[97] = '';
    $my_arrg[98] = '';
    $my_arrg[99] = '';
    $my_arrg[100] = '';
    $my_arrg[101] = '';
    $my_arrg[102] = '';
    $rules[] = $my_arrg;
    update_option('echo_rules_list', $rules, false);
    echo 'ok';
    die;
}
add_action( 'wp_ajax_echo_iframe', 'echo_iframe_callback' );
function echo_iframe_callback() {
        $echo_Main_Settings = get_option('echo_Main_Settings', false);
		if(!current_user_can('administrator')) die();
        $started = '%3Cs';
		$url = null;
		$use_proxy = isset($_GET['useproxy']) ? $_GET['useproxy'] : '' ;
        $custom_simple = isset($_GET['customsimple']) ? $_GET['customsimple'] : '' ;
        $use_phantom = isset($_GET['usephantom']) ? $_GET['usephantom'] : '' ;
		$cookie = isset($_GET['crawlCookie']) ? $_GET['crawlCookie'] : '' ;
		$customUA = isset($_GET['customUA']) ? $_GET['customUA'] : '' ;
		$htuser_pass = isset($_GET['userpass']) ? $_GET['userpass'] : '' ;
        $url = $_GET['address'];
        
        if($custom_simple == '0' || $custom_simple == '')
        {
            if(isset($echo_Main_Settings['echo_custom_simplepie']) && $echo_Main_Settings['echo_custom_simplepie'] == 'on') 
            {
                $custom_simple = '1';
            }
            else
            {
                $custom_simple = '2';
            }
        }
        if ($custom_simple == '1')
        {
            try
            {
                if(!class_exists('SimplePie_Autoloader', false))
                {
                    require_once(dirname(__FILE__) . "/res/simplepie/autoloader.php");
                }
            }
            catch(Exception $e) 
            {
                echo_log_to_file('Failed to load SimplePie in Visual Selector.');
                echo 'Failed to load SimplePie in Visual Selector.';
                exit();
            }
        }
        elseif ($custom_simple == '2')
        {
            if ( ! class_exists( 'SimplePie', false ) ) {
                require_once( ABSPATH . WPINC . '/class-simplepie.php' );
            }
            include_once (ABSPATH . WPINC . '/feed.php');
        }
        elseif ($custom_simple == '3')
        {
            if(!class_exists('SimpleXMLElement'))
            {
                echo_log_to_file('Please activate the SimpleXMLElement module on your site for this feature to work!');
                echo 'Please activate the SimpleXMLElement module on your site for this feature to work!';
                exit();
            }
            if(!class_exists('Echo_RSS_Feed'))
            {
                require_once (dirname(__FILE__) . "/res/rss-php/Feed.php");
            }
        }
        else
        {
            echo_log_to_file('Unrecognized custom_simplepie value in Visual Selector: ' . $custom_simple);
            echo 'Unrecognized custom_simplepie value in Visual Selector: ' . $custom_simple;
            exit();
        }
        $url = echo_replaceSynergyShortcodes($url);

        if($customUA == 'random')
        {
            $customUA = echo_get_random_user_agent();
        }

		if ( !$url ) {
            echo_log_to_file('Empty URL value in Visual Selector.');
            echo 'Empty URL value in Visual Selector.';
            exit();
		}
        $fitem_url = '';
        try
        {
            $feed_uri = $url;
            if ($custom_simple == '1') 
            {
                $feed = new SimplePie();
                if(method_exists($feed, 'set_curl_options'))
                {
                    $feed->set_curl_options(
                        array(
                            CURLOPT_SSL_VERIFYHOST => false,
                            CURLOPT_SSL_VERIFYPEER => false
                        )
                    );
                }
                if ((isset($echo_Main_Settings['echo_force_feeds']) && $echo_Main_Settings['echo_force_feeds'] == 'on') || (strpos($feed_uri, 'feeds.bbci.co.uk') !== false)) {
                    $feed->force_feed(true);
                }
                if ($use_proxy && isset($echo_Main_Settings['proxy_url']) && $echo_Main_Settings['proxy_url'] != '' && $echo_Main_Settings['proxy_url'] != 'disable' && $echo_Main_Settings['proxy_url'] != 'disabled') {
                    $prx_arr = array();
                    $prx = explode(',', $echo_Main_Settings['proxy_url']);
                    $randomness = array_rand($prx);
                    $prx_arr[CURLOPT_PROXY] = trim($prx[$randomness]);
                    if (isset($echo_Main_Settings['proxy_auth']) && $echo_Main_Settings['proxy_auth'] != '') 
                    {
                        $prx_auth = explode(',', $echo_Main_Settings['proxy_auth']);
                        if(isset($prx_auth[$randomness]) && trim($prx_auth[$randomness]) != '')
                        {
                            $prx_arr[CURLOPT_PROXYUSERPWD] = trim($prx_auth[$randomness]);
                        }
                    }
                    if(method_exists($feed, 'set_curl_options'))
                    {
                        $feed->set_curl_options($prx_arr);
                    }
                }
                if($customUA != '')
                {
                    if(method_exists($feed, 'set_useragent'))
                    {
                        $feed->set_useragent($customUA);
                    }
                }
                else
                {
                    if (isset($echo_Main_Settings['clear_user_agent']) && $echo_Main_Settings['clear_user_agent'] == 'on') {
                        if(method_exists($feed, 'set_curl_options'))
                        {
                            $feed->set_useragent($customUA);
                        }
                    }
                }
                if (isset($echo_Main_Settings['custom_feed_timeout']) && $echo_Main_Settings['custom_feed_timeout'] != '') {
                    $feed->set_timeout($echo_Main_Settings['custom_feed_timeout']);
                }
                $feed->set_feed_url($feed_uri);
                if (isset($echo_Main_Settings['echo_enable_caching']) && $echo_Main_Settings['echo_enable_caching'] == 'on') {
                    $feed->enable_cache(true);
                    $feed->set_cache_location(dirname(__FILE__) . "/res/cache");
                }
                else
                {
                    $feed->enable_cache(false);
                }
                if (isset($echo_Main_Settings['echo_no_strip']) && $echo_Main_Settings['echo_no_strip'] == 'on') {
                    $feed->strip_htmltags(false);
                }
                $feed->init();
                $feed->handle_content_type();
                if ($feed->error()) {
                    echo_log_to_file('Error when reading feed: ' . $feed->error());
                    echo 'Error when reading feed: ' . $feed->error();
                    exit();
                }
            }
            elseif ($custom_simple == '2')
            {
                add_action('wp_feed_options', 'echo_add_feed_options', 10,2);
                set_transient( 'echo_use_proxy', $use_proxy, '3600' );
                set_transient( 'echo_user_agent_cust', $customUA, '3600' );
                $feed = fetch_feed ( $feed_uri );
                remove_action('wp_feed_options', 'echo_add_feed_options', 10);
                if (is_wp_error ( $feed )){
                    echo_log_to_file('WP error when reading feed(2): ' . print_r($feed, true));
                    echo 'WP error when reading feed(2): ' . print_r($feed, true);
                    exit();
                }
            }
            elseif ($custom_simple == '3')
            {
                set_transient( 'echo_user_agent_cust', $customUA, '3600' ); 
                if (isset($echo_Main_Settings['echo_enable_caching']) && $echo_Main_Settings['echo_enable_caching'] == 'on') {
                    Echo_RSS_Feed::$cacheDir = dirname(__FILE__) . "/res/cache";
                    Echo_RSS_Feed::$cacheExpire = '1 hour';
                }
                $feed = Echo_RSS_Feed::load($feed_uri);
                if ($feed === false){
                    echo_log_to_file('Failed to read feed: ' . print_r($feed_uri, true));
                    echo 'Failed to read feed: ' . print_r($feed_uri, true);
                    exit();
                }
            }
            if ($custom_simple == '3')
            {
                if(isset($feed->channel))
                {
                    $feed     = $feed->channel;
                }
                elseif(isset($feed->Channel))
                {
                    $feed     = $feed->Channel;
                }
                foreach ($feed->item as $item)
                {
                    $items[] = $item;
                }
                
                if(count($items) == 0)
                {
                    foreach ($feed->entry as $fentry)
                    {
                        $items[] = $fentry;
                    }
                }
                foreach ($items as $item)
                {
                    if(isset($item->link))
                    {
                        $fitem_url = echo_encodeURI($item->link->__toString());
                    }
                    else
                    {
                        $fitem_url = '';
                    }
                    if($fitem_url != '')
                    {
                        break;
                    }
                }
            }
            else
            {
                $items = $feed->get_items();
                foreach ($items as $item)
                {
                    $fitem_url = echo_encodeURI($item->get_permalink());
                    if($fitem_url != '')
                    {
                        break;
                    }
                }
            }
            if ($custom_simple == '1') {
                $feed->__destruct();
            }
            $feed = null;
            unset($feed);
            if(count($items) == 0)
            {
                echo_log_to_file('No items found for feed: ' . print_r($feed_uri, true));
                echo 'No items found for feed: ' . print_r($feed_uri, true);
                exit();
            }
            if($fitem_url == '')
            {
                echo_log_to_file('No feed item URL found for feed: ' . print_r($feed_uri, true));
                echo 'No feed item URL found for feed: ' . print_r($feed_uri, true);
                exit();
            }
        }
        catch (Exception $e)
        {
            echo_log_to_file('Exception when parsing feed: ' . print_r($feed_uri, true) . ' - ' . $e->getMessage());
            echo 'Exception when parsing feed: ' . print_r($feed_uri, true) . ' - ' . $e->getMessage();
            exit();
        }

        $htmlcontent = '';
        $got_phantom = false;
        if($use_phantom == '1')
        {
            $htmlcontent = echo_get_page_PhantomJS($fitem_url, $cookie, $customUA, $use_proxy);
            if($htmlcontent !== false)
            {
                $got_phantom = true;
            }
        }
        elseif($use_phantom == '2')
        {
            $htmlcontent = echo_get_page_Puppeteer($fitem_url, $cookie, $customUA, $use_proxy, $htuser_pass);
            if($htmlcontent !== false)
            {
                $got_phantom = true;
            }
        }
        elseif($use_phantom == '4')
        {
            $htmlcontent = echo_get_page_PuppeteerAPI($fitem_url, $cookie, $customUA, $use_proxy, $htuser_pass, '');
            if($htmlcontent !== false)
            {
                $got_phantom = true;
            }
        }
        elseif($use_phantom == '5')
        {
            $htmlcontent = echo_get_page_TorAPI($fitem_url, $cookie, $customUA, $use_proxy, $htuser_pass, '');
            if($htmlcontent !== false)
            {
                $got_phantom = true;
            }
        }
        elseif($use_phantom == '6')
        {
            $htmlcontent = echo_get_page_PhantomJSAPI($fitem_url, $cookie, $customUA, $use_proxy, $htuser_pass, '');
            if($htmlcontent !== false)
            {
                $got_phantom = true;
            }
        }
        if($got_phantom === false)
        {
            if (!echo_check_if_phantom($use_phantom) || !isset($echo_Main_Settings['disable_fallback']) || $echo_Main_Settings['disable_fallback'] != 'on')
            { 
                $htmlcontent = echo_get_web_page($fitem_url, $customUA, $cookie, $use_proxy, $htuser_pass);
            }
        }
        if($htmlcontent === FALSE)
        {
            echo_log_to_file('Failed to download webpage when using Visual Selector: ' . print_r($fitem_url, true));
            echo 'Failed to download webpage when using Visual Selector: ' . print_r($fitem_url, true);
            exit();
        }
		if ( !preg_match('/<base\s/i', $htmlcontent) ) {
			$base = '<base href="' . $fitem_url . '">';
			$htmlcontent = str_replace('</head>', $base . '</head>', $htmlcontent);
		}
        $htmlcontent = preg_replace('/src="\/\/(.*?)"/', 'src="https://$1"', $htmlcontent);
        $htmlcontent = preg_replace('/href="\/\/(.*?)"/', 'href="https://$1"', $htmlcontent);
		if ( preg_match('!^https?://[^/]+!', $fitem_url, $matches) ) {
			$stem = $matches[0];
			$htmlcontent = preg_replace('!(\s)(src|href)(=")\/!i', "\\1\\2\\3$stem/", $htmlcontent);
			$htmlcontent = preg_replace('!(\s)(url)(\s*\(\s*["\']?)\/!i', "\\1\\2\\3$stem/", $htmlcontent);
		}
        $url_changed_prompt = '';
        if($fitem_url != '')
        {
            $url_changed_prompt = urldecode('%3Cscript%3E') . 'alert("' . esc_html__('Showing URL: ', 'rss-feed-post-generator-echo') . esc_url_raw($fitem_url) . '");' . urldecode('%3C%2Fscript%3E');
        }
		$htmlcontent = preg_replace('{<script[\s\S]*?\/\s?script>}s', '', $htmlcontent);
		echo $htmlcontent . urldecode($started . "tyle%3E%5Bclass~%3Dhighlight%5D%7Bbox-shadow%3Ainset%200%200%200%201000px%20rgba%28255%2C0%2C0%2C.5%29%20%21important%3B%7D%5Bclass~%3Dhighlight%5D%7Boutline%3A.010416667in%20solid%20red%20%21important%3B%7D") . urldecode("%3C%2Fstyle%3E") . $url_changed_prompt;
        die();
}
add_action('wp_ajax_echo_my_action', 'echo_my_action_callback');
function echo_my_action_callback()
{
    $echo_Main_Settings = get_option('echo_Main_Settings', false);
    $failed             = false;
    $del_id             = $_POST['id'];
    $how                = $_POST['how'];
    if($how == 'duplicate')
    {
        $GLOBALS['wp_object_cache']->delete('echo_rules_list', 'options');
        if (!get_option('echo_rules_list')) {
            $rules = array();
        } else {
            $rules = get_option('echo_rules_list');
        }
        if (!empty($rules)) {
            $found            = 0;
            $cont = 0;
            foreach ($rules as $request => $bundle[]) {
                if ($cont == $del_id) {
                    $copy_bundle = $rules[$request];
                    $copy_bundle[37] = uniqid('', true);
                    $rules[] = $copy_bundle;
                    $found   = 1;
                    break;
                }
                $cont = $cont + 1;
            }
            if($found == 0)
            {
                echo_log_to_file('echo_rules_list index not found: ' . $del_id);
                echo 'nochange';
                die();
            }
            else
            {
                update_option('echo_rules_list', $rules, false);
                echo 'ok';
                die();
            }
        } else {
            echo_log_to_file('echo_rules_list empty!');
            echo 'nochange';
            die();
        }
        
    }
    $force_delete       = true;
    $number             = 0;
    if ($how == 'trash') {
        $force_delete = false;
    }
    $postsPerPage = 50000;
    $paged = 0;
    do
    {
        $postOffset = $paged * $postsPerPage;
        $query     = array(
            'post_status' => array(
                'publish',
                'draft',
                'pending',
                'trash',
                'private',
                'future'
            ),
            'post_type' => array(
                'any'
            ),
            'numberposts' => $postsPerPage,
            'fields' => 'ids',
            'meta_key' => 'echo_parent_rule',
            'offset'  => $postOffset
        );
        $post_list = get_posts($query);
        $paged++;
        wp_suspend_cache_addition(true);
        foreach ($post_list as $post) {
            $index = get_post_meta($post, 'echo_parent_rule', true);
            if ($index == $del_id) {
                $args             = array(
                    'post_parent' => $post
                );
                $post_attachments = get_children($args);
                if (isset($post_attachments) && !empty($post_attachments)) {
                    foreach ($post_attachments as $attachment) {
                        wp_delete_attachment($attachment->ID, true);
                    }
                }
                $res = wp_delete_post($post, $force_delete);
                if ($res === false) {
                    $failed = true;
                } else {
                    $number++;
                }
            }
        }
        wp_suspend_cache_addition(false);
    }while(!empty($post_list));
    $post_list = null;
    unset($post_list);
    if ($failed === true) {
        if (isset($echo_Main_Settings['enable_detailed_logging'])) {
            echo_log_to_file('[PostDelete] Failed to delete all posts for rule id: ' . esc_html($del_id));
        }
        echo 'failed';
    } else {
        if (isset($echo_Main_Settings['enable_detailed_logging'])) {
            echo_log_to_file('[PostDelete] Successfuly deleted ' . esc_html($number) . ' posts for rule id: ' . esc_html($del_id));
        }
        if ($number == 0) {
            echo 'nochange';
        } else {
            echo 'ok';
        }
    }
    die();
}
add_action('wp_ajax_echo_run_my_action', 'echo_run_my_action_callback');
function echo_run_my_action_callback()
{
    $run_id = $_POST['id'];
    echo echo_run_rule($run_id, 0);
    die();
}
function echo_truncate_title($title, $max_length = 255) 
{
    if (strlen($title) > $max_length) {
        $title = substr($title, 0, $max_length);
    }
	if (function_exists ( 'iconv' )) 
    {
        return iconv ( 'utf-8', 'utf-8//IGNORE', $title );
    } 
    else 
    {
        return $title;
    }
    return $title;
}
function echo_clearFromList($param)
{
    $GLOBALS['wp_object_cache']->delete('echo_running_list', 'options');
    $running = get_option('echo_running_list');
    if($running !== false)
    {
        $key     = array_search($param, $running);
        if ($key !== FALSE) {
            unset($running[$key]);
            update_option('echo_running_list', $running);
        }
    }
}

function echo_curl_exec_utf8($ch) {
    $data = curl_exec($ch);
    if (!is_string($data))
    {
        echo_log_to_file('Failed to exec curl in echo_curl_exec_utf8! ' . curl_error($ch) . ' - ' . curl_errno($ch) . ' url: ' . curl_getinfo($ch, CURLINFO_EFFECTIVE_URL));
        return $data;
    } 
    unset($charset);
    $content_type = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
    preg_match( '@([\w/+]+)(;\s*charset=(\S+))?@i', $content_type, $matches );
    if ( isset( $matches[3] ) )
        $charset = $matches[3];
    if (!isset($charset)) {
        preg_match( '@<meta\s+http-equiv="Content-Type"\s+content="([\w/]+)(;\s*charset=([^\s"]+))?@i', $data, $matches );
        if ( isset( $matches[3] ) )
            $charset = $matches[3];
    }
    if (!isset($charset)) {
        preg_match( '@<\?xml.+encoding="([^\s"]+)@si', $data, $matches );
        if ( isset( $matches[1] ) )
            $charset = $matches[1];
    }
    if (!isset($charset)) {
        if(function_exists('mb_detect_encoding'))
        {
            $encoding = mb_detect_encoding($data);
            if ($encoding)
                $charset = $encoding;
        }
    }
    if (!isset($charset)) {
        if (strstr($content_type, "text/html") === 0)
            $charset = "ISO 8859-1";
    }
    if (isset($charset) && strtoupper($charset) != "UTF-8")
    {   
        if (function_exists('iconv'))
        {
            $data = iconv($charset, 'UTF-8//IGNORE', $data);
        }
    }
    if($data === false)
    {
        return curl_exec($ch);
    }
    return $data;
}
function echo_isCurl(){
    return function_exists('curl_version');
}

function echo_testPhantom()
{
    if(!function_exists('shell' . '_exec')) {
        return 0;
    }
    $disabled = explode(',', ini_get('disable_functions'));
    if(in_array('shell' . '_exec', $disabled))
    {
        return 0;
    }
    $echo_Main_Settings = get_option('echo_Main_Settings', false);
    if (isset($echo_Main_Settings['phantom_path']) && $echo_Main_Settings['phantom_path'] != '') 
    {
        $phantomjs_comm = $echo_Main_Settings['phantom_path'] . ' ';
    }
    else
    {
        $phantomjs_comm = 'phantomjs ';
    }
    $shefunc = trim(' s ') . trim(' h ') . 'ell' . '_exec';
    $cmdResult = $shefunc($phantomjs_comm . '-h 2>&1');
    if(stristr($cmdResult, 'Usage') !== false)
    {
        return 1;
    }
    return 0;
}

function echo_get_page_Puppeteer($url, $custom_cookies, $custom_user_agent, $use_proxy, $user_pass)
{
    $echo_Main_Settings = get_option('echo_Main_Settings', false);
    if(!function_exists('shell' . '_exec')) {
        if (isset($echo_Main_Settings['enable_detailed_logging'])) {
            echo_log_to_file('shel' . 'l_exec not found!');
        }
        return false;
    }
    $disabled = explode(',', ini_get('disable_functions'));
    if(in_array('shell' . '_exec', $disabled))
    {
        if (isset($echo_Main_Settings['enable_detailed_logging'])) {
            echo_log_to_file('shel' . 'l_exec disabled');
        }
        return false;
    }
    if($custom_user_agent == '')
    {
        $custom_user_agent = 'default';
    }
    if($custom_cookies == '')
    {
        $custom_cookies = 'default';
    }
    if($user_pass == '')
    {
        $user_pass = 'default';
    }
    
    $delay = '';
    if (isset($echo_Main_Settings['request_delay']) && $echo_Main_Settings['request_delay'] != '') 
    {
        if(stristr($echo_Main_Settings['request_delay'], ',') !== false)
        {
            $tempo = explode(',', $echo_Main_Settings['request_delay']);
            if(isset($tempo[1]) && is_numeric(trim($tempo[1])) && is_numeric(trim($tempo[0])))
            {
                $delay = rand(trim($tempo[0]), trim($tempo[1]));
            }
        }
        else
        {
            if(is_numeric(trim($echo_Main_Settings['request_delay'])))
            {
                $delay = intval(trim($echo_Main_Settings['request_delay']));
            }
        }
    }
    if($delay != '' && is_numeric($delay))
    {
        $GLOBALS['wp_object_cache']->delete('echo_last_time', 'options');
        $last_time = get_option('echo_last_time', false);
        if($last_time !== false && intval(((intval($last_time) - time()) * 1000 + $delay ) * 1000) > 0)
        {
            $sleep_time = intval(((intval($last_time) - time()) * 1000 + $delay ) * 1000);
            if (isset($echo_Main_Settings['enable_detailed_logging'])) 
            {
                echo_log_to_file('Delay between requests set, waiting ' . ($sleep_time/1000) . ' ms');
            }
            if($sleep_time < 21600000)
            {
                usleep($sleep_time);
            }
        }
    }
    $phantomjs_proxcomm = '"null"';
    if ($use_proxy == '1' && isset($echo_Main_Settings['proxy_url']) && $echo_Main_Settings['proxy_url'] != '' && $echo_Main_Settings['proxy_url'] != 'disable' && $echo_Main_Settings['proxy_url'] != 'disabled') 
    {
        $prx = explode(',', $echo_Main_Settings['proxy_url']);
        $randomness = array_rand($prx);
        $phantomjs_proxcomm = '"' . trim($prx[$randomness]);
        if (isset($echo_Main_Settings['proxy_auth']) && $echo_Main_Settings['proxy_auth'] != '') 
        {
            $prx_auth = explode(',', $echo_Main_Settings['proxy_auth']);
            if(isset($prx_auth[$randomness]) && trim($prx_auth[$randomness]) != '')
            {
                $phantomjs_proxcomm .= '~~~' . trim($prx_auth[$randomness]);
            }
        }
        $phantomjs_proxcomm .= '"';
    }
    
    $puppeteer_comm = 'node ';
    $puppeteer_comm .= '"' . dirname(__FILE__) . '/res/puppeteer/puppeteer.js" "' . $url . '" ' . $phantomjs_proxcomm . '  "' . $custom_user_agent . '" "' . $custom_cookies . '" "' . $user_pass . '"';
    $puppeteer_comm .= ' 2>&1';
    if (isset($echo_Main_Settings['enable_detailed_logging'])) {
        echo_log_to_file('Puppeteer command: ' . $puppeteer_comm);
    }
    $shefunc = trim(' s ') . trim(' h ') . 'ell' . '_exec';
    $cmdResult = $shefunc($puppeteer_comm);
    if($delay != '' && is_numeric($delay))
    {
        update_option('echo_last_time', time());
    }
    if($cmdResult === NULL || $cmdResult == '')
    {
        echo_log_to_file('puppeteer did not return usable info for: ' . $url);
        return false;
    }
    if(trim($cmdResult) === 'timeout')
    {
        echo_log_to_file('puppeteer timed out while getting page: ' . $url. ' - please increase timeout in Main Settings');
        return false;
    }
    if(stristr($cmdResult, 'sh: node: command not found') !== false || stristr($cmdResult, 'throw err;') !== false)
    {
        echo_log_to_file('nodeJS not found, please install it on your server');
        return false;
    }
    if(stristr($cmdResult, 'sh: puppeteer: command not found') !== false)
    {
        echo_log_to_file('puppeteer not found, please install it on your server');
        return false;
    }
    if(stristr($cmdResult, 'Error: Cannot find module \'puppeteer\'') !== false)
    {
        echo_log_to_file('puppeteer module not found, please install it on your server');
        return false;
    }
    if(stristr($cmdResult, 'sh: node: command not found') !== false)
    {
        echo_log_to_file('puppeteer module not found, please install it on your server!');
        return false;
    }
    if(stristr($cmdResult, 'res/puppeteer/puppeteer.js:') !== false)
    {
        echo_log_to_file('puppeteer failed to run, error: ' . $cmdResult);
        return false;
    }
    return $cmdResult;
}
function echo_get_page_PhantomJS($url, $custom_cookies, $custom_user_agent, $use_proxy)
{
    if(!function_exists('shell' . '_exec')) {
        return false;
    }
    if(empty($url))
    {
        return false;
    }
    $disabled = explode(',', ini_get('disable_functions'));
    if(in_array('shell' . '_exec', $disabled))
    {
        return false;
    }
    $echo_Main_Settings = get_option('echo_Main_Settings', false);
    
    $delay = '';
    if (isset($echo_Main_Settings['request_delay']) && $echo_Main_Settings['request_delay'] != '') 
    {
        if(stristr($echo_Main_Settings['request_delay'], ',') !== false)
        {
            $tempo = explode(',', $echo_Main_Settings['request_delay']);
            if(isset($tempo[1]) && is_numeric(trim($tempo[1])) && is_numeric(trim($tempo[0])))
            {
                $delay = rand(trim($tempo[0]), trim($tempo[1]));
            }
        }
        else
        {
            if(is_numeric(trim($echo_Main_Settings['request_delay'])))
            {
                $delay = intval(trim($echo_Main_Settings['request_delay']));
            }
        }
    }
    if($delay != '' && is_numeric($delay))
    {
        $GLOBALS['wp_object_cache']->delete('echo_last_time', 'options');
        $last_time = get_option('echo_last_time', false);
        if($last_time !== false && intval(((intval($last_time) - time()) * 1000 + $delay ) * 1000) > 0)
        {
            $sleep_time = intval(((intval($last_time) - time()) * 1000 + $delay ) * 1000);
            if (isset($echo_Main_Settings['enable_detailed_logging'])) 
            {
                echo_log_to_file('Delay between requests set, waiting ' . ($sleep_time/1000) . ' ms');
            }
            if($sleep_time < 21600000)
            {
                usleep($sleep_time);
            }
        }
    }
    if (isset($echo_Main_Settings['phantom_path']) && $echo_Main_Settings['phantom_path'] != '') 
    {
        $phantomjs_comm = $echo_Main_Settings['phantom_path'];
    }
    else
    {
        $phantomjs_comm = 'phantomjs';
    }
    if (isset($echo_Main_Settings['phantom_timeout']) && $echo_Main_Settings['phantom_timeout'] != '')
    {
        $phantomjs_timeout = ((int)$echo_Main_Settings['phantom_timeout']);
    }
    else
    {
        $phantomjs_timeout = '15000';
    }
    if($custom_user_agent == '')
    {
        $custom_user_agent = 'default';
    }
    if($custom_cookies == '')
    {
        $custom_cookies = 'default';
    }
    if($use_proxy && isset($echo_Main_Settings['proxy_url']) && $echo_Main_Settings['proxy_url'] != '' && $echo_Main_Settings['proxy_url'] != 'disable' && $echo_Main_Settings['proxy_url'] != 'disabled') 
    {
        $prx = explode(',', $echo_Main_Settings['proxy_url']);
        $randomness = array_rand($prx);
        $phantomjs_comm .= ' --proxy=' . trim($prx[$randomness]) . ' ';
        if (isset($echo_Main_Settings['proxy_auth']) && $echo_Main_Settings['proxy_auth'] != '') 
        {
            $prx_auth = explode(',', $echo_Main_Settings['proxy_auth']);
            if(isset($prx_auth[$randomness]) && trim($prx_auth[$randomness]) != '')
            {
                $phantomjs_comm .= '--proxy-auth=' . trim($prx_auth[$randomness]) . ' ';
            }
        }
    }
    $phantomjs_comm .= ' --ignore-ssl-errors=true ';
    $phantomjs_comm .= '"' . dirname(__FILE__) . '/res/phantomjs/phantom.js" "' . $url . '" "' . $phantomjs_timeout . '" "' . $custom_user_agent . '" "' . $custom_cookies . '"';
    $phantomjs_comm .= ' 2>&1';
    $shefunc = trim(' s ') . trim(' h ') . 'ell' . '_exec';
    $cmdResult = $shefunc($phantomjs_comm); 
    if($delay != '' && is_numeric($delay))
    {
        update_option('echo_last_time', time());
    }
    if($cmdResult === NULL || $cmdResult == '')
    {
        return false;
    }
    if(trim($cmdResult) === 'timeout')
    {
        echo_log_to_file('phantomjs timed out while getting page: ' . $url. ' - please increase timeout in Main Settings');
        return false;
    }
    if(stristr($cmdResult, 'sh: phantomjs: command not found') !== false)
    {
        echo_log_to_file('phantomjs not found, please install it on your server');
        return false;
    }
    return $cmdResult;
}

function echo_get_web_page($url, $user_agent_cust = '', $custom_cookie = '', $use_proxy = 0, $htuser_pass = '')
{
    if(empty($url))
    {
        return false;
    }
    $echo_Main_Settings = get_option('echo_Main_Settings', false);
    if($user_agent_cust == '')
    {
        $user_agent = echo_get_random_user_agent();
    }
    else
    {
        $user_agent = $user_agent_cust;
    }
    $delay = '';
    if (isset($echo_Main_Settings['request_delay']) && $echo_Main_Settings['request_delay'] != '') 
    {
        if(stristr($echo_Main_Settings['request_delay'], ',') !== false)
        {
            $tempo = explode(',', $echo_Main_Settings['request_delay']);
            if(isset($tempo[1]) && is_numeric(trim($tempo[1])) && is_numeric(trim($tempo[0])))
            {
                $delay = rand(trim($tempo[0]), trim($tempo[1]));
            }
        }
        else
        {
            if(is_numeric(trim($echo_Main_Settings['request_delay'])))
            {
                $delay = intval(trim($echo_Main_Settings['request_delay']));
            }
        }
    }
    if($delay != '' && is_numeric($delay))
    {
        $GLOBALS['wp_object_cache']->delete('echo_last_time', 'options');
        $last_time = get_option('echo_last_time', false);
        if($last_time !== false && intval(((intval($last_time) - time()) * 1000 + $delay ) * 1000) > 0)
        {
            $sleep_time = intval(((intval($last_time) - time()) * 1000 + $delay ) * 1000);
            if (isset($echo_Main_Settings['enable_detailed_logging'])) 
            {
                echo_log_to_file('Delay between requests set, waiting ' . ($sleep_time/1000) . ' ms');
            }
            if($sleep_time < 21600000)
            {
                usleep($sleep_time);
            }
        }
    }
    $content = false;
    if ($use_proxy && (!isset($echo_Main_Settings['proxy_url']) || $echo_Main_Settings['proxy_url'] == '') || $echo_Main_Settings['proxy_url'] == 'disable' || $echo_Main_Settings['proxy_url'] == 'disabled') {
        $ckc = array();
        if($custom_cookie != '')
        {
            if(class_exists('WP_Http_Cookie'))
            {
                if(!function_exists('http_parse_cookie')){
                    function http_parse_cookie($szHeader, $object = true){
                        $obj		 = new stdClass;
                        $arrCookie	 = array();
                        $arrObj		 = array();
                        $arrCookie =  explode("\n", $szHeader);
                        for($i = 0; $i<count($arrCookie); $i++){
                            $cookie			 = $arrCookie[$i];
                            $attributes		 = explode(';', $cookie);
                            $arrCookie[$i]	 = array();
                            foreach($attributes as $attrEl){
                                $tmp = explode('=', $attrEl, 2);
                                if(count($tmp)<2){
                                    continue;
                                }
                                $key	 = trim($tmp[0]);
                                $value	 = trim($tmp[1]);
                                if($key=='version'||$key=='path'||$key=='expires'||$key=='domain'||$key=='comment'){
                                    if(!isset($arrObj[$key])){
                                        $arrObj[$key] = $value;
                                    }
                                }else{
                                    $arrObj['cookies'][$key] = $value;
                                }
                            }
                        }
                        if($object===true){
                            $obj	 = (object)$arrObj;
                            $return	 = $obj;
                        }else{
                            $return = $arrObj;
                        }
                        return $return;
                    }
                }
                $CP = http_parse_cookie($custom_cookie);
                if(isset($CP->cookies))
                {
                    foreach ( $CP->cookies as $xname => $xcookie ) {
                        $ckc[] = new WP_Http_Cookie( array( 'name' => $xname, 'value' => $xcookie ) );
                    }
                }
            }
        }
        $headersx = array(); 
        if($htuser_pass != '')
        {
            $har = explode(':', $htuser_pass);
            if(isset($har[1]))
            {
                $headersx = array('Authorization' => 'Basic ' . base64_encode( $htuser_pass ));
            }
        }
        $args = array(
           'timeout'     => 30,
           'redirection' => 10,
           'user-agent'  => $user_agent,
           'blocking'    => true,
           'headers'     => $headersx,
           'cookies'     => $ckc,
           'body'        => null,
           'compress'    => false,
           'decompress'  => true,
           'sslverify'   => false,
           'stream'      => false,
           'filename'    => null
        );
        $ret_data            = wp_remote_get(html_entity_decode($url), $args);  
        $response_code       = wp_remote_retrieve_response_code( $ret_data );
        $response_message    = wp_remote_retrieve_response_message( $ret_data );   
        if($delay != '' && is_numeric($delay))
        {
            update_option('echo_last_time', time());
        }     
        if ( 200 != $response_code ) {
        } else {
            $content = wp_remote_retrieve_body( $ret_data );
        }
    }
    if($content === false)
    {
        if(echo_isCurl() && filter_var($url, FILTER_VALIDATE_URL))
        {
            if (isset($echo_Main_Settings['echo_clear_curl_charset']) && $echo_Main_Settings['echo_clear_curl_charset'] == 'on') {
                $options    = array(
                    CURLOPT_CUSTOMREQUEST => "GET",
                    CURLOPT_COOKIEJAR => get_temp_dir() . 'echocookie.txt',
                    CURLOPT_COOKIEFILE => get_temp_dir() . 'echocookie.txt',
                    CURLOPT_POST => false,
                    CURLOPT_USERAGENT => $user_agent,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_AUTOREFERER => true,
                    CURLOPT_CONNECTTIMEOUT => 10,
                    CURLOPT_TIMEOUT => 20,
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_ENCODING => '',
                    CURLOPT_REFERER => 'https://www.' . 'google.' . 'com/',
                    CURLOPT_SSL_VERIFYHOST => false,
                    CURLOPT_SSL_VERIFYPEER => false
                );
            }
            else
            {
                $options    = array(
                    CURLOPT_CUSTOMREQUEST => "GET",
                    CURLOPT_COOKIEJAR => get_temp_dir() . 'echocookie.txt',
                    CURLOPT_COOKIEFILE => get_temp_dir() . 'echocookie.txt',
                    CURLOPT_POST => false,
                    CURLOPT_USERAGENT => $user_agent,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_AUTOREFERER => true,
                    CURLOPT_CONNECTTIMEOUT => 10,
                    CURLOPT_TIMEOUT => 20,
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_REFERER => 'https://www.' . 'google.' . 'com/',
                    CURLOPT_SSL_VERIFYHOST => false,
                    CURLOPT_SSL_VERIFYPEER => false
                );
            }
            if ($use_proxy && isset($echo_Main_Settings['proxy_url']) && $echo_Main_Settings['proxy_url'] != '' && $echo_Main_Settings['proxy_url'] != 'disable' && $echo_Main_Settings['proxy_url'] != 'disabled') {
                $prx = explode(',', $echo_Main_Settings['proxy_url']);
                $randomness = array_rand($prx);
                $options[CURLOPT_PROXY] = trim($prx[$randomness]);
                if (isset($echo_Main_Settings['proxy_auth']) && $echo_Main_Settings['proxy_auth'] != '') 
                {
                    $prx_auth = explode(',', $echo_Main_Settings['proxy_auth']);
                    if(isset($prx_auth[$randomness]) && trim($prx_auth[$randomness]) != '')
                    {
                        $options[CURLOPT_PROXYUSERPWD] = trim($prx_auth[$randomness]);
                    }
                }
            }
            $ch = curl_init($url);
            if($ch === FALSE)
            {
                return false;
            }
            if($htuser_pass != '')
            {
                $har = explode(':', $htuser_pass);
                if(isset($har[1]))
                {
                    curl_setopt($ch, CURLOPT_USERPWD, $htuser_pass);
                }
            }
            if($custom_cookie != '')
            {
                $headers   = array();
                $headers[] = 'Cookie: ' . $custom_cookie;
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                curl_setopt($ch, CURLOPT_COOKIE , $custom_cookie);
                unset($options[CURLOPT_COOKIEJAR]);
                unset($options[CURLOPT_COOKIEFILE]);
            }
            curl_setopt_array($ch, $options);
            
            $content = echo_curl_exec_utf8($ch);
            if($delay != '' && is_numeric($delay))
            {
                update_option('echo_last_time', time());
            }
            curl_close($ch);
        }
        else
        {
            $allowUrlFopen = preg_match('/1|yes|on|true/i', ini_get('allow_url_fopen'));
            if ($allowUrlFopen) {
                global $wp_filesystem;
                if ( ! is_a( $wp_filesystem, 'WP_Filesystem_Base') ){
                    include_once(ABSPATH . 'wp-admin/includes/file.php');$creds = request_filesystem_credentials( site_url() );
                    wp_filesystem($creds);
                }
                if($delay != '' && is_numeric($delay))
                {
                    update_option('echo_last_time', time());
                }
                error_reporting(0);
                $retme = $wp_filesystem->get_contents($url);
                error_reporting(E_ALL);
                return $retme;
            }
        }
    }
    if($content === false && echo_isCurl())
    {
        if (isset($echo_Main_Settings['search_google']) && $echo_Main_Settings['search_google'] == 'on') {
            $google_url =  "http://webcache.googleusercontent.com/search?q=cache:".urlencode($url);
            $ch2 = curl_init($google_url);
            if ($ch2 === FALSE) {
                return FALSE;
            }
            curl_setopt_array($ch2, $options);
            curl_setopt($ch2, CURLOPT_CONNECTTIMEOUT, 10);
            curl_setopt($ch2, CURLOPT_TIMEOUT, 10);
            $content = curl_exec($ch2);
            if($delay != '' && is_numeric($delay))
            {
                update_option('echo_last_time', time());
            }
            if($content === false || empty($content) || (stristr($content, 'was not found on this server.') !== false && stristr($content, 'Error 404 (Not Found)!!1') !== false))
            {
                require_once (dirname(__FILE__) . "/res/GoogleTranslateProxy.php"); 
                try 
                {
                    $GoogleTranslateProxy = new RSSGoogleTranslateProxy($ch2);
                    $content = $GoogleTranslateProxy->fetch($url);
                    curl_close($ch2);
                    return $content;
                } 
                catch (Exception $e) 
                {
                    echo_log_to_file('Google Proxy failed: ' . $e->getMessage());
                }
            }
            curl_close($ch2);
        }
    }
    return $content;
}

function echo_get_screenshot_PuppeteerAPI($url, $custom_cookies, $custom_user_agent, $use_proxy, $user_pass, $timeout = '', $request_delay = '')
{
    $echo_Main_Settings = get_option('echo_Main_Settings', false);
    if (!isset($echo_Main_Settings['headlessbrowserapi_key']) || trim($echo_Main_Settings['headlessbrowserapi_key']) == '')
    {
        echo_log_to_file('You need to add your HeadlessBrowserAPI key in the plugin\'s \'Main Settings\' before you can use this feature.');
        return false;
    }
    $delay = '';
    if (isset($echo_Main_Settings['request_delay']) && $echo_Main_Settings['request_delay'] != '') {
        if(stristr($echo_Main_Settings['request_delay'], ',') !== false)
        {
            $tempo = explode(',', $echo_Main_Settings['request_delay']);
            if(isset($tempo[1]) && is_numeric(trim($tempo[1])) && is_numeric(trim($tempo[0])))
            {
                $delay = rand(trim($tempo[0]), trim($tempo[1]));
            }
        }
        else
        {
            if(is_numeric(trim($echo_Main_Settings['request_delay'])))
            {
                $delay = intval(trim($echo_Main_Settings['request_delay']));
            }
        }
    }
    if ($request_delay != '') 
    {
        if(stristr($request_delay, ',') !== false)
        {
            $tempo = explode(',', $request_delay);
            if(isset($tempo[1]) && is_numeric(trim($tempo[1])) && is_numeric(trim($tempo[0])))
            {
                $delay = rand(trim($tempo[0]), trim($tempo[1]));
            }
        }
        else
        {
            if(is_numeric(trim($request_delay)))
            {
                $delay = intval(trim($request_delay));
            }
        }
    }
    if($delay != '' && is_numeric($delay))
    {
        $GLOBALS['wp_object_cache']->delete('echo_last_time', 'options');
        $last_time = get_option('echo_last_time', false);
        if($last_time !== false && intval(((intval($last_time) - time()) * 1000 + $delay ) * 1000) > 0)
        {
            $sleep_time = intval(((intval($last_time) - time()) * 1000 + $delay ) * 1000);
            if (isset($echo_Main_Settings['enable_detailed_logging'])) 
            {
                echo_log_to_file('Delay between requests set, waiting ' . ($sleep_time/1000) . ' ms');
            }
            if($sleep_time < 21600000)
            {
                usleep($sleep_time);
            }
        }
    }
    if($custom_user_agent == '')
    {
        $custom_user_agent = 'default';
    }
    if($custom_cookies == '')
    {
        $custom_cookies = 'default';
    }
    if($user_pass == '')
    {
        $user_pass = 'default';
    }
    if (isset($echo_Main_Settings['phantom_timeout']) && $echo_Main_Settings['phantom_timeout'] != '') 
    {
        $phantomjs_timeout = ((int)$echo_Main_Settings['phantom_timeout']);
    }
    else
    {
        $phantomjs_timeout = 'default';
    }
    $phantomjs_proxcomm = '"null"';
    if ($use_proxy == '1' && isset($echo_Main_Settings['proxy_url']) && $echo_Main_Settings['proxy_url'] != '') 
    {
        $proxy_url = $echo_Main_Settings['proxy_url'];
        if(isset($echo_Main_Settings['proxy_auth']) && $echo_Main_Settings['proxy_auth'] != '')
        {
            $proxy_auth = $echo_Main_Settings['proxy_auth'];
        }
        else
        {
            $proxy_auth = 'default';
        }
    }
    else
    {
        $proxy_url = 'default';
        $proxy_auth = 'default';
    }
    
    $za_api_url = 'https://headlessbrowserapi.com/apis/scrape/v1/screenshot?apikey=' . trim($echo_Main_Settings['headlessbrowserapi_key']) . '&url=' . urlencode($url) . '&custom_user_agent=' . urlencode($custom_user_agent) . '&custom_cookies=' . urlencode($custom_cookies) . '&user_pass=' . urlencode($user_pass) . '&timeout=' . urlencode($phantomjs_timeout) . '&proxy_url=' . urlencode($proxy_url) . '&proxy_auth=' . urlencode($proxy_auth);
    $api_timeout = 120;
    $args = array(
       'timeout'     => $api_timeout,
       'redirection' => 10,
       'blocking'    => true,
       'compress'    => false,
       'decompress'  => true,
       'sslverify'   => false,
       'stream'      => false
    );
    $ret_data = wp_remote_get($za_api_url, $args);
    $response_code       = wp_remote_retrieve_response_code( $ret_data );
    $response_message    = wp_remote_retrieve_response_message( $ret_data );   
    if($delay != '' && is_numeric($delay))
    {
        update_option('echo_last_time', time());
    } 
    if ( 200 != $response_code ) {
        if (isset($echo_Main_Settings['enable_detailed_logging'])) 
        {
            echo_log_to_file('Failed to get response from HeadlessBrowserAPI: ' . $za_api_url . ' code: ' . $response_code . ' message: ' . $response_message);
            if(isset($ret_data->errors['http_request_failed']))
            {
                foreach($ret_data->errors['http_request_failed'] as $errx)
                {
                    echo_log_to_file('Error message: ' . html_entity_decode($errx));
                }
            }
        }
        return false;
    } else {
        $cmdResult = wp_remote_retrieve_body( $ret_data );
    }
    if(strstr($cmdResult, '"error"') !== false)
    {
        echo_log_to_file('Failed to decode response from HeadlessBrowserAPI: ' . $za_api_url . ' - ' . print_r($cmdResult, true));
        return false;
    }
    return $cmdResult;
}
function echo_get_page_PuppeteerAPI($url, $custom_cookies, $custom_user_agent, $use_proxy, $user_pass, $timeout = '')
{
    $echo_Main_Settings = get_option('echo_Main_Settings', false);
    if (!isset($echo_Main_Settings['headlessbrowserapi_key']) || trim($echo_Main_Settings['headlessbrowserapi_key']) == '')
    {
        echo_log_to_file('You need to add your HeadlessBrowserAPI key in the plugin\'s \'Main Settings\' before you can use this feature.');
        return false;
    }
    if($custom_user_agent == '')
    {
        $custom_user_agent = 'default';
    }
    if($custom_cookies == '')
    {
        $custom_cookies = 'default';
    }
    if($user_pass == '')
    {
        $user_pass = 'default';
    }
    if($timeout != '')
    {
        $phantomjs_timeout = $timeout;
    }
    else
    {
        if (isset($echo_Main_Settings['phantom_timeout']) && $echo_Main_Settings['phantom_timeout'] != '') 
        {
            $phantomjs_timeout = ((int)$echo_Main_Settings['phantom_timeout']);
        }
        else
        {
            $phantomjs_timeout = 'default';
        }
    }
    
    $delay = '';
    if (isset($echo_Main_Settings['request_delay']) && $echo_Main_Settings['request_delay'] != '') 
    {
        if(stristr($echo_Main_Settings['request_delay'], ',') !== false)
        {
            $tempo = explode(',', $echo_Main_Settings['request_delay']);
            if(isset($tempo[1]) && is_numeric(trim($tempo[1])) && is_numeric(trim($tempo[0])))
            {
                $delay = rand(trim($tempo[0]), trim($tempo[1]));
            }
        }
        else
        {
            if(is_numeric(trim($echo_Main_Settings['request_delay'])))
            {
                $delay = intval(trim($echo_Main_Settings['request_delay']));
            }
        }
    }
    if($delay != '' && is_numeric($delay))
    {
        $GLOBALS['wp_object_cache']->delete('echo_last_time', 'options');
        $last_time = get_option('echo_last_time', false);
        if($last_time !== false && intval(((intval($last_time) - time()) * 1000 + $delay ) * 1000) > 0)
        {
            $sleep_time = intval(((intval($last_time) - time()) * 1000 + $delay ) * 1000);
            if (isset($echo_Main_Settings['enable_detailed_logging'])) 
            {
                echo_log_to_file('Delay between requests set, waiting ' . ($sleep_time/1000) . ' ms');
            }
            if($sleep_time < 21600000)
            {
                usleep($sleep_time);
            }
        }
    }
    $phantomjs_proxcomm = '"null"';
    if ($use_proxy == '1' && isset($echo_Main_Settings['proxy_url']) && $echo_Main_Settings['proxy_url'] != '') 
    {
        $proxy_url = $echo_Main_Settings['proxy_url'];
        if(isset($echo_Main_Settings['proxy_auth']) && $echo_Main_Settings['proxy_auth'] != '')
        {
            $proxy_auth = $echo_Main_Settings['proxy_auth'];
        }
        else
        {
            $proxy_auth = 'default';
        }
    }
    else
    {
        $proxy_url = 'default';
        $proxy_auth = 'default';
    }
    
    $za_api_url = 'https://headlessbrowserapi.com/apis/scrape/v1/puppeteer?apikey=' . trim($echo_Main_Settings['headlessbrowserapi_key']) . '&url=' . urlencode($url) . '&custom_user_agent=' . urlencode($custom_user_agent) . '&custom_cookies=' . urlencode($custom_cookies) . '&user_pass=' . urlencode($user_pass) . '&timeout=' . urlencode($phantomjs_timeout) . '&proxy_url=' . urlencode($proxy_url) . '&proxy_auth=' . urlencode($proxy_auth);
    $api_timeout = 120;
    $args = array(
       'timeout'     => $api_timeout,
       'redirection' => 10,
       'blocking'    => true,
       'compress'    => false,
       'decompress'  => true,
       'sslverify'   => false,
       'stream'      => false
    );
    $ret_data = wp_remote_get($za_api_url, $args);
    $response_code       = wp_remote_retrieve_response_code( $ret_data );
    $response_message    = wp_remote_retrieve_response_message( $ret_data );  
    if($delay != '' && is_numeric($delay))
    {
        update_option('echo_last_time', time());
    }  
    if ( 200 != $response_code ) {
        if (isset($echo_Main_Settings['enable_detailed_logging'])) 
        {
            echo_log_to_file('Failed to get response from HeadlessBrowserAPI: ' . $za_api_url . ' code: ' . $response_code . ' message: ' . $response_message);
            if(isset($ret_data->errors['http_request_failed']))
            {
                foreach($ret_data->errors['http_request_failed'] as $errx)
                {
                    echo_log_to_file('Error message: ' . html_entity_decode($errx));
                }
            }
        }
        return false;
    } else {
        $cmdResult = wp_remote_retrieve_body( $ret_data );
    }
    $jcmdResult = json_decode($cmdResult, true);
    if($jcmdResult === false)
    {
        echo_log_to_file('Failed to decode response from HeadlessBrowserAPI: ' . $za_api_url . ' - ' . print_r($cmdResult, true));
        return false;
    }
    $cmdResult = $jcmdResult;
    if(isset($cmdResult['apicalls']))
    {
        update_option('headless_calls', esc_html($cmdResult['apicalls']));
    }
    if(isset($cmdResult['error']))
    {
        echo_log_to_file('An error occurred while getting content from HeadlessBrowserAPI: ' . $za_api_url . ' - ' . print_r($cmdResult['error'], true));
        return false;
    }
    if(!isset($cmdResult['html']))
    {
        echo_log_to_file('Malformed data imported from HeadlessBrowserAPI: ' . $za_api_url . ' - ' . print_r($cmdResult, true));
        return false;
    }
    return '<html><body>' . $cmdResult['html'] . '</body></html>';
}
function echo_get_page_TorAPI($url, $custom_cookies, $custom_user_agent, $use_proxy, $user_pass, $timeout = '')
{
    $echo_Main_Settings = get_option('echo_Main_Settings', false);
    if (!isset($echo_Main_Settings['headlessbrowserapi_key']) || trim($echo_Main_Settings['headlessbrowserapi_key']) == '')
    {
        echo_log_to_file('You need to add your HeadlessBrowserAPI key in the plugin\'s \'Main Settings\' before you can use this feature.');
        return false;
    }
    if($custom_user_agent == '')
    {
        $custom_user_agent = 'default';
    }
    if($custom_cookies == '')
    {
        $custom_cookies = 'default';
    }
    if($user_pass == '')
    {
        $user_pass = 'default';
    }
    if($timeout != '')
    {
        $phantomjs_timeout = $timeout;
    }
    else
    {
        if (isset($echo_Main_Settings['phantom_timeout']) && $echo_Main_Settings['phantom_timeout'] != '') 
        {
            $phantomjs_timeout = ((int)$echo_Main_Settings['phantom_timeout']);
        }
        else
        {
            $phantomjs_timeout = 'default';
        }
    }
    $delay = '';
    if (isset($echo_Main_Settings['request_delay']) && $echo_Main_Settings['request_delay'] != '') 
    {
        if(stristr($echo_Main_Settings['request_delay'], ',') !== false)
        {
            $tempo = explode(',', $echo_Main_Settings['request_delay']);
            if(isset($tempo[1]) && is_numeric(trim($tempo[1])) && is_numeric(trim($tempo[0])))
            {
                $delay = rand(trim($tempo[0]), trim($tempo[1]));
            }
        }
        else
        {
            if(is_numeric(trim($echo_Main_Settings['request_delay'])))
            {
                $delay = intval(trim($echo_Main_Settings['request_delay']));
            }
        }
    }
    if($delay != '' && is_numeric($delay))
    {
        $GLOBALS['wp_object_cache']->delete('echo_last_time', 'options');
        $last_time = get_option('echo_last_time', false);
        if($last_time !== false && intval(((intval($last_time) - time()) * 1000 + $delay ) * 1000) > 0)
        {
            $sleep_time = intval(((intval($last_time) - time()) * 1000 + $delay ) * 1000);
            if (isset($echo_Main_Settings['enable_detailed_logging'])) 
            {
                echo_log_to_file('Delay between requests set, waiting ' . ($sleep_time/1000) . ' ms');
            }
            if($sleep_time < 21600000)
            {
                usleep($sleep_time);
            }
        }
    }
    $phantomjs_proxcomm = '"null"';
    if ($use_proxy == '1' && isset($echo_Main_Settings['proxy_url']) && $echo_Main_Settings['proxy_url'] != '') 
    {
        $proxy_url = $echo_Main_Settings['proxy_url'];
        if(isset($echo_Main_Settings['proxy_auth']) && $echo_Main_Settings['proxy_auth'] != '')
        {
            $proxy_auth = $echo_Main_Settings['proxy_auth'];
        }
        else
        {
            $proxy_auth = 'default';
        }
    }
    else
    {
        $proxy_url = 'default';
        $proxy_auth = 'default';
    }
    
    $za_api_url = 'https://headlessbrowserapi.com/apis/scrape/v1/tor?apikey=' . trim($echo_Main_Settings['headlessbrowserapi_key']) . '&url=' . urlencode($url) . '&custom_user_agent=' . urlencode($custom_user_agent) . '&custom_cookies=' . urlencode($custom_cookies) . '&user_pass=' . urlencode($user_pass) . '&timeout=' . urlencode($phantomjs_timeout) . '&proxy_url=' . urlencode($proxy_url) . '&proxy_auth=' . urlencode($proxy_auth);
    $api_timeout = 120;
    $args = array(
       'timeout'     => $api_timeout,
       'redirection' => 10,
       'blocking'    => true,
       'compress'    => false,
       'decompress'  => true,
       'sslverify'   => false,
       'stream'      => false
    );
    $ret_data = wp_remote_get($za_api_url, $args);
    $response_code       = wp_remote_retrieve_response_code( $ret_data );
    $response_message    = wp_remote_retrieve_response_message( $ret_data ); 
    if($delay != '' && is_numeric($delay))
    {
        update_option('echo_last_time', time());
    }   
    if ( 200 != $response_code ) {
        if (isset($echo_Main_Settings['enable_detailed_logging'])) 
        {
            echo_log_to_file('Failed to get response from HeadlessBrowserAPI: ' . $za_api_url . ' code: ' . $response_code . ' message: ' . $response_message);
            if(isset($ret_data->errors['http_request_failed']))
            {
                foreach($ret_data->errors['http_request_failed'] as $errx)
                {
                    echo_log_to_file('Error message: ' . html_entity_decode($errx));
                }
            }
        }
        return false;
    } else {
        $cmdResult = wp_remote_retrieve_body( $ret_data );
    }
    $jcmdResult = json_decode($cmdResult, true);
    if($jcmdResult === false)
    {
        echo_log_to_file('Failed to decode response from HeadlessBrowserAPI: ' . $za_api_url . ' - ' . print_r($cmdResult, true));
        return false;
    }
    $cmdResult = $jcmdResult;
    if(isset($cmdResult['apicalls']))
    {
        update_option('headless_calls', esc_html($cmdResult['apicalls']));
    }
    if(isset($cmdResult['error']))
    {
        echo_log_to_file('An error occurred while getting content from HeadlessBrowserAPI: ' . $za_api_url . ' - ' . print_r($cmdResult['error'], true));
        return false;
    }
    if(!isset($cmdResult['html']))
    {
        echo_log_to_file('Malformed data imported from HeadlessBrowserAPI: ' . $za_api_url . ' - ' . print_r($cmdResult, true));
        return false;
    }
    return '<html><body>' . $cmdResult['html'] . '</body></html>';
}
function echo_get_page_PhantomJSAPI($url, $custom_cookies, $custom_user_agent, $use_proxy, $user_pass, $timeout = '')
{
    $echo_Main_Settings = get_option('echo_Main_Settings', false);
    if (!isset($echo_Main_Settings['headlessbrowserapi_key']) || trim($echo_Main_Settings['headlessbrowserapi_key']) == '')
    {
        echo_log_to_file('You need to add your HeadlessBrowserAPI key in the plugin\'s \'Main Settings\' before you can use this feature.');
        return false;
    }
    if($custom_user_agent == '')
    {
        $custom_user_agent = 'default';
    }
    if($custom_cookies == '')
    {
        $custom_cookies = 'default';
    }
    if($user_pass == '')
    {
        $user_pass = 'default';
    }
    if($timeout != '')
    {
        $phantomjs_timeout = $timeout;
    }
    else
    {
        if (isset($echo_Main_Settings['phantom_timeout']) && $echo_Main_Settings['phantom_timeout'] != '') 
        {
            $phantomjs_timeout = ((int)$echo_Main_Settings['phantom_timeout']);
        }
        else
        {
            $phantomjs_timeout = 'default';
        }
    }
    $phantomjs_proxcomm = '"null"';
    if ($use_proxy == '1' && isset($echo_Main_Settings['proxy_url']) && $echo_Main_Settings['proxy_url'] != '') 
    {
        $proxy_url = $echo_Main_Settings['proxy_url'];
        if(isset($echo_Main_Settings['proxy_auth']) && $echo_Main_Settings['proxy_auth'] != '')
        {
            $proxy_auth = $echo_Main_Settings['proxy_auth'];
        }
        else
        {
            $proxy_auth = 'default';
        }
    }
    else
    {
        $proxy_url = 'default';
        $proxy_auth = 'default';
    }
    
    $delay = '';
    if (isset($echo_Main_Settings['request_delay']) && $echo_Main_Settings['request_delay'] != '') 
    {
        if(stristr($echo_Main_Settings['request_delay'], ',') !== false)
        {
            $tempo = explode(',', $echo_Main_Settings['request_delay']);
            if(isset($tempo[1]) && is_numeric(trim($tempo[1])) && is_numeric(trim($tempo[0])))
            {
                $delay = rand(trim($tempo[0]), trim($tempo[1]));
            }
        }
        else
        {
            if(is_numeric(trim($echo_Main_Settings['request_delay'])))
            {
                $delay = intval(trim($echo_Main_Settings['request_delay']));
            }
        }
    }
    if($delay != '' && is_numeric($delay))
    {
        $GLOBALS['wp_object_cache']->delete('echo_last_time', 'options');
        $last_time = get_option('echo_last_time', false);
        if($last_time !== false && intval(((intval($last_time) - time()) * 1000 + $delay ) * 1000) > 0)
        {
            $sleep_time = intval(((intval($last_time) - time()) * 1000 + $delay ) * 1000);
            if (isset($echo_Main_Settings['enable_detailed_logging'])) 
            {
                echo_log_to_file('Delay between requests set, waiting ' . ($sleep_time/1000) . ' ms');
            }
            if($sleep_time < 21600000)
            {
                usleep($sleep_time);
            }
        }
    }
    $za_api_url = 'https://headlessbrowserapi.com/apis/scrape/v1/phantomjs?apikey=' . trim($echo_Main_Settings['headlessbrowserapi_key']) . '&url=' . urlencode($url) . '&custom_user_agent=' . urlencode($custom_user_agent) . '&custom_cookies=' . urlencode($custom_cookies) . '&user_pass=' . urlencode($user_pass) . '&timeout=' . urlencode($phantomjs_timeout) . '&proxy_url=' . urlencode($proxy_url) . '&proxy_auth=' . urlencode($proxy_auth);
    $api_timeout = 120;
    $args = array(
       'timeout'     => $api_timeout,
       'redirection' => 10,
       'blocking'    => true,
       'compress'    => false,
       'decompress'  => true,
       'sslverify'   => false,
       'stream'      => false
    );
    $ret_data = wp_remote_get($za_api_url, $args);
    $response_code       = wp_remote_retrieve_response_code( $ret_data );
    $response_message    = wp_remote_retrieve_response_message( $ret_data );    
    if($delay != '' && is_numeric($delay))
    {
        update_option('echo_last_time', time());
    }
    if ( 200 != $response_code ) {
        if (isset($echo_Main_Settings['enable_detailed_logging'])) 
        {
            echo_log_to_file('Failed to get response from HeadlessBrowserAPI: ' . $za_api_url . ' code: ' . $response_code . ' message: ' . $response_message);
            if(isset($ret_data->errors['http_request_failed']))
            {
                foreach($ret_data->errors['http_request_failed'] as $errx)
                {
                    echo_log_to_file('Error message: ' . html_entity_decode($errx));
                }
            }
        }
        return false;
    } else {
        $cmdResult = wp_remote_retrieve_body( $ret_data );
    }
    $jcmdResult = json_decode($cmdResult, true);
    if($jcmdResult === false)
    {
        echo_log_to_file('Failed to decode response from HeadlessBrowserAPI: ' . $za_api_url . ' - ' . print_r($cmdResult, true));
        return false;
    }
    $cmdResult = $jcmdResult;
    if(isset($cmdResult['apicalls']))
    {
        update_option('headless_calls', esc_html($cmdResult['apicalls']));
    }
    if(isset($cmdResult['error']))
    {
        echo_log_to_file('An error occurred while getting content from HeadlessBrowserAPI: ' . $za_api_url . ' - ' . print_r($cmdResult['error'], true));
        return false;
    }
    if(!isset($cmdResult['html']))
    {
        echo_log_to_file('Malformed data imported from HeadlessBrowserAPI: ' . $za_api_url . ' - ' . print_r($cmdResult, true));
        return false;
    }
    return '<html><body>' . $cmdResult['html'] . '</body></html>';
}
add_action('wp_feed_options', 'echo_force_feed', 10, 2); 
function echo_force_feed($feed, $url) {
    $echo_Main_Settings = get_option('echo_Main_Settings', false);
    if (isset($echo_Main_Settings['echo_enabled']) && $echo_Main_Settings['echo_enabled'] == 'on') {
        if ((isset($echo_Main_Settings['echo_force_feeds']) && $echo_Main_Settings['echo_force_feeds'] == 'on') || (is_string($url) && (strpos($url, 'feeds.bbci.co.uk') !== false))) {
            $feed->force_feed(true);
        }
    }
}

function echo_check_if_video($str)
{
    if(preg_match('/^.*\.(avi|flv|f4v|f4p|f4a|f4b|webm|vob|ogv|ogg|drc|qt|yuv|rm|rmvb|asf|amv|m4p|mpe|mpv|m2v|m4v|svi|3gp|3g2|mxf|roq|nsv|mp4|mov|mpg|mpeg|wmv|mkv)$/i', $str))
    {
        return true;
    }
    else
    {
        return false;
    }
}

function echo_get_featured_image($url, $content, $item, $orig_content, $skip_og, $skip_feed_image, $skip_first_img, $skip_post_content, $use_proxy, $use_phantom, $is_galerts, $feed_url = '', $custom_simple = '', $image_type = '', $image_expre = '', $lazy_tag = '', $html_data = '', $html_dl_failed = false)
{
    $echo_Main_Settings = get_option('echo_Main_Settings', false);
    $biggest_img = '';
    $skip_feed_content_img = false;
    if($image_expre != '' && $image_type != 'auto' && $image_type != '')
    {
        if (isset($echo_Main_Settings['echo_get_image_from_content']) && $echo_Main_Settings['echo_get_image_from_content'] == 'on' && (stripos($url, 'rss.careerjet.ae') === false)) 
        { 
            if($url != '' && $html_dl_failed == false && ($html_data == false || $html_data == ''))
            {
                $html_data = echo_get_web_page($url, '', '', $use_proxy);
                if($html_data === false || $html_data == '')
                {
                    $html_dl_failed = true;
                }
                if($is_galerts == true && $html_data !== false)
                {
                    preg_match('#<meta(?:[^>]*) content="(?:\d+);url=([^"<>]*?)" (?:[^>]*)>#i', $html_data, $galerm);
                    $html_data = false;
                    if(isset($galerm[1]))
                    {
                        $html_data = echo_get_web_page($galerm[1], '', '', $use_proxy);
                        if($html_data === false || $html_data == '')
                        {
                            $html_dl_failed = true;
                        }
                    }
                }   
            }
            if($html_data != false && $html_data != '')
            {
                $biggest_img_text = '';
                if ($image_type == 'regex') {
                    $matches     = array();
                    $rez = preg_match_all($image_expre, $html_data, $matches);
                    if ($rez === FALSE) {
                        if (isset($echo_Main_Settings['enable_detailed_logging'])) {
                            echo_log_to_file('[echo get full content] preg_match_all failed for image_expr: ' . $image_expre);
                        }
                    }
                    if(isset($matches[1][0]))
                    {
                        foreach ($matches[1] as $match) {
                            $biggest_img = $match; 
                            break;
                        }
                    }
                    elseif(isset($matches[0][0]))
                    {
                        foreach ($matches[0] as $match) {
                            $biggest_img_text = $match; 
                            if($biggest_img_text != '')
                            {
                                break;
                            }
                        }
                    }
                } 
                elseif ($image_type == 'xpath' || $image_type == 'visual') 
                {
                    require_once (dirname(__FILE__) . "/res/simple_html_dom.php");
                    $extractok = false;
                    $html_dom_original_html = echo_str_get_html($html_data);
                    if($html_dom_original_html !== false && method_exists($html_dom_original_html, 'find')){
                        $ret = $html_dom_original_html->find( trim($image_expre) );
                        foreach ($ret as $citem ) {
                            $extractok = true;
                            $biggest_img_text = $citem->outertext;
                            if($biggest_img_text != '')
                            {
                                break;
                            }
                        }
                        $html_dom_original_html->clear();
                        unset($html_dom_original_html);
                    }
                    if($extractok == false)
                    {
                        $doc = new DOMDocument;
                        $internalErrors = libxml_use_internal_errors(true);
                        $doc->loadHTML('<?xml encoding="utf-8" ?>' . $html_data);
                        libxml_use_internal_errors($internalErrors);
                        $xpath = new \DOMXpath($doc);
                        $articles = $xpath->query(trim($image_expre));
                        if($articles !== false && $articles->length > 0)
                        {
                            foreach($articles as $container) {
                                if(method_exists($container, 'saveHTML'))
                                {
                                    $biggest_img_text = $container->saveHTML();
                                }
                                elseif(isset($container->ownerDocument) && method_exists($container->ownerDocument, 'saveHTML'))
                                {
                                    $biggest_img_text = $container->ownerDocument->saveHTML($container);
                                }
                                elseif(isset($container->nodeValue))
                                {
                                    $biggest_img_text = $container->nodeValue;
                                }
                                if($biggest_img_text != '')
                                {
                                    break;
                                }
                            }
                        }
                    }
                } else {
                    require_once (dirname(__FILE__) . "/res/simple_html_dom.php");
                    $extractok = false;
                    $html_dom_original_html = echo_str_get_html($html_data);
                    if($html_dom_original_html !== false && method_exists($html_dom_original_html, 'find')){
                        $getnames = explode(',', $image_expre);
                        foreach($getnames as $gname)
                        {
                            $ret = $html_dom_original_html->find('*['.$image_type.'="'.trim($gname).'"]');
                            foreach ($ret as $itema ) {
                                $extractok = true;
                                $biggest_img_text =$itema->outertext;
                                if($biggest_img_text != '')
                                {
                                    break;
                                }
                            }
                        }
                        $html_dom_original_html->clear();
                        unset($html_dom_original_html);
                    }
                    if($extractok == false)
                    {
                        $doc = new DOMDocument;
                        $internalErrors = libxml_use_internal_errors(true);
                        $doc->loadHTML('<?xml encoding="utf-8" ?>' . $html_data);
                        libxml_use_internal_errors($internalErrors);
                        $xpath = new \DOMXpath($doc);
                        $getnames = explode(',', $image_expre);
                        foreach($getnames as $gname)
                        {
                            $articles = $xpath->query('//*[@'.$image_type.'="'.trim($gname).'"]');
                            if($articles !== false && $articles->length > 0)
                            {
                                foreach($articles as $container) {
                                    if(method_exists($container, 'saveHTML'))
                                    {
                                        $biggest_img_text = $container->saveHTML();
                                    }
                                    elseif(isset($container->ownerDocument) && method_exists($container->ownerDocument, 'saveHTML'))
                                    {
                                        $biggest_img_text = $container->ownerDocument->saveHTML($container);
                                    }
                                    elseif(isset($container->nodeValue))
                                    {
                                        $biggest_img_text = $container->nodeValue;
                                    }
                                    if($biggest_img_text != '')
                                    {
                                        break;
                                    }
                                }
                            }
                        }
                    }
                }
                if($biggest_img_text !== '')
                {
                    $tmpdoc = new DOMDocument();
                    $internalErrors = libxml_use_internal_errors(true);
                    $tmpdoc->loadHTML($biggest_img_text);
                    libxml_use_internal_errors($internalErrors);
                    $imageTags = $tmpdoc->getElementsByTagName('img');
                    if(count($imageTags) > 0)
                    {
                        if($imageTags[0] !== null)
                        {
                            if($lazy_tag == '')
                            {
                                $lazy_tag = 'src';
                            }
                            $biggest_img = $imageTags[0]->getAttribute($lazy_tag);
                            if($biggest_img == '' && $lazy_tag != 'src')
                            {
                                $biggest_img = $imageTags[0]->getAttribute('src');
                            }
                            if($biggest_img == '')
                            {
                                preg_match('@src=["\']([^"\']+)["\']@i', $biggest_img_text, $match);
                                if(isset($match[1]) && $match[1] != '')
                                {
                                    $biggest_img = $match[1];
                                }
                            }
                        }
                        else
                        {
                            preg_match('@src=["\']([^"\']+)["\']@i', $biggest_img_text, $match);
                            if(isset($match[1]) && $match[1] != '')
                            {
                                $biggest_img = $match[1];
                            }
                        }
                    }
                }
            }
        }
        if($biggest_img != '')
        {
            if (isset($echo_Main_Settings['echo_featured_image_checking']) && $echo_Main_Settings['echo_featured_image_checking'] == 'on') {
                $url_headers2 = echo_get_url_header($biggest_img);
                if (isset($url_headers2['Content-Type'])) {
                    if (is_array($url_headers2['Content-Type'])) {
                        $img_type2 = strtolower($url_headers2['Content-Type'][0]);
                    } else {
                        $img_type2 = strtolower($url_headers2['Content-Type']);
                    }
                    if (echo_is_valid_img($img_type2, $biggest_img) === TRUE) {
                        return $biggest_img;
                    }
                }
            }
            else
            {
                return $biggest_img;
            }
        }
    }
    if($skip_feed_image != '1')
    {
        if ($custom_simple != '3' && $feed_enclosures = $item->get_enclosures())
        {
			foreach($feed_enclosures as $feed_enclosure)
			{
				$get_img = echo_encodeURI($feed_enclosure->get_thumbnail());
				if($get_img != '')
				{
                    if (isset($echo_Main_Settings['echo_featured_image_checking']) && $echo_Main_Settings['echo_featured_image_checking'] == 'on') {
                        $url_headers2 = echo_get_url_header($get_img);
                        if (isset($url_headers2['Content-Type'])) {
                            if (is_array($url_headers2['Content-Type'])) {
                                $img_type2 = strtolower($url_headers2['Content-Type'][0]);
                            } else {
                                $img_type2 = strtolower($url_headers2['Content-Type']);
                            }
                            if (echo_is_valid_img($img_type2, $get_img) === TRUE) {
                                return $get_img;
                            }
                        }
                    }
                    else
                    {
                        return $get_img;
                    }
				}
                if(empty($get_img))
                {
                    $mimey = $feed_enclosure->get_type();
                    if(empty($mimey))
                    {
                        $mimey = '';
                    }
                    $feed_link = $feed_enclosure->get_link();
                    if(empty($feed_link))
                    {
                        $feed_link = '';
                    }
                    if(stristr($mimey, 'image') !== false || (stristr($feed_link, '.webp') !== false || stristr($feed_link, '.ico') !== false || stristr($feed_link, '.svg') !== false || stristr($feed_link, '.tiff') !== false || stristr($feed_link, '.tif') !== false || stristr($feed_link, '.jpe') !== false || stristr($feed_link, '.jpg') !== false || stristr($feed_link, '.jpeg') !== false || stristr($feed_link, '.png') !== false || stristr($feed_link, '.gif') !== false))
                    {
                        if(!empty($feed_link))
                        {
                            $get_img = echo_encodeURI($feed_link);
                            if($get_img != '')
                            {
                                if (isset($echo_Main_Settings['echo_featured_image_checking']) && $echo_Main_Settings['echo_featured_image_checking'] == 'on') {
                                    $url_headers2 = echo_get_url_header($get_img);
                                    if (isset($url_headers2['Content-Type'])) {
                                        if (is_array($url_headers2['Content-Type'])) {
                                            $img_type2 = strtolower($url_headers2['Content-Type'][0]);
                                        } else {
                                            $img_type2 = strtolower($url_headers2['Content-Type']);
                                        }
                                        if (echo_is_valid_img($img_type2, $get_img) === TRUE) {
                                            return $get_img;
                                        }
                                    }
                                }
                                else
                                {
                                    return $get_img;
                                }
                            }
                        }
                    }
                }
			}
        }
        elseif($custom_simple == '3')
        {
            if(isset($item->enclosure))
            {
                $attributes = $item->enclosure->attributes();
                if(isset($attributes['url']))
                {
                    $get_img = echo_encodeURI($attributes['url']);
                    if($get_img != '')
                    {
                        if (isset($echo_Main_Settings['echo_featured_image_checking']) && $echo_Main_Settings['echo_featured_image_checking'] == 'on') {
                            $url_headers2 = echo_get_url_header($get_img);
                            if (isset($url_headers2['Content-Type'])) {
                                if (is_array($url_headers2['Content-Type'])) {
                                    $img_type2 = strtolower($url_headers2['Content-Type'][0]);
                                } else {
                                    $img_type2 = strtolower($url_headers2['Content-Type']);
                                }
                                if (echo_is_valid_img($img_type2, $get_img) === TRUE) {
                                    return $get_img;
                                }
                            }
                        }
                        else
                        {
                            return $get_img;
                        }
                    }
                }
            }
        }
    }
    if (isset($echo_Main_Settings['echo_get_image_from_content']) && $echo_Main_Settings['echo_get_image_from_content'] == 'on' && (stripos($url, 'rss.careerjet.ae') === false)) 
    { 
        if($skip_og != '1')
        {
            if($url != '' && $html_dl_failed == false && ($html_data == false || $html_data == ''))
            {
                $html_data = echo_get_web_page($url, '', '', $use_proxy);
                if($html_data === false || $html_data == '')
                {
                    $html_dl_failed = true;
                }
                if($is_galerts == true && $html_data !== false)
                {
                    preg_match('#<meta(?:[^>]*) content="(?:\d+);url=([^"<>]*?)" (?:[^>]*)>#i', $html_data, $galerm);
                    if(isset($galerm[1]))
                    {
                        $html_data = echo_get_web_page($galerm[1], '', '', $use_proxy);
                        if($html_data === false || $html_data == '')
                        {
                            $html_dl_failed = true;
                        }
                    }
                }
            }
            if($html_data !== false && $html_data != '')
            {
                preg_match('{<meta[^<]*?property\s*=["|\']og:image(?::secure_url)?["|\'][^<]*?>}i', $html_data, $mathc);
                if(isset($mathc[0]) && stristr($mathc[0], 'og:image')){
                    preg_match('{content=["|\'](.*?)["|\']}s', $mathc[0],$matx);
                    if(isset($matx[1]))
                    {
                        $og_img = $matx[1];
                        if(trim($og_img) !='')
                        {
                            if (isset($echo_Main_Settings['echo_featured_image_checking']) && $echo_Main_Settings['echo_featured_image_checking'] == 'on') {
                                $url_headers2 = echo_get_url_header($og_img);
                                if (isset($url_headers2['Content-Type'])) {
                                    if (is_array($url_headers2['Content-Type'])) {
                                        $img_type2 = strtolower($url_headers2['Content-Type'][0]);
                                    } else {
                                        $img_type2 = strtolower($url_headers2['Content-Type']);
                                    }
                                    if (echo_is_valid_img($img_type2, $og_img) === TRUE) {
                                        return $og_img;
                                    }
                                }
                            }
                            else
                            {
                                return $og_img;
                            }
                        }
                    }
                }
                preg_match('{<meta[^<]*?property\s*=["|\']twitter:image["|\'][^<]*?>}i', $html_data, $mathc);
                if(isset($mathc[0]) && stristr($mathc[0], 'twitter:image')){
                    preg_match('{content=["|\'](.*?)["|\']}s', $mathc[0],$matx);
                    if(isset($matx[1]))
                    {
                        $og_img = $matx[1];
                        if(trim($og_img) !='')
                        {
                            if (isset($echo_Main_Settings['echo_featured_image_checking']) && $echo_Main_Settings['echo_featured_image_checking'] == 'on') {
                                $url_headers2 = echo_get_url_header($og_img);
                                if (isset($url_headers2['Content-Type'])) {
                                    if (is_array($url_headers2['Content-Type'])) {
                                        $img_type2 = strtolower($url_headers2['Content-Type'][0]);
                                    } else {
                                        $img_type2 = strtolower($url_headers2['Content-Type']);
                                    }
                                    if (echo_is_valid_img($img_type2, $og_img) === TRUE) {
                                        return $og_img;
                                    }
                                }
                            }
                            else
                            {
                                return $og_img;
                            }
                        }
                    }
                }
                preg_match('{[\'"]]thumbnailUrl[\'"]\s*:\s*[\'"]([^\'"]+)[\'"]}i', $html_data, $mathc);
                if(isset($mathc[1][0]))
                {
                    $og_img = $mathc[1][0];
                    if(trim($og_img) !='')
                    {
                        if (isset($echo_Main_Settings['echo_featured_image_checking']) && $echo_Main_Settings['echo_featured_image_checking'] == 'on') {
                            $url_headers2 = echo_get_url_header($og_img);
                            if (isset($url_headers2['Content-Type'])) {
                                if (is_array($url_headers2['Content-Type'])) {
                                    $img_type2 = strtolower($url_headers2['Content-Type'][0]);
                                } else {
                                    $img_type2 = strtolower($url_headers2['Content-Type']);
                                }
                                if (echo_is_valid_img($img_type2, $og_img) === TRUE) {
                                    return $og_img;
                                }
                            }
                        }
                        else
                        {
                            return $og_img;
                        }
                    }
                }
                preg_match('{[\'"]@type[\'"]:[\'"]ImageObject[\'"],[\'"]url[\'"]:[\'"]([^\'"]+)[\'"]}i', $html_data, $mathc);
                if(isset($mathc[1][0]))
                {
                    $og_img = $mathc[1][0];
                    if(trim($og_img) !='')
                    {
                        if (isset($echo_Main_Settings['echo_featured_image_checking']) && $echo_Main_Settings['echo_featured_image_checking'] == 'on') {
                            $url_headers2 = echo_get_url_header($og_img);
                            if (isset($url_headers2['Content-Type'])) {
                                if (is_array($url_headers2['Content-Type'])) {
                                    $img_type2 = strtolower($url_headers2['Content-Type'][0]);
                                } else {
                                    $img_type2 = strtolower($url_headers2['Content-Type']);
                                }
                                if (echo_is_valid_img($img_type2, $og_img) === TRUE) {
                                    return $og_img;
                                }
                            }
                        }
                        else
                        {
                            return $og_img;
                        }
                    }
                }
                preg_match('{<meta[^<]*?itemprop\s*=["\']thumbnailUrl["\'][^<]*?>}i', $html_data, $mathc);
                if(isset($mathc[0]) && stristr($mathc[0], 'content=')){
                    preg_match('{content=["|\'](.*?)["|\']}s', $mathc[0],$matx);
                    if(isset($matx[1]))
                    {
                        $og_img = $matx[1];
                        if(trim($og_img) !='')
                        {
                            if (isset($echo_Main_Settings['echo_featured_image_checking']) && $echo_Main_Settings['echo_featured_image_checking'] == 'on') {
                                $url_headers2 = echo_get_url_header($og_img);
                                if (isset($url_headers2['Content-Type'])) {
                                    if (is_array($url_headers2['Content-Type'])) {
                                        $img_type2 = strtolower($url_headers2['Content-Type'][0]);
                                    } else {
                                        $img_type2 = strtolower($url_headers2['Content-Type']);
                                    }
                                    if (echo_is_valid_img($img_type2, $og_img) === TRUE) {
                                        return $og_img;
                                    }
                                }
                            }
                            else
                            {
                                return $og_img;
                            }
                        }
                    }
                }
                preg_match('{<meta[^<]*?name\s*=["\']thumbnail["\'][^<]*?>}i', $html_data, $mathc);
                if(isset($mathc[0]) && stristr($mathc[0], 'content=')){
                    preg_match('{content=["|\'](.*?)["|\']}s', $mathc[0],$matx);
                    if(isset($matx[1]))
                    {
                        $og_img = $matx[1];
                        if(trim($og_img) !='')
                        {
                            if (isset($echo_Main_Settings['echo_featured_image_checking']) && $echo_Main_Settings['echo_featured_image_checking'] == 'on') {
                                $url_headers2 = echo_get_url_header($og_img);
                                if (isset($url_headers2['Content-Type'])) {
                                    if (is_array($url_headers2['Content-Type'])) {
                                        $img_type2 = strtolower($url_headers2['Content-Type'][0]);
                                    } else {
                                        $img_type2 = strtolower($url_headers2['Content-Type']);
                                    }
                                    if (echo_is_valid_img($img_type2, $og_img) === TRUE) {
                                        return $og_img;
                                    }
                                }
                            }
                            else
                            {
                                return $og_img;
                            }
                        }
                    }
                }
                preg_match('{<meta[^<]*?itemprop\s*=["\']image["\'][^<]*?>}i', $html_data, $mathc);
                if(isset($mathc[0]) && stristr($mathc[0], 'content=')){
                    preg_match('{content=["|\'](.*?)["|\']}s', $mathc[0],$matx);
                    if(isset($matx[1]))
                    {
                        $og_img = $matx[1];
                        if(trim($og_img) !='')
                        {
                            if (isset($echo_Main_Settings['echo_featured_image_checking']) && $echo_Main_Settings['echo_featured_image_checking'] == 'on') {
                                $url_headers2 = echo_get_url_header($og_img);
                                if (isset($url_headers2['Content-Type'])) {
                                    if (is_array($url_headers2['Content-Type'])) {
                                        $img_type2 = strtolower($url_headers2['Content-Type'][0]);
                                    } else {
                                        $img_type2 = strtolower($url_headers2['Content-Type']);
                                    }
                                    if (echo_is_valid_img($img_type2, $og_img) === TRUE) {
                                        return $og_img;
                                    }
                                }
                            }
                            else
                            {
                                return $og_img;
                            }
                        }
                    }
                }
            }
        }
    }
    if($skip_post_content != '1' && $content != '')
    {
        if($skip_feed_content_img != true)
        {
            $dom     = new DOMDocument();
            $internalErrors = libxml_use_internal_errors(true);
            $dom->loadHTML($content);
            libxml_use_internal_errors($internalErrors);
            if(stristr($feed_url, 'feedburner') !== false)
            {
                $xpath = new DOMXPath($dom);
                foreach($xpath->query('//div[contains(attribute::class, "feedflare")]') as $e ) {
                    if($e->parentNode !== null)
                    {
                        $e->parentNode->removeChild($e);
                    }
                }
            }
            $tags      = $dom->getElementsByTagName('img');
            foreach ($tags as $tag) {
                $temp_get_img = $tag->getAttribute('src');
                if ($temp_get_img != '' && filter_var($temp_get_img, FILTER_VALIDATE_URL)) {
                    $temp_get_img = strtok($temp_get_img, '?');
                    if (isset($echo_Main_Settings['echo_featured_image_checking']) && $echo_Main_Settings['echo_featured_image_checking'] == 'on') {
                        $url_headers2 = echo_get_url_header($temp_get_img);
                        if (isset($url_headers2['Content-Type'])) {
                            if (is_array($url_headers2['Content-Type'])) {
                                $img_type2 = strtolower($url_headers2['Content-Type'][0]);
                            } else {
                                $img_type2 = strtolower($url_headers2['Content-Type']);
                            }
                            if (echo_is_valid_img($img_type2, $temp_get_img) === TRUE) {
                                return rtrim($temp_get_img, '/');
                            }
                        }
                    }
                    else
                    {
                        return rtrim($temp_get_img, '/');
                    }
                }
            }
            if($orig_content != '')
            {
                $dom2     = new DOMDocument();
                $internalErrors = libxml_use_internal_errors(true);
                $dom2->loadHTML($orig_content);
                        libxml_use_internal_errors($internalErrors);
                $tags = $dom2->getElementsByTagName('img');
                foreach ($tags as $tag) {
                    $temp_get_img = $tag->getAttribute('src');
                    if ($temp_get_img != '') {
                        $temp_get_img = strtok($temp_get_img, '?');
                        if (isset($echo_Main_Settings['echo_featured_image_checking']) && $echo_Main_Settings['echo_featured_image_checking'] == 'on') {
                            $url_headers2 = echo_get_url_header($temp_get_img);
                            if (isset($url_headers2['Content-Type'])) {
                                if (is_array($url_headers2['Content-Type'])) {
                                    $img_type2 = strtolower($url_headers2['Content-Type'][0]);
                                } else {
                                    $img_type2 = strtolower($url_headers2['Content-Type']);
                                }
                                if (echo_is_valid_img($img_type2, $temp_get_img) === TRUE) {
                                    return rtrim($temp_get_img, '/');
                                }
                            }
                        }
                        else
                        {
                            return rtrim($temp_get_img, '/');
                        }
                    }
                }
            }
        }
        $count = 0;
        $biggest_img = '';           
        if (isset($echo_Main_Settings['echo_get_image_from_content']) && $echo_Main_Settings['echo_get_image_from_content'] == 'on' && (stripos($url, 'rss.careerjet.ae') === false)) { 
            if($url != '' && $html_dl_failed == false && ($html_data == false || $html_data == ''))
            {
                $html_data = echo_get_web_page($url, '', '', $use_proxy);
                if($html_data === false || $html_data == '')
                {
                    $html_dl_failed = true;
                }
                if($is_galerts == true && $html_data !== false)
                {
                    preg_match('#<meta(?:[^>]*) content="(?:\d+);url=([^"<>]*?)" (?:[^>]*)>#i', $html_data, $galerm);
                    $html_data = false;
                    if(isset($galerm[1])){
                        $html_data = echo_get_web_page($galerm[1], '', '', $use_proxy);
                        if($html_data === false || $html_data == '')
                        {
                            $html_dl_failed = true;
                        }
                    }
                }
            }
            if($html_data !== false && $html_data != '')
            {
                $doc = new DOMDocument();
                $internalErrors = libxml_use_internal_errors(true);
                $doc->loadHTML($html_data);
                libxml_use_internal_errors($internalErrors);
                $tags    = $doc->getElementsByTagName('img');
                $maxSize = 0;
                foreach ($tags as $tag) {
                    $temp_get_img = $tag->getAttribute('src');
                    if ($temp_get_img != '') {
                        $temp_get_img = strtok($temp_get_img, '?');
                        $temp_get_img   = rtrim($temp_get_img, '/');
                        $image=echo_getimgsize($temp_get_img);
                        $count++;
                        if(isset($image[0]) && isset($image[1]) && is_numeric($image[0]) && is_numeric($image[1]))
                        {
                            if (($image[0] * $image[1]) > $maxSize) {   
                                $maxSize = $image[0] * $image[1]; 
                                $biggest_img = $temp_get_img;
                            }
                        }
                        else
                        {
                            error_reporting(0);
                            $image = getimagesize($temp_get_img);
                            error_reporting(E_ALL);
                            if(isset($image[0]) && isset($image[1]) && is_numeric($image[0]) && is_numeric($image[1]))
                            {
                                if (($image[0] * $image[1]) > $maxSize) {   
                                    $maxSize = $image[0] * $image[1]; 
                                    $biggest_img = $temp_get_img;
                                }
                            }
                        }
                    }
                }
            }
        }
        if($count == 1 && $skip_first_img == '1')
        {
            return '';
        }
        return $biggest_img;
    }
    return ''; 
}

function echo_get_url_header($newstr)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $newstr);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HEADER, 1);
    curl_setopt($ch, CURLOPT_NOBODY, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    $echo_Main_Settings = get_option('echo_Main_Settings', false);
    if (isset($echo_Main_Settings['proxy_url']) && $echo_Main_Settings['proxy_url'] != '' && $echo_Main_Settings['proxy_url'] != 'disable' && $echo_Main_Settings['proxy_url'] != 'disabled') {
        $prx = explode(',', $echo_Main_Settings['proxy_url']);
        $randomness = array_rand($prx);
        curl_setopt( $ch, CURLOPT_PROXY, trim($prx[$randomness]));
        if (isset($echo_Main_Settings['proxy_auth']) && $echo_Main_Settings['proxy_auth'] != '') 
        {
            $prx_auth = explode(',', $echo_Main_Settings['proxy_auth']);
            if(isset($prx_auth[$randomness]) && trim($prx_auth[$randomness]) != '')
            {
                curl_setopt( $ch, CURLOPT_PROXYUSERPWD, trim($prx_auth[$randomness]) );
            }
        }
    }
    $output = curl_exec($ch);
    if($output === false)
    {
        curl_close($ch);
        return false;
    }
    if(!curl_errno($ch))
    {
        curl_close($ch);
        return false;
    }
    curl_close($ch);
    $headers=array();
    $data=explode("\n",$output);
    $headers['Status']=$data[0];
    array_shift($data);
    foreach($data as $part){
        if($part != '')
        {
            $middle=explode(":",$part);
            if(isset($middle[1]) && isset($middle[0]) && $middle[1] != '' && $middle[0] != '')
            {
                $headers[trim($middle[0])] = trim($middle[1]);
            }
        }
    }
    return $headers;
}
function echo_is_valid_img($img_type3, $img_url)
{
    
    if (strstr($img_type3, 'image/') !== false) {
        $image=echo_getimgsize($img_url);
        if(isset($image[0]) && isset($image[1]) && is_numeric($image[0]) && is_numeric($image[1]))
        {
            if (($image[0] * $image[1]) >= 100) {
                return true;
            }
        }
        else
        {
            error_reporting(0);
            $image=getimagesize($img_url);
            error_reporting(E_ALL);
            if(isset($image[0]) && isset($image[1]) && is_numeric($image[0]) && is_numeric($image[1]))
            {
                if (($image[0] * $image[1]) >= 100) {
                    return true;
                }
            }
        }
    }
    return false;
}
function echo_getimgsize($url, $referer = 'https://www.google.com')
{
    if(!function_exists('imagecreatefromstring'))
    {
        return false;
    }
    $headers = array(
                    'Range: bytes=0-32768'
                    );
    if (!empty($referer)) array_push($headers, 'Referer: '.$referer);
    $curl = curl_init($url);
    $echo_Main_Settings = get_option('echo_Main_Settings', false);
    if (isset($echo_Main_Settings['proxy_url']) && $echo_Main_Settings['proxy_url'] != '' && $echo_Main_Settings['proxy_url'] != 'disable' && $echo_Main_Settings['proxy_url'] != 'disabled') {
        $prx = explode(',', $echo_Main_Settings['proxy_url']);
        $randomness = array_rand($prx);
        curl_setopt( $curl, CURLOPT_PROXY, trim($prx[$randomness]));
        if (isset($echo_Main_Settings['proxy_auth']) && $echo_Main_Settings['proxy_auth'] != '') 
        {
            $prx_auth = explode(',', $echo_Main_Settings['proxy_auth']);
            if(isset($prx_auth[$randomness]) && trim($prx_auth[$randomness]) != '')
            {
                curl_setopt( $curl, CURLOPT_PROXYUSERPWD, trim($prx_auth[$randomness]) );
            }
        }
    }
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 10);
    curl_setopt($curl, CURLOPT_TIMEOUT, 10);
    $data = curl_exec($curl);
    curl_close($curl);
    if($data == false)
    {
        return false;
    }
    error_reporting(0);
    $image = imagecreatefromstring($data);
    if($image === false)
    {
        return false;
    }
    $return = array(imagesx($image), imagesy($image));
    imagedestroy($image);
    error_reporting(E_ALL);
    return $return;
}
function echo_wpse_allowedtags() {
        return '<script>,<style>,<br>,<em>,<i>,<ul>,<ol>,<li>,<a>,<p>,<img>,<video>,<audio>'; 
}
function echo_wpse_allowedtags2() {
        return '<br>,<em>,<i>,<ul>,<ol>,<li>,<a>,<p>'; 
}
function echo_wpse_allowedtags3() {
        return '<br>'; 
}
function echo_truncate($text, $length = 100, $options = array()) {
    $default = array(
        'ending' => '...', 'exact' => true, 'html' => false
    );
    $options = array_merge($default, $options);
    extract($options);

    if ($html) {
        if (strlen(preg_replace('/<.*?>/', '', $text)) <= $length) {
            return $text;
        }
        $totalLength = strlen(strip_tags($ending));
        $openTags = array();
        $truncate = '';

        preg_match_all('/(<\/?([\w+]+)[^>]*>)?([^<>]*)/', $text, $tags, PREG_SET_ORDER);
        foreach ($tags as $tag) {
            if (!preg_match('/img|br|input|hr|area|base|basefont|col|frame|isindex|link|meta|param/s', $tag[2])) {
                if (preg_match('/<[\w]+[^>]*>/s', $tag[0])) {
                    array_unshift($openTags, $tag[2]);
                } else if (preg_match('/<\/([\w]+)[^>]*>/s', $tag[0], $closeTag)) {
                    $pos = array_search($closeTag[1], $openTags);
                    if ($pos !== false) {
                        array_splice($openTags, $pos, 1);
                    }
                }
            }
            $truncate .= $tag[1];

            $contentLength = strlen(preg_replace('/&[0-9a-z]{2,8};|&#[0-9]{1,7};|&#x[0-9a-f]{1,6};/i', ' ', $tag[3]));
            if ($contentLength + $totalLength > $length) {
                $left = $length - $totalLength;
                $entitiesLength = 0;
                if (preg_match_all('/&[0-9a-z]{2,8};|&#[0-9]{1,7};|&#x[0-9a-f]{1,6};/i', $tag[3], $entities, PREG_OFFSET_CAPTURE)) {
                    foreach ($entities[0] as $entity) {
                        if ($entity[1] + 1 - $entitiesLength <= $left) {
                            $left--;
                            $entitiesLength += strlen($entity[0]);
                        } else {
                            break;
                        }
                    }
                }

                $truncate .= substr($tag[3], 0 , $left + $entitiesLength);
                break;
            } else {
                $truncate .= $tag[3];
                $totalLength += $contentLength;
            }
            if ($totalLength >= $length) {
                break;
            }
        }
    } else {
        if (strlen($text) <= $length) {
            return $text;
        } else {
            $truncate = substr($text, 0, $length - strlen($ending));
        }
    }
    if (!$exact) {
        $spacepos = strrpos($truncate, ' ');
        if (isset($spacepos)) {
            if ($html) {
                $bits = substr($truncate, $spacepos);
                preg_match_all('/<\/([a-z]+)>/', $bits, $droppedTags, PREG_SET_ORDER);
                if (!empty($droppedTags)) {
                    foreach ($droppedTags as $closingTag) {
                        if (!in_array($closingTag[1], $openTags)) {
                            array_unshift($openTags, $closingTag[1]);
                        }
                    }
                }
            }
            $truncate = substr($truncate, 0, $spacepos);
        }
    }
    $truncate .= $ending;

    if ($html) {
        foreach ($openTags as $tag) {
            $truncate .= '</'.$tag.'>';
        }
    }

    return $truncate;
} 
function echo_custom_wp_trim_excerpt($wpse_excerpt, $excerpt_word_count, $more_url, $read_more) {
    if($wpse_excerpt == '')
    {
        return '';
    }
    $wpse_excerpt = strip_shortcodes( $wpse_excerpt );
    $wpse_excerpt = str_replace(']]>', ']]&gt;', $wpse_excerpt);
    if($read_more == 'disabled')
    {
        $wpse_excerpt = strip_tags($wpse_excerpt, echo_wpse_allowedtags3());
    }
    else
    {
        $wpse_excerpt = strip_tags($wpse_excerpt, echo_wpse_allowedtags());
    }
    $tokens = array();
    $excerptOutput = '';
    $count = 0;
    preg_match_all('/(<[^>]+>|[^<>\s]+)\s*/u', $wpse_excerpt, $tokens);
    foreach ($tokens[0] as $token) { 
        if ($count >= $excerpt_word_count && preg_match('/[\,\;\?\.\!]\s*$/uS', $token)) { 
            $excerptOutput .= trim($token);
            break;
        }
        $count++;
        $excerptOutput .= $token;
    }
    $wpse_excerpt = trim(force_balance_tags($excerptOutput));
    $echo_Main_Settings = get_option('echo_Main_Settings', false);
    if($read_more == '')
    {
        if (isset($echo_Main_Settings['read_more_text']) && $echo_Main_Settings['read_more_text'] != '') {
            $read_more = $echo_Main_Settings['read_more_text'];
        }
        else
        {
            $read_more = esc_html__('Read More', 'rss-feed-post-generator-echo');
        }
    }
    elseif($read_more == 'disabled')
    {
        $read_more = '';
    }
    if($read_more !== '')
    {
        $excerpt_end = ' <a class="echo_read_more" href="' . esc_url_raw($more_url) . '" target="_blank">&nbsp;&raquo;&nbsp;' . esc_html($read_more) . '</a>'; 
        $wpse_excerpt .= $excerpt_end;
    }
    else
    {
        $wpse_excerpt = trim($wpse_excerpt, ',!?.:;-[](){}\'"') . '...';
    }
    return $wpse_excerpt;
}

function echo_my_list_cats() {
    $catsArray = array();
    $cat_args   = array(
        'orderby' => 'name',
        'hide_empty' => 0,
        'order' => 'ASC'
    );
    $categories = get_categories($cat_args);
    foreach($categories as $cat) {
        $catsArray[] = $cat->name;
    }
    return $catsArray;
}
function echo_str_word_count( $str, $format = 0, $strip_tags = false )
{
    if( $strip_tags )
        $str = trim(strip_tags($str));
    $words = 0;
    $array = array();
    $pattern = "/[\d\"^!#$%&()*+,.\/:;<=>?@\]\[\\\_`{|}~ \t\r\n\v\f]+/";
    $str = preg_replace($pattern, " ", $str);
    $str_array = explode(' ', $str);
    foreach( $str_array as $word )
    {
        if( preg_match('/[A-Za-z\pL]/', $word) )
        {
            $array[] = $word;
            $words++;
        }
    }    
    if( $format == 1 )
        return $array;    
    return $words;
}

function echo_my_user_by_rand( $ua ) {
  remove_action('pre_user_query', 'echo_my_user_by_rand');
  $ua->query_orderby = str_replace( 'user_login ASC', 'RAND()', $ua->query_orderby );
}

function echo_display_random_user(){
  add_action('pre_user_query', 'echo_my_user_by_rand');
  $args = array(
    'orderby' => 'user_login', 'order' => 'ASC', 'number' => 1, 'role__in' => array( 'contributor','author','editor','administrator','super-admin' )
  );
  $user_query = new WP_User_Query( $args );
  $user_query->query();
  $results = $user_query->results;
  if(empty($results))
  {
      return false;
  }
  shuffle($results);
  return array_pop($results);
}


function echo_url_handle($href, $api_key)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://api.shorte.st/v1/data/url");
    curl_setopt($ch, CURLOPT_POSTFIELDS, "urlToShorten=" . trim($href));
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $headers = [
        'public-api-token: ' . $api_key,
        'Content-Type: application/x-www-form-urlencoded'
    ];
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
    curl_setopt($ch, CURLOPT_TIMEOUT, 60);
    $serverOutput = json_decode(curl_exec($ch), true);
    curl_close($ch);
    if (!isset($serverOutput['shortenedUrl']) || $serverOutput['shortenedUrl'] == '') {
        return $href;
    } else {
        return esc_url_raw($serverOutput['shortenedUrl']);
    }  
}

function echo_fix_google_links($url, $feed_uri)
{
    if (stristr ( $url, 'news.google' ) && stristr ( $url, 'url=' )) 
    {
        $urlParts = explode ( 'url=', $url );
        $correctUrl = $urlParts [1];
        $url = $correctUrl;
    }
    elseif(stristr($url,'news.google') && stristr($url,'/articles/') )
    {
        $new_link = echo_get_google_news_link($url);
        if($new_link !== false)
        {
            $url = $new_link;
        }
    }
    elseif (stristr ( $feed_uri, 'alerts/feeds' ) && stristr ( $feed_uri, 'google' )) 
    {
        preg_match ( '{url\=(.*?)&}', $url, $urlMatches );
        if(isset($urlMatches[1]))
        {
            $correctUrl = $urlMatches[1];
            if (trim($correctUrl) != '') 
            {
                $url = $correctUrl;
            }
        }
    }
    return $url;
}

function echo_get_google_news_link($link) 
{
    $link = explode('?', $link)[0];
    $base64_partz = preg_match('/\/articles\/(.*?)$/', $link, $matches);
    if (isset($matches[1]) && trim($matches[1]) != '') 
    {
        $base64_partz = $matches[1];
    } 
    else 
    {
        return false;
    }
    $decoded = base64_decode($base64_partz);
    if (stristr($decoded, 'http://') || stristr($decoded, 'https://')) 
    {
        $decoded = preg_replace('/^.*http/', 'http', $decoded);
        $decoded = preg_replace('{\\xd2\\x01\\x00}', '', $decoded);
        $decoded = trim($decoded);
        return $decoded;
    }
    if (stristr($decoded, 'AU_y')) 
    {
        $decoded = echo_decode_google_news_link_base64($base64_partz);
        if ($decoded !== false && trim($decoded) != '') 
        {
            return $decoded;
        }
    }
    return $link;
}
function echo_decode_google_news_link_base64($id)
{
    $decoding_params = echo_get_decoding_params($id);
    if($decoding_params === false)
    {
        return false;
    }
    $articles = array($decoding_params);
    $articles_reqs = [];
    foreach ($articles as $art) 
    {
        $articles_reqs[] = [
            "Fbv4je",
            '["garturlreq",[["X","X",["X","X"],null,null,1,1,"US:en",null,1,null,null,null,null,null,0,1],"X","X",1,[1,1,1],1,1,null,0,0,null,0],"' . $art["gn_art_id"] . '",' . $art["timestamp"] . ',"' . $art["signature"] . '"]',
        ];
    }
    $payload = 'f.req=' . urlencode(json_encode([$articles_reqs]));
    $ch = curl_init();
    if($ch === false)
    {
        return false;
    }
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_REFERER, 'https://news.google.com/');
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 15);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_MAXREDIRS, 20);
    curl_setopt($ch, CURLOPT_URL, "https://news.google.com/_/DotsSplashUi/data/batchexecute");
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/x-www-form-urlencoded;charset=UTF-8"]);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
    $response = curl_exec($ch);
    $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    if ($httpcode == 302) 
    {
        $redirect_url = curl_getinfo($ch, CURLINFO_REDIRECT_URL);
        if (stristr($redirect_url, 'sorry')) 
        {
            return false;
        }
    }
    if (!stristr($response, 'garturlres')) 
    {
        return false;
    }
    $response = stripslashes($response);
    preg_match_all('#"garturlres","([^"]*?)"#i', $response, $garturMatchs);
    if(isset($garturMatchs[1][0]) && trim($garturMatchs[1][0]) !== '')
    {
        return trim($garturMatchs[1][0]);
    }
    return false;
}
function echo_get_decoding_params($gn_art_id)
{
    $randsleep = rand(500000, 1000000);
    usleep($randsleep);
    $url = "https://news.google.com/rss/articles/" . $gn_art_id;
    $ch = curl_init();
    if($ch === false)
    {
        return false;
    }
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_REFERER, 'https://news.google.com/');
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 15);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_MAXREDIRS, 20);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.3');
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: text/html'));
    $server_output = curl_exec ($ch);
    if($server_output === false)
    {
        echo_log_to_file('Failed to get server_output ');
        return false;
    }
    $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    if ($httpcode != 200) 
    {
        echo_log_to_file('Failed to get httpcode ' . $httpcode);
        return false;
    }
    if (trim($server_output) == '') 
    {
        echo_log_to_file('Failed to get server_output ' . $server_output);
        return false;
    }
    $dom = new DOMDocument();
    $internalErrors = libxml_use_internal_errors(true);
    $dom->loadHTML($server_output);
    libxml_use_internal_errors($internalErrors);
    $xpath = new DOMXPath($dom);
    /** @var DOMElement $div */
    $div = $xpath->query("//c-wiz/div")->item(0);

    if (!$div) 
    {
        echo_log_to_file('Failed to get div ' . $div);
        return false;
    }
    return [
        "signature" => $div->getAttribute("data-n-a-sg"),
        "timestamp" => $div->getAttribute("data-n-a-ts"),
        "gn_art_id" => $gn_art_id,
    ];
}
function echo_replaceSynergyShortcodes($the_content)
{
    $regex = '#%%([a-z0-9]+?)(?:_title)?_(\d+?)_(\d+?)%%#';
    $rezz = preg_match_all($regex, $the_content, $matches);
    if ($rezz === FALSE) {
        return $the_content;
    }
    if(isset($matches[1][0]))
    {
        $two_var_functions = array('pdfomatic');
        $three_var_functions = array('bhomatic', 'crawlomatic', 'dmomatic', 'ezinomatic', 'fbomatic', 'flickomatic', 'imguromatic', 'iui', 'instamatic', 'linkedinomatic', 'mediumomatic', 'pinterestomatic', 'echo', 'spinomatic', 'tumblomatic', 'wordpressomatic', 'wpcomomatic', 'youtubomatic', 'mastermind', 'businessomatic');
        $four_var_functions = array('aiomatic', 'contentomatic', 'quoramatic', 'newsomatic', 'aliomatic', 'amazomatic', 'blogspotomatic', 'bookomatic', 'careeromatic', 'cbomatic', 'cjomatic', 'craigomatic', 'ebayomatic', 'etsyomatic', 'rakutenomatic', 'learnomatic', 'eventomatic', 'gameomatic', 'gearomatic', 'giphyomatic', 'gplusomatic', 'hackeromatic', 'imageomatic', 'midas', 'movieomatic', 'nasaomatic', 'ocartomatic', 'okomatic', 'playomatic', 'recipeomatic', 'redditomatic', 'soundomatic', 'mp3omatic', 'ticketomatic', 'tmomatic', 'trendomatic', 'tuneomatic', 'twitchomatic', 'twitomatic', 'vimeomatic', 'viralomatic', 'vkomatic', 'walmartomatic', 'bestbuyomatic', 'wikiomatic', 'xlsxomatic', 'yelpomatic', 'yummomatic');
        for ($i = 0; $i < count($matches[1]); $i++)
        {
            $replace_me = false;
            if(in_array($matches[1][$i], $four_var_functions))
            {
                $za_function = $matches[1][$i] . '_run_rule';
                if(function_exists($za_function))
                {
                    $xreflection = new ReflectionFunction($za_function);
                    if($xreflection->getNumberOfParameters() >= 4)
                    {  
                        $rule_runner = $za_function($matches[3][$i], $matches[2][$i], 0, 1);
                        if($rule_runner != 'fail' && $rule_runner != 'nochange' && $rule_runner != 'ok' && $rule_runner !== false)
                        {
                            if(is_array($rule_runner))
                            {
                                $the_content = str_replace('%%' . $matches[1][$i] . '_' . $matches[2][$i] . '_' . $matches[3][$i] . '%%', $rule_runner[0], $the_content);
                                $the_content = str_replace('%%' . $matches[1][$i] . '_title_' . $matches[2][$i] . '_' . $matches[3][$i] . '%%', $rule_runner[1], $the_content);
                            }
                            else
                            {
                                $the_content = str_replace('%%' . $matches[1][$i] . '_' . $matches[2][$i] . '_' . $matches[3][$i] . '%%', $rule_runner, $the_content);
                                $the_content = str_replace('%%' . $matches[1][$i] . '_title_' . $matches[2][$i] . '_' . $matches[3][$i] . '%%', '', $the_content);
                            }
                            
                            $replace_me = true;
                        }
                    }
                    $xreflection = null;
                    unset($xreflection);
                }
            }
            elseif(in_array($matches[1][$i], $three_var_functions))
            {
                $za_function = $matches[1][$i] . '_run_rule';
                if(function_exists($za_function))
                {
                    $xreflection = new ReflectionFunction($za_function);
                    if($xreflection->getNumberOfParameters() >= 3)
                    {
                        $rule_runner = $za_function($matches[3][$i], 0, 1);
                        if($rule_runner != 'fail' && $rule_runner != 'nochange' && $rule_runner != 'ok' && $rule_runner !== false)
                        {
                            if(is_array($rule_runner))
                            {
                                $the_content = str_replace('%%' . $matches[1][$i] . '_' . $matches[2][$i] . '_' . $matches[3][$i] . '%%', $rule_runner[0], $the_content);
                                $the_content = str_replace('%%' . $matches[1][$i] . '_title_' . $matches[2][$i] . '_' . $matches[3][$i] . '%%', $rule_runner[1], $the_content);
                            }
                            else
                            {
                                $the_content = str_replace('%%' . $matches[1][$i] . '_' . $matches[2][$i] . '_' . $matches[3][$i] . '%%', $rule_runner, $the_content);
                                $the_content = str_replace('%%' . $matches[1][$i] . '_title_' . $matches[2][$i] . '_' . $matches[3][$i] . '%%', '', $the_content);
                            }
                            
                            $replace_me = true;
                        }
                    }
                    $xreflection = null;
                    unset($xreflection);
                }
            }
            elseif(in_array($matches[1][$i], $two_var_functions))
            {
                $za_function = $matches[1][$i] . '_run_rule';
                if(function_exists($za_function))
                {
                    $xreflection = new ReflectionFunction($za_function);
                    if($xreflection->getNumberOfParameters() >= 2)
                    {
                        $rule_runner = $za_function($matches[3][$i], 1);
                        if($rule_runner != 'fail' && $rule_runner != 'nochange' && $rule_runner != 'ok' && $rule_runner !== false)
                        {
                            if(is_array($rule_runner))
                            {
                                $the_content = str_replace('%%' . $matches[1][$i] . '_' . $matches[2][$i] . '_' . $matches[3][$i] . '%%', $rule_runner[0], $the_content);
                                $the_content = str_replace('%%' . $matches[1][$i] . '_title_' . $matches[2][$i] . '_' . $matches[3][$i] . '%%', $rule_runner[1], $the_content);
                            }
                            else
                            {
                                $the_content = str_replace('%%' . $matches[1][$i] . '_' . $matches[2][$i] . '_' . $matches[3][$i] . '%%', $rule_runner, $the_content);
                                $the_content = str_replace('%%' . $matches[1][$i] . '_title_' . $matches[2][$i] . '_' . $matches[3][$i] . '%%', '', $the_content);
                            }
                            
                            $replace_me = true;
                        }
                    }
                    $xreflection = null;
                    unset($xreflection);
                }
            }
            if($replace_me == false)
            {
                $the_content = str_replace('%%' . $matches[1][$i] . '_' . $matches[2][$i] . '_' . $matches[3][$i] . '%%', '', $the_content);
                $the_content = str_replace('%%' . $matches[1][$i] . '_title_' . $matches[2][$i] . '_' . $matches[3][$i] . '%%', '', $the_content);
            }
        }
    }
    if(function_exists('aiomatic_ai_content_replace'))
    {
        $the_content = apply_filters('aiomatic_replace_aicontent_shortcode', $the_content);
    }
    else
    {
        preg_match_all('#\[[\t\s]*aicontent(?:[\t\s]*model=[\'"]?([^\]"\']+)[\'"]?)?[\t\s]*\](.*?)\[\/[\t\s]*aicontent[\t\s]*\]#i', $the_content, $matches);
        if(isset($matches[0][0]) && isset($matches[1][0]))
        {
            for($i = 0; $i < count($matches[0]); $i++)
            {
                $the_content = str_replace($matches[0][$i], '', $the_content);
            }
        }
    }
    return $the_content;
}

function echo_add_feed_options($feed, $url)
{
    $echo_Main_Settings = get_option('echo_Main_Settings', false);
    if ((isset($echo_Main_Settings['echo_force_feeds']) && $echo_Main_Settings['echo_force_feeds'] == 'on') || (strpos($url, 'feeds.bbci.co.uk') !== false)) 
    {
        if(method_exists($feed, 'force_feed'))
        {
            $feed->force_feed(true);
        }
    }
    if(method_exists($feed, 'set_curl_options'))
    {
        $feed->set_curl_options(
            array(
                CURLOPT_SSL_VERIFYHOST => false,
                CURLOPT_SSL_VERIFYPEER => false
            )
        );
    }
    $use_proxy = get_transient( 'echo_use_proxy');
    if ($use_proxy && isset($echo_Main_Settings['proxy_url']) && $echo_Main_Settings['proxy_url'] != '' && $echo_Main_Settings['proxy_url'] != 'disable' && $echo_Main_Settings['proxy_url'] != 'disabled') {
        $prx_arr = array();
        $prx = explode(',', $echo_Main_Settings['proxy_url']);
        $randomness = array_rand($prx);
        $prx_arr[CURLOPT_PROXY] = trim($prx[$randomness]);
        if (isset($echo_Main_Settings['proxy_auth']) && $echo_Main_Settings['proxy_auth'] != '') 
        {
            $prx_auth = explode(',', $echo_Main_Settings['proxy_auth']);
            if(isset($prx_auth[$randomness]) && trim($prx_auth[$randomness]) != '')
            {
                $prx_arr[CURLOPT_PROXYUSERPWD] = trim($prx_auth[$randomness]);
            }
        }
        if(method_exists($feed, 'set_curl_options'))
        {
            $feed->set_curl_options($prx_arr);
        }
    }
    $user_agent_cust = get_transient( 'echo_user_agent_cust');
    if($user_agent_cust != '')
    {
        if(method_exists($feed, 'set_useragent'))
        {
            $feed->set_useragent($user_agent_cust);
        }
    }
    else
    {
        if (isset($echo_Main_Settings['clear_user_agent']) && $echo_Main_Settings['clear_user_agent'] == 'on') {
            if(method_exists($feed, 'set_curl_options'))
            {
                $feed->set_useragent($user_agent_cust);
            }
        }
    }
    if (isset($echo_Main_Settings['custom_feed_timeout']) && $echo_Main_Settings['custom_feed_timeout'] != '') {
        if(method_exists($feed, 'set_timeout'))
        {
            $feed->set_timeout($echo_Main_Settings['custom_feed_timeout']);
        }
    }
    if (isset($echo_Main_Settings['echo_enable_caching']) && $echo_Main_Settings['echo_enable_caching'] == 'on') {
        if(method_exists($feed, 'enable_cache') && method_exists($feed, 'set_cache_location'))
        {
            $feed->enable_cache(true);
            $feed->set_cache_location(dirname(__FILE__) . "/res/cache");
        }
    }
    if (isset($echo_Main_Settings['echo_no_strip']) && $echo_Main_Settings['echo_no_strip'] == 'on') {
        if(method_exists($feed, 'strip_htmltags'))
        {
            $feed->strip_htmltags(false);
        }
    }
}

function echo_run_rule($param, $auto = 1, $ret_content = 0)
{
    $plugin = plugin_basename(__FILE__);
    $plugin_slug = explode('/', $plugin);
    $plugin_slug = $plugin_slug[0];
    $uoptions = array();
    $is_activated = echo_is_activated($plugin_slug, $uoptions);
    if($is_activated !== true && $is_activated !== 2)
    {
        echo_log_to_file('You need to activate the plugin using a valid purchase code for this feature to work.');
        return 'nochange';
    }
    $echo_Main_Settings = get_option('echo_Main_Settings', false);
    if($ret_content == 0)
    {
        $f = fopen(get_temp_dir() . 'echo_' . $param, 'w');
        if($f !== false)
        {
            $flock_disabled = explode(',', ini_get('disable_functions'));
            if(!in_array('flock', $flock_disabled))
            {
                if (!flock($f, LOCK_EX | LOCK_NB)) {
                    return 'nochange';
                }
            }
        }
    }
    if (isset($echo_Main_Settings['rule_timeout']) && $echo_Main_Settings['rule_timeout'] != '') {
        $timeout = intval($echo_Main_Settings['rule_timeout']);
    } else {
        $timeout = 3600;
    }
    ini_set('memory_limit', '-1');
    ini_set('default_socket_timeout', $timeout);
    ini_set('safe_mode', 'Off');
    ini_set('max_execution_time', $timeout);
    ini_set('ignore_user_abort', 1);
    ini_set('user_agent', echo_get_random_user_agent());
    if(function_exists('ignore_user_abort'))
    {
        ignore_user_abort(true);
    }
            if(function_exists('set_time_limit'))
    {
        set_time_limit($timeout);
    }
    $draft_me = false;
    $xxxfailed = false;
    $posts_inserted         = 0;
    $auto_generate_comments = '0';
    if (isset($echo_Main_Settings['echo_enabled']) && $echo_Main_Settings['echo_enabled'] == 'on') {
        try {
            $seed_news        = 'https://www.newsomaticapi.com/seed';
            $item_img         = '';
            $cont             = 0;
            $found            = 0;
            $ids              = '';
            $schedule         = '';
            $enable_comments  = '1';
            $enable_pingback  = '1';
            $parent_id        = '';
            $author_link      = '';
            $author_email     = '';
            $max              = PHP_INT_MAX;
            $active           = '0';
            $last_run         = '';
            $ruleType         = 'week';
            $first            = false;
            $others           = array();
            $post_title       = '';
            $post_content     = '';
            $list_item        = '';
            $default_category = '';
            $extra_categories = '';
            $only_text       = '';
            $stick_posts     = '';
            $single          = '';
            $type            = '';
            $inner           = '';
            $expre           = '';
            $lazy_tag        = '';
            $attach_screen   = '';
            $get_css         = '';
            $posted_items    = array();
            $post_status     = 'publish';
            $post_type       = 'post';
            $accept_comments = 'closed';
            $post_user_name  = 1;
            $full_content    = '0';
            $full_title      = '0';
            $item_create_tag = '';
            $can_create_tag  = '0';
            $strip_images    = '0';
            $strip_links     = '0';
            $restr_target    = 'c';
            $item_tags       = '';
            $max             = 50;
            $auto_categories = '0';
            $featured_image  = '0';
            $image_url       = '';
            $banned_words    = '';
            $required_words  = '';
            $banned_authors    = '';
            $required_authors  = '';
            $banned_cats     = '';
            $required_cats   = '';
            $copy_slug       = '';
            $cap_first       = '';
            $strip_by_id     = '';
            $encoding        = 'NO_CHANGE';
            $strip_by_class  = '';
            $post_format     = 'post-format-standard';
            $post_array      = array();
            $limit_word_count = '';
            $limit_title_count = '';
            $import_date     = '0';
            $translate       = 'disabled';
            $hideGoogle      = '0';
            $remove_default  = '0';
            $rule_unique_id  = '';
            $read_more       = '';
            $skip_og         = '0';
            $skip_feed_image = '0';
            $remove_cats     = '';
            $auto_delete     = '';
            $skip_first_img  = '0';
            $content_percent = '';
            $skip_post_content = '0';
            $custom_fields   = '';
            $source_lang     = 'en';
            $second_translate = 'disabled';
            $custom_feed_tag_list = '';
            $custom_tag_list = '';
            $custom_tag_separator = '';
            $custom_attr_separator = '';
            $keyword_cat     = '';
            $feed_order      = '';
            $keyword_tag     = '';
            $banned_urls     = '';
            $image_captions  = '';
            $post_excerpt    = '';
            $htuser_pass     = '';
            $strip_by_regex  = '';
            $replace_regex   = '';
            $custom_tax      = '';
            $link_source     = '0';
            $replace_url     = '';
			$skip_older      = '';
            $skip_newer      = '';
            $parent_category_id = '';
            $nofollow        = '';
            $date_index      = '';
            $custom_simple   = '';
            $try_final       = '';
            $tag_type        = '';
            $tag_expre       = '';
            $use_phantom     = '';
            $use_proxy       = '';
            $update_existing = '';
            $rel_canonical   = '';
            $wpml_lang       = '';
            $user_agent_cust = '';
            $royalty_free    = '';
            $custom_cookie   = '';
            $image_type      = '';
            $image_expre     = '';
            $allow_html_tags = '';
            $default_author  = '';
            $regex_before    = '';
            $rule_description = '';
            $strip_by_regex_title = '';
            $replace_regex_title = '';
            $GLOBALS['wp_object_cache']->delete('echo_rules_list', 'options');
            if (!get_option('echo_rules_list')) {
                $rules = array();
            } else {
                $rules = get_option('echo_rules_list');
            }
            $feed_uri = 'default';
            $zxdome = false;
            if (!empty($rules)) {
                foreach ($rules as $request => $bundle[]) {
                    if ($cont == $param) {
                        $bundle_values    = array_values($bundle);
                        $myValues         = $bundle_values[$cont];
                        $array_my_values  = array_values($myValues);for($iji=0;$iji<count($array_my_values);++$iji){if(is_string($array_my_values[$iji])){$array_my_values[$iji]=stripslashes($array_my_values[$iji]);}}
                        $ids              = isset($array_my_values[0]) ? $array_my_values[0] : '';
                        $schedule         = isset($array_my_values[1]) ? $array_my_values[1] : '';
                        $active           = isset($array_my_values[2]) ? $array_my_values[2] : '';
                        $last_run         = isset($array_my_values[3]) ? $array_my_values[3] : '';
                        $max              = isset($array_my_values[4]) ? $array_my_values[4] : '';
                        $post_status      = isset($array_my_values[5]) ? $array_my_values[5] : '';
                        $post_type        = isset($array_my_values[6]) ? $array_my_values[6] : '';
                        $post_user_name   = isset($array_my_values[7]) ? $array_my_values[7] : '';
                        $item_create_tag  = isset($array_my_values[8]) ? $array_my_values[8] : '';
                        $default_category = isset($array_my_values[9]) ? $array_my_values[9] : '';
                        $auto_categories  = isset($array_my_values[10]) ? $array_my_values[10] : '';
                        $can_create_tag   = isset($array_my_values[11]) ? $array_my_values[11] : '';
                        $enable_comments  = isset($array_my_values[12]) ? $array_my_values[12] : '';
                        $featured_image   = isset($array_my_values[13]) ? $array_my_values[13] : '';
                        $image_url        = isset($array_my_values[14]) ? $array_my_values[14] : '';
                        $post_title       = isset($array_my_values[15]) ? htmlspecialchars_decode($array_my_values[15]) : '';
                        $post_content     = isset($array_my_values[16]) ? htmlspecialchars_decode($array_my_values[16]) : '';
                        $enable_pingback  = isset($array_my_values[17]) ? $array_my_values[17] : '';
                        $post_format      = isset($array_my_values[18]) ? $array_my_values[18] : '';
                        $full_content     = isset($array_my_values[19]) ? $array_my_values[19] : '';
                        $only_text        = isset($array_my_values[20]) ? $array_my_values[20] : '';
                        $single           = isset($array_my_values[21]) ? $array_my_values[21] : '';
                        $type             = isset($array_my_values[22]) ? $array_my_values[22] : '';
                        $inner            = isset($array_my_values[23]) ? $array_my_values[23] : '';
                        $expre            = isset($array_my_values[24]) ? $array_my_values[24] : '';
                        $stick_posts      = isset($array_my_values[25]) ? $array_my_values[25] : '';
                        $banned_words     = isset($array_my_values[26]) ? $array_my_values[26] : '';
                        $required_words   = isset($array_my_values[27]) ? $array_my_values[27] : '';
                        $strip_by_id      = isset($array_my_values[28]) ? $array_my_values[28] : '';
                        $strip_by_class   = isset($array_my_values[29]) ? $array_my_values[29] : '';
                        $encoding         = isset($array_my_values[30]) ? $array_my_values[30] : 'NO_CHANGE';
                        $limit_word_count = isset($array_my_values[31]) ? $array_my_values[31] : '';
                        $translate        = isset($array_my_values[32]) ? $array_my_values[32] : 'disabled';
                        $hideGoogle       = isset($array_my_values[33]) ? $array_my_values[33] : '';
                        $strip_images     = isset($array_my_values[34]) ? $array_my_values[34] : '';
                        $import_date      = isset($array_my_values[35]) ? $array_my_values[35] : '';
                        $remove_default   = isset($array_my_values[36]) ? $array_my_values[36] : '';
                        $rule_unique_id   = isset($array_my_values[37]) ? $array_my_values[37] : '';
                        $read_more        = isset($array_my_values[38]) ? $array_my_values[38] : '';
                        $skip_og          = isset($array_my_values[39]) ? $array_my_values[39] : '';
                        $skip_feed_image  = isset($array_my_values[40]) ? $array_my_values[40] : '';
                        $remove_cats      = isset($array_my_values[41]) ? $array_my_values[41] : '';
                        $auto_delete      = isset($array_my_values[42]) ? $array_my_values[42] : '';
                        $skip_first_img   = isset($array_my_values[43]) ? $array_my_values[43] : '';
                        $skip_post_content= isset($array_my_values[44]) ? $array_my_values[44] : '';
                        $content_percent  = isset($array_my_values[45]) ? $array_my_values[45] : '';
                        $custom_fields    = isset($array_my_values[46]) ? $array_my_values[46] : '';
                        $source_lang      = isset($array_my_values[47]) ? $array_my_values[47] : '';
                        $strip_by_regex   = isset($array_my_values[48]) ? $array_my_values[48] : '';
                        $replace_regex    = isset($array_my_values[49]) ? $array_my_values[49] : '';
                        $custom_tax       = isset($array_my_values[50]) ? $array_my_values[50] : '';
                        $link_source      = isset($array_my_values[51]) ? $array_my_values[51] : '';
                        $replace_url      = isset($array_my_values[52]) ? $array_my_values[52] : '';
						$skip_older       = isset($array_my_values[53]) ? $array_my_values[53] : '';
                        $parent_category_id= isset($array_my_values[54]) ? $array_my_values[54] : '';
                        $date_index       = isset($array_my_values[55]) ? $array_my_values[55] : '';
                        $skip_newer       = isset($array_my_values[56]) ? $array_my_values[56] : '';
                        $nofollow         = isset($array_my_values[57]) ? $array_my_values[57] : '';
                        $user_agent_cust  = isset($array_my_values[58]) ? $array_my_values[58] : '';
                        $custom_simple    = isset($array_my_values[59]) ? $array_my_values[59] : '';
                        $use_phantom      = isset($array_my_values[60]) ? $array_my_values[60] : '';
                        $limit_title_count= isset($array_my_values[61]) ? $array_my_values[61] : '';
                        $royalty_free     = isset($array_my_values[62]) ? $array_my_values[62] : '';
                        $lazy_tag         = isset($array_my_values[63]) ? $array_my_values[63] : '';
                        $attach_screen    = isset($array_my_values[64]) ? $array_my_values[64] : '';
                        $custom_cookie    = isset($array_my_values[65]) ? $array_my_values[65] : '';
                        $use_proxy        = isset($array_my_values[66]) ? $array_my_values[66] : '';
                        $update_existing  = isset($array_my_values[67]) ? $array_my_values[67] : '';
                        $rel_canonical    = isset($array_my_values[68]) ? $array_my_values[68] : '';
                        $wpml_lang        = isset($array_my_values[69]) ? $array_my_values[69] : '';
                        $strip_by_regex_title = isset($array_my_values[70]) ? $array_my_values[70] : '';
                        $replace_regex_title = isset($array_my_values[71]) ? $array_my_values[71] : '';
                        $try_final        = isset($array_my_values[72]) ? $array_my_values[72] : '';
                        $tag_type         = isset($array_my_values[73]) ? $array_my_values[73] : '';
                        $tag_expre        = isset($array_my_values[74]) ? $array_my_values[74] : '';
                        $strip_links      = isset($array_my_values[75]) ? $array_my_values[75] : '';
                        $restr_target     = isset($array_my_values[76]) ? $array_my_values[76] : 'tc';
                        $banned_authors   = isset($array_my_values[77]) ? $array_my_values[77] : '';
                        $required_authors = isset($array_my_values[78]) ? $array_my_values[78] : '';
                        $banned_cats      = isset($array_my_values[79]) ? $array_my_values[79] : '';
                        $required_cats    = isset($array_my_values[80]) ? $array_my_values[80] : '';
                        $cap_first        = isset($array_my_values[81]) ? $array_my_values[81] : '';
                        $copy_slug        = isset($array_my_values[82]) ? $array_my_values[82] : '';
                        $image_type       = isset($array_my_values[83]) ? $array_my_values[83] : '';
                        $image_expre      = isset($array_my_values[84]) ? $array_my_values[84] : '';
                        $allow_html_tags  = isset($array_my_values[85]) ? $array_my_values[85] : '';
                        $full_title       = isset($array_my_values[86]) ? $array_my_values[86] : '';
                        $default_author   = isset($array_my_values[87]) ? $array_my_values[87] : '';
                        $regex_before     = isset($array_my_values[88]) ? $array_my_values[88] : '';
                        $rule_description = isset($array_my_values[89]) ? $array_my_values[89] : '';
                        $second_translate = isset($array_my_values[90]) ? $array_my_values[90] : '';
                        $custom_feed_tag_list = isset($array_my_values[91]) ? $array_my_values[91] : '';
                        $custom_tag_list  = isset($array_my_values[92]) ? $array_my_values[92] : '';
                        $custom_tag_separator = isset($array_my_values[93]) ? $array_my_values[93] : '';
                        $custom_attr_separator = isset($array_my_values[94]) ? $array_my_values[94] : '';
                        $keyword_cat      = isset($array_my_values[95]) ? $array_my_values[95] : '';
                        $keyword_tag      = isset($array_my_values[96]) ? $array_my_values[96] : '';
                        $feed_order       = isset($array_my_values[97]) ? $array_my_values[97] : '';
                        $banned_urls      = isset($array_my_values[98]) ? $array_my_values[98] : '';
                        $image_captions   = isset($array_my_values[99]) ? $array_my_values[99] : '';
                        $htuser_pass      = isset($array_my_values[100]) ? $array_my_values[100] : '';
                        $post_excerpt     = isset($array_my_values[101]) ? $array_my_values[101] : '';
                        $parent_id        = isset($array_my_values[102]) ? $array_my_values[102] : '';
                        $found            = 1;
                        break;
                    }
                    $cont = $cont + 1;
                }
            } else {
                echo_log_to_file('No rules found for echo_rules_list!');
                return 'fail';
            }
            if ($found == 0) {
                echo_log_to_file($param . ' not found in echo_rules_list!');
                return 'fail';
            } else {
                if($ret_content == 0)
                {
                    $GLOBALS['wp_object_cache']->delete('echo_rules_list', 'options');
                    $rules = get_option('echo_rules_list', array());
                    $rules[$param][3] = echo_get_date_now();
                    update_option('echo_rules_list', $rules, false);
                }
            }
            if($source_lang == 'disabled')
            {
                $source_lang = 'auto';
            }
            if($rule_unique_id == '')
            {
                $rule_unique_id = $param;
            }
            if(isset($echo_Main_Settings['date_locale']) && trim($echo_Main_Settings['date_locale']) != '') 
            { 
                setlocale(LC_TIME, trim($echo_Main_Settings['date_locale']));
            }
            if($ret_content == 0)
            {
                $GLOBALS['wp_object_cache']->delete('echo_running_list', 'options');
                $running = get_option('echo_running_list', array());
                if (!empty($running)) {
                    if (in_array($rule_unique_id, $running)) {
                        if (isset($echo_Main_Settings['enable_detailed_logging'])) {
                            echo_log_to_file('Only one instance of this rule is allowed. Rule is already running!');
                        }
                        return 'nochange';
                    }
                }
                $key = time();
                if(!isset($running[$key]))
                {
                    $running[$key] = $rule_unique_id;
                }
                else
                {
                    $running[$key + 1] = $rule_unique_id;
                }
                update_option('echo_running_list', $running, false);
                register_shutdown_function('echo_clear_flag_at_shutdown', $rule_unique_id);
            }
            if($custom_simple == '0' || $custom_simple == '')
            {
                if(isset($echo_Main_Settings['echo_custom_simplepie']) && $echo_Main_Settings['echo_custom_simplepie'] == 'on') 
                {
                    $custom_simple = '1';
                }
                else
                {
                    $custom_simple = '2';
                }
            }
            if ($custom_simple == '1')
            {
                try
                {
                    if(!class_exists('SimplePie_Autoloader', false))
                    {
                        require_once(dirname(__FILE__) . "/res/simplepie/autoloader.php");
                    }
                }
                catch(Exception $e) 
                {
                    echo_log_to_file('Exception thrown in SimplePie autoloader: ' . $e->getMessage());
                    if($auto == 1)
                    {
                        echo_clearFromList($param);
                    }
                    return 'fail';
                }
            }
            elseif ($custom_simple == '2')
            {
                if ( ! class_exists( 'SimplePie', false ) ) {
                    require_once( ABSPATH . WPINC . '/class-simplepie.php' );
                }
                include_once (ABSPATH . WPINC . '/feed.php');
            }
            elseif ($custom_simple == '3')
            {
                if(!class_exists('SimpleXMLElement'))
                {
                    echo_log_to_file('Please activate the SimpleXMLElement module on your site for this feature to work!');
                    if($auto == 1)
                    {
                        echo_clearFromList($param);
                    }
                    return 'fail';
                }
                if(!class_exists('Echo_RSS_Feed'))
                {
                    require_once (dirname(__FILE__) . "/res/rss-php/Feed.php");
                }
            }
            else
            {
                echo_log_to_file('Unrecognized feed parsing method: ' . $custom_simple);
                return 'fail';
            }
            if ($enable_comments == '1') {
                $accept_comments = 'open';
            }
            $ids = echo_replaceSynergyShortcodes($ids);
            $idsx = array();
            if(trim($ids, '/') == $seed_news)
            {
                for($ddx = 0; $ddx < 2; $ddx++)
                {
                    $valid_suff_arr = array('0','1','2','3','4','5','6','7','8','9','a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y');
                    $GLOBALS['wp_object_cache']->delete('echo_next_api_page', 'options');
                    $next_page  = get_option('echo_next_api_page', 0);
                    if($next_page === false)
                    {
                        $next_page = 0;
                    }
                    echo_log_to_file('Importing from feed source: ' . $valid_suff_arr[$next_page]);
                    $next_page_cmp = $next_page;
                    $next_page_cmp += 1;
                    if($next_page_cmp >= count($valid_suff_arr))
                    {
                        $next_page_cmp = 0;
                    }
                    update_option('echo_next_api_page', $next_page_cmp);
                    $got_me = array();
                    $sourcesPerPage = 500;
                    $paged = 0;
                    do
                    {
                        $postOffset = $paged * $sourcesPerPage;
                        $query = array(
                            'post_status' => array(
                                'publish'
                            ),
                            'post_type' => array(
                                'cna_source'
                            ),
                            'orderby'     => 'rand',
                            'numberposts' => $sourcesPerPage,
                            'offset'  => $postOffset,
                            'fields' => 'ids'
                        );
                        $query['tax_query'] = array(
                            'relation' => 'AND',
                            array(
                                'taxonomy'         => 'cna_type',
                                'field'            => 'slug',
                                'terms'            => 'feed' . $valid_suff_arr[$next_page],
                                'operator'         => 'IN'
                            ),
                            array(
                                'taxonomy'         => 'cna_active',
                                'field'            => 'slug',
                                'terms'            => '1',
                                'operator'         => 'IN'
                            )
                        );
                        $got_menow = get_posts($query);
                        $got_me = array_merge($got_menow, $got_me);
                        $paged++;
                    }while(!empty($got_menow));
                    foreach($got_me as $gmx)
                    {
                        $cna_rez = get_the_terms($gmx, 'cna_url');
                        if(is_wp_error($cna_rez) || $cna_rez == false)
                        {
                            echo_log_to_file('get_the_terms failed to get cna_url: ' . $gmx . '! ' . print_r($cna_rez, true));
                        }
                        else
                        {
                            $echo_custom_simplepie = get_post_meta( $gmx, 'echo_custom_simplepie', true );
                            if($echo_custom_simplepie != false && $echo_custom_simplepie != '')
                            {
                                $idsx[$gmx . '|' . $echo_custom_simplepie] = $cna_rez[0]->name;
                                if($echo_custom_simplepie != $custom_simple)
                                {
                                    if ($echo_custom_simplepie == '1')
                                    {
                                        try
                                        {
                                            if(!class_exists('SimplePie_Autoloader', false))
                                            {
                                                require_once(dirname(__FILE__) . "/res/simplepie/autoloader.php");
                                            }
                                        }
                                        catch(Exception $e) 
                                        {
                                            echo_log_to_file('Exception thrown in SimplePie autoloader: ' . $e->getMessage());
                                            if($auto == 1)
                                            {
                                                echo_clearFromList($param);
                                            }
                                            return 'fail';
                                        }
                                    }
                                    elseif ($echo_custom_simplepie == '2')
                                    {
                                        if ( ! class_exists( 'SimplePie', false ) ) {
                                            require_once( ABSPATH . WPINC . '/class-simplepie.php' );
                                            include_once (ABSPATH . WPINC . '/feed.php');
                                        }
                                    }
                                    elseif ($echo_custom_simplepie == '3')
                                    {
                                        if(!class_exists('SimpleXMLElement'))
                                        {
                                            echo_log_to_file('Please activate the SimpleXMLElement module on your site for this feature to work!');
                                            if($auto == 1)
                                            {
                                                echo_clearFromList($param);
                                            }
                                            return 'fail';
                                        }
                                        if(!class_exists('Echo_RSS_Feed'))
                                        {
                                            require_once (dirname(__FILE__) . "/res/rss-php/Feed.php");
                                        }
                                    }
                                }
                            }
                            else
                            {
                                $idsx[$gmx] = $cna_rez[0]->name;
                            }
                        }  
                    }
                }
            }
            else
            {
                $idsx = array($ids);
            }
            foreach($idsx as $source_id => $idx)
            {
                $source_id_arr = explode('|', $source_id);
                $source_id = $source_id_arr[0];
                if(isset($source_id_arr[1]))
                {
                    $current_custom_simplepie = $source_id_arr[1];
                }
                else
                {
                    $current_custom_simplepie = $custom_simple;
                }
                try
                {
                    if (isset($echo_Main_Settings['enable_detailed_logging'])) 
                    {
                        echo_log_to_file('Starting processing feed: ' . $idx);
                    }
                    $feed_uri = $idx;
                    if ($current_custom_simplepie == '1') 
                    {
                        $feed = new SimplePie();
                        if(method_exists($feed, 'set_curl_options'))
                        {
                            $feed->set_curl_options(
                                array(
                                    CURLOPT_SSL_VERIFYHOST => false,
                                    CURLOPT_SSL_VERIFYPEER => false
                                )
                            );
                        }
                        if ((isset($echo_Main_Settings['echo_force_feeds']) && $echo_Main_Settings['echo_force_feeds'] == 'on') || (strpos($feed_uri, 'feeds.bbci.co.uk') !== false)) {
                            $feed->force_feed(true);
                        }
                        if ($use_proxy && isset($echo_Main_Settings['proxy_url']) && $echo_Main_Settings['proxy_url'] != '' && $echo_Main_Settings['proxy_url'] != 'disable' && $echo_Main_Settings['proxy_url'] != 'disabled') {
                            $prx_arr = array();
                            $prx = explode(',', $echo_Main_Settings['proxy_url']);
                            $randomness = array_rand($prx);
                            $prx_arr[CURLOPT_PROXY] = trim($prx[$randomness]);
                            if (isset($echo_Main_Settings['proxy_auth']) && $echo_Main_Settings['proxy_auth'] != '') 
                            {
                                $prx_auth = explode(',', $echo_Main_Settings['proxy_auth']);
                                if(isset($prx_auth[$randomness]) && trim($prx_auth[$randomness]) != '')
                                {
                                    $prx_arr[CURLOPT_PROXYUSERPWD] = trim($prx_auth[$randomness]);
                                }
                            }
                            if(method_exists($feed, 'set_curl_options'))
                            {
                                $feed->set_curl_options($prx_arr);
                            }
                        }
                        if($htuser_pass != '')
                        {
                            $har = explode(':', $htuser_pass);
                            if(isset($har[1]))
                            {
                                $prx_arr = array();
                                $prx_arr[CURLOPT_USERPWD] = $htuser_pass;
                                $feed->set_curl_options($prx_arr);
                            }
                        }
                        if($user_agent_cust != '')
                        {
                            if(method_exists($feed, 'set_useragent'))
                            {
                                $feed->set_useragent($user_agent_cust);
                            }
                        }
                        else
                        {
                            if (isset($echo_Main_Settings['clear_user_agent']) && $echo_Main_Settings['clear_user_agent'] == 'on') {
                                if(method_exists($feed, 'set_curl_options'))
                                {
                                    $feed->set_useragent($user_agent_cust);
                                }
                            }
                        }
                        if (isset($echo_Main_Settings['custom_feed_timeout']) && $echo_Main_Settings['custom_feed_timeout'] != '') {
                            $feed->set_timeout($echo_Main_Settings['custom_feed_timeout']);
                        }
                        $feed->set_feed_url($feed_uri);
                        if (isset($echo_Main_Settings['echo_enable_caching']) && $echo_Main_Settings['echo_enable_caching'] == 'on') {
                            $feed->enable_cache(true);
                            $feed->set_cache_location(dirname(__FILE__) . "/res/cache");
                        }
                        else
                        {
                            $feed->enable_cache(false);
                        }
                        if (isset($echo_Main_Settings['echo_no_strip']) && $echo_Main_Settings['echo_no_strip'] == 'on') {
                            $feed->strip_htmltags(false);
                        }
                        $feed->init();
                        $feed->handle_content_type();
                        if ($feed->error()) {
                            echo_log_to_file('Error in parsing RSS feed (custom method): ' . $feed->error() . ' for ' . $feed_uri);
                            if($auto == 1)
                            {
                                echo_clearFromList($param);
                            }
                            throw new Exception('Failed: ' . $feed_uri);
                        }
                    }
                    elseif ($current_custom_simplepie == '2')
                    {
                        add_action('wp_feed_options', 'echo_add_feed_options', 10,2);
                        set_transient( 'echo_use_proxy', $use_proxy, '3600' );
                        set_transient( 'echo_user_agent_cust', $user_agent_cust, '3600' );
                        $feed = fetch_feed ( $feed_uri );
                        remove_action('wp_feed_options', 'echo_add_feed_options', 10);
                        if (is_wp_error ( $feed )){
                            echo_log_to_file('Error in parsing RSS feed (built-in method): ' . $feed->get_error_message() . ' for ' . $feed_uri);
                            if($auto == 1)
                            {
                                echo_clearFromList($param);
                            }
                            throw new Exception('Failed: ' . $feed_uri);
                        }
                    }
                    elseif ($current_custom_simplepie == '3')
                    {
                        try
                        {
                            set_transient( 'echo_user_agent_cust', $user_agent_cust, '3600' ); 
                            if (isset($echo_Main_Settings['echo_enable_caching']) && $echo_Main_Settings['echo_enable_caching'] == 'on') {
                                Echo_RSS_Feed::$cacheDir = dirname(__FILE__) . "/res/cache";
                                Echo_RSS_Feed::$cacheExpire = '1 hour';
                            }
                            $feed = Echo_RSS_Feed::load($feed_uri);
                            if ($feed === false){
                                echo_log_to_file('Error in parsing RSS feed (Echo_RSS_Feed): ' . $feed_uri);
                                if($auto == 1)
                                {
                                    echo_clearFromList($param);
                                }
                                throw new Exception('Echo_RSS_Feed loading failed');
                            }
                        }
                        catch(Exception $e)
                        {
                            throw new Exception('Error in parsing RSS feed (experimental method): ' . $e->getMessage() . ' for ' . $feed_uri);
                        }
                    }
                    $is_galerts = false;
                    if(stristr($feed_uri, 'google.com/alerts/feeds') !== false)
                    {
                        $is_galerts = true;
                    }
                    if ($current_custom_simplepie == '3')
                    {
                        if(isset($feed->channel))
                        {
                            $feed     = $feed->channel;
                        }
                        elseif(isset($feed->Channel))
                        {
                            $feed     = $feed->Channel;
                        }
                        if(isset($feed->title))
                        {
                            $feed_title       = $feed->title->__toString();
                        }
                        else
                        {
                            $feed_title = '';
                        }
                        if(isset($feed->description))
                        {
                            $feed_description = $feed->description->__toString();
                        }
                        else
                        {
                            $feed_description = '';
                        }
                        $feed_logo = '';
                        if(isset($feed->author->name))
                        {
                            $author = $feed->author->name->__toString();
                        }
                        else
                        {
                            $author = '';
                        }
                        if(isset($feed->author->uri))
                        {
                            $author_link = $feed->author->uri->__toString();
                        }
                        else
                        {
                            $author_link = '';
                        }
                        $author_email = '';
                        $entry_cnt = 0;
                        $item_cnt = 0;
                        $items = array();
                        foreach ($feed->item as $item)
                        {
                            $items[] = $item;
                        }
                        if(count($items) == 0)
                        {
                            foreach ($feed->entry as $fentry)
                            {
                                $items[] = $fentry;
                            }
                        }
                        if(count($items) == 0)
                        {
                            foreach ($feed->Channel->item as $fentry)
                            {
                                $items[] = $fentry;
                            }
                        }
                        if(count($items) == 0)
                        {
                            foreach ($feed->channel->item as $fentry)
                            {
                                $items[] = $fentry;
                            }
                        }
                        if(count($items) == 0)
                        {
                            echo_log_to_file('Error in interpreting RSS feed (Echo_RSS_Feed): ' . print_r($feed_uri, true));
                            if($auto == 1)
                            {
                                echo_clearFromList($param);
                            }
                            throw new Exception('Failed Echo_RSS_Feed: ' . $feed_uri);
                        }
                        $feed_fcontent = false;
                    }
                    else
                    {
                        $feed_title       = $feed->get_title();
                        $feed_description = $feed->get_description();
                        $feed_logo        = $feed->get_image_url();
                        if($feed_logo == null)
                        {
                            $feed_logo = '';
                        }
                        if ($fauthor = $feed->get_author()) {
                            $author = $fauthor->get_name();
                            if (method_exists($fauthor, 'get_link')) {
                                $author_link = echo_encodeURI($fauthor->get_link());
                            } else {
                                $author_link = '';
                            }
                            if (method_exists($fauthor, 'get_email')) {
                                $author_email = $fauthor->get_email();
                            } else {
                                $author_email = '';
                            }
                        } else {
                            $author       = '';
                            $author_link  = '';
                            $author_email = '';
                        }
                        $items     = $feed->get_items();
                        if ((isset($echo_Main_Settings['custom_feed_tag_list']) && $echo_Main_Settings['custom_feed_tag_list'] != '') || (isset($echo_Main_Settings['custom_tag_list']) && $echo_Main_Settings['custom_tag_list'] != '') || $custom_feed_tag_list != '' || $custom_tag_list != '')
                        {
                            $feed_fcontent = $feed->get_raw_data();
                        }
                        else
                        {
                            $feed_fcontent = false;
                        }
                    }
                    $saved_author       = $author;
                    $saved_author_link  = $author_link;
                    $saved_author_email = $author_email;
                    if ($current_custom_simplepie == '1') {
                        $feed->__destruct();
                    }
                    $feed = null;
                    unset($feed);
                    
                    $count = 1;
                    if (isset($echo_Main_Settings['append_enclosure']) && $echo_Main_Settings['append_enclosure'] == 'on') {
                        if (isset($echo_Main_Settings['iframe_resize_width']) && $echo_Main_Settings['iframe_resize_width'] !== '')
                        {
                            $iframe_resize_width = ' width="' . esc_attr($echo_Main_Settings['iframe_resize_width']) . '"'; 
                        }
                        else
                        {
                            $iframe_resize_width = '';
                        }
                        if (isset($echo_Main_Settings['iframe_resize_height']) && $echo_Main_Settings['iframe_resize_height'] !== '')
                        {
                            $iframe_resize_height = ' height="' . esc_attr($echo_Main_Settings['iframe_resize_height']) . '"'; 
                        }
                        else
                        {
                            $iframe_resize_height = '';
                        }
                    }
                    $init_date = time();
                    $skip_pcount = 0;
                    $skipped_pcount = 0;
                    if($ret_content == 1)
                    {
                        $item_xcounter = count($items);
                        $skip_pcount = rand(0, $item_xcounter-1);
                    }
                    if($default_author != '')
                    {
                        $dff_u = $default_author;
                    }
                    else
                    {
                        if (isset($echo_Main_Settings['def_user']) && is_numeric($echo_Main_Settings['def_user'])) {
                            $dff_u = $echo_Main_Settings['def_user'];
                        }
                        else
                        {
                            $dff_u = '1';
                        }
                    }
                    $user_name_type = $post_user_name;
                    if($feed_order == 'reversed')
                    {
                        $items = array_reverse($items);
                    }
                    elseif($feed_order == 'random')
                    {
                        shuffle($items);
                    }
                    if (isset($echo_Main_Settings['enable_detailed_logging'])) 
                    {
                        echo_log_to_file('Number of items discovered: ' . count($items));
                    }
                    foreach ($items as $item) {
                        $author = $saved_author;
                        $author_link = $saved_author_link;
                        $author_email = $saved_author_email;
                        if($ret_content == 1)
                        {
                            if($skip_pcount > $skipped_pcount)
                            {
                                $skipped_pcount++;
                                continue;
                            }
                        }
                        if ($count > intval($max)) {
                            break;
                        }
                        if ($current_custom_simplepie == '3')
                        {
                            if(isset($item->link))
                            {
                                $url = echo_encodeURI(trim($item->link->__toString()));
                            }
                            else
                            {
                                $url = '';
                            }
                        }
                        else
                        {
                            $url = echo_encodeURI(trim($item->get_permalink()));
                        }
                        if($try_final == '1' && $url != '')
                        {
                            $url = echo_get_final_url($url);
                        }
                        if ($current_custom_simplepie == '3')
                        {
                            if(isset($item->title))
                            {
                                $title = $item->title->__toString();
                            }
                            else
                            {
                                $title = '';
                            }
                        }
                        else
                        {
                            $title = $item->get_title();
                        }
                        
                        $update_me = false;
                        if (isset($echo_Main_Settings['check_title']) && $echo_Main_Settings['check_title'] == 'on')
                        {
                            if(trim($ids, '/') == $seed_news)
                            {
                                $xposts = get_posts(
                                    array(
                                        'post_type'              => 'cna_news',
                                        'title'                  => html_entity_decode($title),
                                        'post_status'            => 'all',
                                        'numberposts'            => 1,
                                        'update_post_term_cache' => false,
                                        'update_post_meta_cache' => false,           
                                        'orderby'                => 'post_date ID',
                                        'order'                  => 'ASC',
                                    )
                                );
                                if ( ! empty( $xposts ) ) {
                                    $zap = $xposts[0];
                                } else {
                                    $zap = null;
                                }
                                if($zap !== null)
                                {
                                    if (isset($echo_Main_Settings['enable_detailed_logging'])) 
                                    {
                                        echo_log_to_file('Post found already posted: ' . $title);
                                    }
                                    continue;
                                }
                                if(html_entity_decode($title) != $title)
                                {
                                    $xposts = get_posts(
                                        array(
                                            'post_type'              => 'cna_news',
                                            'title'                  => $title,
                                            'post_status'            => 'all',
                                            'numberposts'            => 1,
                                            'update_post_term_cache' => false,
                                            'update_post_meta_cache' => false,           
                                            'orderby'                => 'post_date ID',
                                            'order'                  => 'ASC',
                                        )
                                    );
                                    if ( ! empty( $xposts ) ) {
                                        $zap = $xposts[0];
                                    } else {
                                        $zap = null;
                                    }
                                    if($zap !== null)
                                    {
                                        if (isset($echo_Main_Settings['enable_detailed_logging'])) 
                                        {
                                            echo_log_to_file('Title found already posted: ' . $title);
                                        }
                                        continue;
                                    }
                                }
                            }
                            else
                            {
                                if (has_filter('echo_filter_dup_check') || (isset($echo_Main_Settings['do_not_check_duplicates']) && $echo_Main_Settings['do_not_check_duplicates'] == 'on')) 
                                {
                                }
                                else
                                {
                                    wp_suspend_cache_addition(true);
                                    if($ret_content == 0)
                                    {
                                        $posted_items    = array();
                                        $postsPerPage = 50000;
                                        $paged = 0;
                                        do
                                        {
                                            $postOffset = $paged * $postsPerPage;
                                            {
                                                $query     = array(
                                                    'post_status' => array(
                                                        'publish',
                                                        'draft',
                                                        'pending',
                                                        'trash',
                                                        'private',
                                                        'future'
                                                    ),
                                                    'numberposts' => $postsPerPage,
                                                    'fields' => 'ids',
                                                    'meta_key' => 'echo_item_title',
                                                    'meta_value' => $title,
                                                    'offset'  => $postOffset
                                                );
                                                if(trim($ids, '/') == $seed_news)
                                                {
                                                    $query['post_type'] = array('cna_news');
                                                }
                                                else
                                                {
                                                    $query['post_type'] = array('any');
                                                }
                                                $post_list = get_posts($query);
                                                foreach ($post_list as $post) {
                                                    $xtemp_url = get_post_meta($post, 'echo_item_title', true);
                                                    $posted_items[$xtemp_url] = $post;
                                                }
                                            }
                                            $paged++;
                                        }while(!empty($post_list));
                                    }
                                    wp_suspend_cache_addition(false);
                                    $post_list = null;
                                    unset($post_list);
                                }
                                if(has_filter('echo_filter_dup_check'))
                                {
                                    $continue_filter = false;
                                    $continue_filter = apply_filters( 'echo_filter_dup_check', $title );
                                    if($continue_filter === true)
                                    {
                                        if (isset($echo_Main_Settings['enable_detailed_logging'])) 
                                        {
                                            echo_log_to_file('Filtered title found already posted: ' . $title);
                                        }
                                        continue;
                                    }
                                }
                                else
                                {   
                                    if (isset($posted_items[$title])) {
                                        if($update_existing == '1')
                                        {
                                            $update_me = $posted_items[$title];
                                        }
                                        else
                                        {
                                            if (isset($echo_Main_Settings['enable_detailed_logging'])) {
                                                echo_log_to_file('Skipping post "' . esc_html($title) . '", because it is already posted');
                                            }
                                            continue;
                                        }
                                    }
                                }
                            }
                        }
                        else
                        {
                            if (has_filter('echo_filter_dup_check') || (isset($echo_Main_Settings['do_not_check_duplicates']) && $echo_Main_Settings['do_not_check_duplicates'] == 'on')) 
                            {
                            }
                            else
                            {
                                if($ret_content == 0)
                                {
                                    if (!isset($echo_Main_Settings['no_dup_titles']) || $echo_Main_Settings['no_dup_titles'] != 'on')
                                    {
                                        $postsPerPage = 50000;
                                        $paged = 0;
                                        wp_suspend_cache_addition(true);
                                        do
                                        {
                                            $postOffset = $paged * $postsPerPage;
                                            {
                                                $query     = array(
                                                    'post_status' => array(
                                                        'publish',
                                                        'draft',
                                                        'pending',
                                                        'trash',
                                                        'private',
                                                        'future'
                                                    ),
                                                    'numberposts' => $postsPerPage,
                                                    'fields' => 'ids',
                                                    'meta_key' => 'echo_post_url',
                                                    'meta_value' => $url,
                                                    'offset'  => $postOffset
                                                );
                                                if(trim($ids, '/') == $seed_news)
                                                {
                                                    $query['post_type'] = array('cna_news');
                                                }
                                                else
                                                {
                                                    $query['post_type'] = array('any');
                                                }
                                                $post_list = get_posts($query);
                                                foreach ($post_list as $post) {
                                                    $xtemp_url = get_post_meta($post, 'echo_post_full_url', true);
                                                    if($xtemp_url == false && $xtemp_url == '')
                                                    {
                                                        $xtemp_url = get_post_meta($post, 'echo_post_url', true);
                                                    }
                                                    $posted_items[$xtemp_url] = $post;
                                                }
                                            }
                                            $paged++;
                                        }while(!empty($post_list));
                                    }
                                }
                                wp_suspend_cache_addition(false);
                                $post_list = null;
                                unset($post_list);
                            }
                            if(has_filter('echo_filter_dup_check'))
                            {
                                $continue_filter = false;
                                $continue_filter = apply_filters( 'echo_filter_dup_check', $url );
                                if($continue_filter === true)
                                {
                                    if (isset($echo_Main_Settings['enable_detailed_logging'])) 
                                    {
                                        echo_log_to_file('Filtered title found already posted: ' . $title);
                                    }
                                    continue;
                                }
                            }
                            else
                            {   
                                if ($url != '' && isset($posted_items[$url])) {
                                    if($update_existing == '1')
                                    {
                                        $update_me = $posted_items[$url];
                                    }
                                    else
                                    {
                                        if (isset($echo_Main_Settings['enable_detailed_logging'])) {
                                            echo_log_to_file('Skipping post "' . esc_html($title) . '", because it is already posted');
                                        }
                                        continue;
                                    }
                                }
                            }
                        }
                        $feed_user_name = '';
                        if ($current_custom_simplepie == '3')
                        {
                            if(isset($item->author))
                            {
                                $feed_user_name = $item->author->__toString();
                            }
                        }
                        else
                        {
                            if ($fauthor = $item->get_author()) 
                            {
                                $feed_user_name = $fauthor->get_name();
                            }
                        }
                        if(!empty($feed_user_name))
                        {
                            if ($current_custom_simplepie == '3')
                            {
                                if(isset($item->author))
                                {
                                    $author = $item->author->__toString();
                                }
                            }
                            else
                            {
                                if ($fauthor = $item->get_author()) {
                                    $author = $fauthor->get_name();
                                    $author_link = echo_encodeURI($fauthor->get_link());
                                    $author_email = $fauthor->get_email();
                                    if($author_email == null)
                                    {
                                        $author_email = '';
                                    }
                                }
                                else
                                {
                                    $author = $dff_u;
                                }
                            }
                        }
                        if ($current_custom_simplepie == '3')
                        {
                            if(isset($item->description))
                            {
                                $description = strip_tags($item->description->__toString());
                            }
                            else
                            {
                                $description = '';
                            }
                        }
                        else
                        {
                            $dsc = $item->get_description();
                            if(!empty($dsc))
                            {
                                $description = strip_tags($dsc);
                            }
                            else
                            {
                                $description = '';
                            }
                        }
                        $short_url = $url;
                        if ($current_custom_simplepie == '3')
                        {
                            if(isset($item->content))
                            {
                                $content = $item->content->__toString();
                            }
                            elseif(isset($item->{'content:encoded'}))
                            {
                                $content = $item->{'content:encoded'}->__toString();
                            }
                            elseif(isset($item->description))
                            {
                                $content = $item->description->__toString();
                            }
                            else
                            {
                                $content = '';
                            }
                        }
                        else
                        {
                            $content = $item->get_content();
                        }
                        if ($current_custom_simplepie != '3' && isset($echo_Main_Settings['append_enclosure']) && $echo_Main_Settings['append_enclosure'] == 'on') {
                            $enclosures = $item->get_enclosures();
                            if(is_array($enclosures))
                            {
                                foreach ($enclosures as $enclosure)
                                {
                                    if($content == '')
                                    {
                                        $content = $enclosure->get_description();
                                    }
                                    if($description == '')
                                    {
                                        $description = $enclosure->get_description();
                                    }
                                    $za_link = echo_encodeURI($enclosure->get_link());
                                    preg_match_all('/https:\/\/www.youtube.com\/v\/([^?"\'&]*)/i', $za_link, $matches);
                                    if(isset($matches[1][0]))
                                    {
                                        $content .= '<br/><iframe ' . $iframe_resize_width . $iframe_resize_height . ' src="https://www.youtube.com/embed/' . $matches[1][0] . '" frameborder="0" allowfullscreen></iframe>';
                                    }
                                    else
                                    {
                                        if(echo_endsWith($za_link, '.webp') || echo_endsWith($za_link, '.jpeg') || echo_endsWith($za_link, '.jpg') || echo_endsWith($za_link, '.gif') || echo_endsWith($za_link, '.png') || echo_endsWith($za_link, '.jpe') || echo_endsWith($za_link, '.tif') || echo_endsWith($za_link, '.tiff') || echo_endsWith($za_link, '.svg') || echo_endsWith($za_link, '.ico'))
                                        {
                                            $content .= '<br/><img src="' . esc_url_raw($za_link) . '" alt="image"/>';
                                        }
                                        else
                                        {
                                            if(echo_endsWith($za_link, '.mp3') || echo_endsWith($za_link, '.wav') || echo_endsWith($za_link, '.mid') || echo_endsWith($za_link, '.midi') || echo_endsWith($za_link, '.flac') || echo_endsWith($za_link, '.wma') || echo_endsWith($za_link, '.aac') || echo_endsWith($za_link, '.m4a'))
                                            {
                                                $content .= '<br/><audio controls><source src="' . esc_url_raw($za_link) . '" type="audio/mpeg">Your browser does not support the audio element.</audio>';
                                            }
                                            else
                                            {
                                                if(echo_endsWith($za_link, '.mpg') || echo_endsWith($za_link, '.mpeg') || echo_endsWith($za_link, '.avi') || echo_endsWith($za_link, '.wmv') || echo_endsWith($za_link, '.mov') || echo_endsWith($za_link, '.rm') || echo_endsWith($za_link, '.ram') || echo_endsWith($za_link, '.swf') || echo_endsWith($za_link, '.ogg') || echo_endsWith($za_link, '.mp4'))
                                                {
                                                    $content .= '<br/><video controls><source src="' . esc_url_raw($za_link) . '" type="video/mp4">Your browser does not support the video tag.</video>';
                                                }
                                                else
                                                {
                                                    $content .= '<br/><embed class="crf_pointer" src="' . esc_url_raw($za_link) . '" type="' . esc_html($enclosure->get_real_type()) . '"' . $iframe_resize_width . $iframe_resize_height . '></embed>';
                                                }
                                            }
                                        }
                                    }
                                    
                                }
                            }
                        }
                        elseif($current_custom_simplepie != '3' && ($description == '' || $content == ''))
                        {
                            $enclosures = $item->get_enclosures();
                            if(is_array($enclosures))
                            {
                                foreach ($enclosures as $enclosure)
                                {
                                    if($content == '')
                                    {
                                        $content = $enclosure->get_description();
                                    }
                                    if($description == '')
                                    {
                                        $description = $enclosure->get_description();
                                    }
                                }
                            }
                        }
                        if (isset($echo_Main_Settings['strip_scripts']) && $echo_Main_Settings['strip_scripts'] == 'on') {
                            $content = preg_replace('{<ins.*?ins>}s', '', $content);
                            $content = preg_replace('{<ins.*?>}s', '', $content);
                            $content = preg_replace('{<script[\s\S]*?\/\s?script>}s', '', $content);
                            $content = preg_replace('{\(adsbygoogle.*?\);}s', '', $content);
                        }
                        if ($current_custom_simplepie == '3')
                        {
                            if(isset($item->timestamp) && $item->timestamp->__toString() != '')
                            {
                                $date = date("Y-m-d H:i:s", (int)$item->timestamp->__toString());
                            }
                            elseif(isset($item->pubDate) && $item->pubDate->__toString() != '')
                            {
                                $pubzdate = strtotime($item->pubDate->__toString());
                                if($pubzdate !== false)
                                {
                                    $date = date("Y-m-d H:i:s", $pubzdate);
                                }
                                else
                                {
                                    $date = '';
                                }
                            }
                            else
                            {
                                $date = '';
                            }
                        }
                        else
                        {
                            $date = $item->get_date();
                        }
                        if($date_index != '')
                        {
                            $old_d = strtotime($date);
                            if($old_d !== false)
                            {
                                $newtime = $old_d + ($date_index * 60 * 60);
                                $date = date("Y-m-d H:i:s", $newtime);
                            }
                        }
                        if (isset($echo_Main_Settings['skip_old']) && $echo_Main_Settings['skip_old'] == 'on' && isset($echo_Main_Settings['skip_year']) && $echo_Main_Settings['skip_year'] !== '' && isset($echo_Main_Settings['skip_month']) && isset($echo_Main_Settings['skip_day'])) {
                            $old_date      = $echo_Main_Settings['skip_day'] . '-' . $echo_Main_Settings['skip_month'] . '-' . $echo_Main_Settings['skip_year'];
                            $time_date     = strtotime($date);
                            $time_old_date = strtotime($old_date);
                            if ($time_date !== false && $time_old_date !== false) {
                                if ($time_date < $time_old_date) {
                                    if (isset($echo_Main_Settings['enable_detailed_logging'])) {
                                        echo_log_to_file('Skipping post "' . esc_html($title) . '", because it is older than ' . $old_date . ' - posted on ' . $date);
                                    }
                                    continue;
                                }
                            }
                        }
                        if (isset($echo_Main_Settings['skip_new']) && $echo_Main_Settings['skip_new'] == 'on' && isset($echo_Main_Settings['skip_year_new']) && $echo_Main_Settings['skip_year_new'] !== '' && isset($echo_Main_Settings['skip_month_new']) && isset($echo_Main_Settings['skip_day_new'])) {
                            $new_date      = $echo_Main_Settings['skip_day_new'] . '-' . $echo_Main_Settings['skip_month_new'] . '-' . $echo_Main_Settings['skip_year_new'];
                            $time_date     = strtotime($date);
                            $time_new_date = strtotime($new_date);
                            if ($time_date !== false && $time_new_date !== false) {
                                if ($time_date > $time_new_date) {
                                    if (isset($echo_Main_Settings['enable_detailed_logging'])) {
                                        echo_log_to_file('Skipping post "' . esc_html($title) . '", because it is newer than ' . $new_date . ' - posted on ' . $date);
                                    }
                                    continue;
                                }
                            }
                        }
                        if($skip_older != '')
                        {
                            $time_date         = strtotime($date);
                            $time_old_date     = strtotime($skip_older);
                            if ($time_date !== false && $time_old_date !== false) {
                                if ($time_date < $time_old_date) {
                                    if (isset($echo_Main_Settings['enable_detailed_logging'])) {
                                        echo_log_to_file('Skipping post "' . esc_html($title) . '", because it is older than ' . $skip_older . ' (rule defined) - posted on ' . $date);
                                    }
                                    continue;
                                }
                            }
                        }
                        if($skip_newer != '')
                        {
                            $time_date         = strtotime($date);
                            $time_new_date     = strtotime($skip_newer);
                            if ($time_date !== false && $time_new_date !== false) {
                                if ($time_date > $time_new_date) {
                                    if (isset($echo_Main_Settings['enable_detailed_logging'])) {
                                        echo_log_to_file('Skipping post "' . esc_html($title) . '", because it is newer than ' . $skip_newer . ' (rule defined) - posted on ' . $date);
                                    }
                                    continue;
                                }
                            }
                        }
                        $check_cats = array();
                        $extra_categories = '';
                        if ($current_custom_simplepie != '3')
                        {
                            $feed_cats = $item->get_categories();
                            if(is_array($feed_cats) && count($feed_cats) > 0)
                            {
                                foreach ($feed_cats as $category)
                                {
                                    $extra_categories .= $category->get_label() . ',';
                                    $check_cats[] = $category->get_label();
                                }
                            }
                        }
                        else
                        {
                            $feed_cats = array();
                            if(isset($item->category))
                            {
                                foreach($item->category as $cata)
                                {
                                    $feed_cats[] = $cata->__toString();
                                    $check_cats[] = $cata->__toString();
                                    $extra_categories .= $cata->__toString() . ',';
                                }
                                if(count($feed_cats) == 0)
                                {
                                    $feed_cats[] = $item->category->__toString();
                                    $check_cats[] = $item->category->__toString();
                                    $extra_categories .= $item->category->__toString() . ',';
                                }
                            }
                        }
                        $extra_categories = trim($extra_categories, ',');
                        
                        if ($banned_cats != '') {
                            $continue    = false;
                            $banned_cats = str_replace('\\,', '!@CR@CR@CR@!', $banned_cats);
                            $banned_list = explode(',', $banned_cats);
                            foreach ($banned_list as $banned_cat) 
                            {
                                $banned_cat = str_replace('!@CR@CR@CR@!', ',', $banned_cat);
                                if (in_array(trim($banned_cat), $check_cats)) 
                                {
                                    if (isset($echo_Main_Settings['enable_detailed_logging'])) {
                                        echo_log_to_file('Skipping post "' . esc_html($title) . '", because it\'s category is banned: ' . $banned_cat);
                                    }
                                    $continue = true;
                                    break;
                                }
                            }
                            if ($continue === true) {
                                continue;
                            }
                        }
                        if ($required_cats != '') {
                            $continue    = true;
                            $required_cats = str_replace('\\,', '!@CR@CR@CR@!', $required_cats);
                            $req_list = explode(',', $required_cats);
                            foreach ($req_list as $req_cat) 
                            {
                                $req_cat = str_replace('!@CR@CR@CR@!', ',', $req_cat);
                                if (in_array(trim($req_cat), $check_cats)) 
                                {
                                    $continue = false;
                                    break;
                                }
                            }
                            if ($continue === true) {
                                if (isset($echo_Main_Settings['enable_detailed_logging'])) {
                                    echo_log_to_file('Skipping post "' . esc_html($title) . '", because it\'s category is not found in the required categories list: ' . print_r($required_cats, true));
                                }
                                continue;
                            }
                        }
                        $fxml_match = array();
                        $custom_tag_var_list = array();
                        if($custom_tag_list == '' && isset($echo_Main_Settings['custom_tag_list']) && $echo_Main_Settings['custom_tag_list'] != '')
                        {
                            $custom_tag_list = $echo_Main_Settings['custom_tag_list'];
                        }
                        if ($current_custom_simplepie != '3' && $custom_tag_list != '') {
                            $ctl = explode(',', $custom_tag_list);
                            
                            foreach($ctl as $unctl)
                            {
                                $unctl = trim($unctl);
                                if($unctl != '')
                                {
                                    $unctl_orig = $unctl;
                                    $unctl = explode('->', $unctl);
                                    $children_found = $unctl;
                                    $unctl = $unctl[0];
                                    if($custom_tag_separator != '')
                                    {
                                        $tag_separator = $custom_tag_separator;
                                    }
                                    else
                                    {
                                        if (isset($echo_Main_Settings['custom_tag_separator']) && $echo_Main_Settings['custom_tag_separator'] != '') {
                                            $tag_separator = $echo_Main_Settings['custom_tag_separator'];
                                        }
                                        else
                                        {
                                            $tag_separator = ':';
                                        }
                                    }
                                    $multiple_ns = explode($tag_separator, $unctl);
                                    if(isset($multiple_ns[1]))
                                    {
                                        $name_space = $multiple_ns[0];
                                        if($feed_fcontent !== false)
                                        {
                                            preg_match('#xmlns:' . $name_space . '="([^"]*?)"#', $feed_fcontent, $fxml_match);
                                            if(isset($fxml_match[1]))
                                            {
                                                $name_space = $fxml_match[1];
                                            }
                                        }
                                        $tag_name = $multiple_ns[1];
                                    }
                                    else
                                    {
                                        $name_space = '';
                                        $tag_name = $unctl;
                                    }
                                    if($custom_attr_separator != '')
                                    {
                                        $attr_separator = $custom_attr_separator;
                                    }
                                    else
                                    {
                                        if (isset($echo_Main_Settings['custom_attr_separator']) && $echo_Main_Settings['custom_attr_separator'] != '') {
                                            $attr_separator = $echo_Main_Settings['custom_attr_separator'];
                                        }
                                        else
                                        {
                                            $attr_separator = '*';
                                        }
                                    }
                                    $xattr = '';
                                    $attr_jam = explode($attr_separator, $tag_name);
                                    if(isset($attr_jam[1]))
                                    {
                                        $xattr = $attr_jam[1];
                                        $tag_name = $attr_jam[0];
                                    }
                                    $custom_tag_data = $item->get_item_tags($name_space, $tag_name);
                                    $ctd_value = '';
                                    if(is_array($custom_tag_data))
                                    {
                                        foreach($custom_tag_data as $ctd)
                                        {
                                            if($xattr != '')
                                            {
                                                if(isset($ctd['attribs']))
                                                {
                                                    foreach($ctd['attribs'] as $feac)
                                                    {
                                                        if(isset($feac[$xattr]))
                                                        {
                                                            $ctd_value .= str_replace(',', ' -', $feac[$xattr]) . ',';
                                                        }
                                                    }
                                                }
                                            }
                                            else
                                            {
                                                if(isset($children_found[1]) && isset($ctd['child']) && count($ctd['child']) > 0)
                                                {
                                                    $mother_index = $ctd;
                                                    $going_down = array();
                                                    array_shift($children_found);
                                                    foreach($children_found as $cf)
                                                    {
                                                        $name_space = '';
                                                        $multiple_ns = explode($tag_separator, $cf);
                                                        if(isset($multiple_ns[1]))
                                                        {
                                                            $name_space = $multiple_ns[0];
                                                            if($feed_fcontent !== false)
                                                            {
                                                                preg_match('#xmlns:' . $name_space . '="([^"]*?)"#', $feed_fcontent, $fxml_match);
                                                                if(isset($fxml_match[1]))
                                                                {
                                                                    $going_down[] = $fxml_match[1];
                                                                }
                                                            }
                                                            $going_down[] = $multiple_ns[1];
                                                        }
                                                        else
                                                        {
                                                            $going_down[] = $cf;
                                                        }
                                                    }
                                                    if(count($going_down) > 0)
                                                    {
                                                        $mother_index = $ctd['child'];
                                                        foreach($going_down as $gdindex)
                                                        {
                                                            if(isset($mother_index[$gdindex]))
                                                            {
                                                                $mother_index = $mother_index[$gdindex];
                                                            }
                                                            else
                                                            {
                                                                if(isset($mother_index[0]['child'][$gdindex]))
                                                                {
                                                                    $mother_index = $mother_index[0]['child'][$gdindex];
                                                                }
                                                                else
                                                                {
                                                                    if (isset($echo_Main_Settings['enable_detailed_logging'])) {
                                                                        echo_log_to_file('Child index ' . $gdindex . ' not found in: ' . print_r($mother_index, true));
                                                                    } 
                                                                }
                                                            }
                                                        }
                                                        if(isset($mother_index[0]) && !isset($mother_index['data']))
                                                        {
                                                            $mother_index = $mother_index[0];
                                                        }
                                                        
                                                    }
                                                    if(isset($mother_index['data']))
                                                    {
                                                        $ctd_value .= str_replace(',', ' -', $mother_index['data']) . ',';
                                                    }
                                                    elseif(isset($children_found[0]))
                                                    {
                                                        $firstElement = reset($mother_index);
                                                        if(is_array($firstElement) && isset($firstElement[$children_found[0]]))
                                                        {
                                                            foreach($firstElement[$children_found[0]] as $cdatax)
                                                            {
                                                                if(isset($cdatax['data']))
                                                                {
                                                                    $ctd_value .= str_replace(',', ' -', $cdatax['data']) . ',';
                                                                }
                                                            }
                                                        }
                                                    }
                                                }
                                                else
                                                {
                                                    $ctd_value .= str_replace(',', ' -', $ctd['data']) . ',';
                                                }
                                            }
                                        }
                                        $ctd_value = trim($ctd_value, ',');
                                    }
                                    $custom_tag_var_list[$unctl_orig] = $ctd_value;
                                }
                            }
                        }
                        $custom_feed_tag_var_list = array();
                        if($custom_feed_tag_list == '' && isset($echo_Main_Settings['custom_feed_tag_list']) && $echo_Main_Settings['custom_feed_tag_list'] != '')
                        {
                            $custom_feed_tag_list = $echo_Main_Settings['custom_feed_tag_list'];
                        }
                        if ($current_custom_simplepie != '3' && $custom_feed_tag_list != '') {
                            $ctl = explode(',', $custom_feed_tag_list);
                            foreach($ctl as $unctl)
                            {
                                $unctl = trim($unctl);
                                if($unctl != '')
                                {
                                    $unctl_orig = $unctl;
                                    $unctl = explode('->', $unctl);
                                    $children_found = $unctl;
                                    $unctl = $unctl[0];
                                    if($custom_tag_separator != '')
                                    {
                                        $tag_separator = $custom_tag_separator;
                                    }
                                    else
                                    {
                                        if (isset($echo_Main_Settings['custom_tag_separator']) && $echo_Main_Settings['custom_tag_separator'] != '') {
                                            $tag_separator = $echo_Main_Settings['custom_tag_separator'];
                                        }
                                        else
                                        {
                                            $tag_separator = ':';
                                        }
                                    }
                                    $multiple_ns = explode($tag_separator, $unctl);
                                    if(isset($multiple_ns[1]))
                                    {
                                        $name_space = $multiple_ns[0];
                                        if($feed_fcontent !== false)
                                        {
                                            preg_match('#xmlns:' . $name_space . '="([^"]*?)"#', $feed_fcontent, $fxml_match);
                                            if(isset($fxml_match[1]))
                                            {
                                                $name_space = $fxml_match[1];
                                            }
                                        }
                                        $tag_name = $multiple_ns[1];
                                    }
                                    else
                                    {
                                        $name_space = '';
                                        $tag_name = $unctl;
                                    }
                                    if($custom_attr_separator != '')
                                    {
                                        $attr_separator = $custom_attr_separator;
                                    }
                                    else
                                    {
                                        if (isset($echo_Main_Settings['custom_attr_separator']) && $echo_Main_Settings['custom_attr_separator'] != '') {
                                            $attr_separator = $echo_Main_Settings['custom_attr_separator'];
                                        }
                                        else
                                        {
                                            $attr_separator = '*';
                                        }
                                    }
                                    $xattr = '';
                                    $attr_jam = explode($attr_separator, $tag_name);
                                    if(isset($attr_jam[1]))
                                    {
                                        $xattr = $attr_jam[1];
                                        $tag_name = $attr_jam[0];
                                    }
                                    if(method_exists($item,'get_feed_tags'))
                                    {
                                        $custom_tag_data = $item->get_feed_tags($name_space, $tag_name); 
                                        $ctd_value = '';
                                        if(is_array($custom_tag_data))
                                        {  
                                            foreach($custom_tag_data as $ctd)
                                            {
                                                if($xattr != '')
                                                {
                                                    if(isset($ctd['attribs']))
                                                    {
                                                        foreach($ctd['attribs'] as $feac)
                                                        {
                                                            if(isset($feac[$xattr]))
                                                            {
                                                                $ctd_value .= str_replace(',', ' -', $feac[$xattr]) . ',';
                                                            }
                                                        }
                                                    }
                                                }
                                                else
                                                {
                                                    if(isset($children_found[1]) && isset($ctd['child']) && count($ctd['child']) > 0)
                                                    {
                                                        $mother_index = $ctd;
                                                        $going_down = array();
                                                        array_shift($children_found);
                                                        foreach($children_found as $cf)
                                                        {
                                                            $name_space = '';
                                                            $multiple_ns = explode($tag_separator, $cf);
                                                            if(isset($multiple_ns[1]))
                                                            {
                                                                $name_space = $multiple_ns[0];
                                                                if($feed_fcontent !== false)
                                                                {
                                                                    preg_match('#xmlns:' . $name_space . '="([^"]*?)"#', $feed_fcontent, $fxml_match);
                                                                    if(isset($fxml_match[1]))
                                                                    {
                                                                        $going_down[] = $fxml_match[1];
                                                                    }
                                                                }
                                                                $going_down[] = $multiple_ns[1];
                                                            }
                                                            else
                                                            {
                                                                $going_down[] = $cf;
                                                            }
                                                        }
                                                        if(count($going_down) > 0)
                                                        {
                                                            $mother_index = $ctd['child'];
                                                            foreach($going_down as $gdindex)
                                                            {
                                                                if(isset($mother_index[$gdindex]))
                                                                {
                                                                    $mother_index = $mother_index[$gdindex];
                                                                }
                                                                else
                                                                {
                                                                    if(isset($mother_index[0]['child'][$gdindex]))
                                                                    {
                                                                        $mother_index = $mother_index[0]['child'][$gdindex];
                                                                    }
                                                                    else
                                                                    {
                                                                        if (isset($echo_Main_Settings['enable_detailed_logging'])) {
                                                                            echo_log_to_file('Child feed index ' . $gdindex . ' not found in: ' . print_r($mother_index, true));
                                                                        } 
                                                                    }
                                                                }
                                                            }
                                                            if(isset($mother_index[0]) && !isset($mother_index['data']))
                                                            {
                                                                $mother_index = $mother_index[0];
                                                            }
                                                        }
                                                        $ctd_value .= str_replace(',', ' -', $mother_index['data']) . ',';
                                                    }
                                                    else
                                                    {
                                                        $ctd_value .= str_replace(',', ' -', $ctd['data']) . ',';
                                                    }
                                                }
                                            }
                                            $ctd_value = trim($ctd_value, ',');
                                        }
                                        $custom_feed_tag_var_list[$unctl_orig] = $ctd_value;
                                    }
                                }
                            }
                        }
                        $my_post                          = array();
                        $my_post['post_enclosures'] = array();
                        if ($current_custom_simplepie != '3' && isset($echo_Main_Settings['add_attachments']) && $echo_Main_Settings['add_attachments'] == 'on') {
                            $my_post['post_enclosures'] = $item->get_enclosures();
                            $imgaarray = array();
                            preg_match_all("/src=['\"]([^\"']*?)['\"]/i", $content, $imgaarray) ;
                            if(isset($imgaarray[1][0]))
                            {
                                $my_post['post_enclosures'] = array_merge($my_post['post_enclosures'], $imgaarray[1]);
                            }
                        }
                        $my_post['echo_enable_pingbacks'] = $enable_pingback;
                        $my_post['post_type']             = $post_type;
                        $my_post['comment_status']        = $accept_comments;
                        if (isset($echo_Main_Settings['draft_first']) && $echo_Main_Settings['draft_first'] == 'on')
                        {
                            if($post_status == 'publish')
                            {
                                $draft_me = true;
                                $my_post['post_status'] = 'draft';
                            }
                            else
                            {
                                $my_post['post_status']   = $post_status;
                            }
                        }
                        else
                        {
                            $my_post['post_status'] = $post_status;
                        }
                        if($user_name_type == 'rnd-echo')
                        {
                            $randid = echo_display_random_user();
                            if($randid === false)
                            {
                                $post_user_name               = $dff_u;
                            }
                            else
                            {
                                $post_user_name               = $randid->ID;
                            }
                        }
                        elseif($user_name_type == 'feed-echo')
                        {
                            if($author != '')
                            {
                                $xauthor = sanitize_user( $author );
                                $xauthor = apply_filters( 'pre_user_login', $xauthor );
                                $xauthor = trim( $xauthor );
                                if(username_exists( $xauthor ))
                                {
                                    $user_id_t = get_user_by('login', $xauthor);
                                    if($user_id_t)
                                    {
                                        $post_user_name = $user_id_t->ID;
                                    }
                                    else
                                    {
                                        $post_user_name = $dff_u;
                                    }
                                }
                                else
                                {
                                    $palphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890!@#$%^*()-+=_?><,.;:}{][';
                                    $ppass = '';
                                    $alphaLength = strlen($palphabet) - 1;
                                    for ($ipass = 0; $ipass < 8; $ipass++) 
                                    {
                                        $npass = rand(0, $alphaLength);
                                        $ppass .= $palphabet[$npass];
                                    }
                                    $random_email = echo_generate_random_email();
                                    $user_data = array(
                                        'user_login' => $xauthor,
                                        'user_pass'  => $ppass,
                                        'user_email' => $random_email,
                                    );
                                    $curr_id = wp_insert_user( $user_data );
                                    if ( is_int($curr_id) )
                                    {
                                        $u = new WP_User($curr_id);
                                        $u->remove_role('subscriber');
                                        $u->add_role('author');
                                        $post_user_name               = $curr_id;
                                    }
                                    else
                                    {
                                        $post_user_name               = $dff_u;
                                    }
                                }
                            }
                            else
                            {
                                $post_user_name               = $dff_u;
                            }
                        }
                        $my_post['post_author'] = $post_user_name;
                        $item_tags = '';
                        if(is_array($feed_cats) && count($feed_cats) > 0)
                        {
                            if (isset($echo_Main_Settings['skip_first_tag']) && $echo_Main_Settings['skip_first_tag'] == 'on')
                            {
                                $xcatcnt = -1;
                            }
                            else
                            {
                                $xcatcnt = 0;
                            }
                            foreach ($feed_cats as $xcategory)
                            {
                                $xcatcnt++;
                                if($xcatcnt == 0)
                                {
                                    continue;
                                }
                                if ($current_custom_simplepie != '3')
                                {
                                    $item_tags .= $xcategory->get_label() . ',';
                                }
                                else
                                {
                                    $item_tags .= $xcategory . ',';
                                }
                            }
                            $item_tags = trim($item_tags, ',');
                        }
                        $orig_content = '';
                        $my_post['echo_post_url']  = $short_url;
                        $my_post['echo_post_full_url'] = $url;
                        $my_post['echo_post_date'] = $date;
                        $moretags = '';
                        $htmlcontent = '';
                        $html_dl_failed = false;
                        if (($full_content == '1' || $full_title == '1') && $url != '') 
                        {
                            if($full_title != '1')
                            {
                                $my_title = 'skip';
                            }
                            else
                            {
                                $my_title = 'get_me';
                            }
                            $url = echo_fix_google_links($url, $feed_uri);
                            if (isset($echo_Main_Settings['enable_detailed_logging'])) {
                                echo_log_to_file('Getting full content from: ' . $url);
                            }
                            if(!strstr($url, 'news.google.com/rss/articles/'))
                            {
                                $exp_content = echo_get_full_content($url, $type, htmlspecialchars_decode($expre), $single, $inner, $encoding, $user_agent_cust, $use_phantom, $custom_cookie, $use_proxy, $is_galerts, $tag_type, $tag_expre, $moretags, $htmlcontent, $html_dl_failed, $my_title, $htuser_pass);
                                if ($full_content == '1')
                                {
                                    if($exp_content !== FALSE && $exp_content != '') 
                                    {
                                        $orig_content = $content;
                                        $content = $exp_content;
                                    }
                                    else
                                    {
                                        if(isset($echo_Main_Settings['skip_no_full']) && $echo_Main_Settings['skip_no_full'] == 'on')
                                        {
                                            if (isset($echo_Main_Settings['enable_detailed_logging'])) {
                                                echo_log_to_file('Skipping post because full content cannot be extracted: ' . $url);
                                            } 
                                            continue;
                                        }
                                    }
                                }
                            }
                            else
                            {
                                if (isset($echo_Main_Settings['enable_detailed_logging'])) {
                                    echo_log_to_file('Skipping post because Google News full content cannot be extracted: ' . $url);
                                } 
                                continue;
                            }
                            if($full_title == '1' && $my_title != 'skip' && $my_title != 'get_me' && $my_title != '')
                            {
                                $title = $my_title;
                            }
                        }
                        if (trim($lazy_tag) != '' && trim($lazy_tag) != 'src' && strstr($content, trim($lazy_tag)) !== false) 
                        {
                            $lazy_tag = trim($lazy_tag);
                            $lazy_found = false;
                            preg_match_all('{<img .*?>}s', $content, $imgsMatchs);
                            if(isset($imgsMatchs[0]))
                            {
                                $imgsMatchs = $imgsMatchs[0];
                                foreach($imgsMatchs as $imgMatch){
                                    if(stristr($imgMatch, $lazy_tag )){
                                        $newImg = $imgMatch;
                                        $newImg = preg_replace('{ src=["\'].*?[\'"]}', '', $newImg);
                                        if(stristr($lazy_tag, 'srcset') !== false)
                                        {
                                            $newImg = preg_replace('{\ssrcset=["\'].*?[\'"]}', '', $newImg);
                                            $newImg = str_replace($lazy_tag, 'srcset', $newImg);
                                            preg_match_all('#srcset=[\'"](?:([^"\'\s,]+)\s*(?:\s+\d+[wx])(?:,\s*)?)+["\']#', $newImg, $imgma);
                                            if(isset($imgma[1][0]))
                                            {
                                                $newImg = preg_replace('#<img#', '<img src="' . $imgma[1][0] . '"', $newImg);
                                            }
                                        }
                                        else
                                        {
                                            $newImg = str_replace($lazy_tag, 'src', $newImg); 
                                        }
                                        $content = str_replace($imgMatch, $newImg, $content);   
                                        $lazy_found = true;                            
                                    }
                                }
                            }
                            if($lazy_found == false)
                            {
                                $content = str_replace(trim($lazy_tag), 'src', $content); 
                            }
                            preg_match_all('{<iframe .*?>}s', $content, $imgsMatchs);
                            if(isset($imgsMatchs[0]))
                            {
                                $imgsMatchs = $imgsMatchs[0];
                                if(is_array($imgsMatchs))
                                {
                                    foreach($imgsMatchs as $imgMatch){
                                        if(stristr($imgMatch, $lazy_tag )){
                                            $newImg = $imgMatch;
                                            $newImg = preg_replace('{ src=["\'].*?[\'"]}', '', $newImg);
                                            $newImg = str_replace($lazy_tag, 'src', $newImg);   
                                            $content = str_replace($imgMatch, $newImg, $content);                          
                                        }
                                    }
                                }
                            }
                        }
                        else
                        {
                            $content = echo_lazy_loading_auto_fix($content);
                        }
                        
                        $content = preg_replace('{data-image-meta="(?:[^\"]*?)"}i', '', $content);
                        if(isset($echo_Main_Settings['attr_text']) && $echo_Main_Settings['attr_text'] != '')
                        {
                            $img_attr = $echo_Main_Settings['attr_text'];
                        }
                        else
                        {
                            $img_attr = '';
                        }
                        $get_img = '';
                        $img_found = false;
                        if($royalty_free == '1')
                        {
                            if(isset($echo_Main_Settings['textrazor_key']) && trim($echo_Main_Settings['textrazor_key']) != '')
                            {
                                try
                                {
                                    if(!class_exists('TextRazor'))
                                    {
                                        require_once(dirname(__FILE__) . "/res/TextRazor.php");
                                    }
                                    TextRazorSettings::setApiKey(trim($echo_Main_Settings['textrazor_key']));
                                    $textrazor = new TextRazor();
                                    $textrazor->addExtractor('entities');
                                    $response = $textrazor->analyze($title);
                                    if (isset($response['response']['entities'])) 
                                    {
                                        foreach ($response['response']['entities'] as $entity) 
                                        {
                                            $query_words = '';
                                            if(isset($entity['entityEnglishId']))
                                            {
                                                $query_words = $entity['entityEnglishId'];
                                            }
                                            else
                                            {
                                                $query_words = $entity['entityId'];
                                            }
                                            if($query_words != '')
                                            {
                                                $get_img = echo_get_free_image($echo_Main_Settings, $query_words, $img_attr, 10);
                                                if($get_img == false)
                                                {
                                                    $get_img = '';
                                                }
                                                if(!empty($get_img))
                                                {
                                                    if (isset($echo_Main_Settings['enable_detailed_logging'])) {
                                                        echo_log_to_file('Royalty Free Featured Image Generated with help of TextRazor (kw: "' . $query_words . '"): ' . $get_img);
                                                    }
                                                    break;
                                                }
                                            }
                                        }
                                    }
                                }
                                catch(Exception $e)
                                {
                                    if (isset($echo_Main_Settings['enable_detailed_logging'])) {
                                        echo_log_to_file('Failed to search for keywords using TextRazor: ' . $e->getMessage());
                                    }
                                }
                            }
                            if(empty($get_img))
                            {
                                $keyword_class = new Echo_keywords();
                                $query_words = $keyword_class->keywords($title, 2);
                                $get_img = echo_get_free_image($echo_Main_Settings, $query_words, $img_attr, 10);
                                if($get_img == '' || $get_img === false)
                                {
                                    if(isset($echo_Main_Settings['bimage']) && $echo_Main_Settings['bimage'] == 'on')
                                    {
                                        $query_words = $keyword_class->keywords($title, 1);
                                        $get_img = echo_get_free_image($echo_Main_Settings, $query_words, $img_attr, 20);
                                        if($get_img == '' || $get_img === false)
                                        {
                                            if(isset($echo_Main_Settings['no_royalty_skip']) && $echo_Main_Settings['no_royalty_skip'] == 'on')
                                            {
                                                if (isset($echo_Main_Settings['enable_detailed_logging'])) {
                                                    echo_log_to_file('Skipping importing because no royalty free image found.');
                                                }
                                                continue;
                                            }
                                            if(isset($echo_Main_Settings['no_orig']) && $echo_Main_Settings['no_orig'] == 'on')
                                            {
                                                $get_img = '';
                                            }
                                            else
                                            {
                                                $get_img = echo_get_featured_image($url, $content, $item, $orig_content, $skip_og, $skip_feed_image, $skip_first_img, $skip_post_content, $use_proxy, $use_phantom, $is_galerts, $feed_uri, $current_custom_simplepie, $image_type, $image_expre, $lazy_tag, $htmlcontent, $html_dl_failed);
                                            }
                                        }
                                    }
                                    else
                                    {
                                        if(isset($echo_Main_Settings['no_royalty_skip']) && $echo_Main_Settings['no_royalty_skip'] == 'on')
                                        {
                                            if (isset($echo_Main_Settings['enable_detailed_logging'])) {
                                                echo_log_to_file('Skipping importing because no royalty free image found.');
                                            }
                                            continue;
                                        }
                                        if(isset($echo_Main_Settings['no_orig']) && $echo_Main_Settings['no_orig'] == 'on')
                                        {
                                            $get_img = '';
                                        }
                                        else
                                        {
                                            $get_img = echo_get_featured_image($url, $content, $item, $orig_content, $skip_og, $skip_feed_image, $skip_first_img, $skip_post_content, $use_proxy, $use_phantom, $is_galerts, $feed_uri, $current_custom_simplepie, $image_type, $image_expre, $lazy_tag, $htmlcontent, $html_dl_failed);
                                        }
                                    }
                                }
                            }
                        }
                        else
                        {
                            $get_img = echo_get_featured_image($url, $content, $item, $orig_content, $skip_og, $skip_feed_image, $skip_first_img, $skip_post_content, $use_proxy, $use_phantom, $is_galerts, $feed_uri, $current_custom_simplepie, $image_type, $image_expre, $lazy_tag, $htmlcontent, $html_dl_failed);
                        }
                        
                        if($get_img != '')
                        {
                            $img_found = true;
                            if(substr($get_img, 0, 1) === "/")
                            {
                                if(substr($get_img, 1, 1) === "/")
                                {
                                    $get_img = 'http:' . $get_img;
                                }
                                else
                                {
                                    if($url != '')
                                    {
                                        $get_img = preg_replace('{\/(.*)}', echo_get_url_domain($url) . '/$1', $get_img);
                                    }
                                }
                            }
                        }
                        if($url != '')
                        {
                            $content = preg_replace('{"\/([^\/].+?)"}', '"' . echo_get_url_domain($url) . '/$1"', $content);
                        }
                        if (isset($echo_Main_Settings['skip_image_names']) && $echo_Main_Settings['skip_image_names'] != '' && $img_found == true) 
                        {
                            $need_to_continue = false;
                            $skip_images = explode(',', $echo_Main_Settings['skip_image_names']);
                            foreach($skip_images as $ski)
                            {
                                if(echo_stringMatchWithWildcard($get_img, trim($ski)))
                                {
                                    if (isset($echo_Main_Settings['enable_detailed_logging'])) {
                                        echo_log_to_file('Skipping post "' . esc_html($title) . '", because it has excluded image name: ' . $get_img . ' - ' . $ski);
                                    }
                                    $need_to_continue = true;
                                    break;
                                }
                            }
                            if($need_to_continue == true)
                            {
                                continue;
                            }
                        }
                        if ($featured_image == '1' && isset($echo_Main_Settings['skip_no_img']) && $echo_Main_Settings['skip_no_img'] == 'on' && $img_found == false) {
                            if (isset($echo_Main_Settings['enable_detailed_logging'])) {
                                echo_log_to_file('Skipping post "' . esc_html($title) . '", because it has no detected image file attached');
                            }
                            continue;
                        }
                        if(substr($get_img, 0, 2) === "//")
                        {
                            $get_img = 'http:' . $get_img;
                        }
                        $my_post['echo_post_image']       = $get_img;
                        if ($strip_by_id != '') {
                            require_once (dirname(__FILE__) . "/res/simple_html_dom.php");
                            $strip_list = explode(',', $strip_by_id);
                            $extractok = false;
                            $html_dom_original_html = echo_str_get_html($content);
                            if($html_dom_original_html !== false && method_exists($html_dom_original_html, 'find')){
                                foreach ($strip_list as $strip_id) {
                                    $ret = $html_dom_original_html->find('*[id="'.trim($strip_id).'"]');
                                    foreach ($ret as $itm ) {
                                        $extractok = true;
                                        $itm->outertext = '' ;
                                    }
                                }
                                $content = $html_dom_original_html->save();
                                $html_dom_original_html->clear();
                                unset($html_dom_original_html);
                            }
                            if($extractok == true)
                            {
                                foreach ($strip_list as $strip_id) {
                                    if(trim($strip_id) == '')
                                    {
                                        continue;
                                    }
                                    $content_r = echo_removeTagByID($content, trim($strip_id));
                                    if($content_r !== false)
                                    {
                                        $content = $content_r;
                                    }
                                }
                            }
                        }
                        if ($strip_by_class != '') {
                            require_once (dirname(__FILE__) . "/res/simple_html_dom.php");
                            $strip_list = explode(',', $strip_by_class);
                            $extractok = false;
                            $html_dom_original_html = echo_str_get_html($content);
                            if($html_dom_original_html !== false && method_exists($html_dom_original_html, 'find')){
                                foreach ($strip_list as $strip_class) {
                                    if(trim($strip_class) == '')
                                    {
                                        continue;
                                    }
                                    $ret = $html_dom_original_html->find('*[class="'.trim($strip_class).'"]');
                                    foreach ($ret as $itm ) {
                                        $extractok = true;
                                        $itm->outertext = '' ;
                                    }
                                }
                                $content = $html_dom_original_html->save();
                                $html_dom_original_html->clear();
                                unset($html_dom_original_html);
                            }
                            if($extractok == true)
                            {
                                foreach ($strip_list as $strip_class) {
                                    if(trim($strip_class) == '')
                                    {
                                        continue;
                                    }
                                    $content_r = echo_removeTagByClass($content, trim($strip_class));
                                    if($content_r !== false)
                                    {
                                        $content = $content_r;
                                    }
                                }
                            }
                        }
                        if ((isset($echo_Main_Settings['strip_content_links']) && $echo_Main_Settings['strip_content_links'] == 'on') || $strip_links == '1')
                        {
                            $content = echo_strip_links($content);
                        }
                        if ((isset($echo_Main_Settings['strip_content_html']) && $echo_Main_Settings['strip_content_html'] == 'on'))
                        {
                            $content = echo_strip_html_tags_nl($content);
                        }
                        if (isset($echo_Main_Settings['excerpt_length']) && is_numeric($echo_Main_Settings['excerpt_length'])) {
                            $words = intval($echo_Main_Settings['excerpt_length']);
                        }
                        else
                        {
                            $words = 55;
                        }
						$xexcerpt = echo_getExcerpt($content, $words);
                        $postdate = strtotime($date);
                        if($postdate !== FALSE)
                        {
                            $postdate = gmdate("Y-m-d H:i:s", intval($postdate));
                        }
                        if($import_date == '1')
                        {
                            if($postdate !== FALSE)
                            {
                                $my_post['post_date_gmt'] = $postdate;
                            }
                            else
                            {
                                $postdatex = gmdate("Y-m-d H:i:s", intval($init_date));
                                $my_post['post_date_gmt'] = $postdatex;
                                $init_date = $init_date - 1;
                            }
                        }
                        else
                        {
                            $postdatex = gmdate("Y-m-d H:i:s", intval($init_date));
                            $my_post['post_date_gmt'] = $postdatex;
                            $init_date = $init_date - 1;
                        }
                        if($postdate === false)
                        {
                            $postdate = $date;
                        }
                        if($content_percent != '' && is_numeric($content_percent) && $content_percent != 100)
                        {
                            $temp_t = echo_strip_html_tags($content);
                            $temp_t = str_replace('&nbsp;',"",$temp_t);
                            $ccount = echo_str_word_count($temp_t);
                            if($ccount > 10)
                            {
                                $str_count = strlen($content);
                                $leave_cont = round($str_count * $content_percent / 100);
                                $content = echo_substr_close_tags($content, $leave_cont);
                            }
                        }
                        if ($limit_word_count !== "") 
                        {
                            $content = echo_custom_wp_trim_excerpt($content, $limit_word_count, $short_url, $read_more);
                        }
                        if($regex_before == '1')
                        {
                            if ($strip_by_regex !== '')
                            {
                                $xstrip_by_regex = preg_split('/\r\n|\r|\n/', $strip_by_regex);
                                $xreplace_regex = preg_split('/\r\n|\r|\n/', $replace_regex);
                                $xcnt = 0;
                                foreach($xstrip_by_regex as $sbr)
                                {
                                    if(isset($xreplace_regex[$xcnt]))
                                    {
                                        $repreg = $xreplace_regex[$xcnt];
                                    }
                                    else
                                    {
                                        $repreg = '';
                                    }
                                    $xcnt++;
                                    $temp_cont = preg_replace("~" . $sbr . "~i", $repreg, $content);
                                    if($temp_cont !== NULL)
                                    {
                                        $content = $temp_cont;
                                    }
                                }
                            }
                        }
                        if (isset($echo_Main_Settings['only_imported']) && $echo_Main_Settings['only_imported'] == 'on') 
                        {
                            $arr                     = echo_spin_and_translate($title, $content, $translate, $source_lang, $second_translate, $hideGoogle, $use_proxy);
                            $title                   = $arr[0];
                            $content                 = $arr[1];
                        }
                        $extra_title_tags = '';
                        $z_keyword_tag = $keyword_tag;
                        if($z_keyword_tag != '')
                        {
                            if (stristr ( $z_keyword_tag, '|' ))
                            {
                                $content_to_check = $title . ' ' . $content;
                                $splt_keyword_categories = preg_split('/\r\n|\r|\n/', $z_keyword_tag);                    
                                foreach ( $splt_keyword_categories as $splt_keyword_tag ) {
                                    if (stristr ( $splt_keyword_tag, '|' )) 
                                    {
                                        $splt_keyword_tag = trim ( $splt_keyword_tag );
                                        $splt_keyword_tag_parts = explode ( '|', $splt_keyword_tag );
                                        $splt_keyword_tag_keyword = $splt_keyword_tag_parts [0];
                                        $splt_keyword_tag_category = $splt_keyword_tag_parts [1];
                                        $was_found = false;
                                        $splt_keyword_tag_keywords = explode ( ',', $splt_keyword_tag_keyword );
                                        foreach ( $splt_keyword_tag_keywords as $splt_keyword_tag_single ) 
                                        {
                                            if (stristr ($content_to_check, $splt_keyword_tag_single )) 
                                            {
                                                $was_found = true;
                                                break;
                                            }
                                        }
                                        if ($was_found) 
                                        {
                                            $extra_title_tags .= $splt_keyword_tag_category;
                                        }
                                    }
                                }
                            }
                            else
                            {
                                $kw_list = explode(',', $z_keyword_tag);
                                foreach($kw_list as $thiskw)
                                {
                                    $thiskw = trim($thiskw);
                                    if (stripos($title, $thiskw) !== false) 
                                    {
                                        $extra_title_tags .= $thiskw . ',';
                                    }
                                    elseif (stripos($content, $thiskw) !== false) 
                                    {
                                        $extra_title_tags .= $thiskw . ',';
                                    }
                                }
                            }
                        }
                        $extra_title_tags = trim($extra_title_tags, ',');
                        $extra_title_categories = '';
                        $z_keyword_cat = $keyword_cat;
                        if($z_keyword_cat != '')
                        {
                            if (stristr ( $z_keyword_cat, '|' ))
                            {
                                $content_to_check = $title . ' ' . $content;
                                $splt_keyword_categories = preg_split('/\r\n|\r|\n/', $z_keyword_cat);                    
                                foreach ( $splt_keyword_categories as $splt_keyword_category ) {
                                    if (stristr ( $splt_keyword_category, '|' )) 
                                    {
                                        $splt_keyword_category = trim ( $splt_keyword_category );
                                        $splt_keyword_category_parts = explode ( '|', $splt_keyword_category );
                                        $splt_keyword_category_keyword = $splt_keyword_category_parts [0];
                                        $splt_keyword_category_category = $splt_keyword_category_parts [1];
                                        $was_found = false;
                                        $splt_keyword_category_keywords = explode ( ',', $splt_keyword_category_keyword );
                                        foreach ( $splt_keyword_category_keywords as $splt_keyword_category_single ) 
                                        {
                                            if (stristr ($content_to_check, $splt_keyword_category_single )) 
                                            {
                                                $was_found = true;
                                                break;
                                            }
                                        }
                                        
                                        if ($was_found) 
                                        {
                                            $extra_title_categories .= ',' . $splt_keyword_category_category;
                                            $extra_title_categories = trim($extra_title_categories, ',');
                                        }
                                    }
                                }
                            }
                            else
                            {
                                $kw_list = explode(',', $z_keyword_cat);
                                foreach($kw_list as $thiskw)
                                {
                                    $thiskw = trim($thiskw);
                                    if (stripos($title, $thiskw) !== false) 
                                    {
                                        $extra_title_categories .= $thiskw . ',';
                                    }
                                    elseif (stripos($content, $thiskw) !== false) 
                                    {
                                        $extra_title_categories .= $thiskw . ',';
                                    }
                                }
                            }
                        }
                        $extra_title_categories = trim($extra_title_categories, ',');
                        if($description == '' && $content != '')
                        {
                            $description = wp_trim_excerpt($content);
                        }
                        if ($limit_word_count !== "") 
                        {
                            $description = echo_custom_wp_trim_excerpt($description, $limit_word_count, $short_url, $read_more);
                        }
                        $screenimageURL = '';
                        $screens_attach_id = '';
                        if (isset($echo_Main_Settings['headless_screen']) && $echo_Main_Settings['headless_screen'] == 'on')
                        {
                            if($attach_screen == '1' || (strstr($post_content, '%%item_show_screenshot%%') !== false || strstr($post_content, '%%item_screenshot_url%%') !== false || strstr($custom_fields, '%%item_show_screenshot%%') !== false || strstr($custom_fields, '%%item_screenshot_url%%') !== false || strstr($custom_tax, '%%item_show_screenshot%%') !== false || strstr($custom_tax, '%%item_screenshot_url%%') !== false))
                            {
                                if ($use_proxy == '1' && isset($echo_Main_Settings['proxy_url']) && $echo_Main_Settings['proxy_url'] != '' && $echo_Main_Settings['proxy_url'] != 'disable' && $echo_Main_Settings['proxy_url'] != 'disabled') 
                                {
                                    $prx = explode(',', $echo_Main_Settings['proxy_url']);
                                    $randomness = array_rand($prx);
                                    $phantomjs_comm .= '--proxy=' . trim($prx[$randomness]) . ' ';
                                    if (isset($echo_Main_Settings['proxy_auth']) && $echo_Main_Settings['proxy_auth'] != '') 
                                    {
                                        $prx_auth = explode(',', $echo_Main_Settings['proxy_auth']);
                                        if(isset($prx_auth[$randomness]) && trim($prx_auth[$randomness]) != '')
                                        {
                                            $phantomjs_comm .= '--proxy-auth=' . trim($prx_auth[$randomness]) . ' ';
                                        }
                                    }
                                }
                                $screenshotimg = echo_get_screenshot_PuppeteerAPI($url, 'default', 'default', $use_proxy, 'default', '', '');
                                if($screenshotimg !== false)
                                {
                                    $upload_dir = wp_upload_dir();
                                    $dir_name   = $upload_dir['basedir'] . '/echo-files';
                                    $dir_url    = $upload_dir['baseurl'] . '/echo-files';
                                    global $wp_filesystem;
                                    if ( ! is_a( $wp_filesystem, 'WP_Filesystem_Base') ){
                                        include_once(ABSPATH . 'wp-admin/includes/file.php');$creds = request_filesystem_credentials( site_url() );
                                        wp_filesystem($creds);
                                    }
                                    if (!$wp_filesystem->exists($dir_name)) {
                                        wp_mkdir_p($dir_name);
                                    }
                                    $screen_name = uniqid();
                                    $screenimageName = $dir_name . '/' . $screen_name . '.jpg';
                                    $screenimageURL = $dir_url . '/' . $screen_name . '.jpg';
                                    
                                    $is_fail = $wp_filesystem->put_contents($screenimageName, $screenshotimg);
                                    if($is_fail === false)
                                    {
                                        echo_log_to_file('Error in writing screenshot to file: ' . $screenimageName);
                                    }
                                    else
                                    {
                                        $wp_filetype = wp_check_filetype( $screen_name . '.jpg', null );
                                        $attachment = array(
                                            'post_mime_type' => $wp_filetype['type'],
                                            'post_title' => sanitize_file_name( $screen_name . '.jpg' ),
                                            'post_content' => '',
                                            'post_status' => 'inherit'
                                        );
                                        $screens_attach_id = wp_insert_attachment($attachment, $screenimageName);
                                        require_once( ABSPATH . 'wp-admin/includes/image.php' );
                                        require_once( ABSPATH . 'wp-admin/includes/media.php' );
                                        $attach_data = wp_generate_attachment_metadata($screens_attach_id, $screenimageName);
                                        wp_update_attachment_metadata( $screens_attach_id, $attach_data );
                                    }
                                }
                            }
                        }
                        elseif (isset($echo_Main_Settings['phantom_screen']) && $echo_Main_Settings['phantom_screen'] == 'on')
                        {
                            if($url != '' && $attach_screen == '1' || (strstr($post_content, '%%item_show_screenshot%%') !== false || strstr($post_content, '%%item_screenshot_url%%') !== false || strstr($custom_fields, '%%item_show_screenshot%%') !== false || strstr($custom_fields, '%%item_screenshot_url%%') !== false || strstr($custom_tax, '%%item_show_screenshot%%') !== false || strstr($custom_tax, '%%item_screenshot_url%%') !== false))
                            {
                                if(function_exists('shell' . '_exec')) 
                                {
                                    $disabled = explode(',', ini_get('disable_functions'));
                                    if(!in_array('shell' . '_exec', $disabled))
                                    {
                                        if (isset($echo_Main_Settings['phantom_path']) && $echo_Main_Settings['phantom_path'] != '') 
                                        {
                                            $phantomjs_comm = $echo_Main_Settings['phantom_path'] . ' ';
                                        }
                                        else
                                        {
                                            $phantomjs_comm = 'phantomjs ';
                                        }
                                        if (isset($echo_Main_Settings['screenshot_height']) && $echo_Main_Settings['screenshot_height'] != '') 
                                        {
                                            $h = esc_attr($echo_Main_Settings['screenshot_height']);
                                        }
                                        else
                                        {
                                            $h = '0';
                                        }
                                        if (isset($echo_Main_Settings['screenshot_width']) && $echo_Main_Settings['screenshot_width'] != '') 
                                        {
                                            $w = esc_attr($echo_Main_Settings['screenshot_width']);
                                        }
                                        else
                                        {
                                            $w = '1920';
                                        }
                                        $upload_dir = wp_upload_dir();
                                        $dir_name   = $upload_dir['basedir'] . '/echo-files';
                                        $dir_url    = $upload_dir['baseurl'] . '/echo-files';
                                        global $wp_filesystem;
                                        if ( ! is_a( $wp_filesystem, 'WP_Filesystem_Base') ){
                                            include_once(ABSPATH . 'wp-admin/includes/file.php');$creds = request_filesystem_credentials( site_url() );
                                            wp_filesystem($creds);
                                        }
                                        if (!$wp_filesystem->exists($dir_name)) {
                                            wp_mkdir_p($dir_name);
                                        }
                                        $screen_name = uniqid();
                                        $screenimageName = $dir_name . '/' . $screen_name;
                                        $screenimageURL = $dir_url . '/' . $screen_name . '.jpg';
                                        if($use_proxy && isset($echo_Main_Settings['proxy_url']) && $echo_Main_Settings['proxy_url'] != '' && $echo_Main_Settings['proxy_url'] != 'disable' && $echo_Main_Settings['proxy_url'] != 'disabled') 
                                        {
                                            $prx = explode(',', $echo_Main_Settings['proxy_url']);
                                            $randomness = array_rand($prx);
                                            $phantomjs_comm .= '--proxy=' . trim($prx[$randomness]) . ' ';
                                            if (isset($echo_Main_Settings['proxy_auth']) && $echo_Main_Settings['proxy_auth'] != '') 
                                            {
                                                $prx_auth = explode(',', $echo_Main_Settings['proxy_auth']);
                                                if(isset($prx_auth[$randomness]) && trim($prx_auth[$randomness]) != '')
                                                {
                                                    $phantomjs_comm .= '--proxy-auth=' . trim($prx_auth[$randomness]) . ' ';
                                                }
                                            }
                                        }
                                        $shefunc = trim(' s ') . trim(' h ') . 'ell' . '_exec';
                                        $cmdResult = $shefunc($phantomjs_comm . '"' . dirname(__FILE__) .'/res/phantomjs/phantom-screenshot.js"' . ' "'. dirname(__FILE__) . '" "' . $url . '" "' . $screenimageName . '" ' . $w . ' ' . $h . '  2>&1');
                                        if($cmdResult === NULL || $cmdResult == '' || trim($cmdResult) === 'timeout' || stristr($cmdResult, 'sh: phantomjs: command not found') !== false)
                                        {
                                            $screenimageURL = '';
                                        }
                                        else
                                        {
                                            if($wp_filesystem->exists($screenimageName))
                                            {
                                                $wp_filetype = wp_check_filetype( $screen_name . '.jpg', null );
                                                $attachment = array(
                                                  'post_mime_type' => $wp_filetype['type'],
                                                  'post_title' => sanitize_file_name( $screen_name . '.jpg' ),
                                                  'post_content' => '',
                                                  'post_status' => 'inherit'
                                                );
                                                $screens_attach_id = wp_insert_attachment( $attachment, $screenimageName . '.jpg' );
                                                require_once( ABSPATH . 'wp-admin/includes/image.php' );
                                                require_once( ABSPATH . 'wp-admin/includes/media.php' );
                                                $attach_data = wp_generate_attachment_metadata( $screens_attach_id, $screenimageName . '.jpg' );
                                                wp_update_attachment_metadata( $screens_attach_id, $attach_data );
                                            }
                                            else
                                            {
                                                echo_log_to_file('Screenshot file not found after phantom exec: ' . $cmdResult);
                                            }
                                        }
                                    }
                                }
                            }
                        }
                        elseif (isset($echo_Main_Settings['puppeteer_screen']) && $echo_Main_Settings['puppeteer_screen'] == 'on')
                        {
                            if($url != '' && $attach_screen == '1' || (strstr($post_content, '%%item_show_screenshot%%') !== false || strstr($post_content, '%%item_screenshot_url%%') !== false || strstr($custom_fields, '%%item_show_screenshot%%') !== false || strstr($custom_fields, '%%item_screenshot_url%%') !== false || strstr($custom_tax, '%%item_show_screenshot%%') !== false || strstr($custom_tax, '%%item_screenshot_url%%') !== false))
                            {
                                if(function_exists('shell' . '_exec')) 
                                {
                                    $disabled = explode(',', ini_get('disable_functions'));
                                    if(!in_array('shell' . '_exec', $disabled))
                                    {
                                        $phantomjs_comm = 'node ';
                                        if (isset($echo_Main_Settings['screenshot_height']) && $echo_Main_Settings['screenshot_height'] != '') 
                                        {
                                            $h = esc_attr($echo_Main_Settings['screenshot_height']);
                                        }
                                        else
                                        {
                                            $h = '0';
                                        }
                                        if (isset($echo_Main_Settings['screenshot_width']) && $echo_Main_Settings['screenshot_width'] != '') 
                                        {
                                            $w = esc_attr($echo_Main_Settings['screenshot_width']);
                                        }
                                        else
                                        {
                                            $w = '1920';
                                        }
                                        if ($w < 350) {
                                            $w = 350;
                                        }
                                        if ($w > 1920) {
                                            $w = 1920;
                                        }
                                        $upload_dir = wp_upload_dir();
                                        $dir_name   = $upload_dir['basedir'] . '/echo-files';
                                        $dir_url    = $upload_dir['baseurl'] . '/echo-files';
                                        global $wp_filesystem;
                                        if ( ! is_a( $wp_filesystem, 'WP_Filesystem_Base') ){
                                            include_once(ABSPATH . 'wp-admin/includes/file.php');$creds = request_filesystem_credentials( site_url() );
                                            wp_filesystem($creds);
                                        }
                                        if (!$wp_filesystem->exists($dir_name)) {
                                            wp_mkdir_p($dir_name);
                                        }
                                        $screen_name = uniqid();
                                        $screenimageName = $dir_name . '/' . $screen_name . '.jpg';
                                        $screenimageURL = $dir_url . '/' . $screen_name . '.jpg';
                                        $phantomjs_proxcomm = '"null"';
                                        if ($use_proxy && isset($echo_Main_Settings['proxy_url']) && $echo_Main_Settings['proxy_url'] != '' && $echo_Main_Settings['proxy_url'] != 'disable' && $echo_Main_Settings['proxy_url'] != 'disabled') 
                                        {
                                            $prx = explode(',', $echo_Main_Settings['proxy_url']);
                                            $randomness = array_rand($prx);
                                            $phantomjs_proxcomm = '"' . trim($prx[$randomness]);
                                            if (isset($echo_Main_Settings['proxy_auth']) && $echo_Main_Settings['proxy_auth'] != '') 
                                            {
                                                $prx_auth = explode(',', $echo_Main_Settings['proxy_auth']);
                                                if(isset($prx_auth[$randomness]) && trim($prx_auth[$randomness]) != '')
                                                {
                                                    $phantomjs_proxcomm .= '~~~' . trim($prx_auth[$randomness]);
                                                }
                                            }
                                            $phantomjs_proxcomm .= '"';
                                        }
                                        if($user_agent_cust == '')
                                        {
                                            $user_agent_cust = 'default';
                                        }
                                        if($custom_cookie == '')
                                        {
                                            $custom_cookie = 'default';
                                        }
                                        $user_pass = 'default';
                                        $shefunc = trim(' s ') . trim(' h ') . 'ell' . '_exec';
                                        $cmdResult = $shefunc($phantomjs_comm . '"' . dirname(__FILE__) .'/res/puppeteer/screenshot.js"' . ' "' . $url . '" "' . $screenimageName . '" ' . $w . ' ' . $h . ' ' . $phantomjs_proxcomm . '  "' . esc_html($user_agent_cust) . '" "' . esc_html($custom_cookie) . '" "' . $user_pass . '" 2>&1');
                                        if(stristr($cmdResult, 'sh: node: command not found') !== false || stristr($cmdResult, 'throw err;') !== false)
                                        {
                                            $screenimageURL = '';
                                            echo_log_to_file('Error in puppeteer screenshot: exec: ' . $phantomjs_comm . '"' . dirname(__FILE__) .'/res/puppeteer/screenshot.js"' . ' "' . $url . '" "' . $screenimageName . '" ' . $w . ' ' . $h . ' ' . $phantomjs_proxcomm . '  "' . esc_html($user_agent_cust) . '" "' . esc_html($custom_cookie) . '" "' . $user_pass . '" , reterr: ' . $cmdResult);
                                        }
                                        else
                                        {
                                            if($wp_filesystem->exists($screenimageName))
                                            {
                                                $wp_filetype = wp_check_filetype( $screen_name . '.jpg', null );
                                                $attachment = array(
                                                  'post_mime_type' => $wp_filetype['type'],
                                                  'post_title' => sanitize_file_name( $screen_name . '.jpg' ),
                                                  'post_content' => '',
                                                  'post_status' => 'inherit'
                                                );
                                                $screens_attach_id = wp_insert_attachment( $attachment, $screenimageName);
                                                require_once( ABSPATH . 'wp-admin/includes/image.php' );
                                                require_once( ABSPATH . 'wp-admin/includes/media.php' );
                                                $attach_data = wp_generate_attachment_metadata( $screens_attach_id, $screenimageName);
                                                wp_update_attachment_metadata( $screens_attach_id, $attach_data );
                                            }
                                            else
                                            {
                                                echo_log_to_file('Screenshot file not found after puppeteer exec: ' . $cmdResult);
                                            }
                                        }
                                    }
                                }
                            }
                        }
                        if($regex_before == '1')
                        {
                            if ($strip_by_regex_title !== '')
                            {
                                $xstrip_by_regex = preg_split('/\r\n|\r|\n/', $strip_by_regex_title);
                                $xreplace_regex = preg_split('/\r\n|\r|\n/', $replace_regex_title);
                                $xcnt = 0;
                                foreach($xstrip_by_regex as $sbr)
                                {
                                    if(isset($xreplace_regex[$xcnt]))
                                    {
                                        $repreg = $xreplace_regex[$xcnt];
                                    }
                                    else
                                    {
                                        $repreg = '';
                                    }
                                    $xcnt++;
                                    $temp_contx = preg_replace("~" . $sbr . "~i", $repreg, $title);
                                    if($temp_contx !== NULL)
                                    {
                                        $title = $temp_contx;
                                    }
                                }
                            }
                        }
                        if (isset($echo_Main_Settings['strip_emoticons']) && $echo_Main_Settings['strip_emoticons'] == 'on')
                        {
                            $title = echo_remove_emoji($title);
                        }
                        if (isset($echo_Main_Settings['strip_non_char']) && $echo_Main_Settings['strip_non_char'] == 'on')
                        {
                            $title = preg_replace('/[^\x20-\x7E]/','', $title);
                        }
                        if($item_create_tag != '')
                        {
                            $item_create_tag_current = echo_replaceContentShortcodes($item_create_tag, $title, $content, $short_url, $extra_categories, $item_tags, $get_img, $feed_title, $feed_description, $description, $feed_logo, $author, $author_link, $author_email, $read_more, $postdate, $custom_tag_var_list, $custom_feed_tag_var_list, $img_attr, $screenimageURL, $feed_uri, $source_id, $xexcerpt);
                        }
                        else
                        {
                            $item_create_tag_current = '';
                        }
                        if($moretags != '')
                        {
                            if($item_create_tag_current != '')
                            {
                                $item_create_tag_current = $item_create_tag_current . ',' . $moretags;
                            }
                            else
                            {
                                $item_create_tag_current = $moretags;
                            }
                        }
                        if($extra_title_tags != '')
                        {
                            if($item_create_tag_current == '')
                            {
                                $item_create_tag_current = $extra_title_tags;
                            }
                            else
                            {
                                $item_create_tag_current .= ',' . $extra_title_tags;
                            }
                        }
                        if ($can_create_tag == '1') {
                            $my_post['tags_input'] = ($item_create_tag_current != '' ? $item_create_tag_current . ',' : '') . $item_tags;
                        } elseif ($item_create_tag_current != '') {
                            $my_post['tags_input'] = $item_create_tag_current;
                        }
                        $new_post_content = echo_replaceContentShortcodes($post_content, $title, $content, $short_url, $extra_categories, $item_tags, $get_img, $feed_title, $feed_description, $description, $feed_logo, $author, $author_link, $author_email, $read_more, $postdate, $custom_tag_var_list, $custom_feed_tag_var_list, $img_attr, $screenimageURL, $feed_uri, $source_id, $xexcerpt);
                        $new_post_title = echo_replaceContentShortcodes($post_title, $title, $content, $short_url, $extra_categories, $item_tags, $get_img, $feed_title, $feed_description, $description, $feed_logo, $author, $author_link, $author_email, $read_more, $postdate, $custom_tag_var_list, $custom_feed_tag_var_list, $img_attr, $screenimageURL, $feed_uri, $source_id, $xexcerpt);
                        $my_post['screen_attach']    = $screens_attach_id;
                        $my_post['extra_categories'] = $extra_categories;
                        $my_post['extra_tags']       = $item_tags;
                        $my_post['feed_title']       = $feed_title;
                        $my_post['feed_description'] = $feed_description;
                        $my_post['description']      = $description;
                        $my_post['feed_logo']        = $feed_logo;
                        $my_post['author']           = $author;
                        $my_post['author_link']      = $author_link;
                        $my_post['author_email']     = $author_email;
                        $my_post['seo_meta']         = '';
                        if (isset($echo_Main_Settings['scrape_seo']) && $echo_Main_Settings['scrape_seo'] == 'on')
                        {
                            if(!empty($htmlcontent))
                            {
                                if (preg_match('#<meta[\s\n]+name="description"[\s\n]+content="([^"]+)"#i', $htmlcontent, $matches)) 
                                {
                                    $my_post['seo_meta'] = $matches[1];
                                }
                            }
                        }
                        if (!isset($echo_Main_Settings['only_imported']) || $echo_Main_Settings['only_imported'] != 'on') 
                        {
                            $arr                         = echo_spin_and_translate($new_post_title, $new_post_content, $translate, $source_lang, $second_translate, $hideGoogle, $use_proxy);
                            if($arr === false)
                            {
                                continue;
                            }
                            $new_post_title              = $arr[0];
                            $new_post_content            = $arr[1];
                        }
                        $new_post_title              = html_entity_decode($new_post_title);
                        if($regex_before != '1')
                        {
                            if ($strip_by_regex_title !== '')
                            {
                                $xstrip_by_regex = preg_split('/\r\n|\r|\n/', $strip_by_regex_title);
                                $xreplace_regex = preg_split('/\r\n|\r|\n/', $replace_regex_title);
                                $xcnt = 0;
                                foreach($xstrip_by_regex as $sbr)
                                {
                                    if(isset($xreplace_regex[$xcnt]))
                                    {
                                        $repreg = $xreplace_regex[$xcnt];
                                    }
                                    else
                                    {
                                        $repreg = '';
                                    }
                                    $xcnt++;
                                    $temp_contx = preg_replace("~" . $sbr . "~i", $repreg, $new_post_title);
                                    if($temp_contx !== NULL)
                                    {
                                        $new_post_title = $temp_contx;
                                    }
                                }
                            }
                        }
                        if (isset($echo_Main_Settings['no_link_translate']) && $echo_Main_Settings['no_link_translate'] == 'on')
                        {
                            $new_post_content = preg_replace('{"https:\/\/translate\.google\.com\/translate\?hl=(?:.*?)&(?:amp;)?prev=_t&(?:amp;)?sl=(?:.*?)&(?:amp;)?tl=(?:.*?)&(?:amp;)?u=([^"]*?)"}i', "$1", html_entity_decode($new_post_content, ENT_COMPAT | ENT_HTML5));
                        }
                        else
                        {
                            $new_post_content = html_entity_decode($new_post_content, ENT_COMPAT | ENT_HTML5);
                        }
                        
                        $title_count = -1;
                        if (isset($echo_Main_Settings['min_word_title']) && $echo_Main_Settings['min_word_title'] != '') {
                            $title_count = echo_str_word_count($new_post_title);
                            if ($title_count < intval($echo_Main_Settings['min_word_title'])) {
                                if (isset($echo_Main_Settings['enable_detailed_logging'])) {
                                    echo_log_to_file('Skipping post "' . esc_html($new_post_title) . '", because title length (' . $title_count . ') < ' . $echo_Main_Settings['min_word_title']);
                                }
                                continue;
                            }
                        }
                        if (isset($echo_Main_Settings['max_word_title']) && $echo_Main_Settings['max_word_title'] != '') {
                            if ($title_count == -1) {
                                $title_count = echo_str_word_count($new_post_title);
                            }
                            if ($title_count > intval($echo_Main_Settings['max_word_title'])) {
                                if (isset($echo_Main_Settings['enable_detailed_logging'])) {
                                    echo_log_to_file('Skipping post "' . esc_html($new_post_title) . '", because title length (' . $title_count . ') > ' . $echo_Main_Settings['max_word_title']);
                                }
                                continue;
                            }
                        }
                        $content_count = -1;
                        if (isset($echo_Main_Settings['min_word_content']) && $echo_Main_Settings['min_word_content'] != '') {
                            $content_count = echo_str_word_count(echo_strip_html_tags($new_post_content));
                            if ($content_count < intval($echo_Main_Settings['min_word_content'])) {
                                if (isset($echo_Main_Settings['enable_detailed_logging'])) {
                                    echo_log_to_file('Skipping post "' . esc_html($new_post_title) . '", because content length (' . $content_count . ') < ' . $echo_Main_Settings['min_word_content']);
                                }
                                continue;
                            }
                        }
                        if (isset($echo_Main_Settings['max_word_content']) && $echo_Main_Settings['max_word_content'] != '') {
                            if ($content_count == -1) {
                                $content_count = echo_str_word_count(echo_strip_html_tags($new_post_content));
                            }
                            if ($content_count > intval($echo_Main_Settings['max_word_content'])) {
                                if (isset($echo_Main_Settings['enable_detailed_logging'])) {
                                    echo_log_to_file('Skipping post "' . esc_html($new_post_title) . '", because content length (' . $content_count . ') > ' . $echo_Main_Settings['max_word_content']);
                                }
                                continue;
                            }
                        }
                        if ($banned_words != '') {
                            $banned_words = trim(trim(trim($banned_words), ','));
                            $continue    = false;
                            $banned_list = explode(',', $banned_words);
                            foreach ($banned_list as $banned_word) {
                                if($restr_target == 'c')
                                {
                                    if (stripos($new_post_content, trim($banned_word)) !== FALSE) {
                                        if (isset($echo_Main_Settings['enable_detailed_logging'])) {
                                            echo_log_to_file('Skipping post "' . esc_html($new_post_title) . '", because it\'s content contains banned word: ' . $banned_word);
                                        }
                                        $continue = true;
                                        break;
                                    }
                                }
                                elseif($restr_target == 't')
                                {
                                    if (stripos($new_post_title, trim($banned_word)) !== FALSE) {
                                        if (isset($echo_Main_Settings['enable_detailed_logging'])) {
                                            echo_log_to_file('Skipping post "' . esc_html($new_post_title) . '", because it\'s title contains banned word: ' . $banned_word);
                                        }
                                        $continue = true;
                                        break;
                                    }
                                }
                                elseif($restr_target == 'tc')
                                {
                                    if (stripos($new_post_content, trim($banned_word)) !== FALSE) {
                                        if (isset($echo_Main_Settings['enable_detailed_logging'])) {
                                            echo_log_to_file('Skipping post "' . esc_html($new_post_title) . '", because it\'s content contains banned word: ' . $banned_word);
                                        }
                                        $continue = true;
                                        break;
                                    }
                                    if (stripos($new_post_title, trim($banned_word)) !== FALSE) {
                                        if (isset($echo_Main_Settings['enable_detailed_logging'])) {
                                            echo_log_to_file('Skipping post "' . esc_html($new_post_title) . '", because it\'s title contains banned word: ' . $banned_word);
                                        }
                                        $continue = true;
                                        break;
                                    }
                                }
                            }
                            if ($continue === true) {
                                continue;
                            }
                        }
                        if ($banned_urls != '') {
                            $continue    = false;
                            $banned_urls_arr = preg_split('/\r\n|\r|\n/', $banned_urls);
                            foreach ($banned_urls_arr as $banned_url) 
                            {
                                if (stristr($url, $banned_url)) 
                                {
                                    if (isset($echo_Main_Settings['enable_detailed_logging'])) {
                                        echo_log_to_file('Skipping post "' . esc_html($new_post_title) . '", because it\'s URL (' . $url . ') is banned: ' . $banned_url);
                                    }
                                    $continue = true;
                                    break;
                                }
                            }
                            if ($continue === true) {
                                continue;
                            }
                        }
                        if(isset($echo_Main_Settings['global_ban_urls']) && $echo_Main_Settings['global_ban_urls'] != '') {
                            $continue    = false;
                            $banned_urls_arr = preg_split('/\r\n|\r|\n/', $echo_Main_Settings['global_ban_urls']);
                            foreach ($banned_urls_arr as $banned_url) 
                            {
                                if (stristr($url, $banned_url)) 
                                {
                                    if (isset($echo_Main_Settings['enable_detailed_logging'])) {
                                        echo_log_to_file('Skipping post "' . esc_html($new_post_title) . '", because it\'s URL (' . $url . ') is gobally banned: ' . $banned_url);
                                    }
                                    $continue = true;
                                    break;
                                }
                            }
                            if ($continue === true) {
                                continue;
                            }
                        }
                        if ($banned_authors != '') {
                            $continue    = false;
                            $banned_list = explode(',', $banned_authors);
                            foreach ($banned_list as $banned_author) 
                            {
                                if ($author == trim($banned_author)) 
                                {
                                    if (isset($echo_Main_Settings['enable_detailed_logging'])) {
                                        echo_log_to_file('Skipping post "' . esc_html($new_post_title) . '", because it\'s author is banned: ' . $author);
                                    }
                                    $continue = true;
                                    break;
                                }
                            }
                            if ($continue === true) {
                                continue;
                            }
                        }
                        if ($required_authors != '') {
                            $continue    = true;
                            $req_list = explode(',', $required_authors);
                            foreach ($req_list as $req_author) 
                            {
                                if ($author == trim($req_author)) 
                                {
                                    $continue = false;
                                    break;
                                }
                            }
                            if ($continue === true) {
                                if (isset($echo_Main_Settings['enable_detailed_logging'])) {
                                    echo_log_to_file('Skipping post "' . esc_html($new_post_title) . '", because it\'s author is not found in the required author list: ' . $author);
                                }
                                continue;
                            }
                        }
                        if(isset($echo_Main_Settings['global_ban_words']) && $echo_Main_Settings['global_ban_words'] != '') {
                            $continue    = false;
                            $echo_Main_Settings['global_ban_words'] = trim(trim(trim($echo_Main_Settings['global_ban_words']), ','));
                            $banned_list = explode(',', $echo_Main_Settings['global_ban_words']);
                            foreach ($banned_list as $banned_word) {
                                if (stripos($new_post_content, trim($banned_word)) !== FALSE) {
                                    if (isset($echo_Main_Settings['enable_detailed_logging'])) {
                                        echo_log_to_file('Skipping post "' . esc_html($new_post_title) . '", because it\'s content contains global banned word: ' . $banned_word);
                                    }
                                    $continue = true;
                                    break;
                                }
                                if (stripos($new_post_title, trim($banned_word)) !== FALSE) {
                                    if (isset($echo_Main_Settings['enable_detailed_logging'])) {
                                        echo_log_to_file('Skipping post "' . esc_html($new_post_title) . '", because it\'s title contains global banned word: ' . $banned_word);
                                    }
                                    $continue = true;
                                    break;
                                }
                            }
                            if ($continue === true) {
                                continue;
                            }
                        }
                        if(isset($echo_Main_Settings['global_req_words']) && $echo_Main_Settings['global_req_words'] != '')
                        {
                            if(isset($echo_Main_Settings['require_only_one']) && $echo_Main_Settings['require_only_one'] == 'on')
                            {
                                $continue      = true;
                                $echo_Main_Settings['global_req_words'] = trim(trim(trim($echo_Main_Settings['global_req_words']), ','));
                                $required_list = explode(',', $echo_Main_Settings['global_req_words']);
                                foreach ($required_list as $required_word) {
                                    if (stripos($new_post_content, trim($required_word)) !== FALSE || stripos($new_post_title, trim($required_word)) !== FALSE) {
                                        $continue = false;
                                        break;
                                    }
                                }
                                if ($continue === true) {
                                    if (isset($echo_Main_Settings['enable_detailed_logging'])) {
                                        echo_log_to_file('Skipping post "' . esc_html($new_post_title) . '", because it\'s content doesn\'t contain global required words.');
                                    }
                                    continue;
                                }
                            }
                            else
                            {
                                $continue      = false;
                                $echo_Main_Settings['global_req_words'] = trim(trim(trim($echo_Main_Settings['global_req_words']), ','));
                                $required_list = explode(',', $echo_Main_Settings['global_req_words']);
                                foreach ($required_list as $required_word) {
                                    if (stripos($new_post_content, trim($required_word)) === FALSE && stripos($new_post_title, trim($required_word)) === FALSE) {
                                        $continue = true;
                                        break;
                                    }
                                }
                                if ($continue === true) {
                                    if (isset($echo_Main_Settings['enable_detailed_logging'])) {
                                        echo_log_to_file('Skipping post "' . esc_html($new_post_title) . '", because it\'s content doesn\'t contain global required words.');
                                    }
                                    continue;
                                }
                            }
                        }
                        if ($required_words != '') {
                            $continue      = true;
                            $continue_and  = false;
                            $required_words = trim(trim(trim($required_words), ','));
                            $required_list = explode(',', $required_words);
                            foreach ($required_list as $required_word) {
                                $required_word = trim($required_word);
                                if(substr($required_word, 0, 2) === "&&")
                                {
                                    $required_word = substr($required_word, 2);
                                    if (stripos($new_post_content, $required_word) === FALSE && stripos($new_post_title, $required_word) === FALSE) {
                                        $continue_and = true;
                                        break;
                                    }
                                }
                                else
                                {
                                    if($restr_target == 'c')
                                    {
                                        if (stripos($new_post_content, $required_word) !== FALSE) {
                                            $continue = false;
                                        }
                                    }
                                    elseif($restr_target == 't')
                                    {
                                        if (stripos($new_post_title, $required_word) !== FALSE) {
                                            $continue = false;
                                        }
                                    }
                                    elseif($restr_target == 'tc')
                                    {
                                        if (stripos($new_post_content, $required_word) !== FALSE || stripos($new_post_title, $required_word) !== FALSE) {
                                            $continue = false;
                                        }
                                    }
                                }
                            }
                            if ($continue === true) {
                                if (isset($echo_Main_Settings['enable_detailed_logging'])) {
                                    echo_log_to_file('Skipping post "' . esc_html($new_post_title) . '", because required words not found: ' . $required_words);
                                }
                                continue;
                            }
                            if ($continue_and === true) {
                                if (isset($echo_Main_Settings['enable_detailed_logging'])) {
                                    echo_log_to_file('Skipping post "' . esc_html($new_post_title) . '", because required word not found: ' . $required_words);
                                }
                                continue;
                            }
                        }
                        if (isset($echo_Main_Settings['strip_links']) && $echo_Main_Settings['strip_links'] == 'on') {
                            $new_post_content = echo_strip_links($new_post_content);
                        }
                        if($replace_url != '')
                        {
                            $new_post_content = preg_replace('/<a(.+?)href=["\']([^"\']+?)["\']([^>]*?)>/i','<a$1href="' . esc_url_raw($replace_url) . '"$3>', $new_post_content);
                        }
                        else
                        {
                            if (isset($echo_Main_Settings['replace_url']) && $echo_Main_Settings['replace_url'] !== '') {
                                $new_post_content = preg_replace('/<a(.+?)href=["\']([^"\']+?)["\']([^>]*?)>/i','<a$1href="' . esc_url_raw($echo_Main_Settings['replace_url']) . '"$3>', $new_post_content);
                            }
                        }
                        if ($strip_images == '1') {
                            $new_post_content = echo_strip_images($new_post_content);
                        }
                        if ($limit_title_count !== "") {
                            $new_post_title = wp_trim_words($new_post_title, $limit_title_count);
                        }
                        if (isset($echo_Main_Settings['link_new_tab']) && $echo_Main_Settings['link_new_tab'] == 'on') {
                            $new_post_content = preg_replace ("/<a([^>]+)>/is","<a$1 target=\"_blank\">", $new_post_content);  
                        }
                        if ((isset($echo_Main_Settings['link_nofollow']) && $echo_Main_Settings['link_nofollow'] == 'on') || $nofollow == '1') {
                            $new_post_content = preg_replace ("/<a([^>]+)>/is","<a rel=\"nofollow\" $1>", $new_post_content);  
                        }
                        if ($img_found == true && isset($echo_Main_Settings['strip_featured_image']) && $echo_Main_Settings['strip_featured_image'] == 'on') {
                            $get_img_tmp = explode('?', $get_img);
                            $get_img_tmp = $get_img_tmp[0];
                            $ext = pathinfo($get_img_tmp, PATHINFO_EXTENSION);
                            $get_img_tmp = preg_quote($get_img_tmp);
                            $get_img_tmp = str_replace('\.' . $ext, '(?:-?\d+x\d+)?\.' . $ext, $get_img_tmp);
                            $new_post_content = preg_replace('#<img(?:[^<>]*?)=[\'"]' . $get_img_tmp . '(?:\?[^<>]*?)?[\'"][^<>]*?\/?>#i', '', $new_post_content);
                        }
                        if ((isset($echo_Main_Settings['link_attributes_internal']) && $echo_Main_Settings['link_attributes_internal'] !== '') || (isset($echo_Main_Settings['link_attributes_external']) && $echo_Main_Settings['link_attributes_external'] !== ''))
                        {
                            $new_post_content = echo_add_link_tags($new_post_content);
                        }
                        if ((isset($echo_Main_Settings['link_append']) && $echo_Main_Settings['link_append'] !== ''))
                        {
                            $new_post_content = echo_append_link_strings($new_post_content, $echo_Main_Settings['link_append']);
                        }
                        if (isset($echo_Main_Settings['iframe_resize_width']) && $echo_Main_Settings['iframe_resize_width'] !== '')
                        {
                            $new_post_content = preg_replace("~<iframe(.*?)(?:width=[\"\'](?:\d*?)[\"\'])?(.*?)>~i", '<iframe$1 width="' . esc_attr($echo_Main_Settings['iframe_resize_width']) . '"$2>', $new_post_content); 
                        }
                        if (isset($echo_Main_Settings['iframe_resize_height']) && $echo_Main_Settings['iframe_resize_height'] !== '')
                        {
                            $new_post_content = preg_replace("~<iframe(.*?)(?:height=[\"\'](?:\d*?)[\"\'])?(.*?)>~i", '<iframe$1 height="' . esc_attr($echo_Main_Settings['iframe_resize_height']) . '"$2>', $new_post_content); 
                        }
                        if($regex_before != '1')
                        {
                            if ($strip_by_regex !== '')
                            {
                                $xstrip_by_regex = preg_split('/\r\n|\r|\n/', $strip_by_regex);
                                $xreplace_regex = preg_split('/\r\n|\r|\n/', $replace_regex);
                                $xcnt = 0;
                                foreach($xstrip_by_regex as $sbr)
                                {
                                    if(isset($xreplace_regex[$xcnt]))
                                    {
                                        $repreg = $xreplace_regex[$xcnt];
                                    }
                                    else
                                    {
                                        $repreg = '';
                                    }
                                    $xcnt++;
                                    $temp_cont = preg_replace("~" . $sbr . "~i", $repreg, $new_post_content);
                                    if($temp_cont !== NULL)
                                    {
                                        $new_post_content = $temp_cont;
                                    }
                                }
                            }
                        }
                        $exc_cont = $content;
                        if($regex_before != '1')
                        {
                            if ($strip_by_regex !== '')
                            {
                                $xstrip_by_regex = preg_split('/\r\n|\r|\n/', $strip_by_regex);
                                $xreplace_regex = preg_split('/\r\n|\r|\n/', $replace_regex);
                                $xcnt = 0;
                                foreach($xstrip_by_regex as $sbr)
                                {
                                    if(isset($xreplace_regex[$xcnt]))
                                    {
                                        $repreg = $xreplace_regex[$xcnt];
                                    }
                                    else
                                    {
                                        $repreg = '';
                                    }
                                    $xcnt++;
                                    $temp_cont = preg_replace("~" . $sbr . "~i", $repreg, $exc_cont);
                                    if($temp_cont !== NULL)
                                    {
                                        $exc_cont = $temp_cont;
                                    }
                                }
                            }
                        }
                        $new_post_content = str_replace('</ iframe>', '</iframe>', $new_post_content);
                        if (isset($echo_Main_Settings['copy_images']) && $echo_Main_Settings['copy_images'] == 'on') {
                            $new_post_content = preg_replace("~\ssrcset=['\"](?:[^'\"]*)['\"]~i", ' ', $new_post_content);
                            preg_match_all('/(http|https|ftp|ftps)?:\/\/\S+\.(?:jpg|jpeg|png|gif)/', $new_post_content, $matches);
                            if(isset($matches[0][0]))
                            {
                                $matches[0] = array_unique($matches[0]);
                                foreach($matches[0] as $match)
                                {
                                    $file_path = echo_copy_image_locally($match, $echo_Main_Settings);
                                    if($file_path != false)
                                    {
                                        $file_path = str_replace('\\', '/', $file_path);
                                        $new_post_content = str_replace($match, $file_path, $new_post_content);
                                    }
                                }
                            }
                        }
                        if (isset($echo_Main_Settings['go_utf']) && $echo_Main_Settings['go_utf'] == 'on' && function_exists('mb_detect_encoding') && function_exists('mb_detect_order') && function_exists('iconv'))
                        {
                            $new_post_title = iconv(mb_detect_encoding($new_post_title, mb_detect_order(), true), "UTF-8", $new_post_title);
                            $new_post_content = iconv(mb_detect_encoding($new_post_content, mb_detect_order(), true), "UTF-8", $new_post_content);
                        }
                        if ($only_text == '1') {
                            $new_post_content = echo_strip_html_tags_nl($new_post_content, $allow_html_tags);
                        }
                        if (isset($echo_Main_Settings['fix_html']) && $echo_Main_Settings['fix_html'] == "on")
                        {
                            $new_post_content = echo_repairHTML($new_post_content);
                        }
                        if($cap_first == '1')
                        {
                            $new_post_title = str_replace('(', '( ', $new_post_title);
                            $new_post_title = implode('-', array_map('ucwords', explode('-', $new_post_title)));
                            $new_post_title = str_replace('( ', '(', $new_post_title);
                        }
                        if($ret_content == 1)
                        {
                            return array($new_post_content, $new_post_title);
                        }
                        $my_post['post_content'] = apply_filters('echo_insertion_post_content', $new_post_content);
                        if($zxdome == true)
                        {
                            $my_post['post_content'] .= '<br/><br/>' . esc_html__('Powered by the', 'rss-feed-post-generator-echo') . ' <a href="https://1.envato.market/echo" target="_blank">Echo RSS Plugin</a>' . ' ' . esc_html__('by', 'rss-feed-post-generator-echo') . ' ' . '<a href="https://coderevolution.ro/" target="_blank">CodeRevolution</a>.';
                        }
                        if (isset($echo_Main_Settings['disable_excerpt']) && $echo_Main_Settings['disable_excerpt'] == "on") {
                            $my_post['post_excerpt'] = '';
                        }
                        else
                        {
                            if (isset($echo_Main_Settings['excerpt_length']) && is_numeric($echo_Main_Settings['excerpt_length'])) {
                                $words = intval($echo_Main_Settings['excerpt_length']);
                            }
                            else
                            {
                                $words = 55;
                            }
                            if(empty($post_excerpt))
                            {
                                if ($translate != "disabled" && $translate != "en") {
                                    $my_post['post_excerpt'] = echo_getExcerpt($new_post_content, $words);
                                } else {
                                    $my_post['post_excerpt'] = echo_getExcerpt($exc_cont, $words);
                                }
                            }
                            else
                            {
                                $new_post_excerpt = echo_replaceContentShortcodes($post_excerpt, $title, $content, $short_url, $extra_categories, $item_tags, $get_img, $feed_title, $feed_description, $description, $feed_logo, $author, $author_link, $author_email, $read_more, $postdate, $custom_tag_var_list, $custom_feed_tag_var_list, $img_attr, $screenimageURL, $feed_uri, $source_id, $xexcerpt);
                                $my_post['post_excerpt'] = echo_getExcerpt($new_post_excerpt, $words);
                            }
                        }
                        $my_post['auto_delete'] = '';
                        if ($auto_delete !== "") {
                            $auto_delete = trim($auto_delete, ' "');
                            $del_time = strtotime($auto_delete);
                            if($del_time !== false)
                            {
                                $my_post['auto_delete'] = $del_time;
                            }
                        }
                        if (isset($echo_Main_Settings['no_dup_titles']) && $echo_Main_Settings['no_dup_titles'] == 'on')
                        {
                            $xposts = get_posts(
                                array(
                                    'post_type'              => $post_type,
                                    'title'                  => html_entity_decode($new_post_title),
                                    'post_status'            => 'all',
                                    'numberposts'            => 1,
                                    'update_post_term_cache' => false,
                                    'update_post_meta_cache' => false,           
                                    'orderby'                => 'post_date ID',
                                    'order'                  => 'ASC',
                                )
                            );
                            if ( ! empty( $xposts ) ) {
                                $zap = $xposts[0];
                            } else {
                                $zap = null;
                            }
                            if ($zap !== NULL) 
                            {
                                if (isset($echo_Main_Settings['enable_detailed_logging'])) {
                                    echo_log_to_file('Skipping post "' . esc_html($new_post_title) . '", because it was found to be already posted (same title post exists)!');
                                }
                                continue;
                            }
                            if (html_entity_decode($new_post_title) != $new_post_title) 
                            {
                                $xposts = get_posts(
                                    array(
                                        'post_type'              => $post_type,
                                        'title'                  => $new_post_title,
                                        'post_status'            => 'all',
                                        'numberposts'            => 1,
                                        'update_post_term_cache' => false,
                                        'update_post_meta_cache' => false,           
                                        'orderby'                => 'post_date ID',
                                        'order'                  => 'ASC',
                                    )
                                );
                                if ( ! empty( $xposts ) ) {
                                    $zap = $xposts[0];
                                } else {
                                    $zap = null;
                                }
                                if ($zap !== NULL) 
                                {
                                    if (isset($echo_Main_Settings['enable_detailed_logging'])) {
                                        echo_log_to_file('Skipping post "' . esc_html($new_post_title) . '", because it was found to be already posted (same title post exists)!');
                                    }
                                    continue;
                                }
                            }
                        }
                        if($copy_slug == '1')
                        {
                            $slugs = explode("/", trim($url, '/'));
                            $zend = end($slugs);
                            if(!empty($zend))
                            {
                                $my_post['post_name'] = apply_filters('echo_insertion_post_name', sanitize_title($zend));
                            }
                        }
                        $my_post['post_title']       = echo_truncate_title($new_post_title);
                        
                        $my_post['original_title']   = $title;
                        $my_post['original_content'] = $content;
                        $my_post['echo_source_feed'] = $feed_uri;
                        $my_post['echo_timestamp']   = echo_get_date_now();
                        $my_post['echo_post_format'] = $post_format;
                        if ($enable_pingback == '1') {
                            $my_post['ping_status'] = 'open';
                        } else {
                            $my_post['ping_status'] = 'closed';
                        }
                        $custom_arr = array();
                        if($custom_fields != '')
                        {
                            if(stristr($custom_fields, '=>') != false)
                            {
                                $rule_arr = explode(',', trim($custom_fields));
                                foreach($rule_arr as $rule)
                                {
                                    $my_args = explode('=>', trim($rule));
                                    if(isset($my_args[1]))
                                    {
                                        $custom_field_content = trim($my_args[1]);
                                        $custom_field_contentx = echo_replaceContentShortcodes($custom_field_content, $new_post_title, $new_post_content, $short_url, $extra_categories, $item_tags, $get_img, $feed_title, $feed_description, $description, $feed_logo, $author, $author_link, $author_email, $read_more, $postdate, $custom_tag_var_list, $custom_feed_tag_var_list, $img_attr, $screenimageURL, $feed_uri, $source_id, $xexcerpt);
                                        if(stristr($my_args[0], '[') !== false && stristr($my_args[0], ']') !== false)
                                        {
                                            preg_match_all('#([^\[\]]*?)\[([^\[\]]*?)\]#', $my_args[0], $cfm);
                                            if(isset($cfm[2][0]))
                                            {
                                                if(isset($custom_arr[trim($cfm[1][0])]) && is_array($custom_arr[trim($cfm[1][0])]))
                                                {
                                                    $custom_arr[trim($cfm[1][0])] = array_merge($custom_arr[trim($cfm[1][0])], array(trim($cfm[2][0]) => $custom_field_contentx));
                                                }
                                                else
                                                {
                                                    $custom_arr[trim($cfm[1][0])] = array(trim($cfm[2][0]) => $custom_field_contentx);
                                                }
                                            }
                                            else
                                            {
                                                $custom_arr[trim($my_args[0])] = $custom_field_contentx;
                                            }
                                        }
                                        else
                                        {
                                            $custom_arr[trim($my_args[0])] = $custom_field_contentx;
                                        }
                                    }
                                }
                            }
                        }
                        $custom_arr = array_merge($custom_arr, array('echo_featured_img' => $get_img));
                        $custom_tax_arr = array();
                        if($custom_tax != '')
                        {
                            if(stristr($custom_tax, '=>') != false)
                            {
                                $rule_arr = explode(';', trim($custom_tax));
                                foreach($rule_arr as $rule)
                                {
                                    $my_args = explode('=>', trim($rule));
                                    if(isset($my_args[1]))
                                    {
                                        $custom_tax_content = trim($my_args[1]);
                                        $custom_tax_contentx = echo_replaceContentShortcodes($custom_tax_content, $new_post_title, $new_post_content, $short_url, $extra_categories, $item_tags, $get_img, $feed_title, $feed_description, $description, $feed_logo, $author, $author_link, $author_email, $read_more, $postdate, $custom_tag_var_list, $custom_feed_tag_var_list, $img_attr, $screenimageURL, $feed_uri, $source_id, $xexcerpt);
                                        if(substr(trim($my_args[0]), 0, 3) === "pa_" && $post_type == 'product' && !empty($custom_tax_contentx))
                                        {
                                            if(isset($custom_arr['_product_attributes']))
                                            {
                                                $custom_arr['_product_attributes'] = array_merge($custom_arr['_product_attributes'], array(trim($my_args[0]) =>array(
                                                    'name' => trim($my_args[0]),
                                                    'value' => $custom_tax_contentx,
                                                    'is_visible' => '1',
                                                    'is_taxonomy' => '1'
                                                )));
                                            }
                                            else
                                            {
                                                $custom_arr['_product_attributes'] = array(trim($my_args[0]) =>array(
                                                    'name' => trim($my_args[0]),
                                                    'value' => $custom_tax_contentx,
                                                    'is_visible' => '1',
                                                    'is_taxonomy' => '1'
                                                ));
                                            }
                                        }
                                        if(isset($custom_tax_arr[trim($my_args[0])]))
                                        {
                                            $custom_tax_arr[trim($my_args[0])] .= ',' . $custom_tax_contentx;
                                        }
                                        else
                                        {
                                            $custom_tax_arr[trim($my_args[0])] = $custom_tax_contentx;
                                        }
                                    }
                                }
                            }
                        }
                        if(count($custom_tax_arr) > 0)
                        {
                            $my_post['taxo_input'] = $custom_tax_arr;
                        }
                        $my_post['meta_input'] = $custom_arr;
                        if($parent_id != '')
                        {
                            $my_post['post_parent'] = intval($parent_id);
                        }
                        if (!isset($echo_Main_Settings['keep_filters']) || $echo_Main_Settings['keep_filters'] != 'on') 
                        {
                            remove_filter('content_save_pre', 'wp_filter_post_kses');
                            remove_filter('content_filtered_save_pre', 'wp_filter_post_kses');remove_filter('title_save_pre', 'wp_filter_kses');
                        }
                        if($update_me !== false)
                        {
                            $my_post['ID'] = $update_me;
                            remove_filter('content_save_pre', 'wp_filter_post_kses');
                            remove_filter('content_filtered_save_pre', 'wp_filter_post_kses');remove_filter('title_save_pre', 'wp_filter_kses');
                            $post_id = wp_update_post($my_post, true);
                            add_filter('content_save_pre', 'wp_filter_post_kses');
                            add_filter('content_filtered_save_pre', 'wp_filter_post_kses');add_filter('title_save_pre', 'wp_filter_kses');
                        }
                        else
                        {
                            $post_id = wp_insert_post($my_post, true);
                        }
                        if (!isset($echo_Main_Settings['keep_filters']) || $echo_Main_Settings['keep_filters'] != 'on') 
                        {
                            add_filter('content_save_pre', 'wp_filter_post_kses');
                            add_filter('content_filtered_save_pre', 'wp_filter_post_kses');add_filter('title_save_pre', 'wp_filter_kses');
                        }
                        if (!is_wp_error($post_id)) {
                            if($post_id === 0)
                            {
                                echo_log_to_file('An error occurred while inserting post into wp database! Title:' . $my_post['post_title']);
                                continue;
                            }
                            $posts_inserted++;
                            if($wpml_lang != '' && function_exists('pll_set_post_language'))
                            {
                                pll_set_post_language($post_id, $wpml_lang);
                            }
                            $default_categories = array();
                            if($remove_default == '1' && ($auto_categories == '1' || (isset($default_category) && $default_category !== 'echo_no_category_12345678' && $default_category[0] !== 'echo_no_category_12345678')))
                            {
                                $default_categories = wp_get_post_categories($post_id);
                            }
                            if ($stick_posts == '1') 
                            {
                                stick_post($post_id);
                            }
                            if(isset($my_post['seo_meta']) && !empty($my_post['seo_meta']))
                            {
                                echo_save_seo_description($post_id, $my_post['seo_meta']);
                            }
                            if(isset($my_post['taxo_input']))
                            {
                                foreach($my_post['taxo_input'] as $taxn => $taxval)
                                {
                                    $taxn = trim($taxn);
                                    $taxval = trim($taxval);
                                    if(is_taxonomy_hierarchical($taxn))
                                    {
                                        $taxval = array_map('trim', explode(',', $taxval));
                                        for($ii = 0; $ii < count($taxval); $ii++)
                                        {
                                            if(!is_numeric($taxval[$ii]))
                                            {
                                                $xtermid = get_term_by('name', $taxval[$ii], $taxn);
                                                if($xtermid !== false)
                                                {
                                                    $taxval[$ii] = intval($xtermid->term_id);
                                                }
                                                else
                                                {
                                                    wp_insert_term( $taxval[$ii], $taxn);
                                                    $xtermid = get_term_by('name', $taxval[$ii], $taxn);
                                                    $wpml_taxn = apply_filters( 'wpml_element_type', $taxn );
                                                    if($xtermid !== false)
                                                    {
                                                        if($wpml_lang != '' && function_exists('pll_set_term_language'))
                                                        {
                                                            pll_set_term_language($xtermid->term_id, $wpml_lang); 
                                                        }
                                                        elseif($wpml_lang != '' && has_filter('wpml_object_id'))
                                                        {
                                                            $pars['element_id'] = $xtermid->term_id;
                                                            $pars['element_type'] = $wpml_taxn;
                                                            $pars['language_code'] = $wpml_lang;
                                                            $pars['trid'] = FALSE;
                                                            $pars['source_language_code'] = NULL;
                                                            do_action('wpml_set_element_language_details', $pars);
                                                        }
                                                        $taxval[$ii] = intval($xtermid->term_id);
                                                    }
                                                }
                                            }
                                        }
                                        wp_set_post_terms($post_id, $taxval, $taxn, true);
                                    }
                                    else
                                    {
                                        if(trim($ids, '/') == $seed_news)
                                        {
                                            $taxval = str_replace(',','%^&',$taxval);
                                            wp_set_post_terms($post_id, trim($taxval), $taxn, true);
                                        }
                                        else
                                        {
                                            wp_set_post_terms($post_id, trim($taxval), $taxn, true);
                                        }
                                    }
                                }
                            }
                            if (isset($my_post['echo_post_format']) && $my_post['echo_post_format'] != '' && $my_post['echo_post_format'] != 'post-format-standard') {
                                wp_set_post_terms($post_id, $my_post['echo_post_format'], 'post_format', true);
                            }
                            if($my_post['screen_attach'] != '')
                            {
                                $media_post = wp_update_post( array(
                                    'ID'            => $my_post['screen_attach'],
                                    'post_parent'   => $post_id,
                                ), true );

                                if( is_wp_error( $media_post ) ) {
                                    echo_log_to_file( 'Failed to assign post attachment ' . $my_post['screen_attach'] . ' to post id ' . $post_id . ': ' . print_r( $media_post, 1 ) );
                                }
                            }
                            $attachments_echo = echo_add_post_attachments($my_post['post_enclosures'], $post_id, $use_proxy);
                            if (isset($echo_Main_Settings['add_gallery']) && $echo_Main_Settings['add_gallery'] == 'on') {
                                if(count($attachments_echo) > 0)
                                {
                                    $att_me = '';
                                    foreach($attachments_echo as $ae)
                                    {
                                        $att_me .= $ae . ',';
                                    }
                                    $att_me = trim($att_me, ',');
                                    if($att_me != '')
                                    {
                                        $the_current_post = get_post( $post_id, 'ARRAY_A' );
                                        $the_current_post['post_content'] .= '[gallery ids="' . esc_html($att_me) . '"]';
                                        add_filter('wp_get_attachment_link', 'echo_remove_img_width_height', 10, 4);
                                        $the_current_post['post_content'] = do_shortcode($the_current_post['post_content']);
                                        remove_filter('content_save_pre', 'wp_filter_post_kses');
                                        remove_filter('content_filtered_save_pre', 'wp_filter_post_kses');remove_filter('title_save_pre', 'wp_filter_kses');
                                        wp_update_post($the_current_post);
                                        add_filter('content_save_pre', 'wp_filter_post_kses');
                                        add_filter('content_filtered_save_pre', 'wp_filter_post_kses');add_filter('title_save_pre', 'wp_filter_kses');
                                        remove_filter('wp_get_attachment_link', 'echo_remove_img_width_height', 10, 4);
                                    }
                                }
                            }
                            if ($update_me !== false && isset($echo_Main_Settings['no_existing_img']) && $echo_Main_Settings['no_existing_img'] == 'on')
                            {
                            }
                            else
                            {    
                                $featured_path = '';
                                $image_failed  = false;
                                if ($featured_image == '1') {
                                    $get_img = $my_post['echo_post_image'];
                                    if ($get_img != '') {
                                        if (!echo_generate_featured_image($get_img, $post_id, $image_captions)) {
                                            $image_failed = true;
                                            if (isset($echo_Main_Settings['enable_detailed_logging'])) {
                                                echo_log_to_file('echo_generate_featured_image failed for ' . $get_img);
                                            }
                                        } else {
                                            $featured_path = $get_img;
                                            update_post_meta( $post_id, 'echo_featured_img', $featured_path );
                                        }
                                    } else {
                                        $image_failed = true;
                                    }
                                }
                                if ($image_failed || $featured_image !== '1') {
                                    if ($image_url != '') {
                                        $replacement = str_replace(array('[', ']'), '', $my_post['post_title']);
                                        $image_url_temp = str_replace('%%item_title%%', $replacement, $image_url);
                                        $image_url_temp = preg_replace_callback('#%%random_image\[([^\]]*?)\]%%#', function ($matches) {
                                            $my_img = echo_get_random_image_google($matches[1]);
                                            return $my_img;
                                        }, $image_url_temp);
                                        $image_urlx = explode(',', $image_url_temp);
                                        $image_urlx = trim($image_urlx[array_rand($image_urlx)]);
                                        $retim = false;
                                        if(is_numeric($image_urlx))
                                        {
                                            $retim = echo_assign_featured_image($image_urlx, $post_id);
                                        }
                                        else
                                        {
                                            if($image_urlx != '')
                                            {
                                                if (isset($echo_Main_Settings['echo_featured_image_checking']) && $echo_Main_Settings['echo_featured_image_checking'] == 'on') {
                                                    $url_headers = echo_get_url_header($image_urlx);
                                                    if (isset($url_headers['Content-Type'])) {
                                                        if (is_array($url_headers['Content-Type'])) {
                                                            $img_type = strtolower($url_headers['Content-Type'][0]);
                                                        } else {
                                                            $img_type = strtolower($url_headers['Content-Type']);
                                                        }
                                                        
                                                        if (strstr($img_type, 'image/') !== false) {
                                                            if (!echo_generate_featured_image($image_urlx, $post_id, $image_captions)) {
                                                                $image_failed = true;
                                                                if (isset($echo_Main_Settings['enable_detailed_logging'])) {
                                                                    echo_log_to_file('echo_generate_featured_image failed to default value: ' . $image_url);
                                                                }
                                                            } else {
                                                                $featured_path = $image_urlx;
                                                                update_post_meta( $post_id, 'echo_featured_img', $featured_path );
                                                            }
                                                        }
                                                    }
                                                }
                                                else
                                                {
                                                    if (!echo_generate_featured_image($image_urlx, $post_id, $image_captions)) {
                                                        $image_failed = true;
                                                        if (isset($echo_Main_Settings['enable_detailed_logging'])) {
                                                            echo_log_to_file('echo_generate_featured_image failed to default value2: ' . $image_url);
                                                        }
                                                    } else {
                                                        $featured_path = $image_urlx;
                                                        update_post_meta( $post_id, 'echo_featured_img', $featured_path );
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                                if($featured_image == '1' && $featured_path == '' && isset($echo_Main_Settings['skip_no_img']) && $echo_Main_Settings['skip_no_img'] == 'on')
                                {
                                    if (isset($echo_Main_Settings['enable_detailed_logging'])) {
                                        echo_log_to_file('Skipping post "' . $my_post['post_title'] . '", because it failed to generate a featured image for: ' . $get_img . ' and ' . $image_url);
                                    }
                                    wp_delete_post($post_id, true);
                                    $posts_inserted--;
                                    continue;
                                }
                            }
                            if($extra_title_categories != '')
                            {
                                $titler = $extra_title_categories;
                                if($parent_category_id != '')
                                {
                                    $termid = echo_create_terms('category', $parent_category_id, $titler, $remove_cats);
                                }
                                else
                                {
                                    $termid = echo_create_terms('category', null, $titler, $remove_cats);
                                }
                                if($wpml_lang != '' && function_exists('pll_set_term_language'))
                                {
                                    foreach($termid as $tx)
                                    {
                                        pll_set_term_language($tx, $wpml_lang); 
                                    }
                                }
                                elseif($wpml_lang != '' && has_filter('wpml_object_id'))
                                {
                                    $wpml_element_type = apply_filters( 'wpml_element_type', 'category' );
                                    foreach($termid as $tx)
                                    {
                                        $pars['element_id'] = $tx;
                                        $pars['element_type'] = $wpml_element_type;
                                        $pars['language_code'] = $wpml_lang;
                                        $pars['trid'] = FALSE;
                                        $pars['source_language_code'] = NULL;
                                        do_action('wpml_set_element_language_details', $pars);
                                    }
                                }
                                wp_set_post_terms($post_id, $termid, 'category', true);
                            }
                            if ($auto_categories == '1') {
                                if (isset($echo_Main_Settings['new_category']) && $echo_Main_Settings['new_category'] == 'on')
                                {
                                    if ($my_post['extra_categories'] != '') {
                                        $titler = $my_post['extra_categories'];
                                        $titler = array_map('trim', explode(',', $titler));
                                        if (isset($echo_Main_Settings['first_feed_cat']) && $echo_Main_Settings['first_feed_cat'] == 'on')
                                        {
                                            $titler = array(array_shift($titler));
                                        }
                                    }
                                    else
                                    {
                                        if (!isset($echo_Main_Settings['disable_cat_extr']) || $echo_Main_Settings['disable_cat_extr'] != 'on')
                                        {
                                            $keyword_class = new Echo_keywords();
                                            $titler = $keyword_class->keywords($my_post['post_title'], 1);
                                            $titler = explode(' ', $titler);
                                        }
                                        else
                                        {
                                            $titler = array();
                                        }
                                    }
                                    $blog_cats = echo_my_list_cats();
                                    $blog_cats = array_map('strtolower', $blog_cats);
                                    $title_words = '';
                                    foreach($titler as $t)
                                    {
                                        if(in_array(strtolower($t), $blog_cats))
                                        {
                                            $title_words .= $t . ',';
                                        }
                                    }
                                    $title_words = trim($title_words, ',');
                                    if($title_words != '')
                                    {
                                        if($parent_category_id != '')
                                        {
                                            $termid = echo_create_terms('category', $parent_category_id, $title_words, $remove_cats);
                                        }
                                        else
                                        {
                                            $termid = echo_create_terms('category', null, $title_words, $remove_cats);
                                        }
                                        if($wpml_lang != '' && function_exists('pll_set_term_language'))
                                        {
                                            foreach($termid as $tx)
                                            {
                                                pll_set_term_language($tx, $wpml_lang); 
                                            }
                                        }
                                        elseif($wpml_lang != '' && has_filter('wpml_object_id'))
                                        {
                                            $wpml_taxn = apply_filters( 'wpml_element_type', 'category' );
                                            foreach($termid as $tx)
                                            {
                                                $pars['element_id'] = $tx;
                                                $pars['element_type'] = $wpml_taxn;
                                                $pars['language_code'] = $wpml_lang;
                                                $pars['trid'] = FALSE;
                                                $pars['source_language_code'] = NULL;
                                                do_action('wpml_set_element_language_details', $pars);
                                            }
                                        }
                                        wp_set_post_terms($post_id, $termid, 'category', true);
                                    }
                                }
                                else
                                {
                                    if ($my_post['extra_categories'] != '') {
                                        if (isset($echo_Main_Settings['first_feed_cat']) && $echo_Main_Settings['first_feed_cat'] == 'on')
                                        {
                                            $titler = explode(',', $my_post['extra_categories']);
                                            $titler = $titler[0];
                                        }
                                        else
                                        {
                                            $titler = $my_post['extra_categories'];
                                        }
                                        if($parent_category_id != '')
                                        {
                                            $termid = echo_create_terms('category', $parent_category_id, $titler, $remove_cats);
                                        }
                                        else
                                        {
                                            $termid = echo_create_terms('category', null, $titler, $remove_cats);
                                        }
                                        if($wpml_lang != '' && function_exists('pll_set_term_language'))
                                        {
                                            foreach($termid as $tx)
                                            {
                                                pll_set_term_language($tx, $wpml_lang); 
                                            }
                                        }
                                        elseif($wpml_lang != '' && has_filter('wpml_object_id'))
                                        {
                                            $wpml_element_type = apply_filters( 'wpml_element_type', 'category' );
                                            foreach($termid as $tx)
                                            {
                                                $pars['element_id'] = $tx;
                                                $pars['element_type'] = $wpml_element_type;
                                                $pars['language_code'] = $wpml_lang;
                                                $pars['trid'] = FALSE;
                                                $pars['source_language_code'] = NULL;
                                                do_action('wpml_set_element_language_details', $pars);
                                            }
                                        }
                                        wp_set_post_terms($post_id, $termid, 'category', true);
                                    }
                                    else
                                    {
                                        if (!isset($echo_Main_Settings['disable_cat_extr']) || $echo_Main_Settings['disable_cat_extr'] != 'on')
                                        {
                                            $keyword_class = new Echo_keywords();
                                            $titler = $keyword_class->keywords($my_post['post_title'], 1);
                                            $titler = str_replace(' ', ',', $titler);
                                            if($parent_category_id != '')
                                            {
                                                $termid = echo_create_terms('category', $parent_category_id, $titler, $remove_cats);
                                            }
                                            else
                                            {
                                                $termid = echo_create_terms('category', null, $titler, $remove_cats);
                                            }
                                            if($wpml_lang != '' && function_exists('pll_set_term_language'))
                                            {
                                                foreach($termid as $tx)
                                                {
                                                    pll_set_term_language($tx, $wpml_lang); 
                                                }
                                            }
                                            elseif($wpml_lang != '' && has_filter('wpml_object_id'))
                                            {
                                                $wpml_element_type = apply_filters( 'wpml_element_type', 'category' );
                                                foreach($termid as $tx)
                                                {
                                                    $pars['element_id'] = $tx;
                                                    $pars['element_type'] = $wpml_element_type;
                                                    $pars['language_code'] = $wpml_lang;
                                                    $pars['trid'] = FALSE;
                                                    $pars['source_language_code'] = NULL;
                                                    do_action('wpml_set_element_language_details', $pars);
                                                }
                                            }
                                            wp_set_post_terms($post_id, $termid, 'category', true);
                                        }
                                    }
                                }
                            }
                            if (isset($default_category) && $default_category !== 'echo_no_category_12345678' && $default_category[0] !== 'echo_no_category_12345678') {
                                if(is_array($default_category))
                                {
                                    $cats   = array();
                                    foreach($default_category as $dc)
                                    {
                                        $cats[] = $dc;
                                    }
                                    global $sitepress;
                                    if($wpml_lang != '' && has_filter('wpml_current_language') && $sitepress != null)
                                    {
                                        $current_language = apply_filters( 'wpml_current_language', NULL );
                                        $sitepress->switch_lang($wpml_lang);
                                    }
                                    wp_set_post_categories($post_id, $cats, true);
                                    if($wpml_lang != '' && function_exists('pll_set_term_language'))
                                    {
                                        foreach($cats as $cc)
                                        {
                                            pll_set_term_language($cc, $wpml_lang);
                                        }
                                    }
                                    elseif($wpml_lang != '' && has_filter('wpml_current_language') && $sitepress != null)
                                    {
                                        $sitepress->switch_lang($current_language);
                                    }
                                }
                                else
                                {
                                    $cats   = array();
                                    $cats[] = $default_category;
                                    global $sitepress;
                                    if($wpml_lang != '' && has_filter('wpml_current_language') && $sitepress != null)
                                    {
                                        $current_language = apply_filters( 'wpml_current_language', NULL );
                                        $sitepress->switch_lang($wpml_lang);
                                    }
                                    wp_set_post_categories($post_id, $cats, true);
                                    if($wpml_lang != '' && function_exists('pll_set_term_language'))
                                    {
                                        foreach($cats as $cc)
                                        {
                                            pll_set_term_language($cc, $wpml_lang);
                                        }
                                    }
                                    elseif($wpml_lang != '' && has_filter('wpml_current_language') && $sitepress != null)
                                    {
                                        $sitepress->switch_lang($current_language);
                                    }
                                }
                            }
                            if($remove_default == '1' && ($auto_categories == '1' || (isset($default_category) && $default_category !== 'echo_no_category_12345678' && $default_category[0] !== 'echo_no_category_12345678')))
                            {
                                $new_categories = wp_get_post_categories($post_id);
                                if(isset($default_categories) && !($default_categories == $new_categories))
                                {
                                    foreach($default_categories as $dc)
                                    {
                                        $rem_cat = get_category( $dc );
                                        wp_remove_object_terms( $post_id, $rem_cat->slug, 'category' );
                                    }
                                }
                            }
                            if (isset($echo_Main_Settings['post_source_custom']) && $echo_Main_Settings['post_source_custom'] != '') {
                                $tax_rez = wp_set_object_terms( $post_id, $echo_Main_Settings['post_source_custom'], 'coderevolution_post_source', true);
                            }
                            else
                            {
                                $tax_rez = wp_set_object_terms( $post_id, 'Echo_' . $param, 'coderevolution_post_source', true);
                            }
                            if (is_wp_error($tax_rez)) {
                                if (isset($echo_Main_Settings['enable_detailed_logging'])) {
                                    echo_log_to_file('wp_set_object_terms failed for: ' . $post_id);
                                }
                            }
                            if($post_type == 'topic' && $parent_id != '')
                            {
                                update_post_meta($post_id, '_bbp_forum_id', $parent_id);
                                update_post_meta($post_id, '_bbp_topic_id', $post_id);
                                update_post_meta($post_id, '_bbp_voice_count', '0');
                                update_post_meta($post_id, '_bbp_reply_count', '0');
                                update_post_meta($post_id, '_bbp_reply_count_hidden', '0');
                                update_post_meta($post_id, '_bbp_last_reply_id', '0');
                                update_post_meta($post_id, '_bbp_last_active_id', $post_id);
                                update_post_meta($post_id, '_bbp_last_active_time', get_post_field( 'post_date', $topic_id, 'db' ));
                                do_action( 'bbp_insert_topic', (int) $post_id, (int) $parent_id );
                            }
                            if($post_type == 'reply' && $parent_id != '')
                            {
                                if(function_exists('bbp_get_topic_forum_id'))
                                {
                                    $forum_aidi = bbp_get_topic_forum_id($parent_id);
                                    if(empty($forum_aidi))
                                    {
                                        $forum_aidi = 0;
                                    }
                                }
                                else
                                {
                                    $forum_aidi = 0;
                                }
                                do_action( 'bbp_insert_reply', (int) $post_id, (int) $parent_id, (int) $forum_aidi );
                            }
                            if($link_source == '1')
                            {
                                $title_link_url = '1';
                            }
                            else
                            {
                                $title_link_url = '0';
                            }
                            echo_addPostMeta($post_id, $my_post, $param, $featured_path, $title_link_url, $echo_Main_Settings);
                            
                            if($wpml_lang != '' && (class_exists('SitePress') || function_exists('wpml_object_id')))
                            {
                                $wpml_element_type = apply_filters( 'wpml_element_type', $post_type );
                                $pars['element_id'] = $post_id;
                                $pars['element_type'] = $wpml_element_type;
                                $pars['language_code'] = $wpml_lang;
                                $pars['source_language_code'] = NULL;
                                do_action('wpml_set_element_language_details', $pars);

                                global $wp_filesystem;
                                if ( ! is_a( $wp_filesystem, 'WP_Filesystem_Base') ){
                                    include_once(ABSPATH . 'wp-admin/includes/file.php');$creds = request_filesystem_credentials( site_url() );
                                    wp_filesystem($creds);
                                }
                                if($wp_filesystem->exists(WP_PLUGIN_DIR . '/sitepress-multilingual-cms/inc/wpml-api.php'))
                                {
                                    include_once( WP_PLUGIN_DIR . '/sitepress-multilingual-cms/inc/wpml-api.php' );
                                }
                                $wpml_lang = trim($wpml_lang);
                                if(function_exists('wpml_update_translatable_content'))
                                {
                                    wpml_update_translatable_content('post_' . $post_type, $post_id, $wpml_lang);
                                    if($my_post['echo_post_full_url'] != '')
                                    {
                                        global $sitepress;
                                        global $wpdb;
                                        $keyid = md5($my_post['echo_post_full_url']);
                                        $keyName = $keyid . '_wpml';
                                        $rezxxxa = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}postmeta WHERE `meta_key` = '$keyName' limit 1", ARRAY_A );
                                        if(count($rezxxxa) != 0)
                                        {
                                            $metaRow = $rezxxxa[0];
                                            $metaValue = $metaRow['meta_value'];
                                            $metaParts = explode('_', $metaValue);
                                            $sitepress->set_element_language_details($post_id, 'post_'.$my_post['post_type'] , $metaParts[0], $wpml_lang, $metaParts[1] ); 
                                        }
                                        else
                                        {
                                            $ptrid = $sitepress->get_element_trid($post_id);
                                            update_post_meta($post_id, $keyid.'_wpml', $ptrid.'_'.$wpml_lang );
                                        }
                                    }
                                    
                                }
                            }
                            if($rel_canonical == '1')
                            {
                                update_post_meta($post_id, 'echo_add_canonical', '1');
                            }
                            if (isset($echo_Main_Settings['draft_first']) && $echo_Main_Settings['draft_first'] == 'on' && $draft_me == true)
                            {
                                echo_change_post_status($post_id, 'publish');
                            }
                            if (isset($echo_Main_Settings['send_post_email']) && $echo_Main_Settings['send_post_email'] == 'on') 
                            {
                                $to = $echo_Main_Settings['email_address'];
                                if (!filter_var($to, FILTER_VALIDATE_EMAIL) === false)
                                {
                                    $subject   = get_the_title($post_id);
                                    $content_post = get_post($post_id);
                                    $message = $content_post->post_content;
                                    $message = str_replace(']]>', ']]&gt;', $message);
                                    $headers[] = 'From: Echo Plugin <echo@noreply.net>';
                                    $headers[] = 'Reply-To: noreply@echo.com';
                                    $headers[] = 'X-Mailer: PHP/' . phpversion();
                                    $headers[] = 'Content-Type: text/html';
                                    $headers[] = 'Charset: ' . get_option('blog_charset', 'UTF-8');
                                    wp_mail($to, $subject, $message, $headers);
                                }
                            }
                            
                        } else {
                            echo_log_to_file('Failed to insert post into database! Title:' . $my_post['post_title'] . '! Error: ' . $post_id->get_error_message() . 'Error code: ' . $post_id->get_error_code() . 'Error data: ' . $post_id->get_error_data());
                            continue;
                        }
                        $count++;
                    }
                }
                catch (Exception $e) 
                {
                    echo_log_to_file('Exception thrown ' . esc_html($e->getMessage()));
                    if($auto == 1)
                    {
                        echo_clearFromList($param);
                    }
                    $xxxfailed = true;
                    continue;
                }
            }
            $items = null;
            unset($items);
            $posted_items = null;
            unset($posted_items);
            if(trim($ids, '/') == $seed_news)
            {
                echo_log_to_file('Ending importing from: ' . $valid_suff_arr[$next_page]);
            }
        }
        catch (Exception $e) {
            echo_log_to_file('Exception encountered ' . esc_html($e->getMessage()));
            if($auto == 1)
            {
                echo_clearFromList($param);
            }
            return 'fail';
        }
        if($xxxfailed == true)
        {
            return 'fail';
        }
        if (isset($echo_Main_Settings['enable_detailed_logging'])) {
            echo_log_to_file('Rule ID ' . esc_html($param) . ' for ' . $feed_uri . ' succesfully run! ' . esc_html($posts_inserted) . ' posts created!');
        }
        if (isset($echo_Main_Settings['send_email']) && $echo_Main_Settings['send_email'] == 'on' && $echo_Main_Settings['email_address'] !== '') {
            if (isset($echo_Main_Settings['email_summary']) && $echo_Main_Settings['email_summary'] == 'on') 
            {
                $last_sent  = get_option('echo_last_sent_email', false);
                if($last_sent == false)
                {
                    $last_sent = date("d.m.y");
                    update_option('echo_last_sent_email', $last_sent);
                }
                $email_content  = get_option('echo_email_content', '');
                $email_content .= '<br/>Rule ID ' . esc_html($param) . ' for ' . $feed_uri . ' successfully run! ' . esc_html($posts_inserted) . ' posts created!';
                if($last_sent != date("d.m.y"))
                {
                    update_option('echo_last_sent_email', date("d.m.y"));
                    update_option('echo_email_content', '');
                    try {
                        $to        = $echo_Main_Settings['email_address'];
                        if (!filter_var($to, FILTER_VALIDATE_EMAIL) === false)
                        {
                            $subject   = '[Echo] Rule running report - ' . echo_get_date_now();
                            $message   = 'Rule ID ' . esc_html($param) . ' for ' . $feed_uri . ' successfully run! ' . esc_html($posts_inserted) . ' posts created!';
                            $headers[] = 'From: Echo Plugin <echo@noreply.net>';
                            $headers[] = 'Reply-To: noreply@echo.com';
                            $headers[] = 'X-Mailer: PHP/' . phpversion();
                            $headers[] = 'Content-Type: text/html';
                            $headers[] = 'Charset: ' . get_option('blog_charset', 'UTF-8');
                            wp_mail($to, $subject, $message, $headers);
                        }
                    }
                    catch (Exception $e) {
                        if (isset($echo_Main_Settings['enable_detailed_logging'])) {
                            echo_log_to_file('Failed to send mail: Exception thrown ' . esc_html($e->getMessage()));
                        }
                    }
                }
                else
                {
                    update_option('echo_email_content', $email_content);
                }
            }
            else
            {
                $getdatex = get_option('echo_last_sent_email', false);
                if($getdatex != false)
                {
                    update_option('echo_last_sent_email', false);
                }
                $getdatex = get_option('echo_email_content', false);
                if($getdatex != false)
                {
                    update_option('echo_email_content', false);
                }
                try {
                    $to        = $echo_Main_Settings['email_address'];
                    if (!filter_var($to, FILTER_VALIDATE_EMAIL) === false)
                    {
                        $subject   = '[Echo] Rule running report - ' . echo_get_date_now();
                        $message   = 'Rule ID ' . esc_html($param) . ' for ' . $feed_uri . ' successfully run! ' . esc_html($posts_inserted) . ' posts created!';
                        $headers[] = 'From: Echo Plugin <echo@noreply.net>';
                        $headers[] = 'Reply-To: noreply@echo.com';
                        $headers[] = 'X-Mailer: PHP/' . phpversion();
                        $headers[] = 'Content-Type: text/html';
                        $headers[] = 'Charset: ' . get_option('blog_charset', 'UTF-8');
                        wp_mail($to, $subject, $message, $headers);
                    }
                }
                catch (Exception $e) {
                    if (isset($echo_Main_Settings['enable_detailed_logging'])) {
                        echo_log_to_file('Failed to send mail: Exception thrown ' . esc_html($e->getMessage()));
                    }
                }
            }
        }
    }
    if ($posts_inserted == 0) {
        if($auto == 1)
        {
            echo_clearFromList($param);
        }
        return 'nochange';
    } else {
        if($auto == 1)
        {
            echo_clearFromList($param);
        }
        return 'ok';
    }
}
function echo_remove_emoji($string)
{
    $regex_alphanumeric = '/[\x{1F100}-\x{1F1FF}]/u';
    $clear_string = preg_replace($regex_alphanumeric, '', $string);

    $regex_symbols = '/[\x{1F300}-\x{1F5FF}]/u';
    $clear_string = preg_replace($regex_symbols, '', $clear_string);

    $regex_emoticons = '/[\x{1F600}-\x{1F64F}]/u';
    $clear_string = preg_replace($regex_emoticons, '', $clear_string);

    $regex_transport = '/[\x{1F680}-\x{1F6FF}]/u';
    $clear_string = preg_replace($regex_transport, '', $clear_string);
    
    $regex_supplemental = '/[\x{1F900}-\x{1F9FF}]/u';
    $clear_string = preg_replace($regex_supplemental, '', $clear_string);

    $regex_misc = '/[\x{2600}-\x{26FF}]/u';
    $clear_string = preg_replace($regex_misc, '', $clear_string);

    $regex_dingbats = '/[\x{2700}-\x{27BF}]/u';
    $clear_string = preg_replace($regex_dingbats, '', $clear_string);

    $regex_other = '/([^-\p{L}\x00-\x7F]+)/u';
    $clear_string = preg_replace($regex_other, '', $clear_string);

    return $clear_string;
}
function echo_change_post_status($post_id, $status){
    $current_post = get_post( $post_id, 'ARRAY_A' );
    $current_post['post_status'] = $status;
    remove_filter('content_save_pre', 'wp_filter_post_kses');
    remove_filter('content_filtered_save_pre', 'wp_filter_post_kses');remove_filter('title_save_pre', 'wp_filter_kses');
    wp_update_post($current_post);
    add_filter('content_save_pre', 'wp_filter_post_kses');
    add_filter('content_filtered_save_pre', 'wp_filter_post_kses');add_filter('title_save_pre', 'wp_filter_kses');
}
function echo_stringMatchWithWildcard($source, $pattern) {
    $pattern = preg_quote($pattern,'/');        
    $pattern = str_replace( '\*' , '.*', $pattern);   
    return preg_match( '~' . $pattern . '~i' , $source );
}

function echo_add_link_tags($content) {
    $content = stripslashes(preg_replace_callback('~<(a\s[^>]+)>~isU', "echo_link_callback", preg_quote($content)));
    return $content;
}

function echo_append_link_strings($content, $append) {
    $content = preg_replace('`((?:http|ftp|https):\/\/(?:[\w_-]+(?:(?:\.[\w_-]+)+))(?:[\w.,@?^=%&:/~+#-]*[\w@?^=%&/~+#-])?)`i','$1' . $append . '', $content);
    return $content;
}

function echo_link_callback($match) { 
    list($original, $tag) = $match;
    $echo_Main_Settings = get_option('echo_Main_Settings', false);
    $blog_url = get_home_url();
    $disallowed = array('http://', 'https://', 'www.');
    foreach($disallowed as $d) {
       $blog_url = str_replace($d, '', $blog_url);
    }
    if (stripos($tag, $blog_url) !== false) {
        if (isset($echo_Main_Settings['link_attributes_internal']) && $echo_Main_Settings['link_attributes_internal'] != '') {
            return "<$tag " . $echo_Main_Settings['link_attributes_internal'] . ">";
        }
    }
    else {
        if (isset($echo_Main_Settings['link_attributes_external']) && $echo_Main_Settings['link_attributes_external'] != '') {
            return "<$tag " . $echo_Main_Settings['link_attributes_external'] . ">";
        }
    }
    return $original;
}

$echo_fatal = false;
function echo_clear_flag_at_shutdown($param)
{
    $error = error_get_last();
    if ($error !== null && $error['type'] === E_ERROR && $GLOBALS['echo_fatal'] === false) {
        $GLOBALS['echo_fatal'] = true;
        $running = array();
        update_option('echo_running_list', $running);
        echo_log_to_file('[FATAL] Exit error: ' . $error['message'] . ', file: ' . $error['file'] . ', line: ' . $error['line'] . ' - rule ID: ' . $param);
        echo_clearFromList($param);
    }
    else
    {
        echo_clearFromList($param);
    }
}

function echo_removeTagByClass(string $html, string $className) 
{
    if($html == '')
    {
        return '';
    }
    $dom = new \DOMDocument();
    $internalErrors = libxml_use_internal_errors(true);
    $dom->loadHTML('<?xml encoding="utf-8" ?>' . $html);
    libxml_use_internal_errors($internalErrors);
    $finder = new \DOMXPath($dom);
    $nodes = $finder->query("//*[contains(concat(' ', normalize-space(@class), ' '), ' {$className} ')]");
    if($nodes !== false && $nodes->length > 0)
    {
        foreach ($nodes as $node) {
            if($node->parentNode !== null)
            {
                $node->parentNode->removeChild($node);
            }
        }
    }
    return $dom->saveHTML();
}
function echo_removeTagByID(string $html, string $className) 
{
    if($html == '')
    {
        return '';
    }
    $dom = new \DOMDocument();
    $internalErrors = libxml_use_internal_errors(true);
    $dom->loadHTML('<?xml encoding="utf-8" ?>' . $html);
    libxml_use_internal_errors($internalErrors);
    $finder = new \DOMXPath($dom);
    $nodes = $finder->query('//*[@id="' . trim($className) . '"]');
    if($nodes !== false && $nodes->length > 0)
    {
        foreach ($nodes as $node) {
            if($node->parentNode !== null)
            {
                $node->parentNode->removeChild($node);
            }
        }
    }
    return $dom->saveHTML();
}
function echo_strip_images($content)
{
    $content = preg_replace("/<img[^>]+\>/i", "", $content); 
    return $content;
}
function echo_get_url_domain($url) {
    $result = parse_url($url);
    if($result === false)
    {
        return $url;
    }
    return $result['scheme']."://".$result['host'];
}
function echo_strip_links($content)
{
    $content = preg_replace('~<a(?:[^>]*)>~', "", $content);
    $content = preg_replace('~<\/a>~', "", $content);
    return $content;
}

add_filter('the_title', 'echo_add_affiliate_title_keyword');
function echo_add_affiliate_title_keyword($content)
{
    $rules  = get_option('echo_keyword_list');
    if(!is_array($rules))
    {
       $rules = array();
    }
    $output = '';
    if (!empty($rules)) {
        foreach ($rules as $request => $value) {
            if(!isset($value[2]) || $value[2] == 'content')
            {
                continue;
            }
            if (is_array($value) && isset($value[1])) {
                $repl = $value[1];
            } else {
                $repl = $request;
            }
            if (isset($value[0]) && $value[0] != '') {
                $content = preg_replace('\'(?!((<.*?)|(<a.*?)))(' . preg_quote($request, '\'') . ')(?!(([^<>]*?)>)|([^>]*?<\/a>))\'i', '<a href="' . esc_url_raw($value[0]) . '" target="_blank">' . esc_html($repl) . '</a>', $content);
            } else {
                $content = preg_replace('#(?!((<.*?)|(<a.*?)))(' . preg_quote($request, '#') . ')(?!(([^<>]*?)>)|([^>]*?<\/a>))#i', $repl, $content);
            }
        }
    }
    return $content;
}
add_filter('the_content', 'echo_add_affiliate_content_keyword');
add_filter('the_excerpt', 'echo_add_affiliate_content_keyword');
function echo_add_affiliate_content_keyword($content)
{
    $rules  = get_option('echo_keyword_list');
    if(!is_array($rules))
    {
       $rules = array();
    }
    $output = '';
    if (!empty($rules)) {
        foreach ($rules as $request => $value) {
            if(isset($value[2]) && $value[2] == 'title')
            {
                continue;
            }
            if (is_array($value) && isset($value[1]) && $value[1] != '') {
                $repl = $value[1];
            } else {
                $repl = $request;
            }
            if (isset($value[0]) && $value[0] != '') {
                $content = preg_replace('\'(?!((<.*?)|(<a.*?)))(' . preg_quote($request, '\'') . ')(?!(([^<>]*?)>)|([^>]*?<\/a>))\'i', '<a href="' . esc_url_raw($value[0]) . '" target="_blank">' . esc_html($repl) . '</a>', $content);
            } else {
                $content = preg_replace('\'(?!((<.*?)|(<a.*?)))(' . preg_quote($request, '\'') . ')(?!(([^<>]*?)>)|([^>]*?<\/a>))\'i', esc_html($repl), $content);
            }
        }
    }
    return $content;
}

function echo_meta_box_function($post)
{
    wp_register_style('echo-browser-style', plugins_url('styles/echo-browser.css', __FILE__), false, ECHO_MAJOR_VERSION);
    wp_enqueue_style('echo-browser-style');
    wp_suspend_cache_addition(true);
    $index                 = get_post_meta($post->ID, 'echo_parent_rule', true);
    $title                 = get_post_meta($post->ID, 'echo_item_title', true);
    $img                   = get_post_meta($post->ID, 'echo_featured_img', true);
    $echo_post_url         = get_post_meta($post->ID, 'echo_post_url', true);
    $echo_delete_time      = get_post_meta($post->ID, 'echo_delete_time', true);
    
    if (isset($index) && $index != '') {
        $ech = '<table class="crf_table"><tr><td><b>' . esc_html__('Post Parent Rule:', 'rss-feed-post-generator-echo') . '</b></td><td>&nbsp;' . esc_html($index) . '</td></tr>';
        $ech .= '<tr><td><b>' . esc_html__('Post Original Title:', 'rss-feed-post-generator-echo') . '</b></td><td>&nbsp;' . esc_html($title) . '</td></tr>';
        if ($img != '') {
            $ech .= '<tr><td><b>' . esc_html__('Featured Image:', 'rss-feed-post-generator-echo') . '</b></td><td>&nbsp;' . esc_url_raw($img) . '</td></tr>';
        }
        if ($echo_post_url != '') {
            $ech .= '<tr><td><b>' . esc_html__('Item Source URL:', 'rss-feed-post-generator-echo') . '</b></td><td>&nbsp;' . esc_url_raw($echo_post_url) . '</td></tr>';
        }
        if ($echo_delete_time != '') {
            $ech .= '<tr><td><b>Auto Delete Post:</b></td><td>&nbsp;' . gmdate("Y-m-d H:i:s", intval($echo_delete_time)) . '</td></tr>';
        }
        $ech .= '</table><br/>';
    } else {
        $ech = esc_html__('This is not an automatically generated post.', 'rss-feed-post-generator-echo');
    }
    echo $ech;
    wp_suspend_cache_addition(false);
}

function echo_generate_random_email()
{
    $tlds = array("com", "net", "gov", "org", "edu", "biz", "info");
    $char = "0123456789abcdefghijklmnopqrstuvwxyz";
    $ulen = mt_rand(5, 10);
    $dlen = mt_rand(7, 17);
    $a = "";
    for ($i = 1; $i <= $ulen; $i++) {
        $a .= substr($char, mt_rand(0, strlen($char)), 1);
    }
    $a .= "@";
    for ($i = 1; $i <= $dlen; $i++) {
        $a .= substr($char, mt_rand(0, strlen($char)), 1);
    }
    $a .= ".";
    $a .= $tlds[mt_rand(0, (sizeof($tlds)-1))];
    return $a;
}

function echo_addPostMeta($post_id, $post, $param, $featured_img, $title_url, $echo_Main_Settings)
{
    update_post_meta($post_id, 'echo_parent_rule', $param);
    update_post_meta($post_id, 'echo_post_full_url', $post['echo_post_full_url']);
    update_post_meta($post_id, 'echo_item_title', $post['original_title']);
    update_post_meta($post_id, 'echo_post_url', $post['echo_post_url']);
    if (!isset($echo_Main_Settings['echo_enable_pingbacks']) || $echo_Main_Settings['echo_enable_pingbacks'] != 'on') {
        update_post_meta($post_id, 'echo_enable_pingbacks', $post['echo_enable_pingbacks']);
    }
    if (!isset($echo_Main_Settings['echo_comment_status']) || $echo_Main_Settings['echo_comment_status'] != 'on') {
        update_post_meta($post_id, 'echo_comment_status', $post['comment_status']);
    }
    
    if (!isset($echo_Main_Settings['echo_extra_categories']) || $echo_Main_Settings['echo_extra_categories'] != 'on') {
        update_post_meta($post_id, 'echo_extra_categories', $post['extra_categories']);
    }
    if (!isset($echo_Main_Settings['echo_extra_tags']) || $echo_Main_Settings['echo_extra_tags'] != 'on') {
        update_post_meta($post_id, 'echo_extra_tags', $post['extra_tags']);
    }
    if (!isset($echo_Main_Settings['echo_source_feed']) || $echo_Main_Settings['echo_source_feed'] != 'on') {
        update_post_meta($post_id, 'echo_source_feed', $post['echo_source_feed']);
    }
    if (!isset($echo_Main_Settings['echo_timestamp']) || $echo_Main_Settings['echo_timestamp'] != 'on') {
        update_post_meta($post_id, 'echo_timestamp', $post['echo_timestamp']);
    }
    if (!isset($echo_Main_Settings['echo_post_date']) || $echo_Main_Settings['echo_post_date'] != 'on') {
        update_post_meta($post_id, 'echo_post_date', $post['echo_post_date']);
    }
    if (!isset($echo_Main_Settings['echo_feed_title']) || $echo_Main_Settings['echo_feed_title'] != 'on') {
        update_post_meta($post_id, 'echo_feed_title', $post['feed_title']);
    }
    if (!isset($echo_Main_Settings['echo_feed_description']) || $echo_Main_Settings['echo_feed_description'] != 'on') {
        update_post_meta($post_id, 'echo_feed_description', $post['feed_description']);
    }
    if (!isset($echo_Main_Settings['feed_logo']) || $echo_Main_Settings['feed_logo'] != 'on') {
        update_post_meta($post_id, 'feed_logo', $post['feed_logo']);
    }
    if (!isset($echo_Main_Settings['echo_author']) || $echo_Main_Settings['echo_author'] != 'on') {
        update_post_meta($post_id, 'echo_author', $post['author']);
    }
    if (!isset($echo_Main_Settings['echo_author_link']) || $echo_Main_Settings['echo_author_link'] != 'on') {
        update_post_meta($post_id, 'echo_author_link', $post['author_link']);
    }
    if (!isset($echo_Main_Settings['echo_author_email']) || $echo_Main_Settings['echo_author_email'] != 'on') {
        update_post_meta($post_id, 'echo_author_email', $post['author_email']);
    }
    if($post['auto_delete'] != '' && is_numeric($post['auto_delete']))
    {
        update_post_meta($post_id, 'echo_delete_time', intval($post['auto_delete']));
    }
    if($title_url == '1')
    {
        update_post_meta($post_id, 'echo_change_title_link', '1');
    }
}

function echo_repairHTML($text)
{
    $text = htmlspecialchars_decode($text);
    $text = str_replace("< ", "<", $text);
    $text = str_replace(" >", ">", $text);
    $text = str_replace("= ", "=", $text);
    $text = str_replace(" =", "=", $text);
    $text = str_replace("\/ ", "\/", $text);
    $text = str_replace("</ iframe>", "</iframe>", $text);
    $text = str_replace("frameborder ", "frameborder=\"0\" allowfullscreen></iframe>", $text);
    $doc = new DOMDocument();
    $doc->substituteEntities = false;
    $internalErrors = libxml_use_internal_errors(true);
    $doc->loadHTML('<?xml encoding="utf-8" ?>' . $text);
    libxml_use_internal_errors($internalErrors);
    $text = $doc->saveHTML();
	$text = preg_replace('#<!DOCTYPE html PUBLIC "-\/\/W3C\/\/DTD HTML 4\.0 Transitional\/\/EN" "http:\/\/www\.w3\.org\/TR\/REC-html40\/loose\.dtd">(?:[^<]*)<\?xml encoding="utf-8" \?><html><body>(?:<p>)?#i', '', $text);
	$text = str_replace('</p></body></html>', '', $text);
    $text = str_replace('</body></html></p>', '', $text);
    $text = str_replace('</body></html>', '', $text);
    return $text;
}
function echo_add_post_attachments($image_urls, $post_id, $use_proxy)
{
    global $wp_filesystem;
    $image_array_ids = array();
    $echo_Main_Settings = get_option('echo_Main_Settings', false);
    $upload_dir = wp_upload_dir();
    foreach($image_urls as $image_url)
    {
        if(isset($image_url->link))
        {
            $image_url = $image_url->link;
        }
        if(stristr($image_url, 'https://www.youtube.com') === false)
        {
            $image_data = echo_get_web_page(html_entity_decode($image_url), '', '', $use_proxy);
            if ($image_data === FALSE || strpos($image_data, '<Message>Access Denied</Message>') !== FALSE || strpos($image_data, '<title>Just a moment...</title>') !== FALSE || strpos($image_data, '<html') !== FALSE) {
                continue;
            }
            $filename = basename($image_url);
            $filename = explode("?", $filename);
            $filename = $filename[0];
            $filename = urlencode($filename);
            $filename = str_replace('%', '-', $filename);
            $filename = str_replace('#', '-', $filename);
            $filename = str_replace('&', '-', $filename);
            $filename = str_replace('{', '-', $filename);
            $filename = str_replace('}', '-', $filename);
            $filename = str_replace('\\', '-', $filename);
            $filename = str_replace('<', '-', $filename);
            $filename = str_replace('>', '-', $filename);
            $filename = str_replace('*', '-', $filename);
            $filename = str_replace('/', '-', $filename);
            $filename = str_replace('$', '-', $filename);
            $filename = str_replace('\'', '-', $filename);
            $filename = str_replace('"', '-', $filename);
            $filename = str_replace(':', '-', $filename);
            $filename = str_replace('@', '-', $filename);
            $filename = str_replace('+', '-', $filename);
            $filename = str_replace('|', '-', $filename);
            $filename = str_replace('=', '-', $filename);
            $filename = str_replace('`', '-', $filename);
            $file_parts = pathinfo($filename);
            
            $post_title = get_the_title($post_id);
            $post_title = remove_accents( $post_title );
            $invalid = array(
                ' '   => '-',
                '%20' => '-',
                '_'   => '-',
            );
            $post_title = str_replace( array_keys( $invalid ), array_values( $invalid ), $post_title );
            $post_title = preg_replace('/[\x{1F3F4}](?:\x{E0067}\x{E0062}\x{E0077}\x{E006C}\x{E0073}\x{E007F})|[\x{1F3F4}](?:\x{E0067}\x{E0062}\x{E0073}\x{E0063}\x{E0074}\x{E007F})|[\x{1F3F4}](?:\x{E0067}\x{E0062}\x{E0065}\x{E006E}\x{E0067}\x{E007F})|[\x{1F3F4}](?:\x{200D}\x{2620}\x{FE0F})|[\x{1F3F3}](?:\x{FE0F}\x{200D}\x{1F308})|[\x{0023}\x{002A}\x{0030}\x{0031}\x{0032}\x{0033}\x{0034}\x{0035}\x{0036}\x{0037}\x{0038}\x{0039}](?:\x{FE0F}\x{20E3})|[\x{1F415}](?:\x{200D}\x{1F9BA})|[\x{1F468}\x{1F469}](?:\x{200D}\x{1F467}\x{200D}\x{1F467})|[\x{1F468}\x{1F469}](?:\x{200D}\x{1F467}\x{200D}\x{1F466})|[\x{1F468}\x{1F469}](?:\x{200D}\x{1F467})|[\x{1F468}\x{1F469}](?:\x{200D}\x{1F466}\x{200D}\x{1F466})|[\x{1F468}\x{1F469}](?:\x{200D}\x{1F466})|[\x{1F468}](?:\x{200D}\x{1F468}\x{200D}\x{1F467}\x{200D}\x{1F467})|[\x{1F468}](?:\x{200D}\x{1F468}\x{200D}\x{1F466}\x{200D}\x{1F466})|[\x{1F468}](?:\x{200D}\x{1F468}\x{200D}\x{1F467}\x{200D}\x{1F466})|[\x{1F468}](?:\x{200D}\x{1F468}\x{200D}\x{1F467})|[\x{1F468}](?:\x{200D}\x{1F468}\x{200D}\x{1F466})|[\x{1F468}\x{1F469}](?:\x{200D}\x{1F469}\x{200D}\x{1F467}\x{200D}\x{1F467})|[\x{1F468}\x{1F469}](?:\x{200D}\x{1F469}\x{200D}\x{1F466}\x{200D}\x{1F466})|[\x{1F468}\x{1F469}](?:\x{200D}\x{1F469}\x{200D}\x{1F467}\x{200D}\x{1F466})|[\x{1F468}\x{1F469}](?:\x{200D}\x{1F469}\x{200D}\x{1F467})|[\x{1F468}\x{1F469}](?:\x{200D}\x{1F469}\x{200D}\x{1F466})|[\x{1F469}](?:\x{200D}\x{2764}\x{FE0F}\x{200D}\x{1F469})|[\x{1F469}\x{1F468}](?:\x{200D}\x{2764}\x{FE0F}\x{200D}\x{1F468})|[\x{1F469}](?:\x{200D}\x{2764}\x{FE0F}\x{200D}\x{1F48B}\x{200D}\x{1F469})|[\x{1F469}\x{1F468}](?:\x{200D}\x{2764}\x{FE0F}\x{200D}\x{1F48B}\x{200D}\x{1F468})|[\x{1F468}\x{1F469}](?:\x{200D}\x{1F9BD})|[\x{1F468}\x{1F469}](?:\x{200D}\x{1F9BC})|[\x{1F468}\x{1F469}](?:\x{200D}\x{1F9AF})|[\x{1F575}\x{1F3CC}\x{26F9}\x{1F3CB}](?:\x{FE0F}\x{200D}\x{2640}\x{FE0F})|[\x{1F575}\x{1F3CC}\x{26F9}\x{1F3CB}](?:\x{FE0F}\x{200D}\x{2642}\x{FE0F})|[\x{1F468}\x{1F469}](?:\x{200D}\x{1F692})|[\x{1F468}\x{1F469}](?:\x{200D}\x{1F680})|[\x{1F468}\x{1F469}](?:\x{200D}\x{2708}\x{FE0F})|[\x{1F468}\x{1F469}](?:\x{200D}\x{1F3A8})|[\x{1F468}\x{1F469}](?:\x{200D}\x{1F3A4})|[\x{1F468}\x{1F469}](?:\x{200D}\x{1F4BB})|[\x{1F468}\x{1F469}](?:\x{200D}\x{1F52C})|[\x{1F468}\x{1F469}](?:\x{200D}\x{1F4BC})|[\x{1F468}\x{1F469}](?:\x{200D}\x{1F3ED})|[\x{1F468}\x{1F469}](?:\x{200D}\x{1F527})|[\x{1F468}\x{1F469}](?:\x{200D}\x{1F373})|[\x{1F468}\x{1F469}](?:\x{200D}\x{1F33E})|[\x{1F468}\x{1F469}](?:\x{200D}\x{2696}\x{FE0F})|[\x{1F468}\x{1F469}](?:\x{200D}\x{1F3EB})|[\x{1F468}\x{1F469}](?:\x{200D}\x{1F393})|[\x{1F468}\x{1F469}](?:\x{200D}\x{2695}\x{FE0F})|[\x{1F471}\x{1F64D}\x{1F64E}\x{1F645}\x{1F646}\x{1F481}\x{1F64B}\x{1F9CF}\x{1F647}\x{1F926}\x{1F937}\x{1F46E}\x{1F482}\x{1F477}\x{1F473}\x{1F9B8}\x{1F9B9}\x{1F9D9}\x{1F9DA}\x{1F9DB}\x{1F9DC}\x{1F9DD}\x{1F9DE}\x{1F9DF}\x{1F486}\x{1F487}\x{1F6B6}\x{1F9CD}\x{1F9CE}\x{1F3C3}\x{1F46F}\x{1F9D6}\x{1F9D7}\x{1F3C4}\x{1F6A3}\x{1F3CA}\x{1F6B4}\x{1F6B5}\x{1F938}\x{1F93C}\x{1F93D}\x{1F93E}\x{1F939}\x{1F9D8}](?:\x{200D}\x{2640}\x{FE0F})|[\x{1F468}\x{1F469}](?:\x{200D}\x{1F9B2})|[\x{1F468}\x{1F469}](?:\x{200D}\x{1F9B3})|[\x{1F468}\x{1F469}](?:\x{200D}\x{1F9B1})|[\x{1F468}\x{1F469}](?:\x{200D}\x{1F9B0})|[\x{1F471}\x{1F64D}\x{1F64E}\x{1F645}\x{1F646}\x{1F481}\x{1F64B}\x{1F9CF}\x{1F647}\x{1F926}\x{1F937}\x{1F46E}\x{1F482}\x{1F477}\x{1F473}\x{1F9B8}\x{1F9B9}\x{1F9D9}\x{1F9DA}\x{1F9DB}\x{1F9DC}\x{1F9DD}\x{1F9DE}\x{1F9DF}\x{1F486}\x{1F487}\x{1F6B6}\x{1F9CD}\x{1F9CE}\x{1F3C3}\x{1F46F}\x{1F9D6}\x{1F9D7}\x{1F3C4}\x{1F6A3}\x{1F3CA}\x{1F6B4}\x{1F6B5}\x{1F938}\x{1F93C}\x{1F93D}\x{1F93E}\x{1F939}\x{1F9D8}](?:\x{200D}\x{2642}\x{FE0F})|[\x{1F441}](?:\x{FE0F}\x{200D}\x{1F5E8}\x{FE0F})|[\x{1F1E6}\x{1F1E7}\x{1F1E8}\x{1F1E9}\x{1F1F0}\x{1F1F2}\x{1F1F3}\x{1F1F8}\x{1F1F9}\x{1F1FA}](?:\x{1F1FF})|[\x{1F1E7}\x{1F1E8}\x{1F1EC}\x{1F1F0}\x{1F1F1}\x{1F1F2}\x{1F1F5}\x{1F1F8}\x{1F1FA}](?:\x{1F1FE})|[\x{1F1E6}\x{1F1E8}\x{1F1F2}\x{1F1F8}](?:\x{1F1FD})|[\x{1F1E6}\x{1F1E7}\x{1F1E8}\x{1F1EC}\x{1F1F0}\x{1F1F2}\x{1F1F5}\x{1F1F7}\x{1F1F9}\x{1F1FF}](?:\x{1F1FC})|[\x{1F1E7}\x{1F1E8}\x{1F1F1}\x{1F1F2}\x{1F1F8}\x{1F1F9}](?:\x{1F1FB})|[\x{1F1E6}\x{1F1E8}\x{1F1EA}\x{1F1EC}\x{1F1ED}\x{1F1F1}\x{1F1F2}\x{1F1F3}\x{1F1F7}\x{1F1FB}](?:\x{1F1FA})|[\x{1F1E6}\x{1F1E7}\x{1F1EA}\x{1F1EC}\x{1F1ED}\x{1F1EE}\x{1F1F1}\x{1F1F2}\x{1F1F5}\x{1F1F8}\x{1F1F9}\x{1F1FE}](?:\x{1F1F9})|[\x{1F1E6}\x{1F1E7}\x{1F1EA}\x{1F1EC}\x{1F1EE}\x{1F1F1}\x{1F1F2}\x{1F1F5}\x{1F1F7}\x{1F1F8}\x{1F1FA}\x{1F1FC}](?:\x{1F1F8})|[\x{1F1E6}\x{1F1E7}\x{1F1E8}\x{1F1EA}\x{1F1EB}\x{1F1EC}\x{1F1ED}\x{1F1EE}\x{1F1F0}\x{1F1F1}\x{1F1F2}\x{1F1F3}\x{1F1F5}\x{1F1F8}\x{1F1F9}](?:\x{1F1F7})|[\x{1F1E6}\x{1F1E7}\x{1F1EC}\x{1F1EE}\x{1F1F2}](?:\x{1F1F6})|[\x{1F1E8}\x{1F1EC}\x{1F1EF}\x{1F1F0}\x{1F1F2}\x{1F1F3}](?:\x{1F1F5})|[\x{1F1E6}\x{1F1E7}\x{1F1E8}\x{1F1E9}\x{1F1EB}\x{1F1EE}\x{1F1EF}\x{1F1F2}\x{1F1F3}\x{1F1F7}\x{1F1F8}\x{1F1F9}](?:\x{1F1F4})|[\x{1F1E7}\x{1F1E8}\x{1F1EC}\x{1F1ED}\x{1F1EE}\x{1F1F0}\x{1F1F2}\x{1F1F5}\x{1F1F8}\x{1F1F9}\x{1F1FA}\x{1F1FB}](?:\x{1F1F3})|[\x{1F1E6}\x{1F1E7}\x{1F1E8}\x{1F1E9}\x{1F1EB}\x{1F1EC}\x{1F1ED}\x{1F1EE}\x{1F1EF}\x{1F1F0}\x{1F1F2}\x{1F1F4}\x{1F1F5}\x{1F1F8}\x{1F1F9}\x{1F1FA}\x{1F1FF}](?:\x{1F1F2})|[\x{1F1E6}\x{1F1E7}\x{1F1E8}\x{1F1EC}\x{1F1EE}\x{1F1F2}\x{1F1F3}\x{1F1F5}\x{1F1F8}\x{1F1F9}](?:\x{1F1F1})|[\x{1F1E8}\x{1F1E9}\x{1F1EB}\x{1F1ED}\x{1F1F1}\x{1F1F2}\x{1F1F5}\x{1F1F8}\x{1F1F9}\x{1F1FD}](?:\x{1F1F0})|[\x{1F1E7}\x{1F1E9}\x{1F1EB}\x{1F1F8}\x{1F1F9}](?:\x{1F1EF})|[\x{1F1E6}\x{1F1E7}\x{1F1E8}\x{1F1EB}\x{1F1EC}\x{1F1F0}\x{1F1F1}\x{1F1F3}\x{1F1F8}\x{1F1FB}](?:\x{1F1EE})|[\x{1F1E7}\x{1F1E8}\x{1F1EA}\x{1F1EC}\x{1F1F0}\x{1F1F2}\x{1F1F5}\x{1F1F8}\x{1F1F9}](?:\x{1F1ED})|[\x{1F1E6}\x{1F1E7}\x{1F1E8}\x{1F1E9}\x{1F1EA}\x{1F1EC}\x{1F1F0}\x{1F1F2}\x{1F1F3}\x{1F1F5}\x{1F1F8}\x{1F1F9}\x{1F1FA}\x{1F1FB}](?:\x{1F1EC})|[\x{1F1E6}\x{1F1E7}\x{1F1E8}\x{1F1EC}\x{1F1F2}\x{1F1F3}\x{1F1F5}\x{1F1F9}\x{1F1FC}](?:\x{1F1EB})|[\x{1F1E6}\x{1F1E7}\x{1F1E9}\x{1F1EA}\x{1F1EC}\x{1F1EE}\x{1F1EF}\x{1F1F0}\x{1F1F2}\x{1F1F3}\x{1F1F5}\x{1F1F7}\x{1F1F8}\x{1F1FB}\x{1F1FE}](?:\x{1F1EA})|[\x{1F1E6}\x{1F1E7}\x{1F1E8}\x{1F1EC}\x{1F1EE}\x{1F1F2}\x{1F1F8}\x{1F1F9}](?:\x{1F1E9})|[\x{1F1E6}\x{1F1E8}\x{1F1EA}\x{1F1EE}\x{1F1F1}\x{1F1F2}\x{1F1F3}\x{1F1F8}\x{1F1F9}\x{1F1FB}](?:\x{1F1E8})|[\x{1F1E7}\x{1F1EC}\x{1F1F1}\x{1F1F8}](?:\x{1F1E7})|[\x{1F1E7}\x{1F1E8}\x{1F1EA}\x{1F1EC}\x{1F1F1}\x{1F1F2}\x{1F1F3}\x{1F1F5}\x{1F1F6}\x{1F1F8}\x{1F1F9}\x{1F1FA}\x{1F1FB}\x{1F1FF}](?:\x{1F1E6})|[\x{00A9}\x{00AE}\x{203C}\x{2049}\x{2122}\x{2139}\x{2194}-\x{2199}\x{21A9}-\x{21AA}\x{231A}-\x{231B}\x{2328}\x{23CF}\x{23E9}-\x{23F3}\x{23F8}-\x{23FA}\x{24C2}\x{25AA}-\x{25AB}\x{25B6}\x{25C0}\x{25FB}-\x{25FE}\x{2600}-\x{2604}\x{260E}\x{2611}\x{2614}-\x{2615}\x{2618}\x{261D}\x{2620}\x{2622}-\x{2623}\x{2626}\x{262A}\x{262E}-\x{262F}\x{2638}-\x{263A}\x{2640}\x{2642}\x{2648}-\x{2653}\x{265F}-\x{2660}\x{2663}\x{2665}-\x{2666}\x{2668}\x{267B}\x{267E}-\x{267F}\x{2692}-\x{2697}\x{2699}\x{269B}-\x{269C}\x{26A0}-\x{26A1}\x{26AA}-\x{26AB}\x{26B0}-\x{26B1}\x{26BD}-\x{26BE}\x{26C4}-\x{26C5}\x{26C8}\x{26CE}-\x{26CF}\x{26D1}\x{26D3}-\x{26D4}\x{26E9}-\x{26EA}\x{26F0}-\x{26F5}\x{26F7}-\x{26FA}\x{26FD}\x{2702}\x{2705}\x{2708}-\x{270D}\x{270F}\x{2712}\x{2714}\x{2716}\x{271D}\x{2721}\x{2728}\x{2733}-\x{2734}\x{2744}\x{2747}\x{274C}\x{274E}\x{2753}-\x{2755}\x{2757}\x{2763}-\x{2764}\x{2795}-\x{2797}\x{27A1}\x{27B0}\x{27BF}\x{2934}-\x{2935}\x{2B05}-\x{2B07}\x{2B1B}-\x{2B1C}\x{2B50}\x{2B55}\x{3030}\x{303D}\x{3297}\x{3299}\x{1F004}\x{1F0CF}\x{1F170}-\x{1F171}\x{1F17E}-\x{1F17F}\x{1F18E}\x{1F191}-\x{1F19A}\x{1F201}-\x{1F202}\x{1F21A}\x{1F22F}\x{1F232}-\x{1F23A}\x{1F250}-\x{1F251}\x{1F300}-\x{1F321}\x{1F324}-\x{1F393}\x{1F396}-\x{1F397}\x{1F399}-\x{1F39B}\x{1F39E}-\x{1F3F0}\x{1F3F3}-\x{1F3F5}\x{1F3F7}-\x{1F3FA}\x{1F400}-\x{1F4FD}\x{1F4FF}-\x{1F53D}\x{1F549}-\x{1F54E}\x{1F550}-\x{1F567}\x{1F56F}-\x{1F570}\x{1F573}-\x{1F57A}\x{1F587}\x{1F58A}-\x{1F58D}\x{1F590}\x{1F595}-\x{1F596}\x{1F5A4}-\x{1F5A5}\x{1F5A8}\x{1F5B1}-\x{1F5B2}\x{1F5BC}\x{1F5C2}-\x{1F5C4}\x{1F5D1}-\x{1F5D3}\x{1F5DC}-\x{1F5DE}\x{1F5E1}\x{1F5E3}\x{1F5E8}\x{1F5EF}\x{1F5F3}\x{1F5FA}-\x{1F64F}\x{1F680}-\x{1F6C5}\x{1F6CB}-\x{1F6D2}\x{1F6D5}\x{1F6E0}-\x{1F6E5}\x{1F6E9}\x{1F6EB}-\x{1F6EC}\x{1F6F0}\x{1F6F3}-\x{1F6FA}\x{1F7E0}-\x{1F7EB}\x{1F90D}-\x{1F93A}\x{1F93C}-\x{1F945}\x{1F947}-\x{1F971}\x{1F973}-\x{1F976}\x{1F97A}-\x{1F9A2}\x{1F9A5}-\x{1F9AA}\x{1F9AE}-\x{1F9CA}\x{1F9CD}-\x{1F9FF}\x{1FA70}-\x{1FA73}\x{1FA78}-\x{1FA7A}\x{1FA80}-\x{1FA82}\x{1FA90}-\x{1FA95}]/u', '', $post_title);
            
            $post_title = preg_replace('/\.(?=.*\.)/', '', $post_title);
            $post_title = preg_replace('/-+/', '-', $post_title);
            $post_title = str_replace('-.', '.', $post_title);
            $post_title = strtolower( $post_title );
            if($post_title == '')
            {
                $post_title = uniqid();
            }
            if(isset($file_parts['extension']))
            {
                switch($file_parts['extension'])
                {
                    case "":
                    if(!echo_endsWith($filename, '.jpg'))
                        $filename .= '.jpg';
                    break;
                    case NULL:
                    if(!echo_endsWith($filename, '.jpg'))
                        $filename .= '.jpg';
                    break;
                    default:
                    if(!echo_endsWith($filename, '.' . $file_parts['extension']))
                        $filename .= '.' . $file_parts['extension'];
                    break;
                }
            }
            else
            {
                if(!echo_endsWith($filename, '.jpg'))
                    $filename .= '.jpg';
            }
            $filename = stripslashes(preg_replace_callback('#(%[a-zA-Z0-9_]*)#', function($matches){ return rand(0, 9); }, preg_quote($filename)));$filename = sanitize_file_name($filename);
            if(strlen($filename) > 100)
            {
                $filename = substr($filename, 0, 90) . uniqid();
            }
            if (wp_mkdir_p($upload_dir['path']))
                $file = $upload_dir['path'] . '/' . $post_id . '-' . $filename;
            else
                $file = $upload_dir['basedir'] . '/' . $post_id . '-' . $filename;
            if ( ! is_a( $wp_filesystem, 'WP_Filesystem_Base') ){
                include_once(ABSPATH . 'wp-admin/includes/file.php');$creds = request_filesystem_credentials( site_url() );
                wp_filesystem($creds);
            }
            $ret = $wp_filesystem->put_contents($file, $image_data);
            if ($ret === FALSE) {
                continue;
            }
            if (function_exists('finfo_open') && (isset($echo_Main_Settings['resize_height']) && $echo_Main_Settings['resize_height'] !== '') || (isset($echo_Main_Settings['resize_width']) && $echo_Main_Settings['resize_width'] !== ''))
            {
                try
                {
                    if(!class_exists('\Gumlet\ImageResize')){require_once (dirname(__FILE__) . "/res/ImageResize/ImageResizeException.php");require_once (dirname(__FILE__) . "/res/ImageResize/ImageResize.php");}
                    $imageRes = new ImageResize($file);
                    if ((isset($echo_Main_Settings['resize_quality']) && $echo_Main_Settings['resize_quality'] !== ''))
                    {
                        $imageRes->quality_jpg = $echo_Main_Settings['resize_quality'];
                        $imageRes->quality_webp = $echo_Main_Settings['resize_quality'];
                        if($echo_Main_Settings['resize_quality'] > 9)
                        {
                            $imageRes->quality_png = 9;
                        }
                        else
                        {
                            $imageRes->quality_png = $echo_Main_Settings['resize_quality'];
                        }
                    }
                    else
                    {
                        $imageRes->quality_jpg = 85;
                        $imageRes->quality_webp = 85;
                        $imageRes->quality_png = 6;
                    }
                    if ((isset($echo_Main_Settings['resize_height']) && $echo_Main_Settings['resize_height'] !== '') && (isset($echo_Main_Settings['resize_width']) && $echo_Main_Settings['resize_width'] !== ''))
                    {
                        $imageRes->resizeToBestFit($echo_Main_Settings['resize_width'], $echo_Main_Settings['resize_height'], true);
                    }
                    elseif (isset($echo_Main_Settings['resize_width']) && $echo_Main_Settings['resize_width'] !== '')
                    {
                        $imageRes->resizeToWidth($echo_Main_Settings['resize_width'], true);
                    }
                    elseif (isset($echo_Main_Settings['resize_height']) && $echo_Main_Settings['resize_height'] !== '')
                    {
                        $imageRes->resizeToHeight($echo_Main_Settings['resize_height'], true);
                    }
                    $imageRes->save($file);
                }
                catch(Exception $e)
                {
                    echo_log_to_file('Failed to resize featured image (att): ' . $image_url . ' to sizes ' . $echo_Main_Settings['resize_width'] . ' - ' . $echo_Main_Settings['resize_height'] . '. Exception thrown ' . esc_html($e->getMessage()));
                }
            }
            $wp_filetype = wp_check_filetype($filename, null);
            if($wp_filetype['type'] == '')
            {
                $wp_filetype['type'] = 'image/png';
            }
            $attachment  = array(
                'post_mime_type' => $wp_filetype['type'],
                'post_title' => $post_title,
                'post_content' => '',
                'post_status' => 'inherit'
            );
            $attach_id   = wp_insert_attachment($attachment, $file, $post_id);
            if ($attach_id === 0) {
                echo_log_to_file('Failed to resize featured image: ' . $file);
            }
            else
            {
                $image_array_ids[] = $attach_id;
            }
        }
    }
    return $image_array_ids;
}

function echo_rel_canonical() 
{
	$link = false;
	if ( is_singular() ) 
    {
        $source_url = get_post_meta(get_the_ID(), 'echo_post_url', true);
        if($source_url != '')
        {
            $link = $source_url;
        }
	}
    if($link !== false)
    {
        $link = apply_filters( 'rel_canonical', $link );
        if ( $link )
        {
            echo "<link rel=\"canonical\" href=\"" . esc_url_raw($link) .  "\" />\n";
        }
    }
}

function echo_startsWith($haystack, $needle)
{
     $length = strlen($needle);
     return (substr($haystack, 0, $length) === $needle);
}

function echo_endsWith($haystack, $needle)
{
    $length = strlen($needle);
    if ($length == 0) {
        return true;
    }

    return (substr($haystack, -$length) === $needle);
}
function echo_generate_featured_image($image_url, $post_id, $image_captions)
{
    global $wp_filesystem;
    $image_url = echo_encodeURI($image_url);
    $echo_Main_Settings = get_option('echo_Main_Settings', false);
    $upload_dir = wp_upload_dir();
    if ( ! is_a( $wp_filesystem, 'WP_Filesystem_Base') ){
        include_once(ABSPATH . 'wp-admin/includes/file.php');$creds = request_filesystem_credentials( site_url() );
        wp_filesystem($creds);
    }
    if(!function_exists('is_plugin_active'))
    {
        include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
    }
    if (isset($echo_Main_Settings['no_local_image']) && $echo_Main_Settings['no_local_image'] == 'on' && (!isset($echo_Main_Settings['url_image']) || $echo_Main_Settings['url_image'] != 'on')) {
        
        if(!echo_url_is_image($image_url))
        {
            return false;
        }
        
        $file = $upload_dir['basedir'] . '/default_img_rss_echo.jpg';
        if(!$wp_filesystem->exists($file))
        {
            $image_data = echo_get_web_page(html_entity_decode(dirname(__FILE__) . "/images/icon.png"));
            if ($image_data === FALSE || strpos($image_data, '<Message>Access Denied</Message>') !== FALSE || strpos($image_data, '<title>Just a moment...</title>') !== FALSE || strpos($image_data, '<html') !== FALSE || strpos($image_data, 'ERROR: The requested URL could not be retrieved') !== FALSE) {
                return false;
            }
            $ret = $wp_filesystem->put_contents($file, $image_data);
            if ($ret === FALSE) {
                return false;
            }
        }
        $need_attach = false;
        $checking_id = get_option('echo_attach_id', false);
        if($checking_id === false)
        {
            $need_attach = true;
        }
        else
        {
            $atturl = wp_get_attachment_url($checking_id);
            if($atturl === false)
            {
                $need_attach = true;
            }
        }
        if($need_attach)
        {
            $filename = basename(dirname(__FILE__) . "/images/icon.png");
            $wp_filetype = wp_check_filetype($filename, null);
            if($wp_filetype['type'] == '')
            {
                $wp_filetype['type'] = 'image/png';
            }
            $attachment  = array(
                'post_mime_type' => $wp_filetype['type'],
                'post_title' => sanitize_file_name($filename),
                'post_content' => '',
                'post_status' => 'inherit'
            );
            
            $attach_id   = wp_insert_attachment($attachment, $file, $post_id);
            if ($attach_id === 0) {
                return false;
            }
            update_option('echo_attach_id', $attach_id);
            require_once(ABSPATH . 'wp-admin/includes/image.php');
            require_once(ABSPATH . 'wp-admin/includes/media.php');
            $attach_data = wp_generate_attachment_metadata($attach_id, $file);
            wp_update_attachment_metadata($attach_id, $attach_data);
        }
        else
        {
            $attach_id = $checking_id;
        }
        $res2 = set_post_thumbnail($post_id, $attach_id);
        if ($res2 === FALSE) {
            return false;
        }
        
        return true;
    }
    elseif (isset($echo_Main_Settings['url_image']) && $echo_Main_Settings['url_image'] == 'on' && (is_plugin_active('featured-image-from-url/featured-image-from-url.php') || is_plugin_active('fifu-premium/fifu-premium.php')))
    {
        if(!echo_url_is_image($image_url))
        {
            echo_log_to_file('Provided remote image is not valid: ' . $image_url);
            return false;
        }
        if(function_exists('fifu_dev_set_image'))
        {
            fifu_dev_set_image($post_id, $image_url);
        }
        else
        {
            $value = echo_get_formatted_value($image_url, '', $post_id);
            $attach_id = echo_insert_attachment_by($value);
            update_post_meta($post_id, '_thumbnail_id', $attach_id);
            update_post_meta($post_id, 'fifu_image_url', $image_url);
            update_post_meta($attach_id, '_wp_attached_file', ';' . $image_url);
            $attach = get_post( $attach_id );
            if($attach !== null)
            {
                $attach->post_author = 77777;
                wp_update_post( $attach );
            }
        }
        return true;
    }
    if(substr( $image_url, 0, 10 ) === "data:image")
    {
        $data = explode(',', $image_url);
        if(isset($data[1]))
        {
            $image_data = base64_decode($data[1]);
            if($image_data === FALSE)
            {
                return false;
            }
        }
        else
        {
            return false;
        }
        preg_match('{data:image/(.*?);}', $image_url ,$ex_matches);
        if(isset($ex_matches[1]))
        {
            $image_url = 'image.' . $ex_matches[1];
        }
        else
        {
            $image_url = 'image.jpg';
        }
    }
    else
    {
        $image_data = echo_get_web_page(html_entity_decode($image_url));
        if ($image_data === FALSE || strpos($image_data, '<Message>Access Denied</Message>') !== FALSE || strpos($image_data, '<title>Just a moment...</title>') !== FALSE || strpos($image_data, '<html') !== FALSE) {
            return false;
        }
    }
    if (isset($echo_Main_Settings['generic_name']) && $echo_Main_Settings['generic_name'] == 'on')
    {
        $filename = 'attachment.jpg';
    }
    else
    {
        $filename = basename($image_url);
        $filename = explode("?", $filename);
        $filename = $filename[0];
        $filename = urlencode($filename);
        $filename = str_replace('%', '-', $filename);
        $filename = str_replace('#', '-', $filename);
        $filename = str_replace('&', '-', $filename);
        $filename = str_replace('{', '-', $filename);
        $filename = str_replace('}', '-', $filename);
        $filename = str_replace('\\', '-', $filename);
        $filename = str_replace('<', '-', $filename);
        $filename = str_replace('>', '-', $filename);
        $filename = str_replace('*', '-', $filename);
        $filename = str_replace('/', '-', $filename);
        $filename = str_replace('$', '-', $filename);
        $filename = str_replace('\'', '-', $filename);
        $filename = str_replace('"', '-', $filename);
        $filename = str_replace(':', '-', $filename);
        $filename = str_replace('@', '-', $filename);
        $filename = str_replace('+', '-', $filename);
        $filename = str_replace('|', '-', $filename);
        $filename = str_replace('=', '-', $filename);
        $filename = str_replace('`', '-', $filename);
    }
    $file_parts = pathinfo($filename);
    $post_title = get_the_title($post_id);
    if((!isset($echo_Main_Settings['generic_name']) || $echo_Main_Settings['generic_name'] != 'on') && $post_title != '')
    {
        $post_title = remove_accents( $post_title );
        $invalid = array(
            ' '   => '-',
            '%20' => '-',
            '_'   => '-',
        );
        $post_title = str_replace( array_keys( $invalid ), array_values( $invalid ), $post_title );
        $post_title = preg_replace('/[\x{1F3F4}](?:\x{E0067}\x{E0062}\x{E0077}\x{E006C}\x{E0073}\x{E007F})|[\x{1F3F4}](?:\x{E0067}\x{E0062}\x{E0073}\x{E0063}\x{E0074}\x{E007F})|[\x{1F3F4}](?:\x{E0067}\x{E0062}\x{E0065}\x{E006E}\x{E0067}\x{E007F})|[\x{1F3F4}](?:\x{200D}\x{2620}\x{FE0F})|[\x{1F3F3}](?:\x{FE0F}\x{200D}\x{1F308})|[\x{0023}\x{002A}\x{0030}\x{0031}\x{0032}\x{0033}\x{0034}\x{0035}\x{0036}\x{0037}\x{0038}\x{0039}](?:\x{FE0F}\x{20E3})|[\x{1F415}](?:\x{200D}\x{1F9BA})|[\x{1F468}\x{1F469}](?:\x{200D}\x{1F467}\x{200D}\x{1F467})|[\x{1F468}\x{1F469}](?:\x{200D}\x{1F467}\x{200D}\x{1F466})|[\x{1F468}\x{1F469}](?:\x{200D}\x{1F467})|[\x{1F468}\x{1F469}](?:\x{200D}\x{1F466}\x{200D}\x{1F466})|[\x{1F468}\x{1F469}](?:\x{200D}\x{1F466})|[\x{1F468}](?:\x{200D}\x{1F468}\x{200D}\x{1F467}\x{200D}\x{1F467})|[\x{1F468}](?:\x{200D}\x{1F468}\x{200D}\x{1F466}\x{200D}\x{1F466})|[\x{1F468}](?:\x{200D}\x{1F468}\x{200D}\x{1F467}\x{200D}\x{1F466})|[\x{1F468}](?:\x{200D}\x{1F468}\x{200D}\x{1F467})|[\x{1F468}](?:\x{200D}\x{1F468}\x{200D}\x{1F466})|[\x{1F468}\x{1F469}](?:\x{200D}\x{1F469}\x{200D}\x{1F467}\x{200D}\x{1F467})|[\x{1F468}\x{1F469}](?:\x{200D}\x{1F469}\x{200D}\x{1F466}\x{200D}\x{1F466})|[\x{1F468}\x{1F469}](?:\x{200D}\x{1F469}\x{200D}\x{1F467}\x{200D}\x{1F466})|[\x{1F468}\x{1F469}](?:\x{200D}\x{1F469}\x{200D}\x{1F467})|[\x{1F468}\x{1F469}](?:\x{200D}\x{1F469}\x{200D}\x{1F466})|[\x{1F469}](?:\x{200D}\x{2764}\x{FE0F}\x{200D}\x{1F469})|[\x{1F469}\x{1F468}](?:\x{200D}\x{2764}\x{FE0F}\x{200D}\x{1F468})|[\x{1F469}](?:\x{200D}\x{2764}\x{FE0F}\x{200D}\x{1F48B}\x{200D}\x{1F469})|[\x{1F469}\x{1F468}](?:\x{200D}\x{2764}\x{FE0F}\x{200D}\x{1F48B}\x{200D}\x{1F468})|[\x{1F468}\x{1F469}](?:\x{200D}\x{1F9BD})|[\x{1F468}\x{1F469}](?:\x{200D}\x{1F9BC})|[\x{1F468}\x{1F469}](?:\x{200D}\x{1F9AF})|[\x{1F575}\x{1F3CC}\x{26F9}\x{1F3CB}](?:\x{FE0F}\x{200D}\x{2640}\x{FE0F})|[\x{1F575}\x{1F3CC}\x{26F9}\x{1F3CB}](?:\x{FE0F}\x{200D}\x{2642}\x{FE0F})|[\x{1F468}\x{1F469}](?:\x{200D}\x{1F692})|[\x{1F468}\x{1F469}](?:\x{200D}\x{1F680})|[\x{1F468}\x{1F469}](?:\x{200D}\x{2708}\x{FE0F})|[\x{1F468}\x{1F469}](?:\x{200D}\x{1F3A8})|[\x{1F468}\x{1F469}](?:\x{200D}\x{1F3A4})|[\x{1F468}\x{1F469}](?:\x{200D}\x{1F4BB})|[\x{1F468}\x{1F469}](?:\x{200D}\x{1F52C})|[\x{1F468}\x{1F469}](?:\x{200D}\x{1F4BC})|[\x{1F468}\x{1F469}](?:\x{200D}\x{1F3ED})|[\x{1F468}\x{1F469}](?:\x{200D}\x{1F527})|[\x{1F468}\x{1F469}](?:\x{200D}\x{1F373})|[\x{1F468}\x{1F469}](?:\x{200D}\x{1F33E})|[\x{1F468}\x{1F469}](?:\x{200D}\x{2696}\x{FE0F})|[\x{1F468}\x{1F469}](?:\x{200D}\x{1F3EB})|[\x{1F468}\x{1F469}](?:\x{200D}\x{1F393})|[\x{1F468}\x{1F469}](?:\x{200D}\x{2695}\x{FE0F})|[\x{1F471}\x{1F64D}\x{1F64E}\x{1F645}\x{1F646}\x{1F481}\x{1F64B}\x{1F9CF}\x{1F647}\x{1F926}\x{1F937}\x{1F46E}\x{1F482}\x{1F477}\x{1F473}\x{1F9B8}\x{1F9B9}\x{1F9D9}\x{1F9DA}\x{1F9DB}\x{1F9DC}\x{1F9DD}\x{1F9DE}\x{1F9DF}\x{1F486}\x{1F487}\x{1F6B6}\x{1F9CD}\x{1F9CE}\x{1F3C3}\x{1F46F}\x{1F9D6}\x{1F9D7}\x{1F3C4}\x{1F6A3}\x{1F3CA}\x{1F6B4}\x{1F6B5}\x{1F938}\x{1F93C}\x{1F93D}\x{1F93E}\x{1F939}\x{1F9D8}](?:\x{200D}\x{2640}\x{FE0F})|[\x{1F468}\x{1F469}](?:\x{200D}\x{1F9B2})|[\x{1F468}\x{1F469}](?:\x{200D}\x{1F9B3})|[\x{1F468}\x{1F469}](?:\x{200D}\x{1F9B1})|[\x{1F468}\x{1F469}](?:\x{200D}\x{1F9B0})|[\x{1F471}\x{1F64D}\x{1F64E}\x{1F645}\x{1F646}\x{1F481}\x{1F64B}\x{1F9CF}\x{1F647}\x{1F926}\x{1F937}\x{1F46E}\x{1F482}\x{1F477}\x{1F473}\x{1F9B8}\x{1F9B9}\x{1F9D9}\x{1F9DA}\x{1F9DB}\x{1F9DC}\x{1F9DD}\x{1F9DE}\x{1F9DF}\x{1F486}\x{1F487}\x{1F6B6}\x{1F9CD}\x{1F9CE}\x{1F3C3}\x{1F46F}\x{1F9D6}\x{1F9D7}\x{1F3C4}\x{1F6A3}\x{1F3CA}\x{1F6B4}\x{1F6B5}\x{1F938}\x{1F93C}\x{1F93D}\x{1F93E}\x{1F939}\x{1F9D8}](?:\x{200D}\x{2642}\x{FE0F})|[\x{1F441}](?:\x{FE0F}\x{200D}\x{1F5E8}\x{FE0F})|[\x{1F1E6}\x{1F1E7}\x{1F1E8}\x{1F1E9}\x{1F1F0}\x{1F1F2}\x{1F1F3}\x{1F1F8}\x{1F1F9}\x{1F1FA}](?:\x{1F1FF})|[\x{1F1E7}\x{1F1E8}\x{1F1EC}\x{1F1F0}\x{1F1F1}\x{1F1F2}\x{1F1F5}\x{1F1F8}\x{1F1FA}](?:\x{1F1FE})|[\x{1F1E6}\x{1F1E8}\x{1F1F2}\x{1F1F8}](?:\x{1F1FD})|[\x{1F1E6}\x{1F1E7}\x{1F1E8}\x{1F1EC}\x{1F1F0}\x{1F1F2}\x{1F1F5}\x{1F1F7}\x{1F1F9}\x{1F1FF}](?:\x{1F1FC})|[\x{1F1E7}\x{1F1E8}\x{1F1F1}\x{1F1F2}\x{1F1F8}\x{1F1F9}](?:\x{1F1FB})|[\x{1F1E6}\x{1F1E8}\x{1F1EA}\x{1F1EC}\x{1F1ED}\x{1F1F1}\x{1F1F2}\x{1F1F3}\x{1F1F7}\x{1F1FB}](?:\x{1F1FA})|[\x{1F1E6}\x{1F1E7}\x{1F1EA}\x{1F1EC}\x{1F1ED}\x{1F1EE}\x{1F1F1}\x{1F1F2}\x{1F1F5}\x{1F1F8}\x{1F1F9}\x{1F1FE}](?:\x{1F1F9})|[\x{1F1E6}\x{1F1E7}\x{1F1EA}\x{1F1EC}\x{1F1EE}\x{1F1F1}\x{1F1F2}\x{1F1F5}\x{1F1F7}\x{1F1F8}\x{1F1FA}\x{1F1FC}](?:\x{1F1F8})|[\x{1F1E6}\x{1F1E7}\x{1F1E8}\x{1F1EA}\x{1F1EB}\x{1F1EC}\x{1F1ED}\x{1F1EE}\x{1F1F0}\x{1F1F1}\x{1F1F2}\x{1F1F3}\x{1F1F5}\x{1F1F8}\x{1F1F9}](?:\x{1F1F7})|[\x{1F1E6}\x{1F1E7}\x{1F1EC}\x{1F1EE}\x{1F1F2}](?:\x{1F1F6})|[\x{1F1E8}\x{1F1EC}\x{1F1EF}\x{1F1F0}\x{1F1F2}\x{1F1F3}](?:\x{1F1F5})|[\x{1F1E6}\x{1F1E7}\x{1F1E8}\x{1F1E9}\x{1F1EB}\x{1F1EE}\x{1F1EF}\x{1F1F2}\x{1F1F3}\x{1F1F7}\x{1F1F8}\x{1F1F9}](?:\x{1F1F4})|[\x{1F1E7}\x{1F1E8}\x{1F1EC}\x{1F1ED}\x{1F1EE}\x{1F1F0}\x{1F1F2}\x{1F1F5}\x{1F1F8}\x{1F1F9}\x{1F1FA}\x{1F1FB}](?:\x{1F1F3})|[\x{1F1E6}\x{1F1E7}\x{1F1E8}\x{1F1E9}\x{1F1EB}\x{1F1EC}\x{1F1ED}\x{1F1EE}\x{1F1EF}\x{1F1F0}\x{1F1F2}\x{1F1F4}\x{1F1F5}\x{1F1F8}\x{1F1F9}\x{1F1FA}\x{1F1FF}](?:\x{1F1F2})|[\x{1F1E6}\x{1F1E7}\x{1F1E8}\x{1F1EC}\x{1F1EE}\x{1F1F2}\x{1F1F3}\x{1F1F5}\x{1F1F8}\x{1F1F9}](?:\x{1F1F1})|[\x{1F1E8}\x{1F1E9}\x{1F1EB}\x{1F1ED}\x{1F1F1}\x{1F1F2}\x{1F1F5}\x{1F1F8}\x{1F1F9}\x{1F1FD}](?:\x{1F1F0})|[\x{1F1E7}\x{1F1E9}\x{1F1EB}\x{1F1F8}\x{1F1F9}](?:\x{1F1EF})|[\x{1F1E6}\x{1F1E7}\x{1F1E8}\x{1F1EB}\x{1F1EC}\x{1F1F0}\x{1F1F1}\x{1F1F3}\x{1F1F8}\x{1F1FB}](?:\x{1F1EE})|[\x{1F1E7}\x{1F1E8}\x{1F1EA}\x{1F1EC}\x{1F1F0}\x{1F1F2}\x{1F1F5}\x{1F1F8}\x{1F1F9}](?:\x{1F1ED})|[\x{1F1E6}\x{1F1E7}\x{1F1E8}\x{1F1E9}\x{1F1EA}\x{1F1EC}\x{1F1F0}\x{1F1F2}\x{1F1F3}\x{1F1F5}\x{1F1F8}\x{1F1F9}\x{1F1FA}\x{1F1FB}](?:\x{1F1EC})|[\x{1F1E6}\x{1F1E7}\x{1F1E8}\x{1F1EC}\x{1F1F2}\x{1F1F3}\x{1F1F5}\x{1F1F9}\x{1F1FC}](?:\x{1F1EB})|[\x{1F1E6}\x{1F1E7}\x{1F1E9}\x{1F1EA}\x{1F1EC}\x{1F1EE}\x{1F1EF}\x{1F1F0}\x{1F1F2}\x{1F1F3}\x{1F1F5}\x{1F1F7}\x{1F1F8}\x{1F1FB}\x{1F1FE}](?:\x{1F1EA})|[\x{1F1E6}\x{1F1E7}\x{1F1E8}\x{1F1EC}\x{1F1EE}\x{1F1F2}\x{1F1F8}\x{1F1F9}](?:\x{1F1E9})|[\x{1F1E6}\x{1F1E8}\x{1F1EA}\x{1F1EE}\x{1F1F1}\x{1F1F2}\x{1F1F3}\x{1F1F8}\x{1F1F9}\x{1F1FB}](?:\x{1F1E8})|[\x{1F1E7}\x{1F1EC}\x{1F1F1}\x{1F1F8}](?:\x{1F1E7})|[\x{1F1E7}\x{1F1E8}\x{1F1EA}\x{1F1EC}\x{1F1F1}\x{1F1F2}\x{1F1F3}\x{1F1F5}\x{1F1F6}\x{1F1F8}\x{1F1F9}\x{1F1FA}\x{1F1FB}\x{1F1FF}](?:\x{1F1E6})|[\x{00A9}\x{00AE}\x{203C}\x{2049}\x{2122}\x{2139}\x{2194}-\x{2199}\x{21A9}-\x{21AA}\x{231A}-\x{231B}\x{2328}\x{23CF}\x{23E9}-\x{23F3}\x{23F8}-\x{23FA}\x{24C2}\x{25AA}-\x{25AB}\x{25B6}\x{25C0}\x{25FB}-\x{25FE}\x{2600}-\x{2604}\x{260E}\x{2611}\x{2614}-\x{2615}\x{2618}\x{261D}\x{2620}\x{2622}-\x{2623}\x{2626}\x{262A}\x{262E}-\x{262F}\x{2638}-\x{263A}\x{2640}\x{2642}\x{2648}-\x{2653}\x{265F}-\x{2660}\x{2663}\x{2665}-\x{2666}\x{2668}\x{267B}\x{267E}-\x{267F}\x{2692}-\x{2697}\x{2699}\x{269B}-\x{269C}\x{26A0}-\x{26A1}\x{26AA}-\x{26AB}\x{26B0}-\x{26B1}\x{26BD}-\x{26BE}\x{26C4}-\x{26C5}\x{26C8}\x{26CE}-\x{26CF}\x{26D1}\x{26D3}-\x{26D4}\x{26E9}-\x{26EA}\x{26F0}-\x{26F5}\x{26F7}-\x{26FA}\x{26FD}\x{2702}\x{2705}\x{2708}-\x{270D}\x{270F}\x{2712}\x{2714}\x{2716}\x{271D}\x{2721}\x{2728}\x{2733}-\x{2734}\x{2744}\x{2747}\x{274C}\x{274E}\x{2753}-\x{2755}\x{2757}\x{2763}-\x{2764}\x{2795}-\x{2797}\x{27A1}\x{27B0}\x{27BF}\x{2934}-\x{2935}\x{2B05}-\x{2B07}\x{2B1B}-\x{2B1C}\x{2B50}\x{2B55}\x{3030}\x{303D}\x{3297}\x{3299}\x{1F004}\x{1F0CF}\x{1F170}-\x{1F171}\x{1F17E}-\x{1F17F}\x{1F18E}\x{1F191}-\x{1F19A}\x{1F201}-\x{1F202}\x{1F21A}\x{1F22F}\x{1F232}-\x{1F23A}\x{1F250}-\x{1F251}\x{1F300}-\x{1F321}\x{1F324}-\x{1F393}\x{1F396}-\x{1F397}\x{1F399}-\x{1F39B}\x{1F39E}-\x{1F3F0}\x{1F3F3}-\x{1F3F5}\x{1F3F7}-\x{1F3FA}\x{1F400}-\x{1F4FD}\x{1F4FF}-\x{1F53D}\x{1F549}-\x{1F54E}\x{1F550}-\x{1F567}\x{1F56F}-\x{1F570}\x{1F573}-\x{1F57A}\x{1F587}\x{1F58A}-\x{1F58D}\x{1F590}\x{1F595}-\x{1F596}\x{1F5A4}-\x{1F5A5}\x{1F5A8}\x{1F5B1}-\x{1F5B2}\x{1F5BC}\x{1F5C2}-\x{1F5C4}\x{1F5D1}-\x{1F5D3}\x{1F5DC}-\x{1F5DE}\x{1F5E1}\x{1F5E3}\x{1F5E8}\x{1F5EF}\x{1F5F3}\x{1F5FA}-\x{1F64F}\x{1F680}-\x{1F6C5}\x{1F6CB}-\x{1F6D2}\x{1F6D5}\x{1F6E0}-\x{1F6E5}\x{1F6E9}\x{1F6EB}-\x{1F6EC}\x{1F6F0}\x{1F6F3}-\x{1F6FA}\x{1F7E0}-\x{1F7EB}\x{1F90D}-\x{1F93A}\x{1F93C}-\x{1F945}\x{1F947}-\x{1F971}\x{1F973}-\x{1F976}\x{1F97A}-\x{1F9A2}\x{1F9A5}-\x{1F9AA}\x{1F9AE}-\x{1F9CA}\x{1F9CD}-\x{1F9FF}\x{1FA70}-\x{1FA73}\x{1FA78}-\x{1FA7A}\x{1FA80}-\x{1FA82}\x{1FA90}-\x{1FA95}]/u', '', $post_title);
        
        $post_title = preg_replace('/\.(?=.*\.)/', '', $post_title);
        $post_title = preg_replace('/-+/', '-', $post_title);
        $post_title = str_replace('-.', '.', $post_title);
        $post_title = strtolower( $post_title );
        if($post_title == '')
        {
            $post_title = uniqid();
        }
        if (isset($echo_Main_Settings['fix_greek']) && $echo_Main_Settings['fix_greek'] == 'on') {
            if(!function_exists('echo_greeklish_slugs'))
            {
                include_once(dirname(__FILE__) . "/res/diacritics.php");
            }
            $post_title = echo_greeklish_slugs($post_title);
        }
        if(isset($file_parts['extension']))
        {
            switch($file_parts['extension'])
            {
                case "":
                $filename = sanitize_title($post_title) . '.jpg';
                break;
                case NULL:
                $filename = sanitize_title($post_title) . '.jpg';
                break;
                default:
                $filename = sanitize_title($post_title) . '.' . $file_parts['extension'];
                break;
            }
        }
        else
        {
            $filename = sanitize_title($post_title) . '.jpg';
        }
    }
    else
    {
        if(isset($file_parts['extension']))
        {
            switch($file_parts['extension'])
            {
                case "":
                if(!echo_endsWith($filename, '.jpg'))
                    $filename .= '.jpg';
                break;
                case NULL:
                if(!echo_endsWith($filename, '.jpg'))
                    $filename .= '.jpg';
                break;
                default:
                if(!echo_endsWith($filename, '.' . $file_parts['extension']))
                    $filename .= '.' . $file_parts['extension'];
                break;
            }
        }
        else
        {
            if(!echo_endsWith($filename, '.jpg'))
                $filename .= '.jpg';
        }
    }
    $filename = stripslashes(preg_replace_callback('#(%[a-zA-Z0-9_]*)#', function($matches){ return rand(0, 9); }, preg_quote($filename)));$filename = sanitize_file_name($filename);
    if(strlen($filename) > 100)
    {
        $filename = substr($filename, 0, 90) . uniqid();
    }
    if (wp_mkdir_p($upload_dir['path']))
        $file = $upload_dir['path'] . '/' . $post_id . '-' . $filename;
    else
        $file = $upload_dir['basedir'] . '/' . $post_id . '-' . $filename;
    if ( ! is_a( $wp_filesystem, 'WP_Filesystem_Base') ){
        include_once(ABSPATH . 'wp-admin/includes/file.php');$creds = request_filesystem_credentials( site_url() );
        wp_filesystem($creds);
    }
    $ret = $wp_filesystem->put_contents($file, $image_data);
    if ($ret === FALSE) {
        return false;
    }
    if (function_exists('finfo_open') && (isset($echo_Main_Settings['resize_height']) && $echo_Main_Settings['resize_height'] !== '') || (isset($echo_Main_Settings['resize_width']) && $echo_Main_Settings['resize_width'] !== ''))
    {
        try
        {
            if(!class_exists('\Gumlet\ImageResize')){require_once (dirname(__FILE__) . "/res/ImageResize/ImageResizeException.php");require_once (dirname(__FILE__) . "/res/ImageResize/ImageResize.php");}
            $imageRes = new ImageResize($file);
            if ((isset($echo_Main_Settings['resize_quality']) && $echo_Main_Settings['resize_quality'] !== ''))
            {
                $imageRes->quality_jpg = $echo_Main_Settings['resize_quality'];
                $imageRes->quality_webp = $echo_Main_Settings['resize_quality'];
                if($echo_Main_Settings['resize_quality'] > 9)
                {
                    $imageRes->quality_png = 9;
                }
                else
                {
                    $imageRes->quality_png = $echo_Main_Settings['resize_quality'];
                }
            }
            else
            {
                $imageRes->quality_jpg = 85;
                $imageRes->quality_webp = 85;
                $imageRes->quality_png = 6;
            }
            if ((isset($echo_Main_Settings['resize_height']) && $echo_Main_Settings['resize_height'] !== '') && (isset($echo_Main_Settings['resize_width']) && $echo_Main_Settings['resize_width'] !== ''))
            {
                $imageRes->resizeToBestFit($echo_Main_Settings['resize_width'], $echo_Main_Settings['resize_height'], true);
            }
            elseif (isset($echo_Main_Settings['resize_width']) && $echo_Main_Settings['resize_width'] !== '')
            {
                $imageRes->resizeToWidth($echo_Main_Settings['resize_width'], true);
            }
            elseif (isset($echo_Main_Settings['resize_height']) && $echo_Main_Settings['resize_height'] !== '')
            {
                $imageRes->resizeToHeight($echo_Main_Settings['resize_height'], true);
            }
            $imageRes->save($file);
        }
        catch(Exception $e)
        {
            echo_log_to_file('Failed to resize featured image (generate): ' . $image_url . ' to sizes ' . $echo_Main_Settings['resize_width'] . ' - ' . $echo_Main_Settings['resize_height'] . '. Exception thrown ' . esc_html($e->getMessage()));
        }
    }
    $wp_filetype = wp_check_filetype($filename, null);
    if($wp_filetype['type'] == '')
    {
        $wp_filetype['type'] = 'image/png';
    }
    $parent_post_title = get_the_title($post_id);
    if($image_captions == '')
    {
        $image_captions = $parent_post_title;
    }
    $attachment  = array(
        'post_mime_type' => $wp_filetype['type'],
        'post_title' => $post_title,
        'post_excerpt' => $image_captions,
        'post_content' => $parent_post_title,
        'post_status' => 'inherit'
    );
    $attach_id   = wp_insert_attachment($attachment, $file, $post_id);
    if ($attach_id === 0) {
        return false;
    }
    require_once(ABSPATH . 'wp-admin/includes/image.php');
    require_once(ABSPATH . 'wp-admin/includes/media.php');
    $attach_data = wp_generate_attachment_metadata($attach_id, $file);
    wp_update_attachment_metadata($attach_id, $attach_data);
    $res2 = set_post_thumbnail($post_id, $attach_id);
    if ($res2 === FALSE) {
        return false;
    }
    update_post_meta($attach_id, '_wp_attachment_image_alt', $post_title);
    return true;
}

function echo_insert_attachment_by($value) {
    global $wpdb;
    $wpdb->get_results("
        INSERT INTO " . $wpdb->prefix . "posts" . " (post_author, guid, post_title, post_mime_type, post_type, post_status, post_parent, post_date, post_date_gmt, post_modified, post_modified_gmt, post_content, post_excerpt, to_ping, pinged, post_content_filtered) 
        VALUES " . $value);
    return $wpdb->insert_id;
}
function echo_get_formatted_value($url, $alt, $post_parent) {
    return "(77777, '" . $url . "', '" . str_replace("'", "", $alt) . "', 'image/jpeg', 'attachment', 'inherit', '" . $post_parent . "', now(), now(), now(), now(), '', '', '', '', '')";
}
function echo_assign_featured_image($attach_id, $post_id)
{
    if ($attach_id === 0 || !is_numeric($attach_id)) {
        return false;
    }
    require_once(ABSPATH . 'wp-admin/includes/image.php');
    require_once(ABSPATH . 'wp-admin/includes/media.php');
    $res2 = set_post_thumbnail($post_id, $attach_id);
    if ($res2 === FALSE) {
        return false;
    }
    return true;
}

function echo_copy_image_locally($image_url, $echo_Main_Settings)
{
    $upload_dir = wp_upload_dir();
    global $wp_filesystem;
    if ( ! is_a( $wp_filesystem, 'WP_Filesystem_Base') ){
        include_once(ABSPATH . 'wp-admin/includes/file.php');$creds = request_filesystem_credentials( site_url() );
        wp_filesystem($creds);
    }
    if(substr( $image_url, 0, 10 ) === "data:image")
    {
        $data = explode(',', $image_url);
        if(isset($data[1]))
        {
            $image_data = base64_decode($data[1]);
            if($image_data === FALSE)
            {
                return false;
            }
        }
        else
        {
            return false;
        }
        preg_match('{data:image/(.*?);}', $image_url ,$ex_matches);
        if(isset($ex_matches[1]))
        {
            $image_url = 'image.' . $ex_matches[1];
        }
        else
        {
            $image_url = 'image.jpg';
        }
    }
    else
    {
        $image_data = echo_get_web_page(html_entity_decode($image_url));
        if ($image_data === FALSE || strpos($image_data, '<Message>Access Denied</Message>') !== FALSE || strpos($image_data, '<title>Just a moment...</title>') !== FALSE || strpos($image_data, '<html') !== FALSE) {
            return false;
        }
    }
    $filename = basename($image_url);
    $filename = explode("?", $filename);
    $filename = $filename[0];
    $filename = urlencode($filename);
    $filename = str_replace('%', '-', $filename);
    $filename = str_replace('#', '-', $filename);
    $filename = str_replace('&', '-', $filename);
    $filename = str_replace('{', '-', $filename);
    $filename = str_replace('}', '-', $filename);
    $filename = str_replace('\\', '-', $filename);
    $filename = str_replace('<', '-', $filename);
    $filename = str_replace('>', '-', $filename);
    $filename = str_replace('*', '-', $filename);
    $filename = str_replace('/', '-', $filename);
    $filename = str_replace('$', '-', $filename);
    $filename = str_replace('\'', '-', $filename);
    $filename = str_replace('"', '-', $filename);
    $filename = str_replace(':', '-', $filename);
    $filename = str_replace('@', '-', $filename);
    $filename = str_replace('+', '-', $filename);
    $filename = str_replace('|', '-', $filename);
    $filename = str_replace('=', '-', $filename);
    $filename = str_replace('`', '-', $filename);
    $file_parts = pathinfo($filename);
    switch($file_parts['extension'])
    {
        case "":
        if(!echo_endsWith($filename, '.jpg'))
            $filename .= 'jpg';
        break;
        case NULL:
        if(!echo_endsWith($filename, '.jpg'))
            $filename .= '.jpg';
        break;
    }
    if (wp_mkdir_p($upload_dir['path'] . '/localimages'))
    {
        $file = $upload_dir['path'] . '/localimages/' . $filename;
        $ret_path = $upload_dir['url'] . '/localimages/' . $filename;
    }
    else
    {
        $file = $upload_dir['basedir'] . '/' . $filename;
        $ret_path = $upload_dir['baseurl'] . '/' . $filename;
    }
    if($wp_filesystem->exists($file))
    {
        if(empty($file_parts['extension']))
        {
            $file_parts['extension'] = 'jpg';
        }
        $unid = uniqid();
        $file .= $unid . '.' . $file_parts['extension'];
        $ret_path .= $unid . '.' . $file_parts['extension'];
    }
    
    $ret = $wp_filesystem->put_contents($file, $image_data);
    if ($ret === FALSE) {
        return false;
    }
    if (!isset($echo_Main_Settings['no_attach']) || $echo_Main_Settings['no_attach'] != 'on') 
    {
        $wp_filetype = wp_check_filetype( $file, null );
        $attachment = array(
            'post_mime_type' => $wp_filetype['type'],
            'post_title' => sanitize_file_name( $file ),
            'post_content' => '',
            'post_status' => 'inherit'
        );
        $screens_attach_id = wp_insert_attachment( $attachment, $file );
        require_once( ABSPATH . 'wp-admin/includes/image.php' );
        require_once( ABSPATH . 'wp-admin/includes/media.php' );
        $attach_data = wp_generate_attachment_metadata( $screens_attach_id, $file );
        wp_update_attachment_metadata( $screens_attach_id, $attach_data );
    }
    return $ret_path;
}

function echo_url_is_image( $url ) {
    $url = str_replace(' ', '%20', $url);
    if ( ! filter_var( $url, FILTER_VALIDATE_URL ) ) {
        return FALSE;
    }
    $ext = array( 'jpeg', 'jpg', 'gif', 'png', 'jpe', 'tif', 'tiff', 'svg', 'ico' , 'webp', 'dds', 'heic', 'psd', 'pspimage', 'tga', 'thm', 'yuv', 'ai', 'eps', 'php');
    
    $info = (array) pathinfo( parse_url( $url, PHP_URL_PATH ) );
    if(!isset( $info['extension'] ))
    {
        return true;
    }
    return isset( $info['extension'] )
        && in_array( strtolower( $info['extension'] ), $ext, TRUE );
}


function echo_hour_diff($date1, $date2)
{
    $date1 = new DateTime($date1, echo_get_blog_timezone());
    $date2 = new DateTime($date2, echo_get_blog_timezone());
    
    $number1 = (int) $date1->format('U');
    $number2 = (int) $date2->format('U');
    return ($number1 - $number2) / 60;
}

function echo_add_hour($date, $hour)
{
    $date1 = new DateTime($date, echo_get_blog_timezone());
    $date1->modify("$hour hours");
    $date1 = (array)$date1;
    foreach ($date1 as $key => $value) {
        if ($key == 'date') {
            return $value;
        }
    }
    return $date;
}

function echo_minute_diff($date1, $date2)
{
    $date1 = new DateTime($date1, echo_get_blog_timezone());
    $date2 = new DateTime($date2, echo_get_blog_timezone());
    
    $number1 = (int) $date1->format('U');
    $number2 = (int) $date2->format('U');
    return ($number1 - $number2);
}

function echo_add_minute($date, $minute)
{
    $date1 = new DateTime($date, echo_get_blog_timezone());
    $date1->modify("$minute minutes");
    $date1 = (array)$date1;
    foreach ($date1 as $key => $value) {
        if ($key == 'date') {
            return $value;
        }
    }
    return $date;
}

function echo_get_blog_timezone() {

    $tzstring = get_option( 'timezone_string' );
    $offset   = get_option( 'gmt_offset' );

    if( empty( $tzstring ) && 0 != $offset && floor( $offset ) == $offset ){
        $offset_st = $offset > 0 ? "-$offset" : '+'.absint( $offset );
        $tzstring  = 'Etc/GMT'.$offset_st;
    }
    if( empty( $tzstring ) ){
        $tzstring = 'UTC';
    }
    $timezone = new DateTimeZone( $tzstring );
    return $timezone; 
}
function echo_get_date_now($param = 'now')
{
    $date = new DateTime($param, echo_get_blog_timezone());
    $date = (array)$date;
    foreach ($date as $key => $value) {
        if ($key == 'date') {
            return $value;
        }
    }
    return '';
}

function echo_create_terms($taxonomy, $parent, $terms_str, $remove_cats)
{
    if($remove_cats != '')
    {
        $remove_cats = explode(',', $remove_cats);
    }
    else
    {
        $remove_cats = array();
    }
    $terms          = explode(',', $terms_str);
    $categories     = array();
    $parent_term_id = $parent;
    foreach ($terms as $term) {
        $term = trim($term);
        $skip = false;
        foreach($remove_cats as $skip)
        {
            if(strcasecmp(trim($skip), $term) == 0)
            {
                $skip = true;
                break;
            }
        }
        if($skip === true)
        {
            continue;
        }
        $res = term_exists($term, $taxonomy, $parent);
        if ($res != NULL && $res != 0 && count($res) > 0 && isset($res['term_id'])) {
            $parent_term_id = $res['term_id'];
            $categories[]   = $parent_term_id;
        } else {
            if($parent === null)
            {
                $insert_parent = 0;
            }
            else
            {
                $insert_parent = $parent;
            }
            $new_term = wp_insert_term($term, $taxonomy, array(
                'parent' => $insert_parent
            ));
            if (!is_wp_error( $new_term ) && $new_term != NULL && $new_term != 0 && count($new_term) > 0 && isset($new_term['term_id'])) {
                $parent_term_id = $new_term['term_id'];
                $categories[]   = $parent_term_id;
            }
        }
    }
    
    return $categories;
}
function echo_getExcerpt($the_content, $words = 55)
{
    if($words == '')
    {
        $words = 55;
    }
    $preview = echo_strip_html_tags($the_content);
    $preview = wp_trim_words($preview, $words, '');
    $preview = trim($preview, ',');
    return $preview;
}

function echo_getPlainContent($the_content)
{
    $preview = echo_strip_html_tags($the_content);
    $preview = wp_trim_words($preview, 999999);
    return $preview;
}
function echo_getItemImage($img, $title)
{
    if($img == '')
    {
        return '';
    }
    $preview = '<img src="' . esc_url_raw($img) . '" alt="' . trim(strip_tags($title)) . '"/>';
    return $preview;
}

function echo_getReadMoreButton($url, $read_more)
{
    $link = '';
    if (isset($url)) {
        $echo_Main_Settings = get_option('echo_Main_Settings', false);
        if($read_more == '')
        {
            if (isset($echo_Main_Settings['read_more_text']) && $echo_Main_Settings['read_more_text'] != '') {
                $read_more = $echo_Main_Settings['read_more_text'];
            }
            else
            {
                $read_more = esc_html__('Read More', 'rss-feed-post-generator-echo');
            }
        }
        $link = '<a href="' . $url . '" class="button purchase" target="_blank">' . esc_html($read_more) . '</a>';
    }
    return $link;
}


add_action('init', 'echo_create_taxonomy', 0);
add_action('wp_loaded', 'echo_run_cron', 0);
function echo_run_cron()
{
    if(isset($_GET['run_echo']))
    {
        $echo_Main_Settings = get_option('echo_Main_Settings', false);
        if(isset($echo_Main_Settings['secret_word']) && $_GET['run_echo'] == urlencode($echo_Main_Settings['secret_word']))
        {
            echo_cron();
            die();
        }
    }
}
add_action( 'enqueue_block_editor_assets', 'echo_enqueue_block_editor_assets' );
function echo_enqueue_block_editor_assets() {
	wp_register_style('echo-browser-style', plugins_url('styles/echo-browser.css', __FILE__), false, ECHO_MAJOR_VERSION);
    wp_enqueue_style('echo-browser-style');
	$block_js_display   = 'scripts/display-posts.js';
	wp_enqueue_script(
		'echo-display-block-js', 
        plugins_url( $block_js_display, __FILE__ ), 
        array(
			'wp-blocks',
			'wp-i18n',
			'wp-element',
		),
        ECHO_MAJOR_VERSION
	);
    $block_js_list   = 'scripts/list-posts.js';
	wp_enqueue_script(
		'echo-list-block-js', 
        plugins_url( $block_js_list, __FILE__ ), 
        array(
			'wp-blocks',
			'wp-i18n',
			'wp-element',
		),
        ECHO_MAJOR_VERSION
	);
    $block_rss_list   = 'scripts/live-rss.js';
	wp_enqueue_script(
		'echo-live-rss-block-js', 
        plugins_url( $block_rss_list, __FILE__ ), 
        array(
			'wp-blocks',
			'wp-i18n',
			'wp-element',
		),
        ECHO_MAJOR_VERSION
	);
}
function echo_create_taxonomy()
{
    $echo_Main_Settings = get_option('echo_Main_Settings', false);
    if (isset($echo_Main_Settings['echo_enabled']) && $echo_Main_Settings['echo_enabled'] === 'on') {
        if (isset($echo_Main_Settings['no_local_image']) && $echo_Main_Settings['no_local_image'] == 'on' && (!isset($echo_Main_Settings['url_image']) || $echo_Main_Settings['url_image'] != 'on')) {
            add_filter('wp_get_attachment_url', 'echo_replace_attachment_url', 10, 2);
            add_filter('wp_get_attachment_image_src', 'echo_replace_attachment_image_src', 10, 3);
            add_filter('post_thumbnail_html', 'echo_thumbnail_external_replace', 10, 6);
        }
    }
    if ( function_exists( 'register_block_type' ) ) {
        register_block_type( 'rss-feed-post-generator-echo/echo-display', array(
            'render_callback' => 'echo_display_posts_shortcode',
        ) );
        register_block_type( 'rss-feed-post-generator-echo/echo-list', array(
            'render_callback' => 'echo_list_posts',
        ) );
        register_block_type( 'rss-feed-post-generator-echo/live-rss', array(
            'render_callback' => 'echo_display_rss_func',
        ) );
    }
    add_image_size( 'echo_preview_image', 260, 146);
    if(!taxonomy_exists('coderevolution_post_source'))
    {
        $labels = array(
            'name' => _x('Post Source', 'taxonomy general name', 'rss-feed-post-generator-echo'),
            'singular_name' => _x('Post Source', 'taxonomy singular name', 'rss-feed-post-generator-echo'),
            'search_items' => esc_html__('Search Post Source', 'rss-feed-post-generator-echo'),
            'popular_items' => esc_html__('Popular Post Source', 'rss-feed-post-generator-echo'),
            'all_items' => esc_html__('All Post Sources', 'rss-feed-post-generator-echo'),
            'parent_item' => null,
            'parent_item_colon' => null,
            'edit_item' => esc_html__('Edit Post Source', 'rss-feed-post-generator-echo'),
            'update_item' => esc_html__('Update Post Source', 'rss-feed-post-generator-echo'),
            'add_new_item' => esc_html__('Add New Post Source', 'rss-feed-post-generator-echo'),
            'new_item_name' => esc_html__('New Post Source Name', 'rss-feed-post-generator-echo'),
            'separate_items_with_commas' => esc_html__('Separate Post Source with commas', 'rss-feed-post-generator-echo'),
            'add_or_remove_items' => esc_html__('Add or remove Post Source', 'rss-feed-post-generator-echo'),
            'choose_from_most_used' => esc_html__('Choose from the most used Post Source', 'rss-feed-post-generator-echo'),
            'not_found' => esc_html__('No Post Sources found.', 'rss-feed-post-generator-echo'),
            'menu_name' => esc_html__('Post Source', 'rss-feed-post-generator-echo')
        );
        
        $args = array(
            'hierarchical' => false,
            'public' => false,
            'show_ui' => false,
            'show_in_menu' => false,
            'description' => 'Post Source',
            'labels' => $labels,
            'show_admin_column' => true,
            'update_count_callback' => '_update_post_term_count',
            'rewrite' => false
        );

        $add_post_type = array(
            'post',
            'page'
        );
        $xargs = array(
            'public'   => true,
            '_builtin' => false
        );
        $output = 'names'; 
        $operator = 'and';
        $post_types = get_post_types( $xargs, $output, $operator );
        if ( $post_types ) 
        {
            foreach ( $post_types  as $post_type ) {
                $add_post_type[] = $post_type;
            }
        }
        register_taxonomy('coderevolution_post_source', $add_post_type, $args);
        add_action('pre_get_posts', function($qry) {
            if (is_admin()) return;
            if (is_tax('coderevolution_post_source')){
                $qry->set_404();
            }
        });
    }
}

register_activation_hook(__FILE__, 'echo_activation_callback');
function echo_activation_callback($defaults = FALSE)
{
    if (!get_option('echo_posts_per_page') || $defaults === TRUE) {
        if ($defaults === FALSE) {
            add_option('echo_posts_per_page', '10', '', false);
        } else {
            update_option('echo_posts_per_page', '10', false);
        }
    }
    if (!get_option('echo_Main_Settings') || $defaults === TRUE) {
        $echo_Main_Settings = array(
            'echo_enabled' => 'on',
            'disable_excerpt' => 'on',
            'excerpt_length' => '55',
            'def_user' => '1',
            'enable_metabox' => 'on',
            'skip_no_img' => '',
            'skip_no_full' => '',
            'require_only_one' => '',
            'skip_old' => '',
            'skip_year' => '',
            'skip_month' => '',
            'skip_day' => '',
            'skip_new' => '',
            'skip_year_new' => '',
            'skip_month_new' => '',
            'skip_day_new' => '',
            'custom_html2' => '',
            'custom_html' => '',
            'echo_get_image_from_content' => 'on',
            'sentence_list' => 'This is one %adjective %noun %sentence_ending
This is another %adjective %noun %sentence_ending
I %love_it %nouns , because they are %adjective %sentence_ending
My %family says this plugin is %adjective %sentence_ending
These %nouns are %adjective %sentence_ending',
            'sentence_list2' => 'Meet this %adjective %noun %sentence_ending
This is the %adjective %noun ever %sentence_ending
I %love_it %nouns , because they are the %adjective %sentence_ending
My %family says this plugin is very %adjective %sentence_ending
These %nouns are quite %adjective %sentence_ending',
            'variable_list' => 'adjective_very => %adjective;very %adjective;

adjective => clever;interesting;smart;huge;astonishing;unbelievable;nice;adorable;beautiful;elegant;fancy;glamorous;magnificent;helpful;awesome

noun_with_adjective => %noun;%adjective %noun

noun => plugin;WordPress plugin;item;ingredient;component;constituent;module;add-on;plug-in;addon;extension

nouns => plugins;WordPress plugins;items;ingredients;components;constituents;modules;add-ons;plug-ins;addons;extensions

love_it => love;adore;like;be mad for;be wild about;be nuts about;be crazy about

family => %adjective %family_members;%family_members

family_members => grandpa;brother;sister;mom;dad;grandma

sentence_ending => .;!;!!',
            'auto_clear_logs' => 'No',
            'enable_logging' => 'on',
            'enable_detailed_logging' => '',
            'rule_timeout' => '3600',
            'request_delay' => '',
            'strip_links' => '',
            'strip_content_links' => '',
            'strip_content_html' => '',
            'link_new_tab' => '',
            'link_nofollow' => '',
            'strip_featured_image' => '',
            'strip_scripts' => '',
            'email_address' => '',
            'email_summary' => '',
            'send_email' => '',
            'send_post_email' => '',
            'best_password' => '',
            'only_imported' => '',
            'protected_terms' => '',
            'phantom_timeout' => '',
            'phantom_screen' => '',
            'headless_screen' => '',
            'puppeteer_screen' => '',
            'disable_fallback' => '',
            'phantom_path' => '',
            'screenshot_height' => '',
            'screenshot_width' => '',
            'best_user' => '',
            'wordai_uniqueness' => '',
            'spin_text' => 'disabled',
            'enable_robots' => '',
            'refresh_url' => '',
            'google_trans_auth' => '',
            'deepl_auth' => '',
            'deppl_free' => '',
            'bing_auth' => '',
            'bing_region' => '',
            'xmlrpc_enabled' => '',
            'max_word_content' => '',
            'min_word_content' => '',
            'max_word_title' => '',
            'min_word_title' => '',
            'echo_custom_simplepie' => 'on',
            'echo_force_feeds' => '',
            'echo_enable_caching' => '',
            'echo_no_strip' => 'on',
            'echo_featured_image_checking' => '',
            'generic_name' => '',
            'keep_filters' => '',
            'fix_greek' => '',
            'echo_clear_curl_charset' => '',
            'custom_feed_timeout' => '',
            'custom_tag_list' => '',
            'custom_tag_separator' => ':',
            'custom_attr_separator' => '*',
            'custom_feed_tag_list' => '',
            'search_google' => '',
            'post_source_custom' => '',
            'date_locale' => '',
            'resize_width' => '',
            'resize_height' => '',
            'resize_quality' => '',
            'read_more_text' => 'Read More',
            'conditional_words' => '',
            'no_local_image' => '',
            'url_image' => '',
            'clear_user_agent' => '',
            'auto_delete_enabled' => '',
            'no_title_spin' => '',
            'no_failed_trans' => '',
            'fix_html' => '',
            'confidence_level' => 'medium',
            'tldr_max' => '',
            'tldr_min' => '',
            'copy_images' => '',
            'no_attach' => '',
            'rule_delay' => '',
            'no_spin' => '',
            'replace_url' => '',
            'link_attributes_external' => '',
            'link_append' => '',
            'date_format' => '',
            'link_attributes_internal' => '',
            'do_not_check_duplicates' => '',
            'check_title' => '',
            'no_dup_titles' => '',
            'disable_backend_content' => '',
            'author_roles' => '',
            'run_after' => '',
            'run_before' => '',
            'append_enclosure' => '',
            'add_attachments' => '',
            'add_gallery' => '',
            'link_source' => '',
            'redir_302' => '',
            'no_canonical_removal' => '',
            'rel_canonical' => '',
            'no_link_translate' => 'on',
            'strip_emoticons' => '',
            'strip_non_char' => '',
            'scrape_seo' => '',
            'iframe_resize_height' => '',
            'iframe_resize_width' => '',
            'skip_image_names' => '',
            'no_check' => '',
            'link_feed_source' => '',
            'textrazor_key' => '',
            'draft_first' => '',
            'go_utf' => '',
            'full_descri' => '',
            'global_req_words' => '',
            'global_ban_words' => '',
            'global_ban_urls' => '',
            'new_category' => '',
            'skip_first_tag' => '',
            'first_feed_cat' => '',
            'no_existing_img' => '',
            'proxy_auth' => '',
            'secret_word' => '',
            'proxy_url' => '',
            'disable_cat_extr' => '',
            'flickr_order' => 'date-posted-desc',
            'flickr_license' => '-1',
            'flickr_api' => '',
            'scrapeimg_height' => '',
            'attr_text' => 'Photo Credit: <a href="%%image_source_url%%" target="_blank">%%image_source_name%%</a>',
            'scrapeimg_width' => '',
            'scrapeimg_cat' => 'all',
            'scrapeimg_order' => 'any',
            'scrapeimg_orientation' => 'all',
            'imgtype' => 'all',
            'pixabay_api' => '',
            'pexels_api' => '',
            'morguefile_secret' => '',
            'morguefile_api' => '',
            'bimage' => 'on',
            'no_royalty_skip' => '',
            'no_orig' => '',
            'img_order' => 'popular',
            'img_cat' => 'all',
            'img_width' => '',
            'img_mwidth' => '',
            'img_ss' => '',
            'img_editor' => '',
            'img_language' => 'any',
            'pixabay_scrape' => '',
            'unsplash_key' => '',
            'google_images' => '',
            'scrapeimgtype' => 'all',
            'echo_author_email' => '',
            'echo_author_link' => '',
            'echo_author' => '',
            'feed_logo' => '',
            'echo_feed_description' => '',
            'echo_feed_title' => '',
            'echo_post_date' => '',
            'echo_timestamp' => '',
            'echo_source_feed' => '',
            'echo_extra_tags' => '',
            'echo_extra_categories' => '',
            'echo_comment_status' => '',
            'echo_enable_pingbacks' => '',
            'clean_uninstall' => ''
        );
        if ($defaults === FALSE) {
            add_option('echo_Main_Settings', $echo_Main_Settings, '', false);
        } else {
            update_option('echo_Main_Settings', $echo_Main_Settings, false);
        }
    }
}
register_uninstall_hook( __FILE__, 'echo_uninstall_called_hook' );
function echo_uninstall_called_hook() 
{
    $echo_Main_Settings = get_option('echo_Main_Settings', false);
    if (isset($echo_Main_Settings['clean_uninstall']) && $echo_Main_Settings['clean_uninstall'] === 'on') 
    {
        delete_option( 'echo_Main_Settings' );
        delete_option( 'echo_rules_list' );
    }
}

function echo_generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}
function echo_get_free_image($echo_Main_Settings, $query_words, &$img_attr, $res_cnt = 3)
{
    $original_url = '';
    $rand_arr = array();
    if(isset($echo_Main_Settings['pixabay_api']) && $echo_Main_Settings['pixabay_api'] != '')
    {
        $rand_arr[] = 'pixabay';
    }
    if(isset($echo_Main_Settings['flickr_api']) && $echo_Main_Settings['flickr_api'] !== '')
    {
        $rand_arr[] = 'flickr';
    }
    if(isset($echo_Main_Settings['pexels_api']) && $echo_Main_Settings['pexels_api'] !== '')
    {
        $rand_arr[] = 'pexels';
    }
    if(isset($echo_Main_Settings['pixabay_scrape']) && $echo_Main_Settings['pixabay_scrape'] == 'on')
    {
        $rand_arr[] = 'pixabayscrape';
    }
    if(isset($echo_Main_Settings['unsplash_key']) && $echo_Main_Settings['unsplash_key'] != '')
    {
        $rand_arr[] = 'unsplash';
    }
    if(isset($echo_Main_Settings['google_images']) && $echo_Main_Settings['google_images'] == 'on')
    {
        $rand_arr[] = 'google';
    }
    $rez = false;
    while(($rez === false || $rez === '') && count($rand_arr) > 0)
    {
        $rand = array_rand($rand_arr);
        if($rand_arr[$rand] == 'pixabay')
        {
            unset($rand_arr[$rand]);
            if(isset($echo_Main_Settings['img_ss']) && $echo_Main_Settings['img_ss'] == 'on')
            {
                $img_ss = '1';
            }
            else
            {
                $img_ss = '0';
            }
            if(isset($echo_Main_Settings['img_editor']) && $echo_Main_Settings['img_editor'] == 'on')
            {
                $img_editor = '1';
            }
            else
            {
                $img_editor = '0';
            }
            $rez = echo_get_pixabay_image($echo_Main_Settings['pixabay_api'], $query_words, $echo_Main_Settings['img_language'], $echo_Main_Settings['imgtype'], $echo_Main_Settings['scrapeimg_orientation'], $echo_Main_Settings['img_order'], $echo_Main_Settings['img_cat'], $echo_Main_Settings['img_mwidth'], $echo_Main_Settings['img_width'], $img_ss, $img_editor, $original_url, $res_cnt);
            if($rez !== false && $rez !== '')
            {
                $img_attr = str_replace('%%image_source_name%%', 'Pixabay', $img_attr);
                $img_attr = str_replace('%%image_source_url%%', $original_url, $img_attr);
                $img_attr = str_replace('%%image_source_website%%', 'https://pixabay.com/', $img_attr);
            }
        }
        elseif($rand_arr[$rand] == 'morguefile')
        {
            unset($rand_arr[$rand]);
            $rez = echo_get_morguefile_image($echo_Main_Settings['morguefile_api'], $echo_Main_Settings['morguefile_secret'], $query_words, $original_url);
            if($rez !== false && $rez !== '')
            {
                $img_attr = str_replace('%%image_source_name%%', 'MorgueFile', $img_attr);
                $img_attr = str_replace('%%image_source_url%%', 'https://morguefile.com/', $img_attr);
                $img_attr = str_replace('%%image_source_website%%', 'https://morguefile.com/', $img_attr);
            }
        }
        elseif($rand_arr[$rand] == 'flickr')
        {
            unset($rand_arr[$rand]);
            $rez = echo_get_flickr_image($echo_Main_Settings, $query_words, $original_url, $res_cnt);
            if($rez !== false && $rez !== '')
            {
                $img_attr = str_replace('%%image_source_name%%', 'Flickr', $img_attr);
                $img_attr = str_replace('%%image_source_url%%', $original_url, $img_attr);
                $img_attr = str_replace('%%image_source_website%%', 'https://www.flickr.com/', $img_attr);
            }
        }
        elseif($rand_arr[$rand] == 'pexels')
        {
            unset($rand_arr[$rand]);
            $rez = echo_get_pexels_image($echo_Main_Settings, $query_words, $original_url, $res_cnt);
            if($rez !== false && $rez !== '')
            {
                $img_attr = str_replace('%%image_source_name%%', 'Pexels', $img_attr);
                $img_attr = str_replace('%%image_source_url%%', $original_url, $img_attr);
                $img_attr = str_replace('%%image_source_website%%', 'https://www.pexels.com/', $img_attr);
            }
        }
        elseif($rand_arr[$rand] == 'pixabayscrape')
        {
            unset($rand_arr[$rand]);
            $rez = echo_scrape_pixabay_image($echo_Main_Settings, $query_words, $original_url);
            if($rez !== false && $rez !== '')
            {
                $img_attr = str_replace('%%image_source_name%%', 'Pixabay', $img_attr);
                $img_attr = str_replace('%%image_source_url%%', $original_url, $img_attr);
                $img_attr = str_replace('%%image_source_website%%', 'https://pixabay.com/', $img_attr);
            }
        }
        elseif($rand_arr[$rand] == 'unsplash')
        {
            unset($rand_arr[$rand]);
            $rez = echo_scrape_unsplash_image($query_words, $original_url);
            if($rez !== false && $rez !== '')
            {
                $img_attr = str_replace('%%image_source_name%%', 'Unsplash', $img_attr);
                $img_attr = str_replace('%%image_source_url%%', $original_url, $img_attr);
                $img_attr = str_replace('%%image_source_website%%', 'https://unsplash.com/', $img_attr);
            }
        }
        elseif($rand_arr[$rand] == 'google')
        {
            unset($rand_arr[$rand]);
            $original_url = 'https://google.com/';
            $rez = echo_get_random_image_google($query_words);
            if($rez !== false && $rez !== '')
            {
                $img_attr = str_replace('%%image_source_name%%', 'Google Images', $img_attr);
                $img_attr = str_replace('%%image_source_url%%', $original_url, $img_attr);
                $img_attr = str_replace('%%image_source_website%%', 'https://google.com/', $img_attr);
            }
        }
        else
        {
            echo_log_to_file('Unrecognized free file source: ' . $rand_arr[$rand]);
            unset($rand_arr[$rand]);
        }
    }
    $img_attr = str_replace('%%image_source_name%%', '', $img_attr);
    $img_attr = str_replace('%%image_source_url%%', '', $img_attr);
    $img_attr = str_replace('%%image_source_website%%', '', $img_attr);
    return $rez;
}   
function echo_file_get_contents_advanced($url, $headers = '', $referrer = 'self', $user_agent = false)
{
    $content = false;
    if (parse_url($url, PHP_URL_SCHEME) != '' && function_exists('curl_init')) 
    {
        $echo_Main_Settings = get_option('echo_Main_Settings', false);
        $max_redirects = 10;
        $ch = curl_init();
        if($ch !== false)
        {
            curl_setopt($ch, CURLOPT_URL, $url);
            if (strtolower($referrer) == 'self') {
                curl_setopt($ch, CURLOPT_REFERER, $url);
            } elseif (strlen($referrer)) {
                curl_setopt($ch, CURLOPT_REFERER, $referrer);
            }
            if ($user_agent) {
                curl_setopt($ch, CURLOPT_USERAGENT, $user_agent);
            } 
            curl_setopt($ch, CURLOPT_ENCODING, 'gzip,deflate');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            $headers = trim($headers);
            if (strlen($headers)) {
                $headers_array = explode(PHP_EOL, $headers);
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers_array);
            }
            if (isset($echo_Main_Settings['proxy_url']) && $echo_Main_Settings['proxy_url'] != '' && $echo_Main_Settings['proxy_url'] != 'disable' && $echo_Main_Settings['proxy_url'] != 'disabled') {
                $prx = explode(',', $echo_Main_Settings['proxy_url']);
                $randomness = array_rand($prx);
                curl_setopt( $ch, CURLOPT_PROXY, trim($prx[$randomness]));
                if (isset($echo_Main_Settings['proxy_auth']) && $echo_Main_Settings['proxy_auth'] != '') 
                {
                    $prx_auth = explode(',', $echo_Main_Settings['proxy_auth']);
                    if(isset($prx_auth[$randomness]) && trim($prx_auth[$randomness]) != '')
                    {
                        curl_setopt( $ch, CURLOPT_PROXYUSERPWD, trim($prx_auth[$randomness]));
                    }
                }
            }
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
            if (ini_get('open_basedir') == '') 
            {
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                curl_setopt($ch, CURLOPT_MAXREDIRS, $max_redirects);
            } 
            else 
            {
                $base_url = $url;
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
                $rch = curl_copy_handle($ch);
                curl_setopt($rch, CURLOPT_HEADER, true);
                curl_setopt($rch, CURLOPT_NOBODY, true);
                curl_setopt($rch, CURLOPT_FORBID_REUSE, false);
                curl_setopt($rch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($rch, CURLOPT_SSL_VERIFYPEER, false);
                do 
                {
                    curl_setopt($rch, CURLOPT_URL, $url);
                    curl_setopt($rch, CURLOPT_REFERER, $url);
                    $header = curl_exec($rch);
                    if (curl_errno($rch)) {
                        $code = 0;
                    } else {
                        $code = curl_getinfo($rch, CURLINFO_HTTP_CODE);
                        if ($code == 301 || $code == 302) {
                            preg_match('/Location:(.*?)\n/', $header, $matches);
                            $url = trim(array_pop($matches));
                            if (strlen($url) && substr($url, 0, 1) == '/') {
                                $url = $base_url . $url;
                            }
                        } else {
                            $code = 0;
                        }
                    }
                } 
                while ($code && --$max_redirects);
                curl_close($rch);
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_REFERER, $url);
            }
            curl_setopt($ch, CURLOPT_HEADER, false);
            $content = curl_exec($ch);
            $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            if ($code != 200) {
                $content = false;
            }
            curl_close($ch);
        }
    }
    if (!isset($content) || $content === false) {
        error_reporting(0);
        stream_context_set_default(array('ssl' => array('verify_peer' => false, 'verify_peer_name' => false,), 'http' => array('method' => 'HEAD', 'timeout' => 10, 'user_agent' => $user_agent)));
        $content = file_get_contents($url);
        error_reporting(E_ALL);
    }
    return $content;
}
function echo_get_random_image_google($keyword, $min_width = 0, $min_height = 0)
{
    $gimageurl = 'https://www.google.com/search?q=' . urlencode($keyword . ' -site:depositphotos.com -site:123rf.com') . '&tbm=isch&tbs=il:cl&sa=X';
    $res = echo_file_get_contents_advanced($gimageurl, '', 'self', 'Mozilla/5.0 (Windows NT 10.0;WOW64;rv:97.0) Gecko/20100101 Firefox/97.0/3871tuT2p1u-81');
    preg_match_all('/\["([\w%-\.\/:\?&=]+\.jpg|\.jpeg|\.gif|\.png|\.bmp|\.wbmp|\.webm|\.xbm)",\d+,\d+\]/i', $res, $matches);
    $items = $matches[0];
    if (count($items)) {
        shuffle($items);
        foreach ($items as $item) {
            preg_match('#\["(.*?)",(.*?),(.*?)\]#', $item, $matches);
            if (count($matches) == 4 && ($min_width > 0 || $min_width <= $matches[3]) && ($min_height > 0 || $min_height <= $matches[2])) {
                return $matches[1];
            }
        }
    }
    return '';
}
function echo_get_pixabay_image($app_id, $query, $lang, $image_type, $orientation, $order, $image_category, $max_width, $min_width, $safe_search, $editors_choice, &$original_url, $get_max = 3)
{
    $original_url = 'https://pixabay.com';
    $featured_image = '';
    $feed_uri = 'https://pixabay.com/api/?key=' . $app_id;
    if($query != '')
    {
        $feed_uri .= '&q=' . urlencode($query);
    }
    $feed_uri .= '&per_page=' . $get_max;
    if($lang != '' && $lang != 'any')
    {
        $feed_uri .= '&lang=' . $lang;
    }
    if($image_type != '')
    {
        $feed_uri .= '&image_type=' . $image_type;
    }
    if($orientation != '')
    {
        $feed_uri .= '&orientation=' . $orientation;
    }
    if($order != '')
    {
        $feed_uri .= '&order=' . $order;
    }
    if($image_category != '')
    {
        $feed_uri .= '&category=' . $image_category;
    }
    if($max_width != '')
    {
        $feed_uri .= '&max_width=' . $max_width;
    }
    if($min_width != '')
    {
        $feed_uri .= '&min_width=' . $min_width;
    }
    if($safe_search == '1')
    {
        $feed_uri .= '&safesearch=true';
    }
    if($editors_choice == '1')
    {
        $feed_uri .= '&editors_choice=true';
    }
    $exec = echo_get_web_page($feed_uri);
    if ($exec !== FALSE) 
    {
        if (stristr($exec, '"hits"') !== FALSE) 
        {
            $exec = preg_replace('#^[a-zA-Z0-9]*#', '', $exec);
            $exec = trim($exec, '()');
            $json  = json_decode($exec);
            $items = $json->hits;
            if (count($items) != 0) 
            {
                shuffle($items);
                foreach($items as $item)
                {
                    $featured_image = $item->webformatURL;
                    $original_url = $item->pageURL;
                    break;
                }
            }
        }
        else
        {
            echo_log_to_file('Unknow response from api: ' . $feed_uri . ' - resp: ' . $exec);
            return false;
        }
    }
    else
    {
        echo_log_to_file('Error while getting api url: ' . $feed_uri);
        return false;
    }
    return $featured_image;
}
function echo_get_redirect_url($url){
    preg_match_all('#https?:\/\/www\.google\.com\/url\?(?:.*?)url=([^&]*?)&(?:.*?)#', $url, $xmatches);
    if(isset($xmatches[1][0]))
    {
        return $xmatches[1][0];
    }
    $url_parts = parse_url($url);
    if (!$url_parts) return false;
    if (!isset($url_parts['host'])) return false;
    if (!isset($url_parts['path'])) $url_parts['path'] = '/';

    $sock = fsockopen($url_parts['host'], (isset($url_parts['port']) ? (int)$url_parts['port'] : 80), $errno, $errstr, 30);
    if (!$sock) return false;

    $request = "HEAD " . $url_parts['path'] . (isset($url_parts['query']) ? '?'.$url_parts['query'] : '') . " HTTP/1.1".PHP_EOL; 
    $request .= 'Host: ' . $url_parts['host'] . PHP_EOL; 
    $request .= "Connection: Close".PHP_EOL.PHP_EOL; 
    fwrite($sock, $request);
    $response = '';
    while(!feof($sock)) $response .= fread($sock, 8192);
    fclose($sock);

    if (preg_match('/^Location: (.+?)$/m', $response, $matches)){
        if ( substr($matches[1], 0, 1) == "/" )
            return $url_parts['scheme'] . "://" . $url_parts['host'] . trim($matches[1]);
        else
            return trim($matches[1]);

    } else {
        return false;
    }
}

function echo_get_all_redirects($url){
    $redirects = array();
    while ($newurl = echo_get_redirect_url($url)){
        if (in_array($newurl, $redirects)){
            break;
        }
        $redirects[] = $newurl;
        $url = $newurl;
    }
    return $redirects;
}

function echo_seo_plugins_active()
{
    if (!function_exists('is_plugin_active')) {
        include_once(ABSPATH . 'wp-admin/includes/plugin.php');
    }
    $seo_plugin_activated = false;
    if(is_plugin_active('wordpress-seo/wp-seo.php'))
    {
        $seo_plugin_activated = '_yoast_wpseo_metadesc';
    }
    elseif(is_plugin_active('all-in-one-seo-pack/all_in_one_seo_pack.php')){
        $seo_plugin_activated = '_aioseo_description';
    }
    elseif(is_plugin_active('seo-by-rank-math/rank-math.php')){
        $seo_plugin_activated = 'rank_math_description';
    }
    return $seo_plugin_activated;
}
function echo_save_seo_description($post_id, $description)
{
    global $wpdb;
    if(empty($description))
    {
        return;
    }
    $seo_plugin_activated = echo_seo_plugins_active();
    if($seo_plugin_activated == '_yoast_wpseo_metadesc'){
        update_post_meta($post_id, $seo_plugin_activated, $description);
    }
    if($seo_plugin_activated == '_aioseo_description'){
        update_post_meta($post_id, $seo_plugin_activated, $description);
        $check = $wpdb->get_row($wpdb->prepare("SELECT * FROM ".$wpdb->prefix."aioseo_posts WHERE post_id=%d",$post_id));
        if($check)
        {
            $wpdb->update($wpdb->prefix.'aioseo_posts',array(
                'description' => $description
            ), array(
                'post_id' => $post_id
            ));
        }
        else{
            $wpdb->insert($wpdb->prefix.'aioseo_posts',array(
                'post_id' => $post_id,
                'description' => $description,
                'created' => date('Y-m-d H:i:s'),
                'updated' => date('Y-m-d H:i:s')
            ));
        }
    }
    if($seo_plugin_activated == 'rank_math_description'){
        update_post_meta($post_id, $seo_plugin_activated, $description);
    }
    if($seo_plugin_activated == false)
    {
        $seo_plugin_activated = 'aiomatic_html_meta';
        update_post_meta($post_id, $seo_plugin_activated, $description);
    }
}
function echo_get_final_url($url){
    if (strpos($url, 'localhost') !== false)
    {
        return $url;
    }
    $redirects = echo_get_all_redirects($url);
    if (count($redirects)>0){
        return array_pop($redirects);
    } else {
        return $url;
    }
}
function echo_scrape_unsplash_image($query, &$original_url)
{
    $echo_Main_Settings = get_option('echo_Main_Settings', false);
    if(!isset($echo_Main_Settings['unsplash_key']) || trim($echo_Main_Settings['unsplash_key']) == '')
    {
        echo_log_to_file('You need to enter an Unsplash API key for this to work');
        return false;
    }
    if($query == '')
    {
        echo_log_to_file('Empty queries are not allowed for Unsplash.');
        return false;
    }
    $original_url = 'https://unsplash.com/';
    $page = 1;
    $perPage = 30;
    $orderBy = 'relevant';
    $collections = '';
    $contentFilter = 'low';
    $color = '';
    $orientation = '';
    $feed_uri = "https://api.unsplash.com/search/photos";
    $params = [
        'query' => $query,
        'page' => $page,
        'per_page' => $perPage,
        'order_by' => $orderBy,
        'collections' => $collections,
        'content_filter' => $contentFilter,
        'client_id' => trim($echo_Main_Settings['unsplash_key'])
    ];
    if(!empty($orientation))
    {
        $params['orientation'] = $orientation;
    }
    if(!empty($color))
    {
        $params['color'] = $color;
    }
    $featured_image = '';
    $queryUrl = $feed_uri . '?' . http_build_query($params);
    $ch               = curl_init();
    if ($ch === FALSE) {
        echo_log_to_file('Failed to init curl for Unsplash!');
        return false;
    }
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
    curl_setopt($ch, CURLOPT_HTTPGET, 1);
    $ztime = 300;
    curl_setopt($ch, CURLOPT_TIMEOUT, $ztime);
    curl_setopt($ch, CURLOPT_URL, $queryUrl);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $exec = curl_exec($ch);
    curl_close($ch);
    $items = json_decode ( $exec, true );
    if(!isset($items['results']))
    {
        echo_log_to_file('Failed to find photo node in Unsplash response URI: ' . $queryUrl);
        return false;
    }
    if(count($items['results']) == 0)
    {
        return $featured_image;
    }
    $x = 0;
    foreach($items['results'] as $photox)
    {
        if(isset($photox['urls']['raw']))
        {
            $full_result_list[] = $photox['urls']['raw'];
        }
    }
    shuffle($items['results']);
    while($featured_image == '' && isset($items['results'][$x]))
    {
        $item = $items['results'][$x];
        if(isset($item['urls']['raw']))
        {
            $featured_image = $item['urls']['raw'];
        }
        if($featured_image != '' && isset($item['links']['html']))
        {
            $original_url = $item['links']['html'];
        }
        $x++;
    }
    return $featured_image;
}
function echo_scrape_pixabay_image($echo_Main_Settings, $query, &$original_url)
{
    $original_url = 'https://pixabay.com';
    $featured_image = '';
    $feed_uri = 'https://pixabay.com/en/photos/';
    if($query != '')
    {
        $feed_uri .= '?q=' . urlencode($query);
    }

    if($echo_Main_Settings['scrapeimgtype'] != 'all')
    {
        $feed_uri .= '&image_type=' . $echo_Main_Settings['scrapeimgtype'];
    }
    if($echo_Main_Settings['scrapeimg_orientation'] != '')
    {
        $feed_uri .= '&orientation=' . $echo_Main_Settings['scrapeimg_orientation'];
    }
    if($echo_Main_Settings['scrapeimg_order'] != '' && $echo_Main_Settings['scrapeimg_order'] != 'any')
    {
        $feed_uri .= '&order=' . $echo_Main_Settings['scrapeimg_order'];
    }
    if($echo_Main_Settings['scrapeimg_cat'] != '')
    {
        $feed_uri .= '&category=' . $echo_Main_Settings['scrapeimg_cat'];
    }
    if($echo_Main_Settings['scrapeimg_height'] != '')
    {
        $feed_uri .= '&min_height=' . $echo_Main_Settings['scrapeimg_height'];
    }
    if($echo_Main_Settings['scrapeimg_width'] != '')
    {
        $feed_uri .= '&min_width=' . $echo_Main_Settings['scrapeimg_width'];
    }
    $exec = echo_get_web_page($feed_uri);
    if ($exec !== FALSE) 
    {
        preg_match_all('/<a href="([^"]+?)".+?(?:data-lazy|src)="([^"]+?\.jpg|png)"/i', $exec, $matches);
        if (!empty($matches[2])) {
            $p = array_combine($matches[1], $matches[2]);
            if(count($p) > 0)
            {
                shuffle($p);
                foreach ($p as $key => $val) {
                    $featured_image = $val;
                    if(!is_numeric($key))
                    {
                        if(substr($key, 0, 4) !== "http")
                        {
                            $key = 'https://pixabay.com' . $key;
                        }
                        $original_url = $key;
                    }
                    else
                    {
                        $original_url = 'https://pixabay.com';
                    }
                    break;
                }
            }
        }
    }
    else
    {
        echo_log_to_file('Error while getting api url: ' . $feed_uri);
        return false;
    }
    return $featured_image;
}

function echo_change_feed_cache_transient_lifetime( $time, $url = '' ) {
	$echo_Main_Settings = get_option('echo_Main_Settings', false);
    if (isset($echo_Main_Settings['echo_enabled']) && $echo_Main_Settings['echo_enabled'] == 'on') 
    {
        if (isset($echo_Main_Settings['refresh_url']) && $echo_Main_Settings['refresh_url'] != '')
        {
            return intval($echo_Main_Settings['refresh_url']);
        }
    }
	return $time;
}
add_filter( 'wp_feed_cache_transient_lifetime', 'echo_change_feed_cache_transient_lifetime', 2);
function echo_get_morguefile_image($app_id, $app_secret, $query, &$original_url)
{
    $featured_image = '';
    if(!class_exists('echo_morguefile'))
    {
        require_once (dirname(__FILE__) . "/res/morguefile/mf.api.class.php");
    }
    $query = explode(' ', $query);
    $query = $query[0];
    {
        $mf = new echo_morguefile($app_id, $app_secret);
        $rez = $mf->call('/images/search/sort/page/' . $query);
        if ($rez !== FALSE) 
        {
            $chosen_one = $rez->doc[array_rand($rez->doc)];
            if (isset($chosen_one->file_path_large)) 
            {
                return $chosen_one->file_path_large;
            }
            else
            {
                return false;
            }
        }
        else
        {
            echo_log_to_file('Error while getting api response from morguefile.');
            return false;
        }
    }
    return $featured_image;
}
function echo_get_flickr_image($echo_Main_Settings, $query, &$original_url, $max)
{
    $original_url = 'https://www.flickr.com';
    $featured_image = '';
    $feed_uri = 'https://api.flickr.com/services/rest/?method=flickr.photos.search&api_key=' . $echo_Main_Settings['flickr_api'] . '&media=photos&per_page=' . esc_html($max) . '&format=php_serial&text=' . urlencode($query);
    if(isset($echo_Main_Settings['flickr_license']) && $echo_Main_Settings['flickr_license'] != '-1')
    {
        $feed_uri .= '&license=' . $echo_Main_Settings['flickr_license'];
    }
    if(isset($echo_Main_Settings['flickr_order']) && $echo_Main_Settings['flickr_order'] != '')
    {
        $feed_uri .= '&sort=' . $echo_Main_Settings['flickr_order'];
    }
    $feed_uri .= '&extras=description,license,date_upload,date_taken,owner_name,icon_server,original_format,last_update,geo,tags,machine_tags,o_dims,views,media,path_alias,url_sq,url_t,url_s,url_q,url_m,url_n,url_z,url_c,url_l,url_o';
     
    {
        $ch               = curl_init();
        if ($ch === FALSE) {
            echo_log_to_file('Failed to init curl for flickr!');
            return false;
        }
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Referer: https://www.flickr.com/'));
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ch, CURLOPT_HTTPGET, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_URL, $feed_uri);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $exec = curl_exec($ch);
        curl_close($ch);
        if (stristr($exec, 'photos') === FALSE) {
            echo_log_to_file('Unrecognized Flickr API response: ' . $exec . ' URI: ' . $feed_uri);
            return false;
        }
        $items = unserialize ( $exec );
        if(!isset($items['photos']['photo']))
        {
            echo_log_to_file('Failed to find photo node in response: ' . $exec . ' URI: ' . $feed_uri);
            return false;
        }
        if(count($items['photos']['photo']) == 0)
        {
            return $featured_image;
        }
        $x = 0;
        shuffle($items['photos']['photo']);
        while($featured_image == '' && isset($items['photos']['photo'][$x]))
        {
            $item = $items['photos']['photo'][$x];
            if(isset($item['url_o']))
            {
                $featured_image = $item['url_o'];
            }
            elseif(isset($item['url_l']))
            {
                $featured_image = $item['url_l'];
            }
            elseif(isset($item['url_c']))
            {
                $featured_image = $item['url_c'];
            }
            elseif(isset($item['url_z']))
            {
                $featured_image = $item['url_z'];
            }
            elseif(isset($item['url_n']))
            {
                $featured_image = $item['url_n'];
            }
            elseif(isset($item['url_m']))
            {
                $featured_image = $item['url_m'];
            }
            elseif(isset($item['url_q']))
            {
                $featured_image = $item['url_q'];
            }
            elseif(isset($item['url_s']))
            {
                $featured_image = $item['url_s'];
            }
            elseif(isset($item['url_t']))
            {
                $featured_image = $item['url_t'];
            }
            elseif(isset($item['url_sq']))
            {
                $featured_image = $item['url_sq'];
            }
            if($featured_image != '')
            {
                $original_url = 'https://www.flickr.com/photos/' . $item['owner'] . '/' . $item['id'];
            }
            $x++;
        }
    }
    return $featured_image;
}
function echo_encodeURI($url) {
    if($url == null)
    {
        return '';
    }
    $unescaped = array(
        '%2D'=>'-','%5F'=>'_','%2E'=>'.','%21'=>'!', '%7E'=>'~',
        '%2A'=>'*', '%27'=>"'", '%28'=>'(', '%29'=>')', '%5B'=>'[', '%5D'=>']'
    );
    $reserved = array(
        '%3B'=>';','%2C'=>',','%2F'=>'/','%3F'=>'?','%3A'=>':',
        '%40'=>'@','%26'=>'&','%3D'=>'=','%2B'=>'+','%24'=>'$','%25'=>'%'
    );
    $score = array(
        '%23'=>'#'
    );
    return strtr(rawurlencode($url), array_merge($reserved, $unescaped, $score));

}
function echo_get_pexels_image($echo_Main_Settings, $query, &$original_url, $max)
{
    $original_url = 'https://pexels.com';
    $featured_image = '';
    $feed_uri = 'https://api.pexels.com/v1/search?query=' . urlencode($query) . '&per_page=' . $max;
     
    {
        $ch               = curl_init();
        if ($ch === FALSE) {
            echo_log_to_file('Failed to init curl for flickr!');
            return false;
        }
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: ' . $echo_Main_Settings['pexels_api']));
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ch, CURLOPT_HTTPGET, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_URL, $feed_uri);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $exec = curl_exec($ch);
        curl_close($ch);
        if (stristr($exec, 'photos') === FALSE) {
            echo_log_to_file('Unrecognized Pexels API response: ' . $exec . ' URI: ' . $feed_uri);
            return false;
        }
        $items = json_decode ( $exec, true );
        if(!isset($items['photos']))
        {
            echo_log_to_file('Failed to find photo node in Pexels response: ' . $exec . ' URI: ' . $feed_uri);
            return false;
        }
        if(count($items['photos']) == 0)
        {
            return $featured_image;
        }
        $x = 0;
        shuffle($items['photos']);
        while($featured_image == '' && isset($items['photos'][$x]))
        {
            $item = $items['photos'][$x];
            if(isset($item['src']['large']))
            {
                $featured_image = $item['src']['large'];
            }
            elseif(isset($item['src']['medium']))
            {
                $featured_image = $item['src']['medium'];
            }
            elseif(isset($item['src']['small']))
            {
                $featured_image = $item['src']['small'];
            }
            elseif(isset($item['src']['portrait']))
            {
                $featured_image = $item['src']['portrait'];
            }
            elseif(isset($item['src']['landscape']))
            {
                $featured_image = $item['src']['landscape'];
            }
            elseif(isset($item['src']['original']))
            {
                $featured_image = $item['src']['original'];
            }
            elseif(isset($item['src']['tiny']))
            {
                $featured_image = $item['src']['tiny'];
            }
            if($featured_image != '')
            {
                $original_url = $item['url'];
            }
            $x++;
        }
    }
    return $featured_image;
}

function echo_spin_text($title, $content, $alt = false)
{
    $echo_Main_Settings = get_option('echo_Main_Settings', false);
    $titleSeparator = '[19459000]';
    $text           = $title . ' ' . $titleSeparator . ' ' . $content;
    $text           = html_entity_decode($text);
    preg_match_all("/<[^<>]+>/is", $text, $matches, PREG_PATTERN_ORDER);
    $htmlfounds         = array_filter(array_unique($matches[0]));
    $htmlfounds[]       = '&quot;';
    $imgFoundsSeparated = array();
    foreach ($htmlfounds as $key => $currentFound) {
        if (stristr($currentFound, '<img') && stristr($currentFound, 'alt')) {
            $altSeparator   = '';
            $colonSeparator = '';
            if (stristr($currentFound, 'alt="')) {
                $altSeparator   = 'alt="';
                $colonSeparator = '"';
            } elseif (stristr($currentFound, 'alt = "')) {
                $altSeparator   = 'alt = "';
                $colonSeparator = '"';
            } elseif (stristr($currentFound, 'alt ="')) {
                $altSeparator   = 'alt ="';
                $colonSeparator = '"';
            } elseif (stristr($currentFound, 'alt= "')) {
                $altSeparator   = 'alt= "';
                $colonSeparator = '"';
            } elseif (stristr($currentFound, 'alt=\'')) {
                $altSeparator   = 'alt=\'';
                $colonSeparator = '\'';
            } elseif (stristr($currentFound, 'alt = \'')) {
                $altSeparator   = 'alt = \'';
                $colonSeparator = '\'';
            } elseif (stristr($currentFound, 'alt= \'')) {
                $altSeparator   = 'alt= \'';
                $colonSeparator = '\'';
            } elseif (stristr($currentFound, 'alt =\'')) {
                $altSeparator   = 'alt =\'';
                $colonSeparator = '\'';
            }
            if (trim($altSeparator) != '') {
                $currentFoundParts = explode($altSeparator, $currentFound);
                $preAlt            = $currentFoundParts[1];
                $preAltParts       = explode($colonSeparator, $preAlt);
                $altText           = $preAltParts[0];
                if (trim($altText) != '') {
                    unset($preAltParts[0]);
                    $imgFoundsSeparated[] = $currentFoundParts[0] . $altSeparator;
                    $imgFoundsSeparated[] = $colonSeparator . implode('', $preAltParts);
                    $htmlfounds[$key]     = '';
                }
            }
        }
    }
    if (count($imgFoundsSeparated) != 0) {
        $htmlfounds = array_merge($htmlfounds, $imgFoundsSeparated);
    }
    preg_match_all("/<\!--.*?-->/is", $text, $matches2, PREG_PATTERN_ORDER);
    $newhtmlfounds = $matches2[0];
    preg_match_all("/\[.*?\]/is", $text, $matches3, PREG_PATTERN_ORDER);
    $shortcodesfounds = $matches3[0];
    $htmlfounds       = array_merge($htmlfounds, $newhtmlfounds, $shortcodesfounds);
    $in               = 0;
    $cleanHtmlFounds  = array();
    foreach ($htmlfounds as $htmlfound) {
        if ($htmlfound == '[19459000]') {
        } elseif (trim($htmlfound) == '') {
        } else {
            $cleanHtmlFounds[] = $htmlfound;
        }
    }
    $htmlfounds = $cleanHtmlFounds;
    $start      = 19459001;
    foreach ($htmlfounds as $htmlfound) {
        $text = str_replace($htmlfound, '[' . $start . ']', $text);
        $start++;
    }
    try {
        require_once(dirname(__FILE__) . "/res/echo-text-spinner.php");
        $phpTextSpinner = new PhpTextSpinner();
        if ($alt === FALSE) {
            $spinContent = $phpTextSpinner->spinContent($text);
        } else {
            $spinContent = $phpTextSpinner->spinContentAlt($text);
        }
        $translated = $phpTextSpinner->runTextSpinner($spinContent);
    }
    catch (Exception $e) {
        if (isset($echo_Main_Settings['enable_detailed_logging'])) {
            echo_log_to_file('Exception thrown in spinText ' . $e->getMessage());
        }
        return false;
    }
    preg_match_all('{\[.*?\]}', $translated, $brackets);
    $brackets = $brackets[0];
    $brackets = array_unique($brackets);
    foreach ($brackets as $bracket) {
        if (stristr($bracket, '19')) {
            $corrrect_bracket = str_replace(' ', '', $bracket);
            $corrrect_bracket = str_replace('.', '', $corrrect_bracket);
            $corrrect_bracket = str_replace(',', '', $corrrect_bracket);
            $translated       = str_replace($bracket, $corrrect_bracket, $translated);
        }
    }
    if (stristr($translated, $titleSeparator)) {
        $start = 19459001;
        foreach ($htmlfounds as $htmlfound) {
            $translated = str_replace('[' . $start . ']', $htmlfound, $translated);
            $start++;
        }
        $contents = explode($titleSeparator, $translated);
        $title    = $contents[0];
        $content  = $contents[1];
    } else {
        if (isset($echo_Main_Settings['enable_detailed_logging'])) {
            echo_log_to_file('Failed to parse spinned content, separator not found');
        }
        return false;
    }
    return array(
        $title,
        $content
    );
}

class Echo_Spintax
{
    public function process($text)
    {
        $x = stripslashes(preg_replace_callback(
            '/\{(((?>[^\{\}]+)|(?R))*)\}/x',
            array($this, 'replace'),
            preg_quote($text)
        ));
        return $x;
    }
    public function replace($text)
    {
        $text = $this->process($text[1]);
        $parts = explode('|', $text);
        return $parts[array_rand($parts)];
    }
}
function echo_best_spin_text($title, $content, $use_proxy)
{
    $echo_Main_Settings = get_option('echo_Main_Settings', false);
    if (!isset($echo_Main_Settings['best_user']) || $echo_Main_Settings['best_user'] == '' || !isset($echo_Main_Settings['best_password']) || $echo_Main_Settings['best_password'] == '') {
        echo_log_to_file('Please insert a valid "The Best Spinner" user name and password.');
        return FALSE;
    }
    $titleSeparator   = '[19459000]';
    $newhtml             = $title . ' ' . $titleSeparator . ' ' . $content;
    $url              = 'http://thebestspinner.com/api.php';
    $data             = array();
    $data['action']   = 'authenticate';
    $data['format']   = 'php';
    $data['username'] = $echo_Main_Settings['best_user'];
    $data['password'] = $echo_Main_Settings['best_password'];
    $ch               = curl_init();
    if ($ch === FALSE) {
        if (isset($echo_Main_Settings['enable_detailed_logging'])) {
            echo_log_to_file('"The Best Spinner" failed to init curl.');
        }
        return FALSE;
    }
    if ($use_proxy && isset($echo_Main_Settings['proxy_url']) && $echo_Main_Settings['proxy_url'] != '' && $echo_Main_Settings['proxy_url'] != 'disable' && $echo_Main_Settings['proxy_url'] != 'disabled') {
        $prx = explode(',', $echo_Main_Settings['proxy_url']);
        $randomness = array_rand($prx);
        curl_setopt( $ch, CURLOPT_PROXY, trim($prx[$randomness]));
        if (isset($echo_Main_Settings['proxy_auth']) && $echo_Main_Settings['proxy_auth'] != '') 
        {
            $prx_auth = explode(',', $echo_Main_Settings['proxy_auth']);
            if(isset($prx_auth[$randomness]) && trim($prx_auth[$randomness]) != '')
            {
                curl_setopt( $ch, CURLOPT_PROXYUSERPWD, trim($prx_auth[$randomness]) );
            }
        }
    }
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
    curl_setopt($ch, CURLOPT_TIMEOUT, 60);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    $fdata = "";
    foreach ($data as $key => $val) {
        $fdata .= "$key=" . urlencode($val) . "&";
    }
    curl_setopt($ch, CURLOPT_POSTFIELDS, $fdata);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_REFERER, $url);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    $html = echo_curl_exec_utf8($ch);
    curl_close($ch);
    if ($html === FALSE) {
        if (isset($echo_Main_Settings['enable_detailed_logging'])) {
            echo_log_to_file('"The Best Spinner" failed to exec curl.');
        }
        return FALSE;
    }
    $output = unserialize($html);
    if ($output['success'] == 'true') {
        $session                = $output['session'];
        $data                   = array();
        $data['session']        = $session;
        $data['format']         = 'php';
        if (isset($echo_Main_Settings['protected_terms']) && $echo_Main_Settings['protected_terms'] != '') 
        {
            $protected_terms = $echo_Main_Settings['protected_terms'];
        }
        else
        {
            $protected_terms = '';
        }
        $data['protectedterms'] = $protected_terms;
        $data['action']         = 'replaceEveryonesFavorites';
        $data['maxsyns']        = '100';
        $data['quality']        = '1';
        $ch = curl_init();
        if ($ch === FALSE) {
            if (isset($echo_Main_Settings['enable_detailed_logging'])) {
                echo_log_to_file('Failed to init curl');
            }
            return FALSE;
        }
        $newhtml = html_entity_decode($newhtml);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_REFERER, $url);
        $spinned = '';
        if(str_word_count($newhtml) > 4000)
        {
            while($newhtml != '')
            {
                $first30k = substr($newhtml, 0, 30000);
                $first30k = rtrim($first30k, '(*');
                $first30k = ltrim($first30k, ')*');
                $newhtml = substr($newhtml, 30000);
                $data['text']           = $first30k;
                $fdata = "";
                foreach ($data as $key => $val) {
                    $fdata .= "$key=" . urlencode($val) . "&";
                }
                curl_setopt($ch, CURLOPT_POSTFIELDS, $fdata);
                $output = curl_exec($ch);
                if ($output === FALSE) {
                    if (isset($echo_Main_Settings['enable_detailed_logging'])) {
                        echo_log_to_file('"The Best Spinner" failed to exec curl after auth.');
                    }
                    return FALSE;
                }
                $output = unserialize($output);
                if ($output['success'] == 'true') {
                    $spinned .= ' ' . $output['output'];
                } else {
                    if (isset($echo_Main_Settings['enable_detailed_logging'])) {
                        echo_log_to_file('"The Best Spinner" failed to spin article.');
                    }
                    return FALSE;
                }
            }
        }
        else
        {
            $data['text'] = $newhtml;
            $fdata = "";
            foreach ($data as $key => $val) {
                $fdata .= "$key=" . urlencode($val) . "&";
            }
            curl_setopt($ch, CURLOPT_POSTFIELDS, $fdata);
            $output = curl_exec($ch);
            if ($output === FALSE) {
                if (isset($echo_Main_Settings['enable_detailed_logging'])) {
                    echo_log_to_file('"The Best Spinner" failed to exec curl after auth.');
                }
                return FALSE;
            }
            $output = unserialize($output);
            if ($output['success'] == 'true') {
                $spinned = $output['output'];
            } else {
                if (isset($echo_Main_Settings['enable_detailed_logging'])) {
                    echo_log_to_file('"The Best Spinner" failed to spin article: ' . print_r($output, true));
                }
                return FALSE;
            }
        }
        curl_close($ch);
        $result = explode($titleSeparator, $spinned);
        if (count($result) < 2) {
            if (isset($echo_Main_Settings['enable_detailed_logging'])) {
                echo_log_to_file('"The Best Spinner" failed to spin article - titleseparator not found.' . print_r($output, true));
            }
            return FALSE;
        }
        $spintax = new Echo_Spintax();
        $result[0] = $spintax->process($result[0]);
        $result[1] = $spintax->process($result[1]);
        return $result;

    } else {
        if (isset($echo_Main_Settings['enable_detailed_logging'])) {
            echo_log_to_file('"The Best Spinner" authentification failed. ' . print_r($output, true));
        }
        return FALSE;
    }
}

function echo_wordai_spin_text($title, $content, $use_proxy)
{
    $echo_Main_Settings = get_option('echo_Main_Settings', false);
    if (!isset($echo_Main_Settings['best_user']) || $echo_Main_Settings['best_user'] == '' || !isset($echo_Main_Settings['best_password']) || $echo_Main_Settings['best_password'] == '') {
        echo_log_to_file('Please insert a valid "Wordai" user name and password.');
        return FALSE;
    }
    if (isset($echo_Main_Settings['wordai_uniqueness']) && $echo_Main_Settings['wordai_uniqueness'] != '') 
    {
        $wordai_uniqueness = trim($echo_Main_Settings['wordai_uniqueness']);
    }
    else
    {
        $wordai_uniqueness = '2';
    }
    if($wordai_uniqueness != '1' && $wordai_uniqueness != '2' && $wordai_uniqueness != '3')
    {
        $wordai_uniqueness = '2';
    }
    $titleSeparator   = '[19459000]';
    $quality = 'Readable';
    $html             = $title . ' ' . $titleSeparator . ' ' . $content;
    $email = $echo_Main_Settings['best_user'];
    $pass = $echo_Main_Settings['best_password'];
    $html = urlencode($html);
    $ch = curl_init('https://wai.wordai.com/api/rewrite');
    if($ch === false)
    {
        echo_log_to_file('Failed to init curl in wordai spinning.');
        return FALSE;
    }
    curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt ($ch, CURLOPT_POST, 1);
    curl_setopt ($ch, CURLOPT_POSTFIELDS, "input=" . $html . "&uniqueness=" . $wordai_uniqueness . "&rewrite_num=1&return_rewrites=true&email=" . $email . "&key=" . $pass);
	curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
    curl_setopt($ch, CURLOPT_TIMEOUT, 120);
    $result = curl_exec($ch);
    if ($result === FALSE) {
        echo_log_to_file('"Wordai" failed to exec curl after auth: ' . curl_error($ch));
        curl_close ($ch);
        return FALSE;
    }
    curl_close ($ch);
    $result = json_decode($result);
    if(!isset($result->rewrites))
    {
        echo_log_to_file('"Wordai" unrecognized response: ' . print_r($result, true));
        return FALSE;
    }
    $result = explode($titleSeparator, $result->rewrites[0]);
    if (count($result) < 2) {
        $result[1] = $result[0];
        $result[0] = $title;
    }
    return $result;
}
function echo_replaceExcludes($text, &$htmlfounds, &$pre_tags_matches, &$pre_tags_matches_s, &$conseqMatchs, $spin_type)
{
    if($spin_type == 'tldr')
    {
        return $text;
    }
    preg_match_all ( '{<script.*?script>}s', $text, $script_matchs );
    $script_matchs = $script_matchs [0];
    preg_match_all ( '{<pre.*?/pre>}s', $text, $pre_matchs );
    $pre_matchs = $pre_matchs [0];
    preg_match_all ( '{<code.*?/code>}s', $text, $code_matchs );
    $code_matchs = $code_matchs [0];
    preg_match_all ( "/<[^<>]+>/is", $text, $matches, PREG_PATTERN_ORDER );
    $htmlfounds = array_filter ( array_unique ( $matches [0] ) );
    $htmlfounds = array_merge ( $script_matchs, $pre_matchs, $code_matchs, $htmlfounds );
    $htmlfounds [] = '&quot;';
    $imgFoundsSeparated = array ();
    $new_imgFoundsSeparated = array ();
    $altSeparator = '';
    $colonSeparator = '';
    foreach ( $htmlfounds as $key => $currentFound ) 
    {
        if (stristr ( $currentFound, '<img' ) && stristr ( $currentFound, 'alt' ) && ! stristr ( $currentFound, 'alt=""' )) 
        {
            $altSeparator = '';
            $colonSeparator = '';
            if (stristr ( $currentFound, 'alt="' )) {
                $altSeparator = 'alt="';
                $colonSeparator = '"';
            } elseif (stristr ( $currentFound, 'alt = "' )) {
                $altSeparator = 'alt = "';
                $colonSeparator = '"';
            } elseif (stristr ( $currentFound, 'alt ="' )) {
                $altSeparator = 'alt ="';
                $colonSeparator = '"';
            } elseif (stristr ( $currentFound, 'alt= "' )) {
                $altSeparator = 'alt= "';
                $colonSeparator = '"';
            } elseif (stristr ( $currentFound, 'alt=\'' )) {
                $altSeparator = 'alt=\'';
                $colonSeparator = '\'';
            } elseif (stristr ( $currentFound, 'alt = \'' )) {
                $altSeparator = 'alt = \'';
                $colonSeparator = '\'';
            } elseif (stristr ( $currentFound, 'alt= \'' )) {
                $altSeparator = 'alt= \'';
                $colonSeparator = '\'';
            } elseif (stristr ( $currentFound, 'alt =\'' )) {
                $altSeparator = 'alt =\'';
                $colonSeparator = '\'';
            }
            if (trim ( $altSeparator ) != '') 
            {
                $currentFoundParts = explode ( $altSeparator, $currentFound );
                $preAlt = $currentFoundParts [1];
                $preAltParts = explode ( $colonSeparator, $preAlt );
                $altText = $preAltParts [0];
                if (trim ( $altText ) != '') 
                {
                    unset ( $preAltParts [0] );
                    $past_alt_text = implode ( $colonSeparator, $preAltParts );
                    $imgFoundsSeparated [] = $currentFoundParts [0] . $altSeparator;
                    $imgFoundsSeparated [] = $colonSeparator . $past_alt_text;
                    $htmlfounds [$key] = '';
                }
            }
        }
    }
    $title_separator = str_replace ( 'alt', 'title', $altSeparator );
    if($title_separator == '')
    {
        $title_separator = 'title';
    }
    if($colonSeparator != '')
    {
        foreach ( $imgFoundsSeparated as $img_part ) 
        {
            if (stristr ( $img_part, ' title' )) 
            {
                $img_part_parts = explode ( $title_separator, $img_part );
                $pre_title_part = $img_part_parts [0] . $title_separator;
                $post_title_parts = explode ( $colonSeparator, $img_part_parts [1] );
                $found_title = $post_title_parts [0];
                unset ( $post_title_parts [0] );
                $past_title_text = implode ( $colonSeparator, $post_title_parts );
                $post_title_part = $colonSeparator . $past_title_text;
                $new_imgFoundsSeparated [] = $pre_title_part;
                $new_imgFoundsSeparated [] = $post_title_part;
            } else {
                $new_imgFoundsSeparated [] = $img_part;
            }
        }
    }
    if (count ( $new_imgFoundsSeparated ) != 0) {
        $htmlfounds = array_merge ( $htmlfounds, $new_imgFoundsSeparated );
    }
    preg_match_all ( "/<\!--.*?-->/is", $text, $matches2, PREG_PATTERN_ORDER );
    $newhtmlfounds = $matches2 [0];
    preg_match_all ( "/\[.*?\]/is", $text, $matches3, PREG_PATTERN_ORDER );
    $shortcodesfounds = $matches3 [0];
    $htmlfounds = array_merge ( $htmlfounds, $newhtmlfounds, $shortcodesfounds );
    $in = 0;
    $cleanHtmlFounds = array ();
    foreach ( $htmlfounds as $htmlfound ) {
        
        if ($htmlfound == '[19459000]') {
        } elseif (trim ( $htmlfound ) == '') {
        } else {
            $cleanHtmlFounds [] = $htmlfound;
        }
    }
    $htmlfounds = array_filter ( $cleanHtmlFounds );
    $start = 19459001;
    foreach ( $htmlfounds as $htmlfound ) {
        $text = str_replace ( $htmlfound, '[' . $start . ']', $text );
        $start ++;
    }
    $text = str_replace ( '.{', '. {', $text );
    preg_match_all ( '!(?:\[1945\d*\][\s]*){2,}!s', $text, $conseqMatchs );
    $startConseq = 19659001;
    foreach ( $conseqMatchs [0] as $conseqMatch ) {
        $text = preg_replace ( '{' . preg_quote ( trim ( $conseqMatch ) ) . '}', '[' . $startConseq . ']', $text, 1 );
        $startConseq ++;
    }
    preg_match_all ( '{\[.*?\]}', $text, $pre_tags_matches );
    $pre_tags_matches = ($pre_tags_matches [0]);
    preg_match_all ( '{\s*\[.*?\]\s*}u', $text, $pre_tags_matches_s );
    $pre_tags_matches_s = ($pre_tags_matches_s [0]);
    $text = str_replace ( '[', "\n\n[", $text );
    $text = str_replace ( ']', "]\n\n", $text );
	return $text;	
}
function echo_restoreExcludes($translated, $htmlfounds, $pre_tags_matches, $pre_tags_matches_s, $conseqMatchs, $spin_type){
    if($spin_type == 'tldr')
    {
        return $translated;
    }
    $translated = preg_replace ( '{]\s*?1945}', '][1945', $translated );
    $translated = preg_replace ( '{ 19459(\d*?)]}', ' [19459$1]', $translated );
    $translated = str_replace ( '[ [1945', '[1945', $translated );
    $translated = str_replace ( '], ', ']', $translated );
    preg_match_all ( '{\[.*?\]}', $translated, $bracket_matchs );
    $bracket_matchs = $bracket_matchs [0];
    foreach ( $bracket_matchs as $single_bracket ) 
    {
        if (stristr ( $single_bracket, '1' ) && stristr ( $single_bracket, '9' )) {
            $single_bracket_clean = str_replace ( array (
                    ',',
                    ' ' 
            ), '', $single_bracket );
            $translated = str_replace ( $single_bracket, $single_bracket_clean, $translated );
        }
    }
    preg_match_all ( '{\[\d*?\]}', $translated, $post_tags_matches );
    $post_tags_matches = ($post_tags_matches [0]);
    if (count ( $pre_tags_matches ) == count ( $post_tags_matches )) 
    {
        if ($pre_tags_matches !== $post_tags_matches) 
        {
            $i = 0;
            foreach ( $post_tags_matches as $post_tags_match ) {
                $translated = preg_replace ( '{' . preg_quote ( trim ( $post_tags_match ) ) . '}', '[' . $i . ']', $translated, 1 );
                $i ++;
            }
            $i = 0;
            foreach ( $pre_tags_matches as $pre_tags_match ) {
                $translated = str_replace ( '[' . $i . ']', $pre_tags_match, $translated );
                $i ++;
            }
        }
    }
    $translated = str_replace ( "\n\n[", '[', $translated );
    $translated = str_replace ( "]\n\n", ']', $translated );
    $i = 0;
    foreach ( $pre_tags_matches_s as $pre_tags_match ) 
    {
        $pre_tags_match_h = htmlentities ( $pre_tags_match );
        if (stristr ( $pre_tags_match_h, '&nbsp;' )) {
            $pre_tags_match = str_replace ( '&nbsp;', ' ', $pre_tags_match_h );
        }
        $translated = preg_replace ( '{' . preg_quote ( trim ( $pre_tags_match ) ) . '}', "[$i]", $translated, 1 );
        $i ++;
    }
    $translated = preg_replace ( '{\s*\[}u', '[', $translated );
    $translated = preg_replace ( '{\]\s*}u', ']', $translated );
    $i = 0;
    foreach ( $pre_tags_matches_s as $pre_tags_match ) 
    {
        $pre_tags_match_h = htmlentities ( $pre_tags_match );
        if (stristr ( $pre_tags_match_h, '&nbsp;' )) {
            $pre_tags_match = str_replace ( '&nbsp;', ' ', $pre_tags_match_h );
        }
        $translated = preg_replace ( '{' . preg_quote ( "[$i]" ) . '}', $pre_tags_match, $translated, 1 );
        $i ++;
    }
    $startConseq = 19659001;
    foreach ( $conseqMatchs [0] as $conseqMatch ) {
        $translated = str_replace ( '[' . $startConseq . ']', $conseqMatch, $translated );
        $startConseq ++;
    }
    preg_match_all ( '!\[.*?\]!', $translated, $brackets );
    $brackets = $brackets [0];
    $brackets = array_unique ( $brackets );
    foreach ( $brackets as $bracket ) {
        if (stristr ( $bracket, '19' )) 
        {
            $corrrect_bracket = str_replace ( ' ', '', $bracket );
            $corrrect_bracket = str_replace ( '.', '', $corrrect_bracket );
            $corrrect_bracket = str_replace ( ',', '', $corrrect_bracket );
            $translated = str_replace ( $bracket, $corrrect_bracket, $translated );
        }
    }
    $start = 19459001;
    foreach ( $htmlfounds as $htmlfound ) {
        $translated = str_replace ( '[' . $start . ']', $htmlfound, $translated );
        $start ++;
    }
    return $translated;
}
function echo_fix_spinned_content($final_content, $spinner)
{
    if ($spinner == 'wordai') {
        $final_content = str_replace('-LRB-', '(', $final_content);
        $final_content1 = preg_replace("/{\*\|.*?}/", '*', $final_content);
        if($final_content1 !== null)
        {
            $final_content = $final_content1;
        }
    }
    elseif ($spinner == 'spinnerchief') {
        $final_content = preg_replace('#\[[\s\\\/]*([\d]*?)[\s\\\/]*\[#', '[$1]', $final_content);
        $final_content = preg_replace('#\][\s\\\/]*([\d]*?)[\s\\\/]*\]#', '[$1]', $final_content);
        $final_content = preg_replace('#\[[\s\\\/]*([\d]*?)[\s\\\/]*\]#', '[$1]', $final_content);
    }
    elseif ($spinner == 'spinrewriter' || $spinner == 'translate') {
        $final_content = str_replace('& #', '&#', $final_content);
        $final_content = preg_replace('#&\s([a-zA-Z]+?);#', '', $final_content);
    }
    return $final_content;
}
function echo_spin_and_translate($post_title, $final_content, $translate, $source_lang, $second_translate, $hideGoogle, $use_proxy)
{
    $translation = false;
    $pre_tags_matches = array();
    $pre_tags_matches_s = array();
    $conseqMatchs = array();
    $echo_Main_Settings = get_option('echo_Main_Settings', false);
    if (isset($echo_Main_Settings['spin_text']) && $echo_Main_Settings['spin_text'] !== 'disabled' && $hideGoogle != '1') {
        
        $htmlfounds = array();
        $final_content = echo_replaceExcludes($final_content, $htmlfounds, $pre_tags_matches, $pre_tags_matches_s, $conseqMatchs, $echo_Main_Settings['spin_text']);
        
        if ($echo_Main_Settings['spin_text'] == 'builtin') {
            $translation = echo_builtin_spin_text($post_title, $final_content);
        } elseif ($echo_Main_Settings['spin_text'] == 'wikisynonyms') {
            $translation = echo_spin_text($post_title, $final_content, false);
        } elseif ($echo_Main_Settings['spin_text'] == 'freethesaurus') {
            $translation = echo_spin_text($post_title, $final_content, true);
        } elseif ($echo_Main_Settings['spin_text'] == 'best') {
            $translation = echo_best_spin_text($post_title, $final_content, $use_proxy);
        } elseif ($echo_Main_Settings['spin_text'] == 'wordai') {
            $translation = echo_wordai_spin_text($post_title, $final_content, $use_proxy);
        } elseif ($echo_Main_Settings['spin_text'] == 'tldr') {
            $translation = echo_summarize_content($post_title, $final_content);
        } elseif ($echo_Main_Settings['spin_text'] == 'spinrewriter') {
            if(isset($echo_Main_Settings['confidence_level']) && $echo_Main_Settings['confidence_level'] != '')
            {
                $confidence = $echo_Main_Settings['confidence_level'];
            }
            else
            {
                $confidence = 'medium';
            }
            $translation = echo_spinrewriter_spin_text($post_title, $final_content, $confidence);
        } elseif ($echo_Main_Settings['spin_text'] == 'spinnerchief') {
            $translation = echo_spinnerchief_spin_text($post_title, $final_content);
        }
        if ($translation !== FALSE) {
            if (is_array($translation) && isset($translation[0]) && isset($translation[1])) {
                if (isset($echo_Main_Settings['no_title_spin']) && $echo_Main_Settings['no_title_spin'] == 'on') {
                }
                else
                {
                    $post_title    = $translation[0];
                }
                $final_content = $translation[1];
                
                $final_content = echo_fix_spinned_content($final_content, $echo_Main_Settings['spin_text']);
                $final_content = echo_restoreExcludes($translation[1], $htmlfounds, $pre_tags_matches, $pre_tags_matches_s, $conseqMatchs, $echo_Main_Settings['spin_text']);
                
            } else {
                $final_content = echo_restoreExcludes($translation[1], $htmlfounds, $pre_tags_matches, $pre_tags_matches_s, $conseqMatchs, $echo_Main_Settings['spin_text']);
                if (isset($echo_Main_Settings['enable_detailed_logging'])) {
                    echo_log_to_file('Text Spinning failed - malformed data ' . $echo_Main_Settings['spin_text']);
                }
            }
        } else {
            $final_content = echo_restoreExcludes($final_content, $htmlfounds, $pre_tags_matches, $pre_tags_matches_s, $conseqMatchs, $echo_Main_Settings['spin_text']);
            if (isset($echo_Main_Settings['enable_detailed_logging'])) {
                echo_log_to_file('Text Spinning Failed - returned false ' . $echo_Main_Settings['spin_text']);
            }
        }
        $post_title = str_replace('& #x 27;', '\'', $post_title);
    }
    if ($translate != 'disabled') {
        if (isset($source_lang) && $source_lang != 'disabled' && $source_lang != '') {
            $tr = $source_lang;
        }
        else
        {
            $tr = 'auto';
        }
        $htmlfounds = array();
        $final_content = echo_replaceExcludes($final_content, $htmlfounds, $pre_tags_matches, $pre_tags_matches_s, $conseqMatchs, $echo_Main_Settings['spin_text']);
        $translation = echo_translate($post_title, $final_content, $tr, $translate, $use_proxy);
        if (is_array($translation) && isset($translation[1]))
        {
            $translation[1] = preg_replace('#(?<=[\*(])\s+(?=[\*)])#', '', $translation[1]);
            $translation[1] = preg_replace('#([^(*\s]\s)\*+\)#', '$1', $translation[1]);
            $translation[1] = preg_replace('#\(\*+([\s][^)*\s])#', '$1', $translation[1]);
            if($second_translate != '' && $second_translate != 'disabled')
            {
                $translation = echo_translate($translation[0], $translation[1], $translate, $second_translate, $use_proxy);
                if (is_array($translation) && isset($translation[1]))
                {
                    $translation[1] = echo_restoreExcludes($translation[1], $htmlfounds, $pre_tags_matches, $pre_tags_matches_s, $conseqMatchs, $echo_Main_Settings['spin_text']);
                }
                else
                {
                    $final_content = echo_restoreExcludes($translation[1], $htmlfounds, $pre_tags_matches, $pre_tags_matches_s, $conseqMatchs, $echo_Main_Settings['spin_text']);
                    $translation = false;
                    if (isset($echo_Main_Settings['enable_detailed_logging'])) {
                        echo_log_to_file('Failed to translate shortcode text the second time, from ' . $translate . ' to ' . $second_translate);
                    }
                }
            }
            else
            {
                $translation[1] = echo_restoreExcludes($translation[1], $htmlfounds, $pre_tags_matches, $pre_tags_matches_s, $conseqMatchs,  $echo_Main_Settings['spin_text']);
            }
            
        }
        else
        {
            if (isset($echo_Main_Settings['no_failed_trans']) && $echo_Main_Settings['no_failed_trans'] == 'on') 
            {
                return false;
            }
            $final_content = echo_restoreExcludes($translation[1], $htmlfounds, $pre_tags_matches, $pre_tags_matches_s, $conseqMatchs, $echo_Main_Settings['spin_text']);
        }

        if ($translation !== FALSE) {
            if (is_array($translation) && isset($translation[0]) && isset($translation[1])) {
                $post_title    = $translation[0];
                $final_content = $translation[1];
                $final_content = str_replace('</ iframe>', '</iframe>', $final_content);
                if(stristr($final_content, '<head>') !== false)
                {
                    $d = new DOMDocument;
                    $mock = new DOMDocument;
                    $internalErrors = libxml_use_internal_errors(true);
                    $d->loadHTML('<?xml encoding="utf-8" ?>' . $final_content);
                    libxml_use_internal_errors($internalErrors);
                    $body = $d->getElementsByTagName('body')->item(0);
                    foreach ($body->childNodes as $child)
                    {
                        $mock->appendChild($mock->importNode($child, true));
                    }
                    $new_post_content_temp = $mock->saveHTML();
                    if($new_post_content_temp !== '' && $new_post_content_temp !== false)
                    {
						$new_post_content_temp = str_replace('<?xml encoding="utf-8" ?>', '', $new_post_content_temp);
                        $final_content = preg_replace("/_addload\(function\(\){([^<]*)/i", "", $new_post_content_temp); 
                    }
                }
                $final_content = htmlspecialchars_decode($final_content);
                $final_content = str_replace('</ ', '</', $final_content);
                $final_content = str_replace(' />', '/>', $final_content);
                $final_content = str_replace('< br/>', '<br/>', $final_content);
                $final_content = str_replace('< / ', '</', $final_content);
                $final_content = str_replace(' / >', '/>', $final_content);
                $final_content = preg_replace('/[\x00-\x1F\x7F\xA0]/u', '', $final_content);
                $post_title = preg_replace('{&\s*#\s*(\d+)\s*;}', '&#$1;', $post_title);
                $post_title = htmlspecialchars_decode($post_title);
                $post_title = str_replace('</ ', '</', $post_title);
                $post_title = str_replace(' />', '/>', $post_title);
                $post_title = preg_replace('/[\x00-\x1F\x7F\xA0]/u', '', $post_title);
            } else {
                if (isset($echo_Main_Settings['enable_detailed_logging'])) {
                    echo_log_to_file('Translation failed - malformed data!');
                }
            }
        } else {
            if (isset($echo_Main_Settings['enable_detailed_logging'])) {
                echo_log_to_file('Translation Failed - returned false!');
            }
        }
    }
    return array(
        $post_title,
        $final_content
    );
}
add_shortcode( 'echo-display-input', 'echo_display_input' );
function echo_display_input( $atts ) {
    if ( is_admin() ) 
    {
        return;
    }
    $atts = shortcode_atts( array(
		'placeholder'               => 'Input your URL',
        'button'                    => 'Import Posts!',
        'user_submit_message'       => 'Thank you for your submission',
        'not_logged_in'             => 'Please log in to use this feature',
        'email_template'            => '',
        'email_subject'             => '',
        'email_address'             => '',
        'post_template'             => '',
        'show_category_input'       => 'off',
        'category_selector'         => '',
        'show_post_type_input'      => 'off'
	), $atts, 'echo-display-input' );
    $placeholder = sanitize_text_field( $atts['placeholder'] );
    $show_category_input = sanitize_text_field( $atts['show_category_input'] );
    $show_post_type_input = sanitize_text_field( $atts['show_post_type_input'] );
    $button      = sanitize_text_field( $atts['button'] );
    $user_submit_message = sanitize_text_field($atts['user_submit_message']);
    $not_logged_in = sanitize_text_field($atts['not_logged_in']);
    $email_template = sanitize_text_field($atts['email_template']);
    $email_subject = sanitize_text_field($atts['email_subject']);
    $email_address = sanitize_text_field($atts['email_address']);
    $post_template = $atts['post_template'];
    $category_selector = sanitize_text_field($atts['category_selector']);
    if(!is_user_logged_in())
    {
        return $not_logged_in;
    }
    if($user_submit_message != '' && isset($_POST['echo_user_button']) && isset($_POST['echo_user_input']) && trim($_POST['echo_user_input']) != '')
    {
        return html_entity_decode($user_submit_message);
    }
	$return = '<div class="echo_div_class"><form id="userForm" method="post" action=""><input name="echo_email_template_input" type="hidden" value="' . html_entity_decode($email_template) . '"><input name="echo_email_subject_input" type="hidden" value="' . html_entity_decode($email_subject) . '"><input name="echo_template" type="hidden" value="' . esc_attr(html_entity_decode($post_template)) . '"><input name="echo_email_input" type="hidden" value="' . html_entity_decode($email_address) . '"><input name="echo_user_input" type="url" validator="url" class="echo_input_class" placeholder="' . html_entity_decode($placeholder) . '">';

    if($show_category_input == 'on')
    {
        $my_cats = array();
        if($category_selector != '')
        {
            $category_selector = htmlspecialchars_decode($category_selector);
            $category_selector = explode(',', $category_selector);
            foreach($category_selector as $cts)
            {
                $cts = trim($cts);
                $cts = explode('=>', $cts);
                if(isset($cts[1]))
                {
                    $my_cats[trim($cts[0])] = trim($cts[1]);
                }
            }
        }
        $return .= '<br/><select multiple id="echo_user_input_class" name="echo_user_input_class[]" class="echo_input_class echo_input_select">';
        if(count($my_cats) == 0)
        {
            $return .= '<option value="echo_no_category_12345678" selected><?php echo esc_html__("Do Not Add a Category");?></option>';
        }
        $cat_args   = array(
            'orderby' => 'name',
            'hide_empty' => 0,
            'order' => 'ASC'
        );
        $categories = get_categories($cat_args);
        foreach ($categories as $category) {
            if(count($my_cats) > 0)
            {
                if(array_key_exists($category->slug, $my_cats))
                {                    
                    $return .= '<option value="';
                    $return .= $category->term_id;
                    $return .= '">';
                    $return .= sanitize_text_field($my_cats[$category->slug]);
                    $return .= '</option>';
                }
            }
            else
            {
                $return .= '<option value="';
                $return .= $category->term_id;
                $return .= '">';
                $return .= sanitize_text_field($category->name);
                $return .= '</option>';
            }
        }
        $return .= '</select><br/>'; 
    }
    if($show_post_type_input == 'on')
    {
        $return .= '<br/><select id="echo_user_input_post_type" name="echo_user_input_post_type[]" class="echo_input_post_type echo_input_select">';
        foreach ( get_post_types( '', 'names' ) as $post_type ) {
           $return .=  '<option value="' . esc_attr($post_type) . '"';
           $return .=  '>' . esc_html($post_type) . '</option>';
        }
        $return .= '</select><br/>'; 
    }
    $return .= '<input name="echo_user_button" type="submit" value="' . html_entity_decode($button) . '"><input name="echo_user_nonce" type="hidden" value="' . esc_attr(wp_create_nonce('echo_user_form')) . '"></form></div>';
    return $return;
}
add_action( 'wp_loaded', 'echo_check_post_header_sent' );
function echo_check_post_header_sent( $atts ) 
{
    if(isset($_POST['echo_user_button']) && isset($_POST['echo_user_nonce']) && isset($_POST['echo_user_input']) && trim($_POST['echo_user_input']) != '')
    {
        if(!wp_verify_nonce($_POST['echo_user_nonce'], 'echo_user_form'))
        {
            return;
        }
        $curr_id = 1;
        if(!is_user_logged_in())
        {
            return;
        }
        else
        {
            $curr_id = get_current_user_id();
            if($curr_id == 0)
            {
                $curr_id = 1;
            }
        }
        if (filter_var(trim($_POST['echo_user_input']), FILTER_VALIDATE_URL))
        {
            $GLOBALS['wp_object_cache']->delete('echo_rules_list', 'options');
            if (!get_option('echo_rules_list')) {
                $rules = array();
            } else {
                $rules = get_option('echo_rules_list');
            }
            $add_me = true;
            foreach($rules as $rls)
            {
                if($rls[0] == trim($_POST['echo_user_input']))
                {
                    $add_me = false;
                }    
            }
            if(isset($_POST['echo_user_input_class']) && is_array($_POST['echo_user_input_class']))
            {
                $class_lst = $_POST['echo_user_input_class'];
            }
            else
            {
                $class_lst = array('echo_no_category_12345678');
            }
            if(isset($_POST['echo_user_input_post_type']) &&  is_array($_POST['echo_user_input_post_type']))
            {
                $post_type = $_POST['echo_user_input_post_type'][0];
            }
            else
            {
                $post_type = 'post';
            }
            if($add_me == true)
            {
                if(isset($_POST['echo_template']) && $_POST['echo_template'] != '')
                {
                    $za_t = $_POST['echo_template'];
                }
                else
                {
                    $za_t = '%%item_content%%';
                }
                $my_arrg[0] = trim($_POST['echo_user_input']);
                $my_arrg[1] = '24';
                $my_arrg[2] = '1';
                $my_arrg[3] = '1988-01-27 00:00:00';
                $my_arrg[4] = '10';
                $my_arrg[5] = 'publish';
                $my_arrg[6] = $post_type;
                $my_arrg[7] = $curr_id;
                $my_arrg[8] = '';
                $my_arrg[9] = $class_lst;
                $my_arrg[10] = '0';
                $my_arrg[11] = '0';
                $my_arrg[12] = '1';
                $my_arrg[13] = '1';
                $my_arrg[14] = '';
                $my_arrg[15] = '%%item_title%%';
                $my_arrg[16] = $za_t;
                $my_arrg[17] = '1';
                $my_arrg[18] = 'post-format-standard';
                $my_arrg[19] = '0';
                $my_arrg[20] = '0';
                $my_arrg[21] = '0';
                $my_arrg[22] = 'id';
                $my_arrg[23] = '0';
                $my_arrg[24] = '';
                $my_arrg[25] = '0';
                $my_arrg[26] = '';
                $my_arrg[27] = '';
                $my_arrg[28] = '';
                $my_arrg[29] = '';
                $my_arrg[30] = 'NO_CHANGE';
                $my_arrg[31] = '';
                $my_arrg[32] = 'disabled';
                $my_arrg[33] = '0';
                $my_arrg[34] = '0';
                $my_arrg[35] = '1';
                $my_arrg[36] = '1';
                $my_arrg[37] = uniqid('', true);
                $my_arrg[38] = '';
                $my_arrg[39] = '0';
                $my_arrg[40] = '0';
                $my_arrg[41] = '';
                $my_arrg[42] = '';
                $my_arrg[43] = '0';
                $my_arrg[44] = '0';
                $my_arrg[45] = '';
                $my_arrg[46] = '';
                $my_arrg[47] = 'disabled';
                $my_arrg[48] = '';
                $my_arrg[49] = '';
                $my_arrg[50] = '';
                $my_arrg[51] = '0';
                $my_arrg[52] = '';
                $my_arrg[53] = '';
                $my_arrg[54] = '';
                $my_arrg[55] = '';
                $my_arrg[56] = '';
                $my_arrg[57] = '0';
                $my_arrg[58] = '';
                $my_arrg[59] = '0';
                $my_arrg[60] = '0';
                $my_arrg[61] = '';
                $my_arrg[62] = '';
                $my_arrg[63] = '';
                $my_arrg[64] = '';
                $my_arrg[65] = '';
                $my_arrg[66] = '';
                $my_arrg[67] = '';
                $my_arrg[68] = '';
                $my_arrg[69] = '';
                $my_arrg[70] = '';
                $my_arrg[71] = '';
                $my_arrg[72] = '';
                $my_arrg[73] = '';
                $my_arrg[74] = '';
                $my_arrg[75] = '';
                $my_arrg[76] = '';
                $my_arrg[77] = '';
                $my_arrg[78] = '';
                $my_arrg[79] = '';
                $my_arrg[80] = '';
                $my_arrg[81] = '';
                $my_arrg[82] = '';
                $my_arrg[83] = '';
                $my_arrg[84] = '';
                $my_arrg[85] = '';
                $my_arrg[86] = '';
                $my_arrg[87] = '';
                $my_arrg[88] = '';
                $my_arrg[89] = '';
                $my_arrg[90] = '';
                $my_arrg[91] = '';
                $my_arrg[92] = '';
                $my_arrg[93] = '';
                $my_arrg[94] = '';
                $my_arrg[95] = '';
                $my_arrg[96] = '';
                $my_arrg[97] = '';
                $my_arrg[98] = '';
                $my_arrg[99] = '';
                $my_arrg[100] = '';
                $my_arrg[101] = '';
                $my_arrg[102] = '';
                $rules[] = $my_arrg;
                update_option('echo_rules_list', $rules, false);
                if(isset($_POST['echo_email_input']) && $_POST['echo_email_input'] != '')
                {
                    $to = sanitize_email($_POST['echo_email_input']);
                    if (!filter_var($to, FILTER_VALIDATE_EMAIL) === false)
                    {
                        if(isset($_POST['echo_email_subject_input']) && $_POST['echo_email_subject_input'] != '')
                        {
                            $subject   = $_POST['echo_email_subject_input'];
                        }
                        else
                        {
                            $subject   = '[Echo] New RSS URL user submission';
                        }
                        if(isset($_POST['echo_email_template_input']) && $_POST['echo_email_template_input'] != '')
                        {
                            $message   = $_POST['echo_email_template_input'];
                        }
                        else
                        {
                            $message   = 'A new RSS URL user submission was just sent. Please check.';
                        }
                        $headers[] = 'From: Echo Plugin <echo@noreply.net>';
                        $headers[] = 'Reply-To: noreply@echo.com';
                        $headers[] = 'X-Mailer: PHP/' . phpversion();
                        $headers[] = 'Content-Type: text/html';
                        $headers[] = 'Charset: ' . get_option('blog_charset', 'UTF-8');
                        wp_mail($to, $subject, $message, $headers);
                    }
                }
            }
        }
    }
}
function echo_print_r_reverse($in) {
    $lines = explode("\n", trim($in));
    if (trim($lines[0]) != 'Array') {
        return $in;
    } else {
        if (preg_match("/(\s{5,})\(/", $lines[1], $match)) {
            $spaces = $match[1];
            $spaces_length = strlen($spaces);
            $lines_total = count($lines);
            for ($i = 0; $i < $lines_total; $i++) {
                if (substr($lines[$i], 0, $spaces_length) == $spaces) {
                    $lines[$i] = substr($lines[$i], $spaces_length);
                }
            }
        }
        array_shift($lines); 
        array_shift($lines); 
        array_pop($lines); 
        $in = implode("\n", $lines);
       
        preg_match_all("/^\s{4}\[(.+?)\] \=\> /m", $in, $matches, PREG_OFFSET_CAPTURE | PREG_SET_ORDER);
        $pos = array();
        $previous_key = '';
        $in_length = strlen($in);
        foreach ($matches as $match) {
            $key = $match[1][0];
            $start = $match[0][1] + strlen($match[0][0]);
            $pos[$key] = array($start, $in_length);
            if ($previous_key != '') $pos[$previous_key][1] = $match[0][1] - 1;
            $previous_key = $key;
        }
        $ret = array();
        foreach ($pos as $key => $where) {
            $ret[$key] = print_r_reverse(substr($in, $where[0], $where[1] - $where[0]));
        }
        return $ret;
    }
} 

function echo_translate($title, $content, $from, $to, $use_proxy)
{
    $ch = FALSE;
    $echo_Main_Settings = get_option('echo_Main_Settings', false);
    try {
        if($from == 'disabled')
        {
            if(strstr($to, '-') !== false && $to != 'zh-CN' && $to != 'zh-TW')
            {
                $from = 'auto-';
            }
            else
            {
                $from = 'auto';
            }
        }
        if($from != 'en' && $from != 'en-' && $from != 'en!' && $from == $to)
        {
            if(strstr($to, '-') !== false && $to != 'zh-CN' && $to != 'zh-TW')
            {
                $from = 'en-';
            }
            else
            {
                $from = 'en';
            }
        }
        elseif(($from == 'en' || $from == 'en-' || $from == 'en!') && $from == $to)
        {
            return false;
        }
        
        if(strstr($to, '!') !== false)
        {
            if (!isset($echo_Main_Settings['bing_auth']) || trim($echo_Main_Settings['bing_auth']) == '')
            {
                throw new Exception('You must enter a Microsoft Translator API key from plugin settings, to use this feature!');
            }
            require_once (dirname(__FILE__) . "/res/echo-translator-microsoft.php");
            $options    = array(
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_CONNECTTIMEOUT => 10,
                CURLOPT_TIMEOUT => 60,
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_SSL_VERIFYHOST => 0,
                CURLOPT_SSL_VERIFYPEER => 0
            );
            $ch = curl_init();
            if ($ch === FALSE) {
                echo_log_to_file ('Failed to init curl in Microsoft Translator');
				return false;
            }
            if ($use_proxy && isset($echo_Main_Settings['proxy_url']) && $echo_Main_Settings['proxy_url'] != '' && $echo_Main_Settings['proxy_url'] != 'disable' && $echo_Main_Settings['proxy_url'] != 'disabled') {
				$prx = explode(',', $echo_Main_Settings['proxy_url']);
                $randomness = array_rand($prx);
                $options[CURLOPT_PROXY] = trim($prx[$randomness]);
                if (isset($echo_Main_Settings['proxy_auth']) && $echo_Main_Settings['proxy_auth'] != '') 
                {
                    $prx_auth = explode(',', $echo_Main_Settings['proxy_auth']);
                    if(isset($prx_auth[$randomness]) && trim($prx_auth[$randomness]) != '')
                    {
                        $options[CURLOPT_PROXYUSERPWD] = trim($prx_auth[$randomness]);
                    }
                }
            }
            curl_setopt_array($ch, $options);
			$MicrosoftTranslator = new MicrosoftTranslator ( $ch );	
			try 
            {
                if (!isset($echo_Main_Settings['bing_region']) || trim($echo_Main_Settings['bing_region']) == '')
                {
                    $mt_region = 'global';
                }
                else
                {
                    $mt_region = trim($echo_Main_Settings['bing_region']);
                }
                if($from == 'auto' || $from == 'auto-' || $from == 'disabled')
                {
                    $from = 'no';
                }
				$accessToken = $MicrosoftTranslator->getToken ( trim($echo_Main_Settings['bing_auth']) , $mt_region  );
                $from = trim($from, '!');
                $to = trim($to, '!');
				$translated = $MicrosoftTranslator->translateWrap ( $content, $from, $to );
                $translated_title = $MicrosoftTranslator->translateWrap ( $title, $from, $to );
                curl_close($ch);
			} 
            catch ( Exception $e ) 
            {
                curl_close($ch);
				echo_log_to_file ('Microsoft Translation error: ' . $e->getMessage());
				return false;
			}
        }
        elseif(strstr($to, '-') !== false && $to != 'zh-CN' && $to != 'zh-TW')
        {
            if (!isset($echo_Main_Settings['deepl_auth']) || trim($echo_Main_Settings['deepl_auth']) == '')
            {
                throw new Exception('You must enter a DeepL API key from plugin settings, to use this feature!');
            }
            $deepl_auth = trim($echo_Main_Settings['deepl_auth']);
            $deepl_auth = explode(',', $deepl_auth);
            $deepl_auth = trim($deepl_auth[array_rand($deepl_auth)]);
            $to = rtrim($to, '-');
            $from = rtrim($from, '-');
            if(strlen($content) > 13000)
            {
                $translated = '';
                while($content != '')
                {
                    if (isset($echo_Main_Settings['enable_detailed_logging'])) {
                        echo_log_to_file('Running DeepL chunked translation for 30000 characters: ' . substr($content, 0, 40) . '...');
                    }
                    $first30k = substr($content, 0, 13000);
                    $content = substr($content, 13000);
                    if (isset($echo_Main_Settings['deppl_free']) && trim($echo_Main_Settings['deppl_free']) == 'on')
                    {
                        $ch = curl_init('https://api-free.deepl.com/v2/translate');
                    }
                    else
                    {
                        $ch = curl_init('https://api.deepl.com/v2/translate');
                    }
                    if($ch !== false)
                    {
                        $data           = array();
                        $data['text']   = $first30k;
                        if($from != 'auto')
                        {
                            $data['source_lang']   = $from;
                        }
                        $data['tag_handling']  = 'xml';
                        $data['non_splitting_tags']  = 'div';
                        $data['preserve_formatting']  = '1';
                        $data['target_lang']   = $to;
                        $data['auth_key']   = $deepl_auth;
                        $fdata = "";
                        foreach ($data as $key => $val) {
                            $fdata .= "$key=" . urlencode(trim($val)) . "&";
                        }
                        $headers = [
                            'Content-Type: application/x-www-form-urlencoded',
                            'Content-Length: ' . strlen($fdata)
                        ];
                        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
                        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                        curl_setopt($ch, CURLOPT_POST, 1);
                        curl_setopt($ch, CURLOPT_USERAGENT, echo_get_random_user_agent());
                        curl_setopt($ch, CURLOPT_POSTFIELDS, $fdata);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
                        curl_setopt($ch, CURLOPT_TIMEOUT, 60);              
                        if ($use_proxy && isset($echo_Main_Settings['proxy_url']) && $echo_Main_Settings['proxy_url'] != '' && $echo_Main_Settings['proxy_url'] != 'disable' && $echo_Main_Settings['proxy_url'] != 'disabled') {
                            $prx = explode(',', $echo_Main_Settings['proxy_url']);
                            $randomness = array_rand($prx);
                            curl_setopt( $ch, CURLOPT_PROXY, trim($prx[$randomness]));
                            if (isset($echo_Main_Settings['proxy_auth']) && $echo_Main_Settings['proxy_auth'] != '') 
                            {
                                $prx_auth = explode(',', $echo_Main_Settings['proxy_auth']);
                                if(isset($prx_auth[$randomness]) && trim($prx_auth[$randomness]) != '')
                                {
                                    curl_setopt( $ch, CURLOPT_PROXYUSERPWD, trim($prx_auth[$randomness]) );
                                }
                            }
                        }
                        $translated_temp = curl_exec($ch);
                        if($translated_temp === false)
                        {
                            throw new Exception('Failed to post to DeepL: ' . curl_error($ch));
                        }
                        curl_close($ch);
                        $ch = false;
                    }
                    $trans_json = json_decode($translated_temp, true);
                    if($trans_json === false)
                    {
                        throw new Exception('Incorrect multipart response from DeepL: ' . $translated_temp);
                    }
                    if(!isset($trans_json['translations'][0]['text']))
                    {
                        throw new Exception('Unrecognized multipart response from DeepL: ' . $translated_temp);
                    }
                    $translated .= ' ' . $trans_json['translations'][0]['text'];
                }
            }
            else
            {
                if (isset($echo_Main_Settings['enable_detailed_logging'])) {
                    echo_log_to_file('Running DeepL translation for ' . strlen($content) . ' characters: ' . substr($content, 0, 40) . '...');
                }
                if (isset($echo_Main_Settings['deppl_free']) && trim($echo_Main_Settings['deppl_free']) == 'on')
                {
                    $ch = curl_init('https://api-free.deepl.com/v2/translate');
                }
                else
                {
                    $ch = curl_init('https://api.deepl.com/v2/translate');
                }
                if($ch !== false)
                {
                    $data           = array();
                    $data['text']   = $content;
                    if($from != 'auto')
                    {
                        $data['source_lang']   = $from;
                    }
                    $data['tag_handling']  = 'xml';
                    $data['non_splitting_tags']  = 'div';
                    $data['preserve_formatting']  = '1';
                    $data['target_lang']   = $to;
                    $data['auth_key']   = $deepl_auth;
                    $fdata = "";
                    foreach ($data as $key => $val) {
                        $fdata .= "$key=" . urlencode(trim($val)) . "&";
                    }
                    curl_setopt($ch, CURLOPT_POST, 1);
                    $headers = [
                        'Content-Type: application/x-www-form-urlencoded',
                        'Content-Length: ' . strlen($fdata)
                    ];
                    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
                    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $fdata);
                    curl_setopt($ch, CURLOPT_USERAGENT, echo_get_random_user_agent());
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
                    curl_setopt($ch, CURLOPT_TIMEOUT, 60); 
                    if ($use_proxy && isset($echo_Main_Settings['proxy_url']) && $echo_Main_Settings['proxy_url'] != '' && $echo_Main_Settings['proxy_url'] != 'disable' && $echo_Main_Settings['proxy_url'] != 'disabled') {
                        $prx = explode(',', $echo_Main_Settings['proxy_url']);
                        $randomness = array_rand($prx);
                        curl_setopt( $ch, CURLOPT_PROXY, trim($prx[$randomness]));
                        if (isset($echo_Main_Settings['proxy_auth']) && $echo_Main_Settings['proxy_auth'] != '') 
                        {
                            $prx_auth = explode(',', $echo_Main_Settings['proxy_auth']);
                            if(isset($prx_auth[$randomness]) && trim($prx_auth[$randomness]) != '')
                            {
                                curl_setopt( $ch, CURLOPT_PROXYUSERPWD, trim($prx_auth[$randomness]) );
                            }
                        }
                    }
                    $translated = curl_exec($ch);
                    if($translated === false)
                    {
                        throw new Exception('Failed to post to DeepL: ' . curl_error($ch));
                    }
                    curl_close($ch);
                    $ch = false;
                }
                $trans_json = json_decode($translated, true);
                if($trans_json === false)
                {
                    throw new Exception('Incorrect text response from DeepL: ' . $translated);
                }
                if(!isset($trans_json['translations'][0]['text']))
                {
                    if (isset($echo_Main_Settings['deppl_free']) && trim($echo_Main_Settings['deppl_free']) == 'on')
                    {
                        throw new Exception('Unrecognized text response from DeepL: ' . 'https://api-free.deepl.com/v2/translate?text=' . urlencode($content) . '&source_lang=' . $from . '&target_lang=' . $to . '&auth_key=' . $deepl_auth . '&tag_handling=xml&preserve_formatting=1' . ' --- ' . $translated);
                    }
                    else
                    {
                        throw new Exception('Unrecognized text response from DeepL: ' . 'https://api.deepl.com/v2/translate?text=' . urlencode($content) . '&source_lang=' . $from . '&target_lang=' . $to . '&auth_key=' . $deepl_auth . '&tag_handling=xml&preserve_formatting=1' . ' --- ' . $translated);
                    }
                }
                $translated = $trans_json['translations'][0]['text'];
            }
            $translated = str_replace('<strong>', ' <strong>', $translated);
            $translated = str_replace('</strong>', '</strong> ', $translated);
            if($from != 'auto')
            {
                $from_from = '&source_lang=' . $from;
            }
            else
            {
                $from_from = '';
            }
            if (isset($echo_Main_Settings['enable_detailed_logging'])) {
                echo_log_to_file('Running DeepL translation for title for ' . strlen($title) . ' characters: ' . substr($title, 0, 40) . '...');
            }
            
            if (isset($echo_Main_Settings['deppl_free']) && trim($echo_Main_Settings['deppl_free']) == 'on')
            {
                $translated_title = echo_get_web_page('https://api-free.deepl.com/v2/translate?text=' . urlencode($title) . $from_from . '&target_lang=' . $to . '&auth_key=' . $deepl_auth . '&tag_handling=xml&preserve_formatting=1');
            }
            else
            {
                $translated_title = echo_get_web_page('https://api.deepl.com/v2/translate?text=' . urlencode($title) . $from_from . '&target_lang=' . $to . '&auth_key=' . $deepl_auth . '&tag_handling=xml&preserve_formatting=1');
            }
            $trans_json = json_decode($translated_title, true);
            if($trans_json === false)
            {
                throw new Exception('Incorrect title response from DeepL: ' . $translated_title);
            }
            if(!isset($trans_json['translations'][0]['text']))
            {
                throw new Exception('Unrecognized title response from DeepL: ' . $translated_title);
            }
            $translated_title = $trans_json['translations'][0]['text'];
        }
        else
        {
            if (isset($echo_Main_Settings['google_trans_auth']) && trim($echo_Main_Settings['google_trans_auth']) != '')
            {
                require_once(dirname(__FILE__) . "/res/translator-api.php");
                $ch = curl_init();
                if ($ch === FALSE) {
                    if (isset($echo_Main_Settings['enable_detailed_logging'])) {
                        echo_log_to_file('Failed to init cURL in translator!');
                    }
                    return false;
                }
                if ($use_proxy == '1' && isset($echo_Main_Settings['proxy_url']) && $echo_Main_Settings['proxy_url'] != '' && $echo_Main_Settings['proxy_url'] != 'disable' && $echo_Main_Settings['proxy_url'] != 'disabled') 
                {
                    $prx = explode(',', $echo_Main_Settings['proxy_url']);
                    $randomness = array_rand($prx);
                    curl_setopt( $ch, CURLOPT_PROXY, trim($prx[$randomness]));
                    if (isset($echo_Main_Settings['proxy_auth']) && $echo_Main_Settings['proxy_auth'] != '') 
                    {
                        $prx_auth = explode(',', $echo_Main_Settings['proxy_auth']);
                        if(isset($prx_auth[$randomness]) && trim($prx_auth[$randomness]) != '')
                        {
                            curl_setopt( $ch, CURLOPT_PROXYUSERPWD, trim($prx_auth[$randomness]) );
                        }
                    }
                }
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
                curl_setopt($ch, CURLOPT_TIMEOUT, 10);
                $GoogleTranslatorAPI = new GoogleTranslatorAPI($ch, $echo_Main_Settings['google_trans_auth']);
                $translated = '';
                $translated_title = '';
                if($content != '')
                {
                    if(strlen($content) > 13000)
                    {
                        while($content != '')
                        {
                            $first30k = substr($content, 0, 13000);
                            $content = substr($content, 13000);
                            $translated_temp       = $GoogleTranslatorAPI->translateText($first30k, $from, $to);
                            $translated .= ' ' . $translated_temp;
                        }
                    }
                    else
                    {
                        $translated       = $GoogleTranslatorAPI->translateText($content, $from, $to);
                    }
                }
                if($title != '')
                {
                    $translated_title = $GoogleTranslatorAPI->translateText($title, $from, $to);
                }
                curl_close($ch);
            }
            else
            {
                require_once(dirname(__FILE__) . "/res/echo-translator.php");
                $ch = curl_init();
                if ($ch === FALSE) {
                    if (isset($echo_Main_Settings['enable_detailed_logging'])) {
                        echo_log_to_file('Failed to init cURL in translator!');
                    }
                    return false;
                }
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
                curl_setopt($ch, CURLOPT_TIMEOUT, 60);
                curl_setopt($ch, CURLOPT_USERAGENT, echo_get_random_user_agent());
                if ($use_proxy && isset($echo_Main_Settings['proxy_url']) && $echo_Main_Settings['proxy_url'] != '' && $echo_Main_Settings['proxy_url'] != 'disable' && $echo_Main_Settings['proxy_url'] != 'disabled') {
                    $prx = explode(',', $echo_Main_Settings['proxy_url']);
                    $randomness = array_rand($prx);
                    curl_setopt( $ch, CURLOPT_PROXY, trim($prx[$randomness]));
                    if (isset($echo_Main_Settings['proxy_auth']) && $echo_Main_Settings['proxy_auth'] != '') 
                    {
                        $prx_auth = explode(',', $echo_Main_Settings['proxy_auth']);
                        if(isset($prx_auth[$randomness]) && trim($prx_auth[$randomness]) != '')
                        {
                            curl_setopt( $ch, CURLOPT_PROXYUSERPWD, trim($prx_auth[$randomness]) );
                        }
                    }
                }
                $GoogleTranslator = new GoogleTranslator($ch);
                if(strlen($content) > 13000)
                {
                    $translated = '';
                    while($content != '')
                    {
                        $first30k = substr($content, 0, 13000);
                        $content = substr($content, 13000);
                        $translated_temp       = $GoogleTranslator->translateText($first30k, $from, $to);
                        if (strpos($translated, '<h2>The page you have attempted to translate is already in ') !== false) {
                            throw new Exception('Page content already in ' . $to);
                        }
                        if (strpos($translated, 'Error 400 (Bad Request)!!1') !== false) {
                            throw new Exception('Unexpected error while translating page!');
                        }
                        if(substr_compare($translated_temp, '</pre>', -strlen('</pre>')) === 0){$translated_temp = substr_replace($translated_temp ,"", -6);}if(substr( $translated_temp, 0, 5 ) === "<pre>"){$translated_temp = substr($translated_temp, 5);}
                        $translated .= ' ' . $translated_temp;
                    }
                }
                else
                {
                    $translated       = $GoogleTranslator->translateText($content, $from, $to);
                    if (strpos($translated, '<h2>The page you have attempted to translate is already in ') !== false) {
                        throw new Exception('Page content already in ' . $to);
                    }
                    if (strpos($translated, 'Error 400 (Bad Request)!!1') !== false) {
                        throw new Exception('Unexpected error while translating page!');
                    }
                }
                $translated_title = $GoogleTranslator->translateText($title, $from, $to);
                if (strpos($translated_title, '<h2>The page you have attempted to translate is already in ') !== false) {
                    throw new Exception('Page title already in ' . $to);
                }
                if (strpos($translated_title, 'Error 400 (Bad Request)!!1') !== false) {
                    throw new Exception('Unexpected error while translating page title!');
                }
                curl_close($ch);
            }
        }
    }
    catch (Exception $e) {
        if($ch !== false)
        {
            curl_close($ch);
        }
        if (isset($echo_Main_Settings['enable_detailed_logging'])) {
            echo_log_to_file('Exception thrown in Translator ' . $e);
        }
        return false;
    }
    if(substr_compare($translated_title, '</pre>', -strlen('</pre>')) === 0){$title = substr_replace($translated_title ,"", -6);}else{$title = $translated_title;}if(substr( $title, 0, 5 ) === "<pre>"){$title = substr($title, 5);}
    if(substr_compare($translated, '</pre>', -strlen('</pre>')) === 0){$text = substr_replace($translated ,"", -6);}else{$text = $translated;}if(substr( $text, 0, 5 ) === "<pre>"){$text = substr($text, 5);}
    $text  = preg_replace('/' . preg_quote('html lang=') . '.*?' . preg_quote('>') . '/', '', $text);
    $text  = preg_replace('/' . preg_quote('!DOCTYPE') . '.*?' . preg_quote('<') . '/', '', $text);
    $text = str_replace('%% item_cat %%', '%%item_cat%%', $text);
    $text = str_replace('%% item_tags %%', '%%item_tags%%', $text);
    $text = str_replace('%% item_url %%', '%%item_url%%', $text);
    $text = str_replace('%% item_read_more_button %%', '%%item_read_more_button%%', $text);
    $text = str_replace('%%item_read_more_button %%', '%%item_read_more_button%%', $text);
    $text = str_replace('%% item_read_more_button%%', '%%item_read_more_button%%', $text);
    $text = str_replace('%% item_image_URL %%', '%%item_image_URL%%', $text);
    $text = str_replace('%% author_link %%', '%%author_link%%', $text);
    $text = str_replace('%% custom_html2 %%', '%%custom_html2%%', $text);
    $text = str_replace('%% custom_html %%', '%%custom_html%%', $text);
    $text = str_replace('%% random_sentence %%', '%%random_sentence%%', $text);
    $text = str_replace('%% random_sentence2 %%', '%%random_sentence2%%', $text);
    $text = str_replace('%% item_title %%', '%%item_title%%', $text);
    $text = str_replace('%% item_content %%', '%%item_content%%', $text);
    $text = str_replace('%% item_original_content %%', '%%item_original_content%%', $text);
    $text = str_replace('%% item_content_plain_text %%', '%%item_content_plain_text%%', $text);
    $text = str_replace('%% item_description %%', '%%item_description%%', $text);
    $text = str_replace('%% author %%', '%%author%%', $text);
    $text = str_replace('%% item_media %%', '%%item_media%%', $text);
    $text = str_replace('%% item_date %%', '%%item_date%%', $text);
    $text = str_replace('&amp; # 039;', '\'', $text);
    $text = str_replace('%% %% item_read_more_button', '%%item_read_more_button%%', $text);
    $text = str_replace('&amp; ldquo;', '"', $text);
    $text = str_replace('&amp; rdquo;', '"', $text);
    $text = str_replace(' \' ', '\'', $text);
    $text = preg_replace('{<iframe src="https://translate.google.com/translate(?:.*?)></iframe>}i', "", html_entity_decode($text, ENT_QUOTES));
    $text = preg_replace('{<span class="google-src-text.*?>.*?</span>}', "", $text);
    $text = preg_replace('{<span class="notranslate.*?>(.*?)</span>}', "$1", $text);
    $title = str_replace('%% random_sentence %%', '%%random_sentence%%', $title);
    $title = str_replace('%% random_sentence2 %%', '%%random_sentence2%%', $title);
    $title = str_replace('%% item_title %%', '%%item_title%%', $title);
    $title = str_replace('%% item_description %%', '%%item_description%%', $title);
    $title = str_replace('%% item_url %%', '%%item_url%%', $title);
    $title = str_replace('%% item_date %%', '%%item_date%%', $title);
    $title = str_replace('%% author %%', '%%author%%', $title);
    $title = str_replace('%% item_cat %%', '%%item_cat%%', $title);
    $title = str_replace('%% item_tags %%', '%%item_tags%%', $title);
    $title = str_replace('&amp; # 039;', '\'', $title);
    $title = str_replace('&amp; ldquo;', '"', $title);
    $title = str_replace('&amp; rdquo;', '"', $title);
    $title = str_replace(' \' ', '\'', $title);

    return array(
        $title,
        $text
    );
}

function echo_strip_html_tags($str)
{
    $str = html_entity_decode($str);
    $str1 = preg_replace('/(<|>)\1{2}/is', '', $str);
    if($str1 !== null)
    {
        $str = $str1;
    }
    $str1 = preg_replace(array(
        '@<head[^>]*?>.*?</head>@siu',
        '@<style[^>]*?>.*?</style>@siu',
        '@<script[^>]*?.*?</script>@siu',
        '@<noscript[^>]*?.*?</noscript>@siu'
    ), "", $str);
    if($str1 !== null)
    {
        $str = $str1;
    }
    $str = str_replace('><', '> <', $str);
    $str = strip_tags($str);
    return $str;
}
function echo_strip_html_tags_nl($str, $allow_html_tags = null)
{
    $str = html_entity_decode($str);
    $str = preg_replace('/(<|>)\1{2}/is', '', $str);
    $str = preg_replace(array(
        '@<head[^>]*?>.*?</head>@siu',
        '@<style[^>]*?>.*?</style>@siu',
        '@<script[^>]*?.*?</script>@siu',
        '@<noscript[^>]*?.*?</noscript>@siu'
    ), "", $str);
    $str = strip_tags($str, $allow_html_tags);
	$str = preg_replace('#<br\s*\/?>#i', PHP_EOL, $str);
	$str = preg_replace('#<\/p>#i', PHP_EOL . PHP_EOL, $str);
	$str = preg_replace('#<p([^>]*?)>#i', '', $str);
    return $str;
}

function echo_DOMinnerHTML(DOMNode $element)
{
    $innerHTML = "";
    $children  = $element->childNodes;
    
    foreach ($children as $child) {
        $innerHTML .= $element->ownerDocument->saveHTML($child);
    }
    
    return $innerHTML;
}

function echo_url_exists($url)
{
    $headers = echo_get_url_header($url);
    if (!isset($headers[0]) || strpos($headers[0], '200') === false)
        return false;
    return true;
}
use fivefilters\Readability\Readability;
use fivefilters\Readability\Configuration;
function echo_convert_readable_html($html_string) {
    if(!class_exists('\fivefilters\Readability\Readability'))
    {
        require_once (dirname(__FILE__) . "/res/readability/vendor/autoload.php");

        require_once (dirname(__FILE__) . "/res/readability/Readability.php");
        require_once (dirname(__FILE__) . "/res/readability/ParseException.php");
        require_once (dirname(__FILE__) . "/res/readability/Configuration.php");
        require_once (dirname(__FILE__) . "/res/readability/Nodes/NodeUtility.php");
        require_once (dirname(__FILE__) . "/res/readability/Nodes/NodeTrait.php");
        require_once (dirname(__FILE__) . "/res/readability/Nodes/DOM/DOMAttr.php");
        require_once (dirname(__FILE__) . "/res/readability/Nodes/DOM/DOMCdataSection.php");
        require_once (dirname(__FILE__) . "/res/readability/Nodes/DOM/DOMCharacterData.php");
        require_once (dirname(__FILE__) . "/res/readability/Nodes/DOM/DOMComment.php");
        require_once (dirname(__FILE__) . "/res/readability/Nodes/DOM/DOMDocument.php");
        require_once (dirname(__FILE__) . "/res/readability/Nodes/DOM/DOMDocumentFragment.php");
        require_once (dirname(__FILE__) . "/res/readability/Nodes/DOM/DOMDocumentType.php");
        require_once (dirname(__FILE__) . "/res/readability/Nodes/DOM/DOMElement.php");
        require_once (dirname(__FILE__) . "/res/readability/Nodes/DOM/DOMEntity.php");
        require_once (dirname(__FILE__) . "/res/readability/Nodes/DOM/DOMEntityReference.php");
        require_once (dirname(__FILE__) . "/res/readability/Nodes/DOM/DOMNodeList.php");
        require_once (dirname(__FILE__) . "/res/readability/Nodes/DOM/DOMNode.php");
        require_once (dirname(__FILE__) . "/res/readability/Nodes/DOM/DOMNotation.php");
        require_once (dirname(__FILE__) . "/res/readability/Nodes/DOM/DOMProcessingInstruction.php");
        require_once (dirname(__FILE__) . "/res/readability/Nodes/DOM/DOMText.php");
    }
    try {
        $readConf = new Configuration();
        $readConf->setSummonCthulhu(true);
        $readability = new Readability($readConf);
        $readability->parse($html_string);
        $return_me = $readability->getContent();
        if($return_me == '' || $return_me == null)
        {
            throw new Exception('Content blank');
        }
        $return_me = str_replace('</article>', '', $return_me);
        $return_me = str_replace('<article>', '', $return_me);
        return $return_me;
    } catch (Exception $e) {
        try
        {
            require_once (dirname(__FILE__) . "/res/echo-readability.php");
            $readability = new Readability2($html_string);
            $readability->debug = false;
            $readability->convertLinksToFootnotes = false;
            $result = $readability->init();
            if ($result) {
                $content = $readability->getContent()->innerHTML;
                $content = str_replace('</article>', '', $content);
                $content = str_replace('<article>', '', $content);
                return $content;
            } else {
                return '';
            }
        }
        catch(Exception $e2)
        {
            echo_log_to_file('Readability failed: ' . sprintf('Error processing text: %s', $e2->getMessage()));
            return '';
        }
    }
}
function echo_convert_readable_title($html_string) {
    if(!class_exists('\fivefilters\Readability\Readability'))
    {
        require_once (dirname(__FILE__) . "/res/readability/vendor/autoload.php");

        require_once (dirname(__FILE__) . "/res/readability/Readability.php");
        require_once (dirname(__FILE__) . "/res/readability/ParseException.php");
        require_once (dirname(__FILE__) . "/res/readability/Configuration.php");
        require_once (dirname(__FILE__) . "/res/readability/Nodes/NodeUtility.php");
        require_once (dirname(__FILE__) . "/res/readability/Nodes/NodeTrait.php");
        require_once (dirname(__FILE__) . "/res/readability/Nodes/DOM/DOMAttr.php");
        require_once (dirname(__FILE__) . "/res/readability/Nodes/DOM/DOMCdataSection.php");
        require_once (dirname(__FILE__) . "/res/readability/Nodes/DOM/DOMCharacterData.php");
        require_once (dirname(__FILE__) . "/res/readability/Nodes/DOM/DOMComment.php");
        require_once (dirname(__FILE__) . "/res/readability/Nodes/DOM/DOMDocument.php");
        require_once (dirname(__FILE__) . "/res/readability/Nodes/DOM/DOMDocumentFragment.php");
        require_once (dirname(__FILE__) . "/res/readability/Nodes/DOM/DOMDocumentType.php");
        require_once (dirname(__FILE__) . "/res/readability/Nodes/DOM/DOMElement.php");
        require_once (dirname(__FILE__) . "/res/readability/Nodes/DOM/DOMEntity.php");
        require_once (dirname(__FILE__) . "/res/readability/Nodes/DOM/DOMEntityReference.php");
        require_once (dirname(__FILE__) . "/res/readability/Nodes/DOM/DOMNodeList.php");
        require_once (dirname(__FILE__) . "/res/readability/Nodes/DOM/DOMNode.php");
        require_once (dirname(__FILE__) . "/res/readability/Nodes/DOM/DOMNotation.php");
        require_once (dirname(__FILE__) . "/res/readability/Nodes/DOM/DOMProcessingInstruction.php");
        require_once (dirname(__FILE__) . "/res/readability/Nodes/DOM/DOMText.php");
    }
    try {
        $readConf = new Configuration();
        $readConf->setSummonCthulhu(true);
        $readability = new Readability($readConf);
        $readability->parse($html_string);
        $return_me = $readability->getTitle();
        if($return_me == '' || $return_me == null)
        {
            throw new Exception('Title blank');
        }
        return $return_me;
    } catch (Exception $e) {
        try
        {
            require_once (dirname(__FILE__) . "/res/echo-readability.php");
            $readability = new Readability2($html_string);
            $readability->debug = false;
            $readability->convertLinksToFootnotes = false;
            $result = $readability->init();
            if ($result) {
                $content = $readability->getTitle()->innerHTML;
                return $content;
            } else {
                return '';
            }
        }
        catch(Exception $e2)
        {
            echo_log_to_file('Readability failed: ' . sprintf('Error processing text: %s', $e2->getMessage()));
            return '';
        }
    }
}
if (!function_exists('iconv') && function_exists('libiconv')) {
    function iconv($input_encoding, $output_encoding, $string) {
        return libiconv($input_encoding, $output_encoding, $string);
    }
}
function echo_wp_strip_all_tags($string, $remove_breaks = false) {
    $string = preg_replace( '@<(script|style)[^>]*?>.*?</\\1>@si', '', $string );
    $string = strip_tags(str_replace('<', ' <', $string));
 
    if ( $remove_breaks )
        $string = preg_replace('/[\r\n\t ]+/', ' ', $string);
 
    return trim( $string );
}
function echo_get_title($content)
{
    preg_match('{<meta[^<]*?property=["\']og:title["\'][^<]*?>}i', $content, $mathc);
    if(isset($mathc[0]) && stristr($mathc[0], 'content')){
        preg_match('{content\s*=["\'](.*?)["\']}s', $mathc[0],$matx);
        if(isset($matx[1]))
        {
            $auth = $matx[1];
            if(trim($auth) !='')
            {
                return $auth;
            }
        }
    }
    preg_match('{<meta[^<]*?property=["\']twitter:title["\'][^<]*?>}i', $content, $mathc);
    if(isset($mathc[0]) && stristr($mathc[0], 'content')){
        preg_match('{content\s*=["\'](.*?)["\']}s', $mathc[0],$matx);
        if(isset($matx[1]))
        {
            $auth = $matx[1];
            if(trim($auth) !='')
            {
                return $auth;
            }
        }
    }
    preg_match('{<meta[^<]*?itemprop\s*=["\']title["\'][^<]*?>}i', $content, $mathc);
    if(isset($mathc[0]) && stristr($mathc[0], 'content=')){
        preg_match('{content\s*=["\'](.*?)["\']}s', $mathc[0],$matx);
        if(isset($matx[1]))
        {
            $auth = $matx[1];
            if(trim($auth) !='')
            {
                return $auth;
            }
        }
    }
    preg_match('{<meta[^<]*?itemprop\s*=["\']headline["\'][^<]*?>}i', $content, $mathc);
    if(isset($mathc[0]) && stristr($mathc[0], 'content=')){
        preg_match('{content\s*=["\'](.*?)["\']}s', $mathc[0],$matx);
        if(isset($matx[1]))
        {
            $auth = $matx[1];
            if(trim($auth) !='')
            {
                return $auth;
            }
        }
    }
    preg_match('{<title(?:[^>]*?)>([^<]*?)<\/title>}i', $content, $mathc);
    if(isset($mathc[1][0])){
        $auth = $mathc[1][0];
        if(trim($auth) !='')
        {
            return $auth;
        }
    }
    return ''; 
}
function echo_get_full_content($url, $type, $getname, $single, $inner, $encoding, $user_agent_cust, $use_phantom, $custom_cookie, $use_proxy, $is_galerts, $tag_type, $tag_expre, &$tags, &$htmlcontent, &$html_dl_failed, &$my_title, $htuser_pass)
{
    require_once (dirname(__FILE__) . "/res/simple_html_dom.php"); 
    $echo_Main_Settings = get_option('echo_Main_Settings', false);
    $extract = '';
    $htmlcontent = '';
    $got_phantom = false;
    if($use_phantom == '1')
    {
        $htmlcontent = echo_get_page_PhantomJS($url, $custom_cookie, $user_agent_cust, $use_proxy);
        if($htmlcontent !== false)
        {
            $got_phantom = true;
        }
    }
    elseif($use_phantom == '2')
    {
        $htmlcontent = echo_get_page_Puppeteer($url, $custom_cookie, $user_agent_cust, $use_proxy, $htuser_pass);
        if($htmlcontent !== false)
        {
            $got_phantom = true;
        }
    }
    elseif($use_phantom == '4')
    {
        $htmlcontent = echo_get_page_PuppeteerAPI($url, $custom_cookie, $user_agent_cust, $use_proxy, $htuser_pass, '');
        if($htmlcontent !== false)
        {
            $got_phantom = true;
        }
    }
    elseif($use_phantom == '5')
    {
        $htmlcontent = echo_get_page_TorAPI($url, $custom_cookie, $user_agent_cust, $use_proxy, $htuser_pass, '');
        if($htmlcontent !== false)
        {
            $got_phantom = true;
        }
    }
    elseif($use_phantom == '6')
    {
        $htmlcontent = echo_get_page_PhantomJSAPI($url, $custom_cookie, $user_agent_cust, $use_proxy, $htuser_pass, '');
        if($htmlcontent !== false)
        {
            $got_phantom = true;
        }
    }
    if($got_phantom === false)
    {
        if (!echo_check_if_phantom($use_phantom) || !isset($echo_Main_Settings['disable_fallback']) || $echo_Main_Settings['disable_fallback'] != 'on')
        { 
            $htmlcontent = echo_get_web_page($url, $user_agent_cust, $custom_cookie, $use_proxy, $htuser_pass);
        }
    }
    if($htmlcontent === FALSE)
    {
        if (isset($echo_Main_Settings['enable_detailed_logging'])) {
            echo_log_to_file('echo_get_web_page failed for: ' . $url . ', query: ' . $getname . ', type: ' . $type);
        }
        $html_dl_failed = true;
        return false;
    }
    if($my_title != 'skip')
    {
        $my_title_temp = echo_wp_strip_all_tags(echo_get_title($htmlcontent));
        if($my_title_temp == '')
        {
            $extract = echo_convert_readable_title($htmlcontent);
            if($extract != '')
            {
                $my_title_temp = $extract[0];
            }
        }
        if($my_title_temp != '')
        {
            $my_title = $my_title_temp;
        }
    }
    $htmlcontent = preg_replace('/[\r\n]/', ' ', $htmlcontent);
    if($is_galerts == true && $htmlcontent !== false)
    {
        preg_match('#<meta(?:[^>]*) content="(?:\d+);url=([^"<>]*?)" (?:[^>]*)>#i', $htmlcontent, $galerm);
        if(isset($galerm[1]))
        {
            $got_phantom = false;
            $htmlcontent = false;
            if($use_phantom == '1')
            {
                $htmlcontent = echo_get_page_PhantomJS($galerm[1], $custom_cookie, $user_agent_cust, $use_proxy);
                if($htmlcontent !== false)
                {
                    $got_phantom = true;
                }
            }
            elseif($use_phantom == '2')
            {
                $htmlcontent = echo_get_page_Puppeteer($galerm[1], $custom_cookie, $user_agent_cust, $use_proxy, $htuser_pass);
                if($htmlcontent !== false)
                {
                    $got_phantom = true;
                }
            }
            elseif($use_phantom == '4')
            {
                $htmlcontent = echo_get_page_PuppeteerAPI($galerm[1], $custom_cookie, $user_agent_cust, $use_proxy, $htuser_pass, '');
                if($htmlcontent !== false)
                {
                    $got_phantom = true;
                }
            }
            elseif($use_phantom == '5')
            {
                $htmlcontent = echo_get_page_TorAPI($galerm[1], $custom_cookie, $user_agent_cust, $use_proxy, $htuser_pass, '');
                if($htmlcontent !== false)
                {
                    $got_phantom = true;
                }
            }
            elseif($use_phantom == '6')
            {
                $htmlcontent = echo_get_page_PhantomJSAPI($galerm[1], $custom_cookie, $user_agent_cust, $use_proxy, $htuser_pass, '');
                if($htmlcontent !== false)
                {
                    $got_phantom = true;
                }
            }
            if($got_phantom === false)
            {
                if (!echo_check_if_phantom($use_phantom) || !isset($echo_Main_Settings['disable_fallback']) || $echo_Main_Settings['disable_fallback'] != 'on')
                { 
                    $htmlcontent = echo_get_web_page($galerm[1], '', '', $use_proxy, $htuser_pass);
                }
            }
            if($htmlcontent === FALSE)
            {
                if (isset($echo_Main_Settings['enable_detailed_logging'])) {
                    echo_log_to_file('echo_get_web_page (2) failed for: ' . $url . ', query: ' . $getname . ', type: ' . $type);
                }
                $html_dl_failed = true;
                return false;
            }
        }
    }
    if (isset($echo_Main_Settings['enable_robots']) && $echo_Main_Settings['enable_robots'] == 'on') 
    {
        $is_robots = echo_findRobotsMetaTagProperties($htmlcontent);
        if((isset($is_robots['noindex']) && $is_robots['noindex'] == true) || (isset($is_robots['none']) && $is_robots['none'] == true))
        {
            if (isset($echo_Main_Settings['enable_detailed_logging'])) 
            {
                echo_log_to_file('Skipping URL, crawling disabled by robots tag: ' . $url);
            }
            $html_dl_failed = true;
            return false;
        }
    }
    if ($encoding != 'UTF-8' && $encoding != 'NO_CHANGE')
    {
        $extract_temp = FALSE;
        if($encoding !== 'AUTO')
        {
            if (function_exists('iconv'))
            {
                $extract_temp = iconv($encoding, "UTF-8//IGNORE", $htmlcontent);
            }
        }
        else
        {
            if(function_exists('mb_detect_encoding'))
            {
                $temp_enc = mb_detect_encoding($htmlcontent, 'auto');
                if ($temp_enc !== FALSE && $temp_enc != 'UTF-8')
                {
                    if (function_exists('iconv'))
                    {
                        $extract_temp = iconv($temp_enc, "UTF-8//IGNORE", $htmlcontent);
                    }
                }
            }
        }
        if($extract_temp !== FALSE)
        {
            $htmlcontent = $extract_temp;
        }
        else
        {
            if($encoding !== 'AUTO')
            {
                if (isset($echo_Main_Settings['enable_detailed_logging'])) {
                    echo_log_to_file('Failed to convert to encoding ' . $encoding);
                }
            }
        }
    }
    if($getname == '' || $type == 'auto')
    {
        $extract = echo_convert_readable_html($htmlcontent);
    }
    else
    {
        if ($type == 'regex') {
            $matches     = array();
            $rez = preg_match_all($getname, $htmlcontent, $matches);
            if ($rez === FALSE) {
                if (isset($echo_Main_Settings['enable_detailed_logging'])) {
                    echo_log_to_file('[echo get full content] preg_match_all failed for expr: ' . $getname);
                }
                return false;
            }
            if ($inner == '1')
            {
                if(isset($matches[1][0]))
                {
                    foreach ($matches[1] as $match) {
                        $extract .= $match;
                        if ($single == '1') {
                            break;
                        }
                    }
                }
            }
            else
            {
                if(isset($matches[0][0]))
                {
                    foreach ($matches[0] as $match) {
                        $extract .= $match;
                        if ($single == '1') {
                            break;
                        }
                    }
                }
            }
        } elseif ($type == 'xpath' || $type == 'visual') {
            $extractok = false;
            $html_dom_original_html = echo_str_get_html($htmlcontent);
            if(stristr($getname, ' or ') === false && $html_dom_original_html !== false && method_exists($html_dom_original_html, 'find')){
                $ret = $html_dom_original_html->find( trim($getname) );
                foreach ($ret as $item ) {
                    $extractok = true;
                    if($inner == '1'){
                        $extract = $extract . $item->innertext ;
                    }else{
                        $extract = $extract . $item->outertext ;
                    }
                    if ($single == '1') {
                        break;
                    }		
                }
                $html_dom_original_html->clear();
                unset($html_dom_original_html);
            }
            if($extractok == false)
            {
                $doc = new DOMDocument;
                $internalErrors = libxml_use_internal_errors(true);
                $doc->loadHTML('<?xml encoding="utf-8" ?>' . $htmlcontent);
                libxml_use_internal_errors($internalErrors);
                $xpath = new \DOMXpath($doc);
                $articles = $xpath->query(trim($getname));
                if($articles !== false && $articles->length > 0)
                {
                    foreach($articles as $container) {
                        if(method_exists($container, 'saveHTML'))
                        {
                            $extract .= ' ' . $container->saveHTML();
                        }
                        elseif(isset($container->ownerDocument) && method_exists($container->ownerDocument, 'saveHTML'))
                        {
                            $extract .= ' ' . $container->ownerDocument->saveHTML($container);
                        }
                        elseif(isset($container->nodeValue))
                        {
                            $extract .= ' ' . $container->nodeValue;
                        }
                    }
                }
            }
        } elseif ($type == 'class' && strstr(trim($getname), ' ') === false) {
            $extractok = false;
            $html_dom_original_html = echo_str_get_html($htmlcontent);
            if($html_dom_original_html !== false && method_exists($html_dom_original_html, 'find')){
                $getnames = explode(',', $getname);
                foreach($getnames as $gname)
                {
                    $ret = $html_dom_original_html->find('//*[contains(@class, "' . trim($gname) . '")]');
                    foreach ($ret as $item ) {
                        $extractok = true;
                        if($inner == '1'){
                            $extract = $extract . $item->innertext ;
                        }else{
                            $extract = $extract . $item->outertext ;
                        }
                        if ($single == '1') {
                            break;
                        }	
                    }
                }
                $html_dom_original_html->clear();
                unset($html_dom_original_html);
            }
            if($extractok == false)
            {
                $doc = new DOMDocument;
                $internalErrors = libxml_use_internal_errors(true);
                $doc->loadHTML('<?xml encoding="utf-8" ?>' . $htmlcontent);
                libxml_use_internal_errors($internalErrors);
                $xpath = new \DOMXpath($doc);
                $getnames = explode(',', $getname);
                foreach($getnames as $gname)
                {
                    $articles = $xpath->query('//*[contains(@class, "' . $gname . '")]');
                    if($articles !== false && $articles->length > 0)
                    {
                        foreach($articles as $container) {
                            if(method_exists($container, 'saveHTML'))
                            {
                                $extract .= ' ' . $container->saveHTML();
                            }
                            elseif(isset($container->ownerDocument) && method_exists($container->ownerDocument, 'saveHTML'))
                            {
                                $extract .= ' ' . $container->ownerDocument->saveHTML($container);
                            }
                            elseif(isset($container->nodeValue))
                            {
                                $extract .= ' ' . $container->nodeValue;
                            }
                        }
                    }
                }
            }
        } else {
            $extractok = false;
            $html_dom_original_html = echo_str_get_html($htmlcontent);
            if($html_dom_original_html !== false && method_exists($html_dom_original_html, 'find')){
                $getnames = explode(',', $getname);
                foreach($getnames as $gname)
                {
                    $ret = $html_dom_original_html->find('*['.$type.'="'.trim($gname).'"]');
                    foreach ($ret as $item ) {
                        $extractok = true;
                        if($inner == '1'){
                            $extract = $extract . $item->innertext ;
                        }else{
                            $extract = $extract . $item->outertext ;
                        }
                        if ($single == '1') {
                            break;
                        }	
                    }
                }
                $html_dom_original_html->clear();
                unset($html_dom_original_html);
            }
            if($extractok == false)
            {
                $doc = new DOMDocument;
                $internalErrors = libxml_use_internal_errors(true);
                $doc->loadHTML('<?xml encoding="utf-8" ?>' . $htmlcontent);
                libxml_use_internal_errors($internalErrors);
                $xpath = new \DOMXpath($doc);
                $getnames = explode(',', $getname);
                foreach($getnames as $gname)
                {
                    $articles = $xpath->query('//*[@'.$type.'="'.trim($gname).'"]');
                    if($articles !== false && $articles->length > 0)
                    {
                        foreach($articles as $container) {
                            if(method_exists($container, 'saveHTML'))
                            {
                                $extract .= ' ' . $container->saveHTML();
                            }
                            elseif(isset($container->ownerDocument) && method_exists($container->ownerDocument, 'saveHTML'))
                            {
                                $extract .= ' ' . $container->ownerDocument->saveHTML($container);
                            }
                            elseif(isset($container->nodeValue))
                            {
                                $extract .= ' ' . $container->nodeValue;
                            }
                        }
                    }
                }
            }
        }
        if($extract == '')
        {
            $extract = echo_convert_readable_html($htmlcontent);
        }
    }
    if($tag_expre == '' || $tag_type == 'disabled')
    {
    }
    else
    {
        if ($tag_type == 'regex') {
            $matches     = array();
            $rez = preg_match_all($tag_expre, $htmlcontent, $matches);
            if ($rez === FALSE) {
                if (isset($echo_Main_Settings['enable_detailed_logging'])) {
                    echo_log_to_file('[echo get full content] preg_match_all failed for tag_expr: ' . $tag_expre);
                }
            }
            if ($inner == '1')
            {
                if(isset($matches[1][0]))
                {
                    foreach ($matches[1] as $match) {
                        $tags .= $match. ',';
                        if ($single == '1') {
                            break;
                        }
                    }
                }
            }
            else
            {
                if(isset($matches[0][0]))
                {
                    foreach ($matches[0] as $match) {
                        $tags .= $match. ',';
                        if ($single == '1') {
                            break;
                        }
                    }
                }
            }
        } elseif ($tag_type == 'xpath' || $tag_type == 'visual') {
            $extractok = false;
            $html_dom_original_html = echo_str_get_html($htmlcontent);
            if($html_dom_original_html !== false && method_exists($html_dom_original_html, 'find')){
                $ret = $html_dom_original_html->find( trim($tag_expre) );
                foreach ($ret as $item ) {
                    $extractok = true;
                    if($inner == '1'){
                        $tags .= $item->innertext. ',' ;
                    }else{
                        $tags .= $item->outertext. ',' ;
                    }
                    if ($single == '1') {
                        break;
                    }		
                }
                $html_dom_original_html->clear();
                unset($html_dom_original_html);
            }
            if($extractok == true)
            {
                $doc = new DOMDocument;
                $internalErrors = libxml_use_internal_errors(true);
                $doc->loadHTML('<?xml encoding="utf-8" ?>' . $htmlcontent);
                libxml_use_internal_errors($internalErrors);
                $xpath = new \DOMXpath($doc);
                $articles = $xpath->query(trim($tag_expre));
                if($articles !== false && $articles->length > 0)
                {
                    foreach($articles as $container) {
                        if(method_exists($container, 'saveHTML'))
                        {
                            $tags .= $container->saveHTML(). ',' ;
                        }
                        elseif(isset($container->ownerDocument) && method_exists($container->ownerDocument, 'saveHTML'))
                        {
                            $tags .= $container->ownerDocument->saveHTML($container). ',' ;
                        }
                        elseif(isset($container->nodeValue))
                        {
                            $tags .= $container->nodeValue. ',' ;
                        }
                    }
                }
            }
        } else {
            $extractok = false;
            $html_dom_original_html = echo_str_get_html($htmlcontent);
            if($html_dom_original_html !== false && method_exists($html_dom_original_html, 'find')){
                $getnames = explode(',', $tag_expre);
                foreach($getnames as $gname)
                {
                    $ret = $html_dom_original_html->find('*['.$tag_type.'="'.trim($gname).'"]');
                    foreach ($ret as $item ) {
                        $extractok = true;
                        if($inner == '1'){
                            $tags = $tags . $item->innertext. ',' ;
                        }else{
                            $tags = $tags . $item->outertext. ',' ;
                        }
                        if ($single == '1') {
                            break;
                        }		
                    }
                }
                $html_dom_original_html->clear();
                unset($html_dom_original_html);
            }
            if($extractok == false)
            {
                $doc = new DOMDocument;
                $internalErrors = libxml_use_internal_errors(true);
                $doc->loadHTML('<?xml encoding="utf-8" ?>' . $htmlcontent);
                libxml_use_internal_errors($internalErrors);
                $xpath = new \DOMXpath($doc);
                $getnames = explode(',', $tag_expre);
                foreach($getnames as $gname)
                {
                    $articles = $xpath->query('//*[@'.$tag_type.'="'.trim($gname).'"]');
                    if($articles !== false && $articles->length > 0)
                {
                        foreach($articles as $container) {
                            if(method_exists($container, 'saveHTML'))
                            {
                                $tags .= $container->saveHTML() . ',';
                            }
                            elseif(isset($container->ownerDocument) && method_exists($container->ownerDocument, 'saveHTML'))
                            {
                                $tags .= $container->ownerDocument->saveHTML($container) . ',';
                            }
                            elseif(isset($container->nodeValue))
                            {
                                $tags .= $container->nodeValue . ',';
                            }
                        }
                    }
                }
            }
        }
        $tags = trim($tags, ',');
    }
    $my_url  = parse_url($url);
	$my_host = $my_url['host'];
    preg_match_all('{src[\s]*=[\s]*["|\'](.*?)["|\'].*?>}is', $extract , $matches);
	$img_srcs =  ($matches[1]);
	foreach ($img_srcs as $img_src){
		$original_src = $img_src;
        if(stristr($img_src, '../')){
			$img_src = str_replace('../', '', $img_src);
		}
		if(stristr($img_src, 'http:') === FALSE && stristr($img_src, 'www.') === FALSE && stristr($img_src, 'https:') === FALSE && stristr($img_src, 'data:image') === FALSE)
		{
			$img_src = trim($img_src);
			if(preg_match('{^//}', $img_src)){
				$img_src = 'http:'.$img_src;
			}elseif( preg_match('{^/}', $img_src) ){
				$img_src = 'http://'.$my_host.$img_src;
			}else{
				$img_src = 'http://'.$my_host.'/'.$img_src;
			}
			$reg_img = '{["|\'][\s]*'.preg_quote($original_src,'{').'[\s]*["|\']}s';
            $extract = preg_replace( $reg_img, '"'.$img_src.'"', $extract);
		}
	}
    preg_match_all('{href[\s]*=[\s]*["\'](.*?)["\']}is', $extract , $matches);
	$link_srcs =  ($matches[1]);
	foreach ($link_srcs as $link_src){
		$original_src = $link_src;
        if(stristr($link_src, '../')){
			$link_src = str_replace('../', '', $link_src);
		}
		if(stristr($link_src, 'http:') === FALSE && stristr($link_src, 'www.') === FALSE && stristr($link_src, 'https:') === FALSE)
		{
			$link_src = trim($link_src);
			if(preg_match('{^//}', $link_src)){
				$link_src = 'http:'.$link_src;
			}elseif( preg_match('{^/}', $link_src) ){
				$link_src = 'http://'.$my_host.$link_src;
			}else{
				$link_src = 'http://'.$my_host.'/'.$link_src;
			}
			$reg_img = '{["|\'][\s]*'.preg_quote($original_src,'{').'[\s]*["|\']}s';
            $extract = preg_replace( $reg_img, '"'.$link_src.'"', $extract);
		}
	}	
    $extract = str_replace('href="../', 'href="http://'.$my_host.'/', $extract);
	$extract = preg_replace('{href="/(\w)}', 'href="http://'.$my_host.'/$1', $extract);
    $extract = preg_replace('{\ssrcset=".*?"}', ' ', $extract);
	$extract = preg_replace('{\ssizes=".*?"}', ' ', $extract);
    $extract = html_entity_decode($extract, ENT_NOQUOTES | ENT_HTML5) ;
    if (isset($echo_Main_Settings['strip_scripts']) && $echo_Main_Settings['strip_scripts'] == 'on') {
        $extract = preg_replace('{<ins.*?ins>}s', '', $extract);
        $extract = preg_replace('{<ins.*?>}s', '', $extract);
        $extract = preg_replace('{<script[\s\S]*?\/\s?script>}s', '', $extract);
        $extract = preg_replace('{\(adsbygoogle.*?\);}s', '', $extract);
    }
    return $extract;
}
function echo_check_if_phantom($use_phantom)
{
    if($use_phantom == '1' || $use_phantom == '2' || $use_phantom == '3' || $use_phantom == '4' || $use_phantom == '5' || $use_phantom == '6')
    {
        return true;
    }
    return false;
}

function echo_substr_close_tags($text, $max_length)
{
    $tags   = array();
    $result = "";

    $is_open   = false;
    $grab_open = false;
    $is_close  = false;
    $in_double_quotes = false;
    $in_single_quotes = false;
    $tag = "";

    $i = 0;
    $stripped = 0;

    $stripped_text = strip_tags($text);
    if (function_exists('mb_strlen') && function_exists('mb_substr')) {
        while ($i < mb_strlen($text) && $stripped < mb_strlen($stripped_text) && $stripped < $max_length)
        {
            $symbol  = mb_substr($text,$i,1);
            $result .= $symbol;

            switch ($symbol)
            {
               case '<':
                    $is_open   = true;
                    $grab_open = true;
                    break;

               case '"':
                   if ($in_double_quotes)
                       $in_double_quotes = false;
                   else
                       $in_double_quotes = true;

                break;

                case "'":
                  if ($in_single_quotes)
                      $in_single_quotes = false;
                  else
                      $in_single_quotes = true;

                break;

                case '/':
                    if ($is_open && !$in_double_quotes && !$in_single_quotes)
                    {
                        $is_close  = true;
                        $is_open   = false;
                        $grab_open = false;
                    }

                    break;

                case ' ':
                    if ($is_open)
                        $grab_open = false;
                    else
                        $stripped++;

                    break;

                case '>':
                    if ($is_open)
                    {
                        $is_open   = false;
                        $grab_open = false;
                        array_push($tags, $tag);
                        $tag = "";
                    }
                    else if ($is_close)
                    {
                        $is_close = false;
                        array_pop($tags);
                        $tag = "";
                    }

                    break;

                default:
                    if ($grab_open || $is_close)
                        $tag .= $symbol;

                    if (!$is_open && !$is_close)
                        $stripped++;
            }
            $i++;
        }
    }
    else
    {
        while ($i < strlen($text) && $stripped < strlen($stripped_text) && $stripped < $max_length)
        {
            $symbol  = $text[$i];
            $result .= $symbol;

            switch ($symbol)
            {
               case '<':
                    $is_open   = true;
                    $grab_open = true;
                    break;

               case '"':
                   if ($in_double_quotes)
                       $in_double_quotes = false;
                   else
                       $in_double_quotes = true;

                break;

                case "'":
                  if ($in_single_quotes)
                      $in_single_quotes = false;
                  else
                      $in_single_quotes = true;

                break;

                case '/':
                    if ($is_open && !$in_double_quotes && !$in_single_quotes)
                    {
                        $is_close  = true;
                        $is_open   = false;
                        $grab_open = false;
                    }

                    break;

                case ' ':
                    if ($is_open)
                        $grab_open = false;
                    else
                        $stripped++;

                    break;

                case '>':
                    if ($is_open)
                    {
                        $is_open   = false;
                        $grab_open = false;
                        array_push($tags, $tag);
                        $tag = "";
                    }
                    else if ($is_close)
                    {
                        $is_close = false;
                        array_pop($tags);
                        $tag = "";
                    }

                    break;

                default:
                    if ($grab_open || $is_close)
                        $tag .= $symbol;

                    if (!$is_open && !$is_close)
                        $stripped++;
            }
            $i++;
        }
    }

    while ($tags)
        $result .= "</".array_pop($tags).">";
    return force_balance_tags($result);
}


register_activation_hook(__FILE__, 'echo_check_version');
function echo_check_version()
{
    if (!function_exists('curl_init')) {
        echo '<h3>'.esc_html__('Please enable curl PHP extension. Please contact your hosting provider\'s support to help you in this matter.', 'rss-feed-post-generator-echo').'</h3>';
        die;
    }
    global $wp_version;
    if (!current_user_can('activate_plugins')) {
        echo '<p>' . esc_html__('You are not allowed to activate plugins!', 'rss-feed-post-generator-echo') . '</p>';
        die;
    }
    $php_version_required = '5.6';
    $wp_version_required  = '2.7';
    
    if (version_compare(PHP_VERSION, $php_version_required, '<')) {
        deactivate_plugins(basename(__FILE__));
        echo '<p>' . sprintf(esc_html__('This plugin can not be activated because it requires a PHP version greater than %1$s. Please update your PHP version before you activate it.', 'rss-feed-post-generator-echo'), $php_version_required) . '</p>';
        die;
    }
    
    if (version_compare($wp_version, $wp_version_required, '<')) {
        deactivate_plugins(basename(__FILE__));
        echo '<p>' . sprintf(esc_html__('This plugin can not be activated because it requires a WordPress version greater than %1$s. Please go to Dashboard -> Updates to get the latest version of WordPress.', 'rss-feed-post-generator-echo'), $wp_version_required) . '</p>';
        die;
    }
}
function echo_get_pinfo() {
    ob_start();
    phpinfo();
    $data = ob_get_contents();
    ob_clean();
    return $data;
}

function echo_redirect($url, $statusCode = 301)
{
   if(!function_exists('wp_redirect'))
   {
       include_once( ABSPATH . 'wp-includes/pluggable.php' );
   }
   wp_redirect($url, $statusCode);
   die();
}
function echo_license_not_activated_notice() {
?>
    <div class="notice notice-error is-dismissible">
        <p><?php 
        $result = sprintf( wp_kses( __( 'The Echo RSS plugin\'s license is not activated. Please <a href="%s" target="_blank">activate the license</a> to use the plugin. You can get a new license, <a href="%s" target="_blank">here</a>.', 'rss-feed-post-generator-echo'), array(  'a' => array( 'href' => array(), 'target' => array() ) ) ), admin_url('admin.php?page=echo_admin_settings'), 'https://1.envato.market/echo');
        echo $result; ?></p>
    </div>
<?php
}

function echo_register_mysettings()
{
    echo_cron_schedule();
    register_setting('echo_option_group', 'echo_Main_Settings');
    
    if(isset($_GET['echo_page']))
    {
        $curent_page = $_GET["echo_page"];
    }
    else
    {
        $curent_page = '';
    }
    $GLOBALS['wp_object_cache']->delete('echo_rules_list', 'options');
    $all_rules = get_option('echo_rules_list', array());
    if($all_rules === false)
    {
        $all_rules = array();
    }
    $rules_count = count($all_rules);
    $rules_per_page = get_option('echo_posts_per_page', 10);
    $max_pages = ceil($rules_count/$rules_per_page);
    if($max_pages == 0)
    {
        $max_pages = 1;
    }
    $last_url = (echo_isSecure() ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    if(stristr($last_url, 'echo_items_panel') !== false && (!is_numeric($curent_page) || $curent_page > $max_pages || $curent_page <= 0))
    {
        if(stristr($last_url, 'echo_page=') === false)
        {
            if(stristr($last_url, '?') === false)
            {
                $last_url .= '?echo_page=' . $max_pages;
            }
            else
            {
                $last_url .= '&echo_page=' . $max_pages;
            }
        }
        else
        {
            if(isset($_GET['echo_page']))
            {
                $curent_page = $_GET["echo_page"];
            }
            else
            {
                $curent_page = '';
            }
            if(is_numeric($curent_page))
            {
                $last_url = str_replace('echo_page=' . $curent_page, 'echo_page=' . $max_pages, $last_url);
            }
            else
            {
                if(stristr($last_url, '?') === false)
                {
                    $last_url .= '?echo_page=' . $max_pages;
                }
                else
                {
                    $last_url .= '&echo_page=' . $max_pages;
                }
            }
        }
        echo_redirect($last_url);
    }

    if(is_multisite())
    {
        if (!get_option('echo_Main_Settings'))
        {
            echo_activation_callback(TRUE);
        }
    }
    if(isset($_POST['echo_download_rules_to_file']))
    {
        $GLOBALS['wp_object_cache']->delete('echo_rules_list', 'options');
        if (!get_option('echo_rules_list')) {
            $rules = array();
        } else {
            $rules = get_option('echo_rules_list');
        }
        header("Content-type: application/x-msdownload");
        header("Content-Disposition: attachment; filename=echo_rules.bak");
        header("Pragma: no-cache");
        header("Expires: 0");
        echo json_encode($rules);
        exit();
    }
    if(isset($_POST['echo_restore_rules']))
    {
        if(isset($_FILES['echo-file-upload-rules']['tmp_name'])) 
        {
            global $wp_filesystem;
            if ( ! is_a( $wp_filesystem, 'WP_Filesystem_Base') ){
                include_once(ABSPATH . 'wp-admin/includes/file.php');$creds = request_filesystem_credentials( site_url() );
                wp_filesystem($creds);
            }
            error_reporting(0);
            $file = $wp_filesystem->get_contents($_FILES['echo-file-upload-rules']['tmp_name']);
            error_reporting(E_ALL);
            if($file === false)
            {
                echo_log_to_file('Failed to restore rules from file: ' . $_FILES['echo-file-upload-rules']['tmp_name']);
            }
            else
            {
                $rules = json_decode($file, true);
                if($rules === false)
                {
                    echo_log_to_file('Failed to decode value: ' . print_r($file, true));
                }
                else
                {
                    if(isset($rules[0][0]) && isset($rules[0][37]))
                    {
                        update_option('echo_rules_list', $rules, false);
                    }
                    else
                    {
                        echo_log_to_file('Invalid file given: ' . print_r($rules, true));
                    }
                }
            }               
        }
    }
}

function echo_get_plugin_url()
{
    return plugins_url('', __FILE__);
}

function echo_get_file_url($url)
{
    return esc_url_raw(echo_get_plugin_url() . '/' . $url);
}

function echo_admin_load_files()
{
    wp_register_style('echo-browser-style', plugins_url('styles/echo-browser.css', __FILE__), false, ECHO_MAJOR_VERSION);
    wp_enqueue_style('echo-browser-style');
    wp_register_style('echo-custom-style', plugins_url('styles/coderevolution-style.css', __FILE__), false, ECHO_MAJOR_VERSION);
    wp_enqueue_style('echo-custom-style');
    wp_enqueue_script('jquery');
    wp_enqueue_script('media-upload');
    wp_enqueue_script('thickbox');
    wp_enqueue_style('thickbox');
}

add_action('template_redirect', 'echo_wp_load_files_fast');
function echo_wp_load_files_fast()
{
    $echo_Main_Settings = get_option('echo_Main_Settings', false);
    if (isset($echo_Main_Settings['echo_enabled']) && $echo_Main_Settings['echo_enabled'] == 'on') 
    {
        if (!isset($echo_Main_Settings['no_canonical_removal']) || $echo_Main_Settings['no_canonical_removal'] != 'on')
        {
            $go_canonical = false;
            if ( is_singular() ) 
            {
                $source_url = get_post_meta(get_the_ID(), 'echo_add_canonical', true);
                if($source_url == '1')
                {
                    $go_canonical = true;
                }
            }
            if ($go_canonical == true || (isset($echo_Main_Settings['rel_canonical']) && $echo_Main_Settings['rel_canonical'] == 'on')) 
            {
                remove_action( 'wp_head', 'rel_canonical' );
                add_action( 'wp_head', 'echo_rel_canonical' );
                add_filter( 'wpseo_canonical', function( $canonical )
                {
                    if ( is_single())
                    {
                        $id = get_the_ID();
                        if(!empty(get_post_meta( $id, 'echo_post_url', true )) || !empty(get_post_meta( $id, 'crawlomatic_post_url', true )) || !empty(get_post_meta( $id, 'hackeromatic_post_url', true )) || !empty(get_post_meta( $id, 'newsomatic_post_url', true )) || !empty(get_post_meta( $id, 'quoramatic_post_url', true )) || !empty(get_post_meta( $id, 'viralomatic_post_url', true )))
                        {
                            return false;
                        }
                    }
                    return $canonical;
                });
                add_filter( 'rank_math/frontend/canonical', function( $canonical )
                {
                    if ( is_single())
                    {
                        $id = get_the_ID();
                        if(!empty(get_post_meta( $id, 'echo_post_url', true )) || !empty(get_post_meta( $id, 'crawlomatic_post_url', true )) || !empty(get_post_meta( $id, 'hackeromatic_post_url', true )) || !empty(get_post_meta( $id, 'newsomatic_post_url', true )) || !empty(get_post_meta( $id, 'quoramatic_post_url', true )) || !empty(get_post_meta( $id, 'viralomatic_post_url', true )))
                        {
                            return false;
                        }
                    }
                    return $canonical;
                });
            }
        }
    }
}

add_action('wp_enqueue_scripts', 'echo_wp_load_files');
function echo_wp_load_files()
{
    wp_enqueue_style('coderevolution-front-css', plugins_url('styles/coderevolution-front.css', __FILE__), false, ECHO_MAJOR_VERSION);
    wp_enqueue_style('echo-thumbnail-css', plugins_url('styles/echo-thumbnail.css', __FILE__), false, ECHO_MAJOR_VERSION);
}

function echo_random_sentence_generator($first = true)
{
    $echo_Main_Settings = get_option('echo_Main_Settings', false);
    if ($first == false) {
        $r_sentences = $echo_Main_Settings['sentence_list2'];
    } else {
        $r_sentences = $echo_Main_Settings['sentence_list'];
    }
    $r_variables = $echo_Main_Settings['variable_list'];
    $r_sentences = trim($r_sentences);
    $r_variables = trim($r_variables, ';');
    $r_variables = trim($r_variables);
    $r_sentences = str_replace("\r\n", "\n", $r_sentences);
    $r_sentences = str_replace("\r", "\n", $r_sentences);
    $r_sentences = explode("\n", $r_sentences);
    $r_variables = str_replace("\r\n", "\n", $r_variables);
    $r_variables = str_replace("\r", "\n", $r_variables);
    $r_variables = explode("\n", $r_variables);
    $r_vars      = array();
    for ($x = 0; $x < count($r_variables); $x++) {
        $var = explode("=>", trim($r_variables[$x]));
        if (isset($var[1])) {
            $key          = strtolower(trim($var[0]));
            $words        = explode(";", trim($var[1]));
            $r_vars[$key] = $words;
        }
    }
    $max_s    = count($r_sentences) - 1;
    $rand_s   = rand(0, $max_s);
    $sentence = $r_sentences[$rand_s];
    $sentence = str_replace(' ,', ',', ucfirst(echo_replace_words($sentence, $r_vars)));
    $sentence = str_replace(' .', '.', $sentence);
    $sentence = str_replace(' !', '!', $sentence);
    $sentence = str_replace(' ?', '?', $sentence);
    $sentence = trim($sentence);
    $spintax = new Echo_Spintax();
    $sentence = $spintax->process($sentence);
    return $sentence;
}

function echo_get_word($key, $r_vars)
{
    if (isset($r_vars[$key])) {
        
        $words  = $r_vars[$key];
        $w_max  = count($words) - 1;
        $w_rand = rand(0, $w_max);
        return echo_replace_words(trim($words[$w_rand]), $r_vars);
    } else {
        return "";
    }
    
}

function echo_replace_words($sentence, $r_vars)
{
    
    if (str_replace('%', '', $sentence) == $sentence)
        return $sentence;
    
    $words = explode(" ", $sentence);
    
    $new_sentence = array();
    for ($w = 0; $w < count($words); $w++) {
        
        $word = trim($words[$w]);
        
        if ($word != '') {
            if (preg_match('/^%([^%\n]*)$/', $word, $m)) {
                $varkey         = trim($m[1]);
                $new_sentence[] = echo_get_word($varkey, $r_vars);
            } else {
                $new_sentence[] = $word;
            }
        }
    }
    return implode(" ", $new_sentence);
}

class Echo_keywords{ 
    public static $charset = 'UTF-8';
    public static $banned_words = array('adsbygoogle', 'able', 'about', 'above', 'act', 'add', 'afraid', 'after', 'again', 'against', 'age', 'ago', 'agree', 'all', 'almost', 'alone', 'along', 'already', 'also', 'although', 'always', 'am', 'amount', 'an', 'and', 'anger', 'angry', 'animal', 'another', 'answer', 'any', 'appear', 'apple', 'are', 'arrive', 'arm', 'arms', 'around', 'arrive', 'as', 'ask', 'at', 'attempt', 'aunt', 'away', 'back', 'bad', 'bag', 'bay', 'be', 'became', 'because', 'become', 'been', 'before', 'began', 'begin', 'behind', 'being', 'bell', 'belong', 'below', 'beside', 'best', 'better', 'between', 'beyond', 'big', 'body', 'bone', 'born', 'borrow', 'both', 'bottom', 'box', 'boy', 'break', 'bring', 'brought', 'bug', 'built', 'busy', 'but', 'buy', 'by', 'call', 'came', 'can', 'cause', 'choose', 'close', 'close', 'consider', 'come', 'consider', 'considerable', 'contain', 'continue', 'could', 'cry', 'cut', 'dare', 'dark', 'deal', 'dear', 'decide', 'deep', 'did', 'die', 'do', 'does', 'dog', 'done', 'doubt', 'down', 'during', 'each', 'ear', 'early', 'eat', 'effort', 'either', 'else', 'end', 'enjoy', 'enough', 'enter', 'even', 'ever', 'every', 'except', 'expect', 'explain', 'fail', 'fall', 'far', 'fat', 'favor', 'fear', 'feel', 'feet', 'fell', 'felt', 'few', 'fill', 'find', 'fit', 'fly', 'follow', 'for', 'forever', 'forget', 'from', 'front', 'gave', 'get', 'gives', 'goes', 'gone', 'good', 'got', 'gray', 'great', 'green', 'grew', 'grow', 'guess', 'had', 'half', 'hang', 'happen', 'has', 'hat', 'have', 'he', 'hear', 'heard', 'held', 'hello', 'help', 'her', 'here', 'hers', 'high', 'hill', 'him', 'his', 'hit', 'hold', 'hot', 'how', 'however', 'I', 'if', 'ill', 'in', 'indeed', 'instead', 'into', 'iron', 'is', 'it', 'its', 'just', 'keep', 'kept', 'knew', 'know', 'known', 'late', 'least', 'led', 'left', 'lend', 'less', 'let', 'like', 'likely', 'likr', 'lone', 'long', 'look', 'lot', 'make', 'many', 'may', 'me', 'mean', 'met', 'might', 'mile', 'mine', 'moon', 'more', 'most', 'move', 'much', 'must', 'my', 'near', 'nearly', 'necessary', 'neither', 'never', 'next', 'no', 'none', 'nor', 'not', 'note', 'nothing', 'now', 'number', 'of', 'off', 'often', 'oh', 'on', 'once', 'only', 'or', 'other', 'ought', 'our', 'out', 'please', 'prepare', 'probable', 'pull', 'pure', 'push', 'put', 'raise', 'ran', 'rather', 'reach', 'realize', 'reply', 'require', 'rest', 'run', 'said', 'same', 'sat', 'saw', 'say', 'see', 'seem', 'seen', 'self', 'sell', 'sent', 'separate', 'set', 'shall', 'she', 'should', 'side', 'sign', 'since', 'so', 'sold', 'some', 'soon', 'sorry', 'stay', 'step', 'stick', 'still', 'stood', 'such', 'sudden', 'suppose', 'take', 'taken', 'talk', 'tall', 'tell', 'ten', 'than', 'thank', 'that', 'the', 'their', 'them', 'then', 'there', 'therefore', 'these', 'they', 'this', 'those', 'though', 'through', 'till', 'to', 'today', 'told', 'tomorrow', 'too', 'took', 'tore', 'tought', 'toward', 'tried', 'tries', 'trust', 'try', 'turn', 'two', 'under', 'until', 'up', 'upon', 'us', 'use', 'usual', 'various', 'verb', 'very', 'visit', 'want', 'was', 'we', 'well', 'went', 'were', 'what', 'when', 'where', 'whether', 'which', 'while', 'white', 'who', 'whom', 'whose', 'why', 'will', 'with', 'within', 'without', 'would', 'yes', 'yet', 'you', 'young', 'your', 'br', 'img', 'p','lt', 'gt', 'quot', 'copy');
    public static $min_word_length = 4;
    
    public static function text($text, $length = 160)
    {
        return self::limit_chars(self::clean($text), $length,'',TRUE);
    } 

    public static function keywords($text, $max_keys = 3)
    {
        if(!function_exists('echo_greeklish_slugs'))
        {
            include_once(dirname(__FILE__) . "/res/diacritics.php");
        }
        $wordcount = array_count_values(str_word_count(self::clean($text), 1, $diacritics));
        foreach ($wordcount as $key => $value) 
        {
            if ( (strlen($key)<= self::$min_word_length) OR in_array($key, self::$banned_words))
                unset($wordcount[$key]);
        }
        uasort($wordcount,[self::class, 'cmp']);
        $wordcount = array_slice($wordcount,0, $max_keys);
        return implode(' ', array_keys($wordcount));
    } 

    private static function clean($text)
    { 
        $text = html_entity_decode($text,ENT_QUOTES,self::$charset);
        $text = strip_tags($text);
        $text = preg_replace('/\s\s+/', ' ', $text);
        $text = str_replace (array('\r\n', '\n', '+'), ',', $text);
        return trim($text); 
    } 

    private static function cmp($a, $b) 
    {
        if ($a == $b) return 0; 

        return ($a < $b) ? 1 : -1; 
    } 

    private static function limit_chars($str, $limit = 100, $end_char = NULL, $preserve_words = FALSE)
    {
        $end_char = ($end_char === NULL) ? '&#8230;' : $end_char;
        $limit = (int) $limit;
        if (trim($str) === '' OR strlen($str) <= $limit)
            return $str;
        if ($limit <= 0)
            return $end_char;
        if ($preserve_words === FALSE)
            return rtrim(substr($str, 0, $limit)).$end_char;
        if ( ! preg_match('/^.{0,'.$limit.'}\s/us', $str, $matches))
            return $end_char;
        return rtrim($matches[0]).((strlen($matches[0]) === strlen($str)) ? '' : $end_char);
    }
}

function echo_findRobotsMetaTagProperties($html)
{
    $metaTagLine = echo_findRobotsMetaTagLine($html);

    return [
        'noindex' => $metaTagLine
            ? strpos(strtolower($metaTagLine), 'noindex') !== false
            : false,

        'none' => $metaTagLine
            ? strpos(strtolower($metaTagLine), 'none') !== false
            : false,
    ];
}

function echo_findRobotsMetaTagLine($html)
{
    if (preg_match('/\<meta name="robots".*?\>/mis', $html, $matches)) {
        return $matches[0];
    }

    return '';
}
?>