<?php
    require_once ("Tasks.php");
    require_once ("TaskRepository.php");
    class  TaskPersistence implements TaskRepository {
        private static array $_tabTasks = [];

        public function __construct()
        {
        }

        public function setTabTasks( Task $task, $indice ):void  {
            $tabSize = count(self::$_tabTasks);

            if($indice === -1 || $indice >= $tabSize) {
                $this->addTaskInQueue($task);
                return;
            }
            $this->addTaskAtPosition( $task, $indice );

        }

        public function getTabTasks(): array{ return self::$_tabTasks; }

        /**
         * @param mixed $indice
         * @param int $tabSize
         * @param Task $task
         * @return void
         */
        public function addTaskInQueue( Task $task ): void
        {
            self::$_tabTasks[] = $task;
        }

        /**
         * @param int $tabSize
         * @param mixed $indice
         * @param Task $task
         * @return void
         */
        public function addTaskAtPosition( Task $task, int $indice ): void
        {
            self::$_tabTasks[$indice] = $task;
        }

        public function saveTaskInMemory($task, int $indice = -1) : void {
            $this->setTabTasks($task, $indice);
        }

        public static function getAllTasksIdsFromMemory():array
        {
            return array_map(fn($oneTask)=>$oneTask->getTaskId(), self::$_tabTasks);
        }

        public function isValidParentId(?string $parentId):bool
        {
            return in_array($parentId, $this->getAllTasksIdsFromMemory());
        }



        public function existingTask(Task $task): int {
            $taskId = $task->getTaskId();
            $theKey = -1;
            if(!empty(self::$_tabTasks)){
                $foundTask = array_filter(self::$_tabTasks, fn(Task $oneTask)=>$oneTask->getTaskId() === $taskId );
                empty($foundTask) ? : $theKey = key($foundTask);
            }
            return $theKey;
        }

        public function getSubTasks(string $taskId): array{

            $tasksTab = $this->getTabTasks();
            $validParentId = $this->isValidParentId($taskId);

            if($validParentId){
                return array_filter($tasksTab, fn(Task $oneTask)=>$oneTask->getParentId() === $taskId);
            }
            return [];
        }

        public function markTaskAsFinished(Task $task) : void {

            $taskId = $task->getTaskId();
            $subTasks = $this->getSubTasks($taskId);

            $indice = $this->existingTask($task);

            $task->changeTaskStatusToFinish();
            $this->saveTaskInMemory($task, $indice);

            if( !empty($subTasks) ){
                foreach ($subTasks as $oneTask){
                    $oneTask->changeTaskStatusToFinish();
                }
                $this->saveAllTasks($subTasks);
            }
        }


        public function saveAllTasks(array $tasksTab):void{
            foreach ($tasksTab as $key=>$oneTask) {
                self::$_tabTasks[$key] = $oneTask;
            }
        }

    }