<?php 
    include_once("bootstrap.php");
    session_start();

    if(!isset($_SESSION['username'])){
        header("location: index.php");
    }

    if(!empty($_GET["id"])){
        $conn = Db::getInstance();
        $statement = $conn->prepare("SELECT * FROM `prompts` WHERE email = :email and approved = :approved");
        $statement->bindValue(":email", $_GET["id"]);
        $statement->bindValue(":approved", 1);
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

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
    tailwind.config = {
      theme: {
        screens: {
            sm: '480px',
            md: '768px',
            lg: '1024px',
            xl: '1280px',
        },
        extend: {
          colors: {
            fadedpurple: '#C688F4',
            fadedblue: '#5C69AA',
            offgrey: '#fdfcfd',
            offblack: '#313639',
            offwhite: '#f9f9f9'
          }
        }
      }
    }
  </script>
</head>

<body class="mr-10">
    <h1 class="text-3xl font-semibold mt-5"> <?php echo $_GET["id"]; ?> </h1>

    <?php if(isset($user_prompts)) : ?>
        <?php foreach($user_prompts as $prompt): ?>
            <div class="my-5 bg-offblack mr-10 px-8 py-8 rounded max-w-sm">
                <p class="mb-5 text-lg text-offwhite hover:text-fadedpurple"> <strong>Name: </strong> <?php echo $prompt["name"];?></p>
                <img class="mb-5" src="<?php echo $prompt["image"]; ?>" alt="input image">
                <p class="mb-3 text-lg text-offwhite"> <strong>description: </strong> <?php echo $prompt["description"];?></p>
                <p class="mb-3 text-lg text-offwhite"> <strong>type: </strong> <?php echo $prompt["type"]?>  </p>
                <p class="mb-3 text-lg text-offwhite"> <strong>price: </strong> <?php echo $prompt["price"];?></p>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</body>
</html>