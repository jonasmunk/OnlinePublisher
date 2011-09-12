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

class Link {
    
    var $id;
    var $text;
	var $alternative;
	var $targetType;
	var $targetValue;
	var $targetId;
	var $pageId;
	var $partId;
    
    function Link() {
        
    }

	function setId($id) {
	    $this->id = $id;
	}

	function getId() {
	    return $this->id;
	}
	
	function setPageId($pageId) {
	    $this->pageId = $pageId;
	}

	function getPageId() {
	    return $this->pageId;
	}
	
	function setPartId($partId) {
	    $this->partId = $partId;
	}

	function getPartId() {
	    return $this->partId;
	}
	
	
	function setText($text) {
	    $this->text = $text;
	}

	function getText() {
	    return $this->text;
	}
	
	function setAlternative($alternative) {
	    $this->alternative = $alternative;
	}

	function getAlternative() {
	    return $this->alternative;
	}
	
	function setTypeAndValue($type,$value) {
		error_log($type.'>'.$value);
		if ($type=='page' || $type=='file') {
			$this->targetType = $type;
			$this->targetId=intval($value);
			$this->targetValue=null;
		} else if ($type=='url' || $type=='email') {
			$this->targetType = $type;
			$this->targetValue=$value;
			$this->targetId=null;
		}
	}
	
	function getUrl() {
		if ($this->targetType=='url') {
			return $this->targetValue;
		} else {
			return '';
		}
	}
	
	function getEmail() {
		if ($this->targetType=='email') {
			return $this->targetValue;
		} else {
			return '';
		}
	}
	
	function getPage() {
		if ($this->targetType=='page') {
			return $this->targetId;
		} else {
			return null;
		}
	}
	
	function getFile() {
		if ($this->targetType=='file') {
			return $this->targetId;
		} else {
			return null;
		}
	}

	function load($id) {
		$sql = "select * from link where id=".Database::int($id);
        if ($row = Database::selectFirst($sql)) {
			$link = new Link();
			$link->setId(intval($id));
			$link->setText($row['source_text']);
			$link->setAlternative($row['alternative']);
			$link->targetType=$row['target_type'];
			$link->targetValue=$row['target_value'];
			$link->targetId=intVal($row['target_id']);
			$link->partId=intVal($row['part_id']);
			$link->pageId=intVal($row['page_id']);
			return $link;
		}
		return null;
	}
	
	function remove() {
		$sql="delete from link where id=".Database::int($this->id);
		Database::delete($sql);
	}
	
	function save() {
		if (strlen($this->text)==0) {
			return;
		}
		if ($this->id) {
			$sql="update link set ".
			"part_id=".Database::int($this->partId).
			",page_id=".Database::int($this->pageId).
			",source_text=".Database::text($this->text).
			",target_type=".Database::text($this->targetType).
			",target_value=".Database::text($this->targetValue).
			",target_id=".Database::int($this->targetId).
			",target=".Database::text($this->target).
			",alternative=".Database::text($this->alternative).
			" where id=".Database::int($this->id);
			Database::update($sql);
		} else {
			$sql="insert into link (page_id,part_id,source_type,source_text,target_type,target_value,target_id,target,alternative
				) values (".
				Database::int($this->pageId).
				",".Database::int($this->partId).
				",'text',".
				Database::text($this->text).",".
				Database::text($this->targetType).",".
				Database::text($this->targetValue).",".
				Database::int($this->targetId).",".
				Database::text($this->target).",".
				Database::text($alternative).
			")";
			$this->id = Database::insert($sql);
		}
	}

}