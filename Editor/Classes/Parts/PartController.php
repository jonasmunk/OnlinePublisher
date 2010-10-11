<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Part
 */
require_once($basePath.'Editor/Classes/Services/XslService.php');
require_once($basePath.'Editor/Classes/Services/PartService.php');
require_once($basePath.'Editor/Classes/Utilities/StringUtils.php');
require_once($basePath.'Editor/Classes/PartContext.php');

class PartController
{
	var $type;
	
	function PartController($type) {
		$this->type = $type;
	}
	
	function build($part,$context) {
		$xml = '<part xmlns="http://uri.in2isoft.com/onlinepublisher/part/1.0/" type="'.$part->getType().'" id="'.$part->getId().'">'.
		'<sub>';
		if (method_exists($this,'buildSub')) {
			$xml.=$this->buildSub($part,$context);
		}
		$xml.=
		'</sub>'.
		'</part>';
		return $xml;
	}
	
	function getNamespace($version='1.0') {
		return 'http://uri.in2isoft.com/onlinepublisher/part/'.$this->type.'/'.$version.'/';
	}
	
	function getNewPart() {
		Log::debug('You must override getNewPart');
	}
	
	function render($part,$context,$editor=true) {
		global $basePath;
		$xmlData = '<?xml version="1.0" encoding="ISO-8859-1"?>'.$this->build($part,$context);
		
		$xslData='<?xml version="1.0" encoding="UTF-8"?>'.
		'<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">'.
		'<xsl:output method="html" indent="no" encoding="UTF-8"/>'.
		'<xsl:include href="'.$basePath.'style/basic/xslt/util.xsl"/>'.
		'<xsl:include href="'.$basePath.'style/basic/xslt/part_'.$this->type.'.xsl"/>'.
		'<xsl:variable name="design"></xsl:variable>'.
		'<xsl:variable name="path">../../../</xsl:variable>'.
		'<xsl:variable name="navigation-path"></xsl:variable>'.
		'<xsl:variable name="page-path"></xsl:variable>'.
		'<xsl:variable name="template"></xsl:variable>'.
		'<xsl:variable name="agent">'.StringUtils::escapeSimpleXML($_SERVER['HTTP_USER_AGENT']).'</xsl:variable>'.
		'<xsl:variable name="userid"></xsl:variable>'.
		'<xsl:variable name="username"></xsl:variable>'.
		'<xsl:variable name="usertitle"></xsl:variable>'.
		'<xsl:variable name="preview"></xsl:variable>'.
		'<xsl:variable name="editor">'.($editor ? 'true' : 'false').'</xsl:variable>'.
		'<xsl:variable name="highquality">false</xsl:variable>'.
		'<xsl:template match="/"><xsl:apply-templates/></xsl:template>'.
		'</xsl:stylesheet>';
		
		return XslService::transform($xmlData,$xslData);
	}
	
	function getIndex($part) {
		return '';
	}
	
	// Overwrite this
	function getSectionClass($part) {
		return '';
	}
	
	// Override this
	function isDynamic($part) {
		return false;
	}
	
	function buildXMLStyle($part) {
		$array = array(
			'getColor' => 'color',
			'getFontFamily' => 'font-family',
			'getFontSize' => 'font-size',
			'getFontWeight' => 'font-weight',
			'getFontStyle' => 'font-style',
			'getFontVariant' => 'font-variant',
			'getLineHeight' => 'line-height',
			'getTextAlign' => 'text-align',
			'getWordSpacing' => 'word-spacing',
			'getLetterSpacing' => 'letter-spacing',
			'getTextDecoration' => 'text-decoration',
			'getTextIndent' => 'text-indent',
			'getTextTransform' => 'text-transform',
			'getFontStyle' => 'font-style',
			'getFontVariant' => 'font-variant'
		);
		$xml = '<style';
		foreach ($array as $method => $attribute) {
			if (method_exists($part,$method)) {
				$value = $part->$method();
				if (StringUtils::isNotBlank($value)) {
					$xml.=' '.$attribute.'="'.StringUtils::escapeXML($value).'"';
				}
			}
		}
		$xml.='/>';
		return $xml;
	}
	
	function buildCSSStyle($part) {
		$array = array(
			'getColor' => 'color',
			'getFontFamily' => 'font-family',
			'getFontSize' => 'font-size',
			'getFontWeight' => 'font-weight',
			'getFontStyle' => 'font-style',
			'getFontVariant' => 'font-variant',
			'getLineHeight' => 'line-height',
			'getTextAlign' => 'text-align',
			'getWordSpacing' => 'word-spacing',
			'getLetterSpacing' => 'letter-spacing',
			'getTextDecoration' => 'text-decoration',
			'getTextIndent' => 'text-indent',
			'getTextTransform' => 'text-transform',
			'getFontStyle' => 'font-style',
			'getFontVariant' => 'font-variant'
		);
		$css = '';
		foreach ($array as $method => $attribute) {
			if (method_exists($part,$method)) {
				$value = $part->$method();
				if (StringUtils::isNotBlank($value)) {
					$css.=$attribute.': '.StringUtils::escapeXML($value).';';
				}
			}
		}
		return $css;
	}
	
	function getToolbars() {
		return null;
	}
}
?>