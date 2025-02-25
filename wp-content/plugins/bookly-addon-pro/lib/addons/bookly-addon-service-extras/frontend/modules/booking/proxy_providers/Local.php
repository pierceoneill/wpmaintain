<?php
namespace BooklyServiceExtras\Frontend\Modules\Booking\ProxyProviders;

use Bookly\Lib as BooklyLib;
use Bookly\Frontend\Modules\Booking\Proxy;

class Local extends Proxy\ServiceExtras
{
    /**
     * @inheritDoc
     */
    public static function getStepHtml( BooklyLib\UserBookingData $userData, $show_cart_btn, $info_text, $progress_tracker, $show_back_btn )
    {
        $chain = array();
        $chain_price = 0;
        foreach ( $userData->chain->getItems() as $pos => $chain_item ) {
            $extras  = array();
            $service = $chain_item->getService();
            $item_price = 0;
            if ( $service->withSubServices() ) {
                // Price.
                $item_price = $service->getPrice();
                // Extras.
                $sub_services  = $service->getSubServices();
                $processed_ids = array();
                foreach ( $sub_services as $sub_service ) {
                    $service_id = $sub_service->getId();
                    if ( ! in_array( $service_id, $processed_ids ) ) {
                        $extras = array_merge( $extras, (array) BooklyLib\Proxy\ServiceExtras::findByServiceId( $service_id ) );
                        $processed_ids[] = $service_id;
                    }
                }
            } else {
                $service_id = $service->getId();
                // Price.
                if ( count( $chain_item->getStaffIds() ) === 1 || $userData->getSlots() ) {
                    $slots = $userData->getSlots();
                    if ( $slots ) {
                        $staff_id = $slots[ $pos ][1];
                    } else {
                        $staff_id = current( $chain_item->getStaffIds() );
                    }
                    $staff_service = BooklyLib\Entities\StaffService::query()
                        ->select( 'price' )
                        ->where( 'service_id', $service_id )
                        ->where( 'staff_id', $staff_id )
                        ->where( 'location_id', BooklyLib\Proxy\Locations::prepareStaffLocationId( $chain_item->getLocationId(), $staff_id ) ?: null )
                        ->fetchRow();
                    if ( \BooklyServiceExtras\Lib\ProxyProviders\Local::considerDuration() ) {
                        $item_price = $staff_service['price'];
                    } else {
                        list( , , $datetime ) = $slots[ $pos ];
                        $item_price = BooklyLib\Proxy\SpecialHours::adjustPrice( $staff_service['price'], $staff_id, $service->getId(), $chain_item->getLocationId(), substr( $datetime, 11 ), date( 'w', strtotime( $datetime ) ) + 1 ) ;
                    }
                } else {
                    $item_price = $service->getPrice();
                }
                // Extras.
                $extras = (array) BooklyLib\Proxy\ServiceExtras::findByServiceId( $service_id );
            }

            $item_price *= $chain_item->getUnits() * $chain_item->getNumberOfPersons();
            $chain_price += $item_price;
            foreach ( $extras as $key => &$extra ) {
                if ( $extra->getMaxQuantity() <= 0 ) {
                    unset( $extras[ $key ] );
                } else {
                    $extra->setMinQuantity( max( 0, $extra->getMinQuantity() ) );
                }
            }
            $chain[] = array(
                'service_title'  => $service->getTranslatedTitle(),
                'extras'         => $extras,
                'checked_extras' => $chain_item->getExtras(),
                'nop_multiplier' => get_option( 'bookly_service_extras_multiply_nop', 1 ) ? $chain_item->getNumberOfPersons() : 1,
            );
        }

        $show = get_option( 'bookly_service_extras_show' );

        return self::renderTemplate( 'step_extras', compact( 'chain', 'show', 'show_cart_btn', 'info_text', 'progress_tracker', 'chain_price', 'show_back_btn' ), false );
    }
}