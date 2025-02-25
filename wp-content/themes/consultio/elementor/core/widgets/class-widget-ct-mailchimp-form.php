<?php

class CT_CtMailchimpForm_Widget extends Case_Theme_Core_Widget_Base{
    protected $name = 'ct_mailchimp_form';
    protected $title = 'Case Mailchimp Form';
    protected $icon = 'eicon-email-field';
    protected $categories = array( 'case-theme-core' );
    protected $params = '{"sections":[{"name":"source_section","label":"Color Settings","tab":"style","controls":[{"name":"style","label":"Style","type":"select","options":{"style1":"Style 1","style2":"Style 2","style3":"Style 3","style4":"Style 4","style5":"Style 5","style6":"Style 6","style7":"Style 7"},"default":"style1"},{"name":"input_color","label":"Input Color","type":"color","selectors":{"{{WRAPPER}} .ct-mailchimp.ct-mailchimp1 .mc4wp-form .mc4wp-form-fields input":"color: {{VALUE}};"}},{"name":"input_bg_color","label":"Input Background Color","type":"color","selectors":{"{{WRAPPER}} .ct-mailchimp.ct-mailchimp1 .mc4wp-form .mc4wp-form-fields input":"background-color: {{VALUE}};"}},{"name":"button_bg_color","label":"Button Background Color","type":"color","selectors":{"{{WRAPPER}} .ct-mailchimp.ct-mailchimp1 .mc4wp-form .mc4wp-form-fields:before":"background: {{VALUE}};","{{WRAPPER}} .ct-mailchimp.ct-mailchimp1 .mc4wp-form":"--gradient-color-from: {{VALUE}};"}},{"name":"button_bg_gradient","label":"Button Background Gradient","type":"color","selectors":{"{{WRAPPER}} .ct-mailchimp.ct-mailchimp1 .mc4wp-form":"--gradient-color-to: {{VALUE}};"}},{"name":"gradient_type","label":"Button Gradient Type","type":"select","options":{"horizontal":"Horizontal","vertical":"Vertical"},"default":"horizontal"}]}]}';
    protected $styles = array(  );
    protected $scripts = array(  );
}