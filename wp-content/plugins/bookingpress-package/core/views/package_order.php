<?php
	global $wpdb, $bookingpress_ajaxurl, $BookingPress, $bookingpress_common_date_format, $tbl_bookingpress_appointment_bookings,$BookingPressPro, $bookingpress_global_options;
	$bookingpress_common_datetime_format = $bookingpress_common_date_format . ' HH:mm';
	$bookingpres_default_time_format = $BookingPress->bookingpress_get_settings('default_time_format','general_setting');
    //$bookingpress_count_record = $wpdb->get_var("SELECT COUNT(bookingpress_package_booking_id) as total FROM {$tbl_bookingpress_package_bookings}"); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- Reason: $tbl_bookingpress_package_bookings is table name defined globally. False Positive alarm

?>
<el-main class="bpa-main-listing-card-container bpa-default-card bpa--is-page-non-scrollable-mob" id="all-page-main-container">
    <el-row type="flex" class="bpa-mlc-head-wrap">
        <el-col :xs="24" :sm="12" :md="12" :lg="12" :xl="12" class="bpa-mlc-left-heading">
            <h1 class="bpa-page-heading"><?php esc_html_e('Package Order', 'bookingpress-package'); ?></h1>
        </el-col>        
        <el-col :xs="24" :sm="12" :md="12" :lg="12" :xl="12">
            <div class="bpa-hw-right-btn-group">                
                <el-button class="bpa-btn bpa-btn--primary" @click="open_add_package_modal()"> 
                    <span class="material-icons-round">add</span> 
                    <?php esc_html_e('Add New', 'bookingpress-package'); ?>
                </el-button>

            </div>
        </el-col>
    </el-row>
    <div class="bpa-back-loader-container" id="bpa-page-loading-loader">
        <div class="bpa-back-loader"></div>
    </div>
    <div id="bpa-main-container">
        <div class="bpa-table-filter">                
            <el-row type="flex" :gutter="32">            
				<el-col :xs="24" :sm="24" :md="24" :lg="8" :xl="8">
                    <span class="bpa-form-label"><?php esc_html_e('Purchase Date', 'bookingpress-package'); ?></span>
                    <el-date-picker @focus="bookingpress_remove_date_range_picker_focus" class="bpa-form-control bpa-form-control--date-range-picker" format="<?php echo esc_html($bookingpress_common_date_format); ?>" v-model="package_date_range" type="daterange" start-placeholder="<?php esc_html_e('Start date', 'bookingpress-package'); ?>" end-placeholder="<?php esc_html_e('End date', 'bookingpress-package'); ?>" :popper-append-to-body="false" popper-class="bpa-el-select--is-with-navbar bpa-date-range-picker-widget-wrapper" range-separator=" - " value-format="yyyy-MM-dd" :picker-options="filter_pickerOptions"> </el-date-picker>
                </el-col>            
				<el-col :xs="24" :sm="24" :md="24" :lg="8" :xl="8">
                    <span class="bpa-form-label"><?php esc_html_e('Customer Name', 'bookingpress-package'); ?></span>    
                    <el-select class="bpa-form-control" v-model="search_customer_name" multiple filterable collapse-tags placeholder="<?php esc_html_e( 'Start typing to fetch customer', 'bookingpress-package' ); ?>" remote reserve-keyword	 :remote-method="bookingpress_get_search_customer_list" :loading="bookingpress_loading" :popper-append-to-body="false" popper-class="bpa-el-select--is-with-navbar">
						<el-option v-for="item in search_customer_list" :key="item.value" :label="item.text" :value="item.value"></el-option>
					</el-select>
                </el-col>
				<el-col :xs="24" :sm="24" :md="24" :lg="8" :xl="8">
                    <span class="bpa-form-label"><?php esc_html_e('Package Name', 'bookingpress-package'); ?></span>
                    <el-select class="bpa-form-control" v-model="search_package_name" multiple filterable collapse-tags 
                        placeholder="<?php esc_html_e('Select package', 'bookingpress-package'); ?>"
                        :popper-append-to-body="false" popper-class="bpa-el-select--is-with-navbar">                       
                            <el-option v-for="item in package_list" :key="item.bookingpress_package_id" :label="item.bookingpress_package_name" :value="item.bookingpress_package_id"></el-option>                        
                    </el-select>
                </el-col>            
            </el-row><br>
            <el-row type="flex" :gutter="32">
                <el-col :xs="24" :sm="24" :md="24" :lg="4" :xl="4">
                    <el-input class="bpa-form-control" v-model="search_package_id" placeholder="<?php esc_html_e('Package Order ID', 'bookingpress-package'); ?>" @input="isOnlyNumber($event)" >    
                    </el-input>
                </el-col>
				<el-col :xs="24" :sm="24" :md="24" :lg="12" :xl="12">
                    <el-input class="bpa-form-control" v-model="search_package" placeholder="<?php esc_html_e('Search for customers, package...', 'bookingpress-package'); ?>" >    
                    </el-input>
                </el-col>
				<el-col :xs="24" :sm="24" :md="24" :lg="8" :xl="8">
                    <div class="bpa-tf-btn-group">
                        <el-button class="bpa-btn bpa-btn__medium bpa-btn--full-width" @click="resetFilter">
                            <?php esc_html_e('Reset', 'bookingpress-package'); ?>
                        </el-button>
                        <el-button class="bpa-btn bpa-btn__medium bpa-btn--primary bpa-btn--full-width" @click="loadPackageOrder()">
                            <?php esc_html_e('Apply', 'bookingpress-package'); ?>
                        </el-button>
                    </div>
                </el-col>
            </el-row><br>
        </div>
        <div id="bpa-loader-div">
            <el-row type="flex" v-show="items.length == 0">
                <el-col :xs="24" :sm="24" :md="24" :lg="24" :xl="24">
                    <div class="bpa-data-empty-view">
                        <div class="bpa-ev-left-vector">
                            <picture>
                                <source srcset="<?php echo esc_url(BOOKINGPRESS_IMAGES_URL . '/data-grid-empty-view-vector.webp'); ?>" type="image/webp">
                                <img src="<?php echo esc_url(BOOKINGPRESS_IMAGES_URL . '/data-grid-empty-view-vector.png'); ?>">
                            </picture>
                        </div>
                        <div class="bpa-ev-right-content">
                            <h4><?php esc_html_e('No Record Found!', 'bookingpress-package'); ?></h4>
                            
                            <el-button class="bpa-btn bpa-btn--primary bpa-btn__medium" @click="open_add_package_modal()">                         
                                <span class="material-icons-round">add</span> 
                                <?php esc_html_e('Add New', 'bookingpress-package'); ?>
                            </el-button>
                        </div>
                    </div>
                </el-col>
            </el-row>
        </div>
        <el-row v-if="items.length > 0">
            <el-col :xs="24" :sm="24" :md="24" :lg="24" :xl="24">
                <el-container class="bpa-table-container">
                    <div class="bpa-back-loader-container" v-if="is_display_loader == '1'">
                        <div class="bpa-back-loader"></div>
                    </div>
                    <div class="bpa-tc__wrapper" v-if="current_screen_size == 'desktop'">
                        <el-table ref="multipleTable" class="bpa-manage-appointment-items" :data="items" @selection-change="handleSelectionChange" fit="false" @row-click="bookingpress_full_row_clickable" @expand-change="bookingpress_row_expand">
                            <el-table-column type="expand">
                                <template slot-scope="scope">
                                    <div class="bpa-view-appointment-card">
                                        <div class="bpa-vac--head">
                                            <div class="bpa-vac--head__left">											
                                                <span><?php esc_html_e('Booking ID', 'bookingpress-package'); ?>: #{{ scope.row.booking_id }}</span>
                                                <div class="bpa-left__service-detail">
                                                    <h2>{{ scope.row.service_name }}</h2>
                                                    <span class="bpa-sd__price">{{ scope.row.package_payment }}</span>
                                                </div>
                                            </div>
                                            <div class="bpa-hw-right-btn-group bpa-vac--head__right">
                                                <el-popconfirm 
                                                cancel-button-text='<?php esc_html_e( 'Close', 'bookingpress-package' ); ?>' 
                                                confirm-button-text='<?php esc_html_e( 'Cancel', 'bookingpress-package' ); ?>' 
                                                icon="false" 
                                                title="<?php esc_html_e( 'Are you sure you want to cancel this package?', 'bookingpress-package' ); ?>" 
                                                @confirm="bookingpress_change_package_order_status(scope.row.package_order_id, '3')" 
                                                confirm-button-type="bpa-btn bpa-btn__small bpa-btn--danger" 
                                                cancel-button-type="bpa-btn bpa-btn__small"
                                                v-if="scope.row.package_status != '3'">
                                                    <el-button type="text" slot="reference" class="bpa-btn" v-if="scope.row.package_status != '3'">
                                                        <span class="material-icons-round">close</span>
                                                        <?php esc_html_e( 'Cancel', 'bookingpress-package' ); ?>
                                                    </el-button>
                                                </el-popconfirm>
                                            </div>
                                        </div>
                                        <div class="bpa-vac--body">
                                            <el-row :gutter="56">
                                                <el-col :xs="24" :sm="24" :md="24" :lg="16" :xl="18">
                                                    <div class="bpa-vac-body--package-details">
                                                        <el-row :gutter="40">
                                                            <el-col :xs="24" :sm="24" :md="12" :lg="12" :xl="12">
                                                                <div class="bpa-ad__basic-details" v-if="scope.row.bookingpress_package_services.length != 0">
                                                                    <h4 class="bpa-vac__sec-heading"><?php esc_html_e('Services Detail', 'bookingpress-package'); ?></h4>
                                                                    <div class="bpa-bd__item">
                                                                        <div class="bpa-bd__item-head">
                                                                            <span><?php esc_html_e('Name', 'bookingpress-package'); ?></span>
                                                                        </div>
                                                                        <div class="bpa-bd__item-head">
                                                                            <span><?php esc_html_e('Remain Appo.', 'bookingpress-package'); ?></span>
                                                                        </div>                                                                        
                                                                    </div>
                                                                    <div v-for="pack_serv in scope.row.bookingpress_package_services" class="bpa-bd__item">
                                                                        <div class="bpa-bd__item-head">
                                                                            <h4>{{pack_serv.bookingpress_service_name}}</h4>
                                                                        </div>
                                                                        <div class="bpa-bd__item-body">
                                                                            <h4>{{pack_serv.service_remaining_appointment}}/{{pack_serv.bookingpress_no_of_appointments}}</h4>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </el-col>
                                                            <el-col :xs="24" :sm="24" :md="12" :lg="12" :xl="12">
                                                                <div class="bpa-ad__customer-details bpp-expand-cust-detail">
                                                                    <h4 class="bpa-vac__sec-heading"><?php esc_html_e('Customer Details', 'bookingpress-package'); ?></h4>
                                                                    <div class="bpa-bd__item"  v-if="scope.row.customer_name != ''">
                                                                        <div class="bpa-bd__item-head">
                                                                            <span>{{form_field_data.fullname}}</span>
                                                                        </div>
                                                                        <div class="bpa-bd__item-body">
                                                                            <h4>{{ scope.row.customer_name }}</h4>
                                                                        </div>
                                                                    </div>
                                                                    <div class="bpa-bd__item" v-if="scope.row.customer_first_name != ''">
                                                                        <div class="bpa-bd__item-head">
                                                                        <span>{{form_field_data.firstname}}</span>
                                                                        </div>
                                                                        <div class="bpa-bd__item-body">
                                                                            <h4>{{ scope.row.customer_first_name }}</h4>
                                                                        </div>
                                                                    </div>
                                                                    <div class="bpa-bd__item">
                                                                        <div class="bpa-bd__item-head" v-if="scope.row.customer_last_name != ''">
                                                                            <span>{{form_field_data.lastname}}</span>
                                                                        </div>
                                                                        <div class="bpa-bd__item-body" >
                                                                            <h4>{{ scope.row.customer_last_name }}</h4>
                                                                        </div>
                                                                    </div>
                                                                    <div class="bpa-bd__item" v-if="scope.row.customer_phone != ''">
                                                                        <div class="bpa-bd__item-head">
                                                                            <span>{{form_field_data.phone_number}}</span>
                                                                        </div>
                                                                        <div class="bpa-bd__item-body">
                                                                            <h4>{{ scope.row.customer_phone }}</h4>
                                                                        </div>
                                                                    </div>
                                                                    <div class="bpa-bd__item">
                                                                        <div class="bpa-bd__item-head">
                                                                            <span>{{form_field_data.email_address}}</span>
                                                                        </div>
                                                                        <div class="bpa-bd__item-body">
                                                                            <h4>{{ scope.row.customer_email }}</h4>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </el-col>
                                                        </el-row>
                                                    </div>													
                                                    <div class="bpa-vac-body--custom-fields" v-if="scope.row.custom_fields_values.length > 0">
														<h4 class="bpa-vac__sec-heading"><?php esc_html_e('Custom Fields', 'bookingpress-package'); ?></h4>
														<div class="bpa-cf__body">
															<el-row>
																<el-col :xs="24" :sm="24" :md="12" :lg="12" :xl="12" v-for="custom_fields in scope.row.custom_fields_values">
																	<div class="bpa-bd__item">
																		<div class="bpa-bd__item-head">
																			<span v-html="custom_fields.label"></span>
																		</div>
																		<div class="bpa-bd__item-body">
																			<h4 v-html="custom_fields.value"></h4>
																		</div>
																	</div>																
																</el-col>
															</el-row>
														</div>
													</div>                                                    
                                                </el-col>
                                                <el-col :xs="24" :sm="24" :md="24" :lg="8" :xl="6">
                                                    <div class="bpa-vac-body--payment-details">
                                                        <h4><?php esc_html_e('Payment Details', 'bookingpress-package'); ?></h4>
                                                        <div class="bpa-pd__body">
                                                            <div class="bpa-pd__item bpa-pd-method__item">
                                                                <span><?php esc_html_e('Payment Method', 'bookingpress-package'); ?></span>
                                                                <p>{{ scope.row.payment_method }}</p>
                                                            </div>
                                                            <div class="bpa-pd__item">
                                                                <span><?php esc_html_e('Status', 'bookingpress-package'); ?></span>
                                                                <p :class="[(scope.row.package_status == '1') ? 'bpa-cl-pt-main-green' : '', (scope.row.package_status == '2') ? 'bpa-cl-sc-warning' : '', (scope.row.package_status == '4') ? 'bpa-cl-pt-blue' : '', (scope.row.package_status == '3') ? 'bpa-cl-danger' : '']">{{ scope.row.package_status_label }}</p>
                                                            </div>
                                                            <div class="bpa-pd__item bpa-pd-total__item">
                                                                <span><?php esc_html_e('Total Amount', 'bookingpress-package'); ?></span>
                                                                <p class="bpa-cl-pt-main-green">{{ scope.row.package_payment }}</p>
                                                            </div>
                                                        </div>									
                                                    </div>
                                                </el-col>
                                            </el-row>										
                                        </div>
                                    </div>
                                </template>
                            </el-table-column>
                            <el-table-column type="selection"></el-table-column>
                            <el-table-column prop="booking_id" min-width="30" label="<?php esc_html_e( 'ID', 'bookingpress-package' ); ?>">
                                <template slot-scope="scope">
                                    <span>#{{ scope.row.booking_id }}</span>
                                </template>
                            </el-table-column>
                            <el-table-column prop="package_name" min-width="120" label="<?php esc_html_e( 'Package Name', 'bookingpress-package' ); ?>" sortable sort-by='package_name'></el-table-column>
							<el-table-column prop="customer_name" min-width="120" label="<?php esc_html_e( 'Customer', 'bookingpress-package' ); ?>" sortable sort-by='customer_name'></el-table-column>
                            <el-table-column prop="view_package_purchase_date" min-width="60" label="<?php esc_html_e( 'Purchase Date', 'bookingpress-package' ); ?>"></el-table-column>
                            <el-table-column prop="package_duration" min-width="70" label="<?php esc_html_e( 'Duration', 'bookingpress-package' ); ?>"></el-table-column>
                            <el-table-column prop="view_package_expiration_date" min-width="60" label="<?php esc_html_e( 'Expire Date', 'bookingpress-package' ); ?>"></el-table-column>

                            
                            <el-table-column prop="package_status" min-width="80" label="<?php esc_html_e( 'Status', 'bookingpress-package' ); ?>">
                                <template slot-scope="scope">
                                    <div class="bpa-table-status-dropdown-wrapper" :class="(scope.row.change_status_loader == 1) ? '__bpa-is-loader-active' : ''">
                                        <div class="bpa-tsd--loader" v-if="scope.row.change_status_loader == 1" :class="(scope.row.change_status_loader == 1) ? '__bpa-is-active' : ''">
                                            <div class="bpa-btn--loader__circles">
                                                <div></div>
                                                <div></div>
                                                <div></div>
                                            </div>
                                        </div>
                                        <el-select class="bpa-form-control" :class="((scope.row.package_status == '2' || scope.row.package_status == 'Pending') ? 'bpa-appointment-status--warning' : '') || ((scope.row.package_status == '5') ? 'bpa-appointment-status--refund-partial' : '') || (scope.row.package_status == '3' ? 'bpa-appointment-status--rejected' : '') || (scope.row.package_status == '1' ? 'bpa-appointment-status--completed' : '') || (scope.row.package_status == '4' ? 'bpa-appointment-status--approved' : '')" v-model="scope.row.package_status" placeholder="<?php esc_html_e( 'Select Status', 'bookingpress-package' ); ?>" @change="bookingpress_change_package_order_status(scope.row.package_order_id, $event)" popper-class="bpa-package-status-dropdown-popper">
                                            <el-option-group label="<?php esc_html_e( 'Change status', 'bookingpress-package' ); ?>">
                                                <el-option v-if="item.value != '2'" v-for="item in package_status" :key="item.value" :label="item.text" :value="item.value"></el-option>
                                            </el-option-group>
                                        </el-select>
                                    </div>
                                </template>
                            </el-table-column> 
                            
                            
                            <el-table-column prop="package_payment" min-width="60" label="<?php esc_html_e( 'Payment', 'bookingpress-package' ); ?>" sortable sort-by="payment_numberic_amount">
                                <template slot-scope="scope">
                                    <div class="bpa-apc__amount-row">
                                        <div class="bpa-apc__ar-body">
                                            <span class="bpa-apc__amount">{{ scope.row.package_payment }}</span>
                                        </div>
                                    </div>
                                </template>
                            </el-table-column>
                            <el-table-column prop="created_date" label="<?php esc_html_e( 'Created Date', 'bookingpress-package' ); ?>" sortable sort-by="bookingpress_package_created_date">
                                <template slot-scope="scope">
                                    <label>{{ scope.row.created_date }}</label>
                                        <div class="bpa-table-actions-wrap">
                                            <div class="bpa-table-actions">
                                                

                                                <el-tooltip effect="dark" content="" placement="top" open-delay="300">
                                                    <div slot="content">
                                                        <span><?php esc_html_e( 'Change package expiration date', 'bookingpress-package' ); ?></span>
                                                    </div>
                                                    <el-button class="bpa-btn bpa-btn--icon-without-box bpa-btn--icon-change-expiry-date" @click="bookingpress_open_package_expiration_modal(event,scope.$index, scope.row)">
                                                      <span class="material-icons-round">history</span>                                                        
                                                    </el-button>
                                                </el-tooltip>
                                                <el-tooltip v-if="scope.row.is_package_appointment_booked == 0 || scope.row.is_package_appointment_booked == '0'" effect="dark" content="" placement="top" open-delay="300">
                                                    <div slot="content">
                                                        <span><?php esc_html_e( 'Edit', 'bookingpress-package' ); ?></span>
                                                    </div>
                                                    <el-button class="bpa-btn bpa-btn--icon-without-box" @click.native.prevent="editpackageData(scope.$index, scope.row)">
                                                        <span class="material-icons-round">mode_edit</span>
                                                    </el-button>
                                                </el-tooltip>
                                                    
                                                <el-tooltip v-if="scope.row.is_package_appointment_booked == 0 || scope.row.is_package_appointment_booked == '0'" effect="dark" content="" placement="top" open-delay="300">
                                                    <div slot="content">
                                                        <span><?php esc_html_e( 'Delete', 'bookingpress-package' ); ?></span>
                                                    </div>
                                                    <el-popconfirm 
                                                        cancel-button-text='<?php esc_html_e( 'Cancel', 'bookingpress-package' ); ?>' 
                                                        confirm-button-text='<?php esc_html_e( 'Delete', 'bookingpress-package' ); ?>' 
                                                        icon="false" 
                                                        title="<?php esc_html_e( 'Are you sure you want to delete this package order?', 'bookingpress-package' ); ?>" 
                                                        @confirm="deletepackage(scope.$index, scope.row)" 
                                                        confirm-button-type="bpa-btn bpa-btn__small bpa-btn--danger" 
                                                        cancel-button-type="bpa-btn bpa-btn__small">
                                                        <el-button type="text" slot="reference" class="bpa-btn bpa-btn--icon-without-box __danger">
                                                            <span class="material-icons-round">delete</span>
                                                        </el-button>
                                                    </el-popconfirm>
                                                </el-tooltip>
                                            </div>
                                        </div>
                                </template>
                            </el-table-column>
                        </el-table>
                    </div>
                    <div class="bpa-tc__wrapper" v-if="current_screen_size == 'tablet'">
                        <el-table ref="multipleTable" class="bpa-manage-appointment-items" :data="items" @selection-change="handleSelectionChange" fit="false" @row-click="bookingpress_full_row_clickable" @expand-change="bookingpress_row_expand">
                            <el-table-column type="expand">
                                <template slot-scope="scope">
                                    <div class="bpa-view-appointment-card">
                                        <div class="bpa-vac--head">
                                            <div class="bpa-vac--head__left">											
                                                <span><?php esc_html_e('Booking ID', 'bookingpress-package'); ?>: #{{ scope.row.booking_id }}</span>
                                                <div class="bpa-left__service-detail">
                                                    <h2>{{ scope.row.package_name }}</h2>
                                                    <span class="bpa-sd__price">{{ scope.row.package_payment }}</span>
                                                </div>
                                            </div>
                                            <div class="bpa-hw-right-btn-group bpa-vac--head__right">
                                                <el-popconfirm 
                                                cancel-button-text='<?php esc_html_e( 'Close', 'bookingpress-package' ); ?>' 
                                                confirm-button-text='<?php esc_html_e( 'Cancel', 'bookingpress-package' ); ?>' 
                                                icon="false" 
                                                title="<?php esc_html_e( 'Are you sure you want to cancel this package?', 'bookingpress-package' ); ?>" 
                                                @confirm="bookingpress_change_package_order_status(scope.row.package_order_id, '3')" 
                                                confirm-button-type="bpa-btn bpa-btn__small bpa-btn--danger" 
                                                cancel-button-type="bpa-btn bpa-btn__small"
                                                v-if="scope.row.package_status != '3'">
                                                    <el-button type="text" slot="reference" class="bpa-btn" v-if="scope.row.package_status != '3'">
                                                        <span class="material-icons-round">close</span>
                                                        <?php esc_html_e( 'Cancel', 'bookingpress-package' ); ?>
                                                    </el-button>
                                                </el-popconfirm>
                                            </div>
                                        </div>
                                        <div class="bpa-vac--body">
                                            <el-row :gutter="56">
                                                <el-col :xs="24" :sm="24" :md="24" :lg="16" :xl="18">
                                                    <div class="bpa-vac-body--package-details">
                                                        <el-row :gutter="40">
                                                            <el-col :xs="24" :sm="24" :md="12" :lg="12" :xl="12">                                                            
                                                                <div class="bpa-ad__basic-details">
                                                                    <h4 class="bpa-vac__sec-heading"><?php esc_html_e('Package Detail', 'bookingpress-package'); ?></h4>
                                                                    <div class="bpa-bd__item">
                                                                        <div class="bpa-bd__item-head">
                                                                            <span><?php esc_html_e( 'Expire Date', 'bookingpress-package' ); ?></span>
                                                                        </div>
                                                                        <div class="bpa-bd__item-body">
                                                                            <span>{{scope.row.view_package_expiration_date}}</span>
                                                                        </div>                                                                        
                                                                    </div>
                                                                    <div class="bpa-bd__item">
                                                                        <div class="bpa-bd__item-head">
                                                                            <span><?php esc_html_e( 'Purchase Date', 'bookingpress-package' ); ?></span>
                                                                        </div>
                                                                        <div class="bpa-bd__item-body">
                                                                            <span>{{scope.row.view_package_purchase_date}}</span>
                                                                        </div>                                                                        
                                                                    </div>                                
                                                                </div><br>    
                                                                <div class="bpa-ad__basic-details" v-if="scope.row.bookingpress_package_services.length != 0">
                                                                    <h4 class="bpa-vac__sec-heading"><?php esc_html_e('Services Detail', 'bookingpress-package'); ?></h4>
                                                                    <div class="bpa-bd__item">
                                                                        <div class="bpa-bd__item-head">
                                                                            <span><?php esc_html_e('Name', 'bookingpress-package'); ?></span>
                                                                        </div>
                                                                        <div class="bpa-bd__item-head">
                                                                            <span><?php esc_html_e('Remain Appo.', 'bookingpress-package'); ?></span>
                                                                        </div>                                                                        
                                                                    </div>
                                                                    <div v-for="pack_serv in scope.row.bookingpress_package_services" class="bpa-bd__item">
                                                                        <div class="bpa-bd__item-head">
                                                                            <h4>{{pack_serv.bookingpress_service_name}}</h4>
                                                                        </div>
                                                                        <div class="bpa-bd__item-body">
                                                                            <h4>{{pack_serv.service_remaining_appointment}}/{{pack_serv.bookingpress_no_of_appointments}}</h4>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <br>
                                                            </el-col>
                                                            <el-col :xs="24" :sm="24" :md="12" :lg="12" :xl="12">
                                                                <div class="bpa-ad__customer-details bpp-expand-cust-detail">
                                                                    <h4 class="bpa-vac__sec-heading"><?php esc_html_e('Customer Details', 'bookingpress-package'); ?></h4>
                                                                    <div class="bpa-bd__item"  v-if="scope.row.customer_name != ''">
                                                                        <div class="bpa-bd__item-head">
                                                                            <span>{{form_field_data.fullname}}</span>
                                                                        </div>
                                                                        <div class="bpa-bd__item-body">
                                                                            <h4>{{ scope.row.customer_name }}</h4>
                                                                        </div>
                                                                    </div>
                                                                    <div class="bpa-bd__item" v-if="scope.row.customer_first_name != ''">
                                                                        <div class="bpa-bd__item-head">
                                                                        <span>{{form_field_data.firstname}}</span>
                                                                        </div>
                                                                        <div class="bpa-bd__item-body">
                                                                            <h4>{{ scope.row.customer_first_name }}</h4>
                                                                        </div>
                                                                    </div>
                                                                    <div class="bpa-bd__item">
                                                                        <div class="bpa-bd__item-head" v-if="scope.row.customer_last_name != ''">
                                                                            <span>{{form_field_data.lastname}}</span>
                                                                        </div>
                                                                        <div class="bpa-bd__item-body" >
                                                                            <h4>{{ scope.row.customer_last_name }}</h4>
                                                                        </div>
                                                                    </div>
                                                                    <div class="bpa-bd__item" v-if="scope.row.customer_phone != ''">
                                                                        <div class="bpa-bd__item-head">
                                                                            <span>{{form_field_data.phone_number}}</span>
                                                                        </div>
                                                                        <div class="bpa-bd__item-body">
                                                                            <h4>{{ scope.row.customer_phone }}</h4>
                                                                        </div>
                                                                    </div>
                                                                    <div class="bpa-bd__item">
                                                                        <div class="bpa-bd__item-head">
                                                                            <span>{{form_field_data.email_address}}</span>
                                                                        </div>
                                                                        <div class="bpa-bd__item-body">
                                                                            <h4>{{ scope.row.customer_email }}</h4>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </el-col>
                                                        </el-row>
                                                    </div>													
                                                    <div class="bpa-vac-body--custom-fields" v-if="scope.row.custom_fields_values.length > 0">
                                                        <h4 class="bpa-vac__sec-heading"><?php esc_html_e('Custom Fields', 'bookingpress-package'); ?></h4>
                                                        <div class="bpa-cf__body">
                                                            <el-row>
                                                                <el-col :xs="24" :sm="24" :md="12" :lg="12" :xl="12" v-for="custom_fields in scope.row.custom_fields_values">
                                                                    <div class="bpa-bd__item">
                                                                        <div class="bpa-bd__item-head">
                                                                            <span v-html="custom_fields.label"></span>
                                                                        </div>
                                                                        <div class="bpa-bd__item-body">
                                                                            <h4 v-html="custom_fields.value"></h4>
                                                                        </div>
                                                                    </div>																
                                                                </el-col>
                                                            </el-row>
                                                        </div>
                                                    </div>                                                    
                                                </el-col>
                                                <el-col :xs="24" :sm="24" :md="24" :lg="8" :xl="6">
                                                    <div class="bpa-vac-body--payment-details">
                                                        <h4><?php esc_html_e('Payment Details', 'bookingpress-package'); ?></h4>
                                                        <div class="bpa-pd__body">
                                                            <div class="bpa-pd__item bpa-pd-method__item">
                                                                <span><?php esc_html_e('Payment Method', 'bookingpress-package'); ?></span>
                                                                <p>{{ scope.row.payment_method }}</p>
                                                            </div>
                                                            <div class="bpa-pd__item">
                                                                <span><?php esc_html_e('Status', 'bookingpress-package'); ?></span>
                                                                <p :class="[(scope.row.package_status == '1') ? 'bpa-cl-pt-main-green' : '', (scope.row.package_status == '2') ? 'bpa-cl-sc-warning' : '', (scope.row.package_status == '4') ? 'bpa-cl-pt-blue' : '', (scope.row.package_status == '3') ? 'bpa-cl-danger' : '']">{{ scope.row.package_status_label }}</p>
                                                            </div>
                                                            <div class="bpa-pd__item bpa-pd-total__item">
                                                                <span><?php esc_html_e('Total Amount', 'bookingpress-package'); ?></span>
                                                                <p class="bpa-cl-pt-main-green">{{ scope.row.package_payment }}</p>
                                                            </div>
                                                        </div>									
                                                    </div>
                                                </el-col>
                                            </el-row>										
                                        </div>
                                    </div>
                                </template>
                            </el-table-column>
                            <el-table-column type="selection"></el-table-column>
                            <el-table-column prop="booking_id" min-width="30" label="<?php esc_html_e( 'ID', 'bookingpress-package' ); ?>">
                                <template slot-scope="scope">
                                    <span>#{{ scope.row.booking_id }}</span>
                                </template>
                            </el-table-column>        
                                <el-table-column prop="package_name" min-width="120" label="<?php esc_html_e( 'Package Name', 'bookingpress-package' ); ?>" sortable sort-by='package_name'></el-table-column>
                                <el-table-column prop="customer_name" min-width="120" label="<?php esc_html_e( 'Customer', 'bookingpress-package' ); ?>" sortable sort-by='customer_name'></el-table-column>							
                                <el-table-column prop="package_status" min-width="80" label="<?php esc_html_e( 'Status', 'bookingpress-package' ); ?>">
                                <template slot-scope="scope">
                                    <div class="bpa-table-status-dropdown-wrapper" :class="(scope.row.change_status_loader == 1) ? '__bpa-is-loader-active' : ''">
                                        <div class="bpa-tsd--loader" v-if="scope.row.change_status_loader == 1" :class="(scope.row.change_status_loader == 1) ? '__bpa-is-active' : ''">
                                            <div class="bpa-btn--loader__circles">
                                                <div></div>
                                                <div></div>
                                                <div></div>
                                            </div>
                                        </div>
                                        <el-select class="bpa-form-control" :class="((scope.row.package_status == '2' || scope.row.package_status == 'Pending') ? 'bpa-appointment-status--warning' : '') || ((scope.row.package_status == '5') ? 'bpa-appointment-status--refund-partial' : '') || (scope.row.package_status == '3' ? 'bpa-appointment-status--rejected' : '') || (scope.row.package_status == '1' ? 'bpa-appointment-status--completed' : '') || (scope.row.package_status == '4' ? 'bpa-appointment-status--approved' : '')" v-model="scope.row.package_status" placeholder="<?php esc_html_e( 'Select Status', 'bookingpress-package' ); ?>" @change="bookingpress_change_package_order_status(scope.row.package_order_id, $event)" popper-class="bpa-package-status-dropdown-popper">
                                            <el-option-group label="<?php esc_html_e( 'Change status', 'bookingpress-package' ); ?>">
                                                <el-option v-if="item.value != '2'" v-for="item in package_status" :key="item.value" :label="item.text" :value="item.value"></el-option>
                                            </el-option-group>
                                        </el-select>
                                    </div>
                                </template>
                            </el-table-column>             
                        </el-table>
                    </div>
                    <div class="bpa-tc__wrapper bpa-manage-appointment-container--sm" v-if="current_screen_size == 'mobile'">
                        <el-table ref="multipleTable" class="bpa-manage-appointment-items" :data="items" @selection-change="handleSelectionChange" 	fit="false" :show-header="false" @row-click="bookingpress_full_row_clickable" @expand-change="bookingpress_row_expand">
                            <el-table-column type="expand">
                                <template slot-scope="scope">
                                    <div class="bpa-view-appointment-card">
                                        <div class="bpa-vac--head">
                                            <div class="bpa-vac--head__left">
                                                <span><?php esc_html_e('Booking ID', 'bookingpress-package'); ?>: #{{ scope.row.booking_id }}</span>
                                                <div class="bpa-left__service-detail">
                                                    <h2>{{ scope.row.package_name }}</h2>                                
                                                    <span class="bpa-sd__price" v-else>{{ scope.row.package_payment }}</span>
                                                </div>
                                            </div>
                                            <div class="bpa-hw-right-btn-group bpa-vac--head__right">
                                                    <el-popconfirm 
                                                    cancel-button-text='<?php esc_html_e( 'Close', 'bookingpress-package' ); ?>' 
                                                    confirm-button-text='<?php esc_html_e( 'Cancel', 'bookingpress-package' ); ?>' 
                                                    icon="false" 
                                                    title="<?php esc_html_e( 'Are you sure you want to cancel this package?', 'bookingpress-package' ); ?>" 
                                                    @confirm="bookingpress_change_package_order_status(scope.row.package_order_id, '3')" 
                                                    confirm-button-type="bpa-btn bpa-btn__small bpa-btn--danger" 
                                                    cancel-button-type="bpa-btn bpa-btn__small"
                                                    v-if="scope.row.package_status != '3'">
                                                        <el-button type="text" slot="reference" class="bpa-btn" v-if="scope.row.package_status != '3'">
                                                            <span class="material-icons-round">close</span>
                                                            <?php esc_html_e( 'Cancel', 'bookingpress-package' ); ?>
                                                        </el-button>
                                                    </el-popconfirm>
                                            </div>
                                        </div>
                                        <div class="bpa-vac--body">
                                            <el-row :gutter="56">
                                                <el-col :xs="24" :sm="24" :md="24" :lg="16" :xl="18">
                                                    <div class="bpa-vac-body--appointment-details">
                                                        <el-row :gutter="40">
                                                            <el-col :xs="24" :sm="24" :md="12" :lg="12" :xl="12">
                                                                <div class="bpa-ad__basic-details bpa-ad__basic-details-pack-serv">                                            
                                                                    <h4 class="bpa-vac__sec-heading"><?php esc_html_e('Package Detail', 'bookingpress-package'); ?></h4>
                                                                    <div class="bpa-bd__item">
                                                                        <div class="bpa-bd__item-head">
                                                                            <span><?php esc_html_e( 'Package Name', 'bookingpress-package' ); ?></span>
                                                                        </div>
                                                                        <div class="bpa-bd__item-body">
                                                                            <span>{{scope.row.package_name}}</span>
                                                                        </div>                                                                        
                                                                    </div>
                                                                    <div class="bpa-bd__item">
                                                                        <div class="bpa-bd__item-head">
                                                                            <span><?php esc_html_e( 'Customer', 'bookingpress-package' ); ?></span>
                                                                        </div>
                                                                        <div class="bpa-bd__item-body">
                                                                            <span>{{scope.row.customer_name}}</span>
                                                                        </div>                                                                        
                                                                    </div>                                                
                                                                    <div class="bpa-bd__item">
                                                                        <div class="bpa-bd__item-head">
                                                                            <span><?php esc_html_e( 'Expire Date', 'bookingpress-package' ); ?></span>
                                                                        </div>
                                                                        <div class="bpa-bd__item-body">
                                                                            <span>{{scope.row.view_package_expiration_date}}</span>
                                                                        </div>                                                                        
                                                                    </div>
                                                                    <div class="bpa-bd__item">
                                                                        <div class="bpa-bd__item-head">
                                                                            <span><?php esc_html_e( 'Purchase Date', 'bookingpress-package' ); ?></span>
                                                                        </div>
                                                                        <div class="bpa-bd__item-body">
                                                                            <span>{{scope.row.view_package_purchase_date}}</span>
                                                                        </div>                                                                        
                                                                    </div>                                
                                                                </div> <br>
                                                                <div class="bpa-ad__basic-details bpa-ad__basic-details-pack-serv" v-if="scope.row.bookingpress_package_services.length != 0">
                                                                    <h4 class="bpa-vac__sec-heading"><?php esc_html_e('Services Detail', 'bookingpress-package'); ?></h4>
                                                                    <div class="bpa-bd__item">
                                                                        <div class="bpa-bd__item-head">
                                                                            <span><?php esc_html_e('Name', 'bookingpress-package'); ?></span>
                                                                        </div>
                                                                        <div class="bpa-bd__item-head">
                                                                            <span><?php esc_html_e('Remain Appo.', 'bookingpress-package'); ?></span>
                                                                        </div>                                                                        
                                                                    </div>
                                                                    <div v-for="pack_serv in scope.row.bookingpress_package_services" class="bpa-bd__item">
                                                                        <div class="bpa-bd__item-head">
                                                                            <h4>{{pack_serv.bookingpress_service_name}}</h4>
                                                                        </div>
                                                                        <div class="bpa-bd__item-body">
                                                                            <h4>{{pack_serv.service_remaining_appointment}}/{{pack_serv.bookingpress_no_of_appointments}}</h4>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <br>                                                                                      
                                                            </el-col>
                                                            <el-col :xs="24" :sm="24" :md="12" :lg="12" :xl="12">
                                                                <div class="bpa-ad__customer-details bpp-expand-cust-detail">
                                                                    <h4 class="bpa-vac__sec-heading"><?php esc_html_e('Customer Details', 'bookingpress-package'); ?></h4>
                                                                    <div class="bpa-bd__item"  v-if="scope.row.customer_name != ''">
                                                                        <div class="bpa-bd__item-head">
                                                                            <span>{{form_field_data.fullname}}</span>
                                                                        </div>
                                                                        <div class="bpa-bd__item-body">
                                                                            <h4>{{ scope.row.customer_name }}</h4>
                                                                        </div>
                                                                    </div>
                                                                    <div class="bpa-bd__item" v-if="scope.row.customer_first_name != ''">
                                                                        <div class="bpa-bd__item-head">
                                                                        <span>{{form_field_data.firstname}}</span>
                                                                        </div>
                                                                        <div class="bpa-bd__item-body">
                                                                            <h4>{{ scope.row.customer_first_name }}</h4>
                                                                        </div>
                                                                    </div>
                                                                    <div class="bpa-bd__item">
                                                                        <div class="bpa-bd__item-head" v-if="scope.row.customer_last_name != ''">
                                                                            <span>{{form_field_data.lastname}}</span>
                                                                        </div>
                                                                        <div class="bpa-bd__item-body" >
                                                                            <h4>{{ scope.row.customer_last_name }}</h4>
                                                                        </div>
                                                                    </div>
                                                                    <div class="bpa-bd__item" v-if="scope.row.customer_phone != ''">
                                                                        <div class="bpa-bd__item-head">
                                                                            <span>{{form_field_data.phone_number}}</span>
                                                                        </div>
                                                                        <div class="bpa-bd__item-body">
                                                                            <h4>{{ scope.row.customer_phone }}</h4>
                                                                        </div>
                                                                    </div>
                                                                    <div class="bpa-bd__item">
                                                                        <div class="bpa-bd__item-head">
                                                                            <span>{{form_field_data.email_address}}</span>
                                                                        </div>
                                                                        <div class="bpa-bd__item-body">
                                                                            <h4>{{ scope.row.customer_email }}</h4>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </el-col>
                                                        </el-row>
                                                    </div>
                                                    <div class="bpa-vac-body--custom-fields" v-if="scope.row.custom_fields_values.length > 0">
                                                        <h4 class="bpa-vac__sec-heading"><?php esc_html_e('Custom Fields', 'bookingpress-package'); ?></h4>
                                                        <div class="bpa-cf__body">
                                                            <el-row>
                                                                <el-col :xs="24" :sm="24" :md="12" :lg="12" :xl="12" v-for="custom_fields in scope.row.custom_fields_values">
                                                                    <div class="bpa-bd__item">
                                                                        <div class="bpa-bd__item-head">
                                                                            <span v-html="custom_fields.label"></span>
                                                                        </div>
                                                                        <div class="bpa-bd__item-body">
                                                                            <h4 v-html="custom_fields.value"></h4>
                                                                        </div>
                                                                    </div>																
                                                                </el-col>
                                                            </el-row>
                                                        </div>
                                                    </div> 
                                                </el-col>  
                                                <el-col :xs="24" :sm="24" :md="24" :lg="8" :xl="6">
                                                    <div class="bpa-vac-body--payment-details">
                                                        <h4><?php esc_html_e('Payment Details', 'bookingpress-package'); ?></h4>
                                                        <div class="bpa-pd__body">
                                                            <div class="bpa-pd__item bpa-pd-method__item">
                                                                <span><?php esc_html_e('Payment Method', 'bookingpress-package'); ?></span>
                                                                <p>{{ scope.row.payment_method }}</p>
                                                            </div>
                                                            <div class="bpa-pd__item">
                                                                <span><?php esc_html_e('Status', 'bookingpress-package'); ?></span>
                                                                <p :class="[(scope.row.package_status == '1') ? 'bpa-cl-pt-main-green' : '', (scope.row.package_status == '2') ? 'bpa-cl-sc-warning' : '', (scope.row.package_status == '4') ? 'bpa-cl-pt-blue' : '', (scope.row.package_status == '3') ? 'bpa-cl-danger' : '']">{{ scope.row.package_status_label }}</p>
                                                            </div>
                                                            <div class="bpa-pd__item bpa-pd-total__item">
                                                                <span><?php esc_html_e('Total Amount', 'bookingpress-package'); ?></span>
                                                                <p class="bpa-cl-pt-main-green">{{ scope.row.package_payment }}</p>
                                                            </div>
                                                        </div>									
                                                    </div>
                                                </el-col>                            
                                            </el-row>										
                                        </div>
                                    </div>
                                </template>
                            </el-table-column>
                            <el-table-column type="selection"></el-table-column>
                            <el-table-column>
                                <template slot-scope="scope">
                                    <div class="bpa-ap-item__mob">
                                        <div class="bpa-api--head">
                                            <h4>{{ scope.row.package_name }}</h4>
                                            <div class="bpa-api--head-apointment-details">
                                                <p><b><?php esc_html_e( 'Customer', 'bookingpress-package' ); ?>&nbsp;</b> {{ scope.row.customer_name }}</p>
                                                <p><b><?php esc_html_e( 'Expire Date', 'bookingpress-package' ); ?>&nbsp;</b> {{ scope.row.view_package_expiration_date }}</p>                            
                                            </div>
                                        </div>
                                        <div class="bpa-mpay-item--foot">
                                            <div class="bpa-table-status-dropdown-wrapper" :class="(scope.row.change_status_loader == 1) ? '__bpa-is-loader-active' : ''">
                                                    <div class="bpa-tsd--loader" v-if="scope.row.change_status_loader == 1" :class="(scope.row.change_status_loader == 1) ? '__bpa-is-active' : ''">
                                                        <div class="bpa-btn--loader__circles">
                                                            <div></div>
                                                            <div></div>
                                                            <div></div>
                                                        </div>
                                                    </div>
                                                    <el-select class="bpa-form-control" :class="((scope.row.package_status == '2' || scope.row.package_status == 'Pending') ? 'bpa-appointment-status--warning' : '') || ((scope.row.package_status == '5') ? 'bpa-appointment-status--refund-partial' : '') || (scope.row.package_status == '3' ? 'bpa-appointment-status--rejected' : '') || (scope.row.package_status == '1' ? 'bpa-appointment-status--completed' : '') || (scope.row.package_status == '4' ? 'bpa-appointment-status--approved' : '')" v-model="scope.row.package_status" placeholder="<?php esc_html_e( 'Select Status', 'bookingpress-package' ); ?>" @change="bookingpress_change_package_order_status(scope.row.package_order_id, $event)" popper-class="bpa-package-status-dropdown-popper">
                                                        <el-option-group label="<?php esc_html_e( 'Change status', 'bookingpress-package' ); ?>">
                                                            <el-option v-if="item.value != '2'" v-for="item in package_status" :key="item.value" :label="item.text" :value="item.value"></el-option>
                                                        </el-option-group>
                                                    </el-select>
                                                </div>
                                        </div>										
                                    </div>
                                </template>
                            </el-table-column>
                        </el-table>
                    </div>                    
                </el-container>
            </el-col>
        </el-row>
        <el-row class="bpa-pagination" type="flex" v-if="items.length > 0"> <!-- Pagination -->
            <el-col :xs="24" :sm="24" :md="24" :lg="12" :xl="12" >
                <div class="bpa-pagination-left">
                    <p><?php esc_html_e('Showing', 'bookingpress-package'); ?> <strong><u>{{ items.length }}</u></strong>&nbsp;<?php esc_html_e('out of', 'bookingpress-package'); ?>&nbsp;<strong>{{ totalItems }}</strong></p>
                    <div class="bpa-pagination-per-page">
                        <p><?php esc_html_e('Per Page', 'bookingpress-package'); ?></p>
                        <el-select v-model="pagination_length_val" placeholder="Select" @change="changePaginationSize($event)" class="bpa-form-control" popper-class="bpa-pagination-dropdown">
                            <el-option v-for="item in pagination_val" :key="item.text" :label="item.text" :value="item.value"></el-option>
                        </el-select>
                    </div>
                </div>
            </el-col>
            <el-col :xs="24" :sm="24" :md="24" :lg="12" :xl="12" class="bpa-pagination-nav">
                <el-pagination @size-change="handleSizeChange" @current-change="handleCurrentChange" :current-page.sync="currentPage" layout="prev, pager, next" :total="totalItems" :page-sizes="pagination_length" :page-size="perPage"></el-pagination>
            </el-col>
            <el-container v-if="multipleSelection.length > 0" class="bpa-default-card bpa-bulk-actions-card" >
                <el-button class="bpa-btn bpa-btn--icon-without-box bpa-bac__close-icon" @click="closeBulkAction">
                    <span class="material-icons-round">close</span>
                </el-button>
                <el-row type="flex" class="bpa-bac__wrapper">
                    <el-col class="bpa-bac__left-area" :xs="24" :sm="12" :md="12" :lg="12" :xl="12">
                        <span class="material-icons-round">check_circle</span>
                        <p>{{ multipleSelection.length }}<?php esc_html_e(' Items Selected', 'bookingpress-package'); ?></p>
                    </el-col>
                    <el-col class="bpa-bac__right-area" :xs="24" :sm="12" :md="12" :lg="12" :xl="12">
                        <el-select class="bpa-form-control" v-model="bulk_action" placeholder="<?php esc_html_e('Select', 'bookingpress-package'); ?>"
                        popper-class="bpa-dropdown--bulk-actions">
                            <el-option v-for="item in bulk_options" :key="item.value" :label="item.label" :value="item.value"></el-option>
                        </el-select>
                        <el-button @click="bulk_actions()" class="bpa-btn bpa-btn--primary bpa-btn__medium">
                            <?php esc_html_e('Go', 'bookingpress-package'); ?>
                        </el-button>
                    </el-col>
                </el-row>
            </el-container>        
        </el-row>
    </div>
