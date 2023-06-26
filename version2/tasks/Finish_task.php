<?php

    require_once("Store_task.php");

    class Finish_task{

        public function __construct(){
            
        }

        /**
         * @return bool
         */
        public function finish_tasks_in_memory(Task $task) : bool {

            $storer = new Store_task();
            
            $task_id = $task->get_task_id();
            $is_an_parent_task = $storer->have_sub_tasks($task_id);

            $indice = $storer->existing_task($task);

            if( is_bool($is_an_parent_task) ){

                $task->set_status(true);
                $task->set_description("Tache terminée");

                $storer->store_user_or_task_in_memory($task, $indice);

                // If all sub-task are finished, I auto_finish the parent
                if($this->is_all_sub_tasks_finished($task)){
                    $the_parent_id = $task->get_parent_id();
                    $parent_task = $storer->find_one_task_in_memory($the_parent_id);
                    $parent_task->set_status(true);
                }
                return true;
            }

            $task->set_status(true);
            $task->set_description("Tache terminée");
            foreach ($is_an_parent_task as $one_task){
                $one_task->set_status(true);
                $one_task->set_description("Tache terminée");
                $storer->store_user_or_task_in_memory($task, $indice);
            }

            return true;
        }

        public function is_all_sub_tasks_finished(Task $task) : bool {

            $storer = new Store_task();

            $parent_id = $task->get_parent_id();
            $all_sub_tasks = $storer->have_sub_tasks($parent_id);

            if(empty($all_sub_tasks)){
                return false;
            }

            $tasks = array_filter($all_sub_tasks, fn($task)=> ! $task->get_status());
            if(!empty($tasks)){
                return false;
            }

            return true;
        }
    }

$empty_task = new Task('',2);
    Task::createTask();
    $storer = new Store_task();
    $finish = new Finish_task();

    $pending_task = $empty_task->create_task(1,"Ma premiere tache", 3, "Ma superbe descriptioin");
    $task = $storer->set_rigth_parent_id($pending_task);
    $task_list = $storer->store_user_or_task_in_memory($task);

    $pending_task1 = $empty_task->create_task('02',"Ma deuxieme tache", 3, "Ma superbe descriptioin", 1);
    $task1 = $storer->set_rigth_parent_id($pending_task1);
    $task_list = $storer->store_user_or_task_in_memory($task1);

    $pending_task2 = $empty_task->create_task('03',"Ma troisieme tache", 3, "Ma superbe troisieme descriptioin", 1);
    $task2 = $storer->set_rigth_parent_id($pending_task2);
    $task_list = $storer->store_user_or_task_in_memory($task2);

    $pending_task3 = $empty_task->create_task('04',"Ma quatrieme tache", 3, "Ma superbe quatrieme descriptioin");
    $task3 = $storer->set_rigth_parent_id($pending_task3);
    $task_list = $storer->store_user_or_task_in_memory($task3);

    $finish->finish_tasks_in_memory($task1);
    // $finish->finish_tasks_in_memory($task1);

    foreach($task_list  as $task){
        $task->display_object();
        echo( "</br>" );
    }