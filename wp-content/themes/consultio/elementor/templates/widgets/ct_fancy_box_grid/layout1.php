<?php
$default_settings = [
    'col_xl' => '4',
    'col_lg' => '4',
    'col_md' => '3',
    'col_sm' => '2',
    'col_xs' => '1',
    'content_list' => '',
    'border_color_color' => '',
];
$settings = array_merge($default_settings, $settings);
extract($settings);
$html_id = ct_get_element_id($settings);
$col_xl = 12 / intval($col_xl);
$col_lg = 12 / intval($col_lg);
$col_md = 12 / intval($col_md);
$col_sm = 12 / intval($col_sm);
$col_xs = 12 / intval($col_xs);
$grid_sizer = "col-xl-{$col_xl} col-lg-{$col_lg} col-md-{$col_md} col-sm-{$col_sm} col-{$col_xs}";
$item_class = "grid-item col-xl-{$col_xl} col-lg-{$col_lg} col-md-{$col_md} col-sm-{$col_sm} col-{$col_xs}";
$is_new = \Elementor\Icons_Manager::is_migration_allowed();
?>
<?php if(isset($content_list) && !empty($content_list) && count($content_list)): ?>
    <div id="<?php echo esc_attr($html_id) ?>" class="ct-grid ct-fancy-box-grid ct-fancy-box-grid1">
        <div class="ct-inline-css"  data-css="
            <?php if(!empty($border_color_color)) : ?>
                #<?php echo esc_attr($html_id) ?> .item--inner .item-line-top, #<?php echo esc_attr($html_id) ?> .item--inner .item-line-bottom {
                    background: rgba(0, 0, 0, 0) -webkit-linear-gradient(to right, rgba(0, 0, 0, 0), <?php echo esc_attr($border_color_color); ?>, rgba(0, 0, 0, 0));
                    background: rgba(0, 0, 0, 0) -ms-linear-gradient(to right, rgba(0, 0, 0, 0), <?php echo esc_attr($border_color_color); ?>, rgba(0, 0, 0, 0));
                    background: rgba(0, 0, 0, 0) -o-linear-gradient(to right, rgba(0, 0, 0, 0), <?php echo esc_attr($border_color_color); ?>, rgba(0, 0, 0, 0));
                    background: rgba(0, 0, 0, 0) linear-gradient(to right, rgba(0, 0, 0, 0), <?php echo esc_attr($border_color_color); ?>, rgba(0, 0, 0, 0));
                }
                #<?php echo esc_attr($html_id) ?> .item--inner .item-line-left, #<?php echo esc_attr($html_id) ?> .item--inner .item-line-right {
                    background: rgba(0, 0, 0, 0) -webkit-linear-gradient(to top, rgba(0,0,0, 0), <?php echo esc_attr($border_color_color); ?>, rgba(0,0,0, 0));
                    background: rgba(0, 0, 0, 0) -ms-linear-gradient(to top, rgba(0,0,0, 0), <?php echo esc_attr($border_color_color); ?>, rgba(0,0,0, 0));
                    background: rgba(0, 0, 0, 0) -o-linear-gradient(to top, rgba(0,0,0, 0), <?php echo esc_attr($border_color_color); ?>, rgba(0,0,0, 0));
                    background: rgba(0, 0, 0, 0) linear-gradient(to top, rgba(0,0,0, 0), <?php echo esc_attr($border_color_color); ?>, rgba(0,0,0, 0));
                }
            <?php endif; ?>">
        </div>
        <div class="ct-grid-inner ct-grid-masonry row" data-gutter="7">
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
                $icon_type = isset($value['icon_type']) ? $value['icon_type'] : '';
                $icon = isset($value['ct_icon']) ? $value['ct_icon'] : '';
                $icon_key = $widget->get_repeater_setting_key( 'ct_icon', 'icons', $key );
                $has_icon = ! empty( $value['ct_icon'] );
                $widget->add_render_attribute( $icon_key, [
                    'class' => $icon,
                    'aria-hidden' => 'true',
                ] );
                $icon_image = isset($value['icon_image']) ? $value['icon_image'] : '';
            	?>
                <div class="<?php echo esc_attr($item_class); ?>">
                    <div class="item--inner <?php if($key == 4) { echo 'active'; } ?>">
                        <div class="item--holder">
                        	<div class="item--meta">
                                <?php if($icon_type == 'icon' && !empty($icon)) { ?>
                                    <div class="item--icon">
                                        <?php
                                            if($is_new):
                                                \Elementor\Icons_Manager::render_icon( $icon, [ 'aria-hidden' => 'true' ] );
                                        ?>
                                        <?php else: ?>
                                            <i <?php ct_print_html($widget->get_render_attribute_string( $icon_key )); ?>></i>
                                        <?php endif; ?>
                                    </div>
                                <?php } ?>
                                <?php if($icon_type == 'image' && !empty($icon_image)) { 
                                    $img = ct_get_image_by_size( array(
                                        'attach_id'  => $icon_image['id'],
                                        'thumb_size' => 'full',
                                    ));
                                    $thumbnail = $img['thumbnail']; ?>
                                    <div class="item--icon">
                                        <?php echo wp_kses_post($thumbnail); ?>
                                    </div>
                                <?php } ?>
                                <h3 class="item--title">    
                                    <?php echo ct_print_html($title); ?>
                                </h3>
                                <?php if ( ! empty( $value['link']['url'] ) ) { ?>
                                    <a class="item-link" <?php echo implode( ' ', [ $link_attributes ] ); ?>></a>
                                <?php } ?>
                            </div>
                        </div>
                        <div class="item-line item-line-top"></div>
                        <div class="item-line item-line-bottom"></div>
                        <div class="item-line item-line-left"></div>
                        <div class="item-line item-line-right"></div>
                   </div>
                </div>
            <?php endforeach; ?>
            <div class="grid-sizer <?php echo esc_attr($grid_sizer); ?>"></div>
        </div>
    </div>
<?php endif; ?>
