<?php
include_once("bootstrap.php");

session_start();
$username = $_SESSION['username'];
if(!isset($_SESSION['username'])){
    header('Location: index.php');
}

$name = "";
$description = "";
$email = "";
//$image = "";
$type = "";
$price = "";
$succes="";

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
            offgrey: '#D9D9D9',
            offblack: '#D9D9D9',
          }
        }
      }
    }
  </script>
</head>
<body>
    <h1 class="text-4xl mx-5 font-semibold mt-7"> Share your prompt! </h1>
    <form action="#" method="post" class="my-5 mx-5 bg-offgrey px-8 py-8 rounded max-w-sm">
        <div class="my-2">
          <label for="name">Name</label>
          <input class="bg-offgrey" id="name" name="name" type="text" placeholder="Name">
        </div>
        <div class="my-2">
          <label for="description">Description</label>
          <input class="bg-offgrey" id="description" name="description" type="text" placeholder="Description">
        </div>
        <div class="my-2">
          <label for="image">Image</label>
          <input class="bg-offgrey" id="image" name="image" type="text" placeholder="Image">
        </div>
        <div class="my-2">
          <label for="type">Type</label>
          <input class="bg-offgrey" id="type" name="type" type="text" placeholder="Type">
          <!--<input type="checkbox" name="type[]" value="lineart"> Line art <br>
          <input type="checkbox" name="type[]" value="cartoon"> Cartoon <br>
          <input type="checkbox" name="type[]" value="realistic"> Realistic <br>-->
        </div class="my-2">
            <label for="price">Price</label>
            <input class="bg-offgrey" id="price" name="price" type="text" placeholder="Price">
        </div>
        <div class="my-2">
        <button class="bg-fadedpurple px-5 py-3 mt-5 rounded font-semibold" type="submit" name="uploadPrompt">Upload prompt</button>
        </div>
    </form>
    <article class="my-5 mx-5 bg-offgrey px-8 py-8 rounded max-w-sm">
        <h2 class="text-2xl font-semibold">Your prompt:</h2>
        <div>
            <p class="my-2">Name: <?php echo htmlspecialchars($name); ?></p>
            <p class="my-2">Description: <?php echo htmlspecialchars($description); ?></p>
            <img class="my-2" src="<?php echo htmlspecialchars($image); ?>" alt="input image">
            <p class="my-2">Type: <?php echo htmlspecialchars($type); ?></p>
            <p class="my-2">Price: € <?php echo htmlspecialchars($price); ?></p>
        </div>
        <p class="font-semibold text-fadedblue"><?php echo $succes?></p>
    </article>
</body>
