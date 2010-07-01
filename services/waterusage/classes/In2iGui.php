<?
class In2iGui {

	function display($elements,&$xmlData) {
		global $basePath,$xwg_skin;
		$skin = $xwg_skin;
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
			echo $xslt->transformToXML(DomDocument::loadXML($xmlData));
		}
	}

	function render(&$gui) {
		global $basePath;
		$xmlData='<?xml version="1.0" encoding="UTF-8"?>'.$gui;
		$xslData='<?xml version="1.0" encoding="UTF-8"?>'.
		'<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">'.
		'<xsl:output method="xml" indent="no" encoding="UTF-8"/>'.
		'<xsl:include href="'.$basePath.'In2iGui/xslt/gui.xsl"/>';
		$xslData.='<xsl:template match="/"><xsl:apply-templates/></xsl:template>'.
		'</xsl:stylesheet>';
	
		if (function_exists('xslt_create')) {
			$arguments = array('/_xml' => &$gui,'/_xsl' => &$xslData);
			$xp = xslt_create();
			echo xslt_process($xp, 'arg:/_xml', 'arg:/_xsl', NULL, $arguments );
	    	xslt_free($xp);
		}
		else {
			function xslErrorHandler($errno, $errmsg, $filename, $linenum, $vars) {
				header('Content-Type: text/xml');
				echo $vars['gui'];
				exit;
			}
			$xslt = new xsltProcessor;
			$xslt->importStyleSheet(DomDocument::loadXML($xslData));
			echo $xslt->transformToXML(DomDocument::loadXML($gui));
		}
	}
	
	function respondSuccess() {
		header('Content-Type: text/xml');
		echo '<?xml version="1.0" encoding="UTF-8"?><success/>';
	}

	function toDateTime($stamp) {
		return date("YmdHis",$stamp);
	}
	
	function buildOptions($objects,$selected=array()) {
		$gui='';
		foreach ($objects as $object) {
			$gui.='<option title="'.In2iGui::escape($object->getTitle()).'" value="'.In2iGui::escape($object->getId()).'" selected="'.(in_array($object->getId(), $selected) ? 'true' : 'false').'"/>';
		}
		return $gui;
	}
	
	function escape(&$input) {
		$output = In2iGui::_htmlnumericentities($input);
		$output = str_replace('&#151;', '-', $output);
		$output = str_replace('&#146;', '&#39;', $output);
		$output = str_replace('&#147;', '&#8220;', $output);
		$output = str_replace('&#148;', '&#8221;', $output);
		$output = str_replace('&#128;', '&#243;', $output);
		$output = str_replace('&#128;', '&#243;', $output);
		$output = str_replace('"', '&quot;', $output);
		return $output;
	}

	function _htmlnumericentities(&$str){
	  return preg_replace('/[^!-%\x27-;=?-~ ]/e', '"&#".ord("$0").chr(59)', $str);
	}
}
?>