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
require_once($basePath.'Editor/Classes/Utilities/StringUtils.php');

Object::$schema['remotepublisher'] = array(
	'url'			=> array('type'=>'text'),
);
class Remotepublisher extends Object {
	var $url;

	function Remotepublisher() {
		parent::Object('remotepublisher');
	}
	
	function load($id) {
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