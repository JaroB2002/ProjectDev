<?php 
    session_start();
    if(isset($_SESSION['username'])){
        //user is logged in
        echo "Welcome " . $_SESSION['username'];
        //queries in sql
    } else{
        //user is not logged in
        header("location: login.php");
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
</head>
<a href="logout.php">Log out?</a>

<body>
    <h1>Your home</h1>
</body>
</html>

