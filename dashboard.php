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


    //$allApprovedPrompts = Prompt::getAllApproved();
    $filter = Prompt::filter($pricing, $type, $date, $search);
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

                    <div>
                        <a href="#" data-id="<?php echo $prompt['id']; ?>" class="like">Like</a>
                        <span class='likes' id="likes">people like this</span> 
                    </div>
                </div>
        <?php endforeach; ?>
    </article>
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
<body>
	<h1>Dashboard</h1>
	
	<form method="post" enctype="multipart/form-data">
		<label for="file">Selecteer een afbeelding:</label>
		<input type="file" name="file" id="file"><br><br>
		<input type="submit" name="submit" value="Uploaden">
	</form>

	<?php
require_once('classes/ProfilePic.php');

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if file was uploaded without errors
    if(isset($_FILES["profile_picture"]) && $_FILES["profile_picture"]["error"] == 0){
        $allowed = array("jpg" => "image/jpg", "jpeg" => "image/jpeg", "png" => "image/png");
        $filename = $_FILES["profile_picture"]["name"];
        $filetype = $_FILES["profile_picture"]["type"];
        $filesize = $_FILES["profile_picture"]["size"];

        // Verify file extension
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        if(!array_key_exists($ext, $allowed)) die("Error: Please select a valid file format.");

        // Verify file size - 5MB maximum
        $maxsize = 10 * 1024 * 1024; // 10MB
        if($filesize > $maxsize) die("Error: File size is larger than the allowed limit.");

        // Verify MYME type of the file
        if(in_array($filetype, $allowed)){
            // Check if file exists
            if(file_exists("upload/" . $_FILES["profile_picture"]["name"])){
                // Remove the line that displays the error message for existing file
                // echo $_FILES["profile_picture"]["name"] . " is already exists.";
            } else{
                $user_id = $_POST['user_id'];
                $stmt = $conn->prepare("UPDATE profile_pictures SET filename = :filename WHERE user_id = :user_id");
                $stmt->bindParam(':user_id', $user_id);
                $stmt->bindParam(':filename', $filename);
                $button_text = "Upload";

                if($stmt->execute()){
                    move_uploaded_file($_FILES["profile_picture"]["tmp_name"], "upload/" . $_FILES["profile_picture"]["name"]);
                    echo "Your profile picture was uploaded successfully.";
                    header("Refresh:2");
                } else {
                    echo "Error: There was a problem uploading your file. Please try again."; 
                }
            }
        } else{
            echo "Error: Please select a valid file format.";
        }
    } else{
        echo "Error: " . $_FILES["profile_picture"]["error"];
    }
    // delete profile picture
// Delete profile picture
if(isset($_POST['delete_profile_picture'])){
    // Get user ID
    $user_id = $_POST['user_id'];

    // Fetch the filename of the profile picture from the database
    $stmt = $conn->prepare("SELECT filename FROM profile_pictures WHERE user_id = :user_id");
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $filename = $row['filename'];

    // Delete the profile picture file from the server
    $file_path = "upload/" . $filename;
    if(file_exists($file_path)){
        unlink($file_path);
    }

    // Update the filename in the database to empty string or null, depending on your database schema
    $stmt = $conn->prepare("UPDATE profile_pictures SET filename = '' WHERE user_id = :user_id");
    $stmt->bindParam(':user_id', $user_id);
    if($stmt->execute()){
        echo "Your profile picture was deleted successfully.";
        header("Refresh:2");
    } else {
        echo "Error: There was a problem deleting your file. Please try again."; 
    }
}
}

// Get user's profile picture filename
$user_id = 1; // change this to the user_id of the user whose profile picture you want to display
$stmt = $conn->prepare("SELECT filename FROM profile_pictures WHERE user_id = :user_id");
$stmt->bindParam(':user_id', $user_id);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if (isset($_FILES["profile_picture"]["error"]) && $_FILES["profile_picture"]["error"] !== false) {
    echo "Error: " . $_FILES["profile_picture"]["error"];
} else {
    echo "Error: There was a problem uploading your file. Please try again."; 
}
?>

<!-- Display user's profile picture -->
<img src="upload/<?php echo $filename; ?>" alt="Profile Picture">
<img src="delete/<?php echo $filename; ?>" alt="Delete Picture">

<!-- Form to upload profile picture -->
<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
    <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
    <input type="file" name="profile_picture">
    <input type="submit" value="Upload">
    <input type="submit" value="Delete"> 
</form>

<script src="js/like.js"></script>