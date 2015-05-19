<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;
use Mmanos\Metable\Metable;

/**
 * User
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\$this->metaModel()[] $metas
 * @property integer $id
 * @property string $username
 * @property string $firstname
 * @property string $lastname
 * @property string $email
 * @property string $password
 * @property string $permissions
 * @property boolean $activated
 * @property string $activation_code
 * @property string $activated_at
 * @property string $last_login
 * @property string $persist_code
 * @property string $remember_token
 * @property string $reset_password_code
 * @property string $first_name
 * @property string $last_name
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\User whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\User whereUsername($value)
 * @method static \Illuminate\Database\Query\Builder|\User whereFirstname($value)
 * @method static \Illuminate\Database\Query\Builder|\User whereLastname($value)
 * @method static \Illuminate\Database\Query\Builder|\User whereEmail($value)
 * @method static \Illuminate\Database\Query\Builder|\User wherePassword($value)
 * @method static \Illuminate\Database\Query\Builder|\User wherePermissions($value)
 * @method static \Illuminate\Database\Query\Builder|\User whereActivated($value)
 * @method static \Illuminate\Database\Query\Builder|\User whereActivationCode($value)
 * @method static \Illuminate\Database\Query\Builder|\User whereActivatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\User whereLastLogin($value)
 * @method static \Illuminate\Database\Query\Builder|\User wherePersistCode($value)
 * @method static \Illuminate\Database\Query\Builder|\User whereRememberToken($value)
 * @method static \Illuminate\Database\Query\Builder|\User whereResetPasswordCode($value)
 * @method static \Illuminate\Database\Query\Builder|\User whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\User whereUpdatedAt($value)
 * @property-read \Illuminate\Database\Eloquent\Collection|\Task[] $tasks
 * @property-read mixed $full_name
 * @property-read mixed $image
 * @property-read mixed $default_metas

 */
class User extends Eloquent implements UserInterface, RemindableInterface {

	use UserTrait, RemindableTrait,Metable;
    protected  $appends = array('full_name','image','default_metas');


	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users';

    protected $meta_model = 'Meta';
    protected $metable_table = 'user_metas';

    public static $rules = array(
        'username'=>'required|alpha|min:3',
        'email'=>'required|email|unique:users',
        'password'=>'required|alpha_num|between:6,12|confirmed',
        'password_confirmation'=>'required|alpha_num|between:6,12'
    );
	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = array('password');

    /**
     * @var array
     */
    protected $fillable = array('username','email', 'password');


    public function tasks(){
        return $this->hasMany('Task');
    }

    public function getFullNameAttribute(){
        $fullname = $this->firstname." ".$this->lastname;
        return $fullname;
    }

    public function getImageAttribute(){
        return 'http://www.gravatar.com/avatar/'.md5($this->email);
    }

    public function getDefaultMetasAttribute(){
        $metas = array();
        $defaults = get_user_default_metas();
        foreach($defaults as $v){
            foreach($v as $k=>$meta){
                $value = $this->meta($meta['name']);
                if(is_null($value)){
                    $metas[] = array('name'=>$meta['name'],'value'=>$meta['value']);
                }
            }
        }
        return $metas;
    }

    public function save(array $options = array()){

        $this->username = ($this->username)?$this->username:'';
        $this->firstname = ($this->firstname)?$this->firstname:'';
        $this->lastname = ($this->lastname)?$this->lastname:'';
        $this->remember_token = ($this->remember_token)?$this->remember_token:'';

        try{
            return parent::save($options);
        }catch(Exception $e){
            App::abort(500,'ERROR!');
        }
    }

}
