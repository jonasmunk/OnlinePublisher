<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes
 */
require_once($basePath.'Editor/Classes/Object.php');

class ImageGroup extends Object {

	function ImageGroup() {
		parent::Object('imagegroup');
	}

	function load($id) {
		$obj = new ImageGroup();
		$obj->_load($id);
		return $obj;
	}

	function sub_create() {
		$sql = "insert into imagegroup (object_id) values (".$this->id.")";
		Database::insert($sql);
	}

	function sub_update() {
	}

	function sub_remove() {
		$sql="delete from imagegroup_image where imagegroup_id=".$this->id;
		Database::delete($sql);
		$sql="delete from imagegallery_object where object_id=".$this->id;
		Database::delete($sql);
		$sql = "delete from imagegroup where object_id=".$this->id;
		Database::delete($sql);
	}
	

	function addImage($imageId) {
		$sql="delete from imagegroup_image where image_id=".$imageId." and imagegroup_id=".$this->id;
		Database::delete($sql);

		$sql="insert into imagegroup_image (image_id, imagegroup_id) values (".$imageId.",".$this->id.")";
		Database::insert($sql);
		EventManager::fireEvent('relation_change','object','imagegroup',$this->id);
	}
	
	function addImages($images) {
		for ($i=0;$i<count($images);$i++) {
			$sql="insert into imagegroup_image (image_id, imagegroup_id) values (".$images[$i].",".$this->id.")";
			Database::insert($sql);
		}
		EventManager::fireEvent('relation_change','object','imagegroup',$this->id);
	}
	
	function removeImages($images) {
		for ($i=0;$i<count($images);$i++) {
			$sql="delete from imagegroup_image where image_id=".$images[$i]." and imagegroup_id=".$this->id;
			Database::delete($sql);
		}
		EventManager::fireEvent('relation_change','object','imagegroup',$this->id);
	}
	
	//////////////////////////// Special ///////////////////////
	
	/**
	 * @static
	 */
	function listAllByTitle() {
		$groups = array();
		$ids = array();
		$sql = "select id from object where type='imagegroup' order by title";
		$result = Database::select($sql);
		while ($row = Database::next($result)) {
			$ids[]=$row['id'];
		}
		Database::free($result);
		foreach ($ids as $id) {
			$groups[] = ImageGroup::load($id);
		}
		return $groups;
	}
	
	/**
	 * @static
	 */
	function getConcatenatedImageData() {
		$out = '';
		$sql = "select object.data from object,imagegroup_image where imagegroup_image.image_id=object.id and imagegroup_image.imagegroup_id=".$this->id;
		$result = Database::select($sql);
		while ($row = Database::next($result)) {
			$out.=$row['data'];
		}
		Database::free($result);
		return $out;
	}
	
	function getIn2iGuiIcon() {
        return "common/folder";
	}
}
?>