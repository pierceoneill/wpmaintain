<?php

class CT_CtTeamDetails_Widget extends Case_Theme_Core_Widget_Base{
    protected $name = 'ct_team_details';
    protected $title = 'Case Team Details';
    protected $icon = 'eicon-nerd-wink';
    protected $categories = array( 'case-theme-core' );
    protected $params = '{"sections":[{"name":"section_Content","label":"Content","tab":"content","controls":[{"name":"image","label":"Image","type":"media","description":"Select image."},{"name":"title","label":"Title","type":"text","label_block":true},{"name":"position","label":"Position","type":"text"},{"name":"email","label":"Email","type":"text"},{"name":"phone","label":"Phone","type":"text"},{"name":"address","label":"Address","type":"text"},{"name":"icons","label":"Social","type":"repeater","controls":[{"name":"ct_icon","label":"Icon","type":"icons","fa4compatibility":"icon","default":{"value":"fas fa-star","library":"fa-solid"}},{"name":"icon_link","label":"Icon Link","type":"url","label_block":true}]},{"name":"btn_text","label":"Button Text","type":"text"},{"name":"btn_link","label":"Button Link","type":"url"}]}]}';
    protected $styles = array(  );
    protected $scripts = array(  );
}