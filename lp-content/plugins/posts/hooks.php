<?php

register_dashboard_widget(array(
    'posts::widgets/backend/latestposts',
    'posts::widgets/backend/quickpress'
));

register_dashboard_sidebar_menu(array(
    'posts::sidebar/backend/smenu'
));

register_footer_items(array(
    'posts::tools/footer'
));

register_layouts(array(
    array(
        'name'   =>  'posts_index',
        'title'  =>  'posts::strings.posts_index',
        'routes'  =>  array(
            '\\Plugins\\posts\\controllers\\PostsController@mainpage'
        ),
        'positions' =>  plugins_path().'/posts/positions_list.php',
        'positions_tpl'=>   'posts::list_theme'
    ),
    array(
        'name'   =>  'posts_singlepost',
        'title'  =>  'posts::strings.single_post',
        'routes'  =>  array(
            '\\Plugins\\posts\\controllers\\PostsController@single'
        ),
        'positions' =>  plugins_path().'/posts/positions_single.php',
        'positions_tpl'=>   'posts::single_theme'
    ),
));



register_shortcode('latest_blog_posts',function($shortcode,$content,$object,$c){
    $posts = Plugins\posts\models\Post::whereStatus(1)->orderBy('created_at','desc')->take(5)->get();
    if($posts->count()){
        $html = "<ul class='nav nav-stacked'>";
        foreach($posts as $post){
            $html .= "<li><a href='".url($post->slug)."'>".$post->title."</a></li>";
        }
        $html .= "</ul>";
    }
    return $html;

});

register_content_block(array(
    'posts_main_content'  =>  array(
        'name'  =>  'posts_main_content',
        'title' =>  t('posts::strings.latest_posts'),
        'tpl'   =>  array(
            'posts::blocks/latest'=>'default'
        ),
        'model'  =>  '\Plugins\posts\models\Post',
        'action'    => 'paginate',
        'params'    => \Config::get('settings.cms.paging'),
        'multiple'  =>  false,
        'configurable'=> false
    )
));

register_help_items(array(
    'posts::help/listposts'
),'\\Plugins\\posts\\controllers\\PostsbackendController@listposts');

//register_dashboard_navbar_tools(array(
//    '/tools/tools'
//));

//register_navbar_addon_links(array(
//    'posts::addonlinks/links'
//));

register_content_type(array(
    array(
        'type' => 'posts', //the content type
        'title' => t('posts::strings.posts'), //the title to display
        'slug' => 'posts', //the slug that will be prepend on the item slug. eg: /page/about-us
        'model' => '\Plugins\posts\models\Post', //the model to pull items from
        'controller' => '\Plugins\posts\controllers\PostsController' //the plugin controller
    ),

));


function posts_install(){
    Schema::create('posts', function($table)
    {
        $table->increments('id');
        $table->integer('category_id')->index('taxonomy_id');
        $table->string('slug')->unique('posts_slug_unique');
        $table->string('title');
        $table->text('content', 65535);
        $table->integer('image_id');
        $table->integer('user_id')->index('user_id');
        $table->boolean('status');
        $table->timestamps();
    });

    Schema::create('post_metas', function(Blueprint $table)
    {
        $table->increments('id');
        $table->integer('xref_id')->unsigned();
        $table->integer('meta_id')->unsigned();
        $table->text('value', 65535);
        $table->dateTime('meta_created_at')->default('0000-00-00 00:00:00');
        $table->dateTime('meta_updated_at')->default('0000-00-00 00:00:00');
        $table->unique(['xref_id','meta_id'], 'xref_meta');
    });
}

function posts_uninstall(){
    Schema::drop('posts');
    Schema::drop('post_metas');
}

function postsToList($pages){
    ob_start();
    ?>
    <ul class="nav nav-stacked">
        <?php foreach($pages as $item):?>
            <li class="clearfix"><a href="<?php echo url('backend/posts/edit')."/". $item->id ?>"><?php echo $item->title?></a></li>
        <?php endforeach?>
    </ul>
    <?php
    return ob_get_clean();
}

function quickpress_form(){
    ob_start();
    echo Form::open(array('url'=>'backend/posts/create', 'class'=>'form-newpage'));
    ?>
    <div class="clearfix">
            <input onclick="this.focus()" type="text" name="title" value="" class="form-control" placeholder="<?php echo t('strings.title')?>"/>
        </div>

        <div class="clearfix">
            <textarea class="tinyrte form-control" name="content"></textarea>
        </div>
        <div class="clearfix">
            <input onclick="this.focus()" class="form-control" type="text" value="" placeholder="<?php echo t('strings.tags')?>" name="tags" />
        </div>
        <input type="submit" value="<?php echo t('strings.save')?>" class="btn btn-primary" />

    <?php
    echo Form::close();
    return ob_get_clean();
}

register_plugin_install_handler('posts','posts_install');
register_plugin_uninstall_handler('posts','posts_uninstall');