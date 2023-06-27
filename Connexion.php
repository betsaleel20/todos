<?php

    class Connexion{

        protected $_id_con;
        protected string $sgbd_host_dbname ;
        protected string $user ;
        protected string $pwd ;
        protected array $pdo_options;

        protected function __construct(
                $sgbd_host_dbname = 'mysql:host=127.0.0.1;dbname=todos;charset=utf8',
                $user = 'root',
                $pwd = '',
                $pdo_options = array(
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                )
            )
        {
            $this->sgbd_host_dbname = $sgbd_host_dbname;
            $this->user = $user;
            $this->pwd = $pwd;
            $this->pdo_options = $pdo_options;
        }

        protected function connexionDB()
        {
            try{
                $this->_id_con = new PDO($this->sgbd_host_dbname, $this->user,$this->pwd,$this->pdo_options);
                
                return $this->_id_con;
            }
            catch(PDOException $error){
                echo("Erreur de connexion: ". $error->getMessage());
                die();
            }
            
            
        }
    }