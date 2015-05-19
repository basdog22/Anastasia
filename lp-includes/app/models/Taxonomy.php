<?php


use Cviebrock\EloquentSluggable\SluggableInterface;
use Cviebrock\EloquentSluggable\SluggableTrait;


/**
 * Taxonomy
 *
 * @property-read Taxonomy $parent
 * @property-read \Illuminate\Database\Eloquent\Collection|Taxonomy[] $children
 * @property-write mixed $parent_id
 * @property integer $id
 * @property integer $_lft
 * @property integer $_rgt
 * @property string $name
 * @property string $title
 * @property boolean $active
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\Taxonomy whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Taxonomy whereLft($value)
 * @method static \Illuminate\Database\Query\Builder|\Taxonomy whereRgt($value)
 * @method static \Illuminate\Database\Query\Builder|\Taxonomy whereParentId($value)
 * @method static \Illuminate\Database\Query\Builder|\Taxonomy whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\Taxonomy whereTitle($value)
 * @method static \Illuminate\Database\Query\Builder|\Taxonomy whereActive($value)
 * @method static \Illuminate\Database\Query\Builder|\Taxonomy whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Taxonomy whereUpdatedAt($value)
 * @property integer $sort
 * @method static \Illuminate\Database\Query\Builder|\Taxonomy whereSort($value)
 */
class Taxonomy extends Kalnoy\Nestedset\Node implements SluggableInterface
{
    use SluggableTrait,\Translatable;
    protected $table='taxonomies';

    protected $translatable = array('title');

    protected $sluggable = array(
        'build_from' => 'title',
        'save_to'    => 'name',
    );

    public function save(Array $options = array()){
        try{
            return parent::save($options);
        }catch(Exception $e){
            App::abort(500,'ERROR!');
        }
    }

}