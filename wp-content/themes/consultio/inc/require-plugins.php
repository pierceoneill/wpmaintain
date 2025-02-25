<?php
/**
 * Include the TGM_Plugin_Activation class.
 */
get_template_part( 'inc/libs/class-tgm-plugin-activation' );

add_action( 'tgmpa_register', 'consultio_register_required_plugins' );
/**
 * Register the required plugins for this theme.
 *
 * In this example, we register two plugins - one included with the TGMPA library
 * and one from the .org repo.
 *
 * The variable passed to tgmpa_register_plugins() should be an array of plugin
 * arrays.
 *
 * This function is hooked into tgmpa_init, which is fired within the
 * TGM_Plugin_Activation class constructor.
*/
function consultio_register_required_plugins() {

    /**
     * Array of plugin arrays. Required keys are name and slug.
     * If the source is NOT from the .org repo, then source is also required.
     */
    $plugins = array(

        /* Case Plugin */
        array(
            'name'               => esc_html__('* Redux Framework', 'consultio'),
            'slug'               => 'redux-framework',
            'required'           => true,
        ),

        array(
            'name'               => esc_html__('Elementor', 'consultio'),
            'slug'               => 'elementor',
            'required'           => true,
        ),

        array(
            'name'               => esc_html__('Case Theme Core', 'consultio'),
            'slug'               => 'case-theme-core',
            'source'             => esc_url('http://demo.casethemes.net/plugins/elementor/case-theme-core.zip'),
            'required'           => true,
        ),
        
        array(
            'name'               => esc_html__('Case Theme Import', 'consultio'),
            'slug'               => 'case-theme-import',
            'source'             => esc_url('http://demo.casethemes.net/plugins/elementor/case-theme-import.zip'),
            'required'           => true,
        ),

        array(
            'name'               => esc_html__('Case Theme User', 'consultio'),
            'slug'               => 'case-theme-user',
            'source'             => esc_url('http://demo.casethemes.net/plugins/elementor/case-theme-user.zip'),
            'required'           => true,
        ),


        array(
            'name'               => esc_html__('Booked', 'consultio'),
            'slug'               => 'booked',
            'source'             => esc_url('http://casethemes.net/plugins/booked.zip'),
            'required'           => true,
        ),

        array(
            'name'               => esc_html__('Revolution Slider', 'consultio'),
            'slug'               => 'revslider',
            'source'             => esc_url('http://demo.casethemes.net/plugins/revslider.zip'),
            'required'           => true,
        ),

        array(
            'name'               => esc_html__('Cryptocurrency Widgets', 'consultio'),
            'slug'               => 'massive-cryptocurrency-widgets',
            'source'             => esc_url('http://demo.casethemes.net/plugins/massive-cryptocurrency-widgets.zip'),
            'required'           => false,
        ),

        array(
            'name'               => esc_html__('Contact Form 7', 'consultio'),
            'slug'               => 'contact-form-7',
            'required'           => true,
        ),

        array(
            'name'               => esc_html__('Contact Form 7 Multi Step Pro', 'consultio'),
            'slug'               => 'contact-form-7-multi-step',
            'source'             => esc_url('http://demo.casethemes.net/plugins/contact-form-7-multi-step.zip'),
            'required'           => true,
        ),

        array(
            'name'               => esc_html__('Newsletter', 'consultio'),
            'slug'               => 'newsletter',
            'required'           => true,
        ),  

        array(
            'name'               => esc_html__('Instagram Feed', 'consultio'),
            'slug'               => 'instagram-feed',
            'required'           => true,
        ),  

        array(
            'name'               => esc_html__('Meks Simple Flickr', 'consultio'),
            'slug'               => 'meks-simple-flickr-widget',
            'required'           => false,
        ),  

        array(
            'name'               => esc_html__('Mailchimp', 'consultio'),
            'slug'               => 'mailchimp-for-wp',
            'required'           => true,
        ),  

        array(
            'name'               => esc_html__('WooCommerce', 'consultio'),
            'slug'               => "woocommerce",
            'required'           => true,
        ),

        array(
            'name'               => esc_html__('WooCommerce Quick View', 'consultio'),
            'slug'               => "yith-woocommerce-quick-view",
            'required'           => false,
        ),

        array(
            'name'               => esc_html__('WooCommerce Wishlist', 'consultio'),
            'slug'               => "yith-woocommerce-wishlist",
            'required'           => false,
        ),
        
    );

    /**
     * Array of configuration settings. Amend each line as needed.
     * If you want the default strings to be available under your own theme domain,
     * leave the strings uncommented.
     * Some of the strings are added into a sprintf, so see the comments at the
     * end of each line for what each argument will be.
    */
    $config = array(
        'default_path' => '',                      // Default absolute path to pre-packaged plugins.
        'menu'         => 'tgmpa-install-plugins', // Menu slug.

    );

    tgmpa( $plugins, $config );

}