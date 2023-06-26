<?php
    require_once ("User.php");
    require_once ("AuthUser.php");
    class StoreUser{

        private static array $_tabUsers = [];

        public function __construct(){}

        /**
         * @return array
         */
        public function getTabUsers(): array
        {
            return self::$_tabUsers;
        }

        /**
         * @param array $tabUsers
         */
        public static function setTabUsers(User $user): void
        {
            self::$_tabUsers[] = $user;
        }

        public function storeUserInMemory(User $user) : array {

            $this->setTabUsers($user);
            return $this->getTabUsers();
        }
    }

/**
 * Tests du code
 */
//$user = new User('jude', 'scribe');
$storer = new StoreUser();
$user = User::createUser('Albert', 'betsaleel', 'betsa@gmail.com', 1589632,'crina@2023' );
$user1 = User::createUser('Jude', 'prenom', 'ju@gmail.com', 456, 'crina@2023' );
$user2 = User::createUser('abc', 'def', 'ghi@gmail.com', 789,'crina@2023' );

$storedUsers = $storer->storeUserInMemory($user);
$storedUsers = $storer->storeUserInMemory($user1);
$storedUsers = $storer->storeUserInMemory($user2);

$total = count($storedUsers);
echo("</br></br>---- List of Stored users($total) ----</br>");
$i = 0;
foreach($storedUsers as $oneUser){
    echo("User: ".$i."</br> ");
    $oneUser->displayUser();
    echo("</br>");
    $i++;
}

$autUser = new AuthUser();
echo("---- Loged user ----</br>");
$retrievedUser =$autUser->logUserInMemory("betsa@gmail.com", "crina@2023");
if(is_null($retrievedUser)){
    echo("Valeur de retour Nulle");
} else{
    $retrievedUser->displayUser();
}