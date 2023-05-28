<?php
include_once("bootstrap.php");

session_start();
$username = $_SESSION['username'];
if(!isset($_SESSION['username'])){
    header('Location: index.php');
}
$succes = "";
if(!empty($_POST)){
    try{
        $prompt = new Prompt();
        $prompt->setName($_POST['name']);
        $prompt->setDescription($_POST['description']);
        $prompt->setEmail($username);
        $prompt->setImage($_POST['image']);
        $prompt->setType($_POST['type']);
        $prompt->setPrice($_POST['price']);
        $prompt->setTag($_POST['tag']);

        $name = $prompt->getName();
        $description = $prompt->getDescription();
        $email = $prompt->getEmail();
        $image = $prompt->getImage();
        $type = $prompt->getType();
        $price = $prompt->getPrice();
        $tag = $prompt->getTag();

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
            offgrey: '#faf9f6',
            offblack: '#313639',
            offwhite: '#f9f9f9'
          }
        }
      }
    }
  </script>
</head>
<body   class="relative bg-cover bg-no-repeat"
  style="background-image: url('uploads/promptbg.jpg');">
  <nav><?php include_once("inc/navigation.php")?></nav>
  <main class="flex justify-center">
    <form action="#" method="post" class="my-5 mx-5 py-8 rounded max-w-lg bg-offgrey px-20">
      <h1 class="text-4xl font-semibold mt-2"> Share your prompt! </h1>  
        <div class="my-2">
          <label class="text-fadedblue font-bold" for="name">Name</label><br>
          <input class="bg-offgrey px-4 py-2 rounded w-full" id="name" name="name" type="text" placeholder="Name">
        </div>
        <div class="my-2">
          <label class="text-fadedblue font-bold" for="description">Description</label><br>
          <textarea class="bg-offgrey px-4 py-2 h-32 rounded w-full" id="description" name="description" placeholder="Description"></textarea>
        </div>
        <div class="my-2">
          <label class="text-fadedblue font-bold" for="tag">Tags</label><br>
          <input class="bg-offgrey px-4 py-2 rounded w-full" id="tag" name="tag" type="text" placeholder="tags">
        </div>
        <div class="my-2">
          <label class="text-fadedblue font-bold" for="image">Image</label><br>
          <input class="bg-offgrey px-4 py-2 rounded w-full" id="image" name="image" type="text" placeholder="Image">
        </div>
        <div class="my-2">
          <label class="text-fadedblue font-bold" for="type">Type</label><br>
          <select class="bg-offgrey px-4 py-2 rounded w-full" name="type">
                <option value="all">All</option>
                <option value="line art">Line art</option>
                <option value="realistic">Realistic</option>
                <option value="cartoon">Cartoon</option>
            </select>
        </div class="my-2">
            <label class="text-fadedblue font-bold" for="price">Price</label><br>
            <input class="bg-offgrey px-4 py-2 rounded w-full" id="price" name="price" type="text" placeholder="Price">
        </div>
        <div class="my-2">
        <button class="bg-fadedpurple px-5 py-3 mt-5 rounded font-semibold" type="submit" name="uploadPrompt">Upload prompt</button>
        </div>
        <p class="font-semibold text-fadedblue pt-5 text-xl"><?php echo htmlspecialchars($succes)?></p>
        <?php if(isset($error)): ?>
            <p class="font-semibold text-fadedblue pt-5 text-xl"><?php echo htmlspecialchars($error)?></p>
        <?php endif; ?>
    </form>
  </main>
  <footer><?php include_once("inc/footer.php");?></footer>
  <script src="js/nav.js"></script>
</body>
</html>

