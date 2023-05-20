<?php
    class Moderator extends User {
       
        public function approvePrompt(){
            $conn = Db::getInstance();
            $statement = $conn->prepare('update prompts set approved = :approve where id = :id');
            $statement->bindValue(':approve', 1);
            $statement->bindValue(":id", $_GET["approve"]);
            $statement->execute();
        }

        public function unapprovePrompt(){
            $conn = Db::getInstance();
            $statement = $conn->prepare('delete from prompts where id = :id');
            $statement->bindValue(":id", $_GET["disapprove"]);
            $statement->execute();
        }

        public function blockUser(){
            $conn = Db::getInstance();
            $statement = $conn->prepare('update users set blocked = :blocked where email = :email');
            $statement->bindValue(":blocked", 1);
            $statement->bindValue(":email", 1);
            $statement->execute();
        }
        public function getAllModerators(){
            $conn = Db::getInstance();
            $statement = $conn->prepare("SELECT * FROM users WHERE is_admin = :is_admin");
            $statement->bindValue(":is_admin", 1);
            $statement->execute();
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        }

        public function addModerator($email) {
            $conn = Db::getInstance();
            $statement = $conn->prepare("UPDATE users SET is_admin = :is_admin WHERE email = :email");
            $statement->bindValue(":is_admin", 1);
            $statement->bindValue(":email", $email);
            $statement->execute();
          }
          
          public function removeModerator($email) {
            $conn = Db::getInstance();
            $statement = $conn->prepare("UPDATE users SET is_admin = :is_admin WHERE email = :email");
            $statement->bindValue(":is_admin", 0);
            $statement->bindValue(":email", $email);
            $statement->execute();
          }
    }
