<?
/**
 * @package OnlinePublisher
 * @subpackage Classes.Formats
 */

if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}
class VEvent {
	
	var $summary = '';
	var $description = '';
	var $location = '';
	var $startDate;
	var $endDate;
	var $duration;
	var $timeStamp;
	var $uniqueId;
	var $recurrenceRules = array();
	var $url;
	
	function VEvent() {
	}
	
	function addRecurrenceRule($rule) {
		$this->recurrenceRules[] = $rule;
	}
	
	function getRecurrenceRules() {
		return $this->recurrenceRules;
	}
	
	function isRecurring() {
		return count($this->recurrenceRules)>0;
	}
	
	function setSummary($summary) {
	    $this->summary = $summary;
	}

	function getSummary() {
	    return $this->summary;
	}
	
	function setDescription($description) {
	    $this->description = $description;
	}

	function getDescription() {
	    return $this->description;
	}
	
	function setLocation($location) {
	    $this->location = $location;
	}

	function getLocation() {
	    return $this->location;
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
	
	function setTimeStamp($timeStamp) {
	    $this->timeStamp = $timeStamp;
	}

	function getTimeStamp() {
	    return $this->timeStamp;
	}
	
	function setUniqueId($uniqueId) {
	    $this->uniqueId = $uniqueId;
	}

	function getUniqueId() {
	    return $this->uniqueId;
	}
	
	function setDuration($duration) {
	    $this->duration = $duration;
	}

	function getDuration() {
	    return $this->duration;
	}
	
	function setUrl($url) {
	    $this->url = $url;
	}

	function getUrl() {
	    return $this->url;
	}
	
	
}
?>