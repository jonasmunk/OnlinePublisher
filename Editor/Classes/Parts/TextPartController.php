<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Part
 */
require_once($basePath.'Editor/Classes/Parts/PartController.php');
require_once($basePath.'Editor/Classes/Parts/TextPart.php');
require_once($basePath.'Editor/Classes/Utilities/StringUtils.php');

class TextPartController extends PartController
{
	function TextPartController() {
		parent::PartController('text');
	}
	
	function createPart() {
		$part = new TextPart();
		$part->setText('Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.');
		$part->save();
		return $part;
	}
	
	function getFromRequest($id) {
		$part = TextPart::load($id);
		$part->setText(Request::getEncodedString('text'));
		if (Request::exists('fontSize')) {
			$part->setFontSize(Request::getString('fontSize'));
			$part->setFontFamily(Request::getString('fontFamily'));
			$part->setTextAlign(Request::getString('textAlign'));
			$part->setLineHeight(Request::getString('lineHeight'));
			$part->setFontWeight(Request::getString('fontWeight'));
			$part->setFontStyle(Request::getString('fontStyle'));
			$part->setWordSpacing(Request::getString('wordSpacing'));
			$part->setLetterSpacing(Request::getString('letterSpacing'));
			$part->setTextIndent(Request::getString('textIndent'));
			$part->setTextTransform(Request::getString('textTransform'));
			$part->setFontVariant(Request::getString('fontVariant'));
			$part->setTextDecoration(Request::getString('textDecoration'));
			$part->setImageId(Request::getInt('imageId'));
			$part->setImageFloat(Request::getString('imageFloat'));
		}
		return $part;
	}
	
	function buildSub($part,$context) {
		$text = $part->getText();
		$text = StringUtils::escapeSimpleXML($text);
		$text = $context->decorateForBuild($text);
		$text = StringUtils::insertLineBreakTags($text,'<break/>');
		$text = str_replace('<break/><break/>', '</p><p>', $text);
		$xml = '<text xmlns="'.$this->getNamespace().'">';
		$xml.= $this->buildXMLStyle($part);
		if ($part->getImageId()>0) {
			$data = Object::getObjectData($part->getImageId());
			if (StringUtils::isNotBlank($data)) {
				$xml.='<image float="'.StringUtils::escapeXML($part->getImageFloat()).'">'.$data.'</image>';
			}
		}
		$xml.= '<p>'.$text.'</p>';
		$xml.= '</text>';
		return $xml;
	}
}
?>