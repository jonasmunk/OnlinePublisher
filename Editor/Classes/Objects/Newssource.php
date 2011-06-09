<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Objects
 */
require_once($basePath.'Editor/Classes/Object.php');
require_once($basePath.'Editor/Classes/Services/NewsService.php');

Object::$schema['newssource'] = array(
	'url' => array('type'=>'text'),
	'synchronized'		=> array('type'=>'datetime'),
	'syncInterval'		=> array('type'=>'int','column'=>'sync_interval')
);
class Newssource extends Object {
	var $url;
	var $synchronized;
	var $syncInterval;
	
	function Newssource() {
		parent::Object('newssource');
	}
	
	function load($id) {
		return Object::get($id,'newssource');
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
	
	function setSyncInterval($syncInterval) {
	    $this->syncInterval = $syncInterval;
	}

	function getSyncInterval() {
	    return $this->syncInterval;
	}

	function isInSync() {
		return (time() - $this->synchronized < $this->syncInterval);
	}
	
	function synchronize($force=false) {
		global $basePath;
		if ($this->isInSync() && $force==false) {
			return;
		}
		NewsService::synchronizeSource($this->id);
	}
	
	
	function getIn2iGuiIcon() {
		return 'common/internet';
	}
	
	function removeMore() {
		$items = Query::after('newssourceitem')->withProperty('newssource_id',$this->id)->get();
		foreach ($items as $item) {
			$item->remove();
		}
	}
}