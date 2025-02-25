<?php
$widget->add_render_attribute( 'inner', [
    'class' => 'ct-carousel-inner',
] );

$pause_on_hover = $widget->get_setting('pause_on_hover');
$autoplay = $widget->get_setting('autoplay', '');
$autoplay_speed = $widget->get_setting('autoplay_speed', '5000');
$infinite = $widget->get_setting('infinite');
$speed = $widget->get_setting('speed', '500');
if (is_rtl()) {
    $carousel_dir = 'true';
} else {
    $carousel_dir = 'false';
}
$widget->add_render_attribute( 'carousel', [
    'class' => 'ct-slick-carousel',
    'data-arrows' => 'false',
    'data-dots' => 'false',
    'data-pauseOnHover' => $pause_on_hover,
    'data-autoplay' => $autoplay,
    'data-autoplaySpeed' => $autoplay_speed,
    'data-infinite' => 'false',
    'data-speed' => $speed,
    'data-colxs' => '1',
    'data-colsm' => '1',
    'data-colmd' => '1',
    'data-collg' => '1',
    'data-colxl' => '1',
    'data-dir' => $carousel_dir,
    'data-slidesToScroll' => '1',
    'data-fade' => 'true',
] );
?>
<?php if(isset($settings['portfolio']) && !empty($settings['portfolio']) && count($settings['portfolio'])): ?>
    <div class="ct-portfolio-external1 ct-slick-slider <?php echo esc_attr($settings['ct_animate']); ?>">
        <div class="ct-portfolio-inner">
            <div <?php ct_print_html($widget->get_render_attribute_string( 'inner' )); ?>>
                <div <?php ct_print_html($widget->get_render_attribute_string( 'carousel' )); ?>>
                    <?php foreach ($settings['portfolio'] as $key => $value):
                        $category = isset($value['category']) ? $value['category'] : '';
                        $description = isset($value['description']) ? $value['description'] : '';
                        $image = isset($value['image']) ? $value['image'] : '';
                        $img = consultio_get_image_by_size( array(
                            'attach_id'  => $image['id'],
                            'thumb_size' => 'full',
                        ));
                        $thumbnail = $img['thumbnail']; 

                        $link_key = $widget->get_repeater_setting_key( 'link', 'value', $key );
                        if ( ! empty( $value['link']['url'] ) ) {
                            $widget->add_render_attribute( $link_key, 'href', $value['link']['url'] );

                            if ( $value['link']['is_external'] ) {
                                $widget->add_render_attribute( $link_key, 'target', '_blank' );
                            }

                            if ( $value['link']['nofollow'] ) {
                                $widget->add_render_attribute( $link_key, 'rel', 'nofollow' );
                            }
                        }
                        $link_attributes = $widget->get_render_attribute_string( $link_key );
                        ?>
                            <div class="slick-slide">
                                <div class="item--inner-primary">
                                    <?php if(!empty($image)) { ?>
                                        <div class="item--image bg-image" style="background-image: url(<?php echo esc_url($image['url']); ?>);"></div>
                                    <?php } ?>
                                    <div class="item--holder bg-image">
                                        <div class="item--description"><?php echo ct_print_html($description); ?></div>
                                        <div class="item--category"><?php echo esc_attr($category); ?></div>
                                        <a class="item--link" <?php echo implode( ' ', [ $link_attributes ] ); ?>><i class="flaticonv2-right-arrow"></i></a>
                                    </div>
                               </div>
                            </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="ct-slick-nav-wrap">
                <div class="el-widget el-empty"><?php echo esc_attr($settings['wg_title']); ?></div>
                <div class="ct-slick-nav" data-dir="<?php echo esc_attr($carousel_dir); ?>" data-nav="20" data-infinite="false">
                    <?php foreach ($settings['portfolio'] as $value_nav): 
                        $title = isset($value_nav['title']) ? $value_nav['title'] : '';
                         if(!empty($image['id'])) { ?>
                            <div class="slick-slide">
                                <div class="item--inner-nav">
                                    <div class="item--title">    
                                        <span><?php echo ct_print_html($title); ?></span>
                                    </div>
                               </div>
                            </div>
                        <?php } ?>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>
