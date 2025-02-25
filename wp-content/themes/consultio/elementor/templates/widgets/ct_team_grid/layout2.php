<?php
$default_settings = [
    'col_xl' => '4',
    'col_lg' => '4',
    'col_md' => '3',
    'col_sm' => '2',
    'col_xs' => '1',
    'content_list2' => '',
    'thumbnail_size' => '',
    'thumbnail_custom_dimension' => '',
    'bar_color_style' => '',
    'ct_animate' => '',
];
$settings = array_merge($default_settings, $settings);
extract($settings);
$col_xl = 12 / intval($col_xl);
$col_lg = 12 / intval($col_lg);
$col_md = 12 / intval($col_md);
$col_sm = 12 / intval($col_sm);
$col_xs = 12 / intval($col_xs);
$grid_sizer = "col-xl-{$col_xl} col-lg-{$col_lg} col-md-{$col_md} col-sm-{$col_sm} col-{$col_xs}";
$item_class = "grid-item col-xl-{$col_xl} col-lg-{$col_lg} col-md-{$col_md} col-sm-{$col_sm} col-{$col_xs}";
if($thumbnail_size != 'custom'){
    $img_size = $thumbnail_size;
}
elseif(!empty($thumbnail_custom_dimension['width']) && !empty($thumbnail_custom_dimension['height'])){
    $img_size = $thumbnail_custom_dimension['width'] . 'x' . $thumbnail_custom_dimension['height'];
}
else{
    $img_size = 'full';
}
?>
<?php if(isset($content_list2) && !empty($content_list2) && count($content_list2)): ?>
    <div class="ct-grid ct-team-grid ct-team-grid2 bar-color-<?php echo esc_attr($bar_color_style); ?>">
        <div class="ct-grid-inner ct-grid-masonry row animate-time" data-gutter="7">
            <?php foreach ($content_list2 as $key => $value):
            	$link_key = $widget->get_repeater_setting_key( 'title', 'value', $key );
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
    			$title = isset($value['title']) ? $value['title'] : '';
                $position = isset($value['position']) ? $value['position'] : '';
                $btn_text = isset($value['btn_text']) ? $value['btn_text'] : '';
    			$image_position = isset($value['image_position']) ? $value['image_position'] : '';
                $image = isset($value['image']) ? $value['image'] : '';
    			$img = ct_get_image_by_size( array(
                    'attach_id'  => $image['id'],
                    'thumb_size' => $img_size,
                    'class' => 'disable-lazy',
                ));
                $thumbnail = $img['thumbnail'];
                $social = isset($value['social']) ? $value['social'] : '';
                $progressbar = isset($value['progressbar']) ? $value['progressbar'] : '';
            	?>
                <div class="<?php echo esc_attr($item_class); ?>">
                    <div class="item--inner <?php echo esc_attr($image_position.' '.$ct_animate); ?>">
                        <div class="item--holder">
                            <div class="item--social">
                                <?php if(!empty($social)):
                                    $team_social = json_decode($social, true);
                                    foreach ($team_social as $value): ?>
                                        <a target="_blank" href="<?php echo esc_url($value['url']); ?>"><i class="<?php echo esc_attr($value['icon']); ?>"></i></a>
                                    <?php endforeach;
                                endif; ?>
                            </div>
                            <h3 class="item--title">    
                                <?php echo ct_print_html($title); ?>
                            </h3>
                            <span class="item--position"><?php echo ct_print_html($position); ?></span>
                            <div class="item--progressbar-wrap">
                                <?php if(!empty($progressbar)):
                                    $progressbar = json_decode($progressbar, true);
                                    foreach ($progressbar as $value): ?>
                                        <div class="ct-progressbar ct-team-progressbar">
                                            <div class="ct-progress-meta">
                                                <?php if ( ! empty( $value['title'] ) ) { ?>
                                                    <span class="ct-progress-title"><?php echo ct_print_html($value['title']); ?></span>
                                                <?php } ?>
                                            </div>
                                            <div class="ct-progress-holder">
                                                <div class="ct-progress-bar" role="progressbar" data-valuetransitiongoal="<?php echo esc_html($value['number']); ?>" data-valuenow="<?php echo esc_html($value['number']); ?>"><div class="ct-progress-percentage"><?php echo esc_html($value['number']); ?>%</div></div>
                                            </div>
                                        </div>
                                    <?php endforeach;
                                endif; ?>
                            </div>
                        </div>
                        <?php if(!empty($image)) { ?>
                            <div class="item--image bg-image" style="background-image: url(<?php echo esc_url($image['url']); ?>);">
                                <a <?php echo implode( ' ', [ $link_attributes ] ); ?>></a>
                            </div>
                        <?php } ?>
                   </div>
                </div>
            <?php endforeach; ?>
            <div class="grid-sizer <?php echo esc_attr($grid_sizer); ?>"></div>
        </div>
    </div>
<?php endif; ?>
