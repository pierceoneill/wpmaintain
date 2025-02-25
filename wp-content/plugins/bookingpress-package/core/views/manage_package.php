<?php
	global $bookingpress_ajaxurl, $bookingpress_common_date_format, $bookingpress_global_options;
	$bookingpress_global_options_arr = $bookingpress_global_options->bookingpress_global_options();

?>
<el-main class="bpa-main-listing-card-container bpa-mlc__package-container bpa-default-card bpa--is-page-scrollable-tablet" id="all-page-main-container">
	<el-row type="flex" class="bpa-mlc-head-wrap __services-screen">
		<el-col :xs="24" :sm="24" :md="24" :lg="12" :xl="12" class="bpa-mlc-left-heading">
			<h1 class="bpa-page-heading"><?php esc_html_e( 'Manage Packages', 'bookingpress-package' ); ?></h1>
		</el-col>
		<el-col :xs="24" :sm="24" :md="24" :lg="12" :xl="12">
			<div class="bpa-hw-right-btn-group">
				<el-button class="bpa-btn bpa-btn--primary" @click="open_add_package_modal('add')">
					<span class="material-icons-round">add</span>
					<?php esc_html_e( 'Add New', 'bookingpress-package' ); ?>
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
				<el-col :xs="24" :sm="24" :md="24" :lg="9" :xl="10">
					<span class="bpa-form-label"><?php esc_html_e( 'Package Name', 'bookingpress-package' ); ?></span>
					<el-input class="bpa-form-control" v-model="search_package_name"
						placeholder="<?php esc_html_e( 'Enter Package Name', 'bookingpress-package' ); ?>">
					</el-input>
				</el-col>
				<el-col :xs="24" :sm="24" :md="24" :lg="6" :xl="4">
					<div class="bpa-tf-btn-group">
						<el-button class="bpa-btn bpa-btn__medium bpa-btn--full-width" @click="resetFilter">
							<?php esc_html_e( 'Reset', 'bookingpress-package' ); ?>
						</el-button>
						<el-button class="bpa-btn bpa-btn__medium bpa-btn--primary bpa-btn--full-width"
							@click="loadPackages">
							<?php esc_html_e( 'Apply', 'bookingpress-package' ); ?>
						</el-button>
					</div>
				</el-col>
			</el-row>
		</div>		
		<el-row type="flex" v-if="items.length == 0">
			<el-col :xs="24" :sm="24" :md="24" :lg="24" :xl="24">
				<div class="bpa-data-empty-view">
					<div class="bpa-ev-left-vector">
						<picture>
							<source srcset="<?php echo esc_url( BOOKINGPRESS_IMAGES_URL . '/data-grid-empty-view-vector.webp' ); ?>" type="image/webp">
							<img src="<?php echo esc_url( BOOKINGPRESS_IMAGES_URL . '/data-grid-empty-view-vector.png' ); ?>">
						</picture>
					</div>
					<div class="bpa-ev-right-content">
						<h4><?php esc_html_e( 'No Record Found!', 'bookingpress-package' ); ?></h4>						
						<el-button class="bpa-btn bpa-btn--primary bpa-btn__medium" @click="open_add_package_modal('add')"> 
							<span class="material-icons-round">add</span>
							<?php esc_html_e( 'Add New', 'bookingpress-package' ); ?>
						</el-button>
					</div>
				</div>
			</el-col>
		</el-row>
		<el-container class="bpa-grid-list-container bpa-grid-list--service-page"> <!-- reputelog - need to change -->
			<div class="bpa-back-loader-container" v-if="is_display_loader == '1'">
				<div class="bpa-back-loader"></div>
			</div>
			<el-row v-if="items.length > 0">
				<el-col :xs="24" :sm="24" :md="24" :lg="24" :xl="24">
					<div class="bpa-card bpa-card__heading-row">
						<el-row type="flex">
							<el-col :xs="24" :sm="10" :md="10" :lg="10" :xl="9">
								<div class="bpa-card__item bpa-card__item--ischecbox">
									<el-checkbox v-model="is_multiple_checked" @change="selectAllpackages($event)"></el-checkbox><?php /* @change="selectAllServices($event)"*/ ?>
									<h4 class="bpa-card__item__heading"><?php esc_html_e( 'Name', 'bookingpress-package' ); ?></h4>
								</div>
							</el-col>
							<el-col :xs="24" :sm="5" :md="5" :lg="5" :xl="8">
								<div class="bpa-card__item">
									<h4 class="bpa-card__item__heading"><?php esc_html_e('Services', 'bookingpress-package'); ?></h4>
								</div>
							</el-col>
							<el-col :xs="24" :sm="4" :md="4" :lg="4" :xl="5">
								<div class="bpa-card__item">
									<h4 class="bpa-card__item__heading"><?php esc_html_e( 'Duration', 'bookingpress-package'); ?><h4>
								</div>
							</el-col>
							<el-col :xs="24" :sm="3" :md="3" :lg="3" :xl="5">
								<div class="bpa-card__item">
									<h4 class="bpa-card__item__heading"><?php esc_html_e( 'Appointments', 'bookingpress-package' ); ?></h4>
								</div>
							</el-col>							
							<el-col :xs="24" :sm="3" :md="3" :lg="3" :xl="5">
								<div class="bpa-card__item">
									<h4 class="bpa-card__item__heading"><?php esc_html_e( 'Retail Price', 'bookingpress-package' ); ?></h4>
								</div>
							</el-col>
							<el-col :xs="24" :sm="3" :md="3" :lg="3" :xl="5">
								<div class="bpa-card__item">
									<h4 class="bpa-card__item__heading"><?php esc_html_e( 'Package Price', 'bookingpress-package' ); ?></h4>
								</div>
							</el-col>							
						</el-row>
					</div>
				</el-col>
				<draggable :list="items" class="list-group" ghost-class="ghost" @start="dragging = true" @end="updatepackagePosition($event)" > <?php /* @end="updateServicePos($event)" :disabled="!enabled" */ ?>
					<el-col :xs="24" :sm="24" :md="24" :lg="24" :xl="24" v-for="items_data in items" :data-package_id="items_data.bookingpress_package_id" > <!-- :data-service_id="items_data.service_id -->
						<div class="bpa-card bpa-card__body-row list-group-item">
							<div class="bpa-card__item--drag-icon-wrap">
								<span class="material-icons-round">drag_indicator</span>
							</div>
							<el-row type="flex">
								<el-col :xs="24" :sm="10" :md="10" :lg="10" :xl="9">
									<div class="bpa-card__item bpa-card__item--ischecbox">

										<el-tooltip effect="dark" content="" placement="top" v-if="items_data.package_bulk_action">
                                            <div slot="content">
                                                <span><?php esc_html_e('One or more appointments are associated with this package,', 'bookingpress-package'); ?></span><br/>
                                                <span><?php esc_html_e('so you will not be able to delete it', 'bookingpress-package'); ?></span>
                                            </div>
                                            <el-checkbox v-model="items_data.selected" :disabled=items_data.package_bulk_action @change="handleSelectionChange(event, $event, items_data.service_id)"></el-checkbox>
                                        </el-tooltip>
										<el-checkbox v-model="items_data.selected" :disabled=items_data.package_bulk_action @change="handlepackageSelectionChange(event, $event, items_data.bookingpress_package_id)" v-else></el-checkbox><?php ?>
										<img :src="items_data.package_image" alt="package-thumbnail" class="bpa-card__item--service-thumbnail" v-if="items_data.package_image != ''">
										<img :src="package_default_img_url" alt="package-thumbnail" class="bpa-card__item--service-thumbnail" v-else />
										<h4 class="bpa-card__item__heading is--body-heading"> <span v-html="items_data.bookingpress_package_name"></span> <span class="bpa-card__item--id">(<?php esc_html_e( 'ID', 'bookingpress-package' ); ?>: {{ items_data.bookingpress_package_id }} )</span></h4>
									</div>
								</el-col> 
								<el-col :xs="24" :sm="5" :md="5" :lg="5" :xl="8">
									<div class="bpa-card__item bpa-card__item--ischecbox bpa-card__item--with-count">										
										<h4 class="bpa-card__item__heading is--body-heading"> <span v-html="items_data.package_service_display"></span></h4>
										<el-popover v-if="items_data.package_service_count != 0" placement="top-start" title="<?php esc_html_e('Other Services', 'bookingpress-package'); ?>" width="280" trigger="hover" popper-class="bpa-card-item-extra-popover">
											<div class="bpa-card-item-extra-content">
												<div class="bpa-cec__item" v-for="pack_service in items_data.package_services">{{ pack_service }}</div>												
											</div>
											<div slot="reference" class="bpa-card__item-extra-tooltip">
												<el-link class="bpa-iet__label">+{{ items_data.package_service_count }}</el-link>
											</div>
										</el-popover>
									</div>
								</el-col>
								<el-col :xs="24" :sm="4" :md="4" :lg="4" :xl="5">
									<div class="bpa-card__item">
										<h4 class="bpa-card__item__heading is--body-heading">{{ items_data.package_duration }}</h4>
									</div>
								</el-col> 
								<el-col :xs="24" :sm="3" :md="3" :lg="3" :xl="5">
									<div class="bpa-card__item">
										<h4 class="bpa-card__item__heading is--body-heading">{{ items_data.package_total_appointment }}</h4>
									</div>
								</el-col>
								<el-col :xs="24" :sm="3" :md="3" :lg="3" :xl="5">
									<div class="bpa-card__item">
										<h4 class="bpa-card__item__heading is--body-heading">{{ items_data.retail_price }}</h4>
									</div>
								</el-col>
								<el-col :xs="24" :sm="3" :md="3" :lg="3" :xl="5">
									<div class="bpa-card__item">
										<h4 class="bpa-card__item__heading is--body-heading">{{ items_data.package_price }}</h4>
									</div>
								</el-col>								 								
							</el-row>
							<div class="bpa-table-actions-wrap">
								<div class="bpa-table-actions">
								<el-tooltip popper-class="bpa-tooltip-with-confirm" effect="dark" content="" placement="top" open-delay="300" v-if="items_data.bookingpress_package_status == '1' || items_data.bookingpress_package_status == 1">
										<div slot="content">
											<span><?php esc_html_e( 'Disable Package', 'bookingpress-package' ); ?></span>
										</div>
										<el-popconfirm
											cancel-button-text='<?php esc_html_e( 'Cancel', 'bookingpress-package' ); ?>' 
											confirm-button-text='<?php esc_html_e( 'Disable', 'bookingpress-package' ); ?>' 
											icon="false" 
											title="<?php esc_html_e( 'Are you sure you want to disable package?', 'bookingpress-package' ); ?>" 
											@confirm="bookingpress_package_change_status(items_data.bookingpress_package_id, '0')" 
											confirm-button-type="bpa-btn bpa-btn__small bpa-btn--secondary" 
											cancel-button-type="bpa-btn bpa-btn__small">
											<el-button type="text" slot="reference" class="bpa-btn bpa-btn--icon-without-box __secondary">
												<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
													<path d="M10 16.6668L9.28333 15.9502C8.3 14.9668 8.30833 13.3668 9.3 12.4002L10 11.7168C9.675 11.6835 9.43333 11.6668 9.16667 11.6668C6.94167 11.6668 2.5 12.7835 2.5 15.0002V16.6668H10ZM9.16667 10.0002C11.0083 10.0002 12.5 8.5085 12.5 6.66683C12.5 4.82516 11.0083 3.3335 9.16667 3.3335C7.325 3.3335 5.83333 4.82516 5.83333 6.66683C5.83333 8.5085 7.325 10.0002 9.16667 10.0002Z" />
													<path d="M16.9992 14.5006C16.9992 16.4334 15.4324 18.0002 13.4996 18.0002C11.5668 18.0002 10 16.4334 10 14.5006C10 12.5678 11.5668 11.001 13.4996 11.001C15.4324 11.001 16.9992 12.5678 16.9992 14.5006ZM11.1199 14.5006C11.1199 15.8149 12.1853 16.8803 13.4996 16.8803C14.8139 16.8803 15.8793 15.8149 15.8793 14.5006C15.8793 13.1863 14.8139 12.1209 13.4996 12.1209C12.1853 12.1209 11.1199 13.1863 11.1199 14.5006Z" />
													<rect x="14.9219" y="11.7139" width="1.16654" height="5.83268" transform="rotate(35.8094 14.9219 11.7139)"/>
												</svg>
											</el-button>
										</el-popconfirm>
									</el-tooltip>
									<el-tooltip effect="dark" content="" placement="top" open-delay="300" v-else>
										<div slot="content">
											<span><?php esc_html_e( 'Enable Package', 'bookingpress-package' ); ?></span>
										</div>
										<el-popconfirm
											cancel-button-text='<?php esc_html_e( 'Cancel', 'bookingpress-package' ); ?>' 
											confirm-button-text='<?php esc_html_e( 'Enable', 'bookingpress-package' ); ?>' 
											icon="false" 
											title="<?php esc_html_e( 'Are you sure you want to enable package?', 'bookingpress-package' ); ?>" 
											@confirm="bookingpress_package_change_status(items_data.bookingpress_package_id, '1')"
											confirm-button-type="bpa-btn bpa-btn__small bpa-btn--secondary" 
											cancel-button-type="bpa-btn bpa-btn__small">
											<el-button type="text" slot="reference" class="bpa-btn bpa-btn--icon-without-box __secondary">
												<span class="material-icons-round">how_to_reg</span>
											</el-button>
										</el-popconfirm>
									</el-tooltip>																	
									<el-tooltip effect="dark" content="" placement="top" open-delay="300">
										<div slot="content">
											<span><?php esc_html_e( 'Edit', 'bookingpress-package' ); ?></span>
										</div>
										<el-button class="bpa-btn bpa-btn--icon-without-box" @click.native.prevent="editpackage(items_data.bookingpress_package_id)">
											<span class="material-icons-round">mode_edit</span>
										</el-button>
									</el-tooltip>
									<el-tooltip effect="dark" content="" placement="top" open-delay="300">
										<div slot="content">
											<span><?php esc_html_e( 'Duplicate', 'bookingpress-package' ); ?></span>
										</div>
										<el-button class="bpa-btn bpa-btn--icon-without-box __secondary" @click.native.prevent="bookingpress_duplicate_package(items_data.bookingpress_package_id)">
											<span class="material-icons-round">queue</span>
										</el-button>
									</el-tooltip>									
									<el-tooltip effect="dark" content="" placement="top" open-delay="300">
										<div slot="content">
											<span><?php esc_html_e( 'Delete', 'bookingpress-package' ); ?></span>
										</div>
										<el-popconfirm 
											confirm-button-text='<?php esc_html_e( 'Delete', 'bookingpress-package' ); ?>' 
											cancel-button-text='<?php esc_html_e( 'Cancel', 'bookingpress-package' ); ?>' 
											icon="false" 
											title="<?php esc_html_e( 'Are you sure you want to delete this package?', 'bookingpress-package' ); ?>" 
											@confirm="deletepackage(items_data.bookingpress_package_id)" 
											confirm-button-type="bpa-btn bpa-btn__small bpa-btn--danger" 
											cancel-button-type="bpa-btn bpa-btn__small">
											<el-button type="text" slot="reference" class="bpa-btn bpa-btn--icon-without-box __danger">
												<span class="material-icons-round">delete</span>
											</el-button>
										</el-popconfirm>
									</el-tooltip>
								</div>
							</div>
						</div>
					</el-col>
				</draggable>
			</el-row>
		</el-container>
		<el-row class="bpa-pagination" v-if="items.length > 0">
			<el-container v-if="multiplepackageSelection.length > 0" class="bpa-default-card bpa-bulk-actions-card">
				<el-button class="bpa-btn bpa-btn--icon-without-box bpa-bac__close-icon" @click="clearBulkAction">
					<span class="material-icons-round">close</span>
				</el-button>
				<el-row type="flex" class="bpa-bac__wrapper">
					<el-col class="bpa-bac__left-area" :xs="12" :sm="12" :md="12" :lg="12" :xl="12">
						<span class="material-icons-round">check_circle</span>
						<p>{{ multiplepackageSelection.length }}<?php esc_html_e(' Items Selected', 'bookingpress-package'); ?></p>
					</el-col>
					<el-col class="bpa-bac__right-area" :xs="12" :sm="12" :md="12" :lg="12" :xl="12">
						<el-select class="bpa-form-control" v-model="package_bulk_action" placeholder="<?php esc_html_e('Select', 'bookingpress-package'); ?>"
						popper-class="bpa-dropdown--bulk-actions">
							<el-option v-for="item in bulk_options" :key="item.value" :label="item.label" :value="item.value"></el-option>
						</el-select>
						<el-button @click="bulk_actions_package" class="bpa-btn bpa-btn--primary bpa-btn__medium">
							<?php esc_html_e('Go', 'bookingpress-package'); ?>
						</el-button>
					</el-col>
				</el-row>
			</el-container>
		</el-row>
		
	</div>
