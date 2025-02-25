<?php
$default_settings = [
    'tab_title_monthly' => '',
    'tab_title_year' => '',
    'col_monthly' => '',
    'col_year' => '',
    'content_monthly' => '',
    'content_year' => '',
    'ct_animate' => '',
    'pricing_note' => '',
];
$settings = array_merge($default_settings, $settings);
extract($settings);
$is_new = \Elementor\Icons_Manager::is_migration_allowed();
?>
<div class="ct-pricing-multi ct-pricing-multi-layout3 <?php if(!empty($tab_title_monthly) || !empty($tab_title_year)) { echo 'ct-pricing-tab-active'; } ?>">
    <?php if(!empty($tab_title_monthly) || !empty($tab_title_year)) : ?>
        <div class="ct-pricing-tab-title">
            <?php if($tab_title_monthly) : ?>
                <div class="ct-pricing-tab-item title-tab-monthly active"><?php echo ct_print_html($tab_title_monthly); ?></div>
            <?php endif; ?>
            <?php if($tab_title_year) : ?>
                <div class="ct-pricing-tab-item title-tab-year"><?php echo ct_print_html($tab_title_year); ?></div>
            <?php endif; ?>
        </div>
    <?php endif; ?>
    <?php if(!empty($pricing_note)) : ?>
        <div class="ct-pricing-note"><?php echo esc_attr($pricing_note); ?></div>
    <?php endif; ?>
    <div class="ct-pricing-tab-content">
        <?php if(!empty($content_monthly)) : ?>
            <div class="ct-pricing-body ct-pricing-monthly pricing-<?php echo esc_attr($col_monthly); ?>-column">
                <?php foreach ($content_monthly as $key => $value):
                $title = isset($value['title']) ? $value['title'] : '';
                $desc = isset($value['desc']) ? $value['desc'] : '';
                $time = isset($value['time']) ? $value['time'] : '';
                $price = isset($value['price']) ? $value['price'] : '';
                $button_text = isset($value['button_text']) ? $value['button_text'] : '';
                $button_link = isset($value['button_link']) ? $value['button_link'] : '';
                $link_key = $widget->get_repeater_setting_key( 'title', 'value', $key );
                if ( ! empty( $button_link['url'] ) ) {
                    $widget->add_render_attribute( $link_key, 'href', $button_link['url'] );

                    if ( $button_link['is_external'] ) {
                        $widget->add_render_attribute( $link_key, 'target', '_blank' );
                    }

                    if ( $button_link['nofollow'] ) {
                        $widget->add_render_attribute( $link_key, 'rel', 'nofollow' );
                    }
                }
                $link_attributes = $widget->get_render_attribute_string( $link_key ); 
                $featured = isset($value['featured']) ? $value['featured'] : '';
                $feature = isset($value['feature']) ? $value['feature'] : '';
                ?>
                <div class="ct-pricing-item <?php echo esc_attr($ct_animate); ?>" data-wow-duration="1.2s">
                    <div class="ct-pricing-item-inner <?php if($featured == 'yes') { echo 'ct-pricing-featured'; } ?>">
                        <div class="ct-pricing-meta">
                            <div class="ct-pricing-price"><?php echo ct_print_html($price); ?></div>     
                            <div class="ct-pricing-time"><?php echo esc_attr($time); ?></div>
                        </div>
                        <h4 class="ct-pricing-title"><?php echo ct_print_html($title); ?></h4>
                        <div class="ct-pricing-desc"><?php echo ct_print_html($desc); ?></div>
                        <ul class="ct-pricing-features-list">
                            <?php if(!empty($feature)):
                                $pricing_feature = json_decode($feature, true);
                                foreach ($pricing_feature as $value): 
                                    $content_pricing = isset($value['content_pricing']) ? $value['content_pricing'] : '';
                                    $class_pricing = isset($value['class_pricing']) ? $value['class_pricing'] : '';
                                    ?>
                                    <li class="<?php echo esc_attr($class_pricing); ?>"><i class="flaticonv7 flaticonv7-check"></i><?php echo ct_print_html($content_pricing); ?></li>
                                <?php endforeach;
                            endif; ?>
                        </ul>
                        <?php if(!empty($button_text)) : ?>
                            <div class="ct-pricing-button">
                                <a class="btn btn-mini" <?php echo implode( ' ', [ $link_attributes ] ); ?>><?php echo esc_attr($button_text); ?></a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        <?php if(!empty($content_year)) : ?>
            <div class="ct-pricing-body ct-pricing-year <?php if(!empty($tab_title_monthly) || !empty($tab_title_year)) { echo 'ct-pricing-hide'; } ?> pricing-<?php echo esc_attr($col_year); ?>-column">
                <?php foreach ($content_year as $key => $value):
                $title = isset($value['title']) ? $value['title'] : '';
                $desc = isset($value['desc']) ? $value['desc'] : '';
                $time = isset($value['time']) ? $value['time'] : '';
                $price = isset($value['price']) ? $value['price'] : '';
                $button_text = isset($value['button_text']) ? $value['button_text'] : '';
                $button_link = isset($value['button_link']) ? $value['button_link'] : '';
                $link_key = $widget->get_repeater_setting_key( 'title', 'value', $key );
                if ( ! empty( $button_link['url'] ) ) {
                    $widget->add_render_attribute( $link_key, 'href', $button_link['url'] );

                    if ( $button_link['is_external'] ) {
                        $widget->add_render_attribute( $link_key, 'target', '_blank' );
                    }

                    if ( $button_link['nofollow'] ) {
                        $widget->add_render_attribute( $link_key, 'rel', 'nofollow' );
                    }
                }
                $link_attributes = $widget->get_render_attribute_string( $link_key ); 
                $featured = isset($value['featured']) ? $value['featured'] : '';
                $feature = isset($value['feature']) ? $value['feature'] : '';
                ?>
                <div class="ct-pricing-item">
                    <div class="ct-pricing-item-inner <?php if($featured == 'yes') { echo 'ct-pricing-featured'; } ?>">
                        <div class="ct-pricing-meta">
                            <div class="ct-pricing-price"><?php echo ct_print_html($price); ?></div>
                            <div class="ct-pricing-time"><?php echo esc_attr($time); ?></div>
                        </div>
                        <h4 class="ct-pricing-title"><?php echo ct_print_html($title); ?></h4>
                        <div class="ct-pricing-desc"><?php echo ct_print_html($desc); ?></div>
                        <ul class="ct-pricing-features-list">
                            <?php if(!empty($feature)):
                                $pricing_feature = json_decode($feature, true);
                                foreach ($pricing_feature as $value): 
                                    $content_pricing = isset($value['content_pricing']) ? $value['content_pricing'] : '';
                                    $class_pricing = isset($value['class_pricing']) ? $value['class_pricing'] : '';
                                    ?>
                                    <li class="<?php echo esc_attr($class_pricing); ?>"><i class="flaticonv7 flaticonv7-check"></i><?php echo ct_print_html($content_pricing); ?></li>
                                <?php endforeach;
                            endif; ?>
                        </ul>
                        <?php if(!empty($button_text)) : ?>
                            <div class="ct-pricing-button">
                                <a class="btn btn-mini" <?php echo implode( ' ', [ $link_attributes ] ); ?>><?php echo esc_attr($button_text); ?></a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>