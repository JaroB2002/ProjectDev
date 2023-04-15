<?php
   /*edit bio*/
    include_once("bootstrap.php");
    //get username from session
    session_start();
    $username = $_SESSION['username'];
    
    if(!empty($_POST)){
        try{
            $user = new User();
            $user->setEmail($username);
            $user->setBiography($_POST['biography']);
            $biography = $user->getBiography();
            $user->updateProfile();
        } 
        catch(Throwable $e){
            $error=$e->getMessage();
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
</head>
<body>
    <form action="#" method="post">
        <div>
          <label for="biography">Biography</label>
          <input id="biography" name="biography" type="text" placeholder="Biography">
          <p><?php echo htmlspecialchars($biography); ?></p> <!--special chars als je iets laat zien in frontend-->
        </div>
        <div>
        <button type="submit" name="updateProfile">Add bio</button>
        </div>
    </form>
</body>
</html>