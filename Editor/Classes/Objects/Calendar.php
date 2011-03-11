<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes
 */
require_once($basePath.'Editor/Classes/Object.php');

Object::$schema['calendar'] = array();
class Calendar extends Object {

	function Calendar() {
		parent::Object('calendar');
	}

	function load($id) {
		return Object::get($id,'calendar');
	}

	function removeMore() {
		$sql = "delete from calendar_event where calendar_id=".$this->id;
		Database::delete($sql);
	}
	
	function getIn2iGuiIcon() {
		return 'common/calendar';
	}
}
?>