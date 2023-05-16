<?php
 spl_autoload_register(function($class){
    require_once("../classes" . DIRECTORY_SEPARATOR . $class . ".php");
}); //test
session_start();
    if(!empty($_POST)){
         //nieuwe like aanmaken
        $f = new Favorite();
        $f->setPromptId($_POST['promptId']);
        $f->setUser($_SESSION["username"]);

        if (Favorite::getAll($_POST['promptId'])) {
            $f->remove($_POST['promptId']);
            $status = 'add to favorites';
        } else {
         //save()
         $status = 'remove from favorites';
         $f->save($_POST['promptId']);
        }
         //succesboodschap teruggeven
         $respons = [
             'status' => $status,
             'message' => 'favorite saved',
         ];

         echo json_encode($respons); //json object gemaakt worden 
    }
?>