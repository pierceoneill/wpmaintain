<?php
$default_settings = [
    'image' => '',
    'title' => '',
    'sub_title' => '',
    'style' => '',
    'ct_animate' => '',
];
$settings = array_merge($default_settings, $settings);
extract($settings);
$img  = consultio_get_image_by_size( array(
    'attach_id'  => $image['id'],
    'thumb_size' => 'full',
) );
$thumbnail = $img['thumbnail']; ?>
<div class="ct-signature1 <?php echo esc_attr($ct_animate.' '.$style); ?>">
	<?php if(!empty($image['id'])) : ?>
        <div class="signature-image">
        	<?php echo wp_kses_post($thumbnail); ?>
        </div>
    <?php endif; ?>
    <div class="signature-meta">
    	<h3><?php echo esc_attr($title); ?></h3>
    	<span><?php echo esc_attr($sub_title); ?></span>
    </div>
</div>
