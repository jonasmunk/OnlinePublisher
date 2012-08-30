<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Part
 */
if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

Part::$schema['file'] = array(
	'fields' => array(
		'fileId' => array( 'type' => 'int', 'column' => 'file_id' )
	)
);

class FilePart extends Part
{
	var $fileId;
	
	function FilePart() {
		parent::Part('file');
	}
	
	function load($id) {
		return Part::load('file',$id);
	}
	
	function setFileId($fileId) {
	    $this->fileId = $fileId;
	}

	function getFileId() {
	    return $this->fileId;
	}
	
}
?>