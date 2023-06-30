<?php
    class AuthUser{
        private StoreUser $storeUser;
        public function __construct()
        {
            $this->storeUser = new StoreUser();
        }

        /**
         * @return Null|USER
         */
        public function logUserInMemory($email, $password): ?User
        {
            $password = hash('md5', $password);
            $users = $this->storeUser->getTabUsers();

            if(!empty($users)){

                $foundUser = array_values(array_filter(
                    $users,
                    fn(User $oneUser)=>$oneUser->getEmail() === $email && $oneUser->getPassword() === $password ));

                if(!empty($foundUser)){
                    $foundUser[0]->setIsLogged(true);
                    return $foundUser[0];
                }
            }
            return null;
        }
    }
