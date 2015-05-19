<?php

if (!function_exists('ll')) {
    /**
     * print_r and die
     */
    function ll()
    {
        echo "<pre>";
        array_map(function ($x) {
            print_r($x);
        }, func_get_args());
        echo "</pre>";
        die;
    }
}
if (!function_exists('lr')) {
    /**
     * print_r
     */
    function lr()
    {
        echo "<pre>";
        array_map(function ($x) {
            print_r($x);
        }, func_get_args());
        echo "</pre>";

    }
}

if (!function_exists('le')) {

    function le($str)
    {
        $str = strip_tags($str);
        $str = e($str);
        return $str;
    }
}

/**
 * @param $items
 * @param int $num
 * @return string
 */
function feedToList($items, $num = 10)
{
    ob_start();
    ?>
    <ul class="nav nav-stacked">
        <?php foreach ($items as $k => $item):if ($k + 1 <= $num): ?>
            <li class="clearfix"><a target="_blank"
                                    href="<?php echo $item->get_permalink() ?>"><?php echo $item->get_title() ?></a>
            </li>
        <?php endif; endforeach ?>
    </ul>
    <?php
    return ob_get_clean();
}

/**
 * @return array|null
 */
function get_user_default_metas()
{
    return \Event::fire('collect.user.default_metas');
}

/**
 * @param $text
 * @param $route
 */
function add_breadcrumb($text, $route)
{
    \Breadcrumbs::addCrumb($text, $route);
}

/**
 * @return string
 */
function show_breadcrumb()
{
    return \Breadcrumbs::render();
}


/**
 * @param mixed $position
 * @param array $params
 * @return string
 */
function render_menu($position, $params = array())
{
    if ($position instanceof \Menu) {
        $menu = $position;
    } else {
        $menu = Menu::where('position', '=', $position)->first();
    }

    if (is_null($menu)) {
        return;
    }
    $html = "";
    foreach ($menu->children as $k => $v) {
        if ($v->children()->count() || trim($v->model)) {
            $html .= "<li class='dropdown'>";
            $linkclass = 'dropdown-toggle';
            $linkattr = 'data-toggle="dropdown"';
            $caret = '<b class="caret"></b>';
        } else {
            $html .= "<li>";
            $linkclass = '';
            $linkattr = '';
            $caret = '';
        }
        if (function_exists($v->url)) {
            $func = $v->url;
            $url = $func();
        } else {
            $url = $v->url;
        }
        $html .= "<a {$linkattr} {$v->link_attr} style='{$v->link_css}' target='{$v->link_target}' class='{$v->link_class} {$linkclass}' href='" . url($url) . "'>{$v->link_text} {$caret}</a>";
        if ($v->hasChildren()) {
            $html .= '<ul class="dropdown-menu">' . render_menu($v) . '</ul>';
        }
        if (trim($v->model)) {
            $model = $v->model;
            $items = $model::latest()->whereStatus(1)->take(5)->get();
            $html .= '<ul class="dropdown-menu">';
            foreach ($items as $item) {
                $html .= "<li><a href='" . url($item->slug) . "'>" . e($item->title) . "</a></li>";
            }
            $html .= '</ul>';
        }
        $html .= "</li>";
    }
    $html .= "";
    return $html;
}

/**
 * @return mixed
 */
function pagetitle()
{
    return \Config::get('cms.page.title');
}

/**
 * @return mixed
 */
function pagedescription()
{
    return \Config::get('cms.page.description');
}

/**
 * @return mixed
 */
function pagekeywords()
{
    return \Config::get('cms.page.keywords');
}

/**
 * @return mixed
 */
function home_url()
{
    return \Config::get('app.url');
}

/**
 * @param $var
 * @return mixed
 */
function requested($var=false)
{
    if($var){
        return \Input::get($var);
    }
    return \Input::all();
}

/**
 * @param $value
 * @return bool
 */
function get_theme_config($value)
{
    $config = \Config::get('themes.current.config');
    if (isset($config[$value])) {
        return $config[$value]['value'];
    }
    return false;
}

/**
 * @param $user
 * @return bool
 */
