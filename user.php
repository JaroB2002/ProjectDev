<?php 
    include_once("bootstrap.php");
    session_start();

    if(!isset($_SESSION['username'])){
        header("location: index.php");
    }

    if($_SESSION['username'] == $_GET['id']){
      header("location: editprofile.php");
    }

    if(!empty($_GET["id"])){
      $u = new User();
      $user_prompts = $u->getUserPrompts();
    }

    $f = new Follow();

    $user = new User();
    $user->setEmail($_SESSION['username']);
    $userDetails = $user->getUserDetails();
    $isModerator = $userDetails['is_admin'] == 1;

    if ($isModerator) {
        if (isset($_POST['add'])) {
            $moderator = new Moderator();
            $moderator->addModerator($_POST['add']);
            $success = "Moderator added";
        }

        if (isset($_POST['remove'])) {
            $moderator = new Moderator();
            $moderator->removeModerator($_POST['remove']);
            $success = "Moderator removed";
        }
    }

    if (isset($_POST['ban'])) {
        $u = new User();
        $u->banUser();    
        // Delete the user's account if it is banned
        if ($u->isBanned() !== false) {
            $u->deleteAccount($_GET['id']);
            header("Location: dashboard.php"); 
            exit();
        }
    }

    //bio
    $user->setEmail($_GET['id']);
    $userDetails = $user->getUserDetails();
    $biography = $userDetails['biography'];
  
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

<body class="ml-10">
  <nav><?php include_once("navigation.php")?></nav>

    <article class="flex flex-wrap gap-10 justify-center items-center mt-5">
      <div>
        <img src="uploads/promptbg.jpg" class="rounded-full w-96 h-96" alt="pic">
      </div>
      <div>
        <h2 class="text-3xl text-fadedblue mb-5">Hi, it's <?php echo htmlspecialchars($_GET["id"]);?>!</h2>
        <div class="max-w-sm">
        <h3 class="text-2xl mb-3">My biography:</h3>
        <p class="text-8sm mb-3"><?php echo htmlspecialchars($biography); ?></p> 
        </div>
        <?php if ($_SESSION['username'] !== $_GET['id']): ?>
          <div class="mt-10">
            <a href="#" data-id="<?php echo htmlspecialchars($_GET['id']); ?>" class="follow bg-fadedpurple px-5 py-3 rounded font-semibold mt-5"><?php if(Follow::getAll($_GET['id']) == true) { echo htmlspecialchars('Unfollow'); } else { echo htmlspecialchars('Follow');}?></a>
          </div>
        <?php endif; ?>
    </article>

    <?php if ($isModerator) : ?>
      <div class="flex ml-20 mt-10">
        <form method="post">
            <button class="bg-fadedblue px-5 py-3 mt-5 rounded font-semibold text-white mr-5 ml-5" type="submit" name="add" value="<?= isset($_GET['id']) ? htmlspecialchars($_GET['id'], ENT_QUOTES, 'UTF-8') : '' ?>">Add Moderator</button>
        </form>
        <form method="post">
            <button class="bg-fadedblue px-5 py-3 mt-5 rounded font-semibold text-white mr-5" type="submit" name="remove" value="<?= isset($_GET['id']) ? htmlspecialchars($_GET['id'], ENT_QUOTES, 'UTF-8') : '' ?>">Remove Moderator</button>
        </form>
        <form method="post">
            <button class="bg-red-500 px-5 py-3 mt-5 rounded font-semibold text-white" type="submit" name="ban">Ban User</button>
        </form>
      </div>   
    <?php endif; ?>
    
    <?php if (isset($success)) : ?>
        <p><?php echo htmlspecialchars($success) ?></p>
    <?php endif; ?>
  <article class="flex flex-wrap justify-center mt-5 pt-5">
      <?php if(!empty($user_prompts)) : ?>
          <?php foreach($user_prompts as $prompt): ?>
              <div class="my-5 bg-offblack mr-10 px-8 py-8 rounded max-w-sm">
                  <p class="mb-5 text-lg text-offwhite"> <strong>Name: </strong> <?php echo htmlspecialchars($prompt["name"]);?></p>
                  <img class="object-none h-96 w-96 mb-5" src="<?php echo htmlspecialchars($prompt["image"]); ?>" alt="input image">
                  <p class="mb-3 text-lg text-offwhite"> <strong>description: </strong> <?php echo htmlspecialchars($prompt["description"]);?></p>
                  <p class="mb-3 text-lg text-offwhite"> <strong>type: </strong> <?php echo htmlspecialchars($prompt["type"])?>  </p>
                  <p class="mb-3 text-lg text-offwhite"> <strong>price: </strong> <?php echo htmlspecialchars($prompt["price"]);?></p>
              </div>
          <?php endforeach; ?>
      <?php endif; ?>
    </article>

    <script src="js/follow.js"></script>
</body>
</html>