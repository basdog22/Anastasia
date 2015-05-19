<?php

/**
 * Theme
 *
 * @method static \Theme ofState($status)
 * @property integer $id
 * @property string $name
 * @property string $title
 * @property string $image
 * @property string $version
 * @property string $author
 * @property string $url
 * @property boolean $installed
 * @property boolean $active
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\Theme whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Theme whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\Theme whereTitle($value)
 * @method static \Illuminate\Database\Query\Builder|\Theme whereImage($value)
 * @method static \Illuminate\Database\Query\Builder|\Theme whereVersion($value)
 * @method static \Illuminate\Database\Query\Builder|\Theme whereAuthor($value)
 * @method static \Illuminate\Database\Query\Builder|\Theme whereUrl($value)
 * @method static \Illuminate\Database\Query\Builder|\Theme whereInstalled($value)
 * @method static \Illuminate\Database\Query\Builder|\Theme whereActive($value)
 * @method static \Illuminate\Database\Query\Builder|\Theme whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Theme whereUpdatedAt($value)
 */
class Theme extends Eloquent
{

    protected $table='themes';

    protected $fillable = array('name','title', 'image','version','author','url','installed','active');


    public function scopeOfState($query,$status){
        return $query->where('active','=',1);
    }

    public function save(array $options = array()){

        $this->image = ($this->image)?$this->image:'';
        $this->version = ($this->version)?$this->version:'';
        $this->author = ($this->author)?$this->author:'';
        $this->url = ($this->url)?$this->url:'';

        try{
            return parent::save($options);
        }catch(Exception $e){
            App::abort(500,'ERROR!');
        }
    }

}