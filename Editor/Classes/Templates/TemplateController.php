<?
/**
 * @package OnlinePublisher
 * @subpackage Classes.Templates
 */

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