<?php
$default_settings = [
    'typing_out' => '',
    'sub_title' => '',
];
$settings = array_merge($default_settings, $settings);
extract($settings);
?>
<h3 class="ct-typing-out">
	<span class="ct-sub-title"><?php echo esc_attr($sub_title); ?></span>
	<?php if(!empty($typing_out)) { ?>
        <span class="ct-typingout-typed" data-period="2000" data-type='[ <?php echo esc_attr($typing_out); ?> ]'>
            <span class="ct-typingout-animation"></span>
        </span>
    <?php } ?>
</h3>