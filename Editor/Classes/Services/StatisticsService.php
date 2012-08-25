<?php
if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}
require_once($basePath.'Editor/Classes/Model/Page.php');

class StatisticsService {
	
	function registerPage($id,$referer) {
		$ip = getenv("REMOTE_ADDR");
		$method = getenv('REQUEST_METHOD');
		$uri = getenv('REQUEST_URI');
		$language = getenv('HTTP_ACCEPT_LANGUAGE');
		$session = session_id();
		$agent = $_SERVER['HTTP_USER_AGENT'];
		$userhost = '';
		if(isset($_SERVER['REMOTE_HOST'])) {
			$userhost = $_SERVER['REMOTE_HOST'];
		}
		$country='';

		$sql="insert into statistics (time,type,value,ip,country,agent,method,uri,language,session,referer,host) values (".
		"now(),'page',".Database::int($id).",".Database::text($ip).",".Database::text($country).",".Database::text($agent).",".Database::text($method).",".Database::text($uri).",".Database::text($language).",".Database::text($session).",".Database::text($referer).",".Database::text($userhost).")";
		Database::insert($sql);
	}
	
}