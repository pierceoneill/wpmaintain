<?php

class CT_CtHistory_Widget extends Case_Theme_Core_Widget_Base{
    protected $name = 'ct_history';
    protected $title = 'Case History';
    protected $icon = 'eicon-history';
    protected $categories = array( 'case-theme-core' );
    protected $params = '{"sections":[{"name":"section_content","label":"Content","tab":"content","controls":[{"name":"start_text","label":"Start Text","type":"text","default":"Start"},{"name":"end_image","label":"End Image","type":"media"},{"name":"history","label":"History","type":"repeater","default":[],"controls":[{"name":"title","label":"Title","type":"text","label_block":true},{"name":"content","label":"Content","type":"textarea","label_block":true},{"name":"item_link","label":"Link","type":"url","label_block":true},{"name":"item_margin_bottom","label":"Item Spacer Bottom","type":"slider","control_type":"responsive","size_units":["px"],"range":{"px":{"min":0,"max":1000}},"selectors":{"{{WRAPPER}} .ct-history1 .ct-history--holder {{CURRENT_ITEM}}":"margin-bottom: {{SIZE}}{{UNIT}} !important;"}}],"title_field":"{{{ title }}}"}]}]}';
    protected $styles = array(  );
    protected $scripts = array(  );
}