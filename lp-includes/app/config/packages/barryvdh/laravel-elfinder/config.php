<?php

$config = array(

    /*
    |--------------------------------------------------------------------------
    | Upload dir
    |--------------------------------------------------------------------------
    |
    | The dir where to store the images (relative from public)
    |
    */

    'dir' => 'lp-content/files',

    /*
    |--------------------------------------------------------------------------
    | Access filter
    |--------------------------------------------------------------------------
    |
    | Filter callback to check the files
    |
    */

    'access' => 'Barryvdh\Elfinder\Elfinder::checkAccess',

    /*
    |--------------------------------------------------------------------------
    | Roots
    |--------------------------------------------------------------------------
    |
    | By default, the roots file is LocalFileSystem, with the above public dir.
    | If you want custom options, you can set your own roots below.
    |
    */

    'roots'=>array(
        array(
            'driver'     => 'Flysystem',
            'path'       => 'archives',
            'URL'        => \Config::get('app.url').'/lp-content/files/archives',
            'tmbPath'    => \Config::get('settings.cms.content').'/files/.tmb',
            'tmbURL'     => \Config::get('app.url').'/lp-content/files/.tmb',
            'alias'      => 'Archives',
            'icon'       => \Config::get('app.url').'/lp-includes/packages/barryvdh/laravel-elfinder/img/archives.png',
            'filesystem' => new Filesystem(new LocalAdapter(Config::get('settings.cms.content').'/files')),
            'uploadAllow' => array('application/vnd.debian.binary-package','application/gzip','application/zip','application/x-cpio','application/x-shar','application/x-tar','application/x-bzip2','application/x-gzip','application/x-lzip','application/x-lzma','application/x-lzop','application/x-xz','application/x-compress','application/x-7z-compressed','application/x-ace-compressed','application/x-astrotite-afa','application/x-alz-compressed','application/vnd.android.package-archive','application/x-arj','application/x-b1','application/vnd.ms-cab-compressed','application/x-cfs-compressed','application/x-dar','application/x-dgc-compressed','application/x-apple-diskimage','application/x-gca-compressed','application/x-lzh','application/x-rar-compressed','application/x-stuffit','application/x-stuffitx','application/x-gtar','application/x-zoo','application/x-rar'),
            'uploadDeny'  => array('all'),
            'uploadOrder' => 'deny,allow'
        ),array(
            'driver'     => 'Flysystem',
            'path'       => 'images',
            'URL'        => \Config::get('app.url').'/lp-content/files/images',
            'tmbPath'    => \Config::get('settings.cms.content').'/files/.tmb',
            'tmbURL'     => \Config::get('app.url').'/lp-content/files/.tmb',
            'alias'      => 'Images',
            'icon'       => \Config::get('app.url').'/lp-includes/packages/barryvdh/laravel-elfinder/img/images.png',
            'filesystem' => new Filesystem(new LocalAdapter(Config::get('settings.cms.content').'/files')),
            'uploadAllow' => array('image'),
            'uploadDeny'  => array('all'),
            'uploadOrder' => 'deny,allow'
        ),array(
            'driver'     => 'Flysystem',
            'path'       => 'sounds',
            'URL'        => \Config::get('app.url').'/lp-content/files/sounds',
            'tmbPath'    => \Config::get('settings.cms.content').'/files/.tmb',
            'tmbURL'     => \Config::get('app.url').'/lp-content/files/.tmb',
            'alias'      => 'Sounds',
            'icon'       => \Config::get('app.url').'/lp-includes/packages/barryvdh/laravel-elfinder/img/sounds.png',
            'filesystem' => new Filesystem(new LocalAdapter(Config::get('settings.cms.content').'/files')),
            'uploadAllow' => array('audio/x-wav','audio/basic', 'audio/L24', 'audio/mp4','audio/mpeg','audio/ogg','audio/flac','audio/opus','audio/vorbis','audio/vnd.rn-realaudio','audio/vnd.wave','audio/webm'),
            'uploadDeny'  => array('all'),
            'uploadOrder' => 'deny,allow'
        ),array(
            'driver'     => 'Flysystem',
            'path'       => 'videos',
            'URL'        => \Config::get('app.url').'/lp-content/files/videos',
            'tmbPath'    => \Config::get('settings.cms.content').'/files/.tmb',
            'tmbURL'     => \Config::get('app.url').'/lp-content/files/.tmb',
            'alias'      => 'Videos',
            'icon'       => \Config::get('app.url').'/lp-includes/packages/barryvdh/laravel-elfinder/img/videos.png',
            'filesystem' => new Filesystem(new LocalAdapter(Config::get('settings.cms.content').'/files')),
            'uploadAllow' => array('video/avi', 'video/mpeg', 'video/mp4','video/ogg','video/quicktime','video/webm','video/x-matroska','video/x-ms-wmv','video/x-flv','video/3gpp'),
            'uploadDeny'  => array('all'),
            'uploadOrder' => 'deny,allow'
        ),array(
            'driver'     => 'Flysystem',
            'path'       => 'misc',
            'URL'        => \Config::get('app.url').'/lp-content/files/misc',
            'tmbPath'    => \Config::get('settings.cms.content').'/files/.tmb',
            'tmbURL'     => \Config::get('app.url').'/lp-content/files/.tmb',
            'alias'      => 'Misc',
            'icon'       => \Config::get('app.url').'/lp-includes/packages/barryvdh/laravel-elfinder/img/misc.png',
            'filesystem' => new Filesystem(new LocalAdapter(Config::get('settings.cms.content').'/files')),
            'uploadDeny' => array('audio/x-wav','video/avi', 'video/mpeg', 'video/mp4','video/ogg','video/quicktime','video/webm','video/x-matroska','video/x-ms-wmv','video/x-flv','audio/basic', 'audio/L24', 'audio/mp4','audio/mpeg','audio/ogg','audio/flac','audio/opus','audio/vorbis','audio/vnd.rn-realaudio','audio/vnd.wave','audio/webm','image','application/vnd.debian.binary-package','application/gzip','application/zip','application/x-cpio','application/x-shar','application/x-tar','application/x-bzip2','application/x-gzip','application/x-lzip','application/x-lzma','application/x-lzop','application/x-xz','application/x-compress','application/x-7z-compressed','application/x-ace-compressed','application/x-astrotite-afa','application/x-alz-compressed','application/vnd.android.package-archive','application/x-arj','application/x-b1','application/vnd.ms-cab-compressed','application/x-cfs-compressed','application/x-dar','application/x-dgc-compressed','application/x-apple-diskimage','application/x-gca-compressed','application/x-lzh','application/x-rar-compressed','application/x-stuffit','application/x-stuffitx','application/x-gtar','application/x-zoo','application/x-rar'),
            'uploadAllow'  => array('all'),
            'uploadOrder' => 'allow,deny'
        ),
    ),
    /*
    |--------------------------------------------------------------------------
    | Options
    |--------------------------------------------------------------------------
    |
    | These options are merged, together with 'roots' and passed to the Connector.
    | See https://github.com/Studio-42/elFinder/wiki/Connector-configuration-options-2.1
    |
    */

    'options' => array(),
    
    /*
    |--------------------------------------------------------------------------
    | CSRF
    |--------------------------------------------------------------------------
    |
    | CSRF in a state by default false.
    | If you want to use CSRF it can be replaced with true (boolean).
    |
    */

    'csrf'=>true,

);

