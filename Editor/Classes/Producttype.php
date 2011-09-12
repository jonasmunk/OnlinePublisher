<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes
 */
if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}
require_once($basePath.'Editor/Classes/Object.php');

class ProductType extends Object {
	
	function ProductType() {
		parent::Object('producttype');
	}
	
	function load($id) {
		$obj = new ProductType();
		$obj->_load($id);
		return $obj;
	}
	
	function sub_create() {
		$sql = "insert into producttype (object_id) values (".$this->id.")";
		Database::insert($sql);
	}
	
	function sub_update() {
	}
	
	function sub_remove() {
		$sql = "delete from producttype where object_id=".$this->id;
		Database::delete($sql);
	}
	
	function canRemove() {
		$sql = "select object_id from product where producttype_id=".$this->id;
		$out = Database::isEmpty($sql);
		return $out;
	}
	
	/////////////////////////// GUI /////////////////////////
	
	function getIcon() {
	    return 'Element/Folder';
	}
}
?>