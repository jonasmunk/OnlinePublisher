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

$mode = getRequestToolSessionVar('statistics','visitors.mode','mode','weeks');

$gui='<xmlwebgui xmlns="uri:XmlWebGui">'.
'<configuration path="../../../"/>'.
'<interface>'.
'<list xmlns="uri:List" width="100%" margin="3" sort="true">'.
'<tabgroup align="left">'.
'<tab title="År"'.($mode=='years' ? ' style="Hilited"' : ' link="VisitorsList.php?mode=years"').'/>'.
'<tab title="Måneder"'.($mode=='months' ? ' style="Hilited"' : ' link="VisitorsList.php?mode=months"').'/>'.
'<tab title="Uger"'.($mode=='weeks' ? ' style="Hilited"' : ' link="VisitorsList.php?mode=weeks"').'/>'.
'<tab title="Alle dage"'.($mode=='days' ? ' style="Hilited"' : ' link="VisitorsList.php?mode=days"').'/>'.
'<tab title="Ugedage"'.($mode=='daysOfWeek' ? ' style="Hilited"' : ' link="VisitorsList.php?mode=daysOfWeek"').'/>'.
'<tab title="Dage om måneden"'.($mode=='daysOfMonth' ? ' style="Hilited"' : ' link="VisitorsList.php?mode=daysOfMonth"').'/>'.
'<tab title="Timer i døgnet"'.($mode=='hours' ? ' style="Hilited"' : ' link="VisitorsList.php?mode=hours"').'/>'.
'</tabgroup>'.
'<content>';

$parms = buildSql();
$sql="SELECT count(statistics.id) as total FROM statistics ".(strlen($parms['where'])>0 ? " where ".$parms : "");
$row=Database::selectFirst($sql);
$total=$row['total'];
switch ($mode) {
	case "years": $gui.=buildYears($total); break;
	case "months": $gui.=buildMonths($total); break;
	case "weeks": $gui.=buildWeeks($total); break;
	case "days": $gui.=buildDays($total); break;
	case "daysOfMonth": $gui.=buildDaysOfMonth($total); break;
	case "daysOfWeek": $gui.=buildDaysOfWeek($total); break;
	case "hours": $gui.=buildHours($total); break;
}


$gui.=
'</content>'.
'</list>'.
'</interface>'.
'</xmlwebgui>';


$elements = array("List");

writeGui($xwg_skin,$elements,$gui);


function buildYears($total) {
	$gui=
	'<headergroup>'.
	'<header title="År" type="number" width="50%"/>'.
	'<header title="Graf" type="number" align="right" width="10%"/>'.
	'<header title="Hits" type="number" align="right" width="10%"/>'.
	'<header title="%" type="number" align="right" width="10%"/>'.
	'<header title="Sessioner" type="number" align="right" width="10%"/>'.
	'<header title="Adresser" type="number" align="right" width="10%"/>'.
	'</headergroup>';
	$sql=buildVisitorsSql('years');
	$max = findMaxHit($sql);
	$result = Database::select($sql);	
	while($row = Database::next($result)) {
		$gui.=
		'<row>'.
		'<cell>'.$row['label'].'</cell>'.
		'<cell index="'.$row['hits'].'"><progress value="'.round($row['hits']/$max*100).'"/></cell>'.
		'<cell>'.$row['hits'].'</cell>'.
		'<cell index="'.$row['hits'].'">'.round($row['hits']/$total*100,1).'</cell>'.
		'<cell>'.$row['sessions'].'</cell>'.
		'<cell>'.$row['ips'].'</cell>'.
		'</row>';
	}
	Database::free($result);
	return $gui;
}

