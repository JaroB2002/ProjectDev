<?php
  include_once("bootstrap.php");
  session_start();

  if(!isset($_SESSION['username'])){
    header("location: index.php");
  } 

  $username = $_SESSION['username'];

  $user = new User();
  $user->setEmail($username);
  $userDetails = $user->getUserDetails();
  $biography = $userDetails['biography'];

  //update biography
  if(!empty($_POST)){
    try{
      if(!empty($_POST['biography'])){
        $user->setBiography($_POST['biography']);
        $biography = $user->getBiography();
        $user->updateProfile();
      }
    } 
    catch(Throwable $e){
        $error=$e->getMessage();
    }
  } 
  if (!empty($_POST['password'])) {
    $newPassword = $_POST['password'];
    $user->changePassword($newPassword);
    // Redirect naar profielpagina of andere pagina
    header("location: editprofile.php");
    exit;
  }
 
  //verify user
  if($user->checkVerify()){
    $user->verifyUser(true);
  }

  //show credits
  $allCredits = $user->showCredits();
  $credits = $allCredits['credits'];

  //prompts of user
  $u = new User();
  $user_prompts = $u->showUserPrompts();
  $user_favorites = $u->getFavorites();

  try {
    if(isset($_POST['delete_account'])){
      $u->deleteAccount($username);
      header("Location: register.php"); 
    }
  } catch (PDOException $e) {
      echo "Error: " . $e->getMessage();
  }
/*
  if (isset($_POST['addCredit'])) {
    $user->incrementCredits(1);
    $user->updateCredits(); // Update credits in de database
    header("Location: editprofile.php");
    exit;
  }*/
?>

<!DOCTYPE html>
<html lang="en">
<head>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" integrity="sha512-PmkEJHmZvcwdeUDzL5Z+K9QGQxxbivn5nMxvM5rPLnAR">
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
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
<body>
  <header class="bg-gray-900 pb-20">
    <nav><?php include_once("inc/navigation.php")?></nav>
    <article class="flex flex-wrap gap-10 justify-center items-end">
      <div>
        <h1 class="text-5xl text-offgrey mb-10 mt-10 font-semibold">Profile</h1>
        <img src="uploads/promptbg.jpg" class="rounded-full w-96 h-96" alt="pic">
      </div>
      <div>
        <h2 class="text-3xl text-fadedblue mb-5">Hi, it's <?php echo htmlspecialchars($username); ?>!</h2>
        <?php if($user->checkVerify()): ?>
          <h2 class="text-2xl text-fadedpurple mb-5">I am a verified user.</h2>
        <?php endif; ?>
        <h3 class="text-2xl text-offgrey mb-3"><?php echo "My credits: " . htmlspecialchars($credits);?></h3>
        <form action="#" method="post">
  <button class="bg-fadedpurple px-5 py-3 mt-5 rounded font-semibold text-white" type="submit" name="addCredit">Add Credit</button>
</form>
        <div class="max-w-sm">
        <h3 class="text-2xl mb-3 text-offgrey">My biography:</h3>
        <p class="text-8sm mb-3 text-offgrey"><?php echo htmlspecialchars($biography); ?></p> 
        </div>

        <form action="#" method="post">
          <div>
            <label class="underline text-fadedblue text-1xl" for="biography">Update your biography here:</label><br>
            <textarea name="biography" placeholder="Biography" class="bg-gray-900 input-field resize-none border p-2 text-white border-none w-90"></textarea>
          </div>
          <div>
            <button class="bg-fadedpurple px-5 py-3 mt-5 rounded font-semibold text-white" type="submit" name="updateProfile">Add bio</button>
            <a class="bg-fadedpurple px-3.5 py-3.5 mt-5 rounded font-semibold text-white" href="following.php">View Following</a>
          </div>
        </form>
      </div>
    </article>
  </header>
  <main class="ml-10 mt-10 mr-10">
    <div class="flex justify-center mt-20">
      <h2 class="text-3xl mb-2 font-semibold">My Favorites</h2>
    </div>
    <article class="flex flex-wrap justify-center mt-5 pt-5">
      <?php if(!empty($user_favorites)) : ?>
          <?php foreach($user_favorites as $prompt): ?>
              <div class="my-5 bg-offgrey mr-10 px-8 py-8 rounded max-w-md">
                  <p class="mb-5 text-lg text-offblack"> <strong>Name: </strong> <?php echo htmlspecialchars($prompt["name"]);?></p>
                  <img class="object-none h-96 w-96 mb-5" src="<?php echo htmlspecialchars($prompt["image"]); ?>" alt="input image">
                  <p class="mb-3 text-lg text-offblack"> <strong>Description: </strong> <?php echo htmlspecialchars($prompt["description"]);?></p>
                  <p class="mb-3 text-lg text-offblack"><strong>Tags: </strong><?php echo htmlspecialchars($prompt["tags"]);?></p>
                  <p class="mb-3 text-lg text-offblack"> <strong>Type: </strong> <?php echo htmlspecialchars($prompt["type"])?>  </p>
                  <p class="mb-3 text-lg text-offblack"> <strong>Price: </strong> <?php echo htmlspecialchars($prompt["price"]);?></p>
              </div>
          <?php endforeach; ?>
      <?php endif; ?>
    </article>
    <div class="flex justify-center mt-20">
      <h2 class="text-3xl mb-2 font-semibold">My Prompts</h2>
    </div>
    <article class="flex flex-wrap justify-center mt-5 pt-5">
      <?php if(!empty($user_prompts)) : ?>
          <?php foreach($user_prompts as $prompt): ?>
              <div class="my-5 bg-offgrey mr-10 px-8 py-8 rounded max-w-md">
                  <p class="mb-5 text-lg text-offblack"> <strong>Name: </strong> <?php echo htmlspecialchars($prompt["name"]);?></p>
                  <img class="object-none h-96 w-96 mb-5" src="<?php echo htmlspecialchars($prompt["image"]); ?>" alt="input image">
                  <p class="mb-3 text-lg text-offblack"> <strong>Description: </strong> <?php echo htmlspecialchars($prompt["description"]);?></p>
                  <p class="mb-3 text-lg text-offblack"><strong>Tags: </strong><?php echo htmlspecialchars($prompt["tags"]);?></p>
                  <p class="mb-3 text-lg text-offblack"> <strong>Type: </strong> <?php echo htmlspecialchars($prompt["type"])?>  </p>
                  <p class="mb-3 text-lg text-offblack"> <strong>Price: </strong> <?php echo htmlspecialchars($prompt["price"]);?></p>
              </div>
          <?php endforeach; ?>
      <?php endif; ?>
    </article>

    <hr class="h-px my-8 bg-offgrey">
    <h4 class="text-xl text-fadedblue mb-5">Do you want to delete your account?</h4>
    <form method="post" action="">
      <button class="text-fadedpurple underline" type="submit" name="delete_account" onclick="return confirm('Are you sure you want to delete your account? This action cannot be undone.');">Delete Account</button>
    </form>
    <form action="editprofile.php" method="post" class="py-5">
    <h4 class="text-xl text-fadedblue mb-5">Do you want to change your password?</h4>
    <div>
      <label for="password"  class="font-bold">New Password:</label><br>
      <input type="password" class="bg-offgrey" name="password" required>
    </div>
    <div>
      <button class="text-fadedpurple underline" type="submit" name="changePassword">Change Password</button>
    </div>
    </form>
</main>
<footer><?php include_once("inc/footer.php");?></footer>
<script src="js/nav.js"></script>
</body>
</html>

