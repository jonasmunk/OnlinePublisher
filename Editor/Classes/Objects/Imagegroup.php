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

Object::$schema['imagegroup'] = array();

class ImageGroup extends Object {

	function ImageGroup() {
		parent::Object('imagegroup');
	}

	function load($id) {
		return Object::get($id,'imagegroup');
	}
	
	function getIcon() {
        return "common/folder";
	}

	function removeMore() {
		$sql="delete from imagegroup_image where imagegroup_id=".Database::int($this->id);
		Database::delete($sql);
	}
	
}
?>