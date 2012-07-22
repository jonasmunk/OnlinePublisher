<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes
 */
if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}
require_once($basePath.'Editor/Classes/Services/FileSystemService.php');
require_once($basePath.'Editor/Classes/Network/RemoteFile.php');
require_once($basePath.'Editor/Classes/Objects/File.php');
require_once($basePath.'Editor/Classes/Core/Log.php');

class FileService {
	
	public static $types = array(
		 array('kind' => 'unknown.any', 'label' => 'Ukendt', 'category' => 'unknown',
			'mimetypes' => array('application/octet-stream'),
			'extensions' => array()
		),
		 array('kind' => 'document.microsoft-word', 'label' => 'Microsoft Word', 'category' => 'document',
			'mimetypes' => array('application/msword','application/vnd.openxmlformats-officedocument.wordprocessingml.document'),
			'extensions' => array('doc','docx')
		),
		array('kind' => 'image.jpeg', 'category' => 'image', 'label' => 'JPEG billede',
			'mimetypes' => array('image/jpeg','image/pjpeg'),
			'extensions'=>array('jpg','jpeg','pjpeg')
		),
		array('kind' => 'image.png', 'category' => 'image', 'label' => 'PNG billede',
			'mimetypes' => array('image/png'),
			'extensions'=>array('png')
		),
		array('kind' => 'image.gif', 'category' => 'image', 'label' => 'GIF billede',
			'mimetypes' => array('image/gif'),
			'extensions'=>array('gif')
		),
		array('kind' => 'image.tiff', 'category' => 'image', 'label' => 'TIFF billede',
			'mimetypes' => array('image/tif','image/tiff'),
			'extensions'=>array('tif','tiff')
		),
		array('kind' => 'image.photoshop', 'category' => 'image', 'label' => 'Adobe Photoshop',
			'mimetypes' => array('application/x-photoshop'),
			'extensions'=>array('psd')
		),
		array('kind' => 'multimedia.flash', 'category' => 'multimedia', 'label' => 'Adobe Flash',
			'mimetypes' => array('application/x-shockwave-flash'),
			'extensions'=>array('swf')
		),
		array('kind' => 'document.html', 'category' => 'document', 'label' => 'HTML-dokument',
			'mimetypes' => array('text/html','application/xhtml+xml'),
			'extensions'=>array('html','htm','xhtml')
		),
		array('kind' => 'document.pdf', 'category' => 'document', 'label' => 'PDF-dokument',
			'mimetypes' => array('application/pdf'),
			'extensions'=>array('pdf')
		),
		array('kind' => 'archive.zip', 'category' => 'archive', 'label' => 'ZIP-arkiv',
			'mimetypes' => array('application/zip','application/x-gzip'),
			'extensions'=>array('zip','gz')
		),
		array('kind' => 'data.xml', 'category' => 'data', 'label' => 'XML-data',
			'mimetypes' => array('text/xml'),
			'extensions'=>array('xml','xsl')
		),
		array('kind' => 'document.text', 'category' => 'document', 'label' => 'Tekst',
			'mimetypes' => array('text/plain'),
			'extensions'=>array('txt')
		),
		array('kind' => 'document.rtf', 'category' => 'document', 'label' => 'RTF-dokument',
			'mimetypes' => array('text/rtf'),
			'extensions'=>array('rtf')
		),
		array('kind' => 'document.excel', 'category' => 'document', 'label' => 'Microsoft Excel',
			'mimetypes' => array('application/vnd.ms-excel'),
			'extensions'=>array('xls')
		),
		array('kind' => 'document.powerpoint', 'category' => 'document', 'label' => 'Microsoft PowerPoint',
			'mimetypes' => array('application/vnd.ms-powerpoint'),
			'extensions'=>array('ppt')
		),
		array('kind' => 'audio.windowsmedia', 'category' => 'audio', 'label' => 'Windows Media Audio',
			'mimetypes' => array('audio/x-ms-wma'),
			'extensions'=>array()
		),
		array('kind' => 'audio.windowsmedia.playlist', 'category' => 'audio', 'label' => 'Windows Media Playlist',
			'mimetypes' => array('application/vnd.ms-wpl'),
			'extensions'=>array('wpl')
		)
		
		
	);
	
	public static $categories = array(
		 'document' => 'Dokument'
		,'image' => 'Billede'
		,'multimedia' => 'Multimedie'
		,'movie' => 'Film'
		,'text' => 'Tekst'
	);
	
	function mimeTypeToInfo($mime) {
		foreach (FileService::$types as $type) {
			foreach ($type['mimetypes'] as $mimeType) {
				if ($mime===$mimeType) {
					return $type;
				}
			}
		}
		return null;
	}
	
	function extensionToInfo($ext) {
		foreach (FileService::$types as $type) {
			foreach ($type['extensions'] as $extension) {
				if ($ext===$extension) {
					return $type;
				}
			}
		}
		return null;
	}
	
	function mimeTypeToLabel($mime) {
		if ($info = FileService::mimeTypeToInfo($mime)) {
			return $info['label'];
		}
		return $mime;
	}
	
	function mimeTypeToKind($mime) {
		if ($info = FileService::mimeTypeToInfo($mime)) {
			return $info['kind'];
		}
		return '';
	}
	
	function mimeTypeToExtension($mime) {
		if ($info = FileService::mimeTypeToInfo($mime)) {
			$ext = $info['extensions'];
			if (count($ext)>0) {
				return $ext[0];
			}
		}
		return '';
	}
	
	function kindToMimeTypes($kind) {
		foreach (FileService::$types as $type) {
			if ($type['kind']===$kind) {
				return $type['mimetypes'];
			}
		}
		return null;
	}
	
