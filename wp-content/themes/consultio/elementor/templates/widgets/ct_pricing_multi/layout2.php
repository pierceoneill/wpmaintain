<?php
$default_settings = [
    'tab_title_monthly' => '',
    'tab_title_year' => '',
    'col_monthly' => '',
    'col_year' => '',
    'content_monthly2' => '',
    'content_year2' => '',
    'ct_animate' => '',
];
$settings = array_merge($default_settings, $settings);
extract($settings);
$is_new = \Elementor\Icons_Manager::is_migration_allowed();
?>
<div class="ct-pricing-multi ct-pricing-multi-layout2 <?php if(!empty($tab_title_monthly) || !empty($tab_title_year)) { echo 'ct-pricing-tab-active'; } ?>">
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
        <?php if(!empty($content_monthly2)) : ?>
            <div class="ct-pricing-body ct-pricing-monthly pricing-<?php echo esc_attr($col_monthly); ?>-column">
                <?php foreach ($content_monthly2 as $key => $value):
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
                $feature = isset($value['feature']) ? $value['feature'] : '';
                $icon_key = $widget->get_repeater_setting_key( 'ct_icon', 'icons', $key );
                $widget->add_render_attribute( $icon_key, [
                    'class' => $value['ct_icon'],
                    'aria-hidden' => 'true',
                ] );
                ?>
                <div class="ct-pricing-item <?php echo esc_attr($ct_animate); ?>" data-wow-duration="1.2s">
                    <div class="ct-pricing-item-inner">
                        <?php if(!empty( $value['ct_icon']["value"] )) : ?>
                            <div class="ct-pricing-icon">
                                <div class="ct-pricing-hexagon"><span></span></div>
                                <?php
                                    if($is_new):
                                        \Elementor\Icons_Manager::render_icon( $value['ct_icon'], [ 'aria-hidden' => 'true' ] );
                                ?>
                                <?php else: ?>
                                    <i <?php ct_print_html($widget->get_render_attribute_string( $icon_key )); ?>></i>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                        <h3 class="ct-pricing-title"><?php echo ct_print_html($title); ?></h3>
                        <div class="ct-pricing-meta">
                            <div class="ct-pricing-price"><?php echo ct_print_html($price); ?></div>     
                            <div class="ct-pricing-time"><?php echo esc_attr($time); ?></div>
                        </div>
                        <ul class="ct-pricing-features-list">
                            <?php if(!empty($feature)):
                                $career_feature = json_decode($feature, true);
                                foreach ($career_feature as $value): ?>
                                    <li><i class="fac fac-check"></i><?php echo ct_print_html($value['content_pricing']); ?></li>
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
        <?php if(!empty($content_year2)) : ?>
            <div class="ct-pricing-body ct-pricing-year <?php if(!empty($tab_title_monthly) || !empty($tab_title_year)) { echo 'ct-pricing-hide'; } ?> pricing-<?php echo esc_attr($col_year); ?>-column">
                <?php foreach ($content_year2 as $key => $value):
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
                $feature = isset($value['feature']) ? $value['feature'] : '';
                $icon_key = $widget->get_repeater_setting_key( 'ct_icon', 'icons', $key );
                $has_icon = ! empty( $value['ct_icon'] );
                $widget->add_render_attribute( $icon_key, [
                    'class' => $value['ct_icon'],
                    'aria-hidden' => 'true',
                ] );
                ?>
                <div class="ct-pricing-item">
                    <div class="ct-pricing-item-inner">
                        <?php if ( $has_icon ) : ?>
                            <div class="ct-pricing-icon">
                                <div class="ct-pricing-hexagon"><span></span></div>
                                <?php
                                    if($is_new):
                                        \Elementor\Icons_Manager::render_icon( $value['ct_icon'], [ 'aria-hidden' => 'true' ] );
                                ?>
                                <?php else: ?>
                                    <i <?php ct_print_html($widget->get_render_attribute_string( $icon_key )); ?>></i>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                        <h3 class="ct-pricing-title"><?php echo ct_print_html($title); ?></h3>
                        <div class="ct-pricing-meta">
                            <div class="ct-pricing-price"><?php echo ct_print_html($price); ?></div>     
                            <div class="ct-pricing-time"><?php echo esc_attr($time); ?></div>
                        </div>
                        <ul class="ct-pricing-features-list">
                            <?php if(!empty($feature)):
                                $career_feature = json_decode($feature, true);
                                foreach ($career_feature as $value): ?>
                                    <li><i class="fac fac-check"></i><?php echo ct_print_html($value['content_pricing']); ?></li>
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