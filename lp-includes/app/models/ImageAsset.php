<?php

/**
 * ImageAsset
 *
 * @property integer $id
 * @property string $filename
 * @property string $path
 * @property string $original_filename
 * @property string $type
 * @property integer $filesize
 * @property integer $width
 * @property integer $height
 * @property float $ratio
 * @property string $sizes
 * @property string $full_data
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\ImageAsset whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\ImageAsset whereFilename($value)
 * @method static \Illuminate\Database\Query\Builder|\ImageAsset wherePath($value)
 * @method static \Illuminate\Database\Query\Builder|\ImageAsset whereOriginalFilename($value)
 * @method static \Illuminate\Database\Query\Builder|\ImageAsset whereType($value)
 * @method static \Illuminate\Database\Query\Builder|\ImageAsset whereFilesize($value)
 * @method static \Illuminate\Database\Query\Builder|\ImageAsset whereWidth($value)
 * @method static \Illuminate\Database\Query\Builder|\ImageAsset whereHeight($value)
 * @method static \Illuminate\Database\Query\Builder|\ImageAsset whereRatio($value)
 * @method static \Illuminate\Database\Query\Builder|\ImageAsset whereSizes($value)
 * @method static \Illuminate\Database\Query\Builder|\ImageAsset whereFullData($value)
 * @method static \Illuminate\Database\Query\Builder|\ImageAsset whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\ImageAsset whereUpdatedAt($value)
 * @property string $title 
 * @property string $description 
 * @property-read mixed $asset_type 
 * @method static \Illuminate\Database\Query\Builder|\ImageAsset whereTitle($value)
 * @method static \Illuminate\Database\Query\Builder|\ImageAsset whereDescription($value)
 */
class ImageAsset extends \Asset
{
    protected $table='assets_images';

    public function delete(){
        //get the image sizes
        $sizes = $this->sizes;
        foreach($sizes as $size=>$path){
            $realpath = str_replace(\Config::get('app.url'),public_path(),$path);
            if(\File::exists($realpath)){
                \File::delete($realpath);
            }
        }
        return parent::delete();
    }

    public function getSizesAttribute($value){
        return json_decode($value,1);
    }

    public function setSizesAttribute($value){
        $this->attributes['sizes'] = json_encode($value);
    }

}