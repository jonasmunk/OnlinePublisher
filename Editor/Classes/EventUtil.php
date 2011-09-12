<?

if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}
class EventUtil {
	
	function EventUtil() {
	}
	
	//////////////////////// Static /////////////////////
	

	function getEventInsidePeriod($startDate,$endDate,$event) {
		if (($event['startDate']<=$startDate && $event['endDate']<=$startDate) || ($event['startDate']>=$endDate && $event['endDate']>=$endDate)) {
			return null;
		} else {
			if ($event['startDate']<$startDate) {
				$event['startDate']=$startDate;
			}
			if ($event['endDate']>$endDate) {
				$event['endDate']=$endDate;
			}
			return $event;
		}
	}
	
	function isEventsColliding($event,$other) {
		if (($event['startDate']<=$other['startDate'] && $event['endDate']<=$other['startDate']) || ($event['startDate']>=$other['endDate'] && $event['endDate']>=$other['endDate'])) {
			return false;
		} else {
			return true;
		}
	}
}
?>