<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes
 */
if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}
require_once($basePath.'Editor/Classes/Model/Object.php');

Object::$schema['producttype'] = array();

class ProductType extends Object {

	function ProductType() {
		parent::Object('producttype');
	}

	function load($id) {
		return Object::get($id,'producttype');
	}
	
	function getIn2iGuiIcon() {
        return "common/folder";
	}

	function canRemove() {
		$sql = "select object_id from product where producttype_id=".Database::int($this->id);
		return Database::isEmpty($sql);
	}
	
}
?>