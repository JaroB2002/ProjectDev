<?php 
include_once("bootstrap.php");

$error_message = ""; // Variabele voor foutmelding

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $user = new User();

    $email = $_POST["username"];
    $password = $_POST["password"];

    $user->setEmail($email);
    $user->setPassword($password);

    if ($user->canLogin($email, $password)) {
        session_start();
        $_SESSION['username'] = $email;
        header("Location: dashboard.php");
    } else {
        $error_message = "Ongeldig e-mailadres of wachtwoord."; // Foutmelding toewijzen
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            clifford: '#da373d',
          }
        }
      }
    }
  </script>
  <style type="text/tailwindcss">
    @layer utilities {
      .content-auto {
        content-visibility: auto;
      }
    }
  </style>
  <title>Login</title>
  <link rel="stylesheet" href="./style.css">
  <script src="https://cdn.tailwindcss.com?plugins=forms,typography,aspect-ratio,line-clamp"></script>
</head>
<body>
<header>
  <a href="#" class="loggedIn">
    <?php if(isset($_SESSION['username'])): ?>
      <h3 class="user--name"><?php echo $_SESSION['username']; ?></h3>
    <?php endif; ?>
  </a>
</header>

<div class="relative md:h-screen flex overflow-hidden">
  <div class="flex flex-col sm:flex-row items-center md:items-start sm:justify-center flex-auto min-w-0 bg-white md:my-0 my-8">
    <div class="md:flex md:items-center md:justify-center w-full  md:h-full sm:rounded-lg md:rounded-none bg-white px-6">
      <div class="max-w-md w-full mx-auto">
        <div>
          <img class="mx-auto h-12 w-auto" src="PromptBaes.svg" alt="Logo PromptBaes">
          <h2 class="mt-6 text-center text-3xl font-bold tracking-tight text-gray-900">Log in</h2>
          <p class="mt-2 text-center text-sm text-gray-600"> Or <a href="register.php" class="font-medium text-indigo-600 hover:text-indigo-500">sign up here</a>
          </p>
        </div>
        <form class="mt-8 space-y-6" action="#" method="POST">
          <input type="hidden" name="remember" value="true">
          <div class="-space-y-px rounded-md shadow-sm">
            <div>
              <label for="username" class="sr-only">Email address</label>
              <input id="username" name="username" type="text" autocomplete="username" required class="relative block w-full rounded-t-md border-0 py-1.5 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:z-10 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" placeholder="Email address">
            </div>
            <div>
              <label for="password" class="sr-only">Wachtwoord</label>
              <input id="password" name="password" type="password" autocomplete="current-password" required class="relative block w-full rounded-b-md border-0 py-1.5 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:z-10 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" placeholder="Password">
            </div>
          </div>
          
          <?php if (!empty($error_message)): ?>
            <div class="text-red-500 mt-2"><?php echo $error_message; ?></div>
          <?php endif; ?>
          
          <div class="flex items-center justify-between">
            <div class="flex items-center">
              <input id="remember-me" name="remember-me" type="checkbox" class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-600">
              <label for="remember-me" class="ml-2 block text-sm text-gray-900">Onthoud mijn gegevens</label>
            </div>
            <div class="text-sm">
              <a href="reset_password.php" class="font-medium text-indigo-600 hover:text-indigo-500">Wachtwoord vergeten?</a>
            </div>
          </div>
          <div>
            <button type="submit" class="group relative flex w-full justify-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
              <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                <svg class="h-5 w-5 text-indigo-500 group-hover:text-indigo-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                  <path fill-rule="evenodd" d="M10 1a4.5 4.5 0 00-4.5 4.5V9H5a2 2 0 00-2 2v6a2 2 0 002 2h10a2 2 0 002-2v-6a2 2 0 00-2-2h-.5V5.5A4.5 4.5 0 0010 1zm3 8V5.5a3 3 0 10-6 0V9h6z" clip-rule="evenodd" />
                </svg>
              </span> Log in
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
  <div class="sm:w-2/4 hidden md:flex">
    <img class="w-full object-cover" src="https://images.pexels.com/photos/3182750/pexels-photo-3182750.jpeg" alt="">
  </div>
</div>

</body>
</html>
