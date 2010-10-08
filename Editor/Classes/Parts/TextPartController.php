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
	
	function display($part,$context) {
		return $this->render($part,$context);
	}
	
	function getIndex($part) {
		// TODO Strip tags etc.
		return $part->getText();
	}
	
	function editor($part,$context) {
		return
		'<textarea class="part_text common_font" name="text" id="PartTextTextarea" style="border: 1px solid lightgrey; width: 100%; height: 200px; background: transparent; padding: 0; -webkit-box-sizing: border-box; -moz-box-sizing: border-box; box-sizing: border-box;'.$this->buildCSSStyle($part).'">'.
		StringUtils::escapeXML($part->getText()).
		'</textarea>'.
		'<input type="hidden" name="fontSize" value="'.StringUtils::escapeXML($part->getFontSize()).'"/>'.
		'<input type="hidden" name="fontFamily" value="'.StringUtils::escapeXML($part->getFontfamily()).'"/>'.
		'<input type="hidden" name="textAlign" value="'.StringUtils::escapeXML($part->getTextAlign()).'"/>'.
		'<input type="hidden" name="lineHeight" value="'.StringUtils::escapeXML($part->getLineHeight()).'"/>'.
		'<input type="hidden" name="fontWeight" value="'.StringUtils::escapeXML($part->getFontWeight()).'"/>'.
		'<input type="hidden" name="fontStyle" value="'.StringUtils::escapeXML($part->getFontWeight()).'"/>'.
		'<input type="hidden" name="color" value="'.StringUtils::escapeXML($part->getColor()).'"/>'.
		'<input type="hidden" name="wordSpacing" value="'.StringUtils::escapeXML($part->getWordSpacing()).'"/>'.
		'<input type="hidden" name="letterSpacing" value="'.StringUtils::escapeXML($part->getLetterSpacing()).'"/>'.
		'<input type="hidden" name="textIndent" value="'.StringUtils::escapeXML($part->getTextIndent()).'"/>'.
		'<input type="hidden" name="textTransform" value="'.StringUtils::escapeXML($part->getTextTransform()).'"/>'.
		'<input type="hidden" name="fontVariant" value="'.StringUtils::escapeXML($part->getFontVariant()).'"/>'.
		'<input type="hidden" name="textDecoration" value="'.StringUtils::escapeXML($part->getTextDecoration()).'"/>'.
		'<input type="hidden" name="imageId" value="'.StringUtils::escapeXML($part->getImageId()).'"/>'.
		'<input type="hidden" name="imageFloat" value="'.StringUtils::escapeXML($part->getImageFloat()).'"/>'.
		'<script type="text/javascript">'.
		'document.getElementById("PartTextTextarea").focus();'.
		'document.getElementById("PartTextTextarea").select();'.
		'</script>';
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