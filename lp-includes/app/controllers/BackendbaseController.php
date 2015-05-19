<?php

/**
 * Class BackendbaseController
 */
class BackendbaseController extends Controller
{

    /**
     * Setup the layout used by the controller.
     *
     * @return void
     */
    /**
     * @var
     */
    protected $addonlinks;

    /**
     * @var int
     */
    protected $runonce = 0;

    /**
     * Sets up the layout used by the backend controllers
     */
    protected function setupLayout()
    {
        $this->runonce++;
        if(!defined('IN_ADMIN_AREA')){
            define('IN_ADMIN_AREA',true);
        }

        $locale = (requested('locale'))?requested('locale'):null;
        if(!is_null($locale)){
            \Session::forget('current_locale');
            \Session::put('current_locale',$locale);
            \Session::forget('default_locale');
            \Session::put('default_locale',get_default_locale());
        }
        App::setLocale(get_current_locale(), get_default_locale());

        if($this->runonce===1){
            add_breadcrumb(t('strings.dashboard'), url('backend/dashboard'));
        }
        if (!is_null($this->layout)) {
            $temp = $this->layout;
            $this->layout = View::make($this->layout);

        }





        \Breadcrumbs::setListElement('ol');
        \Breadcrumbs::addCssClasses('breadcrumb');
        \Breadcrumbs::removeCssClasses('breadcrumbs');
        \Breadcrumbs::setDivider(null);




        $this->getThemeConfig();
        if ($temp == 'layouts.backend.laradmin') {

//            register_dashboard_widget(array('backend/widget'));
            register_dashboard_navbar_tools(array('backend/tools'));

            $addonlinks = Event::fire('backend.addonlinks.collect');

            $headeritems = Event::fire('backend.header.create');

            $navtools_html = $headeritems_html = $footeritems_html = $sidebarmenu_html = '';
            foreach ($headeritems as $k=>$w) {

                foreach ($w as $o) {
                    $headeritems_html .= (string) \View::make($o);
                }
            }


            $this->addonlinks = '';
            foreach ($addonlinks as $w) {
                foreach ($w as $o) {
                    $this->addonlinks .= \View::make($o);
                }
            }

            Config::set('addonlinks', $this->addonlinks);

            $sidebarmenu = Event::fire('backend.sidebar.create');

            foreach ($sidebarmenu as $w) {
                foreach ($w as $o) {
                    $sidebarmenu_html .= \View::make($o);
                }
            }

            $navtools = Event::fire('backend.navbar.create');

            foreach ($navtools as $w) {
                foreach ($w as $o) {
                    $navtools_html .= \View::make($o);
                }
            }


            $footeritems = Event::fire('backend.footer.create');

            foreach ($footeritems as $w) {
                foreach ($w as $o) {
                    $footeritems_html .= \View::make($o);
                }
            }



            Config::set('theme.menus', Event::fire('menu.positions.collect'));

            //check if empty



            $header = \View::make('backend/header')->with('headeritems', $headeritems_html);
            $sidebar = \View::make('backend/sidebar')->with('sidebarmenu', $sidebarmenu_html);
            $navbar = \View::make('backend/navbar')->with('navtools', $navtools_html);
            $footer = \View::make('backend/footer')->with('footeritems', $footeritems_html);




            \View::share('sidebar',(string)$sidebar);
            \View::share('navbar',(string)$navbar);
            \View::share('footer',(string)$footer);
            \View::share('header',(string)$header);
        }
    }

    /**
     * Formats the message with some extra html
     *
     * @param $message
     * @param string $type
     * @return string
     */
    public function notifyView($message, $type = 'success')
    {
        return \MessagesHelper::message_format($message, $type);
    }

    /**
     * Returns the flat array of the content types
     * @return array
     */
    public function getContentTypesFlat()
    {
        $content = \Config::get('content_types');
        $types = array();
        foreach ($content as $k => $v) {
            foreach ($v as $o) {
                $types[] = $o;
            }
        }
        return $types;
    }

    /**
     * Saves the theme configuration
     *
     * @param $config
     */
    public function setThemeConfig($config)
    {
        $theme = \Config::get('themes.current');

        //check if the theme has configurable settings
        $xml = File::get($theme['theme_path'] . '/theme.xml');
        $xml = simplexml_load_string($xml);

        foreach ($config as $k => $v) {
            $xml->configurator->$k = $v['value'];
        }
        $xml->asXML($theme['theme_path'] . '/theme.xml');
    }

    /**
     * Loads the theme configuration
     */
    public function getThemeConfig()
    {
        $theme = \Config::get('themes.current');

        //check if the theme has configurable settings
        $xml = File::get($theme['theme_path'] . '/theme.xml');
        $xml = simplexml_load_string($xml, null, LIBXML_NOCDATA);

        if ($xml->configurator) {
            foreach ($xml->configurator->children() as $k => $item) {
                $config[$item->getName()] = array(
                    'type' => (string)$item->attributes()['type'],
                    'name' => $item->getName(),
                    'value' => (string)$item
                );
            }
            \Config::set('themes.current.config', $config);

        }

    }
}
