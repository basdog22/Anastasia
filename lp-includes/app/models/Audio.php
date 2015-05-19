<?php


/**
 * Audio
 *
 * @property integer $id
 * @property string $filename
 * @property string $type
 * @property integer $filesize
 * @property integer $duration
 * @property string $path
 * @property string $full_data
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\Audio whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Audio whereFilename($value)
 * @method static \Illuminate\Database\Query\Builder|\Audio whereType($value)
 * @method static \Illuminate\Database\Query\Builder|\Audio whereFilesize($value)
 * @method static \Illuminate\Database\Query\Builder|\Audio whereDuration($value)
 * @method static \Illuminate\Database\Query\Builder|\Audio wherePath($value)
 * @method static \Illuminate\Database\Query\Builder|\Audio whereFullData($value)
 * @method static \Illuminate\Database\Query\Builder|\Audio whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Audio whereUpdatedAt($value)
 * @property string $title 
 * @property string $description 
 * @property-read mixed $asset_type 
 * @method static \Illuminate\Database\Query\Builder|\Audio whereTitle($value)
 * @method static \Illuminate\Database\Query\Builder|\Audio whereDescription($value)
 */
class Audio extends \Asset
{

    protected $table='assets_audio';



}