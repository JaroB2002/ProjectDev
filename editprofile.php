<?php
    include_once("bootstrap.php");
    
    session_start();
    $username = $_SESSION['username'];
    
    $user = new User();
    $user->setEmail($username);
    $userDetails = $user->getUserDetails();
    $biography = $userDetails['biography'];

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
?>
<!DOCTYPE html>
<html lang="en">
<head>
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
    <!--mx margin left right, my margin top bottom-->
    <nav class="relative container mx-auto p-6 bg-offgrey rounded-md">
    <!--flex container-->
    <div class="flex items-center justify-between">
        <!--menu-->
        <div class="hidden md:flex space-x-6">
            <a href="dashboard.php" class="text-lg font-bold hover:text-fadedpurple">Home</a>
            <a href="editprofile.php" class="text-lg font-bold hover:text-fadedpurple">Profile</a>
            <a href="uploadPrompt.php" class="text-lg font-bold hover:text-fadedpurple">Upload</a>
        </div>
        <!--button-->
        <a href="logout.php" class="hidden md:block p-3 px-6 pt-2 text-white bg-fadedpurple rounded-full baseline">Log out</a>
    </div>
    </nav>
    <main class="ml-10 mt-10">
      <h1 class="text-5xl mb-10 font-semibold">Profile</h1>
      <h2 class="text-3xl text-fadedblue mb-5">Hi it's <?php echo htmlspecialchars($username); ?>!</h2>
      <div class="bg-offwhite rounded w-96 p-5">
      <h3 class="text-2xl mb-3">My biography:</h3>
      <p class="text-8sm mb-3"><?php echo htmlspecialchars($biography); ?></p> 
      </div>
      <form action="#" method="post">
          <div>
            <label class="underline text-fadedblue text-1xl" for="biography">Update your biography here: </label>
            <input id="biography" name="biography" type="text" placeholder="Biography">
          </div>
          <div>
          <button class="bg-fadedpurple px-5 py-3 mt-5 rounded font-semibold text-white" type="submit" name="updateProfile">Add bio</button>
          </div>
      </form>
    </main>
</body>
</html>