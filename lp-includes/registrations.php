<?php

/**
 * @param $paths
 * @param int $priority
 */
function register_dashboard_widget($paths, $priority = 1)
{
    \Event::listen('backend.widgets.collect', function () use ($paths) {
        return $paths;
    }, $priority);
}

/**
 * @param $paths
 * @param int $priority
 */
function register_dashboard_sidebar_menu($paths, $priority = 1)
{
    \Event::listen('backend.sidebar.create', function () use ($paths) {
        return $paths;
    }, $priority);
}

/**
 * @param $metas
 * @param int $priority
 */
function register_user_default_metas($metas,$priority=1){
    \Event::listen('collect.user.default_metas', function () use ($metas) {
        return $metas;
    }, $priority);
}

/**
 * @summary Allows to register actions that can cachebust the routes caching mechanism.
 *
 * @param array $routes
 * @param int $priority
 */
function register_no_cache_routes(Array $routes=array(),$priority=1){
    \Event::listen('before.cached.routes',function() use ($routes){
        $nocache = \Config::get('routes.no_cache');
        $nocache = (is_array($nocache))?$nocache:array();
        $nocache = array_merge($nocache,$routes);
        \Config::set('routes.no_cache',$nocache);
    },$priority);
}

/**
 * @param $paths
 * @param int $priority
 */
function register_dashboard_navbar_tools($paths, $priority = 1)
{
    \Event::listen('backend.navbar.create', function () use ($paths) {
        return $paths;
    }, $priority);
}

/**
 * @param $paths
 * @param int $priority
 */
function register_navbar_addon_links($paths, $priority = 1)
{
    \Event::listen('backend.addonlinks.collect', function () use ($paths) {
        return $paths;
    }, $priority);
}

/**
 * @param $paths
 * @param int $priority
 */
function register_footer_items($paths, $priority = 1)
{
    \Event::listen('backend.footer.create', function () use ($paths) {
        return $paths;
    }, $priority);
}

/**
 * @param $paths
 * @param int $priority
 */
function register_header_scripts($paths, $priority = 1)
{
    \Event::listen('backend.header.create', function () use ($paths) {
        return $paths;
    }, $priority);
}

/**
 * @param $paths
 * @param int $priority
 */
function register_header_elements($paths, $priority = 1)
{
    \Event::listen('frontend.header.create', function () use ($paths) {
        return $paths;
    }, $priority);
}

/**
 * @param $paths
 * @param int $priority
 */
function register_footer_elements($paths, $priority = 1)
{
    \Event::listen('frontend.footer.create', function () use ($paths) {
        return $paths;
    }, $priority);
}

/**
 * @param $addon_name
 * @param $funcname
 * @param int $priority
 */
function register_plugin_upload_handler($addon_name, $funcname, $priority = 1)
{
    \Event::listen('backend.addons.saveaddoninfo.' . $addon_name, function () use ($funcname) {
        return call_user_func($funcname);
    }, $priority);
}

/**
 * @param $addon_name
 * @param $funcname
 * @param int $priority
 */
function register_plugin_uninstall_handler($addon_name, $funcname, $priority = 1)
{
    \Event::listen('backend.addons.uninstall.' . $addon_name, function () use ($funcname) {
        return call_user_func($funcname);
    }, $priority);
}

/**
 * @param $addon_name
 * @param $funcname
 * @param int $priority
 */
function register_plugin_install_handler($addon_name, $funcname, $priority = 1)
{
    \Event::listen('backend.addons.install.' . $addon_name, function () use ($funcname) {
        return call_user_func($funcname);
    }, $priority);
}

/**
 * @param $content_type
 * @param int $priority
 */
function register_content_type($content_type, $priority = 1)
{
    \Event::listen('cms.content_types.collect', function () use ($content_type) {
        return $content_type;
    }, $priority);
}

/**
 * @param $blocks
 * @param int $priority
 *
 * @description:
 * 'default_title_block'  =>  array(                        <-- Unique name.
 * 'name'  =>  'default_title_block',                      <-- Unique name.
 * 'title' =>  \Lang::get('strings.title'),                <-- The block title as it appears in the blockmanager
 * 'tpl'   =>  array(
 * 'common/blocks/default_title'=>'default'                <-- The tpl file to use for display
 * ),
 * 'model'  =>  '\Widget',                                 <-- The model/class to use for data collection
 * 'action'    => 'getblockdata',                          <-- The action to call for display
 * 'params_action'=>'lists',                               <-- The action to call for editing in the blockmanager
 * 'params_args'   => array('original_filename','id'),     <-- The params to use on the params_action action
 * 'params_tpl'=>  'param_tpls/default_title',             <-- The tpl to use for editing in the blockmanager
 * 'params'    => array('title'=>'Custom block'),          <-- an array of key,value params
 * 'multiple'  =>  true,                                   <-- If this block can be used multiple times in the same layout
 * 'configurable'=> true                                   <-- If this block can be configured
 * ),
 */
function register_content_block($blocks, $priority = 1)
{
    \Event::listen('content.blocks.collect', function () use ($blocks) {
        return $blocks;
    }, $priority);
}

/**
 * @param $positions
 * @param int $priority
 */
function register_menu_positions($positions, $priority = 1)
{
    \Event::listen('menu.positions.collect', function () use ($positions) {
        return $positions;
    }, $priority);
}

/**
 * @param $paths
 * @param $route
 * @param int $priority
 */
function register_help_items($paths, $route, $priority = 1)
{
    \Event::listen($route . '.help', function () use ($paths) {
        return $paths;
    }, $priority);
}

/**
 * @param $shortcode
 * @param $classandfunction
 */
function register_shortcode($shortcode, $classandfunction,$description='')
{
    \Event::listen('collect.registered.shortcodes', function () use ($shortcode,$description) {
        return array('shortcode'=>$shortcode,'description'=>$description);
    }, 1);
    \Shortcode::register($shortcode, $classandfunction);
}

/**
 * @param $layouts
 * @param int $priority
 */
function register_layouts($layouts, $priority = 1)
{
    \Event::listen('blockmanager.layouts.collect', function () use ($layouts) {
        return $layouts;
    }, $priority);
}

/**
 * @param $hookname
 * @param $view
 */
function register_tpl_hook($hookname,$view){
    \Event::listen($hookname,function() use ($view){
        return View::make($view);
    });
}

function register_plugin_route_filter($callback,$priority=1){
    \Event::listen('plugins.filter.load', $callback, $priority);
}
