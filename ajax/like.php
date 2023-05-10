<?php
    include_once("../bootstrap.php");
    session_start();
    if(!empty($_POST)){
         //nieuwe like aanmaken
        $l = new Like();
        $l->setPromptId($_POST["promptId"]);
        $l->setUser($_SESSION["username"]);

         //save()
        $l->save();

         //succesboodschap teruggeven
         $respons = [
             'status' => 'succes',
             'message' => 'like saved',
         ];

         header('Content-Type: application/json');
         echo json_encode($respons); //json object gemaakt worden 
    }
?>