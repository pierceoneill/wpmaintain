<?php
namespace BooklyPro\Lib;

use Bookly\Lib as BooklyLib;

class Updater extends BooklyLib\Base\Updater
{
    public function update_8_1()
    {
        $this->createTables( array(
            'bookly_tags' => 'CREATE TABLE IF NOT EXISTS `%s` ( `id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY, `tag` VARCHAR(255) NOT NULL DEFAULT "", `color_id` INT UNSIGNED NOT NULL DEFAULT 0, INDEX `tag` (`tag`) ) ENGINE = INNODB',
        ) );
        add_option( 'bookly_tag_colors', array( '#17a2b8', '#6c757d', '#28a745', '#dc3545', '#ffc107', '#007bff', '#343a40' ) );
    }

    public function update_7_8()
    {
        if ( ! is_array( get_option( 'bookly_pro_licensed_products' ) ) ) {
            add_option( 'bookly_pro_licensed_products', 'undefined' );
        }
        add_option( 'bookly_temporary_logs_google', '0' );
    }

    public function update_7_5()
    {
        $this->alterTables( array(
            'bookly_gift_card_types' => array(
                'ALTER TABLE `%s` ADD COLUMN `attachment_id` INT UNSIGNED DEFAULT NULL',
            ),
        ) );
    }

    public function update_7_0()
    {
        global $wpdb;

        foreach ( array( 'bookly_staff', 'bookly_services' ) as $table ) {
            $disposable_options[] = $this->disposable( __FUNCTION__ . '-' . $table . '-rename-gateways', function( $self ) use ( $wpdb, $table ) {
                $table_name = $self->getTableName( $table );
                foreach ( $wpdb->get_results( 'SELECT id, gateways FROM `' . $table_name . '` WHERE `gateways` != \'[]\'' ) as $record ) {
                    $gateways = str_replace( 'bookly-addon-', '', $record->gateways );
                    $wpdb->query( $wpdb->prepare( 'UPDATE `' . $table_name . '` SET `gateways` = %s WHERE id = %d', $gateways, $record->id ) );
                }
            } );
        }

        $disposable_options[] = $this->disposable( __FUNCTION__ . '-options', function( $self ) {
            delete_option( 'bookly_save_email_logs' );
            add_option( 'bookly_email_logs_expire', '30' );
            $bookly_pmt_order = str_replace( 'bookly-addon-', '', get_option( 'bookly_pmt_order' ) );
            update_option( 'bookly_pmt_order', $bookly_pmt_order );
        } );

        foreach ( $disposable_options as $option_name ) {
            delete_option( $option_name );
        }
    }

    public function update_6_9()
    {
        add_option( 'bookly_jitsi_server', 'https://meet.jit.si' );
        $this->alterTables( array(
            'bookly_gift_card_types' => array(
                'ALTER TABLE `%s` ADD COLUMN `link_with_buyer` INT UNSIGNED NOT NULL DEFAULT 0 AFTER `max_appointments`',
            )
        ) );
        if ( get_option( 'bookly_zoom_authentication' ) === 'jwt' ) {
            update_option( 'bookly_zoom_authentication', 'oauth' );
        }
        $this->deleteUserMeta( array( 'bookly_dismiss_zoom_jwt_notice' ) );
        delete_option( 'bookly_zoom_jwt_api_key' );
        delete_option( 'bookly_zoom_jwt_api_secret' );
    }

    public function update_6_8()
    {
        $this->alterTables( array(
            'bookly_gift_card_types' => array(
                'ALTER TABLE `%s` ADD COLUMN `info` TEXT DEFAULT NULL',
                'ALTER TABLE `%s` ADD COLUMN `wc_cart_info` TEXT DEFAULT NULL',
                'ALTER TABLE `%s` ADD COLUMN `wc_cart_info_name` VARCHAR(255) DEFAULT NULL',
                'ALTER TABLE `%s` ADD COLUMN `wc_product_id` INT UNSIGNED NOT NULL DEFAULT 0',
            )
        ) );
    }

    public function update_6_7()
    {
        $this->alterTables( array(
            'bookly_gift_cards' => array(
                'ALTER TABLE `%s` ADD `owner_id` INT UNSIGNED DEFAULT NULL AFTER `gift_card_type_id`',
                'ALTER TABLE `%s` ADD `notes` TEXT DEFAULT NULL',
                'ALTER TABLE `%s` ADD FOREIGN KEY (owner_id) REFERENCES `' . $this->getTableName( 'bookly_customers' ) . '` (id) ON UPDATE CASCADE ON DELETE SET NULL',
            ),
        ) );
    }

