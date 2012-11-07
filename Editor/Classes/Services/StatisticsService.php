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
	
	function getChart($query) {
		$patterns = array(
			'day'=>array('sql' => '%Y%m%d','php' => 'Ymd'),
			'hour'=>array('sql' => '%Y%m%d%H','php' => 'YmdH')
		);

		$resolution = 'day';
		$days = $query['days'];

		$sql = 'SELECT count(distinct statistics.session) as sessions, count(distinct statistics.ip) as ips, count(statistics.id) as hits,date_format(statistics.time, "'.$patterns[$resolution]['sql'].'") as `key`,date_format(statistics.time, "%e") as label FROM statistics where statistics.time>DATE_SUB(now(), INTERVAL '.$days.' DAY)  group by `key` order by `key`';
		$rows = Database::selectAll($sql,'key');
		$rows = StatisticsService::_fillGaps($rows,$days,$patterns,$resolution);
		$sets = array();
		$dimensions = array('sessions','ips','hits');


		foreach ($dimensions as $dim) {
			$entres = array();
			foreach ($rows as $row) {
				$entries[$row['key']] = $row[$dim];
			}
			$sets[] = array('type'=>'line','entries'=>$entries);
		}
		return array('sets'=>$sets);
	}

	function _fillGaps($rows,$days,$patterns,$resolution) {
		$filled = array();
		$now = time();
		for ($i=$days; $i >= 0; $i--) {
			if ($resolution=='day') {
				$date = DateUtils::addDays($now,$i*-1);
			} else {
				$date = DateUtils::addHours($now,$i*-1);
			}
			$key = date($patterns[$resolution]['php'],$date);
			if (array_key_exists($key,$rows)) {
				$filled[$key] = $rows[$key];
			} else {
				$filled[$key] = array('hits'=>0,'sessions'=>0,'ips'=>0,'key'=>$key,'label'=>date('j',$date));
			}
		}
		return $filled;
	}
}