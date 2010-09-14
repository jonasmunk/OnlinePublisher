<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Part
 */
require_once($basePath.'Editor/Classes/Parts/PartController.php');
require_once($basePath.'Editor/Classes/Parts/Text.php');
require_once($basePath.'Editor/Classes/Utilities/StringUtils.php');

class TextController extends PartController
{
	function TextController() {
		parent::PartController('text');
	}
	
	function createPart() {
		$part = new Text();
		$part->setText('Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.');
		$part->save();
		return $part;
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
		$text = StringUtils::escapeSimpleXML($text);
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