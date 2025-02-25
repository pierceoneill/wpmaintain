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
<div class="ct-counter ct-counter-layout6 <?php echo esc_attr($settings['ct_animate']).' '.esc_attr($settings['style']); ?>" data-wow-delay="<?php echo esc_attr($settings['ct_animate_delay']); ?>ms">
    <div class="ct-counter-inner">
        <div class="ct-counter-number">
            <?php if(!empty($settings['prefix'])) : ?>
                <span class="ct-counter-number-prefix"><?php echo esc_html($settings['prefix']); ?></span>
            <?php endif; ?>
            <span <?php ct_print_html($widget->get_render_attribute_string( 'counter' )); ?>><?php echo esc_html($settings['starting_number']); ?></span>
            <span class="ct-counter-number-value-after"><?php echo esc_html($settings['number_value']); ?></span>
            <span class="ct-counter-number-suffix"><?php echo esc_html($settings['suffix']); ?></span>
        </div>
        <div class="ct-counter-holder">
            <?php if ( $settings['title'] ) : ?>
                <h5 class="ct-counter-title"><?php echo ct_print_html($settings['title']); ?></h5>
            <?php endif; ?>

            <?php if ( $settings['description_text'] ) : ?>
                <div class="ct-counter-desc"><?php echo ct_print_html($settings['description_text']); ?></div>
            <?php endif; ?>
        </div>
    </div>
</div>