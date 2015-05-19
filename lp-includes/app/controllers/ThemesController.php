<?php

/**
 * Class ThemesController
 */
class ThemesController extends BackendbaseController{
    /**
     * @var string
     */
    protected $layout = 'layouts.backend.laradmin';

    /**
     * Shows the themes screen
     */
    function manage(){
        add_breadcrumb(trans('strings.themes'),url('themes/manage'));
        $themes = \Theme::all();
        \Event::fire('backend.themes.manage', array($themes));
        $this->layout->content = \View::make('backend/themes/themes')->with('themes',$themes);
    }

    /**
     * Uninstalls the specified theme
     *
     * @param $themeid
     * @return mixed
     */
    function uninstall($themeid){


        \Event::fire('backend.restore.point');
        $theme = \Theme::find($themeid);
        \Event::fire('backend.themes.uninstall', array($theme));
        if($theme->active){
            $default = \Theme::whereName('anastasia')->first();
            $default->active = 1;
            if(!$default->save()){
                return \Redirect::to('themes/manage')->withMessage($this->notifyView(t('messages.error_occured'),'danger'));
            }
        }
        $theme->installed = 0;
        $theme->active = 0;
        if(!$theme->save()){
            return \Redirect::to('themes/manage')->withMessage($this->notifyView(t('messages.error_occured'),'danger'));
        }
        return \Redirect::to('themes/manage')->withMessage($this->notifyView(t('messages.theme_uninstalled')));


    }

    public function bulkinstall(){
        \Event::fire('backend.restore.point');
        $ids = requested('ids');
        foreach($ids as $id){
            $id = (int) $id;
            $theme = \Theme::find($id);
            \Event::fire('backend.themes.install', array($theme));
            $theme->installed = 1;
            if(!$theme->save()){
                return \Redirect::to('themes/manage')->withMessage($this->notifyView(t('messages.error_occured'),'danger'));
            }
        }
        return \Response::json(array('type' => 'success', 'text' => t('messages.themes_installed')));
    }

    public function bulkuninstall(){
        \Event::fire('backend.restore.point');
        $ids = requested('ids');
        foreach($ids as $id){
            $id = (int) $id;
            $theme = \Theme::find($id);
            \Event::fire('backend.themes.uninstall', array($theme));
            if($theme->active){
                $default = Theme::find(1);
                $default->active = 1;
                if(!$default->save()){
                    return \Response::json(array('type' => 'danger', 'text' => t('messages.error_occured')));
                }
            }
            $theme->installed = 0;
            $theme->active = 0;
            if(!$theme->save()){
                return \Response::json(array('type' => 'danger', 'text' => t('messages.error_occured')));
            }
        }
        return \Response::json(array('type' => 'success', 'text' => t('messages.themes_uninstalled')));
    }

    /**
     * Saves the theme configurator
     *
     * @return mixed
     */
    public function saveconfig(){
        $theme = Config::get('themes.current');
        $config = $theme['config'];
        foreach($config as $k=>$v){
            if($v['type']=='image'){
                if($img = Image::upload($_FILES[$k])){

                    $config[$k]['value'] = $img->url();
                }

            }else{
                $config[$k]['value'] = requested($k);
            }

        }
        $this->setThemeConfig($config);
        return \Redirect::to('themes/themeconfig')->withMessage($this->notifyView(t('messages.theme_config_saved')));
    }

    /**
     * Shows the theme configurator screen
     *
     * @return mixed
     */
    public function configurator(){
        $theme = \Config::get('themes.current');
        return \View::make("backend/themes/configurator")->withTheme($theme)->withConfig($theme['config']);
    }

    /**
     * Saves the theme file after editing
     *
     * @return mixed
     */
    public function savefile(){

        $themeid = (int) requested('themeid');
        $contents = requested('filecontents');
        $file = requested('filepath');

        $theme = \Theme::find($themeid);

        if(is_null($theme)){
            return \Redirect::to('themes/manage')->withMessage($this->notifyView(t('messages.theme_not_found')));
        }

        $bytes_written = File::put($file, $contents);
        if ($bytes_written === false){
            return \Redirect::back()->withMessage($this->notifyView(t('messages.cannot_write_to_file')));
        }else{
            return \Redirect::back()->withMessage($this->notifyView(t('messages.file_saved')));
        }
    }

    /**
     * Show the theme editor screen
     *
     * @param $themeid
     * @param bool $file
     * @return mixed
     */
    public function showedit($themeid,$file=false){

        add_breadcrumb(trans('strings.themes'),url('themes/manage'));
        add_breadcrumb(trans('strings.themes_edit'),url('themes/edit/'.$themeid));

        $theme = \Theme::find($themeid);
        if(is_null($theme)){
            return \Redirect::to('themes/manage')->withMessage($this->notifyView(t('messages.theme_not_found')));
        }
        $fileshandler = \File::allFiles(Config::get('settings.cms.content').'/themes/'.$theme->name);
        $files = array();
        foreach($fileshandler as $k=>$v){
            $path = str_replace(array(\Config::get('settings.cms.content').'/themes/',$v->getFileName()),'',$v->getPathName());
            $files[$path][] = $v->getFileName();
        }
//        ll($files);
        if(!$file){
            $file = reset($fileshandler);
            $filecontents = \File::get($file->getPathName());
            $filepath = $file->getPathName();
        }else{
            $file = str_replace("\\","/",$file);
            $filecontents = \File::get(Config::get('settings.cms.content').'/themes/'.$file);
            $filepath = \Config::get('settings.cms.content').'/themes/'.$file;
        }

        $this->layout->content = \View::make('backend/themes/editpanel')->withFiles($files)->withTheme($theme)->withFilecontents($filecontents)->withFilepath($filepath);
    }

    /**
     * Installs a theme
     *
     * @param $themeid
     * @return mixed
     */
    function install($themeid){

        \Event::fire('backend.restore.point');
        $theme = \Theme::find($themeid);
        \Event::fire('backend.themes.install', array($theme));
        $theme->installed = 1;
        if(!$theme->save()){
            return \Redirect::to('themes/manage')->withMessage($this->notifyView(t('messages.error_occured'),'danger'));
        }
        return Redirect::to('themes/manage')->withMessage($this->notifyView(t('messages.theme_installed')));

    }

    /**
     * Activates a theme
     *
     * @param $themeid
     * @return mixed
     */
    function activate($themeid){
        $theme = \Theme::find($themeid);
        \Event::fire('backend.restore.point');
        if($theme->active){
            return \Redirect::to('themes/manage')->withMessage($this->notifyView(t('messages.no_change'),'warning'));
        }else{
            $themes = \Theme::all();
            foreach($themes as $atheme){
                if($atheme->id!=$theme->id){
                    $atheme->active = 0;
                    if(!$atheme->save()){
                        return \Redirect::to('themes/manage')->withMessage($this->notifyView(t('messages.error_occured'),'danger'));
                    }
                }else{
                    \Event::fire('backend.themes.activate', array($atheme));
                    $atheme->active = 1;
                    if(!$atheme->save()){
                        return \Redirect::to('themes/manage')->withMessage($this->notifyView(t('messages.error_occured'),'danger'));
                    }
                }
            }
        }
        return \Redirect::to('themes/manage')->withMessage($this->notifyView(t('messages.theme_activated')));
    }

    /**
     * Shows the new theme dialog
     *
     * @return \Illuminate\View\View
     */
    function newtheme(){
        if (\Request::ajax()){
            return \View::make('backend/themes/newtheme');
        }else{
            $this->layout->content = \View::make('backend/themes/newtheme');
        }
    }
}