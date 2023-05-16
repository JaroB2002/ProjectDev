<?php 
include_once("bootstrap.php");

    session_start();

    $report = new Report();
    /*if($report->reportCountPrompt()){
        $reportCount = $report->deletePrompt(true);
        echo "done";
    };*/
    
    //niet via url binnen raken
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

    //var_dump($search);
    

    //$allApprovedPrompts = Prompt::getAllApproved();
    $filter = Prompt::filter($pricing, $type, $date, $search);
    $likes = new Prompt();

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

<body class="mx-10">
    <nav class="relative container mx-auto p-6 bg-offwhite rounded-md">
    <div class="flex items-center justify-between">
        <div class=" md:flex space-x-6">
            <a href="dashboard.php" class="text-lg font-bold hover:text-fadedpurple">Home</a>
            <a href="editprofile.php" class="text-lg font-bold hover:text-fadedpurple">Profile</a>
            <a href="uploadPrompt.php" class="text-lg font-bold hover:text-fadedpurple">Upload</a>
        </div>
        <a href="logout.php" class="hidden md:block p-3 px-6 pt-2 text-white bg-fadedpurple rounded-full baseline font-semibold text-lg">Log out</a>
    </div>
    </nav>
    <h1 class="text-4xl font-semibold">Homepage</h1>
    <!--search-->
    <form method="get" action=""> <!--veranderd nr get-->
        <div>
            <h2 class="text-xl font-semibold mt-7">Filter on title</h2>
            <input name="search" type="text" placeholder="Search by title">
        </div>
        <article class="flex flex-row">
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
        <div>
            <button class="bg-fadedblue text-white px-5 py-2 mt-5 rounded font-semibold text-lg" type="submit" value="Search">Search</button>
        </div>
    </form>
    <h2 class="text-3xl font-semibold mt-5">Prompt overview</h2>
    <?php if($filter == null): ?>
        <p class="text-xl font-semibold mt-5 text-fadedpurple">No prompts found</p>
    <?php endif; ?>
    <article class="flex flex-wrap">
        <?php foreach ($filter as $prompt): ?>
                <div class="my-5 bg-offblack mr-10 px-8 py-8 rounded max-w-sm">
                    <h3 class="font-semibold text-xl text-fadedpurple"><?php echo htmlspecialchars($prompt["name"]); ?></h3>
                    <a href="user.php?id=<?php echo $prompt["email"]; ?>">
                        <p class="mb-5 text-lg text-offwhite hover:text-fadedpurple"><strong>User:</strong> <?php echo $prompt["email"]; ?></p>
                    </a>
                    <img class="mb-5" src="<?php echo htmlspecialchars($prompt["image"]); ?>" alt="input image">
                    <p class="mb-3 text-lg text-offwhite"><strong>Description:</strong> <?php echo htmlspecialchars($prompt["description"]); ?></p>
                    <p class="mb-3 text-lg text-offwhite"><strong>Type:</strong> <?php echo htmlspecialchars($prompt["type"]); ?></p>
                    <p class="mb-3 text-lg text-offwhite"><strong>Price:</strong> <?php echo htmlspecialchars($prompt["price"]); ?></p>
                    <button id="reportButton" data-promptid="<?php echo $prompt['id'];?>" class="p-3 px-6 pt-2 text-white bg-fadedpurple rounded-full baseline font-semibold text-lg">Report Prompt</button>
                    <div>
                        <button class="report-button" data-prompt-id="<?php echo $prompt["id"]; ?>" data-error-id="<?php echo 'error-' . $prompt["id"]; ?>">Report user</button>
                        <?php if (isset($errorMessage) && $_GET["buy"] == $prompt["id"]): ?>
                            <div class="error-message text-red-500" id="<?php echo 'error-' . $prompt["id"]; ?>">
                                <?php echo $errorMessage; ?>
                            </div>
                        <?php endif; ?>
                        <form action="" class="mt-3">
                            <button class="bg-fadedpurple px-5 py-3 rounded font-semibold ml-5" type="submit" name="buy" value="<?php echo $prompt['id'];?>">Buy</button>
                        </form>
                        <div class="mt-5">
                        <a href="#" data-id="<?php echo $prompt['id']; ?>" class="like bg-fadedpurple px-5 py-3 rounded font-semibold ml-5 mt-5">Like <span class='likes' id="likes"><?php echo $likes->getLikes($prompt['id']) ?> people like this</span> </a>
                    </div>
                    </div>
                </div>
                
        <?php endforeach; ?>
    </article>
    <?php
include_once(__DIR__ . "/classes/Comment.php");
$allComments = Comment::getAll(3);
//var_dump($allComments);
?>
<div class="post">  
  <div class="post__comments">
      <div class="post__comments__form">
        <input type="text" id="commentText" placeholder="What's on your mind">
        <a href="#" class="btn" id="btnAddComment" data-postid="3">Add comment</a>
      </div>  
    
      <ul class="post__comments__list">
        <?php foreach($allComments as $c): ?>
          <li><?php echo $c['text']; ?></li>
        <?php endforeach; ?>
      </ul>
  </div>

  
</div>
  <script src="index.css"></script>
  <script src="app.js"></script>
  <script>
        let report = document.querySelectorAll("#reportButton");

        report.forEach(function(button){
            button.addEventListener("click", reportPrompt);
        });

        function reportPrompt(event){
        console.log(event);
        event.preventDefault();
        console.log("reportPrompt");
        let promptid = event.target.dataset.promptid;
        console.log(promptid);
        let formData = new FormData();
        formData.append("promptid", promptid);

        let item = this;
        fetch("ajax/reportPrompt.php", {
            method: "POST",
            body: formData
        })
        .then(function(response){
            return response.json();
        })
        .then(function(result){
            if(result.status == "success"){
                item.innerHTML = result.message;
            }
        });
        }
    </script>
</body>
</html>
<?php
// START DELETE ACCOUNT CODE

// Establish database connection
//$conn = new PDO('mysql:host=localhost;dbname=demo', 'root', '');
$conn = Db::getInstance();

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
}
?>
<?php include_once("footer.php");?>
