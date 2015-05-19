<?php

/**
 * Class AssetsController
 */
class AssetsController extends BackendbaseController {
    /**
     * @var string
     */
    protected $layout = 'layouts.backend.laradmin';


    /**
     * Loads the filemanager
     */
    public function filemanager(){
        $app = app();
        $controller = $app->make('Barryvdh\Elfinder\ElfinderController');
        $this->layout->content = $controller->callAction('showIndex',$params = array());
    }
    /**
     * Load the sound file for elfinder
     *
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function elfinderfix(){
        return \Response::download(public_path().'/lp-includes/packages/misc/rm.wav');
    }
    /**
     * Performs a search on all content types
     */
    public function search(){
        $content = $this->getContentTypesFlat();
        $q = requested('search');
        $assets = (\Config::get('settings.cms.search_assets'))?$this->searchAssets($q):array();
        $results = array();
        foreach($content as $type){
            $model = $type['model'];
            $results[$type['type']]['content'] = $type;
            $results[$type['type']]['data'] = $model::where("title",'LIKE',"%".$q."%")->paginate(20);
        }
        $results = array_merge($results,$assets);
        $this->layout->content = View::make('backend/results')->withResults($results)->withQ($q);
    }

    /**
     * Performs search in assets
     *
     * @param $q
     * @return array
     */
    public function searchAssets($q){

        $images = \ImageAsset::find(1);
//        ll($images->full_data);
        $results = array();
        $images = \ImageAsset::search($q);
        if($images->count()){
            $results['assets_images']['data'] = $images;
            $results['assets_images']['content'] = array(
                'title' => t('strings.images'),
                'type' => 'asset',
                'slug' => 'image',
            );
        }
        $audios = \Audio::search($q);
        if($audios->count()){
            $results['assets_audios']['data'] = $audios;
            $results['assets_audios']['content'] = array(
                'title' => t('strings.sounds'),
                'type' => 'asset',
                'slug' => 'audio',
            );
        }
        $archives = \Archive::search($q);
        if($archives->count()){
            $results['assets_archives']['data'] = $archives;
            $results['assets_archives']['content'] = array(
                'title' => t('strings.archives'),
                'type' => 'asset',
                'slug' => 'archive',
            );
        }
        $videos = \Video::search($q);
        if($videos->count()){
            $results['assets_videos']['data'] = $videos;
            $results['assets_videos']['content'] = array(
                'title' => t('strings.videos'),
                'type' => 'asset',
                'slug' => 'video',
            );
        }
        $misc = \Miscfile::search($q);
        if($misc->count()){
            $results['assets_misc']['data'] = $misc;
            $results['assets_misc']['content'] = array(
                'title' => t('strings.miscfiles'),
                'type' => 'asset',
                'slug' => 'misc',
            );
        }

        return $results;
    }

    /**
     * Saves a media record in the db
     *
     * @return \Illuminate\Http\JsonResponse
     */
    function savemedia(){
        $file = requested('file');
        $type = (string) requested('type');

        switch($type){
            case "image":
                return $this->saveimage($file);
                break;
            case "video":
                return $this->savevideo($file);
                break;
            case "audio":
                return $this->saveaudio($file);
                break;
            case "archive":
                return $this->savearchive($file);
                break;
            case "other":
                if($file['mime']=='application/ogg'){
                    return $this->savevideo($file);
                }
                return $this->saveotherfile($file);
                break;
        }
    }

