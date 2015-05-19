<?php

/**
 * Class WordReader
 */
class WordReader
{
    /**
     * @var
     */
    private $filename;



    /**
     * @file
     * ODT to HTML conversion functions
     *
     * Two functions are defined here : the first extracts the contents as XML from
     * the ODT file. The second parses the XML to produce HTML.
     */

    /* Configuration.
     0 : do not parse, do not print
     1 : print as simple text (do not apply any HTML tag or style)
     2 : print  and apply all supported HTML tags and styles
     */
    var $_ophir_odt_import_conf = array(
        "features" => array(
            "header" => 2,
            "list" => 2,
            "table" => 2,
            "footnote" => 2,
            "link" => 2,
            "image" => 2,
            "note" => 2,
            "annotation" => 2,
            'table of contents' => 0,
        ),
        "images_folder" => '/lp-content/files/images/'
    );


    function ophir_is_image($file)
    {
        $image_extensions = array("jpg", "jpeg", "png", "gif", "svg");
        $ext = pathinfo($file, PATHINFO_EXTENSION);
        if (!in_array($ext, $image_extensions)) return FALSE;
        return (strpos(@mime_content_type($file), 'image') === 0);
    }

    function ophir_copy_file($from, $to)
    {
        if (function_exists('file_unmanaged_copy')) {
            $filename = file_unmanaged_save_data(file_get_contents($from), $to, FILE_EXISTS_REPLACE);
            return ($filename) ? file_create_url($filename) : false;
        } else {
            if (file_exists($to)) {
                if (crc32(file_get_contents($from)) === crc32(file_get_contents($from))) return $to;
                $i = pathinfo($to);
                $to = $i['dirname'] . '/' . $i['filename'] . time() . '.' . $i['extension'];
            }
            return (copy($from, $to)) ? $to : FALSE;
        }
    }

    function ophir_error($error)
    {
        return $error;
    }

