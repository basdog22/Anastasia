<?php

namespace Plugins\posts\controllers;

use \Plugins\posts\models\Post;
use \Plugins\posts\models\Category;

/**
 * Class PostsbackendController
 * @package Plugins\posts\controllers
 */
class PostsbackendController extends \BackendbaseController{
    /**
     * @var string
     */
    protected $layout = 'layouts.backend.laradmin';

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function dashboard(){
        return \Redirect::to('/backend/posts/list');
    }

    /**
     *
     */
    public function listposts(){
        add_breadcrumb(trans('posts::strings.posts'),url('backend/posts/list'));
        $posts = Post::ofNormal()->paginate(\Config::get('settings.cms.paging'));

        $this->layout->content = \View::make('posts::posts_list')->withPosts($posts);
    }

    /**
     *
     */
    public function listpublished(){
        add_breadcrumb(trans('posts::strings.posts'),url('backend/posts/list'));
        add_breadcrumb(trans('strings.published'),url('backend/posts/list/published'));
        $posts = Post::ofPublished()->paginate(\Config::get('settings.cms.paging'));

        $this->layout->content = \View::make('posts::posts_list')->withPosts($posts);
    }

    /**
     *
     */
    public function listdrafts(){
        add_breadcrumb(trans('posts::strings.posts'),url('backend/posts/list'));
        add_breadcrumb(trans('strings.draft'),url('backend/posts/list/draft'));
        $posts = Post::ofDraft()->paginate(\Config::get('settings.cms.paging'));

        $this->layout->content = \View::make('posts::posts_list')->withPosts($posts);
    }

    /**
     *
     */
    public function listtrashed(){
        add_breadcrumb(trans('posts::strings.posts'),url('backend/posts/list'));
        add_breadcrumb(trans('posts::strings.trashed'),url('backend/posts/list/trash'));
        $posts = Post::ofTrash()->paginate(\Config::get('settings.cms.paging'));

        $this->layout->content = \View::make('posts::posts_list')->withPosts($posts);
    }

    /**
     *
     */
    public function newpost(){

        $post = new Post;
        $post->status = 3;
        $post->user_id = \Auth::id();
        $post->save();

        $tags = $post->tagNames();

        add_breadcrumb(trans('posts::strings.posts'),url('backend/posts/list'));
        add_breadcrumb(trans('strings.edit'),url('backend/posts/edit/'.$post->id));

        $categories = Category::whereIsRoot()->get();
        $categories = \View::make('backend/taxonomies/options')->withTaxonomies($categories);
        $this->layout->content = \View::make('posts::new_post')->withPost($post)->withCategories($categories)->withTags($tags);
    }

    /**
     * @param $postid
     * @return mixed
     */
    public function edit($postid){
        $post = Post::find($postid);
        $image = $post->image;
        $tags = $post->tagNames();
        $categories = Category::whereIsRoot()->get();
        $categories = \View::make('backend/taxonomies/options')->withTaxonomies($categories);
        if (\Request::ajax()){
            return \View::make('posts::new_post')->withPost($post)->withCategories($categories)->withTags($tags);
        }else{
            $this->layout->content = \View::make('posts::new_post')->withPost($post)->withCategories($categories)->withTags($tags);
        }

    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function attachtag(){
        $postid = (int) requested('post_id');
        $post = Post::find($postid);
        $tags = requested('tags');
        if(is_null($post)){
            return \Response::json(array('Error'));
        }
        foreach($tags as $tag){
           $post->tag($tag);
        }

        return \Response::json($post->tagNames());
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function dettachtag(){
        $postid = (int) requested('post_id');
        $post = Post::find($postid);
        $tag = requested('tag');
        if(is_null($post)){
            return \Response::json(array('Error'));
        }
        $post->untag($tag);

        return \Response::json($post->tagNames());
    }

    /**
     * @param $postid
     * @return mixed
     */
    public function restore($postid){
        $post = Post::find($postid);
        if(is_null($post)){
            return \Redirect::to('/backend/posts/list')->withMessage($this->notifyView(t('posts::messages.no_post_found'),'error'));
        }
        $post->status = 0;
        $post->save();
        return \Redirect::to('/backend/posts/list')->withMessage($this->notifyView(t('posts::messages.post_restored'),'success'));
    }

    /**
     * @param $postid
     * @return mixed
     */
    public function delete($postid){
        $post = Post::find($postid);
        if(is_null($post)){
            return \Redirect::to('/backend/posts/list')->withMessage($this->notifyView(t('posts::messages.no_post_found'),'error'));
        }
        if($post->status==2){
            $post->delete();
            return \Redirect::to('/backend/posts/list')->withMessage($this->notifyView(t('posts::messages.post_deleted'),'success'));
        }else{
            $post->status=2;
            $post->save();
            return \Redirect::to('/backend/posts/list')->withMessage($this->notifyView(t('posts::messages.post_trushed'),'success'));
        }
    }

    public function bulkdelete(){
        $ids = requested('ids');
        foreach($ids as $id){
            $id = (int) $id;
            $post = Post::find($id);
            if($post->status==2){
                $post->delete();
            }else{
                $post->status=2;
                $post->save();
            }
        }
        return \Response::json(array('type' => 'success', 'text' => t('posts::messages.posts_deleted')));
    }

    /**
     * @return mixed
     */
    public function create(){
        if(requested('postid')){
            $post = Post::find((int) requested('postid'));
        }else{
            $post = new Post;
        }
        $imagepath = requested('image_id');

        $image = \ImageAsset::wherePath(str_replace(home_url().'/lp-content/files/',"",$imagepath))->first();
        $post->image_id = (isset($image->id))?$image->id:0;
        $a = (requested('category_id'))?$post->category_id = (int) requested('category_id'):0;
        $post->title = strip_tags(requested('title'));
        $a = (requested('slug'))?$post->slug = requested('slug'):'';
        $post->content = requested('content');
        $post->status = (requested('status'))?requested('status'):0;
        $post->user_id = \Auth::user()->id;

        $metas = requested('metas');
        $newmeta = requested('newmetas');


        //update old
        update_meta($metas,$post,true);
        //add new
        update_meta($newmeta,$post);


        foreach($metas as $name=>$value){
            if($name=='new'){
                $post->setMeta($value['name'], $value['value']);
            }else{

            }
        }

        $post->save();
        if(trim(requested('tags'))){
            $tags = explode(",",requested('tags'));
            foreach($tags as $tag){
                $post->tag($tag);
            }
        }
        if(requested('saveclose')){
            return \Redirect::to('/backend/posts/list')->withMessage($this->notifyView(t('posts::messages.post_saved'),'success'));
        }

        return \Redirect::to('/backend/posts/edit/'.$post->id)->withMessage($this->notifyView(t('posts::messages.post_saved'),'success'));

    }
}