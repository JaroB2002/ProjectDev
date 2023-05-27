<?php 
include_once("bootstrap.php");

    session_start();

    $report = new Report();
    
    if(!isset($_SESSION['username'])){
        header("location: index.php");
    } 
    //price filter
    if(!empty($_GET['price'])){
        $pricing = $_GET['price'];
    }
    else{
        $pricing = "all";
    }
    //type filter
    if(!empty($_GET['type'])){
        $type = $_GET['type'];
    }
    else{
        $type = "all";
    }
    //date filter
    if(!empty($_GET['date'])){
        $date = $_GET['date'];
    }
    else{
        $date = "all";
    }
    //text filter
    if(!empty($_GET['search'])){
        $search = $_GET['search'];
    }
    else{
        $search = "all";
    }

    $filter = Prompt::filter($pricing, $type, $date, $search);
    $likes = new Prompt();
    $favorites = new Favorite();

    try {
        $user = new User();
        if(isset($_GET['buy'])){
            $canBuy = $user->checkIfCanBuy();
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

    // Handle prompt rating
    /*if (isset($_POST['rating']) && isset($_POST['promptId'])) {
        $rating = $_POST['rating'];
        $promptId = $_POST['promptId'];
    
        $prompt = new Prompt();
        $prompt->ratePrompt($promptId, $rating);
    }*/

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>

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
    <header class="bg-gray-900">
        <nav><?php include_once("navigation.php")?></nav>
        <div class="flex flex-col justify-center items-center py-20 gap-10">
            <img src="uploads/homeheader.jpg" alt="header" class="rounded-full w-96 h-96">
            <img src="uploads/PromptBaes.svg" alt="header" class="w-96 h-35" >
            <h4 class="text-offgrey text-xl max-w-sm text-center">Promptbaes, the perfect webapp for finding your prompt. Thanks for joining our community, thanks for being a promptbae!</h4>
        </div>
    </header>
    <form method="get" action="" class="bg-fadedpurple py-10"> 
        <div class="flex justify-center items-center flex-col">
            <h2 class="text-xl font-semibold">Search title or tag</h2>
            <input class="max-w-sm" name="search" type="text" placeholder="Search by title or tag">
        </div>
        <article class="flex flex-row justify-center">
        <div class="mr-10">
            <h2 class="text-xl font-semibold mt-7 mb-2">Pricing</h2>
            <select name="price">
                <option value="all" <?php if ($pricing == "all") echo "selected";?>>All</option>
                <option value="paid" <?php if ($pricing == "paid") echo "selected";?>>Paid</option>
                <option value="free" <?php if ($pricing == "free") echo "selected";?>>Free</option>
            </select>
        </div>
        <div class="mr-10">
            <h2 class="text-xl font-semibold mt-7 mb-2">Category</h2>
            <select name="type">
                <option value="all" <?php if ($type == "all") echo "selected"; ?>>All</option>
                <option value="lineArt" <?php if ($type == "lineArt") echo "selected"; ?>>Line art</option>
                <option value="realistic" <?php if ($type == "realistic") echo "selected"; ?>>Realistic</option>
                <option value="cartoon" <?php if ($type == "cartoon") echo "selected"; ?>>Cartoon</option>
            </select>
        </div>
        <div>
            <h2 class="text-xl font-semibold mt-7 mb-2">Date</h2>
            <select name="date">
                <option value="all" <?php if ($date == "all") echo "selected"; ?>>All</option>
                <option value="new" <?php if ($date == "new") echo "selected"; ?>>New</option>
                <option value="old" <?php if ($date == "old") echo "selected"; ?>>Old</option>
            </select>
        </div>
        </article>
        <div class="flex justify-center">
            <button class="bg-fadedblue text-white px-5 py-2 mt-5 rounded font-semibold text-lg" type="submit" value="Search">Search</button>
        </div>
    </form>
    <h2 class="text-3xl font-semibold mt-5 ml-10">Prompt overview</h2>
    <?php if ($filter == null): ?>
        <p class="text-xl font-semibold mt-5 text-fadedpurple">No prompts found</p>
    <?php endif; ?>
    <article class="flex flex-wrap justify-center mt-5 pt-5">
        <?php foreach ($filter as $prompt): ?>
            <div class="my-5 bg-offgrey mr-10 px-8 py-8 rounded max-w-md">
                <h3 class="font-semibold text-xl text-fadedpurple"><?php echo htmlspecialchars($prompt["name"]); ?></h3>
                <a href="user.php?id=<?php echo htmlspecialchars($prompt["email"]); ?>">
                    <p class="mb-5 text-lg text-offblack hover:text-fadedpurple"><strong>User:</strong> <?php echo $prompt["email"]; ?></p>
                </a>
                <img class="object-none h-96 w-96 mb-5" src="<?php echo htmlspecialchars($prompt["image"]); ?>" alt="input image">
                <p class="mb-5 text-lg text-offblack"><strong>Description:</strong> <?php echo htmlspecialchars($prompt["description"]); ?></p>
                <p class="mb-5 text-lg text-offblack"><strong>Type:</strong> <?php echo htmlspecialchars($prompt["type"]); ?></p>
                <p class="mb-5 text-lg text-offblack"><strong>Price:</strong> <?php echo htmlspecialchars($prompt["price"]); ?></p>
                <!--<form method="post" class="mt-4">
                    <input type="hidden" name="promptId" value="<?php echo htmlspecialchars($prompt['id']); ?>">
                    <div class="flex items-center">
                        <label for="rating" class="mb-5 text-lg text-offblack">Rate prompt:</label>
                        <select name="rating" id="rating" class="border border-gray-300 rounded p-1">
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                        </select>
                        <button type="submit" class="ml-2 bg-fadedpurple text-white font-semibold px-4 py-2 rounded">Rate</button>
                    </div>
                </form>-->
                <div>
                    <?php if (isset($errorMessage) && $_GET["buy"] == $prompt["id"]): ?>
                        <div class="error-message text-red-500" id="<?php echo 'error-' . $prompt["id"]; ?>">
                            <?php echo htmlspecialchars($errorMessage); ?>
                        </div>
                    <?php endif; ?>
                    <form action="" class="mt-3">
                        <button class="bg-gray-900 text-offwhite py-3 rounded font-semibold w-full" type="submit" name="buy" value="<?php echo $prompt['id']; ?>">Buy</button>
                    </form>
                    <div class="flex flex-row items-center justify-between">
                        <div class="mt-5">
                            <a href="#" data-id="<?php echo $prompt['id']; ?>" class="like text-fadedpurple rounded font-semibold mt-5"><?php if (Like::getAll($prompt['id']) == true) { echo 'Unlike '; } else { echo 'Like ';}?> <span class='likes' id="likes"><?php echo $likes->getLikes($prompt['id']) ?> people like this</span> </a>
                        </div>
                        <div class="mt-8">
                            <a href="#" data-id="<?php echo $prompt['id']; ?>" class="favorite bg-fadedpurple text-offwhite px-5 py-3 rounded font-semibold mt-5"><?php if (Favorite::getAll($prompt['id']) == true) { echo 'Remove from favorites '; } else { echo 'Add to favorites ';}?></a>
                        </div>
                    </div>
                    <form action="" class="mt-10 flex rounded justify-between">
                        <input class="text" type="text" placeholder="Write a comment".>
                        <button class="comments text-offwhite bg-fadedblue px-5 py-3 rounded font-semibold ml-5" type="submit" name="comment" data-id="<?php echo $prompt['id']; ?>">Comment</button>
                    </form>
                    <?php $comments = Comment::getAll($prompt['id']); ?>
                    <ul class="list mt-5 text-offwhite bg-fadedblue pt-3 pb-3 pl-5 pr-5 rounded">
                        <?php foreach ($comments as $comment): ?>
                            <li> <strong> <?php echo htmlspecialchars($comment['userId']); ?></strong> <br> <?php echo htmlspecialchars($comment['comment']);?></li> <br>               
                        <?php endforeach; ?>
                    </ul>
                    <button id="reportButton" data-promptid="<?php echo $prompt['id']; ?>" class="pt-2 text-offblack font-semibold text-lg">Report Prompt</button>
                </div>
            </div>
        <?php endforeach; ?>
    </article>
    <footer><?php include_once("footer.php");?></footer>
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
    <script src="js/comment.js"></script>
    <script src="js/reportPrompt.js"></script>
</body>
</html>
<?php
//IN COMMENTS GEZET WANT MOET NOG GECLEAND WORDEN*/
// START DELETE ACCOUNT CODE

// Establish database connection
//$conn = new PDO('mysql:host=localhost;dbname=demo', 'root', '');
/*$conn = Db::getInstance();

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate and sanitize email input to prevent SQL injection
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);

    // Get user data from database
    $stmt = $conn->prepare('SELECT * FROM users WHERE email = :email');
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Check if account was found
    if ($stmt->rowCount() == 0) {
        echo "Account not found.";
    } else {
        // Confirm user's identity
        $confirm = filter_input(INPUT_POST, 'confirm_delete', FILTER_SANITIZE_EMAIL);
        if ($confirm != $user['email']) {
            echo "Email confirmation does not match.";
        } else {
            // Delete all data related to user
            $stmt = $conn->prepare('DELETE FROM users WHERE email = :email');
            $stmt->bindParam(':email', $email);
            $stmt->execute();

            // Confirm deletion
            if ($stmt->rowCount() > 0) {
                // Log out user and redirect to index.php
                session_start();
                session_unset();
                session_destroy();
                header("Location: index.php");
                exit();
            } else {
                echo "Account was not deleted.";
            }
        }
    }
}*/
?>

