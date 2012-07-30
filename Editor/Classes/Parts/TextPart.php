<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Part
 */
if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}
require_once($basePath.'Editor/Classes/Parts/Part.php');

Part::$schema['text'] = array(
	'fields' => array(
		'text'   => array('type'=>'text'),
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
		'fontVariant' => array( 'type' => 'text', 'column' => 'fontvariant' ),
		'imageId' => array( 'type' => 'int', 'column' => 'image_id' ),
		'imageFloat' => array( 'type' => 'text', 'column' => 'imagefloat' ),
		'imageWidth' => array( 'type' => 'int', 'column' => 'imagewidth' ),
		'imageHeight' => array( 'type' => 'int', 'column' => 'imageheight' )
	)
);
class TextPart extends Part
{
	var $text;
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
	var $imageId;
	var $imageFloat;
	var $imageWidth;
	var $imageHeight;
	
	function TextPart() {
		parent::Part('text');
	}
	
	function toUnicode() {
		$this->text = mb_convert_encoding($this->text, "UTF-8","ISO-8859-1");
	}
	
	function load($id) {
		return Part::load('text',$id);
	}
	
	function setText($text) {
	    $this->text = $text;
	}

	function getText() {
	    return $this->text;
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
	
	function setImageId($imageId) {
	    $this->imageId = $imageId;
	}

	function getImageId() {
	    return $this->imageId;
	}
	
	function setImageFloat($imageFloat) {
	    $this->imageFloat = $imageFloat;
	}

	function getImageFloat() {
	    return $this->imageFloat;
	}
	
	function setImageWidth($imageWidth) {
	    $this->imageWidth = $imageWidth;
	}

	function getImageWidth() {
	    return $this->imageWidth;
	}
	
	function setImageHeight($imageHeight) {
	    $this->imageHeight = $imageHeight;
	}

	function getImageHeight() {
	    return $this->imageHeight;
	}
	
}
?>