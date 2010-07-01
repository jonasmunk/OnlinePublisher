<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes
 */
require_once($basePath.'Editor/Classes/Object.php');

class PersonRole extends Object {
	
	var $personId;
	
	function setPersonId($personId) {
		$this->personId = $personId;
	}
	
	function getPersonId() {
		return $this->personId;
	}
	
	function PersonRole() {
		parent::Object('personrole');
	}
	
	function load($id) {
		$obj = new PersonRole();
		$obj->_load($id);
		$sql = "select * from personrole where object_id=".$id;
		$row = Database::selectFirst($sql);
		if ($row) {
			$obj->setPersonId($row['person_id']);
		}
		return $obj;
	}
	
	function sub_create() {
		$sql = "insert into personrole (object_id,person_id) values (".$this->id.",".($this->personId > 0 ? $this->personId : 0 ).")";
		Database::insert($sql);
	}
	
	function sub_update() {
		$sql = "update personrole set person_id = ".$this->personId." where object_id = ".$this->id;
		Database::update($sql);
	}
	
	function sub_remove() {
		$sql = "delete from personrole where object_id=".$this->id;
		Database::delete($sql);
	}
}
?>