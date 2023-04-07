<?php 

require 'vendor/autoload.php';

if(!empty($_POST)){
  $email = $_POST['username'];
  $options = [
      'cost' => 14,
  ];
  $password = password_hash($_POST['password'], PASSWORD_DEFAULT, $options);
  //echo $password;

  $conn = new PDO('mysql:host=localhost;dbname=demo', "root", "root");

  // Check if email already exists
  $query = $conn->prepare("SELECT COUNT(*) FROM users WHERE email = :email");
  $query->bindValue(":email", $email);
  $query->execute();
  $count = $query->fetchColumn();

  if ($count > 0) {
      echo "Error: This email already exists.";
  } else {
      // Insert new user into database
      $query = $conn->prepare("INSERT INTO users (email, password) VALUES(:email, :password)");
      $query->bindValue(":email", $email);
      $query->bindValue(":password", $password);
      $query->execute();
      echo "User registered successfully!";

      $email = new \SendGrid\Mail\Mail();
        $email->setFrom("yadina.moreira@gmail.com", "Fred Kroket");
        $email->setSubject("Sending with Twilio SendGrid is Fun");
        $email->addTo("yadina.moreira@gmail.com", "Yadina");
        $email->addContent("text/plain", "and easy to do anywhere, even with PHP");
        $email->addContent(
           "text/html", "<strong>and easy to do anywhere, even with PHP</strong>"
        );

        $options = array(
          'turn_off_ssl_verification' => true
        );

        $sendgrid = new \SendGrid(getenv('SENDGRID_API_KEY'), $options);
        try {
            $response = $sendgrid->send($email);
            print $response->statusCode() . "\n";
            print_r($response->headers());
            print $response->body() . "\n";
            echo "email sent!\n";

        } catch (Exception $e) {
            echo 'Caught exception: '. $e->getMessage() ."\n";
        }
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


// Path: index.php

<div class="flex min-h-full items-center justify-center px-4 py-12 sm:px-6 lg:px-8">
  <div class="w-full max-w-md space-y-8">
    <div>
      <img class="mx-auto h-12 w-auto" src="https://tailwindui.com/img/logos/mark.svg?color=indigo&shade=600" alt="Your Company">
      <h2 class="mt-6 text-center text-3xl font-bold tracking-tight text-gray-900">Register your account</h2>
      <p class="mt-2 text-center text-sm text-gray-600">
        Or
        <a href="index.php" class="font-medium text-indigo-600 hover:text-indigo-500">login now</a>
      </p>
    </div>
    <form class="mt-8 space-y-6" action="#" method="POST">
      <input type="hidden" name="remember" value="true">
      <div class="-space-y-px rounded-md shadow-sm">
        <div>
          <label for="username" class="sr-only">Username</label>
          <input id="username" name="username" type="text" autocomplete="username" required class="relative block w-full rounded-t-md border-0 py-1.5 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:z-10 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" placeholder="Username">
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
<!-- partial -->
  
</body>
</html>
