<?php
include_once("bootstrap.php");

session_start();
if(!isset($_SESSION['username'])){
    header("location: index.php");
} 
$username = $_SESSION['username'];


// Check if the user is an administrator or has the necessary permissions to ban users
// Add your logic here to determine the user's authorization

if (isset($_POST['blockUser'])) {
    $userToBlock = $_POST['blockUser'];

    // Validate the email
    if (filter_var($userToBlock, FILTER_VALIDATE_EMAIL)) {
        $user = new User();
        $user->setEmail($userToBlock);

        // Check if the user is already blocked
        $userDetails = $user->getUserDetails();
        $isBlocked = $userDetails['is_blocked'];
        
        if ($isBlocked) {
            $user->unblockUser();
            // Redirect to a success page or perform any other actions after unblocking the user
            header("Location: success.php");
            exit();
        } else {
            $user->blockUser();
            // Redirect to a success page or perform any other actions after blocking the user
            header("Location: success.php");
            exit();
        }
    } else {
        echo "Invalid email";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ban/Unblock User</title>
</head>
<body>
    <h1>Ban/Unblock User</h1>

    <form action="" method="post">
        <label for="blockUser">User to Ban/Unblock:</label>
        <input type="text" name="blockUser" id="blockUser" required>
        <button type="submit">Ban/Unblock User</button>
    </form>
</body>
</html>
