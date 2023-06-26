<?php

    require_once ('../Connexion.php');
    require_once("../interfaces/Display_interface.php");

    class Task extends Connexion implements Display_interface
    {
        private ?string $_task_id = null;
        private string $_title ;
        private ?string $_description = null;
        private bool $_status = false;

        private string $_user_id;
        private ?string $_parent_id = null;
        private bool $_is_deleted = false;

        public function __construct( string $title, string $user_id, $status = false )
        {
            $this->_title   = $title;
            $this->_user_id = $user_id;
            $this->_status  = $status;
        }

       /** Setters */
        public function set_is_deleted(bool $is_deleted): void{ $this->_is_deleted = $is_deleted; }
        public function set_task_id(string $task_id): void{ $this->_task_id = $task_id; }
        public function set_title(string $title): void{ $this->_title = $title; }
        public function set_description(string $description): void{ $this->_description = $description; }
        public function set_status(bool $status): void{ $this->_status = $status; }
        public function set_user_id(string $user_id): void{ $this->_user_id = $user_id; }
        public function set_parent_id(string $parent_id):void  { $this->_parent_id = $parent_id ; }
        

        /** Getters */
        public function get_task_id(): ?string { return $this->_task_id; }
        public function get_title(): string { return $this->_title; }
        public function get_description(): ?string { return $this->_description; }
        public function get_status(): bool { return $this->_status; }
        public function get_user_id(): string { return $this->_user_id; }
        public function get_parent_id(): ?string { return $this->_parent_id; }
        public function get_is_deleted(): bool { return $this->_is_deleted; }


        /**
         * Function that create a task without saving it in DB
         * @param string $parentId, $userId, $description
         * @param string $parentId = null default NULL
         * @return array
         */

        public static function create_task(string $id = '', string $title, string $user_id, string $description, string $parent_id = null): array
        {
            $task = new Task($title, $user_id);
            if(is_null($id)){
                $task->set_task_id(uniqid());
            }
            else{
                $task->set_task_id($id);
            }
            $task->set_description($description);
            
            $pending_task[0] = $task;
            $pending_task[1] = $parent_id;
            return $pending_task;
        }

        public static function createTask(){

        }
        
        public function display_object() : void {
            
            echo("identifiant: ".$this->get_task_id(). "</br> " );
            echo("Titre: ".$this->get_title(). "</br> " );
            echo("Description: ".$this->get_description(). "</br> " );
            echo("Etat: ".$this->get_status(). "</br> " );
            echo("user ID: ".$this->get_user_id(). "</br> " );
            echo("Parent ID: ".$this->get_parent_id(). "</br> " );
        }

        /**
         * Get the list of created tasks
         * @return array $tasks
         */
        public static function get_tasks_list()
        {

        }
    }
    