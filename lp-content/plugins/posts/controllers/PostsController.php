<?php

namespace Plugins\posts\controllers;

use Plugins\posts\models\Post;
use Plugins\posts\models\Category;

/**
 * Class PostsController
 * @package Plugins\posts\controllers
 */
class PostsController extends \FrontendbaseController{
    /**
     * @var string
     */
    protected $layout = 'layouts.default.theme';

    /**
     *
     */


    public function __construct(){

    }

    public function mainpage($params=array()){
        add_breadcrumb(get_config_value('brand'), url('/'));
        add_breadcrumb('Blog', url('blog'));
        if(isset($params['grids'])){
            \View::share('grids',$params['grids']);
        }
        $posts = Post::ofPublished()->orderBy('created_at','DESC')->paginate(get_config_value('paging'));
        \Session::forget('gridsrun');
        $this->loadLayout($posts,'posts::list','posts_index');
        $this->setPageTitle('Blog');
    }

    public function showpage($post){
        add_breadcrumb(get_config_value('brand'), url('/'));
        add_breadcrumb('Blog', url('blog'));
        if($post instanceof \Plugins\posts\models\Post){

        }else{
            $postid = (int) $post;
            $post = \Plugins\posts\models\Post::find($postid);

        }
        add_breadcrumb($post->title, url(\URL::current()));
        \Session::forget('gridsrun');
        $this->loadLayout($post,'posts::page','posts_singlepost');
        $this->setPageTitle($post->title);
//        $this->layout->content =  \View::make('page_template')->withPage($page);

    }
}