</el-main>


<el-dialog custom-class="bpa-dialog bpa-dialog--fullscreen bpa--is-page-non-scrollable-mob" modal-append-to-body=false :visible.sync="open_package_modal" :before-close="closepackageModal" fullscreen=true :close-on-press-escape="close_modal_on_esc">
	<div class="bpa-dialog-heading">
		<el-row type="flex">
			<el-col :xs="12" :sm="12" :md="16" :lg="16" :xl="16">
				<h1 class="bpa-page-heading" v-if="package_formdata.package_update_id == 0"><?php esc_html_e( 'Assign Package', 'bookingpress-package' ); ?></h1>
				<h1 class="bpa-page-heading" v-else><?php esc_html_e( 'Edit Assign Package', 'bookingpress-package' ); ?></h1>
			</el-col>
			<el-col :xs="12" :sm="12" :md="7" :lg="7" :xl="7" class="bpa-dh__btn-group-col">
				<el-button class="bpa-btn bpa-btn--primary" :class="(is_display_save_loader == '1') ? 'bpa-btn--is-loader' : ''" @click="savePackageBooking('package_formdata')" :disabled="is_disabled" >					
				  <span class="bpa-btn__label"><?php esc_html_e( 'Save', 'bookingpress-package' ); ?></span>
				  <div class="bpa-btn--loader__circles">				    
					  <div></div>
					  <div></div>
					  <div></div>
				  </div>
				</el-button>
				<el-button class="bpa-btn" @click="closepackageModal()"><?php esc_html_e( 'Cancel', 'bookingpress-package' ); ?></el-button>
			</el-col>
		</el-row>
	</div>
	<div class="bpa-dialog-body">
		<div class="bpa-back-loader-container" v-if="is_display_loader == '1'">
			<div class="bpa-back-loader"></div>
		</div>
		<div class="bpa-form-row">
			<el-row>
				<el-col :xs="24" :sm="24" :md="24" :lg="24" :xl="24">
					<div class="bpa-db-sec-heading">
						<el-row type="flex" align="middle">
							<el-col :xs="24" :sm="24" :md="12" :lg="12" :xl="12">
								<div class="db-sec-left">
									<h2 class="bpa-page-heading"><?php esc_html_e( 'Basic Details', 'bookingpress-package' ); ?></h2>
								</div>
							</el-col>							
						</el-row>
					</div>
					<div class="bpa-default-card bpa-db-card">
						<el-form class="bpa-add-package-form" ref="package_formdata" :rules="rules" :model="package_formdata" label-position="top" @submit.native.prevent>
							<template>								
								<div class="bpa-form-body-row">
									<el-row :gutter="32">
										<el-col :xs="24" :sm="24" :md="24" :lg="8" :xl="8">											
											<el-form-item prop="package_selected_customer">
												<template #label>
													<span class="bpa-form-label"><?php esc_html_e( 'Select Customer', 'bookingpress-package' ); ?></span>
												</template>
												<el-select class="bpa-form-control" name="package_selected_customer" v-model="package_formdata.package_selected_customer"  @change="bookingpress_package_select_customer($event)" filterable placeholder="<?php esc_html_e( 'Start typing to fetch Customer', 'bookingpress-package' ); ?>" remote reserve-keyword :remote-method="bookingpress_get_customer_list" :loading="bookingpress_loading"  popper-class="bpa-el-select--is-with-modal" v-cancel-read-only>												
													<el-option value="add_new" label="Add New" v-if="bookingpress_edit_customers == 1">
														<i class="el-icon-plus" ></i>
														<span><?php esc_html_e( 'Add New', 'bookingpress-package' ); ?></span>
													</el-option>
													<el-option v-for="customer_data in package_customers_list" :key="customer_data.value" :label="customer_data.text" :value="customer_data.value">
														<span>{{ customer_data.text }}</span>
													</el-option>													
                                                </el-select>  												
											</el-form-item>
										</el-col>										
										<el-col :xs="24" :sm="24" :md="24" :lg="8" :xl="8">
											<el-form-item prop="package_selected_package">
												<template #label>
													<span class="bpa-form-label"><?php esc_html_e( 'Select Package', 'bookingpress-package' ); ?></span>
												</template>
												<div class="bpa-aaf__service-selection-col">
													<el-select class="bpa-form-control" @change="bookingpress_package_change_package" v-model="package_formdata.package_selected_package" name="package_selected_package" filterable placeholder="<?php esc_html_e( 'Select Package', 'bookingpress-package' ); ?>" popper-class="bpa-el-select--is-with-modal">
                                                        <el-option v-for="item in package_list" :key="item.bookingpress_package_id " :label="item.bookingpress_package_name+' ('+item.formatted_package_price+')'" :value="item.bookingpress_package_id"></el-option>
													</el-select>
												</div>
											</el-form-item>
										</el-col>
										<el-col :xs="24" :sm="24" :md="24" :lg="8" :xl="8">
											<el-form-item>
												<template #label>
													<span class="bpa-form-label"><?php esc_html_e( 'Internal note', 'bookingpress-package' ); ?></span>
												</template>
												<el-input class="bpa-form-control" v-model="package_formdata.package_internal_note"></el-input>
											</el-form-item>
										</el-col>										
									</el-row>									
								</div>						
								<div class="bpa-form-body-row">
									<el-row :gutter="24">
										<el-col :xs="24" :sm="24" :md="24" :lg="08" :xl="08">
											<el-form-item>
												<label class="bpa-form-label bpa-custom-checkbox--is-label"> <el-checkbox v-model="package_formdata.package_send_notification"></el-checkbox> <?php esc_html_e( 'Do Not Send Notifications', 'bookingpress-package' ); ?></label>
											</el-form-item>
										</el-col>
										<?php do_action('bookingpress_add_package_field_section') ?>
									</el-row>
								</div>	
							</template>
						</el-form>
					</div>
				</el-col>				
			</el-row>			
		</div>
				
		<div class="bpa-form-row bpa-pack-form-rows" v-if="bookingpress_form_fields.length > 0">
			<el-row>
				<el-col :xs="24" :sm="24" :md="24" :lg="24" :xl="24" v-if="bookingpress_form_fields.length > 0">
					<div class="bpa-db-sec-heading">
						<el-row type="flex" align="middle">
							<el-col :xs="24" :sm="24" :md="12" :lg="12" :xl="12">
								<div class="db-sec-left">
									<h2 class="bpa-page-heading"><?php esc_html_e( 'Custom Fields', 'bookingpress-package' ); ?></h2>
								</div>
							</el-col>
						</el-row>
					</div>
					<div class="bpa-default-card bpa-db-card">
						<el-form ref="package_custom_formdata" :rules="custom_field_rules" :model="package_formdata.bookingpress_package_meta_fields_value" label-position="top" @submit.native.prevent>
							<template>
								<div class="bpa-form-body-row">
									<el-row :gutter="34">
										<el-col :xs="24" :sm="24" :md="24" :lg="08" :xl="08" v-for="form_fields in bookingpress_form_fields" :class="(form_fields.is_separator == true) ? '--bpa-is-field-separator' : ''">
											<div v-if="form_fields.is_separator == false">
												<div v-if="'undefined' != typeof form_fields.selected_services && form_fields.selected_services.length > 0">
													<el-form-item v-if='(form_fields.bookingpress_field_type == "text" || form_fields.bookingpress_field_type == "email" || form_fields.bookingpress_field_type == "phone") && form_fields.selected_services.includes(package_formdata.package_selected_service)' :prop="form_fields.bookingpress_field_meta_key">
														<template #label>
															<span class="bpa-form-label">{{ form_fields.bookingpress_field_label }}</span>
														</template>
														<el-input class="bpa-form-control" v-model="package_formdata.bookingpress_package_meta_fields_value[form_fields.bookingpress_field_meta_key]" :placeholder="form_fields.bookingpress_field_placeholder"></el-input>
													</el-form-item>
													<el-form-item v-if='form_fields.bookingpress_field_type == "textarea" && form_fields.selected_services.includes(package_formdata.package_selected_service)' :prop="form_fields.bookingpress_field_meta_key">
														<template #label>
															<span class="bpa-form-label">{{ form_fields.bookingpress_field_label }}</span>
														</template>
														<el-input class="bpa-form-control" v-model="package_formdata.bookingpress_package_meta_fields_value[form_fields.bookingpress_field_meta_key]" :placeholder="form_fields.bookingpress_field_placeholder" type="textarea" :rows="3"></el-input>
													</el-form-item>									
													<el-form-item v-if="form_fields.bookingpress_field_type == 'checkbox' && form_fields.selected_services.includes(package_formdata.package_selected_service)" :prop="form_fields.bookingpress_field_meta_key">
														<template #label>
															<span class="bpa-form-label">{{ form_fields.bookingpress_field_label }}</span>
														</template>
														<el-checkbox-group v-model="package_formdata.bookingpress_package_meta_fields_value[form_fields['bookingpress_field_meta_key']]">
															<el-checkbox class="bpa-front-label bpa-custom-checkbox--is-label" v-for="(chk_data, keys) in JSON.parse( form_fields.bookingpress_field_values)" :label="chk_data.value" :key="chk_data.value" :name="form_fields['bookingpress_field_meta_key']"><p v-html="chk_data.label"></p></el-checkbox>
														</el-checkbox-group>
													</el-form-item>
													<el-form-item v-if="form_fields.bookingpress_field_type == 'radio' && form_fields.selected_services.includes(package_formdata.package_selected_service)" :prop="form_fields.bookingpress_field_meta_key">
														<template #label>
															<span class="bpa-form-label">{{ form_fields.bookingpress_field_label }}</span>
														</template>
														<el-radio class="bpa-form-label bpa-custom-radio--is-label" v-model="package_formdata.bookingpress_package_meta_fields_value[form_fields.bookingpress_field_meta_key]" v-for="(chk_data, keys) in JSON.parse(form_fields.bookingpress_field_values)" :label="chk_data.label" :key="chk_data.value" @change="bookingpress_handle_tax_calculation_pkg(form_fields.bookingpress_form_field_id, chk_data.value, form_fields)">{{chk_data.label}}</el-radio>
													</el-form-item>
													<el-form-item v-if='form_fields.bookingpress_field_type == "dropdown" && form_fields.selected_services.includes(package_formdata.package_selected_service)' :prop="form_fields.bookingpress_field_meta_key">
														<template #label>
															<span class="bpa-form-label">{{ form_fields.bookingpress_field_label }}</span>
														</template>
														<el-select class="bpa-form-control" :placeholder="form_fields.bookingpress_field_placeholder" v-model="package_formdata.bookingpress_package_meta_fields_value[form_fields.bookingpress_field_meta_key]" @change="bookingpress_handle_tax_calculation_pkg(form_fields.bookingpress_form_field_id, chk_data.value, form_fields)">
															<el-option v-for="sel_data in JSON.parse(form_fields.bookingpress_field_values)" :key="sel_data.value" :label="sel_data.label" :value="sel_data.value" ></el-option>
														</el-select>
													</el-form-item>
													<el-form-item v-if='form_fields.bookingpress_field_type == "date" && form_fields.selected_services.includes(package_formdata.package_selected_service)' :prop="form_fields.bookingpress_field_meta_key">
														<template #label>
															<span class="bpa-form-label">{{ form_fields.bookingpress_field_label }}</span>
														</template>
															<el-date-picker :format="( 'true' == form_fields.bookingpress_field_options.enable_timepicker ) ? '<?php echo esc_html( $bookingpress_common_datetime_format ); ?>' : '<?php echo esc_html( $bookingpress_common_date_format ) ?>'" class="bpa-form-control bpa-form-control--date-picker" prefix-icon="" v-model="package_formdata.bookingpress_package_meta_fields_value[form_fields.bookingpress_field_meta_key]" :placeholder="form_fields.bookingpress_field_placeholder" :type="'true' == form_fields.bookingpress_field_options.enable_timepicker ? 'datetime' : 'date'" :value-format="form_fields.bookingpress_field_options.enable_timepicker == 'true' ? 'yyyy-MM-dd hh:mm:ss' : 'yyyy-MM-dd'" :picker-options="filter_pickerOptions"></el-date-picker> 
													</el-form-item>
													<el-form-item v-if='form_fields.bookingpress_field_type == "file" && form_fields.selected_services.includes(package_formdata.package_selected_service)' :prop="form_fields.bookingpress_field_meta_key" >
														<template #label>
															<span class="bpa-form-label">{{ form_fields.bookingpress_field_label }}</span>
														</template>
														<el-upload :action="form_fields.bpa_action_url" :ref="form_fields.bpa_ref_name" :data="form_fields.bpa_action_data" v-model="package_formdata.bookingpress_package_meta_fields_value[form_fields.bookingpress_field_meta_key]" :on-success="BPACustomerFileUploadPackage" :on-remove="BPACustomerFileUploadRemovePackage" :file-list="form_fields.bpa_file_list" :on-error="BPACustomerFileUploadError" multiple="false" limit="1" :name="form_fields.bookingpress_field_meta_key" >
															<label for="bpa-file-upload-two" class="bpa-form-control--file-upload">
																<span class="bpa-fu__placeholder" v-if="form_fields.bookingpress_field_placeholder != ''">{{form_fields.bookingpress_field_placeholder}}</span>
                                                                <span class="bpa-fu__placeholder" v-else>Choose a file...</span>
																<span class="bpa-fu__btn" v-if="typeof form_fields.bookingpress_field_options.browse_button_label !== 'undefined'">{{form_fields.bookingpress_field_options.browse_button_label}}</span>
                                                                <span class="bpa-fu__btn" v-else>Browse</span>
															</label> 
														</el-upload>
													</el-form-item>								
												</div>
												<div v-else>
													<el-form-item v-if='(form_fields.bookingpress_field_type == "text" || form_fields.bookingpress_field_type == "email" || form_fields.bookingpress_field_type == "phone")' :prop="form_fields.bookingpress_field_meta_key">
														<template #label>
															<span class="bpa-form-label">{{ form_fields.bookingpress_field_label }}</span>
														</template>
														<el-input class="bpa-form-control" v-model="package_formdata.bookingpress_package_meta_fields_value[form_fields.bookingpress_field_meta_key]" :placeholder="form_fields.bookingpress_field_placeholder"></el-input>
													</el-form-item>
													<el-form-item v-if='form_fields.bookingpress_field_type == "textarea"' :prop="form_fields.bookingpress_field_meta_key">
														<template #label>
															<span class="bpa-form-label">{{ form_fields.bookingpress_field_label }}</span>
														</template>
														<el-input class="bpa-form-control" :placeholder="form_fields.bookingpress_field_placeholder" type="textarea" :rows="3" v-model="package_formdata.bookingpress_package_meta_fields_value[form_fields.bookingpress_field_meta_key]"></el-input>
													</el-form-item>									
													<el-form-item v-if="form_fields.bookingpress_field_type == 'checkbox'" :prop="form_fields.bookingpress_field_meta_key">
														<template #label>
															<span class="bpa-form-label">{{ form_fields.bookingpress_field_label }}</span>
														</template>
														<el-checkbox-group v-model="package_formdata.bookingpress_package_meta_fields_value[form_fields['bookingpress_field_meta_key']]">
															<el-checkbox class="bpa-front-label bpa-custom-checkbox--is-label" v-for="(chk_data, keys) in JSON.parse( form_fields.bookingpress_field_values)" :label="chk_data.value" :key="chk_data.value" :name="form_fields['bookingpress_field_meta_key']"><p v-html="chk_data.label"></p></el-checkbox>
														</el-checkbox-group>
													</el-form-item>
													<el-form-item v-if="form_fields.bookingpress_field_type == 'radio'" :prop="form_fields.bookingpress_field_meta_key">
														<template #label>
															<span class="bpa-form-label">{{ form_fields.bookingpress_field_label }}</span>
														</template>
														<el-radio class="bpa-form-label bpa-custom-radio--is-label" v-for="(chk_data, keys) in JSON.parse(form_fields.bookingpress_field_values)" :label="chk_data.label" :key="chk_data.value" v-model="package_formdata.bookingpress_package_meta_fields_value[form_fields.bookingpress_field_meta_key]" @change="bookingpress_handle_tax_calculation_pkg(form_fields.bookingpress_form_field_id, chk_data.value, form_fields)">{{chk_data.label}}</el-radio>
													</el-form-item>
													<el-form-item v-if='form_fields.bookingpress_field_type == "dropdown"' :prop="form_fields.bookingpress_field_meta_key">
														<template #label>
															<span class="bpa-form-label">{{ form_fields.bookingpress_field_label }}</span>
														</template>
														<el-select class="bpa-form-control" :placeholder="form_fields.bookingpress_field_placeholder" v-model="package_formdata.bookingpress_package_meta_fields_value[form_fields.bookingpress_field_meta_key]" @change="bookingpress_handle_tax_calculation_pkg(form_fields.bookingpress_form_field_id, $event, form_fields)">
															<el-option v-for="sel_data in JSON.parse(form_fields.bookingpress_field_values)" :key="sel_data.value" :label="sel_data.label" :value="sel_data.value"></el-option>
														</el-select>
													</el-form-item>
													<el-form-item v-if='form_fields.bookingpress_field_type == "date"' :prop="form_fields.bookingpress_field_meta_key">
														<template #label>
															<span class="bpa-form-label">{{ form_fields.bookingpress_field_label }}</span>
														</template>
															<el-date-picker :format="( 'true' == form_fields.bookingpress_field_options.enable_timepicker ) ? '<?php echo esc_html( $bookingpress_common_datetime_format ); ?>' : '<?php echo esc_html( $bookingpress_common_date_format ) ?>'" class="bpa-form-control bpa-form-control--date-picker" v-model="package_formdata.bookingpress_package_meta_fields_value[form_fields.bookingpress_field_meta_key]" prefix-icon="" :placeholder="form_fields.bookingpress_field_placeholder" :type="( 'true' == form_fields.bookingpress_field_options.enable_timepicker ) ? 'datetime' : 'date'" <?php if($bookingpres_default_time_format == 'H:i') { ?> :value-format="form_fields.bookingpress_field_options.enable_timepicker == 'true' ? 'yyyy-MM-dd HH:mm:ss' : 'yyyy-MM-dd'" <?php } else {?> :value-format="form_fields.bookingpress_field_options.enable_timepicker == 'true' ? 'yyyy-MM-dd hh:mm:ss' : 'yyyy-MM-dd'" <?php } ?> :picker-options="filter_pickerOptions"></el-date-picker> 
													</el-form-item>
													<el-form-item v-if='form_fields.bookingpress_field_type == "file"' :prop="form_fields.bookingpress_field_meta_key">
														<template #label>
															<span class="bpa-form-label">{{ form_fields.bookingpress_field_label }}</span>
														</template>
														<el-upload class="bpa-form-control" :action="form_fields.bpa_action_url" :ref="form_fields.bpa_ref_name" :data="form_fields.bpa_action_data" v-model="package_formdata.bookingpress_package_meta_fields_value[form_fields.bookingpress_field_meta_key]" :on-success="BPACustomerFileUploadPackage" :on-remove="BPACustomerFileUploadRemovePackage" :file-list="form_fields.bpa_file_list" :on-error="BPACustomerFileUploadError" multiple="false" limit="1" :name="form_fields.bookingpress_field_meta_key" >
															<label for="bpa-file-upload-two" class="bpa-form-control--file-upload" >
																<span class="bpa-fu__placeholder" v-if="form_fields.bookingpress_field_placeholder != ''">{{form_fields.bookingpress_field_placeholder}}</span>
                                                                <span class="bpa-fu__placeholder" v-else>Choose a file...</span>
																<span class="bpa-fu__btn" v-if="typeof form_fields.bookingpress_field_options.browse_button_label !== 'undefined'">{{form_fields.bookingpress_field_options.browse_button_label}}</span>
                                                                <span class="bpa-fu__btn" v-else>Browse</span>
															</label> 
														</el-upload>
													</el-form-item>
												</div>
											</div>
										</el-col>
									</el-row>
								</div>
							</template>
						</el-form>	
					</div>
				</el-col>
			</el-row>
		</div>

		<div class="bpa-form-row" v-if="bookingpress_payments == 1">
			<el-row>
				<el-col :xs="24" :sm="24" :md="24" :lg="24" :xl="24">
					<div class="bpa-db-sec-heading">
						<el-row type="flex" align="middle">
							<el-col :xs="24" :sm="24" :md="12" :lg="12" :xl="12">
								<div class="db-sec-left">
									<h2 class="bpa-page-heading"><?php esc_html_e( 'Payment Details', 'bookingpress-package' ); ?></h2>
								</div>
							</el-col>
						</el-row>
					</div>
					<div class="bpa-default-card bpa-db-card">
						<div class="bpa-aaf--payment-details">
							<div class="bpa-aaf-pd__base-price-row">
								<div class="bpa-bpr__item">
									<h4>
										<?php esc_html_e('Subtotal', 'bookingpress-package'); ?> 
										<span v-if="package_formdata.selected_bring_members > 1">(<?php esc_html_e('No. Of Person', 'bookingpress-package'); ?> x {{ package_formdata.selected_bring_members}})</span>
									</h4>
									<h4>{{ package_formdata.subtotal_with_currency }}</h4>
								</div>
								<div class="bpa-bpr__item" v-if="package_formdata.tax != '0' && (package_formdata.tax_price_display_options != 'include_taxes' || (package_formdata.tax_price_display_options == 'include_taxes' && package_formdata.display_tax_order_summary == 'true') )">
									<h4><?php esc_html_e('Tax', 'bookingpress-package'); ?></h4>
									<h4>+{{ package_formdata.tax_with_currency }}</h4>
								</div>								
							</div>

							<!-- for tip addon add do_action for fornt-end add package -->
							<?php do_action('bookingpress_package_order_add_content_after_subtotal_data_backend'); ?>

							<div class="bpa-aaf-pd__base-price-row bpa-aaf-pd__total-row">
								<div class="bpa-bpr__item">
									<h4><?php esc_html_e('Total', 'bookingpress-package'); ?> <span v-if="package_formdata.tax_price_display_options == 'include_taxes'">{{ package_formdata.included_tax_label }}</span></h4>
									<h4 class="bpa-text--primary-color">{{ package_formdata.total_amount_with_currency }}</h4>
								</div>								
							</div>
							<!--
                            <div class="bpa-aaf-pd__mark-paid-checkbox" v-if="(package_formdata.package_update_id == '')">
								<div>
									<h4><?php esc_html_e('Once package booked', 'bookingpress-package'); ?></h4>-->
									<!--<el-radio v-model="package_formdata.complete_payment_url_selection" label="send_payment_link"><?php esc_html_e( 'Send Payment Link', 'bookingpress-package' ); ?></el-radio>--> 
									<!--<el-radio v-model="package_formdata.complete_payment_url_selection" label="mark_as_paid"><?php esc_html_e( 'Mark as paid', 'bookingpress-package' ); ?></el-radio>
									<el-radio v-model="package_formdata.complete_payment_url_selection" label="do_nothing"><?php esc_html_e( 'Do Nothing', 'bookingpress-package' ); ?></el-radio>
								</div>
								<div class="bpa-aaf-pd__custom-link-itemns" v-if="package_formdata.complete_payment_url_selection == 'send_payment_link'">
									<el-checkbox-group v-model="package_formdata.complete_payment_url_selected_method">
										<el-checkbox class="bpa-front-label bpa-custom-checkbox--is-label" label="email"><?php esc_html_e( 'Through Email', 'bookingpress-package' ); ?></el-checkbox>
										<?php
											do_action('bookingpress_add_more_complete_payment_link_option');
										?>
									</el-checkbox-group>
								</div>								
							</div>-->

						</div>
					</div>
				</el-col>
			</el-row>
		</div>		
	</div>
