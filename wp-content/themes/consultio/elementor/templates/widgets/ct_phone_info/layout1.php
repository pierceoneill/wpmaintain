<?php
$default_settings = [
    'label_phone' => '',
    'phone_number' => '',
    'label_email' => '',
    'email_address' => '',
    'ct_animate' => '',
];
$settings = array_merge($default_settings, $settings);
extract($settings);
$html_id = ct_get_element_id($settings);
?>
<div class="ct-phone-info <?php echo esc_attr($ct_animate); ?>">
    <?php if(!empty($phone_number)) : ?>
        <div class="ct-phone-inner">
            <a class="ct-phone-icon" href="tel:<?php echo esc_attr($phone_number); ?>">
                <i class="flaticonv5 flaticonv5-phone-call"></i>
            </a>
            <div class="ct-phone-holder">
                <label class="label-phone"><?php echo esc_attr($label_phone); ?></label>
                <a class="phone-number" href="tel:<?php echo esc_attr($phone_number); ?>"><?php echo esc_attr($phone_number); ?></a>
            </div>
        </div>
    <?php endif; ?>
    <?php if(!empty($label_email)) : ?>
        <div class="ct-email-inner">
            <a href="mailto:<?php echo esc_attr($email_address); ?>" class="btn"><i class="flaticonv5 flaticonv5-email"></i><?php echo esc_attr($label_email); ?></a>
        </div>
    <?php endif; ?>
</div>
