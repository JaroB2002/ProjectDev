<?php
    class Follow{
        private $userId;
        private $user;

        /**
         * Get the value of promptId
         */ 
        public function getUserId()
        {
                return $this->userId;
        }

        /**
         * Set the value of promptId
         *
         * @return  self
         */ 
        public function setUserId($userId)
        {
                $this->userId = $userId;

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
            $statement = $conn->prepare("insert into follow (user_id, username) values (:user_id, :username)");
            $statement->bindValue(":user_id", $this->getUserId());
            $statement->bindValue(":username", $this->getUser());
            return $statement->execute();
        }

        public static function getAll($user_id)
        {
                $conn = Db::getInstance();
                $statement = $conn->prepare("SELECT * FROM follow WHERE user_id = :user_id AND username = :username");
                $statement->bindValue(":user_id", $user_id);
                $statement->bindValue(":username", $_SESSION['username']);
                $statement->execute();
                $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                if (!empty($result)) {
                        return true;
                } else {
                        return false;
                }
        }

        public function remove($user_id)
        {
                $conn = Db::getInstance();
                $statement = $conn->prepare("DELETE FROM follow WHERE user_id = :user_id AND username = :username");
                $statement->bindValue(":user_id", $user_id);
                $statement->bindValue(":username", $_SESSION['username']);
                $statement->execute();
        }

    }