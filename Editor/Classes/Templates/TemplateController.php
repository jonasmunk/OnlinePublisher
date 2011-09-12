<?
/**
 * @package OnlinePublisher
 * @subpackage Classes.Templates
 */

if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}
class TemplateController {
	
	var $type;
	
	function TemplateController($type) {
		$this->type = $type;
	}
	
	// Override this
	function isClientSide() {
		return false;
	}
}