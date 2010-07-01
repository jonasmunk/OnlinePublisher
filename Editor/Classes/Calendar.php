<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes
 */
require_once($basePath.'Editor/Classes/Object.php');

class Calendar extends Object {

	function Calendar() {
		parent::Object('calendar');
	}

	function load($id) {
		$obj = new Calendar();
		$obj->_load($id);
		$sql = "select * from calendar where object_id=".$id;
		$row = Database::selectFirst($sql);
		if ($row) {
		}
		return $obj;
	}

	function sub_create() {
		$sql="insert into calendar (object_id) values (".
		$this->id.
		")";
		Database::insert($sql);
	}

	function sub_update() {
	}

	function sub_publish() {
		$data =
		'<calendar xmlns="'.parent::_buildnamespace('1.0').'">'.
		'</calendar>';
		return $data;
	}

	function sub_remove() {
		$sql = "delete from calendar where object_id=".$this->id;
		Database::delete($sql);
		$sql = "delete from calendar_event where calendar_id=".$this->id;
		Database::delete($sql);
	}
	
	function getIcon() {
		return 'Tool/Calendar';
	}
	
	function getIn2iGuiIcon() {
		return 'common/calendar';
	}
	
	/**
	 * @static
	 */
	function search() {
		$results = array();
		$sql = "select id from object where type='calendar' order by title";
		$result = Database::select($sql);
		while ($row = Database::next($result)) {
			$obj = new Calendar();
			$obj->_load($row['id']);
			$results[] = $obj;
		}
		Database::free($result);
		return $results;
	}
}
?>