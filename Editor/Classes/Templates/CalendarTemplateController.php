<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Templates
 */
if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

class CalendarTemplateController extends TemplateController
{
	function CalendarTemplateController() {
		parent::TemplateController('calendar');
	}

	function create($page) {
		$sql="insert into calendarviewer (page_id,title) values (".Database::int($page->getId()).",".Database::text($page->getTitle()).")";
		Database::insert($sql);
	}

	function delete($page) {
		$sql="delete from calendarviewer where page_id=".Database::int($page->getId());
		Database::delete($sql);
		$sql="delete from calendarviewer_object where page_id=".Database::int($page->getId());
		Database::delete($sql);
	}

    function build($id) {
		$data = '<calendar xmlns="http://uri.in2isoft.com/onlinepublisher/publishing/calendar/1.0/">';
		$data.= '<!--dynamic-->';
		$data.= '</calendar>';
        return array('data' => $data, 'dynamic' => true, 'index' => '');
    }

	function dynamic($id,&$state) {
		$refresh = Request::getBoolean('refresh');
		$date = Request::getDate('date');
		if (!$date) {
			$date=Dates::stripTime(time());
		}
		$sql="select * from calendarviewer where page_id = ".Database::int($id);
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
		$xml.=Dates::buildTag('date',$date);
		$xml.=Dates::buildTag('today',time());
		$xml.=Dates::buildTag('next',$info['next']);
		$xml.=Dates::buildTag('previous',$info['previous']);
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
		usort($events,'Calendarsource::_startDateComparator');
		return $events;
	}


