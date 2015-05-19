<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

\Route::when('*', 'csrf|honeypot|plugins', ['post', 'put', 'patch', 'delete']);

\Route::group(array('before' => 'isroot','as'=>'root'),function(){
    //Addons section
    \Route::get('addons/setdefault/{addonid}','AddonsController@setdefault');

    \Route::get('addons/manage','AddonsController@manage');

    register_help_items(array(
        'backend/help/manage_addons'
    ),'AddonsController@manage');


    \Route::post('addons/manage/m_install','AddonsController@bulkinstall');
    \Route::post('addons/manage/m_uninstall','AddonsController@bulkuninstall');
    \Route::get('addons/uninstall/{addonid}','AddonsController@uninstall');
    \Route::get('addons/install/{addonid}','AddonsController@install');
    \Route::get('addons/new','AddonsController@newaddon');
    \Route::get('addons/edit/{addonsid}/{themefile?}','AddonsController@showedit');

    register_help_items(array(
        'backend/help/edit_addon'
    ),'AddonsController@showedit');

    \Route::post('addons/edit/save','AddonsController@savefile');
    //Themes section
    \Route::get('themes/manage','ThemesController@manage');

    register_help_items(array(
        'backend/help/manage_themes'
    ),'ThemesController@manage');

    \Route::post('themes/manage/m_install','ThemesController@bulkinstall');
    \Route::post('themes/manage/m_uninstall','ThemesController@bulkuninstall');
    \Route::get('themes/themeconfig','ThemesController@configurator');
    \Route::get('themes/edit/{themeid}/{themefile?}','ThemesController@showedit');

    register_help_items(array(
        'backend/help/edit_theme'
    ),'ThemesController@showedit');

    \Route::get('themes/uninstall/{themeid}','ThemesController@uninstall');
    \Route::get('themes/install/{themeid}','ThemesController@install');
    \Route::get('themes/activate/{themeid}','ThemesController@activate');
    \Route::get('themes/new','ThemesController@newtheme');
    \Route::post('themes/edit/save','ThemesController@savefile');
    \Route::post('themes/configurator','ThemesController@saveconfig');
    //Users section
    \Route::get('users/newgroup','UsersController@addgroup');
    \Route::get('users/delgroup/{groupid}','UsersController@delgroup');
    \Route::get('users/new','UsersController@newuser');
    \Route::get('users/edituser/{userid}','UsersController@edituser');
    \Route::post('users/adduser','UsersController@adduser');
    \Route::post('users/saveuser','UsersController@saveuser');
    \Route::post('users/newgroup','UsersController@creategroup');
    \Route::get('users/groups','UsersController@groups');

    register_help_items(array(
        'backend/help/groups_manage'
    ),'UsersController@groups');

    \Route::post('users/groups/m_delete','UsersController@bulkdeletegroups');
    \Route::post('users/groupperms','UsersController@editgrouppermissions');

    //General section
    \Route::get('backend/settings', 'BackendController@settings');

    register_help_items(array(
        'backend/help/settings'
    ),'BackendController@settings');

    \Route::get('backend/tinymce', 'BackendController@tinysettings');

    register_help_items(array(
        'backend/help/tinymce_settings'
    ),'BackendController@tinysettings');

    \Route::post('backend/savesettings', 'BackendController@savesettings');
    \Route::post('backend/tinymce/save', 'BackendController@tinysave');
    \Route::get('backend/backup', 'BackendController@backupmanager');

    \Route::post('backend/dobackup', 'BackendController@backupsystem');
    \Route::post('backend/dorestore', 'BackendController@restoresystem');


    \Route::get('users/logoutuser/{session_id}','UsersController@logoutuser');

    \Route::get('users/logs/{logtype?}','UsersController@showlogs');

    \Route::get('backend/locales/add/{locale}','TranslationsController@addlocale');
});