function is_root($user)
{
    $user = Sentry::findUserById($user->id);

    if ($user->hasAccess('root')) {
        return true;
    }
    return false;

}

/**
 * @param $user
 * @return bool
 */
function is_admin($user)
{
    $user = Sentry::findUserById($user->id);

    if ($user->hasAccess('admin')) {
        return true;
    }
    return false;

}

/**
 * @param $user
 * @return bool
 */
function is_editor($user)
{
    $user = Sentry::findUserById($user->id);

    if ($user->hasAccess('editor')) {
        return true;
    }
    return false;

}

/**
 * @param $user
 * @return bool
 */
function is_author($user)
{
    if (is_null($user)) {
        return false;
    }
    $user = Sentry::findUserById($user->id);

    if ($user->hasAccess('author')) {
        return true;
    }
    return false;

}

/**
 * @param $user
 * @return bool
 */
function is_member($user)
{
    $user = Sentry::findUserById($user->id);

    if ($user->hasAccess('member')) {
        return true;
    }
    return false;

}

/**
 * @return string
 */
function get_help_contents()
{
    $route = \Route::current()->getActionName();
    $help_contents = \Event::fire($route . '.help');
    $contents = '';
    foreach ($help_contents as $help) {
        foreach ($help as $o) {
            $contents .= View::make($o);
        }
    }
    return $contents;
}

/**
 * @return array|mixed
 */
function get_theme_positions($layout)
{
    // global $positions;

    $positions = include Config::get('themes.current.theme_path') . '/positions.php';

    if (isset($layout['positions'])) {
        $custom = include $layout['positions'];

        foreach ($positions as $key => $position) {
            if (!$position['is_default']) {
                $positions[$key] = array();
            }
        }
        foreach ($custom as $key => $position) {
            if ($position['is_default']) {
                unset($custom[$key]);
            }
        }
        $positions = array_merge($positions, $custom);
    }
//lr($positions);
    return $positions;
}

/**
 * @return array|null
 */
function get_content_blocks()
{
    return \Event::fire('content.blocks.collect');
}

/**
 * @return mixed
 */
function get_theme_domain()
{
    $theme_domain = \Config::get('themes.current');
    return $theme_domain['name'];
}

/**
 * @param $position
 * @param $layout
 * @param $grid_id
 * @return \Illuminate\Database\Eloquent\Collection|static[]
 */
function get_blocks($position, $layout, $grid_id)
{

    $theme_domain = get_theme_domain();
    return GridManager::where('theme_domain', '=', $theme_domain)->where('position', '=', $position)->where('grid', '=', $grid_id)->where('layout', '=', $layout)->orderBy('order_weight')->get();
}

/**
 * @param $block_name
 * @return mixed
 */
function get_block_info($block_name)
{
    $blocks = get_content_blocks();
    foreach ($blocks as $k => $v) {
        foreach ($v as $name => $data) {
            if ($name == $block_name) {
                return $data;
            }
        }
    }
}

/**
 * @param $block
 * @return mixed
 */
function is_configurable_block($block)
{
    $info = get_block_info($block->name);
    return $info['configurable'];
}

/**
 * @param $file
 * @return mixed
 */
function get_fileid3_info($file)
{
    $id3 = new \getID3;
    $data = $id3->analyze($file);
    return $data;
}

/**
 * @param $data
 * @return string
 */
function safe_serialize($data)
{
    $data = serialize($data);
    $data = base64_encode($data);
    return $data;
}

/**
 * @param $data
 * @return mixed|string
 */
function safe_unserialize($data)
{
    $data = base64_decode($data);
    $data = unserialize($data);
    return $data;
}

/**
 * @param $file
 * @return string
 */
function read_plain_file($file)
{
    $path = str_replace(\Config::get('app.url'), public_path(), $file->path);
    $text = \File::get($path);
    $fileArray = pathinfo($path);
    $file_ext = $fileArray['extension'];
    //check if we need to add pre
    if (in_array($file_ext, array('js', 'css', 'xml', 'php'))) {
        return "<pre style='background:#666;color:#00ff00'>" . htmlentities($text) . "</pre>";
    }
    return $text;
}

