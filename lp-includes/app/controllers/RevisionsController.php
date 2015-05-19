<?php

/**
 * Class RevisionsController
 */
class RevisionsController extends BackendbaseController {
    /**
     * @var string
     */
    protected $layout = 'layouts.backend.laradmin';


    /**
     * View the revision specified
     *
     * @param $revision_id
     * @return mixed
     */
    public function viewrevision($revision_id){
        $revision = \Revision::find($revision_id);

        if (Request::ajax()){
            return \View::make('backend/revision')->withRevision($revision);
        }else{
            $this->layout->content = \View::make('backend/revision')->withRevision($revision);
        }
    }

    /**
     * Restores a revision
     *
     * @param $revision_id
     * @return mixed
     */
    public function restorerevision($revision_id){
        $revision = \Revision::find($revision_id);
        $model = $revision->revisionable_type;
        $key = $revision->key;
        $item = $model::find($revision->revisionable_id);

        $item->$key = $revision->old_value;
        $item->save();
        return \Redirect::back()->withMessage($this->notifyView(t('messages.revision_restored')));
    }

    /**
     * Deletes the revision specified
     *
     * @param $revision_id
     * @return mixed
     */
    public function removerevision($revision_id){
        $revision = \Revision::find($revision_id);
        if(!$revision->delete()){
            return \Redirect::back()->withMessage($this->notifyView(t('messages.error_occured'),'danger'));
        }
        return \Redirect::back()->withMessage($this->notifyView(t('messages.revision_removed')));
    }

}