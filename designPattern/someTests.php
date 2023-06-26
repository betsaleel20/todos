<?php

    require_once("./interfaces/someInterface.php");

    abstract class Dialog implements TestInterface {

        private $someVariable;

        public function __construct()
        {
            $this->someVariable = 0;
        }


        public function createButton(): TestInterface {

            return $this->someFUnction();
        }

        abstract public function anAbstractFunctionShoulNotHaveBoddy() : TestInterface;
    }
