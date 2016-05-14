<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Formats
 */

if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}
class VRecurrenceRule {
	
	var $frequency;
	var $interval;
	var $count;
	var $weekStart;
	var $until;
	var $byMonth;
	var $byDay;
	var $byMonthDay;
	var $byYearDay;
	var $byWeekNumber;
	
	function VRecurrenceRule() {
		
	}
	
	function setFrequency($frequency) {
	    $this->frequency = $frequency;
	}

	function getFrequency() {
	    return $this->frequency;
	}
	
	function setInterval($interval) {
	    $this->interval = $interval;
	}

	function getInterval() {
	    return $this->interval;
	}
	
	function setCount($count) {
	    $this->count = $count;
	}

	function getCount() {
	    return $this->count;
	}
	
	function setWeekStart($weekStart) {
	    $this->weekStart = $weekStart;
	}

	function getWeekStart() {
	    return $this->weekStart;
	}
	
	function setUntil($until) {
	    $this->until = $until;
	}

	function getUntil() {
	    return $this->until;
	}
	
	function setByMonth($byMonth) {
	    $this->byMonth = $byMonth;
	}

	function getByMonth() {
	    return $this->byMonth;
	}
	
	function setByDay($byDay) {
	    $this->byDay = $byDay;
	}

	function getByDay() {
	    return $this->byDay;
	}
	
	function setByMonthDay($byMonthDay) {
	    $this->byMonthDay = $byMonthDay;
	}

	function getByMonthDay() {
	    return $this->byMonthDay;
	}
	
	function setByYearDay($byYearDay) {
	    $this->byYearDay = $byYearDay;
	}

	function getByYearDay() {
	    return $this->byYearDay;
	}
	
	function setByWeekNumber($byWeekNumber) {
	    $this->byWeekNumber = $byWeekNumber;
	}

	function getByWeekNumber() {
	    return $this->byWeekNumber;
	}
	
}
?>