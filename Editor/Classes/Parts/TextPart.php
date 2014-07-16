<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Part
 */
if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

Entity::$schema['TextPart'] = array(
	'table' => 'part_text',
	'properties' => array(
		'text'   => array('type'=>'string'),
		'textAlign' => array( 'type' => 'string', 'column' => 'textalign' ),
		'fontFamily' => array( 'type' => 'string', 'column' => 'fontfamily' ),
		'fontSize' => array( 'type' => 'string', 'column' => 'fontsize' ),
		'lineHeight' => array( 'type' => 'string', 'column' => 'lineheight' ),
		'fontWeight' => array( 'type' => 'string', 'column' => 'fontweight' ),
		'color' => array( 'type' => 'string', 'column' => 'color' ),
		'wordSpacing' => array( 'type' => 'string', 'column' => 'wordspacing' ),
		'letterSpacing' => array( 'type' => 'string', 'column' => 'letterspacing' ),
		'textDecoration' => array( 'type' => 'string', 'column' => 'textdecoration' ),
		'textIndent' => array( 'type' => 'string', 'column' => 'textindent' ),
		'textTransform' => array( 'type' => 'string', 'column' => 'texttransform' ),
		'fontStyle' => array( 'type' => 'string', 'column' => 'fontstyle' ),
		'fontVariant' => array( 'type' => 'string', 'column' => 'fontvariant' ),
		'imageId' => array('type'=>'int', 'column' => 'image_id', 'relation'=>array('class'=>'Image','property'=>'id')),
		'imageFloat' => array( 'type' => 'string', 'column' => 'imagefloat' ),
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
		
	static function load($id) {
		return Part::get('text',$id);
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