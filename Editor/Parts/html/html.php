<?
/**
 * @package OnlinePublisher
 * @subpackage Parts.Html
 */
require_once($basePath.'Editor/Classes/Parts/LegacyPartController.php');

class PartHtml extends LegacyPartController {
	
	function PartHtml($id=0) {
		parent::LegacyPartController('html');
		$this->id = $id;
	}
	
}
?>