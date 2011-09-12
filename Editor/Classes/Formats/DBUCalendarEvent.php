<?
/**
 * @package OnlinePublisher
 * @subpackage Classes.Formats
 */

if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}
class DBUCalendarEvent {
	
	var $location;
	var $homeTeam;
	var $guestTeam;
	var $startDate;
	var $endDate;
	var $score;

	function setLocation($location) {
	    $this->location = $location;
	}

	function getLocation() {
	    return $this->location;
	}
	
	function setHomeTeam($homeTeam) {
	    $this->homeTeam = $homeTeam;
	}

	function getHomeTeam() {
	    return $this->homeTeam;
	}
	
	function setGuestTeam($guestTeam) {
	    $this->guestTeam = $guestTeam;
	}

	function getGuestTeam() {
	    return $this->guestTeam;
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
	
	function setScore($score) {
	    $this->score = $score;
	}

	function getScore() {
	    return $this->score;
	}
	
}
?>