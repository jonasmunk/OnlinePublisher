<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Part
 */
require_once($basePath.'Editor/Classes/Parts/PartController.php');
require_once($basePath.'Editor/Classes/Parts/Text.php');

class TextController extends PartController
{
	function TextController() {
		parent::PartController('text');
	}
	
	function getFromRequest() {
		$id = Request::getInt('id');
		$text = Request::getUnicodeString('text');

		$part = Text::load($id);
		$part->setText($text);
		return $part;
	}
	
	function buildSub($part,$context) {
		$text = $part->getText();
		$text = $this->escapeXML($text);
		$text = $context->decorateForBuild($text);
		$text = $this->insertLineBreakTags($text,'<break/>');
		return 
			'<text xmlns="'.$this->getNamespace().'">'.
			$this->buildXMLStyle($part).
			'<p>'.$text.'</p>'.
			'</text>';
	}
}
?>