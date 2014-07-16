<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Objects
 */
if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

Entity::$schema['Issuestatus'] = [
    'table' => 'issuestatus',
    'properties' => []
];

class Issuestatus extends Object {
    
    function Issuestatus() {
		parent::Object('issuestatus');
    }

	static function load($id) {
		return Object::get($id,'issuestatus');
	}
}
?>