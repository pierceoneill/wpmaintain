<?php

class CT_CtNavigationMenu_Widget extends Case_Theme_Core_Widget_Base{
    protected $name = 'ct_navigation_menu';
    protected $title = 'Case Navigation Menu';
    protected $icon = 'eicon-menu-bar';
    protected $categories = array( 'case-theme-core' );
    protected $params = '{"sections":[{"name":"source_section","label":"Source Settings","tab":"content","controls":[{"name":"menu","label":"Select Menu","type":"select","options":{"":"Default","main-menu":"Main Menu","menu-one-page":"Menu One Page"}},{"name":"style","label":"Style","type":"select","options":{"default":"Default","one-col-light":"1 Column Light","tow-col-light":"2 Columns Light","tow-col-light preset2":"2 Columns Light - Preset 2","style-light1":"Style Light 1","style-light2":"Style Light 2","style-modern":"Modern"},"default":"default"},{"name":"link_color","label":"Link Color","type":"color","selectors":{"{{WRAPPER}} .ct-navigation-menu1 ul.menu li a":"color: {{VALUE}} !important;"}},{"name":"link_color_hover","label":"Link Color Hover &amp; Active","type":"color","selectors":{"{{WRAPPER}} .ct-navigation-menu1 ul.menu li a:hover, {{WRAPPER}} .ct-navigation-menu1 ul.menu li.current_page_item > a, {{WRAPPER}} .ct-navigation-menu1 ul.menu li.current-menu-item > a":"color: {{VALUE}} !important;"}},{"name":"line_color_hover","label":"Line Color Hover","type":"color","selectors":{"{{WRAPPER}} .ct-navigation-menu1 ul.menu li a:after":"background-color: {{VALUE}} !important;"}},{"name":"link_typography","label":"Link Typography","type":"typography","control_type":"group","selector":"{{WRAPPER}} .ct-navigation-menu1 ul.menu li a"},{"name":"item_space","label":"Item Spacer","type":"slider","control_type":"responsive","size_units":["px"],"range":{"px":{"min":0,"max":300}},"selectors":{"{{WRAPPER}} .ct-navigation-menu1 ul li + li":"margin-top: {{SIZE}}{{UNIT}};"}}]}]}';
    protected $styles = array(  );
    protected $scripts = array(  );
}