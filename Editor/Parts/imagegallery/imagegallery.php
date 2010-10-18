<?
/**
 * @package OnlinePublisher
 * @subpackage Parts.Imagegallery
 */
require_once($basePath.'Editor/Classes/Parts/LegacyPartController.php');
require_once($basePath.'Editor/Classes/Parts/ImagegalleryPart.php');
require_once($basePath.'Editor/Classes/Request.php');
require_once($basePath.'Editor/Classes/GuiUtils.php');

class PartImagegallery extends LegacyPartController {
	
	function PartImagegallery($id=0) {
		parent::LegacyPartController('imagegallery');
		$this->id = $id;
	}
	
}
?>