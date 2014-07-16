<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Objects
 */
if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

Entity::$schema['Cachedurl'] = [
    'table' => 'cachedurl',
    'properties' => [
        'url'			=> array('type'=>'string'),
        'synchronized'	=> array('type'=>'datetime'),
        'mimeType'		=> array('type'=>'string')
    ]
];

class Cachedurl extends Object {
	var $url;
	var $synchronized;
	var $mimeType;

	function Cachedurl() {
		parent::Object('cachedurl');
	}
	
	static function load($id) {
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