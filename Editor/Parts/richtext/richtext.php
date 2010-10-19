<?
/**
 * @package OnlinePublisher
 * @subpackage Parts.Richtext
 */
require_once($basePath.'Editor/Classes/Parts/LegacyPartController.php');

class PartRichtext extends LegacyPartController {
	
	function PartRichtext($id=0) {
		parent::LegacyPartController('richtext');
		$this->id = $id;
	}
	
}
?>