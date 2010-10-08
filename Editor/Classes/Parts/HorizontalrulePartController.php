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
	function HorizontalrulePartController() {
		parent::PartController('horizontalrule');
	}
	
	function createPart() {
		$part = new HorizontalrulePart();
		$part->save();
		return $part;
	}
	
	function getFromRequest($id) {
		return HorizontalrulePart::load($id);
	}
	
	function display($part,$context) {
		return $this->render($part,$context);
	}
	
	function editor($part,$context) {
		return $this->render($part,$context);
	}
	
	function buildSub($part,$context) {
		return '<horizontalrule xmlns="'.$this->getNamespace().'"/>';
	}
}
?>