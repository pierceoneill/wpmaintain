<?php
namespace BooklyServiceExtras\Backend\Modules\Notifications\ProxyProviders;

use Bookly\Backend\Modules\Notifications\Proxy;

class Shared extends Proxy\Shared
{
    /**
     * @inheritDoc
     */
    public static function prepareNotificationCodes( array $codes, $type )
    {
        $codes['customer_appointment']['extras']             = array( 'description' => __( 'Extras titles', 'bookly' ) );
        $codes['customer_appointment']['extras_total_price'] = array( 'description' => __( 'Extras total price', 'bookly' ) );

        return $codes;
    }
}