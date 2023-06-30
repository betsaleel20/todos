<?php
    require_once("TaskStatus.php");
    require_once ("TaskFieldsValidation.php");
    class Task
    {
        private string $_taskId;
        private string $_title;
        private ?string $_description = null;
        private TaskStatus $_status = TaskStatus::PENDING;
        private string $_userId;
        private ?string $_parentId = null;
        private bool $_isDeleted = false;

        private function __construct(string $taskId, string $title, string $userId, ?string $parentId )
        {
            $this->_taskId = $taskId;
            $this->_title  = $title;
            $this->_userId = $userId;
            $this->_parentId = $parentId;
        }

        public static function createTask(
            string $title,
            ?string $userId,
            string $description = '',
            ?string $parentId = null
        ): Task
        {
            $task = new self(uniqid(), $title, $userId, $parentId);
            $task->setDescription($description);

            return $task;
        }

        public function setIsDeleted(bool $is_deleted): void{ $this->_isDeleted = $is_deleted; }
        public function setDescription(?string $description): void{ $this->_description = $description; }
        public function setStatus(TaskStatus $status): void{ $this->_status = $status; }
        public function setParentId(string $parentId):void  { $this->_parentId = $parentId ; }

        public function changeTaskStatusToFinish():void{
            $this->setStatus(TaskStatus::FINISHED);
        }

        public function getTaskId(): string { return $this->_taskId; }
        public function getTitle(): string { return $this->_title; }
        public function getDescription(): ?string { return $this->_description; }
        public function getStatus(): TaskStatus { return $this->_status; }
        public function getUserId(): string { return $this->_userId; }
        public function getParentId(): ?string { return $this->_parentId; }
        public function getIsDeleted(): bool { return $this->_isDeleted; }

        public function displayTask() : void {
//            if($this->getStatus() == Status::FINISHED){
//                $value = "Terminée";
//            }
//            if ($this->getStatus() == Status::PENDING){
//                $value = "En cours";
//            }
//            echo("identifiant: ".$this->getTaskId(). "</br> " );
//            echo("Titre: ".$this->getTitle(). "</br> " );
//            echo("Description: ".$this->getDescription(). "</br> " );
//            echo("Etat: ".$value. "</br> " );
//            echo("user ID: ".$this->getUserId(). "</br> " );
//            echo("Parent ID: ".$this->getParentId(). "</br></br> " );
            var_dump($this);
        }
    }
