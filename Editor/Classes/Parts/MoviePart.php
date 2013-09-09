<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Part
 */
if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

Part::$schema['movie'] = array(
	'fields' => array(
		'fileId' => array( 'type' => 'int', 'column' => 'file_id' ),
		'text' => array( 'type' => 'text' )
	)
);

class MoviePart extends Part
{
	var $fileId;
	var $text;
	
	function MoviePart() {
		parent::Part('movie');
	}
	
	function load($id) {
		return Part::load('movie',$id);
	}
	
	function setFileId($fileId) {
	    $this->fileId = $fileId;
	}

	function getFileId() {
	    return $this->fileId;
	}
	
	function setText($text) {
	    $this->text = $text;
	}

	function getText() {
	    return $this->text;
	}
}
?>