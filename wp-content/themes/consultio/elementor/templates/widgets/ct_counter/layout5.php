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
?>
<div class="ct-counter ct-counter-layout5 <?php echo esc_attr($settings['ct_animate']).' '.esc_attr($settings['style']); ?>" data-wow-delay="<?php echo esc_attr($settings['ct_animate_delay']); ?>ms">
    <div class="ct-counter-inner">
        <?php if($settings['icon_type'] == 'icon'): ?>
            <div class="ct-counter-icon">
                <?php if(!empty($settings['counter_icon'])): ?>
                    <?php
                    if($is_new):
                        \Elementor\Icons_Manager::render_icon( $settings['counter_icon'], [ 'aria-hidden' => 'true' ] );
                    ?>
                    <?php
                    else:
                        $widget->add_render_attribute( 'i', 'class', $settings['counter_icon'] );
                        $widget->add_render_attribute( 'i', 'aria-hidden', 'true' );
                    ?>
                        <i <?php ct_print_html($widget->get_render_attribute_string( 'i' )); ?>></i>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        <?php elseif($settings['icon_type'] == 'image'): ?>
            <div class="ct-counter-icon">
                <?php
                    if(!empty($settings['icon_image'])){
                        echo wp_get_attachment_image($settings['icon_image']['id']);
                    }
                ?>
            </div>
        <?php endif; ?>
        <div class="ct-counter-holder">
            <div class="ct-counter-number">
                <?php if(!empty($settings['prefix'])) : ?>
                    <span class="ct-counter-number-prefix"><?php echo esc_html($settings['prefix']); ?></span>
                <?php endif; ?>
                <span <?php ct_print_html($widget->get_render_attribute_string( 'counter' )); ?>><?php echo esc_html($settings['starting_number']); ?></span>
                <span class="ct-counter-number-value-after"><?php echo esc_html($settings['number_value']); ?></span>
                <span class="ct-counter-number-suffix"><?php echo esc_html($settings['suffix']); ?></span>
            </div>
            <?php if ( $settings['title'] ) : ?>
                <div class="ct-counter-title"><?php echo ct_print_html($settings['title']); ?></div>
            <?php endif; ?>
        </div>
    </div>
</div>