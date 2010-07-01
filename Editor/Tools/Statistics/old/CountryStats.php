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
$startdate = trim(requestPostDateTime('startdate'));
$enddate = trim(requestPostDateTime('enddate'));

$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../../"/>'.
'<interface background="Desktop">'.
'<window xmlns="uri:Window" width="100%" height="100%">'.
'<titlebar title="Lande statistik" icon="Tool/Statistics"/>'.

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
		if($type=='hour'){return generateCountryStatistics(subtractHoursFromCurrentDate(1,"Y-m-d-H-i-s"),date("Y-m-d-H-i-s"),"%Y-%m-%d-%H-%i-%s");} else
		if($type=='day'){return generateCountryStatistics(subtractHoursFromCurrentDate(24,"Y-m-d-H"),date("Y-m-d-H"),"%Y-%m-%d-%H");} else
		if($type=='7day'){return generateCountryStatistics(subtractHoursFromCurrentDate(168,"Y-m-d"),date("Y-m-d"),"%Y-%m-%d");} else 
		if($type=='14day'){return generateCountryStatistics(subtractHoursFromCurrentDate(336,"Y-m-d"),date("Y-m-d"),"%Y-%m-%d");} else 
		if($type=='month'){return generateCountryStatistics(subtractHoursFromCurrentDate(720,"Y-m-d"),date("Y-m-d"),"%Y-%m-%d");} else 
		if($type=='6month'){return generateCountryStatistics(subtractHoursFromCurrentDate(4320,"Y-m-d"),date("Y-m-d"),"%Y-%m-%d");} else
		if($type=='12month'){return generateCountryStatistics(subtractHoursFromCurrentDate(8640,"Y-m-d"),date("Y-m-d"),"%Y-%m-%d");} else
		if($type=='5year'){return generateCountryStatistics(subtractHoursFromCurrentDate(43200,"Y-m-d"),date("Y-m-d"),"%Y-%m-%d");} else
		if($type=='total'){return generateCountryStatistics(date("Y-m-d",0),date("Y-m-d"),"%Y-%m-%d");} 
		if($type=='advanced'){
			$startdate = trim(requestPostDateTime('startdate'));
			$enddate = trim(requestPostDateTime('enddate'));
		return getAdvancedDateSettings($startdate,$enddate);}
		
}

function getAdvancedDateSettings($startdate,$enddate){
	$output =	'<headergroup>'.
					'<header title=""/>'.
					'</headergroup>';
	if(strlen($startdate) > 0){
		$output = generateCountryStatistics(date("Y-m-d-H-i-s",$startdate),date("Y-m-d-H-i-s",$enddate),"%Y-%m-%d-%H-%i-%s");
	}
	return $output;
}

function dateSettings($type){
 	$output = "";
	if($type == 'advanced'){
		$output.=	'<group size="Large">'.
					'<datetime badge="Fra:" name="startdate" object="StartDate" value="'.xwgTimeStamp2dateTime(mktime()-(7*60*60*24)).'" display="dmy"/>'.
					'<datetime badge="Til og med:" name="enddate" object="EndDate" value="'.xwgTimeStamp2dateTime(mktime()).'" display="dmy"/>'.
					'</group>'.
					'<buttongroup size="Large">'.
					'<button title="Annuller" link="?type=month"/>'.
					'<button title="Hent statistik" submit="true" style="Hilited"/>'.
					'</buttongroup>';
	}
	return $output;
}

function generateCountryStatistics($fromDate = "", $toDate="",$dateFormat="%Y-%m"){
		$sqlFromTo = ' where date_format(time,"'.$dateFormat.'") >"'.$fromDate.'" AND date_format(time,"'.$dateFormat.'") <="'.$toDate.'"';
		$sqlDefault = ' where date_format(time, "'.$dateFormat.'") = "'.$id.'"';
		$sql = 'SELECT count(distinct session) as uniquehits, count(distinct ip) as networks, count(*) as hits FROM statistics';
		
		if(strlen($fromDate)>0)
				$sql.= $sqlFromTo;
			else
				$sql.= $sqlDefault;
		$result = Database::select($sql);
		$totalHits = Database::next($result);
		$maxHits = $totalHits['uniquehits'];
		
		$output =	'<headergroup>'.
					'<header title="#"/>'.
					'<header title="Unikke hits"/>'.
					'<header title="%"/>'.
					'<header title="Hits"/>'.
					'<header title="Unikke ip adresser"/>'.	
					'<header title="Lande"/>'.
					'</headergroup>';
		$sql = 'SELECT count(distinct session) as uniquehits, count(distinct ip) as networks, count(*) as hits, country FROM statistics';
		
			if(strlen($fromDate)>0)
				$sql.= $sqlFromTo;
			else
				$sql.= $sqlDefault;
		$sql.=' group by country ORDER BY uniquehits DESC';
		
		$result = Database::select($sql);	
		$count = 0;
		while($row = Database::next($result)){
			$count++;
			$percentage = round(($row['uniquehits']/$maxHits) * 100,2);
			$countryNumber = country_code_to_number($row['country']);
			$country = country_names($countryNumber);
			$output.=	'<row>'.
						'<cell>'.$count.'</cell>'.						
						'<cell>'.$row['uniquehits'].'</cell>'.
						'<cell>'.$percentage.'</cell>'.
						'<cell>'.$row['hits'].'</cell>'.
						'<cell>'.$row['networks'].'</cell>'.
						'<cell>'.$country.'</cell>'.
						'</row>';
			
		}
		
		Database::free($result);
		
		return $output;
	}

$elements = array("List","Window","Frame","Form","Html");

writeGui($xwg_skin,$elements,$gui);

?>