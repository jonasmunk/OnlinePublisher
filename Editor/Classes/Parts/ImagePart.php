<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Part
 */
if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

Part::$schema['image'] = array(
	'fields' => array(
		'imageId' => array( 'type' => 'int', 'column' => 'image_id' ),
		'text' => array( 'type' => 'text' ),
		'align' => array( 'type' => 'text' ),
		'frame' => array( 'type' => 'text' ),
		'greyscale' => array( 'type' => 'boolean' ),
		'scaleMethod' => array( 'type' => 'text', 'column' => 'scalemethod' ),
		'scalePercent' => array( 'type' => 'int', 'column' => 'scalepercent' ),
		'scaleWidth' => array( 'type' => 'int', 'column' => 'scalewidth' ),
		'scaleHeight' => array( 'type' => 'int', 'column' => 'scaleHeight' )
	)
);
Entity::$schema['ImagePart'] = array(
	'table' => 'part_image',
	'properties' => array(
		'imageId' => array('type'=>'int','relation'=>array('class'=>'Image','property'=>'id'))
	)
);

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