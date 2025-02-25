<?php
$default_settings = [
    'download' => '',
];
$settings = array_merge($default_settings, $settings);
extract($settings);
$is_new = \Elementor\Icons_Manager::is_migration_allowed();
?>
<?php if(isset($download) && !empty($download) && count($download)): ?>
    <div class="ct-download">
        <?php foreach ($download as $key => $ct_download):
        	$icon_key = $widget->get_repeater_setting_key( 'ct_icon', 'icons', $key );
            $has_icon = ! empty( $ct_download['ct_icon'] );
            $widget->add_render_attribute( $icon_key, [
                'class' => $ct_download['ct_icon'],
                'aria-hidden' => 'true',
            ] );

        	$link_key = $widget->get_repeater_setting_key( 'title', 'download', $key );
        	if ( ! empty( $ct_download['link']['url'] ) ) {
			    $widget->add_render_attribute( $link_key, 'href', $ct_download['link']['url'] );

			    if ( $ct_download['link']['is_external'] ) {
			        $widget->add_render_attribute( $link_key, 'target', '_blank' );
			    }

			    if ( $ct_download['link']['nofollow'] ) {
			        $widget->add_render_attribute( $link_key, 'rel', 'nofollow' );
			    }
			}
			$link_attributes = $widget->get_render_attribute_string( $link_key );
        	?>
            <div class="item--download">
                <a <?php echo implode( ' ', [ $link_attributes ] ); ?>>
            	<?php
                    if($is_new):
                        \Elementor\Icons_Manager::render_icon( $ct_download['ct_icon'], [ 'aria-hidden' => 'true' ] );
                ?>
                <?php else: ?>
                    <i <?php ct_print_html($widget->get_render_attribute_string( $icon_key )); ?>></i>
                <?php endif; ?>
                <span><?php ct_print_html(nl2br($ct_download['title'])); ?></span>
            	</a>
           </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
