<?php
$default_settings = [
    'banner' => '',
    'icon_image1' => '',
    'icon_image2' => '',
    'icon_image3' => '',
    'icon_image4' => '',
    'title1' => '',
    'title2' => '',
    'title3' => '',
    'title4' => '',
    'description1' => '',
    'description2' => '',
    'description3' => '',
    'description4' => '',
    'btn_text1' => '',
    'btn_text2' => '',
    'btn_text3' => '',
    'btn_text4' => '',
    'btn_link1' => '',
    'btn_link2' => '',
    'btn_link3' => '',
    'btn_link4' => '',
];
$settings = array_merge($default_settings, $settings);
extract($settings);

$banner_size  = ct_get_image_by_size( array(
    'attach_id'  => $banner['id'],
    'thumb_size' => 'full',
) );
$banner_img    = $banner_size['thumbnail'];

$icon_size1  = ct_get_image_by_size( array(
    'attach_id'  => $icon_image1['id'],
    'thumb_size' => 'full',
) );
$icon_img1    = $icon_size1['thumbnail'];

$icon_size2  = ct_get_image_by_size( array(
    'attach_id'  => $icon_image2['id'],
    'thumb_size' => 'full',
) );
$icon_img2    = $icon_size2['thumbnail'];

$icon_size3  = ct_get_image_by_size( array(
    'attach_id'  => $icon_image3['id'],
    'thumb_size' => 'full',
) );
$icon_img3    = $icon_size3['thumbnail'];

$icon_size4  = ct_get_image_by_size( array(
    'attach_id'  => $icon_image4['id'],
    'thumb_size' => 'full',
) );
$icon_img4   = $icon_size4['thumbnail'];

if ( ! empty( $settings['btn_link1']['url'] ) ) {
    $widget->add_render_attribute( 'button1', 'href', $settings['btn_link1']['url'] );

    if ( $settings['btn_link1']['is_external'] ) {
        $widget->add_render_attribute( 'button1', 'target', '_blank' );
    }

    if ( $settings['btn_link1']['nofollow'] ) {
        $widget->add_render_attribute( 'button1', 'rel', 'nofollow' );
    }
}

if ( ! empty( $settings['btn_link2']['url'] ) ) {
    $widget->add_render_attribute( 'button2', 'href', $settings['btn_link2']['url'] );

    if ( $settings['btn_link2']['is_external'] ) {
        $widget->add_render_attribute( 'button2', 'target', '_blank' );
    }

    if ( $settings['btn_link2']['nofollow'] ) {
        $widget->add_render_attribute( 'button2', 'rel', 'nofollow' );
    }
}

if ( ! empty( $settings['btn_link3']['url'] ) ) {
    $widget->add_render_attribute( 'button3', 'href', $settings['btn_link3']['url'] );

    if ( $settings['btn_link3']['is_external'] ) {
        $widget->add_render_attribute( 'button3', 'target', '_blank' );
    }

    if ( $settings['btn_link3']['nofollow'] ) {
        $widget->add_render_attribute( 'button3', 'rel', 'nofollow' );
    }
}

if ( ! empty( $settings['btn_link4']['url'] ) ) {
    $widget->add_render_attribute( 'button4', 'href', $settings['btn_link4']['url'] );

    if ( $settings['btn_link4']['is_external'] ) {
        $widget->add_render_attribute( 'button4', 'target', '_blank' );
    }

    if ( $settings['btn_link4']['nofollow'] ) {
        $widget->add_render_attribute( 'button4', 'rel', 'nofollow' );
    }
}

if(!empty($banner['id'])) : ?>
    <div class="ct-feature-list">
        <div class="ct-item-left">
            <?php if(!empty($title1)) : ?>
                <div class="ct-feature-item">
                    <?php echo wp_kses_post($icon_img1); ?>
                    <h4><?php echo esc_attr($title1); ?></h4>
                    <p><?php echo esc_attr($description1); ?></p>
                    <?php if(!empty($settings['btn_text1'])) : ?>
                        <a class="btn-line" <?php ct_print_html($widget->get_render_attribute_string( 'button1' )); ?>><span><?php echo esc_attr($settings['btn_text1']); ?></span><i class="flaticonv4-next"></i></a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
            <?php if(!empty($title2)) : ?>
                <div class="ct-feature-item">
                    <?php echo wp_kses_post($icon_img2); ?>
                    <h4><?php echo esc_attr($title2); ?></h4>
                    <p><?php echo esc_attr($description2); ?></p>
                    <?php if(!empty($settings['btn_text2'])) : ?>
                        <a class="btn-line" <?php ct_print_html($widget->get_render_attribute_string( 'button2' )); ?>><span><?php echo esc_attr($settings['btn_text2']); ?></span><i class="flaticonv4-next"></i></a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
        <div class="ct-item-center">
            <?php echo wp_kses_post($banner_img); ?>
            <?php if(!empty($settings['banner_text'])) : ?>
                <div class="text-box"><?php echo ct_print_html($settings['banner_text']); ?></div>
            <?php endif; ?>
        </div>
        <div class="ct-item-right">
            <?php if(!empty($title3)) : ?>
                <div class="ct-feature-item">
                    <?php echo wp_kses_post($icon_img3); ?>
                    <h4><?php echo esc_attr($title3); ?></h4>
                    <p><?php echo esc_attr($description3); ?></p>
                    <?php if(!empty($settings['btn_text3'])) : ?>
                        <a class="btn-line" <?php ct_print_html($widget->get_render_attribute_string( 'button3' )); ?>><span><?php echo esc_attr($settings['btn_text3']); ?></span><i class="flaticonv4-next"></i></a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
            <?php if(!empty($title4)) : ?>
                <div class="ct-feature-item">
                    <?php echo wp_kses_post($icon_img4); ?>
                    <h4><?php echo esc_attr($title4); ?></h4>
                    <p><?php echo esc_attr($description4); ?></p>
                    <?php if(!empty($settings['btn_text4'])) : ?>
                        <a class="btn-line" <?php ct_print_html($widget->get_render_attribute_string( 'button4' )); ?>><span><?php echo esc_attr($settings['btn_text4']); ?></span><i class="flaticonv4-next"></i></a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
<?php endif; ?>