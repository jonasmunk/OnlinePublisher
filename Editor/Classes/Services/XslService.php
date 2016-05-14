<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Services
 */
if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

class XslService {
	
	static function transform($xmlData,$xslData) {
		/*if (function_exists('xslt_create')) {
			$arguments = array('/_xml' => $xmlData,'/_xsl' => $xslData);
			$xp = xslt_create();
			$result = xslt_process($xp, 'arg:/_xml', 'arg:/_xsl', NULL, $arguments );
			xslt_free($xp);
		}
		else {*/
			$xslt = new xsltProcessor;
			$doc = new DOMDocument();
			$doc->loadXML($xslData);
			$xslt->importStyleSheet($doc);
			$doc = new DOMDocument();
			$doc->loadXML($xmlData);
			$result = $xslt->transformToXML($doc);
//		}
		return $result;
  }
}