<?php

    class Task
    {
        private string $_taskId;
        private string $_title;
        private ?string $_description = null;
        private bool $_status = false;
        private string $_userId;
        private ?string $_parentId = null;
        private bool $_isDeleted = false;

        private function __construct(string $taskId, string $title, string $userId )
        {
            $this->_taskId = $taskId;
            $this->_title  = $title;
            $this->_userId = $userId;
        }

        public static function createTask(
            string $title,
            string $userId,
            string $description,
        ): Task
        {
            $task = new self(uniqid(), $title, $userId);
            $task->setDescription($description);
//            $task->isParentIdNotNull($parentId);
            return $task;
        }

        public function setIsDeleted(bool $is_deleted): void{ $this->_isDeleted = $is_deleted; }
        public function setTaskId(string $taskId): void{ $this->_taskId = $taskId; }
        public function setTitle(string $title): void{ $this->_title = $title; }
        public function setDescription(string $description): void{ $this->_description = $description; }
        public function setStatus(bool $status): void{ $this->_status = $status; }
        public function set_user_id(string $userId): void{ $this->_userId = $userId; }
        public function setParentId(string $parentId):void  { $this->_parentId = $parentId ; }


        /** Getters */
        public function getTaskId(): string { return $this->_taskId; }
        public function getTitle(): string { return $this->_title; }
        public function getDescription(): ?string { return $this->_description; }
        public function getStatus(): bool { return $this->_status; }
        public function getUserId(): string { return $this->_userId; }
        public function getParentId(): ?string { return $this->_parentId; }
        public function getIsDeleted(): bool { return $this->_isDeleted; }
        
        public function display_task() : void {
            
            echo("identifiant: ".$this->getTaskId(). "</br> " );
            echo("Titre: ".$this->getTitle(). "</br> " );
            echo("Description: ".$this->getDescription(). "</br> " );
            echo("Etat: ".$this->getStatus(). "</br> " );
            echo("user ID: ".$this->getUserId(). "</br> " );
            echo("Parent ID: ".$this->getParentId(). "</br></br> " );
        }
    }


//    $task = Task::createTask('Ma super tâche', 2, 'Ma description');
//    $task->storeTask();
//
//    $parentId = $task->getTaskId();
//    $task1 = Task::createTask('Ma super deuxieme tâche', 1, 'Ma 2e description','1');
//    $task1->storeTask();
//
//    $task2 = Task::createTask('Ma super troisieme tâche', 5, 'Ma 3e desc', $task->getTaskId());
//    $task2->storeTask();
//
//    Task::createTask(
//        'Ma super quatrieme tâche',
//        3,
//        'Ma 4e desc',
//        $parentId
//    )->storeTask();
//
//    $task->finishedTasksInMemory($task);
//     $task2->finishedTasksInMemory($task2);
//
//    $taskList = $task->getTabTasks();
//    $total = count($taskList);
//    echo("Liste des taches($total) en Memoire</br></br>");
//    foreach( $taskList as $task ){
//        $task->display_task();
//        echo("</br>");
//    }
//
//    echo("Liste des taches terminees </br></br>");
//
//     $task->display_task();