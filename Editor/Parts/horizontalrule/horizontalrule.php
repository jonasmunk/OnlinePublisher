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
}
?>