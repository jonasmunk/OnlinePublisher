<?
/**
 * @package OnlinePublisher
 * @subpackage Parts.List
 */
require_once($basePath.'Editor/Classes/Parts/LegacyPartController.php');
require_once($basePath.'Editor/Classes/Request.php');
require_once($basePath.'Editor/Classes/Calendarsource.php');
require_once($basePath.'Editor/Classes/DateUtil.php');
require_once($basePath.'Editor/Classes/XmlUtils.php');
require_once($basePath.'Editor/Classes/In2iGui.php');
require_once($basePath.'Editor/Classes/News.php');

class PartList extends LegacyPartController {
	
	function PartList($id=0) {
		parent::LegacyPartController('list');
		$this->id = $id;
	}
	
}
?>