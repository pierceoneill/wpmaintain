<?php
$default_settings = [
    'ct_content' => '',
    'active' => '',
    'img_size' => '',
];
$settings = array_merge($default_settings, $settings);
extract($settings);
$has_icon = ! empty( $settings['ct_icon'] );
if ( $has_icon ) {
    $widget->add_render_attribute( 'i', 'class', $settings['ct_icon'] );
    $widget->add_render_attribute( 'i', 'aria-hidden', 'true' );
}
$is_new = \Elementor\Icons_Manager::is_migration_allowed();
$active = intval($active);
?>
<?php if(isset($ct_content) && !empty($ct_content) && count($ct_content)): ?>
    <div class="ct-cover-boxes1">
        <?php foreach ($ct_content as $key => $value):
            $icon_key = $widget->get_repeater_setting_key( 'ct_icon', 'icons', $key );
            $has_icon = ! empty( $value['ct_icon'] );
            $widget->add_render_attribute( $icon_key, [
                'class' => $value['ct_icon'],
                'aria-hidden' => 'true',
            ] );
            $title = isset($value['title']) ? $value['title'] : '';
            $description = isset($value['description']) ? $value['description'] : '';
            $btn_text = isset($value['btn_text']) ? $value['btn_text'] : '';
            $btn_link = isset($value['btn_link']) ? $value['btn_link'] : '';
            $image = isset($value['image']) ? $value['image'] : '';
            $img = ct_get_image_by_size( array(
                'attach_id'  => $image['id'],
                'thumb_size' => $img_size,
            ));
            $thumbnail = $img['thumbnail'];
            $link_key = $widget->get_repeater_setting_key( 'title', 'value', $key );
            if ( ! empty( $btn_link['url'] ) ) {
                $widget->add_render_attribute( $link_key, 'href', $btn_link['url'] );

                if ( $btn_link['is_external'] ) {
                    $widget->add_render_attribute( $link_key, 'target', '_blank' );
                }

                if ( $btn_link['nofollow'] ) {
                    $widget->add_render_attribute( $link_key, 'rel', 'nofollow' );
                }
            }
            $link_attributes = $widget->get_render_attribute_string( $link_key );
            $is_active = ($key + 1) == $active;
            ?>
            <div class="ct-cover-item <?php echo esc_attr($settings['ct_animate']); ?> <?php echo esc_attr($is_active?'active':''); ?>">
                <div class="ct-cover-inner">
                    <div class="item--image">
                        <?php echo wp_kses_post($thumbnail); ?>
                        <div class="item--icon">
                            <?php if ( $has_icon ) : ?>
                                <?php
                                    if($is_new):
                                        \Elementor\Icons_Manager::render_icon( $value['ct_icon'], [ 'aria-hidden' => 'true' ] );
                                ?>
                                <?php else: ?>
                                    <i <?php ct_print_html($widget->get_render_attribute_string( $icon_key )); ?>></i>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="item--content">
                        <div class="item--content-inner">
                            <h4 class="item--title"><?php echo esc_attr($title); ?></h4>
                            <div class="item--desc"><?php echo ct_print_html($description); ?></div>
                            <?php if(!empty($btn_text)) : ?>
                                <div class="item--readmore">
                                    <a <?php echo implode( ' ', [ $link_attributes ] ); ?>><?php echo esc_attr($btn_text); ?><i class="flaticonv2-right-arrow"></i></a>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>