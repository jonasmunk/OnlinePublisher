<?php
if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

class StatisticsService {
	
	function registerPage($options) {
		$ip = getenv("REMOTE_ADDR");
		$method = 'GET';//getenv('REQUEST_METHOD');
		//$uri = getenv('REQUEST_URI');
		$language = getenv('HTTP_ACCEPT_LANGUAGE');
		$session = session_id();
		$agent = $_SERVER['HTTP_USER_AGENT'];
		$userhost = '';
		if(isset($_SERVER['REMOTE_HOST'])) {
			$userhost = $_SERVER['REMOTE_HOST'];
		}
		$country='';
		$sql="insert into statistics (time,type,value,ip,country,agent,method,uri,language,session,referer,host) values (".
		"now(),'page',".Database::int($options['id']).",".Database::text($ip).",".Database::text($country).",".Database::text($agent).",".Database::text($method).",".Database::text($options['uri']).",".Database::text($language).",".Database::text($session).",".Database::text($options['referrer']).",".Database::text($userhost).")";
		Database::insert($sql);
	}
	
	function getPageHits($rows) {
		$ids = array();
		$counts = array();
		foreach ($rows as $row) {
			$ids[] = $row['id'];
		}
		if (count($ids) > 0) {			
			$sql = "select count(id) as hits,value as id from statistics where type='page' and value in (".join($ids,',').") group by value";
			$result = Database::selectAll($sql);
			foreach ($result as $row) {
				$counts[$row['id']] = $row['hits'];
			}
		}
		return $counts;
	}
	
	function search($query) {
		$sql = 'SELECT count(distinct statistics.session) as sessions, count(distinct statistics.ip) as ips, count(statistics.id) as hits,date_format(statistics.time, "%Y%m%d") as `key`,date_format(statistics.time, "%d-%m-%Y") as label FROM statistics group by label order by `key` desc limit 100';
		return Database::selectAll($sql);
	}
}