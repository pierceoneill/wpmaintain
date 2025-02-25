<?php 
	$html_id = ct_get_element_id($settings);
	$colors = [];
    foreach($settings['particle_color_item'] as $values) {
        $colors[] = $values['particle_color'];
    }
    if(empty($colors)) {
        $colors = ["#fff"];
    }
    $widget->add_render_attribute( 'color', 'data-color', json_encode($colors) );
?>
<div id="<?php echo esc_attr($html_id); ?>" class="ct-particles-js <?php echo esc_attr($settings['style']); ?>" data-number="<?php if(!empty($settings['number'])) { echo esc_attr($settings['number']); } else { echo '60'; } ?>" data-size="<?php if(!empty($settings['size'])) { echo esc_attr($settings['size']); } else { echo '3'; } ?>" data-size-random="<?php echo esc_attr($settings['size_random']); ?>" data-line-linked="<?php echo esc_attr($settings['line_linked']); ?>" data-move-direction="<?php echo esc_attr($settings['move_direction']); ?>" <?php echo wp_kses_post($widget->get_render_attribute_string( 'color' )); ?>></div>