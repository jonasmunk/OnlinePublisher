<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Part
 */
if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

Entity::$schema['ImagePart'] = [
	'table' => 'part_image',
	'properties' => [
		'imageId' => ['type'=>'int', 'column' => 'image_id','relation'=>['class'=>'Image','property'=>'id']],
		'text' => [ 'type' => 'string' ],
		'align' => [ 'type' => 'string' ],
		'frame' => [ 'type' => 'string' ],
		'greyscale' => [ 'type' => 'boolean' ],
		'scaleMethod' => [ 'type' => 'string', 'column' => 'scalemethod' ],
		'scalePercent' => [ 'type' => 'int', 'column' => 'scalepercent' ],
		'scaleWidth' => [ 'type' => 'int', 'column' => 'scalewidth' ],
		'scaleHeight' => [ 'type' => 'int', 'column' => 'scaleHeight' ]
	]
];

class ImagePart extends Part
{
	var $imageId;
	var $text;
	var $align;
	var $greyscale;
	var $scaleMethod;
	var $scalePercent;
	var $scaleWidth;
	var $scaleHeight;
	var $frame;
	
	function ImagePart() {
		parent::Part('image');
	}
	
	static function load($id) {
		return Part::get('image',$id);
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
	
	function setAlign($align) {
	    $this->align = $align;
	}

	function getAlign() {
	    return $this->align;
	}
	
	
	function setGreyscale($greyscale) {
	    $this->greyscale = $greyscale;
	}

	function getGreyscale() {
	    return $this->greyscale;
	}
	
	function setScaleMethod($scaleMethod) {
	    $this->scaleMethod = $scaleMethod;
	}
	
	function getScaleMethod() {
	    return $this->scaleMethod;
	}

	function setScalePercent($scalePercent) {
	    $this->scalePercent = $scalePercent;
	}

	function getScalePercent() {
	    return $this->scalePercent;
	}
	
	function setScaleWidth($scaleWidth) {
	    $this->scaleWidth = $scaleWidth;
	}

	function getScaleWidth() {
	    return $this->scaleWidth;
	}
	
	function setScaleHeight($scaleHeight) {
	    $this->scaleHeight = $scaleHeight;
	}

	function getScaleHeight() {
	    return $this->scaleHeight;
	}
	
	function setFrame($frame) {
	    $this->frame = $frame;
	}

	function getFrame() {
	    return $this->frame;
	}
	
	
}
?>