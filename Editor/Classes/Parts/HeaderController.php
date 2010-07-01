<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Part
 */
require_once($basePath.'Editor/Classes/Parts/PartController.php');
require_once($basePath.'Editor/Classes/Parts/Header.php');

class HeaderController extends PartController
{
	function HeaderController() {
		parent::PartController('header');
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
		$text = $this->escapeXML($text);
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