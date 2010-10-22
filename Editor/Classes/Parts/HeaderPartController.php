<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Part
 */
require_once($basePath.'Editor/Classes/Parts/PartController.php');
require_once($basePath.'Editor/Classes/Parts/HeaderPart.php');
require_once($basePath.'Editor/Classes/Utilities/StringUtils.php');

class HeaderPartController extends PartController
{
	function HeaderPartController() {
		parent::PartController('header');
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
		$part->setText(Request::getEncodedString('text'));
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
		return
		'<textarea class="part_header part_header_'.$part->getLevel().'" name="text" id="PartHeaderTextarea" style="border: 1px solid lightgrey; width: 100%; background: transparent; padding: 0; -webkit-box-sizing: border-box; -moz-box-sizing: border-box; box-sizing: border-box; '.$this->buildCSSStyle($part).'">'.
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
		'<script type="text/javascript">'.
		'document.getElementById("PartHeaderTextarea").focus();'.
		'document.getElementById("PartHeaderTextarea").select();'.
		'</script>';
	}
	
	function getIndex($part) {
		// TODO Strip tags etc.
		return $part->getText();
	}
	
	function getSectionClass($part) {
		return 'part_section_header_'.$part->getLevel();
	}
	
	function buildSub($part,$context) {
		$text = $part->getText();
		$text = StringUtils::escapeSimpleXML($text);
		$text = $context->decorateForBuild($text);
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
			'Punktopstilling' =>
			'
			<dropdown label="Niveau" name="level">
				<item value="1" title="Niveau 1"/>
				<item value="2" title="Niveau 2"/>
				<item value="3" title="Niveau 3"/>
				<item value="4" title="Niveau 4"/>
				<item value="5" title="Niveau 5"/>
				<item value="6" title="Niveau 6"/>
			</dropdown>
			<style-length label="St&#248;rrelse" name="fontSize"/>
			<segmented label="Placering" name="textAlign" allow-null="true">
				<item icon="style/text_align_left" value="left"/>
				<item icon="style/text_align_center" value="center"/>
				<item icon="style/text_align_right" value="right"/>
				<item icon="style/text_align_justify" value="justify"/>
			</segmented>
			<divider/>
			<dropdown label="Skrift" name="fontFamily" width="180">
				<item value="" title=""/>
				<item value="sans-serif" title="*Sans-serif*"/>
				<item value="Verdana,sans-serif" title="Verdana"/>
				<item value="Tahoma,Geneva,sans-serif" title="Tahoma"/>
				<item value="Trebuchet MS,Helvetica,sans-serif" title="Trebuchet"/>
				<item value="Geneva,Tahoma,sans-serif" title="Geneva"/>
				<item value="Helvetica,sans-serif" title="Helvetica"/>
				<item value="Arial,Helvetica,sans-serif" title="Arial"/>
				<item value="Arial Black,Gadget,Arial,sans-serif" title="Arial Black"/>
				<item value="Impact,Charcoal,Arial Black,Gadget,Arial,sans-serif" title="Impact"/>
				<item value="serif" title="*Serif*"/>
				<item value="Times New Roman,Times,serif" title="Times New Roman"/>
				<item value="Times,Times New Roman,serif" title="Times"/>
				<item value="Book Antiqua,Palatino,serif" title="Book Antiqua"/>
				<item value="Palatino,Book Antiqua,serif" title="Palatino"/>
				<item value="Georgia,Book Antiqua,Palatino,serif" title="Georgia"/>
				<item value="Garamond,Times New Roman,Times,serif" title="Garamond"/>
				<item value="cursive" title="*Kursiv*"/>
				<item value="Comic Sans MS,cursive" title="Comic Sans"/>
				<item value="monospace" title="*Monospace*"/>
				<item value="Courier New,Courier,monospace" title="Courier New"/>
				<item value="Courier,Courier New,monospace" title="Courier"/>
				<item value="Lucida Console,Monaco,monospace" title="Lucida Console"/>
				<item value="Monaco,Lucida Console,monospace" title="Monaco"/>
				<item value="fantasy" title="*Fantasi*"/>
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
			</segmented>
			'
			);
	}
}
?>