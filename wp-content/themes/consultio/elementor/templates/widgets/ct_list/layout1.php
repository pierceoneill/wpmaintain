<?php
$default_settings = [
    'list' => '',
    'style' => 'style1',
    'column' => '',
    'ct_animate' => '',
    'ct_animate_delay' => '',
];
$settings = array_merge($default_settings, $settings);
extract($settings);
?>
<?php if(isset($list) && !empty($list) && count($list)): ?>
    <div class="ct-list <?php echo esc_attr($style.' '.$column); ?>">
        <?php
        	foreach ($list as $key => $ct_list): 
            $link_key = $widget->get_repeater_setting_key( 'title', 'value', $key );
            if ( ! empty( $ct_list['link']['url'] ) ) {
                $widget->add_render_attribute( $link_key, 'href', $ct_list['link']['url'] );

                if ( $ct_list['link']['is_external'] ) {
                    $widget->add_render_attribute( $link_key, 'target', '_blank' );
                }

                if ( $ct_list['link']['nofollow'] ) {
                    $widget->add_render_attribute( $link_key, 'rel', 'nofollow' );
                }
            }
            $link_attributes = $widget->get_render_attribute_string( $link_key );
            ?>
            <div class="ct-list-item <?php echo esc_attr($ct_animate); ?>" data-wow-delay="<?php echo esc_attr($ct_animate_delay); ?>ms">
            	<div class="ct-list-icon"><i class="fac fac-check-circle"></i></div>
            	<div class="ct-list-meta">
	            	<div class="ct-list-desc">
	            		<?php echo ct_print_html($ct_list['content'])?>
	            	</div>
                    <?php if ( ! empty( $ct_list['link']['url'] ) ) { ?>
                        <a class="item--link" <?php echo implode( ' ', [ $link_attributes ] ); ?>></a>
                    <?php } ?>
	            </div>
           </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
