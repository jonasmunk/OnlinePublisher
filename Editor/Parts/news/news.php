<?
/**
 * @package OnlinePublisher
 * @subpackage Parts.Html
 */
require_once($basePath.'Editor/Classes/Parts/LegacyPartController.php');
require_once($basePath.'Editor/Classes/GuiUtils.php');

class PartNews extends LegacyPartController {
	
	function PartNews($id=0) {
		parent::LegacyPartController('news');
		$this->id = $id;
	}
	
	function sub_isDynamic() {
		return true;
	}
	
	// Toolbar stuff
	
	function isIn2iGuiEnabled() {
		return true;
	}

}
?>