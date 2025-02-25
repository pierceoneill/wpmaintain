<?php

class CT_CtPageTitle_Widget extends Case_Theme_Core_Widget_Base{
    protected $name = 'ct_page_title';
    protected $title = 'Case Page Title';
    protected $icon = 'eicon-archive-title';
    protected $categories = array( 'case-theme-core' );
    protected $params = '{"sections":[{"name":"title_section","label":"Page Title","tab":"content","controls":[{"name":"ptitle_color","label":"Title Color","type":"color","selectors":{"{{WRAPPER}} .ct-page-title":"color: {{VALUE}};"},"control_type":"responsive"},{"name":"ptitle_typography","label":"Title Typography","type":"typography","control_type":"group","selector":"{{WRAPPER}} .ct-page-title"}]}]}';
    protected $styles = array(  );
    protected $scripts = array(  );
}