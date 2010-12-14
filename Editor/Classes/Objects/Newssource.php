<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Objects
 */
require_once($basePath.'Editor/Classes/Object.php');

Object::$schema['newssource'] = array(
	'url' => array('type'=>'text')
);
class Newssource extends Object {
	var $url;

	function Newssource() {
		parent::Object('cachedurl');
	}
	
	function load($id) {
		return Object::get($id,'cachedurl');
	}
	
	function setUrl($url) {
	    $this->url = $url;
	}

	function getUrl() {
	    return $this->url;
	}
}