<?php
    //include_once("bootstrap.php");
    include_once(__DIR__ . "/classes/User.php");
    include_once(__DIR__ . "/classes/Db.php");
    require_once("vendor/autoload.php");
    //Zolang er geen namespace is classes hier appart includen
    
    if(!empty($_POST)){
      try{
        $user = new User();
        $user->setEmail($_POST["email"]);
        $user->setPassword($_POST["password"]);
        // Check if email is available before saving
        if ($user->checkMailAvailable()) {
          $user->save();
          $user->sendMail();
          header("Location: index.php");
        } else {
          $error = "Email was already taken. Registration failed.";
        }
      }
      catch (Throwable $e){
        $error = $e->getMessage();
      }
    }
?>
<!DOCTYPE html>
<html lang="en" >
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
  <title>Register</title>
  <link rel="stylesheet" href="./style.css">
  <script src="https://cdn.tailwindcss.com?plugins=forms,typography,aspect-ratio,line-clamp"></script>

<body>

<div class="flex min-h-full items-center justify-center px-4 py-12 sm:px-6 lg:px-8">
  <div class="w-full max-w-md space-y-8">
    <div>
      <img class="mx-auto h-12 w-auto" src="uploads/PromptBaes.svg" alt="PromptBaes">
      <h2 class="mt-6 text-center text-3xl font-bold tracking-tight text-gray-900">Register your account</h2>
      <p class="mt-2 text-center text-sm text-gray-600">
        Or
        <a href="index.php" class="font-medium text-indigo-600 hover:text-indigo-500">login now</a>
      </p>
    </div>
    <?php if (isset($error)) : ?>
    <div class="error-message" style="color: red;">
        <?php echo $error; ?>
    </div>
    <?php endif; ?>
    <form class="mt-8 space-y-6" action="#" method="POST">
      <input type="hidden" name="remember" value="true">
      <div class="-space-y-px rounded-md shadow-sm">
        <div>
          <label for="email" class="sr-only">Email</label>
          <span id="feedback"></span>
          <input id="email" name="email" type="text" autocomplete="email" onchange="checkMail()" required class="relative block w-full rounded-t-md border-0 py-1.5 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:z-10 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" placeholder="Email">
        </div>
        <div>
          <label for="password" class="sr-only">Password</label>
          <input id="password" name="password" type="password" autocomplete="current-password" required class="relative block w-full rounded-b-md border-0 py-1.5 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:z-10 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" placeholder="Password">
        </div>
      </div>

      <div class="flex items-center justify-between">
        <div class="flex items-center">
          <input id="remember-me" name="remember-me" type="checkbox" class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-600">
          <label for="remember-me" class="ml-2 block text-sm text-gray-900">Remember me</label>
        </div>

        <div class="text-sm">
          <a href="#" class="font-medium text-indigo-600 hover:text-indigo-500">Forgot your password?</a>
        </div>
      </div>

      <div>
        <button type="submit" class="group relative flex w-full justify-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
          <span class="absolute inset-y-0 left-0 flex items-center pl-3">
            <svg class="h-5 w-5 text-indigo-500 group-hover:text-indigo-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
              <path fill-rule="evenodd" d="M10 1a4.5 4.5 0 00-4.5 4.5V9H5a2 2 0 00-2 2v6a2 2 0 002 2h10a2 2 0 002-2v-6a2 2 0 00-2-2h-.5V5.5A4.5 4.5 0 0010 1zm3 8V5.5a3 3 0 10-6 0V9h6z" clip-rule="evenodd" />
            </svg>
          </span>
          Register
        </button>
      </div>
      <div>
        <button type="submit" class="group relative flex w-full justify-center rounded-md bg-indigo-200 px-3 py-2 text-sm font-semibold text-white hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
          <span class="absolute inset-y-0 left-0 flex items-center pl-3">
              <path fill-rule="evenodd" d="M10 1a4.5 4.5 0 00-4.5 4.5V9H5a2 2 0 00-2 2v6a2 2 0 002 2h10a2 2 0 002-2v-6a2 2 0 00-2-2h-.5V5.5A4.5 4.5 0 0010 1zm3 8V5.5a3 3 0 10-6 0V9h6z" clip-rule="evenodd" />
            </svg>
          </span>
          Or log in
        </button>
      </div>

    </form>
  </div>
</div>
</div>
<script src="js/checkMail.js"></script> 
</body>
</html>
