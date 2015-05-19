<?php

/**
 * Class PagesController
 */
class PagesController extends BackendbaseController {
    /**
     * @var string
     */
    protected $layout = 'layouts.backend.laradmin';

    public function pages(){
        add_breadcrumb(trans('strings.pages'),url('backend/pages'));
        $pages = \Page::orderBy('created_at','desc')->paginate(\Config::get('settings.cms.paging'));
        $this->layout->content = \View::make('backend/pages/list')->withPages($pages);
    }

    public function newpage(){
        $post = new \Page;
        $layouts = \Event::fire('blockmanager.layouts.collect');
        add_breadcrumb(trans('strings.pages'),url('backend/pages'));
        add_breadcrumb(trans('strings.edit'),url('backend/pages/edit/'.$post->id));
        $this->layout->content = \View::make('backend/pages/new')->withPost($post)->withLayouts($layouts);
    }

    public function editpage($pageid){
        $checkformultiple = explode(",",$pageid);
        $layouts = \Event::fire('blockmanager.layouts.collect');
        $pages = array();
        if(isset($checkformultiple[1])){
            foreach($checkformultiple as $id){
                $id = (int) $id;
                $page = \Page::find($id);
                $pages[] = $page;
            }
            add_breadcrumb(trans('strings.pages'),url('backend/pages'));
            add_breadcrumb(trans('strings.bulk_edit'),url('backend/pages/edit/'));
            $this->layout->content = \View::make('backend/pages/bulk')->withPages($pages)->withLayouts($layouts);
        }else{
            $pageid = (int) $pageid;
            $post = \Page::find($pageid);

            add_breadcrumb(trans('strings.pages'),url('backend/pages'));
            add_breadcrumb(trans('strings.edit'),url('backend/pages/edit/'.$post->id));
            if(\Request::ajax()){
                return \View::make('backend/pages/new')->withPost($post)->withLayouts($layouts);
            }else{
                $this->layout->content = \View::make('backend/pages/new')->withPost($post)->withLayouts($layouts);
            }

        }

    }

    public function addpage(){
        if(requested('pageid')){
            $post = \Page::find((int) requested('pageid'));
        }else{
            $post = new \Page;
        }

        $post->title = strip_tags(requested('title'));
        $a = (requested('slug'))?$post->slug = rtrim(requested('slug'),'/'):'';
        $post->layout = (requested('layout'))?requested('layout'):null;
        $post->content = requested('content');
        $post->status = (requested('status'))?requested('status'):0;
        if($post->save()){}else{
            return \Redirect::to('/backend/pages/edit/'.$post->id)->withMessage($this->notifyView(t('messages.error_occured'),'danger'));
        }
        $metas = requested('metas');
        $newmeta = requested('newmetas');
        //update old
        update_meta($metas,$post,true);
        //add new
        update_meta($newmeta,$post);
        if(requested('saveclose')){
            return \Redirect::to('/backend/pages')->withMessage($this->notifyView(t('messages.page_saved'),'success'));
        }
        return \Redirect::to('/backend/pages/edit/'.$post->id)->withMessage($this->notifyView(t('messages.page_saved'),'success'));

    }

    public function bulksavepages(){
        $pages = requested('pages');
        foreach($pages as $page_id=>$posted){
            $page = \Page::find($page_id);
            $page->title = $posted['title'];
            $a = ($posted['slug'])?$page->slug = $posted['slug']:'';
            $page->content = $posted['content'];
            $page->status = ($posted['status'])?$posted['status']:0;
            $page->layout = ($posted['layout'])?$posted['layout']:null;
            if(!$page->save()){
                return \Redirect::to('/backend/pages')->withMessage($this->notifyView(t('messages.error_occured'),'danger'));
            }
        }
        return \Redirect::to('/backend/pages')->withMessage($this->notifyView(t('messages.pages_saved'),'success'));
    }

    public function bulkdeletepages(){
        $ids = requested('ids');
        foreach($ids as $id){
            $id = (int) $id;
            $page = \Page::find($id);
            if(!$page->delete()){
                return \Response::json(array('type' => 'danger', 'text' => t('messages.error_occured')));
            }
        }
        return \Response::json(array('type' => 'success', 'text' => t('messages.pages_deleted')));
    }

    public function bulkeditpages(){
        $ids = requested('ids');
        return \Response::json(array('type' => 'success', 'text' => redirect_script(url('backend/pages/edit/'.implode(",",$ids)))));
    }

    public function delpage($pageid){
        $pageid = (int) $pageid;
        $post = Page::find($pageid);
        if(is_null($post)){
            return \Redirect::to('/backend/pages')->withMessage($this->notifyView(t('messages.no_page_found'),'danger'));
        }
        if($post->status==0){
            $post->delete();
            return \Redirect::to('/backend/pages')->withMessage($this->notifyView(t('messages.page_deleted'),'success'));
        }else{
            $post->status=0;
            $post->save();
            return \Redirect::to('/backend/pages')->withMessage($this->notifyView(t('messages.page_trushed'),'success'));
        }
    }
}