<?php

/**
 * Class UsersController
 */
class UsersController extends \BackendbaseController
{

    /**
     * @var string
     */
    protected $layout = 'layouts.common.auth';

    /**
     * Show login
     */
    public function getLogin()
    {
        $this->layout->content = \View::make('backend/users/login');
    }



    /**
     * Logs out a user and redirects
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function getLogout()
    {
        \Event::fire('users.user.before_logout', array(\Auth::user()));
        \Auth::logout();
        \Event::fire('users.user.after_logout');
        log_event('Logout','User Logged out');
        return \Redirect::to('users/login')->withMessage(t('messages.you_logged_out'));
    }


    public function showlogs($logtype=false){
        $this->useBackendLayout();
        if($logtype){
            $logs = \Logs::where('log_type','=',$logtype)->orderBy('created_at','DESC')->paginate(get_config_value('paging'));
        }else{
            $logs = \Logs::orderBy('created_at','DESC')->paginate(get_config_value('paging'));
        }
        $this->layout->content = \View::make('backend/users/logs')->withLogs($logs);
    }

    /**
     * Logs a user in and redirects
     *
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function postSignin()
    {
        if (\Auth::attempt(array('email' => requested('email'), 'password' => requested('pass')), true)) {
            $user = \Auth::user();
            $user->last_login = new \DateTime();
            if(!$user->save()){
                return \Redirect::intended('/backend/dashboard')->withMessage(t('messages.error_occured'));
            }
            log_event('Login','Succesfull Login Attempt');
            return \Redirect::intended('/backend/dashboard');
        } else {
            //log the event
            log_event('Login','Unsuccesfull Login Attempt');
            return \Redirect::to('users/login')
                ->withMessage(t('messages.wrong_pass'))
                ->withInput();
        }
    }

    /**
     * Sets up the layout to use admin theme
     */
    public function useBackendLayout()
    {
        $this->layout = 'layouts.backend.laradmin';
        $this->setupLayout();
    }

    public function manageOnlineUsers(){
        $sessions = \Sessions::all();
        $users = array();
        foreach($sessions as $session){
            $payload = $session->payload;
            unset($payload['_token']);
            unset($payload['url']);
            unset($payload['flash']);
            unset($payload['_sf2_meta']);
            $values = array_values($payload);
            if(isset($values[0])){
                $uid = $values[0];

                $user = \User::find($uid);
                $user->session_id = $session->id;
                $users[] = $user;
            }

        }
        return \Paginator::make($users,$sessions->count(),\Config::get('settings.cms.paging'));
    }

    public function logoutuser($session_id){
        if(LP_SESSION_DRIVER=='database'){
            $session = \Sessions::find($session_id);
            $session->delete();
            return \Redirect::to('users/manage/online')->withMessage($this->notifyView(t('messages.user_logged_out'),'success'));
        }
        return \Redirect::to('users/manage/online')->withMessage($this->notifyView(t('messages.you_need_to_store_sessions_in_db'),'danger'));
    }

    /**
     * Shows users of group
     *
     * @param bool $groupname
     */
    public function manage($groupname=false)
    {
        add_breadcrumb(trans('strings.users'),url('users/manage'));
        $groups = \Sentry::findAllGroups();
        if(!$groupname){
            $users = \User::paginate(\Config::get('settings.cms.paging'));
        }elseif($groupname=='online'){
            if(LP_SESSION_DRIVER=='database'){
                $users = $this->manageOnlineUsers();
                add_breadcrumb(ucfirst($groupname),url('users/manage/'.$groupname));
            }else{
                //all we can do is this...
                $ten = time()-600;
                $tenminutes = date("Y-m-d H:i:s",$ten);
                $users = \User::where("last_login",'>',$tenminutes)->paginate(\Config::get('settings.cms.paging'));
                add_breadcrumb(ucfirst($groupname),url('users/manage/'.$groupname));
            }

        }else{
            $uids = array();
            $group = \Sentry::findGroupByName(ucfirst($groupname));
            $susers = \Sentry::findAllUsersInGroup($group);
            foreach($susers as $suser){
                $uids[] = $suser->id;
            }
            add_breadcrumb(ucfirst($groupname),url('users/manage/'.$groupname));
            $users = \User::whereIn('id',$uids)->paginate(\Config::get('settings.cms.paging'));
        }

        \Event::fire('backend.users.manage', array($users));
        $this->useBackendLayout();

        $this->layout->content = \View::make('backend/users/users')->with('users', $users)->withGroups($groups);
    }

