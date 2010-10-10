<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Part
 */
require_once($basePath.'Editor/Classes/Parts/PartController.php');
require_once($basePath.'Editor/Classes/Parts/ImagePart.php');
require_once($basePath.'Editor/Classes/Utilities/StringUtils.php');

class ImagePartController extends PartController
{
	function ImagePartController() {
		parent::PartController('image');
	}
	
	function createPart() {
		$part = new ImagePart();
		$part->setScaleMethod('max');
		$part->setScaleHeight(200);
		$part->setImageId(ImageService::getLatestImageId());
		$part->save();
		return $part;
	}
}
?>