/**
 * @param $file
 * @return bool|mixed|string
 */
function read_office_file($file)
{
    $path = str_replace(\Config::get('app.url'), public_path(), $file->path);
    $docObj = new \WordReader($path);
    $docText = $docObj->convertToText();
    return $docText;
}

/**
 * @param $needle
 * @param $haystack
 * @return bool
 */
function recursive_array_search($needle, $haystack)
{
    $needle = mb_strtolower($needle, 'utf-8');
    foreach ($haystack as $key => $value) {
        $current_key = $key;
        if (!is_array($value)) {
            $quoted = preg_quote($needle);
            $value = mb_strtolower($value, 'utf-8');
            $found = preg_match("#\b" . $quoted . "\b#u", $value);
        } else {
            $found = false;
        }
        if ($found || (is_array($value) && recursive_array_search($needle, $value) !== false)) {
            return true;
        }
    }
    return false;
}

/**
 * @param array $events
 * @param array $options
 * @param array $callbacks
 * @return string
 */
function show_calendar($events = array(), $options = array(), $callbacks = array())
{
    $events = (is_array($events)) ? $events : array();

    $events[] = Calendar::event(
        'Anastasia Born',
        'The gift was given!',
        true,
        '2014-06-13T0845',
        '2014-06-13T0845',
        1,
        0
    );

    /*
     * $events[] = Calendar::event(
            'Event One', //event title
            false, //full day event?
            '2015-02-11T0800', //start time (you can also use Carbon instead of DateTime)
            '2015-02-12T0800' //end time (you can also use Carbon instead of DateTime)
        );

        $events[] = Calendar::event(
            "Valentine's Day", //event title
            true, //full day event?
            new DateTime('2015-02-14'), //start time (you can also use Carbon instead of DateTime)
            new DateTime('2015-02-14') //end time (you can also use Carbon instead of DateTime)
        );
     * */

    foreach ($events as $event) {
        $color = '#000000';
        if (isset($event->complete)) {
            switch ($event->complete) {
                case 0:
                    $color = '#ff0000';
                    break;
                case 1:
                    $color = '#00ff00';
                    break;
            }
        }

        $calendar = \Calendar::addEvent($event, ['color' => $color]);
    }
    $calendar->setOptions($options)->setCallbacks($callbacks);

    $cal = HTML::script('//cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.3.1/fullcalendar.min.js');
    $cal .= HTML::style('//cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.3.1/fullcalendar.min.css');
    $cal .= HTML::style('//cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.3.1/fullcalendar.print.css');
    $cal .= $calendar->calendar();
    $cal .= $calendar->script();
    return $cal;
}

/**
 * @return array
 */
function get_my_tasks()
{
    $taskobjects = Auth::user()->tasks;
    $tasks = array();

    foreach ($taskobjects as $task) {
        $event = \Calendar::event(
            $task->title, //event title
            $task->description, //event description
            $task->full_date, //full day event?
            $task->start_date, //start time (you can also use Carbon instead of DateTime)
            $task->end_date, //end time (you can also use Carbon instead of DateTime)
            $task->status,
            $task->id
        );
        $tasks[] = $event;
    }
    return $tasks;
}

/**
 * @param $url
 * @param array $cacheoptions
 * @return mixed
 */
function get_oembed_object_code($url, $cacheoptions = array())
{
    $object = get_oembed_object($url, $cacheoptions);
    return $object->code;
}

/**
 * @param $url
 * @param array $cacheoptions
 * @return mixed
 */
function get_oembed_object($url, $cacheoptions = array())
{

    $defaults = array('lifetime' => 300);
    $cacheoptions = array_merge($defaults, $cacheoptions);
    $object = \Oembed::cache($url, $cacheoptions);
    return $object;
    /*
    $info = \Oembed::get($url);
    $info->title; //The page title
    $info->description; //The page description
    $info->url; //The canonical url
    $info->type; //The page type (link, video, image, rich)
    $info->images; //List of all images found in the page
    $info->image; //The image choosen as main image
    $info->imageWidth; //The with of the main image
    $info->imageHeight; //The height  of the main image
    $info->code; //The code to embed the image, video, etc
    $info->width; //The with of the embed code
    $info->height; //The height of the embed code
    $info->aspectRatio; //The aspect ratio (width/height)
    $info->authorName; //The (video/article/image/whatever) author
    $info->authorUrl; //The author url
    $info->providerName; //The provider name of the page (youtube, twitter, instagram, etc)
    $info->providerUrl; //The provider url
    $info->providerIcons; //All provider icons found in the page
    $info->providerIcon; //The icon choosen as main icon
    */

}

