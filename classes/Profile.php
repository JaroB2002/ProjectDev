<?php
class Profile {
  private $profilePhoto;

  public function __construct() {
    if (file_exists('uploads/profile.jpg')) {
      $this->profilePhoto = 'uploads/profile.jpg';
    } else {
      $this->profilePhoto = '';
    }
  }

  public function getProfilePhoto() {
    return $this->profilePhoto;
  }

  public function setProfilePhoto($photo) {
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($photo["name"]);
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    $check = getimagesize($photo["tmp_name"]);
    if($check !== false) {
      move_uploaded_file($photo["tmp_name"], $target_dir."profile.jpg");
      $this->profilePhoto = 'uploads/profile.jpg';
      return "De afbeelding is geüpload.";
    } else {
      return "Het bestand is geen afbeelding.";
    }
  }

  public function deleteProfilePhoto() {
    $target_file = "uploads/profile.jpg";
    if (file_exists($target_file)) {
      unlink($target_file);
      $this->profilePhoto = '';
      return "De afbeelding is verwijderd.";
    } else {
      return "Er is geen afbeelding om te verwijderen.";
    }
  }
}
?>