    public function update_6_4()
    {
        global $wpdb;

        $this->alterTables( array(
            'bookly_gift_cards' => array(
                'ALTER TABLE `%s` ADD order_id INT UNSIGNED NULL',
                'ALTER TABLE `%s` ADD FOREIGN KEY (order_id) REFERENCES `' . $this->getTableName( 'bookly_orders' ) . '` (id) ON UPDATE CASCADE ON DELETE SET NULL'
            ),
        ) );

        foreach ( $wpdb->get_results( 'SELECT id, details FROM `' . $this->getTableName( 'bookly_payments' ) . '` WHERE target = \'gift_cards\' AND details NOT LIKE \'%"type":"gift_card"%\'', ARRAY_A ) as $record ) {
            $details = json_decode( $record['details'], true );
            $details['title'] = $details['type'];
            $details['cost'] = $details['price'];
            $details['type'] = 'gift_card';
            $this->query( $wpdb->prepare( 'UPDATE `' . $this->getTableName( 'bookly_payments' ) . '` SET details = %s WHERE id = %d', json_encode( $details ), $record['id'] ) );
        }
    }

    public function update_6_3()
    {
        if ( $this->existsColumn( 'bookly_gift_card_type_staff', 'gift_card_id' ) && $this->existsColumn( 'bookly_gift_card_type_staff', 'gift_card_type_id' ) ) {
            $this->dropTableColumns( $this->getTableName( 'bookly_gift_card_type_staff' ), array( 'gift_card_id' ) );
        }
        if ( $this->existsColumn( 'bookly_gift_card_type_services', 'gift_card_id' ) && $this->existsColumn( 'bookly_gift_card_type_services', 'gift_card_type_id' ) ) {
            $this->dropTableColumns( $this->getTableName( 'bookly_gift_card_type_services' ), array( 'gift_card_id' ) );
        }
        if ( $this->existsColumn( 'bookly_gift_card_types', 'date_limit_start' ) && $this->existsColumn( 'bookly_gift_card_types', 'start_date' ) ) {
            $this->dropTableColumns( $this->getTableName( 'bookly_gift_card_type_staff' ), array( 'date_limit_start' ) );
        }
        if ( $this->existsColumn( 'bookly_gift_card_types', 'date_limit_end' ) && $this->existsColumn( 'bookly_gift_card_types', 'end_date' ) ) {
            $this->dropTableColumns( $this->getTableName( 'bookly_gift_card_type_staff' ), array( 'date_limit_end' ) );
        }
    }

    public function update_6_2()
    {
        if ( ! $this->existsColumn( 'bookly_gift_card_type_staff', 'gift_card_type_id' ) ) {
            $this->alterTables( array(
                'bookly_gift_card_type_staff' => array(
                    'ALTER TABLE `%s` CHANGE `gift_card_id` `gift_card_type_id` INT UNSIGNED NOT NULL',
                ),
                'bookly_gift_card_type_services' => array(
                    'ALTER TABLE `%s` CHANGE `gift_card_id` `gift_card_type_id` INT UNSIGNED NOT NULL',
                ),
                'bookly_gift_card_types' => array(
                    'ALTER TABLE `%s` CHANGE `date_limit_start` `start_date` DATE DEFAULT NULL',
                    'ALTER TABLE `%s` CHANGE `date_limit_end` `end_date` DATE DEFAULT NULL',
                ),
            ) );
        }
    }

