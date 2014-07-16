<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes
 */
if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

Entity::$schema['Testphrase'] = [
	'table' => 'testphrase',
	'properties' => []
];

class Testphrase extends Object {

	function Testphrase() {
		parent::Object('testphrase');
	}
	
	static function load($id) {
		return Object::get($id,'testphrase');
	}
		
	/////////////////////////// GUI /////////////////////////
	
	function getIcon() {
	    return 'monochrome/globe';
	}
}
?>