<?php
     include_once("bootstrap.php");
      session_start();
      
      $followPrompts = Prompt::getAllFollowing();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Following</title>

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
<body>
<h1 class="text-2xl mx-5 font-semibold mt-7" > Following</h1>
    <article class="text-white flex flex-wrap">
        <?php foreach($followPrompts as $prompt): ?>
            <div class="my-5 mx-5 bg-stone-700 px-8 py-8 rounded max-w-sm">
                <h3 class="font-semibold text-xl text-fadedpurple"><?php echo htmlspecialchars($prompt["name"]); ?></h3>
                <a href="user.php?id=<?php echo $prompt["email"]; ?>">
                    <p class="mb-5 text-lg text-offwhite hover:text-fadedpurple"><strong>User:</strong> <?php echo $prompt["email"]; ?></p>
                </a>
                <img class="mb-5" src="<?php echo htmlspecialchars($prompt["image"]); ?>" alt="input image">
                <p class="mb-3 text-lg text-offwhite">  <strong>description: </strong> <?php echo htmlspecialchars($prompt["description"]);?></p>
                <p class="mb-3 text-lg text-offwhite"> <strong>type: </strong> <?php echo htmlspecialchars($prompt["type"])?> </p>
                <p class="mb-3 text-lg text-offwhite"><strong>price: </strong> <?php echo htmlspecialchars($prompt["price"]);?></p>
            </div>
        <?php endforeach; ?>
    </article>
</body>
</html>