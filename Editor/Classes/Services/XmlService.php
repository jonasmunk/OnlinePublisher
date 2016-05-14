<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Services
 */
if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

class XmlService {
	
	static function validateSnippet($data) {
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
	
	static function readFile($path) {
		return simplexml_load_file($path);
	}
}