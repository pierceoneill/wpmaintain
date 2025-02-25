<div class="crypto-edit">
    <div class="crypto-options">

        <div class="micon-times crypto-collapse"></div>

        <div class="crypto-rows">
            <div class="crypto-cols crypto-labels">
                <label for="title"><?php _e("Widget Title", "massive-cryptocurrency-widgets"); ?></label>
            </div>
            <div class="crypto-cols">
                <input type="text" class="selectize-input post-title" name="post_title" id="title" value="<?php echo esc_html(get_the_title()); ?>" />
            </div>
        </div>

        <div class="crypto-rows widget-type">
            <div class="crypto-cols crypto-labels">
                <label for="crypto_ticker"><?php _e("Widget Type", "massive-cryptocurrency-widgets"); ?></label>
            </div>
            <div class="crypto-cols">
                <label for="crypto_ticker1" class="form-radio">
                    <input type="radio" class="beaut-radio" name="type" id="crypto_ticker1" value="ticker" <?php if ($options['type'] == 'ticker') { echo 'checked'; } ?> /><i class="form-icon"></i> <?php _e("Ticker", "massive-cryptocurrency-widgets"); ?>
                </label>
                <label for="crypto_ticker2" class="form-radio">
                    <input type="radio" class="beaut-radio" name="type" id="crypto_ticker2" value="table" <?php if ($options['type'] == 'table') { echo 'checked'; } ?> /><i class="form-icon"></i> <?php _e("Table", "massive-cryptocurrency-widgets"); ?>
                </label>
                <label for="crypto_ticker3" class="form-radio">
                    <input type="radio" class="beaut-radio" name="type" id="crypto_ticker3" value="chart" <?php if ($options['type'] == 'chart') { echo 'checked'; } ?> /><i class="form-icon"></i> <?php _e("Chart", "massive-cryptocurrency-widgets"); ?>
                </label>
                <label for="crypto_ticker4" class="form-radio">
                    <input type="radio" class="beaut-radio" name="type" id="crypto_ticker4" value="card" <?php if ($options['type'] == 'card') { echo 'checked'; } ?> /><i class="form-icon"></i> <?php _e("Card", "massive-cryptocurrency-widgets"); ?>
                </label>
                <label for="crypto_ticker5" class="form-radio">
                    <input type="radio" class="beaut-radio" name="type" id="crypto_ticker5" value="label" <?php if ($options['type'] == 'label') { echo 'checked'; } ?> /><i class="form-icon"></i> <?php _e("Label", "massive-cryptocurrency-widgets"); ?>
                </label>
                <label for="crypto_ticker8" class="form-radio">
                    <input type="radio" class="beaut-radio" name="type" id="crypto_ticker8" value="converter" <?php if ($options['type'] == 'converter') { echo 'checked'; } ?> /><i class="form-icon"></i> <?php _e("Converter", "massive-cryptocurrency-widgets"); ?>
                </label>
                <label for="crypto_ticker6" class="form-radio">
                    <input type="radio" class="beaut-radio" name="type" id="crypto_ticker6" value="box" <?php if ($options['type'] == 'box') { echo 'checked'; } ?> /><i class="form-icon"></i> <?php _e("Box", "massive-cryptocurrency-widgets"); ?>
                </label>
                <label for="crypto_ticker7" class="form-radio">
                    <input type="radio" class="beaut-radio" name="type" id="crypto_ticker7" value="list" <?php if ($options['type'] == 'list') { echo 'checked'; } ?> /><i class="form-icon"></i> <?php _e("List", "massive-cryptocurrency-widgets"); ?>
                </label>
                <label for="crypto_ticker10" class="form-radio">
                    <input type="radio" class="beaut-radio" name="type" id="crypto_ticker10" value="changelly" <?php if ($options['type'] == 'changelly') { echo 'checked'; } ?> /><i class="form-icon"></i> <?php _e("Changelly", "massive-cryptocurrency-widgets"); ?>
                </label>
                <label for="crypto_ticker11" class="form-radio">
                    <input type="radio" class="beaut-radio" name="type" id="crypto_ticker11" value="multicurrency" <?php if ($options['type'] == 'multicurrency') { echo 'checked'; } ?> /><i class="form-icon"></i> <?php _e("Multicurrency", "massive-cryptocurrency-widgets"); ?>
                </label>
                <label for="crypto_ticker12" class="form-radio">
                    <input type="radio" class="beaut-radio" name="type" id="crypto_ticker12" value="news" <?php if ($options['type'] == 'news') { echo 'checked'; } ?> /><i class="form-icon"></i> <?php _e("News", "massive-cryptocurrency-widgets"); ?>
                </label>
            </div>
        </div>

        <div class="crypto-rows crypto-toggle news-position<?php if ($options['type'] !== 'news') { echo ' cc-hide'; } ?>">
            <div class="crypto-cols crypto-labels">
                <label for="crypto_news_feeds"><?php _e("Feeds URL (One per line)", "massive-cryptocurrency-widgets"); ?></label>
            </div>
            <div class="crypto-cols">
                <textarea name="news_feeds" class="selectize-input" id="" rows="2"><?php echo str_replace("rn", "\n", $options['news_feeds']); ?></textarea>
            </div>
        </div>

        <div class="crypto-rows crypto-toggle news-position<?php if ($options['type'] !== 'news') { echo ' cc-hide'; } ?>">
            <div class="crypto-cols crypto-labels">
                <label for="crypto_news_count"><?php _e("No of News", "massive-cryptocurrency-widgets"); ?></label>
            </div>
            <div class="crypto-cols">
                <input name="news_count" type="text" class="selectize-input" value="<?php echo $options['news_count']; ?>">
            </div>
        </div>

        <div class="crypto-rows crypto-toggle news-position<?php if ($options['type'] !== 'news') { echo ' cc-hide'; } ?>">
            <div class="crypto-cols crypto-labels">
                <label for="crypto_news_length"><?php _e("Content Length (Words)", "massive-cryptocurrency-widgets"); ?></label>
            </div>
            <div class="crypto-cols">
                <input name="news_length" type="text" class="selectize-input" value="<?php echo $options['news_length']; ?>">
            </div>
        </div>

        <div class="crypto-rows crypto-toggle multicurrency-position<?php if ($options['type'] !== 'multicurrency') { echo ' cc-hide'; } ?>">
            <div class="crypto-cols crypto-labels">
                <label for="crypto_ticker_currencies"><?php _e("Currencies", "massive-cryptocurrency-widgets"); ?> <a class="removecur" style="float: right;"><?php _e("Clear", "massive-cryptocurrency-widgets"); ?></a></label>
            </div>
            <div class="crypto-cols">
                <select name="multi_currencies[]" id="currency-select" multiple>
                    <option value=""><?php _e("Select currencies", "massive-cryptocurrency-widgets"); ?></option>
                    <?php
                        $mcw_currencies = $this->get_currencies();
                        $remaining = array_diff_key((array) $mcw_currencies, $options['multi_currencies']);

                        foreach($options['multi_currencies'] as $currency) {
                            echo '<option value="' . $currency . '" selected>' . $currency . '</option>';
                        }
                        foreach($remaining as $key => $value) {
                            echo '<option value="' . $key . '">' . $key . '</option>';
                        }
                    ?>
                </select>
            </div>
        </div>

        <div class="crypto-rows crypto-toggle all-position changelly-not-position news-not-position<?php if ($options['type'] === 'changelly' || $options['type'] === 'news') { echo ' cc-hide'; } ?>">
            <div class="crypto-cols crypto-labels">
                <label for="crypto_ticker_coin"><?php _e("Coin(s)", "massive-cryptocurrency-widgets"); ?> <a class="removecoins" style="float: right;"><?php _e("Clear", "massive-cryptocurrency-widgets"); ?></a></label>
            </div>

            <div class="crypto-cols">
                <select id="select-beast" name="coins[]" multiple>
                    <option value=""><?php _e("Select coin(s)", "massive-cryptocurrency-widgets"); ?></option>
                    <?php
                    $mcw_coinsyms = $this->mcw_coinsyms();
                    $coins = array_intersect($options['coins'], array_keys($mcw_coinsyms));
                    $remaining = array_diff_key($mcw_coinsyms, $coins);

                    foreach($coins as $coin) {
                        echo '<option value="' . $coin . '" selected data-extra=\'{ "symbol": "' . strtolower($mcw_coinsyms[$coin]['symbol']) . '" }\'>' . $mcw_coinsyms[$coin]['name'] . '</option>';
                    }
                    foreach($remaining as $key => $value) {
                        echo '<option value="' . $key . '" data-extra=\'{ "symbol": "' . strtolower($value['symbol']) . '" }\'>' . $value['name'] . '</option>';
                    }
                    ?>
                </select>
            </div>
            <br><br>

            <div class="crypto-cols crypto-toggle ticker-position table-position converter-position list-position<?php if ($options['type'] !== 'converter' && $options['type'] !== 'ticker' && $options['type'] !== 'table' && $options['type'] !== 'list') { echo ' cc-hide'; } ?>">
                <?php _e("or show top"); ?> <select name="numcoins" id="" class="selectize-select" style="width: 10ch;">
                    <option value=""<?php if (intval($options['numcoins']) == 0) { echo ' selected'; } ?>></option>
                    <option value="10"<?php if (intval($options['numcoins']) == 10) { echo ' selected'; } ?>>10</option>
                    <option value="50"<?php if (intval($options['numcoins']) == 50) { echo ' selected'; } ?>>50</option>
                    <option value="100"<?php if (intval($options['numcoins']) == 100) { echo ' selected'; } ?>>100</option>
                    <option value="200"<?php if (intval($options['numcoins']) == 200) { echo ' selected'; } ?>>200</option>
                    <option value="500"<?php if (intval($options['numcoins']) == 500) { echo ' selected'; } ?>>500</option>
                    <option value="1000"<?php if (intval($options['numcoins']) == 1000) { echo ' selected'; } ?>>1000</option>
                    <option value="2000"<?php if (intval($options['numcoins']) == 2000) { echo ' selected'; } ?>><?php echo sizeof($mcw_coinsyms); ?></option>
                </select> <?php _e("coins", "massive-cryptocurrency-widgets"); ?>
            </div>
        </div>

        <div class="crypto-rows crypto-radio changelly-position<?php if ($options['type'] !== 'changelly') { echo ' cc-hide'; } ?>">
            <div class="crypto-cols crypto-labels">
				<label for="text_color"><?php _e("Theme", "massive-cryptocurrency-widgets"); ?></label>
			</div>
			<div class="crypto-cols no-padding">
                <label data-tooltip="<?php _e("Default", "massive-cryptocurrency-widgets"); ?>" for="changelly-theme1" class="form-radio<?php if ($options['changelly_theme'] == 'default') { echo ' cc-active'; } ?>">
                    <input type="radio" class="beaut-radio" name="changelly_theme" id="changelly-theme1" value="default" <?php if ($options['changelly_theme'] == 'default') { echo 'checked'; } ?> /><span style="background: #10d078;"></span>
                </label>
                <label data-tooltip="<?php _e("Aqua", "massive-cryptocurrency-widgets"); ?>" for="changelly-theme2" class="form-radio<?php if ($options['changelly_theme'] == 'aqua') { echo ' cc-active'; } ?>">
                    <input type="radio" class="beaut-radio" name="changelly_theme" id="changelly-theme2" value="aqua" <?php if ($options['changelly_theme'] == 'aqua') { echo 'checked'; } ?> /><span style="background: #00a4ff;"></span>
                </label>
                <label data-tooltip="<?php _e("Danger", "massive-cryptocurrency-widgets"); ?>" for="changelly-theme3" class="form-radio<?php if ($options['changelly_theme'] == 'danger') { echo ' cc-active'; } ?>">
                    <input type="radio" class="beaut-radio" name="changelly_theme" id="changelly-theme3" value="danger" <?php if ($options['changelly_theme'] == 'danger') { echo 'checked'; } ?> /><span style="background: #ff675f;"></span>
                </label>
                <label data-tooltip="<?php _e("Jungle", "massive-cryptocurrency-widgets"); ?>" for="changelly-theme4" class="form-radio<?php if ($options['changelly_theme'] == 'jungle') { echo ' cc-active'; } ?>">
                    <input type="radio" class="beaut-radio" name="changelly_theme" id="changelly-theme4" value="jungle" <?php if ($options['changelly_theme'] == 'jungle') { echo 'checked'; } ?> /><span style="background: #5fc294;"></span>
                </label>
                <label data-tooltip="<?php _e("Deep Purple", "massive-cryptocurrency-widgets"); ?>" for="changelly-theme5" class="form-radio<?php if ($options['changelly_theme'] == 'deep-purple') { echo ' cc-active'; } ?>">
                    <input type="radio" class="beaut-radio" name="changelly_theme" id="changelly-theme5" value="deep-purple" <?php if ($options['changelly_theme'] == 'deep-purple') { echo 'checked'; } ?> /><span style="background: #8a5cdc;"></span>
                </label>
                <label data-tooltip="<?php _e("Desert", "massive-cryptocurrency-widgets"); ?>" for="changelly-theme6" class="form-radio<?php if ($options['changelly_theme'] == 'desert') { echo ' cc-active'; } ?>">
                    <input type="radio" class="beaut-radio" name="changelly_theme" id="changelly-theme6" value="desert" <?php if ($options['changelly_theme'] == 'desert') { echo 'checked'; } ?> /><span style="background: #eba300;"></span>
                </label>
                <label data-tooltip="<?php _e("Barbie", "massive-cryptocurrency-widgets"); ?>" for="changelly-theme7" class="form-radio<?php if ($options['changelly_theme'] == 'barbie') { echo ' cc-active'; } ?>">
                    <input type="radio" class="beaut-radio" name="changelly_theme" id="changelly-theme7" value="barbie" <?php if ($options['changelly_theme'] == 'barbie') { echo 'checked'; } ?> /><span style="background: #e647de;"></span>
                </label>
                <label data-tooltip="<?php _e("Azure", "massive-cryptocurrency-widgets"); ?>" for="changelly-theme8" class="form-radio<?php if ($options['changelly_theme'] == 'azure') { echo ' cc-active'; } ?>">
                    <input type="radio" class="beaut-radio" name="changelly_theme" id="changelly-theme8" value="azure" <?php if ($options['changelly_theme'] == 'azure') { echo 'checked'; } ?> /><span style="background: #33d8e0;"></span>
                </label>
			</div>
        </div>

        <div class="crypto-rows crypto-toggle changelly-position<?php if ($options['type'] !== 'changelly') { echo ' cc-hide'; } ?>">
            <div class="crypto-cols crypto-labels">
                <label for="crypto_changelly_amount"><?php _e("Default Amount", "massive-cryptocurrency-widgets"); ?></label>
            </div>
            <div class="crypto-cols">
                <input type="text" name="changelly_amount" class="selectize-input" value="<?php echo $options['changelly_amount']; ?>">
            </div>
        </div>

        <div class="crypto-rows crypto-toggle changelly-position<?php if ($options['type'] !== 'changelly') { echo ' cc-hide'; } ?>">
            <div class="crypto-cols crypto-labels">
                <label for="crypto_changelly_from"><?php _e("You Send", "massive-cryptocurrency-widgets"); ?> <a class="remove-changelly-send" style="float: right;"><?php _e("Clear", "massive-cryptocurrency-widgets"); ?></a></label>
            </div>
            <div class="crypto-cols">
                <select id="changelly-send" name="changelly_send[]" multiple>
                <?php
                    $chlycoins = array_merge($this->changelly['fiat'], $this->changelly['crypto']);
                    $remaining = array_diff(array_keys($chlycoins), $options['changelly_send']);

                    foreach($options['changelly_send'] as $coin) {
                        echo '<option value="' . $coin . '" selected>' . $chlycoins[$coin] . '</option>';
                    }
                    foreach($remaining as $coin) {
                        echo '<option value="' . $coin . '">' . $chlycoins[$coin] . '</option>';
                    }
                ?>
                </select>
            </div>
            <div class="crypto-cols">
                <label class="form-switch" for="changelly-send-all">
                    <input type="checkbox" name="changelly_send_all" id="changelly-send-all" value="yes" <?php if ($options['changelly_send_all'] == 'yes') { echo "checked";} ?> />
                    <i class="form-icon"></i><?php _e("Select all", "massive-cryptocurrency-widgets"); ?>
                </label>
            </div>
        </div>

         <div class="crypto-rows crypto-toggle changelly-position<?php if ($options['type'] !== 'changelly') { echo ' cc-hide'; } ?>">
            <div class="crypto-cols crypto-labels">
                <label for="crypto_changelly_to"><?php _e("You Receive", "massive-cryptocurrency-widgets"); ?> <a class="remove-changelly-receive" style="float: right;"><?php _e("Clear", "massive-cryptocurrency-widgets"); ?></a></label>
            </div>
            <div class="crypto-cols">
                <select id="changelly-receive" name="changelly_receive[]" multiple>
                    <?php
                        $chlycoins = array_merge($this->changelly['fiat'], $this->changelly['crypto']);
                        $remaining = array_diff(array_keys($chlycoins), $options['changelly_receive']);

                        foreach($options['changelly_receive'] as $coin) {
                            echo '<option value="' . $coin . '" selected>' . $chlycoins[$coin] . '</option>';
                        }
                        foreach($remaining as $coin) {
                            echo '<option value="' . $coin . '">' . $chlycoins[$coin] . '</option>';
                        }
                    ?>
                </select>
            </div>
            <div class="crypto-cols">
                <label class="form-switch" for="changelly-receive-all">
                    <input type="checkbox" name="changelly_receive_all" id="changelly-receive-all" value="yes" <?php if ($options['changelly_receive_all'] == 'yes') { echo "checked";} ?> />
                    <i class="form-icon"></i><?php _e("Select all", "massive-cryptocurrency-widgets"); ?>
                </label>
            </div>
        </div>

        <div class="crypto-rows crypto-toggle changelly-position<?php if ($options['type'] !== 'changelly') { echo ' cc-hide'; } ?>">
            <div class="crypto-cols crypto-labels">
                <label for="crypto_changelly_id"><?php _e("Affiliate Link", "massive-cryptocurrency-widgets"); ?></label>
            </div>
            <div class="crypto-cols">
                <input type="text" class="selectize-input" name="changelly_link" value="<?php echo $options['changelly_link']; ?>">
            </div>
        </div>

        <div class="crypto-rows crypto-toggle ticker-position<?php if ($options['type'] !== 'ticker') { echo ' cc-hide'; } ?>">
            <div class="crypto-cols crypto-labels">
                <label for="crypto_ticker_position"><?php _e("Ticker Position", "massive-cryptocurrency-widgets"); ?></label>
            </div>
            <div class="crypto-cols no-padding" style="display: flex;">
                <label for="ticker_position1" class="form-radio ticker-position-label<?php if ($options['ticker_position'] == 'header') { echo ' selected'; } ?>">
                    <input type="radio" class="beaut-radio" name="ticker_position" id="ticker_position1" value="header" <?php if ($options['ticker_position'] == 'header') { echo ' checked'; } ?> />
                    <img src="<?php echo MCW_URL; ?>/assets/admin/img/card1.png" alt="">
                    <img class="hover-img" src="<?php echo MCW_URL; ?>/assets/admin/img/card1hover.png" alt="">
                </label>
                <label for="ticker_position2" class="form-radio ticker-position-label<?php if ($options['ticker_position'] == 'footer') { echo ' selected'; } ?>">
                    <input type="radio" class="beaut-radio" name="ticker_position" id="ticker_position2" value="footer" <?php if ($options['ticker_position'] == 'footer') { echo ' checked'; } ?> />
                    <img src="<?php echo MCW_URL; ?>/assets/admin/img/card2.png" alt="">
                    <img class="hover-img" src="<?php echo MCW_URL; ?>/assets/admin/img/card2hover.png" alt="">
                </label>
                <label for="ticker_position3" class="form-radio ticker-position-label<?php if ($options['ticker_position'] == 'same') { echo ' selected'; } ?>">
                    <input type="radio" class="beaut-radio" name="ticker_position" id="ticker_position3" value="same" <?php if ($options['ticker_position'] == 'same') { echo ' checked'; } ?> />
                    <img src="<?php echo MCW_URL; ?>/assets/admin/img/card3.png" alt="">
                    <img class="hover-img" src="<?php echo MCW_URL; ?>/assets/admin/img/card3hover.png" alt="">
                </label>
            </div>
        </div>

        <div class="crypto-rows crypto-toggle ticker-position<?php if($options['type'] !== "ticker") { echo ' cc-hide'; } ?>">
            <div class="crypto-cols crypto-labels">
                <label for="ticker_design"><?php _e("Ticker Design", "massive-cryptocurrency-widgets"); ?></label>
            </div>
            <select name="ticker_design" id="ticker_design" class="selectize-select">
				<option value="1"<?php if ($options['ticker_design'] == 1) { echo ' selected'; } ?>><?php _e("Design", "massive-cryptocurrency-widgets"); ?> 1</option>
				<option value="2"<?php if ($options['ticker_design'] == 2) { echo ' selected'; } ?>><?php _e("Design", "massive-cryptocurrency-widgets"); ?> 2</option>
            </select>
        </div>

        <div class="crypto-rows crypto-radio crypto-toggle ticker-position card-position label-position box-position list-position multicurrency-position<?php if (!in_array($options['type'], array('ticker', 'card', 'label', 'box', 'list', 'multicurrency'))) { echo ' cc-hide'; } ?>">
            <div class="crypto-cols crypto-labels">
                <label for="crypto_theme"><div class="widgetname"><?php echo ucfirst($options['type']); ?></div> <?php echo _e("Theme", "massive-cryptocurrency-widgets"); ?></label>
            </div>
            <div class="crypto-cols no-padding">
                <label data-tooltip="<?php _e("White", "massive-cryptocurrency-widgets"); ?>" for="theme1" class="dark-check form-radio<?php if ($options['theme'] == 'white') { echo ' cc-active'; } ?>">
                    <input type="radio" class="beaut-radio" name="theme" id="theme1" value="white" <?php if ($options['theme'] == 'white') { echo 'checked'; } ?> /><span style="background: #fff;"></span>
                </label>
                <label data-tooltip="<?php _e("Dark", "massive-cryptocurrency-widgets"); ?>" for="theme2" class="form-radio<?php if ($options['theme'] == 'dark') { echo ' cc-active'; } ?>">
                    <input type="radio" class="beaut-radio" name="theme" id="theme2" value="dark" <?php if ($options['theme'] == 'dark') { echo 'checked'; } ?> /><span style="background: #000;"></span>
                </label>
                <label data-tooltip="<?php _e("Midnight", "massive-cryptocurrency-widgets"); ?>" for="theme3" class="form-radio<?php if ($options['theme'] == 'midnight') { echo ' cc-active'; } ?>">
                    <input type="radio" class="beaut-radio" name="theme" id="theme3" value="midnight" <?php if ($options['theme'] == 'midnight') { echo 'checked'; } ?> /><span style="background: #293145;"></span>
                </label>
                <label data-tooltip="<?php _e("Blue", "massive-cryptocurrency-widgets"); ?>" for="theme4" class="form-radio<?php if ($options['theme'] == 'info') { echo ' cc-active'; } ?>">
                    <input type="radio" class="beaut-radio" name="theme" id="theme4" value="info" <?php if ($options['theme'] == 'info') { echo 'checked'; } ?> /><span style="background: #5073F5;"></span>
                </label>
                <label data-tooltip="<?php _e("Orange", "massive-cryptocurrency-widgets"); ?>" for="theme5" class="form-radio<?php if ($options['theme'] == 'warning') { echo ' cc-active'; } ?>">
                    <input type="radio" class="beaut-radio" name="theme" id="theme5" value="warning" <?php if ($options['theme'] == 'warning') { echo 'checked'; } ?> /><span style="background: #ff7d19;"></span>
                </label>
                <label data-tooltip="<?php _e("Red", "massive-cryptocurrency-widgets"); ?>" for="theme6" class="form-radio<?php if ($options['theme'] == 'danger') { echo ' cc-active'; } ?>">
                    <input type="radio" class="beaut-radio" name="theme" id="theme6" value="danger" <?php if ($options['theme'] == 'danger') { echo 'checked'; } ?> /><span style="background: #FF0033;"></span>
                </label>
                <label data-tooltip="<?php _e("Green", "massive-cryptocurrency-widgets"); ?>" for="theme7" class="form-radio<?php if ($options['theme'] == 'success') { echo ' cc-active'; } ?>">
                    <input type="radio" class="beaut-radio" name="theme" id="theme7" value="success" <?php if ($options['theme'] == 'success') { echo 'checked'; } ?> /><span style="background: #85B000;"></span>
                </label>
                <label data-tooltip="<?php _e("Custom", "massive-cryptocurrency-widgets"); ?>" for="theme8" class="custom-color form-radio<?php if ($options['theme'] == 'custom') { echo ' cc-active'; } ?>">
                    <input type="radio" class="beaut-radio" name="theme" id="theme8" value="custom" <?php if ($options['theme'] == 'custom') { echo 'checked'; } ?> /><span></span>
                </label>
            </div>
        </div>

        <div class="crypto-rows crypto-toggle ticker-position<?php if ($options['type'] !== 'ticker') { echo ' cc-hide'; } ?>">
            <div class="crypto-cols crypto-labels">
                <label for="crypto_speed"><?php echo __("Ticker", "massive-cryptocurrency-widgets") . " " . __("Speed", "massive-cryptocurrency-widgets"); ?></label>
            </div>
            <div class="crypto-cols range-slider">
                <input name="ticker_speed" id="crypto_speed" class="range-slider__range" type="range" step="5" value="<?php echo $options['ticker_speed'] ?>" min="0" max="200">
                <span class="range-slider__value" data-suffix="%"><?php echo $options['ticker_speed']; ?>%</span>
            </div>
        </div>

        <div class="crypto-rows crypto-radio crypto-toggle table-position<?php if ($options['type'] !== 'table') { echo ' cc-hide'; } ?>">
            <div class="crypto-cols crypto-labels">
                <label for="crypto_table_style"><?php echo __("Table", "massive-cryptocurrency-widgets") . " " . __("Style", "massive-cryptocurrency-widgets"); ?></label>
            </div>
            <div class="crypto-cols no-padding">
                <label data-tooltip="<?php _e("Light", "massive-cryptocurrency-widgets"); ?>" for="table_style1" class="dark-check form-radio<?php if ($options['table_style'] == 'light') { echo ' cc-active'; } ?>">
                    <input type="radio" class="beaut-radio" name="table_style" id="table_style1" value="light" <?php if ($options['table_style'] == 'light') { echo 'checked'; } ?> /><span style="background: #fff;"></span>
                </label>
                <label data-tooltip="<?php _e("Dark", "massive-cryptocurrency-widgets"); ?>" for="table_style2" class="form-radio<?php if ($options['table_style'] == 'dark') { echo ' cc-active'; } ?>">
                    <input type="radio" class="beaut-radio" name="table_style" id="table_style2" value="dark" <?php if ($options['table_style'] == 'dark') { echo 'checked'; } ?> /><span style="background: #333;"></span>
                </label>
            </div>
        </div>

        <div class="crypto-rows crypto-toggle table-position<?php if ($options['type'] !== 'table') { echo ' cc-hide'; } ?>">
            <div class="crypto-cols crypto-labels">
                <label for="table_length"><?php _e("Coins Per Page", "massive-cryptocurrency-widgets"); ?></label>
            </div>
            <div class="crypto-cols range-slider">
                    <input name="table_length" id="table_length" class="range-slider__range" type="range" step="5" value="<?php echo $options['table_length']; ?>" min="5" max="2000">
                    <span class="range-slider__value" data-suffix=""><?php echo $options['table_length']; ?></span>
            </div>
        </div>

        <div class="crypto-rows crypto-toggle table-position<?php if ($options['type'] !== 'table') { echo ' cc-hide'; } ?>">
            <div class="crypto-cols crypto-labels">
                <label for="table_length"><?php echo __("Table", "massive-cryptocurrency-widgets") . " " . __("Columns", "massive-cryptocurrency-widgets"); ?> <a class="removecols" style="float: right;"><?php _e("Clear", "massive-cryptocurrency-widgets"); ?></a></label>
            </div>
            <div class="crypto-cols">
                <select id="mcw_tablecols" name="table_columns[]" multiple>

                    <?php
                    
                        $fields = ['rank', 'name', 'symbol', 'price_usd', 'price_btc', 'market_cap_usd', 'volume_usd_24h', 'available_supply', 'percent_change_24h', 'weekly'];

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

                        $remaining = array_diff($fields, $options['table_columns']);

                        foreach($options['table_columns'] as $column) {
                            echo '<option value="' . $column . '" selected>' . $colnames[$column] . '</option>';
                        }
                        foreach($remaining as $rem) {
                            echo '<option value="' . $rem . '">' . $colnames[$rem] . '</option>';
                        }

                    ?>
                </select>
            </div>
        </div>

        <div class="crypto-rows crypto-toggle chart-position<?php if ($options['type'] !== 'chart') { echo ' cc-hide'; } ?>">
            <div class="crypto-cols crypto-labels">
                <label for="chart_type"><?php _e("Chart Type", "massive-cryptocurrency-widgets"); ?></label>
            </div>
            <div class="crypto-cols">
            <label for="chart_type1" class="form-radio">
                <input type="radio" class="beaut-radio" name="chart_type" id="chart_type1" value="chart" <?php if ($options['chart_type'] == 'chart') { echo 'checked'; } ?> /><i class="form-icon"></i><?php _e("Line", "massive-cryptocurrency-widgets"); ?>
            </label>
            <label for="chart_type2" class="form-radio">
                <input type="radio" class="beaut-radio" name="chart_type" id="chart_type2" value="candlestick" <?php if ($options['chart_type'] == 'candlestick') { echo 'checked'; } ?> /><i class="form-icon"></i><?php _e("Candlestick", "massive-cryptocurrency-widgets"); ?>
            </label>
            </div>
        </div>

        <div class="crypto-rows crypto-toggle chart-position<?php if ($options['type'] !== 'chart') { echo ' cc-hide'; } ?>">
            <div class="crypto-cols crypto-labels">
                <label for="chart_view"><?php _e("Default Chart View", "massive-cryptocurrency-widgets"); ?></label>
            </div>
            <div class="crypto-cols">
                <label for="chart_view1" class="form-radio">
                    <input type="radio" class="beaut-radio" name="chart_view" id="chart_view1" value="day" <?php if ($options['chart_view'] == 'day') { echo 'checked'; } ?> /><i class="form-icon"></i>1 <?php _e("Day", "massive-cryptocurrency-widgets"); ?>
                </label>
                <label for="chart_view2" class="form-radio">
                    <input type="radio" class="beaut-radio" name="chart_view" id="chart_view2" value="week" <?php if ($options['chart_view'] == 'week') { echo 'checked'; } ?> /><i class="form-icon"></i>1 <?php _e("Week", "massive-cryptocurrency-widgets"); ?>
                </label>
                <label for="chart_view3" class="form-radio">
                    <input type="radio" class="beaut-radio" name="chart_view" id="chart_view3" value="month" <?php if ($options['chart_view'] == 'month') { echo 'checked'; } ?> /><i class="form-icon"></i>1 <?php _e("Month", "massive-cryptocurrency-widgets"); ?>
                </label>
                <label for="chart_view4" class="form-radio">
                    <input type="radio" class="beaut-radio" name="chart_view" id="chart_view4" value="threemonths" <?php if ($options['chart_view'] == 'threemonths') { echo 'checked'; } ?> /><i class="form-icon"></i>3 <?php _e("Months", "massive-cryptocurrency-widgets"); ?>
                </label>
                <label for="chart_view5" class="form-radio">
                    <input type="radio" class="beaut-radio" name="chart_view" id="chart_view5" value="sixmonths" <?php if ($options['chart_view'] == 'sixmonths') { echo 'checked'; } ?> /><i class="form-icon"></i>6 <?php _e("Months", "massive-cryptocurrency-widgets"); ?>
                </label>
                <label for="chart_view6" class="form-radio">
                    <input type="radio" class="beaut-radio" name="chart_view" id="chart_view6" value="year" <?php if ($options['chart_view'] == 'year') { echo 'checked'; } ?> /><i class="form-icon"></i>1 <?php _e("Year", "massive-cryptocurrency-widgets"); ?>
                </label>
            </div>
        </div>

        <div class="crypto-rows crypto-radio crypto-toggle chart-position<?php if ($options['type'] !== 'chart') { echo ' cc-hide'; } ?>">
            <div class="crypto-cols crypto-labels">
                <label for="crypto_chart_theme"><?php _e("Chart Theme", "massive-cryptocurrency-widgets"); ?></label>
            </div>
            <div class="crypto-cols no-padding">
                <label data-tooltip="<?php _e("Light", "massive-cryptocurrency-widgets"); ?>" for="chart_theme1" class="dark-check form-radio<?php if ($options['chart_theme'] == 'light') { echo ' cc-active'; } ?>">
                    <input type="radio" class="beaut-radio" name="chart_theme" id="chart_theme1" value="light" <?php if ($options['chart_theme'] == 'light') { echo 'checked'; } ?> /><span style="background: #fff;"></span>
                </label>
                <label data-tooltip="<?php _e("Dark", "massive-cryptocurrency-widgets"); ?>" for="chart_theme2" class="form-radio<?php if ($options['chart_theme'] == 'dark') { echo ' cc-active'; } ?>">
                    <input type="radio" class="beaut-radio" name="chart_theme" id="chart_theme2" value="dark" <?php if ($options['chart_theme'] == 'dark') { echo 'checked'; } ?> /><span style="background: #000;"></span>
                </label>
                <label data-tooltip="<?php _e("Light + Custom", "massive-cryptocurrency-widgets"); ?>" for="chart_theme3" class="custom-color form-radio<?php if ($options['chart_theme'] == 'lightcustom') { echo ' cc-active'; } ?>">
                    <input type="radio" class="beaut-radio" name="chart_theme" id="chart_theme3" value="lightcustom" <?php if ($options['chart_theme'] == 'lightcustom') { echo 'checked'; } ?> /><span></span>
                </label>
                <label data-tooltip="<?php _e("Dark + Custom", "massive-cryptocurrency-widgets"); ?>" for="chart_theme4" class="custom-color form-radio<?php if ($options['chart_theme'] == 'darkcustom') { echo ' cc-active'; } ?>">
                    <input type="radio" class="beaut-radio" name="chart_theme" id="chart_theme4" value="darkcustom" <?php if ($options['chart_theme'] == 'darkcustom') { echo 'checked'; } ?> /><span></span>
                </label>
            </div>
        </div>

        <div class="crypto-rows crypto-toggle converter-position<?php if($options['type'] !== "converter") { echo ' cc-hide'; } ?>">
            <div class="crypto-cols crypto-labels">
                <label for="converter_type"><?php _e("Converter Type", "massive-cryptocurrency-widgets"); ?></label>
            </div>
            <div class="crypto-cols">
                <select id="converter-type" name="converter_type" class="selectize-select">
                    <option value="crypto-to-fiat"<?php if ($options['converter_type'] == 'crypto-to-fiat') { echo ' selected'; } ?>><?php _e("Crypto to Fiat", "massive-cryptocurrency-widgets"); ?></option>
                    <option value="fiat-to-crypto"<?php if ($options['converter_type'] == 'fiat-to-crypto') { echo ' selected'; } ?>><?php _e("Fiat to Crypto", "massive-cryptocurrency-widgets"); ?></option>
                    <option value="crypto-to-crypto"<?php if ($options['converter_type'] == 'crypto-to-crypto') { echo ' selected'; } ?>><?php _e("Crypto to Crypto", "massive-cryptocurrency-widgets"); ?></option>
                    <option value="fiat-to-fiat"<?php if ($options['converter_type'] == 'fiat-to-fiat') { echo ' selected'; } ?>><?php _e("Fiat to Fiat", "massive-cryptocurrency-widgets"); ?></option>
                </select>
            </div>
        </div>

        <div class="crypto-rows crypto-toggle card-position<?php if($options['type'] !== "card") { echo ' cc-hide'; } ?>">
            <div class="crypto-cols crypto-labels">
                <label for="card_design"><?php _e("Card Design", "massive-cryptocurrency-widgets"); ?></label>
            </div>
            <select name="card_design" id="card_design" class="selectize-select">
				<option value="1"<?php if ($options['card_design'] == 1) { echo ' selected'; } ?>><?php _e("Design", "massive-cryptocurrency-widgets"); ?> 1</option>
				<option value="2"<?php if ($options['card_design'] == 2) { echo ' selected'; } ?>><?php _e("Design", "massive-cryptocurrency-widgets"); ?> 2</option>
				<option value="3"<?php if ($options['card_design'] == 3) { echo ' selected'; } ?>><?php _e("Design", "massive-cryptocurrency-widgets"); ?> 3</option>
				<option value="4"<?php if ($options['card_design'] == 4) { echo ' selected'; } ?>><?php _e("Design", "massive-cryptocurrency-widgets"); ?> 4</option>
				<option value="5"<?php if ($options['card_design'] == 5) { echo ' selected'; } ?>><?php _e("Design", "massive-cryptocurrency-widgets"); ?> 5</option>
				<option value="6"<?php if ($options['card_design'] == 6) { echo ' selected'; } ?>><?php _e("Design", "massive-cryptocurrency-widgets"); ?> 6</option>
				<option value="7"<?php if ($options['card_design'] == 7) { echo ' selected'; } ?>><?php _e("Design", "massive-cryptocurrency-widgets"); ?> 7</option>
            </select>
        </div>
        
        <div class="crypto-rows crypto-toggle label-position<?php if($options['type'] !== "label") { echo ' cc-hide'; } ?>">
            <div class="crypto-cols crypto-labels">
                <label for="label_design"><?php _e("Label Design", "massive-cryptocurrency-widgets"); ?></label>
            </div>
            <select name="label_design" id="label_design" class="selectize-select">
				<option value="1"<?php if ($options['label_design'] == 1) { echo ' selected'; } ?>><?php _e("Design", "massive-cryptocurrency-widgets"); ?> 1</option>
				<option value="2"<?php if ($options['label_design'] == 2) { echo ' selected'; } ?>><?php _e("Design", "massive-cryptocurrency-widgets"); ?> 2</option>
				<option value="3"<?php if ($options['label_design'] == 3) { echo ' selected'; } ?>><?php _e("Design", "massive-cryptocurrency-widgets"); ?> 3</option>
            </select>
        </div>

        <div class="crypto-rows crypto-toggle box-position<?php if($options['type'] !== "box") { echo ' cc-hide'; } ?>">
            <div class="crypto-cols crypto-labels">
                <label for="box_design"><?php _e("Box Design", "massive-cryptocurrency-widgets"); ?></label>
            </div>
            <select name="box_design" id="box_design" class="selectize-select">
				<option value="1"<?php if ($options['box_design'] == 1) { echo ' selected'; } ?>><?php _e("Design", "massive-cryptocurrency-widgets"); ?> 1</option>
				<option value="2"<?php if ($options['box_design'] == 2) { echo ' selected'; } ?>><?php _e("Design", "massive-cryptocurrency-widgets"); ?> 2</option>
				<option value="3"<?php if ($options['box_design'] == 3) { echo ' selected'; } ?>><?php _e("Design", "massive-cryptocurrency-widgets"); ?> 3</option>
				<option value="4"<?php if ($options['box_design'] == 4) { echo ' selected'; } ?>><?php _e("Design", "massive-cryptocurrency-widgets"); ?> 4</option>
				<option value="5"<?php if ($options['box_design'] == 5) { echo ' selected'; } ?>><?php _e("Design", "massive-cryptocurrency-widgets"); ?> 5</option>
				<option value="6"<?php if ($options['box_design'] == 6) { echo ' selected'; } ?>><?php _e("Design", "massive-cryptocurrency-widgets"); ?> 6</option>
            </select>
        </div>

        <div class="crypto-rows crypto-toggle list-position<?php if($options['type'] !== "list") { echo ' cc-hide'; } ?>">
            <div class="crypto-cols crypto-labels">
                <label for="list_design"><?php _e("List Design", "massive-cryptocurrency-widgets"); ?></label>
            </div>
            <select name="list_design" id="list_design" class="selectize-select">
				<option value="1"<?php if ($options['list_design'] == 1) { echo ' selected'; } ?>><?php _e("Design", "massive-cryptocurrency-widgets"); ?> 1</option>
				<option value="2"<?php if ($options['list_design'] == 2) { echo ' selected'; } ?>><?php _e("Design", "massive-cryptocurrency-widgets"); ?> 2</option>
				<option value="3"<?php if ($options['list_design'] == 3) { echo ' selected'; } ?>><?php _e("Design", "massive-cryptocurrency-widgets"); ?> 3</option>
            </select>
        </div>

        <div class="crypto-rows crypto-toggle all-position changelly-not-position<?php if ($options['type'] === 'changelly') { echo ' cc-hide'; } ?>">
            <div class="crypto-cols crypto-labels">
				<label for="real_time"><?php _e("Options", "massive-cryptocurrency-widgets"); ?></label>
            </div>
            <div class="crypto-cols">
				<span class="crypto-toggle all-position changelly-not-position news-not-position<?php if ($options['type'] === 'changelly' || $options['type'] === 'news') { echo ' cc-hide'; } ?>">
                    <label for="real_time1" class="form-switch">
                        <input type="checkbox" name="real_time" id="real_time1" value="on" <?php if($options['real_time'] == 'on') { echo 'checked'; } ?> /><i class="form-icon"></i><?php _e("Real Time", "massive-cryptocurrency-widgets"); ?>
                    </label>
                </span>
                 <span class="crypto-toggle ticker-position card-position table-position label-position list-position multicurrency-position<?php if(!in_array($options['type'], array('ticker','table','card','label','list','multicurrency'))) { echo ' cc-hide'; }  ?>">
                    <label class="form-switch" for="ticker_columns2">
                        <input type="checkbox" name="ticker_columns[]" id="ticker_columns2" value="logo" <?php if (is_array($options['ticker_columns']) && in_array('logo', $options['ticker_columns'])) { echo "checked"; } ?> />
                        <i class="form-icon"></i><?php _e("Show Logos", "massive-cryptocurrency-widgets"); ?>
                    </label>
                </span>

                <span class="crypto-toggle ticker-position<?php if ($options['type'] !== 'ticker') { echo ' cc-hide'; } ?>">
                    <label class="form-switch" for="ticker_columns3">
                        <input type="checkbox" name="ticker_columns[]" id="ticker_columns3" value="round" <?php if(is_array($options['ticker_columns']) && in_array('round',$options['ticker_columns'], $strict = FALSE)) {echo "checked";} ?> />
                        <i class="form-icon"></i><?php _e("Rounded", "massive-cryptocurrency-widgets"); ?>
                    </label>
                </span>
                <span class="crypto-toggle ticker-position multicurrency-position<?php if ($options['type'] !== 'ticker' && $options['type'] !== 'multicurrency') { echo ' cc-hide'; } ?>">
                    <label class="form-switch" for="ticker_columns1">
                        <input type="checkbox" name="ticker_columns[]" id="ticker_columns1" value="changes" <?php if(is_array($options['ticker_columns']) && in_array('changes',$options['ticker_columns'], $strict = FALSE)) {echo "checked";} ?> />
                        <i class="form-icon"></i><?php _e("24h Change", "massive-cryptocurrency-widgets"); ?>
                    </label>
                </span>
                <span class="crypto-toggle chart-position<?php if ($options['type'] !== 'chart') { echo ' cc-hide'; } ?>">
                    <label for="chart_smooth1" class="form-switch">
                        <input type="checkbox" name="chart_smooth" id="chart_smooth1" value="true" <?php if ($options['chart_smooth'] == 'true') { echo 'checked'; } ?> /><i class="form-icon"></i><?php _e("Smooth Edges", "massive-cryptocurrency-widgets"); ?>
                    </label>
                </span>
                <span class="crypto-toggle card-position label-position<?php if($options['type'] !== "card" && $options['type'] !== 'label') { echo ' cc-hide'; } ?>">
                    <label class="form-switch" for="display_columns1">
                        <input type="checkbox" name="display_columns[]" id="display_columns1" value="fullwidth"<?php if(in_array('fullwidth', $options['display_columns'])) {echo " checked";} ?> />
                        <i class="form-icon"></i><?php _e("Full Width", "massive-cryptocurrency-widgets"); ?>
                    </label>
                </span>
                <span class="crypto-toggle card-position label-position box-position multicurrency-position<?php if($options['type'] !== "card" && $options['type'] !== 'label' && $options['type'] !== 'box' && $options['type'] !== 'multicurrency') { echo ' cc-hide'; } ?>">
                    <label class="form-switch" for="display_columns2">
                        <input type="checkbox" name="display_columns[]" id="display_columns2" value="rounded"<?php if(in_array('rounded', $options['display_columns'])) {echo " checked";} ?> />
				        <i class="form-icon"></i><?php _e("Round Corners", "massive-cryptocurrency-widgets"); ?>
                    </label>
                </span>
                <span class="crypto-toggle converter-position<?php if($options['type'] !== "converter") { echo ' cc-hide'; } ?>">
                    <label class="form-switch" for="converter_button">
                        <input type="checkbox" name="converter_button" id="converter_button" value="on" <?php if($options['converter_button'] == 'on') { echo "checked"; } ?> />
                        <i class="form-icon"></i><?php _e("Convert Button", "massive-cryptocurrency-widgets"); ?>
                    </label>
                </span>
                <span class="crypto-toggle news-position<?php if($options['type'] !== "news") { echo ' cc-hide'; } ?>">
                    <label class="form-switch" for="images">
                        <input type="checkbox" name="display_columns[]" id="images" value="images" <?php if(in_array('images', $options['display_columns'])) { echo "checked"; } ?> />
                        <i class="form-icon"></i><?php _e("Thumbnails", "massive-cryptocurrency-widgets"); ?>
                    </label>
                </span>
                <span class="crypto-toggle all-position chart-not-position converter-not-position changelly-not-position news-not-position<?php if ($options['type'] === 'chart' || $options['type'] === 'converter' || $options['type'] === 'changelly' || $options['type'] === 'news') { echo ' cc-hide'; } ?>">
                    <label class="form-switch" for="settings1">
                        <input type="checkbox" name="settings[]" id="settings1" value="linkto" <?php if(is_array($options['settings']) && in_array('linkto',$options['settings'], $strict = FALSE)) {echo "checked";} ?> />
                        <i class="form-icon"></i><?php _e("Link to coin pages", "massive-cryptocurrency-widgets"); ?>
                    </label>
                </span>
			</div>
        </div>

        <div class="crypto-rows crypto-toggle all-position changelly-not-position<?php if ($options['type'] === 'changelly') { echo ' cc-hide'; } ?>">
             <div class="crypto-cols crypto-labels">
				<label for="crypto_font"><?php _e("Font", "massive-cryptocurrency-widgets"); ?></label>
            </div>
            <div class="crypto-cols">
				<select name="font"  class="selectize-select">
                    <option value="inherit"<?php if ($options['font'] === 'inherit') { echo ' selected'; } ?>>Theme Default</option>
                    <?php foreach ($options['config']['fonts'] as $font) { ?>
                    <option value="<?php echo $font; ?>"<?php if ($options['font'] === $font) { echo ' selected'; } ?>><?php echo $font; ?></option>
                    <?php } ?>
				</select>
			</div>
        </div>

        <div class="crypto-rows crypto-toggle all-position changelly-not-position multicurrency-not-position news-not-position<?php if ($options['type'] === 'changelly' || $options['type'] === 'multicurrency' || $options['type'] === 'news') { echo ' cc-hide'; } ?>">
            <div class="crypto-cols crypto-labels">
				<label for="crypto_price_format"><?php _e("Marketcap Price Format", "massive-cryptocurrency-widgets"); ?></label>
            </div>
            <div class="crypto-cols">
				<select id="price-format" name="price_format"  class="selectize-select">
					<option value="1"<?php if ($options['price_format'] == '1') { echo ' selected'; } ?>><?php _e("Symbol", "massive-cryptocurrency-widgets"); ?> ($ 156 B)</option>
					<option value="3"<?php if ($options['price_format'] == '3') { echo ' selected'; } ?>><?php _e("Numbers", "massive-cryptocurrency-widgets"); ?> ($ 156,422,421,202)</option>
				</select>
			</div>
        </div>

        <div class="crypto-rows crypto-toggle all-position changelly-not-position multicurrency-not-position news-not-position<?php if ($options['type'] === 'changelly' || $options['type'] === 'multicurrency' || $options['type'] === 'news') { echo ' cc-hide'; } ?>">
            <div class="crypto-cols crypto-labels">
				<label for="crypto_currency_fiat"><?php _e("Currency", "massive-cryptocurrency-widgets"); ?></label>
            </div>
            <div class="crypto-cols">
				<select id="mcw-currencies" name="currency" class="selectize-select">
					<?php $mcw_currencies = $this->get_currencies();
					foreach($mcw_currencies as $key => $value) { ?>
						<option value="<?php echo $key; ?>"<?php if ($key == $options['currency']) { echo ' selected'; } ?>><?php echo $key; ?></option>
					<?php } ?>
				</select>
			</div>
        </div>

        <div class="crypto-rows crypto-toggle all-position changelly-not-position multicurrency-not-position news-not-position<?php if ($options['type'] === 'changelly' || $options['type'] === 'multicurrency' || $options['type'] === 'news') { echo ' cc-hide'; } ?>">
            <div class="crypto-cols crypto-labels">
				<label for="crypto_currency_2"><?php _e("Currency", "massive-cryptocurrency-widgets"); ?> 2</label>
            </div>
            <div class="crypto-cols">
				<select id="mcw-currencies" name="currency2" class="selectize-select">
					<?php $mcw_currencies = $this->get_currencies();
					foreach($mcw_currencies as $key => $value) { ?>
						<option value="<?php echo $key; ?>"<?php if ($key == $options['currency2']) { echo ' selected'; } ?>><?php echo $key; ?></option>
					<?php } ?>
				</select>
			</div>
        </div>

        <div class="crypto-rows crypto-toggle card-position label-position<?php if ($options['type'] !== 'card' && $options['type'] !== 'label') { echo ' cc-hide'; } ?>">
            <div class="crypto-cols crypto-labels">
				<label for="crypto_currency_2"><?php _e("Currency", "massive-cryptocurrency-widgets"); ?> 3</label>
            </div>
            <div class="crypto-cols">
				<select id="mcw-currencies" name="currency3" class="selectize-select">
					<?php $mcw_currencies = $this->get_currencies();
					foreach($mcw_currencies as $key => $value) { ?>
						<option value="<?php echo $key; ?>"<?php if ($key == $options['currency3']) { echo ' selected'; } ?>><?php echo $key; ?></option>
					<?php } ?>
				</select>
			</div>
        </div>

        <div class="crypto-rows crypto-toggle box-position table-position ticker-position list-position<?php if (!in_array($options['type'], array('ticker', 'table', 'box', 'list'))) { echo ' cc-hide'; } ?>">
            <div class="crypto-cols crypto-labels">
				<label for="chart_color"><?php _e("Chart Color", "massive-cryptocurrency-widgets"); ?></label>
            </div>
            <div class="crypto-cols">
				<input type="text" name="chart_color" value="<?php echo $options['chart_color']; ?>" class="selectize-input color-field">
			</div>
        </div>

        <div class="crypto-rows crypto-toggle all-position changelly-not-position news-not-position<?php if ($options['type'] === 'changelly' || $options['type'] === 'news') { echo ' cc-hide'; } ?>">
            <div class="crypto-cols crypto-labels">
				<label for="text_color"><?php _e("Custom Text Color", "massive-cryptocurrency-widgets"); ?></label>
			</div>
			<div class="crypto-cols">
                <input type="text" name="text_color" value="<?php echo $options['text_color']; ?>" class="selectize-input color-field" data-alpha="true">
			</div>
        </div>

        <div class="crypto-rows crypto-toggle all-position changelly-not-position news-not-position<?php if ($options['type'] === 'changelly' || $options['type'] === 'news') { echo ' cc-hide'; } ?>">
            <div class="crypto-cols crypto-labels">
				<label for="background_color"><?php _e("Custom Background Color", "massive-cryptocurrency-widgets"); ?></label>
            </div>
            <div class="crypto-cols">
				<input type="text" name="background_color" value="<?php echo $options['background_color']; ?>" class="selectize-input color-field" data-alpha="true">
			</div>
        </div>

    </div>

    <div class="crypto-preview">
        <div class="crypto-notice"><span class="micon-info-circled"></span> <?php _e("Publish or update to preview", "massive-cryptocurrency-widgets"); ?></div>
        <div class="crypto-affix">
            <?php echo do_shortcode('[mcrypto id="'.$post->ID.'"]'); ?>
        </div>
    </div>
</div>