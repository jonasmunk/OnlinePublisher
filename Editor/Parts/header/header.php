<?
/**
 * @package OnlinePublisher
 * @subpackage Parts.Header
 */
require_once($basePath.'Editor/Classes/Parts/LegacyPartController.php');
require_once($basePath.'Editor/Classes/Services/XslService.php');
require_once($basePath.'Editor/Classes/Utilities/StringUtils.php');

class PartHeader extends LegacyPartController {

	var $id;
	
	function PartHeader($id=0) {
		parent::LegacyPartController('header');
		$this->id = $id;
	}
	
	function sub_display($context) {
		return $this->render($context);
	}
	
	function sub_getSectionClass() {
		$sql = "select * from part_header where part_id=".$this->id;
		if ($row = Database::selectFirst($sql)) {
			return 'part_section_header_'.$row['level'];
		} else {
			return '';
		}
	}
	
	function sub_editor($context) {
		$sql = "select * from part_header where part_id=".$this->id;
		if ($row = Database::selectFirst($sql)) {
			return
			'<textarea class="part_header part_header_'.$row['level'].'" name="text" id="PartHeaderTextarea" style="border: 1px solid lightgrey; width: 100%; background: transparent; padding: 0; -webkit-box-sizing: border-box; -moz-box-sizing: border-box; box-sizing: border-box; '.$this->_buildCSSStyle($row).'">'.
			StringUtils::escapeXML($row['text']).
			'</textarea>'.
			'<input type="hidden" name="level" value="'.$row['level'].'"/>'.
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
			'<script type="text/javascript">'.
			'document.getElementById("PartHeaderTextarea").focus();'.
			'document.getElementById("PartHeaderTextarea").select();'.
			'</script>';
		} else {
			return '';
		}
	}
	
	function sub_import(&$node) {
		$xml = '<?xml version="1.0" encoding="ISO-8859-1"?>'.$node->toString();
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

		$level = $node->getAttribute('level');
		$text = XslService::transform($xml,$xsl);
		
		$style = $this->_parseXMLStyle($node->selectNodes('style',1));
		
		$sql = "update part_header set".
		" level=".$level.
		",text=".Database::text($text).
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
		$sql = "select * from part_header where part_id=".$this->id;
		if ($row = Database::selectFirst($sql)) {
			$text = $row['text'];
			$text = StringUtils::escapeSimpleXML($text);
			$text = $context->decorateForBuild($text);
			$text = StringUtils::insertLineBreakTags($text,'<break/>');
			return 
			'<header level="'.$row['level'].'" xmlns="'.$this->_buildnamespace('1.0').'">'.
			$this->_buildXMLStyle($row).
			$text.
			'</header>';
		} else {
			return '';
		}
	}
	
	function sub_index() {
		$sql = "select * from part_header where part_id=".$this->id;
		if ($row = Database::selectFirst($sql)) {
			return $row['text'];
			// TODO: Strip special tags from index
		} else {
			return '';
		}
	}
}
?>