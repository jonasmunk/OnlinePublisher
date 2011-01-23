<?
/**
 * @package OnlinePublisher
 * @subpackage Classes
 */
require_once($basePath.'Editor/Classes/FileSystemUtil.php');
require_once($basePath.'Editor/Classes/Services/FileSystemService.php');
require_once($basePath.'Editor/Classes/Image.php');
require_once($basePath.'Editor/Classes/Objects/Imagegroup.php');
require_once($basePath.'Editor/Classes/EventManager.php');
require_once($basePath.'Editor/Classes/Utilities/StringUtils.php');

class ImageService {
	
	function getUsedImageIds() {
	    $sql = "select image_id as id from part_image".
	    " union select image.object_id as id from imagegallery_object,image".
	        " where imagegallery_object.object_id = image.object_id".
	    " union select image_id as id from person,image".
	        " where person.image_id=image.object_id".
	    " union select image_id as id from product,image".
	        " where product.image_id=image.object_id".
	    " union select image_id as id from imagegallery_object,imagegroup_image".
	        " where imagegroup_image.imagegroup_id = imagegallery_object.object_id";
        return Database::getIds($sql);
	}
	
	function addImageToGroup($imageId,$groupId) {
		$image = Image::load($imageId);
		$group = Imagegroup::load($groupId);
		if ($image && $group) {
			$sql="delete from imagegroup_image where image_id=".$imageId." and imagegroup_id=".$groupId;
			Database::delete($sql);

			$sql="insert into imagegroup_image (image_id, imagegroup_id) values (".$imageId.",".$groupId.")";
			Database::insert($sql);
			EventManager::fireEvent('relation_change','object','imagegroup',$groupId);
		}
	}
	
	function getTotalImageCount() {
    	$sql= "select count(object.id) as num FROM object,image where image.object_id=object.id";
        if ($row = Database::selectFirst($sql)) {
            return intval($row['num']);
        } else {
            return 0;
        }
	}
	
	function getNumberOfImagesNotInGroup() {
		$sql = "select count(object.id) as num from object,image".
			" left join imagegroup_image on imagegroup_image.image_id=image.object_id".
        	" where object.id = image.object_id and imagegroup_image.imagegroup_id is null".
        	" order by object.title";
        if ($row = Database::selectFirst($sql)) {
            return intval($row['num']);
        } else {
            return 0;
        }
	}
	
	function getUnusedImagesCount() {
        $used = ImageService::getUsedImageIds();
    	$sql= "SELECT count(object.id) as num FROM object,image".
    	" where image.object_id=object.id".
    	(count($used)>0 ? " and object.id not in (".implode(",",$used).")" : "").
    	" order by title";
        if ($row = Database::selectFirst($sql)) {
            return $row['num'];
        } else {
            return 0;
        }
	}

	function createImageFromUrl($url) {
		global $basePath;
		error_reporting(E_ERROR);

		$report = array('imported' => array(), 'problems' => array());
		$error = false;

		if (StringUtils::isBlank($url)) {
			return array(
				'success' => false,
				'errorMessage' => 'Adressen er ikke valid',
				'errorDetails' => 'Du skal udfylde hvilken adresse billedet skal hentes fra.'
			);
		}
		$temp = $basePath.'local/cache/temp/'.basename($url);
		if (RemoteDataService::writeUrlToFile($url,$temp)) {
			$result = ImageService::createImageFromFile($temp,basename($url),null,filesize($temp));
			unlink($temp);
			return $result;
		} else {
			Log::debug('Unable to load url: '.$url);
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
		$fileName=FileSystemService::safeFilename($fileName);
		$fileType=$_FILES["file"]["type"];
		$tempFile=$_FILES['file']['tmp_name'];
		$fileSize=$_FILES["file"]["size"];
		return ImageService::createImageFromFile($tempFile,$fileName,$fileType,$fileSize,$title,$group);
	}
	
	function createImageFromFile($tempPath,$fileName,$mimeType,$fileSize,$title=null,$group=null) {
		global $basePath;
		$errorMessage=null;
		$errorDetails=null;

		$uploadDir = $basePath.'images/';
		$filePath = $uploadDir . $fileName;
		$imageWidth=0;
		$imageHeight=0;

		$filePath = FileSystemUtil::findFreeFilePath($filePath);
		$fileName = FileSystemUtil::findFilePathName($filePath);
		if (!file_exists($tempPath)) {
			$errorMessage='Filen findes ikke';
			$errorDetails='Sti:'.$tempPath;			
		} else if (!ImageService::isSupportedImageFile($fileName,$mimeType)) {
			$errorMessage='Filen er ikke et validt billede';
			$errorDetails='mime: '.$mimeType.', filename:'.$fileName;
		}
		else if (!@copy($tempPath, $filePath)) {
			$errorMessage='Kunne ikke kopiere filen fra cachen';
			$errorDetails=$tempPath.' -> '.$filePath;
		}
		else {
			// Get the size of the image
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
		return array(
			'success' => ($errorMessage==null)
			,'errorMessage' => $errorMessage
			,'errorDetails' => $errorDetails,
			'id'=>$imageId
		);
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
		$extension = strtolower(FileSystemService::getFileExtension($fileName));
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