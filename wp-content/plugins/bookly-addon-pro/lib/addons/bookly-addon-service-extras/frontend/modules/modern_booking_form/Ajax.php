<?php
namespace BooklyServiceExtras\Frontend\Modules\ModernBookingForm;

use Bookly\Lib as BooklyLib;
use BooklyServiceExtras\Lib;

class Ajax extends BooklyLib\Base\Ajax
{
    public static function modernBookingFormGetExtras()
    {
        $extras = array();

        foreach ( self::parameter( 'chain', array() ) as $index => $item ) {
            $service_id = $item['service_id'];
            $extras[ $index ] = array();
            $service = BooklyLib\Entities\Service::find( $service_id );
            if ( $service->getType() === BooklyLib\Entities\Service::TYPE_SIMPLE ) {
                foreach ( Lib\ProxyProviders\Local::findByServiceId( $service_id ) as $extra ) {
                    if ( $extra->getMaxQuantity() > 0 ) {
                        $extras[ $service_id ][ $extra->getId() ] = self::_prepareExtras( $extra );
                    }
                }
            } elseif ( $service->isCollaborative() || $service->isCompound() ) {
                foreach ( $service->getSubServices() as $sub_service ) {
                    foreach ( Lib\ProxyProviders\Local::findByServiceId( $sub_service->getId() ) as $extra ) {
                        if ( $extra->getMaxQuantity() > 0 ) {
                            $extras[ $service->getId() ][ $extra->getId() ] = self::_prepareExtras( $extra );
                        }
                    }
                }
            }
        }

        wp_send_json_success( $extras );
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
        if ( $extra->getAttachmentId() && $image_attributes = wp_get_attachment_image_src( $extra->getAttachmentId(), 'thumbnail' ) ) {
            $extra_result['img'] = $image_attributes[0];
        }

        return $extra_result;
    }

    /**
     * @inheritDoc
     */
    protected static function permissions()
    {
        return array( '_default' => 'anonymous' );
    }
}