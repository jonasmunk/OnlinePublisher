<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes
*/

require_once($basePath.'Editor/Classes/Object.php');
require_once($basePath.'Editor/Classes/FileSystemUtil.php');
require_once($basePath.'Editor/Classes/RemoteFile.php');

class File extends Object {
	var $filename;
	var $size;
	var $mimetype;

	function File() {
		parent::Object('file');
	}
	
	function getIn2iGuiIcon() {
        return "file/generic";
	}

	function setFilename($filename) {
		$this->filename = $filename;
	}

	function getFilename() {
		return $this->filename;
	}

	function setSize($size) {
		$this->size = $size;
	}

	function getSize() {
		return $this->size;
	}

	function setMimetype($type) {
		$this->mimetype = $type;
	}

	function getMimetype() {
		return $this->mimetype;
	}

	function load($id) {
		$sql = "select * from file where object_id=".$id;
		$row = Database::selectFirst($sql);
		if ($row) {
			$obj = new File();
			$obj->_load($id);
			$obj->filename=$row['filename'];
			$obj->size=$row['size'];
			$obj->mimetype=$row['type'];
			return $obj;
		} else {
			return null;
		}
	}

	function sub_create() {
		$sql="insert into file (object_id,filename,size,type) values (".
		$this->id.
		",".Database::text($this->filename).
		",".Database::int($this->size).
		",".Database::text($this->mimetype).
		")";
		Database::insert($sql);
	}

	function sub_update() {
		$sql = "update file set ".
		"filename=".Database::text($this->filename).
		",size=".Database::int($this->size).
		",type=".Database::text($this->mimetype).
		" where object_id=".$this->id;
		Database::update($sql);
	}

	function sub_publish() {
		$data =
		'<file xmlns="'.parent::_buildnamespace('1.0').'">'.
		'<filename>'.encodeXML($this->filename).'</filename>'.
		'<size>'.encodeXML($this->size).'</size>'.
		'<mimetype>'.encodeXML($this->mimetype).'</mimetype>'.
		'</file>';
		return $data;
	}

	function sub_remove() {
		global $basePath;
		$path = $basePath.'files/'.$this->filename;
		if (file_exists($path)) {
			!@unlink ($path);
		}
		$sql="delete from filegroup_file where file_id=".Database::int($this->id);
		Database::delete($sql);
		$sql = "delete from file where object_id=".Database::int($this->id);
		Database::delete($sql);
	}
	
	/***************** Groups ****************/
	
	function getGroupIds() {
		$sql = "select filegroup_id as id from filegroup_file where file_id=".Database::int($this->id);
		return Database::getIds($sql);
	}
	
	function updateGroupIds($ids) {
		$ids = Object::getValidIds($ids);
		$sql = "delete from filegroup_file where file_id=".Database::int($this->id);
		Database::delete($sql);
		foreach ($ids as $id) {
			$sql = "insert into filegroup_file (filegroup_id,file_id) values (".Database::int($id).",".Database::int($this->id).")";
			Database::insert($sql);
		}
	}
	
	function addGroupId($id) {
		$sql = "delete from filegroup_file where file_id=".Database::int($this->id)." and filegroup_id=".Database::int($id);
		Database::delete($sql);
		$sql = "insert into filegroup_file (filegroup_id,file_id) values (".Database::int($id).",".Database::int($this->id).")";
		Database::insert($sql);
	}
	
	/********************** Search *****************/
	
