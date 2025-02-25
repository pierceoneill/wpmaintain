<?php
$default_settings = [
    'col_xl' => '4',
    'col_lg' => '4',
    'col_md' => '3',
    'col_sm' => '2',
    'col_xs' => '1',
    'clients' => '',
    'item_btn_text' => '',
    'item_btn_link' => '',
    'item_desc' => '',
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
if ( ! empty($item_btn_link['url'] ) ) {
    $widget->add_render_attribute( 'button', 'href',$item_btn_link['url'] );

    if ($item_btn_link['is_external'] ) {
        $widget->add_render_attribute( 'button', 'target', '_blank' );
    }

    if ($item_btn_link['nofollow'] ) {
        $widget->add_render_attribute( 'button', 'rel', 'nofollow' );
    }
}
?>
<?php if(isset($clients) && !empty($clients) && count($clients)): ?>
    <div id="<?php echo esc_attr($html_id) ?>" class="ct-grid ct-client-grid1">
        <div class="ct-grid-inner ct-grid-masonry row animate-time" data-gutter="7">
            <?php foreach ($clients as $key => $client):
                $link_key = $widget->get_repeater_setting_key( 'title', 'value', $key );
                if ( ! empty( $client['client_link']['url'] ) ) {
                    $widget->add_render_attribute( $link_key, 'href', $client['client_link']['url'] );

                    if ( $client['client_link']['is_external'] ) {
                        $widget->add_render_attribute( $link_key, 'target', '_blank' );
                    }

                    if ( $client['client_link']['nofollow'] ) {
                        $widget->add_render_attribute( $link_key, 'rel', 'nofollow' );
                    }
                }
                $link_attributes = $widget->get_render_attribute_string( $link_key );
            	if(!empty($client['client_image']['id'])) { 
                    $img          = ct_get_image_by_size( array(
                        'attach_id'  => $client['client_image']['id'],
                        'thumb_size' => 'full',
                        'class' => 'disable-lazy',
                    ) );
                    $thumbnail    = $img['thumbnail'];
                    ?>
                    <div class="<?php echo esc_attr($item_class); ?>">
                        <div class="client-image">
                            <a <?php echo implode( ' ', [ $link_attributes ] ); ?>><?php echo ct_print_html($thumbnail); ?></a>
                        </div>
                    </div>
                <?php } ?>
            <?php endforeach; ?>
            
            <?php if(!empty($item_desc)) : ?>
                <div class="<?php echo esc_attr($item_class); ?> item-last">
                    <div class="client-item-inner">
                        <div class="client-desc"><?php echo ct_print_html($item_desc); ?></div>
                        <?php if(!empty($item_btn_text)) : ?>
                            <a class="btn btn-preset4" <?php ct_print_html($widget->get_render_attribute_string( 'button' )); ?>><?php echo esc_attr($item_btn_text); ?></a>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>

            <div class="grid-sizer <?php echo esc_attr($grid_sizer); ?>"></div>
        </div>
    </div>
<?php endif; ?>
