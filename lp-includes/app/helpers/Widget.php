<?php

/**
 * Class Widget
 */
class Widget
{
    /**
     * @param $title
     * @param $data
     * @param bool $loadview
     * @param bool $scrollbars
     * @return string
     */
    static function show($title, $data, $loadview = false,$scrollbars=true)
    {

        ob_start();
        ?>
    <div id="<?php echo Widget::getid() ?>" class="col-md-4 ms31 box widget-box">
        <div class="box-header handle">
            <div class="box-name">
                <span><?php echo $title ?></span>
            </div>
            <div class="box-icons">
                <a class="move-activ" href="#"><i class="fa fa-arrows "></i></a>
            </div>
        </div>
        <div data-height="300" class="box-content <?php if($scrollbars):?>scrollbars<?php endif;?>">
            <?php
            if ($loadview) {
                $data = View::make($loadview)->with($data);
            }
            echo $data
            ?>
        </div>
        </div><?php
        $result = ob_get_clean();
        return $result;
    }

    /**
     * @return string
     */
    static function getid()
    {
        $widgets = Config::get('cms.widgets');
        $widgets['widget_' . count($widgets)] = 1;
        Config::set('cms.widgets', $widgets);
        return 'widget_' . count($widgets);
    }

    /**
     * @param array $params
     * @return array
     */
    static function getblockdata($params=array()){
        return $params;
    }

}