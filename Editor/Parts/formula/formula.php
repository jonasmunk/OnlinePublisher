<?
/**
 * @package OnlinePublisher
 * @subpackage Parts.Html
 */
require_once($basePath.'Editor/Classes/Parts/LegacyPartController.php');
require_once($basePath.'Editor/Classes/Request.php');

class PartFormula extends LegacyPartController {
	
	function PartFormula($id=0) {
		parent::LegacyPartController('formula');
		$this->id = $id;
	}

}
?>