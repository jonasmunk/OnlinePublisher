<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Optimization
 */
require_once '../../../Include/Private.php';

$kind = Request::getString('kind');
$time = Request::getString('time');

if ($kind=='live') {
	live();
} else if ($kind=='pages') {
	pages();
} else if ($kind=='paths') {
	paths();
} else if ($kind=='agents') {
	agents();
} else if ($kind=='browsers') {
	browsers(false);
} else if ($kind=='browserVersions') {
	browsers(true);
} else {
	visits();
}

function live() {
	$writer = new ListWriter();

	$writer->startList();
	$writer->startHeaders();
	$writer->header(array('title'=>array('Time','da'=>'Tidspunkt')));
	$writer->header(array('title'=>array('Page','da'=>'Side')));
	$writer->header(array('title'=>array('Session','da'=>'Session')));
	$writer->header(array('title'=>array('Device','da'=>'Maskine')));
	$writer->endHeaders();

	$sql = "select UNIX_TIMESTAMP(statistics.time) as time,statistics.type,statistics.session,statistics.ip,page.title as page_title from statistics left join page on statistics.value=page.id where statistics.type='page' order by statistics.time desc limit 100";
	$result = Database::select($sql);
	while($row = Database::next($result)) {
		$writer->startRow();
		$writer->startCell(array('icon'=>'common/time'))->text(DateUtils::formatFuzzy($row['time']))->endCell();
		$writer->startCell()->icon('common/page')->text($row['page_title'])->endCell();
		$writer->startCell()->text($row['session'])->endCell();
		$writer->startCell()->text($row['ip'])->endCell();
		$writer->endRow();
	}
	Database::free($result);
	$writer->endList();	
}

