<?php

register_menu_positions(array(
    'main_menu'         =>  t('anastasia::strings.main_menu'),
    'secondary_menu'    =>  t('anastasia::strings.secondary_menu')
));

register_content_block(array(
    'menu_select_block'  =>  array(
        'name'  =>  'menu_select_block',
        'title' =>  t('anastasia::strings.menu'),
        'tpl'   =>  array(
            'anastasia::blocks/topmenu'=>'default',
            'anastasia::blocks/pills'   =>  'Pills'
        ),
        'model'  =>  '\Menu',
        'action'    => 'find',
        'params_action'=>'lists',
        'params_args'   => array('title','id'),
        'params_title'=>0,
        'params'    => 1,
        'multiple'  =>  true,
        'configurable'=> true
    ),
    'logo_block'  =>  array(
        'name'  =>  'logo_block',
        'title' =>  t('anastasia::strings.logo'),
        'tpl'   =>  array(
            'anastasia::blocks/logo'=>'default'
        ),
        'multiple'  =>  true,
        'configurable'=> false
    ),
    'carousel_block'  =>  array(
        'name'  =>  'carousel_block',
        'title' =>  t('anastasia::strings.carousel'),
        'tpl'   =>  array(
            'anastasia::blocks/carousel'=>'default'
        ),
        'model'  =>  'AnastasiaTheme',
        'action'    => 'getimages',
        'params_action'=>'listimages',
        'params_args'   => array('original_filename','id'),
        'params_tpl'=>  'anastasia::blocks/param_tpls/carousel',
        'params'    => array(
            array('img'=>1,'url'=>'#','caption'=>'Caption 1'),
            array('img'=>2,'url'=>'#','caption'=>'Caption 2'),
            array('img'=>3,'url'=>'#','caption'=>'Caption 3'),
        ),
        'multiple'  =>  true,
        'configurable'=> true
    ),
    'html_box'  =>  array(
        'name'  =>  'html_box',
        'title' =>  t('anastasia::strings.html_box'),
        'tpl'   =>  array(
            'anastasia::blocks/htmlbox'=>'default'
        ),
        'model'  =>  'AnastasiaTheme',
        'action'    => 'getblockdata',
        'params_tpl'=>  'anastasia::blocks/param_tpls/htmlbox',
        'params'    => array('title'=>'Custom block','text'=>'This is a configurable text','url'=>'http://www.google.com','button'=>'click here'),
        'params_title'=>'title',
        'multiple'  =>  true,
        'configurable'=> true
    ),
    'call_to_action'  =>  array(
        'name'  =>  'call_to_action',
        'title' =>  t('anastasia::strings.call_to_action'),
        'tpl'   =>  array(
            'anastasia::blocks/calltoaction'=>'default'
        ),
        'model'  =>  'AnastasiaTheme',
        'action'    => 'getblockdata',
        'params_tpl'=>  'anastasia::blocks/param_tpls/htmlbox',
        'params'    => array('title'=>'Custom block','text'=>'This is a configurable text','url'=>'http://www.google.com','button'=>'click here'),
        'params_title'=>'title',
        'multiple'  =>  true,
        'configurable'=> true
    ),


));