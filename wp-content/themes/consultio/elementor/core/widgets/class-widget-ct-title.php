<?php

class CT_CtTitle_Widget extends Case_Theme_Core_Widget_Base{
    protected $name = 'ct_title';
    protected $title = 'Case Title';
    protected $icon = 'eicon-t-letter';
    protected $categories = array( 'case-theme-core' );
    protected $params = '{"sections":[{"name":"title_section","label":"Content","tab":"content","controls":[{"name":"style","label":"Style","type":"select","options":{"style1":"Style 1","style2":"Style 2","style3":"Style 3","style4":"Style 4"},"default":"style1"},{"name":"title","label":"Title","type":"text","label_block":true},{"name":"title_color","label":"Title Color","type":"color","selectors":{"{{WRAPPER}} .ct-title1 h3":"color: {{VALUE}};"}},{"name":"title_color_hover","label":"Title Color Hover","type":"color","selectors":{"{{WRAPPER}} .ct-title1 h3:hover":"color: {{VALUE}} !important;","{{WRAPPER}} .ct-title1 h3 a span:before":"background-color: {{VALUE}} !important;"},"condition":{"style":["style4"]}},{"name":"line_color","label":"Line Color","type":"color","selectors":{"{{WRAPPER}} .ct-title1 h3 i":"background-color: {{VALUE}};"},"condition":{"style":["style1","style3"]}},{"name":"title_typography","type":"typography","control_type":"group","selector":"{{WRAPPER}} .ct-title1 h3"},{"name":"title_space_bottom","label":"Bottom Space","type":"slider","control_type":"responsive","size_units":["px"],"range":{"px":{"min":0,"max":300}},"selectors":{"{{WRAPPER}} .ct-title1 h3":"margin-bottom: {{SIZE}}{{UNIT}} !important;"}},{"name":"link","label":"Link","type":"url","condition":{"style":["style4"]}}]}]}';
    protected $styles = array(  );
    protected $scripts = array(  );
}