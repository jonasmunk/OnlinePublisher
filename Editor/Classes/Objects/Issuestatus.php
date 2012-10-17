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
class IssueStatus extends Object {
    
    function IssueStatus() {
		parent::Object('issuestatus');
    }

	function load($id) {
		return Object::get($id,'issuestatus');
	}
}
?>