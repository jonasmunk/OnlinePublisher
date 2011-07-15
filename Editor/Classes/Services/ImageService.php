<?
/**
 * @package OnlinePublisher
 * @subpackage Classes
 */
require_once($basePath.'Editor/Classes/Services/FileSystemService.php');
require_once($basePath.'Editor/Classes/Objects/Image.php');
require_once($basePath.'Editor/Classes/Objects/Imagegroup.php');
require_once($basePath.'Editor/Classes/EventManager.php');
require_once($basePath.'Editor/Classes/Utilities/StringUtils.php');

class ImageService {

	static $validTypes = array("image/pjpeg","image/jpeg","image/gif","image/png","image/x-png");
	static $validExtensions = array("jpeg","jpg","gif","png");
	
	function getUsedImageIds() {
		// Image parts
	    $sql = "select image_id as id from part_image".
		// Persons
	    " union select image_id as id from person,image".
	        " where person.image_id=image.object_id".
		// Products
	    " union select image_id as id from product,image".
	        " where product.image_id=image.object_id".
		// Image gallery
	    " union select image_id as id from imagegallery_object,imagegroup_image".
	        " where imagegroup_image.imagegroup_id = imagegallery_object.object_id".
	    " union select image.object_id as id from imagegallery_object,image".
	        " where imagegallery_object.object_id = image.object_id".
		// Image gallery part
	    " union select image_id as id from part_imagegallery,imagegroup_image".
	        " where imagegroup_image.imagegroup_id = part_imagegallery.imagegroup_id";
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
	
	function getNumberOfPagesWithImages() {
		$count = 0;
		$sql = "select count(page.id) as num from `part_image`,document_section,page".
			" where part_image.part_id=document_section.part_id and page.id=document_section.page_id".
		" union select count(object.id) as num from imagegallery_object,object,page".
			" where imagegallery_object.object_id = object.id and imagegallery_object.page_id=page.id and object.type='image'".
		" union select count(object.id) as num from imagegallery_object,imagegroup_image,object,page".
			" where imagegroup_image.imagegroup_id = imagegallery_object.object_id and imagegallery_object.page_id=page.id".
			" and imagegroup_image.image_id=object.id";
		$rows = Database::selectAll($sql);
		foreach ($rows as $row) {
			$count+= intval($row['num']);
		}
		return $count;
	}
	
	function getNumberOfProductsWithImages() {
		$sql = "select count(object_id) as num from product,object where object.id=product.image_id";
		if ($row = Database::selectFirst($sql)) {
            return intval($row['num']);
        } else {
            return 0;
        }
	}
	
	function getNumberOfPersonsWithImages() {
		$sql = "select count(object_id) as num from person,object where object.id=person.image_id";
		if ($row = Database::selectFirst($sql)) {
            return intval($row['num']);
        } else {
            return 0;
        }
	}
	
	
	
	function getPageImageRelations() {
		$sql = "select image_id,object.title as image_title,page.title as page_title,page.id as page_id,'image' as part".
			" from `part_image`,document_section,page,object".
			" where part_image.part_id=document_section.part_id and page.id=document_section.page_id and part_image.image_id=object.id".
		" union select image_id,object.title as image_title,page.title as page_title,page.id as page_id,'text' as part".
			" from part_text,document_section,page,object".
			" where part_text.part_id=document_section.part_id and page.id=document_section.page_id and part_text.image_id=object.id".
		" union select distinct object.id as image_id,object.title as image_title,page.title as page_title,page.id as page_id,'imagegallery' as part".
			" from part_imagegallery,imagegroup,imagegroup_image,document_section,page,object".
			" where part_imagegallery.part_id=document_section.part_id and page.id=document_section.page_id".
				" and part_imagegallery.imagegroup_id=imagegroup_image.imagegroup_id and imagegroup_image.image_id=object.id".
		" union select object.id as image_id,object.title as image_title, page.title as page_title, page.id as page_id,'' as part".
			" from imagegallery_object,object,page where imagegallery_object.object_id = object.id".
			" and imagegallery_object.page_id=page.id and object.type='image'".
		" union select object.id as image_id,object.title as image_title, page.title as page_title, page.id as page_id,'' as part".
			" from imagegallery_object,imagegroup_image,object,page".
			" where imagegroup_image.imagegroup_id = imagegallery_object.object_id".
			" and imagegallery_object.page_id=page.id and imagegroup_image.image_id=object.id".
		" order by page_title,part,image_title";

 		return Database::selectAll($sql);
	}
	
	function getProductImageRelations() {
		$sql = "select image_object.id as image_id, image_object.title as image_title, product_object.id as product_id, product_object.title as product_title from product,object as image_object,object as product_object where image_object.id=product.image_id and product_object.id=product.object_id";
		return Database::selectAll($sql);
	}
	
	function getPersonImageRelations() {
		$sql = "select image_object.id as image_id, image_object.title as image_title, person_object.id as person_id, person_object.title as person_title from person,object as image_object,object as person_object where image_object.id=person.image_id and person_object.id=person.object_id";
		return Database::selectAll($sql);
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
	
	function createImageFromBase64($data,$fileName=null,$title=null) {
		global $basePath;
		$output = array('image'=>null,'success'=>false,'message'=>null);
		
		if (StringUtils::isBlank($data)) {
			$output['message'] = 'No data';
			return $output;
		}
		if ($fileName==null) {
			$fileName = 'untitled';
		}
		if ($title==null) {
			$title = 'Untitled';
		}
		$decoded = base64_decode($data);
		$tempPath = FileSystemService::getFreeTempPath();
		FileSystemService::writeStringToFile($decoded,$tempPath);
		
		$info = getimagesize($tempPath);
		$width = $info[0];
		$height = $info[1];
		$mimeType = $info['mime'];
		if ($width==0 || $height==0) {
			$output['message'] = 'Illegal dimensions';
			@unlink($tempPath);
			return $output;
		}
		if (!in_array($mimeType,ImageService::$validTypes)) {
			$output['message'] = 'Invalid mime type: '+$mimeType;
			return $output;
		}

		$extension = FileSystemService::getFileExtension($fileName);
		if (!$extension) {
			$extension = FileService::mimeTypeToExtension($mimeType);
			$fileName.='.'.$extension;
		}
		$path = $basePath.'images/'.$fileName;
		$path = FileSystemService::findFreeFilePath($path);
		
		if (!@rename($tempPath, $path)) {
			$output['message'] = 'Unable to move file from temporary path;';
			return $output;
			@unlink($tempPath);
		}
		
		$fileName = FileSystemService::getFileBaseName($path);
		
		$image = new Image();
		$image->setTitle($title);
		$image->setFilename($fileName);
		$image->setSize(filesize($path));
		$image->setMimetype($mimeType);
		$image->setWidth($width);
		$image->setHeight($height);
		$image->create();
		$image->publish();
		
		$output['image'] = $image;
		$output['success'] = true;
		
		return $output;
	}
	
	function createImageFromFile($tempPath,$fileName,$mimeType,$fileSize,$title=null,$group=null) {
		global $basePath;
		$errorMessage=null;
		$errorDetails=null;

		$uploadDir = $basePath.'images/';
		$filePath = $uploadDir . $fileName;
		$imageWidth=0;
		$imageHeight=0;

		$filePath = FileSystemService::findFreeFilePath($filePath);
		$fileName = FileSystemService::getFileBaseName($filePath);
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
					$title = FileSystemService::filenameToTitle($fileName);
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
		$extension = strtolower(FileSystemService::getFileExtension($fileName));
		return (in_array($mimeType,ImageService::$validTypes) || in_array($extension,ImageService::$validExtensions));
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