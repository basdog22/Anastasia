<?php

namespace Plugins\posts\models;
/**
 * Class Category
 * @package Plugins\posts\models
 */
class Category extends \Taxonomy {
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */


    use \Translatable;

    protected $translatable = array('title');

    public function posts(){
        return $this->hasMany("Post");
    }
}