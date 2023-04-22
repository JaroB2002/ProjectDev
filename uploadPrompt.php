<?php
include_once("bootstrap.php");

session_start();
$username = $_SESSION['username'];
if(!isset($_SESSION['username'])){
    header('Location: index.php');
}

if(!empty($_POST)){
    try{
        $prompt = new Prompt();
        $prompt->setName($_POST['name']);
        $prompt->setDescription($_POST['description']);
        //$prompt->setUserId($username); //$prompt->setUserId($_SESSION['id']);
        $prompt->setEmail($username);
        $prompt->setImage($_POST['image']);
        $prompt->setType($_POST['type']);
        $prompt->setPrice($_POST['price']);

        $name = $prompt->getName();
        $description = $prompt->getDescription();
        //$userId = $prompt->getUserId();
        $email = $prompt->getEmail();
        $image = $prompt->getImage();
        $type = $prompt->getType();
        $price = $prompt->getPrice();

        $prompt->save();
        //header('Location: dashboard.php');
        $succes="Thanks for sharing you prompt! We will be approving them as fast as possible.";

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
    <title>Upload prompt</title>
</head>
<body>
    <h1> Share your prompt! </h1>
    <form action="#" method="post">
        <div>
          <label for="name">Name</label>
          <input id="name" name="name" type="text" placeholder="Name">
        </div>
        <div>
          <label for="description">Description</label>
          <input id="description" name="description" type="text" placeholder="Description">
        </div>
        <div>
          <label for="image">Image</label>
          <input id="image" name="image" type="text" placeholder="Image">
        </div>
        <div>
          <label for="type">Type</label>
          <input id="type" name="type" type="text" placeholder="Type">
        </div>
            <label for="price">Price</label>
            <input id="price" name="price" type="text" placeholder="Price">
        </div>
        <div>
        <button type="submit" name="uploadPrompt">Upload prompt</button>
        </div>
    </form>
    <p><strong>your prompt:</strong></p>
    <div>
        <p>Name: <?php echo htmlspecialchars($name); ?></p>
        <p>Description: <?php echo htmlspecialchars($description); ?></p>
        <p>Image: <?php echo htmlspecialchars($image); ?></p>
        <p>Type: <?php echo htmlspecialchars($type); ?></p>
        <p>Price: € <?php echo htmlspecialchars($price); ?></p>
    </div>
    <p> <?php echo $succes?></p>
</body>
