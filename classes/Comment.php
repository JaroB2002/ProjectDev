<?php
    class Comment{
        private $text;
        private $promptId;
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
        public function getPromptId()
        {
                return $this->promptId;
        }

        /**
         * Set the value of postId
         *
         * @return  self
         */ 
        public function setPromptId($promptId)
        {
                $this->promptId = $promptId;

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
            $statement = $conn->prepare("INSERT INTO comments (comment, postId, userId) VALUES (:comment, :postId, :userId)");
            $statement->bindValue(":comment",  $this->getText());
            $statement->bindValue(":postId", $this->getPromptId());
            $statement->bindValue(":userId", $this->getUserId());
            $result = $statement->execute();
        }
        public static function getAll($promptId)
        {
                $conn = Db::getInstance();
                $statement = $conn->prepare("SELECT * FROM comments WHERE postId = :postId");
                $statement->bindValue(":postId", $promptId);
                $statement->execute();
                $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                return $result;
        }
    }
    ?>