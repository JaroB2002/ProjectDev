<?php 
    session_start();
    if(isset($_SESSION['username'])){
        //user is logged in
        echo "Welcome " . $_SESSION['username'];
        //queries in sql
    } else{
        //user is not logged in
        header("location: login.php");
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
</head>
<a href="logout.php">Log out?</a>

<body>
    <h1>Your home</h1>
</body>
</html>


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


