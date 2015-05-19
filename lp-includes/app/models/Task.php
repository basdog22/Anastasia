<?php

/**
 * Task
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $title
 * @property string $description
 * @property boolean $full_date
 * @property string $start_date
 * @property string $end_date
 * @property boolean $status
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \User $user
 * @method static \Illuminate\Database\Query\Builder|\Task whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Task whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\Task whereTitle($value)
 * @method static \Illuminate\Database\Query\Builder|\Task whereDescription($value)
 * @method static \Illuminate\Database\Query\Builder|\Task whereFullDate($value)
 * @method static \Illuminate\Database\Query\Builder|\Task whereStartDate($value)
 * @method static \Illuminate\Database\Query\Builder|\Task whereEndDate($value)
 * @method static \Illuminate\Database\Query\Builder|\Task whereStatus($value)
 * @method static \Illuminate\Database\Query\Builder|\Task whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Task whereUpdatedAt($value)
 */
class Task extends \Eloquent{

    protected $table ='tasks';

    public function user(){
        return $this->belongsTo('User');
    }

    public function setStartDateAttribute($value){
        $date = strtotime($value);
        $date = date("Y-m-d H:i:s",$date);
        $this->attributes['start_date'] = $date;
    }

    public function setEndDateAttribute($value){
        $date = strtotime($value);
        $date = date("Y-m-d H:i:s",$date);
        $this->attributes['end_date'] = $date;
    }

    public function save(array $options = array()){

        $this->title = ($this->title)?$this->title:'';
        $this->description = ($this->description)?$this->description:'';


        try{
            return parent::save($options);
        }catch(Exception $e){
            App::abort(500,'ERROR!');
        }
    }
}