<?php
 spl_autoload_register(function($class){
    require_once("../classes" . DIRECTORY_SEPARATOR . $class . ".php");
}); //test
session_start();

    if(!empty($_POST)){
         //nieuwe like aanmaken
        $fo = new Follow();
        $fo->setUserId($_POST['usersId']);
        $fo->setUser($_SESSION["username"]);

        if (Follow::getAll($_POST['usersId'])) {
            $fo->remove($_POST['usersId']);
            $status = 'Follow';
        } else {
         //save()
         $status = 'Unfollow';
         $fo->save($_POST['usersId']);
        }
        
         //succesboodschap teruggeven
         $respons = [
             'status' => $status,
             'message' => 'follow saved',
         ];

         echo json_encode($respons); //json object gemaakt worden 
    }
?>