	function buildWeekView($date,$id,$refresh,$setup) {
		$startHour = $setup['weekview_starthour'];
		$firstWeekDay = Dates::getWeekStart($date);
		$lastWeekDay = Dates::addDays($firstWeekDay,7);
		$query = array('sort' => 'startDate', 'startDate' => $firstWeekDay, 'endDate' => $lastWeekDay);
		$events = $this->getEvents($id,$query,$refresh);
		$xml='<weekview>';
		$derived = array();
		for ($i=0;$i<7;$i++) {
			$timestamp = Dates::addDays($firstWeekDay,$i);
			$timestampEnd = Dates::addDays($firstWeekDay,$i+1)-1;
			$xml.='<day date="'.date("Ymd",$timestamp).'" today="'.(date("Ymd",$timestamp)==date("Ymd",time()) ? 'true' : 'false').'" selected="'.(date("Ymd",$timestamp)==date("Ymd",$date) ? 'true' : 'false').'" title="'.Dates::formatShortDate($timestamp).'">';
			$xml.=Dates::buildTag('date',$timestamp);
			$dayEvents = array();
			foreach ($events as $event) {
				$event = EventUtils::getEventInsidePeriod($timestamp,$timestampEnd,$event);
				if ($event!=null) {
					$secs = Dates::getSecondsSinceMidnight($event['startDate']);
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
		return array('xml'=>$xml,'first'=>$firstWeekDay,'last' => $lastWeekDay,'next' => Dates::addDays($date,7),'previous' => Dates::addDays($date,-7));
	}


	function buildMonthView($date,$id,$refresh) {
		$startDay = Dates::getMonthStart($date);
		$startDay = Dates::getWeekStart($startDay);
		$endDay = Dates::getMonthEnd($date);
		$days = date("d",$endDay);
		$days=35;
		$query = array('sort' => 'startDate', 'startDate' => $startDay, 'endDate' => $endDay);
		$events = $this->getEvents($id,$query,$refresh);
		$xml='<monthview>';
		$derived = array();
		for ($i=0;$i<$days;$i++) {
			$timestamp = Dates::addDays($startDay,$i);
			$timestampEnd = Dates::addDays($startDay,$i+1)-1;
			$weekday = Dates::getWeekDay($timestamp);
			if ($weekday==0) {
				$xml.='<week>';
			}
			$title = Dates::formatDate($timestamp,array('shortWeekday'=>true,'year'=>false));
			$title = mb_convert_encoding($title, "ISO-8859-1","UTF-8");
			$xml.='<day date="'.date("Ymd",$timestamp).'" today="'.(date("Ymd",$timestamp)==date("Ymd",time()) ? 'true' : 'false').'" selected="'.(date("Ymd",$timestamp)==date("Ymd",$date) ? 'true' : 'false').'" title="'.$title.'">';
			$xml.=Dates::buildTag('date',$timestamp);
			$dayEvents = array();
			foreach ($events as $event) {
				$event = EventUtils::getEventInsidePeriod($timestamp,$timestampEnd,$event);
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
		return array('xml'=>$xml,'first'=>$startDay,'last' => $endDay,'next' => Dates::addMonths($date,1),'previous' => Dates::addMonths($date,-1));
	}

	function buildEventXML(&$event) {
		return '<event unique-id="'.Strings::escapeXML($event['uniqueId']).'" collision-count="'.(isset($event['collisionCount']) ? $event['collisionCount'] : 0).'" collision-number="'.(isset($event['collisionNumber']) ? $event['collisionNumber'] : 0).'" height="'.(isset($event['height']) ? $event['height'] : '').'" top="'.(isset($event['top']) ? $event['top'] : '').'" time-from="'.Dates::formatShortTime($event['startDate']).'" time-to="'.Dates::formatShortTime($event['endDate']).'">'.
		Dates::buildTag('start',$event['startDate']).
		Dates::buildTag('end',$event['endDate']).
		'<summary>'.Strings::escapeXML($event['summary']).'</summary>'.
		'<description>'.Strings::escapeXML($event['description']).'</description>'.
		'<location>'.Strings::escapeXML($event['location']).'</location>'.
		'<calendar>'.Strings::escapeXML($event['calendarTitle']).'</calendar>'.
		'</event>';
	}

	function buildListView($date,$id,$refresh) {
		$startDay = Dates::getMonthStart($date);
		$endDay = Dates::getMonthEnd($date);
		$days = date("d",$endDay);
		$query = array('sort' => 'startDate', 'startDate' => $startDay, 'endDate' => $endDay);
		$events = $this->getEvents($id,$query,$refresh);
		$xml='<listview>';
		$derived = array();
		for ($i=0;$i<$days;$i++) {
			$timestamp = Dates::addDays($startDay,$i);
			$timestampEnd = Dates::addDays($startDay,$i+1)-1;
			$title = Dates::formatDate($timestamp,array('shortWeekday'=>true,'year'=>false));
			$title = mb_convert_encoding($title, "ISO-8859-1","UTF-8");
			$xml.='<day date="'.date("Ymd",$timestamp).'" today="'.(date("Ymd",$timestamp)==date("Ymd",time()) ? 'true' : 'false').'" selected="'.(date("Ymd",$timestamp)==date("Ymd",$date) ? 'true' : 'false').'" title="'.$title.'">';
			$xml.=Dates::buildTag('date',$timestamp);
			$dayEvents = array();
			foreach ($events as $event) {
				$event = EventUtils::getEventInsidePeriod($timestamp,$timestampEnd,$event);
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
		return array('xml'=>$xml,'first'=>$startDay,'last' => $endDay,'next' => Dates::addMonths($date,1),'previous' => Dates::addMonths($date,-1));
	}

	function buildAgendaView($date,$id,$refresh) {
		$startDay = Dates::getMonthStart($date);
		$endDay = Dates::getMonthEnd($date);
		$days = date("d",$endDay);
		$query = array('sort' => 'startDate', 'startDate' => $startDay, 'endDate' => $endDay);
		$events = $this->getEvents($id,$query,$refresh);
		$xml='<agendaview>';
		foreach ($events as $event) {
			$xml.=$this->buildEventXML($event);
		}
		$xml.='</agendaview>';
		return array('xml'=>$xml,'first'=>$startDay,'last' => $endDay,'next' => Dates::addMonths($date,1),'previous' => Dates::addMonths($date,-1));
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
					if (EventUtils::isEventsColliding($event,$other)) {
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