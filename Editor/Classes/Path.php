<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes
 */
require_once($basePath.'Editor/Classes/Object.php');

class Path extends Object {
	var $path;
	var $pageId=0;

	function Path() {
		parent::Object('path');
	}

	function setPath($path) {
		$this->path = $path;
		$this->_updateTitle();
	}

	function getPath() {
		return $this->path;
	}

	function setPageId($id) {
		$this->pageId = $id;
		$this->_updateTitle();
	}

	function getPageId() {
		return $this->pageId;
	}

	function _updateTitle() {
		$this->setTitle($this->path.' -> '.$this->pageId);
	}

    /////////////////////////// Persistence ////////////////////////

	function load($id) {
		$sql = "select * from path where object_id=".$id;
		$row = Database::selectFirst($sql);
		if ($row) {
			$obj = new Path();
			$obj->_load($id);
			$obj->path=$row['path'];
			$obj->pageId=$row['page_id'];
			return $obj;
		}
		return null;
	}

	function sub_create() {
		$sql="insert into path (object_id,path,page_id) values (".
		$this->id.
		",".sqlText($this->path).
		",".sqlInt($this->pageId).
		")";
		Database::insert($sql);
	}

	function sub_update() {
		$sql = "update path set ".
		"path=".sqlText($this->path).
		",page_id=".sqlInt($this->pageId).
		" where object_id=".$this->id;
		Database::update($sql);
	}

	function sub_publish() {
		return '<path xmlns="'.parent::_buildnamespace('1.0').'"></path>';
	}

	function sub_remove() {
		$sql = "delete from path where object_id=".$this->id;
		Database::delete($sql);
	}
	
	/////////////////////////// GUI /////////////////////////
	
	function getIcon() {
	    return 'Part/Generic';
	}
	
	function getIn2iGuiIcon() {
	    return 'monochrome/globe';
	}
}
?>