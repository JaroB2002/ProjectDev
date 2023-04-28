<?php 
    include_once("bootstrap.php");
    session_start();

    if(!isset($_SESSION['username'])){
        header("location: index.php");
    }

    if(!empty($_GET["id"])){
        $conn = Db::getInstance();
        $statement = $conn->prepare("SELECT * FROM `prompts` WHERE email = :email");
        $statement->bindValue(":email", $_GET["id"]);
        $statement->execute();
        $user_prompts = $statement->fetchAll(PDO::FETCH_ASSOC);
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
</head>

<body>
    <h1> <?php echo $_GET["id"]; ?> </h1>

    <?php if(isset($user_prompts)) : ?>
        <?php foreach($user_prompts as $prompt): ?>
            <div>
                <p> <strong>Name: </strong> <?php echo $prompt["name"];?></p>
                <img src="<?php echo $prompt["image"]; ?>" alt="input image">
                <p> <strong>description: </strong> <?php echo $prompt["description"];?></p>
                <p> <strong>type: </strong> <?php echo $prompt["type"]?>  </p>
                <p> <strong>price: </strong> <?php echo $prompt["price"];?></p>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</body>
</html>