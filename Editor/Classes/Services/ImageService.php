<?
/**
 * @package OnlinePublisher
 * @subpackage Classes
 */
require_once($basePath.'Editor/Classes/FileSystemUtil.php');
require_once($basePath.'Editor/Classes/Image.php');

class ImageService {

	function createImageFromUrl($url) {
		global $basePath;
		error_reporting(E_ERROR);

		$report = array('imported' => array(), 'problems' => array());
		$error = false;

		if (strlen($url)=="") {
			return array(
				'success' => false,
				'errorMessage' => 'Adressen er ikke angivet',
				'errorDetails' => 'Du skal udfylde hvilken adresse billedet skal hentes fra.'
			);
		}
		else if ($file = fopen ($url, "rb")) {
		    $tempFilename = $basePath.'local/cache/temp/'.basename($url);
		    $temp = fopen($tempFilename, "wb");
			while (!feof($file)) {
				fwrite($temp,fread($file, 8192));
			}
		    fclose($temp);
			$result = ImageService::createImageFromFile($tempFilename,basename($url),null,filesize($tempFilename),null,$group);
			unlink($tempFilename);
			fclose($file);
			return $result;
		}
		return array(
			'success' => false,
			'errorMessage' => 'Kunne ikke hente billede',
			'errorDetails' => 'Den angivne adresse kunne ikke kontaktes.'
		);
	}

	/**
	 * Creates a new Image object based on an uploaded file
	 * @param string $title The title of the new Image object
	 * @param int $group Optional ID of ImageGroup to place the Image in
	 * @return array An array describing the success of the procedure
	 */
	function createUploadedImage($title="",$group=0) {
		global $basePath;
	
		$fileName=$_FILES['file']['name'];
		$fileName=FileSystemUtil::safeFilename($fileName);
		$fileType=$_FILES["file"]["type"];
		$tempFile=$_FILES['file']['tmp_name'];
		$fileSize=$_FILES["file"]["size"];
		return ImageService::createImageFromFile($tempFile,$fileName,$fileType,$fileSize,$title,$group);
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

		if (!ImageService::isSupportedImageFile($fileName,$mimeType)) {
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
	
	function getLatestImageId() {
		$sql = "select max(object_id) as id from image";
		if ($row = Database::selectFirst($sql)) {
			return intval($row['id']);
		}
		return null;
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
}

class ImageImportResult {
	
	var $success = false;
	var $errorTitle;
	var $errorDescription;
	
	function isSuccess() {
		return $this->success;
	}
}