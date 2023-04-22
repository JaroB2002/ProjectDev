<?php
    include_once("bootstrap.php");
   //alleen admin logt in op deze pagina
    session_start();

    //prompts printen
    $allPrompts = Prompt::getAllUnapproved();
    //var_dump($allPrompts);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Approve prompt</title>
</head>
<body>
    <h1>Approve prompts</h1>
    <article>
        <?php foreach($allPrompts as $prompt): ?>
            <div>
                <p> <strong>User: </strong> <?php echo $prompt["name"];?></p>
                <p> <strong>Image: </strong> <?php echo $prompt["image"];?></p>
                <p> <strong>description: </strong> <?php echo $prompt["description"];?></p>
                <p> <strong>type: </strong> <?php echo $prompt["type"]?> <strong>price: </strong> <?php echo $prompt["price"];?></p>
            </div>
        <?php endforeach; ?>
    </article>
</body>
</html>