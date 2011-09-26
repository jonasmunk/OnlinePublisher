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

Object::$schema['productgroup'] = array();

class ProductGroup extends Object {

	function ProductGroup() {
		parent::Object('productgroup');
	}

	function load($id) {
		return Object::get($id,'productgroup');
	}
	
	function removeMore() {
		$sql="delete from productgroup_product where productgroup_id=".Database::int($this->id);
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