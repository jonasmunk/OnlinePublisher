<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Modules.Water
 */
if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

class StatisticsQuery {
	
	var $startTime;
	var $endTime;
	var $resolution;
	
	function withTime($time) {
		if ($time=='year') {
			$this->startTime = DateUtils::getYearStart();
			$this->endTime = DateUtils::getYearEnd();			
		} else if ($time=='month') {
			$this->startTime = DateUtils::getMonthStart();
			$this->endTime = DateUtils::getMonthEnd();
		} else if ($time=='week') {
			$this->startTime = DateUtils::getWeekStart();
			$this->endTime = DateUtils::getWeekEnd();
		}
		return $this;
	}
	
	function withResolution($resolution) {
	    $this->resolution = $resolution;
		return $this;
	}

	function setStartTime($startTime) {
	    $this->startTime = $startTime;
	}

	function getStartTime() {
	    return $this->startTime;
	}
	
	function setEndTime($endTime) {
	    $this->endTime = $endTime;
	}

	function getEndTime() {
	    return $this->endTime;
	}
	

	function getResolution() {
	    return $this->resolution;
	}
	
}