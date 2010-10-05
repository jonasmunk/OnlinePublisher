<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Part
 */
require_once($basePath.'Editor/Classes/Parts/Part.php');

Part::$schema['listing'] = array(
	'fields' => array(
		'text'   => array('type'=>'text'),
		'listStyle'   => array('type'=>'text' , 'column' => 'type'),
		'textAlign' => array( 'type' => 'text', 'column' => 'textalign' ),
		'fontFamily' => array( 'type' => 'text', 'column' => 'fontfamily' ),
		'fontSize' => array( 'type' => 'text', 'column' => 'fontsize' ),
		'lineHeight' => array( 'type' => 'text', 'column' => 'lineheight' ),
		'fontWeight' => array( 'type' => 'text', 'column' => 'fontweight' ),
		'color' => array( 'type' => 'text', 'column' => 'color' ),
		'wordSpacing' => array( 'type' => 'text', 'column' => 'wordspacing' ),
		'letterSpacing' => array( 'type' => 'text', 'column' => 'letterspacing' ),
		'textDecoration' => array( 'type' => 'text', 'column' => 'textdecoration' ),
		'textIndent' => array( 'type' => 'text', 'column' => 'textindent' ),
		'textTransform' => array( 'type' => 'text', 'column' => 'texttransform' ),
		'fontStyle' => array( 'type' => 'text', 'column' => 'fontstyle' ),
		'fontVariant' => array( 'type' => 'text', 'column' => 'fontvariant' )
	)
);
class ListingPart extends Part
{
	var $text;
	var $listStyle;
	var $textAlign;
	var $fontFamily;
	var $fontSize;
	var $lineHeight;
	var $fontWeight;
	var $color;
	var $wordSpacing;
	var $letterSpacing;
	var $textDecoration;
	var $textIndent;
	var $textTransform;
	var $fontStyle;
	var $fontVariant;
	
	function ListingPart() {
		parent::Part('listing');
	}
	
	function toUnicode() {
		$this->text = mb_convert_encoding($this->text, "UTF-8","ISO-8859-1");
	}
	
	function load($id) {
		return Part::load('listing',$id);
	}
	
	function setText($text) {
	    $this->text = $text;
	}

	function getText() {
	    return $this->text;
	}
	
	function setListStyle($listStyle) {
	    $this->listStyle = $listStyle;
	}

	function getListStyle() {
	    return $this->listStyle;
	}
	
	
	function setTextAlign($textAlign) {
	    $this->textAlign = $textAlign;
	}

	function getTextAlign() {
	    return $this->textAlign;
	}
	
	function setFontFamily($fontFamily) {
	    $this->fontFamily = $fontFamily;
	}
	
	function getFontFamily() {
	    return $this->fontFamily;
	}
	
	function setFontSize($fontSize) {
	    $this->fontSize = $fontSize;
	}

	function getFontSize() {
	    return $this->fontSize;
	}
	
	function setLineHeight($lineHeight) {
	    $this->lineHeight = $lineHeight;
	}

	function getLineHeight() {
	    return $this->lineHeight;
	}
	
	function setFontWeight($fontWeight) {
	    $this->fontWeight = $fontWeight;
	}

	function getFontWeight() {
	    return $this->fontWeight;
	}
	
	function setColor($color) {
	    $this->color = $color;
	}

	function getColor() {
	    return $this->color;
	}
	
	function setWordSpacing($wordSpacing) {
	    $this->wordSpacing = $wordSpacing;
	}

	function getWordSpacing() {
	    return $this->wordSpacing;
	}
	
	function setLetterSpacing($letterSpacing) {
	    $this->letterSpacing = $letterSpacing;
	}

	function getLetterSpacing() {
	    return $this->letterSpacing;
	}
	
	function setTextDecoration($textDecoration) {
	    $this->textDecoration = $textDecoration;
	}

	function getTextDecoration() {
	    return $this->textDecoration;
	}
	
	function setTextIndent($textIndent) {
	    $this->textIndent = $textIndent;
	}

	function getTextIndent() {
	    return $this->textIndent;
	}
	
	function setTextTransform($textTransform) {
	    $this->textTransform = $textTransform;
	}

	function getTextTransform() {
	    return $this->textTransform;
	}
	
	function setFontStyle($fontStyle) {
	    $this->fontStyle = $fontStyle;
	}

	function getFontStyle() {
	    return $this->fontStyle;
	}
	
	function setFontVariant($fontVariant) {
	    $this->fontVariant = $fontVariant;
	}

	function getFontVariant() {
	    return $this->fontVariant;
	}
	
}
?>