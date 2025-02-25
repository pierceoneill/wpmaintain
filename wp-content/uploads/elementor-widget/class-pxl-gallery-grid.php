<?php

class PxlGalleryGrid_Widget extends Pxltheme_Core_Widget_Base{
    protected $name = 'pxl_gallery_grid';
    protected $title = 'Case Gallery Grid';
    protected $icon = 'eicon-gallery-justified';
    protected $categories = array( 'pxltheme-core' );
    protected $params = '{"sections":[{"name":"section_content","label":"Content","tab":"content","controls":[{"name":"image","label":"Image","type":"gallery","label_block":true},{"name":"img_size","label":"Image Size Default","type":"text","description":"Enter image size (Example: \"thumbnail\", \"medium\", \"large\", \"full\" or other sizes defined by theme). Alternatively enter size in pixels (Example: 200x100 (Width x Height)."},{"name":"img_size_popup","label":"Image Size Popup","type":"text","description":"Enter image size (Example: \"thumbnail\", \"medium\", \"large\", \"full\" or other sizes defined by theme). Alternatively enter size in pixels (Example: 200x100 (Width x Height)."}]},{"name":"grid_section","label":"Grid","tab":"content","controls":[{"name":"col_xs","label":"Columns XS Devices","type":"select","default":"1","options":{"1":"1","2":"2","3":"3","4":"4","6":"6"}},{"name":"col_sm","label":"Columns SM Devices","type":"select","default":"2","options":{"1":"1","2":"2","3":"3","4":"4","6":"6"}},{"name":"col_md","label":"Columns MD Devices","type":"select","default":"3","options":{"1":"1","2":"2","3":"3","4":"4","6":"6"}},{"name":"col_lg","label":"Columns LG Devices","type":"select","default":"4","options":{"1":"1","2":"2","3":"3","4":"4","5":"5","6":"6"}},{"name":"col_xl","label":"Columns XL Devices","type":"select","default":"4","options":{"1":"1","2":"2","3":"3","4":"4","5":"5","6":"6"}},{"name":"spacing","label":"Spacing","type":"select","default":"nogap","options":{"nogap":"No Gap","default":"Default"}}]}]}';
    protected $styles = array(  );
    protected $scripts = array( 'imagesloaded','isotope','pxl-post-grid' );
}