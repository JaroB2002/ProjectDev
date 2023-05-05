<?php
    class Like{
        private $promptId;
        private $userId;

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
            $statement = $conn->prepare("insert into likes (prompts_id, users_id, date_created) values (:prompts_id, :user_id, NOW())");
            $statement->bindValue(":prompts_id", $this->getPromptId());
            $statement->bindValue(":user_id", $this->getUserId());
            return $statement->execute();
        }
    }
?>