<?
/**
 * @package OnlinePublisher
 * @subpackage Parts.Text
 */
require_once($basePath.'Editor/Classes/Parts/LegacyPartController.php');
require_once($basePath.'Editor/Classes/Services/XslService.php');
require_once($basePath.'Editor/Classes/Utilities/StringUtils.php');

class PartText extends LegacyPartController { 

	var $id;
	
	function PartText($id=0) {
		parent::LegacyPartController('text');
		$this->id = $id;
	}
}
?>