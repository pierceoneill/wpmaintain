<?php
$default_settings = [
    'col_xl' => '4',
    'col_lg' => '4',
    'col_md' => '3',
    'col_sm' => '2',
    'col_xs' => '1',
    'ct_animate' => '',
];
$settings = array_merge($default_settings, $settings);
extract($settings);
$ct_col_xl_tmp = $col_xl;
$ct_col_lg_tmp = $col_lg;

$col_xl = 12 / intval($col_xl);
$col_lg = 12 / intval($col_lg);
$col_md = 12 / intval($col_md);
$col_sm = 12 / intval($col_sm);
$col_xs = 12 / intval($col_xs);

if($ct_col_xl_tmp == '5') {
    $col_xl = '25';
}

if($ct_col_lg_tmp == '5') {
    $col_lg = '25';
}

$grid_sizer = "col-xl-{$col_xl} col-lg-{$col_lg} col-md-{$col_md} col-sm-{$col_sm} col-{$col_xs}";
$item_class = "grid-item col-xl-{$col_xl} col-lg-{$col_lg} col-md-{$col_md} col-sm-{$col_sm} col-{$col_xs}";
$img_size = '300x300';
if(!empty($settings['img_size'])) {
    $img_size = $settings['img_size'];
}
?>
<?php if(isset($settings['image']) && !empty($settings['image']) && count($settings['image'])): ?>
    <div class="ct-grid ct-gallery-grid1">
        <div class="ct-grid-inner ct-grid-masonry row animate-time" data-gutter="7">
            <?php foreach ($settings['image'] as $value): 
                $img = ct_get_image_by_size( array(
                    'attach_id'  => $value['id'],
                    'thumb_size' => $img_size,
                    'class' => 'no-lazyload',
                ));
                $thumbnail = $img['thumbnail']; 
                $thumbnail_url = wp_get_attachment_image_src($value['id'], 'full');
                ?>
                <div class="<?php echo esc_attr($item_class); ?>">
                    <div class="item--inner <?php echo esc_attr($ct_animate); ?>" data-wow-duration="1.2s">
                        <a href="<?php echo esc_url($thumbnail_url[0]); ?>"><i class="fac fa-search"></i></a>
                        <?php echo wp_kses_post($thumbnail); ?>
                   </div>
                </div>
            <?php endforeach; ?>
            <div class="grid-sizer <?php echo esc_attr($grid_sizer); ?>"></div>
        </div>
    </div>
<?php endif; ?>
