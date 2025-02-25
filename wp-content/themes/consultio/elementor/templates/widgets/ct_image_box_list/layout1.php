<?php 
$default_settings = [
    'image' => '',
    'list' => '',
    'ct_animate' => '',
    'ct_animate_right' => '',
];
$settings = array_merge($default_settings, $settings);
extract($settings);

if(!empty($image['id'])) : 
    $img  = consultio_get_image_by_size( array(
        'attach_id'  => $image['id'],
        'thumb_size' => 'full',
    ) );
    $thumbnail    = $img['thumbnail'];
    ?>
    <div class="ct-image-box-list1">
        <div class="ct-image-box">
            <?php echo wp_kses_post($thumbnail); ?>
            <div class="ct-list-wrap">
                <?php foreach ($list as $key => $ct_list): 
                    if($key < 8) : ?>
                        <div class="ct-list-item <?php if($key < 4) { echo esc_attr($ct_animate); } else { echo esc_attr($ct_animate_right); } ?>">
                            <div class="ct-list-number"><?php echo '0'.esc_attr($key+1); ?></div>
                            <div class="ct-list-content">
                                <?php echo ct_print_html($ct_list['content'])?>
                            </div>
                       </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="ct-list-hidden">
            <?php foreach ($list as $key => $ct_list): 
                if($key < 8) : ?>
                    <div class="ct-list-item">
                        <div class="ct-list-number"><?php echo '0'.esc_attr($key+1); ?></div>
                        <div class="ct-list-content">
                            <?php echo ct_print_html($ct_list['content'])?>
                        </div>
                   </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </div>
<?php endif; ?>