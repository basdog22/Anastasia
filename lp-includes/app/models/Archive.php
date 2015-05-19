<?php



/**
 * Archive
 *
 * @property integer $id
 * @property string $filename
 * @property string $type
 * @property integer $filesize
 * @property string $path
 * @property string $full_data
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\Archive whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Archive whereFilename($value)
 * @method static \Illuminate\Database\Query\Builder|\Archive whereType($value)
 * @method static \Illuminate\Database\Query\Builder|\Archive whereFilesize($value)
 * @method static \Illuminate\Database\Query\Builder|\Archive wherePath($value)
 * @method static \Illuminate\Database\Query\Builder|\Archive whereFullData($value)
 * @method static \Illuminate\Database\Query\Builder|\Archive whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Archive whereUpdatedAt($value)
 * @property string $title 
 * @property string $content 
 * @property-read mixed $asset_type 
 * @method static \Illuminate\Database\Query\Builder|\Archive whereTitle($value)
 * @method static \Illuminate\Database\Query\Builder|\Archive whereContent($value)
 */
class Archive extends \Asset
{

    protected $table='assets_archive';



}