<?
/**
 * @package OnlinePublisher
 * @subpackage Parts.Listing
 */
require_once($basePath.'Editor/Classes/Parts/LegacyPartController.php');
require_once($basePath.'Editor/Classes/Services/XslService.php');
require_once($basePath.'Editor/Classes/Utilities/StringUtils.php');

class PartListing extends LegacyPartController {

	var $id;

	function PartListing($id=0) {
		parent::LegacyPartController('listing');
		$this->id = $id;
	}

	function sub_display($context) {
		return $this->render($context);
	}

	function _formatBuildText($text,$context) {
		$text = StringUtils::escapeSimpleXML($text);
		$text = $context->decorateForBuild($text);
		$text = StringUtils::insertLineBreakTags($text,'<break/>');
		return $text;
	}
	
	function sub_editor($context) {
		$sql = "select * from part_listing where part_id=".$this->id;
		if ($row = Database::selectFirst($sql)) {
			return
			'<textarea class="part_listing common_font" name="text" id="PartListingTextarea" style="border: 1px solid lightgrey; width: 100%; height: 200px; background: transparent;'.$this->_buildCSSStyle($row).'">'.
			encodeXML($row['text']).
			'</textarea>'.
			'<input type="hidden" name="type" value="'.encodeXML($row['type']).'"/>'.
			'<input type="hidden" name="fontSize" value="'.encodeXML($row['fontsize']).'"/>'.
			'<input type="hidden" name="fontFamily" value="'.encodeXML($row['fontfamily']).'"/>'.
			'<input type="hidden" name="textAlign" value="'.encodeXML($row['textalign']).'"/>'.
			'<input type="hidden" name="lineHeight" value="'.encodeXML($row['lineheight']).'"/>'.
			'<input type="hidden" name="fontWeight" value="'.encodeXML($row['fontweight']).'"/>'.
			'<input type="hidden" name="fontStyle" value="'.encodeXML($row['fontstyle']).'"/>'.
			'<input type="hidden" name="color" value="'.encodeXML($row['color']).'"/>'.
			'<input type="hidden" name="wordSpacing" value="'.encodeXML($row['wordspacing']).'"/>'.
			'<input type="hidden" name="letterSpacing" value="'.encodeXML($row['letterspacing']).'"/>'.
			'<input type="hidden" name="textIndent" value="'.encodeXML($row['textindent']).'"/>'.
			'<input type="hidden" name="textTransform" value="'.encodeXML($row['texttransform']).'"/>'.
			'<input type="hidden" name="fontVariant" value="'.encodeXML($row['fontvariant']).'"/>'.
			'<input type="hidden" name="textDecoration" value="'.encodeXML($row['textdecoration']).'"/>'.
			'<script type="text/javascript">'.
			'document.getElementById("PartListingTextarea").focus();'.
			'document.getElementById("PartListingTextarea").select();'.
			'</script>';
		} else {
			return '';
		}
	}
	
	function sub_update() {
		$text = requestPostText('text');
		$type = requestPostText('type');
		$fontSize = requestPostText('fontSize');
		$fontFamily = requestPostText('fontFamily');
		$textAlign = requestPostText('textAlign');
		$lineHeight = requestPostText('lineHeight');
		$color = requestPostText('color');
		$fontWeight = requestPostText('fontWeight');
		$fontStyle = requestPostText('fontStyle');
		$wordSpacing = requestPostText('wordSpacing');
		$letterSpacing = requestPostText('letterSpacing');
		$textIndent = requestPostText('textIndent');
		$textTransform = requestPostText('textTransform');
		$fontVariant = requestPostText('fontVariant');
		$textDecoration = requestPostText('textDecoration');
		
		
		$sql = "update part_listing set".
		" text=".Database::text($text).
		",type=".Database::text($type).
		",fontsize=".Database::text($fontSize).
		",fontfamily=".Database::text($fontFamily).
		",textalign=".Database::text($textAlign).
		",lineheight=".Database::text($lineHeight).
		",color=".Database::text($color).
		",fontweight=".Database::text($fontWeight).
		",fontstyle=".Database::text($fontStyle).
		",wordspacing=".Database::text($wordSpacing).
		",letterspacing=".Database::text($letterSpacing).
		",textindent=".Database::text($textIndent).
		",texttransform=".Database::text($textTransform).
		",fontvariant=".Database::text($fontVariant).
		",textdecoration=".Database::text($textDecoration).
		" where part_id=".$this->id;
		Database::insert($sql);
	}
	
	function sub_build($context) {
		$data = '';
		$sql = "select * from part_listing where part_id=".$this->id;
		if ($row = Database::selectFirst($sql)) {
			$data.='<listing xmlns="'.$this->_buildnamespace('1.0').'">'.
			$this->_buildXMLStyle($row).
			'<list type="'.$row['type'].'">';
			$parsed = $this->_parse($row['text']);
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
		}
		return $data;
	}

	function sub_index() {
		$sql = "select * from part_listing where part_id=".$this->id;
		if ($row = Database::selectFirst($sql)) {
			return $row['text'];
			// TODO: Strip special tags from index
		} else {
			return '';
		}
	}

		function sub_import(&$node) {
			$xml = '<?xml version="1.0" encoding="ISO-8859-1"?>'.$node->toString();
			
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
			// Fix line feeds
			$text = str_replace("\n","\r\n",$text);
			
			$type = $node->childNodes[1]->getAttribute('type');

			$style = $this->_parseXMLStyle($node->selectNodes('style',1));

			$sql = "update part_listing set".
			" text=".Database::text($text).
			",type=".Database::text($type).
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
	
	
	//////////////////// Support methods ///////////////////
	
	function _parse($list) {
		$list="\r\n".$list;
		$items = preg_split("/\r\n\*/",$list);
		$parsed = array();
		for ($i=1;$i<count($items);$i++) {
			$item=$items[$i];
			$lines=preg_split("/\r\n/",$item);
			$parsed[]=$lines;
		}
		return $parsed;
	}
}
?>