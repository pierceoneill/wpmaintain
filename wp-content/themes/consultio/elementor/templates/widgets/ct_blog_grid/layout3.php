<?php
$html_id = ct_get_element_id($settings);
$tax = ['category'];
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
], ['category']));
$filter_default_title = $widget->get_setting('filter_default_title', 'All');
$col_xl = 12 / intval($widget->get_setting('col_xl', 4));
$col_lg = 12 / intval($widget->get_setting('col_lg', 4));
$col_md = 12 / intval($widget->get_setting('col_md', 3));
$col_sm = 12 / intval($widget->get_setting('col_sm', 2));
$col_xs = 12 / intval($widget->get_setting('col_xs', 1));
$grid_sizer = "col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12";
$gap = intval($widget->get_setting('gap', 30));
$gap_item = intval($gap / 2);
$grid_class = '';
$layout_type = $widget->get_setting('layout_type', 'masonry');
if ($layout_type == 'masonry') {
    $grid_class = 'ct-grid-inner ct-grid-masonry row';
} else {
    $grid_class = 'ct-grid-inner row';
}
$style = $widget->get_setting('style', 'style-dark');
$filter = $widget->get_setting('filter', 'false');
$filter_alignment = $widget->get_setting('filter_alignment', 'center');
$thumbnail_size = $widget->get_setting('thumbnail_size', '');
$thumbnail_custom_dimension = $widget->get_setting('thumbnail_custom_dimension', '');
$pagination_type = $widget->get_setting('pagination_type', 'pagination');
$show_meta = $widget->get_setting('show_meta');
$show_author = $widget->get_setting('show_author');
$show_post_date = $widget->get_setting('show_post_date');
$show_excerpt = $widget->get_setting('show_excerpt');
$num_words = $widget->get_setting('num_words');
$ct_animate = $widget->get_setting('ct_animate');
$load_more = array(
    'startPage' => $paged,
    'maxPages'  => $max,
    'total'     => $total,
    'perpage'   => $limit,
    'nextLink'  => $next_link,
    'source' => $source,
    'orderby' => $orderby,
    'order' => $order,
    'limit' => $limit,
    'post_ids' => $post_ids,
    'col_xl'    => $col_xl,
    'col_lg'    => $col_lg,
    'col_md'    => $col_md,
    'col_sm'    => $col_sm,
    'col_xs'    => $col_xs,
    'thumbnail_size'  => $thumbnail_size,
    'thumbnail_custom_dimension'  => $thumbnail_custom_dimension,
    'pagination_type' => $pagination_type,
    'show_meta' => $show_meta,
    'show_author' => $show_author,
    'show_post_date' => $show_post_date,
    'show_excerpt' => $show_excerpt,
    'num_words' => $num_words,
    'ct_animate' => $ct_animate,
    'template_type' => 'post_grid_layout3',
);
?>

<div id="<?php echo esc_attr($html_id) ?>" class="ct-grid ct-blog-grid ct-blog-grid-layout3 <?php echo esc_attr($style); ?>">

    <div class="<?php echo esc_attr($grid_class); ?>" data-gutter="<?php echo esc_attr($gap_item); ?>">
        <div class="grid-sizer <?php echo esc_attr($grid_sizer); ?>"></div>
        <?php $load_more['tax'] = $tax;
            consultio_get_post_grid($posts, $load_more);
        ?>
    </div>
</div>