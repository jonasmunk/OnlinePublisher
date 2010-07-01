<?
/**
 * @package OnlinePublisher
 * @subpackage Include
 */
require_once($basePath.'Editor/Classes/FileSystemUtil.php');

/**
 * Replaces an existing Image object based on an uploaded file
 * @param int $id The ID of the existing Image object
 * @return array An array describing the success of the procedure
 */
function replaceUploadedImage($id) {
	global $basePath;
	
	$fileName=$_FILES['file']['name'];
	$fileName=FileSystemUtil::safeFilename($fileName);
	$fileType=$_FILES["file"]["type"];
	$tempFile=$_FILES['file']['tmp_name'];
	$uploadDir = $basePath.'images/';
	$filePath = $uploadDir . $fileName;
	$validTypes = array("image/pjpeg","image/jpeg","image/gif","image/png","image/x-png");
	$validExtensions = array("jpeg","jpg","gif","png");
	$imageWidth=0;
	$imageHeight=0;
	$fileSize=$_FILES["file"]["size"];
	$extension = FileSystemUtil::getFileExtension($fileName);

	$errorMessage=null;
	$errorDetails=null;

	$filePath = FileSystemUtil::findFreeFilePath($filePath);
	$fileName = FileSystemUtil::findFilePathName($filePath);

	if (!isSupportedImageFile($fileName,$mimeType)) {
		$errorMessage='Filen er ikke et validt billede';
		$errorDetails='mime: '.$mimeType.', filename:'.$fileName;
	}
	else if (!move_uploaded_file($tempFile, $filePath)) {
		$errorMessage='Kunne ikke flytte filen fra cachen';
	}
    else {
	    $imagehw = getimagesize($filePath);
	    $imageWidth = $imagehw[0];
	    $imageHeight = $imagehw[1];
    }
	if ($errorMessage==null) {

		// Load object
		$image = Image::load($id);
		
		// Delete old file
		$oldFilename=$image->getFilename();
        
		if (!unlink ($basePath.'images/'.$oldFilename)) {
			$errorMessage='Kunne ikke slette alt fra serveren';
		}
		// Update the object
		$image->setFilename($fileName);
		$image->setSize($fileSize);
		$image->setMimetype($fileType);
		$image->setWidth($imageWidth);
		$image->setHeight($imageHeight);
		$image->update();
		$image->publish();
		$image->clearCache();
		$image->fireRelationChangeEventOnGroups();
	}
	return array('success' => ($errorMessage==null),'errorMessage' => $errorMessage,'errorDetails' => $errorDetails);

	
}

/**
 * Checks whether the file is a valid file for image creation.
 * Note: The file need not exist and the mimeType is optional
 * @param string $fileName The name or path of the file
 * @param string $mimeType Optional mimetype of the file
 * @return boolean True if file supported, false otherwise
 */
function isSupportedImageFile($fileName,$mimeType="") {
	$validTypes = array("image/pjpeg","image/jpeg","image/gif","image/png","image/x-png");
	$validExtensions = array("jpeg","jpg","gif","png");
	$extension = strtolower(FileSystemUtil::getFileExtension($fileName));
	return (in_array($mimeType,$validTypes) || in_array($extension,$validExtensions));
}

/**
 * Creates a new Image object based on an uploaded file
 * @param string $title The title of the new Image object
 * @param int $group Optional ID of ImageGroup to place the Image in
 * @return array An array describing the success of the procedure
 */
function createUploadedImage($title,$group=0) {
	global $basePath;
	
	$fileName=$_FILES['file']['name'];
	$fileName=FileSystemUtil::safeFilename($fileName);
	$fileType=$_FILES["file"]["type"];
	$tempFile=$_FILES['file']['tmp_name'];
	$fileSize=$_FILES["file"]["size"];
	return createImageFromFile($tempFile,$fileName,$fileType,$fileSize,$title,$group);
}


