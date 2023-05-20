<?php 
    include_once("bootstrap.php");
    session_start();

    if(!isset($_SESSION['username'])){
        header("location: index.php");
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
  <nav class="relative container mx-auto p-6 bg-offwhite rounded-md">
    <div class="flex items-center justify-between">
        <div class=" md:flex space-x-6">
            <a href="dashboard.php" class="text-lg font-bold hover:text-fadedpurple">Home</a>
            <a href="editprofile.php" class="text-lg font-bold hover:text-fadedpurple">Profile</a>
            <a href="uploadPrompt.php" class="text-lg font-bold hover:text-fadedpurple">Upload</a>
        </div>
        <a href="logout.php" class="hidden md:block p-3 px-6 pt-2 text-white bg-fadedpurple rounded-full baseline font-semibold text-lg">Log out</a>
    </div>
    </nav>
    <h1 class="text-3xl font-semibold mt-5"> <?php echo $_GET["id"]; ?> </h1>
    <?php if ($_SESSION['username'] !== $_GET['id']): ?>
      <div class="mt-10">
        <a href="#" data-id="<?php echo $_GET['id']; ?>" class="follow bg-fadedpurple px-5 py-3 rounded font-semibold mt-5"><?php if(Follow::getAll($_GET['id']) == true) { echo 'Unfollow'; } else { echo 'Follow';}?></a>
      </div>
    <?php endif; ?>
    <?php if ($isModerator) : ?>
      <div class="flex">
        <form method="post">
            <button class="bg-fadedblue px-5 py-3 mt-5 rounded font-semibold text-white mr-5" type="submit" name="add" value="<?= isset($_GET['id']) ? $_GET['id'] : '' ?>">Add Moderator</button>
        </form>

        <form method="post">
            <button class="bg-fadedblue px-5 py-3 mt-5 rounded font-semibold text-white mr-5" type="submit" name="remove" value="<?= isset($_GET['id']) ? $_GET['id'] : '' ?>">Remove Moderator</button>
        </form>
      </div>
    <?php endif; ?>
    <?php if (isset($success)) : ?>
        <p><?php echo htmlspecialchars($success) ?></p>
    <?php endif; ?>
  <article class="flex flex-wrap">
      <?php if(!empty($user_prompts)) : ?>
          <?php foreach($user_prompts as $prompt): ?>
              <div class="my-5 bg-offblack mr-10 px-8 py-8 rounded max-w-sm">
                  <p class="mb-5 text-lg text-offwhite"> <strong>Name: </strong> <?php echo $prompt["name"];?></p>
                  <img class="mb-5" src="<?php echo $prompt["image"]; ?>" alt="input image">
                  <p class="mb-3 text-lg text-offwhite"> <strong>description: </strong> <?php echo $prompt["description"];?></p>
                  <p class="mb-3 text-lg text-offwhite"> <strong>type: </strong> <?php echo $prompt["type"]?>  </p>
                  <p class="mb-3 text-lg text-offwhite"> <strong>price: </strong> <?php echo $prompt["price"];?></p>
              </div>
          <?php endforeach; ?>
      <?php endif; ?>
    </article>

    <script src="js/follow.js"></script>
</body>
</html>