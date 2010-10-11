<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Part
 */
require_once($basePath.'Editor/Classes/Parts/PartController.php');
require_once($basePath.'Editor/Classes/GuiUtils.php');
require_once($basePath.'Editor/Classes/Utilities/StringUtils.php');

class MailinglistPartController extends PartController
{
	function MailinglistPartController() {
		parent::PartController('mailinglist');
	}
		
	function createPart() {
		$part = new MailinglistPart();
		$part->save();
		return $part;
	}

	function getToolbars() {
		return array(
			'Postliste' =>
				'<checkboxes name="lists" label="Postlister">
				'.GuiUtils::buildObjectItems('mailinglist').'
				</checkboxes>'
			);
	}
}