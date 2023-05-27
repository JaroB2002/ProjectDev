<?php
// removeflaggeduser.php

if (isset($_GET['user'])) {
    $userToRemove = $_GET['user'];

    // Code om de gerapporteerde gebruiker te verwijderen
    // uit de database of andere vereiste verwerking

    // Na het verwijderen, omleiden naar het flaggedusers.php bestand
    header("location: flaggedusers.php");
    exit();
} else {
    // Geen gebruikersnaam opgegeven, omleiden naar flaggedusers.php
    header("location: flaggedusers.php");
    exit();
}
?>
