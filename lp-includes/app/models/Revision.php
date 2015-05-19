<?php

/**
 * Revision
 *
 * @property integer $id
 * @property string $revisionable_type
 * @property integer $revisionable_id
 * @property integer $user_id
 * @property string $key
 * @property string $old_value
 * @property string $new_value
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\Revision whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Revision whereRevisionableType($value)
 * @method static \Illuminate\Database\Query\Builder|\Revision whereRevisionableId($value)
 * @method static \Illuminate\Database\Query\Builder|\Revision whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\Revision whereKey($value)
 * @method static \Illuminate\Database\Query\Builder|\Revision whereOldValue($value)
 * @method static \Illuminate\Database\Query\Builder|\Revision whereNewValue($value)
 * @method static \Illuminate\Database\Query\Builder|\Revision whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Revision whereUpdatedAt($value)
 */
class Revision extends Eloquent
{

    protected $table='revisions';


    public function save(Array $options = array()){
        try{
            return parent::save($options);
        }catch(Exception $e){
            App::abort(500,'ERROR!');
        }
    }
}