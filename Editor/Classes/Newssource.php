<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes
 */
require_once($basePath.'Editor/Classes/Object.php');

class NewsSource extends Object {
	var $url;
	
	function News() {
		parent::Object('newssource');
	}
	
	function setUrl($url) {
		$this->url = $url;
	}
	
	function getUrl() {
		return $this->url;
	}
	
	function load($id) {
		$obj = new NewsSource();
		$obj->_load($id);
		$sql = "select url from newssource where object_id=".$id;
		$row = Database::selectFirst($sql);
		if ($row) {
			$obj->url=$row['url'];
		}
		return $obj;
	}
	
	function sub_create() {
		$sql="insert into newssource (object_id,url) values (".
		$this->id.
		",".sqlText($this->url).
		")";
		Database::insert($sql);
	}
	
	function sub_update() {
		$sql = "update newssource set ".
		"url=".sqlText($this->url).
		" where object_id=".$this->id;
		Database::update($sql);
	}
	
	function sub_publish() {
		$data = '<newssource xmlns="'.parent::_buildnamespace('1.0').'">'.
		'<url>'.encodeXML($this->url).'</url>'.
		'</newssource>';
		return $data;
	}
	
	function sub_remove() {
		$sql = "delete from newssource where object_id=".$this->id;
		Database::delete($sql);
	}
}
?>