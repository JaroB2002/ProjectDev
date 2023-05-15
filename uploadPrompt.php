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
        $prompt->setEmail($username);
        $prompt->setImage($_POST['image']);
        $prompt->setType($_POST['type']);
        $prompt->setPrice($_POST['price']);

        $name = $prompt->getName();
        $description = $prompt->getDescription();
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
          <select class="bg-offgrey" name="type">
                <option value="all">All</option>
                <option value="line art">Line art</option>
                <option value="realistic">Realistic</option>
                <option value="cartoon">Cartoon</option>
            </select>
        </div class="my-2">
            <label for="price">Price</label>
            <input class="bg-offgrey" id="price" name="price" type="text" placeholder="Price">
        </div>
        <div class="my-2">
        <button class="bg-fadedpurple px-5 py-3 mt-5 rounded font-semibold" type="submit" name="uploadPrompt">Upload prompt</button>
        </div>
    </form>
    <p class="font-semibold text-fadedblue mx-5 text-xl"><?php echo $succes?></p>
</body>
