<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes
 */
require_once($basePath.'Editor/Classes/Object.php');

class PersonGroup extends Object {
	
	function PersonGroup() {
		parent::Object('persongroup');
	}
	
	function getIn2iGuiIcon() {
		return 'common/folder';
	}
	
	function load($id) {
		$obj = new PersonGroup();
		$obj->_load($id);
		return $obj;
	}
	
	function sub_create() {
		$sql = "insert into persongroup (object_id) values (".$this->id.")";
		Database::insert($sql);
	}
	
	function sub_update() {
	}
	
	function sub_remove() {
		$sql="delete from persongroup_person where persongroup_id=".$this->id;
		Database::delete($sql);
		
		$sql = "delete from persongroup where object_id=".$this->id;
		Database::delete($sql);
	}
}
?>