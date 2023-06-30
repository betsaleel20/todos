<?php
    require_once ("Tasks.php");
    require_once ("TaskPersistence.php");
    class TaskFieldsValidation{

        private TaskPersistence $persistence;

        public function __construct()
        {
            $this->persistence = new TaskPersistence() ;
        }

        public function validateParentId(string $theParentId): ?string
        {
            $isValid = in_array($theParentId, $this->persistence::getAllTasksIdsFromMemory());
            if($isValid){
                if($this->isNotAChildTask($theParentId)){
                    return $theParentId;
                }
            }
            return null;
        }

        public function isNotAChildTask($taskId): bool
        {
            $task = $this->findOneTaskInMemory($taskId);
            $hisParentId = $task->getParentId();
            if( is_null($hisParentId)){
                return true;
            }
            return false;
        }

        public function findOneTaskInMemory(string $taskId) :?Task {

            $task = array_values(array_filter($this->persistence->getTabTasks(), fn(Task $oneTask)=>$oneTask->getTaskId() === $taskId));
            if(!empty($task)) {
                return $task[0];
            }
            return null;
        }


    }

//    Tests

$fieldValidator = new TaskFieldsValidation();
$storer = new TaskPersistence();

$task1 = Task::createTask(
    "La toute premiere tache: Task 1",
    "10",
    "Une description pour Task1",
);
$storer->saveTaskInMemory($task1);

$parentId = $task1->getTaskId();
$validarentId = $fieldValidator->validateParentId($parentId);
$task2 = Task::createTask(
    "Titre task02 depuis Task fields validation",
    "10",
    "Une description pour Task2",
    "$validarentId"
);
$storer->saveTaskInMemory($task2);


//$parentId = $task2->getTaskId();
//$validarentId = $fieldValidator->validateParentId($parentId);
$task3 = Task::createTask(
    "La tache 03",
    "10",
    "Une description pour Task 3",
    "$validarentId"
);
$storer->saveTaskInMemory($task3);

$task4 = Task::createTask(
    "Titre task03 depuis store",
    "10",
    "une description pour Task 4",
);
$storer->saveTaskInMemory($task4);

$storer->markTaskAsFinished($task3);
$tasksTab = $storer->getTabTasks();
echo("Taches en memoires (".count($tasksTab).")</br></br>");

foreach ($tasksTab as $oneTask)
    $oneTask->displayTask();