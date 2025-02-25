<?php

global $BookingPress, $bookingpress_package_version, $wpdb;
$bookingpress_old_package_version = get_option('bookingpress_package_version', true);



$bookingpress_package_new_version = '1.9';
update_option('bookingpress_package_version', $bookingpress_package_new_version);
update_option('bookingpress_package_version_updated_date_' . $bookingpress_package_new_version, current_time('mysql'));