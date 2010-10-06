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
}
?>