<?php
/**
 * @package OnlinePublisher
 * @subpackage Services.Start
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Include/Functions.php';
require_once '../../Classes/InternalProblem.php';
require_once '../../Classes/StatisticsUtil.php';
$data = buildData();

$gui='<xmlwebgui xmlns="uri:XmlWebGui">'.
'<configuration path="../../../"/>'.
'<interface>
<layout xmlns="uri:Layout" width="100%" height="100%"><row><cell align="center" valign="middle">
<chart xmlns="uri:Chart" width="380" height="200">
	<body top="0" bottom="10" right="20" left="30" vertical="10" horizontal="10"/>
	<x-axis labels="'.$data['labels'].'" max-labels="100"/>
	<y-axis steps="10"/>
	<data type="line" color="#aea" width="2" values="'.$data['sessions'].'"/>
	<data type="line" color="#abc" width="2" values="'.$data['hits'].'"/>
</chart>
</cell></row></layout>
</interface>'.
'</xmlwebgui>';

$elements = array("Chart","Layout");
writeGui($xwg_skin,$elements,$gui);

function buildData() {
	$days = 21;
	StatisticsUtil::preProcess();
	$sql = "SELECT count(distinct statistics.session) as sessions, count(distinct statistics.ip) as ips, count(statistics.id) as hits,date_format(statistics.time, '%Y%m%d') as labelIndex,date_format(statistics.time, '%d-%m-%Y') as label FROM statistics where time>DATE_SUB(CURDATE(),INTERVAL ".$days." DAY) and known=0 and robot=0 group by label order by labelIndex";
	$result = Database::select($sql);
	$data = array();
	while ($row = Database::next($result)) {
		$data[] = $row;
	}
	Database::free($result);
	
	$min = date("Ydm",mktime(0,0,0,date('m'),date('d')-$days,date('Y')));
	$max = date("Ydm");
	
	$j = 0;
	$newData = array();
	for ($i=0;$i<=$days;$i++) {
		$index = date("Ymd",mktime(0,0,0,date('m'),date('d')-$days+$i,date('Y')));
		$label = date("d",mktime(0,0,0,date('m'),date('d')-$days+$i,date('Y')));
		if (count($data)>$j && $data[$j]['labelIndex']==$index) {
			$newData[] = array('hits' => $data[$j]['hits'],'sessions' => $data[$j]['sessions'],'label'=>$label);
			$j++;
		} else {
			$newData[] = array('hits' => 0,'sessions' => 0,'label'=>$label);
		}
	}
	$hits = "";
	$labels = "";
	$sessions = "";
	foreach ($newData as $item) {
		if (strlen($labels)>0) $labels.=',';
		$labels.="'".$item['label']."'";
		if (strlen($hits)>0) $hits.=',';
		$hits.=$item['hits'];
		if (strlen($sessions)>0) $sessions.=',';
		$sessions.=$item['sessions'];
	}
	return array('hits'=>$hits,'sessions'=>$sessions,'labels'=>$labels);
}
?>