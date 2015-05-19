<?php
/**
 * @return string
 */
function content_path(){
    return public_path().'/lp-content';
}

/**
 * @return string
 */
function content_url(){
    return home_url().'/lp-content';
}
/**
 * @return string
 */
function files_path(){
    return content_path().'/files';
}

/**
 * @return string
 */
function files_url(){
    return content_url().'/files';
}

/**
 * @param string $asset eg: 'images','sounds','videos','archives' or 'misc'
 * @return string
 */
function asset_path($asset='images'){
    return files_path().'/'.$asset;
}

/**
 * @param string $asset
 * @return string
 */
function asset_url($asset='images'){
    return files_url().'/'.$asset;
}

/**
 * @return string
 */
function plugins_path(){
    return content_path().'/plugins';
}

/**
 * @return string
 */
function plugins_url(){
    return content_url().'/plugins';
}
/**
 * @return string
 */
function themes_path(){
    return content_path().'/themes';
}

/**
 * @return string
 */
function themes_url(){
    return content_url().'/themes';
}
/**
 * @return mixed
 */
function the_theme_path(){
    return \Config::get('themes.current.theme_path');
}

/**
 * @return mixed
 */
function the_views_path(){
    return \Config::get('themes.current.views_path');
}

/**
 * @return string
 */
function the_views_url(){
    return theme_url().'/views';
}

/**
 * @return string
 */
function includes_path(){
    return public_path().'/lp-includes';
}

/**
 * @return string
 */
function packages_path(){
    return includes_path().'/packages';
}

/**
 * @return mixed
 */
function theme_url(){
    return \Config::get('themes.current.theme_url');
}