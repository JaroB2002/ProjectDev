<?php
include_once("bootstrap.php");

session_start();
$username = $_SESSION['username'];

$user = new User();
$user->setEmail($username);
$userDetails = $user->getUserDetails();
$biography = $userDetails['biography'];

// Profiel delen
$currentDomain = $_SERVER['HTTP_HOST'];
$profileLink = "https://" . $currentDomain . "/user.php?id=" . urlencode($username);

if($user->checkVerify()){
  $user->verifyUser(true);
}

if(!empty($_POST)){
    try{
        $user->setBiography($_POST['biography']);
        $biography = $user->getBiography();
        $user->updateProfile();
    } 
    catch(Throwable $e){
        $error=$e->getMessage();
    }
}

$allCredits = $user->showCredits();
$credits = $allCredits['credits'];
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
            offgrey: '#D9D9D9',
            offblack: '#D9D9D9',
            offwhite: '#f9f9f9'
          }
        }
      }
    }
  </script>
</head>
<body>
  <nav><?php include_once("navigation.php")?></nav>
  <main class="ml-10 mt-10">
    <a href="<?php echo $profileLink; ?>">Deel mijn profiel</a>

    <h1 class="text-5xl mb-10 font-semibold">Profile</h1>
    <h2 class="text-3xl text-fadedblue mb-5">Hi, it's <?php echo htmlspecialchars($username); ?>!</h2>
    <?php if($user->checkVerify()): ?>
      <h2 class="text-2xl text-fadedpurple mb-5">I am a verified user.</h2>
    <?php endif; ?>
    <h3 class="text-2xl mb-3"><?php echo "My credits: " . htmlspecialchars($credits);?></h3>
    <div class="bg-offwhite rounded w-96 p-5">
      <h3 class="text-2xl mb-3">My biography:</h3>
      <p class="text-8sm mb-3"><?php echo htmlspecialchars($biography); ?></p> 
    </div>
    <form action="#" method="post">
      <div>
        <label class="underline text-fadedblue text-1xl" for="biography">Update your biography here:</label>
        <input id="biography" name="biography" type="text" placeholder="Biography">
      </div>
      <div>
        <button class="bg-fadedpurple px-5 py-3 mt-5 rounded font-semibold text-white" type="submit" name="updateProfile">Add bio</button>
      </div>
    </form>

    <form action="reportedUsers.php" method="post">
  <input type="hidden" name="reportedUser" value="<?php echo $username; ?>">
  <button class="bg-red-500 px-5 py-3 mt-5 rounded font-semibold text-white" type="submit" name="reportUser">Report User</button>
</form>

</main>
</body>
<h2>Delete Your Account</h2>
<p>Please confirm your email address to delete your account and all associated data.</p>
<form method="post" action="dashboard.php">
  <label for="email">Email:</label>
  <input type="email" name="email" required>
  <br>
  <label for="confirm_delete">Confirm your email address:</label>
  <input type="email" name="confirm_delete" required>
  <br>
  <p>To delete your account, type "delete my account" below:</p>
  <input type="text" name="delete_confirmation" required>
  <br>
  <button type="submit" name="delete_account" onclick="return confirm('Are you sure you want to delete your account? This action cannot be undone.');">Delete Account</button>
</form>

<h2>Download Your Data</h2>
<p>You can download a copy of your data by clicking the button below.</p>
<a href="download_data.php">Download My Data</a>

<form action="" method="post" enctype="multipart/form-data" class="ml-10">
  <label>Selecteer een afbeelding:</label>
  <input type="file" name="image" required>
  <br><br>
  <input type="submit" name="uploadPhoto" value="Uploaden" class="py-2 px-4 bg-blue-500 hover:bg-blue-600 text-white font-semibold rounded">
  <input type="submit" name="deletePhoto" value="Verwijderen" class="py-2 px-4 bg-blue-500 hover:bg-blue-600 text-white font-semibold rounded">
</body>
</html>

<?php
include('classes/Profile.php');

$profile = new Profile();

if (isset($_POST['uploadPhoto'])) {
  $result = $profile->setProfilePhoto($_FILES['image']);
  echo $result;
}

if (isset($_POST['deletePhoto'])) {
  $result = $profile->deleteProfilePhoto();
  echo $result;
}

if ($profile->getProfilePhoto() != '') {
  echo '<img src="' . $profile->getProfilePhoto() . '">';
}
?>

<?php include_once("footer.php");?>
