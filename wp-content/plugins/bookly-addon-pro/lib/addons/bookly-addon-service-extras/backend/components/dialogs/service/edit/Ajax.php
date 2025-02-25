<?php
namespace BooklyServiceExtras\Backend\Components\Dialogs\Service\Edit;

use Bookly\Lib as BooklyLib;
use BooklyServiceExtras\Lib;
use BooklyServiceExtras\Lib\Entities;

class Ajax extends BooklyLib\Base\Ajax
{
    /**
     * Update extras position.
     */
    public static function updateExtraPosition()
    {
        foreach ( self::parameter( 'extras', array() ) as $position => $extra_id ) {
            Entities\ServiceExtra::query()
                ->update()
                ->set( 'position', $position )
                ->where( 'id', $extra_id )
                ->whereNot( 'position', $position )
                ->execute();
        }

        wp_send_json_success();
    }
}