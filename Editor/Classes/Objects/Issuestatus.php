<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Objects
 */
if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

Object::$schema['issuestatus'] = array(
);
class Issuestatus extends Object {
    
    function Issuestatus() {
		parent::Object('issuestatus');
    }

	static function load($id) {
		return Object::get($id,'issuestatus');
	}
}
?>