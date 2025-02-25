<?php
$html_id = ct_get_element_id($settings);
$active_tab = $widget->get_setting('active_tab', 1);
$tabs = $widget->get_setting('tabs', '');
$is_new = \Elementor\Icons_Manager::is_migration_allowed();

if(isset($settings['tabs3']) && !empty($settings['tabs3']) && count($settings['tabs3'])): ?>
    <div class="ct-tabs ct-tab-banner3">
        <div class="ct-tabs-title">
            <?php foreach ($settings['tabs3'] as $key => $value): 
                $tab_title = isset($value['tab_title']) ? $value['tab_title'] : '';
                $tab_content = isset($value['tab_content']) ? $value['tab_content'] : '';
                $icon_key = $widget->get_repeater_setting_key( 'ct_icon', 'icons', $key );
                $has_icon = ! empty( $value['ct_icon'] );
                $widget->add_render_attribute( $icon_key, [
                    'class' => $value['ct_icon'],
                    'aria-hidden' => 'true',
                ] );
                ?>
                <div class="ct-tab-title <?php if($active_tab == $key + 1) { echo 'active'; } ?>" data-target="#<?php echo esc_attr($html_id.$key); ?>">
                    <div class="ct-tab-icon">
                        <?php
                            if($is_new):
                                \Elementor\Icons_Manager::render_icon( $value['ct_icon'], [ 'aria-hidden' => 'true' ] );
                        ?>
                        <?php else: ?>
                            <i <?php ct_print_html($widget->get_render_attribute_string( $icon_key )); ?>></i>
                        <?php endif; ?>
                    </div>
                    <div class="ct-tab-meta">
                        <h4><?php echo esc_attr($tab_title); ?></h4>
                        <p><?php echo ct_print_html($tab_content); ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="ct-tabs-content">
            <?php foreach ($settings['tabs3'] as $key => $value): 
                $banner = isset($value['banner']) ? $value['banner'] : '';
                $box_title = isset($value['box_title']) ? $value['box_title'] : '';
                $box_subtitle = isset($value['box_subtitle']) ? $value['box_subtitle'] : '';
                $box_content = isset($value['box_content']) ? $value['box_content'] : '';
                $box_btn_text = isset($value['box_btn_text']) ? $value['box_btn_text'] : '';
                $box_btn_link = isset($value['box_title']) ? $value['box_btn_link'] : '';
                $link_key = $widget->get_repeater_setting_key( 'title', 'value', $key );
                if ( ! empty( $box_btn_link['url'] ) ) {
                    $widget->add_render_attribute( $link_key, 'href', $box_btn_link['url'] );

                    if ( $box_btn_link['is_external'] ) {
                        $widget->add_render_attribute( $link_key, 'target', '_blank' );
                    }

                    if ( $box_btn_link['nofollow'] ) {
                        $widget->add_render_attribute( $link_key, 'rel', 'nofollow' );
                    }
                }
                $link_attributes = $widget->get_render_attribute_string( $link_key );
                ?>
                <div id="<?php echo esc_attr($html_id.$key); ?>" class="ct-tab-content" <?php if($active_tab == $key + 1) { ?>style="display: block;"<?php } ?>>
                    <?php if(!empty($banner['url'])) : ?>
                        <div class="ct-tab-image bg-image" style="background-image: url(<?php echo esc_url($banner['url']); ?>);">
                            <div class="ct-tab-box">
                                <h4 class="ct-box-title"><?php echo esc_attr($box_title); ?></h4>
                                <div class="ct-box-subtitle"><?php echo ct_print_html($box_subtitle); ?></div>
                                <div class="ct-box-content"><?php echo ct_print_html($box_content); ?></div>
                                <?php if(!empty($box_btn_text)) : ?>
                                    <div class="ct-box-button">
                                        <a class="btn btn-small btn-effect" <?php echo implode( ' ', [ $link_attributes ] ); ?>><?php echo esc_attr($box_btn_text); ?><i class="flaticonv2-right-arrow"></i></a>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
<?php endif; ?>