<?php

/**
 * Plugin
 *
 * @property integer $id
 * @property string $name
 * @property string $title
 * @property string $image
 * @property string $version
 * @property string $author
 * @property string $url
 * @property string $mainpage_route
 * @property boolean $installed
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\Plugin whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Plugin whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\Plugin whereTitle($value)
 * @method static \Illuminate\Database\Query\Builder|\Plugin whereImage($value)
 * @method static \Illuminate\Database\Query\Builder|\Plugin whereVersion($value)
 * @method static \Illuminate\Database\Query\Builder|\Plugin whereAuthor($value)
 * @method static \Illuminate\Database\Query\Builder|\Plugin whereUrl($value)
 * @method static \Illuminate\Database\Query\Builder|\Plugin whereMainpageRoute($value)
 * @method static \Illuminate\Database\Query\Builder|\Plugin whereInstalled($value)
 * @method static \Illuminate\Database\Query\Builder|\Plugin whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Plugin whereUpdatedAt($value)
 */
class Plugin extends Eloquent
{

	protected $table='plugins';

    protected $fillable = array('name','title', 'image','version','author','url','installed');


    public function save(array $options = array()){

        $this->image = ($this->image)?$this->image:'';
        $this->version = ($this->version)?$this->version:'';
        $this->author = ($this->author)?$this->author:'';
        $this->url = ($this->url)?$this->url:'';
        $this->mainpage_route = ($this->mainpage_route)?$this->mainpage_route:'';

        try{
            return parent::save($options);
        }catch(Exception $e){
            App::abort(500,'ERROR!');
        }
    }
}