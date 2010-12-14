<?
/**
 * @package OnlinePublisher
 * @subpackage Classes.Utilities
 */

class DateUtils {
	
	function parseRFC822($str) {
		preg_match("/(.+)\, (\d+) (\w+) (\d+) (\d+):(\d+):(\d+) (.+)/i",$str, $matches);
		
		$months = array("Jan" => 1,"Feb" => 2,"Mar" => 3,"Apr" => 4,"May" => 5,"Jun" => 6,"Jul" => 7,"Aug" => 8,"Sep" => 9,"Oct" => 10,"Nov" => 11,"Dec" => 12);
		
		return gmmktime ( $matches[5],$matches[6],$matches[7], $months[$matches[3]],$matches[2], $matches[4]);
	}
	
	function parseRFC3339($date) {
		if (preg_match("/([0-9]{4})-([0-9]{2})-([0-9]{2})T([0-9]{2}):([0-9]{2}):([0-9]{2})-([0-9]{2}):([0-9]{2})/mi",$date, $matches)) {
			$diff = intval($matches[7]);
			Log::debug('The difference is: '.$diff);
			return gmmktime( $matches[4]+$diff,$matches[5], $matches[6], $matches[2],$matches[3], $matches[1]);
		}
		Log::debug('Could not parse date: '.$date);
		return null;
	}
	
	function formatLongDateTime($timestamp,$locale="da_DK") {
		if ($timestamp) {
			setlocale(LC_TIME, $locale);
			return strftime("%e. %b %Y kl. %H:%M",$timestamp);
		} else {
			return '';
		}
	}
	
	function formatDateTime($timestamp,$locale="da_DK") {
		if ($timestamp) {
			setlocale(LC_TIME, $locale);
			return strftime("%e. %b kl. %H:%M",$timestamp);
		} else {
			return '';
		}
	}
		
	function formatShortTime($timestamp,$locale="da_DK") {
		if ($timestamp) {
			setlocale(LC_TIME, $locale);
			return strftime("%H:%M",$timestamp);
		}
	}
	
	function formatLongDateTimeGM($timestamp,$locale="da_DK") {
		if ($timestamp) {
			setlocale(LC_TIME, $locale);
			return gmstrftime("%e. %b %Y kl. %H:%M",$timestamp);
		} else {
			return '';
		}
	}
	
	function formatFuzzy($timestamp,$locale="da_DK") {
		if ($timestamp) {
			$diff = time()-$timestamp;
			if ($diff>0) {
				if ($diff<60) {
					return 'for '.$diff.' sekunder siden ('.DateUtils::formatDateTime($timestamp,$locale).')';
				} else if ($diff<3600) {
					$minutes = floor($diff/60);
					return 'for '.$minutes.($minutes==1 ? ' minut siden' : ' minutter siden');
				} else if ($diff<3600*24) {
					$minutes = floor($diff/60/60);
					return 'for '.$minutes.($minutes==1 ? ' time siden' : ' timer siden');
				} else if ($diff<3600*24*4) {
					return 'for '.floor($diff/3600/24).' dage siden kl. '.DateUtils::formatShortTime($timestamp,$locale);
				}
			}
			if (strftime('%Y',time())!==strftime('%Y',$timestamp)) {
				return DateUtils::formatLongDateTime($timestamp,$locale);
			} else {
				return DateUtils::formatDateTime($timestamp,$locale);
			}
		} else {
			return '';
		}
	}
}