<?php
    interface TaskRepository{
        public function saveAllTasks(array $taskTab): void;
    }