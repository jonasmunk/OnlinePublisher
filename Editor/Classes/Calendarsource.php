<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes
 */
require_once($basePath.'Editor/Classes/Database.php');
require_once($basePath.'Editor/Classes/Object.php');
require_once($basePath.'Editor/Classes/DateUtil.php');

class Calendarsource extends Object {
	var $url;
	var $synchronized;
	var $syncInterval;
	var $filter;
	var $displayTitle;
	
	function Calendarsource() {
		parent::Object('calendarsource');
	}
	
	function setUrl($url) {
		$this->url = $url;
	}
	
	function getUrl() {
		return $this->url;
	}
	
	function setSyncInterval($syncInterval) {
	    $this->syncInterval = $syncInterval;
	}

	function getSyncInterval() {
	    return $this->syncInterval;
	}
	
	function setFilter($filter) {
	    $this->filter = $filter;
	}

	function getFilter() {
	    return $this->filter;
	}
	
	function setDisplayTitle($displayTitle) {
	    $this->displayTitle = $displayTitle;
	}

	function getDisplayTitle() {
	    return $this->displayTitle;
	}
	
	function getIcon() {
		return 'Basic/Internet';
	}
	
	function getIn2iGuiIcon() {
		return 'common/internet';
	}
	
	function getSynchronized() {
		return $this->synchronized;
	}
	
	function synchronize($force=false) {
		global $basePath;
		if (time()-$this->synchronized<$this->syncInterval && $force==false) {
			// If not synced for an hour
			return;
		}
		if (strpos($this->url,'dbu.dk')!==false) {
			$this->synchronizeDBU();
		} else {
			$this->synchronizeVCal();
		}
		$sql = "update calendarsource set synchronized=".Database::datetime(time())." where object_id=".$this->id;
		Database::update($sql);
	}
	
	function getParsedFilter() {
		$parsed = array();
		if ($this->filter) {
			preg_match('/home=([a-zA-Z ]+)/i', $this->filter, $result);
			if ($result) {
				$parsed['home'] = $result[1];
			}
			preg_match('/away=([a-zA-Z ]+)/i', $this->filter, $result);
			if ($result) {
				$parsed['away'] = $result[1];
			}
		}
		return $parsed;
	}
	
	function synchronizeDBU() {
		global $basePath;
		require_once($basePath.'Editor/Classes/DBUCalendar.php');
		$parser = new DBUCalendarParser();
		$cal = $parser->parseURL($this->url);
		if ($cal) {
			$homeMode = strpos($this->getTitle(),'hjemmekampe')!==false;
			$guestMode = strpos($this->getTitle(),'udekampe')!==false;
			$filter = $this->getParsedFilter();
			$events = $cal->getEvents();
			$sql = "delete from calendarsource_event where calendarsource_id=".$this->id;
			Database::delete($sql);
			//return;
			foreach($events as $event) {
				if (($homeMode && strpos($event->getHomeTeam(),'Hals')===false) || ($guestMode && strpos($event->getGuestTeam(),'Hals')===false)) {
					continue;
				}
				if ($filter['home'] && strpos($event->getHomeTeam(),$filter['home'])===false) {
					continue;
				}
				if ($filter['away'] && strpos($event->getGuestTeam(),$filter['away'])===false) {
					continue;
				}
				$summary = $event->getHomeTeam()." - ".$event->getGuestTeam();
				if ($event->getScore()) {
					$summary.=' ('.$event->getScore().')';
				}
				$sql = "insert into calendarsource_event (".
				"calendarsource_id,summary,location,startdate,enddate".
				") values (".
				$this->id.",".
				Database::text($summary).",".
				Database::text($event->getLocation()).",".
				Database::datetime($event->getStartDate()).",".
				Database::datetime($event->getEndDate()).
				")";
				Database::insert($sql);
			}
		}
	}
	
