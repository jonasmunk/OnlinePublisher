<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Part
 */
if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

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
				$formatted = $this->_formatBuildText($point[$i],$context,$part);
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

	function _formatBuildText($text,$context,$part) {
		$text = Strings::escapeSimpleXML($text);
		$text = $context->decorateForBuild($text,$part->getId());
		$text = Strings::insertLineBreakTags($text,'<break/>');
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
		$parsed = $this->_parse($part->getText());
		$text = '';
		foreach ($parsed as $line) {
			$text.= join("\n",$line)."\n";
		}
		$context = new PartContext();
		$text = $context->decorateForIndex($text);
		return $text;
	}
	
	function getFromRequest($id) {
		$part = ListingPart::load($id);
		$part->setText(Request::getString('text'));
		if (Request::exists('type')) {
			$part->setListStyle(Request::getString('type'));
			$part->setFontSize(Request::getString('fontSize'));
			$part->setFontFamily(Request::getString('fontFamily'));
			$part->setTextAlign(Request::getString('textAlign'));
			$part->setLineHeight(Request::getString('lineHeight'));
			$part->setFontWeight(Request::getString('fontWeight'));
			$part->setFontStyle(Request::getString('fontStyle'));
			$part->setColor(Request::getString('color'));
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
		Strings::escapeXML($part->getText()).
		'</textarea>'.
		'<input type="hidden" name="type" value="'.Strings::escapeXML($part->getListStyle()).'"/>'.
		'<input type="hidden" name="fontSize" value="'.Strings::escapeXML($part->getFontSize()).'"/>'.
		'<input type="hidden" name="fontFamily" value="'.Strings::escapeXML($part->getFontFamily()).'"/>'.
		'<input type="hidden" name="textAlign" value="'.Strings::escapeXML($part->getTextAlign()).'"/>'.
		'<input type="hidden" name="lineHeight" value="'.Strings::escapeXML($part->getLineHeight()).'"/>'.
		'<input type="hidden" name="fontWeight" value="'.Strings::escapeXML($part->getFontWeight()).'"/>'.
		'<input type="hidden" name="fontStyle" value="'.Strings::escapeXML($part->getFontStyle()).'"/>'.
		'<input type="hidden" name="color" value="'.Strings::escapeXML($part->getColor()).'"/>'.
		'<input type="hidden" name="wordSpacing" value="'.Strings::escapeXML($part->getWordSpacing()).'"/>'.
		'<input type="hidden" name="letterSpacing" value="'.Strings::escapeXML($part->getLetterSpacing()).'"/>'.
		'<input type="hidden" name="textIndent" value="'.Strings::escapeXML($part->getTextIndent()).'"/>'.
		'<input type="hidden" name="textTransform" value="'.Strings::escapeXML($part->getTextTransform()).'"/>'.
		'<input type="hidden" name="fontVariant" value="'.Strings::escapeXML($part->getFontVariant()).'"/>'.
		'<input type="hidden" name="textDecoration" value="'.Strings::escapeXML($part->getTextDecoration()).'"/>'.
		'<script src="'.ConfigurationService::getBaseUrl().'Editor/Parts/listing/script.js" type="text/javascript" charset="utf-8"></script>';
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
		$type='disc';
		if ($listing = DOMUtils::getFirstChildElement($node,'listing')) {
			if (isset($types[$listing->getAttribute('type')])) {
				$type = $types[$listing->getAttribute('type')];
			}
		}
		$this->parseXMLStyle($part,DOMUtils::getFirstDescendant($node,'style'));
		$part->setListStyle($type);
		$part->setText($text);
	}
	
	function getToolbars() {
		return array(
			GuiUtils::getTranslated(array('Bullet list','da'=>'Punktopstilling')) =>
			'
			<field label="{Bullet; da:Symbol}">
				<segmented name="listStyle">
					<item icon="style/list-style-disc" value="disc"/>
					<item icon="style/list-style-square" value="square"/>
					<item icon="style/list-style-circle" value="circle"/>
					<item icon="style/list-style-decimal" value="decimal"/>
					<item icon="style/list-style-lower-alpha" value="lower-alpha"/>
					<item icon="style/list-style-upper-alpha" value="upper-alpha"/>
					<item icon="style/list-style-lower-roman" value="lower-roman"/>
					<item icon="style/list-style-upper-roman" value="upper-roman"/>
				</segmented>
			</field>
			<field label="{Size; da:Størrelse}">
				<style-length-input name="fontSize" width="90"/>
			</field>
			<field label="{Justify; da:Placering}">
				<segmented name="textAlign" allow-null="true">
					<item icon="style/text_align_left" value="left"/>
					<item icon="style/text_align_center" value="center"/>
					<item icon="style/text_align_right" value="right"/>
					<item icon="style/text_align_justify" value="justify"/>
				</segmented>
			</field>
			<divider/>
			<field label="{Font; da:Skrift}">
				<font-input name="fontFamily"/>
			</field>
			<field label="{Line-height; da:Linjehøjde}">
				<style-length-input name="lineHeight" width="90"/>
			</field>
			<field label="{Font; da:Farve}">
				<color-input name="color"/>
			</field>
			<field label="{Weight; da:Fed}">
				<segmented name="fontWeight" allow-null="true">
					<item icon="style/text_normal" value="normal"/>
					<item icon="style/text_bold" value="bold"/>
				</segmented>
			</field>
			<field label="{Italic; da:Kursiv}">
				<segmented name="fontStyle" allow-null="true">
					<item icon="style/text_normal" value="normal"/>
					<item icon="style/text_italic" value="italic"/>
				</segmented>
			</field>
			',
			
		GuiUtils::getTranslated(array('Advanced','da'=>'Avanceret')) =>
			'
			<field label="{Word spacing; da:Ord-mellemrum}">
				<style-length-input name="wordSpacing" width="90"/>
			</field>
			<field label="{Letter spacing; da:Tegn-mellemrum}">
				<style-length-input name="letterSpacing" width="90"/>
			</field>
			<field label="{Indentation; da:Indrykning}">
				<style-length-input name="textIndent" width="90"/>
			</field>
			<field label="{Letters; da:Bogstaver}">
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
			<field label="{Stroke; da:Streg}">
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