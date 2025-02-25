<?php
$default_settings = [
    'title' => '',
    'typingout' => '',
    'title_tag' => 'h3',
    'style' => 'st-default',
    'sub_title' => '',
    'sub_title_line' => 'show',
    'sub_title_style' => 'style1',
    'content_alignment_section' => 'left',
    'text_align' => '',
    'color_gr_from' => '',
    'color_gr_to' => '',
    'ct_animate' => '',
    'sub_divider_image' => '',
    'ct_animate_delay' => '',
];
$settings = array_merge($default_settings, $settings);
extract($settings);
if(!empty($typingout)) {
    wp_enqueue_script('typingout', get_template_directory_uri() . '/assets/js/typingout.js', array( 'jquery' ), '1.0.0', true);
}
$html_id = ct_get_element_id($settings);
$editor_title = $widget->get_settings_for_display( 'title' );
$editor_title = $widget->parse_text_editor( $editor_title );
?>
<div id="<?php echo esc_attr($html_id) ?>" class="ct-heading h-align-<?php echo esc_attr($text_align); ?> sub-<?php echo esc_attr($sub_title_style); ?> ct-heading-<?php echo esc_attr($content_alignment_section); ?> item-<?php echo esc_attr($style); ?>">
	<div class="ct-item--inner">
        <div class="ct-inline-css"  data-css="
            <?php if( !empty($color_gr_from) && !empty($color_gr_to) ) : ?>
                #<?php echo esc_attr($html_id) ?>.ct-heading .item--title.st-line-right2 span.sp-main::before {
                    background-image: -webkit-gradient(linear, left top, right top, from(<?php echo esc_attr($color_gr_from); ?>), to(<?php echo esc_attr($color_gr_to); ?>));
                    background-image: -webkit-linear-gradient(left, <?php echo esc_attr($color_gr_from); ?>, <?php echo esc_attr($color_gr_to); ?>);
                    background-image: -moz-linear-gradient(left, <?php echo esc_attr($color_gr_from); ?>, <?php echo esc_attr($color_gr_to); ?>);
                    background-image: -ms-linear-gradient(left, <?php echo esc_attr($color_gr_from); ?>, <?php echo esc_attr($color_gr_to); ?>);
                    background-image: -o-linear-gradient(left, <?php echo esc_attr($color_gr_from); ?>, <?php echo esc_attr($color_gr_to); ?>);
                    background-image: linear-gradient(left, <?php echo esc_attr($color_gr_from); ?>, <?php echo esc_attr($color_gr_to); ?>);
                    filter: progid:DXImageTransform.Microsoft.gradient(startColorStr='<?php echo esc_attr($color_gr_from); ?>', endColorStr='<?php echo esc_attr($color_gr_to); ?>', gradientType='1');
                }
            <?php endif; ?>">
        </div>
        <?php if(!empty($sub_title)) : ?>
    		<div class="item--sub-title <?php echo esc_attr($sub_title_style); ?> <?php echo esc_attr($sub_title_line); ?>-line">
                <?php if($sub_title_style == 'style11') : ?>
                    <?php if ( !empty($settings['ct_icon']['value']) ) : ?>
                        <span class="item--sub-icon"><?php \Elementor\Icons_Manager::render_icon( $settings['ct_icon'], [ 'aria-hidden' => 'true', 'class' => '' ], 'i' ); ?></span>
                    <?php endif; ?>
                <?php endif; ?>
                <?php if($sub_title_style == 'style10') : ?>
                    <span class="sub-dots"><span></span></span>
                <?php endif; ?>
                <?php echo esc_attr($sub_title); ?>
                <?php if($sub_title_style == 'style8') : ?>
                    <span></span>
                <?php endif; ?>
                <?php if($sub_title_style == 'style9') : ?>
                    <span class="line-left"></span>
                    <span class="line-right"></span>
                <?php endif; ?>
                <?php if(!empty($sub_divider_image['id'])) :
                    $img_divider  = consultio_get_image_by_size( array(
                        'attach_id'  => $sub_divider_image['id'],
                        'thumb_size' => 'full',
                    ) );
                    $thumbnail_divider    = $img_divider['thumbnail']; ?>
                    <div class="sub-divider-image"><?php echo wp_kses_post($thumbnail_divider); ?></div>
                <?php endif; ?>
            </div>
    	<?php endif; ?>
        <<?php echo esc_attr($title_tag); ?> class="item--title <?php echo esc_attr($style); ?> <?php if($ct_animate != 'case-fade-in-up' && !empty($ct_animate)) { echo 'wow '.esc_attr($ct_animate); } else { echo 'case-animate-time'; } ?>" data-wow-delay="<?php echo esc_attr($ct_animate_delay); ?>ms">
            <?php if($style == 'st-line-top1' || $style == 'st-line-top2') : ?>
                <div class="ct-heading-divider"><span></span></div>
            <?php endif; ?>
            <span class="sp-main">
                <?php if($style == 'st-line-left1' || $style == 'st-line-right1' || $style == 'st-line-left2') : ?>
                    <i></i>
                <?php endif; ?>

                <?php if($ct_animate == 'case-fade-in-up') {
                    $arr_str = explode(' ', $title);
                    foreach ($arr_str as $index => $value) {
                        $arr_str[$index] = '<span class="slide-in-container"><span class="d-inline-block wow '.$ct_animate.'">' . $value . '</span></span>';
                    }
                    $str = implode(' ', $arr_str);
                    echo ct_print_html($str);
                } else {
                    echo wp_kses_post($editor_title);
                } ?>

                <?php if(!empty($typingout)) { ?>
                    <span class="ct-typingout-typed" data-period="600" data-type='[ <?php echo esc_attr($typingout); ?> ]'>
                        <span class="ct-typingout-animation"></span>
                    </span>
                <?php } ?>

                <?php if($style == 'st-line-left3') : ?>
                    <i class="dot-shape">
                        <i></i>
                        <i></i>
                        <i></i>
                        <i></i>
                        <i></i>
                        <i></i>
                    </i>
                <?php endif; ?>
            </span>
            <?php if($style == 'st-line-bottom1') : ?>
                <div class="ct-heading-divider"><span></span></div>
            <?php endif; ?>
        </<?php echo esc_attr($title_tag); ?>>
    </div>
</div>