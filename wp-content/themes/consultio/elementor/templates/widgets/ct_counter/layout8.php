<?php
$widget->add_render_attribute( 'counter', [
    'class' => 'ct-counter-number-value',
    'data-duration' => $settings['duration'],
    'data-to-value' => $settings['ending_number'],
] );

if ( ! empty( $settings['thousand_separator'] ) ) {
    $delimiter = empty( $settings['thousand_separator_char'] ) ? '' : $settings['thousand_separator_char'];
    $widget->add_render_attribute( 'counter', 'data-delimiter', $delimiter );
}
$is_new = \Elementor\Icons_Manager::is_migration_allowed();
$has_icon = ! empty( $settings['counter_icon'] );
if ( $has_icon ) {
    $widget->add_render_attribute( 'i', 'class', $settings['counter_icon'] );
    $widget->add_render_attribute( 'i', 'aria-hidden', 'true' );
}
?>
<div class="ct-counter ct-counter-layout8 <?php echo esc_attr($settings['ct_animate']); ?>" data-wow-delay="<?php echo esc_attr($settings['ct_animate_delay']); ?>ms">
    <div class="ct-counter-inner">
        <div class="ct-counter-holder">
            <?php if ( $settings['icon_type'] == 'icon' && $has_icon ) : ?>
                <div class="item--icon">
                    <?php if($is_new):
                        \Elementor\Icons_Manager::render_icon( $settings['counter_icon'], [ 'aria-hidden' => 'true' ] );
                        else: ?>
                        <i <?php ct_print_html($widget->get_render_attribute_string( 'i' )); ?>></i>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
            <?php if ( $settings['icon_type'] == 'image' && !empty($settings['icon_image']['id']) ) : ?>
                <div class="item--icon">
                    <?php $img_icon  = ct_get_image_by_size( array(
                            'attach_id'  => $settings['icon_image']['id'],
                            'thumb_size' => 'full',
                        ) );
                        $thumbnail_icon    = $img_icon['thumbnail'];
                    echo ct_print_html($thumbnail_icon); ?>
                </div>
            <?php endif; ?>
            <div class="ct-counter-number">
                <span class="ct-counter-number-prefix"><?php echo ct_print_html($settings['prefix']); ?></span>
                <span <?php ct_print_html($widget->get_render_attribute_string( 'counter' )); ?>><?php echo esc_html($settings['starting_number']); ?></span>
                <span class="ct-counter-number-suffix"><?php echo ct_print_html($settings['suffix']); ?></span>
            </div>
        </div>
        <?php if ( $settings['title'] ) : ?>
            <h4 class="ct-counter-title"><?php echo ct_print_html($settings['title']); ?></h4>
        <?php endif; ?>
    </div>
</div>