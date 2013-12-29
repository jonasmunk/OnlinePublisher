<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Utilities
 */

if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}
class DOMUtils {
	
	static $codes = array(
		XML_ERROR_NONE => 'XML_ERROR_NONE',
		XML_ERROR_NO_MEMORY => 'XML_ERROR_NO_MEMORY',
		XML_ERROR_SYNTAX => 'XML_ERROR_SYNTAX',
		XML_ERROR_NO_ELEMENTS => 'XML_ERROR_NO_ELEMENTS',
		XML_ERROR_INVALID_TOKEN => 'XML_ERROR_INVALID_TOKEN',
		XML_ERROR_UNCLOSED_TOKEN => 'XML_ERROR_UNCLOSED_TOKEN',
		XML_ERROR_PARTIAL_CHAR => 'XML_ERROR_PARTIAL_CHAR',
		XML_ERROR_TAG_MISMATCH => 'XML_ERROR_TAG_MISMATCH',
		XML_ERROR_DUPLICATE_ATTRIBUTE => 'XML_ERROR_DUPLICATE_ATTRIBUTE',
		XML_ERROR_JUNK_AFTER_DOC_ELEMENT => 'XML_ERROR_JUNK_AFTER_DOC_ELEMENT',
		XML_ERROR_PARAM_ENTITY_REF => 'XML_ERROR_PARAM_ENTITY_REF',
		XML_ERROR_UNDEFINED_ENTITY => 'XML_ERROR_UNDEFINED_ENTITY',
		XML_ERROR_RECURSIVE_ENTITY_REF => 'XML_ERROR_RECURSIVE_ENTITY_REF',
		XML_ERROR_ASYNC_ENTITY => 'XML_ERROR_ASYNC_ENTITY',
		XML_ERROR_BAD_CHAR_REF => 'XML_ERROR_BAD_CHAR_REF',
		XML_ERROR_BINARY_ENTITY_REF => 'XML_ERROR_BINARY_ENTITY_REF',
		XML_ERROR_ATTRIBUTE_EXTERNAL_ENTITY_REF => 'XML_ERROR_ATTRIBUTE_EXTERNAL_ENTITY_REF',
		XML_ERROR_MISPLACED_XML_PI => 'XML_ERROR_MISPLACED_XML_PI',
		XML_ERROR_UNKNOWN_ENCODING => 'XML_ERROR_UNKNOWN_ENCODING',
		XML_ERROR_INCORRECT_ENCODING => 'XML_ERROR_INCORRECT_ENCODING',
		XML_ERROR_UNCLOSED_CDATA_SECTION => 'XML_ERROR_UNCLOSED_CDATA_SECTION',
		XML_ERROR_EXTERNAL_ENTITY_HANDLING => 'XML_ERROR_EXTERNAL_ENTITY_HANDLING'
	);
	
	static function getFirstDescendant($node,$name) {
		$nodes = $node->getElementsByTagName($name);
		if ($nodes->length>0) {
			return $nodes->item(0);
		}
		return null;
	}
	
	static function parseHTML($str) {
		$doc = new DOMDocument();
		$success = @$doc->loadHtml($str);
		if ($success) {
			return $doc;
		} else {
			return null;
		}
	}
	
	static function parseAnything($str) {
		global $basePath;
		require_once($basePath.'Editor/Libraries/htmlawed/htmLawed.php');
		$str = htmLawed($str); 
		$doc = new DOMDocument();
		$doc->recover = TRUE;
		try {
			$success = @$doc->loadXML($str,LIBXML_NOERROR);			
		} catch (Exception $e) {
			Log::debug($e);
			Log::debug($str);
		}
		return $doc;
	}

	static function parse($str) {
		$doc = new DOMDocument();
		$success = @$doc->loadXML($str);
		if ($success) {
			return $doc;
		} else {
			return null;
		}
	}
	
	static function getChildElements($node,$name=null) {
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
	
	static function getFirstChildElement($node,$name=null) {
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
	
	static function getFirstChildText($node,$name) {
		if ($child = DOMUtils::getFirstChildElement($node,$name)) {
			return DOMUtils::getText($child);
		}
		return null;
	}
	
	static function getText($node) {
		return $node->textContent;
	}
	
	static function getPathText(&$node,$path) {
		$xpath = new DOMXPath($node->ownerDocument);
		if ($child =& $xpath->query($path,$node)->item(0)) {
			return $child->textContent;
		} else {
			return '';
		}
	}
	
	static function stripNamespaces($str) {
		return preg_replace('/ xmlns="[\\w:\\/.]*"/e','',$str);
	}
	
	static function getInnerXML($node) {
		$doc = DOMUtils::parse('<xml></xml>');
		for ($i=0; $i < $node->childNodes->length; $i++) { 
			$clone = $doc->importNode($node->childNodes->item($i),true);
			$doc->documentElement->appendChild($clone);
		}
		$xml = $doc->saveXML();
		
		return substr($xml, 27, -7);
	}
	
	static function getXML($node) {
		$doc = DOMUtils::parse('<xml></xml>');
		$clone = $doc->importNode($node,true);
		$doc->documentElement->appendChild($clone);
		$xml = $doc->saveXML();
		
		return substr($xml, 27, -7);
	}
	
	static function isValid($data) {
		$code=0;
		$parser = xml_parser_create();
		xml_parser_set_option($parser,XML_OPTION_CASE_FOLDING,0);
		xml_parser_set_option($parser,XML_OPTION_SKIP_WHITE,1);
		xml_parse_into_struct($parser,$data,$values,$tags);
		$code = xml_get_error_code($parser);
		xml_parser_free($parser);
		if ($code==false) {
			return true;
		}
		else {
			Log::debug('Invalid - code: '.$code.' ('.@DOMUtils::$codes[$code].') / '.xml_error_string($code));
			return false;
		}
	}
	
	static function isValidFragment($data) {
		return DOMUtils::isValid('<x>'.$data.'</x>');
	}
}