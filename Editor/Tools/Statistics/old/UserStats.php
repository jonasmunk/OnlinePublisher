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
//$startdate = trim(requestPostDateTime('startdate'));
//$enddate = trim(requestPostDateTime('enddate'));

$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../../"/>'.
'<interface background="Desktop">'.
'<window xmlns="uri:Window" width="100%" height="100%">'.
'<titlebar title="Besøgs statistik" icon="Tool/Statistics"/>'.

'<content valign="top">'.
//'<interface>'.
'<form xmlns="uri:Form" action="?type=advanced" method="post">'.

'<list xmlns="uri:List" width="100%" margin="3" sort="true">'.
'<tabgroup align="left">'.
'<tab link="?type=hour" title="Sidste time" help="Besøg den sidste time" style="'.getStyle($type,"hour").'"/>'.
'<tab link="?type=day" title="Sidste 24 timer" target="" help="Besøg de sidste 24 timer" style="'.getStyle($type,"day").'"/>'.
'<tab link="?type=7day" title="Sidste 7 dage" target="" help="Besøg de sidste 7 dage" style="'.getStyle($type,"7day").'"/>'.
'<tab link="?type=14day" title="Sidste 14 dage" target="" help="Besøg de sidste 14 dage" style="'.getStyle($type,"14day").'"/>'.
'<tab link="?type=month" title="Sidste 30 dage" target="" help="Besøg de sidste 30 dage" style="'.getStyle($type,"month").'"/>'.
'<tab link="?type=6month" title="Sidste 6 måneder" target="" help="Besøg de sidste 6 måneder" style="'.getStyle($type,"6month").'"/>'.
'<tab link="?type=12month" title="Sidste 12 måneder" target="" help="Besøg de sidste 12 måneder" style="'.getStyle($type,"12month").'"/>'.
'<tab link="?type=5year" title="Sidste 5 år" target="" help="Besøg de sidste 5 år" style="'.getStyle($type,"5year").'"/>'.
'<tab link="?type=total" title="Total" target="" help="Besøg siden hjemmesidens opstart" style="'.getStyle($type,"total").'"/>'.
'<tab link="?type=advanced" title="Avanceret" target="" help="Avanceret" style="'.getStyle($type,"advanced").'"/>'.
'</tabgroup>'.
'<content valign="top">'.
getStatistics($type).
'</content>'.
'</list>'.
dateSettings($type).
'</form>'.
//'</interface>'.
'</content>'.
'</window>'.
'</interface>'.
'</xmlwebgui>';

function getStyle($selectedType, $myType){
	$value = "Standard";
	if($selectedType == $myType){
		$value = "Hilited";
	}
	return $value;
}

function getStatistics($type){
		$currentYear = date("Y");
		$currentMonth = date("m");
		$currentDay = date("d");
		if($type=='hour'){return generateUserStatistics(subtractHoursFromCurrentDate(1,"Y-m-d-H-i-s"),date("Y-m-d-H-i-s"),"%Y-%m-%d-%H-%i-%s","minute");} else
		if($type=='day'){return generateUserStatistics(subtractHoursFromCurrentDate(24,"Y-m-d-H"),date("Y-m-d-H"),"%Y-%m-%d-%H","hour");} else
		if($type=='7day'){return generateUserStatistics(subtractHoursFromCurrentDate(168,"Y-m-d"),date("Y-m-d"),"%Y-%m-%d","day");} else 
		if($type=='14day'){return generateUserStatistics(subtractHoursFromCurrentDate(336,"Y-m-d"),date("Y-m-d"),"%Y-%m-%d","day");} else 
		if($type=='month'){return generateUserStatistics(subtractHoursFromCurrentDate(720,"Y-m-d"),date("Y-m-d"),"%Y-%m-%d","day");} else 
		if($type=='6month'){return generateUserStatistics(subtractHoursFromCurrentDate(4320,"Y-m-d"),date("Y-m-d"),"%Y-%m-%d","month");} else
		if($type=='12month'){return generateUserStatistics(subtractHoursFromCurrentDate(8640,"Y-m-d"),date("Y-m-d"),"%Y-%m-%d","month");} else
		if($type=='5year'){return generateUserStatistics(subtractHoursFromCurrentDate(43200,"Y-m-d"),date("Y-m-d"),"%Y-%m-%d","year");} else
		if($type=='total'){return generateUserStatistics(date("Y-m-d",0),date("Y-m-d"),"%Y-%m-%d","year");} 
		if($type=='advanced'){
			$startdate = trim(requestPostDateTime('startdate'));
			$enddate = trim(requestPostDateTime('enddate'));
			$showBy = trim(requestPostText('showBy'));
		return getAdvancedDateSettings($startdate,$enddate,$showBy);}
		
}