/**
 * @param $row
 * @param $box
 * @param $grid
 * @return bool
 */
function grid_has($row, $box, $grid)
{
    return isset($grid[$row][$box]);
}

/**
 * @param $url
 * @return string
 */
function redirect_script($url)
{
    return "<script>document.location='{$url}';</script>";
}

/**
 * @param $metas
 * @param $object
 * @param bool $remove_old
 */
function update_meta($metas, $object, $remove_old = false)
{
    if ($remove_old) {
        $old = $object->metasArray();
        foreach ($old as $k => $v) {
            $object->unsetMeta($k);
        }
    }
    foreach ($metas as $key => $meta) {
        if (trim($meta['value'])) {
            $object->setMeta($meta['name'], $meta['value']);
        }
    }
}

/**
 * @param $object
 * @return string
 */
function place_slug($object)
{
    $html = '<div class="clearfix">&nbsp;</div>';
    $html .= Form::label(Lang::get('strings.slug')) . ': <span class="small">' . home_url() . '/</span> ';
    $html .= Form::text('slug', $object->slug, array('class' => 'small-input small incognito', 'placeholder' => \Lang::get('strings.slug')));
    $html .= "<a href='" . url($object->slug) . "' target='_blank'>" . trans('strings.view') . "</a>";
    $html .= '<div class="clearfix">&nbsp;</div>';
    return $html;
}

/**
 * @param $name
 * @param null $default
 * @return mixed
 */
function get_config_value($name, $default = null)
{
    return \Config::get('settings.cms.' . $name, $default);
}

/**
 * @param $str
 * @param null $limit
 * @param string $end
 * @return string
 */
function the_excerpt($str, $limit = null, $end = '...')
{
    $limit = get_config_value('excerpt_length', $limit);
    return mb_substr(html_entity_decode(strip_tags($str)), 0, $limit, 'utf-8') . $end;
}

/**
 * @return array
 */
function get_static_tpls()
{
    $tpls = array();
    $files = \File::files(public_path() . '/lp-includes/app/views/common/blocks/static');
    foreach ($files as $file) {
        $file = str_replace(array(public_path() . '/lp-includes/app/views/', '.htm'), '', $file);
        $filename = explode("/", $file);
        $tpls[$file] = last($filename);
    }
    return $tpls;
}

/**
 * @return bool
 */
function has_session()
{
    if (LP_SESSION_DRIVER == 'database') {
        $sessions = \Sessions::all();
        $users = array();
        foreach ($sessions as $session) {
            $payload = $session->payload;
            unset($payload['_token']);
            unset($payload['url']);
            unset($payload['flash']);
            unset($payload['_sf2_meta']);
            $values = array_values($payload);
            if (isset($values[0])) {
                $uid = $values[0];

                if ($uid == \Auth::id()) {
                    return true;
                }
            }

        }

        return false;
    }
    return true;
}

/**
 * Clears cache and removes compiled views
 */
function app_clear_cache()
{
    $files = \File::files(storage_path() . '/views');
    foreach ($files as $file) {
        \File::delete($file);
    }
    \Cache::flush();
}

/**
 * @param $name
 * @return \Illuminate\Database\Query\Builder|Settings
 */
function get_db_setting($name)
{
    return \Settings::whereSettingName($name);
}

/**
 * @return bool
 */
function allow_comments()
{
    return ((int)get_config_value('allow_comments') && Auth::check());
}

/**
 * @return string
 */
function honey_pot()
{
    \Session::put('honeypot', true);
    return \Form::text('hn_pt', '', ['class' => 'hidden', 'style' => 'display:none']);
}

/**
 * @return int
 */
