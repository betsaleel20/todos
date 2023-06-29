<?php
require_once ("../users/User.php");
require_once ("../users/AuthUser.php");
require_once ("../users/StoreUser.php");

require_once ("../tasks/Tasks.php");
require_once ("../tasks/TaskPersistence.php");
require_once ("../tasks/TaskFieldsValidation.php");

class TestTodos{
    public function __construct()
    {
    }

    public function getLoggedUserId( User $user ):?string
    {
        if($user->getIsLogged()){
            return $user->getUserId();
        }
        return null;
    }
}

$userStorer = new StoreUser();
$authUser = new AuthUser();


$user1 = User::createUser(
    'Jude',
    'prenom',
    'ju@gmail.com',
    456,
    'crina@2023'
);
$user2 = User::createUser(
    'Be',
    'Different',
    'bedifferent@gmail.com',
    789,
    'imDifferent#2@23'
);

$userStorer->storeUserInMemory($user1);
$userStorer->storeUserInMemory($user2);

$authUser->logUserInMemory("bedifferent@gmail.com","imDifferent#2@23");

$user2->displayUser();

