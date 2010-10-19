<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Part
 */
require_once($basePath.'Editor/Classes/Parts/PartController.php');
require_once($basePath.'Editor/Classes/Parts/ListingPart.php');
require_once($basePath.'Editor/Classes/Utilities/StringUtils.php');

class ListingPartController extends PartController
{
	function ListingPartController() {
		parent::PartController('listing');
	}
	
	function createPart() {
		$part = new ListingPart();
		$part->setText("* Punkt 1\n* Punkt 2");
		$part->setListStyle('disc');
		$part->save();
		return $part;
	}
	
	function display($part,$context) {
		return $this->render($part,$context);
	}
	
	function buildSub($part,$context) {
		$data = '<listing xmlns="'.$this->getNamespace().'">'.
		$this->buildXMLStyle($part).
		'<list type="'.$part->getListStyle().'">';
		$parsed = $this->_parse($part->getText());
		foreach ($parsed as $point) {
			$data.='<item>';
			$lines = count($point);
			for ($i=0;$i<$lines;$i++) {
				$formatted = $this->_formatBuildText($point[$i],$context);
				if ($i>0) {
					$data.='<break/>'.$formatted;
				} else {
					$data.='<first>'.$formatted.'</first>';
				}
			}
			$data.='</item>';
		}
		$data.='</list>';
		$data.='</listing>';
		return $data;
	}

	function _formatBuildText($text,$context) {
		$text = StringUtils::escapeSimpleXML($text);
		$text = $context->decorateForBuild($text);
		$text = StringUtils::insertLineBreakTags($text,'<break/>');
		return $text;
	}

	function _parse($list) {
		$list = str_replace("\r\n","\n",$list);
		$list="\n".$list;
		$items = preg_split("/\n\*/",$list);
		$parsed = array();
		for ($i=1;$i<count($items);$i++) {
			$item=$items[$i];
			$lines=preg_split("/\n/",$item);
			$parsed[]=$lines;
		}
		return $parsed;
	}
	
	function getIndex($part) {
		// TODO Strip tags etc.
		return $part->getText();
	}
	
	function getFromRequest($id) {
		$part = ListingPart::load($id);
		$part->setText(Request::getEncodedString('text'));
		if (Request::exists('type')) {
			$part->setListStyle(Request::getString('type'));
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
		}
		return $part;
	}
	
	function editor($part,$context) {
		return
		'<textarea class="part_listing common_font" name="text" id="PartListingTextarea" style="border: 1px solid lightgrey; width: 100%; height: 200px; background: transparent; padding: 0; -webkit-box-sizing: border-box; -moz-box-sizing: border-box; box-sizing: border-box;'.$this->buildCSSStyle($part).'">'.
		StringUtils::escapeXML($part->getText()).
		'</textarea>'.
		'<input type="hidden" name="type" value="'.StringUtils::escapeXML($part->getListStyle()).'"/>'.
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
		'document.getElementById("PartListingTextarea").focus();'.
		'document.getElementById("PartListingTextarea").select();'.
		'</script>';
	}
	
	function importSub($node,$part) {
		$xml = '<?xml version="1.0" encoding="ISO-8859-1"?>'.DOMUtils::getInnerXML($node);
		$xsl = '<?xml version="1.0" encoding="ISO-8859-1"?>
		<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
		 xmlns:t="http://uri.in2isoft.com/onlinepublisher/part/listing/1.0/" exclude-result-prefixes="t">
		<xsl:output method="text" encoding="ISO-8859-1"/>

		<xsl:template match="t:listing"><xsl:apply-templates/></xsl:template>
		<xsl:template match="t:break"><xsl:text>'."\n".'</xsl:text></xsl:template>
		<xsl:template match="t:strong">[s]<xsl:apply-templates/>[s]</xsl:template>
		<xsl:template match="t:em">[e]<xsl:apply-templates/>[e]</xsl:template>
		<xsl:template match="t:del">[slet]<xsl:apply-templates/>[slet]</xsl:template>
		<xsl:template match="t:link"><xsl:apply-templates/></xsl:template>
		<xsl:template match="t:item"><xsl:if test="position()>1">'."<xsl:text>\n</xsl:text>".'</xsl:if>*<xsl:apply-templates/></xsl:template>

		</xsl:stylesheet>';
		$text = XslService::transform($xml,$xsl);
		$text = str_replace("\n","\r\n",$text);
		
		$types = array(
			'disc' => 'disc',
			'square' => 'square',
			'circle' => 'circle',
			'decimal' => 'decimal',
			'lower-alpha' => 'lower-alpha',
			'upper-alpha' => 'upper-alpha',
			'lower-roman' => 'lower-roman',
			'upper-roman' => 'upper-roman',
			'1' => 'decimal',
			'a' => 'lower-alpha',
			'A' => 'upper-alpha',
			'i' => 'lower-roman',
			'I' => 'upper-roman'
		);
		if ($listing = DOMUtils::getFirstChildElement($node,'listing')) {
			$type = $types[$listing->getAttribute('type')];
		}
		if (!$type) {
			$type='disc';
		}
		$this->parseXMLStyle($part,DOMUtils::getFirstDescendant($node,'style'));
		$part->setListStyle($type);
		$part->setText($text);
	}
	
	function getToolbars() {
		return array(
			'Punktopstilling' =>
			'
			<segmented label="Symbol" name="listStyle">
				<item icon="style/list-style-disc" value="disc"/>
				<item icon="style/list-style-square" value="square"/>
				<item icon="style/list-style-circle" value="circle"/>
				<item icon="style/list-style-decimal" value="decimal"/>
				<item icon="style/list-style-lower-alpha" value="lower-alpha"/>
				<item icon="style/list-style-upper-alpha" value="upper-alpha"/>
				<item icon="style/list-style-lower-roman" value="lower-roman"/>
				<item icon="style/list-style-upper-roman" value="upper-roman"/>
			</segmented>
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
			<segmented label="Variant" name="textDecoration" allow-null="true">
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