<?php

/**
 * Sessions
 *
 * @property string $id
 * @property string $payload
 * @property integer $last_activity
 * @method static \Illuminate\Database\Query\Builder|\Sessions whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Sessions wherePayload($value)
 * @method static \Illuminate\Database\Query\Builder|\Sessions whereLastActivity($value)
 */
class Sessions extends \Eloquent{
    protected $table = 'sessions';


    public function getPayloadAttribute($value){
        return safe_unserialize($value);
    }
}