<?php

class CT_CtIconHiddenSidebar_Widget extends Case_Theme_Core_Widget_Base{
    protected $name = 'ct_icon_hidden_sidebar';
    protected $title = 'Case Icon Hidden Sidebar';
    protected $icon = 'eicon-menu-bar';
    protected $categories = array( 'case-theme-core' );
    protected $params = '{"sections":[{"name":"source_section","label":"Source Settings","tab":"content","controls":[{"name":"style","label":"Style","type":"select","options":{"style1":"Style 1","style2":"Style 2"},"default":"style1"},{"name":"icon_color","label":"Icon Color","type":"color","selectors":{"{{WRAPPER}} .ct-icon-hidden-sidebar span":"color: {{VALUE}};","{{WRAPPER}} .ct-icon-hidden-sidebar.style2 .item--inner .icon-line, {{WRAPPER}} .ct-icon-hidden-sidebar.style2 .item--inner::before, {{WRAPPER}} .ct-icon-hidden-sidebar.style2 .item--inner::after":"background-color: {{VALUE}};"}},{"name":"icon_color_hover","label":"Icon Color Hover","type":"color","selectors":{"{{WRAPPER}} .ct-icon-hidden-sidebar span:hover":"color: {{VALUE}};","{{WRAPPER}} .ct-icon-hidden-sidebar.style2 .item--inner:hover .icon-line, {{WRAPPER}} .ct-icon-hidden-sidebar.style2 .item--inner:hover:before, {{WRAPPER}} .ct-icon-hidden-sidebar.style2 .item--inner:hover:after":"background-color: {{VALUE}};"}},{"name":"wg_align","label":"Alignment","type":"choose","control_type":"responsive","options":{"flex-start":{"title":"Left","icon":"fa fa-align-left"},"center":{"title":"Center","icon":"fa fa-align-center"},"flex-end":{"title":"Right","icon":"fa fa-align-right"}},"selectors":{"{{WRAPPER}} .ct-icon-hidden-sidebar":"justify-content: {{VALUE}};"}}]}]}';
    protected $styles = array(  );
    protected $scripts = array(  );
}