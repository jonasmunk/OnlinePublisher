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
	
	function searchVisits($query) {
		$sql = 'SELECT count(distinct statistics.session) as sessions, count(distinct statistics.ip) as ips, count(statistics.id) as hits,date_format(statistics.time, "%Y%m%d") as `key`,date_format(statistics.time, "%d-%m-%Y") as label';
		$sql.= ' FROM statistics';
		$sql.= StatisticsService::_buildWhere($query);
		$sql.= ' group by label order by `key` desc limit 500';
		return Database::selectAll($sql);
	}
	
	function searchAgents($query) {
		$sql = "SELECT UNIX_TIMESTAMP(max(statistics.time)) as lasttime,UNIX_TIMESTAMP(min(statistics.time)) as firsttime, count(distinct id) as visits,count(distinct ip) as ips,count(distinct session) as sessions,agent from statistics";
		$sql.= StatisticsService::_buildWhere($query);
		$sql.= " group by agent order by lasttime desc";
		return Database::selectAll($sql);
	}
	
	function searchPages($query) {
		$sql = "select UNIX_TIMESTAMP(max(statistics.time)) as lasttime,UNIX_TIMESTAMP(min(statistics.time)) as firsttime,count(distinct statistics.id) as visits,count(distinct statistics.session) as sessions,count(distinct statistics.ip) as ips,page.title as page_title,page.id as page_id from statistics left join page on statistics.value=page.id where statistics.type='page'";
		$sql.= StatisticsService::_buildWhere($query,false);
		$sql.= " group by statistics.value order by visits desc";
	 	return Database::select($sql);
	}
	
	function searchPaths($query) {
		
		$sql = "select UNIX_TIMESTAMP(max(statistics.time)) as lasttime,UNIX_TIMESTAMP(min(statistics.time)) as firsttime,count(distinct statistics.id) as visits,count(distinct statistics.session) as sessions,count(distinct statistics.ip) as ips,statistics.uri,page.title as page_title,page.id as page_id from statistics left join page on statistics.value=page.id where statistics.type='page'";
		$sql.= StatisticsService::_buildWhere($query,false);
		$sql.= " group by statistics.uri order by visits desc limit 100";
		return Database::select($sql);
	}
	
	function _buildWhere($query,$prepend=true) {
		$where = array();
		if ($query->getStartTime()) {
			$where[] = 'statistics.time>='.Database::datetime($query->getStartTime());
		}
		if ($query->getEndTime()) {
			$where[] = 'statistics.time<='.Database::datetime($query->getEndTime());
		}
		if ($where) {
			return ($prepend ? ' where ' : ' and ').implode($where,' and ');
		}
		return '';
	}
	
	function getVisitsChart($query) {
		$patterns = array(
			'daily' => array('sql' => '%Y%m%d','php' => 'Ymd', 'div' => 60*60*24),
			'hourly' => array('sql' => '%Y%m%d%H','php' => 'YmdH', 'div' => 60*60),
			'monthly' => array('sql' => '%Y%m','php' => 'Ym', 'div' => 60*60*24*31),
			'yearly' => array('sql' => '%Y','php' => 'Y', 'div' => 60*60*24*365)
		);
		
		$days = 100;

		$resolution = $query->getResolution();

		$sql = 'SELECT count(distinct statistics.session) as sessions, count(distinct statistics.ip) as ips, count(statistics.id) as hits,date_format(statistics.time, "'.$patterns[$resolution]['sql'].'") as `key`,date_format(statistics.time, "%e") as label FROM statistics';
		$sql.= StatisticsService::_buildWhere($query);
		$sql.= '  group by `key` order by `key`';
		$rows = Database::selectAll($sql,'key');
		
		if ($query->getStartTime()) {
			$start = $query->getStartTime();
		} else {
			$sql = "SELECT UNIX_TIMESTAMP(min(statistics.time)) as `min` from statistics ".StatisticsService::_buildWhere($query);
			$row = Database::selectFirst($sql);
			$start = intval($row['min']);
		}
		$end = DateUtils::getDayEnd();
		
		$days = floor(($end-$start)/$patterns[$resolution]['div']);
		
		$rows = StatisticsService::_fillGaps($rows,$days,$patterns,$resolution);
		$sets = array();
		$dimensions = array('sessions','ips','hits');


		foreach ($dimensions as $dim) {
			$entres = array();
			foreach ($rows as $row) {
				$entries[$row['key']] = $row[$dim];
			}
			$sets[] = array('type'=>'column','entries'=>$entries);
		}
		return array('sets'=>$sets);
	}

	function _fillGaps($rows,$days,$patterns,$resolution) {
		$filled = array();
		$now = time();
		for ($i=$days; $i >= 0; $i--) {
			if ($resolution=='daily') {
				$date = DateUtils::addDays($now,$i*-1);
			} else if ($resolution=='monthly') {
				$date = DateUtils::addMonths($now,$i*-1);
			} else if ($resolution=='yearly') {
				$date = DateUtils::addYears($now,$i*-1);
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