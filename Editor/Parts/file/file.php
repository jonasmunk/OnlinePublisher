<?
/**
 * @package OnlinePublisher
 * @subpackage Parts.File
 */
require_once($basePath.'Editor/Classes/Part.php');
require_once($basePath.'Editor/Classes/Request.php');
require_once($basePath.'Editor/Classes/File.php');

class PartFile extends Part {
	
	function PartFile($id=0) {
		parent::Part('file');
		$this->id = $id;
	}
	
	function sub_display($context) {
		return $this->render();
	}
	
	function _display($id) {
		return $this->render();
	}
	
	function sub_editor($context) {
		global $baseUrl;
		$sql = "select * from part_file where part_id=".$this->id;
		if ($row = Database::selectFirst($sql)) {
			return '<div id="part_file_container">'.$this->render().'</div>'.
			'<input type="hidden" name="fileId" value="'.$row['file_id'].'"/>'.
			'<script src="'.$baseUrl.'Editor/Parts/file/script.js" type="text/javascript" charset="utf-8"></script>';
		} else {
			return '';
		}
	}
	
	function sub_create() {
		$sql = "insert into part_file (part_id) values (".$this->id.")";
		Database::insert($sql);
	}
	
	function sub_delete() {
		$sql = "delete from part_file where part_id=".$this->id;
		Database::delete($sql);
	}
	
	function sub_update() {
		$fileId = Request::getInt('fileId');
		$sql = "update part_file set file_id=".Database::int($fileId)." where part_id=".$this->id;
		Database::update($sql);
	}
	
	function sub_import(&$node) {
	}
	
	function sub_build($context) {
		$sql = "select * from part_file where part_id=".$this->id;
		if ($row = Database::selectFirst($sql)) {
			$xml='<file xmlns="'.$this->_buildnamespace('1.0').'">';
			$sql="select object.data from object,file where file.object_id = object.id and object.id=".$row['file_id'];
			if ($file = Database::selectFirst($sql)) {
				$xml.=$file['data'];
			}
			$xml.='</file>';
			return $xml;
		} else {
			return '';
		}
	}
	
	function sub_preview() {
		$xml='<file xmlns="'.$this->_buildnamespace('1.0').'">';
		$sql="select object.data from object,file where file.object_id = object.id and object.id=".Request::getInt('fileId');
		if ($file = Database::selectFirst($sql)) {
			$xml.=$file['data'];
		}
		$xml.='</file>';
		return $xml;
	}
	
	// Toolbar stuff
	
	function isIn2iGuiEnabled() {
		return true;
	}
}
?>