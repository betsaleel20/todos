<?php

    require_once ("Task.php");
    class Delete_task{

        public function __construct(){
            
        }
        
        /**
         * This function delete the for task the user. But, for us, it still exist in DB
         * @param Task $task
         * @return Task
         */
        public function delete_task(Task $task): Task
        {
            $task->set_is_deleted(true);
            return $task;
        }
    }