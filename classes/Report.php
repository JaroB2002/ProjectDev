<?php
//include_once("bootstrap.php");
class Report{
    private $reporter;
    private $promptid;

    public function setReporter($reporter){
        $this->reporter = $reporter;
    }
    public function getReporter(){
        return $this->reporter;
    }

    public function setPromptid($promptid){
        $this->promptid = $promptid;
    }
    public function getPromptid(){
        return $this->promptid;
    }

    public function reportPrompt(){
        $conn = Db::getInstance();
        $statement = $conn->prepare("INSERT INTO reports (reporter, promptid) VALUES (:reporter, :promptid)");
        $statement->bindValue(":reporter", $this->getReporter()); 
        $statement->bindValue(":promptid", $this->getPromptid());
        return $statement->execute(); 
    }

    public function isPromptReported(){
        $conn = Db::getInstance();
        $statement = $conn->prepare("SELECT * FROM reports WHERE reporter = :reporter AND promptid = :promptid");
        $statement->bindValue(":reporter", $this->getReporter()); 
        $statement->bindValue(":promptid", $this->getPromptid());
        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_ASSOC); 
        if($result){
            return true;
        }
        else{
            return false;
        }
    }
    public static function getAllReportedPrompts(){
        $conn = Db::getInstance();
        $statement = $conn->prepare("SELECT * FROM reports");
        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
}