<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Part
 */
if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

Part::$schema['movie'] = [
	'fields' => [
		'fileId' => [ 'type' => 'int', 'column' => 'file_id'],
		'imageId' => [ 'type' => 'int', 'column' => 'image_id' ],
		'text' => [ 'type' => 'text' ],
		'url' => [ 'type' => 'text' ],
		'code' => [ 'type' => 'text' ],
		'width' => [ 'type' => 'text' ],
		'height' => [ 'type' => 'text' ]
  ]
];

class MoviePart extends Part
{
	var $fileId;
	var $imageId;
	var $text;
	var $url;
	var $code;
    var $width;
    var $height;
	
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
  
	function setWidth($width) {
	    $this->width = $width;
	}

	function getWidth() {
	    return $this->width;
	}
  
	function setHeight($height) {
	    $this->height = $height;
	}

	function getHeight() {
	    return $this->height;
	}
  
}
?>