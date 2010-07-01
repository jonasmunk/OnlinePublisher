<?
/**
 * @package OnlinePublisher
 * @subpackage Classes
 */
require_once($basePath.'Editor/Classes/Database.php');
require_once($basePath.'Editor/Classes/Object.php');

class Frame {
        
	var $id;
	var $title;
	var $name;
	var $hierarchyId;

    function Frame() {
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
	
	function setName($name) {
	    $this->name = $name;
	}

	function getName() {
	    return $this->name;
	}
	
	function setHierarchyId($id) {
	    $this->hierarchyId = $id;
	}

	function getHierarchyId() {
	    return $this->hierarchyId;
	}

	function load($id) {
		$sql = "select id,title,name,hierarchy_id from frame where id=".$id;
		$result = Database::select($sql);
		$frame = null;
		if ($row = Database::next($result)) {
			$frame = new Frame();
			$frame->setId($row['id']);
			$frame->setTitle($row['title']);
			$frame->setName($row['name']);
			$frame->setHierarchyId($row['hierarchy_id']);
		}
		Database::free($result);
		return $frame;
	}

	function search() {
		$list = array();
		$sql = "select id,title,name,hierarchy_id from frame order by name";
		$result = Database::select($sql);
		while ($row = Database::next($result)) {
			$frame = new Frame();
			$frame->setId($row['id']);
			$frame->setTitle($row['title']);
			$frame->setName($row['name']);
			$frame->setHierarchyId($row['hierarchy_id']);
			$list[] = $frame;
		}
		Database::free($result);
		return $list;
	}
}