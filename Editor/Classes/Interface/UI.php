<?php
if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

class UI {

	static function renderFile($file) {
		$gui = file_get_contents($file);
		UI::render($gui);
	}

	static function renderFragment($gui) {
		global $basePath;
		if (Strings::startsWith($gui,'<?xml')) {
			$gui = UI::localize($gui,InternalSession::getLanguage());
		} else {
			$gui = '<?xml version="1.0" encoding="UTF-8"?><subgui xmlns="uri:hui">'.UI::localize($gui,InternalSession::getLanguage()).'</subgui>';
		}
		$xsl='<?xml version="1.0" encoding="UTF-8"?>'.
		'<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">'.
		'<xsl:output method="xml"/>'.

		UI::_parameters().

		'<xsl:include href="'.$basePath.'hui/xslt/gui.xsl"/>'.
		'<xsl:template match="/"><xsl:apply-templates/></xsl:template>'.
		'</xsl:stylesheet>';
		$result = XslService::transform($gui,$xsl);
		$result = preg_replace("/<!DOCTYPE[^>]+>/u", "", $result);
		$result = str_replace(
			array(' xmlns="http://www.w3.org/1999/xhtml"',' xmlns:html="http://www.w3.org/1999/xhtml"')
			,'',$result);
		return $result;
	}

	static function render(&$gui) {
		global $basePath;

		$xhtml = strpos($_SERVER['HTTP_ACCEPT'],'application/xhtml+xml')!==false;
		$xhtml = false;
		if (Request::exists('xhtml','false')) {
			$xhtml = false;
		}

		$xmlData = UI::localize($gui,InternalSession::getLanguage());

		if (!Strings::startsWith($xmlData,'<?xml')) {
			$xmlData = '<?xml version="1.0" encoding="UTF-8"?>'.$xmlData;
		}

		$xslData='<?xml version="1.0" encoding="UTF-8"?>'.
		'<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">'.
		'<xsl:output method="'.($xhtml ? 'xml' : 'html').'"/>'.

		UI::_parameters().

		'<xsl:include href="'.$basePath.'hui/xslt/gui.xsl"/>'.

		'<xsl:template match="/"><xsl:apply-templates/></xsl:template>'.

		'</xsl:stylesheet>';

        header('X-UA-Compatible: IE=edge');
		function xslErrorHandler($errno, $errmsg, $filename, $linenum, $vars) {
			header('Content-Type: text/xml');
			echo $vars['gui'];
			exit;
		}
		header('Content-Type: '.($xhtml ? 'application/xhtml+xml' : 'text/html').'; charset=UTF-8');
		$xslt = new xsltProcessor;
		$doc = new DOMDocument();
		$doc->loadXML($xslData);
		$xslt->importStyleSheet($doc);
		$doc = new DOMDocument();
		$doc->loadXML($xmlData);
		echo MarkupUtils::moveScriptsToBottom($xslt->transformToXML($doc));
	}

	static function _parameters() {

		$dev = Request::getBoolean('dev');
		$profile = Request::getBoolean('profile');
		$context = substr(ConfigurationService::getBaseUrl(),0,-1);
		$pathVersion = ConfigurationService::isUrlRewrite() ? 'version'.ConfigurationService::getDeploymentTime().'/' : '';

    if (true && ConfigurationService::isUrlRewrite()) {
			$context.= '/'.'version'.ConfigurationService::getDeploymentTime();
			$pathVersion = '';
    }

		return '<xsl:variable name="dev">'.($dev ? 'true' : 'false').'</xsl:variable>'.
		'<xsl:variable name="profile">'.$profile.'</xsl:variable>'.
		'<xsl:variable name="version">'.ConfigurationService::getDeploymentTime().'</xsl:variable>'.
		'<xsl:variable name="pathVersion">'.$pathVersion.'</xsl:variable>'.
		'<xsl:variable name="context">'.$context.'</xsl:variable>'.
		'<xsl:variable name="language">'.InternalSession::getLanguage().'</xsl:variable>';
	}

	static function localize($xml,$language='en') {

		$pattern = "/({[^}]+})/mi";
		preg_match_all($pattern, $xml, $matches,PREG_OFFSET_CAPTURE);
		$diff = 0;
		for ($i=0;$i<count($matches[0]);$i++) {
			$pos = $matches[0][$i][1];
			if ($xml[$pos+$diff-1]!='"' && $xml[$pos+$diff-1]!='>') {
				continue;
			}
			$old = $matches[0][$i][0];
			$parts = UI::extract($old);
			$new = array_key_exists($language,$parts) ? $parts[$language] : @$parts['any'];
			$xml = substr_replace ( $xml , $new , $pos+$diff ,strlen($old));

			$diff = $diff + strlen($new)-strlen($old);
		}
		return $xml;
	}

	static function extract($str) {
		$parsed = array();
		$str = substr($str,1,-1);
		$parts = explode(';',$str);
		foreach ($parts as $part) {
			$pos = strpos($part,':');
			if ($pos===false) {
				$parsed['any'] = trim($part);
			} else {
				$lang = trim(substr($part,0,$pos));
				$text = substr($part,$pos+1);
				$parsed[$lang] = trim($text);
			}
		}
		return $parsed;
	}

  static function buildAbstractUI($xml) {
    $doc = DOMUtils::parse($xml);

    $root = $doc->documentElement;

    $children = DOMUtils::getChildElements($root);

    $gui = '<formula><group>';

    foreach ($children as $child) {
      if ($child->tagName=='text') {
        $gui.='<field label="' . Strings::escapeXML($child->getAttribute('label')) . '">';
        $gui.='<text-input key="' . Strings::escapeXML($child->getAttribute('key')) . '"/>';
        $gui.='</field>';
      }
    }

    $gui .= '</group></formula>';
    return UI::renderFragment($gui);
  }

	static function compile() {
    global $basePath;
    $cmd = $basePath."hui/tools/compile.sh";
    return ShellService::execute($cmd);
  }

	static function toLinks($links) {
		$out = array();
		foreach ($links as $link) {
			$out[] = array(
				'id' => $link->getId(),
				'text' => $link->getText(),
				'kind' => $link->getType(),
				'value' => $link->getValue(),
				'info' => $link->getInfo(),
				'icon' => $link->getIcon()
			);
		}
		return $out;
	}

	static function fromLinks($links) {
		if (!is_array($links)) return;
		$out = array();
		foreach ($links as $link) {
			$objectLink = new ObjectLink();
			$objectLink->setText($link->text);
			$objectLink->setType($link->kind);
			$objectLink->setValue($link->value);
			$out[] = $objectLink;
		}
		return $out;
	}
}