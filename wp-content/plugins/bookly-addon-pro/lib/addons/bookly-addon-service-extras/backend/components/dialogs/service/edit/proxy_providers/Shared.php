<?php
namespace BooklyServiceExtras\Backend\Components\Dialogs\Service\Edit\ProxyProviders;

use Bookly\Lib as BooklyLib;
use BooklyServiceExtras\Lib;
use Bookly\Backend\Components\Dialogs\Service\Edit\Proxy;
use BooklyServiceExtras\Lib\Entities\ServiceExtra;

class Shared extends Proxy\Shared
{
    /**
     * @inheritDoc
     */
    public static function enqueueAssetsForServices()
    {
        $list = Lib\Utils\Common::getExtrasList();

        self::enqueueStyles( array(
            'bookly' => array(
                'backend/resources/css/typeahead.css' => array( 'bookly-backend-globals' ),
            ),
        ) );

        self::enqueueScripts( array(
            'module' => array( 'js/extras.js' => array( 'jquery' ), ),
            'bookly' => array(
                'backend/resources/js/typeahead.bundle.min.js' => array( 'jquery' ),
            ),
        ) );

        wp_localize_script( 'bookly-extras.js', 'BooklyExtrasL10n', array(
            'list' => $list,
            'quantity_error' => __( 'Min quantity should not be greater than max quantity', 'bookly' ),
        ) );
    }

    /**
     * @inheritDoc
     */
    public static function prepareAfterServiceList( $html, $simple_services )
    {
        return $html . self::renderTemplate( 'extras_blank', array(), false  );
    }

    /**
     * @inheritDoc
     */
    public static function prepareUpdateServiceResponse( array $response, BooklyLib\Entities\Service $service )
    {
        $response['new_extras_list'] = Lib\Utils\Common::getExtrasList();

        return $response;
    }

    /**
     * @inheritDoc
     */
    public static function updateService( array $alert, BooklyLib\Entities\Service $service, array $parameters )
    {
        if ( isset( $parameters['extras'] ) ) {
            $extras = $parameters['extras'];
            $existing_extras = ServiceExtra::query()->where( 'service_id', $service->getId() )->fetchCol( 'id' );
            $ids_to_delete = array_diff( $existing_extras, array_keys( $extras ) );
            if ( $ids_to_delete ) {
                // Remove redundant extras.
                ServiceExtra::query()->delete()->whereIn( 'id', $ids_to_delete )->execute();
            }
            foreach ( $extras as $data ) {
                $entity = new ServiceExtra();
                if ( isset( $data['id'] ) ) {
                    $entity->load( $data['id'] );
                }
                $entity->setFields( $data )
                    ->setServiceId( $service->getId() )
                    ->save();
            }
        } else {
            ServiceExtra::query()->delete()->where( 'service_id', $service->getId() )->execute();
        }

        return $alert;
    }
}