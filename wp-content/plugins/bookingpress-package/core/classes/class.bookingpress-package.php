<?php
if (!class_exists('bookingpress_package')) {
	class bookingpress_package Extends BookingPress_Core {
        function __construct(){

            global $wp, $wpdb, $tbl_bookingpress_packages, $tbl_bookingpress_package_services, $tbl_bookingpress_package_images, $BookingPress, $tbl_bookingpress_package_bookings, $tbl_bookingpress_package_bookings_meta;

            $tbl_bookingpress_packages = $wpdb->prefix.'bookingpress_packages';
            $tbl_bookingpress_package_services = $wpdb->prefix.'bookingpress_package_services';
            $tbl_bookingpress_package_images = $wpdb->prefix.'bookingpress_package_images';
            $tbl_bookingpress_package_bookings = $wpdb->prefix.'bookingpress_package_bookings';
            $tbl_bookingpress_package_bookings_meta = $wpdb->prefix.'bookingpress_package_bookings_meta';
            
            register_activation_hook(BOOKINGPRESS_PACKAGE_DIR.'/bookingpress-package.php', array('bookingpress_package', 'install'));
            register_uninstall_hook(BOOKINGPRESS_PACKAGE_DIR.'/bookingpress-package.php', array('bookingpress_package', 'uninstall'));

            add_action('user_register', array($this,'bookingpress_package_add_capabilities_to_new_user'));
            add_action('set_user_role', array($this, 'bookingpress_package_assign_caps_on_role_change'), 10, 3);
            add_action( 'admin_notices', array( $this, 'bookingpress_package_admin_notices') );

            $package_addon_working = $this->bookingpress_check_package_addon_requirement();  

            if( !function_exists('is_plugin_active') ){
                include_once ABSPATH . 'wp-admin/includes/plugin.php';
            }

            if( is_plugin_active( 'bookingpress-appointment-booking-pro/bookingpress-appointment-booking-pro.php' ) && !empty( $BookingPress->bpa_pro_plugin_version() ) && version_compare( $BookingPress->bpa_pro_plugin_version(), '3.2', '>=' ) && $package_addon_working ){

                /* add value to slug */
                add_action( 'init', array( $this, 'bookingpress_package_page_slugs' ), 11 );

                /* add menu at WordPress sidebar */
                add_action('bookingpress_add_specific_menu', array($this, 'bookingpress_add_specific_menu_func'), 10, 1);

                /* add menu at WordPress sidebar after payment menu */
                add_action('bookingpress_add_specific_menu_after_payment_menu', array($this, 'bookingpress_add_specific_menu_after_payment_menu_func'), 10, 1);                

                /* Change package status */
                add_action( 'wp_ajax_bookingpress_change_package_status', array( $this, 'bookingpress_change_package_status_func' ) );

                //Load default data variables
                add_action( 'admin_init', array( $this, 'bookingpress_package_vue_data_fields_func') );

                //Load CSS & JS at admin side
                add_action( 'admin_enqueue_scripts', array( $this, 'set_css' ), 12 );
                add_action( 'admin_enqueue_scripts', array( $this, 'set_js' ), 12 );

                add_filter( 'bookingpress_package_dynamic_view_load', array( $this, 'bookingpress_load_package_view_func' ), 10 );
                add_action( 'bookingpress_package_dynamic_vue_methods', array( $this, 'bookingpress_package_vue_methods_func' ) );
                add_action( 'bookingpress_package_dynamic_on_load_methods', array( $this, 'bookingpress_package_on_load_methods_func' ) );
                add_action( 'bookingpress_package_dynamic_data_fields', array( $this, 'bookingpress_package_dynamic_data_fields_func' ) );
                add_action( 'bookingpress_package_dynamic_helper_vars', array( $this, 'bookingpress_package_dynamic_helper_vars_func' ) );

                add_filter('bookingpress_modify_capability_data', array($this, 'bookingpress_modify_capability_data_func'), 11, 1);

                /* Get Service details */
                add_action('wp_ajax_bookingpress_get_packages', array($this, 'bookingpress_get_packages_func'));

                /* Save Service details */
                add_action('wp_ajax_bookingpress_add_package', array($this, 'bookingpress_save_package_details_func'));

                /* Delete Service */
                add_action('wp_ajax_bookingpress_delete_package', array($this, 'bookingpress_delete_package_func'));

                /* Get edit package details */
                add_action('wp_ajax_bookingpress_get_edit_package', array($this, 'bookingpress_get_edit_package_func'));                

                /* Add menu item to top bookingpress menu */
                /*
                Temp Remove
                add_action('bookingpress_add_dynamic_menu_item_to_top', array($this, 'bookingpress_add_dynamic_menu_item_to_top_func'));
                */

                add_filter( 'bookingpress_modify_form_sequence_flag', '__return_true');                

                add_filter( 'bookingpress_package_selection_visibility', '__return_true');

                /** Bulk Delete for Package */
                add_action( 'wp_ajax_bookingpress_bulk_package', array( $this, 'bookingpress_delete_package_bulk_action') );
                add_action( 'wp_ajax_bookingpress_position_package', array( $this, 'bookingpress_update_package_position') );

                /* Package placeholder */
                add_action( 'bookingpress_generate_booking_form_customize_css', array( $this, 'bookingpress_add_package_form_customize_css' ), 10, 2);

                /* Upload Image */            
                add_action( 'wp_ajax_bookingpress_upload_package', array( $this, 'bookingpress_upload_package_image') );

                add_action( 'wp_ajax_bookingpress_remove_package_file', array( $this, 'bookingpress_remove_package_file_func') );
                
                /* Function for create duplicate package */    
                add_action('wp_ajax_bookingpress_duplicate_package', array( $this, 'bookingpress_duplicate_package_func' ));

                /* Multi-Language Function Start Here */
                if(is_plugin_active('bookingpress-multilanguage/bookingpress-multilanguage.php')) {
                    add_filter('bookingpress_multilanguage_customize_allow_setting_type', array($this, 'bookingpress_multilanguage_customize_allow_setting_type_package'));
                } 

                /* Function to add Debug Log in Settings - view - download and delete */
                add_action( 'bookingpress_add_debug_log_outside', array($this, 'bookingpress_add_debug_log_outside_package_func'));
                add_filter( 'bookingpress_add_setting_dynamic_data_fields', array( $this, 'bookingpress_add_setting_dynamic_data_fields_package_func' ), 10 );
                add_filter( 'bookingpress_modify_debug_log_data', array($this, 'bookingpress_modify_debug_log_data_outside_package_func'), 10, 2);
                add_action( 'bookingpress_delete_debug_log_from_outside', array($this, 'bookingpress_clear_debug_payment_log_package_func'), 10, 1);
                add_filter( 'bookingpress_modify_download_debug_log_query', array( $this, 'bookingpress_modify_download_debug_log_query_package_func' ), 10, 3 );


                /* Calculate payment total amount with package discount */
                add_filter('bookingpress_modify_outside_total_amount', array( $this, 'bookingpress_modify_outside_total_amount_func'), 20,3);

                add_filter('bookingpress_modify_outside_sub_total_amount_appointment_details', array( $this, 'bookingpress_modify_outside_sub_total_amount_appointment_details_func'), 10,3);

                add_filter('bookingpress_appointment_add_view_field', array($this, 'bookingpress_appointment_add_view_field_func'), 10, 2);

                /* Function added for the help button Documentation load */
                add_action( 'bpa_add_extra_tab_outside_func', array( $this,'bpa_add_extra_tab_outside_func_arr_package'), 11);

                /* BookingPress Package Addon invoice tag added */
                add_filter('bookingpress_add_setting_dynamic_data_fields',array($this,'bookingpress_add_setting_dynamic_data_fields_func'),15);
                add_filter('bookingpress_change_label_value_for_invoice_using_appointment',array($this,'bookingpress_change_label_value_for_invoice_func'),10,4);
                
                /* Multi-Language Function Start Here */
                if(is_plugin_active('bookingpress-multilanguage/bookingpress-multilanguage.php')) {
                    add_filter('bookingpress_modified_language_translate_fields',array($this,'bookingpress_modified_language_translate_fields_func'),10);
                    add_filter('bookingpress_modified_package_language_translate_fields',array($this,'bookingpress_modified_package_translate_fields_func'),10);
                    add_filter('bookingpress_modified_language_translate_fields_section',array($this,'bookingpress_modified_language_translate_fields_section_func'),10);
                    
                }
                
                /* register widget */
                add_action( 'admin_init', array($this, 'bookingpress_package_add_gutenbergblock' ));
                add_filter('bookingpress_modify_booking_form_default_display_services',array($this,'bookingpress_modify_booking_form_default_display_services_func'),10,1);
                add_filter('bookingpress_modified_payment_revenue_detail',array($this,'bookingpress_modified_payment_revenue_detail_func'),10,2);

                add_action('bookingpress_revenue_list_extra_icons',array($this,'bookingpress_revenue_list_extra_icons_func'));
                add_action( 'bookingpress_modify_readmore_link', array( $this, 'bookingpress_modify_readmore_link_package'),20 );

            } 
            
	    add_action('admin_init', array( $this, 'bookingpress_update_package_data') );  
            add_action('activated_plugin',array($this,'bookingpress_is_package_addon_activated'),11,2);
        }
        
        function bookingpress_update_package_data(){
            global $BookingPress;
            $bookingpress_db_package_version = get_option('bookingpress_package_version', true);

            if( version_compare( $bookingpress_db_package_version, '1.9', '<' ) ){
                $bookingpress_load_package_update_file = BOOKINGPRESS_PACKAGE_DIR . '/core/views/upgrade_latest_data.php';
                include $bookingpress_load_package_update_file;
                $BookingPress->bookingpress_send_anonymous_data_cron();
            }
        }	
	
        function bookingpress_modify_readmore_link_package(){
            ?>
            var selected_tab = sessionStorage.getItem("current_tabname");
            if( "package" == bpa_requested_module || "package_order" == bpa_requested_module ){
                read_more_link = "https://www.bookingpressplugin.com/documents/service-package/";
            } 
            <?php
        }	
	
        function bookingpress_is_package_addon_activated($plugin,$network_activation)
        {  
            $myaddon_name = "bookingpress-package/bookingpress-package.php";

            if($plugin == $myaddon_name)
            {

                if(!(is_plugin_active('bookingpress-appointment-booking-pro/bookingpress-appointment-booking-pro.php')))
                {
                    deactivate_plugins($myaddon_name, FALSE);
                    $redirect_url = network_admin_url('plugins.php?deactivate=true&bkp_license_deactivate=true&bkp_deactivate_plugin='.$myaddon_name);
                    $bpa_dact_message = __('Please activate license of BookingPress premium plugin to use BookingPress Service Package Add-on', 'bookingpress-package');
					$bpa_link = sprintf( __('Please %s Click Here %s to Continue', 'bookingpress-package'), '<a href="javascript:void(0)" onclick="window.location.href=\'' . $redirect_url . '\'">', '</a>');
					wp_die('<p>'.$bpa_dact_message.'<br/>'.$bpa_link.'</p>');
                    die;
                }

                $license = trim( get_option( 'bkp_license_key' ) );
                $package = trim( get_option( 'bkp_license_package' ) );

                if( '' === $license || false === $license ) 
                {
                    deactivate_plugins($myaddon_name, FALSE);
                    $redirect_url = network_admin_url('plugins.php?deactivate=true&bkp_license_deactivate=true&bkp_deactivate_plugin='.$myaddon_name);
                    $bpa_dact_message = __('Please activate license of BookingPress premium plugin to use BookingPress Service Package Add-on', 'bookingpress-package');
					$bpa_link = sprintf( __('Please %s Click Here %s to Continue', 'bookingpress-package'), '<a href="javascript:void(0)" onclick="window.location.href=\'' . $redirect_url . '\'">', '</a>');
					wp_die('<p>'.$bpa_dact_message.'<br/>'.$bpa_link.'</p>');
                    die;
                }
                else
                {
                    $store_url = BOOKINGPRESS_PACKAGE_STORE_URL;
                    $api_params = array(
                        'edd_action' => 'check_license',
                        'license' => $license,
                        'item_id'  => $package,
                        //'item_name' => urlencode( $item_name ),
                        'url' => home_url()
                    );
                    $response = array('response'=>array('code'=>200,'message'=>'ok'),'body'=>'{"success": true,"license":"valid"}');
                    if ( is_wp_error( $response ) ) {
                        return false;
                    }
        
                    $license_data = json_decode( wp_remote_retrieve_body( $response ) );
                    $license_data_string =  wp_remote_retrieve_body( $response );
        
                    $message = '';

                    if ( true === $license_data->success ) 
                    {
                        if($license_data->license != "valid")
                        {
                            deactivate_plugins($myaddon_name, FALSE);
                            $redirect_url = network_admin_url('plugins.php?deactivate=true&bkp_license_deactivate=true&bkp_deactivate_plugin='.$myaddon_name);
                            $bpa_dact_message = __('Please activate license of BookingPress premium plugin to use BookingPress Service Package Add-on', 'bookingpress-package');
                            $bpa_link = sprintf( __('Please %s Click Here %s to Continue', 'bookingpress-package'), '<a href="javascript:void(0)" onclick="window.location.href=\'' . $redirect_url . '\'">', '</a>');
                            wp_die('<p>'.$bpa_dact_message.'<br/>'.$bpa_link.'</p>');
                            die;
                        }

                    }
                    else
                    {
                        deactivate_plugins($myaddon_name, FALSE);
                        $redirect_url = network_admin_url('plugins.php?deactivate=true&bkp_license_deactivate=true&bkp_deactivate_plugin='.$myaddon_name);
                        $bpa_dact_message = __('Please activate license of BookingPress premium plugin to use BookingPress Service Package Add-on', 'bookingpress-package');
                        $bpa_link = sprintf( __('Please %s Click Here %s to Continue', 'bookingpress-package'), '<a href="javascript:void(0)" onclick="window.location.href=\'' . $redirect_url . '\'">', '</a>');
                        wp_die('<p>'.$bpa_dact_message.'<br/>'.$bpa_link.'</p>');
                        die;
                    }
                }
            }

        }

        function bookingpress_revenue_list_extra_icons_func(){
        ?>
        <el-tooltip content="<?php esc_html_e('Package Order Transaction', 'bookingpress-package'); ?>" placement="top" v-if="scope.row.is_package_booking == '1'">
            <span class="material-icons-round bpa-apc__package-icon bpa-apc__package-rev-icon"> 
                    <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M7.98832 9.995H2.14277C1.6796 9.995 1.30061 10.3739 1.30061 10.8371V17.1579C1.30061 17.6211 1.6796 18 2.14277 18H8.08661C8.28473 18 8.28473 17.7872 8.28473 17.7872V10.2812C8.28467 10.2812 8.28467 9.995 7.98832 9.995ZM15.849 9.995H10.0094C9.65375 9.995 9.707 10.3654 9.707 10.3654V17.794C9.707 17.794 9.70412 17.9998 9.91806 17.9998H15.8489C16.3121 17.9998 16.6911 17.6209 16.6911 17.1577V10.8371C16.6912 10.3739 16.3122 9.995 15.849 9.995ZM8.28467 5.02167C8.28467 5.02167 8.28467 4.73631 8.00268 4.73631H1.19177C0.7286 4.73631 0.349609 5.1153 0.349609 5.57841V8.20717C0.349609 8.67034 0.7286 9.04927 1.19177 9.04927H8.02575C8.28467 9.04927 8.28467 8.82471 8.28467 8.82471V5.02167ZM16.8 4.73631H9.96733C9.70713 4.73631 9.70713 4.98425 9.70713 4.98425V8.82992C9.70713 8.82992 9.70713 9.04927 10.0235 9.04927H16.8C17.2631 9.04927 17.6421 8.67034 17.6421 8.20717V5.57841C17.6421 5.1153 17.2631 4.73631 16.8 4.73631ZM5.4203 4.11325C5.03499 4.11325 4.68306 4.08269 4.37444 4.02238C3.59057 3.86924 3.05181 3.57118 2.72737 3.11126C2.43667 2.69907 2.3477 2.19093 2.46286 1.60088C2.6646 0.568517 3.35791 0 4.41494 0C4.63864 0 4.88431 0.0258305 5.14519 0.0768166C5.8088 0.20646 6.65759 0.586985 7.41576 1.0947C8.70207 1.95619 8.76569 2.49175 8.70164 2.8197C8.60746 3.30158 8.15705 3.64591 7.32464 3.87243C6.76226 4.02545 6.06815 4.11325 5.4203 4.11325ZM4.415 1.34975C4.00564 1.34975 3.86495 1.46412 3.78771 1.85967C3.72451 2.18308 3.80421 2.29603 3.83035 2.33315C3.9398 2.48838 4.22492 2.61784 4.63324 2.69754C4.85357 2.74061 5.1258 2.76337 5.42024 2.76337C6.06772 2.76337 6.63814 2.66527 7.01664 2.55796C7.04419 2.55016 7.08696 2.51759 7.04112 2.49028C6.54629 2.08718 5.641 1.54891 4.88639 1.40147C4.71061 1.36724 4.55195 1.34975 4.415 1.34975ZM12.5909 4.11325H12.5908C11.943 4.11325 11.2489 4.02545 10.6865 3.87243C9.85407 3.64597 9.40372 3.30158 9.30954 2.81976C9.24555 2.49182 9.30905 1.95625 10.5955 1.09476C11.3535 0.587046 12.2023 0.206521 12.8661 0.076878C13.1269 0.0258919 13.3726 6.13551e-05 13.5961 6.13551e-05C14.6534 6.13551e-05 15.3466 0.568639 15.5482 1.601C15.6635 2.19099 15.5746 2.69913 15.2838 3.11132C14.9594 3.5713 14.4207 3.8693 13.6366 4.02244C13.3281 4.08263 12.9762 4.11325 12.5909 4.11325ZM10.9809 2.48194C10.937 2.5074 10.9582 2.54759 10.981 2.55415C11.3593 2.66294 11.9357 2.76344 12.5908 2.76344C12.8854 2.76344 13.1575 2.74067 13.3779 2.6976C13.7861 2.61784 14.0714 2.48844 14.1808 2.33321C14.207 2.29609 14.2868 2.18314 14.2234 1.85974C14.1462 1.46418 14.0055 1.34981 13.5961 1.34981C13.4592 1.34981 13.3006 1.36724 13.1247 1.4016C12.3701 1.54897 11.4757 2.07877 10.9809 2.48194Z"/>
                    </svg>                    
            </span>
        </el-tooltip>        
        <?php 
        }

        function bookingpress_modified_payment_revenue_detail_func($bookingpress_tmp_payment_data,$payment_detail){
            global $BookingPress;

            $bookingpress_tmp_payment_data['is_package_booking'] = "";
            if($payment_detail['bookingpress_purchase_type'] == 2){

                $bookingpress_tmp_payment_data['is_package_booking'] = "1";
                $bookingpress_package_price = (isset($payment_detail['bookingpress_package_price']))?$payment_detail['bookingpress_package_price']:0;
                $bookingpress_total_amount = (isset($payment_detail['bookingpress_total_amount']))?$payment_detail['bookingpress_total_amount']:0;
                $bookingpress_currency_name = $payment_detail['bookingpress_payment_currency'];
                $bookingpress_selected_currency = $BookingPress->bookingpress_get_currency_symbol($bookingpress_currency_name);                    
                

                $bookingpress_tmp_payment_data['total_amount'] = $bookingpress_total_amount;
                $bookingpress_tmp_payment_data['total_amount_with_currency'] = $BookingPress->bookingpress_price_formatter_with_currency_symbol($bookingpress_total_amount, $bookingpress_selected_currency); 
                
                $bookingpress_tmp_payment_data['deposit_amount'] = $bookingpress_total_amount;
                $bookingpress_tmp_payment_data['deposit_amount_with_currency'] = $BookingPress->bookingpress_price_formatter_with_currency_symbol($bookingpress_total_amount, $bookingpress_selected_currency);

                $bookingpress_tmp_payment_data['payment_service'] = stripslashes_deep($payment_detail['bookingpress_package_name']);

            }
            return $bookingpress_tmp_payment_data;
        }

        function bookingpress_check_package_addon_requirement(){
            global $bookingpress_pro_version;
            $package_working = true;
            if(is_plugin_active('bookingpress-tax/bookingpress-tax.php')){ 
                $bookingpress_tax_module = get_option('bookingpress_tax_module');
                if( version_compare( $bookingpress_tax_module, '1.6', '<' ) ){
                    $package_working = false;
                }   
            }
            if(is_plugin_active('bookingpress-multilanguage/bookingpress-multilanguage.php')){ 
                $bookingpress_multilanguage_version = get_option('bookingpress_multilanguage_version');
                if( version_compare( $bookingpress_multilanguage_version, '1.2', '<' ) ){
                    $package_working = false;
                }
            }
            /*
            if(is_plugin_active('bookingpress-tip/bookingpress-tip.php')){ 
                $bookingpress_tip_addon = get_option('bookingpress_tip_addon');
                if( version_compare( $bookingpress_tip_addon, '1.4', '<' ) ){
                    $package_working = false;
                }                
            }            
            if(is_plugin_active('bookingpress-invoice/bookingpress-invoice.php')){
                $bookingpress_invoice_version = get_option('bookingpress_invoice_version');
                if( version_compare( $bookingpress_invoice_version, '2.0', '<' ) ){
                    $package_working = false;
                }                
            }
            */
            if(is_plugin_active('bookingpress-stripe/bookingpress-stripe.php')){ 
                $bookingpress_stripe_payment_gateway = get_option('bookingpress_stripe_payment_gateway');
                if( version_compare( $bookingpress_stripe_payment_gateway, '1.7', '<' ) ){
                    $package_working = false;
                }                 
            }
            if(is_plugin_active('bookingpress-mollie/bookingpress-mollie.php')){
                $bookingpress_mollie_payment_gateway = get_option('bookingpress_mollie_payment_gateway');
                if( version_compare( $bookingpress_mollie_payment_gateway, '1.5', '<' ) ){
                    $package_working = false;
                }
            }
            if(is_plugin_active('bookingpress-authorize_net/bookingpress-authorize_net.php')){
                $bookingpress_auth_net_payment_gateway_version = get_option('bookingpress_auth_net_payment_gateway', true);
                if( version_compare( $bookingpress_auth_net_payment_gateway_version, '1.3', '<' ) ){
                    $package_working = false;
                }
            } 
            if(is_plugin_active('bookingpress-razorpay/bookingpress-razorpay.php')){ 
                $bookingpress_razorpay_payment_gateway = get_option('bookingpress_razorpay_payment_gateway');
                if( version_compare( $bookingpress_razorpay_payment_gateway, '1.4', '<' ) ){
                    $package_working = false;
                }
            } 
            if(is_plugin_active('bookingpress-paypalpro/bookingpress-paypalpro.php')){ 
                $bookingpress_paypalpro_payment_gateway = get_option('bookingpress_paypalpro_payment_gateway');
                if( version_compare( $bookingpress_paypalpro_payment_gateway, '1.3', '<' ) ){
                    $package_working = false;
                }                
            } 
            if(is_plugin_active('bookingpress-pagseguro/bookingpress-pagseguro.php')){
                $bookingpress_pagseguro_payment_gateway = get_option('bookingpress_pagseguro_payment_gateway');
                if( version_compare( $bookingpress_pagseguro_payment_gateway, '1.3', '<' ) ){
                    $package_working = false;
                }                
            } 
            if(is_plugin_active('bookingpress-braintree/bookingpress-braintree.php')){
                $bookingpress_braintree_addon_version = get_option('bookingpress_braintree_payment_gateway');
                if( version_compare( $bookingpress_braintree_addon_version, '1.4', '<' ) ){
                    $package_working = false;
                }
            }
            if(is_plugin_active('bookingpress-paystack/bookingpress-paystack.php')){ 
                $bookingpress_paystack_payment_gateway = get_option('bookingpress_paystack_payment_gateway');
                if( version_compare( $bookingpress_paystack_payment_gateway, '1.3', '<' ) ){
                    $package_working = false;
                } 
            }
            if(is_plugin_active('bookingpress-payumoney/bookingpress-payumoney.php')){ 
                $bookingpress_payumoney_payment_gateway = get_option('bookingpress_payumoney_payment_gateway');
                if( version_compare( $bookingpress_payumoney_payment_gateway, '1.3', '<' ) ){
                    $package_working = false;
                }                
            }
            if(is_plugin_active('bookingpress-paddle/bookingpress-paddle.php')){                
                $bookingpress_paddle_payment_gateway = get_option('bookingpress_paddle_payment_gateway');
                if( version_compare( $bookingpress_paddle_payment_gateway, '1.2', '<' ) ){
                    $package_working = false;
                }
            }  
            if(is_plugin_active('bookingpress-klarna/bookingpress-klarna.php')){
                $bookingpress_klarna_addon_version = get_option('bookingpress_klarna_payment_gateway');
                if( version_compare( $bookingpress_klarna_addon_version, '1.3', '<' ) ){
                    $package_working = false;
                }
            }
            if(is_plugin_active('bookingpress-payfast/bookingpress-payfast.php')){                
                $bookingpress_payfast_payment_gateway = get_option('bookingpress_payfast_payment_gateway');
                if( version_compare( $bookingpress_payfast_payment_gateway, '1.2', '<' ) ){
                    $package_working = false;
                }
            } 
            if(is_plugin_active('bookingpress-square/bookingpress-square.php')){ 
                $bookingpress_square_payment_gateway = get_option('bookingpress_square_payment_gateway');
                if( version_compare( $bookingpress_square_payment_gateway, '1.6', '<' ) ){
                    $package_working = false;
                } 
            }
            if(is_plugin_active('bookingpress-skrill/bookingpress-skrill.php')){ 
                $bookingpress_skrill_payment_gateway = get_option('bookingpress_skrill_payment_gateway');
                if( version_compare( $bookingpress_skrill_payment_gateway, '1.3', '<' ) ){
                    $package_working = false;
                }                
            }
            if(is_plugin_active('bookingpress-woocommerce/bookingpress-woocommerce.php')){ 
                $bookingpress_woocommerce_version = get_option('bookingpress_woocommerce_version');
                if( version_compare( $bookingpress_woocommerce_version, '1.7', '<' ) ){
                    $package_working = false;
                }                
            }  

            return $package_working;
        }

        /**
         * Function for add package admin notice
         *
         * @return void
         */
        function bookingpress_package_admin_notices(){
            if( !function_exists('is_plugin_active') ){
                include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
            }
            if( !is_plugin_active('bookingpress-appointment-booking-pro/bookingpress-appointment-booking-pro.php') ){
                echo "<div class='notice notice-warning'><p>" . esc_html__('BookingPress - Service Package plugin requires BookingPress Premium Plugin installed and active.', 'bookingpress-package') . "</p></div>";
            }
            if( file_exists( WP_PLUGIN_DIR . '/bookingpress-appointment-booking-pro/bookingpress-appointment-booking-pro.php' ) ){
                $bpa_pro_plugin_info = get_plugin_data( WP_PLUGIN_DIR . '/bookingpress-appointment-booking-pro/bookingpress-appointment-booking-pro.php' );
                $bpa_pro_plugin_version = $bpa_pro_plugin_info['Version'];                
                if( version_compare( $bpa_pro_plugin_version, '3.2', '<' ) ){
                    echo "<div class='notice notice-error is-dismissible'><p>".esc_html__("BookingPress - Service Package Plugin Requires to update the BookingPress Premium Plugin to version 3.2 or higher in order to use the BookingPress Package plugin", "bookingpress-package").".</p></div>";
                }
            }
            if(is_plugin_active('bookingpress-tip/bookingpress-tip.php')){ 
                $bookingpress_tip_addon = get_option('bookingpress_tip_addon');
                if( version_compare( $bookingpress_tip_addon, '1.4', '<' ) ){
                    echo "<div class='notice notice-error is-dismissible'><p>".esc_html__("BookingPress - Service Package Plugin Requires to update the BookingPress - Tip Plugin to version 1.4 or higher.", "bookingpress-package")."</p></div>";
                }                
            }            
            if(is_plugin_active('bookingpress-tax/bookingpress-tax.php')){ 
                $bookingpress_tax_module = get_option('bookingpress_tax_module');
                if( version_compare( $bookingpress_tax_module, '1.6', '<' ) ){
                    echo "<div class='notice notice-error is-dismissible'><p>".esc_html__("BookingPress - Service Package Plugin Requires to update the BookingPress - Tax Plugin to version 1.6 or higher.", "bookingpress-package")."</p></div>";
                }   
            }
            if(is_plugin_active('bookingpress-multilanguage/bookingpress-multilanguage.php')){ 
                $bookingpress_multilanguage_version = get_option('bookingpress_multilanguage_version');
                if( version_compare( $bookingpress_multilanguage_version, '1.2', '<' ) ){
                    echo "<div class='notice notice-error is-dismissible'><p>".esc_html__("BookingPress - Service Package Plugin Requires to update the BookingPress - Multi-Language Plugin to version 1.2 or higher.", "bookingpress-package")."</p></div>";
                }
            }
            if(is_plugin_active('bookingpress-invoice/bookingpress-invoice.php')){
                $bookingpress_invoice_version = get_option('bookingpress_invoice_version');
                if( version_compare( $bookingpress_invoice_version, '2.0', '<' ) ){
                    echo "<div class='notice notice-error is-dismissible'><p>".esc_html__("BookingPress - Service Package Plugin Requires to update the BookingPress - Invoice Plugin to version 2.0 or higher.", "bookingpress-package")."</p></div>";
                }                
            }
            if(is_plugin_active('bookingpress-stripe/bookingpress-stripe.php')){ 
                $bookingpress_stripe_payment_gateway = get_option('bookingpress_stripe_payment_gateway');
                if( version_compare( $bookingpress_stripe_payment_gateway, '1.7', '<' ) ){
                    echo "<div class='notice notice-error is-dismissible'><p>".esc_html__("BookingPress - Service Package Plugin Requires to update the BookingPress - Stripe Payment Gateway Plugin to version 1.7 or higher.", "bookingpress-package")."</p></div>";
                }                 
            }
            if(is_plugin_active('bookingpress-mollie/bookingpress-mollie.php')){
                $bookingpress_mollie_payment_gateway = get_option('bookingpress_mollie_payment_gateway');
                if( version_compare( $bookingpress_mollie_payment_gateway, '1.5', '<' ) ){
                    echo "<div class='notice notice-error is-dismissible'><p>".esc_html__("BookingPress - Service Package Plugin Requires to update the BookingPress - Mollie Payment Gateway Plugin to version 1.5 or higher.", "bookingpress-package")."</p></div>";
                }
            }
            if(is_plugin_active('bookingpress-authorize_net/bookingpress-authorize_net.php')){
                $bookingpress_auth_net_payment_gateway_version = get_option('bookingpress_auth_net_payment_gateway', true);
                if( version_compare( $bookingpress_auth_net_payment_gateway_version, '1.3', '<' ) ){
                    echo "<div class='notice notice-error is-dismissible'><p>".esc_html__("BookingPress - Service Package Plugin Requires to update the BookingPress - Authorize.Net Payment Gateway Plugin to version 1.3 or higher.", "bookingpress-package")."</p></div>";
                }
            }
            if(is_plugin_active('bookingpress-razorpay/bookingpress-razorpay.php')){ 
                $bookingpress_razorpay_payment_gateway = get_option('bookingpress_razorpay_payment_gateway');
                if( version_compare( $bookingpress_razorpay_payment_gateway, '1.4', '<' ) ){
                    echo "<div class='notice notice-error is-dismissible'><p>".esc_html__("BookingPress - Service Package Plugin Requires to update the BookingPress - Razorpay Payment Gateway Plugin to version 1.4 or higher.", "bookingpress-package")."</p></div>";
                }
            }
            if(is_plugin_active('bookingpress-paypalpro/bookingpress-paypalpro.php')){ 
                $bookingpress_paypalpro_payment_gateway = get_option('bookingpress_paypalpro_payment_gateway');
                if( version_compare( $bookingpress_paypalpro_payment_gateway, '1.3', '<' ) ){
                    echo "<div class='notice notice-error is-dismissible'><p>".esc_html__("BookingPress - Service Package Plugin Requires to update the BookingPress - PayPal Pro Payment Gateway Plugin to version 1.3 or higher.", "bookingpress-package")."</p></div>";
                }                
            }
            if(is_plugin_active('bookingpress-pagseguro/bookingpress-pagseguro.php')){
                $bookingpress_pagseguro_payment_gateway = get_option('bookingpress_pagseguro_payment_gateway');
                if( version_compare( $bookingpress_pagseguro_payment_gateway, '1.3', '<' ) ){
                    echo "<div class='notice notice-error is-dismissible'><p>".esc_html__("BookingPress - Service Package Plugin Requires to update the BookingPress - PagSeguro Payment Gateway Plugin to version 1.3 or higher.", "bookingpress-package")."</p></div>";
                }                
            }
            if(is_plugin_active('bookingpress-braintree/bookingpress-braintree.php')){
                $bookingpress_braintree_addon_version = get_option('bookingpress_braintree_payment_gateway');                
                if( version_compare( $bookingpress_braintree_addon_version, '1.4', '<' ) ){
                    echo "<div class='notice notice-error is-dismissible'><p>".esc_html__("BookingPress - Service Package Plugin Requires to update the BookingPress - Braintree Payment Gateway Plugin to version 1.4 or higher.", "bookingpress-package")."</p></div>";
                }
            }
            if(is_plugin_active('bookingpress-paystack/bookingpress-paystack.php')){ 
                $bookingpress_paystack_payment_gateway = get_option('bookingpress_paystack_payment_gateway');
                if( version_compare( $bookingpress_paystack_payment_gateway, '1.3', '<' ) ){
                    echo "<div class='notice notice-error is-dismissible'><p>".esc_html__("BookingPress - Service Package Plugin Requires to update the BookingPress - Paystack Payment Gateway Plugin to version 1.3 or higher.", "bookingpress-package")."</p></div>";
                } 
            }
            if(is_plugin_active('bookingpress-payumoney/bookingpress-payumoney.php')){ 
                $bookingpress_payumoney_payment_gateway = get_option('bookingpress_payumoney_payment_gateway');
                if( version_compare( $bookingpress_payumoney_payment_gateway, '1.3', '<' ) ){
                    echo "<div class='notice notice-error is-dismissible'><p>".esc_html__("BookingPress - Service Package Plugin Requires to update the BookingPress - PayUMoney Payment Gateway Plugin to version 1.3 or higher.", "bookingpress-package")."</p></div>";
                }                
            }
            if(is_plugin_active('bookingpress-paddle/bookingpress-paddle.php')){                
                $bookingpress_paddle_payment_gateway = get_option('bookingpress_paddle_payment_gateway');
                if( version_compare( $bookingpress_paddle_payment_gateway, '1.2', '<' ) ){
                    echo "<div class='notice notice-error is-dismissible'><p>".esc_html__("BookingPress - Service Package Plugin Requires to update the BookingPress - Paddle Payment Gateway Plugin to version 1.2 or higher.", "bookingpress-package")."</p></div>";
                }
            }
            if(is_plugin_active('bookingpress-klarna/bookingpress-klarna.php')){
                $bookingpress_klarna_addon_version = get_option('bookingpress_klarna_payment_gateway');
                if( version_compare( $bookingpress_klarna_addon_version, '1.3', '<' ) ){
                    echo "<div class='notice notice-error is-dismissible'><p>".esc_html__("BookingPress - Service Package Plugin Requires to update the BookingPress - Klarna Payment Gateway Plugin to version 1.3 or higher.", "bookingpress-package")."</p></div>";
                }
            }
            if(is_plugin_active('bookingpress-payfast/bookingpress-payfast.php')){                
                $bookingpress_payfast_payment_gateway = get_option('bookingpress_payfast_payment_gateway');
                if( version_compare( $bookingpress_payfast_payment_gateway, '1.2', '<' ) ){
                    echo "<div class='notice notice-error is-dismissible'><p>".esc_html__("BookingPress - Service Package Plugin Requires to update the BookingPress - PayFast Payment Gateway Plugin to version 1.2 or higher.", "bookingpress-package")."</p></div>";
                }
            }                                           
            if(is_plugin_active('bookingpress-square/bookingpress-square.php')){ 
                $bookingpress_square_payment_gateway = get_option('bookingpress_square_payment_gateway');
                if( version_compare( $bookingpress_square_payment_gateway, '1.6', '<' ) ){
                    echo "<div class='notice notice-error is-dismissible'><p>".esc_html__("BookingPress - Service Package Plugin Requires to update the BookingPress - Square Payment Gateway Plugin to version 1.6 or higher.", "bookingpress-package")."</p></div>";
                } 
            }            
            if(is_plugin_active('bookingpress-skrill/bookingpress-skrill.php')){ 
                $bookingpress_skrill_payment_gateway = get_option('bookingpress_skrill_payment_gateway');
                if( version_compare( $bookingpress_skrill_payment_gateway, '1.3', '<' ) ){
                    echo "<div class='notice notice-error is-dismissible'><p>".esc_html__("BookingPress - Service Package Plugin Requires to update the BookingPress - Skrill Payment Gateway Plugin to version 1.3 or higher.", "bookingpress-package")."</p></div>";
                }                
            }
            if(is_plugin_active('bookingpress-woocommerce/bookingpress-woocommerce.php')){ 
                $bookingpress_woocommerce_version = get_option('bookingpress_woocommerce_version');
                if( version_compare( $bookingpress_woocommerce_version, '1.7', '<' ) ){
                    echo "<div class='notice notice-error is-dismissible'><p>".esc_html__("BookingPress - Service Package Plugin Requires to update the BookingPress - WooCommerce Payment Gateway Plugin to version 1.7 or higher.", "bookingpress-package")."</p></div>";
                }                
            }            

        }

        /**
         * Function for display package service only
         *
         * @param  mixed $services
         * @return void
         */
        function bookingpress_modify_booking_form_default_display_services_func($services){
            $bpservice_ids = isset($_REQUEST['bpservice_ids']) ? sanitize_text_field($_REQUEST['bpservice_ids']) : '';
            if(!empty($bpservice_ids)){
                if(empty($services)){
                    $services = $bpservice_ids;
                }else{
                    /*
                    $servicesarr = explode(",",$services);
                    $bpservice_ids_arr = explode(",",$bpservice_ids);
                    $bookingpress_service_arrs = array_merge($servicesarr,$bpservice_ids_arr);
                    $services = implode(",",$bookingpress_service_arrs);
                    */
                    $services = $bpservice_ids;
                }
            }
            return $services;
        }

        /**
         * Add gutenberg blocks
         *
         * @return void
         */
        function bookingpress_package_add_gutenbergblock() {
            register_block_type( BOOKINGPRESS_PACKAGE_DIR . '/js/build/package_booking_form' ); 
        }

                
        /**
         * Function for check multi-language addon active or not
         *
         * @return void
         */
        function is_multi_language_addon_active(){
            return (is_plugin_active('bookingpress-multilanguage/bookingpress-multilanguage.php'))?1:0;
        }

        /**
         * Function for add language new section
         *
         * @param  mixed $bookingpress_all_language_translation_fields_section
         * @return void
         */
        function bookingpress_modified_language_translate_fields_section_func($bookingpress_all_language_translation_fields_section){
            $bookingpress_location_section_added = array('package' => __('Package', 'bookingpress-package') );
            $bookingpress_all_language_translation_fields_section = array_merge($bookingpress_all_language_translation_fields_section,$bookingpress_location_section_added);			
            return $bookingpress_all_language_translation_fields_section;
        }

        /**
         * Function for add language translation fields
         *
         * @param  mixed $bookingpress_all_language_translation_fields
         * @return void
        */
        function bookingpress_modified_language_translate_fields_func($bookingpress_all_language_translation_fields){

            $bookingpress_package_language_translation_fields = array(                
                'package' => array(
                    'bookingpress_package_name' => array('field_type'=>'text','field_label'=>__('Package Name', 'bookingpress-package'),'save_field_type'=>'package'),
                    'bookingpress_package_description' => array('field_type'=>'textarea','field_label'=>__('Package Description', 'bookingpress-package'),'save_field_type'=>'package'),
                )                    
            );
            $bookingpress_all_language_translation_fields = array_merge($bookingpress_all_language_translation_fields,$bookingpress_package_language_translation_fields);
             
			return $bookingpress_all_language_translation_fields;
        }

        /**
         * Function for add language translation fields
         *
         * @param  mixed $bookingpress_all_language_translation_fields
         * @return void
        */
        function bookingpress_modified_package_translate_fields_func($bookingpress_all_language_translation_fields){
            $bookingpress_package_language_translation_fields = array(                
                'package' => array(
                    'bookingpress_package_name' => array('field_type'=>'text','field_label'=>__('Package Name', 'bookingpress-package'),'save_field_type'=>'package'),
                    'bookingpress_package_description' => array('field_type'=>'textarea','field_label'=>__('Package Description', 'bookingpress-package'),'save_field_type'=>'package'),
                )                    
            );   
            $bookingpress_all_language_translation_fields = array_merge($bookingpress_all_language_translation_fields,$bookingpress_package_language_translation_fields);
            return $bookingpress_all_language_translation_fields;
        }

        /**
         * Function for add invoice calculation
         *
         * @param  mixed $bookingpress_invoice_html_view
         * @param  mixed $log_detail
         * @return void
        */
        function bookingpress_change_label_value_for_invoice_func($bookingpress_invoice_html_view, $log_detail,$bookingpress_final_appointment_details,$bookingpress_total_amount){

            global $BookingPress;

            //print_r($log_detail); die;

            $bookingpress_package_discount_amount = 0;            
            $bookingpress_currency_name   = !empty($log_detail['bookingpress_payment_currency']) ? esc_html($log_detail['bookingpress_payment_currency']) : '';
            $bookingpress_currency_symbol = $BookingPress->bookingpress_get_currency_symbol( $bookingpress_currency_name );
            $bookingpress_applied_package_data = (isset($log_detail['bookingpress_applied_package_data']))?$log_detail['bookingpress_applied_package_data']:'';
            $has_package_used = 0;
            if(!empty($bookingpress_applied_package_data)){
                $bookingpress_package_discount_amount = (isset($log_detail['bookingpress_package_discount_amount']))?$log_detail['bookingpress_package_discount_amount']:0;
                $has_package_used = 1;
            }            
            if(!empty($bookingpress_final_appointment_details) && is_array($bookingpress_final_appointment_details)){
                $final_discount_amt = 0;
                if(count($bookingpress_final_appointment_details) > 0){
                    foreach($bookingpress_final_appointment_details as $appointment_val){                        
                        $bookingpress_package_discount_amount_fnl = (isset($appointment_val['all_fields']['bookingpress_package_discount_amount']))?$appointment_val['all_fields']['bookingpress_package_discount_amount']:0;
                        if($bookingpress_package_discount_amount_fnl > 0){
                            $has_package_used = 1;
                        }
                        $final_discount_amt = $final_discount_amt + floatval($bookingpress_package_discount_amount_fnl);
                    }
                    $bookingpress_package_discount_amount = $final_discount_amt;                    
                }    
            }
            if($has_package_used){
                $bookingpress_total_amount = $bookingpress_total_amount - $bookingpress_package_discount_amount;
                $bookingpress_total_amount_with_currency = $BookingPress->bookingpress_price_formatter_with_currency_symbol($bookingpress_total_amount, $bookingpress_currency_symbol);
                $bookingpress_due_amount_with_currency = $BookingPress->bookingpress_price_formatter_with_currency_symbol(0, $bookingpress_currency_symbol);                                

                $bookingpress_invoice_html_view = str_replace('{total_amt}', $bookingpress_total_amount_with_currency, $bookingpress_invoice_html_view);
                $bookingpress_invoice_html_view = str_replace('{due_amt}', $bookingpress_due_amount_with_currency, $bookingpress_invoice_html_view);

                $bookingpress_invoice_html_view = str_replace('{paid_amt}', $bookingpress_total_amount_with_currency, $bookingpress_invoice_html_view);

            }            

            $bookingpress_package_discount_amount_with_currency = $BookingPress->bookingpress_price_formatter_with_currency_symbol($bookingpress_package_discount_amount, $bookingpress_currency_symbol);            
            $bookingpress_invoice_html_view = str_replace('{package_discount_amt}', $bookingpress_package_discount_amount_with_currency, $bookingpress_invoice_html_view);

            return $bookingpress_invoice_html_view;

        }

        /**
         * Function for add invoice package tag
         *
         * @param  mixed $bookingpress_dynamic_setting_data_fields
         * @return void
        */
        function bookingpress_add_setting_dynamic_data_fields_func($bookingpress_dynamic_setting_data_fields) {   
                        
            if(is_plugin_active('bookingpress-invoice/bookingpress-invoice.php')){

                if(isset($bookingpress_dynamic_setting_data_fields['bookingpress_invoice_tag_list'])){

                    $bookingpress_invoice_tag_list = (isset($bookingpress_dynamic_setting_data_fields['bookingpress_invoice_tag_list']))?$bookingpress_dynamic_setting_data_fields['bookingpress_invoice_tag_list']:array();
                    $bookingpress_invoice_tag_list[] =  
                    array( 
                        'group_tag_name' =>  'package',
                        'tag_details' => array(      
                            array( 'tag_name' =>  '{package_discount_amt}'),
                        ),
                    );
                    $bookingpress_dynamic_setting_data_fields['bookingpress_invoice_tag_list'] = $bookingpress_invoice_tag_list;

                }                
            }

            return $bookingpress_dynamic_setting_data_fields;

        }

        
        /**
         * Function for get package service purchase count
         *
         * @param  mixed $bookingpress_package_no
         * @param  mixed $bookingpress_service_id
         * @return void
         */
        function get_package_service_purchase_count($bookingpress_package_no,$bookingpress_service_id,$exclude_appointment_id = 0){
            global $tbl_bookingpress_appointment_bookings, $wpdb;
            $bookingpress_total_package_serv_booked = 0;
            if(!empty($bookingpress_package_no) && !empty($bookingpress_service_id)){
                $exclude_appointment_id = intval($exclude_appointment_id);
                $bookingpress_total_package_serv_booked = $wpdb->get_var($wpdb->prepare("SELECT SUM(bookingpress_selected_extra_members) as total FROM {$tbl_bookingpress_appointment_bookings} WHERE bookingpress_service_id = %d AND bookingpress_package_id = %d AND bookingpress_purchase_type = %d AND bookingpress_appointment_booking_id <> %d AND (bookingpress_appointment_status = %s OR bookingpress_appointment_status = %s)", $bookingpress_service_id, $bookingpress_package_no, 3,$exclude_appointment_id,'1', '2')); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- Reason: $tbl_bookingpress_appointment_bookings is table name defined globally. False Positive alarm
                if(empty($bookingpress_total_package_serv_booked)){
                    $bookingpress_total_package_serv_booked = 0;
                }
            }
            return $bookingpress_total_package_serv_booked;
        }

        function bookingpress_multilanguage_customize_allow_setting_type_package($customize_allow_setting_type){
            array_push($customize_allow_setting_type, 'package_booking_form');
            return $customize_allow_setting_type;
        }

        /**
         * bpa_add_extra_tab_outside_func_arr_package
         *
         * @return void
         */
        function bpa_add_extra_tab_outside_func_arr_package(){ ?>
            if( bpa_get_page == 'bookingpress_package'){
				vm.openNeedHelper('packages', 'packages', 'Packages');
                vm.bpa_fab_floating_btn = 0;
			}
            else if( bpa_get_page == 'bookingpress_package_order'){
				vm.openNeedHelper('packages', 'packages', 'Package Order');
                vm.bpa_fab_floating_btn = 0;
			}
        <?php
        }

        /**
         * bookingpress_is_multilanguage_active
         *
         * @return void
         */
        function bookingpress_is_multilanguage_active(){
            if( !function_exists('is_plugin_active') ){
                include ABSPATH . '/wp-admin/includes/plugin.php';
            }
            $plugin_slug = 'bookingpress-multilanguage/bookingpress-multilanguage.php';
            return is_plugin_active( $plugin_slug );
        }

		/**
		 * Function for modify appointment listing details
		 *
		 * @param  mixed $bookingpress_appointment_data
		 * @return void
		*/
		function bookingpress_appointment_add_view_field_func($bookingpress_appointment_data, $get_appointment){
            global $BookingPress;

            $bookingpress_appointment_data['bookingpress_applied_package_data'] = '';
            if(isset($get_appointment['bookingpress_package_discount_amount'])){
                
                $bookingpress_applied_package_data_arr = array();
                $bookingpress_applied_package_data = (isset($get_appointment['bookingpress_applied_package_data']))?$get_appointment['bookingpress_applied_package_data']:'';
                if(!empty($bookingpress_applied_package_data)){
                    $bookingpress_applied_package_data_arr = json_decode($bookingpress_applied_package_data,true); 
                }
                if(is_array($bookingpress_applied_package_data_arr) && !empty($bookingpress_applied_package_data_arr)){
                    
                    $currency_name                       = $get_appointment['bookingpress_service_currency'];
                    $currency_symbol                     = $BookingPress->bookingpress_get_currency_symbol($currency_name);
                    $bookingpress_appointment_data['bookingpress_package_discount_amount'] = $get_appointment['bookingpress_package_discount_amount'];
                    $bookingpress_appointment_data['bookingpress_package_discount_amount_with_currency'] = $BookingPress->bookingpress_price_formatter_with_currency_symbol($get_appointment['bookingpress_package_discount_amount'], $currency_symbol);    
                    $bookingpress_appointment_data['bookingpress_applied_package_data'] = $bookingpress_applied_package_data_arr;

                }
            }
            return $bookingpress_appointment_data;
        }        
        
        /**
         * Function for modified appointment subtotal amount
         *
         * @param  mixed $bookingpress_tmp_subtotal_amount
         * @param  mixed $bookingpress_appointment_details
         * @param  mixed $bookingpress_selected_currency
         * @return void
         */
        function bookingpress_modify_outside_sub_total_amount_appointment_details_func( $bookingpress_tmp_subtotal_amount, $bookingpress_appointment_details, $bookingpress_selected_currency ){
            
            $bookingpress_package_discount_amount = (isset($bookingpress_appointment_details['bookingpress_package_discount_amount']))?floatval($bookingpress_appointment_details['bookingpress_package_discount_amount']):0;            
            $bookingpress_tmp_subtotal_amount = $bookingpress_tmp_subtotal_amount - $bookingpress_package_discount_amount;           

            return $bookingpress_tmp_subtotal_amount;

        }

        /**
         * Function for modified calculate total payable amount
         *
         * @param  mixed $retrun_calculate_data
         * @param  mixed $bookingpress_payment_id
         * @param  mixed $bookingpress_selected_currency
         * @return void
         */
        function bookingpress_modify_outside_total_amount_func( $payment_log_details, $bookingpress_payment_id, $bookingpress_selected_currency ){

            global $tbl_bookingpress_appointment_bookings, $wpdb, $BookingPress,$tbl_bookingpress_payment_logs;
            
            $bookingpress_total_package_discount = 0;
            if(!empty($payment_log_details)){

                $bookingpess_retrun_final_amount = (isset($payment_log_details['total_amount']))?$payment_log_details['total_amount']:'';
                $bookingpress_appointment_details = (isset($payment_log_details['appointment_details']))?$payment_log_details['appointment_details']:'';
                if(!empty($bookingpress_appointment_details) && is_array($bookingpress_appointment_details)){                        
                    foreach($bookingpress_appointment_details as $key=>$booking_app_det){
                        
                        $currency_name      = $booking_app_det['bookingpress_service_currency'];
                        $currency_symbol    = $BookingPress->bookingpress_get_currency_symbol($currency_name);

                        $bookingpress_applied_package_data = $booking_app_det['bookingpress_applied_package_data'];
                        if(!empty($bookingpress_applied_package_data)){
                            $bookingpress_applied_package_data_arr = json_decode($bookingpress_applied_package_data,true);
                            if(!empty($bookingpress_applied_package_data_arr)){                                
                                $bookingpress_appointment_details[$key]['bookingpress_package_discount_amount_with_currency'] = $BookingPress->bookingpress_price_formatter_with_currency_symbol($booking_app_det['bookingpress_package_discount_amount'], $currency_symbol);
                            }
                            $bookingpress_appointment_details[$key]['bookingpress_applied_package_data_arr'] = $bookingpress_applied_package_data_arr;
                        }
                    }
                    $payment_log_details['appointment_details'] = $bookingpress_appointment_details;
                }                

               



            }
            return $payment_log_details;

        }

        /**
         * bookingpress_modify_download_debug_log_query_package_func
         *
         * @param  mixed $bookingpress_debug_log_query
         * @param  mixed $bookingpress_view_log_selector
         * @param  mixed $bookingpress_posted_data
         * @return void
         */
        function bookingpress_modify_download_debug_log_query_package_func( $bookingpress_debug_log_query, $bookingpress_view_log_selector, $bookingpress_posted_data){
            global $wpdb, $BookingPress, $tbl_bookingpress_other_debug_logs;

			$bookingpress_debug_payment_log_where_cond = '';
			$bookingpress_selected_download_duration   = ! empty( $bookingpress_posted_data['bookingpress_selected_download_duration'] ) ? sanitize_text_field( $bookingpress_posted_data['bookingpress_selected_download_duration'] ) : 'all';
            
            if ( ! empty( $bookingpress_posted_data['bookingpress_selected_download_custom_duration'] ) && $bookingpress_selected_download_duration == 'custom' ) {
                $bookingpress_start_date                   = date( 'Y-m-d 00:00:00', strtotime( sanitize_text_field( $bookingpress_posted_data['bookingpress_selected_download_custom_duration'][0] ) ) );
				$bookingpress_end_date                     = date( 'Y-m-d 23:59:59', strtotime( sanitize_text_field( $bookingpress_posted_data['bookingpress_selected_download_custom_duration'][1] ) ) );

                if(!empty($bookingpress_view_log_selector) && ($bookingpress_view_log_selector == 'package_order_debug_logs')) {
					$bookingpress_debug_payment_log_where_cond = " AND (bookingpress_other_log_added_date >= '" . $bookingpress_start_date . "' AND bookingpress_other_log_added_date <= '" . $bookingpress_end_date . "')";
				}
            }
            elseif ( ! empty( $bookingpress_selected_download_duration ) && $bookingpress_selected_download_duration != 'custom' && $bookingpress_selected_download_duration != 'all') {
                if(!empty($bookingpress_view_log_selector) && ($bookingpress_view_log_selector == 'package_order_debug_logs')) {
                    $bookingpress_last_selected_days           = date( 'Y-m-d', strtotime( '-' . $bookingpress_selected_download_duration . ' days' ) );
					$bookingpress_debug_payment_log_where_cond = " AND (bookingpress_other_log_added_date >= '" . $bookingpress_last_selected_days . "')";
                }
            }
            if ( $bookingpress_view_log_selector == 'package_order_debug_logs' ) {
				$bookingpress_debug_log_query = 'SELECT * FROM `' . $tbl_bookingpress_other_debug_logs . "` WHERE `bookingpress_other_log_type` = 'package_order_debug_logs'" . $bookingpress_debug_payment_log_where_cond . ' ORDER BY bookingpress_other_log_id DESC';
			}
            return $bookingpress_debug_log_query;
        }
        
        /**
         * bookingpress_clear_debug_payment_log_package_func
         *
         * @param  mixed $posted_data
         * @return void
         */
        function bookingpress_clear_debug_payment_log_package_func($posted_data){
            global $wpdb, $BookingPress, $tbl_bookingpress_other_debug_logs;
            if ( ! empty( $posted_data ) ) {
                $bookingpress_view_log_selector = ! empty( $posted_data['bookingpress_debug_log_selector'] ) ? sanitize_text_field( $posted_data['bookingpress_debug_log_selector'] ) : '';
                if ( $bookingpress_view_log_selector == 'package_order_debug_logs' ) {
                    $wpdb->delete( $tbl_bookingpress_other_debug_logs, array( 'bookingpress_other_log_type' => $bookingpress_view_log_selector ) );
                }
            }
        }
        
        /**
         * bookingpress_add_setting_dynamic_data_fields_package_func
         *
         * @param  mixed $bookingpress_dynamic_setting_data_fields
         * @return void
         */
        function bookingpress_add_setting_dynamic_data_fields_package_func($bookingpress_dynamic_setting_data_fields){
            $bookingpress_dynamic_setting_data_fields['debug_log_setting_form']['package_order_debug_logs'] = false;    
            return $bookingpress_dynamic_setting_data_fields;
        }
        
        /**
         * bookingpress_modify_debug_log_data_outside_package_func
         *
         * @param  mixed $debug_log_data
         * @param  mixed $posted_data
         * @return void
         */
        function bookingpress_modify_debug_log_data_outside_package_func($debug_log_data, $posted_data){
            global $wpdb, $tbl_bookingpress_other_debug_logs;
            $bookingpress_debug_log_selector = !empty($posted_data['bookingpress_debug_log_selector']) ? sanitize_text_field($posted_data['bookingpress_debug_log_selector']) : '';
            $perpage     = isset($_POST['perpage']) ? intval($_POST['perpage']) : 20; //phpcs:ignore
            $currentpage = isset($_POST['currentpage']) ? intval($_POST['currentpage']) : 1; //phpcs:ignore
            $offset      = ( ! empty($currentpage) && $currentpage > 1 ) ? ( ( $currentpage - 1 ) * $perpage ) : 0;
            if ( ! empty( $bookingpress_debug_log_selector ) && $bookingpress_debug_log_selector == 'package_order_debug_logs' ) {
                $bookingpress_total_package_order_debug_logs_data = $wpdb->get_results( $wpdb->prepare( "SELECT bookingpress_other_log_id FROM {$tbl_bookingpress_other_debug_logs} WHERE bookingpress_other_log_type = %s ORDER BY bookingpress_other_log_id DESC", 'package_order_debug_logs' ), ARRAY_A ); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared --Reason: $tbl_bookingpress_other_debug_logs is a table name. false alarm
                $bookingpress_package_order_debug_logs_data = $wpdb->get_results( $wpdb->prepare( "SELECT bookingpress_other_log_id,bookingpress_other_log_event,bookingpress_other_log_raw_data,bookingpress_other_log_added_date FROM {$tbl_bookingpress_other_debug_logs} WHERE bookingpress_other_log_type = %s ORDER BY bookingpress_other_log_id DESC LIMIT %d, %d", 'package_order_debug_logs',$offset , $perpage ), ARRAY_A ); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared --Reason: $tbl_bookingpress_other_debug_logs is a table name. false alarm
                $bookingpress_debug_log_data = array();
				$bookingpress_date_format    = get_option( 'date_format' );
                foreach ( $bookingpress_package_order_debug_logs_data as $bookingpress_debug_log_key => $bookingpress_debug_log_val ) {
					$bookingpress_debug_log_data[] = array(
						'payment_debug_log_id'         => $bookingpress_debug_log_val['bookingpress_other_log_id'],
						'payment_debug_log_name'       => $bookingpress_debug_log_val['bookingpress_other_log_event'],
						'payment_debug_log_data'       => stripslashes_deep($bookingpress_debug_log_val['bookingpress_other_log_raw_data']),
						'payment_debug_log_added_date' => date( $bookingpress_date_format, strtotime( $bookingpress_debug_log_val['bookingpress_other_log_added_date'] ) ),
					);
				}

				$debug_log_data['items'] = $bookingpress_debug_log_data;
				$debug_log_data['total'] = count($bookingpress_total_package_order_debug_logs_data);
			}
            return $debug_log_data;
        }
        
        /**
         * bookingpress_add_debug_log_outside_package_func
         *
         * @return void
         */
        function bookingpress_add_debug_log_outside_package_func(){
            global $bookingpress_common_date_format;
            ?>
            <div class="bpa-gs__cb--item">
                <div class="bpa-gs__cb--item-heading">
                    <h4 class="bpa-sec--sub-heading"><?php esc_html_e( 'Package Debug Logs', 'bookingpress-package' ); ?></h4>
                </div>
                <el-row type="flex" class="bpa-gs--tabs-pb__cb-item-row">
                    <el-col :xs="24" :sm="24" :md="24" :lg="24" :xl="24">
                        <el-row type="flex" class="bpa-debug-item__body">
                            <el-col :xs="12" :sm="12" :md="12" :lg="16" :xl="16" class="bpa-gs__cb-item-left">
                                <h4> <?php esc_html_e( 'Package Order Logs', 'bookingpress-package' ); ?></h4>
                            </el-col>
                            <el-col :xs="12" :sm="12" :md="12" :lg="8" :xl="8" class="bpa-gs__cb-item-right">
                                <el-form-item>
                                    <el-switch class="bpa-swtich-control" v-model="debug_log_setting_form.package_order_debug_logs"></el-switch>
                                </el-form-item>
                            </el-col>
                        </el-row>
                        <el-row>
                            <el-col :xs="24" :sm="24" :md="24" :lg="24" :xl="24">
                                <div class="bpa-debug-item__btns" v-if="debug_log_setting_form.package_order_debug_logs == true">
                                    <div class="bpa-di__btn-item">
                                        <el-button class="bpa-btn bpa-btn__small" @click="bookingpess_view_log('package_order_debug_logs', '', '<?php esc_html_e( 'Package Order Logs', 'bookingpress-package' ); ?>')" ><?php esc_html_e( 'View log', 'bookingpress-package' ); ?></el-button>
                                    </div>
                                    <div class="bpa-di__btn-item">
                                        <el-popover placement="bottom" width="450" trigger="click" >
                                            <div class="bpa-dialog-download"> 
                                                <el-row type="flex">
                                                    <el-col :xs="24" :sm="24" :md="12" :lg="14" :xl="14" class="bpa-download-dropdown-label">			
                                                        <label for="start_time" class="el-form-item__label">
                                                            <span class="bpa-form-label"><?php esc_html_e( 'Select log duration to download', 'bookingpress-package' ); ?></span>
                                                        </label>			
                                                    </el-col>			
                                                    <el-col :xs="24" :sm="24" :md="12" :lg="10" :xl="10">											
                                                        <el-select :popper-append-to-body="proper_body_class" v-model="select_download_log" class="bpa-form-control bpa-form-control__left-icon">	
                                                            <el-option v-for="download_option in log_download_default_option" :key="download_option.key" :label="download_option.key" :value="download_option.value"></el-option>
                                                        </el-select>										
                                                    </el-col>		
                                                </el-row>										
                                                <el-row v-if="select_download_log == 'custom'" class="bpa-download-datepicker">
                                                    <el-col :xs="24" :sm="24" :md="24" :lg="24" :xl="24" >											
                                                        <el-date-picker popper-class="bpa-el-select--is-with-modal" class="bpa-form-control--date-range-picker" format="<?php echo esc_html( $bookingpress_common_date_format ); ?>" v-model="download_log_daterange" type="daterange" start-placeholder="<?php esc_html_e('Start date', 'bookingpress-package'); ?>" end-placeholder="<?php esc_html_e('End date', 'bookingpress-package'); ?>" :clearable="false" value-format="yyyy-MM-dd" :picker-options="filter_pickerOptions"> </el-date-picker>
                                                    </el-col>
                                                </el-row>
                                                <el-row>													
                                                    <el-col :xs="24" :sm="24" :md="24" :lg="24" :xl="24" >										
                                                        <el-button class="bpa-btn bpa-btn--primary" :class="is_display_download_save_loader == '1' ? 'bpa-btn--is-loader' : ''" @click="bookingpress_download_log('package_order_debug_logs', select_download_log, download_log_daterange)" :disabled="is_disabled" >
                                                            <span class="bpa-btn__label"><?php esc_html_e( 'Download', 'bookingpress-package' ); ?></span>
                                                            <div class="bpa-btn--loader__circles">
                                                                <div></div>
                                                                <div></div>
                                                                <div></div>
                                                            </div>
                                                        </el-button>	
                                                    </el-col>
                                                </el-row>	
                                            </div>
                                            <el-button class="bpa-btn bpa-btn__small" slot="reference" ><?php esc_html_e( 'Download Log', 'bookingpress-package' ); ?></el-button>
                                        </el-popover>	
                                    </div>
                                    <div class="bpa-di__btn-item">
                                        <el-popconfirm 
                                            confirm-button-text='<?php esc_html_e( 'Delete', 'bookingpress-package' ); ?>' 
                                            cancel-button-text='<?php esc_html_e( 'Cancel', 'bookingpress-package' ); ?>' 
                                            icon="false" 
                                            title="<?php esc_html_e( 'Are you sure you want to clear debug logs?', 'bookingpress-package' ); ?>"
                                            @confirm="bookingpess_clear_bebug_log('package_order_debug_logs')"
                                            confirm-button-type="bpa-btn bpa-btn__small bpa-btn--danger" 
                                            cancel-button-type="bpa-btn bpa-btn__small" >
                                            <el-button class="bpa-btn bpa-btn__small" slot="reference"><?php esc_html_e( 'Clear Log', 'bookingpress-package' ); ?></el-button>
                                        </el-popconfirm>
                                    </div>
                                </div>
                            </el-col>
                        </el-row>
                    </el-col>
                </el-row>
				</div>
            <?php
        }        
        
        /**
         * Function for find package booked appointments
         *
         * @param  mixed $package_id
         * @param  mixed $service_id
         * @param  mixed $total_appointment
         * @return void
         */
        function bookingpress_total_package_booked_appointment($bookingpress_package_no = 0,$package_id=0,$service_id = 0){            
            $total_booked = 0;            
            return $total_booked;
        }

        /**
         * Function for check package appointment is booked or not
         *
         * @param  mixed $package_id
         * @return void
         */
        function bookingpress_check_package_appointment_book($package_id,$bookingpress_package_no = ''){
            $package_id = intval($package_id);
            global $tbl_bookingpress_appointment_bookings,$tbl_bookingpress_package_bookings, $wpdb;
            $has_booked_package_appointments = 0;
            if(empty($bookingpress_package_no)){
                $bookingpress_total_package_order = $wpdb->get_results($wpdb->prepare("SELECT bookingpress_package_no FROM {$tbl_bookingpress_package_bookings} WHERE bookingpress_package_id = %d", $package_id),ARRAY_A);  // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- Reason: $tbl_bookingpress_package_bookings is table name defined globally. False Positive alarm
            }else{
                $bookingpress_total_package_order = $wpdb->get_results($wpdb->prepare("SELECT bookingpress_package_no FROM {$tbl_bookingpress_package_bookings} WHERE bookingpress_package_id = %d AND bookingpress_package_no = %d", $package_id,$bookingpress_package_no),ARRAY_A); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- Reason: $tbl_bookingpress_package_bookings is table name defined globally. False Positive alarm
            }            
            if(!empty($bookingpress_total_package_order)){
                $total_package_no_arr = array();
                foreach($bookingpress_total_package_order as $total_pack_no){
                    $total_package_no_arr[] = $total_pack_no['bookingpress_package_no'];
                }
                if(!empty($total_package_no_arr)){
                    $total_package_no_data = implode(",",$total_package_no_arr);
                    if(!empty($total_package_no_data)){            
                        $bookingpress_total_package_serv_booked = $wpdb->get_var("SELECT SUM(bookingpress_selected_extra_members) as total FROM {$tbl_bookingpress_appointment_bookings} WHERE  bookingpress_package_id IN (".$total_package_no_data.")"); // phpcs:ignore
                        if($bookingpress_total_package_serv_booked > 0){
                            $has_booked_package_appointments = 1;
                        }                    
                    }
                }
            }
            return $has_booked_package_appointments;
        }

        
        /**
         * Function for add package status
         *
         * @return void
         */
        function get_package_order_status(){
            $package_order_status = array(
                array(
                    'value' => '1',
                    'text'  => esc_html__('Paid', 'bookingpress-package'),
                ),
                array(
                    'value' => '2',
                    'text'  => esc_html__('Pending', 'bookingpress-package'),
                ),                
                array(
                    'value' => '3', 
                    'text' => esc_html__('Refunded', 'bookingpress-package')
                ),
                array(
                    'value' => '4',
                    'text'  => esc_html__('Partially Paid', 'bookingpress-package'),
                ),
                array(
                    'value' => '5',
                    'text'  => esc_html__('Refunded ( partial )', 'bookingpress-package'),
                )
            );
            return $package_order_status;
        }

        /**
         * Get package services data
         *
         * @param  mixed $package_id
         * @return void
        */
        function get_package_services_by_package_id( $package_id ){
            global $wpdb, $tbl_bookingpress_packages,$tbl_bookingpress_package_services,$tbl_bookingpress_services,$bookingpress_package,$BookingPressPro;
            $pack_services_data = array();
            $bookingpress_package_services = $wpdb->get_results($wpdb->prepare("SELECT pack_serv.bookingpress_package_id, pack_serv.bookingpress_service_id, pack_serv.bookingpress_service_price, pack_serv.bookingpress_no_of_appointments , service.bookingpress_service_name FROM {$tbl_bookingpress_package_services} AS pack_serv LEFT JOIN {$tbl_bookingpress_services} AS service ON pack_serv.bookingpress_service_id = service.bookingpress_service_id  WHERE pack_serv.bookingpress_package_id = %d", $package_id), ARRAY_A); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared --Reason: $tbl_bookingpress_package_services is table name.
            if(!empty($bookingpress_package_services)){                
                foreach($bookingpress_package_services as $pack_service){


                    if($bookingpress_package->is_multi_language_addon_active()){
                        if(method_exists( $BookingPressPro, 'bookingpress_pro_front_language_translation_func') ) {
                            $bookingpress_service_name = $pack_service['bookingpress_service_name'];                                                               
                            $bookingpress_service_id = $pack_service['bookingpress_service_id'];
                            $pack_service['bookingpress_service_name'] = $BookingPressPro->bookingpress_pro_front_language_translation_func($pack_service['bookingpress_service_name'],'service','bookingpress_service_name',$bookingpress_service_id);
                        }
                    }                    

                    $single_serv_data = array(
                        'bookingpress_package_id' => $pack_service['bookingpress_package_id'],
                        'bookingpress_service_id' => $pack_service['bookingpress_service_id'],
                        'bookingpress_service_name' => $pack_service['bookingpress_service_name'],
                        'bookingpress_service_price' => $pack_service['bookingpress_service_price'],
                        'bookingpress_no_of_appointments' => $pack_service['bookingpress_no_of_appointments'],
                        'bookingpress_no_of_booked_appointments' => 0,
                    );
                    $pack_services_data[] = $single_serv_data;
                }
            }
            return $pack_services_data;
        }


        /**
         * Get package details from specific ID
         *
         * @param  mixed $package_id
         * @return void
         */
        function get_package_by_id( $package_id ){
            global $wpdb, $tbl_bookingpress_packages,$tbl_bookingpress_package_services,$bookingpress_package,$BookingPressPro;
            $package_data = array();
            if (! empty($package_id) ) {                
                $package_data = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$tbl_bookingpress_packages} WHERE bookingpress_package_id = %d", $package_id), ARRAY_A); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- Reason: $tbl_bookingpress_packages is table name defined globally. False Positive alarm
                if(isset($package_data['bookingpress_package_name'])){
                    if($bookingpress_package->is_multi_language_addon_active()){
                        if(method_exists( $BookingPressPro, 'bookingpress_pro_front_language_translation_func') ) {
                            $bookingpress_package_id = (isset($package_data['bookingpress_package_id']))?$package_data['bookingpress_package_id']:'';
                            if(!empty($bookingpress_package_id)){
                                $package_data['bookingpress_package_name'] = $BookingPressPro->bookingpress_pro_front_language_translation_func($package_data['bookingpress_package_name'],'package','bookingpress_package_name',$bookingpress_package_id);
                            }                                                
                        }
                    }
                }                
            }
            return $package_data;
        }        
        
        /**
         * Function for create duplicate package
         *
         * @return void
         */
        function bookingpress_duplicate_package_func(){

            global $wpdb, $tbl_bookingpress_services, $tbl_bookingpress_packages, $tbl_bookingpress_package_services, $tbl_bookingpress_package_images, $BookingPress;

            $response              = array();
            $bpa_check_authorization = $this->bpa_check_authentication( 'save_package_details', true, 'bpa_wp_nonce' );            
            if( preg_match( '/error/', $bpa_check_authorization ) ){
                $bpa_auth_error = explode( '^|^', $bpa_check_authorization );
                $bpa_error_msg = !empty( $bpa_auth_error[1] ) ? $bpa_auth_error[1] : esc_html__( 'Sorry. Something went wrong while processing the request', 'bookingpress-package');

                $response['variant'] = 'error';
                $response['title'] = esc_html__( 'Error', 'bookingpress-package');
                $response['msg'] = $bpa_error_msg;

                wp_send_json( $response );
                die;
            }
            $response['variant']               = 'error';
            $response['title']                 = esc_html__('Error', 'bookingpress-package');
            $response['duplicate_pack_id']     = '';
            $response['msg']                   = esc_html__('Something went wrong..', 'bookingpress-package');
            $bookingpress_duplicate_package_id = ! empty($_REQUEST['package_id']) ? intval($_REQUEST['package_id']) : 0;            

            if (!empty($bookingpress_duplicate_package_id)) {
               
                $bookingpress_duplicate_package = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$tbl_bookingpress_packages} WHERE bookingpress_package_id = %d", $bookingpress_duplicate_package_id ), ARRAY_A ); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- Reason: $tbl_bookingpress_packages is table name defined globally. False Positive alarm
                
                /* Find Max Position Of Services */
                $bookingpress_find_last_pos = $wpdb->get_row("SELECT MAX(bookingpress_package_position) as bookingpress_last_pos FROM {$tbl_bookingpress_packages}", ARRAY_A);// phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- Reason: $tbl_bookingpress_packages is table name defined globally. False Positive alarm
                $bookingpress_new_pos       = $bookingpress_find_last_pos['bookingpress_last_pos'] + 1;

                $bookingpress_duplicate_package_data = $bookingpress_duplicate_package;
                unset($bookingpress_duplicate_package_data['bookingpress_package_id']);

                $bookingpress_duplicate_package_data['bookingpress_package_name']        = __('Copy', 'bookingpress-package') . ' ' . $bookingpress_duplicate_package_data['bookingpress_package_name'];
                $bookingpress_duplicate_package_data['bookingpress_package_position']    = $bookingpress_new_pos;
                $bookingpress_duplicate_package_data['bookingpress_package_created_date'] = current_time('mysql');

                $wpdb->insert($tbl_bookingpress_packages, $bookingpress_duplicate_package_data);
                $bookingpress_inserted_package_id = $wpdb->insert_id;                
                
                /* Add Package Services */
                $bookingpress_package_services = $wpdb->get_results($wpdb->prepare("SELECT * FROM {$tbl_bookingpress_package_services} WHERE bookingpress_package_id = %d", $bookingpress_duplicate_package_id), ARRAY_A); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared --Reason: $tbl_bookingpress_package_services is table name.
                if(!empty($bookingpress_package_services)){
                    foreach($bookingpress_package_services as $package_service_key => $package_service_val){
                        $package_service_val['bookingpress_package_id'] = $bookingpress_inserted_package_id;
                        unset($package_service_val['bookingpress_package_service_id']);
                        unset($package_service_val['bookingpress_package_service_created_date']);
                        $wpdb->insert($tbl_bookingpress_package_services, $package_service_val);
                    }
                }

                /* Add Package Images */
                $bookingpress_package_images = $wpdb->get_results($wpdb->prepare("SELECT * FROM {$tbl_bookingpress_package_images} WHERE bookingpress_package_id = %d", $bookingpress_duplicate_package_id), ARRAY_A); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared --Reason: $tbl_bookingpress_package_images is table name.
                if(!empty($bookingpress_package_images)){
                    foreach($bookingpress_package_images as $package_image_key => $package_image_val){
                        $package_image_val['bookingpress_package_id'] = $bookingpress_inserted_package_id;
                        unset($package_image_val['bookingpress_package_img_id']);
                        unset($package_image_val['bookingpress_package_img_created_date']);
                        $wpdb->insert($tbl_bookingpress_package_images, $package_image_val);
                    }
                }
                
                do_action('bookingpress_duplicate_package_more_details', $bookingpress_inserted_package_id, $bookingpress_duplicate_package_id);

                $response['variant']           = 'success';
                $response['title']             = esc_html__('Success', 'bookingpress-package');
                $response['msg']               = esc_html__('Package duplicate successfully', 'bookingpress-package');                
                $response['duplicate_pack_id'] = $bookingpress_inserted_package_id;

            }else{
                $response['variant'] = 'error';
                $response['title']   = esc_html__('Error', 'bookingpress-package');
                $response['msg']     = esc_html__('No package found...', 'bookingpress-package');
            }

            echo wp_json_encode($response);
            exit();
        }
                
        /**
         * Function for change package status for avaliable or not
         *
         * @return void
         */
        function bookingpress_change_package_status_func(){

            global $wpdb, $BookingPress, $bookingpress_services, $tbl_bookingpress_packages;
			$response = array();
			$bpa_check_authorization = $this->bpa_check_authentication( 'change_package_status', true, 'bpa_wp_nonce' );           
			if( preg_match( '/error/', $bpa_check_authorization ) ){
				$bpa_auth_error = explode( '^|^', $bpa_check_authorization );
				$bpa_error_msg = !empty( $bpa_auth_error[1] ) ? $bpa_auth_error[1] : esc_html__( 'Sorry. Something went wrong while processing the request', 'bookingpress-package');
				$response['variant'] = 'error';
				$response['title'] = esc_html__( 'Error', 'bookingpress-package');
				$response['msg'] = $bpa_error_msg;
				wp_send_json( $response );
				die;
			}
			$bookingpress_package_id         = isset( $_POST['package_id'] ) ? intval( $_POST['package_id'] ) : 0; // phpcs:ignore
			$bookingpress_package_new_status = isset( $_POST['package_new_status'] ) ? intval( $_POST['package_new_status'] ) : 1; // phpcs:ignore

            if($bookingpress_package_id){
                $wpdb->update($tbl_bookingpress_packages, array('bookingpress_package_status' => $bookingpress_package_new_status), array( 'bookingpress_package_id' => $bookingpress_package_id));
				$response['variant'] = 'success';
				$response['title']   = esc_html__( 'Success', 'bookingpress-package' );
				$response['msg']     = esc_html__( 'Package status changed successfully', 'bookingpress-package' );                
            }
            wp_send_json( $response );
            die;

        }

        /**
         * Function for removed package file
         *
         * @return void
         */
        function bookingpress_remove_package_file_func(){

            global $wpdb;
            $response = array();
            $bpa_check_authorization = $this->bpa_check_authentication( 'remove_package_avatar', true, 'bpa_wp_nonce' );            
            if( preg_match( '/error/', $bpa_check_authorization ) ){

                $bpa_auth_error = explode( '^|^', $bpa_check_authorization );
                $bpa_error_msg = !empty( $bpa_auth_error[1] ) ? $bpa_auth_error[1] : esc_html__( 'Sorry. Something went wrong while processing the request', 'bookingpress-package');
                $response['variant'] = 'error';
                $response['title'] = esc_html__( 'Error', 'bookingpress-package');
                $response['msg'] = $bpa_error_msg;

                wp_send_json( $response );
                die;
                
            }
            if (! empty($_POST) && ! empty($_POST['upload_file_url']) ) { // phpcs:ignore WordPress.Security.NonceVerification
                $bookingpress_uploaded_avatar_url = esc_url_raw($_POST['upload_file_url']); // phpcs:ignore
                $bookingpress_file_name_arr       = explode('/', $bookingpress_uploaded_avatar_url);
                $bookingpress_file_name           = $bookingpress_file_name_arr[ count($bookingpress_file_name_arr) - 1 ];
                if( file_exists( BOOKINGPRESS_TMP_IMAGES_DIR . '/' . $bookingpress_file_name ) ){
                    @unlink(BOOKINGPRESS_TMP_IMAGES_DIR . '/' . $bookingpress_file_name);
                }
            }
            die;

        }        

        function bookingpress_upload_package_image(){
            global $BookingPress;

            $return_data = array(
                'error'            => 0,
                'msg'              => '',
                'upload_url'       => '',
                'upload_file_name' => '',
            );

            $bpa_check_authorization = $this->bpa_check_authentication( 'upload_package_avatar', true, 'bookingpress_upload_package' );
            
            if( preg_match( '/error/', $bpa_check_authorization ) ){
                $bpa_auth_error = explode( '^|^', $bpa_check_authorization );
                $bpa_error_msg = !empty( $bpa_auth_error[1] ) ? $bpa_auth_error[1] : esc_html__( 'Sorry. Something went wrong while processing the request', 'bookingpress-package');
                $response = array();
                $response['variant'] = 'error';
                $response['error'] = 1;
                $response['title'] = esc_html__( 'Error', 'bookingpress-package');
                $response['msg'] = $bpa_error_msg;

                wp_send_json( $response );
                die;
            }

            $bookingpress_fileupload_obj = new bookingpress_fileupload_class($_FILES['file']); // phpcs:ignore

            if (! $bookingpress_fileupload_obj ) {
                $return_data['error'] = 1;
                $return_data['msg']   = $bookingpress_fileupload_obj->error_message;
            }

            $bookingpress_fileupload_obj->check_cap          = true;
            $bookingpress_fileupload_obj->check_nonce        = true;
            $bookingpress_fileupload_obj->nonce_data         = isset($_REQUEST['_wpnonce']) ? sanitize_text_field($_REQUEST['_wpnonce']) : '';
            $bookingpress_fileupload_obj->nonce_action       = isset($_REQUEST['action']) ? sanitize_text_field($_REQUEST['action']) : '';
            $bookingpress_fileupload_obj->check_only_image   = true;
            $bookingpress_fileupload_obj->check_specific_ext = false;
            $bookingpress_fileupload_obj->allowed_ext        = array();

            $file_name                = current_time('timestamp') . '_' . isset($_FILES['file']['name']) ? sanitize_file_name($_FILES['file']['name']) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Missing --Reason Nonce already verified from the caller function.
            $upload_dir               = BOOKINGPRESS_TMP_IMAGES_DIR . '/';
            $upload_url               = BOOKINGPRESS_TMP_IMAGES_URL . '/';
            $bookingpress_destination = $upload_dir . $file_name;

            $upload_file = $bookingpress_fileupload_obj->bookingpress_process_upload($bookingpress_destination);
            if ($upload_file == false ) {
                $return_data['error'] = 1;
                $return_data['upload_error'] = $upload_file;
                $return_data['msg']   = ! empty($bookingpress_fileupload_obj->error_message) ? $bookingpress_fileupload_obj->error_message : esc_html__('Something went wrong while updating the file', 'bookingpress-package');
            } else {
                $return_data['error']            = 0;
                $return_data['msg']              = '';
                $return_data['upload_url']       = $upload_url . $file_name;
                $return_data['upload_file_name'] = isset($_FILES['file']['name']) ? sanitize_file_name($_FILES['file']['name']) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Missing --Reason Nonce already verified from the caller function.
            }

            echo wp_json_encode($return_data);
            exit();
        }

        function bookingpress_add_package_form_customize_css($bookingpress_customize_css_content,$bookingpress_custom_data_arr){

            $content_color                     = $bookingpress_custom_data_arr['booking_form']['content_color'];
            $border_color                      = $bookingpress_custom_data_arr['booking_form']['border_color'];
            $shortcode_footer_background_color = $bookingpress_custom_data_arr['booking_form']['footer_background_color'];
            $sub_title_color                   = $bookingpress_custom_data_arr['booking_form']['sub_title_color'];
            $title_label_color                 = $bookingpress_custom_data_arr['booking_form']['label_title_color'];
            $title_font_family                 = $bookingpress_custom_data_arr['booking_form']['title_font_family'];
			$title_font_family          	   = $title_font_family == 'Inherit Fonts' ? 'inherit' : $title_font_family;

            $bookingpress_customize_css_content .= '';
            
            return $bookingpress_customize_css_content;
        }
        
        /**
         * Function for update package position
         *
         * @return void
         */
        function bookingpress_update_package_position(){
            global $wpdb, $BookingPress, $tbl_bookingpress_packages;
            $response = array();

            $bpa_check_authorization = $this->bpa_check_authentication( 'manage_package_position', true, 'bpa_wp_nonce' );
            
            if( preg_match( '/error/', $bpa_check_authorization ) ){
                $bpa_auth_error = explode( '^|^', $bpa_check_authorization );
                $bpa_error_msg = !empty( $bpa_auth_error[1] ) ? $bpa_auth_error[1] : esc_html__( 'Sorry. Something went wrong while processing the request', 'bookingpress-package');

                $response['variant'] = 'error';
                $response['title'] = esc_html__( 'Error', 'bookingpress-package');
                $response['msg'] = $bpa_error_msg;

                wp_send_json( $response );
                die;
            }

            $old_position        = isset($_POST['old_position']) ? intval($_POST['old_position']) : $old_position; // phpcs:ignore WordPress.Security.NonceVerification
            $new_position        = isset($_POST['new_position']) ? intval($_POST['new_position']) : $new_position; // phpcs:ignore WordPress.Security.NonceVerification
            $response['variant'] = 'danger';
            $response['title']   = esc_html__('Error', 'bookingpress-package');
            $response['msg']     = esc_html__('Something went wrong..', 'bookingpress-package');
            if (isset($old_position) && isset($new_position) ) {


                $fields    = $wpdb->get_results( 'SELECT * FROM ' . $tbl_bookingpress_packages . ' order by bookingpress_package_position ASC', ARRAY_A); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared -- Reason: $tbl_bookingpress_form_fields is table name defined globally. False Positive alarm
                $i = 0;
                foreach ( $fields as $field ) {
                    $args = array('bookingpress_package_position' => $i);
                    $wpdb->update($tbl_bookingpress_packages, $args, array( 'bookingpress_package_id' => $field['bookingpress_package_id'] ));
                   $i++;  
                }
                if ($new_position > $old_position ) {                    
                    $services  = $wpdb->get_results( $wpdb->prepare( 'SELECT bookingpress_package_position, bookingpress_package_id FROM ' . $tbl_bookingpress_packages . ' WHERE bookingpress_package_position BETWEEN %d AND %d order by bookingpress_package_position ASC', $old_position, $new_position ), ARRAY_A); //phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared --Reason: $tbl_bookingpress_packages is a table name                   
                    foreach ( $services as $service ) {
                        $position = $service['bookingpress_package_position'] - 1;
                        $position = ( $service['bookingpress_package_position'] == $old_position ) ? $new_position : $position;
                        $args     = array(
                            'bookingpress_package_position' => $position,
                        );
                        $wpdb->update($tbl_bookingpress_packages, $args, array( 'bookingpress_package_id' => $service['bookingpress_package_id'] ));
                    }
                } else {
                    $services = $wpdb->get_results( $wpdb->prepare( 'SELECT bookingpress_package_position, bookingpress_package_id FROM ' . $tbl_bookingpress_packages . ' WHERE bookingpress_package_position BETWEEN %d AND %d order by bookingpress_package_position ASC', $new_position, $old_position ), ARRAY_A);// phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared -- Reason: $tbl_bookingpress_packages is table name defined globally. False Positive alarm
                    foreach ( $services as $service ) {
                        $position = $service['bookingpress_package_position'] + 1;
                        $position = ( $service['bookingpress_package_position'] == $old_position ) ? $new_position : $position;
                        $args     = array(
                            'bookingpress_package_position' => $position,
                        );
                        $wpdb->update($tbl_bookingpress_packages, $args, array( 'bookingpress_package_id' => $service['bookingpress_package_id'] ));
                    }
                }
                
                $response['variant'] = 'success';
                $response['title']   = esc_html__('Success', 'bookingpress-package');
                $response['msg']     = esc_html__('Service position has been changed successfully.', 'bookingpress-package');
            }
            if (isset($_POST['action']) && sanitize_text_field($_POST['action']) == 'bookingpress_position_package' ) { // phpcs:ignore WordPress.Security.NonceVerification
                wp_send_json($response);
            }
            die;
        }
        
        /**
         * Function for add package menu
         *
         * @return void
         */
        function bookingpress_add_dynamic_menu_item_to_top_func(){
            global $bookingpress_slugs;
            $request_module = ( ! empty( $_REQUEST['page'] ) && ( $_REQUEST['page'] != 'bookingpress' ) ) ? str_replace( 'bookingpress_', '', sanitize_text_field( $_REQUEST['page'] ) ) : 'dashboard'; //// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized --Reason - $_REQUEST['action'] sanitized properly
            ?>
                <!--
                <li class="bpa-nav-item <?php echo ( 'package' == $request_module ) ? '__active' : ''; ?>">
					<?php //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped --Reason - URL is escaped properly ?>
					<a href="<?php echo add_query_arg( 'page',esc_html($bookingpress_slugs->bookingpress_package), esc_url( admin_url() . 'admin.php?page=bookingpress' ) );  // phpcs:ignore ?>" class="bpa-nav-link">
                        <div class="bpa-nav-link--icon">
                            <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M2.78814 6.65445L8.40973 5.01496C8.67151 4.93861 8.9484 4.93861 9.21018 5.01496L14.8318 6.65445L8.80996 8.66145L2.78814 6.65445ZM8.33338 10.8832V18L1.97595 15.6163C1.88531 15.5822 1.80721 15.5213 1.75205 15.4417C1.69689 15.3621 1.66729 15.2676 1.66718 15.1707V11.0938L6.7543 12.7904C6.85725 12.8246 6.96877 12.8226 7.07049 12.785C7.17222 12.7473 7.25808 12.6761 7.31394 12.5831L8.33338 10.8832ZM15.9527 11.0938V15.1707C15.9526 15.2676 15.923 15.3621 15.8679 15.4417C15.8127 15.5213 15.7346 15.5822 15.644 15.6163L9.28653 18V10.8832L10.306 12.5831C10.3618 12.6761 10.4477 12.7473 10.5494 12.785C10.6511 12.8226 10.7627 12.8246 10.8656 12.7904L15.9527 11.0938ZM8.09593 9.42749L6.69305 11.7659L0 9.53489L1.40288 7.19648L8.09593 9.42749ZM9.52398 9.42749L16.217 7.19648L17.6199 9.53489L10.9269 11.7659L9.52398 9.42749ZM8.18067 0.629283C8.18067 0.462386 8.24697 0.302326 8.36499 0.184313C8.483 0.0662992 8.64306 0 8.80996 0C8.97685 0 9.13691 0.0662992 9.25493 0.184313C9.37294 0.302326 9.43924 0.462386 9.43924 0.629283V2.72689C9.43924 2.89379 9.37294 3.05385 9.25493 3.17186C9.13691 3.28987 8.97685 3.35617 8.80996 3.35617C8.64306 3.35617 8.483 3.28987 8.36499 3.17186C8.24697 3.05385 8.18067 2.89379 8.18067 2.72689V0.629283ZM13.9952 3.83191C13.9247 3.98323 13.7969 4.10033 13.6401 4.15745C13.4832 4.21457 13.31 4.20703 13.1587 4.13648C13.0074 4.06594 12.8903 3.93818 12.8332 3.7813C12.7761 3.62442 12.7836 3.45128 12.8541 3.29996L13.741 1.39869C13.8139 1.2513 13.9416 1.1383 14.0967 1.08385C14.2519 1.0294 14.4221 1.03784 14.5711 1.10735C14.7201 1.17686 14.836 1.30193 14.894 1.45579C14.9519 1.60966 14.9474 1.7801 14.8813 1.93064L13.9952 3.83191ZM4.78926 3.29996C4.85537 3.4505 4.85993 3.62094 4.80197 3.7748C4.74401 3.92867 4.62813 4.05373 4.47913 4.12325C4.33013 4.19276 4.15984 4.20119 4.00469 4.14675C3.84955 4.0923 3.72188 3.97929 3.649 3.83191L2.76213 1.93064C2.69602 1.7801 2.69146 1.60966 2.74942 1.45579C2.80738 1.30193 2.92326 1.17686 3.07226 1.10735C3.22127 1.03784 3.39156 1.0294 3.5467 1.08385C3.70184 1.1383 3.82951 1.2513 3.90239 1.39869L4.78926 3.29996Z"/>
                            </svg>                                                    
                        </div>                        
                        <?php esc_html_e( 'Packages', 'bookingpress-package' ); ?>
                    </a>
                </li>
                -->

                <li class="bpa-nav-item <?php echo ( 'package' == $request_module || 'package_order' == $request_module ) ? '__active' : ''; ?>">
						<el-dropdown class="bpa-nav-item-dropdown" trigger="hover">                        
							<a href="#" class="bpa-nav-link">
								<div class="bpa-nav-link--icon bpp-backend-package-icon">
                                    <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M7.98832 9.995H2.14277C1.6796 9.995 1.30061 10.3739 1.30061 10.8371V17.1579C1.30061 17.6211 1.6796 18 2.14277 18H8.08661C8.28473 18 8.28473 17.7872 8.28473 17.7872V10.2812C8.28467 10.2812 8.28467 9.995 7.98832 9.995ZM15.849 9.995H10.0094C9.65375 9.995 9.707 10.3654 9.707 10.3654V17.794C9.707 17.794 9.70412 17.9998 9.91806 17.9998H15.8489C16.3121 17.9998 16.6911 17.6209 16.6911 17.1577V10.8371C16.6912 10.3739 16.3122 9.995 15.849 9.995ZM8.28467 5.02167C8.28467 5.02167 8.28467 4.73631 8.00268 4.73631H1.19177C0.7286 4.73631 0.349609 5.1153 0.349609 5.57841V8.20717C0.349609 8.67034 0.7286 9.04927 1.19177 9.04927H8.02575C8.28467 9.04927 8.28467 8.82471 8.28467 8.82471V5.02167ZM16.8 4.73631H9.96733C9.70713 4.73631 9.70713 4.98425 9.70713 4.98425V8.82992C9.70713 8.82992 9.70713 9.04927 10.0235 9.04927H16.8C17.2631 9.04927 17.6421 8.67034 17.6421 8.20717V5.57841C17.6421 5.1153 17.2631 4.73631 16.8 4.73631ZM5.4203 4.11325C5.03499 4.11325 4.68306 4.08269 4.37444 4.02238C3.59057 3.86924 3.05181 3.57118 2.72737 3.11126C2.43667 2.69907 2.3477 2.19093 2.46286 1.60088C2.6646 0.568517 3.35791 0 4.41494 0C4.63864 0 4.88431 0.0258305 5.14519 0.0768166C5.8088 0.20646 6.65759 0.586985 7.41576 1.0947C8.70207 1.95619 8.76569 2.49175 8.70164 2.8197C8.60746 3.30158 8.15705 3.64591 7.32464 3.87243C6.76226 4.02545 6.06815 4.11325 5.4203 4.11325ZM4.415 1.34975C4.00564 1.34975 3.86495 1.46412 3.78771 1.85967C3.72451 2.18308 3.80421 2.29603 3.83035 2.33315C3.9398 2.48838 4.22492 2.61784 4.63324 2.69754C4.85357 2.74061 5.1258 2.76337 5.42024 2.76337C6.06772 2.76337 6.63814 2.66527 7.01664 2.55796C7.04419 2.55016 7.08696 2.51759 7.04112 2.49028C6.54629 2.08718 5.641 1.54891 4.88639 1.40147C4.71061 1.36724 4.55195 1.34975 4.415 1.34975ZM12.5909 4.11325H12.5908C11.943 4.11325 11.2489 4.02545 10.6865 3.87243C9.85407 3.64597 9.40372 3.30158 9.30954 2.81976C9.24555 2.49182 9.30905 1.95625 10.5955 1.09476C11.3535 0.587046 12.2023 0.206521 12.8661 0.076878C13.1269 0.0258919 13.3726 6.13551e-05 13.5961 6.13551e-05C14.6534 6.13551e-05 15.3466 0.568639 15.5482 1.601C15.6635 2.19099 15.5746 2.69913 15.2838 3.11132C14.9594 3.5713 14.4207 3.8693 13.6366 4.02244C13.3281 4.08263 12.9762 4.11325 12.5909 4.11325ZM10.9809 2.48194C10.937 2.5074 10.9582 2.54759 10.981 2.55415C11.3593 2.66294 11.9357 2.76344 12.5908 2.76344C12.8854 2.76344 13.1575 2.74067 13.3779 2.6976C13.7861 2.61784 14.0714 2.48844 14.1808 2.33321C14.207 2.29609 14.2868 2.18314 14.2234 1.85974C14.1462 1.46418 14.0055 1.34981 13.5961 1.34981C13.4592 1.34981 13.3006 1.36724 13.1247 1.4016C12.3701 1.54897 11.4757 2.07877 10.9809 2.48194Z"/>
                                    </svg>
								</div>
								<?php esc_html_e('Packages', 'bookingpress-package'); ?>
							</a>
							<el-dropdown-menu slot="dropdown" class="bpa-ni-dropdown-menu" v-cloak>                           
								<el-dropdown-item class="bpa-ni-dropdown-menu--item <?php echo ( 'package' == $request_module  ) ? '__active' : ''; ?>">
									<a href="<?php echo add_query_arg( array( 'page'=> $bookingpress_slugs->bookingpress_package), esc_url( admin_url() . 'admin.php?page=bookingpress' ) );  // phpcs:ignore ?>" class="bpa-dm--item-link">
									<span>
                                        <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M7.98832 9.995H2.14277C1.6796 9.995 1.30061 10.3739 1.30061 10.8371V17.1579C1.30061 17.6211 1.6796 18 2.14277 18H8.08661C8.28473 18 8.28473 17.7872 8.28473 17.7872V10.2812C8.28467 10.2812 8.28467 9.995 7.98832 9.995ZM15.849 9.995H10.0094C9.65375 9.995 9.707 10.3654 9.707 10.3654V17.794C9.707 17.794 9.70412 17.9998 9.91806 17.9998H15.8489C16.3121 17.9998 16.6911 17.6209 16.6911 17.1577V10.8371C16.6912 10.3739 16.3122 9.995 15.849 9.995ZM8.28467 5.02167C8.28467 5.02167 8.28467 4.73631 8.00268 4.73631H1.19177C0.7286 4.73631 0.349609 5.1153 0.349609 5.57841V8.20717C0.349609 8.67034 0.7286 9.04927 1.19177 9.04927H8.02575C8.28467 9.04927 8.28467 8.82471 8.28467 8.82471V5.02167ZM16.8 4.73631H9.96733C9.70713 4.73631 9.70713 4.98425 9.70713 4.98425V8.82992C9.70713 8.82992 9.70713 9.04927 10.0235 9.04927H16.8C17.2631 9.04927 17.6421 8.67034 17.6421 8.20717V5.57841C17.6421 5.1153 17.2631 4.73631 16.8 4.73631ZM5.4203 4.11325C5.03499 4.11325 4.68306 4.08269 4.37444 4.02238C3.59057 3.86924 3.05181 3.57118 2.72737 3.11126C2.43667 2.69907 2.3477 2.19093 2.46286 1.60088C2.6646 0.568517 3.35791 0 4.41494 0C4.63864 0 4.88431 0.0258305 5.14519 0.0768166C5.8088 0.20646 6.65759 0.586985 7.41576 1.0947C8.70207 1.95619 8.76569 2.49175 8.70164 2.8197C8.60746 3.30158 8.15705 3.64591 7.32464 3.87243C6.76226 4.02545 6.06815 4.11325 5.4203 4.11325ZM4.415 1.34975C4.00564 1.34975 3.86495 1.46412 3.78771 1.85967C3.72451 2.18308 3.80421 2.29603 3.83035 2.33315C3.9398 2.48838 4.22492 2.61784 4.63324 2.69754C4.85357 2.74061 5.1258 2.76337 5.42024 2.76337C6.06772 2.76337 6.63814 2.66527 7.01664 2.55796C7.04419 2.55016 7.08696 2.51759 7.04112 2.49028C6.54629 2.08718 5.641 1.54891 4.88639 1.40147C4.71061 1.36724 4.55195 1.34975 4.415 1.34975ZM12.5909 4.11325H12.5908C11.943 4.11325 11.2489 4.02545 10.6865 3.87243C9.85407 3.64597 9.40372 3.30158 9.30954 2.81976C9.24555 2.49182 9.30905 1.95625 10.5955 1.09476C11.3535 0.587046 12.2023 0.206521 12.8661 0.076878C13.1269 0.0258919 13.3726 6.13551e-05 13.5961 6.13551e-05C14.6534 6.13551e-05 15.3466 0.568639 15.5482 1.601C15.6635 2.19099 15.5746 2.69913 15.2838 3.11132C14.9594 3.5713 14.4207 3.8693 13.6366 4.02244C13.3281 4.08263 12.9762 4.11325 12.5909 4.11325ZM10.9809 2.48194C10.937 2.5074 10.9582 2.54759 10.981 2.55415C11.3593 2.66294 11.9357 2.76344 12.5908 2.76344C12.8854 2.76344 13.1575 2.74067 13.3779 2.6976C13.7861 2.61784 14.0714 2.48844 14.1808 2.33321C14.207 2.29609 14.2868 2.18314 14.2234 1.85974C14.1462 1.46418 14.0055 1.34981 13.5961 1.34981C13.4592 1.34981 13.3006 1.36724 13.1247 1.4016C12.3701 1.54897 11.4757 2.07877 10.9809 2.48194Z"/>
                                        </svg>
									</span>    
									<?php esc_html_e( 'Packages', 'bookingpress-package' ); ?>
									</a>
								</el-dropdown-item>                           
								<el-dropdown-item class="bpa-ni-dropdown-menu--item  <?php echo ( 'package_order' == $request_module ) ? '__active' : ''; ?>">
									<a href="<?php echo add_query_arg( array( 'page'=> $bookingpress_slugs->bookingpress_package_order), esc_url( admin_url() . 'admin.php?page=bookingpress' ) );  // phpcs:ignore ?>" class="bpa-dm--item-link">
										<span>
											<svg width="19" height="18" viewBox="0 0 19 18" fill="none" xmlns="http://www.w3.org/2000/svg">
											<path d="M12.3334 9.83366L18.1367 13.2187L15.6592 13.927L17.4301 16.9945L15.9867 17.8278L14.2159 14.7612L12.3634 16.5528L12.3334 9.83366ZM10.6667 4.00033H12.3334V5.66699H16.5001C16.7211 5.66699 16.9331 5.75479 17.0893 5.91107C17.2456 6.06735 17.3334 6.27931 17.3334 6.50033V9.83366H15.6667V7.33366H7.33342V15.667H10.6667V17.3337H6.50008C6.27907 17.3337 6.06711 17.2459 5.91083 17.0896C5.75455 16.9333 5.66675 16.7213 5.66675 16.5003V12.3337H4.00008V10.667H5.66675V6.50033C5.66675 6.27931 5.75455 6.06735 5.91083 5.91107C6.06711 5.75479 6.27907 5.66699 6.50008 5.66699H10.6667V4.00033ZM2.33341 10.667V12.3337H0.666748V10.667H2.33341ZM2.33341 7.33366V9.00033H0.666748V7.33366H2.33341ZM2.33341 4.00033V5.66699H0.666748V4.00033H2.33341ZM2.33341 0.666992V2.33366H0.666748V0.666992H2.33341ZM5.66675 0.666992V2.33366H4.00008V0.666992H5.66675ZM9.00008 0.666992V2.33366H7.33342V0.666992H9.00008ZM12.3334 0.666992V2.33366H10.6667V0.666992H12.3334Z" />
											</svg>
										</span>
										<?php esc_html_e( 'Package Order', 'bookingpress-package' ); ?>
									</a>
								</el-dropdown-item>                            
						</el-dropdown>
				</li>

            <?php
        }

        
        /**
         * Function for get service duration text
         *
         * @param  mixed $bookingpress_service_duration_val
         * @param  mixed $bookingpress_service_duration_unit
         * @return void
         */
        function bookingpress_get_service_duration_text($bookingpress_service_duration_val,$bookingpress_service_duration_unit){
            if(!empty($bookingpress_service_duration_val)){

                if ( $bookingpress_service_duration_unit == 'm' ) {
                    if($bookingpress_service_duration_val == 1){
                        $bookingpress_service_duration_val .= ' ' . esc_html__('Min', 'bookingpress-package');
                    }
                    else{
                        $bookingpress_service_duration_val .= ' ' . esc_html__('Mins', 'bookingpress-package');
                    }
                } else if($bookingpress_service_duration_unit == 'h') {
                    if( $bookingpress_service_duration_val == 1 ) {
                        $bookingpress_service_duration_val .= ' ' . esc_html__('Hour', 'bookingpress-package');
                    }
                    else {
                        $bookingpress_service_duration_val .= ' ' . esc_html__('Hours', 'bookingpress-package');
                    }
                }else{
                    if( $bookingpress_service_duration_val == 1 ) {
                        $bookingpress_service_duration_val .= ' ' . esc_html__('Day', 'bookingpress-package');
                    }
                    else {
                        $bookingpress_service_duration_val .= ' ' . esc_html__('Days', 'bookingpress-package');
                    }                            
                } 

            }
            return $bookingpress_service_duration_val;
        }
        
        /**
         * Function for get package edit detail.
         *
         * @return void
         */
        function bookingpress_get_edit_package_func(){
            global $wpdb, $BookingPress, $tbl_bookingpress_packages, $tbl_bookingpress_package_services, $tbl_bookingpress_services, $tbl_bookingpress_package_images;

            $bpa_check_authorization = $this->bpa_check_authentication( 'edit_package_details', true, 'bpa_wp_nonce' );
            
            if( preg_match( '/error/', $bpa_check_authorization ) ){
                $bpa_auth_error = explode( '^|^', $bpa_check_authorization );
                $bpa_error_msg = !empty( $bpa_auth_error[1] ) ? $bpa_auth_error[1] : esc_html__( 'Sorry. Something went wrong while processing the request', 'bookingpress-package');

                $response['variant'] = 'error';
                $response['title'] = esc_html__( 'Error', 'bookingpress-package');
                $response['msg'] = $bpa_error_msg;

                wp_send_json( $response );
                die;
            }

            $response['variant']   = 'error';
            $response['title']     = esc_html__('Error', 'bookingpress-package');
            $response['msg']       = esc_html__('Something went wrong..', 'bookingpress-package');
            $response['edit_data'] = array();
            
            if (! empty($_POST['edit_id']) ) { // phpcs:ignore WordPress.Security.NonceVerification
                $bookingpress_edit_id               = intval($_POST['edit_id']); // phpcs:ignore WordPress.Security.NonceVerification

                $package_details = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$tbl_bookingpress_packages} WHERE bookingpress_package_id = %d", $bookingpress_edit_id), ARRAY_A ); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- Reason: $tbl_bookingpress_packages is table name defined globally.
                


                $get_package_services = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$tbl_bookingpress_package_services} WHERE bookingpress_package_id = %d", $bookingpress_edit_id ) ); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- Reason: $tbl_bookingpress_packages_service_staff_pricing_details is table name defined globally.

                $get_package_images = $wpdb->get_results( $wpdb->prepare( "SELECT bookingpress_package_img_id,bookingpress_package_img_name,bookingpress_package_img_url FROM {$tbl_bookingpress_package_images} WHERE bookingpress_package_id = %d", $bookingpress_edit_id ) ); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- Reason: $tbl_bookingpress_packages_service_staff_pricing_details is table name defined globally.
                $assigned_images = array();
                if(!empty($get_package_images)){
                    foreach( $get_package_images as $ls_key => $ls_value ){

                        $assigned_package_image_data = array(
                            'bookingpress_package_img_id' => $ls_value->bookingpress_package_img_id,
                            'image_url' => $ls_value->bookingpress_package_img_url,
                            'image_name' => $ls_value->bookingpress_package_img_name,
                        );
                        $assigned_images[] = $assigned_package_image_data;

                    }
                }

                $assigned_services = array();
                if( !empty( $get_package_services ) ){
                    foreach( $get_package_services as $ls_key => $ls_value ){
                        
                        $package_service_id = $ls_value->bookingpress_service_id;
                        $bookingpress_package_service_id = $ls_value->bookingpress_package_service_id;

                        $package_service_detail = $wpdb->get_row( $wpdb->prepare( "SELECT bookingpress_service_name,bookingpress_service_duration_val,bookingpress_service_duration_unit FROM {$tbl_bookingpress_services} WHERE bookingpress_service_id = %d", $package_service_id ) ); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- Reason: $tbl_bookingpress_services is table name defined globally.
                        $package_service_name = (isset($package_service_detail->bookingpress_service_name))?$package_service_detail->bookingpress_service_name:'';
                        $bookingpress_service_duration_val = (isset($package_service_detail->bookingpress_service_duration_val))?$package_service_detail->bookingpress_service_duration_val:'';
                        $bookingpress_service_duration_unit = (isset($package_service_detail->bookingpress_service_duration_unit))?$package_service_detail->bookingpress_service_duration_unit:'';                       
                        $service_duration = $this->bookingpress_get_service_duration_text($bookingpress_service_duration_val,$bookingpress_service_duration_unit);                                                
                        $package_service_no_of_appointments = $ls_value->bookingpress_no_of_appointments;

                        $assigned_package_service_data = array(
                            'bookingpress_package_service_id' => $bookingpress_package_service_id,
                            'service_id' => $package_service_id,
                            'service_name' => $package_service_name,
                            'service_duration' => $service_duration,
                            'service_no_of_appointments' => $package_service_no_of_appointments
                        );
                        
                        $assigned_services[] = $assigned_package_service_data;

                    }
                }
                $package_details['assigned_service_details'] = $assigned_services;
                $package_details['assigned_image_details'] = $assigned_images;


                $package_details['bookingpress_package_description'] = (!empty($package_details['bookingpress_package_description']))?stripslashes_deep($package_details['bookingpress_package_description']):'';
                $package_details['bookingpress_package_name'] = (!empty($package_details['bookingpress_package_name']))?stripslashes_deep($package_details['bookingpress_package_name']):'';                
                
                $response['edit_data'] = $package_details;
                $response['msg']       = esc_html__('Edit data retrieved successfully', 'bookingpress-package');
                $response['variant']   = 'success';
                $response['title']     = esc_html__('Success', 'bookingpress-package');
                $response = apply_filters('bookingpress_modified_get_edit_package_response',$response,$bookingpress_edit_id);
            }

            echo wp_json_encode($response);
            exit();
        }
        
        /**
         * Function for bulk delete package 
         *
         * @return void
         */
        function bookingpress_delete_package_bulk_action(){
            global $wpdb, $tbl_bookingpress_packages, $tbl_bookingpress_package_services,$tbl_bookingpress_package_images,$tbl_bookingpress_package_bookings;
            $response = array();
            $bpa_check_authorization = $this->bpa_check_authentication( 'delete_package', true, 'bpa_wp_nonce' );            
            if( preg_match( '/error/', $bpa_check_authorization ) ){
                $bpa_auth_error = explode( '^|^', $bpa_check_authorization );
                $bpa_error_msg = !empty( $bpa_auth_error[1] ) ? $bpa_auth_error[1] : esc_html__( 'Sorry. Something went wrong while processing the request', 'bookingpress-package');

                $response['variant'] = 'error';
                $response['title'] = esc_html__( 'Error', 'bookingpress-package');
                $response['msg'] = $bpa_error_msg;

                wp_send_json( $response );
                die;
            }            
            $response['variant'] = 'error';
            $response['title']   = esc_html__('Error', 'bookingpress-package');
            $response['msg']     = esc_html__('Something went wrong..', 'bookingpress-package');
            $return              = false;
            $delete_ids = !empty( $_POST['delete_ids'] ) ? sanitize_text_field( $_POST['delete_ids'] ) : ''; // phpcs:ignore
            $delete_ids = json_decode( stripslashes_deep( $delete_ids ), true );

            if( empty( $delete_ids ) ){
                $response['msg'] = esc_html__( 'Please select at-least one package to delete', 'bookingpress-package' );
                wp_send_json( $response );
                die;
            }            
            $bookingpress_error_msg = "";
            $deleted_ids = array();
            foreach( $delete_ids as $package_id ){

                $bookingpress_package_bookings_data = $wpdb->get_row( $wpdb->prepare( "SELECT bookingpress_package_id FROM {$tbl_bookingpress_package_bookings} WHERE bookingpress_package_id = %d ", $package_id ), ARRAY_A ); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared --Reason: $tbl_bookingpress_package_bookings is a table name. false alarm
                if(empty($bookingpress_package_bookings_data)){
                    $bpa_package_delete = $wpdb->delete($tbl_bookingpress_packages, array( 'bookingpress_package_id' => $package_id ));
                    $bpa_package_services_delete = $wpdb->delete($tbl_bookingpress_package_services, array( 'bookingpress_package_id' => $package_id ));
                    $bpa_package_images_delete = $wpdb->delete($tbl_bookingpress_package_images, array( 'bookingpress_package_id' => $package_id ));
                    if( true == $bpa_package_delete || true == $bpa_package_services_delete || true == $bpa_package_images_delete){
                        $deleted_ids[] = $package_id;
                    }
                }else{
                    $bookingpress_error_msg = esc_html__(' I am sorry', 'bookingpress-package') . '! ' . esc_html__('This package cannot be deleted because it has already been purchased by the customer.', 'bookingpress-package') . '.';
                }
            }

            if( count( $delete_ids ) == count( $deleted_ids ) ){
                $response['variant'] = 'success';
                $response['title']   = esc_html__('Success', 'bookingpress-package');
                $response['msg']     = esc_html__('package has been deleted successfully.', 'bookingpress-package');
            } else if( 0 == count( $delete_ids ) ) {
                $response['msg']     = esc_html__('Something went wrong while deleting packages.', 'bookingpress-package');
            } else {
                $response['msg']     = esc_html__('Some of the packages has not been deleted sucessfully.', 'bookingpress-package');
            }

            wp_send_json( $response );
            die;
        }
        
        /**
         * Function for delete package
         *
         * @param  mixed $delete_id
         * @return void
         */
        function bookingpress_delete_package_func($delete_id){

            global $wpdb, $tbl_bookingpress_packages, $tbl_bookingpress_package_services,$tbl_bookingpress_package_images,$tbl_bookingpress_appointment_bookings,$tbl_bookingpress_package_bookings;
            $response              = array();
            $bpa_check_authorization = $this->bpa_check_authentication( 'delete_package', true, 'bpa_wp_nonce' );

            if( preg_match( '/error/', $bpa_check_authorization ) ){

                $bpa_auth_error = explode( '^|^', $bpa_check_authorization );
                $bpa_error_msg = !empty( $bpa_auth_error[1] ) ? $bpa_auth_error[1] : esc_html__( 'Sorry. Something went wrong while processing the request', 'bookingpress-package');
                $response['variant'] = 'error';
                $response['title'] = esc_html__( 'Error', 'bookingpress-package');
                $response['msg'] = $bpa_error_msg;
                wp_send_json( $response );
                die;

            }
            
            $response['variant'] = 'error';
            $response['title']   = esc_html__('Error', 'bookingpress-package');
            $response['msg']     = esc_html__('Something went wrong..', 'bookingpress-package');
            $return              = false;

            if (! empty($_POST['delete_id']) || intval($delete_id) ) { // phpcs:ignore WordPress.Security.NonceVerification
                $delete_package_id = ! empty($_POST['delete_id']) ? intval($_POST['delete_id']) : intval($delete_id); // phpcs:ignore WordPress.Security.NonceVerification
                if (! empty($delete_package_id) ) {

                    $current_date                   = date('Y-m-d', current_time('timestamp'));

                    //$bookingperss_appointments_data = $wpdb->get_results( $wpdb->prepare( "SELECT bookingpress_appointment_booking_id FROM {$tbl_bookingpress_appointment_bookings} WHERE bookingpress_package_id = %d AND bookingpress_appointment_date >= %s AND (bookingpress_appointment_status != '3' AND bookingpress_appointment_status != '4') ", $delete_package_id, $current_date ), ARRAY_A ); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared --Reason: $tbl_bookingpress_appointment_bookings is a table name. false alarm
 
                    $bookingpress_package_bookings_data = $wpdb->get_row( $wpdb->prepare( "SELECT bookingpress_package_id FROM {$tbl_bookingpress_package_bookings} WHERE bookingpress_package_id = %d ", $delete_package_id ), ARRAY_A ); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared --Reason: $tbl_bookingpress_package_bookings is a table name. false alarm

                    if(empty($bookingpress_package_bookings_data)){

                        $wpdb->delete($tbl_bookingpress_packages, array( 'bookingpress_package_id' => $delete_package_id ));
                        $wpdb->delete($tbl_bookingpress_package_services, array( 'bookingpress_package_id' => $delete_package_id ));    
                        $wpdb->delete($tbl_bookingpress_package_images, array( 'bookingpress_package_id' => $delete_package_id ));

                        $response['variant'] = 'success';
                        $response['title']   = esc_html__('Success', 'bookingpress-package');
                        $response['msg']     = esc_html__('package has been deleted successfully.', 'bookingpress-package');
                        $return = true;

                        if (! empty($_POST['action']) && sanitize_text_field($_POST['action']) == 'bookingpress_delete_package' ) { // phpcs:ignore
                            echo wp_json_encode($response);
                            exit();
                        }
                    } else {

                        $bookingpress_error_msg = esc_html__(' I am sorry', 'bookingpress-package') . '! ' . esc_html__('This package cannot be deleted because it has already been purchased by the customer.', 'bookingpress-package') . '.';

                        $response['variant'] = 'warning';
                        $response['title']   = esc_html__('warning', 'bookingpress-package');
                        $response['msg']     = $bookingpress_error_msg;
                        $return              = false;
                        
                        if (! empty($_POST['action']) && sanitize_text_field($_POST['action']) == 'bookingpress_delete_package' ) { // phpcs:ignore
                            echo wp_json_encode($response);
                            exit();
                        }
                       
                    }
                }
            }
            return $return;
        }
        
        /**
         * Function for add package capability
         *
         * @param  mixed $bpa_caps
         * @return void
         */
        function bookingpress_modify_capability_data_func($bpa_caps){

            $bpa_caps['bookingpress_package'][] = 'get_package_details';
            $bpa_caps['bookingpress_package'][] = 'save_package_details';
            $bpa_caps['bookingpress_package'][] = 'delete_package';
            $bpa_caps['bookingpress_package'][] = 'edit_package_details';
            $bpa_caps['bookingpress_package'][] = 'manage_package_position';
            $bpa_caps['bookingpress_package'][] = 'remove_package_avatar';
            $bpa_caps['bookingpress_package'][] = 'upload_package_avatar';
            $bpa_caps['bookingpress_package'][] = 'change_package_status';

            return $bpa_caps;
        }
        
        /**
         * Function for get package duration text
         *
         * @param  mixed $bookingpress_package_duration
         * @param  mixed $bookingpress_package_duration_unit
         * @param  mixed $display_text
         * @return void
         */
        function get_package_duration_limit_text($bookingpress_package_duration,$bookingpress_package_duration_unit,$display_text=true){
            $duration_data = '';
            $duration_unit_text = '';
            if($bookingpress_package_duration == 1){                
                if($bookingpress_package_duration_unit == 'd'){
                    $duration_unit_text = esc_html__( 'Day', 'bookingpress-package');
                }else if($bookingpress_package_duration_unit == 'm'){
                    $duration_unit_text = esc_html__( 'Month', 'bookingpress-package');
                }else if($bookingpress_package_duration_unit == 'y'){
                    $duration_unit_text = esc_html__( 'Year', 'bookingpress-package');
                }                
            }else{
                if($bookingpress_package_duration_unit == 'd'){
                    $duration_unit_text = esc_html__( 'Days', 'bookingpress-package');
                }else if($bookingpress_package_duration_unit == 'm'){
                    $duration_unit_text = esc_html__( 'Months', 'bookingpress-package');
                }else if($bookingpress_package_duration_unit == 'y'){
                    $duration_unit_text = esc_html__( 'Years', 'bookingpress-package');
                } 
            }
            $duration_data = $bookingpress_package_duration.' '.$duration_unit_text;
            return $duration_data;
        }
        
        /**
         * Function for get package list
         *
         * @return void
         */
        function bookingpress_get_packages_func(){

            global $wpdb, $BookingPress, $tbl_bookingpress_services ,$tbl_bookingpress_packages, $tbl_bookingpress_package_services, $tbl_bookingpress_package_images, $tbl_bookingpress_appointment_bookings;
			$response              = array();

			$bpa_check_authorization = $this->bpa_check_authentication( 'get_package_details', true, 'bpa_wp_nonce' );           
			if( preg_match( '/error/', $bpa_check_authorization ) ){
				$bpa_auth_error = explode( '^|^', $bpa_check_authorization );
				$bpa_error_msg = !empty( $bpa_auth_error[1] ) ? $bpa_auth_error[1] : esc_html__( 'Sorry. Something went wrong while processing the request', 'bookingpress-package');
				$response['variant'] = 'error';
				$response['title'] = esc_html__( 'Error', 'bookingpress-package');
				$response['msg'] = $bpa_error_msg;
				wp_send_json( $response );
				die;
			}

            $response['variant'] = 'error';
            $response['title'] = esc_html__( 'Error', 'bookingpress-package');
            $response['msg'] = esc_html__('Sorry. Something went wrong while processing the request', 'bookingpress-package');
            $perpage     = isset($_POST['perpage']) ? intval($_POST['perpage']) : 10; // phpcs:ignore WordPress.Security.NonceVerification
            $currentpage = isset($_POST['currentpage']) ? intval($_POST['currentpage']) : 1; // phpcs:ignore WordPress.Security.NonceVerification
            $offset      = ( ! empty($currentpage) && $currentpage > 1 ) ? ( ( $currentpage - 1 ) * $perpage ) : 0; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized --Reason - $_REQUEST['search_data'] contains mixed array and it's been sanitized properly using 'appointment_sanatize_field' function
            $bookingpress_search_data  = ! empty($_REQUEST['search_data']) ? array_map(array( $BookingPress, 'appointment_sanatize_field' ), $_REQUEST['search_data']) : array(); // phpcs:ignore
            $bookingpress_search_query = '';
            if (! empty($bookingpress_search_data) ) {                
                $search_package_name = (isset($bookingpress_search_data['search_package_name']))?$bookingpress_search_data['search_package_name']:'';
                if(!empty($search_package_name)){
                    $bookingpress_search_query .= " WHERE bookingpress_package_name LIKE '%{$search_package_name}%' ";
                }
            }

            $get_total_packages = $wpdb->get_results("SELECT * FROM {$tbl_bookingpress_packages} {$bookingpress_search_query} ORDER BY bookingpress_package_position ASC", ARRAY_A); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared --Reason: $tbl_bookingpress_packages is a table name. false alarm            
            $total_packages     = $wpdb->get_results("SELECT * FROM {$tbl_bookingpress_packages} {$bookingpress_search_query} ORDER BY bookingpress_package_position ASC", ARRAY_A); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared --Reason: $tbl_bookingpress_packages is a table name. false alarm
            $packages           = array();
            
            $current_date = date('Y-m-d', current_time('timestamp'));
            if (! empty($total_packages) ) {
                foreach( $total_packages as $get_package ){


                    $get_package['bookingpress_package_description'] = (!empty($get_package['bookingpress_package_description']))?stripslashes_deep($get_package['bookingpress_package_description']):'';
                    $get_package['bookingpress_package_name'] = (!empty($get_package['bookingpress_package_name']))?stripslashes_deep($get_package['bookingpress_package_name']):'';
                    $bookingpress_package_id     = intval($get_package['bookingpress_package_id']);
                    $bookingpress_package_duration     = intval($get_package['bookingpress_package_duration']);
                    $bookingpress_package_duration_unit     = esc_html($get_package['bookingpress_package_duration_unit']);
                    $get_package['package_duration'] = $this->get_package_duration_limit_text($bookingpress_package_duration,$bookingpress_package_duration_unit);                                         
                                        
                    $bookingpress_package_img_url = $wpdb->get_var( $wpdb->prepare( "SELECT bookingpress_package_img_url FROM {$tbl_bookingpress_package_images} WHERE bookingpress_package_id = %d order by bookingpress_package_img_id ASC", $bookingpress_package_id ) ); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- Reason: $tbl_bookingpress_package_images is table name defined globally. False Positive alarm
                    if(empty($bookingpress_package_img_url)){
                        $bookingpress_package_img_url = esc_html(BOOKINGPRESS_PACKAGE_URL."/images/package-default-img.jpg");
                    }
                    $bookingperss_package_services = $wpdb->get_results( $wpdb->prepare( "SELECT bpackserv.bookingpress_no_of_appointments, bserv.bookingpress_service_name FROM {$tbl_bookingpress_package_services} bpackserv INNER JOIN {$tbl_bookingpress_services} bserv ON bpackserv.bookingpress_service_id = bserv.bookingpress_service_id WHERE bpackserv.bookingpress_package_id = %d", $bookingpress_package_id ), ARRAY_A ); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- Reason: $tbl_bookingpress_services is table name defined globally. False Positive alarm
                    $total_no_of_appointment = 0;
                    $package_services = array();
                    $package_service_display = '';
                    foreach($bookingperss_package_services as $val){
                        $total_no_of_appointment = $total_no_of_appointment + $val['bookingpress_no_of_appointments'];
                        if(empty($package_service_display)){
                            $package_service_display = $val['bookingpress_service_name'];                            
                        }
                        $package_services[] = $val['bookingpress_service_name'];
                    }
                    
                    
                    $get_package['package_services'] = $package_services;

                    $get_package['package_image'] = $bookingpress_package_img_url;
                    //$get_package['package_image'] = "";
                    $get_package['package_service_display'] = $package_service_display;
                    $get_package['package_service_count'] = (!empty($package_services))?(count($package_services) - 1):0;
                    
                    $get_package['package_total_appointment'] = $total_no_of_appointment;
                    $get_package['package_price']   = $BookingPress->bookingpress_price_formatter_with_currency_symbol($get_package['bookingpress_package_price']);
                    $get_package['retail_price']    = $BookingPress->bookingpress_price_formatter_with_currency_symbol($get_package['bookingpress_package_calculated_price']);
                    $bookingperss_appointments_data = '';

                    //$bookingperss_appointments_data = $wpdb->get_results( $wpdb->prepare( 'SELECT bookingpress_appointment_booking_id  FROM ' . $tbl_bookingpress_appointment_bookings . ' WHERE bookingpress_package_id = %d AND bookingpress_appointment_date >= %s AND (bookingpress_appointment_status != "3" AND bookingpress_appointment_status != "4")', $bookingpress_package_id, $current_date ), ARRAY_A);// phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared -- Reason: $tbl_bookingpress_appointment_bookings is table name defined globally. False Positive alarm                
                    
                    $get_package['package_bulk_action'] = false; 
                    if (! empty($bookingperss_appointments_data) ) {
                        $get_package['package_bulk_action'] = true; 
                    }
                    $get_package['selected'] = false;
                    $packages[] = $get_package;

                }
                $resposne['variant'] = 'success';
                $response['title'] = esc_html__( 'Success', 'bookingpress-package' );
                $response['msg'] = esc_html__( 'package data fetched successfully', 'bookingpress-package' );
            }
            
            $response['items'] = $packages;
            $response['total'] = count($total_packages);

            echo wp_json_encode($response);
            exit;
        }
        
        /**
         * Function for save package
         *
         * @return void
         */
        function bookingpress_save_package_details_func(){
            global $wpdb, $BookingPress, $tbl_bookingpress_packages, $tbl_bookingpress_package_services, $tbl_bookingpress_package_images, $tbl_bookingpress_services, $tbl_bookingpress_staffmembers_services,  $bookingpress_pro_services;
			$response              = array();
			$bpa_check_authorization = $this->bpa_check_authentication( 'save_package_details', true, 'bpa_wp_nonce' );           
			if( preg_match( '/error/', $bpa_check_authorization ) ){
				$bpa_auth_error = explode( '^|^', $bpa_check_authorization );
				$bpa_error_msg = !empty( $bpa_auth_error[1] ) ? $bpa_auth_error[1] : esc_html__( 'Sorry. Something went wrong while processing the request', 'bookingpress-package');

				$response['variant'] = 'error';
				$response['title'] = esc_html__( 'Error', 'bookingpress-package');
				$response['msg'] = $bpa_error_msg;

				wp_send_json( $response );
				die;
			}

            $response['variant'] = 'error';
            $response['title'] = esc_html__( 'Error', 'bookingpress-package');
            $response['msg'] = esc_html__('Something went wrong while save package details', 'bookingpress-package');
            

            //$bookingpress_package_form_data = !empty($_POST['package_details']) ? $_POST['package_details'] : array(); // phpcs:ignore
            //$bookingpress_package_form_service_details = !empty($_POST['package_service_details']) ? $_POST['package_service_details'] : array(); // phpcs:ignore            
            
            $bookingpress_package_form_data = !empty($_POST['package_details'])?array_map(array( $BookingPress, 'appointment_sanatize_field' ), $_POST['package_details']):array();  // phpcs:ignore 

            $bookingpress_package_form_service_details = !empty($_POST['package_service_details'] && !is_array($_POST['package_service_details']))?array_map(array( $BookingPress, 'appointment_sanatize_field' ), json_decode(stripslashes_deep($_POST['package_service_details']),true)):array();  // phpcs:ignore  
            
            

            $bookingpress_package_images_list = !empty($bookingpress_package_form_data['package_images_list']) ? $bookingpress_package_form_data['package_images_list'] : array(); // phpcs:ignore

            if( !empty($bookingpress_package_form_data) ){

                do_action('bookingpress_add_package_validation');

                if (strlen($bookingpress_package_form_data['package_name']) > 255 ) {
                    $response            = array();
                    $response['variant'] = 'error';
                    $response['title']   = esc_html__('Error', 'bookingpress-package');
                    $response['msg']     = esc_html__('Service name is too long...', 'bookingpress-package');
                    wp_send_json($response);
                    die();
                }                
                if(!empty( trim($bookingpress_package_form_data['package_name'] ) )){

                    $bookingpress_update_id = !empty($bookingpress_package_form_data['package_update_id']) ? intval($bookingpress_package_form_data['package_update_id']) : 0;

                    /*
                    $bookingpress_package_img_name = !empty($bookingpress_package_form_data['package_image_name']) ? $bookingpress_package_form_data['package_image_name'] : '';
                    $bookingpress_package_img_url = !empty($bookingpress_package_form_data['package_image']) ? $bookingpress_package_form_data['package_image'] : '';
                    if(!empty($bookingpress_package_img_name) && !empty($bookingpress_package_img_url)){
                        global $BookingPress;
                        $upload_dir                 = BOOKINGPRESS_UPLOAD_DIR . '/';
                        $bookingpress_new_file_name = current_time('timestamp') . '_' . $bookingpress_package_img_name;
                        $upload_path                = $upload_dir . $bookingpress_new_file_name;

                        $bookingpress_upload_res = new bookingpress_fileupload_class( $bookingpress_package_img_url, true );
                        $bookingpress_upload_res->bookingpress_process_upload( $upload_path );

                        $package_image_new_url   = BOOKINGPRESS_UPLOAD_URL . '/' . $bookingpress_new_file_name;
                        
                        $bookingpress_file_name_arr = explode('/', $bookingpress_package_img_url);
                        $bookingpress_file_name     = $bookingpress_file_name_arr[ count($bookingpress_file_name_arr) - 1 ];
                        if( file_exists( BOOKINGPRESS_TMP_IMAGES_DIR . '/' . $bookingpress_file_name ) ){
                            @unlink(BOOKINGPRESS_TMP_IMAGES_DIR . '/' . $bookingpress_file_name);
                        }

                        $bookingpress_package_img_url = $package_image_new_url;
                        $bookingpress_package_img_name = $bookingpress_new_file_name;
                    }
                    */

                    $bookingpress_is_record_add = false;
                    $package_name = isset($bookingpress_package_form_data['package_name']) ? sanitize_text_field($bookingpress_package_form_data['package_name']) : ''; // phpcs:ignore WordPress.Security.NonceVerification
                    $package_description = isset($bookingpress_package_form_data['package_description']) ? sanitize_text_field($bookingpress_package_form_data['package_description']) : ''; // phpcs:ignore WordPress.Security.NonceVerification
                    $package_price = isset($bookingpress_package_form_data['package_price']) ? floatval($bookingpress_package_form_data['package_price']) : 0; // phpcs:ignore WordPress.Security.NonceVerification
                    $package_purchase_limit = isset($bookingpress_package_form_data['package_purchase_limit']) ? intval($bookingpress_package_form_data['package_purchase_limit']) : 0; // phpcs:ignore WordPress.Security.NonceVerification                    
                    $package_duration_val = isset($bookingpress_package_form_data['package_duration_val']) ? intval($bookingpress_package_form_data['package_duration_val']) : 0; // phpcs:ignore WordPress.Security.NonceVerification
                    $package_duration_unit = isset($bookingpress_package_form_data['package_duration_unit']) ? sanitize_text_field($bookingpress_package_form_data['package_duration_unit']) : ''; // phpcs:ignore WordPress.Security.NonceVerification

                    $bookingpress_db_fields = array(
                        'bookingpress_package_name' => $package_name,
                        'bookingpress_package_description' => $package_description,
                        'bookingpress_package_price' => $package_price,
                        'bookingpress_package_customer_purchase_limit' => $package_purchase_limit,
                        'bookingpress_package_duration' => $package_duration_val,
                        'bookingpress_package_duration_unit' => $package_duration_unit, 
                        'bookingpress_package_calculated_price' => 0,                       
                    );

                    if(empty($bookingpress_update_id)){

                        $bookingpress_package_position = 0;

                        $package  = $wpdb->get_row('SELECT * FROM ' . $tbl_bookingpress_packages . ' ORDER BY bookingpress_package_position DESC LIMIT 1', ARRAY_A);// phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared -- Reason: $tbl_bookingpress_packages is table name defined globally. False Positive alarm
                        if (! empty($package) ) {
                            $bookingpress_package_position = $package['bookingpress_package_position'] + 1;
                        }
                        $bookingpress_db_fields['bookingpress_package_position'] = $bookingpress_package_position;

                        $wpdb->insert($tbl_bookingpress_packages, $bookingpress_db_fields);
                        $bookingpress_update_id = $wpdb->insert_id;
                        $bookingpress_is_record_add = true;

                        do_action('bookingpress_after_add_package', $bookingpress_update_id);

                        $response['variant'] = 'success';
                        $response['title'] = esc_html__( 'Success', 'bookingpress-package');
                        $response['msg'] = esc_html__('Package has been added successfully.', 'bookingpress-package');

                    }else{

                        $wpdb->update($tbl_bookingpress_packages, $bookingpress_db_fields, array('bookingpress_package_id' => $bookingpress_update_id));                        
                        do_action('bookingpress_after_update_package', $bookingpress_update_id);
                        $response['variant'] = 'success';
                        $response['title'] = esc_html__( 'Success', 'bookingpress-package');
                        $response['msg'] = esc_html__('package has been updated successfully.', 'bookingpress-package');

                    }

                } elseif( empty(trim($bookingpress_package_form_data['package_name']))){
                    $response['msg'] = esc_html__('Please add valid data for add package', 'bookingpress-package') . '.';
                }
            }

            if( !empty( $bookingpress_package_form_data['deleted_images'] ) ){
                $bookingpress_package_delete_images = $bookingpress_package_form_data['deleted_images'];
                foreach( $bookingpress_package_delete_images as $package_data_image_id ){
                    if($package_data_image_id){
                        $wpdb->delete(
                            $tbl_bookingpress_package_images,
                            array(
                                'bookingpress_package_img_id' => $package_data_image_id
                            )
                        );    
                    }
                }                
            }

            if(!empty($bookingpress_package_images_list)){
                foreach($bookingpress_package_images_list as $img_data){

                    $bookingpress_package_img_id = $img_data['bookingpress_package_img_id'];
                    $bookingpress_package_img_name = $img_data['image_name'];
                    $bookingpress_package_img_url = $img_data['image_url'];

                    if(!empty($bookingpress_package_img_name) && !empty($bookingpress_package_img_url)){

                        //global $BookingPress;
                        $upload_dir                 = BOOKINGPRESS_UPLOAD_DIR . '/';
                        $bookingpress_new_file_name = current_time('timestamp').rand(). '_' . $bookingpress_package_img_name;
                        $upload_path                = $upload_dir . $bookingpress_new_file_name;
                        $bookingpress_upload_res = new bookingpress_fileupload_class( $bookingpress_package_img_url, true );
                        $bookingpress_upload_res->check_cap          = true;
                        $bookingpress_upload_res->check_nonce        = true;
                        $bookingpress_upload_res->nonce_data         = isset($_REQUEST['_wpnonce']) ? sanitize_text_field($_REQUEST['_wpnonce']) : '';
                        $bookingpress_upload_res->nonce_action       = 'bpa_wp_nonce';
                        $bookingpress_upload_res->check_only_image   = true;
                        $bookingpress_upload_res->check_specific_ext = false;
                        $bookingpress_upload_res->allowed_ext        = array();
                        $upload_response = $bookingpress_upload_res->bookingpress_process_upload( $upload_path );
                        if( true == $upload_response ){
                            $package_image_new_url   = BOOKINGPRESS_UPLOAD_URL . '/' . $bookingpress_new_file_name;                        
                            $bookingpress_file_name_arr = explode('/', $bookingpress_package_img_url);
                            $bookingpress_file_name     = $bookingpress_file_name_arr[ count($bookingpress_file_name_arr) - 1 ];
                            if( file_exists( BOOKINGPRESS_TMP_IMAGES_DIR . '/' . $bookingpress_file_name ) ){
                                //@unlink(BOOKINGPRESS_TMP_IMAGES_DIR . '/' . $bookingpress_file_name);
                            }
                            
                            $bookingpress_package_img_url = $package_image_new_url;
                            $bookingpress_package_img_name = $bookingpress_new_file_name;                        
                            $bookingpress_assign_package_to_image = array(                        
                                'bookingpress_package_id' => $bookingpress_update_id,
                                'bookingpress_package_img_name' => $bookingpress_package_img_name,
                                'bookingpress_package_img_url' => $bookingpress_package_img_url,                        
                            );

                            if(empty($bookingpress_package_img_id)){
                                $wpdb->insert( $tbl_bookingpress_package_images, $bookingpress_assign_package_to_image );
                            }
                        }
                    }
                }
            }

            if( !empty( $bookingpress_package_form_data['deleted_packages'] ) ){
                $bookingpress_package_delete_services = $bookingpress_package_form_data['deleted_packages'];
                foreach( $bookingpress_package_delete_services as $package_data_service_id ){
                    if($package_data_service_id){
                        $wpdb->delete(
                            $tbl_bookingpress_package_services,
                            array(
                                'bookingpress_package_service_id' => $package_data_service_id
                            )
                        );    
                    }
                }
            }

            if( !empty($bookingpress_package_form_service_details) ){
                
                $bookingpress_package_calculated_price = 0;
                foreach($bookingpress_package_form_service_details as $bookingpress_package_service_details_key => $bookingpress_package_service_details_val){
                    
                    $bookingpress_assign_package_to_service = array(                        
                        'bookingpress_package_id' => $bookingpress_update_id,
                        'bookingpress_service_id' => 0,
                        'bookingpress_service_price' => 0,
                        'bookingpress_no_of_appointments' => 0,
                    );

                    $bookingpress_package_service_id = !empty( $bookingpress_package_service_details_val['bookingpress_package_service_id'] ) ? intval( $bookingpress_package_service_details_val['bookingpress_package_service_id'] ) : 0;
                    $bookingpress_service_id = intval( $bookingpress_package_service_details_val['service_id'] );                                        
                    $bookingpress_service_price = (float)$wpdb->get_var( $wpdb->prepare( "SELECT bookingpress_service_price FROM {$tbl_bookingpress_services} WHERE bookingpress_service_id = %d", $bookingpress_service_id ) );  //phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- Reason: $tbl_bookingpress_services is table name defined globally. False Positive alarm
                    $bookingpress_service_no_of_appointments = !empty( $bookingpress_package_service_details_val['service_no_of_appointments'] ) ? intval( $bookingpress_package_service_details_val['service_no_of_appointments'] ) : 1;
                    $bookingpress_assign_package_to_service['bookingpress_service_id'] = $bookingpress_service_id;
                    $bookingpress_assign_package_to_service['bookingpress_no_of_appointments'] = $bookingpress_service_no_of_appointments;
                    $bookingpress_assign_package_to_service['bookingpress_service_price'] = $bookingpress_service_price;
                    $bookingpress_package_calculated_price = $bookingpress_package_calculated_price + ($bookingpress_service_price * $bookingpress_service_no_of_appointments);
                    
                    if( !empty( $bookingpress_package_service_id ) ){
                        $wpdb->update( $tbl_bookingpress_package_services, $bookingpress_assign_package_to_service, array( 'bookingpress_package_service_id' => $bookingpress_package_service_id ) );
                    } else {                                                
                        $wpdb->insert( $tbl_bookingpress_package_services, $bookingpress_assign_package_to_service );
                    }

                }

                $wpdb->update($tbl_bookingpress_packages, array('bookingpress_package_calculated_price'=>$bookingpress_package_calculated_price), array('bookingpress_package_id' => $bookingpress_update_id));

            }
            
            echo wp_json_encode($response);
            die;
        }
        
        /**
         * Add Admin CSS
         *
         * @return void
         */
        function set_css(){
            global $bookingpress_slugs;
			wp_register_style( 'bookingpress_package_css', BOOKINGPRESS_PACKAGE_URL . '/css/bookingpress_package_admin.css', array(), BOOKINGPRESS_PACKAGE_VERSION );
            wp_register_style( 'bookingpress_package_admin_rtl_css', BOOKINGPRESS_PACKAGE_URL . '/css/bookingpress_package_admin_rtl.css', array(), BOOKINGPRESS_PACKAGE_VERSION );
            if ( isset( $_REQUEST['page'] ) && in_array( sanitize_text_field( $_REQUEST['page'] ), (array) $bookingpress_slugs ) ) {
				wp_enqueue_style( 'bookingpress_package_css' );

                if($_REQUEST['page'] == "bookingpress_package"){
                    //wp_enqueue_style('bookingpress_tel_input');
                }
                if (is_rtl() ) {
                    wp_enqueue_style('bookingpress_package_admin_rtl_css');
                } 
			}
        }
        
        /**
         * Function for add admin js
         *
         * @return void
         */
        function set_js(){
            global $bookingpress_slugs;
            if ( isset( $_REQUEST['page'] ) && in_array( sanitize_text_field( $_REQUEST['page'] ), (array) $bookingpress_slugs ) ) {
                if($_REQUEST['page'] == "bookingpress_package"){
                  
                }
                wp_enqueue_script('bookingpress_sortable_js');
                wp_enqueue_script('bookingpress_draggable_js');
            }
        }
        
        /**
         * Function for add package vue data
         *
         * @return void
         */
        function bookingpress_package_vue_data_fields_func(){

            global $bookingpress_package_vue_data_fields, $bookingpress_global_options,$BookingPress;
            $bookingpress_options             = $bookingpress_global_options->bookingpress_global_options();

            $bookingpress_pagination          = $bookingpress_options['pagination'];
            $bookingpress_pagination_arr      = json_decode($bookingpress_pagination, true);
            $bookingpress_pagination_selected = $bookingpress_pagination_arr[0];

            $bookingpress_package_vue_data_fields = array(
                'package_bulk_action'       => 'bulk_action',
                'bulk_options'               => array(
                    array(
                        'value' => 'bulk_action',
                        'label' => __('Bulk Action', 'bookingpress-package'),
                    ),
                    array(
                        'value' => 'delete',
                        'label' => __('Delete', 'bookingpress-package'),
                    ),
                ),
                'loading'                    => false,
                'items'                      => array(),
                'multiplepackageSelection'          => array(),
                'perPage'                    => $bookingpress_pagination_selected,
                'totalItems'                 => 0,
                'pagination_selected_length' => $bookingpress_pagination_selected,
                'pagination_length'          => $bookingpress_pagination,
                'currentPage'                => 1,
                'pagination_length_val'      => '10',
                'pagination_val'             => array(
                    array(
                        'text'  => '10',
                        'value' => '10',
                    ),
                    array(
                        'text'  => '20',
                        'value' => '20',
                    ),
                    array(
                        'text'  => '50',
                        'value' => '50',
                    ),
                    array(
                        'text'  => '100',
                        'value' => '100',
                    ),
                    array(
                        'text'  => '200',
                        'value' => '200',
                    ),
                    array(
                        'text'  => '300',
                        'value' => '300',
                    ),
                    array(
                        'text'  => '400',
                        'value' => '400',
                    ),
                    array(
                        'text'  => '500',
                        'value' => '500',
                    ),
                ),
                'is_display_loader'          => '0',
                'is_disabled'                => false,
                'is_display_save_loader'     => '0',
                'is_multiple_checked'        => false,
                'open_package_modal'        => false,
                'packageShowFileList'        => false,
                'rules'                      => array(
                    'package_name'        => array(
                        array(
                            'required' => true,
                            'message'  => esc_html__('Please enter package name', 'bookingpress-package'),
                            'trigger'  => 'blur',
                        ),
                    ),
                    'package_duration_val' => array(
                        array(
                            'required' => true,
                            'message'  => esc_html__('Please enter duration', 'bookingpress-package'),
                            'trigger'  => 'blur',
                        ),
                    ),
                    'package_price'        => array(
                        array(
                            'required' => true,
                            'message'  => esc_html__('Please enter price', 'bookingpress-package'),
                            'trigger'  => 'blur',
                        ),
                    ),                    
                ),
                'modal_loader'                => 1,
                'search_package_name'         => '',
                'package'                    => array(
                    'package_name'           => '',
                    'package_description'    => '',
                    'package_duration_val'   => 1,
                    'package_duration_unit'  => 'y',
                    'package_price'          => '',
                    'package_purchase_limit' => '0',

                    'package_image'          => '',
                    'package_image_name'     => '',
                    'package_images_list'    => array(),
                    'deleted_images'         => array(),

                    'deleted_packages'        => array(),                    
                    'package_update_id'      => 0,
                ),
            );

            $max_no_of_customer_limit = 100;
            $bookingpress_no_of_customer_limit = array();
            for($i=0;$i<=$max_no_of_customer_limit;$i++){
                if($i == 0){
                    $label = esc_html__('Unlimited', 'bookingpress-package');
                }else{
                    $label = $i;
                }                 
                $bookingpress_no_of_customer_limit[] = array('label' => $label,'value' => $i);
            }
            $bookingpress_package_vue_data_fields['bookingpress_no_of_customer_limit'] = $bookingpress_no_of_customer_limit;

            $bookingpress_package_vue_data_fields['price_number_of_decimals'] = $BookingPress->bookingpress_get_settings('price_number_of_decimals', 'payment_setting');            
            $bookingpress_payment_deafult_currency  = $BookingPress->bookingpress_get_settings('payment_default_currency', 'payment_setting');
            $bookingpress_payment_deafult_currency  = $BookingPress->bookingpress_get_currency_symbol($bookingpress_payment_deafult_currency);
            $bookingpress_package_vue_data_fields['package_price_currency'] = $bookingpress_payment_deafult_currency;

        }

        function bookingpress_package_dynamic_helper_vars_func(){
            global $bookingpress_global_options;
			$bookingpress_options     = $bookingpress_global_options->bookingpress_global_options();
			$bookingpress_locale_lang = $bookingpress_options['locale'];
			?>
				var lang = ELEMENT.lang.<?php echo esc_html( $bookingpress_locale_lang ); ?>;
				ELEMENT.locale(lang)
			<?php
        }

        function bookingpress_package_dynamic_data_fields_func(){
            global $wpdb, $bookingpress_package_vue_data_fields, $BookingPress, $tbl_bookingpress_services, $bookingpress_pro_services, $tbl_bookingpress_staffmembers, $tbl_bookingpress_staffmembers_services, $bookingpress_services;

            $bookingpress_phone_country_option = $BookingPress->bookingpress_get_settings('default_phone_country_code', 'general_setting');
            $bookingpress_package_vue_data_fields['customer']['customer_phone_country'] = $bookingpress_phone_country_option;

            $bookingpress_package_vue_data_fields['bookingpress_tel_input_props'] = array(
                'defaultCountry' => $bookingpress_phone_country_option,
                'inputOptions' => array(
                    'placeholder' => '',
                ),
                'validCharactersOnly' => true,
            );
            
            $bookingpress_package_vue_data_fields['package_default_img_url'] = esc_html(BOOKINGPRESS_PACKAGE_URL."/images/package-default-img.jpg");
            $bookingpress_package_vue_data_fields['items'] = array();

            $bookingpress_package_vue_data_fields['package_assigned_services'] = array();
            $bookingpress_package_vue_data_fields['package_images_list'] = array();
            $bookingpress_package_vue_data_fields['open_assign_service_package_modal'] = false;

            $bookingpress_services_data = $BookingPress->get_bookingpress_service_data_group_with_category();
            $bookingpress_package_services_data = array();
			if(!empty($bookingpress_services_data)){

				foreach($bookingpress_services_data as $k => $v){
					$bookingpress_category_services = !empty($v['category_services']) ? $v['category_services'] : array();
					if(!empty($bookingpress_category_services)){
						foreach($bookingpress_category_services as $k2 => $v2){

							$bookingpress_service_id = $v2['service_id'];
							$bookingpress_service_max_capacity = $bookingpress_pro_services->bookingpress_get_service_max_capacity($bookingpress_service_id);
                            
                            $has_custom_service_duration = false;
                            if(is_plugin_active('bookingpress-custom-service-duration/bookingpress-custom-service-duration.php')){
                                $enable_custom_service_duration = $bookingpress_services->bookingpress_get_service_meta($bookingpress_service_id,'enable_custom_service_duration');                            
                                if(!empty($enable_custom_service_duration) && $enable_custom_service_duration == 'true'){
                                    $has_custom_service_duration = true;
                                }    
                            }
                            if(!$has_custom_service_duration){
                                $service_duration = $this->bookingpress_get_service_duration_text($v2['service_duration'],$v2['service_duration_unit']);                            
                                $bookingpress_services_data[$k]['category_services'][$k2]['service_duration'] = $service_duration;                            
                                $bookingpress_services_data[$k]['category_services'][$k2]['service_max_capacity'] = $bookingpress_service_max_capacity;
                                $bookingpress_services_data[$k]['category_services'][$k2]['service_price_without_currency'] =  $v2['service_price_without_currency'];    
                            }else{                                
                                unset($bookingpress_services_data[$k]['category_services'][$k2]);                                
                            }

						}
					}
				}                
                //$bookingpress_package_services_data[$k] = $bookingpress_services_data[$k];
			}

			$bookingpress_package_vue_data_fields['bookingpress_service_list'] = $bookingpress_services_data;
            $bookingpress_package_vue_data_fields['assign_package_service_form'] = array(
                'assign_service_id' => '',
                'assign_service_name' => '',
                'assign_service_no_of_appointments' => 1,
                'service_duration' => '',
                'is_edit_package_service' => 0,
                'assigned_service_list' => array(),
            );
            $bookingpress_package_vue_data_fields = apply_filters( 'bookingpress_modify_package_vue_fields_data', $bookingpress_package_vue_data_fields );
            echo wp_json_encode($bookingpress_package_vue_data_fields);
        }
        
        /**
         * Function for load package list
         *
         * @return void
         */
        function bookingpress_package_on_load_methods_func(){
            ?>
                this.loadPackages();
            <?php
        }
        
        /**
         * Function for add package vue method in admin
         *
         * @return void
         */
        function bookingpress_package_vue_methods_func(){
            global $bookingpress_notification_duration;
            ?>
                bookingpress_duplicate_package(package_id){

                    const vm2 = this;
                    var bookingpress_dup_package_data = [];
                    bookingpress_dup_package_data.action = "bookingpress_duplicate_package"
                    bookingpress_dup_package_data.package_id = package_id,
                    bookingpress_dup_package_data._wpnonce = '<?php echo esc_html(wp_create_nonce('bpa_wp_nonce')); ?>'

                    axios.post(appoint_ajax_obj.ajax_url, Qs.stringify(bookingpress_dup_package_data))
                    .then(function(response){
                        vm2.$notify({
                            title: response.data.title,
                            message: response.data.msg,
                            type: response.data.variant,
                            customClass: response.data.variant+'_notification',
                            duration:<?php echo intval($bookingpress_notification_duration); ?>,
                        });
                        vm2.loadPackages();
                        vm2.multiplepackageSelection = [];
                        vm2.totalItems = vm2.items.length;
                        if(response.data.duplicate_pack_id != '' || response.data.duplicate_pack_id != undefined){
                            vm2.editpackage(response.data.duplicate_pack_id);
                        }
                    }).catch(function(error){
                        console.log(error)
                        vm2.$notify({
                            title: '<?php esc_html_e('Error', 'bookingpress-package'); ?>',
                            message: '<?php esc_html_e('Something went wrong..', 'bookingpress-package'); ?>',
                            type: 'error',
                            customClass: 'error_notification',
                            duration:<?php echo intval($bookingpress_notification_duration); ?>,
                        });
                    });

                },
                bookingpress_package_change_status(package_id, package_new_status){                    
                    const vm2 = this;
                    var postdata = [];
                    postdata.package_id = package_id;
                    postdata.package_new_status = package_new_status;
                    postdata.action = 'bookingpress_change_package_status';						
                    postdata._wpnonce = '<?php echo esc_html( wp_create_nonce( 'bpa_wp_nonce' ) ); ?>';
                    axios.post( appoint_ajax_obj.ajax_url, Qs.stringify( postdata ) )
                    .then(function(response){

                        vm2.$notify({
                                title: response.data.title,
                                message: response.data.msg,
                                type: response.data.variant,
                                customClass: response.data.variant+'_notification',
                        });

                        if (response.data.variant == 'success') {										
                            vm2.$notify({
                                title: response.data.title,
                                message: response.data.msg,
                                type: response.data.variant,
                                customClass: response.data.variant+'_notification',
                            });
                            vm2.loadPackages();                                
                        }

                    }).catch(function(error){
                        console.log(error);
                        vm2.$notify({
                            title: '<?php esc_html_e( 'Error', 'bookingpress-package' ); ?>',
                            message: '<?php esc_html_e( 'Something went wrong..', 'bookingpress-package' ); ?>',
                            type: 'error',
                            customClass: 'error_notification',
                        });
                    });

                },
                isNumberValidate(evt) {
                    const regex = /^(?!.*(,,|,\.|\.,|\.\.))[\d.,]+$/gm;
                    let m;
                    if((m = regex.exec(evt)) == null ) {
                        this.package.package_price = '';
                    }
                    var price_number_of_decimals = this.price_number_of_decimals;                
                    if((evt != null && evt.indexOf(".")>-1 && (evt.split('.')[1].length > price_number_of_decimals))){
                        this.package.package_price = evt.slice(0, -1);
                    }                
                },
                isValidateZeroDecimal(evt){
                    const vm = this                
                    if (/[^0-9]+/.test(evt)){
                        vm.package.package_price = evt.slice(0, -1);
                    }
                },            
                resetFilter(){
                    const vm = this;
                    vm.search_package_name = '';                    
                    vm.loadPackages();
                    vm.is_multiple_checked = false;
                    vm.multipleSelection = [];
                },            
                async loadPackages() {                    
                    const vm = this;
                    vm.is_display_loader = '1'
                    //var bookingpress_search_data = { 'selected_category_id': this.search_service_category, 'service_name': this.search_service_name }
                    var bookingpress_search_data = {'search_package_name': vm.search_package_name };
                    var postData = { action:'bookingpress_get_packages', perpage:this.perPage, currentpage:this.currentPage, search_data: bookingpress_search_data, _wpnonce:'<?php echo esc_html(wp_create_nonce('bpa_wp_nonce')); ?>' };
                    axios.post( appoint_ajax_obj.ajax_url, Qs.stringify( postData ) )
                    .then( function (response) {
                        vm.is_display_loader = '0'
                        this.items = response.data.items;
                        this.totalItems = response.data.total;
                        setTimeout(function(){
                            vm.bookingpress_remove_focus_for_popover();
                        },1000);
                    }.bind(this) )
                    .catch( function (error) {
                        vm2.is_display_loader = '0'
                        console.log(error);
                        vm2.$notify({
                            title: '<?php esc_html_e('Error', 'bookingpress-package'); ?>',
                            message: '<?php esc_html_e('Something went wrong..', 'bookingpress-package'); ?>',
                            type: 'error',
                            customClass: 'error_notification',
                            duration:<?php echo intval($bookingpress_notification_duration); ?>,
                        });
                    });
                },
                open_add_package_modal(action = 'add') {
                    const vm = this;
                    vm.open_package_modal = true;
                    if(action ==  'add') {
                        vm.modal_loader = 0;
                    }
                    <?php do_action('bookingpress_open_package_modal_after'); ?>
                },
                closepackageModal(){
                    const vm = this;

                    vm.$refs['package'].resetFields()
                    vm.open_package_modal = false;

                    vm.package.package_name = "";                                        
                    vm.package.package_images_list = [];                    
                    vm.package_images_list = [];

                    vm.package.package_duration_unit = "y";
                    vm.package.package_duration_val = "1";
                    vm.package.package_price = "";
                    vm.package.package_purchase_limit = '0';                    
                    vm.package.package_description = "";                    
                    vm.package.package_update_id = 0;

                    vm.package.deleted_packages = [];
                    vm.package.deleted_images = [];

                    vm.package_assigned_services = [];

                },
                bookingpress_upload_package_func(response, file, fileList){
                    const vm2 = this;                    
                    if(response != ''){                                                
                        if(vm2.package.package_images_list.length < 5){
                            var temp_package_images_list = vm2.package.package_images_list;
                            temp_package_images_list.push({"bookingpress_package_img_id":"","image_url": response.upload_url,"image_name": response.upload_file_name});
                            vm2.package.package_images_list = temp_package_images_list;
                        }else{
                            vm2.$notify({
                                title: '<?php esc_html_e('Error', 'bookingpress-package'); ?>',
                                message: '<?php esc_html_e('You can upload up to 5 images.', 'bookingpress-package'); ?>',
                                type: 'error',
                                customClass: 'error_notification',
                                duration:<?php echo intval($bookingpress_notification_duration); ?>,
                            });                            
                        }
                    }
                },
                bookingpress_image_upload_limit(files, fileList){
                    const vm2 = this;
                    if(vm2.package.package_images_list.length > 4){                        
                        vm2.$notify({
                            title: '<?php esc_html_e('Error', 'bookingpress-package'); ?>',
                            message: '<?php esc_html_e('You can upload up to 5 images.', 'bookingpress-package'); ?>',
                            type: 'error',
                            customClass: 'error_notification',
                            duration:<?php echo intval($bookingpress_notification_duration); ?>,
                        });                        
                    }
                },
                bookingpress_image_upload_err(err, file, fileList){
                    const vm2 = this
                    var bookingpress_err_msg = '<?php esc_html_e('Something went wrong', 'bookingpress-package'); ?>';
                    if(err != '' || err != undefined){
                        bookingpress_err_msg = err
                    }
                    vm2.$notify({
                        title: '<?php esc_html_e('Error', 'bookingpress-package'); ?>',
                        message: bookingpress_err_msg,
                        type: 'error',
                        customClass: 'error_notification',
                        duration:<?php echo intval($bookingpress_notification_duration); ?>,
                    });
                },
                bookingpress_remove_package_img(key){
                    const vm2 = this;                    
                    
                    let package_images_data = vm2.package.package_images_list; 
                    var upload_url = package_images_data[key].image_url;
                    var upload_filename = package_images_data[key].image_name;

                    var bookingpress_package_img_id = package_images_data[ key ].bookingpress_package_img_id;
                    if(bookingpress_package_img_id == ""){

                        var postData = { action:'bookingpress_remove_package_file', upload_file_url: upload_url,_wpnonce:'<?php echo esc_html(wp_create_nonce('bpa_wp_nonce')); ?>' };
                        axios.post( appoint_ajax_obj.ajax_url, Qs.stringify( postData ) )
                        .then( function (response) {

                            for( let index in package_images_data ){
                                let elm = package_images_data[ index ];
                                if( key == index ){
                                    var bookingpress_package_img_id = package_images_data[ index ].bookingpress_package_img_id;
                                    vm2.package.package_images_list.splice( index, 1 );
                                    if(bookingpress_package_img_id != ""){
                                        vm2.package.deleted_images.push(bookingpress_package_img_id);
                                    }                                
                                }
                            }                                                                                            
                            vm2.$refs.avatarRef.clearFiles();

                        }.bind(vm2) )
                        .catch( function (error) {
                            console.log(error);
                        });

                    }else{
                        
                        for( let index in package_images_data ){
                            let elm = package_images_data[ index ];
                            if( key == index ){
                                var bookingpress_package_img_id = package_images_data[ index ].bookingpress_package_img_id;
                                vm2.package.package_images_list.splice( index, 1 );
                                if(bookingpress_package_img_id != ""){
                                    vm2.package.deleted_images.push(bookingpress_package_img_id);
                                }                                
                            }
                        } 

                    }
                },
                checkUploadedFile(file){
                    const vm2 = this
                    if(file.type != 'image/jpeg' && file.type != 'image/png' && file.type != 'image/webp'){
                        vm2.$notify({
                            title: '<?php esc_html_e('Error', 'bookingpress-package'); ?>',
                            message: '<?php esc_html_e('Please upload jpg/png file only', 'bookingpress-package'); ?>',
                            type: 'error',
                            customClass: 'error_notification',
                            duration:<?php echo intval($bookingpress_notification_duration); ?>,
                        });                    
                        return false
                    }else{
                        var bpa_image_size = parseInt(file.size / 1000000);
                        if(bpa_image_size > 1){
                            vm2.$notify({
                                title: '<?php esc_html_e('Error', 'bookingpress-package'); ?>',
                                message: '<?php esc_html_e('Please upload maximum 1 MB file only', 'bookingpress-package'); ?>',
                                type: 'error',
                                customClass: 'error_notification',
                                duration:<?php echo intval($bookingpress_notification_duration); ?>,
                            });                    
                            return false
                        }
                    }
                },
                bookingpress_save_package(){
                    const vm = this;
                    this.$refs["package"].validate((valid) => {
                        if (valid) {
                            if(vm.package_assigned_services.length == 0){                                
                                vm.$notify({
                                    title: '<?php esc_html_e('Error', 'bookingpress-package'); ?>',
                                    message: '<?php esc_html_e('Please add service to the package.', 'bookingpress-package'); ?>',
                                    type: 'error',
                                    customClass: 'error_notification',
                                    duration:<?php echo intval($bookingpress_notification_duration); ?>,
                                });
                            }else if(vm.package.package_images_list.length == 0){
                                vm.$notify({
                                    title: '<?php esc_html_e('Error', 'bookingpress-package'); ?>',
                                    message: '<?php esc_html_e('Please add image to the package.', 'bookingpress-package'); ?>',
                                    type: 'error',
                                    customClass: 'error_notification',
                                    duration:<?php echo intval($bookingpress_notification_duration); ?>,
                                });
                            }else{                                
                                var postdata = [];
                                postdata.package_details = vm.package;
                                postdata.action = 'bookingpress_add_package';
                                vm.is_disabled = true;
                                vm.is_display_save_loader = '1';
                                vm.savebtnloading = true;
                                postdata.package_service_details = JSON.stringify(vm.package_assigned_services);
                                <?php do_action('bookingpress_add_package_more_postdata'); ?>
                                postdata._wpnonce = '<?php echo esc_html(wp_create_nonce('bpa_wp_nonce')); ?>';
                                axios.post( appoint_ajax_obj.ajax_url, Qs.stringify( postdata ) )
                                .then(function(response){
                                    if(response.data.variant != 'error'){
                                        vm.closepackageModal();
                                        vm.loadPackages();
                                    }
                                    vm.is_disabled = false;
                                    vm.is_display_save_loader = '0';                            
                                    vm.$notify({
                                        title: response.data.title,
                                        message: response.data.msg,
                                        type: response.data.variant,
                                        customClass: response.data.variant+'_notification',
                                        duration:<?php echo intval($bookingpress_notification_duration); ?>,
                                    });
                                    vm.savebtnloading = false
                                }).catch(function(error){
                                    console.log(error);
                                    vm.$notify({
                                        title: '<?php esc_html_e('Error', 'bookingpress-package'); ?>',
                                        message: '<?php esc_html_e('Something went wrong..', 'bookingpress-package'); ?>',
                                        type: 'error',
                                        customClass: 'error_notification',
                                        duration:<?php echo intval($bookingpress_notification_duration); ?>,
                                    });
                                });
                            }
                        } else {
                            return false;
                        }
                    });
                },
                updatepackagePosition( currentElement ){
                    var new_index = currentElement.newIndex;
                    var old_index = currentElement.oldIndex;
                    var service_id = currentElement.item.dataset.service_id;
                    const vm = this;
                    var postData = { action: 'bookingpress_position_package', old_position: old_index, new_position: new_index, currentPage : this.currentPage, perPage: this.perPage,_wpnonce:'<?php echo esc_html(wp_create_nonce('bpa_wp_nonce')); ?>' };
                    axios.post( appoint_ajax_obj.ajax_url, Qs.stringify(     postData ) )
                    .then(function(response){
                        
                    }).catch(function(error){
                        console.log(error);
                        vm.$notify({
                            title: '<?php esc_html_e('Error', 'bookingpress-package'); ?>',
                            message: '<?php esc_html_e('Something went wrong..', 'bookingpress-package'); ?>',
                            type: 'error',
                            customClass: 'error_notification',
                            duration:<?php echo intval($bookingpress_notification_duration); ?>,
                        });
                    });
                },
                handlepackageSelectionChange( e, isChecked, package_id ){
                    const vm = this                                
                    vm.package_bulk_action = 'bulk_action';
                    if(isChecked){
                        vm.multiplepackageSelection.push(package_id);
                    }else{
                        var removeIndex = vm.multiplepackageSelection.indexOf(package_id);
                        if(removeIndex > -1){
                            vm.multiplepackageSelection.splice(removeIndex, 1);
                        }
                    }
                    if( vm.multiplepackageSelection.length == vm.totalItems ){
                        vm.is_multiple_checked = true;
                    } else {
                        vm.is_multiple_checked = false;
                    }
                },
                clearBulkpackageSelection(){
                    const vm = this
                    vm.package_bulk_action = 'bulk_action';
                    vm.multiplepackageSelection = []
                    vm.items.forEach(function(selectedVal, index, arr) {            
                        selectedVal.selected = false;
                    })
                    vm.is_multiple_checked = false;
                },
                selectAllpackages( isChecked ){
                    const vm = this                
                    let selected_package_parent = '';
                    if( isChecked ){
                        vm.items.forEach( ( selectedVal, index ) =>{
                            if( selectedVal.package_bulk_action == false) {
                                vm.multiplepackageSelection.push(selectedVal.bookingpress_package_id);
                                selectedVal.selected = true;
                            }
                        });
                    } else {
                        vm.clearBulkpackageSelection();
                    }
                },               
                handleSelectionChange(val) {
					this.multiplepackageSelection = val;
					this.bulk_action = 'bulk_action';
				},
				handleSizeChange(val) {
					this.perPage = val
					this.loadPackages()
				},
				handleCurrentChange(val) {
					this.currentPage = val;
					this.loadPackages()
				},
				changeCurrentPage(perPage) {
					var total_item = this.totalItems;
					var recored_perpage = perPage;
					var select_page =  this.currentPage;				
					var current_page = Math.ceil(total_item/recored_perpage);
					if(total_item <= recored_perpage ) {
						current_page = 1;
					} else if(select_page >= current_page ) {
						
					} else {
						current_page = select_page;
					}
					return current_page;
				},
				changePaginationSize(selectedPage) { 	
					var total_recored_perpage = selectedPage;
					var current_page = this.changeCurrentPage(total_recored_perpage);										
					this.perPage = selectedPage;					
					this.currentPage = current_page;	
					this.loadPackages()
				},
                clearBulkAction(){
                    const vm = this
                    vm.bulk_action = 'bulk_action';
                    vm.multiplepackageSelection = []
                    vm.items.forEach(function(selectedVal, index, arr) {            
                        selectedVal.selected = false;
                    })
                    vm.is_multiple_checked = false;
                },
                editpackage(edit_id){
                    const vm2 = this
                    vm2.package.package_update_id = edit_id;
                    vm2.open_add_package_modal('edit');
                    var package_action = { action: 'bookingpress_get_edit_package', edit_id: edit_id, _wpnonce:'<?php echo esc_html(wp_create_nonce('bpa_wp_nonce')); ?>' }
                    axios.post( appoint_ajax_obj.ajax_url, Qs.stringify( package_action ) )
                    .then(function(response){
                        if(response.data.variant == 'success'){                            
                            
                            vm2.package.package_name = response.data.edit_data.bookingpress_package_name;
                            vm2.package.package_duration_val = response.data.edit_data.bookingpress_package_duration;
                            vm2.package.package_duration_unit = response.data.edit_data.bookingpress_package_duration_unit;
                            vm2.package.package_price = response.data.edit_data.bookingpress_package_price;
                            vm2.package.package_purchase_limit = response.data.edit_data.bookingpress_package_customer_purchase_limit;
                            vm2.package.package_description = response.data.edit_data.bookingpress_package_description;                            
                            vm2.package_assigned_services = response.data.edit_data.assigned_service_details;
                            vm2.package.package_images_list = response.data.edit_data.assigned_image_details;

                        } else {
                            vm2.$notify({
                                title: response.data.title,
                                message: response.data.msg,
                                type: response.data.variant,
                                customClass: response.data.variant+'_notification',
                                duration:<?php echo intval($bookingpress_notification_duration); ?>,
                            });                            
                        }
                        <?php do_action('bookingpress_edit_package_more_vue_data'); ?>
                    }).catch(function(error){
                        console.log(error)
                        vm2.$notify({
                            title: '<?php esc_html_e('Error', 'bookingpress-package'); ?>',
                            message: '<?php esc_html_e('Something went wrong..', 'bookingpress-package'); ?>',
                            type: 'error',
                            customClass: 'error_notification',
                            duration:<?php echo intval($bookingpress_notification_duration); ?>,
                        });
                    });
                },
                deletepackage(delete_id){
                    const vm2 = this
                    var package_action = { action: 'bookingpress_delete_package', delete_id: delete_id,_wpnonce:'<?php echo esc_html(wp_create_nonce('bpa_wp_nonce')); ?>' }
                    axios.post( appoint_ajax_obj.ajax_url, Qs.stringify( package_action ) )
                    .then(function(response){
                        vm2.$notify({
                            title: response.data.title,
                            message: response.data.msg,
                            type: response.data.variant,
                            customClass: response.data.variant+'_notification',
                            duration:<?php echo intval($bookingpress_notification_duration); ?>,
                        });
                        vm2.loadPackages()
                    }).catch(function(error){
                        console.log(error)
                        vm2.$notify({
                            title: '<?php esc_html_e('Error', 'bookingpress-package'); ?>',
                            message: '<?php esc_html_e('Something went wrong..', 'bookingpress-package'); ?>',
                            type: 'error',
                            customClass: 'error_notification',
                            duration:<?php echo intval($bookingpress_notification_duration); ?>,
                        });
                    });
                },
                bulk_actions_package(){
                    const vm = this;
                    
                    if( "bulk_action" == vm.package_bulk_action ){
                        vm.$notify({
                            title: '<?php esc_html_e('Error', 'bookingpress-package'); ?>',
                            message: '<?php esc_html_e('Please select any action...', 'bookingpress-package'); ?>',
                            type: 'error',
                            customClass: 'error_notification',
                            duration:<?php echo intval($bookingpress_notification_duration); ?>,
                        });
                    } else if( "delete" == vm.package_bulk_action ){
                        if( 0 < this.multiplepackageSelection.length ){
                            let package_ids = [];
                            this.multiplepackageSelection.forEach( (element,index) =>{
                                let package_id = element;
                                package_ids.push( package_id );
                            });
                            package_ids = JSON.stringify( package_ids );
                            let package_delete_data = {
                                action: 'bookingpress_bulk_package',
                                delete_ids: package_ids,
                                bulk_action: 'delete',
                                _wpnonce: '<?php echo esc_html( wp_create_nonce( 'bpa_wp_nonce') ); ?>'
                            };
                            axios.post( appoint_ajax_obj.ajax_url, Qs.stringify( package_delete_data ) )
                            .then( function(response){
                                vm.$notify({
                                    title: response.data.title,
                                    message: response.data.msg,
                                    type: response.data.variant,
                                    customClass: response.data.variant+'_notification',
                                    duration:<?php echo intval($bookingpress_notification_duration); ?>,

                                });
                                vm.loadPackages();
                                vm.multiplepackageSelection = [];
                                vm.totalItems = vm.items.length
                            }).catch( function(error){
                                console.log(error);
                                vm.$notify({
                                    title: '<?php esc_html_e('Error', 'bookingpress-package'); ?>',
                                    message: '<?php esc_html_e('Something went wrong..', 'bookingpress-package'); ?>',
                                    type: 'error',
                                    customClass: 'error_notification',
                                    duration:<?php echo intval($bookingpress_notification_duration); ?>,
                                });
                            });
                        }

                    } else {

                    }
                },
                bookingpress_package_add_service_model( currentElement ){
                    const vm = this;
                    vm.open_assign_service_package_modal = true;
                    if( typeof vm.bpa_adjust_popup_position != 'undefined' ){
                        vm.bpa_adjust_popup_position( currentElement, 'div#assign_package_service .el-dialog.bpa-dialog-assign-service__is-package' );
                    }
                },
                bookingpress_set_service_duration( selected_value , bookingpress_service_list){
                    const vm = this;
                    if( "undefined" != typeof bookingpress_service_list ){
                        if(selected_value != '' && bookingpress_service_list != '') {
                            for (let x in bookingpress_service_list) {                      
                                var catservlist_arr = bookingpress_service_list[x].category_services;                            
                                for(let y in catservlist_arr) {
                                    var service_id = catservlist_arr[y].service_id;
                                    if(service_id == selected_value){
                                        vm.assign_package_service_form.service_duration = catservlist_arr[y].service_duration;                     
                                        vm.assign_package_service_form.assign_service_name = catservlist_arr[y].service_name;
                                    }                                                 
                                }                      
                            }
                        }                                                               
                    }
                },
                async bookingpress_save_assign_package_service(){
                    const vm = this;
                    let service_form = vm.assign_package_service_form;
                    let error = 0;
                    if( "" == service_form.assign_service_id ){
                        vm.$notify({
                            title: '<?php esc_html_e('Error', 'bookingpress-package'); ?>',
                            message: '<?php esc_html_e('Please select service', 'bookingpress-package'); ?>',
                            type: 'error',
                            customClass: 'error_notification',
                            duration:<?php echo intval($bookingpress_notification_duration); ?>,
                        });
                        return false;
                    }
                    if( 0 == service_form.assign_service_no_of_appointments ){
                        vm.$notify({
                            title: '<?php esc_html_e('Error', 'bookingpress-package'); ?>',
                            message: '<?php esc_html_e('Please set No. Of Appointment', 'bookingpress-package'); ?>',
                            type: 'error',
                            customClass: 'error_notification',
                            duration:<?php echo intval($bookingpress_notification_duration); ?>,
                        });
                        return false;
                    }                                        
                    var package_assigned_services_object = vm.package_assigned_services;
                    let service_added = true;
                    if( service_form.is_edit_package_service == false ){                        
                        Object.entries(package_assigned_services_object).forEach(entry => {
						    const [key, value] = entry;		
                            console.log(entry);					
                            if(package_assigned_services_object[key].service_id == service_form.assign_service_id){
                                vm.$notify({
                                    title: '<?php esc_html_e('Error', 'bookingpress-package'); ?>',
                                    message: '<?php esc_html_e('This service already added in package.', 'bookingpress-package'); ?>',
                                    type: 'error',
                                    customClass: 'error_notification',
                                    duration:<?php echo intval($bookingpress_notification_duration); ?>,
                                });
                                service_added = false;                            
                                return false;
                            }
                        });
                        if(service_added){
                            let new_assigned_service = {};
                            new_assigned_service.service_id = service_form.assign_service_id;
                            new_assigned_service.service_name = service_form.assign_service_name;
                            new_assigned_service.service_duration = service_form.service_duration;
                            new_assigned_service.service_no_of_appointments = service_form.assign_service_no_of_appointments;                                                                        
                            vm.package_assigned_services.push(new_assigned_service);
                        }
                    } else {                        
                        let edit_package_index = service_form.edit_package_service_index;
                        Object.entries(package_assigned_services_object).forEach(entry => {
						    const [key, value] = entry;							
                            if(package_assigned_services_object[key].service_id == service_form.assign_service_id && key != edit_package_index){
                                vm.$notify({
                                    title: '<?php esc_html_e('Error', 'bookingpress-package'); ?>',
                                    message: '<?php esc_html_e('This service already added in package.', 'bookingpress-package'); ?>',
                                    type: 'error',
                                    customClass: 'error_notification',
                                    duration:<?php echo intval($bookingpress_notification_duration); ?>,
                                });    
                                service_added = false;                        
                                return false;
                            }
                        });
                        if(service_added){
                            vm.package_assigned_services[ edit_package_index ].service_no_of_appointments = service_form.assign_service_no_of_appointments;
                            vm.package_assigned_services[ edit_package_index ].service_id = service_form.assign_service_id;
                            vm.package_assigned_services[ edit_package_index ].service_duration = service_form.service_duration;
                            vm.package_assigned_services[ edit_package_index ].service_name = service_form.assign_service_name;
                        }                    
                    }

                    vm.bookingpress_close_assign_package_modal();
                },
                bookingpress_close_assign_package_modal(){
                    const vm = this;
                    vm.assign_package_service_form.assign_service_id = "";
                    vm.assign_package_service_form.assign_service_no_of_appointments = 1;
                    vm.assign_package_service_form.assign_service_name = "";
                    vm.assign_package_service_form.is_edit_package_service = false;
                    vm.assign_package_service_form.service_duration = "";                    
                    vm.open_assign_service_package_modal = false;
                },                
                bookingpress_edit_assigned_package_service( package_service_id, currentElement, key ){
                    const vm = this;
                    let package_service_data = vm.package_assigned_services;
                    for( let index in package_service_data ){
                        let elm = package_service_data[ index ];
                        if( key == index ){
                            vm.assign_package_service_form.assign_service_id = elm.service_id;
                            vm.assign_package_service_form.assign_service_no_of_appointments = elm.service_no_of_appointments;
                            vm.assign_package_service_form.assign_service_name = elm.service_name;                            
                            vm.assign_package_service_form.service_duration = elm.service_duration;
                            vm.assign_package_service_form.is_edit_package_service = true;
                            vm.assign_package_service_form.edit_package_service_id = package_service_id;
                            vm.assign_package_service_form.edit_package_service_index = index;
                            break;
                        }
                    }
                    vm.open_assign_service_package_modal = true;
                    if( typeof vm.bpa_adjust_popup_position != 'undefined' ){
                        vm.bpa_adjust_popup_position( currentElement, 'div#assign_package_service .el-dialog.bpa-dialog-assign-service__is-package' );
                    }
                },
                bookingpress_delete_assigned_package_service( package_service_id, key ){
                    const vm = this;
                    let package_service_data = vm.package_assigned_services;                    
                    for( let index in package_service_data ){
                        let elm = package_service_data[ index ];
                        if( key == index ){
                            vm.package_assigned_services.splice( index, 1 );
                            vm.package.deleted_packages.push( package_service_id );
                        }
                    }
                },
            <?php
            do_action('bookingpress_add_package_dynamic_vue_methods');
        }

        function bookingpress_load_package_view_func() {
			$bookingpress_load_file_name = BOOKINGPRESS_PACKAGE_VIEWS_DIR . '/manage_package.php';
			require $bookingpress_load_file_name;
		}

        function bookingpress_package_page_slugs(){
            global $bookingpress_slugs;
            $bookingpress_slugs->bookingpress_package = 'bookingpress_package';
            $bookingpress_slugs->bookingpress_package_order = 'bookingpress_package_order';
        }

        function bookingpress_add_specific_menu_after_payment_menu_func($bookingpress_slugs){
            global $BookingPress;
            add_submenu_page( $bookingpress_slugs->bookingpress, __( 'Packages Order', 'bookingpress-package' ), __( 'Package Order', 'bookingpress-package' ), 'bookingpress_package_order', $bookingpress_slugs->bookingpress_package_order, array( $BookingPress, 'route' ) );
        }

        function bookingpress_add_specific_menu_func($bookingpress_slugs){
            global $BookingPress;
            add_submenu_page( $bookingpress_slugs->bookingpress, __( 'Packages', 'bookingpress-package' ), __( 'Packages', 'bookingpress-package' ), 'bookingpress_package', $bookingpress_slugs->bookingpress_package, array( $BookingPress, 'route' ) );            
        }

        public static function install(){

            global $wpdb, $bookingpress_package_version, $BookingPress, $tbl_bookingpress_customize_settings,$bookingpress_package, $tbl_bookingpress_notifications;

            $bookingpress_package_tmp_version = get_option('bookingpress_package_version');

            if (!isset($bookingpress_package_tmp_version) || $bookingpress_package_tmp_version == '') {


                $myaddon_name = "bookingpress-package/bookingpress-package.php";
                
                // activate license for this addon
                $posted_license_key = trim( get_option( 'bkp_license_key' ) );
			    $posted_license_package = '32087';

                $api_params = array(
                    'edd_action' => 'activate_license',
                    'license'    => $posted_license_key,
                    'item_id'  => $posted_license_package,
                    //'item_name'  => urlencode( BOOKINGPRESS_ITEM_NAME ), // the name of our product in EDD
                    'url'        => home_url()
                );

                // Call the custom API.
                $response = array('response'=>array('code'=>200,'message'=>'ok'),'body'=>'{"success": true,"license":"valid","expires": "1970-01-01 23:59:59","customer_name":"GPL","customer_email":"test@test.org","license_limit": 1000}');

                //echo "<pre>";print_r($response); echo "</pre>"; exit;

                // make sure the response came back okay
                $message = "";
                if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {
                    $message =  ( is_wp_error( $response ) && ! empty( $response->get_error_message() ) ) ? $response->get_error_message() : __( 'An error occurred, please try again.','bookingpress-package' );
                } else {
                    $license_data = json_decode( wp_remote_retrieve_body( $response ) );
                    $license_data_string = wp_remote_retrieve_body( $response );
                    if ( false === $license_data->success ) {
                        switch( $license_data->error ) {
                            case 'expired' :
                                $message = sprintf(
                                    __( 'Your license key expired on %s.','bookingpress-package' ),
                                    date_i18n( get_option( 'date_format' ), strtotime( $license_data->expires, current_time( 'timestamp' ) ) )
                                );
                                break;
                            case 'revoked' :
                                $message = __( 'Your license key has been disabled.','bookingpress-package' );
                                break;
                            case 'missing' :
                                $message = __( 'Invalid license.','bookingpress-package' );
                                break;
                            case 'invalid' :
                            case 'site_inactive' :
                                $message = __( 'Your license is not active for this URL.','bookingpress-package' );
                                break;
                            case 'item_name_mismatch' :
                                $message = __('This appears to be an invalid license key for your selected package.','bookingpress-package');
                                break;
                            case 'invalid_item_id' :
                                    $message = __('This appears to be an invalid license key for your selected package.','bookingpress-package');
                                    break;
                            case 'no_activations_left':
                                $message = __( 'Your license key has reached its activation limit.','bookingpress-package' );
                                break;
                            default :
                                $message = __( 'An error occurred, please try again.','bookingpress-package' );
                                break;
                        }

                    }

                }

                if ( ! empty( $message ) ) {
                    update_option( 'bkp_package_license_data_activate_response', $license_data_string );
                    update_option( 'bkp_package_license_status', $license_data->license );
                    deactivate_plugins($myaddon_name, FALSE);
                    $redirect_url = network_admin_url('plugins.php?deactivate=true&bkp_license_deactivate=true&bkp_deactivate_plugin='.$myaddon_name);
                    $bpa_dact_message = __('Please activate license of BookingPress premium plugin to use BookingPress Service Package Add-on', 'bookingpress-package');
					$bpa_link = sprintf( __('Please %s Click Here %s to Continue', 'bookingpress-package'), '<a href="javascript:void(0)" onclick="window.location.href=\'' . $redirect_url . '\'">', '</a>');
					wp_die('<p>'.$bpa_dact_message.'<br/>'.$bpa_link.'</p>');
                    die;
                }
                
                if($license_data->license === "valid")
                {
                    update_option( 'bkp_package_license_key', $posted_license_key );
                    update_option( 'bkp_package_license_package', $posted_license_package );
                    update_option( 'bkp_package_license_status', $license_data->license );
                    update_option( 'bkp_package_license_data_activate_response', $license_data_string );
                }




                update_option('bookingpress_package_version', $bookingpress_package_version);

                require_once ABSPATH . 'wp-admin/includes/upgrade.php';
				@set_time_limit( 0 );

				$charset_collate = '';
				if ( $wpdb->has_cap( 'collation' ) ) {
					if ( ! empty( $wpdb->charset ) ) {
						$charset_collate = "DEFAULT CHARACTER SET $wpdb->charset";
					}
					if ( ! empty( $wpdb->collate ) ) {
						$charset_collate .= " COLLATE $wpdb->collate";
					}
				}
                                                
                $bookingpress_dbtbl_create = array();
                $tbl_bookingpress_packages = $wpdb->prefix.'bookingpress_packages';
                $sql_table = "CREATE TABLE IF NOT EXISTS `{$tbl_bookingpress_packages}`(
					`bookingpress_package_id` int(11) NOT NULL AUTO_INCREMENT,
					`bookingpress_package_name` varchar(255) NOT NULL,
					`bookingpress_package_description` TEXT DEFAULT NULL,                    
                    `bookingpress_package_price` DOUBLE NOT NULL,
                    `bookingpress_package_calculated_price` DOUBLE NOT NULL,
                    `bookingpress_package_duration` INT(11) NOT NULL DEFAULT 0,
                    `bookingpress_package_duration_unit` VARCHAR(1) NOT NULL,
                    `bookingpress_package_customer_purchase_limit` INT(11) NOT NULL,
                    `bookingpress_package_status` smallint(1) DEFAULT 1,
                    `bookingpress_package_position` INT(11) NOT NULL,                    
					`bookingpress_package_created_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
					PRIMARY KEY (`bookingpress_package_id`)
				) {$charset_collate}";
				$bookingpress_dbtbl_create[ $tbl_bookingpress_packages ] = dbDelta( $sql_table );

                $tbl_bookingpress_package_services = $wpdb->prefix.'bookingpress_package_services';
                $sql_table = "CREATE TABLE IF NOT EXISTS `{$tbl_bookingpress_package_services}`(
					`bookingpress_package_service_id` int(11) NOT NULL AUTO_INCREMENT,
                    `bookingpress_package_id` INT(11) NOT NULL,
                    `bookingpress_service_id` INT(11) NOT NULL,
                    `bookingpress_service_price` DOUBLE NOT NULL,
                    `bookingpress_no_of_appointments` INT(11) NOT NULL,
					`bookingpress_package_service_created_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
					PRIMARY KEY (`bookingpress_package_service_id`)
				) {$charset_collate}";
				$bookingpress_dbtbl_create[ $tbl_bookingpress_package_services ] = dbDelta( $sql_table );                  

                $tbl_bookingpress_package_images = $wpdb->prefix.'bookingpress_package_images';
                $sql_table = "CREATE TABLE IF NOT EXISTS `{$tbl_bookingpress_package_images}`(
					`bookingpress_package_img_id` int(11) NOT NULL AUTO_INCREMENT,
                    `bookingpress_package_id` INT(11) NOT NULL DEFAULT 0,
                    `bookingpress_package_img_name` TEXT DEFAULT NULL,
                    `bookingpress_package_img_url` TEXT DEFAULT NULL,
					`bookingpress_package_img_created_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
					PRIMARY KEY (`bookingpress_package_img_id`)
				) {$charset_collate}";
				$bookingpress_dbtbl_create[ $tbl_bookingpress_package_images ] = dbDelta( $sql_table );           
                
                /* Added "bookingpress_entries" table new column */    
                $tbl_bookingpress_entries = $wpdb->prefix . 'bookingpress_entries';                                
                $bookingpress_is_bookingpress_purchase_type_entry_exists = $wpdb->get_row("SHOW COLUMNS FROM {$tbl_bookingpress_entries} LIKE 'bookingpress_purchase_type'");// phpcs:ignore 
                if(empty($bookingpress_is_bookingpress_purchase_type_entry_exists)){
                    $wpdb->query( "ALTER TABLE {$tbl_bookingpress_entries} ADD bookingpress_purchase_type SMALLINT NOT NULL DEFAULT 1 AFTER bookingpress_complete_payment_token" ); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared --Reason: $tbl_bookingpress_entries is a table name. false alarm
                }
               
                $bookingpress_is_package_id_entry_exists = $wpdb->get_row("SHOW COLUMNS FROM {$tbl_bookingpress_entries} LIKE 'bookingpress_package_id'");// phpcs:ignore 
                if(empty($bookingpress_is_package_id_entry_exists)){
                    $wpdb->query( "ALTER TABLE {$tbl_bookingpress_entries} ADD bookingpress_package_id INT(11) NOT NULL DEFAULT 0 AFTER bookingpress_purchase_type" ); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared --Reason: $tbl_bookingpress_entries is a table name. false alarm
                }
                 /* New Logic added start */                
                $bookingpress_is_package_no_entry_exists = $wpdb->get_row("SHOW COLUMNS FROM {$tbl_bookingpress_entries} LIKE 'bookingpress_package_no'");// phpcs:ignore 
                if(empty($bookingpress_is_package_no_entry_exists)){
                    $wpdb->query( "ALTER TABLE {$tbl_bookingpress_entries} ADD bookingpress_package_no INT(11) NOT NULL DEFAULT 0 AFTER bookingpress_purchase_type" ); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared --Reason: $tbl_bookingpress_entries is a table name. false alarm
                }
                /* New Logic added over */
                $bookingpress_is_package_details_exists = $wpdb->get_row("SHOW COLUMNS FROM {$tbl_bookingpress_entries} LIKE 'bookingpress_package_details'");// phpcs:ignore 
                if(empty($bookingpress_is_package_details_exists)){
                    $wpdb->query( "ALTER TABLE {$tbl_bookingpress_entries} ADD bookingpress_package_details text DEFAULT NULL AFTER bookingpress_package_id" ); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared --Reason: $tbl_bookingpress_entries is a table name. false alarm
                }
                $bookingpress_is_applied_package_data_entry_exists = $wpdb->get_row("SHOW COLUMNS FROM {$tbl_bookingpress_entries} LIKE 'bookingpress_applied_package_data'");// phpcs:ignore 
                if(empty($bookingpress_is_applied_package_data_entry_exists)){
                    $wpdb->query( "ALTER TABLE {$tbl_bookingpress_entries} ADD bookingpress_applied_package_data text DEFAULT NULL AFTER bookingpress_package_id" ); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared --Reason: $tbl_bookingpress_entries is a table name. false alarm
                }
                $bookingpress_is_package_discount_amount_entry_exists = $wpdb->get_row("SHOW COLUMNS FROM {$tbl_bookingpress_entries} LIKE 'bookingpress_package_discount_amount'");// phpcs:ignore 
                if(empty($bookingpress_is_package_discount_amount_entry_exists)){
                    $wpdb->query( "ALTER TABLE {$tbl_bookingpress_entries} ADD bookingpress_package_discount_amount float DEFAULT 0 AFTER bookingpress_applied_package_data" ); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared --Reason: $tbl_bookingpress_entries is a table name. false alarm
                }

                $tbl_bookingpress_appointment_bookings = $wpdb->prefix . 'bookingpress_appointment_bookings';
                $bookingpress_is_bookingpress_purchase_type_entry_exists = $wpdb->get_row("SHOW COLUMNS FROM {$tbl_bookingpress_appointment_bookings} LIKE 'bookingpress_purchase_type'");// phpcs:ignore 
                if(empty($bookingpress_is_bookingpress_purchase_type_entry_exists)){
                    $wpdb->query( "ALTER TABLE {$tbl_bookingpress_appointment_bookings} ADD bookingpress_purchase_type SMALLINT NOT NULL DEFAULT 1 AFTER bookingpress_complete_payment_token" ); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared --Reason: $tbl_bookingpress_entries is a table name. false alarm
                }    
                $bookingpress_is_package_id_entry_exists = $wpdb->get_row("SHOW COLUMNS FROM {$tbl_bookingpress_appointment_bookings} LIKE 'bookingpress_package_id'");// phpcs:ignore 
                if(empty($bookingpress_is_package_id_entry_exists)){
                    $wpdb->query( "ALTER TABLE {$tbl_bookingpress_appointment_bookings} ADD bookingpress_package_id INT(11) NOT NULL DEFAULT 0 AFTER bookingpress_purchase_type" ); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared --Reason: $tbl_bookingpress_entries is a table name. false alarm
                }
                $bookingpress_is_applied_package_data_entry_exists = $wpdb->get_row("SHOW COLUMNS FROM {$tbl_bookingpress_appointment_bookings} LIKE 'bookingpress_applied_package_data'");// phpcs:ignore 
                if(empty($bookingpress_is_applied_package_data_entry_exists)){
                    $wpdb->query( "ALTER TABLE {$tbl_bookingpress_appointment_bookings} ADD bookingpress_applied_package_data text DEFAULT NULL AFTER bookingpress_package_id" ); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared --Reason: $tbl_bookingpress_entries is a table name. false alarm
                }
                $bookingpress_is_package_discount_amount_entry_exists = $wpdb->get_row("SHOW COLUMNS FROM {$tbl_bookingpress_appointment_bookings} LIKE 'bookingpress_package_discount_amount'");// phpcs:ignore 
                if(empty($bookingpress_is_package_discount_amount_entry_exists)){
                    $wpdb->query( "ALTER TABLE {$tbl_bookingpress_appointment_bookings} ADD bookingpress_package_discount_amount float DEFAULT 0 AFTER bookingpress_applied_package_data" ); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared --Reason: $tbl_bookingpress_entries is a table name. false alarm
                }

                $tbl_bookingpress_payment_logs         = $wpdb->prefix . 'bookingpress_payment_transactions';
                $bookingpress_is_bookingpress_purchase_type_entry_exists = $wpdb->get_row("SHOW COLUMNS FROM {$tbl_bookingpress_payment_logs} LIKE 'bookingpress_purchase_type'");// phpcs:ignore 
                if(empty($bookingpress_is_bookingpress_purchase_type_entry_exists)){
                    $wpdb->query( "ALTER TABLE {$tbl_bookingpress_payment_logs} ADD bookingpress_purchase_type SMALLINT NOT NULL DEFAULT 1 AFTER bookingpress_complete_payment_token" ); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared --Reason: $tbl_bookingpress_entries is a table name. false alarm
                }    
                $bookingpress_is_package_id_entry_exists = $wpdb->get_row("SHOW COLUMNS FROM {$tbl_bookingpress_payment_logs} LIKE 'bookingpress_package_id'");// phpcs:ignore 
                if(empty($bookingpress_is_package_id_entry_exists)){
                    $wpdb->query( "ALTER TABLE {$tbl_bookingpress_payment_logs} ADD bookingpress_package_id INT(11) NOT NULL DEFAULT 0 AFTER bookingpress_purchase_type" ); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared --Reason: $tbl_bookingpress_entries is a table name. false alarm
                }                
                $bookingpress_is_package_booking_ref_entry_exists = $wpdb->get_row("SHOW COLUMNS FROM {$tbl_bookingpress_payment_logs} LIKE 'bookingpress_package_order_booking_ref'");// phpcs:ignore 
                if(empty($bookingpress_is_package_booking_ref_entry_exists)){
                    $wpdb->query( "ALTER TABLE {$tbl_bookingpress_payment_logs} ADD bookingpress_package_order_booking_ref bigint(11) DEFAULT 0 AFTER bookingpress_purchase_type" ); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared --Reason: $tbl_bookingpress_entries is a table name. false alarm
                }                
                $bookingpress_is_bookingpress_package_price_exists = $wpdb->get_row("SHOW COLUMNS FROM {$tbl_bookingpress_payment_logs} LIKE 'bookingpress_package_price'");// phpcs:ignore 
                if(empty($bookingpress_is_bookingpress_package_price_exists)){
                    $wpdb->query( "ALTER TABLE {$tbl_bookingpress_payment_logs} ADD bookingpress_package_price DOUBLE NOT NULL DEFAULT 0 AFTER bookingpress_purchase_type" ); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared --Reason: $tbl_bookingpress_entries is a table name. false alarm
                }
                $bookingpress_is_bookingpress_package_name_exists = $wpdb->get_row("SHOW COLUMNS FROM {$tbl_bookingpress_payment_logs} LIKE 'bookingpress_package_name'");// phpcs:ignore 
                if(empty($bookingpress_is_bookingpress_package_name_exists)){
                    $wpdb->query( "ALTER TABLE {$tbl_bookingpress_payment_logs} ADD bookingpress_package_name varchar(255) DEFAULT NULL AFTER bookingpress_purchase_type" ); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared --Reason: $tbl_bookingpress_entries is a table name. false alarm
                }                
                $bookingpress_is_applied_package_data_exists = $wpdb->get_row("SHOW COLUMNS FROM {$tbl_bookingpress_payment_logs} LIKE 'bookingpress_applied_package_data'");// phpcs:ignore 
                if(empty($bookingpress_is_applied_package_data_exists)){
                    $wpdb->query( "ALTER TABLE {$tbl_bookingpress_payment_logs} ADD bookingpress_applied_package_data text DEFAULT NULL AFTER bookingpress_purchase_type" ); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared --Reason: $tbl_bookingpress_entries is a table name. false alarm
                }
                $bookingpress_is_package_discount_amount_entry_exists = $wpdb->get_row("SHOW COLUMNS FROM {$tbl_bookingpress_payment_logs} LIKE 'bookingpress_package_discount_amount'");// phpcs:ignore 
                if(empty($bookingpress_is_package_discount_amount_entry_exists)){
                    $wpdb->query( "ALTER TABLE {$tbl_bookingpress_payment_logs} ADD bookingpress_package_discount_amount float DEFAULT 0 AFTER bookingpress_applied_package_data" ); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared --Reason: $tbl_bookingpress_entries is a table name. false alarm
                }


                $tbl_bookingpress_form_fields          = $wpdb->prefix . 'bookingpress_form_fields';
                $bookingpress_field_is_package_hide_exists = $wpdb->get_row("SHOW COLUMNS FROM {$tbl_bookingpress_form_fields} LIKE 'bookingpress_field_is_package_hide'");// phpcs:ignore 
                if(empty($bookingpress_field_is_package_hide_exists)){
                    $wpdb->query( "ALTER TABLE {$tbl_bookingpress_form_fields} ADD bookingpress_field_is_package_hide TINYINT(1) DEFAULT 0 AFTER bookingpress_field_is_hide" ); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared --Reason: $tbl_bookingpress_entries is a table name. false alarm
                }

                /* Added new package booking table for front side booking */
                $tbl_bookingpress_package_bookings = $wpdb->prefix.'bookingpress_package_bookings';                 
                
                $sql_table = "CREATE TABLE IF NOT EXISTS `{$tbl_bookingpress_package_bookings}`(
                    `bookingpress_package_booking_id` bigint(11) NOT NULL AUTO_INCREMENT,
                    `bookingpress_package_no` varchar(255) DEFAULT NULL,
                    `bookingpress_entry_id` bigint(11) DEFAULT NULL,
                    `bookingpress_payment_id` bigint(11) DEFAULT 0,
                    `bookingpress_customer_id` bigint(11) NOT NULL,
                    `bookingpress_login_user_id` bigint(11) NOT NULL,
                    `bookingpress_customer_name` varchar(255) DEFAULT NULL,
                    `bookingpress_username` varchar(255) DEFAULT NULL,
                    `bookingpress_customer_phone` varchar(255) DEFAULT NULL,
                    `bookingpress_customer_firstname` varchar(255) DEFAULT NULL,
                    `bookingpress_customer_lastname` varchar(255) DEFAULT NULL,
                    `bookingpress_customer_country` VARCHAR(60) DEFAULT NULL,
                    `bookingpress_customer_phone_dial_code` VARCHAR(5) DEFAULT NULL,
                    `bookingpress_customer_email` varchar(255) DEFAULT NULL,
                    `bookingpress_package_id` INT(11) NOT NULL,
					`bookingpress_package_name` varchar(255) NOT NULL,
					`bookingpress_package_description` TEXT DEFAULT NULL,                    
                    `bookingpress_package_price` DOUBLE NOT NULL,
                    `bookingpress_package_calculated_price` DOUBLE NOT NULL,
                    `bookingpress_package_duration` INT(11) NOT NULL DEFAULT 0,
                    `bookingpress_package_duration_unit` VARCHAR(1) NOT NULL,
                    `bookingpress_package_customer_purchase_limit` INT(11) NOT NULL,
                    `bookingpress_package_status` smallint(1) DEFAULT 1,                    
                    `bookingpress_package_purchase_date` DATE NOT NULL,
                    `bookingpress_package_purchase_time` TIME NOT NULL,
                    `bookingpress_package_expiration_date` DATE NOT NULL,
                    `bookingpress_package_services` TEXT DEFAULT NULL,
                    `bookingpress_package_currency` varchar(100) NOT NULL,
                    `bookingpress_package_internal_note` TEXT DEFAULT NULL,                    
                    `bookingpress_package_send_notification` TINYINT(1) DEFAULT 0,
                    `bookingpress_package_booking_status` smallint(1) DEFAULT 1,	
                    `bookingpress_tax_percentage` float DEFAULT 0,
                    `bookingpress_tax_amount` float DEFAULT 0,
                    `bookingpress_price_display_setting` varchar(20) DEFAULT 'exclude_taxes',
                    `bookingpress_tip_amount` float DEFAULT 0,                    
                    `bookingpress_included_tax_label` varchar(255) DEFAULT NULL,
                    `bookingpress_display_tax_order_summary` smallint(6) DEFAULT 1, 
                    `bookingpress_package_paid_amount` float DEFAULT 0,
                    `bookingpress_mark_as_paid` smallint(1) DEFAULT 1,
                    `bookingpress_complete_payment_url_selection` varchar(20) DEFAULT NULL,
                    `bookingpress_complete_payment_url_selection_method` varchar(20) DEFAULT NULL,
                    `bookingpress_complete_payment_token` varchar(255) DEFAULT NULL,
                    `bookingpress_package_timezone` varchar(50) DEFAULT NULL,
                    `bookingpress_package_dst_timezone` TINYINT NULL DEFAULT '0',
                    `bookingpress_package_token` varchar(50) DEFAULT NULL, 
                    `bookingpress_package_due_amount` float DEFAULT 0,
                    `bookingpress_package_total_amount` float DEFAULT 0,                   
                    `bookingpress_package_created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
                    PRIMARY KEY (`bookingpress_package_booking_id`)
                ) {$charset_collate}";
                $bookingpress_dbtbl_create[ $tbl_bookingpress_package_bookings ] = dbDelta($sql_table);
                
                $tbl_bookingpress_package_bookings_meta = $wpdb->prefix.'bookingpress_package_bookings_meta';
                $sql_table = "CREATE TABLE IF NOT EXISTS `{$tbl_bookingpress_package_bookings_meta}`(
                    `bookingpress_package_bookings_meta_id` bigint(11) NOT NULL AUTO_INCREMENT,
                    `bookingpress_entry_id` bigint(11) DEFAULT 0,                    
                    `bookingpress_package_booking_id` bigint(11) DEFAULT 0,
                    `bookingpress_package_meta_key` varchar(255) NOT NULL,
                    `bookingpress_package_meta_value` TEXT DEFAULT NULL,
                    `bookingpress_package_meta_created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                    PRIMARY KEY (`bookingpress_package_bookings_meta_id`)
                ) {$charset_collate}";
                $bookingpress_dbtbl_create[ $tbl_bookingpress_package_bookings_meta ] = dbDelta( $sql_table );                
                $bookingpress_package->bookingpress_package_install_default_pages();                
            }

            $bookingpress_default_appointment_booking_page_url = '';
            $default_appointment_booking_page = $BookingPress->bookingpress_get_customize_settings('default_booking_page', 'booking_form');
            if(!empty($default_appointment_booking_page)){                
                $bookingpress_default_appointment_booking_page_url = get_permalink($default_appointment_booking_page);
            }            

            $booking_form = array(
                'package_form_title' => esc_html__('Packages', 'bookingpress-package'),
				'package_search_placeholder' => esc_html__('What are you looking for..', 'bookingpress-package'),
                'no_package_found_msg' => esc_html__('No Packages Found!', 'bookingpress-package'),
				'package_search_button' => esc_html__('Search', 'bookingpress-package'),
				'package_buy_now_nutton_text'	=> esc_html__('Buy Now', 'bookingpress-package'),
				'package_services_include_text'	=> esc_html__('Services Includes', 'bookingpress-package'),
                'package_services_show_more_text'	=> esc_html__('Show More', 'bookingpress-package'),
                'package_services_show_less_text'	=> esc_html__('Show Less', 'bookingpress-package'),
                'hide_image_indicator' => false,
                'auto_scroll_image' => false,
                'hide_real_price' => false,
                'hide_package_description' => false,
                'hide_package_pagination' => false,
                'auto_scroll_image_interval' => 5,
                'user_details_step_label' => esc_html__('User Details', 'bookingpress-package'),
                'login_form_title_label' => esc_html__('Please Login', 'bookingpress-package'),
                'login_form_username_field_label' => esc_html__('Username', 'bookingpress-package'),
                'login_form_username_field_placeholder' => esc_html__('Enter your email address', 'bookingpress-package'),
                'login_form_password_field_label' => esc_html__('Password', 'bookingpress-package'),
                'login_form_password_field_placeholder' => esc_html__('Enter your password', 'bookingpress-package'),
                'login_form_username_required_field_label' => esc_html__('Please enter username', 'bookingpress-package'),
                'login_form_password_required_field_label' => esc_html__('Please enter password', 'bookingpress-package'),
                'login_form_signup_link_text' => esc_html__('SignUp', 'bookingpress-package'),
                'login_form_dont_have_acc_text' => esc_html__('Don\'t have an account?', 'bookingpress-package'),
                'remember_me_field_label' => esc_html__('Remember Me', 'bookingpress-package'),
                'login_button_label' => esc_html__('Login', 'bookingpress-package'),
                'forgot_password_link_label' => esc_html__('Forgot Password?', 'bookingpress-package'),
                'login_error_message_label' => esc_html__('The Username/ Password you entered is invalid.', 'bookingpress-package'),
                'forgot_password_form_title' => esc_html__('Forgot Password', 'bookingpress-package'),
                'forgot_password_email_address_field_label' => esc_html__('Username OR Email Address', 'bookingpress-package'),
                'forgot_password_email_address_placeholder' => esc_html__('Enter email address', 'bookingpress-package'),
                'forgot_password_email_required_field_label' => esc_html__('Please enter email address', 'bookingpress-package'),
                'forgot_password_button_label' => esc_html__('Submit', 'bookingpress-package'),
                'forgot_password_error_message' => esc_html__('There is no user registered with that email address/Username.', 'bookingpress-package'),
                'forgot_password_success_message_label' => esc_html__('We have sent you a password reset link, Please check your mail.', 'bookingpress-package'),
                'forgot_password_sing_in_link_label' => esc_html__('Sign In', 'bookingpress-package'),
                'signup_account_form_title' => esc_html__('Create Account', 'bookingpress-package'),
                'signup_form_button_title' => esc_html__('Sign Up', 'bookingpress-package'),
                'signup_form_already_have_acc_text' => esc_html__('Already have an account?', 'bookingpress-package'),
                'signup_form_login_link_text' => esc_html__('Login', 'bookingpress-package'),
                'signup_account_fullname_label' => esc_html__('Full name', 'bookingpress-package'),
                'signup_account_email_label' => esc_html__('Email', 'bookingpress-package'), 
                'signup_account_mobile_number_label' => esc_html__('Mobile number', 'bookingpress-package'), 
                'signup_account_password_label' => esc_html__('Password', 'bookingpress-package'), 
                'signup_account_fullname_placeholder' => esc_html__('Enter your full name', 'bookingpress-package'), 
                'signup_account_email_placeholder' => esc_html__('Enter your email', 'bookingpress-package'), 
                'signup_account_password_placeholder' => esc_html__('Enter your password', 'bookingpress-package'),
                'signup_account_email_required_message' => esc_html__('Please enter email address', 'bookingpress-package'),
                'signup_account_mobile_number_required_message' => esc_html__('Please enter mobile number', 'bookingpress-package'), 
                'signup_account_password_required_message' => esc_html__('Please enter password', 'bookingpress-package'), 
                'signup_wrong_email_message' => esc_html__('Email address not valid.', 'bookingpress-package'), 
                'signup_email_exists' => esc_html__('Email address already exists.', 'bookingpress-package'), 
                'signup_account_fullname_required_message' => esc_html__('Please enter fullname', 'bookingpress-package'),
                'basic_details_form_title' => esc_html__('Basic Details', 'bookingpress-package'),
                'basic_details_submit_button_title' => esc_html__('Submit', 'bookingpress-package'),
                'make_payment_tab_title' => esc_html__('Payment', 'bookingpress-package'),
                'make_payment_form_title' => esc_html__('Make a Payment', 'bookingpress-package'),
                'make_payment_subtotal_text' => esc_html__('Subtotal', 'bookingpress-package'),
                'make_payment_total_amount_text' => esc_html__('Total Amount', 'bookingpress-package'),
                'make_payment_select_payment_method_text' => esc_html__('Select Payment Method', 'bookingpress-package'),
                'make_payment_buy_package_btn_text' => esc_html__('Buy Package', 'bookingpress-package'),
                'summary_step_title' => esc_html__('Summary', 'bookingpress-package'),
                'summary_step_form_title' => esc_html__('Summary', 'bookingpress-package'),
                'summary_tab_booking_id_text' => esc_html__('Booking Id', 'bookingpress-package'),
                'summary_tab_package_booked_success_message' => esc_html__('Your package booked successfully!', 'bookingpress-package'),
                'summary_tab_package_booking_information_sent_message' => esc_html__('We have sent your booking information to your email address.', 'bookingpress-package'),
                'summary_tab_package_title_text' => esc_html__('Package', 'bookingpress-package'),
                'summary_tab_customer_title' => esc_html__('Customer Name', 'bookingpress-package'),
                'summary_tab_book_appointment_btn_text' => esc_html__('Book Appointment', 'bookingpress-package'),
                'pkg_card_details_text' => esc_html__('Card Details', 'bookingpress-package'), 
                'pkg_card_number_text'  => esc_html__('Card Number', 'bookingpress-package'), 
                'pkg_expire_month_text'  => esc_html__('Expire Month', 'bookingpress-package'), 
                'pkg_expire_year_text'  => esc_html__('Expire Year', 'bookingpress-package'), 
                'pkg_cvv_text'  => esc_html__('CVV', 'bookingpress-package'), 
                'pkg_card_name_text'  => esc_html__('Name on card', 'bookingpress-package'),
                'stripe_text'  => __('Credit Card', 'bookingpress-package'),
                'package_tax_title'	=> esc_html__('Tax', 'bookingpress-package'),
                'package_tip_label_txt' => esc_html__('Give a tip', 'bookingpress-package'),
                'package_tip_placeholder_txt' => esc_html__('Enter tip amount', 'bookingpress-package'),
                'package_tip_button_txt' => esc_html__('Apply', 'bookingpress-package'),
                'package_tip_applied_title' => esc_html__('Tip Applied', 'bookingpress-package'),
                'package_tip_error_msg' => esc_html__('Please enter tip amount', 'bookingpress-package'),
                'package_go_back_button_text' => esc_html__('Go back', 'bookingpress-package'),    
                'package_month_text' => esc_html__('Month', 'bookingpress-package'),
                'package_months_text' => esc_html__('Months', 'bookingpress-package'),
                'package_year_text' => esc_html__('Year', 'bookingpress-package'),
                'package_years_text' => esc_html__('Years', 'bookingpress-package'),
                'package_day_text' => esc_html__('Day', 'bookingpress-package'),
                'package_days_text' => esc_html__('Days', 'bookingpress-package'),                
                'package_desc_show_less_text' => esc_html__('Show Less', 'bookingpress-package'),    
                'package_desc_read_more_text' => esc_html__('Read More', 'bookingpress-package'),    
		        'package_appointment_book_redirect' => $bookingpress_default_appointment_booking_page_url,
                'package_order_payment_failed_message' => esc_html__('Sorry! Something went wrong. Your payment has been failed.', 'bookingpress-package'),
                'pkg_paypal_text' => esc_html__('PayPal', 'bookingpress-package'),
                'package_purchase_limit_message' => esc_html__('The package purchase limit has been exceeded.', 'bookingpress-package'),
            );

            foreach($booking_form as $key => $value) {
                $bookingpress_get_customize_text = $BookingPress->bookingpress_get_customize_settings($key, 'package_booking_form');
                if(empty($bookingpress_get_customize_text)){
                    $bookingpress_customize_settings_db_fields = array(
                        'bookingpress_setting_name'  => $key,
                        'bookingpress_setting_value' => $value,
                        'bookingpress_setting_type'  => 'package_booking_form',
                    );
                    $wpdb->insert( $tbl_bookingpress_customize_settings, $bookingpress_customize_settings_db_fields );
                }
			}	

            $my_booking_my_package_fields = array(
                'my_package_tab_title' => __('My Package', 'bookingpress-package'),
                'my_package_title' => __('My Package', 'bookingpress-package'),
				'my_package_search_start_date_title' => __('Please select date', 'bookingpress-package'),
				'my_package_search_end_date_title' => __('Please select date', 'bookingpress-package'),
				'my_package_search_package_text_placeholder'	=> __('Search package', 'bookingpress-package'),
				'my_package_search_package_btn_placeholder'	=> __('Apply', 'bookingpress-package'),
                'my_package_id_title' => __('ID', 'bookingpress-package'),
                'my_package_pkg_name_title' => __('Name', 'bookingpress-package'),
                'my_package_pkg_remin_appointment_title' => __('Remaining Appo.', 'bookingpress-package'),
				'my_package_pkg_expoire_on_title'	=> __('Expire on', 'bookingpress-package'),
				'my_package_pkg_payment_title'	=> __('Payment', 'bookingpress-package'),
                'my_package_booking_id_title' => __('Booking Id', 'bookingpress-package'),
				'my_package_pkg_purchase_date_title' => __('Purchase date', 'bookingpress-package'),
				'my_package_pkg_expiry_date_title' => __('Expiry date', 'bookingpress-package'),
				'my_package_pkg_services_title'	=> __('Services', 'bookingpress-package'),
				'my_package_service_name_title'	=> __('Name', 'bookingpress-package'),
                'my_package_service_duration_title' => __('Duration', 'bookingpress-package'),
				'my_package_service_remain_appoint_title' => __('Remaining appointment', 'bookingpress-package'),
				'my_package_pkg_payment_details_title'	=> __('Payment details', 'bookingpress-package'),
				'my_package_total_amount_title'	=> __('Total amount', 'bookingpress-package'),
                'my_package_appointment_package_name'	=> __('Appointment Package name label', 'bookingpress-package'),
            );

            foreach($my_booking_my_package_fields as $key => $value) {
                $bookingpress_get_customize_text = $BookingPress->bookingpress_get_customize_settings($key, 'booking_my_booking');
                if(empty($bookingpress_get_customize_text)){
                    $bookingpress_customize_settings_db_fields = array(
                        'bookingpress_setting_name'  => $key,
                        'bookingpress_setting_value' => $value,
                        'bookingpress_setting_type'  => 'booking_my_booking',
                    );
                    $wpdb->insert( $tbl_bookingpress_customize_settings, $bookingpress_customize_settings_db_fields );
                }
			}	

            /* Package Front Appointment Booking Form label start */
                $bookingpress_booking_form_customize_setting = array(
                    'package_label_txt'	=> __('Redeem Package', 'bookingpress-package'),
                    'package_button_txt'	=> __('Redeem', 'bookingpress-package'),
                    'package_placeholder_txt'	=> __('Select package', 'bookingpress-package'),
                    'package_login_msg' => __('You must be logged in to continue.','bookingpress-package'),
                    'package_error_msg'     => __('Package appointment not avaliable.', 'bookingpress-package'),
                );
                $tbl_bookingpress_customize_settings = $wpdb->prefix . 'bookingpress_customize_settings';
                foreach($bookingpress_booking_form_customize_setting as $key => $val){
                    $bookingpress_get_customize_text = $BookingPress->bookingpress_get_customize_settings($key, 'booking_form');
                    if(empty($bookingpress_get_customize_text)){
                        $bookingpress_bd_data = array(
                            'bookingpress_setting_name' => $key,
                            'bookingpress_setting_value' => $val,
                            'bookingpress_setting_type' => 'booking_form',
                        );
                        $wpdb->insert($tbl_bookingpress_customize_settings, $bookingpress_bd_data);        
                    }
                }
            /* Package Front Appointment Booking Form label over */

            /* Assign capabilities to all admin users start */           
                $args  = array(
                    'role'   => 'administrator',
                    'fields' => 'id',
                );
                $users = get_users($args);
                if (count($users) > 0 ) {
                    $bookingpressroles = array(
                        'bookingpress_package' => esc_html__('Packages', 'bookingpress-package'),
                        'bookingpress_package_order' => esc_html__('Package Order', 'bookingpress-package')
                    );
                    foreach ( $users as $key => $user_id ) {
                        $userObj           = new WP_User($user_id);
                        foreach ( $bookingpressroles as $bookingpressrole => $bookingpress_roledescription ) {
                            $userObj->add_cap($bookingpressrole);
                        }
                    }
                    unset($bookingpressrole);
                    unset($bookingpressroles);
                    unset($bookingpress_roledescription);
                }
            
            /* Assign capabilities to all admin users over */

            /* Default Email Notification for the Package Order - starts */
            
            /*customer Email*/
            $bookingpress_default_notifications_name_arr = array( 'Package Order');
            $bookingpress_default_notifications_message_arr = array(
            'Package Order' => __('Dear %customer_first_name% %customer_last_name%,<br>You have successfully ordered package.<br>Thank you for choosing us,<br>%company_name%','bookingpress-package'), // phpcs:ignore
            ); 
            foreach ( $bookingpress_default_notifications_name_arr as $bookingpress_default_notification_key => $bookingpress_default_notification_val ) {

                $bookingpress_get_custom_notification_data = $wpdb->get_row( $wpdb->prepare( "SELECT bookingpress_notification_name FROM {$tbl_bookingpress_notifications} WHERE bookingpress_notification_name = %s AND bookingpress_notification_receiver_type = %s",$bookingpress_default_notification_val,'customer'), ARRAY_A);// phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- Reason: $tbl_bookingpress_notifications is table name defined globally. False Positive alarm
                if(empty($bookingpress_get_custom_notification_data)){
                    $bookingpress_customer_notification_data = array(
                        'bookingpress_notification_name'   => $bookingpress_default_notification_val,
                        'bookingpress_notification_receiver_type' => 'customer',
                        'bookingpress_notification_status' => 1,
                        'bookingpress_notification_type'   => 'default',
                        'bookingpress_notification_subject' => $bookingpress_default_notification_val,
                        'bookingpress_notification_message' => $bookingpress_default_notifications_message_arr[ $bookingpress_default_notification_val ],
                        'bookingpress_created_at'          => current_time( 'mysql' ),
                    );
                    $wpdb->insert( $tbl_bookingpress_notifications, $bookingpress_customer_notification_data );
                }
            }

            /*Admin Email*/
            $bookingpress_default_notifications_arr2 = array(
                'Package Order'    => 'Hi administrator,<br/>Your package %package_name% is purchased. <br>Thank you,<br>%company_name%',
            );
            foreach ( $bookingpress_default_notifications_name_arr as $bookingpress_default_notification_key => $bookingpress_default_notification_val ) {
                $bookingpress_get_custom_notification_data = $wpdb->get_row( $wpdb->prepare( "SELECT bookingpress_notification_name FROM {$tbl_bookingpress_notifications} WHERE bookingpress_notification_name = %s AND bookingpress_notification_receiver_type = %s",$bookingpress_default_notification_val,'employee'), ARRAY_A);// phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- Reason: $tbl_bookingpress_notifications is table name defined globally. False Positive alarm
                if(empty($bookingpress_get_custom_notification_data)){
                    $bookingpress_employee_notification_data = array(
                        'bookingpress_notification_name'   => $bookingpress_default_notification_val,
                        'bookingpress_notification_receiver_type' => 'employee',
                        'bookingpress_notification_status' => 1,
                        'bookingpress_notification_type'   => 'default',
                        'bookingpress_notification_subject' => $bookingpress_default_notification_val,
                        'bookingpress_notification_message' => $bookingpress_default_notifications_arr2[ $bookingpress_default_notification_val ],
                        'bookingpress_created_at'          => current_time( 'mysql' ),
                    );
                    $wpdb->insert( $tbl_bookingpress_notifications, $bookingpress_employee_notification_data );
                }
            }
            /* Default Email Notification for the Package Order - ends */
        }

        /**
         * Install default pages when active package module
         *
         * @return void
         */
        public static function bookingpress_package_install_default_pages(){

            global $wpdb,$tbl_bookingpress_customize_settings;

            $post_table = $wpdb->posts;
            $post_author = get_current_user_id();
            $bookingpress_bookingformpage_content = '<!-- wp:shortcode -->[bookingpress_package_form]<!-- /wp:shortcode -->';

            $bookingpress_bookingform_page_details = array(
                'post_title'    => esc_html__('Our Package', 'bookingpress-package'),
                'post_name'     => 'our-package',
                'post_content'  => $bookingpress_bookingformpage_content,
                'post_status'   => 'publish',
                'post_parent'   => 0,
                'post_author'   => 1,
                'post_type'     => 'page',
                'post_author'   => $post_author,
                'post_date'     => current_time( 'mysql' ),
                'post_date_gmt' => current_time( 'mysql', 1 ),
            );
            
            $wpdb->insert( $post_table, $bookingpress_bookingform_page_details );
            $bookingpress_post_id = $wpdb->insert_id;

            $current_guid = get_post_field( 'guid', $bookingpress_post_id );
            $where = array( 'ID' => $bookingpress_post_id );
            if( '' === $current_guid ){
                $wpdb->update( $wpdb->posts, array( 'guid' => get_permalink( $bookingpress_post_id ) ), $where );
            }

            $bookingpress_customize_settings_db_fields = array(
                'bookingpress_setting_name'  => 'default_package_booking_page',
                'bookingpress_setting_value' => $bookingpress_post_id,
                'bookingpress_setting_type'  => 'booking_form',
            );
            $wpdb->insert( $tbl_bookingpress_customize_settings, $bookingpress_customize_settings_db_fields );
            

            /*
            $bookingpress_thankyoupage_content = '[bookingpress_package_booking_thankyou]';
            $bookingpress_thankyou_page_details = array(
                'post_title'   => esc_html__('Thank you', 'bookingpress-package'),
                'post_name'    => 'package-thank-you',
                'post_content' => $bookingpress_thankyoupage_content,
                'post_status'  => 'publish',
                'post_parent'  => 0,
                'post_author'  => 1,
                'post_type'    => 'page',
                'post_author'   => $post_author,
                'post_date'     => current_time( 'mysql' ),
                'post_date_gmt' => current_time( 'mysql', 1 ),
            );
            $wpdb->insert( $post_table, $bookingpress_thankyou_page_details );
            $bookingpress_post_id = $wpdb->insert_id;            
            $current_guid = get_post_field( 'guid', $bookingpress_post_id );
            $where = array( 'ID' => $bookingpress_post_id );
            if( '' === $current_guid ){
                $wpdb->update( $wpdb->posts, array( 'guid' => get_permalink( $bookingpress_post_id ) ), $where );
            }
            $bookingpress_thankyou_page_url = get_permalink($bookingpress_post_id);
            if (! empty($bookingpress_thankyou_page_url) ) {   
                $booking_form = array(
                    'package_after_booking_redirection' => $bookingpress_post_id,                    
                    'bookingpress_package_thankyou_msg' => $bookingpress_thankyoupage_content,
                );		
                foreach($booking_form as $key => $value) {
                    $bookingpress_customize_settings_db_fields = array(
                        'bookingpress_setting_name'  => $key,
                        'bookingpress_setting_value' => $value,
                        'bookingpress_setting_type'  => 'package_booking_form',
                    );
                    $wpdb->insert( $tbl_bookingpress_customize_settings, $bookingpress_customize_settings_db_fields );
                }
            }

            $bookingpress_cancel_payment_page = '
            <div class="bpa-front-data-empty-view __bpa-is-guest-view">
                <h4>' . esc_html__('Sorry! Something went wrong. Your payment has been failed.', 'bookingpress-package') . '</h4>
            </div>';            
            
            $bookingpress_cancel_page_details = array(
                'post_title'   => esc_html__('Cancel Payment', 'bookingpress-package'),
                'post_name'    => 'cancel-package-payment',
                'post_content' => $bookingpress_cancel_payment_page,
                'post_status'  => 'publish',
                'post_parent'  => 0,
                'post_author'  => 1,
                'post_type'    => 'page',
                'post_author'   => $post_author,
                'post_date'     => current_time( 'mysql' ),
                'post_date_gmt' => current_time( 'mysql', 1 ),
            );

            $wpdb->insert( $post_table, $bookingpress_cancel_page_details );
            $bookingpress_post_id = $wpdb->insert_id;

            $current_guid = get_post_field( 'guid', $bookingpress_post_id );
            $where = array( 'ID' => $bookingpress_post_id );
            if( '' === $current_guid ){
                $wpdb->update( $wpdb->posts, array( 'guid' => get_permalink( $bookingpress_post_id ) ), $where );
            }

            $bookingpress_cancel_payment_url = get_permalink($bookingpress_post_id);
            if (! empty($bookingpress_cancel_payment_url) ) {
                $my_booking_form = array(
                    'package_after_failed_payment_redirection' => $bookingpress_post_id,
                    'bookingpress_package_failed_payment_msg' => $bookingpress_cancel_payment_page,
                );		    
                foreach($my_booking_form as $key => $value) {
                    $bookingpress_customize_settings_db_fields = array(
                        'bookingpress_setting_name'  => $key,
                        'bookingpress_setting_value' => $value,
                        'bookingpress_setting_type'  => 'package_booking_form',
                    );
                    $wpdb->insert( $tbl_bookingpress_customize_settings, $bookingpress_customize_settings_db_fields );
                }
            }                   
            
            $my_booking_form = array(
                'package_redirection_mode' => 'in-built',                
            );		    
            foreach($my_booking_form as $key => $value) {
                $bookingpress_customize_settings_db_fields = array(
                    'bookingpress_setting_name'  => $key,
                    'bookingpress_setting_value' => $value,
                    'bookingpress_setting_type'  => 'package_booking_form',
                );
                $wpdb->insert( $tbl_bookingpress_customize_settings, $bookingpress_customize_settings_db_fields );
            }   
            
          */      

        }

        public static function uninstall(){

            delete_option( 'bkp_package_license_key');
            delete_option( 'bkp_package_license_package');
            delete_option( 'bkp_package_license_status');
            delete_option( 'bkp_package_license_data_activate_response');

            delete_option('bookingpress_package_version');            
            $args  = array(
                'role'   => 'administrator',
                'fields' => 'id',
            );
            $users = get_users($args);
            if (count($users) > 0 ) {
                $bookingpressroles = array(
                    'bookingpress_package' => esc_html__('Package', 'bookingpress-package'),
                    'bookingpress_package_order' => esc_html__('Package Order', 'bookingpress-package')
                );
                foreach ( $users as $key => $user_id ) {
                    $userObj           = new WP_User($user_id);
                    foreach ( $bookingpressroles as $bookingpressrole => $bookingpress_roledescription ) {
                        if($userObj->has_cap($bookingpressrole)){
                            $userObj->remove_cap($bookingpressrole, true);
                        }
                    }
                }
            }
        }

        function bookingpress_package_add_capabilities_to_new_user($user_id){            
            if ($user_id == '') {
                return;
            }
            if (user_can($user_id, 'administrator')) {
                $bookingpressroles = array(
                    'bookingpress_package' => esc_html__('Package', 'bookingpress-package'),
                    'bookingpress_package_order' => esc_html__('Package Order', 'bookingpress-package')
                );
                $userObj = new WP_User($user_id);
                foreach ($bookingpressroles as $bookingpress_role => $bookingpress_role_desc) {
                    $userObj->add_cap($bookingpress_role);
                }
                unset($bookingpress_role);
                unset($bookingpress_roles);
                unset($bookingpress_role_desc);
            }           
        }

        function bookingpress_package_assign_caps_on_role_change( $user_id, $role, $old_roles ){
            
            global $BookingPress;
            if(!empty($user_id) && $role == "administrator"){
                $bookingpressroles = array(
                    'bookingpress_package' => esc_html__('Package', 'bookingpress-package'),
                    'bookingpress_package_order' => esc_html__('Package Order', 'bookingpress-package')
                );
                $userObj = new WP_User($user_id);
                foreach ($bookingpressroles as $bookingpress_role => $bookingpress_role_desc) {
                    $userObj->add_cap($bookingpress_role);
                }
                unset($bookingpress_role);
                unset($bookingpress_roles);
                unset($bookingpress_role_desc);
            }
            
        }

        public function is_addon_activated(){
            $bookingpress_package_version = get_option('bookingpress_package_version');
            return !empty($bookingpress_package_version) ? 1 : 0;
        }
    }
    
	global $bookingpress_package;
	$bookingpress_package = new bookingpress_package();
}