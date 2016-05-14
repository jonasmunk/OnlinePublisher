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
	
	static function debug($first,$second=null) {
		if (ConfigurationService::isDebug()) {
			if ($second!=null) {
				error_log($first . ': ' . print_r($second,true));				
			} else {
				error_log(print_r($first,true));				
			}
		}
	}
	
	static function trace() {
		$trace = debug_backtrace();
		$str = '';
		
		for ($i=0; $i < count($trace); $i++) { 
			if ($i>0) {
				$str.="   <<   ";
			}
			$line = $trace[$i];
			$str.= $line['file'].' {'.$line['line'].'} - '.$line['class'].$line['type'].$line['function'];
		}
		error_log($str);
		//error_log(print_r($trace,true));
	}
	
	static function debugRequest() {
		Log::debug($_SERVER['REQUEST_METHOD'].' '.$_SERVER['REQUEST_URI']);
		Log::debug("Get: ".print_r($_GET,true));
		Log::debug("Post: ".print_r($_POST,true));
	}
	
	static function debugJSON($object) {
		Log::debug(Strings::toJSON($object));
	}
	
	static function info($object) {
		error_log('INFO: '.print_r($object,true));
	}
	
	static function warn($object) {
		error_log('WARNING: '.print_r($object,true));
	}

	static function logTool($tool,$event,$message,$entity=0) {
		Log::_logAnything($tool,$event,$message,$entity);
	}
	
	static function logSystem($key,$message,$entity=0) {
		Log::_logAnything('system',$key,$message,$entity);
	}

	static function logPublic($key,$message) {
		Log::_logAnything('public',$key,$message);
	}

	static function logUser($key,$message) {
		Log::_logAnything('user',$key,$message);
	}

	static function _logAnything($category,$event,$message,$entity=0) {
        // TODO Make this more robust / safe
		$sql = "insert into `log` (`time`,`category`,`event`,`entity`,`message`,`user_id`,`ip`,`session`) values (now(),".Database::text($category).",".Database::text($event).",".$entity.",".Database::text($message).",".InternalSession::getUserId().",".Database::text(getenv("REMOTE_ADDR")).",".Database::text(session_id()).")";
		if (!Database::insert($sql)) {
			error_log("could not write to log: ".$sql);
		}
	}

}
?>