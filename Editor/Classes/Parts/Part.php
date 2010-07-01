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
		$this->update();
	}
	
	function update() {
		$sql = "update part set updated=now(),dynamic=".Database::boolean($this->isDynamic())." where id=".$this->id;
		Database::update($sql);
		
		$sql = "update part_".$this->type." set ";
		
		$schema = Part::$schema[$this->type];
		$sql.=SchemaService::buildSqlSetters($this,$schema);
		
		$sql.=" where part_id=".$this->id;
		Log::debug($sql);
		Database::update($sql);
	}
	
	function isDynamic() {
		return false;
	}
	
	function load($type,$id) {
		global $basePath;
		$sql = "select part.id";
		$schema = Part::$schema[$type];
		foreach ($schema['fields'] as $field => $info) {
			$sql.=",part_".$type.".".$field;
		}
		$sql.=" from part,part_".$type." where part.id=part_".$type.".part_id and part.id=".$id;
		Log::debug($sql);
		$row = Database::selectFirst($sql);
		Log::debug($row);
		$class = ucfirst($type);
		require_once $basePath.'Editor/Classes/Parts/'.$class.'.php';
		$part = new $class();
		$part->setId($row['id']);
		foreach ($schema['fields'] as $field => $info) {
			$setter = 'set'.ucfirst($field);
			$part->$setter($row[$field]);
		}
		return $part;
	}
}

?>