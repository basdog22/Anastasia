<?php

register_shortcode('list',function($shortcode,$content,$object,$c){
    $html = "<ul class='nav nav-stacked'>";
    foreach(explode(PHP_EOL,$content) as $line){
        $html .= "<li>{$line}</li>";
    }
    $html .= "</ul>";
    return $html;
},'Attributes: No attributes<br/>Creates a list from text');

register_shortcode('pagelist',function($shortcode,$content,$object,$c){
    $pages = \Page::where("slug","LIKE",$shortcode->sluglike."/%")->get();
    $html = "<ul class='nav nav-stacked'>";
    foreach($pages as $page){
        $html .= "<li><a href='".url($page->slug)."'>{$page->title}</a></li>";
    }
    $html .= "</ul>";
    return $html;
},'Attributes: sluglike<br/>Show a list of pages that match the sluglike attribute');