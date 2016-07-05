<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Part
 */
if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

class PartController
{
	var $type;
	static $methodToAttribute = array(
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
		'getFontVariant' => 'font-variant',
		'getListStyle' => 'list-style'
	);
	
	function PartController($type) {
		$this->type = $type;
	}
	
	function getType() {
	    return $this->type;
	}
	
	function display($part,$context) {
		return $this->render($part,$context);
	}
	
	function build($part,$context) {
		if (!$part) {
			return '';
		}
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
	
	function getSingleLink($part,$sourceType=null) {
		return PartService::getSingleLink($part,$sourceType);
	}
	
	function importFromString($xml) {
		if ($doc = DOMUtils::parse($xml)) {
			return $this->importFromNode($doc->documentElement);
		}
		return null;
	}
	
	function importFromNode($node) {
		$part = PartService::newInstance($this->type);
		if ($subNode = DOMUtils::getFirstDescendant($node,'sub')) {
			if (method_exists($this,'importSub')) {
				$this->importSub($subNode,$part);
			}
		} else {
			Log::debug('The node has no "sub" element');
		}
		return $part;
	}
	
	function getNamespace($version='1.0') {
		return 'http://uri.in2isoft.com/onlinepublisher/part/'.$this->type.'/'.$version.'/';
	}
	
	function isLiveEnabled() {
		return false;
	}
    
    /** If the XSL of the design should be included when rendering */
    function renderUsingDesign() {
        return false;
    }
		
	function render($part,$context,$editor=true) {
		global $basePath;
        $encoding = ConfigurationService::isUnicode() ? 'UTF-8' : 'ISO-8859-1';
        
		$xmlData = '<?xml version="1.0" encoding="'.$encoding.'"?>'.$this->build($part,$context);
		
		$xsl='<?xml version="1.0" encoding="UTF-8"?>'.
		'<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">'.
		'<xsl:output method="html" indent="no" encoding="UTF-8"/>';
        if ($this->renderUsingDesign()) {
    		$xsl.='<xsl:include href="'.$basePath.'style/basic/xslt/part_'.$this->type.'.xsl"/>'.
            '<xsl:include href="'.$basePath.'style/' . $context->design . '/xslt/main.xsl"/>';
        } else {
    		$xsl.='<xsl:include href="'.$basePath.'style/basic/xslt/util.xsl"/>'.
        	'<xsl:include href="'.$basePath.'style/basic/xslt/part_'.$this->type.'.xsl"/>';
        }
        $xsl.=
		'<xsl:variable name="design"></xsl:variable>'.
		'<xsl:variable name="path">../../../</xsl:variable>'.
		'<xsl:variable name="navigation-path"></xsl:variable>'.
		'<xsl:variable name="page-path"></xsl:variable>'.
    '<xsl:variable name="data-path">' . ConfigurationService::getDataUrl() . '</xsl:variable>'.
		'<xsl:variable name="template"></xsl:variable>'.
		'<xsl:variable name="userid"></xsl:variable>'.
		'<xsl:variable name="username"></xsl:variable>'.
		'<xsl:variable name="usertitle"></xsl:variable>'.
		'<xsl:variable name="preview"></xsl:variable>'.
		'<xsl:variable name="mini">false</xsl:variable>'.
		'<xsl:variable name="editor">'.($editor ? 'true' : 'false').'</xsl:variable>'.
		'<xsl:variable name="urlrewrite">'.(ConfigurationService::isUrlRewrite() ? 'true' : 'false').'</xsl:variable>'.
		'<xsl:variable name="timestamp">'.ConfigurationService::getDeploymentTime().'</xsl:variable>'.
		'<xsl:variable name="language">'.strtolower($context->getLanguage()).'</xsl:variable>'.
		'<xsl:template match="/"><xsl:apply-templates/></xsl:template>'.
		'</xsl:stylesheet>';
		$html = XslService::transform($xmlData,$xsl);
		return str_replace("<br></br>","<br/>",$html);
	}
	
	function buildHiddenFields($items) {
		$str = '';
		foreach ($items as $key => $value) {
			$str.='<input type="hidden" name="'.$key.'" value="'.Strings::escapeEncodedXML($value).'"/>';
		}
		return $str;
	}
    
    function getEditorScript() {
        return '<script src="'.ConfigurationService::getBaseUrl().'Editor/Parts/' . $this->type . '/script.js" type="text/javascript" charset="utf-8"></script>';
    }
	
	function getIndex($part) {
		return '';
	}
	
	function updateAdditional($part) {
		// Overwrite this
	}
	
	function beforeSave($part) {
        return false;
		// Overwrite this - called before updating and after creation
        // So the part will be persistent - if true is returned the part will be updated after saving
	}
	
	// Overwrite this
	function getSectionClass($part) {
		return '';
	}
	
	// Override this
	function isDynamic($part) {
		return false;
	}
	
	function parseXMLStyle($part,$node) {
		if (!$node)	{
			return;
		}
		foreach (PartController::$methodToAttribute as $method => $attribute) {
			if (method_exists($part,$method)) {
				$value = $node->getAttribute($attribute);
				if (Strings::isNotBlank($value)) {
					$method = str_replace('get','set',$method);
					$part->$method($value);
				}
			}
		}
	}
	
	function buildXMLStyle($part) {
		
		$xml = '<style';
		foreach (PartController::$methodToAttribute as $method => $attribute) {
			if (method_exists($part,$method)) {
				$value = $part->$method();
				if (Strings::isNotBlank($value)) {
					$xml.=' '.$attribute.'="'.Strings::escapeXML($value).'"';
				}
			}
		}
		$xml.='/>';
		return $xml;
	}
	
	function buildCSSStyle($part) {
		$css = '';
		foreach (PartController::$methodToAttribute as $method => $attribute) {
			if (method_exists($part,$method)) {
				$value = $part->$method();
				if (Strings::isNotBlank($value)) {
					$css.=$attribute.': '.Strings::escapeXML($value).';';
				}
			}
		}
		return $css;
	}
	
	function getUI() {
		return null;
	}
	
	function getToolbars() {
		return null;
	}
}
?>