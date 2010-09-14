<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Part
 */
require_once($basePath.'Editor/Classes/Parts/PartController.php');
require_once($basePath.'Editor/Classes/Parts/Header.php');
require_once($basePath.'Editor/Classes/Utilities/StringUtils.php');

class HeaderController extends PartController
{
	function HeaderController() {
		parent::PartController('header');
	}
	
	function createPart() {
		$part = new Header();
		$part->setText('Velkommen');
		$part->setLevel(1);
		$part->save();
		return $part;
	}
	
	function getFromRequest() {
		$id = Request::getInt('id');
		$text = Request::getUnicodeString('text');

		$part = Header::load($id);
		$part->setText($text);
		return $part;
	}
	
	function buildSub($part,$context) {
		$text = $part->getText();
		$text = StringUtils::escapeSimpleXML($text);
		$text = $context->decorateForBuild($text);
		$text = $this->insertLineBreakTags($text,'<break/>');
		return 
			'<header level="'.$part->getLevel().'" xmlns="'.$this->getNamespace().'">'.
			$this->buildXMLStyle($part).
			$text.
			'</header>';
	}
}
?>