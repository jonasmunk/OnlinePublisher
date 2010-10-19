<?
/**
 * @package OnlinePublisher
 * @subpackage Parts.Listing
 */
require_once($basePath.'Editor/Classes/Parts/LegacyPartController.php');
require_once($basePath.'Editor/Classes/Services/XslService.php');
require_once($basePath.'Editor/Classes/Utilities/StringUtils.php');

class PartListing extends LegacyPartController {

	var $id;

	function PartListing($id=0) {
		parent::LegacyPartController('listing');
		$this->id = $id;
	}
}
?>