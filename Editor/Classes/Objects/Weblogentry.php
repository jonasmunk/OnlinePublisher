<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes
 */
if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

Object::$schema['weblogentry'] = array(
	'text' => array('type'=>'string'),
	'date'  => array('type'=>'datetime'),
	'pageId' => array('type'=>'int','column'=>'page_id')
);
class Weblogentry extends Object {
	var $text;
	var $date;
	var $pageId;
	var $groups;

	function Weblogentry() {
		parent::Object('weblogentry');
	}

	static function load($id) {
		return Object::get($id,'weblogentry');
	}
	
	function setText($text) {
	    $this->text = $text;
	}

	function getText() {
	    return $this->text;
	}
	
	function setDate($date) {
	    $this->date = $date;
	}

	function getDate() {
	    return $this->date;
	}
	
	function setPageId($pageId) {
	    $this->pageId = $pageId;
	}

	function getPageId() {
	    return $this->pageId;
	}
	
	function loadGroups() {
		$this->groups = array();
		$sql = "select object.title,object.id from webloggroup_weblogentry,object where webloggroup_weblogentry.webloggroup_id=object.id and weblogentry_id=".$this->id." order by object.title";
		$subResult = Database::select($sql);
		while ($subRow = Database::next($subResult)) {
			$this->groups[] = $subRow['id'];
		}
		Database::free($subResult);
	}
	
	////////////////////////////// Persistence ///////////////////////

	function sub_publish() {
		$data =
		'<weblogentry xmlns="'.parent::_buildnamespace('1.0').'">'.
		Dates::buildTag('date',$this->date).
		'<text><![CDATA['.Strings::escapeSimpleXMLwithLineBreak($this->text,'<br/>').']]></text>';
		$data.='</weblogentry>';
		return $data;
	}

	function removeMore() {
		$sql = "delete from webloggroup_weblogentry where weblogentry_id=".Database::int($this->id);
		Database::delete($sql);
	}
	
	//////////////////////////// Convenience ///////////////////////////
	
	

	function changeGroups($groups) {
			Log::debug($groups);
		if (!is_array($groups)) {
			Log::debug('Not a group');
			return;
		}
		$sql="delete from webloggroup_weblogentry where weblogentry_id=".Database::int($this->id);
		Database::delete($sql);
		foreach ($groups as $id) {
			$sql="insert into webloggroup_weblogentry (weblogentry_id,webloggroup_id) values (".Database::int($this->id).",".Database::int($id).")";
			Database::insert($sql);
		}
	}
}
?>