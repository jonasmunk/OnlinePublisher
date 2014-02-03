<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Part
 */
if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

Part::$schema['imagegallery'] = array(
	'fields' => array(
		'variant'   	=> array( 'type' => 'text' ),
		'height'		=> array( 'type' => 'int' ),
		'width'		    => array( 'type' => 'int' ),
		'imageGroupId'	=> array( 'type' => 'int', 'column' => 'imagegroup_id' ),
		'framed'		=> array( 'type' => 'boolean' ),
		'frame'         => array( 'type' => 'text' ),
		'showTitle'		=> array( 'type' => 'boolean', 'column' => 'show_title' )
	)
);
Entity::$schema['ImagegalleryPart'] = array(
	'table' => 'part_imagegallery',
	'properties' => array(
		'imageGroupId' => array('type'=>'int','relation'=>array('class'=>'Imagegroup','property'=>'id'))
	)
);

class ImagegalleryPart extends Part
{
	var $variant;
	var $height;
	var $width;
	var $imageGroupId;
	var $framed;
	var $frame;
	var $showTitle;
	
	function ImagegalleryPart() {
		parent::Part('imagegallery');
	}
	
	static function load($id) {
		return Part::get('imagegallery',$id);
	}
	
	function setVariant($variant) {
	    $this->variant = $variant;
	}

	function getVariant() {
	    return $this->variant;
	}
	
	function setHeight($height) {
	    $this->height = $height;
	}

	function getHeight() {
	    return $this->height;
	}
	
	function setWidth($width) {
	    $this->width = $width;
	}

	function getWidth() {
	    return $this->width;
	}
	
	function setImageGroupId($imageGroupId) {
	    $this->imageGroupId = $imageGroupId;
	}

	function getImageGroupId() {
	    return $this->imageGroupId;
	}
	
	function setFramed($framed) {
	    $this->framed = $framed;
	}

	function getFramed() {
	    return $this->framed;
	}
	
	function setFrame($frame) {
	    $this->frame = $frame;
	}

	function getFrame() {
	    return $this->frame;
	}
	
	function setShowTitle($showTitle) {
	    $this->showTitle = $showTitle;
	}

	function getShowTitle() {
	    return $this->showTitle;
	}
}
?>