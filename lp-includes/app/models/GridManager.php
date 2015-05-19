<?php

/**
 * GridManager
 *
 * @property integer $id
 * @property string $position
 * @property string $grid
 * @property string $layout
 * @property string $name
 * @property string $title
 * @property string $order_weight
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\GridManager whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\GridManager wherePosition($value)
 * @method static \Illuminate\Database\Query\Builder|\GridManager whereGrid($value)
 * @method static \Illuminate\Database\Query\Builder|\GridManager whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\GridManager whereTitle($value)
 * @method static \Illuminate\Database\Query\Builder|\GridManager whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\GridManager whereUpdatedAt($value)
 * @property string $theme_domain
 * @property string $tpl
 * @property string $params
 * @method static \Illuminate\Database\Query\Builder|\GridManager whereThemeDomain($value)
 * @method static \Illuminate\Database\Query\Builder|\GridManager whereLayout($value)
 * @method static \Illuminate\Database\Query\Builder|\GridManager whereTpl($value)
 * @method static \Illuminate\Database\Query\Builder|\GridManager whereParams($value)
 * @method static \Illuminate\Database\Query\Builder|\GridManager whereOrderWeight($value)
 */
class GridManager extends \Eloquent{

    use \Translatable;

    protected $table='blocks';

    protected $fillable = array('theme_domain','position', 'grid','layout','name','title','tpl','params','order_weight');

    protected $translatable = array('params');

    public function setParamsAttribute($value){
        $this->attributes['params'] = safe_serialize($value);
    }

    public function getParamsAttribute($value){
        return safe_unserialize($value);
    }

    public function save(Array $options = array()){
        try{
            return parent::save($options);
        }catch(Exception $e){
            App::abort(500,'ERROR!');
        }
    }
}