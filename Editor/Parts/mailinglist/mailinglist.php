<?
/**
 * @package OnlinePublisher
 * @subpackage Parts.Mailinglist
 */
require_once($basePath.'Editor/Classes/Parts/LegacyPartController.php');
require_once($basePath.'Editor/Classes/Request.php');

class PartMailinglist extends LegacyPartController {
	
	function PartMailinglist($id=0) {
		parent::LegacyPartController('mailinglist');
		$this->id = $id;
	}
		
}
?>