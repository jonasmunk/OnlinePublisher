<?
/**
 * @package OnlinePublisher
 * @subpackage Parts.Header
 */
require_once($basePath.'Editor/Classes/Parts/LegacyPartController.php');
require_once($basePath.'Editor/Classes/Services/XslService.php');
require_once($basePath.'Editor/Classes/Utilities/StringUtils.php');

class PartHeader extends LegacyPartController {

	var $id;
	
	function PartHeader($id=0) {
		parent::LegacyPartController('header');
		$this->id = $id;
	}
}
?>