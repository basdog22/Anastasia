<?php


/*
|--------------------------------------------------------------------------
| Application & Route Filters
|--------------------------------------------------------------------------
|
| Below you will find the "before" and "after" events for the application
| which may be used to do any work before or after a request into your
| application. Here you may also register your custom route filters.
|
*/

App::before(function ($request) {


    \Event::listen("backend.restore.point",function(){
        //This is called each time a plugin or theme is installed. So, we can use it to clear any compiled files
        // and regenerate them for speed and support any changes done by the plugin
        BackupManager::backup();
        Artisan::call('clear-compiled');
        Artisan::call('optimize');
    });

    //if fist time running create the user groups and assign the first user as root
    first_check();

    //Just an example on how to cachebust the routes caching. You can comment it out if you want your feeds to be cached

    register_no_cache_routes(array('FrontController@dofeeds'));

    $locale = \Request::segment(1);
    if($locale!='backend'){
        if (in_array($locale, get_available_locales())) {
            \Session::forget('current_locale');
            \Session::put('current_locale',$locale);
            \App::setLocale($locale);
        } else {
            if(\Session::has('current_locale')){
                \Session::forget('current_locale');
                \Session::put('current_locale',get_default_locale());
                \App::setLocale($locale);
            }
            $locale = null;
        }
        \Event::fire('before.cached.routes');
        \Route::group(['before'=>'cache','after'=>'cache','prefix'=>$locale],function(){
            \Route::get('/', 'FrontController@mainpage');
            \Route::get('feeds/{feedmodel}/{feedtype?}', 'FrontController@dofeeds');
            //register any route handler after user routes.
            Route::any('{slug}', 'FrontController@decide')->where('slug', '(.*)?');
        });
    }

    /**
     * Register the default content blocks
     */
    register_default_content_blocks();

});


App::after(function ($request, $response) {

    if(requested('cc')){
       app_clear_cache();
    }
});

/*
|--------------------------------------------------------------------------
| Authentication Filters
|--------------------------------------------------------------------------
|
| The following filters are used to verify that the user of the current
| session is logged into this application. The "basic" filter easily
| integrates HTTP Basic authentication for quick, simple checking.
|
*/

Route::filter('auth', function () {

    if (Auth::guest()) {
        if (Request::ajax()) {
            return Response::make('Unauthorized', 401);
        } else {
            return Redirect::guest('users/login')->with(
                'message', Lang::get('messages.login_required')
            );
        }
    }
});

Route::filter('isroot', function () {
    $access = true;

    if (Auth::guest()) {
        $access = false;
    } else {
        $access = has_session();
        $user = Sentry::findUserByID(Auth::user()->id);

        if (!$user->hasAccess('root')) {
            $access = false;
        }
    }
    if (!$access) {
        if (Request::ajax()) {
            return Response::make('Unauthorized', 401);
        } else {
            return Redirect::guest('users/login')->with(
                'message', Lang::get('messages.no_access')
            );
        }
    }
});

Route::filter('isadmin', function () {
    $access = true;
    if (Auth::guest()) {
        $access = false;
    } else {
        $access = has_session();
        $user = Sentry::findUserByID(Auth::user()->id);
        if (!$user->hasAccess('admin')) {
            $access = false;
        }
    }
    if (!$access) {
        if (Request::ajax()) {
            return Response::make('Unauthorized', 401);
        } else {
            return Redirect::guest('users/login')->with(
                'message', Lang::get('messages.no_access')
            );
        }
    }
});

Route::filter('iseditor', function () {
    $access = true;
    if (Auth::guest()) {
        $access = false;
    } else {
        $access = has_session();
        $user = Sentry::findUserByID(Auth::user()->id);
        if (!$user->hasAccess('editor')) {
            $access = false;
        }
    }
    if (!$access) {
        if (Request::ajax()) {
            return Response::make('Unauthorized', 401);
        } else {
            return Redirect::guest('users/login')->with(
                'message', Lang::get('messages.no_access')
            );
        }
    }
});

Route::filter('isauthor', function () {
    $access = true;
    if (Auth::guest()) {
        $access = false;
    } else {
        $access = has_session();
        $user = Sentry::findUserByID(Auth::user()->id);
        if (!$user->hasAccess('author')) {
            $access = false;
        }
    }
    if (!$access) {
        if (Request::ajax()) {
            return Response::make('Unauthorized', 401);
        } else {
            return Redirect::guest('users/login')->with(
                'message', Lang::get('messages.no_access')
            );
        }
    }
});
Route::filter('ismember', function () {
    $access = true;
    if (Auth::guest()) {
        $access = false;
    } else {
        $access = has_session();
        $user = Sentry::findUserByID(Auth::user()->id);
        if (!$user->hasAccess('member')) {
            $access = false;
        }
    }
    if (!$access) {
        if (Request::ajax()) {
            return Response::make('Unauthorized', 401);
        } else {
            return Redirect::guest('users/login')->with(
                'message', Lang::get('messages.no_access')
            );
        }
    }
});

Route::filter('honeypot',function(){
    if(\Session::has('honeypot')){

        $honeypot = requested('hn_pt');
        if(trim($honeypot)){
            App::abort('404','');
        }
        \Session::forget('honeypot');
    }
});

Route::filter('auth.basic', function () {
    return Auth::basic();
});

/*
|--------------------------------------------------------------------------
| Guest Filter
|--------------------------------------------------------------------------
|
| The "guest" filter is the counterpart of the authentication filters as
| it simply checks that the current user is not logged in. A redirect
| response will be issued if they are, which you may freely change.
|
*/

Route::filter('guest', function () {
    if (Auth::check()) return Redirect::to('/');
});

/*
|--------------------------------------------------------------------------
| CSRF Protection Filter
|--------------------------------------------------------------------------
|
| The CSRF filter is responsible for protecting your application against
| cross-site request forgery attacks. If this special token in a user
| session does not match the one given in this request, we'll bail.
|
*/



Route::filter('plugins', function () {
    $res  = \Event::fire('plugins.filter.load');
    $message = '';
    foreach($res as $result){
        if($result){
            $message .= $result."<br/>";
        }
    }
    if(trim($message)){
        return \Redirect::back()->withMessage($message);
    }
});

Route::filter('csrf', function () {
    $token = Request::ajax() ? Request::header('X-CSRF-Token') : Input::get('_token');

    if (Session::token() !== $token) {
        throw new Illuminate\Session\TokenMismatchException;
    }
});

Route::filter('cache', function($route, $request, $response = null)
{
    $no_cache = \Config::get('routes.no_cache');
    $current_route = \Route::getCurrentRoute()->getActionName();
    if(!in_array($current_route,$no_cache)){
        $key = 'route-'.Str::slug(Request::url());
        if(is_null($response) && Cache::has($key))
        {
            return Cache::get($key);
        }
        elseif(!is_null($response) && !Cache::has($key))
        {
            Cache::put($key, $response->getContent(), page_cache_time());
        }
    }

});
App::error(function ($e, $code) {
    switch($code){
        case 403:
            inform_admin(array('text'=>$e->getMessage()));
        case 404:
            inform_admin(array('text'=>$e->getMessage()));
        case 500:
            inform_admin(array('text'=>$e->getMessage()));
            break;
    }

});
