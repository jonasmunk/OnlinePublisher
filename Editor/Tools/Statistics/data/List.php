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
} else if ($kind=='unknownAgents') {
	unknownAgents();
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
		return '!!!'.$agent;
	}
}

function getAgentAppVersion($agent) {
	$analyzer = new UserAgentAnalyzer();
	$analyzer->setUserAgent($agent);
	$app = $analyzer->getApplicationName();
	if (strlen($app)>0) {
		return $app.' '.$analyzer->getApplicationVersion();
	} else {
		return '!!!'.$agent;
	}
}

function unknownAgents() {
	
	$query = new StatisticsQuery();
	$query->withTime(Request::getString('time'));
	$result = StatisticsService::searchAgents($query);

	$writer = new ListWriter();

	$writer->startList() ->
		startHeaders() ->
			header(array('title'=>array('From','da'=>'Fra'))) ->
			header(array('title'=>array('To','da'=>'Til'))) ->
			header(array('title'=>array('Browser','da'=>'Browser'))) ->
			header(array('title'=>array('Visits','da'=>'Besøg'))) ->
			header(array('title'=>array('Sessions','da'=>'Sessioner'))) ->
			header(array('title'=>array('Devices','da'=>'Maskiner'))) ->
		endHeaders();

	foreach ($result as $row) {
		$analyzer = new UserAgentAnalyzer($row['agent']);
		if ($analyzer->getApplicationName()) {
			continue;
		}
		$writer->startRow()->
			startCell(array('icon'=>'common/time')) -> text(DateUtils::formatFuzzy($row['firsttime'])) -> endCell() ->
			startCell(array('icon'=>'common/time')) -> text(DateUtils::formatFuzzy($row['lasttime'])) -> endCell() ->
			startCell() -> icon('common/page') -> text($row['agent']) -> endCell() ->
			startCell() -> text($row['visits']) -> endCell() ->
			startCell() -> text($row['sessions']) -> endCell() ->
			startCell() -> text($row['ips']) -> endCell() ->
		endRow();
	}
	$writer->endList();
	
}

