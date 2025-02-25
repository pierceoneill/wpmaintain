<?php 
$default_settings = [
    'logo1' => '',
    'logo2' => '',
    'top_positioon' => '',
    'left_positioon' => '',
    'hidden_lg_sc' => '',
    'hidden_md_sc' => '',
    'hidden_sm_sc' => '',
    'ct_animate' => '',
];
$settings = array_merge($default_settings, $settings);
extract($settings);
$html_id = ct_get_element_id($settings);
if ( ! empty( $logo_link['url'] ) ) {
    $widget->add_render_attribute( 'logo_link', 'href', $logo_link['url'] );

    if ( $logo_link['is_external'] ) {
        $widget->add_render_attribute( 'logo_link', 'target', '_blank' );
    }

    if ( $logo_link['nofollow'] ) {
        $widget->add_render_attribute( 'logo_link', 'rel', 'nofollow' );
    }
}
?>

<div id="<?php echo esc_attr($html_id); ?>" class="ct-logo-animate el-move-parents <?php echo esc_attr($hidden_lg_sc.' '.$hidden_md_sc.' '.$hidden_sm_sc.' '.$ct_animate); ?>">
    <div class="ct-inline-css"  data-css="
        #<?php echo esc_attr($html_id); ?>.ct-logo-animate.el-move-parents {
            left: <?php echo esc_attr($left_positioon['size']).esc_attr($left_positioon['unit']); ?>;
            top: <?php echo esc_attr($top_positioon['size']).esc_attr($top_positioon['unit']); ?>;
        }">
    </div>
    <div class="ct-logo1">
        <div class="ct-logo-inner">
            <?php if(!empty($logo1['id'])) : 
                $img_logo1  = ct_get_image_by_size( array(
                    'attach_id'  => $logo1['id'],
                    'thumb_size' => 'full',
                ) );
                $thumbnail_logo1    = $img_logo1['thumbnail'];
                echo wp_kses_post($thumbnail_logo1);
            endif; ?>
        </div>
    </div>
    <div class="ct-logo2">
        <?php if(!empty($logo2['id'])) : 
            $img_logo2  = ct_get_image_by_size( array(
                'attach_id'  => $logo2['id'],
                'thumb_size' => 'full',
            ) );
            $thumbnail_logo2    = $img_logo2['thumbnail'];
            echo wp_kses_post($thumbnail_logo2);
        endif; ?>
    </div>
</div>