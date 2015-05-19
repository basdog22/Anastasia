<?php

/**
 * Class AddonsController
 */
class AddonsController extends BackendbaseController
{
    /**
     * @var string
     */
    protected $layout = 'layouts.backend.laradmin';

    /**
     * The main page of the addons controller.
     */
    function manage()
    {

        add_breadcrumb(trans('strings.addons'), url('addons/manage'));
        $addons = Plugin::all();

        \Event::fire('backend.addons.manage', array($addons));

        $this->layout->content = View::make('backend/addons/addons')->with('addons', $addons);
    }

    public function bulkinstall()
    {
        $ids = requested('ids');
        \Event::fire('backend.restore.point');
        foreach ($ids as $id) {
            $id = (int)$id;
            $addon = \Plugins::find($id);
            require_once Config::get('settings.cms.content') . "/plugins/{$addon->name}/hooks.php";
            Event::fire('backend.addons.install.' . $addon->name, array($addon));

            $addon->installed = 1;
            if ($addon->save()) {

            } else {
                return \Response::json(array('type' => 'success', 'text' => t('messages.error_occured')));
            }
        }
        return \Response::json(array('type' => 'success', 'text' => t('messages.addons_installed')));
    }

    public function bulkuninstall()
    {
        \Event::fire('backend.restore.point');
        $ids = requested('ids');
        foreach ($ids as $id) {
            $id = (int)$id;
            $addon = Plugin::find($id);
            \Event::fire('backend.addons.uninstall.' . $addon->name, array($addon));
            $addon->installed = 0;
            if ($addon->save()) {

            } else {
                return \Response::json(array('type' => 'success', 'text' => t('messages.error_occured')));
            }
        }
        return \Response::json(array('type' => 'success', 'text' => t('messages.addons_uninstalled')));
    }

    /**
     * Uninstalls the specified addon
     *
     * @param $addonid
     * @return mixed
     */
    function uninstall($addonid)
    {
        \Event::fire('backend.restore.point');
        $addon = Plugin::find($addonid);
        \Event::fire('backend.addons.uninstall.' . $addon->name, array($addon));
        $addon->installed = 0;
        if (!$addon->save()) {
            return Redirect::to('addons/manage')->withMessage($this->notifyView(t('messages.error_occured')));
        }
        return Redirect::to('addons/manage')->withMessage($this->notifyView(t('messages.addon_uninstalled')));
    }

    /**
     * Installs the specified addon
     * @param $addonid
     * @return mixed
     */
    function install($addonid)
    {
        \Event::fire('backend.restore.point');
        $addon = Plugin::find($addonid);
        require_once Config::get('settings.cms.content') . "/plugins/{$addon->name}/hooks.php";
        \Event::fire('backend.addons.install.' . $addon->name, array($addon));
        $addon->installed = 1;
        if (!$addon->save()) {
            return Redirect::to('addons/manage')->withMessage($this->notifyView(t('messages.error_occured')));
        }
        return Redirect::to('addons/manage')->withMessage($this->notifyView(t('messages.addon_installed')));


    }

    /**
     * Save the addon file.
     * @return mixed
     */
    public function savefile()
    {

        $addonid = (int)requested('addonid');
        $contents = requested('filecontents');
        $file = requested('filepath');

        $addon = \Plugin::find($addonid);

        if (is_null($addon)) {
            return \Redirect::to('addons/manage')->withMessage($this->notifyView(t('messages.addon_not_found')));
        }

        $bytes_written = File::put($file, $contents);
        if ($bytes_written === false) {
            return \Redirect::back()->withMessage($this->notifyView(t('messages.cannot_write_to_file')));
        } else {
            return \Redirect::back()->withMessage($this->notifyView(t('messages.file_saved')));
        }
    }

    /**
     * Shows the edit panel for the addon
     *
     * @param $addonid
     * @param bool $file
     * @return mixed
     */
    public function showedit($addonid, $file = false)
    {
        add_breadcrumb(trans('strings.addons'), url('addons/manage'));
        add_breadcrumb(trans('strings.addons_edit'), url('addons/edit/' . $addonid));
        $addon = Plugin::find($addonid);
        if (is_null($addon)) {
            return Redirect::to('themes/manage')->withMessage($this->notifyView(t('messages.theme_not_found')));
        }

        $fileshandler = File::allFiles(Config::get('settings.cms.content') . '/plugins/' . $addon->name);
        $files = array();
        foreach ($fileshandler as $k => $v) {
            $path = str_replace(array(Config::get('settings.cms.content') . '/plugins/', $v->getFileName()), '', $v->getPathName());
            $files[$path][] = $v->getFileName();
        }
//        ll($files);
        if (!$file) {
            $file = reset($fileshandler);
            $filecontents = File::get($file->getPathName());

            $filepath = $file->getPathName();
        } else {
            $file = str_replace("\\", "/", $file);
            $filecontents = File::get(Config::get('settings.cms.content') . '/plugins/' . $file);
            $filepath = Config::get('settings.cms.content') . '/plugins/' . $file;
        }

        $this->layout->content = View::make('backend/addons/editpanel')->withFiles($files)->withAddon($addon)->withFilecontents($filecontents)->withFilepath($filepath);
    }

    /**
     * Sets the specified addon as the default controller for mainpage
     *
     * @param $addonid
     * @return mixed
     */
    function setdefault($addonid)
    {

        $addon = Plugin::find($addonid);
        $maincontroller = Settings::ofOption("main_controller")->first();
        $mainfunction = Settings::ofOption("main_controller_function")->first();

        if ($addon->mainpage_route) {
            $route = explode("@", $addon->mainpage_route);

            if ($route[0] && $route[1]) {
                $maincontroller->setting_value = $route[0];
                $mainfunction->setting_value = $route[1];
                $maincontroller->save();
                $mainfunction->save();
                return Redirect::to('addons/manage')->withMessage($this->notifyView(t('messages.addon_is_default')));
            } else {
                return Redirect::to('addons/manage')->withMessage($this->notifyView(t('messages.not_applicable')));
            }
        } else {
            return Redirect::to('addons/manage')->withMessage($this->notifyView(t('messages.not_applicable')));
        }

    }

    /**
     * Shows the dialog for adding a new addon
     *
     * @return \Illuminate\View\View
     */
    function newaddon()
    {
        if (Request::ajax()) {
            return View::make('backend/addons/newaddon');
        } else {
            $this->layout->content = View::make('backend/addons/newaddon');
        }

    }
}