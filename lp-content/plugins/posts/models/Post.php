<?php

namespace Plugins\posts\models;


use \Cviebrock\EloquentSluggable\SluggableInterface;
use \Cviebrock\EloquentSluggable\SluggableTrait;
use \Lanz\Commentable\Commentable;
use Mmanos\Metable\Metable;

/**
 * Class Post
 * @package Plugins\posts\models
 */
class Post extends \Eloquent implements SluggableInterface
{
    use SluggableTrait;
    use \Conner\Tagging\TaggableTrait;
    use \Venturecraft\Revisionable\RevisionableTrait;
    use Commentable,Metable,\Feedable,\Translatable;

    /**
     * @var string
     */
    protected $table='posts';

    protected $meta_model = 'Meta';
    protected $metable_table = 'post_metas';

    protected $appends = array('user_fullname','category_name','default_metas');


    protected $translatable = array('title','content');
    /**
     * @var array
     */
    protected $sluggable = array(
        'build_from' => 'title',
        'save_to'    => 'slug',
    );

    public function image(){
        return $this->belongsTo("Image");
    }

    public function getUserFullnameAttribute(){
        return $this->user->full_name;
    }

    public function getCategoryNameAttribute(){
        return $this->category->title;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category(){
        return $this->belongsTo("\\Plugins\\posts\\models\\Category");
    }

    public function user(){
        return $this->belongsTo('User');
    }

    /**
     * @param $query
     * @return mixed
     */
    public function scopeOfNormal($query){
        return $query->where('status','=',0)->orWhere('status','=',1);
    }

    /**
     * @param $query
     * @return mixed
     */
    public function scopeOfPublished($query){
        return $query->where('status','=',1);
    }

    /**
     * @param $query
     * @return mixed
     */
    public function scopeOfDraft($query){
        return $query->where('status','=',0);
    }

    /**
     * @param $query
     * @return mixed
     */
    public function scopeOfTrash($query){
        return $query->where('status','=',2);
    }


    public function getDefaultMetasAttribute(){
        $metas = array(
            array('name'=>'meta_description','value'=>''),
            array('name'=>'meta_keywords','value'=>''),
        );
        return $metas;
    }

    public function delete(){
        $this->comments()->delete();
        $tags = $this->tagNames();
        foreach($tags as $tag){
            $this->untag($tag);
        }
        return parent::delete();
    }

    public function renderContent(){
        $content = $this->content;
        $oembed = new \AutoEmbed;
        $content = $oembed->parse($content);
        return $content;
    }

    /**
     * @return mixed|string
     */
    public function __toString()
    {
        return $this->title;
    }

    public function save(array $options = array()){

        $this->title = ($this->title)?$this->title:'';
        $this->content = ($this->content)?$this->content:'';
	$this->category_id = ($this->category_id)?$this->category_id:0;
	$this->image_id = ($this->image_id)?$this->image_id:0;

        return parent::save($options);
    }

}