    /**
     * Shows new user dialog
     *
     * @return mixed
     */
    public function newuser()
    {
        $this->useBackendLayout();
        $groups = \Sentry::findAllGroups();
        if (\Request::ajax()) {
            return \View::make('backend/users/newuser')->withGroups($groups);
        } else {
            $this->layout->content = \View::make('backend/users/newuser')->withGroups($groups);
        }
    }

    /**
     * Lists the user groups
     */
    public function groups()
    {
        add_breadcrumb(trans('strings.user_groups'),url('users/groups'));
        $this->useBackendLayout();
        $groups = \Sentry::findAllGroups();
//        ll($groups);
        $this->layout->content = \View::make('backend/users/groups')->withGroups($groups);
    }

    public function bulkdeletegroups(){
        $ids = requested('ids');
        foreach($ids as $id){
            $id = (int) $id;
            $group = \Sentry::findGroupById($id);
            $group->delete();
        }
        return \Response::json(array('type' => 'success', 'text' => t('messages.groups_deleted')));
    }

    /**
     * Changes permissions
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function editgrouppermissions()
    {


        $groupid = (int)requested('groupid');
        $perm = requested('perm');
        $newval = (int)requested('newval');
        try {
            $group = \Sentry::findGroupById($groupid);
            $perms = $group->getPermissions();
            $perms[$perm] = $newval;
            $group->permissions = $perms;
            if ($group->save()) {
                return \Response::json(array('type' => 'success', 'text' => t('messages.sort_saved')));
            }
        } catch (Cartalyst\Sentry\Groups\NameRequiredException $e) {
            return \Response::json(array('type' => 'danger', 'text' => $e->getMessage()));
        } catch (Cartalyst\Sentry\Groups\GroupExistsException $e) {
            return \Response::json(array('type' => 'danger', 'text' => $e->getMessage()));
        } catch (Cartalyst\Sentry\Groups\GroupNotFoundException $e) {
            return \Response::json(array('type' => 'danger', 'text' => $e->getMessage()));
        }

    }

    /**
     * Shows new group dialog
     *
     * @return \Illuminate\View\View
     */
    public function addgroup()
    {
        $this->useBackendLayout();
        if (\Request::ajax()) {
            return \View::make('backend/users/newgroup');
        } else {
            $this->layout->content = \View::make('backend/users/newgroup');
        }
    }

    /**
     * Adds a new group
     *
     * @return mixed
     */
    public function creategroup()
    {

        try {
            $name = requested('name');
            $perms = requested('perms');
            $member = \Sentry::createGroup(array(
                'name' => $name,
                'permissions' => $perms
            ));
            return \Redirect::back()->withMessage($this->notifyView(t('messages.user_group_created'),'success'));
        } catch (Cartalyst\Sentry\Groups\NameRequiredException $e) {
            return \Redirect::back()->withMessage($this->notifyView(t('messages.name_field_required'),'danger'));
        } catch (Cartalyst\Sentry\Groups\GroupExistsException $e) {
            return \Redirect::back()->withMessage($this->notifyView(t('messages.user_group_exists'),'danger'));
        }
    }

