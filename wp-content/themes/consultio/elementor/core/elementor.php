<?php

$files = scandir(get_template_directory() . '/elementor/core/register');

foreach ($files as $file){
    $pos = strrpos($file, ".php");
    if($pos !== false){
        require_once get_template_directory() . '/elementor/core/register/' . $file;
    }
}

if(!function_exists('consultio_register_custom_icon_library')){
    add_filter('elementor/icons_manager/native', 'consultio_register_custom_icon_library');
    function consultio_register_custom_icon_library($tabs){
        $custom_tabs = [
            'extra_icon1' => [
                'name' => 'material',
                'label' => esc_html__( 'Material Design Iconic', 'consultio' ),
                'url' => get_template_directory_uri() . '/assets/css/material-design-iconic-font.min.css',
                'enqueue' => [  ],
                'prefix' => 'zmdi zmdi-',
                'displayPrefix' => 'material',
                'labelIcon' => 'zmdi zmdi-collection-text',
                'ver' => '1.0.0',
                'fetchJson' => get_template_directory_uri() . '/assets/elementor-icon/materialdesign.js',
                'native' => true,
            ],

            'extra_icon2' => [
                'name' => 'flaticon',
                'label' => esc_html__( 'Flaticon Version 1', 'consultio' ),
                'url' => get_template_directory_uri() . '/assets/css/flaticon.css',
                'enqueue' => [  ],
                'prefix' => 'flaticon-',
                'displayPrefix' => 'flaticon',
                'labelIcon' => 'flaticon-report',
                'ver' => '1.0.0',
                'fetchJson' => get_template_directory_uri() . '/assets/elementor-icon/flaticon.js',
                'native' => true,
            ],

            'extra_icon3' => [
                'name' => 'flaticonv2',
                'label' => esc_html__( 'Flaticon Version 2', 'consultio' ),
                'url' => get_template_directory_uri() . '/assets/css/flaticon-v2.css',
                'enqueue' => [  ],
                'prefix' => 'flaticonv2-',
                'displayPrefix' => 'flaticonv2',
                'labelIcon' => 'flaticonv2-creative',
                'ver' => '1.0.0',
                'fetchJson' => get_template_directory_uri() . '/assets/elementor-icon/flaticon-v2.js',
                'native' => true,
            ],

            'extra_icon4' => [
                'name' => 'flaticonv3',
                'label' => esc_html__( 'Flaticon Version 3', 'consultio' ),
                'url' => get_template_directory_uri() . '/assets/css/flaticon-v3.css',
                'enqueue' => [  ],
                'prefix' => 'flaticonv3-',
                'displayPrefix' => 'flaticonv3',
                'labelIcon' => 'flaticonv3-presentation',
                'ver' => '1.0.0',
                'fetchJson' => get_template_directory_uri() . '/assets/elementor-icon/flaticon-v3.js',
                'native' => true,
            ],

            'extra_icon5' => [
                'name' => 'flaticonv4',
                'label' => esc_html__( 'Flaticon Version 4', 'consultio' ),
                'url' => get_template_directory_uri() . '/assets/css/flaticon-v4.css',
                'enqueue' => [  ],
                'prefix' => 'flaticonv4-',
                'displayPrefix' => 'flaticonv4',
                'labelIcon' => 'flaticonv4-doctor',
                'ver' => '1.0.0',
                'fetchJson' => get_template_directory_uri() . '/assets/elementor-icon/flaticon-v4.js',
                'native' => true,
            ],

            'extra_icon6' => [
                'name' => 'flaticonv5',
                'label' => esc_html__( 'Flaticon Version 5', 'consultio' ),
                'url' => get_template_directory_uri() . '/assets/css/flaticon-v5.css',
                'enqueue' => [  ],
                'prefix' => 'flaticonv5-',
                'displayPrefix' => 'flaticonv5',
                'labelIcon' => 'flaticonv5-report',
                'ver' => '1.0.0',
                'fetchJson' => get_template_directory_uri() . '/assets/elementor-icon/flaticon-v5.js',
                'native' => true,
            ],

            'extra_icon7' => [
                'name' => 'flaticonv6',
                'label' => esc_html__( 'Flaticon Version 6', 'consultio' ),
                'url' => get_template_directory_uri() . '/assets/css/flaticon-v6.css',
                'enqueue' => [  ],
                'prefix' => 'flaticonv6-',
                'displayPrefix' => 'flaticonv6',
                'labelIcon' => 'flaticonv6-worldwide',
                'ver' => '1.0.0',
                'fetchJson' => get_template_directory_uri() . '/assets/elementor-icon/flaticon-v6.js',
                'native' => true,
            ],

            'extra_icon8' => [
                'name' => 'flaticonv7',
                'label' => esc_html__( 'Flaticon Version 7', 'consultio' ),
                'url' => get_template_directory_uri() . '/assets/css/flaticon-v7.css',
                'enqueue' => [  ],
                'prefix' => 'flaticonv7-',
                'displayPrefix' => 'flaticonv7',
                'labelIcon' => 'flaticonv7-contacts-book',
                'ver' => '1.0.0',
                'fetchJson' => get_template_directory_uri() . '/assets/elementor-icon/flaticon-v7.js',
                'native' => true,
            ],

            'extra_icon9' => [
                'name' => 'flaticonv8',
                'label' => esc_html__( 'Flaticon Version 8', 'consultio' ),
                'url' => get_template_directory_uri() . '/assets/css/flaticon-v8.css',
                'enqueue' => [  ],
                'prefix' => 'flaticonv8-',
                'displayPrefix' => 'flaticonv8',
                'labelIcon' => 'flaticonv8-meeting-room',
                'ver' => '1.0.0',
                'fetchJson' => get_template_directory_uri() . '/assets/elementor-icon/flaticon-v8.js',
                'native' => true,
            ],
        ];

        $tabs = array_merge($custom_tabs, $tabs);

        return $tabs;
    }
}