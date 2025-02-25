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
if ( ! empty( $settings['link']['url'] ) ) {
    $widget->add_render_attribute( 'button', 'href', $settings['link']['url'] );

    if ( $settings['link']['is_external'] ) {
        $widget->add_render_attribute( 'button', 'target', '_blank' );
    }

    if ( $settings['link']['nofollow'] ) {
        $widget->add_render_attribute( 'button', 'rel', 'nofollow' );
    }
}
$is_new = \Elementor\Icons_Manager::is_migration_allowed();
?>
<div class="ct-fancy-box ct-fancy-box-layout1 <?php echo esc_attr($settings['style']); ?> <?php echo esc_attr($settings['ct_animate']); ?>" data-wow-delay="<?php echo esc_attr($settings['ct_animate_delay']); ?>ms">
    <?php if ( $settings['icon_type'] == 'icon' && $has_icon ) : ?>
        <div class="item--icon">
            <span class="item--shape"></span>
            <?php if($is_new):
                \Elementor\Icons_Manager::render_icon( $settings['selected_icon'], [ 'aria-hidden' => 'true' ] );
                else: ?>
                <i <?php ct_print_html($widget->get_render_attribute_string( 'i' )); ?>></i>
            <?php endif; ?>
            <?php if ( ! empty( $settings['link']['url'] ) ) { ?>
                <a class="item--link" <?php ct_print_html($widget->get_render_attribute_string( 'button' )); ?>></a>
            <?php } ?>
        </div>
    <?php endif; ?>
    <?php if ( $settings['icon_type'] == 'image' && !empty($settings['icon_image']['id']) ) : ?>
        <div class="item--icon">
            <span class="item--shape"></span>
            <?php $img_icon  = consultio_get_image_by_size( array(
                    'attach_id'  => $settings['icon_image']['id'],
                    'thumb_size' => 'full',
                ) );
                $thumbnail_icon    = $img_icon['thumbnail'];
            echo wp_kses_post($thumbnail_icon); ?>
            <?php if ( ! empty( $settings['link']['url'] ) ) { ?>
                <a class="item--link" <?php ct_print_html($widget->get_render_attribute_string( 'button' )); ?>></a>
            <?php } ?>
        </div>
    <?php endif; ?>
    <div class="item--holder">
        <?php if(!empty($settings['title_text'])) : ?>
            <h3 class="item--title">
                <?php echo ct_print_html($settings['title_text']); ?>
            </h3>
        <?php endif; ?>
        <div <?php ct_print_html($widget->get_render_attribute_string( 'description_text' )); ?>><?php echo ct_print_html($settings['description_text']); ?></div>
    </div>
</div>