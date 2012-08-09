<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Part
 */
if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}
require_once($basePath.'Editor/Classes/Parts/PartController.php');
require_once($basePath.'Editor/Classes/Parts/HeaderPart.php');
require_once($basePath.'Editor/Classes/Utilities/StringUtils.php');

class HeaderPartController extends PartController
{
	function HeaderPartController() {
		parent::PartController('header');
	}
	
	function isLiveEnabled() {
		return true;
	}

	
	function createPart() {
		$part = new HeaderPart();
		$part->setText('Velkommen');
		$part->setLevel(1);
		$part->save();
		return $part;
	}
	
	function getFromRequest($id) {
		$part = HeaderPart::load($id);
		$part->setText(Request::getString('text'));
		// Until Ajax posts all vars
		if (Request::exists('level')) {
			$part->setLevel(Request::getInt('level'));
			$part->setFontSize(Request::getString('fontSize'));
			$part->setFontFamily(Request::getString('fontFamily'));
			$part->setTextAlign(Request::getString('textAlign'));
			$part->setLineHeight(Request::getString('lineHeight'));
			$part->setColor(Request::getString('color'));
			$part->setLetterSpacing(Request::getString('letterSpacing'));
			$part->setFontWeight(Request::getString('fontWeight'));
			$part->setFontStyle(Request::getString('fontStyle'));
			$part->setWordSpacing(Request::getString('wordSpacing'));
			$part->setTextIndent(Request::getString('textIndent'));
			$part->setTextTransform(Request::getString('textTransform'));
			$part->setFontVariant(Request::getString('fontVariant'));
			$part->setTextDecoration(Request::getString('textDecoration'));
		}
		return $part;
	}
	
	function display($part,$context) {
		return $this->render($part,$context);
	}
	
	function editor($part,$context) {
		global $baseUrl;
		return
		'<textarea class="part_header common_font part_header_'.$part->getLevel().'" name="text" id="part_header_textarea" style="border: 1px solid lightgrey; width: 100%; background: transparent; padding: 0; -webkit-box-sizing: border-box; -moz-box-sizing: border-box; box-sizing: border-box; '.$this->buildCSSStyle($part).'">'.
		StringUtils::escapeXML($part->getText()).
		'</textarea>'.
		'<input type="hidden" name="level" value="'.$part->getLevel().'"/>'.
		'<input type="hidden" name="fontSize" value="'.StringUtils::escapeXML($part->getFontSize()).'"/>'.
		'<input type="hidden" name="fontFamily" value="'.StringUtils::escapeXML($part->getFontFamily()).'"/>'.
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
		'<script src="'.$baseUrl.'Editor/Parts/header/script.js" type="text/javascript" charset="utf-8"></script>';
	}
	
	function getIndex($part) {
		$context = new PartContext();
		$text = $part->getText();
		$text = $context->decorateForIndex($text);
		return $text;
	}
	
	function getSectionClass($part) {
		return 'part_section_header_'.$part->getLevel();
	}
	
	function buildSub($part,$context) {
		$text = $part->getText();
		$text = StringUtils::escapeSimpleXML($text);
		$text = $context->decorateForBuild($text,$part->getId());
		$text = StringUtils::insertLineBreakTags($text,'<break/>');
		return 
			'<header level="'.$part->getLevel().'" xmlns="'.$this->getNamespace().'">'.
			$this->buildXMLStyle($part).
			$text.
			'</header>';
	}
	
