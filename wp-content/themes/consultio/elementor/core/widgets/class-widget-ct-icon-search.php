<?php

class CT_CtIconSearch_Widget extends Case_Theme_Core_Widget_Base{
    protected $name = 'ct_icon_search';
    protected $title = 'Case Icon Search';
    protected $icon = 'eicon-search';
    protected $categories = array( 'case-theme-core' );
    protected $params = '{"sections":[{"name":"source_section","label":"Source Settings","tab":"content","controls":[{"name":"selected_icon","label":"Icon","type":"icons","fa4compatibility":"icon"},{"name":"icon_color","label":"Icon Color","type":"color","selectors":{"{{WRAPPER}} .ct-search-popup":"color: {{VALUE}};"}},{"name":"icon_color_hover","label":"Icon Color Hover","type":"color","selectors":{"{{WRAPPER}} .ct-search-popup:hover":"color: {{VALUE}};"}},{"name":"icon_font_size","label":"Icon Font Size","type":"slider","control_type":"responsive","size_units":["px"],"range":{"px":{"min":0,"max":300}},"selectors":{"{{WRAPPER}} .ct-search-popup":"font-size: {{SIZE}}{{UNIT}};"}}]}]}';
    protected $styles = array(  );
    protected $scripts = array(  );
}