function aggregateBrowsers($list,$version=false) {
	$agg = array();	
	foreach ($list as $row) {
		$app = $version ? getAgentAppVersion($row['agent']) : getAgentApp($row['agent']);
		if (isset($agg[$app])) {
			$agg[$app]['visits']+=$row['visits'];
			$agg[$app]['sessions']+=$row['visits'];
			$agg[$app]['ips']+=$row['visits'];
			$agg[$app]['firsttime']=min($agg[$app]['firsttime'],$row['firsttime']);
			$agg[$app]['lasttime']=max($agg[$app]['lasttime'],$row['lasttime']);
		} else {
			$row['agent'] = $app;
			$agg[$app] = $row;
		}
	}	
	return $agg;
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

function getAgentAppVersion($agent) {
	$analyzer = new UserAgentAnalyzer();
	$analyzer->setUserAgent($agent);
	$app = $analyzer->getApplicationName();
	if (strlen($app)>0) {
		return $app.' '.$analyzer->getApplicationVersion();
	} else {
		return $agent;
	}
}

function browsers($version) {
	$writer = new ListWriter();

	$writer->startList();
	$writer->startHeaders();
	$writer->header(array('title'=>array('Time','da'=>'Fra')));
	$writer->header(array('title'=>array('Time','da'=>'Til')));
	$writer->header(array('title'=>array('Browser','da'=>'Browser')));
	$writer->header(array('title'=>array('Visits','da'=>'Besøg')));
	$writer->header(array('title'=>array('Sessions','da'=>'Sessioner')));
	$writer->header(array('title'=>array('Devices','da'=>'Maskiner')));
	$writer->endHeaders();

	$sql = "select UNIX_TIMESTAMP(max(statistics.time)) as lasttime,UNIX_TIMESTAMP(min(statistics.time)) as firsttime, count(distinct id) as visits,count(distinct ip) as ips,count(distinct session) as sessions,agent from statistics group by agent order by lasttime desc";
	$result = Database::selectAll($sql);
	$result = aggregateBrowsers($result,$version);
	foreach ($result as $row) {
		$writer->startRow();
		$writer->startCell(array('icon'=>'common/time'))->text(DateUtils::formatFuzzy($row['firsttime']))->endCell();
		$writer->startCell(array('icon'=>'common/time'))->text(DateUtils::formatFuzzy($row['lasttime']))->endCell();
		$writer->startCell()->icon('common/page')->text($row['agent'])->endCell();
		$writer->startCell()->text($row['visits'])->endCell();
		$writer->startCell()->text($row['sessions'])->endCell();
		$writer->startCell()->text($row['ips'])->endCell();
		$writer->endRow();
	}
	$writer->endList();
}

function browserVersions() {
	$writer = new ListWriter();

	$writer->startList();
	$writer->startHeaders();
	$writer->header(array('title'=>array('From','da'=>'Fra')));
	$writer->header(array('title'=>array('To','da'=>'Til')));
	$writer->header(array('title'=>array('Browser','da'=>'Browser')));
	$writer->header(array('title'=>array('Visits','da'=>'Besøg')));
	$writer->header(array('title'=>array('Sessions','da'=>'Sessioner')));
	$writer->header(array('title'=>array('Devices','da'=>'Maskiner')));
	$writer->endHeaders();

	$sql = "select UNIX_TIMESTAMP(max(statistics.time)) as lasttime,UNIX_TIMESTAMP(min(statistics.time)) as firsttime, count(distinct id) as visits,count(distinct ip) as ips,count(distinct session) as sessions,agent from statistics group by agent order by lasttime desc";
	$result = Database::selectAll($sql);
	$result = aggregateBrowsers($result);
	foreach ($result as $row) {
		$writer->startRow();
		$writer->startCell(array('icon'=>'common/time'))->text(DateUtils::formatFuzzy($row['firsttime']))->endCell();
		$writer->startCell(array('icon'=>'common/time'))->text(DateUtils::formatFuzzy($row['lasttime']))->endCell();
		$writer->startCell()->icon('common/page')->text($row['agent'])->endCell();
		$writer->startCell()->text($row['visits'])->endCell();
		$writer->startCell()->text($row['sessions'])->endCell();
		$writer->startCell()->text($row['ips'])->endCell();
		$writer->endRow();
	}
	$writer->endList();
}

function agents() {
	$writer = new ListWriter();

	$writer->startList();
	$writer->startHeaders();
	$writer->header(array('title'=>array('Time','da'=>'Fra')));
	$writer->header(array('title'=>array('Time','da'=>'Til')));
	$writer->header(array('title'=>array('Browser','da'=>'Browser')));
	$writer->header();
	$writer->header(array('title'=>array('Visits','da'=>'Besøg')));
	$writer->header(array('title'=>array('Sessions','da'=>'Sessioner')));
	$writer->header(array('title'=>array('Devices','da'=>'Maskiner')));
	$writer->endHeaders();

	$sql = "select UNIX_TIMESTAMP(max(statistics.time)) as lasttime,UNIX_TIMESTAMP(min(statistics.time)) as firsttime, count(distinct id) as visits,count(distinct ip) as ips,count(distinct session) as sessions,agent from statistics group by agent order by lasttime desc";
	$result = Database::selectAll($sql);
	foreach ($result as $row) {
		$analyzer = new UserAgentAnalyzer($row['agent']);
		$app = $analyzer->getApplicationName();
		$writer->startRow();
		$writer->startCell(array('icon'=>'common/time'))->text(DateUtils::formatFuzzy($row['firsttime']))->endCell();
		$writer->startCell(array('icon'=>'common/time'))->text(DateUtils::formatFuzzy($row['lasttime']))->endCell();
		$writer->startCell()->text($row['agent'])->endCell();
		$writer->startCell()->text($app)->endCell();
		$writer->startCell()->text($row['visits'])->endCell();
		$writer->startCell()->text($row['sessions'])->endCell();
		$writer->startCell()->text($row['ips'])->endCell();
		$writer->endRow();
	}
	$writer->endList();
}

function pages() {
	$writer = new ListWriter();

	$writer->startList();
	$writer->startHeaders();
	$writer->header(array('title'=>array('Time','da'=>'Fra')));
	$writer->header(array('title'=>array('Time','da'=>'Til')));
	$writer->header(array('title'=>array('Page','da'=>'Side')));
	$writer->header(array('title'=>array('Visits','da'=>'Besøg')));
	$writer->header(array('title'=>array('Sessions','da'=>'Sessioner')));
	$writer->header(array('title'=>array('Devices','da'=>'Maskiner')));
	$writer->endHeaders();

	$sql = "select UNIX_TIMESTAMP(max(statistics.time)) as lasttime,UNIX_TIMESTAMP(min(statistics.time)) as firsttime,count(distinct statistics.id) as visits,count(distinct statistics.session) as sessions,count(distinct statistics.ip) as ips,page.title as page_title,page.id as page_id from statistics left join page on statistics.value=page.id where statistics.type='page' group by statistics.value order by statistics.time desc limit 100";
	$result = Database::select($sql);
	while($row = Database::next($result)) {
		$writer->startRow();
		$writer->startCell(array('icon'=>'common/time'))->text(DateUtils::formatFuzzy($row['firsttime']))->endCell();
		$writer->startCell(array('icon'=>'common/time'))->text(DateUtils::formatFuzzy($row['lasttime']))->endCell();
		$writer->startCell()->icon('common/page')->text($row['page_title'])->endCell();
		$writer->startCell()->text($row['visits'])->endCell();
		$writer->startCell()->text($row['sessions'])->endCell();
		$writer->startCell()->text($row['ips'])->endCell();
		$writer->endRow();
	}
	Database::free($result);
	$writer->endList();
}

function paths() {
	$writer = new ListWriter();

	$writer->startList();
	$writer->startHeaders();
	$writer->header(array('title'=>array('From','da'=>'Fra')));
	$writer->header(array('title'=>array('To','da'=>'Til')));
	$writer->header(array('title'=>array('Path','da'=>'Sti')));
	$writer->header(array('title'=>array('Page','da'=>'Side')));
	$writer->header(array('title'=>array('Visits','da'=>'Besøg')));
	$writer->header(array('title'=>array('Sessions','da'=>'Sessioner')));
	$writer->header(array('title'=>array('Devices','da'=>'Maskiner')));
	$writer->endHeaders();

	$sql = "select UNIX_TIMESTAMP(max(statistics.time)) as lasttime,UNIX_TIMESTAMP(min(statistics.time)) as firsttime,count(distinct statistics.id) as visits,count(distinct statistics.session) as sessions,count(distinct statistics.ip) as ips,statistics.uri,page.title as page_title,page.id as page_id from statistics left join page on statistics.value=page.id where statistics.type='page' group by statistics.uri order by statistics.time desc limit 100";
	$result = Database::select($sql);
	while($row = Database::next($result)) {
		$writer->startRow();
		$writer->startCell(array('icon'=>'common/time'))->text(DateUtils::formatFuzzy($row['firsttime']))->endCell();
		$writer->startCell(array('icon'=>'common/time'))->text(DateUtils::formatFuzzy($row['lasttime']))->endCell();
		$writer->startCell()->text($row['uri'])->endCell();
		$writer->startCell()->icon('common/page')->text($row['page_title'])->endCell();
		$writer->startCell()->text($row['visits'])->endCell();
		$writer->startCell()->text($row['sessions'])->endCell();
		$writer->startCell()->text($row['ips'])->endCell();
		$writer->endRow();
	}
	Database::free($result);
	$writer->endList();
}

function visits() {
	$query = new StatisticsQuery();

	$result = StatisticsService::search($query);

	$writer = new ListWriter();

	$writer->startList();
	if ($result) {
		$writer->startHeaders();
		$writer->header(array('title'=>array('Date','da'=>'Dato')));
		$writer->header(array('title'=>array('Pageviews','da'=>'Sidevisninger')));
		$writer->header(array('title'=>array('Sessioner','da'=>'Sessions')));
		$writer->header(array('title'=>array('Devices','da'=>'Maskiner')));
		$writer->endHeaders();

		foreach ($result as $row) {
			$writer->startRow();
			$writer->startCell(array('icon'=>'common/time'))->text($row['label'])->endCell();
			$writer->startCell()->text($row['hits'])->endCell();
			$writer->startCell()->text($row['sessions'])->endCell();
			$writer->startCell()->text($row['ips'])->endCell();
			$writer->endRow();
		}
	}
	$writer->endList();
}
?>