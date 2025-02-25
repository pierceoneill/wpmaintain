<?php

$bookingpress_geoip_file = BOOKINGPRESS_PRO_LIBRARY_DIR . '/geoip/autoload.php';
require $bookingpress_geoip_file;
use GeoIp2\Database\Reader;

if (!class_exists('bookingpress_package_booking_form')) {
	class bookingpress_package_booking_form Extends BookingPress_Core {

        var $bookingpress_form_package;
        var $bookingpress_selected_package_param;
        var $bookingpress_default_date_format;
        var $bookingpress_form_fields_error_msg_arr;
        var $bookingpress_form_fields_new;
        var $bookingpress_default_time_format;
        var $bookingpress_is_package_load_from_url;

        function __construct(){            
            global $wpdb, $BookingPress, $bookingpress_pro_appointment_bookings, $tbl_bookingpress_packages, $tbl_bookingpress_package_services, $tbl_bookingpress_package_images, $BookingPress, $tbl_bookingpress_package_bookings, $tbl_bookingpress_package_bookings_meta,$bookingpress_package;

            /*
            $tbl_bookingpress_packages = $wpdb->prefix.'bookingpress_packages';
            $tbl_bookingpress_package_services = $wpdb->prefix.'bookingpress_package_services';
            $tbl_bookingpress_package_images = $wpdb->prefix.'bookingpress_package_images';
            $tbl_bookingpress_package_bookings = $wpdb->prefix.'bookingpress_package_bookings';
            $tbl_bookingpress_package_bookings_meta = $wpdb->prefix.'bookingpress_package_bookings_meta';  
            */
            
            $package_addon_working = $bookingpress_package->bookingpress_check_package_addon_requirement();

            if( !function_exists('is_plugin_active') ){
                include_once ABSPATH . 'wp-admin/includes/plugin.php';
            }
            if( is_plugin_active( 'bookingpress-appointment-booking-pro/bookingpress-appointment-booking-pro.php' ) && !empty( $BookingPress->bpa_pro_plugin_version() ) && version_compare( $BookingPress->bpa_pro_plugin_version(), '3.2', '>=' ) && $package_addon_working){                
               
                /* Add Package booking form shortcode */
               add_shortcode('bookingpress_package_form', array($this,'bookingpress_package_front_booking_form'));
               add_filter('bookingpress_package_front_booking_dynamic_data_fields', array( $this, 'bookingpress_package_front_booking_dynamic_data_fields_func' ), 10, 3);               
               
               /* Removed appointment booking  shortcode added css */
               add_filter('bookingpress_check_front_page_or_not',array($this,'bookingpress_check_front_page_or_not_func'),10,1);
               add_filter('bookingpress_package_front_booking_dynamic_vue_methods',array($this,'bookingpress_package_front_booking_dynamic_vue_methods_func'),10,1);

               

               /* Appointment filter */ 
               add_action('wp_ajax_bookingpress_get_front_packages', array( $this, 'bookingpress_get_front_packages_func' ), 10);
               add_action('wp_ajax_nopriv_bookingpress_get_front_packages', array( $this, 'bookingpress_get_front_packages_func' ), 10);

               /* My Booking Page Start Here */
               add_action('bookingpress_my_booking_my_appointment_menutab_after',array($this,'bookingpress_my_booking_my_appointment_after_func'),10); 
               add_action('bookingpress_my_booking_mobile_my_appointment_menutab_after',array($this,'bookingpress_my_booking_mobile_my_appointment_after_func'),10); 
               add_action('bookingpress_my_booking_my_appointment_tab_after',array($this,'bookingpress_my_booking_my_appointment_tab_after_func'),10);                           

               add_filter('bookingpress_front_appointment_add_dynamic_data', array($this, 'bookingpress_front_appointment_my_booking_add_dynamic_data_func'), 15, 1);
               add_action('bookingpress_dynamic_add_onload_myappointment_methods',array($this,'bookingpress_dynamic_add_onload_myappointment_methods_func'));
               add_action('bookingpress_front_appointments_dynamic_vue_methods', array( $this, 'bookingpress_front_appointments_dynamic_vue_methods_func' ));
               add_action('wp_ajax_bookingpress_get_customer_package_order', array( $this, 'bookingpress_get_customer_package_order_func' ), 10);               
               /* My Booking Page Over Here */

               /* Front Login Ajax Call Here */
               add_action('wp_ajax_nopriv_bookingpress_package_login_customer_account', array($this, 'bookingpress_package_login_customer_account_func'), 10);

               /* Front Register Ajax Call Here */
               add_action('wp_ajax_nopriv_bookingpress_package_register_customer_account', array($this, 'bookingpress_package_register_customer_account_func'), 10);

               /* Function for Forgot Password */
               add_action('wp_ajax_nopriv_bookingpress_package_forgot_password_account', array($this, 'bookingpress_package_forgot_password_account_func'), 10);
               
               /* Front Appointment Booking Form & My Booking CSS Added  */
               add_action('wp_head', array( $this, 'set_package_front_css' ),12); 
               
               /* Ajax Request For Validate Package  */
               add_action('wp_ajax_bookingpress_before_book_package_validate', array( $this, 'bookingpress_before_book_package_validate_func' ), 10);
               add_action('wp_ajax_nopriv_bookingpress_before_book_package_validate', array( $this, 'bookingpress_before_book_package_validate_func' ), 10);

               /* Package addon entry data added with validation */
               add_filter( 'bookingpress_validate_submitted_package_order_booking_form', array( $this, 'bookingpress_validate_submitted_package_order_booking_form_func' ), 10, 3 );

			   //Hook for modify shortcode content of thank you page
			   add_action('wp_ajax_bookingpress_package_render_thankyou_content', array($this, 'bookingpress_package_render_thankyou_content_func'));
			   add_action('wp_ajax_nopriv_bookingpress_package_render_thankyou_content', array($this, 'bookingpress_package_render_thankyou_content_func'));


               add_action('wp_ajax_bookingpress_package_render_external_thankyou_content', array($this, 'bookingpress_package_render_external_thankyou_content_func'));

               //Hook to generate dynamic CSS while saving the settings
               add_filter( 'bookingpress_generate_my_booking_customize_css', array( $this, 'bookingpress_generate_my_booking_css'), 10, 2);

               /* Multi language addon translation */
                if(is_plugin_active('bookingpress-multilanguage/bookingpress-multilanguage.php')) {
                    //add_filter('bookingpress_modified_language_translate_fields',array($this,'bookingpress_modified_package_language_translate_fields_func'),1,1);

                    add_filter('bookingpress_modified_customize_my_booking_language_translate_fields',array($this,'bookingpress_modified_package_language_translate_fields_func'),1);

                    add_filter('bookingpress_modified_language_translate_fields_section',array($this,'bookingpress_modified_package_language_translate_fields_section_func'),10);

                }

                add_filter( 'bookingpress_package_order_paypal_submit_form_data', array( $this, 'bookingpress_package_order_paypal_submission_data' ), 10, 2 );

                /*Function for my appointment package icon*/
                add_action('bookingpress_my_booking_extra_icons',array($this,'bookingpress_my_booking_extra_icons_package_func'),10);
                add_action('bookingpress_add_additional_information_outside_before',array($this,'add_additional_information_outside_before_for_package'),10);
                add_action('bookingpress_modify_my_appointment_data',array($this,'bookingpress_modify_my_appointment_data_func_package'),10,2);

                add_action( 'wp_ajax_bookingpress_generate_package_token', array( $this, 'bookingpress_generate_package_token_func') );

                /* Paypal popup gateway filter add */
                add_filter('bookingpress_after_selecting_payment_method_for_package_order',array($this,'bookingpress_after_selecting_payment_method_for_package_order_func'),10,1);

                add_action('wp_ajax_bookingpress_paypal_booking_validate_package', array($this, 'bookingpress_paypal_booking_validate_package_func'), 10);
                add_action('wp_ajax_nopriv_bookingpress_paypal_booking_validate_package', array($this, 'bookingpress_paypal_booking_validate_package_func'), 10);	  

                add_action('wp_ajax_bookingpress_paypal_booking_payment_confirm_package', array($this, 'bookingpress_paypal_booking_payment_confirm_package_func'), 10);
                add_action('wp_ajax_nopriv_bookingpress_paypal_booking_payment_confirm_package', array($this, 'bookingpress_paypal_booking_payment_confirm_package_func'), 10);

                add_action('bookingpress_package_paypal_payment_button_html',array($this,'bookingpress_paypal_payment_button_html'),10);

                add_filter('bookingpress_after_package_final_step_amount',array($this,'bookingpress_after_package_final_step_amount_func'),10,1);

                add_action('bookingpress_after_succesfully_my_booking_login_data',array($this,'bookingpress_after_succesfully_my_booking_login_data_func'),10,1);

                if(is_plugin_active('bookingpress-google-captcha/bookingpress-google-captcha.php')){
                    add_filter('bookingpress_frontend_package_order_form_add_dynamic_data', array($this, 'bookingpress_frontend_package_order_form_dynamic_data_func'));
                    add_action('bookingpress_customize_package_settings_add', array($this, 'bookingpress_customize_package_settings_add_func'));
                    add_filter('bookingpress_package_front_booking_dynamic_vue_methods',array($this,'bookingpress_package_front_captcha_dynamic_vue_methods_func'),10,1);
                    add_filter('bookingpress_package_front_booking_dynamic_on_load_methods', array($this, 'bookingpress_package_front_on_load_methods_func'));
                    add_action('bookingpress_add_field_after_package_form', array($this, 'bookingpress_add_field_after_package_form_func'));
                    add_filter('bookingpress_before_package_purchase_data',array($this,'bookingpress_regenerate_google_captcha_package'));
                    add_action('bookingpress_validate_package_form', array($this, 'bookingpress_validate_google_cpatcha_package'), 11, 1);                   

                }
            }
        }

        function bookingpress_validate_google_cpatcha_package($posted_data) {
            global $BookingPress;
            $bookingpresss_enable_google_captcha = $BookingPress->bookingpress_get_customize_settings('enable_google_captcha', 'package_booking_form');            
            $bookingpress_google_captch_site_key = $BookingPress->bookingpress_get_settings('google_captcha_site_key','google_captcha_setting');
            $bookingpress_google_secret_key = $BookingPress->bookingpress_get_settings('google_captcha_secret_key','google_captcha_setting');              
            $bookingpress_google_captcha_failed_msg = $BookingPress->bookingpress_get_settings('google_captcha_failed_msg','google_captcha_setting');  
            
            if(!empty($bookingpress_google_captch_site_key) && !empty($bookingpress_google_secret_key) && ($bookingpresss_enable_google_captcha == 'true')){                                           
                $bookingpress_form_random_key = !empty($posted_data['package_data']['bookingpress_uniq_id']) ? esc_html($posted_data['package_data']['bookingpress_uniq_id']) : '';                
                if(isset($posted_data['package_data']['bookingpress_package_captcha_'.$bookingpress_form_random_key])){    
                    require_once(BOOKINGPRESS_GOOGLE_CAPTCHA_LIBRARY_DIR . '/recaptchalib/recaptchalib.php');           
                    $bookingpress_recaptcha = new Bookingpress_ReCaptcha($bookingpress_google_secret_key);                    
                    $bookingpress_recaptcha_response = $bookingpress_recaptcha->verifyResponse(sanitize_text_field($_SERVER['REMOTE_ADDR']), $posted_data['package_data']['bookingpress_package_captcha_'.$bookingpress_form_random_key]);  //phpcs:ignore  
                    if ($bookingpress_recaptcha_response->success != 1 && !empty($bookingpress_recaptcha_response->errorCodes)) {
                        $bookingpress_recptcha_invalid_message = !empty($bookingpress_google_captcha_failed_msg) ? $bookingpress_google_captcha_failed_msg : __('Google reCAPTCHA is Invalid or Expired. Please reload page and try again', 'bookingpress-package').'.';                        
                        $response['variant'] = 'error';
                        $response['title']   = esc_html__( 'Error', 'bookingpress-package' );
                        $response['msg'] = $bookingpress_recptcha_invalid_message;
                        echo json_encode( $response );
                        exit();
                    }
                }
            }                                
        } 

        function bookingpress_regenerate_google_captcha_package($bookingpress_before_package_purchase_data) {
            $bookingpress_before_package_purchase_data .= '
                const regenerateCaptcha = async () => {
                    const cdata = await vm.bookingpress_package_reload_captcha();
                    let updateData = JSON.parse(postData.package_data);
                    for (let bookingpress_grecaptcha_field_v3 in window["bookingpress_package_recaptcha_v3"]) {
                        console.log(cdata)
                        updateData[bookingpress_grecaptcha_field_v3] = cdata;
                    }
                    postData.package_data = JSON.stringify(updateData);
                };                
                regenerateCaptcha();
            ';
            return $bookingpress_before_package_purchase_data;
        }

        function bookingpress_add_field_after_package_form_func($bookingpress_uniq_id){       
            global $BookingPress;                 
            $bookingpresss_enable_google_captcha = $BookingPress->bookingpress_get_customize_settings('enable_google_captcha', 'package_booking_form');                                           
            $google_captcha_site_key = $BookingPress->bookingpress_get_settings('google_captcha_site_key','google_captcha_setting');            
            if($bookingpresss_enable_google_captcha == 'true' && !empty($google_captcha_site_key)) {
                ?>
                <el-row>
                    <el-input type="hidden" name="bookingpress_package_form_<?php echo esc_html($bookingpress_uniq_id); ?>" v-model="package_step_form_data.bookingpress_package_captcha_<?php echo esc_html($bookingpress_uniq_id); ?>" id="bookingpress_package_captcha_<?php echo esc_html($bookingpress_uniq_id); ?>"></el-input> 
                <el-row>
                <?php   
            }
        }

        function bookingpress_package_front_on_load_methods_func($bookingpress_package_form_data){
            $bookingpress_package_form_data .= 'this.initPackageMap();';            
            return $bookingpress_package_form_data;
        }

        function bookingpress_package_front_captcha_dynamic_vue_methods_func($bookingpress_vue_methods_data){  
            global $BookingPress;
            $bookingpress_google_captcha_site_key = $BookingPress->bookingpress_get_settings('google_captcha_site_key','google_captcha_setting');
            $bookingpress_google_captcha_language = $BookingPress->bookingpress_get_settings('google_captcha_language','google_captcha_setting');                
            $bookingpress_g_recaptcha_response = !empty($_POST['g-recaptcha-response']) ? sanitize_text_field($_POST['g-recaptcha-response']) : '';  // phpcs:ignore

            $script_url = "https://www.google.com/recaptcha/api.js?hl=".$bookingpress_google_captcha_language."&render=".$bookingpress_google_captcha_site_key;

            $bookingpress_vue_methods_data .= ' 
                loadCaptchaJsPackage(is_listing = false){
                    const vm = this
                    var script_url = "'.$script_url.'"
                    if(is_listing == false){
                        script_url = script_url+"&onload=render_bookingpress_package_captcha_v3"
                    }
                    var script = document.createElement("script")
                    script.src = script_url
                    script.async = true
                    var bookingpress_captcha_v3 =  "bookingpress_package_captcha_"+bookingpress_uniq_id_js_var
                    vm[bookingpress_captcha_v3] = "'.$bookingpress_g_recaptcha_response.'"
                    var dsize = "normal"

                    window.addEventListener("load", function() { (function($) {                    
                        jQuery(document).ready(function (){
                        if( !window["bookingpress_package_recaptcha_v3"] ){
                            window["bookingpress_package_recaptcha_v3"] = {}
                        }
                        window["bookingpress_package_recaptcha_v3"][bookingpress_captcha_v3] = {
                            size : dsize
                        };
                    }); })(jQuery); })
                    document.head.appendChild(script)
                },
                initPackageMap(){
                    const vm = this
                    var bookingpress_package_grecaptcha_site_key = "'.$bookingpress_google_captcha_site_key.'"                                    
                    var bookingpress_grecaptcha_language = "'.$bookingpress_google_captcha_language.'"                          
                    
                    if(vm.enable_package_google_captcha == "true" && bookingpress_package_grecaptcha_site_key != "" && bookingpress_grecaptcha_language != "") {    
                        vm.loadCaptchaJsPackage()
                    }  
                    window.render_bookingpress_package_captcha_v3 = function() {      
                        if (typeof window["bookingpress_captcha_v3"] != "undefined" && typeof grecaptcha != "undefined") {                                        
                            if (!document.getElementById(bookingpress_grecaptcha_field_v3).dataset.rendered) {
                                grecaptcha.ready(function() {
                                    grecaptcha.execute(bookingpress_package_grecaptcha_site_key).then(function(bookingpress_recaptcha_token) {                                
                                        for (var bookingpress_grecaptcha_field_v3 in chwindow["bookingpress_package_recaptcha_v3"]) {                                    
                                            var bookingpress_grecaptcha_fields_v3 = bookingpress_grecaptcha_field_v3
                                            var bookingpress_grecaptcha_size = window["bookingpress_package_recaptcha_v3"][bookingpress_grecaptcha_field_v3]["size"]                      
                                            bookingpress_grecaptcha_fields_v3 = grecaptcha.render(bookingpress_grecaptcha_field_v3, {
                                                "sitekey": bookingpress_package_grecaptcha_site_key,
                                                "size": bookingpress_grecaptcha_size,
                                            })
                                            vm["package_step_form_data"][bookingpress_grecaptcha_field_v3] = bookingpress_recaptcha_token
                                        }
                                    });  
                                }); 
                            }
                        } 
                        else {
                            var bookingpress_captcha_int = 0;
                            var bookingpress_captcha_interval = setInterval(function(){                            
                                if (typeof(window["bookingpress_package_recaptcha_v3"]) != "undefined"){     
                                    grecaptcha.ready(function() {                                    
                                        grecaptcha.execute(bookingpress_package_grecaptcha_site_key).then(function(bookingpress_recaptcha_token) {                                    
                                            for (var bookingpress_grecaptcha_field_v3 in window["bookingpress_package_recaptcha_v3"]) {
                                                var bookingpress_grecaptcha_fields_v3 = bookingpress_grecaptcha_field_v3;
                                                var bookingpress_grecaptcha_size = window["bookingpress_package_recaptcha_v3"][bookingpress_grecaptcha_field_v3]["size"]    
                                                if (document.getElementById(bookingpress_grecaptcha_field_v3)) {
                                                    bookingpress_grecaptcha_fields_v3 = grecaptcha.render(bookingpress_grecaptcha_field_v3, {
                                                        "sitekey": bookingpress_package_grecaptcha_site_key,
                                                        "size": bookingpress_grecaptcha_size,
                                                    })
                                                }
                                                else {
                                                    console.error(`Element with ID ${bookingpress_grecaptcha_field_v3} not found.`);
                                                }
                                                vm["package_step_form_data"][bookingpress_grecaptcha_field_v3] = bookingpress_recaptcha_token
                                                clearInterval(bookingpress_captcha_interval)
                                            }
                                        });   
                                    }); 
                                }else{
                                    bookingpress_captcha_int++;
                                    if(bookingpress_captcha_int == 10){
                                        clearInterval(bookingpress_captcha_interval)
                                    }
                                }
                            }, 1500)
                        }
                    }
                },
                bookingpress_package_reload_captcha() {
                    var bookingpress_package_grecaptcha_site_key = "'.$bookingpress_google_captcha_site_key.'";
                    if (typeof(window["bookingpress_package_recaptcha_v3"]) != "undefined" && typeof(grecaptcha) != "undefined") {
                        return new Promise( (res,rej) => {
                            grecaptcha.ready( () =>{
                                grecaptcha.execute(bookingpress_package_grecaptcha_site_key).then(function(bookingpress_recaptcha_token) {
                                    return res(bookingpress_recaptcha_token);
                                })
                            } );
                        } );
                    }
                },
            ';

            return $bookingpress_vue_methods_data;      
        }


        /**
         * bookingpress_frontend_package_order_form_dynamic_data_func
         *
         * @param  mixed $bookingpress_frontend_package_order_form_dynamic_data_func
         * @return void
         */
        function bookingpress_frontend_package_order_form_dynamic_data_func($bookingpress_front_vue_data_fields)
        {
            global $BookingPress;
            /* Google Recaptcha changes */
            $bookingpresss_enable_google_captcha = $BookingPress->bookingpress_get_customize_settings('enable_google_captcha', 'package_booking_form');       
            if($bookingpresss_enable_google_captcha == 'true') {
                $bookingpress_front_vue_data_fields['package_google_captcha_site_key'] = $BookingPress->bookingpress_get_settings('google_captcha_site_key','google_captcha_setting');                
            }
            $bookingpress_front_vue_data_fields['enable_package_google_captcha'] = $bookingpresss_enable_google_captcha;
            /* Google Recaptcha changes */
            return $bookingpress_front_vue_data_fields;
        }

        /**
         * bookingpress_customize_package_settings_add_func
         *
         * @return void
         */
        function bookingpress_customize_package_settings_add_func(){
            ?>
            <div class="bpa-cs-sp-sub-module bpa-cs-sp--form-controls" v-if="typeof is_gcaptcha_activated !== 'undefined' && is_gcaptcha_activated == 1">
                <div class="bpa-cs-sp-sub-module--separator"></div>
                <div class="bpa-sm--item">
                    <h5 class="bpa-sm-sub-heading--item"><?php esc_html_e( 'Google reCaptcha Settings', 'bookingpress-package' ); ?></h5>
                </div>
                <div class="bpa-sm--item --bpa-is-flexbox">
                    <label class="bpa-form-label"><?php esc_html_e( 'Enable google recaptcha', 'bookingpress-package' ); ?></label>
                    <el-switch v-model="package_booking_form_settings.enable_google_captcha" class="bpa-swtich-control"></el-switch>
                </div>
            </div>
            <?php
        }
        
        function bookingpress_after_succesfully_my_booking_login_data_func(){
        ?>
            vm.loadFrontMyPackages();
        <?php 
        }

        function bookingpress_paypal_payment_button_html(){
        ?>
			<el-button v-if="paypal_button_loader != 'false'" class="bpp-front-btn bpp-front-btn__medium bpp-front-btn--primary bpp-loader-button bpp-front-btn--is-loader">                
				<span class="bpp-btn__label">Test Button</span>
				<div class="bpp-front-btn--loader__circles">			    
					<div></div>
					<div></div>
					<div></div>
				</div>
			</el-button>		
			<div v-if="paypal_button_loader != 'true'" id="paypal-package-button-container"></div>        
        <?php 
        }

        function bookingpress_paypal_booking_payment_confirm_package_func(){

            global  $BookingPress,$bookingpress_debug_payment_log_id,$bookingpress_pro_payment_gateways,$bookingpress_paypal;
            $wpnonce               = isset( $_POST['_wpnonce'] ) ? sanitize_text_field( $_POST['_wpnonce'] ) : '';
			$bpa_verify_nonce_flag = wp_verify_nonce( $wpnonce, 'bpa_wp_nonce' );
			$response              = array();
			
			$response['variant'] = 'error';
			$response['title']   = esc_html__( 'Error', 'bookingpress-package' );
			$response['msg']     = esc_html__( 'Sorry, payment is not successed with the paypal.', 'bookingpress-package' );

			$bookingpress_payment_res = (isset($_POST['bookingpress_payment_res']))?$_POST['bookingpress_payment_res']:'';

			do_action( 'bookingpress_payment_log_entry', 'paypal', 'payment popup response data', 'bookingpress pro', $_POST, $bookingpress_debug_payment_log_id );
            if ( ! $bpa_verify_nonce_flag ) {
				$response['variant'] = 'error';
				$response['title']   = esc_html__( 'Error', 'bookingpress-package' );
				$response['msg']     = esc_html__( 'Sorry, Your request can not process due to security reason.', 'bookingpress-package' );
				wp_send_json( $response );
				die();
			}	
            
            $paypal_client_id = $BookingPress->bookingpress_get_settings( 'paypal_client_id', 'payment_setting' );
            $paypal_client_secret = $BookingPress->bookingpress_get_settings( 'paypal_client_secret', 'payment_setting' );

            if (empty($paypal_client_id) ) {
                $bookingpress_paypal_error_msg .= esc_html__('Please configure PayPal Client ID', 'bookingpress-package');
                $response['variant']       = 'error';
                $response['title']         = esc_html__('Error', 'bookingpress-package');
                $response['msg']           = $bookingpress_paypal_error_msg;
                $response['is_redirect']   = 0;
                $response['redirect_data'] = '';
                $response['is_spam']       = 0;
                echo json_encode($response);
                exit;
            }
            if (empty($paypal_client_secret) ) {
                $bookingpress_paypal_error_msg .= esc_html__('Please Configure PayPal Client Secret', 'bookingpress-package');
                $response['variant']       = 'error';
                $response['title']         = esc_html__('Error', 'bookingpress-package');
                $response['msg']           = $bookingpress_paypal_error_msg;
                $response['is_redirect']   = 0;
                $response['redirect_data'] = '';
                $response['is_spam']       = 0;
                echo json_encode($response);
                exit;
            }
            
			$order_id = (isset($bookingpress_payment_res['id']))?$bookingpress_payment_res['id']:'';            
			$order = "";
            if(!empty($order_id)){
                try {  
                    $order = $bookingpress_paypal->validate_paypal_order($order_id); 
                } catch(Exception $e) {  
                    $api_error = $e->getMessage();  
                    $response['variant'] = 'error';
                    $response['title']   = esc_html__( 'Error', 'bookingpress-package' );
                    $response['msg']     = $api_error;
                    wp_send_json( $response );
                    die();                
                }
                    
                $reference_id = (isset($order['purchase_units'][0]['reference_id']))?$order['purchase_units'][0]['reference_id']:'';
                $order_status = (isset($order['status']))?$order['status']:'';
                $transaction_id  =  (isset($order['purchase_units'][0]['payments']['captures'][0]['id']))?$order['purchase_units'][0]['payments']['captures'][0]['id']:'';
                $payment_status = (isset($order['purchase_units'][0]['payments']['captures'][0]['status']))?$order['purchase_units'][0]['payments']['captures'][0]['status']:'';
                $amount = (isset($order['purchase_units'][0]['amount']['value']))?$order['purchase_units'][0]['amount']['value']:'';
                $currency_code = (isset($order['purchase_units'][0]['amount']['currency_code']))?$order['purchase_units'][0]['amount']['currency_code']:'';
                $bookingpress_is_cart = 0;

                if(!empty($reference_id)){                    

                    $entry_id = $reference_id;                    
                    if(!empty($order_id) && $order_status == 'COMPLETED' && !empty($entry_id)){
    
                        $bookingpress_webhook_data = array();
						$payer_email = (isset($order['payer']['email_address']))?$order['payer']['email_address']:'';
						$bookingpress_webhook_data['bookingpress_payer_email'] = $payer_email;
						$bookingpress_webhook_data['txn_id'] = $transaction_id;
						$bookingpress_webhook_data['amt'] = $amount;
						$bookingpress_webhook_data['amount'] = $amount;
						$bookingpress_webhook_data['currency'] = $currency_code;
                        $payment_add_status = '1';
                        if($payment_status == "PENDING"){
                            $payment_add_status = '1';
                        }

                        $bookingpress_pro_payment_gateways->bookingpress_confirm_booking( (int)$entry_id, $bookingpress_webhook_data, $payment_add_status, 'txn_id', 'amt', 1, $bookingpress_is_cart,'currency');
                        $response['variant'] = 'success';
                        $response['title']   = esc_html__( 'Success', 'bookingpress-package' );
                        $response['msg']     = esc_html__( 'Package succesfully created.', 'bookingpress-package' );
    
                        wp_send_json( $response );
                        die();
                    }
    
                }

            }


			echo json_encode($response);
			die;            

        }

        function bookingpress_paypal_booking_validate_package_func(){

            global $wpdb, $BookingPress, $tbl_bookingpress_appointment_bookings, $tbl_bookingpress_payment_logs,$tbl_bookingpress_customers,$bookingpress_payment_gateways,$tbl_bookingpress_form_fields,$tbl_bookingpress_package_bookings,$bookingpress_package;
            $response              = array();
            $wpnonce               = isset($_REQUEST['_wpnonce']) ? sanitize_text_field($_REQUEST['_wpnonce']) : '';
            $bpa_verify_nonce_flag = wp_verify_nonce($wpnonce, 'bpa_wp_nonce');
            $return_data = false;
            
            if (! $bpa_verify_nonce_flag ) {
                $response['variant']      = 'error';
                $response['title']        = esc_html__('Error', 'bookingpress-package');
                $response['msg']          = esc_html__('Sorry, Your request can not be processed due to security reason.', 'bookingpress-package');
                $response['redirect_url'] = '';
                if($return_data){
                    return $response;
                }
                echo wp_json_encode($response);
                exit;
            }
            $response['variant'] = 'error';
            $response['title']   = esc_html__('Error', 'bookingpress-package');
            $response['msg']     = esc_html__('Something Wrong', 'bookingpress-package');
            $response['error_type'] = '';

            if( !empty( $_REQUEST['package_data'] ) && !is_array( $_REQUEST['package_data'] ) ){
                $_REQUEST['package_data'] = json_decode( stripslashes_deep( $_REQUEST['package_data'] ), true ); //phpcs:ignore                
                $_POST['package_data'] = $_REQUEST['package_data'] =  !empty($_REQUEST['package_data']) ? array_map(array($this,'bookingpress_boolean_type_cast'), $_REQUEST['package_data'] ) : array(); // phpcs:ignore
            }
            $posted_data = !empty($_POST) ? array_map(array( $BookingPress, 'appointment_sanatize_field' ), $_POST) : array();
	        $bookingpress_unique_id = isset($_REQUEST['package_data']['bookingpress_uniq_id']) ? sanitize_text_field($_REQUEST['package_data']['bookingpress_uniq_id']) : '';
            $bookingpress_package_token = !empty( $posted_data['package_data']['package_token'] ) ? $posted_data['package_data']['package_token'] : ''; //phpcs:ignore

            if(session_id() == '' OR session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            if(( empty( $bookingpress_package_token ) || empty( $_SESSION['bpa_package_filter_input'] )) && $return_data == false ){
                $response['package_token'] = $bookingpress_package_token;
                $response['session_package_token'] = $_SESSION['bpa_package_filter_input'];
                $response['variant']      = 'error';
                $response['title']        = esc_html__('Error', 'bookingpress-package');
                $response['msg']          = esc_html__('Sorry, Your request can not be processed due to security reason.', 'bookingpress-package');
                $response['redirect_url'] = '';
                if($return_data){
                    return $response;
                }                
                echo wp_json_encode($response);
                die;
            }
            
            if( !empty( $_SESSION['bpa_package_filter_input'] ) && $_SESSION['bpa_package_filter_input'] != md5( $bookingpress_package_token ) && $return_data == false ){
                if( !empty( $package_active_tokens[ md5( $bookingpress_package_toke ) ]) ){
                    $response['variant']      = 'error';
                    $response['title']        = esc_html__('Error', 'bookingpress-package');
                    $response['msg']          = esc_html__('Sorry, Your request can not be processed due to security reason.', 'bookingpress-package');
                    $response['redirect_url'] = '';
                    if($return_data){
                        return $response;
                    }                    
                    echo wp_json_encode($response);
                    die;
                }
            }

            $bookingpress_form_token = !empty( $_REQUEST['package_data']['bookingpress_form_token'] ) ? sanitize_text_field( $_REQUEST['package_data']['bookingpress_form_token'] ) : $bookingpress_unique_id;


            $unsupported_currecy_selected_for_the_payment = $BookingPress->bookingpress_get_settings('unsupported_currecy_selected_for_the_payment', 'message_setting');
            $no_payment_method_is_selected_for_the_booking = $BookingPress->bookingpress_get_settings('no_payment_method_is_selected_for_the_booking', 'message_setting');


            /* server side validation */
            $all_fields = $wpdb->get_results( "SELECT bookingpress_field_error_message,bookingpress_form_field_name,bookingpress_field_is_default FROM {$tbl_bookingpress_form_fields} WHERE bookingpress_field_required = 1 AND bookingpress_field_is_hide = 0 AND bookingpress_field_is_package_hide = 0" ); //phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared --Reason: $tbl_bookingpress_form_fields is a table name. false alarm            

			/* $all_required_fields = array(); */
			$service_visibility_field_arr = array();
			$field_validation_message = array();
			if ( ! empty( $all_fields ) ) {
				$is_required_validation = false;
				foreach ( $all_fields as $field_data ) {					
					if( isset($field_data->bookingpress_field_type) && 'password' == $field_data->bookingpress_field_type && (is_user_logged_in() || !empty($user_id)) ){
						continue;
					}
					$field_error_msg = $field_data->bookingpress_field_error_message;
                    $field_options = array();
                    if(isset($field_data->bookingpress_field_options)){
                        $field_options = json_decode($field_data->bookingpress_field_options, true);
                    }					
					$bookingpress_selected_service = isset($field_options['selected_services']) ? $field_options['selected_services']: '';   
                    if(!empty($field_options) && $field_options['visibility'] == 'always' &&  $field_data->bookingpress_form_field_name != '2 Col' && $field_data->bookingpress_form_field_name != '3 Col' && $field_data->bookingpress_form_field_name != '4 Col' ){
						$bpa_visible_field_key = '';
						if( $field_data->bookingpress_field_is_default == 1 ){
							if( $field_data->bookingpress_form_field_name == 'firstname'){
								$bpa_visible_field_key = 'customer_firstname';		
							}
							if( $field_data->bookingpress_form_field_name == 'lastname'){
								$bpa_visible_field_key = 'customer_lastname';		
							}
							if( $field_data->bookingpress_form_field_name == 'email_address'){
								$bpa_visible_field_key = 'customer_email';		
							}
							if( $field_data->bookingpress_form_field_name == 'note'){
								$bpa_visible_field_key = 'appointment_note';		
							}
							if( $field_data->bookingpress_form_field_name == 'phone_number'){
								$bpa_visible_field_key = 'customer_phone';		
							}
							if( $field_data->bookingpress_form_field_name == 'fullname'){
								$bpa_visible_field_key = 'customer_name';		
							}
							if( $field_data->bookingpress_form_field_name == 'username'){
								$bpa_visible_field_key = 'customer_username';		
							}
							if( $field_data->bookingpress_form_field_name == 'terms_and_conditions'){
								$bpa_visible_field_key = 'appointment_terms_conditions';		
							}
						} else {
							$bpa_visible_field_key = $field_data->bookingpress_field_meta_key;
						}

						if( 'password' == $field_data->bookingpress_field_type && empty( $posted_data['package_data']['form_fields'][$bpa_visible_field_key] ) ){
							continue;
						}

						$val = $posted_data['package_data']['form_fields'][ $bpa_visible_field_key ];

						if( $bpa_visible_field_key == 'appointment_terms_conditions'){

							if( empty($val[0])){
								$is_required_validation = true;
								$field_validation_message[] = $field_error_msg;
							}
						} else {
							if( '' === $val ){
								$is_required_validation = true;
								$field_validation_message[] = $field_error_msg;
							}
						}
					}

					if( !empty($field_options) && $field_options['visibility'] == 'services' &&  $field_data->bookingpress_form_field_name != '2 Col' && $field_data->bookingpress_form_field_name != '3 Col' && $field_data->bookingpress_form_field_name != '4 Col' ){
						$bookingpress_field_meta_key_val = $field_data->bookingpress_field_meta_key;

						if( $field_data->bookingpress_field_is_default == 1 ){
							if( $field_data->bookingpress_form_field_name == 'firstname'){
								$bookingpress_field_meta_key_val = 'customer_firstname';		
							}
							if( $field_data->bookingpress_form_field_name == 'lastname'){
								$bookingpress_field_meta_key_val = 'customer_lastname';		
							}
							if( $field_data->bookingpress_form_field_name == 'email_address'){
								$bookingpress_field_meta_key_val = 'customer_email';		
							}
							if( $field_data->bookingpress_form_field_name == 'note'){
								$bookingpress_field_meta_key_val = 'appointment_note';		
							}
							if( $field_data->bookingpress_form_field_name == 'phone_number'){
								$bookingpress_field_meta_key_val = 'customer_phone';		
							}
							if( $field_data->bookingpress_form_field_name == 'fullname'){
								$bookingpress_field_meta_key_val = 'customer_name';		
							}
							if( $field_data->bookingpress_form_field_name == 'username'){
								$bookingpress_field_meta_key_val = 'customer_username';		
							}
							if( $field_data->bookingpress_form_field_name == 'terms_and_conditions'){
								$bookingpress_field_meta_key_val = 'appointment_terms_conditions';		
							}
						} else {
							$bookingpress_field_meta_key_val = $field_data->bookingpress_field_meta_key;
						}
						$service_visibility_field_arr[$bookingpress_field_meta_key_val] = $bookingpress_selected_service;
					}
				}

			}
            
			if( true == $is_required_validation ){
				$response['variant'] = 'error';
				$response['title']   = esc_html__('Error', 'bookingpress-package');
				$response['msg']     = (!empty($field_validation_message)) ? implode(',', $field_validation_message) : array();
                if($return_data){
                    return $response;
                }                 
				echo wp_json_encode($response);	
                exit;
			}
            if( !empty( $posted_data ) ){
                

                $bookingpress_selected_package_id = (isset($posted_data['package_data']['bookingpress_selected_package_id']))?$posted_data['package_data']['bookingpress_selected_package_id']:'';
                $bookingpress_selected_package_detail = (isset($posted_data['package_data']['bookingpress_selected_package_detail']))?$posted_data['package_data']['bookingpress_selected_package_detail']:'';
				if (empty($bookingpress_selected_package_detail) || empty($bookingpress_selected_package_id)) {
					$response['variant'] = 'error';
					$response['title']   = esc_html__('Error', 'bookingpress-package');
					$response['msg']     = esc_html__('Package not selected.', 'bookingpress-package');
                    if($return_data){
                        return $response;
                    }                    
					echo wp_json_encode($response);
                    exit;
				}                                
				if (empty($posted_data['package_data']['selected_payment_method']) ) {
					$response['variant'] = 'error';
					$response['title']   = esc_html__('Error', 'bookingpress-package');
					$response['msg']     = $no_payment_method_is_selected_for_the_booking;
                    if($return_data){
                        return $response;
                    }                    
					echo  wp_json_encode($response);
                    exit;
				}
                $total_payable_amount = (isset($posted_data['package_data']['total_payable_amount']))?(float)$posted_data['package_data']['total_payable_amount']:'';
                if(empty($posted_data['package_data']['total_payable_amount']) || $total_payable_amount <= 0){
					$response['variant'] = 'error';
					$response['title']   = esc_html__('Error', 'bookingpress-package');
					$response['msg']     = esc_html__('Please enter valid package price.', 'bookingpress-package');
                    if($return_data){
                        return $response;
                    }                    
					echo wp_json_encode($response);  
                    exit;                  
                }

                $current_user_id = get_current_user_id();
                if(!empty($user_id)){
                    $current_user_id = $user_id;
                }
                
                $bookingpress_customer_id = $posted_data['package_data']['bookingpress_selected_package_id'];

                $bookingpress_selected_package_id = $posted_data['package_data']['bookingpress_selected_package_id'];
                $package_data = $bookingpress_package->get_package_by_id($bookingpress_selected_package_id);
                
                $bookingpress_package_customer_purchase_limit = (isset($package_data['bookingpress_package_customer_purchase_limit']))?$package_data['bookingpress_package_customer_purchase_limit']:0;
                if($bookingpress_package_customer_purchase_limit > 0){

                    $get_customer_total_purchase_package = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) as total FROM {$tbl_bookingpress_package_bookings} WHERE bookingpress_package_id = %d AND bookingpress_login_user_id = %d", $bookingpress_selected_package_id, $current_user_id ) ); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared --Reason: $tbl_bookingpress_package_bookings is a table name. false alarm
                    if($get_customer_total_purchase_package >= $bookingpress_package_customer_purchase_limit){   
                        
                        $package_purchase_limit_message = $BookingPress->bookingpress_get_customize_settings('package_purchase_limit_message', 'package_booking_form');
                        
                        $response['variant'] = 'error';
                        $response['title']   = esc_html__('Error', 'bookingpress-package');
                        $response['msg']     = $package_purchase_limit_message;
                        if($return_data){
                            return $response;
                        }                       
                        echo wp_json_encode($response);
                        exit();

                    }

                }

                /* New Validation added start */
                $authorization_token = !empty( $posted_data['package_data']['authorized_token'] ) ? $posted_data['package_data']['authorized_token'] : '';				
                $bookingpress_uniq_id = $posted_data['package_data']['bookingpress_uniq_id'];
                $authorization_time = $posted_data['package_data']['authorized_time'];                
                $verification_token_key = 'bookingpress_verify_payment_token_' .  $bookingpress_uniq_id . '_' . $authorization_time;

				if( wp_hash( $verification_token_key ) != $authorization_token ){
					$bookingpress_invalid_token = esc_html__('Sorry! package could not be processed', 'bookingpress-package');

                    $response['variant']       = 'error';
                    $response['title']         = esc_html__('Error', 'bookingpress-package');
                    $response['msg']           = $bookingpress_invalid_token;
                    $response['is_redirect']   = 0;
                    $response['reason']        = 'token mismatched ' . $authorization_token . ' --- ' . wp_hash( $verification_token ) . ' --- ' . $verification_token_key;
                    $response['redirect_data'] = '';
                    $response['is_spam']       = 0;
                    if($return_data){
                        return $response;
                    }
                    echo json_encode($response);
                    exit;
				}

                $bookingpress_total_price = !empty($posted_data['package_data']['total_payable_amount']) ? $posted_data['package_data']['total_payable_amount'] : 0;

				$bookingpress_total_payment_price = get_transient( $authorization_token );
				if( false !== $bookingpress_total_payment_price && $bookingpress_total_payment_price != $bookingpress_total_price ){
					$bookingpress_invalid_amount = esc_html__('Sorry! package could not be processed', 'bookingpress-package');

                    $response['variant']       = 'error';
                    $response['title']         = esc_html__('Error', 'bookingpress-package');
                    $response['msg']           = $bookingpress_invalid_amount;
                    $response['is_redirect']   = 0;
                    $response['reason']        = 'price mismatched.';
                    $response['redirect_data'] = '';
                    $response['is_spam']       = 0;
                    if($return_data){
                        return $response;
                    }
                    echo json_encode($response);
                    exit;
				}                

                /* New Validation added over */
                
                if(empty($bookingpress_customer_id)){
                    $bookingpress_current_user_obj = new WP_User($current_user_id); 
                    $bookingpress_customer_name  = ! empty($bookingpress_current_user_obj->data->user_login) ? $bookingpress_current_user_obj->data->user_login : '';
                    $bookingpress_customer_email = ! empty($bookingpress_current_user_obj->data->user_email) ? $bookingpress_current_user_obj->data->user_email : '';
                    $bookingpress_firstname      = get_user_meta($current_user_id, 'first_name', true);
                    $bookingpress_lastname       = get_user_meta($current_user_id, 'last_name', true);
                }

				$bookingpress_selected_payment_method = sanitize_text_field($posted_data['package_data']['selected_payment_method']);
				$bookingpress_currency_name           = $BookingPress->bookingpress_get_settings('payment_default_currency', 'payment_setting');
				$bookingpress_paypal_currency = $bookingpress_payment_gateways->bookingpress_paypal_supported_currency_list();            
				$bookingpress_is_support = 1;
				if ($bookingpress_selected_payment_method == 'paypal' && !in_array($bookingpress_currency_name,$bookingpress_paypal_currency ) ) {
					$bookingpress_is_support = 0;
				} else {					
					$bookingpress_is_support = apply_filters('bookingpress_pro_validate_currency_before_book_appointment',$bookingpress_is_support,$bookingpress_selected_payment_method,$bookingpress_currency_name);
				}
				if($bookingpress_is_support == 0){
					$response['variant'] = 'error';
					$response['title']   = esc_html__('Error', 'bookingpress-package');
					$response['msg']     = esc_html($unsupported_currecy_selected_for_the_payment);
                    if($return_data){
                        return $response;
                    }                    
					echo wp_json_encode($response);
                    exit;  
				}

                do_action('bookingpress_validate_package_form', $posted_data);
                /* New Validation Added Start */

                $bookingpress_package_data     = array_map( array( $BookingPress, 'appointment_sanatize_field' ), $posted_data['package_data'] );// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized --Reason $_REQUEST contains mixed array and will be sanitized using 'appointment_sanatize_field' function
                $bookingpress_payment_gateway  = ! empty( $bookingpress_package_data['selected_payment_method'] ) ? sanitize_text_field( $bookingpress_package_data['selected_payment_method'] ) : '';
                $bookingpress_total_price = !empty($bookingpress_package_data['total_payable_amount']) ? $bookingpress_package_data['total_payable_amount'] : 0;
                $payment_gateway  = $bookingpress_payment_gateway;

                $bookingpress_return_data = apply_filters( 'bookingpress_validate_submitted_package_order_booking_form', $payment_gateway, $bookingpress_package_data, $current_user_id );



                $bookingpress_return_data['selected_package_details'] = $bookingpress_package_data['bookingpress_selected_package_detail'];
                $bookingpress_return_data['page_url'] = !empty( $_POST['pageURL'] ) ? esc_url_raw( $_POST['pageURL'] ) : home_url();
                $entry_id = ! empty( $bookingpress_return_data['entry_id'] ) ? $bookingpress_return_data['entry_id'] : '';

                $bookingpress_package_name = (isset($bookingpress_return_data['selected_package_details']['bookingpress_package_name']))?$bookingpress_return_data['selected_package_details']['bookingpress_package_name']:'';


                if(!empty($entry_id)){


                    $currency_code                     = $bookingpress_return_data['currency_code'];
                    $bookingpress_final_payable_amount = isset( $bookingpress_return_data['payable_amount'] ) ? $bookingpress_return_data['payable_amount'] : 0;

					$order_id = "";                   
                    $customer_details                  = $bookingpress_return_data['customer_details'];
                    $customer_email                    = ! empty($customer_details['customer_email']) ? $customer_details['customer_email'] : '';

                    $bookingpress_service_name = ! empty($bookingpress_return_data['service_data']['bookingpress_service_name']) ? $bookingpress_return_data['service_data']['bookingpress_service_name'] : __('Appointment Booking', 'bookingpress-package');
                    $custom_var = $entry_id;


                    $bookingpress_payment_mode    = $BookingPress->bookingpress_get_settings('paypal_payment_mode', 'payment_setting');
                    $paypal_client_id = $BookingPress->bookingpress_get_settings( 'paypal_client_id', 'payment_setting' );
                    $paypal_client_secret = $BookingPress->bookingpress_get_settings( 'paypal_client_secret', 'payment_setting' );	                    

					$Sandbox = ($bookingpress_payment_mode == "sandbox")?true:false;
					$paypalClientID = $paypal_client_id;
					$paypalSecret = $paypal_client_secret;

					$token_url = 'https://api-m.paypal.com/v1/oauth2/token';
					$api_url = 'https://api-m.paypal.com/v2/checkout/orders';
					if ($Sandbox) {
						$token_url = 'https://api-m.sandbox.paypal.com/v1/oauth2/token';
						$api_url = 'https://api-m.sandbox.paypal.com/v2/checkout/orders';			
					}						
					$request_args = array(
						'headers'     => array(
							'Authorization' => 'Basic ' . base64_encode($paypalClientID . ':' . $paypalSecret),
						),
						'body'        => array(
							'grant_type' => 'client_credentials',
						),
					);			
					$response_return = wp_remote_post($token_url, $request_args);
					if (is_wp_error($response_return)) {
						$error = $response_return->get_error_code() . ': ' . $response_return->get_error_message();
						$response['variant'] = 'error';
						$response['title']   = esc_html__( 'Error', 'bookingpress-package' );
						$response['msg']     = $error;	
						wp_send_json( $response );
						die();					
					}					
					$auth_response = json_decode(wp_remote_retrieve_body($response_return));
					if(isset($auth_response->error_description) && isset($auth_response->error)){
						$response['variant'] = 'error';
						$response['title']   = esc_html__( 'Error', 'bookingpress-package' );
						$response['msg']     = $auth_response->error.' '.$auth_response->error_description;
						echo wp_json_encode( $response );
						die();						
					}                    
					if (empty($auth_response)) {
						wp_send_json( $response );
						die();									
					} else {				
						if (!empty($auth_response->access_token)) {
							$headers = array(
								'Content-Type' => 'application/json',
								'Authorization' => 'Bearer ' . $auth_response->access_token,
							);
							$body = array(
								'intent' => 'CAPTURE',												
								'purchase_units' => array(																					
									array(
										'reference_id'=> ''.$custom_var,
										'description' => $bookingpress_package_name,
										'amount' => array(
											'currency_code' => ''.$currency_code, 
											'value' => $bookingpress_final_payable_amount, 
										),
									),
								),
							);				
							
							$response_return = wp_remote_post(
								$api_url,
								array(
									'method' => 'POST',
									'headers' => $headers,
									'body' => wp_json_encode($body),
								)
							);
							if (is_wp_error($response_return)) {
								$error_message = $response_return->get_error_message();
								$response['variant'] = 'error';
								$response['title']   = esc_html__( 'Error', 'bookingpress-package' );
								$response['msg']     = "Something went wrong:";
								wp_send_json( $response );
								die();								
							} else {
								$response_body = wp_remote_retrieve_body($response_return);
								$order_data = json_decode($response_body, true);
								$order_id = (isset($order_data['id']))?$order_data['id']:'';
							}
						}				
					}

                    if(!empty($order_id)){

						$response['variant'] = 'success';
						$response['title']   = esc_html__( 'Success', 'bookingpress-package' );
						$response['msg']     = esc_html__( 'Appointment succesfully created.', 'bookingpress-package' );
						$response['order_id']  = $order_id;
						$response['paypal_success_url']  = '';
						$response['paypal_cancel_url']  = '';
						$response['paypal_booking_form_redirection_mode']  = '';                        

                        wp_send_json( $response );
                        die();	

                    }


                }
            }

            wp_send_json( $response );
            die();

        }

        function bookingpress_after_package_final_step_amount_func($bookingpress_after_package_final_step_amount){

            global $BookingPress;                
            $paypal_payment = $BookingPress->bookingpress_get_settings( 'paypal_payment', 'payment_setting' );
            if($paypal_payment == true){


                $paypal_payment_method_type = $BookingPress->bookingpress_get_settings( 'paypal_payment_method_type', 'payment_setting' );
                $paypal_client_id = $BookingPress->bookingpress_get_settings( 'paypal_client_id', 'payment_setting' );
                $paypal_client_secret = $BookingPress->bookingpress_get_settings( 'paypal_client_secret', 'payment_setting' );                     
                if($paypal_payment_method_type == 'popup' && !empty($paypal_client_id) && !empty($paypal_client_secret)){                    
                    $bookingpress_after_package_final_step_amount.='                                                
                        if(vm.package_step_form_data.selected_payment_method == "paypal"){
                            setTimeout(function(){
                                vm.select_payment_method(vm.package_step_form_data.selected_payment_method);
                            },800);                            
                        }
                    ';
                }


            }

            return $bookingpress_after_package_final_step_amount;
        }


        function bookingpress_after_selecting_payment_method_for_package_order_func($bookingpress_after_selecting_payment_method_data){

            global $BookingPress;                
            $paypal_payment = $BookingPress->bookingpress_get_settings( 'paypal_payment', 'payment_setting' );
            if($paypal_payment == true){

                $paypal_payment_method_type = $BookingPress->bookingpress_get_settings( 'paypal_payment_method_type', 'payment_setting' );
                $paypal_client_id = $BookingPress->bookingpress_get_settings( 'paypal_client_id', 'payment_setting' );
                $paypal_client_secret = $BookingPress->bookingpress_get_settings( 'paypal_client_secret', 'payment_setting' );

                if($paypal_payment_method_type == 'popup'){

                    $bookingpress_after_selecting_payment_method_data.='
                        var vm7 = this;
                        if(payment_method == "paypal"){
                            vm7.show_package_paypal_popup_button = "true";
                        }else{
                            vm7.show_package_paypal_popup_button = "false";
                        }
                    ';

                    if(empty($paypal_client_id)){					
                        $client_id_error = esc_html__( 'Client ID is required.', 'bookingpress-package' );
                        $bookingpress_after_selecting_payment_method_data.='
                            window.app.bookingpress_package_set_error_msg("'.$client_id_error.'");
                        ';		
                    }else if(empty($paypal_client_secret)){
                        $client_secret_error = esc_html__( 'Client secret is required.', 'bookingpress-package' );
                        $bookingpress_after_selecting_payment_method_data.='
                            window.app.bookingpress_package_set_error_msg("'.$client_secret_error.'");
                        ';		
                    }else{

                        $bookingpress_after_selecting_payment_method_data.='
                            if(payment_method == "paypal"){                                
                                var has_paypal_div = document.getElementById("paypal-package-button-container");
                                if(has_paypal_div){
                                    document.getElementById("paypal-package-button-container").innerHTML = "";
                                }    
                                var vm3 = this;                            
                                paypal.Buttons({
                                    async createOrder(data, actions) {


                                        

                                        var vm2 = this;											
                                        var bkp_wpnonce_pre = "'.wp_create_nonce( 'bpa_wp_nonce' ).'";
                                        var bkp_wpnonce_pre_fetch = document.getElementById("_wpnonce");										
                                        if(typeof bkp_wpnonce_pre_fetch=="undefined" || bkp_wpnonce_pre_fetch==null){
                                            bkp_wpnonce_pre_fetch = bkp_wpnonce_pre;
                                        }else {
                                            bkp_wpnonce_pre_fetch = bkp_wpnonce_pre_fetch.value;
                                        }
                                        
                                        await vm3.bookingpress_verify_total_payment_amount_package_v2();

                                        var final_order_id = "";	
                                        var postData = { action: "bookingpress_paypal_booking_validate_package", _wpnonce: bkp_wpnonce_pre_fetch,package_data:JSON.stringify(vm3.package_step_form_data)}
                                        var final_data = "";
                                        try {
                                            const response = await axios.post(appoint_ajax_obj.ajax_url, Qs.stringify( postData ));											
                                            if(response.data.variant != "error") {												
                                                if(typeof response.data.order_id != "undefined" && response.data.order_id != ""){
                                                    vm3.paypal_success_url = response.data.paypal_success_url;
                                                    vm3.paypal_cancel_url = response.data.paypal_cancel_url;
                                                    vm3.paypal_booking_form_redirection_mode = response.data.paypal_booking_form_redirection_mode;
                                                    return response.data.order_id;												
                                                }	
                                            }else{
                                                vm3.bookingpress_package_set_error_msg(response.data.msg);
                                                
                                                return 0;
                                            }											
                                        } catch (error) {
                                            vm3.bookingpress_package_set_error_msg("Failed to create PayPal order");
                                            return 0;
                                        }
            
                                    },																		
                                    onCancel: function(data){},
                                    onApprove: (data, actions) => {
                                        return actions.order.capture().then(function(orderData) {
                                            vm3.paypal_button_loader = "true";
                                            var bkp_wpnonce_pre = "'.wp_create_nonce( 'bpa_wp_nonce' ).'";
                                            var bkp_wpnonce_pre_fetch = document.getElementById("_wpnonce");										
                                            if(typeof bkp_wpnonce_pre_fetch=="undefined" || bkp_wpnonce_pre_fetch==null){
                                                bkp_wpnonce_pre_fetch = bkp_wpnonce_pre;
                                            }else {
                                                bkp_wpnonce_pre_fetch = bkp_wpnonce_pre_fetch.value;
                                            }
                                            var sca_confirm_booking_data = { action: "bookingpress_paypal_booking_payment_confirm_package", bookingpress_payment_res: orderData, _wpnonce: bkp_wpnonce_pre_fetch}											
                                            axios.post( appoint_ajax_obj.ajax_url, Qs.stringify( sca_confirm_booking_data ) )
                                            .then(function(response) {                                               
                                                if(response.data.variant != "error"){															                                                    
                                                    vm3.bookingpress_render_package_thankyou_content();
                                                }else{
                                                    setTimeout(function(){
                                                        vm3.paypal_button_loader = "false";
                                                    },500);                                                     
                                                    vm3.bookingpress_package_set_error_msg(response.data.msg);
                                                }                                                
                                            }).catch(function(error){
												setTimeout(function(){
													vm3.paypal_button_loader = "false";
												},500);                                                
                                                console.log(error);
                                            });

                                        });									
                                    },
                                    style: {
                                      layout: "vertical",                                     
                                      color: "gold", 
                                      shape: "pill", 
                                      label: "paypal", 
                                      fundingicons: false, 
                                    }    
                                }).render("#paypal-package-button-container");

                            }else{
                                document.getElementById("paypal-package-button-container").innerHTML = "";
                            }
                        ';            
                    }
                }

            }


            return $bookingpress_after_selecting_payment_method_data;
        }

		/**
		 * bpa function for get customer package order list 
		 *
		 * @param  mixed $user_detail
		 * @return void
		*/        
		function bookingpress_bpa_get_package_thankyou_func($user_detail=array()){
            		
			global $BookingPress,$wpdb,$tbl_bookingpress_customers,$tbl_bookingpress_categories,$BookingPressPro,$tbl_bookingpress_staffmembers,$tbl_bookingpress_package_bookings,$tbl_bookingpress_payment_logs,$bookingpress_global_options,$bookingpress_package;

			$result = array();						
			$result["thankyou_data"] = array();
			$response = array('status' => 0, 'message' => '', 'response' => array('result' => $result));            

            if(class_exists('BookingPressPro') && method_exists( $BookingPressPro, 'bookingpress_bpa_check_valid_connection_callback_func') && $BookingPressPro->bookingpress_bpa_check_valid_connection_callback_func()){
                $entry_id = isset($user_detail['entry_id']) ? intval($user_detail['entry_id']) : '';
                $return_data = array();
                if(!empty($entry_id)){                    
                    $result["thankyou_data"] = $this->bookingpress_bpa_get_package_thankyou_page_using_entry_id_func($entry_id);
                    $response = array('status' => 1, 'message' => '', 'response' => array('result' => $result));
                }
            }
            return $response;

        }

		/**
		 * bpa function for get customer package order list 
		 *
		 * @param  mixed $user_detail
		 * @return void
		*/        
		function bookingpress_bpa_get_package_thankyou_page_using_entry_id_func($entry_id){
            global $wpdb,$BookingPress,$tbl_bookingpress_package_bookings,$BookingPressPro,$bookingpress_package;
            $return_data = array();
            if(!empty($entry_id)){

                $bookingpress_package_order_details = $wpdb->get_row($wpdb->prepare("SELECT bookingpress_package_services,bookingpress_package_id,bookingpress_package_no,bookingpress_customer_name,bookingpress_customer_firstname,bookingpress_customer_lastname, bookingpress_username,	bookingpress_package_name FROM {$tbl_bookingpress_package_bookings} WHERE bookingpress_entry_id = %d", $entry_id), ARRAY_A); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- Reason: $bookingpress_appointment_details is table name defined globally. False Positive alarm
                if(!empty($bookingpress_package_order_details)){
                    
                    $bookingpress_customer_name = $bookingpress_package_order_details['bookingpress_customer_name'];
                    if(empty($bookingpress_customer_name)){
                        $bookingpress_customer_name = $bookingpress_package_order_details['bookingpress_customer_firstname'].' '.$bookingpress_package_order_details['bookingpress_customer_lastname'];
                    }
                    if(empty($bookingpress_package_order_details['bookingpress_customer_firstname']) && empty($bookingpress_package_order_details['bookingpress_customer_lastname'])){
                        $bookingpress_customer_name = $bookingpress_package_order_details['bookingpress_username'];
                    }
                    $return_data['bookingpress_package_no'] = $bookingpress_package_order_details['bookingpress_package_no'];
                    $return_data['bookingpress_customer_name'] = $bookingpress_customer_name;
                    $return_data['bookingpress_package_name'] = $bookingpress_package_order_details['bookingpress_package_name'];                                        
                    $bookingpress_package_services_ids = array();
                    $bookingpress_package_services = $bookingpress_package_order_details['bookingpress_package_services'];
                    if(!empty($bookingpress_package_services)){
                        $bookingpress_package_services_arr = json_decode($bookingpress_package_services,true);
                        if(!empty($bookingpress_package_services_arr)){
                            foreach($bookingpress_package_services_arr as $servarr){
                                $bookingpress_package_services_ids[] = $servarr['bookingpress_service_id'];
                            }
                        }
                        $return_data['bookingpress_package_services_ids'] = $bookingpress_package_services_ids;
                    }
                    if($bookingpress_package->is_multi_language_addon_active()){
                        if(method_exists( $BookingPressPro, 'bookingpress_pro_front_language_translation_func') ) {
                            $bookingpress_package_id = $bookingpress_package_order_details['bookingpress_package_id'];
                            $return_data['bookingpress_package_name'] = $BookingPressPro->bookingpress_pro_front_language_translation_func($return_data['bookingpress_package_name'],'package','bookingpress_package_name',$bookingpress_package_id);                            
                        }
                    }                    
                    
                }
            }

            return $return_data;          
        }

		/**
		 * bpa function for get customer package order list 
		 *
		 * @param  mixed $user_detail
		 * @return void
		*/        
		function bookingpress_bpa_package_booking_func($user_detail=array()){
            		
			global $BookingPress,$wpdb,$tbl_bookingpress_customers,$tbl_bookingpress_categories,$BookingPressPro,$tbl_bookingpress_staffmembers,$tbl_bookingpress_package_bookings,$tbl_bookingpress_payment_logs,$bookingpress_global_options,$bookingpress_package;

			$result = array();			
			$result["total_records"] = 0;
			$result["items"] = array();
			$response = array('status' => 0, 'message' => '', 'response' => array('result' => $result));            

            if(class_exists('BookingPressPro') && method_exists( $BookingPressPro, 'bookingpress_bpa_check_valid_connection_callback_func') && $BookingPressPro->bookingpress_bpa_check_valid_connection_callback_func()){
                $user_id = isset($user_detail['user_id']) ? intval($user_detail['user_id']) : '';
                $package_booking_detail = isset($user_detail['package_booking_detail']) ? $user_detail['package_booking_detail'] : '';
                $bookingpress_nonce = isset($user_detail['bookingpress_nonce']) ? sanitize_text_field($user_detail['bookingpress_nonce']) : '';
                $bpa_login_customer_id = $user_id;
				if(!empty($bookingpress_nonce)){
					$_REQUEST['_wpnonce'] = $bookingpress_nonce;
				}else{
					$bookingpress_nonce = wp_create_nonce('bpa_wp_nonce');
					$_REQUEST['_wpnonce'] = $bookingpress_nonce;					
				}
                if(!empty($package_booking_detail)){                    
                    $_REQUEST['package_data'] = $package_booking_detail;  
                    $_POST = $_REQUEST;
                    $bookingpress_response = $this->bookingpress_before_book_package_validate_func(true,$user_id);
                    $bookingpress_check_response = (isset($bookingpress_response['variant']))?$bookingpress_response['variant']:'';                
                    if($bookingpress_check_response == 'error'){                    
                        $message = (isset($bookingpress_response['msg']))?$bookingpress_response['msg']:'';
                        $response = array('status' => 0, 'message' => $message, 'response' => array('result' => $result));					
                    }else{
                        $result = $bookingpress_response;                                            
                        $response = array('status' => 1, 'message' => '', 'response' => array('result' => $result));					
                    }                    
                }

            }
            return $response;

        }

		/**
		 * bpa function for get packages list
		 *
		 * @param  mixed $user_detail
		 * @return void
		*/        
		function bookingpress_bpa_get_packages_func($user_detail=array()){	
            		
			global $BookingPress,$wpdb,$tbl_bookingpress_packages,$tbl_bookingpress_services, $tbl_bookingpress_package_services, $tbl_bookingpress_package_images, $BookingPress,$bookingpress_package, $BookingPress,$bookingpress_global_options,$BookingPressPro;

			$result = array();			
			$result["total_records"] = 0;
			$result["items"] = array();
			$response = array('status' => 0, 'message' => '', 'response' => array('result' => $result));            

            if(class_exists('BookingPressPro') && method_exists( $BookingPressPro, 'bookingpress_bpa_check_valid_connection_callback_func') && $BookingPressPro->bookingpress_bpa_check_valid_connection_callback_func()){

                $bookingpress_nonce = isset($user_detail['bookingpress_nonce']) ? sanitize_text_field($user_detail['bookingpress_nonce']) : '';
                $user_id = isset($user_detail['user_id']) ? intval($user_detail['user_id']) : '';
                $search_filter = isset($user_detail['search_filter']) ? $user_detail['search_filter'] : '';					
                $perpage     = isset($user_detail['per_page']) ? intval($user_detail['per_page']) : 10;
                $currentpage = isset($user_detail['current_page']) ? intval($user_detail['current_page']) : 1;
                $package_id     = isset($user_detail['package_id']) ? intval($user_detail['package_id']) : '';
                $offset      = (!empty($currentpage) && $currentpage > 1 ) ? ( ( $currentpage - 1 ) * $perpage ) : 0;
                $bpa_login_customer_id = $user_id;
				if(!empty($bookingpress_nonce)){
					$_REQUEST['_wpnonce'] = $bookingpress_nonce;
				}else{
					$bookingpress_nonce = wp_create_nonce('bpa_wp_nonce');
					$_REQUEST['_wpnonce'] = $bookingpress_nonce;					
				}
                $_REQUEST['perpage'] = $perpage;
                $_REQUEST['currentpage'] = $currentpage;               
                if(isset($search_filter['package_search'])){
                    $new_search_filter = array();
                    $new_search_filter['package_name'] = $search_filter['package_search'];
                    $search_filter = $new_search_filter;
                    $_REQUEST['search_data'] = $search_filter;
                }                               
                if($package_id > 0){
                    $_REQUEST['default_package'] = $package_id;     
                }
                $_POST = $_REQUEST;
                $bookingpress_response = $this->bookingpress_get_front_packages_func(true);
				$bookingpress_check_response = (isset($bookingpress_response['variant']))?$bookingpress_response['variant']:'';
				if($bookingpress_check_response == 'error'){					
					$message = (isset($bookingpress_response['msg']))?$bookingpress_response['msg']:'';
					$response = array('status' => 0, 'message' => $message, 'response' => array('result' => $result));					
				}else{
					$result = $bookingpress_response;
					$response = array('status' => 1, 'message' => '', 'response' => array('result' => $result));					
				}                              
            }
            return $response;
        }

		/**
		 * bpa function for get customer package order list 
		 *
		 * @param  mixed $user_detail
		 * @return void
		*/        
		function bookingpress_bpa_get_customer_package_order_func($user_detail=array()){
            		
			global $BookingPress,$wpdb,$tbl_bookingpress_customers,$tbl_bookingpress_categories,$BookingPressPro,$tbl_bookingpress_staffmembers,$tbl_bookingpress_package_bookings,$tbl_bookingpress_payment_logs,$bookingpress_global_options,$bookingpress_package;

			$result = array();			
			$result["total_records"] = 0;
			$result["items"] = array();
			$response = array('status' => 0, 'message' => '', 'response' => array('result' => $result));            

            if(class_exists('BookingPressPro') && method_exists( $BookingPressPro, 'bookingpress_bpa_check_valid_connection_callback_func') && $BookingPressPro->bookingpress_bpa_check_valid_connection_callback_func()){

                $user_id = isset($user_detail['user_id']) ? intval($user_detail['user_id']) : '';
                $search_filter = isset($user_detail['search_filter']) ? $user_detail['search_filter'] : '';					
                $perpage     = isset($user_detail['per_page']) ? intval($user_detail['per_page']) : 10;
                $currentpage = isset($user_detail['current_page']) ? intval($user_detail['current_page']) : 1;
                $offset      = (!empty($currentpage) && $currentpage > 1 ) ? ( ( $currentpage - 1 ) * $perpage ) : 0;
                $bookingpress_nonce = isset($user_detail['bookingpress_nonce']) ? sanitize_text_field($user_detail['bookingpress_nonce']) : '';
                $bpa_login_customer_id = $user_id;
                
				if(!empty($bookingpress_nonce)){
					$_REQUEST['_wpnonce'] = $bookingpress_nonce;
				}else{
					$bookingpress_nonce = wp_create_nonce('bpa_wp_nonce');
					$_REQUEST['_wpnonce'] = $bookingpress_nonce;					
				}
                $_REQUEST['perpage'] = $perpage;
                $_REQUEST['currentpage'] = $currentpage;    
                $new_search_filter = array();           
                if(isset($search_filter['search_package'])){                    
                    $new_search_filter['search_package'] = $search_filter['search_package'];
                } 
                if(isset($search_filter['expire_date_range'][0]) && isset($search_filter['expire_date_range'][1])){                    
                    $new_search_filter['selected_date_range'][] = $search_filter['expire_date_range'][0];
                    $new_search_filter['selected_date_range'][] = $search_filter['expire_date_range'][1];
                }                                               
                if(!empty($new_search_filter)){
                    $search_filter = $new_search_filter;
                    $_REQUEST['search_data'] = $search_filter;  
                }
                $_POST = $_REQUEST;

                $bookingpress_response = $this->bookingpress_get_customer_package_order_func(true,$user_id);
				$bookingpress_check_response = (isset($bookingpress_response['variant']))?$bookingpress_response['variant']:'';
				if($bookingpress_check_response == 'error'){					
					$message = (isset($bookingpress_response['msg']))?$bookingpress_response['msg']:'';
					$response = array('status' => 0, 'message' => $message, 'response' => array('result' => $result));					
				}else{
					$result = $bookingpress_response;
                    $items = (isset($bookingpress_response['items']))?$bookingpress_response['items']:array();
                    if(!empty($items)){
                        foreach($items as $k => $item_val){								
							foreach($items[$k] as $newkey => $newval){																	
                            	if(gettype($newval) === 'string' && is_array(json_decode($newval,true))){
                                	$items[$k][$newkey] = json_decode($newval,true);
                            	}
							}
                        }
                        $result['items'] = $items;
                    }                     
					$response = array('status' => 1, 'message' => '', 'response' => array('result' => $result));					
				}

            }
            return $response;

        }

        function bookingpress_generate_package_token_func(){
            global $wpdb, $bookingpress_spam_protection;
            $response              = array();
            $wpnonce               = isset($_REQUEST['_wpnonce']) ? sanitize_text_field($_REQUEST['_wpnonce']) : '';
            $bpa_verify_nonce_flag = wp_verify_nonce($wpnonce, 'bpa_wp_nonce');
            $response['updated_nonce'] = '';
            if (! $bpa_verify_nonce_flag ) {

                $response['variant'] = 'error';
                $response['title']   = esc_html__('Error', 'bookingpress-package');
                $response['msg']     = esc_html__('Sorry, Your request can not process due to security reason.', 'bookingpress-package');
                $response['updated_nonce'] = esc_html(wp_create_nonce('bpa_wp_nonce'));
                wp_send_json($response);
                die();
            }

            if(session_id() == '' OR session_status() === PHP_SESSION_NONE) {
                session_start();
            }

            $bookingpress_spam_captcha    = $bookingpress_spam_protection->bookingpress_generate_captcha_code(12);
            $_SESSION['bpa_package_filter_input'] = md5($bookingpress_spam_captcha);

            $response['variant']     = 'success';
            $response['title']       = esc_html__('Success', 'bookingpress-package');
            $response['msg']         = esc_html__('Captcha generated successfully.', 'bookingpress-package');
            $response['captcha_val'] = $bookingpress_spam_captcha;
            wp_send_json($response);
            die();
        }

        function bookingpress_generate_my_booking_css($bookingpress_customize_css_content,$bookingpress_custom_data_arr) {
            $content_color  = $bookingpress_custom_data_arr['my_booking_form']['content_color'];
            $bookingpress_customize_css_content .= ".bpa-front-cp-my-appointment span.material-icons-round.bpp-apc__package-icon {fill: $content_color !important}";
            return $bookingpress_customize_css_content;
        }
        
        /**
         * bookingpress_modify_my_appointment_data_func_package
         *
         * @param  mixed $appointments_data
         * @param  mixed $k
         * @return void
         */
        function bookingpress_modify_my_appointment_data_func_package( $appointments_data, $k){
            $package_data = isset($appointments_data[$k]['bookingpress_applied_package_data']) ? $appointments_data[$k]['bookingpress_applied_package_data'] : array();
            if(!empty($package_data)){
                $appointment_package_data = json_decode($package_data, true);
                $appointments_data[$k]['bookingpress_package_name'] = isset($appointment_package_data['bookingpress_package_name']) ? $appointment_package_data['bookingpress_package_name'] : '';
            }
            return $appointments_data;
        }
        
        /**
         * bookingpress_integration_connect_extra_link
         *
         * @return void
         */
        function add_additional_information_outside_before_for_package(){
            global $BookingPress;
            $my_package_appointment_package_name = $BookingPress->bookingpress_get_customize_settings('my_package_appointment_package_name', 'booking_my_booking');
        ?>
            <div class="bpa-vac-bd__row" v-if="undefined != typeof scope.row.bookingpress_package_name && undefined != scope.row.bookingpress_purchase_type && scope.row.bookingpress_purchase_type == 3">
                <div class="bpa-bd__item" >
                    <div class="bpa-item--label"><?php echo esc_html($my_package_appointment_package_name); ?>:</div>  
                    <div class="bpa-item--val">
                        {{scope.row.bookingpress_package_name}}
                    </div>
                </div>
            </div>
            <?php
        }
        
        /**
         * bookingpress_my_booking_extra_icons_func
         *
         * @return void
         */
        function bookingpress_my_booking_extra_icons_package_func(){
            ?>
           <el-tooltip content="<?php esc_html_e('Package Transaction', 'bookingpress-package'); ?>" placement="top" v-if="('undefined' != typeof scope.row.bookingpress_purchase_type  && scope.row.bookingpress_purchase_type == 3)">			
                <span class="material-icons-round bpp-apc__package-icon"> 
                    <svg width="18" height="18" viewBox="0 0 18 18" xmlns="http://www.w3.org/2000/svg">
                    <path d="M7.98832 9.995H2.14277C1.6796 9.995 1.30061 10.3739 1.30061 10.8371V17.1579C1.30061 17.6211 1.6796 18 2.14277 18H8.08661C8.28473 18 8.28473 17.7872 8.28473 17.7872V10.2812C8.28467 10.2812 8.28467 9.995 7.98832 9.995ZM15.849 9.995H10.0094C9.65375 9.995 9.707 10.3654 9.707 10.3654V17.794C9.707 17.794 9.70412 17.9998 9.91806 17.9998H15.8489C16.3121 17.9998 16.6911 17.6209 16.6911 17.1577V10.8371C16.6912 10.3739 16.3122 9.995 15.849 9.995ZM8.28467 5.02167C8.28467 5.02167 8.28467 4.73631 8.00268 4.73631H1.19177C0.7286 4.73631 0.349609 5.1153 0.349609 5.57841V8.20717C0.349609 8.67034 0.7286 9.04927 1.19177 9.04927H8.02575C8.28467 9.04927 8.28467 8.82471 8.28467 8.82471V5.02167ZM16.8 4.73631H9.96733C9.70713 4.73631 9.70713 4.98425 9.70713 4.98425V8.82992C9.70713 8.82992 9.70713 9.04927 10.0235 9.04927H16.8C17.2631 9.04927 17.6421 8.67034 17.6421 8.20717V5.57841C17.6421 5.1153 17.2631 4.73631 16.8 4.73631ZM5.4203 4.11325C5.03499 4.11325 4.68306 4.08269 4.37444 4.02238C3.59057 3.86924 3.05181 3.57118 2.72737 3.11126C2.43667 2.69907 2.3477 2.19093 2.46286 1.60088C2.6646 0.568517 3.35791 0 4.41494 0C4.63864 0 4.88431 0.0258305 5.14519 0.0768166C5.8088 0.20646 6.65759 0.586985 7.41576 1.0947C8.70207 1.95619 8.76569 2.49175 8.70164 2.8197C8.60746 3.30158 8.15705 3.64591 7.32464 3.87243C6.76226 4.02545 6.06815 4.11325 5.4203 4.11325ZM4.415 1.34975C4.00564 1.34975 3.86495 1.46412 3.78771 1.85967C3.72451 2.18308 3.80421 2.29603 3.83035 2.33315C3.9398 2.48838 4.22492 2.61784 4.63324 2.69754C4.85357 2.74061 5.1258 2.76337 5.42024 2.76337C6.06772 2.76337 6.63814 2.66527 7.01664 2.55796C7.04419 2.55016 7.08696 2.51759 7.04112 2.49028C6.54629 2.08718 5.641 1.54891 4.88639 1.40147C4.71061 1.36724 4.55195 1.34975 4.415 1.34975ZM12.5909 4.11325H12.5908C11.943 4.11325 11.2489 4.02545 10.6865 3.87243C9.85407 3.64597 9.40372 3.30158 9.30954 2.81976C9.24555 2.49182 9.30905 1.95625 10.5955 1.09476C11.3535 0.587046 12.2023 0.206521 12.8661 0.076878C13.1269 0.0258919 13.3726 6.13551e-05 13.5961 6.13551e-05C14.6534 6.13551e-05 15.3466 0.568639 15.5482 1.601C15.6635 2.19099 15.5746 2.69913 15.2838 3.11132C14.9594 3.5713 14.4207 3.8693 13.6366 4.02244C13.3281 4.08263 12.9762 4.11325 12.5909 4.11325ZM10.9809 2.48194C10.937 2.5074 10.9582 2.54759 10.981 2.55415C11.3593 2.66294 11.9357 2.76344 12.5908 2.76344C12.8854 2.76344 13.1575 2.74067 13.3779 2.6976C13.7861 2.61784 14.0714 2.48844 14.1808 2.33321C14.207 2.29609 14.2868 2.18314 14.2234 1.85974C14.1462 1.46418 14.0055 1.34981 13.5961 1.34981C13.4592 1.34981 13.3006 1.36724 13.1247 1.4016C12.3701 1.54897 11.4757 2.07877 10.9809 2.48194Z"/>
                    </svg>      
                </span>          
            </el-tooltip>   
        <?php 
        }

        function bookingpress_package_order_paypal_submission_data( $response, $bookingpress_return_data ){

            
            if( !empty( $bookingpress_return_data ) ){
                global $bookingpress_paypal, $BookingPress;
                /* $bookingpress_paypal->arm_init_paypal();

                $entry_id                          = $bookingpress_return_data['entry_id'];
				$currency                          = $bookingpress_return_data['currency'];

                $currency_symbol                   = $BookingPress->bookingpress_get_currency_code( $currency );
                $bookingpress_final_payable_amount = isset( $bookingpress_return_data['payable_amount'] ) ? $bookingpress_return_data['payable_amount'] : 0;
                $customer_details                  = $bookingpress_return_data['customer_details'];
				$customer_email                    = ! empty( $customer_details['customer_email'] ) ? $customer_details['customer_email'] : '';

                
                $bookingpress_is_cart = !empty($bookingpress_return_data['is_cart']) ? 1 : 0;
				$custom_var = $entry_id."|".$bookingpress_is_cart;
                
				$sandbox = $bookingpress_paypal->bookingpress_is_sandbox_mode ? 'sandbox.' : '';
                
                $notify_url = $bookingpress_return_data['notify_url'];
                
                $redirect_url = $bookingpress_return_data['page_url'] . '?bpa_page=return_url'; */
                
                $bookingpress_return_data['service_data']['bookingpress_service_name'] = !empty( $bookingpress_return_data['selected_package_details']['bookingpress_package_name'] ) ? $bookingpress_return_data['selected_package_details']['bookingpress_package_name'] : __( 'BookingPress Package', 'bookingpress-package' );
                /* echo "<pre>";
                print_r( $bookingpress_return_data );
                echo "</pre>"; */

                $response = $bookingpress_paypal->bookingpress_submit_form_data( $response, $bookingpress_return_data );
            }

            return $response;
        }

        function bookingpress_modified_package_language_translate_fields_func($bookingpress_customize_my_booking_language_translate_fields)
        {
            $bookingpress_customize_my_booking_language_translate_fields['customized_my_booking_my_package_messages'] = array(
                
                'my_package_tab_title' => array('field_type'=>'text','field_label'=>__('My package tab title', 'bookingpress-package'),'save_field_type'=>'booking_my_booking'),

                'my_package_title' => array('field_type'=>'text','field_label'=>__('My Package title', 'bookingpress-package'),'save_field_type'=>'booking_my_booking'),

                'my_package_search_start_date_title' => array('field_type'=>'text','field_label'=>__('Start date placeholder', 'bookingpress-package'),'save_field_type'=>'booking_my_booking'),

                'my_package_search_end_date_title' => array('field_type'=>'text','field_label'=>__('End date placeholder', 'bookingpress-package'),'save_field_type'=>'booking_my_booking'),

                'my_package_search_package_text_placeholder' => array('field_type'=>'text','field_label'=>__('Search package placeholder', 'bookingpress-package'),'save_field_type'=>'booking_my_booking'),

                'my_package_search_package_btn_placeholder' => array('field_type'=>'text','field_label'=>__('Apply button', 'bookingpress-package'),'save_field_type'=>'booking_my_booking'),

                'my_package_id_title' => array('field_type'=>'text','field_label'=>__('ID title', 'bookingpress-package'),'save_field_type'=>'booking_my_booking'),

                'my_package_pkg_name_title' => array('field_type'=>'text','field_label'=>__('Package name title', 'bookingpress-package'),'save_field_type'=>'booking_my_booking'),

                'my_package_pkg_remin_appointment_title' => array('field_type'=>'text','field_label'=>__('Remaining appointment title', 'bookingpress-package'),'save_field_type'=>'booking_my_booking'),

                'my_package_pkg_expoire_on_title' => array('field_type'=>'text','field_label'=>__('Expire on title', 'bookingpress-package'),'save_field_type'=>'booking_my_booking'),

                'my_package_pkg_payment_title' => array('field_type'=>'text','field_label'=>__('Payment title', 'bookingpress-package'),'save_field_type'=>'booking_my_booking'),

                'my_package_booking_id_title' => array('field_type'=>'text','field_label'=>__('Booking id title', 'bookingpress-package'),'save_field_type'=>'booking_my_booking'),

                'my_package_pkg_purchase_date_title' => array('field_type'=>'text','field_label'=>__('Purchase date title', 'bookingpress-package'),'save_field_type'=>'booking_my_booking'),

                'my_package_pkg_expiry_date_title' => array('field_type'=>'text','field_label'=>__('Expiry date title', 'bookingpress-package'),'save_field_type'=>'booking_my_booking'),

                'my_package_pkg_services_title' => array('field_type'=>'text','field_label'=>__('Services title', 'bookingpress-package'),'save_field_type'=>'booking_my_booking'),

                'my_package_service_name_title' => array('field_type'=>'text','field_label'=>__('Service name title', 'bookingpress-package'),'save_field_type'=>'booking_my_booking'),

                'my_package_service_duration_title' => array('field_type'=>'text','field_label'=>__('Service duration title', 'bookingpress-package'),'save_field_type'=>'booking_my_booking'),

                'my_package_service_remain_appoint_title' => array('field_type'=>'text','field_label'=>__('Service remainging appointment title', 'bookingpress-package'),'save_field_type'=>'booking_my_booking'),

                'my_package_pkg_payment_details_title' => array('field_type'=>'text','field_label'=>__('Payment details title', 'bookingpress-package'),'save_field_type'=>'booking_my_booking'),

                'my_package_total_amount_title' => array('field_type'=>'text','field_label'=>__('Total amount title', 'bookingpress-package'),'save_field_type'=>'booking_my_booking'),

                'my_package_appointment_package_name' => array('field_type'=>'text','field_label'=>__('Appointment Package name', 'bookingpress-package'),'save_field_type'=>'booking_my_booking'),
            ); 
            return $bookingpress_customize_my_booking_language_translate_fields;
        }         

        function bookingpress_modified_package_language_translate_fields_section_func($bookingpress_all_language_translation_fields_section){
			/* Function to add package step heading */
            $bookingpress_package_step_section_added = array('customized_my_booking_my_package_messages' => __('My package labels', 'bookingpress-package') );
			$bookingpress_all_language_translation_fields_section = array_merge($bookingpress_all_language_translation_fields_section,$bookingpress_package_step_section_added);

            /*Package Booking Section Labels */
            $bookingpress_all_language_translation_fields_section['customized_package_booking_field_labels'] =  __('Common field labels', 'bookingpress-package');
            $bookingpress_all_language_translation_fields_section['customized_package_booking_user_detail_step_label'] =  __('User Details step labels', 'bookingpress-package');
            $bookingpress_all_language_translation_fields_section['customized_package_booking_login_related_labels'] =  __('Login Related labels', 'bookingpress-package'); 
            $bookingpress_all_language_translation_fields_section['customized_package_booking_forgot_password_labels'] =  __('Forgot Password related labels', 'bookingpress-package'); 
            $bookingpress_all_language_translation_fields_section['customized_package_booking_signup_form_labels'] =  __('SignUp related labels', 'bookingpress-package'); 
            $bookingpress_all_language_translation_fields_section['customized_package_booking_basic_details_labels'] =  __('Basic Details labels', 'bookingpress-package'); 
            $bookingpress_all_language_translation_fields_section['customized_package_booking_make_payment_labels'] =  __('Make Payment Form labels', 'bookingpress-package'); 
            $bookingpress_all_language_translation_fields_section['customized_package_booking_summary_step_labels'] =  __('Summary step labels', 'bookingpress-package'); 
            /*Package Booking Section Labels */

			return $bookingpress_all_language_translation_fields_section;
		}  
        


        function bookingpress_package_render_external_thankyou_content_func(){
			global $BookingPress, $wpdb, $tbl_bookingpress_appointment_bookings, $tbl_bookingpress_entries,$tbl_bookingpress_package_bookings,$bookingpress_package,$BookingPressPro;
			$response              = array();
			$response['variant'] = 'error';
			$response['title']   = esc_html__( 'Error', 'bookingpress-package' );
			$wpnonce               = isset( $_REQUEST['_wpnonce'] ) ? sanitize_text_field( $_REQUEST['_wpnonce'] ) : '';
			$bpa_verify_nonce_flag = wp_verify_nonce( $wpnonce, 'bpa_wp_nonce' );
			if ( ! $bpa_verify_nonce_flag ) {
				$response['msg']     = esc_html__( 'Sorry, Your request can not be processed due to security reason.', 'bookingpress-package' );
				echo wp_json_encode( $response );
				die();
			}
			$bookingpress_uniq_id = !empty($_POST['bookingpress_uniq_id']) ? sanitize_text_field( $_POST['bookingpress_uniq_id'] ) : '';

            $package_order_entry_id = !empty($_POST['package_order_entry_id']) ? sanitize_text_field( $_POST['package_order_entry_id'] ) : '';
            $bookingpress_payment_is_success = !empty($_POST['is_success']) ? sanitize_text_field( $_POST['is_success'] ) : '';
            $return_data = array('bookingpress_package_no'=>'','bookingpress_customer_name'=>'','bookingpress_package_name'=>'','bookingpress_default_appointment_booking_page_url'=>'');

            if(($bookingpress_payment_is_success == 1 || $bookingpress_payment_is_success == '1') && !empty($package_order_entry_id)){

                $bookingpress_entry_id = base64_decode($package_order_entry_id);                
                $bookingpress_package_order_details = $wpdb->get_row($wpdb->prepare("SELECT bookingpress_package_services,bookingpress_package_id,bookingpress_package_no,bookingpress_customer_name,bookingpress_customer_firstname,bookingpress_customer_lastname, bookingpress_username,	bookingpress_package_name FROM {$tbl_bookingpress_package_bookings} WHERE bookingpress_entry_id = %d", $bookingpress_entry_id), ARRAY_A); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- Reason: $bookingpress_appointment_details is table name defined globally. False Positive alarm                
                if(!empty($bookingpress_package_order_details)){  

                    $bookingpress_customer_name = $bookingpress_package_order_details['bookingpress_customer_name'];
                    if(empty($bookingpress_customer_name)){
                        $bookingpress_customer_name = $bookingpress_package_order_details['bookingpress_customer_firstname'].' '.$bookingpress_package_order_details['bookingpress_customer_lastname'];
                    }
                    if(empty($bookingpress_package_order_details['bookingpress_customer_firstname']) && empty($bookingpress_package_order_details['bookingpress_customer_lastname'])){
                        $bookingpress_customer_name = $bookingpress_package_order_details['bookingpress_username'];
                    }
                    $return_data['bookingpress_package_no'] = $bookingpress_package_order_details['bookingpress_package_no'];
                    $return_data['bookingpress_customer_name'] = $bookingpress_customer_name;
                    $return_data['bookingpress_package_name'] = $bookingpress_package_order_details['bookingpress_package_name'];

                    if($bookingpress_package->is_multi_language_addon_active()){

                        if(method_exists( $BookingPressPro, 'bookingpress_pro_front_language_translation_func') ) {
                            $bookingpress_package_id = $bookingpress_package_order_details['bookingpress_package_id'];
                            $return_data['bookingpress_package_name'] = $BookingPressPro->bookingpress_pro_front_language_translation_func($return_data['bookingpress_package_name'],'package','bookingpress_package_name',$bookingpress_package_id);
                            
                        }
                    }                    
                    $package_appointment_btn_services = $BookingPress->bookingpress_get_customize_settings('package_appointment_btn_services', 'package_booking_form');
                    if($package_appointment_btn_services == 'package_services'){
                        $bookingpress_package_services_ids = array();
                        $bookingpress_package_services = $bookingpress_package_order_details['bookingpress_package_services'];
                        if(!empty($bookingpress_package_services)){
                            $bookingpress_package_services_arr = json_decode($bookingpress_package_services,true);
                            if(!empty($bookingpress_package_services_arr)){
                                foreach($bookingpress_package_services_arr as $servarr){
                                    $bookingpress_package_services_ids[] = $servarr['bookingpress_service_id'];
                                }
                            }
                        }
                        $bookingpress_default_appointment_booking_page_url = $BookingPress->bookingpress_get_customize_settings('package_appointment_book_redirect', 'package_booking_form');
                        if(empty($bookingpress_default_appointment_booking_page_url)){
                            $bookingpress_default_appointment_booking_page_url = '';
                            $default_appointment_booking_page = $BookingPress->bookingpress_get_customize_settings('default_booking_page', 'booking_form');
                            if(!empty($default_appointment_booking_page)){                
                                $bookingpress_default_appointment_booking_page_url = get_permalink($default_appointment_booking_page);
                            }
                        }  
                        if(!empty($bookingpress_default_appointment_booking_page_url) && !empty($bookingpress_package_services_ids)){
                            $bookingpress_package_services_ids_str = implode(',',$bookingpress_package_services_ids);
                            $bookingpress_default_appointment_booking_page_url = add_query_arg( 'bpservice_ids', $bookingpress_package_services_ids_str, $bookingpress_default_appointment_booking_page_url);
                            $return_data['bookingpress_default_appointment_booking_page_url'] = $bookingpress_default_appointment_booking_page_url;
                        }
                    }                      
        

                    $response['variant'] = 'success';
                    $response['is_success'] = $bookingpress_payment_is_success;
                    $response['title'] = esc_html__('Success', 'bookingpress-package');

                }else{

                    if(($bookingpress_payment_is_success == 1 || $bookingpress_payment_is_success == '1') && !empty($package_order_entry_id)){
                        
                        $return_data['bookingpress_package_no'] = '';
                        $return_data['bookingpress_customer_name'] = '';
                        $return_data['bookingpress_package_name'] = '';
                        $response['variant'] = 'success';
                        $response['is_success'] = $bookingpress_payment_is_success;
                        $response['title'] = esc_html__('Success', 'bookingpress-package');

                    }

                }                
            }else{

                if(($bookingpress_payment_is_success == 2 || $bookingpress_payment_is_success == '2') && !empty($package_order_entry_id)){                        
                    $return_data['bookingpress_package_no'] = '';
                    $return_data['bookingpress_customer_name'] = '';
                    $return_data['bookingpress_package_name'] = '';
                    $response['variant'] = 'success';
                    $response['is_success'] = $bookingpress_payment_is_success;
                    $response['title'] = esc_html__('Success', 'bookingpress-package');
                }

            }

            $response['thankyou_page_data'] = $return_data;
			$response['failed_content'] = (isset($bookingpress_failed_redirect_content))?$bookingpress_failed_redirect_content:'';
			$response['package_order_id'] = $return_data['bookingpress_package_no'];
            
			echo wp_json_encode($response);
			exit;
        }


		/**
		 * Render thank you content when redirection method set to in-built
		 *
		 * @return void
		 */
		function bookingpress_package_render_thankyou_content_func(){

			global $BookingPress, $wpdb, $tbl_bookingpress_appointment_bookings, $tbl_bookingpress_entries,$tbl_bookingpress_package_bookings,$BookingPressPro,$bookingpress_package;
			$response              = array();
			$response['variant'] = 'error';
			$response['title']   = esc_html__( 'Error', 'bookingpress-package' );
			$wpnonce               = isset( $_REQUEST['_wpnonce'] ) ? sanitize_text_field( $_REQUEST['_wpnonce'] ) : '';
			$bpa_verify_nonce_flag = wp_verify_nonce( $wpnonce, 'bpa_wp_nonce' );
			if ( ! $bpa_verify_nonce_flag ) {
				$response['msg']     = esc_html__( 'Sorry, Your request can not be processed due to security reason.', 'bookingpress-package' );
				echo wp_json_encode( $response );
				die();
			}
			$bookingpress_uniq_id = !empty($_POST['bookingpress_uniq_id']) ? sanitize_text_field( $_POST['bookingpress_uniq_id'] ) : '';
			$appointment_id = 0;

            $return_data = array('bookingpress_package_no'=>'','bookingpress_customer_name'=>'','bookingpress_package_name'=>'','bookingpress_default_appointment_booking_page_url'=>'');
			if(!empty($bookingpress_uniq_id)){                

                $bookingpress_cookie_name = $bookingpress_uniq_id."_package_data";
                if(!empty($_COOKIE[$bookingpress_cookie_name])){

                    $bookingpress_cookie_value = $_COOKIE[$bookingpress_cookie_name]; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
                    $bookingpress_entry_id = base64_decode($bookingpress_cookie_value);                    
                    $bookingpress_entry_id = (int)$bookingpress_entry_id;
                    $bookingpress_package_order_details = $wpdb->get_row($wpdb->prepare("SELECT bookingpress_package_services,bookingpress_package_id,bookingpress_package_no,bookingpress_customer_name,bookingpress_customer_firstname,bookingpress_customer_lastname, bookingpress_username,	bookingpress_package_name FROM {$tbl_bookingpress_package_bookings} WHERE bookingpress_entry_id = %d", $bookingpress_entry_id), ARRAY_A); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- Reason: $bookingpress_appointment_details is table name defined globally. False Positive alarm
                    if(!empty($bookingpress_package_order_details)){
                        
                        $bookingpress_customer_name = $bookingpress_package_order_details['bookingpress_customer_name'];
                        if(empty($bookingpress_customer_name)){
                            $bookingpress_customer_name = $bookingpress_package_order_details['bookingpress_customer_firstname'].' '.$bookingpress_package_order_details['bookingpress_customer_lastname'];
                        }
                        if(empty($bookingpress_package_order_details['bookingpress_customer_firstname']) && empty($bookingpress_package_order_details['bookingpress_customer_lastname'])){
                            $bookingpress_customer_name = $bookingpress_package_order_details['bookingpress_username'];
                        }
                        $return_data['bookingpress_package_no'] = $bookingpress_package_order_details['bookingpress_package_no'];
                        $return_data['bookingpress_customer_name'] = $bookingpress_customer_name;
                        $return_data['bookingpress_package_name'] = $bookingpress_package_order_details['bookingpress_package_name'];

                        $package_appointment_btn_services = $BookingPress->bookingpress_get_customize_settings('package_appointment_btn_services', 'package_booking_form');
                        if($package_appointment_btn_services == 'package_services'){
                            $bookingpress_package_services_ids = array();
                            $bookingpress_package_services = $bookingpress_package_order_details['bookingpress_package_services'];
                            if(!empty($bookingpress_package_services)){
                                $bookingpress_package_services_arr = json_decode($bookingpress_package_services,true);
                                if(!empty($bookingpress_package_services_arr)){
                                    foreach($bookingpress_package_services_arr as $servarr){
                                        $bookingpress_package_services_ids[] = $servarr['bookingpress_service_id'];
                                    }
                                }
                            }
                            $bookingpress_default_appointment_booking_page_url = $BookingPress->bookingpress_get_customize_settings('package_appointment_book_redirect', 'package_booking_form');
                            if(empty($bookingpress_default_appointment_booking_page_url)){
                                $bookingpress_default_appointment_booking_page_url = '';
                                $default_appointment_booking_page = $BookingPress->bookingpress_get_customize_settings('default_booking_page', 'booking_form');
                                if(!empty($default_appointment_booking_page)){                
                                    $bookingpress_default_appointment_booking_page_url = get_permalink($default_appointment_booking_page);
                                }
                            }  
                            if(!empty($bookingpress_default_appointment_booking_page_url) && !empty($bookingpress_package_services_ids)){
                                $bookingpress_package_services_ids_str = implode(',',$bookingpress_package_services_ids);
                                $bookingpress_default_appointment_booking_page_url = add_query_arg( 'bpservice_ids', $bookingpress_package_services_ids_str, $bookingpress_default_appointment_booking_page_url);
                                $return_data['bookingpress_default_appointment_booking_page_url'] = $bookingpress_default_appointment_booking_page_url;
                            }
                        }                      

                        if($bookingpress_package->is_multi_language_addon_active()){

                            if(method_exists( $BookingPressPro, 'bookingpress_pro_front_language_translation_func') ) {
                                $bookingpress_package_id = $bookingpress_package_order_details['bookingpress_package_id'];
                                $return_data['bookingpress_package_name'] = $BookingPressPro->bookingpress_pro_front_language_translation_func($return_data['bookingpress_package_name'],'package','bookingpress_package_name',$bookingpress_package_id);
                                
                            }
                        } 

                        
                    }
                }
            }

            $bookingpress_failed_redirect_content = '';

			$response['variant'] = 'success';
			$response['title'] = esc_html__('Success', 'bookingpress-package');			
            $response['thankyou_page_data'] = $return_data;
			$response['failed_content'] = $bookingpress_failed_redirect_content;
			$response['package_order_id'] = $return_data['bookingpress_package_no'];
            
			echo wp_json_encode($response);
			exit;
        }
        
        /**
         * Function for validate submit for front package order
         *
         * @param  mixed $payment_gateway
         * @param  mixed $posted_data
         * @return void
         */
        function bookingpress_validate_submitted_package_order_booking_form_func($payment_gateway, $posted_data, $user_id = ""){
			
            global $BookingPress, $wpdb, $tbl_bookingpress_entries, $bookingpress_debug_payment_log_id, $bookingpress_coupons, $tbl_bookingpress_appointment_meta, $tbl_bookingpress_extra_services, $bookingpress_pro_staff_members, $tbl_bookingpress_staffmembers, $bookingpress_deposit_payment, $tbl_bookingpress_staffmembers_services, $bookingpress_other_debug_log_id,$bookingpress_package,$tbl_bookingpress_customers,$tbl_bookingpress_package_bookings_meta;			
            $return_data = array(
				'service_data'     => array(),
				'payable_amount'   => 0,
				'customer_details' => array(),
				'currency'         => '',
			);
            $bookingpress_package_data = $posted_data;

            if (!empty($bookingpress_package_data) && !empty($payment_gateway )){                

                $bookingpress_selected_package_id = $bookingpress_package_data['bookingpress_selected_package_id'];                
                $package_data = $bookingpress_package->get_package_by_id($bookingpress_selected_package_id);

				$__payable_amount = $bookingpress_package_data['total_payable_amount'];
				$bookingpress_due_amount = 0;
				$customer_email     = !empty($bookingpress_package_data['form_fields']['customer_email']) ? $bookingpress_package_data['form_fields']['customer_email'] : $bookingpress_package_data['customer_email'];
				$customer_full_name  = !empty( $bookingpress_package_data['form_fields']['customer_name'] ) ? sanitize_text_field( $bookingpress_package_data['form_fields']['customer_name'] ) : (!empty( $bookingpress_package_data['customer_name'] ) ? sanitize_text_field($bookingpress_package_data['customer_name'] ) : '');
				$customer_username  = !empty( $bookingpress_package_data['form_fields']['customer_username'] ) ? sanitize_text_field( $bookingpress_package_data['form_fields']['customer_username'] ) : (!empty( $bookingpress_package_data['customer_username'] ) ? sanitize_text_field($bookingpress_package_data['customer_username'] ) : '');
				$customer_firstname = !empty( $bookingpress_package_data['form_fields']['customer_firstname'] ) ? sanitize_text_field( $bookingpress_package_data['form_fields']['customer_firstname'] ) : (!empty($bookingpress_package_data['customer_firstname']) ? sanitize_text_field($bookingpress_package_data['customer_firstname'] ) : '');
				$customer_lastname  = !empty( $bookingpress_package_data['form_fields']['customer_lastname'] ) ? sanitize_text_field( $bookingpress_package_data['form_fields']['customer_lastname'] ) : (!empty($bookingpress_package_data['customer_lastname']) ? sanitize_text_field($bookingpress_package_data['customer_lastname'] ) : '');
				$customer_phone     = !empty( $bookingpress_package_data['form_fields']['customer_phone'] ) ? sanitize_text_field( $bookingpress_package_data['form_fields']['customer_phone'] ) : ( !empty($bookingpress_package_data['customer_phone']) ? sanitize_text_field($bookingpress_package_data['customer_phone'] ) : '' );
				$customer_country   = !empty( $bookingpress_package_data['form_fields']['customer_phone_country'] ) ? sanitize_text_field( $bookingpress_package_data['form_fields']['customer_phone_country'] ) : ( !empty($bookingpress_package_data['customer_phone_country']) ? sanitize_text_field($bookingpress_package_data['customer_phone_country'] ) : '');
				$customer_phone_dial_code = !empty($bookingpress_package_data['customer_phone_dial_code']) ? $bookingpress_package_data['customer_phone_dial_code'] : '';
				$customer_timezone = !empty($bookingpress_package_data['bookingpress_customer_timezone']) ? $bookingpress_package_data['bookingpress_customer_timezone'] : wp_timezone_string();				
                $customer_dst_timezone = !empty( $bookingpress_package_data['client_dst_timezone'] ) ? intval( $bookingpress_package_data['client_dst_timezone'] ) : 0;
				if( !empty($customer_phone) && !empty( $customer_phone_dial_code) ){
                    $customer_phone_pattern = '/(^\+'.$customer_phone_dial_code.')/';
                    if( preg_match($customer_phone_pattern, $customer_phone) ){
                        $customer_phone = preg_replace( $customer_phone_pattern, '', $customer_phone) ;
                    }
                }
                $bookingpress_customer_id = $bookingpress_package_data['bookingpress_customer_id'];
                if(empty($bookingpress_customer_id)){

                    if(empty($user_id)){
                        $user_id = get_current_user_id();
                    }

                    $bookingpress_current_user_obj = new WP_User($user_id); 
                    $customer_details = array(
                        'bookingpress_wpuser_id'   => $user_id,
                        'bookingpress_user_login'  => $customer_email,
                        'bookingpress_user_status' => 1,
                        'bookingpress_user_type'   => 2,
                        'bookingpress_user_email'  => $customer_email,
                        'bookingpress_user_name'   => !empty($bookingpress_current_user_obj->data->user_login)?$bookingpress_current_user_obj->data->user_login : '',
                        'bookingpress_customer_full_name'  => $customer_full_name,
                        'bookingpress_user_firstname' => $customer_firstname,
                        'bookingpress_user_lastname' => $customer_lastname,
                        'bookingpress_user_phone'  => $customer_phone,
                        'bookingpress_user_country_phone' => $customer_country,
                        'bookingpress_user_country_dial_code' => $customer_phone_dial_code,
                        'bookingpress_user_timezone' => $customer_dst_timezone,
                        'bookingpress_user_created' => current_time('mysql'),
                        'bookingpress_created_by'  => $user_id,
                    );                    
                    $wpdb->insert($tbl_bookingpress_customers, $customer_details);
                    $bookingpress_customer_id = $wpdb->insert_id;

                }

                //get_current_user_id();

				$return_data['customer_details'] = array(
					'customer_firstname' => $customer_firstname,
					'customer_lastname'  => $customer_lastname,
					'customer_email'     => $customer_email,
					'customer_username'  => !empty($customer_username) ? $customer_username : $customer_full_name,
					'customer_phone'     => $customer_phone,
				);

				$return_data['card_details'] = array(
					'card_holder_name' => $bookingpress_package_data['card_holder_name'],
					'card_number'      => $bookingpress_package_data['card_number'],
					'expire_month'     => $bookingpress_package_data['expire_month'],
					'expire_year'      => $bookingpress_package_data['expire_year'],
					'cvv'              => $bookingpress_package_data['cvv'],
				);                

                $bookingpress_currency_name = $BookingPress->bookingpress_get_settings('payment_default_currency', 'payment_setting');
                $return_data['currency']    = $bookingpress_currency_name;
                $return_data['currency_code'] = $BookingPress->bookingpress_get_currency_code( $bookingpress_currency_name );
                
                $bookingpress_decimal_points = $BookingPress->bookingpress_get_settings('price_number_of_decimals', 'payment_setting');                
                if ($bookingpress_decimal_points == '0' ) {
                    $__payable_amount = round($__payable_amount);
                }

                $return_data['payable_amount'] = (float) $__payable_amount;

                //$bookingpress_customer_id = get_current_user_id();
                $bookingpress_currency_name = $BookingPress->bookingpress_get_settings('payment_default_currency', 'payment_setting');
				$bookingpress_internal_note = '';

				if(isset($bookingpress_package_data['appointment_note'])){
					$bookingpress_internal_note           = !empty( $bookingpress_package_data['appointment_note'] ) ? sanitize_textarea_field( $bookingpress_package_data['appointment_note'] ) : $bookingpress_package_data['form_fields']['appointment_note'];
				}

                $bookingpress_total_amount = $bookingpress_package_data['total_payable_amount'];

                $bookingpress_tax_percentage = !empty($bookingpress_package_data['tax_percentage']) ? floatval($bookingpress_package_data['tax_percentage']) : 0;
                $bookingpress_tax_amount = !empty($bookingpress_package_data['tax']) ? $bookingpress_package_data['tax'] : 0;
                $bookingpress_tax_price_display_options = !empty($bookingpress_package_data['tax_price_display_options']) ? $bookingpress_package_data['tax_price_display_options'] : 'exclude_taxes';
                $bookingpress_tax_order_summary = (!empty($bookingpress_package_data['display_tax_order_summary']) && $bookingpress_package_data['display_tax_order_summary'] == 'true') ? 1 : 0;
                $bookingpress_included_tax_label = !empty($bookingpress_package_data['included_tax_label']) ? $bookingpress_package_data['included_tax_label'] : '';
                $customer_dst_timezone = !empty( $bookingpress_package_data['client_dst_timezone'] ) ? intval( $bookingpress_package_data['client_dst_timezone'] ) : 0;

                $customer_timezone = isset($bookingpress_package_data['bookingpress_customer_timezone']) ? $bookingpress_package_data['bookingpress_customer_timezone'] : wp_timezone_string();

                $customer_dst_timezone = isset( $bookingpress_appointment_data['client_dst_timezone'] ) ? intval( $bookingpress_appointment_data['client_dst_timezone'] ) : 0;   


				$bookingpress_entry_details = array(
					'bookingpress_customer_id'                    => $bookingpress_customer_id,
					'bookingpress_order_id'                       => 0,
					'bookingpress_customer_name'                  => $customer_full_name,
					'bookingpress_username'                       => $customer_username,
					'bookingpress_customer_phone'                 => $customer_phone,
					'bookingpress_customer_firstname'             => $customer_firstname,
					'bookingpress_customer_lastname'              => $customer_lastname,
					'bookingpress_customer_country'               => $customer_country,
					'bookingpress_customer_phone_dial_code'       => $customer_phone_dial_code,
					'bookingpress_customer_email'                 => $customer_email,
					'bookingpress_customer_timezone'              => $customer_timezone,
					'bookingpress_dst_timezone'					  => $customer_dst_timezone,
					'bookingpress_service_id'                     => 0,
					'bookingpress_service_name'                   => '',
					'bookingpress_service_price'                  => 0,
					'bookingpress_service_currency'               => $bookingpress_currency_name,
					'bookingpress_service_duration_val'           => 0,
					'bookingpress_service_duration_unit'          => '',
					'bookingpress_payment_gateway'                => $payment_gateway,
					'bookingpress_appointment_date'               => date('Y-m-d'),
					'bookingpress_appointment_time'               => date('H:i:s'),
					'bookingpress_appointment_end_time'  		  => '00:00:00',
					'bookingpress_appointment_internal_note'      => $bookingpress_internal_note,
					'bookingpress_appointment_send_notifications' => 1,
					'bookingpress_appointment_status'             => 1,
                    'bookingpress_package_details'                => wp_json_encode( $package_data ),
					'bookingpress_purchase_type'                  => 2,
                    'bookingpress_paid_amount'                    => $__payable_amount,
					'bookingpress_due_amount'                     => 0,
					'bookingpress_total_amount'                   => $bookingpress_total_amount,
					'bookingpress_created_at'                     => current_time( 'mysql' ),
				);
                
                $package_services_data = $bookingpress_package->get_package_services_by_package_id($bookingpress_selected_package_id);

                $bookingpress_entry_details['bookingpress_tax_percentage'] = $bookingpress_tax_percentage;
                $bookingpress_entry_details['bookingpress_tax_amount'] = $bookingpress_tax_amount;
                $bookingpress_entry_details['bookingpress_price_display_setting'] = $bookingpress_tax_price_display_options;
                $bookingpress_entry_details['bookingpress_display_tax_order_summary'] = $bookingpress_tax_order_summary;
                $bookingpress_entry_details['bookingpress_included_tax_label'] = $bookingpress_included_tax_label;                

                $bookingpress_entry_details = apply_filters('bookingpress_modify_frontend_add_package_order_entry_data', $bookingpress_entry_details, $bookingpress_package_data);
                $wpdb->insert($tbl_bookingpress_entries, $bookingpress_entry_details);
                $entry_id       = $wpdb->insert_id;

                if(is_array($package_services_data)){
                    $package_services_data = json_encode($package_services_data,true);
                }

                /* Add Meta Custom Fields value when package order added */                       
                $bookingpress_package_form_fields_data = array(
                    'form_fields' => !empty($bookingpress_package_data['form_fields']) ? $bookingpress_package_data['form_fields'] : array(),
                    'bookingpress_front_field_data' => !empty($bookingpress_package_data['bookingpress_front_field_data']) ? $bookingpress_package_data['bookingpress_front_field_data'] : array(),
                );
                $bookingpress_db_fields = array(
                    'bookingpress_entry_id' => $entry_id,
                    'bookingpress_package_booking_id' => 0,
                    'bookingpress_package_meta_value' => wp_json_encode($bookingpress_package_form_fields_data),
                    'bookingpress_package_meta_key' => 'package_form_fields_data',
                );            
                $wpdb->insert($tbl_bookingpress_package_bookings_meta, $bookingpress_db_fields);
                /* Add meta custom fields value when package order added */                

                if(!empty($entry_id)) {

                    $tbl_bookingpress_entries_meta = $wpdb->prefix . 'bookingpress_entries_meta';
                    $bookingpress_db_fields = array(
                        'bookingpress_entry_id' => $entry_id,                        
                        'bookingpress_entry_meta_key' => 'bookingpress_package_data',
                        'bookingpress_entry_meta_value' => wp_json_encode( $package_data ),
                    );
                    $wpdb->insert($tbl_bookingpress_entries_meta, $bookingpress_db_fields);                    
                    if(is_array($package_services_data)){
                        $package_services_data = json_encode($package_services_data,true);
                    }
                    $bookingpress_db_fields = array(
                        'bookingpress_entry_id' => $entry_id,                        
                        'bookingpress_entry_meta_key' => 'bookingpress_package_service_data',
                        'bookingpress_entry_meta_value' => $package_services_data,
                    );
                    $wpdb->insert($tbl_bookingpress_entries_meta, $bookingpress_db_fields);

                }

                /*
                $package_redirection_mode = $BookingPress->bookingpress_get_customize_settings('package_redirection_mode', 'package_booking_form');
                if(empty($package_redirection_mode)){
                    $package_redirection_mode = 'in-built';
                }
                */

                $return_data['entry_id'] = $entry_id;
                $return_data['booking_form_redirection_mode'] = 'in-built';

				$bookingpress_uniq_id = $posted_data['bookingpress_uniq_id'];
				$bookingpress_cookie_name = $bookingpress_uniq_id."_package_data";
				$bookingpress_cookie_value = $entry_id;
                
				$bookingpress_cookie_exists = !empty($_COOKIE[$bookingpress_cookie_name]) ? 1 : 0;
				if($bookingpress_cookie_exists){
					setcookie($bookingpress_cookie_name, "", time()-3600, "/");
					setcookie("bookingpress_last_request_id", "", time()-3600, "/");
					setcookie("bookingpress_referer_url", "", time() - 3600, "/");
				}
                
				$bookingpress_referer_url = (wp_get_referer()) ? wp_get_referer() : BOOKINGPRESS_HOME_URL;
				$bookingpress_encoded_value = base64_encode($entry_id);
				setcookie($bookingpress_cookie_name, $bookingpress_encoded_value, time()+(86400), "/");
				setcookie("bookingpress_last_request_id", $bookingpress_uniq_id, time()+(86400), "/");
				setcookie("bookingpress_referer_url", $bookingpress_referer_url, time()+(86400), "/");
                
                $bookingpress_entry_hash = md5($entry_id);
                //$bookingpress_after_approved_payment_url     = $BookingPress->bookingpress_get_customize_settings('package_after_booking_redirection', 'package_booking_form');
                //$bookingpress_canceled_appointment_url       = $BookingPress->bookingpress_get_customize_settings('package_after_failed_payment_redirection', 'package_booking_form');

                $default_package_booking_page = $BookingPress->bookingpress_get_customize_settings('default_package_booking_page', 'package_booking_form');                
                $bookingpress_after_approved_payment_url     = $default_package_booking_page;
                $bookingpress_canceled_appointment_url       = $default_package_booking_page;

                $bookingpress_approved_appointment_url = $bookingpress_canceled_appointment_url = !empty( $_POST['pageURL'] ) ? esc_url_raw( $_POST['pageURL'] ) : $bookingpress_referer_url; // phpcs:ignore WordPress.Security.NonceVerification
                $bookingpress_approved_appointment_url = add_query_arg( 'is_success', 1, $bookingpress_approved_appointment_url);
                $bookingpress_approved_appointment_url = add_query_arg( 'package_order_entry_id', base64_encode($entry_id), $bookingpress_approved_appointment_url);
                $bookingpress_approved_appointment_url = add_query_arg( 'bp_tp_nonce', wp_create_nonce( 'bpa_nonce_url-'.$bookingpress_entry_hash ), $bookingpress_approved_appointment_url );

                $bookingpress_canceled_appointment_url = add_query_arg('is_success', 2, $bookingpress_canceled_appointment_url);
                $bookingpress_canceled_appointment_url = add_query_arg('package_order_entry_id', base64_encode($entry_id), $bookingpress_canceled_appointment_url);
                $bookingpress_canceled_appointment_url = add_query_arg( 'bp_tp_nonce', wp_create_nonce( 'bpa_nonce_url-'.$bookingpress_entry_hash ), $bookingpress_canceled_appointment_url );

                /*

                $bookingpress_after_approved_payment_page_id = $BookingPress->bookingpress_get_customize_settings('package_after_booking_redirection', 'package_booking_form');
                $bookingpress_after_approved_payment_url     = get_permalink($bookingpress_after_approved_payment_page_id);
                $bookingpress_after_approved_payment_url     = ! empty($bookingpress_after_approved_payment_url) ? $bookingpress_after_approved_payment_url : BOOKINGPRESS_HOME_URL;
                $bookingpress_after_approved_payment_url    = add_query_arg('appointment_id', base64_encode($entry_id), $bookingpress_after_approved_payment_url);
                $bookingpress_after_approved_payment_url = add_query_arg( 'bp_tp_nonce', wp_create_nonce( 'bpa_nonce_url-'.$bookingpress_entry_hash ), $bookingpress_after_approved_payment_url );

                $bookingpress_after_canceled_payment_page_id = $BookingPress->bookingpress_get_customize_settings('after_cancelled_appointment_redirection', 'booking_my_booking');
                $bookingpress_after_canceled_payment_url     = get_permalink($bookingpress_after_canceled_payment_page_id);
                $bookingpress_after_canceled_payment_url     = ! empty($bookingpress_after_canceled_payment_url) ? $bookingpress_after_canceled_payment_url : BOOKINGPRESS_HOME_URL;
                $bookingpress_after_canceled_payment_url     = add_query_arg('appointment_id', base64_encode($entry_id), $bookingpress_after_canceled_payment_url);
                $bookingpress_after_canceled_payment_url = add_query_arg( 'bp_tp_nonce', wp_create_nonce( 'bpa_nonce_url-'.$bookingpress_entry_hash ), $bookingpress_after_canceled_payment_url );
                
                */

                $return_data['approved_appointment_url'] = $bookingpress_approved_appointment_url;
                $return_data['pending_appointment_url'] = $return_data['approved_appointment_url'];
                $return_data['canceled_appointment_url'] = $bookingpress_canceled_appointment_url;                
                
				$bookingpress_notify_url   = BOOKINGPRESS_HOME_URL . '/?bookingpress-listener=bpa_pro_' . $payment_gateway . '_url';
				$return_data['notify_url'] = $bookingpress_notify_url;
                
                $return_data = apply_filters('bookingpress_after_modify_validate_submit_form_data', $return_data);

            }

            return $return_data;

        }


        /**
         * Server Side Validaton - Backend Side Validation
         *
         * @return void
         */
        function bookingpress_before_book_package_validate_func($return_data = false, $user_id = ""){

            global $wpdb, $BookingPress, $tbl_bookingpress_appointment_bookings, $tbl_bookingpress_payment_logs,$tbl_bookingpress_customers,$bookingpress_payment_gateways,$tbl_bookingpress_form_fields,$tbl_bookingpress_package_bookings,$bookingpress_package;
            $response              = array();
            $wpnonce               = isset($_REQUEST['_wpnonce']) ? sanitize_text_field($_REQUEST['_wpnonce']) : '';
            $bpa_verify_nonce_flag = wp_verify_nonce($wpnonce, 'bpa_wp_nonce');
            if (! $bpa_verify_nonce_flag ) {
                
                $response['variant']      = 'error';
                $response['title']        = esc_html__('Error', 'bookingpress-package');
                $response['msg']          = esc_html__('Sorry, Your request can not be processed due to security reason.', 'bookingpress-package');
                $response['redirect_url'] = '';
                if($return_data){
                    return $response;
                }
                echo wp_json_encode($response);
                exit;

            }
            $response['variant']    = 'success';
            $response['title']      = '';
            $response['msg']        = '';
            $response['error_type'] = '';  

            
            if( !empty( $_REQUEST['package_data'] ) && !is_array( $_REQUEST['package_data'] ) ){
                $_REQUEST['package_data'] = json_decode( stripslashes_deep( $_REQUEST['package_data'] ), true ); //phpcs:ignore                
                $_POST['package_data'] = $_REQUEST['package_data'] =  !empty($_REQUEST['package_data']) ? array_map(array($this,'bookingpress_boolean_type_cast'), $_REQUEST['package_data'] ) : array(); // phpcs:ignore
            }
            $posted_data = !empty($_POST) ? array_map(array( $BookingPress, 'appointment_sanatize_field' ), $_POST) : array();   
	    
	    $bookingpress_unique_id = isset($_REQUEST['package_data']['bookingpress_uniq_id']) ? sanitize_text_field($_REQUEST['package_data']['bookingpress_uniq_id']) : '';
            
            $bookingpress_package_token = !empty( $posted_data['package_data']['package_token'] ) ? $posted_data['package_data']['package_token'] : ''; //phpcs:ignore
            
            if(session_id() == '' OR session_status() === PHP_SESSION_NONE) {
                session_start();
            }

            if(( empty( $bookingpress_package_token ) || empty( $_SESSION['bpa_package_filter_input'] )) && $return_data == false ){
                $response['package_token'] = $bookingpress_package_token;
                $response['session_package_token'] = $_SESSION['bpa_package_filter_input'];
                $response['variant']      = 'error';
                $response['title']        = esc_html__('Error', 'bookingpress-package');
                $response['msg']          = esc_html__('Sorry, Your request can not be processed due to security reason.', 'bookingpress-package');
                $response['redirect_url'] = '';
                if($return_data){
                    return $response;
                }                
                echo wp_json_encode($response);
                die;
            }

            /* 
            if( !empty( $_SESSION['bpa_package_filter_input'] ) ){
                $package_active_tokens = !empty( $_SESSION['bpa_package_filter_input']['active'] ) ? $_SESSION['bpa_package_filter_input']['active'] : array() ; //phpcs:ignore

            } 
            */
            
            if( !empty( $_SESSION['bpa_package_filter_input'] ) && $_SESSION['bpa_package_filter_input'] != md5( $bookingpress_package_token ) && $return_data == false ){
                if( !empty( $package_active_tokens[ md5( $bookingpress_package_toke ) ]) ){
                    $response['variant']      = 'error';
                    $response['title']        = esc_html__('Error', 'bookingpress-package');
                    $response['msg']          = esc_html__('Sorry, Your request can not be processed due to security reason.', 'bookingpress-package');
                    $response['redirect_url'] = '';
                    if($return_data){
                        return $response;
                    }                    
                    echo wp_json_encode($response);
                    die;
                }
            }

            $bookingpress_form_token = !empty( $_REQUEST['package_data']['bookingpress_form_token'] ) ? sanitize_text_field( $_REQUEST['package_data']['bookingpress_form_token'] ) : $bookingpress_unique_id;


            $unsupported_currecy_selected_for_the_payment = $BookingPress->bookingpress_get_settings('unsupported_currecy_selected_for_the_payment', 'message_setting');
            $no_payment_method_is_selected_for_the_booking = $BookingPress->bookingpress_get_settings('no_payment_method_is_selected_for_the_booking', 'message_setting');


            /* server side validation */
            $all_fields = $wpdb->get_results( "SELECT bookingpress_field_error_message,bookingpress_form_field_name,bookingpress_field_is_default FROM {$tbl_bookingpress_form_fields} WHERE bookingpress_field_required = 1 AND bookingpress_field_is_hide = 0 AND bookingpress_field_is_package_hide = 0" ); //phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared --Reason: $tbl_bookingpress_form_fields is a table name. false alarm            

			/* $all_required_fields = array(); */
			$service_visibility_field_arr = array();
			$field_validation_message = array();
			if ( ! empty( $all_fields ) ) {
				$is_required_validation = false;
				foreach ( $all_fields as $field_data ) {					
					if( isset($field_data->bookingpress_field_type) && 'password' == $field_data->bookingpress_field_type && (is_user_logged_in() || !empty($user_id)) ){
						continue;
					}
					$field_error_msg = $field_data->bookingpress_field_error_message;
                    $field_options = array();
                    if(isset($field_data->bookingpress_field_options)){
                        $field_options = json_decode($field_data->bookingpress_field_options, true);
                    }					
					$bookingpress_selected_service = isset($field_options['selected_services']) ? $field_options['selected_services']: '';   
                    if(!empty($field_options) && $field_options['visibility'] == 'always' &&  $field_data->bookingpress_form_field_name != '2 Col' && $field_data->bookingpress_form_field_name != '3 Col' && $field_data->bookingpress_form_field_name != '4 Col' ){
						$bpa_visible_field_key = '';
						if( $field_data->bookingpress_field_is_default == 1 ){
							if( $field_data->bookingpress_form_field_name == 'firstname'){
								$bpa_visible_field_key = 'customer_firstname';		
							}
							if( $field_data->bookingpress_form_field_name == 'lastname'){
								$bpa_visible_field_key = 'customer_lastname';		
							}
							if( $field_data->bookingpress_form_field_name == 'email_address'){
								$bpa_visible_field_key = 'customer_email';		
							}
							if( $field_data->bookingpress_form_field_name == 'note'){
								$bpa_visible_field_key = 'appointment_note';		
							}
							if( $field_data->bookingpress_form_field_name == 'phone_number'){
								$bpa_visible_field_key = 'customer_phone';		
							}
							if( $field_data->bookingpress_form_field_name == 'fullname'){
								$bpa_visible_field_key = 'customer_name';		
							}
							if( $field_data->bookingpress_form_field_name == 'username'){
								$bpa_visible_field_key = 'customer_username';		
							}
							if( $field_data->bookingpress_form_field_name == 'terms_and_conditions'){
								$bpa_visible_field_key = 'appointment_terms_conditions';		
							}
						} else {
							$bpa_visible_field_key = $field_data->bookingpress_field_meta_key;
						}

						if( 'password' == $field_data->bookingpress_field_type && empty( $posted_data['package_data']['form_fields'][$bpa_visible_field_key] ) ){
							continue;
						}

						$val = $posted_data['package_data']['form_fields'][ $bpa_visible_field_key ];

						if( $bpa_visible_field_key == 'appointment_terms_conditions'){

							if( empty($val[0])){
								$is_required_validation = true;
								$field_validation_message[] = $field_error_msg;
							}
						} else {
							if( '' === $val ){
								$is_required_validation = true;
								$field_validation_message[] = $field_error_msg;
							}
						}
					}

					if( !empty($field_options) && $field_options['visibility'] == 'services' &&  $field_data->bookingpress_form_field_name != '2 Col' && $field_data->bookingpress_form_field_name != '3 Col' && $field_data->bookingpress_form_field_name != '4 Col' ){
						$bookingpress_field_meta_key_val = $field_data->bookingpress_field_meta_key;

						if( $field_data->bookingpress_field_is_default == 1 ){
							if( $field_data->bookingpress_form_field_name == 'firstname'){
								$bookingpress_field_meta_key_val = 'customer_firstname';		
							}
							if( $field_data->bookingpress_form_field_name == 'lastname'){
								$bookingpress_field_meta_key_val = 'customer_lastname';		
							}
							if( $field_data->bookingpress_form_field_name == 'email_address'){
								$bookingpress_field_meta_key_val = 'customer_email';		
							}
							if( $field_data->bookingpress_form_field_name == 'note'){
								$bookingpress_field_meta_key_val = 'appointment_note';		
							}
							if( $field_data->bookingpress_form_field_name == 'phone_number'){
								$bookingpress_field_meta_key_val = 'customer_phone';		
							}
							if( $field_data->bookingpress_form_field_name == 'fullname'){
								$bookingpress_field_meta_key_val = 'customer_name';		
							}
							if( $field_data->bookingpress_form_field_name == 'username'){
								$bookingpress_field_meta_key_val = 'customer_username';		
							}
							if( $field_data->bookingpress_form_field_name == 'terms_and_conditions'){
								$bookingpress_field_meta_key_val = 'appointment_terms_conditions';		
							}
						} else {
							$bookingpress_field_meta_key_val = $field_data->bookingpress_field_meta_key;
						}
						$service_visibility_field_arr[$bookingpress_field_meta_key_val] = $bookingpress_selected_service;
					}
				}

			}
            
			if( true == $is_required_validation ){
				$response['variant'] = 'error';
				$response['title']   = esc_html__('Error', 'bookingpress-package');
				$response['msg']     = (!empty($field_validation_message)) ? implode(',', $field_validation_message) : array();
                if($return_data){
                    return $response;
                }                 
				echo wp_json_encode($response);	
                exit;
			}
            if( !empty( $posted_data ) ){
                

                $bookingpress_selected_package_id = (isset($posted_data['package_data']['bookingpress_selected_package_id']))?$posted_data['package_data']['bookingpress_selected_package_id']:'';
                $bookingpress_selected_package_detail = (isset($posted_data['package_data']['bookingpress_selected_package_detail']))?$posted_data['package_data']['bookingpress_selected_package_detail']:'';
				if (empty($bookingpress_selected_package_detail) || empty($bookingpress_selected_package_id)) {
					$response['variant'] = 'error';
					$response['title']   = esc_html__('Error', 'bookingpress-package');
					$response['msg']     = esc_html__('Package not selected.', 'bookingpress-package');
                    if($return_data){
                        return $response;
                    }                    
					echo wp_json_encode($response);
                    exit;
				}                                
				if (empty($posted_data['package_data']['selected_payment_method']) ) {
					$response['variant'] = 'error';
					$response['title']   = esc_html__('Error', 'bookingpress-package');
					$response['msg']     = $no_payment_method_is_selected_for_the_booking;
                    if($return_data){
                        return $response;
                    }                    
					echo  wp_json_encode($response);
                    exit;
				}
                $total_payable_amount = (isset($posted_data['package_data']['total_payable_amount']))?(float)$posted_data['package_data']['total_payable_amount']:'';
                if(empty($posted_data['package_data']['total_payable_amount']) || $total_payable_amount <= 0){
					$response['variant'] = 'error';
					$response['title']   = esc_html__('Error', 'bookingpress-package');
					$response['msg']     = esc_html__('Please enter valid package price.', 'bookingpress-package');
                    if($return_data){
                        return $response;
                    }                    
					echo wp_json_encode($response);  
                    exit;                  
                }

                $current_user_id = get_current_user_id();
                if(!empty($user_id)){
                    $current_user_id = $user_id;
                }
                
                $bookingpress_customer_id = $posted_data['package_data']['bookingpress_selected_package_id'];

                $bookingpress_selected_package_id = $posted_data['package_data']['bookingpress_selected_package_id'];
                $package_data = $bookingpress_package->get_package_by_id($bookingpress_selected_package_id);
                
                $bookingpress_package_customer_purchase_limit = (isset($package_data['bookingpress_package_customer_purchase_limit']))?$package_data['bookingpress_package_customer_purchase_limit']:0;
                if($bookingpress_package_customer_purchase_limit > 0){

                    $get_customer_total_purchase_package = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) as total FROM {$tbl_bookingpress_package_bookings} WHERE bookingpress_package_id = %d AND bookingpress_login_user_id = %d", $bookingpress_selected_package_id, $current_user_id ) ); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared --Reason: $tbl_bookingpress_package_bookings is a table name. false alarm
                    if($get_customer_total_purchase_package >= $bookingpress_package_customer_purchase_limit){   
                        
                        $package_purchase_limit_message = $BookingPress->bookingpress_get_customize_settings('package_purchase_limit_message', 'package_booking_form');
                        
                        $response['variant'] = 'error';
                        $response['title']   = esc_html__('Error', 'bookingpress-package');
                        $response['msg']     = $package_purchase_limit_message;
                        if($return_data){
                            return $response;
                        }                       
                        echo wp_json_encode($response);
                        exit();

                    }

                }

                /* New Validation added start */
                $authorization_token = !empty( $posted_data['package_data']['authorized_token'] ) ? $posted_data['package_data']['authorized_token'] : '';				
                $bookingpress_uniq_id = $posted_data['package_data']['bookingpress_uniq_id'];
                $authorization_time = $posted_data['package_data']['authorized_time'];                
                $verification_token_key = 'bookingpress_verify_payment_token_' .  $bookingpress_uniq_id . '_' . $authorization_time;

				if( wp_hash( $verification_token_key ) != $authorization_token ){
					$bookingpress_invalid_token = esc_html__('Sorry! package could not be processed', 'bookingpress-package');

                    $response['variant']       = 'error';
                    $response['title']         = esc_html__('Error', 'bookingpress-package');
                    $response['msg']           = $bookingpress_invalid_token;
                    $response['is_redirect']   = 0;
                    $response['reason']        = 'token mismatched ' . $authorization_token . ' --- ' . wp_hash( $verification_token ) . ' --- ' . $verification_token_key;
                    $response['redirect_data'] = '';
                    $response['is_spam']       = 0;
                    if($return_data){
                        return $response;
                    }
                    echo json_encode($response);
                    exit;
				}

                $bookingpress_total_price = !empty($posted_data['package_data']['total_payable_amount']) ? $posted_data['package_data']['total_payable_amount'] : 0;

				$bookingpress_total_payment_price = get_transient( $authorization_token );
				if( false !== $bookingpress_total_payment_price && $bookingpress_total_payment_price != $bookingpress_total_price ){
					$bookingpress_invalid_amount = esc_html__('Sorry! package could not be processed', 'bookingpress-package');

                    $response['variant']       = 'error';
                    $response['title']         = esc_html__('Error', 'bookingpress-package');
                    $response['msg']           = $bookingpress_invalid_amount;
                    $response['is_redirect']   = 0;
                    $response['reason']        = 'price mismatched ' . $bpa_service_amount . ' --- ' . $bookingpress_service_price;
                    $response['redirect_data'] = '';
                    $response['is_spam']       = 0;
                    if($return_data){
                        return $response;
                    }
                    echo json_encode($response);
                    exit;
				}                

                /* New Validation added over */


                if(empty($bookingpress_customer_id)){

                    $bookingpress_current_user_obj = new WP_User($current_user_id); 
                    $bookingpress_customer_name  = ! empty($bookingpress_current_user_obj->data->user_login) ? $bookingpress_current_user_obj->data->user_login : '';
                    $bookingpress_customer_email = ! empty($bookingpress_current_user_obj->data->user_email) ? $bookingpress_current_user_obj->data->user_email : '';
                    $bookingpress_firstname      = get_user_meta($current_user_id, 'first_name', true);
                    $bookingpress_lastname       = get_user_meta($current_user_id, 'last_name', true);


                    /*
                    
                    $full_name = $bookingpress_firstname.' '.$bookingpress_lastname;
                    $customer_details = array(
                        'bookingpress_wpuser_id'   => $current_user_id,
                        'bookingpress_user_login'  => $bookingpress_customer_email,
                        'bookingpress_user_status' => 1,
                        'bookingpress_user_type'   => 2,
                        'bookingpress_user_email'  => $bookingpress_customer_email,
                        'bookingpress_user_name'   => $full_name,
                        'bookingpress_customer_full_name'  => $full_name,
                        'bookingpress_user_firstname' => $bookingpress_firstname,
                        'bookingpress_user_lastname' => $bookingpress_lastname,
                        'bookingpress_user_phone'  => $customer_phone,
                        'bookingpress_user_country_phone' => $bookingpress_customer_country,
                        'bookingpress_user_country_dial_code' => $bookingpress_customer_dial_code,
                        'bookingpress_user_timezone' => $bookingpress_customer_timezone,
                        'bookingpress_user_created' => current_time('mysql'),
                        'bookingpress_created_by'  => $bookingpress_wpuser_id,
                    );                    
                    */
                }else{


                }
                
                

				$bookingpress_selected_payment_method = sanitize_text_field($posted_data['package_data']['selected_payment_method']);
				$bookingpress_currency_name           = $BookingPress->bookingpress_get_settings('payment_default_currency', 'payment_setting');
				$bookingpress_paypal_currency = $bookingpress_payment_gateways->bookingpress_paypal_supported_currency_list();            
				$bookingpress_is_support = 1;
				if ($bookingpress_selected_payment_method == 'paypal' && !in_array($bookingpress_currency_name,$bookingpress_paypal_currency ) ) {
					$bookingpress_is_support = 0;
				} else {					
					$bookingpress_is_support = apply_filters('bookingpress_pro_validate_currency_before_book_appointment',$bookingpress_is_support,$bookingpress_selected_payment_method,$bookingpress_currency_name);
				}
				if($bookingpress_is_support == 0){
					$response['variant'] = 'error';
					$response['title']   = esc_html__('Error', 'bookingpress-package');
					$response['msg']     = esc_html($unsupported_currecy_selected_for_the_payment);
                    if($return_data){
                        return $response;
                    }                    
					echo wp_json_encode($response);
                    exit;  
				}

                /* New Validation Added Start */


                /* New Validation Added Over */
                do_action('bookingpress_validate_package_form', $posted_data);

                if (!empty( $posted_data )) {

                    $bookingpress_package_data     = array_map( array( $BookingPress, 'appointment_sanatize_field' ), $posted_data['package_data'] );// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized --Reason $_REQUEST contains mixed array and will be sanitized using 'appointment_sanatize_field' function
                    $bookingpress_payment_gateway  = ! empty( $bookingpress_package_data['selected_payment_method'] ) ? sanitize_text_field( $bookingpress_package_data['selected_payment_method'] ) : '';
                    $bookingpress_total_price = !empty($bookingpress_package_data['total_payable_amount']) ? $bookingpress_package_data['total_payable_amount'] : 0;
                    $payment_gateway  = $bookingpress_payment_gateway;

                    $bookingpress_return_data = apply_filters( 'bookingpress_validate_submitted_package_order_booking_form', $payment_gateway, $bookingpress_package_data, $user_id );

                    $bookingpress_return_data['selected_package_details'] = $bookingpress_package_data['bookingpress_selected_package_detail'];
                    $bookingpress_return_data['page_url'] = !empty( $_POST['pageURL'] ) ? esc_url_raw( $_POST['pageURL'] ) : home_url();

                    $response = apply_filters( 'bookingpress_package_order_' . $payment_gateway . '_submit_form_data', $response, $bookingpress_return_data );

                }
            }
            if($return_data){
                
                $entry_id = (isset($bookingpress_return_data['entry_id']))?$bookingpress_return_data['entry_id']:'';
                $is_card_payment =  apply_filters( 'bookingpress_check_payment_gateway_support_card_payment', 0, $payment_gateway );
                $response['package_book'] = ($is_card_payment)?1:0;
                $response['entry_id'] = $entry_id;
                $response['thankyou_page_data'] = array();
                $is_transaction_completed = (isset($response['is_transaction_completed']))?$response['is_transaction_completed']:'';
                if($is_transaction_completed == 1 && $is_card_payment && !empty($entry_id)){
                    $response['thankyou_page_data'] = $this->bookingpress_bpa_get_package_thankyou_page_using_entry_id_func($entry_id);
                }

                return $response;
            }
			echo wp_json_encode( $response );
			exit;

        }

        function set_package_front_css(){
            global $BookingPress;
            wp_register_style( 'bookingpress_package_appointment_front_css', BOOKINGPRESS_PACKAGE_URL . '/css/bookingpress_package_appointment_front.css', array(), BOOKINGPRESS_PACKAGE_VERSION );
            if ( $BookingPress->bookingpress_is_front_page() ) {
                wp_enqueue_style( 'bookingpress_package_appointment_front_css' );
                if (is_rtl() ) {
                    
                }
                //$this->bookingpress_load_package_front_custom_css();
            }
        }

        function bookingpress_load_package_front_custom_css(){
            global $BookingPress;
            $bookingpress_customize_css_key = get_option('bookingpress_custom_css_key', true);

            if (file_exists(BOOKINGPRESS_UPLOAD_DIR . '/bookingpress_front_package_' . $bookingpress_customize_css_key . '.css') ) {
                wp_register_style('bookingpress_front_package_custom', BOOKINGPRESS_UPLOAD_URL . '/bookingpress_front_package_' . $bookingpress_customize_css_key . '.css', 'bookingpress_package_appointment_front_css', BOOKINGPRESS_VERSION);
                wp_enqueue_style('bookingpress_front_package_custom');

                global $bookingpress_global_options;
                $bookingpress_google_fonts_list  = $bookingpress_global_options->bookingpress_get_google_fonts();
                
                $bookingform_title_font_family = $BookingPress->bookingpress_get_customize_settings('title_font_family', 'booking_form');
                
                
                if (! empty($bookingform_title_font_family) && ($bookingform_title_font_family != 'Poppins') && in_array( $bookingform_title_font_family, $bookingpress_google_fonts_list ) ) {
                    $bookingpress_google_font_url = 'https://fonts.googleapis.com/css2?family=' . $bookingform_title_font_family . '&display=swap';
                    $bookingpress_google_font_url = apply_filters('bookingpress_modify_google_font_url', $bookingpress_google_font_url, $bookingform_title_font_family);
                    wp_register_style('bookingpress_front_font_css_' . $bookingform_title_font_family, $bookingpress_google_font_url, array(), BOOKINGPRESS_VERSION);
                    wp_enqueue_style('bookingpress_front_font_css_' . $bookingform_title_font_family);                    
                }

                $bookingpress_get_white_label_svg = $BookingPress->bookingpress_get_settings('bpa_white_label_icon', 'general_setting');
			    $bookingpress_primary_color = $BookingPress->bookingpress_get_customize_settings('primary_color', 'booking_form');

                if( $bookingpress_get_white_label_svg == 'bpa_square_icon'){

                    $bpa_square_icon_svg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 200 200">
                                        <rect fill="{bpa_square_icon_svg_color}" stroke="{bpa_square_icon_svg_color}" stroke-width="15" width="30" height="30" x="25" y="50">
                                            <animate attributeName="opacity" calcMode="spline" dur="1.4" values="1;0;1;"
                                                keySplines=".5 0 .5 1;.5 0 .5 1" repeatCount="indefinite" begin="-.4"></animate>
                                        </rect>
                                        <rect fill="{bpa_square_icon_svg_color}" stroke="{bpa_square_icon_svg_color}" stroke-width="15" width="30" height="30" x="85" y="50">
                                            <animate attributeName="opacity" calcMode="spline" dur="1.4" values="1;0;1;"
                                                keySplines=".5 0 .5 1;.5 0 .5 1" repeatCount="indefinite" begin="-.2"></animate>
                                        </rect>
                                        <rect fill="{bpa_square_icon_svg_color}" stroke="{bpa_square_icon_svg_color}" stroke-width="15" width="30" height="30" x="145" y="50">
                                            <animate attributeName="opacity" calcMode="spline" dur="1.4" values="1;0;1;"
                                                keySplines=".5 0 .5 1;.5 0 .5 1" repeatCount="indefinite" begin="0"></animate>
                                        </rect></svg>';
    
                    $bpa_square_icon_svg = base64_encode(str_replace('{bpa_square_icon_svg_color}', $bookingpress_primary_color, $bpa_square_icon_svg ));

                    $bookingpress_customize_css_content ='.bpp-frontend-main-container-package .bpp-frontend-main-inner_container .bpp-front-loader-container .bpp-front-loader svg { display: none; } .bpp-frontend-main-container-package .bpp-frontend-main-inner_container .bpp-front-loader-container .bpp-front-loader{ background: url(data:image/svg+xml;base64,'.$bpa_square_icon_svg.') no-repeat left top; background-size: 100%; }';
    
    
                }else if( $bookingpress_get_white_label_svg == 'bpa_ripple_icon'){
    
                    $bpa_ripple_icon_svg = '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" style="margin: auto; display: block; shape-rendering: auto;" width="350px" height="350px" viewBox="0 0 100 100" preserveAspectRatio="xMidYMid">
                    <circle cx="50" cy="50" r="0" fill="none" stroke="{bpa_ripple_icon_svg_color}" stroke-width="6">
                      <animate attributeName="r" repeatCount="indefinite" dur="1.5" values="0;36" keyTimes="0;1" keySplines="0 0.2 0.8 1" calcMode="spline" begin="0s"></animate>
                      <animate attributeName="opacity" repeatCount="indefinite" dur="1.5" values="1;0" keyTimes="0;1" keySplines="0.2 0 0.8 1" calcMode="spline" begin="0s"></animate>
                    </circle><circle cx="50" cy="50" r="0" fill="none" stroke="{bpa_ripple_icon_svg_color}" stroke-width="6">
                      <animate attributeName="r" repeatCount="indefinite" dur="1.5" values="0;36" keyTimes="0;1" keySplines="0 0.2 0.8 1" calcMode="spline" begin="-0.5263157894736842s"></animate>
                      <animate attributeName="opacity" repeatCount="indefinite" dur="1.5" values="1;0" keyTimes="0;1" keySplines="0.2 0 0.8 1" calcMode="spline" begin="-0.5263157894736842s"></animate>
                    </circle>
                    </svg>';
    
                    $bpa_ripple_icon_svg = base64_encode(str_replace('{bpa_ripple_icon_svg_color}', $bookingpress_primary_color, $bpa_ripple_icon_svg ));

                    $bookingpress_customize_css_content ='.bpp-frontend-main-container-package .bpp-frontend-main-inner_container .bpp-front-loader-container .bpp-front-loader svg { display: none; } .bpp-frontend-main-container-package .bpp-frontend-main-inner_container .bpp-front-loader-container .bpp-front-loader{ background: url(data:image/svg+xml;base64,'.$bpa_ripple_icon_svg.') no-repeat left top; background-size: 100%; }';
    
                } 
                
                if( !empty( $bookingpress_customize_css_content )){

                    wp_add_inline_style( 'bookingpress_front_package_custom', $bookingpress_customize_css_content, 'after' );

                }
            }
        }

		/**
		 * Function for send forgot password email notification
		 *
		 * @param  mixed $bookingpress_email
		 * @return void
		 */
		function bookingpress_send_forgotpassword_email($bookingpress_email){
			
			global $BookingPress,$wpdb;	
			$user_data = "";	
			if ( empty( $bookingpress_email ) ) {
				return false;
			} else if ( strpos( $bookingpress_email, '@' ) ) {
				$user_data = get_user_by( 'email', trim( $bookingpress_email ) );
				if ( empty( $user_data ) )
					return false;
			} else {
				$login = trim($bookingpress_email);
				$user_data = get_user_by('login', $login);				
				if ( !$user_data ) 			
					return false;
			}	

			do_action('lostpassword_post');
			
			// redefining user_login ensures we return the right case in the email
			$user_login = $user_data->user_login;
			$user_email = $user_data->user_email;

			do_action('retreive_password', $user_login);  // Misspelled and deprecated
			do_action('retrieve_password', $user_login);
		
			$allow = apply_filters('allow_password_reset', true, $user_data->ID);
           
			if ( ! $allow )
				return false;
			else if ( is_wp_error($allow) )
				return false;
			
			$key = get_password_reset_key($user_data);
			
			$message = esc_html__('Someone requested that the password be reset for the following account:', 'bookingpress-package') . "\r\n\r\n";
			$message .= network_home_url( '/' ) . "\r\n\r\n";
			/* translators: 1. Username */
			$message .= sprintf(esc_html__('Username: %s', 'bookingpress-package'), $user_login) . "\r\n\r\n";
			$message .= esc_html__('If this was a mistake, just ignore this email and nothing will happen.', 'bookingpress-package') . "\r\n\r\n";
			$message .= esc_html__('To reset your password, visit the following address:', 'bookingpress-package') . "\r\n\r\n";

			$bookingpress_password_reset_link = network_site_url("wp-login.php?action=rp&key=$key&login=" . rawurlencode($user_login), 'login');

			$message .= $bookingpress_password_reset_link."\r\n";

			$blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);	
			/* translators: 1. Site Name */
			$title = sprintf( esc_html__('[%s] Password Reset', 'bookingpress-package'), $blogname );	
			$title = apply_filters('retrieve_password_title', $title);
			$message = apply_filters('retrieve_password_message', $message, $key);	
            
            /*
            wp_mail($user_email, 'Test Title', 'Test Message');

            echo $user_email;
            echo $title;
            die;
            */

			wp_mail($user_email, $title, $message);
			return true;
		}


        /**
         * Function for forgot password package functionality
         *
         * @return void
         */
        function bookingpress_package_forgot_password_account_func(){
			
            global $BookingPress;

			$bookingpress_forgot_password_err_msg = $BookingPress->bookingpress_get_customize_settings('forgot_password_error_message', 'package_booking_form');
			$bookingpress_forgot_password_success_msg = $BookingPress->bookingpress_get_customize_settings('forgot_password_success_message_label', 'package_booking_form');
            
			$response              = array();
			$response['variant'] = 'error';
			$response['title']   = esc_html__( 'Error', 'bookingpress-package' );
			$response['msg'] = stripslashes_deep($bookingpress_forgot_password_err_msg);

			$wpnonce               = isset( $_REQUEST['_wpnonce'] ) ? sanitize_text_field( $_REQUEST['_wpnonce'] ) : '';
			$bpa_verify_nonce_flag = wp_verify_nonce( $wpnonce, 'bpa_wp_nonce' );
			if ( ! $bpa_verify_nonce_flag ) {
				$response['msg']     = esc_html__( 'Sorry, Your request can not be processed due to security reason.', 'bookingpress-package' );
				echo wp_json_encode( $response );
				die();
			}            
			$bookingpress_forgot_pass_email = !empty($_POST['forgot_pass_email_address']) ? sanitize_email($_POST['forgot_pass_email_address']) : '';
			if(!empty($bookingpress_forgot_pass_email)){
				$return  = $this->bookingpress_send_forgotpassword_email($bookingpress_forgot_pass_email);
				if($return){
					$response['variant'] = 'success';
					$response['title'] = esc_html__('Success', 'bookingpress-package');
					$response['msg'] = stripslashes_deep($bookingpress_forgot_password_success_msg);
				}
			}
			echo wp_json_encode($response);
			exit;            
            
        }
        
		/**
		 * Pakage Customer Account Register request callback function
		 *
		 * @return void
		 */
		function bookingpress_package_register_customer_account_func(){

            global $wpdb,$BookingPress,$tbl_bookingpress_customers,$tbl_bookingpress_form_fields;

            $signup_account_fullname_required_message = $BookingPress->bookingpress_get_customize_settings('signup_account_fullname_required_message', 'package_booking_form');
            $signup_account_email_required_message = $BookingPress->bookingpress_get_customize_settings('signup_account_email_required_message', 'package_booking_form');
            $signup_account_mobile_number_required_message = $BookingPress->bookingpress_get_customize_settings('signup_account_mobile_number_required_message', 'package_booking_form');
            $signup_account_password_required_message = $BookingPress->bookingpress_get_customize_settings('signup_account_password_required_message', 'package_booking_form');                        
            $signup_wrong_email_message = $BookingPress->bookingpress_get_customize_settings('signup_wrong_email_message', 'package_booking_form');
            $signup_email_exists = $BookingPress->bookingpress_get_customize_settings('signup_email_exists', 'package_booking_form');

			$wpnonce               = isset( $_REQUEST['_wpnonce'] ) ? sanitize_text_field( $_REQUEST['_wpnonce'] ) : '';
			$bpa_verify_nonce_flag = wp_verify_nonce( $wpnonce, 'bpa_wp_nonce' );
			$response              = array();
			$response['variant'] = 'error';
			$response['title']   = esc_html__( 'Error', 'bookingpress-package' );
                        
			if(!$bpa_verify_nonce_flag){
				$response['msg']     = esc_html__( 'Sorry, Your request can not be processed due to security reason.', 'bookingpress-package' );
				echo wp_json_encode( $response );
				die();
			}            
            $full_name = (isset($_POST['full_name']))?sanitize_text_field($_POST['full_name']):'';
            $login_email_address = (isset($_POST['login_email_address']))?sanitize_text_field($_POST['login_email_address']):'';
            $login_password = (isset($_POST['login_password']))?sanitize_text_field($_POST['login_password']):'';
            $customer_phone = (isset($_POST['customer_phone']))?sanitize_text_field($_POST['customer_phone']):'';
            /*
            if(empty($full_name) || empty($login_email_address) || empty($login_password) || empty($customer_phone)){
				$response['msg']     = esc_html__( 'Please enter required field.', 'bookingpress-package' );
				echo wp_json_encode( $response );
				die();                
            }
            */
            if(empty($full_name)){
				$response['msg']     = $signup_account_fullname_required_message;
				echo wp_json_encode( $response );
				die();                   
            }
            if(empty($login_email_address)){
				$response['msg']     = $signup_account_email_required_message;
				echo wp_json_encode( $response );
				die();                   
            }
            if(empty($customer_phone)){
				$response['msg']     = $signup_account_mobile_number_required_message;
				echo wp_json_encode( $response );
				die();                   
            }
            if(empty($login_password)){
				$response['msg']     = $signup_account_password_required_message;
				echo wp_json_encode( $response );
				die();                   
            }                                    
            if(!is_email($login_email_address)){
				$response['msg']     = $signup_wrong_email_message;
				echo wp_json_encode( $response );
				die();  
            }            
            $bookingpress_is_wp_user_exist = get_user_by('email', $login_email_address);
            if(!empty($bookingpress_is_wp_user_exist)){
				$response['msg']     = $signup_email_exists;
				echo wp_json_encode( $response );
				die();  
            }
            $bookingpress_wpuser_id = wp_create_user($login_email_address, $login_password, $login_email_address);
            if(!$bookingpress_wpuser_id){
				$response['msg']     = esc_html__('Something wrong user not created.', 'bookingpress-package' );
				echo wp_json_encode( $response );
				die();                
            }
            wp_send_new_user_notifications($bookingpress_wpuser_id);
            $full_name_arr = explode(" ",$full_name);

            $bookingpress_customer_firstname = (isset($full_name_arr[0]))?$full_name_arr[0]:'';
            $bookingpress_customer_lastname = (isset($full_name_arr[1]))?$full_name_arr[1]:'';
            /* Update WordPress user firstname and lastname */
            $booking_user_update_meta_details = array();

            $booking_user_update_meta_details['customer_phone'] = $customer_phone;
            if(!empty($bookingpress_customer_firstname)){
                $booking_user_update_meta_details['first_name'] = $bookingpress_customer_firstname;
            }
            if(!empty($bookingpress_customer_lastname)){
                $booking_user_update_meta_details['last_name'] = $bookingpress_customer_lastname;
            }
            if ( ! empty( $bookingpress_wpuser_id ) ) {
                do_action( 'bookingpress_user_update_meta', $bookingpress_wpuser_id, $booking_user_update_meta_details );
            }
            $bookingpress_is_customer_exist = $wpdb->get_var($wpdb->prepare("SELECT COUNT(bookingpress_customer_id) as total FROM {$tbl_bookingpress_customers} WHERE bookingpress_user_email = %s AND bookingpress_user_type = 2", $login_email_address)); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- Reason: $tbl_bookingpress_customers is table name defined globally. False Positive alarm

            $bookingpress_customer_dial_code = (isset($_POST['customer_phone_dial_code']))?sanitize_text_field($_POST['customer_phone_dial_code']):'';
            $bookingpress_customer_country = (isset($_POST['customer_phone_country']))?sanitize_text_field($_POST['customer_phone_country']):'';
            $bookingpress_customer_timezone = (isset($_POST['bookingpress_customer_timezone']))?sanitize_text_field($_POST['bookingpress_customer_timezone']):'';

            if ($bookingpress_is_customer_exist == 0) {
                
                $customer_details = array(
                    'bookingpress_wpuser_id'   => $bookingpress_wpuser_id,
                    'bookingpress_user_login'  => $login_email_address,
                    'bookingpress_user_status' => 1,
                    'bookingpress_user_type'   => 2,
                    'bookingpress_user_email'  => $login_email_address,
                    'bookingpress_user_name'   => $full_name,
                    'bookingpress_customer_full_name'  => $full_name,
                    'bookingpress_user_firstname' => $bookingpress_customer_firstname,
                    'bookingpress_user_lastname' => $bookingpress_customer_lastname,
                    'bookingpress_user_phone'  => $customer_phone,
                    'bookingpress_user_country_phone' => $bookingpress_customer_country,
                    'bookingpress_user_country_dial_code' => $bookingpress_customer_dial_code,
                    'bookingpress_user_timezone' => $bookingpress_customer_timezone,
                    'bookingpress_user_created' => current_time('mysql'),
                    'bookingpress_created_by'  => $bookingpress_wpuser_id,
                );

                $wpdb->insert($tbl_bookingpress_customers, $customer_details);
                $bookingpress_customer_id = $wpdb->insert_id;
                $bookingpress_is_customer_create = 1;
                do_action( 'bookingpress_after_signup_customer_from_package', $bookingpress_customer_id );
                
            }

            $bookingpress_form_fields = isset($_POST['form_fields']) ? $_POST['form_fields'] : array(); // phpcs:ignore
            $bookingpress_login_arr = array(
                'user_login' => $login_email_address,
                'user_password' => $login_password,                
            );
            $bookingpress_user_signin = wp_signon($bookingpress_login_arr);

            wp_set_current_user( $bookingpress_wpuser_id );				
            $BookingPress->bookingpress_add_user_role_and_capabilities();

            $bookingpress_customer_id = '';
            $bookingpress_package_login_user_data = $this->get_bookingpress_package_login_user_data();
            if(!empty($bookingpress_package_login_user_data)){

                $bookingpress_customer_id = $bookingpress_package_login_user_data['bookingpress_customer_id'];
                $bookingpress_form_fields['customer_name'] = (isset($bookingpress_package_login_user_data['customer_name']))?$bookingpress_package_login_user_data['customer_name']:'';
                $bookingpress_form_fields['customer_username'] = (isset($bookingpress_package_login_user_data['customer_username']))?$bookingpress_package_login_user_data['customer_username']:'';
                $bookingpress_form_fields['customer_firstname'] = (isset($bookingpress_package_login_user_data['customer_firstname']))?$bookingpress_package_login_user_data['customer_firstname']:'';
                $bookingpress_form_fields['customer_lastname'] = (isset($bookingpress_package_login_user_data['customer_lastname']))?$bookingpress_package_login_user_data['customer_lastname']:'';
                $bookingpress_form_fields['customer_email'] = (isset($bookingpress_package_login_user_data['customer_email']))?$bookingpress_package_login_user_data['customer_email']:'';
                $bookingpress_form_fields['customer_phone'] = (isset($bookingpress_package_login_user_data['customer_phone']))?$bookingpress_package_login_user_data['customer_phone']:'';
                $bookingpress_form_fields['customer_phone_country'] = (isset($bookingpress_package_login_user_data['customer_phone_country']))?$bookingpress_package_login_user_data['customer_phone_country']:'';
                $bookingpress_form_fields['customer_lastname'] = (isset($bookingpress_package_login_user_data['customer_lastname']))?$bookingpress_package_login_user_data['customer_lastname']:'';
                
                $all_external_fields = $wpdb->get_results( $wpdb->prepare( "SELECT bookingpress_field_meta_key,bookingpress_form_field_name FROM {$tbl_bookingpress_form_fields} WHERE bookingpress_field_type NOT IN ('2_col', '3_col', '4_col') AND bookingpress_field_is_default != %d AND bookingpress_field_is_package_hide != %d", 1,0) ); // phpcs:ignore
                if( !empty( $all_external_fields ) ){

                    foreach( $all_external_fields as $external_field_data ){

                        $field_name = $external_field_data->bookingpress_form_field_name;
                        $field_metakey = $external_field_data->bookingpress_field_meta_key;
                        if( $field_name == 'Password'){
                            $bookingpress_form_fields['customer_password'] = '';
                        } else {
                            $bookingpress_form_fields[ $field_metakey ] = '';
                        }

                    }

                }
            }

            $response['variant'] = 'success';
            $response['title'] = esc_html__('Success', 'bookingpress-package');
            $response['msg'] = esc_html__('Login Successfully', 'bookingpress-package');
            $response['login_user_id'] =  $bookingpress_user_signin->ID;
            $response['bookingpress_customer_id'] =  $bookingpress_customer_id;
            $response['bookingpress_form_fields'] =  $bookingpress_form_fields;
            $response['login_user_detail'] =  wp_get_current_user();
            $response['new_nonce'] = wp_create_nonce('bpa_wp_nonce');        

			echo wp_json_encode($response);
			exit;

        }

		/**
		 * Pakage Customer Account Login request callback function
		 *
		 * @return void
		 */
		function bookingpress_package_login_customer_account_func(){

			global $BookingPress,$tbl_bookingpress_form_fields,$wpdb,$tbl_bookingpress_customers_meta,$tbl_bookingpress_customers;            
            
            $package_login_error_message_label = $BookingPress->bookingpress_get_customize_settings('login_error_message_label', 'package_booking_form');
			$bookingpress_login_err_msg = $package_login_error_message_label;
			$response              = array();
			$response['variant'] = 'error';
			$response['title']   = esc_html__( 'Error', 'bookingpress-package' );
			$response['msg'] = stripslashes_deep($bookingpress_login_err_msg);
			$response['new_nonce'] = '';
			$response['is_bookingpress_staffmember'] = 0;

			$wpnonce               = isset( $_REQUEST['_wpnonce'] ) ? sanitize_text_field( $_REQUEST['_wpnonce'] ) : '';
			$bpa_verify_nonce_flag = wp_verify_nonce( $wpnonce, 'bpa_wp_nonce' );
			if ( ! $bpa_verify_nonce_flag ) {
				$response['msg']     = esc_html__( 'Sorry, Your request can not be processed due to security reason.', 'bookingpress-package' );
				echo wp_json_encode( $response );
				die();
			}

			$bookingpress_login_email = !empty($_POST['login_email_address']) ? sanitize_text_field($_POST['login_email_address']) : '';
			$bookingpress_login_pass = !empty($_POST['login_password']) ? $_POST['login_password'] : ''; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized --Reason: contains password and no need sanitize
			$bookingpress_remember_me = !empty($_POST['is_remember']) ? true : false;
            $bookingpress_form_fields = isset($_POST['form_fields']) ? $_POST['form_fields'] : array(); // phpcs:ignore

			if(!empty($bookingpress_login_email) && !empty($bookingpress_login_pass)){

				$bookingpress_login_arr = array(
					'user_login' => $bookingpress_login_email,
					'user_password' => $bookingpress_login_pass,
					'remember' => $bookingpress_remember_me
				);
				$bookingpress_user_signin = wp_signon($bookingpress_login_arr);

				/*
                if ( isset( $bookingpress_user_signin->roles ) && is_array( $bookingpress_user_signin->roles ) && isset( $bookingpress_user_signin->caps ) && is_array( $bookingpress_user_signin->caps )) {
					if ( in_array( 'bookingpress-staffmember', $bookingpress_user_signin->roles ) && !in_array( 'administrator', $bookingpress_user_signin->roles ) && in_array( 'bookingpress', $bookingpress_user_signin->caps )) {
						$redirect_to =  esc_url( admin_url() . 'admin.php?page=bookingpress');
						$bookingpress_staffmember_access_admin = $BookingPress->bookingpress_get_settings( 'bookingpress_staffmember_access_admin', 'staffmember_setting' );
						if((!empty($_COOKIE['bookingpress_staffmember_view']) && $_COOKIE['bookingpress_staffmember_view'] == 'admin_view') && !empty($bookingpress_staffmember_access_admin) && $bookingpress_staffmember_access_admin == 'true') {
							$redirect_to = add_query_arg( 'staffmember_view','admin_view',$redirect_to);
						}
						$response['is_bookingpress_staffmember'] = 1;
						$response['staff_redirect_to'] = $redirect_to;

					}
				}
				*/
				if(!is_wp_error($bookingpress_user_signin)){
                    //$userObj = new WP_User($user_id);
					wp_set_current_user( $bookingpress_user_signin->ID );				
                    $BookingPress->bookingpress_add_user_role_and_capabilities();

                    //$bookingpress_form_fields

                                 
                    $bookingpress_customer_id = '';
                    $bookingpress_package_login_user_data = $this->get_bookingpress_package_login_user_data();
                    if(!empty($bookingpress_package_login_user_data)){
    
                        $bookingpress_customer_id = $bookingpress_package_login_user_data['bookingpress_customer_id'];
                        $bookingpress_form_fields['customer_name'] = (isset($bookingpress_package_login_user_data['customer_name']))?$bookingpress_package_login_user_data['customer_name']:'';
                        $bookingpress_form_fields['customer_username'] = (isset($bookingpress_package_login_user_data['customer_username']))?$bookingpress_package_login_user_data['customer_username']:'';
                        $bookingpress_form_fields['customer_firstname'] = (isset($bookingpress_package_login_user_data['customer_firstname']))?$bookingpress_package_login_user_data['customer_firstname']:'';
                        $bookingpress_form_fields['customer_lastname'] = (isset($bookingpress_package_login_user_data['customer_lastname']))?$bookingpress_package_login_user_data['customer_lastname']:'';
                        $bookingpress_form_fields['customer_email'] = (isset($bookingpress_package_login_user_data['customer_email']))?$bookingpress_package_login_user_data['customer_email']:'';
                        $bookingpress_form_fields['customer_phone'] = (isset($bookingpress_package_login_user_data['customer_phone']))?$bookingpress_package_login_user_data['customer_phone']:'';
                        $bookingpress_form_fields['customer_phone_country'] = (isset($bookingpress_package_login_user_data['customer_phone_country']))?$bookingpress_package_login_user_data['customer_phone_country']:'';
                        $bookingpress_form_fields['customer_lastname'] = (isset($bookingpress_package_login_user_data['customer_lastname']))?$bookingpress_package_login_user_data['customer_lastname']:'';
                        
                        $all_external_fields = $wpdb->get_results( $wpdb->prepare( "SELECT bookingpress_field_meta_key,bookingpress_form_field_name FROM {$tbl_bookingpress_form_fields} WHERE bookingpress_field_type NOT IN ('2_col', '3_col', '4_col') AND bookingpress_field_is_default != %d AND bookingpress_field_is_package_hide != %d", 1,0) ); // phpcs:ignore
                        if( !empty( $all_external_fields ) ){
    
                            foreach( $all_external_fields as $external_field_data ){
    
                                $field_name = $external_field_data->bookingpress_form_field_name;
                                $field_metakey = $external_field_data->bookingpress_field_meta_key;
                                if( $field_name == 'Password'){
                                    $bookingpress_form_fields['customer_password'] = '';
                                } else {
                                    $bookingpress_form_fields[ $field_metakey ] = '';
                                }
    
                            }
    
                        }
                    }
                    
                    /** Set customer form field data */
                    $bpa_form_fields = (is_array($bookingpress_form_fields))?$bookingpress_form_fields:array();
                    if( is_user_logged_in() && !empty( $bpa_form_fields ) ){
                        //global $tbl_bookingpress_customers, $tbl_bookingpress_customers_meta;
                        $current_user_id = get_current_user_id();
                        $bpa_is_user_customer = $wpdb->get_row( $wpdb->prepare( "SELECT bookingpress_customer_id FROM {$tbl_bookingpress_customers} WHERE bookingpress_wpuser_id = %d ORDER BY bookingpress_customer_id DESC", $current_user_id ) ); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- Reason: $tbl_bookingpress_customers is table name defined globally. False Positive alarm 
                        if( !empty( $bpa_is_user_customer ) ){
                            $current_user_customer_id = $bpa_is_user_customer->bookingpress_customer_id;
                            $bpa_form_field_keys = array_keys( $bpa_form_fields );
                            $bpa_excluded_keys = array( 'customer_name', 'customer_firstname', 'customer_lastname', 'customer_email', 'customer_phone', 'customer_phone_country', 'appointment_note');
                            foreach( $bpa_form_field_keys as $bpa_field_key ){
                                if( !in_array( $bpa_field_key, $bpa_excluded_keys ) ){
                                    $is_customer_field = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT( bookingpress_form_field_id ) FROM {$tbl_bookingpress_form_fields} WHERE bookingpress_field_meta_key = %s AND bookingpress_is_customer_field = %d", $bpa_field_key, 1) ); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- Reason: $tbl_bookingpress_form_fields is table name defined globally. False Positive alarm 

                                    if( 1 == $is_customer_field ){
                                        $meta_value = $wpdb->get_row( $wpdb->prepare( "SELECT bookingpress_customersmeta_value FROM {$tbl_bookingpress_customers_meta} WHERE bookingpress_customersmeta_key = %s AND bookingpress_customer_id = %d", $bpa_field_key, $current_user_customer_id )); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- Reason: $tbl_bookingpress_customers_meta is table name defined globally. False Positive alarm 
                                        if( !empty( $meta_value ) ){
                                            $customer_meta_value = $meta_value->bookingpress_customersmeta_value;
                                            $is_json = json_decode( $customer_meta_value, true ) == NULL ? false : true;
                                            if( $is_json  ){
                                                $customer_meta_value = json_decode( $customer_meta_value, true );
                                            }
                                            $bookingpress_form_fields[ $bpa_field_key ] = $customer_meta_value;
                                        }
                                    }
                                }
                            }
                        }
                    }                    


					$response['variant'] = 'success';
					$response['title'] = esc_html__('Success', 'bookingpress-package');
					$response['msg'] = esc_html__('Login Successfully', 'bookingpress-package');
					$response['login_user_id'] =  $bookingpress_user_signin->ID;
                    $response['bookingpress_customer_id'] =  $bookingpress_customer_id;
                    $response['bookingpress_form_fields'] =  $bookingpress_form_fields;
                    $response['login_user_detail'] =  wp_get_current_user();
					$response['new_nonce'] = wp_create_nonce('bpa_wp_nonce');
				}
			}
			echo wp_json_encode($response);
			exit;
		}                
        
        /**
         * Function for get customer package order detail
         *
         * @return void
         */
        function bookingpress_get_customer_package_order_func($return_data = false,$user_id = ""){
            
            global $BookingPress,$wpdb,$tbl_bookingpress_appointment_bookings,$tbl_bookingpress_customers,$bookingpress_global_options, $tbl_bookingpress_payment_logs,$tbl_bookingpress_package_bookings,$bookingpress_package,$tbl_bookingpress_services;
            if(!$return_data){
                $this->set_package_front_css();
            }
            $response              = array();
            $wpnonce               = isset($_REQUEST['_wpnonce']) ? sanitize_text_field($_REQUEST['_wpnonce']) : '';
            $bpa_verify_nonce_flag = wp_verify_nonce($wpnonce, 'bpa_wp_nonce');
            if(!$bpa_verify_nonce_flag){
                $response['variant'] = 'error';
                $response['title']   = esc_html__('Error', 'bookingpress-package');
                $response['msg']     = esc_html__('Sorry, Your request can not be processed due to security reason.', 'bookingpress-package');
                if($return_data){
                    return $response;
                }
                wp_send_json($response);
                die();
            }
            $bpa_login_customer_id = get_current_user_id();
            if(!empty($user_id)){
                $bpa_login_customer_id = $user_id;
            }
            $bookingpress_get_customer_details = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$tbl_bookingpress_customers} WHERE bookingpress_wpuser_id =%d ORDER BY bookingpress_customer_id DESC", $bpa_login_customer_id), ARRAY_A); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- Reason: $tbl_bookingpress_customers is table name defined globally. False Positive alarm
            $bookingpress_current_user_id      = ! empty($bookingpress_get_customer_details['bookingpress_customer_id']) ? $bookingpress_get_customer_details['bookingpress_customer_id'] : 0;
            
            $bookingpress_total_package_order = 0;
            $perpage     = isset($_POST['perpage']) ? intval($_POST['perpage']) : 10;
            $currentpage = isset($_POST['currentpage']) ? intval($_POST['currentpage']) : 1;
            $offset      = ( ! empty($currentpage) && $currentpage > 1 ) ? ( ( $currentpage - 1 ) * $perpage ) : 0;

            
            $pakage_order_data = array();
            $bookingpress_total_pakage_order = 0;

            if (! empty($bookingpress_current_user_id) ) {


                $bookingpress_search_data        = ! empty($_REQUEST['search_data']) ? array_map(array( $BookingPress, 'appointment_sanatize_field' ), $_REQUEST['search_data']) : array(); // phpcs:ignore
                $bookingpress_search_query       = '';
                $bookingpress_search_query_where = "WHERE 1=1 AND (bookingpress_login_user_id={$bpa_login_customer_id}) ";
                
                    if(!empty($bookingpress_search_data) ) {

                        
                        if(! empty($bookingpress_search_data['search_package']) ) {
                            $bookingpress_search_string       = $bookingpress_search_data['search_package'];
                            $bookingpress_search_query_where .= "AND (bookingpress_package_name LIKE '%{$bookingpress_search_string}%') ";
                        }
                        if ( !empty ( $bookingpress_search_data['selected_date_range'] ) && ! empty($bookingpress_search_data['selected_date_range'][0] && $bookingpress_search_data['selected_date_range'][1]) ) {                        
                            $bookingpress_search_date         = $bookingpress_search_data['selected_date_range'];
                            $start_date                       = date('Y-m-d', strtotime($bookingpress_search_date[0]));
                            $end_date                         = date('Y-m-d', strtotime($bookingpress_search_date[1]));
                            $bookingpress_search_query_where .= "AND (bookingpress_package_expiration_date BETWEEN '{$start_date}' AND '{$end_date}')";
                        }                  
                    }

                    $bookingpress_global_data = $bookingpress_global_options->bookingpress_global_options();
                    $bookingpress_date_format = $bookingpress_global_data['wp_default_date_format'];
                    $bookingpress_time_format = $bookingpress_global_data['wp_default_time_format'];
                    $bookingpress_appointment_statuses = $bookingpress_global_data['appointment_status'];
                    $bookingpress_payment_statuses = $bookingpress_global_data['payment_status'];
                    
                    $bookingpress_total_pakage_order = $wpdb->get_var("SELECT COUNT(bookingpress_package_booking_id) FROM {$tbl_bookingpress_package_bookings} {$bookingpress_search_query} {$bookingpress_search_query_where} ORDER BY bookingpress_package_booking_id DESC"); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared --Reason: $tbl_bookingpress_package_bookings is a table name. false alarm                    

                    $pakage_order_data = $wpdb->get_results("SELECT * FROM {$tbl_bookingpress_package_bookings} {$bookingpress_search_query} {$bookingpress_search_query_where} ORDER BY bookingpress_package_booking_id DESC LIMIT {$offset} , {$perpage}", ARRAY_A); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared --Reason: $tbl_bookingpress_package_bookings is a table name. false alarm

                    if(!empty($pakage_order_data) && is_array($pakage_order_data) ){
                        foreach($pakage_order_data as $k => $v){
                            
                            $bookingpress_package_expiration_date_formated = date_i18n($bookingpress_date_format, strtotime($v['bookingpress_package_expiration_date']));
                            $bookingpress_package_purchase_date_formatted = $v['bookingpress_package_purchase_date']; 

                            if(isset($v['bookingpress_package_purchase_date']) && ($v['bookingpress_package_purchase_date'])) {
                                $bookingpress_package_purchase_date_formatted = date_i18n($bookingpress_date_format, strtotime($v['bookingpress_package_purchase_date'])); 
                            }
                            $pakage_order_data[$k]['bookingpress_package_purchase_date_formated'] = $bookingpress_package_purchase_date_formatted;
                            $pakage_order_data[$k]['bookingpress_package_expiration_date_formated'] = $bookingpress_package_expiration_date_formated;
                            $bookingpress_package_services_data = $v['bookingpress_package_services'];
                            $bookingpress_package_services_arr = array();
                            if(!empty($bookingpress_package_services_data)){
                                $bookingpress_package_services_arr  = json_decode($bookingpress_package_services_data,true);
                            }
                            $package_total_appointments = 0;
                            $package_total_remaining_appointments = 0;                            
                            if(!empty($bookingpress_package_services_arr)){
                                foreach($bookingpress_package_services_arr as $sk=>$package_serv){
                                    $package_total_appointments = $package_total_appointments+(int)$package_serv['bookingpress_no_of_appointments'];

                                    $service_booked_appointment = $bookingpress_package->get_package_service_purchase_count($v['bookingpress_package_no'],$package_serv['bookingpress_service_id']);
                                    
                                    $service_remaining_appointment = intval($package_serv['bookingpress_no_of_appointments']) -  $service_booked_appointment;
                                    $package_total_remaining_appointments = $package_total_remaining_appointments + $service_remaining_appointment;

                                    $bookingpress_package_services_arr[$sk]['booked_appointment'] = $service_booked_appointment;
                                    $bookingpress_package_services_arr[$sk]['service_remaining_appointment'] = $service_remaining_appointment;

                                    $service_id_of_package = isset($package_serv['bookingpress_service_id']) ? $package_serv['bookingpress_service_id'] : '';
                                    $package_service_detail = $wpdb->get_row( $wpdb->prepare( "SELECT bookingpress_service_name,bookingpress_service_duration_val,bookingpress_service_duration_unit FROM {$tbl_bookingpress_services} WHERE bookingpress_service_id = %d", $service_id_of_package ) ); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- Reason: $tbl_bookingpress_services is table name defined globally. 
                                    $bookingpress_service_duration_val = (isset($package_service_detail->bookingpress_service_duration_val))?$package_service_detail->bookingpress_service_duration_val:'';
                                    $bookingpress_service_duration_unit = (isset($package_service_detail->bookingpress_service_duration_unit))?$package_service_detail->bookingpress_service_duration_unit:'';          
                                    $service_duration = $bookingpress_package->bookingpress_get_service_duration_text($bookingpress_service_duration_val,$bookingpress_service_duration_unit);        
                                    $bookingpress_package_services_arr[$sk]['service_duration'] = $service_duration;             
                                }
                            }
                            $pakage_order_data[$k]['bookingpress_package_services'] = $bookingpress_package_services_arr;                            
                            $pakage_order_data[$k]['package_total_appointments'] = $package_total_appointments;
                            $pakage_order_data[$k]['package_total_remaining_appointments'] = $package_total_remaining_appointments;
                            $pakage_order_data[$k]['package_expire_date_class'] = (strtotime($v['bookingpress_package_expiration_date']) < strtotime(date('Y-m-d')))?'bpp-package-expire':'';
                            $bookingpress_package_status = esc_html($v['bookingpress_package_booking_status']);
                            $bookingpress_package_status_arr = $bookingpress_package->get_package_order_status();
                            $status_key = array_search($bookingpress_package_status, array_column($bookingpress_package_status_arr, 'value'));
                            $pakage_order_data[$k]['bookingpress_package_status_label'] = $bookingpress_package_status_arr[$status_key]['text'];
                            $pakage_order_data[$k]['bookingpress_package_status'] = $bookingpress_package_status;
                            $currency_name   = $v['bookingpress_package_currency'];
                            $currency_symbol = $BookingPress->bookingpress_get_currency_symbol($currency_name);
                            $bookingpress_paid_price_with_currency = $BookingPress->bookingpress_price_formatter_with_currency_symbol($v['bookingpress_package_paid_amount'], $currency_symbol);
                            $pakage_order_data[$k]['bookingpress_paid_price_with_currency'] = $bookingpress_paid_price_with_currency;
    
                            $bookingpress_payment_log_details = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$tbl_bookingpress_payment_logs} WHERE bookingpress_package_order_booking_ref = %d", intval($v['bookingpress_package_booking_id'])), ARRAY_A); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared --Reason: $tbl_bookingpress_payment_logs is a table name. false alarm

                            $bookingpress_payment_status = $bookingpress_payment_status_label = $bookingpress_payment_method = '';
                            if(!empty($bookingpress_payment_log_details)){
                                $bookingpress_payment_method = $bookingpress_payment_log_details['bookingpress_payment_gateway'];
                                $bookingpress_payment_method = apply_filters('bookingpress_selected_gateway_label_name_package', $bookingpress_payment_method, $bookingpress_payment_method);                                
                                $bookingpress_payment_status = $bookingpress_payment_log_details['bookingpress_payment_status'];
                                foreach($bookingpress_payment_statuses as $k2 => $v2){
                                    if($v2['value'] == $bookingpress_payment_status){
                                        $bookingpress_payment_status_label = $v2['text'];
                                    }
                                }
                            }                            

                            $pakage_order_data[$k]['bookingpress_payment_status'] = $bookingpress_payment_status;
                            $pakage_order_data[$k]['bookingpress_payment_status_label'] = $bookingpress_payment_status_label;
                            $pakage_order_data[$k]['bookingpress_payment_method'] = $bookingpress_payment_method;
    
                            $appointment_status_cls = '';
                            $pakage_order_data[$k]['bookingpress_payment_status_class'] = $appointment_status_cls;   
                            
                            //refer - bookingpress_modify_my_appointment_data

                        
                    }                        
                }

            }

            $data['items'] = $pakage_order_data;
            $data['total_records'] = $bookingpress_total_pakage_order;
            $data = apply_filters('bookingpress_modify_my_package_order_data', $data);
            if($return_data){
                return $data;
            }            
            wp_send_json($data);
            exit;

        }

        /**
         * Function for add dynamic vue method
         *
         * @return void
         */
        function bookingpress_front_appointments_dynamic_vue_methods_func(){

            $bookingpress_nonce = wp_create_nonce('bpa_wp_nonce');            
        ?>
            toggleBusyPackage() {
                if(this.is_display_loader == '1'){
                    this.is_display_package_loader = '0';
                }else{
                    this.is_display_package_loader = '1';
                }
            },         
            loadFrontMyPackages(is_display_loader = 0){
                
                const vm = this;
                vm.disable_my_package_apply = true;
                //this.toggleBusyPackage();
                var bookingpress_search_data = { 'search_package':this.search_package,'selected_date_range': this.package_date_range};

                if(is_display_loader == 1 ){
                    //vm.is_front_my_package_empty_loader = "1";
                }
                var bkp_wpnonce_pre = vm.bookingpress_created_nonce;
                var bkp_wpnonce_pre_fetch = document.getElementById("_wpnonce");
                if(typeof bkp_wpnonce_pre_fetch == "undefined" || bkp_wpnonce_pre_fetch == null){
                    bkp_wpnonce_pre_fetch = bkp_wpnonce_pre;
                }else {
                    bkp_wpnonce_pre_fetch = bkp_wpnonce_pre_fetch.value;
                }                
                var postData = { action:'bookingpress_get_customer_package_order', perpage:this.package_per_page, currentpage:this.package_currentPage, search_data: bookingpress_search_data,_wpnonce:bkp_wpnonce_pre_fetch};
                axios.post( appoint_ajax_obj.ajax_url, Qs.stringify( postData ) )                
                .then( function (response) {

                    //this.toggleBusyPackage();                    
                    vm.disable_my_package_apply = false;
                    this.package_items = response.data.items;                    
                    this.package_total_records = parseInt(response.data.total_records);                    
                    this.is_display_package_pagination = 0;
                    if( is_display_loader == 1){
                        vm.is_front_package_empty_loader = "0";
                        vm.is_front_my_package_empty_loader = "0";
                    }
                    if(this.package_total_records > 10) {
                        this.is_display_package_pagination = 1;
                    }                    
                }.bind(this) )
                .catch( function (error) {     
                    vm.disable_my_package_apply = false;               
                    vm.$notify({
                        title: '<?php esc_html_e('Error', 'bookingpress-package'); ?>',
                        message: '<?php esc_html_e('Something went wrong..', 'bookingpress-package'); ?>',
                        type: 'error',
                        customClass: 'error_notification',
                    });
                });                



                
            },
        <?php 
        }

        /**
         * Function for add dynamic onload method
         *
         * @return void
         */
        function bookingpress_dynamic_add_onload_myappointment_methods_func(){
            if(get_current_user_id()){            
        ?>
            this.loadFrontMyPackages();
        <?php
            }
        }

		/**
		 * Modify My Bookings Shortcode data
		 *
		 * @param  mixed $data
		 * @return void
		 */
		function bookingpress_front_appointment_my_booking_add_dynamic_data_func($bookingpress_front_appointment_vue_data_fields){

            global $wpdb, $BookingPress, $tbl_bookingpress_appointment_bookings, $tbl_bookingpress_payment_logs, $bookingpress_pro_staff_members, $bookingpress_global_options, $bookingpress_pro_payment, $bookingpress_services,$bookingpress_pro_appointment;

            $bookingpress_front_appointment_vue_data_fields['package_items'] = array();
            $bookingpress_front_appointment_vue_data_fields['is_display_package_loader'] = 0;
            $bookingpress_front_appointment_vue_data_fields['package_date_range'] = array();
            $bookingpress_front_appointment_vue_data_fields['search_package'] = '';
            $bookingpress_front_appointment_vue_data_fields['is_package_disabled'] = false;
            $bookingpress_front_appointment_vue_data_fields['disable_my_package_apply'] = false;
            $bookingpress_front_appointment_vue_data_fields['is_display_package_pagination'] = 0;
            $bookingpress_front_appointment_vue_data_fields['is_front_package_empty_loader'] = '0';
            $bookingpress_front_appointment_vue_data_fields['is_front_my_package_empty_loader'] = '0';
            $bookingpress_front_appointment_vue_data_fields['package_per_page'] = 10;
            $bookingpress_front_appointment_vue_data_fields['package_pagination_length'] = 10;
            $bookingpress_front_appointment_vue_data_fields['package_currentPage'] = 1;
            $bookingpress_front_appointment_vue_data_fields['package_total_records'] = 0;                        
            $bookingpress_front_appointment_vue_data_fields['package_service_name'] = '';
            $bookingpress_front_appointment_vue_data_fields['package_date'] = '';
            $bookingpress_front_appointment_vue_data_fields['package_duration'] = '';
            $bookingpress_front_appointment_vue_data_fields['package_status'] = '';
            $bookingpress_front_appointment_vue_data_fields['package_payment'] = '';

            return $bookingpress_front_appointment_vue_data_fields;
        }

        function bookingpress_package_front_booking_dynamic_vue_methods_func($bookingpress_package_front_booking_dynamic_vue_methods){
            
            global $BookingPress, $wp;
            $page_url = home_url( $wp->request );

            $bookingpress_nonce = wp_create_nonce('bpa_wp_nonce'); 
            $bookingpress_after_selecting_payment_method_data = '';
            $bookingpress_after_selecting_payment_method_data = apply_filters('bookingpress_after_selecting_payment_method_for_package_order', $bookingpress_after_selecting_payment_method_data);


            $bookingpress_reset_package_order_popup_data = '';
            $bookingpress_reset_package_order_popup_data = apply_filters('bookingpress_reset_package_order_popup_data', $bookingpress_reset_package_order_popup_data);


            $bookingpress_after_package_final_step_amount = '';
            $bookingpress_after_package_final_step_amount = apply_filters('bookingpress_after_package_final_step_amount', $bookingpress_after_package_final_step_amount);

            $bookingpress_before_package_purchase_data = '';
            $bookingpress_before_package_purchase_data = apply_filters('bookingpress_before_package_purchase_data', $bookingpress_before_package_purchase_data);

            $bookingpress_after_package_purchase_xhr_data = '';
            $bookingpress_after_package_purchase_xhr_data = apply_filters('bookingpress_after_package_response_xhr_data', $bookingpress_after_package_purchase_xhr_data);


            $bookingpress_redirection_mode = 'in-built';

            $is_success = (isset($_REQUEST['is_success']))?sanitize_text_field($_REQUEST['is_success']):'';
            $package_order_entry_id = (isset($_REQUEST['package_order_entry_id']))?sanitize_text_field($_REQUEST['package_order_entry_id']):'';
            if(!empty($package_order_entry_id) && !empty($is_success)){
                $bookingpresslistener = (isset($_REQUEST['bookingpress-listener']))?sanitize_text_field($_REQUEST['bookingpress-listener']):'';
                if($bookingpresslistener == "bpa_pro_razorpay_url"){

                }
            }

            $bookingpress_package_front_booking_dynamic_vue_methods.='
            bpp_external_thankyou_page(){
                const vm = this;
                var bkp_wpnonce_pre = "' . $bookingpress_nonce . '";
                var bkp_wpnonce_pre_fetch = document.getElementById("_wpnonce");
                if(typeof bkp_wpnonce_pre_fetch=="undefined" || bkp_wpnonce_pre_fetch==null)
                {
                    bkp_wpnonce_pre_fetch = bkp_wpnonce_pre;
                }
                else {
                    bkp_wpnonce_pre_fetch = bkp_wpnonce_pre_fetch.value;
                }
                
                var postData = { action:"bookingpress_package_render_external_thankyou_content", bookingpress_uniq_id: vm.package_step_form_data.bookingpress_uniq_id, _wpnonce:bkp_wpnonce_pre_fetch,"package_order_entry_id":"'.$package_order_entry_id.'","is_success":"'.$is_success.'" };                
                axios.post( appoint_ajax_obj.ajax_url, Qs.stringify( postData ) )
                .then( function (response) {
                    if(response.data.variant == "success"){

                        if(response.data.is_success == "2"){
                            
                            vm.bookingpress_current_package_tab = "summary";
                            vm.open_package_booking_modal = true;
                            vm.package_payment_failed = "1";

                        }else{
                            
                            vm.bookingpress_current_package_tab = "summary";
                            vm.bookingpress_booked_package_detail.bookingpress_package_no = response.data.thankyou_page_data.bookingpress_package_no;
                            vm.bookingpress_booked_package_detail.bookingpress_customer_name = response.data.thankyou_page_data.bookingpress_customer_name;
                            vm.bookingpress_booked_package_detail.bookingpress_package_name = response.data.thankyou_page_data.bookingpress_package_name;
                            if(response.data.thankyou_page_data.bookingpress_default_appointment_booking_page_url != ""){
                                vm.bookingpress_default_appointment_booking_page_url = response.data.thankyou_page_data.bookingpress_default_appointment_booking_page_url;
                            }
                            vm.open_package_booking_modal = true;
                        }
                    }
                }.bind(this) )
                .catch( function (error) {
                    vm.bookingpress_package_set_error_msg(error);
                });


            },
            bpp_check_password_validation( bpa_email_value ) {					
                const vm = this;                                
            },            
			async package_order_submit(){
				const vm = this;
                const vm2 = vm;
                vm.isPackageLoadBookingLoader = "1";
                vm.isPackageBookingDisabled = true;                
                
                var bkp_wpnonce_pre = "' . $bookingpress_nonce . '";
                var bkp_wpnonce_pre_fetch = document.getElementById("_wpnonce");
                if(typeof bkp_wpnonce_pre_fetch=="undefined" || bkp_wpnonce_pre_fetch==null){
                    bkp_wpnonce_pre_fetch = bkp_wpnonce_pre;
                }else {
                    bkp_wpnonce_pre_fetch = bkp_wpnonce_pre_fetch.value;
                }
                var postData = { action:"bookingpress_before_book_package_validate",_wpnonce:bkp_wpnonce_pre_fetch };
                postData.pageURL = "'.$page_url.'";
                postData.package_data = JSON.stringify( vm.package_step_form_data );
                
                '. $bookingpress_before_package_purchase_data.'      

				setTimeout(function(){
                    ' . $bookingpress_after_package_final_step_amount . '                            
                    
                    axios.post( appoint_ajax_obj.ajax_url, Qs.stringify( postData ) )
                    .then( function (response) {

                        '.$bookingpress_after_package_purchase_xhr_data.'

                        vm.isPackageLoadBookingLoader = "0";
                        vm.isPackageBookingDisabled = false;
                        /*
                        if(response.data.variant != "error"){
                            vm.bookingpress_render_package_thankyou_content();
                            vm.bookingpress_package_remove_error_msg(); 
                        }else{
                            vm2.bookingpress_package_set_error_msg(response.data.msg);
                        }
                        */
                        var bookingpress_redirection_mode = "'.$bookingpress_redirection_mode.'";
                        if(bookingpress_redirection_mode == "external_redirection"){                           
                            if(response.data.variant == "redirect"){                                
                                vm2.bookingpress_package_external_html = response.data.redirect_data;
                                setTimeout(function(){
                                    var scripts = document.getElementsByClassName("bpa-external-script-package")[0].querySelectorAll("script");
                                    if(scripts.length > 0){
                                        var text = scripts[scripts.length - 1].textContent;
                                        eval(text);
                                    }
                                },50);
                                vm2.bookingpress_package_remove_error_msg();
                            

                            }else if(response.data.variant == "redirect_url"){
                                vm2.bookingpress_package_remove_error_msg();
                                window.location.href = response.data.redirect_data;
                            }else if(response.data.variant == "error"){
                                vm2.bookingpress_package_set_error_msg(response.data.msg);
                            }else{
                                vm2.bookingpress_package_remove_error_msg();
                            }                            
                        }else{
                            var bookingpress_uniq_id = vm2.package_step_form_data.bookingpress_uniq_id;
                            if(response.data.variant != "error"){

                                //vm2.bookingpress_render_package_thankyou_content();
                                //vm2.bookingpress_package_remove_error_msg();
                                
                                if(response.data.variant == "redirect"){
                                    vm2.bookingpress_package_external_html = response.data.redirect_data;
                                    setTimeout(function(){

                                        var scripts = document.getElementsByClassName("bpa-external-script-package")[0].querySelectorAll("script");
                                        if(scripts.length > 0){
                                            var text = scripts[scripts.length - 1].textContent;
                                            eval(text);
                                        }
                                    },50);
                                    vm2.bookingpress_package_remove_error_msg();
                                }else if(response.data.variant == "redirect_url" && typeof response.data.is_transaction_completed != "undefined" && response.data.is_transaction_completed == "1"){
                                    vm2.bookingpress_package_remove_error_msg();
                                    vm2.bookingpress_render_package_thankyou_content();    
                                }else if(response.data.variant == "redirect_url" && typeof response.data.is_transaction_completed != "undefined" && response.data.is_transaction_completed == "0"){
                                    vm2.bookingpress_package_remove_error_msg();
                                    vm2.bookingpress_render_package_thankyou_content();
                                }else if(response.data.variant == "redirect_url" && typeof response.data.is_transaction_completed == "undefined"){
                                    vm2.bookingpress_package_remove_error_msg();
                                    window.location.href = response.data.redirect_data;
                                }else{
                                    vm2.appointment_step_form_data.is_transaction_completed = 1;
                                    vm2.bookingpress_render_package_thankyou_content();
                                }                                
                            }else{
                                vm2.appointment_step_form_data.is_transaction_completed = "";
                                vm2.bookingpress_package_set_error_msg(response.data.msg);
                            }
                        }
					}.bind(this) )
					.catch( function (error) {
                        vm.isPackageLoadBookingLoader = "0";
                        vm.isPackageBookingDisabled = false;                        
						vm.bookingpress_package_set_error_msg(error);
					});
				},1500);
			},
            bpp_display_basic_details_step(){
                const vm = this;
                vm.bookingpress_current_package_tab = "user_detail";
            },
            bookingpress_render_package_payment_error_content(){

                const vm = this;
                vm.bookingpress_current_package_tab = "summary";
                vm.package_payment_failed = "1";
                //vm.bookingpress_package_set_error_msg("Payment not done.");

            },
            reset_booking_form_before_thankyou(){
                const vm = this;
                vm.package_step_form_data.selected_payment_method = "";
                vm.is_display_card_option = 0;                
                vm.package_step_form_data.is_display_card_option = 0;
                vm.package_step_form_data.card_holder_name = "";
                vm.package_step_form_data.card_number = "";
                vm.package_step_form_data.expire_month = "";
                vm.package_step_form_data.expire_year = "";
                vm.package_step_form_data.cvv = "";

            },
            bookingpress_render_package_thankyou_content(){                                
                const vm = this;
                var bkp_wpnonce_pre = "' . $bookingpress_nonce . '";
                var bkp_wpnonce_pre_fetch = document.getElementById("_wpnonce");
                vm.isPackageLoadBookingLoader = "1";
                if(typeof bkp_wpnonce_pre_fetch=="undefined" || bkp_wpnonce_pre_fetch==null)
                {
                    bkp_wpnonce_pre_fetch = bkp_wpnonce_pre;
                }
                else {
                    bkp_wpnonce_pre_fetch = bkp_wpnonce_pre_fetch.value;
                }
                //vm.reset_booking_form_before_thankyou();
                var postData = { action:"bookingpress_package_render_thankyou_content", bookingpress_uniq_id: vm.package_step_form_data.bookingpress_uniq_id, _wpnonce:bkp_wpnonce_pre_fetch };
                axios.post( appoint_ajax_obj.ajax_url, Qs.stringify( postData ) )
                .then( function (response) {
                    if(response.data.variant == "success"){                        
                        app.paypal_button_loader = "false";                                                                         
                        vm.bookingpress_current_package_tab = "summary";
                        vm.bookingpress_booked_package_detail.bookingpress_package_no = response.data.thankyou_page_data.bookingpress_package_no;
                        vm.bookingpress_booked_package_detail.bookingpress_customer_name = response.data.thankyou_page_data.bookingpress_customer_name;
                        vm.bookingpress_booked_package_detail.bookingpress_package_name = response.data.thankyou_page_data.bookingpress_package_name;                        
                        if(response.data.thankyou_page_data.bookingpress_default_appointment_booking_page_url != ""){
                            vm.bookingpress_default_appointment_booking_page_url = response.data.thankyou_page_data.bookingpress_default_appointment_booking_page_url;
                        }
                        vm.isPackageLoadBookingLoader = "0";
                        //var bookingpress_appointment_id = response.data.appointment_id;
                        //document.getElementById("bpa-thankyou-screen-div").innerHTML = response.data.thankyou_content;
                        //document.getElementById("bpa-failed-screen-div").innerHTML = response.data.failed_content;                       

                    }
                }.bind(this) )
                .catch( function (error) {
                    vm.isPackageLoadBookingLoader = "0";
                    vm.bookingpress_package_set_error_msg(error);
                });

            },
            package_appointment_book_redirect(){
                const vm = this;
                window.location.href = vm.bookingpress_default_appointment_booking_page_url;
            },
			select_payment_method(payment_method){
				const vm = this;
				vm.package_step_form_data.selected_payment_method = payment_method;
				var bookingpress_allowed_payment_gateways_for_card_fields = [];
				' . $bookingpress_after_selecting_payment_method_data . ';
				if(bookingpress_allowed_payment_gateways_for_card_fields.includes(payment_method)){
					vm.is_display_card_option = 1;
				}else{
					vm.is_display_card_option = 0;
				}
			},            
            inputFormat() {
                let text = this.package_step_form_data.card_number.split(" ").join("");
                /* this.cardVdid is not formated in 4 spaces */
                this.cardVadid = text;
                if (text.length > 0) {
                    /* regExp 4 in 4 number add an space between */
                    text = text.match(new RegExp(/.{1,4}/, "g")).join(" ")
                                                    /* accept only numbers */
                        .replace(new RegExp(/[^\d]/, "ig"), " ");
                }
                /* this.package_step_form_data.card_number is formated on 4 spaces */
                this.package_step_form_data.card_number = text;
                /* after formatd they callback cardType for choose a type of the card */
                this.GetCardType(this.cardVadid);
            },    
            /* get the name of the card name  */
            /* loop for the next 9 years for expire data on credit card */
            expirationDate() {
                let yearNow = new Date().getFullYear();
                for (let i = yearNow; i < yearNow + this.timeToExpire; i++) {
                    this.years.push({ year: i });
                }
            },            
            GetCardType(number) {
                this.regx.forEach((item) => {
                    if (number.match(item.re) != null) {
                        this.cardType = item.logo;
                        /* cClass add a class with the name of cardName to manipulate with css */
                        this.cClass = item.name.toLowerCase();
                    } else if (!number) {
                        this.cardType = "";
                        this.cClass = "";
                    }
                });
                /* after choose a cardtype return the number for the luhn algorithm  */
                this.validCreditCard(number);
            },
            /* mouse down on btn */   
            validCreditCard(value) {
                let inputValidate = document.getElementById("cardNumber");
                 /* luhn algorithm */
                let numCheck = 0,
                    bEven = false;
                value = value.toString().replace(new RegExp(/\D/g, ""));
                for (let n = value.length - 1; n >= 0; n--) {
                    let cDigit = value.charAt(n),
                        digit = parseInt(cDigit, 10);

                    if (bEven && (digit *= 2) > 9) digit -= 9;
                    numCheck += digit;
                    bEven = !bEven;
                }
                let len = value.length;
                /* true: return valid number */
                /* this.cardType return true if have an valid number on regx array */
                
                if (numCheck % 10 === 0 && len === 16 && this.cardType) {
                    inputValidate.classList.remove("notValid");
                    inputValidate.classList.add("valid");
                    this.isBookingDisabled = false;
                }
                /* false: return not valid number */
                else if (!(numCheck % 10 === 0) && len === 16) {
                    inputValidate.classList.remove("valid");
                    inputValidate.classList.add("notValid");
                    this.isBookingDisabled = true;
                    /* if not have number on input */
                } else {
                    inputValidate.classList.remove("valid");
                    inputValidate.classList.remove("notValid");
                    this.isBookingDisabled = false;
                }
            }, 
            /* mouse down on btn */
            mouseDw() {
                this.btnClassName = "btn__active";
            },
            /* mouse up on btn */
            mouseUp() {
                this.btnClassName = "";
            },
            blr() {
                let cr = document.getElementsByClassName("card--credit__card")[0];
                if( null != cr && "undefined" != typeof cr.classList ){
                    cr.classList.remove("cvv-active")
                }
            },                                        
            package_user_basic_detail_submit(){
                const vm = this;
                var customer_form = "package_step_form_data";                
                vm.$refs[customer_form].validate((valid) => {
                    if(valid) {
                        vm.bookingpress_current_package_tab = "payment";
                        vm.bookingpress_front_get_package_final_step_amount();
                    }
                });                
            },
            bookingpress_front_get_package_final_step_amount(){
                const vm = this;

                /* for payment selection */
                setTimeout( function(){
                    var bpp_total_payment_div_count = document.querySelectorAll(".bpp-front-module--pm-body__item").length;
                    vm.bookingpress_activate_payment_gateway_total_counter = vm.bookingpress_activate_package_payment_gateway_counter;
                    
                    if( vm.paypal_payment == "true" ){
                        let total_counter_payment_gateway = vm.bookingpress_activate_payment_gateway_total_counter + 1;
                        vm.bookingpress_activate_payment_gateway_total_counter = total_counter_payment_gateway;
                    } 
                    if(bpp_total_payment_div_count == 1){
                        var bpp_total_payment_div = document.querySelector(".bpp-front-module--pm-body__item");
                        if( null != bpp_total_payment_div && "undefined" != typeof bpp_total_payment_div) {
                            vm.prevent_verification_on_load = true;
                            bpp_total_payment_div.click();
                            vm.prevent_verification_on_load = false;
                        }
                    }

                },100);
                /* for payment selection end */

                var payment_method = vm.package_step_form_data.selected_payment_method;                
                vm.package_step_form_data.package_price_without_currency = 0;
                var total_amount = 0;
                var subtotal_price = 0;

                let bookingpress_package_price = vm.package_step_form_data.bookingpress_selected_package_detail.bookingpress_package_price;
                vm.package_step_form_data.package_price_without_currency = parseFloat(bookingpress_package_price);
                subtotal_price = total_amount = parseFloat(bookingpress_package_price);

                if(typeof vm.package_step_form_data.tax_percentage_temp == "undefined"){
                    vm.package_step_form_data.tax_percentage_temp = vm.package_step_form_data.tax_percentage;                        
                }
                else {
                    vm.package_step_form_data.tax_percentage = vm.package_step_form_data.tax_percentage_temp;  
                }

				var tax_amount = 0;
				if(vm.is_tax_enable){	

                    /* Country Wise Tax Calcualtion related chanegs */
                    if(typeof vm.package_step_form_data.enable_country_wise_tax != "undefined" && vm.package_step_form_data.enable_country_wise_tax == "true"){
                        const field_meta_key = Object.keys(vm.package_step_form_data.bookingpress_front_field_data).find(key => vm.package_step_form_data.bookingpress_front_field_data[key] === vm.package_step_form_data.countryselectedField);
                        if(field_meta_key) {
                            const form_field_details = vm.package_step_form_data.form_fields;
                            const form_field_value = form_field_details[field_meta_key];
                            const taxPercentage = vm.package_step_form_data.country_wise_tax_details.find(item => item.selectedOption === form_field_value)?.bookingpress_country_wise_tax_per;
                            if(taxPercentage != "" && typeof taxPercentage != "undefined") {
                                vm.package_step_form_data.tax_percentage = parseFloat(taxPercentage);                                
                            }
                        }
                    }
                    /* Country Wise Tax Calcualtion related chanegs */

					if(vm.package_step_form_data.tax_percentage != ""){
						var tax_percentage = parseFloat(vm.package_step_form_data.tax_percentage);					
						if(vm.package_step_form_data.tax_price_display_options == "include_taxes"){
							tax_amount = (total_amount * tax_percentage) / (100+tax_percentage);
						}else{
							tax_amount = total_amount * ( tax_percentage / 100 );
							total_amount = total_amount + tax_amount;
						}
					}									
				}
                
				vm.package_step_form_data.tax = tax_amount; 
				vm.package_step_form_data.tax_with_currency = vm.bookingpress_price_with_currency_symbol( tax_amount );
				vm.package_step_form_data.total_amount_with_currency = vm.bookingpress_price_with_currency_symbol( total_amount );
        		vm.package_step_form_data.total_amount = parseFloat(total_amount);		

				vm.package_step_form_data.subtotal_with_currency = vm.bookingpress_price_with_currency_symbol( subtotal_price );                
        		vm.package_step_form_data.subtotal = subtotal_price;
                if(vm.is_tip_enable){
                    if(typeof vm.package_step_form_data.tip_amount !== "undefined"){
                        if(vm.package_step_form_data.tip_amount){
                            vm.package_step_form_data.tip_amount_with_currency = vm.bookingpress_price_with_currency_symbol( vm.package_step_form_data.tip_amount );
                            var tip_amount = parseFloat(vm.package_step_form_data.tip_amount);
                            total_amount = total_amount + tip_amount;
                            vm.package_step_form_data.total_amount_with_currency = vm.bookingpress_price_with_currency_symbol( total_amount );
                            vm.package_step_form_data.total_amount = total_amount;						
                        }
                    }                
                }                 
                vm.package_step_form_data.total_payable_amount = vm.package_step_form_data.total_amount;

                

                this.bookingpress_verify_total_payment_amount_package();

                '.$bookingpress_after_package_final_step_amount.'
                
                
            },
            bookingpress_verify_total_payment_amount_package_v2(){
                const vm = this;

                var bkp_wpnonce_pre = "' . $bookingpress_nonce . '";
                var bkp_wpnonce_pre_fetch = document.getElementById("_wpnonce");
                if(typeof bkp_wpnonce_pre_fetch=="undefined" || bkp_wpnonce_pre_fetch==null)
                {
                    bkp_wpnonce_pre_fetch = bkp_wpnonce_pre;
                }
                else {
                    bkp_wpnonce_pre_fetch = bkp_wpnonce_pre_fetch.value;
                }

                vm.bkp_processing_token = true;
                let bpk_payable_data = {
                    action: "bookingpress_pre_booking_verify_details",
                    booking_token: vm.package_step_form_data.bookingpress_uniq_id,
                    booking_data: JSON.stringify( vm.package_step_form_data ),
                    _wpnonce: bkp_wpnonce_pre_fetch
                };

                return axios.post( appoint_ajax_obj.ajax_url, Qs.stringify( bpk_payable_data ) )
                .then( function (response) {
                    if( "undefined" != typeof response.data.verification_token ){
                        vm.package_step_form_data.authorized_token = response.data.verification_token;
                        vm.package_step_form_data.authorized_time = response.data.verification_time;
                        vm.bkp_processing_token = false;
                    }
                }.bind(this) )
                .catch( function (error) {
                    
                });
            },            
            bookingpress_verify_total_payment_amount_package(){
                const vm = this;

                var bkp_wpnonce_pre = "' . $bookingpress_nonce . '";
                var bkp_wpnonce_pre_fetch = document.getElementById("_wpnonce");
                if(typeof bkp_wpnonce_pre_fetch=="undefined" || bkp_wpnonce_pre_fetch==null){
                    bkp_wpnonce_pre_fetch = bkp_wpnonce_pre;
                }else {
                    bkp_wpnonce_pre_fetch = bkp_wpnonce_pre_fetch.value;
                }
                vm.bkp_processing_token = true;
                let bpk_payable_data = {
                    action: "bookingpress_pre_booking_verify_details",
                    booking_token: vm.package_step_form_data.bookingpress_uniq_id,
                    booking_data: JSON.stringify( vm.package_step_form_data ),
                    _wpnonce: bkp_wpnonce_pre_fetch
                };
                axios.post( appoint_ajax_obj.ajax_url, Qs.stringify( bpk_payable_data ) )
                .then( function (response) {
                    if( "undefined" != typeof response.data.verification_token ){
                        vm.package_step_form_data.authorized_token = response.data.verification_token;
                        vm.package_step_form_data.authorized_time = response.data.verification_time;
                        vm.bkp_processing_token = false;
                    }
                }.bind(this) )
                .catch( function (error) {
                    
                });
            },            
            BPACustomerFileUpload(response, file, fileList){
                const vm = this;
                let ref = response.reference;
                if( response.error == 1 ){
                    vm.$refs[ ref ][0].$options.parent.validateMessage = response.msg;
                    vm.$refs[ ref ][0].$options.parent.validateState = "error";
                    vm.$refs[ ref ][0].clearFiles();	
                } else {
                    vm.$refs[ ref ][0].$options.parent.validateMessage = "";
                    vm.$refs[ ref ][0].$options.parent.validateState = "";
                    let upload_file_name = response.upload_file_name;
                    let upload_url = response.upload_url;
                    vm.package_step_form_data[ response.file_ref ] = upload_url;
                    vm.package_step_form_data.form_fields[ response.file_ref ] = upload_url;
                }
            },
            BPACustomerFileUploadError(err, file, fileList){
                /** Need to handle error but currently no error is reaching to this function */
                if( file.status == "fail" ){
                    console.log( err );
                }
            },
            BPACustomerFileUploadRemove( file, fileList ){
                const vm = this;
                let response = file.response;
                vm.package_step_form_data[ response.file_ref ] = "";
                vm.package_step_form_data.form_fields[ response.file_ref ] = "";

                let postData = {
                    action:"bpa_remove_form_file",
                    _wpnonce: "'.wp_create_nonce( 'bpa_wp_nonce' ).'",
                    uploaded_file_name: response.upload_file_name
                };
                axios.post( appoint_ajax_obj.ajax_url, Qs.stringify( postData ) )
                .then( function( response ){
                }).catch( function( error ){
                });
            },
            BPAConvertBytesToMB( bytes){
                return (bytes / (1024 * 1024)).toFixed(0);
            },            
            bookingpress_get_parents( elem, selector ){
                if (!Element.prototype.matches) {
                    Element.prototype.matches = Element.prototype.matchesSelector ||
                        Element.prototype.mozMatchesSelector ||
                        Element.prototype.msMatchesSelector ||
                        Element.prototype.oMatchesSelector ||
                        Element.prototype.webkitMatchesSelector ||
                        function(s) {
                            var matches = (this.document || this.ownerDocument).querySelectorAll(s),
                                i = matches.length;
                            while (--i >= 0 && matches.item(i) !== this) {}
                            return i > -1;
                        };
                }            
                var parents = [];            
                for (; elem && elem !== document; elem = elem.parentNode) {
                    if (selector) {
                        if (elem.matches(selector)) {
                            parents.push(elem);
                        }
                        continue;
                    }
                    parents.push(elem);
                }            
                return parents;
            },            
            bookingpress_phone_country_open( vmodel ){
                const vm = this;
                if( "" != vmodel ){
                    let elm = document.querySelector(`div[data-tel-id="${vmodel}"]`);
                    if( null != elm ){
                        let parent = vm.bookingpress_get_parents( elm, ".el-col" );
                        if( 0 < parent.length && null != parent[0] ){
                            parent[0].classList.add("bpa-active-col");
                        }
                    }
                }
            },
            bookingpress_phone_country_close( vmodel ){
                const vm = this;
                if( "" != vmodel ){
                    let elm = document.querySelector(`div[data-tel-id="${vmodel}"]`);
                    if( null != elm ){
                        let parent = vm.bookingpress_get_parents( elm, ".bpa-active-col" );
                        if( 0 < parent.length && null != parent[0] ){
                            parent[0].classList.remove("bpa-active-col");
                        }
                    }
                }
            },            
            bookingpress_selectpicker_set_position( flag ){
                if( true == flag ){	
                    let is_admin_bar_visible = ( document.getElementById("wpadminbar") != null && document.getElementById("wpadminbar").getBoundingClientRect().width > 0 && document.getElementById("wpadminbar").getBoundingClientRect().height > 0 ) ? true : false;
                    if( document.querySelector(".bpp-focused-select") != null &&  is_admin_bar_visible ) {
                        setTimeout(function(){
                            let top_pos = document.querySelector(".bpp-focused-select").style.top;
                            top_pos = parseInt( top_pos.replace("px","") );
                            document.querySelector(".bpp-focused-select").style.top = ( top_pos + 32 ) + "px";
                        },10);
                    }
                }
            },
            bookingpress_set_datepicker_position( event ){
                let popperElm = document.querySelector(".bpp-custom-datepicker");
                if( popperElm != null ){
                    let is_admin_bar_visible = ( document.getElementById("wpadminbar") != null && document.getElementById("wpadminbar").getBoundingClientRect().width > 0 && document.getElementById("wpadminbar").getBoundingClientRect().height > 0 ) ? true : false;
                    if( is_admin_bar_visible ){
                        setTimeout(function(){
                            let top_pos = popperElm.style.top;
                            top_pos = parseInt( top_pos.replace("px","") );
                            popperElm.style.top = ( top_pos + 32 ) + "px";
                        },10);
                    }
                }
            },            
			bookingpress_phone_country_change_func(bookingpress_country_obj){
				const vm = this;
                var bookingpress_selected_country = bookingpress_country_obj.iso2;
				vm.package_step_form_data.customer_phone_country = bookingpress_selected_country;
                vm.package_step_form_data.customer_phone_dial_code = bookingpress_country_obj.dialCode;
                let exampleNumber = window.intlTelInputUtils.getExampleNumber( bookingpress_selected_country, true, 1 );                                
                if( typeof vm.bookingpress_phone_default_placeholder == "undefined" &&  "" != exampleNumber ){
                    vm.bookingpress_package_tel_input_props.inputOptions.placeholder = exampleNumber;
                } else if(vm.bookingpress_phone_default_placeholder == "false" && "" != exampleNumber){
                    vm.bookingpress_package_tel_input_props.inputOptions.placeholder = exampleNumber;
                }
			},            
            bookingpress_selectpicker_set_position( flag ){
                if( true == flag ){	
                    let is_admin_bar_visible = ( document.getElementById("wpadminbar") != null && document.getElementById("wpadminbar").getBoundingClientRect().width > 0 && document.getElementById("wpadminbar").getBoundingClientRect().height > 0 ) ? true : false;
                    if( document.querySelector(".bpa-focused-select") != null &&  is_admin_bar_visible ) {
                        setTimeout(function(){
                            let top_pos = document.querySelector(".bpa-focused-select").style.top;
                            top_pos = parseInt( top_pos.replace("px","") );
                            document.querySelector(".bpa-focused-select").style.top = ( top_pos + 32 ) + "px";
                        },10);
                    }
                }
            },
            bookingpress_package_reset_error_success_msg(){
                const vm = this;
                vm.is_display_error = "0";
                vm.is_error_msg = "";
                vm.is_display_success = "0";
                vm.is_success_msg = "";
            },
			bookingpress_package_remove_error_msg(){
				const vm = this;
				vm.is_display_error = "0";
				vm.is_error_msg = "";
			},                        
            bookingpress_package_set_error_msg(error_msg){
                const vm = this;                
                vm.is_display_error = "1";
                vm.is_error_msg = error_msg;
                let pos = 0;
                let container = vm.$el;
                if( null != container ){
                    pos = container.getBoundingClientRect().top + window.scrollY;
                }                 
                const popupDiv = document.querySelector(".bpp-package-buy-now-popup-right-section");
                popupDiv.scrollTo({
                    top: 0,
                    behavior: "smooth",
                });                
                setTimeout(function(){
                    vm.bookingpress_package_reset_error_success_msg();
                },3000);
            },
            bookingpress_package_set_success_msg(success_msg){
                const vm = this;
                vm.bookingpress_package_reset_error_success_msg();
                vm.is_display_success = "1";
                vm.is_success_msg = success_msg;
                const popupDiv = document.querySelector(".bpp-package-buy-now-popup-right-section");
                popupDiv.scrollTo({
                    top: 0,
                    behavior: "smooth",
                }); 
            },        
            bookingpress_package_customer_signup(){                
                const vm = this;
                vm.$refs["bookingpress_package_reg_frm"].validate((valid) => {                    
                    vm.bookingpress_package_reg_loader = "1";
                    var bkp_wpnonce_pre = "'.esc_html( $bookingpress_nonce ).'";
                    var bkp_wpnonce_pre_fetch = document.getElementById("_wpnonce");
                    if(typeof bkp_wpnonce_pre_fetch=="undefined" || bkp_wpnonce_pre_fetch==null){
                        bkp_wpnonce_pre_fetch = bkp_wpnonce_pre;
                    }else {
                        bkp_wpnonce_pre_fetch = bkp_wpnonce_pre_fetch.value;
                    }

                    var regFormData = { action:"bookingpress_package_register_customer_account",form_fields:vm.package_step_form_data.form_fields,bookingpress_customer_timezone:vm.bookingpress_package_reg_form.bookingpress_customer_timezone,customer_phone_dial_code:vm.bookingpress_package_reg_form.customer_phone_dial_code,customer_phone_country:vm.bookingpress_package_reg_form.customer_phone_country,full_name: vm.bookingpress_package_reg_form.bookingpress_customer_full_name, login_email_address: vm.bookingpress_package_reg_form.bookingpress_reg_email, login_password: vm.bookingpress_package_reg_form.bookingpress_reg_pass, customer_phone: vm.bookingpress_package_reg_form.customer_phone, _wpnonce:bkp_wpnonce_pre_fetch };
                    axios.post( appoint_ajax_obj.ajax_url, Qs.stringify( regFormData ) )
                    .then( function (response) {								
                        vm.bookingpress_package_reg_loader = "0";
                        if(response.data.variant == "error"){
                            vm.bookingpress_package_set_error_msg(response.data.msg);
                        }else{

                            vm.package_step_form_data.bookingpress_customer_id = response.data.bookingpress_customer_id;
                            vm.package_step_form_data.form_fields = response.data.bookingpress_form_fields;
                            vm.bookingpress_package_login_user_id = response.data.login_user_id;
                            document.getElementById("_wpnonce").value = response.data.new_nonce;
                            if(response.data.bookingpress_form_fields){
                                vm.form_fields_org = JSON.stringify(response.data.bookingpress_form_fields);
                            }                            

                            var post_data = { action:"bookingpress_generate_package_token", _wpnonce:response.data.new_nonce};
                            axios.post( appoint_ajax_obj.ajax_url, Qs.stringify( post_data ) ).then( function (response) {    
                                if( "undefined" != typeof response.data.captcha_val ){
                                    vm.package_step_form_data.package_token = response.data.captcha_val;
                                }
                            }.bind(this) )
                            .catch( function (error) {     
                                
                            });

                        }
                    }.bind(this) )
                    .catch( function (error) {                    
                        console.log(error);
                    });
                });                
            },
            bookingpress_package_forgot_password(){
                const vm = this;
                vm.$refs["bookingpress_package_forgot_password_frm"].validate((valid) => {
                    if(valid){
                        vm.bookingpress_package_forgotpassword_loader = "1";
                        var bkp_wpnonce_pre = "'.esc_html( $bookingpress_nonce ).'";
                        var bkp_wpnonce_pre_fetch = document.getElementById("_wpnonce");
                        if(typeof bkp_wpnonce_pre_fetch=="undefined" || bkp_wpnonce_pre_fetch==null){
                            bkp_wpnonce_pre_fetch = bkp_wpnonce_pre;
                        }else {
                            bkp_wpnonce_pre_fetch = bkp_wpnonce_pre_fetch.value;
                        }
                        var forgotPassFormData = { action:"bookingpress_package_forgot_password_account", forgot_pass_email_address: vm.bookingpress_package_forgot_password_form.bookingpress_forgot_password_email, _wpnonce:bkp_wpnonce_pre_fetch };
                        axios.post( appoint_ajax_obj.ajax_url, Qs.stringify( forgotPassFormData ) )
                        .then( function (response) {
                            vm.bookingpress_package_forgotpassword_loader = "0";
                            if(response.data.variant == "error"){
                                vm.bookingpress_package_set_error_msg(response.data.msg);
                            }else{
                                vm.bookingpress_package_set_success_msg(response.data.msg);
                            }
                        }.bind(this) )
                        .catch( function (error) { 
                            vm.bookingpress_package_forgotpassword_loader = "0";                   
                            console.log(error);
                        });
                    }
                });                
            },
            bookingpress_package_customer_login(){
                const vm = this;
                vm.$refs["bookingpress_package_login_frm"].validate((valid) => {
                    if(valid){
                        vm.bookingpress_package_login_loader = "1";
                        var bkp_wpnonce_pre = "'.esc_html( $bookingpress_nonce ).'";
                        var bkp_wpnonce_pre_fetch = document.getElementById("_wpnonce");
                        if(typeof bkp_wpnonce_pre_fetch=="undefined" || bkp_wpnonce_pre_fetch==null){
                            bkp_wpnonce_pre_fetch = bkp_wpnonce_pre;
                        }else {
                            bkp_wpnonce_pre_fetch = bkp_wpnonce_pre_fetch.value;
                        }
                        var loginFormData = { action:"bookingpress_package_login_customer_account",form_fields:vm.package_step_form_data.form_fields,login_email_address: vm.bookingpress_package_login_form.bookingpress_login_email, login_password: vm.bookingpress_package_login_form.bookingpress_login_pass, is_remember: vm.bookingpress_package_login_form.bookingpress_is_remember, _wpnonce:bkp_wpnonce_pre_fetch };
                        axios.post( appoint_ajax_obj.ajax_url, Qs.stringify( loginFormData ) )
                        .then( function (response) {								
                            vm.bookingpress_package_login_loader = "0";
                            if(response.data.variant == "error"){
                                vm.bookingpress_package_set_error_msg(response.data.msg);
                            }else{
                                vm.package_step_form_data.bookingpress_customer_id = response.data.bookingpress_customer_id;
                                vm.package_step_form_data.form_fields = response.data.bookingpress_form_fields;
                                if(response.data.bookingpress_form_fields){
                                    vm.form_fields_org = JSON.stringify(response.data.bookingpress_form_fields);
                                }                                
                                vm.bookingpress_package_login_user_id = response.data.login_user_id;
                                document.getElementById("_wpnonce").value = response.data.new_nonce;

                                var post_data = { action:"bookingpress_generate_package_token", _wpnonce:response.data.new_nonce};
                                axios.post( appoint_ajax_obj.ajax_url, Qs.stringify( post_data ) ).then( function (response) {    
                                    if( "undefined" != typeof response.data.captcha_val ){
                                        vm.package_step_form_data.package_token = response.data.captcha_val;
                                    }
                                }.bind(this) )
                                .catch( function (error) {     
                                    
                                });

                            }
                        }.bind(this) )
                        .catch( function (error) {                    
                            console.log(error);
                        });			
                    }
                });                
            },   

                packageGoToForm(form_name){
                    const vm = this;                    
                    if(vm.bookingpress_package_is_active_form == "login"){                                                 
                        vm.$refs["bookingpress_package_login_frm"].resetFields();                                                
                    }else if(vm.bookingpress_package_is_active_form == "signup"){
                        vm.$refs["bookingpress_package_reg_frm"].resetFields();
                    }else if(vm.bookingpress_package_is_active_form == "forgotpassword"){
                        vm.$refs["bookingpress_package_forgot_password_frm"].resetFields();
                    }                    
                    vm.bookingpress_package_is_active_form = form_name;   
                },
                applyPackagesFilter(){
                    const vm = this;                  
                    bookingpress_search_package_name = vm.bookingpress_package_filter.package_name;
                    //if(bookingpress_search_package_name != ""){  
                        vm.currentPage = 1;                      
                        vm.getPackageList();
                    //}
                },
                resetPackageFilter(){
                    const vm = this;
                    vm.bookingpress_package_filter.package_name = "";
                    vm.getPackageList();
                }, 
                getPackageList(is_display_loader = 0){
                    const vm = this;
                    vm.bookingpress_package_filter_apply = true;                        
                    if(is_display_loader == 1 ){
                        vm.is_package_booking_pakage_booking_loader = "1";
                    }
                    var bkp_wpnonce_pre = vm.bookingpress_created_nonce;
                    var bkp_wpnonce_pre_fetch = document.getElementById("_wpnonce");
                    if(typeof bkp_wpnonce_pre_fetch=="undefined" || bkp_wpnonce_pre_fetch==null){
                        bkp_wpnonce_pre_fetch = bkp_wpnonce_pre;
                    }else {
                        bkp_wpnonce_pre_fetch = bkp_wpnonce_pre_fetch.value;
                    }                    
                    var postData = { action:"bookingpress_get_front_packages", perpage:this.per_page, currentpage: this.currentPage, search_data: vm.bookingpress_package_filter,_wpnonce:bkp_wpnonce_pre_fetch};
                    postData.default_package = vm.default_package;
                    axios.post( appoint_ajax_obj.ajax_url, Qs.stringify( postData ) ).then( function (response) {
                        
                        vm.bookingpress_package_filter_apply = false;                        
                        this.bookingpress_all_packages_data = response.data.package_list;
                        this.total_records = parseInt(response.data.total_records);                    
                        this.is_display_pagination = 0;
                        if( is_display_loader == 1){
                            setTimeout(function(){
                                vm.is_package_booking_pakage_booking_loader = "0";
                            },600);                            
                        }
                        if(this.total_records > 6) {
                            this.is_display_pagination = 1;
                        }


                    }.bind(this) )
                    .catch( function (error) {     
                        vm.disable_my_appointments_apply = false;                                       
                        vm.$notify({
                            title: "'.esc_html__('Error', 'bookingpress-package').'",
                            message: "'.esc_html__('Something went wrong..', 'bookingpress-package').'",
                            type: "error",
                            customClass: "error_notification",
                        });
                    });

                },
                bookingpress_toggle_package_description( package_key, is_show ){
                    const vm = this;

                    let package_data = vm.bookingpress_all_packages_data;
                    let current_package_data = package_data[ package_key ];
                    
                    if( true == is_show ){
                        current_package_data.bookingpress_package_desc_show_less = true;
                        current_package_data.bookingpress_package_desc_show_more_active = false;
                    } else {
                        current_package_data.bookingpress_package_desc_show_less = false;
                        current_package_data.bookingpress_package_desc_show_more_active = true;
                    }
                },
                bookingpress_open_package_booking_popup(package_data){
                    const vm = this;

                    var bkp_wpnonce_pre = vm.bookingpress_created_nonce;
                    var bkp_wpnonce_pre_fetch = document.getElementById("_wpnonce");
                    if( typeof bkp_wpnonce_pre_fetch == "undefined" || bkp_wpnonce_pre_fetch == null ){
                        bkp_wpnonce_pre_fetch = bkp_wpnonce_pre;
                    } else {
                        bkp_wpnonce_pre_fetch = bkp_wpnonce_pre_fetch.value;
                    }

                    var post_data = { action:"bookingpress_generate_package_token", _wpnonce:bkp_wpnonce_pre_fetch};

                    vm.package_step_form_data.bookingpress_selected_package_id = package_data.bookingpress_package_id;
                    vm.package_step_form_data.bookingpress_selected_package_detail = package_data;
                    app.package_step_form_data.bookingpress_selected_package_id = package_data.bookingpress_package_id;
                    app.package_step_form_data.bookingpress_selected_package_detail = package_data;
                    vm.bookingpress_reset_package_booking_popup(); 
                    if(vm.form_fields_org == ""){           
                        if(vm.package_step_form_data.form_fields != ""){
                            vm.form_fields_org = JSON.stringify(vm.package_step_form_data.form_fields);
                        }
                    }else{                                                
                        vm.package_step_form_data.form_fields = JSON.parse(vm.form_fields_org);
                    }
                    //vm.package_step_form_data.form_fields = vm.form_fields_org;
                    vm.open_package_booking_modal = true;

                    axios.post( appoint_ajax_obj.ajax_url, Qs.stringify( post_data ) ).then( function (response) {
                        if( "undefined" != typeof response.data.captcha_val ){
                            vm.package_step_form_data.package_token = response.data.captcha_val;
                            vm.$forceUpdate();
                            app.package_step_form_data.package_token = response.data.captcha_val;
                        }
                    }.bind(this) )
                    .catch( function (error) {     
                        
                    });

                },
                bookingpress_reset_package_booking_popup(){
                    const vm = this;

                    vm.package_step_form_data.selected_payment_method = "";
                    vm.is_display_card_option = 0;
                    vm.bookingpress_current_package_tab = vm.bookingpress_current_package_tab_org;
                    vm.bookingpress_package_sidebar_step_data = vm.bookingpress_package_sidebar_step_data_org;
                    vm.package_step_form_data.is_display_card_option = 0;
                    vm.package_step_form_data.card_holder_name = "";
                    vm.package_step_form_data.card_number = "";
                    vm.package_step_form_data.expire_month = "";
                    vm.package_step_form_data.expire_year = "";
                    vm.package_step_form_data.cvv = "";         
                    vm.bookingpress_default_appointment_booking_page_url = vm.bookingpress_default_appointment_booking_page_url_org;
                              
                    /*
                        vm.bookingpress_booked_package_detail.bookingpress_package_no = "";
                        vm.bookingpress_booked_package_detail.bookingpress_customer_name = "";
                        vm.bookingpress_booked_package_detail.bookingpress_package_name = "";
                        vm.bookingpress_booked_package_detail.bookingpress_package_sidebar_step_data = vm.bookingpress_booked_package_detail.bookingpress_package_sidebar_step_data_org;
                        vm.package_step_form_data.package_price_without_currency = 0;
                        vm.package_step_form_data.bookingpress_selected_package_id = "";
                        vm.package_step_form_data.bookingpress_selected_package_detail = [];
                        vm.package_step_form_data.selected_payment_method = "";
                        vm.package_step_form_data.is_display_card_option = 0;
                        vm.isPackageLoadBookingLoader = "0";
                        vm.isPackageBookingDisabled = false;
                        vm.package_step_form_data.card_holder_name = "";
                        vm.package_step_form_data.card_number = "";
                        vm.package_step_form_data.expire_month = "";
                        vm.package_step_form_data.expire_year = "";
                        vm.package_step_form_data.cvv = "";
                        vm.package_step_form_data.subtotal = 0;
                        vm.package_step_form_data.subtotal_with_currency = "";
                        vm.package_step_form_data.total_amount = 0;
                        vm.package_step_form_data.total_payable_amount = 0;
                        vm.package_step_form_data.total_amount_with_currency = "";
                        vm.bookingpress_package_login_form = vm.bookingpress_package_login_form_org;
                        vm.bookingpress_package_reg_form = vm.bookingpress_package_reg_form_org;
                        vm.bookingpress_package_forgot_password_form = vm.bookingpress_package_forgot_password_form_org;
                        vm.package_step_form_data.form_fields = vm.form_fields_org;
                    */
                    '.$bookingpress_reset_package_order_popup_data.'
                },
                bookingpress_close_package_booking_popup(){
                    const vm = this;
                    vm.open_package_booking_modal = false;
                    vm.bookingpress_reset_package_booking_popup();
                    vm.package_payment_failed = "0";
                },
                bookingpress_customer_package_login(){
                    const vm = this;
					
                },   
                bookingpress_load_more_package_services(package_id){
					const vm = this;
                    if(vm.bookingpress_all_packages_data[package_id].package_services_expanded == "true") {
                        vm.bookingpress_all_packages_data[package_id].package_services_expanded = "false";
                    }
                    else {
                        vm.bookingpress_all_packages_data[package_id].package_services_expanded = "true";
                    }
				},                          
            ';

            return $bookingpress_package_front_booking_dynamic_vue_methods;
        }


        /**
         * Function for add new my packages tab in my booking
         *
         * @return void
        */
        function bookingpress_my_booking_my_appointment_tab_after_func(){
            $bookingpress_shortcode_file_url = BOOKINGPRESS_PACKAGE_VIEWS_DIR . '/frontend/package_my_booking.php';            
            include $bookingpress_shortcode_file_url;
        }
        
        /**
         * Function for add mobile my package tab
         *
         * @return void
         */
        function bookingpress_my_booking_mobile_my_appointment_after_func(){
        ?>
        <el-dropdown-item class="bpa-tn__dropdown-item bpa__di-edit-profile-item">
            <a href="javascript:void(0)" class="bpa-tm__item" :class="(bookingpress_my_booking_current_tab == 'my_package') ? ' __bpa-is-active' : ''" @click="bookingpress_activate_myboooking_tab('my_package')">
                <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M7.98832 9.995H2.14277C1.6796 9.995 1.30061 10.3739 1.30061 10.8371V17.1579C1.30061 17.6211 1.6796 18 2.14277 18H8.08661C8.28473 18 8.28473 17.7872 8.28473 17.7872V10.2812C8.28467 10.2812 8.28467 9.995 7.98832 9.995ZM15.849 9.995H10.0094C9.65375 9.995 9.707 10.3654 9.707 10.3654V17.794C9.707 17.794 9.70412 17.9998 9.91806 17.9998H15.8489C16.3121 17.9998 16.6911 17.6209 16.6911 17.1577V10.8371C16.6912 10.3739 16.3122 9.995 15.849 9.995ZM8.28467 5.02167C8.28467 5.02167 8.28467 4.73631 8.00268 4.73631H1.19177C0.7286 4.73631 0.349609 5.1153 0.349609 5.57841V8.20717C0.349609 8.67034 0.7286 9.04927 1.19177 9.04927H8.02575C8.28467 9.04927 8.28467 8.82471 8.28467 8.82471V5.02167ZM16.8 4.73631H9.96733C9.70713 4.73631 9.70713 4.98425 9.70713 4.98425V8.82992C9.70713 8.82992 9.70713 9.04927 10.0235 9.04927H16.8C17.2631 9.04927 17.6421 8.67034 17.6421 8.20717V5.57841C17.6421 5.1153 17.2631 4.73631 16.8 4.73631ZM5.4203 4.11325C5.03499 4.11325 4.68306 4.08269 4.37444 4.02238C3.59057 3.86924 3.05181 3.57118 2.72737 3.11126C2.43667 2.69907 2.3477 2.19093 2.46286 1.60088C2.6646 0.568517 3.35791 0 4.41494 0C4.63864 0 4.88431 0.0258305 5.14519 0.0768166C5.8088 0.20646 6.65759 0.586985 7.41576 1.0947C8.70207 1.95619 8.76569 2.49175 8.70164 2.8197C8.60746 3.30158 8.15705 3.64591 7.32464 3.87243C6.76226 4.02545 6.06815 4.11325 5.4203 4.11325ZM4.415 1.34975C4.00564 1.34975 3.86495 1.46412 3.78771 1.85967C3.72451 2.18308 3.80421 2.29603 3.83035 2.33315C3.9398 2.48838 4.22492 2.61784 4.63324 2.69754C4.85357 2.74061 5.1258 2.76337 5.42024 2.76337C6.06772 2.76337 6.63814 2.66527 7.01664 2.55796C7.04419 2.55016 7.08696 2.51759 7.04112 2.49028C6.54629 2.08718 5.641 1.54891 4.88639 1.40147C4.71061 1.36724 4.55195 1.34975 4.415 1.34975ZM12.5909 4.11325H12.5908C11.943 4.11325 11.2489 4.02545 10.6865 3.87243C9.85407 3.64597 9.40372 3.30158 9.30954 2.81976C9.24555 2.49182 9.30905 1.95625 10.5955 1.09476C11.3535 0.587046 12.2023 0.206521 12.8661 0.076878C13.1269 0.0258919 13.3726 6.13551e-05 13.5961 6.13551e-05C14.6534 6.13551e-05 15.3466 0.568639 15.5482 1.601C15.6635 2.19099 15.5746 2.69913 15.2838 3.11132C14.9594 3.5713 14.4207 3.8693 13.6366 4.02244C13.3281 4.08263 12.9762 4.11325 12.5909 4.11325ZM10.9809 2.48194C10.937 2.5074 10.9582 2.54759 10.981 2.55415C11.3593 2.66294 11.9357 2.76344 12.5908 2.76344C12.8854 2.76344 13.1575 2.74067 13.3779 2.6976C13.7861 2.61784 14.0714 2.48844 14.1808 2.33321C14.207 2.29609 14.2868 2.18314 14.2234 1.85974C14.1462 1.46418 14.0055 1.34981 13.5961 1.34981C13.4592 1.34981 13.3006 1.36724 13.1247 1.4016C12.3701 1.54897 11.4757 2.07877 10.9809 2.48194Z"/>
                </svg>                 
                <?php 
                    global $BookingPress;
                    $bp_my_package_title = $BookingPress->bookingpress_get_customize_settings('my_package_tab_title', 'booking_my_booking');  
                    echo esc_html(stripslashes_deep($bp_my_package_title));
                ?>
            </a>
        </el-dropdown-item>            
        <?php 
        }

        /**
         * Function for add my booking new my packages tab
         *
         * @return void
         */
        function bookingpress_my_booking_my_appointment_after_func(){
        ?>
        <a href="javascript:void(0)" class="bpa-tm__item" :class="(bookingpress_my_booking_current_tab == 'my_package') ? ' __bpa-is-active' : ''" @click="bookingpress_activate_myboooking_tab('my_package')">						
            <div class="bpa-tm__item-icon bpp-my-packages-tab">
                <svg class="bpp-my-packages-icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M7.98832 9.995H2.14277C1.6796 9.995 1.30061 10.3739 1.30061 10.8371V17.1579C1.30061 17.6211 1.6796 18 2.14277 18H8.08661C8.28473 18 8.28473 17.7872 8.28473 17.7872V10.2812C8.28467 10.2812 8.28467 9.995 7.98832 9.995ZM15.849 9.995H10.0094C9.65375 9.995 9.707 10.3654 9.707 10.3654V17.794C9.707 17.794 9.70412 17.9998 9.91806 17.9998H15.8489C16.3121 17.9998 16.6911 17.6209 16.6911 17.1577V10.8371C16.6912 10.3739 16.3122 9.995 15.849 9.995ZM8.28467 5.02167C8.28467 5.02167 8.28467 4.73631 8.00268 4.73631H1.19177C0.7286 4.73631 0.349609 5.1153 0.349609 5.57841V8.20717C0.349609 8.67034 0.7286 9.04927 1.19177 9.04927H8.02575C8.28467 9.04927 8.28467 8.82471 8.28467 8.82471V5.02167ZM16.8 4.73631H9.96733C9.70713 4.73631 9.70713 4.98425 9.70713 4.98425V8.82992C9.70713 8.82992 9.70713 9.04927 10.0235 9.04927H16.8C17.2631 9.04927 17.6421 8.67034 17.6421 8.20717V5.57841C17.6421 5.1153 17.2631 4.73631 16.8 4.73631ZM5.4203 4.11325C5.03499 4.11325 4.68306 4.08269 4.37444 4.02238C3.59057 3.86924 3.05181 3.57118 2.72737 3.11126C2.43667 2.69907 2.3477 2.19093 2.46286 1.60088C2.6646 0.568517 3.35791 0 4.41494 0C4.63864 0 4.88431 0.0258305 5.14519 0.0768166C5.8088 0.20646 6.65759 0.586985 7.41576 1.0947C8.70207 1.95619 8.76569 2.49175 8.70164 2.8197C8.60746 3.30158 8.15705 3.64591 7.32464 3.87243C6.76226 4.02545 6.06815 4.11325 5.4203 4.11325ZM4.415 1.34975C4.00564 1.34975 3.86495 1.46412 3.78771 1.85967C3.72451 2.18308 3.80421 2.29603 3.83035 2.33315C3.9398 2.48838 4.22492 2.61784 4.63324 2.69754C4.85357 2.74061 5.1258 2.76337 5.42024 2.76337C6.06772 2.76337 6.63814 2.66527 7.01664 2.55796C7.04419 2.55016 7.08696 2.51759 7.04112 2.49028C6.54629 2.08718 5.641 1.54891 4.88639 1.40147C4.71061 1.36724 4.55195 1.34975 4.415 1.34975ZM12.5909 4.11325H12.5908C11.943 4.11325 11.2489 4.02545 10.6865 3.87243C9.85407 3.64597 9.40372 3.30158 9.30954 2.81976C9.24555 2.49182 9.30905 1.95625 10.5955 1.09476C11.3535 0.587046 12.2023 0.206521 12.8661 0.076878C13.1269 0.0258919 13.3726 6.13551e-05 13.5961 6.13551e-05C14.6534 6.13551e-05 15.3466 0.568639 15.5482 1.601C15.6635 2.19099 15.5746 2.69913 15.2838 3.11132C14.9594 3.5713 14.4207 3.8693 13.6366 4.02244C13.3281 4.08263 12.9762 4.11325 12.5909 4.11325ZM10.9809 2.48194C10.937 2.5074 10.9582 2.54759 10.981 2.55415C11.3593 2.66294 11.9357 2.76344 12.5908 2.76344C12.8854 2.76344 13.1575 2.74067 13.3779 2.6976C13.7861 2.61784 14.0714 2.48844 14.1808 2.33321C14.207 2.29609 14.2868 2.18314 14.2234 1.85974C14.1462 1.46418 14.0055 1.34981 13.5961 1.34981C13.4592 1.34981 13.3006 1.36724 13.1247 1.4016C12.3701 1.54897 11.4757 2.07877 10.9809 2.48194Z"/>
                </svg> 
            </div>
            <?php 
                global $BookingPress;
                $bp_my_package_title = $BookingPress->bookingpress_get_customize_settings('my_package_tab_title', 'booking_my_booking');  
                echo esc_html(stripslashes_deep($bp_my_package_title));
            ?>
        </a>     
        <?php 
        }

        function bookingpress_check_front_page_or_not_func($bookingpress_check_front_page){
            global $wp, $wpdb, $wp_query, $post,$BookingPress;
            if (! is_admin() ) {
                $found_matches = array();
                $pattern       = '\[(\[?)(bookingpress_pakage_(.*?))(?![\w-])([^\]\/]*(?:\/(?!\])[^\]\/]*)*?)(?:(\/)\]|\](?:([^\[]*+(?:\[(?!\/\2\])[^\[]*+)*+)\[\/\2\])?)(\]?)';
                $posts         = $wp_query->posts;                
                if (is_array($posts) ) {
                    foreach ( $posts as $mypost ) {
                        if (preg_match_all('/' . $pattern . '/s', $mypost->post_content, $matches) > 0 ) {
                            $found_matches[] = $matches;
                        }
                    }
                }
                $found_matches = $BookingPress->bpa_array_trim($found_matches);
                if (! empty($found_matches) && count($found_matches) > 0 ) {
                    $bookingpress_check_front_page = false;
                }                              
            }
            return $bookingpress_check_front_page;
        }


        /**
         * Hook for add data variables for Package Booking Form shortcode
         *
         * @param  mixed $bookingpress_dynamic_data_fields      Global data variable for Booking Form
         * @param  mixed $bookingpress_category                 Shortcode allowed category
         * @param  mixed $bookingpress_service                  Shortcode allowed service
         * @param  mixed $selected_service                      Shortcode default selected service
         * @param  mixed $selected_category                     Shortcode default selected category
         * @return void
         */
        function bookingpress_package_front_booking_dynamic_data_fields_func( $bookingpress_dynamic_data_fields, $bookingpress_form_package, $bookingpress_package ){

            global $wpdb, $BookingPress, $bookingpress_front_vue_data_fields, $tbl_bookingpress_customers, $tbl_bookingpress_services, $tbl_bookingpress_form_fields, $bookingpress_global_options,$tbl_bookingpress_customers_meta, $bookingpress_tax;                        
            
            $bookingpress_front_vue_data_fields['package_step_form_data']['bookingpress_form_token'] = uniqid();

            $bookingpress_front_vue_data_fields['bookingpress_package_filter']['package_name'] = '';
            $bookingpress_front_vue_data_fields['bookingpress_package_filter']['is_search'] = 'no';
            $bookingpress_front_vue_data_fields['bookingpress_package_filter_apply'] = false;
            $bookingpress_front_vue_data_fields['bookingpress_activate_package_payment_gateway_counter'] = 0;
            $bookingpress_front_vue_data_fields['bookingpress_activate_payment_gateway_total_counter'] = 0;

            $bookingpress_front_vue_data_fields['per_page'] = 6;
            $bookingpress_front_vue_data_fields['pagination_length'] = 10;
            $bookingpress_front_vue_data_fields['currentPage'] = 1;
            $bookingpress_front_vue_data_fields['total_records'] = 0;
            $bookingpress_front_vue_data_fields['hide_on_single_page'] = true;
            $bookingpress_front_vue_data_fields['open_package_booking_modal'] = false;

			$bookingpress_front_vue_data_fields['show_package_paypal_popup_button'] = "false";
            $bookingpress_front_vue_data_fields['paypal_button_loader'] = "false";
			$bookingpress_front_vue_data_fields['paypal_success_url'] = "";
			$bookingpress_front_vue_data_fields['paypal_cancel_url'] = "";
			$bookingpress_front_vue_data_fields['paypal_booking_form_redirection_mode'] = "";

            /* Paypal Payment GateWay Data Here Start */

            $paypal_payment  = $BookingPress->bookingpress_get_settings('paypal_payment', 'payment_setting');
            $bookingpress_front_vue_data_fields['paypal_payment']  = $paypal_payment;
            if($paypal_payment == '1' || $paypal_payment == 1){
                $bookingpress_front_vue_data_fields['bookingpress_activate_package_payment_gateway_counter'] = 1;
            }
            $bookingpress_paypal_text  = $BookingPress->bookingpress_get_customize_settings('pkg_paypal_text', 'package_booking_form');
            if (empty($bookingpress_paypal_text) ) {
                $bookingpress_paypal_text = __('PayPal', 'bookingpress-package');
            }
            $bookingpress_front_vue_data_fields['paypal_text']  = $bookingpress_paypal_text;

            $package_go_back_button_text = $BookingPress->bookingpress_get_customize_settings('package_go_back_button_text', 'package_booking_form');
            $bookingpress_front_vue_data_fields['package_go_back_text']  = $package_go_back_button_text;

            $bookingpress_front_vue_data_fields['no_payment_method_available']  = __('There is no payment method available.
            ', 'bookingpress-package');

            /* Paypal Payment GateWay Data Here Over */
            
            $bookingpress_pkg_user_details_step_label = $BookingPress->bookingpress_get_customize_settings('user_details_step_label', 'package_booking_form');
            $bookingpress_pkg_make_payment_tab_title = $BookingPress->bookingpress_get_customize_settings('make_payment_tab_title', 'package_booking_form');
            $bookingpress_pkg_summary_step_title = $BookingPress->bookingpress_get_customize_settings('summary_step_title', 'package_booking_form');

            $bookingpress_pkg_user_details_step_label = !empty($bookingpress_pkg_user_details_step_label) ? stripslashes_deep($bookingpress_pkg_user_details_step_label) : '';
            $bookingpress_pkg_make_payment_tab_title = !empty($bookingpress_pkg_make_payment_tab_title) ? stripslashes_deep($bookingpress_pkg_make_payment_tab_title) : '';
            $bookingpress_pkg_summary_step_title = !empty($bookingpress_pkg_summary_step_title) ? stripslashes_deep($bookingpress_pkg_summary_step_title) : '';
        
            $bookingpress_package_sidebar_steps_data = array(
                'user_detail' => array(
                    'tab_name' => $bookingpress_pkg_user_details_step_label,
                    'tab_value' => 'user_detail',
                    'tab_icon' => '<svg width="28" height="28" viewBox="0 0 28 28" fill="#00ff00" xmlns="http://www.w3.org/2000/svg"><g clip-path="url(#clip0_917_2499)"><path class="bpp-ev__vector-primary-color" d="M14.0007 2.33334C7.56065 2.33334 2.33398 7.56001 2.33398 14C2.33398 20.44 7.56065 25.6667 14.0007 25.6667C20.4407 25.6667 25.6673 20.44 25.6673 14C25.6673 7.56001 20.4407 2.33334 14.0007 2.33334ZM14.0007 5.83334C15.9373 5.83334 17.5007 7.39668 17.5007 9.33334C17.5007 11.27 15.9373 12.8333 14.0007 12.8333C12.064 12.8333 10.5007 11.27 10.5007 9.33334C10.5007 7.39668 12.064 5.83334 14.0007 5.83334ZM14.0007 22.4C11.084 22.4 8.50565 20.9067 7.00065 18.6433C7.03565 16.3217 11.6673 15.05 14.0007 15.05C16.3223 15.05 20.9657 16.3217 21.0007 18.6433C19.4957 20.9067 16.9173 22.4 14.0007 22.4Z"/></g><defs><clipPath id="clip0_917_2499"><rect width="28" height="28" fill="white"/></clipPath></defs></svg>',
                    'next_tab_name' => 'payment',
                    'next_tab_label' => '',
                    'previous_tab_name' => '',
                    'validate_fields' => array(),
                    'auto_focus_tab_callback' => array(),
                    'validation_msg' => array(),
                    'is_allow_navigate' => 0,
                    'is_navigate_to_next' => 0,
                    'is_display_step' => 1,
                    'sorting_key' => 'user_detail_selection',
                ),
                'payment' => array(
                    'tab_name' => $bookingpress_pkg_make_payment_tab_title,
                    'tab_value' => 'payment',
                    'tab_icon' => '<svg width="28" height="28" viewBox="0 0 28 28" fill="none" xmlns="http://www.w3.org/2000/svg"><g clip-path="url(#clip0_917_2497)"><path class="bpp-ev__vector-primary-color" d="M14.0007 2.33334C7.56065 2.33334 2.33398 7.56001 2.33398 14C2.33398 20.44 7.56065 25.6667 14.0007 25.6667C20.4407 25.6667 25.6673 20.44 25.6673 14C25.6673 7.56001 20.4407 2.33334 14.0007 2.33334ZM15.6457 21.105V21.7817C15.6457 22.6333 14.9457 23.3333 14.094 23.3333H14.0823C13.2307 23.3333 12.5307 22.6333 12.5307 21.7817V21.0817C10.979 20.755 9.60232 19.9033 9.01898 18.4683C8.75065 17.8267 9.25232 17.115 9.95232 17.115H10.2323C10.664 17.115 11.014 17.4067 11.1773 17.815C11.5157 18.69 12.4023 19.2967 14.1057 19.2967C16.3923 19.2967 16.9057 18.1533 16.9057 17.4417C16.9057 16.4733 16.3923 15.5633 13.7907 14.945C10.8973 14.245 8.91398 13.055 8.91398 10.6633C8.91398 8.65668 10.5357 7.35001 12.5423 6.91834V6.21834C12.5423 5.36668 13.2423 4.66668 14.094 4.66668H14.1057C14.9573 4.66668 15.6573 5.36668 15.6573 6.21834V6.94168C17.2673 7.33834 18.2823 8.34168 18.7256 9.57834C18.959 10.22 18.469 10.8967 17.7807 10.8967H17.4773C17.0457 10.8967 16.6957 10.5933 16.579 10.1733C16.3107 9.28668 15.5757 8.71501 14.1057 8.71501C12.3557 8.71501 11.3057 9.50834 11.3057 10.6283C11.3057 11.6083 12.064 12.25 14.4207 12.8567C16.7773 13.4633 19.2973 14.4783 19.2973 17.4183C19.274 19.5533 17.6757 20.72 15.6457 21.105V21.105Z"/></g><defs><clipPath id="clip0_917_2497"><rect width="28" height="28" fill="white"/></clipPath></defs></svg>',
                    'next_tab_name' => 'summary',
                    'next_tab_label' => '',
                    'previous_tab_name' => 'user_detail',
                    'validate_fields' => array(),
                    'auto_focus_tab_callback' => array(),
                    'validation_msg' => array(),
                    'is_allow_navigate' => 0,
                    'is_navigate_to_next' => 0,
                    'is_display_step' => 1,
                    'sorting_key' => 'user_detail_selection',
                ),
                'summary' => array(
                    'tab_name' => $bookingpress_pkg_summary_step_title,
                    'tab_value' => 'summary',
                    'tab_icon' => '<svg width="28" height="28" viewBox="0 0 28 28" fill="none" xmlns="http://www.w3.org/2000/svg"><g clip-path="url(#clip0_917_2498)"><path class="bpp-ev__vector-primary-color" d="M21.8932 3.50001H17.0768C16.5928 2.14667 15.3253 1.16667 13.8274 1.16667C12.3295 1.16667 11.062 2.14667 10.578 3.50001H5.76156C4.49407 3.50001 3.45703 4.55001 3.45703 5.83334V22.1667C3.45703 23.45 4.49407 24.5 5.76156 24.5H21.8932C23.1607 24.5 24.1978 23.45 24.1978 22.1667V5.83334C24.1978 4.55001 23.1607 3.50001 21.8932 3.50001ZM13.8274 3.50001C14.4611 3.50001 14.9797 4.02501 14.9797 4.66667C14.9797 5.30834 14.4611 5.83334 13.8274 5.83334C13.1937 5.83334 12.6751 5.30834 12.6751 4.66667C12.6751 4.02501 13.1937 3.50001 13.8274 3.50001ZM10.7048 19.005L7.7204 15.9833C7.27102 15.5283 7.27102 14.7933 7.7204 14.3383C8.16979 13.8833 8.89571 13.8833 9.3451 14.3383L11.5229 16.5317L18.2982 9.67167C18.7476 9.21667 19.4735 9.21667 19.9229 9.67167C20.3723 10.1267 20.3723 10.8617 19.9229 11.3167L12.3295 19.005C11.8916 19.46 11.1541 19.46 10.7048 19.005Z"/></g><defs><clipPath id="clip0_917_2498"><rect width="27.6543" height="28" fill="white"/></clipPath></defs></svg>',
                    'next_tab_name' => '',
                    'auto_focus_tab_callback' => array(),
                    'previous_tab_name' => '',
                    'validate_fields' => array(),
                    'is_allow_navigate' => 0,
                    'is_display_step' => 1,
                    'is_navigate_to_next' => false,
                    'sorting_key' => 'summary_selection',
                ),
            );            
            $bookingpress_front_vue_data_fields['bookingpress_package_sidebar_step_data'] = $bookingpress_package_sidebar_steps_data;
            $bookingpress_front_vue_data_fields['bookingpress_package_sidebar_step_data_org'] = $bookingpress_package_sidebar_steps_data;            
            $bookingpress_front_vue_data_fields['bookingpress_current_package_tab'] = 'user_detail';
            
            
            $bookingpress_front_vue_data_fields['bookingpress_booked_package_detail'] = array(
                'bookingpress_package_no' => '',
                'bookingpress_customer_name' => '',
                'bookingpress_package_name' => '',    
            );

            $bookingpress_front_vue_data_fields['bookingpress_current_package_tab_org'] = 'user_detail';            
            $bookingpress_front_vue_data_fields['bookingpress_package_login_user_id'] = get_current_user_id();

            $bookingpress_front_vue_data_fields['package_step_form_data']['package_price_without_currency'] = 0;
            $bookingpress_front_vue_data_fields['package_step_form_data']['bookingpress_selected_package_id'] = '';
            $bookingpress_front_vue_data_fields['package_step_form_data']['bookingpress_selected_package_detail'] = array();

            $bookingpress_front_vue_data_fields['package_step_form_data']['selected_payment_method'] = '';

            $bookingpress_front_vue_data_fields['is_display_card_option'] = 0;


            $bookingpress_front_vue_data_fields['isPackageLoadBookingLoader'] = '0';
            $bookingpress_front_vue_data_fields['isPackageBookingDisabled'] = false;            

            /* Payment Gateway Add Start Here */
            
            $paypal_payment  = $BookingPress->bookingpress_get_settings('paypal_payment', 'payment_setting');
            $bookingpress_front_vue_data_fields['paypal_payment']  = $paypal_payment;            

            /* Payment Gateway Add Over Here */

			$bookingpress_front_vue_data_fields['package_step_form_data']['card_holder_name'] = '';
			$bookingpress_front_vue_data_fields['package_step_form_data']['card_number']      = '';
			$bookingpress_front_vue_data_fields['package_step_form_data']['expire_month']     = '';
			$bookingpress_front_vue_data_fields['package_step_form_data']['expire_year']      = '';
			$bookingpress_front_vue_data_fields['package_step_form_data']['cvv']              = '';            

			$bookingpress_front_vue_data_fields['package_step_form_data']['subtotal'] = 0;
			$bookingpress_front_vue_data_fields['package_step_form_data']['subtotal_with_currency'] = $BookingPress->bookingpress_price_formatter_with_currency_symbol(0);

            $tax_percentage = $BookingPress->bookingpress_get_settings( 'tax_percentage', 'payment_setting' );			
            $bookingpress_front_vue_data_fields['package_step_form_data']['tax_percentage'] = $tax_percentage;

            $bookingpress_front_vue_data_fields['package_step_form_data']['enable_country_wise_tax'] = $BookingPress->bookingpress_get_settings('enable_country_wise_tax', 'payment_setting');

            $bookingpress_front_vue_data_fields['package_step_form_data']['countryselectedField'] = $BookingPress->bookingpress_get_settings('countryselectedField', 'payment_setting');  

            $bookingpress_front_vue_data_fields['package_step_form_data']['country_wise_tax_details'] = array();

            if ( class_exists('bookingpress_tax') && method_exists($bookingpress_tax, 'bookingpress_get_country_wise_tax_details')) {
                $bookingpress_front_vue_data_fields['package_step_form_data']['country_wise_tax_details'] = $bookingpress_tax->bookingpress_get_country_wise_tax_details();
            }

            $bookingpress_front_vue_data_fields['package_step_form_data']['tax'] = 0;
            $bookingpress_front_vue_data_fields['package_step_form_data']['tax_with_currency'] = $BookingPress->bookingpress_price_formatter_with_currency_symbol(0);
            $bookingpress_price_setting_display_option = $BookingPress->bookingpress_get_settings('price_settings_and_display', 'payment_setting');
            $bookingpress_front_vue_data_fields['package_step_form_data']['tax_price_display_options'] = $bookingpress_price_setting_display_option;            
            $bookingpress_tax_order_summary = $BookingPress->bookingpress_get_settings('display_tax_order_summary', 'payment_setting');
            $bookingpress_front_vue_data_fields['package_step_form_data']['display_tax_order_summary'] = $bookingpress_tax_order_summary;            
            $bookingpress_tax_order_summary_text = $BookingPress->bookingpress_get_settings('included_tax_label', 'payment_setting');
            $bookingpress_front_vue_data_fields['package_step_form_data']['included_tax_label'] = $bookingpress_tax_order_summary_text;
            
            $bookingpress_front_vue_data_fields['is_tax_activated']    = (is_plugin_active('bookingpress-tax/bookingpress-tax.php'))?1:0;
            $bookingpress_front_vue_data_fields['is_tip_activated']    = (is_plugin_active('bookingpress-tip/bookingpress-tip.php'))?1:0;

            $bookingpress_front_vue_data_fields['package_step_form_data']['total_amount'] = 0;
            $bookingpress_front_vue_data_fields['package_step_form_data']['total_payable_amount'] = 0;
            $bookingpress_front_vue_data_fields['package_step_form_data']['total_amount_with_currency'] = $BookingPress->bookingpress_price_formatter_with_currency_symbol(0);

            $bookingpress_front_vue_data_fields['is_tax_enable'] = (is_plugin_active('bookingpress-tax/bookingpress-tax.php'))?1:0;
            $bookingpress_front_vue_data_fields['is_tip_enable'] = (is_plugin_active('bookingpress-tip/bookingpress-tip.php'))?1:0;

			$bookingpress_currency_separator = $BookingPress->bookingpress_get_settings('price_separator', 'payment_setting');
			$bookingpress_front_vue_data_fields['bookingpress_currency_separator'] = $bookingpress_currency_separator;			
			$bookingpress_decimal_points = $BookingPress->bookingpress_get_settings('price_number_of_decimals', 'payment_setting');
			$bookingpress_decimal_points = intval($bookingpress_decimal_points);
			$bookingpress_front_vue_data_fields['bookingpress_decimal_points'] = $bookingpress_decimal_points;
            $bookingpress_currency_name = $BookingPress->bookingpress_get_settings('payment_default_currency', 'payment_setting');
            $bookingpress_front_vue_data_fields['bookingpress_currency_name'] = $bookingpress_currency_name;			
            $bookingpress_front_vue_data_fields['bookingpress_currency_symbol'] = $BookingPress->bookingpress_get_currency_symbol($bookingpress_currency_name);
            $bookingpress_price_symbol_position = $BookingPress->bookingpress_get_settings('price_symbol_position', 'payment_setting');
            $bookingpress_front_vue_data_fields['bookingpress_currency_symbol_position'] = $bookingpress_price_symbol_position;


			/* Package Login Form Data Start  */            
			$bookingpress_front_vue_data_fields['bookingpress_package_login_form'] = array(
				'bookingpress_login_email' => '',
				'bookingpress_login_pass' => '',
				'bookingpress_is_remember' => '',
			);
            $bookingpress_front_vue_data_fields['bookingpress_package_login_form_org'] = $bookingpress_front_vue_data_fields['bookingpress_package_login_form'];
            $login_form_username_required_field_label = stripslashes_deep($BookingPress->bookingpress_get_customize_settings('login_form_username_required_field_label', 'package_booking_form'));
            $login_form_password_required_field_label = stripslashes_deep($BookingPress->bookingpress_get_customize_settings('login_form_password_required_field_label', 'package_booking_form'));
                  
			$bookingpress_front_vue_data_fields['bookingpress_package_login_form_rules'] = array(
				'bookingpress_login_email' => array(
					'required' => true,
					'message' => $login_form_username_required_field_label,
					'trigger' => 'blur',
				),
				'bookingpress_login_pass' => array(
					'required' => true,
					'message' => $login_form_password_required_field_label,
					'trigger' => 'blur',
				),
			);          
            $bookingpress_front_vue_data_fields['bookingpress_package_login_loader'] = '0';
            /* Package Login Form Data Over */
            $bookingpress_front_vue_data_fields['bookingpress_package_is_active_form'] = 'login';
            /* Tel-Phone Input Data  */
            $bookingpress_front_vue_data_fields['vue_tel_mode'] = 'international';
            $bookingpress_front_vue_data_fields['vue_tel_auto_format'] = true;    
            $bookingpress_phone_country_option = $BookingPress->bookingpress_get_settings('default_phone_country_code', 'general_setting');
            $bookingpress_front_vue_data_fields['bookingpress_package_tel_input_props'] = array(
                'defaultCountry' => $bookingpress_phone_country_option,
                'inputOptions'   => array(
                    'placeholder' => '',
                ),
                'validCharactersOnly' => true,
            );

			if ( ! empty( $bookingpress_front_vue_data_fields['bookingpress_package_tel_input_props']['defaultCountry'] ) && $bookingpress_front_vue_data_fields['bookingpress_package_tel_input_props']['defaultCountry'] == 'auto_detect' ) {
				// Get visitors ip address
				$bookingpress_ip_address = $this->boookingpress_get_visitor_ip();                
				try {
                    
					$bookingpress_country_reader = new Reader( BOOKINGPRESS_PRO_LIBRARY_DIR . '/geoip/inc/GeoLite2-Country.mmdb' );
					
                    $bookingpress_country_record = $bookingpress_country_reader->country( $bookingpress_ip_address );
                    
					if ( ! empty( $bookingpress_country_record->country ) ) {
						$bookingpress_country_name     = $bookingpress_country_record->country->name;
						$bookingpress_country_iso_code = $bookingpress_country_record->country->isoCode;
						$bookingpress_front_vue_data_fields['bookingpress_package_tel_input_props']['defaultCountry'] = $bookingpress_country_iso_code;
					}
				} catch ( Exception $e ) {
					$bookingpress_error_message = $e->getMessage();                    
				}
			}  

			$bookingpress_front_vue_data_fields['bookingpress_package_reg_form'] = array(				
				'bookingpress_customer_full_name' => '',
				'bookingpress_reg_email' => '',
                'customer_phone' => '',
				'bookingpress_reg_pass' => '',                
			);

            $signup_account_email_required_message = stripslashes_deep($BookingPress->bookingpress_get_customize_settings('signup_account_email_required_message', 'package_booking_form'));
            $signup_account_mobile_number_required_message = stripslashes_deep($BookingPress->bookingpress_get_customize_settings('signup_account_mobile_number_required_message', 'package_booking_form'));
            $signup_account_password_required_message = stripslashes_deep($BookingPress->bookingpress_get_customize_settings('signup_account_password_required_message', 'package_booking_form'));
            $signup_account_fullname_required_message = stripslashes_deep($BookingPress->bookingpress_get_customize_settings('signup_account_fullname_required_message', 'package_booking_form'));
            
            $bookingpress_front_vue_data_fields['bookingpress_package_reg_loader'] = '0';
			$bookingpress_front_vue_data_fields['bookingpress_package_reg_form_rules'] = array(
				'bookingpress_customer_full_name' => array(
					'required' => true,
					'message' => $signup_account_fullname_required_message,
					'trigger' => 'blur',
				),               
                'bookingpress_reg_email' => array(
					'required' => true,
					'message' => $signup_account_email_required_message,
					'trigger' => 'blur',
				),
                'customer_phone' => array(
					'required' => true,
					'message' => $signup_account_mobile_number_required_message,
					'trigger' => 'blur',
				),                
				'bookingpress_reg_pass' => array(
					'required' => true,
					'message' => $signup_account_password_required_message,
					'trigger' => 'blur',
				),
			);
            $bookingpress_front_vue_data_fields['bookingpress_package_reg_form']['customer_phone_country'] = $bookingpress_phone_country_option;
            $bookingpress_front_vue_data_fields['bookingpress_package_reg_form']['customer_phone_dial_code'] = '';
            $bookingpress_front_vue_data_fields['bookingpress_package_reg_form']['bookingpress_customer_timezone'] = $bookingpress_global_options->bookingpress_get_site_timezone_offset();

            $bookingpress_front_vue_data_fields['bookingpress_package_reg_form_org'] = $bookingpress_front_vue_data_fields['bookingpress_package_reg_form'];
            /* Forgot Password Form Data */			
			$bookingpress_front_vue_data_fields['bookingpress_package_forgot_password_form'] = array(
				'bookingpress_forgot_password_email' => '',
			);

            $forgot_password_form_email_required_field_label = $BookingPress->bookingpress_get_customize_settings('forgot_password_email_required_field_label', 'package_booking_form');
			$bookingpress_front_vue_data_fields['bookingpress_package_forgot_password_form_rules'] = array(
				'bookingpress_forgot_password_email' => array(
					array(
						'required' => true,
						'message'  => stripslashes_deep($forgot_password_form_email_required_field_label),
						'trigger'  => 'change',
					),
				),
			);            
            $bookingpress_front_vue_data_fields['bookingpress_package_forgotpassword_loader'] = '0';
            $bookingpress_front_vue_data_fields['bookingpress_package_forgot_password_form_org'] = $bookingpress_front_vue_data_fields['bookingpress_package_forgot_password_form'];
            /* Tel-Phone Input Data */

            $bookingpress_package_form_title = $BookingPress->bookingpress_get_customize_settings('package_form_title', 'package_booking_form');
            $bookingpress_package_search_placeholder = $BookingPress->bookingpress_get_customize_settings('package_search_placeholder', 'package_booking_form');
            $bookingpress_package_search_button = $BookingPress->bookingpress_get_customize_settings('package_search_button', 'package_booking_form');
            $bookingpress_package_buy_now_button_text = $BookingPress->bookingpress_get_customize_settings('package_buy_now_nutton_text', 'package_booking_form');
            $bookingpress_package_services_include_text = $BookingPress->bookingpress_get_customize_settings('package_services_include_text', 'package_booking_form');
            $bookingpress_package_desc_read_more_text = $BookingPress->bookingpress_get_customize_settings('package_desc_read_more_text', 'package_booking_form');
            $bookingpress_package_desc_show_less_text = $BookingPress->bookingpress_get_customize_settings('package_desc_show_less_text', 'package_booking_form');

            $package_booking_labels = array(
                'package_form_title' => stripslashes_deep($bookingpress_package_form_title),
                'package_search_placeholder' => stripslashes_deep($bookingpress_package_search_placeholder),
                'package_search_button' => stripslashes_deep($bookingpress_package_search_button),
                'package_buy_now_button_text' => stripslashes_deep($bookingpress_package_buy_now_button_text),
                'package_services_include_text' => stripslashes_deep($bookingpress_package_services_include_text),
                'package_desc_read_more_text' => stripslashes_deep($bookingpress_package_desc_read_more_text),
                'package_desc_show_less_text' => stripslashes_deep($bookingpress_package_desc_show_less_text),
            );
            $bookingpress_front_vue_data_fields['package_booking_labels'] = $package_booking_labels;

            $bookingpress_hide_slider_indicators = $BookingPress->bookingpress_get_customize_settings( 'hide_image_indicator', 'package_booking_form' );
            $bookingpress_is_slider_autoplay = $BookingPress->bookingpress_get_customize_settings( 'auto_scroll_image', 'package_booking_form' );
            $bookingpress_slider_autoplay_interval = 5; /** reputelog - make this settings dynamic */

            $bookingpress_front_vue_data_fields['package_customize_settings'] = array(
                'hide_indicator' => ( 'true' == $bookingpress_hide_slider_indicators ) ? true : false,
                'autoplay' => ( 'true' == $bookingpress_is_slider_autoplay ) ? true : false,
                'autoplay_interval' => ( $bookingpress_slider_autoplay_interval > 0 ) ? ($bookingpress_slider_autoplay_interval * 1000) : 5000,
            );


            /* Front Custom Fields Get Start */

            $bookingpress_front_vue_data_fields['check_bookingpress_username_set'] = 0;
            $bookingpress_front_vue_data_fields['bpa_check_user_login'] = get_current_user_id();

            $bookingpress_phone_mandatory_option = $BookingPress->bookingpress_get_settings('phone_number_mandatory', 'general_setting');
            if (! empty($bookingpress_phone_mandatory_option) && $bookingpress_phone_mandatory_option == 'true' ) {
                $mandatory_field_data = array(
                'required' => true,
                'message'  => __('Please enter customer phone number', 'bookingpress-package'),
                'trigger'  => 'blur',
                );
                $bookingpress_front_vue_data_fields['package_customer_details_rule']['customer_phone'] = $mandatory_field_data;
            }

            $bookingpress_front_vue_data_fields['package_step_form_data']['form_fields'] = array();

            $bookingpress_form_fields = $wpdb->get_results( $wpdb->prepare('SELECT * FROM ' . $tbl_bookingpress_form_fields . ' WHERE bookingpress_field_is_package_hide = %d ORDER BY bookingpress_is_customer_field,bookingpress_field_position ASC',0), ARRAY_A ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared --Reason: $tbl_bookingpress_form_fields is table name defined globally. False Positive alarm

           
            $bookingpress_form_fields_error_msg_arr = $bookingpress_form_fields_new = array();
            
            $bookingpress_form_fields        = apply_filters('bookingpress_modify_field_data_before_prepare', $bookingpress_form_fields);
            
            foreach ( $bookingpress_form_fields as $bookingpress_form_field_key => $bookingpress_form_field_val ) {

                if($bookingpress_form_field_val['bookingpress_field_is_hide'] == 0) {

                    $bookingpress_v_model_value = '';
                    if ($bookingpress_form_field_val['bookingpress_form_field_name'] == 'fullname' ) {
                        $bookingpress_v_model_value = 'customer_name';
                    } elseif ($bookingpress_form_field_val['bookingpress_form_field_name'] == 'firstname' ) {
                        $bookingpress_v_model_value = 'customer_firstname';
                    } elseif ($bookingpress_form_field_val['bookingpress_form_field_name'] == 'lastname' ) {
                        $bookingpress_v_model_value = 'customer_lastname';
                    } elseif ($bookingpress_form_field_val['bookingpress_form_field_name'] == 'email_address' ) {
                        $bookingpress_v_model_value = 'customer_email';
                    } elseif ($bookingpress_form_field_val['bookingpress_form_field_name'] == 'phone_number' ) {
                        $bookingpress_v_model_value = 'customer_phone';
                    } elseif ($bookingpress_form_field_val['bookingpress_form_field_name'] == 'note' ) {
                        $bookingpress_v_model_value = 'appointment_note';
                    } elseif($bookingpress_form_field_val['bookingpress_form_field_name'] == 'username' ){
                        $bookingpress_v_model_value = 'customer_username';
                    } elseif ($bookingpress_form_field_val['bookingpress_form_field_name'] == 'terms_and_conditions' ) {
                        $bookingpress_v_model_value = 'appointment_terms_conditions';
                    } else {
                        $bookingpress_v_model_value = $bookingpress_form_field_val['bookingpress_field_meta_key'];
                    }

                    $bookingpress_front_vue_data_fields['package_step_form_data'][$bookingpress_v_model_value] = '';
                    if( 'appointment_terms_conditions' == $bookingpress_v_model_value ){
                        $bookingpress_front_vue_data_fields['package_step_form_data'][$bookingpress_v_model_value] = array();
                    }

                    $bookingpress_field_type = '';
                    if ($bookingpress_form_field_val['bookingpress_form_field_name'] == 'fullname' ) {
                        $bookingpress_field_type = 'Text';
                    } elseif ($bookingpress_form_field_val['bookingpress_form_field_name'] == 'firstname' ) {
                        $bookingpress_field_type = 'Text';
                    } elseif ($bookingpress_form_field_val['bookingpress_form_field_name'] == 'lastname' ) {
                        $bookingpress_field_type = 'Text';
                    } elseif ($bookingpress_form_field_val['bookingpress_form_field_name'] == 'email_address' ) {
                        $bookingpress_field_type = 'Email';
                    } elseif ($bookingpress_form_field_val['bookingpress_form_field_name'] == 'phone_number' ) {
                        $bookingpress_field_type = 'Dropdown';
                    } elseif ($bookingpress_form_field_val['bookingpress_form_field_name'] == 'note' ) {
                        $bookingpress_field_type = 'Textarea';
                    } elseif($bookingpress_form_field_val['bookingpress_form_field_name'] == 'username' ){
                        $bookingpress_field_type = 'Text';
                    } elseif($bookingpress_form_field_val['bookingpress_form_field_name'] == 'terms_and_conditions'){
                        $bookingpress_field_type = 'terms_and_conditions';
                    } else {
                        $bookingpress_field_type = $bookingpress_form_field_val['bookingpress_field_type'];
                    }

                    $bookingpress_field_setting_fields_tmp                   = array();
                    $bookingpress_field_setting_fields_tmp['id']             = intval($bookingpress_form_field_val['bookingpress_form_field_id']);
                    $bookingpress_field_setting_fields_tmp['field_name']     = $bookingpress_form_field_val['bookingpress_form_field_name'];
                    $bookingpress_field_setting_fields_tmp['field_type']     = $bookingpress_field_type;
                    $bookingpress_field_setting_fields_tmp['is_edit']        = false;

                    $bookingpress_field_setting_fields_tmp['is_required']    = ( $bookingpress_form_field_val['bookingpress_field_required'] == 0 ) ? false : true;
                    $bookingpress_field_setting_fields_tmp['label']          = stripslashes_deep($bookingpress_form_field_val['bookingpress_field_label']);
                    $bookingpress_field_setting_fields_tmp['placeholder']    = stripslashes_deep($bookingpress_form_field_val['bookingpress_field_placeholder']);
                    $bookingpress_field_setting_fields_tmp['error_message']  = stripslashes_deep($bookingpress_form_field_val['bookingpress_field_error_message']);
                    $bookingpress_field_setting_fields_tmp['is_hide']        = ( $bookingpress_form_field_val['bookingpress_field_is_hide'] == 0 ) ? false : true;
                    $bookingpress_field_setting_fields_tmp['field_position'] = floatval($bookingpress_form_field_val['bookingpress_field_position']);
                    $bookingpress_field_setting_fields_tmp['v_model_value']  = $bookingpress_v_model_value;                    

                    $bookingpress_field_setting_fields_tmp = apply_filters( 'bookingpress_arrange_form_fields_outside', $bookingpress_field_setting_fields_tmp, $bookingpress_form_field_val);                    
                    $bookingpress_front_vue_data_fields['package_step_form_data'] = apply_filters('bookingpress_add_appointment_step_form_data_filter',$bookingpress_front_vue_data_fields['package_step_form_data'],$bookingpress_field_setting_fields_tmp);                    
                    array_push( $bookingpress_form_fields_new, $bookingpress_field_setting_fields_tmp );
                    if ($bookingpress_form_field_val['bookingpress_field_required'] == '1' ) {
                        if ($bookingpress_v_model_value == 'customer_email' ) {
                            $bookingpress_form_fields_error_msg_arr[ $bookingpress_v_model_value ] = array(
                                array(
                                'required' => true,
                                'message'  => stripslashes_deep($bookingpress_form_field_val['bookingpress_field_error_message']),
                                'trigger'  => 'blur',
                                ),
                                array(
                                'type'    => 'email',
                                'message' => esc_html__('Please enter valid email address', 'bookingpress-package'),
                                'trigger' => 'blur',
                            ),
                         );
                        } elseif( $bookingpress_v_model_value == 'appointment_terms_conditions') {
                               
                            $bookingpress_form_fields_error_msg_arr[ $bookingpress_v_model_value ][] = array(
                                'required' => true,
                                'message'  => stripslashes_deep($bookingpress_form_field_val['bookingpress_field_error_message']),
                                'trigger'  => 'change',
                            ); 
                        } else {                 
                            $bookingpress_form_fields_error_msg_arr[ $bookingpress_v_model_value ][] = array(
                                'required' => true,
                                'message'  => stripslashes_deep($bookingpress_form_field_val['bookingpress_field_error_message']),
                                'trigger'  => 'blur',
                            );                                                       
                        }

                        if(isset($bookingpress_form_fields_error_msg_arr[$bookingpress_v_model_value][0]['message']) && $bookingpress_form_fields_error_msg_arr[ $bookingpress_v_model_value][0]['message'] == '') {
                            $bookingpress_form_fields_error_msg_arr[ $bookingpress_v_model_value ][0]['message'] = !empty($bookingpress_form_field_val['bookingpress_field_label']) ?  stripslashes_deep($bookingpress_form_field_val['bookingpress_field_label']).' '.__('is required','bookingpress-package') : '';
                        }           
                    }                                       
                    $bookingpress_form_fields_error_msg_arr = apply_filters( 'bookingpress_modify_form_fields_rules_arr', $bookingpress_form_fields_error_msg_arr,$bookingpress_field_setting_fields_tmp );
                }    
            }
            
            
			$bookingpress_front_vue_data_fields['bookingpress_package_customer_details_rule'] = $bookingpress_form_fields_error_msg_arr;			            
            $bookingpress_front_vue_data_fields['package_customer_form_fields'] = $bookingpress_form_fields_new;           
			if(isset($bookingpress_front_vue_data_fields['package_step_form_data']['customer_name'])) {
				$bookingpress_front_vue_data_fields['package_step_form_data']['form_fields']['customer_name'] = !empty($bookingpress_front_vue_data_fields['package_step_form_data']['customer_name']) ? $bookingpress_front_vue_data_fields['package_step_form_data']['customer_name'] : '';

			}			
			if(isset($bookingpress_front_vue_data_fields['package_step_form_data']['customer_username'])){
				$bookingpress_front_vue_data_fields['package_step_form_data']['form_fields']['customer_username'] = !empty($bookingpress_front_vue_data_fields['package_step_form_data']['customer_username']) ? $bookingpress_front_vue_data_fields['package_step_form_data']['customer_username'] : '';

			}
			if(isset($bookingpress_front_vue_data_fields['package_step_form_data']['customer_firstname'])) {
				$bookingpress_front_vue_data_fields['package_step_form_data']['form_fields']['customer_firstname'] = !empty($bookingpress_front_vue_data_fields['package_step_form_data']['customer_firstname']) ? $bookingpress_front_vue_data_fields['package_step_form_data']['customer_firstname'] : '';

                
			}
			if(isset($bookingpress_front_vue_data_fields['package_step_form_data']['customer_lastname'])) {
				$bookingpress_front_vue_data_fields['package_step_form_data']['form_fields']['customer_lastname'] = !empty($bookingpress_front_vue_data_fields['package_step_form_data']['customer_lastname']) ? $bookingpress_front_vue_data_fields['package_step_form_data']['customer_lastname'] : '';

                
			}
			if(isset($bookingpress_front_vue_data_fields['package_step_form_data']['customer_email'])) {
				$bookingpress_front_vue_data_fields['package_step_form_data']['form_fields']['customer_email'] = !empty($bookingpress_front_vue_data_fields['package_step_form_data']['customer_email']) ? $bookingpress_front_vue_data_fields['package_step_form_data']['customer_email'] : '';

                
			}
			if(isset($bookingpress_front_vue_data_fields['package_step_form_data']['customer_phone'])) {
				$bookingpress_front_vue_data_fields['package_step_form_data']['form_fields']['customer_phone'] = !empty($bookingpress_front_vue_data_fields['package_step_form_data']['customer_phone']) ? $bookingpress_front_vue_data_fields['package_step_form_data']['customer_phone'] : '';

                
			}
			if(isset($bookingpress_front_vue_data_fields['package_step_form_data']['customer_phone_country'])) {
				$bookingpress_front_vue_data_fields['package_step_form_data']['form_fields']['customer_phone_country'] = !empty($bookingpress_front_vue_data_fields['package_step_form_data']['customer_phone_country']) ? $bookingpress_front_vue_data_fields['package_step_form_data']['customer_phone_country'] : '';

                
			}
			if(isset($bookingpress_front_vue_data_fields['package_step_form_data']['appointment_note'])) {
				$bookingpress_front_vue_data_fields['package_step_form_data']['form_fields']['appointment_note'] = !empty($bookingpress_front_vue_data_fields['package_step_form_data']['appointment_note']) ? $bookingpress_front_vue_data_fields['package_step_form_data']['appointment_note'] : '';

                

			}
			if(isset($bookingpress_front_vue_data_fields['package_step_form_data']['appointment_terms_conditions'])) {

				$bookingpress_front_vue_data_fields['package_step_form_data']['form_fields']['appointment_terms_conditions'] = !empty($bookingpress_front_vue_data_fields['package_step_form_data']['appointment_terms_conditions']) ? $bookingpress_front_vue_data_fields['package_step_form_data']['appointment_terms_conditions'] : array();

                
			}
			$all_external_fields = $wpdb->get_results( $wpdb->prepare( "SELECT bookingpress_field_meta_key,bookingpress_form_field_name FROM {$tbl_bookingpress_form_fields} WHERE bookingpress_field_type NOT IN ('2_col', '3_col', '4_col', 'repeater')  AND bookingpress_form_field_name NOT IN ('2 Col', '3 Col', '4 Col', 'Repeater') AND bookingpress_field_is_default != %d AND bookingpress_field_is_package_hide = %d AND bookingpress_field_type != %s", 1, 0,'password') ); // phpcs:ignore
			if( !empty( $all_external_fields ) ){
				foreach( $all_external_fields as $external_field_data ){
					$field_name = $external_field_data->bookingpress_form_field_name;
					$field_metakey = $external_field_data->bookingpress_field_meta_key;
					if( $field_name == 'Password'){
						$bookingpress_front_vue_data_fields['package_step_form_data']['form_fields']['customer_password'] = '';
                        
					} else {
						$bookingpress_front_vue_data_fields['package_step_form_data']['form_fields'][ $field_metakey ] = '';
                        
					}
				}
			}            
            $bookingpress_front_vue_data_fields['bpa_check_user_login'] = 0;
            /* Add Login User Data In Custom Fields Start */

            $bookingpress_front_vue_data_fields['bookingpress_created_nonce'] = esc_html(wp_create_nonce('bpa_wp_nonce'));

            $bookingpress_front_vue_data_fields['package_step_form_data']['bookingpress_customer_id'] = '';
            if(is_user_logged_in()){        
                $bookingpress_front_vue_data_fields['bpa_check_user_login'] = 1;

                $bookingpress_package_login_user_data = $this->get_bookingpress_package_login_user_data();
                if(!empty($bookingpress_package_login_user_data)){

                    $bookingpress_front_vue_data_fields['package_step_form_data']['bookingpress_customer_id'] = $bookingpress_package_login_user_data['bookingpress_customer_id'];
                    $bookingpress_front_vue_data_fields['package_step_form_data']['form_fields']['customer_name'] = (isset($bookingpress_package_login_user_data['customer_name']))?$bookingpress_package_login_user_data['customer_name']:'';

                    $bookingpress_front_vue_data_fields['package_step_form_data']['form_fields']['customer_username'] = (isset($bookingpress_package_login_user_data['customer_username']))?$bookingpress_package_login_user_data['customer_username']:'';
                    $bookingpress_front_vue_data_fields['package_step_form_data']['form_fields']['customer_firstname'] = (isset($bookingpress_package_login_user_data['customer_firstname']))?$bookingpress_package_login_user_data['customer_firstname']:'';
                    $bookingpress_front_vue_data_fields['package_step_form_data']['form_fields']['customer_lastname'] = (isset($bookingpress_package_login_user_data['customer_lastname']))?$bookingpress_package_login_user_data['customer_lastname']:'';
                    $bookingpress_front_vue_data_fields['package_step_form_data']['form_fields']['customer_email'] = (isset($bookingpress_package_login_user_data['customer_email']))?$bookingpress_package_login_user_data['customer_email']:'';
                    $bookingpress_front_vue_data_fields['package_step_form_data']['form_fields']['customer_phone'] = (isset($bookingpress_package_login_user_data['customer_phone']))?$bookingpress_package_login_user_data['customer_phone']:'';
                    $bookingpress_front_vue_data_fields['package_step_form_data']['form_fields']['customer_phone_country'] = (isset($bookingpress_package_login_user_data['customer_phone_country']))?$bookingpress_package_login_user_data['customer_phone_country']:'';
                    $bookingpress_front_vue_data_fields['package_step_form_data']['form_fields']['customer_lastname'] = (isset($bookingpress_package_login_user_data['customer_lastname']))?$bookingpress_package_login_user_data['customer_lastname']:'';
                    
                    $all_external_fields = $wpdb->get_results( $wpdb->prepare( "SELECT bookingpress_field_meta_key,bookingpress_form_field_name FROM {$tbl_bookingpress_form_fields} WHERE bookingpress_field_type NOT IN ('2_col', '3_col', '4_col') AND bookingpress_field_is_default != %d AND bookingpress_field_is_package_hide != %d", 1,0) ); // phpcs:ignore
                    if( !empty( $all_external_fields ) ){

                        foreach( $all_external_fields as $external_field_data ){

                            $field_name = $external_field_data->bookingpress_form_field_name;
                            $field_metakey = $external_field_data->bookingpress_field_meta_key;
                            if( $field_name == 'Password'){
                                $bookingpress_front_vue_data_fields['package_step_form_data']['form_fields']['customer_password'] = '';
                            } else {
                                $bookingpress_front_vue_data_fields['package_step_form_data']['form_fields'][ $field_metakey ] = '';
                            }

                        }

                    }
                }

			    /** Set customer form field data */
			    $bpa_form_fields = !empty( $bookingpress_front_vue_data_fields['package_step_form_data']['form_fields'] ) ? $bookingpress_front_vue_data_fields['package_step_form_data']['form_fields'] : array();


                if( is_user_logged_in() && !empty( $bpa_form_fields ) ){

                    //global $tbl_bookingpress_customers, $tbl_bookingpress_customers_meta;
    
                    $current_user_id = get_current_user_id();
    
                    $bpa_is_user_customer = $wpdb->get_row( $wpdb->prepare( "SELECT bookingpress_customer_id FROM {$tbl_bookingpress_customers} WHERE bookingpress_wpuser_id = %d ORDER BY bookingpress_customer_id DESC", $current_user_id ) ); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- Reason: $tbl_bookingpress_customers is table name defined globally. False Positive alarm 
    
                    if( !empty( $bpa_is_user_customer ) ){
                        $current_user_customer_id = $bpa_is_user_customer->bookingpress_customer_id;
                        $bpa_form_field_keys = array_keys( $bpa_form_fields );
                        $bpa_excluded_keys = array( 'customer_name', 'customer_firstname', 'customer_lastname', 'customer_email', 'customer_phone', 'customer_phone_country', 'appointment_note');
                        foreach( $bpa_form_field_keys as $bpa_field_key ){
                            if( !in_array( $bpa_field_key, $bpa_excluded_keys ) ){
                                $is_customer_field = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT( bookingpress_form_field_id ) FROM {$tbl_bookingpress_form_fields} WHERE bookingpress_field_meta_key = %s AND bookingpress_is_customer_field = %d", $bpa_field_key, 1) ); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- Reason: $tbl_bookingpress_form_fields is table name defined globally. False Positive alarm 
        
                                if( 1 == $is_customer_field ){
                                    $meta_value = $wpdb->get_row( $wpdb->prepare( "SELECT bookingpress_customersmeta_value FROM {$tbl_bookingpress_customers_meta} WHERE bookingpress_customersmeta_key = %s AND bookingpress_customer_id = %d", $bpa_field_key, $current_user_customer_id )); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- Reason: $tbl_bookingpress_customers_meta is table name defined globally. False Positive alarm 
    
                                    if( !empty( $meta_value ) ){
                                        $customer_meta_value = $meta_value->bookingpress_customersmeta_value;
                                        $is_json = json_decode( $customer_meta_value, true ) == NULL ? false : true;
    
                                        if( $is_json  ){
                                            $customer_meta_value = json_decode( $customer_meta_value, true );
                                        }
                                        $bookingpress_front_vue_data_fields['package_step_form_data']['form_fields'][ $bpa_field_key ] = $customer_meta_value;
                                        
                                    }
                                }
                            }
                        }
                    }
                }


            }
            $bookingpress_form_fields_temp_data = $bookingpress_front_vue_data_fields['package_step_form_data']['form_fields'];
            $bookingpress_front_vue_data_fields['form_fields_org'] = '';
            //$bookingpress_front_vue_data_fields['form_fields_org']['data'] = $bookingpress_form_fields_temp_data;

            //$bookingpress_front_vue_data_fields['form_fields_neworg'] = $bookingpress_form_fields_temp_data;
            /* Add Login User Data In Custom Fields Over */
            /* Front Custom Fields Get Over */

            /* Add Custom fields list in database start */

			$bookingpress_form_fields = wp_cache_get( 'bpa_appointment_booking_form_fields_data_');
            if( false == $bookingpress_form_fields ){
                $bookingpress_form_fields = $wpdb->get_results("SELECT * FROM {$tbl_bookingpress_form_fields} ORDER BY bookingpress_field_position ASC", ARRAY_A); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared --Reason: $tbl_bookingpress_form_fields is a table name. false alarm
                wp_cache_set( 'bpa_appointment_booking_form_fields_data_', $bookingpress_form_fields);
            }
			$bookingpress_form_fields_error_msg_arr = $bookingpress_form_fields_new = $bookingpress_field_list = array();
			foreach ( $bookingpress_form_fields as $bookingpress_form_field_key => $bookingpress_form_field_val ) {
				$bookingpress_v_model_value = '';
				if ( $bookingpress_form_field_val['bookingpress_form_field_name'] == 'fullname' ) {
					$bookingpress_field_list['customer_name'] = $bookingpress_form_field_val['bookingpress_form_field_id'];
				} elseif ( $bookingpress_form_field_val['bookingpress_form_field_name'] == 'firstname' ) {
					$bookingpress_field_list['customer_firstname'] = $bookingpress_form_field_val['bookingpress_form_field_id'];
				} elseif ( $bookingpress_form_field_val['bookingpress_form_field_name'] == 'lastname' ) {
					$bookingpress_field_list['customer_lastname'] = $bookingpress_form_field_val['bookingpress_form_field_id'];
				} elseif ( $bookingpress_form_field_val['bookingpress_form_field_name'] == 'email_address' ) {
					$bookingpress_field_list['customer_email'] = $bookingpress_form_field_val['bookingpress_form_field_id'];
				} elseif ( $bookingpress_form_field_val['bookingpress_form_field_name'] == 'phone_number' ) {
					$bookingpress_field_list['customer_phone'] = $bookingpress_form_field_val['bookingpress_form_field_id'];
				} elseif ( $bookingpress_form_field_val['bookingpress_form_field_name'] == 'note' ) {
					$bookingpress_field_list['appointment_note'] = $bookingpress_form_field_val['bookingpress_form_field_id'];
				} elseif ( $bookingpress_form_field_val['bookingpress_form_field_name'] == 'username' ) {
					$bookingpress_field_list['customer_username'] = $bookingpress_form_field_val['bookingpress_form_field_id'];
				} else {
					/* $bookingpress_field_list[ $bookingpress_form_field_val['bookingpress_form_field_name'] ] = $bookingpress_form_field_val['bookingpress_form_field_id']; */
					$bookingpress_field_list[$bookingpress_form_field_val['bookingpress_field_meta_key']] = $bookingpress_form_field_val['bookingpress_form_field_id'];
				}
			}
			$bookingpress_front_vue_data_fields['package_step_form_data']['bookingpress_front_field_data'] = $bookingpress_field_list;

            $bookingpress_all_checkbox_fields = $wpdb->get_results( $wpdb->prepare( "SELECT bookingpress_field_meta_key FROM {$tbl_bookingpress_form_fields} WHERE bookingpress_field_type = %s AND bookingpress_is_customer_field = %d", 'checkbox', 0 ) ); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared --Reason: $tbl_bookingpress_form_fields is table name.
			
			if( !empty( $bookingpress_all_checkbox_fields ) && is_array($bookingpress_all_checkbox_fields) ){
				foreach( $bookingpress_all_checkbox_fields as $bpa_checkbox_field_data ){
					$bpa_checkbox_meta_key = $bpa_checkbox_field_data->bookingpress_field_meta_key;
					$bookingpress_front_vue_data_fields['package_step_form_data']['form_fields'][ $bpa_checkbox_meta_key ] = array();
				}
			}

            /* Add custom fields list in database over */
         /*    $bookingpress_card_name_text = $BookingPress->bookingpress_get_customize_settings('card_name_text', 'booking_form');
			$bookingpress_card_number_text = $BookingPress->bookingpress_get_customize_settings('card_number_text', 'booking_form');
			$bookingpress_expire_month_text = $BookingPress->bookingpress_get_customize_settings('expire_month_text', 'booking_form');
			$bookingpress_expire_year_text = $BookingPress->bookingpress_get_customize_settings('expire_year_text', 'booking_form');
			$bookingpress_cvv_text = $BookingPress->bookingpress_get_customize_settings('cvv_text', 'booking_form');

            $bookingpress_card_details_text = "";
			$bookingpress_front_vue_data_fields['card_details_text'] = $bookingpress_card_details_text;
			$bookingpress_front_vue_data_fields['card_name_text'] = $bookingpress_card_name_text;
			$bookingpress_front_vue_data_fields['card_number_text'] = $bookingpress_card_number_text;
			$bookingpress_front_vue_data_fields['expire_month_text'] = $bookingpress_expire_month_text;
			$bookingpress_front_vue_data_fields['expire_year_text'] = $bookingpress_expire_year_text;
			$bookingpress_front_vue_data_fields['cvv_text'] = $bookingpress_cvv_text;   */
			$bookingpress_front_vue_data_fields['months'] = array(
				array( 'month' => '01' ),
				array( 'month' => '02' ),
				array( 'month' => '03' ),
				array( 'month' => '04' ),
				array( 'month' => '05' ),
				array( 'month' => '06' ),
				array( 'month' => '07' ),
				array( 'month' => '08' ),
				array( 'month' => '09' ),
				array( 'month' => '10' ),
				array( 'month' => '11' ),
				array( 'month' => '12' ),
			);
			$bookingpress_front_vue_data_fields['years']        = array();
			$bookingpress_front_vue_data_fields['timeToExpire'] = 9;
			$bookingpress_front_vue_data_fields['cardVadid']    = '';
			$bookingpress_front_vue_data_fields['cardType']     = '';
			$bookingpress_front_vue_data_fields['cClass']       = '';
			$bookingpress_front_vue_data_fields['cardHolder']   = '';
			$bookingpress_front_vue_data_fields['regx']         = array(
				array(
					'name' => 'Visa',
					'logo' => 'https://seeklogo.com/images/V/visa-logo-CF29426B98-seeklogo.com.png',
					're'   => '^4',
				),
				array(
					'name' => 'Hipercard',
					'logo' => 'https://cdn.worldvectorlogo.com/logos/hipercard.svg',
					're'   => '/^(606282\d{10}(\d{3})?)|(3841\d{15})$/',
				),
				array(
					'name' => 'MasterCard',
					'logo' => 'https://logodownload.org/wp-content/uploads/2014/07/mastercard-logo-novo-3.png',
					're'   => '/^(5[1-5]|677189)|^(222[1-9]|2[3-6]\d{2}|27[0-1]\d|2720)/',
				),
				array(
					'name' => 'Discover',
					'logo' => 'https://i.pinimg.com/originals/b3/d7/85/b3d7853a11dcc8c424866915ddd4d3e3.png',
					're'   => '/^(6011|65|64[4-9]|622)/',
				),
				array(
					'name' => 'Elo',
					'logo' => 'https://seeklogo.com/images/E/elo-logo-0B17407ECC-seeklogo.com.png',
					're'   => '/^(4011(78|79)|43(1274|8935)|45(1416|7393|763(1|2))|50(4175|6699|67[0-7][0-9]|9000)|627780|63(6297|6368)|650(03([^4])|04([0-9])|05(0|1)|4(0[5-9]|3[0-9]|8[5-9]|9[0-9])|5([0-2][0-9]|3[0-8])|9([2-6][0-9]|7[0-8])|541|700|720|901)|651652|655000|655021)/',
				),
				array(
					'name' => 'American Express',
					'logo' => 'https://ccard-generator.com/assets/images/cardmedium/american-express.png',
					're'   => '/^3[47]\d{13,14}$/',
				),
			);

            $package_label_txt = $BookingPress->bookingpress_get_customize_settings('package_tax_title', 'package_booking_form');
            $bookingpress_front_vue_data_fields['package_tax_title'] = stripslashes_deep($package_label_txt);            
            $bookingpress_front_vue_data_fields['bookingpress_package_external_html'] = '';
            $default_booking_page = $BookingPress->bookingpress_get_customize_settings('default_package_booking_page', 'booking_form');

            $bookingpress_front_vue_data_fields['bookingpress_default_appointment_booking_page'] = $default_booking_page;
            $bookingpress_default_appointment_booking_page_url = $BookingPress->bookingpress_get_customize_settings('package_appointment_book_redirect', 'package_booking_form');
            if(empty($bookingpress_default_appointment_booking_page_url)){
                $bookingpress_default_appointment_booking_page_url = '';
                $default_appointment_booking_page = $BookingPress->bookingpress_get_customize_settings('default_booking_page', 'booking_form');
                if(!empty($default_appointment_booking_page)){                
                    $bookingpress_default_appointment_booking_page_url = get_permalink($default_appointment_booking_page);
                }
            }

            $package_order_payment_failed_message = $BookingPress->bookingpress_get_customize_settings('package_order_payment_failed_message', 'package_booking_form');            
            $bookingpress_front_vue_data_fields['package_payment_failed'] = 0;    
            $bookingpress_front_vue_data_fields['package_order_payment_failed_message'] = $package_order_payment_failed_message; 


            $bookingpress_front_vue_data_fields['bookingpress_default_appointment_booking_page_url'] = $bookingpress_default_appointment_booking_page_url;
            $bookingpress_front_vue_data_fields['bookingpress_default_appointment_booking_page_url_org'] = $bookingpress_default_appointment_booking_page_url;

            $bookingpress_front_vue_data_fields = apply_filters('bookingpress_frontend_package_order_form_add_dynamic_data', $bookingpress_front_vue_data_fields);            
            $bookingpress_dynamic_data_fields = wp_json_encode($bookingpress_front_vue_data_fields);

            return $bookingpress_dynamic_data_fields;            
        }

		function boookingpress_get_visitor_ip() {
			if ( isset( $_SERVER['HTTP_CF_CONNECTING_IP'] ) ) {
				$_SERVER['REMOTE_ADDR']    = ! empty( $_SERVER['HTTP_CF_CONNECTING_IP'] ) ? sanitize_text_field( $_SERVER['HTTP_CF_CONNECTING_IP'] ) : '';
				$_SERVER['HTTP_CLIENT_IP'] = ! empty( $_SERVER['HTTP_CF_CONNECTING_IP'] ) ? sanitize_text_field( $_SERVER['HTTP_CF_CONNECTING_IP'] ) : '';
			}

			$client  = ! empty( $_SERVER['HTTP_CLIENT_IP'] ) ? sanitize_text_field( $_SERVER['HTTP_CLIENT_IP'] ) : '';
			$forward = ! empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ? sanitize_text_field( $_SERVER['HTTP_X_FORWARDED_FOR'] ) : '';
			$remote  = ! empty( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( $_SERVER['REMOTE_ADDR'] ) : '';

			if ( filter_var( $client, FILTER_VALIDATE_IP ) ) {
				$ip = $client;
			} elseif ( filter_var( $forward, FILTER_VALIDATE_IP ) ) {
				$ip = $forward;
			} else {
				$ip = $remote;
			}

			return $ip;
		}

        function get_bookingpress_package_login_user_data(){
            global $tbl_bookingpress_customers, $wpdb;
            $bookingpress_package_login_user_data = array();
            if (is_user_logged_in() ) {
                $current_user_id               = get_current_user_id();
                $bookingpress_current_user_obj = new WP_User($current_user_id);                
                $get_current_user_data = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$tbl_bookingpress_customers} WHERE bookingpress_wpuser_id = %d", $current_user_id ), ARRAY_A); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- Reason: $tbl_bookingpress_customers is table name defined globally. False Positive alarm  
                if (! empty($get_current_user_data) ) {

                    $bookingpress_firstname = stripslashes_deep($get_current_user_data['bookingpress_user_firstname']);
                    $bookingpress_lastname = stripslashes_deep($get_current_user_data['bookingpress_user_lastname']);
                    $bookingpress_customername = !empty($get_current_user_data['bookingpress_user_name']) ? stripslashes_deep($get_current_user_data['bookingpress_user_name']) : '';
                    $bookingpress_customername_full_name = !empty($get_current_user_data['bookingpress_customer_full_name']) ? stripslashes_deep($get_current_user_data['bookingpress_customer_full_name']) : '';

                    $customer_username  = ! empty($bookingpress_current_user_obj->data->user_login) ? $bookingpress_current_user_obj->data->user_login : '';

                    $bookingpress_package_login_user_data = array(
                        'bookingpress_customer_id' => $get_current_user_data['bookingpress_customer_id'],
                        'customer_name' => stripslashes_deep($bookingpress_customername_full_name),
                        'customer_username' => stripslashes_deep($customer_username),
                        'customer_phone' => $get_current_user_data['bookingpress_user_phone'],
                        'customer_email' => stripslashes_deep($get_current_user_data['bookingpress_user_email']),
                        'customer_firstname' => stripslashes_deep($bookingpress_firstname),
                        'customer_lastname' => stripslashes_deep($bookingpress_lastname),
                        'customer_email' => stripslashes_deep($get_current_user_data['bookingpress_user_email']), 
                        'customer_phone_country' => stripslashes_deep($get_current_user_data['bookingpress_user_country_dial_code']),                        
                        'customer_phone_country' => stripslashes_deep($get_current_user_data['bookingpress_user_country_dial_code']),
                        'customer_phone_country' => stripslashes_deep($get_current_user_data['bookingpress_user_country_dial_code']),
                    );

                    if(empty($bookingpress_package_login_user_data['customer_email'])){                                                
                        $bookingpress_customer_email = ! empty($bookingpress_current_user_obj->data->user_email) ? $bookingpress_current_user_obj->data->user_email : '';
                        $bookingpress_package_login_user_data['customer_email'] = $bookingpress_customer_email;
                    }

                    if(empty($bookingpress_package_login_user_data['customer_firstname'])){ 
                        $bookingpress_firstname      = get_user_meta($current_user_id, 'first_name', true);                        
                        $bookingpress_package_login_user_data['customer_firstname'] = $bookingpress_firstname;
                    }

                    if(empty($bookingpress_package_login_user_data['customer_lastname'])){ 
                        $bookingpress_lastname       = get_user_meta($current_user_id, 'last_name', true);
                        $bookingpress_package_login_user_data['customer_lastname'] = $bookingpress_lastname;
                    }

                    if(empty($bookingpress_package_login_user_data['customer_name'])){ 
                        $bookingpress_firstname      = get_user_meta($current_user_id, 'first_name', true);
                        $bookingpress_lastname       = get_user_meta($current_user_id, 'last_name', true);
                        $bookingpress_package_login_user_data['customer_name'] = $bookingpress_firstname.' '.$bookingpress_lastname;
                    }                    

                } elseif (! empty($current_user_id) && ! empty($bookingpress_current_user_obj) ) {
                    
                    
                    $customer_username  = ! empty($bookingpress_current_user_obj->data->user_login) ? $bookingpress_current_user_obj->data->user_login : '';
                    $bookingpress_customer_name  = ! empty($bookingpress_current_user_obj->data->user_login) ? $bookingpress_current_user_obj->data->user_login : '';
                    $bookingpress_customer_email = ! empty($bookingpress_current_user_obj->data->user_email) ? $bookingpress_current_user_obj->data->user_email : '';
                    $bookingpress_firstname      = get_user_meta($current_user_id, 'first_name', true);
                    $bookingpress_lastname       = get_user_meta($current_user_id, 'last_name', true);
                    $bookingpress_customername = $bookingpress_firstname.' '.$bookingpress_lastname;

                    $bookingpress_package_login_user_data = array(
                        'bookingpress_customer_id' => '',
                        'customer_name' => stripslashes_deep($bookingpress_firstname).' '.stripslashes_deep($bookingpress_lastname),
                        'customer_username' => stripslashes_deep($customer_username),                        
                        'customer_email' => stripslashes_deep($bookingpress_customer_email),
                        'customer_firstname' => stripslashes_deep($bookingpress_firstname),
                        'customer_lastname' => stripslashes_deep($bookingpress_lastname),
                                               
                    );                    

                    

                }                
            }
            return $bookingpress_package_login_user_data;
        }
        
        /**
         * Function for get front side packages
         *
         * @return void
         */
        function bookingpress_get_front_packages_func($return_data = false){
            
            global $wpdb,$tbl_bookingpress_packages,$tbl_bookingpress_services, $tbl_bookingpress_package_services, $tbl_bookingpress_package_images, $BookingPress,$bookingpress_package, $BookingPress,$bookingpress_global_options,$BookingPressPro;
            $response              = array();
            $wpnonce               = isset($_REQUEST['_wpnonce']) ? sanitize_text_field($_REQUEST['_wpnonce']) : '';
            $bpa_verify_nonce_flag = wp_verify_nonce($wpnonce, 'bpa_wp_nonce');
            if (! $bpa_verify_nonce_flag ) {
                $response['variant'] = 'error';
                $response['title']   = esc_html__('Error', 'bookingpress-package');
                $response['msg']     = esc_html__('Sorry, Your request can not be processed due to security reason.', 'bookingpress-package');
                if($return_data){
                    return $response;
                }
                wp_send_json($response);
                die();
            } 
            $bookingpress_total_packages = 0;
            $perpage     = isset($_POST['perpage']) ? intval($_POST['perpage']) : 6;
            $package_hide_package_pagination = $BookingPress->bookingpress_get_customize_settings('hide_package_pagination', 'package_booking_form');
            if($package_hide_package_pagination != "true"){
                $perpage = 60;
            }
            $bookingpress_search_data        = ! empty($_REQUEST['search_data']) ? array_map(array( $BookingPress, 'appointment_sanatize_field' ), $_REQUEST['search_data']) : array(); // phpcs:ignore
            $currentpage = isset($_POST['currentpage']) ? intval($_POST['currentpage']) : 1;
            $offset      = ( ! empty($currentpage) && $currentpage > 1 ) ? ( ( $currentpage - 1 ) * $perpage ) : 0;
            $bookingpress_search_query_where = " AND bookingpress_package_status = 1 ";            
            if(isset($bookingpress_search_data['package_name']) && !empty($bookingpress_search_data['package_name'])){
                $bookingpress_search_string = $bookingpress_search_data['package_name'];
                $bookingpress_search_query_where .= "AND (bookingpress_package_name LIKE '%{$bookingpress_search_string}%' OR bookingpress_package_description LIKE '%{$bookingpress_search_string}%') ";
            }            
            $bookingpress_global_data = $bookingpress_global_options->bookingpress_global_options();
            $bookingpress_date_format = $bookingpress_global_data['wp_default_date_format'];
            $bookingpress_time_format = $bookingpress_global_data['wp_default_time_format'];            
            //if(!empty($bookingpress_search_query_where)){
                $bookingpress_search_query = 'WHERE 1=1';   
            //}

            $select_default_package = isset($_REQUEST['default_package']) ? sanitize_text_field($_REQUEST['default_package']) : '';
            if(!empty($select_default_package)){
                $bookingpress_search_query .= ' AND bookingpress_package_id IN ('.$select_default_package.') ';
            }
            $bookingpress_total_pakages = $wpdb->get_var("SELECT COUNT(bookingpress_package_id) FROM {$tbl_bookingpress_packages} {$bookingpress_search_query} {$bookingpress_search_query_where}"); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared --Reason: $tbl_bookingpress_packages is a table name. false alarm            
            $packages_data = $wpdb->get_results("SELECT * FROM {$tbl_bookingpress_packages} {$bookingpress_search_query}   {$bookingpress_search_query_where} ORDER BY bookingpress_package_position ASC LIMIT {$offset} , {$perpage}", ARRAY_A); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared --Reason: $tbl_bookingpress_packages is a table name. false alarm

            $package_list = array();
            if(!empty($packages_data) && is_array($packages_data) ){                
                foreach($packages_data as $package){                    
                    $package['bookingpress_package_description'] = (!empty($package['bookingpress_package_description']))?stripslashes_deep($package['bookingpress_package_description']):'';
                    $package['bookingpress_package_name'] = (!empty($package['bookingpress_package_name']))?stripslashes_deep($package['bookingpress_package_name']):'';

                    $bookingpress_package_id = $package['bookingpress_package_id'];
                    $package['formatted_package_price']   = $BookingPress->bookingpress_price_formatter_with_currency_symbol($package['bookingpress_package_price']);
                    $package['formatted_retail_price']    = $BookingPress->bookingpress_price_formatter_with_currency_symbol($package['bookingpress_package_calculated_price']);
                    $bookingpress_package_id = $package['bookingpress_package_id'];                    
                    $package['package_duration'] = $this->get_package_duration_limit_text($package['bookingpress_package_duration'],$package['bookingpress_package_duration_unit']);                                         
                    $bookingperss_package_services = $wpdb->get_results( $wpdb->prepare( "SELECT bpackserv.bookingpress_service_id,bpackserv.bookingpress_no_of_appointments, bserv.bookingpress_service_name,bserv.bookingpress_service_duration_val,bserv.bookingpress_service_duration_unit FROM {$tbl_bookingpress_package_services} bpackserv INNER JOIN {$tbl_bookingpress_services} bserv ON bpackserv.bookingpress_service_id = bserv.bookingpress_service_id WHERE bpackserv.bookingpress_package_id = %d", $bookingpress_package_id), ARRAY_A ); // phpcs:ignore
                    $package_service_detail = array();
                    foreach($bookingperss_package_services as $key=>$package_service){
                        $package_service_duration = $bookingpress_package->bookingpress_get_service_duration_text($package_service['bookingpress_service_duration_val'],$package_service['bookingpress_service_duration_unit']);
                        $bookingperss_package_services[$key]['service_duration'] = $package_service_duration;  
                        if($bookingpress_package->is_multi_language_addon_active()){
                            if(method_exists( $BookingPressPro, 'bookingpress_pro_front_language_translation_func') ) {
                                $bookingpress_service_name = $package_service['bookingpress_service_name'];                                                               
                                $bookingpress_service_id = $package_service['bookingpress_service_id'];
                                $bookingperss_package_services[$key]['bookingpress_service_name']  = $BookingPressPro->bookingpress_pro_front_language_translation_func($bookingpress_service_name,'service','bookingpress_service_name',$bookingpress_service_id);
                            }
                        }
                    }                    
                    if($bookingpress_package->is_multi_language_addon_active()){
                        if(method_exists( $BookingPressPro, 'bookingpress_pro_front_language_translation_func') ) {
                            $package['bookingpress_package_name'] = $BookingPressPro->bookingpress_pro_front_language_translation_func($package['bookingpress_package_name'],'package','bookingpress_package_name',$bookingpress_package_id);
                            $package['bookingpress_package_description'] = $BookingPressPro->bookingpress_pro_front_language_translation_func($package['bookingpress_package_description'],'package','bookingpress_package_description',$bookingpress_package_id);
                        }
                    }
                    $package['package_services'] = $bookingperss_package_services;
                    $package['package_services_expanded'] = 'false';
                    $bookingperss_package_images = $wpdb->get_results( $wpdb->prepare( "SELECT bookingpress_package_img_url,bookingpress_package_img_name FROM {$tbl_bookingpress_package_images} WHERE bookingpress_package_id = %d", $bookingpress_package_id ), ARRAY_A ); //phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared --Reason $tbl_bookingpress_package_images is a table name.
                    $package['package_images'] = $bookingperss_package_images;
                    $package_desc_length = strlen( $package['bookingpress_package_description'] );
                    $package['bookingpress_package_desc_excerpt'] = ( 240 < $package_desc_length ) ? substr( $package['bookingpress_package_description'], 0, 236 ) . '....' : $package['bookingpress_package_description'];
                    $package['bookingpress_package_desc_show_more'] = ( 240 < $package_desc_length )  ? true : false;
                    $package['bookingpress_package_desc_show_more_active'] = ( 240 < $package_desc_length )  ? true : false;
                    $package['bookingpress_package_desc_show_less'] = false;                    
                    $package_list[] = $package;
                    
                }
            }

            $data['package_list'] = $package_list;
            $data['total_records'] = $bookingpress_total_pakages;
            if($return_data){
                return $data;
            }                 
            wp_send_json($data);
            exit;

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
            global $BookingPress;
            $duration_data = '';
            $duration_unit_text = '';
            $package_month_text = $BookingPress->bookingpress_get_customize_settings('package_month_text', 'package_booking_form');
            $package_months_text = $BookingPress->bookingpress_get_customize_settings('package_months_text', 'package_booking_form');
            $package_year_text = $BookingPress->bookingpress_get_customize_settings('package_year_text', 'package_booking_form');
            $package_day_text = $BookingPress->bookingpress_get_customize_settings('package_day_text', 'package_booking_form');
            $package_days_text = $BookingPress->bookingpress_get_customize_settings('package_days_text', 'package_booking_form');

            if($bookingpress_package_duration == 1){                
                if($bookingpress_package_duration_unit == 'd'){
                    $duration_unit_text = $package_day_text;
                }else if($bookingpress_package_duration_unit == 'm'){
                    $duration_unit_text = $package_month_text;
                }else if($bookingpress_package_duration_unit == 'y'){
                    $duration_unit_text = $package_year_text;
                }                
            }else{
                if($bookingpress_package_duration_unit == 'd'){
                    $duration_unit_text = $package_days_text;
                }else if($bookingpress_package_duration_unit == 'm'){
                    $duration_unit_text = $package_months_text;
                }else if($bookingpress_package_duration_unit == 'y'){
                    $duration_unit_text = $package_year_text;
                } 
            }
            $duration_data = $bookingpress_package_duration.' '.$duration_unit_text;
            return $duration_data;
        }

        /**
         * Fetch all packages
         *
         * @param  mixed $service [bookingpress_pakage_form package=1]
         * @param  mixed $selected_service [bookingpress_pakage_form selected_package=1]
         * @return array
        */
        function bookingpress_has_package_avaliable_front(){
            global $wpdb,$tbl_bookingpress_packages,$tbl_bookingpress_services, $tbl_bookingpress_package_services, $tbl_bookingpress_package_images, $BookingPress,$bookingpress_package;
            $package_details = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$tbl_bookingpress_packages} WHERE bookingpress_package_status = %d",1), ARRAY_A ); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- Reason: $tbl_bookingpress_packages is table name defined globally.
            if(empty($package_details)){
                return false;
            }else{
                return true;
            }
            return false;
        }

        /**
         * Fetch all packages
         *
         * @param  mixed $service [bookingpress_pakage_form package=1]
         * @param  mixed $selected_service [bookingpress_pakage_form selected_package=1]
         * @return array
        */
        function bookingpress_retrieve_all_packages($package, $selected_package){
            
            global $wpdb,$tbl_bookingpress_packages,$tbl_bookingpress_services, $tbl_bookingpress_package_services, $tbl_bookingpress_package_images, $BookingPress,$bookingpress_package;
            $package_list = array();
            return $package_list;
            $package_details = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$tbl_bookingpress_packages} WHERE bookingpress_package_status = %d limit 10",1), ARRAY_A ); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- Reason: $tbl_bookingpress_packages is table name defined globally.
            if(!empty($package_details)){
                foreach($package_details as $package){

                    $package['formatted_package_price']   = $BookingPress->bookingpress_price_formatter_with_currency_symbol($package['bookingpress_package_price']);
                    $package['formatted_retail_price']    = $BookingPress->bookingpress_price_formatter_with_currency_symbol($package['bookingpress_package_calculated_price']);
                    $bookingpress_package_id = $package['bookingpress_package_id'];
                    
                    $get_package['package_duration'] = $bookingpress_package->get_package_duration_limit_text($package['bookingpress_package_duration'],$package['bookingpress_package_duration_unit']);                                         

                    $bookingperss_package_services = $wpdb->get_results( $wpdb->prepare( "SELECT bpackserv.bookingpress_no_of_appointments, bserv.bookingpress_service_name,bserv.bookingpress_service_duration_val,bserv.bookingpress_service_duration_unit FROM {$tbl_bookingpress_package_services} bpackserv INNER JOIN {$tbl_bookingpress_services} bserv ON bpackserv.bookingpress_service_id = bserv.bookingpress_service_id WHERE bpackserv.bookingpress_package_id = %d", $bookingpress_package_id), ARRAY_A ); // phpcs:ignore
                    $package_service_detail = array();
                    foreach($bookingperss_package_services as $key=>$package_service){
                        $package_service_duration = $bookingpress_package->bookingpress_get_service_duration_text($package_service['bookingpress_service_duration_val'],$package_service['bookingpress_service_duration_unit']);
                        $bookingperss_package_services[$key]['service_duration'] = $package_service_duration;    
                    } 
                    $package['package_services'] = $bookingperss_package_services;
                    $bookingperss_package_images = $wpdb->get_results( $wpdb->prepare( "SELECT bookingpress_package_img_url,bookingpress_package_img_name FROM {$tbl_bookingpress_package_images} WHERE bookingpress_package_id = %d", $bookingpress_package_id), ARRAY_A ); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- Reason: $tbl_bookingpress_package_images is table name defined globally.
                    $package['package_images'] = $bookingperss_package_images;
                    $package_list[] = $package;

                }
            }            
            return $package_list;

        }

        /**
         * Load frontside JS
         *
         * @param  mixed $force_enqueue
         * @return void
         */
        function set_front_js( $force_enqueue = 0 ){
            
            global $wpdb, $tbl_bookingpress_form_fields, $wp_version, $BookingPress;
            
            wp_register_script('bookingpress_vue_js', BOOKINGPRESS_URL . '/js/bookingpress_vue.min.js', array(), BOOKINGPRESS_VERSION, true);
            wp_register_script('bookingpress_axios_js', BOOKINGPRESS_URL . '/js/bookingpress_axios.min.js', array(), BOOKINGPRESS_VERSION, true);
            wp_register_script('bookingpress_element_js', BOOKINGPRESS_URL . '/js/bookingpress_element.js', array(), BOOKINGPRESS_VERSION, true);
            wp_register_script('bookingpress_wordpress_vue_helper_js', BOOKINGPRESS_URL . '/js/bookingpress_wordpress_vue_qs_helper.js', array(), BOOKINGPRESS_VERSION, true);
            wp_register_script('bookingpress_moment_js', BOOKINGPRESS_URL . '/js/bookingpress_moment.min.js', array(), BOOKINGPRESS_VERSION, true);
            wp_register_script('bookingpress_tel_input_js', BOOKINGPRESS_URL . '/js/bookingpress_tel_input.js', array(), BOOKINGPRESS_VERSION, true);
            wp_register_script('bookingpress_tel_utils_js', BOOKINGPRESS_URL . '/js/bookingpress_tel_utils.js', array(), BOOKINGPRESS_VERSION, true );

            if( version_compare( $wp_version, '5.0', '<' ) ){
                wp_register_script( 'wp-hooks', BOOKINGPRESS_URL . '/js/hooks.js', array(), BOOKINGPRESS_VERSION, true );
            }
            $bookingress_load_js_css_all_pages = $BookingPress->bookingpress_get_settings('load_js_css_all_pages', 'general_setting');

            if ($BookingPress->bookingpress_is_front_page() || ( $bookingress_load_js_css_all_pages == 'true' ) || ( $force_enqueue == 1 ) ) {

                $get_already_loaded_vue_setting_val = $BookingPress->bookingpress_get_settings('use_already_loaded_vue', 'general_setting');
                if (! $get_already_loaded_vue_setting_val || $get_already_loaded_vue_setting_val == 'false' ) {
                    wp_enqueue_script('bookingpress_vue_js');
                }
                wp_enqueue_script('wp-hooks');
                wp_enqueue_script('bookingpress_axios_js');
                wp_enqueue_script('bookingpress_wordpress_vue_helper_js');
                wp_enqueue_script('bookingpress_element_js');

                wp_enqueue_script('bookingpress_moment_js');                

                $bpa_phone_number_field_detail = wp_cache_get( 'bookingpress_phone_field_data' );
                if( false === $bpa_phone_number_field_detail ){
                    $bookingpress_form_field_data = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$tbl_bookingpress_form_fields} WHERE bookingpress_form_field_name = %s", 'phone_number'), ARRAY_A);// phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- Reason: $tbl_bookingpress_form_fields is table name defined globally. False alarm
                    wp_cache_set( 'bookingpress_phone_field_data', $bookingpress_form_field_data );
                } else {
                    $bookingpress_form_field_data = $bpa_phone_number_field_detail;
                }

                $bookingpress_is_field_hide = isset($bookingpress_form_field_data['bookingpress_field_is_hide']) ? intval($bookingpress_form_field_data['bookingpress_field_is_hide']) : 1;
                    wp_enqueue_script('bookingpress_tel_input_js');
                    wp_enqueue_script('bookingpress_tel_utils_js');

                global $bookingpress_global_options;
                $bookingpress_site_current_language = $bookingpress_global_options->bookingpress_get_site_current_language();

                if ($bookingpress_site_current_language != 'en' ) {
                    wp_register_script('bookingpress_elements_locale', BOOKINGPRESS_URL . '/js/elements_locale/' . $bookingpress_site_current_language . '.js', array(), BOOKINGPRESS_VERSION, true);
                    wp_enqueue_script('bookingpress_elements_locale');                    
                } else {
                    wp_register_script('bookingpress_elements_locale', BOOKINGPRESS_URL . '/js/bookingpress_element_en.js', array(), BOOKINGPRESS_VERSION, true);
                    wp_enqueue_script('bookingpress_elements_locale');
                }

                $data = 'var appoint_ajax_obj = '.json_encode( array(
                        'ajax_url' => admin_url( 'admin-ajax.php')
                    )
                ).';';
                wp_add_inline_script('bookingpress_vue_js', $data, 'before');                
            }


        }

        /**
         * Set front CSS
         *
         * @param  mixed $force_enqueue
         * @return void
         */
        function set_front_css( $force_enqueue = 0 ){

            global $wpdb, $tbl_bookingpress_form_fields,$BookingPress;
            wp_register_style('bookingpress_element_css', BOOKINGPRESS_URL . '/css/bookingpress_element_theme.css', array(), BOOKINGPRESS_VERSION);

            wp_register_style('bookingpress_fonts_css', BOOKINGPRESS_URL . '/css/fonts/fonts.css', array(), BOOKINGPRESS_VERSION);
            wp_register_style('bookingpress_tel_input', BOOKINGPRESS_URL . '/css/bookingpress_tel_input.css', array(), BOOKINGPRESS_VERSION);

            wp_register_style('bookingpress_package_front_css', BOOKINGPRESS_PACKAGE_URL . '/css/bookingpress_package_front.css', array(), BOOKINGPRESS_PACKAGE_VERSION);
            wp_register_style('bookingpress_package_front_rtl_css', BOOKINGPRESS_PACKAGE_URL . '/css/bookingpress_package_front_rtl.css', array(), BOOKINGPRESS_PACKAGE_VERSION);

            $bookingress_load_js_css_all_pages = $BookingPress->bookingpress_get_settings('load_js_css_all_pages', 'general_setting');
            if ($BookingPress->bookingpress_is_front_page() || ( $bookingress_load_js_css_all_pages == 'true' ) || ( $force_enqueue == 1 )  ) {

                wp_enqueue_style('bookingpress_element_css');
                wp_enqueue_style('bookingpress_fonts_css');
                wp_enqueue_style('bookingpress_package_front_css');

                $this->bookingpress_load_package_front_custom_css();

                $bpa_phone_number_field_detail = wp_cache_get( 'bookingpress_phone_field_data' );

                if( false === $bpa_phone_number_field_detail ){
                    $bookingpress_form_field_data = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$tbl_bookingpress_form_fields} WHERE bookingpress_form_field_name = %s", 'phone_number'), ARRAY_A);// phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- Reason: $tbl_bookingpress_form_fields is table name defined globally. False Positive alarm
                    wp_cache_set( 'bookingpress_phone_field_data', $bookingpress_form_field_data );
                } else {
                    $bookingpress_form_field_data = $bpa_phone_number_field_detail;
                }                    
                $bookingpress_is_field_hide = isset($bookingpress_form_field_data['bookingpress_field_is_hide']) ? intval($bookingpress_form_field_data['bookingpress_field_is_hide']) : 1;
                    wp_enqueue_style('bookingpress_tel_input');
                if (is_rtl() ) {
                    wp_enqueue_style('bookingpress_package_front_rtl_css');
                }
                if($bookingress_load_js_css_all_pages == 'true' ) {
                    //load custom css & js here
                }
                                
            }

        }

                
        /**
         * Package Booking Shortcode 
         *
         * @param  mixed $atts
         * @param  mixed $content
         * @param  mixed $tag
         * @return void
         */
        function bookingpress_package_front_booking_form($atts, $content, $tag){
            global $wpdb, $BookingPress, $bookingpress_front_vue_data_fields,$bookingpress_global_options,$tbl_bookingpress_form_fields,$tbl_bookingpress_customers,$bookingpress_common_datetime_format, $wp;

            $defaults = array(
                'package'  => 0,
                'selected_package' => 0,
            );
            $args = shortcode_atts($defaults, $atts, $tag);
            if( !empty( $atts ) ){
                if( !empty( $atts['package'] ) && !preg_match( '/^[(\d+)\,]+$/', $atts['package'] ) ){
                    $atts['package'] = '';
                }                                            
                $atts['selected_package'] = !empty( $atts['selected_package'] ) ? intval( $atts['selected_package'] ) : '';
            }            
            extract($args);
            $Bookingpress_package  = 0;    
            if(!empty($package) && $package != 0){
                $Bookingpress_package            = $package;
                $this->bookingpress_form_package = $package;
            }
            if( !empty( $atts['selected_package'] ) ){
                $this->bookingpress_selected_package_param = true;
            }

            /** Set flag to display no service placeholder */
            $bookingpress_display_no_package_placeholder = false;
            $bpa_all_packages = $this->bookingpress_retrieve_all_packages( $package, $selected_package );            
            if(!$this->bookingpress_has_package_avaliable_front()){
                $bookingpress_display_no_package_placeholder = true;                
            }            

            $bookingpress_front_vue_data_fields['bookingpress_display_no_package_placeholder'] = $bookingpress_display_no_package_placeholder;
            $bookingpress_front_vue_data_fields['bookingpress_all_packages_data'] = $bpa_all_packages;

            $bookingpress_global_options_arr       = $bookingpress_global_options->bookingpress_global_options();
            $bookingpress_default_date_time_format = $bookingpress_global_options_arr['wp_default_date_format'];
            
            $bookingpress_default_date_format = 'MMMM D, YYYY';
            if ($bookingpress_default_date_time_format == 'F j, Y' ) {
                $bookingpress_default_date_format = 'MMMM D, YYYY';
            } elseif ($bookingpress_default_date_time_format == 'Y-m-d' ) {
                $bookingpress_default_date_format = 'YYYY-MM-DD';
            } elseif ($bookingpress_default_date_time_format == 'm/d/Y' ) {
                $bookingpress_default_date_format = 'MM/DD/YYYY';
            } elseif($bookingpress_default_date_time_format == 'd/m/Y') {
                $bookingpress_default_date_format = 'DD/MM/YYYY';
            } elseif ($bookingpress_default_date_time_format == 'd.m.Y') {
                $bookingpress_default_date_format = 'DD.MM.YYYY';
            } elseif ($bookingpress_default_date_time_format == 'd-m-Y') {
                $bookingpress_default_date_format = 'DD-MM-YYYY';
            }            
            
            $this->bookingpress_default_date_format = $bookingpress_default_date_format;

            $bookingpress_form_fields = wp_cache_get( 'bpa_appointment_booking_form_fields_data_');               
            if( false == $bookingpress_form_fields ){
                $bookingpress_form_fields = $wpdb->get_results("SELECT * FROM {$tbl_bookingpress_form_fields} ORDER BY bookingpress_field_position ASC", ARRAY_A); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- Reason: $tbl_bookingpress_form_fields is table name defined globally. False Positive alarm
                wp_cache_set( 'bpa_appointment_booking_form_fields_data_', $bookingpress_form_fields);
            }

            $current_page_url =  home_url( $wp->request );

            $bookingpress_form_fields_error_msg_arr = $bookingpress_form_fields_new = array();
            
            $bookingpress_form_fields        = apply_filters('bookingpress_modify_field_data_before_prepare', $bookingpress_form_fields);
            
            foreach ( $bookingpress_form_fields as $bookingpress_form_field_key => $bookingpress_form_field_val ) {

                if($bookingpress_form_field_val['bookingpress_field_is_hide'] == 0) {

                    $bookingpress_v_model_value = '';
                    if ($bookingpress_form_field_val['bookingpress_form_field_name'] == 'fullname' ) {
                        $bookingpress_v_model_value = 'customer_name';
                    } elseif ($bookingpress_form_field_val['bookingpress_form_field_name'] == 'firstname' ) {
                        $bookingpress_v_model_value = 'customer_firstname';
                    } elseif ($bookingpress_form_field_val['bookingpress_form_field_name'] == 'lastname' ) {
                        $bookingpress_v_model_value = 'customer_lastname';
                    } elseif ($bookingpress_form_field_val['bookingpress_form_field_name'] == 'email_address' ) {
                        $bookingpress_v_model_value = 'customer_email';
                    } elseif ($bookingpress_form_field_val['bookingpress_form_field_name'] == 'phone_number' ) {
                        $bookingpress_v_model_value = 'customer_phone';
                    } elseif ($bookingpress_form_field_val['bookingpress_form_field_name'] == 'note' ) {
                        $bookingpress_v_model_value = 'appointment_note';
                    } elseif($bookingpress_form_field_val['bookingpress_form_field_name'] == 'username' ){
                        $bookingpress_v_model_value = 'customer_username';
                    } elseif ($bookingpress_form_field_val['bookingpress_form_field_name'] == 'terms_and_conditions' ) {
                        $bookingpress_v_model_value = 'appointment_terms_conditions';
                    } else {
                        $bookingpress_v_model_value = $bookingpress_form_field_val['bookingpress_field_meta_key'];
                    }

                    $bookingpress_front_vue_data_fields['package_step_form_data'][$bookingpress_v_model_value] = '';
                    if( 'appointment_terms_conditions' == $bookingpress_v_model_value ){
                        $bookingpress_front_vue_data_fields['package_step_form_data'][$bookingpress_v_model_value] = array();
                    }

                    $bookingpress_field_type = '';
                    if ($bookingpress_form_field_val['bookingpress_form_field_name'] == 'fullname' ) {
                        $bookingpress_field_type = 'Text';
                    } elseif ($bookingpress_form_field_val['bookingpress_form_field_name'] == 'firstname' ) {
                        $bookingpress_field_type = 'Text';
                    } elseif ($bookingpress_form_field_val['bookingpress_form_field_name'] == 'lastname' ) {
                        $bookingpress_field_type = 'Text';
                    } elseif ($bookingpress_form_field_val['bookingpress_form_field_name'] == 'email_address' ) {
                        $bookingpress_field_type = 'Email';
                    } elseif ($bookingpress_form_field_val['bookingpress_form_field_name'] == 'phone_number' ) {
                        $bookingpress_field_type = 'Dropdown';
                    } elseif ($bookingpress_form_field_val['bookingpress_form_field_name'] == 'note' ) {
                        $bookingpress_field_type = 'Textarea';
                    } elseif($bookingpress_form_field_val['bookingpress_form_field_name'] == 'username' ){
                        $bookingpress_field_type = 'Text';
                    } elseif($bookingpress_form_field_val['bookingpress_form_field_name'] == 'terms_and_conditions'){
                        $bookingpress_field_type = 'terms_and_conditions';
                    } else {
                        $bookingpress_field_type = $bookingpress_form_field_val['bookingpress_field_type'];
                    }

                    $bookingpress_field_setting_fields_tmp                   = array();
                    $bookingpress_field_setting_fields_tmp['id']             = intval($bookingpress_form_field_val['bookingpress_form_field_id']);
                    $bookingpress_field_setting_fields_tmp['field_name']     = $bookingpress_form_field_val['bookingpress_form_field_name'];
                    $bookingpress_field_setting_fields_tmp['field_type']     = $bookingpress_field_type;
                    $bookingpress_field_setting_fields_tmp['is_edit']        = false;

                    $bookingpress_field_setting_fields_tmp['is_required']    = ( $bookingpress_form_field_val['bookingpress_field_required'] == 0 ) ? false : true;
                    $bookingpress_field_setting_fields_tmp['label']          = stripslashes_deep($bookingpress_form_field_val['bookingpress_field_label']);
                    $bookingpress_field_setting_fields_tmp['placeholder']    = stripslashes_deep($bookingpress_form_field_val['bookingpress_field_placeholder']);
                    $bookingpress_field_setting_fields_tmp['error_message']  = stripslashes_deep($bookingpress_form_field_val['bookingpress_field_error_message']);
                    $bookingpress_field_setting_fields_tmp['is_hide']        = ( $bookingpress_form_field_val['bookingpress_field_is_hide'] == 0 ) ? false : true;
                    $bookingpress_field_setting_fields_tmp['field_position'] = floatval($bookingpress_form_field_val['bookingpress_field_position']);
                    $bookingpress_field_setting_fields_tmp['v_model_value']  = $bookingpress_v_model_value;

                    $bookingpress_field_setting_fields_tmp = apply_filters( 'bookingpress_arrange_form_fields_outside', $bookingpress_field_setting_fields_tmp, $bookingpress_form_field_val);

                    $bookingpress_front_vue_data_fields['package_step_form_data'] = apply_filters('bookingpress_add_package_step_form_data_filter',$bookingpress_front_vue_data_fields['package_step_form_data'],$bookingpress_field_setting_fields_tmp);
                    
                    array_push( $bookingpress_form_fields_new, $bookingpress_field_setting_fields_tmp );

                    if ($bookingpress_form_field_val['bookingpress_field_required'] == '1' ) {
                        if ($bookingpress_v_model_value == 'customer_email' ) {
                            $bookingpress_form_fields_error_msg_arr[ $bookingpress_v_model_value ] = array(
                                array(
                                'required' => true,
                                'message'  => stripslashes_deep($bookingpress_form_field_val['bookingpress_field_error_message']),
                                'trigger'  => 'blur',
                                ),
                                array(
                                'type'    => 'email',
                                'message' => esc_html__('Please enter valid email address', 'bookingpress-package'),
                                'trigger' => 'blur',
                            ),
                         );
                        } elseif( $bookingpress_v_model_value == 'appointment_terms_conditions') {                               
                            $bookingpress_form_fields_error_msg_arr[ $bookingpress_v_model_value ][] = array(
                                'required' => true,
                                'message'  => stripslashes_deep($bookingpress_form_field_val['bookingpress_field_error_message']),
                                'trigger'  => 'change',
                            ); 
                        } else {                 
                            $bookingpress_form_fields_error_msg_arr[ $bookingpress_v_model_value ][] = array(
                                'required' => true,
                                'message'  => stripslashes_deep($bookingpress_form_field_val['bookingpress_field_error_message']),
                                'trigger'  => 'blur',
                            );                                                       
                        }
                        if(isset($bookingpress_form_fields_error_msg_arr[$bookingpress_v_model_value][0]['message']) && $bookingpress_form_fields_error_msg_arr[ $bookingpress_v_model_value][0]['message'] == '') {
                            $bookingpress_form_fields_error_msg_arr[ $bookingpress_v_model_value ][0]['message'] = !empty($bookingpress_form_field_val['bookingpress_field_label']) ?  stripslashes_deep($bookingpress_form_field_val['bookingpress_field_label']).' '.__('is required','bookingpress-package') : '';
                        }           
                    }                                       
                    $bookingpress_form_fields_error_msg_arr = apply_filters( 'bookingpress_modify_form_fields_rules_arr', $bookingpress_form_fields_error_msg_arr,$bookingpress_field_setting_fields_tmp );
                }    
            }

            $this->bookingpress_form_fields_error_msg_arr = apply_filters( 'bookingpress_modify_form_fields_msg_array', $bookingpress_form_fields_error_msg_arr );                      
            $this->bookingpress_form_fields_new           = $bookingpress_form_fields_new;

	        $bookingress_load_js_css_all_pages = $BookingPress->bookingpress_get_settings('load_js_css_all_pages', 'general_setting');

            if (is_user_logged_in() ) {
                $bookingpress_wp_user_id              = get_current_user_id();
                $bookingpress_check_user_exist_or_not = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(bookingpress_customer_id) as total FROM {$tbl_bookingpress_customers} WHERE bookingpress_wpuser_id = %d AND bookingpress_user_status = 0 AND bookingpress_user_type = 0", $bookingpress_wp_user_id ));  // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- Reason: $tbl_bookingpress_customers is table name defined globally. False Positive alarm
                if ($bookingpress_check_user_exist_or_not > 0 ) {
                    $bookingpress_update_customer_data = array(
                        'bookingpress_user_status' => 1,
                        'bookingpress_user_type'   => 2,
                    );
                    $bookingpress_where_condition = array(
                        'bookingpress_wpuser_id' => $bookingpress_wp_user_id,
                    );
                    $wpdb->update($tbl_bookingpress_customers, $bookingpress_update_customer_data, $bookingpress_where_condition);
                }
            }
            
            $bookingpress_uniq_id = uniqid();
            $this->set_front_css(1);
            $this->set_front_js(1);

			/* Code for modify front shortcode data from outside start */			
            $bookingpress_class_vars_val = array(                
                'form_package' => $this->bookingpress_form_package,                
                'default_date_format' => $this->bookingpress_default_date_format,
                'default_time_format' => $this->bookingpress_default_time_format,
                'form_field_err_msg_arr' => $this->bookingpress_form_fields_error_msg_arr,
                'form_fields_new' => $this->bookingpress_form_fields_new,
                'is_package_load_from_url' => $this->bookingpress_is_package_load_from_url,
            );            
            /* Code for modify front shortcode data from outside end */
            
            ob_start();
            $bookingpress_shortcode_file_url = BOOKINGPRESS_PACKAGE_VIEWS_DIR . '/frontend/package_booking_form.php';            
            include $bookingpress_shortcode_file_url;
            $content .= ob_get_clean();

            /* Main data loading script */
            $bookingpress_global_details     = $bookingpress_global_options->bookingpress_global_options();
            $bookingpress_formatted_timeslot = $bookingpress_global_details['bpa_time_format_for_timeslot'];
            $bookingpress_wp_default_time_format = $bookingpress_global_details['wp_default_time_format'];
            
            $bookingpress_site_current_language = get_locale();
            if ($bookingpress_site_current_language == 'ru_RU' ) {
                $bookingpress_site_current_language = 'ru';
            } elseif ($bookingpress_site_current_language == 'ar' ) {
                $bookingpress_site_current_language = 'ar'; // arabic
            } elseif ($bookingpress_site_current_language == 'bg_BG' ) {
                $bookingpress_site_current_language = 'bg'; // Bulgeria
            } elseif ($bookingpress_site_current_language == 'ca' ) {
                $bookingpress_site_current_language = 'ca'; // Canada
            } elseif ($bookingpress_site_current_language == 'da_DK' ) {
                $bookingpress_site_current_language = 'da'; // Denmark
            } elseif ($bookingpress_site_current_language == 'de_DE' || $bookingpress_site_current_language == 'de_CH_informal' || $bookingpress_site_current_language == 'de_AT' || $bookingpress_site_current_language == 'de_CH' || $bookingpress_site_current_language == 'de_DE_formal' ) {
                $bookingpress_site_current_language = 'de'; // Germany
            } elseif ($bookingpress_site_current_language == 'el' ) {
                $bookingpress_site_current_language = 'el'; // Greece
            } elseif ($bookingpress_site_current_language == 'es_ES' ) {
                $bookingpress_site_current_language = 'es'; // Spain
            } elseif ($bookingpress_site_current_language == 'fr_FR' ) {
                $bookingpress_site_current_language = 'fr'; // France
            } elseif ($bookingpress_site_current_language == 'hr' ) {
                $bookingpress_site_current_language = 'hr'; // Croatia
            } elseif ($bookingpress_site_current_language == 'hu_HU' ) {
                $bookingpress_site_current_language = 'hu'; // Hungary
            } elseif ($bookingpress_site_current_language == 'id_ID' ) {
                $bookingpress_site_current_language = 'id'; // Indonesia
            } elseif ($bookingpress_site_current_language == 'is_IS' ) {
                $bookingpress_site_current_language = 'is'; // Iceland
            } elseif ($bookingpress_site_current_language == 'it_IT' ) {
                $bookingpress_site_current_language = 'it'; // Italy
            } elseif ($bookingpress_site_current_language == 'ja' ) {
                $bookingpress_site_current_language = 'ja'; // Japan
            } elseif ($bookingpress_site_current_language == 'ka_GE' ) {
                $bookingpress_site_current_language = 'ka'; // Georgia
            } elseif ($bookingpress_site_current_language == 'ko_KR' ) {
                $bookingpress_site_current_language = 'ko'; // Korean
            } elseif ($bookingpress_site_current_language == 'lt_LT' ) {
                $bookingpress_site_current_language = 'lt'; // Lithunian
            } elseif ($bookingpress_site_current_language == 'mn' ) {
                $bookingpress_site_current_language = 'mn'; // Mongolia
            } elseif ($bookingpress_site_current_language == 'nl_NL' ) {
                $bookingpress_site_current_language = 'nl'; // Netherlands
            } elseif ($bookingpress_site_current_language == 'nn_NO' ) {
                $bookingpress_site_current_language = 'no'; // Norway
            } elseif ($bookingpress_site_current_language == 'pl_PL' ) {
                $bookingpress_site_current_language = 'pl'; // Poland
            } elseif ($bookingpress_site_current_language == 'pt_BR' ) {
                $bookingpress_site_current_language = 'pt-br'; // Portuguese
            } elseif ($bookingpress_site_current_language == 'ro_RO' ) {
                $bookingpress_site_current_language = 'ro'; // Romania
            } elseif ($bookingpress_site_current_language == 'sk_SK' ) {
                $bookingpress_site_current_language = 'sk'; // Slovakia
            } elseif ($bookingpress_site_current_language == 'sl_SI' ) {
                $bookingpress_site_current_language = 'sl'; // Slovenia
            } elseif ($bookingpress_site_current_language == 'sq' ) {
                $bookingpress_site_current_language = 'sq'; // Albanian
            } elseif ($bookingpress_site_current_language == 'sr_RS' ) {
                $bookingpress_site_current_language = 'sr'; // Suriname
            } elseif ($bookingpress_site_current_language == 'sv_SE' ) {
                $bookingpress_site_current_language = 'sv'; // El Salvador
            } elseif ($bookingpress_site_current_language == 'tr_TR' ) {
                $bookingpress_site_current_language = 'tr'; // Turkey
            } elseif ($bookingpress_site_current_language == 'uk' ) {
                $bookingpress_site_current_language = 'uk'; // Ukrain
            } elseif ($bookingpress_site_current_language == 'vi' ) {
                $bookingpress_site_current_language = 'vi'; // Virgin Islands (U.S.)
            } elseif ($bookingpress_site_current_language == 'zh_CN' ) {
                $bookingpress_site_current_language = 'zh-cn'; // Chinese
            } elseif ($bookingpress_site_current_language == 'nl_BE'){
                $bookingpress_site_current_language = 'nl-be'; // Nederlands ( Belgi )
            } elseif ($bookingpress_site_current_language == 'cs_CZ'){
                $bookingpress_site_current_language = 'cs';
            }elseif ($bookingpress_site_current_language == 'pt_PT'){
                $bookingpress_site_current_language = 'pt';
            }elseif ($bookingpress_site_current_language == 'et'){
                $bookingpress_site_current_language = 'et';
            }elseif ($bookingpress_site_current_language == 'nb_NO'){
                $bookingpress_site_current_language = 'no';
            }
            elseif ($bookingpress_site_current_language == 'lv'){
                $bookingpress_site_current_language = 'lv';
            }elseif ($bookingpress_site_current_language == 'fi'){
                $bookingpress_site_current_language = 'fi'; //Finnish
            }
             else {
                $bookingpress_site_current_language = 'en';
            }            

            //$no_service_selected_for_the_booking = $BookingPress->bookingpress_get_settings('no_service_selected_for_the_booking', 'message_setting');

            $no_package_selected_for_the_booking = 'Please select pakage.';
            $bookingpress_script_return_data = '';            

            $bookingpress_front_booking_dynamic_helper_vars = '';
            $bookingpress_front_booking_dynamic_helper_vars = apply_filters('bookingpress_package_front_booking_dynamic_helper_vars', $bookingpress_front_booking_dynamic_helper_vars);

            $bookingpress_dynamic_directive_data = '';
            $bookingpress_dynamic_directive_data = apply_filters('bookingpress_package_front_booking_dynamic_directives', $bookingpress_dynamic_directive_data);

            $bookingpress_dynamic_data_fields = '';
            $bookingpress_dynamic_data_fields = apply_filters('bookingpress_package_front_booking_dynamic_data_fields', $bookingpress_dynamic_data_fields, $this->bookingpress_form_package ,$selected_package);
            
            $bookingpress_dynamic_on_load_methods_data = '';
            $bookingpress_dynamic_on_load_methods_data = apply_filters('bookingpress_package_front_booking_dynamic_on_load_methods', $bookingpress_dynamic_on_load_methods_data);            

            $bookingpress_vue_methods_data = '';
            $bookingpress_vue_methods_data = apply_filters('bookingpress_package_front_booking_dynamic_vue_methods', $bookingpress_vue_methods_data);
            
            if (! empty($bookingpress_front_booking_dynamic_helper_vars) ) {
                $bookingpress_script_return_data .= $bookingpress_front_booking_dynamic_helper_vars;
            }
            
            $bookingpress_script_return_data .= "var bookingpress_uniq_id_js_var = '" . $bookingpress_uniq_id . "';";

            $bookingpress_nonce = esc_html(wp_create_nonce('bpa_wp_nonce'));

            $bookingpress_site_date = date('Y-m-d H:i:s', current_time( 'timestamp') );
            $bookingpress_site_date = apply_filters( 'bookingpress_modify_current_date', $bookingpress_site_date );
            if( !empty( $bookingpress_site_date ) ){
                $bookingpress_site_current_date = date( 'Y-m-d', strtotime( $bookingpress_site_date ) ) . ' 00:00:00';
            } else {
                $bookingpress_site_current_date = "";
            }
            $bookingpress_site_date = str_replace('-', '/', $bookingpress_site_date);

            $bpa_allow_modify_from_url = !empty($_GET['allow_modify']) ? 1 : 0;
            
            if( ( isset($_GET['bpservice_id']) ) || isset($_GET['s_id']) ){
                $this->bookingpress_is_package_load_from_url = 1;
            }
            if( 1 == $this->bookingpress_is_package_load_from_url ){
                if( empty( $selected_service ) ){
                    $this->bookingpress_is_package_load_from_url = 0;
                }
            }
            $first_day_of_week = (int)  $bookingpress_global_options_arr['start_of_week'];
            $first_day_of_week_inc = $first_day_of_week + 1;
	    
	        $bookingpress_site_current_lang_moment_locale = get_locale(); 
            if($bookingpress_site_current_lang_moment_locale == "am" || $bookingpress_site_current_lang_moment_locale == "ary" || $bookingpress_site_current_lang_moment_locale == "skr") {
                $bookingpress_site_current_lang_moment_locale = "ar";
            }else if( $bookingpress_site_current_lang_moment_locale == "azb" ) {
                $bookingpress_site_current_lang_moment_locale = "fa_AF";
            }else if( $bookingpress_site_current_lang_moment_locale == "dsb" || $bookingpress_site_current_lang_moment_locale == "hsb" || $bookingpress_site_current_lang_moment_locale == "szl" ) {
                $bookingpress_site_current_lang_moment_locale = "pl";
            }else if( $bookingpress_site_current_lang_moment_locale == "fur" ) {
                $bookingpress_site_current_lang_moment_locale = "it";
            }else if ( $bookingpress_site_current_lang_moment_locale == "ckb" ) {
                $bookingpress_site_current_lang_moment_locale = "ku";
            }else if ( $bookingpress_site_current_lang_moment_locale == "oci" ) {
                $bookingpress_site_current_lang_moment_locale = "ca";
            }else if ( $bookingpress_site_current_lang_moment_locale == "sah" ) {
                $bookingpress_site_current_lang_moment_locale = "ky";
            }else if ( $bookingpress_site_current_lang_moment_locale == "tl" ) {
                $bookingpress_site_current_lang_moment_locale = "fil";
            }else if ( $bookingpress_site_current_lang_moment_locale == "as" ) {
                $bookingpress_site_current_lang_moment_locale = "bn";
            }else if ( $bookingpress_site_current_lang_moment_locale == "hy" ) {
                $bookingpress_site_current_lang_moment_locale = "hy-am";
            }else if ( $bookingpress_site_current_lang_moment_locale == "et" ) {
                $bookingpress_site_current_lang_moment_locale = "et";
            }else if ( $bookingpress_site_current_lang_moment_locale == "nb_NO" ) {
                $bookingpress_site_current_lang_moment_locale = "no";
            }            
            $bookingpress_global_details  = $bookingpress_global_options->bookingpress_global_options();
            $bookingpress_wp_default_time_format = $bookingpress_global_details['wp_default_time_format'];
            $bookingpress_inherit_from_wordpress_arr = json_decode($bookingpress_global_details['bookingpress_inherit_from_wordpress_arr'],true);

            if(isset($bookingpress_inherit_from_wordpress_arr[$bookingpress_wp_default_time_format])){
                $bookingpress_formatted_timeslot = $bookingpress_inherit_from_wordpress_arr[$bookingpress_wp_default_time_format];
            }
            $bookingpress_vue_root_element_id = '#bookingpress_booking_form_' . $bookingpress_uniq_id;
            $bookingpress_vue_root_element_id_without_hash = 'bookingpress_booking_form_' . $bookingpress_uniq_id;
            $bookingpress_vue_root_element_id_el = 'method_' . $bookingpress_uniq_id;            
            $bookingpress_package_thankyou_page = "";
            $is_success = (isset($_REQUEST['is_success']))?sanitize_text_field($_REQUEST['is_success']):'';
            $package_order_entry_id = (isset($_REQUEST['package_order_entry_id']))?sanitize_text_field($_REQUEST['package_order_entry_id']):''; 
            if(!empty($package_order_entry_id) && !empty($is_success)){
                $bookingpress_package_thankyou_page = "                    
                    vm.bpp_external_thankyou_page();
                ";
                $bookingpresslistener = (isset($_REQUEST['bookingpress-listener']))?sanitize_text_field($_REQUEST['bookingpress-listener']):'';
                if($bookingpresslistener == "bpa_pro_razorpay_url"){

                }
                               
            }
            $bookingpress_script_return_data .= 'app = new Vue({ 
				el: "' . $bookingpress_vue_root_element_id . '",
				components: {},
				directives: { ' . $bookingpress_dynamic_directive_data . ' },
				data(){
                    
                    var bpa_check_username = ( rule, value, callback ) =>{
                        const vm = this;
                        
                        if( "undefined" == vm.package_step_form_data.check_username_validation || false == vm.package_step_form_data.check_username_validation ){
                            if( "undefined" != vm.package_step_form_data.invalid_customer_username && true == vm.package_step_form_data.invalid_customer_username ){
                                return callback( new Error( vm.package_step_form_data.invalid_customer_message ) );
                            } else {
                                return callback();
                            }
                        }

                        var bkp_wpnonce_pre = "' . $bookingpress_nonce . '";
                        var bkp_wpnonce_pre_fetch = document.getElementById("_wpnonce");
                        if(typeof bkp_wpnonce_pre_fetch=="undefined" || bkp_wpnonce_pre_fetch==null){
                            bkp_wpnonce_pre_fetch = bkp_wpnonce_pre;
                        }else{
                            bkp_wpnonce_pre_fetch = bkp_wpnonce_pre_fetch.value;
                        }
                        let bookingpress_username_value = value;                        
                        var bookingpress_username = { action:"bookingpress_validate_username", _username: bookingpress_username_value, _wpnonce:bkp_wpnonce_pre_fetch};
                        axios.post( appoint_ajax_obj.ajax_url, Qs.stringify( bookingpress_username ) )
                        .then( function (response) {
                            vm.package_step_form_data.check_username_validation = false;
                            
                            if(response.data.variant == "error"){
                                vm.package_step_form_data.invalid_customer_username = true;
                                vm.package_step_form_data.invalid_customer_message = response.data.msg;
                                return callback(new Error( response.data.msg ));
                            } else {
                                vm.package_step_form_data.invalid_customer_username = false;
                                callback();
                            }
                        }.bind(this) )
                        .catch( function (error) {
                            //vm.bookingpress_package_set_error_msg(error)
                        });
                    };
                    
					var bookingpress_return_data = ' . $bookingpress_dynamic_data_fields . ';
					bookingpress_return_data["jsCurrentDate"] = new Date('. ( !empty( $bookingpress_site_date ) ? '"'.$bookingpress_site_date.'"' : '' ) .');
					bookingpress_return_data["jsCurrentDateFormatted"] = new Date ('. ( !empty( $bookingpress_site_current_date ) ? '"'.$bookingpress_site_current_date.'"' : '' ) .');
					bookingpress_return_data["package_step_form_data"]["stime"] = ' . ( time() + 14921 ) . ';
					bookingpress_return_data["package_step_form_data"]["spam_captcha"] = "";					
					bookingpress_return_data["default_date_format"] = "' . $this->bookingpress_default_date_format . '";
					bookingpress_return_data["package_customer_details_rule"] = ' . json_encode($this->bookingpress_form_fields_error_msg_arr) . ';

                    if( "undefined" != typeof bookingpress_return_data["package_customer_details_rule"].customer_username && bookingpress_return_data["check_bookingpress_username_set"] == 0 ){
                        let rule_for_username = {
                            "validator": bpa_check_username,
                            "trigger": "blur"
                        };
                        bookingpress_return_data["package_customer_details_rule"].customer_username.push( rule_for_username );                       
                    }

					bookingpress_return_data["customer_form_fields"] = ' . json_encode($this->bookingpress_form_fields_new) . ';
					bookingpress_return_data["is_error_msg"] = "";
					bookingpress_return_data["is_display_error"] = "0";
					
                    bookingpress_return_data["is_success_msg"] = "";
					bookingpress_return_data["is_display_success"] = "0";

					bookingpress_return_data["is_service_loaded_from_url"] = "' . $this->bookingpress_is_package_load_from_url . '";
					bookingpress_return_data["booking_cal_maxdate"] = new Date( Date.now() + ( 3600 * 1000 * (24 * 365) ) );
                    bookingpress_return_data["is_package_booking_form_empty_loader"] = "1";

                    bookingpress_return_data["is_package_booking_pakage_booking_loader"] = "1";

                    bookingpress_return_data["bpa_allow_modify_from_url"] = "'.$bpa_allow_modify_from_url.'";

					bookingpress_return_data["site_locale"] = "' . $bookingpress_site_current_language . '";    
					bookingpress_return_data["package_step_form_data"]["bookingpress_uniq_id"] = "' . $bookingpress_uniq_id . '";                    
                    bookingpress_return_data["package_step_form_data"]["bookingpress_form_token"] = "' . $bookingpress_uniq_id . '";
					var bookingpress_captcha_key = "bookingpress_captcha_' . $bookingpress_uniq_id . '";
					bookingpress_return_data["package_step_form_data"][bookingpress_captcha_key] = "";

                    bookingpress_return_data["first_day_of_week"] = "' . $first_day_of_week_inc. '"; 
                    bookingpress_return_data["filter_pickerOptions"] = {
                        "firstDayOfWeek": '.$first_day_of_week.',
                    };
                    bookingpress_return_data["package_step_form_data"]["base_price_without_currency"] = 0;
                    bookingpress_return_data["use_base_price_for_calculation"] = true;
                    bookingpress_return_data["default_package"] = "' . $this->bookingpress_form_package. '";

                    bookingpress_return_data["modelConfig"] = {
                        "type": "string",
                        "mask": "YYYY-MM-DD",
                    };

					return bookingpress_return_data;
				},
				filters: {
					bookingpress_format_date: function(value){
                        var default_date_format = "' . $this->bookingpress_default_date_format . '";
                        return moment(String(value)).locale("'.$bookingpress_site_current_lang_moment_locale.'").format(default_date_format)
					},
					bookingpress_format_time: function(value){
						var default_time_format = "' . $bookingpress_formatted_timeslot . '";
                        return moment(String(value), "HH:mm:ss").locale("'.$bookingpress_site_current_lang_moment_locale.'").format(default_time_format)
					}
				},
                beforeCreate(){   
                    this.bookingpress_all_packages_data = [];                 
					this.is_package_booking_form_empty_loader = "1";
				},
				created(){
					this.bookingpress_load_package_booking_form();                    
				},
				mounted(){
                    const vm_onload = this;
                    const vm = vm_onload;
                    //vm_onload.bpa_check_browser();
                    //vm_onload.bpa_check_browser_version();                    
					this.loadSpamProtection();
                    setTimeout(function(){
                        vm.bookingpress_created_nonce = "'.$bookingpress_nonce.'";
                        vm_onload.getPackageList(1);
                        vm_onload.expirationDate();                        
                    },100);  
                    setTimeout(function(){
                        '.$bookingpress_package_thankyou_page.'
                    },500);      
					' . $bookingpress_dynamic_on_load_methods_data . '
				},
                computed:{
                    bpasortedPackages: function(){
                        
                    }
                },
				methods: {                                   
                    bpa_check_username_validation(bpa_username){
                        const vm = this;                        
                    },
                    bookingpress_load_package_booking_form(){
                        const vm = this;
                        setTimeout(function(){
                            vm.is_package_booking_form_empty_loader = "0";                            
                        }, 2000);
                    },
					generateSpamCaptcha(){
						const vm = this;
                        var bkp_wpnonce_pre = "' . $bookingpress_nonce . '";
                        var bkp_wpnonce_pre_fetch = document.getElementById("_wpnonce");
                        if(typeof bkp_wpnonce_pre_fetch=="undefined" || bkp_wpnonce_pre_fetch==null)
                        {
                            bkp_wpnonce_pre_fetch = bkp_wpnonce_pre;
                        }
                        else {
                            bkp_wpnonce_pre_fetch = bkp_wpnonce_pre_fetch.value;
                        }
						var postData = { action: "bookingpress_generate_spam_captcha", _wpnonce:bkp_wpnonce_pre_fetch };
							axios.post( appoint_ajax_obj.ajax_url, Qs.stringify( postData ) )
						.then( function (response) {
							if(response.variant != "error" && (response.data.captcha_val != "" && response.data.captcha_val != undefined)){
								vm.package_step_form_data.spam_captcha = response.data.captcha_val;
							}else{
                                var bkp_wpnonce_pre_fetch = document.getElementById("_wpnonce");
                                if(typeof bkp_wpnonce_pre_fetch!="undefined" && bkp_wpnonce_pre_fetch!=null && response.data.updated_nonce!=""){
                                    document.getElementById("_wpnonce").value = response.data.updated_nonce;
                                } else {
                                    /*
                                    vm.$notify({
                                        title: response.data.title,
                                        message: response.data.msg,
                                        type: response.data.variant,
                                        customClass: "error_notification"
                                    });
                                    */
                                }
							}
						}.bind(this) )
						.catch( function (error) {
							console.log(error);
						});
					},
					loadSpamProtection(){
						const vm = this;
						vm.generateSpamCaptcha();
					},
                    bookingpress_price_with_currency_symbol( price_amount, ignore_symbol = false ){
                        const vm = this;
                        if( "String" == typeof price_amount ){
                            price_amount = parseFloat( price_amount );
                        }                        
                        let currency_separator = vm.bookingpress_currency_separator;
                        let decimal_points = vm.bookingpress_decimal_points;

                        if( "comma-dot" == currency_separator ){
                            price_amount = vm.bookingpress_number_format( price_amount, decimal_points, ".", "," );
                        } else if( "dot-comma" == currency_separator ){
                            price_amount = vm.bookingpress_number_format( price_amount, decimal_points, ",", "." );
                        } else if( "space-dot" == currency_separator ){
                            price_amount = vm.bookingpress_number_format( price_amount, decimal_points, ".", " " );
                        } else if( "space-comma" == currency_separator ){
                            price_amount = vm.bookingpress_number_format( price_amount, decimal_points, ",", " " );
                        } else if( "Custom" == currency_separator){
                            let custom_comma_separator = vm.bookingpress_custom_comma_separator;
                            let custom_thousand_separator = vm.bookingpress_custom_thousand_separator;
                            price_amount = vm.bookingpress_number_format( price_amount, decimal_points, custom_comma_separator, custom_thousand_separator );
                        }

                        if( true == ignore_symbol ){
                            return price_amount;
                        }

                        let currency_symbol = vm.bookingpress_currency_symbol;
                        let currency_symbol_pos = vm.bookingpress_currency_symbol_position;

                        if( "before" == currency_symbol_pos ){
                            price_amount = currency_symbol + price_amount;
                        } else if( "before_with_space" == currency_symbol_pos ){
                            price_amount = currency_symbol + " " + price_amount;
                        } else if( "after" == currency_symbol_pos ){
                            price_amount = price_amount + currency_symbol;
                        } else if( "after_with_space" == currency_symbol_pos ){
                            price_amount = price_amount + " " + currency_symbol;
                        }

                        return price_amount;

                    },
                    bookingpress_number_format( number, decimals, decPoint, thousandsSep ){
                        number = (number + "").replace(/[^0-9+\-Ee.]/g, "");
                        const n = !isFinite(+number) ? 0 : +number;
                        const prec = !isFinite(+decimals) ? 0 : Math.abs(decimals);
                        const sep = (typeof thousandsSep === "undefined") ? "," : thousandsSep;
                        const dec = (typeof decPoint === "undefined") ? "." : decPoint;
                        let s = "";
                        const toFixedFix = function (n, prec) {
                            if (("" + n).indexOf("e") === -1) {
                                return +(Math.round(n + "e+" + prec) + "e-" + prec);
                            } else {
                                const arr = ("" + n).split("e");
                                let sig = "";
                                if (+arr[1] + prec > 0) {
                                    sig = "+";
                                }
                                return (+(Math.round(+arr[0] + "e" + sig + (+arr[1] + prec)) + "e-" + prec)).toFixed(prec);
                            }
                        };
                        /* @todo: for IE parseFloat(0.55).toFixed(0) = 0; */
                        s = (prec ? toFixedFix(n, prec).toString() : "" + Math.round(n)).split(".");
                        if (s[0].length > 3) {
                            s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
                        }
                        if ((s[1] || "").length < prec) {
                            s[1] = s[1] || "";
                            s[1] += new Array(prec - s[1].length + 1).join("0");
                        }
                        return s.join(dec);
                    },                    
					' . $bookingpress_vue_methods_data . '
				},
			});';            
            
            $bpa_script_data = " var app;  
			var is_script_loaded_$bookingpress_vue_root_element_id_el = false;
            bookingpress_beforeload_data = '';
            if( null != document.getElementById('$bookingpress_vue_root_element_id_without_hash') ){
                bookingpress_beforeload_data = document.getElementById('$bookingpress_vue_root_element_id_without_hash').innerHTML;
            }
            window.addEventListener('DOMContentLoaded', function() {
                if( is_script_loaded_$bookingpress_vue_root_element_id_el == false) {
                    is_script_loaded_$bookingpress_vue_root_element_id_el = true;
                    bpa_load_vue_shortcode_$bookingpress_vue_root_element_id_el();
                }
            });
            window.addEventListener( 'elementor/popup/show', (event) => {
                let element = event.detail.instance.\$element[0].querySelector('.bpp-frontend-main-container-package');
                if( 'undefined' != typeof element ){
                    document.getElementById('$bookingpress_vue_root_element_id_without_hash').innerHTML = bookingpress_beforeload_data;
                    bpa_load_vue_shortcode_$bookingpress_vue_root_element_id_el();
                }
            });
            function bpa_load_vue_shortcode_$bookingpress_vue_root_element_id_el(){
                {$bookingpress_script_return_data}                
            }";
                
            if( $bookingress_load_js_css_all_pages == 'true' ){
                wp_enqueue_script('bookingpress_elements_locale');
                $bpa_script_data .= 'if( false == is_script_loaded_'.$bookingpress_vue_root_element_id_el.' ) {  is_script_loaded_'.$bookingpress_vue_root_element_id_el.' = true; bpa_load_vue_shortcode_'.$bookingpress_vue_root_element_id_el.'(); }';
            }
                
                wp_add_inline_script('bookingpress_elements_locale', $bpa_script_data, 'after');

                //$bookingpress_custom_css = $BookingPress->bookingpress_get_customize_settings('custom_css', 'booking_form');            
                //$bookingpress_custom_css = !empty($bookingpress_custom_css) ? stripslashes_deep( $bookingpress_custom_css ) : '';
                //wp_add_inline_style( 'bookingpress_front_custom_css', $bookingpress_custom_css, 'after' );

                $this->bookingpress_form_package = 0 ;
                $this->bookingpress_is_package_load_from_url = 0;
                $this->bookingpress_form_fields_error_msg_arr = array();
                $this->bookingpress_form_fields_new = array();

                return do_shortcode( $content );
        }

    }

    global $bookingpress_package_booking_form;
	$bookingpress_package_booking_form = new bookingpress_package_booking_form();
}