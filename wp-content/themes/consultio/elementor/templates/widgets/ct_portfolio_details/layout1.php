<?php
$default_settings = [
    'portfolio_content' => '',
];
$settings = array_merge($default_settings, $settings);
extract($settings);
$has_icon = ! empty( $settings['ct_icon'] );
if ( $has_icon ) {
    $widget->add_render_attribute( 'i', 'class', $settings['ct_icon'] );
    $widget->add_render_attribute( 'i', 'aria-hidden', 'true' );
}
$is_new = \Elementor\Icons_Manager::is_migration_allowed();
?>
<?php if(isset($portfolio_content) && !empty($portfolio_content) && count($portfolio_content)): ?>
    <ul class="ct-portfolio-detail">
        <?php foreach ($portfolio_content as $key => $value):
            $icon_key = $widget->get_repeater_setting_key( 'ct_icon', 'portfolio_content', $key );
            $has_icon = ! empty( $value['ct_icon'] );
            $widget->add_render_attribute( $icon_key, [
                'class' => $value['ct_icon'],
                'aria-hidden' => 'true',
            ] );
            $label = isset($value['label']) ? $value['label'] : '';
            $content = isset($value['content']) ? $value['content'] : '';
            ?>
            <li>
                <?php if ( $has_icon ) : ?>
                    <?php if($is_new):
                            \Elementor\Icons_Manager::render_icon( $value['ct_icon'], [ 'aria-hidden' => 'true' ] ); ?>
                    <?php else: ?>
                        <i <?php ct_print_html($widget->get_render_attribute_string( $icon_key )); ?>></i>
                    <?php endif; ?>
                <?php endif; ?>

                <?php if(!empty($label)) : ?>
                    <label><?php echo esc_attr($label); ?></label>
                <?php endif; ?>

                <?php if(!empty($content)) : ?>
                    <span><?php echo esc_attr($content); ?></span>
                <?php endif; ?>
            </li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>