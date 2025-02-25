<?php
$html_id = ct_get_element_id($settings);
$tax = ['service-category'];
$source = $widget->get_setting('source', '');
$orderby = $widget->get_setting('orderby', 'date');
$order = $widget->get_setting('order', 'desc');
$limit = $widget->get_setting('limit', 8);
$post_ids = $widget->get_setting('post_ids', '');
extract(ct_get_posts_of_grid('service', [
    'source' => $source,
    'orderby' => $orderby,
    'order' => $order,
    'limit' => $limit,
    'post_ids' => $post_ids,
]));
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
$show_title = $widget->get_setting('show_title');
$show_excerpt = $widget->get_setting('show_excerpt');
$num_words = $widget->get_setting('num_words');
$show_button = $widget->get_setting('show_button');
$ct_animate = $widget->get_setting('ct_animate');
$load_more = array(
    'posttype' => 'service',
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
    'show_title' => $show_title,
    'show_excerpt' => $show_excerpt,
    'num_words' => $num_words,
    'show_button' => $show_button,
    'ct_animate' => $ct_animate,
    'template_type' => 'service_layout4',
);
?>

<div id="<?php echo esc_attr($html_id) ?>" class="ct-grid ct-service-grid4" data-layout="<?php echo esc_attr($layout_type); ?>" data-start-page="<?php echo esc_attr($paged); ?>" data-max-pages="<?php echo esc_attr($max); ?>" data-total="<?php echo esc_attr($total); ?>" data-perpage="<?php echo esc_attr($limit); ?>" data-next-link="<?php echo esc_attr($next_link); ?>">
    <?php if(!empty($settings['el_color1']) && !empty($settings['el_color2'])) : ?>
        <div class="ct-inline-css"  data-css="
            #<?php echo esc_attr($html_id) ?> .item--title i.icon-left, #<?php echo esc_attr($html_id) ?> .item--title i.icon-right {
                background-image: -webkit-gradient(linear, left top, left bottom, from(<?php echo esc_attr($settings['el_color1']); ?>), to(<?php echo esc_attr($settings['el_color2']); ?>));
                background-image: -webkit-linear-gradient(top, <?php echo esc_attr($settings['el_color1']); ?>, <?php echo esc_attr($settings['el_color2']); ?>);
                background-image: -moz-linear-gradient(top, <?php echo esc_attr($settings['el_color1']); ?>, <?php echo esc_attr($settings['el_color2']); ?>);
                background-image: -ms-linear-gradient(top, <?php echo esc_attr($settings['el_color1']); ?>, <?php echo esc_attr($settings['el_color2']); ?>);
                background-image: -o-linear-gradient(top, <?php echo esc_attr($settings['el_color1']); ?>, <?php echo esc_attr($settings['el_color2']); ?>);
                background-image: linear-gradient(top, <?php echo esc_attr($settings['el_color1']); ?>, <?php echo esc_attr($settings['el_color2']); ?>);
                filter: progid:DXImageTransform.Microsoft.gradient(startColorStr='<?php echo esc_attr($settings['el_color1']); ?>', endColorStr='<?php echo esc_attr($settings['el_color2']); ?>');
                background-color: transparent;
            }
            #<?php echo esc_attr($html_id) ?> .grid-item-inner:before {
                background-image: -webkit-gradient(linear, left top, right top, from(<?php echo esc_attr($settings['el_color2']); ?>), to(<?php echo esc_attr($settings['el_color1']); ?>));
                background-image: -webkit-linear-gradient(left, <?php echo esc_attr($settings['el_color2']); ?>, <?php echo esc_attr($settings['el_color1']); ?>);
                background-image: -moz-linear-gradient(left, <?php echo esc_attr($settings['el_color2']); ?>, <?php echo esc_attr($settings['el_color1']); ?>);
                background-image: -ms-linear-gradient(left, <?php echo esc_attr($settings['el_color2']); ?>, <?php echo esc_attr($settings['el_color1']); ?>);
                background-image: -o-linear-gradient(left, <?php echo esc_attr($settings['el_color2']); ?>, <?php echo esc_attr($settings['el_color1']); ?>);
                background-image: linear-gradient(left, <?php echo esc_attr($settings['el_color2']); ?>, <?php echo esc_attr($settings['el_color1']); ?>);
                filter: progid:DXImageTransform.Microsoft.gradient(startColorStr='<?php echo esc_attr($settings['el_color2']); ?>', endColorStr='<?php echo esc_attr($settings['el_color1']); ?>', gradientType='1');
            }
            #<?php echo esc_attr($html_id) ?> .ct-load-more .btn, #<?php echo esc_attr($html_id) ?> .ct-load-more .btn:hover, #<?php echo esc_attr($html_id) ?> .ct-load-more .btn:focus {
                background-image: none !important;
                background-color: <?php echo esc_attr($settings['el_color1']); ?>;
            }
            #<?php echo esc_attr($html_id) ?> .ct-load-more .btn:after {
                display: block;
            }">
        </div>
    <?php endif; ?>
    <div class="ct-grid-overlay"></div>
    <?php if ($filter == "true" and $layout_type == 'masonry'): ?>
        <div class="grid-filter-wrap align-<?php echo esc_attr($filter_alignment); ?>">
            <span class="filter-item active" data-filter="*"><?php echo esc_html($filter_default_title); ?></span>
            <?php foreach ($categories as $category): ?>
                <?php $category_arr = explode('|', $category); ?>
                <?php $tax[] = $category_arr[1]; ?>
                <?php $term = get_term_by('slug',$category_arr[0], $category_arr[1]); ?>

                <span class="filter-item" data-filter="<?php echo esc_attr('.' . $term->slug); ?>">
                    <?php echo esc_html($term->name); ?>
                </span>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <div class="<?php echo esc_attr($grid_class); ?> animate-time" data-gutter="<?php echo esc_attr($gap_item); ?>">
        <?php
        $load_more['tax'] = $tax;
        consultio_get_post_grid($posts, $load_more);
        ?>
        <?php if ($layout_type == 'masonry') : ?>
            <div class="grid-sizer <?php echo esc_attr($grid_sizer); ?>"></div>
        <?php endif; ?>
    </div>
    <?php if ($layout_type == 'masonry' && $pagination_type == 'pagination') { ?>
        <div class="ct-grid-pagination" data-loadmore="<?php echo esc_attr(json_encode($load_more)); ?>" data-query="<?php echo esc_attr(json_encode($args)); ?>">
            <?php consultio_posts_pagination($query, true); ?>
        </div>
    <?php } ?>
    <?php if (!empty($next_link) && $layout_type == 'masonry' && $pagination_type == 'loadmore') { ?>
        <div class="ct-load-more text-center" data-loadmore="<?php echo esc_attr(json_encode($load_more)); ?>">
            <span class="btn btn-default">
                <i class="fac fac-redo"></i>
                <?php echo esc_html__('Load more', 'consultio') ?>
            </span>
        </div>
    <?php } ?>
</div>