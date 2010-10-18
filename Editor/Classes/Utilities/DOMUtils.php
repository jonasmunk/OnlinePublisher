<?
/**
 * @package OnlinePublisher
 * @subpackage Classes.Utilities
 */

class DOMUtils {
	
	function getFirstDescendant($node,$name) {
		$nodes = $node->getElementsByTagName($name);
		if ($nodes->length>0) {
			return $nodes->item(0);
		}
		return null;
	}
	
	function parse($str) {
		$doc = new DOMDocument();
		$success = @$doc->loadXML($str);
		if ($success) {
			return $doc;
		} else {
			return null;
		}
	}
	
	function getText($node) {
		return $node->textContent;
	}
	
	function stripNamespaces($str) {
		return preg_replace('/ xmlns="[\\w:\\/.]*"/e','',$str);
	}
	
	function getInnerXML($node) {
		Log::debug($node->childNodes->length);
		$doc = DOMUtils::parse('<xml></xml>');
		for ($i=0; $i < $node->childNodes->length; $i++) { 
			$clone = $doc->importNode($node->childNodes->item($i),true);
			$doc->documentElement->appendChild($clone);
		}
		$xml = $doc->saveXML();
		
		return substr($xml, 27, -7);
	}
	
	function isValid($data) {
		$code=0;
		$parser = xml_parser_create();
		xml_parser_set_option($parser,XML_OPTION_CASE_FOLDING,0);
		xml_parser_set_option($parser,XML_OPTION_SKIP_WHITE,1);
		xml_parse_into_struct($parser,$data,$values,$tags);
		$code=xml_get_error_code($parser);
		xml_parser_free($parser);
		if ($code==false) {
			return true;
		}
		else {
			return false;
		}
	}
	
	function isValidFragment($data) {
		return DOMUtils::isValid('<x>'.$data.'</x>');
	}
}