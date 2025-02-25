<?php 
$default_settings = [
    'image' => '',
    'img_size' => 'full',
    'background_overlay' => '',
    'image_type' => '',
    'video_title' => '',
    'btn_video_style' => '',
];
$settings = array_merge($default_settings, $settings);
extract($settings);
?>
<div class="ct-video-player video-player-<?php echo esc_attr($settings['btn_video_style']); ?> <?php echo esc_attr($settings['ct_animate']); ?> <?php if(!empty($image['url'])) { echo 'img-added'; } ?>">
    <?php if(!empty($background_overlay) && !empty($image['url'])) : ?>
        <div class="bg-overlay"></div>
    <?php endif; ?>
    <?php if ($image_type == 'img' && !empty($image['id'])) { 
        $img  = consultio_get_image_by_size( array(
            'attach_id'  => $image['id'],
            'thumb_size' => $img_size,
        ) );
        $thumbnail   = $img['thumbnail'];
        echo wp_kses_post($thumbnail);
    } elseif($image_type == 'bg' && !empty($image['id'])) { ?>
        <div class="ct-video-image-bg bg-image" style="background-image: url(<?php echo esc_url($image['url']); ?>);"></div>
    <?php } ?>
    <?php if(!empty($settings['video_link'])) : ?>
        <a class="ct-video-button <?php echo esc_attr($settings['btn_video_style']); ?>" href="<?php echo esc_url($settings['video_link']); ?>">
            <i class="fac fac-play"></i>
            <span class="line-video-animation line-video-1"></span>
            <span class="line-video-animation line-video-2"></span>
            <span class="line-video-animation line-video-3"></span>
        </a>
    <?php endif; ?>
    <span></span>
</div>
<?php if($btn_video_style == 'style9' && !empty($video_title)) : ?>
    <div class="ct-video-title"><?php echo ct_print_html($video_title); ?></div>
<?php endif; ?>