<?
/**
 * @package OnlinePublisher
 * @subpackage Classes
 */
require_once($basePath.'Editor/Classes/Database.php');
require_once($basePath.'Editor/Classes/Log.php');
require_once($basePath.'Editor/Classes/Model/SearchResult.php');
require_once($basePath.'Editor/Classes/EventManager.php');
require_once($basePath.'Editor/Classes/Services/SchemaService.php');

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
				$column = SchemaService::getColumn($property,$info);
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
					$column = SchemaService::getColumn($property,$info);
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
				$column = SchemaService::getColumn($property,$info);
				$sql.=",`$column`";
			}
			$sql.=") values (".$object->id;
			foreach ($schema as $property => $info) {
				$column = SchemaService::getColumn($property,$info);
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
				$column = SchemaService::getColumn($property,$info);
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
	
	function toXml($object) {
		$ns = 'http://uri.in2isoft.com/onlinepublisher/class/object/1.0/';
		$xml = '<object xmlns="'.$ns.'" id="'.$object->id.'" type="'.$object->type.'">'.
		'<title>'.StringUtils::escapeXML($object->title).'</title>'.
		'<note>'.StringUtils::escapeXMLBreak($object->note,'<break/>').'</note>'.
		DateUtils::buildTag('created',$object->created).
		DateUtils::buildTag('updated',$object->updated).
		DateUtils::buildTag('published',$object->published);
		$links='';
		$sql = "select object_link.*,page.path from object_link left join page on page.id=object_link.target_value and object_link.target_type='page' where object_id=".$object->id." order by position";
		$result = Database::select($sql);
		while ($row = Database::next($result)) {
			$links.='<link title="'.StringUtils::escapeXML($row['title']).'"';
			if ($row['alternative']!='') {
				$links.=' alternative="'.StringUtils::escapeXML($row['alternative']).'"';
			}
			if ($row['target']!='') {
				$links.=' target="'.StringUtils::escapeXML($row['target']).'"';
			}
			if ($row['path']!='') {
				$links.=' path="'.StringUtils::escapeXML($row['path']).'"';
			}
			if ($row['target_type']=='page') {
				$links.=' page="'.StringUtils::escapeXML($row['target_value']).'"';
			}
			elseif ($row['target_type']=='file') {
				$links.=' file="'.StringUtils::escapeXML($row['target_value']).'" filename="'.StringUtils::escapeXML(ObjectService::_getFilename($row['target_value'])).'"';
			}
			elseif ($row['target_type']=='url') {
				$links.=' url="'.StringUtils::escapeXML($row['target_value']).'"';
			}
			elseif ($row['target_type']=='email') {
				$links.=' email="'.StringUtils::escapeXML($row['target_value']).'"';
			}
			$links.='/>';
		}
		Database::free($result);
		if ($links!='') {
			$xml.='<links>'.$links.'</links>';
		}
		$xml.='<sub>';
		if (method_exists($object,'sub_publish')) {
			$xml.=$object->sub_publish();
		}
		$xml.='</sub>'.
		'</object>';
		return $xml;
	}

	function _getFilename($id) {
		$sql = "select filename from file where object_id=".Database::int($id);
		if ($row = Database::selectFirst($sql)) {
			return $row['filename'];
		}
		return null;
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
		if ($query->getWindowPage()!==null) {
			$parts['windowPage'] = $query->getWindowPage();
		}
		if ($query->getWindowSize()!==null) {
			$parts['windowSize'] = $query->getWindowSize();
		}
		if ($query->getCreatedMin()!==null) {
			$parts['createdMin'] = $query->getCreatedMin();
		}
		if ($class = ObjectService::getInstance($query->getType())) {
			if (method_exists($class,'addCustomSearch')) {
				$class->addCustomSearch($query,$parts);
			}
		} else {
			Log::debug('Unable to get class for type='.$query->getType());
		}
		$x =  ObjectService::find($parts);
		$result = new SearchResult();
		$result->setList($x['result']);
		$result->setTotal($x['total']);
		$result->setWindowPage($x['windowPage']);
		$result->setWindowSize($x['windowSize']);
		
		return $result;
	}

	function find($query = array()) {
    	global $basePath;
		$type = $query['type'];
		$schema = Object::$schema[$type];
		if (!is_array($schema)) {
			Log::debug('Unable to find schema for: '.$type);
		}
    	$parts = array(
			// It is important to name type "object_type" since the image class also has a column named type
			'columns' => 'object.id,object.title,object.note,object.type as object_type,object.owner_id,UNIX_TIMESTAMP(object.created) as created,UNIX_TIMESTAMP(object.updated) as updated,UNIX_TIMESTAMP(object.published) as published,object.searchable',
			'tables' => 'object,`'.$type.'`',
			'ordering' => 'object.title',
			'limits' => array(
				'`'.$type.'`.object_id=object.id'
			),
			'joins' => array()
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
		if (is_array($query['joins'])) {
			$parts['joins'] = array_merge($parts['joins'],$query['joins']);
		}
		if (is_array($query['fields'])) {
			foreach ($query['fields'] as $field => $value) {
				if (isset($schema[$field]) && isset($schema[$field]['column'])) {
					$field = $schema[$field]['column'];
				}
				$parts['limits'][] = '`'.$type.'`.`'.$field.'`='.Database::text($value);
			}
		}
		if (isset($query['tables']) && is_array($query['tables']) && count($query['tables'])>0) {
			$parts['tables'].=','.implode(',',$query['tables']);
		}
		if (isset($query['query'])) {
			$words = preg_split("/[\s,]+/", $query['query']);
			foreach ($words as $word) {
				if ($word!='') {
					$parts['limits'][] = '`index` like '.Database::search($word);
				}
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
		
		ObjectService::importType($type);
		$class = ucfirst($type);
		foreach ($list['rows'] as $row) {
    		$obj = new $class;
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
			$list['result'][] = $obj;
    	}
		return $list;
	}
}