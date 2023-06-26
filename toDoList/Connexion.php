<?php

    class Connexion{

        protected $_id_con;

        protected function __construct()
        {
            
        }

        protected function connexionDB()
        {
            try{
                $this->_id_con = new PDO('mysql:host=127.0.0.1;dbname=todos;charset=utf8','root','',array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
                
                return $this->_id_con;
            }
            catch(PDOException $error){
                echo("Erreur de connexion: ". $error->getMessage());
                die();
            }
            
            
        }
    }