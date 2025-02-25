<?php
if (!class_exists('bookingpress_package_appointment_book')) {
	class bookingpress_package_appointment_book Extends BookingPress_Core {

        function __construct(){

            global $wp, $wpdb, $tbl_bookingpress_packages, $tbl_bookingpress_package_services, $tbl_bookingpress_package_images, $BookingPress, $tbl_bookingpress_package_bookings,$bookingpress_package;
            
            $package_addon_working = $bookingpress_package->bookingpress_check_package_addon_requirement();

            if( is_plugin_active( 'bookingpress-appointment-booking-pro/bookingpress-appointment-booking-pro.php' ) && !empty( $BookingPress->bpa_pro_plugin_version() ) && version_compare( $BookingPress->bpa_pro_plugin_version(), '3.2', '>=' ) && $package_addon_working){

                add_filter('bookingpress_frontend_apointment_form_add_dynamic_data',array($this,'bookingpress_frontend_apointment_form_add_dynamic_data_func'));

                add_action('bookingpress_add_content_before_coupon_code_data_frontend',array($this,'bookingpress_add_content_before_coupon_code_data_frontend_func'),10);

                /* Customized section data add */
                add_action('bookingpress_add_customize_extra_section',array($this, 'bookingpress_add_customize_extra_section_func'));

                add_filter('bookingpress_get_booking_form_customize_data_filter',array($this,'bookingpress_get_booking_form_customize_data_filter_func'));

                add_filter('bookingpress_customize_add_dynamic_data_fields',array($this,'bookingpress_customize_add_dynamic_data_fields_func'));

                add_filter('bookingpress_before_save_customize_booking_form',array($this,'bookingpress_before_save_customize_booking_form_func'),11);

                /* validation for check package addon service */
                add_filter( 'bookingpress_dynamic_validation_for_step_change', array( $this, 'bookingpress_dynamic_validation_for_step_change_for_package_service'));

                /* Add package dynamic vue method */
                add_filter('bookingpress_add_appointment_booking_vue_methods', array( $this, 'bookingpress_booking_dynamic_vue_methods_func' ), 10, 1);

                /* Package Redeem Request  */
                add_action( 'wp_ajax_bookingpress_redeem_package_request', array( $this, 'bookingpress_redeem_package_request_func' ) );

                add_filter( 'bookingpress_sub_total_amount_payable_modify_outside', array( $this, 'bookingpress_sub_total_amount_payable_modify_outside_func'),20,1);

                //add_filter( 'bookingpress_total_amount_modify_outside_arr', array( $this, 'bookingpress_total_amount_modify_outside_arr_func'),20);

                /* Entries Data Modified For Package Appointment Insert Data */
                add_filter('bookingpress_modify_entry_data_before_insert',array($this,'bookingpress_modify_entry_data_before_insert_func'),20,2);

                /* Entries Data Modified For Package Appointment Insert Data for cart */
                add_filter('bookingpress_modify_cart_entry_data_before_insert',array($this,'bookingpress_modify_cart_entry_data_before_insert_func'),11,3);

                add_filter('bookingpress_modify_appointment_booking_fields_before_insert',array($this,'bookingpress_modify_appointment_booking_fields_before_insert_func'),15,2);

                add_filter('bookingpress_modify_payment_log_fields_before_insert',array($this,'bookingpress_modify_payment_log_fields_before_insert_func'),15,2);

                /* Function for add recurring icons in appointment list backend */
                add_action('bookingpress_backend_appointment_list_type_icons',array($this,'bookingpress_backend_appointment_list_type_icons_func'));
                add_filter('bookingpress_appointment_add_view_field', array($this, 'bookingpress_appointment_add_view_field_func'), 10, 2);

                /* Add cart package data here  */
                add_filter('bookingpress_add_custom_service_duration_data',array($this,'bookingpress_add_cart_package_data_func'),10);

                /* Filter for Complete Payment Appointment Booking  */
                add_filter('modify_complate_payment_data_after_entry_create', array($this, 'modify_complate_payment_data_after_entry_create_func'), 15, 2);
                add_action('bookingpress_complete_payment_subtotal_price_after',array($this,'bookingpress_complete_payment_subtotal_price_after_func'),10);                
                add_filter('bookingpress_modify_recalculate_amount',array($this,'bookingpress_modify_recalculate_amount_before_calculation_func'),25,2);                

                add_filter('bookingpress_modify_recalculate_appointment_details', array($this, 'bookingpress_modify_calculated_appointment_details_func'),15,2);
                add_filter('bookingpress_modified_coupon_total_payable_amount',array($this,'bookingpress_modified_coupon_total_payable_amount_func'),10,2);                
                add_filter('bookingpress_modify_calculated_appointment_details', array($this, 'bookingpress_modify_calculated_appointment_details_final_func'));
                add_filter('bookingpress_get_final_step_amount_after',array($this,'bookingpress_get_final_step_amount_after_func'),10,1);

                if(is_plugin_active('bookingpress-multilanguage/bookingpress-multilanguage.php')) {
                    add_filter('bookingpress_modified_language_translate_fields',array($this,'bookingpress_modified_language_translate_fields_func'),10);
                    add_filter('bookingpress_modified_customize_form_language_translate_fields',array($this,'bookingpress_modified_customize_form_language_translate_fields_func'),10);
                    add_filter('bookingpress_modified_language_translate_fields_section',array($this,'bookingpress_modified_language_translate_fields_section_func'),10);
                }
                add_filter('bookingpress_my_appointment_modify_data_for_rescheduling',array($this,'bookingpress_my_appointment_modify_data_for_rescheduling_func'),11,2);
                add_action('bookingpress_reschedule_appointment_extra_validation',array($this,'bookingpress_reschedule_appointment_extra_validation_func'));


                add_filter('bookingpress_before_remove_cart_item',array($this,'bookingpress_clear_package_applied_data_item_func'),10,1);
                add_filter('bookingpress_before_add_to_cart_item',array($this,'bookingpress_clear_package_applied_data_item_func'),10,1);
                
                add_action( 'bookingpress_after_book_appointment', array( $this, 'bookingpress_after_book_appointment_fun' ), 10, 3 );

                //Check applied coupon is valid or not
                add_filter('bookingpress_check_coupon_validity_from_outside', array($this, 'bookingpress_check_coupon_validity_from_outside_func'), 15, 2);

            }
        }       

		/**
		 * bpa function for redeem package request
		 *
		 * @param  mixed $user_detail
		 * @return void
		*/
		function bookingpress_bpa_redeem_package_func($user_detail=array()){
			global $BookingPress,$wpdb,$BookingPressPro;	
			$result = array();
			$response = array('status' => 0, 'message' => '', 'response' => array('result' => $result));
			if(class_exists('BookingPressPro') && method_exists( $BookingPressPro, 'bookingpress_bpa_check_valid_connection_callback_func') && $BookingPressPro->bookingpress_bpa_check_valid_connection_callback_func()){
				if(!empty($bookingpress_nonce)){
					$_REQUEST['_wpnonce'] = $bookingpress_nonce;
				}else{
					$bookingpress_nonce = wp_create_nonce('bpa_wp_nonce');
					$_REQUEST['_wpnonce'] = $bookingpress_nonce;					
				}				
				$user_detail = !empty($user_detail) ? array_map(array( $BookingPress, 'appointment_sanatize_field' ), $user_detail) : array();
				$appointment_details = isset($user_detail['appointment_details']) ? $user_detail['appointment_details'] : '';
                $user_id = isset($user_detail['user_id']) ? $user_detail['user_id'] : '';
				if(!empty($appointment_details) && !empty($user_id)){
					$_REQUEST['appointment_details'] = $appointment_details;
					$_POST = $_REQUEST;
				}												
				$bookingpress_response = $this->bookingpress_redeem_package_request_func(true,$user_id);
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
         * bpa function for get user purchase package list
         *
         * @param  mixed $user_detail
         * @return void
        */
        function bookingpress_bpa_get_customer_purchase_package_func($user_detail=array()){
			global $$BookingPress,$tbl_bookingpress_customers, $wpdb, $tbl_bookingpress_package_bookings,$BookingPressPro,$bookingpress_package,$BookingPressPro;

			$result = array();						
			$result["customer_package_list"] = array();
			$response = array('status' => 0, 'message' => '', 'response' => array('result' => $result));

            if(class_exists('BookingPressPro') && method_exists( $BookingPressPro, 'bookingpress_bpa_check_valid_connection_callback_func') && $BookingPressPro->bookingpress_bpa_check_valid_connection_callback_func()){

                $user_id = isset($user_detail['user_id']) ? intval($user_detail['user_id']) : 0;
                $service_id = isset($user_detail['service_id']) ? intval($user_detail['service_id']) : '';
                $bookingpress_current_date = date('Y-m-d');
                $bookingpress_get_all_customer_purchase_package_list = $this->bookingpress_get_customer_purchase_package_list($user_id);

                $result["customer_package_list"] = (isset($bookingpress_get_all_customer_purchase_package_list['bookingpress_customer_all_package_list']))?$bookingpress_get_all_customer_purchase_package_list['bookingpress_customer_all_package_list']:array();
                
                $response = array('status' => 0, 'message' => '', 'response' => array('result' => $result));                                           

                return $response;
            }

            return $response;

        }

        function bookingpress_check_coupon_validity_from_outside_func($response, $bookingpress_appointment_details){
            global $wpdb, $tbl_bookingpress_coupons, $BookingPress, $bookingpress_deposit_payment, $bookingpress_coupons;
            if($bookingpress_coupons->bookingpress_check_coupon_module_activation()){
                if(!empty($bookingpress_appointment_details) && !empty($bookingpress_appointment_details['cart_items']) ){
                    $bookingpress_cart_items = $bookingpress_appointment_details['cart_items'];
                    $bookingpress_is_coupon_applied = 0;
                    $bookingpress_coupon_applied_msg = "";
                    $bookingpress_applied_coupon_data = array();

                    $bookingpress_coupon_code = !empty($bookingpress_appointment_details['coupon_code']) ? $bookingpress_appointment_details['coupon_code'] : '';
                    $bookingpress_payable_amount = !empty($bookingpress_appointment_details['bookingpress_cart_total']) ? floatval($bookingpress_appointment_details['bookingpress_cart_total']) : 0;

                    if(isset($bookingpress_appointment_details['bookingpress_applied_package_data']['package_discount_price'])){
                        $package_discount_price = floatval($bookingpress_appointment_details['bookingpress_applied_package_data']['package_discount_price']);
                        $bookingpress_payable_amount = $bookingpress_payable_amount - $package_discount_price;
                    }                    
                    if(isset($bookingpress_appointment_details['bookingpress_package_applied_data']['package_discount_price'])){
                        $package_discount_price = floatval($bookingpress_appointment_details['bookingpress_package_applied_data']['package_discount_price']);                        
                        $bookingpress_payable_amount = $bookingpress_payable_amount - $package_discount_price;

                        if($bookingpress_payable_amount == 0 && $bookingpress_payable_amount > $package_discount_price && $package_discount_price != 0){                            
                            $new_response['variant']     = "error";
                            $new_response['title']       = esc_html__( 'Error', 'bookingpress-package' );
                            $new_response['msg']         = __( 'Coupon code not applied on this service', 'bookingpress-package' );
                            $new_response['coupon_data'] = array();
                            
                            return $new_response;    
                            
                        }
                    }                                       
                    foreach($bookingpress_cart_items as $k => $v){
                        $bookingpress_service_id = !empty($v['bookingpress_service_id']) ? intval($v['bookingpress_service_id']) : 0;
                        $bookingpress_applied_coupon_response = $bookingpress_coupons->bookingpress_apply_coupon_code( $bookingpress_coupon_code, $bookingpress_service_id );
                        $bookingpress_coupon_applied_msg = $bookingpress_applied_coupon_response['msg'];
                        if ( is_array( $bookingpress_applied_coupon_response ) && ! empty( $bookingpress_applied_coupon_response ) && !empty($bookingpress_applied_coupon_response['coupon_status']) && ($bookingpress_applied_coupon_response['coupon_status'] == "success") ) {
                            $bookingpress_is_coupon_applied = 1;
                            $bookingpress_applied_coupon_data = $bookingpress_applied_coupon_response['coupon_data'];
                        }else{                                                        
                            $bookingpress_is_coupon_applied = 0;
                            $bookingpress_applied_coupon_data = array();                            
                        }
                    }                    
                    if($bookingpress_is_coupon_applied == 1) {         
                        $bookingpress_tax_amount = isset($bookingpress_appointment_details['calculated_tax_amount_org']) ? $bookingpress_appointment_details['calculated_tax_amount_org'] : 0;
                        $bookingpress_payable_amount = $bookingpress_payable_amount - $bookingpress_tax_amount;  
                        $bookingpress_after_discount_amounts = $bookingpress_coupons->bookingpress_calculate_bookingpress_coupon_amount( $bookingpress_coupon_code, $bookingpress_payable_amount );
                        if($bookingpress_after_discount_amounts['discounted_amount'] <= $bookingpress_payable_amount && $bookingpress_payable_amount != 0 ) {
                            $final_payable_amount = $response['final_payable_amount'] = ! empty( $bookingpress_after_discount_amounts['final_payable_amount'] ) ? floatval( $bookingpress_after_discount_amounts['final_payable_amount'] ) : 0;
                            
                            $response['discounted_amount']    = ! empty( $bookingpress_after_discount_amounts['discounted_amount'] ) ? $BookingPress->bookingpress_price_formatter_with_currency_symbol( floatval( $bookingpress_after_discount_amounts['discounted_amount'] ) ) : 0;

                            $tax_percentage = !empty($bookingpress_appointment_details['tax_percentage']) ? $bookingpress_appointment_details['tax_percentage'] : 0;
                            $bookingpress_price_display_setting = !empty($bookingpress_appointment_details['tax_price_display_options']) ? $bookingpress_appointment_details['tax_price_display_options'] : 'exclude_taxes';

                            if ( ! empty( $tax_percentage ) ) {
                                if($bookingpress_price_display_setting == "include_taxes"){
                                    $bookingpress_tax_amount    = $final_payable_amount * ( $tax_percentage / 100 );
                                    $final_payable_amount = $final_payable_amount + $bookingpress_tax_amount;
                                    $response['tax_included_amount'] = $final_payable_amount;
                                }else{
                                    $bookingpress_tax_amount    = $final_payable_amount * ( $tax_percentage / 100 );
                                    $final_payable_amount = $final_payable_amount + $bookingpress_tax_amount;
                                    $response['tax_excluded_amount'] = $final_payable_amount;
                                }

                                $response['tax_amount_without_currency'] = $bookingpress_tax_amount;
                                $bookingpress_tax_amount = $BookingPress->bookingpress_price_formatter_with_currency_symbol( $bookingpress_tax_amount );
                                $response['tax_amount']        = $bookingpress_tax_amount;
                                $response['is_tax_calculated'] = 1;
                                $response['tax_included_amount'] = $final_payable_amount;
                                $response['final_payable_amount'] = $final_payable_amount;
                            }
                            
                            $response['coupon_discount_amount'] = ! empty( $bookingpress_after_discount_amounts['discounted_amount'] ) ? $bookingpress_after_discount_amounts['discounted_amount'] : 0;
                            $response['coupon_discount_amount_with_currecny'] = $response['discounted_amount'];

                            $response['total_payable_amount'] = $response['final_payable_amount'];
                            $response['total_payable_amount_with_currency'] = !empty( $response['final_payable_amount'] ) ? $BookingPress->bookingpress_price_formatter_with_currency_symbol( $response['final_payable_amount'] ) : 0;;

                            $response['variant']     = "success";
                            $response['title']       = esc_html__( 'Success', 'bookingpress-package' );
                            $response['msg']         = $bookingpress_coupon_applied_msg;
                            $response['coupon_data'] = $bookingpress_applied_coupon_data;
                        } else {                            
                            /*
                            $response['variant']     = "error";
                            $response['title']       = esc_html__( 'Error', 'bookingpress-package' );
                            $response['msg']         = __( 'Coupon code not applied on this service', 'bookingpress-package' );
                            */
                        }
                    }else{
                        $response['variant']     = "error";
                        $response['title']       = esc_html__( 'Error', 'bookingpress-package' );
                        $response['msg']         = $bookingpress_coupon_applied_msg;
                        $response['coupon_data'] = $bookingpress_applied_coupon_data;
                    }
                }
            }

            return $response;
        }        

		/**
		 * Function for add appointment meta data
		 *
		 * @param  mixed $appointment_id
		 * @param  mixed $entry_id
		 * @param  mixed $payment_gateway_data
		 * @return void
		 */
		function bookingpress_after_book_appointment_fun( $appointment_id, $entry_id = '', $payment_gateway_data = array() ){

            global $tbl_bookingpress_entries,$wpdb,$tbl_bookingpress_appointment_meta,$BookingPress,$tbl_bookingpress_appointment_bookings;
            $tbl_bookingpress_entries_meta = $wpdb->prefix . 'bookingpress_entries_meta';
			if( empty( $appointment_id ) && empty($entry_id)){                
				return;
			}            
            $entry_id = $wpdb->get_var($wpdb->prepare("SELECT bookingpress_entry_id FROM {$tbl_bookingpress_appointment_bookings} WHERE bookingpress_appointment_booking_id = %d", $appointment_id)); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- Reason: $tbl_bookingpress_appointment_bookings is table name defined globally. False Positive alarm
            $entry_data = $wpdb->get_row( $wpdb->prepare( "SELECT bookingpress_entry_meta_value FROM {$tbl_bookingpress_entries_meta} WHERE bookingpress_entry_id = %d AND bookingpress_entry_meta_key = 'bookingpress_happy_hour_data'", $entry_id ), ARRAY_A ); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- Reason: $tbl_bookingpress_entries_meta is table name defined globally. False Positive alarm            
            if(!empty($entry_data)){
                
            }
            
        }        

        function bookingpress_clear_package_applied_data_item_func($bookingpress_before_remove_cart_item){
            $bookingpress_before_remove_cart_item.='
                vm8 = this;    
                vm8.appointment_step_form_data.bookingpress_package_applied_data = "";
                vm8.appointment_step_form_data.bookingpress_package_applied_for_all_cart_appointments = "";
                if(vm8.appointment_step_form_data.selected_payment_method != "on-site"){
                    vm8.appointment_step_form_data.selected_payment_method = "";
                }                
                vm8.appointment_step_form_data.selected_package = "";
            ';
            return $bookingpress_before_remove_cart_item;
        }

        function bookingpress_reschedule_appointment_extra_validation_func(){
            global $tbl_bookingpress_appointment_bookings,$wpdb,$tbl_bookingpress_appointment_meta,$tbl_bookingpress_package_bookings;

            $reschedule_appointment_id = !empty( $_REQUEST['resche_apt_id'] ) ? intval( $_REQUEST['resche_apt_id'] ) : 0;
            $appointment_selected_date = !empty( $_POST['resche_date'] ) ? date( 'Y-m-d', strtotime( sanitize_text_field( $_POST['resche_date'] ) ) ) : '';//phpcs:ignore
            if(!empty($reschedule_appointment_id) && !empty($appointment_selected_date)){

                $bookingpress_appointment_data = $wpdb->get_row( $wpdb->prepare( "SELECT bookingpress_package_id FROM {$tbl_bookingpress_appointment_bookings} WHERE bookingpress_appointment_booking_id = %d", $reschedule_appointment_id ), ARRAY_A ); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared --Reason: $tbl_bookingpress_appointment_bookings is a table name. false alarm
                $bookingpress_package_no = (isset($bookingpress_appointment_data['bookingpress_package_id']))?$bookingpress_appointment_data['bookingpress_package_id']:'';
                if($bookingpress_package_no){
                    $bookingpress_get_purchase_packages = $wpdb->get_row( $wpdb->prepare( "SELECT bookingpress_package_expiration_date FROM {$tbl_bookingpress_package_bookings} WHERE bookingpress_package_no = %d", $bookingpress_package_no), ARRAY_A); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared --Reason: $tbl_bookingpress_package_bookings is a table name. false alarm
                    $bookingpress_package_expiration_date = (isset($bookingpress_get_purchase_packages['bookingpress_package_expiration_date']))?$bookingpress_get_purchase_packages['bookingpress_package_expiration_date']:'';
                    if(!empty($bookingpress_package_expiration_date)){
                        if($appointment_selected_date > $bookingpress_package_expiration_date){
                            $response['variant']      = 'error';
                            $response['title']        = esc_html__( 'Error', 'bookingpress-package' );
                            $response['msg']          = esc_html__( 'Sorry, the selected date is greater than the package expiration date.', 'bookingpress-package' );
                            $response['redirect_url'] = '';
                            echo wp_json_encode( $response );
                            die();                            
                        }
                    }
                }
            }
        }

        /**
         * Package appointment reschedule within package expire date validation.
         *
         * @param  mixed $response
         * @param  mixed $reschedule_appointment_id
         * @return void
         */
        function bookingpress_my_appointment_modify_data_for_rescheduling_func($response,$reschedule_appointment_id) {
            global $tbl_bookingpress_appointment_bookings,$wpdb,$tbl_bookingpress_appointment_meta,$tbl_bookingpress_package_bookings;
            if(!empty($reschedule_appointment_id)) {

                $bookingpress_appointment_data = $wpdb->get_row( $wpdb->prepare( "SELECT bookingpress_package_id FROM {$tbl_bookingpress_appointment_bookings} WHERE bookingpress_appointment_booking_id = %d", $reschedule_appointment_id ), ARRAY_A ); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared --Reason: $tbl_bookingpress_appointment_bookings is a table name. false alarm
                $bookingpress_package_no = (isset($bookingpress_appointment_data['bookingpress_package_id']))?$bookingpress_appointment_data['bookingpress_package_id']:'';
                if($bookingpress_package_no){
                    $bookingpress_get_purchase_packages = $wpdb->get_row( $wpdb->prepare( "SELECT bookingpress_package_expiration_date FROM {$tbl_bookingpress_package_bookings} WHERE bookingpress_package_no = %d", $bookingpress_package_no), ARRAY_A); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared --Reason: $tbl_bookingpress_package_bookings is a table name. false alarm
                    $bookingpress_package_expiration_date = (isset($bookingpress_get_purchase_packages['bookingpress_package_expiration_date']))?$bookingpress_get_purchase_packages['bookingpress_package_expiration_date']:'';
                    if(!empty($bookingpress_package_expiration_date)){
                        $bookingpress_service_expiration_date = $response['bookingpress_service_expiration_date'];
                        if(empty($bookingpress_service_expiration_date)){
                            $response['bookingpress_service_expiration_date'] = $bookingpress_package_expiration_date; 
                        }else{
                            if($bookingpress_service_expiration_date > $bookingpress_package_expiration_date){
                                //$response['bookingpress_service_expiration_date'] = $bookingpress_package_expiration_date; 
                            }
                        }
                    }
                }
            }    
            return $response;
        }


        function bookingpress_modified_language_translate_fields_section_func($bookingpress_all_language_translation_fields_section){
			/* Function to add cart step heading */
            $bookingpress_custom_duration_step_section_added = array('package_appointment_booking_labels' => __('Package labels', 'bookingpress-package') );
			$bookingpress_all_language_translation_fields_section = array_merge($bookingpress_all_language_translation_fields_section,$bookingpress_custom_duration_step_section_added);
			return $bookingpress_all_language_translation_fields_section;
		}

		function bookingpress_modified_customize_form_language_translate_fields_func($bookingpress_all_language_translation_fields){
			$bookingpress_custom_service_language_translation_fields = array(                
				'package_appointment_booking_labels' => array(
                    'package_label_txt' => array('field_type'=>'text','field_label'=>__('Package label', 'bookingpress-package'),'save_field_type'=>'booking_form'),
                    'package_button_txt' => array('field_type'=>'text','field_label'=>__('Package apply button label', 'bookingpress-package'),'save_field_type'=>'booking_form'),
                    'package_placeholder_txt' => array('field_type'=>'textarea','field_label'=>__('Package placeholder', 'bookingpress-package'),'save_field_type'=>'booking_form'),
                    'package_applied_txt' => array('field_type'=>'textarea','field_label'=>__('Package applied text', 'bookingpress-package'),'save_field_type'=>'booking_form'),
                    'package_login_msg' => array('field_type'=>'textarea','field_label'=>__('Login Message', 'bookingpress-package'),'save_field_type'=>'booking_form'),
                    'package_error_msg' => array('field_type'=>'textarea','field_label'=>__('Error Message', 'bookingpress-package'),'save_field_type'=>'booking_form'),				)   
			);  
			$pos = 5;
			$bookingpress_all_language_translation_fields = array_slice($bookingpress_all_language_translation_fields, 0, $pos)+$bookingpress_custom_service_language_translation_fields + array_slice($bookingpress_all_language_translation_fields, $pos);
			return $bookingpress_all_language_translation_fields;
		}        

        /**
         * Function for add language translation fields
         *
         * @param  mixed $bookingpress_all_language_translation_fields
         * @return void
        */
        function bookingpress_modified_language_translate_fields_func($bookingpress_all_language_translation_fields){
            $bookingpress_package_language_translation_fields = array(                
                'package_appointment_booking_labels' => array(
                    'package_label_txt' => array('field_type'=>'text','field_label'=>__('Package label', 'bookingpress-package'),'save_field_type'=>'booking_form'),
                    'package_button_txt' => array('field_type'=>'text','field_label'=>__('Package apply button label', 'bookingpress-package'),'save_field_type'=>'booking_form'),
                    'package_placeholder_txt' => array('field_type'=>'textarea','field_label'=>__('Package placeholder', 'bookingpress-package'),'save_field_type'=>'booking_form'),
                    'package_applied_txt' => array('field_type'=>'textarea','field_label'=>__('Package applied text', 'bookingpress-package'),'save_field_type'=>'booking_form'),
                    'package_login_msg' => array('field_type'=>'textarea','field_label'=>__('Login Message', 'bookingpress-package'),'save_field_type'=>'booking_form'),
                    'package_error_msg' => array('field_type'=>'textarea','field_label'=>__('Error Message', 'bookingpress-package'),'save_field_type'=>'booking_form'),
                )                    
            );   
            $bookingpress_all_language_translation_fields = array_merge($bookingpress_all_language_translation_fields,$bookingpress_package_language_translation_fields);            
            return $bookingpress_all_language_translation_fields;
        }



        function bookingpress_modify_calculated_appointment_details_final_func( $bookingpress_appointment_details ){
            global $BookingPress;                                                       
            $final_payable_amount = $total_payable_amount =  ! empty( $bookingpress_appointment_details['bpa_final_payable_amount'] ) ? floatval( $bookingpress_appointment_details['bpa_final_payable_amount'] ) : 0;
            if(isset($bookingpress_appointment_details['is_complete_payment_request']) && $bookingpress_appointment_details['is_complete_payment_request'] == 'true'){
                if(isset($bookingpress_appointment_details['bookingpress_applied_package_data']['package_discount_price'])){                                        
                    $bookingpress_service_price = $BookingPress->bookingpress_price_formatter_with_currency_symbol( $total_payable_amount );
                    $bookingpress_appointment_details['total_payable_amount_with_currency'] = $bookingpress_service_price;
                    $bookingpress_appointment_details['total_payable_amount'] = $total_payable_amount;
                    $bookingpress_appointment_details['bpa_final_payable_amount'] = $total_payable_amount;                                        
                }
            }
            return $bookingpress_appointment_details;
        }        
        /**
         * Function for complete payment calculate payment total amount
         *
         * @param  mixed $bookingpress_payable_amount
         * @param  mixed $bookingpress_appointment_details
         * @return void
         */
        function bookingpress_modified_coupon_total_payable_amount_func($bookingpress_payable_amount, $bookingpress_appointment_details){

            if(isset($bookingpress_appointment_details['is_complete_payment_request']) && $bookingpress_appointment_details['is_complete_payment_request'] == 'true'){
                if(isset($bookingpress_appointment_details['bookingpress_applied_package_data']['package_discount_price'])){
                    $package_discount_price = floatval($bookingpress_appointment_details['bookingpress_applied_package_data']['package_discount_price']);                    
                    if( !empty($bookingpress_appointment_details['tax_price_display_options']) && $bookingpress_appointment_details['tax_price_display_options'] == "exclude_taxes" ){
                        $payment_gateway = !empty($bookingpress_appointment_details['selected_payment_method']) ? $bookingpress_appointment_details['selected_payment_method'] : '';        
                        $bookingpress_tax_percentage = $this->bookingpress_get_current_tax_percentage();    
                        if(!empty($bookingpress_appointment_details['tax_percentage']) && isset($bookingpress_appointment_details['tax_percentage'])){
                            $bookingpress_tax_percentage = $bookingpress_appointment_details['tax_percentage'];
                        }                   
                        $bookingpress_package_tax_amount = $package_discount_price * ($bookingpress_tax_percentage / 100);  
                        $package_discount_price = $package_discount_price + $bookingpress_package_tax_amount;                                                                        
                    }else if(!empty($bookingpress_appointment_details['tax_price_display_options']) && ($bookingpress_appointment_details['tax_price_display_options'] == "include_taxes")){                        
                        $bookingpress_included_tax_label = !empty($bookingpress_appointment_details['included_tax_label']) ? $bookingpress_appointment_details['included_tax_label'] : '';                        
                        $bookingpress_tax_percentage = $this->bookingpress_get_current_tax_percentage();      
                        if(!empty($bookingpress_appointment_details['tax_percentage']) && isset($bookingpress_appointment_details['tax_percentage'])){
                            $bookingpress_tax_percentage = $bookingpress_appointment_details['tax_percentage'];
                        }   
                        $bookingpress_included_tax_amount = ($final_payable_amount * $bookingpress_tax_percentage) / ( 100 + $bookingpress_tax_percentage );
                        $bookingpress_included_tax_amount_with_currency = $BookingPress->bookingpress_price_formatter_with_currency_symbol($bookingpress_included_tax_amount);                        
                    }                    
                    $bookingpress_payable_amount = $bookingpress_payable_amount - $package_discount_price;
                }
            }else{                
                if(isset($bookingpress_appointment_details['bookingpress_package_applied_data']['package_discount_price'])){                    
                    $package_discount_price = floatval($bookingpress_appointment_details['bookingpress_package_applied_data']['package_discount_price']); 
                    $bookingpress_payable_amount = $bookingpress_payable_amount - $package_discount_price;

                }
            }

            return $bookingpress_payable_amount;
        }

                
        /**
         * Function for complete payment link modified payment calculation data
         *
         * @param  mixed $bookingpress_appointment_details
         * @param  mixed $total_payable_amount
         * @return void
        */
        function bookingpress_modify_calculated_appointment_details_func( $bookingpress_appointment_details,$total_payable_amount ){

            global $BookingPress, $bookingpress_deposit_payment;
                                            
            $final_payable_amount = ! empty( $bookingpress_appointment_details['bpa_final_payable_amount'] ) ? floatval( $bookingpress_appointment_details['bpa_final_payable_amount'] ) : 0;

           
            if(isset($bookingpress_appointment_details['is_complete_payment_request']) && $bookingpress_appointment_details['is_complete_payment_request'] == 'true'){
                if(isset($bookingpress_appointment_details['bookingpress_applied_package_data']['package_discount_price'])){

                    $package_discount_price = floatval($bookingpress_appointment_details['bookingpress_applied_package_data']['package_discount_price']);
                    
                    if( !empty($bookingpress_appointment_details['tax_price_display_options']) && $bookingpress_appointment_details['tax_price_display_options'] == "exclude_taxes" ){
                        $payment_gateway = !empty($bookingpress_appointment_details['selected_payment_method']) ? $bookingpress_appointment_details['selected_payment_method'] : '';        
                        $bookingpress_tax_percentage = $this->bookingpress_get_current_tax_percentage();
                        if(isset($bookingpress_appointment_details['tax_percentage']) && !empty($bookingpress_appointment_details['tax_percentage'])){
                            $bookingpress_tax_percentage = $bookingpress_appointment_details['tax_percentage'];
                         }
                        $bookingpress_appointment_details['tax_percentage'] = $bookingpress_tax_percentage;     
                        $bookingpress_package_tax_amount = $package_discount_price * ($bookingpress_tax_percentage / 100);  
                        $package_discount_price = $package_discount_price + $bookingpress_package_tax_amount;                                                
                        $bookingpress_tax_amount = floatval($bookingpress_appointment_details['tax_amount_without_currency']) - $bookingpress_package_tax_amount;                    
                        $bookingpress_appointment_details['tax_amount'] = $BookingPress->bookingpress_price_formatter_with_currency_symbol( $bookingpress_tax_amount );
                        $bookingpress_appointment_details['tax_amount_without_currency'] = $bookingpress_tax_amount;
                        
                    }else if(!empty($bookingpress_appointment_details['tax_price_display_options']) && ($bookingpress_appointment_details['tax_price_display_options'] == "include_taxes")){
                        
                        $bookingpress_included_tax_label = !empty($bookingpress_appointment_details['included_tax_label']) ? $bookingpress_appointment_details['included_tax_label'] : '';                        
                        $bookingpress_tax_percentage = $this->bookingpress_get_current_tax_percentage();     
                        if(isset($bookingpress_appointment_details['tax_percentage']) && !empty($bookingpress_appointment_details['tax_percentage'])){
                            $bookingpress_tax_percentage = $bookingpress_appointment_details['tax_percentage'];
                        }
                        
                        $bookingpress_included_tax_amount = ($final_payable_amount * $bookingpress_tax_percentage) / ( 100 + $bookingpress_tax_percentage );
                        $bookingpress_included_tax_amount_with_currency = $BookingPress->bookingpress_price_formatter_with_currency_symbol($bookingpress_included_tax_amount);
                        $bookingpress_appointment_details['tax_amount_without_currency'] = $bookingpress_included_tax_amount; 
                        $bookingpress_appointment_details['tax_amount'] = $bookingpress_included_tax_amount_with_currency;
        
                        if(!empty($bookingpress_included_tax_label)){
                            $bookingpress_appointment_details['included_tax_label'] = str_replace('{bookingpress_tax_amount}', $bookingpress_included_tax_amount_with_currency, $bookingpress_appointment_details['included_tax_label']);
                        }
                        
                    }
                    $final_payable_amount = $final_payable_amount -  $package_discount_price;
                    $final_payable_amount = $final_payable_amount;
                    $total_payable_amount = $final_payable_amount = $final_payable_amount;                    

                    $bookingpress_service_price = $BookingPress->bookingpress_price_formatter_with_currency_symbol( $total_payable_amount );
                    $bookingpress_appointment_details['total_payable_amount_with_currency'] = $bookingpress_service_price;
                    $bookingpress_appointment_details['total_payable_amount'] = $total_payable_amount;                    
                    $bookingpress_appointment_details['bpa_final_payable_amount'] = $total_payable_amount;


                }
            }

            return $bookingpress_appointment_details;
        }

        /**
         * Function for calculate total amount in complete payment 
         *
         * @param  mixed $final_payable_amount
         * @param  mixed $bookingpress_appointment_details
         * @return void
         */
        function bookingpress_modify_recalculate_amount_before_calculation_func($final_payable_amount,$bookingpress_appointment_details) {
            
            global $BookingPress, $bookingpress_deposit_payment;

            if(isset($bookingpress_appointment_details['is_complete_payment_request']) && $bookingpress_appointment_details['is_complete_payment_request'] == 'true'){
                if(isset($bookingpress_appointment_details['bookingpress_applied_package_data']['package_discount_price'])){

                    $package_discount_price = floatval($bookingpress_appointment_details['bookingpress_applied_package_data']['package_discount_price']);
                    
                    if( !empty($bookingpress_appointment_details['tax_price_display_options']) && $bookingpress_appointment_details['tax_price_display_options'] == "exclude_taxes" ){
                        $payment_gateway = !empty($bookingpress_appointment_details['selected_payment_method']) ? $bookingpress_appointment_details['selected_payment_method'] : '';        
                        $bookingpress_tax_percentage = $this->bookingpress_get_current_tax_percentage();
                        if(isset($bookingpress_appointment_details['tax_percentage']) && !empty($bookingpress_appointment_details['tax_percentage'])){
                            $bookingpress_tax_percentage = $bookingpress_appointment_details['tax_percentage'];
                         }
                        $bookingpress_appointment_details['tax_percentage'] = $bookingpress_tax_percentage;        
                        $bookingpress_package_tax_amount = $package_discount_price * ($bookingpress_tax_percentage / 100);  
                        $package_discount_price = $package_discount_price + $bookingpress_package_tax_amount;                                                
                        $bookingpress_tax_amount = floatval($bookingpress_appointment_details['tax_amount_without_currency']) - $bookingpress_package_tax_amount;                    
                        $bookingpress_appointment_details['tax_amount'] = $BookingPress->bookingpress_price_formatter_with_currency_symbol( $bookingpress_tax_amount );
                        $bookingpress_appointment_details['tax_amount_without_currency'] = $bookingpress_tax_amount;
                        
                    }else if(!empty($bookingpress_appointment_details['tax_price_display_options']) && ($bookingpress_appointment_details['tax_price_display_options'] == "include_taxes")){
                        
                        $bookingpress_included_tax_label = !empty($bookingpress_appointment_details['included_tax_label']) ? $bookingpress_appointment_details['included_tax_label'] : '';                        
                        $bookingpress_tax_percentage = $this->bookingpress_get_current_tax_percentage();        
                        $bookingpress_included_tax_amount = ($final_payable_amount * $bookingpress_tax_percentage) / ( 100 + $bookingpress_tax_percentage );
                        $bookingpress_included_tax_amount_with_currency = $BookingPress->bookingpress_price_formatter_with_currency_symbol($bookingpress_included_tax_amount);
                        $bookingpress_appointment_details['tax_amount_without_currency'] = $bookingpress_included_tax_amount; 
                        $bookingpress_appointment_details['tax_amount'] = $bookingpress_included_tax_amount_with_currency;
        
                        if(!empty($bookingpress_included_tax_label)){
                            $bookingpress_appointment_details['included_tax_label'] = str_replace('{bookingpress_tax_amount}', $bookingpress_included_tax_amount_with_currency, $bookingpress_appointment_details['included_tax_label']);
                        }
                        
                    }
                    $final_payable_amount = $final_payable_amount -  $package_discount_price;
                    $final_payable_amount = $final_payable_amount;
                    $total_payable_amount = $final_payable_amount = $final_payable_amount;                    

                    $bookingpress_service_price = $BookingPress->bookingpress_price_formatter_with_currency_symbol( $total_payable_amount );
                    $bookingpress_appointment_details['total_payable_amount_with_currency'] = $bookingpress_service_price;
                    $bookingpress_appointment_details['total_payable_amount'] = $total_payable_amount;                    
                    $bookingpress_appointment_details['bpa_final_payable_amount'] = $total_payable_amount;


                }
            }          
            return floatval($final_payable_amount);
        }
        
        /**
         * Function for get tax percentage
         *
         * @return void
         */
        function bookingpress_get_current_tax_percentage(){
            global $BookingPress;
            $bookingpress_get_current_tax_percentage = $BookingPress->bookingpress_get_settings('tax_percentage', 'payment_setting');
            $bookingpress_get_current_tax_percentage = !empty($bookingpress_get_current_tax_percentage) ? floatval($bookingpress_get_current_tax_percentage) : 0;
            return $bookingpress_get_current_tax_percentage;
        }

        /**
         * Remove payment method when total amount is zero 
         *
         * @param  mixed $bookingpress_get_final_step_amount_after
         * @return void
         */
        function bookingpress_get_final_step_amount_after_func($bookingpress_get_final_step_amount_after){

            $bookingpress_get_final_step_amount_after.='
                if(vm.appointment_step_form_data.bookingpress_package_applied_data != ""){  
                    var bookingpress_check_total_amt = parseFloat(vm.appointment_step_form_data.total_payable_amount);
                    var bookingpress_check_package_discount_price_amt = vm.appointment_step_form_data.bookingpress_package_applied_data.package_discount_price;
                    if(bookingpress_check_total_amt == 0){
                        if(vm.appointment_step_form_data.selected_payment_method != "on-site"){
                            vm.appointment_step_form_data.selected_payment_method = " - ";
                        }
                        //if(vm.on_site_payment != "false"){                            
                            //vm.appointment_step_form_data.selected_payment_method = "on-site";
                        //}
                    }
                }
            ';

            return $bookingpress_get_final_step_amount_after;
        }    
        
        /**
         * Function for add complete payment sub total price
         *
         * @return void
         */
        function bookingpress_complete_payment_subtotal_price_after_func(){
        ?>
            <div class="bpa-fm--bs-amount-item" :class="((typeof(appointment_step_form_data.bookingpress_applied_package_data) != 'undefined' && appointment_step_form_data.bookingpress_applied_package_data != '')) ? 'bpa-fm--bs-amount-item--tax-module' : ''" v-if="appointment_step_form_data.is_cart == '0' && (typeof(appointment_step_form_data.bookingpress_applied_package_data) != 'undefined' && appointment_step_form_data.bookingpress_applied_package_data != '')">
                <div class="bpa-bs-ai__item">{{package_applied_txt}}</div> 										                
                <div class="bpa-bs-ai__item">-{{ bookingpress_package_discount_amount_with_currency }}</div>
            </div>            
        <?php 
        }

        /**
         * Complete Payment Data Modified For Package Addon
         *
         * @param  mixed $bookingpress_complete_payment_data_vars
         * @param  mixed $bookingpress_appointment_details
         * @return void
         */
        function modify_complate_payment_data_after_entry_create_func($bookingpress_complete_payment_data_vars, $bookingpress_appointment_details){
            global $BookingPress;
            $bookingpress_selected_currency = $bookingpress_appointment_details['bookingpress_service_currency'];
            $bookingpress_selected_currency = $BookingPress->bookingpress_get_currency_symbol($bookingpress_selected_currency);
            $bookingpress_complete_payment_data_vars['appointment_step_form_data']['bookingpress_applied_package_data'] = "";
            $bookingpress_complete_payment_data_vars['bookingpress_package_discount_amount_with_currency'] = "";
            $bookingpress_applied_package_data = (isset($bookingpress_appointment_details['bookingpress_applied_package_data'])) ? $bookingpress_appointment_details['bookingpress_applied_package_data'] : "";
            if(!empty($bookingpress_applied_package_data)){

                $bookingpress_complete_payment_data_vars['appointment_step_form_data']['is_complete_payment_request'] = 'true';

                $bookingpress_applied_package_data_arr = json_decode($bookingpress_applied_package_data,true);                
                if(!empty($bookingpress_applied_package_data_arr)){                    
                    $bookingpress_complete_payment_data_vars['appointment_step_form_data']['bookingpress_applied_package_data'] = $bookingpress_applied_package_data_arr;
                }                
                $bookingpress_package_discount_price = (isset($bookingpress_applied_package_data_arr['package_discount_price']) && !empty($bookingpress_applied_package_data_arr['package_discount_price']))?floatval($bookingpress_applied_package_data_arr['package_discount_price']):0;
                $bookingpress_complete_payment_data_vars['bookingpress_package_discount_amount_with_currency'] = $BookingPress->bookingpress_price_formatter_with_currency_symbol($bookingpress_package_discount_price, $bookingpress_selected_currency);
                if(!empty($bookingpress_appointment_details['bookingpress_extra_service_details'])){
                    $bookingpress_extra_service_details = json_decode($bookingpress_appointment_details['bookingpress_extra_service_details'], TRUE);
                    if(!empty($bookingpress_extra_service_details)){
                        $bookingpress_new_extra_service_details = array();
                        foreach($bookingpress_extra_service_details as $extrak=>$extraval){
                            $bookingpress_extra_services_id = $extraval['bookingpress_extra_service_details']['bookingpress_extra_services_id'];
                            $extraval['bookingpress_is_selected'] = 'true';
                            $bookingpress_new_extra_service_details[$bookingpress_extra_services_id] = $extraval;
                        }                        
                        $bookingpress_complete_payment_data_vars['appointment_step_form_data']['bookingpress_selected_extra_details'] = $bookingpress_new_extra_service_details;
                    }                    
                }
            }
            

            $package_applied_txt = $BookingPress->bookingpress_get_customize_settings('package_applied_txt', 'booking_form');
            $bookingpress_complete_payment_data_vars['package_applied_txt'] = $package_applied_txt; 

            return $bookingpress_complete_payment_data_vars;

        }
        
        /**
         * Function for add cart appointment base price
         *
         * @param  mixed $custom_package_data
         * @return void
         */
        function bookingpress_add_cart_package_data_func($custom_package_data){
            $custom_package_data.='                
                currentValue.base_price_without_currency = vm5.appointment_step_form_data.base_price_without_currency;
                currentValue.bookingpress_package_applied_data = ""; 
            ';
            return $custom_package_data;
        }

		/**
		 * Function for modify appointment listing details
		 *
		 * @param  mixed $bookingpress_appointment_data
		 * @return void
		*/
		function bookingpress_appointment_add_view_field_func($bookingpress_appointment_data, $get_appointment){
            $bookingpress_appointment_data['bookingpress_is_package'] = 0;          
            if(isset($get_appointment['bookingpress_purchase_type']) && $get_appointment['bookingpress_purchase_type'] == 3){
                $bookingpress_appointment_data['bookingpress_is_package'] = 1;          
            }
            return $bookingpress_appointment_data;
        }

        function bookingpress_backend_appointment_list_type_icons_func(){
        ?>
            <span class="material-icons-round bpa-apc__recurring-icon bpa-apc__package-icon">    
            <el-tooltip content="<?php esc_html_e('Package Transaction', 'bookingpress-package'); ?>" placement="top" v-if="scope.row.bookingpress_is_package == 1">                
                <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M7.98832 9.995H2.14277C1.6796 9.995 1.30061 10.3739 1.30061 10.8371V17.1579C1.30061 17.6211 1.6796 18 2.14277 18H8.08661C8.28473 18 8.28473 17.7872 8.28473 17.7872V10.2812C8.28467 10.2812 8.28467 9.995 7.98832 9.995ZM15.849 9.995H10.0094C9.65375 9.995 9.707 10.3654 9.707 10.3654V17.794C9.707 17.794 9.70412 17.9998 9.91806 17.9998H15.8489C16.3121 17.9998 16.6911 17.6209 16.6911 17.1577V10.8371C16.6912 10.3739 16.3122 9.995 15.849 9.995ZM8.28467 5.02167C8.28467 5.02167 8.28467 4.73631 8.00268 4.73631H1.19177C0.7286 4.73631 0.349609 5.1153 0.349609 5.57841V8.20717C0.349609 8.67034 0.7286 9.04927 1.19177 9.04927H8.02575C8.28467 9.04927 8.28467 8.82471 8.28467 8.82471V5.02167ZM16.8 4.73631H9.96733C9.70713 4.73631 9.70713 4.98425 9.70713 4.98425V8.82992C9.70713 8.82992 9.70713 9.04927 10.0235 9.04927H16.8C17.2631 9.04927 17.6421 8.67034 17.6421 8.20717V5.57841C17.6421 5.1153 17.2631 4.73631 16.8 4.73631ZM5.4203 4.11325C5.03499 4.11325 4.68306 4.08269 4.37444 4.02238C3.59057 3.86924 3.05181 3.57118 2.72737 3.11126C2.43667 2.69907 2.3477 2.19093 2.46286 1.60088C2.6646 0.568517 3.35791 0 4.41494 0C4.63864 0 4.88431 0.0258305 5.14519 0.0768166C5.8088 0.20646 6.65759 0.586985 7.41576 1.0947C8.70207 1.95619 8.76569 2.49175 8.70164 2.8197C8.60746 3.30158 8.15705 3.64591 7.32464 3.87243C6.76226 4.02545 6.06815 4.11325 5.4203 4.11325ZM4.415 1.34975C4.00564 1.34975 3.86495 1.46412 3.78771 1.85967C3.72451 2.18308 3.80421 2.29603 3.83035 2.33315C3.9398 2.48838 4.22492 2.61784 4.63324 2.69754C4.85357 2.74061 5.1258 2.76337 5.42024 2.76337C6.06772 2.76337 6.63814 2.66527 7.01664 2.55796C7.04419 2.55016 7.08696 2.51759 7.04112 2.49028C6.54629 2.08718 5.641 1.54891 4.88639 1.40147C4.71061 1.36724 4.55195 1.34975 4.415 1.34975ZM12.5909 4.11325H12.5908C11.943 4.11325 11.2489 4.02545 10.6865 3.87243C9.85407 3.64597 9.40372 3.30158 9.30954 2.81976C9.24555 2.49182 9.30905 1.95625 10.5955 1.09476C11.3535 0.587046 12.2023 0.206521 12.8661 0.076878C13.1269 0.0258919 13.3726 6.13551e-05 13.5961 6.13551e-05C14.6534 6.13551e-05 15.3466 0.568639 15.5482 1.601C15.6635 2.19099 15.5746 2.69913 15.2838 3.11132C14.9594 3.5713 14.4207 3.8693 13.6366 4.02244C13.3281 4.08263 12.9762 4.11325 12.5909 4.11325ZM10.9809 2.48194C10.937 2.5074 10.9582 2.54759 10.981 2.55415C11.3593 2.66294 11.9357 2.76344 12.5908 2.76344C12.8854 2.76344 13.1575 2.74067 13.3779 2.6976C13.7861 2.61784 14.0714 2.48844 14.1808 2.33321C14.207 2.29609 14.2868 2.18314 14.2234 1.85974C14.1462 1.46418 14.0055 1.34981 13.5961 1.34981C13.4592 1.34981 13.3006 1.36724 13.1247 1.4016C12.3701 1.54897 11.4757 2.07877 10.9809 2.48194Z"/>
                </svg>                                                        
            </el-tooltip>
            </span>
        <?php 
        }

        /**
         * Function for modified appointment payment fields
         *
         * @param  mixed $payment_log_data
         * @param  mixed $entry_data
         * @return void
         */
        function bookingpress_modify_payment_log_fields_before_insert_func($payment_log_data, $entry_data){

            //$payment_log_data['bookingpress_package_discount_amount'] = $entry_data['bookingpress_package_discount_amount'];            
            if($entry_data['bookingpress_purchase_type'] == 3){
                
                $payment_log_data['bookingpress_purchase_type'] = $entry_data['bookingpress_purchase_type'];
                $payment_log_data['bookingpress_package_id'] = $entry_data['bookingpress_package_id'];
                $payment_log_data['bookingpress_applied_package_data'] = $entry_data['bookingpress_applied_package_data'];
                $payment_log_data['bookingpress_package_discount_amount'] = $entry_data['bookingpress_package_discount_amount'];

                if( $payment_log_data['bookingpress_total_amount'] == 0 ){
                    $payment_log_data['bookingpress_payment_gateway'] = 'pre-paid';
                    $payment_log_data['bookingpress_payment_status']  = 1;
                }

            }
            return $payment_log_data;
        }        

        /**
         * Function for modified appointment fields
         *
         * @param  mixed $appointment_booking_fields
         * @param  mixed $entry_data
         * @return void
         */
        function bookingpress_modify_appointment_booking_fields_before_insert_func($appointment_booking_fields, $entry_data){

            //$appointment_booking_fields['bookingpress_package_discount_amount'] = $entry_data['bookingpress_package_discount_amount'];
            if($entry_data['bookingpress_purchase_type'] == 3){
                $appointment_booking_fields['bookingpress_purchase_type'] = $entry_data['bookingpress_purchase_type'];
                $appointment_booking_fields['bookingpress_package_id'] = $entry_data['bookingpress_package_id'];
                $appointment_booking_fields['bookingpress_applied_package_data'] = $entry_data['bookingpress_applied_package_data'];
                $appointment_booking_fields['bookingpress_package_discount_amount'] = $entry_data['bookingpress_package_discount_amount'];
            }
            return $appointment_booking_fields;
        }
        
        /**
         * Function for add cart entry data for package
         *
         * @param  mixed $bookingpress_entry_details
         * @param  mixed $posted_data
         * @param  mixed $cart_post_data
         * @return void
         */
        function bookingpress_modify_cart_entry_data_before_insert_func($bookingpress_entry_details, $posted_data,$cart_post_data){
            global $wpdb,$tbl_bookingpress_services,$BookingPress;
            if(!empty($posted_data['cart_items'])) {

                if(isset($posted_data['bookingpress_package_applied_data']) && !empty($posted_data['bookingpress_package_applied_data'])){ 

                    $package_discount_price = (isset($posted_data['bookingpress_package_applied_data']['package_discount_price']))?$posted_data['bookingpress_package_applied_data']['package_discount_price']:0;
                    $bookingpress_package_no = (isset($posted_data['bookingpress_package_applied_data']['bookingpress_package_no']))?$posted_data['bookingpress_package_applied_data']['bookingpress_package_no']:0;

                    if(isset($cart_post_data['bookingpress_package_applied_data']) && !empty($cart_post_data['bookingpress_package_applied_data'])){ 

                        $package_discount_price = (isset($cart_post_data['bookingpress_package_applied_data']['package_discount_price']))?$cart_post_data['bookingpress_package_applied_data']['package_discount_price']:0;
                        $bookingpress_package_no = (isset($cart_post_data['bookingpress_package_applied_data']['bookingpress_package_no']))?$cart_post_data['bookingpress_package_applied_data']['bookingpress_package_no']:0;
                        $bookingpress_entry_details['bookingpress_purchase_type'] = 3;
                        $bookingpress_entry_details['bookingpress_package_id'] = $bookingpress_package_no;
                        $bookingpress_package_applied_data_insert = (isset($cart_post_data['bookingpress_package_applied_data']))?$cart_post_data['bookingpress_package_applied_data']:array();                        
                        $bookingpress_entry_details['bookingpress_applied_package_data'] = json_encode($bookingpress_package_applied_data_insert);                        
                        $bookingpress_entry_details['bookingpress_package_discount_amount'] = $package_discount_price;
                        /*
                        $bookingpress_entry_details['bookingpress_deposit_amount'] = $cart_post_data['bookingpress_deposit_total'];
                        */
                    }                    

                }
                $bookingpress_applied_package = false;
                foreach($posted_data['cart_items'] as $cart_item){
                    if(isset($cart_item['bookingpress_package_applied_data']) && !empty($cart_item['bookingpress_package_applied_data'])){ 
                        
                        $bookingpress_entry_details['bookingpress_deposit_payment_details'] = "";                    
                        unset($bookingpress_entry_details['bookingpress_deposit_amount']);
                        break;

                    }
                }
            }
            return $bookingpress_entry_details;
        }

        /**
         * Package Appointment Data added
         *
         * @param  mixed $bookingpress_entry_details
         * @param  mixed $posted_data
         * @return void
         */
        function bookingpress_modify_entry_data_before_insert_func($bookingpress_entry_details, $posted_data) {
            global $wpdb,$tbl_bookingpress_services,$BookingPress;
            if(empty($posted_data['cart_items'])) {
                                
                if(isset($posted_data['bookingpress_package_applied_data']) && !empty($posted_data['bookingpress_package_applied_data'])){  

                    $package_discount_price = (isset($posted_data['bookingpress_package_applied_data']['package_discount_price']))?$posted_data['bookingpress_package_applied_data']['package_discount_price']:0;
                    $bookingpress_package_no = (isset($posted_data['bookingpress_package_applied_data']['bookingpress_package_no']))?$posted_data['bookingpress_package_applied_data']['bookingpress_package_no']:0;
                    $bookingpress_entry_details['bookingpress_purchase_type'] = 3;
                    $bookingpress_entry_details['bookingpress_package_id'] = $bookingpress_package_no;
                    $bookingpress_package_applied_data_insert = (isset($posted_data['bookingpress_package_applied_data']))?$posted_data['bookingpress_package_applied_data']:array();
                    $bookingpress_entry_details['bookingpress_applied_package_data'] = json_encode($bookingpress_package_applied_data_insert);
                    $bookingpress_entry_details['bookingpress_package_discount_amount'] = $package_discount_price;

                    $bookingpress_entry_details['bookingpress_deposit_payment_details'] = "";                    
                    unset($bookingpress_entry_details['bookingpress_deposit_amount']);

                }

            }
            return $bookingpress_entry_details;
        }

        /*
        function bookingpress_total_amount_modify_outside_arr_func($bookingpress_total_amount_modify_outside_arr){
            
            $bookingpress_total_amount_modify_outside_arr.='
            if(vm.appointment_step_form_data.bookingpress_package_applied_data != ""){
                
            }
            var bookingpress_package_discount_amount = parseFloat(vm.appointment_step_form_data.bookingpress_package_discount_amount);                    
            total_payable_amount = total_payable_amount - bookingpress_package_discount_amount;
            vm.appointment_step_form_data.total_payable_amount_with_currency = vm.bookingpress_price_with_currency_symbol( total_payable_amount );
            vm.appointment_step_form_data.total_payable_amount = total_payable_amount;            
            ';
            
            return $bookingpress_total_amount_modify_outside_arr;

        }
        */

        function bookingpress_sub_total_amount_payable_modify_outside_func($bookingpress_sub_total_amount_payable_modify_outside){

            $bookingpress_sub_total_amount_payable_modify_outside.='   
                var bookingpress_package_is_recurring_appointment = false;         
                if(typeof vm.appointment_step_form_data.recurring_appointments != "undefined"){
                    if(vm.appointment_step_form_data.is_recurring_appointments == true || vm.appointment_step_form_data.is_recurring_appointments == "true"){
                        bookingpress_package_is_recurring_appointment = true;
                    } 
                }
                var bookingpress_package_is_cart = false;
                if (typeof vm.appointment_step_form_data.cart_items != "undefined"){
                    bookingpress_package_is_cart = true;
                }
                var bookingpress_package_is_gift_card_applied = false;
                if(typeof vm.appointment_step_form_data.gift_card_code != "undefined" && vm.appointment_step_form_data.gift_card_code != ""){
                   var bookingpress_package_is_gift_card_applied = true;                   
                }

                if( typeof tax_amount != "undefined" ){
                    var calc_final_tax_amt = vm.appointment_step_form_data.tax_amount_org;
                    //total_payable_amount_without_tax = parseFloat( total_payable_amount ) - parseFloat( calc_final_tax_amt );
                }                 
                if(vm.is_tax_addon_active){                    
                   vm.appointment_step_form_data.tax_amount_without_currency = vm.appointment_step_form_data.tax_amount_without_currency_org;
                   vm.appointment_step_form_data.tax_amount = vm.appointment_step_form_data.tax_amount_org;
                }
                var package_discount_price = 0;
                total_payable_amount = total_payable_amount - package_discount_price;
                var total_tax_amount = 0;
                if(vm.is_tax_addon_active){
                    total_tax_amount = parseFloat(vm.appointment_step_form_data.tax_amount_without_currency);
                }                             
                if(bookingpress_package_is_cart && vm.is_tax_addon_active){
                    if(vm.appointment_step_form_data.bookingpress_cart_total != vm.appointment_step_form_data.bookingpress_cart_original_total){                        
                        if(vm.is_tax_addon_active && !bookingpress_package_is_recurring_appointment){
                            vm.appointment_step_form_data.bookingpress_cart_total = vm.appointment_step_form_data.bookingpress_cart_original_total;
                            let tax_percentage = parseFloat(vm.appointment_step_form_data.tax_percentage);
                            let tax_display_opt = vm.appointment_step_form_data.tax_price_display_options;
                            total_payable_amount = parseFloat(vm.appointment_step_form_data.bookingpress_cart_total);
                            let coupon_amt = 0;
                            if(typeof vm.appointment_step_form_data.coupon_discount_amount != "undefined" && vm.appointment_step_form_data.coupon_code != ""){   
                                coupon_amt = vm.appointment_step_form_data.coupon_discount_amount;
                            }
                            if(tax_percentage > 0){
                                if( "exclude_taxes" == tax_display_opt ){
                                    let calculated_tax_amount =  (total_payable_amount - coupon_amt) * ( tax_percentage / 100 );
                                    let final_tax_amount = total_payable_amount + calculated_tax_amount;
                                    vm.appointment_step_form_data.tax_amount_without_currency = calculated_tax_amount;
                                    vm.appointment_step_form_data.tax_amount = vm.bookingpress_price_with_currency_symbol( calculated_tax_amount );

                                    vm.appointment_step_form_data.bookingpress_cart_total = total_payable_amount + calculated_tax_amount;
                                    vm.appointment_step_form_data.calculated_tax_amount_org = calculated_tax_amount;
                                    vm.appointment_step_form_data.tax_amount_without_currency_org = vm.appointment_step_form_data.tax_amount_without_currency;
                                    vm.appointment_step_form_data.tax_amount_org = vm.appointment_step_form_data.tax_amount;                
                                } else {
                                    let calculated_tax_amount =  (( parseFloat(total_payable_amount) - coupon_amt) *  parseFloat( tax_percentage ) ) / ( 100 + parseFloat(tax_percentage) );
                                    vm.appointment_step_form_data.tax_amount_without_currency = 0;
                                    vm.appointment_step_form_data.tax_amount = vm.bookingpress_price_with_currency_symbol( calculated_tax_amount );
                                    vm.appointment_step_form_data.calculated_tax_amount_org = calculated_tax_amount;
                                    vm.appointment_step_form_data.tax_amount_without_currency_org = vm.appointment_step_form_data.tax_amount_without_currency;
                                    vm.appointment_step_form_data.tax_amount_org = vm.appointment_step_form_data.tax_amount;                
                                }                                                                
                            }
                            total_payable_amount = vm.appointment_step_form_data.bookingpress_cart_total;
                            tax_amount = vm.appointment_step_form_data.tax_amount_without_currency;
                            total_payable_amount_without_tax = parseFloat( total_payable_amount ) - parseFloat( tax_amount );
                        }
                    }
                }
                var package_discount_calculated_price = 0;
                if(!bookingpress_package_is_recurring_appointment && !bookingpress_package_is_cart){
                    if(vm.appointment_step_form_data.bookingpress_package_applied_data != ""){
                        //package_discount_price = parseFloat(vm.appointment_step_form_data.base_price_without_currency);                      
                        package_discount_price = parseFloat(vm.appointment_step_form_data.bookingpress_package_applied_data.package_discount_price);                                          
                    }
                }
                if(!bookingpress_package_is_cart && !bookingpress_package_is_gift_card_applied){

                    if(vm.is_tax_addon_active){
                        total_payable_amount_without_tax = total_payable_amount_without_tax  - package_discount_price;
                        let tax_percentage = vm.appointment_step_form_data.tax_percentage;
                        let tax_display_opt = vm.appointment_step_form_data.tax_price_display_options;
                        let coupon_amt = 0;
                        if(typeof vm.appointment_step_form_data.coupon_discount_amount != "undefined" && vm.appointment_step_form_data.coupon_code != ""){   
                            coupon_amt = vm.appointment_step_form_data.coupon_discount_amount;
                        }

                        if( "exclude_taxes" == tax_display_opt ){
                            let calculated_tax_amount =  (total_payable_amount_without_tax - coupon_amt ) * ( tax_percentage / 100 );
                            let final_tax_amount = total_payable_amount_without_tax + calculated_tax_amount;
                            vm.appointment_step_form_data.tax_amount_without_currency = calculated_tax_amount;
                            vm.appointment_step_form_data.tax_amount = vm.bookingpress_price_with_currency_symbol( calculated_tax_amount );
                            
                            total_payable_amount = total_payable_amount_without_tax + calculated_tax_amount;
                        } else {
                            if(typeof vm.appointment_step_form_data.coupon_code  == "undefined" || vm.appointment_step_form_data.coupon_code == ""){
                                let calculated_tax_amount =  ( parseFloat(total_payable_amount_without_tax) *  parseFloat( tax_percentage ) ) / ( 100 + parseFloat(tax_percentage) );
                                vm.appointment_step_form_data.tax_amount_without_currency = 0;
                                vm.appointment_step_form_data.tax_amount = vm.bookingpress_price_with_currency_symbol( calculated_tax_amount );

                                total_payable_amount = total_payable_amount  - package_discount_price;
                            }
                        }  
                    }else{
                        total_payable_amount = total_payable_amount  - package_discount_price;
                    }
                    
                    
                    vm.appointment_step_form_data.bookingpress_package_discount_amount = package_discount_price;
                    vm.appointment_step_form_data.bookingpress_package_discount_amount_with_currency = vm.bookingpress_price_with_currency_symbol( package_discount_price );                    

                }else{

                    if(!bookingpress_package_is_recurring_appointment){
                        if(vm.appointment_step_form_data.bookingpress_package_applied_data != ""){                            
                            package_discount_price = parseFloat(vm.appointment_step_form_data.bookingpress_package_applied_data.package_discount_price);   
                            if(vm.is_tax_addon_active){

                                total_payable_amount_without_tax = total_payable_amount_without_tax  - package_discount_price;
                                let tax_percentage = parseFloat(vm.appointment_step_form_data.tax_percentage);
                                let tax_display_opt = vm.appointment_step_form_data.tax_price_display_options;
                                
                                if(tax_percentage > 0){
                                    if( "exclude_taxes" == tax_display_opt ){
                                        let calculated_tax_amount =  total_payable_amount_without_tax * ( tax_percentage / 100 );
                                        let final_tax_amount = total_payable_amount_without_tax + calculated_tax_amount;
                                        vm.appointment_step_form_data.tax_amount_without_currency = calculated_tax_amount;
                                        vm.appointment_step_form_data.tax_amount = vm.bookingpress_price_with_currency_symbol( calculated_tax_amount );                                    
                                        if( true == bookingpress_package_is_cart ){
                                            vm.appointment_step_form_data.bookingpress_cart_total = total_payable_amount_without_tax + calculated_tax_amount;
                                        }
                                        total_payable_amount = total_payable_amount_without_tax + calculated_tax_amount;

                                    } else {
                                        let calculated_tax_amount =  ( parseFloat(total_payable_amount_without_tax) *  parseFloat( tax_percentage ) ) / ( 100 + parseFloat(tax_percentage) );
                                        vm.appointment_step_form_data.tax_amount_without_currency = 0;
                                        vm.appointment_step_form_data.tax_amount = vm.bookingpress_price_with_currency_symbol( calculated_tax_amount );            
                                        total_payable_amount = total_payable_amount  - package_discount_price;
                                    }
                                }else{
                                    total_payable_amount = total_payable_amount  - package_discount_price;  
                                }
                            }else{
                                total_payable_amount = total_payable_amount  - package_discount_price;                                
                            }
                            vm.appointment_step_form_data.bookingpress_package_discount_amount = package_discount_price;
                            vm.appointment_step_form_data.bookingpress_package_discount_amount_with_currency = vm.bookingpress_price_with_currency_symbol( package_discount_price );                            

                         }

                    }

                }
            ';

            return $bookingpress_sub_total_amount_payable_modify_outside;

        }
        
        
        /**
         * Front Side Package Redeem Request 
         *
         * @return void
         */
        function bookingpress_redeem_package_request_func($return_data = false,$user_id = ''){
            global $wpdb, $BookingPress;            
            $wpnonce               = isset( $_REQUEST['_wpnonce'] ) ? sanitize_text_field( $_REQUEST['_wpnonce'] ) : '';
            $bpa_verify_nonce_flag = wp_verify_nonce( $wpnonce, 'bpa_wp_nonce' );
            $response              = array();
            if ( ! $bpa_verify_nonce_flag ) {
                $response                = array();
                $response['variant']     = 'error';
                $response['title']       = esc_html__( 'Error', 'bookingpress-package' );
                $response['msg']         = esc_html__( 'Sorry, Your request can not be processed due to security reason.', 'bookingpress-package' );
                if($return_data){
                    return $response;
                }
                echo wp_json_encode( $response );
                die();
            }
            $response                         = array();
            $response['variant']              = 'error';
            $response['title']                = __( 'Error', 'bookingpress-package' );
            $response['msg']                  = __( 'Package appointments are not available.', 'bookingpress-package' );
            $package_error_msg = $BookingPress->bookingpress_get_customize_settings('package_error_msg', 'booking_form');
            $package_success_msg = __( 'Package succesfully redeem.', 'bookingpress-package' );
            if( !empty( $_POST['appointment_details'] ) && !is_array( $_POST['appointment_details'] ) ){
                $_POST['appointment_details'] = json_decode( stripslashes_deep( $_POST['appointment_details'] ), true ); //phpcs:ignore
            }            
            $bookingpress_selected_service = ! empty( $_POST['appointment_details']['selected_service'] ) ? intval( $_POST['appointment_details']['selected_service'] ) : 0;
            $bookingpress_payable_amount   = ! empty( $_POST['appointment_details']['total_payable_amount'] ) ? floatval( $_POST['appointment_details']['total_payable_amount'] ) : 0; 
            $bookingpress_appointment_details = !empty( $_POST['appointment_details'] ) ? array_map( array( $BookingPress, 'appointment_sanatize_field' ), $_POST['appointment_details'] ) : array();  // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized --Reason: $_POST['appointment_details'] has already been sanitized.           
            $bookingpress_selected_package = !empty( $_POST['appointment_details']['selected_package'] ) ? intval( $_POST['appointment_details']['selected_package'] ) : 0;
            $bookingpress_package_customer_id = !empty( $_POST['appointment_details']['bookingpress_package_customer_id'] ) ? intval( $_POST['appointment_details']['bookingpress_package_customer_id'] ) : 0;            
            $current_user_id = get_current_user_id();
            $package_applied_data = array();
            //base_price_without_currency
            $bookingpress_package_redeem_amount = ! empty( $_POST['appointment_details']['base_price_without_currency'] ) ? floatval( $_POST['appointment_details']['base_price_without_currency'] ) : 0;
            $is_recurring_appointments = isset($_POST['appointment_details']['is_recurring_appointments']) ? $_POST['appointment_details']['is_recurring_appointments']  : '';  //phpcs:ignore
            $bookingpress_cart_items = isset($_POST['appointment_details']['cart_items']) ? $_POST['appointment_details']['cart_items']  : ''; //phpcs:ignore
            $bookingpress_is_cart = 0;

            $bookingpress_package_applied_for_all_cart_appointments = '';

            if(empty($bookingpress_cart_items)){

                $selected_date = isset($_POST['appointment_details']['selected_date']) ? sanitize_text_field($_POST['appointment_details']['selected_date']):'';
                
                $custom_service_duration_value = isset($_POST['appointment_details']['custom_service_duration_value']) ? sanitize_text_field($_POST['appointment_details']['custom_service_duration_value']):'';

                $enable_custom_service_duration = isset($_POST['appointment_details']['enable_custom_service_duration']) ? sanitize_text_field($_POST['appointment_details']['enable_custom_service_duration']):'';

                if($bookingpress_selected_package && $enable_custom_service_duration != "true"){                                
                    $bookingpress_selected_bring_members = !empty( $_POST['appointment_details']['bookingpress_selected_bring_members'] ) ? intval( $_POST['appointment_details']['bookingpress_selected_bring_members'] ) : '';
                    if(!empty($bookingpress_selected_bring_members)){
                        $bookingpress_get_no_of_appointment_remaining_data = $this->bookingpress_get_no_of_appointment_remaining_data($bookingpress_selected_package,$bookingpress_package_customer_id,$bookingpress_selected_service,$selected_date,$user_id);
                        if(!empty($bookingpress_get_no_of_appointment_remaining_data)){
                            foreach($bookingpress_get_no_of_appointment_remaining_data as $remain_data){
                                $total_avaliable = $remain_data['total_avaliable'];
                                if($total_avaliable != 0){
                                    if($total_avaliable >= $bookingpress_selected_bring_members){

                                        $package_discount_price = $bookingpress_package_redeem_amount * $bookingpress_selected_bring_members;
                                        $remain_data['package_discount_price'] = $package_discount_price;
                                        $package_applied_data = $remain_data;
    
                                    }else{
                                        $response['msg'] = esc_html__('Only ', 'bookingpress-package') . $total_avaliable . esc_html__(' appointments are available in this package.', 'bookingpress-package');
                                    }                                
                                }else{
                                    $response['msg'] = __('All appointments are booked in this package.', 'bookingpress-package');
                                }
                            }
                        }
                    }
                }
                if(!empty($package_applied_data)){                
                    $response['variant']     = 'success';
                    $response['title']       = __('Success', 'bookingpress-package');
                    $response['bookingpress_is_cart']           = 0;
                    $response['bookingpress_has_applied_cart']  = 0;
                    $response['bookingpress_cart_items']        = array();                    
                    $response['msg']         = $package_success_msg;
                    $response['bookingpress_package_applied_for_all_cart_appointments']  = '';
                    $response['bookingpress_package_applied_data'] = $package_applied_data;
                }
            }else{
                $bookingpress_is_cart = 1;
                $bookingpress_has_applied_cart = 0;
                $bookingpress_service_wise_booked = array();
                $bookingpress_cart_total_package_discount = 0;
                $bookingpress_cart_package_name = '';
                $bookingpress_package_applied_for_all_cart_appointments = 'yes';
                foreach($bookingpress_cart_items as $key=>$cart_item){
                    
                    
                    $enable_custom_service_duration = (isset($cart_item['enable_custom_service_duration']))?$cart_item['enable_custom_service_duration']:'false';
                    $custom_service_duration_value = (isset($cart_item['custom_service_duration_value']))?$cart_item['custom_service_duration_value']:'';
                    $selected_date = (isset($cart_item['bookingpress_selected_date']))?$cart_item['bookingpress_selected_date']:'';
                    $bookingpress_cart_items[$key]['bookingpress_package_applied_data'] = '';
                    $bookingpress_service_id = $cart_item['bookingpress_service_id'];
                    $bookingpress_get_no_of_appointment_remaining_data = $this->bookingpress_get_no_of_appointment_remaining_data($bookingpress_selected_package,$bookingpress_package_customer_id,$bookingpress_service_id,$selected_date,$user_id);
                    $bookingpress_selected_bring_members = $cart_item['bookingpress_bring_anyone_selected_members'];
                    $bookingpress_package_redeem_amount = $cart_item['base_price_without_currency'];

                    if(!empty($bookingpress_get_no_of_appointment_remaining_data) && $enable_custom_service_duration != "true"){

                        foreach($bookingpress_get_no_of_appointment_remaining_data as $remain_data){
                            $total_avaliable = $remain_data['total_avaliable'];
                            if($total_avaliable != 0){
                                if(!empty($bookingpress_service_wise_booked)){
                                    foreach($bookingpress_service_wise_booked as $service_wise_booked){
                                        if($service_wise_booked['bookingpress_service_id'] == $bookingpress_service_id && $service_wise_booked['bookingpress_package_no'] == $remain_data['bookingpress_package_no']){
                                            $total_avaliable = $total_avaliable - $service_wise_booked['bookingpress_selected_bring_members'];
                                        }
                                    }                                 
                                }
                                if($total_avaliable >= $bookingpress_selected_bring_members){
                                    
                                    if(isset($bookingpress_service_wise_booked['serv'.$bookingpress_service_id.'pack'.$remain_data['bookingpress_package_no']])){

                                        $update_bookingpress_selected_bring_members = $bookingpress_service_wise_booked['serv'.$bookingpress_service_id.'pack'.$remain_data['bookingpress_package_no']]['bookingpress_selected_bring_members'];
                                        $update_bookingpress_selected_bring_members = $bookingpress_selected_bring_members + $update_bookingpress_selected_bring_members;
                                        $bookingpress_service_wise_booked['serv'.$bookingpress_service_id.'pack'.$remain_data['bookingpress_package_no']]['bookingpress_selected_bring_members'] = $update_bookingpress_selected_bring_members;

                                    }else{
                                        $bookingpress_service_wise_booked['serv'.$bookingpress_service_id.'pack'.$remain_data['bookingpress_package_no']] = array(
                                            'bookingpress_service_id' => $bookingpress_service_id,
                                            'bookingpress_selected_bring_members' => $bookingpress_selected_bring_members,
                                            'bookingpress_package_no' => $remain_data['bookingpress_package_no'],
                                        );    
                                    }
                                    $package_discount_price = $bookingpress_package_redeem_amount * $bookingpress_selected_bring_members;
                                    $bookingpress_cart_total_package_discount = $bookingpress_cart_total_package_discount + $package_discount_price;
                                    $remain_data['package_discount_price'] = $package_discount_price;
                                    $bookingpress_cart_items[$key]['bookingpress_package_applied_data'] = $remain_data;
                                    $package_applied_data = $remain_data;
                                    $bookingpress_has_applied_cart = 1;
                                    if(empty($bookingpress_cart_package_name)){
                                        $bookingpress_cart_package_name = $remain_data['bookingpress_package_name'];
                                    }                                    
                                    break;
                                }else{                                    
                                    $bookingpress_cart_items[$key]['package_error_msg'] = esc_html__('Only ', 'bookingpress-package') . $total_avaliable . esc_html__(' appointments are available in this package.', 'bookingpress-package');
                                }                                
                            }else{
                                $bookingpress_cart_items[$key]['package_error_msg'] = __('All appointments are booked in this package.', 'bookingpress-package');
                                $response['msg']         = __('All appointments are booked in this package.', 'bookingpress-package');
                            }
                        }
                        
                    }
                    
                    if(isset($bookingpress_cart_items[$key]['bookingpress_package_applied_data']) && empty($bookingpress_cart_items[$key]['bookingpress_package_applied_data'])){
                        $bookingpress_package_applied_for_all_cart_appointments = '';
                    }

                }

                if($bookingpress_has_applied_cart){                    
                    $response['variant']     = 'success';
                    $response['title']       = __('Success', 'bookingpress-package');
                    $response['bookingpress_is_cart']         = $bookingpress_is_cart;
                    $response['bookingpress_has_applied_cart']  = $bookingpress_has_applied_cart;
                    $response['bookingpress_cart_items']         = $bookingpress_cart_items;
                    $response['msg']         = $package_success_msg;
                    $response['bookingpress_package_applied_for_all_cart_appointments']  = $bookingpress_package_applied_for_all_cart_appointments;
                    $response['bookingpress_package_applied_data'] = array(
                        'package_discount_price' => $bookingpress_cart_total_package_discount,
                        'bookingpress_package_no' => 0,
                        'bookingpress_service_id' => 0,
                        'bookingpress_package_id' => 0,
                        'bookingpress_package_name' => $bookingpress_cart_package_name,
                    );    
                }                               
            }
            if($return_data){
                return $response;
            }            
            echo wp_json_encode( $response );
            die();
        }

        function bookingpress_get_no_of_appointment_remaining_data($package_id,$package_customer_id,$service_id,$appointment_selected_date,$user_id=""){
            global $wpdb,$BookingPress, $tbl_bookingpress_customers, $tbl_bookingpress_package_bookings,$bookingpress_package,$BookingPressPro;
            $package_service_data = array();
            if(!empty($package_id) && !empty($service_id) && !empty($appointment_selected_date)){

                $appointment_selected_date = date('Y-m-d',strtotime($appointment_selected_date));
                $current_user_id = get_current_user_id();
                if(!empty($user_id)){
                    $current_user_id = $user_id;
                }                
                $bookingpress_current_date = date('Y-m-d');
                $bookingpress_get_purchase_packages = $wpdb->get_results( $wpdb->prepare( "SELECT bookingpress_customer_id,bookingpress_login_user_id,bookingpress_package_id,bookingpress_package_expiration_date,bookingpress_package_no,bookingpress_package_services,bookingpress_package_name FROM {$tbl_bookingpress_package_bookings} WHERE bookingpress_package_expiration_date > %s AND bookingpress_package_id = %d AND bookingpress_login_user_id = %d AND (bookingpress_package_booking_status = %s OR bookingpress_package_booking_status = %s) AND bookingpress_package_expiration_date >= %s",$bookingpress_current_date, $package_id,$current_user_id,'1','4',$appointment_selected_date), ARRAY_A);  //phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared --Reason: $tbl_bookingpress_package_bookings is a table name. false alarm		
                if(!empty($bookingpress_get_purchase_packages)){
                    foreach($bookingpress_get_purchase_packages as $package_purchase){
                        $packge_services = (isset($package_purchase['bookingpress_package_services']))?$package_purchase['bookingpress_package_services']:'';
                        if(!empty($packge_services)){
                            $packge_services_arr = json_decode($packge_services,true);
                            if(!empty($packge_services_arr)){
                                foreach($packge_services_arr as $key=>$pack_single_serv){
                                    $bookingpress_no_of_appointments = $pack_single_serv['bookingpress_no_of_appointments'];
                                    $bookingpress_service_id = $pack_single_serv['bookingpress_service_id'];
                                    if($service_id == $bookingpress_service_id){

                                        $total_purchase = $bookingpress_package->get_package_service_purchase_count($package_purchase['bookingpress_package_no'],$bookingpress_service_id);
                                        $total_avaliable = $bookingpress_no_of_appointments - $total_purchase;
                                        $bookingpress_package_name = (isset($package_purchase['bookingpress_package_name']))?$package_purchase['bookingpress_package_name']:'';
                                        if($bookingpress_package->is_multi_language_addon_active()){
                                            if(method_exists( $BookingPressPro, 'bookingpress_pro_front_language_translation_func') ) {
                                                $bookingpress_package_id = (isset($package_purchase['bookingpress_package_id']))?$package_purchase['bookingpress_package_id']:'';
                                                if(!empty($bookingpress_package_id)){
                                                    $bookingpress_package_name = $BookingPressPro->bookingpress_pro_front_language_translation_func($bookingpress_package_name,'package','bookingpress_package_name',$bookingpress_package_id);
                                                }                                                
                                            }
                                        }
                                        $package_service_data[] = array(
                                            'bookingpress_package_no' => $package_purchase['bookingpress_package_no'],
                                            'bookingpress_package_id' => $package_purchase['bookingpress_package_id'],
                                            'bookingpress_customer_id' => $package_purchase['bookingpress_customer_id'],
                                            'bookingpress_login_user_id' => $package_purchase['bookingpress_login_user_id'],
                                            'bookingpress_service_id' => $bookingpress_service_id,
                                            'bookingpress_package_name' => $bookingpress_package_name,
                                            'total_avaliable' => $total_avaliable,
                                        );

                                    }
                                }                                
                            }
                        }
                    }
                }
            }            
            return $package_service_data;
        }

        /*
        function get_package_service_purchase_count($bookingpress_package_no,$bookingpress_service_id){
            global $tbl_bookingpress_appointment_bookings, $wpdb;
            $bookingpress_total_package_serv_booked = 0;
            if(!empty($bookingpress_package_no) && !empty($bookingpress_service_id)){
                $bookingpress_total_package_serv_booked = $wpdb->get_var($wpdb->prepare("SELECT SUM(bookingpress_selected_extra_members) as total FROM {$tbl_bookingpress_appointment_bookings} WHERE bookingpress_service_id = %d AND bookingpress_package_id = %d AND bookingpress_purchase_type = %d", $bookingpress_service_id, $bookingpress_package_no, 3));
                if(empty($bookingpress_total_package_serv_booked)){
                    $bookingpress_total_package_serv_booked = 0;
                }
            }
            return $bookingpress_total_package_serv_booked;
        }
        */

        /**
         * Function for add dynamic front vue method
         *
         * @return void
         */
        function bookingpress_booking_dynamic_vue_methods_func($bookingpress_vue_methods_data){
            global $wpdb, $BookingPress;
            $bookingpress_create_nonce      = wp_create_nonce( 'bpa_wp_nonce' );
            
            $bookingpress_vue_methods_data.='
            bookingpress_redeem_package_for_appointment(){
                const vm = this;
                vm.package_apply_loader = "1";
                vm.appointment_step_form_data.bookingpress_package_applied_data = "";
                var bookingpress_apply_package_data = {};
                bookingpress_apply_package_data.action = "bookingpress_redeem_package_request";
                bookingpress_apply_package_data.appointment_details = JSON.stringify(vm.appointment_step_form_data);
                var bkp_wpnonce_pre = "' . $bookingpress_create_nonce . '";
                var bkp_wpnonce_pre_fetch = document.getElementById("_wpnonce");
                if(typeof bkp_wpnonce_pre_fetch=="undefined" || bkp_wpnonce_pre_fetch==null){
                    bkp_wpnonce_pre_fetch = bkp_wpnonce_pre;
                }else{
                    bkp_wpnonce_pre_fetch = bkp_wpnonce_pre_fetch.value;
                }
                bookingpress_apply_package_data._wpnonce = bkp_wpnonce_pre_fetch;
                axios.post( appoint_ajax_obj.ajax_url, Qs.stringify( bookingpress_apply_package_data ) )
                .then( function (response) {
                    
                    vm.package_applied_status = response.data.variant;
                    if(response.data.variant == "error"){
                        vm.package_apply_code_msg = response.data.msg;
                        vm.bookingpress_set_error_msg(response.data.msg);
                        vm.appointment_step_form_data.bookingpress_package_applied_data ="";
                    } else {
                        if(vm.is_coupon_activated == "1"){
                            vm.appointment_step_form_data.coupon_code = "";
                            vm.coupon_code_msg = "";                            
                            vm.bpa_coupon_apply_disabled = 0;
                            vm.coupon_applied_status = "error";
                            vm.coupon_discounted_amount = "";
                            vm.appointment_step_form_data.coupon_discount_amount = 0;                            
                        }                        
                        var bookingpress_is_cart = response.data.bookingpress_is_cart;
                        if(bookingpress_is_cart == "0"){
                            vm.package_apply_code_msg = response.data.msg;
                            vm.appointment_step_form_data.bookingpress_package_applied_data = response.data.bookingpress_package_applied_data;                            
                            vm.appointment_step_form_data.bookingpress_package_applied_for_all_cart_appointments = "";

                            vm.bpa_package_apply_disabled = 1;                           
                        }else{
                            vm.package_apply_code_msg = response.data.msg;
                            vm.appointment_step_form_data.bookingpress_package_applied_data = response.data.bookingpress_package_applied_data;
                            vm.appointment_step_form_data.bookingpress_package_applied_for_all_cart_appointments = response.data.bookingpress_package_applied_for_all_cart_appointments;
                            vm.bpa_package_apply_disabled = 1;
                            var cart_items = response.data.bookingpress_cart_items;
                            if (typeof cart_items != "undefined"){                                
                                if(cart_items != "" && cart_items.length != 0){
                                    vm.appointment_step_form_data.cart_items = cart_items;
                                }
                            }
                            /*
                            if (typeof cart_items != "undefined"){
                                var cart_items_temp = cart_items;
                                if(cart_items_temp != "" && cart_items_temp.length != 0){
                                    var bookingpress_deposit_total = 0 ,bookingpress_deposit_due_amount_total=0;
                                    cart_items_temp.forEach(function(currentValue, index, arr){                                       
                                       var bookingpress_package_applied_data = cart_items_temp[index]["bookingpress_package_applied_data"];
                                       if(bookingpress_package_applied_data != ""){

                                          
                                            if( vm.bookingpress_is_deposit_payment_activate == 1){
                                                
                                                if (typeof cart_items_temp[index]["bookingpress_deposit_price_org"] == "undefined"){
                                                    cart_items_temp[index]["bookingpress_deposit_price_org"] = cart_items_temp[index]["bookingpress_deposit_price"];
                                                }
                                                if (typeof cart_items_temp[index]["bookingpress_deposit_due_amount_org"] == "undefined"){
                                                    cart_items_temp[index]["bookingpress_deposit_due_amount_org"] = cart_items_temp[index]["bookingpress_deposit_due_amount"];
                                                }
                                                
                                                var package_discount_price = bookingpress_package_applied_data.package_discount_price;                                          
                                                var bookingpress_service_original_price = cart_items_temp[index]["bookingpress_service_original_price"];
                                                var final_service_payable = parseFloat(bookingpress_service_original_price) - parseFloat(package_discount_price);
                                                                                                
                                                var bookingpress_deposit_amount = parseFloat(cart_items_temp[index]["services_meta"]["deposit_amount"]);
                                                var deposit_payment_type = cart_items_temp[index]["services_meta"]["deposit_type"];
                                                if(deposit_payment_type == "percentage"){
                                                    bookingpress_deposit_price = final_service_payable * (bookingpress_deposit_amount / 100);
                                                }else{
                                                    bookingpress_deposit_price = bookingpress_deposit_amount;
                                                }                                                
                                                var bookingpress_deposit_due_amount = final_service_payable - bookingpress_deposit_price;
                                                cart_items_temp[index]["bookingpress_deposit_price"] = bookingpress_deposit_price;
                                                cart_items[index]["bookingpress_deposit_price_with_currency"] = vm.bookingpress_price_with_currency_symbol(cart_items_temp[index]["bookingpress_deposit_price"]);                                                
                                                cart_items_temp[index]["bookingpress_deposit_due_amount"] = bookingpress_deposit_due_amount;
                                                cart_items[index]["bookingpress_deposit_due_amount_with_currency"] = vm.bookingpress_price_with_currency_symbol(cart_items_temp[index]["bookingpress_deposit_due_amount"]);
                                                bookingpress_deposit_total = parseFloat(bookingpress_deposit_total) + parseFloat(cart_items_temp[index]["bookingpress_deposit_price"]);
                                                bookingpress_deposit_due_amount_total = parseFloat(bookingpress_deposit_due_amount_total) + parseFloat(cart_items_temp[index]["bookingpress_deposit_due_amount"]);                                                


                                            }                                                                                      
                                          
                                       }
                                    });  
                                    cart_items = cart_items_temp;

                                }
                                vm.appointment_step_form_data.cart_items = cart_items; 
                            }                             
                            */                          
                        }
                    }
                    vm.bookingpress_get_final_step_amount();
                    setTimeout(function(){
                        vm.package_apply_loader = "0";
                    },1000);                                                            
                }.bind(this) )
                .catch( function (error) {
                    vm.bookingpress_set_error_msg(error);
                });
            },   
            bookingpress_remove_applied_package(){
                const vm = this;
                vm.appointment_step_form_data.bookingpress_package_applied_data = "";
                vm.appointment_step_form_data.bookingpress_package_applied_for_all_cart_appointments = "";
                vm.appointment_step_form_data.selected_package = ""; 
                if(vm.appointment_step_form_data.selected_payment_method != "on-site"){
                    vm.appointment_step_form_data.selected_payment_method = "";            
                }                  
                if (typeof vm.appointment_step_form_data.cart_items != "undefined"){
                    var cart_items_temp = vm.appointment_step_form_data.cart_items;
                    if(cart_items_temp != "" && cart_items_temp.length != 0){                        
                        cart_items_temp.forEach(function(currentValue, index, arr){
                            cart_items_temp[index]["bookingpress_package_applied_data"] = "";                            
                        });
                        vm.appointment_step_form_data.cart_items = cart_items_temp;
                    }
                }                 
                if(vm.is_coupon_activated == "1"){
                    vm.bookingpress_remove_coupon_code();
                }else{
                    vm.bookingpress_get_final_step_amount();
                }
            },
            ';

            return $bookingpress_vue_methods_data;
        }

        /**
         * Function for check package step change validation
         *
         * @param  mixed $bookingpress_dynamic_validation_for_step_change
         * @return void
         */
        function bookingpress_dynamic_validation_for_step_change_for_package_service($bookingpress_dynamic_validation_for_step_change ){
            $bookingpress_dynamic_validation_for_step_change.='            
                var vm6 = this;
                var current_step_for_package = vm.bookingpress_current_tab;                                                    
                if("basic_details" == next_tab && vm.bookingpress_current_tab != next_tab){

                    vm.appointment_step_form_data.bookingpress_package_applied_data = "";
                    vm.appointment_step_form_data.bookingpress_package_applied_for_all_cart_appointments = "";
                    if(vm.appointment_step_form_data.selected_payment_method != "on-site"){
                        vm.appointment_step_form_data.selected_payment_method = "";
                    }
                    vm.appointment_step_form_data.selected_package = "";                    
                    vm.bookingpress_disable_package_list = "true";

                    
                    if(typeof vm.appointment_step_form_data.cart_items != "undefined"){
                        //vm.appointment_step_form_data.bookingpress_customer_service_package_list = []; 
                        if(0 < vm.appointment_step_form_data.cart_items.length && vm.bookingpress_customer_package_list.length != 0 ){                            
                            var bookingpress_customer_package_list_temp = vm.bookingpress_customer_package_list;
                            vm.bookingpress_customer_package_list.forEach(function(currentValuereset, indexreset, arrreset){
                                if(currentValuereset.bookingpress_package_services.length > 0){                                
                                    currentValuereset.bookingpress_package_services.forEach(function(currentValue2, index2reset, arrreset2){                                    
                                        bookingpress_customer_package_list_temp[indexreset]["is_display"] = "0";                                 
                                    });
                                }    
                            });                             
                            vm.appointment_step_form_data.cart_items.forEach( function(currentValueCart,i,arrnew){

                                let current_selected_service_for_package = currentValueCart.bookingpress_service_id;                                
                                var current_selected_appointment_date = currentValueCart.bookingpress_selected_date;
                                let custom_duration_service = false;
                                if(vm.is_custom_duration_addon_active != ""){
                                    if(typeof currentValueCart.enable_custom_service_duration !== "undefined" && (currentValueCart.enable_custom_service_duration == "true" || currentValueCart.enable_custom_service_duration == true)){
                                        custom_duration_service = true;
                                    }                            
                                }                                

                                if(vm.bookingpress_customer_package_list.length != 0 && current_selected_appointment_date != "" && !custom_duration_service){                                                            
                                    vm.bookingpress_customer_package_list.forEach(function(currentValue, index, arr){
                                        if(currentValue.bookingpress_package_services.length > 0){                                
                                            currentValue.bookingpress_package_services.forEach(function(currentValue2, index2, arr2){    
                                                                               
                                                if(currentValue2.bookingpress_service_id == current_selected_service_for_package && currentValue2.bookingpress_package_expiration_date >= current_selected_appointment_date){
                                                    bookingpress_customer_package_list_temp[index]["is_display"] = "1";
                                                    vm.bookingpress_disable_package_list = "false";
                                                }else{                                                    
                                                    vm.bookingpress_customer_all_package_list.forEach(function(all_currentValue, all_index, all_arr){
                                                        
                                                        if(currentValue2.bookingpress_package_id == all_currentValue.bookingpress_package_id && all_currentValue.bookingpress_package_expiration_date >= current_selected_appointment_date){
                                                            all_currentValue.bookingpress_package_services.forEach(function(all_currentValue2, all_index2, all_arr2){ 
                                                                if(all_currentValue2.bookingpress_service_id == current_selected_service_for_package){
                                                                    bookingpress_customer_package_list_temp[index]["is_display"] = "1";
                                                                    vm.bookingpress_disable_package_list = "false";                                                                    
                                                                }
                                                            });
                                                        }
                                                    });
                                                }
                                            });
                                        }    
                                    });                                                            
                                }
                            }); 
                            vm.bookingpress_customer_package_list = bookingpress_customer_package_list_temp;                               
                        }                        
                    }else{
                        
                        let current_selected_service_for_package = vm.appointment_step_form_data.selected_service;
                        var current_selected_appointment_date = vm.appointment_step_form_data.selected_date;
                        let custom_duration_service = false;
                        if(vm.is_custom_duration_addon_active != ""){
                            if(typeof vm.appointment_step_form_data.enable_custom_service_duration !== "undefined" && (vm.appointment_step_form_data.enable_custom_service_duration == "true" || vm.appointment_step_form_data.enable_custom_service_duration == true)){
                                custom_duration_service = true;
                            }                            
                        }

                        vm.appointment_step_form_data.bookingpress_customer_service_package_list = [];                    
                        if(vm.bookingpress_customer_package_list.length != 0 && !custom_duration_service){
                            var bookingpress_customer_package_list_temp = vm.bookingpress_customer_package_list;
                            vm.bookingpress_customer_package_list.forEach(function(currentValue, index, arr){
                                if(currentValue.bookingpress_package_services.length > 0){                                
                                    currentValue.bookingpress_package_services.forEach(function(currentValue2, index2, arr2){                                    
                                        bookingpress_customer_package_list_temp[index]["is_display"] = "0";                                    
                                    });
                                }    
                            });   
                            if(current_selected_appointment_date != ""){                      
                                vm.bookingpress_customer_package_list.forEach(function(currentValue, index, arr){
                                    if(currentValue.bookingpress_package_services.length > 0){                                
                                        currentValue.bookingpress_package_services.forEach(function(currentValue2, index2, arr2){
                                            if(currentValue2.bookingpress_service_id == current_selected_service_for_package && currentValue2.bookingpress_package_expiration_date >= current_selected_appointment_date){                                            
                                                bookingpress_customer_package_list_temp[index]["is_display"] = "1";
                                                vm.bookingpress_disable_package_list = "false";
                                            }else{
                                                vm.bookingpress_customer_all_package_list.forEach(function(all_currentValue, all_index, all_arr){
                                                    if(currentValue2.bookingpress_package_id == all_currentValue.bookingpress_package_id && all_currentValue.bookingpress_package_expiration_date >= current_selected_appointment_date){
                                                        all_currentValue.bookingpress_package_services.forEach(function(all_currentValue2, all_index2, all_arr2){ 
                                                            if(all_currentValue2.bookingpress_service_id == current_selected_service_for_package){
                                                                bookingpress_customer_package_list_temp[index]["is_display"] = "1";
                                                                vm.bookingpress_disable_package_list = "false";                                                                    
                                                            }
                                                        });
                                                    }
                                                });                                            

                                            }
                                        });
                                    }    
                                }); 
                            }
                            vm.bookingpress_customer_package_list = bookingpress_customer_package_list_temp;                        
                        }

                    }

                }
            ';
            return $bookingpress_dynamic_validation_for_step_change;
        }

        /**
         * Function for add coupon input fields
         *
         * @return void
         */
        function bookingpress_add_content_before_coupon_code_data_frontend_func(){
        ?>        
        <div class="bpa-is-coupon-module-enable">
        <div class="bpa-fm--bs-amount-item bpa-is-coupon-applied" v-if="appointment_step_form_data.bookingpress_package_applied_data != ''">
            <div class="bpa-bs-ai__item">
                {{package_applied_txt}}											
                <span>{{ appointment_step_form_data.bookingpress_package_applied_data.bookingpress_package_name }}<svg @click="bookingpress_remove_applied_package" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M0 0h24v24H0V0z" fill="none"/><path d="M18.3 5.71c-.39-.39-1.02-.39-1.41 0L12 10.59 7.11 5.7c-.39-.39-1.02-.39-1.41 0-.39.39-.39 1.02 0 1.41L10.59 12 5.7 16.89c-.39.39-.39 1.02 0 1.41.39.39 1.02.39 1.41 0L12 13.41l4.89 4.89c.39.39 1.02.39 1.41 0 .39-.39.39-1.02 0-1.41L13.41 12l4.89-4.89c.38-.38.38-1.02 0-1.4z"/></svg></span>											
            </div>
            <div class="bpa-bs-ai__item bpa-is-ca__price">-{{ appointment_step_form_data.bookingpress_package_discount_amount_with_currency }}</div>
        </div>       
        <div class="bpa-fm--bs__coupon-module-textbox bpa-fm--bs__package-module-box" v-if="appointment_step_form_data.bookingpress_package_applied_data == '' && ( typeof appointment_step_form_data.is_waiting_list == 'undefined' || appointment_step_form_data.is_waiting_list == false ) && bookingpress_allow_package_apply == 1">
            <div class="bpa-cmt__left">
                <span class="bpa-front-form-label">{{package_label_txt}}</span>
            </div> 
            <div class="bpa-cmt__right">
                <div class="bpa-cmt__right-inner">
                    <el-select :disabled="(bookingpress_disable_package_list == 'true')?true:false" class="bpa-front-form-control" :placeholder="package_placeholder_txt" v-model="appointment_step_form_data.selected_package" popper-class="bpa-fm--service__advance-options-popper" @change="">
                        <el-option v-for="(item,key) in bookingpress_customer_package_list" v-if="item.is_display == '1'" :label="item.bookingpress_package_name" :value="item.bookingpress_package_id">{{ item.bookingpress_package_name }}</el-option>
                    </el-select>                
                    <el-button :disabled="(bookingpress_disable_package_list == 'true')?true:false" class="bpa-front-btn bpa-front-btn--primary" :class="(package_apply_loader == '1') ? 'bpa-front-btn--is-loader' : ''" @click="(bookingpress_disable_package_list == 'true')?'':bookingpress_redeem_package_for_appointment()">
                        <span class="bpa-btn__label">{{package_button_txt}}</span>                    
                        <div class="bpa-front-btn--loader__circles">
                            <div></div>
                            <div></div>
                            <div></div>
                        </div>
                    </el-button>
                </div>
                <div class="bpa-bs__coupon-validation --is-error" v-if="package_login_user_id == 0 || package_login_user_id == '0'"><p>{{package_login_msg}}</p></div>
            </div> 
        </div>
        </div>
        <?php 
        }

        /**
         * Function for check cart addon active or not
         *
         * @return void
         */
        function is_cart_addon_active(){
            $bookingpress_cart_addon  = 0;
            if(is_plugin_active('bookingpress-cart/bookingpress-cart.php')){
                $bookingpress_cart_addon = 1;
            }
            return $bookingpress_cart_addon;          
        }        

        /**
         * Function for check cart addon active or not
         *
         * @return void
         */
        function is_custom_duration_addon_active(){
            $bookingpress_custom_duration_addon  = "";
            if(is_plugin_active('bookingpress-custom-service-duration/bookingpress-custom-service-duration.php')){
                $bookingpress_custom_duration_addon = "1";
            }
            return $bookingpress_custom_duration_addon;          
        } 
        
        /**
         * Common function for get customer purchase package list
         *
         * @return void
        */
        function bookingpress_get_customer_purchase_package_list($user_id = ''){
            global $BookingPress,$tbl_bookingpress_customers, $wpdb, $tbl_bookingpress_package_bookings,$BookingPressPro,$bookingpress_package;
            
            $current_user_id = get_current_user_id();
            if(!empty($user_id)){
                $current_user_id = $user_id;
            }
            $bookingpress_current_date = date('Y-m-d');
            $bookingpress_customer_package_list = $bookingpress_customer_all_package_list = array();

                    $bookingpress_customer_id = (isset($get_current_user_customer['bookingpress_customer_id']))?$get_current_user_customer['bookingpress_customer_id']:0;
                    $bookingpress_current_date = date('Y-m-d');
                    $bookingpress_get_purchase_packages = $wpdb->get_results( $wpdb->prepare( "SELECT bookingpress_package_expiration_date,bookingpress_package_id,bookingpress_package_no,bookingpress_package_services,bookingpress_package_name FROM {$tbl_bookingpress_package_bookings} WHERE bookingpress_package_expiration_date > %s AND bookingpress_login_user_id = %d AND (bookingpress_package_booking_status = %s OR bookingpress_package_booking_status = %s)", $bookingpress_current_date,$current_user_id,'1','4'), ARRAY_A); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared --Reason: $tbl_bookingpress_package_bookings is a table name. false alarm		
                    
                    $package_added_id = array('none');
                    if(!empty($bookingpress_get_purchase_packages)){

                        foreach($bookingpress_get_purchase_packages as $packge_data){
                            $packge_services = (isset($packge_data['bookingpress_package_services']))?$packge_data['bookingpress_package_services']:'';
                            if(!empty($packge_services)){
                                $packge_services_arr = json_decode($packge_services,true);
                                if(!empty($packge_services_arr)){
                                    $package_service_final_arr = array();

                                    foreach($packge_services_arr as $key=>$pack_single_serv){

                                        $packge_services_arr[$key]['available_no_of_appointments'] = $pack_single_serv['bookingpress_no_of_appointments'];
                                        $bookingpress_available_no_of_appointments = $packge_services_arr[$key]['available_no_of_appointments'];
                                        $bookingpress_service_id = $packge_services_arr[$key]['bookingpress_service_id'];
                                        $total_purchase = $bookingpress_package->get_package_service_purchase_count($packge_data['bookingpress_package_no'],$bookingpress_service_id);
                                        if($bookingpress_available_no_of_appointments > $total_purchase){
                                            $package_service_final_arr[] = $pack_single_serv;
                                        }

                                    }
                                    if(!empty($package_service_final_arr)){
                                        if(!in_array($packge_data['bookingpress_package_id'],$package_added_id)){

                                            $bookingpress_package_name = (isset($packge_data['bookingpress_package_name']))?$packge_data['bookingpress_package_name']:'';
                                            $bookingpress_package_id = (isset($packge_data['bookingpress_package_id']))?$packge_data['bookingpress_package_id']:'';

                                            if($bookingpress_package->is_multi_language_addon_active()){
                                                if(method_exists( $BookingPressPro, 'bookingpress_pro_front_language_translation_func') ) {                                                    
                                                    $bookingpress_package_name = $BookingPressPro->bookingpress_pro_front_language_translation_func($bookingpress_package_name,'package','bookingpress_package_name',$bookingpress_package_id);                                                    
                                                }
                                            }                                            
                                            $bookingpress_customer_package_list[] = array(
                                                'bookingpress_package_id' => $packge_data['bookingpress_package_id'],
                                                'bookingpress_package_expiration_date' => $packge_data['bookingpress_package_expiration_date'],
                                                'bookingpress_package_no' => $packge_data['bookingpress_package_no'],
                                                'bookingpress_package_name' => $bookingpress_package_name,
                                                'is_display' => '0',
                                                'bookingpress_package_services' => $package_service_final_arr,                                        
                                            );
                                        }
                                        $bookingpress_customer_all_package_list[] = array(
                                            'bookingpress_package_id' => $packge_data['bookingpress_package_id'],
                                            'bookingpress_package_expiration_date' => $packge_data['bookingpress_package_expiration_date'],
                                            'bookingpress_package_no' => $packge_data['bookingpress_package_no'],
                                            'bookingpress_package_name' => $bookingpress_package_name,
                                            'is_display' => '0',
                                            'bookingpress_package_services' => $package_service_final_arr,                                        
                                        );                                    
                                        $package_added_id[] = $packge_data['bookingpress_package_id'];    
                                    }
                                }                                
                            }
                        }

            }            

            return array('bookingpress_customer_package_list' => $bookingpress_customer_package_list, 'bookingpress_customer_all_package_list' => $bookingpress_customer_all_package_list);

        }

        /**
         * Function for add front package appointment vue data
         *
         * @param  mixed $bookingpress_front_vue_data_fields
         * @return void
        */
        function bookingpress_frontend_apointment_form_add_dynamic_data_func($bookingpress_front_vue_data_fields) {

            global $BookingPress,$tbl_bookingpress_customers, $wpdb, $tbl_bookingpress_package_bookings,$BookingPressPro,$bookingpress_package;

            $package_label_txt = $BookingPress->bookingpress_get_customize_settings('package_label_txt', 'booking_form');
            $bookingpress_front_vue_data_fields['package_label_txt'] = $package_label_txt;
            $package_button_txt = $BookingPress->bookingpress_get_customize_settings('package_button_txt', 'booking_form');
            $bookingpress_front_vue_data_fields['package_button_txt'] = $package_button_txt;
            $package_placeholder_txt = $BookingPress->bookingpress_get_customize_settings('package_placeholder_txt', 'booking_form');
            $bookingpress_front_vue_data_fields['package_placeholder_txt'] = $package_placeholder_txt;
            $package_applied_txt = $BookingPress->bookingpress_get_customize_settings('package_applied_txt', 'booking_form');
            $bookingpress_front_vue_data_fields['package_applied_txt'] = $package_applied_txt;            
            $package_login_msg = $BookingPress->bookingpress_get_customize_settings('package_login_msg', 'booking_form');
            $bookingpress_front_vue_data_fields['package_login_msg'] = $package_login_msg;
            $package_error_msg = $BookingPress->bookingpress_get_customize_settings('package_error_msg', 'booking_form');
            $bookingpress_front_vue_data_fields['package_error_msg'] = $package_error_msg;

            $bookingpress_front_vue_data_fields['package_apply_loader'] = 0;
            $bookingpress_front_vue_data_fields['bpa_package_apply_disabled'] = 0;
            $bookingpress_front_vue_data_fields['package_applied_status'] = '';            
            $bookingpress_front_vue_data_fields['package_apply_code_msg'] = '';
            $bookingpress_front_vue_data_fields['bookingpress_allow_package_apply'] = 1;     

            $bookingpress_front_vue_data_fields['appointment_step_form_data']['package_redeem_amount'] = 0;

            $current_user_id = get_current_user_id();
            $bookingpress_package_purchase_service_list = array();
            $bookingpress_customer_package_list = array();
            $bookingpress_customer_service_package_list = array();
            $bookingpress_customer_id = '';
            $bookingpress_customer_all_package_list = array();
            if($current_user_id){

                $bookingpress_get_all_customer_purchase_package_list = $this->bookingpress_get_customer_purchase_package_list();
                if(isset($bookingpress_get_all_customer_purchase_package_list['bookingpress_customer_all_package_list'])){
                    $bookingpress_customer_all_package_list = $bookingpress_get_all_customer_purchase_package_list['bookingpress_customer_all_package_list'];
                }                
                if(isset($bookingpress_get_all_customer_purchase_package_list['bookingpress_customer_package_list'])){
                    $bookingpress_customer_package_list = $bookingpress_get_all_customer_purchase_package_list['bookingpress_customer_package_list'];
                }

            }
            $bookingpress_front_vue_data_fields['appointment_step_form_data']['bookingpress_package_customer_id'] = $bookingpress_customer_id;
            //$bookingpress_front_vue_data_fields['bookingpress_package_purchase_service_list'] = $bookingpress_package_purchase_service_list;
            $bookingpress_front_vue_data_fields['bookingpress_customer_all_package_list'] = $bookingpress_customer_all_package_list;
            $bookingpress_front_vue_data_fields['bookingpress_customer_package_list'] = $bookingpress_customer_package_list;
            $bookingpress_front_vue_data_fields['appointment_step_form_data']['bookingpress_customer_service_package_list'] = $bookingpress_customer_service_package_list;

            
            
            $bookingpress_front_vue_data_fields['package_login_user_id'] = $current_user_id;
            $bookingpress_front_vue_data_fields['appointment_step_form_data']['selected_package'] = '';
            $bookingpress_front_vue_data_fields['appointment_step_form_data']['selected_package_data'] = '';
            $bookingpress_front_vue_data_fields['bookingpress_disable_package_list'] = 'true';
            $bookingpress_front_vue_data_fields['appointment_step_form_data']['bookingpress_package_applied_data'] = '';

            $bookingpress_front_vue_data_fields['appointment_step_form_data']['bookingpress_package_applied_for_all_cart_appointments'] = '';

            $bookingpress_front_vue_data_fields['appointment_step_form_data']['bookingpress_package_discount_amount'] = 0;
            $bookingpress_front_vue_data_fields['appointment_step_form_data']['bookingpress_package_discount_amount_with_currency'] = '';
            $bookingpress_front_vue_data_fields['appointment_step_form_data']['tax_amount_without_currency_org'] = 0;            

            $is_tax_addon_active = is_plugin_active('bookingpress-tax/bookingpress-tax.php');
            $is_cart_addon_active = $this->is_cart_addon_active();
            $is_custom_duration_addon_active = $this->is_custom_duration_addon_active();
                        
            $bookingpress_front_vue_data_fields['is_custom_duration_addon_active'] = $is_custom_duration_addon_active;

            $bookingpress_front_vue_data_fields['is_cart_addon_active'] = $is_cart_addon_active;
            $bookingpress_front_vue_data_fields['is_tax_addon_active'] = ($is_tax_addon_active)?'yes':'';

            return $bookingpress_front_vue_data_fields;
        }


        function bookingpress_before_save_customize_booking_form_func($booking_form_settings){

            $package_label_txt = ! empty($_POST['front_label_edit_data']['package_label_txt']) ? sanitize_text_field($_POST['front_label_edit_data']['package_label_txt']) : ''; // phpcs:ignore WordPress.Security.NonceVerification           
            $booking_form_settings['front_label_edit_data']['package_label_txt'] = $package_label_txt; 

            $package_placeholder_txt = ! empty($_POST['front_label_edit_data']['package_placeholder_txt']) ? sanitize_text_field($_POST['front_label_edit_data']['package_placeholder_txt']) : ''; // phpcs:ignore WordPress.Security.NonceVerification
            $booking_form_settings['front_label_edit_data']['package_placeholder_txt'] = $package_placeholder_txt; 

            $package_applied_txt = ! empty($_POST['front_label_edit_data']['package_applied_txt']) ? sanitize_text_field($_POST['front_label_edit_data']['package_applied_txt']) : ''; // phpcs:ignore WordPress.Security.NonceVerification          
            $booking_form_settings['front_label_edit_data']['package_applied_txt'] = $package_applied_txt;             

            $package_button_txt = ! empty($_POST['front_label_edit_data']['package_button_txt']) ? sanitize_text_field($_POST['front_label_edit_data']['package_button_txt']) : ''; // phpcs:ignore WordPress.Security.NonceVerification         
            $booking_form_settings['front_label_edit_data']['package_button_txt'] = $package_button_txt; 

            $package_login_msg = ! empty($_POST['front_label_edit_data']['package_login_msg']) ? sanitize_text_field($_POST['front_label_edit_data']['package_login_msg']) : ''; // phpcs:ignore WordPress.Security.NonceVerification          
            $booking_form_settings['front_label_edit_data']['package_login_msg'] = $package_login_msg; 

            $package_error_msg = ! empty($_POST['front_label_edit_data']['package_error_msg']) ? sanitize_text_field($_POST['front_label_edit_data']['package_error_msg']) : ''; // phpcs:ignore WordPress.Security.NonceVerification          
            $booking_form_settings['front_label_edit_data']['package_error_msg'] = $package_error_msg;             

            return $booking_form_settings;

        }
        
        function bookingpress_customize_add_dynamic_data_fields_func($bookingpress_customize_vue_data_fields){
            
            $bookingpress_customize_vue_data_fields['front_label_edit_data']['package_label_txt'] = '';                        
            $bookingpress_customize_vue_data_fields['front_label_edit_data']['package_button_txt'] = '';                        
            $bookingpress_customize_vue_data_fields['front_label_edit_data']['package_placeholder_txt'] = '';
            $bookingpress_customize_vue_data_fields['front_label_edit_data']['package_applied_txt'] = '';                        
            $bookingpress_customize_vue_data_fields['front_label_edit_data']['package_login_msg'] = '';   
            $bookingpress_customize_vue_data_fields['front_label_edit_data']['package_error_msg'] = '';                        

            
            return $bookingpress_customize_vue_data_fields;
        }
            
        /**
         * Function for add package customization fields
         *
         * @param  mixed $bookingpress_booking_form_data
         * @return void
        */
        function bookingpress_get_booking_form_customize_data_filter_func($bookingpress_booking_form_data){
                
            $bookingpress_booking_form_data['front_label_edit_data']['package_label_txt'] =  __('Redeem Package', 'bookingpress-package');   
            $bookingpress_booking_form_data['front_label_edit_data']['package_button_txt'] =  __('Redeem', 'bookingpress-package');   
            $bookingpress_booking_form_data['front_label_edit_data']['package_placeholder_txt'] =  __('Select package', 'bookingpress-package');
            $bookingpress_booking_form_data['front_label_edit_data']['package_applied_txt'] =  __('Package Redemption', 'bookingpress-package');   
            $bookingpress_booking_form_data['front_label_edit_data']['package_login_msg'] =  __('You must be logged in to continue.', 'bookingpress-package'); 
            $bookingpress_booking_form_data['front_label_edit_data']['package_error_msg'] =  __('Package appointment not avaliable.', 'bookingpress-package');

            return $bookingpress_booking_form_data;
        }

        /**
         * Function for add package label
         *
         * @return void
         */
        function bookingpress_add_customize_extra_section_func(){ ?>
            <div class="bpa-cs-sp-sub-module--separator"></div>
            <div class="bpa-sm--item">
                <h5 class="bpa-sm-sub-heading--item"><?php esc_html_e( 'Package labels', 'bookingpress-package' ); ?></h5>
            </div>
            <div class="bpa-sm--item">
                <label class="bpa-form-label"><?php esc_html_e('Package label', 'bookingpress-package'); ?></label>
                <el-input v-model="front_label_edit_data.package_label_txt " class="bpa-form-control"></el-input>
            </div> 
            <div class="bpa-sm--item">
                <label class="bpa-form-label"><?php esc_html_e('Package placeholder', 'bookingpress-package'); ?></label>
                <el-input v-model="front_label_edit_data.package_placeholder_txt " class="bpa-form-control"></el-input>
            </div>
            <div class="bpa-sm--item">
                <label class="bpa-form-label"><?php esc_html_e('Package applied text', 'bookingpress-package'); ?></label>
                <el-input v-model="front_label_edit_data.package_applied_txt " class="bpa-form-control"></el-input>
            </div>                 
            <div class="bpa-sm--item">
                <label class="bpa-form-label"><?php esc_html_e('Package apply button label', 'bookingpress-package'); ?></label>
                <el-input v-model="front_label_edit_data.package_button_txt " class="bpa-form-control"></el-input>
            </div> 
            <div class="bpa-sm--item">
                <label class="bpa-form-label"><?php esc_html_e('Login Message', 'bookingpress-package'); ?></label>
                <el-input v-model="front_label_edit_data.package_login_msg " class="bpa-form-control"></el-input>
            </div> 
            <div class="bpa-sm--item">
                <label class="bpa-form-label"><?php esc_html_e('Error Message', 'bookingpress-package'); ?></label>
                <el-input v-model="front_label_edit_data.package_error_msg " class="bpa-form-control"></el-input>
            </div>
        <?php 
        }        


    }

	global $bookingpress_package_appointment_book;
	$bookingpress_package_appointment_book = new bookingpress_package_appointment_book();
}