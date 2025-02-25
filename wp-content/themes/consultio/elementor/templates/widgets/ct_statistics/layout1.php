<?php
$default_settings = [
    'list' => '',
    'ct_animate' => '',
];
$settings = array_merge($default_settings, $settings);
extract($settings);
$html_id = ct_get_element_id($settings);
?>
<?php if(isset($list) && !empty($list) && count($list)): ?>
    <div class="ct-statistics">
        <?php
        	foreach ($list as $key => $ct_list): ?>
            <div class="ct-inline-css"  data-css="
                #<?php echo esc_attr($html_id.$key) ?> .ct-counter-wrap {
                    <?php if(!empty($ct_list['box_color'])) : ?>
                        background-color: <?php echo esc_attr($ct_list['box_color']); ?>;
                    <?php endif; ?>
                }">
            </div>
            <div id="<?php echo esc_attr($html_id.$key); ?>" class="ct-item <?php echo esc_attr($ct_animate); ?>">
                <?php if(!empty($ct_list['number'])) : ?>
                    <div class="ct-counter-wrap">
            	       <span class="ct-counter-number-value" data-duration="2000" data-to-value="<?php echo esc_html($ct_list['number'])?>" data-delimiter=",">0</span>
                    </div>
                <?php endif; ?>
                <h4><?php echo esc_html($ct_list['title'])?></h4>
           </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
