<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes
 */
if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

Entity::$schema['Productgroup'] = [
	'table' => 'productgroup',
	'properties' => []
];

class Productgroup extends Object {

	function Productgroup() {
		parent::Object('productgroup');
	}

	static function load($id) {
		return Object::get($id,'productgroup');
	}
	
	function removeMore() {
		$sql="delete from productgroup_product where productgroup_id=".Database::int($this->id);
		Database::delete($sql);
	}
	
	/////////////////////////// GUI /////////////////////////
	
	function getIcon() {
	    return 'common/folder';
	}
}
?>