<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes
 */
if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}
require_once($basePath.'Editor/Classes/Object.php');

Object::$schema['persongroup'] = array();
class PersonGroup extends Object {
	
	function PersonGroup() {
		parent::Object('persongroup');
	}
	
	function getIn2iGuiIcon() {
		return 'common/folder';
	}
	
	function load($id) {
		return Object::get($id,'persongroup');
	}
	
	function removeMore() {
		$sql="delete from persongroup_person where persongroup_id=".Database::int($this->id);
		Database::delete($sql);
	}
}
?>