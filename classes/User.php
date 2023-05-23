<?php
//include_once("bootstrap.php");

class User{
    private $email;
    private $password;
    private $biography;
    public function blockUser() {
        $conn = Db::getInstance();
        $statement = $conn->prepare("UPDATE users SET is_blocked = :is_blocked WHERE email = :email");
        $statement->bindValue(":is_blocked", 1);
        $statement->bindValue(":email", $this->getEmail());
        $statement->execute();
    }

        // Existing properties and methods
        
        public function unblockUser() {
            $conn = Db::getInstance();
            $statement = $conn->prepare("UPDATE users SET is_blocked = :is_blocked WHERE email = :email");
            $statement->bindValue(":is_blocked", 0);
            $statement->bindValue(":email", $this->getEmail());
            $statement->execute();
        }
    
    
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
        $statement = $conn->prepare("INSERT INTO users (email, password, is_admin, credits, verified) VALUES (:email, :password, :is_admin, :credits, :verified)");
        //binden
        $statement->bindValue(":email", $this->getEmail()); 
        $statement->bindValue(":password", $this->getPassword());
        $statement->bindValue(":is_admin", 0);
        $statement->bindValue(":credits", 0);
        $statement->bindValue(":verified", 0);
        //execute
        return $statement->execute(); 
    }
    /*login*/
    public function canLogin($email, $password) {
        $conn = Db::getInstance();
        $statement = $conn->prepare("SELECT email, password FROM users WHERE email = :email");
        $statement->bindValue(":email", $email);
        $statement->execute();
        $row = $statement->fetch();

        if ($row) {
            return password_verify($password, $row['password']);
        }

        return false;
    }

    public static function getAll()
    {
        $conn = Db::getInstance();
        $statement = $conn->prepare("select * from users");
        $statement->execute();
        $users = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $users;
    }

    public function checkMailAvailable(){
        $conn = Db::getInstance();
        $statement = $conn->prepare("select * from users where email = :email");
        $statement->bindValue(":email", $this->getEmail());
        $statement->execute();
        $count = $statement->fetch();
        if ($count != null) {
            return false;
        } else{
            return true;
        }
        return $count;
    }
    /*Email versturen*/
    public function sendMail(){
        $config = parse_ini_file('config/config.ini', true);
        $key = $config['keys']['SENDGRID_API_KEY'];
        //var_dump($key);
        
        $email = new \SendGrid\Mail\Mail();
        $email->setFrom("r0784273@student.thomasmore.be", "PromptBaes");
        $email->setSubject("Confirm email");
        $email->addTo($_POST["email"], $_POST["email"]);
        $email->addContent("text/plain", "Please confirm your email by clicking the link below");
        $email->addContent(
           "text/html", "Please confirm your email by clicking the link <a>https://promptbaesxd.azurewebsites.net/index.php</a>"
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

    public function getUserPrompts(){
        $conn = Db::getInstance();
        $statement = $conn->prepare("SELECT * FROM `prompts` WHERE email = :email and approved = :approved");
        $statement->bindValue(":email", $_GET['id']);
        $statement->bindValue(":approved", 1);
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function showUserPrompts(){
        $conn = Db::getInstance();
        $statement = $conn->prepare("SELECT * FROM `prompts` WHERE email = :email and approved = :approved");
        $statement->bindValue(":email", $_SESSION['username']);
        $statement->bindValue(":approved", 1);
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function checkIfCanBuy(){
        $conn = Db::getInstance();
        $statement = $conn->prepare("SELECT users.credits >= prompts.price AS can_buy FROM users, prompts WHERE prompts.id = :prompts_id AND users.email = :email");
        $statement->bindValue(":prompts_id", $_GET["buy"]);
        $statement->bindValue(":email", $_SESSION['username']);
        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        /*var_dump($result);*/
        return $result;
    }

    public function buyPrompt(){
        $conn = Db::getInstance();
        $statement = $conn->prepare("UPDATE users SET users.credits = users.credits - (SELECT prompts.price FROM prompts WHERE prompts.id = :prompts_id) WHERE users.email = :email");
        $statement->bindValue(":prompts_id", $_GET["buy"]);
        $statement->bindValue(":email", $_SESSION['username']);
        $statement->execute();
    }

    public function sellPrompt(){
        $conn = Db::getInstance();
        $statement = $conn->prepare("UPDATE users SET users.credits = users.credits + (SELECT prompts.price FROM prompts WHERE prompts.id = :prompts_id) WHERE users.email = (SELECT prompts.email FROM prompts WHERE prompts.id = :prompts_id)");
        $statement->bindValue(":prompts_id", $_GET["buy"]);
        $statement->execute();
    }
     //show total amount of credits user
     public function showCredits(){
        $conn = Db::getInstance();
        $statement = $conn->prepare("SELECT credits FROM users WHERE email = :email");
        $statement->bindValue(":email", $_SESSION['username']);
        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        return $result;
    }

    //add credits if prompt of user is approved
    public function addCreditsIfApproved($approved){
        if($approved){
            $conn = Db::getInstance();
            $statement = $conn->prepare("UPDATE users SET users.credits = users.credits + 1 WHERE email = :email");
            $statement->bindValue(":email", $_SESSION['username']);
            $statement->execute();
        }
    }
    /* laten nakijken op sql injectie op feedback*/
    public function checkVerify(){
        $conn = Db::getInstance();
        $statement = $conn->prepare("SELECT COUNT(*) AS approved_count FROM prompts WHERE email = :email AND approved = :approved");
        $statement->bindValue(":email", $this->getEmail());
        $statement->bindValue(":approved", 1);
        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        
        return $result['approved_count'] > 3;
    }
    
    public function verifyUser($verified){
        if($verified){
            $conn = Db::getInstance();
            $statement = $conn->prepare("UPDATE users SET users.verified = :verified WHERE email = :email");
            $statement = bindValue(":verified", 1);
            $statement->bindValue(":email", $_SESSION['username']);
            $statement->execute();
        }
    }
}