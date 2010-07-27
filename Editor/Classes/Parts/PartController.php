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

	function insertLineBreakTags($input,$tag) {
		return str_replace(array("\r\n","\r","\n"), $tag, $input);;
	}
	
	function render($part,$pageId) {
		global $basePath;
		$context = PartService::buildPartContext($pageId);
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
		'<xsl:variable name="editor"></xsl:variable>'.
		'<xsl:variable name="highquality">false</xsl:variable>'.
		'<xsl:template match="/"><xsl:apply-templates/></xsl:template>'.
		'</xsl:stylesheet>';
		
		return XslService::transform($xmlData,$xslData);
	}
	
	function buildXMLStyle($part) {
		$xml = '<style';
		if (method_exists($part,'getColor')) {
			$xml.=' color="'.$part->getColor().'"';
		}
		$xml.='/>';
		return $xml;
	}
}
?>