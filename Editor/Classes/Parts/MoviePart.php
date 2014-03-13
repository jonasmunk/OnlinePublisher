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
		'imageId' => array( 'type' => 'int', 'column' => 'image_id' ),
		'text' => array( 'type' => 'text' ),
		'url' => array( 'type' => 'text' ),
		'code' => array( 'type' => 'text' )
	)
);

class MoviePart extends Part
{
	var $fileId;
	var $imageId;
	var $text;
	var $url;
	var $code;
	
	function MoviePart() {
		parent::Part('movie');
	}
	
	static function load($id) {
		return Part::get('movie',$id);
	}
	
	function setFileId($fileId) {
	    $this->fileId = $fileId;
	}

	function getFileId() {
	    return $this->fileId;
	}
	
	function setImageId($imageId) {
	    $this->imageId = $imageId;
	}

	function getImageId() {
	    return $this->imageId;
	}
	
	function setText($text) {
	    $this->text = $text;
	}

	function getText() {
	    return $this->text;
	}
	
	function setUrl($url) {
	    $this->url = $url;
	}

	function getUrl() {
	    return $this->url;
	}
	
	function setCode($code) {
	    $this->code = $code;
	}

	function getCode() {
	    return $this->code;
	}
}
?>