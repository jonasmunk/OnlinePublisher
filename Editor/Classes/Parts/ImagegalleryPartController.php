<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Part
 */
require_once($basePath.'Editor/Classes/Parts/PartController.php');
require_once($basePath.'Editor/Classes/Parts/ImagegalleryPart.php');
require_once($basePath.'Editor/Classes/Utilities/StringUtils.php');

class ImagegalleryPartController extends PartController
{
	function ImagegalleryPartController() {
		parent::PartController('imagegallery');
	}
	
	function createPart() {
		$part = new ImagegalleryPart();
		$part->setHeight(100);
		$part->setVariant('floating');
		$part->save();
		return $part;
	}
	
	function getFromRequest() {
		$id = Request::getInt('id');
		$part = ImagegalleryPart::load($id);
		return $part;
	}
	
	function buildSub($part,$context) {
		return '';
	}
}
?>