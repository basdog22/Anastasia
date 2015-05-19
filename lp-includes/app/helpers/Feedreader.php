<?php

/**
 * Class Feedreader
 */
class Feedreader{
    /**
     * @param $feed
     * @return array|null
     */
    static function read($feed){
        $parser = new SimplePie();
        $parser->set_feed_url($feed);
        $parser->set_cache_location(storage_path().'/cache');
        $parser->set_cache_duration(100);
        $success = $parser->init();
        $parser->handle_content_type();
        if($parser->error() || !$success){
            return null;
        }else{
            return $parser->get_items();
        }
    }
}