<?php
    class Like{
        private $promptId;
        private $user;

        /**
         * Get the value of promptId
         */ 
        public function getPromptId()
        {
                return $this->promptId;
        }

        /**
         * Set the value of promptId
         *
         * @return  self
         */ 
        public function setPromptId($promptId)
        {
                $this->promptId = $promptId;

                return $this;
        }
        
        /**
         * Get the value of user
         */ 
        public function getUser()
        {
                return $this->user;
        }

        /**
         * Set the value of user
         *
         * @return  self
         */ 
        public function setUser($user)
        {
                $this->user = $user;

                return $this;
        }

        public function save(){
            $conn = Db::getInstance();
            $statement = $conn->prepare("insert into likes (prompts_id, username) values (:prompts_id, :username)");
            $statement->bindValue(":prompts_id", $this->getPromptId());
            $statement->bindValue(":username", $this->getUser());
            return $statement->execute();
        }

        public static function getAll($prompt_id)
        {
                $conn = Db::getInstance();
                $statement = $conn->prepare("SELECT * FROM likes WHERE prompts_id = :prompts_id AND username = :username");
                $statement->bindValue(":prompts_id", $prompt_id);
                $statement->bindValue(":username", $_SESSION['username']);
                $statement->execute();
                $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                if (!empty($result)) {
                        return true;
                } else {
                        return false;
                }
        }

        public function remove($prompt_id)
        {
                $conn = Db::getInstance();
                $statement = $conn->prepare("DELETE FROM likes WHERE prompts_id = :prompts_id AND username = :username");
                $statement->bindValue(":prompts_id", $prompt_id);
                $statement->bindValue(":username", $_SESSION['username']);
                $statement->execute();
        }

    }