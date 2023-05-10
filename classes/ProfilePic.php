<!-- //// class ProfilePic {
// 	private $target_dir = "uploads/";
// 	private $target_file;
// 	private $imageFileType;

// 	public function __construct() {
// 		$this->target_file = $this->target_dir . basename($_FILES["file"]["name"]);
// 		$this->imageFileType = strtolower(pathinfo($this->target_file,PATHINFO_EXTENSION));
// 	}

// 	public function upload() {
// 		if($this->imageFileType != "jpg" && $this->imageFileType != "png" && $this->imageFileType != "jpeg"
// 		&& $this->imageFileType != "gif" ) {
// 			echo "Alleen JPG, JPEG, PNG & GIF bestanden zijn toegestaan.";
// 		} else {
// 			if (move_uploaded_file($_FILES["file"]["tmp_name"], $this->target_file)) {
// 				echo "De afbeelding is succesvol geüpload.";
// 				echo "<br><img src='".$this->target_file."' width='300' height='300'>";
// 			} else {
// 				echo "Er is een fout opgetreden bij het uploaden van de afbeelding.";
// 			}
// 		}
// 	}
// }
//?>

<?php 
	class ProfilePic {
		private $picUrl;
		private $uploadDir;
	
		public function setPicUrl($url) {
			$this->picUrl = $url;
		}
	
		public function getPicUrl() {
			return $this->picUrl;
		}
	
		public function setUploadDir($dir) {
			$this->uploadDir = $dir;
		}
	
		public function getUploadDir() {
			return $this->uploadDir;
		}
	
		public function uploadPic() {
			if(isset($_FILES["image"])) {
				$target_dir = $this->getUploadDir();
				$target_file = $target_dir . basename($_FILES["image"]["name"]);
				$uploadOk = 1;
				$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
	
				// Check if image file is a actual image or fake image
				if(isset($_POST["submit"])) {
					$check = getimagesize($_FILES["image"]["tmp_name"]);
					if($check !== false) {
						$uploadOk = 1;
					} else {
						$uploadOk = 0;
					}
				}
	
				// Check file size
				if ($_FILES["image"]["size"] > 500000) {
					$uploadOk = 0;
				}
	
				if ($uploadOk == 0) {
					return false;
				} else {
					if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
						$this->setPicUrl($target_file);
						return true;
					} else {
						return false;
					}
				}
			}
		}
	}
	
?>
