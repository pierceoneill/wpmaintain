<?php
$default_settings = [
    'col_xl' => '4',
    'col_lg' => '4',
    'col_md' => '3',
    'col_sm' => '2',
    'col_xs' => '1',
    'content_list' => '',
    'thumbnail_size' => '',
    'thumbnail_custom_dimension' => '',
    'ct_animate' => '',
    'style' => 'style1',
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
<?php if(isset($content_list) && !empty($content_list) && count($content_list)): ?>
    <div class="ct-grid ct-team-grid ct-team-grid1 <?php echo esc_attr($style); ?>">
        <div class="ct-grid-inner ct-grid-masonry row animate-time" data-gutter="7">
            <?php foreach ($content_list as $key => $value):
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
    			$image = isset($value['image']) ? $value['image'] : '';
    			$img = ct_get_image_by_size( array(
                    'attach_id'  => $image['id'],
                    'thumb_size' => $img_size,
                    'class' => 'disable-lazy',
                ));
                $thumbnail = $img['thumbnail'];
                $social = isset($value['social']) ? $value['social'] : '';
            	?>
                <div class="<?php echo esc_attr($item_class); ?>">
                    <div class="item--inner <?php echo esc_attr($ct_animate); ?>">
                    	<?php if(!empty($image)) { ?>
                            <div class="item--image">
                                <a <?php echo implode( ' ', [ $link_attributes ] ); ?>><?php echo wp_kses_post($thumbnail); ?></a>
                                <div class="item--social">
                                    <?php if(!empty($social)):
                                        $team_social = json_decode($social, true);
                                        foreach ($team_social as $value): ?>
                                            <a target="_blank" href="<?php echo esc_url($value['url']); ?>"><i class="<?php echo esc_attr($value['icon']); ?>"></i></a>
                                        <?php endforeach;
                                    endif; ?>
                                </div>
                            </div>
                        <?php } ?>
                        <div class="item--meta-wrap">
                            <div class="item--meta">
                            	<h3 class="item--title">	
                	            	<?php echo ct_print_html($title); ?>
                		        </h3>
                		        <span class="item--position"><?php echo ct_print_html($position); ?></span>
                                <?php if(!empty($btn_text)) : ?>
                                    <div class="item--button">
                                        <a class="btn btn-team" <?php echo implode( ' ', [ $link_attributes ] ); ?>><?php echo esc_attr($btn_text); ?><i class="flaticonv2-right-arrow"></i></a>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                   </div>
                </div>
            <?php endforeach; ?>
            <div class="grid-sizer <?php echo esc_attr($grid_sizer); ?>"></div>
        </div>
    </div>
<?php endif; ?>
