<?php

/**
 * Class FrontendbaseController
 */
class FrontendbaseController extends Controller
{

    /**
     * Setup the layout used by the controller.
     *
     * @return void
     */
    protected function setupLayout()
    {
        if (!is_null($this->layout)) {
            $this->layout = View::make($this->layout);
        }

        \Breadcrumbs::setListElement('ol');
        \Breadcrumbs::addCssClasses('breadcrumb');
        \Breadcrumbs::removeCssClasses('breadcrumbs');
        \Breadcrumbs::setDivider(null);
        $this->getThemeConfig();

        $navtools = Event::fire('frontend.navbar.create');
        $navtools_html = $footeritems_html = $headeritems_html = '';
        \View::share('navbar', '');
        \View::share('header', '');
        \View::share('footer', '');
        foreach ($navtools as $w) {
            foreach ($w as $o) {
                $navtools_html .= View::make($o);
            }
        }

        $footeritems = Event::fire('frontend.footer.create');

        foreach ($footeritems as $w) {
            foreach ($w as $o) {
                $footeritems_html .= View::make($o);
            }
        }

        $headeritems = Event::fire('frontend.header.create');


        foreach ($headeritems as $w) {
            foreach ($w as $o) {
                $headeritems_html .= View::make($o);
            }
        }

        Config::set('theme.menus', Event::fire('menu.positions.collect'));

        //get the layout for the current route
        //load the default if no layout specified


//        ll($footeritems_html);

        if (\View::exists('anastasia::navbar')) {
            $navtools = \View::make('navbar')->with('navtools', $navtools_html);

            \View::share('navbar', (string)$navtools);
        }
        if (\View::exists('header')) {
            $headeritems = \View::make('header')->with('headeritems', $headeritems_html);
            \View::share('header', (string)$headeritems);
        }
        if (\View::exists('footer')) {
            $footeritems = \View::make('footer')->with('footeritems', $footeritems_html);
            \View::share('footer', (string)$footeritems);
        }



    }

    /**
     * Load the layout for this route
     */
    public function loadLayout($special = false, $tpl = false, $force_layout = false)
    {
        error_reporting(0);
        $return = null;
        if ($special) {
            if (is_object($special)) {

                $specialid = $special->id;
            } else {
                $specialid = (int)$special;
            }
        }

        if (!\Session::has('gridsrun')) {

            $route = \Route::current()->getActionName();
            $layouts = \Event::fire('blockmanager.layouts.collect');
            $currentLayout = 'default';
            $grids = array();
            foreach ($layouts as $v) {
                foreach ($v as $key => $layout) {
                    if (isset($layout['routes']) && in_array($route, $layout['routes'])) {
                        if ($route == 'FrontController@decide' && isset($layout['object_id']) && $layout['object_id'] == $specialid) {


                            if (is_null($special->layout)) {
                                $currentLayout = $layout['name'];

                                break;
                            } else {
                                $currentLayout = $special->layout;
                                break;
                            }

                        } elseif ($route != 'FrontController@decide') {

                            $currentLayout = $layout['name'];
                            break;
                        } elseif ($route == 'FrontController@decide') {
                            $currentLayout = $special->layout;
                        }

                    }
                }

            }
            if ($force_layout) {
                $currentLayout = $force_layout;
            }
            foreach($layouts as $v){
                foreach ($v as $key => $layout) {
                    if($layout['name']==$currentLayout){
                        $thelayout = $layout;
                    }
                }
            }
            //lets load the positions too.
            $positions = get_theme_positions($thelayout);
            //now for each position we load the blocks
            foreach ($positions as $k => $position) {
                if ($position['is_default']) {
                    //this position is default (header,footer or something else).
                    //use the default layout here
                    $uselayout = 'default';
                } else {
                    //use the current layout
                    $uselayout = $currentLayout;
                }
                if(isset($thelayout['positions']) && !$position['is_default']){
                    $positions[$k]['positions'] = $thelayout['positions'];
                    $positions[$k]['tpl']   =   $thelayout['positions_tpl'];
                }
                foreach ($position['grids'] as $grid_id => $grid) {
                    //get blocks for each grid

                    $blocks = get_blocks($grid_id, $uselayout, $k);

                    $positions[$k]['grids'][$grid_id]['blocks'] = $blocks;

                }
            }
            //now we need to run and assign to layout
            foreach ($positions as $k => $position) {

                if(isset($position['tpl'])){
//                    ll($position);
                    foreach ($position['grids'] as $grid_id => $grid) {
                        $row = '';

                        foreach ($grid['blocks'] as $block) {

                            $block_info = get_block_info($block->name);

                            $model = (isset($block_info['model'])) ? $block_info['model'] : false;
                            $action = (isset($block_info['action'])) ? $block_info['action'] : false;
                            $params = ($block->params) ? $block->params : $block_info['params'];
                            if ($model && $action) {
                                $block_data = $model::$action($params);

                                $row .= \View::make($block->tpl)->withBlockdata($block_data)->withShortcodes();
                            } else {
                                if ($block_info['name'] == 'default_main_content') {
                                    if ($special && $tpl) {
                                        $maincontent = (string)\View::make($tpl)->withObject($special);
                                    } else {
                                        $maincontent = '';
                                    }
                                    $row .= $return = \View::make($block->tpl)->withMaincontent($maincontent)->withShortcodes();
                                } else {
                                    $row .= \View::make($block->tpl)->withShortcodes();
                                }

                            }


                        }
                        $customgrids[$k][$grid_id] = $row;
                    }

                  $body = (string) \View::make($position['tpl'])->withGrids($customgrids);

                  $grids[$k] = $body;
                }else{
                    foreach ($position['grids'] as $grid_id => $grid) {
                        $row = '';

                        foreach ($grid['blocks'] as $block) {
                            $block_info = get_block_info($block->name);

                            $model = (isset($block_info['model'])) ? $block_info['model'] : false;
                            $action = (isset($block_info['action'])) ? $block_info['action'] : false;
                            $params = ($block->params) ? $block->params : $block_info['params'];
                            if ($model && $action) {
                                $block_data = $model::$action($params);

                                $row .= \View::make($block->tpl)->withBlockdata($block_data)->withShortcodes();
                            } else {
                                if ($block_info['name'] == 'default_main_content') {
                                    if ($special && $tpl) {
                                        $maincontent = (string)\View::make($tpl)->withObject($special);
                                    } else {
                                        $maincontent = '';
                                    }
                                    $row .= $return = \View::make($block->tpl)->withMaincontent($maincontent)->withShortcodes();
                                } else {
                                    $row .= \View::make($block->tpl)->withShortcodes();
                                }

                            }


                        }
                        $grids[$k][$grid_id] = $row;
                    }
                }

            }
            Session::put('gridsrun', true);
            \View::share('grids', $grids);
        } else {

            Session::forget('gridsrun');
        }
        return $return;
    }

