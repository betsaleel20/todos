<?php

    require_once("../Connexion.php");
    require_once("User.php");
    require_once("Store_user.php");

    class Log_in_user extends Connexion{

        private $_telephone;
        private $_email;
        private $_password;


        public function __construct(string $pwd)
        {
            $this->set_password($pwd);
        }

        public function set_telephone( $tel ) : void { $this->_telephone = $tel; }
        public function set_email( $mail ) : void { $this->_telephone = $mail; }
        public function set_password( $pwd ) : void { $this->_password = $pwd; }

        public function get_telephone() : int { return $this->_telephone; }
        public function get_email() : string { return $this->_email; }
        public function get_password() : string { return $this->_password; }


        /**
         * Login an user
         */
        public function log_user_in_db($email, $password) {

            if ( empty($email) || empty($password)){
                Echo('Email ou Mot de passe invalide');
                die();
            }

            $hashed_pwd = $password;
            // $hashed_pwd = $this->hash_passwords_or_die( 'md5', $password );

            $db_con = new Connexion();
            $db_con = $db_con->connexionDB();

            $request = $db_con->query("SELECT * FROM users WHERE email = '$email' AND pass_word = '$hashed_pwd' LIMIT 1");
        
            $result = $request->fetch(PDO::FETCH_ASSOC);

            if( !is_bool($result )){
                $empty_user = new User('','');
                $user = $empty_user->array_to_user($result);
                $user->set_is_logged(true);
                
                return  $user;
            }
            return null;
        }

        /**
         * @return Null|USER
         */
        public function log_user_in_memory($email, $password){
            
            $user_storer = new Store_user();
            $stored_users = $user_storer->get_tab_users();
            
            if(count($stored_users) > 0 ){

                foreach($stored_users as $key => $one_user){
                    
                    if($one_user->get_email() === $email && $one_user->get_password() === $password){
                        $one_user->set_is_logged(true);
                        return $one_user;
                    }
                }
                echo("incorrect email or Password. </br>");
                return null;
            }
            echo("The table of user is empty. </br>");
            return null;
        }

    }