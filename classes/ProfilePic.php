<?php
class ProfilePic {
	private $target_dir = "uploads/";
	private $target_file;
	private $imageFileType;

	public function __construct() {
		$this->target_file = $this->target_dir . basename($_FILES["file"]["name"]);
		$this->imageFileType = strtolower(pathinfo($this->target_file,PATHINFO_EXTENSION));
	}

	public function upload() {
		if($this->imageFileType != "jpg" && $this->imageFileType != "png" && $this->imageFileType != "jpeg"
		&& $this->imageFileType != "gif" ) {
			echo "Alleen JPG, JPEG, PNG & GIF bestanden zijn toegestaan.";
		} else {
			if (move_uploaded_file($_FILES["file"]["tmp_name"], $this->target_file)) {
				echo "De afbeelding is succesvol geüpload.";
				echo "<br><img src='".$this->target_file."' width='300' height='300'>";
			} else {
				echo "Er is een fout opgetreden bij het uploaden van de afbeelding.";
			}
		}
	}
}
?>
