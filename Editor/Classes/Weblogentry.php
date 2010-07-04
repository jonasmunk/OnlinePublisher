<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes
 */
require_once($basePath.'Editor/Classes/Object.php');
require_once($basePath.'Editor/Classes/Page.php');

class Weblogentry extends Object {
	var $text;
	var $date;
	var $pageId;
	var $groups;

	function Weblogentry() {
		parent::Object('weblogentry');
	}
	
	function toUnicode() {
		parent::toUnicode();
		$this->text = mb_convert_encoding($this->text, "UTF-8","ISO-8859-1");
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

	function load($id) {
		$obj = new Weblogentry();
		if ($obj->_load($id)) {
			$sql = "select page_id,text,UNIX_TIMESTAMP(date) as date from weblogentry where object_id=".$id;
			$row = Database::selectFirst($sql);
			if ($row) {
				$obj->text=$row['text'];
				$obj->date=$row['date'];
				$obj->pageId=$row['page_id'];
			}
			return $obj;
		} else {
			return false;
		}
	}

	function sub_create() {
		$sql="insert into weblogentry (object_id,text,date,page_id) values (".
		$this->id.
		",".Database::text($this->text).
		",".sqlTimestamp($this->date).
		",".sqlInt($this->pageId).
		")";
		Database::insert($sql);
	}

	function sub_update() {
		$sql = "update weblogentry set ".
		"text=".Database::text($this->text).
		",date=".sqlTimestamp($this->date).
		",page_id=".sqlInt($this->pageId).
		" where object_id=".$this->id;
		Database::update($sql);
	}

	function sub_publish() {
		$data =
		'<weblogentry xmlns="'.parent::_buildnamespace('1.0').'">'.
		$this->_builddate('date',$this->date).
		'<text><![CDATA['.escapeXMLwithLineBreak($this->text,'<br/>').']]></text>';
		$data.='</weblogentry>';
		return $data;
	}

	function sub_remove() {
		$sql = "delete from weblogentry where object_id=".$this->id;
		Database::delete($sql);
		$sql = "delete from webloggroup_weblogentry where weblogentry_id=".$this->id;
		Database::delete($sql);
	}
	
	//////////////////////////// Convenience ///////////////////////////
	
	

	function changeGroups($groups) {
		$sql="delete from webloggroup_weblogentry where weblogentry_id=".$this->id;
		Database::delete($sql);
		foreach ($groups as $id) {
			$sql="insert into webloggroup_weblogentry (weblogentry_id,webloggroup_id) values (".$this->id.",".$id.")";
			Database::insert($sql);
		}
	}
	
	/**
	 * @static
	 */
    function search($query = array()) {
        $out = array();
        $sql = "select id from object,weblogentry where object.id=weblogentry.object_id";
		$sql.=" order by object.title";
        $result = Database::select($sql);
		$ids = array();
        while ($row = Database::next($result)) {
            $ids[] = $row['id'];
        }
        Database::free($result);
		foreach ($ids as $id) {
			$out[] = Event::load($id);
		}
        return $out;
    }
}
?>