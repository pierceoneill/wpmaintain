<?php
if (!class_exists('bookingpress_package_appointment_book_backend')) {
	class bookingpress_package_appointment_book_backend Extends BookingPress_Core {

        function __construct(){

            global $wp, $wpdb, $tbl_bookingpress_packages, $tbl_bookingpress_package_services, $tbl_bookingpress_package_images, $BookingPress, $tbl_bookingpress_package_bookings,$bookingpress_package;
                        
            $package_addon_working = $bookingpress_package->bookingpress_check_package_addon_requirement();

            if( is_plugin_active( 'bookingpress-appointment-booking-pro/bookingpress-appointment-booking-pro.php' ) && !empty( $BookingPress->bpa_pro_plugin_version() ) && version_compare( $BookingPress->bpa_pro_plugin_version(), '3.2', '>=' ) && $package_addon_working){

                /* Add Functionality For Backend Package Data Add */
                add_filter('bookingpress_modify_appointment_data_fields',array($this,'bookingpress_modify_backend_appointment_data_fields_func'),15);            
                add_filter( 'bookingpress_modify_calendar_data_fields', array( $this, 'bookingpress_modify_backend_appointment_data_fields_func' ),15);
                add_filter( 'bookingpress_modify_dashboard_data_fields', array( $this, 'bookingpress_modify_backend_appointment_data_fields_func' ),15 );                

                /* add backend fields */
                add_action('bookingpress_add_appointment_after_sub_total_backend',array($this,'bookingpress_add_appointment_after_sub_total_backend_func'),10);

                add_action('bookingpress_change_backend_service',array($this,'bookingpress_change_backend_service_func'),10);

                add_action('bookingress_backend_after_select_customer',array($this,'bookingress_backend_after_select_customer_func'),10);

                /* Backend admin vue method added */
                //add_action( 'bookingpress_appointment_add_dynamic_vue_methods', array( $this, 'bookingpress_appointment_add_dynamic_vue_methods_func' ), 15 );
                add_action('bookingpress_admin_panel_vue_methods', array($this, 'bookingpress_appointment_add_dynamic_vue_methods_func'), 10);
                
                /* Function for get user package list */
                add_action('wp_ajax_bookingpress_backend_get_customer_package_list', array($this,'bookingpress_backend_get_customer_package_list_func'));

                /* Apply package order price in backend */            
                add_action('bookingpress_admin_calculate_subtotal_price',array($this,'bookingpress_admin_calculate_package_price_fun'),25);

                /* Package Redeem Request backend */
                add_action( 'wp_ajax_bookingpress_redeem_package_backend_request', array( $this, 'bookingpress_redeem_package_backend_request_func' ) );

                add_filter('bookingpress_modify_backend_add_appointment_entry_data',array($this,'bookingpress_modify_backend_add_appointment_entry_data_func'),10,2);

                /* BookingPress Add appointment clear data for backend */
                add_action('bookingpress_add_appointment_model_reset',array($this,'bookingpress_add_appointment_model_reset_func'),11);            
                add_action('bookingpress_calendar_add_appointment_model_reset', array( $this, 'bookingpress_add_appointment_model_reset_func' ),11);

                /* Set a package edit appointment data  */
                add_action('bookingpress_edit_appointment_details',array($this,'bookingpress_edit_appointment_details_func'),10);
                add_filter('bookingpress_modify_edit_appointment_data',array($this,'bookingpress_modify_edit_appointment_data_func'),10,1);

                /* Set Update Appointment Data */
                add_filter('bookingpress_modify_appointment_booking_fields',array($this,'bookingpress_modify_appointment_booking_fields_func'),12,3);

            }
        }
        
        /**
         * Function for backend update appointment data add.
         *
         * @param  mixed $appointment_booking_fields
         * @param  mixed $entry_data
         * @param  mixed $bookingpress_appointment_data
         * @return void
        */
        function bookingpress_modify_appointment_booking_fields_func($appointment_booking_fields, $entry_data, $bookingpress_appointment_data) { 

            if(isset($bookingpress_appointment_data['bookingpress_package_applied_data']) && !empty($bookingpress_appointment_data['bookingpress_package_applied_data'])){ 
                
                $package_discount_price = (isset($bookingpress_appointment_data['bookingpress_package_applied_data']['package_discount_price']))?$bookingpress_appointment_data['bookingpress_package_applied_data']['package_discount_price']:0;
                $bookingpress_package_no = (isset($bookingpress_appointment_data['bookingpress_package_applied_data']['bookingpress_package_no']))?$bookingpress_appointment_data['bookingpress_package_applied_data']['bookingpress_package_no']:0;
                $appointment_booking_fields['bookingpress_purchase_type'] = 3;
                $appointment_booking_fields['bookingpress_package_id'] = $bookingpress_package_no;
                $bookingpress_package_applied_data_insert = (isset($bookingpress_appointment_data['bookingpress_package_applied_data']))?$bookingpress_appointment_data['bookingpress_package_applied_data']:array();
                $appointment_booking_fields['bookingpress_applied_package_data'] = json_encode($bookingpress_package_applied_data_insert);
                $appointment_booking_fields['bookingpress_package_discount_amount'] = $package_discount_price;

                $appointment_booking_field['bookingpress_tax_amount'] = (isset($bookingpress_appointment_data['tax']) && !empty($bookingpress_appointment_data['tax']))?floatval($bookingpress_appointment_data['tax']):0;

            }else{

                $appointment_booking_fields['bookingpress_purchase_type'] = 1;
                $appointment_booking_fields['bookingpress_package_id'] = 0;
                $appointment_booking_fields['bookingpress_package_discount_amount'] = 0;
                $appointment_booking_fields['bookingpress_applied_package_data'] = "";
                if($entry_data['bookingpress_purchase_type'] == 3){
                    $appointment_booking_field['bookingpress_tax_amount'] = (isset($bookingpress_appointment_data['tax']) && !empty($bookingpress_appointment_data['tax']))?floatval($bookingpress_appointment_data['tax']):0;
                }

            }

            return $appointment_booking_fields;

        }

        /**
         * Function for modified backend edit appointment data
         *
         * @param  mixed $appointment_data
         * @return void
        */
        function bookingpress_modify_edit_appointment_data_func($appointment_data){
            
            $bookingpress_applied_package_data = (isset($appointment_data['bookingpress_applied_package_data']))?$appointment_data['bookingpress_applied_package_data']:'';
            $bookingpress_applied_package_data_assign = '';
            if(!empty($bookingpress_applied_package_data)){
                $bookingpress_applied_package_data = json_decode($bookingpress_applied_package_data,true);
                if(is_array($bookingpress_applied_package_data) && !empty($bookingpress_applied_package_data)){
                    $bookingpress_applied_package_data_assign = $bookingpress_applied_package_data;
                }
            }
            $appointment_data['bookingpress_applied_package_data'] = $bookingpress_applied_package_data_assign;
            return $appointment_data;

        }

        /**
         * Function for backend edit appointment package data set
         *
         * @return void
         */
        function bookingpress_edit_appointment_details_func(){
        ?>
            if(typeof response.data.bookingpress_applied_package_data != "undefined"){
              vm2.appointment_formdata.bookingpress_package_applied_data = response.data.bookingpress_applied_package_data;
              vm.bookingpress_backend_get_customer_package_list();
            }else{
              vm.bookingpress_backend_get_customer_package_list();
            }
        <?php 
        }
                      
        /**
         * Function for reset appointment modal 
         *
         * @return void
        */
        function bookingpress_add_appointment_model_reset_func(){
        ?>
			if(typeof vm2.appointment_formdata.bookingpress_package_applied_data != "undefined"){
				vm2.appointment_formdata.bookingpress_package_applied_data = '';
			}              
            if(typeof vm2.bookingpress_customer_package_list != "undefined"){
                vm2.bookingpress_customer_package_list = [];  
            }
        <?php 
        }
                
        /**
         * Function for modified package applied data in backend
         *
         * @param  mixed $bookingpress_entry_details
         * @param  mixed $bookingpress_appointment_data
         * @return void
        */
        function bookingpress_modify_backend_add_appointment_entry_data_func($bookingpress_entry_details, $bookingpress_appointment_data){

            if(isset($bookingpress_appointment_data['bookingpress_package_applied_data']) && !empty($bookingpress_appointment_data['bookingpress_package_applied_data'])){ 
                
                $package_discount_price = (isset($bookingpress_appointment_data['bookingpress_package_applied_data']['package_discount_price']))?$bookingpress_appointment_data['bookingpress_package_applied_data']['package_discount_price']:0;
                $bookingpress_package_no = (isset($bookingpress_appointment_data['bookingpress_package_applied_data']['bookingpress_package_no']))?$bookingpress_appointment_data['bookingpress_package_applied_data']['bookingpress_package_no']:0;
                $bookingpress_entry_details['bookingpress_purchase_type'] = 3;
                $bookingpress_entry_details['bookingpress_package_id'] = $bookingpress_package_no;
                $bookingpress_package_applied_data_insert = (isset($bookingpress_appointment_data['bookingpress_package_applied_data']))?$bookingpress_appointment_data['bookingpress_package_applied_data']:array();
                $bookingpress_entry_details['bookingpress_applied_package_data'] = json_encode($bookingpress_package_applied_data_insert);
                $bookingpress_entry_details['bookingpress_package_discount_amount'] = $package_discount_price;
                
            }
            return $bookingpress_entry_details;

        }

        /**
         * Function for package redeem request
         *
         * @return void
         */
        function bookingpress_redeem_package_backend_request_func(){
            
            global $tbl_bookingpress_customers,$tbl_bookingpress_package_bookings,$wpdb,$BookingPress,$bookingpress_package;                       
            $wpnonce               = isset( $_REQUEST['_wpnonce'] ) ? sanitize_text_field( $_REQUEST['_wpnonce'] ) : '';
            $bpa_verify_nonce_flag = wp_verify_nonce( $wpnonce, 'bpa_wp_nonce' );
            $response              = array();
            if ( ! $bpa_verify_nonce_flag ) {
                $response                = array();
                $response['variant']     = 'error';
                $response['title']       = esc_html__( 'Error', 'bookingpress-package' );
                $response['msg']         = esc_html__( 'Sorry, Your request can not be processed due to security reason.', 'bookingpress-package' );
                echo wp_json_encode( $response );
                die();
            }
            $response                         = array();
            $response['variant']              = 'error';
            $response['title']                = __( 'Error', 'bookingpress-package' );
            $response['msg']                  = __( 'Package appointments are not available.', 'bookingpress-package' );

            if( !empty( $_POST['appointment_details'] ) && !is_array( $_POST['appointment_details'] ) ){
                $_POST['appointment_details'] = json_decode( stripslashes_deep( $_POST['appointment_details'] ), true ); //phpcs:ignore
            }
            $bookingpress_selected_package = !empty( $_POST['appointment_details']['selected_package'] ) ? intval( $_POST['appointment_details']['selected_package'] ) : 0;
            $bookingpress_package_customer_id = !empty( $_POST['appointment_details']['appointment_selected_customer'] ) ? intval( $_POST['appointment_details']['appointment_selected_customer'] ) : 0;             
            $appointment_selected_service = !empty( $_POST['appointment_details']['appointment_selected_service'] ) ? intval( $_POST['appointment_details']['appointment_selected_service'] ) : 0; 
            $bookingpress_selected_bring_members = !empty( $_POST['appointment_details']['selected_bring_members'] ) ? intval( $_POST['appointment_details']['selected_bring_members']) : 1; 
            $appointment_update_id = (isset($_POST['appointment_details']['appointment_update_id']))?intval($_POST['appointment_details']['appointment_update_id']):'';            
            $enable_custom_service_duration = (isset($_POST['appointment_details']['enable_custom_service_duration']))?$_POST['appointment_details']['enable_custom_service_duration']:''; // phpcs:ignore

            $package_applied_data = "";
            if(!empty($bookingpress_selected_package) && !empty($bookingpress_package_customer_id) && !empty($appointment_selected_service) && empty($enable_custom_service_duration)){

                $get_current_user_customer = $wpdb->get_row( $wpdb->prepare( "SELECT bookingpress_wpuser_id FROM {$tbl_bookingpress_customers} WHERE bookingpress_customer_id = %d ", $bookingpress_package_customer_id ), ARRAY_A); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- Reason: $tbl_bookingpress_customers is table name defined globally. False Positive alarm                
                if(!empty($get_current_user_customer)){
                    $bookingpress_wpuser_id = (isset($get_current_user_customer['bookingpress_wpuser_id']) && !empty($get_current_user_customer['bookingpress_wpuser_id']))?$get_current_user_customer['bookingpress_wpuser_id']:'';
                    $bookingpress_package_redeem_amount = 0;
                    if($bookingpress_wpuser_id){                        
                        $bookingpress_get_no_of_appointment_remaining_data = $this->bookingpress_get_no_of_appointment_remaining_data($bookingpress_selected_package,$bookingpress_wpuser_id,$appointment_selected_service,$appointment_update_id);
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
            }
            if(!empty($package_applied_data)){
                $response['variant']     = 'success';
                $response['title']       = __('Success', 'bookingpress-package');
                $response['bookingpress_is_cart']           = 0;
                $response['bookingpress_has_applied_cart']  = 0;
                $response['bookingpress_cart_items']        = array();                    
                $response['msg']         = __('Package Succesfully Applied', 'bookingpress-package');
                $response['bookingpress_package_applied_data'] = $package_applied_data;                
            }
            echo wp_json_encode( $response );
            die();
        }
        

        function bookingpress_get_no_of_appointment_remaining_data($package_id,$bookingpress_wpuser_id,$service_id,$appointment_update_id = 0){
            global $wpdb,$BookingPress, $tbl_bookingpress_customers, $tbl_bookingpress_package_bookings,$bookingpress_package;
            $package_service_data = array();
            if(!empty($package_id) && !empty($service_id)){
                $current_user_id = $bookingpress_wpuser_id;

                $bookingpress_current_date = date('Y-m-d');
                $bookingpress_get_purchase_packages = $wpdb->get_results( $wpdb->prepare( "SELECT bookingpress_customer_id,bookingpress_login_user_id,bookingpress_package_id,bookingpress_package_no,bookingpress_package_services,bookingpress_package_name FROM {$tbl_bookingpress_package_bookings} WHERE bookingpress_package_expiration_date > %s AND bookingpress_package_id = %d AND bookingpress_login_user_id = %d AND (bookingpress_package_booking_status = %s OR bookingpress_package_booking_status = %s)",$bookingpress_current_date, $package_id,$current_user_id,'1','4'), ARRAY_A); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared --Reason: $tbl_bookingpress_package_bookings is a table name. false alarm		
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
                                        
                                        $total_purchase = $bookingpress_package->get_package_service_purchase_count($package_purchase['bookingpress_package_no'],$bookingpress_service_id,$appointment_update_id);
                                        $total_avaliable = $bookingpress_no_of_appointments - $total_purchase;
                                        $package_service_data[] = array(
                                            'bookingpress_package_no' => $package_purchase['bookingpress_package_no'],
                                            'bookingpress_package_id' => $package_purchase['bookingpress_package_id'],
                                            'bookingpress_customer_id' => $package_purchase['bookingpress_customer_id'],
                                            'bookingpress_login_user_id' => $package_purchase['bookingpress_login_user_id'],
                                            'bookingpress_service_id' => $bookingpress_service_id,
                                            'bookingpress_package_name' => $package_purchase['bookingpress_package_name'],
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

        /**
         * Function for add package price in backend
         *
         * @return void
         */
        function bookingpress_admin_calculate_package_price_fun(){
        ?>
            var bookingpress_is_package_remove = "";
            if(vm.is_custom_service_duration){	
                if(typeof vm.appointment_formdata.enable_custom_service_duration !== 'undefined' && vm.appointment_formdata.enable_custom_service_duration == true){
                    bookingpress_is_package_remove = "yes";
                }
            }
            if(typeof vm.appointment_formdata.is_recurring_appointments != "undefined"){
                if(vm.appointment_formdata.is_recurring_appointments == true){
                    bookingpress_is_package_remove = "yes";
                }
            }    
            if(bookingpress_is_package_remove == "yes"){
                vm.bookingpress_backend_remove_applied_package(false);
            }else{                
                var package_discount_price = 0;
                if(vm.appointment_formdata.bookingpress_package_applied_data != ""){
                    var package_discount_price = parseFloat(vm.appointment_formdata.service_price_without_currency);                
                    var selected_bring_members = parseInt(vm.appointment_formdata.selected_bring_members);
                    package_discount_price = package_discount_price * selected_bring_members;  
                    package_discount_price = parseFloat(package_discount_price);
                    vm.appointment_formdata.bookingpress_package_applied_data.package_discount_price = package_discount_price;
                }            
                total_amount = total_amount - package_discount_price;
                vm.appointment_formdata.bookingpress_package_discount_amount_with_currency = vm.bookingpress_price_with_currency_symbol( package_discount_price );
            }
        <?php 
        }

        /**
         * Function for get user purchase package
         *
         * @return void
         */
        function bookingpress_backend_get_customer_package_list_func(){

            global $tbl_bookingpress_customers,$tbl_bookingpress_package_bookings,$wpdb,$BookingPress,$bookingpress_package;

            $wpnonce               = isset( $_REQUEST['_wpnonce'] ) ? sanitize_text_field( $_REQUEST['_wpnonce'] ) : '';
            $bpa_verify_nonce_flag = wp_verify_nonce( $wpnonce, 'bpa_wp_nonce' );
            $response              = array();
            if ( ! $bpa_verify_nonce_flag ) {
                $response                = array();
                $response['variant']     = 'error';
                $response['title']       = esc_html__( 'Error', 'bookingpress-package' );
                $response['msg']         = esc_html__( 'Sorry, Your request can not be processed due to security reason.', 'bookingpress-package' );
                echo wp_json_encode( $response );
                die();
            }

            $response                = array();
            $response['variant']     = 'error';
            $response['title']       = esc_html__( 'Error', 'bookingpress-package' );
            $response['msg']         =  esc_html__( 'Something Wrong.', 'bookingpress-package' );
            $response['bookingpress_customer_package_list'] = array();

            $bookingpress_customer_package_list = array();
            $appointment_form_data = isset($_POST['appointment_data_obj'])?$_POST['appointment_data_obj']:''; //phpcs:ignore 
            if(!empty($appointment_form_data)){
                $_POST['appointment_data_obj'] = json_decode( stripslashes_deep( $_POST['appointment_data_obj'] ), true ); //phpcs:ignore
                $appointment_form_data = !empty( $_POST['appointment_data_obj'] ) ? array_map(array( $BookingPress, 'appointment_sanatize_field' ), $_POST['appointment_data_obj']) : array(); //phpcs:ignore  
            }           
            $selected_service_id = (isset($_POST['service_id']))?sanitize_text_field($_POST['service_id']):'';
            $bookingpress_customer_id = (isset($_POST['selected_customer']))?sanitize_text_field($_POST['selected_customer']):'';         
            $bookingpress_wpuser_id = '';
            $appointment_update_id = (isset($_POST['appointment_update_id']))?intval($_POST['appointment_update_id']):'';
            $enable_custom_service_duration = (isset($_POST['appointment_data_obj']['enable_custom_service_duration']))?sanitize_text_field($_POST['appointment_data_obj']['enable_custom_service_duration']):'';

            if(!empty($bookingpress_customer_id) && !empty($selected_service_id) && empty($enable_custom_service_duration)){

                $get_current_user_customer = $wpdb->get_row( $wpdb->prepare( "SELECT bookingpress_wpuser_id FROM {$tbl_bookingpress_customers} WHERE bookingpress_customer_id = %d ", $bookingpress_customer_id ), ARRAY_A); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- Reason: $tbl_bookingpress_customers is table name defined globally. False Positive alarm                
                if(!empty($get_current_user_customer)){
                    $bookingpress_wpuser_id = (isset($get_current_user_customer['bookingpress_wpuser_id']) && !empty($get_current_user_customer['bookingpress_wpuser_id']))?$get_current_user_customer['bookingpress_wpuser_id']:'';
                    if($bookingpress_wpuser_id){

                        $bookingpress_current_date = date('Y-m-d');
                        $bookingpress_get_purchase_packages = $wpdb->get_results( $wpdb->prepare( "SELECT bookingpress_package_id,bookingpress_package_no,bookingpress_package_services,bookingpress_package_name FROM {$tbl_bookingpress_package_bookings} WHERE bookingpress_package_expiration_date > %s AND bookingpress_login_user_id = %d AND (bookingpress_package_booking_status = %s OR bookingpress_package_booking_status = %s)", $bookingpress_current_date,$bookingpress_wpuser_id,'1','4'), ARRAY_A); //phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared --Reason: $tbl_bookingpress_package_bookings is a table name. false alarm		
                        if(!empty($bookingpress_get_purchase_packages)){
                            $package_added_id = array('none');
                            foreach($bookingpress_get_purchase_packages as $packge_data){
                                $packge_services = (isset($packge_data['bookingpress_package_services']))?$packge_data['bookingpress_package_services']:'';
                                if(!empty($packge_services)){
                                    $packge_services_arr = json_decode($packge_services,true);
                                    $has_package_added = false;
                                    if(!empty($packge_services_arr)){
                                        $package_service_final_arr = array();
                                        foreach($packge_services_arr as $key=>$pack_single_serv){    

                                            $packge_services_arr[$key]['available_no_of_appointments'] = $pack_single_serv['bookingpress_no_of_appointments'];
                                            $bookingpress_available_no_of_appointments = $packge_services_arr[$key]['available_no_of_appointments'];
                                            $bookingpress_service_id = $packge_services_arr[$key]['bookingpress_service_id'];
                                            if($bookingpress_service_id == $selected_service_id){
                                                $total_purchase = $bookingpress_package->get_package_service_purchase_count($packge_data['bookingpress_package_no'],$bookingpress_service_id,$appointment_update_id);
                                                if($bookingpress_available_no_of_appointments > $total_purchase){
                                                    $has_package_added = true;                                                
                                                }    
                                            }

                                        }                                                                                
                                        if($has_package_added){
                                            if(!in_array($packge_data['bookingpress_package_id'],$package_added_id)){
                                                $bookingpress_customer_package_list[] = $packge_data;
                                                $package_added_id[] = $packge_data['bookingpress_package_id'];    
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }            
            if(!empty($bookingpress_customer_package_list)){
                $response['variant']   = 'success';
                $response['bookingpress_customer_package_list'] = $bookingpress_customer_package_list;
            }
            echo wp_json_encode( $response );
            die();
        }

        /**
         * Function for add backend vue method
         *
         * @return void
         */
        function bookingpress_appointment_add_dynamic_vue_methods_func(){
            $bookingpress_nonce = wp_create_nonce('bpa_wp_nonce');   
        ?>        
            bookingpress_backend_remove_applied_package(calculation = true){
                const vm = this;
                vm.appointment_formdata.bookingpress_package_applied_data = "";
                vm.appointment_formdata.selected_package = "";
                if(calculation){
                    vm.bookingpress_admin_get_final_step_amount();
                }                
            },    
            bookingpress_backend_redeem_package_for_appointment(){
                const vm = this;
                vm.package_apply_loader = '1';
                vm.appointment_formdata.bookingpress_package_applied_data = "";
                var bookingpress_apply_package_data = {};
                bookingpress_apply_package_data.action = "bookingpress_redeem_package_backend_request";
                bookingpress_apply_package_data.appointment_details = JSON.stringify(vm.appointment_formdata);
                var bkp_wpnonce_pre = "<?php echo  esc_html($bookingpress_nonce); ?>";
                bookingpress_apply_package_data._wpnonce = bkp_wpnonce_pre;
                axios.post( appoint_ajax_obj.ajax_url, Qs.stringify( bookingpress_apply_package_data ) )
                .then( function (response) {                    
                    vm.package_applied_status = response.data.variant;
                    if(response.data.variant == "error"){

                        vm.package_apply_code_msg = response.data.msg;                        
                        vm.appointment_formdata.bookingpress_package_applied_data ="";
                        vm.bookingpress_admin_get_final_step_amount();

                    } else {
                          
                        vm.package_apply_code_msg = "";
                        vm.appointment_formdata.bookingpress_package_applied_data = response.data.bookingpress_package_applied_data;                      
                        vm.bpa_package_apply_disabled = 1;
                        if(vm.is_coupon_enable == 1){
                            vm.bookingpress_remove_coupon_code();
                        }                        
                        vm.bookingpress_admin_get_final_step_amount();

                    }                    
                }.bind(this) )
                .catch( function (error) {
                    vm.bookingpress_set_error_msg(error);
                });                


            },
            bookingpress_backend_get_customer_package_list(){                   
                const vm = this;                
                vm.package_apply_loader = '1';
                const CustformData = new FormData();                
                var selected_service_id = vm.appointment_formdata.appointment_selected_service;
                var selected_customer = vm.appointment_formdata.appointment_selected_customer;
                let bookingpress_appointment_form_data = vm.appointment_formdata;
                var postData = { action:"bookingpress_backend_get_customer_package_list", service_id: selected_service_id, selected_customer: selected_customer, _wpnonce:"<?php echo  esc_html($bookingpress_nonce); ?>", };
                postData.appointment_data_obj = JSON.stringify(vm.appointment_formdata);
                axios.post( appoint_ajax_obj.ajax_url, Qs.stringify( postData ) )
                .then( function (response) {
                    if(response.data.variant == "success"){                        
                        vm.bookingpress_customer_package_list = response.data.bookingpress_customer_package_list;                        
                    }else{                                                               
                        vm.bookingpress_customer_package_list = [];
                        vm.bookingpress_disable_package_list = 'true';                        
                    }
                    vm.package_apply_loader = 0;
                }.bind(this) )
                .catch( function (error) {
                    console.log(error);
                    vm.package_apply_loader = 0;
                    vm.bookingpress_customer_package_list = [];
                });
            },
        <?php 
        }

        function bookingress_backend_after_select_customer_func(){
        ?>  
            vm.bookingpress_backend_remove_applied_package();                      
            vm.bookingpress_backend_get_customer_package_list();
        <?php 
        }

        function bookingpress_change_backend_service_func(){
        ?>   
            vm.bookingpress_backend_remove_applied_package();         
            vm.bookingpress_backend_get_customer_package_list();
        <?php 
        }

        function bookingpress_add_appointment_after_sub_total_backend_func(){
        ?>
        <div class="bpa-aaf-pd__coupon-module" v-if="bookingpress_allow_package_apply == 1">
            <div class="bpa-fm--bs-amount-item bpa-is-coupon-applied bpa-is-hide-stroke" v-if="appointment_formdata.bookingpress_package_applied_data != ''">
                <el-row>
                    <el-col :xs="20" :sm="20" :md="24" :lg="22" :xl="22">
                        <h4>
                            <?php esc_html_e( 'Package Redemption', 'bookingpress-package' ); ?>
                            <span>{{ appointment_formdata.bookingpress_package_applied_data.bookingpress_package_name }}<a class="material-icons-round bpa-pack-remov-icon" @click="bookingpress_backend_remove_applied_package">close</a></span>		
                        </h4>
                    </el-col>
                    <el-col :xs="04" :sm="04" :md="24" :lg="2" :xl="2">
                        <h4 class="is-price">-{{ appointment_formdata.bookingpress_package_discount_amount_with_currency }}</h4>
                    </el-col>
                </el-row>
            </div>            
            <div class="bpa-fm--bs__coupon-module-textbox bpa-fm--bs__package-module-box" v-if="appointment_formdata.bookingpress_package_applied_data == '' && ( typeof appointment_formdata.is_waiting_list == 'undefined' || appointment_formdata.is_waiting_list == false )">
                <div class="bpa-cmt__left">
                    <span class="bpa-front-form-label">Redeem Package</span>
                </div> 
                <div class="bpa-cmt__right">
                    <div class="bpa-cmt__right-inner">
                        <div>
                            <el-select :disabled="(bookingpress_customer_package_list.length == 0)?true:false" class="bpa-form-control" placeholder="<?php esc_html_e( 'Select Package', 'bookingpress-package' ); ?>" v-model="appointment_formdata.selected_package" popper-class="bpa-fm--service__advance-options-popper">
                                <el-option v-for="(item,key) in bookingpress_customer_package_list" :label="item.bookingpress_package_name" :value="item.bookingpress_package_id">{{ item.bookingpress_package_name }}</el-option>
                            </el-select>  

                        </div>
                        <el-button :disabled="(bookingpress_customer_package_list.length == 0)?true:false" class="bpa-btn bpa-btn__medium bpa-btn--primary bpa-package-redeem" :class="(package_apply_loader == '1') ? 'bpa-front-btn--is-loader' : ''" @click="(bookingpress_customer_package_list.length == 0)?'':bookingpress_backend_redeem_package_for_appointment()">
                            <span class="bpa-btn__label"><?php esc_html_e( 'Redeem', 'bookingpress-package' ); ?></span>                    
                            <div class="bpa-front-btn--loader__circles">
                                <div></div>
                                <div></div>
                                <div></div>
                            </div>
                        </el-button>
                    </div>
                    <div class="bpa-bs__coupon-validation --is-error" v-if="package_apply_code_msg != ''">
                           <span class="material-icons-round">error_outline</span>
                           <p>{{ package_apply_code_msg }}</p>
                    </div>                    
                </div> 
            </div>
        </div>
        <?php 
        }

        function bookingpress_modify_backend_appointment_data_fields_func($bookingpress_appointment_vue_data_fields){


            $bookingpress_appointment_vue_data_fields['package_apply_loader'] = 0;
            $bookingpress_appointment_vue_data_fields['bpa_package_apply_disabled'] = 0;
            $bookingpress_appointment_vue_data_fields['package_applied_status'] = '';            
            $bookingpress_appointment_vue_data_fields['package_apply_code_msg'] = '';
            $bookingpress_appointment_vue_data_fields['bookingpress_disable_package_list'] = 'true';


            //$bookingpress_appointment_vue_data_fields['appointment_formdata']['recurring_edit_index'] = '';

            $bookingpress_appointment_vue_data_fields['appointment_formdata']['package_redeem_amount'] = 0;
            $bookingpress_appointment_vue_data_fields['appointment_formdata']['selected_package'] = "";
            $bookingpress_appointment_vue_data_fields['bookingpress_customer_package_list'] = array();            
            $bookingpress_appointment_vue_data_fields['bookingpress_allow_package_apply'] = 1;                        
            $bookingpress_appointment_vue_data_fields['appointment_formdata']['bookingpress_package_applied_data'] = '';
            $bookingpress_appointment_vue_data_fields['appointment_formdata']['bookingpress_package_discount_amount'] = 0;
            $bookingpress_appointment_vue_data_fields['appointment_formdata']['bookingpress_package_discount_amount_with_currency'] = '';


            return $bookingpress_appointment_vue_data_fields;

        }



    }

	global $bookingpress_package_appointment_book_backend;
	$bookingpress_package_appointment_book_backend = new bookingpress_package_appointment_book_backend();
}