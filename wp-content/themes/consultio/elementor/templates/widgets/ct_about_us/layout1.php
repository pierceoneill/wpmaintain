<?php
$default_settings = [
    'image' => '',
    'sub_title' => '',
    'title' => '',
    'desc' => '',
    'icon_box' => '',
];
$settings = array_merge($default_settings, $settings);
extract($settings);
$img  = ct_get_image_by_size( array(
    'attach_id'  => $image['id'],
    'thumb_size' => '306x306',
) );
$thumbnail    = $img['thumbnail'];
$is_new = \Elementor\Icons_Manager::is_migration_allowed();
?>
<div class="ct-about-us">
	<div class="ct-about-left">
		<?php if($image['id']) : ?>
			<div class="ct-about-imge"><?php echo wp_kses_post($thumbnail); ?></div>
		<?php endif; ?>
	</div>
	<div class="ct-about-right">
		<div class="ct-about-holder">
	        <div class="ct-about-subtitle"><span><?php echo esc_attr($sub_title); ?></span></div>
	        <h3 class="ct-about-title"><?php echo esc_attr($title); ?></h3>
	        <div class="ct-about-desc"><?php echo ct_print_html($desc); ?></div>
	        <?php if(isset($icon_box) && !empty($icon_box) && count($icon_box)): ?>
			    <div class="ct-about-meta">
			        <?php
			        	foreach ($icon_box as $key => $ct_box): 
			        	$icon_key = $widget->get_repeater_setting_key( 'ib_icon', 'icons', $key );
			            $has_icon = ! empty( $ct_box['ib_icon'] );
			            $widget->add_render_attribute( $icon_key, [
			                'class' => $ct_box['ib_icon'],
			                'aria-hidden' => 'true',
			            ] );
			        	?>
			            <div class="ct-box-item">
			            	<div class="ct-box-icon">
			            		<?php if ( !empty($icon_box) ) : ?>
					                <?php
				                        if($is_new):
				                            \Elementor\Icons_Manager::render_icon( $ct_box['ib_icon'], [ 'aria-hidden' => 'true' ] );
				                    ?>
				                    <?php else: ?>
				                        <i <?php ct_print_html($widget->get_render_attribute_string( $icon_key )); ?>></i>
				                    <?php endif; ?>
					            <?php endif; ?>
			            	</div>
			            	<div class="ct-box-content">
			            		<h5 class="ct-box-title"><?php echo esc_attr($ct_box['ib_title']); ?></h5>
				            	<div class="ct-box-desc">
				            		<?php echo ct_print_html($ct_box['ib_content']); ?>
				            	</div>
				            </div>
			           </div>
			        <?php endforeach; ?>
			    </div>
			<?php endif; ?>
	    </div>
	</div>
</div>