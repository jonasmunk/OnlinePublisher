<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes
 */
if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}
require_once($basePath.'Editor/Classes/Core/Database.php');
require_once($basePath.'Editor/Classes/Core/InternalSession.php');
require_once($basePath.'Editor/Classes/Core/Log.php');
require_once($basePath.'Editor/Classes/Services/ObjectService.php');
require_once($basePath.'Editor/Classes/Utilities/StringUtils.php');

class Object {
	var $id;
	var $title;
	var $created;
	var $updated;
	var $published;
	var $type;
	var $note;
	var $searchable;
	var $ownerId;
	static $schema = array();
	
	function Object($type) {
		$this->type = $type;
		$this->searchable = true;
		$this->ownerId = InternalSession::getUserId();
	}
	
	function getId() {
		return $this->id;
	}
	
	function getType() {
		return $this->type;
	}
	
	function setTitle($title) {
		$this->title = $title;
	}
	
	function getTitle() {
		return (string) $this->title;
	}
	
	function setNote($note) {
		$this->note = $note;
	}
	
	function getNote() {
		return $this->note;
	}
	
	function isSearchable() {
		return $this->searchable;
	}
	
	function setSearchable($searchable) {
		$this->searchable = ($searchable==true);
	}
	
	function isPublished() {
		return ($this->updated<=$this->published);
	}
	
	function getUpdated() {
		return $this->updated;
	}
	
	function getCreated() {
		return $this->created;
	}
	
	function setOwnerId($ownerId) {
	    $this->ownerId = $ownerId;
	}

	function getOwnerId() {
	    return $this->ownerId;
	}
	
	function save() {
		if ($this->id > 0) {
			$this->update();
		} else {
			$this->create();
		}
	}
	
	function isPersistent() {
		return $this->id>0;
	}
	
	function create() {
		return ObjectService::create($this);
	}
	
	function update() {
		return ObjectService::update($this);
	}
	
	/**
	 * Override this to prevent removal
	 */
	function canRemove() {
		return true;
	}
	
	function remove() {
		return ObjectService::remove($this);
	}
	
	function publish() {
		ObjectService::publish($this);
	}
	
	function getIndex() {
		$index = '';
		$index.=$this->title.' ';
		$index.=$this->note.' ';
		if (method_exists($this,'sub_index')) {
			$index.=$this->sub_index();
		}
		return $index;
	}
	
	function getCurrentXml() {
		return ObjectService::toXml($this);
	}
	
	function _buildnamespace($version) {
		return 'http://uri.in2isoft.com/onlinepublisher/class/'.$this->type.'/'.$version.'/';
	}
	
	// TODO: Deprecated
	function _load($id) {
		Log::debug('Object::_load() is deprecated for ...');
		Log::debug($this);
		$sql = "select id,title,note,type,owner_id,UNIX_TIMESTAMP(created) as created,UNIX_TIMESTAMP(updated) as updated,UNIX_TIMESTAMP(published) as published,searchable from `object` where id=".$id;
		$row = Database::selectFirst($sql);
		if ($row) {
			$this->id=$row['id'];
			$this->title=$row['title'];
			$this->created=$row['created'];
			$this->updated=$row['updated'];
			$this->published=$row['published'];
			$this->type=$row['type'];
			$this->note=$row['note'];
			$this->ownerId=$row['owner_id'];
			$this->searchable=($row['searchable']==1);
			return true;
		} else {
			return false;
		}
	}
	
	function get($id,$type) {
		return ObjectService::load($id,$type);
	}
		
    /*=========================== links =======================*/

	function updateLinks($links) {
		global $basePath;
		require_once($basePath.'Editor/Classes/Services/ObjectLinkService.php');
		return ObjectLinkService::updateLinks($this->id,$links);
	}
	
	function getLinks() {
		global $basePath;
		require_once($basePath.'Editor/Classes/Services/ObjectLinkService.php');
		return ObjectLinkService::search(array('objectId'=>$this->id));
	}


    /*=========================== Interface =======================*/

    function getIcon() {
        return 'Element/Generic';
    }

    function getIn2iGuiIcon() {
        return 'common/object';
    }
    
    ///////////////////////////// Static ///////////////////////////
    
	function getValidIds($ids) {
		if (count($ids)==0) {
			return array();
		}
		$sql = "select id from object where id in (".implode(',',$ids).")";
		return Database::getIds($sql);
	}

    function load($id) {
		return ObjectService::loadAny($id);
    }
    
    function getObjectData($id) {
    	$data = null;
		if ($id) {
	    	$sql = "select data from object where id =".Database::int($id);
	    	if ($row = Database::selectFirst($sql)) {
	    		$data = $row['data'];
	    	}
		}
    	return $data;
    }

}
?>