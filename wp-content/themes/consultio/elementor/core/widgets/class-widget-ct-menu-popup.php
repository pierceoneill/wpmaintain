<?php

class CT_CtMenuPopup_Widget extends Case_Theme_Core_Widget_Base{
    protected $name = 'ct_menu_popup';
    protected $title = 'Case Menu Popup';
    protected $icon = 'eicon-nav-menu';
    protected $categories = array( 'case-theme-core' );
    protected $params = '{"sections":[{"name":"source_section","label":"Source Settings","tab":"content","controls":[{"name":"label_menu","label":"Label","type":"text"},{"name":"label_typography","label":"Typography","type":"typography","control_type":"group","selector":"{{WRAPPER}} .ct-header-menu-popup span"},{"name":"label_color","label":"Color","type":"color","selectors":{"{{WRAPPER}} .ct-header-menu-popup span ":"color: {{VALUE}};"}}]}]}';
    protected $styles = array(  );
    protected $scripts = array(  );
}