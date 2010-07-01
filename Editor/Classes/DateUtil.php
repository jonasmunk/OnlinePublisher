<?

class DateUtil {
	
	function DateUtil() {
	}
	
	//////////////////////// Static /////////////////////
	
	function stripTime($timestamp) {
		$year = date('Y',$timestamp);
		$month = date('n',$timestamp);
		$date = date('j',$timestamp);
		return mktime(0,0,0,$month,$date,$year);
	}
	
	function getWeekStart($timestamp) {
		$year = date('Y',$timestamp);
		$weekday = date('w',$timestamp);
		if ($weekday==0) {
			$weekday=6;
		} else {
			$weekday--;
		}
		$month = date('n',$timestamp);
		$date = date('j',$timestamp);
		return mktime(0,0,0,$month,$date-$weekday,$year);
	}
	
	function getWeekDay($timestamp) {
		$weekday = date('w',$timestamp);
		if ($weekday==0) {
			$weekday=6;
		} else {
			$weekday--;
		}
		return $weekday;
	}
	
	function getWeekEnd($timestamp) {
		$year = date('Y',$timestamp);
		$weekday = date('w',$timestamp);
		$month = date('n',$timestamp);
		$date = date('j',$timestamp);
		return mktime(23,59,59,$month,$date-$weekday+7,$year);
	}
	
	function getMonthStart($timestamp) {
		$year = date('Y',$timestamp);
		$month = date('n',$timestamp);
		return mktime(0,0,0,$month,1,$year);
	}
	
	function getMonthEnd($timestamp) {
		$year = date('Y',$timestamp);
		$month = date('n',$timestamp);
		return mktime(0,0,-1,$month+1,1,$year);
	}
	
	function getYearStart($timestamp) {
		$year = date('Y',$timestamp);
		return mktime(0,0,0,1,1,$year);
	}
	
	function getYearEnd($timestamp) {
		$year = date('Y',$timestamp);
		return mktime(0,0,-1,1,1,$year+1);
	}
	
	function getSecondsSinceMidnight($timestamp) {
		$hours = date('H',$timestamp);
		$minutes = date('i',$timestamp);
		$seconds = date('s',$timestamp);
		return $hours*60*60+$minutes*60+$seconds;
	}
	
	
	//
	
	
	function addSeconds($timestamp,$secs) {
		$hours = date('H',$timestamp);
		$minutes = date('i',$timestamp);
		$seconds = date('s',$timestamp);
		$year = date('Y',$timestamp);
		$month = date('n',$timestamp);
		$date = date('j',$timestamp);
		return mktime($hours,$minutes,$seconds+$secs,$month,$date,$year);
	}
	
	function addDays($timestamp,$days) {
		$hours = date('H',$timestamp);
		$minutes = date('i',$timestamp);
		$seconds = date('s',$timestamp);
		$year = date('Y',$timestamp);
		$month = date('n',$timestamp);
		$date = date('j',$timestamp);
		return mktime($hours,$minutes,$seconds,$month,$date+$days,$year);
	}
	
	function addYears($timestamp,$years) {
		$hours = date('H',$timestamp);
		$minutes = date('i',$timestamp);
		$seconds = date('s',$timestamp);
		$year = date('Y',$timestamp);
		$month = date('n',$timestamp);
		$date = date('j',$timestamp);
		return mktime($hours,$minutes,$seconds,$month,$date,$year+$years);
	}
	
	function addMonths($timestamp,$months) {
		$hours = date('H',$timestamp);
		$minutes = date('i',$timestamp);
		$seconds = date('s',$timestamp);
		$year = date('Y',$timestamp);
		$month = date('n',$timestamp);
		$date = date('j',$timestamp);
		return mktime($hours,$minutes,$seconds,$month+$months,$date,$year);
	}
}
?>