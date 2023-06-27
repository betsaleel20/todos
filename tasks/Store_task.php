<?php

use Store_task as GlobalStore_task;

    require_once("Task.php");
    require("../interfaces/Persistence_interface.php");

    class Store_task extends Connexion implements Persistence_interface{
        private static array $_tab_tasks = [];


        public function __construct(){

        } 

        public function get_tab_tasks(): array{ return self::$_tab_tasks;}

        public function set_tab_tasks(Task $task, $indice = ''):void  {
            if(is_null($indice) || !$indice){
                self::$_tab_tasks[] = $task ; 
            }
            else{
                self::$_tab_tasks[$indice] = $task ;
            }
        }


        /**
         * Get all tasks IDs from memory
         * @return Array $ids
         */
        public static function get_all_tasks_ids_from_memory():array
        {
            $ids = [];
            $all_task = self::$_tab_tasks;
            foreach($all_task as $one_task){
                $ids[] = $one_task->get_task_id();
            }

            return $ids;
        }

        /**
         * Get all tasks IDs from memory
         * @return Array $ids
         */
        public static function get_all_sub_tasks_ids_from_memory():array
        {
            $ids = [];
            $all_task = self::$_tab_tasks;
            foreach($all_task as $one_task){
                if($one_task->get_parent_id() != ''){
                    $ids[] = $one_task->get_task_id();
                }
            }

            return $ids;
        }

        /**
         * @param string $parentId
         * @return boolean
         */
        public function is_valid_parent_id( $parent_id ):bool
        {
            $tab_of_ids = $this->get_all_tasks_ids_from_memory();
            return in_array($parent_id, $tab_of_ids);
        }

        /**
         * 
         */
        public function find_one_task_in_memory(string $task_id) : bool|Task {
            $all_task = self::$_tab_tasks;
            foreach($all_task as $task){
                if($task->get_task_id() === $task_id){
                    return $task;
                }
            }
            return false;
        }

        public function is_it_a_parent(string $task_id):bool{

            $task = $this->find_one_task_in_memory($task_id);
            if( $task instanceof Task ){
                if($task->get_parent_id() != '' ){
                    return false;
                }
            }
            return true;
        }

        /**
         * @param string $parent_id
         * @return void
         */
        public function is_parent_id_not_null($task, $parent_id): void
        {
            if(!is_null($parent_id)){

                $empty_task = new Task('',0);

                if($this->is_valid_parent_id($parent_id) && $this->is_it_a_parent($parent_id)) {
                    $task->set_parent_id($parent_id);
                }

                else{
                    // On crée directement une tache et non plus une sous-tache
                }
            }
        }

        /**
         * @return int|bool
         */
        public function existing_task(Task $task):int|bool{

            $task_id = $task->get_task_id();
            if(count(self::$_tab_tasks) > 0){
                
                foreach(self::$_tab_tasks as $key => $one_task){
                    if($one_task->get_task_id() === $task_id){
                        return $key;
                    }
                }
            }
            
            return false;
        }

        /**
         * @param array of size 2.
         * @return Task
         */
        public function set_rigth_parent_id( array $pending_task):Task{

            $task = $pending_task[0];
            $parent_id = $pending_task[1];

            $this->is_parent_id_not_null($task, $parent_id);

            return $task;
        }

        /**
         * @param Task $task
         * @return array
         */
        public function store_user_or_task_in_memory( $user_or_task, $indice = ''){
            $task = $user_or_task;

            $this->set_tab_tasks($task, $indice);
            
            return $this->get_tab_tasks();
        }

        /**
         * @return array
         */
        public function have_sub_tasks( $task_id ): array{
            $tasks_tab = $this->get_tab_tasks();
            $valid_id = $this->is_valid_parent_id($task_id);

            if(!$valid_id || empty($tasks_tab)){
                return [];
            }
            
            return array_filter($tasks_tab, fn($task)=>$task->get_parent_id() === $task_id);
  
        }

        

    }
   