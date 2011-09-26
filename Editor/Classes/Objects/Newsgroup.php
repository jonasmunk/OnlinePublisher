<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes
 */
if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}
require_once($basePath.'Editor/Classes/Model/Object.php');

Object::$schema['newsgroup'] = array();

class NewsGroup extends Object {
	
	function NewsGroup() {
		parent::Object('newsgroup');
	}
	
	function search() {
		$results = array();
		$sql = "select id from object where type='newsgroup' order by title";
		$result = Database::select($sql);
		while ($row = Database::next($result)) {
			$obj = new NewsGroup();
			$obj->_load($row['id']);
			$results[] = $obj;
		}
		Database::free($result);
		return $results;
	}
	
	function load($id) {
		return Object::get($id,'newsgroup');
	}
	
	function removeMore() {
		$sql="delete from newsgroup_news where newsgroup_id=".$this->id;
		Database::delete($sql);
		$sql="delete from part_news_newsgroup where newsgroup_id=".$this->id;
		Database::delete($sql);
	}
	
	function canDelete() {
		$sql="select id from frame_newsblock_newsgroup where newsgroup_id=".$this->id;
		if (Database::selectFirst($sql)) {
			return FALSE;
		} else {
			return TRUE;
		}
	}
	
	function getIn2iGuiIcon() {
        return "common/folder";
	}
}
?>