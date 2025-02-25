<?php

// Register Google Maps Widget
ct_add_custom_widget(
    array(
        'name' => 'ct_google_map',
        'title' => esc_html__( 'Google Maps', 'consultio' ),
        'icon' => 'eicon-google-maps',
        'categories' => array( Case_Theme_Core::CT_CATEGORY_NAME ),
        'scripts' => array(
            'maps-googleapis',
            'custom-gm-widget-js',
        ),
        'params' => array(
            'sections' => array(
                array(
                    'name' => 'source_section',
                    'label' => esc_html__( 'Source Settings', 'consultio' ),
                    'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
                    'controls' => array(
                        array(
                            'name' => 'address',
                            'label' => esc_html__( 'Address', 'consultio' ),
                            'type' => \Elementor\Controls_Manager::TEXT,
                            'default' => 'New York, United States',
                        ),
                        array(
                            'name' => 'coordinate',
                            'label' => esc_html__( 'Coordinate', 'consultio' ),
                            'type' => \Elementor\Controls_Manager::TEXT,
                            'default' => '40.6976684,-74.2605501',
                        ),
                        array(
                            'name' => 'infoclick',
                            'label' => esc_html__( 'Click Show Info Window', 'consultio' ),
                            'type' => \Elementor\Controls_Manager::SWITCHER,
                        ),
                        array(
                            'name' => 'markercoordinate',
                            'label' => esc_html__( 'Marker Coordinate', 'consultio' ),
                            'type' => \Elementor\Controls_Manager::TEXT,
                            'description' => esc_html__('Enter marker coordinate of Map, format input (latitude, longitude)', 'consultio'),
                            'default' => '40.6976684,-74.2605501',
                        ),
                        array(
                            'name' => 'markertitle',
                            'label' => esc_html__( 'Marker Title', 'consultio' ),
                            'type' => \Elementor\Controls_Manager::TEXT,
                        ),
                        array(
                            'name' => 'markerdesc',
                            'label' => esc_html__( 'Marker Description', 'consultio' ),
                            'type' => \Elementor\Controls_Manager::TEXTAREA,
                            'description' => esc_html__('Enter Description Info windows for marker', 'consultio')
                        ),
                        array(
                            'name' => 'markericon',
                            'label' => esc_html__( 'Marker Icon', 'consultio' ),
                            'type' => \Elementor\Controls_Manager::MEDIA,
                            'description' => esc_html__('Select image icon for marker', 'consultio')
                        ),
                        array(
                            'name' => 'infowidth',
                            'label' => esc_html__( 'Info Window Max Width', 'consultio' ),
                            'type' => \Elementor\Controls_Manager::TEXT,
                            'description' => esc_html__('Set max width for info window', 'consultio')
                        ),
                        array(
                            'name' => 'type',
                            'label' => esc_html__( 'Map Type', 'consultio' ),
                            'type' => \Elementor\Controls_Manager::SELECT,
                            'options' => [
                                'ROADMAP' => 'ROADMAP',
                                'HYBRID' => 'HYBRID',
                                'SATELLITE' => 'SATELLITE',
                                'TERRAIN' => 'TERRAIN'
                            ],
                            'default' => 'ROADMAP',
                            'description' => esc_html__('Select the map type.', 'consultio')
                        ),
                        array(
                            'name' => 'style',
                            'label' => esc_html__( 'Style Template', 'consultio' ),
                            'type' => \Elementor\Controls_Manager::SELECT,
                            'options' => [
                                '' => 'Google Default',
                                'light-monochrome' => 'Light Monochrome',
                                'blue-water' => 'Blue water',
                                'midnight-commander' => 'Midnight Commander',
                                'paper' => 'Paper',
                                'red-hues' => 'Red Hues',
                                'hot-pink' => 'Hot Pink',
                                'custom' => 'Custom',
                            ],
                            'default' => '',
                            'description' => esc_html__('Select the map template.', 'consultio')
                        ),
                        array(
                            'name' => 'content',
                            'label' => esc_html__( 'Custom Template', 'consultio' ),
                            'type' => \Elementor\Controls_Manager::CODE,
                            'language' => 'json',
                            'description' => esc_html__('Get template from snazzymaps.com', 'consultio'),
                            'condition' => [
                                'style' => 'custom',
                            ],
                        ),
                        array(
                            'name' => 'zoom',
                            'label' => esc_html__( 'Zoom', 'consultio' ),
                            'type' => \Elementor\Controls_Manager::TEXT,
                            'default' => 13,
                            'description' => esc_html__('Set max width for info window', 'consultio')
                        ),
                        array(
                            'name' => 'width',
                            'label' => esc_html__( 'Width', 'consultio' ),
                            'type' => \Elementor\Controls_Manager::TEXT,
                            'default' => 'auto',
                            'description' => esc_html__('Width of map without pixel, default is auto', 'consultio')
                        ),
                        array(
                            'name' => 'height',
                            'label' => esc_html__( 'Height', 'consultio' ),
                            'type' => \Elementor\Controls_Manager::TEXT,
                            'default' => '350px',
                            'description' => esc_html__('Height of map without pixel, default is 350px', 'consultio')
                        ),
                        array(
                            'name' => 'scrollwheel',
                            'label' => esc_html__( 'Scroll Wheel', 'consultio' ),
                            'type' => \Elementor\Controls_Manager::SWITCHER,
                            'description' => esc_html__('Height of map without pixel, default is 350px', 'consultio')
                        ),
                        array(
                            'name' => 'pancontrol',
                            'label' => esc_html__( 'Pan Control', 'consultio' ),
                            'type' => \Elementor\Controls_Manager::SWITCHER,
                            'description' => esc_html__('Height of map without pixel, default is 350px', 'consultio')
                        ),
                        array(
                            'name' => 'zoomcontrol',
                            'label' => esc_html__( 'Zoom Control', 'consultio' ),
                            'type' => \Elementor\Controls_Manager::SWITCHER,
                            'description' => esc_html__('Height of map without pixel, default is 350px', 'consultio')
                        ),
                        array(
                            'name' => 'scalecontrol',
                            'label' => esc_html__( 'Scale Control', 'consultio' ),
                            'type' => \Elementor\Controls_Manager::SWITCHER,
                            'description' => esc_html__('Height of map without pixel, default is 350px', 'consultio')
                        ),
                        array(
                            'name' => 'maptypecontrol',
                            'label' => esc_html__( 'Map Type Control', 'consultio' ),
                            'type' => \Elementor\Controls_Manager::SWITCHER,
                            'description' => esc_html__('Height of map without pixel, default is 350px', 'consultio')
                        ),
                        array(
                            'name' => 'streetviewcontrol',
                            'label' => esc_html__( 'Street View Control', 'consultio' ),
                            'type' => \Elementor\Controls_Manager::SWITCHER,
                            'description' => esc_html__('Height of map without pixel, default is 350px', 'consultio')
                        ),
                        array(
                            'name' => 'overviewmapcontrol',
                            'label' => esc_html__( 'Over View Map Control', 'consultio' ),
                            'type' => \Elementor\Controls_Manager::SWITCHER,
                            'description' => esc_html__('Height of map without pixel, default is 350px', 'consultio')
                        ),
                    ),
                ),
            ),
        ),
    ),
    get_template_directory() . '/elementor/core/widgets/'
);