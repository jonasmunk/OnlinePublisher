<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes
 */
if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

class Log {
	
	function debug($object) {
		if (ConfigurationService::isDebug()) {
			error_log(print_r($object,true));
		}
	}
	
	function debugRequest() {
		Log::debug($_SERVER['REQUEST_METHOD'].' '.$_SERVER['REQUEST_URI']);
		Log::debug("Get: ".print_r($_GET,true));
		Log::debug("Post: ".print_r($_POST,true));
	}
	
	function debugJSON($object) {
		Log::debug(Strings::toJSON($object));
	}
	
	function info($object) {
		error_log('INFO: '.print_r($object,true));
	}
	
	function warn($object) {
		error_log('WARNING: '.print_r($object,true));
	}

	function logTool($tool,$event,$message,$entity=0) {
		Log::_logAnything($tool,$event,$message,$entity);
	}
	
	function logSystem($key,$message,$entity=0) {
		Log::_logAnything('system',$key,$message,$entity);
	}

	function logPublic($key,$message) {
		Log::_logAnything('public',$key,$message);
	}

	function logUser($key,$message) {
		Log::_logAnything('user',$key,$message);
	}

	function _logAnything($category,$event,$message,$entity=0) {
		$sql = "insert into `log` (`time`,`category`,`event`,`entity`,`message`,`user_id`,`ip`,`session`) values (now(),".Database::text($category).",".Database::text($event).",".$entity.",".Database::text($message).",".InternalSession::getUserId().",".Database::text(getenv("REMOTE_ADDR")).",".Database::text(session_id()).")";
		if (!Database::insert($sql)) {
			error_log("could not write to log: ".$sql);
		}
	}

}
?>