<?php

/**
 * Class BackupManager
 */
class BackupManager{
    /**
     * @return bool
     */
    static function backup(){
        $sub = get_secure_string();
        if(!\File::isDirectory(app_path().'/database/points/'.date("Y.m.d.H.i",time()).$sub)){
            \File::makeDirectory(app_path().'/database/points/'.date("Y.m.d.H.i",time()).$sub);
            \File::copy(app_path().'/database/seeds/DatabaseSeeder.php',app_path().'/database/points/'.date("Y.m.d.H.i",time()).$sub.'/DatabaseSeeder.php');
        }

        Iseed::generateSeed('assets_archive');
        Iseed::generateSeed('assets_audio');
        Iseed::generateSeed('assets_images');
        Iseed::generateSeed('assets_miscfiles');
        Iseed::generateSeed('assets_videos');
        Iseed::generateSeed('blocks');
        Iseed::generateSeed('cache');
        Iseed::generateSeed('comments');
        Iseed::generateSeed('groups');
        Iseed::generateSeed('menus');
        Iseed::generateSeed('metas');
        Iseed::generateSeed('pages');
        Iseed::generateSeed('page_metas');
        Iseed::generateSeed('plugins');
        Iseed::generateSeed('posts');
        Iseed::generateSeed('post_metas');
        Iseed::generateSeed('revisions');
        Iseed::generateSeed('sessions');
        Iseed::generateSeed('settings');
        Iseed::generateSeed('tagging_tagged');
        Iseed::generateSeed('tagging_tags');
        Iseed::generateSeed('tasks');
        Iseed::generateSeed('taxonomies');
        Iseed::generateSeed('themes');
        Iseed::generateSeed('throttle');
        Iseed::generateSeed('users');
        Iseed::generateSeed('users_groups');
        Iseed::generateSeed('user_metas');
        return true;
    }

    /**
     * @param $point
     * @return bool
     */
    static function restore($point){
        $files = \File::files($point);
        foreach($files as $file){
            require_once $file;
        }
        $seeder = new DatabaseSeeder();
        $seeder->run();
        return true;
    }
}