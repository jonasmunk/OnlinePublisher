<?
/**
 * @package OnlinePublisher
 * @subpackage Parts.Html
 */
require_once($basePath.'Editor/Classes/Parts/LegacyPartController.php');

class PartPerson extends LegacyPartController {
	
	function PartPerson($id=0) {
		parent::LegacyPartController('person');
		$this->id = $id;
	}
	
}
?>