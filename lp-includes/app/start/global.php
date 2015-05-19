<?php

require includes_path().'/default_shortcodes.php';



ClassLoader::addDirectories(array(
    app_path() . '/commands',
    app_path() . '/controllers',
    app_path() . '/models',
    app_path() . '/database/seeds',
));

//add the htm extension
View::addExtension('htm', 'blade');

//get settings
$settings = Settings::where("autoload",'=','1')->get();
$auto_settings = array();
foreach ($settings as $setting) {
    $auto_settings[$setting->namespace][$setting->setting_name] = $setting->setting_value;
}
\Breadcrumbs::setListElement('ol');
\Breadcrumbs::addCssClasses('breadcrumb');
$auto_settings['cms']['url'] = home_url();
$auto_settings['cms']['includes'] = includes_path();
$auto_settings['cms']['content'] = content_path();
$auto_settings['cms']['rel_includes'] = home_url() . "/lp-includes";
$auto_settings['cms']['rel_content'] = home_url() . "/lp-content";




Config::set("settings", $auto_settings);
register_layouts(array(
    array(
        'name' => 'default',
        'title' => 'strings.default',
    ),array(
        'name' => 'tagpage',
        'title' => 'strings.tag',
    ),

));



//get the pages and load a layout foreach
$pages = Page::all();
$layouts = array();
foreach ($pages as $page) {
    if($page->layout=='default' || !is_null($page->layout)){
        continue;
    }
    $layouts[] = array(
        'name' => 'page'.$page->id,
        'title' => $page->title,
        'routes' => array(
            'FrontController@decide'
        ),
        'object_id' =>  $page->id
    );
}

register_layouts($layouts);
//load the plugins
$plugins = Plugin::where('installed','=','1')->get();

if (!is_null($plugins)) {
    foreach ($plugins as $addon) {
        //add the plugin directories to the classloader
        ClassLoader::addDirectories(array(
            plugins_path() . "/{$addon->name}/controllers",
            plugins_path() . "/{$addon->name}/models"
        ));

        if (file_exists(plugins_path() . "/{$addon->name}/routes.php")) {
            require plugins_path() . "/{$addon->name}/routes.php";
        }

        //add languages for each plugin
        Lang::addNamespace($addon->name, plugins_path() . "/{$addon->name}/languages/");

        if (file_exists(plugins_path() . "/{$addon->name}/hooks.php")) {
            require plugins_path() . "/{$addon->name}/hooks.php";
        }

        //add view namespace
        View::addNamespace($addon->name, plugins_path() . "/{$addon->name}/views");


    }
}
//
register_content_type(array(
    array(
    'type' => 'pages', //the content type
    'title' => t('strings.pages'), //the title to display
    'slug' => 'pages', //the slug that will be prepend on the item slug. eg: /page/about-us
    'model' => 'Page', //the model to pull items from
    'controller' => 'PagesController' //the plugin controller
    )
),0);

//collect content types
Config::set('content_types', Event::fire('cms.content_types.collect'));

//load the theme
$theme = Theme::ofState(1)->first();

if (is_null($theme)) {
    $theme = Theme::find(1);
}


Config::set('themes.current', $theme->toArray());
Config::set('themes.current.views_path', themes_path() . '/' . $theme->name . '/views');
Config::set('themes.current.languages_path', themes_path() . '/' . $theme->name . '/languages');
Config::set('themes.current.theme_path', themes_path() . '/' . $theme->name);


Config::set('themes.current.theme_url', home_url() . '/lp-content/themes/' . $theme->name . '/views');

//add languages from the theme if any
Lang::addNamespace($theme->name, Config::get('themes.current.languages_path'));

if (file_exists(the_theme_path() . "/functions.php")) {
    include_once the_theme_path() . "/functions.php";
}
//include the theme functions and the hooks
if (file_exists(the_theme_path() . "/hooks.php")) {
    include_once the_theme_path() . "/hooks.php";
}


View::addLocation(Config::get('themes.current.views_path'));
View::addNamespace($theme->name, Config::get('themes.current.views_path'));

register_dashboard_widget(array(
    'backend/widget'
));
/*
|--------------------------------------------------------------------------
| Application Error Logger
|--------------------------------------------------------------------------
|
| Here we will configure the error logger setup for the application which
| is built on top of the wonderful Monolog library. By default we will
| build a basic log file setup which creates a single file for logs.
|
*/

Log::useFiles(storage_path() . '/logs/laravel.log');

/*
|--------------------------------------------------------------------------
| Application Error Handler
|--------------------------------------------------------------------------
|
| Here you may handle any errors that occur in your application, including
| logging them or displaying custom views for specific errors. You may
| even register several error handlers to handle different types of
| exceptions. If nothing is returned, the default error view is
| shown, which includes a detailed stack trace during debug.
|
*/

App::error(function (Exception $exception, $code) {
    Log::error($exception);
});

/*
|--------------------------------------------------------------------------
| Maintenance Mode Handler
|--------------------------------------------------------------------------
|
| The "down" Artisan command gives you the ability to put an application
| into maintenance mode. Here, you will define what is displayed back
| to the user if maintenance mode is in effect for the application.
|
*/

App::down(function () {
    return Response::make("Be right back!", 503);
});

/*
|--------------------------------------------------------------------------
| Require The Filters File
|--------------------------------------------------------------------------
|
| Next we will load the filters file for the application. This gives us
| a nice separate location to store our route and application filter
| definitions instead of putting them all in the main routes file.
|
*/

require includes_path() . '/filters.php';

