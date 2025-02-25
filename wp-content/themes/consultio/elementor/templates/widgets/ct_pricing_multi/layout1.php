<?php
$default_settings = [
    'tab_title_monthly' => '',
    'tab_title_year' => '',
    'col_monthly' => '',
    'col_year' => '',
    'content_monthly' => '',
    'content_year' => '',
    'ct_animate' => '',
];
$settings = array_merge($default_settings, $settings);
extract($settings);
$is_new = \Elementor\Icons_Manager::is_migration_allowed();
?>
<div class="ct-pricing-multi ct-pricing-multi-layout1 <?php if(!empty($tab_title_monthly) || !empty($tab_title_year)) { echo 'ct-pricing-tab-active'; } ?>">
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
    <div class="ct-pricing-tab-content">
        <?php if(!empty($content_monthly)) : ?>
            <div class="ct-pricing-body ct-pricing-monthly pricing-<?php echo esc_attr($col_monthly); ?>-column">
                <?php foreach ($content_monthly as $key => $value):
                $title = isset($value['title']) ? $value['title'] : '';
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
                            <h3 class="ct-pricing-title"><?php echo ct_print_html($title); ?></h3>
                            <div class="ct-pricing-price"><?php echo ct_print_html($price); ?></div>     
                            <div class="ct-pricing-time"><?php echo esc_attr($time); ?></div>
                        </div>
                        <ul class="ct-pricing-features-list">
                            <?php if(!empty($feature)):
                                $career_feature = json_decode($feature, true);
                                foreach ($career_feature as $value): ?>
                                    <li><?php echo ct_print_html($value['content_pricing']); ?></li>
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
                            <h3 class="ct-pricing-title"><?php echo ct_print_html($title); ?></h3>
                            <div class="ct-pricing-price"><?php echo ct_print_html($price); ?></div>
                            <div class="ct-pricing-time"><?php echo esc_attr($time); ?></div>
                        </div>
                        <ul class="ct-pricing-features-list">
                            <?php if(!empty($feature)):
                                $career_feature = json_decode($feature, true);
                                foreach ($career_feature as $value): ?>
                                    <li><?php echo ct_print_html($value['content_pricing']); ?></li>
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