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
require_once($basePath.'Editor/Classes/EventManager.php');
require_once($basePath.'Editor/Classes/InternalSession.php');
require_once($basePath.'Editor/Classes/Log.php');
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
		if ($this->id>0) {
			$this->update();
		} else {
			$this->create();
		}
	}
	
	function isPersistent() {
		return $this->id>0;
	}
	
	function toUnicode() {
		foreach ($this as $key => $value) {
			if (is_string($value)) {
				$this->$key = StringUtils::toUnicode($value);
			}
		}
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
	
	// TODO: Deprecated
	function getColumn($property,$info) {
		if ($info['column']) {
			return $info['column'];
		}
		return $property;
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
    	global $basePath;
    	$object = false;
    	$sql = "select type from object where id =".Database::int($id);
    	if ($row = Database::selectFirst($sql)) {
    		$unique = ucfirst($row['type']);
			if (!$unique) {
				Log::debug('Unable to load object by id: '.$id);
				return false;
			}
			if (file_exists($basePath.'Editor/Classes/'.$unique.'.php')) {
    			require_once($basePath.'Editor/Classes/'.$unique.'.php');
			} else {
				require_once($basePath.'Editor/Classes/Objects/'.$unique.'.php');
			}
    		$class = new $unique;
    		$object = $class->load($id);
    	}
    	return $object;
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

	function _find($parts,$query) {
		$list = array('result' => array(),'rows' => array(),'windowPage' => 0,'windowSize' => 0,'total' => 0);

		$sql = "select ".$parts['columns']." from ".$parts['tables'];
		if (is_array($parts['joins']) && count($parts['joins'])>0) {
			$sql.=" ".implode(' ',$parts['joins']);
		}
		if (is_array($parts['limits']) && count($parts['limits'])>0) {
			$sql.=" where ".implode(' and ',$parts['limits']);
		}
		if (is_string($parts['limits']) && strlen($parts['limits'])>0) {
			$sql.=" where ".$parts['limits'];
		}
		if (strlen($parts['ordering'])) {
			$sql.=" order by ".$parts['ordering'];
			if ($parts['direction']=='descending') {
				$sql.=' desc';
			} else if ($parts['direction']=='ascending') {
				$sql.=' asc';
			}
		}
		$start=0;
		$end=1000;
		if (isset($query['windowSize']) && isset($query['windowPage'])) {
			$start = ($query['windowPage'])*$query['windowSize'];
			$end = ($query['windowPage']+1)*$query['windowSize'];
			$list['windowPage'] = $query['windowPage'];
			$list['windowSize'] = $query['windowSize'];
		}
		$num = 1;
		$size = 0;
		$result = Database::select($sql);
		$list['total'] = Database::size($result);
    	while ($row = Database::next($result)) {
			if ($num>=$start && $num<$end) {
				$list['rows'][] = $row;
				$size++;
			}
			$num++;
    	}
		Database::free($result);
		return $list;
	}

    function find($query = array()) {
    	global $basePath;
    	$parts = array();
		$parts['columns'] = 'object.id,object.type';
		$parts['tables'] = 'object';
		$parts['limits'] = array();
		$parts['ordering'] = 'object.title';
		$parts['direction'] = $query['direction'];
    	
		if ($query['sort']=='title') {
			$parts['ordering']="object.title";
		} else if ($query['sort']=='type') {
			$parts['ordering']="object.type";
		} else if ($query['sort']=='updated') {
			$parts['ordering']="object.updated";
		}
		if (isset($query['type'])) {
			$parts['limits'][]='object.type='.Database::text($query['type']);
		}
		if (isset($query['query'])) {
			$parts['limits'][]='`index` like '.Database::search($query['query']);
		}
		$list = Object::_find($parts,$query);
		$list['result'] = array();
		foreach ($list['rows'] as $row) {
			if ($row['type']=='') {
				error_log('Could not load '.$row['id'].' it has no type');
				continue;
			}
	    	$className = ucfirst($row['type']);
			ObjectService::importType($row['type']);
    		$class = new $className;
    		$object = $class->load($row['id']);
			if ($object) {
				$list['result'][] = $object;
			} else {
				error_log('Could not load '.$row['id']);
			}
		}
		return $list;
	}
}
?>