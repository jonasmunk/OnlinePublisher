<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes
 */
if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

Entity::$schema['Object'] = [
	'table' => 'object',
	'properties' => [
		'title' => ['type' => 'string'],
		'created' => ['type' => 'datetime'],
		'updated' => ['type' => 'datetime'],
		'published' => ['type' => 'datetime'],
		'type' => ['type' => 'string'],
		'note' => ['type' => 'string'],
		'searchable' => ['type'=>'boolean'],
		'ownerId' => ['type' => 'int','column' => 'owner_id','relation' => ['class' => 'User','property' => 'id']]
    ]
];
class Object extends Entity {

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
			return $this->update();
		} else {
			return $this->create();
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
	
	/**
	 * Override this to prevent creating/updating invalid data
	 */
	function isValid() {
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
	
		
    /*=========================== links =======================*/

	function updateLinks($links) {
		return ObjectLinkService::updateLinks($this->id,$links);
	}
	
	function getLinks() {
		return ObjectLinkService::search(array('objectId'=>$this->id));
	}


    /*=========================== Interface =======================*/

    function getIcon() {
        return 'common/object';
    }
    
    ///////////////////////////// Static ///////////////////////////

	static function get($id,$type) {
		return ObjectService::load($id,$type);
	}

    static function load($id) {
		return ObjectService::loadAny($id);
    }

}
?>