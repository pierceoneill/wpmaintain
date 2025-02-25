<?php
$html_id = ct_get_element_id($settings);
$active_tab = $widget->get_setting('active_tab', 1);
$tabs = $widget->get_setting('tabs', '');
$is_new = \Elementor\Icons_Manager::is_migration_allowed();

if(isset($settings['tabs']) && !empty($settings['tabs']) && count($settings['tabs'])): ?>
    <div class="ct-tabs ct-tab-banner2">
        <div class="ct-tabs-title">
            <?php foreach ($settings['tabs'] as $key => $value): 
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
                    <?php
                        if($is_new):
                            \Elementor\Icons_Manager::render_icon( $value['ct_icon'], [ 'aria-hidden' => 'true' ] );
                    ?>
                    <?php else: ?>
                        <i <?php ct_print_html($widget->get_render_attribute_string( $icon_key )); ?>></i>
                    <?php endif; ?>
                    <h4><?php echo esc_attr($tab_title); ?></h4>
                    <p><?php echo ct_print_html($tab_content); ?></p>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="ct-tabs-content">
            <?php foreach ($settings['tabs'] as $key => $value): 
                $banner = isset($value['banner']) ? $value['banner'] : '';
                $img = consultio_get_image_by_size( array(
                    'attach_id'  => $banner['id'],
                    'thumb_size' => '502x586',
                ));
                $thumbnail = $img['thumbnail'];
                ?>
                <div id="<?php echo esc_attr($html_id.$key); ?>" class="ct-tab-content" <?php if($active_tab == $key + 1) { ?>style="display: block;"<?php } ?>>
                    <?php if(!empty($banner['url'])) : ?>
                        <div class="ct-tab-image">
                            <?php echo wp_kses_post($thumbnail); ?>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
<?php endif; ?>