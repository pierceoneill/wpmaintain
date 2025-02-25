<?php
$default_settings = [
    'contact_info' => '',
    'style' => 'style1',
    'icon_gradient_from' => '',
    'icon_gradient_to' => '',
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
$html_id = ct_get_element_id($settings);
?>
<?php if(isset($contact_info) && !empty($contact_info) && count($contact_info)): ?>
    <ul id="<?php echo esc_attr($html_id); ?>" class="ct-contact-info ct-contact-info1 <?php echo esc_attr($style.' '.$ct_animate); ?>">
        <div class="ct-inline-css"  data-css="
            <?php if( !empty($icon_gradient_from) && !empty($icon_gradient_to) ) : ?>
                #<?php echo esc_attr($html_id) ?> .ct-contact-icon i {
                    background-image: -webkit-gradient(linear, left top, left bottom, from(<?php echo esc_attr($icon_gradient_from); ?>), to(<?php echo esc_attr($icon_gradient_to); ?>));
                    background-image: -webkit-linear-gradient(left, <?php echo esc_attr($icon_gradient_from); ?>, <?php echo esc_attr($icon_gradient_to); ?>);
                    background-image: -moz-linear-gradient(left, <?php echo esc_attr($icon_gradient_from); ?>, <?php echo esc_attr($icon_gradient_to); ?>);
                    background-image: -ms-linear-gradient(left, <?php echo esc_attr($icon_gradient_from); ?>, <?php echo esc_attr($icon_gradient_to); ?>);
                    background-image: -o-linear-gradient(left, <?php echo esc_attr($icon_gradient_from); ?>, <?php echo esc_attr($icon_gradient_to); ?>);
                    background-image: linear-gradient(left, <?php echo esc_attr($icon_gradient_from); ?>, <?php echo esc_attr($icon_gradient_to); ?>);
                    filter: progid:DXImageTransform.Microsoft.gradient(startColorStr='<?php echo esc_attr($icon_gradient_from); ?>', endColorStr='<?php echo esc_attr($icon_gradient_to); ?>');
                    background-color: transparent;
                    background-clip: text;
                    -o-background-clip: text;
                    -ms-background-clip: text;
                    -moz-background-clip: text;
                    -webkit-background-clip: text;
                    text-fill-color: transparent;
                    -o-text-fill-color: transparent;
                    -ms-text-fill-color: transparent;
                    -moz-text-fill-color: transparent;
                    -webkit-text-fill-color: transparent;
                }
            <?php endif; ?>">

        </div>
        <?php foreach ($contact_info as $key => $ct_info):
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
            <li>
            	<?php if ( $ct_info['icon_type'] == 'icon' && $has_icon ) : ?>
			        <span class="ct-contact-icon">
		                <?php
		                    if($is_new):
		                        \Elementor\Icons_Manager::render_icon( $ct_info['ct_icon'], [ 'aria-hidden' => 'true' ] );
		                ?>
		                <?php else: ?>
		                    <i <?php ct_print_html($widget->get_render_attribute_string( $icon_key )); ?>></i>
		                <?php endif; ?>
			        </span>
			    <?php endif; ?>
                <?php if ( $ct_info['icon_type'] == 'image' && !empty($ct_info['icon_image']) ) : 
                    $img_icon  = consultio_get_image_by_size( array(
                        'attach_id'  => $ct_info['icon_image']['id'],
                        'thumb_size' => 'full',
                    ) );
                    $thumbnail_icon    = $img_icon['thumbnail'];
                    ?>
                    <span class="ct-contact-icon">
                        <?php echo wp_kses_post($thumbnail_icon); ?>
                    </span>
                <?php endif; ?>
                <?php if ( ! empty( $ct_info['link']['url'] ) ) { ?>
                    <a class="ct-contact-content ct-contact-link" <?php echo implode( ' ', [ $link_attributes ] ); ?>>
                       <?php echo wp_kses_post($ct_info['content'])?>
                    </a>
                <?php } else { ?>
                    <span class="ct-contact-content">
                       <?php echo wp_kses_post($ct_info['content'])?>
                    </span>
                <?php } ?>
           </li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>
