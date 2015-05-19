<?php

/**
 * Class TranslationsController
 */

class TranslationsController extends BackendbaseController {
    /**
     * @var string
     */
    protected $layout = 'layouts.backend.laradmin';




    public function getIndex($group = null)
    {

        add_breadcrumb(trans('strings.translations'), url('backend/translations/index'));
        if(!is_null($group)){
            add_breadcrumb(trans('strings.'.$group), url('backend/translations/index/'.$group));
        }
        $app = app();
        $controller = $app->make('Barryvdh\TranslationManager\Controller');
        $this->layout->content = $controller->callAction('getIndex', array('group'=>$group));
    }

    protected function loadLocales()
    {
        $app = app();
        $controller = $app->make('Barryvdh\TranslationManager\Controller');
        return $controller->callAction('loadLocales', array());
    }

    public function postAdd($group)
    {
        $app = app();
        $controller = $app->make('Barryvdh\TranslationManager\Controller');
        return $controller->callAction('postAdd', array('group'=>$group));
    }

    public function addlocale($locale){
        \File::copyDirectory(app_path().'/lang/'.get_default_locale(),app_path().'/lang/'.$locale);
        if(\File::exists(packages_path().'/anastasia/flags/'.$locale.'.png')){
            \File::copy(packages_path().'/anastasia/flags/'.$locale.'.png',app_path().'/lang/'.$locale.'/'.$locale.'.png');
            \File::delete(app_path().'/lang/'.$locale.'/'.get_default_locale().'.png');
        }
        return \Redirect::back()->withMessage($this->notifyView(t('messages.locale_added')));
    }

    public function postEdit($group)
    {
        $app = app();
        $controller = $app->make('Barryvdh\TranslationManager\Controller');
        return $controller->callAction('postEdit', array('group'=>$group));
    }

    public function getDelete($group, $key)
    {
        $app = app();
        $controller = $app->make('Barryvdh\TranslationManager\Controller');
        $ok = $controller->callAction('getDelete', array('group'=>$group,'key'=>$key));
        return \Redirect::back()->withMessage($this->notifyView(t('messages.string_deleted')));
    }

    public function postImport()
    {
        $app = app();
        $controller = $app->make('Barryvdh\TranslationManager\Controller');
        return $controller->callAction('postImport', array());
    }

    public function postFind()
    {
        $app = app();
        $controller = $app->make('Barryvdh\TranslationManager\Controller');
        return $controller->callAction('postFind', array());
    }

    public function postPublish($group)
    {
        $app = app();
        $controller = $app->make('Barryvdh\TranslationManager\Controller');
        return $controller->callAction('postPublish', array('group'=>$group));
    }
}