<?php

class CT_CtTypingOut_Widget extends Case_Theme_Core_Widget_Base{
    protected $name = 'ct_typing_out';
    protected $title = 'Case Typing Out';
    protected $icon = 'eicon-edit';
    protected $categories = array( 'case-theme-core' );
    protected $params = '{"sections":[{"name":"content_section","label":"Content","tab":"content","controls":[{"name":"sub_title","label":"Sub Title","type":"text"},{"name":"sub_title_color","label":"Sub Title Color","type":"color","selectors":{"{{WRAPPER}} .ct-sub-title":"color: {{VALUE}};"}},{"name":"sub_title_typography","label":"Sub Title Typography","type":"typography","control_type":"group","selector":"{{WRAPPER}} .ct-sub-title"},{"name":"typing_out","label":"Typping Out","type":"textarea","description":"Example: &quot;designing&quot;, &quot;developing&quot;, &quot;marketing&quot; "},{"name":"typing_out_color","label":"Typping Out Color","type":"color","selectors":{"{{WRAPPER}} .ct-typing-out":"color: {{VALUE}};"}},{"name":"typing_out_typography","label":"Typping Out Typography","type":"typography","control_type":"group","selector":"{{WRAPPER}} .ct-typing-out"}]}]}';
    protected $styles = array(  );
    protected $scripts = array( 'ct-typing-out-js' );
}