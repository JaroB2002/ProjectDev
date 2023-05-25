<?php
//https://play.tailwindcss.com/no3sgy5KXk
?>
<!DOCTYPE html>
<html lang="en">
<head>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" integrity="sha512-PmkEJHmZvcwdeUDzL5Z+K9QGQxxbivn5nMxvM5rPLnAR">
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
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
<nav class="relative container mx-auto p-6 bg-offgrey rounded-md">
    <div class="flex items-center justify-between">
      <div class="hidden md:flex space-x-6">
        <a href="dashboard.php" class="text-lg font-bold hover:text-fadedpurple">Home</a>
        <a href="editprofile.php" class="text-lg font-bold hover:text-fadedpurple">Profile</a>
        <a href="uploadPrompt.php" class="text-lg font-bold hover:text-fadedpurple">Upload</a>
        <a href="approvePrompt.php" class="text-lg text-fadedblue font-bold hover:text-fadedpurple">Approve</a>
      </div>
      <a href="logout.php" class="hidden md:block p-3 px-6 pt-2 text-white bg-fadedpurple rounded-full baseline">Log out</a>
    </div>
  </nav>
</body>
</html>
