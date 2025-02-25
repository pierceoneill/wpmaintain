<?php
$widget->add_render_attribute( 'selected_icon', 'class' );
$has_icon = ! empty( $settings['selected_icon'] );
if ( $has_icon ) {
    $widget->add_render_attribute( 'i', 'class', $settings['selected_icon'] );
    $widget->add_render_attribute( 'i', 'aria-hidden', 'true' );
}

$widget->add_render_attribute( 'description_text', 'class', 'item--description' );

$widget->add_inline_editing_attributes( 'title_text', 'none' );
$widget->add_inline_editing_attributes( 'description_text' );
$is_new = \Elementor\Icons_Manager::is_migration_allowed();

if ( ! empty( $settings['btn_link']['url'] ) ) {
    $widget->add_render_attribute( 'button', 'href', $settings['btn_link']['url'] );

    if ( $settings['btn_link']['is_external'] ) {
        $widget->add_render_attribute( 'button', 'target', '_blank' );
    }

    if ( $settings['btn_link']['nofollow'] ) {
        $widget->add_render_attribute( 'button', 'rel', 'nofollow' );
    }
}
$html_id = ct_get_element_id($settings);
?>
<div id="<?php echo esc_attr($html_id); ?>" class="ct-fancy-box ct-fancy-box-layout10 <?php echo esc_attr($settings['ct_animate']); ?>" data-wow-delay="<?php echo esc_attr($settings['ct_animate_delay']); ?>ms">
    <?php if(!empty($settings['box_image']['url'])) : ?>
        <div class="item--overlay bg-image" style="background-image: url(<?php echo esc_url($settings['box_image']['url']); ?>);"></div>
    <?php endif; ?>
    
    <?php if ( $settings['icon_type'] == 'icon' && $has_icon ) : ?>
        <div class="item--icon">
            <?php if($is_new):
                \Elementor\Icons_Manager::render_icon( $settings['selected_icon'], [ 'aria-hidden' => 'true' ] );
                else: ?>
                <i <?php ct_print_html($widget->get_render_attribute_string( 'i' )); ?>></i>
            <?php endif; ?>
        </div>
    <?php endif; ?>
    <?php if ( $settings['icon_type'] == 'image' && !empty($settings['icon_image']['id']) ) : ?>
        <div class="item--icon">
            <?php $img_icon  = consultio_get_image_by_size( array(
                    'attach_id'  => $settings['icon_image']['id'],
                    'thumb_size' => 'full',
                ) );
                $thumbnail_icon    = $img_icon['thumbnail'];
            echo wp_kses_post($thumbnail_icon); ?>
        </div>
    <?php endif; ?>
    <div class="item--holder">
        <h3 class="item--title">
            <?php echo esc_html($settings['title_text']); ?>
        </h3>
        <div <?php ct_print_html($widget->get_render_attribute_string( 'description_text' )); ?>><?php echo ct_print_html($settings['description_text']); ?></div>
        <?php if ( ! empty( $settings['btn_text'] ) ) { ?>
            <div class="item--button">
                <a class="btn-arrow2" <?php ct_print_html($widget->get_render_attribute_string( 'button' )); ?>><span><?php echo esc_attr($settings['btn_text']); ?></span><i class="flaticonv2-right-arrow"></i></a>
            </div>
        <?php } ?>
    </div>
</div>