    /**
     * Deletes a group
     *
     * @param $groupid
     * @return mixed
     */
    public function delgroup($groupid){
        $groupid = (int) $groupid;
        try
        {
            $group = \Sentry::findGroupById($groupid);
            $group->delete();
            return \Redirect::back()->withMessage($this->notifyView(t('messages.user_group_deleted'),'success'));
        }
        catch (Cartalyst\Sentry\Groups\GroupNotFoundException $e)
        {
            return \Redirect::back()->withMessage($this->notifyView(t('messages.user_group_not_found'),'danger'));
        }
    }

    /**
     * Shows edit user dialog
     *
     * @param $userid
     * @return mixed
     */
    public function edituser($userid)
    {
        $groups = \Sentry::findAllGroups();
        $user = \Sentry::findUserById($userid);
        \Event::fire('backend.users.edit', array($user));
        $this->useBackendLayout();
        if (\Request::ajax()) {
            return \View::make('backend/users/edituser')->with('user', $user)->withGroups($groups);
        } else {
            $this->layout->content = \View::make('backend/users/edituser')->with('user', $user)->withGroups($groups);
        }
    }

    /**
     * Updates a user
     *
     * @return mixed
     */
    public function saveuser()
    {
        $groupid = (int) requested('user_group');
        $group = \Sentry::findGroupById($groupid);
        if(is_null($group)){
            return \Redirect::back()->withMessage($this->notifyView(t('messages.user_group_not_found')));
        }

        $userid = (int) requested('userid');
        $user = \Sentry::findUserById($userid);
        \Event::fire('backend.users.before_save', array($user));
        $user->firstname = requested('firstname');
        $user->lastname = requested('lastname');
        $user->email = requested('email');
        if (requested('password')) {
            $user->password = Hash::make(requested('password'));
        }

        if(!$user->save()){
            return \Redirect::to('users/manage/')->withMessage($this->notifyView(t('messages.error_occured'),'danger'));
        }

        //remove any groups from this user and add the new one

        $user->getGroups();
        foreach($user->groups as $oldgroup)
        {
            $user->removeGroup($oldgroup);
        }
        $user->addGroup($group);

        //now lets update and add metadata
        $metas = requested('metas');
        $newmeta = requested('newmetas');
        //update old
        $obj = \User::find($user->id);
        update_meta($metas,$obj,true);
        //add new
        update_meta($newmeta,$obj);

        \Event::fire('backend.users.after_save', array($user));
        return \Redirect::to('users/manage/')->withMessage($this->notifyView(t('messages.user_saved')));

    }

    /**
     * Loads profile dialog
     *
     * @param null $userid
     * @return $this
     */
    public function profile($userid = null)
    {
        $nocontact = false;
        if (!$userid) {
            $userid = \Auth::user()->id;
            $nocontact = true;
        }
        $user = \User::find($userid);

        \Event::fire('backend.users.profile', array($user));
        $this->useBackendLayout();
        if (\Request::ajax()) {
            return \View::make('backend/users/profile')->with('user', $user)->with('nocontact', $nocontact);
        } else {
            $this->layout->content = \View::make('backend/users/profile')->with('user', $user)->with('nocontact', $nocontact);
        }
    }

    /**
     * Adds a user
     *
     * @return mixed
     */
    public function adduser()
    {
        $user = new \User;
        $user->firstname = requested('firstname');
        $user->lastname = requested('lastname');
        $user->email = requested('email');
        $user->password = \Hash::make(requested('password'));
        \Event::fire('backend.users.before_add', array($user));
        if(!$user->save()){
            return \Redirect::to('users/manage/')->withMessage($this->notifyView(t('messages.error_occured'),'danger'));
        }
        $groupid = (int) requested('user_group');
        $group = \Sentry::findGroupById($groupid);
        if(is_null($group)){
            return \Redirect::back()->withMessage($this->notifyView(t('messages.user_group_not_found')));
        }
        $user = \Sentry::findUserById($user->id);
        $user->addGroup($group);
        return \Redirect::to('users/manage/')->withMessage($this->notifyView(t('messages.user_created')));
    }

}
