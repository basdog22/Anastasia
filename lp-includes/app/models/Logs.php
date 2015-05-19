<?php


class Logs extends \Eloquent{
    protected $table = 'logs';


    public function getContentAttribute($value){
        return safe_unserialize($value);
    }

    public function setContentAttribute($value){
        $this->attributes['content'] = safe_serialize($value);
    }
}