    /*
     * Function that parses the XML and outputs HTML. If $xml is not provided,
     * extract content.xml from $odt_file
     */
    function odt2html($odt_file, $xml_string = NULL)
    {
        $_ophir_odt_import_conf = $this->_ophir_odt_import_conf;

        $xml = new XMLReader();

        if ($xml_string === NULL) {
            if (@$xml->open('zip://' . $odt_file . '#content.xml') === FALSE) {
                ophir_error("Unable to read file contents.");
                return false;
            }
        } else {
            if (@$xml->xml($xml_string) === FALSE) {
                ophir_error("Invalid file contents.");
                return false;
            }
        }

        //Now, convert the xml from a string to an
        $html = "";

        $elements_tree = array();

        static $styles = array("Quotations" => array("tags" => array("blockquote")));

        $footnotes = "";

        $translation_table = array();
        $translation_table['draw:frame'] = 'div class="odt-frame"';
        if ($_ophir_odt_import_conf["features"]["list"] === 0) $translation_table["text:list"] = FALSE;
        elseif ($_ophir_odt_import_conf["features"]["list"] === 2) {
            $translation_table["text:list"] = "ul";
            $translation_table["text:list-item"] = "li";
        }
        if ($_ophir_odt_import_conf["features"]["table"] === 0) $translation_table["table:table"] = FALSE;
        elseif ($_ophir_odt_import_conf["features"]["table"] === 2) {
            $translation_table["table:table"] = "table cellspacing=0 cellpadding=0 border=1";
            $translation_table["table:table-row"] = "tr";
            $translation_table["table:table-cell"] = "td";
        }
        if ($_ophir_odt_import_conf["features"]["table of contents"] === 0) $translation_table['text:table-of-content'] = FALSE;
        elseif ($_ophir_odt_import_conf["features"]["table of contents"] === 2) {
            $translation_table['text:table-of-content'] = 'div class="odt-table-of-contents"';
        }
        $translation_table['text:line-break'] = 'br';

        while ($xml->read()) {
            $opened_tags = array(); //This array will contain the HTML tags opened in every iteration

            if ($xml->nodeType === XMLReader::END_ELEMENT) { //Handle a closing tag
                if (empty($elements_tree)) continue;
                do {
                    $element = array_pop($elements_tree);
                    if ($element && $element["tags"]) {
                        //Close opened tags
                        $element["tags"] = array_reverse($element["tags"]);
                        foreach ($element["tags"] as $HTML_tag) {
                            //$html.= "<font style='color:red' title='Closing $HTML_tag, from $element[name]. Current element is " .($xml->name). "'>©</font>";
                            $HTML_tag = current(explode(" ", $HTML_tag));
                            $html .= "</" . $HTML_tag . ">";
                        }
                    }
                } while ($xml->name !== $element["name"] && $element); //Close every opened tags. This should also handle malformed XML files
                continue;
            } elseif (in_array($xml->nodeType,
                array(XMLReader::ELEMENT,
                    XMLReader::TEXT,
                    XMLReader::SIGNIFICANT_WHITESPACE))
            ) { //Handle tags
                switch ($xml->name) {
                    case "#text": //Text
                        $html .= htmlspecialchars($xml->value);
                        break;
                    case "text:h": //Title
                        if ($_ophir_odt_import_conf["features"]["header"] === 0) {
                            $xml->next();
                            break;
                        } elseif ($_ophir_odt_import_conf["features"]["header"] === 1) break;
                        $n = $xml->getAttribute("text:outline-level");
                        if ($n > 6) $n = 6;
                        $opened_tags[] = "h$n";
                        $html .= "\n\n<h$n>";
                        break;

                    case "text:p": //Paragraph
                        //Just convert odf <text:p> to html <p>
                        $tags = @$styles[$xml->getAttribute("text:style-name")]["tags"];
                        if (!($tags && !in_array("blockquote", $tags))) {
                            // Do not print a <p> immediatly after or before a <blockquote>
                            $opened_tags[] = "p";
                            $html .= "\n<p>";
                        }
                        break;

                    case "text:a":
                        if ($_ophir_odt_import_conf["features"]["link"] === 0) {
                            $xml->next();
                            break;
                        } elseif ($_ophir_odt_import_conf["features"]["link"] === 1) break;
                        $href = $xml->getAttribute("xlink:href");
                        $opened_tags[] = 'a';
                        $html .= '<a href="' . $href . '">';
                        break;

                    case "draw:image":
                        if ($_ophir_odt_import_conf["features"]["image"] === 0) {
                            $xml->next();
                            break;
                        } elseif ($_ophir_odt_import_conf["features"]["image"] === 1) break;

                        $image_file = 'zip://' . $odt_file . '#' . $xml->getAttribute("xlink:href");
                        if (isset($_ophir_odt_import_conf["images_folder"]) &&
                            is_dir($_ophir_odt_import_conf["images_folder"])
                        ) {
                            if (ophir_is_image($image_file)) {
                                $image_to_save = $_ophir_odt_import_conf["images_folder"] . '/' . basename($image_file);
                                if (!($src = ophir_copy_file($image_file, $image_to_save))) {
                                    ophir_error("Unable to move image file");
                                    break;
                                }
                            } else {
                                ophir_error("Found invalid image file.");
                                break;
                            }
                        } else {
                            //ophir_error('Unable to save the image. Creating a data URL. Image saved directly in the body.F');
                            $src = 'data:image;base64,' . base64_encode(file_get_contents($image_file));
                        }
                        $html .= "\n<img src=\"$src\" />";
                        break;

                    case "style:style":
                        $name = $xml->getAttribute("style:name");
                        $parent = $xml->getAttribute("style:parent-style-name");
                        if (array_key_exists($parent, $styles)) $styles[$name] = $styles[$parent]; //Not optimal

                        if ($xml->isEmptyElement) break; //We can't handle that at the moment
                        while ($xml->read() && //Read one tag
                            ($xml->name != "style:style" || $xml->nodeType != XMLReader::END_ELEMENT) //Stop on </style:style>
                        ) {
                            if ($xml->name == "style:text-properties") {
                                if ($xml->getAttribute("fo:font-style") == "italic")
                                    $styles[$name]["tags"][] = "em"; //Creates the style and add <em> to its tags

                                if ($xml->getAttribute("fo:font-weight") == "bold")
                                    $styles[$name]["tags"][] = "strong"; //Creates the style and add <strong> to its tags

                                if ($xml->getAttribute("style:text-underline-style") == "solid")
                                    $styles[$name]["tags"][] = "u"; //Creates the style and add <u> to its tags

                            }
                        }
                        break;
                    case "text:note":
                        if ($_ophir_odt_import_conf["features"]["note"] === 0) {
                            $xml->next();
                            break;
                        } elseif ($_ophir_odt_import_conf["features"]["note"] === 1) break;
                        $note_id = $xml->getAttribute("text:id");
                        $note_name = "Note";
                        while ($xml->read() && //Read one tag
                            ($xml->name != "text:note" || $xml->nodeType != XMLReader::END_ELEMENT) //Stop on </style:style>
                        ) {
                            if ($xml->name == "text:note-citation" &&
                                $xml->nodeType == XMLReader::ELEMENT
                            )
                                $note_name = $xml->readString();
                            elseif ($xml->name == "text:note-body" &&
                                $xml->nodeType == XMLReader::ELEMENT
                            ) {
                                $note_content = odt2html($odt_file, $xml->readOuterXML());
                            }
                        }

                        $html .= "<sup><a href=\"#odt-footnote-$note_id\" class=\"odt-footnote-anchor\" name=\"anchor-odt-$note_id\">$note_name</a></sup>";

                        $footnotes .= "\n" . '<div class="odt-footnote" id="odt-footnote-' . $note_id . '" >';
                        $footnotes .= '<a class="footnote-name" href="#anchor-odt-' . $note_id . '">' . $note_name . ' .</a> ';
                        $footnotes .= $note_content;
                        $footnotes .= '</div>' . "\n";
                        break;

                    case "office:annotation":
                        if ($_ophir_odt_import_conf["features"]["annotation"] === 0) {
                            $xml->next();
                            break;
                        } elseif ($_ophir_odt_import_conf["features"]["annotation"] === 1) break;
                        $annotation_id = (isset($annotation_id)) ? $annotation_id + 1 : 1;
                        $annotation_content = "";
                        $annotation_creator = "Anonymous";
                        $annotation_date = "";
                        do {
                            $xml->read();
                            if ($xml->name == "dc:creator" &&
                                $xml->nodeType == XMLReader::ELEMENT
                            )
                                $annotation_creator = $xml->readString();
                            elseif ($xml->name == "dc:date" &&
                                $xml->nodeType == XMLReader::ELEMENT
                            ) {
                                $annotation_date = date("jS \of F Y, H\h i\m", strtotime($xml->readString()));
                            } elseif ($xml->nodeType == XMLReader::ELEMENT) {
                                $annotation_content .= $xml->readString();
                                $xml->next();
                            }
                        } while (!($xml->name === "office:annotation" &&
                            $xml->nodeType === XMLReader::END_ELEMENT));
                        //End of the note

                        $html .= '<sup><a href="#odt-annotation-' . $annotation_id . '" name="anchor-odt-annotation-' . $annotation_id . '" title="Annotation (' . $annotation_creator . ')">(' . $annotation_id . ')</a></sup>';
                        $footnotes .= "\n" . '<div class="odt-annotation" id="odt-annotation-' . $annotation_id . '" >';
                        $footnotes .= '<a class="annotation-name" href="#anchor-odt-annotation-' . $annotation_id . '"> (' . $annotation_id . ')&nbsp;</a>';
                        $footnotes .= "\n" . '<b>' . $annotation_creator . ' (<i>' . $annotation_date . '</i>)</b> :';
                        $footnotes .= "\n" . '<div class="odt-annotation-content">' . $annotation_content . '</div>';
                        $footnotes .= '</div>' . "\n";
                        break;

                    default:
                        if (array_key_exists($xml->name, $translation_table)) {
                            if ($translation_table[$xml->name] === FALSE) {
                                $xml->next();
                                break;
                            }
                            $tag = explode(" ", $translation_table[$xml->name], 1);
                            //$tag[0] is the tag name, other indexes are attributes
                            $opened_tags[] = $tag[0];
                            $html .= "\n<" . $translation_table[$xml->name] . ">";
                        }
                }
            }

            if ($xml->nodeType === XMLReader::ELEMENT &&
                !($xml->isEmptyElement)
            ) { //Opening tag
                $current_element_style = $xml->getAttribute("text:style-name");
                if ($current_element_style &&
                    isset($styles[$current_element_style])
                ) {
                    //Styling tags management
                    foreach ($styles[$current_element_style]["tags"] as $HTML_tag) {
                        $html .= "<" . $HTML_tag . ">";
                        $opened_tags[] = $HTML_tag;
                    }
                }
                $elements_tree[] = array("name" => $xml->name,
                    "tags" => $opened_tags);
            }

        }
        return $html . $footnotes;
    }


