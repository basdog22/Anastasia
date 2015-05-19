<?php


use \Cviebrock\EloquentSluggable\SluggableInterface;
use \Cviebrock\EloquentSluggable\SluggableTrait;
use \Lanz\Commentable\Commentable;
use Mmanos\Metable\Metable;


/**
 * Class Post
 *
 * @package Plugins\posts\models
 * @property integer $id
 * @property string $slug
 * @property string $title
 * @property string $content
 * @property boolean $status
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Venturecraft\Revisionable\Revision[] $revisionHistory
 * @property-read \Illuminate\Database\Eloquent\Collection|\Lanz\Commentable\Comment[] $comments
 * @method static \Illuminate\Database\Query\Builder|\Page whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Page whereSlug($value)
 * @method static \Illuminate\Database\Query\Builder|\Page whereTitle($value)
 * @method static \Illuminate\Database\Query\Builder|\Page whereContent($value)
 * @method static \Illuminate\Database\Query\Builder|\Page whereStatus($value)
 * @method static \Illuminate\Database\Query\Builder|\Page whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Page whereUpdatedAt($value)
 * @property string $layout
 * @method static \Illuminate\Database\Query\Builder|\Page whereLayout($value)
 * @property-read mixed $default_metas
 * @property-read \Illuminate\Database\Eloquent\Collection|\$this->metaModel()[] $metas
 */
class Page extends \Eloquent implements SluggableInterface
{
    use SluggableTrait;
    use \Venturecraft\Revisionable\RevisionableTrait;
    use Commentable,Metable,Feedable,Translatable;

    protected $table = 'pages';
    protected $appends = array('default_metas');

    protected $sluggable = array(
        'build_from' => 'title',
        'save_to'    => 'slug',
    );

    protected $meta_model = 'Meta';
    protected $metable_table = 'page_metas';

    protected $translatable = array('title','content');



    public function getDefaultMetasAttribute(){
        $metas = array();
        $desc = $this->meta('meta_description');
        $keyw = $this->meta('meta_keywords');
        if(is_null($desc)){
            $metas[] = array('name'=>'meta_description','value'=>'');
        }
        if(is_null($keyw)){
            $metas[] = array('name'=>'meta_keywords','value'=>'');
        }
        return $metas;
    }

    public function delete(){
        $this->comments()->delete();
        return parent::delete();
    }

    public function renderContent(){
        $content = $this->content;
        $oembed = new \AutoEmbed;
        $content = $oembed->parse($content);
        return $content;
    }

    public function save(array $options = array()){

        $this->title = ($this->title)?$this->title:'';
        $this->content = ($this->content)?$this->content:'';


        try{
            return parent::save($options);
        }catch(Exception $e){
            App::abort(500,'ERROR!');
        }
    }
}