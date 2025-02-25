<?php

$html_id = ct_get_element_id($settings);
$source = $widget->get_setting('source', '');
$orderby = $widget->get_setting('orderby', 'date');
$order = $widget->get_setting('order', 'desc');
$limit = $widget->get_setting('limit', 6);
$post_ids = $widget->get_setting('post_ids', '');
extract(ct_get_posts_of_grid('post', [
    'source' => $source,
    'orderby' => $orderby,
    'order' => $order,
    'limit' => $limit,
    'post_ids' => $post_ids,
]));

$widget->add_render_attribute( 'inner', [
    'class' => 'ct-carousel-inner',
] );

$col_xs = $widget->get_setting('col_xs', '');
$col_sm = $widget->get_setting('col_sm', '');
$col_md = $widget->get_setting('col_md', '');
$col_lg = $widget->get_setting('col_lg', '');
$col_xl = $widget->get_setting('col_xl', '');
$slides_to_scroll = $widget->get_setting('slides_to_scroll', '');


$arrows = $widget->get_setting('arrows');
$dots = $widget->get_setting('dots');
$pause_on_hover = $widget->get_setting('pause_on_hover');
$autoplay = $widget->get_setting('autoplay');
$autoplay_speed = $widget->get_setting('autoplay_speed', '5000');
$infinite = $widget->get_setting('infinite');
$speed = $widget->get_setting('speed', '500');

$show_author = $widget->get_setting('show_author');
$show_date = $widget->get_setting('show_date');
$show_category = $widget->get_setting('show_category');
$show_button = $widget->get_setting('show_button');
$button_text = $widget->get_setting('button_text');
$show_excerpt = $widget->get_setting('show_excerpt');
$num_words = $widget->get_setting('num_words');

if (is_rtl()) {
    $carousel_dir = 'true';
} else {
    $carousel_dir = 'false';
}

$widget->add_render_attribute( 'carousel', [
    'class' => 'ct-slick-carousel',
    'data-arrows' => $arrows,
    'data-dots' => $dots,
    'data-pauseOnHover' => $pause_on_hover,
    'data-autoplay' => $autoplay,
    'data-autoplaySpeed' => $autoplay_speed,
    'data-infinite' => $infinite,
    'data-speed' => $speed,
    'data-dir' => $carousel_dir,
    'data-colxs' => $col_xs,
    'data-colsm' => $col_sm,
    'data-colmd' => $col_md,
    'data-collg' => $col_lg,
    'data-colxl' => $col_xl,
    'data-slidesToScroll' => $slides_to_scroll,
] );

$title_tag = $widget->get_setting('title_tag', 'h3');

$thumbnail_size = $widget->get_setting('thumbnail_size', 'full');
$thumbnail_custom_dimension = $widget->get_setting('thumbnail_custom_dimension', '');
if($thumbnail_size != 'custom'){
    $img_size = $thumbnail_size;
}
elseif(!empty($thumbnail_custom_dimension['width']) && !empty($thumbnail_custom_dimension['height'])){
    $img_size = $thumbnail_custom_dimension['width'] . 'x' . $thumbnail_custom_dimension['height'];
}
else{
    $img_size = '270x320';
}
switch ($settings['style_l7']) {
    case 'style2':
        $dot_style = 'dot-style-u2';
        break;
    
    case 'style3':
        $dot_style = 'dot-style-u4';
        break;

    case 'style4':
        $dot_style = 'dot-style-u5';
        break;

    case 'style5':
        $dot_style = 'dot-style-u11';
        break;

    default:
        $dot_style = 'dot-style-u1';
        break;
}

?>
<?php if (is_array($posts)): ?>

<div id="<?php echo esc_attr($html_id) ?>" class="ct-blog-carousel-layout7 <?php echo esc_attr($dot_style); ?> ct-slick-slider <?php echo esc_attr($settings['style_l7']); ?>">
    <div <?php ct_print_html($widget->get_render_attribute_string( 'inner' )); ?>>
        <div <?php ct_print_html($widget->get_render_attribute_string( 'carousel' )); ?>>

        <?php
            foreach ($posts as $post):
            $img_id       = get_post_thumbnail_id( $post->ID );
            $author = get_user_by('id', $post->post_author); 
            $external_url = get_post_meta($post->ID, 'external_url', true);
            ?>
            <div class="carousel-item slick-slide">
                <div class="grid-item-inner <?php echo esc_attr($settings['ct_animate']); ?>">
                    <?php if (has_post_thumbnail($post->ID) && wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), false)):
                        $img          = consultio_get_image_by_size( array(
                            'attach_id'  => $img_id,
                            'thumb_size' => $img_size,
                        ) );
                        $thumbnail    = $img['thumbnail'];
                        $thumbnail_url = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), false);
                        ?>
                        <div class="item--featured">
                            <a class="bg-image" href="<?php if(!empty($external_url)) { echo esc_url($external_url); } else { echo esc_url(get_permalink( $post->ID )); } ?>" style="background-image: url(<?php echo esc_url($thumbnail_url[0]); ?>);"></a>
                            <?php if($show_date == 'true'): ?>
                                <div class="item--date">
                                    <div class="item--date-inner">
                                        <span><?php echo get_the_date('d', $post->ID); ?></span>
                                        <span><?php echo get_the_date('M', $post->ID); ?></span>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                    <div class="item--body">
                        <?php if($show_category == 'true') : ?>
                            <div class="item--category">
                                <?php the_terms( $post->ID, 'category', '', ', ' ); ?>
                            </div>
                        <?php endif; ?>
                        <h3 class="item--title"><a href="<?php if(!empty($external_url)) { echo esc_url($external_url); } else { echo esc_url(get_permalink( $post->ID )); } ?>"><?php echo ct_print_html(get_the_title($post->ID)); ?></a></h3>
                        <?php if($show_excerpt == 'true'): ?>
                            <div class="item--content">
                                <?php echo wp_trim_words( $post->post_excerpt, $num_words, $more = null ); ?>
                            </div>
                        <?php endif; ?>
                        <?php if($show_button == 'true') : ?>
                            <div class="item--readmore">
                                <a class="btn btn-effect" href="<?php if(!empty($external_url)) { echo esc_url($external_url); } else { echo esc_url(get_permalink( $post->ID )); } ?>">
                                    <span>
                                        <?php if(!empty($button_text)) {
                                            echo esc_attr($button_text);
                                        } else {
                                            echo esc_html__('Read more', 'consultio');
                                        } ?>
                                    </span>
                                    <i class="<?php if($settings['style_l7'] == 'style1' || $settings['style_l7'] == 'style5') { echo 'flaticonv2-right-arrow'; } else { echo 'fac fac-plus'; } ?>"></i>
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
        </div>
    </div>
</div>
<?php endif; ?>