	function extensionToMimeType($ext) {
		if ($info = FileService::extensionToInfo($ext)) {
			$mimes = $info['mimetypes'];
			if (count($mimes)>0) {
				return $mimes[0];
			}
		}
		return '';
	}
	
	function fileNameToMimeType($filename) {
		$ext = FileSystemService::getFileExtension($filename);
		return FileService::extensionToMimeType($ext);
	}

	/**
	 * Replaces an existing file object based on an uploaded file
	 * @param int $id The ID of the existing File object
	 * @return array An array describing the success of the procedure
	 */
	function replaceUploadedFile($id) {
		global $basePath;
		$fileName = FileSystemService::safeFilename($_FILES['file']['name']);
		$fileType = $_FILES["file"]["type"];
		$tempFile = $_FILES['file']['tmp_name'];
		$uploadDir = $basePath.'files/';
		$filePath = $uploadDir . $fileName;
		$fileSize = $_FILES["file"]["size"];

		$filePath = FileSystemService::findFreeFilePath($filePath);
		$fileName = FileSystemService::getFileBaseName($filePath);

		$errorMessage = false;
		$errorDetails='';

		if (file_exists($filePath)) {
			$errorMessage='Filen findes allerede';
		}
		else if (!move_uploaded_file($tempFile, $filePath)) {
			$errorMessage = 'Kunne ikke flytte filen fra cachen';
		}

		if (!$errorMessage) {
		
			$file = File::load($id);
			if ($file) {
				// Delete old file
				$oldFilename = $file->getFilename();
		
				if (!unlink ($basePath.'files/'.$oldFilename)) {
					$errorMessage='Kunne ikke slette alt fra serveren';
				}
				$file->setFilename($fileName);
				$file->setSize($fileSize);
				$file->setMimetype($fileType);
				$file->update();
				$file->publish();
			} else {
				$errorMessage='Kunne ikke finde fil med id='.$id;
			}
		}
		return array('success' => ($errorMessage===false),'errorMessage' => $errorMessage,'errorDetails' => $errorDetails);
	}
	
	/**
	 * Creates a new file object based on an uploaded file
	 * @param string $title The title of the uploaded file
	 * @param int $group Optional ID of FileGroup to place the file in
	 * @return array An array describing the success of the procedure
	 */
	function createUploadedFile($title='',$group=0) {
		global $basePath;
		$fileName = $_FILES['file']['name'];
		$fileType=$_FILES["file"]["type"];
		$tempFile=$_FILES['file']['tmp_name'];
		$fileSize=$_FILES["file"]["size"];
		
		if ($fileType=='application/octet-stream') {
			$fileType = FileService::fileNameToMimeType($fileName);
		}
		$fileName = FileSystemService::safeFilename($fileName);
		$uploadDir = $basePath.'files/';
		$filePath = $uploadDir . $fileName;

		$filePath = FileSystemService::findFreeFilePath($filePath);
		$fileName = FileSystemService::getFileBaseName($filePath);

		$errorMessage=false;
		$errorDetails='';

		if (file_exists($filePath)) {
			$errorMessage='Filen findes allerede';
		}
		else if (!move_uploaded_file($tempFile, $filePath)) {
			$errorMessage='Kunne ikke flytte filen fra cachen';
		}
		$result = new ImportResult();
		if (!$errorMessage) {

			if ($title=='') {
				$title = FileSystemService::filenameToTitle($fileName);
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
				$sql="insert into filegroup_file (file_id,filegroup_id) values (".Database::int($fileId).",".Database::int($group).")";
				Database::insert($sql);
			}
			$result->setSuccess(true);
			$result->setObject($file);
		}
		return $result;
	}
	
	function createFromUrl($url) {
		global $basePath;
		$remote = new RemoteFile($url);
		$path = $remote->writeToTempFile();
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
				$extension = FileService::mimeTypeToExtension($type);
				if ($extension!=null) {
					$filename.='.'.$extension;
				}
			}
		}
		$filename = FileSystemService::safeFilename($filename);
		$newPath = FileSystemService::findFreeFilePath($basePath.'files/'.$filename);
		if (!@rename($path,$newPath)) {
			return array('success' => false,'message' => 'Der skete en uventet fejl ');
		}
		
		$title = FileSystemService::filenameToTitle($filename);

		$file = new File();
		$file->setTitle($title);
		$file->setFilename($filename);
		$file->setSize($size);
		$file->setMimetype($type);
		$file->create();
		$file->publish();
		return array('success' => true);
	}
	
	function getLatestFileId() {
		$sql = "select max(object_id) as id from file";
		if ($row = Database::selectFirst($sql)) {
			return intval($row['id']);
		}
		return null;
	}
	
	function getFileFilename($id) {
		$sql = "select filename from file where object_id=".Database::int($id);
		if ($row = Database::selectFirst($sql)) {
			return $row['filename'];
		}
		return NULL;
	}
	
	function getGroupCounts() {
		$out = array();
		$sql="select distinct object.id,object.title,count(file.object_id) as filecount from filegroup, filegroup_file, file,object  where filegroup_file.filegroup_id=filegroup.object_id and filegroup_file.file_id = file.object_id and object.id=filegroup.object_id group by filegroup.object_id union select object.id,object.title,'0' from object left join filegroup_file on filegroup_file.filegroup_id=object.id where object.type='filegroup' and filegroup_file.file_id is null order by title";
		$result = Database::select($sql);
		while ($row = Database::next($result)) {
			$out[] = array(
				'id' => $row['id'],
				'title' => $row['title'],
				'count' => $row['filecount']
			);
		}
		Database::free($result);
		return $out;
	}
}