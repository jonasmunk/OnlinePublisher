<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Part
 */
require_once($basePath.'Editor/Classes/Parts/PartController.php');
require_once($basePath.'Editor/Classes/Parts/ListingPart.php');
require_once($basePath.'Editor/Classes/Utilities/StringUtils.php');

class ListingPartController extends PartController
{
	function ListingPartController() {
		parent::PartController('listing');
	}
	
	function createPart() {
		$part = new ListingPart();
		$part->setText("* Punkt 1\n* Punkt 2");
		$part->setListStyle('disc');
		$part->save();
		return $part;
	}
}
?>