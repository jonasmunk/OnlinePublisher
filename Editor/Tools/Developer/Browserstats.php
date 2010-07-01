<?php
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Include/Session.php';


$sql="SELECT count(distinct session) as sessions, count(distinct ip) as ips, count(id) as hits,agent FROM statistics group by agent order by agent";
$analyzed = array();
$result = Database::select($sql);	
while($row = Database::next($result)) {
	//echo $row['agent'].' = '.$row['hits'].'<br>';
	$trans = getAgent($row['agent']);
	if (array_key_exists($trans,$analyzed)) {
		$analyzed[$trans]['hits']+=$row['hits'];
		$analyzed[$trans]['sessions']+=$row['sessions'];
		$analyzed[$trans]['ips']+=$row['ips'];
	} else {
		$analyzed[$trans]=array('hits' => $row['hits'],'sessions' => $row['sessions'],'ips' => $row['ips']);
	}
}
Database::free($result);

print_r($analyzed);

function getAgent($agent) {
	if (strpos($agent, 'AppleWebKit')!==false && strpos($agent, 'Safari')!==false) {
		return 'Safari';
	}
	elseif (strpos($agent, 'Gecko')!==false && strpos($agent, 'Camino')!==false) {
		return 'Camino';
	}
	elseif (strpos($agent, 'Gecko')!==false && strpos($agent, 'Firefox')!==false) {
		return 'Firefox';
	}
	elseif (strpos($agent, 'Gecko')!==false) {
		return 'Gecko';
	}
	elseif (strpos($agent, 'MSIE 5.5')!==false) {
		return 'Internet Explorer 5.5';
	}
	elseif (strpos($agent, 'MSIE 5')!==false) {
		return 'Internet Explorer 5';
	}
	elseif (strpos($agent, 'MSIE 6')!==false) {
		return 'Internet Explorer 6';
	}
	elseif (strpos($agent, 'W3C_Validator')!==false) {
		return 'W3C Validator';
	}
	elseif (strpos($agent, 'W3C_CSS_Validator')!==false) {
		return 'W3C CSS Validator';
	}
	elseif (strpos($agent, 'Mozilla/4')!==false) {
		return 'Mozilla 4+';
	}
	else {
		return $agent;
	}
}
?>