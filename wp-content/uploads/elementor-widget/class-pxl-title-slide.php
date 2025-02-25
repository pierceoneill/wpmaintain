<?php

class PxlTitleSlide_Widget extends Pxltheme_Core_Widget_Base{
    protected $name = 'pxl_title_slide';
    protected $title = 'Case Title Slide';
    protected $icon = 'eicon-text';
    protected $categories = array( 'pxltheme-core' );
    protected $params = '{"sections":[{"name":"tab_content","label":"Content","tab":"content","controls":[{"name":"title","label":"Content","type":"repeater","controls":[{"name":"title_text","label":"Title","type":"text","label_block":true},{"name":"item_link","label":"Link","type":"url","label_block":true}],"title_field":"{{{ title_text }}}"}]},{"name":"tab_style","label":"Style","tab":"style","controls":[{"name":"title_color","label":"Title Color","type":"color","selectors":{"{{WRAPPER}} .pxl-title-scroll .pxl-item--title a":"color: {{VALUE}};"}},{"name":"title_typography","label":"Title Typography","type":"typography","control_type":"group","selector":"{{WRAPPER}} .pxl-title-scroll .pxl-item--title"}]}]}';
    protected $styles = array(  );
    protected $scripts = array( 'gsap','pxl-slideText','pxl-scroll-trigger' );
}