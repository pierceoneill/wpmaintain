<?php
// Register Video Player Widget
ct_add_custom_widget(
    array(
        'name' => 'ct_video_player',
        'title' => esc_html__('Case Video Player', 'consultio' ),
        'icon' => 'eicon-play',
        'categories' => array( Case_Theme_Core::CT_CATEGORY_NAME ),
        'scripts' => array(

        ),
        'params' => array(
            'sections' => array(
                array(
                    'name' => 'icon_section',
                    'label' => esc_html__('Video Player', 'consultio' ),
                    'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
                    'controls' => array(
                        array(
                            'name' => 'image',
                            'label' => esc_html__('Choose Image', 'consultio' ),
                            'type' => \Elementor\Controls_Manager::MEDIA,
                        ),
                        array(
                            'name' => 'img_size',
                            'label' => esc_html__('Image Size', 'consultio' ),
                            'type' => \Elementor\Controls_Manager::TEXT,
                            'description' => 'Enter image size (Example: "thumbnail", "medium", "large", "full" or other sizes defined by theme). Alternatively enter size in pixels (Example: 200x100 (Width x Height).',
                            'condition' => [
                                'image_type' => 'img',
                            ],
                            'default' => 'full'
                        ),
                        array(
                            'name' => 'background_overlay',
                            'label' => esc_html__('Background Overlay', 'consultio' ),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .ct-video-player .bg-overlay' => 'background-color: {{VALUE}};',
                            ],
                        ),
                        array(
                            'name' => 'image_type',
                            'label' => esc_html__('Image Type', 'consultio' ),
                            'type' => \Elementor\Controls_Manager::SELECT,
                            'options' => [
                                'img' => 'Image',
                                'bg' => 'Background',
                            ],
                            'default' => 'img',
                        ),
                        array(
                            'name' => 'image_height',
                            'label' => esc_html__('Image Height', 'consultio' ),
                            'type' => \Elementor\Controls_Manager::SLIDER,
                            'description' => esc_html__('Enter number.', 'consultio' ),
                            'condition' => [
                                'image_type' => 'bg',
                            ],
                            'range' => [
                                'px' => [
                                    'min' => 0,
                                    'max' => 3000,
                                ],
                            ],
                            'control_type' => 'responsive',
                            'selectors' => [
                                '{{WRAPPER}} .ct-video-player .ct-video-image-bg' => 'height: {{SIZE}}{{UNIT}};',
                            ],
                        ),
                        array(
                            'name' => 'video_link',
                            'label' => esc_html__('Video Link', 'consultio' ),
                            'type' => \Elementor\Controls_Manager::TEXT,
                        ),
                        array(
                            'name' => 'btn_video_style',
                            'label' => esc_html__('Button Video Style', 'consultio' ),
                            'type' => \Elementor\Controls_Manager::SELECT,
                            'options' => [
                                'style1' => 'Style 1',
                                'style2' => 'Style 2',
                                'style3' => 'Style 3',
                                'style4' => 'Style 4 (Light)',
                                'style5' => 'Style 5 (Small)',
                                'style6' => 'Style 6 (Light)',
                                'style7' => 'Style 7 (Light)',
                                'style8' => 'Style 8 (Light Fixed Center)',
                                'style9' => 'Style 9 (Light + Title)',
                                'style10' => 'Style 10',
                                'style11' => 'Style 11 (Light)',
                                'style12' => 'Style 12',
                                'style13' => 'Style 13 (Dark)',
                                'style14' => 'Style 14 (Border Light)',
                                'style15' => 'Style 15 (Position Top/Left)',
                            ],
                            'default' => 'style1',
                        ),
                        array(
                            'name' => 'btn-color',
                            'label' => esc_html__( 'Button Color', 'consultio' ),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'default' => '',
                            'selectors' => [
                                '{{WRAPPER}} .ct-video-player .ct-video-button' => 'color: {{VALUE}} !important;',
                            ],
                        ),
                        array(
                            'name' => 'btn_bg_color',
                            'label' => esc_html__( 'Button Background Color', 'consultio' ),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'default' => '',
                            'selectors' => [
                                '{{WRAPPER}} .ct-video-player .ct-video-button' => 'background-color: {{VALUE}} !important;background-image: none !important;',
                            ],
                        ),
                        array(
                            'name' => 'video_title',
                            'label' => esc_html__('Title', 'consultio' ),
                            'type' => \Elementor\Controls_Manager::TEXT,
                            'condition' => [
                                'btn_video_style' => 'style9',
                            ],
                        ),
                        array(
                            'name' => 'ct_animate',
                            'label' => esc_html__('Case Animate', 'consultio' ),
                            'type' => \Elementor\Controls_Manager::SELECT,
                            'options' => consultio_animate(),
                            'default' => '',
                        ),
                    ),
                ),
            ),
        ),
    ),
    get_template_directory() . '/elementor/core/widgets/'
);