</el-main>



<!-- Package Add Modal -->
<el-dialog custom-class="bpa-dialog bpa-dialog--fullscreen bpa-dialog--fullscreen__package bpa--is-page-scrollable-tablet" title="" :visible.sync="open_package_modal" top="32px" fullscreen=true :close-on-press-escape="close_modal_on_esc">
    <div class="bpa-dialog-heading">
        <el-row type="flex">
            <el-col :xs="12" :sm="12" :md="16" :lg="16" :xl="16">
                <h1 class="bpa-page-heading" v-if="package.package_update_id == 0">
                    <?php esc_html_e('Add Package', 'bookingpress-package'); ?></h1>
                <h1 class="bpa-page-heading" v-else><?php esc_html_e('Edit Package', 'bookingpress-package'); ?></h1>
            </el-col>
            <el-col :xs="12" :sm="12" :md="7" :lg="7" :xl="7" class="bpa-dh__btn-group-col">
                <el-button class="bpa-btn bpa-btn--primary" :class="(is_display_save_loader == '1') ? 'bpa-btn--is-loader' : ''" :disabled="is_disabled" @click="bookingpress_save_package">
                      <span class="bpa-btn__label"><?php esc_html_e('Save', 'bookingpress-package'); ?></span>
                      <div class="bpa-btn--loader__circles">                    
                          <div></div>
                          <div></div>
                          <div></div>
                      </div>
                </el-button>    
                <el-button class="bpa-btn" @click="closepackageModal()">
                    <?php esc_html_e('Cancel', 'bookingpress-package'); ?></el-button>
		 			<?php do_action('bookingpress_package_header_extra_button'); ?>    
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
                        </el-row>
                    </div>
                    <div class="bpa-default-card bpa-db-card">
                        <el-form ref="package" :rules="rules" :model="package" label-position="top" @submit.native.prevent>
                            <template>
                                <el-row :gutter="24">
                                    <el-col :xs="24" :sm="24" :md="24" :lg="24" :xl="24" class="bpa-form-group">
									  <div class="bpa-multi-select-fields">	
										<div class="bpa-multi-upload-col-head">
											<el-upload class="bpa-upload-component bpa-multi-upload-component" ref="avatarRef"
												action="<?php echo wp_nonce_url(admin_url('admin-ajax.php') . '?action=bookingpress_upload_package', 'bookingpress_upload_package');//phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped --Reason - esc_html is already used by wp_nonce_url function and it's false positive ?>"
												:on-success="bookingpress_upload_package_func"
												 
												:multiple="true"
												:show-file-list="packageShowFileList" 
												:limit="10"
												:on-exceed="bookingpress_image_upload_limit"
												:on-error="bookingpress_image_upload_err"
												:on-remove="bookingpress_remove_package_img"
												:before-upload="checkUploadedFile" drag>
												<span
													class="material-icons-round bpa-upload-component__icon">cloud_upload</span>
												<div class="bpa-upload-component__text" v-if="package.package_image == ''"><?php esc_html_e('Upload jpg/png/webp file', 'bookingpress-package'); ?></div>
											</el-upload>
										</div>
										<div class="bpa-multi-upload-col" v-if="package.package_images_list.length != 0" v-for="(pack_image, keys) in package.package_images_list">
											<div class="bpa-multi-uploaded-avatar__preview">		
												<button type="button" class="bpa-avatar-close-icon" @click="bookingpress_remove_package_img(keys)">
														<span class="material-icons-round">close</span>
												</button>											
												<picture class="bpa-multi-upload-imgs">												
													<img :src="pack_image.image_url" alt="Image">
												</picture>										
											</div>	
										</div>
									  </div>	                                        
									</el-col>										
								</el-row>
								<div class="bpa-form-body-row">
									<el-row :gutter="32">
										<el-col :xs="24" :sm="24" :md="24" :lg="08" :xl="08">
											<el-form-item prop="package_name">
												<template #label>
													<span class="bpa-form-label"><?php esc_html_e( 'Package Name:', 'bookingpress-package' ); ?></span>
												</template>
												<el-input class="bpa-form-control" v-model="package.package_name" placeholder="<?php esc_html_e( 'Enter package name', 'bookingpress-package' ); ?>">
												</el-input>
											</el-form-item>
										</el-col>
										<el-col :xs="24" :sm="24" :md="24" :lg="08" :xl="08">
											<el-form-item prop="package_duration_val">
												<template #label>
													<span class="bpa-form-label"><?php esc_html_e( 'Duration:', 'bookingpress-package' ); ?> </span>
												</template>
												<el-row :gutter="10">
													<el-col :xs="18" :sm="18" :md="18" :lg="16" :xl="18">
														<el-input-number class="bpa-form-control bpa-form-control--number" :min="1" v-model="package.package_duration_val" id="package_duration_val" name="package_duration_val" step-strictly></el-input-number>
													</el-col>
													<el-col :xs="6" :sm="6" :md="6" :lg="8" :xl="6">
														<el-select class="bpa-form-control" v-model="package.package_duration_unit" popper-class="bpa-el-select--is-with-modal bpa-service-number-control-dropdown bpa-el-select--is-sm-modal">
															
															<el-option key="d" label="<?php esc_html_e( 'Days', 'bookingpress-package' ); ?>" value="d"></el-option>															
															<el-option key="m" label="<?php esc_html_e( 'Months', 'bookingpress-package' ); ?>" value="m"></el-option>
															<el-option key="y" label="<?php esc_html_e( 'Years', 'bookingpress-package' ); ?>" value="y"></el-option>															
														</el-select>
													</el-col>
												</el-row>
											</el-form-item>
										</el-col>
										<el-col :xs="24" :sm="24" :md="24" :lg="08" :xl="08" >
											<el-form-item prop="package_price">
												<template #label>
													<span class="bpa-form-label"><?php esc_html_e( 'Price:', 'bookingpress-package' ); ?>({{package_price_currency}})</span>
												</template>
												<el-input class="bpa-form-control" @input="isNumberValidate($event)" v-model="package.package_price" id="package_price" name="package_price" placeholder="0.00" v-if="price_number_of_decimals != '0'"></el-input>  
											</el-form-item>
										</el-col>
									</el-row>
								</div>
								<div class="bpa-form-body-row">
									<el-row :gutter="32">																		
										<el-col :xs="24" :sm="24" :md="24" :lg="08" :xl="08">
											<el-form-item>
												<template #label>
													<span
														class="bpa-form-label"><?php esc_html_e( 'Per Customer Limit:', 'bookingpress-package' ); ?> </span>
												</template>		
												<el-select :filterable="true" v-model="package.package_purchase_limit" id="package_purchase_limit" popper-class="bpa-el-select--is-with-modal bpa-service-number-control-dropdown bpa-el-select--is-sm-modal" class="bpa-form-control">
													<el-option v-for="(nper, keys) in bookingpress_no_of_customer_limit" :label="nper.label" :value="(nper.value == '0' || nper.value == 0 || nper.value == '')?'0':nper.value">
														<span>{{nper.label}}</span>
													</el-option>
												</el-select>																						
												<!--<el-input-number class="bpa-form-control bpa-form-control--number" :min="0" v-model="package.package_purchase_limit" id="package_purchase_limit" name="package_purchase_limit" step-strictly></el-input-number>
												<span class="bpa-sm__field-helper-label"><?php esc_html_e('Leave it 0 for unlimited.', 'bookingpress-package'); ?></span>-->
											</el-form-item>
										</el-col>
										<el-col :xs="24" :sm="24" :md="24" :lg="08" :xl="08">
											<el-form-item>
												<template #label>
													<span
														class="bpa-form-label"><?php esc_html_e( 'Description:', 'bookingpress-package' ); ?> </span>
												</template>
												<el-input class="bpa-form-control" v-model="package.package_description" type="textarea" :rows="5" placeholder="<?php esc_html_e( 'Description', 'bookingpress-package' ); ?>">
												</el-input>
											</el-form-item>
										</el-col>
										<el-col :xs="24" :sm="24" :md="24" :lg="08" :xl="08">
										</el-col>	
									</el-row>
								</div>

							</template>
						</el-form>
					</div>
				</el-col>
			</el-row>
		</div>
		<?php /** Service Selection */ ?>
		<div class="bpa-form-row">
			<el-row>
				<el-col :xs="24" :sm="24" :md="24" :lg="24" :xl="24">
					<div class="bpa-db-sec-heading">
						<el-row type="flex" align="middle">
							<el-col :xs="24" :sm="24" :md="12" :lg="12" :xl="12">
								<div class="bpa-db-sec-left">
									<h2 class="bpa-page-heading"><?php esc_html_e( 'Services', 'bookingpress-package'); ?></h2>
								</div>
							</el-col>
							<el-col :xs="24" :sm="24" :md="12" :lg="12" :xl="12">
								<div class="bpa-hw-right-btn-group">
									<el-button class="bpa-btn bpa-btn__filled-light" @click="bookingpress_package_add_service_model(event)">
										<span class="material-icons-round">add</span>
										<?php esc_html_e( 'Add New', 'bookingpress-package' ); ?>
									</el-button>
								</div>
							</el-col>
						</el-row>
					</div>
					<div class="bpa-default-card bpa-db-card bpa-grid-list-container bpa-dc__staff--assigned-service">
						<el-row class="bpa-dc--sec-sub-head" v-if="package_assigned_services.length != 0">
							<el-col :xs="24" :sm="24" :md="24" :lg="24" :xl="24">
								<h2 v-if="package_assigned_services.length == 1" class="bpa-sec--sub-heading">{{ package_assigned_services.length }} <?php esc_html_e( 'Service', 'bookingpress-package' ); ?></h2>
								<h2 v-if="package_assigned_services.length > 1" class="bpa-sec--sub-heading">{{ package_assigned_services.length }} <?php esc_html_e( 'Services', 'bookingpress-package' ); ?></h2>
							</el-col>
						</el-row>						
						<div class="bpa-as__body">
							<el-row type="flex" class="bpa-as__empty-view" v-if="package_assigned_services.length == 0">
								<el-col :xs="24" :sm="24" :md="24" :lg="24" :xl="24">
									<div class="bpa-data-empty-view">
										<div class="bpa-ev-left-vector">
											<picture>
												<source srcset="<?php echo esc_url( BOOKINGPRESS_IMAGES_URL . '/data-grid-empty-view-vector.webp' ); ?>" type="image/webp">
												<img src="<?php echo esc_url( BOOKINGPRESS_IMAGES_URL . '/data-grid-empty-view-vector.png' ); ?>">
											</picture>
										</div>				
										<div class="bpa-ev-right-content">					
											<h4><?php esc_html_e( 'No Service Found', 'bookingpress-package' ); ?></h4>
										</div>				
									</div>
								</el-col>
							</el-row>
							<el-row v-else>
								<el-col :xs="24" :sm="24" :md="24" :lg="24" :xl="24">
									<div class="bpa-card bpa-card__heading-row">
										<el-row type="flex">
											<el-col :xs="07" :sm="07" :md="07" :lg="07" :xl="07">
												<div class="bpa-card__item">
													<h4 class="bpa-card__item__heading"><?php esc_html_e( 'Service Name', 'bookingpress-package' ); ?></h4>
												</div>
											</el-col>											
											<el-col :xs="07" :sm="07" :md="07" :lg="07" :xl="07">
												<div class="bpa-card__item">
													<h4 class="bpa-card__item__heading"><?php esc_html_e( 'Duration', 'bookingpress-package' ); ?></h4>
												</div>
											</el-col>
											<el-col :xs="07" :sm="07" :md="07" :lg="07" :xl="07">
												<div class="bpa-card__item">
													<h4 class="bpa-card__item__heading"><?php esc_html_e( 'No. Of Appointments', 'bookingpress-package' ); ?></h4>
												</div>
											</el-col>
											<el-col :xs="3" :sm="3" :md="3" :lg="3" :xl="3">
												<div class="bpa-card__item">
													<h4 class="bpa-card__item__heading"><?php esc_html_e( 'Action', 'bookingpress-package' ); ?></h4>
												</div>
											</el-col>
										</el-row>
									</div>
								</el-col>
								<el-col :xs="24" :sm="24" :md="24" :lg="24" :xl="24" v-for="(assigned_service_details,key) in package_assigned_services">
									<div class="bpa-card bpa-card__body-row list-group-item">
										<el-row type="flex">
											<el-col :xs="07" :sm="07" :md="07" :lg="07" :xl="07">
												<div class="bpa-card__item">
													<h4 class="bpa-card__item__heading is--body-heading">{{ assigned_service_details.service_name }}</h4>
												</div>
											</el-col>
											<el-col :xs="07" :sm="07" :md="07" :lg="07" :xl="07">
												<div class="bpa-card__item">
													<h4 class="bpa-card__item__heading is--body-heading">{{ assigned_service_details.service_duration}}</h4>
												</div>
											</el-col>											
											<el-col :xs="07" :sm="07" :md="07" :lg="07" :xl="07">
												<div class="bpa-card__item">
													<h4 class="bpa-card__item__heading is--body-heading">{{ assigned_service_details.service_no_of_appointments }}</h4>
												</div>
											</el-col>
											<el-col :xs="3" :sm="3" :md="3" :lg="3" :xl="3">
												<div>
													<el-tooltip effect="dark" content="" placement="top" open-delay="300">
														<div slot="content">
															<span><?php esc_html_e( 'Edit', 'bookingpress-package' ); ?></span>
														</div>
														<el-button class="bpa-btn bpa-btn--icon-without-box" @click="bookingpress_edit_assigned_package_service( assigned_service_details.bookingpress_package_service_id, event, key)">
															<span class="material-icons-round">mode_edit</span>
														</el-button>
													</el-tooltip>
													<el-tooltip effect="dark" content="" placement="top" open-delay="300">
														<div slot="content">
															<span><?php esc_html_e( 'Delete', 'bookingpress-package' ); ?></span>
														</div>
														<el-button class="bpa-btn bpa-btn--icon-without-box __danger" @click="bookingpress_delete_assigned_package_service( assigned_service_details.bookingpress_package_service_id, key )">
															<span class="material-icons-round">delete</span>
														</el-button>
													</el-tooltip>
												</div>
											</el-col>
										</el-row>
									</div>
								</el-col>
							</el-row>
						</div>
					</div>
				</el-col>
			</el-row>
			<br/><br/><br/>
		</div>
	<?php /** Service Selection */ ?>		
	</div>
