<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Statistics
 */
require_once '../../Include/Private.php';

function buildSql() {
	$where = "";
	if (getToolSessionVar('statistics','ignoreInternalUsers',false)) {
		$where.= " statistics.known=0";
	}
	if (getToolSessionVar('statistics','ignoreRobots',false)) {
		if (strlen($where)>0) {
			$where.=" and ";
		}
		$where.=" statistics.robot=0";
	}
	return $where;
}

function getTotalCount($type=null) {
	$parms = buildSql();
	if ($type!==null) {
		$sql="SELECT count(statistics.id) as total FROM statistics where type='".$type."'".(strlen($parms)>0 ? " and ".$parms : "");
	}
	else {
		$sql="SELECT count(statistics.id) as total FROM statistics ".(strlen($parms)>0 ? " where ".$parms : "");
	}
	if ($row=Database::selectFirst($sql)) {
		return $row['total'];
	}
	else {
		return 0;
	}
}

function sortResult($a, $b) 
{
    if ($a['hits'] == $b['hits']) {
        return 0;
    }
    return ($a['hits'] < $b['hits']) ? -1 : 1;
}

function findMaxHit($sql) {
	$max = 0;
	$result = Database::select($sql);	
	while($row = Database::next($result)) {
		if ($row['hits']>$max) $max = $row['hits'];
	}
	Database::free($result);
	return $max;
}


/****************************** Visitors ******************************/

function buildVisitorsSql($mode) {
	$parms = buildSql();
	switch($mode) {
		case "years":
			return "SELECT count(distinct statistics.session) as sessions, count(distinct statistics.ip) as ips, count(statistics.id) as hits,date_format(statistics.time, \"%Y\") as label FROM statistics ".(strlen($parms)>0 ? " where ".$parms : "")." group by label order by label desc";
			break;
		case "months":
			return "SELECT count(distinct statistics.session) as sessions, count(distinct statistics.ip) as ips, count(statistics.id) as hits,date_format(statistics.time, \"%Y%m\") as labelIndex,date_format(statistics.time, \"%m%Y\") as label FROM statistics ".(strlen($parms)>0 ? " where ".$parms : "")." group by label order by labelIndex desc";
			break;
		case "hours":
			return "SELECT count(distinct statistics.session) as sessions, count(distinct statistics.ip) as ips, count(statistics.id) as hits,date_format(statistics.time, \"%H\") as label FROM statistics ".(strlen($parms)>0 ? " where ".$parms : "")." group by label order by label desc";
			break;
		case "weeks":
			return "SELECT count(distinct statistics.session) as sessions, count(distinct statistics.ip) as ips, count(statistics.id) as hits,date_format(statistics.time, \"%Y%u\") as labelIndex,date_format(statistics.time, \"%u%Y\") as label FROM statistics ".(strlen($parms)>0 ? " where ".$parms : "")." group by label order by labelIndex desc limit 100";
			break;
		case "days":
			return "SELECT count(distinct statistics.session) as sessions, count(distinct statistics.ip) as ips, count(statistics.id) as hits,date_format(statistics.time, \"%Y%m%d\") as labelIndex,date_format(statistics.time, \"%d-%m-%Y\") as label FROM statistics ".(strlen($parms)>0 ? " where ".$parms : "")." group by label order by labelIndex desc limit 100";
			break;
		case "daysOfMonth":
			return "SELECT count(distinct statistics.session) as sessions, count(distinct statistics.ip) as ips, count(statistics.id) as hits,date_format(statistics.time, \"%d\") as labelIndex,date_format(statistics.time, \"%d\") as label FROM statistics ".(strlen($parms)>0 ? " where ".$parms : "")." group by label order by labelIndex asc";
			break;
		case "daysOfWeek":
			return "SELECT count(distinct statistics.session) as sessions, count(distinct statistics.ip) as ips, count(statistics.id) as hits,date_format(statistics.time, \"%w\") as label FROM statistics ".(strlen($parms)>0 ? " where ".$parms : "")." group by label order by label desc";
			break;
	}
}

/****************************** Pages ******************************/

function buildPagesSql() {
	$parms = buildSql();
	return "SELECT page.title,count(distinct statistics.session) as sessions, count(distinct statistics.ip) as ips, count(statistics.id) as hits FROM statistics left join page on statistics.value=page.id where statistics.type='page'".(strlen($parms)>0 ? " and ".$parms : "")." group by value order by hits desc";
}

/****************************** Files ******************************/

