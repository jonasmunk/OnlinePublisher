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
		$context = new PartContext();
		$text = $part->getText();
		$text = $context->decorateForIndex($text);
		return $text;
	}
	
	function editor($part,$context) {
		global $baseUrl;
		return
		'<textarea class="part_text common_font" name="text" id="PartTextTextarea" style="border: 1px solid lightgrey; width: 100%; height: 200px; background: transparent; padding: 0; -webkit-box-sizing: border-box; -moz-box-sizing: border-box; box-sizing: border-box;'.$this->buildCSSStyle($part).'">'.
		StringUtils::escapeXML($part->getText()).
		'</textarea>'.
		'<input type="hidden" name="fontSize" value="'.StringUtils::escapeXML($part->getFontSize()).'"/>'.
		'<input type="hidden" name="fontFamily" value="'.StringUtils::escapeXML($part->getFontfamily()).'"/>'.
		'<input type="hidden" name="textAlign" value="'.StringUtils::escapeXML($part->getTextAlign()).'"/>'.
		'<input type="hidden" name="lineHeight" value="'.StringUtils::escapeXML($part->getLineHeight()).'"/>'.
		'<input type="hidden" name="fontWeight" value="'.StringUtils::escapeXML($part->getFontWeight()).'"/>'.
		'<input type="hidden" name="fontStyle" value="'.StringUtils::escapeXML($part->getFontStyle()).'"/>'.
		'<input type="hidden" name="color" value="'.StringUtils::escapeXML($part->getColor()).'"/>'.
		'<input type="hidden" name="wordSpacing" value="'.StringUtils::escapeXML($part->getWordSpacing()).'"/>'.
		'<input type="hidden" name="letterSpacing" value="'.StringUtils::escapeXML($part->getLetterSpacing()).'"/>'.
		'<input type="hidden" name="textIndent" value="'.StringUtils::escapeXML($part->getTextIndent()).'"/>'.
		'<input type="hidden" name="textTransform" value="'.StringUtils::escapeXML($part->getTextTransform()).'"/>'.
		'<input type="hidden" name="fontVariant" value="'.StringUtils::escapeXML($part->getFontVariant()).'"/>'.
		'<input type="hidden" name="textDecoration" value="'.StringUtils::escapeXML($part->getTextDecoration()).'"/>'.
		'<input type="hidden" name="imageId" value="'.StringUtils::escapeXML($part->getImageId()).'"/>'.
		'<input type="hidden" name="imageFloat" value="'.StringUtils::escapeXML($part->getImageFloat()).'"/>'.
		'<script src="'.$baseUrl.'Editor/Parts/text/script.js" type="text/javascript" charset="utf-8"></script>';
	}
	
	function getFromRequest($id) {
		$part = TextPart::load($id);
		$part->setText(Request::getUnicodeString('text'));
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
			$part->setColor(Request::getString('color'));
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
	
	function importSub($node,$part) {
		$xml = '<?xml version="1.0" encoding="ISO-8859-1"?>'.DOMUtils::getInnerXML($node);
		$xsl = '<?xml version="1.0" encoding="ISO-8859-1"?>
		<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
		 xmlns:t="http://uri.in2isoft.com/onlinepublisher/part/text/1.0/" exclude-result-prefixes="t">
		<xsl:output method="text" encoding="ISO-8859-1"/>

		<xsl:template match="t:text"><xsl:apply-templates/></xsl:template>
		<xsl:template match="t:break"><xsl:text>'."\n".'</xsl:text></xsl:template>
		<xsl:template match="t:strong">[s]<xsl:apply-templates/>[s]</xsl:template>
		<xsl:template match="t:em">[e]<xsl:apply-templates/>[e]</xsl:template>
		<xsl:template match="t:del">[slet]<xsl:apply-templates/>[slet]</xsl:template>
		<xsl:template match="t:link"><xsl:apply-templates/></xsl:template>

		</xsl:stylesheet>';
		
		$text = XslService::transform($xml,$xsl);
		
		$this->parseXMLStyle($part,DOMUtils::getFirstDescendant($node,'style'));
		
		$part->setText($text);
	}
	
	
	function getToolbars() {
		return array(
			'Tekst' =>
			'
			<style-length label="St&#248;rrelse" name="fontSize"/>
			<segmented label="Placering" name="textAlign" allow-null="true">
				<item icon="style/text_align_left" value="left"/>
				<item icon="style/text_align_center" value="center"/>
				<item icon="style/text_align_right" value="right"/>
				<item icon="style/text_align_justify" value="justify"/>
			</segmented>
			<divider/>
			<dropdown label="Skrift" name="fontFamily" width="180">
				'.$this->getFontItems().'
			</dropdown>
			<style-length label="Linjeh&#248;jde" name="lineHeight"/>
			<textfield label="Farve" name="color" width="60"/>
			<segmented label="Fed" name="fontWeight" allow-null="true">
				<item icon="style/text_normal" value="normal"/>
				<item icon="style/text_bold" value="bold"/>
			</segmented>
			<segmented label="Kursiv" name="fontStyle" allow-null="true">
				<item icon="style/text_normal" value="normal"/>
				<item icon="style/text_italic" value="italic"/>
			</segmented>',
			
		'Avanceret' =>
			'
			<style-length label="Ord-mellemrum" name="wordSpacing"/>
			<style-length label="Tegn-mellemrum" name="letterSpacing"/>
			<style-length label="Indrykning" name="textIndent"/>
			<segmented label="Bogstaver" name="textTransform" allow-null="true">
				<item icon="style/text_normal" value="normal"/>
				<item icon="style/text_transform_capitalize" value="capitalize"/>
				<item icon="style/text_transform_uppercase" value="uppercase"/>
				<item icon="style/text_transform_lowercase" value="lowercase"/>
			</segmented>
			<segmented label="Variant" name="fontVariant" allow-null="true">
				<item icon="style/font_variant_normal" value="normal"/>
				<item icon="style/font_variant_smallcaps" value="small-caps"/>
			</segmented>
			<segmented label="Streg" name="textDecoration" allow-null="true">
				<item icon="style/text_normal" value="none"/>
				<item icon="style/text_decoration_underline" value="underline"/>
				<item icon="style/text_decoration_linethrough" value="line-through"/>
				<item icon="style/text_decoration_overline" value="overline"/>
			</segmented>',
			
		'Billede' =>
			'
			<dropdown label="Billede" name="imageId" width="180">
				<item value="" title=""/>
				'.GuiUtils::buildObjectItems('image').'
			</dropdown>
			<segmented label="Placering" name="imageFloat">
				<item icon="style/float_left" value="left"/>
				<item icon="style/float_right" value="right"/>
			</segmented>
			'
			);
	}
}
?>