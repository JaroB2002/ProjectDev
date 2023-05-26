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
        <div class="hidden md:flex">
            <a href="logout.php" class="p-3 px-6 pt-2 font-bold text-white bg-fadedpurple rounded-full baseline">Log out</a>
        </div>
        <div class="md:hidden">
            <button class="focus:outline-none flex flex-row gap-5" onclick="toggleNavMenu()">
                <svg class="h-6 w-6 text-offblack" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
                <p class="font-semibold">Navigation</p>
            </button>
        </div>
    </div>
    <div class="hidden md:hidden" id="navMenu">
        <a href="dashboard.php" class="block p-2 font-bold hover:text-fadedpurple">Home</a>
        <a href="editprofile.php" class="block p-2 font-bold hover:text-fadedpurple">Profile</a>
        <a href="uploadPrompt.php" class="block p-2 font-bold hover:text-fadedpurple">Upload</a>
        <a href="approvePrompt.php" class="block p-2 text-fadedblue font-bold hover:text-fadedpurple">Approve</a>
        <a href="logout.php" class="block p-2 text-white bg-fadedpurple font-bold">Log out</a>
    </div>
</nav>

<script>
    function toggleNavMenu() {
        const navMenu = document.getElementById('navMenu');
        navMenu.classList.toggle('hidden');
    }
</script>
</body>
</html>