function auto_approve_comments()
{
    return (int)get_config_value('auto_approve_comments');
}

/**
 * @param $comment
 * @return string
 */
function reply_link($comment)
{
    $link = "<a onclick='jQuery(\"#parent_id\").val(\"{$comment->id}\");jQuery(\"#comment_title\").val(\"Re: {$comment->title}\")' class='reply_link' href='#comment_form'>" . Lang::get('strings.reply') . "</a>";
    return $link;
}

/**
 * @param $object
 * @return mixed
 */
function comments_count($object)
{
    return $object->comments->count();
}

/**
 * @param int $limit
 * @return string
 */
function get_secure_string($limit = 5)
{
    return substr(\Config::get('app.key'), 0, $limit);
}

/**
 * @return string
 */
function get_current_locale()
{
    if (\Session::has('current_locale')) {
        return \Session::get('current_locale');
    }
    return \Config::get('app.locale');
}

/**
 * @return string
 */
function get_default_locale()
{
    return get_config_value('default_locale');
}

/**
 * @return array
 */
function get_available_locales()
{
    $langdirs = \File::directories(app_path() . '/lang');
    $locales = array();
    foreach ($langdirs as $file) {
        $locales[] = str_replace(app_path() . '/lang/', '', $file);

    }
    return $locales;
}

/**
 * @param $str
 * @return array|mixed|string
 */
function t($str, $options = array())
{
    $str = Lang::get($str, $options);

    if (is_array($str)) {
        return end($str);
    }
    return $str;
}

/**
 * @return int
 */
function page_cache_time()
{
    return (int)get_config_value('page_cache_time');
}

/**
 * @param bool $url
 * @param $locale
 * @return array|bool|string
 */
function locale_url($url = false, $locale)
{
    if (!$url) {
        $url = Request::path();
    }
    $locales = get_available_locales();

    $url = explode("/", $url);
    foreach ($locales as $loc) {
        if ($url[0] == $loc) {
            unset($url[0]);
        }
    }
    $url = implode("/", $url);


    $url = trim($url, '/');

    if ($locale !== get_default_locale()) {
        $url = url($locale . '/' . $url, ['no_locale' => 1]);
    } else {
        $url = url($url, ['no_locale' => 1]);
    }

    return $url;
}

/**
 * @return string
 */
function hreflang()
{
    $locales = get_available_locales();
    $html = '';
    foreach ($locales as $locale) {
        if ($locale !== get_default_locale()) {
            $html .= "<link rel='alternate' hreflang='{$locale}' href='" . locale_url(0, $locale) . "' />\n";
        }

    }
    return $html;
}

function get_theme_views()
{
    //if a dir named pages exists
    if (\File::isDirectory(the_views_path() . '/pages')) {
        //read from there
        $files = \File::files(the_views_path() . '/pages');
        $pre = 'pages/';
    } else {
        //read from theme root
        $files = \File::files(the_views_path());
        $pre = '';
    }
    $views = array();
    foreach ($files as $view) {

        $tpl = basename($view);
        $tpl = explode(".", $tpl);
        unset($tpl[count($tpl) - 1]);
        $views[get_theme_domain() . '::' . $pre . implode(".", $tpl)] = implode(".", $tpl);
    }
    return $views;
}

function get_theme_editor_templates()
{
    if (\File::isDirectory(the_views_path() . '/templates')) {
        $files = \File::files(the_views_path() . '/templates');
        foreach ($files as $view) {

            $tpl = basename($view);
            $tpl = explode(".", $tpl);
            unset($tpl[count($tpl) - 1]);

            $data = get_file_data($view,array('title'=>implode(".", $tpl),'description'=>implode(".", $tpl)));
            $url = str_replace(the_views_path(),theme_url(),$view);

            $views[] = array(
                'title' => $data['title'],
                'url' => $url,
                'description' => $data['description']
            );
        }
        return json_encode($views);
    }
    return json_encode(array());
}


