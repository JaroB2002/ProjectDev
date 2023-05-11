<?php
include_once("../bootstrap.php");

if(!empty($_POST)){

    $email = $_POST['email'];
    $user = new User();
    $user->setEmail($email);
        
    if($user->checkMailAvailable()){
        $response = [
            'status' => 'success',
            'email' => $email,
            'available' => 'true',
        ];
    } else{
        $response = [
            'status' => 'success',
            'email' => $email,
            'available' => 'false',
        ];
    }
    echo json_encode($response);
}
?>