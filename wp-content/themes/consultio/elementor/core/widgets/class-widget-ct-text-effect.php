<?php

class CT_CtTextEffect_Widget extends Case_Theme_Core_Widget_Base{
    protected $name = 'ct_text_effect';
    protected $title = 'Case Text Effect';
    protected $icon = 'eicon-heading';
    protected $categories = array( 'case-theme-core' );
    protected $params = '{"sections":[{"name":"section_content","label":"Content","tab":"content","controls":[{"name":"ct_text","label":"Text","type":"repeater","controls":[{"name":"text","label":"Text","type":"text","label_block":true}],"title_field":"{{{ text }}}"}]},{"name":"section_style","label":"Style","tab":"style","controls":[{"name":"text_style","label":"Text Style","type":"select","options":{"text-default":"Default","text-outline":"Outline"},"default":"text-default"},{"name":"text_color","label":"Color","type":"color","selectors":{"{{WRAPPER}} .ct-text-effect":"color: {{VALUE}};"},"condition":{"text_style":"text-default"}},{"name":"text_outline_color","label":"Color","type":"color","selectors":{"{{WRAPPER}} .ct-text-effect.text-outline":"-webkit-text-stroke-color: {{VALUE}};"},"condition":{"text_style":"text-outline"}},{"name":"h_el","label":"Box Height","type":"slider","control_type":"responsive","size_units":["px","%"],"range":{"px":{"min":0,"max":3000}},"selectors":{"{{WRAPPER}} .ct-text-effect":"height: {{SIZE}}{{UNIT}};"}},{"name":"title_typography","label":"Typography","type":"typography","control_type":"group","selector":"{{WRAPPER}} .ct-text-effect"},{"name":"text_effect","label":"Text Effect","type":"select","options":{"":"None","ct-slide-to-left":"Slide Right To Left","ct-slide-to-right":"Slide Left To Right"},"default":""},{"name":"effect_speed","label":"Effect Speed","type":"text","label_block":true,"description":"Default: 18000 - Unit: ms","condition":{"text_effect":["ct-slide-to-left","ct-slide-to-right"]}}]}]}';
    protected $styles = array(  );
    protected $scripts = array(  );
}