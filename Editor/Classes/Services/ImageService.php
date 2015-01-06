<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes
 */
if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

class ImageService {

	static $validTypes = array("image/pjpeg","image/jpeg","image/gif","image/png","image/x-png");
	static $validExtensions = array("jpeg","jpg","gif","png");
	
	static function getUsedImageIds() {
		// Image parts
	    $sql = "select image_id as id from part_image".
		// Persons
	    " union select image_id as id from person,image".
	        " where person.image_id=image.object_id".
		// Products
	    " union select image_id as id from product,image".
	        " where product.image_id=image.object_id".
		// Image gallery part
	    " union select image_id as id from part_imagegallery,imagegroup_image".
	        " where imagegroup_image.imagegroup_id = part_imagegallery.imagegroup_id";
        return Database::getIds($sql);
	}
	
	static function addImageToGroup($imageId,$groupId) {
		$image = Image::load($imageId);
		$group = Imagegroup::load($groupId);
		if ($image && $group) {
			$sql="delete from imagegroup_image where image_id=".$imageId." and imagegroup_id=".$groupId;
			Database::delete($sql);

			$sql="insert into imagegroup_image (image_id, imagegroup_id) values (".$imageId.",".$groupId.")";
			Database::insert($sql);
			EventService::fireEvent('relation_change','object','imagegroup',$groupId);
		}
	}
	
	static function getGroupCounts() {
		$out = array();
		$sql="select distinct object.id,object.title,count(image.object_id) as imagecount from imagegroup, imagegroup_image, image,object  where imagegroup_image.imagegroup_id=imagegroup.object_id and imagegroup_image.image_id = image.object_id and object.id=imagegroup.object_id group by imagegroup.object_id union select object.id,object.title,'0' from object left join imagegroup_image on imagegroup_image.imagegroup_id=object.id where object.type='imagegroup' and imagegroup_image.image_id is null order by title";
		$result = Database::select($sql);
		while ($row = Database::next($result)) {
			$out[] = array(
				'id' => $row['id'],
				'title' => $row['title'],
				'count' => $row['imagecount']
			);
		}
		Database::free($result);
		return $out;
	}

	
	static function getTotalImageCount() {
    	$sql= "select count(object.id) as num FROM object,image where image.object_id=object.id";
        if ($row = Database::selectFirst($sql)) {
            return intval($row['num']);
        } else {
            return 0;
        }
	}
	
	static function getNumberOfPagesWithImages() {
		$count = 0;
		$sql = "select count(distinct page.id) as num from `part_image`,object,document_section,page where part_image.part_id=document_section.part_id and page.id=document_section.page_id and `part_image`.`image_id`=object.`id`";
		if ($row = Database::selectFirst($sql)) {
			$count+= intval($row['num']);
		}
		$sql = "select distinct page.id as num from part_imagegallery,imagegroup,object,`imagegroup_image`,document_section,page where part_imagegallery.part_id=document_section.part_id and page.id=document_section.page_id and `part_imagegallery`.`imagegroup_id`=imagegroup.`object_id` and `imagegroup_image`.`imagegroup_id`=imagegroup.`object_id` and imagegroup.`object_id`=object.id";
		if ($row = Database::selectFirst($sql)) {
			$count+= intval($row['num']);
		}		
		return $count;
	}
	
	static function getNumberOfProductsWithImages() {
		$sql = "select count(object_id) as num from product,object where object.id=product.image_id";
		if ($row = Database::selectFirst($sql)) {
            return intval($row['num']);
        } else {
            return 0;
        }
	}
	
	static function getNumberOfPersonsWithImages() {
		$sql = "select count(object_id) as num from person,object where object.id=person.image_id";
		if ($row = Database::selectFirst($sql)) {
            return intval($row['num']);
        } else {
            return 0;
        }
	}
	
	
	
	static function getPageImageRelations() {
		$sql = "select image_id,object.title as image_title,page.title as page_title,page.id as page_id,'image' as part,'document' as template".
			" from `part_image`,document_section,page,object".
			" where part_image.part_id=document_section.part_id and page.id=document_section.page_id and part_image.image_id=object.id".
		" union select image_id,object.title as image_title,page.title as page_title,page.id as page_id,'text' as part,'document' as template".
			" from part_text,document_section,page,object".
			" where part_text.part_id=document_section.part_id and page.id=document_section.page_id and part_text.image_id=object.id".
		" union select distinct object.id as image_id,object.title as image_title,page.title as page_title,page.id as page_id,'imagegallery' as part,'document' as template".
			" from part_imagegallery,imagegroup,imagegroup_image,document_section,page,object".
			" where part_imagegallery.part_id=document_section.part_id and page.id=document_section.page_id".
				" and part_imagegallery.imagegroup_id=imagegroup_image.imagegroup_id and imagegroup_image.image_id=object.id".
		" order by page_title,part,image_title";

 		return Database::selectAll($sql);
	}
	
	static function getProductImageRelations() {
		$sql = "select image_object.id as image_id, image_object.title as image_title, product_object.id as product_id, product_object.title as product_title from product,object as image_object,object as product_object where image_object.id=product.image_id and product_object.id=product.object_id";
		return Database::selectAll($sql);
	}
	