    public function update_6_1()
    {
        /** @global \wpdb $wpdb */
        global $wpdb;

        $disposable_options = array();
        if ( ! $this->existsColumn( 'bookly_gift_card_types', 'title' ) ) {
            $self = $this;
            $disposable_options[] = $this->disposable( __FUNCTION__ . '-rename-table', function() use ( $self ) {
                $self->alterTables( array(
                    'bookly_gift_card_staff' => array(
                        'ALTER TABLE `%s` CHANGE `gift_card_id` `gift_card_type_id` INT UNSIGNED NOT NULL',
                    ),
                    'bookly_gift_card_services' => array(
                        'ALTER TABLE `%s` CHANGE `gift_card_id` `gift_card_type_id` INT UNSIGNED NOT NULL',
                    ),
                    'bookly_gift_cards' => array(
                        'ALTER TABLE `%s` CHANGE `date_limit_start` `start_date` DATE DEFAULT NULL',
                        'ALTER TABLE `%s` CHANGE `date_limit_end` `end_date` DATE DEFAULT NULL',
                    ),
                ) );
                $self->dropTableForeignKeys( $self->getTableName( 'bookly_payments' ), array( 'gift_card_id' ) );
                $self->renameTables( array(
                    'bookly_gift_cards' => 'bookly_gift_card_types',
                    'bookly_gift_card_staff' => 'bookly_gift_card_type_staff',
                    'bookly_gift_card_services' => 'bookly_gift_card_type_services',
                ) );
                $self->createTables( array(
                    'bookly_gift_cards' => 'CREATE TABLE IF NOT EXISTS `%s` ( `id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY, `code` VARCHAR(255) NOT NULL DEFAULT "", `gift_card_type_id` INT UNSIGNED NOT NULL, `balance` DECIMAL(10,2) NOT NULL DEFAULT 0, `customer_id` INT UNSIGNED DEFAULT NULL, `payment_id` INT UNSIGNED DEFAULT NULL, CONSTRAINT FOREIGN KEY (gift_card_type_id) REFERENCES ' . $self->getTableName( 'bookly_gift_card_types' ) . '(id) ON DELETE CASCADE ON UPDATE CASCADE, CONSTRAINT FOREIGN KEY (customer_id) REFERENCES ' . $self->getTableName( 'bookly_customers' ) . '(id) ON DELETE CASCADE ON UPDATE CASCADE, CONSTRAINT FOREIGN KEY (payment_id) REFERENCES ' . $self->getTableName( 'bookly_payments' ) . '(id) ON DELETE SET NULL ON UPDATE CASCADE ) ENGINE = INNODB',
                ) );
                $self->alterTables( array(
                    'bookly_gift_card_types' => array(
                        'ALTER TABLE `%s` ADD title VARCHAR(64) NULL AFTER id',
                        'UPDATE `%s` SET title = CONCAT( \'card - \', amount)',
                    ),
                    'bookly_payments' => array(
                        'ALTER TABLE `%s` ADD CONSTRAINT FOREIGN KEY (`gift_card_id`) REFERENCES `' . $self->getTableName( 'bookly_gift_cards' ) . '` (`id`) ON DELETE SET NULL ON UPDATE CASCADE'
                    )
                ) );
            } );

            $disposable_options[] = $this->disposable( __FUNCTION__ . '-create-cards', function() use ( $self, $wpdb ) {
                $wpdb->query( sprintf( 'INSERT INTO `%s` (gift_card_type_id, customer_id, code, balance) SELECT id, customer_id, code, balance FROM `%s`', $self->getTableName( 'bookly_gift_cards' ), $self->getTableName( 'bookly_gift_card_types' ) ) );
            } );

            $disposable_options[] = $this->disposable( __FUNCTION__ . '-delete-columns', function() use ( $self ) {
                $self->dropTableColumns( $self->getTableName( 'bookly_gift_card_types' ), array( 'balance', 'customer_id', 'code' ) );
            } );

            $disposable_options[] = $this->disposable( __FUNCTION__ . '-other', function() use ( $self ) {
                add_option( 'bookly_gift_card_partial_payment', '0' );
                delete_option( 'bookly_l10n_label_pay_cloud_gift' );
            } );
        }

        foreach ( $disposable_options as $option_name ) {
            delete_option( $option_name );
        }
    }

    public function update_5_9()
    {
        $this->alterTables( array(
            'bookly_forms' => array(
                'ALTER TABLE `%s` CHANGE `type` `type` ENUM("search-form","services-form","staff-form","cancellation-confirmation") NOT NULL DEFAULT "search-form"',
            ),
        ) );
    }

    public function update_5_8()
    {
        $this->alterTables( array(
            'bookly_gift_cards' => array(
                'ALTER TABLE `%s` MODIFY amount DECIMAL(10, 2) DEFAULT 0.00 NOT NULL',
                'ALTER TABLE `%s` MODIFY balance DECIMAL(10, 2) DEFAULT 0.00 NOT NULL',
            ),
        ) );
    }