    /**
     * Sets the page title
     *
     * @param $title
     */
    public function setPageTitle($title)
    {
        Config::set('cms.page.title', $title);
    }

    /**
     * Sets the page meta description
     *
     * @param $descr
     */
    public function setPageDescription($descr)
    {
        Config::set('cms.page.description', mb_substr(strip_tags($descr), 0, 155, 'utf-8'));
    }

    /**
     * Sets the page meta keywords
     *
     * @param $keywords
     */
    public function setPageKeywords($keywords)
    {
        Config::set('cms.page.keywords', $keywords);
    }

    /**
     * Loads the theme config
     */
    public function getThemeConfig()
    {
        $theme = Config::get('themes.current');

        //check if the theme has configurable settings
        $xml = File::get($theme['theme_path'] . '/theme.xml');
        $xml = simplexml_load_string($xml, null, LIBXML_NOCDATA);

        if ($xml->configurator) {
            foreach ($xml->configurator->children() as $k => $item) {
                $config[$item->getName()] = array(
                    'type' => (string)$item->attributes()['type'],
                    'name' => $item->getName(),
                    'value' => (string)$item
                );
            }
            Config::set('themes.current.config', $config);

        }

    }

    function addcomment()
    {
        try {

            $objectid = (int)requested('object_id');
            $model = requested('object_type');
            $title = requested('title');
            $content = requested('content');
            $user_id = \Auth::id();
            $parent_id = (trim(requested('parent_id')))?(int) requested('parent_id'):null;
            $comment = new \Comment;
            $comment->user_id = $user_id;
            $comment->title = le($title);
            $comment->body = le($content);
            $comment->status = (auto_approve_comments()) ? 1 : 0;
            $object = $model::find($objectid);
            $comment->parent_id = $parent_id;
            $object->comments()->save($comment);

            return \Redirect::back()->withMessage(t('messages.comment_submited'));
        } catch (Exception $e) {
            return \Redirect::back()->withMessage(t('messages.comment_failed'));
        }
    }

    /**
     * Returns the flat array of the content types
     * @return array
     */
    public function getContentTypesFlat()
    {
        $content = Config::get('content_types');
        $types = array();
        foreach ($content as $k => $v) {
            foreach ($v as $o) {
                $types[] = $o;
            }
        }
        return $types;
    }

}
