<?php
$default_settings = [
    'list' => '',
    'title' => '',
    'description' => '',
    'box_bg' => '',
    'ct_animate' => '',
];
$settings = array_merge($default_settings, $settings);
extract($settings);
?>
<div class="ct-emergency <?php echo esc_attr($ct_animate); ?> bg-image" <?php if(!empty($box_bg['url'])) : ?>style="background-image: url(<?php echo esc_url($box_bg['url']); ?>);"<?php endif; ?>>
    <h3 class="item--title"><?php echo ct_print_html($title); ?></h3>
    <div class="item--desc"><?php echo ct_print_html($description); ?></div>
    <?php if(isset($list) && !empty($list) && count($list)): ?>
        <ul>
            <?php
                foreach ($list as $key => $ct_list): ?>
                <li>
                    <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><g><path d="m21.625 11.2192-5-4a1 1 0 0 0 -1.25 1.5615l2.7744 2.2193h-6.1494a5.0059 5.0059 0 0 0 -5 5v2a1 1 0 0 0 2 0v-2a3.0033 3.0033 0 0 1 3-3h6.149l-2.774 2.2192a1 1 0 0 0 1.25 1.5615l5-4a1.008 1.008 0 0 0 0-1.5615z"/></g></svg>
                    <?php echo esc_html($ct_list['list_content'])?>
               </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</div>
