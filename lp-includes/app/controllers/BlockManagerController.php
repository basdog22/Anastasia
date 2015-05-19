<?php

/**
 * Class BlockManagerController
 */
class BlockManagerController extends \BackendbaseController{
    /**
     * @var string
     */
    protected $layout = 'layouts.backend.laradmin';

    /**
     * Show the block manager
     */
    public function manage(){
        add_breadcrumb(trans('strings.gridmanager'),url('backend/blockmanager'));
        $layouts = \Event::fire('blockmanager.layouts.collect');



//        ll($layouts);
        $this->layout->content = \View::make('backend/blockmanager/manager')->withLayouts($layouts);
    }

    /**
     * Sort the blocks
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function sort(){
        $id = (int) requested('itemid');
        $sort = (int) requested('newsort');
        $block = \GridManager::find($id);
        if(is_null($block)){
            return \Response::json(array('type' => 'danger', 'text' => t('messages.block_not_found')));
        }
        $block->order_weight = $sort;
        if($block->save()){
            return \Response::json(array('type' => 'success', 'text' => t('messages.block_order_changed')));
        }
        return \Response::json(array('type' => 'success', 'text' => t('messages.error_occured')));
    }

    /**
     * Show the add block dialog
     *
     * @param $positiongrid
     * @return mixed
     */
    public function addblock($positiongrid){
        $positiongrid = explode("-",$positiongrid);
        $position = $positiongrid[0];
        $layout = $positiongrid[1];
        $grid = $positiongrid[2];
        $blocks = get_content_blocks();

        $existing = \GridManager::where('name','!=','default_main_content')->get();

        if(\Request::ajax()){
            return \View::make('backend/blockmanager/add')->withLayout($layout)->withGrid($grid)->withPosition($position)->withBlocks($blocks)->withExisting($existing);
        }
        $this->layout->content = \View::make('backend/blockmanager/add')->withLayout($layout)->withGrid($grid)->withPosition($position)->withBlocks($blocks);

    }

    /**
     * Add a block to the specified grid position
     *
     * @return mixed
     */
    public function addtogrid(){


        $block = new \GridManager();
        $position = requested('position');
        $grid = requested('grid');
        $layout = requested('layout');
        $existing = requested('existing');

        if(isset($existing)){
            $blockid = (int) requested('blockid');
            $copyfrom = \GridManager::find($blockid);
            $params = $copyfrom->params;
            $name = $copyfrom->name;
            $block->title = t($copyfrom->title);
        }else{
            $name = requested('block_title');
            $block_info = get_block_info($name);
            $params = (isset($block_info['params']))?$block_info['params']:array();
            $block->title = t($block_info['title']);
        }


        $tpl = array_keys($block_info['tpl']);



        //check if user tries to add same block on same layout but block does not support this behavior
        if(!$block_info['multiple']){
            $exists = \GridManager::where('name','=',$name)->where('layout','=',$layout)->first();
            if(!is_null($exists)){
                return \Redirect::back()->withMessage($this->notifyView(t('messages.block_already_exists'),'danger'));
            }
        }


        $block->theme_domain = get_theme_domain();

        $block->position = $position;
        $block->grid = $grid;
        $block->tpl = $tpl[0];
        $block->params = $params;
        $block->order_weight = 0;
        $block->layout = $layout;
        $block->name = $name;

        try{
            $block->save();
            return \Redirect::back()->withMessage($this->notifyView(t('messages.block_added')));
        }catch (Exception $e){
            return \Redirect::back()->withMessage($this->notifyView($e->getMessage(),'danger'));
        }

    }
    /**
     * Shows the edit block dialog
     *
     * @param $blockid
     * @return mixed
     */
    public function editblock($blockid){
        $blockid = (int) $blockid;
        $block = \GridManager::find($blockid);
        $block_info = get_block_info($block->name);
        $block_info['params'] = $block->params;
        $model = (isset($block_info['model']))?$block_info['model']:false;
        $data = false;
        if(isset($block_info['params_action']) && !isset($block_info['params_tpl'])){
            $paramsaction = $block_info['params_action'];
            $paramsargs = $block_info['params_args'];
            $data = $model::$paramsaction($paramsargs[0],$paramsargs[1]);
        }elseif(isset($block_info['params_tpl']) && isset($block_info['params']) && !isset($block_info['params_action'])){
            $data = (string) \View::make($block_info['params_tpl'])->withParams($block_info['params'])->withBlock($block);
        }elseif(isset($block_info['params_tpl']) && isset($block_info['params']) && isset($block_info['params_action'])){
            $paramsaction = $block_info['params_action'];
            $paramsargs = $block_info['params_args'];
            $data = $model::$paramsaction($paramsargs[0],$paramsargs[1]);
            $data = (string) \View::make($block_info['params_tpl'])->withParams($block_info['params'])->withData($data)->withBlock($block);

        }

        if(\Request::ajax()){
            return View::make('backend/blockmanager/edit')->withBlock($block)->withInfo($block_info)->withData($data);
        }else{
            $this->layout->content = \View::make('backend/blockmanager/edit')->withBlock($block)->withInfo($block_info)->withData($data);
        }
    }

    /**
     * Save the block data and params
     *
     * @return mixed
     */
    public function save(){
        $blockid = (int) requested('blockid');
        $block = \GridManager::find($blockid);
        $block_info = get_block_info($block->name);
        $params = requested('params');
        $tpl = requested('tpl');
        $block->params = $params;
        $block->tpl = $tpl;
        $block->title = t($block_info['title']);
        if(isset($block_info['params_title'])){
            try{
                if(isset($block_info['model'])  && $block_info['model']!='\Widget'){
                    $model = $block_info['model'];
                    $title = $params[$block_info['params_title']];
                    if(is_numeric($title)){
                        $title = $model::find($title);
                    }else{
                        $title = $model::where('title','=',$title)->first();
                    }

                    $title = $title->title;
                }
            }catch(\Exception $e){

                $title = $params[$block_info['params_title']];
            }
            $block->title .= ' - '. $title;
        }elseif($block_info['name']=='default_static_block'){
            $title = explode("/",$tpl);
            $title = end($title);
            $block->title .= ' - '. $title;
        }else{
            $block->title .= ' - '.$block->layout;
        }

        if($block->save()){
            return \Redirect::to('backend/blockmanager')->withMessage($this->notifyView(t('messages.block_saved'),'success'));
        }
        return Redirect::to('backend/blockmanager')->withMessage($this->notifyView(t('messages.error_occured'),'danger'));
    }

    /**
     * Deletes a block
     *
     * @param $blockid
     * @return mixed
     */
    public function delblock($blockid){
        $blockid = (int) $blockid;
        $block = \GridManager::find($blockid);
        if($block->delete()){
            return \Redirect::back()->withMessage($this->notifyView(t('messages.block_deleted')));
        }
        return \Redirect::back()->withMessage($this->notifyView(t('messages.error_occured'),'danger'));
    }

    /**
     * Moves a block from one grid to another
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function moveblock(){
        $blockid = (int) requested('block');
        $grid = requested('grid');
        $layout = requested('layout');
        $position =  requested('position');

        $block = \GridManager::find($blockid);
        if(is_null($block)){
            return \Response::json(array('type' => 'danger', 'text' => t('messages.block_not_found'),'danger'));
        }
        $block->grid = $grid;
        $block->layout = $layout;
        $block->position = $position;
        if($block->save()){
            return \Response::json(array('type' => 'success', 'text' => t('messages.block_updated')));
        }
        return \Response::json(array('type' => 'success', 'text' => t('messages.error_occured'),'danger'));
    }

}
