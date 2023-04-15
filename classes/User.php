<?php
include_once("bootstrap.php");

class User{
    private $email;
    private $password;
    private $biography;

    public function setEmail($email){
        if (empty($email)) {
            throw new Exception("Email cannot be empty.");
        }
        else{ 
        $this->email = $email;
        }
        
    }
    public function getEmail(){
        return $this->email;
    }

    public function setPassword($password){
        if(empty($password)){
            throw new Exception("Password cannot be empty.");
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
        $statement = $conn->prepare("INSERT INTO users (email, password) VALUES (:email, :password)");
        //binden
        $statement->bindValue(":email", $this->getEmail()); 
        $statement->bindValue(":password", $this->getPassword());
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
    
    public function sendMail(){
        $config = parse_ini_file('config/config.ini', true);
        $key = $config['keys']['SENDGRID_API_KEY'];
        //var_dump($key);
        
         require 'vendor/autoload.php';
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
    }


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
    /*Denkwijze profile edits weergeven ~ Sarah 
    getuserdetails
    select * users where email = $email
    bindvalue van email set email
    username ophalen met session */  
       
}