    /**
     * @param $filePath
     */
    public function __construct($filePath)
    {
        $this->filename = $filePath;
    }


    /**
     * @return mixed|string
     */
    private function read_doc()
    {
        $fileHandle = fopen($this->filename, "r");
        $line = @fread($fileHandle, filesize($this->filename));
        $lines = explode(chr(0x0D), $line);

        $outtext = "";
        foreach ($lines as $thisline) {
            $pos = mb_strpos($thisline, chr(0x00), null, 'utf-8');
            if (($pos !== FALSE) || (mb_strlen($thisline, 'utf-8') == 0)) {
                // $outtext .= $thisline."\n";
            } else {

                $outtext .= $thisline . "\n";
            }
        }
        $outtext = preg_replace("/[^a-zA-Z0-9\p{Greek}\s\,\.\-\r\t@\/\_\(\)]/", "", $outtext);
        $outtext = nl2br($outtext);

        return $outtext;
    }

    /**
     * @return bool|string
     */
    private function read_docx()
    {

        $striped_content = '';
        $content = '';

        $zip = zip_open($this->filename);

        if (!$zip || is_numeric($zip)) return false;

        while ($zip_entry = zip_read($zip)) {

            if (zip_entry_open($zip, $zip_entry) == FALSE) continue;

            if (zip_entry_name($zip_entry) != "word/document.xml") continue;

            $content .= zip_entry_read($zip_entry, zip_entry_filesize($zip_entry));

            zip_entry_close($zip_entry);
        }
        // end while

        zip_close($zip);

        $content = str_replace('</w:r></w:p></w:tc><w:tc>', " ", $content);
        $content = str_replace('</w:r></w:p>', "\r\n", $content);
        $striped_content = strip_tags($content);

        return $striped_content;
    }

