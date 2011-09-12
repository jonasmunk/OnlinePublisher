<?
/**
 * @package OnlinePublisher
 * @subpackage Templates.Document
 */
if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}
require_once($basePath.'Editor/Classes/LegacyTemplateController.php');
require_once($basePath.'Editor/Classes/Utilities/DateUtils.php');
require_once($basePath.'Editor/Classes/EventUtil.php');
require_once($basePath.'Editor/Classes/Objects/Calendarsource.php');
require_once($basePath.'Editor/Classes/Objects/Event.php');
require_once($basePath.'Editor/Classes/Database.php');
require_once($basePath.'Editor/Classes/Request.php');
require_once($basePath.'Editor/Classes/Utilities/StringUtils.php');

class CalendarController extends LegacyTemplateController {
    
    function CalendarController($id) {
        parent::LegacyTemplateController($id);
    }

	function create($page) {
		$sql="insert into calendarviewer (page_id,title) values (".$page->getId().",".Database::text($page->getTitle()).")";
		Database::insert($sql);
	}
	
	function delete() {
		$sql="delete from calendarviewer where page_id=".$this->id;
		Database::delete($sql);
		$sql="delete from calendarviewer_object where page_id=".$this->id;
		Database::delete($sql);
	}
    
    function build() {
		$sql="select * from calendarviewer where page_id=".$this->id;
		$row = Database::selectFirst($sql);
		$data = '<calendar xmlns="http://uri.in2isoft.com/onlinepublisher/publishing/calendar/1.0/">';
		$data.= '<!--dynamic-->';
		$data.= '</calendar>';
        return array('data' => $data, 'dynamic' => true, 'index' => '');
    }

    function import(&$node) {
    }
    
	function dynamic(&$state) {		
		$refresh = Request::getBoolean('refresh');
		$date = Request::getDate('date');
		if (!$date) {
			$date=DateUtils::stripTime(time());
		}
		$id = $this->id;
		$sql="select * from calendarviewer where page_id = ".$id;
		$setup = Database::selectFirst($sql);

		$view = Request::getString('view');
		if (!$view) $view=$setup['standard_view'];


		if ($view=='week') {
			$info=$this->buildWeekView($date,$id,$refresh,$setup);
		} else if ($view=='list') {
			$info=$this->buildListView($date,$id,$refresh);
		} else if ($view=='month') {
			$info=$this->buildMonthView($date,$id,$refresh);
		} else if ($view=='agenda') {
			$info=$this->buildAgendaView($date,$id,$refresh);
		}


		$xml='<state view="'.$view.'">';
		$xml.=DateUtils::buildTag('date',$date);
		$xml.=DateUtils::buildTag('today',time());
		$xml.=DateUtils::buildTag('next',$info['next']);
		$xml.=DateUtils::buildTag('previous',$info['previous']);
		$xml.='</state>';
		$xml.=$info['xml'];
		$state['data'] = str_replace("<!--dynamic-->", $xml, $state['data']);
	}



	function getEvents($id,$query,$refresh) {
		$events = array();
		$sql="select calendarsource.object_id as id from calendarviewer_object,calendarsource where calendarsource.object_id = calendarviewer_object.object_id and calendarviewer_object.page_id = ".$id;
		$ids = Database::getIds($sql);
		foreach ($ids as $sourceId) {
			$source = Calendarsource::load($sourceId);
			$source->synchronize($refresh);
			$events = array_merge($events,$source->getEvents($query));
		}
		$sql="select calendar.object_id as id,object.title from calendarviewer_object,calendar,object where object.id = calendar.object_id and calendar.object_id = calendarviewer_object.object_id and calendarviewer_object.page_id = ".$id;
		
        $result = Database::select($sql);
        while ($row = Database::next($result)) {
			$eventQuery = array('calendarId'=>$row['id'],'startDate' => $query['startDate'],'endDate' => $query['endDate'],'calendarTitle' => $row['title']);
			$events = array_merge($events,Event::getSimpleEvents($eventQuery));
		}
        Database::free($result);
		usort($events,array('Calendarsource','_startDateComparator'));
		return $events;
	}


