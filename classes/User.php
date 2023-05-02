<?php
include_once("bootstrap.php");

class User{
    private $email;
    private $password;
    private $biography;

    public function setEmail($email){
        if(strpos($email, '@') === false || empty($email)){
            throw new Exception("Email is not valid.");
            return false;
        }
        else{ 
        $this->email = $email;
        }
        
    }
    public function getEmail(){
        return $this->email;
    }

    public function setPassword($password){
        if(strlen($password) <= 5 || empty($password)){
            throw new Exception("Password is not valid.");
            return false;
        }
        else{
        $options = [
            'cost' => 12,
            ];
        $this->password = password_hash($password, PASSWORD_DEFAULT, $options);
        }
    }
    public function getPassword(){
        return $this->password;
    }
    /*registratie*/
    public function save(){
        //get connection
        $conn = Db::getInstance();
        //prepare statement
        $statement = $conn->prepare("INSERT INTO users (email, password, is_admin) VALUES (:email, :password, :is_admin)");
        //binden
        $statement->bindValue(":email", $this->getEmail()); 
        $statement->bindValue(":password", $this->getPassword());
        $statement->bindValue(":is_admin", 0);
        //execute
        return $statement->execute(); 
        /* ~ code van Jaro uit eerste register.php
        latere deadline: zien of een account bij signup nog available is: 
        $query = $conn->prepare("SELECT COUNT(*) FROM users WHERE email = :email");
        $query->bindValue(":email", $email);
        $query->execute();
        $count = $query->fetchColumn();

        if ($count > 0) {
            echo "Error: This email already exists.";
        }*/
    }

    public static function getAll()
    {
        $conn = Db::getInstance();
        $statement = $conn->prepare("select * from users");
        $statement->execute();
        $users = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $users;
    }

    /*public function checkDoubleMail(){
        $conn = Db::getInstance();
        $statement = $conn->prepare("select count(*) from users where email = :email");
        $statement->bindValue(":email", $this->getEmail());
        $statement->execute();
        $count = $statement->fetchColumn();

        if ($count > 0) {
            echo "Error: This email already exists.";
        }
    }*/
    /*Email versturen*/
    /*public function sendMail(){
        $config = parse_ini_file('config/config.ini', true);
        $key = $config['keys']['SENDGRID_API_KEY'];
        //var_dump($key);
        
        $email = new \SendGrid\Mail\Mail();
        $email->setFrom("r0784273@student.thomasmore.be", "Fred Kroket");
        $email->setSubject("Sending with Twilio SendGrid is Fun");
        $email->addTo("yadina.moreira@gmail.com", "Yadina");
        $email->addContent("text/plain", "and easy to do anywhere, even with PHP");
        $email->addContent(
           "text/html", "<strong>and easy to do anywhere, even with PHP</strong>"
        );

        $options = array(
          'turn_off_ssl_verification' => true
        );

        $sendgrid = new \SendGrid($key, $options);
        try {
            $response = $sendgrid->send($email);
            print $response->statusCode() . "\n";
            print_r($response->headers());
            print $response->body() . "\n";
            echo "email sent!\n";

        } catch (Exception $e) {
            echo 'Caught exception: '. $e->getMessage() ."\n";
        }
    }*/

    public function setBiography($biography){
        $this->biography=$biography;
    }

    public function getBiography(){
        return $this->biography;
    }
    /*profiel updaten*/
    public function updateProfile(){
        $conn = Db::getInstance();
        $statement = $conn->prepare("UPDATE users SET biography = :biography WHERE email = :email");
        $statement->bindValue(":biography", $this->getBiography());
        $statement->bindValue(":email", $this->getEmail());
        $statement->execute();
    }

     public function getUserDetails(){
        $conn = Db::getInstance();
        $statement = $conn->prepare("select * from users WHERE email = :email");
        $statement->bindValue(":email", $this->getEmail());
        $statement->execute();
        $users = $statement->fetch(PDO::FETCH_ASSOC);
        return $users;
    }

    public function isAdmin(){
        $conn = Db::getInstance();
        $username = $_SESSION['username'];
        
        $statement = $conn->prepare("select * from users where is_admin = :is_admin and email = :email");
        $statement->bindValue(":is_admin", 1);
        $statement->bindValue(":email", $username); 
        $statement->execute();
        $admins = $statement->fetchAll(PDO::FETCH_ASSOC);
        //var_dump($admins);
        //als array leeg is is die persoon geen admin dus mag die niet opdeze pagina
        if (empty($admins)) {
            header("Location: dashboard.php");
        }
    }
}