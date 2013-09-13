<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes
 */
if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

Object::$schema['calendar'] = array();
class Calendar extends Object {

	function Calendar() {
		parent::Object('calendar');
	}

	static function load($id) {
		return Object::get($id,'calendar');
	}

	function removeMore() {
		$sql = "delete from calendar_event where calendar_id=".$this->id;
		Database::delete($sql);
	}
	
	function getIcon() {
		return 'common/calendar';
	}
}
?>