    /**
     * Removes a media record from the db
     *
     * @return \Illuminate\Http\JsonResponse
     */
    function removemedia(){
        $file = requested('file');
        $file = explode("_",$file);
        $file = $file[1];
        $filename = base64_decode($file);

        //check if image
        $file = \ImageAsset::where('path','=',$filename)->first();
        if(is_null($file)){
            //check if video
            $file = \Video::where('path','LIKE','%'.$filename)->first();

            if(is_null($file)){
                //check if audio
                $file = \Audio::where('path','LIKE','%'.$filename)->first();

                if(is_null($file)){
                    //check if archive
                    $file = \Archive::where('path','LIKE','%'.$filename)->first();

                    if(is_null($file)){
                        //check if other file
                        $file = \Miscfile::where('path','LIKE','%'.$filename)->first();
                    }
                }

            }
        }
        if(is_null($file)){
            return Response::json(array('type' => 'danger', 'text' => t('messages.no_file_in_db')));
        }else{
            $file->delete();
            return Response::json(array('type' => 'success', 'text' => t('messages.file_reference_removed')));
        }
    }

    /**
     * Saves a db record for other files
     *
     * @param $file
     * @return \Illuminate\Http\JsonResponse
     */
    private function saveotherfile($file){
        $misc = new \Miscfile;
        $misc->filename = $file['name'];
        $misc->type = $file['mime'];
        $misc->filesize = $file['size'];
        $path = explode("_",$file['hash']);
        $path = base64_decode($path[1]);
        $misc->path = \Config::get('app.url').'/lp-content/files/'.$path;
        $misc->save();
        $data = $this->readasset('misc',$misc->id);
        $misc->full_data = array($data);
        if($misc->save()){
            return Response::json(array('type' => 'success', 'text' => t('messages.file_uploaded')));
        }
        return Response::json(array('type' => 'success', 'text' => t('messages.error_occured')));
    }

    /**
     * Saves a db record for archives
     *
     * @param $file
     * @return \Illuminate\Http\JsonResponse
     */
    private function savearchive($file){
        $archive = new \Archive;
        $archive->filename = $file['name'];
        $archive->type = $file['mime'];
        $archive->filesize = $file['size'];
        $path = explode("_",$file['hash']);
        $path = base64_decode($path[1]);
        $archive->path = \Config::get('app.url').'/lp-content/files/'.$path;
        $data = ArchiveReader::readarchive($file,public_path().'/lp-content/files/'.$path);
        $archive->full_data = $data;
        if($archive->save()){
            return Response::json(array('type' => 'success', 'text' => t('messages.archive_uploaded')));
        }
        return Response::json(array('type' => 'success', 'text' => t('messages.error_occured')));
    }

    /**
     * Saves a db record for audio
     *
     * @param $file
     * @return \Illuminate\Http\JsonResponse
     */
    private function saveaudio($file){
        $audio = new \Audio;
        $audio->filename = $file['name'];
        $audio->type = $file['mime'];
        $audio->filesize = $file['size'];
        $path = explode("_",$file['hash']);
        $path = base64_decode($path[1]);
        $audio->path = \Config::get('app.url').'/lp-content/files/'.$path;
        //read file info with id3
        $data = get_fileid3_info(public_path().'/lp-content/files/'.$path);
        $audio->full_data = $data;
        $audio->duration = $data['playtime_seconds'];
        if($audio->save()){
            return Response::json(array('type' => 'success', 'text' => t('messages.audio_uploaded')));
        }
        return Response::json(array('type' => 'success', 'text' => t('messages.error_occured')));
    }

    /**
     * Saves a db record for video
     *
     * @param $file
     * @return \Illuminate\Http\JsonResponse
     */
    private function savevideo($file){
        $video = new \Video;

        $video->filename = $file['name'];
        $video->type = $file['mime'];
        $video->filesize = $file['size'];
        $path = explode("_",$file['hash']);
        $path = base64_decode($path[1]);
        $video->path = \Config::get('app.url').'/lp-content/files/'.$path;
        //read file info with id3
        $data = get_fileid3_info(public_path().'/lp-content/files/'.$path);
        $video->full_data = $data;
        $video->duration = $data['playtime_seconds'];
        $video->width = $data['video']['resolution_x'];
        $video->height = $data['video']['resolution_y'];
        if($video->save()){
            return Response::json(array('type' => 'success', 'text' => t('messages.video_uploaded')));
        }
        return Response::json(array('type' => 'success', 'text' => t('messages.error_occured')));
    }

