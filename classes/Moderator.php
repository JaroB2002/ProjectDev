<?php
    include_once("bootstrap.php");

    class Moderator extends User {
        public function isAdmin(){
            $conn = Db::getInstance();
            $username = $_SESSION['username'];
            
            $statement = $conn->prepare("select * from users where is_admin = :is_admin and email = :email");
            $statement->bindValue(":is_admin", 1);
            $statement->bindValue(":email", $username); 
            $statement->execute();
            $admins = $statement->fetchAll(PDO::FETCH_ASSOC);
            //var_dump($admins);
            //als array leeg is is die persoon geen admin dus mag die niet opdeze pagina
            if (empty($admins)) {
                header("Location: dashboard.php");
            }
        }
    }
?>