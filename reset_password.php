<?php
// Connect to database
$conn = new PDO('mysql:host=localhost;dbname=demo', "root", "");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  // Retrieve email from POST data
  $email = $_POST['email'] ?? '';

  // Check if email exists in the database
  $query = "SELECT * FROM users WHERE email = :email";
  $statement = $conn->prepare($query);
  $statement->bindValue(":email", $email);
  $statement->execute();
  $result = $statement->fetch();

  if (!$result) {
    // Email address not found in database
    echo "Sorry, we could not find that email address.";
    exit;
  }
  
  // Generate token for password reset
  $token = bin2hex(random_bytes(32));

  // Insert token and email into password_resets table
  $query = "INSERT INTO password_resets (email, token, created_at) VALUES (:email, :token, NOW())";
  $statement = $conn->prepare($query);
  $statement->bindValue(":email", $email);
  $statement->bindValue(":token", $token);
  $statement->execute();

  // Send password reset link to user's email address
  // You can customize the email message with your own content
  $subject = "Password Reset Request";
  $message = "Hello,\n\nPlease click the following link to reset your password: http://example.com/reset.php?email=".urlencode($email)."&token=".$token."\n\nIf you did not request a password reset, please ignore this message.";
  $headers = "From: webmaster@example.com\r\nReply-To: webmaster@example.com\r\nX-Mailer: PHP/".phpversion();

  if (mail($email, $subject, $message, $headers)) {
    echo "A password reset link has been sent to your email address.";
  } else {
    echo "Sorry, we could not send the password reset link. Please try again later.";
  }
} else {
  // Display password reset form
  ?>
  <form method="POST" action="">
    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required>
    <br>
    <input type="submit" value="Reset Password">
  </form>
  <?php
}
?>
