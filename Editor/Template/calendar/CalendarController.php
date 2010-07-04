<?
/**
 * @package OnlinePublisher
 * @subpackage Templates.Document
 */
require_once($basePath.'Editor/Classes/TemplateController.php');
require_once($basePath.'Editor/Classes/DateUtil.php');
require_once($basePath.'Editor/Classes/EventUtil.php');
require_once($basePath.'Editor/Classes/UserInterface.php');
require_once($basePath.'Editor/Classes/Calendarsource.php');
require_once($basePath.'Editor/Classes/Event.php');
require_once($basePath.'Editor/Classes/Database.php');
require_once($basePath.'Editor/Classes/XmlUtils.php');

class CalendarController extends TemplateController {
    
    function CalendarController($id) {
        parent::TemplateController($id);
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
		$refresh = requestGetBoolean('refresh');
		$date = requestGetDate('date');
		if (!$date) {
			$date=DateUtil::stripTime(time());
		}
		$id = $this->id;
		$sql="select * from calendarviewer where page_id = ".$id;
		$setup = Database::selectFirst($sql);

		$view = requestGetText('view');
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
		$xml.=XmlUtils::buildDate('date',$date);
		$xml.=XmlUtils::buildDate('today',time());
		$xml.=XmlUtils::buildDate('next',$info['next']);
		$xml.=XmlUtils::buildDate('previous',$info['previous']);
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
		$firstWeekDay = DateUtil::getWeekStart($date);
		$lastWeekDay = DateUtil::addDays($firstWeekDay,7);
		$query = array('sort' => 'startDate', 'startDate' => $firstWeekDay, 'endDate' => $lastWeekDay);
		$events = $this->getEvents($id,$query,$refresh);
		$xml='<weekview>';
		$derived = array();
		for ($i=0;$i<7;$i++) {
			$timestamp = DateUtil::addDays($firstWeekDay,$i);
			$timestampEnd = DateUtil::addDays($firstWeekDay,$i+1)-1;
			$xml.='<day date="'.date("Ymd",$timestamp).'" today="'.(date("Ymd",$timestamp)==date("Ymd",time()) ? 'true' : 'false').'" selected="'.(date("Ymd",$timestamp)==date("Ymd",$date) ? 'true' : 'false').'" title="'.UserInterface::presentShortDate($timestamp).'">';
			$xml.=XmlUtils::buildDate('date',$timestamp);
			$dayEvents = array();
			foreach ($events as $event) {
				$event = EventUtil::getEventInsidePeriod($timestamp,$timestampEnd,$event);
				if ($event!=null) {
					$secs = DateUtil::getSecondsSinceMidnight($event['startDate']);
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
		return array('xml'=>$xml,'first'=>$firstWeekDay,'last' => $lastWeekDay,'next' => DateUtil::addDays($date,7),'previous' => DateUtil::addDays($date,-7));
	}


	function buildMonthView($date,$id,$refresh) {
		$startDay = DateUtil::getMonthStart($date);
		$startDay = DateUtil::getWeekStart($startDay);
		$endDay = DateUtil::getMonthEnd($date);
		//$endDay = DateUtil::getWeekEnd($endDay);
		$days = date("d",$endDay);
		$days=35;
		$query = array('sort' => 'startDate', 'startDate' => $startDay, 'endDate' => $endDay);
		$events = $this->getEvents($id,$query,$refresh);
		$xml='<monthview>';
		$derived = array();
		for ($i=0;$i<$days;$i++) {
			$timestamp = DateUtil::addDays($startDay,$i);
			$timestampEnd = DateUtil::addDays($startDay,$i+1)-1;
			$weekday = DateUtil::getWeekDay($timestamp);
			if ($weekday==0) {
				$xml.='<week>';
			}
			$title = UserInterface::presentDate($timestamp,array('shortWeekday'=>true,'year'=>false));
			$title = mb_convert_encoding($title, "ISO-8859-1","UTF-8");
			$xml.='<day date="'.date("Ymd",$timestamp).'" today="'.(date("Ymd",$timestamp)==date("Ymd",time()) ? 'true' : 'false').'" selected="'.(date("Ymd",$timestamp)==date("Ymd",$date) ? 'true' : 'false').'" title="'.$title.'">';
			$xml.=XmlUtils::buildDate('date',$timestamp);
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
		return array('xml'=>$xml,'first'=>$startDay,'last' => $endDay,'next' => DateUtil::addMonths($date,1),'previous' => DateUtil::addMonths($date,-1));
	}

	function buildEventXML(&$event) {
		return '<event unique-id="'.encodeXML($event['uniqueId']).'" collision-count="'.(isset($event['collisionCount']) ? $event['collisionCount'] : 0).'" collision-number="'.(isset($event['collisionNumber']) ? $event['collisionNumber'] : 0).'" height="'.(isset($event['height']) ? $event['height'] : '').'" top="'.(isset($event['top']) ? $event['top'] : '').'" time-from="'.UserInterface::presentShortTime($event['startDate']).'" time-to="'.UserInterface::presentShortTime($event['endDate']).'">'.
		XmlUtils::buildDate('start',$event['startDate']).
		XmlUtils::buildDate('end',$event['endDate']).
		'<summary>'.encodeXML($event['summary']).'</summary>'.
		'<description>'.encodeXML($event['description']).'</description>'.
		'<location>'.encodeXML($event['location']).'</location>'.
		'<calendar>'.encodeXML($event['calendarTitle']).'</calendar>'.
		'</event>';
	}

	function buildListView($date,$id,$refresh) {
		$startDay = DateUtil::getMonthStart($date);
		$endDay = DateUtil::getMonthEnd($date);
		$days = date("d",$endDay);
		$query = array('sort' => 'startDate', 'startDate' => $startDay, 'endDate' => $endDay);
		$events = $this->getEvents($id,$query,$refresh);
		$xml='<listview>';
		$derived = array();
		for ($i=0;$i<$days;$i++) {
			$timestamp = DateUtil::addDays($startDay,$i);
			$timestampEnd = DateUtil::addDays($startDay,$i+1)-1;
			$title = UserInterface::presentDate($timestamp,array('shortWeekday'=>true,'year'=>false));
			$title = mb_convert_encoding($title, "ISO-8859-1","UTF-8");
			$xml.='<day date="'.date("Ymd",$timestamp).'" today="'.(date("Ymd",$timestamp)==date("Ymd",time()) ? 'true' : 'false').'" selected="'.(date("Ymd",$timestamp)==date("Ymd",$date) ? 'true' : 'false').'" title="'.$title.'">';
			$xml.=XmlUtils::buildDate('date',$timestamp);
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
		return array('xml'=>$xml,'first'=>$startDay,'last' => $endDay,'next' => DateUtil::addMonths($date,1),'previous' => DateUtil::addMonths($date,-1));
	}

	function buildAgendaView($date,$id,$refresh) {
		$startDay = DateUtil::getMonthStart($date);
		$endDay = DateUtil::getMonthEnd($date);
		$days = date("d",$endDay);
		$query = array('sort' => 'startDate', 'startDate' => $startDay, 'endDate' => $endDay);
		$events = $this->getEvents($id,$query,$refresh);
		$xml='<agendaview>';
		foreach ($events as $event) {
			$xml.=$this->buildEventXML($event);
		}
		$xml.='</agendaview>';
		return array('xml'=>$xml,'first'=>$startDay,'last' => $endDay,'next' => DateUtil::addMonths($date,1),'previous' => DateUtil::addMonths($date,-1));
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