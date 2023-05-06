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
            $statement = $conn->prepare("insert into likes (prompts_id, username, date_created) values (:prompts_id, :username, NOW())");
            $statement->bindValue(":prompts_id", $this->getPromptId());
            $statement->bindValue(":username", $this->getUser());
            return $statement->execute();
        }

    }
?>