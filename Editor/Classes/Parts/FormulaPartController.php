<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Part
 */
require_once($basePath.'Editor/Classes/Parts/PartController.php');
require_once($basePath.'Editor/Classes/Utilities/StringUtils.php');

class FormulaPartController extends PartController
{
	function FormulaPartController() {
		parent::PartController('formula');
	}
	
	function createPart() {
		$part = new FormulaPart();
		$part->save();
		return $part;
	}
	
	function getFromRequest() {
		$id = Request::getInt('id');
		$part = FormulaPart::load($id);
		return $part;
	}
	
	function buildSub($part,$context) {
		return '';
	}
}
?>