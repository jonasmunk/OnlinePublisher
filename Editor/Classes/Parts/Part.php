<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Part
 */
require_once($basePath.'Editor/Classes/Database.php');
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
			$sql = "insert into part (type,dynamic,created,updated) values (".
			Database::text($this->type).",".
			Database::boolean($this->dynamic).",".
			"now(),now()".
			")";
			$this->id = Database::insert($sql);
			$sql = "insert into part_".$this->type." (part_id";
			$columns = SchemaService::buildSqlColumns(Part::$schema[$this->type]);
			if (strlen($columns)>0) {
				$sql.=",".$columns;
			}
			$sql.=") values (".$this->id;
			$values = SchemaService::buildSqlValues($this,Part::$schema[$this->type]);
			if (strlen($values)>0) {
				$sql.=",".$values;
			}
			$sql.=")";
			Database::insert($sql);
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
	}
	
	function isDynamic() {
		return false;
	}
	
	function remove() {
		$sql = "delete from part where id=".$this->id;
		Database::delete($sql);
		$sql = "delete from part_".$this->type." where part_id=".$this->id;
		Database::delete($sql);
	}
	
	function isPersistent() {
		return $this->id!=null;
	}
	
	function load($type,$id) {
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
			$sql.=",part_".$type.".".$column;
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
			return $part;
		}
		return null;
	}
}

?>