</el-dialog>


<el-dialog id="customer_add_modal" custom-class="bpa-dialog bpa-dialog--fullscreen bpa-dialog--customer-modal bpa--is-page-non-scrollable-mob" modal-append-to-body=false :visible.sync="open_customer_modal" :before-close="closePackageCustomerModal" fullscreen=true :close-on-press-escape="close_modal_on_esc">
    <div class="bpa-dialog-heading">
        <el-row type="flex">
            <el-col :xs="12" :sm="12" :md="16" :lg="16" :xl="16">
        <h1 class="bpa-page-heading" v-if="customer.update_id == 0"><?php esc_html_e('Add Customer', 'bookingpress-package'); ?></h1>
        <h1 class="bpa-page-heading" v-else><?php esc_html_e('Edit Customer', 'bookingpress-package'); ?></h1>
            </el-col>
            <el-col :xs="12" :sm="12" :md="7" :lg="7" :xl="7" class="bpa-dh__btn-group-col">
                <el-button class="bpa-btn bpa-btn--primary " :class="is_display_save_loader == '1' ? 'bpa-btn--is-loader' : ''" @click="savePackageCustomerDetails" :disabled="is_disabled" >
                    <span class="bpa-btn__label"><?php esc_html_e('Save', 'bookingpress-package'); ?></span>
                    <div class="bpa-btn--loader__circles">
                        <div></div>
                        <div></div>
                        <div></div>
                    </div>
                </el-button> 
                <el-button class="bpa-btn" @click="closePackageCustomerModal()"><?php esc_html_e('Cancel', 'bookingpress-package'); ?></el-button>
            </el-col>
        </el-row>
    </div>
    
    <div class="bpa-dialog-body">
        <div class="bpa-back-loader-container" v-if="is_display_loader == '1'">
            <div class="bpa-back-loader"></div>
        </div>
        <div class="bpa-form-row">
            <el-row>
                <el-col :xs="24" :sm="24" :md="24" :lg="24" :xl="24">
                    <div class="bpa-db-sec-heading">
                        <el-row type="flex" align="middle">
                            <el-col :xs="24" :sm="24" :md="12" :lg="12" :xl="12">
                                <div class="db-sec-left">
                                    <h2 class="bpa-page-heading"><?php esc_html_e('Basic Details', 'bookingpress-package'); ?></h2>
                                </div>
                            </el-col>
                            <!-- <el-col :xs="24" :sm="24" :md="12" :lg="12" :xl="12">
                                <div class="bpa-hw-right-btn-group">
                                    
                                </div>
                            </el-col> -->
                        </el-row>
                    </div>            
                    <div class="bpa-default-card bpa-db-card">
                        <el-form ref="customer" :rules="customer_rules" :model="customer" label-position="top" @submit.native.prevent>
                            <template>                            
                                <el-row :gutter="24">
                                    <el-col :xs="24" :sm="24" :md="24" :lg="24" :xl="24" class="bpa-form-group">
                                        <el-upload class="bpa-upload-component" ref="avatarRef" action="<?php echo wp_nonce_url($bookingpress_ajaxurl . '?action=bookingpress_upload_customer_avatar', 'bookingpress_upload_customer_avatar'); //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped --Reason - esc_html is already used by wp_nonce_url function and it's false positive ?>" :on-success="bookingpress_upload_customer_avatar_func" :file-list="customer.avatar_list" multiple="false" :show-file-list="cusShowFileList" limit="1" :on-exceed="bookingpress_image_upload_limit" :on-error="bookingpress_image_upload_err" :on-remove="bookingpress_remove_customer_avatar" :before-upload="checkUploadedFile" drag>
                                            <span class="material-icons-round bpa-upload-component__icon">cloud_upload</span>
                                           <div class="bpa-upload-component__text" v-if="customer.avatar_url == ''"><?php esc_html_e('jpg/png files with a size less than 500kb', 'bookingpress-package'); ?>                                           
                                           </div>
                                        </el-upload>
                                        <div class="bpa-uploaded-avatar__preview"  v-if="customer.avatar_url != ''">
                                            <button class="bpa-avatar-close-icon" @click="bookingpress_remove_customer_avatar">
                                                <span class="material-icons-round">close</span>
                                            </button>
                                            <el-avatar shape="square" :src="customer.avatar_url" class="bpa-uploaded-avatar__picture"></el-avatar>
                                        </div>
                                    </el-col>
                                </el-row>
                                <div class="bpa-form-body-row bpa-fbr--customer">
                                    <el-row :gutter="32" type="flex">
                                        <el-col :xs="24" :sm="24" :md="24" :lg="8" :xl="8">
                                            <el-form-item prop="wp_user">
                                                <template #label>
                                                    <span class="bpa-form-label"><?php esc_html_e('WordPress User', 'bookingpress-package'); ?></span>
                                                </template>
												<el-select class="bpa-form-control" v-model="customer.wp_user" filterable placeholder="<?php esc_html_e( 'Start typing to fetch user.', 'bookingpress-package' ); ?>" @change="bookingpress_get_existing_user_details($event)"  remote reserve-keyword	 :remote-method="get_wordpress_users" :loading="bookingpress_loading">
													<el-option-group label="<?php esc_html_e( 'Create New User', 'bookingpress-package' ); ?>">
														<template>
															<el-option value="add_new" label="Create New">
																<i class="el-icon-plus" ></i>
																<span><?php esc_html_e( 'Create New', 'bookingpress-package' ); ?></span>
															</el-option>
														</template>
													</el-option-group>
													<el-option-group v-for="wp_user_list_cat in wpUsersList" :key="wp_user_list_cat.category" :label="wp_user_list_cat.category">
														<template>
															<el-option v-for="item in wp_user_list_cat.wp_user_data" :key="item.wp_user" :label="item.label" :value="item.value" >
																<span>{{ item.label }}</span>
															</el-option>
														</template>
													</el-option-group>
												</el-select>
                                            </el-form-item>                                                
                                        </el-col>                                        
                                        <el-col :xs="24" :sm="24" :md="24" :lg="8" :xl="8" v-if="customer.wp_user =='add_new'">
                                            <el-form-item>
                                                <template #label>
                                                    <span class="bpa-form-label"><?php esc_html_e('Password', 'bookingpress-package'); ?></span>
                                                </template>
                                                <el-input class="bpa-form-control --bpa-fc-field-pass" type="password" v-model="customer.password" placeholder="<?php esc_html_e('Enter Password', 'bookingpress-package'); ?>" :show-password="true" ></el-input>
                                            </el-form-item>                                            
                                        </el-col>
                                            <el-col :xs="24" :sm="24" :md="24" :lg="8" :xl="8">
                                            <el-form-item prop="username">
                                                <template #label>
                                                    <span class="bpa-form-label"><?php esc_html_e('Username', 'bookingpress-package'); ?></span>
                                                </template>
                                                <el-input class="bpa-form-control" v-model="customer.username" id="username" name="username" placeholder="<?php esc_html_e('Enter Username', 'bookingpress-package'); ?>"></el-input>
                                            </el-form-item>
                                        </el-col>
                                        </el-col>
                                            <el-col :xs="24" :sm="24" :md="24" :lg="8" :xl="8">
                                            <el-form-item prop="firstname">
                                                <template #label>
                                                    <span class="bpa-form-label"><?php esc_html_e('First Name', 'bookingpress-package'); ?></span>
                                                </template>
                                                <el-input class="bpa-form-control" v-model="customer.firstname" id="firstname" name="firstname" placeholder="<?php esc_html_e('Enter First Name', 'bookingpress-package'); ?>"></el-input>
                                            </el-form-item>
                                        </el-col>
                                        <el-col :xs="24" :sm="24" :md="24" :lg="8" :xl="8">
                                            <el-form-item prop="lastname">
                                                <template #label>
                                                    <span class="bpa-form-label"><?php esc_html_e('Last Name', 'bookingpress-package'); ?></span>
                                                </template>
                                                <el-input class="bpa-form-control" v-model="customer.lastname" id="lastname" name="lastname" placeholder="<?php esc_html_e('Enter Last Name', 'bookingpress-package'); ?>"></el-input>
                                            </el-form-item>
                                        </el-col>                                            
                                        <el-col :xs="24" :sm="24" :md="24" :lg="8" :xl="8">
                                            <el-form-item prop="email">
                                                <template #label>
                                                    <span class="bpa-form-label"><?php esc_html_e('Email', 'bookingpress-package'); ?></span>
                                                </template>
                                                <el-input class="bpa-form-control" v-model="customer.email" id="email" name="email" placeholder="<?php esc_html_e('Enter Email', 'bookingpress-package'); ?>"></el-input>
                                            </el-form-item>
                                        </el-col>
                                        <el-col :xs="24" :sm="24" :md="24" :lg="8" :xl="8">
                                            <el-form-item prop="phone">
                                                <template #label>
                                                    <span class="bpa-form-label"><?php esc_html_e('Phone', 'bookingpress-package'); ?></span>
                                                </template>
                                                <vue-tel-input v-model="customer.phone" class="bpa-form-control --bpa-country-dropdown" @country-changed="bookingpress_phone_country_change_func($event)" v-bind="bookingpress_tel_input_props" ref="bpa_tel_input_field">
                                                    <template v-slot:arrow-icon>
                                                        <span class="material-icons-round">keyboard_arrow_down</span>
                                                    </template>
                                                </vue-tel-input>
                                            </el-form-item>
                                        </el-col>            
                                        <el-col :xs="24" :sm="24" :md="24" :lg="8" :xl="8">
                                            <el-form-item prop="note">
                                                <template #label>
                                                    <span class="bpa-form-label"><?php esc_html_e('Note', 'bookingpress-package'); ?></span>
                                                </template>
                                                <el-input class="bpa-form-control" type="textarea" :rows="3" v-model="customer.note"></el-input>
                                            </el-form-item>
                                        </el-col>
										<el-col :xs="24" :sm="24" :md="24" :lg="8" :xl="8" v-if="bookingpress_customer_fields.length > 0" :data-customer-field-id="bpa_cus_field.bookingpress_form_field_id" v-for="(bpa_cus_field, cfkey) in bookingpress_customer_fields"> 
											<el-form-item :prop="bpa_cus_field.bookingpress_field_meta_key">
												<template #label>
													<span class="bpa-form-label">{{bpa_cus_field.bookingpress_field_label}}</span>
												</template>
												<el-input class="bpa-form-control" v-model="customer['bpa_customer_field'][bpa_cus_field.bookingpress_field_meta_key]" :placeholder="bpa_cus_field.bookingpress_field_placeholder" v-if="'text' == bpa_cus_field.bookingpress_field_type"></el-input>
												<el-input class="bpa-form-control" :placeholder="bpa_cus_field.bookingpress_field_placeholder" v-model="customer['bpa_customer_field'][bpa_cus_field.bookingpress_field_meta_key]" v-if="'textarea' == bpa_cus_field.bookingpress_field_type" type="textarea"></el-input>
												<template v-if="'checkbox' == bpa_cus_field.bookingpress_field_type">
													<el-checkbox v-model="customer['bpa_customer_field'][bpa_cus_field.bookingpress_field_meta_key+'_'+keys]" class="bpa-form-label bpa-custom-checkbox--is-label" v-for="(chk_data,keys) in bpa_cus_field.bookingpress_field_values" :label="chk_data.value" :key="chk_data.value">{{chk_data.value}}</el-checkbox>
												</template>
												<template v-if="'radio' == bpa_cus_field.bookingpress_field_type">
													<el-radio v-model="customer['bpa_customer_field'][bpa_cus_field.bookingpress_field_meta_key]" class="bpa-form-label bpa-custom-radio--is-label" v-for="(rdo_data,keys) in bpa_cus_field.bookingpress_field_values" :label="rdo_data.value" :key="rdo_data.value">{{rdo_data.value}}</el-radio>
												</template>
												<template v-if="'dropdown' == bpa_cus_field.bookingpress_field_type">
													<el-select  v-model="customer['bpa_customer_field'][bpa_cus_field.bookingpress_field_meta_key]" class="bpa-form-control" :placeholder="bpa_cus_field.bookingpress_field_placeholder">
														<el-option v-for="sel_data in bpa_cus_field.bookingpress_field_values" :key="sel_data.value" :label="sel_data.label" :value="sel_data.value" ></el-option>
													</el-select>
												</template>
												<el-date-picker  :format="( 'true' == bpa_cus_field.bookingpress_field_options.enable_timepicker ) ? '<?php echo esc_html( $bookingpress_common_datetime_format ); ?>' : '<?php echo esc_html( $bookingpress_common_date_format ) ?>'" :placeholder="bpa_cus_field.bookingpress_field_placeholder" v-model="customer['bpa_customer_field'][bpa_cus_field.bookingpress_field_meta_key]" class="bpa-form-control bpa-form-control--date-picker" prefix-icon="" v-if="'date' == bpa_cus_field.bookingpress_field_type || 'datepicker' == bpa_cus_field.bookingpress_field_type" :type="'true' == bpa_cus_field.bookingpress_field_options.enable_timepicker ? 'datetime' : 'date'" :placeholder="bpa_cus_field.placeholder" :value-format="bpa_cus_field.bookingpress_field_options.enable_timepicker == 'true' ? 'yyyy-MM-dd hh:mm:ss' : 'yyyy-MM-dd'" :picker-options="filter_pickerOptions"></el-date-picker> <!-- @change="bpa_get_customer_formatted_date($event, bpa_cus_field.bookingpress_field_meta_key,bpa_cus_field.bookingpress_field_options.enable_timepicker)" -->
											</el-form-item>
										</el-col>
                                    </el-row>
                                </div>
                            </template>
                        </el-form>
                    </div>
                </el-col>
            </el-row>
        </div>
    </div>
