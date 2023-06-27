<?php
    require_once ("Tasks.php");
    class  TaskPersistence{
        private static array $_tabTasks = [];

        public function __construct()
        {
        }

        public function setTabTasks(Task $task, $indice = ''):void  {
            if($indice ==='' || $indice > count(self::$_tabTasks)){
                self::$_tabTasks[] = $task ;
            }
            if(count(self::$_tabTasks) <= $indice){
                self::$_tabTasks[$indice] = $task ;
            }
        }

        public function getTabTasks(): array{ return self::$_tabTasks; }

        public function storeTask( $task, $theIndice = '') : void {
            $this->setTabTasks($task, $theIndice);
        }

        public static function getAllTasksIdsFromMemory():array
        {
            $ids = [];
            foreach(self::$_tabTasks as $oneTask) {
                $ids[] = $oneTask->getTaskId();
            }
            return $ids;
        }

        public function isValidParentId(?string $parentId):bool
        {
            $tabOfIds = $this->getAllTasksIdsFromMemory();
            return in_array($parentId, $tabOfIds);
        }

        public function isNotAParent(string $taskId):bool{
            $task = $this->findOneTaskInMemory($taskId);
            if( $task instanceof Task ){
                if($task->getParentId() != null ){
                    return false;
                }
            }
            return true;
        }

        public function setParent(Task $task, string $theParentId): void
        {
            $parentId = $task->getParentId();
            if($parentId != ''){
                if($this->isNotAParent($parentId)) {
                    $task->setParentId($parentId);
                }
                else{
                    echo ("Id parent invalide");
                }

                // Sinon, On crée directement une tache et non plus une sous-tache
            }
            $task->setParentId($theParentId);
        }

        public function findOneTaskInMemory(string $taskId) :bool|Task {

            $task = array_filter(self::$_tabTasks, fn(Task $oneTask)=>$oneTask->getTaskId()===$taskId);
            if(!empty($task)) {
                return $task;
            }
            return false;
        }

        public function existingTask(Task $task):int|bool{

            $task_id = $task->getTaskId();
            if(count(self::$_tabTasks) > 0){

                foreach(self::$_tabTasks as $key => $one_task){
                    if($one_task->getTaskId() === $task_id){
                        return $key;
                    }
                }
            }

            return false;
        }

        public function haveSubTasks(string $taskId): array{

            $tasks_tab = $this->getTabTasks();
            $valid_id = $this->isValidParentId($taskId);

            if($valid_id && !empty($tasks_tab)){
                return array_filter($tasks_tab, fn(Task $oneTask)=>$oneTask->getParentId()=== $taskId) ;
//                var_dump($hisSubTasks);
            }
            return [];
        }

        public function finishedTasksInMemory(Task $task) : bool {

            $task_id = $task->getTaskId();
            $is_an_parent_task = $this->haveSubTasks($task_id);

            $indice = $this->existingTask($task);

            if( is_bool($is_an_parent_task) ){

                $task->setStatus(true);
                $task->setDescription("Tache terminée");

                $task->storeTask($indice);

                // If all sub-task are finished, if auto_finish the parent
                if($task->is_all_sub_tasks_finished($task)){
                    $this->setStatus(true);
                }
                return true;
            }

            $task->setStatus(true);

            foreach ($is_an_parent_task as $one_task){
                $one_task->setStatus(true);
                $this->storeTask( $one_task,$indice);
            }

            return true;
        }

        public function is_all_sub_tasks_finished(Task $task) : bool {

            $parent_id = $this->getParentId();
            $all_sub_tasks = $this->haveSubTasks($parent_id);

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

    }

    $storer = new TaskPersistence();
    $task = Task::createTask(
        "Titre depuis store",
        "10",
        "Une description"
    );

    $parentId = $task->getTaskId();
    $task1 = Task::createTask(
        "Titre task01 depuis store",
        "10",
        "Une description"
    );
    $storer->setParent($task1, $parentId);

    $task2 = Task::createTask(
        "Titre task02 depuis store",
        "10",
        "Une description"
    );

    $task3 = Task::createTask(
        "Titre task03 depuis store",
        "10",
        "Une description"
    );

    $storer->storeTask($task);
    $storer->storeTask($task1);
    $storer->storeTask($task2);
    $storer->storeTask($task3);

    $tasksTab = $storer->getTabTasks();
    $storer->finishedTasksInMemory($task);
    echo("Taches en memoires (".count($tasksTab).")</br></br>");

    foreach ($tasksTab as $oneTask)
        $oneTask->display_task();