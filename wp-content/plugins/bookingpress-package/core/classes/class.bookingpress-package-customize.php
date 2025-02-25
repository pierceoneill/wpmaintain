<?php
if (!class_exists('bookingpress_package_customize')) {
	class bookingpress_package_customize Extends BookingPress_Core {

        function __construct(){

            global $wp, $wpdb, $tbl_bookingpress_packages, $tbl_bookingpress_package_services, $tbl_bookingpress_package_images, $BookingPress, $tbl_bookingpress_package_bookings,$bookingpress_package;

            /*
            $tbl_bookingpress_packages = $wpdb->prefix.'bookingpress_packages';
            $tbl_bookingpress_package_services = $wpdb->prefix.'bookingpress_package_services';
            $tbl_bookingpress_package_images = $wpdb->prefix.'bookingpress_package_images';
            $tbl_bookingpress_package_bookings = $wpdb->prefix.'bookingpress_package_bookings';
            */
            
            $package_addon_working = $bookingpress_package->bookingpress_check_package_addon_requirement();

            if( is_plugin_active( 'bookingpress-appointment-booking-pro/bookingpress-appointment-booking-pro.php' ) && !empty( $BookingPress->bpa_pro_plugin_version() ) && version_compare( $BookingPress->bpa_pro_plugin_version(), '3.2', '>=' ) && $package_addon_working){

                add_action('bookingpress_customize_tab_menu_after',array($this,'bookingpress_customize_tab_menu_after_func'),10);
                add_action('bookingpress_customize_tab_after',array($this,'bookingpress_customize_tab_after_func'),10);
                add_filter('bookingpress_customize_add_dynamic_data_fields',array($this,'bookingpress_customize_add_dynamic_data_fields_func'));
                //add_action('bookingpress_customize_dynamic_on_load_methods', array( $this, 'bookingpress_dynamic_onload_methods_func' ),10);
                
                /* Add Function For Package Field Add */
                add_action('bookingpress_custom_field_setting_after',array($this,'bookingpress_custom_field_setting_after_func'),10);
                add_filter( 'bookingpress_modify_field_data_before_load', array( $this, 'bookingpress_modify_form_field_data' ), 15, 2 );
                add_filter( 'bookingpress_modify_form_field_data_before_save', array( $this, 'bookingpress_modify_form_field_data_before_save_func' ), 10, 2 );

                /*Customize save Package Bookings Settings*/
                add_action( 'bookingpress_save_customize_other_settings_data', array($this, 'bookingpress_save_customize_other_settings_data_func'));
                add_action( 'bookingpress_customize_dynamic_vue_methods', array( $this, 'bookingpress_package_dynamic_vue_methods_func' ), 8);
                add_action( 'wp_ajax_bookingpress_save_customize_package_settings', array( $this, 'bookingpress_save_customize_package_settings_func' ));
    			add_filter( 'bookingpress_modify_capability_data', array($this, 'bookingpress_modify_package_capability_data_func'), 10, 1);

                add_action( 'bookingpress_add_my_booking_form_labels', array($this, 'bpa_add_my_booking_form_labels_my_packages'), 10 );
                add_filter( 'bookingpress_get_my_booking_customize_data_filter', array( $this, 'bookingpress_get_my_booking_package_customize_data_filter_func' ) );

                add_action( 'bookingpress_generate_exteranl_css_outside', array( $this, 'bookingpress_generate_package_add_on_custom_css' ), 10, 3 );

                if(is_plugin_active('bookingpress-multilanguage/bookingpress-multilanguage.php')) {
                    add_action('bookingpress_customize_language_traslation_popup_outside', array($this, 'bookingpress_customize_language_traslation_popup_outside_func'));
                    add_filter('bookingpress_modified_language_translate_fields', array($this, 'bookingpress_modified_language_translate_fields_package_form_func'), 15);                     
                }
            }
        }
        
       function bookingpress_modified_language_translate_fields_package_form_func($bookingpress_all_language_translation_fields){

            $bookingpress_all_language_translation_fields['customized_package_booking_field_labels'] = 
            array(
                'package_form_title' => array('field_type'=>'text','field_label'=>__('Package Form Label', 'bookingpress-package'),'save_field_type'=>'package_booking_form'),
                'package_search_placeholder' => array('field_type'=>'text','field_label'=>__('Package search placeholder', 'bookingpress-package'),'save_field_type'=>'package_booking_form'),
                'package_search_button' => array('field_type'=>'text','field_label'=>__('Search button', 'bookingpress-package'),'save_field_type'=>'package_booking_form'),
                'no_package_found_msg' => array('field_type'=>'text','field_label'=>__('No Package Found', 'bookingpress-package'),'save_field_type'=>'package_booking_form'),
                'package_buy_now_nutton_text' => array('field_type'=>'text','field_label'=>__('Buy now button', 'bookingpress-package'),'save_field_type'=>'package_booking_form'),
                'package_services_include_text' => array('field_type'=>'text','field_label'=>__('Services Includes text', 'bookingpress-package'),'save_field_type'=>'package_booking_form'),
                'package_services_show_more_text' => array('field_type'=>'text','field_label'=>__('Services Show More text', 'bookingpress-package'),'save_field_type'=>'package_booking_form'),
                'package_services_show_less_text' => array('field_type'=>'text','field_label'=>__('Services Show Less text', 'bookingpress-package'),'save_field_type'=>'package_booking_form'),
                'package_desc_show_less_text' => array('field_type'=>'text','field_label'=>__('Package Description Show Less', 'bookingpress-package'),'save_field_type'=>'package_booking_form'), 
                'package_desc_read_more_text' => array('field_type'=>'text','field_label'=>__('Package Description Read More', 'bookingpress-package'),'save_field_type'=>'package_booking_form'),
                'package_go_back_button_text' => array('field_type'=>'text','field_label'=>__('Go back button', 'bookingpress-package'),'save_field_type'=>'package_booking_form'),
                'package_month_text' => array('field_type'=>'text','field_label'=>__('Package month label', 'bookingpress-package'),'save_field_type'=>'package_booking_form'), 
                'package_months_text' => array('field_type'=>'text','field_label'=>__('Package months label', 'bookingpress-package'),'save_field_type'=>'package_booking_form'),
                'package_year_text' => array('field_type'=>'text','field_label'=>__('Package year label', 'bookingpress-package'),'save_field_type'=>'package_booking_form'),
                'package_day_text' => array('field_type'=>'text','field_label'=>__('Package day label', 'bookingpress-package'),'save_field_type'=>'package_booking_form'),
                'package_days_text' => array('field_type'=>'text','field_label'=>__('Package days label', 'bookingpress-package'),'save_field_type'=>'package_booking_form'),                
            ); 
            
            
            
            $bookingpress_all_language_translation_fields['customized_package_booking_user_detail_step_label'] = 
            array(
                'user_details_step_label' => array('field_type'=>'text','field_label'=>__('User details step', 'bookingpress-package'),'save_field_type'=>'package_booking_form'),
            );
            
            $bookingpress_all_language_translation_fields['customized_package_booking_login_related_labels'] = 
            array(
                'login_form_title_label' => array('field_type'=>'text','field_label'=>__('Login form title', 'bookingpress-package'),'save_field_type'=>'package_booking_form'),
                'login_form_username_field_label' => array('field_type'=>'text','field_label'=>__('Username / Email field label', 'bookingpress-package'),'save_field_type'=>'package_booking_form'),
                'login_form_username_field_placeholder' => array('field_type'=>'text','field_label'=>__('Username / Email field Placeholder', 'bookingpress-package'),'save_field_type'=>'package_booking_form'),
                'login_form_password_field_label' => array('field_type'=>'text','field_label'=>__('Password label', 'bookingpress-package'),'save_field_type'=>'package_booking_form'), 
                'login_form_password_field_placeholder' => array('field_type'=>'text','field_label'=>__('Password field Placeholder', 'bookingpress-package'),'save_field_type'=>'package_booking_form'),
                'login_form_username_required_field_label' => array('field_type'=>'text','field_label'=>__('User name required field label', 'bookingpress-package'),'save_field_type'=>'package_booking_form'),
                'login_form_password_required_field_label' => array('field_type'=>'text','field_label'=>__('Password required field label', 'bookingpress-package'),'save_field_type'=>'package_booking_form'),
                'remember_me_field_label' => array('field_type'=>'text','field_label'=>__('Remember Me field label', 'bookingpress-package'),'save_field_type'=>'package_booking_form'),
                'login_button_label' => array('field_type'=>'text','field_label'=>__('Login button label', 'bookingpress-package'),'save_field_type'=>'package_booking_form'),
                'forgot_password_link_label' => array('field_type'=>'text','field_label'=>__('Forgot Password link label', 'bookingpress-package'),'save_field_type'=>'package_booking_form'),
                'login_error_message_label' => array('field_type'=>'text','field_label'=>__('Error message label', 'bookingpress-package'),'save_field_type'=>'package_booking_form'),
                'login_form_signup_link_text' => array('field_type'=>'text','field_label'=>__('SignUp link label', 'bookingpress-package'),'save_field_type'=>'package_booking_form'),
                'login_form_dont_have_acc_text' => array('field_type'=>'text','field_label'=>__('Don\'t have an account label', 'bookingpress-package'),'save_field_type'=>'package_booking_form'),
            );
            
            $bookingpress_all_language_translation_fields['customized_package_booking_forgot_password_labels'] = 
            array(
                'forgot_password_form_title' => array('field_type'=>'text','field_label'=>__('Forgot Password form title', 'bookingpress-package'),'save_field_type'=>'package_booking_form'),
                'forgot_password_email_address_field_label' => array('field_type'=>'text','field_label'=>__('Email address field label', 'bookingpress-package'),'save_field_type'=>'package_booking_form'),
                'forgot_password_email_address_placeholder' => array('field_type'=>'text','field_label'=>__('Email address placeholder label', 'bookingpress-package'),'save_field_type'=>'package_booking_form'),
                'forgot_password_email_required_field_label' => array('field_type'=>'text','field_label'=>__('Email required field label', 'bookingpress-package'),'save_field_type'=>'package_booking_form'),
                'forgot_password_button_label' => array('field_type'=>'text','field_label'=>__('Forgot password button label', 'bookingpress-package'),'save_field_type'=>'package_booking_form'),
                'forgot_password_error_message' => array('field_type'=>'text','field_label'=>__('Error message label', 'bookingpress-package'),'save_field_type'=>'package_booking_form'),
                'forgot_password_success_message_label' => array('field_type'=>'text','field_label'=>__('Success message label', 'bookingpress-package'),'save_field_type'=>'package_booking_form'),
                'forgot_password_sing_in_link_label' => array('field_type'=>'text','field_label'=>__('Sign In Link Label', 'bookingpress-package'),'save_field_type'=>'package_booking_form'),
            ); 
        
            $bookingpress_all_language_translation_fields['customized_package_booking_signup_form_labels'] = 
            array(
                'signup_account_form_title' => array('field_type'=>'text','field_label'=>__('Signup form title', 'bookingpress-package'),'save_field_type'=>'package_booking_form'),
                'signup_account_fullname_label' => array('field_type'=>'text','field_label'=>__('Full name field label', 'bookingpress-package'),'save_field_type'=>'package_booking_form'),
                'signup_account_email_label' => array('field_type'=>'text','field_label'=>__('Email field label', 'bookingpress-package'),'save_field_type'=>'package_booking_form'),
                'signup_account_mobile_number_label' => array('field_type'=>'text','field_label'=>__('Mobile Number field label', 'bookingpress-package'),'save_field_type'=>'package_booking_form'),
                'signup_account_password_label' => array('field_type'=>'text','field_label'=>__('Password field label', 'bookingpress-package'),'save_field_type'=>'package_booking_form'),
                'signup_account_fullname_placeholder' => array('field_type'=>'text','field_label'=>__('Full name field placeholder', 'bookingpress-package'),'save_field_type'=>'package_booking_form'),
                'signup_account_email_placeholder' => array('field_type'=>'text','field_label'=>__('Email field placeholder', 'bookingpress-package'),'save_field_type'=>'package_booking_form'),
                'signup_account_password_placeholder' => array('field_type'=>'text','field_label'=>__('Password field placeholder', 'bookingpress-package'),'save_field_type'=>'package_booking_form'),
                'signup_account_fullname_required_message' => array('field_type'=>'text','field_label'=>__('Full name required message', 'bookingpress-package'),'save_field_type'=>'package_booking_form'),
                'signup_account_email_required_message' => array('field_type'=>'text','field_label'=>__('Email required message', 'bookingpress-package'),'save_field_type'=>'package_booking_form'),
                'signup_account_mobile_number_required_message' => array('field_type'=>'text','field_label'=>__('Mobile Number required message', 'bookingpress-package'),'save_field_type'=>'package_booking_form'),
                'signup_account_password_required_message' => array('field_type'=>'text','field_label'=>__('Password required message', 'bookingpress-package'),'save_field_type'=>'package_booking_form'),
                
                'signup_wrong_email_message' => array('field_type'=>'text','field_label'=>__('Password required message', 'bookingpress-package'),'save_field_type'=>'package_booking_form'),
                'signup_email_exists' => array('field_type'=>'text','field_label'=>__('Password required message', 'bookingpress-package'),'save_field_type'=>'package_booking_form'),

                'signup_form_button_title' => array('field_type'=>'text','field_label'=>__('Signup button title', 'bookingpress-package'),'save_field_type'=>'package_booking_form'),
                'signup_form_already_have_acc_text' => array('field_type'=>'text','field_label'=>__('Already have account text', 'bookingpress-package'),'save_field_type'=>'package_booking_form'),
                'signup_form_login_link_text' => array('field_type'=>'text','field_label'=>__('Login link title', 'bookingpress-package'),'save_field_type'=>'package_booking_form'),                
            );       
        
            $bookingpress_all_language_translation_fields['customized_package_booking_basic_details_labels'] = 
            array(
                'basic_details_form_title' => array('field_type'=>'text','field_label'=>__('Basic Details form title', 'bookingpress-package'),'save_field_type'=>'package_booking_form'),
                'basic_details_submit_button_title' => array('field_type'=>'text','field_label'=>__('Submit button title', 'bookingpress-package'),'save_field_type'=>'package_booking_form'),
            );

            $bookingpress_all_language_translation_fields['customized_package_booking_make_payment_labels'] = 
            array(
                'make_payment_tab_title' => array('field_type'=>'text','field_label'=>__('Make payment tab title', 'bookingpress-package'),'save_field_type'=>'package_booking_form'),
                'make_payment_form_title' => array('field_type'=>'text','field_label'=>__('Make payment form title', 'bookingpress-package'),'save_field_type'=>'package_booking_form'),
                'make_payment_subtotal_text' => array('field_type'=>'text','field_label'=>__('Subtotal text', 'bookingpress-package'),'save_field_type'=>'package_booking_form'),
                'make_payment_total_amount_text' => array('field_type'=>'text','field_label'=>__('Total amount text', 'bookingpress-package'),'save_field_type'=>'package_booking_form'),
                'make_payment_select_payment_method_text' => array('field_type'=>'text','field_label'=>__('Select payment method text', 'bookingpress-package'),'save_field_type'=>'package_booking_form'),
                'make_payment_buy_package_btn_text' => array('field_type'=>'text','field_label'=>__('Buy package button text', 'bookingpress-package'),'save_field_type'=>'package_booking_form'),
            );

            $bookingpress_all_language_translation_fields['customized_package_booking_summary_step_labels'] = 
            array(
                'summary_step_title' => array('field_type'=>'text','field_label'=>__('Summary step', 'bookingpress-package'),'save_field_type'=>'package_booking_form'),
                'summary_step_form_title' => array('field_type'=>'text','field_label'=>__('Summary form title', 'bookingpress-package'),'save_field_type'=>'package_booking_form'),
                'summary_tab_booking_id_text' => array('field_type'=>'text','field_label'=>__('Booking Id', 'bookingpress-package'),'save_field_type'=>'package_booking_form'),
                'summary_tab_package_booked_success_message' => array('field_type'=>'textarea','field_label'=>__('Package booked success message', 'bookingpress-package'),'save_field_type'=>'package_booking_form'),
                'summary_tab_package_booking_information_sent_message' => array('field_type'=>'textarea','field_label'=>__('Package booking information sent message', 'bookingpress-package'),'save_field_type'=>'package_booking_form'),
                'summary_tab_package_title_text' => array('field_type'=>'text','field_label'=>__('Package title', 'bookingpress-package'),'save_field_type'=>'package_booking_form'),
                'summary_tab_customer_title' => array('field_type'=>'text','field_label'=>__('Customer Name title', 'bookingpress-package'),'save_field_type'=>'package_booking_form'),
                'summary_tab_book_appointment_btn_text' => array('field_type'=>'text','field_label'=>__('Book Appointment title', 'bookingpress-package'),'save_field_type'=>'package_booking_form'),
                'pkg_card_details_text' => array('field_type'=>'text','field_label'=>__('Card details title', 'bookingpress-package'),'save_field_type'=>'package_booking_form'),
                'pkg_card_name_text' => array('field_type'=>'text','field_label'=>__('Card name placeholder', 'bookingpress-package'),'save_field_type'=>'package_booking_form'),
                'pkg_card_number_text' => array('field_type'=>'text','field_label'=>__('Card number placeholder', 'bookingpress-package'),'save_field_type'=>'package_booking_form'),
                'pkg_expire_month_text' => array('field_type'=>'text','field_label'=>__('Expire month placeholder', 'bookingpress-package'),'save_field_type'=>'package_booking_form'),
                'pkg_expire_year_text' => array('field_type'=>'text','field_label'=>__('Expire year placeholder', 'bookingpress-package'),'save_field_type'=>'package_booking_form'),
                'pkg_cvv_text' => array('field_type'=>'text','field_label'=>__('Cvv placeholder', 'bookingpress-package'),'save_field_type'=>'package_booking_form'),
                'package_tax_title' => array('field_type'=>'text','field_label'=>__('Tax title', 'bookingpress-package'),'save_field_type'=>'package_booking_form'),
                'pkg_paypal_text' => array('field_type'=>'text','field_label'=>__('PayPal payment title', 'bookingpress-package'),'save_field_type'=>'package_booking_form'),
                'package_purchase_limit_message' => array('field_type'=>'text','field_label'=>__('Package purchase limit message', 'bookingpress-package'),'save_field_type'=>'package_booking_form'),
            ); 

            $bookingpress_all_language_translation_fields['customized_package_booking_summary_step_labels'] = apply_filters('bookingpress_customized_package_booking_summary_step_labels_translate',$bookingpress_all_language_translation_fields['customized_package_booking_summary_step_labels']);

            $bookingpress_all_language_translation_fields = apply_filters('bookingpress_customized_package_booking_all_language_translation_fields',$bookingpress_all_language_translation_fields);

            return $bookingpress_all_language_translation_fields;
        }

        function bookingpress_customize_language_traslation_popup_outside_func(){
            ?>
            <el-drawer custom-class="bpa-drawer__language-translate" :visible.sync="open_customize_package_booking_form_translate_language">
	            <div class="bpa-dlt__heading">
		            <h3><?php esc_html_e( 'Language Translate', 'bookingpress-package' ); ?></h3>
	            </div>
	            <div class="bpa-dlt__body">
                    <div v-if="empty_selected_language == 0" class="bpa-dlt-language-items">
			            <div @click="change_customize_current_language(field_language_ind)" v-for="(select_lang, field_language_ind) in bookingpress_get_selected_languages" :class="(bookingpress_current_selected_lang == field_language_ind)?'__bpa-is-active':''"   class="bpa-li__item">				
				            <img v-if="select_lang.flag_image != ''" :src="select_lang.flag_image" :alt="select_lang.english_name">
				            <p>{{select_lang.english_name}}</p> 
			            </div>
		            </div>
                    <div class="bpa-dlt-body-module-wrapper">	
                        <div v-if="empty_selected_language == 0" v-for="(field_language, field_language_ind) in bookingpress_get_selected_languages">
                            <div v-if="bookingpress_current_selected_lang == field_language_ind" v-for="(lang_fields, lang_field_key) in package_form_language_fields_data[field_language_ind]" class="bpa-bmw__block">
                                <div class="bpa-mw__title">
                                    <h4 v-html="(typeof bookingpress_customize_package_booking_language_section_title != 'undefined' && typeof bookingpress_customize_package_booking_language_section_title[lang_field_key] !== 'undefined')?bookingpress_customize_package_booking_language_section_title[lang_field_key]:''"></h4>
						        </div>
                                <el-form ref="" label-position="top">
                                    <template>
                                        <div class="bpa-mw__form">
                                            <el-form-item v-for="(lang_field_data, lang_field_data_key) in lang_fields">
                                                <template #label>
                                                    <span class="bpa-form-label">{{lang_field_data.field_label}}</span>
                                                </template>
                                                <el-input class="bpa-form-control"  v-model="package_language_data[field_language_ind][lang_field_data.save_field_type][lang_field_data_key]" :type="(lang_field_data.field_type == 'text')?'text':'textarea'" :rows="(lang_field_data.field_type == 'text')?1:5"  :placeholder="lang_field_data.field_label">
                                                </el-input>
                                            </el-form-item>
                                        </div>
                                    </template>
						    </el-form>
                        
                            </div>
                        </div>
                        <?php do_action('bookingpress_multi_language_popup_translate_language_not_found'); ?>		
                    </div>		
                </div>
                <div class="bpa-dlt__footer">
		            <el-button @click="open_customize_package_booking_form_translate_language = false;" class="bpa-btn bpa-btn--primary"><?php esc_html_e( 'Okay', 'bookingpress-package' ); ?></el-button>
	            </div>
            </el-drawer>
            <?php
        }

        function bookingpress_get_my_booking_package_customize_data_filter_func($bookingpress_my_booking_field_settings){
            $bookingpress_my_booking_field_settings['my_package_tab_title'] = '';
            $bookingpress_my_booking_field_settings['my_package_title'] = '';
            $bookingpress_my_booking_field_settings['my_package_search_start_date_title'] = '';
            $bookingpress_my_booking_field_settings['my_package_search_end_date_title'] = '';
            $bookingpress_my_booking_field_settings['my_package_search_package_text_placeholder'] = '';
            $bookingpress_my_booking_field_settings['my_package_search_package_btn_placeholder'] = '';
            $bookingpress_my_booking_field_settings['my_package_id_title'] = '';
            $bookingpress_my_booking_field_settings['my_package_pkg_name_title'] = '';
            $bookingpress_my_booking_field_settings['my_package_pkg_remin_appointment_title'] = '';
            $bookingpress_my_booking_field_settings['my_package_pkg_expoire_on_title'] = '';
            $bookingpress_my_booking_field_settings['my_package_pkg_payment_title'] = '';
            $bookingpress_my_booking_field_settings['my_package_booking_id_title'] = '';
            $bookingpress_my_booking_field_settings['my_package_pkg_purchase_date_title'] = '';
            $bookingpress_my_booking_field_settings['my_package_pkg_expiry_date_title'] = '';
            $bookingpress_my_booking_field_settings['my_package_pkg_services_title'] = '';
            $bookingpress_my_booking_field_settings['my_package_service_name_title'] = '';
            $bookingpress_my_booking_field_settings['my_package_service_duration_title'] = '';
            $bookingpress_my_booking_field_settings['my_package_service_remain_appoint_title'] = '';
            $bookingpress_my_booking_field_settings['my_package_pkg_payment_details_title'] = '';
            $bookingpress_my_booking_field_settings['my_package_total_amount_title'] = '';
            $bookingpress_my_booking_field_settings['my_package_appointment_package_name'] = '';
            return $bookingpress_my_booking_field_settings;
        }

        function bpa_add_my_booking_form_labels_my_packages(){
            ?>
            
            <h5><?php esc_html_e('My Package labels', 'bookingpress-package'); ?></h5>
            <div class="bpa-sm--item">
                <label class="bpa-form-label"><?php esc_html_e('My package tab title', 'bookingpress-package'); ?></label>
                <el-input v-model="my_booking_field_settings.my_package_tab_title" class="bpa-form-control"></el-input>
            </div> 
            <div class="bpa-sm--item">
                <label class="bpa-form-label"><?php esc_html_e('My package title', 'bookingpress-package'); ?></label>
                <el-input v-model="my_booking_field_settings.my_package_title" class="bpa-form-control"></el-input>
            </div> 
            <div class="bpa-sm--item">
                <label class="bpa-form-label"><?php esc_html_e('Start date placeholder', 'bookingpress-package'); ?></label>
                <el-input v-model="my_booking_field_settings.my_package_search_start_date_title" class="bpa-form-control"></el-input>
            </div> 
            <div class="bpa-sm--item">
                <label class="bpa-form-label"><?php esc_html_e('End date placeholder', 'bookingpress-package'); ?></label>
                <el-input v-model="my_booking_field_settings.my_package_search_end_date_title" class="bpa-form-control"></el-input>
            </div> 
            <div class="bpa-sm--item">
                <label class="bpa-form-label"><?php esc_html_e('Search package placeholder', 'bookingpress-package'); ?></label>
                <el-input v-model="my_booking_field_settings.my_package_search_package_text_placeholder" class="bpa-form-control"></el-input>
            </div> 
            <div class="bpa-sm--item">
                <label class="bpa-form-label"><?php esc_html_e('Apply button', 'bookingpress-package'); ?></label>
                <el-input v-model="my_booking_field_settings.my_package_search_package_btn_placeholder" class="bpa-form-control"></el-input>
            </div> 
            <div class="bpa-sm--item">
                <label class="bpa-form-label"><?php esc_html_e('ID title', 'bookingpress-package'); ?></label>
                <el-input v-model="my_booking_field_settings.my_package_id_title" class="bpa-form-control"></el-input>
            </div> 
            <div class="bpa-sm--item">
                <label class="bpa-form-label"><?php esc_html_e('Package name title', 'bookingpress-package'); ?></label>
                <el-input v-model="my_booking_field_settings.my_package_pkg_name_title" class="bpa-form-control"></el-input>
            </div> 
            <div class="bpa-sm--item">
                <label class="bpa-form-label"><?php esc_html_e('Remaining appointment title', 'bookingpress-package'); ?></label>
                <el-input v-model="my_booking_field_settings.my_package_pkg_remin_appointment_title" class="bpa-form-control"></el-input>
            </div> 
            <div class="bpa-sm--item">
                <label class="bpa-form-label"><?php esc_html_e('Expire on title', 'bookingpress-package'); ?></label>
                <el-input v-model="my_booking_field_settings.my_package_pkg_expoire_on_title" class="bpa-form-control"></el-input>
            </div> 
            <div class="bpa-sm--item">
                <label class="bpa-form-label"><?php esc_html_e('Payment title', 'bookingpress-package'); ?></label>
                <el-input v-model="my_booking_field_settings.my_package_pkg_payment_title" class="bpa-form-control"></el-input>
            </div> 
            <div class="bpa-sm--item">
                <label class="bpa-form-label"><?php esc_html_e('Booking id title', 'bookingpress-package'); ?></label>
                <el-input v-model="my_booking_field_settings.my_package_booking_id_title" class="bpa-form-control"></el-input>
            </div> 
            <div class="bpa-sm--item">
                <label class="bpa-form-label"><?php esc_html_e('Purchase date title', 'bookingpress-package'); ?></label>
                <el-input v-model="my_booking_field_settings.my_package_pkg_purchase_date_title" class="bpa-form-control"></el-input>
            </div> 
            <div class="bpa-sm--item">
                <label class="bpa-form-label"><?php esc_html_e('Expiry date title', 'bookingpress-package'); ?></label>
                <el-input v-model="my_booking_field_settings.my_package_pkg_expiry_date_title" class="bpa-form-control"></el-input>
            </div> 
            <div class="bpa-sm--item">
                <label class="bpa-form-label"><?php esc_html_e('Services title', 'bookingpress-package'); ?></label>
                <el-input v-model="my_booking_field_settings.my_package_pkg_services_title" class="bpa-form-control"></el-input>
            </div> 
            <div class="bpa-sm--item">
                <label class="bpa-form-label"><?php esc_html_e('Service name title', 'bookingpress-package'); ?></label>
                <el-input v-model="my_booking_field_settings.my_package_service_name_title" class="bpa-form-control"></el-input>
            </div> 
            <div class="bpa-sm--item">
                <label class="bpa-form-label"><?php esc_html_e('Service duration title', 'bookingpress-package'); ?></label>
                <el-input v-model="my_booking_field_settings.my_package_service_duration_title" class="bpa-form-control"></el-input>
            </div> 
            <div class="bpa-sm--item">
                <label class="bpa-form-label"><?php esc_html_e('Service remainging appointment title', 'bookingpress-package'); ?></label>
                <el-input v-model="my_booking_field_settings.my_package_service_remain_appoint_title" class="bpa-form-control"></el-input>
            </div> 
            <div class="bpa-sm--item">
                <label class="bpa-form-label"><?php esc_html_e('Payment details title', 'bookingpress-package'); ?></label>
                <el-input v-model="my_booking_field_settings.my_package_pkg_payment_details_title" class="bpa-form-control"></el-input>
            </div> 
            <div class="bpa-sm--item">
                <label class="bpa-form-label"><?php esc_html_e('Total amount title', 'bookingpress-package'); ?></label>
                <el-input v-model="my_booking_field_settings.my_package_total_amount_title" class="bpa-form-control"></el-input>
            </div> 
            <div class="bpa-sm--item">
                <label class="bpa-form-label"><?php esc_html_e('Appointment Package name label', 'bookingpress-package'); ?></label>
                <el-input v-model="my_booking_field_settings.my_package_appointment_package_name" class="bpa-form-control"></el-input>
            </div>             
            <?php
        }


        /**
         * bookingpress_modify_package_capability_data_func
         *
         * @param  mixed $bpa_caps
         * @return void
         */
        function bookingpress_modify_package_capability_data_func($bpa_caps){
            /*Nonce cap for the saving customize package booking setting*/
            $bpa_caps['bookingpress_customize'][] = 'save_package_settings';
            return $bpa_caps;
        }

        function bookingpress_generate_package_add_on_custom_css( $action_name, $bookingpress_custom_data_arr, $bookingpress_customize_css_key ){

            if( empty( $bookingpress_customize_css_key ) ){
                $bookingpress_customize_css_key     = get_option('bookingpress_custom_css_key', true); 
            }

            $bookingpress_customize_css_content = '';

            if( 'bookingpress_save_customize_package_settings' == $action_name ){
                $shortcode_background_color        = $bookingpress_custom_data_arr['package_booking_form']['background_color'];
                $shortcode_footer_background_color = $bookingpress_custom_data_arr['package_booking_form']['footer_background_color'];
                $border_color                      = $bookingpress_custom_data_arr['package_booking_form']['border_color'];
                $primary_color                     = $bookingpress_custom_data_arr['package_booking_form']['primary_color'];
                $primary_alpha_color               = $bookingpress_custom_data_arr['package_booking_form']['primary_background_color'];
                $title_label_color                 = $bookingpress_custom_data_arr['package_booking_form']['label_title_color'];
                $title_font_size                   = '18px';
                $title_font_family                 = $bookingpress_custom_data_arr['package_booking_form']['title_font_family'];
                $title_font_family                 =  $title_font_family == 'Inherit Fonts' ? 'inherit' : $title_font_family;
                $content_color                     = $bookingpress_custom_data_arr['package_booking_form']['content_color'];
                $price_button_text_content_color   = $bookingpress_custom_data_arr['package_booking_form']['price_button_text_color'];                        
                $sub_title_font_size               = '16px';
                $content_font_size                 = '14px';
                $sub_title_color                   = $bookingpress_custom_data_arr['package_booking_form']['sub_title_color'];

                $hex                               = $primary_color;
                list($r, $g, $b)                   = sscanf($hex, '#%02x%02x%02x');
                $box_shadow_color                  = "0 4px 8px rgba($r,$g,$b,0.06), 0 8px 16px rgba($r,$g,$b,0.16)";

                $border_hex                        = $content_color;
                list($r, $g, $b)                   = sscanf($border_hex, '#%02x%02x%02x');
                $placeholder_color                 = "rgba($r,$g,$b,0.75)";

                $border_rgba                 = $border_color;
                list($r, $g, $b)            = sscanf($border_rgba, '#%02x%02x%02x');
                $border_rgba          = "rgba($r,$g,$b,0.1)";

                $bookingpress_customize_css_content .= "     
                    .bpp-frontend-main-container-package .bpp-front-package-detail{
                        border: 1px solid ".$border_color." !important;
                    }                               
                    .bpp-front-data-empty-view .bpa-front-dev__primary-bg{ 
                        fill:".$primary_color." !important;
                    }
                    .bpp-front-data-empty-view .bpa-front-dev__primary-bg{
                        stroke:".$primary_color." !important;
                    }                    
                    .bpa-front-data-empty-view .bpp-front-dev__panel-bg{
                        fill: ". $shortcode_footer_background_color ." !important;
                    }                
                    .bpp-front-data-empty-view{
                        background-color: ". $shortcode_background_color ." !important;
                    }
                    .bpp-front-data-empty-view .bpa-front-dev__form-bg{
                        fill: " . $shortcode_background_color ." !important;
                    }    
                    .bpp-front-data-empty-view .bpa-front-dev__title{
                        font-family: " . $title_font_family . " !important;
                        color: " . $content_color . " !important;
                    }
                ";
               
                $bookingpress_customize_css_content .= ".bpp-frontend-main-container-package, .bpp-custom-datepicker{
                    --bpp-pt-main-green: $primary_color !important;  
                    --bpp-pt-main-green-darker: $primary_color !important;
                    --bpp-pt-main-green-alpha-12: $primary_alpha_color !important;
                }";
                $bookingpress_customize_css_content .= ".bpp-package-list-col .bpp-package-price .bpp-package-discprice,
                    .bpp-package-booking-left-menu .__bpp-is-active, 
                    .bpp-package-booking-left-menu .__bpp-is-active .bpp-tm__item-label,
                    .bpp-front-form-control--radio .el-radio__input.is-checked+.el-radio__label,
                    .el-radio__input.is-checked .el-radio__inner
                    { color: $primary_color !important; }";
                $bookingpress_customize_css_content .= ".bpp-package-list-col .bpp-package-price .bpp-package-realprice{ color: $content_color; }";
                $bookingpress_customize_css_content .= ".bpp-package-list-col .bpp-frontend-main-container-package .bpp-package-service-include-text,
                        .bpp-front-form-control--file-upload,
                        .bpp-front-form-control--file-upload .bpp-fu__btn,
                        .bpp-front-form-control.--bpp-country-dropdown,
                        .bpp-front-form-control.--bpp-country-dropdown .vti__dropdown-list,
                        .bpp-front-form-control.--bpp-country-dropdown .vti__dropdown,
                        .bpp-custom-datepicker,
                        .bpp-custom-datepicker .el-time-panel,
                        .bpp-custom-datepicker .el-time-panel__content::after, 
                        .bpp-custom-datepicker .el-time-panel__content::before,
                        .bpp-custom-datepicker .el-time-panel__footer,
                        .bpp-front-package-detail .bpp-front-form-control input,
                        .bpp-front-package-detail .bpp-front-form-control input:focus,
                        .bpp-front-dialog-body .bpp-front-form-control input,
                        .bpp-front-form-control input:focus,
                        .bpp-front-package-booking-dialog .el-checkbox__inner,
                        .bpp-front-form-control--checkbox .el-checkbox__inner,
                        .bpp-front-form-control--radio .el-radio__inner,
                        .bpp-front-form-control .el-textarea__inner,
                        .bpp-front-form-control .el-textarea__inner:focus
                        { border-color: $border_color !important; }";
                $bookingpress_customize_css_content .= "
                        .bpp-custom-datepicker .el-picker-panel__footer{
                            border-top-color: $border_color !important;
                        }";
                $bookingpress_customize_css_content .= "
                        .bpp-custom-datepicker .el-picker-panel__footer{
                            border-bottom-color: $border_color !important;
                        }";                
                $bookingpress_customize_css_content .= ".bpp-package-list-col .bpp-package-service-load-link, 
                        .bpp-package-list-col .bpp-package-service-load-link:hover,
                        .bpp-front-form-control--checkbox .el-checkbox__input.is-checked + .el-checkbox__label,
                        .el-date-picker__header-label.active,
                        .el-date-picker__header-label:hover,
                        .bpp-custom-dropdown .el-select-dropdown__item.selected,
                        .el-date-table td.today:not(.current),
                        .el-date-table td.today:not(.current) span
                        { color: $primary_color !important; }";
                $bookingpress_customize_css_content .= ".bpp-front-loader .bpp-front-loader-cl-primary,
                        .bpp-show-package-services_load .bpp-package-service-load-link svg
                        { fill: $primary_color !important; }";
                $bookingpress_customize_css_content .= ".bpp-front-loader-cl-primary,
                        .bpp-package-booking-left-menu .bpp-package-menu-item.__bpp-is-active svg,
                        .bpp-package-booking-left-menu .bpp-package-menu-item.__bpp-is-active svg .bpp-ev__vector-primary-color 
                        {  fill: $primary_color !important; }";
                $bookingpress_customize_css_content .= ".bpa-front-btn--primary:focus, .bpp-front-btn--primary:focus,
                        .el-radio__input.is-checked .el-radio__inner,
                        .bpp-front-form-control--file-upload .bpp-fu__btn:hover,
                        .el-date-picker.has-time .el-picker-panel__footer .el-button--default,
                        .el-date-picker.has-time .el-picker-panel__footer .el-button--default:focus,
                        .el-date-picker.has-time .el-picker-panel__footer .el-button--default:hover,
                        .el-date-table td.current:not(.disabled) span
                        {  background-color: $primary_color !important; }";
                $bookingpress_customize_css_content .= "
                        .bpp-front-btn--primary,
                        .bpp-front-btn--primary:hover, 
                        .bpa-front-btn--primary, 
                        .bpa-front-btn--primary:hover,
                        .bpp-frontend-main-container-package .bpp-buy-now-button,
                        .bpp-frontend-main-container-package .bpp-buy-now-button:hover
                        { background:$primary_color !important; }";
                $bookingpress_customize_css_content .= "
                        .bpp-front-btn--primary,
                        .bpp-front-btn--primary:hover, 
                        .bpa-front-btn--primary, 
                        .bpa-front-btn--primary:hover,
                        .bpp-frontend-main-container-package .bpp-buy-now-button,
                        .bpp-frontend-main-container-package .bpp-buy-now-button:hover,
                        .bpa-front-btn--primary:focus,
                        .bpp-front-btn--primary:focus,
                        .el-checkbox__input.is-checked .el-checkbox__inner,
                        .bpp-front-btn--primary:focus,
                        .el-date-picker.has-time .el-picker-panel__footer .el-button--default,
                        .el-date-picker.has-time .el-picker-panel__footer .el-button--default:focus,
                        .el-date-picker.has-time .el-picker-panel__footer .el-button--default:hover,
                        .el-date-picker.has-time .el-time-panel__btn.confirm,
                        .el-radio__inner:hover,
                        .bpa-front-form-control--checkbox .el-checkbox__inner:hover,
                        .el-radio__input.is-checked .el-radio__inner,
                        .bpp-front-form-control--file-upload .bpp-fu__btn:hover
                        { border-color: $primary_color !important; } ";
                $bookingpress_customize_css_content .= "
                        .bpa-front-btn--primary span, 
                        .bpp-front-btn--primary span,
                        .bpp-frontend-main-container-package .bpp-buy-now-button span,
                        .bpp-front-form-control--file-upload .bpp-fu__btn:hover,
                        .el-date-picker.has-time .el-picker-panel__footer .el-button--default,
                        .el-date-table td.current:not(.disabled) span
                        { color: $price_button_text_content_color !important}";
                $bookingpress_customize_css_content .= "
                        .bpp-front-btn--primary svg,
                        .el-date-table td.current:not(.disabled) span
                        { fill: $price_button_text_content_color !important}";
                $bookingpress_customize_css_content .= "
                        .bpp-front-module-heading, .bpp-package-name, .bpp-front-module-heading,
                        .bpp-front-form-control input,
                        .bpp-package-service-include-text,                        
                        .bpp-front-form-control .el-textarea__inner,
                        .bpp-front-form-control.--bpp-country-dropdown .vti__dropdown-item.highlighted strong,
                        .bpp-front-form-control.--bpp-country-dropdown .vti__dropdown-item.highlighted span,
                        .bpp-front-dialog-body .bpp-packge-name,
                        .bpp-is-total-row .bpp-bs-ai__item span,
                        .bpp-front-package-detail .bpp-displaying-total-package-num,
                        .el-picker-panel__content .el-date-table td:not(.next-month):not(.prev-month):not(.today):not(.current) span,
                        .el-date-picker__header-label,
                        .bpp-summary-item-detail,
                        .bpp-summary-bkid-success-msg,
                        .bpp-summary-id-detail .bpp-summary-bkid .bpp-front-pb-id,
                        .el-date-picker__time-header .el-input .el-input__inner,
                        .el-date-picker.has-time .el-time-spinner__item.active:not(.disabled)
                        { color: $title_label_color !important; }";
                $bookingpress_customize_css_content .= "
                        .bpp-package-duration, 
                        .bpp-front-form-label,
                        .el-form-item__label span,
                        .bpp-package-booking-left-menu .bpp-tm__item-label,
                        .bpp-frontend-main-container-package .bpp-package-list-col .bpp-package-service-nm,
                        .bpp-frontend-main-container-package .bpp-package-list-col .bpp-package-service-dur,
                        .bpp-package-service-no-app,
                        .bpp-front-form-control.--bpp-country-dropdown .vti__dropdown-item strong,
                        .bpp-front-form-control.--bpp-country-dropdown .vti__dropdown-item span,
                        .bpp-front-btn.bpp-front-btn--borderless,
                        .bpp-front-module--bs-amount-details .bpp-is-total-row .bpp-fm-tr__tax-included-label,
                        .bpp-front-module--bs-amount-details .--bpp-is-dpm-total-item .bpp-fm-tr__tax-included-label,
                        .bpp-front-module--bs-amount-details .bpp-bs-ai__item,
                        .bpp-front-cdf__title,
                        .bpp-front-module--payment-methods .bpp-front-module--pm-body .bpp-front-module--pm-body__item p,
                        .bpp-front-package-detail .bpp-displaying-total-package-txt,
                        .el-picker-panel__content .el-date-table td:not(.current):not(.today) span:hover, 
                        .el-picker-panel__content .el-date-table td:not(.next-month):not(.prev-month):not(.today):not(.current) span:hover,
                        .bpp-summary-id-detail .bpp-summary-bkid,
                        .bpp-custom-dropdown .el-select-dropdown__item,
                        .el-picker-panel__content .el-date-table th,
                        .el-date-picker.has-time .el-time-spinner__item
                        { color: $sub_title_color !important; }";
                $bookingpress_customize_css_content .= "
                        .bpp-front-btn.bpp-front-btn--borderless,
                        .bpp-package-detail .bpp-package-duration svg
                        { fill: $sub_title_color !important; }";    
                $bookingpress_customize_css_content .= ".bpp-frontend-main-container-package .bpp-package-indicator::after      
                        { background: $sub_title_color !important; }";    
                $bookingpress_customize_css_content .= "
                        .bpp-package-description,
                        .bpp-front-form-control--radio .el-radio__label,
                        .bpp-summary-bkid-success-info-text,
                        .bpp-summary-item-title,
                        .bpp-front-cp__singup-link-group .bpp-custom-signup-label a,
                        .bpp-front-form-field--file-upload .el-upload-list__item-name [class^=el-icon],
                        .bpp-front-form-control--checkbox .el-checkbox__label,
                        .bpp-front-form-control--file-upload .bpp-fu__placeholder
                        { color: $content_color !important; }"; 
                $bookingpress_customize_css_content .= "
                        .bpp-front-module--payment-methods .bpp-front-module--pm-body .bpp-front-module--pm-body__item svg.bpp-front-pm-pay-local-icon,
                        .bpp-package-booking-left-menu .bpp-package-menu-item svg
                        { fill: $content_color !important; }";                         
                $bookingpress_customize_css_content .= "                        
                        .bpp-front-form-control input::placeholder,
                        .bpp-front-form-control .el-textarea__inner::placeholder,
                        .el-date-picker__time-header .el-input .el-input__inner::placeholder { color:$placeholder_color !important;}";
                $bookingpress_customize_css_content .= ".bpa-front-btn--primary:focus, .bpp-front-btn--primary:focus,
                        .bpp-front-tabs--panel-body .bpp-front-tabs--foot .bpp-front-btn--primary:focus
                        {  box-shadow: $box_shadow_color !important; }";
                $bookingpress_customize_css_content .= ".bpp-frontend-main-container-package,
                        .bpp-frontend-main-inner_container,
                        .bpp-front-dialog.bpp-front-package-booking-dialog,
                        .bpp-front-form-control--file-upload,
                        .bpp-front-form-control--file-upload .bpp-fu__btn,
                        .bpp-front-form-control--file-upload,
                        .bpp-front-form-control.--bpp-country-dropdown .vti__dropdown,
                        .bpp-front-form-control.--bpp-country-dropdown .vti__dropdown-list,
                        .bpp-custom-dropdown.el-select-dropdown,
                        .bpp-custom-datepicker,
                        .bpp-custom-datepicker .el-picker-panel__footer,
                        .bpp-custom-datepicker .el-time-panel,
                        .bpp-front-module--payment-methods .bpp-front-module--pm-body .bpp-front-module--pm-body__item .bpp-front-si-card--checkmark-icon,
                        .bpp-frontend-main-container-package .bpp-package-list-col,
                        .bpp-front-tabs--panel-body .bpp-front-tabs--foot,
                        .bpp-front-form-control--radio .el-radio__inner::after,
                        .bpp-custom-datepicker .el-time-panel,
                        .el-date-picker.has-time .el-time-spinner__item
                        { background-color: $shortcode_background_color !important }";
                $bookingpress_customize_css_content .= "
                        .bpp-front-form-control .el-textarea__inner,
                        .el-date-picker__header-label,
                        .bpp-custom-dropdown .el-select-dropdown__item span,
                        .bpp-front-module-heading, .bpp-search-btn-txt,.bpa-ma-dt__time-val,.bpp-package-name,
                        .bpp-front-module-heading, .bpp-search-btn-txt,.bpa-ma-dt__time-val span,
                        .bpp-front-package-detail .bpp-package-description,
                        .bpp-front-package-detail .bpp-package-description-full,
                        .bpp-front-package-detail .bpp-package-description .bpp-package-description-show-less,
                        .bpp-front-package-detail .bpp-package-description .bpp-package-description-show-more,
                        .bpp-front-package-detail .bpp-package-description .bpp-package-description-excerpt,
                        .bpp-front-package-detail .bpp-package-services-list .bpp-package-service-include-text,
                        .bpp-front-package-detail .bpp-package-price .bpp-package-realprice,
                        .bpp-front-package-detail .bpp-package-price .bpp-package-discprice,
                        .bpp-front-package-detail .bpp-package-service .bpp-package-service-nm,
                        .bpp-front-package-detail .bpp-package-service .bpp-package-service-dur,
                        .bpp-front-package-detail .bpp-package-service .bpp-package-service-no-app,
                        .bpp-front-package-detail .bpp-show-package-services_load .bpp-package-service-load-link span,
                        .bpp-front-package-detail .bpp-package-button .bpa-btn span,
                        .bpp-pagination-record-display-label .bpp-displaying-total-package-txt,
                        .bpp-pagination-record-display-label .bpp-displaying-total-package-num,
                        .bpp-front-form-control input,.bpp-front-package-booking-dialog .bpp-front-form-label,
                        .bpp-front-dialog-body .bpp-tm__item-label, .bpp-front-dialog-body .bpp-tm__item-icon,
                        .bpp-front-form-control input::placeholder,
                        .bpp-front-form-control .el-textarea__inner::placeholder,
                        .el-date-picker__time-header .el-input .el-input__inner::placeholder,
                        .el-date-picker__time-header .el-input .el-input__inner,
                        .bpp-front-form-control--checkbox .el-checkbox__label div,
                        .bpp-front-form-control--radio .el-radio__label,
                        .bpp-front-form-control--file-upload .bpp-fu__placeholder,
                        .bpp-front-form-field--file-upload .el-upload-list__item-name,
                        .bpp-front-dialog-body .bpp-front-btn--primary span,
                        .bpp-front-form-control--file-upload .bpp-fu__btn,
                        .bpp-front-dialog-body .el-form-item__error,.bpp-front-btn.bpp-front-btn--borderless span label,
                        .bpp-front-cp__singup-link-group .bpp-custom-signup-label a,
                        .bpp-front-toast-notification.--bpp-error p,
                        .bpp-front-dialog-body .bpp-packge-name, .bpp-front-module--bs-amount-details .bpp-bs-ai__item,
                        .bpp-front-module--payment-methods .bpp-front-module--pm-body .bpp-front-module--pm-body__item,
                        .bpp-front-module--payment-methods .bpp-front-module--pm-body .bpp-front-module--pm-body__item p,            
                        .bpp-front-module--bs-amount-details .bpp-is-total-row .bpp-fm-tr__tax-included-label,
                        .bpp-is-total-row .bpp-bs-ai__item span,
                        .bpp-front-module-container-payment .bpp-front-cdf__title,
                        .bpp-summary-id-detail .bpp-summary-bkid,.bpp-summary-id-detail .bpp-summary-bkid .bpp-front-pb-id,
                        .bpp-summary-bkid-success-msg, .bpp-summary-bkid-success-info-text,
                        .bpp-summary-item-title, .bpp-summary-item-detail,
                        .el-date-picker__header-label, .el-picker-panel__content .el-date-table td span,
                        .el-date-picker.has-time button.el-button--mini,
                        .el-picker-panel__content .el-date-table th,
                        .el-date-picker.has-time .el-time-spinner__item
                        {  font-family: $title_font_family !important;}";
                $bookingpress_customize_css_content .= "
                        .bpp-front-form-control .el-textarea__inner,
                        .bpp-front-form-control input,
                        .bpp-front-btn--primary span
                        {  font-size: $content_font_size !important;}";
                $bookingpress_customize_css_content .= "
                        .bpp-front-form-control.--bpp-country-dropdown .vti__dropdown:hover,
                        .bpp-front-form-control.--bpp-country-dropdown .vti__dropdown-item.highlighted,
                        .bpp-custom-dropdown .el-select-dropdown__item.hover,
                        .bpp-custom-dropdown .el-select-dropdown__item:hover
                        { background-color: $shortcode_footer_background_color !important;}";  
                $bookingpress_customize_css_content .= "                        
                        .bpp-front-tmc__vector--confirmation .bpp-front-vc__bg          
                        { fill: $shortcode_footer_background_color !important;}";   
                if (! function_exists('WP_Filesystem') ) {
                    include_once ABSPATH . 'wp-admin/includes/file.php';
                }
                WP_Filesystem();
                global $wp_filesystem;
                $wp_upload_dir = wp_upload_dir();
                $target_path   = $wp_upload_dir['basedir'] . '/bookingpress/bookingpress_front_package_' . $bookingpress_customize_css_key . '.css';
                $result        = $wp_filesystem->put_contents($target_path, $bookingpress_customize_css_content, 0777);
            }

        }
        
        /**
         * bookingpress_save_customize_package_settings_func
         *
         * @return void
         */
        function bookingpress_save_customize_package_settings_func(){
            global $wpdb, $BookingPress, $tbl_bookingpress_customize_settings, $bookingpress_global_options;
            $response              = array();
            $bpa_check_authorization = $this->bpa_check_authentication( 'save_package_settings', true, 'bpa_wp_nonce' );
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
            // phpcs:disable WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
            $bookingpress_colorpicker_data = ! empty($_POST['package_booking_selected_colorpicker_values']) ? array_map(array( $BookingPress, 'appointment_sanatize_field' ), $_POST['package_booking_selected_colorpicker_values']) : array(); // phpcs:ignore WordPress.Security.NonceVerification
            $bookingpress_font_values_data = ! empty($_POST['package_booking_selected_font_values']) ? array_map(array( $BookingPress, 'appointment_sanatize_field' ), $_POST['package_booking_selected_font_values']) : array(); // phpcs:ignore WordPress.Security.NonceVerification
            $bookingpress_package_settings_data    = ! empty($_POST['package_booking_form_settings']) ? array_map(array( $BookingPress, 'appointment_sanatize_field' ), $_POST['package_booking_form_settings']) : array(); // phpcs:ignore WordPress.Security.NonceVerification
            // phpcs:enable
            if (! empty($bookingpress_package_settings_data) ) {
                foreach ( $bookingpress_package_settings_data as $bookingpress_setting_key => $bookingpress_setting_val ) {
                    $bookingpress_db_fields = array(
                        'bookingpress_setting_name'  => $bookingpress_setting_key,
                        'bookingpress_setting_value' => $bookingpress_setting_val,
                        'bookingpress_setting_type'  => 'package_booking_form',
                    );
                    $is_setting_exists = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(bookingpress_setting_id) as total FROM {$tbl_bookingpress_customize_settings} WHERE bookingpress_setting_name = %s AND bookingpress_setting_type = 'package_booking_form'", $bookingpress_setting_key )); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- Reason: $tbl_bookingpress_customize_settings is table name defined globally. False Positive alarm
                    if ($is_setting_exists > 0 ) {
                        $wpdb->update(
                            $tbl_bookingpress_customize_settings,
                            $bookingpress_db_fields,
                            array(
                                'bookingpress_setting_name' => $bookingpress_setting_key,
                                'bookingpress_setting_type' => 'package_booking_form',
                            )
                        );
                    } else {
                        $wpdb->insert($tbl_bookingpress_customize_settings, $bookingpress_db_fields);
                    }
                }

                if( !empty( $bookingpress_colorpicker_data ) ){
                    foreach( $bookingpress_colorpicker_data as $bookingpress_setting_key => $bookingpress_setting_val ){
                        $bookingpress_setting_val = !empty($bookingpress_setting_val) && gettype($bookingpress_setting_val) == 'array' ? json_encode($bookingpress_setting_val) : $bookingpress_setting_val;
                        $bookingpress_db_fields = array(
                            'bookingpress_setting_name'  => $bookingpress_setting_key,
                            'bookingpress_setting_value' => $bookingpress_setting_val,
                            'bookingpress_setting_type'  => 'package_booking_form',
                        );
    
                        $is_setting_exists = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(bookingpress_setting_id) as total FROM {$tbl_bookingpress_customize_settings} WHERE bookingpress_setting_name = %s AND bookingpress_setting_type = 'package_booking_form'", $bookingpress_setting_key) ); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- Reason: $tbl_bookingpress_customize_settings is table name defined globally. False Positive alarm
                        if ($is_setting_exists > 0 ) {
                            $wpdb->update(
                                $tbl_bookingpress_customize_settings,
                                $bookingpress_db_fields,
                                array(
                                'bookingpress_setting_name' => $bookingpress_setting_key,
                                'bookingpress_setting_type' => 'package_booking_form',
                                )
                            );
                        } else {
                            $wpdb->insert($tbl_bookingpress_customize_settings, $bookingpress_db_fields);
                        }
                    }
                }

                if( !empty( $bookingpress_font_values_data ) ){
                    foreach( $bookingpress_font_values_data as $bookingpress_setting_key => $bookingpress_setting_val ){
                        $bookingpress_setting_val = !empty($bookingpress_setting_val) && gettype($bookingpress_setting_val) == 'array' ? json_encode($bookingpress_setting_val) : $bookingpress_setting_val;
                        $bookingpress_db_fields = array(
                            'bookingpress_setting_name'  => $bookingpress_setting_key,
                            'bookingpress_setting_value' => $bookingpress_setting_val,
                            'bookingpress_setting_type'  => 'package_booking_form',
                        );
    
                        $is_setting_exists = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(bookingpress_setting_id) as total FROM {$tbl_bookingpress_customize_settings} WHERE bookingpress_setting_name = %s AND bookingpress_setting_type = 'package_booking_form'", $bookingpress_setting_key) ); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- Reason: $tbl_bookingpress_customize_settings is table name defined globally. False Positive alarm
                        if ($is_setting_exists > 0 ) {
                            $wpdb->update(
                                $tbl_bookingpress_customize_settings,
                                $bookingpress_db_fields,
                                array(
                                    'bookingpress_setting_name' => $bookingpress_setting_key,
                                    'bookingpress_setting_type' => 'package_booking_form',
                                )
                            );
                        } else {
                            $wpdb->insert($tbl_bookingpress_customize_settings, $bookingpress_db_fields);
                        }
                    }
                }
                 
                $package_booking_form = array();
                $package_booking_form = array(         
                    'background_color'     => $bookingpress_colorpicker_data['background_color'],
                    'footer_background_color' => $bookingpress_colorpicker_data['footer_background_color'],
                    'primary_background_color' => $bookingpress_colorpicker_data['primary_background_color'],
                    'border_color'         => $bookingpress_colorpicker_data['border_color'],
                    'primary_color'        => $bookingpress_colorpicker_data['primary_color'],
                    'label_title_color'    => $bookingpress_colorpicker_data['label_title_color'],
                    'sub_title_color'      => $bookingpress_colorpicker_data['sub_title_color'],
                    'content_color'        => $bookingpress_colorpicker_data['content_color'],
                    'title_font_family'    => $bookingpress_font_values_data['title_font_family'],
                    'price_button_text_color' => $bookingpress_colorpicker_data['price_button_text_color'],
                );
                $bookingpress_action[] = 'bookingpress_save_customize_package_settings';
                $booking_form = array();
                $bookingpress_custom_data_arr = array(                    
                    'package_booking_form' => $package_booking_form, 
                    'action' => $bookingpress_action,
                );
                $BookingPress->bookingpress_generate_customize_css_func($bookingpress_custom_data_arr);
                $response['variant'] = 'success';
                $response['title']   = esc_html__('Success', 'bookingpress-package');
                $response['msg']     = esc_html__('Customize settings updated successfully.', 'bookingpress-package');
            }
            wp_cache_delete( 'bookingpress_all_general_settings' );
            wp_cache_delete( 'bookingpress_all_customize_settings' );
            echo wp_json_encode($response);
            exit();
        }
        
        /**
         * bookingpress_package_dynamic_vue_methods_func
         *
         * @return void
         */
        function bookingpress_package_dynamic_vue_methods_func() {
            global $bookingpress_notification_duration;
            ?>
            bpa_save_field_package_booking_data(){
                const vm2 = this;
                var postData = [];
                postData.action = 'bookingpress_save_customize_package_settings';
                postData.package_booking_form_settings = vm2.package_booking_form_settings;
                postData.package_booking_selected_font_values = vm2.selected_font_values;
                postData.package_booking_selected_colorpicker_values = vm2.selected_colorpicker_values;
                postData._wpnonce = '<?php echo esc_html(wp_create_nonce('bpa_wp_nonce')); ?>'
                axios.post( appoint_ajax_obj.ajax_url, Qs.stringify( postData ) )
                .then( function (response) {
                    if(response.data.variant == 'error'){
                        vm2.$notify({
                            title: response.data.title,
                            message: response.data.msg,
                            type: response.data.variant,
                            customClass: response.data.variant+'_notification',
                            duration:<?php echo intval($bookingpress_notification_duration); ?>,
                        });    
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
            <?php
        }        
        /**
         * bookingpress_save_customize_other_settings_data_func
         *
         * @return void
         */
        function bookingpress_save_customize_other_settings_data_func(){
        ?>
            vm.bpa_save_field_package_booking_data();    
            <?php
        }   

		/**
		 * Function for modify form field data before save
		 *
		 * @param  mixed $form_fields_data
		 * @param  mixed $posted_field_settings
		 * @return void
		*/
		function bookingpress_modify_form_field_data_before_save_func( $form_fields_data, $posted_field_settings ) {            
            $bookingpress_field_is_package_hide = (isset($posted_field_settings['field_is_package_hide']) && !empty( $posted_field_settings['field_is_package_hide'] )) ? sanitize_text_field( $posted_field_settings['field_is_package_hide'] ) : '';
            $form_fields_data['bookingpress_field_is_package_hide']  =  ($bookingpress_field_is_package_hide == 'true')?1:0;
            return $form_fields_data;
        }
        
        /**
         * Function for add new fields data when get custom fields
         *
         * @param  mixed $form_fields_data
         * @param  mixed $db_field_options
         * @return void
         */
        function bookingpress_modify_form_field_data( $form_fields_data, $db_field_options ) {

            $field_is_package_hide = (isset($db_field_options['bookingpress_field_is_package_hide']))? $db_field_options['bookingpress_field_is_package_hide']:0;
            $form_fields_data['field_is_package_hide'] = ($field_is_package_hide == 1)?true:false;
            return $form_fields_data;
        }

        /**
         * Function for add extra custom fields visibility setting.
         *
         * @return void
         */
        function bookingpress_custom_field_setting_after_func(){
        ?>
        <div class="bpa-fs-item-settings-form-control-item bpp-pack-custom-fields">
            <label class="bpa-form-label"><?php esc_html_e( 'Package Form Visibility', 'bookingpress-package' ); ?></label>
            <!--<el-input class="bpa-form-control" v-model="field_settings_data.css_class"></el-input>-->
            <el-checkbox v-model="field_settings_data.field_is_package_hide" class="bpa-form-label bpa-custom-radio--is-label"><?php esc_html_e( 'Hidden', 'bookingpress-package' ); ?></el-checkbox>
        </div>            
        <?php 
        }

        function bookingpress_customize_add_dynamic_data_fields_func($bookingpress_customize_vue_data_fields){
            global $BookingPress, $bookingpress_package;
            $bookingpress_package_form_title = $BookingPress->bookingpress_get_customize_settings('package_form_title', 'package_booking_form');
            $package_search_placeholder = $BookingPress->bookingpress_get_customize_settings('package_search_placeholder', 'package_booking_form');
            $package_search_button = $BookingPress->bookingpress_get_customize_settings('package_search_button', 'package_booking_form');
            $package_buy_now_nutton_text = $BookingPress->bookingpress_get_customize_settings('package_buy_now_nutton_text', 'package_booking_form');
            $package_services_include_text = $BookingPress->bookingpress_get_customize_settings('package_services_include_text', 'package_booking_form');
            $package_services_show_less_text = $BookingPress->bookingpress_get_customize_settings('package_services_show_less_text','package_booking_form');
            $package_services_show_more_text = $BookingPress->bookingpress_get_customize_settings('package_services_show_more_text','package_booking_form');
            $package_user_details_step_label = $BookingPress->bookingpress_get_customize_settings('user_details_step_label', 'package_booking_form');
            $package_login_form_title_label = $BookingPress->bookingpress_get_customize_settings('login_form_title_label', 'package_booking_form');
            $package_login_form_username_field_label = $BookingPress->bookingpress_get_customize_settings('login_form_username_field_label', 'package_booking_form');
            $package_login_form_username_field_placeholder = $BookingPress->bookingpress_get_customize_settings('login_form_username_field_placeholder', 'package_booking_form');
            $package_login_form_password_field_label = $BookingPress->bookingpress_get_customize_settings('login_form_password_field_label', 'package_booking_form');
            $package_login_form_password_field_placeholder = $BookingPress->bookingpress_get_customize_settings('login_form_password_field_placeholder', 'package_booking_form');
            $package_login_form_username_required_field_label = $BookingPress->bookingpress_get_customize_settings('login_form_username_required_field_label', 'package_booking_form');
            $package_login_form_password_required_field_label = $BookingPress->bookingpress_get_customize_settings('login_form_password_required_field_label', 'package_booking_form');
            $package_login_form_signup_link_text = $BookingPress->bookingpress_get_customize_settings('login_form_signup_link_text', 'package_booking_form');   
            $package_login_form_dont_have_acc_text = $BookingPress->bookingpress_get_customize_settings('login_form_dont_have_acc_text', 'package_booking_form');                        
            $package_remember_me_field_label = $BookingPress->bookingpress_get_customize_settings('remember_me_field_label', 'package_booking_form');
            $package_login_button_label = $BookingPress->bookingpress_get_customize_settings('login_button_label', 'package_booking_form');
            $package_forgot_password_link_label = $BookingPress->bookingpress_get_customize_settings('forgot_password_link_label', 'package_booking_form');
            $package_login_error_message_label = $BookingPress->bookingpress_get_customize_settings('login_error_message_label', 'package_booking_form');
            $package_forgot_password_form_title = $BookingPress->bookingpress_get_customize_settings('forgot_password_form_title', 'package_booking_form');
            $package_forgot_password_email_address_field_label = $BookingPress->bookingpress_get_customize_settings('forgot_password_email_address_field_label', 'package_booking_form');
            $package_forgot_password_email_address_placeholder = $BookingPress->bookingpress_get_customize_settings('forgot_password_email_address_placeholder', 'package_booking_form');
            $package_forgot_password_email_required_field_label = $BookingPress->bookingpress_get_customize_settings('forgot_password_email_required_field_label', 'package_booking_form');
            $package_forgot_password_button_label = $BookingPress->bookingpress_get_customize_settings('forgot_password_button_label', 'package_booking_form');
            $package_forgot_password_error_message = $BookingPress->bookingpress_get_customize_settings('forgot_password_error_message', 'package_booking_form');
            $package_forgot_password_success_message_label = $BookingPress->bookingpress_get_customize_settings('forgot_password_success_message_label', 'package_booking_form');
            $package_forgot_password_sing_in_link_label = $BookingPress->bookingpress_get_customize_settings('forgot_password_sing_in_link_label', 'package_booking_form');      
            $package_signup_account_form_title = $BookingPress->bookingpress_get_customize_settings('signup_account_form_title', 'package_booking_form');  
            $package_signup_form_button_title = $BookingPress->bookingpress_get_customize_settings('signup_form_button_title', 'package_booking_form');  
            $package_signup_form_already_have_acc_text = $BookingPress->bookingpress_get_customize_settings('signup_form_already_have_acc_text', 'package_booking_form');  
            $package_signup_form_login_link_text = $BookingPress->bookingpress_get_customize_settings('signup_form_login_link_text', 'package_booking_form');  
            $package_basic_details_form_title = $BookingPress->bookingpress_get_customize_settings('basic_details_form_title', 'package_booking_form');  
            $package_basic_details_submit_button_title = $BookingPress->bookingpress_get_customize_settings('basic_details_submit_button_title', 'package_booking_form');  
            $package_make_payment_form_title = $BookingPress->bookingpress_get_customize_settings('make_payment_form_title', 'package_booking_form');  
            $package_make_payment_subtotal_text = $BookingPress->bookingpress_get_customize_settings('make_payment_subtotal_text', 'package_booking_form');  
            $package_make_payment_total_amount_text = $BookingPress->bookingpress_get_customize_settings('make_payment_total_amount_text', 'package_booking_form');  
            $package_make_payment_select_payment_method_text = $BookingPress->bookingpress_get_customize_settings('make_payment_select_payment_method_text', 'package_booking_form');
            $package_make_payment_buy_package_btn_text = $BookingPress->bookingpress_get_customize_settings('make_payment_buy_package_btn_text', 'package_booking_form');
            $package_summary_step_title = $BookingPress->bookingpress_get_customize_settings('summary_step_title', 'package_booking_form');
            $package_summary_tab_booking_id_text = $BookingPress->bookingpress_get_customize_settings('summary_tab_booking_id_text', 'package_booking_form');
            $package_summary_tab_package_booked_success_message = $BookingPress->bookingpress_get_customize_settings('summary_tab_package_booked_success_message', 'package_booking_form');
            $package_summary_tab_package_booking_information_sent_message = $BookingPress->bookingpress_get_customize_settings('summary_tab_package_booking_information_sent_message', 'package_booking_form');
            $package_summary_tab_package_title_text = $BookingPress->bookingpress_get_customize_settings('summary_tab_package_title_text', 'package_booking_form');
            $package_summary_tab_customer_title = $BookingPress->bookingpress_get_customize_settings('summary_tab_customer_title', 'package_booking_form');
            $package_summary_step_form_title = $BookingPress->bookingpress_get_customize_settings('summary_step_form_title', 'package_booking_form');
            $package_make_payment_tab_title = $BookingPress->bookingpress_get_customize_settings('make_payment_tab_title', 'package_booking_form');
            
            $package_hide_image_indicator = $BookingPress->bookingpress_get_customize_settings('hide_image_indicator', 'package_booking_form');
			$package_hide_image_indicator = !empty( $package_hide_image_indicator ) && 'true' == $package_hide_image_indicator ? true : false;
            $package_auto_scroll_image = $BookingPress->bookingpress_get_customize_settings('auto_scroll_image', 'package_booking_form');
            $package_auto_scroll_image = !empty( $package_auto_scroll_image ) && 'true' == $package_auto_scroll_image ? true : false;
            $auto_scroll_image_interval = $BookingPress->bookingpress_get_customize_settings('auto_scroll_image_interval','package_booking_form');
            $package_hide_real_price = $BookingPress->bookingpress_get_customize_settings('hide_real_price', 'package_booking_form');
            $package_hide_real_price = !empty( $package_hide_real_price ) && 'true' == $package_hide_real_price ? true : false;
            $package_hide_package_description = $BookingPress->bookingpress_get_customize_settings('hide_package_description', 'package_booking_form');
            $package_hide_package_description = !empty( $package_hide_package_description ) && 'true' == $package_hide_package_description ? true : false;
            $package_hide_package_pagination = $BookingPress->bookingpress_get_customize_settings('hide_package_pagination', 'package_booking_form');
            $package_hide_package_pagination = !empty( $package_hide_package_pagination ) && 'true' == $package_hide_package_pagination ? true : false;
            $show_book_appointment_btn = $BookingPress->bookingpress_get_customize_settings('show_book_appointment_btn', 'package_booking_form');
            $show_book_appointment_btn = !empty( $show_book_appointment_btn ) && 'true' == $show_book_appointment_btn ? true : false;
            $package_appointment_btn_services = $BookingPress->bookingpress_get_customize_settings('package_appointment_btn_services', 'package_booking_form');
            $package_appointment_btn_services = (empty($package_appointment_btn_services))?'all':$package_appointment_btn_services;
            $package_appointment_book_redirect = $BookingPress->bookingpress_get_customize_settings('package_appointment_book_redirect', 'package_booking_form');                    
            $package_order_payment_failed_message = $BookingPress->bookingpress_get_customize_settings('package_order_payment_failed_message', 'package_booking_form');
            $no_package_found_msg = $BookingPress->bookingpress_get_customize_settings('no_package_found_msg', 'package_booking_form');

            $package_enable_google_captcha = $BookingPress->bookingpress_get_customize_settings('enable_google_captcha', 'package_booking_form');
			$package_enable_google_captcha = !empty( $package_enable_google_captcha ) && 'true' == $package_enable_google_captcha ? true : false;
            
            $bookingpress_customize_vue_data_fields['package_booking_form_settings'] = array(
                
                'package_form_title' => stripslashes_deep($bookingpress_package_form_title),
                'package_search_placeholder' => stripslashes_deep($package_search_placeholder),
                'package_search_button' => stripslashes_deep($package_search_button),
                'no_package_found_msg' => stripslashes_deep($no_package_found_msg),
                'package_buy_now_nutton_text' => stripslashes_deep($package_buy_now_nutton_text),
                'package_services_include_text' => stripslashes_deep($package_services_include_text),
                'hide_image_indicator' => $package_hide_image_indicator,
                'auto_scroll_image' => $package_auto_scroll_image,
                'hide_real_price' => $package_hide_real_price,
                'hide_package_description' => $package_hide_package_description,
                'hide_package_pagination' => $package_hide_package_pagination,
                'show_book_appointment_btn' => $show_book_appointment_btn,
                'package_appointment_btn_services' => $package_appointment_btn_services,
                'package_appointment_book_redirect' => $package_appointment_book_redirect,
                'package_order_payment_failed_message' => $package_order_payment_failed_message,
                'package_services_show_less_text' => stripslashes_deep($package_services_show_less_text),
                'package_services_show_more_text' => stripslashes_deep($package_services_show_more_text),
                'auto_scroll_image_interval' => esc_html($auto_scroll_image_interval),
                'user_details_step_label'  =>  stripslashes_deep($package_user_details_step_label),
                'login_form_title_label'  =>  stripslashes_deep($package_login_form_title_label), 
                'login_form_username_field_label'  =>   stripslashes_deep($package_login_form_username_field_label), 
                'login_form_username_field_placeholder'  =>  stripslashes_deep($package_login_form_username_field_placeholder), 
                'login_form_password_field_label'  =>   stripslashes_deep($package_login_form_password_field_label), 
                'login_form_password_field_placeholder'  =>  stripslashes_deep($package_login_form_password_field_placeholder), 
                'login_form_username_required_field_label'  =>  stripslashes_deep($package_login_form_username_required_field_label), 
                'login_form_password_required_field_label'  =>   stripslashes_deep($package_login_form_password_required_field_label), 
                'login_form_signup_link_text' => stripslashes_deep($package_login_form_signup_link_text),
                'remember_me_field_label'  =>  stripslashes_deep($package_remember_me_field_label), 
                'login_button_label'  =>  stripslashes_deep($package_login_button_label), 
                'forgot_password_link_label'  =>   stripslashes_deep($package_forgot_password_link_label), 
                'login_error_message_label'  =>  stripslashes_deep($package_login_error_message_label), 
                'forgot_password_form_title'  =>  stripslashes_deep($package_forgot_password_form_title), 
                'forgot_password_email_address_field_label'  => stripslashes_deep($package_forgot_password_email_address_field_label), 
                'forgot_password_email_address_placeholder'  =>   stripslashes_deep($package_forgot_password_email_address_placeholder), 
                'forgot_password_email_required_field_label'  =>  stripslashes_deep($package_forgot_password_email_required_field_label), 
                'forgot_password_button_label'  =>  stripslashes_deep($package_forgot_password_button_label), 
                'forgot_password_error_message'  =>  stripslashes_deep($package_forgot_password_error_message), 
                'forgot_password_success_message_label'  =>  stripslashes_deep($package_forgot_password_success_message_label), 
                'forgot_password_sing_in_link_label'  =>  stripslashes_deep($package_forgot_password_sing_in_link_label), 
                'signup_account_form_title' =>  stripslashes_deep($package_signup_account_form_title),
                'signup_form_button_title' =>  stripslashes_deep($package_signup_form_button_title),
                'signup_form_already_have_acc_text' =>  stripslashes_deep($package_signup_form_already_have_acc_text),
                'signup_form_login_link_text' =>  stripslashes_deep($package_signup_form_login_link_text),
                'basic_details_form_title' =>  stripslashes_deep($package_basic_details_form_title),
                'basic_details_submit_button_title' =>  stripslashes_deep($package_basic_details_submit_button_title),
                'make_payment_tab_title' =>  stripslashes_deep($package_make_payment_tab_title),
                'make_payment_form_title' =>  stripslashes_deep($package_make_payment_form_title),
                'make_payment_subtotal_text' =>  stripslashes_deep($package_make_payment_subtotal_text),
                'make_payment_total_amount_text' =>  stripslashes_deep($package_make_payment_total_amount_text),
                'make_payment_select_payment_method_text' =>  stripslashes_deep($package_make_payment_select_payment_method_text),
                'make_payment_buy_package_btn_text' =>  stripslashes_deep($package_make_payment_buy_package_btn_text),
                'summary_step_title' =>  stripslashes_deep($package_summary_step_title),
                'summary_tab_booking_id_text' =>  stripslashes_deep($package_summary_tab_booking_id_text),
                'summary_tab_package_booked_success_message' =>  stripslashes_deep($package_summary_tab_package_booked_success_message),
                'summary_tab_package_booking_information_sent_message' =>  stripslashes_deep($package_summary_tab_package_booking_information_sent_message),
                'summary_tab_package_title_text' =>  stripslashes_deep($package_summary_tab_package_title_text),
                'summary_tab_customer_title' =>  stripslashes_deep($package_summary_tab_customer_title),
                'summary_step_form_title' => stripslashes_deep($package_summary_step_form_title),
                'login_form_dont_have_acc_text' => stripslashes_deep($package_login_form_dont_have_acc_text),
                'enable_google_captcha' => $package_enable_google_captcha,
            );

            $bookingpress_customize_vue_data_fields['package_booking_form_settings'] = apply_filters('bookingpress_modified_package_customization_fields',$bookingpress_customize_vue_data_fields['package_booking_form_settings']);
            $bookingpress_pkg_booking_customize_settings_arr = array('signup_account_fullname_label', 'signup_account_email_label', 'signup_account_mobile_number_label', 'signup_account_password_label', 'signup_account_fullname_placeholder', 'signup_account_email_placeholder', 'signup_account_password_placeholder','signup_account_email_required_message', 'signup_account_mobile_number_required_message', 'signup_account_password_required_message','signup_wrong_email_message','signup_email_exists', 'signup_account_fullname_required_message', 'summary_tab_book_appointment_btn_text', 'pkg_card_details_text', 'pkg_card_number_text', 'pkg_expire_month_text', 'pkg_expire_year_text', 'pkg_cvv_text', 'pkg_card_name_text', 'pkg_paypal_text','package_purchase_limit_message', 'package_tax_title','package_go_back_button_text','package_desc_show_less_text', 'package_desc_read_more_text','package_month_text','package_months_text','package_year_text','package_years_text','package_day_text','package_days_text');            

            $bookingpress_pkg_booking_customize_settings_arr = $BookingPress->bookingpress_get_customize_settings($bookingpress_pkg_booking_customize_settings_arr, 'package_booking_form');
            foreach($bookingpress_pkg_booking_customize_settings_arr as $key => $value) {
                $bookingpress_customize_vue_data_fields['package_booking_form_settings'][$key] = stripslashes_deep($value);
            }  

            /* Customize Package Booking Form Start - For Mult language Addon*/
            if($bookingpress_package->bookingpress_is_multilanguage_active()) {

                global $bookingpress_multilanguage, $bookingpress_all_language_translation_fields;

                $bookingpress_customize_package_booking_language_translate_fields = array();
        
                $bookingpress_customize_package_booking_language_translate_fields['customized_package_booking_field_labels'] = $bookingpress_all_language_translation_fields['customized_package_booking_field_labels'];

                $bookingpress_customize_package_booking_language_translate_fields['customized_package_booking_user_detail_step_label'] = $bookingpress_all_language_translation_fields['customized_package_booking_user_detail_step_label'];

                $bookingpress_customize_package_booking_language_translate_fields['customized_package_booking_login_related_labels'] = $bookingpress_all_language_translation_fields['customized_package_booking_login_related_labels'];            

                $bookingpress_customize_package_booking_language_translate_fields['customized_package_booking_forgot_password_labels'] = $bookingpress_all_language_translation_fields['customized_package_booking_forgot_password_labels'];

                $bookingpress_customize_package_booking_language_translate_fields['customized_package_booking_signup_form_labels'] = $bookingpress_all_language_translation_fields['customized_package_booking_signup_form_labels'];            

                $bookingpress_customize_package_booking_language_translate_fields['customized_package_booking_basic_details_labels'] = $bookingpress_all_language_translation_fields['customized_package_booking_basic_details_labels'];  

                $bookingpress_customize_package_booking_language_translate_fields['customized_package_booking_make_payment_labels'] = $bookingpress_all_language_translation_fields['customized_package_booking_make_payment_labels'];  

                $bookingpress_all_language_translation_fields['customized_package_booking_summary_step_labels'] = apply_filters('bookingpress_customized_package_booking_summary_step_labels_translate',$bookingpress_all_language_translation_fields['customized_package_booking_summary_step_labels']);
                $bookingpress_customize_package_booking_language_translate_fields['customized_package_booking_summary_step_labels'] = $bookingpress_all_language_translation_fields['customized_package_booking_summary_step_labels'];  
                
                $bookingpress_customize_package_booking_language_translate_fields = apply_filters('bookingpress_customize_package_booking_language_translate_fields_modified',$bookingpress_customize_package_booking_language_translate_fields,$bookingpress_all_language_translation_fields);

                $bookingpress_customize_package_booking_language_section_title = $bookingpress_multilanguage->bookingpress_get_language_translation_section_label($bookingpress_customize_package_booking_language_translate_fields);

                $bookingpress_customize_vue_data_fields['bookingpress_customize_package_booking_language_section_title'] = $bookingpress_customize_package_booking_language_section_title;

                $bookingpress_get_selected_languages = $bookingpress_multilanguage->bookingpress_get_selected_languages();
                if(empty($bookingpress_get_selected_languages)){
                    $bookingpress_get_selected_languages = array();
                }
                if(!empty($bookingpress_get_selected_languages)){
                    $package_booking_language_data = $bookingpress_multilanguage->bookingpress_get_language_data_for_backend(0,'package_booking_form');
                    foreach($bookingpress_get_selected_languages as $key=>$sel_lang){
                        if(empty($bookingpress_current_selected_lang)){
                            $bookingpress_current_selected_lang = $key;
                        }            
                        foreach($bookingpress_customize_package_booking_language_translate_fields as $section_key=>$service_lang){                      
                            foreach($service_lang as $field_key => $field_value){                            
                                $bookingpress_customize_vue_data_fields['package_form_language_fields_data'][$key][$section_key][$field_key] = $field_value; 
                                $bookingpress_customize_vue_data_fields['package_language_data'][$key]['package_booking_form'][$field_key] = '';    
                                $search = array('bookingpress_element_type' => 'package_booking_form', 'bookingpress_language_code' => $key, 'bookingpress_ref_column_name'=> $field_key, 'bookingpress_element_ref_id'=> 0);

                                $keys = array_keys(array_filter($package_booking_language_data, function ($v) use ($search) { 
                                    return $v['bookingpress_element_ref_id'] == $search['bookingpress_element_ref_id'] && $v['bookingpress_element_type'] == $search['bookingpress_element_type'] && $v['bookingpress_language_code'] == $search['bookingpress_language_code'] && $v['bookingpress_ref_column_name'] == $search['bookingpress_ref_column_name']; 
                                        }
                                ));
                                $index_val = isset($keys[0]) ? $keys[0] : '';
                                if($index_val!='' || $index_val == 0) {
                                    if(isset($package_booking_language_data[$index_val])){
                                        $translated_data = $package_booking_language_data[$index_val];
                                        $bp_translated_str = isset($translated_data['bookingpress_translated_value']) ? $translated_data['bookingpress_translated_value'] : '';                                  
                                        $bookingpress_customize_vue_data_fields['package_language_data'][$key]['package_booking_form'][$field_key] = $bp_translated_str;    
                                    }
                                }         
                            } 
                        }
                    }
                }
            }
            /* Customize Package Booking Form End - For Mult language Addon*/
            return $bookingpress_customize_vue_data_fields;

        }

        /**
         * Function for add new customize tab menu
         *
         * @return void
         */
        function bookingpress_customize_tab_menu_after_func(){
        ?>
            <el-radio-button label="package_booking_form"><?php esc_html_e('Package Booking', 'bookingpress-package'); ?></el-radio-button>
        <?php 
        }
        
        /**
         * Function for add customize new tab
         *
         * @return void
         */
        function bookingpress_customize_tab_after_func(){

            global $BookingPress, $bookingpress_common_date_format,$bookingpress_global_options,$bookingpress_pro_staff_members;
            $bookingpress_global_options_arr       = $bookingpress_global_options->bookingpress_global_options();
        
            $bookingpress_default_time_format = $bookingpress_global_options_arr['wp_default_time_format'];
            $bookingpress_default_date_format = $bookingpress_global_options_arr['wp_default_date_format'];
            $bookingpress_default_time_format = apply_filters('bookingpress_change_time_slot_format',$bookingpress_default_time_format);
            $bookingpress_price                = $BookingPress->bookingpress_price_formatter_with_currency_symbol( 1000 );
            $bookingpress_service_price1       = $BookingPress->bookingpress_price_formatter_with_currency_symbol( 350 );
            $bookingpress_service_price2       = $BookingPress->bookingpress_price_formatter_with_currency_symbol( 150 );
            $bookingpress_staffmember_activate = $bookingpress_pro_staff_members->bookingpress_check_staffmember_module_activation();
            $bookingpress_singular_staffmember_name = !empty($bookingpress_global_options_arr['bookingpress_staffmember_singular_name']) ? $bookingpress_global_options_arr['bookingpress_staffmember_singular_name'] : esc_html_e('Staff Member', 'bookingpress-package');
            $bookingpress_plural_staffmember_name = !empty($bookingpress_global_options_arr['bookingpress_staffmember_plural_name']) ? $bookingpress_global_options_arr['bookingpress_staffmember_plural_name'] : esc_html_e('Staff Members', 'bookingpress-package');            
        ?>
        <el-tab-pane name="package_booking_form" v-if="bookingpress_tab_change_loader == '0'">                                           
            <div class="bpa-customize-step-content-container __bpa-is-sidebar">
                <el-row type="flex">
                    <el-col :xs="4" :sm="4" :md="4" :lg="4" :xl="4">
                        <div class="bpa-customize-step-side-panel">
                            <div class="bpa-cs-sp--heading">
                                <h4><?php esc_html_e('Form Options', 'bookingpress-package'); ?></h4>                                        
                            </div>
                            <div class="bpa-cs-sp-sub-module bpa-sm--swtich">
                                <div class="bpa-sm--item --bpa-is-flexbox">
                                    <label class="bpa-form-label"><?php esc_html_e('Hide image rotation arrow', 'bookingpress-package'); ?></label>
                                    <el-switch v-model="package_booking_form_settings.hide_image_indicator" class="bpa-swtich-control"></el-switch>
                                </div>  
                                <div class="bpa-sm--item --bpa-is-flexbox">
                                    <label class="bpa-form-label"><?php esc_html_e('Auto rotate image', 'bookingpress-package'); ?></label>
                                    <el-switch v-model="package_booking_form_settings.auto_scroll_image" class="bpa-swtich-control"></el-switch>
                                </div> 
                                <div class="bpa-sm--item --bpa-is-flexbox" v-show="package_booking_form_settings.auto_scroll_image == true">
                                    <label class="bpa-form-label"><?php esc_html_e('Auto rotate image interval', 'bookingpress-package'); ?></label>
                                </div>  
                                <div class="bpa-sm--item --bpa-is-flexbox" v-show="package_booking_form_settings.auto_scroll_image == true">
                                    <el-input-number class="bpa-form-control bpa-form-control--number" :min="5" :max="20" v-model="package_booking_form_settings.auto_scroll_image_interval" step-strictly></el-input-number>
                                </div>          
                                <div class="bpa-sm--item --bpa-is-flexbox">
                                    <label class="bpa-form-label"><?php esc_html_e('Hide actual price', 'bookingpress-package'); ?></label>
                                    <el-switch v-model="package_booking_form_settings.hide_real_price" class="bpa-swtich-control"></el-switch>
                                </div>  
                                <div class="bpa-sm--item --bpa-is-flexbox">
                                    <label class="bpa-form-label"><?php esc_html_e('Hide description', 'bookingpress-package'); ?></label>
                                    <el-switch v-model="package_booking_form_settings.hide_package_description" class="bpa-swtich-control"></el-switch>
                                </div>  
                                <div class="bpa-sm--item --bpa-is-flexbox">
                                    <label class="bpa-form-label"><?php esc_html_e('Show pagination', 'bookingpress-package'); ?></label>
                                    <el-switch v-model="package_booking_form_settings.hide_package_pagination" class="bpa-swtich-control"></el-switch>
                                </div>
                                <div class="bpa-sm--item --bpa-is-flexbox">
                                    <label class="bpa-form-label"><?php esc_html_e('Show book appointment button', 'bookingpress-package'); ?></label>
                                    <el-switch v-model="package_booking_form_settings.show_book_appointment_btn" class="bpa-swtich-control"></el-switch>
                                </div>
                                <div class="bpa-sm--item bpa-cs-sp-sub-module-extra" v-show="package_booking_form_settings.show_book_appointment_btn == true">
                                    <label class="bpa-form-label bpa-form-label-pack-title"><?php esc_html_e('Display package services', 'bookingpress-package'); ?></label>
                                </div>
                                <div class="bpa-sm--item" v-show="package_booking_form_settings.show_book_appointment_btn == true">
                                    <el-radio v-model="package_booking_form_settings.package_appointment_btn_services" label="all"><?php esc_html_e('All services', 'bookingpress-package'); ?></el-radio>
                                    <el-radio v-model="package_booking_form_settings.package_appointment_btn_services" label="package_services"><?php esc_html_e('Package services', 'bookingpress-package'); ?></el-radio>
                                </div>	                                
                                <div class="bpa-sm--item bpa-cs-sp-sub-module-extra" v-show="package_booking_form_settings.show_book_appointment_btn == true">
                                    <label class="bpa-form-label"><?php esc_html_e('Book Appointment Button URL', 'bookingpress-package'); ?></label>
                                    <el-input type="textarea" :rows="4" resize="none" v-model="package_booking_form_settings.package_appointment_book_redirect" class="bpa-form-control"></el-input>
                                </div>                                
                            </div>
                            <div class="bpa-cs-sp-sub-module bpa-cs-sp-sub-module-extra">                                  
                                <div class="bpa-sm--item">
                                    <label class="bpa-form-label"><?php esc_html_e('Payment failed message', 'bookingpress-package'); ?></label>
                                    <el-input type="textarea" :rows="4" resize="none" v-model="package_booking_form_settings.package_order_payment_failed_message" class="bpa-form-control"></el-input>
                                </div>
                            </div>
                            <?php do_action('bookingpress_customize_package_settings_add') ?>
                        </div>
                    </el-col>                            
                    <el-col :xs="16" :sm="16" :md="16" :lg="16" :xl="16">
                        <div class="bpa-customize-booking-form-preview-container --bpp-package-booking-form">
                            <div class="bpa-cmb-step-preview bpp-frontend-main-container-package" :style="{ 'background': selected_colorpicker_values.background_color }">   
                                <div class="bpp-front-package-detail v-cloak-package-hidden">
                                    <div class="bpp-front-package-filter">
                                        <el-row type="flex" :gutter="12" class="bpp-package--filter-wrapper">            
                                            <el-col class="bpp-package-head-col" :xs="24" :sm="24" :md="24" :lg="9" :xl="9">
                                                <div class="bpp-front-module-heading" :style="{'color': selected_colorpicker_values.label_title_color,'font-family': selected_font_values.title_font_family }">{{package_booking_form_settings.package_form_title}}</div>
                                            </el-col> 
                                            <el-col class="bpp-package-head-col bpp-filter-package-search" :xs="24" :sm="24" :md="24" :lg="9" :xl="9">  
                                                <div class="field bpp-package-head-row bpa-form-control">
                                                    <input type="text" class="bpp-front-form-control bpp-package-search" :style="{'color': selected_colorpicker_values.label_title_color,  'font-family': selected_font_values.title_font_family,'border-color':selected_colorpicker_values.border_color}" :placeholder="package_booking_form_settings.package_search_placeholder" ></input>
                                                    <el-button class="bpp-front-btn" :style="{ 'background': selected_colorpicker_values.primary_color, 'border-color': selected_colorpicker_values.primary_color, color: selected_colorpicker_values.price_button_text_color,'font-size': selected_font_values.sub_title_font_size+'px','font-family': selected_font_values.title_font_family,'font-size': selected_font_values.sub_title_font_size+'px'}">
                                                        <span class="bpp-search-btn-txt">{{package_booking_form_settings.package_search_button}}</span>
                                                    </el-button>
                                                </div>
                                            </el-col>
                                        </el-row>
                                    </div>
                                    <div class="bpp-package-list-wrapper">
                                        <el-row justify="space-between" type="flex" :gutter="12" class="bpp-package-list-row">
                                            <el-col class="bpp-package-list-col" :xs="24" :sm="24" :md="24" :lg="12" :xl="12" :style="{ 'background': selected_colorpicker_values.background_color }">
                                                <div class="bpp-package-list-inner">
                                                    <div class="bpp-package-slider">
                                                        <?php /*Image caroseal */ ?>
                                                        <el-carousel :interval="package_booking_form_settings.autoplay_interval" trigger="click" arrow="never" :autoplay="package_booking_form_settings.auto_scroll_image" :indicator-position="package_booking_form_settings.hide_image_indicator == true ? 'none' : 'inside'">
                                                            <el-carousel-item >
                                                                <img src="<?php echo esc_url(BOOKINGPRESS_PACKAGE_URL . '/images/sample-package-img1.png'); ?>" alt="Sample Package">
                                                            </el-carousel-item>
                                                            <el-carousel-item>
                                                                <img src="<?php echo esc_url(BOOKINGPRESS_PACKAGE_URL . '/images/sample-package-img2.png'); ?>" alt="Sample Package">
                                                            </el-carousel-item>
                                                            <el-carousel-item>
                                                                <img src="<?php echo esc_url(BOOKINGPRESS_PACKAGE_URL . '/images/sample-package-img3.png'); ?>" alt="Sample Package">
                                                            </el-carousel-item>
                                                        </el-carousel> 
                                                    </div>
                                                    <div class="bpp-package-detail">
                                                        <div class="bpp-package-detail-left">
                                                            <div class="bpp-package-name" :style="{'color': selected_colorpicker_values.label_title_color,'font-family': selected_font_values.title_font_family }"><?php esc_html_e('Sample Package 1', 'bookingpress-package'); ?></div>
                                                                <div class="bpp-package-duration">
                                                                    <div class="bpa-ma-dt__time-val">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" :style="{ 'fill': selected_colorpicker_values.sub_title_color}" viewBox="0 0 24 24"><path d="M0 0h24v24H0V0z" fill="none"/><path d="M11.99 2C6.47 2 2 6.48 2 12s4.47 10 9.99 10C17.52 22 22 17.52 22 12S17.52 2 11.99 2zM12 20c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8zm-.22-13h-.06c-.4 0-.72.32-.72.72v4.72c0 .35.18.68.49.86l4.15 2.49c.34.2.78.1.98-.24.21-.34.1-.79-.25-.99l-3.87-2.3V7.72c0-.4-.32-.72-.72-.72z"/></svg>
                                                                    <span :style="{ 'color': selected_colorpicker_values.sub_title_color, 'font-size': selected_font_values.sub_title_font_size+'px', 'font-family': selected_font_values.title_font_family }"><?php esc_html_e('1 Month', 'bookingpress-package'); ?></span>
                                                                </div>
                                                            </div>
                                                        </div>    
                                                        <div class="bpp-package-detail-right">
                                                            <div class="bpp-package-price">
                                                                <span class="bpp-package-realprice" v-if="package_booking_form_settings.hide_real_price == false" :style="{'color': selected_colorpicker_values.content_color,'font-family': selected_font_values.title_font_family }"><?php echo esc_html($bookingpress_price); ?></span>
                                                                <span class="bpp-package-discprice" :style="{'color': selected_colorpicker_values.primary_color,'font-family': selected_font_values.title_font_family }" ><?php echo esc_html($bookingpress_service_price1); ?></span>
                                                            </div>
                                                            <div class="bpp-package-button">
                                                                <el-button class="bpa-btn bpa-btn__medium bpa-btn--full-width bpp-buy-now-button"  :style="{ 'background': selected_colorpicker_values.primary_color, 'border-color': selected_colorpicker_values.primary_color, color: selected_colorpicker_values.price_button_text_color,'font-size': selected_font_values.sub_title_font_size+'px','font-family': selected_font_values.title_font_family,'font-size': selected_font_values.sub_title_font_size+'px'}">{{package_booking_form_settings.package_buy_now_nutton_text}}</el-button>
                                                            </div>
                                                        </div>   
                                                    </div>
                                                    <div v-if="package_booking_form_settings.hide_package_description != true" class="bpp-package-description" :style="{'color': selected_colorpicker_values.label_title_color,'font-family': selected_font_values.title_font_family }">
                                                        <div class="bpp-package-description-excerpt" :style="{'color': selected_colorpicker_values.content_color,'font-family': selected_font_values.title_font_family }">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam varius viverra lectus
                                                        </div>
                                                    </div>
                                                    <div class="bpp-package-services-list">
                                                        <div class="bpp-package-service-include-text" :style="{'color': selected_colorpicker_values.label_title_color,'font-family': selected_font_values.title_font_family }">{{package_booking_form_settings.package_services_include_text}}</div>   
                                                        <div class="bpp-package-service">
                                                            <div class="bpp-package-service-nm" :style="{'color': selected_colorpicker_values.sub_title_color,'font-family': selected_font_values.title_font_family }"><span class="bpp-package-indicator"></span><?php esc_html_e('Sample service 1', 'bookingpress-package'); ?></div>
                                                            <div class="bpp-package-service-dur" :style="{'color': selected_colorpicker_values.sub_title_color,'font-family': selected_font_values.title_font_family }"><span class="bpp-package-indicator"></span><?php esc_html_e('30 m', 'bookingpress-package'); ?></div>
                                                            <div class="bpp-package-service-no-app" :style="{'color': selected_colorpicker_values.label_title_color,'font-family': selected_font_values.title_font_family }">x12</div>    
                                                        </div>
                                                        <div class="bpp-package-service">
                                                            <div class="bpp-package-service-nm" :style="{'color': selected_colorpicker_values.sub_title_color,'font-family': selected_font_values.title_font_family }"><span class="bpp-package-indicator"></span><?php esc_html_e('Sample service 2', 'bookingpress-package'); ?></div>
                                                            <div class="bpp-package-service-dur" :style="{'color': selected_colorpicker_values.sub_title_color,'font-family': selected_font_values.title_font_family }"><span class="bpp-package-indicator"></span><?php esc_html_e('45 m', 'bookingpress-package'); ?></div>
                                                            <div class="bpp-package-service-no-app" :style="{'color': selected_colorpicker_values.label_title_color,'font-family': selected_font_values.title_font_family }">x4</div>    
                                                        </div>
                                                        <div class="bpp-package-service">
                                                            <div class="bpp-package-service-nm" :style="{'color': selected_colorpicker_values.sub_title_color,'font-family': selected_font_values.title_font_family }"><span class="bpp-package-indicator"></span><?php esc_html_e('Sample service 3', 'bookingpress-package'); ?></div>
                                                            <div class="bpp-package-service-dur" :style="{'color': selected_colorpicker_values.sub_title_color,'font-family': selected_font_values.title_font_family }"><span class="bpp-package-indicator"></span><?php esc_html_e('1 hr', 'bookingpress-package'); ?></div>
                                                            <div class="bpp-package-service-no-app":style="{'color': selected_colorpicker_values.label_title_color,'font-family': selected_font_values.title_font_family }">x2</div>    
                                                        </div>
                                                    </div>                                                        
                                                </div>
                                            </el-col>

                                             <el-col class="bpp-package-list-col" :xs="24" :sm="24" :md="24" :lg="12" :xl="12" :style="{ 'background': selected_colorpicker_values.background_color }">
                                                <div class="bpp-package-list-inner">
                                                    <div class="bpp-package-slider">
                                                        <?php /*Image caroseal */ ?>
                                                        <el-carousel :interval="package_booking_form_settings.autoplay_interval" trigger="click" arrow="never" :autoplay="package_booking_form_settings.auto_scroll_image" :indicator-position="package_booking_form_settings.hide_image_indicator == true ? 'none' : 'inside'">
                                                            <el-carousel-item >
                                                                <img src="<?php echo esc_url(BOOKINGPRESS_PACKAGE_URL . '/images/sample-package2-img1.png'); ?>" alt="Sample Package">
                                                            </el-carousel-item>
                                                            <el-carousel-item>
                                                                <img src="<?php echo esc_url(BOOKINGPRESS_PACKAGE_URL . '/images/sample-package2-img2.png'); ?>" alt="Sample Package">
                                                            </el-carousel-item>
                                                          <!--   <el-carousel-item>
                                                                <img src="<?php echo esc_url(BOOKINGPRESS_PACKAGE_URL . '/images/sample-package-img3.png'); ?>" alt="Sample Package">
                                                            </el-carousel-item> -->
                                                        </el-carousel> 
                                                    </div>
                                                    <div class="bpp-package-detail">
                                                        <div class="bpp-package-detail-left">
                                                            <div class="bpp-package-name" :style="{'color': selected_colorpicker_values.label_title_color,'font-family': selected_font_values.title_font_family }"><?php esc_html_e('Sample Package 1', 'bookingpress-package'); ?></div>
                                                                <div class="bpp-package-duration">
                                                                    <div class="bpa-ma-dt__time-val">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" :style="{ 'fill': selected_colorpicker_values.sub_title_color}" viewBox="0 0 24 24"><path d="M0 0h24v24H0V0z" fill="none"/><path d="M11.99 2C6.47 2 2 6.48 2 12s4.47 10 9.99 10C17.52 22 22 17.52 22 12S17.52 2 11.99 2zM12 20c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8zm-.22-13h-.06c-.4 0-.72.32-.72.72v4.72c0 .35.18.68.49.86l4.15 2.49c.34.2.78.1.98-.24.21-.34.1-.79-.25-.99l-3.87-2.3V7.72c0-.4-.32-.72-.72-.72z"/></svg>
                                                                    <span :style="{ 'color': selected_colorpicker_values.sub_title_color, 'font-size': selected_font_values.sub_title_font_size+'px', 'font-family': selected_font_values.title_font_family }"><?php esc_html_e('1 Month', 'bookingpress-package'); ?></span>
                                                                </div>
                                                            </div>
                                                        </div>    
                                                        <div class="bpp-package-detail-right">
                                                            <div class="bpp-package-price">
                                                                <span class="bpp-package-realprice" v-if="package_booking_form_settings.hide_real_price == false" :style="{'color': selected_colorpicker_values.content_color,'font-family': selected_font_values.title_font_family }"><?php echo esc_html($bookingpress_service_price1); ?></span>
                                                                <span class="bpp-package-discprice" :style="{'color': selected_colorpicker_values.primary_color,'font-family': selected_font_values.title_font_family }" ><?php echo esc_html($bookingpress_service_price2); ?></span>
                                                            </div>
                                                            <div class="bpp-package-button">
                                                                <el-button class="bpa-btn bpa-btn__medium bpa-btn--full-width bpp-buy-now-button"  :style="{ 'background': selected_colorpicker_values.primary_color, 'border-color': selected_colorpicker_values.primary_color, color: selected_colorpicker_values.price_button_text_color,'font-size': selected_font_values.sub_title_font_size+'px','font-family': selected_font_values.title_font_family,'font-size': selected_font_values.sub_title_font_size+'px'}">{{package_booking_form_settings.package_buy_now_nutton_text}}</el-button>
                                                            </div>
                                                        </div>   
                                                    </div>
                                                    <div class="bpp-package-description" v-if="package_booking_form_settings.hide_package_description != true" :style="{'color': selected_colorpicker_values.label_title_color,'font-family': selected_font_values.title_font_family }">
                                                        <div class="bpp-package-description-excerpt" :style="{'color': selected_colorpicker_values.content_color,'font-family': selected_font_values.title_font_family }">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam varius viverra lectus
                                                        </div>
                                                    </div>
                                                    <div class="bpp-package-services-list">
                                                        <div class="bpp-package-service-include-text" :style="{'color': selected_colorpicker_values.label_title_color,'font-family': selected_font_values.title_font_family }">{{package_booking_form_settings.package_services_include_text}}</div>   
                                                        <div class="bpp-package-service">
                                                            <div class="bpp-package-service-nm" :style="{'color': selected_colorpicker_values.sub_title_color,'font-family': selected_font_values.title_font_family }"><span class="bpp-package-indicator"></span><?php esc_html_e('Sample service 1', 'bookingpress-package'); ?></div>
                                                            <div class="bpp-package-service-dur" :style="{'color': selected_colorpicker_values.sub_title_color,'font-family': selected_font_values.title_font_family }"><span class="bpp-package-indicator"></span><?php esc_html_e('30 m', 'bookingpress-package'); ?></div>
                                                            <div class="bpp-package-service-no-app" :style="{'color': selected_colorpicker_values.label_title_color,'font-family': selected_font_values.title_font_family }">x12</div>    
                                                        </div>
                                                        <div class="bpp-package-service">
                                                            <div class="bpp-package-service-nm" :style="{'color': selected_colorpicker_values.sub_title_color,'font-family': selected_font_values.title_font_family }"><span class="bpp-package-indicator"></span><?php esc_html_e('Sample service 2', 'bookingpress-package'); ?></div>
                                                            <div class="bpp-package-service-dur" :style="{'color': selected_colorpicker_values.sub_title_color,'font-family': selected_font_values.title_font_family }"><span class="bpp-package-indicator"></span><?php esc_html_e('45 m', 'bookingpress-package'); ?></div>
                                                            <div class="bpp-package-service-no-app" :style="{'color': selected_colorpicker_values.label_title_color,'font-family': selected_font_values.title_font_family }">x4</div>    
                                                        </div>
                                                        <div class="bpp-package-service">
                                                            <div class="bpp-package-service-nm" :style="{'color': selected_colorpicker_values.sub_title_color,'font-family': selected_font_values.title_font_family }"><span class="bpp-package-indicator"></span><?php esc_html_e('Sample service 3', 'bookingpress-package'); ?></div>
                                                            <div class="bpp-package-service-dur" :style="{'color': selected_colorpicker_values.sub_title_color,'font-family': selected_font_values.title_font_family }"><span class="bpp-package-indicator"></span><?php esc_html_e('1 hr', 'bookingpress-package'); ?></div>
                                                            <div class="bpp-package-service-no-app":style="{'color': selected_colorpicker_values.label_title_color,'font-family': selected_font_values.title_font_family }">x2</div>    
                                                        </div>
                                                    </div>                                                        
                                                </div>
                                            </el-col>
                                        </el-row>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </el-col> 
                    <el-col :xs="4" :sm="4" :md="4" :lg="4" :xl="4">
                        <div class="bpa-customize-step-side-panel">
                            <div class="bpa-cs-sp--heading">
                                <h4><?php esc_html_e('Package Label Settings', 'bookingpress-package'); ?></h4>
                            </div>                  
                            <div class="bpa-cs-sp-sub-module bpa-cs-sp--form-controls">                                        
                                <h5><?php esc_html_e('Common field labels', 'bookingpress-package'); ?></h5>                                        
                                <div class="bpa-sm--item">
                                    <label class="bpa-form-label"><?php esc_html_e('Package Form Label', 'bookingpress-package'); ?></label>
                                    <el-input v-model="package_booking_form_settings.package_form_title" class="bpa-form-control"></el-input>
                                </div>
                                <div class="bpa-sm--item">
                                    <label class="bpa-form-label"><?php esc_html_e('Package search placeholder', 'bookingpress-package'); ?></label>
                                    <el-input v-model="package_booking_form_settings.package_search_placeholder" class="bpa-form-control"></el-input>
                                </div>                                     
                                <div class="bpa-sm--item">
                                    <label class="bpa-form-label"><?php esc_html_e('Search button', 'bookingpress-package'); ?></label>
                                    <el-input v-model="package_booking_form_settings.package_search_button" class="bpa-form-control"></el-input>
                                </div> 
                                <div class="bpa-sm--item">
                                    <label class="bpa-form-label"><?php esc_html_e('No Package Found', 'bookingpress-package'); ?></label>
                                    <el-input v-model="package_booking_form_settings.no_package_found_msg" class="bpa-form-control"></el-input>
                                </div>                                   
                                <div class="bpa-sm--item">
                                    <label class="bpa-form-label"><?php esc_html_e('Buy now button', 'bookingpress-package'); ?></label>
                                    <el-input v-model="package_booking_form_settings.package_buy_now_nutton_text" class="bpa-form-control"></el-input>
                                </div>   
                                <div class="bpa-sm--item">
                                    <label class="bpa-form-label"><?php esc_html_e('Services Includes text', 'bookingpress-package'); ?></label>
                                    <el-input v-model="package_booking_form_settings.package_services_include_text" class="bpa-form-control"></el-input>
                                </div>  
                                <div class="bpa-sm--item">
                                    <label class="bpa-form-label"><?php esc_html_e('Services Show More text', 'bookingpress-package'); ?></label>
                                    <el-input v-model="package_booking_form_settings.package_services_show_more_text" class="bpa-form-control"></el-input>
                                </div>
                                <div class="bpa-sm--item">
                                    <label class="bpa-form-label"><?php esc_html_e('Services Show Less text', 'bookingpress-package'); ?></label>
                                    <el-input v-model="package_booking_form_settings.package_services_show_less_text" class="bpa-form-control"></el-input>
                                </div>                                
                                <div class="bpa-sm--item">
                                    <label class="bpa-form-label"><?php esc_html_e('Package Description Read More', 'bookingpress-package'); ?></label>
                                    <el-input v-model="package_booking_form_settings.package_desc_read_more_text" class="bpa-form-control"></el-input>
                                </div>
                                <div class="bpa-sm--item">
                                    <label class="bpa-form-label"><?php esc_html_e('Package Description Show Less', 'bookingpress-package'); ?></label>
                                    <el-input v-model="package_booking_form_settings.package_desc_show_less_text" class="bpa-form-control"></el-input>
                                </div>
                                <div class="bpa-sm--item">
                                    <label class="bpa-form-label"><?php esc_html_e('Go back button', 'bookingpress-package'); ?></label>
                                    <el-input v-model="package_booking_form_settings.package_go_back_button_text" class="bpa-form-control"></el-input>
                                </div>

                                <div class="bpa-sm--item">
                                    <label class="bpa-form-label"><?php esc_html_e('Package month label', 'bookingpress-package'); ?></label>
                                    <el-input v-model="package_booking_form_settings.package_month_text" class="bpa-form-control"></el-input>
                                </div>
                                <div class="bpa-sm--item">
                                    <label class="bpa-form-label"><?php esc_html_e('Package months label', 'bookingpress-package'); ?></label>
                                    <el-input v-model="package_booking_form_settings.package_months_text" class="bpa-form-control"></el-input>
                                </div>
                                <div class="bpa-sm--item">
                                    <label class="bpa-form-label"><?php esc_html_e('Package year label', 'bookingpress-package'); ?></label>
                                    <el-input v-model="package_booking_form_settings.package_year_text" class="bpa-form-control"></el-input>
                                </div>
                                <div class="bpa-sm--item">
                                    <label class="bpa-form-label"><?php esc_html_e('Package years label', 'bookingpress-package'); ?></label>
                                    <el-input v-model="package_booking_form_settings.package_years_text" class="bpa-form-control"></el-input>
                                </div>                                
                                <div class="bpa-sm--item">
                                    <label class="bpa-form-label"><?php esc_html_e('Package day label', 'bookingpress-package'); ?></label>
                                    <el-input v-model="package_booking_form_settings.package_day_text" class="bpa-form-control"></el-input>
                                </div>
                                <div class="bpa-sm--item">
                                    <label class="bpa-form-label"><?php esc_html_e('Package days label', 'bookingpress-package'); ?></label>
                                    <el-input v-model="package_booking_form_settings.package_days_text" class="bpa-form-control"></el-input>
                                </div>                                

                                <h5><?php esc_html_e('User Details step labels', 'bookingpress-package'); ?></h5>         
                                <div class="bpa-sm--item">
                                    <label class="bpa-form-label"><?php esc_html_e('User details step', 'bookingpress-package'); ?></label>
                                    <el-input v-model="package_booking_form_settings.user_details_step_label" class="bpa-form-control"></el-input>
                                </div>
                                <h5><?php esc_html_e('Login Related labels', 'bookingpress-package'); ?></h5>         
                                <div class="bpa-sm--item">
                                    <label class="bpa-form-label"><?php esc_html_e('Login form title', 'bookingpress-package'); ?></label>
                                    <el-input v-model="package_booking_form_settings.login_form_title_label" class="bpa-form-control"></el-input>
                                </div>
                                <div class="bpa-sm--item">
                                    <label class="bpa-form-label"><?php esc_html_e('Username / Email field label', 'bookingpress-package'); ?></label>
                                    <el-input v-model="package_booking_form_settings.login_form_username_field_label" class="bpa-form-control"></el-input>
                                </div>
                                <div class="bpa-sm--item">
                                    <label class="bpa-form-label"><?php esc_html_e('Username / Email field Placeholder', 'bookingpress-package'); ?></label>
                                    <el-input v-model="package_booking_form_settings.login_form_username_field_placeholder" class="bpa-form-control"></el-input>
                                </div>
                                <div class="bpa-sm--item">
                                    <label class="bpa-form-label"><?php esc_html_e('Password label', 'bookingpress-package'); ?></label>
                                    <el-input v-model="package_booking_form_settings.login_form_password_field_label" class="bpa-form-control"></el-input>
                                </div>
                                <div class="bpa-sm--item">
                                    <label class="bpa-form-label"><?php esc_html_e('Password field Placeholder', 'bookingpress-package'); ?></label>
                                    <el-input v-model="package_booking_form_settings.login_form_password_field_placeholder" class="bpa-form-control"></el-input>
                                </div>
                                <div class="bpa-sm--item">
                                    <label class="bpa-form-label"><?php esc_html_e('User name required field label', 'bookingpress-package'); ?></label>
                                    <el-input v-model="package_booking_form_settings.login_form_username_required_field_label" class="bpa-form-control"></el-input>
                                </div>
                                <div class="bpa-sm--item">
                                    <label class="bpa-form-label"><?php esc_html_e('Password required field label', 'bookingpress-package'); ?></label>
                                    <el-input v-model="package_booking_form_settings.login_form_password_required_field_label" class="bpa-form-control"></el-input>
                                </div>
                                <div class="bpa-sm--item">
                                    <label class="bpa-form-label"><?php esc_html_e('Remember Me field label', 'bookingpress-package'); ?></label>
                                    <el-input v-model="package_booking_form_settings.remember_me_field_label" class="bpa-form-control"></el-input>
                                </div>
                                <div class="bpa-sm--item">
                                    <label class="bpa-form-label"><?php esc_html_e('Login button label', 'bookingpress-package'); ?></label>
                                    <el-input v-model="package_booking_form_settings.login_button_label" class="bpa-form-control"></el-input>
                                </div>
                                <div class="bpa-sm--item">
                                    <label class="bpa-form-label"><?php esc_html_e('Forgot Password link label', 'bookingpress-package'); ?></label>
                                    <el-input v-model="package_booking_form_settings.forgot_password_link_label" class="bpa-form-control"></el-input>
                                </div>
                                <div class="bpa-sm--item">
                                    <label class="bpa-form-label"><?php esc_html_e('Error message label', 'bookingpress-package'); ?></label>
                                    <el-input v-model="package_booking_form_settings.login_error_message_label" class="bpa-form-control"></el-input>
                                </div>    
                                <div class="bpa-sm--item">
                                    <label class="bpa-form-label"><?php esc_html_e('SignUp link label', 'bookingpress-package'); ?></label>
                                    <el-input v-model="package_booking_form_settings.login_form_signup_link_text" class="bpa-form-control"></el-input>
                                </div> 
                                <div class="bpa-sm--item">
                                    <label class="bpa-form-label"><?php esc_html_e('Don\'t have an account label', 'bookingpress-package'); ?></label>
                                    <el-input v-model="package_booking_form_settings.login_form_dont_have_acc_text" class="bpa-form-control"></el-input>
                                </div>                
                                <h5><?php esc_html_e('Forgot Password related labels', 'bookingpress-package'); ?></h5>                                        
                                <div class="bpa-sm--item">
                                    <label class="bpa-form-label"><?php esc_html_e('Forgot Password form title', 'bookingpress-package'); ?></label>
                                    <el-input v-model="package_booking_form_settings.forgot_password_form_title" class="bpa-form-control"></el-input>
                                </div>
                                <div class="bpa-sm--item">
                                    <label class="bpa-form-label"><?php esc_html_e('Email address field label', 'bookingpress-package'); ?></label>
                                    <el-input v-model="package_booking_form_settings.forgot_password_email_address_field_label" class="bpa-form-control"></el-input>
                                </div>  
                                <div class="bpa-sm--item">
                                    <label class="bpa-form-label"><?php esc_html_e('Email address placeholder label', 'bookingpress-package'); ?></label>
                                    <el-input v-model="package_booking_form_settings.forgot_password_email_address_placeholder" class="bpa-form-control"></el-input>
                                </div>   
                                <div class="bpa-sm--item">
                                    <label class="bpa-form-label"><?php esc_html_e('Email required field label', 'bookingpress-package'); ?></label>
                                    <el-input v-model="package_booking_form_settings.forgot_password_email_required_field_label" class="bpa-form-control"></el-input>
                                </div>
                                <div class="bpa-sm--item">
                                    <label class="bpa-form-label"><?php esc_html_e('Forgot password button label', 'bookingpress-package'); ?></label>
                                    <el-input v-model="package_booking_form_settings.forgot_password_button_label" class="bpa-form-control"></el-input>
                                </div> 
                                <div class="bpa-sm--item">
                                    <label class="bpa-form-label"><?php esc_html_e('Error message label', 'bookingpress-package'); ?></label>
                                    <el-input v-model="package_booking_form_settings.forgot_password_error_message" class="bpa-form-control"></el-input>
                                </div>           
                                <div class="bpa-sm--item">
                                    <label class="bpa-form-label"><?php esc_html_e('Success message label', 'bookingpress-package'); ?></label>
                                    <el-input v-model="package_booking_form_settings.forgot_password_success_message_label" class="bpa-form-control"></el-input>
                                </div>                              
                                <div class="bpa-sm--item">
                                    <label class="bpa-form-label"><?php esc_html_e('Sign In Link Label', 'bookingpress-package'); ?></label>
                                    <el-input v-model="package_booking_form_settings.forgot_password_sing_in_link_label" class="bpa-form-control"></el-input>
                                </div>
                                <h5><?php esc_html_e('SignUp related labels', 'bookingpress-package'); ?></h5>                                        
                                <div class="bpa-sm--item">
                                    <label class="bpa-form-label"><?php esc_html_e('Signup form title', 'bookingpress-package'); ?></label>
                                    <el-input v-model="package_booking_form_settings.signup_account_form_title" class="bpa-form-control"></el-input>
                                </div>
                                <div class="bpa-sm--item">
                                    <label class="bpa-form-label"><?php esc_html_e('Full name field label', 'bookingpress-package'); ?></label>
                                    <el-input v-model="package_booking_form_settings.signup_account_fullname_label" class="bpa-form-control"></el-input>
                                </div>
                                <div class="bpa-sm--item">
                                    <label class="bpa-form-label"><?php esc_html_e('Email field label', 'bookingpress-package'); ?></label>
                                    <el-input v-model="package_booking_form_settings.signup_account_email_label" class="bpa-form-control"></el-input>
                                </div>
                                <div class="bpa-sm--item">
                                    <label class="bpa-form-label"><?php esc_html_e('Mobile Number field label', 'bookingpress-package'); ?></label>
                                    <el-input v-model="package_booking_form_settings.signup_account_mobile_number_label" class="bpa-form-control"></el-input>
                                </div>
                                <div class="bpa-sm--item">
                                    <label class="bpa-form-label"><?php esc_html_e('Password field label', 'bookingpress-package'); ?></label>
                                    <el-input v-model="package_booking_form_settings.signup_account_password_label" class="bpa-form-control"></el-input>
                                </div>
                                <div class="bpa-sm--item">
                                    <label class="bpa-form-label"><?php esc_html_e('Full name field placeholder', 'bookingpress-package'); ?></label>
                                    <el-input v-model="package_booking_form_settings.signup_account_fullname_placeholder" class="bpa-form-control"></el-input>
                                </div>
                                <div class="bpa-sm--item">
                                    <label class="bpa-form-label"><?php esc_html_e('Email field placeholder', 'bookingpress-package'); ?></label>
                                    <el-input v-model="package_booking_form_settings.signup_account_email_placeholder" class="bpa-form-control"></el-input>
                                </div>
                                <div class="bpa-sm--item">
                                    <label class="bpa-form-label"><?php esc_html_e('Password field placeholder', 'bookingpress-package'); ?></label>
                                    <el-input v-model="package_booking_form_settings.signup_account_password_placeholder" class="bpa-form-control"></el-input>
                                </div>
                                <div class="bpa-sm--item">
                                    <label class="bpa-form-label"><?php esc_html_e('Full name required message', 'bookingpress-package'); ?></label>
                                    <el-input v-model="package_booking_form_settings.signup_account_fullname_required_message" class="bpa-form-control"></el-input>
                                </div>
                                <div class="bpa-sm--item">
                                    <label class="bpa-form-label"><?php esc_html_e('Email required message', 'bookingpress-package'); ?></label>
                                    <el-input v-model="package_booking_form_settings.signup_account_email_required_message" class="bpa-form-control"></el-input>
                                </div>
                                <div class="bpa-sm--item">
                                    <label class="bpa-form-label"><?php esc_html_e('Mobile Number required message', 'bookingpress-package'); ?></label>
                                    <el-input v-model="package_booking_form_settings.signup_account_mobile_number_required_message" class="bpa-form-control"></el-input>
                                </div>
                                <div class="bpa-sm--item">
                                    <label class="bpa-form-label"><?php esc_html_e('Password required message', 'bookingpress-package'); ?></label>
                                    <el-input v-model="package_booking_form_settings.signup_account_password_required_message" class="bpa-form-control"></el-input>
                                </div>
                                <div class="bpa-sm--item">
                                    <label class="bpa-form-label"><?php esc_html_e('Invalid email message', 'bookingpress-package'); ?></label>
                                    <el-input v-model="package_booking_form_settings.signup_wrong_email_message" class="bpa-form-control"></el-input>
                                </div>
                                <div class="bpa-sm--item">
                                    <label class="bpa-form-label"><?php esc_html_e('Email already exists message', 'bookingpress-package'); ?></label>
                                    <el-input v-model="package_booking_form_settings.signup_email_exists" class="bpa-form-control"></el-input>
                                </div>

                                <div class="bpa-sm--item">
                                    <label class="bpa-form-label"><?php esc_html_e('Signup button title', 'bookingpress-package'); ?></label>
                                    <el-input v-model="package_booking_form_settings.signup_form_button_title" class="bpa-form-control"></el-input>
                                </div>                                                                        
                                <div class="bpa-sm--item">
                                    <label class="bpa-form-label"><?php esc_html_e('Already have account text', 'bookingpress-package'); ?></label>
                                    <el-input v-model="package_booking_form_settings.signup_form_already_have_acc_text" class="bpa-form-control"></el-input>
                                </div>
                                <div class="bpa-sm--item">
                                    <label class="bpa-form-label"><?php esc_html_e('Login link title', 'bookingpress-package'); ?></label>
                                    <el-input v-model="package_booking_form_settings.signup_form_login_link_text" class="bpa-form-control"></el-input>
                                </div>
                                <h5><?php esc_html_e('Basic Details labels', 'bookingpress-package'); ?></h5>    
                                <div class="bpa-sm--item">
                                    <label class="bpa-form-label"><?php esc_html_e('Basic Details form title', 'bookingpress-package'); ?></label>
                                    <el-input v-model="package_booking_form_settings.basic_details_form_title" class="bpa-form-control"></el-input>
                                </div>
                                <div class="bpa-sm--item">
                                    <label class="bpa-form-label"><?php esc_html_e('Submit button title', 'bookingpress-package'); ?></label>
                                    <el-input v-model="package_booking_form_settings.basic_details_submit_button_title" class="bpa-form-control"></el-input>
                                </div>
                                <h5><?php esc_html_e('Make Payment Form labels', 'bookingpress-package'); ?></h5> 
                                <div class="bpa-sm--item">
                                    <label class="bpa-form-label"><?php esc_html_e('Make payment tab title', 'bookingpress-package'); ?></label>
                                    <el-input v-model="package_booking_form_settings.make_payment_tab_title" class="bpa-form-control"></el-input>
                                </div>
                                <div class="bpa-sm--item">
                                    <label class="bpa-form-label"><?php esc_html_e('Make payment form title', 'bookingpress-package'); ?></label>
                                    <el-input v-model="package_booking_form_settings.make_payment_form_title" class="bpa-form-control"></el-input>
                                </div>
                                <div class="bpa-sm--item">
                                    <label class="bpa-form-label"><?php esc_html_e('Subtotal text', 'bookingpress-package'); ?></label>
                                    <el-input v-model="package_booking_form_settings.make_payment_subtotal_text" class="bpa-form-control"></el-input>
                                </div>
                                <div class="bpa-sm--item">
                                    <label class="bpa-form-label"><?php esc_html_e('Total amount text', 'bookingpress-package'); ?></label>
                                    <el-input v-model="package_booking_form_settings.make_payment_total_amount_text" class="bpa-form-control"></el-input>
                                </div>
                                <div class="bpa-sm--item">
                                    <label class="bpa-form-label"><?php esc_html_e('Select payment method text', 'bookingpress-package'); ?></label>
                                    <el-input v-model="package_booking_form_settings.make_payment_select_payment_method_text" class="bpa-form-control"></el-input>
                                </div>
                                <div class="bpa-sm--item">
                                    <label class="bpa-form-label"><?php esc_html_e('Buy package button text', 'bookingpress-package'); ?></label>
                                    <el-input v-model="package_booking_form_settings.make_payment_buy_package_btn_text" class="bpa-form-control"></el-input>
                                </div>
                                <h5><?php esc_html_e('Summary step labels', 'bookingpress-package'); ?></h5>    
                                <div class="bpa-sm--item">
                                    <label class="bpa-form-label"><?php esc_html_e('Summary step', 'bookingpress-package'); ?></label>
                                    <el-input v-model="package_booking_form_settings.summary_step_title" class="bpa-form-control"></el-input>
                                </div>
                                <div class="bpa-sm--item">
                                    <label class="bpa-form-label"><?php esc_html_e('Summary form title', 'bookingpress-package'); ?></label>
                                    <el-input v-model="package_booking_form_settings.summary_step_form_title" class="bpa-form-control"></el-input>
                                </div>
                                <div class="bpa-sm--item">
                                    <label class="bpa-form-label"><?php esc_html_e('Booking Id', 'bookingpress-package'); ?></label>
                                    <el-input v-model="package_booking_form_settings.summary_tab_booking_id_text" class="bpa-form-control"></el-input>
                                </div>
                                <div class="bpa-sm--item">
                                    <label class="bpa-form-label"><?php esc_html_e('Package booked success message', 'bookingpress-package'); ?></label>
                                    <el-input type="textarea" v-model="package_booking_form_settings.summary_tab_package_booked_success_message" class="bpa-form-control"></el-input>
                                </div>   
                                <div class="bpa-sm--item">
                                    <label class="bpa-form-label"><?php esc_html_e('Package booking information sent message', 'bookingpress-package'); ?></label>
                                    <el-input type="textarea" v-model="package_booking_form_settings.summary_tab_package_booking_information_sent_message" class="bpa-form-control"></el-input>
                                </div>                                     
                                <div class="bpa-sm--item">
                                    <label class="bpa-form-label"><?php esc_html_e('Package title', 'bookingpress-package'); ?></label>
                                    <el-input v-model="package_booking_form_settings.summary_tab_package_title_text" class="bpa-form-control"></el-input>
                                </div>
                                <div class="bpa-sm--item">
                                    <label class="bpa-form-label"><?php esc_html_e('Customer Name title', 'bookingpress-package'); ?></label>
                                    <el-input v-model="package_booking_form_settings.summary_tab_customer_title" class="bpa-form-control"></el-input>
                                </div>
                                <div class="bpa-sm--item">
                                    <label class="bpa-form-label"><?php esc_html_e('Book Appointment title', 'bookingpress-package'); ?></label>
                                    <el-input v-model="package_booking_form_settings.summary_tab_book_appointment_btn_text" class="bpa-form-control"></el-input>
                                </div>
                                <div class="bpa-sm--item">
                                    <label class="bpa-form-label"><?php esc_html_e('Card details title', 'bookingpress-package'); ?></label>
                                    <el-input v-model="package_booking_form_settings.pkg_card_details_text" class="bpa-form-control"></el-input>
                                </div>  
                                <div class="bpa-sm--item">
                                    <label class="bpa-form-label"><?php esc_html_e('Card name placeholder', 'bookingpress-package'); ?></label>
                                    <el-input v-model="package_booking_form_settings.pkg_card_name_text" class="bpa-form-control"></el-input>
                                </div>  
                                <div class="bpa-sm--item">
                                    <label class="bpa-form-label"><?php esc_html_e('Card number placeholder', 'bookingpress-package'); ?></label>
                                    <el-input v-model="package_booking_form_settings.pkg_card_number_text" class="bpa-form-control"></el-input>
                                </div>  
                                <div class="bpa-sm--item">
                                    <label class="bpa-form-label"><?php esc_html_e('Expire month placeholder', 'bookingpress-package'); ?></label>
                                    <el-input v-model="package_booking_form_settings.pkg_expire_month_text" class="bpa-form-control"></el-input>
                                </div>  
                                <div class="bpa-sm--item">
                                    <label class="bpa-form-label"><?php esc_html_e('Expire year placeholder', 'bookingpress-package'); ?></label>
                                    <el-input v-model="package_booking_form_settings.pkg_expire_year_text" class="bpa-form-control"></el-input>
                                </div>                
                                <div class="bpa-sm--item">
                                    <label class="bpa-form-label"><?php esc_html_e('Cvv placeholder', 'bookingpress-package'); ?></label>
                                    <el-input v-model="package_booking_form_settings.pkg_cvv_text" class="bpa-form-control"></el-input>
                                </div>       
                                <div class="bpa-sm--item">
                                    <label class="bpa-form-label"><?php esc_html_e('Tax title', 'bookingpress-package'); ?></label>
                                    <el-input v-model="package_booking_form_settings.package_tax_title" class="bpa-form-control"></el-input>
                                </div>                                                                     
                                <div class="bpa-sm--item">
                                    <label class="bpa-form-label"><?php esc_html_e('PayPal payment title', 'bookingpress-package'); ?></label>
                                    <el-input v-model="package_booking_form_settings.pkg_paypal_text" class="bpa-form-control"></el-input>
                                </div>
                                <div class="bpa-sm--item">
                                    <label class="bpa-form-label"><?php esc_html_e('Package purchase limit message', 'bookingpress-package'); ?></label>
                                    <el-input type="textarea" :rows="4" resize="none" v-model="package_booking_form_settings.package_purchase_limit_message" class="bpa-form-control"></el-input>
                                </div>
                                
                                <?php
                                    do_action('bookingpress_add_package_label_settings_dynamically');
                                ?>                                     
                            </div>   
                        </div>   
                    </el-col>
                </el-row>
            </div>                    
        </el-tab-pane>            
        <?php 
        }


    }
    
	global $bookingpress_package_customize;
	$bookingpress_package_customize = new bookingpress_package_customize();
}