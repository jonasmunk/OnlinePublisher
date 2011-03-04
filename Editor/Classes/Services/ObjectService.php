<?
/**
 * @package OnlinePublisher
 * @subpackage Classes
 */
require_once($basePath.'Editor/Classes/Database.php');
require_once($basePath.'Editor/Classes/Log.php');
require_once($basePath.'Editor/Classes/Model/SearchResult.php');
require_once($basePath.'Editor/Classes/EventManager.php');

class ObjectService {
	
	function getLatestId($type) {
		$sql = "select max(id) as id from object where type=".Database::text($type);
		if ($row = Database::selectFirst($sql)) {
			return intval($row['id']);
		}
		return null;
	}
	
	function getInstance($type) {
		ObjectService::importType($type);
		$class = ucfirst($type);
		return new $class;
	}
	
	function importType($type) {
		global $basePath;
		$class = ucfirst($type);
		if (class_exists($class,false)) {
			return true;
		}
		$path = $basePath.'Editor/Classes/Objects/'.$class.'.php';
		if (!file_exists($path)) {
			$path = $basePath.'Editor/Classes/'.$class.'.php';
			if (!file_exists($path)) {
				return false;
			}
		}
		require_once $path;
		return true;
	}
	
	function remove($object) {
		if ($object->isPersistent() && $object->canRemove()) {
			$sql = "delete from `object` where id=".Database::int($object->getId());
			$row = Database::delete($sql);
			$sql = "delete from `object_link` where object_id=".Database::int($object->getId());
			$row = Database::delete($sql);
			$schema = Object::$schema[$object->getType()];
			if (is_array($schema)) {
				$sql = "delete from `".$object->getType()."` where object_id=".Database::int($object->getId());
				$row = Database::delete($sql);
				if (method_exists($object,'removeMore')) {
					$object->removeMore();
				}
			}
			else if (method_exists($object,'sub_remove')) {
				$object->sub_remove();
			}
			EventManager::fireEvent('delete','object',$object->getType(),$object->getId());
			return true;
		}
		return false;
	}
	
	function publish($object) {
		if (!$object->isPersistent()) {
			return;
		}
		$index = $object->getIndex();
		$xml = $object->getCurrentXml();
		$sql = "update `object` set data=".Database::text($xml).",`index`=".Database::text($index).",published=now() where id=".Database::int($object->getId());
		Database::update($sql);
		EventManager::fireEvent('publish','object',$object->getType(),$object->getId());
	}
	
	function load($id,$type) {
    	global $basePath;
		ObjectService::importType($type);
		$schema = Object::$schema[$type];
		if (is_array($schema)) {
			
			$sql = "select object.id,object.title,object.note,object.type as object_type,object.owner_id,UNIX_TIMESTAMP(object.created) as created,UNIX_TIMESTAMP(object.updated) as updated,UNIX_TIMESTAMP(object.published) as published,object.searchable";
			foreach ($schema as $property => $info) {
				$column = Object::getColumn($property,$info);
				if ($info['type']=='datetime') {
					$sql.=",UNIX_TIMESTAMP(`$type`.`$column`) as `$column`";
				} else {
					$sql.=",`$type`.`$column`";
				}
			}
			$sql.=" from `object`,";
			$sql.="`".$type."` where `".$type."`.object_id=object.id and object.id=".Database::int($id);
		
			if ($row = Database::selectFirst($sql)) {
		    	$unique = ucfirst($row['object_type']);
	    		$obj = new $unique;
				$obj->id = intval($row['id']);
				$obj->title = $row['title'];
				$obj->created = intval($row['created']);
				$obj->updated = intval($row['updated']);
				$obj->published = intval($row['published']);
				$obj->type = $row['object_type'];
				$obj->note = $row['note'];
				$obj->ownerId = intval($row['owner_id']);
				$obj->searchable = ($row['searchable']==1);
				foreach ($schema as $property => $info) {
					$column = Object::getColumn($property,$info);
					if ($info['type']=='int') {
						$obj->$property = intval($row[$column]);
					} else if ($info['type']=='datetime') {
						$obj->$property = $row[$column] ? intval($row[$column]) : null;
					} else if ($info['type']=='boolean') {
						$obj->$property = $row[$column]==1 ? true : false;
					} else {
						$obj->$property = $row[$column];
					}
				}
				return $obj;
	    	} else {
				Log::debug('Not found: '.$id);
			}
		} else {
			Log::debug('No schema for: '.$type);
		}
		return null;
	}
	
