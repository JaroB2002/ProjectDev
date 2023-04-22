<?php
    include_once("bootstrap.php");
   //alleen admin logt in op deze pagina
    session_start();
    $admin = new User();
    $admin->isAdmin();

    //prompts printen
    $allPrompts = Prompt::getAllUnapproved();
    //var_dump($allPrompts);

    if(isset($_GET["approve"])){
        var_dump("😍");
        $conn = Db::getInstance();
        $statement = $conn->prepare('update prompts set approved = :approve where id = :id');
        $statement->bindValue(':approve', 1);
        $statement->bindValue(":id", $_GET["approve"]);
        $statement->execute();
         // Remove the prompt from the $allPrompts array
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
        // Remove the prompt from the $allPrompts array
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
                <p> <strong>User: </strong> <?php echo $prompt["name"];?></p>
                <p> <strong>Image: </strong> <?php echo $prompt["image"];?></p>
                <p> <strong>description: </strong> <?php echo $prompt["description"];?></p>
                <p> <strong>type: </strong> <?php echo $prompt["type"]?> <strong>price: </strong> <?php echo $prompt["price"];?></p>
            </div>
            <form action="">
                <button type="submit" name="approve" value="<?php echo $prompt['id']; ?>">Approve</button>
                <button type="submit" name="disapprove" value="<?php echo $prompt['id']; ?>">disapprove</button>
            </form>
        <?php endforeach; ?>
    </article>
</body>
</html>