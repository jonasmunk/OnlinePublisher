<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Services
 */
if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

class JsonService {
	
	static function decode($data) {
		return Strings::fromJSON($data);
	}
	
	static function readFile($path) {
		if (file_exists($path)) {
			$json = file_get_contents($path);
			$obj = JsonService::decode($json);
			$obj = Strings::fromUnicode($obj);
			return $obj;
		}
		return null;
	}
}