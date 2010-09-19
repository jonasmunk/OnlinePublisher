<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Part
 */
require_once($basePath.'Editor/Classes/Parts/PartController.php');
require_once($basePath.'Editor/Classes/Parts/HtmlPart.php');
require_once($basePath.'Editor/Classes/Utilities/StringUtils.php');

class HtmlController extends PartController
{
	function HtmlController() {
		parent::PartController('html');
	}
	
	function createPart() {
		$part = new HtmlPart();
		$part->setHtml('<div>HTML-kode</div>');
		$part->save();
		return $part;
	}
	
	function getFromRequest() {
		$id = Request::getInt('id');
		$part = HtmlPart::load($id);
		return $part;
	}
	
	function buildSub($part,$context) {
		return '';
	}
}
?>