<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes
 */
if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

Entity::$schema['Persongroup'] = [
	'table' => 'persongroup',
	'properties' => []
];

class Persongroup extends Object {
	
	function Persongroup() {
		parent::Object('persongroup');
	}
	
	function getIcon() {
		return 'common/folder';
	}
	
	static function load($id) {
		return Object::get($id,'persongroup');
	}
	
	function removeMore() {
		$sql="delete from persongroup_person where persongroup_id=".Database::int($this->id);
		Database::delete($sql);
	}
}
?>