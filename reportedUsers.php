<?php
if(!isset($_SESSION['username'])){
  header("location: index.php");
} 
session_start();


$undoReported = false;
$flaggedUsers = array("User1", "User2", "User3"); // Example list of flagged users

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  if (isset($_POST["reportedUser"])) {
    $reportedUser = $_POST["reportedUser"];

    if (isset($_POST["undoReport"])) {
      // Logic to undo the reported user
      $undoReported = true;
      // Implement your own logic to undo the reported user, such as updating a database or storage
      // You can display a success message or perform any other required actions
      echo "De gebruiker " . $reportedUser . " is niet langer gerapporteerd.";
    } else {
      // Logic to report the user
      // Implement your own logic to report the user, such as updating a database or storage
      // You can display a success message or perform any other required actions
      echo "De gebruiker " . $reportedUser . " is gerapporteerd.";
    }
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Reported Users</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css">
</head>
<body>
  <div class="container mx-auto py-10">
    <h1 class="text-4xl font-bold mb-8">Reported Users</h1>

    <?php if (isset($reportedUser)): ?>
      <div class="bg-gray-100 rounded-lg p-6 mb-4">
        <h2 class="text-2xl mb-4">Rapportage van gebruiker: <?php echo $reportedUser; ?></h2>
        <?php if ($undoReported): ?>
          <p class="text-green-500 font-semibold">Rapportage ongedaan gemaakt</p>
        <?php else: ?>
          <form action="reportedUsers.php" method="post">
            <input type="hidden" name="reportedUser" value="<?php echo $reportedUser; ?>">
            <button type="submit" name="undoReport" class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded">Rapportage ongedaan maken</button>
          </form>
        <?php endif; ?>
      </div>
    <?php endif; ?>

    <div class="bg-gray-100 rounded-lg p-6 mb-4">
      <h2 class="text-2xl mb-4">Lijst van gerapporteerde gebruikers</h2>
      <?php if (!empty($flaggedUsers)): ?>
        <ul>
          <?php foreach ($flaggedUsers as $user): ?>
            <li><?php echo $user; ?></li>
          <?php endforeach; ?>
        </ul>
      <?php else: ?>
        <p>Er zijn geen gerapporteerde gebruikers op dit moment.</p>
      <?php endif; ?>
    </div>

  </div>
</body>
</html>