\Route::group(array('before' => 'isadmin','as'=>'admin'),function(){
    \Route::get('backend/taxonomies','TaxonomiesController@taxonomies');

    register_help_items(array(
        'backend/help/taxonomies'
    ),'TaxonomiesController@taxonomies');

    \Route::get('backend/taxonomies/browse/{taxid?}','TaxonomiesController@browsetaxonomies');
    \Route::get('backend/taxonomies/new','TaxonomiesController@newtaxonomy');
    \Route::get('backend/taxonomies/edit/{taxid}','TaxonomiesController@edittaxonomy');
    \Route::get('backend/taxonomies/delete/{taxid}','TaxonomiesController@deletetaxonomy');
    \Route::post('backend/taxonomies/new','TaxonomiesController@createtaxonomy');
    \Route::post('backend/taxonomies/edit','TaxonomiesController@updatetaxonomy');
    \Route::post('backend/taxonomies/m_edit','TaxonomiesController@bulkedittaxonomies');

    \Route::get('backend/menus', 'MenusController@menus');

    register_help_items(array(
        'backend/help/menus'
    ),'MenusController@menus');

    \Route::get('backend/newmenu', 'MenusController@newmenu');
    \Route::get('backend/newmenuitem/{menuid}', 'MenusController@newmenuitem');
    \Route::get('backend/menuitems/{menuid}','MenusController@menuitems');
    \Route::get('backend/editmenu/{menuid}','MenusController@editmenu');
    \Route::get('backend/editmenuitem/{menuitemid}','MenusController@editmenuitem');
    \Route::get('backend/delmenuitem/{menuitemid}','MenusController@delmenuitem');
    \Route::get('backend/delmenu/{menuid}','MenusController@delmenu');
    \Route::post('backend/menuitemsort', 'MenusController@menuitemsort');
    \Route::post('backend/savemenu', 'MenusController@savemenu');
    \Route::post('backend/menureorder', 'MenusController@menureorder');
    \Route::post('backend/addmenu', 'MenusController@addmenu');
    \Route::post('backend/addmenuitem', 'MenusController@addmenuitem');
    \Route::post('backend/savemenuitem', 'MenusController@savemenuitem');
    \Route::get('users/profile/{userid?}','UsersController@profile');
    \Route::get('users/manage/{groupname?}','UsersController@manage');

    register_help_items(array(
        'backend/help/users'
    ),'UsersController@manage');

    \Route::get('backend/blockmanager','BlockManagerController@manage');

    register_help_items(array(
        'backend/help/blockmanager'
    ),'BlockManagerController@manage');

    Route::get('backend/blockmanager/addblock/{gridid}', 'BlockManagerController@addblock');
    Route::post('backend/blockmanager/addblock', 'BlockManagerController@addtogrid');
    Route::post('backend/blockmanager/moveblock', 'BlockManagerController@moveblock');
    Route::get('backend/blockmanager/delblock/{blockid}', 'BlockManagerController@delblock');
    Route::get('backend/blockmanager/editblock/{blockid}', 'BlockManagerController@editblock');
    Route::post('backend/blockmanager/save', 'BlockManagerController@save');
    Route::post('backend/blockmanager/sort', 'BlockManagerController@sort');

    Route::get('backend/tasks', 'TasksController@alltasks');

    register_help_items(array(
        'backend/help/tasks'
    ),'TasksController@alltasks');

    Route::get('backend/tasks/new', 'TasksController@newtask');
    Route::get('backend/tasks/delete/{taskid}', 'TasksController@deltask');
    Route::get('backend/tasks/toggle/{taskid}', 'TasksController@toggletask');
    Route::post('backend/tasks/new', 'TasksController@addtask');

    Route::get('backend/pages','PagesController@pages');

    register_help_items(array(
        'backend/help/pages'
    ),'PagesController@pages');

    Route::get('backend/pages/new','PagesController@newpage');
    Route::get('backend/pages/delete/{pageid}','PagesController@delpage');
    Route::get('backend/pages/edit/{pageid}','PagesController@editpage');
    Route::post('backend/pages/new','PagesController@addpage');
    Route::post('backend/pages/m_delete','PagesController@bulkdeletepages');
    Route::post('backend/pages/m_edit','PagesController@bulkeditpages');
    Route::post('backend/pages/bulk','PagesController@bulksavepages');


    Route::get('backend/comments/{status?}','CommentsController@comments');

    register_help_items(array(
        'backend/help/comments'
    ),'CommentsController@comments');

    Route::get('backend/comments/edit/{cid}','CommentsController@edit');
    Route::post('backend/comments/edit','CommentsController@save');
    Route::get('backend/comments/approve/{cid}','CommentsController@approve');
    Route::get('backend/comments/disapprove/{cid}','CommentsController@disapprove');
    Route::get('backend/comments/delete/{cid}','CommentsController@delete');
    Route::post('backend/comments/m_delete','CommentsController@bulkdelete');
    Route::post('backend/comments/m_approve','CommentsController@bulkapprove');
    Route::post('backend/comments/m_disapprove','CommentsController@bulkdisapprove');


});

