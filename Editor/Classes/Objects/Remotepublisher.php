<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes
 */
if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

Entity::$schema['Remotepublisher'] = [
	'table' => 'remotepublisher',
	'properties' => [
	    'url' => ['type'=>'text'],
	]
];

class Remotepublisher extends Object {
	var $url;

	function Remotepublisher() {
		parent::Object('remotepublisher');
	}
	
	static function load($id) {
		return Object::get($id,'remotepublisher');
	}
	
	function setUrl($url) {
	    $this->url = $url;
	}

	function getUrl() {
	    return $this->url;
	}
	
}
?>