function getAdvancedDateSettings($startdate,$enddate,$showBy){
	$output =	'<headergroup>'.
					'<header title=""/>'.
					'</headergroup>';
	if(strlen($startdate) > 0){
		$output = generateUserStatistics(date("Y-m-d-H-i-s",$startdate),date("Y-m-d-H-i-s",$enddate),"%Y-%m-%d-%H-%i-%s",$showBy);
	}
	return $output;
}

function dateSettings($type){
 	$output = "";
	if($type == 'advanced'){
		$showBy = trim(requestPostText('showBy'));
		$output.=	'<group size="Large">'.
					'<datetime badge="Fra:" name="startdate" object="StartDate" value="'.xwgTimeStamp2dateTime(mktime()-(7*60*60*24)).'" display="dmy"/>'.
					'<datetime badge="Til og med:" name="enddate" object="EndDate" value="'.xwgTimeStamp2dateTime(mktime()).'" display="dmy"/>'.
					'<select badge="Specificer efter:" name="showBy">'.
					'<option title="Minuter" value="minute" '.($showBy=='minute' ? 'selected="true"' : '').'/>'.
					'<option title="Timer" value="hour" '.($showBy=='hour' ? 'selected="true"' : '').'/>'.
					'<option title="Dage" value="day" '.($showBy=='day' ? 'selected="true"' : '').'/>'.
					'<option title="Måneder" value="month" '.($showBy=='month' ? 'selected="true"' : '').'/>'.
					'<option title="År" value="year" '.($showBy=='year' ? 'selected="true"' : '').'/>'.
					'</select>'.
					'</group>'.
					'<buttongroup size="Large">'.
					'<button title="Annuller" link="?type=month"/>'.
					'<button title="Hent statistik" submit="true" style="Hilited"/>'.
					'</buttongroup>';
	}

	return $output;
}

function generateUserStatistics($fromDate = "", $toDate="",$dateFormat="%Y-%m", $showBy=""){
		$sqlFromTo = ' where date_format(time,"'.$dateFormat.'") >"'.$fromDate.'" AND date_format(time,"'.$dateFormat.'") <="'.$toDate.'"';
		$sqlDefault = ' where date_format(time, "'.$dateFormat.'") = "'.$id.'"';
				
		$output =	'<headergroup>'.
					'<header title="'.getHeader($showBy).'"/>'.
					'<header title="Unikke hits"/>'.
					'<header title="Hits"/>'.
					'<header title="Unikke ip adresser"/>'.
					
					'</headergroup>';
		$sql = 'SELECT count(distinct session) as uniquehits, count(distinct ip) as networks, count(*) as hits ';
		
			
		$sql.= getDateFormat($showBy);
		$sql.=' FROM statistics ';
		if(strlen($fromDate)>0)
				$sql.= $sqlFromTo;
			else
				$sql.= $sqlDefault;
		$sql.=getGroupByClause($showBy).' '.getOrderByClause($showBy).' DESC';

		$result = Database::select($sql);	
		
		while($row = Database::next($result)){

			
			$output.=	'<row>'.
						'<cell>'.$row['time'].'</cell>'.
						'<cell>'.$row['uniquehits'].'</cell>'.
						'<cell>'.$row['hits'].'</cell>'.
						'<cell>'.$row['networks'].'</cell>'.
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