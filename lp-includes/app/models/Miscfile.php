<?php



/**
 * Miscfile
 *
 * @property integer $id
 * @property string $filename
 * @property string $type
 * @property integer $filesize
 * @property string $path
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\Miscfile whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Miscfile whereFilename($value)
 * @method static \Illuminate\Database\Query\Builder|\Miscfile whereType($value)
 * @method static \Illuminate\Database\Query\Builder|\Miscfile whereFilesize($value)
 * @method static \Illuminate\Database\Query\Builder|\Miscfile wherePath($value)
 * @method static \Illuminate\Database\Query\Builder|\Miscfile whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Miscfile whereUpdatedAt($value)
 * @property string $full_data
 * @method static \Illuminate\Database\Query\Builder|\Miscfile whereFullData($value)
 * @property string $title 
 * @property string $description 
 * @property-read mixed $asset_type 
 * @method static \Illuminate\Database\Query\Builder|\Miscfile whereTitle($value)
 * @method static \Illuminate\Database\Query\Builder|\Miscfile whereDescription($value)
 */
class Miscfile extends \Asset
{

    protected $table='assets_miscfiles';



}