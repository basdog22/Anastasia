<?php



class AnastasiaTheme {
    static function listimages($params=''){
        return \Image::lists('original_filename','id');
    }
    static function getimages($params=''){
        $slides = array();
       foreach($params as $k=>$slide){
           $slide['img'] = \Image::find($slide['img']);
           $slide['url'] = url($slide['url']);
           $slides[] = $slide;
       }
       return  $slides;

    }

    static function getblockdata($params){
        return $params;
    }
}