function browsers($version) {
	
	$query = new StatisticsQuery();
	$query->withTime(Request::getString('time'));
	$result = StatisticsService::searchAgents($query);
	$result = aggregateBrowsers($result,$version);
	
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
	$query = new StatisticsQuery();
	$query->withTime(Request::getString('time'));
	$result = StatisticsService::searchAgents($query);
	$result = aggregateBrowsers($result);
	
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
	$query = new StatisticsQuery();
	$query->withTime(Request::getString('time'));
	$result = StatisticsService::searchAgents($query);

	$writer = new ListWriter();

	$writer->startList();
	$writer->startHeaders();
	$writer->header(array('title'=>array('From','da'=>'Fra')));
	$writer->header(array('title'=>array('To','da'=>'Til')));
	$writer->header(array('title'=>array('Browser','da'=>'Browser')));
	$writer->header();
	$writer->header(array('title'=>array('Visits','da'=>'Besøg')));
	$writer->header(array('title'=>array('Sessions','da'=>'Sessioner')));
	$writer->header(array('title'=>array('Devices','da'=>'Maskiner')));
	$writer->endHeaders();

	foreach ($result as $row) {
		$analyzer = new UserAgentAnalyzer($row['agent']);
		$app = $analyzer->getApplicationName().' '.$analyzer->getApplicationVersion().' / '.$analyzer->getTechnologyName().' '.$analyzer->getTechnologyVersion();
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
	$query = new StatisticsQuery();
	$query -> withTime(Request::getString('time'));
	
	$result = StatisticsService::searchPages($query);
	
	$writer = new ListWriter();

	$writer->startList() ->
		startHeaders() ->
			header(array( 'title' => array('From','da'=>'Fra') )) ->
			header(array( 'title' => array('To','da'=>'Til') )) ->
			header(array( 'title' => array('Page','da'=>'Side') )) ->
			header(array( 'title' => array('Visits','da'=>'Besøg') )) ->
			header(array( 'title' => array('Sessions','da'=>'Sessioner') )) ->
			header(array( 'title' => array('Devices','da'=>'Maskiner') )) ->
		endHeaders();

	while($row = Database::next($result)) {
		$writer -> startRow() ->
			startCell(array( 'icon' => 'common/time', 'dimmed' => true)) -> text(DateUtils::formatFuzzy($row['firsttime'])) -> endCell() ->
			startCell(array( 'icon' => 'common/time', 'dimmed' => true)) -> text(DateUtils::formatFuzzy($row['lasttime'])) -> endCell() ->
			startCell() -> icon('common/page') -> text($row['page_title']) -> endCell() ->
			startCell() -> text($row['visits']) -> endCell() ->
			startCell() -> text($row['sessions']) -> endCell() ->
			startCell() -> text($row['ips']) -> endCell() ->
		endRow();
	}
	Database::free($result);

	$writer->endList();
}

function paths() {
	$query = new StatisticsQuery();
	$query -> withTime(Request::getString('time'));
	$result = StatisticsService::searchPaths($query);
	
	$writer = new ListWriter();

	$writer->startList()->
		startHeaders()->
		header(array('title'=>array('From','da'=>'Fra')))->
		header(array('title'=>array('To','da'=>'Til')))->
		header(array('title'=>array('Path','da'=>'Sti')))->
		header(array('title'=>array('Page','da'=>'Side')))->
		header(array('title'=>array('Visits','da'=>'Besøg')))->
		header(array('title'=>array('Sessions','da'=>'Sessioner')))->
		header(array('title'=>array('Devices','da'=>'Maskiner')))->
	endHeaders();

	while($row = Database::next($result)) {
		$writer->startRow()->
			startCell(array('icon'=>'common/time'))->text(DateUtils::formatFuzzy($row['firsttime']))->endCell()->
			startCell(array('icon'=>'common/time'))->text(DateUtils::formatFuzzy($row['lasttime']))->endCell()->
			startCell()->text($row['uri'])->endCell()->
			startCell()->icon('common/page')->text($row['page_title'])->endCell()->
			startCell()->text($row['visits'])->endCell()->
			startCell()->text($row['sessions'])->endCell()->
			startCell()->text($row['ips'])->endCell()->
		endRow();
	}
	Database::free($result);
	$writer->endList();
}

function refererers() {
	$writer = new ListWriter();

	$writer->startList()->
		startHeaders()->
		header(array('title'=>array('From','da'=>'Fra')))->
		header(array('title'=>array('To','da'=>'Til')))->
		header(array('title'=>array('Path','da'=>'Sti')))->
		header(array('title'=>array('Page','da'=>'Side')))->
		header(array('title'=>array('Visits','da'=>'Besøg')))->
		header(array('title'=>array('Sessions','da'=>'Sessioner')))->
		header(array('title'=>array('Devices','da'=>'Maskiner')))->
	endHeaders();

	$sql = "select UNIX_TIMESTAMP(max(statistics.time)) as lasttime,UNIX_TIMESTAMP(min(statistics.time)) as firsttime,count(distinct statistics.id) as visits,count(distinct statistics.session) as sessions,count(distinct statistics.ip) as ips,statistics.`referer` from statistics group by statistics.`referer` order by statistics.time";
	$result = Database::select($sql);
	while($row = Database::next($result)) {
		$writer->startRow()->
			startCell(array('icon'=>'common/time'))->text(DateUtils::formatFuzzy($row['firsttime']))->endCell()->
			startCell(array('icon'=>'common/time'))->text(DateUtils::formatFuzzy($row['lasttime']))->endCell()->
			startCell()->text($row['uri'])->endCell()->
			startCell()->icon('common/page')->text($row['page_title'])->endCell()->
			startCell()->text($row['visits'])->endCell()->
			startCell()->text($row['sessions'])->endCell()->
			startCell()->text($row['ips'])->endCell()->
		endRow();
	}
	Database::free($result);
	$writer->endList();
}

function visits() {
	$query = new StatisticsQuery();
	$query->withTime(Request::getString('time'));
	
	$result = StatisticsService::searchVisits($query);

	$writer = new ListWriter();

	$writer->startList() ->
		startHeaders() ->
			header(array('title'=>array('Date','da'=>'Dato'))) ->
			header(array('title'=>array('Pageviews','da'=>'Sidevisninger'))) ->
			header(array('title'=>array('Sessioner','da'=>'Sessions'))) ->
			header(array('title'=>array('Devices','da'=>'Maskiner'))) ->
		endHeaders();

	foreach ($result as $row) {
		$writer->startRow()->
			startCell(array('icon'=>'common/time')) -> text($row['label']) -> endCell()->
			startCell() -> text($row['hits']) -> endCell() ->
			startCell() -> text($row['sessions']) -> endCell() ->
			startCell() -> text($row['ips']) -> endCell() ->
		endRow();
	}
	$writer->endList();
}
?>