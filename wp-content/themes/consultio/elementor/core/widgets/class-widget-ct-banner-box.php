<?php

class CT_CtBannerBox_Widget extends Case_Theme_Core_Widget_Base{
    protected $name = 'ct_banner_box';
    protected $title = 'Banner Box';
    protected $icon = 'eicon-info-box';
    protected $categories = array( 'case-theme-core' );
    protected $params = '{"sections":[{"name":"content_section","label":"Banner Box","tab":"content","controls":[{"name":"image","label":"Choose Image","type":"media"},{"name":"thumbnail","label":"Image Size","type":"image-size","control_type":"group","default":"full"}]}]}';
    protected $styles = array(  );
    protected $scripts = array(  );
}