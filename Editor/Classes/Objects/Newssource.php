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
	
	function getIn2iGuiIcon() {
		return 'common/internet';
	}
	
	function removeMore() {
		$items = Query::after('newssourceitem')->withProperty('newssource_id',$this->id)->get();
		foreach ($items as $item) {
			//Log::debug('Removing: '.$item->getId());
			$item->remove();
		}
	}
}