<?php
 spl_autoload_register(function($class){
    require_once("../classes" . DIRECTORY_SEPARATOR . $class . ".php");
}); //test
session_start();
    if(!empty($_POST)){
         //nieuwe like aanmaken
        $l = new Like();
        $l->setPromptId($_POST['promptId']);
        $l->setUser($_SESSION["username"]);

         //save()
        $l->save($_POST['promptId']);

        $p = new Prompt();
        $likes = $p->getLikes($_POST['promptId']);

         //succesboodschap teruggeven
         $respons = [
             'status' => 'succes',
             'message' => 'like saved',
             "likes" => $likes
         ];

         echo json_encode($respons); //json object gemaakt worden 
    }
?>