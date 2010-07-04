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

$id = requestGetText('id');

require_once 'Functions.php';
$sql="SELECT DATE_FORMAT(min(time),\"%d.%m.%Y %H:%i:%s\") as min,DATE_FORMAT(max(time),\"%d.%m.%Y %H:%i:%s\") as max from statistics where session=".Database::text($id);
$row = Database::selectFirst($sql);

$gui='<xmlwebgui xmlns="uri:XmlWebGui">'.
'<configuration path="../../../"/>'.
'<interface>'.
'<list xmlns="uri:List" width="100%" margin="3" sort="true">'.
'<tabgroup text="'.$row['min'].' -&gt; '.$row['max'].'"/>'.
'<content>';

$gui.=
'<headergroup>'.
'<header title="Tidspunkt" type="number" width="10%" nowrap="true"/>'.
'<header title="Titel" width="40%"/>'.
'<header title="Reference" width="50%"/>'.
'</headergroup>';

$total=getTotalCount('page');
$parms = buildSql();


$sql="SELECT statistics.referer,statistics.type,page.title as pagetitle,object.title as filetitle,DATE_FORMAT(time,\"%H:%i:%s\") as time FROM statistics left join page on page.id = statistics.value left join object on object.id = statistics.value and object.type='file' where session=".Database::text($id)." order by time";
$max = findMaxHit($sql);
$result = Database::select($sql);	
while($row = Database::next($result)) {
	$gui.=
	'<row link="SessionDetails.php?id='.$row['session'].'" target="_parent">'.
	'<cell>'.$row['time'].'</cell>'.
	'<cell>'.($row['type']=='page' ?
	'<icon icon="Web/Page"/><text>'.encodeXML($row['pagetitle']).'</text>' :
	'<icon icon="File/Generic"/><text>'.encodeXML($row['filetitle']).'</text>').
	'</cell>'.
	'<cell>'.encodeXML($row['referer']).'</cell>'.
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