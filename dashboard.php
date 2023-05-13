<?php 
include_once("bootstrap.php");

    session_start();
    
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


    /*verhuizen naar functie filter in class prompts
    if(!empty($_POST["search"])){
        $conn = Db::getInstance();
        $statement = $conn->prepare("SELECT * FROM `prompts` WHERE name LIKE CONCAT('%', :title, '%')");
        $statement->bindValue(":title", $_POST["search"]);
        $statement->execute();
        $search = $statement->fetchAll(PDO::FETCH_ASSOC);

        if (empty($search)) {
            echo "No results found.";
        }
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
            <input name="search" value="search" type="text" placeholder="Search by title">
        </div>
        <article class="flex flex-row">
        <div class="mr-10">
            <h2 class="text-xl font-semibold mt-7 mb-2">Pricing</h2>
            <select name="price">
                <option value="all">All</option>
                <option value="paid">Paid</option>
                <option value="free">Free</option>
            </select>
        </div>
        <div class="mr-10">
            <h2 class="text-xl font-semibold mt-7 mb-2">Category</h2>
            <select name="type">
                <option value="all">All</option>
                <option value="lineArt">Line art</option>
                <option value="realistic">Realistic</option>
                <option value="cartoon">Cartoon</option>
            </select>
        </div>
        <div>
            <h2 class="text-xl font-semibold mt-7 mb-2">Date</h2>
            <select name="date">
                <option value="all">All</option>
                <option value="new">New</option>
                <option value="old">Old</option>
            </select>
        </div>
        </article>
        <div>
            <button class="bg-fadedblue text-white px-5 py-2 mt-5 rounded font-semibold text-lg" type="submit" value="Search">Search</button>
        </div>
    </form>
    <h2 class="text-3xl font-semibold mt-5">Prompt overview</h2>
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
                    <button class="p-3 px-6 pt-2 text-white bg-fadedpurple rounded-full baseline font-semibold text-lg" class="report-button" data-prompt-id="<?php echo $prompt["id"]; ?>">Report user</button>
                    <button id="reportButton" data-promptid="<?php echo $prompt['id'];?>" class="p-3 px-6 pt-2 text-white bg-fadedpurple rounded-full baseline font-semibold text-lg">Report Prompt</button>
                </div>
        <?php endforeach; ?>
    </article>
    <script>
        let report = document.querySelectorAll("#reportButton");
            //add eventlistener to every button

            report.forEach(function(button){
                button.addEventListener("click", reportPrompt);
            });

        //report.addEventListener("click", reportPrompt);
        function reportPrompt(event){
            console.log(event);
            event.preventDefault();
            console.log("reportPrompt");
            /*hoe juiste promptid vinden?*/
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
                console.log("test");
                item.innerHTML = "Reported prompt";
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


<h2>Delete Your Account</h2>
<p>Please confirm your email address to delete your account and all associated data.</p>
<form method="post" action="dashboard.php">
  <label for="email">Email:</label>
  <input type="email" name="email" required>
  <br>
  <label for="confirm_delete">Confirm your email address:</label>
  <input type="email" name="confirm_delete" required>
  <br>
  <p>To delete your account, type "delete my account" below:</p>
  <input type="text" name="delete_confirmation" required>
  <br>
  <button type="submit" name="delete_account" onclick="return confirm('Are you sure you want to delete your account? This action cannot be undone.');">Delete Account</button>
</form>

<h2>Download Your Data</h2>
<p>You can download a copy of your data by clicking the button below.</p>
<a href="download_data.php">Download My Data</a>






<!DOCTYPE html>
<html>
<head>
	<title>Dashboard</title>
</head>



</html>
<body class="mx-1">
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<?php include_once("footer.php");?>

</body>
</html>

