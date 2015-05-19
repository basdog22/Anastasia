<?php

/**
 * Settings
 *
 * @method static \Settings ofOption($option)
 * @property integer $id
 * @property string $namespace
 * @property string $setting_name
 * @property string $setting_value
 * @property boolean $autoload
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\Settings whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Settings whereNamespace($value)
 * @method static \Illuminate\Database\Query\Builder|\Settings whereSettingName($value)
 * @method static \Illuminate\Database\Query\Builder|\Settings whereSettingValue($value)
 * @method static \Illuminate\Database\Query\Builder|\Settings whereAutoload($value)
 * @method static \Illuminate\Database\Query\Builder|\Settings whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Settings whereUpdatedAt($value)
 */
class Settings extends Eloquent
{

    protected $table='settings';

    protected $fillable = array('namespace','setting_name', 'setting_value','autoload');

    public function scopeOfOption($query,$option){
        return $query->where('setting_name','=',$option);
    }

    public function save(Array $options = array()){
        try{
            return parent::save($options);
        }catch(Exception $e){
            App::abort(500,'ERROR!');
        }
    }

}