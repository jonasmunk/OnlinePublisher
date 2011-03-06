<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes
 */
require_once($basePath.'Editor/Classes/Object.php');
require_once($basePath.'Editor/Classes/Utilities/DateUtils.php');

class Event extends Object {
	var $startdate;
	var $enddate;
	var $location;
	
	function Event() {
		parent::Object('event');
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
	
	function toUnicode() {
		parent::toUnicode();
		$this->location = mb_convert_encoding($this->location, "UTF-8","ISO-8859-1");
	}
	
	////////////////////////////// Utils ///////////////////////////
	
	function getCalendarIds() {
		global $basePath;
		require_once($basePath.'Editor/Classes/Database.php');
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
	
	function load($id) {
		$obj = new Event();
		$obj->_load($id);
		$sql = "select UNIX_TIMESTAMP(startdate) as startdate,UNIX_TIMESTAMP(enddate) as enddate,location from event where object_id=".$id;
		$row = Database::selectFirst($sql);
		if ($row) {
			$obj->enddate=$row['enddate'];
			$obj->startdate=$row['startdate'];
			$obj->location=$row['location'];
		}
		return $obj;
	}
	
	function sub_create() {
		$sql="insert into event (object_id,startdate,enddate,location) values (".
		$this->id.
		",".Database::datetime($this->startdate).
		",".Database::datetime($this->enddate).
		",".Database::text($this->location).
		")";
		Database::insert($sql);
	}
	
	function sub_update() {
		$sql = "update event set ".
		"startdate=".Database::datetime($this->startdate).
		",enddate=".Database::datetime($this->enddate).
		",location=".Database::text($this->location).
		" where object_id=".$this->id;
		error_log($sql);
		Database::update($sql);
	}
	
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
	
	function sub_remove() {
		$sql="delete from calendar_event where event_id=".$this->id;
		Database::delete($sql);
		$sql = "delete from event where object_id=".$this->id;
		Database::delete($sql);
	}
	
	/////////////////////////// GUI /////////////////////////
	
	function getIcon() {
        return "Basic/Time";
	}
}
?>