	function importSub($node,$part) {
		$xml = '<?xml version="1.0" encoding="ISO-8859-1"?>'.DOMUtils::getInnerXML($node);
		$xsl = '<?xml version="1.0" encoding="ISO-8859-1"?>
		<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
		 xmlns:t="http://uri.in2isoft.com/onlinepublisher/part/header/1.0/" exclude-result-prefixes="t">
		<xsl:output method="text" encoding="ISO-8859-1"/>

		<xsl:template match="t:header"><xsl:apply-templates/></xsl:template>
		<xsl:template match="t:break"><xsl:text>'."\n".'</xsl:text></xsl:template>
		<xsl:template match="t:strong">[s]<xsl:apply-templates/>[s]</xsl:template>
		<xsl:template match="t:em">[e]<xsl:apply-templates/>[e]</xsl:template>
		<xsl:template match="t:del">[slet]<xsl:apply-templates/>[slet]</xsl:template>
		<xsl:template match="t:link"><xsl:apply-templates/></xsl:template>

		</xsl:stylesheet>';
		$text = XslService::transform($xml,$xsl);
		if ($header = DOMUtils::getFirstChildElement($node,'header')) {
			$level = intval($header->getAttribute('level'));
		}
		if ($level<1 || $level>6) {
			$level = 1;
		}
		$part->setLevel($level);
		$this->parseXMLStyle($part,DOMUtils::getFirstDescendant($node,'style'));
		
		$part->setText($text);
	}
	
		
	function getToolbars() {
		return array(
			'Overskrift' =>
			'
			<field label="Niveau">
				<segmented name="level">
					<item text="1" value="1"/>
					<item text="2" value="2"/>
					<item text="3" value="3"/>
					<item text="4" value="4"/>
					<item text="5" value="5"/>
					<item text="6" value="6"/>
				</segmented>
			</field>
			<field label="St&#248;rrelse">
				<style-length-input name="fontSize"/>
			</field>
			<field label="Placering">
				<segmented name="textAlign" allow-null="true">
					<item icon="style/text_align_left" value="left"/>
					<item icon="style/text_align_center" value="center"/>
					<item icon="style/text_align_right" value="right"/>
					<item icon="style/text_align_justify" value="justify"/>
				</segmented>
			</field>
			<divider/>
			<field label="Skrift">
				<dropdown name="fontFamily" width="180">
					'.$this->getFontItems().'
				</dropdown>
			</field>
			<field label="Linjeh&#248;jde">
				<style-length-input name="lineHeight"/>
			</field>
			<field label="Farve">
				<color-input name="color"/>
			</field>
			<field label="Fed">
				<segmented name="fontWeight" allow-null="true">
					<item icon="style/text_normal" value="normal"/>
					<item icon="style/text_bold" value="bold"/>
				</segmented>
			</field>
			<field label="Kursiv">
				<segmented name="fontStyle" allow-null="true">
					<item icon="style/text_normal" value="normal"/>
					<item icon="style/text_italic" value="italic"/>
				</segmented>
			</field>
			',
		'Avanceret' =>
			'
			<field label="Ord-mellemrum">
				<style-length-input name="wordSpacing"/>
			</field>
			<field label="Tegn-mellemrum">
				<style-length-input name="letterSpacing"/>
			</field>
			<field label="Indrykning">
				<style-length-input name="textIndent"/>
			</field>
			<field label="Bogstaver">
				<segmented name="textTransform" allow-null="true">
					<item icon="style/text_normal" value="normal"/>
					<item icon="style/text_transform_capitalize" value="capitalize"/>
					<item icon="style/text_transform_uppercase" value="uppercase"/>
					<item icon="style/text_transform_lowercase" value="lowercase"/>
				</segmented>
			</field>
			<field label="Variant">
				<segmented name="fontVariant" allow-null="true">
					<item icon="style/font_variant_normal" value="normal"/>
					<item icon="style/font_variant_smallcaps" value="small-caps"/>
				</segmented>
			</field>
			<field label="Streg">
				<segmented name="textDecoration" allow-null="true">
					<item icon="style/text_normal" value="none"/>
					<item icon="style/text_decoration_underline" value="underline"/>
					<item icon="style/text_decoration_linethrough" value="line-through"/>
					<item icon="style/text_decoration_overline" value="overline"/>
				</segmented>
			</field>
			'
		);
	}
}
?>