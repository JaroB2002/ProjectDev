<?php
//include_once("bootstrap.php");

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

    public function changePassword($newPassword){
        if (strlen($newPassword) <= 5 || empty($newPassword)) {
            throw new Exception("New password is not valid.");
            return false;
        }

        $options = [
            'cost' => 12,
        ];
        $this->password = password_hash($newPassword, PASSWORD_DEFAULT, $options);

        $conn = Db::getInstance();
        $statement = $conn->prepare("UPDATE users SET password = :password WHERE email = :email");
        $statement->bindValue(":password", $this->getPassword());
        $statement->bindValue(":email", $this->getEmail());
        $statement->execute();

        return true;
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
            $statement = $conn->prepare("UPDATE users SET users.credits = users.credits + 1 WHERE users.email = (SELECT prompts.email FROM prompts WHERE prompts.id = :id)");
            $statement->bindValue(":id", $_GET['approve']);
            $statement->execute();
        }
    }
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
            $statement->bindValue(":verified", 1);
            $statement->bindValue(":email", $this->getEmail());
            $statement->execute();
        }
    }

    public function getFavorites(){
        $conn = Db::getInstance();
        $statement = $conn->prepare("SELECT prompts.* FROM `prompts` JOIN favorites ON prompts.id = favorites.prompts_id WHERE favorites.username = :username");
        $statement->bindValue(":username", $_SESSION['username']);
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function banUser(){
        $conn = Db::getInstance();
        $statement = $conn->prepare("UPDATE users SET is_banned = :is_banned WHERE email = :email");
        $statement->bindValue(":is_banned", 1);
        $statement->bindValue(":email", $_GET['id']);
        $statement->execute();
    }

    public function isBanned()
    {
        $conn = Db::getInstance();
        $statement = $conn->prepare("SELECT is_banned FROM users WHERE email = :email");
        $statement->bindValue(":email", $_GET['id']);
        $statement->execute(); 
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        return $result['is_banned'];
    }

    public function deleteAccount($email) {
        $conn = Db::getInstance();
        $statement = $conn->prepare("DELETE users, prompts FROM users LEFT JOIN prompts ON users.email = prompts.email WHERE users.email = :email");
        $statement->bindValue(":email", $email);
        $statement->execute();
    
        // Reset the object properties
        $this->email = null;
        $this->password = null;
        $this->biography = null;
    }

   /* public function incrementCredits($amount) {
        $this->credits += $amount;
        // Update credits in de database of waar je de credits ook opslaat
        // ...
      }

    public function updateCredits() {
        $conn = Db::getInstance();
        $statement = $conn->prepare("UPDATE users SET credits = credits + 1 WHERE email = :email");
        $statement->bindValue(":email", $this->getEmail());
        $statement->execute();
    }*/
    /*public function blockUser() {
        $conn = Db::getInstance();
        $statement = $conn->prepare("UPDATE users SET is_blocked = :is_blocked WHERE email = :email");
        $statement->bindValue(":is_blocked", 1);
        $statement->bindValue(":email", $this->getEmail());
        $statement->execute();
    }

    public function unblockUser() {
        $conn = Db::getInstance();
        $statement = $conn->prepare("UPDATE users SET is_blocked = :is_blocked WHERE email = :email");
        $statement->bindValue(":is_blocked", 0);
        $statement->bindValue(":email", $this->getEmail());
        $statement->execute();
    }*/
}
