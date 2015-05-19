<?php

/**
 * Class TasksController
 */
class TasksController extends BackendbaseController {
    /**
     * @var string
     */
    protected $layout = 'layouts.backend.laradmin';

    /**
     * Show all tasks in a calendar view
     */
    public function alltasks(){
        $taskobjects = \Task::all();
        $tasks = array();

        foreach($taskobjects as $task){
            $event = \Calendar::event(
                $task->title, //event title
                $task->description, //event description
                $task->full_date, //full day event?
                $task->start_date, //start time (you can also use Carbon instead of DateTime)
                $task->end_date, //end time (you can also use Carbon instead of DateTime)
                $task->status,
                $task->id
            );
            $tasks[] = $event;
        }

        $this->layout->content = \View::make('backend/tasks/calendar')->withTasks($tasks);
    }

    /**
     * Delete specified task
     *
     * @param $taskid
     * @return mixed
     */
    public function deltask($taskid){
        $task = \Task::find($taskid);
        if($task->user_id==\Auth::id()){
            if(!$task->delete()){
                return \Redirect::back()->withMessage($this->notifyView(t('messages.error_occured'),'success'));
            }
            return \Redirect::back()->withMessage($this->notifyView(t('messages.task_deleted'),'success'));
        }
        return \Redirect::back()->withMessage($this->notifyView(t('messages.error_occured'),'danger'));

    }

    /**
     * Open/Close task
     *
     * @param $taskid
     * @return mixed
     */
    public function toggletask($taskid){
        $task = \Task::find($taskid);
        if($task->user_id==\Auth::id()){
            $task->status = ($task->status)?0:1;
            if(!$task->save()){
                return \Redirect::back()->withMessage($this->notifyView(t('messages.error_occured'),'success'));
            }
            return \Redirect::back()->withMessage($this->notifyView(t('messages.task_updated'),'success'));
        }
        return \Redirect::back()->withMessage($this->notifyView(t('messages.error_occured'),'danger'));

    }

    /**
     * Show the new task dialog
     *
     * @return mixed
     */
    public function newtask(){
        $users = \User::all();

        if (\Request::ajax()){
            return \View::make('backend/tasks/new')->withUsers($users);
        }else{
            $this->layout->content = \View::make('backend/tasks/new')->withUsers($users);
        }
    }

    /**
     * Add task and assign to user
     *
     * @return mixed
     */
    public function addtask(){
        $title = requested('title');
        $description = requested('description');
        $user_id = requested('user_id');
        $start = requested('start_date').' '.requested('start_time');
        $end = requested('end_date').' '.requested('end_time');

        $task = new \Task;
        $task->title = $title;
        $task->description = $description;
        $task->user_id = $user_id;
        $task->start_date = $start;
        $task->end_date = $end;
        $task->full_date = 0;
        $task->status = 0;

        if($task->save()){
            return \Redirect::back()->withMessage($this->notifyView(t('messages.task_created'),'success'));
        }
        return \Redirect::back()->withMessage($this->notifyView(t('messages.error_occured'),'danger'));
    }

}