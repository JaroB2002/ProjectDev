<?php
include_once("bootstrap.php");
class Prompt{
    private $name; 
    private $description;
    private $email;
    //private $userId;
    private $image;
    //private $promptId;
    private $type;
    private $price;

    public function setName($name){
        if (empty($name)) {
            throw new Exception("Name cannot be empty.");
        }
        else{ 
        $this->name = $name;
        }
        
    }
    public function getName(){
        return $this->name;
    }

    public function setDescription($description){
        if (empty($description)) {
            throw new Exception("Description cannot be empty.");
        }
        else{ 
        $this->description = $description;
        }
        
    }
    public function getDescription(){
        return $this->description;
    }

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
    /*public function setUserId($userId){
        if (empty($userId)) {
            throw new Exception("User ID cannot be empty.");
        }
        else{ 
        $this->userId = $userId;
        }
        
    }
    public function getUserId(){
        return $this->userId;
    }*/

    public function setImage($image){
        if (empty($image)) {
            throw new Exception("Image cannot be empty.");
        }
        else{ 
        $this->image = $image;
        }
        
    }
    public function getImage(){
        return $this->image;
    }

    /*public function setPromptId($promptId){
        if (empty($promptId)) {
            throw new Exception("Prompt ID cannot be empty.");
        }
        else{ 
        $this->promptId = $promptId;
        }
        
    }
    public function getPromptId(){
        return $this->promptId;
    }*/

    public function setType($type){
        if (empty($type)) {
            throw new Exception("Type cannot be empty.");
        }
        else{ 
        $this->type = $type;
        }
        
    }
    public function getType(){
        return $this->type;
    }

    public function setPrice($price){
        if (empty($price)) {
            throw new Exception("Price cannot be empty.");
        }
        else{ 
        $this->price = $price;
        }
        
    }
    public function getPrice(){
        return $this->price;
    }

    //prompts in databank opslaan
    public function save(){
        //get connection
        $conn = Db::getInstance();
        //prepare statement
        $statement = $conn->prepare("INSERT INTO prompts (name, description, email,/*user_id,*/ image, type, price, date, approved) VALUES (:name, :description, :email, /*:userId,*/ :image, :type, :price, now(), :approved)");
        //binden
        $statement->bindValue(":name", $this->getName()); 
        $statement->bindValue(":description", $this->getDescription());
        //$statement->bindValue(":userId", $this->getUserId());
        $statement->bindValue(":email", $this->getEmail());
        $statement->bindValue(":image", $this->getImage());
        $statement->bindValue(":type", $this->getType());
        $statement->bindValue(":price", $this->getPrice());
        $statement->bindValue(":approved", 0);
        //execute
        return $statement->execute(); 
    }

    public static function getAll()
    {
        $conn = Db::getInstance();
        $statement = $conn->prepare("select * from prompts");
        $statement->execute();
        $prompts = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $prompts;
    }

    /*public function setPromptById($promptId){
        $conn = Db::getInstance();
        $statement = $conn->prepare("SELECT id FROM prompts WHERE id = :promptId");
        $statement->bindValue(":promptId", $promptId);
        $promptId = $statement->execute();
        return $promptId;
    }*/
   
    //chronologisch op datum
    public function chronologicalOrder(){
        $conn = Db::getInstance();
        $statement = $conn->prepare("SELECT * FROM prompts ORDER BY date DESC");
        $statement->execute();
        $prompts = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $prompts;
    }

}