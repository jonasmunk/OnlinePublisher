<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Part
 */
require_once($basePath.'Editor/Classes/Parts/PartController.php');
require_once($basePath.'Editor/Classes/Parts/FilePart.php');
require_once($basePath.'Editor/Classes/Utilities/StringUtils.php');

class FilePartController extends PartController
{
	function FilePartController() {
		parent::PartController('file');
	}
	
	function createPart() {
		$part = new FilePart();
		$part->setFileId(FileService::getLatestFileId());
		$part->save();
		return $part;
	}
	
	function display($part,$context) {
		return $this->render($part,$context);
	}
	
	function editor($part,$context) {
		global $baseUrl;
		return '<div id="part_file_container">'.StringUtils::fromUnicode($this->render($part,$context)).'</div>'.
		'<input type="hidden" name="fileId" value="'.$part->getFileId().'"/>'.
		'<script src="'.$baseUrl.'Editor/Parts/file/script.js" type="text/javascript" charset="utf-8"></script>';
	}
	
	function getFromRequest($id) {
		$fileId = Request::getInt('fileId');
		$part = FilePart::load($id);
		$part->setFileId($fileId);
		return $part;
	}
	
	function buildSub($part,$context) {
		$xml='<file xmlns="'.$this->getNamespace().'">';
		$sql="select object.data,file.type from object,file where file.object_id = object.id and object.id=".Database::int($part->getFileId());
		if ($row = Database::selectFirst($sql)) {
			$xml.='<info type="'.GuiUtils::mimeTypeToKind($row['type']).'"/>';
			$xml.=$row['data'];
		}
		$xml.='</file>';
		return $xml;
	}
	
	function importSub($node,$part) {
		if ($object = DOMUtils::getFirstDescendant($node,'object')) {
			if ($id = intval($object->getAttribute('id'))) {
				$part->setFileId($id);
			}
		}
	}
}
?>