    function find($query = array()) {
    	$parts = array();
		$parts['columns'] = 'object.id';
		$parts['tables'] = 'file,object';
		$parts['limits'] = array();
		$parts['ordering'] = 'object.title';
		$parts['direction'] = $query['direction'];
		
		$parts['limits'][] = "object.id=file.object_id";
		if (isset($query['filegroup'])) {
			$parts['tables'].=",filegroup_file";
			$parts['limits'][] = "filegroup_file.file_id = object.id";
			$parts['limits'][] = "filegroup_file.filegroup_id=".$query['filegroup'];
		}
		if (isset($query['type'])) {
			$parts['limits'][]='`file`.`type` = '.Database::text($query['type']);
		}
		if (is_array($query['mimetypes'])) {
			$ors = array();
			foreach ($query['mimetypes'] as $type) {
				$ors[]='`file`.`type` = '.Database::text($type);
			}
			$parts['limits'][]='('.implode(' or ',$ors).')';
		}
		if (isset($query['query'])) {
			$parts['limits'][]='`object`.`index` like '.Database::search($query['query']);
		}
		if (isset($query['createdMin'])) {
			$parts['limits'][]='`object`.`created` > '.Database::datetime($query['createdMin']);
		}
		if ($query['sort']=='title') {
			$parts['ordering']="object.title";
		}
		$list = parent::_find($parts,$query);
		$list['result'] = array();
		foreach ($list['rows'] as $row) {
			$list['result'][] = File::load($row['id']);
		}
		return $list;
	}
	
	function getTypeCounts() {
		$sql="select type,count(object_id) as count from file group by type order by type";
		return Database::selectAll($sql);
	}
		
	/********************** Upload *****************/
	
	/**
	 * Creates a new file object based on an uploaded file
	 * @param string $title The title of the uploaded file
	 * @param int $group Optional ID of FileGroup to place the file in
	 * @return array An array describing the success of the procedure
	 */
	function createUploadedFile($title='',$group=0) {
		global $basePath;
		error_log(print_r($_FILES['file'],true));
		$fileName = $_FILES['file']['name'];
		$fileType=$_FILES["file"]["type"];
		$tempFile=$_FILES['file']['tmp_name'];
		$fileSize=$_FILES["file"]["size"];
		
		if ($fileType=='application/octet-stream') {
			$fileType = FileSystemUtil::fileNameToMimeType($fileName);
		}
		$fileName=FileSystemUtil::safeFilename($fileName);
		$uploadDir = $basePath.'files/';
		$filePath = $uploadDir . $fileName;

		$filePath = FileSystemUtil::findFreeFilePath($filePath);
		$fileName = FileSystemUtil::findFilePathName($filePath);

		$errorMessage=false;
		$errorDetails='';

		if (file_exists($filePath)) {
			$errorMessage='Filen findes allerede';
		}
		else if (!move_uploaded_file($tempFile, $filePath)) {
			$errorMessage='Kunne ikke flytte filen fra cachen';
		}

		if (!$errorMessage) {

			if ($title=='') {
				$title=FileSystemUtil::filenameToTitle($fileName);
			}

			$file = new File();
			$file->setTitle($title);
			$file->setFilename($fileName);
			$file->setSize($fileSize);
			$file->setMimetype($fileType);
			$file->create();
			$file->publish();

			$fileId = $file->getId();

			// Add to group
			if ($group>0) {
				$sql="insert into filegroup_file (file_id,filegroup_id)".
				" values (".$fileId.",".$group.")";
				Database::insert($sql);
			}
		}
		return array('success' => ($errorMessage===false),'errorMessage' => $errorMessage,'errorDetails' => $errorDetails);
	}
	
	function createFromUrl($url) {
		global $basePath;
		$remote = new RemoteFile($url);
		$path = $remote->writeToTempFile();
		error_log(print_r($remote->getInfo(),true));
		if (!$remote->isSuccess()) {
			@unlink($path);
			return array('success' => false,'message' => 'Filen blev ikke fundet');
		}
		$type = $remote->getContentType();
		$filename = $remote->getFilename();
		$size = filesize($path);
		if ($filename==='') {
			$filename = 'newfile';
			if ($type!=null) {
				$extension = FileSystemUtil::mimeTypeToExtension($type);
				if ($extension!=null) {
					$filename.='.'.$extension;
				}
			}
		}
		$filename = FileSystemUtil::safeFilename($filename);
		$newPath = FileSystemUtil::findFreeFilePath($basePath.'files/'.$filename);
		if (!@rename($path,$newPath)) {
			return array('success' => false,'message' => 'Der skete en uventet fejl ');
		}
		
		$title=FileSystemUtil::filenameToTitle($filename);

		$file = new File();
		$file->setTitle($title);
		$file->setFilename($filename);
		$file->setSize($size);
		$file->setMimetype($type);
		$file->create();
		$file->publish();
		return array('success' => true);
	}
}
?>