<?php
    spl_autoload_register(function($class){
        require_once("../classes" . DIRECTORY_SEPARATOR . $class . ".php");
    }); 

    session_start();

    if(!empty($_POST)){
        $c = new Comment();
        $c->setPromptId($_POST['promptId']);
        $c->setText($_POST['text']);
        $c->setUserId($_SESSION["username"]);

        $c->save();

        $response = [
            'status' => 'succes',
            'message' => 'comment saved',
            'body' => htmlspecialchars($c->getText())
        ];

        echo json_encode($response);
    }

?>