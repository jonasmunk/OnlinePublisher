<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes
*/

require_once($basePath.'Editor/Classes/Object.php');

class FileGroup extends Object {

	function FileGroup() {
		parent::Object('filegroup');
	}

	function load($id) {
		$obj = new FileGroup();
		$obj->_load($id);
		return $obj;
	}

	function sub_create() {
		$sql = "insert into filegroup (object_id) values (".$this->id.")";
		Database::insert($sql);
	}

	function sub_update() {
	}

	function sub_remove() {
		$sql="delete from filegroup_file where filegroup_id=".$this->id;
		Database::delete($sql);
		$sql = "delete from filegroup where object_id=".$this->id;
		Database::delete($sql);
	}
	
	function getIn2iGuiIcon() {
        return "common/folder";
	}
}
?>