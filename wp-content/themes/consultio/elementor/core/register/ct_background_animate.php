<?php
ct_add_custom_widget(
    array(
        'name' => 'ct_background_animate',
        'title' => esc_html__('Case Background Animate', 'consultio' ),
        'icon' => 'eicon-posts-ticker',
        'categories' => array( Case_Theme_Core::CT_CATEGORY_NAME ),
        'scripts' => array(

        ),
        'params' => array(
            'sections' => array(
                array(
                    'name' => 'content_section',
                    'label' => esc_html__('Content', 'consultio' ),
                    'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
                    'controls' => array(
                        array(
                            'name' => 'image',
                            'label' => esc_html__('Image', 'consultio' ),
                            'type' => \Elementor\Controls_Manager::MEDIA,
                        ),
                        array(
                            'name' => 'image_overlay',
                            'label' => esc_html__('Image Overlay', 'consultio' ),
                            'type' => \Elementor\Controls_Manager::MEDIA,
                        ),
                        array(
                            'name' => 'color_overlay',
                            'label' => esc_html__('Color Overlay', 'consultio' ),
                            'type' => \Elementor\Controls_Manager::COLOR,
                        ),
                    ),
                ),
            ),
        ),
    ),
    get_template_directory() . '/elementor/core/widgets/'
);