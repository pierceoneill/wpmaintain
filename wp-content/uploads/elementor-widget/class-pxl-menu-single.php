<?php

class PxlMenuSingle_Widget extends Pxltheme_Core_Widget_Base{
    protected $name = 'pxl_menu_single';
    protected $title = 'BR Menu Single';
    protected $icon = 'eicon-nav-menu';
    protected $categories = array( 'pxltheme-core' );
    protected $params = '{"sections":[{"name":"section_list","label":"Content","tab":"content","controls":[{"name":"menu_item","label":"Item","type":"repeater","controls":[{"name":"text","label":"Text","type":"text","label_block":true},{"name":"link","label":"Link","type":"url","label_block":true}],"title_field":"{{{ text }}}"}]}]}';
    protected $styles = array(  );
    protected $scripts = array(  );
}