	static function getPersonImageRelations() {
		$sql = "select image_object.id as image_id, image_object.title as image_title, person_object.id as person_id, person_object.title as person_title from person,object as image_object,object as person_object where image_object.id=person.image_id and person_object.id=person.object_id";
		return Database::selectAll($sql);
	}
	
	
	
	
	static function getNumberOfImagesNotInGroup() {
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
	
	static function getUnusedImagesCount() {
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

	static function createImageFromUrl($url) {
		global $basePath;
		error_reporting(E_ERROR);

		$report = array('imported' => array(), 'problems' => array());
		$error = false;

		if (Strings::isBlank($url)) {
			$result = new ImportResult();
			$result->setSuccess(false);
			$result->setMessage(array('en'=>'The address is invalid', 'da'=>'Adressen er ikke valid'));
			return $result;
		}
		$temp = $basePath.'local/cache/temp/'.basename($url);
		if (RemoteDataService::writeUrlToFile($url,$temp)) {
			$result = ImageService::createImageFromFile($temp,basename($url));
			@unlink($temp);
			return $result;
		} else {
			Log::debug('Unable to load url: '.$url);
		}
		
		$result = new ImportResult();
		$result->setSuccess(false);
		$result->setMessage(array('en'=>'The image could not be fecthed','da'=>'Billedet kunne ikke hentes'));
		return $result;
	}

	/**
	 * Creates a new Image object based on an uploaded file
	 * @param string $title The title of the new Image object
	 * @param int $group Optional ID of ImageGroup to place the Image in
	 * @return array An array describing the success of the procedure
	 */
	static function createUploadedImage($title="",$group=0) {
		global $basePath;
	
		$fileName = $_FILES['file']['name'];
		//$fileName = FileSystemService::safeFilename($fileName);
		//$fileType = $_FILES["file"]["type"];
		$tempFile = $_FILES['file']['tmp_name'];
		//$fileSize = $_FILES["file"]["size"];
		return ImageService::createImageFromFile($tempFile,$fileName,$title,$group);
	}
	
	static function createImageFromBase64($data,$fileName=null,$title=null) {
		global $basePath;
		$output = array('image'=>null,'success'=>false,'message'=>null);
		
		if (Strings::isBlank($data)) {
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
			@unlink($tempPath);
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
			@unlink($tempPath);
			return $output;
		}

        ImageTransformationService::optimizeFile($path);
		
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
	
	static function isUploadedFileValid($name='file') {
		$path = $_FILES[$name]['tmp_name'];
		$info = ImageTransformationService::getImageInfo($path);
		if ($info) {
			if (in_array($info['mime'],ImageService::$validTypes)) {
				return true;
			}
		}
		return false;
	}
	
	static function createImageFromFile($tempPath,$fileName=null,$title=null,$group=null) {
		global $basePath;
		
		if (!file_exists($tempPath)) {
			Log::debug('File not found: '.$tempPath);
			return ImportResult::fail(array('en'=>'The file could not be found', 'da'=>'Filen findes ikke'));
		}
		
		$info = ImageTransformationService::getImageInfo($tempPath);
		if (!$info) {
			Log::debug('Unable to get image info: '.$tempPath);
			return ImportResult::fail(array('en'=>'The file is not a valid image', 'da'=>'Filen er ikke et validt billede'));
		}

		// If no file name then extract it from the path
		if (Strings::isBlank($fileName)) {
			$fileName = FileSystemService::getFileBaseName($tempPath);
		}
        $fileName = FileSystemService::safeFilename($fileName);
		
		$uploadDir = FileSystemService::join($basePath,'images');
		$filePath = FileSystemService::join($uploadDir,$fileName);
		
		// Make sure the file has the correct extension
		$ext = FileService::mimeTypeToExtension($info['mime']);
		$filePath = FileSystemService::overwriteExtension($filePath,$ext);
		
		// Find a free path
		$filePath = FileSystemService::findFreeFilePath($filePath);
		
		// The new file name
		$fileName = FileSystemService::getFileBaseName($filePath);
		
		if (!in_array($info['mime'],ImageService::$validTypes)) {
			Log::debug('Unsupported: '.$info['mime']);
			return ImportResult::fail(array('en'=>'The file format is not supported', 'da'=>'Filens format er ikke understøttet'));
		}
		else if (!@copy($tempPath, $filePath)) {
			Log::debug('Could not copy: '.$tempPath.' -> '.$filePath);
			return ImportResult::fail(array('en'=>'Unable to copy file', 'da'=>'Kunne ikke kopiere filen'));
		}
        
        ImageTransformationService::optimizeFile($filePath);

		// If no title build one from filename
		if (Strings::isBlank($title)) {
			$title = FileSystemService::filenameToTitle($fileName);
		}

		// Create object
		$image = new Image();
		$image->setTitle($title);
		$image->setFilename($fileName);
		$image->setSize(filesize($filePath));
		$image->setMimetype($info['mime']);
		$image->setWidth($info['width']);
		$image->setHeight($info['height']);
		$image->create();
		$image->publish();

		if ($group>0) {
			$image->changeGroups(array($group));
		}
		
		$result = new ImportResult();
		$result->setSuccess(true);
		$result->setObject($image);
		return $result;
	}
	
	static function getLatestImageId() {
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
	static function isSupportedImageFile($fileName,$mimeType="") {
		$extension = strtolower(FileSystemService::getFileExtension($fileName));
		return (in_array($mimeType,ImageService::$validTypes) || in_array($extension,ImageService::$validExtensions));
	}
}
?>