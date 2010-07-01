<?

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
		$this->fileName=$_FILES[$field]['name'];
		$this->mimeType=$_FILES[$field]["type"];
		$this->path = $_FILES['file']['tmp_name'];
		$this->size=$_FILES[$field]["size"];

		$error=$this->ERROR_NO_ERROR;
		$success = false;
		return array('success' => $success,'error' => $error);
	}
	
	function clean() {
		return @unlink($this->path);
	}
}
?>