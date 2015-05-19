<?php

/**
 * Class TaxonomiesController
 */
class TaxonomiesController extends BackendbaseController {
    /**
     * @var string
     */
    protected $layout = 'layouts.backend.laradmin';


    /**
     * Shows the taxonomies screen
     *
     * @param bool $taxid
     */
    public function taxonomies($taxid=false){
        add_breadcrumb(trans('strings.taxonomies'),url('backend/taxonomies'));
        $taxonomies = \Taxonomy::whereIsRoot()->get();

        $this->layout->content = \View::make('backend/taxonomies/taxonomies')->withTaxonomies($taxonomies);
    }

    /**
     * Browse the taxonomy specified
     *
     * @param $taxid
     * @return mixed
     */
    public function browsetaxonomies($taxid){
        add_breadcrumb(trans('strings.taxonomies'),url('backend/taxonomies'));
        $taxonomy = \Taxonomy::find($taxid);
        if(is_null($taxonomy)){
            return \Redirect::to('backend/taxonomies')->withMessage($this->notifyView(t('messages.taxonomy_not_found'),'danger'));
        }
        $taxonomies = $taxonomy->children;
        //$taxonomies = View::make('backend/taxonomies/options')->withTaxonomies($taxonomies);
        $this->layout->content = \View::make('backend/taxonomies/taxonomies')->withTaxonomies($taxonomies);
    }

    /**
     * Show the new taxonomy dialog
     *
     * @return mixed
     */
    public function newtaxonomy(){
        $tree = \Taxonomy::whereIsRoot()->get();

        $tree = \View::make('backend/taxonomies/options')->withTaxonomies($tree);
//        $tree->linkNodes();
//        ll($tree->children);
        if (\Request::ajax()){
            return \View::make('backend/taxonomies/new')->withTaxonomies($tree);
        }else{
            $this->layout->content = \View::make('backend/taxonomies/new')->withTaxonomies($tree);
        }
    }

    /**
     * Add a new taxonomy
     *
     * @return mixed
     */
    public function createtaxonomy(){
        $taxonomy = new \Taxonomy;

        $a = (requested('name'))?$taxonomy->name = requested("name"):'';
        $taxonomy->title = requested("title");
        $taxonomy->parent_id = (int) requested("parent");

        try{
            $taxonomy->save();
            return Redirect::to('backend/taxonomies')->withMessage($this->notifyView(t('messages.taxonomy_saved'),'success'));
        }catch (Exception $e){
            return Redirect::to('backend/taxonomies')->withMessage($this->notifyView($e->getMessage(),'danger'));
        }
    }

    /**
     * Shows the taxonomy edit dialog
     *
     * @param $taxid
     * @return mixed
     */
    public function edittaxonomy($taxid){
        $checkformultiple = explode(",",$taxid);
        $taxonomies = array();

        if(isset($checkformultiple[1])){

            foreach($checkformultiple as $taxid){
                $taxid = (int) $taxid;
                $taxonomy = \Taxonomy::find($taxid);
                $taxonomies[] = $taxonomy;

            }
            add_breadcrumb(trans('strings.taxonomies'),url('backend/taxonomies'));
            add_breadcrumb(trans('strings.bulk_edit'),url('backend/taxonomies'));
            $this->layout->content = \View::make('backend/taxonomies/bulk')->withTaxonomies($taxonomies);
        }else{
            $taxonomy = \Taxonomy::find($taxid);

            if(is_null($taxonomy)){
                return \Redirect::to('backend/taxonomies')->withMessage($this->notifyView(t('messages.taxonomy_not_found'),'danger'));
            }
            $tree = \Taxonomy::whereIsRoot()->get();
            $tree = \View::make('backend/taxonomies/options')->withTaxonomies($tree);
            if (\Request::ajax()){
                return \View::make('backend/taxonomies/edit')->withTaxonomies($tree)->withTaxonomy($taxonomy);
            }else{
                $this->layout->content = \View::make('backend/taxonomies/edit')->withTaxonomies($tree)->withTaxonomy($taxonomy);
            }
        }
    }

    /**
     * Updates a taxonomy
     *
     * @return mixed
     */
    public function updatetaxonomy(){
        $taxid = requested('tax_id');
        $name = requested('name');
        $title = requested('title');
        $parent = requested('parent');
        $taxonomy = \Taxonomy::find($taxid);
        if(is_null($taxonomy)){
            return \Redirect::to('backend/taxonomies')->withMessage($this->notifyView(t('messages.taxonomy_not_found'),'danger'));
        }

        $taxonomy->name = $name;
        $taxonomy->title = $title;
        $taxonomy->parent_id = $parent;

        try{
            $taxonomy->save();
            return \Redirect::to('backend/taxonomies')->withMessage($this->notifyView(t('messages.taxonomy_saved'),'success'));
        }catch (Exception $e){
            return \Redirect::to('backend/taxonomies')->withMessage($this->notifyView($e->getMessage(),'danger'));
        }


    }

    public function bulkdeletetaxonomies(){
        $ids = requested('ids');
        foreach($ids as $id){
            $id = (int) $id;
            $taxonomy = \Taxonomy::find($id);
            if(!$taxonomy->delete()){
                return \Response::json(array('type' => 'danger', 'text' => t('messages.error_occured')));
            }
        }
        return \Response::json(array('type' => 'success', 'text' => t('messages.taxonomies_deleted')));
    }

    public function bulkedittaxonomies(){
        $ids = requested('ids');
        return \Response::json(array('type' => 'success', 'text' => redirect_script(url('backend/taxonomies/edit/'.implode(",",$ids)))));
    }

    /**
     * Deletes a taxonomy
     *
     * @param $taxid
     * @return mixed
     */
    public function deletetaxonomy($taxid){
        $taxonomy = \Taxonomy::find($taxid);
        if(is_null($taxonomy)){
            return \Redirect::to('backend/taxonomies')->withMessage($this->notifyView(t('messages.taxonomy_not_found'),'danger'));
        }
        try{
            $taxonomy->delete();
            return \Redirect::to('backend/taxonomies')->withMessage($this->notifyView(t('messages.taxonomy_deleted'),'success'));
        }catch (Exception $e){
            return \Redirect::to('backend/taxonomies')->withMessage($this->notifyView($e->getMessage(),'danger'));
        }
    }
}