<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Objects
 */
if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}
require_once($basePath.'Editor/Classes/Object.php');

Object::$schema['cachedurl'] = array(
	'url'			=> array('type'=>'text'),
	'synchronized'	=> array('type'=>'datetime'),
	'mimeType'		=> array('type'=>'text')
);
class Cachedurl extends Object {
	var $url;
	var $synchronized;
	var $mimeType;

	function Cachedurl() {
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
	
	function setSynchronized($synchronized) {
	    $this->synchronized = $synchronized;
	}

	function getSynchronized() {
	    return $this->synchronized;
	}
	
	function setMimeType($mimeType) {
	    $this->mimeType = $mimeType;
	}

	function getMimeType() {
	    return $this->mimeType;
	}
	
}