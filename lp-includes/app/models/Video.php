<?php

/**
 * Video
 *
 * @property integer $id
 * @property string $filename
 * @property string $type
 * @property integer $filesize
 * @property integer $width
 * @property integer $height
 * @property integer $duration
 * @property string $path
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\Video whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Video whereFilename($value)
 * @method static \Illuminate\Database\Query\Builder|\Video whereType($value)
 * @method static \Illuminate\Database\Query\Builder|\Video whereFilesize($value)
 * @method static \Illuminate\Database\Query\Builder|\Video whereWidth($value)
 * @method static \Illuminate\Database\Query\Builder|\Video whereHeight($value)
 * @method static \Illuminate\Database\Query\Builder|\Video whereDuration($value)
 * @method static \Illuminate\Database\Query\Builder|\Video wherePath($value)
 * @method static \Illuminate\Database\Query\Builder|\Video whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Video whereUpdatedAt($value)
 * @property string $full_data
 * @method static \Illuminate\Database\Query\Builder|\Video whereFullData($value)
 * @property string $title 
 * @property string $description 
 * @property-read mixed $asset_type 
 * @method static \Illuminate\Database\Query\Builder|\Video whereTitle($value)
 * @method static \Illuminate\Database\Query\Builder|\Video whereDescription($value)
 */
class Video extends \Asset
{

    protected $table='assets_videos';

}