<?
/**
 * @package OnlinePublisher
 * @subpackage Classes
 */
if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}
require_once($basePath.'Editor/Classes/Database.php');
require_once($basePath.'Editor/Classes/InternalSession.php');
require_once($basePath.'Editor/Classes/Utilities/StringUtils.php');

class Log {
	
	function debug($object) {
		global $baseUrl;
		if (strpos('/~jbm/',$baseUrl)!==false || true) {
			error_log(print_r($object,true));
		}
	}
	
	function debugJSON($object) {
		global $baseUrl;
		if (strpos('/~jbm/',$baseUrl)!==false || true) {
			$object = StringUtils::toJSON($object);
			error_log(print_r($object,true));
		}
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