    /**
     * Saves a db record for images
     *
     * @param $file
     * @return \Illuminate\Http\JsonResponse
     */
    private  function saveimage($file){

        $image = new \ImageAsset;
        $image->original_filename = $file['name'];
        $image->type = $file['mime'];
        $image->filesize = $file['size'];

        $path = explode("_",$file['hash']);
        $path = base64_decode($path[1]);
        $image->path = $path;
//        $data=getimagesize(public_path().'/lp-content/files/'.$path);
        $data=get_fileid3_info(public_path().'/lp-content/files/'.$path);
        $image->full_data = $data;
        $image->width = $data['video']['resolution_x'];
        $image->height = $data['video']['resolution_y'];
        $image->ratio = 1.00;
        $image->filename = $file['name'];

        $image->sizes = array(
            'original'=> \Config::get('app.url').'/lp-content/files/'.$path
        );

        if($image->save()){
            return Response::json(array('type' => 'success', 'text' => t('messages.image_uploaded')));
        }
        return Response::json(array('type' => 'success', 'text' => t('messages.error_occured')));
    }

    /**
     * Show a list of the asset type specified
     *
     * @param $type
     * @return mixed
     */
    public function loadasset($type){
        switch($type){
            case "misc":
                $files = \Miscfile::all();
                return \View::make('backend/assets/list')->withFiles($files);
                break;
        }
    }

    /**
     * Read a file.
     *
     * @param $type
     * @param $fileid
     * @return bool|mixed|string
     */
    public function readasset($type,$fileid){
        $contents = '';
        switch($type){
            case "misc":
                $file = \Miscfile::find($fileid);
//                echo $file->type;
                $office = array(
                    'application/msword',
                    'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                    'application/vnd.ms-office',
                    'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                    'application/vnd.oasis.opendocument.spreadsheet',
                    'application/vnd.oasis.opendocument.text',
                    'application/vnd.ms-powerpoint',
                    'application/vnd.openxmlformats-officedocument.presentationml.presentation'

                );
                $plain = array(
                    'text/html',
                    'text/plain',
                    'application/xml',
                    'text/x-php'
                );

                if(in_array($file->type,$office)){
                    $contents = read_office_file($file);

                }elseif(in_array($file->type,$plain)){
                    $contents = read_plain_file($file);
                }
                break;
        }

        return (trim($contents))?$contents:t('messages.cannot_read_file');


    }

    public function getAssetByFilename($filename){
        $isimage = \ImageAsset::whereFilename($filename)->first();
        if(is_null($isimage)){
            $isarchive = \Archive::whereFilename($filename)->first();
            if(is_null($isarchive)){
                $isvideo = \Video::whereFilename($filename)->first();
                if(is_null($isvideo)){
                    $isaudio = \Audio::whereFilename($filename)->first();
                    if(is_null($isaudio)){
                        $isother = \Miscfile::whereFilename($filename)->first();
                        if(is_null($isother)){
                            return false;
                        }else{
                            return $isother;
                        }
                    }else{
                        return $isaudio;
                    }
                }else{
                    return $isvideo;
                }
            }else{
                return $isarchive;
            }
        }else{
            return $isimage;
        }
    }

    public function editmedia($filename){
        //get filename
        $file = $this->getAssetByFilename($filename);
        if (\Request::ajax()){
            return \View::make('backend/assets/edit')->withPost($file);
        }else{
            $this->layout->content = \View::make('backend/assets/edit')->withPost($file);
        }

    }

    public function doeditmedia(){
        $filename = requested('filename');
        $title = requested('title');
        $desc = requested('content');

        $asset = $this->getAssetByFilename($filename);

        if($asset){
            $asset->title = $title;
            $asset->description = $desc;
            $asset->save();
        }

        return \Redirect::back()->withMessage($this->notifyView(t('messages.asset_info_saved')));
    }

}
