<?
/**
 * @package OnlinePublisher
 * @subpackage Classes.Services
 */

class XslService {
	
	function transform($xmlData,$xslData) {
		if (function_exists('xslt_create')) {
			$arguments = array('/_xml' => $xmlData,'/_xsl' => $xslData);
			$xp = xslt_create();
			$result = xslt_process($xp, 'arg:/_xml', 'arg:/_xsl', NULL, $arguments );
			xslt_free($xp);
		}
		else {
			$xslt = new xsltProcessor;
			$xslt->importStyleSheet(DomDocument::loadXML($xslData));
			$result = $xslt->transformToXML(DomDocument::loadXML($xmlData));
		}
		return $result;
	}
}