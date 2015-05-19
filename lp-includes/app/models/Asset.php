<?php

/**
 * Asset
 *
 * @property-read mixed $full_data
 * @property-read mixed $asset_type 
 */
class Asset extends \Eloquent{

    use \Translatable;
    protected $appends = array('asset_type');

    protected $translatable = array('title','description');

    public function getAssetTypeAttribute(){
        return $this->table;
    }

    public static  function search($q){
        $results = self::where('filename','LIKE','%'.$q.'%')->take(50)->get(array('*','filename as title'));

        if(\Config::get('settings.cms.search_assets_metadata') && !is_null($results)){
            $ids = array();
            $results2 = self::searchMetadata($q);
            foreach($results as $result){
                $ids[$result->id] = $result->id;
            }
            if(!is_null($results2)){
                foreach($results2 as $result){
                    $ids[$result->id] = $result->id;
                }
            }
            $results = self::whereIn('id',$ids)->take(50)->get(array('*','filename as title'));

        }elseif(\Config::get('settings.cms.search_assets_metadata') && is_null($results)){
            $results = self::searchMetadata($q)->take(50)->get(array('*','filename as title'));
        }


        return $results;
    }

    public static function searchMetadata($q){
        $results = self::all(array('*','filename as title'));
        $ids = array();
        foreach($results as $result){
            $meta = $result->full_data;
            if(!is_null($meta)){
                if(recursive_array_search($q,$meta)){
                    $ids[] = $result->id;
                }
            }


        }
        return self::whereIn('id',$ids)->take(50)->get(array('*','filename as title'));

    }


    public function save(array $options = array()){
        if(!trim($this->title)){
            $this->title = $this->filename;
        }
        if(!trim($this->description)){
            $this->description = $this->filename;
        }
        try{
            return parent::save($options);
        }catch(Exception $e){
            App::abort(500,$e->getMessage());
        }

    }


    public function getFullDataAttribute($value)
    {
        if(trim($value)){
            return safe_unserialize($value);
        }
        return null;

    }

    public function setFullDataAttribute($value)
    {
        $this->attributes['full_data'] = safe_serialize($value);
    }

}