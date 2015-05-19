<?php

/**
 * Menu
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\Menuitems')->orderby('sort[] $menuitems
 * @property integer $id
 * @property string $position
 * @property string $name
 * @property string $title
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\Menu whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Menu wherePosition($value)
 * @method static \Illuminate\Database\Query\Builder|\Menu whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\Menu whereTitle($value)
 * @method static \Illuminate\Database\Query\Builder|\Menu whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Menu whereUpdatedAt($value)
 * @property integer $_lft
 * @property integer $_rgt
 * @property integer $parent_id
 * @property string $model
 * @property integer $sort
 * @property string $url
 * @property string $link_text
 * @property string $link_target
 * @property string $link_attr
 * @property string $link_css
 * @property string $link_class
 * @property-read Menu $parent
 * @property-read \Illuminate\Database\Eloquent\Collection|Menu[] $children
 * @method static \Illuminate\Database\Query\Builder|\Menu whereLft($value)
 * @method static \Illuminate\Database\Query\Builder|\Menu whereRgt($value)
 * @method static \Illuminate\Database\Query\Builder|\Menu whereParentId($value)
 * @method static \Illuminate\Database\Query\Builder|\Menu whereModel($value)
 * @method static \Illuminate\Database\Query\Builder|\Menu whereSort($value)
 * @method static \Illuminate\Database\Query\Builder|\Menu whereUrl($value)
 * @method static \Illuminate\Database\Query\Builder|\Menu whereLinkText($value)
 * @method static \Illuminate\Database\Query\Builder|\Menu whereLinkTarget($value)
 * @method static \Illuminate\Database\Query\Builder|\Menu whereLinkAttr($value)
 * @method static \Illuminate\Database\Query\Builder|\Menu whereLinkCss($value)
 * @method static \Illuminate\Database\Query\Builder|\Menu whereLinkClass($value)
 */
class Menu extends Kalnoy\Nestedset\Node
{
    use \Translatable;


    protected $table='menus';

    protected $translatable = array('link_text');

    public function menuitems(){
        return $this->hasMany('Menuitems')->orderby('sort','asc');
    }

    static function getForSelect(){
        $menus = Menu::all();
        $sel = array();
        foreach($menus as $menu){
            $sel[$menu->id] = $menu->menu_title;
        }
        return $sel;
    }

    public function save(array $options = array()){

        $this->position = ($this->position)?$this->position:'';
        $this->model = ($this->model)?$this->model:'';
        $this->url = ($this->url)?$this->url:'';
        try{
            return parent::save($options);
        }catch(Exception $e){
            App::abort(500,'ERROR!');
        }
    }


}