<?php

/**
 * Comments
 *
 * @property integer $id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $title
 * @property string $body
 * @property integer $parent_id
 * @property integer $lft
 * @property integer $rgt
 * @property integer $depth
 * @property integer $commentable_id
 * @property string $commentable_type
 * @property integer $user_id
 * @method static \Illuminate\Database\Query\Builder|\Comments whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Comments whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Comments whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Comments whereTitle($value)
 * @method static \Illuminate\Database\Query\Builder|\Comments whereBody($value)
 * @method static \Illuminate\Database\Query\Builder|\Comments whereParentId($value)
 * @method static \Illuminate\Database\Query\Builder|\Comments whereLft($value)
 * @method static \Illuminate\Database\Query\Builder|\Comments whereRgt($value)
 * @method static \Illuminate\Database\Query\Builder|\Comments whereDepth($value)
 * @method static \Illuminate\Database\Query\Builder|\Comments whereCommentableId($value)
 * @method static \Illuminate\Database\Query\Builder|\Comments whereCommentableType($value)
 * @method static \Illuminate\Database\Query\Builder|\Comments whereUserId($value)
 * @property boolean $status
 * @method static \Illuminate\Database\Query\Builder|\Comments whereStatus($value)
 * @property-read \User $user
 * @property-read mixed $item
 */
class Comments extends \Eloquent
{

    protected $table='comments';
    protected $appends = array('item');

    public function user(){
        return $this->belongsTo('User');
    }

    public function getItemAttribute(){
        $model = $this->commentable_type;
        $id = $this->commentable_id;
        return $model::find($id);
    }

    public function save(Array $options = array()){
        try{
            return parent::save($options);
        }catch(Exception $e){
            App::abort(500,'ERROR!');
        }
    }
}