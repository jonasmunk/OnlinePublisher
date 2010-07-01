<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Statistics
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';
require_once '../../Include/XmlWebGui.php';
require_once 'Functions.php';
$type = trim(requestGetText('type'));
$session = trim(requestGetText('session'));
$ip = trim(requestGetText('ip'));
$host = trim(requestGetText('host'));
if(strlen($host) < 1) $host = "ukendt";
$browser = trim(requestGetText('browser'));
$sessionPerTab = 30;


$sql = 'SELECT session FROM statistics group by session';
$result = Database::select($sql);
$numrows = mysql_num_rows($result);
$numOfTabs = 0;
if($numrows > 0){
	$numOfTabs = ($numrows / $sessionPerTab);
}
if(strlen($session) > 0){
	$gui=
	'<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../../"/>'.
	'<interface background="Desktop">'.
	'<window xmlns="uri:Window" width="100%" height="100%">'.
	'<parent title="Bruger statistik" link="?type='.$type.'"/>'.
	'<titlebar title="Bruger analyse - ip: '.$ip.', server: '.$host.', browser: '.$browser.'" icon="Tool/Statistics"/>'.
	
	'<content valign="top">'.
	//'<interface>'.
	'<form xmlns="uri:Form" action="?type=advanced" method="post">'.
	
	'<list xmlns="uri:List" width="100%" margin="3" sort="true">'.
	
	'<content valign="top">';
	
	$gui.=generationSessionStats($session);
	
	$gui.='</content>'.
	'</list>'.
	'</form>'.
	//'</interface>'.
	'</content>'.
	'</window>'.
	'</interface>'.
	'</xmlwebgui>';
}
else{
	$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../../"/>'.
	'<interface background="Desktop">'.
	'<window xmlns="uri:Window" width="100%" height="100%">'.
	'<titlebar title="Bruger statistik" icon="Tool/Statistics"/>'.
	
	'<content valign="top">'.
	//'<interface>'.
	'<form xmlns="uri:Form" action="?type=advanced" method="post">'.
	
	'<list xmlns="uri:List" width="100%" margin="3" sort="true">'.
	'<tabgroup align="left">';
	$start = 1;
	$next = $numrows;
	for($i = 0; $numOfTabs > $i; $i++ ){
		$next = $next - $sessionPerTab;
		$end = $start + ($next > 0 ? $sessionPerTab-1 : $next+$sessionPerTab-1);
		$gui.='<tab link="?type='.$i.'" title="'.$start." - ".$end.'" style="'.getStyle($type,$i).'" />';
		$start = $start + $sessionPerTab;
	}
	$gui.='</tabgroup>'.
	'<content valign="top">';
	
	$gui.=generateUserStatistics($type,$sessionPerTab);
	
	$gui.='</content>'.
	'</list>'.
	'</form>'.
	//'</interface>'.
	'</content>'.
	'</window>'.
	'</interface>'.
	'</xmlwebgui>';
}
function getStyle($selectedType, $myType){
	$value = "Standard";
	if($selectedType == $myType){
		$value = "Hilited";
	}
	return $value;
}



function generateUserStatistics($type,$sessionPerTab){
$sql = 'select ip,session,agent,host,count(session) as hits,DATE_FORMAT(min(time),"%d.%m.%Y %H:%i:%s") as begin,DATE_FORMAT(max(time),"%d.%m.%Y %H:%i:%s") as end from statistics group by session order by time LIMIT '.($type * $sessionPerTab).','.$sessionPerTab;
$result = Database::select($sql);
		$output =	'<headergroup>'.
					'<header title="#"/>'.
					'<header title="Ip adresse"/>'.
					'<header title="Browser"/>'.					
					'<header title="Starttid"/>'.
					'<header title="Sluttid"/>'.
					'<header title="Hits"/>'.
					'</headergroup>';
		$count = ($type * $sessionPerTab);
		//echo $sql;
		while($row = Database::next($result)){
			$count++;
			
			$output.=	'<row link="?session='.$row['session'].'&amp;type='.$type.'&amp;ip='.$row['ip'].'&amp;host='.$row['host'].'&amp;browser='.getBrowser($row['agent']).'">'.
						'<cell>'.$count.'</cell>'.
						'<cell>'.$row['ip'].'</cell>'.
						'<cell>'.getBrowser($row['agent']).'</cell>'.
						'<cell>'.$row['begin'].'</cell>'.
						'<cell>'.$row['end'].'</cell>'.
						'<cell>'.$row['hits'].'</cell>'.
						'</row>';
			
		}
		
		Database::free($result);
		
		return $output;
	}
	
function generationSessionStats($session){
	$sql = 'SELECT uri, value, referer, date_format(time,"%d.%m.%Y %H:%i:%s") as starttime, title FROM statistics LEFT JOIN page ON (statistics.value=page.id) where session="'.$session.'" order by time';
	$result = Database::select($sql);
		$output =	'<headergroup>'.
					'<header title="Starttid"/>'.
					'<header title="Adresse"/>'.
					'<header title="Side"/>'.
					'<header title="Referenceside"/>'.
					'</headergroup>';
		
		while($row = Database::next($result)){
	
			$output.=	'<row>'.
						'<cell>'.$row['starttime'].'</cell>'.
						'<cell>'.escapeHTML($row['uri']).'</cell>'.
						'<cell>'.$row['title'].'</cell>'.
						'<cell>'.escapeHTML($row['referer']).'</cell>'.
						'</row>';
			
		}
		
		Database::free($result);
		
		return $output;

}
	
function getHeader($showBy){
	$header = "";
	if($showBy=='minute') $header.='Tidspunkt'; 
	if($showBy=='hour')   $header.='Time';
	if($showBy=='day')    $header.='Dag';
	if($showBy=='month')  $header.='Måned';
	if($showBy=='year')   $header.='År';
	return $header;
	
}


function getDateFormat($showBy){
	$sql = " ,";
	if($showBy=='minute') $sql.='date_format(time,"%d.%m.%Y %H:%i") as time'; 
	if($showBy=='hour')   $sql.='date_format(time,"%d.%m.%Y %H:00") as time';
	if($showBy=='day')    $sql.='date_format(time,"%d.%m.%Y") as time';
	if($showBy=='month')  $sql.='date_format(time,"%m.%Y") as time';
	if($showBy=='year')   $sql.='date_format(time,"%Y") as time';
	return $sql;
}


function getGroupByClause($showBy){
	$sql = "";
	if($showBy=='minute') $sql='group by date_format(time,"%Y-%m-%d-%H-%i")'; 
	if($showBy=='hour')   $sql='group by date_format(time,"%Y-%m-%d-%H")';
	if($showBy=='day')    $sql='group by date_format(time,"%Y-%m-%d")';
	if($showBy=='month')  $sql='group by date_format(time,"%Y-%m")';
	if($showBy=='year')   $sql='group by date_format(time,"%Y")';
	return $sql;
}

function getOrderByClause($showBy){
	$sql = "";
	if($showBy=='minute') $sql='order by date_format(time,"%Y-%m-%d-%H-%i")'; 
	if($showBy=='hour')   $sql='order by date_format(time,"%Y-%m-%d-%H")';
	if($showBy=='day')    $sql='order by date_format(time,"%Y-%m-%d")';
	if($showBy=='month')  $sql='order by date_format(time,"%Y-%m")';
	if($showBy=='year')   $sql='order by date_format(time,"%Y")';
	return $sql;
}

$elements = array("List","Window","Frame","Form","Html");

writeGui($xwg_skin,$elements,$gui);

?>