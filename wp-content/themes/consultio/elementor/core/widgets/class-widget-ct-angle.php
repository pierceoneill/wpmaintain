<?php

class CT_CtAngle_Widget extends Case_Theme_Core_Widget_Base{
    protected $name = 'ct_angle';
    protected $title = 'Case Angle Row';
    protected $icon = 'eicon-filter';
    protected $categories = array( 'case-theme-core' );
    protected $params = '{"sections":[{"name":"source_section","label":"Source Settings","tab":"content","controls":[{"name":"angle_layout","label":"Layout","type":"select","options":{"layout1":"Layout 1","layout2":"Layout 2"},"default":"layout1"},{"name":"angle_color","label":"Color","type":"color","selectors":{"{{WRAPPER}}":"fill: {{VALUE}};"}},{"name":"angle_height","label":"Height","type":"slider","size_units":["px"],"default":{"size":90},"range":{"px":{"min":0,"max":1000}}},{"name":"angle_position","label":"Position","type":"select","options":{"top":"Top","top-reverse":"Top Reverse","bottom":"Bottom"},"default":"bottom"},{"name":"angle_offset","label":"Offset","type":"slider","size_units":["px"],"range":{"px":{"min":0,"max":1000}},"default":{"size":0}},{"name":"responsive","label":"Responsive","type":"select","options":{"lg":"Default","md":"Hide Tablet","sm":"Hide Mobile"},"default":"lg"},{"name":"el_container","label":"Container","type":"select","options":{"parent-container":"Parent","inner-container":"Inner"},"default":"parent-container"}]}]}';
    protected $styles = array(  );
    protected $scripts = array( 'ct-angle-js' );
}