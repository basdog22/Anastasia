<?php

/**
 * Class MenusController
 */
class MenusController extends BackendbaseController {
    /**
     * @var string
     */
    protected $layout = 'layouts.backend.laradmin';

    /**
     * Shows the menus screen
     */
    public function menus(){
        add_breadcrumb(trans('strings.menus'),url('backend/menus'));
        $menus = \Menu::whereIsRoot()->sortOrder()->get();
        $this->layout->content = \View::make('backend/menus/menus')->with('menus',$menus);
    }

    /**
     * Reorders and rebuilds the menu tree
     *
     * @param bool $order
     * @return \Illuminate\Http\JsonResponse
     */
    public function menureorder($order=false){
        $callback = $order;
        $order = ($order)?$order:requested('menu_order');
        foreach($order as $k=>$ord){
            $current_menu = \Menu::find($ord['id']);
            if(!$callback){
                $current_menu->parent_id=null;
                $current_menu->sort = $k;
                $current_menu->save();
            }
            if(isset($ord['children'])){
                foreach($ord['children'] as $key=>$kids){
                    $kid = \Menu::find($kids['id']);
                    $kid->parent_id = $current_menu->id;
                    $kid->sort = $key;
                    $kid->save();
                    if(isset($kids['children'])){
                        $this->menureorder($ord['children']);
                    }
                }
            }
        }
        if(!$callback){
            return \Response::json(array('type' => 'success', 'text' => t('messages.sort_saved')));
        }
    }
    /**
     * Shows the new menu dialog
     *
     * @return mixed
     */
    public function newmenu(){
        $positions = \Config::get('theme.menus');
        $menus = \Menu::whereIsRoot()->get();
//        ll($positions);
        $content = \Config::get('content_types');
        $menus = \View::make('backend/taxonomies/options')->withTaxonomies($menus);
        if (Request::ajax()){
            return \View::make('backend/menus/new')->withPositions($positions)->withMenus($menus)->withContent($content);
        }else{
            $this->layout->content = \View::make('backend/menus/new')->withPositions($positions)->withMenus($menus)->withContent($content);
        }
    }

    /**
     * Adds a menu
     *
     * @return mixed
     */
    public function addmenu(){

        $menu = new \Menu;
        $menu->name = requested('name');
        $menu->title = requested('title');
        $menu->url = requested('url');
        $model = (requested('model'))?requested('model'):'';

        $menu->model = $model;
        $menu->link_text = requested('link_text');
        $menu->link_target = requested('link_target');
        $menu->link_attr = requested('link_attr');
        $menu->link_css = requested('link_css');
        $menu->link_class = requested('link_class');
        $menu->position = (requested('position'))?requested('position'):'';
        $menu->parent_id = (int) requested("parent");
        if($menu->save()){
            return \Redirect::to('backend/menus')->withMessage($this->notifyView(t('messages.menu_created'),'success'));
        }
        return \Redirect::to('backend/menus')->withMessage($this->notifyView(t('messages.error_occured'),'danger'));
    }

    /**
     * Shows the menu edit dialog
     *
     * @param $menuid
     * @return mixed
     */
    public function editmenu($menuid){
        $positions = \Config::get('theme.menus');
        $menu = \Menu::find($menuid);
        $content = \Config::get('content_types');
        if (\Request::ajax()){
            return \View::make('backend/menus/edit')->with('menu',$menu)->withPositions($positions)->withContent($content);
        }else{
            $this->layout->content = \View::make('backend/menus/edit')->with('menu',$menu)->withPositions($positions)->withContent($content);
        }

    }

    /**
     * Updates the menu
     *
     * @return mixed
     */
    public function savemenu(){
        $menu = \Menu::find(requested('menuid'));
        $menu->name = requested('name');
        $menu->title = requested('title');
        $menu->url = requested('url');
        $model = (requested('model'))?requested('model'):'';
        $menu->model = $model;
        $menu->link_text = requested('link_text');
        $menu->link_target = requested('link_target');
        $menu->link_attr = requested('link_attr');
        $menu->link_css = requested('link_css');
        $menu->link_class = requested('link_class');
        $menu->position = (requested('position'))?requested('position'):'';
        if($menu->save()){
            return \Redirect::to('backend/menus')->withMessage($this->notifyView(t('messages.menu_saved'),'success'));
        }
        return \Redirect::to('backend/menus')->withMessage($this->notifyView(t('messages.error_occured'),'danger'));
    }

    /**
     * Deletes the menu specified
     *
     * @param $menuid
     * @return mixed
     */
    public function delmenu($menuid){
        $menu = \Menu::find($menuid);
        if($menu->delete()){
            return \Redirect::to('backend/menus')->withMessage($this->notifyView(t('messages.menu_deleted')));
        }
        return \Redirect::to('backend/menus')->withMessage($this->notifyView(t('messages.error_occured'),'danger'));
    }
}