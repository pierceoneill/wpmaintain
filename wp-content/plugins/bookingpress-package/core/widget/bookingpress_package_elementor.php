<?php

if (! class_exists('bookingpress_package_element_controller') ) {
    class bookingpress_package_element_controller
    {

        function __construct()
        {
            add_action('plugins_loaded', array( $this, 'bookingpress_package_element_widget' ));

        }
        function bookingpress_package_element_widget()
        {   
            if (! did_action('elementor/loaded') ) {
                return;
            }
            
            include_once BOOKINGPRESS_PACKAGE_WIDGET_DIR . '/bookingpress_package_elementor_element.php';
        }
    }
}

$bookingpress_package_element_controller = new bookingpress_package_element_controller();