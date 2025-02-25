<?php

global $wp_rewrite;
$wp_rewrite = new wp_rewrite;

$mcw_version = get_transient('mcw_version');

if ($mcw_version && version_compare($mcw_version, '2.0.0', '<')) {

    $mcw_posts = get_posts(array(
        'post_type' => 'mcw',
        'posts_per_page' => -1,
        'meta_key' => 'crypto_ticker',
        'post_status' => array('publish', 'pending', 'draft', 'auto-draft', 'future', 'private', 'inherit', 'trash')
    ));

    foreach($mcw_posts as $post) {
        $meta = get_post_meta($post->ID);

        foreach ($meta as $k => $v) {
            $meta[$k] = array_shift($v);
        }

        $converter_types = array('crypto-to-fiat', 'fiat-to-crypto', 'crypto-to-crypto', 'fiat-to-fiat');
        $carddesigns = unserialize($meta['crypto_card_designs']);
        $labeldesigns = unserialize($meta['crypto_label_designs']);
        $coins = (unserialize($meta['crypto_ticker_coin'])) ? unserialize($meta['crypto_ticker_coin']) : array();
        $converterbutton = (unserialize($meta['crypto_converter_columns'])) ? unserialize($meta['crypto_converter_columns']) : array();
        
        $postcontent = array(
            'type' => $meta['crypto_ticker'],
            'coins' => $coins,
            'numcoins' => ($meta['crypto_bunch_select'] == 'all') ? 2000 : intval($meta['crypto_bunch_select']),
            'ticker_color' => $meta['crypto_ticker_color'],
            'ticker_position' => $meta['crypto_ticker_position'],
            'ticker_speed' => 100,
            'ticker_columns' => (unserialize($meta['crypto_ticker_columns'])) ? unserialize($meta['crypto_ticker_columns']) : array(),
            'table_style' => $meta['crypto_table_style'],
            'table_length' => $meta['crypto_table_length'],
            'chart_type' => $meta['crypto_chart_type'],
            'chart_view' => $meta['crypto_chart_view'],
            'chart_theme' => $meta['crypto_chart_theme'],
            'chart_smooth' => $meta['crypto_chart_smooth'],
            'card_design' => substr(array_shift($carddesigns), 7),
            'card_color' => $meta['crypto_card_color'],
            'label_design' => substr(array_shift($labeldesigns), 7),
            'label_color' => $meta['crypto_card_color'],
            'display_columns' => (unserialize($meta['crypto_card_columns'])) ? unserialize($meta['crypto_card_columns']) : array(),
            'converter_type' => $converter_types[$meta['crypto_converter'] - 1],
            'converter_button' => in_array('manual', $converterbutton) ? 'on' : '',
            'currency' => $meta['crypto_currency_fiat'],
            'text_color' => $meta['crypto_text_color'],
            'background_color' => $meta['crypto_background_color'],
            'price_format' => ($meta['crypto_price_format'] == 1) ? 3 : (($meta['crypto_price_format'] == 2) ? 1 : 2),
            'real_time' => $meta['crypto_real_time']
        );

        foreach($meta as $k => $v) {
            delete_post_meta($post->ID, $k);
        }

        update_post_meta($post->ID, 'type', $postcontent['type']);
        update_post_meta($post->ID, 'ticker_position', $postcontent['ticker_position']);
        wp_update_post(array(
            'ID' => $post->ID,
            'post_content' => wp_json_encode($postcontent),
            'post_mime_type' => 'application/json',
        ));

    }
}

if ($mcw_version && version_compare($mcw_version, '2.2.5', '<')) {
    $mcw_posts = get_posts(array(
        'post_type' => 'mcw',
        'posts_per_page' => -1,
        'post_status' => array('publish', 'pending', 'draft', 'auto-draft', 'future', 'private', 'inherit', 'trash')
    ));

    foreach($mcw_posts as $post) {
        $postcontent = json_decode($post->post_content, true);
        $postcontent['table_columns'] = $this->options['table_columns'];
        wp_update_post(array(
            'ID' => $post->ID,
            'post_content' => wp_json_encode($postcontent),
        ));
    }
}

if ($mcw_version && version_compare($mcw_version, '2.3.5', '<')) {

    $mcw_posts = get_posts(array(
        'post_type' => 'mcw',
        'posts_per_page' => -1,
        'post_status' => array('publish', 'pending', 'draft', 'auto-draft', 'future', 'private', 'inherit', 'trash')
    ));

    foreach($mcw_posts as $post) {
        $postcontent = json_decode($post->post_content, true);
        if (is_array($postcontent['ticker_columns'])) {
            array_push($postcontent['ticker_columns'], 'logo');
        }
        wp_update_post(array(
            'ID' => $post->ID,
            'post_content' => wp_json_encode($postcontent),
        ));
    }
}

if ($mcw_version && version_compare($mcw_version, '2.4.0', '<')) {
    add_option('mcw_config', $this->config);
}

if ($mcw_version && version_compare($mcw_version, '2.5.0', '<')) {

    $customcss = '';

    $mcw_posts = get_posts(array(
        'post_type' => 'mcw',
        'posts_per_page' => -1,
        'post_status' => array('publish')
    ));

    foreach($mcw_posts as $post) {
        $postcontent = json_decode($post->post_content, true);

        if (in_array($postcontent['type'], array('ticker', 'card', 'label', 'box', 'list'))) {

            $theme = $postcontent[$postcontent['type'] . '_color'];

            switch ($theme) {
                case 'crimson':
                    $theme = 'warning'; break;
                case 'red':
                    $theme = 'danger'; break;
                case 'green':
                    $theme = 'success'; break;
                case 'blue':
                    $theme = 'info'; break;
            }
            
            $postcontent['theme'] = $theme;
            
            wp_update_post(array(
                'ID' => $post->ID,
                'post_content' => wp_json_encode($postcontent),
            ));
        }

        $customcss .= $postcontent['custom_css'];

        if (!empty($postcontent['custom_css'])) {
            $customcss .= "\n";
        }
    }

    $config = get_option('mcw_config');
    $config['custom_css'] = str_replace('rn', "\n", $customcss);
    update_option('mcw_config', $config);
}

if ($mcw_version && version_compare($mcw_version, '3.0.5', '<')) {
    $this->wpdb->get_results("SHOW COLUMNS FROM `{$this->tablename}` LIKE 'keywords'");

    if ($this->wpdb->num_rows == 0) {
        $this->wpdb->query("ALTER TABLE `{$this->tablename}` ADD `keywords` varchar(255) AFTER `weekly_expire`");
    }
}

// Last version which requires table structure change
if ($mcw_version && version_compare($mcw_version, '3.0.2', '<')) {
    $this->wpdb->query("DROP TABLE IF EXISTS `{$this->tablename}`");
    delete_transient('mcw-datatime');
    delete_transient('mcw-currencies');
    $this->activate();
}

if ($mcw_version && version_compare($mcw_version, '2.2.6', '<')) {
    $this->wpdb->query("ALTER TABLE `{$this->tablename}` MODIFY `price_usd` decimal(24,14) NOT NULL");
}

if (version_compare($mcw_version, MCW_VERSION, '<')) {
    set_transient('mcw_version', MCW_VERSION);
}