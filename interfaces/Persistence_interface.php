<?php

    interface Persistence_interface{

        public function store_user_or_task_in_memory($user_or_task, $indice = '');

    }