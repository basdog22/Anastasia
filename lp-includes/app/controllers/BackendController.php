<?php

/**
 * Class BackendController
 */
class BackendController extends BackendbaseController {
    /**
     * @var string
     */
    protected $layout = 'layouts.backend.laradmin';

    /**
     * Shows the dashboard
     */
    public function dashboard()
    {
        \Event::fire('backend.dashboard.before_load');
        $widgetsview = '';
        $widgets = Event::fire("backend.widgets.collect");
        foreach ($widgets as $w) {
            foreach ($w as $o) {
                $widgetsview .= View::make($o);
            }
        }
        $this->layout->content = \View::make('backend/backend')->withWidgets($widgetsview);
        \Event::fire('backend.dashboard.after_load');
    }

    public function backupmanager(){
        $points = \File::directories(public_path().'/lp-includes/app/database/points');
        $backups = array();
        $sub = get_secure_string();
        foreach($points as $key=>$point){
            $backups[$point] = str_replace(array(app_path().'/database/points/',$sub),'',$point);
        }
        if (\Request::ajax()){
            return \View::make('backend/backup')->with('points',$backups);
        }else{
            $this->layout->content = \View::make('backend/backup')->with('points',$backups);
        }
    }

    public function restoresystem(){
        //backup first
        BackupManager::backup();
        $point = requested('point');
        BackupManager::restore($point);
        $sub = get_secure_string();
        $point = str_replace(array(app_path().'/database/points/',$sub),'',$point);
        return \Redirect::to('backend')->withMessage($this->notifyView(t('messages.backup_restored',['point'=>$point]),'success'));
    }

    public function backupsystem($api=false){
        BackupManager::backup();
        return \Redirect::to('backend')->withMessage($this->notifyView(t('messages.backup_created'),'success'));
    }




    public function getshortcodelist($api=false){
        $shortcodes = \Event::fire('collect.registered.shortcodes');

        if($api){
            $titles = array();
            foreach($shortcodes as $shortcode){
                $titles[] = "[".$shortcode['shortcode']."]";
            }
            return implode(",",$titles);
        }
        return \View::make('backend/shortcodeslist')->withItems($shortcodes);
    }

    /**
     * Shows the tinymce settings
     */
    public function tinysettings(){
        $sel = \Config::get('settings.cms.tinymce_plugins');

        $sel = explode(" ",$sel);
        if(!is_null($sel)){
            foreach($sel as $v){
                $selplugins[$v] = $v;
            }
        }
        $plugins = $this->scanTinyMCEPlugins();

        if (\Request::ajax()){
            return \View::make('backend/tinysettings')->with('plugins',$plugins)->with('selplugins',$selplugins);
        }else{
            $this->layout->content = \View::make('backend/tinysettings')->with('plugins',$plugins)->with('selplugins',$selplugins);
        }
    }

    /**
     * Updates the tinymce settings
     *
     * @return mixed
     */
    public function tinysave(){

        $settings = \Settings::whereRaw("namespace='cms' AND setting_name='tinymce_plugins'")->first();
        if(is_null($settings)){
            $settings = new \Settings;
        }
        $settings->namespace = 'cms';
        $settings->setting_name = 'tinymce_plugins';
        $settings->setting_value = implode(" ",requested('plugins'));
        $settings->autoload = 1;
        if($settings->save()){
            return \Redirect::back()->withMessage($this->notifyView(t('messages.settings_saved'),'success'));
        }
        return \Redirect::back()->withMessage($this->notifyView(t('messages.error_occured'),'danger'));
    }

    /**
     * Scans the tinymce directory for plugins
     *
     * @return array
     */
    private function scanTinyMCEPlugins(){
        $plugins = scandir(\Config::get('settings.cms.includes')."/packages/anastasia/tinymce/plugins/");
        $plugs = array();
        unset($plugins[0]);
        unset($plugins[1]);
        foreach($plugins as $k=>$v){
            $plugs[$v] = $v;
        }
        return $plugs;
    }

    /**
     * Shows the settings screen
     */
    public function settings(){
        add_breadcrumb(trans('strings.settings'),url('backend/settings'));
        $settings = \Settings::whereRaw("namespace='cms'")->get();

        $this->layout->content = View::make('backend/settings')->with('settings',$settings);
    }

    /**
     * Saves the system settings
     *
     * @return mixed
     */
    public function savesettings(){
        if(requested('id')){
            $settings = \Settings::find(requested('id'));
        }else{
            $settings = new \Settings;
        }
        $settings->namespace = requested('namespace');
        $settings->setting_name = requested('setting_name');
        $settings->setting_value = requested('setting_value');
        $settings->autoload = (requested('autoload'))?requested('autoload'):0;
        \Event::fire('backend.settings.save', array($settings));
        if($settings->save()){
            return \Redirect::to('backend/settings/')->withMessage($this->notifyView(t('messages.settings_saved'),'success'));
        }
        return \Redirect::to('backend/settings/')->withMessage($this->notifyView(t('messages.error_occured'),'success'));
    }

    /**
     *
     * @return $this
     */
    public function contentypes(){
        $content = \Config::get('content_types');
        if (\Request::ajax()){
            return \View::make('backend/contenttypes')->with('content',$content);
        }else{
            $this->layout->content = \View::make('backend/contenttypes')->with('content',$content);
        }
    }


    /**
     * Show the integrated help dialog
     *
     * @return \Illuminate\View\View
     */
    public function help(){
        if (\Request::ajax()){
            return \View::make('backend/help')->withShortcodes();
        }else{
            $this->layout->content = \View::make('backend/help')->withShortcodes();;
        }
    }
}