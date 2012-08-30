<?php
if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

class FileUpload {

	var $path;
	var $fileName;
	var $size;
	var $mimeType;
	var $ERROR_NO_ERROR = 0;
	var $ERROR_COULD_NOT_MOVE_FILE_FROM_TEMP = 1;
	
	function FileUpload() {
		
	}
	
	function getFilePath() {
		return $this->path;
	}
	
	function process($field) {
		global $basePath;
		$this->fileName=FileSystemService::safeFilename($_FILES[$field]['name']);
		$this->mimeType=$_FILES[$field]["type"];
		$tempFile=$_FILES['file']['tmp_name'];
		$uploadDir = $basePath.'local/cache/temp/';
		$filePath = $uploadDir . $this->fileName;
		$this->size=$_FILES[$field]["size"];

		$this->path = FileSystemService::findFreeFilePath($filePath);

		$error = $this->ERROR_NO_ERROR;
		$success = false;
		if (!move_uploaded_file($tempFile, $this->path)) {
			$error = $this->ERROR_COULD_NOT_MOVE_FILE_FROM_TEMP;
		} else {
			$success = true;
		}
		return array('success' => $success,'error' => $error);
	}
	
	function clean() {
		return @unlink($this->path);
	}
}
?>