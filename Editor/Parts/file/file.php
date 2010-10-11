<?
/**
 * @package OnlinePublisher
 * @subpackage Parts.File
 */
require_once($basePath.'Editor/Classes/Parts/LegacyPartController.php');
require_once($basePath.'Editor/Classes/Request.php');
require_once($basePath.'Editor/Classes/File.php');

class PartFile extends LegacyPartController {
	
	function PartFile($id=0) {
		parent::LegacyPartController('file');
		$this->id = $id;
	}
	
}
?>