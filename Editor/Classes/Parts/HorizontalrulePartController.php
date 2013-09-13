<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Part
 */
if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

class HorizontalrulePartController extends PartController
{
	function HorizontalrulePartController() {
		parent::PartController('horizontalrule');
	}
	
	static function createPart() {
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