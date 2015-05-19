<?php


/**
 * Class UploadsController
 */
class UploadsController extends Controller
{

    /**
     * @var string
     */
    protected $area = 'common';

    /**
     * Handle addon uploading
     *
     * @return string
     */
    public function postHandleaddons()
    {

        $uploader = new UploadHandler();

        // Specify the list of valid extensions, ex. array("jpeg", "xml", "bmp")
        $uploader->allowedExtensions = array("zip"); // all files types allowed by default

        // Specify max file size in bytes.
        $uploader->sizeLimit = 1 * 1024 * 1024; // default is 10 MiB

        // Specify the input name set in the javascript.
        $uploader->inputName = "qqfile"; // matches Fine Uploader's default inputName value by default

        // If you want to use the chunking/resume feature, specify the folder to temporarily save parts.
        $uploader->chunksFolder = storage_path() . "/cache";

        $method = $_SERVER["REQUEST_METHOD"];
        if ($method == "POST") {
            header("Content-Type: text/plain");

            // Call handleUpload() with the name of the folder, relative to PHP's getcwd()
            $result = $uploader->handleUpload(storage_path() . "/uploads");

            // To return a name used for uploaded file you can use the following line.
            $result["uploadName"] = $uploader->getUploadName();



            //{"success":true,"uuid":"23355116-d788-4e64-a8e1-0133703e6452","uploadName":"jquery-ui-1.8.11.zip"}

            $this->unzip($result['uploadName'],storage_path() . "/uploads/".$result['uuid']."/",\Config::get('settings.cms.content').'/plugins/');
            $this->saveAddoninfo($result['uploadName'],storage_path() . "/uploads/".$result['uuid']."/",$result);
            \File::deleteDirectory(storage_path() . "/uploads/".$result['uuid']);
            return json_encode($result);
        } else {
            header("HTTP/1.0 405 Method Not Allowed");
            return false;
        }
    }

    /**
     * Handle theme uploading
     *
     * @return string
     */
    public function postHandlethemes()
    {

        $uploader = new UploadHandler();

        // Specify the list of valid extensions, ex. array("jpeg", "xml", "bmp")
        $uploader->allowedExtensions = array("zip"); // all files types allowed by default

        // Specify max file size in bytes.
        $uploader->sizeLimit = get_config_value('max_upload_file_size') * 1024 * 1024; // default is 10 MiB

        // Specify the input name set in the javascript.
        $uploader->inputName = "qqfile"; // matches Fine Uploader's default inputName value by default

        // If you want to use the chunking/resume feature, specify the folder to temporarily save parts.
        $uploader->chunksFolder = storage_path() . "/cache";

        $method = $_SERVER["REQUEST_METHOD"];
        if ($method == "POST") {
            header("Content-Type: text/plain");

            // Call handleUpload() with the name of the folder, relative to PHP's getcwd()
            $result = $uploader->handleUpload(storage_path() . "/uploads");

            // To return a name used for uploaded file you can use the following line.
            $result["uploadName"] = $uploader->getUploadName();

            //{"success":true,"uuid":"23355116-d788-4e64-a8e1-0133703e6452","uploadName":"jquery-ui-1.8.11.zip"}



            $this->unzip($result['uploadName'],storage_path() . "/uploads/".$result['uuid']."/",\Config::get('settings.cms.content').'/themes/');
            $this->saveThemeinfo($result['uploadName'],storage_path() . "/uploads/".$result['uuid']."/",$result);
            \File::deleteDirectory(storage_path() . "/uploads/".$result['uuid']);
            return json_encode($result);
        } else {
            header("HTTP/1.0 405 Method Not Allowed");
            return false;
        }
    }

    /**
     * Save addon info
     *
     * @param $file
     * @param $source
     * @param $result
     */
    function saveAddoninfo($file,$source,$result){
        //read the info and save the data
        $zip = new ZipArchive();
        if ($zip->open($source . $file) !== TRUE) {

        }

        for($i = 0; $i < $zip->numFiles; $i++)
        {
           $entry = explode("/",$zip->getNameIndex($i));
           if(end($entry)=='plugin.xml'){
               $xml = $zip->getFromIndex ( $i );
           }
        }
        $xml = new SimpleXMLElement($xml);

        $addon = new \Plugin;
        $addon->name = $xml->name;
        $addon->title = $xml->title;
        $addon->image = $xml->image;
        $addon->version = $xml->version;
        $addon->author = $xml->author;
        $addon->url = $xml->url;
        $addon->mainpage_route = $xml->mainpage_route;
        $addon->installed = 0;
        $addon->save();
        //require the func.php file to load the events to listen
        if(\File::exists(plugins_path()."/{$addon->name}/hooks.php")){
            require_once plugins_path()."/{$addon->name}/hooks.php";
            \Event::fire('backend.addons.saveaddoninfo'.$addon->name, array($addon));
        }
    }

    /**
     * Save theme info
     *
     * @param $file
     * @param $source
     * @param $result
     */
    function saveThemeinfo($file,$source,$result){
        //read the info and save the data
        $zip = new ZipArchive();
        if ($zip->open($source . $file) !== TRUE) {

        }

        for($i = 0; $i < $zip->numFiles; $i++)
        {
            $entry = explode("/",$zip->getNameIndex($i));
            if(end($entry)=='theme.xml'){
                $xml = $zip->getFromIndex ( $i );
            }
        }
        $xml = new SimpleXMLElement($xml);

        $theme = new \Theme;
        $theme->name = $xml->name;
        $theme->title = $xml->title;
        $theme->image = $xml->image;
        $theme->version = $xml->version;
        $theme->author = $xml->author;
        $theme->url = $xml->url;
        $theme->installed = 0;
        $theme->active = 0;
        \Event::fire('backend.themes.savethemeinfo', array($theme));
        $theme->save();
    }

    /**
     * Unzip file
     *
     * @param $file
     * @param $source
     * @param $destination
     * @return bool
     */
    function unzip($file, $source, $destination)
    {
        $zip = new ZipArchive();
        if ($zip->open($source . $file) !== TRUE) {

        }

        $zip->extractTo($destination);
        for ($i = 0; $i < $zip->numFiles; $i++) {
            $entry = $zip->getNameIndex($i);
            chmod($destination . $entry, 0777);
        }
        $zip->close();
        return true;
    }
}