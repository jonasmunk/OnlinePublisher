<?
/**
 * @package OnlinePublisher
 * @subpackage Classes.Formats
 */

class DBUCalendar {
	var $events = array();
	
	function addEvent($event) {
	    $this->events[] = $event;
	}

	function getEvents() {
	    return $this->events;
	}
	
}
?>