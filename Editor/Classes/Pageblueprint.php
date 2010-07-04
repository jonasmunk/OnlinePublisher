<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes
 */
require_once($basePath.'Editor/Classes/Database.php');
require_once($basePath.'Editor/Classes/Object.php');

class PageBlueprint extends Object {
	var $designId;
	var $frameId;
	var $templateId;

	function PageBlueprint() {
		parent::Object('pageblueprint');
	}
	
	function getIcon() {
		return "Element/Template";
	}
	
	function setDesignId($designId) {
	    $this->designId = $designId;
	}

	function getDesignId() {
	    return $this->designId;
	}
	
	function setFrameId($frameId) {
	    $this->frameId = $frameId;
	}

	function getFrameId() {
	    return $this->frameId;
	}
	
	function setTemplateId($templateId) {
	    $this->templateId = $templateId;
	}

	function getTemplateId() {
	    return $this->templateId;
	}
	
	
	
	////////////////////////////// Persistence ///////////////////////

	function load($id) {
		$obj = new PageBlueprint();
		if ($obj->_load($id)) {
			$sql = "select frame_id,design_id,template_id from pageblueprint where object_id=".$id;
			$row = Database::selectFirst($sql);
			if ($row) {
				$obj->frameId=$row['frame_id'];
				$obj->designId=$row['design_id'];
				$obj->templateId=$row['template_id'];
			}
			return $obj;
		} else {
			return false;
		}
	}

	function sub_create() {
		$sql="insert into pageblueprint (object_id,frame_id,design_id,template_id) values (".
		$this->id.
		",".Database::int($this->frameId).
		",".Database::int($this->designId).
		",".Database::int($this->templateId).
		")";
		Database::insert($sql);
	}

	function sub_update() {
		$sql = "update pageblueprint set ".
		"frame_id=".Database::int($this->frameId).
		",design_id=".Database::int($this->designId).
		",template_id=".Database::int($this->templateId).
		" where object_id=".$this->id;
		Database::update($sql);
	}

	function sub_publish() {
		$data =
		'<pageblueprint xmlns="'.parent::_buildnamespace('1.0').'">'.
		'</pageblueprint>';
		return $data;
	}

	function sub_remove() {
		$sql = "delete from pageblueprint where object_id=".$this->id;
		Database::delete($sql);
	}
		
	//////////////////////////// Convenience ///////////////////////////
	
	
	/**
	 * @static
	 */
    function search($query = array()) {
        $out = array();
        $sql = "select id from object,pageblueprint where object.id=pageblueprint.object_id";
		$sql.=" order by object.title";
        $result = Database::select($sql);
        while ($row = Database::next($result)) {
			$out[] = PageBlueprint::load($row['id']);
        }
        Database::free($result);
        return $out;
    }
}
?>