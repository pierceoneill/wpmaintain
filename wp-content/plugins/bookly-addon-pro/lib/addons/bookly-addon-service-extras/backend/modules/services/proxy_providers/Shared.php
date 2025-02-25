<?php
namespace BooklyServiceExtras\Backend\Modules\Services\ProxyProviders;

use Bookly\Lib as BooklyLib;
use BooklyServiceExtras\Lib;
use Bookly\Backend\Modules\Services\Proxy;

class Shared extends Proxy\Shared
{
    /**
     * @inheritDoc
     */
    public static function duplicateService( $source_id, $target_id )
    {
        foreach ( Lib\Entities\ServiceExtra::query()->where( 'service_id', $source_id )->fetchArray() as $extra ) {
            $new_extra = new Lib\Entities\ServiceExtra( $extra );
            $new_extra->setId( null )->setServiceId( $target_id )->save();
        }
    }
}