<?php

require_once("../../Connexion.php");

class User
{
    private string $_id;
    private string $_firstName;
    private string $_lastName;
    private string $_email;
    private string $_password;
    private string $_telephone;
    private bool $_isLogged = false;

    private function __construct(
        string $userId,
        string $firstName,
        string $lastName,
        string $email,
        string $telephone,
        string $password
    ){
        $this->setId($userId);
        $this->setFirstName($firstName);
        $this->setLastName($lastName);
        $this->setEmail($email);
        $this->setTelephone($telephone);
        $this->setPassword($password);
    }

    
    /**
     * @param string $firstName
     * @param string $lastName
     * @param string $email
     * @param string $password
     * @param string $telephone
     * @return User
     */
    public static function createUser(
        string $firstName,
        string $lastName,
        string $email,
        string $telephone,
        string $password
    ): User
    {
        $userId = uniqid();
        return new self($userId,$firstName, $lastName, $email, $telephone, $password);
    }

    /**
     * @param string $password
     * @return string
     */
    public static function hashPassword(string $password): string
    {
        return hash('md5', $password);

    }

    public function setId(string $userId):void { $this->_id = $userId; }
    public function setFirstName(string $firstName):void { $this->_firstName = $firstName; }
    public function setLastName(string $lastName):void { $this->_lastName = $lastName; }
    public function setEmail(string $email):void
    {
        self::validateEmail($email);
        $this->_email = $email;
    }
    public function setPassword(string $password):void
    {
        self::validatePassword($password);
        $password = self::hashPassword($password);
        $this->_password = $password;
    }
    public function setTelephone(string $telephone):void { $this->_telephone = $telephone; }
    public function setIsLogged(bool $isLogged):void { $this->_isLogged = $isLogged; }

    public function getUserId(): string { return $this->_id;}
    public function getFirstName(): string { return $this->_firstName;}
    public function getLastName(): string { return $this->_lastName;}
    public function getEmail(): string { return $this->_email; }
    public function getPassword(): string { return $this->_password; }
    public function getTelephone(): string{ return $this->_telephone; }
    public function getIsLogged(): bool { return $this->_isLogged; }


    public function displayUser() : void {
//        $yes_or_no = "No";
//        if($this->getIsLogged())
//        {
//            $yes_or_no = "Yes";
//        }
//
//        echo("identifiant: ".$this->getUserId(). "</br> " );
//        echo("Nom: ".$this->getFirstName(). "</br> " );
//        echo("Prenom: ".$this->getLastName(). "</br> " );
//        echo("Email: ".$this->getEmail(). "</br> " );
//        echo("Mot de passe: ".$this->getPassword(). "</br> " );
//        echo("Telephone: ".$this->getTelephone(). "</br> " );
//        echo("Est logé ?: ".$yes_or_no. "</br> " );
        var_dump($this);
    }


    private static function validateEmail(string $email): void
    {
        $email = filter_var($email,FILTER_SANITIZE_EMAIL);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)){
            echo("adresse email invalide");
            die();
        }
    }


    private static function validatePassword(string $password): void
    {

        $regex = "/^(?=.*[a-z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&-]{8,}$/";
        if (preg_match($regex, $password) != 1 ) {
            var_dump( $password);
//            echo("Mot de passe invalide</br>");
//            die();
        }
    }
}
