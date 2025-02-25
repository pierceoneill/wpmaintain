<?php
$default_settings = [
    'title' => '',
    'style' => 'style1',
];
$settings = array_merge($default_settings, $settings);
extract($settings); 
if ( ! empty( $settings['link']['url'] ) ) {
    $widget->add_render_attribute( 'button', 'href', $settings['link']['url'] );

    if ( $settings['link']['is_external'] ) {
        $widget->add_render_attribute( 'button', 'target', '_blank' );
    }

    if ( $settings['link']['nofollow'] ) {
        $widget->add_render_attribute( 'button', 'rel', 'nofollow' );
    }
}
?>
<div class="ct-title1 <?php echo esc_attr($style); ?>">
    <h3>
    	<?php if ( ! empty( $settings['link']['url'] ) ) { ?><a <?php ct_print_html($widget->get_render_attribute_string( 'button' )); ?>><?php } ?>
	        <span><?php echo ct_print_html($title); ?></span>
	        <i></i>
	    <?php if ( ! empty( $settings['link']['url'] ) ) { ?></a><?php } ?>
    </h3>
</div>