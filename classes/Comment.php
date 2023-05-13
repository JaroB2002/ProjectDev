<?php
include_once("bootstrap.php");
    class Comment{
        private $text;
        private $postId;
        private $userId;

        /**
         * Get the value of text
         */ 
        public function getText()
        {
                return $this->text;
        }

        /**
         * Set the value of text
         *
         * @return  self
         */ 
        public function setText($text)
        {
                $this->text = $text;

                return $this;
        }

        /**
         * Get the value of postId
         */ 
        public function getPostId()
        {
                return $this->postId;
        }

        /**
         * Set the value of postId
         *
         * @return  self
         */ 
        public function setPostId($postId)
        {
                $this->postId = $postId;

                return $this;
        }

        /**
         * Get the value of userId
         */ 
        public function getUserId()
        {
                return $this->userId;
        }

        /**
         * Set the value of userId
         *
         * @return  self
         */ 
        public function setUserId($userId)
        {
                $this->userId = $userId;

                return $this;
        }
        public function save(){
            $conn = Db::getInstance();
            $statement = $conn->prepare("INSERT INTO comments (text, postId, userId) VALUES (:text, :postId, :userId)");
            
            $text = $this->getText();
            $postId = $this->getPostId();
            $userId = $this->getUserId();
            
            $statement->bindValue(":text", $text);
            $statement->bindValue(":postId", $postId);
            $statement->bindValue(":userId", $userId);

            $result = $statement->execute();
            return $result;
        }
        public static function getAll($postId){
                $conn = Db::getInstance();
                $statement = $conn->prepare("SELECT * FROM comments WHERE postId = :postId");
                $statement->bindValue(":postId", $postId);
                $result = $statement->execute();
                return $statement->fetchAll(PDO::FETCH_ASSOC);
        }
    }
    ?>