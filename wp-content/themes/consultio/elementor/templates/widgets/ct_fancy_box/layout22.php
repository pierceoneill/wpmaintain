<?php
$widget->add_render_attribute( 'selected_icon', 'class' );
$has_icon = ! empty( $settings['selected_icon'] );
if ( $has_icon ) {
    $widget->add_render_attribute( 'i', 'class', $settings['selected_icon'] );
    $widget->add_render_attribute( 'i', 'aria-hidden', 'true' );
}


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
?>
<div class="ct-fancy-box ct-fancy-box-layout22 <?php echo esc_attr($settings['ct_animate']); ?>" data-wow-delay="<?php echo esc_attr($settings['ct_animate_delay']); ?>ms">
    <?php if ( $settings['icon_type'] == 'icon' && $has_icon ) : ?>
        <div class="item--icon">
            <?php if($is_new):
                \Elementor\Icons_Manager::render_icon( $settings['selected_icon'], [ 'aria-hidden' => 'true' ] );
                else: ?>
                <i <?php ct_print_html($widget->get_render_attribute_string( 'i' )); ?>></i>
            <?php endif; ?>
            <?php if(!empty($settings['number'])) : ?>
                <div class="item--number"><?php echo esc_attr($settings['number']); ?></div>
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
            <?php if(!empty($settings['number'])) : ?>
                <div class="item--number"><?php echo esc_attr($settings['number']); ?></div>
            <?php endif; ?>
        </div>
    <?php endif; ?>
    <div class="item--holder">
        <?php if(!empty($settings['title_text'])) : ?>
            <h3 class="item--title <?php echo esc_attr($settings['ct_animate_t']); ?>" data-wow-delay="<?php echo esc_attr($settings['ct_animate_delay_t']); ?>ms">
                <?php echo esc_html($settings['title_text']); ?>
            </h3>
        <?php endif; ?>
        <div class="item--description <?php echo esc_attr($settings['ct_animate_d']); ?>" data-wow-delay="<?php echo esc_attr($settings['ct_animate_delay_d']); ?>ms"><?php echo ct_print_html($settings['description_text']); ?></div>
        <?php if ( ! empty( $settings['btn_text'] ) ) { ?>
            <div class="item--button">
                <a class="btn-text2" <?php ct_print_html($widget->get_render_attribute_string( 'button' )); ?>>
                    <span><?php echo esc_attr($settings['btn_text']); ?></span>
                    <i class="flaticonv2 flaticonv2-right-arrow"></i>
                </a>
            </div>
        <?php } ?>
    </div>
</div>