	function buildWeekView($date,$id,$refresh,$setup) {
		$startHour = $setup['weekview_starthour'];
		$firstWeekDay = DateUtils::getWeekStart($date);
		$lastWeekDay = DateUtils::addDays($firstWeekDay,7);
		$query = array('sort' => 'startDate', 'startDate' => $firstWeekDay, 'endDate' => $lastWeekDay);
		$events = $this->getEvents($id,$query,$refresh);
		$xml='<weekview>';
		$derived = array();
		for ($i=0;$i<7;$i++) {
			$timestamp = DateUtils::addDays($firstWeekDay,$i);
			$timestampEnd = DateUtils::addDays($firstWeekDay,$i+1)-1;
			$xml.='<day date="'.date("Ymd",$timestamp).'" today="'.(date("Ymd",$timestamp)==date("Ymd",time()) ? 'true' : 'false').'" selected="'.(date("Ymd",$timestamp)==date("Ymd",$date) ? 'true' : 'false').'" title="'.DateUtils::formatShortDate($timestamp).'">';
			$xml.=DateUtils::buildTag('date',$timestamp);
			$dayEvents = array();
			foreach ($events as $event) {
				$event = EventUtil::getEventInsidePeriod($timestamp,$timestampEnd,$event);
				if ($event!=null) {
					$secs = DateUtils::getSecondsSinceMidnight($event['startDate']);
					$top = ($secs-$startHour*60*60)/(24-$startHour)/60/60;
					$height = ($event['endDate']-$event['startDate'])/(24-$startHour)/60/60;
					if ($top<0) {
						$height+=$top;
						$top=0;
					}
					if ($height>0) {
						$event['top'] = $top;
						$event['height'] = $height;
						$dayEvents[] = $event;
					}
				}
			}
			$this->analyzeOverlaps($dayEvents,$i);
			foreach ($dayEvents as $dayEvent) {
				$xml.=$this->buildEventXML($dayEvent);
			}
			$xml.='</day>';
		}
		for ($i=$startHour;$i<25;$i++) {
			$xml.='<hour value="'.$i.'"/>';
		}
		$xml.='</weekview>';
		return array('xml'=>$xml,'first'=>$firstWeekDay,'last' => $lastWeekDay,'next' => DateUtils::addDays($date,7),'previous' => DateUtils::addDays($date,-7));
	}


	function buildMonthView($date,$id,$refresh) {
		$startDay = DateUtils::getMonthStart($date);
		$startDay = DateUtils::getWeekStart($startDay);
		$endDay = DateUtils::getMonthEnd($date);
		$days = date("d",$endDay);
		$days=35;
		$query = array('sort' => 'startDate', 'startDate' => $startDay, 'endDate' => $endDay);
		$events = $this->getEvents($id,$query,$refresh);
		$xml='<monthview>';
		$derived = array();
		for ($i=0;$i<$days;$i++) {
			$timestamp = DateUtils::addDays($startDay,$i);
			$timestampEnd = DateUtils::addDays($startDay,$i+1)-1;
			$weekday = DateUtils::getWeekDay($timestamp);
			if ($weekday==0) {
				$xml.='<week>';
			}
			$title = DateUtils::formatDate($timestamp,array('shortWeekday'=>true,'year'=>false));
			$title = mb_convert_encoding($title, "ISO-8859-1","UTF-8");
			$xml.='<day date="'.date("Ymd",$timestamp).'" today="'.(date("Ymd",$timestamp)==date("Ymd",time()) ? 'true' : 'false').'" selected="'.(date("Ymd",$timestamp)==date("Ymd",$date) ? 'true' : 'false').'" title="'.$title.'">';
			$xml.=DateUtils::buildTag('date',$timestamp);
			$dayEvents = array();
			foreach ($events as $event) {
				$event = EventUtil::getEventInsidePeriod($timestamp,$timestampEnd,$event);
				if ($event!=null) {
					$dayEvents[] = $event;
				}
			}
			$this->analyzeOverlaps($dayEvents,$i);
			foreach ($dayEvents as $dayEvent) {
				$xml.=$this->buildEventXML($dayEvent);
			}
			$xml.='</day>';
			if ($weekday==6) {
				$xml.='</week>';
			}
		}
		$xml.='</monthview>';
		return array('xml'=>$xml,'first'=>$startDay,'last' => $endDay,'next' => DateUtils::addMonths($date,1),'previous' => DateUtils::addMonths($date,-1));
	}

