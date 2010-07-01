<?

class UserInterface {
	
	function UserInterface() {
	}
	
	//////////////////////// Static /////////////////////
		
	function presentFuzzyDate($timestamp) {
		if ($timestamp) {
			setlocale(LC_TIME, "da_DK");
			if (strftime("%e",time())==strftime("%e",$timestamp)) {
				return UserInterface::presentShortTime($timestamp);
			} else {
				return UserInterface::presentLongDateTime($timestamp);
			}
		}
	}
		
	function presentShortDate($timestamp) {
		if ($timestamp) {
			setlocale(LC_TIME, "da_DK");
			return strftime("%e. %b",$timestamp);
		}
	}
	
	function presentDate($timestamp,$options=array()) {
		if ($timestamp==null) return '';
		$format = "%e. %B";
		if ($options['shortWeekday']) {
			$format = "%a ".$format;
		}
		if (!isset($options['year']) || $options['year']) {
			$format.=' %Y';
		}
		setlocale(LC_TIME, "da_DK");
		return strftime($format,$timestamp);
	}
		
	function presentShortTime($timestamp) {
		if ($timestamp) {
			setlocale(LC_TIME, "da_DK");
			return strftime("%H:%M",$timestamp);
		}
	}
	
	function presentDateTime($timestamp) {
		if ($timestamp) {
			setlocale(LC_TIME, "da_DK");
			return strftime("%e. %b kl. %H:%M",$timestamp);
		}
	}
	
	function presentLongDateTime($timestamp) {
		if ($timestamp) {
			setlocale(LC_TIME, "da_DK");
			return strftime("%e. %b %Y kl. %H:%M",$timestamp);
		} else {
			return '';
		}
	}
}
?>