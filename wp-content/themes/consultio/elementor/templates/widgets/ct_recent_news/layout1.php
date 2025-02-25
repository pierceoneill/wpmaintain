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

$show_date = $widget->get_setting('show_date');
$btn_text = $widget->get_setting('btn_text');
$btn_link = $widget->get_setting('btn_link');

if ( ! empty( $btn_link['url'] ) ) {
    $widget->add_render_attribute( 'button', 'href', $btn_link['url'] );

    if ( $btn_link['is_external'] ) {
        $widget->add_render_attribute( 'button', 'target', '_blank' );
    }

    if ( $btn_link['nofollow'] ) {
        $widget->add_render_attribute( 'button', 'rel', 'nofollow' );
    }
}

if (is_array($posts)): ?>

<div id="<?php echo esc_attr($html_id) ?>" class="ct-recent-news ct-recent-news1">
    <div class="ct-recent-news-posts">
        <?php
            foreach ($posts as $post):
            $img_id       = get_post_thumbnail_id( $post->ID );
            $img          = ct_get_image_by_size( array(
                'attach_id'  => $img_id,
                'thumb_size' => '500x316',
            ) );
            $thumbnail    = $img['thumbnail']; ?>
            <div class="ct-recent-new-item">
                <?php if (has_post_thumbnail($post->ID) && wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), false)): ?>
                    <div class="item--image">
                        <a href="<?php echo esc_url(get_permalink( $post->ID )); ?>"><?php echo wp_kses_post($thumbnail); ?></a>
                    </div>
                <?php endif; ?>
                <div class="item--meta">
                    <?php if($show_date == 'true'): ?>
                        <div class="item--date"><?php $date_formart = get_option('date_format'); echo get_the_date($date_formart, $post->ID); ?></div>
                    <?php endif; ?>
                    <h3 class="item--title"><a href="<?php echo esc_url(get_permalink( $post->ID )); ?>"><?php echo esc_attr(get_the_title($post->ID)); ?></a></h3>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <?php if(!empty($btn_text)) : ?>
        <div class="ct-recent-news-button">
            <a <?php ct_print_html($widget->get_render_attribute_string( 'button' )); ?>><?php echo esc_attr($btn_text); ?><i class="flaticonv6-right-arrow"></i></a>
        </div>
    <?php endif; ?>
</div>
<?php endif; ?>