<?php

class PxlGallery_Widget extends Pxltheme_Core_Widget_Base{
    protected $name = 'pxl_gallery';
    protected $title = 'BR Gallery';
    protected $icon = 'eicon-gallery-justified';
    protected $categories = array( 'pxltheme-core' );
    protected $params = '{"sections":[{"name":"section_content","label":"Content","tab":"content","controls":[{"name":"image","label":"Image","type":"media"},{"name":"title","label":"Title","type":"text","label_block":true},{"name":"img_size","label":"Image Size Default","type":"text","description":"Enter image size (Example: \"thumbnail\", \"medium\", \"large\", \"full\" or other sizes defined by theme). Alternatively enter size in pixels (Example: 200x100 (Width x Height)."},{"name":"img_size_popup","label":"Image Size Popup","type":"text","description":"Enter image size (Example: \"thumbnail\", \"medium\", \"large\", \"full\" or other sizes defined by theme). Alternatively enter size in pixels (Example: 200x100 (Width x Height)."}]}]}';
    protected $styles = array(  );
    protected $scripts = array( 'imagesloaded','isotope','pxl-post-grid' );
}