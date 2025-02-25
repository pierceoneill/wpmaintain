<?php

class CT_CtPortfolioDetails_Widget extends Case_Theme_Core_Widget_Base{
    protected $name = 'ct_portfolio_details';
    protected $title = 'Case Portfolio Details';
    protected $icon = 'eicon-library-upload';
    protected $categories = array( 'case-theme-core' );
    protected $params = '{"sections":[{"name":"section_content","label":"Content","tab":"content","controls":[{"name":"portfolio_content","label":"Content","type":"repeater","controls":[{"name":"ct_icon","label":"Icon","type":"icons","fa4compatibility":"icon","default":{"value":"fas fa-star","library":"fa-solid"}},{"name":"label","label":"Label","type":"text","label_block":true},{"name":"content","label":"Content","type":"text"}],"title_field":"{{{ label }}}"}]}]}';
    protected $styles = array(  );
    protected $scripts = array(  );
}