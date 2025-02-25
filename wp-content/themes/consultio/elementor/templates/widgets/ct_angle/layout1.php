<?php
$default_settings = [
    'type' => '',
    'angle_color' => '',
    'angle_height' => '',
    'angle_position' => '',
    'angle_offset' => '',
    'angle_layout' => '',
    'responsive' => '',
    'el_container' => '',
];
$settings = array_merge($default_settings, $settings);
extract($settings); 
$html_id = ct_get_element_id($settings);
?>
<div id="<?php echo esc_attr($html_id); ?>" class="ct-angle ct-angle-<?php echo esc_attr($angle_position); ?> hide-<?php echo esc_attr($responsive.' '.$el_container); ?>">
    <svg style="
        <?php if(!empty($angle_height)) : ?>
            height: <?php echo esc_attr($angle_height['size'].'px'); ?>;
        <?php endif; ?>
        <?php if(!empty($angle_color)) : ?>
            fill: <?php echo esc_attr($angle_color); ?>;
        <?php endif; ?>
        <?php if($angle_position == 'top-reverse') { echo 'top'; } else { echo esc_attr($angle_position); } ?>: <?php echo esc_attr($angle_offset['size'].'px'); ?>;
        " xmlns="http://www.w3.org/2000/svg" width="100%" viewBox="0 0 100 100" version="1.1" preserveAspectRatio="none">
        <?php 
            switch ($angle_layout) {
                case 'layout2': ?>
                    <path stroke-width="0" d="M0 100 L100 0 L200 100"></path><?php
                    break;
                
                default: ?>
                    <path stroke-width="0" d="M0 0 C50 100 50 100 100 0 L100 100 0 100"></path><?php
                    break;
            }
        ?>
    </svg>
</div>