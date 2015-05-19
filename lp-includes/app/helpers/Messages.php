<?php

/**
 * Class MessagesHelper
 */
class MessagesHelper{
    /**
     * @param $message
     * @param $type
     * @return string
     */
    public static function message_format($message,$type)
    {
        return '<div role="alert" class="alert alert-'.$type.' alert-dismissible ">
        <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">'.t('strings.close').'</span></button>
        '.$message.'</div>';
    }
}