if ( !function_exists('finfo_buffer')) {
    //Change the roots to use the localfilesystem driver instead of flysystem

    //archives
    $config['roots'][0]['driver'] = 'LocalFileSystem';
    $config['roots'][0]['path'] = public_path().'/lp-content/files/archives';
    unset($config['roots'][0]['filesystem']);

    //images
    $config['roots'][1]['driver'] = 'LocalFileSystem';
    $config['roots'][1]['path'] = public_path().'/lp-content/files/images';
    unset($config['roots'][1]['filesystem']);

    //sounds
    $config['roots'][2]['driver'] = 'LocalFileSystem';
    $config['roots'][2]['path'] = public_path().'/lp-content/files/sounds';
    unset($config['roots'][2]['filesystem']);

    //videos
    $config['roots'][3]['driver'] = 'LocalFileSystem';
    $config['roots'][3]['path'] = public_path().'/lp-content/files/videos';
    unset($config['roots'][3]['filesystem']);

    //misc
    $config['roots'][4]['driver'] = 'LocalFileSystem';
    $config['roots'][4]['path'] = public_path().'/lp-content/files/misc';
    unset($config['roots'][4]['filesystem']);

}

if(USE_MYSQL_VOLUME || USE_FTP_VOLUME){
    if(USE_FTP_VOLUME){
        $config['roots'][] = array(
            'driver' => 'FTP',
            'host'   => FTP_VOLUME_HOST,
            'user'   => FTP_VOLUME_USER,
            'pass'   => FTP_VOLUME_PASSWORD,
            'path'   => FTP_VOLUME_PATH
        );
    }

    if(USE_MYSQL_VOLUME){
        $config['roots'][] = array(
            'driver' => 'MySQL',
            'host'   => DB_HOST,
            'user'   => DB_USER,
            'pass'   => DB_PASS,
            'db'     => DB_NAME,
            'path'   => 1,
//            'separator' => ':',
            'files_table'   => DB_PREFIX.'elfinder_file',
        );
    }
}

return $config;