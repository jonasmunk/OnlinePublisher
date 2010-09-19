<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Part
 */
require_once($basePath.'Editor/Classes/Parts/PartController.php');
require_once($basePath.'Editor/Classes/Parts/HorizontalrulePart.php');
require_once($basePath.'Editor/Classes/Utilities/StringUtils.php');

class HorizontalrulePartController extends PartController
{
	function ImagegalleryPartController() {
		parent::PartController('horizontalrule');
	}
	
	function createPart() {
		$part = new HorizontalrulePart();
		$part->save();
		return $part;
	}
	
	function getFromRequest() {
		$id = Request::getInt('id');
		$part = HorizontalrulePart::load($id);
		return $part;
	}
	
	function buildSub($part,$context) {
		return '';
	}
}
?>