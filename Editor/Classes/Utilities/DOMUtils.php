<?
/**
 * @package OnlinePublisher
 * @subpackage Classes.Utilities
 */

if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}
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
	
	function getChildElements($node,$name=null) {
		$result = array();
		for ($i=0; $i < $node->childNodes->length; $i++) { 
			$child = $node->childNodes->item($i);
			if ($child->nodeType == XML_ELEMENT_NODE) {
 				if ($name!=null && $child->nodeName!=$name) {
					continue;
				}
				$result[] = $child;
			}
		}
		return $result;
	}
	
	function getFirstChildElement($node,$name=null) {
		for ($i=0; $i < $node->childNodes->length; $i++) { 
			$child = $node->childNodes->item($i);
			if ($child->nodeType == XML_ELEMENT_NODE) {
 				if ($name!=null && $child->nodeName!=$name) {
					continue;
				}
				return $child;
			}
		}
		return null;
	}
	
	function getFirstChildText($node,$name) {
		if ($child = DOMUtils::getFirstChildElement($node,$name)) {
			return DOMUtils::getText($child);
		}
		return null;
	}
	
	function getText($node) {
		return $node->textContent;
	}
	
	function getPathText(&$node,$path) {
		$xpath = new DOMXPath($node->ownerDocument);
		if ($child =& $xpath->query($path,$node)->item(0)) {
			return $child->textContent;
		} else {
			return '';
		}
	}
	
	function stripNamespaces($str) {
		return preg_replace('/ xmlns="[\\w:\\/.]*"/e','',$str);
	}
	
	function getInnerXML($node) {
		$doc = DOMUtils::parse('<xml></xml>');
		for ($i=0; $i < $node->childNodes->length; $i++) { 
			$clone = $doc->importNode($node->childNodes->item($i),true);
			$doc->documentElement->appendChild($clone);
		}
		$xml = $doc->saveXML();
		
		return substr($xml, 27, -7);
	}
	
	function getXML($node) {
		$doc = DOMUtils::parse('<xml></xml>');
		$clone = $doc->importNode($node,true);
		$doc->documentElement->appendChild($clone);
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