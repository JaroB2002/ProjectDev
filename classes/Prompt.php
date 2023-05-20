<?php
class Prompt{
    private $name; 
    private $description;
    private $email;
    private $image;
    private $type;
    private $price;
    private $tag;
    //test
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

    public function getTag()
    {
        return $this->tag;
    }

    public function setTag($tag)
    {
        if (empty($tag)) {
            throw new Exception("tags cannot be empty.");
        }
        else{ 
        $this->tag = $tag;
        }
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
        $conn = Db::getInstance();
        $statement = $conn->prepare("INSERT INTO prompts (name, description, email, image, type, price, date, approved, tags) VALUES (:name, :description, :email, :image, :type, :price, now(), :approved, :tags)");
        $statement->bindValue(":name", $this->getName()); 
        $statement->bindValue(":description", $this->getDescription());
        $statement->bindValue(":email", $this->getEmail());
        $statement->bindValue(":image", $this->getImage());
        $statement->bindValue(":type", $this->getType());
        $statement->bindValue(":price", $this->getPrice());
        $statement->bindValue(":approved", 0);
        $statement->bindValue(":tags", $this->getTag());
        return $statement->execute(); 
    }
    //alle prompts los van specificaties
    public static function getAll()
    {
        $conn = Db::getInstance();
        $statement = $conn->prepare("select * from prompts");
        $statement->execute();
        $prompts = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $prompts;
    }
    //unapproved prompts ophalen
    public static function getAllUnapproved(){
        $conn = Db::getInstance();
        $statement = $conn->prepare("select * from prompts where approved = :approved");
        $statement->bindValue(":approved", 0);        
        $statement->execute();
        $prompts = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $prompts;
    }

    public static function filter($pricing, $type, $date, $search){
        $conn = Db::getInstance();
        $statement = "select * from prompts where approved = :approved";
        $typeConditions = [
            "all" => "1",
            "lineArt" => "type = 'line art'",
            "cartoon" => "type = 'cartoon'",
            "realistic" => "type = 'realistic'"
        ];
        switch($pricing){
            case "paid":
                $statement .= " and price > 0";
                break;
            case "free":
                $statement .= " and price = 0";
                break;
        }
        if (isset($typeConditions[$type])) {
            $statement .= " AND " . $typeConditions[$type];
        }
        /*switch($type){
            case "lineArt":
                $statement .= " and type = 'line art'";
                break;
            case "cartoon":
                $statement .= " and type = 'cartoon'";
                break;
            case "realistic":
                $statement .= " and type = 'realistic'";
                break;
        }*/
        switch($date){
            case "new":
                $statement .= " order by date desc";
                break;
            case "old":
                $statement .= " order by date asc";
                break;
        }
        switch($search){
            case "all":
                break;
            default:
                $statement .= " and name LIKE :title or tags LIKE :tags";
                break;
        }
        $result = $conn->prepare($statement);
        $result->bindValue(":approved", 1);
        if($search != "all"){
            $result->bindValue(":title", "%$search%");
            $result->bindValue(":tags", "%$search%");
        }
        $result->execute();
        $filter = $result->fetchAll(PDO::FETCH_ASSOC);
        return $filter;

        self::getLikes();
    }

    public function getLikes($prompt_id){
        $conn = Db::getInstance();
        $statement = $conn->prepare("select count(*) as count from likes where prompts_id = :promptsid");
        $statement->bindValue(":promptsid", $prompt_id);
        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        return $result['count'];
    }

    public static function getAllFollowing(){
        $conn = Db::getInstance();
        $statement = $conn->prepare("SELECT prompts.* FROM prompts JOIN follow ON prompts.email = follow.user_id WHERE follow.username = :username");
        $statement->bindValue(":username", $_SESSION["username"]);        
        $statement->execute();
        $prompts = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $prompts;
    }
}