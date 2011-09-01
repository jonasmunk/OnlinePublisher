<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes
 */
require_once($basePath.'Editor/Classes/Object.php');

Object::$schema['imagegroup'] = array();

class ImageGroup extends Object {

	function ImageGroup() {
		parent::Object('imagegroup');
	}

	function load($id) {
		return Object::get($id,'imagegroup');
	}
	
	function getIn2iGuiIcon() {
        return "common/folder";
	}

	function removeMore() {
		$sql="delete from imagegroup_image where imagegroup_id=".Database::int($this->id);
		Database::delete($sql);
	}
	
}
?>