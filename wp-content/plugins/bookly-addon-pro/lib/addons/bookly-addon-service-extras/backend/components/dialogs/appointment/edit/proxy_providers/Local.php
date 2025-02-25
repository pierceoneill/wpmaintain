<?php
namespace BooklyServiceExtras\Backend\Components\Dialogs\Appointment\Edit\ProxyProviders;

use Bookly\Lib as BooklyLib;
use Bookly\Backend\Components\Dialogs\Appointment\Edit\Proxy\Extras as ExtrasProxy;
use BooklyServiceExtras\Lib;

class Local extends ExtrasProxy
{
    /**
     * @inerhitDoc
     */
    public static function getMaxDurationExtras( array $extras )
    {
        $max_duration = 0;
        $result = array();
        foreach ( $extras as $customer_extras ) {
            if ( ( $duration = Lib\ProxyProviders\Local::getTotalDuration( $customer_extras ) ) > $max_duration ) {
                $result = $customer_extras;
                $max_duration = $duration;
            }
        }

        return $result;
    }
}