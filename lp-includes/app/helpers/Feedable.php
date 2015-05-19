<?php

trait Feedable{

    public static  function atom($num=5){
        $instance = new static;
        $items = $instance->whereStatus('1')->orderBy('updated_at','desc')->take($num)->get();
        $converted = $channel = array();

        $channel['title'] = get_config_value('brand');
        $channel['description'] = get_config_value('brand');
        $channel['link'] = url('feeds');

        foreach($items as $item){
            $converted[] = array(
                'title'         =>  $item->title,
                'description'   =>  the_excerpt($item->content,255),
                'link'          =>  url($item->slug),
                'pubdate'       =>  $item->updated_at
            );
        }

        $channel['pubdate'] = $item->updated_at;
        return Response::make(\View::make('common/feeds/atom')->withItems($converted)->withChannel($channel), 200, array('Content-type' => 'application/atom+xml'.'; charset=utf-8'));
    }

    public static  function rss($num=5){
        $instance = new static;
        $items = $instance->whereStatus('1')->orderBy('updated_at','desc')->take($num)->get();
        $converted = $channel = array();

        $channel['title'] = get_config_value('brand');
        $channel['description'] = get_config_value('brand');
        $channel['lang'] = App::getLocale();
        $channel['link'] = url('feeds');

        foreach($items as $item){
            if(isset($item->user_id)){
                $user = $item->user;
                $user = $user->full_name;
            }else{
                $user = 'Anastasia APP';
            }
            $converted[] = array(
                'title'         =>  $item->title,
                'description'   =>  the_excerpt($item->content,256),
                'content'       =>  $item->content,
                'link'          =>  url($item->slug),
                'author'          =>  $user,
                'pubdate'       =>  $item->created_at
            );
        }

        $channel['pubdate'] = $item->updated_at;

        $feed = \View::make('common/feeds/rss')->withItems($converted)->withChannel($channel);

        return Response::make(\View::make('common/feeds/rss')->withItems($converted)->withChannel($channel), 200, array('Content-type' => 'application/rss+xml'.'; charset=utf-8'));
    }
}