<?php 
include_once("bootstrap.php");
    session_start();
    
    if(isset($_SESSION['username'])){
        //user is logged in
        echo "Welcome " . $_SESSION['username'];
        //queries in sql
    } else{
        //user is not logged in
        header("location: index.php");
    }
    if(!empty($_GET['price'])){
        $pricing = $_GET['price'];
    }
    else{
        $pricing = "all";
    }
    if(!empty($_GET['type'])){
        $type = $_GET['type'];
    }
    else{
        $type = "all";
    }
    

    //$allApprovedPrompts = Prompt::getAllApproved();
    $filter = Prompt::filter($pricing, $type);


    if(!empty($_POST["search"])){
        $conn = Db::getInstance();
        $statement = $conn->prepare("SELECT * FROM `prompts` WHERE name LIKE CONCAT('%', :title, '%')");
        $statement->bindValue(":title", $_POST["search"]);
        $statement->execute();
        $search = $statement->fetchAll(PDO::FETCH_ASSOC);

        if (empty($search)) {
            echo "No results found.";
        }
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
            offgrey: '#D9D9D9',
            offblack: '#D9D9D9',
          }
        }
      }
    }
  </script>
</head>

<body class="mx-10">
    <a href="logout.php" class="font-semibold text-xl">Log out?</a>
    <!--search-->
    <form method="get" action=""> <!--veranderd nr get-->
        <!--<div>
            <h2 class="text-xl font-semibold mt-7">Filter on title</h2>
            <input name="search" type="text" placeholder="Search by title">
        </div>-->
        <div>
            <h2 class="text-xl font-semibold mt-7">Filter on price</h2>
            <select name="price">
                <option value="all">All</option>
                <option value="paid">Paid</option>
                <option value="free">Free</option>
            </select>
        </div>
        <div>
            <h2 class="text-xl font-semibold mt-7">Filter on type</h2>
            <select name="type">
                <option value="all">All</option>
                <option value="lineArt">Line art</option>
                <option value="realistic">Realistic</option>
                <option value="cartoon">Cartoon</option>
            </select>
        </div>
        <div>
            <button class="bg-fadedpurple px-5 py-3 mt-5 rounded font-semibold text-2xl" type="submit" value="Search">Search</button>
        </div>
    </form>
    <h1>Your home</h1>
    <article>
        <?php foreach ($filter as $prompt): ?>
            <?php if (empty($_POST["search"]) || stripos($prompt["name"], $_POST["search"]) !== false):?>
                <div>
                    <a href="user.php?id=<?php echo $prompt["email"]; ?>">
                        <p><strong>User:</strong> <?php echo $prompt["email"]; ?></p>
                    </a>
                    <p><strong>Name:</strong> <?php echo htmlspecialchars($prompt["name"]); ?></p>
                    <img src="<?php echo htmlspecialchars($prompt["image"]); ?>" alt="input image">
                    <p><strong>Description:</strong> <?php echo htmlspecialchars($prompt["description"]); ?></p>
                    <p><strong>Type:</strong> <?php echo htmlspecialchars($prompt["type"]); ?></p>
                    <p><strong>Price:</strong> <?php echo htmlspecialchars($prompt["price"]); ?></p>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>
    </article>
</body>
</html>

<?php
// START DELETE ACCOUNT CODE

// Establish database connection
$conn = new PDO('mysql:host=localhost;dbname=demo', 'root', 'root');

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get email from form input
    $email = $_POST['email'];

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
        if ($_POST['confirm_delete'] != $user['email']) {
            echo "Email confirmation does not match.";
        } else {
            // Delete all data related to user
            $stmt = $conn->prepare('DELETE FROM users WHERE email = :email');
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            // Confirm deletion
            if ($stmt->rowCount() > 0) {
                echo "Account was deleted.";
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
  <label for="confirm_delete">Type your email address to confirm:</label>
  <input type="email" name="confirm_delete" required>
  <br>
  <p>By clicking the button below, you will permanently delete your account and all associated data.</p>
  <button type="submit" name="delete_account" onclick="return confirm('Are you sure you want to delete your account? This action cannot be undone.');">Delete Account</button>
</form>

<h2>Download Your Data</h2>
<p>You can download a copy of your data by clicking the button below.</p>
<a href="download_data.php">Download My Data</a>






// START PROFILE PICTURE CODE

<?php
// Establish database connection
$conn = new PDO('mysql:host=localhost;dbname=demo', 'root', 'root');

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
