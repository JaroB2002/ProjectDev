<?php
    include_once("bootstrap.php");
   //alleen admin logt in op deze pagina
    session_start();
    $admin = new Moderator();
    $admin->isAdmin();

    //prompts printen
    $allPrompts = Prompt::getAllUnapproved();
    //var_dump($allPrompts);

    if(isset($_GET["approve"])){
        $conn = Db::getInstance();
        $statement = $conn->prepare('update prompts set approved = :approve where id = :id');
        $statement->bindValue(':approve', 1);
        $statement->bindValue(":id", $_GET["approve"]);
        $statement->execute();
         // prompts uit allPrompt array halen
         foreach ($allPrompts as $key => $prompt) {
            if ($prompt["id"] == $_GET["approve"]) {
                unset($allPrompts[$key]);
                break;
            }
        }
    }

    if(isset($_GET["disapprove"])){
        $conn = Db::getInstance();
        $statement = $conn->prepare('delete from prompts where id = :id');
        $statement->bindValue(":id", $_GET["disapprove"]);
        $statement->execute();
        // prompts uit allPrompt array halen
        foreach ($allPrompts as $key => $prompt) {
            if ($prompt["id"] == $_GET["disapprove"]) {
                unset($allPrompts[$key]);
                break;
            }
        }
    }
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
                <p> <strong>Name: </strong> <?php echo $prompt["name"];?></p>
                <img src="<?php echo $prompt["image"]; ?>" alt="input image">
                <p> <strong>description: </strong> <?php echo $prompt["description"];?></p>
                <p> <strong>type: </strong> <?php echo $prompt["type"]?> </p>
                <p><strong>price: </strong> <?php echo $prompt["price"];?></p>
            </div>
            <form action="">
                <button type="submit" name="approve" value="<?php echo $prompt['id']; ?>">Approve</button>
                <button type="submit" name="disapprove" value="<?php echo $prompt['id']; ?>">Unapprove</button>
            </form>
        <?php endforeach; ?>
    </article>
</body>
</html>