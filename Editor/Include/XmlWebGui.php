<?php
/**
 * @package OnlinePublisher
 * @subpackage Include
 */
require_once($basePath.'Editor/Classes/Utilities/StringUtils.php');

$xwg_skin='In2ition';

function writeGui($skin,&$elements,&$xmlData) {
	global $basePath;
	$xmlData='<?xml version="1.0" encoding="ISO-8859-1"?>'.$xmlData;
	$xslData='<?xml version="1.0" encoding="ISO-8859-1"?>'.
	'<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">'.
	'<xsl:output method="html" indent="no" encoding="ISO-8859-1"/>'.
	'<xsl:include href="'.$basePath.'XmlWebGui/Skins/'.$skin.'/Main.xsl"/>';
	for ($i=0;$i<sizeof($elements);$i++) { 
		$xslData.='<xsl:include href="'.$basePath.'XmlWebGui/Skins/'.$skin.'/Include/'.$elements[$i].'.xsl"/>';
	}
	$xslData.='<xsl:template match="/"><xsl:apply-templates/></xsl:template>'.
	'</xsl:stylesheet>';
	
	if (function_exists('xslt_create')) {
		$arguments = array('/_xml' => &$xmlData,'/_xsl' => &$xslData);
		$xp = xslt_create();
		header('Content-Type: text/html; charset=iso-8859-1');
		echo xslt_process($xp, 'arg:/_xml', 'arg:/_xsl', NULL, $arguments );
    	xslt_free($xp);
	}
	else {
		function xslErrorHandler($errno, $errmsg, $filename, $linenum, $vars) {
			header('Content-Type: text/xml');
			echo $vars['xmlData'];
			exit;
		}
		$xslt = new xsltProcessor;
		$xslt->importStyleSheet(DomDocument::loadXML($xslData));
		header('Content-Type: text/html; charset=iso-8859-1');
		echo $xslt->transformToXML(DomDocument::loadXML($xmlData));
	}
}

function xwgTimeStamp2dateTime($stamp) {
	return date("YmdHis",$stamp);
}

// builds option-tags for use in selects
// input must be a keyed array: "value" => "title"
function xwgBuildOptions($array) {
	$out='';
	$keys = array_keys($array);
	foreach ($keys as $key) {
		$out.='<option title="'.StringUtils::escapeXML($array[$key]).'" value="'.$key.'"/>';
	}
	return $out;
}

function xwgBuildListLanguageIcon($lang,$alt='auto') {
	$languages = GuiUtils::getLanguages();
	$out = '';
	if ($lang!='') {
		if ($lang=='DA') {
			$out = '<icon icon="Country/dk" help="Dansk"/>';
		}
		else if ($lang=='EN') {
			$out = '<icon icon="Country/gb" help="English"/>';
		}
		else if ($lang=='DE') {
			$out = '<icon icon="Country/de" help="Deutch"/>';
		}
		else if ($lang=='SV') {
			$out = '<icon icon="Country/se" help="Svenska"/>';
		}
		else {
			if ($alt=='auto') {
				$out = '<text align="right">'.StringUtils::escapeXML($languages[$lang]).'</text>';
			}
			else {
				$out = '<text>'.StringUtils::escapeXML($alt).'</text>';
			}
		}
	}
	return $out;
}
?>