function get_file_data($file,$default)
{

    error_reporting(0);
    // We don't need to write to the file, so just open for reading.
    $fp = fopen($file, 'r');
    $data = array();
    // Pull only the first 8kiB of the file in.
    $file_data = fread($fp, 8192);

    // PHP will close file handle, but we are good citizens.
    fclose($fp);

    // Make sure we catch CR-only line endings.
    $file_data = str_replace("\r", "\n", $file_data);

    preg_match("#<!--(.*)-->#si",$file_data,$block);

    if(isset($block[1])){
        $comments = trim($block[1]);

        $comments = explode(PHP_EOL,$comments);
        foreach($comments as $line){
            $line = mb_strtolower($line,'utf-8');
            $line = strip_tags($line);
            $line = explode(":",$line);
            $data[trim($line[0])] = trim($line[1]);
        }
    }


    return array_merge($default,$data);
}

function fire_and_show($event){
    $result = \Event::fire($event);
    return implode("",$result);
}
function inform_admin($error){
    \Mail::send('emails.error_reports', $error, function($message)
    {
        $message->to(get_config_value('admin_email'), get_config_value('brand'))->subject('Error Report for '.$_SERVER['SERVER_NAME']);
    });
}


function log_event($type,$title){
    $log = new \Logs;
    $log->log_type = $type;
    $log->title = $title;

    $log_data = array(
        'request'   =>  $_REQUEST,
        'ip'        =>  $_SERVER['REMOTE_ADDR'],
        'time'      =>  time(),
        'browser'   =>  $_SERVER['HTTP_USER_AGENT']
    );

    if(!\Auth::guest()){
        $log_data['user'] = \Auth::user();
    }else{
        $log_data['user'] = array();
    }

    $log->content = $log_data;
    $log->save();
}

function first_check(){
    try {
        $groupcheck = Sentry::findGroupById(1);
        return;
    } catch (Exception $e) {

        Artisan::call('key:generate');
        Artisan::call('optimize');

        $root = Sentry::createGroup(array(
            'name' => 'Root',
            'permissions' => array(
                'root' => 1,
                'admin' => 1,
                'editor' => 1,
                'author' => 1,
                'member' => 1,
            ),
        ));

        $admin = Sentry::createGroup(array(
            'name' => 'Administrator',
            'permissions' => array(
                'root' => 0,
                'admin' => 1,
                'editor' => 1,
                'author' => 1,
                'member' => 1,
            ),
        ));

        $editor = Sentry::createGroup(array(
            'name' => 'Editor',
            'permissions' => array(
                'root' => 0,
                'admin' => 0,
                'editor' => 1,
                'author' => 1,
                'member' => 1,
            ),
        ));

        $author = Sentry::createGroup(array(
            'name' => 'Author',
            'permissions' => array(
                'root' => 0,
                'admin' => 0,
                'editor' => 0,
                'author' => 1,
                'member' => 1,
            ),
        ));

        $member = Sentry::createGroup(array(
            'name' => 'Member',
            'permissions' => array(
                'root' => 0,
                'admin' => 0,
                'editor' => 0,
                'author' => 0,
                'member' => 1,
            ),
        ));
        $user = Sentry::findUserById(1);
        $user->addGroup($root);
    }
}

