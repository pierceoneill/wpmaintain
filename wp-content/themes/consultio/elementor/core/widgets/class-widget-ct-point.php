<?php

class CT_CtPoint_Widget extends Case_Theme_Core_Widget_Base{
    protected $name = 'ct_point';
    protected $title = 'Case Point';
    protected $icon = 'eicon-cursor-move';
    protected $categories = array( 'case-theme-core' );
    protected $params = '{"sections":[{"name":"content_section","label":"Source Settings","tab":"content","controls":[{"name":"bg_image","label":"Background Image","type":"media"},{"name":"item_list","label":"Items","type":"repeater","default":[],"controls":[{"name":"icon","label":"Icon","type":"media"},{"name":"phone","label":"Phone","type":"text"},{"name":"email","label":"Email","type":"text"},{"name":"address","label":"Address","type":"text"},{"name":"top_positioon","label":"Top Position","type":"slider","size_units":["%"],"default":{"size":0},"range":{"%":{"min":0,"max":100}}},{"name":"left_positioon","label":"Left Position","type":"slider","size_units":["%"],"default":{"size":0},"range":{"%":{"min":0,"max":100}}}]}]}]}';
    protected $styles = array(  );
    protected $scripts = array(  );
}