    /************************excel sheet************************************/
    /**
     * @param $input_file
     * @return string
     */
    function xlsx_to_text($input_file)
    {
        $xml_filename = "xl/sharedStrings.xml"; //content file name
        $zip_handle = new ZipArchive;
        $output_text = "";
        if (true === $zip_handle->open($input_file)) {
            if (($xml_index = $zip_handle->locateName($xml_filename)) !== false) {
                $xml_datas = $zip_handle->getFromIndex($xml_index);
                $dom = new DOMDocument();
                $dom->loadXML($xml_datas, LIBXML_NOENT | LIBXML_XINCLUDE | LIBXML_NOERROR | LIBXML_NOWARNING);

                $output_text = strip_tags($dom->saveXML());
            } else {
                $output_text .= "";
            }
            $zip_handle->close();
        } else {
            $output_text .= "";
        }
        return $output_text;
    }

    /**
     * @param $input_file
     * @return string
     */
    function pptx_to_text($input_file)
    {
        $zip_handle = new ZipArchive;
        $output_text = "";
        if (true === $zip_handle->open($input_file)) {
            $slide_number = 1; //loop through slide files
            while (($xml_index = $zip_handle->locateName("ppt/slides/slide" . $slide_number . ".xml")) !== false) {
                $xml_datas = $zip_handle->getFromIndex($xml_index);
                $dom = new DOMDocument();
                $xml_handle = $dom->loadXML($xml_datas, LIBXML_NOENT | LIBXML_XINCLUDE | LIBXML_NOERROR | LIBXML_NOWARNING);
                $output_text .= strip_tags($dom->saveXML());
                $slide_number++;
            }
            if ($slide_number == 1) {
                $output_text .= "";
            }
            $zip_handle->close();
        } else {
            $output_text .= "";
        }
        return $output_text;
    }

