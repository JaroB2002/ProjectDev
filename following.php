<?php
     include_once("bootstrap.php");
      session_start();
      
      $followPrompts = Prompt::getAllFollowing();

      $likes = new Prompt();
      $favorites = new Favorite();
  
      try {
          $user = new User();
          if(isset($_GET['buy'])){
              $canBuy = $user->checkIfCanBuy();
              /*var_dump($canBuy);*/
              if($canBuy['can_buy'] === '1'){
                  $user->buyPrompt();
                  $user->sellPrompt();
              }else{
                  throw new Exception("You don't have enough credits.");
              }
          }
      } catch (Exception $e) {
          $errorMessage = $e->getMessage();
      }
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

                <div>
                    <?php if (isset($errorMessage) && $_GET["buy"] == $prompt["id"]): ?>
                        <div class="error-message text-red-500" id="<?php echo 'error-' . $prompt["id"]; ?>">
                            <?php echo $errorMessage; ?>
                        </div>
                    <?php endif; ?>
                    <form action="" class="mt-3">
                        <button class="bg-fadedpurple px-5 py-3 rounded font-semibold ml-5" type="submit" name="buy" value="<?php echo $prompt['id'];?>">Buy</button>
                    </form>
                    <div class="mt-5">
                        <a href="#" data-id="<?php echo $prompt['id']; ?>" class="like bg-fadedpurple px-5 py-3 rounded font-semibold ml-5 mt-5"><?php if(Like::getAll($prompt['id']) == true) { echo 'Unlike '; } else { echo 'Like ';}?> <span class='likes' id="likes"><?php echo $likes->getLikes($prompt['id']) ?> people like this</span> </a>
                    </div>
                    <div class="mt-8">
                        <a href="#" data-id="<?php echo $prompt['id']; ?>" class="favorite bg-fadedpurple px-5 py-3 rounded font-semibold ml-5 mt-5"><?php if(Favorite::getAll($prompt['id']) == true) { echo 'remove from favorites '; } else { echo 'add to favorites ';}?></a>
                    </div>
                </div>
            </div>

        <?php endforeach; ?>
    </article>

    <script>
    let links = document.querySelectorAll(".like");
    for (let i = 0; i < links.length; i++) {
        links[i].addEventListener("click", function (e) {
            e.preventDefault();
            //console.log("geklikt😅");

            // Get the clicked <a> element
            let link = e.target;

            // Find the parent <a> element if the clicked element is the <span> element
            if (link.tagName !== "A") {
                link = link.closest("a");
            }

            // Get the <span> element inside the clicked <a> element
            let span = link.querySelector("span.likes");
            console.log(span);

            //krijg de id voor de prompt
            let promptId = this.getAttribute("data-id");

            //post naar database AJAX
            let formData = new FormData();
            formData.append("promptId", promptId);

            fetch("ajax/like.php", {
                method: "POST", // or 'PUT'
                body: formData
            })

            //.then(response => response.json())
            .then(function (response) {
                return response.json();
            })

            .then(function (json) {
                link.innerHTML = json.status + " " +  json.likes + " people like this";
            })

            .catch(function (error) {
                console.log(error);
            });
    });
}
    </script>
      <script src="js/favorite.js"></script>
</body>
</html>