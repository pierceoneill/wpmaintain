<?php
if (!class_exists('bookingpress_package_order')) {
	class bookingpress_package_order Extends BookingPress_Core {

        function __construct(){

            global $wp, $wpdb, $tbl_bookingpress_packages, $tbl_bookingpress_package_services, $tbl_bookingpress_package_images, $BookingPress, $tbl_bookingpress_package_bookings,$tbl_bookingpress_package_bookings_meta,$bookingpress_package;

           /* 
            $tbl_bookingpress_packages = $wpdb->prefix.'bookingpress_packages';
            $tbl_bookingpress_package_services = $wpdb->prefix.'bookingpress_package_services';
            $tbl_bookingpress_package_images = $wpdb->prefix.'bookingpress_package_images';
            $tbl_bookingpress_package_bookings = $wpdb->prefix.'bookingpress_package_bookings';
            */

            $package_addon_working = $bookingpress_package->bookingpress_check_package_addon_requirement();  

            if( is_plugin_active( 'bookingpress-appointment-booking-pro/bookingpress-appointment-booking-pro.php' ) && !empty( $BookingPress->bpa_pro_plugin_version() ) && version_compare( $BookingPress->bpa_pro_plugin_version(), '3.2', '>=' ) && $package_addon_working){

                add_filter('bookingpress_modify_capability_data', array($this, 'bookingpress_modify_capability_data_func'), 11, 1);

                /* Load Default Data Variables */
                add_action( 'admin_init', array( $this, 'bookingpress_package_order_vue_data_fields_func') );
                add_filter( 'bookingpress_package_order_dynamic_view_load', array( $this, 'bookingpress_package_order_dynamic_view_func' ), 10 );
                add_action( 'bookingpress_package_order_dynamic_vue_methods', array( $this, 'bookingpress_package_order_vue_methods_func' ) );
                add_action( 'bookingpress_package_order_dynamic_on_load_methods', array( $this, 'bookingpress_package_order_on_load_methods_func' ) );
                add_action( 'bookingpress_package_order_dynamic_data_fields', array( $this, 'bookingpress_package_order_dynamic_data_fields_func' ) );
                add_action( 'bookingpress_package_order_dynamic_helper_vars', array( $this, 'bookingpress_package_order_dynamic_helper_vars_func' ) );
                
                /* Save package order */
                add_action('wp_ajax_bookingpress_save_package_order_booking', array( $this, 'bookingpress_save_package_order_booking_func' ), 10);

                /* Add for allow date filter option */
                add_filter('bookingpress_allowed_disable_date_filter_pickeroptions',array($this,'bookingpress_allowed_disable_date_filter_pickeroptions_func'),10,2);

                /* Add tel js in package order page */
                add_filter('bookingpress_allowed_tel_input_script_backend',array($this,'bookingpress_allowed_tel_input_script_backend_func'),10,2);

                /* Add filter for save package booking */
                add_filter('bookingpress_before_appointment_confirm_booking_check_package_booking',array($this,'bookingpress_before_appointment_confirm_booking_func'),20,9);
                //bookingpress_before_appointment_confirm_booking

                /* get package orders */
                add_action('wp_ajax_bookingpress_get_package_order', array( $this, 'bookingpress_get_package_order_func' ));

                /* get package customer list */
                add_action('wp_ajax_bookingpress_get_search_customer_list_package',array($this,'bookingpress_get_search_customer_list_func'));

                /* Function for delete package order */
                add_action('wp_ajax_bookingpress_delete_package_order', array( $this, 'bookingpress_delete_package_order_func' ));
                add_action('wp_ajax_bookingpress_bulk_package_order', array( $this, 'bookingpress_bulk_package_order_func' ));

                /* Change Package Order Status */
                add_action('wp_ajax_bookingpress_change_package_order_status', array( $this, 'bookingpress_change_package_order_status_func' ));

                /* Edit package order */
                add_action('wp_ajax_bookingpress_get_edit_package_order_data', array( $this, 'bookingpress_get_edit_package_order_data_func' ), 10);

                /* Get Package Order Meta Value */
                add_action('wp_ajax_bookingpress_get_package_order_meta_values', array($this, 'bookingpress_get_package_order_meta_values_func'));

                /* Function for add package custom fields meta value to assign package booking id */
                add_action('bookingpress_after_add_package_order',array($this,'bookingpress_after_add_package_order_func'),10,2);

                /* Filter for change payment record column name */
                add_filter('bookingpress_payment_transaction_item_label',array($this,'bookingpress_payment_transaction_item_label_func'),10,1);
                add_filter('bookingpress_payment_add_view_field', array($this, 'bookingpress_payment_add_view_field_func'), 10, 2);
                add_filter('bookingpress_modify_payments_listing_data', array($this, 'bookingpress_modify_payments_listing_data_func'), 25, 1);
                add_action('bookingpress_backend_payment_list_type_icons',array($this,'bookingpress_backend_payment_list_type_icons_func'));
                /* Back end payment filter over */

                /*Backend & Fron End Email notification section related hooks - start*/
                add_action('wp_ajax_bookingpress_get_package_customer_list',array($this,'bookingpress_get_package_customer_list_func'));

                add_action('bookingpress_modify_payment_appointment_section',array($this,'bookingpress_modify_payment_appointment_section_func'),10);
                add_action('bookingpress_add_default_notification_section',array($this,'bookingpress_add_default_notification_section_package_func'),10);
                add_action('bookingpress_add_dynamic_notification_data_fields',array($this,'bookingpress_add_dynamic_notification_data_fields_package_func'),10);
                add_filter( 'bookingpress_add_global_option_data', array( $this, 'bookingpress_add_global_option_data_package_func' ), 10 );
                add_action('bookingpress_notification_external_message_plachoders',array( $this, 'bookingpress_notification_external_message_package_plachoders' ), 10 );
                add_filter('add_bookingpress_default_notification_status',array( $this, 'add_bookingpress_default_notification_status_package_func' ), 10,2 );
                
                
                /* Package order expiry date update */
                add_action('wp_ajax_bookingpress_update_package_order_expiry_date',array($this,'bookingpress_update_package_order_expiry_date_func'),10);

                /*Backend & Fron End Email notification section related hooks - over*/

                add_filter('bookingpress_selected_gateway_label_name_package',array($this,'bookingpress_selected_gateway_label_name_package_func'),10, 2);

            }
        }

        function bookingpress_selected_gateway_label_name_package_func($payment_gateway_label, $payment_gateway){
			global $BookingPress;
		 	$payment_gateway_label_temp = $payment_gateway_label;
			if(!empty($payment_gateway) && ($payment_gateway == 'on-site' || $payment_gateway == 'on site') ) {
                $payment_gateway_label = $BookingPress->bookingpress_get_customize_settings('locally_text','package_booking_form');
            } elseif(!empty($payment_gateway) && $payment_gateway != 'manual') {
				$payment_gateway_label = $BookingPress->bookingpress_get_customize_settings($payment_gateway.'_text','package_booking_form');
                if(empty($payment_gateway_label)) {
					$payment_gateway_label = $payment_gateway_label_temp;
				}
			}  
			return $payment_gateway_label;
		}
        
        /**
         * Function for package order expiry date update
         *
         * @return void
        */
        function bookingpress_update_package_order_expiry_date_func(){
            
			global $wpdb, $BookingPress, $BookingPressPro,$tbl_bookingpress_package_bookings,$bookingpress_global_options;
			$response = array();

			$bpa_check_authorization = $this->bpa_check_authentication( 'package_order_expiry_date_update', true, 'bpa_wp_nonce' );
			if( preg_match( '/error/', $bpa_check_authorization ) ){
				$bpa_auth_error = explode( '^|^', $bpa_check_authorization );
				$bpa_error_msg = !empty( $bpa_auth_error[1] ) ? $bpa_auth_error[1] : esc_html__( 'Sorry. Something went wrong while processing the request','bookingpress-package');

				$response['variant'] = 'error';
				$response['title'] = esc_html__( 'Error', 'bookingpress-package');
				$response['msg'] = $bpa_error_msg;

				wp_send_json( $response );
				die;
			}
			$response['variant'] = 'error';
			$response['title'] = esc_html__('Error','bookingpress-package');
			$response['msg'] = esc_html__('Something went wrong while process with update package expire date','bookingpress-package');            
            $package_expiry_date_update_form = ! empty( $_REQUEST['package_expiry_date_update_form'] ) ? array_map( array( $BookingPress, 'appointment_sanatize_field' ), $_REQUEST['package_expiry_date_update_form'] ) : array();// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized --Reason $_REQUEST contains mixed array and will be sanitized using 'appointment_sanatize_field' function
            $package_updated_expiration_date = (isset($package_expiry_date_update_form['package_updated_expiration_date']))?$package_expiry_date_update_form['package_updated_expiration_date']:'';
            $bookingpress_package_booking_id = (isset($package_expiry_date_update_form['bookingpress_package_booking_id']))?$package_expiry_date_update_form['bookingpress_package_booking_id']:'';
            if(!empty($package_updated_expiration_date) && !empty($bookingpress_package_booking_id)){

                $bookingpress_get_purchase_packages = $wpdb->get_row( $wpdb->prepare( "SELECT bookingpress_package_expiration_date FROM {$tbl_bookingpress_package_bookings} WHERE bookingpress_package_booking_id = %d", $bookingpress_package_booking_id), ARRAY_A); //phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- Reason: $tbl_bookingpress_package_bookings is table name defined globally. False Positive alarm
                if(!empty($bookingpress_get_purchase_packages)){

                    $bookingpress_package_expiration_date = (isset($bookingpress_get_purchase_packages['bookingpress_package_expiration_date']))?$bookingpress_get_purchase_packages['bookingpress_package_expiration_date']:'';
                    if(!empty($bookingpress_package_expiration_date)){

                        if($package_updated_expiration_date > $bookingpress_package_expiration_date){                         
                            $wpdb->update( $tbl_bookingpress_package_bookings, array('bookingpress_package_expiration_date' => $package_updated_expiration_date), array('bookingpress_package_booking_id' => $bookingpress_package_booking_id));                            

                            $response['variant']      = 'success';                            
                            $response['title']        = esc_html__( 'Success', 'bookingpress-package' );
                            $response['msg']          = esc_html__( 'Package expire date successfully changed.', 'bookingpress-package' );
                            $response['redirect_url'] = '';
                            echo wp_json_encode( $response );
                            die();                             

                        }else{

                            $response['variant']      = 'error';
                            $response['title']        = esc_html__( 'Error', 'bookingpress-package' );
                            $response['msg']          = esc_html__( 'Sorry, the selected date is less than the package expiration date.', 'bookingpress-package' );
                            $response['redirect_url'] = '';
                            echo wp_json_encode( $response );
                            die();                            


                        }

                    }

                }


            }
            
			wp_send_json( $response );
			die;
        }

       
        /**
         * add_bookingpress_default_notification_status_package_func
         *
         * @param  mixed $bookingpress_default_notification_status_data
         * @param  mixed $bookingpres_default_notification_data
         * @return void
         */
        function add_bookingpress_default_notification_status_package_func($bookingpress_default_notification_status_data, $bookingpres_default_notification_data)
        {
            global $BookingPress, $bookingpress_pro_manage_notifications;
            foreach ( $bookingpres_default_notification_data as $bookingpress_default_notification_key => $bookingpress_default_notification_val ) {
				$bookingpress_notification_value         = ( $bookingpress_default_notification_val['bookingpress_notification_status'] == 1 ) ? true : false;
				$bookingpress_notification_receiver_type = $bookingpress_default_notification_val['bookingpress_notification_receiver_type'];

				switch ( $bookingpress_default_notification_val['bookingpress_notification_name'] ) {
					case 'Package Order':
						$bookingpress_default_notification_status_data[ $bookingpress_notification_receiver_type ]['package_order'] = $bookingpress_notification_value;
						break;
				}
			}
			return $bookingpress_default_notification_status_data;
        }

        /**
         * bookingpress_notification_external_message_package_plachoders
         *
         * @return void
         */
        function bookingpress_notification_external_message_package_plachoders(){
            ?>
            <div class="bpa-gs__cb--item-tags-body" v-if="bookingpress_package_order_placeholder != '' && bookingpress_active_email_notification == 'package_order'">
                <div>
                    <span class="bpa-tags--item-sub-heading"><?php esc_html_e('Package Order', 'bookingpress-package'); ?></span>
                    <span class="bpa-tags--item-body" v-for="item in bookingpress_package_order_placeholder" @click="bookingpress_insert_placeholder(item.value);">
                        {{ item.name }}
                    </span>
                </div>
            </div>
            <?php
        }       

        /**
         * bookingpress_add_global_option_data_package_func
         *
         * @param  mixed $global_data
         * @return void
         */
        function bookingpress_add_global_option_data_package_func($global_data)
        {
            $data = array(
				'package_order_placeholders' => wp_json_encode(
					array(
						array(
							'value' => '%package_name%',
							'name'  => '%package_name%',
						),
                        array(
                            'value' => '%package_duration%',
                            'name'  => '%package_duration%',
                        ),
						array(
							'value' => '%package_purchase_date%',
							'name'  => '%package_purchase_date%',
						),
						array(
							'value' => '%package_expiry_date%',
							'name'  => '%package_expiry_date%',
						),
                        array(
							'value' => '%package_status%',
							'name'  => '%package_status%',
						),
                        array(
							'value' => '%package_payment_amount%',
							'name'  => '%package_payment_amount%',
						), 
                        array(
							'value' => '%package_services_added%',
							'name'  => '%package_services_added%',
						),
                        array(
							'value' => '%package_booking_id%',
							'name'  => '%package_booking_id%',
						),
                        array(
							'value' => '%package_payment_method%',
							'name'  => '%package_payment_method%',
						),
                        array(
							'value' => '%package_tax_amount%',
							'name'  => '%package_tax_amount%',
						),
					)
				),
			);
			$global_data = array_merge( $global_data, $data );
            return $global_data;
        }
                
        /**
         * bookingpress_add_dynamic_notification_data_fields_package_func
         *
         * @param  mixed $bookingpress_notification_vue_methods_data
         * @return void
         */
        function bookingpress_add_dynamic_notification_data_fields_package_func($bookingpress_notification_vue_methods_data){
            global $bookingpress_global_options;
            $bookingpress_options  = $bookingpress_global_options->bookingpress_global_options();
            $bookingpress_package_order_placeholders = $bookingpress_options['package_order_placeholders'];
            $bookingpress_notification_vue_methods_data['bookingpress_package_order_placeholder']  = json_decode($bookingpress_package_order_placeholders);	
            return $bookingpress_notification_vue_methods_data;
        }

        /**
         * bookingpress_add_default_notification_section_package_func
         *
         * @return void
         */
        function bookingpress_add_default_notification_section_package_func()
        {
            ?>
            <div class="bpa-en-left_item-body--list__item" :class="bookingpress_active_email_notification == 'package_order' ? '__bpa-is-active' : ''" ref="refundpayment" @click='bookingpress_select_email_notification("<?php esc_html_e('Package Order Notification', 'bookingpress-package'); ?>","Package Order", "package_order")'>
                <span class="material-icons-round --bpa-item-status is-enabled" v-if="default_notification_status['customer']['package_order'] == true || default_notification_status['employee']['package_order'] == true">circle</span>
                <span class="material-icons-round --bpa-item-status" v-else>circle</span>
                <p><?php esc_html_e( 'Package Order', 'bookingpress-package' ); ?></p>
            </div>
            <?php
        }
        
        /**
         * Add Package Calculation
         *
         * @return void
         */
        function bookingpress_modify_payment_appointment_section_func(){
        ?>
            <div class="bpa-pd__item" v-if="scope.row.bookingpress_applied_package_data != ''">
                <span><?php esc_html_e('Package', 'bookingpress-package'); ?> ( {{ scope.row.bookingpress_applied_package_data.bookingpress_package_name }} )</span>
                <p>{{ scope.row.bookingpress_package_discount_amount_with_currency }}</p>
            </div>            
        <?php 
        }

        function bookingpress_get_package_customer_list_func(){
			global $wpdb, $BookingPress, $BookingPressPro;
			$response                       = array();

            $bpa_check_authorization = $this->bpa_check_authentication( 'retrieve_customers', true, 'bpa_wp_nonce' );
            
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
            $search_user_str = ! empty( $_REQUEST['search_user_str'] ) ? ( sanitize_text_field($_REQUEST['search_user_str'] )) : ''; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.MissingUnslash
            $bookingpress_customer_id = ! empty( $_REQUEST['customer_id'] ) ? ( sanitize_text_field($_REQUEST['customer_id'] )) : ''; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.MissingUnslash

			if(!empty($search_user_str)) {                  
                $response['variant'] = 'success';
                $response['title'] = esc_html__('Success', 'bookingpress-package');
                $response['msg'] = esc_html__('Data retrieved successfully', 'bookingpress-package');
                $response['appointment_customers_details'] = array();
                $bookingpress_appointment_customers_details = $this->bookingpress_get_package_customer_list($search_user_str,$bookingpress_customer_id);			
                $response['appointment_customers_details'] = $bookingpress_appointment_customers_details;
            }    
			echo wp_json_encode($response);
			exit;            
        }


        /**
         * Get appointment customers list
         *
         * @param  mixed $search_user_str
         * @param  mixed $customer_id
         * @return void
         */
        function bookingpress_get_package_customer_list($search_user_str = '',$customer_id = '')
        {
            global $wpdb,$tbl_bookingpress_customers;
            $bookingpress_search_query_where = 'WHERE 1=1 AND cs.bookingpress_wpuser_id != 0 ';
            $bookingpress_search_query_where .= $wpdb->prepare( ' AND cs.bookingpress_user_type = %d AND cs.bookingpress_user_status = %d ', 2,1 );
            if(!empty($search_user_str)) {
                if(!empty($customer_id )) {
                    $bookingpress_search_query_where .= "AND ((cs.bookingpress_user_login LIKE '%{$search_user_str}%' OR cs.bookingpress_user_email LIKE '%{$search_user_str}%' OR cs.bookingpress_user_firstname LIKE '%{$search_user_str}%' OR cs.bookingpress_user_lastname LIKE '%{$search_user_str}%') OR (cs.bookingpress_customer_id = {$customer_id})) ";
                } else {
                    $bookingpress_search_query_where .= "AND (cs.bookingpress_user_login LIKE '%{$search_user_str}%' OR cs.bookingpress_user_email LIKE '%{$search_user_str}%' OR cs.bookingpress_user_firstname LIKE '%{$search_user_str}%' OR cs.bookingpress_user_lastname LIKE '%{$search_user_str}%') ";
                }
            }   
            if(empty($search_user_str) && !empty($customer_id )) {
                $bookingpress_search_query_where .= $wpdb->prepare( 'AND (cs.bookingpress_customer_id =%d) ', $customer_id );
            }
            $bookingpress_search_join_query  = '';
            $bookingpress_search_join_query  = apply_filters('bookingpress_appointment_customer_list_join_filter', $bookingpress_search_join_query);
            $bookingpress_search_query_where = apply_filters('bookingpress_appointment_customer_list_filter', $bookingpress_search_query_where);

            $bookingpress_customer_details = $wpdb->get_results('SELECT cs.bookingpress_user_phone,cs.bookingpress_customer_id,cs.bookingpress_customer_id,cs.bookingpress_user_firstname,cs.bookingpress_user_lastname,cs.bookingpress_user_email FROM ' . $tbl_bookingpress_customers . ' as cs ' . $bookingpress_search_join_query . ' ' . $bookingpress_search_query_where, ARRAY_A); //phpcs:ignore
            $bookingpress_customer_selection_details = array();
            foreach ( $bookingpress_customer_details as $bookingpress_customer_key => $bookingpress_customer_val ) {
                $bookingpress_customer_name = ( $bookingpress_customer_val['bookingpress_user_firstname'] == '' && $bookingpress_customer_val['bookingpress_user_lastname'] == '' ) ? $bookingpress_customer_val['bookingpress_user_email'] : $bookingpress_customer_val['bookingpress_user_firstname'] . ' ' . $bookingpress_customer_val['bookingpress_user_lastname'];
                if(empty($bookingpress_customer_name) && $bookingpress_customer_val['bookingpress_user_phone']) {
                    $bookingpress_customer_name = $bookingpress_customer_val['bookingpress_user_phone'];
                }
                $bookingpress_customer_selection_details[] = array(
                'text'  => stripslashes_deep($bookingpress_customer_name),
                'value' => $bookingpress_customer_val['bookingpress_customer_id'],
                );
            }            
            return $bookingpress_customer_selection_details;
        } 

        /**
         * Function for add recurring payment icon
         *
         * @return void
        */
        function bookingpress_backend_payment_list_type_icons_func(){
        ?>
            <el-tooltip content="<?php esc_html_e('Package Order Transaction', 'bookingpress-package'); ?>" placement="top" v-if="scope.row.is_package_purchase != 'undefined' && scope.row.is_package_purchase == '1'">
                <span class="material-icons-round bpa-apc__package-icon"> 
                    <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M7.98832 9.995H2.14277C1.6796 9.995 1.30061 10.3739 1.30061 10.8371V17.1579C1.30061 17.6211 1.6796 18 2.14277 18H8.08661C8.28473 18 8.28473 17.7872 8.28473 17.7872V10.2812C8.28467 10.2812 8.28467 9.995 7.98832 9.995ZM15.849 9.995H10.0094C9.65375 9.995 9.707 10.3654 9.707 10.3654V17.794C9.707 17.794 9.70412 17.9998 9.91806 17.9998H15.8489C16.3121 17.9998 16.6911 17.6209 16.6911 17.1577V10.8371C16.6912 10.3739 16.3122 9.995 15.849 9.995ZM8.28467 5.02167C8.28467 5.02167 8.28467 4.73631 8.00268 4.73631H1.19177C0.7286 4.73631 0.349609 5.1153 0.349609 5.57841V8.20717C0.349609 8.67034 0.7286 9.04927 1.19177 9.04927H8.02575C8.28467 9.04927 8.28467 8.82471 8.28467 8.82471V5.02167ZM16.8 4.73631H9.96733C9.70713 4.73631 9.70713 4.98425 9.70713 4.98425V8.82992C9.70713 8.82992 9.70713 9.04927 10.0235 9.04927H16.8C17.2631 9.04927 17.6421 8.67034 17.6421 8.20717V5.57841C17.6421 5.1153 17.2631 4.73631 16.8 4.73631ZM5.4203 4.11325C5.03499 4.11325 4.68306 4.08269 4.37444 4.02238C3.59057 3.86924 3.05181 3.57118 2.72737 3.11126C2.43667 2.69907 2.3477 2.19093 2.46286 1.60088C2.6646 0.568517 3.35791 0 4.41494 0C4.63864 0 4.88431 0.0258305 5.14519 0.0768166C5.8088 0.20646 6.65759 0.586985 7.41576 1.0947C8.70207 1.95619 8.76569 2.49175 8.70164 2.8197C8.60746 3.30158 8.15705 3.64591 7.32464 3.87243C6.76226 4.02545 6.06815 4.11325 5.4203 4.11325ZM4.415 1.34975C4.00564 1.34975 3.86495 1.46412 3.78771 1.85967C3.72451 2.18308 3.80421 2.29603 3.83035 2.33315C3.9398 2.48838 4.22492 2.61784 4.63324 2.69754C4.85357 2.74061 5.1258 2.76337 5.42024 2.76337C6.06772 2.76337 6.63814 2.66527 7.01664 2.55796C7.04419 2.55016 7.08696 2.51759 7.04112 2.49028C6.54629 2.08718 5.641 1.54891 4.88639 1.40147C4.71061 1.36724 4.55195 1.34975 4.415 1.34975ZM12.5909 4.11325H12.5908C11.943 4.11325 11.2489 4.02545 10.6865 3.87243C9.85407 3.64597 9.40372 3.30158 9.30954 2.81976C9.24555 2.49182 9.30905 1.95625 10.5955 1.09476C11.3535 0.587046 12.2023 0.206521 12.8661 0.076878C13.1269 0.0258919 13.3726 6.13551e-05 13.5961 6.13551e-05C14.6534 6.13551e-05 15.3466 0.568639 15.5482 1.601C15.6635 2.19099 15.5746 2.69913 15.2838 3.11132C14.9594 3.5713 14.4207 3.8693 13.6366 4.02244C13.3281 4.08263 12.9762 4.11325 12.5909 4.11325ZM10.9809 2.48194C10.937 2.5074 10.9582 2.54759 10.981 2.55415C11.3593 2.66294 11.9357 2.76344 12.5908 2.76344C12.8854 2.76344 13.1575 2.74067 13.3779 2.6976C13.7861 2.61784 14.0714 2.48844 14.1808 2.33321C14.207 2.29609 14.2868 2.18314 14.2234 1.85974C14.1462 1.46418 14.0055 1.34981 13.5961 1.34981C13.4592 1.34981 13.3006 1.36724 13.1247 1.4016C12.3701 1.54897 11.4757 2.07877 10.9809 2.48194Z"/>
                    </svg>                    
                </span>
            </el-tooltip>            
        <?php             
        }        
        
        function bookingpress_modify_payments_listing_data_func( $payment_logs_data ){
            global $BookingPress;
            if(!empty($payment_logs_data) && is_array($payment_logs_data) ){
                foreach($payment_logs_data as $k => $v){                                            
                    if($payment_logs_data[$k]['bookingpress_purchase_type'] == 2){
                        $payment_logs_data[$k]['total_amount_with_currency'] = $payment_logs_data[$k]['package_total_amount'];
                        $payment_logs_data[$k]['subtotal_amount_with_currency'] =  $payment_logs_data[$k]['package_sub_total_amount'];
                        if(!empty($payment_logs_data[$k]['package_tax_amount'])){
                            $payment_logs_data[$k]['tax_amount_with_currency'] = $payment_logs_data[$k]['package_tax_amount'];
                        }    
                        if(!empty($payment_logs_data[$k]['package_tip_amount'])){
                            $payment_logs_data[$k]['bookingpress_tip_amount_currency'] = $payment_logs_data[$k]['package_tip_amount'];
                        }
                    }
                }
            }
            return $payment_logs_data;
        }

        function bookingpress_payment_add_view_field_func($payment, $payment_log_val){
            global $BookingPress;
            $bookingpress_purchase_type = (isset($payment_log_val['bookingpress_purchase_type']))?$payment_log_val['bookingpress_purchase_type']:0;            
            $payment['bookingpress_purchase_type'] = $bookingpress_purchase_type;
            $payment['package_tax_amount'] = '';
            $payment['package_tip_amount'] = '';
            $payment['package_sub_total_amount'] = '';
            $payment['package_total_amount'] = $BookingPress->bookingpress_price_formatter_with_currency_symbol($payment_log_val['bookingpress_paid_amount']);
            if($bookingpress_purchase_type == 2){
                $payment['payment_service'] = $payment_log_val['bookingpress_package_name'];
                $payment['staff_member_name'] = ' - ';
                $payment['is_package_purchase'] = 1; 
                $payment['package_sub_total_amount'] = $BookingPress->bookingpress_price_formatter_with_currency_symbol($payment_log_val['bookingpress_package_price']);
                if(isset($payment_log_val['bookingpress_tax_amount']) && $payment_log_val['bookingpress_tax_amount'] != 0){
                    $payment['package_tax_amount'] = $BookingPress->bookingpress_price_formatter_with_currency_symbol($payment_log_val['bookingpress_tax_amount']);
                }
                if(isset($payment_log_val['bookingpress_tip_amount']) && $payment_log_val['bookingpress_tip_amount'] != 0){
                    $payment['package_tip_amount'] = $BookingPress->bookingpress_price_formatter_with_currency_symbol($payment_log_val['bookingpress_tip_amount']);
                }                
            }            
            $payment['bookingpress_package_price'] = $payment_log_val['bookingpress_package_price'];
            return $payment;
        }
        
        
        /**
         * Function for change payment 
         *
         * @param  mixed $bookingpress_payment_translation_item_label
         * @return void
         */
        function bookingpress_payment_transaction_item_label_func($bookingpress_payment_transaction_item_label){            
            $bookingpress_payment_transaction_item_label = esc_html__( 'Service/Package', 'bookingpress-package' ); 
            return $bookingpress_payment_transaction_item_label;
        }
                
        /**
         * Function for after add package order custom fields assign
         *
         * @return void
         */
        function bookingpress_after_add_package_order_func($entry_id,$inserted_booking_id){
			global $wpdb, $tbl_bookingpress_package_bookings_meta;			
            $wpdb->update( $tbl_bookingpress_package_bookings_meta, array('bookingpress_package_booking_id' => $inserted_booking_id), array('bookingpress_entry_id' => $entry_id) );            
        }

        /**
         * Function for get package order meta fields value
         *
         * @return void
         */
        function bookingpress_get_package_order_meta_values_func(){
			global $wpdb, $BookingPress, $BookingPressPro, $tbl_bookingpress_form_fields;

			$response = array();
			$bpa_check_authorization = $this->bpa_check_authentication( 'get_package_order_meta_value', true, 'bpa_wp_nonce' );
            
            if( preg_match( '/error/', $bpa_check_authorization ) ){
                $bpa_auth_error = explode( '^|^', $bpa_check_authorization );
                $bpa_error_msg = !empty( $bpa_auth_error[1] ) ? $bpa_auth_error[1] : esc_html__( 'Sorry. Something went wrong while processing the request', 'bookingpress-package');

                $response['variant'] = 'error';
                $response['title'] = esc_html__( 'Error', 'bookingpress-package');
                $response['msg'] = $bpa_error_msg;

                wp_send_json( $response );
                die;
            }
            
            $bookingpress_package_booking_id = !empty($_POST['bookingpress_package_booking_id']) ? intval($_POST['bookingpress_package_booking_id']) : 0; // phpcs:ignore

			if(!empty($bookingpress_package_booking_id)){

				$bookingpress_form_field_value = $this->bookingpress_get_package_order_form_field_data($bookingpress_package_booking_id);
				$bookingpress_form_field_value = apply_filters('bookingpress_get_package_order_meta_value_filter',$bookingpress_form_field_value);

				$response['variant']            = 'success';
				$response['title']              = esc_html__( 'Success', 'bookingpress-package' );
				$response['msg']                = esc_html__( 'Custom fields retrieved successfully.', 'bookingpress-package' );
				$response['custom_fields_values'] = $bookingpress_form_field_value;

			}
            
			echo wp_json_encode($response);
			exit;

        }

		/**
		 * This function is used to get the appointment form field data.
		 * 
		 * @param  mixed $bookingpress_appointment_id
		 * @return void
		 */
		function bookingpress_get_package_order_form_field_data($bookingpress_package_booking_id){

            global $wpdb,$tbl_bookingpress_package_bookings,$tbl_bookingpress_package_bookings_meta;
            $bookingpress_package_form_fields = array();

            if(!empty($bookingpress_package_booking_id)) {

                $bookingpress_package_data = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$tbl_bookingpress_package_bookings} WHERE `bookingpress_package_booking_id` = %d ", $bookingpress_package_booking_id ), ARRAY_A ); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared --Reason $tbl_bookingpress_appointment_bookings is a table name. false alarm.

                if(!empty($bookingpress_package_data)) {

                    $bookingpress_package_meta_data = $wpdb->get_row( $wpdb->prepare( "SELECT bookingpress_package_meta_value,bookingpress_package_meta_key FROM {$tbl_bookingpress_package_bookings_meta} WHERE bookingpress_package_booking_id = %d AND bookingpress_package_meta_key = %s ORDER BY bookingpress_package_meta_created_date DESC", $bookingpress_package_booking_id,'package_form_fields_data' ), ARRAY_A );// phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared --Reason $tbl_bookingpress_package_bookings_meta is a table name. false alarm.

                    $bookingpress_package_meta_data = !empty($bookingpress_package_meta_data['bookingpress_package_meta_value']) ? json_decode($bookingpress_package_meta_data['bookingpress_package_meta_value'],true) : array();
                                    
                    $bookingpress_package_form_fields = !empty($bookingpress_package_meta_data['form_fields']) ? stripslashes_deep($bookingpress_package_meta_data['form_fields']) : array();

                }

            }
			return $bookingpress_package_form_fields;
        }        

        /**
         * Function for edit package order
         *
         * @return void
         */
        function bookingpress_get_edit_package_order_data_func(){
            
            global $wpdb,$BookingPress,$tbl_bookingpress_package_bookings, $tbl_bookingpress_payment_logs;
            $response  = array();
            $bpa_check_authorization = $this->bpa_check_authentication( 'edit_package_order_details', true, 'bpa_wp_nonce' );            
            if( preg_match( '/error/', $bpa_check_authorization ) ){
                $bpa_auth_error = explode( '^|^', $bpa_check_authorization );
                $bpa_error_msg = !empty( $bpa_auth_error[1] ) ? $bpa_auth_error[1] : esc_html__( 'Sorry. Something went wrong while processing the request', 'bookingpress-package');

                $response['variant'] = 'error';
                $response['title'] = esc_html__( 'Error', 'bookingpress-package');
                $response['msg'] = $bpa_error_msg;

                wp_send_json( $response );
                die;
            }
            $package_order_data = array();
            if(!empty($_POST['package_order_id'])){  // phpcs:ignore WordPress.Security.NonceVerification
                
                $package_order_id     = intval($_POST['package_order_id']); // phpcs:ignore WordPress.Security.NonceVerification
                $package_order_data   = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$tbl_bookingpress_package_bookings} WHERE bookingpress_package_booking_id = %d", $package_order_id), ARRAY_A);  // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- Reason: $tbl_bookingpress_appointment_bookings is table name defined globally. False Positive alarm

                $bookingpress_customer_id = !empty($package_order_data['bookingpress_customer_id'])  ? intval($package_order_data['bookingpress_customer_id']) : 0 ;
                $bookingpress_customer_selection_details = array();
                if(!empty($bookingpress_customer_id)) {
                    $bookingpress_customer_selection_details = $BookingPress->bookingpress_get_appointment_customer_list('',$bookingpress_customer_id);
                }                
                $package_order_data['package_customer_list'] = $bookingpress_customer_selection_details; 

                $package_order_data = apply_filters('bookingpress_modify_edit_package_order_data', $package_order_data);

            }
            
            echo wp_json_encode($package_order_data);
            exit();
        }
        
        /**
         * Function for change package order status
         *
         * @param  mixed $update_package_id
         * @param  mixed $package_new_status
         * @return void
         */
        function bookingpress_change_package_order_status_func( $update_package_id = '', $package_new_status = '' ){

            global $wpdb, $BookingPress, $tbl_bookingpress_package_bookings, $tbl_bookingpress_payment_logs, $bookingpress_email_notifications;            
            $response  = array();            
            $bpa_check_authorization = $this->bpa_check_authentication( 'change_package_order_status', true, 'bpa_wp_nonce' );
            if( preg_match( '/error/', $bpa_check_authorization ) ){
                $bpa_auth_error = explode( '^|^', $bpa_check_authorization );
                $bpa_error_msg = !empty( $bpa_auth_error[1] ) ? $bpa_auth_error[1] : esc_html__( 'Sorry. Something went wrong while processing the request', 'bookingpress-package');
                $response['variant'] = 'error';
                $response['title'] = esc_html__( 'Error', 'bookingpress-package');
                $response['msg'] = $bpa_error_msg;
                wp_send_json( $response );
                die;
            }
            $update_package_id  = ! empty($_REQUEST['update_package_id']) ? intval($_REQUEST['update_package_id']) : $update_appointment_id;
            $package_new_status = ! empty($_REQUEST['package_new_status']) ? sanitize_text_field($_REQUEST['package_new_status']) : $package_new_status;
            $return = 0;
            if (! empty($update_package_id) && ! empty($package_new_status) ) {                
                $package_order_update_data = array(
                    'bookingpress_package_booking_status' => $package_new_status,
                );
                $package_order_where_condition = array(
                    'bookingpress_package_booking_id' => $update_package_id,
                );
                $wpdb->update($tbl_bookingpress_package_bookings, $package_order_update_data, $package_order_where_condition);
                $return = 1;
            }
            if (isset($_POST['action']) && sanitize_text_field($_POST['action']) != 'bookingpress_change_package_order_status' ) { // phpcs:ignore WordPress.Security.NonceVerification
                return intval($return); 
                exit(); 
            } else {
                echo esc_html($return);                 
                exit;
            }

        }

        /**
         * Function for delete & bulk delete package order 
         *
         * @return void
         */
        function bookingpress_bulk_package_order_func(){

            global $BookingPress,$bookingpress_package,$tbl_bookingpress_package_bookings,$wpdb;
            $response              = array();
            $bpa_check_authorization = $this->bpa_check_authentication( 'delete_package_order', true, 'bpa_wp_nonce' );            
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
            if (! empty($_POST['bulk_action']) && sanitize_text_field($_POST['bulk_action']) == 'delete' ) { // phpcs:ignore
             //phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized --Reason $_POST['app_delete_ids'] contains array and sanitized properly using appointment_sanatize_field function
                $delete_ids = ! empty($_POST['app_delete_ids']) ? array_map(array( $BookingPress, 'appointment_sanatize_field' ), $_POST['app_delete_ids']) : array(); // phpcs:ignore
                if (! empty($delete_ids) ) {
                    foreach ( $delete_ids as $delete_key => $delete_val ) {
                        if (is_array($delete_val) ) {
                            $delete_val = $delete_val['package_id'];
                        }
                        $bookingpress_package_row = $wpdb->get_row($wpdb->prepare("SELECT bookingpress_package_id,bookingpress_package_no FROM {$tbl_bookingpress_package_bookings} WHERE bookingpress_package_booking_id = %d", $delete_val),ARRAY_A);//phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- Reason: $tbl_bookingpress_package_bookings is table name defined globally. False Positive alarm                        
                        if(!empty($bookingpress_package_row)){
                            $has_booked_appointment = $bookingpress_package->bookingpress_check_package_appointment_book($bookingpress_package_row['bookingpress_package_id'],$bookingpress_package_row['bookingpress_package_no']);                        
                            if(!$has_booked_appointment){
                                $return = $this->bookingpress_delete_package_order_func($delete_val);                                                
                                if ($return ) {
                                    $response['variant'] = 'success';
                                    $response['title']   = esc_html__('Success', 'bookingpress-package');
                                    $response['msg']     = esc_html__('Package order has been deleted successfully.', 'bookingpress-package');
                                }    
                            }else{
                                $response['msg']     = esc_html__('Appointment is already booked for package order ID #', 'bookingpress-package') . $bookingpress_package_row['bookingpress_package_no'];
                            }
                        }
                    }
                }
            }
            wp_send_json($response);
        }

        /**
         * Function for delete package order functionality
         *
         * @return void
        */
        function bookingpress_delete_package_order_func($package_id = ''){

            global $wpdb,$tbl_bookingpress_package_bookings,$tbl_bookingpress_payment_logs,$bookingpress_package;
            $response = array();
            $bpa_check_authorization = $this->bpa_check_authentication( 'delete_package_order', true, 'bpa_wp_nonce' );            
            if( preg_match( '/error/', $bpa_check_authorization ) ){
                $bpa_auth_error = explode( '^|^', $bpa_check_authorization );
                $bpa_error_msg = !empty( $bpa_auth_error[1] ) ? $bpa_auth_error[1] : esc_html__( 'Sorry. Something went wrong while processing the request', 'bookingpress-package');
                $response['variant'] = 'error';
                $response['title'] = esc_html__( 'Error', 'bookingpress-package');
                $response['msg'] = $bpa_error_msg;
                wp_send_json( $response );
                die;
            }
            $package_id  = isset($_POST['delete_id']) ? intval($_POST['delete_id']) : $package_id; // phpcs:ignore WordPress.Security.NonceVerification
            $response['variant'] = 'error';
            $response['title']   = esc_html__('Error', 'bookingpress-package');
            $response['msg']     = esc_html__('Something went wrong..', 'bookingpress-package');
            $return              = false;
            if (! empty($package_id) ) {                

                if (isset($_POST['action']) && sanitize_text_field($_POST['action']) == 'bookingpress_delete_package_order' ) { // phpcs:ignore WordPress.Security.NonceVerification
                    $bookingpress_package_row = $wpdb->get_row($wpdb->prepare("SELECT bookingpress_package_id,bookingpress_package_no FROM {$tbl_bookingpress_package_bookings} WHERE bookingpress_package_booking_id = %d", $delete_val),ARRAY_A);// phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- Reason: $tbl_bookingpress_package_bookings is table name defined globally. False Positive alarm                      
                    if(!empty($bookingpress_package_row)){
                        $has_booked_appointment = $bookingpress_package->bookingpress_check_package_appointment_book($bookingpress_package_row['bookingpress_package_id'],$bookingpress_package_row['bookingpress_package_no']);  
                        if($has_booked_appointment){

                            $response['variant'] = 'error';
                            $response['title'] = esc_html__( 'Error', 'bookingpress-package');
                            $response['msg']   = esc_html__('Appointment is already booked for package order ID #', 'bookingpress-package') . $bookingpress_package_row['bookingpress_package_no'];
                            wp_send_json($response);
                        }
                    }
                }
                $wpdb->delete($tbl_bookingpress_package_bookings, array( 'bookingpress_package_booking_id' => $package_id ), array( '%d' ));
                $wpdb->delete($tbl_bookingpress_payment_logs, array( 'bookingpress_package_order_booking_ref' => $package_id ), array( '%d' ));

                $response['variant'] = 'success';
                $response['title']   = esc_html__('Success', 'bookingpress-package');
                $response['msg']     = esc_html__('Package Order Has Been Deleted Successfully.', 'bookingpress-package');
                $return              = true;

            }
            if (isset($_POST['action']) && sanitize_text_field($_POST['action']) == 'bookingpress_delete_package_order' ) { // phpcs:ignore
                wp_send_json($response);
            }
            return $return;

        }
        
		/**
		 * Ajax request for get search customer list
		 *
		 * @return void
		 */
		function bookingpress_get_search_customer_list_func() {

			global $wpdb, $BookingPress, $BookingPressPro;
			$response                       = array();
            $bpa_check_authorization = $this->bpa_check_authentication( 'search_customer', true, 'bpa_wp_nonce' );
            
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
            $search_user_str = ! empty( $_REQUEST['search_user_str'] ) ? ( sanitize_text_field($_REQUEST['search_user_str'] )) : ''; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.MissingUnslash
                   
			if(!empty($search_user_str)) {

                $response['variant'] = 'success';
                $response['title'] = esc_html__('Success', 'bookingpress-package');
                $response['msg'] = esc_html__('Data retrieved successfully', 'bookingpress-package');
                $response['package_customers_details'] = array();
                $bookingpress_appointment_customers_details = $BookingPress->bookingpress_get_search_customer_list($search_user_str);						
                $response['package_customers_details'] = $bookingpress_appointment_customers_details;

            }    

			echo wp_json_encode($response);
			exit;
		}

        function bookingpress_get_package_order_func(){
            
            global $bookingpress_package,$BookingPress,$wpdb, $tbl_bookingpress_services,$tbl_bookingpress_package_bookings,$tbl_bookingpress_packages,$tbl_bookingpress_payment_logs,$tbl_bookingpress_customers,$bookingpress_global_options,$tbl_bookingpress_form_fields;

			$bookingpress_global_data = $bookingpress_global_options->bookingpress_global_options();
			$default_date_format = $bookingpress_global_data['wp_default_date_format'];           
			$default_time_format = $bookingpress_global_data['wp_default_time_format'];  

            $response              = array();
            $bpa_check_authorization = $this->bpa_check_authentication( 'get_package_order_details', true, 'bpa_wp_nonce' );            
            if( preg_match( '/error/', $bpa_check_authorization ) ){
                $bpa_auth_error = explode( '^|^', $bpa_check_authorization );
                $bpa_error_msg = !empty( $bpa_auth_error[1] ) ? $bpa_auth_error[1] : esc_html__( 'Sorry. Something went wrong while processing the request', 'bookingpress-package');
                $response['variant'] = 'error';
                $response['title'] = esc_html__( 'Error', 'bookingpress-package');
                $response['msg'] = $bpa_error_msg;
                wp_send_json( $response );
                die;
            }
            
            $bookingpress_package_status_arr = $bookingpress_package->get_package_order_status();
            $perpage     = isset($_POST['perpage']) ? intval($_POST['perpage']) : 10; // phpcs:ignore WordPress.Security.NonceVerification
            $currentpage = isset($_POST['currentpage']) ? intval($_POST['currentpage']) : 1; // phpcs:ignore WordPress.Security.NonceVerification
            $offset      = ( ! empty($currentpage) && $currentpage > 1 ) ? ( ( $currentpage - 1 ) * $perpage ) : 0;
            $bookingpress_search_data        = ! empty($_REQUEST['search_data']) ? array_map(array( $BookingPress, 'appointment_sanatize_field' ), $_REQUEST['search_data']) : array(); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized --Reason $_REQUEST['search_data'] contains array and sanitized properly using appointment_sanatize_field function
            $bookingpress_search_query       = '';
            $bookingpress_search_query_where = 'WHERE 1=1 ';

            if (!empty($bookingpress_search_data)){

                if (! empty($bookingpress_search_data['search_package']) ) {
                    $bookingpress_search_string = $bookingpress_search_data['search_package'];
                    $bookingpress_search_result = $wpdb->get_results($wpdb->prepare('SELECT bookingpress_customer_id  FROM ' . $tbl_bookingpress_customers . " WHERE bookingpress_customer_full_name LIKE %s OR bookingpress_user_firstname LIKE %s OR bookingpress_user_lastname LIKE %s OR bookingpress_user_login LIKE %s AND (bookingpress_user_type = 1 OR bookingpress_user_type = 2)", '%' . $bookingpress_search_string . '%', '%' . $bookingpress_search_string . '%', '%' . $bookingpress_search_string . '%' , '%' . $bookingpress_search_string . '%'), ARRAY_A); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared -- Reason: $tbl_bookingpress_customers is table name defined globally. False Positive alarm
                    if (! empty($bookingpress_search_result) ) {
                        $bookingpress_customer_ids = array();
                        foreach ( $bookingpress_search_result as $item ) {
                            $bookingpress_customer_ids[] = $item['bookingpress_customer_id'];
                        }
                        $bookingpress_search_user_id      = implode(',', $bookingpress_customer_ids);
                        $bookingpress_search_query_where .= " AND (bookingpress_customer_id IN ({$bookingpress_search_user_id}))";
                    } else {
                        $bookingpress_search_query_where .= " AND (bookingpress_package_name LIKE '%{$bookingpress_search_string}%')";
                    }
                }
                if (! empty($bookingpress_search_data['selected_date_range']) ) {
                    $bookingpress_search_date         = $bookingpress_search_data['selected_date_range'];
                    $start_date                       = date('Y-m-d', strtotime($bookingpress_search_date[0]));
                    $end_date                         = date('Y-m-d', strtotime($bookingpress_search_date[1]));
                    $bookingpress_search_query_where .= " AND (bookingpress_package_purchase_date BETWEEN '{$start_date}' AND '{$end_date}')";
                }  
                if (! empty($bookingpress_search_data['customer_name']) ) {
                    $bookingpress_search_name         = $bookingpress_search_data['customer_name'];
                    $bookingpress_search_customer_id  = implode(',', $bookingpress_search_name);
                    $bookingpress_search_query_where .= " AND (bookingpress_customer_id IN ({$bookingpress_search_customer_id}))";
                }  
                if(!empty( $bookingpress_search_data['search_package_id'])) {
                    $bookingpress_search_id = $bookingpress_search_data['search_package_id'];
                    $bookingpress_search_query_where .= " AND (bookingpress_package_no = '{$bookingpress_search_id}')";                    
                }                                            
                if (! empty($bookingpress_search_data['package_name']) ) {                    
                    $bookingpress_search_name         = $bookingpress_search_data['package_name'];
                    $bookingpress_search_package_id   = implode(',', $bookingpress_search_name);
                    $bookingpress_search_query_where .= " AND (bookingpress_package_id IN ({$bookingpress_search_package_id}))";                    
                }                
            }

            $get_total_package_order = $wpdb->get_results("SELECT * FROM {$tbl_bookingpress_package_bookings} {$bookingpress_search_query} {$bookingpress_search_query_where} ", ARRAY_A); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared --Reason: $tbl_bookingpress_appointment_bookings is a table name. false alarm
            $total_package_order = $wpdb->get_results("SELECT * FROM {$tbl_bookingpress_package_bookings} {$bookingpress_search_query} {$bookingpress_search_query_where} order by bookingpress_package_booking_id DESC LIMIT {$offset} , {$perpage}", ARRAY_A); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared --Reason: $tbl_bookingpress_appointment_bookings is a table name. false alarm

            $package_order  = $bookingpress_formdata = array();

            if(!empty($total_package_order)){

                $counter = 1;                
                $bookingpress_global_options_arr       = $bookingpress_global_options->bookingpress_global_options();
                $bookingpress_default_date_format = $bookingpress_global_options_arr['wp_default_date_format'];
                $bookingpress_default_time_format = $bookingpress_global_options_arr['wp_default_time_format'];
                $bookingpress_default_date_time_format = $bookingpress_default_date_format . ' ' . $bookingpress_default_time_format;                
                $bookingpress_form_field_data = $wpdb->get_results("SELECT `bookingpress_form_field_name`,`bookingpress_field_label` FROM {$tbl_bookingpress_form_fields} Where bookingpress_field_is_package_hide = 0",ARRAY_A);// phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- Reason: $tbl_bookingpress_form_fields is table name defined globally. False Positive alarm
                foreach($bookingpress_form_field_data as $key=> $value) {                    
                    $bookingpress_formdata[$value['bookingpress_form_field_name']] = stripslashes_deep($value['bookingpress_field_label']);
                }

                foreach ( $total_package_order as $get_package_order ) {

                    $single_package_order  = array();
                    $single_package_order['id']  = $counter;
                    $package_order_id            = intval($get_package_order['bookingpress_package_booking_id']);
                    $single_package_order['package_order_id'] = $package_order_id;
                    $single_package_order['payment_id'] = $get_package_order['bookingpress_payment_id'];

                    $single_package_order['bookingpress_package_expiration_date'] = $get_package_order['bookingpress_package_expiration_date'];
                    $single_package_order['bookingpress_package_booking_id'] = $get_package_order['bookingpress_package_booking_id'];

                    $payment_log = $wpdb->get_row($wpdb->prepare('SELECT bookingpress_invoice_id, bookingpress_customer_firstname,bookingpress_customer_lastname,bookingpress_customer_email, bookingpress_payment_gateway FROM ' . $tbl_bookingpress_payment_logs . ' WHERE bookingpress_package_order_booking_ref = %d', $package_order_id), ARRAY_A); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared -- Reason: $tbl_bookingpress_payment_logs is table name defined globally. False Positive alarm
                    $single_package_order['created_date']     = date_i18n($bookingpress_default_date_time_format, strtotime($get_package_order['bookingpress_package_created_at']));
                    $single_package_order['bookingpress_package_created_date'] = $get_package_order['bookingpress_package_created_at'];
                    $single_package_order['booking_id'] = !empty($get_package_order['bookingpress_package_no']) ? $get_package_order['bookingpress_package_no'] : 1;
                    $customer_email = ! empty($get_package_order['bookingpress_customer_email']) ? $get_package_order['bookingpress_customer_email'] : '';
                    $customer_phone = ! empty($get_package_order['bookingpress_customer_phone']) ? $get_package_order['bookingpress_customer_phone'] : '';
                    $single_package_order['customer_first_name'] = !empty($get_package_order['bookingpress_customer_firstname']) ? stripslashes_deep($get_package_order['bookingpress_customer_firstname']) :'';
                    $single_package_order['customer_last_name'] = !empty($get_package_order['bookingpress_customer_lastname']) ? stripslashes_deep($get_package_order['bookingpress_customer_lastname']) :'';
                    $customer_username = ! empty($get_package_order['bookingpress_username']) ? $get_package_order['bookingpress_username'] : '';
                    if( !empty($customer_username ) ){
                        $single_package_order['customer_name'] = (isset($single_package_order['customer_name']) && !empty($single_package_order['customer_name']) && !empty(trim($single_package_order['customer_name']))) ? ($single_package_order['customer_name']) : stripslashes_deep($customer_username);
                    } else{
                        $single_package_order['customer_name'] = !empty($get_package_order['bookingpress_customer_name']) ? stripslashes_deep($get_package_order['bookingpress_customer_name']) : $single_package_order['customer_first_name'].' '.$single_package_order['customer_last_name'];
                        $single_package_order['customer_name'] = !empty(trim($single_package_order['customer_name'])) ? ($single_package_order['customer_name']) : stripslashes_deep($customer_email);
                    }
                    $single_package_order['customer_email'] = stripslashes_deep($customer_email);
                    $single_package_order['customer_phone'] = stripslashes_deep($customer_phone);
                    $single_package_order['package_name']  = stripslashes_deep($get_package_order['bookingpress_package_name']);
                    $single_package_order['appointment_note']  = stripslashes_deep($get_package_order['bookingpress_package_internal_note']);
                    $currency_name     = $get_package_order['bookingpress_package_currency'];
                    $currency_symbol   = $BookingPress->bookingpress_get_currency_symbol($currency_name);                    
                    $payment_amount = $BookingPress->bookingpress_price_formatter_with_currency_symbol($get_package_order['bookingpress_package_paid_amount'], $currency_symbol);
                    $payment_amount_without_currency = floatval($get_package_order['bookingpress_package_paid_amount']);
                    $single_package_order['package_payment'] = $payment_amount;
                    $single_package_order['payment_numberic_amount'] = $payment_amount_without_currency;                    
                    $bookingpress_package_booking_status = esc_html($get_package_order['bookingpress_package_booking_status']);
                    $bookingpress_package_status_label = $bookingpress_package_booking_status;

                    $single_package_order['package_duration'] = $bookingpress_package->get_package_duration_limit_text($get_package_order['bookingpress_package_duration'],$get_package_order['bookingpress_package_duration_unit'],true);
                    foreach($bookingpress_package_status_arr as $status_key => $status_val){
                        if($bookingpress_package_booking_status == $status_val['value']){
                            $bookingpress_package_status_label = $status_val['text'];
                            break;
                        }    
                    }
                    
                    $single_package_order['package_status']  = ($bookingpress_package_booking_status == '2')?$bookingpress_package_status_label:$bookingpress_package_booking_status;
                    $single_package_order['package_status_label'] = $bookingpress_package_status_label;

                    

                    $bookingpress_view_purchase_date = date_i18n($bookingpress_default_date_format, strtotime($get_package_order['bookingpress_package_purchase_date']));
					$bookingpress_view_purchase_time = date($bookingpress_default_time_format, strtotime($get_package_order['bookingpress_package_purchase_time']));
                    $bookingpress_view_expire_date = date_i18n($bookingpress_default_date_format, strtotime($get_package_order['bookingpress_package_expiration_date']));

					$single_package_order['view_package_purchase_date'] = $bookingpress_view_purchase_date;
                    $single_package_order['view_package_purchase_time'] = $bookingpress_view_purchase_time;
                    $single_package_order['view_package_expiration_date'] = $bookingpress_view_expire_date;

                    $bookingpress_payment_method = ( !empty( $payment_log) && $payment_log['bookingpress_payment_gateway']  == 'on-site' ) ? 'On Site': (!empty($payment_log['bookingpress_payment_gateway']) ? $payment_log['bookingpress_payment_gateway'] : '' ); 

                    $bookingpress_payment_method = apply_filters('bookingpress_selected_gateway_label_name_package', $bookingpress_payment_method, $bookingpress_payment_method);

                    $single_package_order['payment_method'] = $bookingpress_payment_method;

                    $single_package_order = apply_filters('bookingpress_package_add_view_field', $single_package_order, $get_package_order);

                    $bookingpress_booking_purchase_timestamp = strtotime($get_package_order['bookingpress_package_expiration_date']);

                    $single_package_order['is_package_expire'] = (current_time('timestamp') > $bookingpress_booking_purchase_timestamp)?1:0;
                    $single_package_order['change_status_loader'] = '0';
                    $single_package_order['is_package_appointment_booked'] = $bookingpress_package->bookingpress_check_package_appointment_book($get_package_order['bookingpress_package_id'],$get_package_order['bookingpress_package_no']);

                    /* Get Package Order Custom Fields Start */

                    $bookingpress_meta_value = $this->bookingpress_get_package_order_form_field_data($get_package_order['bookingpress_package_booking_id']);
                    $bookingpress_package_custom_meta_values = array();
                    if(!empty($bookingpress_meta_value)){
                        foreach($bookingpress_meta_value as $k4 => $v4) {
                            
                            $bookingpress_form_field_data = $wpdb->get_row($wpdb->prepare("SELECT bookingpress_field_label,bookingpress_field_type,bookingpress_field_options,bookingpress_field_values FROM {$tbl_bookingpress_form_fields} WHERE  bookingpress_field_meta_key = %s AND bookingpress_field_type != %s AND bookingpress_field_type != %s AND bookingpress_field_type != %s", $k4, '2_col', '3_col', '4_col'), ARRAY_A); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared --Reason: $tbl_bookingpress_form_fields is table name.

                            $bookingpress_field_label = !empty($bookingpress_form_field_data['bookingpress_field_label']) ? stripslashes_deep($bookingpress_form_field_data['bookingpress_field_label']) : '';
                            if(!empty($bookingpress_field_label)){
                                $bookingpress_field_type = $bookingpress_form_field_data['bookingpress_field_type'];
                                if( !empty($bookingpress_field_type) && 'checkbox' == $bookingpress_field_type ){
                                    $bookingpress_package_custom_meta_values[] = array('label' => $bookingpress_field_label, 'value' => is_array($v4) ? implode(',', $v4) : '' );
                                } elseif(!empty($bookingpress_field_type) && !empty($v4) && 'date' == $bookingpress_field_type ) {
                                    $bookingpress_field_options = json_decode($bookingpress_form_field_data['bookingpress_field_options'],true);
                                    if(!empty($bookingpress_field_options['enable_timepicker']) && $bookingpress_field_options['enable_timepicker'] == 'true') {
                                        $default_date_time_format = $default_date_format.' '.$default_time_format;
                                        $bookingpress_package_custom_meta_values[] = array('label' => $bookingpress_field_label, 'value' => date($default_date_time_format,strtotime($v4)));
                                    } else {
                                        $bookingpress_package_custom_meta_values[] = array('label' => $bookingpress_field_label, 'value' => date($default_date_format,strtotime($v4)));
                                    }
                                } else if( !empty( $bookingpress_field_type ) && 'file' == $bookingpress_field_type ) {
                                    $file_name_data = explode( '/', $v4 );
                                    $file_name = end( $file_name_data );
                                    
                                    $bookingpress_package_custom_meta_values[] = array(
                                        'label' => $bookingpress_field_label,
                                        'value' => '<a href="' . esc_url( $v4 ) . '" target="_blank">'.$file_name.'</a>'
                                    );
                                } else {
                                    $bookingpress_package_custom_meta_values[] = array('label' => $bookingpress_field_label, 'value' => $v4);
                                }
                            }                            

                        }
                    }

                    $single_package_order['custom_fields_values'] = $bookingpress_package_custom_meta_values;	

                    /* Get package Order Custom Fields over */


                    $bookingpress_package_services = array();
                    $bookingpress_package_services_temp = $get_package_order['bookingpress_package_services'];
                    $package_total_remaining_appointments = 0;
                    if(!empty($bookingpress_package_services_temp)){
                        $bookingpress_package_services = json_decode($bookingpress_package_services_temp,true);
                        foreach($bookingpress_package_services as $sk=>$package_serv){
                            $service_booked_appointment = $bookingpress_package->get_package_service_purchase_count($get_package_order['bookingpress_package_no'],$package_serv['bookingpress_service_id']);
                            
                            $service_remaining_appointment = intval($package_serv['bookingpress_no_of_appointments']) -  $service_booked_appointment;                            
                            $package_total_remaining_appointments = $package_total_remaining_appointments + $service_remaining_appointment;

                            $bookingpress_package_services[$sk]['booked_appointment'] = $service_booked_appointment;
                            $bookingpress_package_services[$sk]['service_remaining_appointment'] = $service_remaining_appointment;                            

                        }
                    }
                    $single_package_order['bookingpress_package_services'] = $bookingpress_package_services;
                    $single_package_order['package_total_remaining_appointments'] = $package_total_remaining_appointments;
                    $package_order[] = $single_package_order;
                    $counter++;				
                }                
            }

            $package_order = apply_filters('bookingpress_modify_package_orders_data', $package_order);
            $data['items']       = $package_order;
            $data['form_field_data'] = $bookingpress_formdata;
            $data['items']       = $package_order;
            $data ['totalItems'] = count($get_total_package_order);
            wp_send_json($data);
        }
                
        /**
         * get package expiration date
         *
         * @param  mixed $package_id
         * @param  mixed $package_duration
         * @param  mixed $package_duration_unit
         * @param  mixed $package_created_date
         * @return void
         */
        function get_package_expiration_date($package_id = 0, $package_duration = '',$package_duration_unit = '',$package_created_date = ''){            
            $package_expiration_date = date('Y-m-d');
            if(!empty($package_duration) && !empty($package_duration_unit) && !empty($package_created_date)){
                $duration_added = ' + '.$package_duration;
                $package_created_date = date('Y-m-d',strtotime($package_created_date));
                if($package_duration_unit == 'y'){
                    $duration_added.=' years';
                }else if($package_duration_unit == 'm'){
                    $duration_added.=' months';
                }else{
                    $duration_added.=' days';
                }
                $package_expiration_date = date('Y-m-d',strtotime($package_created_date . " ".$duration_added));

            }
            return $package_expiration_date;
        }

        /**
         * Function for insert package booking table details
         *
         * @param  mixed $package_booking_data
         * @return void
         */
        function bookingpress_insert_package_booking_log( $package_booking_data = array() ){
            global $wpdb, $tbl_bookingpress_package_bookings;
            $package_order_inserted_id = 0;
            if (! empty($package_booking_data) ) {
                $wpdb->insert($tbl_bookingpress_package_bookings, $package_booking_data);
                $package_order_inserted_id = $wpdb->insert_id;
                do_action('bookingpress_after_insert_package_order', $package_order_inserted_id);
            }
            return $package_order_inserted_id;
        }        

        /**
         * Function For Add Package Order
         * bookingpress_before_appointment_confirm_booking_func
         *
         * @param  mixed $is_package_booking
         * @param  mixed $entry_id
         * @param  mixed $payment_gateway_data
         * @param  mixed $payment_status
         * @param  mixed $transaction_id_field
         * @param  mixed $payment_amount_field
         * @param  mixed $is_front
         * @param  mixed $is_cart_order
         * @return void
        */
        public function bookingpress_before_appointment_confirm_booking_func($is_package_booking,$entry_id,$payment_gateway_data,$payment_status,$transaction_id_field,$payment_amount_field,$is_front,$is_cart_order, $payment_currency_field){
            
            global $wpdb, $BookingPress, $tbl_bookingpress_entries, $tbl_bookingpress_customers, $bookingpress_email_notifications, $bookingpress_debug_payment_log_id, $bookingpress_customers, $bookingpress_coupons, $tbl_bookingpress_appointment_meta, $tbl_bookingpress_appointment_bookings, $bookingpress_other_debug_log_id, $tbl_bookingpress_payment_logs,$bookingpress_dashboard,$tbl_bookingpress_entries_meta,$bookingpress_package,$tbl_bookingpress_package_bookings;
            if($entry_id){
                $bookingpress_entry_type = $wpdb->get_row( $wpdb->prepare( "SELECT bookingpress_purchase_type FROM {$tbl_bookingpress_entries} WHERE bookingpress_entry_id = %d", $entry_id ), ARRAY_A ); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared --Reason: $tbl_bookingpress_entries is a table name. false alarm
                $bookingpress_purchase_type = (isset($bookingpress_entry_type['bookingpress_purchase_type']))?$bookingpress_entry_type['bookingpress_purchase_type']:'';
                if($bookingpress_purchase_type == 2){

                                        
                    $transaction_id = ( ! empty( $transaction_id_field ) && ! empty( $payment_gateway_data[ $transaction_id_field ] ) ) ? $payment_gateway_data[ $transaction_id_field ] : '';                    
                    if(!empty($transaction_id)){
                        //Check received transaction id already exists or not
                        $bookingpress_exist_transaction_count = $wpdb->get_var($wpdb->prepare("SELECT * FROM {$tbl_bookingpress_payment_logs} WHERE bookingpress_transaction_id = %s", $transaction_id)); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared --Reason: $tbl_bookingpress_payment_logs is a table name. false alarm  
                        if($bookingpress_exist_transaction_count > 0){
                            do_action( 'bookingpress_other_debug_log_entry', 'appointment_debug_logs', 'Transaction '.$transaction_id.' already exists', 'bookingpress_complete_appointment', $bookingpress_exist_transaction_count, $bookingpress_other_debug_log_id );
                            return 0;
                        }
                    }

                    $is_package_booking = 'true';

                    if(!empty( $entry_id )) {

                        $entry_data = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$tbl_bookingpress_entries} WHERE bookingpress_entry_id = %d", $entry_id ), ARRAY_A ); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared --Reason: $tbl_bookingpress_entries is a table name. false alarm

                        if ( ! empty( $entry_data ) ) {

                            $bookingpress_login_user_id = 0;

                            $bookingpress_entry_user_id                  = $entry_data['bookingpress_customer_id'];
                            $bookingpress_customer_id                    = $bookingpress_entry_user_id;
                            $bookingpress_login_user_id                  = $this->get_customer_user_id( $bookingpress_customer_id );
                            $bookingpress_customer_name                  = $entry_data['bookingpress_customer_name'];
                            $bookingpress_customer_username              = $entry_data['bookingpress_username'];
                            $bookingpress_customer_phone                 = $entry_data['bookingpress_customer_phone'];
                            $bookingpress_customer_firstname             = $entry_data['bookingpress_customer_firstname'];
                            $bookingpress_customer_lastname              = $entry_data['bookingpress_customer_lastname'];
                            $bookingpress_customer_country               = $entry_data['bookingpress_customer_country'];
                            $bookingpress_customer_phone_dial_code       = $entry_data['bookingpress_customer_phone_dial_code'];
                            $bookingpress_customer_email                 = $entry_data['bookingpress_customer_email'];
                            $bookingpress_customer_timezone				 = $entry_data['bookingpress_customer_timezone'];
                            $bookingpress_customer_dst_timezone			 = $entry_data['bookingpress_dst_timezone'];                                                                        
                            $bookingpress_package_internal_note          = $entry_data['bookingpress_appointment_internal_note'];  
                            $bookingpress_package_send_notification      = $entry_data['bookingpress_appointment_send_notifications'];                            
                            $bookingpress_package_currency               = $entry_data['bookingpress_service_currency'];
                            $bookingpress_payment_gateway                = $entry_data['bookingpress_payment_gateway'];
                            $bookingpress_package_purchase_date          = date('Y-m-d');
                            $bookingpress_package_purchase_time          = date('H:i:s');
                            $bookingpress_paid_amount                    = $entry_data['bookingpress_paid_amount'];
                            $bookingpress_due_amount                     = $entry_data['bookingpress_due_amount'];
                            $bookingpress_total_amount                   = $entry_data['bookingpress_total_amount'];
                            $bookingpress_tax_percentage                 = $entry_data['bookingpress_tax_percentage'];
                            $bookingpress_tax_amount                     = $entry_data['bookingpress_tax_amount'];
                            $bookingpress_tip_amount                     = (isset($entry_data['bookingpress_tip_amount']))?floatval($entry_data['bookingpress_tip_amount']):0;
                            $bookingpress_purchase_type                  = (isset($entry_data['bookingpress_purchase_type']))?$entry_data['bookingpress_purchase_type']:2;
                            $bookingpress_price_display_setting          = $entry_data['bookingpress_price_display_setting'];
                            $bookingpress_display_tax_order_summary      = $entry_data['bookingpress_display_tax_order_summary'];
                            $bookingpress_included_tax_label             = $entry_data['bookingpress_included_tax_label'];
                            $bookingpress_complete_payment_token         = $entry_data['bookingpress_complete_payment_token'];
                            $bookingpress_complete_payment_url_selection         = $entry_data['bookingpress_complete_payment_url_selection'];
                            $bookingpress_complete_payment_url_selection_method         = $entry_data['bookingpress_complete_payment_url_selection_method'];                                                                                            
                            $payable_amount = ( ! empty( $payment_amount_field ) && ! empty( $payment_gateway_data[ $payment_amount_field ] ) ) ? $payment_gateway_data[ $payment_amount_field ] : $bookingpress_paid_amount;
                            
                            /* Pending Customer Fields Insert */
                            if ( ! empty( $_REQUEST['package_data']['form_fields'] ) && ! empty( $bookingpress_customer_id ) ) {
                                
                                $this->bookingpress_insert_customer_field_data( $bookingpress_customer_id, array_map( array( $BookingPress, 'appointment_sanatize_field' ), $_REQUEST['package_data']['form_fields'] ) ); //phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized --Reason $_REQUEST['appointment_data']['form_fields'] has already been sanitized.
                            }
                            
                            $entry_meta_data = $wpdb->get_results( $wpdb->prepare( "SELECT bookingpress_entry_meta_key,bookingpress_entry_meta_value FROM {$tbl_bookingpress_entries_meta} WHERE bookingpress_entry_id = %d AND (bookingpress_entry_meta_key = 'bookingpress_package_data' OR bookingpress_entry_meta_key = 'bookingpress_package_service_data')", $entry_id ), ARRAY_A ); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- Reason: $tbl_bookingpress_entries_meta is table name defined globally. False Positive alarm    

                            $bookingpress_package_data = array();
                            $bookingpress_package_service_data = '';
                            if(!empty($entry_meta_data)){
                                foreach($entry_meta_data as $entry_meta){                                    
                                    if($entry_meta['bookingpress_entry_meta_key'] == 'bookingpress_package_data'){
                                        $bookingpress_package_data = $entry_meta['bookingpress_entry_meta_value'];
                                    }
                                    if($entry_meta['bookingpress_entry_meta_key'] == 'bookingpress_package_service_data'){
                                        $bookingpress_package_service_data = $entry_meta['bookingpress_entry_meta_value'];
                                    }
                                }
                            }

                            if(!empty($bookingpress_package_data) && !is_array($bookingpress_package_data)){
                                $bookingpress_package_data = json_decode($bookingpress_package_data,true);
                            }
                            $bookingpress_package_id = (isset( $bookingpress_package_data['bookingpress_package_id'] ))?$bookingpress_package_data['bookingpress_package_id']:0;
                            $bookingpress_package_name = (isset( $bookingpress_package_data['bookingpress_package_name'] ))?$bookingpress_package_data['bookingpress_package_name']:'';
                            $bookingpress_package_description = (isset( $bookingpress_package_data['bookingpress_package_description'] ))?$bookingpress_package_data['bookingpress_package_description']:'';
                            $bookingpress_package_price = (isset( $bookingpress_package_data['bookingpress_package_price'] ))?$bookingpress_package_data['bookingpress_package_price']:'';
                            $bookingpress_package_calculated_price = (isset( $bookingpress_package_data['bookingpress_package_calculated_price'] ))?$bookingpress_package_data['bookingpress_package_calculated_price']:'';
                            $bookingpress_package_duration = (isset( $bookingpress_package_data['bookingpress_package_duration'] ))?$bookingpress_package_data['bookingpress_package_duration']:'';
                            $bookingpress_package_duration_unit = (isset( $bookingpress_package_data['bookingpress_package_duration_unit'] ))?$bookingpress_package_data['bookingpress_package_duration_unit']:'';
                            $bookingpress_package_customer_purchase_limit = (isset( $bookingpress_package_data['bookingpress_package_customer_purchase_limit'] ))?$bookingpress_package_data['bookingpress_package_customer_purchase_limit']:0;
                            $bookingpress_package_status = (isset( $bookingpress_package_data['bookingpress_package_status'] ))?$bookingpress_package_data['bookingpress_package_status']:0;

                            $bookingpress_package_expiration_date = $this->get_package_expiration_date($bookingpress_package_id,$bookingpress_package_duration,$bookingpress_package_duration_unit,date('Y-m-d'));

                            $package_booking_fields = array(
                                'bookingpress_package_no'                    => 0,
                                'bookingpress_entry_id'                      => $entry_id,
                                'bookingpress_payment_id'                    => 0,
                                'bookingpress_customer_id'                   => $bookingpress_customer_id,
                                'bookingpress_login_user_id'                 => $bookingpress_login_user_id,
                                'bookingpress_customer_name'      			 => $bookingpress_customer_name, 
                                'bookingpress_username'                      => $bookingpress_customer_username,
                                'bookingpress_customer_firstname' 			 => $bookingpress_customer_firstname,
                                'bookingpress_customer_lastname'  			 => $bookingpress_customer_lastname,
                                'bookingpress_customer_phone'     			 => $bookingpress_customer_phone,
                                'bookingpress_customer_country'   			 => $bookingpress_customer_country,
                                'bookingpress_customer_phone_dial_code'      => $bookingpress_customer_phone_dial_code,
                                'bookingpress_customer_email'     			 => $bookingpress_customer_email, 
                                'bookingpress_package_id'                    => $bookingpress_package_id,
                                'bookingpress_package_name'                  => $bookingpress_package_name,
                                'bookingpress_package_description'           => $bookingpress_package_description,
                                'bookingpress_package_price'                 => $bookingpress_package_price,
                                'bookingpress_package_calculated_price'      => $bookingpress_package_calculated_price,
                                'bookingpress_package_duration'              => $bookingpress_package_duration,
                                'bookingpress_package_duration_unit'         => $bookingpress_package_duration_unit,
                                'bookingpress_package_customer_purchase_limit'=> $bookingpress_package_customer_purchase_limit,
                                'bookingpress_package_status'                => $bookingpress_package_status,
                                'bookingpress_package_purchase_date'         => $bookingpress_package_purchase_date,
                                'bookingpress_package_purchase_time'         => $bookingpress_package_purchase_time,
                                'bookingpress_package_expiration_date'       => $bookingpress_package_expiration_date,
                                'bookingpress_package_services'              => $bookingpress_package_service_data,
                                'bookingpress_package_currency'              => $bookingpress_package_currency,
                                'bookingpress_package_internal_note'         => $bookingpress_package_internal_note,
                                'bookingpress_package_send_notification'     => $bookingpress_package_send_notification,
                                'bookingpress_package_booking_status'        => (!empty($payment_status))?$payment_status:1,
                                'bookingpress_tax_percentage'                => $bookingpress_tax_percentage,
                                'bookingpress_tax_amount'                    => $bookingpress_tax_amount,
                                'bookingpress_price_display_setting'         => $bookingpress_price_display_setting,
                                'bookingpress_tip_amount'                    => $bookingpress_tip_amount,
                                'bookingpress_included_tax_label'            => $bookingpress_included_tax_label,
                                'bookingpress_display_tax_order_summary'     => $bookingpress_display_tax_order_summary,
                                'bookingpress_package_paid_amount'           => $bookingpress_paid_amount,
                                'bookingpress_mark_as_paid'                  => 0,
                                'bookingpress_complete_payment_url_selection'	=> $bookingpress_complete_payment_url_selection,
                                'bookingpress_complete_payment_url_selection_method' => $bookingpress_complete_payment_url_selection_method,
                                'bookingpress_complete_payment_token'        => $bookingpress_complete_payment_token,
                                'bookingpress_package_timezone'			     => $bookingpress_customer_timezone,
                                'bookingpress_package_dst_timezone'			 => $bookingpress_customer_dst_timezone,
                                'bookingpress_package_due_amount'            => $bookingpress_due_amount,
                                'bookingpress_package_total_amount'          => $bookingpress_total_amount,
                                'bookingpress_package_created_at'         	 => current_time('mysql'),
                            );
                            
                            $package_booking_fields = apply_filters( 'bookingpress_modify_package_booking_fields_before_insert', $package_booking_fields, $entry_data );

                            /** Validate again before confirming the payment start */
                                
                            if( !empty( $payment_gateway_data ) && 'woocommerce' != $bookingpress_payment_gateway ){

                                if( 'stripe' == strtolower( trim( $bookingpress_payment_gateway ) ) && empty( $payment_amount_field  ) ){
                                    $payment_amount_field = 'amount';
                                } else if( 'paypal' == strtolower( trim( $bookingpress_payment_gateway ) ) && empty( $payment_amount_field ) ){
                                    $payment_amount_field = 'mc_gross';
                                }
        
                                if( !empty( $payment_amount_field ) && !preg_match( '/\|/', $payment_amount_field ) ){	
                                    $paid_amount = !empty( $payment_gateway_data[ $payment_amount_field ] ) ? $payment_gateway_data[ $payment_amount_field ] : 0;
                                } else {
                                    $paid_amount = apply_filters( 'bookingpress_retrieve_payment_amount_currency_from_payment_data', 0, $payment_gateway_data, $bookingpress_payment_gateway, false );
                                }
                                $paid_amount = apply_filters( 'bookingpress_adjust_paid_amount', $paid_amount, strtolower( $bookingpress_payment_gateway ) );
        
                                /*
                                echo $paid_amount;
                                print_r($payment_gateway_data);
                                echo 'Retrive '.$paid_amount.' Orignal '.$bookingpress_paid_amount; die;
                                */
                                
                                if( floatval( $bookingpress_paid_amount ) != floatval( $paid_amount )){ //&& !empty($paid_amount)
                                    
                                    $suspicious_data = wp_json_encode(
                                        array(
                                            'paid_amount_entries' => $bookingpress_paid_amount,
                                            'paid_amount_payment' => $paid_amount
                                        )
                                    );                                    
                                    status_header( 400, 'Amount Mismatched' );
                                    http_response_code( 400 );
                                    do_action('bookingpress_payment_log_entry', $bookingpress_payment_gateway, 'prevent suspicious payment due to amount mismatched', 'bookingpress', $suspicious_data, $bookingpress_debug_payment_log_id);
                                    die;                                   
                                }
        
                                /** Check for the currency received from the payment gateway data*/
                                $bookingpress_service_currency = $entry_data['bookingpress_service_currency'];
                                if( !empty( $payment_currency_field ) && !empty( $payment_gateway_data[ $payment_currency_field ] ) && !preg_match( '/\|/', $payment_currency_field ) ){
                                    
                                    if( strtolower( trim( $payment_gateway_data[ $payment_currency_field ] ) ) != strtolower( trim( $bookingpress_service_currency ) ) ){
                                        $suspicious_data = wp_json_encode(
                                            array(
                                                'service_currency' => $bookingpress_service_currency,
                                                'paid_in_currency' => $payment_gateway_data[ $payment_currency_field ]
                                            )
                                        );
            
                                        status_header( 400, 'currency Mismatched' );
                                        http_response_code( 400 );
                                        do_action('bookingpress_payment_log_entry', $bookingpress_payment_gateway, 'prevent suspicious payment due to currency mismatched', 'bookingpress', $suspicious_data, $bookingpress_debug_payment_log_id);
                                        die;
                                    }
        
                                } else {
                                    
                                    $payment_currency = apply_filters('bookingpress_retrieve_payment_amount_currency_from_payment_data', '', $payment_gateway_data, $bookingpress_payment_gateway, true );
        
                                    if( !empty( $payment_currency ) && strtolower( trim( $payment_currency ) ) != strtolower( trim( $bookingpress_service_currency ) ) ){
                                        $suspicious_data = wp_json_encode(
                                            array(
                                                'service_currency' => $bookingpress_service_currency,
                                                'paid_in_currency' => $payment_currency
                                            )
                                        );
            
                                        status_header( 400, 'currency Mismatched' );
                                        http_response_code( 400 );
                                        do_action('bookingpress_payment_log_entry', $bookingpress_payment_gateway, 'prevent suspicious payment due to currency mismatched', 'bookingpress', $suspicious_data, $bookingpress_debug_payment_log_id);
                                        die;
                                    }
                                }
                            }

                            /** Over */

                            do_action( 'bookingpress_payment_log_entry', $bookingpress_payment_gateway, 'before insert package', 'bookingpress package', $package_booking_fields, $bookingpress_debug_payment_log_id );

                            $inserted_booking_id = $this->bookingpress_insert_package_booking_log( $package_booking_fields );
                            if(!empty( $inserted_booking_id )){

                                if(session_id() == '' OR session_status() === PHP_SESSION_NONE) {
                                    session_start();
                                }

                                unset( $_SESSION['bpa_package_filter_input'] );

                                $payer_email = ! empty( $payment_gateway_data['payer_email'] ) ? $payment_gateway_data['payer_email'] : $bookingpress_customer_email;

                                global $tbl_bookingpress_settings;
                                $bookingpress_last_invoice_id = $wpdb->get_var( $wpdb->prepare("SELECT setting_value FROM $tbl_bookingpress_settings WHERE setting_name = %s AND setting_type = %s", 'bookingpress_last_invoice_id', 'invoice_setting' ) ); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared --Reason: $tbl_bookingpress_settings is a table name. false alarm

                                $bookingpress_last_invoice_id++;
                                $BookingPress->bookingpress_update_settings( 'bookingpress_last_invoice_id', 'invoice_setting', $bookingpress_last_invoice_id );
                                $bookingpress_last_invoice_id = apply_filters('bookingpress_modify_invoice_id_externally', $bookingpress_last_invoice_id);                                
                                if($bookingpress_payment_gateway == "on-site"){
                                    $payment_status =  2;
                                }

                                $payment_log_data = array(
                                    'bookingpress_invoice_id'              => $bookingpress_last_invoice_id,
                                    'bookingpress_package_order_booking_ref' => $inserted_booking_id,
                                    'bookingpress_package_id'              => $bookingpress_package_id,
                                    'bookingpress_purchase_type'           => $bookingpress_purchase_type,
                                    'bookingpress_customer_id'             => $bookingpress_customer_id,
                                    'bookingpress_customer_name'           => $bookingpress_customer_name,  
                                    'bookingpress_username'                => $bookingpress_customer_username,
                                    'bookingpress_customer_firstname'      => $bookingpress_customer_firstname,
                                    'bookingpress_customer_lastname'       => $bookingpress_customer_lastname,
                                    'bookingpress_customer_phone'          => $bookingpress_customer_phone,
                                    'bookingpress_customer_country'        => $bookingpress_customer_country,
                                    'bookingpress_customer_phone_dial_code' => $bookingpress_customer_phone_dial_code,
                                    'bookingpress_customer_email'          => $bookingpress_customer_email,
                                    'bookingpress_service_id'              => 0,
                                    'bookingpress_service_name'            => '',

                                    'bookingpress_package_name'            => $bookingpress_package_name,
                                    'bookingpress_package_price'           => $bookingpress_package_price,                                   

                                    'bookingpress_service_price'           => 0,
                                    'bookingpress_payment_currency'        => $bookingpress_package_currency,
                                    'bookingpress_service_duration_val'    => 0,
                                    'bookingpress_service_duration_unit'   => '',
                                    'bookingpress_appointment_date'        => date('Y-m-d'),
                                    'bookingpress_appointment_start_time'  => date('H:i:s'),
                                    'bookingpress_appointment_end_time'    => '00:00:00',
                                    'bookingpress_payment_gateway'         => $bookingpress_payment_gateway,
                                    'bookingpress_payer_email'             => $payer_email,
                                    'bookingpress_transaction_id'          => $transaction_id,
                                    'bookingpress_payment_date_time'       => current_time( 'mysql' ),
                                    'bookingpress_payment_status'          => $payment_status,
                                    'bookingpress_payment_amount'          => $payable_amount,
                                    'bookingpress_payment_currency'        => $bookingpress_package_currency,
                                    'bookingpress_payment_type'            => '',
                                    'bookingpress_payment_response'        => '',
                                    'bookingpress_additional_info'         => '',
                                    'bookingpress_coupon_details'          => '[]',
                                    'bookingpress_coupon_discount_amount'  => 0,
                                    'bookingpress_tax_percentage'          => $bookingpress_tax_percentage,
                                    'bookingpress_tax_amount'              => $bookingpress_tax_amount,
                                    'bookingpress_price_display_setting'   => $bookingpress_price_display_setting,
                                    'bookingpress_display_tax_order_summary' => $bookingpress_display_tax_order_summary,
                                    'bookingpress_included_tax_label'      => $bookingpress_included_tax_label,
                                    /*   
                                    'bookingpress_deposit_payment_details' => $bookingpress_deposit_payment_details,
                                    'bookingpress_deposit_amount'          => $bookingpress_deposit_amount, 
                                    */

                                    
                                    'bookingpress_paid_amount'             => $bookingpress_paid_amount,
                                    'bookingpress_due_amount'              => $bookingpress_due_amount,
                                    'bookingpress_total_amount'            => $bookingpress_total_amount,
                                    'bookingpress_created_at'              => current_time( 'mysql' ),
                                );

                                /*
                                'bookingpress_staff_member_id'         => $bookingpress_staff_member_id,
                                'bookingpress_staff_member_price'      => $bookingpress_staff_member_price,
                                'bookingpress_staff_first_name'        => $bookingpress_staff_first_name,
                                'bookingpress_staff_last_name'         => $bookingpress_staff_last_name,
                                'bookingpress_staff_email_address'     => $bookingpress_staff_email_address,
                                'bookingpress_staff_member_details'    => $bookingpress_staff_member_details,
                                */

                                $payment_log_data = apply_filters( 'bookingpress_modify_package_order_payment_log_fields_before_insert', $payment_log_data, $entry_data );
                                   
                                do_action( 'bookingpress_payment_log_entry', $bookingpress_payment_gateway, 'before insert package payment', 'bookingpress package', $payment_log_data, $bookingpress_debug_payment_log_id );

                                $package_booking_fields_data = $package_booking_fields;

                                $payment_log_id = $BookingPress->bookingpress_insert_payment_logs( $payment_log_data );
                                if(!empty($payment_log_id)){

                                    $wpdb->update($tbl_bookingpress_package_bookings, array('bookingpress_payment_id' => $payment_log_id), array('bookingpress_package_booking_id' => $inserted_booking_id));
                                    $wpdb->update($tbl_bookingpress_package_bookings, array('bookingpress_package_no' => $bookingpress_last_invoice_id), array('bookingpress_package_booking_id' => $inserted_booking_id));
                                    
                                    //New Change Start
                                    $wpdb->update($tbl_bookingpress_entries, array('bookingpress_package_no' => $bookingpress_last_invoice_id), array('bookingpress_entry_id' => $entry_data['bookingpress_entry_id']));

                                    //$wpdb->update($tbl_bookingpress_entries, array('bookingpress_order_id' => $bookingpress_last_invoice_id), array('bookingpress_entry_id' => $entry_data['bookingpress_entry_id']));
                                    
                                    $package_booking_fields_data['bookingpress_package_no'] = $bookingpress_last_invoice_id;
                                    $package_booking_fields_data['bookingpress_package_payment_id'] = $payment_log_id;
                                    $package_booking_fields_data['bookingpress_package_booking_id'] = $inserted_booking_id;
                                }
                                do_action('bookingpress_after_add_package_order', $entry_id,$inserted_booking_id);
                                $bookingpress_is_allowed_email_notification = ! empty($bookingpress_package_send_notification) ? 1 : 0;   
                                
                                

                                if($bookingpress_is_allowed_email_notification) { 

                                    do_action('bookingpress_other_debug_log_entry', 'email_notification_debug_logs', 'Send Email notification package data', 'bookingpress_email_notiifcation', $package_booking_fields_data, $bookingpress_other_debug_log_id);
                                    
                                    $bookingpress_admin_email='';
                                    $bookingpress_admin_emails = $BookingPress->bookingpress_get_settings('admin_email', 'notification_setting');
                                    if ( ! empty( $bookingpress_admin_emails ) ) {
                                        $bookingpress_admin_emails = explode( ',', $bookingpress_admin_emails );
                                        $bookingpress_admin_email = $bookingpress_admin_emails[0];
                                    }
                                    $from_name = $BookingPress->bookingpress_get_settings('sender_name', 'notification_setting');
                                    $from_email = $BookingPress->bookingpress_get_settings('sender_email', 'notification_setting');
                                    $bookingpress_package_customer_notification_data = $this->bookingpress_get_package_order_notification('customer', 'Package Order',$inserted_booking_id);
                                    $bookingpress_package_admin_notification_data = $this->bookingpress_get_package_order_notification('employee', 'Package Order',$inserted_booking_id);

                                    if ( ! empty( $bookingpress_package_customer_notification_data ) ) {
                                        $reply_to_name = $BookingPress->bookingpress_get_settings('sender_name', 'notification_setting');
                                        $reply_to = $bookingpress_admin_email;
                                        foreach ( $bookingpress_package_customer_notification_data as $bookingpress_customer_default_notification_key => $bookingpress_customer_default_notification_val ) {
                                            if($bookingpress_customer_default_notification_val['bookingpress_notification_status'] == 1){
                                                $customer_email_subject = $bookingpress_customer_default_notification_val['bookingpress_notification_subject'];
                                                $customer_email_message = $bookingpress_customer_default_notification_val['bookingpress_notification_message'];
                                                /*                                                                                                
                                                $customer_email_subject = $this->bookingpress_replace_package_notification_data($customer_email_subject,$package_booking_fields_data,'customer');
                                                $customer_email_message = $this->bookingpress_replace_package_notification_data($customer_email_message,$package_booking_fields_data,'customer');
                                                */
                                                $customer_email_content_data = $this->bookingpress_replace_package_notification_data_new($customer_email_subject,$customer_email_message,$package_booking_fields_data,'customer');                                                
                                                $customer_email_subject = (!empty($customer_email_content_data['template_content_subject']))?$customer_email_content_data['template_content_subject']:'';
                                                $customer_email_message = (!empty($customer_email_content_data['template_content_body']))?$customer_email_content_data['template_content_body']:'';                                                
                                                
                                                $bookingpress_email_notifications->bookingpress_send_custom_email_notifications( $bookingpress_customer_email, stripslashes_deep( $customer_email_subject ), stripslashes_deep( $customer_email_message ), stripslashes_deep( $from_name ), $from_email, $reply_to, stripslashes_deep( $reply_to_name ) );

                                            }
                                        }
                                    }
                                    if ( ! empty( $bookingpress_package_admin_notification_data ) ) {
                                        $reply_to_name='';
                                        if(!empty($bookingpress_customer_firstname)) {
                                            $reply_to_name = $bookingpress_customer_firstname.' '; 
                                        }
                                        if(!empty($bookingpress_customer_lastname)) {
                                            $reply_to_name .= $bookingpress_customer_lastname; 
                                        }
                                        if(empty($reply_to_name)) {
                                            $reply_to_name = $BookingPress->bookingpress_get_settings('sender_name', 'notification_setting');   
                                        }
                                        $reply_to = $bookingpress_customer_email;
                                        if(empty($reply_to)) {
                                            $reply_to = $BookingPress->bookingpress_get_settings('sender_email', 'notification_setting');
                                        }                                  
                                        foreach ( $bookingpress_package_admin_notification_data as $bookingpress_admin_default_notification_key => $bookingpress_admin_default_notification_val ) {
                                            if($bookingpress_admin_default_notification_val['bookingpress_notification_status'] == 1){

                                                $admin_email_subject = $bookingpress_admin_default_notification_val['bookingpress_notification_subject'];
                                                $admin_email_content = $bookingpress_admin_default_notification_val['bookingpress_notification_message'];
                                                /*
                                                $admin_email_subject = $this->bookingpress_replace_package_notification_data($admin_email_subject,$package_booking_fields_data,'employee');
                                                $admin_email_content = $this->bookingpress_replace_package_notification_data($admin_email_content,$package_booking_fields_data,'employee');
                                                */
                                                $admin_email_content_data = $this->bookingpress_replace_package_notification_data_new($admin_email_subject,$admin_email_content, $package_booking_fields_data, 'employee');
                                                $admin_email_subject = (!empty($admin_email_content_data['template_content_subject']))?$admin_email_content_data['template_content_subject']:'';
                                                $admin_email_content = (!empty($admin_email_content_data['template_content_body']))?$admin_email_content_data['template_content_body']:'';                                                                                                

                                                $bookingpress_email_notifications->bookingpress_send_custom_email_notifications( $bookingpress_admin_email, stripslashes_deep( $admin_email_subject ), stripslashes_deep( $admin_email_content ), stripslashes_deep( $from_name ), $from_email, $reply_to, stripslashes_deep( $reply_to_name ) );                                               

                                            }
                                        }
                                    }                                                                        

                                    do_action('bookingpress_after_send_package_order_notification');

                                }
                                return $payment_log_id;

                            }
                        }
                    }
                }

            }

            return $is_package_booking;
        }

        function bookingpress_replace_package_notification_data_new($template_content_subject, $template_content_body, $package_booking_data,$type){
            /* replacing the company data */            
            global $BookingPress, $bookingpress_package, $bookingpress_global_options, $bookingpress_package, $wpdb, $tbl_bookingpress_payment_logs, $tbl_bookingpress_form_fields, $tbl_bookingpress_package_bookings_meta,$BookingPressPro;

            $return_data = array('template_content_subject'=>$template_content_subject,'template_content_body'=>$template_content_body);
            if($type == 'employee'){
                do_action('bookingpress_multi_language_data_unset');
            }

            $company_name    = esc_html($BookingPress->bookingpress_get_settings('company_name', 'company_setting'));
            $company_address = esc_html($BookingPress->bookingpress_get_settings('company_address', 'company_setting'));
            $company_phone   = esc_html($BookingPress->bookingpress_get_settings('company_phone_number', 'company_setting'));
            $company_website = $BookingPress->bookingpress_get_settings('company_website', 'company_setting');
     
            $template_content_subject = str_replace('%company_address%', $company_address, $template_content_subject);
            $template_content_subject = str_replace('%company_name%', $company_name, $template_content_subject);
            $template_content_subject = str_replace('%company_phone%', $company_phone, $template_content_subject);
            $template_content_subject = str_replace('%company_website%', $company_website, $template_content_subject);

            $template_content_body = str_replace('%company_address%', $company_address, $template_content_body);
            $template_content_body = str_replace('%company_name%', $company_name, $template_content_body);
            $template_content_body = str_replace('%company_phone%', $company_phone, $template_content_body);
            $template_content_body = str_replace('%company_website%', $company_website, $template_content_body);            

            /*****  replacing the company data *****/
            if(!empty($package_booking_data)) {
                /* Package - Custoemr related data */  
                $global_data = $bookingpress_global_options->bookingpress_global_options();
                $default_date_format = $global_data['wp_default_date_format'];
                $default_time_format = $global_data['wp_default_time_format'];
                $bookingpress_package_status_arr = $bookingpress_package->get_package_order_status();


                $bookingpress_customer_email = isset($package_booking_data['bookingpress_customer_email']) ? esc_html($package_booking_data['bookingpress_customer_email']) : '';
                $bookingpress_customer_firstname = isset($package_booking_data['bookingpress_customer_firstname']) ? esc_html($package_booking_data['bookingpress_customer_firstname']) : '';
                $bookingpress_customer_lastname = isset($package_booking_data['bookingpress_customer_lastname']) ? esc_html($package_booking_data['bookingpress_customer_lastname']) : '';
                $bookingpress_package_internal_note = isset($package_booking_data['bookingpress_package_internal_note']) ? esc_html($package_booking_data['bookingpress_package_internal_note']) : '';
                $bookingpress_customer_phone = isset($package_booking_data['bookingpress_customer_phone']) ? esc_html($package_booking_data['bookingpress_customer_phone']) : '';
                if(!empty($package_booking_data['bookingpress_customer_phone_dial_code'])){
                    $bookingpress_customer_phone = "+".$package_booking_data['bookingpress_customer_phone_dial_code']." ".$bookingpress_customer_phone;
                }    
                $bookingpress_customer_fullname = $bookingpress_customer_firstname.' '.$bookingpress_customer_lastname;

                $template_content_subject = str_replace('%customer_email%', $bookingpress_customer_email, $template_content_subject);
                $template_content_subject = str_replace('%customer_first_name%', stripslashes_deep($bookingpress_customer_firstname), $template_content_subject);
                $template_content_subject = str_replace('%customer_last_name%', stripslashes_deep($bookingpress_customer_lastname), $template_content_subject);
                $template_content_subject = str_replace('%customer_full_name%', stripslashes_deep($bookingpress_customer_fullname), $template_content_subject);
                $template_content_subject = str_replace('%customer_note%', stripslashes_deep($bookingpress_package_internal_note), $template_content_subject);
                $template_content_subject = str_replace('%customer_phone%', $bookingpress_customer_phone, $template_content_subject);

                $template_content_body = str_replace('%customer_email%', $bookingpress_customer_email, $template_content_body);
                $template_content_body = str_replace('%customer_first_name%', stripslashes_deep($bookingpress_customer_firstname), $template_content_body);
                $template_content_body = str_replace('%customer_last_name%', stripslashes_deep($bookingpress_customer_lastname), $template_content_body);
                $template_content_body = str_replace('%customer_full_name%', stripslashes_deep($bookingpress_customer_fullname), $template_content_body);
                $template_content_body = str_replace('%customer_note%', stripslashes_deep($bookingpress_package_internal_note), $template_content_body);
                $template_content_body = str_replace('%customer_phone%', $bookingpress_customer_phone, $template_content_body);                

                /* Package - Custoemr related data */
                /* Custom field data replacement*/
                $custom_field_data = array();
                $bookingpress_package_booking_id = isset($package_booking_data['bookingpress_package_booking_id']) ? intval($package_booking_data['bookingpress_package_booking_id']) : '';
                $bookingpress_custom_fields_meta_values = array();
				$bookingpress_custom_fields_meta_values = $this->bookingpress_get_package_order_form_field_data($bookingpress_package_booking_id);
                if(!empty($bookingpress_custom_fields_meta_values)){
					foreach($bookingpress_custom_fields_meta_values as $k2 => $v2) {
						
                        $bookingpress_field_data = $wpdb->get_row( $wpdb->prepare( "SELECT bookingpress_field_type,bookingpress_field_options,bookingpress_field_values FROM {$tbl_bookingpress_form_fields} WHERE bookingpress_field_meta_key = %s AND bookingpress_is_customer_field = %d", $k2,0) ); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared --Reason: $tbl_bookingpress_form_fields is a table name. false alarm
						//Replace all custom fields values

                        if(!empty($bookingpress_field_data) && $bookingpress_field_data->bookingpress_field_type == 'date' && !empty($bookingpress_field_data->bookingpress_field_options) && !empty($v2)) {
							$bookingpress_field_options = json_decode($bookingpress_field_data->bookingpress_field_options,true);
							if(!empty($bookingpress_field_options['enable_timepicker']) && $bookingpress_field_options['enable_timepicker'] == 'true') {
								$default_date_time_format = $default_date_format.' '.$default_time_format;  
								$v2 = date($default_date_time_format,strtotime($v2));
							} else {
								$v2 = date($default_date_format,strtotime($v2));
							}
						}
						if( is_array( $v2 ) ){
							$v2 = implode( ',', $v2 );
						}
						$template_content_subject    = str_replace( '%'.$k2.'%', $v2, $template_content_subject);
                        $template_content_body       = str_replace( '%'.$k2.'%', $v2, $template_content_body);
					}
                    $bookingpress_existing_custom_fields = $wpdb->get_results($wpdb->prepare("SELECT bookingpress_field_meta_key FROM {$tbl_bookingpress_form_fields} WHERE bookingpress_field_is_default = %d",0), ARRAY_A); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared --Reason: $tbl_bookingpress_form_fields is a table name. false alarm
                    if(!empty($bookingpress_existing_custom_fields)){
						foreach($bookingpress_existing_custom_fields as $k3 => $v3){
							if(!array_key_exists($v3['bookingpress_field_meta_key'], $bookingpress_custom_fields_meta_values)){

								$template_content_subject   = str_replace( '%'.$v3['bookingpress_field_meta_key'].'%', '', $template_content_subject);
                                $template_content_body      = str_replace( '%'.$v3['bookingpress_field_meta_key'].'%', '', $template_content_body);

							}
						}
					}
                }else{
					$bookingpress_existing_custom_fields = $wpdb->get_results($wpdb->prepare("SELECT bookingpress_field_meta_key FROM {$tbl_bookingpress_form_fields} WHERE bookingpress_field_is_default =%d",0), ARRAY_A); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared --Reason: $tbl_bookingpress_form_fields is a table name. false alarm
					if(!empty($bookingpress_existing_custom_fields)){
						foreach($bookingpress_existing_custom_fields as $k3 => $v3){
							$template_content_subject    = str_replace( '%'.$v3['bookingpress_field_meta_key'].'%', '', $template_content_subject);
                            $template_content_body       = str_replace( '%'.$v3['bookingpress_field_meta_key'].'%', '', $template_content_body);
						}
					}
				}
                
                /* Custom field data replacement*/
                /* Package related data */
                $package_name = isset($package_booking_data['bookingpress_package_name']) ? $package_booking_data['bookingpress_package_name'] : '';
                if($bookingpress_package->is_multi_language_addon_active()){
                    if(method_exists( $BookingPressPro, 'bookingpress_pro_front_language_translation_func') ) {
                        $bookingpress_package_id = $package_booking_data['bookingpress_package_id'];
                        $package_name = $BookingPressPro->bookingpress_pro_front_language_translation_func($package_name,'package','bookingpress_package_name',$bookingpress_package_id);                       
                    }
                }                  

                $bookingpress_pkg_currency = !empty($package_booking_data['bookingpress_package_currency']) ? esc_html($package_booking_data['bookingpress_package_currency']) : '';
                $bookingpress_currency_symbol = $BookingPress->bookingpress_get_currency_symbol($bookingpress_pkg_currency);
                $package_price = ! empty($package_booking_data['bookingpress_package_price']) ? $BookingPress->bookingpress_price_formatter_with_currency_symbol($package_booking_data['bookingpress_package_price'], $bookingpress_currency_symbol) : 0;
                $package_price = ! empty($package_booking_data['bookingpress_package_total_amount']) ? $BookingPress->bookingpress_price_formatter_with_currency_symbol($package_booking_data['bookingpress_package_total_amount'], $bookingpress_currency_symbol) : 0;                
                $bookingpress_package_duration = isset($package_booking_data['bookingpress_package_duration']) ? $package_booking_data['bookingpress_package_duration'] : '';
                $bookingpress_package_duration_unit = isset($package_booking_data['bookingpress_package_duration_unit']) ? $package_booking_data['bookingpress_package_duration_unit'] : '';
                $package_duration = $bookingpress_package->get_package_duration_limit_text($bookingpress_package_duration,$bookingpress_package_duration_unit,true);
                $bookingpress_package_purchase_date = isset($package_booking_data['bookingpress_package_purchase_date']) ? $package_booking_data['bookingpress_package_duration_unit'] : '';
                $bookingpress_package_expiration_date = isset($package_booking_data['bookingpress_package_expiration_date']) ? $package_booking_data['bookingpress_package_expiration_date'] : '';
                if(!empty($bookingpress_package_purchase_date)) {
                    $bookingpress_package_purchase_date = date_i18n($default_date_format, strtotime($bookingpress_package_purchase_date));
                }
                if(!empty($bookingpress_package_expiration_date)) {
                    $bookingpress_package_expiration_date = date_i18n($default_date_format, strtotime($bookingpress_package_expiration_date));
                }
                $bookingpress_package_booking_status = esc_html($package_booking_data['bookingpress_package_booking_status']);
                $bookingpress_package_status_label = $bookingpress_package_booking_status;
                if(is_array($bookingpress_package_status_arr)){
                    foreach($bookingpress_package_status_arr as $status_key => $status_val){
                        if($bookingpress_package_booking_status == $status_val['value']){
                            $bookingpress_package_status_label = $status_val['text'];
                            break;
                        }    
                    }
                }
                $bookingpress_package_services_content = '';
                if(isset($package_booking_data['bookingpress_package_services']) && !empty($package_booking_data['bookingpress_package_services'])) {
                    $package_services_data = json_decode($package_booking_data['bookingpress_package_services'], true);
                    $bookingpress_package_services_content = "<table border='1' cellpadding='10' cellspacing='0' style='border-color:#ccc'>";
                    foreach($package_services_data as $service_data_id => $service_data){
                        $service_name = isset($service_data['bookingpress_service_name']) ? $service_data['bookingpress_service_name'] : '';
                        $service_no_of_appointment = isset($service_data['bookingpress_no_of_appointments']) ? $service_data['bookingpress_no_of_appointments'] : '';
                        $bookingpress_package_services_content .= "<tr>";
                            $bookingpress_package_services_content .= "<td>".$service_name." </td>";
                            $bookingpress_package_services_content .= "<td>".$service_no_of_appointment."</td>";
                        $bookingpress_package_services_content .= "</tr>";
                    }
                    $bookingpress_package_services_content .= "</table>";                 
                }
                $bookingpress_package_booking_no = isset($package_booking_data['bookingpress_package_no']) ? $package_booking_data['bookingpress_package_no'] : '';
                $bookingpress_payment_method = '';
                $bookingpress_package_payment_id = isset($package_booking_data['bookingpress_package_payment_id']) ? $package_booking_data['bookingpress_package_payment_id'] : '';
                $bookingpress_payment_details = $wpdb->get_row($wpdb->prepare("SELECT bookingpress_payment_gateway FROM {$tbl_bookingpress_payment_logs} WHERE bookingpress_payment_log_id   = %d", $bookingpress_package_payment_id), ARRAY_A);//phpcs:ignore
                if(!empty($bookingpress_payment_details['bookingpress_payment_gateway'])){
                    $bookingpress_payment_method = $bookingpress_payment_details['bookingpress_payment_gateway'];
                    if(!empty($bookingpress_payment_method) && $bookingpress_payment_method != 'manual') {
                        $bookingpress_payment_method = $BookingPress->bookingpress_get_customize_settings($bookingpress_payment_method.'_text','package_booking_form');
                    }
                }

                $bookingpress_payment_tax_amount = isset($package_booking_data['bookingpress_tax_amount']) ? $package_booking_data['bookingpress_tax_amount'] : 0;
                if(empty($bookingpress_payment_tax_amount)){
                    $bookingpress_payment_tax_amount = 0;
                }
                $bookingpress_payment_tax_amount = $BookingPress->bookingpress_price_formatter_with_currency_symbol($bookingpress_payment_tax_amount, $bookingpress_currency_symbol);

                $template_content_subject = str_replace('%package_name%', $package_name, $template_content_subject);
                $template_content_subject = str_replace('%package_payment_amount%', $package_price, $template_content_subject);
                $template_content_subject = str_replace('%package_duration%', $package_duration, $template_content_subject); 
                $template_content_subject = str_replace('%package_status%', $bookingpress_package_status_label, $template_content_subject); 
                $template_content_subject = str_replace('%package_purchase_date%', $bookingpress_package_purchase_date, $template_content_subject); 
                $template_content_subject = str_replace('%package_expiry_date%', $bookingpress_package_expiration_date, $template_content_subject); 
                $template_content_subject = str_replace('%package_services_added%', $bookingpress_package_services_content, $template_content_subject); 
                $template_content_subject = str_replace('%package_booking_id%', $bookingpress_package_booking_no, $template_content_subject); 
                $template_content_subject = str_replace('%package_payment_method%',$bookingpress_payment_method,$template_content_subject);
                $template_content_subject = str_replace('%package_tax_amount%',$bookingpress_payment_tax_amount,$template_content_subject);

                $template_content_body = str_replace('%package_name%', $package_name, $template_content_body);
                $template_content_body = str_replace('%package_payment_amount%', $package_price, $template_content_body);
                $template_content_body = str_replace('%package_duration%', $package_duration, $template_content_body); 
                $template_content_body = str_replace('%package_status%', $bookingpress_package_status_label, $template_content_body); 
                $template_content_body = str_replace('%package_purchase_date%', $bookingpress_package_purchase_date, $template_content_body); 
                $template_content_body = str_replace('%package_expiry_date%', $bookingpress_package_expiration_date, $template_content_body); 
                $template_content_body = str_replace('%package_services_added%', $bookingpress_package_services_content, $template_content_body); 
                $template_content_body = str_replace('%package_booking_id%', $bookingpress_package_booking_no, $template_content_body); 
                $template_content_body = str_replace('%package_payment_method%',$bookingpress_payment_method,$template_content_body);
                $template_content_body = str_replace('%package_tax_amount%',$bookingpress_payment_tax_amount,$template_content_body);
                /* Package related data */
            }
            
            $return_data = array('template_content_subject'=>$template_content_subject,'template_content_body'=>$template_content_body);

            return $return_data;
        }

        function bookingpress_replace_package_notification_data($template_content, $package_booking_data,$type){
            /* replacing the company data */            
            global $BookingPress, $bookingpress_package, $bookingpress_global_options, $bookingpress_package, $wpdb, $tbl_bookingpress_payment_logs, $tbl_bookingpress_form_fields, $tbl_bookingpress_package_bookings_meta,$BookingPressPro;

            if($type == 'employee'){
                do_action('bookingpress_multi_language_data_unset');
            }

            $company_name    = esc_html($BookingPress->bookingpress_get_settings('company_name', 'company_setting'));
            $company_address = esc_html($BookingPress->bookingpress_get_settings('company_address', 'company_setting'));
            $company_phone   = esc_html($BookingPress->bookingpress_get_settings('company_phone_number', 'company_setting'));
            $company_website = $BookingPress->bookingpress_get_settings('company_website', 'company_setting');
     
            $template_content = str_replace('%company_address%', $company_address, $template_content);
            $template_content = str_replace('%company_name%', $company_name, $template_content);
            $template_content = str_replace('%company_phone%', $company_phone, $template_content);
            $template_content = str_replace('%company_website%', $company_website, $template_content);
            /*****  replacing the company data *****/
            if(!empty($package_booking_data)) {
                /* Package - Custoemr related data */  
                $global_data = $bookingpress_global_options->bookingpress_global_options();
                $default_date_format = $global_data['wp_default_date_format'];
                $default_time_format = $global_data['wp_default_time_format'];
                $bookingpress_package_status_arr = $bookingpress_package->get_package_order_status();


                $bookingpress_customer_email = isset($package_booking_data['bookingpress_customer_email']) ? esc_html($package_booking_data['bookingpress_customer_email']) : '';
                $bookingpress_customer_firstname = isset($package_booking_data['bookingpress_customer_firstname']) ? esc_html($package_booking_data['bookingpress_customer_firstname']) : '';
                $bookingpress_customer_lastname = isset($package_booking_data['bookingpress_customer_lastname']) ? esc_html($package_booking_data['bookingpress_customer_lastname']) : '';
                $bookingpress_package_internal_note = isset($package_booking_data['bookingpress_package_internal_note']) ? esc_html($package_booking_data['bookingpress_package_internal_note']) : '';
                $bookingpress_customer_phone = isset($package_booking_data['bookingpress_customer_phone']) ? esc_html($package_booking_data['bookingpress_customer_phone']) : '';
                if(!empty($package_booking_data['bookingpress_customer_phone_dial_code'])){
                    $bookingpress_customer_phone = "+".$package_booking_data['bookingpress_customer_phone_dial_code']." ".$bookingpress_customer_phone;
                }    
                $bookingpress_customer_fullname = $bookingpress_customer_firstname.' '.$bookingpress_customer_lastname;
                $template_content = str_replace('%customer_email%', $bookingpress_customer_email, $template_content);
                $template_content = str_replace('%customer_first_name%', stripslashes_deep($bookingpress_customer_firstname), $template_content);
                $template_content = str_replace('%customer_last_name%', stripslashes_deep($bookingpress_customer_lastname), $template_content);
                $template_content = str_replace('%customer_full_name%', stripslashes_deep($bookingpress_customer_fullname), $template_content);
                $template_content = str_replace('%customer_note%', stripslashes_deep($bookingpress_package_internal_note), $template_content);
                $template_content = str_replace('%customer_phone%', $bookingpress_customer_phone, $template_content);
                /* Package - Custoemr related data */
                /* Custom field data replacement*/
                $custom_field_data = array();
                $bookingpress_package_booking_id = isset($package_booking_data['bookingpress_package_booking_id']) ? intval($package_booking_data['bookingpress_package_booking_id']) : '';
                $bookingpress_custom_fields_meta_values = array();
				$bookingpress_custom_fields_meta_values = $this->bookingpress_get_package_order_form_field_data($bookingpress_package_booking_id);
                if(!empty($bookingpress_custom_fields_meta_values)){
					foreach($bookingpress_custom_fields_meta_values as $k2 => $v2) {
						$bookingpress_field_data = $wpdb->get_row( $wpdb->prepare( "SELECT bookingpress_field_type,bookingpress_field_options,bookingpress_field_values FROM {$tbl_bookingpress_form_fields} WHERE bookingpress_field_meta_key = %s AND bookingpress_is_customer_field = %d", $k2,0) ); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared --Reason: $tbl_bookingpress_form_fields is a table name. false alarm
						//Replace all custom fields values
                        if(!empty($bookingpress_field_data) && $bookingpress_field_data->bookingpress_field_type == 'date' && !empty($bookingpress_field_data->bookingpress_field_options) && !empty($v2)) {
							$bookingpress_field_options = json_decode($bookingpress_field_data->bookingpress_field_options,true);
							if(!empty($bookingpress_field_options['enable_timepicker']) && $bookingpress_field_options['enable_timepicker'] == 'true') {
								$default_date_time_format = $default_date_format.' '.$default_time_format;  
								$v2 = date($default_date_time_format,strtotime($v2));
							} else {
								$v2 = date($default_date_format,strtotime($v2));
							}
						}
						if( is_array( $v2 ) ){
							$v2 = implode( ',', $v2 );
						}
						$template_content       = str_replace( '%'.$k2.'%', $v2, $template_content);
					}
                    $bookingpress_existing_custom_fields = $wpdb->get_results($wpdb->prepare("SELECT bookingpress_field_meta_key FROM {$tbl_bookingpress_form_fields} WHERE bookingpress_field_is_default = %d",0), ARRAY_A); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared --Reason: $tbl_bookingpress_form_fields is a table name. false alarm
                    if(!empty($bookingpress_existing_custom_fields)){
						foreach($bookingpress_existing_custom_fields as $k3 => $v3){
							if(!array_key_exists($v3['bookingpress_field_meta_key'], $bookingpress_custom_fields_meta_values)){
								$template_content       = str_replace( '%'.$v3['bookingpress_field_meta_key'].'%', '', $template_content);
							}
						}
					}
                }else{
					$bookingpress_existing_custom_fields = $wpdb->get_results($wpdb->prepare("SELECT bookingpress_field_meta_key FROM {$tbl_bookingpress_form_fields} WHERE bookingpress_field_is_default =%d",0), ARRAY_A); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared --Reason: $tbl_bookingpress_form_fields is a table name. false alarm
					if(!empty($bookingpress_existing_custom_fields)){
						foreach($bookingpress_existing_custom_fields as $k3 => $v3){
							$template_content       = str_replace( '%'.$v3['bookingpress_field_meta_key'].'%', '', $template_content);
						}
					}
				}
                
                /* Custom field data replacement*/
                /* Package related data */
                $package_name = isset($package_booking_data['bookingpress_package_name']) ? $package_booking_data['bookingpress_package_name'] : '';
                if($bookingpress_package->is_multi_language_addon_active()){
                    if(method_exists( $BookingPressPro, 'bookingpress_pro_front_language_translation_func') ) {
                        $bookingpress_package_id = $package_booking_data['bookingpress_package_id'];
                        $package_name = $BookingPressPro->bookingpress_pro_front_language_translation_func($package_name,'package','bookingpress_package_name',$bookingpress_package_id);                       
                    }
                }                  

                $bookingpress_pkg_currency = !empty($package_booking_data['bookingpress_package_currency']) ? esc_html($package_booking_data['bookingpress_package_currency']) : '';
                $bookingpress_currency_symbol = $BookingPress->bookingpress_get_currency_symbol($bookingpress_pkg_currency);
                $package_price = ! empty($package_booking_data['bookingpress_package_price']) ? $BookingPress->bookingpress_price_formatter_with_currency_symbol($package_booking_data['bookingpress_package_price'], $bookingpress_currency_symbol) : 0;
                $bookingpress_package_duration = isset($package_booking_data['bookingpress_package_duration']) ? $package_booking_data['bookingpress_package_duration'] : '';
                $bookingpress_package_duration_unit = isset($package_booking_data['bookingpress_package_duration_unit']) ? $package_booking_data['bookingpress_package_duration_unit'] : '';
                $package_duration = $bookingpress_package->get_package_duration_limit_text($bookingpress_package_duration,$bookingpress_package_duration_unit,true);
                $bookingpress_package_purchase_date = isset($package_booking_data['bookingpress_package_purchase_date']) ? $package_booking_data['bookingpress_package_duration_unit'] : '';
                $bookingpress_package_expiration_date = isset($package_booking_data['bookingpress_package_expiration_date']) ? $package_booking_data['bookingpress_package_expiration_date'] : '';
                if(!empty($bookingpress_package_purchase_date)) {
                    $bookingpress_package_purchase_date = date_i18n($default_date_format, strtotime($bookingpress_package_purchase_date));
                }
                if(!empty($bookingpress_package_expiration_date)) {
                    $bookingpress_package_expiration_date = date_i18n($default_date_format, strtotime($bookingpress_package_expiration_date));
                }
                $bookingpress_package_booking_status = esc_html($package_booking_data['bookingpress_package_booking_status']);
                $bookingpress_package_status_label = $bookingpress_package_booking_status;
                if(is_array($bookingpress_package_status_arr)){
                    foreach($bookingpress_package_status_arr as $status_key => $status_val){
                        if($bookingpress_package_booking_status == $status_val['value']){
                            $bookingpress_package_status_label = $status_val['text'];
                            break;
                        }    
                    }
                }
                $bookingpress_package_services_content = '';
                if(isset($package_booking_data['bookingpress_package_services']) && !empty($package_booking_data['bookingpress_package_services'])) {
                    $package_services_data = json_decode($package_booking_data['bookingpress_package_services'], true);
                    $bookingpress_package_services_content = "<table border='1' cellpadding='10' cellspacing='0' style='border-color:#ccc'>";
                    foreach($package_services_data as $service_data_id => $service_data){
                        $service_name = isset($service_data['bookingpress_service_name']) ? $service_data['bookingpress_service_name'] : '';
                        $service_no_of_appointment = isset($service_data['bookingpress_no_of_appointments']) ? $service_data['bookingpress_no_of_appointments'] : '';
                        $bookingpress_package_services_content .= "<tr>";
                            $bookingpress_package_services_content .= "<td>".$service_name." </td>";
                            $bookingpress_package_services_content .= "<td>".$service_no_of_appointment."</td>";
                        $bookingpress_package_services_content .= "</tr>";
                    }
                    $bookingpress_package_services_content .= "</table>";                 
                }
                $bookingpress_package_booking_no = isset($package_booking_data['bookingpress_package_no']) ? $package_booking_data['bookingpress_package_no'] : '';
                $bookingpress_payment_method = '';
                $bookingpress_package_payment_id = isset($package_booking_data['bookingpress_package_payment_id']) ? $package_booking_data['bookingpress_package_payment_id'] : '';
                $bookingpress_payment_details = $wpdb->get_row($wpdb->prepare("SELECT bookingpress_payment_gateway FROM {$tbl_bookingpress_payment_logs} WHERE bookingpress_payment_log_id   = %d", $bookingpress_package_payment_id), ARRAY_A);//phpcs:ignore
                if(!empty($bookingpress_payment_details['bookingpress_payment_gateway'])){
                    $bookingpress_payment_method = $bookingpress_payment_details['bookingpress_payment_gateway'];
                    if(!empty($bookingpress_payment_method) && $bookingpress_payment_method != 'manual') {
                        $bookingpress_payment_method = $BookingPress->bookingpress_get_customize_settings($bookingpress_payment_method.'_text','package_booking_form');
                    }
                }
                $template_content = str_replace('%package_name%', $package_name, $template_content);
                $template_content = str_replace('%package_payment_amount%', $package_price, $template_content);
                $template_content = str_replace('%package_duration%', $package_duration, $template_content); 
                $template_content = str_replace('%package_status%', $bookingpress_package_status_label, $template_content); 
                $template_content = str_replace('%package_purchase_date%', $bookingpress_package_purchase_date, $template_content); 
                $template_content = str_replace('%package_expiry_date%', $bookingpress_package_expiration_date, $template_content); 
                $template_content = str_replace('%package_services_added%', $bookingpress_package_services_content, $template_content); 
                $template_content = str_replace('%package_booking_id%', $bookingpress_package_booking_no, $template_content); 
                $template_content = str_replace('%package_payment_method%',$bookingpress_payment_method,$template_content);
                /* Package related data */
            }
            return $template_content;
        }

        function bookingpress_get_package_order_notification($bookingpress_notification_receiver_type, $bookingpress_notification_name,$bookingpress_package_booking_id = 0){
            $bookingpress_notification_data = array();
            if(!empty($bookingpress_notification_receiver_type) && !empty($bookingpress_notification_name)){
                global $wpdb,$tbl_bookingpress_notifications,$BookingPress;
			    $bookingpress_notification_data = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$tbl_bookingpress_notifications} WHERE bookingpress_notification_receiver_type = %s AND bookingpress_notification_name = %s", $bookingpress_notification_receiver_type, $bookingpress_notification_name ), ARRAY_A ); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared --Reason: $tbl_bookingpress_notifications is a table name. false alarm
                $bookingpress_notification_data = apply_filters('bookingpress_modify_package_email_notification_data', $bookingpress_notification_data,$bookingpress_package_booking_id,$bookingpress_notification_receiver_type);
            }
            return $bookingpress_notification_data;
        }

        /**
         * Get customer details from specific customer id
         *
         * @param  mixed $customer_id
         * @return void
         */
        function get_customer_user_id( $customer_id ){
            global $wpdb, $tbl_bookingpress_customers;
            $customer_user_id = 0;
            if (! empty($customer_id) ) {
                // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- Reason: $tbl_bookingpress_customers is table name defined globally. False Positive alarm
                $customer_data = $wpdb->get_row($wpdb->prepare("SELECT bookingpress_wpuser_id FROM {$tbl_bookingpress_customers} WHERE bookingpress_customer_id =%d", $customer_id), ARRAY_A);
                $customer_user_id = (isset($customer_data['bookingpress_wpuser_id']))?$customer_data['bookingpress_wpuser_id']:0;
            }
            return $customer_user_id;
        }

		/**
		 * Function for insert customer fields
		 *
		 * @param  mixed $bookingpress_customer_id
		 * @param  mixed $appointment_field_data
		 * @return void
		 */
		function bookingpress_insert_customer_field_data( $bookingpress_customer_id, $appointment_field_data ) {
			global $BookingPress,$tbl_bookingpress_form_fields,$wpdb;
			$bookingpress_form_fields = $wpdb->get_results( $wpdb->prepare('SELECT * FROM ' . $tbl_bookingpress_form_fields . ' WHERE bookingpress_is_customer_field = %d ', 0 ), ARRAY_A ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared --Reason: $tbl_bookingpress_form_fields is table name defined globally. False Positive alarm          
			$bookingpress_field_list  = array();

			foreach ( $bookingpress_form_fields as $bookingpress_form_field_key => $bookingpress_form_field_val ) {
				$bookingpress_field_options = ! empty( $bookingpress_form_field_val['bookingpress_field_options'] ) ? json_decode( $bookingpress_form_field_val['bookingpress_field_options'], true ) : '';
				$bookingpress_default_field = array( 'customer_firstname', 'customer_lastname', 'customer_phone', 'customer_phone_country', 'appointment_note' );

				$bookingpress_update_customer_meta = ( ( ! empty( $bookingpress_field_options['used_for_user_information'] ) && $bookingpress_field_options['used_for_user_information'] == 'true' ) || ( ! empty( $bookingpress_field_options['is_customer_field'] ) && $bookingpress_field_options['is_customer_field'] == 'true' ) );

				if ( $bookingpress_update_customer_meta && ( $bookingpress_form_field_val['bookingpress_field_is_default'] != '1' || $bookingpress_form_field_val['bookingpress_field_is_default'] == '1' && $bookingpress_form_field_val['bookingpress_form_field_name'] == 'fullname' ) ) {

					if ( $bookingpress_form_field_val['bookingpress_field_is_default'] == '1' && $bookingpress_form_field_val['bookingpress_form_field_name'] == 'fullname' ) {
						$bookingpress_field_list[] = 'customer_fullname';
					} else {						
						$bookingpress_field_list[] = $bookingpress_form_field_val['bookingpress_field_meta_key'];
					}
				}
			}            

			if ( ! empty( $appointment_field_data ) && ! empty( $bookingpress_customer_id ) ) {
				foreach ( $appointment_field_data as $key => $value ) {
					if ( in_array( $key, $bookingpress_field_list ) ) {
						$field_update[] = $key;
						$BookingPress->update_bookingpress_customersmeta( $bookingpress_customer_id, $key, $value );
					}
				}
			}
		}

        /**
         * Function for save package order
         *
         * @return void
         */
        function bookingpress_save_package_order_booking_func(){

            global $wpdb, $bookingpress_package, $BookingPress, $tbl_bookingpress_entries, $bookingpress_payment_gateways, $tbl_bookingpress_payment_logs, $tbl_bookingpress_appointment_bookings, $bookingpress_email_notifications,$bookingpress_debug_payment_log_id, $bookingpress_other_debug_log_id,$tbl_bookingpress_package_bookings,$tbl_bookingpress_package_bookings_meta;

            $response              = array();
            if( !empty( $_POST['package_data'] ) && !is_array( $_POST['package_data'] ) ){ //phpcs:ignore
				$_POST['package_data'] = json_decode( stripslashes_deep( $_POST['package_data'] ), true ); //phpcs:ignore
				$_REQUEST['package_data'] = $_POST['package_data'] =  !empty($_POST['package_data']) ? array_map(array($this,'bookingpress_boolean_type_cast'), $_POST['package_data'] ) : array(); //phpcs:ignore
			}

            $bpa_check_authorization = $this->bpa_check_authentication( 'save_package_order_details', true, 'bpa_wp_nonce' );            
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

            
            do_action( 'bookingpress_other_debug_log_entry', 'package_order_debug_logs', 'Backend add/update package order posted data', 'bookingpress_package_order', $_POST, $bookingpress_other_debug_log_id ); // phpcs:ignore WordPress.Security.NonceVerification
            
            // phpcs:ignore WordPress.Security.NonceVerification
            if (!empty($_REQUEST) && !empty($_REQUEST['package_data']) ) {

                $bookingpress_package_data = ! empty($_REQUEST['package_data']) ? array_map(array( $BookingPress, 'appointment_sanatize_field' ), $_REQUEST['package_data']) : array(); // phpcs:ignore

                $bookingpress_package_selected_customer    = (isset($bookingpress_package_data['package_selected_customer']) && !empty($bookingpress_package_data['package_selected_customer'])) ? sanitize_text_field($bookingpress_package_data['package_selected_customer']) : '';
                $bookingpress_package_selected_package    = (isset($bookingpress_package_data['package_selected_package']) && !empty($bookingpress_package_data['package_selected_package'])) ? sanitize_text_field($bookingpress_package_data['package_selected_package']) : '';
                $package_price_without_currency    = (isset($bookingpress_package_data['package_price_without_currency']) && !empty($bookingpress_package_data['package_price_without_currency'])) ? sanitize_text_field($bookingpress_package_data['package_price_without_currency']) : 0;                
                $package_update_id    = (isset($bookingpress_package_data['package_update_id']) && !empty($bookingpress_package_data['package_update_id'])) ? sanitize_text_field($bookingpress_package_data['package_update_id']) : '';                
                $bookingpress_package_internal_note        = (isset($bookingpress_package_data['package_internal_note']) && !empty($bookingpress_package_data['package_internal_note'])) ? sanitize_text_field($bookingpress_package_data['package_internal_note']) : '';
                $bookingpress_package_is_send_notification = (isset($bookingpress_package_data['package_send_notification']) && !empty($bookingpress_package_data['package_send_notification'])) ? sanitize_text_field($bookingpress_package_data['package_send_notification']) : '';
                if($bookingpress_package_is_send_notification == 'true') {
                    $bookingpress_package_is_send_notification=0;
                }
                else { 
                    $bookingpress_package_is_send_notification=1;
                }
                if (!empty($bookingpress_package_selected_customer) && !empty($bookingpress_package_selected_package)) {  
                                        
                    $package_data = $bookingpress_package->get_package_by_id($bookingpress_package_selected_package);
                    $bookingpress_package_customer_purchase_limit = (isset($package_data['bookingpress_package_customer_purchase_limit']))?$package_data['bookingpress_package_customer_purchase_limit']:0;
                    if($bookingpress_package_customer_purchase_limit > 0){

                        $get_customer_total_purchase_package = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM {$tbl_bookingpress_package_bookings} WHERE bookingpress_package_id = %d AND bookingpress_customer_id = %d", $bookingpress_package_selected_package, $bookingpress_package_selected_customer ) ); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared --Reason: $tbl_bookingpress_package_bookings is a table name. false alarm
                        if($get_customer_total_purchase_package >= $bookingpress_package_customer_purchase_limit){
                           
                            $response['variant'] = 'error';
                            $response['title']   = esc_html__('Error', 'bookingpress-package');
                            $response['msg'] = esc_html__('The package purchase limit has been exceeded.', 'bookingpress-package');
                            echo wp_json_encode($response);
                            exit();

                        }
                    }
                    $bookingpress_selected_package_detail_arr = $package_data;
                    if(!empty($package_data)){
                        unset($package_data['bookingpress_package_created_date']);
                        unset($package_data['bookingpress_package_position']);
                        $package_data  = json_encode($package_data);                        
                    }

                    $customer_data = $BookingPress->get_customer_details($bookingpress_package_selected_customer);
                    $customer_username = ! empty($customer_data['bookingpress_user_name']) ? ($customer_data['bookingpress_user_name']) : '';
                    if( !empty($customer_data['bookingpress_user_firstname']) || !empty($customer_data['bookingpress_user_lastname'])){
                        $customer_username = $customer_data['bookingpress_user_firstname'].' '.$customer_data['bookingpress_user_lastname'];
                    }
                    if( !empty($customer_data['bookingpress_customer_full_name'])){
                        $customer_username = $customer_data['bookingpress_customer_full_name'];
                    }                    
                    $customer_phone    = ! empty($customer_data['bookingpress_user_phone']) ? esc_html($customer_data['bookingpress_user_phone']) : '';
                    $customer_firstname = ! empty($customer_data['bookingpress_user_firstname']) ? ($customer_data['bookingpress_user_firstname']) : '';
                    $customer_lastname  = ! empty($customer_data['bookingpress_user_lastname']) ? ($customer_data['bookingpress_user_lastname']) : '';
                    $customer_country = ! empty($customer_data['bookingpress_user_country_phone']) ? esc_html($customer_data['bookingpress_user_country_phone']) : '';
                    $customer_dial_code = !empty($customer_data['bookingpress_user_country_dial_code']) ? esc_html($customer_data['bookingpress_user_country_dial_code']) : '';
                    $customer_email   = ! empty($customer_data['bookingpress_user_email']) ? ($customer_data['bookingpress_user_email']) : '';
                    

                    $bookingpress_update_id = ! empty($bookingpress_package_data['package_update_id']) ? $bookingpress_package_data['package_update_id'] : '';                    
                    $bookingpress_currency_name = $BookingPress->bookingpress_get_settings('payment_default_currency', 'payment_setting');

                    $bookingpress_tax_percentage = !empty($bookingpress_package_data['tax_percentage']) ? floatval($bookingpress_package_data['tax_percentage']) : 0;
                    $bookingpress_tax_amount = !empty($bookingpress_package_data['tax']) ? $bookingpress_package_data['tax'] : 0;
                    $bookingpress_tax_price_display_options = !empty($bookingpress_package_data['tax_price_display_options']) ? $bookingpress_package_data['tax_price_display_options'] : 'exclude_taxes';
                    $bookingpress_tax_order_summary = (!empty($bookingpress_package_data['display_tax_order_summary']) && $bookingpress_package_data['display_tax_order_summary'] == 'true') ? 1 : 0;
                    $bookingpress_included_tax_label = !empty($bookingpress_package_data['included_tax_label']) ? $bookingpress_package_data['included_tax_label'] : '';
                    $customer_dst_timezone = !empty( $bookingpress_package_data['client_dst_timezone'] ) ? intval( $bookingpress_package_data['client_dst_timezone'] ) : 0;

                    $customer_timezone = isset($bookingpress_package_data['bookingpress_customer_timezone']) ? $bookingpress_package_data['bookingpress_customer_timezone'] : wp_timezone_string();

                    $customer_dst_timezone = isset( $bookingpress_appointment_data['client_dst_timezone'] ) ? intval( $bookingpress_appointment_data['client_dst_timezone'] ) : 0;   

                    if(!empty($bookingpress_update_id)){

                        $package_order_details = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$tbl_bookingpress_package_bookings} WHERE bookingpress_package_booking_id = %d", $bookingpress_update_id), ARRAY_A); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- Reason: $tbl_bookingpress_package_bookings is table name defined globally. False Positive alarm

                        // get existing appointment data
                        do_action( 'bookingpress_other_debug_log_entry', 'package_order_debug_logs', 'Backend get existing package order data', 'bookingpress_package_order', $package_order_details, $bookingpress_other_debug_log_id ); 
                        if (!empty($package_order_details)){


                            $package_services_data = $bookingpress_package->get_package_services_by_package_id( $bookingpress_package_selected_package );
                            if(is_array($package_services_data)){
                                $package_services_data = json_encode($package_services_data);
                            }

                            $bookingpress_package_expiration_date = $this->get_package_expiration_date($bookingpress_selected_package_detail_arr['bookingpress_package_id'],$bookingpress_selected_package_detail_arr['bookingpress_package_duration'],$bookingpress_selected_package_detail_arr['bookingpress_package_duration_unit'],$package_order_details['bookingpress_package_purchase_date']);

                            $package_order_details['bookingpress_customer_id']                   = $bookingpress_package_selected_customer;
                            $package_order_details['bookingpress_package_id']                    = $bookingpress_package_selected_package;
                            $package_order_details['bookingpress_customer_name']                 = $customer_username;
                            $package_order_details['bookingpress_customer_phone']                = $customer_phone;
                            $package_order_details['bookingpress_customer_firstname']            = $customer_firstname;
                            $package_order_details['bookingpress_customer_lastname']             = $customer_lastname;
                            $package_order_details['bookingpress_customer_country']              = $customer_country;
                            $package_order_details['bookingpress_customer_email']                = $customer_email;
                            $package_order_details['bookingpress_customer_phone_dial_code']      = $customer_dial_code;
                            $package_order_details['bookingpress_username']                      = $customer_username;

                            $bookingpress_login_user_id                             = $this->get_customer_user_id( $bookingpress_package_selected_customer );
                            $package_order_details['bookingpress_login_user_id']    = $bookingpress_login_user_id;

                            if($package_order_details['bookingpress_package_id'] != $bookingpress_package_selected_package){
                                
                                $package_order_details['bookingpress_package_id']                    = $bookingpress_selected_package_detail_arr['bookingpress_package_id'];
                                $package_order_details['bookingpress_package_name']                  = $bookingpress_selected_package_detail_arr['bookingpress_package_name'];
                                $package_order_details['bookingpress_package_description']           = $bookingpress_selected_package_detail_arr['bookingpress_package_description'];
                                $package_order_details['bookingpress_package_price']                 = $bookingpress_selected_package_detail_arr['bookingpress_package_price'];
                                $package_order_details['bookingpress_package_calculated_price']      = $bookingpress_selected_package_detail_arr['bookingpress_package_calculated_price'];
                                $package_order_details['bookingpress_package_duration']              = $bookingpress_selected_package_detail_arr['bookingpress_package_duration'];
                                $package_order_details['bookingpress_package_duration_unit']         = $bookingpress_selected_package_detail_arr['bookingpress_package_duration_unit'];
                                $package_order_details['bookingpress_package_customer_purchase_limit'] = $bookingpress_selected_package_detail_arr['bookingpress_package_customer_purchase_limit'];
                                $package_order_details['bookingpress_package_status']                = $bookingpress_selected_package_detail_arr['bookingpress_package_status'];                                                                                    
                                $package_order_details['bookingpress_package_expiration_date']       = $bookingpress_package_expiration_date;
                                $package_order_details['bookingpress_package_services']              = $package_services_data;
    
                            }

                            $package_order_details['bookingpress_package_currency']              = $bookingpress_currency_name;
                            $package_order_details['bookingpress_package_internal_note']         = $bookingpress_package_internal_note;
                            $package_order_details['bookingpress_package_send_notification']     = $bookingpress_package_is_send_notification;                            
                            $package_order_details['bookingpress_tax_amount']                    = $bookingpress_package_data['tax'];
                            $bookingpress_tip_amount = isset($bookingpress_package_data['tip_amount']) ? intval($bookingpress_package_data['tip_amount']) : '';
                            if(isset($bookingpress_package_data['tip_amount'])){
                                $package_order_details['bookingpress_tip_amount'] = $bookingpress_tip_amount;
                            }
                            
                            $bookingpress_paid_amount = $bookingpress_due_amount = (isset($bookingpress_package_data['total_amount']))?$bookingpress_package_data['total_amount']:0;
                            $bookingpress_total_amount = $bookingpress_package_data['total_amount'];
                            $package_order_details['bookingpress_package_paid_amount']  = $bookingpress_paid_amount;
                            $package_order_details['bookingpress_package_due_amount']   = $bookingpress_total_amount;
                            $package_order_details['bookingpress_package_total_amount'] = $bookingpress_total_amount;
                            
                            if($bookingpress_package_data['complete_payment_url_selection'] == "mark_as_paid"){
                                $package_order_details['bookingpress_mark_as_paid'] = 1;
                            }else{
                                $package_order_details['bookingpress_mark_as_paid'] = 0;
                            }

                            $package_order_details['bookingpress_complete_payment_url_selection'] = $bookingpress_package_data['complete_payment_url_selection'];
                            $tmp_var = !empty($bookingpress_package_data['complete_payment_url_selected_method']) ? implode(',', $bookingpress_package_data['complete_payment_url_selected_method']) : '';
                            $package_order_details['bookingpress_complete_payment_url_selection_method'] = $tmp_var;
                            if($bookingpress_package_data['complete_payment_url_selection'] == "send_payment_link"){
                                $package_order_details['bookingpress_complete_payment_token'] = uniqid("bpa", true);
                            }                            
                            
                            $package_order_details = apply_filters('bookingpress_modify_package_order_edit_booking_fields', $package_order_details, $bookingpress_package_data);

                            do_action( 'bookingpress_other_debug_log_entry', 'package_order_debug_logs', 'Backend modified existing package order data', 'bookingpress_package_order', $package_order_details, $bookingpress_other_debug_log_id ); 

                            $wpdb->update($tbl_bookingpress_package_bookings, $package_order_details,array('bookingpress_package_booking_id' => $bookingpress_update_id));

                            $package_payment_data_update = array(
                                'bookingpress_package_id'  => $package_order_details['bookingpress_package_id'],

                                'bookingpress_customer_id'  => $package_order_details['bookingpress_customer_id'],
                                'bookingpress_customer_name'  => $package_order_details['bookingpress_customer_name'],
                                'bookingpress_username'  => $package_order_details['bookingpress_username'],
                                'bookingpress_customer_firstname'  => $package_order_details['bookingpress_customer_firstname'],
                                'bookingpress_customer_lastname'  => $package_order_details['bookingpress_customer_lastname'],
                                'bookingpress_customer_phone'  => $package_order_details['bookingpress_customer_phone'],
                                'bookingpress_customer_country'  => $package_order_details['bookingpress_customer_country'],
                                'bookingpress_customer_phone_dial_code'  => $package_order_details['bookingpress_customer_phone_dial_code'],
                                'bookingpress_customer_email'  => $package_order_details['bookingpress_customer_email'],

                                'bookingpress_payment_currency'  => $package_order_details['bookingpress_package_currency'],
                                'bookingpress_package_id'  => $package_order_details['bookingpress_package_id'],

                                'bookingpress_package_name'            => $package_order_details['bookingpress_package_name'],
                                'bookingpress_package_price'           => $package_order_details['bookingpress_package_price'],

                                'bookingpress_tax_amount'  => $package_order_details['bookingpress_tax_amount'],
                                'bookingpress_tip_amount'  => $package_order_details['bookingpress_tip_amount'],
                                'bookingpress_paid_amount' => $package_order_details['bookingpress_package_paid_amount'],
                                'bookingpress_due_amount'  => $package_order_details['bookingpress_package_due_amount'],
                                'bookingpress_total_amount'  => $package_order_details['bookingpress_package_total_amount'],
                            );                                                        

                            $wpdb->update($tbl_bookingpress_payment_logs, $package_payment_data_update, array('bookingpress_package_order_booking_ref' => $bookingpress_update_id));

                            $bookingpress_package_form_fields_data = array(
                                'form_fields' => !empty($bookingpress_package_data['bookingpress_package_meta_fields_value']) ? $bookingpress_package_data['bookingpress_package_meta_fields_value'] : array(),
                                'bookingpress_front_field_data' => !empty($bookingpress_package_data['bookingpress_package_meta_fields_value']) ? $bookingpress_package_data['bookingpress_package_meta_fields_value'] : array(),
                            );
                            
                            $get_form_fields_meta = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM {$tbl_bookingpress_package_bookings_meta} WHERE bookingpress_package_meta_key = %s AND bookingpress_package_booking_id = %d", 'package_form_fields_data', $bookingpress_update_id ) ); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared --Reason: $tbl_bookingpress_appointment_meta is a table name. false alarm

                            if( 1 > $get_form_fields_meta ){
                                $bookingpress_db_fields = array(
                                    'bookingpress_entry_id' => $package_order_details['bookingpress_entry_id'],
                                    'bookingpress_package_booking_id' => $bookingpress_update_id,
                                    'bookingpress_package_meta_value' => wp_json_encode($bookingpress_package_form_fields_data),
                                    'bookingpress_package_meta_key' => 'package_form_fields_data',
                                );            
                                $wpdb->insert($tbl_bookingpress_package_bookings_meta, $bookingpress_db_fields);
                            } else {
                                $bookingpress_db_fields = array(
                                    'bookingpress_package_meta_value' => wp_json_encode($bookingpress_package_form_fields_data),
                                );                                
                                $wpdb->update($tbl_bookingpress_package_bookings_meta, $bookingpress_db_fields, array('bookingpress_package_booking_id' => $bookingpress_update_id, 'bookingpress_package_meta_key' => 'package_form_fields_data'));
                            }
                            
                            
                            do_action('bookingpress_after_update_package_order', $bookingpress_update_id);

                            $response['variant'] = 'success';
                            $response['title']   = esc_html__('Success', 'bookingpress-package');
                            $response['msg']     = esc_html__('Package order has been updated successfully.', 'bookingpress-package');                            

                        }

                    }else{                    

                     
                        $bookingpress_login_user_id       = $this->get_customer_user_id( $bookingpress_package_selected_customer );
                        $bookingpress_entry_details = array(
                            'bookingpress_package_id'      => $bookingpress_package_selected_package,
                            'bookingpress_customer_id'     => $bookingpress_package_selected_customer,
                            //'bookingpress_login_user_id'     => $bookingpress_login_user_id,
                            'bookingpress_customer_name'   => $customer_username,
                            'bookingpress_customer_phone'  => $customer_phone,
                            'bookingpress_customer_firstname'  => $customer_firstname,
                            'bookingpress_customer_lastname' => $customer_lastname,
                            'bookingpress_customer_country'  => $customer_country,
                            'bookingpress_customer_email'    => $customer_email,
                            'bookingpress_customer_timezone' => $customer_timezone,
                            'bookingpress_dst_timezone'		 => $customer_dst_timezone,                            
                            'bookingpress_customer_phone_dial_code' => $customer_dial_code,
                            'bookingpress_service_id'      => 0,
                            'bookingpress_service_name'    => '',
                            'bookingpress_service_price'   => 0,
                            'bookingpress_service_currency' => $bookingpress_currency_name,
                            'bookingpress_service_duration_val' => 0,
                            'bookingpress_service_duration_unit' => '',
                            'bookingpress_payment_gateway' => 'manual',
                            'bookingpress_appointment_date' => date('Y-m-d'),
                            'bookingpress_appointment_time' => '00:00:00',
                            'bookingpress_appointment_end_time' => '00:00:00',
                            'bookingpress_appointment_internal_note' => $bookingpress_package_internal_note,
                            'bookingpress_appointment_send_notifications' => $bookingpress_package_is_send_notification,
                            //'bookingpress_appointment_status' => $bookingpress_appointment_status,
                            'bookingpress_dst_timezone'	   => $customer_dst_timezone,

                            'bookingpress_package_details' => $package_data,
                            'bookingpress_purchase_type'   => 2,
                            'bookingpress_created_at'      => current_time('mysql'),
                        );


                        $package_services_data = $bookingpress_package->get_package_services_by_package_id( $bookingpress_package_selected_package );

                        $bookingpress_entry_details['bookingpress_tax_percentage'] = $bookingpress_tax_percentage;
                        $bookingpress_entry_details['bookingpress_tax_amount'] = $bookingpress_tax_amount;
                        $bookingpress_entry_details['bookingpress_price_display_setting'] = $bookingpress_tax_price_display_options;
                        $bookingpress_entry_details['bookingpress_display_tax_order_summary'] = $bookingpress_tax_order_summary;
                        $bookingpress_entry_details['bookingpress_included_tax_label'] = $bookingpress_included_tax_label;                        

                        $bookingpress_paid_amount = $bookingpress_due_amount = (isset($bookingpress_package_data['total_amount']))?$bookingpress_package_data['total_amount']:0;                        
                        $bookingpress_total_amount = $bookingpress_package_data['total_amount'];

                        if($bookingpress_package_data['complete_payment_url_selection'] == "mark_as_paid"){
                            $bookingpress_entry_details['bookingpress_mark_as_paid'] = 1;
                        }else{
                            $bookingpress_entry_details['bookingpress_mark_as_paid'] = 0;
                        }

                        $bookingpress_entry_details['bookingpress_paid_amount'] = $bookingpress_paid_amount;
                        $bookingpress_entry_details['bookingpress_due_amount'] = $bookingpress_due_amount;
                        $bookingpress_entry_details['bookingpress_total_amount'] = $bookingpress_total_amount;                        

                        $bookingpress_entry_details['bookingpress_complete_payment_url_selection'] = $bookingpress_package_data['complete_payment_url_selection'];
                        $tmp_var = !empty($bookingpress_package_data['complete_payment_url_selected_method']) ? implode(',', $bookingpress_package_data['complete_payment_url_selected_method']) : '';
                        $bookingpress_entry_details['bookingpress_complete_payment_url_selection_method'] = $tmp_var;
                        if($bookingpress_package_data['complete_payment_url_selection'] == "send_payment_link"){
                            $bookingpress_entry_details['bookingpress_complete_payment_token'] = uniqid("bpa", true);
                        }

                        $bookingpress_entry_details = apply_filters('bookingpress_modify_backend_add_package_order_entry_data', $bookingpress_entry_details, $bookingpress_package_data);

                        do_action( 'bookingpress_other_debug_log_entry', 'package_order_debug_logs', 'Backend add package order data', 'bookingpress_package_order', $bookingpress_entry_details, $bookingpress_other_debug_log_id ); 

                        $wpdb->insert($tbl_bookingpress_entries, $bookingpress_entry_details);
                        $entry_id       = $wpdb->insert_id;

                        /* Add Meta Custom Fields value when package order added */                       
                            $bookingpress_package_form_fields_data = array(
                                'form_fields' => !empty($bookingpress_package_data['bookingpress_package_meta_fields_value']) ? $bookingpress_package_data['bookingpress_package_meta_fields_value'] : array(),
                                'bookingpress_front_field_data' => !empty($bookingpress_package_data['bookingpress_package_meta_fields_value']) ? $bookingpress_package_data['bookingpress_package_meta_fields_value'] : array(),
                            );
                            $bookingpress_db_fields = array(
                                'bookingpress_entry_id' => $entry_id,
                                'bookingpress_package_booking_id' => 0,
                                'bookingpress_package_meta_value' => wp_json_encode($bookingpress_package_form_fields_data),
                                'bookingpress_package_meta_key' => 'package_form_fields_data',
                            );            
                            $wpdb->insert($tbl_bookingpress_package_bookings_meta, $bookingpress_db_fields);
                        /* Add meta custom fields value when package order added */

                        do_action('bookingpress_after_insert_package_order_entry_data_from_backend', $entry_id, $bookingpress_package_data);

                        $payment_log_id = 0;
                        if(!empty($entry_id)) {

                            $tbl_bookingpress_entries_meta = $wpdb->prefix . 'bookingpress_entries_meta';
                            $bookingpress_db_fields = array(
                                'bookingpress_entry_id' => $entry_id,                        
                                'bookingpress_entry_meta_key' => 'bookingpress_package_data',
                                'bookingpress_entry_meta_value' => $package_data,
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

                            global $bookingpress_pro_payment_gateways;
                            $payment_log_id = $bookingpress_pro_payment_gateways->bookingpress_confirm_booking($entry_id, array(), '1', '', '', 2);

                            if (!empty($payment_log_id)){
                                $response['variant'] = 'success';
                                $response['title']   = esc_html__('Success', 'bookingpress-package');
                                $response['msg']     = esc_html__('Package has been booked successfully.', 'bookingpress-package');
                            }

                        }

                    }
                }else{
                    $response['msg'] = esc_html__('Please fill all required values', 'bookingpress-package');
                }

            }

            echo wp_json_encode($response);
            exit();
        }
        
        /**
         * Function for get package list
         *
         * @return void
         */
        function bookingpress_get_package_list(){
            global $wpdb,$tbl_bookingpress_packages,$BookingPress;

            $packages_data = $wpdb->get_results("SELECT * FROM {$tbl_bookingpress_packages} WHERE bookingpress_package_status = 1 ORDER BY bookingpress_package_position ASC ", ARRAY_A); //phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- Reason: $tbl_bookingpress_packages is table name defined globally. False Positive alarm
            $package_list = array();
            if(!empty($packages_data) && is_array($packages_data) ){                
                foreach($packages_data as $package){
                    $package['formatted_package_price']   = $BookingPress->bookingpress_price_formatter_with_currency_symbol($package['bookingpress_package_price']);
                    $package['formatted_retail_price']    = $BookingPress->bookingpress_price_formatter_with_currency_symbol($package['bookingpress_package_calculated_price']);
                    $bookingpress_package_id = $package['bookingpress_package_id'];                                        
                    $package_list[] = $package;
                }
            }

            return $package_list;
        }
        
        /**
         * Function for Add tel js in package order page
         *
         * @param  mixed $bookingpress_allowed_tel_input_script
         * @param  mixed $page_request
         * @return void
         */
        function bookingpress_allowed_tel_input_script_backend_func($bookingpress_allowed_tel_input_script,$page_request){
            if($page_request == 'bookingpress_package_order'){
                $bookingpress_allowed_tel_input_script = true;
            }
            return $bookingpress_allowed_tel_input_script;
        }
        
        /**
         * Function for add package date filter picker option
         *
         * @param  mixed $bookingpress_allowed_disable_date_filter_pickeroptions
         * @param  mixed $requested_module
         * @return void
         */
        function bookingpress_allowed_disable_date_filter_pickeroptions_func($bookingpress_allowed_disable_date_filter_pickeroptions,$requested_module){
            if($requested_module == 'package_order'){
                $bookingpress_allowed_disable_date_filter_pickeroptions = true;
            }
            return $bookingpress_allowed_disable_date_filter_pickeroptions;
        }


        /**
         * Function for add package vue data
         *
         * @return void
         */
        function bookingpress_package_order_vue_data_fields_func(){

            global $bookingpress_package,$bookingpress_package_order_vue_data_fields, $bookingpress_global_options,$BookingPress;

            $bookingpress_options     = $bookingpress_global_options->bookingpress_global_options();
            $bookingpress_locale_lang = $bookingpress_options['locale'];
            $bookingpress_pagination  = $bookingpress_options['pagination'];

            $bookingpress_pagination_arr      = json_decode($bookingpress_pagination, true);
            $bookingpress_pagination_selected = $bookingpress_pagination_arr[0];

            $bookingpress_package_status_array = $bookingpress_package->get_package_order_status();
            $bookingpress_phone_country_option = $BookingPress->bookingpress_get_settings('default_phone_country_code', 'general_setting');
            $bookingpress_package_order_vue_data_fields = array(
                'bulk_action'                => 'bulk_action',
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
                'items'                      => array(),
                'multipleSelection'          => array(),
                'package_customers_list' => array(),
                'package_services_list'  => array(),
                'package_list'           => array(),
                'perPage'                    => $bookingpress_pagination_selected,
                'totalItems'                 => 0,
                'pagination_selected_length' => $bookingpress_pagination_selected,
                'pagination_length'          => $bookingpress_pagination,
                'currentPage'                => 1,
                'search_package'             => '',
                'search_package_id'         => '',
                'package_date_range'     => array( date('Y-m-d', strtotime('-3 Day')), date('Y-m-d', strtotime('+3 Day')) ),
                'search_customer_name'       => '',
                'search_package_name'        => '',
                'search_service_employee'    => '',
                'search_package_status'  => '',
                'search_customer_list'       => '',
                'search_status'              => $bookingpress_package_status_array,
                'package_time_slot'      => array(),
                'package_status'         => $bookingpress_package_status_array,
                'service_employee'           => array(),
                'package_services_data'  => array(),
                'modal_loader'               => 1,
                'rules'                      => array(
                    'package_selected_customer' => array(
                        array(
                            'required' => true,
                            'message'  => __('Please select customer', 'bookingpress-package'),
                            'trigger'  => 'change',
                        ),
                    ),
                    'package_selected_package'  => array(
                        array(
                            'required' => true,
                            'message'  => __('Please select package', 'bookingpress-package'),
                            'trigger'  => 'change',
                        ),
                    ),
                ),
                'package_formdata'       => array(
                    'package_selected_customer'     => '',
                    //'package_selected_staff_member' => '',
                    'package_selected_package'       => '',
                    'package_selected_service'       => '',
                    'package_price_without_currency' => 0, 
                    //'package_booked_date'           => date('Y-m-d', current_time('timestamp')),
                    //'package_booked_time'           => '',
                    'package_booked_end_time'       => '',
                    'package_internal_note'         => '',
                    'package_send_notification'     => false,
                    'package_status'                => '1',
                    'package_update_id'             => 0,
                ),
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
                'savebtnloading'             => false,
                'open_package_modal'     => false,
                'is_display_loader'          => '0',
                'is_disabled'                => false,
                'is_display_save_loader'     => '0',
                'update_package_expiration_date_modal' => false,
                'is_display_package_expiration_loader'          => '0',
                'package_expiration_current_row' => '',
                'package_expiration_current_index' => '',
                'package_updated_expiration_date' => '',

                
            );

            $bookingpress_package_order_vue_data_fields['customer'] = array(
                'avatar_url' => '',
                'avatar_name' => '',
                'avatar_list' => array(),
                'wp_user' => null,
                'firstname' => '',
                'lastname' => '',
                'email' => '',
                'phone' => '',
                'customer_phone_country' => $bookingpress_phone_country_option,
                'customer_phone_dial_code' => '',
                'note' => '',
                'update_id' => 0,
                '_wpnonce' => '',
                'password' => '',
            );            

        }

        function bookingpress_package_order_dynamic_helper_vars_func(){
            global $bookingpress_global_options;
			$bookingpress_options     = $bookingpress_global_options->bookingpress_global_options();
			$bookingpress_locale_lang = $bookingpress_options['locale'];
			?>
				var lang = ELEMENT.lang.<?php echo esc_html( $bookingpress_locale_lang ); ?>;
				ELEMENT.locale(lang)
			<?php
        }
        
        /**
         * Function for add package dynamic vue data
         *
         * @return void
         */
        function bookingpress_package_order_dynamic_data_fields_func(){
            global $bookingpress_global_options,$tbl_bookingpress_form_fields,$BookingPressPro,$wpdb, $bookingpress_package, $bookingpress_package_order_vue_data_fields, $BookingPress, $tbl_bookingpress_services, $bookingpress_pro_services, $tbl_bookingpress_staffmembers, $tbl_bookingpress_staffmembers_services, $bookingpress_services,$tbl_bookingpress_customers, $tbl_bookingpress_categories, $bookingpress_tax;

            $bookingpress_global_details     = $bookingpress_global_options->bookingpress_global_options();
            $bookingpress_edit_customers = 0;
            if ( $BookingPressPro->bookingpress_check_capability( 'bookingpress_edit_customers' ) ) {
				$bookingpress_edit_customers = 1;
			}

			$bookingpress_package_order_vue_data_fields['open_customer_modal'] = false;
			$bookingpress_options = $bookingpress_global_options->bookingpress_global_options();
			$bookingpress_country_list = $bookingpress_options['country_lists'];
			$bookingpress_phone_country_option = $BookingPress->bookingpress_get_settings('default_phone_country_code', 'general_setting');
			            

            $bookingpress_package_order_vue_data_fields['package_list'] = $this->bookingpress_get_package_list();

			/* Get custom fields start */
			$bookingpress_form_fields = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$tbl_bookingpress_form_fields} WHERE bookingpress_field_is_default = %d AND bookingpress_is_customer_field = %d AND bookingpress_field_is_package_hide = %d ORDER BY bookingpress_field_position ASC", 0, 0, 0 ), ARRAY_A); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared --Reason: $tbl_bookingpress_form_fields is table name defined globally. False Positive alarm

			$bookingpress_listing_fields_value = $bookingpress_appointment_meta_fields_value = array();
			if(!empty($bookingpress_form_fields)){
				foreach($bookingpress_form_fields as $k3 => $v3){
					$bookingpress_form_fields[$k3]['bookingpress_field_error_message']= stripslashes_deep($v3['bookingpress_field_error_message']);
					$bookingpress_form_fields[$k3]['bookingpress_field_label'] = stripslashes_deep($v3['bookingpress_field_label']);
					$bookingpress_form_fields[$k3]['bookingpress_field_placeholder'] = stripslashes_deep($v3['bookingpress_field_placeholder']);
					$bookingpress_field_meta_key = $v3['bookingpress_field_meta_key'];
					$bookingpress_field_options = (isset($v3['bookingpress_field_options']))?json_decode($v3['bookingpress_field_options'], TRUE):array();
					$bookingpress_form_fields[$k3]['bookingpress_field_options'] = $bookingpress_field_options;
					if($v3['bookingpress_field_type'] == "checkbox"){
						$bookingpress_field_values = json_decode($v3['bookingpress_field_values'], TRUE);


						$temp_form_fields_data = array();
						$fmeta_key = $bookingpress_field_meta_key;

						foreach( $bookingpress_field_values as $k4 => $v4 ){
							$bookingpress_form_fields[$k3][ $fmeta_key] [ $k4 ] = '';	
						}

						$bookingpress_appointment_meta_fields_value[$fmeta_key] = array();
						
						$bookingpress_form_fields[$k3]['selected_services'] = (isset($bookingpress_field_options['selected_services']))?$bookingpress_field_options['selected_services']:'';
					}else{
						$bookingpress_form_fields[$k3]['selected_services'] = (isset($bookingpress_field_options['selected_services']))?$bookingpress_field_options['selected_services']:'';
						if( $v3['bookingpress_field_type'] != 'password'){
							
							$bookingpress_appointment_meta_fields_value[$bookingpress_field_meta_key] = '';
							$bookingpress_listing_fields_value[$bookingpress_field_meta_key] = array(
								'label' => $v3['bookingpress_field_label'],
								'value' => '',
							);
						}
					}
				}
			}
            $bookingpress_repeater_inner_field_ids = array();
			if(!empty($bookingpress_form_fields)){
				foreach($bookingpress_form_fields as $k4 => $v4){
					if(($v4['bookingpress_form_field_name'] == "Repeater") || ($v4['bookingpress_form_field_name'] == "2 Col") || ($v4['bookingpress_form_field_name'] == "3 Col") || ($v4['bookingpress_form_field_name'] == "4 Col") ){
                        if(isset($v4['bookingpress_field_options']) && !empty($v4['bookingpress_field_options']) && $v4['bookingpress_form_field_name'] == "Repeater"){
							$inner_fields = isset($v4['bookingpress_field_options']['inner_fields']) ? $v4['bookingpress_field_options']['inner_fields'] : array();
							if(!empty($inner_fields)){
								foreach($inner_fields as $inner_field_key => $inner_field_val){
									$id = (isset($inner_field_val['id']))?$inner_field_val['id']:'';
									$id = str_replace( 'inner_field_', '', $id);
									$bookingpress_repeater_inner_field_ids[] = $id; 									
								}
							}
							
						}
						unset($bookingpress_form_fields[$k4]);
					}
				}

				$bookingpress_form_fields = array_values($bookingpress_form_fields);
			}

			if( !empty( $bookingpress_form_fields ) ) {
				$bookingpress_temp_form_fields = [];
				$n5 = 0;
				foreach( $bookingpress_form_fields as $k5 => $v5 ){

					if( 'file' == $v5['bookingpress_field_type'] ){
						$action_url = admin_url('admin-ajax.php');
						$action_data = array(
							'action' => 'bpa_front_file_upload',
							'_wpnonce' => wp_create_nonce( 'bpa_file_upload_' . $v5['bookingpress_field_meta_key'] ),
							'field_key' => $v5['bookingpress_field_meta_key']
						);
						$v5['bpa_action_url'] = $action_url;
						$v5['bpa_ref_name'] = str_replace('_', '', $v5['bookingpress_field_meta_key']);
						$action_data['bpa_ref'] =$v5['bpa_ref_name'];
						$v5['bpa_file_list'] = array();
						$v5['bpa_action_data'] = $action_data;
						$action_data['bpa_accept_files'] = !empty( $v5['bookingpress_field_options']['allowed_file_ext'] ) ?  base64_encode( $v5['bookingpress_field_options']['allowed_file_ext'] ) : '';
					}

                    $v5['is_repeater_field_inner_field'] = false;
					if(!empty($bookingpress_repeater_inner_field_ids)) {
						if(in_array($v5['bookingpress_form_field_id'], $bookingpress_repeater_inner_field_ids)) {
							$v5['is_repeater_field_inner_field'] = true;
						}
					}

					if( ( ( $n5 + 1 ) % 3 ) == 0 ){
						$v5['is_separator'] = false;
						$bookingpress_temp_form_fields[] = $v5;
						$bookingpress_temp_form_fields[] = array(
							'is_separator' => true
						);
					} else {
						$v5['is_separator'] = false;
						$bookingpress_temp_form_fields[] = $v5;
					}
					$n5++;
				}
				$bookingpress_form_fields = $bookingpress_temp_form_fields;
			}
			
            $bookingpress_package_order_vue_data_fields['package_date_pickerOptions'] = array('firstDayOfWeek'=>intval(esc_html($bookingpress_global_details['start_of_week'])));
            $bookingpress_package_order_vue_data_fields['package_date_pickerOptions_org'] = array('firstDayOfWeek'=>intval(esc_html($bookingpress_global_details['start_of_week'])));            
            $bookingpress_package_order_vue_data_fields['package_date_disable_dates'] = array();

            $bookingpress_package_order_vue_data_fields['package_expiry_date_update_form']['package_updated_expiration_date'] = '';
            $bookingpress_package_order_vue_data_fields['package_expiry_date_update_form']['bookingpress_package_booking_id'] = '';
            $bookingpress_package_order_vue_data_fields['rules_package_expiry_date_update_form'] = array(
				'package_updated_expiration_date' => array(
					array(
						'required' => true,
						'message'  => esc_html__('Please select date', 'bookingpress-package'),
						'trigger'  => 'blur',
					),
				),                
            );


			$bookingpress_package_order_vue_data_fields['bookingpress_form_fields'] = $bookingpress_form_fields;
			$bookingpress_package_order_vue_data_fields['package_formdata']['bookingpress_package_meta_fields_value'] = $bookingpress_appointment_meta_fields_value;
			$bookingpress_package_order_vue_data_fields['bookingpress_listing_fields_value'] = $bookingpress_listing_fields_value;

            /* Get custom field over  */

			$bpa_customer_form_fields = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM `{$tbl_bookingpress_form_fields}` WHERE bookingpress_is_customer_field = %d ORDER BY bookingpress_field_position ASC", 1 ), ARRAY_A ); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared --Reason: $tbl_bookingpress_form_fields is table name.
			
            $bpa_customer_fields = array();
            if( !empty( $bpa_customer_form_fields ) ){
                foreach( $bpa_customer_form_fields as $x => $cs_form_fields ){
                    //$bpa_customer_fields['field_id'] = $cs_form_fields['']   
                    $bpa_customer_fields[ $x ] = $cs_form_fields;
                    $bpa_customer_fields[ $x ]['bookingpress_field_values'] = json_decode( $cs_form_fields['bookingpress_field_values'], true );
                    $bpa_customer_fields[ $x ]['bookingpress_field_options'] = json_decode( $cs_form_fields['bookingpress_field_options'], true );
                    $bpa_customer_fields[ $x ]['bookingpress_field_key'] = '';//$cs_form_fields['bookingpress_field_meta_key'];
                    if( 'checkbox' == $cs_form_fields['bookingpress_field_type'] ){
                        $bpa_customer_fields[ $x ]['bookingpress_field_key'] = array();
                        foreach( $bpa_customer_fields[ $x ]['bookingpress_field_values'] as $chk_key => $chk_val ){
                            //$bpa_customer_fields[ $x ]['bookingpress_field_key'][ $chk_key ] = false;
							$bookingpress_appointment_vue_data_fields['customer']['bpa_customer_field'][ $cs_form_fields['bookingpress_field_meta_key'] . '_' . $chk_key ] = false;
                        }
                    } else {
						$bookingpress_package_order_vue_data_fields['customer']['bpa_customer_field'][$cs_form_fields['bookingpress_field_meta_key']] = $bpa_customer_fields[ $x ]['bookingpress_field_key'];
					}
                }
            }
            $bookingpress_package_order_vue_data_fields['bookingpress_customer_fields'] = $bpa_customer_fields;

			$bookingpress_custom_fields = $bookingpress_package_order_vue_data_fields['bookingpress_form_fields'];
			$bookingpress_custom_fields_validation_arr = array();
			if(!empty($bookingpress_custom_fields)){
				foreach($bookingpress_custom_fields as $custom_field_key => $custom_field_val){
					
					if(isset($custom_field_val['bookingpress_field_is_default']) && $custom_field_val['bookingpress_field_is_default'] == 0 ) {

						$bookingpress_field_meta_key = $custom_field_val['bookingpress_field_meta_key'];
						
						if(isset($custom_field_val['bookingpress_field_required']) && $custom_field_val['bookingpress_field_required'] == 1) {
							$bookingpress_field_err_msg = stripslashes_deep($custom_field_val['bookingpress_field_error_message']);						
							$bookingpress_field_err_msg = empty($bookingpress_field_err_msg) && !empty($custom_field_val['bookingpress_field_label']) ? stripslashes_deep($custom_field_val['bookingpress_field_label']).' '.__('is required','bookingpress-package') : $bookingpress_field_err_msg;
							$bookingpress_custom_fields_validation_arr[$bookingpress_field_meta_key][] = array(
								'required' => 1,
								'message' => $bookingpress_field_err_msg,
								'trigger' => 'change'
							);					
						}
											
						if(!empty($custom_field_val['bookingpress_field_options']['minimum'])) {
							$bookingpress_custom_fields_validation_arr[ $bookingpress_field_meta_key][] = array( 
								'min' => intval($custom_field_val['bookingpress_field_options']['minimum']),
								'message'  => __('Minimum','bookingpress-package').' '.$custom_field_val['bookingpress_field_options']['minimum'].' '.__('character required','bookingpress-package'),
								'trigger'  => 'blur',
							);
						}
						if(!empty($custom_field_val['bookingpress_field_options']['maximum'])) {
							$bookingpress_custom_fields_validation_arr[$bookingpress_field_meta_key][] = array( 
								'max' => intval($custom_field_val['bookingpress_field_options']['maximum']),
								'message'  => __('Maximum','bookingpress-package').' '.$custom_field_val['bookingpress_field_options']['maximum'].' '.__('character allowed','bookingpress-package'),
								'trigger'  => 'blur',
							);
						}
					}	
				}
				
			}

            $bookingpress_package_order_vue_data_fields['bookingpress_customer_fields'] = $bpa_customer_fields;

			$bookingpress_package_order_vue_data_fields['bookingpress_form_fields'] = $bookingpress_form_fields;

			
			$bookingpress_package_order_vue_data_fields['custom_field_rules'] = $bookingpress_custom_fields_validation_arr;

			$bookingpress_package_order_vue_data_fields['phone_countries_details'] = json_decode($bookingpress_country_list);
			$bookingpress_package_order_vue_data_fields['loading'] = false;

			$bookingpress_package_order_vue_data_fields['customer_detail_save'] = false;
			$bookingpress_package_order_vue_data_fields['wpUsersList'] = array();

            $bookingpress_package_order_vue_data_fields['bookingpress_edit_customers'] = $bookingpress_edit_customers;

			if ( $BookingPressPro->bookingpress_check_capability( 'bookingpress_payments' ) ) {
				$bookingpress_payments = 1;
			}

            $bookingpress_package_order_vue_data_fields['bookingpress_payments'] = $bookingpress_payments;            

           // Fetch customers details
           $bookingpress_customer_details           = $wpdb->get_results('SELECT bookingpress_customer_id, bookingpress_user_firstname, bookingpress_user_lastname, bookingpress_user_email FROM ' . $tbl_bookingpress_customers . ' WHERE bookingpress_user_type = 2 AND bookingpress_user_status = 1', ARRAY_A); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared -- Reason: $tbl_bookingpress_customers is table name defined globally. False Positive alarm
           $bookingpress_customer_selection_details = array();
           $bookingpress_customer_name              = '';
           foreach ( $bookingpress_customer_details as $bookingpress_customer_key => $bookingpress_customer_val ) {
               $bookingpress_customer_name = ( $bookingpress_customer_val['bookingpress_user_firstname'] == '' && $bookingpress_customer_val['bookingpress_user_lastname'] == '' ) ? $bookingpress_customer_val['bookingpress_user_email'] : $bookingpress_customer_val['bookingpress_user_firstname'] . ' ' . $bookingpress_customer_val['bookingpress_user_lastname'];

               $bookingpress_customer_selection_details[] = array(
               'text'  => stripslashes_deep($bookingpress_customer_name),
               'value' => $bookingpress_customer_val['bookingpress_customer_id'],
               );
           }


           // Fetch Services Details
           /*
           $bookingpress_services_details2   = array();
           $bookingpress_services_details2[] = array(
           'category_name'     => '',
           'category_services' => array(
           '0' => array(
                'service_id'    => 0,
                'service_name'  => __('Select service', 'bookingpress-package'),
                'service_price' => '',
           ),
           ),
           );           
           $bookingpress_services_details    = $BookingPress->get_bookingpress_service_data_group_with_category();
           $bookingpress_services_details2   = array_merge($bookingpress_services_details2, $bookingpress_services_details);
           $bookingpress_package_order_vue_data_fields['package_services_list'] = $bookingpress_services_details2;
           $bookingpress_package_order_vue_data_fields['package_services_data'] = $bookingpress_services_details;
           */
          
           $bookingpress_default_status_option = $BookingPress->bookingpress_get_settings('package_status', 'general_setting');
           $bookingpress_package_order_vue_data_fields['package_formdata']['package_status'] = ! empty($bookingpress_default_status_option) ? $bookingpress_default_status_option : '1';

           // Pagination data
           $bookingpress_default_perpage_option                               = $BookingPress->bookingpress_get_settings('per_page_item', 'general_setting');
           $bookingpress_package_order_vue_data_fields['perPage']               = ! empty($bookingpress_default_perpage_option) ? $bookingpress_default_perpage_option : '20';
           $bookingpress_package_order_vue_data_fields['pagination_length_val'] = ! empty($bookingpress_default_perpage_option) ? $bookingpress_default_perpage_option : '20';

           $default_daysoff_details = $BookingPress->bookingpress_get_default_dayoff_dates();
           if (! empty($default_daysoff_details) ) {
               $default_daysoff_details                                   = array_map(
                   function ( $date ) {
                       return date('Y-m-d', strtotime($date));
                   },
                   $default_daysoff_details
               );
               $bookingpress_package_order_vue_data_fields['disabledDates'] = $default_daysoff_details;
           } else {
               $bookingpress_package_order_vue_data_fields['disabledDates'] = '';
           }
           $bookingpress_package_order_vue_data_fields['bookingpress_loading'] = false;
           $bookingpress_package_order_vue_data_fields['customer_id'] = '';
           
           
           $bookingpress_package_order_vue_data_fields['package_formdata']['_wpnonce'] = wp_create_nonce('bpa_wp_nonce');

           $bookingpress_package_order_vue_data_fields['bookingpress_previous_row_obj'] = '';

           //Get default booking form shortcode page
           $bpa_default_booking_page = get_page_by_path('book-package');
           $bpa_default_booking_page_id = '';
           if(!empty($bpa_default_booking_page->ID)){
               $bpa_default_booking_page_id = $bpa_default_booking_page->ID;
           }
           $bpa_default_booking_page_url = get_permalink($bpa_default_booking_page_id);

           //Get all wp pages
           $bpa_new_wp_pages = array();
           $bpa_wp_pages = get_pages();
           if(!empty($bpa_wp_pages)){
               foreach($bpa_wp_pages as $bpa_wp_page_key => $bpa_wp_page_val){
                   $bpa_new_wp_pages[] = array(
                       'id' => $bpa_wp_page_val->ID,
                       'title' => $bpa_wp_page_val->post_title,
                       'url' => get_permalink(get_page_by_path($bpa_wp_page_val->post_name)),
                   );
               }
           }
           $bookingpress_package_order_vue_data_fields['all_share_pages'] = $bpa_new_wp_pages;
           $bookingpress_package_order_vue_data_fields['all_share_pages_list'] = array();

           $bookingpress_package_order_vue_data_fields['share_url_form'] = array(
               'selected_page_id' => $bpa_default_booking_page_id,
               'selected_page_wp_id' => '',
               'selected_service_id' => '',
               'generated_url' => $bpa_default_booking_page_url,
               'allow_customer_to_modify' => false,
               'email_sharing' => false,
               'sharing_email' => '',
           );

           $bookingpress_package_order_vue_data_fields['bpa_share_url_modal'] = false;
           $bookingpress_package_order_vue_data_fields['is_share_button_loader'] = '0';
           $bookingpress_package_order_vue_data_fields['is_share_button_disabled'] = true;
           $bookingpress_package_order_vue_data_fields['is_mask_display'] = false;

           $bookingpress_package_order_vue_data_fields['share_url_rules'] = array(
               'selected_service_id' => array(
                   array(
                       'required' => true,
                       'message'  => __('Please select service', 'bookingpress-package'),
                       'trigger'  => 'change',
                   ),
               ),
               'selected_page_wp_id' => array(
                   array(
                       'required' => true,
                       'message'  => __('Please select page', 'bookingpress-package'),
                       'trigger'  => 'change',
                   ),
               ),
               'sharing_email' => array(
                   array(
                       'required' => true,
                       'message'  => __('Please enter email address', 'bookingpress-package'),
                       'trigger'  => 'change',
                   ),
               ),
           );

           $bookingpress_package_order_vue_data_fields['customer_rules'] = array(
                'firstname' => array(
                    array(
                        'required' => true,
                        'message'  => esc_html__('Please enter firstname', 'bookingpress-package'),
                        'trigger'  => 'blur',
                    ),
                ),
                'lastname'  => array(
                    array(
                        'required' => true,
                        'message'  => esc_html__('Please enter lastname', 'bookingpress-package'),
                        'trigger'  => 'blur',
                    ),
                ),
                'email'     => array(
                    array(
                        'required' => true,
                        'message'  => esc_html__('Please enter email address', 'bookingpress-package'),
                        'trigger'  => 'blur',
                    ),
                    array(
                        'type'    => 'email',
                        'message' => esc_html__('Please enter valid email address', 'bookingpress-package'),
                        'trigger' => 'blur',
                    ),
                ),
            );           
			$bookingpress_package_order_vue_data_fields['bookingpress_tel_input_props'] = array(
				'defaultCountry' => $bookingpress_phone_country_option,
				'validCharactersOnly' => true,
				'inputOptions' => array(
					'placeholder' => '',
				)
			);            

			$bookingpress_package_order_vue_data_fields['cusShowFileList'] = false;
			$bookingpress_package_order_vue_data_fields['is_display_loader'] = '0';
			$bookingpress_package_order_vue_data_fields['is_disabled'] = false;
            
			$bookingpress_currency_separator = $BookingPress->bookingpress_get_settings('price_separator', 'payment_setting');
			$bookingpress_package_order_vue_data_fields['bookingpress_currency_separator'] = $bookingpress_currency_separator;			
			$bookingpress_decimal_points = $BookingPress->bookingpress_get_settings('price_number_of_decimals', 'payment_setting');
			$bookingpress_decimal_points = intval($bookingpress_decimal_points);
			$bookingpress_package_order_vue_data_fields['bookingpress_decimal_points'] = $bookingpress_decimal_points;

            $bookingpress_currency_name = $BookingPress->bookingpress_get_settings('payment_default_currency', 'payment_setting');
            $bookingpress_package_order_vue_data_fields['bookingpress_currency_name'] = $bookingpress_currency_name;			
            $bookingpress_package_order_vue_data_fields['bookingpress_currency_symbol'] = $BookingPress->bookingpress_get_currency_symbol($bookingpress_currency_name);

			$bookingpress_package_order_vue_data_fields['bookingpress_currency_name_org'] = $bookingpress_currency_name;

            $bookingpress_price_symbol_position = $BookingPress->bookingpress_get_settings('price_symbol_position', 'payment_setting');
            $bookingpress_package_order_vue_data_fields['bookingpress_currency_symbol_position'] = $bookingpress_price_symbol_position;
            

			$bookingpress_package_order_vue_data_fields['package_formdata']['subtotal'] = 0;
			$bookingpress_package_order_vue_data_fields['package_formdata']['subtotal_with_currency'] = $BookingPress->bookingpress_price_formatter_with_currency_symbol(0);

            $tax_percentage = $BookingPress->bookingpress_get_settings( 'tax_percentage', 'payment_setting' );			
            $bookingpress_package_order_vue_data_fields['package_formdata']['tax_percentage'] = $tax_percentage;

            $bookingpress_package_order_vue_data_fields['package_formdata']['enable_country_wise_tax'] = $BookingPress->bookingpress_get_settings('enable_country_wise_tax', 'payment_setting');

            $bookingpress_package_order_vue_data_fields['package_formdata']['countryselectedField'] = $BookingPress->bookingpress_get_settings('countryselectedField', 'payment_setting');  

            $bookingpress_package_order_vue_data_fields['package_formdata']['country_wise_tax_details'] = array();

            if ( class_exists('bookingpress_tax') && method_exists($bookingpress_tax, 'bookingpress_get_country_wise_tax_details')) {
                $bookingpress_package_order_vue_data_fields['package_formdata']['country_wise_tax_details'] = $bookingpress_tax->bookingpress_get_country_wise_tax_details();
            }

            $bookingpress_package_order_vue_data_fields['package_formdata']['tax'] = 0;
            $bookingpress_package_order_vue_data_fields['package_formdata']['tax_with_currency'] = $BookingPress->bookingpress_price_formatter_with_currency_symbol(0);
            $bookingpress_price_setting_display_option = $BookingPress->bookingpress_get_settings('price_settings_and_display', 'payment_setting');
            $bookingpress_package_order_vue_data_fields['package_formdata']['tax_price_display_options'] = $bookingpress_price_setting_display_option;
            
            $bookingpress_tax_order_summary = $BookingPress->bookingpress_get_settings('display_tax_order_summary', 'payment_setting');
            $bookingpress_package_order_vue_data_fields['package_formdata']['display_tax_order_summary'] = $bookingpress_tax_order_summary;
            
            $bookingpress_tax_order_summary_text = $BookingPress->bookingpress_get_settings('included_tax_label', 'payment_setting');
            $bookingpress_package_order_vue_data_fields['package_formdata']['included_tax_label'] = $bookingpress_tax_order_summary_text;
            
            $bookingpress_package_order_vue_data_fields['package_formdata']['tax_percentage_org'] = $tax_percentage;
            $bookingpress_package_order_vue_data_fields['package_formdata']['tax_price_display_options_org'] = $bookingpress_price_setting_display_option;
            $bookingpress_package_order_vue_data_fields['package_formdata']['display_tax_order_summary_org'] = $bookingpress_tax_order_summary;
            $bookingpress_package_order_vue_data_fields['package_formdata']['included_tax_label_org'] = $bookingpress_tax_order_summary_text;            

            $bookingpress_package_order_vue_data_fields['package_formdata']['total_amount'] = 0;
            $bookingpress_package_order_vue_data_fields['package_formdata']['total_amount_with_currency'] = $BookingPress->bookingpress_price_formatter_with_currency_symbol(0);
            
            $bookingpress_package_order_vue_data_fields['package_formdata']['mark_as_paid'] = false;
            $bookingpress_package_order_vue_data_fields['package_formdata']['complete_payment_url_selection'] = 'do_nothing';
            $bookingpress_package_order_vue_data_fields['package_formdata']['complete_payment_url_selected_method'] = array();  
            
            $bookingpress_package_order_vue_data_fields['is_tax_enable'] = (is_plugin_active('bookingpress-tax/bookingpress-tax.php'))?1:0;
            $bookingpress_package_order_vue_data_fields['is_tip_enable'] = (is_plugin_active('bookingpress-tip/bookingpress-tip.php'))?1:0;

            $bookingpress_package_order_vue_data_fields = apply_filters( 'bookingpress_modify_package_order_vue_fields_data', $bookingpress_package_order_vue_data_fields );
            echo wp_json_encode($bookingpress_package_order_vue_data_fields);
        }

        /**
         * Function for load package list
         *
         * @return void
         */
        function bookingpress_package_order_on_load_methods_func(){
        ?>            
            this.loadPackageOrder().catch(error => {
                console.error(error)
            });            
        <?php
        }
                

        /**
         * Function for add package order vue method in admin
         *
         * @return void
         */
        function bookingpress_package_order_vue_methods_func(){
            global $bookingpress_notification_duration;
        ?>  
            bookingpress_apply_to_change_expire_date(){
                const vm = this;
                var is_error = false;  
                var error_msg = false;
                if(vm.package_expiry_date_update_form.package_updated_expiration_date == '') {
                    error_msg =  '<?php esc_html_e('Please select package expire date', 'bookingpress-package'); ?>';
					is_error = true;                  
                }
                if( is_error == true) {
                    vm.$notify({
                        title: '<?php esc_html_e('Error', 'bookingpress-package'); ?>',
                        message: error_msg,
                        type: 'error',
                        customClass: 'error_notification',
                        duration:<?php echo intval($bookingpress_notification_duration); ?>,
                    });
                    return false;
				}
                vm.is_display_package_expiration_loader = '1';                
                var postData = { action:'bookingpress_update_package_order_expiry_date',package_expiry_date_update_form:vm.package_expiry_date_update_form,_wpnonce:'<?php echo esc_html(wp_create_nonce('bpa_wp_nonce')); ?>' };                    
					axios.post( appoint_ajax_obj.ajax_url, Qs.stringify( postData ) )
					.then( function (response) {
						if(response.data.variant == "success"){
							vm.$notify({
								title: response.data.title,
								message: response.data.msg,
								type: response.data.variant,
								customClass: response.data.variant+'_notification',
								duration:<?php echo intval($bookingpress_notification_duration); ?>,
							});      
                            vm.loadPackageOrder(false);                                                       
							vm.bookingpress_close_package_expiration_modal();							
						} else{	

							vm.$notify({
								title: response.data.title,
								message: response.data.msg,
								type: response.data.variant,
								customClass: response.data.variant+'_notification',
								duration:<?php echo intval($bookingpress_notification_duration); ?>,
							});

						}
						vm.is_display_package_expiration_loader = '0';												
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
            bookingpress_close_package_expiration_modal(){
                const vm = this;
                vm.is_display_package_expiration_loader = '0';
                vm.package_expiration_current_row = "";
                vm.package_expiration_current_index = "";
                vm.package_updated_expiration_date = "";
                vm.update_package_expiration_date_modal = false;  
                vm.package_expiry_date_update_form.package_updated_expiration_date = "";
                vm.package_expiry_date_update_form.bookingpress_package_booking_id = "";                               
                vm.package_date_pickerOptions = vm.package_date_pickerOptions_org;
            },
            bookingpress_open_package_expiration_modal(currentElement,index, row){
                const vm = this;
                vm.bookingpress_close_package_expiration_modal();
                vm.package_expiration_current_row = row;
                vm.package_expiration_current_index = index;
                vm.package_expiry_date_update_form.package_updated_expiration_date = "";
                vm.package_expiry_date_update_form.bookingpress_package_booking_id = row.bookingpress_package_booking_id;                                
                vm.package_date_pickerOptions.disabledDate = function(Time){ 
                    if(row.bookingpress_package_expiration_date != "") {                                                     
                        var max_avaliable_time = Date.parse(""+row.bookingpress_package_expiration_date);                            
                        if(Time.getTime() < max_avaliable_time){
                            return true;
                        }                                
                    }
                };
                vm.update_package_expiration_date_modal = true;
				if( typeof vm.bpa_adjust_popup_position != 'undefined' ){
					vm.bpa_adjust_popup_position( currentElement, 'div#update_package_expiration_date_process .el-dialog.bpa-dialog--expiration-change-process');
				}                                
            },
            bookingpress_change_package_order_status(update_id, selectedValue){
                const vm2 = this;
                vm2.items.forEach(function(currentValue, index, arr){
                    if(update_id == currentValue.package_order_id){
                        vm2.items[index].change_status_loader = 1;
                    }
                });
                var postData = { action:'bookingpress_change_package_order_status', update_package_id: update_id, package_new_status: selectedValue, _wpnonce:'<?php echo esc_html(wp_create_nonce('bpa_wp_nonce')); ?>' };
                axios.post( appoint_ajax_obj.ajax_url, Qs.stringify( postData ) )
                .then( function (response) {
                    if(response.data == "0" || response.data == 0){
                        vm2.$notify({
                            title: '<?php esc_html_e('Error', 'bookingpress-package'); ?>',
                            message: '<?php esc_html_e('Something went wrong..', 'bookingpress-package'); ?>',
                            type: 'error',
                            customClass: 'error_notification',
                            duration:<?php echo intval($bookingpress_notification_duration); ?>,
                        });
                        vm2.loadPackageOrder(false);
                        return false;
                    }else{
                        vm2.$notify({
                            title: '<?php esc_html_e('Success', 'bookingpress-package'); ?>',
                            message: '<?php esc_html_e('Package Order status changed successfully', 'bookingpress-package'); ?>',
                            type: 'success',
                            customClass: 'success_notification',
                            duration:<?php echo intval($bookingpress_notification_duration); ?>,
                        });
                        vm2.loadPackageOrder(false);
                    }
                }.bind(this) )
                .catch( function (error) {
                    vm2.$notify({
                        title: '<?php esc_html_e('Error', 'bookingpress-package'); ?>',
                        message: '<?php esc_html_e('Something went wrong..', 'bookingpress-package'); ?>',
                        type: 'error',
                        customClass: 'error_notification',
                        duration:<?php echo intval($bookingpress_notification_duration); ?>,                        
                    });
                });
            },   
            editpackageData(index,row){
                
                const vm2 = this;
                var edit_id = row.package_order_id;
                vm2.package_formdata.package_update_id = edit_id;
                vm2.open_add_package_modal();

                var postData = { action:'bookingpress_get_edit_package_order_data', payment_log_id: edit_id, package_order_id: edit_id,_wpnonce:'<?php echo esc_html(wp_create_nonce('bpa_wp_nonce')); ?>' };
                axios.post( appoint_ajax_obj.ajax_url, Qs.stringify( postData ) )
                .then( function (response) {
                    if(response.data != undefined || response.data != []){ 

                        var bookingpress_package_booking_id = response.data.bookingpress_package_booking_id;
                        var bookingpress_package_no = response.data.bookingpress_package_no;

                        vm2.package_formdata.package_selected_customer = response.data.bookingpress_customer_id;
                        vm2.package_customers_list = response.data.package_customer_list;  
                        vm2.customer_id = vm2.package_formdata.package_selected_customer;
                        vm2.package_formdata.package_internal_note = response.data.bookingpress_package_internal_note;
                        vm2.package_formdata.package_selected_package = response.data.bookingpress_package_id;
                        vm2.package_formdata.package_status = response.data.bookingpress_package_booking_status;                        
                        
                        var postData = { action:'bookingpress_get_package_order_meta_values', bookingpress_package_booking_id: bookingpress_package_booking_id, bookingpress_package_no: bookingpress_package_no, _wpnonce:'<?php echo esc_html(wp_create_nonce('bpa_wp_nonce')); ?>' };
                        axios.post( appoint_ajax_obj.ajax_url, Qs.stringify( postData ) )
                        .then( function (result) {
                            if(result.data.custom_fields_values != ""){
                                
                                vm2.package_formdata.bookingpress_package_meta_fields_value = [];
                                vm2.package_formdata.bookingpress_package_meta_fields_value = result.data.custom_fields_values;						
                                vm2.bookingpress_form_fields.forEach( (element, index) => {
                                    let package_file_field_list = [];
                                    if( "file" == element.bookingpress_field_type ){
                                        let meta_key = element.bookingpress_field_meta_key;
                                        let file_upload_url = vm2.package_formdata.bookingpress_package_meta_fields_value[ meta_key ];                                        
                                        if(typeof file_upload_url != "undefined"){
                                            let file_data = file_upload_url.split('/');
                                            let file_name = file_data[ file_data.length - 1 ];
                                            let file_obj = {
                                                name: file_name,
                                                url: file_upload_url,
                                                response:{
                                                    file_ref: meta_key
                                                }
                                            };								
                                            package_file_field_list.push( file_obj );
                                            if(file_upload_url != '') {
                                                vm2.bookingpress_form_fields[index].bpa_file_list = package_file_field_list;
                                            } else {
                                                vm2.bookingpress_form_fields[index].bpa_file_list = [];
                                            }
                                        }else{
                                            vm2.bookingpress_form_fields[index].bpa_file_list = [];
                                        }
                                    }
                                });

                            }
                        }.bind(this) )
                        .catch( function (error) {
                            console.log(error);
                        });

                        <?php
                            do_action('bookingpress_edit_package_order_details');
                        ?>                        
                        vm2.bookingpress_admin_get_package_final_step_amount();
                    }
                }.bind(this) )
                .catch( function (error) {
                    console.log(error);
                });                

            },  
            BPACustomerFileUploadRemovePackage( file, fileList ){
                const vm = this;
                let response = file.response;
                vm.package_formdata[ response.file_ref ] = "";
                vm.package_formdata.bookingpress_package_meta_fields_value[ response.file_ref ] = "";

                let postData = {
                    action:"bpa_remove_form_file",
                    _wpnonce: "<?php echo esc_html( wp_create_nonce( 'bpa_wp_nonce' ) ); ?>",
                    uploaded_file_name: response.upload_file_name
                };
                axios.post( appoint_ajax_obj.ajax_url, Qs.stringify( postData ) )
                .then( function( response ){
                }).catch( function( error ){
                });
			},            
            BPACustomerFileUploadPackage( response, file, fileList ){
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
                    vm.package_formdata[ response.file_ref ] = upload_url;
                    vm.package_formdata.bookingpress_package_meta_fields_value[ response.file_ref ] = upload_url;
                }
			},               
            bookingpress_admin_get_package_final_step_amount(){
                const vm = this;
                vm.package_formdata.package_price_without_currency = 0;
                var total_amount = 0;
                var subtotal_price = 0;
				var selected_package = vm.package_formdata.package_selected_package;
                if(selected_package){
                    if(typeof vm.package_list != "undefined"){                        
                        let package_lists = vm.package_list;
                        let max_capacity = 0;
                        package_lists.forEach( function( data ){
                            if(data.bookingpress_package_id == selected_package){
                                let bookingpress_package_price = data.bookingpress_package_price;
                                vm.package_formdata.package_price_without_currency = parseFloat(bookingpress_package_price);
                                subtotal_price = total_amount = parseFloat(bookingpress_package_price);

                            }
                        });				
                    }                    
                }				
				var tax_amount = 0;
				if(vm.is_tax_enable){	
					if(vm.package_formdata.tax_percentage != ''){
						var tax_percentage = parseFloat(vm.package_formdata.tax_percentage);					
						if(vm.package_formdata.tax_price_display_options == "include_taxes"){
							tax_amount = (total_amount * tax_percentage) / (100+tax_percentage);
						}else{
							tax_amount = total_amount * ( tax_percentage / 100 );
							total_amount = total_amount + tax_amount;
						}
					}									
				}
				vm.package_formdata.tax = tax_amount; 
				vm.package_formdata.tax_with_currency = vm.bookingpress_price_with_currency_symbol( tax_amount );
				vm.package_formdata.total_amount_with_currency = vm.bookingpress_price_with_currency_symbol( total_amount );
        		vm.package_formdata.total_amount = parseFloat(total_amount);				
				vm.package_formdata.subtotal_with_currency = vm.bookingpress_price_with_currency_symbol( subtotal_price );
        		vm.package_formdata.subtotal = subtotal_price;
                if(vm.is_tip_enable){
                    if(typeof vm.package_formdata.tip_amount !== 'undefined'){
                        if(vm.package_formdata.tip_amount){
                            var tip_amount = parseFloat(vm.package_formdata.tip_amount);
                            total_amount = total_amount + tip_amount;
                            vm.package_formdata.total_amount_with_currency = vm.bookingpress_price_with_currency_symbol( total_amount );
                            vm.package_formdata.total_amount = total_amount;						
                        }
                    }                
                }

            },
			bookingpress_package_select_customer(bookingpress_selected_customer){
				const vm = this;                
				if(bookingpress_selected_customer == "add_new"){
					vm.open_add_customer_modal();
				} else {
					//vm.bookingpress_retrieve_custom_field_values( bookingpress_selected_customer );
				}
			},            
            bookingpress_package_change_package(){
                const vm = this;    
                vm.bookingpress_admin_get_package_final_step_amount();
            },
            toggleBusy() {
                if(this.is_display_loader == '1'){
                    this.is_display_loader = '0'
                }else{
                    this.is_display_loader = '1'
                }
            },
            handleSelectionChange(val) {
                const package_items_obj = val
                this.multipleSelection = [];
                Object.values(package_items_obj).forEach(val => {
                    this.multipleSelection.push({package_id : val.package_order_id});
                    this.bulk_action = 'bulk_action';
                });
            },
            handleSizeChange(val) {
                this.perPage = val
                this.loadPackageOrder();
            },
            handleCurrentChange(val) {
                this.currentPage = val;
                this.loadPackageOrder();
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
                this.loadPackageOrder();
            },
            savePackageBooking(bookingpackage){
                const vm = new Vue();
                const vm2 = this;
                    this.$refs[bookingpackage].validate((valid) => {
                        if(vm2.$refs['package_custom_formdata'] != undefined){
                            vm2.$refs['package_custom_formdata'].validate((validCustomField) => {
                                if(!validCustomField){
                                    valid = false;
                                }
                            });
                        }
                        if (valid) {
                        vm2.is_disabled = true
                        vm2.is_display_save_loader = '1'
                        var postData = { action:'bookingpress_save_package_order_booking',_wpnonce:'<?php echo esc_html(wp_create_nonce('bpa_wp_nonce')); ?>' };
                        postData.package_data = JSON.stringify(vm2.package_formdata);
                        axios.post( appoint_ajax_obj.ajax_url, Qs.stringify( postData ) )
                        .then(function(response){                            
                            vm2.is_disabled = false
                            vm2.is_display_save_loader = '0'
                            if(response.data.variant != 'error') { 
                                vm2.closepackageModal();    
                                vm2.loadPackageOrder();
                            }
                            vm2.$notify({
                                title: response.data.title,
                                message: response.data.msg,
                                type: response.data.variant,
                                customClass: response.data.variant+'_notification',
                                duration:<?php echo intval($bookingpress_notification_duration); ?>,
                            });                        
                        }).catch(function(error){
                            console.log(error);
                            vm2.$notify({
                                title: '<?php esc_html_e('Error', 'bookingpress-package'); ?>',
                                message: '<?php esc_html_e('Something went wrong..', 'bookingpress-package'); ?>',
                                type: 'error',
                                customClass: 'error_notification',
                                duration:<?php echo intval($bookingpress_notification_duration); ?>,
                            });
                        });
                    }
                });
            },
            async loadPackageOrder(showLoader=true) {
                if(showLoader){
                    this.toggleBusy();
                }                
                const vm2 = this
                var bookingpress_module_type = bookingpress_dashboard_filter_start_date = bookingpress_dashboard_filter_end_date = bookingpress_dashboard_filter_package_status = '';
                bookingpress_module_type = sessionStorage.getItem("bookingpress_module_type");                
                bookingpress_dashboard_filter_start_date = sessionStorage.getItem("bookingpress_dashboard_filter_start_date");
                bookingpress_dashboard_filter_end_date = sessionStorage.getItem("bookingpress_dashboard_filter_end_date");
                bookingpress_dashboard_filter_package_status = sessionStorage.getItem("bookingpress_dashboard_filter_package_status");                
                sessionStorage.removeItem("bookingpress_module_type");
                sessionStorage.removeItem("bookingpress_dashboard_filter_start_date");
                sessionStorage.removeItem("bookingpress_dashboard_filter_end_date");
                sessionStorage.removeItem("bookingpress_dashboard_filter_package_status");                
                if(bookingpress_module_type != '' && bookingpress_module_type == 'package' && bookingpress_dashboard_filter_start_date != '' && bookingpress_dashboard_filter_end_date != '' ) {
                    if(bookingpress_dashboard_filter_package_status == '1') {
                    this.search_package_status = '1';                    
                    }  else if(bookingpress_dashboard_filter_package_status == '2') {
                        this.search_package_status = '2'; 
                    }
                    var package_date_range = [bookingpress_dashboard_filter_start_date,bookingpress_dashboard_filter_end_date];
                    this.package_date_range = package_date_range;
                }                
                var bookingpress_search_data = { 'search_package':this.search_package,'selected_date_range': this.package_date_range, 'customer_name': this.search_customer_name,'package_name': this.search_package_name,'package_status': this.search_package_status, 'search_package_id' : this.search_package_id}                  
                <?php do_action('bookingpress_package_order_add_post_data'); ?>
                //bookingpress_get_assign_packages
                var postData = { action:'bookingpress_get_package_order', perpage:this.perPage, currentpage:this.currentPage, search_data: bookingpress_search_data,_wpnonce:'<?php echo esc_html(wp_create_nonce('bpa_wp_nonce')); ?>'};
                <?php do_action('bookingpress_modify_package_order_send_data'); ?>
                axios.post( appoint_ajax_obj.ajax_url, Qs.stringify( postData ) )
                .then( function (response){ 
                    if(showLoader){                   
                        vm2.toggleBusy();
                    }
                    vm2.items = response.data.items;
                    vm2.totalItems = response.data.totalItems;
                    vm2.form_field_data = response.data.form_field_data;                                   
                    <?php do_action('bookingpress_modify_package_order_success_response_data'); ?>
                }.bind(this) )
                .catch( function (error) {                    
                    vm2.$notify({
                        title: '<?php esc_html_e('Error', 'bookingpress-package'); ?>',
                        message: '<?php esc_html_e('Something went wrong..', 'bookingpress-package'); ?>',
                        type: 'error',
                        customClass: 'error_notification',
                        duration:<?php echo intval($bookingpress_notification_duration); ?>,
                    });
                });
            },
            loadPackageOrderWithoutLoader() {
               
               /*
                const vm2 = this
                var bookingpress_module_type = bookingpress_dashboard_filter_start_date = bookingpress_dashboard_filter_end_date = bookingpress_dashboard_filter_package_status = '';
                bookingpress_module_type = sessionStorage.getItem("bookingpress_module_type");                
                bookingpress_dashboard_filter_start_date = sessionStorage.getItem("bookingpress_dashboard_filter_start_date");
                bookingpress_dashboard_filter_end_date = sessionStorage.getItem("bookingpress_dashboard_filter_end_date");
                bookingpress_dashboard_filter_package_status = sessionStorage.getItem("bookingpress_dashboard_filter_package_status");                
                sessionStorage.removeItem("bookingpress_module_type");
                sessionStorage.removeItem("bookingpress_dashboard_filter_start_date");
                sessionStorage.removeItem("bookingpress_dashboard_filter_end_date");
                sessionStorage.removeItem("bookingpress_dashboard_filter_package_status");                
                if(bookingpress_module_type != '' && bookingpress_module_type == 'package' && bookingpress_dashboard_filter_start_date != '' && bookingpress_dashboard_filter_end_date != '' ) {
                    if(bookingpress_dashboard_filter_package_status == '1') {
                    this.search_package_status = '1';                    
                    }  else if(bookingpress_dashboard_filter_package_status == '2') {
                        this.search_package_status = '2'; 
                    }
                    var package_date_range = [bookingpress_dashboard_filter_start_date,bookingpress_dashboard_filter_end_date];
                    this.package_date_range = package_date_range;
                }                
                var bookingpress_search_data = { 'search_package':this.search_package,'selected_date_range': this.package_date_range, 'customer_name': this.search_customer_name,'service_name': this.search_package_name,'package_status': this.search_package_status}  
                
                <?php do_action('bookingpress_package_order_add_post_data'); ?>

                var postData = { action:'bookingpress_get_packages', perpage:this.perPage, currentpage:this.currentPage, search_data: bookingpress_search_data,_wpnonce:'<?php echo esc_html(wp_create_nonce('bpa_wp_nonce')); ?>'};
                axios.post( appoint_ajax_obj.ajax_url, Qs.stringify( postData ) )
                .then( function (response) {
                    vm2.items = response.data.items;
                    vm2.totalItems = response.data.totalItems;
                }.bind(this) )
                .catch( function (error) {                    
                    vm2.$notify({
                        title: '<?php esc_html_e('Error', 'bookingpress-package'); ?>',
                        message: '<?php esc_html_e('Something went wrong..', 'bookingpress-package'); ?>',
                        type: 'error',
                        customClass: 'error_notification',
                        duration:<?php echo intval($bookingpress_notification_duration); ?>,
                    });
                });
                
                */

            },                   
            bookingpress_loader_hide() {
                this.modal_loader = 0
            },                        
            deletepackage(index, row) {
                const vm = new Vue();
                const vm2 = this;
                var delete_id = row.package_order_id;
                var package_delete_data = { action: 'bookingpress_delete_package_order', delete_id: delete_id,_wpnonce:'<?php echo esc_html(wp_create_nonce('bpa_wp_nonce')); ?>' }
                axios.post( appoint_ajax_obj.ajax_url, Qs.stringify( package_delete_data ) )
                .then(function(response){
                    vm2.$notify({
                        title: response.data.title,
                        message: response.data.msg,
                        type: response.data.variant,
                        customClass: 'error_notification',
                        duration:<?php echo intval($bookingpress_notification_duration); ?>,
                    });
                    vm2.loadPackageOrder()
                }).catch(function(error){
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
            bulk_actions() {                
                const vm = new Vue()
                const vm2 = this
                if(vm2.bulk_action == "bulk_action"){
                    vm2.$notify({
                        title: '<?php esc_html_e('Error', 'bookingpress-package'); ?>',
                        message: '<?php esc_html_e('Please select any action.', 'bookingpress-package'); ?>',
                        type: 'error',
                        customClass: 'error_notification',
                        duration:<?php echo intval($bookingpress_notification_duration); ?>,
                    });
                }else{
                    if(this.multipleSelection.length > 0 && this.bulk_action == "delete"){
                        var package_delete_data = {
                            action:'bookingpress_bulk_package_order',
                            app_delete_ids: this.multipleSelection,
                            bulk_action: 'delete',
                            _wpnonce:'<?php echo esc_html(wp_create_nonce('bpa_wp_nonce')); ?>',
                        }
                        axios.post( appoint_ajax_obj.ajax_url, Qs.stringify( package_delete_data ) )
                        .then(function(response){
                            vm2.$notify({
                                title: response.data.title,
                                message: response.data.msg,
                                type: response.data.variant,
                                customClass: response.data.variant+'_notification',
                                duration:<?php echo intval($bookingpress_notification_duration); ?>,
                            });
                            vm2.loadPackageOrder();
                            vm2.multipleSelection = [];
                            vm2.totalItems = vm2.items.length
                        }).catch(function(error){
                            console.log(error);
                            vm2.$notify({
                                title: '<?php esc_html_e('Error', 'bookingpress-package'); ?>',
                                message: '<?php esc_html_e('Something went wrong..', 'bookingpress-package'); ?>',
                                type: 'error',
                                customClass: 'error_notification',
                                duration:<?php echo intval($bookingpress_notification_duration); ?>,
                            });
                        });
                    }else{    
                        if(this.multipleSelection.length == 0) {
                            vm2.$notify({
                                title: '<?php esc_html_e('Error', 'bookingpress-package'); ?>',
                                message: '<?php esc_html_e('Please select one or more records.', 'bookingpress-package'); ?>',
                                type: 'error',
                                customClass: 'error_notification',
                                duration:<?php echo intval($bookingpress_notification_duration); ?>,
                            });
                        } else {
                        <?php do_action('bookingpress_package_order_dynamic_bulk_action'); ?>
                        }
                    }
                }
            },
            isOnlyNumber: function(evt) {
                const vm = this
                this.search_package_id = event.target.value.replace(/[^0-9]/g, "");
            },
            resetFilter(){
                const vm = this;
                vm.search_package = '';
                vm.package_date_range = '';
                vm.search_customer_name = '';
                vm.search_package_name = '';
                vm.search_package_status = '';
                vm.search_package_id = '';
                <?php 
                do_action('bookingpress_package_reset_filter');
                ?>
                vm.loadPackageOrder()
            },
            resetForm() {
                const vm2 = this;
                vm2.package_formdata.package_selected_customer = '';                               
                vm2.package_formdata.package_internal_note = '';
                vm2.package_formdata.package_send_notification = '';
                vm2.package_formdata.package_status = '1';
                vm2.package_formdata.package_update_id = 0;
				let appointment_meta_fields = vm2.package_formdata.bookingpress_package_meta_fields_value;				
				for( let k in appointment_meta_fields ){
					let currentVal = appointment_meta_fields[k];
					if( "boolean" == typeof currentVal ){
						vm2.package_formdata.bookingpress_package_meta_fields_value[k] = false;
					} else if( "string" == typeof currentVal ){
						vm2.package_formdata.bookingpress_package_meta_fields_value[k] = "";
					} else if( "object" == typeof currentVal ){
						vm2.package_formdata.bookingpress_package_meta_fields_value[k] = [];
					}
				}
				vm2.package_formdata.complete_payment_url_selection = 'do_nothing';
				vm2.package_formdata.complete_payment_url_selected_method = [];
				
				let appointment_form_fields  = vm2.bookingpress_form_fields;
				for( let m in appointment_form_fields ){
					let currentval = appointment_form_fields[m];					
					if(currentval.bookingpress_field_type == 'file') {
						vm2.bookingpress_form_fields[m]['bpa_file_list'] = [];
					}
				}				
				vm2.package_formdata.bookingpress_currency_name	= vm2.package_formdata.bookingpress_currency_name_org; 			
				vm2.package_formdata.total_amount = 0;
				vm2.package_formdata.total_amount_with_currency = vm2.bookingpress_price_with_currency_symbol( 0 );	
				vm2.package_formdata.subtotal = 0;
				vm2.package_formdata.subtotal_with_currency = vm2.bookingpress_price_with_currency_symbol( 0 );

            },
            closepackageModal() {
                const vm2= this
                vm2.$refs['package_formdata'].resetFields()
                vm2.resetForm()
                vm2.package_customers_list = [];
                vm2.open_package_modal = false;                
                <?php do_action('bookingpress_add_package_order_model_reset') ?>
            },    
            open_add_package_modal() {
                this.open_package_modal = true;
            },                        
            closeBulkAction(){
                this.$refs.multipleTable.clearSelection();
                this.bulk_action = 'bulk_action';
            },
            get_formatted_date(iso_date){

                if( true == /(\d{2})\T/.test( iso_date ) ){
                    let date_time_arr = iso_date.split('T');
                    return date_time_arr[0];
                }
            
                var __date = new Date(iso_date);
                var __year = __date.getFullYear();
                var __month = __date.getMonth()+1;
                var __day = __date.getDate();
                if (__day < 10) {
                    __day = '0' + __day;
                }
                if (__month < 10) {
                    __month = '0' + __month;
                }
                var formatted_date = __year+'-'+__month+'-'+__day;
                return formatted_date;
            },
			closePackageCustomerModal() {
                const vm2 = this;
                vm2.$refs['customer'].resetFields();
                vm2.open_customer_modal = false;
                vm2.resetCustomerForm();
				vm2.package_formdata.package_selected_customer = '';
            },            
			savePackageCustomerDetails(){
                const vm2 = this
                vm2.$refs['customer'].validate((valid) => {
                    if(valid){
                        vm2.is_disabled = true
                        vm2.is_display_save_loader = '1'
                        var postdata = vm2.customer;
                        postdata.action = 'bookingpress_add_customer';
						postdata._wpnonce ='<?php echo esc_html(wp_create_nonce('bpa_wp_nonce')); ?>';
                        axios.post( appoint_ajax_obj.ajax_url, Qs.stringify( postdata ) )
                        .then(function(response){
                            vm2.is_disabled = false
                            vm2.is_display_save_loader = '0'                            
                            vm2.$notify({
                                title: response.data.title,
                                message: response.data.msg,
                                type: response.data.variant,
                                customClass: response.data.variant+'_notification',
                                duration:<?php echo intval($bookingpress_notification_duration); ?>,
                            });
                            if (response.data.variant == 'success') {
                                vm2.open_customer_modal = false
                                vm2.customer.update_id = response.data.customer_id
								vm2.bookingpress_get_package_customers_details(response.data.customer_id);
                            }
                            vm2.savebtnloading = false
                        }).catch(function(error){
                            vm2.is_disabled = false
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
                    }
                })
            },
			bookingpress_get_package_customers_details(selected_customer_id = ""){
				const vm = this
				var customer_details_action = { action: 'bookingpress_get_customer_details',customer_id:selected_customer_id, _wpnonce: '<?php echo esc_html(wp_create_nonce('bpa_wp_nonce')); ?>' }
				axios.post( appoint_ajax_obj.ajax_url, Qs.stringify( customer_details_action ) )
				.then(function(response){
					vm.package_customers_list = response.data.appointment_customers_details;
					if(selected_customer_id != ""){
						setTimeout(function(){
							vm.package_formdata.package_selected_customer = ''+selected_customer_id;
						}, 500);
					}
				}).catch(function(error){
					console.log(error)
				});				
			},                  
            bookingpress_get_search_customer_list(query){
				const vm = new Vue()
				const vm2 = this	
				if (query !== '') {
					vm2.bookingpress_loading = true;                    
					var customer_action = { action:'bookingpress_get_search_customer_list_package',search_user_str:query,_wpnonce:'<?php echo esc_html(wp_create_nonce('bpa_wp_nonce')); ?>' }                    
					axios.post( appoint_ajax_obj.ajax_url, Qs.stringify( customer_action ) )
					.then(function(response){
						vm2.bookingpress_loading = false;
						vm2.search_customer_list = response.data.package_customers_details
					}).catch(function(error){
						console.log(error)
					});
				} else {
					vm2.search_customer_list = [];
				}	
			},
            bookingpress_get_customer_list(query){
                const vm = new Vue()
                const vm2 = this	
                if (query !== '') {
                    vm2.bookingpress_loading = true;                    
                    var customer_action = { action:'bookingpress_get_package_customer_list',search_user_str:query,customer_id:vm2.customer_id,_wpnonce:'<?php echo esc_html(wp_create_nonce('bpa_wp_nonce')); ?>' }                    
                    axios.post( appoint_ajax_obj.ajax_url, Qs.stringify( customer_action ) )
                    .then(function(response){
                        vm2.bookingpress_loading = false;
                        vm2.package_customers_list = response.data.appointment_customers_details
                    }).catch(function(error){
                        console.log(error)
                    });
                } else {
                    vm2.package_customers_list = [];
                }	
            },
            bookingpress_get_page_list(query){
                const vm = new Vue();
                const vm2 = this;	
                if (query !== '') {
                    vm2.bookingpress_loading = true;                    
                    var customer_action = { action:'bookingpress_get_wp_page_list',search_page_str:query,_wpnonce:'<?php echo esc_html(wp_create_nonce('bpa_wp_nonce')); ?>' }          
                    axios.post( appoint_ajax_obj.ajax_url, Qs.stringify( customer_action ) )
                    .then(function(response){
                        vm2.bookingpress_loading = false;
                        vm2.all_share_pages_list = response.data.all_page_list
                    }).catch(function(error){
                        console.log(error);
                    });
                } else {
                    vm2.all_share_pages_list = [];
                }	
                
            },
            bookingpress_full_row_clickable(row, $el, events){
                const vm = this;
                let source = events.target;
                if( null != source ){
                    let parents = vm.BPAGetParents( source, '.bpa-btn--icon-change-expiry-date' );
                    if( source.classList.contains('bpa-btn--icon-change-expiry-date') || parents.length > 0 ){
                        return false;
                    }
                }                 
                <?php do_action('bookingpress_package_full_row_clickable'); ?>
                vm.$refs.multipleTable.toggleRowExpansion(row);
            },
            bookingpress_row_expand(row, expanded){
                const vm = this
                if(vm.bookingpress_previous_row_obj != ''){
                    vm.$refs.multipleTable.toggleRowExpansion(vm.bookingpress_previous_row_obj, false);
                    if(vm.bookingpress_previous_row_obj != row){
                        vm.$refs.multipleTable.toggleRowExpansion(vm.bookingpress_previous_row_obj);
                        vm.bookingpress_previous_row_obj = row;
                    }else{
                        if(expanded.length == undefined){
                            vm.$refs.multipleTable.toggleRowExpansion(row);
                        }
                        vm.bookingpress_previous_row_obj = '';
                    }
                }else{
                    if(expanded.length == undefined){
                        vm.$refs.multipleTable.toggleRowExpansion(row);
                    }
                    vm.bookingpress_previous_row_obj = row;
                }
            },
            bookingpress_set_time(event,time_slot_data) {
                const vm = this
                if(event != '' && time_slot_data != '') {
                    for (let x in time_slot_data) {                      
                        var slot_data_arr = time_slot_data[x];                        
                        for(let y in slot_data_arr) {
                            var time_slot_data_arr = slot_data_arr[y];
                            for(let m in time_slot_data_arr) {                            
                                var data_arr  = time_slot_data_arr[m];
                                if(data_arr.store_start_time != undefined && data_arr.store_end_time != undefined && data_arr.store_start_time == event) {   
                                    vm.package_formdata.package_booked_end_time = data_arr.store_end_time;
                                    <?php do_action('bookingpress_admin_add_package_after_select_timeslot'); ?>
                                }
                            }                                                    
                        }                      
                    }                    
                }
            },
            bookingpress_handle_tax_calculation_pkg(field_id, event, form_fields){
                const vm = this;
                if(typeof vm.package_formdata.countryselectedField != "undefined" && vm.package_formdata.countryselectedField == field_id && typeof form_fields !== "undefined" && typeof form_fields.is_repeater_field_inner_field !== "undefined" && form_fields.is_repeater_field_inner_field != true) {
                    if(typeof vm.package_formdata.enable_country_wise_tax != "undefined" && vm.package_formdata.enable_country_wise_tax == "true"){
						const form_field_value = event;
                        const taxPercentage = vm.package_formdata.country_wise_tax_details.find(item => item.selectedOption === form_field_value)?.bookingpress_country_wise_tax_per;
                        if(typeof vm.package_formdata.tax_percentage_temp == "undefined"){
                        	vm.package_formdata.tax_percentage_temp = vm.package_formdata.tax_percentage;                        
                    	}
                    	else {
	                        vm.package_formdata.tax_percentage = vm.package_formdata.tax_percentage_temp;  
                    	}

						if(taxPercentage != "" && typeof taxPercentage != "undefined") {
							vm.package_formdata.tax_percentage = parseFloat(taxPercentage);							
                        }
					}
					vm.bookingpress_admin_get_package_final_step_amount();
				}	
            },
            <?php
            do_action('bookingpress_package_order_add_dynamic_vue_methods');
        }        


        /**
         * Package order view file
         *
         * @return void
         */
        function bookingpress_package_order_dynamic_view_func() {
			$bookingpress_load_file_name = BOOKINGPRESS_PACKAGE_VIEWS_DIR . '/package_order.php';
			require $bookingpress_load_file_name;
		}

        /**
         * Function for add package order capability
         *
         * @param  mixed $bpa_caps
         * @return void
        */
        function bookingpress_modify_capability_data_func($bpa_caps){

            $bpa_caps['bookingpress_package_order'][] = 'get_package_order_details';
            $bpa_caps['bookingpress_package_order'][] = 'save_package_order_details';
            $bpa_caps['bookingpress_package_order'][] = 'delete_package_order';
            $bpa_caps['bookingpress_package_order'][] = 'edit_package_order_details';
            $bpa_caps['bookingpress_package_order'][] = 'change_package_order_status';
            $bpa_caps['bookingpress_package_order'][] = 'get_package_order_meta_value';
            $bpa_caps['bookingpress_package_order'][] = 'package_order_expiry_date_update';
            
            return $bpa_caps;

        }




    }
    
	global $bookingpress_package_order;
	$bookingpress_package_order = new bookingpress_package_order();
}