<?
/**
 * @package OnlinePublisher
 * @subpackage Parts.Html
 */
require_once($basePath.'Editor/Classes/Parts/LegacyPartController.php');

class PartHorizontalrule extends LegacyPartController {
	
	function PartHorizontalrule($id=0) {
		parent::LegacyPartController('horizontalrule');
		$this->id = $id;
	}
	
	function sub_display($context) {
		return $this->render();
	}
	
	function sub_editor($context) {
		return $this->render();
	}
	
	function sub_update() {
		
	}
	
	function sub_build($context) {
		return '<horizontalrule xmlns="'.$this->_buildnamespace('1.0').'"/>';
	}
	
	// Toolbar stuff
	
	function isIn2iGuiEnabled() {
		return true;
	}
}
?>