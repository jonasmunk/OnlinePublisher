<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes
 */
if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}
require_once($basePath.'Editor/Classes/Model/Object.php');
require_once($basePath.'Editor/Classes/Utilities/DateUtils.php');

Object::$schema['event'] = array(
	'location'   => array('type'=>'string'),
	'startdate'  => array('type'=>'datetime'),
	'enddate'  => array('type'=>'datetime')
);
class Event extends Object {
	var $startdate;
	var $enddate;
	var $location;
	
	function Event() {
		parent::Object('event');
	}

	function load($id) {
		return Object::get($id,'event');
	}

	function removeMore() {
		$sql="delete from calendar_event where event_id=".$this->id;
		Database::delete($sql);
	}
	
	function setLocation($location) {
	    $this->location = $location;
	}

	function getLocation() {
	    return $this->location;
	}
	
	function setStartdate($stamp) {
		$this->startdate = $stamp;
	}
	
	function getStartdate() {
		return $this->startdate;
	}
	
	function setEnddate($stamp) {
		$this->enddate = $stamp;
	}
	
	function getEnddate() {
		return $this->enddate;
	}
	
	////////////////////////////// Utils ///////////////////////////
	
	function getCalendarIds() {
		global $basePath;
		require_once($basePath.'Editor/Classes/Core/Database.php');
		$sql="select calendar_id as id from calendar_event where event_id=".$this->id;
		return Database::getIds($sql);
	}
	
	function updateCalendarIds($ids) {
		$sql="delete from calendar_event where event_id=".$this->id;
		Database::delete($sql);
		foreach ($ids as $id) {
			$sql="insert into calendar_event (event_id, calendar_id) values (".$this->id.",".$id.")";
			Database::insert($sql);
		}
	}
	
	/**
	 * @static
	 */
    function search($query = array()) {
        $out = array();
		if (isset($query['calendarId'])) {
        	$sql = "select object.id from object,event,calendar_event where object.id=event.object_id and object.id=calendar_event.event_id and calendar_event.calendar_id=".$query['calendarId'];
		} else {
        	$sql = "select id from object,event where object.id=event.object_id";
		}
		if (isset($query['startDate']) && isset($query['endDate'])) {
			$sql.=" and not (startdate>".Database::datetime($query['endDate'])." or endDate<".Database::datetime($query['startDate']).")";
		}
		$sql.=" order by event.startdate";
        $result = Database::select($sql);
		$ids = array();
        while ($row = Database::next($result)) {
            $ids[] = $row['id'];
        }
        Database::free($result);
		foreach ($ids as $id) {
			$out[] = Event::load($id);
		}
        return $out;
    }

	/**
	 * 
	 */
    function getSimpleEvents($query = array()) {
        $out = array();
		$sql = "select object.id,object.title,object.note,event.location,unix_timestamp(event.startdate) as startdate,unix_timestamp(event.enddate) as enddate ";
		if (isset($query['calendarId'])) {
        	$sql .= "from object,event,calendar_event where object.id=event.object_id and object.id=calendar_event.event_id and calendar_event.calendar_id=".$query['calendarId'];
		} else {
        	$sql .= " from object,event where object.id=event.object_id";
		}
		if (isset($query['startDate']) && isset($query['endDate'])) {
			$sql.=" and not (startdate>".Database::datetime($query['endDate'])." or endDate<".Database::datetime($query['startDate']).")";
		}
		$sql.=" order by object.title";
        $result = Database::select($sql);
		$ids = array();
        while ($row = Database::next($result)) {
            $out[] = array(
				'id' => $row['id'],
				'summary' => $row['title']."\n".$row['note'],
				'location' => $row['location'],
				'uniqueId' => $row['id'],
				'recurring' => false,
				'startDate' => $row['startdate'],
				'endDate' => $row['enddate'],
				'calendarTitle' => (isset($query['calendarTitle']) ? $query['calendarTitle'] : '')
			);
        }
        Database::free($result);
        return $out;
    }
	
	
	////////////////////////////// Persistence ////////////////////////
	
	function sub_publish() {
		$data = '<event xmlns="'.parent::_buildnamespace('1.0').'">';
		if (isset($this->startdate)) {
			$data.=DateUtils::buildTag('startdate',$this->startdate);
		}
		if (isset($this->enddate)) {
			$data.=DateUtils::buildTag('enddate',$this->enddate);
		}
		$data.='</event>';
		return $data;
	}
}
?>