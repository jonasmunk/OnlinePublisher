<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes
 */
require_once($basePath.'Editor/Classes/Object.php');

class ProductGroup extends Object {
	
	function ProductGroup() {
		parent::Object('productgroup');
	}
	
	function load($id) {
		$obj = new ProductGroup();
		$obj->_load($id);
		return $obj;
	}
	
	function sub_create() {
		$sql = "insert into productgroup (object_id) values (".$this->id.")";
		Database::insert($sql);
	}
	
	function sub_update() {
	}
	
	function sub_remove() {
		$sql="delete from productgroup_product where productgroup_id=".$this->id;
		Database::delete($sql);
		$sql = "delete from productgroup where object_id=".$this->id;
		Database::delete($sql);
	}
	
	/////////////////////////// GUI /////////////////////////
	
	function getIcon() {
	    return 'Element/Folder';
	}

	function getIn2iGuiIcon() {
	    return 'common/folder';
	}
}
?>