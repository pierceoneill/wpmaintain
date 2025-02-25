<?php
$html_id = ct_get_element_id($settings);
$tax = ['category'];
$source = $widget->get_setting('source');
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
$grid_sizer = "col-xl-{$col_xl} col-lg-{$col_lg} col-md-{$col_md} col-sm-{$col_sm} col-{$col_xs}";
$gap = intval($widget->get_setting('gap', 30));
$gap_item = intval($gap / 2);
$grid_class = '';
$layout_type = $widget->get_setting('layout_type', 'masonry');
if ($layout_type == 'masonry') {
    $grid_class = 'ct-grid-inner ct-grid-masonry row';
} else {
    $grid_class = 'ct-grid-inner row';
}
$filter = $widget->get_setting('filter', 'false');
$filter_alignment = $widget->get_setting('filter_alignment', 'center');
$thumbnail_size = $widget->get_setting('thumbnail_size', '');
$thumbnail_custom_dimension = $widget->get_setting('thumbnail_custom_dimension', '');
$pagination_type = $widget->get_setting('pagination_type', 'pagination');
$show_author = $widget->get_setting('show_author');
$show_date = $widget->get_setting('show_date');
$show_button = $widget->get_setting('show_button');
$button_text = $widget->get_setting('button_text');
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
    'show_author' => $show_author,
    'show_date' => $show_date,
    'show_button' => $show_button,
    'button_text' => $button_text,
    'ct_animate' => $ct_animate,
    'template_type' => 'post_grid_layout1',
);
?>

<div id="<?php echo esc_attr($html_id) ?>" class="ct-grid ct-blog-grid ct-blog-grid-layout1" data-layout="<?php echo esc_attr($layout_type); ?>" data-start-page="<?php echo esc_attr($paged); ?>" data-max-pages="<?php echo esc_attr($max); ?>" data-total="<?php echo esc_attr($total); ?>" data-perpage="<?php echo esc_attr($limit); ?>" data-next-link="<?php echo esc_attr($next_link); ?>">
    <div class="ct-grid-overlay"></div>
    <?php if ($filter == "true" and $layout_type == 'masonry'): ?>
        <div class="grid-filter-wrap align-<?php echo esc_attr($filter_alignment); ?>">
            <span class="filter-item active" data-filter="*"><?php echo esc_html($filter_default_title); ?></span>
            <?php foreach ($categories as $category): ?>
                <?php $category_arr = explode('|', $category); ?>
                <?php $tax[] = $category_arr[1]; ?>
                <?php $term = get_term_by('slug',$category_arr[0], $category_arr[1]); ?>
                <span class="filter-item" data-filter="<?php echo esc_attr('.filter-category-' . $term->term_id); ?>">
                    <?php echo esc_html($term->name); ?>
                </span>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <div class="<?php echo esc_attr($grid_class); ?> animate-time" data-gutter="<?php echo esc_attr($gap_item); ?>">
        <?php if ($layout_type == 'masonry') : ?>
            <div class="grid-sizer <?php echo esc_attr($grid_sizer); ?>"></div>
        <?php endif; ?>
        <?php
        $load_more['tax'] = $tax;
        consultio_get_post_grid($posts, $load_more);
        ?>
    </div>
    <?php if ($layout_type == 'masonry' && $pagination_type == 'pagination') { ?>
        <div class="ct-grid-pagination" data-loadmore="<?php echo esc_attr(json_encode($load_more)); ?>" data-query="<?php echo esc_attr(json_encode($args)); ?>">
            <?php consultio_posts_pagination($query, true); ?>
        </div>
    <?php } ?>
    <?php if (!empty($next_link) && $layout_type == 'masonry' && $pagination_type == 'loadmore') { ?>
        <div class="ct-load-more text-center" data-loadmore="<?php echo esc_attr(json_encode($load_more)); ?>">
            <span class="btn btn-outline">
                <i class="fac fac-redo"></i>
                <?php echo esc_html__('Load more', 'consultio') ?>
            </span>
        </div>
    <?php } ?>
</div>