function register_default_content_blocks(){
    register_content_block(array(
        'default_title_block' => array(
            'name' => 'default_title_block',
            'title' => \Lang::get('strings.title'),
            'tpl' => array(
                'common/blocks/default_title' => 'default'
            ),
            'model' => '\Widget',
            'action' => 'getblockdata',
            'params_tpl' => 'common/blocks/param_tpls/default_title',
            'params' => array('title' => 'Custom block', 'heading_level' => 1, 'classname' => 'page-header'),
            'params_title'=>'title',
            'multiple' => true,
            'configurable' => true
        ),
        'default_image_block' => array(
            'name' => 'default_image_block',
            'title' => \Lang::get('strings.image'),
            'tpl' => array(
                'common/blocks/image' => 'default'
            ),
            'model' => '\Image',
            'action' => 'find',
            'params_action' => 'lists',
            'params_args' => array('original_filename', 'id'),
            'params_title'=>0,
            'params' => 1,
            'multiple' => true,
            'configurable' => true
        ),
        'default_html_block' => array(
            'name' => 'default_html_block',
            'title' => \Lang::get('strings.html'),
            'tpl' => array(
                'common/blocks/html' => 'default'
            ),
            'model' => '\Widget',
            'action' => 'getblockdata',
            'params_tpl' => 'common/blocks/param_tpls/html',
            'params' => array('text' => 'html block'),
            'params_title'=>'text',
            'multiple' => true,
            'configurable' => true
        ),
        'default_feed_block' => array(
            'name' => 'default_feed_block',
            'title' => \Lang::get('strings.rss'),
            'tpl' => array(
                'common/blocks/rss' => 'default'
            ),
            'model' => '\Widget',
            'action' => 'getblockdata',
            'params_tpl' => 'common/blocks/param_tpls/rss',
            'params' => array('url' => 'http://feeds.feedburner.com/simplepie', 'num' => 5),
            'params_title'=>'url',
            'multiple' => true,
            'configurable' => true
        ),
        'default_static_block' => array(
            'name' => 'default_static_block',
            'title' => \Lang::get('strings.static_template'),
            'tpl' => get_static_tpls(),
            'multiple' => true,
            'configurable' => true,
            'icon'  =>  'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADAAAAAwCAYAAABXAvmHAAABN0lEQVRoge2aMUoEQRBFK9JMz2HuCZxXA32Ayc29gRfwBsYb7xm8gbCYKYtH0AURdw2U3WhBunFmWmSqWvvDT2f+Y6j6TNMiIhJCOAKugEdgA2wdeQ3cq+plCOFQYgHHTdPcOQg6xjdd1x3EANcOgo22ql7EAM/WoTK9iAGsA+X6vXSA7SiAZNoNVAGsNQlAVIIr+krHGwD9JZiWjkOA3hJMSschwFAJLoafYgswtLNfvAMU/wWKn4Gyt5BI0gOflNADqnoKzIEl3//BrYEHVZ0BJ24AVPUc+Bixgb76rW3bMxcAwGtm+L2XXgB+Ej5rrirAnwaYUhXAWu5m4LfeVQGKBphSFcBadQYqgEeAKVUBrFUBrPW/ATy7dIBNDPDkIFSOb2OA4q8alH3ZQyQ5YbYOGbv3xHsHsl2zbuUKdMwAAAAASUVORK5CYII='
        ),
        'default_tagcloud_block' => array(
            'name' => 'default_tagcloud_block',
            'title' => \Lang::get('strings.tag_cloud'),
            'tpl' => array(
                'common/blocks/tagcloud' => 'default'
            ),
            'model' => '\Tag',
            'action' => 'all',
            'params' => array('*'),
            'multiple' => false,
            'configurable' => false
        ),
        'default_oembed_block' => array(
            'name' => 'default_oembed_block',
            'title' => \Lang::get('strings.media'),
            'tpl' => array(
                'common/blocks/oembed' => 'default'
            ),
            'model' => '\Widget',
            'action' => 'getblockdata',
            'params_tpl' => 'common/blocks/param_tpls/oembed',
            'params' => array('url' => 'https://www.youtube.com/watch?v=PP1xn5wHtxE'),
            'params_title'=>'url',
            'multiple' => true,
            'configurable' => true
        ),
        'default_main_content' => array(
            'name' => 'default_main_content',
            'title' => \Lang::get('strings.main_content'),
            'tpl' => array(
                'common/blocks/maincontent' => 'default'
            ),
            'multiple' => false,
            'configurable' => false
        ),
        'default_language_switcher' => array(
            'name' => 'default_language_switcher',
            'title' => \Lang::get('strings.locales'),
            'tpl' => array(
                'common/blocks/language_switcher' => 'Language Switcher'
            ),
            'multiple' => true,
            'configurable' => false
        ),
        'default_page_block' => array(
            'name' => 'default_page_block',
            'title' => \Lang::get('strings.pages'),
            'tpl' => get_theme_views(),
            'multiple' => true,
            'configurable' => true,
            'model' => '\Page',
            'action' => 'find',
            'params_action'=>'lists',
            'params' => 1,
            'params_title'=>0,
            'params_args' => array('title', 'id'),
        ),
    ));
}