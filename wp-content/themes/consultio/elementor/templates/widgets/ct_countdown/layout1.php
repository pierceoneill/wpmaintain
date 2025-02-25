<?php
$default_settings = [
    'date' => '2030/10/10',
];
$settings = array_merge($default_settings, $settings);
extract($settings); 
$month = esc_html__('Month', 'consultio');
$months = esc_html__('Months', 'consultio');
$day = esc_html__('Day', 'consultio');
$days = esc_html__('Days', 'consultio');
$hour = esc_html__('Hour', 'consultio');
$hours = esc_html__('Hours', 'consultio');
$minute = esc_html__('Minute', 'consultio');
$minutes = esc_html__('Minutes', 'consultio');
$second = esc_html__('Second', 'consultio');
$seconds = esc_html__('Seconds', 'consultio');
?>
<div class="ct-countdown-wrap ct-countdown-layout1 <?php echo esc_attr($settings['ct_animate']); ?>" data-wow-delay="<?php echo esc_attr($settings['ct_animate_delay']); ?>ms">
	<div class="ct-countdown" 
		data-month="<?php echo esc_attr($month) ?>"
		data-months="<?php echo esc_attr($months) ?>"
		data-day="<?php echo esc_attr($day) ?>"
		data-days="<?php echo esc_attr($days) ?>"
		data-hour="<?php echo esc_attr($hour) ?>"
		data-hours="<?php echo esc_attr($hours) ?>"
		data-minute="<?php echo esc_attr($minute) ?>"
		data-minutes="<?php echo esc_attr($minutes) ?>"
		data-second="<?php echo esc_attr($second) ?>"
		data-seconds="<?php echo esc_attr($seconds) ?>">
		<div class="ct-countdown-inner" data-count-down="<?php echo esc_attr($date);?>"></div>
	</div>
</div>