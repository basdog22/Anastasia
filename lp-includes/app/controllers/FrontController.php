<?php

/**
 * Class FrontController
 */
class FrontController extends FrontendbaseController
{

    /*
    |--------------------------------------------------------------------------
    | Default Home Controller
    |--------------------------------------------------------------------------
    |
    | You may wish to use controllers instead of, or in addition to, Closure
    | based routes. That's great! Here is an example controller method to
    | get you started. To route to this controller, just add the route:
    |
    |	Route::get('/', 'HomeController@showWelcome');
    |
    */
    /**
     * @var string
     */
    protected $layout = 'layouts.default.theme';


    /**
     * Show the default controller action
     *
     * @return mixed
     */
    public function mainpage($controller = false, $action = false, $params = array())
    {
        // Run controller and method
        if (!$controller)
            $controller = \Config::get('settings.cms.main_controller');
        if (!$action)
            $action = \Config::get('settings.cms.main_controller_function');
        Session::forget('gridsrun');

        $this->loadLayout();
        $params['grids'] = $this->grids;
        $app = app();
        $controller = $app->make($controller);
        return $controller->callAction($action, $params);

    }

    public function decide($url = '/')
    {
        //Thanks October! :)
        if ($url === null) {
            $url = Request::path();
        }
        if (!strlen($url)) {
            $url = '/';
        }

        //check if page
        $page = \Page::whereSlug($url)->first();
        if (!is_null($page)) {
            if ($page->status == "0" && !is_author(\Auth::user())) {
                //it is a page but it is set as hidden and the user is not allowed to see
                $page = \Page::whereSlug('404-page')->first();
                return $this->showpage($page);
            } else {
                //show the page
                return $this->showpage($page);
            }
        }

        //get all content types and check if this slug is any of them
        $content = $this->getContentTypesFlat();
        foreach ($content as $type) {
            $model = $type['model'];
            $controller = $type['controller'];
            $page = $model::whereSlug($url)->first();
            if (!is_null($page)) {
                //we found the content item. Lets see if it supports hide and show
                if (isset($page->status)) {
                    //the content supports hidden items
                    if ($page->status == "0" && !is_author(\Auth::user())) {
                        //the item is hidden but the user has no rights
                        return $this->load404();

                    }
                }
                //everything is ok. Show the content item with it's controller's showpage action
                $app = app();
                $controller = $app->make($controller);
                return $controller->callAction('showpage', array($page));
            }
        }

        //No content found yet
        //lets see if the visitor asked for a tag page
        $tagbase = \Config::get('settings.cms.tagbase');
        $url = explode("/", $url);
        if ($url[0] == $tagbase) {
            //a tag is asked
            $tag = \Tag::whereSlug($url[1])->first();
            return $this->showtagpage($tag);

        }

        //No content...
        //Load a 404 page.
        return $this->load404();
    }

    public function load404()
    {
        $page = \Page::whereSlug('404-page')->first();

        $result = (string)$this->showpage($page);
        $theme = \View::make('layouts.default.theme');
        return Response::make($theme, 404);
    }

    public function showtagpage($tag)
    {
        $content = $this->getContentTypesFlat();
        $list = array();
        add_breadcrumb(get_config_value('brand'), url('/'));
        add_breadcrumb($tag->name, url(URL::current()));
        foreach ($content as $type) {
            $model = $type['model'];
            $content = $type['type'];
            $uses = class_uses(new $model);
            if (isset($uses['Conner\Tagging\TaggableTrait'])) {
                $content = $model::withAnyTag(array($tag->name))->get();
                foreach ($content as $item) {
                    $item->content_type = $type['type'];
                    $list[] = $item;
                }
            }
        }
        $list = Paginator::make($list, count($list), \Config::get('settings.cms.paging'));
        \Session::forget('gridsrun');
        $this->setPageTitle($tag->name);
        $this->loadLayout($list, 'tag_template', 'tagpage');
        //$this->layout->content =  \View::make('tag_template')->withItems($list);
    }

    public function showpage($page)
    {
        add_breadcrumb(get_config_value('brand'), url('/'));
        $subs = explode("/", $page->slug);
        if (count($subs)) {
            $subslug = '';

            foreach ($subs as $sub) {
                $subslug .= '/' . $sub;
                $subslug = ltrim($subslug, "/");
                $subpage = \Page::whereSlug($subslug)->first();
                if(!is_null($subpage)){
                    add_breadcrumb($subpage->title, url($subpage->slug));
                }

            }
        } else {
            add_breadcrumb($page->title, URL::current());
        }


        if ($page instanceof \Page) {

        } else {
            $pageid = (int)$page;
            $page = \Page::find($pageid);
        }

        \Session::forget('gridsrun');
        $this->setPageTitle($page->title);
        $this->setPageDescription($page->meta('meta_description'));
        $this->setPageKeywords($page->meta('meta_keywords'));

        $this->loadLayout($page, 'page_template');
//        $this->layout->content =  \View::make('page_template')->withPage($page);

    }


    public function dofeeds($from, $type = 'atom')
    {



        if($from=='blog'){
            $from= 'posts';
        }
        error_reporting(0);
        $feed_items = get_config_value('feed_items_num');
        $model = ucfirst(str_singular($from));
        try {
            if (class_exists($model)) {
                $feed = $model::$type($feed_items);
            } else {
                $model = "\\Plugins\\$from\\models\\$model";
                if (class_exists($model)) {
                    $feed = $model::$type($feed_items);
                } else {

                    return $this->load404();
                }

            }

        } catch (Exception $e) {

            return $this->load404();
        }
        return $feed;


    }

    /**
     * The default front controller action
     */
    public function anastasia()
    {
        $this->layout->content = \View::make('mainpage');
        $this->setPageTitle('Anastasia Publishing Platform');
    }
}
