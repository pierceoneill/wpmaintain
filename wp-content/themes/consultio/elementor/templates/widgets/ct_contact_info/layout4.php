<?php
$default_settings = [
    'contact_info' => '',
    'ct_animate' => '',
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
<?php if(isset($settings['contact_info']) && !empty($settings['contact_info']) && count($settings['contact_info'])): ?>
    <div class="ct-contact-info ct-contact-info4 <?php echo esc_attr($ct_animate); ?>">
        <?php
        	foreach ($settings['contact_info'] as $key => $ct_info):
        		$icon_key = $widget->get_repeater_setting_key( 'ct_icon', 'contact_info', $key );

        		$has_icon = ! empty( $ct_info['ct_icon'] );
        		$widget->add_render_attribute( $icon_key, [
	                'class' => $ct_info['ct_icon'],
	                'aria-hidden' => 'true',
	            ] );

                $link_key = $widget->get_repeater_setting_key( 'title', 'value', $key );
                if ( ! empty( $ct_info['link']['url'] ) ) {
                    $widget->add_render_attribute( $link_key, 'href', $ct_info['link']['url'] );

                    if ( $ct_info['link']['is_external'] ) {
                        $widget->add_render_attribute( $link_key, 'target', '_blank' );
                    }

                    if ( $ct_info['link']['nofollow'] ) {
                        $widget->add_render_attribute( $link_key, 'rel', 'nofollow' );
                    }
                }
                $link_attributes = $widget->get_render_attribute_string( $link_key );

			?>
            <div class="item--inner">
            	<?php if ( $ct_info['icon_type'] == 'icon' && $has_icon ) : ?>
			        <div class="ct-contact-icon" <?php if(!empty($ct_info['icon_box_color'])) : ?>style="background-color: <?php echo esc_attr($ct_info['icon_box_color']); ?>"<?php endif; ?>>
		                <?php
		                    if($is_new):
		                        \Elementor\Icons_Manager::render_icon( $ct_info['ct_icon'], [ 'aria-hidden' => 'true' ] );
		                ?>
		                <?php else: ?>
		                    <i <?php ct_print_html($widget->get_render_attribute_string( $icon_key )); ?>></i>
		                <?php endif; ?>
			        </div>
			    <?php endif; ?>
                <?php if ( $ct_info['icon_type'] == 'image' && !empty($ct_info['icon_image']) ) : 
                    $img_icon  = consultio_get_image_by_size( array(
                        'attach_id'  => $ct_info['icon_image']['id'],
                        'thumb_size' => 'full',
                    ) );
                    $thumbnail_icon    = $img_icon['thumbnail'];
                    ?>
                    <div class="ct-contact-icon" <?php if(!empty($ct_info['icon_box_color'])) : ?>style="background-color: <?php echo esc_attr($ct_info['icon_box_color']); ?>"<?php endif; ?>>
                        <?php echo wp_kses_post($thumbnail_icon); ?>
                    </div>
                <?php endif; ?>
                <div class="ct-contact-meta">
                    <h4 class="item--title"><?php echo esc_attr($ct_info['title']); ?></h4>
                    <div class="item--content">
                       <?php echo wp_kses_post($ct_info['content'])?>
                    </div>
                </div>

                <a class="ct-contact-link" <?php echo implode( ' ', [ $link_attributes ] ); ?>></a>
                
           </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
