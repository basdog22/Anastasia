<?php namespace Lanz\Commentable;

use Config;
use Baum\Node;
use Illuminate\Database\Eloquent\Model;

/**
 * Comment
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
 * @property-read \ $commentable
 * @property-read \Config::get('auth.model') $user
 * @property-read Lanz\Commentable\Comment $parent
 * @property-read \Illuminate\Database\Eloquent\Collection|Lanz\Commentable\Comment[] $children
 * @method static \Illuminate\Database\Query\Builder|\Lanz\Commentable\Comment whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Lanz\Commentable\Comment whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Lanz\Commentable\Comment whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Lanz\Commentable\Comment whereTitle($value)
 * @method static \Illuminate\Database\Query\Builder|\Lanz\Commentable\Comment whereBody($value)
 * @method static \Illuminate\Database\Query\Builder|\Lanz\Commentable\Comment whereParentId($value)
 * @method static \Illuminate\Database\Query\Builder|\Lanz\Commentable\Comment whereLft($value)
 * @method static \Illuminate\Database\Query\Builder|\Lanz\Commentable\Comment whereRgt($value)
 * @method static \Illuminate\Database\Query\Builder|\Lanz\Commentable\Comment whereDepth($value)
 * @method static \Illuminate\Database\Query\Builder|\Lanz\Commentable\Comment whereCommentableId($value)
 * @method static \Illuminate\Database\Query\Builder|\Lanz\Commentable\Comment whereCommentableType($value)
 * @method static \Illuminate\Database\Query\Builder|\Lanz\Commentable\Comment whereUserId($value)
 * @method static \Baum\Node withoutNode($node)
 * @method static \Baum\Node withoutSelf()
 * @method static \Baum\Node withoutRoot()
 * @method static \Baum\Node limitDepth($limit)
 */
class Comment extends Node
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public $fillable = ['title', 'body'];

    /**
     * Helper method to check if a comment has children
     *
     * @return bool
     */
    public function hasChildren()
    {
        return $this->children()->count() > 0;
    }

    /**
     *
     *
     * @return mixed
     */
    public function commentable()
    {
        return $this->morphTo();
    }

    /**
     * Comment belongs to a user.
     *
     * @return User
     */
    public function user()
    {
        return $this->belongsTo(Config::get('auth.model'));
    }

}
