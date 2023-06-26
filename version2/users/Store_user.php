<?php
    
    require_once("../Connexion.php");
    require_once("User.php");
    require("../interfaces/Persistence_interface.php");

    class Store_user extends Connexion implements Persistence_interface {

        private static array $_tab_users = [];

        public function __construct(){

        }
        
        public function set_tab_users($var):void { self::$_tab_users[] = $var; }

        public function get_tab_users(): array { return self::$_tab_users; }



        public function allow_algorithms() : array
        {
            return hash_algos();
        }

        public function is_valid_algo(string $algo) : bool 
        {
            $allow_algos = $this->allow_algorithms();
            if(in_array($algo, $allow_algos)){
                return true;
            }
            return false;
        }

        public function hash_passwords_or_die(string $algorithm, string $pwd) : string
        {
            if($this->is_valid_algo($algorithm)){
                $hashed_pwd = hash( $algorithm, $pwd );
                return $hashed_pwd;
            }
            echo("Cette algrithme de haschage n'est pas valide");
            die();
        }

        /**
         * @return Array
         */
        public function store_user_or_task_in_memory( $user_or_task, $not_used_in_this_method = ''){
            $user = $user_or_task;

            $hashed_pwd = $this->hash_passwords_or_die('md5', $user->get_password());
            $user->set_password($hashed_pwd);

            $this->set_tab_users($user);
            return $this->get_tab_users();
        }

        public function not_empty_users_tab() : bool {

            empty($this->get_tab_users())? $empty_or_not = false : $empty_or_not = true;
            return $empty_or_not;
        }

        /**
         * @return bool // This method will be written up later
         */
        public function store_user_in_db( User $user, $algo = 'md5' ) : bool {
            
            $hashed_pwd = $this->hash_passwords_or_die($algo, $user->get_password());
            $user->set_password($hashed_pwd);

            return true;
        }

    }

$user = new User('jude', 'scribe');
$user_storer = new Store_user();

$user = $user->create_user('Albert', 'betsaleel', 'betsa@gmail.com', 'pwd145', 1589632);
$user1 = $user->create_user('Jude', 'prenom', 'ju@gmail.com', 'pwd_405', 456);
$user2 = $user->create_user('abc', 'def', 'ghi@gmail.com', 'pwdjkm', 789);

$stored_users = $user_storer->store_user_or_task_in_memory($user);
$stored_users = $user_storer->store_user_or_task_in_memory($user1);
$stored_users = $user_storer->store_user_or_task_in_memory($user2);

// $user_storer->log_user_in_db('ju@ma1il.com', '123pwd');
