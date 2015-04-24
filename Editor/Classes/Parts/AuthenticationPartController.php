<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Part
 */
if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

class AuthenticationPartController extends PartController
{
	function AuthenticationPartController() {
		parent::PartController('authentication');
	}
	
	static function createPart() {
		$part = new AuthenticationPart();
		$part->save();
		return $part;
	}
	
	function getFromRequest($id) {
		return AuthenticationPart::load($id);
	}
	
	function display($part,$context) {
		return $this->render($part,$context);
	}
	
	function editor($part,$context) {
		return $this->render($part,$context);
	}
	
	function buildSub($part,$context) {
		return '<authentication xmlns="'.$this->getNamespace().'"/>';
	}
}
?>