<?php
    include_once("bootstrap.php");
    if(!empty($_POST)){
         //nieuwe like aanmaken
        $l = new Like();
        $l->setPromptId($_POST["data-id"]);
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