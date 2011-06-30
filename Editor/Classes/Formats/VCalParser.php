<?
/**
 * @package OnlinePublisher
 * @subpackage Classes.Formats
 */
require_once($basePath.'Editor/Classes/Utilities/StringUtils.php');
require_once($basePath.'Editor/Classes/Formats/VEvent.php');
require_once($basePath.'Editor/Classes/Formats/VRecurrenceRule.php');
require_once($basePath.'Editor/Classes/Formats/VCalendar.php');

class VCalParser {
	
	var $log = array();
	var $state = 'base';
	var $latestEvent;
	
	function VCalParser() {
	}
	
	function getLog() {
		return $this->log();
	}
	
	function parseURL($url) {
		$handle = @fopen($url, "r");
		if ($handle) {
			$cal = new VCalendar();
			$num = 1;
			$latestLine = '';
		    while (!feof($handle)) {
		        $line = fgets($handle, 4096);
				if ($line[0]==' ') {
					$latestLine.=trim($line);
				} else {
					$this->parseLine($latestLine,$cal);
					//echo 'PARSE!: '.$latestLine.'<br>';
					$latestLine = trim($line);
				}
		        //echo $num.': '.$line.'<br>';
				$num++;
		    }
		    fclose($handle);
			return $cal;
		} else {
			return false;
		}
	}
	
	function parseLine($line,&$cal) {
		$parts = $this->splitLine($line);
		if ($parts[0]=='BEGIN' && $parts[1]=='VEVENT') {
			$this->latestEvent = new VEvent();
		}
		elseif ($parts[0]=='END' && $parts[1]=='VEVENT') {
			$cal->addEvent($this->latestEvent);
			$this->latestEvent = null;
		}
		elseif ($parts[0]=='SUMMARY' && $this->latestEvent) {
			$this->latestEvent->setSummary($this->parseSummary($line));
		}
		elseif ($parts[0]=='DESCRIPTION' && $this->latestEvent) {
			$this->latestEvent->setDescription($this->parseDescription($line));
		}
		elseif ($parts[0]=='DTSTART' && $this->latestEvent) {
			$date = $this->parseDateLine($parts);
			$this->latestEvent->setStartDate($date);
		}
		elseif ($parts[0]=='DTEND' && $this->latestEvent) {
			$date = $this->parseDateLine($parts);
			$this->latestEvent->setEndDate($date);
		}
		elseif ($parts[0]=='DURATION' && $this->latestEvent) {
			$dur = $this->parseDuration($parts[1]);
			$this->latestEvent->setDuration($dur);
		}
		elseif ($parts[0]=='DTSTAMP' && $this->latestEvent) {
			$date = $this->parseDateLine($parts);
			$this->latestEvent->setTimeStamp($date);
		}
		elseif ($parts[0]=='UID' && $this->latestEvent) {
			$this->latestEvent->setUniqueId($parts[1]);
		}
		elseif ($parts[0]=='LOCATION' && $this->latestEvent) {
			$this->latestEvent->setLocation($this->decodeString($parts[1]));
		}
		elseif ($parts[0]=='URL' && $this->latestEvent) {
			$this->latestEvent->setUrl($this->parseURLLine($line));
		}
		elseif ($parts[0]=='RRULE' && $this->latestEvent) {
			$rule = $this->parseRecurrenceRule($parts);
			$this->latestEvent->addRecurrenceRule($rule);
		} elseif ($parts[0]=='VERSION') {
			$cal->setVersion($parts[1]);
		} elseif ($parts[0]=='X-WR-CALNAME') {
			$cal->setTitle($parts[1]);
		} elseif ($parts[0]=='X-WR-TIMEZONE') {
			$cal->setTimeZone($parts[1]);
		}
		
	}
	
	function decodeString($string) {
		$string = utf8_decode($string);
		$search = array('\,','\"',"\\n");
		$replace = array(',','"',"\n");
		return str_replace($search,$replace,$string);
	}
	
	function splitLine($line) {
		$line = trim($line);
		return preg_split('/[;:]+/',$line);
	}
	
	function parseSummary($line) {
		return $this->decodeString(substr($line,8));
	}
	
	function parseDescription($line) {
		return $this->decodeString(substr($line,12));
	}
	
	function parseURLLine($line) {
		return $this->decodeString(substr($line,14));
	}
	
	function parseRecurrenceRule($parts) {
		$rule = new VRecurrenceRule();
		foreach ($parts as $part) {
			$elements = explode('=',$part);
			if ($elements[0]=='FREQ') {
				$rule->setFrequency($elements[1]);
			} elseif ($elements[0]=='INTERVAL') {
				$rule->setInterval($elements[1]);
			} elseif ($elements[0]=='COUNT') {
				$rule->setCount($elements[1]);
			} elseif ($elements[0]=='WKST') {
				$rule->setWeekStart($elements[1]);
			} elseif ($elements[0]=='UNTIL') {
				$rule->setUntil($this->parseDate($elements[1]));
			} elseif ($elements[0]=='BYMONTH') {
				$rule->setByMonth(explode(',',$elements[1]));
			} elseif ($elements[0]=='BYMONTHDAY') {
				$rule->setByMonthDay(explode(',',$elements[1]));
			} elseif ($elements[0]=='BYDAY') {
				$rule->setByDay(explode(',',$elements[1]));
			} elseif ($elements[0]=='BYYEARDAY') {
				$rule->setByYearDay(explode(',',$elements[1]));
			} elseif ($elements[0]=='BYWEEKNO') {
				$rule->setByWeekNumber(explode(',',$elements[1]));
			}
		}
		return $rule;
	}
	
	function parseDuration($dur) {
		if (preg_match("/PT([0-9]*)S/mi",$dur, $matches)) {
			return $matches[1];
		} elseif (preg_match("/PT([0-9]*)H([0-9]*)M/mi",$dur, $matches)) {
			return $matches[1]*60*60+$matches[2]*60;
		} elseif (preg_match("/PT([0-9]*)H/mi",$dur, $matches)) {
			return $matches[1]*60*60;
		} else {
			error_log("Could not parse duration: ".$dur);
			return 0;
		}
	}
	
	function parseDateLine($parts) {
		if (count($parts)==3) {
			return $this->parseDate($parts[2]);
		} elseif (count($parts)==2) {
			return $this->parseDate($parts[1]);
		} else {
			return null;
		}
	}
	
	function parseDate($date) {
		if (preg_match("/([0-9]{4})([0-9]{2})([0-9]{2})T([0-9]{2})([0-9]{2})([0-9]{2})Z/mi",$date, $matches)) {
			return gmmktime ( $matches[4],$matches[5], $matches[6], $matches[2],$matches[3], $matches[1]);
		} else if (preg_match("/([0-9]{4})([0-9]{2})([0-9]{2})T([0-9]{2})([0-9]{2})([0-9]{2})/mi",$date, $matches)) {
			return mktime ( $matches[4],$matches[5], $matches[6], $matches[2],$matches[3], $matches[1]);
		} elseif (preg_match("/([0-9]{4})([0-9]{2})([0-9]{2})/mi",$date, $matches)) {
			return mktime ( 0,0, 0, $matches[2],$matches[3], $matches[1]);
		} else {
			error_log('Could not parse date: '.$date);
			return 0;
		}
	}
}
?>