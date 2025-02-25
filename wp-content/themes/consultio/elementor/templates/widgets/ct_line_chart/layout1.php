<?php
$default_settings = [
    'x_ax' => '',
    'values' => '',
];
$settings = array_merge($default_settings, $settings);
extract($settings);
$data = array(
	'labels' => explode( ';', trim( $x_ax, ';' ) ),
	'datasets' => array(),
);
foreach ( $values as $k => $v ) {

	$data['datasets'][] = array(
		'label' => $v['title'],
		'data' => explode( ';', isset( $v['y_ax'] ) ? trim( $v['y_ax'], ';' ) : '' ),
		'backgroundColor' => $v['bg_color'],
		'borderColor' => $v['border_color'],
        'fill' => true,
        'borderWidth' => 4,
        'lineTension' => 0,
        'pointRadius' => 7,
        'pointBorderColor' => '#fff',
        'pointBackgroundColor' => $v['border_color'],
        'pointBorderWidth' => 2,
	);
}
$options[] = 'data-datasets="' . htmlentities( wp_json_encode( $data ) ) . '"';
?>
<canvas id="ct-line-chart" width="600" height="400" <?php echo implode( ' ', $options ) ?>></canvas>