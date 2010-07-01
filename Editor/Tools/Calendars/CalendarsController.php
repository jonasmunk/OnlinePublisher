<?
require_once $basePath.'Editor/Include/Session.php';
require_once $basePath.'Editor/Classes/DateUtil.php';

class CalendarsController {
	
	function setUpdateSelection($value) {
		setToolSessionVar('calendars','updateSelection',$value);
	}

	function getUpdateSelection() {
		return getToolSessionVar('calendars','updateSelection',false);
	}
	
	function getBaseWindow() {
		$selection = CalendarsController::getSelection();
		if ($selection=='overview') {
			return 'Overview.php';
		} else if (substr($selection,0,6)=='source') {
			return 'Source.php';
		} else if (substr($selection,0,8)=='calendar') {
			return 'Calendar.php';
		}
	}
	
	function setSelection($value) {
		if ($value!=CalendarsController::getSelection()) {
			CalendarsController::setUpdateSelection(true);
		}
		setToolSessionVar('calendars','selection',$value);
	}

	function getSelection() {
		return getToolSessionVar('calendars','selection','overview');
	}
	
	function setSourceId($value) {
		setToolSessionVar('calendars','source',$value);
	}

	function getSourceId() {
		return getToolSessionVar('calendars','source',-1);
	}
	
	function setCalendarId($value) {
		setToolSessionVar('calendars','calendar',$value);
	}

	function getCalendarId() {
		return getToolSessionVar('calendars','calendar',-1);
	}
	
	function setListTimespan($value) {
		setToolSessionVar('calendars','list-timespan',$value);
	}

	function getListTimespan() {
		return getToolSessionVar('calendars','list-timespan','thisMonth');
	}
	
	function getListTimespanDates() {
		$timeSpan = CalendarsController::getListTimespan();
		$dates = array();
		if ($timeSpan=='thisWeek') {
			$dates['startDate'] = DateUtil::getWeekStart(time());
			$dates['endDate'] = DateUtil::getWeekEnd(time());
		} elseif ($timeSpan=='thisMonth') {
			$dates['startDate'] = DateUtil::getMonthStart(time());
			$dates['endDate'] = DateUtil::getMonthEnd(time());
		} elseif ($timeSpan=='thisYear') {
			$dates['startDate'] = DateUtil::getYearStart(time());
			$dates['endDate'] = DateUtil::getYearEnd(time());
		} else {
			$dates['startDate'] = DateUtil::getYearEnd(DateUtil::addYears(time(),-10));
			$dates['endDate'] = DateUtil::getYearEnd(DateUtil::addYears(time(),2));
		}
		return $dates;
	}
}
?>