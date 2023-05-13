<?php
include('classes/Profile.php');

// Maak een nieuwe Profile object aan
$profile = new Profile();

// Controleer of de gebruiker een profielfoto wilt uploaden
if (isset($_POST['uploadPhoto'])) {
  $result = $profile->setProfilePhoto($_FILES['image']);
  echo $result;
}

// Controleer of de gebruiker de profielfoto wilt verwijderen
if (isset($_POST['deletePhoto'])) {
  $result = $profile->deleteProfilePhoto();
  echo $result;
}

// Laat de geüploade afbeelding zien als deze al bestaat
if ($profile->getProfilePhoto() != '') {
  echo '<img src="' . $profile->getProfilePhoto() . '">';
}
?>

<form action="" method="post" enctype="multipart/form-data">
  <label>Selecteer een afbeelding:</label>
  <input type="file" name="image" required>
  <br><br>
  <input type="submit" name="uploadPhoto" value="Uploaden">
  <input type="submit" name="deletePhoto" value="Verwijderen">
</form>
