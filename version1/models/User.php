<?php

require_once('../Connexion.php');

class User extends Connexion
{
    private static array $_tab_users = [];
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
    public function set_tab_users($var):void { self::$_tab_users[] = $var; }


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
    public function get_tab_users(): array { return self::$_tab_users; }

    
    /**
     * Function to add one user
     * @param  string $first_name, $last_name, $email, $pwd, 
     * @param int $tel
     * @return User
     */
    public function create_user($first_name, $last_name, $email, $pwd, $tel): User
    {
        $user = new User($first_name, $last_name);

        $iden = uniqid();
        $user->set_id($iden);
        $user->set_email($email);
        
        $hashed_pwd = hash('md5', $pwd);

        $user->set_password($hashed_pwd);
        $user->set_telephone($tel);

        return $user;

    }

    /**
     * @return Array
     */
    public function store_user_in_memory(User $user) : array {
        
        $this->set_tab_users($user);
        return $this->get_tab_users();
    }

    /**
     * @return User
     */
    public function array_to_user(array $db_result) : User {

        $user = new User($db_result['first_name'], $db_result['last_name']);

        $user->set_id($db_result['id']);
        $user->set_email($db_result['email']);
        $user->set_password($db_result['pass_word']);
        $user->set_telephone($db_result['telephone']);
        $user->set_is_logged($db_result['is_logged_in']);

        return $user;
    }

    /**
     * Login an user
     */
    public function log_user_in_db($email, $password) {

        if ($email === '' || $password === ''){
            Echo('Email ou Mot de passe invalide');
            die();
        }

        $hashed_pwd = hash('md5', $password); 

        $db_con = new Connexion();
        $db_con = $db_con->connexionDB();

        $request = $db_con->query("SELECT * FROM users WHERE email = '$email' AND pass_word = '$hashed_pwd' LIMIT 1");
       
        $result = $request->fetch(PDO::FETCH_ASSOC);
        if(count($result) > 0){

            $user = $this->array_to_user($result);
            $user = $user->set_is_logged(true);
            
            return  $user;
        }
        return null;
    }

    /**
     * @return Null|USER
     */
    public function log_user_in_memory($email, $password){
        
        if(count(self::$_tab_users)>0){
            
            foreach(self::$_tab_users as $key => $one_user){
                
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

    public function display_user() : void {

        if($this->get_is_logged()){
            $yes_or_no = "Yes";
        }
        else{
            $yes_or_no = "No";
        }
        echo("identifiant: ".$this->get_id(). "</br> " );
        echo("Nom: ".$this->get_first_name(). "</br> " );
        echo("Prenom: ".$this->get_last_name(). "</br> " );
        echo("Email: ".$this->get_email(). "</br> " );
        echo("Mot de passe: ".$this->get_password(). "</br> " );
        echo("Telephone: ".$this->get_telephone(). "</br> " );
        echo("Est logé ?: ".$yes_or_no. "</br> " );
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


/**
 * Tests du code
 */
$user = new User('jude', 'scribe');

$user = $user->create_user('Albert', 'betsaleel', 'betsa@gmail.com', 'pwd145', 1589632);
$user1 = $user->create_user('Jude', 'prenom', 'ju@gmail.com', 'pwd_405', 456);
$user2 = $user->create_user('abc', 'def', 'ghi@gmail.com', 'pwdjkm', 789);

$stored_users = $user->store_user_in_memory($user);
$stored_users = $user->store_user_in_memory($user1);
$stored_users = $user->store_user_in_memory($user2);
echo(" Premier user </br>");
$user->display_user();
echo("</br></br> Deuxieme user >");
$user1->display_user();
echo("</br></br> Troisiem user >");
$user2->display_user();

$total = count($stored_users);
echo("</br></br>---- List of Stored users($total) ----</br>");
$i = 0;
foreach($stored_users as $user){
    echo("User: ".$i."</br> ");
    $user->display_user();
    echo("</br>");
    $i++;
}

echo("---- Loged user ----</br>");
$retreived_user = $user->log_user_in_memory("betsa@gmail.com", "7a1ad5caf248e4d5b119ab4b94484cb0"); 
if(is_null($retreived_user)){
    echo("Valeur de retour Nulle");
} else{
    $retreived_user->display_user();
} 