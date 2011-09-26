<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Part
 */
if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}
require_once($basePath.'Editor/Classes/Core/Database.php');
require_once($basePath.'Editor/Classes/Services/SchemaService.php');

class Part
{
	static $schema = array();
	protected $id;
	protected $type;
	protected $dynamic;
	
	function Part($type) {
		$this->type = $type;
	}
	
	function setId($id) {
	    $this->id = $id;
	}

	function getId() {
	    return $this->id;
	}

	function getType() {
	    return $this->type;
	}
	
	function save() {
		if ($this->isPersistent()) {
			$this->update();
		} else {
			$schema = Part::$schema[$this->type];
			
			$sql = "insert into part (type,dynamic,created,updated) values (".
			Database::text($this->type).",".
			Database::boolean($this->isDynamic()).",".
			"now(),now()".
			")";
			$this->id = Database::insert($sql);
			$sql = "insert into part_".$this->type." (part_id";
			$columns = SchemaService::buildSqlColumns($schema);
			if (strlen($columns)>0) {
				$sql.=",".$columns;
			}
			$sql.=") values (".$this->id;
			$values = SchemaService::buildSqlValues($this,$schema);
			if (strlen($values)>0) {
				$sql.=",".$values;
			}
			$sql.=")";
			Database::insert($sql);
			if (is_array($schema['relations'])) {
				foreach ($schema['relations'] as $field => $info) {
					$getter = 'get'.ucfirst($field);
					$ids = $this->$getter();
					if ($ids!==null) {
						foreach ($ids as $id) {
							$sql = "insert into ".$info['table']." (".$info['fromColumn'].",".$info['toColumn'].") values (".$this->id.",".$id.")";
							Database::insert($sql);
						}
					}
				}
			}
		}
	}
	
	function update() {
		$sql = "update part set updated=now(),dynamic=".Database::boolean($this->isDynamic())." where id=".$this->id;
		Database::update($sql);
		
		$sql = "update part_".$this->type." set ";
		
		$schema = Part::$schema[$this->type];
		$sql.=SchemaService::buildSqlSetters($this,$schema);
		
		$sql.=" where part_id=".$this->id;
		Database::update($sql);
		
		// Update relations
		if (is_array($schema['relations'])) {
			foreach ($schema['relations'] as $field => $info) {
				$sql = "delete from ".$info['table']." where ".$info['fromColumn']."=".$this->id;
				Database::delete($sql);
				$getter = 'get'.ucfirst($field);
				$ids = $this->$getter();
				if ($ids!==null) {
					foreach ($ids as $id) {
						$sql = "insert into ".$info['table']." (".$info['fromColumn'].",".$info['toColumn'].") values (".$this->id.",".$id.")";
						Database::insert($sql);
					}
				}
			}
		}
	}
	
	function isDynamic() {
		$ctrl = PartService::getController($this->type);
		if ($ctrl) {
			return $ctrl->isDynamic($this);
		}
		return false;
	}
	
	function remove() {
		$sql = "delete from part where id=".Database::int($this->id);
		Database::delete($sql);

		$sql = "delete from part_".$this->type." where part_id=".Database::int($this->id);
		Database::delete($sql);

		$sql = "delete from link where part_id=".Database::int($this->id);
		Database::delete($sql);
		
		// Delete relations
		$schema = Part::$schema[$this->type];
		if (is_array($schema['relations'])) {
			foreach ($schema['relations'] as $field => $info) {
				$sql = "delete from ".$info['table']." where ".$info['fromColumn']."=".Database::int($this->id);
				Database::delete($sql);
			}
		}
	}
	
	function isPersistent() {
		return $this->id!=null;
	}
	
	function load($type,$id) {
		if (!$id) {
			return null;
		}
		global $basePath;
		$class = ucfirst($type).'Part';
		if (!file_exists($basePath.'Editor/Classes/Parts/'.$class.'.php')) {
			return null;
		}
		require_once $basePath.'Editor/Classes/Parts/'.$class.'.php';
		$sql = "select part.id";
		$schema = Part::$schema[$type];
		if (!$schema) {
			Log::debug('No schema for '.$type);
			return null;
		}
		foreach ($schema['fields'] as $field => $info) {
			$column = $info['column'] ? $info['column'] : $field;
			if ($info['type']=='datetime') {
				$sql.=",UNIX_TIMESTAMP(`part_$type`.`$column`) as `$column`";
			} else {
				$sql.=",`part_$type`.`$column`";
			}
		}
		$sql.=" from part,part_".$type." where part.id=part_".$type.".part_id and part.id=".$id;
		if ($row = Database::selectFirst($sql)) {
			$part = new $class();
			$part->setId($row['id']);
			foreach ($schema['fields'] as $field => $info) {
				$setter = 'set'.ucfirst($field);
				$column = $info['column'] ? $info['column'] : $field;
				$part->$setter($row[$column]);
			}
			
			if (is_array($schema['relations'])) {
				foreach ($schema['relations'] as $field => $info) {
					$setter = 'set'.ucfirst($field);
					$sql = "select `".$info['toColumn']."` as id from `".$info['table']."` where `".$info['fromColumn']."`=".Database::int($id);
					$ids = Database::getIds($sql);
					$part->$setter($ids);
				}
			}
			
			return $part;
		}
		return null;
	}
}

?>