    /**
     * @param $filename
     * @return bool|string
     */
    function read_word($filename)
    {
        $striped_content = '';
        $content = '';

        if (!$filename || !file_exists($filename)) return false;

        $zip = zip_open($filename);

        if (!$zip || is_numeric($zip)) return false;

        while ($zip_entry = zip_read($zip)) {

            if (zip_entry_open($zip, $zip_entry) == FALSE) continue;

            if (zip_entry_name($zip_entry) != "word/document.xml") continue;

            $content .= zip_entry_read($zip_entry, zip_entry_filesize($zip_entry));

            zip_entry_close($zip_entry);
        }
        // end while

        zip_close($zip);


        return $this->_read_word($content);
    }


    /**
     * @param $filename
     * @return bool|string
     */
    function read_odt($filename)
    {
        $striped_content = '';
        $content = '';

        if (!$filename || !file_exists($filename)) return false;

        $zip = zip_open($filename);

        if (!$zip || is_numeric($zip)) return false;

        while ($zip_entry = zip_read($zip)) {

            if (zip_entry_open($zip, $zip_entry) == FALSE) continue;

            if (zip_entry_name($zip_entry) != "content.xml") continue;

            $content .= zip_entry_read($zip_entry, zip_entry_filesize($zip_entry));

            zip_entry_close($zip_entry);
        }
        // end while

        zip_close($zip);


        return $this->_read_odt($content);
    }


    /**
     * @param $string
     * @return string
     */
    function _read_word($string)
    {

        $reader = new XMLReader;
        $reader->xml($string);

        error_reporting(0);
        // set up variables for formatting
        $text = '';
        $formatting['bold'] = 'closed';
        $formatting['italic'] = 'closed';
        $formatting['underline'] = 'closed';
        $formatting['header'] = 0;

        // loop through docx xml dom
        while ($reader->read()) {
            // look for new paragraphs
            if ($reader->nodeType == XMLREADER::ELEMENT && $reader->name === 'w:p') {
                // set up new instance of XMLReader for parsing paragraph independantly
                $paragraph = new XMLReader;
                $p = $reader->readOuterXML();
                $paragraph->xml($p);

                // search for heading
                preg_match('/<w:pStyle w:val="(Heading.*?[1-6])"/', $p, $matches);
                switch ($matches[1]) {
                    case 'Heading1':
                        $formatting['header'] = 1;
                        break;
                    case 'Heading2':
                        $formatting['header'] = 2;
                        break;
                    case 'Heading3':
                        $formatting['header'] = 3;
                        break;
                    case 'Heading4':
                        $formatting['header'] = 4;
                        break;
                    case 'Heading5':
                        $formatting['header'] = 5;
                        break;
                    case 'Heading6':
                        $formatting['header'] = 6;
                        break;
                    default:
                        $formatting['header'] = 0;
                        break;
                }

                // open h-tag or paragraph
                $text .= ($formatting['header'] > 0) ? '<h' . $formatting['header'] . '>' : '<p>';

                // loop through paragraph dom
                while ($paragraph->read()) {
                    // look for elements
                    if ($paragraph->nodeType == XMLREADER::ELEMENT && $paragraph->name === 'w:r') {
                        $node = trim($paragraph->readInnerXML());

                        // add <br> tags
                        if (strstr($node, '<w:br ')) $text .= '<br>';

                        // look for formatting tags
                        $formatting['bold'] = (strstr($node, '<w:b/>')) ? (($formatting['bold'] == 'closed') ? 'open' : $formatting['bold']) : (($formatting['bold'] == 'opened') ? 'close' : $formatting['bold']);
                        $formatting['italic'] = (strstr($node, '<w:i/>')) ? (($formatting['italic'] == 'closed') ? 'open' : $formatting['italic']) : (($formatting['italic'] == 'opened') ? 'close' : $formatting['italic']);
                        $formatting['underline'] = (strstr($node, '<w:u ')) ? (($formatting['underline'] == 'closed') ? 'open' : $formatting['underline']) : (($formatting['underline'] == 'opened') ? 'close' : $formatting['underline']);

                        // build text string of doc
                        $text .= (($formatting['bold'] == 'open') ? '<strong>' : '') .
                            (($formatting['italic'] == 'open') ? '<em>' : '') .
                            (($formatting['underline'] == 'open') ? '<u>' : '') .
                            // htmlentities(iconv('UTF-8', 'ASCII//TRANSLIT',$paragraph->expand()->textContent)).
                            $paragraph->expand()->textContent .
                            (($formatting['underline'] == 'close') ? '</u>' : '') .
                            (($formatting['italic'] == 'close') ? '</em>' : '') .
                            (($formatting['bold'] == 'close') ? '</strong>' : '');

                        // reset formatting variables
                        foreach ($formatting as $key => $format) {
                            if ($format == 'open') $formatting[$key] = 'opened';
                            if ($format == 'close') $formatting[$key] = 'closed';
                        }
                    }
                }
                $text .= ($formatting['header'] > 0) ? '</h' . $formatting['header'] . '>' : '</p>';
            }

        }
        $reader->close();
        return $text;
    }

