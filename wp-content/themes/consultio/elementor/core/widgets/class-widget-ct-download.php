<?php

class CT_CtDownload_Widget extends Case_Theme_Core_Widget_Base{
    protected $name = 'ct_download';
    protected $title = 'Case Download';
    protected $icon = 'eicon-file-download';
    protected $categories = array( 'case-theme-core' );
    protected $params = '{"sections":[{"name":"section_content","label":"Content","tab":"content","controls":[{"name":"download","label":"Download List","type":"repeater","default":[],"controls":[{"name":"title","label":"Title","type":"textarea","label_block":true},{"name":"ct_icon","label":"Icon","type":"icons","fa4compatibility":"icon"},{"name":"link","label":"Link","type":"url"}],"title_field":"{{{ title }}}"}]}]}';
    protected $styles = array(  );
    protected $scripts = array(  );
}