<?php

class CT_CtBackgroundAnimate_Widget extends Case_Theme_Core_Widget_Base{
    protected $name = 'ct_background_animate';
    protected $title = 'Case Background Animate';
    protected $icon = 'eicon-posts-ticker';
    protected $categories = array( 'case-theme-core' );
    protected $params = '{"sections":[{"name":"content_section","label":"Content","tab":"content","controls":[{"name":"image","label":"Image","type":"media"},{"name":"image_overlay","label":"Image Overlay","type":"media"},{"name":"color_overlay","label":"Color Overlay","type":"color"}]}]}';
    protected $styles = array(  );
    protected $scripts = array(  );
}