<?php
if (isset($_POST['subscribe'])) {
  $email = $_POST['email'];

  // Voer hier je gewenste acties uit, zoals het opslaan van de e-mail in een database of het versturen van een bevestigingsmail.

  // Voorbeeld: Eenvoudige opslag in een tekstbestand
  $file = fopen('subscribed_emails.txt', 'a');
  fwrite($file, $email . "\n");
  fclose($file);

  // Stuur eventueel een bevestigingsmail naar de gebruiker

  // Doorsturen naar een bedankpagina
  header('Location: thank-you.html');
  exit();
}
?>