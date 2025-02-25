<?php

if (!defined('ABSPATH')) {
    exit;
}

if (!class_exists('MassiveCrypto_Shortcodes')) {

    class MassiveCrypto_Shortcodes {

        public $config;

        public $changelly;

        public function number_format($number, $iso, $shorten = false, $decimals = 'auto') {
            $number = abs($number);
            $currencies = array_column($this->config['currency_format'], null, 'iso');

            $format = isset($currencies[$iso]) ? $currencies[$iso] : $this->config['default_currency_format'];

            if ($shorten) {
                $decimals = $format['decimals'];
            } else if ($decimals === 'auto') {
                $decimals = ($number >= 1) ? $format['decimals'] : ($number < 0.000001 ? 14 : 6);
            } else {
                $decimals = intval($decimals);
            }

            $index = 0;
            $suffix = '';
            $suffixes = array("", " K", " M", " B", " T");

            if ($shorten) {
                while ($number > 1000) {
                    $number = $number / 1000;
                    $index++;
                }
                $suffix = $suffixes[$index];
            }

            return number_format($number, $decimals, $format['decimals_sep'], $format['thousands_sep']) . $suffix;
        }

        public function price_format($price, $iso, $shorten = false, $decimals = 'auto') {
            $price = abs($price);
            $currencies = array_column($this->config['currency_format'], null, 'iso');
    
            $format = isset($currencies[$iso]) ? $currencies[$iso] : $this->config['default_currency_format'];
            $price = $this->number_format($price, $iso, $shorten, $decimals);
            $price = (($price < 1 && $price != 0) ? rtrim($price, '0') : $price);

            $out = $format['position'];
            $out = str_replace('{symbol}', '<b class="fiat-symbol">' . $format['symbol'] . '</b>', $out);
            $out = str_replace('{space}', ' ', $out);
            $out = str_replace('{price}', '<span>' . $price . '</span>', $out);
    
            return $out;
        }

        public function percent_change($percent, $symbol, $currency = 'USD') {
            $class = ($percent >= 0) ? 'mcw-up' : 'mcw-down';
            $iconclass = ($percent >= 0) ? 'micon-arrow-up' : 'micon-arrow-down';
            return '<span data-live-change="'.$symbol.'" class="'.$class.'"><span class="'.$iconclass.'"></span> <span>'. $this->number_format($percent, $currency, false, 2) .'%</span></span>';
        }

        public function slugify($string){
            $slug = preg_replace('/[^A-Za-z0-9-]+/', '-', $string);
            $slug = strtolower($slug);
            $slug = str_replace('xrp', 'ripple', $slug);
            return $slug;
        }

        public function time_ago($datetime, $full = false) {
            $now = new DateTime;
            $ago = new DateTime(gmdate("Y-m-d H:i:s", $datetime));
            $diff = $now->diff($ago);

            $diff->w = floor($diff->d / 7);
            $diff->d -= $diff->w * 7;

            $string = array(
                'y' => 'year',
                'm' => 'month',
                'w' => 'week',
                'd' => 'day',
                'h' => 'hour',
                'i' => 'minute',
                's' => 'second',
            );
            foreach ($string as $k => &$v) {
                if ($diff->$k) {
                    $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
                } else {
                    unset($string[$k]);
                }
            }

            if (!$full) $string = array_slice($string, 0, 1);
            return $string ? implode(', ', $string) . ' ago' : 'just now';
        }

        public function ticker_shortcode($id, $options) {
            
            $fiatrate = $options['mcw_currencies']->{$options['currency']};
            
            $output = '<div class="cryptoboxes" id="mcw-' . $id . '" data-realtime="' . $options['real_time'] . '"' . (is_rtl() ? ' dir="rtl"' : '') . '>';
            
            $css = '';

            if ($options['font'] !== 'inherit') {
                $css .= '#mcw-' . $id . ' { font-family: "' . $options['font'] . '", sans-serif; }';
            }

            if ($options['theme'] == 'custom') {
                $css .= '#mcw-'.$id.' .cc-coin { color: '.$options['text_color'].'; } #mcw-'.$id.' .cc-ticker { background-color: '.$options['background_color'].'; }';
            }

            wp_add_inline_style("mcw-custom", $css);
            
            $output .= '<div class="mcw-ticker mcw-ticker-' . $options['ticker_design'] . ' mcw-' . $options['ticker_position'] . '" data-speed="' . $options['ticker_speed'] . '">';
            $output .= '<div class="cc-ticker';
            $output .= ' mcw-' . $options['theme'] . '-theme';
            if (is_array($options['ticker_columns']) && in_array('round', $options['ticker_columns'])) {
                $output .= ' cc-ticker-round';
            }
            $output .= '">';
            $output .= '<div class="cc-stats">';
            
            if ($options['ticker_design'] == 1) {
                
                foreach ($options['data'] as $coin) {

                    if (isset($options['links'][$coin->slug])) {
                        $linkstart = '<a href="' . $options['links'][$coin->slug] . '" class="mcw-link cc-coin">'; $linkend = '</a>';
                    } else if (isset($options['links'][$coin->symbol])) {
                        $linkstart = '<a href="' . $options['links'][$coin->symbol] . '" class="mcw-link cc-coin">'; $linkend = '</a>';
                    } else {
                        $linkstart = '<div class="cc-coin">'; $linkend = '</div>';
                    }

                    $output .= $linkstart;
                    if (in_array('logo',$options['ticker_columns'])) {
                        $output .= '<div><img class="coin-img" alt="'. $coin->slug .'" src="' . apply_filters('mcw_coin_img', $coin->img, 'ticker') . '" height="25" /></div>';
                    }
                    $output .=  $coin->name . ' (' . $coin->symbol . ') <span';
                    $output .= ' data-price="' . $coin->price_usd . '"';
                    $output .= ' data-live-price="' . $this->slugify($coin->name) . '"';
                    $output .= ' data-rate="' . $fiatrate . '"';
                    $output .= ' data-currency="' . $options['currency'] . '"';
                    $len = strlen(array_column($this->config['currency_format'], null, 'iso')[$options['currency']]['symbol']);
                    $output .= ' style="width: '. (strlen($this->number_format($coin->price_usd * $fiatrate, $options['currency'])) + $len) .'ch !important;text-align:center;margin-left:1ch;"';
                    $output .= ">";
                    $output .= $this->price_format($coin->price_usd * $fiatrate, $options['currency']);
                    $output .= '</span>';
                    
                    if (in_array('changes',$options['ticker_columns'])) {
                        $output .= $this->percent_change($coin->percent_change_24h, $coin->symbol, $options['currency']);
                    }

                    $output .= $linkend;
                }
                
            } else {
                
                foreach ($options['data'] as $coin) {

                    if (isset($options['links'][$coin->slug])) {
                        $linkstart = '<a href="' . $options['links'][$coin->slug] . '" class="mcw-link cc-coin">'; $linkend = '</a>';
                    } else if (isset($options['links'][$coin->symbol])) {
                        $linkstart = '<a href="' . $options['links'][$coin->symbol] . '" class="mcw-link cc-coin">'; $linkend = '</a>';
                    } else {
                        $linkstart = '<div class="cc-coin">'; $linkend = '</div>';
                    }
                    
                    $output .= $linkstart;
                    $output .= '<div class="coin-info">';
                    $output .= '<div class="coin-name">';
                    if (in_array('logo',$options['ticker_columns'])) {
                        $output .= '<img alt="'. $coin->slug .'" src="' . apply_filters('mcw_coin_img', $coin->img, 'ticker') . '" height="25" />';
                    }
                    $output .= '<span>' . $coin->symbol . '/' . $options['currency'] . '</span>';
                    $output .= '</div>';
                    $output .= '<div class="mcw-price">';
                    $output .= '<span data-price="' . $coin->price_usd . '"';
                    $output .= ' data-live-price="' . $this->slugify($coin->name) . '"';
                    $output .= ' data-rate="' . $fiatrate . '"';
                    $output .= ' data-currency="' . $options['currency'] . '"';
                    $len = strlen(array_column($this->config['currency_format'], null, 'iso')[$options['currency']]['symbol']);
                    $output .= ' style="width: '. (strlen($this->number_format($coin->price_usd * $fiatrate, $options['currency'])) + $len) .'ch !important;text-align:center;"';
                    $output .= ">";
                    $output .= $this->price_format($coin->price_usd * $fiatrate, $options['currency']);
                    $output .= '</span>';
                    if (in_array('changes',$options['ticker_columns'])) {
                        $output .= $this->percent_change($coin->percent_change_24h, $coin->symbol, $options['currency']);
                    }
                    $output .= '</div>';
                    $output .= '</div>';
                    $output .= '<div class="coin-chart" style="width: 150px; height: 50px;">';
                    $output .= '<canvas width="135" height="40" data-rate="' . $fiatrate . '" data-currency="'. $options['currency'] .'" data-chart="sparkline" data-color="' .apply_filters('mcw_chart_color', $options['chart_color'], $options, $coin). '" data-gradient="50" data-border="2" data-points="' . implode(',', array_slice($options['weekly'][$coin->slug], -24)) . '"></canvas>';
                    $output .= '</div>';
                    $output .= $linkend;
                }
                
            }
            
            $output .= '</div>';
            $output .= '</div>';
            $output .= '</div>';
            $output .= '</div>';
            
            return $output;
                
        }
            
        public function table_shortcode($id, $options, $title) {

            $css = '';

            if ($options['font'] !== 'inherit') {
                $css .= '#mcw-' . $id . ' { font-family: "' . $options['font'] . '", sans-serif; }';
            }

            wp_add_inline_style("mcw-custom", $css);
            
            $fiatrate = $options['mcw_currencies']->{$options['currency']};
            
            $colnames = [
                "rank" => __("#", "massive-cryptocurrency-widgets"),
                "name" => __("Coin", "massive-cryptocurrency-widgets"),
                "symbol" => __("Symbol", "massive-cryptocurrency-widgets"),
                "price_usd" => __("Price", "massive-cryptocurrency-widgets"),
                "price_btc" => __("Price (BTC)", "massive-cryptocurrency-widgets"),
                "market_cap_usd" => __("Marketcap", "massive-cryptocurrency-widgets"),
                "volume_usd_24h" => __("Volume (24h)", "massive-cryptocurrency-widgets"),
                "available_supply" => __("Supply", "massive-cryptocurrency-widgets"),
                "percent_change_24h" => __("Change", "massive-cryptocurrency-widgets"),
                "weekly" => __("Last 24h", "massive-cryptocurrency-widgets"),
            ];
            
            $output = '<div class="cryptoboxes mcw-table '. $options['table_style'] . '" id="mcw-' . $id . '" data-realtime="' . $options['real_time'] . '">';
            if ($title !== "") {
                $output .= '<div class="title-bar">' . $title . '</div>';
            }
            $output .= '<table class="mcw-datatable dataTable display nowrap" style="width: 100%" data-length="' . $options['table_length'] . '" data-total="' . ((sizeof($options['coins']) > 0) ? sizeof($options['coins']) : $options['numcoins']) . '">';
            $output .= '<thead><tr>';
            
            foreach ($options['table_columns'] as $column) {
                $align = ($column == 'rank' || $column == 'name') ? ' text-left' : '';
                $output .= '<th class="col-' . $column . $align . '" data-col=' . $column . '>'. $colnames[$column] .'</th>';
            }
            $output .= '</tr></thead>';
            $output .= '<tbody>';
            
            $count = 0;
            $shortprice = ($options['price_format'] == 1) ? true : false;
            
            $output .= '</tbody>';
            $output .= '</table>';
            $output .= '</div>';
            
            return $output;
        }
        
        public function chart_shortcode($id, $options) {

            $css = '';

            if ($options['font'] !== 'inherit') {
                $css .= '#mcw-' . $id . ' { font-family: "' . $options['font'] . '", sans-serif; }';
            }
            
            $output = '<div class="cryptoboxes" id="mcw-' . $id . '" data-realtime="' . $options['real_time'] . '">';
            $output .= '<div class="mcw-chart mcw-chart-'. str_replace('custom', '', $options['chart_theme']) .'"';
            $output .= ' data-font = "'. $options['font'] .'"';                
            $output .= ' data-coin = "'. $options['data'][0]->slug .'"';
            $output .= ' data-symbol = "'. $options['data'][0]->symbol .'"';
            $output .= ' data-currency = "'. $options['currency'] .'"';
            $output .= ' data-rate = "'. $options['mcw_currencies']->{$options['currency']} .'"';
            $output .= ' data-type = "'. $options['chart_type'] .'"';
            $output .= ' data-view = "'. $options['chart_view'] .'"';
            $output .= ' data-theme = "'. str_replace('custom', '', $options['chart_theme']) .'"';
            $output .= ' data-smooth = "'. $options['chart_smooth'] .'"';
            $areacolor = strpos($options['chart_theme'], 'custom') ? $options['text_color'] : '';
            $bgcolor = strpos($options['chart_theme'], 'custom') ? $options['background_color'] : '';
            $output .= ' data-areacolor = "'. $areacolor .'"';
            $output .= ' data-bgcolor = "'. $bgcolor .'"></div>';
            $output .= '</div>';
            
            return $output;
            
        }
        
        public function converter_shortcode($id, $options) {

            $css = '';

            if ($options['font'] !== 'inherit') {
                $css .= '#mcw-' . $id . ' { font-family: "' . $options['font'] . '", sans-serif; }';
            }

            wp_add_inline_style("mcw-custom", $css);

            $converters = explode('-to-', $options['converter_type']);
            
            $selects = array('crypto' => array(), 'fiat' => array(), 'multicrypto' => array(), 'singlefiat' => array());
            
            foreach($options['data'] as $coin) {
                array_push($selects['crypto'], array($coin->slug, $coin->price_usd, $coin->symbol));
            }
            
            foreach($options['mcw_currencies'] as $key => $value) {
                array_push($selects['fiat'], array($key, $value, $key));
            }

            foreach($options['mcw_cryptocurrencies'] as $coin) {
                array_push($selects['multicrypto'], array($coin->slug, $coin->price_usd, $coin->symbol));
            }

            $curcrypto = '';

            if(isset($options['coins'][0])){
                $curcrypto = $options['coins'][0];
            }
            
            $output = '<div class="cryptoboxes" id="mcw-' . $id . '" data-fiat="'. $options['currency'] .'" data-crypto="'. $curcrypto .'">';
            $output .= '<div class="mcw-converter';
            $output .= ($options['converter_button'] == 'on') ? ' mcw-converter-two"' : ' mcw-converter-one"';
            $output .= ' data-from="' . $converters[0] . '" data-to="' . $converters[1] . '"';
            $output .= ($options['converter_button'] == 'on') ? ' data-auto="false"' : ' data-auto="true"';
            $output .= ' style="background-color: ' . $options['background_color'] . '"';
            $output .= '>';
            
            if ($options['converter_button'] == 'on') {
                $output .= '<div class="mcw-form-group">';
            }
            
            $output .= '<div class="mcw-form-control">';
            $output .= '<div class="mcw-input-container">';
            $output .= '<div class="mcw-input-group">';

            $output .= '<select>';
            
            foreach($selects[$converters[0]] as $item) {
                $output .= '<option value="' . $item[0] . '" data-val="' . $item[1] . '">' . $item[2] . '</option>';
            }
            
            $output .= '</select>';
            
            $output .= '<input type="text" class="mcw-field" />';
            
            $output .= '</div>';
            $output .= '</div>';
            $output .= '</div>';
            
            if ($options['converter_button'] == 'on') {
                $output .= '<div class="mcw-form-control mcw-convert-swap" style="flex-grow: 1; text-align: center; opacity: 0.5; cursor: pointer;"><img src="' . MCW_URL . 'assets/public/img/convert.png" alt=""></div>';
            }
            
            $output .= '<div class="mcw-form-control">';
            $output .= '<div class="mcw-input-container">';
            $output .= '<div class="mcw-input-group">';
            
            $multi = ($converters[1] == 'crypto') ? 'multicrypto' : $converters[1];

            $output .= '<select>';
            
            foreach($selects[$multi] as $item) {
                $output .= '<option value="' . $item[0] . '" data-val="' . $item[1] . '">' . $item[2] . '</option>';
            }
            
            $output .= '</select>';
            
            $output .= '<input type="text" class="mcw-field" />';
            
            $output .= '</div>';
            $output .= '</div>';
            $output .= '</div>';
            
            if ($options['converter_button'] == 'on') {
                $output .= '</div>';
                $output .= '<div class="mcw-form-group"><div class="mcw-form-control"><button class="mcw-button">' . __("Convert", "massive-cryptocurrency-widgets") . '</button></div></div>';
            }
            
            $output .= "</div>";
            $output .= '</div>';
            
            return $output;
        }
        
        public function card_shortcode($id, $options) {
            
            $fiatrate = $options['mcw_currencies']->{$options['currency']};
            
            $output = '<div class="cryptoboxes" id="mcw-' . $id . '" data-realtime="' . $options['real_time'] . '">';
            
            $css = '';

            if ($options['font'] !== 'inherit') {
                $css .= '#mcw-' . $id . ' { font-family: "' . $options['font'] . '", sans-serif; }';
            }

            if($options['theme'] == 'custom') {
                $css .= '#mcw-'.$id.' .mcw-card { color: '.$options['text_color'].'; fill: '.$options['text_color'].'; } #mcw-'.$id.' .mcw-card { background-color: '.$options['background_color'].' } #mcw-'.$id.' .mcw-card-7 .mcw-toggle-switch { border: 1px solid '. $options['text_color'] .'; }';
            }

            wp_add_inline_style("mcw-custom", $css);
            
            foreach ($options['data'] as $coin) {

                if (isset($options['links'][$coin->slug])) {
                    $linkstart = '<a href="' . $options['links'][$coin->slug] . '" class="mcw-link mcw-card'; $linkend = '</a>';
                } else if (isset($options['links'][$coin->symbol])) {
                    $linkstart = '<a href="' . $options['links'][$coin->symbol] . '" class="mcw-link mcw-card'; $linkend = '</a>';
                } else {
                    $linkstart = '<div class="mcw-card'; $linkend = '</div>';
                }
                
                $output .= $linkstart;
                $output .= ' mcw-card-' . $options['card_design'];
                $output .= ' mcw-' . $options['theme'] . '-theme';
                $output .= (in_array('fullwidth', $options['display_columns'])) ? ' mcw-stretch' : '';
                $output .= (in_array('rounded', $options['display_columns'])) ? ' mcw-rounded' : '';
                $output .= '">';

                $shortprice = ($options['price_format'] == 1) ? true : false;
                
                switch ($options['card_design']) {
                    case 1:
                    $output .= '<div class="bg">';
                    $output .= '<img alt="'. $coin->slug .'" src="' . $coin->img . '" />';
                    $output .= '</div>';
                    $output .= '<div class="mcw-card-head"><div>';
                    if (in_array('logo', $options['ticker_columns'])) {
                        $output .= '<img alt="'. $coin->slug .'" src="' . apply_filters('mcw_coin_img', $coin->img, 'card') . '" height="25" />';
                    }                        
                    $output .= '<p>' . $coin->name . ' (' . $coin->symbol . ')</p>';
                    $output .= '</div></div>';
                    $output .= '<div class="mcw-pricelabel">' . __("Price", "massive-cryptocurrency-widgets") . '</div>';
                    $output .= '<div class="mcw-price" data-price="' . $coin->price_usd . '" data-live-price="' . $this->slugify($coin->name) . '" data-rate="' . $fiatrate . '" data-currency="' . $options['currency'] . '">';
                    $output .= $this->price_format($coin->price_usd * $fiatrate, $options['currency']);
                    $output .= '</div>';
                    break;
                    case 2:
                    $output .= '<div class="mcw-price" data-price="' . $coin->price_usd . '" data-live-price="' . $this->slugify($coin->name) . '" data-rate="' . $fiatrate . '" data-currency="' . $options['currency'] . '">';
                    $output .= $this->price_format($coin->price_usd * $fiatrate, $options['currency']);
                    $output .= '</div>';
                    $output .= '<div class="mcw-card-head"><div>';
                    if (in_array('logo', $options['ticker_columns'])) {
                        $output .= '<img alt="'. $coin->slug .'" src="' . apply_filters('mcw_coin_img', $coin->img, 'card') . '" height="25" />';
                    }                        
                    $output .= '<p>' . $coin->name . ' (' . $coin->symbol . ')</p>';
                    $output .= '</div></div>';
                    break;
                    case 3:
                    $output .= '<div>';
                    $output .= '<ul>';
                    $output .= "<li>";
                    $output .= '<div class="mcw-card-symbol">' . $coin->symbol . '</div>';
                    $output .= '<div class="mcw-card-name">' . $coin->name . '</div>';
                    $output .= '</li>';
                    $output .= '</ul>';
                    $output .= '<ul>';
                    $output .= '<li>';
                    $output .= '<div class="mcw-pricelabel">' . __("Price", "massive-cryptocurrency-widgets") . '</div>';
                    $output .= '<div class="mcw-price" data-price="' . $coin->price_usd . '" data-live-price="' . $this->slugify($coin->name) . '" data-rate="' . $fiatrate . '" data-currency="' . $options['currency'] . '">';
                    $output .= $this->price_format($coin->price_usd * $fiatrate, $options['currency']);
                    $output .= '</li>';
                    $output .= '</ul>';
                    $output .= '</div>';
                    $output .= '<div>';
                    $output .= '<ul>';
                    $output .= '<li>';
                    $output .= '<div class="mcw-changelabel">' . __("Change", "massive-cryptocurrency-widgets") . '</div>';
                    $output .= '<div class="mcw-card-percent">' . $this->percent_change($coin->percent_change_24h, $coin->symbol, $options['currency']) . '</div>';
                    $output .= '</li>';
                    $output .= '</ul>';
                    $output .= '<ul>';
                    $output .= '<li>';
                    $output .= '<div class="mcw-card-marketcap">' . __("Marketcap", "massive-cryptocurrency-widgets") . '</div>';
                    $output .= '<div class="mcw-card-price">' . $this->price_format($coin->market_cap_usd * $fiatrate, $options['currency'], $shortprice, 0) . '</div>';
                    $output .= '</li>';
                    $output .= '</ul></div>';
                    break;
                    case 4:
                    $output .= '<div class="mcw-card-head">' . $coin->name . '</div>';
                    $output .= '<div class="mcw-price" data-price="' . $coin->price_usd . '" data-live-price="' . $this->slugify($coin->name) . '" data-rate="' . $fiatrate . '" data-currency="' . $options['currency'] . '">';
                    $output .= $this->price_format($coin->price_usd * $fiatrate, $options['currency']);
                    $output .= '</div>';
                    $output .= $this->percent_change($coin->percent_change_24h, $coin->symbol, $options['currency']);
                    break;
                    case 5:
                    $output .= '<div class="mcw-card-head">'.$coin->name.'</div>';
                    $output .= '<div class="mcw-card-marketcap">' . __("Marketcap", "massive-cryptocurrency-widgets") . ' | ' . $this->price_format($coin->market_cap_usd * $fiatrate, $options['currency'], $shortprice, 0) . '</div>';
                    $output .= '<div class="mcw-price" data-price="' . $coin->price_usd . '" data-live-price="' . $this->slugify($coin->name) . '" data-rate="' . $fiatrate . '" data-currency="' . $options['currency'] . '">';
                    $output .= $this->price_format($coin->price_usd * $fiatrate, $options['currency']);
                    $output .= '</div>';
                    $output .= $this->percent_change($coin->percent_change_24h, $coin->symbol, $options['currency']);
                    break;
                    case 6:
                    $output .= '<div class="mcw-dn6-head">';
                    $output .= '<div>' . $coin->symbol . '</div>';
                    $output .= '<div>' . $coin->name . '</div>';
                    $output .= '</div>';
                    $output .= '<div class="mcw-dn6-body">';
                    $output .= '<ul>';
                    $output .= '<li>'. $options['currency'] .'</li>';
                    if (!empty($options['currency2'])) {
                        $output .= '<li>'. $options['currency2'] .'</li>';
                    }
                    if (!empty($options['currency3'])) {
                        $output .= '<li>'. $options['currency3'] .'</li>';
                    }
                    $output .= '</ul>';
                    $output .= '<ul>';
                    $output .= '<li><span data-price="' . $coin->price_usd . '" data-live-price="'.$this->slugify($coin->name).'" data-rate="' . $fiatrate . '" data-currency="' . $options['currency'] . '">' . $this->price_format($coin->price_usd * $fiatrate, $options['currency']) . '</span></li>';
                    if (!empty($options['currency2'])) {
                        $output .= '<li><span data-price="' . $coin->price_usd . '" data-live-price="'.$this->slugify($coin->name).'" data-rate="' . $options['mcw_currencies']->{$options['currency2']} . '" data-currency="' . $options['currency2'] . '">' . $this->price_format($coin->price_usd * $options['mcw_currencies']->{$options['currency2']}, $options['currency2']) . '</span></li>';
                    }
                    if (!empty($options['currency3'])) {
                        $output .= '<li><span data-price="' . $coin->price_usd . '" data-live-price="'.$this->slugify($coin->name).'" data-rate="' . $options['mcw_currencies']->{$options['currency3']} . '" data-currency="' . $options['currency3'] . '">'.  $this->price_format($coin->price_usd * $options['mcw_currencies']->{$options['currency3']}, $options['currency3']) . '</span></li>';
                    }
                    $output .= '</ul>';
                    $output .= '</div>';
                    break;
                    case 7:
                    $output .= '<div class="mcw-card-head">';
                    $output .= '<div class="mcw-price" data-live="' . $this->slugify($coin->name) . '" data-price="' . $coin->price_usd . '" data-rate="' . $fiatrate . '" data-currency="' . $options['currency'] . '">' . $this->price_format($coin->price_usd * $fiatrate, $options['currency']) . '</div>';
                    $output .= '</div>';
                    $output .= '<div class="mcw-card-body">';
                    $output .= '<div class="mcw-card-name">';
                    if (in_array('logo', $options['ticker_columns'])) {
                        $output .= '<img alt="'. $coin->slug .'" src="' . apply_filters('mcw_coin_img', $coin->img, 'card') . '" height="50" />';
                    }
                    $output .= '<div>' . $coin->name . ' <span>(' . $coin->symbol . ')</span></div>';
                    $output .= '</div>';
                    $output .= '<div class="mcw-changes">';
                    $class = ($coin->percent_change_1h > 0) ? 'mcw-up' : 'mcw-down';
                    $output .= '<div>' . __("1h", "massive-cryptocurrency-widgets") . '<span class="' . $class . '">' . abs($coin->percent_change_1h) . '%</span></div>';
                    $class = ($coin->percent_change_24h > 0) ? 'mcw-up' : 'mcw-down';
                    $output .= '<div>' . __("24h", "massive-cryptocurrency-widgets") . '<span class="' . $class . '">' . abs($coin->percent_change_24h) . '%</span></div>';
                    $output .= '</div>';
                    $output .= '<div class="mcw-toggle-wrapper">';
                    $output .= '<div class="mcw-toggle">';
                    $output .= '<div class="mcw-toggle-switch active" data-rate="' . $options['mcw_currencies']->{$options['currency']} . '" data-currency="' . $options['currency'] .'">' . $options['currency'] .'</div>';
                    if (!empty($options['currency2'])) {
                        $output .= '<div class="mcw-toggle-switch" data-rate="' . $options['mcw_currencies']->{$options['currency2']} . '" data-currency="' . $options['currency2'] .'">' . $options['currency2'] .'</div>';
                    }
                    if (!empty($options['currency3'])) {
                        $output .= '<div class="mcw-toggle-switch" data-rate="' . $options['mcw_currencies']->{$options['currency3']} . '" data-currency="' . $options['currency3'] .'">' . $options['currency3'] .'</div>';
                    }
                    $output .= "</div>";
                    $output .= "</div>";
                    $output .= "</div>";
                }
                
                $output .= $linkend;
            }
            
            $output .= '</div>';
            
            return $output;
            
        }
        
        public function label_shortcode($id, $options) {
            
            $fiatrate = $options['mcw_currencies']->{$options['currency']};
            
            $output = '<div class="cryptoboxes" id="mcw-' . $id . '" data-realtime="' . $options['real_time'] . '">';
            
            $css = '';

            if ($options['font'] !== 'inherit') {
                $css .= '#mcw-' . $id . ' { font-family: "' . $options['font'] . '", sans-serif; }';
            }

            if ($options['theme'] == 'custom') {
                $css .= '#mcw-'.$id.'.cryptoboxes .mcw-label { color: '.$options['text_color'].'; } #mcw-'.$id.'.cryptoboxes .mcw-label { background-color: '.$options['background_color'].' }';
            }

            wp_add_inline_style("mcw-custom", $css);
            
            foreach ($options['data'] as $coin) {

                if (isset($options['links'][$coin->slug])) {
                    $linkstart = '<a href="' . $options['links'][$coin->slug] . '" class="mcw-link mcw-label'; $linkend = '</a>';
                } else if (isset($options['links'][$coin->symbol])) {
                    $linkstart = '<a href="' . $options['links'][$coin->symbol] . '" class="mcw-link mcw-label'; $linkend = '</a>';
                } else {
                    $linkstart = '<div class="mcw-label'; $linkend = '</div>';
                }
                
                $output .= $linkstart;
                $output .= ' mcw-label-' . $options['label_design'];
                $output .= ' mcw-' . $options['theme'] . '-theme';
                $output .= (in_array('fullwidth', $options['display_columns'])) ? ' mcw-stretch' : '';
                $output .= (in_array('rounded', $options['display_columns'])) ? ' mcw-rounded' : '';
                $output .= '">';
                
                switch ($options['label_design']) {
                    case 1:
                    $output .= '<div class="mcw-label-dn1-head">';
                    $output .= '<div class="mcw-card-head">';
                    $output .= '<div>';
                    if (in_array('logo', $options['ticker_columns'])) {
                        $output .= '<img alt="'. $coin->slug .'" src="' . apply_filters('mcw_coin_img', $coin->img, 'label') . '" height="25" />';
                    }
                    $output .= '<p>' . $coin->name . ' (' . $coin->symbol . ')</p>';
                    $output .= '</div>';
                    $output .= '</div>';
                    $output .= '</div>';
                    $output .= '<div class="mcw-label-dn1-body">';
                    $output .= '<b data-price="' . $coin->price_usd . '" data-live-price="'.$this->slugify($coin->name).'" data-rate="' . $fiatrate . '" data-currency="' . $options['currency'] . '">' . $this->price_format($coin->price_usd * $fiatrate, $options['currency']) . '</b>';
                    $output .= '</div>';
                    break;
                    case 2:
                    $output .= '<div class="mcw-label-dn2">';
                    $output .= '<div class="mcw-card-head">';
                    $output .= '<div class="mcw-flex">';
                    if (in_array('logo', $options['ticker_columns'])) {
                        $output .= '<img alt="'. $coin->slug .'" src="' . apply_filters('mcw_coin_img', $coin->img, 'label') . '" height="25" />';
                    }                        
                    $output .= '<p>' . $coin->name . ' (' . $coin->symbol . ')</p>';
                    $output .= '</div>';
                    $output .= '</div>';
                    $output .= '</div>';
                    $output .= '<div class="mcw-label-dn2">';
                    $output .= '<div>' . $options['currency'] . '</div>';
                    $output .= '<div>';
                    $output .= '<b data-price="' . $coin->price_usd . '" data-live-price="'.$this->slugify($coin->name).'" data-rate="' . $options['mcw_currencies']->{$options['currency']} . '" data-currency="' . $options['currency'] . '">' . $this->price_format($coin->price_usd * $options['mcw_currencies']->{$options['currency']}, $options['currency']) . '</b>';
                    $output .= '</div>';
                    $output .= '</div>';
                    $output .= '<div class="mcw-label-dn2">';
                    $output .= '<div>' . $options['currency2'] . '</div>';
                    $output .= '<div>';
                    $output .= '<b data-price="' . $coin->price_usd . '" data-live-price="'.$this->slugify($coin->name).'" data-rate="' . $options['mcw_currencies']->{$options['currency2']} . '" data-currency="' . $options['currency2'] . '">' . $this->price_format($coin->price_usd * $options['mcw_currencies']->{$options['currency2']}, $options['currency2']) . '</b>';
                    $output .= '</div>';
                    $output .= '</div>';
                    $output .= '<div class="mcw-label-dn2">';
                    $output .= '<div>' . $options['currency3'] . '</div>';
                    $output .= '<div>';
                    $output .= '<b data-price="' . $coin->price_usd . '" data-live-price="'.$this->slugify($coin->name).'" data-rate="' . $options['mcw_currencies']->{$options['currency3']} . '" data-currency="' . $options['currency3'] . '">' . $this->price_format($coin->price_usd * $options['mcw_currencies']->{$options['currency3']}, $options['currency3']) . '</b>';
                    $output .= '</div>';
                    $output .= '</div>';
                    break;
                    case 3:
                    $output .= '<div class="mcw-label-dn1-head">';
                    $output .= '<div class="mcw-card-head">';
                    $output .= '<div>';
                    if (in_array('logo', $options['ticker_columns'])) {
                        $output .= '<img alt="'. $coin->slug .'" src="' . apply_filters('mcw_coin_img', $coin->img, 'label') . '" height="25" />';
                    }                        
                    $output .= '<p>' . $coin->name . ' (' . $coin->symbol . ')</p>';
                    $output .= '</div>';
                    $output .= '</div>';
                    $output .= '</div>';
                    $output .= '<div class="mcw-label-dn1-body">';
                    $output .= '<b data-price="' . $coin->price_usd . '" data-live-price="'.$this->slugify($coin->name).'" data-rate="' . $fiatrate . '" data-currency="' . $options['currency'] . '">' . $this->price_format($coin->price_usd * $fiatrate, $options['currency']) . '</b>';
                    $output .= $this->percent_change($coin->percent_change_24h, $coin->symbol, $options['currency']);
                    $output .= '</div>';
                    break;
                }
                
                $output .= $linkend;
                
                
            }
            
            $output .= '</div>';
            
            return $output;
            
        }
        
        public function list_shortcode($id, $options) {
            
            $fiatrate = $options['mcw_currencies']->{$options['currency']};
            
            $output = '<div class="cryptoboxes" id="mcw-' . $id . '" data-realtime="' . $options['real_time'] . '">';
            $output .= '<div class="mcw-list mcw-list-shadow';
            $output .= ' mcw-list-' . $options['list_design'];
            if ($options['list_design'] !== 2) {
                $output .= ' mcw-' . $options['theme'] . '-theme';
            }
            $output .= '">';
            
            $css = '';

            if ($options['font'] !== 'inherit') {
                $css .= '#mcw-' . $id . ' { font-family: "' . $options['font'] . '", sans-serif; }';
            }

            if ($options['theme'] == 'custom') {
                $css .= '#mcw-'.$id.'.cryptoboxes .mcw-list { color: '.$options['text_color'].'; background-color: '.$options['background_color'].' } #mcw-'.$id.'.cryptoboxes .mcw-list-2 { background-color: transparent; } #mcw-'.$id.'.cryptoboxes .mcw-list-2 .mcw-list-item { background-color: ' . $options['background_color'] . '; } #mcw-'.$id.' .mcw-list-2 .mcw-list-header i::before, #mcw-'.$id.' .mcw-list-2 .mcw-list-header i::after { background-color: ' . $options['text_color'] . ' }';
            }

            wp_add_inline_style("mcw-custom", $css);
            
            foreach ($options['data'] as $coin) {

                if (isset($options['links'][$coin->slug])) {
                    $linkstart = '<a href="' . $options['links'][$coin->slug] . '" class="mcw-link '. (($options['list_design'] == 2) ? 'mcw-list-body' : 'mcw-list-row') .'">'; $linkend = '</a>';
                } else if (isset($options['links'][$coin->symbol])) {
                    $linkstart = '<a href="' . $options['links'][$coin->symbol] . '" class="mcw-link '. (($options['list_design'] == 2) ? 'mcw-list-body' : 'mcw-list-row') .'">'; $linkend = '</a>';
                } else {
                    $linkstart = '<div class="' . (($options['list_design'] == 2) ? 'mcw-list-body' : 'mcw-list-row') . '">'; $linkend = '</div>';
                }
                
                switch ($options['list_design']) {
                    
                    case 1:

                    $output .= $linkstart;
                    $output .= '<div class="mcw-list-column">';
                    if (in_array('logo', $options['ticker_columns'])) {
                        $output .= '<span class="coin-img"><img alt="'. $coin->slug .'" src="' . apply_filters('mcw_coin_img', $coin->img, 'list') . '" height="25" alt="'. $coin->slug .'"></span>';
                    }
                    $output .= '<span>' . $coin->name . '</span>';
                    $output .= '</div>';
                    $output .= '<div class="mcw-list-column"><span class="mcw-list-price" data-price="' . $coin->price_usd . '" data-live-price="' . $this->slugify($coin->name) . '" data-rate="' . $fiatrate . '" data-currency="' . $options['currency'] . '">' . $this->price_format($coin->price_usd * $fiatrate, $options['currency']) . '</span><span class="mcw-list-change ' . (($coin->percent_change_24h >= 0) ? 'up' : 'down') . '">' . abs($coin->percent_change_24h) . '%</span></div>';
                    $output .= $linkend;
                    break;

                    case 2:
                    $output .= '<div class="mcw-list-item mcw-' . $options['theme'] . '-theme mcw-list-shadow '. ((in_array('rounded', $options['display_columns'])) ? ' mcw-list-rounded' : '') .'">';
                    $output .= '<div class="mcw-list-header">';
                    $output .= '<div class="mcw-list-row">';
                    $output .= '<div class="mcw-list-column">';
                    if (in_array('logo', $options['ticker_columns'])) {
                        $output .= '<span class="coin-img"><img alt="'. $coin->slug .'" src="' . apply_filters('mcw_coin_img', $coin->img, 'list') . '" height="25" alt="'. $coin->slug .'"></span>';
                    }                        
                    $output .= '<span>' . $coin->name . '</span>';
                    $output .= '</div>';
                    $output .= '<div class="mcw-list-column"><span class="mcw-list-price" data-price="' . $coin->price_usd . '" data-live-price="' . $this->slugify($coin->name) . '" data-rate="' . $fiatrate . '" data-currency="' . $options['currency'] . '">' . $this->price_format($coin->price_usd * $fiatrate, $options['currency']) . '</span></div>';
                    $output .= '<i></i>';
                    $output .= '</div>';
                    $output .= '</div>';
                    $output .= $linkstart;
                    $output .= '<div class="mcw-list-price" data-price="' . $coin->price_usd . '" data-live-price="' . $this->slugify($coin->name) . '" data-rate="' . $fiatrate . '" data-currency="' . $options['currency'] . '">' . $this->price_format($coin->price_usd * $fiatrate, $options['currency']) . '</div><span class="mcw-list-change ' . (($coin->percent_change_24h >= 0) ? 'up' : 'down') . '">' . abs($coin->percent_change_24h) . '%</span>';
                    $output .= '<div class="chart-wrapper" style="width: 100%; height: 100px;">';
                    $output .= '<canvas width="135" height="40" data-rate="' . $fiatrate . '" data-currency="'. $options['currency'] .'" data-color="' . apply_filters('mcw_chart_color', $options['chart_color'], $options, $coin) . '" data-gradient="140" data-border="2" data-points="' . implode(',', array_slice($options['weekly'][$coin->slug], -24)) . '"></canvas>';
                    $output .= '</div>';
                    $output .= $linkend;
                    $output .= '</div>';
                    break;

                    case 3:
                    $output .= $linkstart;
                    $output .= '<div class="mcw-list-column">';
                    if (in_array('logo', $options['ticker_columns'])) {
                        $output .= '<span class="coin-img"><img alt="'. $coin->slug .'" src="' . apply_filters('mcw_coin_img', $coin->img, 'list') . '" height="25" alt="'. $coin->slug .'"></span>';
                    }                        
                    $output .= '<span>' . $coin->name . '</span>';
                    $output .= '</div>';
                    $output .= '<div class="mcw-list-column">';
                    $output .= '<span class="mcw-list-price" data-price="' . $coin->price_usd . '" data-live-price="' . $this->slugify($coin->name) . '" data-rate="' . $fiatrate . '" data-currency="' . $options['currency'] . '">' . $this->price_format($coin->price_usd * $fiatrate, $options['currency']) . '</span>';
                    $output .= '<span class="inline-chart"><canvas width="135" height="40" data-rate="' . $fiatrate . '" data-currency="'. $options['currency'] .'" data-color="' . apply_filters('mcw_chart_color', $options['chart_color'], $options, $coin) . '" data-gradient="40" data-border="2" data-points="' . implode(',', array_slice($options['weekly'][$coin->slug], -24)) . '"></canvas></span>';
                    $output .= '</div>';
                    $output .= $linkend;
                }
            }
            
            $output .= '</div>';
            $output .= '</div>';
            
            return $output;
            
        }
        
        public function box_shortcode($id, $options) {
            
            $fiatrate = $options['mcw_currencies']->{$options['currency']};
            $coin = $options['data'][0];
            
            $output = '<div class="cryptoboxes" id="mcw-' . $id . '" data-realtime="' . $options['real_time'] . '">';
            
            $css = '';

            if ($options['font'] !== 'inherit') {
                $css .= '#mcw-' . $id . ' { font-family: "' . $options['font'] . '", sans-serif; }';
            }

            if ($options['theme'] == 'custom') {
                $css .= '#mcw-'.$id.'.cryptoboxes .mcw-box { color: '.$options['text_color'].'; background-color: '.$options['background_color'].' }';
            }

            wp_add_inline_style("mcw-custom", $css);

            if (isset($options['links'][$coin->slug]) && $options['box_design'] != 2) {
                $linkstart = '<a href="' . $options['links'][$coin->slug] . '" class="mcw-link mcw-box'; $linkend = '</a>';
            } else if (isset($options['links'][$coin->symbol]) && $options['box_design'] != 2) {
                $linkstart = '<a href="' . $options['links'][$coin->symbol] . '" class="mcw-link mcw-box'; $linkend = '</a>';
            } else {
                $linkstart = '<div class="mcw-box'; $linkend = '</div>';
            }

            $shortprice = ($options['price_format'] == 1) ? true : false;
            
            $output .= $linkstart;
            $output .= ' mcw-box-' . $options['box_design'] . ' mcw-' . $options['theme'] . '-theme ' . (in_array('rounded', $options['display_columns']) ? 'mcw-rounded' : ''). '">';
            
            switch ($options['box_design']) {
                
                case 1:
                
                $difference = abs(($coin->percent_change_24h / 100) * ($coin->price_usd * $fiatrate));
                $output .= '<div class="mcw-box-row">';
                $output .= '<div class="mcw-box-content"><div>' . $coin->name . ' (' . $coin->symbol . ')' . '</div><div class="change"><span class="mcw-list-change ' . (($coin->percent_change_24h >= 0) ? 'up' : 'down') . '">' . abs($coin->percent_change_24h) . '%</span></div></div>';
                $output .= '<div class="mcw-box-content">';
                $output .= '<div class="mcw-box-price-wrapper"><div class="mcw-price" data-price="' . $coin->price_usd . '" data-live-price="' . $this->slugify($coin->name) . '" data-rate="'. $fiatrate .'" data-currency="' . $options['currency'] . '">' . $this->price_format($coin->price_usd * $fiatrate, $options['currency']) . '</div></div>';
                $output .= '<div>' . $this->price_format($difference, $options['currency']) . '</div>';
                $output .= '</div>';
                $output .= '</div>';
                $output .= '<div class="chart-wrapper" style="width: 100%; height: 100px;">';
                $output .= '<canvas width="135" height="40" data-rate="' . $fiatrate . '" data-currency="'. $options['currency'] .'" data-chart="sparkline" data-color="' . apply_filters('mcw_chart_color', $options['chart_color'], $options, $coin) . '" data-opacity="0.3" data-gradient="0" data-border="3" data-points="' . implode(',', array_slice($options['weekly'][$coin->slug], -24)) . '"></canvas>';
                $output .= '</div>';
                $output .= '<div class="mcw-box-row chart-offset" style="background: rgba('. apply_filters('mcw_chart_color', $options['chart_color'], $options, $coin) . ',0.3); color: '. $options['chart_color'] .'">';
                $output .= '<div class="mcw-box-content"><div>&#8675; ' . $this->number_format(min(array_slice($options['weekly'][$coin->slug], -24)) * $fiatrate, $options['currency']) . '</div><div>' . date('j M') . '</div><div>&#8673; ' . $this->number_format(max(array_slice($options['weekly'][$coin->slug], -24)) * $fiatrate, $options['currency']) . '</div></div>';
                $output .= '</div>';
                break;
                
                case 2:
                
                $selects = array('crypto' => array(), 'fiat' => array());
                
                foreach($options['data'] as $data) {
                    array_push($selects['crypto'], array($data->symbol, $data->price_usd, $data->percent_change_24h));
                }
                
                foreach($options['mcw_currencies'] as $key => $value) {
                    array_push($selects['fiat'], array($key, $value));
                }
                
                $output .= '<div class="mcw-box-row">';
                $output .= '<div class="mcw-box-content">';
                $output .= '<div class="mcw-box-converter">';
                $output .= '<div class="mcw-box-select">';
                $output .= '<div class="mcw-select-wrapper">';
                $output .= '<select name="crypto" class="mcw-crypto-convert">';
                foreach($selects['crypto'] as $item) {
                    $output .= '<option data-change="' . $item[2] . '" value="' . $item[1] . '">' . $item[0] . '</option>';
                }
                $output .= '</select>';
                $output .= '</div>';
                $output .= '</div>';
                $output .= '<div class="mcw-box-separator">-</div>';
                $output .= '<div class="mcw-box-select">';
                $output .= '<div class="mcw-select-wrapper">';
                $output .= '<select name="fiat" class="mcw-fiat-convert">';
                $output .= '<option value="' . $options['mcw_currencies']->{$options['currency']} .'">' . $options['currency'] . '</option>';
                foreach($selects['fiat'] as $item) {
                    $output .= '<option data-currency="'. $item[0] .'" value="' . $item[1] . '">' . $item[0] . '</option>';
                }
                $output .= '</select>';
                $output .= '</div>';
                $output .= '</div>';
                $output .= '</div>';
                $output .= '</div>';
                $output .= '<div class="mcw-box-content">';
                $output .= '<div class="mcw-box-price-wrapper">';
                $output .= '<span class="mcw-price" data-price="' . $coin->price_usd . '" data-live-price="' . $coin->slug .'" data-rate="'. $fiatrate .'" data-currency="' . $options['currency'] . '">'. $this->price_format($coin->price_usd * $fiatrate, $options['currency']) . '</span><span class="mcw-list-change ' . (($coin->percent_change_24h >= 0) ? 'up' : 'down') . '">' . abs($coin->percent_change_24h) . '%</span>';
                $output .= '</div>';
                $output .= '</div>';
                $output .= '</div>';
                $output .= '<br>';
                $output .= '<div class="chart-wrapper" style="width: 100%; height: 100px;">';
                $output .= '<canvas width="135" height="40" data-rate="' . $fiatrate . '" data-chart="sparkline" data-color="' . apply_filters('mcw_chart_color', $options['chart_color'], $options, $coin) . '" data-opacity="0.3" data-gradient="150" data-border="3" data-points="' . implode(',', array_slice($options['weekly'][$coin->slug], -24)) . '"></canvas>';
                $output .= '</div>';
                break;
                
                case 3:
                
                $output .= '<div class="mcw-box-row">';
                $output .= '<div class="mcw-box-content center">';
                $output .= '<div class="mcw-box-price-wrapper">';
                $output .= '<div class="mcw-price" data-price="'. $coin->price_usd .'" data-live-price="'. $coin->slug .'" data-rate="'. $fiatrate .'" data-currency="' . $options['currency'] . '">'. $this->price_format($coin->price_usd * $fiatrate, $options['currency']) . '</div>';
                $output .= '</div>';
                $output .= '</div>';
                $output .= '<div class="mcw-box-content center">'. $coin->name .'</div>';
                $output .= '</div>';
                $output .= '<br>';
                $output .= '<div class="chart-wrapper" style="width: 100%; height: 100px;">';
                $output .= '<canvas width="135" height="40" data-rate="' . $fiatrate . '" data-currency="'. $options['currency'] .'" data-chart="sparkline" data-color="'. apply_filters('mcw_chart_color', $options['chart_color'], $options, $coin) .'" data-opacity="0.3" data-gradient="0" data-border="3" data-points="' . implode(',', array_slice($options['weekly'][$coin->slug], -24)) . '"></canvas>';
                $output .= '</div>';
                $output .= '<div class="mcw-box-row chart-offset" style="background: rgba('. apply_filters('mcw_chart_color', $options['chart_color'], $options, $coin) . ',0.3); color: '. $options['chart_color'] .'">';
                $output .= '<div class="mcw-box-content">';
                $output .= '<div>Marketcap</div><div>'. $this->price_format($coin->market_cap_usd * $fiatrate, $options['currency'], $shortprice, 0) .'</div>';
                $output .= '</div>';
                $output .= '<div class="mcw-box-content">';
                $output .= '<div>24H Volume</div><div>'. $this->price_format($coin->volume_usd_24h * $fiatrate, $options['currency'], $shortprice, 0) .'</div>';
                $output .= '</div>';
                $output .= '<div class="mcw-box-content"><div>Supply</div><div> ' . $this->number_format($coin->available_supply, $options['currency'], $shortprice, 0) . ' ' . $coin->symbol . '</div></div>';
                $output .= '</div>';
                break;
                
                case 4: 
                
                $output .= '<div class="mcw-box-row">';
                $output .= '<div class="mcw-box-content center">';
                $output .= '<br>' . $coin->name . '</br>';
                $output .= '<div class="mcw-box-price-wrapper">';
                $output .= '<div class="mcw-price" data-price="'. $coin->price_usd .'" data-live-price="'. $coin->slug .'" data-rate="'. $fiatrate .'" data-currency="' . $options['currency'] . '">'. $this->price_format($coin->price_usd * $fiatrate, $options['currency']) . '</div>';
                $output .= '</div>';
                $output .= '</div>';
                $output .= '</div>';
                $output .= '<div class="chart-wrapper" style="width: 100%; height: 100px;">';
                $output .= '<canvas width="135" height="40" data-rate="' . $fiatrate . '" data-currency="'. $options['currency'] .'" data-chart="sparkline" data-color="'. apply_filters('mcw_chart_color', $options['chart_color'], $options, $coin) .'" data-opacity="1" data-gradient="0" data-border="3" data-points="' . implode(',', array_slice($options['weekly'][$coin->slug], -24)) . '"></canvas>';
                $output .= '</div>';
                $output .= '<div class="mcw-box-row chart-offset" style="background: rgb('. apply_filters('mcw_chart_color', $options['chart_color'], $options, $coin) .');"></div>';
                break;
                
                case 5: 
                
                $difference = abs(($coin->percent_change_24h / 100) * ($coin->price_usd * $fiatrate));
                $output .= '<div class="mcw-box-row">';
                $output .= '<div class="mcw-box-content">';
                $output .= '<div>'.$coin->name.' ('.$coin->symbol.')</div>';
                $output .= '<div><span class="mcw-list-change ' . (($coin->percent_change_24h >= 0) ? 'up' : 'down') . '">' . abs($coin->percent_change_24h) . '%</span></div>';
                $output .= '</div>';
                $output .= '<div class="mcw-box-content">';
                $output .= '<div class="mcw-box-price-wrapper">';
                $output .= '<div class="mcw-price" data-price="'. $coin->price_usd .'" data-live-price="'. $coin->slug .'" data-rate="'. $fiatrate .'" data-currency="' . $options['currency'] . '">'. $this->price_format($coin->price_usd * $fiatrate, $options['currency']) . '</div>';
                $output .= '</div>';
                $output .= '<div>'. $this->price_format($difference, $options['currency']) .'</div>';
                $output .= '</div>';
                $output .= '</div>';
                $output .= '<div class="mcw-box-row">';
                $output .= '<div class="mcw-box-content">';
                $output .= '<div class="chart-wrapper" style="width: 100%; height: 100px;">';
                $output .= '<canvas width="135" height="40" data-rate="' . $fiatrate . '" data-currency="'. $options['currency'] .'" data-chart="sparkline" data-color="'. apply_filters('mcw_chart_color', $options['chart_color'], $options, $coin) .'" data-opacity="0" data-pointradius="3" data-gradient="0" data-border="3" data-points="' . implode(',', array_slice($options['weekly'][$coin->slug], -24)) . '"></canvas>';
                $output .= '</div>';
                $output .= '</div>';
                $output .= '</div>';
                break;

                case 6:

                $output .= '<div class="mcw-box-row">';
                $output .= '<div class="mcw-box-content">';
                $output .= '<div class="mcw-box-column"><span class="coin-img"><img alt="'. $coin->slug .'" src="' . apply_filters('mcw_coin_img', $coin->img, 'box') . '" height="50" alt=""></span><div style="text-align: left;">' . $coin->name . ' (' . $coin->symbol . ')<br>Rank: '. $coin->rank .'</div></div>';
                $output .= '<div class="mcw-box-column"><div class="mcw-box-price-wrapper"><div class="mcw-price" data-price="'. $coin->price_usd .'" data-live-price="'. $coin->slug .'" data-rate="'. $fiatrate .'" data-currency="' . $options['currency'] . '">'. $this->price_format($coin->price_usd * $fiatrate, $options['currency']) . '</div></div></div>';
                $output .= '</div>';
                $output .= '<div class="mcw-box-content"><div>' . __("Price (BTC)", "massive-cryptocurrency-widgets") . '</div><div>Ƀ'. $coin->price_btc .'</div></div>';
                $output .= '<div class="mcw-box-content"><div>' . __("Marketcap", "massive-cryptocurrency-widgets") . '</div><div>'. $this->price_format($coin->market_cap_usd * $fiatrate, $options['currency'], $shortprice, 0) .'</div></div>';
                $output .= '<div class="mcw-box-content"><div>' . __("Volume", "massive-cryptocurrency-widgets") . '</div><div>'. $this->price_format($coin->volume_usd_24h * $fiatrate, $options['currency'], $shortprice, 0) .'</div></div>';
                $output .= '<div class="mcw-box-content"><div>' . __("24h Change", "massive-cryptocurrency-widgets") . '</div><div class="change"><span class="mcw-list-change ' . (($coin->percent_change_24h >= 0) ? 'up' : 'down') . '">' . abs($coin->percent_change_24h) . '%</span></div></div>';
                $output .= '<div class="mcw-box-content" style="border-bottom: 0;"><div>' . __("Total Supply", "massive-cryptocurrency-widgets") . '</div><div>'. $this->number_format($coin->total_supply, $options['currency'], $shortprice, 0) . ' ' . $coin->symbol .'</div></div>';
                $output .= '<br>';
                $output .= '</div>';
                $output .= '<div class="chart-wrapper" style="width: 100%; height: 100px;">';
                $output .= '<canvas width="135" height="40" data-rate="' . $fiatrate . '" data-chart="sparkline" data-color="' . apply_filters('mcw_chart_color', $options['chart_color'], $options, $coin) . '" data-opacity="0.3" data-gradient="150" data-border="3" data-points="' . implode(',', array_slice($options['weekly'][$coin->slug], -24)) . '"></canvas>';
                $output .= '</div>';
                
            }
            
            
            $output .= $linkend;
            $output .= '</div>';
            
            return $output;
            
        }

        public function changelly_shortcode($id, $options) {

            $args = array(
                'from' => ($options['changelly_send_all'] == 'yes') ? '*' : strtolower(implode(',', $options['changelly_send'])),
                'to' => ($options['changelly_send_all'] == 'yes' && $options['changelly_receive_all'] == 'yes') ? '*' : (($options['changelly_receive_all'] == 'yes') ? strtolower(implode(',', $this->changelly)) : strtolower(implode(',', $options['changelly_receive']))),
                'fromDefault' => ($options['changelly_send_all'] == 'yes' && sizeof($options['changelly_send']) == 0) ? 'btc' : strtolower($options['changelly_send'][0]),
                'toDefault' => ($options['changelly_receive_all'] == 'yes' && sizeof($options['changelly_receive']) == 0) ? 'eth' : strtolower($options['changelly_receive'][0]),
                'amount' => $options['changelly_amount'],
                'theme' => $options['changelly_theme'],
                'merchant_id' => (($options['changelly_link'] != '') ? explode('ref_id=', $options['changelly_link'])[1] : ''),
                'payment_id' => '',
                'v' => 3
            );

            $query_string = array();
        
            foreach ($args as $key => $value) {
                $query_string[] = $key . '=' . $value;
            }

            $query_string = implode('&', $query_string);

            $output = '<div class="cryptoboxes">';
            $output .= '<div class="mcw-changelly">';
            $output .= '<iframe width="100%" height="100%" frameborder=\'none\' src="https://widget.changelly.com?' . $query_string . '">Can\'t load widget</iframe>';
            $output .= '</div>';
            $output .= '</div>';
            
            return $output;

        }

        public function multicurrency_shortcode($id, $options) {

            $output = '<div class="cryptoboxes" id="mcw-' . $id . '" data-realtime="' . $options['real_time'] . '">';

            $css = '';

            if ($options['font'] !== 'inherit') {
                $css .= '#mcw-' . $id . ' { font-family: "' . $options['font'] . '", sans-serif; }';
            }

            if ($options['theme'] === 'custom') {
                $css .= '#mcw-' . $id . ' .mcw-multi-tabs, #mcw-'. $id .' .mcw-tabs-content { color: ' . $options['text_color'] . '; background: ' . $options['background_color'] . '; }';
                $css .= '#mcw-' . $id . ' .mcw-tab-bg { background: ' . $options['background_color'] . '; }';
            }

            wp_add_inline_style("mcw-custom", $css);

            $output .= '<div class="mcw-multi-tabs mcw-list mcw-'. $options['theme'] .'-theme ' . ((in_array('rounded', $options['display_columns'])) ? ' mcw-rounded' : '') . '">';
            $output .= '<div class="mcw-tabs">';

            foreach ($options['multi_currencies'] as $index => $currency) {
                $output .=  '<div class="mcw-tab'. (($index === 0) ? ' active' : '') .'"><span>'. $currency .'</span><div class="mcw-tab-bg mcw-'. $options['theme'] .'-theme"></div></div>';
            }
            
            $output .= '</div>';
            $output .= '<div class="mcw-tabs-content">';

            foreach ($options['multi_currencies'] as $index => $currency) {
                $output .= '<div class="mcw-tab-content'. (($index === 0) ? ' active' : '') .'">';

                foreach ($options['data'] as $coin) {

                    if (isset($options['links'][$coin->slug])) {
                        $linkstart = '<a href="' . $options['links'][$coin->slug] . '" class="mcw-link mcw-list-row">'; $linkend = '</a>';
                    } else if (isset($options['links'][$coin->symbol])) {
                        $linkstart = '<a href="' . $options['links'][$coin->symbol] . '" class="mcw-link mcw-list-row">'; $linkend = '</a>';
                    } else {
                        $linkstart = '<div class="mcw-list-row">'; $linkend = '</div>';
                    }

                    $output .= $linkstart;
                    $output .= '<div class="mcw-list-column">';
                    if (in_array('logo', $options['ticker_columns'])) {
                        $output .= '<span class="coin-img"><img src="' . apply_filters('mcw_coin_img', $coin->img, 'multicurrency') . '" height="25" alt="'. $coin->slug .'"></span>';
                    }
                    $output .= '<span>'. $coin->name .' ('. $coin->symbol .')</span>';
                    $output .= '</div>';
                    $output .= '<div class="mcw-list-column">';
                    $output .= '<span class="mcw-list-price" data-price="'. $coin->price_usd .'" data-live-price="'. $coin->slug .'" data-rate="'. $options['mcw_currencies']->{$currency} .'" data-currency="' . $currency . '">';
                    $output .= $this->price_format($coin->price_usd * $options['mcw_currencies']->{$currency}, $currency);
                    $output .= '</span>';

                    if (in_array('changes', $options['ticker_columns'])) {
                        $output .= '<span class="mcw-list-change '. (($coin->percent_change_24h >= 0) ? 'up' : 'down') .'">';
                        $output .= abs($coin->percent_change_24h) . '%';
                        $output .= '</span>';
                    }

                    $output .= '</div>';
                    $output .= $linkend;
                }

                $output .= '</div>';
            }

            $output .= '</div>';
            $output .= '</div>';
            $output .= '</div>';
            return $output;
        }

        public function news_shortcode($id, $options) {
            global $typenow;

            $response = array(
                'news' => array(),
                'invalid' => array()
            );

            $output = '<div class="cryptoboxes news" id="mcw-' . $id . '">';

            $css = '';

            if ($options['font'] !== 'inherit') {
                $css .= '#mcw-' . $id . ' { font-family: "' . $options['font'] . '", sans-serif; }';
            }

            wp_add_inline_style("mcw-custom", $css);

            $feeds = explode('rn', $options['news_feeds']);
            
            $output .= '<ul class="mcw-news">';
            foreach($feeds as $index => $feed){
                $rss = fetch_feed($feed);
               
                if (!is_wp_error($rss)) {
                    
                    $maxitems = $rss->get_item_quantity($options['news_count']);
                    $items = $rss->get_items(0, $maxitems);

                    foreach ($items as $item) {
                        $output .= '<li>';
                        $output .= '<div class="mcw-n-single">';
                        $output .= '<a href="'. esc_url($item->get_permalink()) .'" target="_blank" rel="nofollow" class="mcw-n-media">';

                        if (in_array('images', $options['display_columns'])) {

                            $output .= '<div class="mcw-n-img"';

                            $media = $item->get_enclosure()->get_link();

                            if (empty($media)) {
                                preg_match('/< *img[^>]*src *= *["\']?([^"\']*)/i', $item->get_description(), $matches);

                                if ($matches) {
                                    $media .= $matches[1];
                                }
                            }

                            if (!empty($media)) {
                                $output .= ' style="background-image: url('. $media .');"';
                            }

                            $output .= '></div>';

                        }

                        $output .= '</a>';
                        $output .= '<div class="mcw-n-content">';
                        $output .= '<a href="'. esc_url($item->get_permalink()) .'" target="_blank" rel="nofollow" class="mcw-n-title">'. esc_html($item->get_title()) .'</a>';
                        $output .= '<div class="mcw-n-stat">'. esc_html($item->get_feed()->get_title()) .' - '. $this->time_ago(strtotime($item->get_date())) .'</div>';

                        if ($options['news_length'] > 0) {
                            $description = $item->get_description();
                            $description = wp_kses(trim($description), array());
                            $description = strip_tags(apply_filters('the_excerpt', $description));
                            $description = wp_trim_words($description, $options['news_length']);
                            $description = trim(preg_replace('!\s+!', ' ', $description));	

                            $output .= '<div class="mcw-n-text">'. $description .'</div>';
                        }

                        $output .= '</div>';
                        $output .= '</div>';
                        $output .= '</li>';
                    }
                } else {
                    $response['invalid'][] = $feed;
                }
            }



            
            $output .= '</ul>';
            if(isset($response['invalid']) && count($response['invalid']) > 0 && $typenow == 'mcw'){

                $output .= '<div class="custom-notice notice-warning"><p>Invalid feed urls has been skipped:</p><p><b>'.implode("</b><br><b>", $response['invalid']).'</b></p></div>';
            }
            $output .= '</div>';

            return $output;
        }
            
        public function text_shortcode($id, $options) {

            if (!$options['data']) {
                return;
            }
            
            if ($id === '') {

                $coin = $options['data'];
                $atts = $options['atts'];
                $currency = strtoupper($atts['currency']);
                $fiatrate = $options['mcw_currencies']->{$currency};
                $shortprice = (strtolower($atts['format']) == 'symbol') ? true : false;

                switch ($atts['info']) {

                    case 'rank':
                        $text = '<span>' . $coin->id . '</span>';
                        break;
                    case 'price':
                        $text = '<span data-live-price="' . $this->slugify($coin->name) . '" data-price="' . $coin->price_usd . '" data-rate="' . ($fiatrate * floatval($atts['multiply'])) . '" data-currency="' . $currency . '">' . $this->price_format($coin->price_usd * $fiatrate * floatval($atts['multiply']), $currency) . '</span>';
                        break;
                    case 'pricebtc':
                        $text = '<span>Ƀ ' . $coin->price_btc . '</span>';
                        break;
                    case 'volume':
                        $text = '<span>' . $this->price_format($coin->volume_usd_24h * $fiatrate, $currency, $shortprice, 0) . '</span>';
                        break;
                    case 'supply':
                        $text = '<span>' . $this->number_format($coin->available_supply, $currency, $shortprice, 0) . '</span>';
                        break;
                    case 'marketcap':
                        $text = '<span>' . $this->price_format($coin->market_cap_usd * $fiatrate, $currency, $shortprice, 0) . '</span>';
                        break;
                        case 'change': 
                            $text = '<span class="mcw-change-' . (($coin->percent_change_24h >= 0) ? 'up' : 'down') . '">' . $coin->percent_change_24h . '%' . '</span>';
                        break;
                }

                $output = '<span data-realtime="' . $atts['realtime'] . '">' . $text . '</span>';
                
            } else {
                
                $output = '<div class="cryptoboxes" id="mcw-' . $id . '">';
                
                $output .= '<div class="mcw-text">';
                $output .= '<h4>Text Shortcodes</h4>';
                $output .= '<p>You not need to save text widgets.<br>Use the following shortcode examples to insert coin details anywhere</p>';
                $output .= '<br>';
                $output .= '<div class="mcw-box-title">Get price in USD</div>';
                $output .= '<div class="mcw-box-shortcode">[mcrypto coin="BTC"]</div>';
                $output .= '<br><br>';
                $output .= '<div class="mcw-box-title">Get price in another fiat currency</div>';
                $output .= '<div class="mcw-box-shortcode">[mcrypto coin="BTC" currency="EUR"]</div>';
                $output .= '<br><br>';
                $output .= '<div class="mcw-box-title">Get rank of coin</div>';
                $output .= '<div class="mcw-box-shortcode">[mcrypto coin="BTC" info="rank"]</div>';
                $output .= '<br><br>';
                $output .= '<div class="mcw-box-title">Get btc price</div>';
                $output .= '<div class="mcw-box-shortcode">[mcrypto coin="LTC" info="pricebtc"]</div>';
                $output .= '<br><br>';
                $output .= '<div class="mcw-box-title">Get volume</div>';
                $output .= '<div class="mcw-box-shortcode">[mcrypto coin="BTC" info="volume"]</div>';
                $output .= '<br><br>';
                $output .= '<div class="mcw-box-title">Get available supply</div>';
                $output .= '<div class="mcw-box-shortcode">[mcrypto coin="BTC" info="supply"]</div>';
                $output .= '<br><br>';
                $output .= '<div class="mcw-box-title">Get marketcap</div>';
                $output .= '<div class="mcw-box-shortcode">[mcrypto coin="BTC" info="marketcap"]</div>';
                $output .= '<br><br>';
                $output .= '<div class="mcw-box-title">Change Marketcap Format (Symbol/Text/Number)</div>';
                $output .= '<div class="mcw-box-shortcode">[mcrypto coin="BTC" info="marketcap" format="symbol"]</div>';
                $output .= '<br><br>';
                $output .= '<div class="mcw-box-title">Get price change percentage</div>';
                $output .= '<div class="mcw-box-shortcode">[mcrypto coin="BTC" info="change"]</div>';
                $output .= '<br><br>';
                $output .= '<div class="mcw-box-title">Turn off realtime update</div>';
                $output .= '<div class="mcw-box-shortcode">[mcrypto coin="BTC" realtime="off"]</div>';
                $output .= '<br>';
                $output .= '</div>';
                
                $output .= '</div>';
            }

            $output = apply_filters('mcw_text_shortcode', $output, $options);
            
            return $output;
        }
    }

}

?>