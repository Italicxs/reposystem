<?php
	require_once'../../inc/config/constants.php';
	require_once'../../inc/config/db.php';
	
	if(isset($_POST['itemImageItemNumber'])){
		
		$itemImageItemNumber = htmlentities($_POST['itemImageItemNumber']);
		
		$baseImageFolder = '../../data/item_images/';
		$itemImageFolder = '';
		
		if(!empty($itemImageItemNumber)){
			
			// Sanitize the item number to ensure it doesn't contain malicious content
			$itemImageItemNumber = filter_var($itemImageItemNumber, FILTER_SANITIZE_STRING);
			
			// Ensure the item number is alphanumeric and of a reasonable length (e.g., 10 characters)
			if (!preg_match('/^[a-zA-Z0-9]{1,10}$/', $itemImageItemNumber)) {
				echo '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button>Invalid item number format.</div>';
				exit();
			}

			// Check if the user has selected an image
			if($_FILES['itemImageFile']['name'] != ''){
				// Both itemNumber and image file given. Hence, proceed to next steps
				
				// Check if itemNumber is in DB
				$itemNumberSql = 'SELECT * FROM item WHERE itemNumber = :itemNumber';
				$itemNumberStatement = $conn->prepare($itemNumberSql);
				$itemNumberStatement->execute(['itemNumber' => $itemImageItemNumber]);
				
				if($itemNumberStatement->rowCount() > 0){
					// Item is in the DB, hence proceed to next steps
					// Check the file .extension
					$arr = explode('.', $_FILES['itemImageFile']['name']);
					$extension = strtolower(end($arr));
					$allowedTypes = array('jpg', 'jpeg', 'png', 'gif');
					
					if(in_array($extension, $allowedTypes)){
						// All good so far...
						
						// Ensure safe directory path construction
						$itemImageFolder = realpath($baseImageFolder . $itemImageItemNumber);
						
						// Check if the path is within the allowed directory
						if (strpos($itemImageFolder, realpath($baseImageFolder)) !== 0) {
							echo '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button>Invalid directory path.</div>';
							exit();
						}
						
						$fileName = time() . '_' . basename($_FILES['itemImageFile']['name']);
						
						// Check if the folder exists; if not, create it
						if (!is_dir($itemImageFolder)) {
							if (!mkdir($itemImageFolder, 0777, true)) {
								echo '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button>Failed to create directory.</div>';
								exit();
							}
						}
						
						$targetPath = $itemImageFolder . DIRECTORY_SEPARATOR . $fileName;
						
						// Upload file to server
						if(move_uploaded_file($_FILES['itemImageFile']['tmp_name'], $targetPath)){
							
							// Update image url in item table
							$updateImageUrlSql = 'UPDATE item SET imageURL = :imageURL WHERE itemNumber = :itemNumber';
							$updateImageUrlStatement = $conn->prepare($updateImageUrlSql);
							$updateImageUrlStatement->execute(['imageURL' => $fileName, 'itemNumber' => $itemImageItemNumber]);
							
							echo '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>Image uploaded successfully.</div>';
							exit();
							
						} else {
							echo '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button>Could not upload image.</div>';
							exit();
						}
						
					} else {
						// Image type is not allowed
						echo '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button>Image type is not allowed. Please select a valid image.</div>';
						exit();
					}
				}
				
			} else {
				// Image file not given
				echo '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button>Please select an image</div>';
				exit();
			}
		
		} else {
			echo '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button>Please enter item number</div>';
			exit();
		}

	}
?>