</el-dialog>

<?php /* Service Assign Popup */ ?>
<el-dialog id="assign_package_service" custom-class="bpa-dialog bpa-dailog__small bpa-dialog-assign-service__is-package" title="" :visible.sync="open_assign_service_package_modal" :close-on-press-escape="close_modal_on_esc">
	<div class="bpa-dialog-heading">
		<el-row type="flex">
			<el-col :xs="24" :sm="24" :md="24" :lg="24" :xl="24">
				<h1 class="bpa-page-heading"><?php esc_html_e('Add Service', 'bookingpress-package'); ?></h1>
			</el-col>
		</el-row>
	</div>
	<div class="bpa-dialog-body">
		<el-container class="bpa-grid-list-container bpa-add-categpry-container">
			<div class="bpa-form-row">
				<el-row>
					<el-col :xs="24" :sm="24" :md="24" :lg="24" :xl="24">
						<el-form label-position="top" @submit.native.prevent>
							<div class="bpa-form-body-row">
								<el-row>
									<el-col :xs="24" :sm="24" :md="24" :lg="24" :xl="24">
										<el-form-item>
											<template #label>
												<span class="bpa-form-label"><?php esc_html_e( 'Select Service', 'bookingpress-package' ); ?></span>
											</template> 
											<el-select v-model="assign_package_service_form.assign_service_id" class="bpa-form-control" filterable collapse-tags placeholder="<?php esc_html_e( 'Select Service', 'bookingpress-package' ); ?>" :popper-append-to-body="false" popper-class="bpa-el-select--is-with-navbar" @change="bookingpress_set_service_duration($event,bookingpress_service_list)">
												<el-option-group v-for="item in bookingpress_service_list" :key="item.category_name" :label="item.category_name">
													<el-option v-for="cat_services in item.category_services" :key="cat_services.service_id" :label="cat_services.service_name" :value="cat_services.service_id"></el-option>
												</el-option-group>
											</el-select>
										</el-form-item>
									</el-col>
									<el-col :xs="24" :sm="24" :md="24" :lg="24" :xl="24">
										<el-form-item>
											<template #label>
												<span class="bpa-form-label"><?php esc_html_e( 'Duration', 'bookingpress-package' ); ?></span>
											</template>
											<el-input :disabled="true" class="bpa-form-control bpa-form-control" v-model="assign_package_service_form.service_duration" step-strictly></el-input>
										</el-form-item>
									</el-col>
									<el-col :xs="24" :sm="24" :md="24" :lg="24" :xl="24">
										<el-form-item>
											<template #label>
												<span class="bpa-form-label"><?php esc_html_e( 'No. Of Appointment', 'bookingpress-package' ); ?></span>
											</template>
											<el-input-number class="bpa-form-control bpa-form-control--number" :min="1" :max="999" v-model="assign_package_service_form.assign_service_no_of_appointments" step-strictly></el-input-number>
										</el-form-item>
									</el-col>									
								</el-row>
							</div>
						</el-form>
					</el-col>
				</el-row>
			</div>
		</el-container>
	</div>
	<div class="bpa-dialog-footer">
		<div class="bpa-hw-right-btn-group">
			<el-button class="bpa-btn bpa-btn__small bpa-btn--primary" @click="bookingpress_save_assign_package_service()"><?php esc_html_e( 'Save', 'bookingpress-package' ); ?></el-button>
			<el-button class="bpa-btn bpa-btn__small" @click="bookingpress_close_assign_package_modal()"><?php esc_html_e( 'Cancel', 'bookingpress-package' ); ?></el-button>
		</div>
	</div>
</el-dialog>
<?php do_action( 'bookingpress_manage_package_view_bottom'); ?>