<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Part
 */
if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

Entity::$schema['ListPart'] = array(
	'table' => 'part_list',
	'properties' => array(
		'align' => array( 'type' => 'string' ),
		'width' => array( 'type' => 'string' ),
		'title' => array( 'type' => 'string' ),
		'maxItems' => array( 'type' => 'int', 'column' => 'maxitems' ),
		'variant' => array( 'type' => 'string' ),
		'timeCount' => array( 'type' => 'int', 'column' => 'time_count' ),
		'timeType' => array( 'type' => 'string', 'column' => 'time_type' ),
		'showSource' => array( 'type' => 'boolean', 'column' => 'show_source' ),
		'showText' => array( 'type' => 'boolean', 'column' => 'show_text' ),
		'showTimeZone' => array( 'type' => 'boolean', 'column' => 'show_timezone' ),
		'timezone' => array( 'type' => 'string' ),
		'maxTextLength' => array( 'type' => 'int', 'column' => 'maxtextlength' ),
		'sortDirection' => array( 'type' => 'string', 'column' => 'sort_direction' )
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
	var $sortDirection;
	
	var $showText;
	var $showTimeZone;
	var $timezone;
	var $maxTextLength;
	
	function ListPart() {
		parent::Part('list');
	}
	
	static function load($id) {
		return Part::get('list',$id);
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
	
	function setShowText($showText) {
	    $this->showText = $showText;
	}

	function getShowText() {
	    return $this->showText;
	}
	
	function setShowTimeZone($showTimeZone) {
	    $this->showTimeZone = $showTimeZone;
	}

	function getShowTimeZone() {
	    return $this->showTimeZone;
	}
	
	function setTimezone($timezone) {
	    $this->timezone = $timezone;
	}

	function getTimezone() {
	    return $this->timezone;
	}

	function setMaxTextLength($maxTextLength) {
	    $this->maxTextLength = $maxTextLength;
	}

	function getMaxTextLength() {
	    return $this->maxTextLength;
	}
	
	function setSortDirection($sortDirection) {
	    $this->sortDirection = $sortDirection;
	}

	function getSortDirection() {
	    return $this->sortDirection;
	}
	
	
}
?>