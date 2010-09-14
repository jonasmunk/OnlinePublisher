<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Part
 */
require_once($basePath.'Editor/Classes/Parts/PartController.php');
require_once($basePath.'Editor/Classes/Parts/Text.php');
require_once($basePath.'Editor/Classes/Utilities/StringUtils.php');

class ImagegalleryController extends PartController
{
	function ImagegalleryController() {
		parent::PartController('imagegallery');
	}
	
	function createPart() {
		$part = new Imagegallery();
		$part->setHeight(100);
		$part->setVariant('floating');
		$part->save();
		return $part;
	}
	
	function getFromRequest() {
		$id = Request::getInt('id');
		$part = Imagegallery::load($id);
		return $part;
	}
	
	function buildSub($part,$context) {
		return '';
	}
}
?>