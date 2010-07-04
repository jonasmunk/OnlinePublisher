<?
/**
 * @package OnlinePublisher
 * @subpackage Parts.Listing
 */
require_once($basePath.'Editor/Classes/Part.php');

class PartListing extends Part {

	var $id;

	function PartListing($id=0) {
		parent::Part('listing');
		$this->id = $id;
	}

	function sub_display($context) {
		$data='';
		$sql = "select * from part_listing where part_id=".$this->id;
		if ($row = Database::selectFirst($sql)) {
			if ($row['type']=='disc' || $row['type']=='circle' || $row['type']=='square') {
				$tag='ul';
			} else {
				$tag='ol';
			}
			$data.= '<div class="part_listing"><'.$tag.' type="'.$row['type'].'" style="'.$this->_buildCSSStyle($row).'">';
			$parsed = $this->_parse($row['text']);
			foreach ($parsed as $point) {
				$data.='<li><span>';
				$lines = count($point);
				for ($i=0;$i<$lines;$i++) {
					$formatted = $this->_formatDisplayText($point[$i],$context);
					if ($i>0) {
						$data.='<br/>'.$formatted;
					} else {
						$data.='<span class="part_listing_first">'.$formatted.'</span>';
					}
				}
				$data.='</span></li>';
			}
			$data.= '</'.$tag.'></div>';
		}
		return $data;
	}
	
	function _formatDisplayText($text,$context) {
		$text = escapeHTML($text);
		$text = $context->decorateForDisplay($text);
		$text = insertLineBreakTags($text,'<br/>');
		return $text;
	}

	function _formatBuildText($text,$context) {
		$text = escapeXML($text);
		$text = $context->decorateForBuild($text);
		$text = insertLineBreakTags($text,'<break/>');
		return $text;
	}
	
