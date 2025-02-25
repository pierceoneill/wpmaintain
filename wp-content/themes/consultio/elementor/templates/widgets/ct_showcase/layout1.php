<?php
$default_settings = [
    'image' => '',
    'title' => '',
    'label' => '',
    'btn_text' => '',
    'button_link' => '',
    'ct_animate' => '',
];
$settings = array_merge($default_settings, $settings);
extract($settings);
if ( ! empty( $button_link['url'] ) ) {
    $widget->add_render_attribute( 'button', 'href', $button_link['url'] );

    if ( $button_link['is_external'] ) {
        $widget->add_render_attribute( 'button', 'target', '_blank' );
    }

    if ( $button_link['nofollow'] ) {
        $widget->add_render_attribute( 'button', 'rel', 'nofollow' );
    }
}
$img = consultio_get_image_by_size( array(
	'attach_id'  => $image['id'],
	'thumb_size' => 'full',
));
$thumbnail = $img['thumbnail'];
?>
<div class="ct-showcase1 <?php echo esc_attr($ct_animate); ?>">
	<?php if(!empty($image['url'])) : ?>
	    <div class="ct-showcase-image">
	    	<?php echo wp_kses_post($thumbnail); ?>
	    	<div class="ct-showcase-overlay"></div>
	    	<?php if(!empty($btn_text)) : ?>
	    		<a <?php ct_print_html($widget->get_render_attribute_string( 'button' )); ?> class="ct-showcase-link btn"><?php echo esc_attr($btn_text); ?></a>
	    	<?php endif; ?>
	    	<?php if(!empty($label)) : ?>
				<label><?php echo esc_attr($label); ?></label>
			<?php endif; ?>
	    </div>
	<?php endif; ?>
    <?php if(!empty($title)) : ?>
	    <div class="ct-showcase-meta">
	    	<h3><?php echo wp_kses_post($title); ?></h3>
	    </div>
	<?php endif; ?>
</div>