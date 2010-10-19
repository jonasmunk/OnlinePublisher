<?
/**
 * @package OnlinePublisher
 * @subpackage Parts.Image
 */
require_once($basePath.'Editor/Classes/Parts/LegacyPartController.php');
require_once($basePath.'Editor/Classes/Image.php');
require_once($basePath.'Editor/Classes/GuiUtils.php');
require_once($basePath.'Editor/Classes/Log.php');

class PartImage extends LegacyPartController {
	
	function PartImage($id=0) {
		parent::LegacyPartController('image');
		$this->id = $id;
	}
		
	function setLatestUploadId($id) {
		$_SESSION['part.image.latest_upload_id'] = $id;
	}
	
	function getLatestUploadId() {
		return $_SESSION['part.image.latest_upload_id'];
	}
}
?>