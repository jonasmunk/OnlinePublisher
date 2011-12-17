<?php
if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}
class TemporaryFolder {

	var $dir;
	
	function TemporaryFolder() {
	}
	
	function make() {
		$this->dir = FileSystemService::getFreeTempPath();
		if (mkdir($this->dir)) {
			return $this->dir;
		} else {
			return false;
		}
	}
	
	function getPath() {
		return $this->dir;
	}
	
	function remove() {
		return FileSystemService::remove($this->dir);
	}
	
	function getFiles() {
		return FileSystemService::find(array('dir'=>$this->dir));
	}
}
?>