\Route::group(array('before' => 'isadmin','prefix'=>'backend'),function(){
    Route::controller('translations', 'TranslationsController');
});
\Route::group(array('before' => 'iseditor','as'=>'editor'), function()
{
    \Route::get('backend','BackendController@dashboard');
    \Route::get('backend/dashboard','BackendController@dashboard');

    register_help_items(array(
        'backend/help/dashboard'
    ),'BackendController@dashboard');

    \Route::get('backend/search','AssetsController@search');

    register_help_items(array(
        'backend/help/search'
    ),'AssetsController@search');


    \Route::get('backend/revisions/view/{revisionid}','RevisionsController@viewrevision');
    \Route::get('backend/revisions/restore/{revisionid}','RevisionsController@restorerevision');
    \Route::get('backend/revisions/remove/{revisionid}','RevisionsController@removerevision');

    \Route::get('backend/assets/edit/{filename}','AssetsController@editmedia');
    \Route::post('backend/assets/edit','AssetsController@doeditmedia');

});

\Route::group(array('before'=>'isauthor','as'=>'author'),function(){
    \Route::get('backend/help','BackendController@help');
    \Route::get('backend/sounds/rm.wav','AssetsController@elfinderfix'); //till it is removed...
    \Route::get('backend/contenttype', 'BackendController@contentypes');
    \Route::get('backend/filemanager/standalonepopup/{input_id}', 'Barryvdh\Elfinder\ElfinderController@showPopup');
    \Route::get('backend/filemanager', 'AssetsController@filemanager');

    register_help_items(array(
        'backend/help/filemanager'
    ),'AssetsController@filemanager');

    \Route::get('backend/filemanager/tinymce', 'Barryvdh\Elfinder\ElfinderController@showTinyMCE4');
    \Route::any('backend/filemanager/connector', 'Barryvdh\Elfinder\ElfinderController@showConnector');
    \Route::get('elfinder', 'Barryvdh\Elfinder\ElfinderController@showIndex');
    \Route::any('elfinder/connector', 'Barryvdh\Elfinder\ElfinderController@showConnector');
    \Route::get('backend/assets/savemedia','AssetsController@savemedia');
    \Route::get('backend/assets/removemedia','AssetsController@removemedia');
    \Route::get('backend/assets/load/{asset}','AssetsController@loadasset');
    \Route::get('backend/assets/read/{asset}/{assetid}','AssetsController@readasset');
    \Route::get('backend/shortcodelist/{api?}','BackendController@getshortcodelist');
});

//We need to add these specific with no as param
Route::group(array('before' => 'auth'), function() {
    \Route::controller("uploads",'UploadsController');
});

\Route::group(array('before'=>'iscron'),function(){
    \Route::get('backend/dobackup', 'BackendController@backupsystem');
});

\Route::controller('users', 'UsersController');

//Now lets check the font




\Route::post('addcomment', 'FrontController@addcomment');