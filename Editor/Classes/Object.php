<?
/**
 * @package OnlinePublisher
 * @subpackage Classes
 */
require_once($basePath.'Editor/Classes/Database.php');
require_once($basePath.'Editor/Classes/EventManager.php');
require_once($basePath.'Editor/Classes/InternalSession.php');
require_once($basePath.'Editor/Classes/Log.php');

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
		$this->title = mb_convert_encoding($this->title, "UTF-8","ISO-8859-1");
		$this->note = mb_convert_encoding($this->note, "UTF-8","ISO-8859-1");
	}
	
	
	function create() {
		$sql = "insert into `object` (title,type,note,created,updated,searchable,owner_id) values (".
		Database::text($this->title).",".
		Database::text($this->type).",".
		Database::text($this->note).",".
		"now(),now(),".
		Database::boolean($this->searchable).",".
		Database::int($this->ownerId).
		")";		
		$this->id = Database::insert($sql);
		$schema = Object::$schema[$this->type];
		if (is_array($schema)) {
			$sql = "insert into `".$this->type."` (object_id";
			foreach ($schema as $property => $info) {
				$column = Object::getColumn($property,$info);
				$sql.=",`$column`";
			}
			$sql.=") values (".$this->id;
			foreach ($schema as $property => $info) {
				$column = Object::getColumn($property,$info);
				$sql.=",";
				if ($info['type']=='int') {
					$sql.=Database::int($this->$property);
				} else if ($info['type']=='datetime') {
					$sql.=Database::datetime($this->$property);
				} else {
					$sql.=Database::text($this->$property);
				}
			}
			$sql.=")";
			Database::insert($sql);
		}
		else if (method_exists($this,'sub_create')) {
			$this->sub_create();
		}
		EventManager::fireEvent('create','object',$this->type,$this->id);
	}
	
	function update() {
		$sql = "update `object` set ".
		"title=".Database::text($this->title).
		",note=".Database::text($this->note).
		",updated=now()".
		",searchable=".Database::boolean($this->searchable).
		",owner_id=".Database::int($this->ownerId).
		" where id=".$this->id;		
		Database::update($sql);
		$schema = Object::$schema[$this->type];
		if (is_array($schema)) {
			$sql = "update `".$this->type."` set object_id=".$this->id;
			foreach ($schema as $property => $info) {
				$column = Object::getColumn($property,$info);
				$sql.=",`".$column."`=";
				if ($info['type']=='int') {
					$sql.=Database::int($this->$property);
				} else if ($info['type']=='datetime') {
					$sql.=Database::datetime($this->$property);
				} else {
					$sql.=Database::text($this->$property);
				}
			}
			$sql.=" where object_id=".$this->id;
			Database::update($sql);
		}
		else if (method_exists($this,'sub_update')) {
			$this->sub_update();
		}
		EventManager::fireEvent('update','object',$this->type,$this->id);
	}
	
	/**
	 * Override this to prevent removal
	 */
	function canRemove() {
		return true;
	}
	
	function remove() {
		if ($this->canRemove()) {
			$sql = "delete from `object` where id=".$this->id;
			$row = Database::delete($sql);
			$sql = "delete from `object_link` where object_id=".$this->id;
			$row = Database::delete($sql);
			$schema = Object::$schema[$this->type];
			if (is_array($schema)) {
				$sql = "delete from `".$this->type."` where object_id=".$this->id;
				$row = Database::delete($sql);
				if (method_exists($this,'removeMore')) {
					$this->removeMore();
				}
			}
			else if (method_exists($this,'sub_remove')) {
				$this->sub_remove();
			}
			EventManager::fireEvent('delete','object',$this->type,$this->id);
		}
	}
	
	function publish() {
		$index = $this->getIndex();
		$xml = $this->getCurrentXml();
		$sql = "update `object` set ".
		"data=".Database::text($xml).
		",`index`=".Database::text($index).
		",published=now()".
		" where id=".$this->id;		
		Database::update($sql);
		EventManager::fireEvent('publish','object',$this->type,$this->id);
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
		$ns = 'http://uri.in2isoft.com/onlinepublisher/class/object/1.0/';
		$xml = '<object xmlns="'.$ns.'" id="'.$this->id.'" type="'.$this->type.'">'.
		'<title>'.encodeXML($this->title).'</title>'.
		'<note>'.encodeXMLBreak($this->note,'<break/>').'</note>'.
		$this->_builddate('created',$this->created).
		$this->_builddate('updated',$this->updated).
		$this->_builddate('published',$this->published);
		$links='';
		$sql = "select object_link.*,page.path from object_link left join page on page.id=object_link.target_value and object_link.target_type='page' where object_id=".$this->id." order by position";
		$result = Database::select($sql);
		while ($row = Database::next($result)) {
			$links.='<link title="'.encodeXML($row['title']).'"';
			if ($row['alternative']!='') {
				$links.=' alternative="'.encodeXML($row['alternative']).'"';
			}
			if ($row['target']!='') {
				$links.=' target="'.encodeXML($row['target']).'"';
			}
			if ($row['path']!='') {
				$links.=' path="'.encodeXML($row['path']).'"';
			}
			if ($row['target_type']=='page') {
				$links.=' page="'.encodeXML($row['target_value']).'"';
			}
			elseif ($row['target_type']=='file') {
				$links.=' file="'.encodeXML($row['target_value']).'" filename="'.encodeXML($this->_getFilename($row['target_value'])).'"';
			}
			elseif ($row['target_type']=='url') {
				$links.=' url="'.encodeXML($row['target_value']).'"';
			}
			elseif ($row['target_type']=='email') {
				$links.=' email="'.encodeXML($row['target_value']).'"';
			}
			$links.='/>';
		}
		Database::free($result);
		if ($links!='') {
			$xml.='<links>'.$links.'</links>';
		}
		$xml.='<sub>';
		if (method_exists($this,'sub_publish')) {
			$xml.=$this->sub_publish();
		}
		$xml.='</sub>'.
		'</object>';
		return $xml;
	}

	function _getFilename($id) {
		$output=NULL;
		$sql = "select filename from file where object_id=".$id;
		if ($row = Database::selectFirst($sql)) {
			$output=$row['filename'];
		}
		return $output;
	}

	function _builddate($tag,$stamp) {
		return '<'.$tag.' unix="'.$stamp.'" day="'.date('d',$stamp).'" weekday="'.date('w',$stamp).'" yearday="'.date('z',$stamp).'" month="'.date('m',$stamp).'" year="'.date('Y',$stamp).'" hour="'.date('H',$stamp).'" minute="'.date('i',$stamp).'" second="'.date('s',$stamp).'" offset="'.date('Z',$stamp).'" timezone="'.date('T',$stamp).'"/>';
	}
	
	function _buildnamespace($version) {
		return 'http://uri.in2isoft.com/onlinepublisher/class/'.$this->type.'/'.$version.'/';
	}
	
	function _load($id) {
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
    	global $basePath;
		$schema = Object::$schema[$type];
		if (is_array($schema)) {
			
			$sql = "select object.id,object.title,object.note,object.type,object.owner_id,UNIX_TIMESTAMP(object.created) as created,UNIX_TIMESTAMP(object.updated) as updated,UNIX_TIMESTAMP(object.published) as published,object.searchable";
			foreach ($schema as $property => $info) {
				$column = Object::getColumn($property,$info);
				if ($info['type']=='datetime') {
					$sql.=",UNIX_TIMESTAMP(`$type`.`$column`) as `$column`";
				} else {
					$sql.=",`$type`.`$column`";
				}
			}
			$sql.=" from `object`,";
			$sql.="`".$type."` where `".$type."`.object_id=object.id and object.id=".$id;
		
			if ($row = Database::selectFirst($sql)) {
		    	$unique = ucfirst($row['type']);
	    		require_once($basePath.'Editor/Classes/'.$unique.'.php');
	    		$obj = new $unique;
				$obj->id = $row['id'];
				$obj->title = $row['title'];
				$obj->created = $row['created'];
				$obj->updated = $row['updated'];
				$obj->published = $row['published'];
				$obj->type = $row['type'];
				$obj->note = $row['note'];
				$obj->ownerId = $row['owner_id'];
				$obj->searchable = ($row['searchable']==1);
				foreach ($schema as $property => $info) {
					$obj->$property = $row[Object::getColumn($property,$info)];
				}
				return $obj;
	    	}
		}
		return null;
	}
	
	function getColumn($property,$info) {
		if ($info['column']) {
			return $info['column'];
		}
		return $property;
	}
	
    /*=========================== links =======================*/
	
	function addLink($title,$alternative,$target,$targetType,$targetValue) {
		$sql="select max(`position`) as `position` from object_link where object_id=".$this->id;
		if ($row = Database::selectFirst($sql)) {
			$pos=$row['position']+1;
		} else {
			$pos=1;
		}
		
		$sql="insert into object_link (object_id,title,alternative,target,position,target_type,target_value) values (".
		$this->id.
		",".Database::text($title).
		",".Database::text($alternative).
		",".Database::text($target).
		",".$pos.
		",".Database::text($targetType).
		",".Database::text($targetValue).
		")";
		Database::insert($sql);
		
		$sql = "update `object` set updated=now() where id=".$this->id;		
		Database::update($sql);
	}
	
	function updateLink($id,$title,$alternative,$target,$targetType,$targetValue) {
		
		$sql="update object_link set title=".Database::text($title).
		",alternative=".Database::text($alternative).
		",target_type=".Database::text($targetType).
		",target=".Database::text($target).
		",target_value=".Database::text($targetValue).
		" where id = ".$id;
		Database::update($sql);
		
		$sql = "update `object` set updated=now() where id=".$this->id;		
		Database::update($sql);
		
	}
	
	function moveLink($id,$dir) {

		$sql="select * from object_link where id=".$id;
		$row = Database::selectFirst($sql);
		$pos=$row['position'];

		$sql="select id from object_link where object_id=".$this->id." and `position`=".($pos+$dir);
		$result = Database::select($sql);
		if ($row = Database::next($result)) {
			$otherid=$row['id'];

			$sql="update object_link set `position`=".($pos+$dir)." where id=".$id;
			Database::update($sql);

			$sql="update object_link set `position`=".$pos." where id=".$otherid;
			Database::update($sql);
		}
		Database::free($result);
		
		$sql = "update `object` set updated=now() where id=".$this->id;		
		Database::update($sql);
	}
	
	function deleteLink($id) {

		// Delete item
		$sql="delete from object_link where id=".$id;
		Database::delete($sql);

		// Fix positions
		$sql="select id from object_link where object_id=".$this->id." order by position";
		$result = Database::select($sql);
		$pos=1;
		while ($row = Database::next($result)) {
			$sql="update object_link set position=".$pos." where id=".$row['id'];
			Database::update($sql);
			$pos++;
		}
		Database::free($result);
		
		$sql = "update `object` set updated=now() where id=".$this->id;		
		Database::update($sql);
	}
	
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
    	$sql = "select type from object where id =".$id;
    	if ($row = Database::selectFirst($sql)) {
    		$unique = ucfirst($row['type']);
    		require_once($basePath.'Editor/Classes/'.$unique.'.php');
    		$class = new $unique;
    		$object = $class->load($id);
    	}
    	return $object;
    }

	function loadAllByType($type) {
    	global $basePath;
		$objects = array();
    	$sql = "select id from object where type =".Database::text($type)." order by title";
		$result = Database::select($sql);
    	while ($row = Database::next($result)) {
			$unique = ucfirst($type);
    		require_once($basePath.'Editor/Classes/'.$unique.'.php');
    		$class = new $unique;
    		if ($object = $class->load($row['id'])) {
				$objects[] = $object;
			}
    	}
		Database::free($result);
		return $objects;
	}
    
    function getObjectData($id) {
    	$data = null;
    	$sql = "select data from object where id =".$id;
    	if ($row = Database::selectFirst($sql)) {
    		$data = $row['data'];
    	}
    	return $data;
    }

	function _find($parts,$query) {
		$list = array('result' => array(),'rows' => array(),'windowPage' => 0,'windowSize' => 0,'total' => 0);

		$sql = "select ".$parts['columns']." from ".$parts['tables'];
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
		Log::debug($parts);
		Log::debug($sql);
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
    		require_once($basePath.'Editor/Classes/'.$className.'.php');
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

	/**
	 * @deprecated
	 */
    function search($query = array()) {
    	global $basePath;
		$list = array();
    	$sql = "select id,type from object";
		$where = array();
		if (isset($query['type'])) {
			$where[] = "type=".Database::text($query['type']);
		}
		if (isset($query['query'])) {
			$where[] = "`index` like ".sqlSearch($query['query']);
		}
		if (count($where)>0) {
			$sql.=' where '.implode(' and ',$where);
		}
		$sql.= " order by title";
		if (isset($query['windowSize']) && isset($query['windowNumber'])) {
			
		}
		$result = Database::select($sql);
    	while ($row = Database::next($result)) {
			if ($row['type']!='') {
	    		$unique = ucfirst($row['type']);
	    		require_once($basePath.'Editor/Classes/'.$unique.'.php');
	    		$class = new $unique;
	    		$object = $class->load($row['id']);
				$list[] = $object;
			}
    	}
		Database::free($result);
    	return $list;
    }

	function retrieve($query = array()) {
    	global $basePath;
		$type = $query['type'];
		$schema = Object::$schema[$type];
		
    	$parts = array(
			'columns' => 'object.id,object.title,object.note,object.type,object.owner_id,UNIX_TIMESTAMP(object.created) as created,UNIX_TIMESTAMP(object.updated) as updated,UNIX_TIMESTAMP(object.published) as published,object.searchable',
			'tables' => 'object,`'.$type.'`',
			'ordering' => 'object.title',
			'limits' => array(
				'`'.$type.'`.object_id=object.id'
			)
		);
		if (isset($query['ordering'])) {
			$parts['ordering'] = $query['ordering'];
		}
		if (isset($query['direction'])) {
			$parts['direction'] = $query['direction'];
		}
		if (is_array($query['limits'])) {
			$parts['limits'] = array_merge($parts['limits'],$query['limits']);
		}
		if (isset($query['tables']) && is_array($query['tables']) && count($query['tables'])>0) {
			$parts['tables'].=','.implode(',',$query['tables']);
		}
		if (isset($query['query'])) {
			$words = preg_split("/[\s,]+/", $query['query']);
			foreach ($words as $word) {
				$parts['limits'][] = '`index` like '.Database::search($word);
			}
		}
		if (isset($query['createdMin'])) {
			$parts['limits'][]='`object`.`created` > '.Database::datetime($query['createdMin']);
		}
		foreach ($schema as $property => $info) {
			$column = $property;
			if ($info['column']) {
				$column = $info['column'];
			}
			if ($info['type']=='datetime') {
				$parts['columns'].=",UNIX_TIMESTAMP(`$type`.`$column`) as `$column`";
			} else {
				$parts['columns'].=",`$type`.`$column`";
			}
		}
		$list = Object::_find($parts,$query);
		
		foreach ($list['rows'] as $row) {
	    	$unique = ucfirst($row['type']);
    		require_once($basePath.'Editor/Classes/'.$unique.'.php');
    		$obj = new $unique;
			$obj->id = $row['id'];
			$obj->title = $row['title'];
			$obj->created = $row['created'];
			$obj->updated = $row['updated'];
			$obj->published = $row['published'];
			$obj->type = $row['type'];
			$obj->note = $row['note'];
			$obj->ownerId = $row['owner_id'];
			$obj->searchable = ($row['searchable']==1);
			foreach ($schema as $property => $info) {
				$column = Object::getColumn($property,$info);
				if ($info['type']=='datetime') {
					$obj->$property = $row[$column] ? intval($row[$column]) : null;
				} else {
					$obj->$property = $row[$column];
				}
			}
			$list['result'][] = $obj;
    	}
		return $list;
	}
}
?>