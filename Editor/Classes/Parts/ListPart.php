<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Part
 */
require_once($basePath.'Editor/Classes/Parts/Part.php');

Part::$schema['list'] = array(
	'fields' => array(
		'align' => array( 'type' => 'text' ),
		'width' => array( 'type' => 'text' ),
		'title' => array( 'type' => 'text' ),
		'maxItems' => array( 'type' => 'int', 'column' => 'maxitems' ),
		'variant' => array( 'type' => 'text' ),
		'timeCount' => array( 'type' => 'int', 'column' => 'time_count' ),
		'timeType' => array( 'type' => 'text', 'column' => 'time_type' ),
		'showSource' => array( 'type' => 'boolean', 'column' => 'show_source' )
	),
	'relations' => array(
		'objectIds' => array( 'table' => 'part_list_object', 'fromColumn' => 'part_id', 'toColumn' => 'object_id' )
	)
);

class ListPart extends Part
{
	var $align;
	var $width;
	var $title;
	var $maxItems;
	var $variant;
	var $timeCount;
	var $timeType;
	var $showSource;
	var $objectIds;
	
	function ListPart() {
		parent::Part('list');
	}
	
	function load($id) {
		return Part::load('list',$id);
	}
	
	function setAlign($align) {
	    $this->align = $align;
	}

	function getAlign() {
	    return $this->align;
	}
	
	function setWidth($width) {
	    $this->width = $width;
	}

	function getWidth() {
	    return $this->width;
	}
	
	function setTitle($title) {
	    $this->title = $title;
	}

	function getTitle() {
	    return $this->title;
	}
	
	function setMaxItems($maxItems) {
	    $this->maxItems = $maxItems;
	}
	
	function setVariant($variant) {
	    $this->variant = $variant;
	}

	function getVariant() {
	    return $this->variant;
	}
	
	function setTimeCount($timeCount) {
	    $this->timeCount = $timeCount;
	}

	function getTimeCount() {
	    return $this->timeCount;
	}
	
	function setTimeType($timeType) {
	    $this->timeType = $timeType;
	}

	function getTimeType() {
	    return $this->timeType;
	}
	
	function getMaxItems() {
	    return $this->maxItems;
	}
	
	function setShowSource($showSource) {
	    $this->showSource = $showSource;
	}

	function getShowSource() {
	    return $this->showSource;
	}
	
	function setObjectIds($objectIds) {
	    $this->objectIds = $objectIds;
	}

	function getObjectIds() {
	    return $this->objectIds;
	}
	
}
?>