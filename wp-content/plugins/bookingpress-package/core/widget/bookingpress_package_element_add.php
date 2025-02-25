<?php

namespace ElementorBookingpressPackage\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if (! defined('ABSPATH') ) {
    exit;
}

if (! class_exists('bookingpress_package_form_shortcode') ) {

    class bookingpress_package_form_shortcode extends Widget_Base
    {

        public function get_name()
        {
            return 'Package Booking Forms - WordPress Booking Plugin';
        }
        public function get_title()
        {
            return __('Package Booking Forms - WordPress Booking Plugin', 'bookingpress-package') . '<style>
            .bookingpress_package_element_icon{
                display: inline-block;
                width: 35px;
                height: 24px;
                background-image: url(' . BOOKINGPRESS_PACKAGE_URL . '/images/bookingpress_menu_icon.png);
                background-repeat: no-repeat;
                background-position: bottom;
            }
            </style>';
        }
        public function get_icon()
        {
            return 'bookingpress_package_element_icon';
        }
        public function get_categories()
        {
            return array( 'general' );
        }
        protected function render()
        {
            echo '[bookingpress_package_form]';
        }

    }
}