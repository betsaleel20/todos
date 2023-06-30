<?php

    class Connexion{

        private $idConnexion = null;

        public function __construct()
        {
        }

        protected function connexionDB()
        {
            if(is_null($this->idConnexion)){
                try{
                    $this->idConnexion = new PDO(
                        'mysql:host=127.0.0.1;dbname=todos;charset=utf8',
                        'root',
                        '',
                        array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)
                    );
                    return $this->idConnexion;
                }
                catch(PDOException $error){
                    echo("Erreur de connexion: ". $error->getMessage());
                    die();
                }
            }
            return $this->idConnexion;

        }
    }