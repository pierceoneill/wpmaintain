<?php

class CT_CtTabTemplate_Widget extends Case_Theme_Core_Widget_Base{
    protected $name = 'ct_tab_template';
    protected $title = 'Tab Template';
    protected $icon = 'eicon-tabs';
    protected $categories = array( 'case-theme-core' );
    protected $params = '{"sections":[{"name":"section_tabs","label":"Tabs","tab":"content","controls":[{"name":"active_tab","label":"Active Tab","type":"number","default":1,"separator":"after"},{"name":"tabs","label":"Tabs Items","type":"repeater","controls":[{"name":"tab_title","label":"Title &amp; Description","type":"text","default":"Tab Title","placeholder":"Tab Title","label_block":true},{"name":"content_type","label":"Content Type","type":"select","default":"template","options":{"template":"Template","text_editor":"Text Editor"}},{"name":"tab_content_template","label":"Template","type":"select","default":"","options":{"":"Select Template","1217":"Pricing - Year","1214":"Pricing - Month"},"condition":{"content_type":"template"}},{"name":"tab_content","label":"Content","type":"wysiwyg","default":"Tab Content","placeholder":"Tab Content","show_label":false,"condition":{"content_type":"text_editor"}}],"title_field":"{{{ tab_title }}}"}]}]}';
    protected $styles = array(  );
    protected $scripts = array( 'ct-tabs-widget-js' );
}