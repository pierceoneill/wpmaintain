<?php
namespace BooklyServiceExtras\Lib;

use Bookly\Lib as BooklyLib;
use BooklyServiceExtras\Backend;
use BooklyServiceExtras\Frontend;
use BooklyServiceExtras\Backend\Components;

abstract class Plugin extends BooklyLib\Base\Plugin
{
    protected static $prefix;
    protected static $title;
    protected static $version;
    protected static $slug;
    protected static $directory;
    protected static $main_file;
    protected static $basename;
    protected static $text_domain;
    protected static $root_namespace;
    protected static $embedded;

    /**
     * @inheritDoc
     */
    protected static function init()
    {
        // Register proxy methods.
        Backend\Modules\Appearance\ProxyProviders\Local::init();
        Backend\Modules\Appearance\ProxyProviders\Shared::init();
        Backend\Modules\Calendar\ProxyProviders\Shared::init();
        Backend\Modules\Notifications\ProxyProviders\Shared::init();
        Backend\Modules\Services\ProxyProviders\Shared::init();
        Backend\Modules\Settings\ProxyProviders\Shared::init();
        if ( get_option( 'bookly_service_extras_enabled' ) ) {
            Frontend\Modules\Booking\ProxyProviders\Shared::init();
            Frontend\Modules\Booking\ProxyProviders\Local::init();
        }
        Frontend\Modules\ModernBookingForm\ProxyProviders\Shared::init();
        Components\Dialogs\Appointment\Edit\ProxyProviders\Local::init();
        Components\Dialogs\Appointment\CustomerDetails\ProxyProviders\Shared::init();
        Components\Dialogs\Service\Edit\ProxyProviders\Local::init();
        Components\Dialogs\Service\Edit\ProxyProviders\Shared::init();
        Notifications\Assets\Item\ProxyProviders\Shared::init();
        ProxyProviders\Local::init();
        ProxyProviders\Shared::init();
    }

    /**
     * @inerhitDoc
     */
    protected static function registerAjax()
    {
        Components\Dialogs\Service\Edit\Ajax::init();
        Frontend\Modules\ModernBookingForm\Ajax::init();
    }
}