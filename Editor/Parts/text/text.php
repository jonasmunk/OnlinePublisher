<?
/**
 * @package OnlinePublisher
 * @subpackage Parts.Text
 */
require_once($basePath.'Editor/Classes/Parts/LegacyPartController.php');
require_once($basePath.'Editor/Classes/Services/XslService.php');
require_once($basePath.'Editor/Classes/Utilities/StringUtils.php');

class PartText extends LegacyPartController { 

	var $id;
	
	function PartText($id=0) {
		parent::LegacyPartController('text');
		$this->id = $id;
	}
	
	function sub_display($context) {
		return $this->render($context);
	}
	
	function sub_editor($context) {
		$sql = "select * from part_text where part_id=".$this->id;
		if ($row = Database::selectFirst($sql)) {
			return
			'<textarea class="part_text common_font" name="text" id="PartTextTextarea" style="border: 1px solid lightgrey; width: 100%; height: 200px; background: transparent;'.$this->_buildCSSStyle($row).'">'.
			StringUtils::escapeXML($row['text']).
			'</textarea>'.
			'<input type="hidden" name="fontSize" value="'.StringUtils::escapeXML($row['fontsize']).'"/>'.
			'<input type="hidden" name="fontFamily" value="'.StringUtils::escapeXML($row['fontfamily']).'"/>'.
			'<input type="hidden" name="textAlign" value="'.StringUtils::escapeXML($row['textalign']).'"/>'.
			'<input type="hidden" name="lineHeight" value="'.StringUtils::escapeXML($row['lineheight']).'"/>'.
			'<input type="hidden" name="fontWeight" value="'.StringUtils::escapeXML($row['fontweight']).'"/>'.
			'<input type="hidden" name="fontStyle" value="'.StringUtils::escapeXML($row['fontstyle']).'"/>'.
			'<input type="hidden" name="color" value="'.StringUtils::escapeXML($row['color']).'"/>'.
			'<input type="hidden" name="wordSpacing" value="'.StringUtils::escapeXML($row['wordspacing']).'"/>'.
			'<input type="hidden" name="letterSpacing" value="'.StringUtils::escapeXML($row['letterspacing']).'"/>'.
			'<input type="hidden" name="textIndent" value="'.StringUtils::escapeXML($row['textindent']).'"/>'.
			'<input type="hidden" name="textTransform" value="'.StringUtils::escapeXML($row['texttransform']).'"/>'.
			'<input type="hidden" name="fontVariant" value="'.StringUtils::escapeXML($row['fontvariant']).'"/>'.
			'<input type="hidden" name="textDecoration" value="'.StringUtils::escapeXML($row['textdecoration']).'"/>'.
			'<input type="hidden" name="imageId" value="'.StringUtils::escapeXML($row['image_id']).'"/>'.
			'<input type="hidden" name="imageFloat" value="'.StringUtils::escapeXML($row['imagefloat']).'"/>'.
			'<script type="text/javascript">'.
			'document.getElementById("PartTextTextarea").focus();'.
			'document.getElementById("PartTextTextarea").select();'.
			'</script>';
		} else {
			return '';
		}
	}
	
	function sub_update() {
		if ($part = TextPart::load($this->id)) {
			$part->setText(Request::getString('text'));
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
			$part->save();
		}
	}
	
	function sub_import(&$node) {
		$xml = '<?xml version="1.0" encoding="ISO-8859-1"?>'.$node->toString();
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
		
		$style = $this->_parseXMLStyle($node->selectNodes('style',1));
		
		$sql = "update part_text set".
		" text=".Database::text($text).
		",textalign=".Database::text($style['textalign']).
		",fontsize=".Database::text($style['fontsize']).
		",fontfamily=".Database::text($style['fontfamily']).
		",lineheight=".Database::text($style['lineheight']).
		",fontweight=".Database::text($style['fontweight']).
		",wordspacing=".Database::text($style['wordspacing']).
		",letterspacing=".Database::text($style['letterspacing']).
		",textdecoration=".Database::text($style['textdecoration']).
		",textindent=".Database::text($style['textindent']).
		",texttransform=".Database::text($style['texttransform']).
		",fontstyle=".Database::text($style['fontstyle']).
		",fontvariant=".Database::text($style['fontvariant']).
		",color=".Database::text($style['color']).
		" where part_id=".$this->id;
		Database::update($sql);
	}
	
	function sub_build($context) {
		$sql = "select * from part_text where part_id=".$this->id;
		if ($row = Database::selectFirst($sql)) {
			$text = $row['text'];
			$text = StringUtils::escapeSimpleXML($text);
			$text = $context->decorateForBuild($text);
			// Important that line breaks are after decorate
			$text = StringUtils::insertLineBreakTags($text,'<break/>');
			
			$text = str_replace('<break/><break/>', '</p><p>', $text);;
			return 
			'<text xmlns="'.$this->_buildnamespace('1.0').'">'.
			$this->_buildXMLStyle($row).
			$this->_buildImage($row).
			'<p>'.$text.'</p>'.
			'</text>';
		} else {
			return '';
		}
	}
	
	function _buildImage($row) {
		if ($row['image_id']>0) {
			$sql = "select data from object where id=".$row['image_id'];
			if ($row2 = Database::selectFirst($sql)) {
				return '<image float="'.StringUtils::escapeSimpleXML($row['imagefloat']).'">'.
				$row2['data'].
				'</image>';
			}
		}
		return '';
	}
	
	function sub_index() {
		if ($part = TextPart::load($this->id)) {
			return $part->getText();
			// TODO: Strip special tags from index
		} else {
			return '';
		}
	}
	
	// Toolbar stuff
	
	function isIn2iGuiEnabled() {
		return true;
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