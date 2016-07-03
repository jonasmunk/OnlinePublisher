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

	static function transform($xmlData, $xslData) {
		$xslt = new xsltProcessor;
    $xslt->registerPHPFunctions();
		$doc = new DOMDocument();
		$doc->loadXML($xslData);
		$xslt->importStyleSheet($doc);
		$doc = new DOMDocument();
		$doc->loadXML($xmlData);
		$result = $xslt->transformToXML($doc);
		return $result;
  }
}