    /**
     * @param $filename
     * @return string
     */
    function read_xls($filename)
    {

        $map = \PHPExcel_IOFactory::load($filename);
        $maxCell = $map->getActiveSheet()->getHighestRowAndColumn();
        $mapdata = $map->getActiveSheet()->rangeToArray('A1:' . $maxCell['column'] . $maxCell['row']);
        return $this->build_table($mapdata);
    }

    /**
     * @param $array
     * @return string
     */
    function build_table($array)
    {

        // start table

        $html = '<table>';

        // header row

        $html .= '<tr><th>#</th>';

        $header = reset($array);

        foreach ($header as $key => $value) {

            $html .= '<th>' . $key . '</th>';

        }

        $html .= '</tr>';

        // data rows
        $count = 0;
        foreach ($array as $key => $value) {
            $count++;
            $html .= '<tr><td>' . $count . '</td>';

            foreach ($value as $key2 => $value2) {

                $html .= '<td>' . $value2 . '</td>';

            }

            $html .= '</tr>';

        }

        // finish table and return it

        $html .= '</table>';

        return $html;

    }

    /**
     * @return bool|mixed|string
     */
    public function convertToText()
    {

        if (isset($this->filename) && !file_exists($this->filename)) {
            return "File Not exists";
        }

        $fileArray = pathinfo($this->filename);
        $file_ext = $fileArray['extension'];
        if ($file_ext == "doc" || $file_ext == 'odt' || $file_ext == "docx" || $file_ext == "xlsx" || $file_ext == "xls" || $file_ext == "ods" || $file_ext == "pptx" || $file_ext == "ppt") {
            if ($file_ext == "doc") {
                return $this->read_doc();
            } elseif ($file_ext == "docx") {
                $read = $this->read_word($this->filename);
                return (trim($read)) ? $read : $this->odt2html($this->filename);
                //return $this->read_docx();
            } elseif ($file_ext == "xlsx" || $file_ext == "xls" || $file_ext == "ods") {
                //return $this->xlsx_to_text($this->filename);
                return $this->read_xls($this->filename);
            } elseif ($file_ext == "pptx" || $file_ext == 'ppt') {
//                return $this->read_ppt($this->filename);
                return $this->pptx_to_text($this->filename);
            } elseif ($file_ext == 'odt') {
                return $this->odt2html($this->filename);
            }
        } else {
            return "Invalid File Type";
        }
    }

}