function buildFilesSql() {
	$parms = buildSql();
	return "SELECT object.title,count(distinct statistics.session) as sessions, count(distinct statistics.ip) as ips, count(statistics.id) as hits FROM statistics left join object on statistics.value=object.id where statistics.type='file'".(strlen($parms)>0 ? " and ".$parms : "")." group by value order by hits desc";
}

/****************************** Countries ******************************/



function buildCountryData() {
	$parms = buildSql();
	$sql="SELECT statistics.country,statistics.language,count(distinct statistics.session) as sessions, count(distinct statistics.ip) as ips, count(statistics.id) as hits FROM statistics ".(strlen($parms)>0 ? " where ".$parms : "")." group by statistics.country,statistics.language";
	$analyzed = array();
	$total = 0;
	$result = Database::select($sql);	
	while($row = Database::next($result)) {
		$trans = getCountry($row['country'],$row['language']);
		if (array_key_exists($trans,$analyzed)) {
			$analyzed[$trans]['hits']+=$row['hits'];
			$analyzed[$trans]['sessions']+=$row['sessions'];
			$analyzed[$trans]['ips']+=$row['ips'];
		} else {
			$analyzed[$trans]=array('hits' => $row['hits'],'sessions' => $row['sessions'],'ips' => $row['ips']);
		}
		$total+=$row['hits'];
	}
	Database::free($result);
	uasort($analyzed,'sortResult');
	return array("data" => array_reverse($analyzed),"total" => $total);
}

function getCountry($country,$language) {
	if ($country!='') {
		return $country;
	}
	elseif ($language!='') {
		$arr = split(",",$language);
		return $arr[0];
	}
	else {
		return "Ukendt";
	}
}



/*************************** Browsers ***************************/


function buildBrowserData($mode) {
	$parms = buildSql();
	
	$sql="SELECT count(distinct statistics.session) as sessions, count(distinct statistics.ip) as ips, count(statistics.id) as hits,statistics.agent FROM statistics ".(strlen($parms)>0 ? " where ".$parms : "")." group by statistics.agent order by statistics.agent";
	$analyzed = array();
	$total = 0;
	$result = Database::select($sql);	
	while($row = Database::next($result)) {
		if ($mode=='versions') {
			$trans = getAgentVersion($row['agent']);
		}
		elseif ($mode=='techs') {
			$trans = getAgentTech($row['agent']);
		}
		elseif ($mode=='techversions') {
			$trans = getAgentTechVersion($row['agent']);
		}
		elseif ($mode=='apps') {
			$trans = getAgentApp($row['agent']);
		}
		else {
			$trans = $row['agent'];
		}
		if (array_key_exists($trans,$analyzed)) {
			$analyzed[$trans]['hits']+=$row['hits'];
			$analyzed[$trans]['sessions']+=$row['sessions'];
			$analyzed[$trans]['ips']+=$row['ips'];
		} else {
			$analyzed[$trans]=array('hits' => $row['hits'],'sessions' => $row['sessions'],'ips' => $row['ips']);
		}
		$total+=$row['hits'];
	}
	Database::free($result);
	uasort($analyzed,'sortResult');
	return array("data" => array_reverse($analyzed),"total" => $total);
}

function getAgentVersion($agent) {
	$analyzer = new UserAgentAnalyzer();
	$analyzer->setUserAgent($agent);
	$tech = $analyzer->getApplicationName();
	$techVersion = $analyzer->getApplicationVersion();
	$tech = appendWordToString($tech,$techVersion,' ');
	if (strlen($tech)>0) {
		return $tech;
	} else {
		return $agent;
	}
}

function getAgentApp($agent) {
	$analyzer = new UserAgentAnalyzer();
	$analyzer->setUserAgent($agent);
	$app = $analyzer->getApplicationName();
	if (strlen($app)>0) {
		return $app;
	} else {
		return $agent;
	}
}

function getAgentTech($agent) {
	$analyzer = new UserAgentAnalyzer();
	$analyzer->setUserAgent($agent);
	$tech = $analyzer->getTechnologyName();
	if (strlen($tech)>0) {
		return $tech;
	} else {
		return $agent;
	}
}

function getAgentTechVersion($agent) {
	$analyzer = new UserAgentAnalyzer();
	$analyzer->setUserAgent($agent);
	$tech = $analyzer->getTechnologyName();
	$techVersion = $analyzer->getTechnologyVersion();
	$tech = appendWordToString($tech,$techVersion,' ');
	if (strlen($tech)>0) {
		return $tech;
	} else {
		return $agent;
	}
}
?>