	function create($object) {
		if ($object->isPersistent()) {
			Log::debug('Tried creating object already persisted...');
			Log::debug($object);
			return;
		}
		$sql = "insert into `object` (title,type,note,created,updated,searchable,owner_id) values (".
		Database::text($object->title).",".
		Database::text($object->type).",".
		Database::text($object->note).",".
		"now(),now(),".
		Database::boolean($object->searchable).",".
		Database::int($object->ownerId).
		")";		
		$object->id = Database::insert($sql);
		$schema = Object::$schema[$object->type];
		if (is_array($schema)) {
			$sql = "insert into `".$object->type."` (object_id";
			foreach ($schema as $property => $info) {
				$column = Object::getColumn($property,$info);
				$sql.=",`$column`";
			}
			$sql.=") values (".$object->id;
			foreach ($schema as $property => $info) {
				$column = Object::getColumn($property,$info);
				$sql.=",";
				if ($info['type']=='int') {
					$sql.=Database::int($object->$property);
				} else if ($info['type']=='datetime') {
					$sql.=Database::datetime($object->$property);
				} else {
					$sql.=Database::text($object->$property);
				}
			}
			$sql.=")";
			Database::insert($sql);
		}
		else if (method_exists($object,'sub_create')) {
			$object->sub_create();
		}
		EventManager::fireEvent('create','object',$object->type,$object->id);
	}

	
	function update($object) {
		if (!$object->isPersistent()) {
			Log::debug('Tried updating object not persisted...');
			Log::debug($object);
			return;
		}
		$sql = "update `object` set ".
		"title=".Database::text($object->getTitle()).
		",note=".Database::text($object->getNote()).
		",updated=now()".
		",searchable=".Database::boolean($object->searchable).
		",owner_id=".Database::int($object->ownerId).
		" where id=".Database::int($object->id);
		Database::update($sql);
		$schema = Object::$schema[$object->type];
		if (is_array($schema)) {
			$sql = "update `".$object->type."` set object_id=".Database::int($object->id);
			foreach ($schema as $property => $info) {
				$column = Object::getColumn($property,$info);
				$sql.=",`".$column."`=";
				if ($info['type']=='int') {
					$sql.=Database::int($object->$property);
				} else if ($info['type']=='datetime') {
					$sql.=Database::datetime($object->$property);
				} else {
					$sql.=Database::text($object->$property);
				}
			}
			$sql.=" where object_id=".Database::int($object->id);
			Database::update($sql);
		}
		else if (method_exists($object,'sub_update')) {
			$object->sub_update();
		}
		EventManager::fireEvent('update','object',$object->type,$object->id);
	}

	
	/**
	 * @param query Query
	 */
	function search($query) {
		$parts = array( 
			'type' => $query->getType(), 
			'query' => $query->getText(), 
			'fields' => $query->getFields(),
			'ordering' => implode(',',$query->getOrdering()),
			'direction' => $query->getDirection()
		);
		if ($class = ObjectService::getInstance($query->getType())) {
			if (method_exists($class,'addCustomSearch')) {
				$class->addCustomSearch($query,$parts);
			}
		} else {
			Log::debug('Unable to get class for type='.$query->getType());
		}
		$x =  Object::retrieve($parts);
		$result = new SearchResult();
		$result->setList($x['result']);
		$result->setTotal($x['total']);
		$result->setWindowPage($x['windowPage']);
		$result->setWindowSize($x['windowSize']);
		
		return $result;
	}
}