<?php

Route::group(array('before' => 'auth','prefix'=>'backend/posts','as'=>'backend'), function(){

    Route::get('/','\\Plugins\\posts\\controllers\\PostsbackendController@dashboard');
    Route::get('list','\\Plugins\\posts\\controllers\\PostsbackendController@listposts');
    Route::post('m_delete','\\Plugins\\posts\\controllers\\PostsbackendController@bulkdelete');
    Route::get('published','\\Plugins\\posts\\controllers\\PostsbackendController@listpublished');
    Route::get('drafts','\\Plugins\\posts\\controllers\\PostsbackendController@listdrafts');
    Route::get('trash','\\Plugins\\posts\\controllers\\PostsbackendController@listtrashed');
    Route::get('restore/{postid}','\\Plugins\\posts\\controllers\\PostsbackendController@restore');
    Route::get('new','\\Plugins\\posts\\controllers\\PostsbackendController@newpost');
    Route::get('edit/{postid}','\\Plugins\\posts\\controllers\\PostsbackendController@edit');
    Route::get('delete/{postid}','\\Plugins\\posts\\controllers\\PostsbackendController@delete');



    Route::get('attach','\\Plugins\\posts\\controllers\\PostsbackendController@attachtag');
    Route::get('dettach','\\Plugins\\posts\\controllers\\PostsbackendController@dettachtag');



    Route::post('create','\\Plugins\\posts\\controllers\\PostsbackendController@create');
});

Route::get('blog/','\\Plugins\\posts\\controllers\\PostsController@mainpage');