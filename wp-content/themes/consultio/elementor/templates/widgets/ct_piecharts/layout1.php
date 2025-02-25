<?php
$primary_color = consultio_get_opt( 'primary_color' );
$default_settings = [
    'title' => '',
    'description' => '',
    'percentage_value' => '',
    'bar_color' => '',
    'track_color' => '',
    'chart_size' => '',
    'style' => 'style1',
    'ct_animate' => '',
];
$settings = array_merge($default_settings, $settings);
extract($settings);
?>
<div class="ct-piechart ct-piechart-layout1 <?php echo esc_attr($style.' '.$ct_animate); ?>">
    <div class="item--value percentage" style="min-height: <?php echo esc_attr($chart_size['size']); ?>px;" data-size="<?php echo esc_attr($chart_size['size']); ?>" data-bar-color="<?php if(!empty($bar_color)) { echo esc_attr($bar_color); } else { echo esc_attr($primary_color); } ?>" data-track-color="<?php if(!empty($track_color)) { echo esc_attr($track_color); } else { echo '#d7d7d7'; } ?>" data-line-width="10" data-percent="-<?php echo esc_attr($percentage_value); ?>">
        <span><?php echo esc_attr($percentage_value); ?>%</span>
    </div>
    <h3 class="item--title"><?php echo ct_print_html($title); ?></h3>
    <div class="item--desc"><?php echo ct_print_html($description); ?></div>
</div>