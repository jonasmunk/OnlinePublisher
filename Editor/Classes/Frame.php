<?
/**
 * @package OnlinePublisher
 * @subpackage Classes
 */
if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}
require_once($basePath.'Editor/Classes/Database.php');
require_once($basePath.'Editor/Classes/Object.php');

class Frame {
        
	var $id;
	var $title;
	var $name;
	var $hierarchyId;
	var $changed;

    function Frame() {
    }

	function isPersistent() {
		return $this->id>0;
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
	
	function getChanged() {
		return $this->changed;
	}

	function load($id) {
		$sql = "select id,title,name,hierarchy_id,UNIX_TIMESTAMP(changed) as changed from frame where id=".Database::int($id);
		if ($row = Database::selectFirst($sql)) {
			$frame = new Frame();
			$frame->setId($row['id']);
			$frame->setTitle($row['title']);
			$frame->setName($row['name']);
			$frame->setHierarchyId($row['hierarchy_id']);
			$frame->changed = $row['changed'];
			return $frame;
		}
		return null;
	}

	function save() {
		$sql = array(
			'table' => 'frame',
			'values' => array(
				'title' => Database::text($this->title),
				'name' => Database::text($this->name),
				'hierarchy_id' => Database::int($this->hierarchyId),
				'changed' => Database::datetime(time())
			),
			'where' => array( 'id' => $this->id)
		);
		
		if ($this->id>0) {
			Database::update($sql);
		} else {
			$this->id = Database::insert($sql);
		}
	}
	
	function remove() {
		if ($this->id>0 && $this->canRemove()) {
			$sql = "delete from frame where id=".Database::int($this->id);
			return Database::delete($sql)>0;
		}
		return false;
	}
	
    function canRemove() {
        $sql="select count(id) as num from page where frame_id=".Database::int($this->id);
        if ($row = Database::selectFirst($sql)) {
            return $row['num']==0;
        }
        return true;
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