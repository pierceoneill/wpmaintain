<el-tab-pane class="bpa-tabs--v_ls__tab-item--pane-body">
    <span slot="label">	
        <?php esc_html_e('Package Report', 'bookingpress-package'); ?>
    </span>
    <div class="bpa-general-settings-tabs--pb__card">
        <div class="bpa-rb-tab-content__body">
            <h3 class="bpa-page-heading"><?php esc_html_e( 'Package Report', 'bookingpress-package' ); ?></h3>
            <div class="bpa-rb-chart-item-wrapper">
                <div class="bpa-ciw__filter-row">
                    <el-row type="flex">
                        <el-col :xs="main_col_width.xs" :sm="main_col_width.sm" :md="main_col_width.md" :lg="main_col_width.lg" :xl="main_col_width.xl" class="bpa-ciw-fr--left">
                            <el-row type="flex" :gutter="24">

                                <el-col :xs="service_col_width.xs" :sm="service_col_width.sm" :md="service_col_width.md" :lg="service_col_width.lg" :xl="service_col_width.xl">
                                    <el-select class="bpa-form-control" v-model="appointment_search_service" multiple filterable collapse-tags 
                                        placeholder="<?php esc_html_e( 'Select Service', 'bookingpress-package' ); ?>"
                                        :popper-append-to-body="false" popper-class="bpa-el-select--is-with-navbar" @change="change_appointment_report_filter">                                        
                                        <el-option v-for="package_data in packages_filter_data" :label="package_data.bookingpress_package_name" :value="package_data.bookingpress_package_id"></el-option>                                        
                                    </el-select>
                                </el-col>

                            </el-row>
                        </el-col>
                        <el-col :xs="12" :sm="12" :md="12" :lg="12" :xl="12" class="bpa-ciw-fr--right">
                            <el-date-picker ref="bookingpress_custom_filter_rangepicker" v-model="custom_filter_val" class="bpa-form-control bpa-form-control--date-range-picker" format="<?php echo esc_html($bookingpress_common_date_format); ?>" type="daterange" start-placeholder="<?php esc_html_e('Start date', 'bookingpress-package'); ?>" end-placeholder="<?php esc_html_e( 'End Date', 'bookingpress-package'); ?>" :popper-append-to-body="false" popper-class="bpa-el-select--is-with-navbar bpa-date-range-picker__is-filter-enabled" range-separator="-" value-format="yyyy-MM-dd" :picker-options="bookingpress_picker_options" @change="select_appointment_report_filter('custom')"></el-date-picker>
                        </el-col>
                    </el-row>
                </div>
                <div class="bpa-ciw__chart-body">
                    <el-row type="flex" :gutter="40">
                        <el-col :xs="12" :sm="12" :md="12" :lg="16" :xl="18">
                            <div class="bpa-cb__content">
                                <canvas id="bookingpress_appointments_charts"></canvas>
                            </div>
                        </el-col>
                        <el-col :xs="12" :sm="12" :md="12" :lg="08" :xl="06">
                            <div class="bpa-cb__chart-stats">
                                <h4 class="bpa-cb-stats-title"><?php esc_html_e('Quick Stats', 'bookingpress-package'); ?></h4>
                                <div class="bpa-cb-stats-item --bpa-is-secondary">
                                    <div class="bpa-stats-item-label"><span class="bpa-sil__cirlce"></span><?php echo esc_html($pending_label); ?></div>
                                    <div class="bpa-stats-item-val">{{ appointment_stat[2] }}</div>
                                </div>
                                <div class="bpa-cb-stats-item --bpa-is-blue">
                                    <div class="bpa-stats-item-label"><span class="bpa-sil__cirlce"></span><?php echo esc_html($approved_label); ?></div>
                                    <div class="bpa-stats-item-val">{{ appointment_stat[1] }}</div>
                                </div>
                                <div class="bpa-cb-stats-item">
                                    <div class="bpa-stats-item-label"><span class="bpa-sil__cirlce"></span><?php echo esc_html($cancelled_label); ?></div>
                                    <div class="bpa-stats-item-val">{{ appointment_stat[3] }}</div>
                                </div>
                                <div class="bpa-cb-stats-item --bpa-is-danger">
                                    <div class="bpa-stats-item-label"><span class="bpa-sil__cirlce"></span><?php echo esc_html($rejected_label); ?></div>
                                    <div class="bpa-stats-item-val">{{ appointment_stat[4] }}</div>
                                </div>
                                <div class="bpa-cb-stats-item --bpa-is-main-green">
                                    <div class="bpa-stats-item-label"><span class="bpa-sil__cirlce"></span><?php echo esc_html($completed_label); ?></div>
                                    <div class="bpa-stats-item-val">{{ appointment_stat[6] }}</div>
                                </div>
                                <div class="bpa-cb-stats-item --bpa-is-brown">
                                    <div class="bpa-stats-item-label"><span class="bpa-sil__cirlce"></span><?php echo esc_html($noshow_label); ?></div>
                                    <div class="bpa-stats-item-val">{{ appointment_stat[5] }}</div>
                                </div>
                            </div>
                        </el-col>
                    </el-row>
                </div>
                <div class="bpa-ciw__grid-listing">
                    <div class="bpa-ciw-gl__item">
                        <h3 class="bpa-page-heading"><?php esc_html_e( 'Packages Summary', 'bookingpress-package' ); ?></h3>
                        

                                       
                    </div>
                </div>
            </div>
        </div>
    </div>
</el-tab-pane>