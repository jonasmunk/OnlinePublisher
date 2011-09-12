<?
/**
 * @package OnlinePublisher
 * @subpackage Classes.Formats
 */

if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}
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