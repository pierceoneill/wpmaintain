<?php
$default_settings = [
    'bg_image' => '',
    'item_list' => '',
];
$settings = array_merge($default_settings, $settings);
extract($settings);
$html_id = ct_get_element_id($settings);
?>
<?php if(isset($item_list) && !empty($item_list) && count($item_list)): ?>
	<div class="ct-point">
		<?php if(!empty($bg_image)) {
	        $img = ct_get_image_by_size( array(
	            'attach_id'  => $bg_image['id'],
	            'thumb_size' => 'full',
	        ));
	        $thumbnail = $img['thumbnail']; ?>
	        <div class="ct-point-image">
	        	<?php echo wp_kses_post($thumbnail); ?>
	        	<?php foreach ($item_list as $key => $value): 
					$icon  = consultio_get_image_by_size( array(
		                'attach_id'  => $value['icon']['id'],
		                'thumb_size' => 'full',
		            ) );
		            $icon_tb    = $icon['thumbnail'];
					?>
				    <div id="<?php echo esc_attr($html_id.$key); ?>" class="ct-point-item">
				    	<div class="ct-inline-css"  data-css="
				            .ct-point #<?php echo esc_attr($html_id.$key) ?> {
				                left: <?php echo esc_attr($value['left_positioon']['size']); ?>%;
				                top: <?php echo esc_attr($value['top_positioon']['size']); ?>%;
				            }">
				        </div>
				    	<div class="ct-point-icon">
				    		<?php echo wp_kses_post($icon_tb); ?>
				    	</div>		
				    	<div class="ct-point-meta">
				    		<?php if(!empty($value['phone'])) : ?>
				    			<div class="ct-point-phone"><?php echo ct_print_html($value['phone']); ?></div>
				    		<?php endif; ?>
				    		<?php if(!empty($value['email'])) : ?>
				    			<div class="ct-point-email"><?php echo ct_print_html($value['email']); ?></div>
				    		<?php endif; ?>
				    		<?php if(!empty($value['address'])) : ?>
				    			<div class="ct-point-address"><?php echo ct_print_html($value['address']); ?></div>
				    		<?php endif; ?>
				    	</div>
				    </div>
				<?php endforeach; ?>
	        </div>
	    <?php } ?>
	</div>
<?php endif; ?>
