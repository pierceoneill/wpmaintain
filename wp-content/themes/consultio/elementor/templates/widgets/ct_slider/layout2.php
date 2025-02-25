<?php
$default_settings = [
    'slider' => '',
];
$settings = array_merge($default_settings, $settings);
extract($settings);
$widget->add_render_attribute( 'inner', [
    'class' => 'ct-carousel-inner',
] );
$box_img = $widget->get_setting('box_img');
$arrows = $widget->get_setting('arrows');
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
    'data-arrows' => $arrows,
    'data-dots' => 'false',
    'data-pauseOnHover' => $pause_on_hover,
    'data-autoplay' => $autoplay,
    'data-autoplaySpeed' => $autoplay_speed,
    'data-infinite' => $infinite,
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
<?php if(isset($slider) && !empty($slider) && count($slider)): ?>
<div class="ct--slider-wrap2">
    <div class="ct--slider ct--slider2 ct-slick-slider">
        <div class="ct--slider-number">
            <div <?php ct_print_html($widget->get_render_attribute_string( 'inner' )); ?>>
                <div <?php ct_print_html($widget->get_render_attribute_string( 'carousel' )); ?>>
                    <?php $count = 0; foreach ($slider as $key => $value_number) :
                        $count++; ?>
                        <div class="slick-slide">
                            <div class="count-number">
                                <span><?php echo '0'.esc_attr($count); ?></span>
                                <span><?php echo '0'.count($slider); ?></span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <?php if($arrows) : ?>
            <div class="ct-nav-carousel">
                <div class="nav-prev"><svg enable-background="new 0 0 2000 2000" height="512" viewBox="0 0 2000 2000" width="512" xmlns="http://www.w3.org/2000/svg"><g><g><path d="m1000 1436c-16.4 0-32.8-6.2-45.3-18.7l-744-744c-25-25-25-65.5 0-90.5s65.5-25 90.5 0l698.7 698.7 698.7-698.7c25-25 65.5-25 90.5 0s25 65.5 0 90.5l-744 744c-12.3 12.5-28.7 18.7-45.1 18.7z"/></g></g></svg></div>
                <div class="nav-next"><svg enable-background="new 0 0 2000 2000" height="512" viewBox="0 0 2000 2000" width="512" xmlns="http://www.w3.org/2000/svg"><g><g><path d="m1000 1436c-16.4 0-32.8-6.2-45.3-18.7l-744-744c-25-25-25-65.5 0-90.5s65.5-25 90.5 0l698.7 698.7 698.7-698.7c25-25 65.5-25 90.5 0s25 65.5 0 90.5l-744 744c-12.3 12.5-28.7 18.7-45.1 18.7z"/></g></g></svg></div>
            </div>
        <?php endif; ?>
        <div <?php ct_print_html($widget->get_render_attribute_string( 'inner' )); ?>>
            <div <?php ct_print_html($widget->get_render_attribute_string( 'carousel' )); ?>>
                <?php foreach ($slider as $key => $value) :
                    $link_key = $widget->get_repeater_setting_key( 'title', 'value', $key );
                    if ( ! empty( $value['btn_link']['url'] ) ) {
                        $widget->add_render_attribute( $link_key, 'href', $value['btn_link']['url'] );

                        if ( $value['btn_link']['is_external'] ) {
                            $widget->add_render_attribute( $link_key, 'target', '_blank' );
                        }

                        if ( $value['btn_link']['nofollow'] ) {
                            $widget->add_render_attribute( $link_key, 'rel', 'nofollow' );
                        }
                    }
                    $link_attributes = $widget->get_render_attribute_string( $link_key );
                    $title = isset($value['title']) ? $value['title'] : '';
                    $desc = isset($value['desc']) ? $value['desc'] : '';
                    $btn_text = isset($value['btn_text']) ? $value['btn_text'] : '';
                    $text_outline = isset($value['text_outline']) ? $value['text_outline'] : '';
                    $image = isset($value['image']) ? $value['image'] : '';
                    ?>
                    <div class="slick-slide">
                        <div class="item--inner">
                            <div class="item--meta bg-image <?php if(!empty($image['id'])) { echo 'img-active'; } ?>" <?php if(!empty($box_img['url'])) : ?>style="background-image: url(<?php echo esc_url($box_img['url']); ?>);"<?php endif; ?>>
                                <div class="item--meta-inner">
                                    <h3 class="item--title el-empty"><?php echo ct_print_html($title); ?></h3>
                                    <div class="item--desc el-empty"><?php echo ct_print_html($desc); ?></div>
                                    <?php if(!empty($btn_text)) : ?>
                                        <div class="item--button"><a class="btn btn-effect2" <?php echo implode( ' ', [ $link_attributes ] ); ?>><?php echo ct_print_html($btn_text); ?></a></div>
                                    <?php endif; ?>
                                </div>
                                <div class="item--textoutline el-empty"><?php echo ct_print_html($text_outline); ?></div>
                            </div>
                            <?php if(!empty($image['id'])) : ?>
                                <div class="item--image">
                                    <div class="item--image-inner">
                                        <div class="image-animation-zoom bg-image" style="background-image: url(<?php echo esc_url($image['url']); ?>);"></div>
                                    </div>
                                </div>
                            <?php endif; ?>
                       </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>
