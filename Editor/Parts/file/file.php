<?
/**
 * @package OnlinePublisher
 * @subpackage Parts.File
 */
require_once($basePath.'Editor/Classes/Parts/LegacyPartController.php');
require_once($basePath.'Editor/Classes/Request.php');
require_once($basePath.'Editor/Classes/File.php');

class PartFile extends LegacyPartController {
	
	function PartFile($id=0) {
		parent::LegacyPartController('file');
		$this->id = $id;
	}
	
	function sub_display($context) {
		return $this->render();
	}
	/*
	function _display($id) {
		return $this->render();
	}
	*/
	function sub_editor($context) {
		global $baseUrl;
		if ($part = FilePart::load($this->id)) {
			return '<div id="part_file_container">'.$this->render().'</div>'.
			'<input type="hidden" name="fileId" value="'.$part->getFileId().'"/>'.
			'<script src="'.$baseUrl.'Editor/Parts/file/script.js" type="text/javascript" charset="utf-8"></script>';
		} else {
			return '';
		}
	}
	
	function sub_update() {
		$fileId = Request::getInt('fileId');
		if ($part = FilePart::load($this->id)) {
			$part->setFileId($fileId);
			$part->save();
		}
	}
	
	function sub_import(&$node) {
	}
	
	function sub_build($context) {
		if ($part = FilePart::load($this->id)) {
			$xml='<file xmlns="'.$this->_buildnamespace('1.0').'">';
			$sql="select object.data,file.type from object,file where file.object_id = object.id and object.id=".Database::int($part->getFileId());
			if ($row = Database::selectFirst($sql)) {
				$xml.='<info type="'.GuiUtils::mimeTypeToKind($row['type']).'"/>';
				$xml.=$row['data'];
			}
			$xml.='</file>';
			return $xml;
		}
		return '';
	}
	
	function sub_preview() {
		$xml='<file xmlns="'.$this->_buildnamespace('1.0').'">';
		$sql="select object.data,file.type from object,file where file.object_id = object.id and object.id=".Request::getInt('fileId');
		if ($file = Database::selectFirst($sql)) {
			$xml.='<info type="'.GuiUtils::mimeTypeToKind($file['type']).'"/>';
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