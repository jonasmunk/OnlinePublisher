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
	
	function getFromRequest() {
		$id = Request::getInt('id');
		$part = FilePart::load($id);
		return $part;
	}
	
	function buildSub($part,$context) {
		return '';
	}
}
?>