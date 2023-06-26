<?php

require_once('../Connexion.php');
require_once("../interfaces/Display_interface.php");

class User extends Connexion implements Display_interface
{
    private $_id;
    private $_first_name;
    private $_last_name;
    private $_email;
    private $_password;
    private $_telephone;
    private $_is_logged;



    public function __construct($first_name, $last_name, $is_logged = false)
    {
        $this->set_first_name($first_name);
        $this->set_last_name($last_name);
        $this->set_is_logged($is_logged);
    }

    /**
     * Setters
     */
    public function set_id($var):void { $this->_id = $var; }
    public function set_first_name($var):void { $this->_first_name = $var; }
    public function set_last_name($var):void { $this->_last_name = $var; }
    public function set_email($var):void { $this->_email = $var; }
    public function set_password($var):void { $this->_password = $var; }
    public function set_telephone($var):void { $this->_telephone = $var; }
    public function set_is_logged($var):void { $this->_is_logged = $var; }


    /**
     * Getters
     */
    public function get_id(): string { return $this->_first_name;}
    public function get_first_name(): string { return $this->_first_name;}
    public function get_last_name(): string { return $this->_last_name;}
    public function get_email(): string { return $this->_email;}
    public function get_password(): string { return $this->_password;}
    public function get_telephone(): int { return $this->_telephone;}
    public function get_is_logged(): bool { return $this->_is_logged;}


    /**
     * @param  string $first_name, $last_name, $email, $pwd, 
     * @param int $tel
     * @return User
     */
    public function create_user(string $first_name, string $last_name, string $email, string $pwd, int $tel): User
    {
        $user = new User($first_name, $last_name);

        $iden = uniqid();
        $user->set_id($iden);
        $user->set_email($email);

        $user->set_password($pwd);
        $user->set_telephone($tel);

        return $user;

    }
    
    public function boolean_to_string(bool $the_boolean) : string
    {
        if($the_boolean){
            return "Yes";
        }
        return "No";
    }

    /**
     * @return User
     */
    public function array_to_user(array $db_query_result) : ?User {

        if(is_null($db_query_result)){
            return null;
        }

        $user = new User($db_query_result['first_name'], $db_query_result['last_name']);

        $user->set_id($db_query_result['id']);
        $user->set_email($db_query_result['email']);
        $user->set_password($db_query_result['pass_word']);
        $user->set_telephone($db_query_result['telephone']);
        $user->set_is_logged($db_query_result['is_logged_in']);

        return $user;
    }

    public function display_object() : void {
        echo("identifiant: ".$this->get_id(). "</br> " );
        echo("Nom: ".$this->get_first_name(). "</br> " );
        echo("Prenom: ".$this->get_last_name(). "</br> " );
        echo("Email: ".$this->get_email(). "</br> " );
        echo("Mot de passe: ".$this->get_password(). "</br> " );
        echo("Telephone: ".$this->get_telephone(). "</br> " );
        echo("Est logé ?: ".$this->boolean_to_string($this->get_is_logged()) . "</br> " );
    }

    /**
     * Validation de l'adresse email
     */
    private static function validate_email(string $email): void
    {
        $email = filter_var($email,FILTER_SANITIZE_EMAIL);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)){
            echo("adresse email invalide");
            die();
        }
    }

    /**
     * Verification de mot de passe
     */
    private static function validate_password(string $password): void
    {
        $regex = "/^(?=.*[a-z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&-]{8,}$/";
        if (!preg_match($regex, $password, $matches)) {
            echo("Mot de passe invalide");
            die();
        }
    }
}


