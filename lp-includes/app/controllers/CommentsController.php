<?php

/**
 * Class CommentsController
 */
class CommentsController extends BackendbaseController {
    /**
     * @var string
     */
    protected $layout = 'layouts.backend.laradmin';

    public function comments($status=false){
        if($status===false){
            $comments = \Comments::paginate(\Config::get('settings.cms.paging'));
        }else{
            $status = (int) $status;
            $comments = \Comments::whereStatus($status)->paginate(\Config::get('settings.cms.paging'));
        }
        $this->layout->content = \View::make('backend/comments/list')->withComments($comments);
    }

    public function edit($comment_id){
        $comment = \Comments::find($comment_id);
        if (\Request::ajax()){
            return \View::make('backend/comments/edit')->with('comment',$comment);
        }else{
            $this->layout->content = \View::make('backend/comments/edit')->with('comment',$comment);
        }
    }

    public function save(){
        $comment_id = (int) requested('comment_id');
        $title = strip_tags(requested('title'));
        $content = requested('body');
        $status = (int) requested('status');

        $comment = \Comments::find($comment_id);
        $comment->title = $title;
        $comment->body = $content;
        $comment->status = $status;
        if($comment->save()){
            return \Redirect::to('backend/comments')->withMessage($this->notifyView(t('messages.comment_saved'),'success'));
        }
        return \Redirect::to('backend/comments')->withMessage($this->notifyView(t('messages.error_occured'),'danger'));
    }

    public function approve($comment_id){
        $comment = \Comments::find($comment_id);
        $comment->status = 1;
        if($comment->save()){
            return \Redirect::back()->withMessage($this->notifyView(t('messages.comment_approved'),'success'));
        }
        return \Redirect::back()->withMessage($this->notifyView(t('messages.error_occured'),'danger'));
    }

    public function disapprove($comment_id){
        $comment = \Comments::find($comment_id);
        $comment->status = 0;
        if($comment->save()){
            return \Redirect::back()->withMessage($this->notifyView(t('messages.comment_disapproved'),'success'));
        }
        return \Redirect::back()->withMessage($this->notifyView(t('messages.error_occured'),'danger'));
    }

    public function delete($comment_id){
        $comment = \Comments::find($comment_id);
        if($comment->delete()){
            return \Redirect::back()->withMessage($this->notifyView(t('messages.comment_deleted'),'success'));
        }
        return \Redirect::back()->withMessage($this->notifyView(t('messages.error_occured'),'danger'));
    }

    public function bulkapprove(){
        $ids = requested('ids');
        foreach($ids as $id){
            $id = (int) $id;
            $comment = \Comments::find($id);
            $comment->status = 1;
            if($comment->save()){

            }else{
                return \Response::json(array('type' => 'danger', 'text' => t('messages.error_occured')));
            }
        }
        return \Response::json(array('type' => 'success', 'text' => t('messages.comments_approved')));
    }

    public function bulkdisapprove(){
        $ids = requested('ids');
        foreach($ids as $id){
            $id = (int) $id;
            $comment = \Comments::find($id);
            $comment->status = 0;
            if($comment->save()){

            }else{
                return \Response::json(array('type' => 'danger', 'text' => t('messages.error_occured')));
            }
        }
        return \Response::json(array('type' => 'success', 'text' => t('messages.comments_disapproved')));
    }

    public function bulkdelete(){
        $ids = requested('ids');
        foreach($ids as $id){
            $id = (int) $id;
            $comment = \Comments::find($id);
            if($comment->delete()){}else{
                return \Response::json(array('type' => 'danger', 'text' => t('messages.error_occured')));
            }
        }
        return \Response::json(array('type' => 'success', 'text' => t('messages.comments_deleted')));
    }
}