function buildMonths($total) {
	$gui=
	'<headergroup>'.
	'<header title="Måned" type="number" width="50%"/>'.
	'<header title="Graf" type="number" align="right" width="10%"/>'.
	'<header title="Hits" type="number" align="right" width="10%"/>'.
	'<header title="%" type="number" align="right" width="10%"/>'.
	'<header title="Sessioner" type="number" align="right" width="10%"/>'.
	'<header title="Adresser" type="number" align="right" width="10%"/>'.
	'</headergroup>';
	$sql=buildVisitorsSql('months');
	$max = findMaxHit($sql);
	$result = Database::select($sql);	
	while($row = Database::next($result)) {
		$gui.=
		'<row>'.
		'<cell index="'.$row['labelIndex'].'">'.parseMonth($row['label']).'</cell>'.
		'<cell index="'.$row['hits'].'"><progress value="'.round($row['hits']/$max*100).'"/></cell>'.
		'<cell>'.$row['hits'].'</cell>'.
		'<cell index="'.$row['hits'].'">'.round($row['hits']/$total*100,1).'</cell>'.
		'<cell>'.$row['sessions'].'</cell>'.
		'<cell>'.$row['ips'].'</cell>'.
		'</row>';
	}
	Database::free($result);
	return $gui;
}

function parseMonth($val) {
	$months = array("","Januar","Februar","Marts","April","Maj","Juni","Juli","August","September","Oktober","November","December");
	$month = intval(substr($val,0,2));
	return $months[$month]." ".substr($val,4,2);
}

function buildHours($total) {
	$gui=
	'<headergroup>'.
	'<header title="Time" type="number" width="50%"/>'.
	'<header title="Graf" type="number" align="right" width="10%"/>'.
	'<header title="Hits" type="number" align="right" width="10%"/>'.
	'<header title="%" type="number" align="right" width="10%"/>'.
	'<header title="Sessioner" type="number" align="right" width="10%"/>'.
	'<header title="Adresser" type="number" align="right" width="10%"/>'.
	'</headergroup>';
	$sql=buildVisitorsSql('hours');
	$max = findMaxHit($sql);
	$result = Database::select($sql);	
	while($row = Database::next($result)) {
		$gui.=
		'<row>'.
		'<cell index="'.intval($row['label']).'">'.$row['label'].'.00+</cell>'.
		'<cell index="'.$row['hits'].'"><progress value="'.round($row['hits']/$max*100).'"/></cell>'.
		'<cell>'.$row['hits'].'</cell>'.
		'<cell index="'.$row['hits'].'">'.round($row['hits']/$total*100,1).'</cell>'.
		'<cell>'.$row['sessions'].'</cell>'.
		'<cell>'.$row['ips'].'</cell>'.
		'</row>';
	}
	Database::free($result);
	return $gui;
}

function buildWeeks($total) {
	$gui=
	'<headergroup>'.
	'<header title="Uge" type="number" width="50%"/>'.
	'<header title="Graf" type="number" align="right" width="10%"/>'.
	'<header title="Hits" type="number" align="right" width="10%"/>'.
	'<header title="%" type="number" align="right" width="10%"/>'.
	'<header title="Sessioner" type="number" align="right" width="10%"/>'.
	'<header title="Adresser" type="number" align="right" width="10%"/>'.
	'</headergroup>';
	$sql=buildVisitorsSql('weeks');
	$max = findMaxHit($sql);
	$result = Database::select($sql);	
	while($row = Database::next($result)) {
		$gui.=
		'<row>'.
		'<cell index="'.$row['labelIndex'].'">Uge '.substr($row['label'],0,2).' '.substr($row['label'],2).'</cell>'.
		'<cell index="'.$row['hits'].'"><progress value="'.round($row['hits']/$max*100).'"/></cell>'.
		'<cell>'.$row['hits'].'</cell>'.
		'<cell index="'.$row['hits'].'">'.round($row['hits']/$total*100,1).'</cell>'.
		'<cell>'.$row['sessions'].'</cell>'.
		'<cell>'.$row['ips'].'</cell>'.
		'</row>';
	}
	Database::free($result);
	return $gui;
}

