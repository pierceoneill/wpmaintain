<?php

class CT_CtPointer_Widget extends Case_Theme_Core_Widget_Base{
    protected $name = 'ct_pointer';
    protected $title = 'Pointer';
    protected $icon = 'eicon-cursor-move';
    protected $categories = array( 'case-theme-core' );
    protected $params = '{"sections":[{"name":"content_section","label":"Content","tab":"content","controls":[{"name":"image","label":"Banner Image","type":"media"},{"name":"content_list","label":"Pointer","type":"repeater","controls":[{"name":"title","label":"Title","type":"text","label_block":true},{"name":"description","label":"Description","type":"textarea"},{"name":"item_link","label":"Link","type":"url","label_block":true},{"name":"active","label":"Actvie","type":"select","options":{"":"No","open":"Yes"},"default":""},{"name":"position","label":"Content Position","type":"select","options":{"content-top":"Top","content-right":"Right","content-bottom":"Bottom","content-left":"Left"},"default":"content-left"},{"name":"pointer_position_top","label":"Position Top (%)","type":"slider","control_type":"responsive","size_units":["%"],"default":{"size":0},"range":{"%":{"min":0,"max":100}}},{"name":"pointer_position_right","label":"Position Right (%)","type":"slider","control_type":"responsive","size_units":["%"],"default":{"size":0},"range":{"%":{"min":0,"max":100}}},{"name":"pointer_position_bottom","label":"Position Bottom (%)","type":"slider","control_type":"responsive","size_units":["%"],"default":{"size":0},"range":{"%":{"min":0,"max":100}}},{"name":"pointer_position_left","label":"Position Left (%)","type":"slider","control_type":"responsive","size_units":["%"],"default":{"size":0},"range":{"%":{"min":0,"max":100}}}],"title_field":"{{{ title }}}"},{"name":"style","label":"Pointer Style","type":"select","options":{"style1":"Style 1","style2":"Style 2"},"default":"style1"}]}]}';
    protected $styles = array(  );
    protected $scripts = array(  );
}