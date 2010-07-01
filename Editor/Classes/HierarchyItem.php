<?
/**
 * @package OnlinePublisher
 * @subpackage Classes
 */
require_once($basePath.'Editor/Classes/Database.php');
require_once($basePath.'Editor/Classes/Object.php');

class HierarchyItem {
        
	var $id;
	var $title;
	var $hidden;

    function HierarchyItem() {
    }

	function setId($id) {
	    $this->id = $id;
	}

	function getId() {
	    return $this->id;
	}
	
	function setTitle($title) {
	    $this->title = $title;
	}

	function getTitle() {
	    return $this->title;
	}

	function setHidden($hidden) {
	    $this->hidden = $hidden;
	}

	function getHidden() {
	    return $this->hidden;
	}
	
	function toUnicode() {
		$this->title = mb_convert_encoding($this->title, "UTF-8","ISO-8859-1");
	}

	function load($id) {
		$sql = "select id,title,hidden from hierarchy_item where id=".$id;
		$result = Database::select($sql);
		$item = null;
		if ($row = Database::next($result)) {
			$item = new HierarchyItem();
			$item->setId($row['id']);
			$item->setTitle($row['title']);
			$item->setHidden($row['hidden']==1);
		}
		Database::free($result);
		return $item;
	}
	
	function save() {
		if ($this->id>0) {
			$sql="update hierarchy_item set".
			" title=".Database::text($this->title).
			",hidden=".Database::boolean($this->hidden).
			" where id=".$this->id;
			Database::update($sql);
		}
	}
}