<?php

class CT_CtBreadcrumb_Widget extends Case_Theme_Core_Widget_Base{
    protected $name = 'ct_breadcrumb';
    protected $title = 'Case Breadcrumb';
    protected $icon = 'eicon-yoast';
    protected $categories = array( 'case-theme-core' );
    protected $params = '{"sections":[{"name":"breadcrumb_section","label":"Breadcrumb","tab":"content","controls":[{"name":"breadcrumb_color","label":"Color","type":"color","selectors":{"{{WRAPPER}} .ct-elementor-breadcrumb":"color: {{VALUE}};"},"control_type":"responsive"},{"name":"breadcrumb_typography","label":"Typography","type":"typography","control_type":"group","selector":"{{WRAPPER}} .ct-elementor-breadcrumb"}]}]}';
    protected $styles = array(  );
    protected $scripts = array(  );
}