function buildDays($total) {
	$gui=
	'<headergroup>'.
	'<header title="Dag" type="number" width="50%"/>'.
	'<header title="Graf" type="number" align="right" width="10%"/>'.
	'<header title="Hits" type="number" align="right" width="10%"/>'.
	'<header title="%" type="number" align="right" width="10%"/>'.
	'<header title="Sessioner" type="number" align="right" width="10%"/>'.
	'<header title="Adresser" type="number" align="right" width="10%"/>'.
	'</headergroup>';
	$sql=buildVisitorsSql('days');
	$max = findMaxHit($sql);
	$result = Database::select($sql);	
	while($row = Database::next($result)) {
		$gui.=
		'<row>'.
		'<cell index="'.$row['labelIndex'].'">'.$row['label'].'</cell>'.
		'<cell index="'.$row['hits'].'"><progress value="'.round($row['hits']/$max*100).'"/></cell>'.
		'<cell>'.$row['hits'].'</cell>'.
		'<cell index="'.$row['hits'].'">'.round($row['hits']/$total*100,1).'</cell>'.
		'<cell>'.$row['sessions'].'</cell>'.
		'<cell>'.$row['ips'].'</cell>'.
		'</row>';
	}
	Database::free($result);
	return $gui;
}

function buildDaysOfMonth($total) {
	$gui=
	'<headergroup>'.	
	'<header title="Dag" type="number" width="50%"/>'.
	'<header title="Graf" type="number" align="right" width="10%"/>'.
	'<header title="Hits" type="number" align="right" width="10%"/>'.
	'<header title="%" type="number" align="right" width="10%"/>'.
	'<header title="Sessioner" type="number" align="right" width="10%"/>'.
	'<header title="Adresser" type="number" align="right" width="10%"/>'.
	'</headergroup>';
	$sql=buildVisitorsSql('daysOfMonth');
	$max = findMaxHit($sql);
	$result = Database::select($sql);	
	while($row = Database::next($result)) {
		$gui.=
		'<row>'.
		'<cell index="'.intval($row['labelIndex']).'">'.$row['label'].'</cell>'.
		'<cell index="'.$row['hits'].'"><progress value="'.round($row['hits']/$max*100).'"/></cell>'.
		'<cell>'.$row['hits'].'</cell>'.
		'<cell index="'.$row['hits'].'">'.round($row['hits']/$total*100,1).'</cell>'.
		'<cell>'.$row['sessions'].'</cell>'.
		'<cell>'.$row['ips'].'</cell>'.
		'</row>';
	}
	Database::free($result);
	return $gui;
}

function buildDaysOfWeek($total) {
	$days=array("Søndag","Mandag","Tirsdag","Onsdag","Torsdag","Fredag","Lørdag");
	$gui=
	'<headergroup>'.	
	'<header title="Dag" type="number" width="50%"/>'.
	'<header title="Graf" type="number" align="right" width="10%"/>'.
	'<header title="Hits" type="number" align="right" width="10%"/>'.
	'<header title="%" type="number" align="right" width="10%"/>'.
	'<header title="Sessioner" type="number" align="right" width="10%"/>'.
	'<header title="Adresser" type="number" align="right" width="10%"/>'.
	'</headergroup>';
	$sql=buildVisitorsSql('daysOfWeek');
	$max = findMaxHit($sql);
	$result = Database::select($sql);	
	while($row = Database::next($result)) {
		$gui.=
		'<row>'.
		'<cell index="'.intval($row['label']).'">'.$days[$row['label']].'</cell>'.
		'<cell index="'.$row['hits'].'"><progress value="'.round($row['hits']/$max*100).'"/></cell>'.
		'<cell>'.$row['hits'].'</cell>'.
		'<cell index="'.$row['hits'].'">'.round($row['hits']/$total*100,1).'</cell>'.
		'<cell>'.$row['sessions'].'</cell>'.
		'<cell>'.$row['ips'].'</cell>'.
		'</row>';
	}
	Database::free($result);
	return $gui;
}
?>