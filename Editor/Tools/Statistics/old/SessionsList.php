<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Statistics
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Include/Session.php';

require_once 'Functions.php';

$tab = requestGetNumber('tab',1);

$parms = buildSql();
// Get total length of list
$sql="SELECT count(distinct statistics.session) as sessions FROM statistics ".(strlen($parms) ? " where ".$parms : "");
$row = Database::selectFirst($sql);
$count = $row['sessions'];
$gui='<xmlwebgui xmlns="uri:XmlWebGui">'.
'<configuration path="../../../"/>'.
'<interface>'.
'<list xmlns="uri:List" width="100%" margin="3" sort="true">'.
'<tabgroup>';


$interval = floor($count/14)-1;
if ($interval<50) $interval=50;
$tabcount = ceil($count/$interval);
for ($i=1;$i<=$tabcount;$i++) {
	$from = (($i-1)*$interval+1);
	$to = ($i*$interval);
	if ($to>$count) $to = $count;
	$gui.='<tab title="'.$from.'-'.$to.'"'.($tab==$i ? ' style="Hilited"' : ' link="SessionsList.php?tab='.$i.'"').'/>';
}
$gui.=
'</tabgroup>'.
'<content>';

$gui.=
'<headergroup>'.
'<header title="Session" type="number" width="40%"/>'.
'<header title="Start" type="number" align="right" width="15%" nowrap="true"/>'.
'<header title="Tid" type="number" align="right" width="10%"/>'.
'<header title="Hits" type="number" align="right" width="10%"/>'.
'<header title="Graf" type="number" align="right" width="15%"/>'.
'<header title="Adresse" type="number" align="right" width="10%"/>'.
'</headergroup>';

$total=getTotalCount('page');

$from = (($tab-1)*$interval);
$to = ($tab*$interval);
if ($to>$count) $to = $count;

$sql="SELECT statistics.ip,count(statistics.id) as hits,DATE_FORMAT(min(statistics.time),\"%d.%m.%Y %H:%i:%s\") as begin,DATE_FORMAT(max(statistics.time),\"%d.%m.%Y %H:%i:%s\") as end, UNIX_TIMESTAMP(max(statistics.time))-UNIX_TIMESTAMP(min(statistics.time)) as duration,statistics.session FROM statistics ".(strlen($parms)>0 ? " where ".$parms : "")." group by statistics.session order by statistics.time limit ".$from.",".$to;
$max = findMaxHit($sql);
$result = Database::select($sql);	
while($row = Database::next($result)) {
	$gui.=
	'<row link="SessionDetails.php?id='.$row['session'].'" target="_parent">'.
	'<cell>'.$row['session'].'</cell>'.
	'<cell>'.$row['begin'].'</cell>'.
	'<cell index="'.$row['duration'].'">'.secondsToDuration($row['duration']).'</cell>'.
	'<cell>'.$row['hits'].'</cell>'.
	'<cell index="'.$row['hits'].'"><progress value="'.round($row['hits']/$max*100).'" help="'.round($row['hits']/$total*100,1).' %"/></cell>'.
	'<cell>'.$row['ip'].'</cell>'.
	'</row>';
}
Database::free($result);

$gui.=
'</content>'.
'</list>'.
'</interface>'.
'</xmlwebgui>';


$elements = array("List");

writeGui($xwg_skin,$elements,$gui);

function secondsToDuration($secs) {
	if ($secs<60) {
		return $secs.' sek.';
	}
	elseif ($secs<60*60) {
		return round($secs/60).' min.';
	}
	else {
		return round($secs/60/60).' timer';
	}
}
?>