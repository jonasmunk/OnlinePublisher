<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Part
 */
if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

Entity::$schema['ImagegalleryPart'] = [
	'table' => 'part_imagegallery',
	'properties' => [
		'variant'   	=> [ 'type' => 'string' ],
		'height'		=> [ 'type' => 'int' ],
		'width'		    => [ 'type' => 'int' ],
		'imageGroupId' => ['type'=>'int', 'column' => 'imagegroup_id','relation'=>['class'=>'Imagegroup','property'=>'id']],
		'framed'		=> [ 'type' => 'boolean' ],
		'frame'         => [ 'type' => 'string' ],
		'showTitle'		=> [ 'type' => 'boolean', 'column' => 'show_title' ]
	]
];

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