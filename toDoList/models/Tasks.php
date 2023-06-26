<?php

    require_once ('../Connexion.php');
    class Task extends Connexion
    {
        private static array $_tab_tasks = [];
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
        public function set_tab_tasks(Task $task, $indice = ''):void  {
            if(is_null($indice) || !$indice){
                self::$_tab_tasks[] = $task ; 
            }
            else{
                self::$_tab_tasks[$indice] = $task ;
            }
        }

        /** Getters */
        public function get_task_id(): ?string { return $this->_task_id; }
        public function get_title(): string { return $this->_title; }
        public function get_description(): ?string { return $this->_description; }
        public function get_status(): bool { return $this->_status; }
        public function get_user_id(): string { return $this->_user_id; }
        public function get_parent_id(): ?string { return $this->_parent_id; }
        public function get_is_deleted(): bool { return $this->_is_deleted; }
        public function get_tab_tasks(): array{ return self::$_tab_tasks;}

        /**
         * Get all tasks IDs
         * @return Array $ids
         */
        public static function get_all_tasks_ids_from_db():array
        {
            $ids = [];

            $database = new Connexion();

            $db_con = $database->connexionDB();
            $request = $db_con->query("SELECT id FROM tasks WHERE is_deleted = 0");
          
            while($occurence = $request->fetch(PDO::FETCH_ASSOC)){
                $ids[] = $occurence['id'];
            }
            return $ids;
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
        public function is_valid_parent_id(string $parent_id):bool
        {
            $tab_of_ids = $this->get_all_tasks_ids_from_memory();
            return in_array($parent_id, $tab_of_ids);
        }

        public function find_one_task_in_memory(string $task_id) : bool|Task {
            $all_task = self::$_tab_tasks;
            foreach($all_task as $task){
                if($task->get_task_id() === $task_id){
                    return $task;
                }
            }
            return false;
        }

        public function is_not_a_parent(string $task_id):bool{

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
        public function is_parent_id_not_null($parent_id): void
        {
            if(!is_null($parent_id)){

                if($this->is_valid_parent_id($parent_id) && $this->is_not_a_parent($parent_id)) {
                    $this->set_parent_id($parent_id);
                }

                else{
                    // On crée directement une tache et non plus une sous-tache
                }
            }
        }

        /**
         * Function that create a task without saving it in DB
         * @param string $parentId, $userId, $description
         * @param string $parentId = null default NULL
         * @return Task
         */

        public static function create_task(string $id = '', string $title, string $user_id, string $description, string $parent_id = null): Task
        {
            $task = new Task($title, $user_id);
            if(is_null($id)){
                $task->set_task_id(uniqid());
            }
            else{
                $task->set_task_id($id);
            }
            $task->set_description($description);
            $task->is_parent_id_not_null($parent_id);
            
            return $task;
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
         * @param Task $task 
         */
        public function store_task(Task $task, $indice = '') : array {

            $this->set_tab_tasks($task, $indice);
            
            return $this->get_tab_tasks();
        }

        /**
         * @return array|boolean
         */
        public function have_sub_tasks(string $task_id): bool|array{

            $tasks_tab = $this->get_tab_tasks();
            $valid_id = $this->is_valid_parent_id($task_id);

            if($valid_id && !empty($tasks_tab)){
                foreach($tasks_tab as $one_task){
                    if($one_task->get_parent_id() === $task_id){
                        $his_sub_tasks[] = $one_task;
                        // echo($one_task->get_parent_id() . " est egal à ". $task_id);
                    }
                }
                if( !empty($his_sub_tasks) ){
                    return $his_sub_tasks;
                }  
            }
            return false;
        }

        public function finish_tasks_in_memory(Task $task) : bool {

            $task_id = $task->get_task_id();
            $is_an_parent_task = $this->have_sub_tasks($task_id);

            $indice = $task->existing_task($task);

            if( is_bool($is_an_parent_task) ){

                $task->set_status(true);
                $task->set_description("Tache terminée");

                $task->store_task($task, $indice);

                // If all sub-task are finished, if auto_finish the parent
                if($task->is_all_sub_tasks_finished($task)){
                    $this->set_status(true);
                }
                return true;
            }

            $this->set_status(true);

            foreach ($is_an_parent_task as $one_task){
                $one_task->set_status(true);
                $one_task->store_task($task, $indice);
            }

            return true;
        }

        public function is_all_sub_tasks_finished(Task $task) : bool {

            $parent_id = $this->get_parent_id();
            $all_sub_tasks = $this->have_sub_tasks($parent_id);

            if( !is_bool($all_sub_tasks) ){
                foreach($all_sub_tasks as $sub_task){
                    if( !$sub_task->get_status()){
                        return false;
                    }
                }
                return true;
            }
            return false;
        }
        
        public function display_task() : void {
            
            echo("identifiant: ".$this->get_task_id(). "</br> " );
            echo("Titre: ".$this->get_title(). "</br> " );
            echo("Description: ".$this->get_description(). "</br> " );
            echo("Etat: ".$this->get_status(). "</br> " );
            echo("user ID: ".$this->get_user_id(). "</br> " );
            echo("Parent ID: ".$this->get_parent_id(). "</br> " );
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

        /**
         * Get the list of created tasks
         * @return array $tasks
         */
        public static function get_tasks_list()
        {

        }
    }

    $task = new Task('Ma super tâche', 2);
    $empty_task = new Task('', 0);
    
    $task = $task::create_task('1','Ma super tâche', 2, 'Ma description');
    $empty_task->store_task($task);

    $task1 = $task::create_task('2','Ma super deuxieme tâche', 1, 'Ma 2e description',1);
    $empty_task->store_task($task1);

    $task2 = $task::create_task('3','Ma super troisieme tâche', 5, 'Ma 3e desc', 1);
    $empty_task->store_task($task2);

    $task3 = $task::create_task('4','Ma super quatrieme tâche', 3, 'Ma 4e desc',3);
    $empty_task->store_task($task3);
    
    $task->finish_tasks_in_memory($task);
    // $task->finish_tasks_in_memory($task3);
    
    $taskList = $task->get_tab_tasks();
    $total = count($taskList);
    echo("Liste des taches($total) en Memoire</br></br>");
    foreach( $taskList as $task ){
        $task->display_task();
        echo("</br>");
    }

    echo("Liste des taches terminees </br></br>");
    
    // $task->display_task();
    // var_dump($task);