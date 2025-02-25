<?php
$default_settings = [
    'title' => '',
    'desc' => '',
    'image' => '',
    'img_size' => '',
];
$settings = array_merge($default_settings, $settings);
extract($settings);
$img_size = isset($img_size) ? $img_size : 'full';
if ( ! empty( $image['url'] ) ) { 
    $img  = consultio_get_image_by_size( array(
        'attach_id'  => $image['id'],
        'thumb_size' => $img_size,
    ) );
    $thumbnail    = $img['thumbnail'];
    ?>
    <div class="ct-banner4 <?php echo esc_attr($settings['ct_animate']); ?>" data-wow-delay="<?php echo esc_attr($settings['ct_animate_delay']); ?>ms">
    	<div class="ct-banner-imge">
        	<?php echo wp_kses_post($thumbnail); ?>
        </div>
        <div class="ct-banner-holder">
    		<h4 class="ct-banner-title"><?php echo esc_attr($title); ?></h4>
            <div class="ct-banner-desc"><?php echo esc_attr($desc); ?></div>
            <span class="ct-banner-arrow"><span></span></span>
        </div>
    </div>
<?php } ?>