<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Part
 */
if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

Entity::$schema['NewsPart'] = [
	'table' => 'part_news',
	'properties' => [
		'align' => [ 'type' => 'text' ],
		'width' => [ 'type' => 'text' ],
		'newsId' => ['type'=>'int', 'column' => 'news_id','relation'=>['class'=>'News','property'=>'id']],
		'mode' => [ 'type' => 'text' ],
  		'title' => [ 'type' => 'text' ],
		'sortBy' => [ 'type' => 'text', 'column' => 'sortby' ],
  		'sortDir' => [ 'type' => 'text', 'column' => 'sortdir' ],
		'maxItems' => [ 'type' => 'int', 'column' => 'maxitems' ],
		'timeType' => [ 'type' => 'text', 'column' => 'timetype' ],
  		'timeCount' => [ 'type' => 'int', 'column' => 'timecount' ],
		'startDate' => [ 'type' => 'datetime', 'column' => 'startdate' ],
		'endDate' => [ 'type' => 'datetime', 'column' => 'enddate' ],
		'variant' => [ 'type' => 'text' ]
	],
	'relations' => [
		'newsGroupIds' => [ 'table' => 'part_news_newsgroup', 'fromColumn' => 'part_id', 'toColumn' => 'newsgroup_id' ]
	]
];

class NewsPart extends Part
{
	var $align;
	var $width;
	var $newsId;
	var $mode;
	var $title;
	var $sortBy;
	var $sortDir;
	var $maxItems;
	var $timeType;
	var $timeCount;
	var $startDate;
	var $endDate;
	var $variant;
	var $newsGroupIds;
	
	function NewsPart() {
		parent::Part('news');
	}
	
	static function load($id) {
		return Part::get('news',$id);
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
	
	function setNewsId($newsId) {
	    $this->newsId = $newsId;
	}

	function getNewsId() {
	    return $this->newsId;
	}
	
	function setMode($mode) {
	    $this->mode = $mode;
	}

	function getMode() {
	    return $this->mode;
	}
	
	function setTitle($title) {
	    $this->title = $title;
	}

	function getTitle() {
	    return $this->title;
	}
	
	function setSortBy($sortBy) {
	    $this->sortBy = $sortBy;
	}

	function getSortBy() {
	    return $this->sortBy;
	}
	
	function setSortDir($sortDir) {
	    $this->sortDir = $sortDir;
	}

	function getSortDir() {
	    return $this->sortDir;
	}
	
	function setMaxItems($maxItems) {
	    $this->maxItems = $maxItems;
	}

	function getMaxItems() {
	    return $this->maxItems;
	}
	
	function setTimeType($timeType) {
	    $this->timeType = $timeType;
	}

	function getTimeType() {
	    return $this->timeType;
	}
	
	function setTimeCount($timeCount) {
	    $this->timeCount = $timeCount;
	}

	function getTimeCount() {
	    return $this->timeCount;
	}
	
	function setStartDate($startDate) {
	    $this->startDate = $startDate;
	}

	function getStartDate() {
	    return $this->startDate;
	}
	
	function setEndDate($endDate) {
	    $this->endDate = $endDate;
	}

	function getEndDate() {
	    return $this->endDate;
	}
	
	function setVariant($variant) {
	    $this->variant = $variant;
	}

	function getVariant() {
	    return $this->variant;
	}
	
	function setNewsGroupIds($newsGroupIds) {
	    $this->newsGroupIds = $newsGroupIds;
	}

	function getNewsGroupIds() {
	    return $this->newsGroupIds;
	}
	
	
}
?>