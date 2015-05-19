<?php

/**
 * Class ArchiveReader
 */
class ArchiveReader
{

    /**
     * @param $file
     * @param $path
     * @return array
     */
    static function readarchive($file, $path)
    {
        $rarmimes = array(
            'application/x-rar',
            'application/x-rar-compressed'
        );

        $zipmimes = array(
            'application/zip',
            'application/x-7z-compressed'
        );
        $tarmimes = array(
            'application/x-gzip',
            'application/x-tar'
        );

        if (in_array($file['mime'], $rarmimes)) {
            $contents = array();
            try {
                if(function_exists('rar_open')){
                    $rar_file = rar_open($path);

                    $entries = rar_list($rar_file);

                    foreach ($entries as $entry) {
                        $contents[] = $entry->getName();

                    }
                    rar_close($rar_file);
                    return $contents;
                }
            } catch (Exception $e) {
                return $contents;
            }
        } elseif (in_array($file['mime'], $zipmimes) || in_array($file['mime'], $tarmimes)) {
            $contents = array();
            try {
                if(class_exists('PharData')){
                    $archive = new PharData($path);
                    $it = new RecursiveIteratorIterator($archive);
                    foreach ($it as $filename => $fileobject) {
                        $contents[] = str_replace("phar://" . $path . '/', "", $filename);
                    }
                }else{
                    try{
                        $zip = zip_open($path);
                        if (!$zip || is_numeric($zip)) return false;
                        while ($zip_entry = zip_read($zip)) {
                            if (zip_entry_open($zip, $zip_entry) == FALSE) continue;
                            $contents[] = $zip_entry;
                            zip_entry_close($zip_entry);
                        }
                        zip_close($zip);
                    }catch (Exception $e){
                        return "No PharData or Zip support";
                    }

                }
                return $contents;
            } catch (Exception $e) {
                return $contents;
            }
        }
    }


}