    public function update_5_7()
    {
        add_option( 'bookly_cloud_gift_enabled', '0' );
        add_option( 'bookly_cloud_gift_default_code_mask', 'GIFT-****' );
        add_option( 'bookly_l10n_cloud_gift_error_expired', __( 'This gift card has expired', 'bookly' ) );
        add_option( 'bookly_l10n_cloud_gift_error_invalid', __( 'This gift card cannot be used for the current order', 'bookly' ) );
        add_option( 'bookly_l10n_cloud_gift_error_low_balance', __( 'Gift card balance is not enough', 'bookly' ) );
        add_option( 'bookly_l10n_cloud_gift_error_not_found', __( 'Gift card not found', 'bookly' ) );
        add_option( 'bookly_l10n_label_cloud_gift', __( 'Gift card', 'bookly' ) );
        add_option( 'bookly_l10n_label_pay_cloud_gift', __( 'I will pay by gift card', 'bookly' ) );

        $gift_cards_table = $this->getTableName( 'bookly_gift_cards' );
        $this->createTables( array(
            'bookly_gift_cards' => 'CREATE TABLE IF NOT EXISTS `%s` ( `id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY, `code` VARCHAR(255) NOT NULL DEFAULT "", `amount` DECIMAL(5,2) NOT NULL DEFAULT 0, `balance` DECIMAL(5,2) NOT NULL DEFAULT 0, `customer_id` INT UNSIGNED DEFAULT NULL, `date_limit_start` DATE DEFAULT NULL, `date_limit_end` DATE DEFAULT NULL, `min_appointments` INT UNSIGNED NOT NULL DEFAULT 1, `max_appointments` INT UNSIGNED DEFAULT NULL ) ENGINE = INNODB',
            'bookly_gift_card_services' => 'CREATE TABLE IF NOT EXISTS `%s` ( `id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY, `gift_card_id` INT UNSIGNED NOT NULL, `service_id` INT UNSIGNED NOT NULL, CONSTRAINT FOREIGN KEY (gift_card_id) REFERENCES  ' . $gift_cards_table . '(id) ON DELETE CASCADE ON UPDATE CASCADE, CONSTRAINT FOREIGN KEY (service_id) REFERENCES ' . $this->getTableName( 'bookly_services' ) . '(id) ON DELETE CASCADE ON UPDATE CASCADE ) ENGINE = INNODB',
            'bookly_gift_card_staff' => 'CREATE TABLE IF NOT EXISTS `%s` ( `id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY, `gift_card_id` INT UNSIGNED NOT NULL, `staff_id` INT UNSIGNED NOT NULL, CONSTRAINT FOREIGN KEY (gift_card_id) REFERENCES ' . $gift_cards_table . '(id) ON DELETE CASCADE ON UPDATE CASCADE, CONSTRAINT FOREIGN KEY (staff_id) REFERENCES ' . $this->getTableName( 'bookly_staff' ) . '(id) ON DELETE CASCADE ON UPDATE CASCADE ) ENGINE = INNODB',
        ) );

        $this->alterTables( array(
            'bookly_payments' => array(
                'ALTER TABLE `%s` ADD CONSTRAINT FOREIGN KEY (gift_card_id) REFERENCES ' . $gift_cards_table . '(id) ON DELETE SET NULL ON UPDATE CASCADE',
            ),
        ) );

        $this->addNotifications( array(
            array(
                'gateway' => 'email',
                'type' => 'new_gift_card',
                'name' => __( 'Notification to customer about purchased gift card', 'bookly' ),
                'subject' => __( 'Your gift card at', 'bookly' ) . ' {company_name}',
                'message' => __( "Dear {client_name},\n\nThis is a confirmation that the following Gift Card {gift_card} has been purchased at {company_name}.\n\nWe are waiting for you at {company_address}.", 'bookly' ) . "\n\n\n\n" . __( 'Thank you for choosing our company.', 'bookly' ) . "\n\n{company_name}\n{company_phone}\n{company_website}",
                'active' => 1,
                'to_customer' => 1,
                'settings' => '{"status":"any","option":2,"services":{"any":"any","ids":[]},"offset_hours":1,"perform":"before","at_hour":18,"before_at_hour":18,"offset_before_hours":-24,"offset_bidirectional_hours":-24}',
            ),
        ) );
    }

    public function update_5_6()
    {
        $this->addL10nOptions( array( 'bookly_l10n_label_pay_cloud_square' => __( 'I will pay now with Credit Card', 'bookly' ), ) );

        $option = get_option( 'bookly_cart_show_columns' );
        $bookly_cart_show_columns = array(
            'service' => array( 'show' => 1 ),
            'date' => array( 'show' => 1 ),
            'time' => array( 'show' => 1 ),
            'employee' => array( 'show' => 1 ),
            'price' => array( 'show' => 1 ),
            'deposit' => array( 'show' => 1 ),
            'tax' => array( 'show' => 0 ),
        );
        if ( ! is_array( $option ) ) {
            update_option( 'bookly_cart_show_columns', $bookly_cart_show_columns );
        }
    }

    public function update_5_4()
    {
        $this->alterTables( array(
            'bookly_staff_categories' => array(
                'ALTER TABLE `%s` ADD COLUMN `info` TEXT DEFAULT NULL AFTER `name`',
                'ALTER TABLE `%s` ADD COLUMN `attachment_id` INT UNSIGNED DEFAULT NULL AFTER `name`',
            ),
            'bookly_forms' => array(
                'ALTER TABLE `%s` CHANGE `type` `type` ENUM("search-form","services-form","cancellation-confirmation") NOT NULL DEFAULT "search-form"',
            ),
        ) );
    }