	function synchronizeVCal() {
		global $basePath;
		require_once($basePath.'Editor/Classes/VCal.php');
		$parser = new VCalParser();
		$cal = $parser->parseURL($this->url);
		if ($cal) {
			$events = $cal->getEvents();
			$sql = "delete from calendarsource_event where calendarsource_id=".$this->id;
			Database::delete($sql);
			foreach($events as $event) {
			
				$recurring = false;
				$frequency = null;
				$until = null;
				$interval = null;
				$count = null;
				$bymonth = null;
				$bymonthday = null;
				$byday = null;
				$byyearday = null;
				$byweeknumber = null;
				$weekstart = null;
				if ($event->isRecurring()) {
					$rule = $event->getRecurrenceRules();
					$rule = $rule[0];
					//error_log(print_r($rule,true));
					$recurring = true;
					$frequency = $rule->getFrequency();
					$until = $rule->getUntil();
					$interval = $rule->getInterval();
					$count = $rule->getCount();
					$weekstart = $rule->getWeekStart();
					if ($rule->getByMonth()!=null) {
						$bymonth = implode($rule->getByMonth(),',');
					}
					if ($rule->getByMonthDay()!=null) {
						$bymonthday = implode($rule->getByMonthDay(),',');
					}
					if ($rule->getByDay()!=null) {
						$byday = implode($rule->getByDay(),',');
					}
					if ($rule->getByYearDay()!=null) {
						$byyearday = implode($rule->getByYearDay(),',');
					}
					if ($rule->getByWeekNumber()!=null) {
						$byweeknumber = implode($rule->getByWeekNumber(),',');
					}
				}
			
				$sql = "insert into calendarsource_event (".
				"calendarsource_id,summary,description,location,startdate,enddate,duration,uniqueid,recurring,frequency,until,`interval`,`count`,weekstart,bymonth,bymonthday,byday,byyearday,byweeknumber".
				") values (".
				$this->id.",".
				Database::text($event->getSummary()).",".
				Database::text($event->getDescription()).",".
				Database::text($event->getLocation()).",".
				Database::datetime($event->getStartDate()).",".
				Database::datetime($event->getEndDate()).",".
				Database::int($event->getDuration()).",".
				Database::text($event->getUniqueId()).",".
				Database::boolean($recurring).",".
				Database::text($frequency).",".
				Database::datetime($until).",".
				Database::int($interval).",".
				Database::int($count).",".
				Database::text($weekstart).",".
				Database::text($bymonth).",".
				Database::text($bymonthday).",".
				Database::text($byday).",".
				Database::text($byyearday).",".
				Database::text($byweeknumber).
				")";
				Database::insert($sql);
			}
		}
	}
	
	function getEvents($query=array()) {
		$events = array();
		$sql = "select id,summary,description,recurring,uniqueid,location,unix_timestamp(startdate) as startdate,unix_timestamp(enddate) as enddate,`duration`".
		" from calendarsource_event where calendarsource_id=".$this->id." and recurring=0";
		if ($query['startDate'] && $query['endDate']) {
			$sql.=" and not (startdate>".Database::datetime($query['endDate'])." or endDate<".Database::datetime($query['startDate']).")";
		}
		$sql.=" order by startdate desc";

		$result = Database::select($sql);

		while ($row = Database::next($result)) {
			$events[] = $this->_buildEvent($row);
		}
		Database::free($result);
		
		// Get recurring events
		$sql = "select id,summary,recurring,uniqueid,location,unix_timestamp(startdate) as startdate,unix_timestamp(enddate) as enddate,`duration`".
		",frequency,unix_timestamp(until) as until,`count`,`interval`,byday".
		" from calendarsource_event where calendarsource_id=".$this->id." and recurring=1 order by startdate desc";

		$result = Database::select($sql);
		while ($row = Database::next($result)) {
			$this->_analyzeReccursion($row,$events,$query);
		}
		Database::free($result);
		
		if ($query['sort'] == 'startDate') {
			usort($events,array('Calendarsource','_startDateComparator'));
		}
		
		return $events;
	}
	
	function _analyzeReccursion($row, &$events,$query) {
		// Skip if until<startdate
		if ($row['until']>0 && $row['until']<$query['startDate']) {
			return;
		}
		if ($row['frequency']=='DAILY' || $row['frequency']=='WEEKLY' || $row['frequency']=='MONTHLY' || $row['frequency']=='YEARLY') {
			// Build maximum 1000 events
			$running = true;
			for ($i=0;$i<1000 && $running;$i++) {
				if ($row['interval']==0 || (($i) % $row['interval'])==0) {
					$futureEvents = $this->_createFutureEvents($row,$row['frequency'],$i);
					foreach ($futureEvents as $futureEvent) {
						$event = $this->_buildEvent($futureEvent);
						if ($event['startDate']>$query['endDate']) {
							$running = false;
						} elseif ($row['count']>0 && $row['count']<=$i) {
							$running = false;
						} elseif ($row['until']>0 && $row['until']<$event['startDate']) {
							$running = false;
						} elseif ($event['startDate']>$query['startDate']) {
							$events[] = $event;
						}
					}
				}
			}
		}
	}
	