	function buildEventXML(&$event) {
		return '<event unique-id="'.StringUtils::escapeXML($event['uniqueId']).'" collision-count="'.(isset($event['collisionCount']) ? $event['collisionCount'] : 0).'" collision-number="'.(isset($event['collisionNumber']) ? $event['collisionNumber'] : 0).'" height="'.(isset($event['height']) ? $event['height'] : '').'" top="'.(isset($event['top']) ? $event['top'] : '').'" time-from="'.DateUtils::formatShortTime($event['startDate']).'" time-to="'.DateUtils::formatShortTime($event['endDate']).'">'.
		DateUtils::buildTag('start',$event['startDate']).
		DateUtils::buildTag('end',$event['endDate']).
		'<summary>'.StringUtils::escapeXML($event['summary']).'</summary>'.
		'<description>'.StringUtils::escapeXML($event['description']).'</description>'.
		'<location>'.StringUtils::escapeXML($event['location']).'</location>'.
		'<calendar>'.StringUtils::escapeXML($event['calendarTitle']).'</calendar>'.
		'</event>';
	}

	function buildListView($date,$id,$refresh) {
		$startDay = DateUtils::getMonthStart($date);
		$endDay = DateUtils::getMonthEnd($date);
		$days = date("d",$endDay);
		$query = array('sort' => 'startDate', 'startDate' => $startDay, 'endDate' => $endDay);
		$events = $this->getEvents($id,$query,$refresh);
		$xml='<listview>';
		$derived = array();
		for ($i=0;$i<$days;$i++) {
			$timestamp = DateUtils::addDays($startDay,$i);
			$timestampEnd = DateUtils::addDays($startDay,$i+1)-1;
			$title = DateUtils::formatDate($timestamp,array('shortWeekday'=>true,'year'=>false));
			$title = mb_convert_encoding($title, "ISO-8859-1","UTF-8");
			$xml.='<day date="'.date("Ymd",$timestamp).'" today="'.(date("Ymd",$timestamp)==date("Ymd",time()) ? 'true' : 'false').'" selected="'.(date("Ymd",$timestamp)==date("Ymd",$date) ? 'true' : 'false').'" title="'.$title.'">';
			$xml.=DateUtils::buildTag('date',$timestamp);
			$dayEvents = array();
			foreach ($events as $event) {
				$event = EventUtil::getEventInsidePeriod($timestamp,$timestampEnd,$event);
				if ($event!=null) {
					$dayEvents[] = $event;
				}
			}
			$this->analyzeOverlaps($dayEvents,$i);
			foreach ($dayEvents as $dayEvent) {
				$xml.=$this->buildEventXML($dayEvent);
			}
			$xml.='</day>';
		}
		$xml.='</listview>';
		return array('xml'=>$xml,'first'=>$startDay,'last' => $endDay,'next' => DateUtils::addMonths($date,1),'previous' => DateUtils::addMonths($date,-1));
	}

	function buildAgendaView($date,$id,$refresh) {
		$startDay = DateUtils::getMonthStart($date);
		$endDay = DateUtils::getMonthEnd($date);
		$days = date("d",$endDay);
		$query = array('sort' => 'startDate', 'startDate' => $startDay, 'endDate' => $endDay);
		$events = $this->getEvents($id,$query,$refresh);
		$xml='<agendaview>';
		foreach ($events as $event) {
			$xml.=$this->buildEventXML($event);
		}
		$xml.='</agendaview>';
		return array('xml'=>$xml,'first'=>$startDay,'last' => $endDay,'next' => DateUtils::addMonths($date,1),'previous' => DateUtils::addMonths($date,-1));
	}

	function analyzeOverlaps(&$events,$day) {
		$collisions = array();
		$others = $events;
		$num=0;
		foreach ($events as $event) {
			$event['collisionNumber'] = 0;
			$event['collisionCount'] = 0;
			foreach ($others as $other) {
				if ($event['id']!=$other['id']) {
					if (EventUtil::isEventsColliding($event,$other)) {
						$collisions = $this->addToCollisionGroups($collisions,$event['id'],$other['id']);
					}
				}
			}
		}
		for ($i=0;$i<count($events);$i++) {
			foreach ($collisions as $collision) {
				$result = array_search($events[$i]['id'], $collision);
				if ($result!==false) {
					$events[$i]['collisionNumber'] = $result+1;
					$events[$i]['collisionCount'] = count($collision);
				}
			}
		}
	}

	function addToCollisionGroups(&$groups,$first,$second) {
		$found = false;
		for ($i=0;$i<count($groups);$i++) {
			if (in_array($first, $groups[$i]) || in_array($second, $groups[$i])) {
				if (!in_array($first, $groups[$i])) $groups[$i][] = $first;
				if (!in_array($second, $groups[$i])) $groups[$i][] = $second;
				$found = true;
			}
		}
		if (count($groups)==0 || !$found) {
			$groups[] = array($first,$second);
		}
		return $groups;
	}
}
?>