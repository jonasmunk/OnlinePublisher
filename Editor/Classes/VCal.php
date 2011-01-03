<?
/**
 * @package OnlinePublisher
 * @subpackage Classes
 */
require_once($basePath.'Editor/Classes/Utilities/StringUtils.php');

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
			return mktime ( $matches[4]+1,$matches[5], $matches[6], $matches[2],$matches[3], $matches[1]);
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

class VCalSerializer {
	
	function VCalSerializer() {
		
	}
	
	function serialize($feed) {
		$xml = '<?xml version="1.0" encoding="utf-8"?><rss version="2.0"><channel>'.
		$this->buildTextTag('title',$feed->getTitle()).
		$this->buildTextTag('link',$feed->getLink()).
		$this->buildTextTag('language',$feed->getLanguage()).
		$this->buildTextTag('description',$feed->getDescription()).
		$this->buildTextTag('copyright',$feed->getCopyright()).
		$this->buildDateTag('pubDate',$feed->getPubDate()).
		$this->buildDateTag('lastBuildDate',$feed->getLastBuildDate()).
		$this->buildTextTag('ttl',$feed->getTtl()).
		$this->buildTextTag('image',$feed->getImage()).
		$this->buildTextTag('rating',$feed->getRating()).
		$this->buildTextTag('docs',$feed->getDocs()).
		$this->buildTextTag('generator',$feed->getGenerator()).
		$this->buildTextTag('webMaster',$feed->getWebMaster()).
		$this->buildTextTag('managingEditor',$feed->getManagingEditor());
		$items =& $feed->getItems();
		foreach ($items as $item) {
			$xml.='<item>'.
			$this->buildTextTag('title',$item->getTitle()).
			$this->buildTextTag('description',$item->getDescription()).
			$this->buildTextTag('link',$item->getLink()).
			$this->buildTextTag('guid',$item->getGuid()).
			$this->buildDateTag('pubDate',$item->getPubDate());
			$encs = $item->getEnclosures();
			foreach ($encs as $enc) {
				$xml.='<enclosure url="'.$enc['url'].'" type="'.$enc['type'].'" length="'.$enc['length'].'"/>';
			}
			$xml.='</item>';
		}
		$xml .= '</channel></rss>';
		return $xml;
	}
	
	function serializeDate($date) {
		return gmdate("D, d M Y H:i:s T",$date);
	}
	
	function buildTextTag($tagName,$value) {
		if ($value) {
			return '<'.$tagName.'>'.StringUtils::escapeXML($value).'</'.$tagName.'>';
		}
	}
	
	function buildDateTag($tagName,$value) {
		if (strlen($value)>0) {
			return '<'.$tagName.'>'.$this->serializeDate($value).'</'.$tagName.'>';
		}
	}
}

class VCalendar {
	
	var $events = array();
	var $version;
	var $title;
	var $timeZone;
	
	function VCalendar() {
	}
	
	function addEvent($event) {
		$this->events[] = $event;
	}
	
	function getEvents($comparator=null) {
		if ($comparator == 'startDate') {
			usort($this->events,array('VCalendar','startDateComparator'));
		}
		return $this->events;
	}
	
	function setVersion($version) {
	    $this->version = $version;
	}

	function getVersion() {
	    return $this->version;
	}
	
	function setTitle($title) {
	    $this->title = $title;
	}

	function getTitle() {
	    return $this->title;
	}
	
	function setTimeZone($timeZone) {
	    $this->timeZone = $timeZone;
	}

	function getTimeZone() {
	    return $this->timeZone;
	}
	
	

	function startDateComparator($a, $b) {
		$a = $a->getStartDate();
		$b = $b->getStartDate();
		if (!$a) $a=0;
		if (!$b) $b=0;
    	if ($a == $b) {
        	return 0;
    	}
    	return ($a < $b) ? -1 : 1;
	}
	
}


class VEvent {
	
	var $summary = '';
	var $description = '';
	var $location = '';
	var $startDate;
	var $endDate;
	var $duration;
	var $timeStamp;
	var $uniqueId;
	var $recurrenceRules = array();
	
	
	function VEvent() {
	}
	
	function addRecurrenceRule($rule) {
		$this->recurrenceRules[] = $rule;
	}
	
	function getRecurrenceRules() {
		return $this->recurrenceRules;
	}
	
	function isRecurring() {
		return count($this->recurrenceRules)>0;
	}
	
	function setSummary($summary) {
	    $this->summary = $summary;
	}

	function getSummary() {
	    return $this->summary;
	}
	
	function setDescription($description) {
	    $this->description = $description;
	}

	function getDescription() {
	    return $this->description;
	}
	
	function setLocation($location) {
	    $this->location = $location;
	}

	function getLocation() {
	    return $this->location;
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
	
	function setTimeStamp($timeStamp) {
	    $this->timeStamp = $timeStamp;
	}

	function getTimeStamp() {
	    return $this->timeStamp;
	}
	
	function setUniqueId($uniqueId) {
	    $this->uniqueId = $uniqueId;
	}

	function getUniqueId() {
	    return $this->uniqueId;
	}
	
	function setDuration($duration) {
	    $this->duration = $duration;
	}

	function getDuration() {
	    return $this->duration;
	}
	
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