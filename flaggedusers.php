<?php
session_start();

include_once("bootstrap.php");

if (!isset($_SESSION['username'])) {
    header("location: index.php");
    exit();
}

if ($_SESSION['username'] == $_GET['id']) {
    header("location: editprofile.php");
    exit();
}

if (!empty($_GET["id"])) {
    $u = new User();
    $user_prompts = $u->getUserPrompts();
}

$f = new Follow();

$user = new User();
$user->setEmail($_SESSION['username']);
$userDetails = $user->getUserDetails();
$isModerator = $userDetails['is_admin'] == 1;

if (isset($_POST['report'])) {
    $reportedUser = $_GET['id'];
    // Voeg de gebruikersnaam en gegevens toe aan de databank
    // Code om de gebruiker te rapporteren en op te slaan in de databank

    // Stuur de gebruikersnaam en de moderatorstatus door naar flaggedusers.php via URL-parameters
    header("location: flaggedusers.php?user=" . urlencode($reportedUser) . "&isModerator=" . urlencode($isModerator));
    exit();
}
?>
