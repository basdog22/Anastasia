<?php


/**
 * Tag
 *
 * @property integer $id
 * @property string $slug
 * @property string $name
 * @property boolean $suggest
 * @property integer $count
 * @method static \Illuminate\Database\Query\Builder|\Tag whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Tag whereSlug($value)
 * @method static \Illuminate\Database\Query\Builder|\Tag whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\Tag whereSuggest($value)
 * @method static \Illuminate\Database\Query\Builder|\Tag whereCount($value)
 */
class Tag extends \Eloquent{
    protected $table='tagging_tags';

    public function save(Array $options = array()){
        try{
            return parent::save($options);
        }catch(Exception $e){
            App::abort(500,'ERROR!');
        }
    }
}