function createImageFromFile($tempPath,$fileName,$mimeType,$fileSize,$title,$group) {
	global $basePath;
	$errorMessage=null;
	$errorDetails=null;

	$uploadDir = $basePath.'images/';
	$filePath = $uploadDir . $fileName;
	$imageWidth=0;
	$imageHeight=0;

	$filePath = FileSystemUtil::findFreeFilePath($filePath);
	$fileName = FileSystemUtil::findFilePathName($filePath);

	if (!isSupportedImageFile($fileName,$mimeType)) {
		$errorMessage='Filen er ikke et validt billede';
		$errorDetails='mime: '.$mimeType.', filename:'.$fileName;
	}
	else if (!copy($tempPath, $filePath)) {
		$errorMessage='Kunne ikke kopiere filen fra cachen';
		$errorDetails=$tempPath.' -> '.$filePath;
	}
	else {
		// Get the size of the image
    	//$image = loadImage($filePath);
		$imagehw = getimagesize($filePath);
		$imageWidth = $imagehw[0];//imagesx($image);
		$imageHeight = $imagehw[1];//imagesy($image);

		// If any of the dimensions are 0 something went wrong
		if ($imageWidth==0 || $imageHeight==0) {
			$errorMessage='Kunne ikke finde billedets størrelse';
			$errorDetails='Kan skyldes at filen ikke er et billede eller at det er et ukendt format';
		}
		// Only create the object if nothing went wrong
		if ($errorMessage==null) {
		
			// If no title build one from filename
			if ($title=='') {
				$title=FileSystemUtil::filenameToTitle($fileName);
			}
	
			// Create object
			$image = new Image();
			$image->setTitle($title);
			$image->setFilename($fileName);
			$image->setSize($fileSize);
			$image->setMimetype($mimeType);
			$image->setWidth($imageWidth);
			$image->setHeight($imageHeight);
			$image->create();
			$image->publish();
			if ($group>0) {
				$image->changeGroups(array($group));
			}
			$imageId = $image->getId();
		}
	}
	return array('success' => ($errorMessage==null)
					,'errorMessage' => $errorMessage
					,'errorDetails' => $errorDetails,'id'=>$imageId);
}


function loadImage($path) {
	$ext = strtolower(FileSystemUtil::getFileExtension($path));
	// create an image of the given filetype
	if ($ext=="jpg" || $ext == "jpeg") {
		$image = ImageCreateFromJpeg($path);
	}
	elseif ($ext=="png") {
		$image = ImageCreateFromPng($path);
	}
	elseif ($ext=="gif") {
		$image = ImageCreateFromGif($path);
	}
	return $image;
}

/**
 * Helper function for image creation functions. Creates thumbnails.
 * @param resource $img The image resource to resize
 * @param string $target The path of the directory to place the image in
 * @param int $width The width of the thumbnail
 * @param int $height The height of the thumbnail
 * @return boolean True on success, false otherwise
 */
function resizeImage($path,&$image,$target="",$width=0,$height=0){
	$output = false;

	// get image size
	//$size = getimagesize ($path);
    $origWidth = imagesx($image);//$size[0];
    $origHeight = imagesy($image);//$size[1];

	//generate white image
	$thumb = imagecreatetruecolor ($width, $height);
	$white = imagecolorallocate($thumb, 255, 255, 255);
	imagefill($thumb,0,0,$white);
	
	// resize image
	if (function_exists("imageCopyResampled")) {
		if (!@ImageCopyResampled($thumb, $image, 0, 0, 0, 0, $width, $height, $origWidth, $origHeight)) {
			ImageCopyResized($thumb, $image, 0, 0, 0, 0, $width, $height, $origWidth, $origHeight);
		}
	}
	else {
		ImageCopyResized($thumb, $image, 0, 0, 0, 0, $width, $height, $origWidth, $origHeight);
	}

	// output image
	if (imagepng($thumb, $target.'.png')) {
		$output = true;
	}
	
	// Free thumb
	imagedestroy($thumb);
	unset($thumb);
	
	return $output;
}
?>