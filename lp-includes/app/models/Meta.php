<?php

use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Database\Eloquent\SoftDeletingTrait;

/**
 * Meta
 *
 * @property integer $id
 * @property string $name
 * @property integer $num_items
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon $deleted_at
 * @method static \Illuminate\Database\Query\Builder|\Meta whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Meta whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\Meta whereNumItems($value)
 * @method static \Illuminate\Database\Query\Builder|\Meta whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Meta whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Meta whereDeletedAt($value)
 */
class Meta extends Eloquent
{
	use SoftDeletingTrait;
	
	/**
	 * The table name for t his model.
	 *
	 * @var string
	 */
	protected $table = 'metas';
	
	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = array('deleted_at');
	
	/**
	 * The attributes that aren't mass assignable.
	 *
	 * @var array
	 */
	protected $guarded = array('id');
	
	/**
	 * The dates array.
	 *
	 * @var array
	 */
	protected $dates = array('deleted_at');

    public function save(Array $options = array()){
        try{
            return parent::save($options);
        }catch(Exception $e){
            App::abort(500,'ERROR!');
        }
    }
}
