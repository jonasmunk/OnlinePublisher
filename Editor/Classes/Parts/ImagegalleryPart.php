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
		'imageGroupId'	=> array( 'type' => 'int', 'column' => 'imagegroup_id' ),
		'framed'		=> array( 'type' => 'boolean' ),
		'showTitle'		=> array( 'type' => 'boolean', 'column' => 'show_title' )
	)
);
Entity::$schema['ImagegalleryPart'] = array(
	'table' => 'part_imagegallery',
	'properties' => array(
		'imageGroupId' => array('type'=>'int','relation'=>array('class'=>'ImageGroup','property'=>'id'))
	)
);

class ImagegalleryPart extends Part
{
	var $variant;
	var $height;
	var $imageGroupId;
	var $framed;
	var $showTitle;
	
	function ImagegalleryPart() {
		parent::Part('imagegallery');
	}
	
	function load($id) {
		return Part::load('imagegallery',$id);
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
	
	function setImageGroupId($imageGroupId) {
	    $this->imageGroupId = $imageGroupId;
	}

	function getImageGroupId() {
	    return $this->imageGroupId;
	}
	
	function setFramed($framed) {
	    $this->framed = $framed;
	}
	
	function setShowTitle($showTitle) {
	    $this->showTitle = $showTitle;
	}

	function getShowTitle() {
	    return $this->showTitle;
	}

	function getFramed() {
	    return $this->framed;
	}
}
?>