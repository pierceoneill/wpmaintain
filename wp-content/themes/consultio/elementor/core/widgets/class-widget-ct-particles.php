<?php

class CT_CtParticles_Widget extends Case_Theme_Core_Widget_Base{
    protected $name = 'ct_particles';
    protected $title = 'Case Particles V2';
    protected $icon = 'eicon-barcode';
    protected $categories = array( 'case-theme-core' );
    protected $params = '{"sections":[{"name":"content_section","label":"Content","tab":"content","controls":[{"name":"style","label":"Style","type":"select","options":{"style-1":"Style 1","style-2":"Style 2"},"default":"style-1"},{"name":"number","label":"Number","type":"text","placeholder":"Enter number. Default: 60","label_block":true},{"name":"size","label":"Size","type":"text","placeholder":"Enter number. Default: 3","label_block":true},{"name":"size_random","label":"Size Random","type":"select","options":{"true":"True","false":"False"},"default":"false"},{"name":"line_linked","label":"Line Linked","type":"select","options":{"true":"True","false":"False"},"default":"true"},{"name":"move_direction","label":"Move Direction","type":"select","options":{"none":"None","top":"Top","top-right":"Top Right","right":"Right","bottom-right":"Bottom Right","bottom":"Bottom","bottom-left":"Bottom Left","left":"Left","top-left":"Top Left"},"default":"none"},{"name":"particle_color_item","label":"Colors","type":"repeater","controls":[{"name":"particle_color","label":"Color","type":"color"}]}]}]}';
    protected $styles = array(  );
    protected $scripts = array( 'lib-particles-js','ct-particles-js' );
}