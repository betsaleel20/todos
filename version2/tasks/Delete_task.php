<?php

    class Delete_task{

        public function __construct(){
            
        }
        
        /**
         * This function delete the for the user. But, for us, it still exist in DB
         * @param Task $task
         * @return Task
         */
        public function delete_task(Task $task): Task
        {
            $task->set_is_deleted(true);
            return $task;
        }
    }