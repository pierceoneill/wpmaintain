<?php
/**
 * Plugin Name: 		Massive Cryptocurrency Widgets
 * Plugin URI:          https://massivecryptopro.blocksera.com
 * Description: 		Premium collection of cryptocurrency widgets for WordPress. Beautifully designed and highly customizable.
 * Author: 				Blocksera
 * Author URI:			https://blocksera.com
 * Version: 			3.2.5
 * Requires at least:   4.3.0
 * Requires PHP:        5.6
 * License: 			GPL v3
 * Text Domain:			massive-cryptocurrency-widgets
 * Domain Path: 		/languages
 * Copyright 2020 Blocksera Technologies
**/

if (!defined('ABSPATH')) {
    exit;
}

define('MCW_VERSION', '3.2.5');
define('MCW_PATH', plugin_dir_path(__FILE__));
define('MCW_URL', plugin_dir_url(__FILE__));

require_once MCW_PATH . 'includes/all.php';

if (!class_exists('MassiveCrypto')) {

    class MassiveCrypto {

        public function __construct() {
            global $wpdb;

            $data = new MassiveCryptoData();            

            $this->config = array_merge($data->config, get_option('mcw_config', array()));
            $this->fonts = $data->fonts;
            $this->changelly = $data->changelly;
            $this->options = $data->options;
            $this->providers = $data->providers;
            $this->options['config'] = apply_filters('mcw_get_config', $this->config);
            $this->updater = new Blocksera_Updater(__FILE__, 'massive-cryptocurrency-widgets', 'JrL2BUzwfYFUUHogLMGM', $this->options['config']['license_key']);
            $this->updater->checker->addResultFilter(array($this, 'refreshLicenseFromPluginInfo'));
            
            $this->wpdb = $wpdb;
            $this->tablename = $this->wpdb->base_prefix . "mcw_coins";

            $this->init();
            $this->create_post_type();

            register_activation_hook(__FILE__, array($this, 'activate'));
            register_deactivation_hook(__FILE__, array($this, 'deactivate'));
        }

        public function fetch_coins($config) {

            $cache = get_transient('mcw-datatime');
            
            $api_interval = ($config['api'] == 'coingecko') ? 900 : $config['api_interval'];

            if ($cache === false || $cache < (time() - $api_interval)) {
                
                switch ($config['api']) {

                    case 'coingecko':
    
                        $request = wp_remote_get('https://api.blocksera.com/v1/tickers');

                        if (is_wp_error($request) || wp_remote_retrieve_response_code($request) != 200) {
                            $this->wpdb->get_results("SELECT `slug` FROM `{$this->tablename}`");

                            if ($this->wpdb->num_rows > 0) {
                                set_transient('mcw-datatime', time(), 60);
                            }
                            return false;
                        }

                        $body = wp_remote_retrieve_body($request);
                        $data = json_decode($body);
    
                        if (!empty($data)) {
                
                                $this->wpdb->query("TRUNCATE `{$this->tablename}`");
                
                                $btc_price = $data[0]->current_price;

                                $values = [];

                                foreach ($data as $coin) {
                                    if (!($coin->market_cap === null || $coin->market_cap_rank === null)) {
                                        $coin->price_btc = $coin->current_price / $btc_price;
                                        $coin->image = strpos($coin->image, 'coingecko.com') ? strtok($coin->image, '?') : MCW_URL . 'assets/public/img/missing.png';
                                        $values[] = array($coin->name, strtoupper($coin->symbol), $coin->id, $coin->image, $coin->market_cap_rank, floatval($coin->current_price), floatval($coin->price_btc), floatval($coin->total_volume), floatval($coin->market_cap), floatval($coin->high_24h), floatval($coin->low_24h), floatval($coin->circulating_supply), floatval($coin->total_supply), floatval($coin->ath), strtotime($coin->ath_date), floatval($coin->price_change_24h), floatval($coin->price_change_percentage_1h), floatval($coin->price_change_percentage_24h), floatval($coin->price_change_percentage_7d), floatval($coin->price_change_percentage_30d), gmdate("Y-m-d H:i:s"));
                                    }
                                }

                                $values = array_chunk($values, 100, true);

                                foreach ($values as $chunk) {
                                    $placeholder = "(%s, %s, %s, %s, %d, %0.14f, %0.8f, %0.2f, %0.2f, %0.10f, %0.10f, %0.2f, %0.2f, %0.10f, %d, %0.10f, %0.2f, %0.2f, %0.2f, %0.2f, %s)";
                                    $query = "INSERT IGNORE INTO `{$this->tablename}` (`name`, `symbol`, `slug`, `img`, `rank`, `price_usd`, `price_btc`, `volume_usd_24h`, `market_cap_usd`, `high_24h`, `low_24h`, `available_supply`, `total_supply`, `ath`, `ath_date`, `price_change_24h`, `percent_change_1h`, `percent_change_24h`, `percent_change_7d`, `percent_change_30d`, `weekly_expire`) VALUES ";
                                    $query .= implode(", ", array_fill(0, count($chunk), $placeholder));
                                    $this->wpdb->query($this->wpdb->prepare($query, call_user_func_array('array_merge', $chunk)));
                                }
                                set_transient('mcw-datatime', time());
                        }

                        break;
    
                    case 'coinmarketcap':
    
                        $request = wp_remote_get('https://pro-api.coinmarketcap.com/v1/cryptocurrency/listings/latest?limit=5000', array('headers' => array('X-CMC_PRO_API_KEY' => $config['api_key'])));

                        if (is_wp_error($request) || wp_remote_retrieve_response_code($request) != 200) {
                            $this->wpdb->get_results("SELECT `slug` FROM `{$this->tablename}`");

                            if ($this->wpdb->num_rows > 0) {
                                set_transient('mcw-datatime', time(), 60);
                            }
                            return false;
                        }

                        $body = wp_remote_retrieve_body($request);
                        $data = json_decode($body);
    
                        if (!empty($data)) {
            
                            if ($data->status->error_code == 0) {
            
                                $this->wpdb->query("TRUNCATE `{$this->tablename}`");
            
                                $btc_price = $data->data[0]->quote->USD->price;
            
                                $values = [];
            
                                foreach($data->data as $coin) {
                                    if ($coin->cmc_rank !== null) {
                                        $coin->price_btc = $coin->quote->USD->price / $btc_price;
                                        $coin->image = 'https://s2.coinmarketcap.com/static/img/coins/64x64/' . $coin->id . '.png';
                                        $values[] = array($coin->name, strtoupper($coin->symbol), $coin->slug, $coin->image, $coin->cmc_rank, floatval($coin->quote->USD->price), floatval($coin->price_btc), floatval($coin->quote->USD->volume_24h), floatval($coin->quote->USD->market_cap), 0.00, 0.00, floatval($coin->circulating_supply), floatval($coin->max_supply), 0.00, strtotime('now'), 0.00, floatval($coin->quote->USD->percent_change_1h), floatval($coin->quote->USD->percent_change_24h), floatval($coin->quote->USD->percent_change_7d), null, gmdate("Y-m-d H:i:s"));
                                    }
                                }
            
                                $values = array_chunk($values, 100, true);
            
                                foreach($values as $chunk) {
                                    $placeholder = "(%s, %s, %s, %s, %d, %0.14f, %0.8f, %0.2f, %0.2f, %0.10f, %0.10f, %0.2f, %0.2f, %0.10f, %d, %0.10f, %0.2f, %0.2f, %0.2f, %0.2f, %s)";
                                    $query = "INSERT IGNORE INTO `{$this->tablename}` (`name`, `symbol`, `slug`, `img`, `rank`, `price_usd`, `price_btc`, `volume_usd_24h`, `market_cap_usd`, `high_24h`, `low_24h`, `available_supply`, `total_supply`, `ath`, `ath_date`, `price_change_24h`, `percent_change_1h`, `percent_change_24h`, `percent_change_7d`, `percent_change_30d`, `weekly_expire`) VALUES ";
                                    $query .= implode(", ", array_fill(0, count($chunk), $placeholder));
                                    $this->wpdb->query($this->wpdb->prepare($query, call_user_func_array('array_merge', $chunk)));
                                }
                                set_transient('mcw-datatime', time());
                            }
        
                        }
    
                        break;
                }

            }

        }

        public function create_post_type() {
            function hide_title() {
                remove_post_type_support('mcw', 'title');
            }
            function create_post_type() {
                $labels = array(
                    'name'                  => _x('Massive Cryptocurrency Widgets', 'Post Type General Name', 'massive-cryptocurrency-widgets'),
                    'singular_name'         => _x('Massive Cryptocurrency Widgets', 'Post Type Singular Name', 'massive-cryptocurrency-widgets'),
                    'menu_name'             => __('Massive Crypto', 'massive-cryptocurrency-widgets'),
                    'name_admin_bar'        => __('Post Type', 'massive-cryptocurrency-widgets'),
                    'archives'              => __('Widget Archives', 'massive-cryptocurrency-widgets'),
                    'attributes'            => __('Widget Attributes', 'massive-cryptocurrency-widgets'),
                    'parent_item_colon'     => __('Parent Widget:', 'massive-cryptocurrency-widgets'),
                    'all_items'             => __('All Widgets', 'massive-cryptocurrency-widgets'),
                    'add_new_item'          => __('Add New Crypto Widget', 'massive-cryptocurrency-widgets'),
                    'add_new'               => __('Add New', 'massive-cryptocurrency-widgets'),
                    'new_item'              => __('New Widget', 'massive-cryptocurrency-widgets'),
                    'edit_item'             => __('Edit Widget', 'massive-cryptocurrency-widgets'),
                    'view_item'             => __('View Widget', 'massive-cryptocurrency-widgets'),
                    'view_items'            => __('View Widgets', 'massive-cryptocurrency-widgets'),
                    'search_items'          => __('Search Widget', 'massive-cryptocurrency-widgets'),
                    'not_found'             => __('Not found', 'massive-cryptocurrency-widgets'),
                    'not_found_in_trash'    => __('Not found in trash', 'massive-cryptocurrency-widgets'),
                    'featured_image'        => __('Featured Image', 'massive-cryptocurrency-widgets'),
                    'set_featured_image'    => __('Set featured image', 'massive-cryptocurrency-widgets'),
                    'remove_featured_image' => __('Remove featured image', 'massive-cryptocurrency-widgets'),
                    'use_featured_image'    => __('Use as featured image', 'massive-cryptocurrency-widgets'),
                    'insert_into_item'      => __('Insert into widget', 'massive-cryptocurrency-widgets'),
                    'uploaded_to_this_item' => __('Uploaded to this widget', 'massive-cryptocurrency-widgets'),
                    'items_list'            => __('Widgets list', 'massive-cryptocurrency-widgets'),
                    'items_list_navigation' => __('Widgets list navigation', 'massive-cryptocurrency-widgets'),
                    'filter_items_list'     => __('Filter widgets list', 'massive-cryptocurrency-widgets'),
                );
                $args = array(
                    'label'                 => __('Massive Cryptocurrency Widgets', 'massive-cryptocurrency-widgets'),
                    'description'           => __('Post Type Description', 'massive-cryptocurrency-widgets'),
                    'labels'                => $labels,
                    'supports'              => array('title'),
                    'taxonomies'            => array(''),
                    'hierarchical'          => false,
                    'public' 				=> false,
                    'show_ui'               => true,
                    'show_in_nav_menus' 	=> false,
                    'menu_position'         => 5,
                    'show_in_admin_bar'     => true,
                    'show_in_nav_menus'     => true,
                    'can_export'            => true,
                    'has_archive' 			=> false,
                    'rewrite' 				=> false,
                    'exclude_from_search'   => true,
                    'publicly_queryable'    => false,
                    'query_var'				=> false,
                    'menu_icon'           	=> 'data:image/svg+xml;base64,'.base64_encode('<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" style="isolation:isolate" viewBox="426.356 267.342 61.288 50.316" width="20" height="20"><path d=" M 468.061 267.342 C 469.884 270.488 471.42 273.219 473.045 275.898 C 473.621 276.847 473.54 277.594 473.004 278.524 C 465.589 291.375 458.206 304.246 450.502 317.658 C 448.583 314.271 446.838 311.195 445.098 308.116 C 444.755 307.508 445.124 307.024 445.406 306.534 C 452.879 293.604 460.353 280.675 468.061 267.342 Z " fill-rule="evenodd" fill="rgb(150,150,150)"/><path d=" M 449.605 267.343 C 451.383 270.402 452.912 273.147 454.566 275.815 C 455.231 276.885 455.038 277.694 454.461 278.684 C 449.145 287.825 443.85 296.98 438.59 306.155 C 437.997 307.191 437.299 307.625 436.097 307.597 C 433.052 307.527 430.005 307.575 426.356 307.575 C 434.199 294.002 441.83 280.798 449.605 267.343 Z " fill-rule="evenodd" fill="rgb(150,150,150)"/><path d=" M 487.644 307.57 C 483.868 307.57 480.736 307.548 477.604 307.584 C 476.633 307.594 475.976 307.266 475.561 306.374 C 475.28 305.771 474.902 305.215 474.569 304.638 C 469.55 295.933 469.55 295.933 475.897 287.272 C 479.756 293.941 483.532 300.464 487.644 307.57 Z " fill-rule="evenodd" fill="rgb(150,150,150)"/></svg>'),
                    'capability_type'       => 'page',
                );
                register_post_type('mcw', $args);
            }
            add_action('init', 'create_post_type');
            add_action('admin_init', 'hide_title');
            add_action('admin_init', array($this, 'display_notices'));
            add_action('admin_menu', array($this, 'register_menu'), 12);
            add_action('add_meta_boxes', array($this, 'meta_boxes'));
            add_filter('manage_mcw_posts_columns', array($this, 'posts_columns'));
            add_action('manage_mcw_posts_custom_column', array($this, 'posts_columns_content'), 10, 2);
            add_action('save_post', array($this, 'save_widget'));
        }

        public function display_notices() {

            if (is_plugin_active('cryptocurrency-widgets-pack/cryptocurrency-widgets-pack.php')) {

                $plugin = 'cryptocurrency-widgets-pack/cryptocurrency-widgets-pack.php';
                $action = 'deactivate';

                if (strpos($plugin,'/') ) {
                    $plugin = str_replace( '\/', '%2F', $plugin );
                }

                $deactivate_url = sprintf(admin_url('plugins.php?action=' . $action . '&plugin=%s&plugin_status=all&paged=1&s'), $plugin);
                $_REQUEST['plugin'] = $plugin;
                $deactivate_url = wp_nonce_url($deactivate_url, $action . '-plugin_' . $plugin);
                
                new Admin_Notice_Display(sprintf(__('<strong>%s</strong>: Please <a href="%s">deactivate</a> Cryptocurrency Widgets Pack plugin as it may not work properly with PRO version', 'massive-cryptocurrency-widgets'), __('Massive Cryptocurrency Widgets', 'massive-cryptocurrency-widgets'), $deactivate_url), array('notice-warning is-dismissible'));
            }


            $update = array('license_action' => '');

            switch ($this->options['config']['license_action']) {

                case 'activate':

                    $queryargs = array(
                        'code' => $this->options['config']['license_key']
                    );

                    $response = $this->updater->request_info($queryargs);

                    if ($response->license_error == 'limit_exceeded') {
                        new Admin_Notice_Display(sprintf(__('<strong>%s</strong>: You have already used this purchase code on another site. Please deactivate license from the site or buy another license.', 'massive-cryptocurrency-widgets'), __('Massive Cryptocurrency Widgets', 'massive-cryptocurrency-widgets')), array('notice-error', 'is-dismissible'));
                    } else if ($response->license == 'false') {
                        new Admin_Notice_Display(sprintf(__('<strong>%s</strong>: Your purchase code is not valid. Please try again.', 'massive-cryptocurrency-widgets'), __('Massive Cryptocurrency Widgets', 'massive-cryptocurrency-widgets')), array('notice-error', 'is-dismissible'));
                    } else {
                        new Admin_Notice_Display(sprintf(__('<strong>%s</strong>: Congratulations! Your copy has been activated.', 'massive-cryptocurrency-widgets'), __('Massive Cryptocurrency Widgets', 'massive-cryptocurrency-widgets')), array('notice-success', 'is-dismissible'));
                    }

                    $update['license'] = $response->license;

                    $this->options['config'] = array_merge($this->options['config'], $update);

                    update_option('mcw_config', $this->options['config']);

                    break;

                case 'deactivate':

                    $queryargs = array(
                        'code' => $this->options['config']['license_key'],
                        'remove' => 'true'
                    );

                    $response = $this->updater->request_info($queryargs);

                    if ($response->license_error == 'site_removed') {

                        new Admin_Notice_Display(sprintf(__('<strong>%s</strong>: Your license has been removed from this site. It can now be used again.', 'massive-cryptocurrency-widgets'), __('Massive Cryptocurrency Widgets', 'massive-cryptocurrency-widgets')), array('notice-info', 'is-dismissible'));

                        $update['license'] = 'false';
                        $update['license_key'] = '';

                    } else {
                        new Admin_Notice_Display(sprintf(__('<strong>%s</strong>: Your purchase code is not valid. Please try again.', 'massive-cryptocurrency-widgets'), __('Massive Cryptocurrency Widgets', 'massive-cryptocurrency-widgets')), array('notice-error', 'is-dismissible'));
                    }

                    $this->options['config'] = array_merge($this->options['config'], $update);

                    update_option('mcw_config', $this->options['config']);

                    break;
            }

            if ((!isset($_REQUEST['page']) || $_REQUEST['page'] !== 'mcw-settings') && ($this->options['config']['license'] != 'regular' && $this->options['config']['license'] != 'extended')) {
                new Admin_Notice_Display(sprintf(__('<strong>%s</strong>: Howdy! Please <a href="%s">activate</a> your copy to receive automatic future updates and support', 'massive-cryptocurrency-widgets'), __('Massive Cryptocurrency Widgets', 'massive-cryptocurrency-widgets'), admin_url('edit.php?post_type=mcw&page=mcw-settings')), array('notice-error', 'is-dismissible'));
            }
        }

        public function register_menu() {
            add_submenu_page('edit.php?post_type=mcw', __('Settings', 'massive-cryptocurrency-widgets'), 'Settings', 'manage_options', 'mcw-settings', array($this, 'settings_page'));
            add_submenu_page('edit.php?post_type=mcw', __('Extensions', 'massive-cryptocurrency-widgets'), 'Extensions', 'manage_options', 'mcw-extensions', array($this, 'extensions_page'));
        }

        public function settings_page() {
            $config = $this->options['config'];
            include_once(MCW_PATH . '/includes/settings.php');
        }

        public function save_settings() {

            $update = array(
                'numformat' => sanitize_text_field($_POST['numformat']),
                'linkto' => sanitize_text_field($_POST['linkto']),
                'link' => sanitize_text_field($_POST['link']),
                'fonts' => isset($_POST['fonts']) ? $_POST['fonts'] : array(),
                'custom_css' => sanitize_textarea_field($_POST['custom_css']),
                'api' => sanitize_text_field($_POST['api']),
                'api_key' => sanitize_text_field($_POST['api_key']),
                'api_interval' => intval($_POST['api_interval']),
                'license' => sanitize_text_field($_POST['license']),
                'license_key' => sanitize_text_field($_POST['license_key']),
                'license_action' => sanitize_text_field($_POST['license_action']),
                'default_currency_format' => isset($_POST['default_currency_format']) ? esc_sql($_POST['default_currency_format']) : array(),
                'currency_format' => isset($_POST['currency_format']) ? esc_sql($_POST['currency_format']) : array()
            );
    
            $config = array_merge($this->options['config'], $update);
            update_option('mcw_config', $config);
            wp_redirect(admin_url('edit.php?post_type=mcw&page=mcw-settings&success=true'));

        }

        public function extensions_page() {
            $extensions = get_transient('mcw_extensions');

            if ($extensions === false) {
                $request = wp_remote_get('https://api.blocksera.com/products?category=wordpress');

                if (is_wp_error($request) || wp_remote_retrieve_response_code($request) != 200) {
                    return false;
                }

                $body = wp_remote_retrieve_body($request);
                $data = json_decode($body);
                $extensions = $data->products;

                if (!empty($extensions)) {
                    set_transient('mcw_extensions', $extensions, 5 * MINUTE_IN_SECONDS);
                }
            }

            include_once(MCW_PATH . '/includes/extensions.php');
        }

        public function posts_columns($columns) {
            $ncolumns = array();
            foreach($columns as $key => $title) {
                if ($key=='date') {
                    $ncolumns['shortcode'] = __('Shortcode', 'massive-cryptocurrency-widgets');
                    $ncolumns['type'] = __('Widget Type', 'massive-cryptocurrency-widgets');
                }
                $ncolumns[$key] = $title;
            }
            return $ncolumns;
        }

        public function posts_columns_content($column, $post_id) {
            switch ($column) {
                case 'type':
                    $type = get_post_meta($post_id, 'type', true);
                    _e(ucfirst($type), 'massive-cryptocurrency-widgets');
                    break;
                case 'shortcode':
                    echo '[mcrypto id="' . $post_id . '"]';
                    break;
            }
        }

        public function meta_boxes() {
            add_meta_box('mcw-editor', __('Crypto Widget Settings', 'massive-cryptocurrency-widgets'), array($this, 'meta_widget_settings'), 'mcw', 'normal', 'high');
            add_meta_box('crypto_widget_shortcode', __('Shortcode', 'massive-cryptocurrency-widgets'), array($this, 'meta_shortcode'), 'mcw', 'side', 'high');
            add_meta_box('crypto_widget_links', __('Coinpress', 'massive-cryptocurrency-widgets'), array($this, 'meta_coinpress'), 'mcw', 'side', 'low');
            add_meta_box('crypto_widget_banner', __('Quick Links', 'massive-cryptocurrency-widgets'), array($this, 'meta_links'), 'mcw', 'side', 'low');
        }

        public function meta_widget_settings($post) {
            wp_nonce_field(plugin_basename(__FILE__), 'mcw_widget_nonce');
            $options = (get_post_status($post->ID) === 'auto-draft') ? $this->options : array_merge($this->options, json_decode($post->post_content, true));
            $options = apply_filters('mcw_get_options', $options);
            require_once(MCW_PATH . 'includes/admin.php');
        }

        public function meta_shortcode($post) {
            echo '<div class="mcw-shortcode"><span class="shortcode-hint">Copied!</span>Paste this shortcode anywhere like page, post or widgets<br><br>';
            echo '<input type="text" id="mcwshortcode" data-clipboard-target="#mcwshortcode" readonly="readonly" class="selectize-input" value="' . esc_attr('[mcrypto id="' . $post->ID . '"]') . '" /></div>';
        }

        public function meta_coinpress($post) {
            echo '<a href="https://coinpress.blocksera.com" target="_blank"><img src="' . MCW_URL . 'assets/admin/img/coinpress.png" style="max-width: 100%" /></a>';
        }

        public function meta_links($post) {
            echo '<div class="mcw-links">';
            echo '<ul>';
            echo '<li><a href="https://codecanyon.net/item/massive-cryptocurrency-widgets/22093978" target="_blank"><i class="micon-star"></i> '. __("Rate us 5 stars", "massive-cryptocurrency-widgets") . '</a></li>';
            echo '<li><a href="https://massivecryptopro.blocksera.com" target="_blank"><i class="micon-world"></i> '. __("Visit homepage", "massive-cryptocurrency-widgets") . '</a></li>';
            echo '<li><a href="https://blocksera.ticksy.com/" target="_blank"><i class="micon-envelope"></i> '. __("Contact support", "massive-cryptocurrency-widgets") . '</a></li>';
            echo '</ul>';
            echo '</div>';
        }

        public function save_widget($post_id) {
            if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
                return;
            }

            if (!isset($_POST['mcw_widget_nonce'])) {
                return;
            }

            if (!wp_verify_nonce($_POST['mcw_widget_nonce'], plugin_basename( __FILE__ ))) {
                return;
            }

            if (!current_user_can('edit_page', $post_id)) {
                return;
            }

            $postcontent = [
                'id' => intval($post_id),
                'type' => sanitize_key($_POST['type']),
                'coins' => isset($_POST['coins']) ? $_POST['coins'] : array(),
                'numcoins' => intval($_POST['numcoins']),
                'theme' => sanitize_key($_POST['theme']),
                'ticker_design' => intval($_POST['ticker_design']),
                'ticker_position' => sanitize_key($_POST['ticker_position']),
                'ticker_speed' => intval($_POST['ticker_speed']),
                'ticker_columns' => isset($_POST['ticker_columns']) ? $_POST['ticker_columns'] : array(),
                'table_style' => sanitize_key($_POST['table_style']),
                'table_length' => intval($_POST['table_length']),
                'table_columns' => isset($_POST['table_columns']) ? $_POST['table_columns'] : $this->options['table_columns'],
                'chart_type' => sanitize_key($_POST['chart_type']),
                'chart_view' => sanitize_key($_POST['chart_view']),
                'chart_theme' => sanitize_key($_POST['chart_theme']),
                'chart_smooth' => sanitize_key($_POST['chart_smooth']),
                'card_design' => intval($_POST['card_design']),
                'label_design' => intval($_POST['label_design']),
                'display_columns' => isset($_POST['display_columns']) ? $_POST['display_columns'] : array(),
                'converter_type' => sanitize_key($_POST['converter_type']),
                'converter_button' => isset($_POST['converter_button']) ? $_POST['converter_button'] : "",
                'box_design' => intval($_POST['box_design']),
                'list_design' => intval($_POST['list_design']),
                'changelly_link' => esc_url_raw($_POST['changelly_link']),
                'changelly_send' => isset($_POST['changelly_send']) ? $_POST['changelly_send'] : array(),
                'changelly_send_all' => isset($_POST['changelly_send_all']) ? sanitize_key($_POST['changelly_send_all']) : "",
                'changelly_receive' => isset($_POST['changelly_receive']) ? $_POST['changelly_receive'] : array(),
                'changelly_receive_all' => isset($_POST['changelly_receive_all']) ? sanitize_key($_POST['changelly_receive_all']) : "",
                'changelly_amount' => floatval($_POST['changelly_amount']),
                'changelly_theme' => sanitize_key($_POST['changelly_theme']),
                'multi_currencies' => isset($_POST['multi_currencies']) ? $_POST['multi_currencies'] : array(),
                'news_feeds' => sanitize_textarea_field($_POST['news_feeds']),
                'news_count' => intval($_POST['news_count']),
                'news_length' => intval($_POST['news_length']),
                'settings' => isset($_POST['settings']) ? $_POST['settings'] : array(),
                'font' => sanitize_text_field($_POST['font']),
                'price_format' => intval($_POST['price_format']),
                'currency' => strtoupper(sanitize_key($_POST['currency'])),
                'currency2' => strtoupper(sanitize_key($_POST['currency2'])),
                'currency3' => strtoupper(sanitize_key($_POST['currency3'])),
                'text_color' => $_POST['text_color'],
                'background_color' => $_POST['background_color'],
                'chart_color' => sanitize_hex_color($_POST['chart_color']),
                'real_time' =>  sanitize_key($_POST['real_time'])
            ];

            remove_action('save_post', array($this, 'save_widget'));

            $post = array(
                'ID' => $post_id,
                'post_content' => wp_json_encode($postcontent),
                'post_mime_type' => 'application/json',
            );

            update_post_meta($post_id, 'type', $postcontent['type']);
            update_post_meta($post_id, 'ticker_position', $postcontent['ticker_position']);
            wp_update_post($post);

            add_action('save_post', array($this, 'save_widget'));
        }
        public function init() {

            add_shortcode('mcrypto', array($this, 'shortcode'));

            add_action('admin_enqueue_scripts', array($this, 'admin_scripts'));
            add_action('wp_enqueue_scripts', array($this, 'frontend_scripts'), 99999);
            add_action('wp_footer', array($this, 'ticker_sticky'));
            add_action('wp_ajax_mcw_table', array($this, 'ajax_tables'));
            add_action('wp_ajax_mcw_clear_cache', array($this, 'clear_cache'));
            add_action('wp_ajax_nopriv_mcw_table', array($this, 'ajax_tables'));
            add_action('admin_post_mcw_save_settings', array($this, 'save_settings'));

            load_plugin_textdomain('massive-cryptocurrency-widgets', false, dirname(plugin_basename(__FILE__)) . '/languages' );

            add_filter('block_get_coinlinks', array($this, 'get_coinlinks'), 10, 2);
            add_filter('mcw_chart_color', array($this, 'change_chart_color'), 10, 3);
            add_filter('mcw_coin_img', array($this, 'change_coin_imgurl'), 10, 2);
            add_filter('plugin_row_meta', array($this, 'insert_plugin_row_meta'), 10, 2);

            if (!is_plugin_active('coinpress/coinmarketcap.php')) {
                add_action('mcw_fetch_coins', array($this, 'fetch_coins'), 10, 1);
            }

            require_once MCW_PATH . 'includes/upgrade.php';
        }
            
        public function insert_plugin_row_meta($links, $file) {
            if (plugin_basename(__FILE__) == $file) {
                // docs
                $links[] = sprintf('<a href="https://docs.blocksera.com/massive-cryptocurrency-widgets?utm_source=wp&utm_medium=admin" target="_blank">' . __('Docs', 'massive-cryptocurrency-widgets') . '</a>');
            }

            return $links;
        }

        public function change_coin_imgurl($imgurl, $type) {
            if ($type === 'table' || $type == 'card') {
                $replace = 'small';
            } else {
                $replace = 'thumb';
            }
            return str_replace('large/', $replace . '/', $imgurl);
        }

        public function change_chart_color($color, $options, $coin) {
            if ($color){
                $hex = str_replace('#', '', $color);
                list($red, $green, $blue) = sscanf($hex, "%02x%02x%02x");
                return $red . ',' . $green . ',' . $blue;
            } else if (isset($coin->percent_change_24h)) {
                return (($coin->percent_change_24h > 0) ? '10,207,151' : '239,71,58');
            }
            return '40,97,245';
        }

        public function admin_scripts() {

            $screen = get_current_screen();

            if ($screen->post_type === 'mcw') {
                wp_enqueue_code_editor( array('type' => 'text/css'));
                wp_enqueue_style('mcw-crypto-select', MCW_URL . 'assets/public/css/selectize.custom.css', array(), MCW_VERSION);
                wp_enqueue_style('mcw-editor', MCW_URL . 'assets/admin/css/style.css', array(), MCW_VERSION);
                wp_enqueue_script('mcw-crypto-select', MCW_URL . 'assets/public/js/selectize.min.js', array('jquery-ui-sortable'), '0.12.4', true);
                wp_enqueue_script('mcwa-vendor', MCW_URL . 'assets/admin/js/vendor.min.js', array('jquery'), MCW_VERSION, true);
                wp_enqueue_script('mcwa-crypto-common', MCW_URL . 'assets/admin/js/common.js', array('mcwa-vendor'), MCW_VERSION, true);
            }

            if ($screen->post_type === 'mcw' && $screen->base === 'post') {
                $this->frontend_scripts();
            }

        }

        public function frontend_scripts() {

            if (count($this->options['config']['fonts']) > 0) {
                wp_enqueue_style('mcw-google-fonts', 'https://fonts.googleapis.com/css?family=' . implode('|', $this->options['config']['fonts']));
            }

            wp_enqueue_style('mcw-crypto', MCW_URL . 'assets/public/css/style.css', array(), MCW_VERSION);
            wp_enqueue_style('mcw-crypto-select', MCW_URL . 'assets/public/css/selectize.custom.css', array(), MCW_VERSION);
            wp_enqueue_style('mcw-crypto-datatable', MCW_URL . 'assets/public/css/jquery.dataTables.min.css', array(), '1.10.16');
            wp_register_script('mcw-crypto-common', MCW_URL . 'assets/public/js/common.min.js', array('jquery'), MCW_VERSION, true);
            wp_enqueue_script('mcw-crypto-socket-io', MCW_URL . 'assets/public/js/socket.io.js', array(), '2.1.0', true);
            wp_enqueue_script('mcw-crypto-es5',	'https://cdnjs.cloudflare.com/ajax/libs/es5-shim/2.0.8/es5-shim.min.js', array(), '2.0.8', true);
            wp_script_add_data('mcw-crypto-es5', 'conditional', 'lt IE 9' );
            wp_enqueue_script('mcw-crypto-select', MCW_URL . 'assets/public/js/selectize.min.js',array('jquery'), '0.12.4',true);

            $atts = array(
                'url' => MCW_URL,
                'ajax_url' => admin_url('admin-ajax.php'),
                'currency_format' => array_column($this->options['config']['currency_format'], null, 'iso'),
                'default_currency_format' => $this->options['config']['default_currency_format'],
                'text' => array(
                    'previous' => __('Previous', 'massive-cryptocurrency-widgets'),
                    'next' => __('Next', 'massive-cryptocurrency-widgets'),
                    'lengthmenu' => sprintf(__('Coins per page: %s', 'massive-cryptocurrency-widgets'), '_MENU_')
                ),
                'api' => $this->config['api']
            );

            wp_localize_script('mcw-crypto-common', 'mcw', $atts);
            wp_enqueue_script('mcw-crypto-common');
            
        }

        public function activate() {
            global $wpdb;
            $charset_collate = $wpdb->get_charset_collate();
            $table_name = $wpdb->base_prefix . "mcw_coins";

            $sql = "CREATE TABLE IF NOT EXISTS `{$table_name}` (
                `id` mediumint(9) NOT NULL AUTO_INCREMENT,
                `name` varchar(100) NOT NULL,
                `symbol` varchar(10) NOT NULL,
                `slug` varchar(100) NOT NULL,
                `img` varchar(200) NOT NULL,
                `rank` int(5) NOT NULL,
                `price_usd` decimal(24,14) NOT NULL,
                `price_btc` decimal(10,8) NOT NULL,
                `volume_usd_24h` decimal(22,2) NOT NULL,
                `market_cap_usd` decimal(22,2) NOT NULL,
                `high_24h` decimal(20,10) NOT NULL,
                `low_24h` decimal(20,10) NOT NULL,
                `available_supply` decimal(22,2) NOT NULL,
                `total_supply` decimal(22,2) NOT NULL,
                `ath` decimal(20,10) NOT NULL,
                `ath_date` int(11) UNSIGNED NOT NULL,
                `price_change_24h` decimal(20,10) NOT NULL,
                `percent_change_1h` decimal(7,2) NOT NULL,
                `percent_change_24h` decimal(7,2) NOT NULL,
                `percent_change_7d` decimal(7,2) NOT NULL,
                `percent_change_30d` decimal(7,2) NOT NULL,
                `weekly` longtext NOT NULL,
                `weekly_expire` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                `keywords` varchar(255) NOT NULL,
                `custom` text NULL,
                UNIQUE KEY `id` (`id`),
                UNIQUE (`slug`)
            ) $charset_collate;";

            require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
            dbDelta($sql);

            add_option('mcw_config', $this->config);
        }

        public function deactivate() {
            delete_transient('mcw-datatime');
            delete_transient('mcw-currencies');
            delete_transient('mcw-data-time');
        }

        public function get_currencies() {
            $exrates = get_transient('mcw-currencies');

            if ($exrates === false) {

                $request = wp_remote_get('https://api.blocksera.com/v1/exrates');

                if (is_wp_error($request) || wp_remote_retrieve_response_code($request) != 200) {
                    return false;
                }

                $body = wp_remote_retrieve_body($request);
                $exrates = apply_filters('block_exrates', json_decode($body));

                if (!empty($exrates)) {
                    set_transient('mcw-currencies', $exrates, DAY_IN_SECONDS);
                }

            }

            return $exrates;
        }

        public function getexrate($currency) {
            switch ($currency) {
                case 'USD':
                    return 1;
                case 'BTC':
                    $coinprice = $this->wpdb->get_var("SELECT `price_usd` FROM `$this->tablename` WHERE `symbol` = '$currency'");
                    return floatval($coinprice);
                default:
                    $currencies = $this->get_currencies();
                    return $currencies->{$currency};
            }
        }

        public function mcw_coinsyms() {
            do_action('mcw_fetch_coins', $this->options['config']);
            $mcw_data = $this->wpdb->get_results("SELECT `name`, `symbol`, `slug` FROM `{$this->tablename}` ORDER BY `rank` ASC");

            $mcw_coinsyms = array();

            foreach($mcw_data as $mcw_each_data) {
                $mcw_coinsyms[$mcw_each_data->slug] = array('name' => $mcw_each_data->name, 'symbol' => $mcw_each_data->symbol);
            }

            return $mcw_coinsyms;
        }
        
        public function shortcode($atts) {
            global $coinpress, $wp;

            $shortcode = new MassiveCrypto_Shortcodes();
            $shortcode->config = $this->options['config'];
            $shortcode->changelly = array_merge($this->changelly['fiat'], $this->changelly['crypto']);

            $atts = shortcode_atts(array(
                'id' => '',
                'coin' => false,
                'currency' => 'USD',
                'info' => 'price',
                'realtime' => 'on',
                'format' => 'number',
                'coinpress' => 'false',
                'multiply' => 1
            ), $atts, 'mcrypto');

            $post = get_post($atts['id']);

            if(($post->post_status != 'publish') && (!is_admin())) {
                return;
            }

            $options = ($post->post_status === 'auto-draft' || $post->post_type !== 'mcw') ? $this->options : array_merge($this->options, json_decode($post->post_content, true));
            $options = apply_filters('mcw_get_options', $options);

            $options['mcw_currencies'] = $this->get_currencies();

            if ($atts['coinpress'] == 'true' && !empty($coinpress->options['config']['link'])) {

                $current_url = add_query_arg(array(), $wp->request);
                $slug_format = $coinpress->options['config']['link'];
                $asset_link = str_replace(site_url(), '', $slug_format);
                $asset_link = str_replace('/', '\/', ltrim($asset_link, '/'));
                $regex = '/^' . str_replace('[symbol]', '([a-zA-Z0-9-\.=\^\$]+)', $asset_link) . '?$/';

                preg_match($regex, $current_url, $matches);
                if (isset($matches[1])) {
                    if($options['type'] == 'box' || $options['type'] == 'chart'){
                        $options['coins']['0'] = $matches[1];
                    } else {
                        array_unshift($options['coins'], $matches[1]);
                    }
                }

            }

            if ($atts['coin']) {
                $options['coins'] = array($atts['coin']);
            }
            
            if ($atts['id'] === '') {

                $options['type'] = 'text'; $options['atts'] = $atts;

            } else if (empty($options['coins']) && in_array($options['type'], ['chart', 'card', 'label', 'box'])) {

                return 'No coins selected';

            } else if (sizeof($options['coins']) == 0 && intval($options['numcoins']) == 0 && !in_array($options['type'], ['changelly', 'news'])) {

                return 'No coins selected';

            }

            wp_register_style('mcw-custom', false);
            wp_enqueue_style('mcw-custom');
            wp_add_inline_style("mcw-custom", $this->options['config']['custom_css']);

            do_action('mcw_fetch_coins', $this->options['config']);

            if (count($options['coins']) > 0) {
                $wquery = "WHERE `slug` IN ('" . implode("', '", $options['coins']) . "') ORDER BY FIELD (`slug`, '" . implode("', '", $options['coins']) . "')";
            } else {
                $wquery = "ORDER BY `rank` LIMIT " . intval($options['numcoins']);
            }

            switch ($options['type']) {

                case 'ticker':

                    if ($options['ticker_design'] == 2) {
                        $options['weekly'] = $this->get_weekly($options['coins'], $options['numcoins']);
                    }

                    $options['data'] = $this->wpdb->get_results("SELECT `name`, `symbol`, `slug`, `img`, `price_usd`, `percent_change_24h` FROM `{$this->tablename}` " . $wquery);
                    $options['links'] = apply_filters('block_get_coinlinks', array(), $options);
                    return $shortcode->ticker_shortcode($atts['id'], $options);
                    break;

                case 'table':

                    $options['links'] = [];

                    if ($options['numcoins'] == 2000) {
                        $coins = $this->mcw_coinsyms();
                        $options['numcoins'] = sizeof($coins);
                    }

                    if (sizeof($options['coins']) > 0) {
                        $coins = array_slice($options['coins'], 0, intval($options['table_length']));
                        $wquery = "WHERE `slug` IN ('" . implode("', '", $coins) . "') ORDER BY FIELD(`slug`, '" . implode("', '", $coins) . "')";
                    } else {
                        $wquery = "ORDER BY `rank` LIMIT " . min($options['numcoins'], $options['table_length']);
                    }

                    $weeklycoins = [];

                    return $shortcode->table_shortcode($atts['id'], $options, $post->post_title);
                    break;

                case 'chart':

                    $options['data'] = $this->wpdb->get_results("SELECT `symbol`, `slug` FROM `{$this->tablename}` " . $wquery);
                    return $shortcode->chart_shortcode($atts['id'], $options);
                    break;

                case 'converter':

                    $options['mcw_cryptocurrencies'] = $this->wpdb->get_results("SELECT `symbol`, `slug`, `price_usd` FROM `{$this->tablename}` ORDER BY `rank` ASC");

                    $options['data'] = $this->wpdb->get_results("SELECT `symbol`, `slug`, `price_usd` FROM `{$this->tablename}` " . $wquery);
                    return $shortcode->converter_shortcode($atts['id'], $options);
                    break;
                
                case 'card':

                    $options['weekly'] = $this->get_weekly($options['coins']);

                    $options['data'] = $this->wpdb->get_results("SELECT `name`, `symbol`, `slug`, `img`, `price_usd`, `market_cap_usd`, `percent_change_1h`, `percent_change_24h` FROM `{$this->tablename}` " . $wquery);
                    $options['links'] = apply_filters('block_get_coinlinks', array(), $options);
                    return $shortcode->card_shortcode($atts['id'], $options);
                    break;

                case 'label':

                    $options['data'] = $this->wpdb->get_results("SELECT `name`, `symbol`, `slug`, `img`, `price_usd`, `percent_change_24h` FROM `{$this->tablename}` " . $wquery);
                    $options['links'] = apply_filters('block_get_coinlinks', array(), $options);
                    return $shortcode->label_shortcode($atts['id'], $options);
                    break;

                case 'list':

                    $options['weekly'] = $this->get_weekly($options['coins'], $options['numcoins']);

                    $options['data'] = $this->wpdb->get_results("SELECT `name`, `slug`, `symbol`, `img`, `price_usd`, `percent_change_24h` FROM `{$this->tablename}` " . $wquery);
                    $options['links'] = apply_filters('block_get_coinlinks', array(), $options);
                    return $shortcode->list_shortcode($atts['id'], $options);
                    break;

                case 'box':

                    $options['weekly'] = $this->get_weekly($options['coins']);

                    if ($options['box_design'] == 2) {
                        $options['data'] = $this->wpdb->get_results("SELECT `symbol`, `slug`, `price_usd`, `percent_change_24h` FROM `{$this->tablename}` ORDER BY FIELD (`slug`, '" . implode("', '", $options['coins']) . "') DESC");
                    } else {
                        $options['data'] = $this->wpdb->get_results("SELECT * FROM `{$this->tablename}` " . $wquery);
                    }

                    $options['links'] = apply_filters('block_get_coinlinks', array(), $options);
                    return $shortcode->box_shortcode($atts['id'], $options);
                    break;

                case 'text':

                    $fields = [
                        'rank' => 'id',
                        'price' => 'price_usd',
                        'pricebtc' => 'price_btc',
                        'volume' => 'volume_usd_24h',
                        'supply' => 'available_supply',
                        'marketcap' => 'market_cap_usd',
                        'change' => 'percent_change_24h'
                    ];

                    $field = isset($fields[$atts['info']]) ? $fields[$atts['info']] : 'slug';
                    $options['data'] = $this->wpdb->get_row($this->wpdb->prepare("SELECT `name`, `symbol`, `{$field}` FROM `{$this->tablename}` WHERE `slug` = %s OR `symbol` = %s", $atts['coin'], $atts['coin']));
                    return $shortcode->text_shortcode($atts['id'], $options);
                    break;

                case 'changelly':
                    return $shortcode->changelly_shortcode($atts['id'], $options);
                    break;

                case 'multicurrency':
                    $options['data'] = $this->wpdb->get_results("SELECT `name`, `symbol`, `slug`, `img`, `price_usd`, `percent_change_24h` FROM `{$this->tablename}` " . $wquery);
                    $options['links'] = apply_filters('block_get_coinlinks', array(), $options);
                    return $shortcode->multicurrency_shortcode($atts['id'], $options);
                    break;
                    
                case 'news':
                    return $shortcode->news_shortcode($atts['id'], $options);
                    break;
            }
        }

        public function ticker_sticky() {
            $posts = get_posts(array('post_type' => 'mcw', 'posts_per_page' => 1, 'meta_query' => array(array('key' => 'type', 'value' => 'ticker'), array('key' => 'ticker_position', 'value' => array('header', 'footer'), 'compare' => 'IN'))));
            
            if (sizeof($posts) > 0) {
                echo apply_filters('mcw_show_ticker', do_shortcode('[mcrypto id="' . $posts[0]->ID . '"]'));
            }
        }

        public function get_coinlinks($links, $options) {
            
            if (!in_array('linkto', $options['settings'])) {
                return $links;
            }

            switch($this->options['config']['linkto']) {
                case 'custom':
                    // init empty array to avoid links from coinpress
                    $links = array();

                    $coin_posts = get_posts(array('posts_per_page' => -1, 'post_type' => 'any', 'meta_key' => 'mcw-coin'));

                    foreach($coin_posts as $coin_post) {
                        $meta_value = get_post_meta($coin_post->ID, 'mcw-coin', true);
                        $links[$meta_value] = get_permalink($coin_post->ID);
                    }

                    if(!empty($this->options['config']['link'])){
                        foreach($options['data'] as $coin) {
                            $link = str_replace('[symbol]', strtolower($coin->slug), $this->options['config']['link']);
                            if(!isset($links[$coin->slug]) && !isset($links[$coin->symbol])){
                                $links[$coin->symbol] = $links[$coin->slug] = (parse_url($link, PHP_URL_SCHEME) != '') ? $link : get_site_url(null, $link);
                            }
                        }
                    }

                    break;

                case 'coinpress':

                    if (is_plugin_active('coinpress/coinmarketcap.php')) {

                        $coinmc_config = get_option('coinmc_config', array());

                        if (!empty($coinmc_config['link'])) {
                            foreach($options['data'] as $coin) {
                                $link = str_replace('[symbol]', strtolower($coin->slug), $coinmc_config['link']);
                                $links[$coin->symbol] = $links[$coin->slug] = (parse_url($link, PHP_URL_SCHEME) != '') ? $link : apply_filters('wpml_permalink', get_site_url(null, $link));
                            }
                        }

                    }
                    break;
            }

            return $links;
        }

        public function get_weekly($coins, $numcoins = 0) {

            $wquery = (count($coins) > 0) ? "WHERE `slug` IN ('" . implode("', '", $coins) . "')" : "ORDER BY `rank` LIMIT " . $numcoins;

            $results = $this->wpdb->get_results("SELECT `slug`, `symbol`, `weekly`, `weekly_expire` FROM `{$this->tablename}` " . $wquery);
            
            $output = []; $expiredcoins = [];

            foreach($results as $res) {
                $output[$res->slug] = explode(',', $res->weekly);
                
                //create list of coins to request and update to sql
                $dateFromDatabase = strtotime($res->weekly_expire);
                $dateTwelveHoursAgo = strtotime("-1 hours");
                
                if (($dateFromDatabase < $dateTwelveHoursAgo) || ($res->weekly == '')){
                    array_push($expiredcoins,$res->slug);
                }
            }

            if (count($expiredcoins) > 0) {
                $request = wp_remote_get('https://api.blocksera.com/v1/tickers/weekly?coins='.strtolower(implode(',',$expiredcoins)).'&limit=168');

                if (is_wp_error($request) || wp_remote_retrieve_response_code($request) != 200) {
                    foreach ($expiredcoins as $coin) {
                        $weekresult = $this->wpdb->query("UPDATE `{$this->tablename}` SET `weekly_expire` = '" . gmdate("Y-m-d H:i:s", strtotime("-55 minutes")) . "' WHERE `slug` = '{$coin}'");
                    }
                }

                $body = wp_remote_retrieve_body($request);
                $data = json_decode($body);
            
                if (!empty($data)) {
                    foreach($data as $key => $value) {
                        $weekquery  = "UPDATE `{$this->tablename}` SET `weekly` = '" . implode(',', $value) . "', `weekly_expire` = '" . gmdate("Y-m-d H:i:s") . "' WHERE `slug` = '{$key}'";
                        $weekresult = $this->wpdb->query($weekquery);
                        $output[$key] = $value;
                    }
                }
                
            }
            
            return $output;
        }

        public function clear_cache() {
            $this->wpdb->query("DROP TABLE IF EXISTS `{$this->tablename}`");
            delete_transient('mcw-datatime');
            delete_transient('mcw-currencies');
            $this->activate();
            wp_redirect(admin_url('edit.php?post_type=mcw&page=mcw-settings&success=true'));
            exit();
        }

        public function ajax_tables() {

            $shortcode = new MassiveCrypto_Shortcodes();
            $shortcode->config = $this->options['config'];

            $table = [];

            $table['id'] = intval($_POST['mcw_id']);

            $post = get_post($table['id']);

            $options = ($post->post_status === 'auto-draft') ? $this->options : array_merge($this->options, json_decode($post->post_content, true));
            $options = apply_filters('mcw_get_options', $options);

            $numcoins = (sizeof($options['coins']) > 0) ? sizeof($options['coins']) : $options['numcoins'];

            if ($numcoins == 2000) {
                $coins = $this->mcw_coinsyms();
                $numcoins = sizeof($coins);
            }

            $table['order'] = array(
                'column' => isset($_POST['order'][0]['column']) ? sanitize_text_field($_POST['columns'][intval($_POST['order'][0]['column'])]['name']) : false,
                'dir' => isset($_POST['order'][0]['dir']) ? sanitize_text_field($_POST['order'][0]['dir']) : 'ASC'
            );

            $table['order']['column'] = in_array($table['order']['column'], array(
                'name',
                'symbol',
                'slug',
                'rank',
                'price_usd',
                'price_btc',
                'volume_usd_24h',
                'market_cap_usd',
                'high_24h',
                'low_24h',
                'available_supply',
                'total_supply',
                'ath',
                'ath_date',
                'price_change_24h',
                'percent_change_1h',
                'percent_change_24h',
                'percent_change_7d',
                'percent_change_30d'
            )) ? $table['order']['column'] : false;

            $table['order']['dir'] = 'DESC' === strtoupper($table['order']['dir']) ? 'DESC' : 'ASC';

            $table['start'] = intval($_POST['start']);
            $table['length'] = intval($_POST['length']);

            if (in_array('weekly', $options['table_columns'])) {
                $options['table_columns'][] = 'percent_change_24h';
            }

            $dbcolumns = array_diff($options['table_columns'], array('no', 'last24h', 'weekly'));

            if (sizeof($options['coins']) > 0) {
                $coins = array_slice($options['coins'], $table['start'], $table['length']);
                $order = ($table['order']['column'] && $table['order']['column'] !== 'slug') ? sanitize_sql_orderby("{$table['order']['column']} {$table['order']['dir']}") : "FIELD(`slug`, '" . implode("', '", $coins) . "')";
                $query = "SELECT `img`, `slug`, `symbol`, `" . implode("`, `", $dbcolumns) . "` FROM `{$this->tablename}` WHERE `slug` IN ('" . implode("', '", $coins) . "') ORDER BY {$order}";
            } else {
                $ordercolumn = $table['order']['column'] ? $table['order']['column'] : 'rank';
                $order = sanitize_sql_orderby("{$ordercolumn} {$table['order']['dir']}");
                $query = $this->wpdb->prepare("SELECT `img`, `slug`, `symbol`, `" . implode("`, `", $dbcolumns) . "` FROM `{$this->tablename}` WHERE `rank` <= {$options['numcoins']} ORDER BY {$order} LIMIT %d, %d", [$table['start'], $table['length']]);
            }

            do_action('mcw_fetch_coins', $this->options['config']);

            $options['data'] = $this->wpdb->get_results($query);
            $options['mcw_currencies'] = $this->get_currencies();

            $weeklycoins = [];

            if (in_array('weekly', $options['table_columns'])) {
                $options['coins'] = [];

                foreach($options['data'] as $coin) {
                    array_push($weeklycoins, $coin->slug);
                }

                $options['weekly'] = $this->get_weekly($weeklycoins);
            }

            $arr = [];
            $options['links'] = apply_filters('block_get_coinlinks', array(), $options);

            $data = [];
            $fiatrate = $this->getexrate($options['currency']);
            $shortprice = ($options['price_format'] == 1) ? true : false;

            foreach($options['data'] as $coin) {

                $temp = [];
                
                foreach ($options['table_columns'] as $column) {
                
                    switch ($column) {

                        case 'rank':
                            $temp['rank'] =  '<td class="text-left">' . $coin->rank . '</td>';
                            break;

                        case 'name':
                            $html =  '<td class="text-left">';
                            $html .=  '<div class="coin">';
                            if (in_array('logo', $options['ticker_columns'])) {
                                $html .=  '<div class="coin-image"><img src="' . apply_filters('mcw_coin_img', $coin->img, $options['type']) . '" style="max-height: 35px;" alt="'. $coin->slug .'"></div>';
                            }
                            if (isset($options['links'][$coin->slug])) {
                                $html .=  '<a href="' . $options['links'][$coin->slug] . '" class="coin-title"><div class="coin-name">' . $coin->name . '</div><div class="coin-symbol">' . $coin->symbol . '</div></a>';
                            } else if (isset($options['links'][$coin->symbol])) {
                                $html .= '<a href="' . $options['links'][$coin->symbol] . '" class="coin-title"><div class="coin-name">' . $coin->name . '</div><div class="coin-symbol">' . $coin->symbol . '</div></a>';
                            } else {
                                $html .= '<div class="coin-title"><div class="coin-name">' . $coin->name . '</div><div class="coin-symbol">' . $coin->symbol . '</div></div>';
                            }
                            $html .= '</div></td>';
                            $temp['name'] = $html;
                            break;

                        case 'symbol':
                            $temp['symbol'] =  '<td>' . $coin->symbol . '</td>';
                            break;

                        case 'price_usd':
                            $temp['price_usd'] =  '<td><span data-price="' . $coin->price_usd . '" data-rate="' . $fiatrate . '" data-currency="' . $options['currency'] . '" data-live-price="' . $shortcode->slugify($coin->name) . '">' . $shortcode->price_format($coin->price_usd * $fiatrate, $options['currency']) . '</span></td>';
                            break;

                        case 'price_btc':
                            $temp['price_btc'] =  '<td>Ƀ ' . $coin->price_btc . '</td>';
                            break;

                        case 'market_cap_usd':
                            $temp['market_cap_usd'] =  '<td>' . $shortcode->price_format($coin->market_cap_usd * $fiatrate, $options['currency'], $shortprice, 0) . '</td>';
                            break;

                        case 'volume_usd_24h':
                            $temp['volume_usd_24h'] =  '<td>' . $shortcode->price_format($coin->volume_usd_24h * $fiatrate, $options['currency'], $shortprice, 0) . '</td>';
                            break;

                        case 'available_supply':
                            $temp['available_supply'] =  '<td>' . $shortcode->number_format($coin->available_supply, $options['currency'], $shortprice, 0) . '</td>';
                            break;

                        case 'percent_change_24h':
                            $html =  '<td>';
                            $html .=  '<span data-table-change="' . $coin->symbol . '" class="' . (($coin->percent_change_24h >= 0) ? 'up' : 'down') . '">' . $shortcode->number_format($coin->percent_change_24h, $options['currency'], false, 2) . '%</span>';
                            $html .=  '</td>';
                            $temp['percent_change_24h'] = $html;
                            break;

                        case 'weekly':
                            $temp['weekly'] = '<td><canvas width="135" height="40" data-rate="' . $fiatrate . '" data-currency="'. $options['currency'] .'" data-color="' . apply_filters('mcw_chart_color', $options['chart_color'], $options, $coin) . '" data-gradient="50" data-border="2" data-points="' . implode(',', array_slice($options['weekly'][$coin->slug], -24)) . '"></canvas></td>';
                            break;
                    }
                }

                $data[] = $temp;
            }

            $output = array(
                'recordsTotal' => $numcoins,
                'recordsFiltered' => $numcoins,
                'draw'=> $_POST['draw'],
                'data'=> $data,                
            );
            
            wp_send_json($output);
            
        }

        public function refreshLicenseFromPluginInfo($pluginInfo, $result) {
            if (!is_wp_error($result) && isset($result['response']['code'])&& ($result['response']['code'] == 200) && !empty($result['body'])) {
                $apiResponse = json_decode($result['body']);
                if (is_object($apiResponse) && isset($apiResponse->license) && $apiResponse->license === 'false' && $apiResponse->license !== $this->options['config']['license']) {
                    $update = array('license' => 'false', 'license_key' => '');
                    $this->options['config'] = array_merge($this->options['config'], $update);
                    update_option('mcw_config', $this->options['config']);
                }
            }
            return $pluginInfo;
        }

    }
}

$massivecrypto = new MassiveCrypto();