	function sub_editor($context) {
		$sql = "select * from part_listing where part_id=".$this->id;
		if ($row = Database::selectFirst($sql)) {
			return
			'<textarea class="part_listing" name="text" id="PartListingTextarea" style="border: 1px solid lightgrey; width: 100%; height: 200px; background: transparent;'.$this->_buildCSSStyle($row).'">'.
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
	
	function sub_create() {
		$sql = "insert into part_listing (part_id,type) values (".$this->id.",'disc')";
		Database::insert($sql);
	}
	
	function sub_delete() {
		$sql = "delete from part_listing where part_id=".$this->id;
		Database::delete($sql);
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

			$text = transformXml($xml,$xsl);
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
	// Toolbar stuff
	
	function getToolbarTabs() {
		return array(
				 'text' => array('title' => 'Punktopstilling')
				,'advanced' => array('title' => 'Avanceret')
			);
	}
	
	function getToolbarDefaultTab() {
		return 'text';
	}
	
	function getToolbarContent($tab) {
		if ($tab=='text') {
			return $this->_textTab();
		} elseif ($tab=='advanced') {
			return $this->_advancedTab();
		} else {
			return '';
		}
	}
	
	function _textTab() {
		return
		'<list-type xmlns="uri:Style" title="Type" value="disc" object="ListType" onchange="updateListType();"/>'.
		'<size xmlns="uri:Style" title="Størrelse" object="FontSize" onchange="updateFontSize();"/>'.
		'<font-family xmlns="uri:Style" title="Skrift" object="FontFamily" onchange="updateFontFamily();">'.
		$this->fontFamilyOptions().
		'</font-family>'.
		'<text-align xmlns="uri:Style" title="Justering" object="TextAlign" onchange="updateTextAlign();"/>'.
		'<size xmlns="uri:Style" title="Linjehøjde" object="LineHeight" onchange="updateLineHeight();"/>'.
		'<color xmlns="uri:Style" title="Farve" object="Color" onchange="updateColor();"/>'.
		'<font-weight xmlns="uri:Style" title="Tykkelse" object="FontWeight" onchange="updateFontWeight();"/>'.
		'<font-style xmlns="uri:Style" title="Kursiv" object="FontStyle" onchange="updateFontStyle();"/>'.
		'<script xmlns="uri:Script">
		function updateListType() {
			formula.type.value=ListType.getValue();
		}
		function updateFontSize() {
			var value = FontSize.getValue();
			formula.fontSize.value=value;
			formula.text.style.fontSize = value;
		}
		function updateColor() {
			var colorValue = Color.getValue();
			formula.color.value=colorValue;
			formula.text.style.color = colorValue;
		}
		function updateFontFamily() {
			var fontFamilyValue = FontFamily.getValue();
			formula.fontFamily.value= fontFamilyValue;
			formula.text.style.fontFamily = fontFamilyValue;
		}
		function updateTextAlign() {
			var textAlignValue = TextAlign.getValue();
			formula.textAlign.value= textAlignValue;
			formula.text.style.textAlign = textAlignValue;
		}
		function updateLineHeight() {
			var lineHeightValue = LineHeight.getValue();
			formula.lineHeight.value= lineHeightValue;
			formula.text.style.lineHeight = lineHeightValue;
		}
		function updateFontWeight() {
			var fontWeightValue = FontWeight.getValue();
			formula.fontWeight.value= fontWeightValue;
			formula.text.style.fontWeight = fontWeightValue;
		}
		function updateFontStyle() {
			var fontStyleValue = FontStyle.getValue();
			formula.fontStyle.value= fontStyleValue;
			formula.text.style.fontStyle = fontStyleValue;	
		}
		function updateThis() {
			ListType.setValue(formula.type.value);
			FontSize.setValue(formula.fontSize.value);
			FontFamily.setValue(formula.fontFamily.value);
			Color.setValue(formula.color.value);
			TextAlign.setValue(formula.textAlign.value);
			LineHeight.setValue(formula.lineHeight.value);
			FontWeight.setValue(formula.fontWeight.value);
			FontStyle.setValue(formula.fontStyle.value);
		}
		updateThis();
		</script>';
	}
	
	
	function _advancedTab() {
		return
		'<size xmlns="uri:Style" title="Ord-mellemrum" object="WordSpacing" onchange="updateWordSpacing();"/>'.
		'<size xmlns="uri:Style" title="Tegn-mellemrum" object="LetterSpacing" onchange="updateLetterSpacing();"/>'.
		'<size xmlns="uri:Style" title="Indrykning" object="TextIndent" onchange="updateTextIndent();"/>'.
		'<text-transform xmlns="uri:Style" title="Bogstaver" object="TextTransform" onchange="updateTextTransform();"/>'.
		'<font-variant xmlns="uri:Style" title="Variant" object="FontVariant" onchange="updateFontVariant();"/>'.
		'<text-decoration xmlns="uri:Style" title="Streg" object="TextDecoration" onchange="updateTextDecoration();"/>'.
		'<script xmlns="uri:Script">
		function updateWordSpacing() {
			var value = WordSpacing.getValue();
			formula.wordSpacing.value=value;
			formula.text.style.wordSpacing = value;
		}
		function updateLetterSpacing() {
			var value = LetterSpacing.getValue();
			formula.letterSpacing.value=value;
			formula.text.style.letterSpacing = value;
		}
		function updateTextIndent() {
			var value = TextIndent.getValue();
			formula.textIndent.value= value;
			formula.text.style.textIndent = value;
		}
		function updateTextTransform() {
			var value = TextTransform.getValue();
			formula.textTransform.value= value;
			formula.text.style.textTransform = value;
		}
		function updateFontVariant() {
			var value = FontVariant.getValue();
			formula.fontVariant.value= value;
			formula.text.style.fontVariant = value;
		}
		function updateTextDecoration() {
			var value = TextDecoration.getValue();
			formula.textDecoration.value= value;
			formula.text.style.textDecoration = value;
		}
		function updateThis() {
			WordSpacing.setValue(formula.wordSpacing.value);
			LetterSpacing.setValue(formula.letterSpacing.value);
			TextIndent.setValue(formula.textIndent.value);
			TextTransform.setValue(formula.textTransform.value);
			FontVariant.setValue(formula.fontVariant.value);
			TextDecoration.setValue(formula.textDecoration.value);
		}
		updateThis();
		</script>';
	}
	
	function fontFamilyOptions() {
		return
		'<font value="sans-serif" title="*Sans-serif*"/>'.
		'<font value="Verdana,sans-serif" title="Verdana"/>'.
		'<font value="Tahoma,Geneva,sans-serif" title="Tahoma"/>'.
		'<font value="Trebuchet MS,Helvetica,sans-serif" title="Trebuchet"/>'.
		'<font value="Geneva,Tahoma,sans-serif" title="Geneva"/>'.
		'<font value="Helvetica,sans-serif" title="Helvetica"/>'.
		'<font value="Arial,Helvetica,sans-serif" title="Arial"/>'.
		'<font value="Arial Black,Gadget,Arial,sans-serif" title="Arial Black"/>'.
		'<font value="Impact,Charcoal,Arial Black,Gadget,Arial,sans-serif" title="Impact"/>'.
		'<font value="serif" title="*Serif*"/>'.
		'<font value="Times New Roman,Times,serif" title="Times New Roman"/>'.
		'<font value="Times,Times New Roman,serif" title="Times"/>'.
		'<font value="Book Antiqua,Palatino,serif" title="Book Antiqua"/>'.
		'<font value="Palatino,Book Antiqua,serif" title="Palatino"/>'.
		'<font value="Georgia,Book Antiqua,Palatino,serif" title="Georgia"/>'.
		'<font value="Garamond,Times New Roman,Times,serif" title="Garamond"/>'.
		'<font value="cursive" title="*Kursiv*"/>'.
		'<font value="Comic Sans MS,cursive" title="Comic Sans"/>'.
		'<font value="monospace" title="*Monospace*"/>'.
		'<font value="Courier New,Courier,monospace" title="Courier New"/>'.
		'<font value="Courier,Courier New,monospace" title="Courier"/>'.
		'<font value="Lucida Console,Monaco,monospace" title="Lucida Console"/>'.
		'<font value="Monaco,Lucida Console,monospace" title="Monaco"/>'.
		'<font value="fantasy" title="*Fantasi*"/>'
		;
	}
}
?>