    public function update_5_3()
    {
        global $wpdb;

        $charset_collate = $wpdb->has_cap( 'collation' )
            ? $wpdb->get_charset_collate()
            : 'DEFAULT CHARACTER SET = utf8 COLLATE = utf8_general_ci';

        $wpdb->query(
            'CREATE TABLE IF NOT EXISTS `' . $this->getTableName( 'bookly_forms' ) . '` (
                `id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
                `type` ENUM("search-form","services-form") NOT NULL DEFAULT "search-form",
                `name` VARCHAR(255) NOT NULL,
                `token` VARCHAR(255) NOT NULL,
                `settings` TEXT DEFAULT NULL,
                `custom_css` TEXT DEFAULT NULL,
                `created_at` DATETIME NOT NULL
            ) ENGINE = INNODB
            ' . $charset_collate
        );
    }

    public function update_4_9()
    {
        add_option( 'bookly_bbb_server_end_point', '' );
        add_option( 'bookly_bbb_shared_secret', '' );
    }

    public function update_4_8()
    {
        add_option( 'bookly_wc_create_order_via_backend', '0' );
        add_option( 'bookly_wc_default_order_status', 'wc-pending' );
        $this->addL10nOptions( array( 'bookly_l10n_qr_code_description' => "{service_name}\n{staff_name}" ) );
    }

    public function update_4_7()
    {
        add_option( 'bookly_auto_change_status', '0' );
    }

    public function update_4_6()
    {
        add_option( 'bookly_app_show_appointment_qr', '0' );
        add_option( 'bookly_wc_create_order_at_zero_cost', '1' );
    }

    public function update_4_5()
    {
        add_option( 'bookly_app_show_tips', '0' );
        $this->addL10nOptions( array(
            'bookly_l10n_label_tips' => __( 'Tips', 'bookly' ),
            'bookly_l10n_button_apply_tips' => __( 'Apply', 'bookly' ),
            'bookly_l10n_button_applied_tips' => __( 'Applied', 'bookly' ),
            'bookly_l10n_tips_error' => __( 'Incorrect value', 'bookly' ),
        ) );
    }

    public function update_4_4()
    {
        $this->addL10nOptions( array(
            'bookly_l10n_info_payment_step_without_intersected_gateways' => __( 'No payment methods available. Please contact service provider.', 'bookly' ),
        ) );
        add_option( 'bookly_cal_frontend_enabled', '0' );
    }

    public function update_4_3()
    {
        $order = explode( ',', get_option( 'bookly_pmt_order' ) );
        if ( $order ) {
            $pmt_order = array();
            $gateways = array(
                'stripe' => 'bookly-addon-stripe',
                'authorize_net' => 'bookly-addon-authorize-net',
                '2checkout' => 'bookly-addon-2checkout',
                'payu_biz' => 'bookly-addon-payu-biz',
                'payu_latam' => 'bookly-addon-payu-latam',
                'payson' => 'bookly-addon-payson',
                'mollie' => 'bookly-addon-mollie',
            );
            foreach ( $order as $gateway ) {
                $pmt_order[] = array_key_exists( $gateway, $gateways )
                    ? $gateways[ $gateway ]
                    : $gateway;
            }
            update_option( 'bookly_pmt_order', implode( ',', $pmt_order ) );
        }
    }

    public function update_4_2()
    {
        global $wpdb;

        $charset_collate = $wpdb->has_cap( 'collation' )
            ? $wpdb->get_charset_collate()
            : 'DEFAULT CHARACTER SET = utf8 COLLATE = utf8_general_ci';

        $wpdb->query(
            'CREATE TABLE IF NOT EXISTS `' . $this->getTableName( 'bookly_email_log' ) . '` (
                `id`         INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
                `to`         VARCHAR(255) NOT NULL,
                `subject`    VARCHAR(255) NOT NULL,
                `body`       TEXT NOT NULL,
                `headers`    TEXT NOT NULL,
                `attach`     TEXT NOT NULL,
                `type`       VARCHAR(255) NOT NULL DEFAULT "",
                `created_at` DATETIME NOT NULL
             ) ENGINE = INNODB
              ' . $charset_collate
        );

        add_option( 'bookly_save_email_logs', '1' );
    }

    public function update_4_1()
    {
        add_option( 'bookly_appointments_main_value', 'provider' );
        add_option( 'bookly_appointments_displayed_time_slots', 'all' );
    }

    public function update_4_0()
    {
        $notifications[] = array(
            'gateway' => 'email',
            'type' => 'staff_new_wp_user',
            'name' => __( 'New staff member\'s WordPress user login details', 'bookly' ),
            'subject' => __( 'New staff member', 'bookly' ),
            'message' => __( "Hello.\n\nAn account was created for you at {site_address}\n\nYour user details:\nuser: {new_username}\npassword: {new_password}\n\nThanks.", 'bookly' ),
            'active' => 1,
            'to_staff' => 1,
            'settings' => '[]',
        );
        $notifications[] = array(
            'gateway' => 'sms',
            'type' => 'staff_new_wp_user',
            'name' => __( 'New staff member\'s WordPress user login details', 'bookly' ),
            'message' => __( "Hello.\n\nAn account was created for you at {site_address}\n\nYour user details:\nuser: {new_username}\npassword: {new_password}\n\nThanks.", 'bookly' ),
            'active' => 1,
            'to_staff' => 1,
            'settings' => '[]',
        );

        $this->addNotifications( $notifications );
        add_option( 'bookly_staff_new_account_role', 'subscriber' );
        add_option( 'bookly_appointments_time_delimiter', '0' );
    }

    public function update_3_6()
    {
        $bookly_gc_event_appointment_info = get_option( 'bookly_gc_event_appointment_info' );
        if ( $bookly_gc_event_appointment_info !== false ) {
            if ( $bookly_gc_event_appointment_info !== '' ) {
                $bookly_gc_event_appointment_info .= PHP_EOL;
            }
            $bookly_gc_event_client_info = get_option( 'bookly_gc_event_client_info', '' );
            $replace = array(
                '{appointment_notes}' => '{participant.appointment_notes}',
                '{client_email}' => '{participant.client_email}',
                '{client_first_name}' => '{participant.client_first_name}',
                '{client_last_name}' => '{participant.client_last_name}',
                '{client_name}' => '{participant.client_name}',
                '{client_phone}' => '{participant.client_phone}',
                '{payment_status}' => '{participant.payment_status}',
                '{payment_type}' => '{participant.payment_type}',
                '{status}' => '{participant.status}',
                '{total_price}' => '{participant.total_price}',
                '{client_address}' => '{participant.client_address}',
                '{custom_fields}' => '{participant.custom_fields}',
                '{number_of_persons}' => '{participant.number_of_persons}',
                '{extras}' => '{participant.extras}',
                '{extras_total_price}' => '{participant.extras_total_price}',
            );
            $bookly_gc_event_client_info = '{#each participants as participant}' . PHP_EOL . strtr( $bookly_gc_event_client_info, $replace ) . PHP_EOL . '{/each}';

            add_option( 'bookly_gc_event_description', $bookly_gc_event_appointment_info . $bookly_gc_event_client_info );
            delete_option( 'bookly_gc_event_appointment_info' );
            delete_option( 'bookly_gc_event_client_info' );
        }

        add_option( 'bookly_zoom_authentication', 'jwt' );
        add_option( 'bookly_zoom_oauth_client_id', '' );
        add_option( 'bookly_zoom_oauth_client_secret', '' );
        add_option( 'bookly_zoom_oauth_token', '' );
    }

    public function update_2_9()
    {
        add_option( 'bookly_cst_limit_statuses', array( 'waitlisted' ) );
    }

    public function update_2_5()
    {
        add_option( 'bookly_zoom_jwt_api_key', '' );
        add_option( 'bookly_zoom_jwt_api_secret', '' );
    }

    public function update_2_2()
    {
        $address_show_fields = (array) get_option( 'bookly_cst_address_show_fields' );
        $fields = array();
        foreach ( $address_show_fields as $field_name => $attributes ) {
            if ( (bool) $attributes['show'] ) {
                $fields[] = '{' . $field_name . '}';
            }
        }
        $this->addL10nOptions( array(
            'bookly_l10n_cst_address_template' => implode( ', ', $fields ),
        ) );
    }

    public function update_2_1()
    {
        // Create WP role for bookly supervisor
        $capabilities = array();
        if ( $subscriber = get_role( 'subscriber' ) ) {
            $capabilities = $subscriber->capabilities;
        }

        // Fix subscribers access to dashboard with woocommerce.
        $capabilities['view_admin_dashboard'] = true;

        $capabilities['manage_bookly'] = true;

        add_role( 'bookly_administrator', 'Bookly Administrator', $capabilities );

        $this->addL10nOptions( array(
            'bookly_l10n_info_payment_step_with_100percents_off_price' => __( 'You are not required to pay for the booked services, click Next to complete the booking process.', 'bookly' ),
        ) );

        update_option( 'bookly_pr_data', array(
            'SW1wb3J0YW50ITxici8+SXQgbG9va3MgbGlrZSB5b3UgYXJlIHVzaW5nIGFuIGlsbGVnYWwgY29weSBvZiBCb29rbHkgUHJvLiBBbmQgaXQgbWF5IGNvbnRhaW4gYSBtYWxpY2lvdXMgY29kZSwgYSB0cm9qYW4gb3IgYSBiYWNrZG9vci4=',
            'Q29uc2lkZXIgc3dpdGNoaW5nIHRvIHRoZSBsZWdhbCBjb3B5IG9mIEJvb2tseSBQcm8gdGhhdCBpbmNsdWRlcyBhbGwgZmVhdHVyZXMsIGxpZmV0aW1lIGZyZWUgdXBkYXRlcywgYW5kIDI0Lzcgc3VwcG9ydC4=',
            'WW91IGNhbiBidXkgYSBsZWdhbCBjb3B5IG9uIG91ciB3ZWJzaXRlIDxhIGhyZWY9Imh0dHBzOi8vd3d3LmJvb2tpbmctd3AtcGx1Z2luLmNvbSIgdGFyZ2V0PSJfYmxhbmsiPnd3dy5ib29raW5nLXdwLXBsdWdpbi5jb208L2E+LCBvciBjb250YWN0IHVzIGF0IDxhIGhyZWY9Im1haWx0bzpzdXBwb3J0QGJvb2tseS5pbmZvIj5zdXBwb3J0QGJvb2tseS5pbmZvPC9hPiBmb3IgYW55IGFzc2lzdGFuY2Uu',
        ) );
    }

    public function update_2_0()
    {
        if ( get_option( 'bookly_paypal_timeout', 'missing' ) === 'missing' ) {
            add_option( 'bookly_paypal_timeout', '0' );
        }
    }

    public function update_1_8()
    {
        $this->upgradeCharsetCollate( array(
            'bookly_staff_categories',
            'bookly_staff_preference_orders',
        ) );
    }

    public function update_1_4()
    {
        /** @global \wpdb $wpdb */
        global $wpdb;

        $self = $this;
        $notifications_table = $this->getTableName( 'bookly_notifications' );
        $notifications = array(
            'client_new_wp_user' => array( 'type' => 'customer_new_wp_user', 'name' => __( 'New customer\'s WordPress user login details', 'bookly' ) ),
            'customer_birthday' => array( 'type' => 'customer_birthday', 'name' => __( 'Customer\'s birthday', 'bookly' ) ),
            'client_approved_appointment_cart' => array( 'type' => 'new_booking_combined', 'name' => __( 'Notification to customer about approved appointments', 'bookly' ) ),
            'client_pending_appointment_cart' => array( 'type' => 'new_booking_combined', 'name' => __( 'Notification to customer about pending appointments', 'bookly' ) ),
        );

        // Changes in schema
        $disposable_options[] = $this->disposable( __FUNCTION__ . '-1', function() use ( $self, $wpdb, $notifications_table, $notifications ) {
            if ( ! $self->existsColumn( 'bookly_notifications', 'name' ) ) {
                $self->alterTables( array(
                    'bookly_notifications' => array(
                        'ALTER TABLE `%s` ADD COLUMN `name` VARCHAR(255) NOT NULL DEFAULT "" AFTER `active`',
                    ),
                ) );
            }

            $update_name = 'UPDATE `' . $notifications_table . '` SET `name` = %s WHERE `type` = %s AND name = \'\'';
            foreach ( $notifications as $type => $value ) {
                $wpdb->query( $wpdb->prepare( $update_name, $value['name'], $type ) );

                switch ( substr( $type, 0, 6 ) ) {
                    case 'staff_':
                        $wpdb->query( sprintf( 'UPDATE `%s` SET `to_staff` = 1 WHERE `type` = "%s"', $notifications_table, $type ) );
                        break;
                    case 'client':
                        $wpdb->query( sprintf( 'UPDATE `%s` SET `to_customer` = 1 WHERE `type` = "%s"', $notifications_table, $type ) );
                        break;
                }
            }
        } );

        // WPML
        $disposable_options[] = $this->disposable( __FUNCTION__ . '-2', function() use ( $self, $wpdb, $notifications_table, $notifications ) {
            $records = $wpdb->get_results( $wpdb->prepare( 'SELECT id, `type`, `gateway` FROM `' . $notifications_table . '` WHERE COALESCE( `settings`, \'[]\' ) = \'[]\' AND `type` IN (' . implode( ', ', array_fill( 0, count( $notifications ), '%s' ) ) . ')', array_keys( $notifications ) ), ARRAY_A );
            $strings = array();
            foreach ( $records as $record ) {
                $type = $record['type'];
                if ( isset( $notifications[ $type ]['type'] ) && $type != $notifications[ $type ]['type'] ) {
                    $key = sprintf( '%s_%s_%d', $record['gateway'], $type, $record['id'] );
                    $value = sprintf( '%s_%s_%d', $record['gateway'], $notifications[ $type ]['type'], $record['id'] );
                    $strings[ $key ] = $value;
                    if ( $record['gateway'] == 'email' ) {
                        $strings[ $key . '_subject' ] = $value . '_subject';
                    }
                }
            }
            $self->renameL10nStrings( $strings, false );
        } );

        // Add settings for notifications
        $disposable_options[] = $this->disposable( __FUNCTION__ . '-3', function() use ( $wpdb, $notifications_table, $notifications ) {
            $update_settings = 'UPDATE `' . $notifications_table . '` SET `type` = %s, `settings` = %s, `active` = %d WHERE id = %d';
            $default_settings = '{"status":"any","option":2,"services":{"any":"any","ids":[]},"offset_hours":2,"perform":"before","at_hour":9,"before_at_hour":18,"offset_before_hours":-24,"offset_bidirectional_hours":0}';
            $records = $wpdb->get_results( $wpdb->prepare( 'SELECT id, `type`, `gateway`, `message`, `active`, `subject` FROM `' . $notifications_table . '` WHERE COALESCE( `settings`, \'[]\' ) = \'[]\' AND `type` IN (' . implode( ', ', array_fill( 0, count( $notifications ), '%s' ) ) . ')', array_keys( $notifications ) ), ARRAY_A );
            foreach ( $records as $record ) {
                $new_type = $notifications[ $record['type'] ]['type'];
                switch ( $record['type'] ) {
                    case 'client_approved_appointment_cart':
                    case 'client_pending_appointment_cart':
                        $new_active = get_option( 'bookly_cst_combined_notifications' ) ? $record['active'] : 0;
                        break;
                    default:
                        $new_active = $record['active'];
                }

                $wpdb->query( $wpdb->prepare( $update_settings, $new_type, $default_settings, $new_active, $record['id'] ) );
            }
        } );

        if ( get_option( 'bookly_cst_combined_notifications' ) == 0 ) {
            // Deactivate combine notifications
            $wpdb->query( 'UPDATE `' . $notifications_table . '` SET `active` = 0 WHERE `type` = \'new_booking_combined\'' );
        }
        delete_option( 'bookly_cst_combined_notifications' );
        foreach ( $disposable_options as $option_name ) {
            delete_option( $option_name );
        }
    }

    public function update_1_1()
    {
        global $wpdb;

        $wpdb->query(
            'CREATE TABLE IF NOT EXISTS `' . $this->getTableName( 'bookly_staff_categories' ) . '` (
                `id`       INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
                `name`     VARCHAR(255) NOT NULL,
                `position` INT NOT NULL DEFAULT 9999
             ) ENGINE = INNODB
             DEFAULT CHARACTER SET = utf8
             COLLATE = utf8_general_ci'
        );

        $wpdb->query(
            'ALTER TABLE `' . $this->getTableName( 'bookly_staff' ) . '`
             ADD CONSTRAINT
                FOREIGN KEY (category_id)
                REFERENCES ' . $this->getTableName( 'bookly_staff_categories' ) . '(id)
                ON DELETE SET NULL
                ON UPDATE CASCADE'
        );

        $bookly_gc_event_client_info = __( 'Name', 'bookly' ) . ': {client_name}' . PHP_EOL . __( 'Email', 'bookly' ) . ': {client_email}' . PHP_EOL . __( 'Phone', 'bookly' ) . ': {client_phone}' . PHP_EOL . '{custom_fields}';
        if ( BooklyLib\Config::serviceExtrasActive() ) {
            $bookly_gc_event_client_info .= PHP_EOL . __( 'Extras', 'bookly' ) . ': {extras}' . PHP_EOL;
        }

        add_option( 'bookly_gc_event_client_info', $bookly_gc_event_client_info );
        add_option( 'bookly_gc_event_appointment_info', '' );
        delete_option( 'bookly_grace_hide_admin_notice_time' );
        $this->renameUserMeta( array( 'show_purchase_reminder' => 'bookly_show_purchase_reminder' ) );

        // Create WP role for bookly supervisor
        $capabilities = array();
        if ( $subscriber = get_role( 'subscriber' ) ) {
            $capabilities = $subscriber->capabilities;
        }
        $capabilities['manage_bookly_appointments'] = true;
        add_role( 'bookly_supervisor', 'Bookly Supervisor', $capabilities );
    }
}