	function _createFutureEvents($row,$by,$count) {
		$events = array();
		if ($by=='WEEKLY') {
			if ($row['byday']) {
				$dayNums = array('MO'=>0, 'TU'=>1, 'WE'=>2, 'TH'=>3,'FR'=>4,'SA'=>5,'SU'=>6);
				$weekday = DateUtil::getWeekDay($row['startdate']);
				$byDays = @split(",",$row['byday']);
				foreach ($byDays as $day) {
					$new = $row;
					$extra = $dayNums[$day]-$weekday;
					//error_log('byday: '.$weekday.' > '.$day.'/'.$extra);
					$new['startdate'] = DateUtil::addDays($new['startdate'],$count*7+$extra);
					$new['enddate'] = DateUtil::addDays($new['enddate'],$count*7+$extra);
					$events[] = $new;
				}
			} else {
				$row['startdate'] = DateUtil::addDays($row['startdate'],$count*7);
				$row['enddate'] = DateUtil::addDays($row['enddate'],$count*7);
				$events[] = $row;
			}
		} elseif ($by=='DAILY') {
			$row['startdate'] = DateUtil::addDays($row['startdate'],$count);
			$row['enddate'] = DateUtil::addDays($row['enddate'],$count);
			$events[] = $row;
		} elseif ($by=='MONTHLY') {
			$row['startdate'] = DateUtil::addMonths($row['startdate'],$count);
			$row['enddate'] = DateUtil::addMonths($row['enddate'],$count);
			$events[] = $row;
		} elseif ($by=='YEARLY') {
			$row['startdate'] = DateUtil::addYears($row['startdate'],$count);
			$row['enddate'] = DateUtil::addYears($row['enddate'],$count);
			$events[] = $row;
		}
		return $events;
	}
	
	function sort(&$events) {
		usort($events,array('Calendarsource','_startDateComparator'));
	}
	
	function _startDateComparator($a, $b) {
		$a = $a['startDate'];
		$b = $b['startDate'];
		if (!$a) $a=0;
		if (!$b) $b=0;
    	if ($a == $b) {
        	return 0;
    	}
    	return ($a < $b) ? -1 : 1;
	}
	
	function _buildEvent($row) {
		$event = array(
			'id' => $row['id'],
			'summary' => $row['summary'],
			'description' => $row['description'],
			'location' => $row['location'],
			'uniqueId' => $row['uniqueid'],
			'recurring' => $row['recurring'],
			'startDate' => $row['startdate'],
			'endDate' => $row['enddate'],
			'calendarTitle' => $this->title,
			'calendarDisplayTitle' => $this->displayTitle
		);
		if ($row['duration']>0) {
			$event['endDate'] = DateUtil::addSeconds($row['startdate'],$row['duration']);
		}
		return $event;
	}
	
	
	
	function search($query=array()) {
		$sql = "select object.id from calendarsource,object where calendarsource.object_id=object.id order by object.title;";
		$ids = Database::getIds($sql);
		$sources = array();
		foreach ($ids as $id) {
			$sources[] = Calendarsource::load($id);
		}
		return $sources;
	}
	
	
	///////////////////////////////// Interface ////////////////////////////////
	
	
	function load($id) {
		if (!$id) return;
		$obj = new Calendarsource();
		$obj->_load($id);
		$sql = "select url,filter,display_title,sync_interval,unix_timestamp(synchronized) as synchronized from calendarsource where object_id=".$id;
		$row = Database::selectFirst($sql);
		if ($row) {
			$obj->url=$row['url'];
			$obj->synchronized=$row['synchronized'];
			$obj->filter=$row['filter'];
			$obj->displayTitle=$row['display_title'];
			$obj->syncInterval=$row['sync_interval'];
			return $obj;
		} else {
			return null;
		}
	}
	
	function sub_create() {
		$sql="insert into calendarsource (object_id,url,display_title,filter,sync_interval) values (".
		$this->id.
		",".Database::text($this->url).
		",".Database::text($this->displayTitle).
		",".Database::text($this->filter).
		",".Database::int($this->syncInterval).
		")";
		Database::insert($sql);
	}
	
	function sub_update() {
		$sql = "update calendarsource set ".
		"url=".Database::text($this->url).
		",filter=".Database::text($this->filter).
		",display_title=".Database::text($this->displayTitle).
		",sync_interval=".Database::int($this->syncInterval).
		" where object_id=".$this->id;
		Database::update($sql);
	}
	
	function sub_publish() {
		$data = '<calendarsource xmlns="'.parent::_buildnamespace('1.0').'">'.
		'<url>'.StringUtils::escapeXML($this->url).'</url>'.
		'</calendarsource>';
		return $data;
	}
	
	function sub_remove() {
		$sql = "delete from calendarsource where object_id=".$this->id;
		Database::delete($sql);
		$sql = "delete from calendarsource_event where calendarsource_id=".$this->id;
		Database::delete($sql);
	}
}
?>