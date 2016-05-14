<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Utilities
 */

if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}
class Console {
    
    static function isFromConsole() {
        return php_sapi_name()==='cli';
    }
    
    static function exitIfNotConsole() {
        if (!Console::isFromConsole()) {
            Response::forbidden('You are being watched!');
            exit;
        }
    }
    
    static function getArguments() {
        global $argv;
        return $argv;
    }
}