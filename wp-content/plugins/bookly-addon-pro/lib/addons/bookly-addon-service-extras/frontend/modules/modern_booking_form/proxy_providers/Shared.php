<?php
namespace BooklyServiceExtras\Frontend\Modules\ModernBookingForm\ProxyProviders;

use Bookly\Lib as BooklyLib;
use Bookly\Frontend\Modules\ModernBookingForm\Proxy;
use BooklyPro\Frontend\Modules\ModernBookingForm\Lib\Request;
use BooklyServiceExtras\Lib\Entities\ServiceExtra;
use BooklyServiceExtras\Lib;

class Shared extends Proxy\Shared
{
    /**
     * @inerhitDoc
     */
    public static function prepareFormOptions( array $bookly_options )
    {
        $bookly_options['extrasSettings'] = array(
            'extrasAfterTime' => (int) get_option( 'bookly_service_extras_after_step_time' ),
            'multiplyNop' => (int) get_option( 'bookly_service_extras_multiply_nop' ),
            'show' => get_option( 'bookly_service_extras_show' ),
        );
        $complex_services = array();
        $query = BooklyLib\Entities\Service::query( 's' )
            ->select( 'sub.service_id, sub.sub_service_id' )
            ->leftJoin( 'SubService', 'sub', 'sub.service_id = s.id' )
            ->whereIn( 's.type', array( BooklyLib\Entities\Service::TYPE_COLLABORATIVE, BooklyLib\Entities\Service::TYPE_COMPOUND ) );
        foreach ( $query->fetchArray() as $service ) {
            $complex_services[ $service['service_id'] ][] = $service['sub_service_id'];
        }
        $extras = array();
        foreach ( Lib\ProxyProviders\Local::findAll() as $extra ) {
            if ( $extra->getMaxQuantity() > 0 ) {
                $_extra = self::_prepareExtras( $extra );
                $extras[ $extra->getServiceId() ][ $extra->getId() ] = $_extra;
                foreach ( $complex_services as $service_id => $sub_services ) {
                    foreach ( $sub_services as $sub_service_id ) {
                        if ( $sub_service_id === $extra->getServiceId() ) {
                            $extras[ $service_id ][ $extra->getId() ] = $_extra;
                        }
                    }
                }
            }
        }
        $bookly_options['extras'] = $extras;

        return $bookly_options;
    }

    /**
     * @inerhitDoc
     */
    public static function prepareAppearance( array $bookly_options )
    {
        $bookly_options['l10n']['summary'] = __( 'Summary', 'bookly' );
        $bookly_options['show_extras_price'] = true;
        $bookly_options['show_extras_summary'] = true;

        return $bookly_options;
    }

    /**
     * @inerhitDoc
     */
    public static function validate( Request $request )
    {
        foreach ( $request->getUserData()->cart->getItems() as $item ) {
            $extras = array();
            foreach ( $item->getExtras() as $id => $quantity ) {
                $_extra = ServiceExtra::find( $id );
                if ( $_extra && $_extra->getMaxQuantity() > 0 ) {
                    $extras[ $id ] = max( $_extra->getMinQuantity(), min( $_extra->getMaxQuantity(), $quantity ), 0 );
                }
            }
            $item->setExtras( $extras );
        }
    }

    private static function _prepareExtras( Lib\Entities\ServiceExtra $extra )
    {
        $extra_result = array(
            'id' => $extra->getId(),
            'price' => $extra->getPrice(),
            'min_quantity' => max( $extra->getMinQuantity(), 0 ),
            'max_quantity' => $extra->getMaxQuantity(),
            'title' => $extra->getTranslatedTitle(),
            'print_price' => BooklyLib\Utils\Price::format( $extra->getPrice() ),
            'print_duration' => BooklyLib\Utils\DateTime::secondsToInterval( $extra->getDuration() ),
        );
        if ($img = BooklyLib\Utils\Common::getAttachmentUrl($extra->getAttachmentId(),'thumbnail')) {
            $extra_result['img'] = $img;
        }

        return $extra_result;
    }
}