</el-dialog>

<!-- Package Order Expiration Date Change --> 
<el-dialog id="update_package_expiration_date_process" custom-class="bpa-dialog bpa-dailog__small bpa-dialog--expiration-change-process" title="" :visible.sync="update_package_expiration_date_modal" :close-on-press-escape="close_modal_on_esc" :modal="is_mask_display">
	<div class="bpa-dialog-heading">
		<el-row type="flex">
			<el-col :xs="12" :sm="12" :md="16" :lg="16" :xl="16">
				<h1 class="bpa-page-heading" ><?php esc_html_e( 'Update Expire date', 'bookingpress-package' ); ?></h1>
			</el-col>
		</el-row>
	</div>
	<div class="bpa-dialog-body bpa-pack-exp-body">
		<el-container class="bpa-grid-list-container bpa-add-categpry-container">
            <div class="bpa-form-row">
                <el-row>
					<el-col :xs="24" :sm="24" :md="24" :lg="24" :xl="24">
						<el-form ref="package_expiry_date_update_form" :rules="rules_package_expiry_date_update_form" :model="package_expiry_date_update_form" label-position="top">
                            <el-row>
                                    <el-col :xs="24" :sm="24" :md="24" :lg="24" :xl="24">
                                        <el-form-item prop="allow_refund" class="bpa-appointment-rp__allow-packexp-row">
                                            <template #label>
                                                <span class="bpa-form-label"><?php esc_html_e( 'Package expire date', 'bookingpress-package' ); ?></span>
                                            </template>                                            
                                            <el-date-picker v-if="update_package_expiration_date_modal" class="bpa-form-control bpa-form-control--date-picker" type="date" placeholder="<?php esc_html_e( 'Package expire date', 'bookingpress-package' ); ?>" format="<?php echo esc_html($bookingpress_common_date_format); ?>" v-model="package_expiry_date_update_form.package_updated_expiration_date" name="package_updated_expiration_date" type="date" popper-class="bpa-el-select--is-with-modal bpa-el-datepicker-widget-wrapper" :clearable="false" :picker-options="package_date_pickerOptions" value-format="yyyy-MM-dd"></el-date-picker>
                                        </el-form-item>
                                    </el-col>
                            </el-row>                                
                        </el-form>                    
                    </el-col>    
                </el-row>
                
			</div>
		</el-container>
	</div>
	<div class="bpa-dialog-footer">
		<div class="bpa-hw-right-btn-group">
			<el-button class="bpa-btn bpa-btn__small" @click="bookingpress_close_package_expiration_modal"><?php esc_html_e( 'Cancel', 'bookingpress-package' ); ?></el-button>
			<el-button class="bpa-btn bpa-btn__small bpa-btn--primary" :class="(is_display_package_expiration_loader == '1') ? 'bpa-btn--is-loader' : ''" @click="bookingpress_apply_to_change_expire_date()" :disabled="(is_display_package_expiration_loader == '1')?true:false">
				<span class="bpa-btn__label"><?php esc_html_e( 'Apply', 'bookingpress-package' ); ?></span>
				<div class="bpa-btn--loader__circles">				    
					<div></div>
					<div></div>
					<div></div>
				</div>
			</el-button>
		</div>
	</div>
</el-dialog>
