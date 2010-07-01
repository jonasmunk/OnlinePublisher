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
include ("diagram.php");
global $xwg_skin;

$id = trim(requestGetText('id'));
$type = trim(requestGetText('type'));
$titleinf = trim(requestGetText('title'));
$imagefile = "";
$imagetag = "";
if($type==''){
	$titleinf = "sidste 12 måneder";
	$imagefile = "12month.png";
	$imagetag = '<html xmlns="uri:Html"><center><img src="'.$imagefile.'"/></center></html>';
	generateImage($imagefile);
}else{
	//if($type=='month'){
		//$imagefile = "month.png";}
}

	
	$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../../"/>'.
'<interface background="Desktop">'.
'<window xmlns="uri:Window" width="100%" height="100%">'.
'<tabgroup align="left"><tab link="link" target="target" title="titel" help="jkj" style="Hilited"/><tab link="" title="titel" target="" help="jkj" style="Hilited"/><tab title="titel" link="" target="" help="jkj" style="Disabled"/></tabgroup>'.
'<titlebar title="Statistik - '.$titleinf.'" icon="Tool/Statistics"/>'.
'<content valign="top">'.
//$imagetag.
//'<tabgroup><tab link="link" target="target" title="titel" help="jkj" style="Standard"/><tab link="" target="" help="jkj" style="Standard"/><tab link="" target="" help="jkj" style="Standard"/></tabgroup>'.
'<interface>'.

'<form xmlns="uri:Form" action="RemoveFromGroup.php" method="post">'.
'<list xmlns="uri:List" width="100%" margin="3" sort="true">'.
'<content valign="top">'.
getStatistics($type,$id).
'</content>'.
'</list>'.
'</form>'.
'</interface>'.
'</content>'.
'</window>'.
'</interface>'.
'</xmlwebgui>';

	function generateImage($imagename){
		$sql = 'SELECT count(distinct session) as hits, date_format(time, "%Y-%m") as yearmonth, date_format(now(),"%Y-%m") as today FROM statistics group by date_format(time, "%Y-%m") order by time DESC';
		$result = Database::select($sql);
	    
		$sql = 'SELECT count(distinct session) as maxNoOfHits FROM statistics';
		$maxHit = Database::selectFirst($sql);
		$diagramHeigth = $maxHit['maxNoOfHits'];
		$yearToday = getYear(date('Y-m'));
		$monthToday = getMonth(date('Y-m'));
		
		$D=new Diagram();
		$D->Img=@ImageCreate(560, 280) or die("Cannot create a new GD image."); 
		ImageColorAllocate($D->Img, 255, 255, 255); //background color
		$D->SetBorder(UTC($yearToday-1,$monthToday,15,0,0,0), UTC($yearToday,$monthToday,15,0,0,0), 0, $diagramHeigth);
		$D->SetFrame(80, 40, 520, 240);
		
		$D->SetText("","", "Unikke hits");
		$D->XScale=4;
		$D->Draw("", "#004080", false);
		$y0=$D->ScreenY(0);
		for ($i=12; $i>=1; $i--)
		{ 
		  $row = Database::next($result);
		  $year = getYear($row['yearmonth']);
		  $month = getMonth($row['yearmonth']);
		  $v=stringToInt($row['hits']);
		  if($v > $diagramHeigth){
		  	$diagramHeigth = $v;
		  }
		  
		  $actualMonth =  (($i+$monthToday)%12);
		  if($actualMonth == 0){$actualMonth = 12;}
		  if($actualMonth != $month){ $i = $i -($actualMonth - $month);	}
		  $y=$D->ScreenY($v);
		  $j=$D->ScreenX(UTC($yearToday-1,$i+$monthToday,1,0,0,0));
		  //echo $j." ";
		  if ($i%2==0) $D->Box($j-12, $y, $j+12, $y0, "v_blue.gif", "", "#FFFFFF", 1, "#000000", "Hits per month", "alert(\"".$v." hits\")");
		  else $D->Box($j-12, $y, $j+12, $y0, "v_red.gif", "", "#000000", 1, "#000000", "Hits per month", "alert(\"".$v." hits\")");
		  
		  		  		  
		}
		
		Database::free($result);
		ImagePng($D->Img, $imagename);
		ImageDestroy($D->Img);
	}
	
	
	function getStatistics($type,$id){
		if($type=='year'){return generateYearlyStatistics($id);} else
		if($type=='month'){return generateMonthlyStatistics($id);} else
		if($type=='hour'){return generateHourlyStatistics($id);} else 
		if($type=='hour12Months'){return generateHourlyStatistics($id,(date("Y")-1)."-".(date("m")),date("Y-m"));} else 
		if($type=='hourYear'){return generateHourlyStatistics($id,"","","%Y");} else 
		if($type=='hourTotal'){return generateHourlyStatistics("");} else
		if($type==''){return generate12MonthsStatistics($id);} else
		if($type=='url'){return generateURLStatistics($id);} else
		if($type=='url12Months'){return generateURLStatistics($id,(date("Y")-1)."-".(date("m")),date("Y-m"));} else
		if($type=='urlYear'){return generateURLStatistics($id,"","","%Y");} else  
		if($type=='urlTotal'){return generateURLStatistics("");} else
		if($type=='country'){return generateCountryStatistics($id);}else 
		if($type=='country12Month'){return generateCountryStatistics($id,(date("Y")-1)."-".(date("m")),date("Y-m"));}else
		if($type=='countryYear'){return generateCountryStatistics($id,"","","%Y");} else
		if($type=='countryTotal'){return generateCountryStatistics("");}else
		if($type=='browser'){return generateBrowserStatistics($id);} else
		if($type=='total'){return generateTotalStatistics($id);}
	}

	function generate12monthsStatistics($id){
	
		$output =	'<headergroup>'.
					'<header title="Måned"/>'.
					'<header title="Hits"/>'.
					'<header title="Unikke hits"/>'.
					'<header title="Unikke ip adresser"/>'.	
					'</headergroup>';
		$sql = 'SELECT count(distinct session) as uniquehits, count(distinct ip) as networks, count(*) as hits, date_format(time, "%Y-%m") as yearmonth, date_format(now(),"%Y-%m") as today FROM statistics group by date_format(time, "%Y-%m") order by time DESC';
		$result = Database::select($sql);			
		while($row = Database::next($result)){
			$year = getYear($row['yearmonth']);
			$month = translateNumToMonth(getMonth($row['yearmonth']));
			$output.=	'<row link="#">'.
						'<cell>'.$month." ".$year.'</cell>'.
						'<cell>'.$row['hits'].'</cell>'.
						'<cell>'.$row['uniquehits'].'</cell>'.
						'<cell>'.$row['networks'].'</cell>'.
						'</row>';
		}
		$year = date('Y');
		$month = date('m');
		$lastyear = ($year-1).'-'.$month;
		$sql = 'SELECT count(distinct session) as uniquehits, count(distinct ip) as networks, count(*) as hits FROM statistics where date_format(time, "%Y-%m") > "'.$lastyear.'" order by time DESC';
		$result = Database::select($sql);
		$row = Database::next($result);
		$output.=	'<row link="#">'.
					'<cell/>'.
					'<cell/>'.
					'<cell/>'.
					'<cell/>'.
					'</row>';
		$output.=	'<row link="#">'.
					'<cell>Total</cell>'.
					'<cell>'.$row['hits'].'</cell>'.
					'<cell>'.$row['uniquehits'].'</cell>'.
					'<cell>'.$row['networks'].'</cell>'.
					'</row>';
						
		Database::free($result);
		return $output;
	}
	
	function generateYearlyStatistics($id){
		$year = getYear($id);
		$output =	'<headergroup>'.
					'<header title="Måned"/>'.
					'<header title="Hits"/>'.
					'<header title="Unikke hits"/>'.
					'<header title="Unikke ip adresser"/>'.	
					'</headergroup>';
		$sql = 'SELECT count(distinct session) as uniquehits, count(distinct ip) as networks, count(*) as hits, date_format(time, "%m") as month FROM statistics where date_format(time, "%Y") = "'.$year.'" group by date_format(time, "%Y-%m") order by time DESC';
		$result = Database::select($sql);			
		while($row = Database::next($result)){
			$month = translateNumToMonth($row['month']);
			$output.=	'<row link="#">'.
						'<cell>'.$month.'</cell>'.
						'<cell>'.$row['hits'].'</cell>'.
						'<cell>'.$row['uniquehits'].'</cell>'.
						'<cell>'.$row['networks'].'</cell>'.
						'</row>';
		}
		$sql = 'SELECT count(distinct session) as uniquehits, count(distinct ip) as networks, count(*) as hits FROM statistics where date_format(time, "%Y") = "'.$year.'" group by date_format(time, "%y") order by time DESC';
		$result = Database::select($sql);
		$row = Database::next($result);
		$output.=	'<row link="#">'.
					'<cell/>'.
					'<cell/>'.
					'<cell/>'.
					'<cell/>'.
					'</row>';
		$output.=	'<row link="#">'.
					'<cell>Total</cell>'.
					'<cell>'.$row['hits'].'</cell>'.
					'<cell>'.$row['uniquehits'].'</cell>'.
					'<cell>'.$row['networks'].'</cell>'.
					'</row>';
		Database::free($result);
		
		return $output;
	}
	
	function generateMonthlyStatistics($id){
		$output =	'<headergroup>'.
					'<header title="Dato"/>'.
					'<header title="Hits"/>'.
					'<header title="Unikke hits"/>'.
					'<header title="Unikke ip adresser"/>'.	
					'</headergroup>';
		$sql = 'SELECT count(distinct session) as uniquehits, count(distinct ip) as networks, count(*) as hits, date_format(time, "%d") as day, date_format(time,"%m") as month FROM statistics where date_format(time, "%Y-%m") = "'.$id.'" group by date_format(time, "%m-%d") order by time DESC';
		$result = Database::select($sql);	
		
		$daysInMonth = cal_days_in_month(CAL_GREGORIAN,stringToInt(getMonth($id)), stringToInt(getYear($id)));
		$month = "";
		while($row = Database::next($result)){
			$month = translateNumToMonth($row['month']);
			while($row['day']!=$daysInMonth){
				$output.=	'<row link="#">'.
							'<cell>'.$daysInMonth.". ".translateNumToMonth($row['month']).'</cell>'.
							'<cell>0</cell>'.
							'<cell>0</cell>'.
							'<cell>0</cell>'.
							'</row>';
				$daysInMonth--;							
			}
			$output.=	'<row link="#">'.
						'<cell>'.$row['day'].". ".translateNumToMonth($row['month']).'</cell>'.
						'<cell>'.$row['hits'].'</cell>'.
						'<cell>'.$row['uniquehits'].'</cell>'.
						'<cell>'.$row['networks'].'</cell>'.
						'</row>';
			$daysInMonth--;
		}
		while($daysInMonth > 0){
			$output.=	'<row link="#">'.
						'<cell>'.$daysInMonth.". ".$month.'</cell>'.
						'<cell>0</cell>'.
						'<cell>0</cell>'.
						'<cell>0</cell>'.
						'</row>';
			$daysInMonth--;			
		}
		$sql = 'SELECT count(distinct session) as uniquehits, count(distinct ip) as networks, count(*) as hits FROM statistics where date_format(time, "%Y-%m") = "'.$id.'" order by time DESC';
		$result = Database::select($sql);
		$row = Database::next($result);
		$output.=	'<row link="#">'.
					'<cell/>'.
					'<cell/>'.
					'<cell/>'.
					'<cell/>'.
					'</row>';
		$output.=	'<row link="#">'.
					'<cell>Total</cell>'.
					'<cell>'.$row['hits'].'</cell>'.
					'<cell>'.$row['uniquehits'].'</cell>'.
					'<cell>'.$row['networks'].'</cell>'.
					'</row>';
		Database::free($result);
		return $output;
	}
	
	function generateHourlyStatistics($id, $fromDate = "", $toDate="",$dateFormat="%Y-%m"){
		$output =	'<headergroup>'.
					'<header title="Time"/>'.
					'<header title="Hits"/>'.
					'<header title="Unikke hits"/>'.
					'<header title="Unikke ip adresser"/>'.	
					'</headergroup>';
		$sqlFromTo = ' where date_format(time,"'.$dateFormat.'") >"'.$fromDate.'" AND date_format(time,"'.$dateFormat.'") <="'.$toDate.'"';
		$sqlDefault = ' where date_format(time, "'.$dateFormat.'") = "'.$id.'"';
		$sql = 'SELECT count(distinct session) as uniquehits, count(distinct ip) as networks, count(*) as hits, date_format(time, "%H") as hour FROM statistics ';
		
		if(strlen($id)> 0){
			if(strlen($fromDate)>0)
				$sql.= $sqlFromTo;
			else
				$sql.= $sqlDefault;
		}
		
		$sql.='group by date_format(time, "%H") order by date_format(time, "%H") DESC';
		
		$result = Database::select($sql);	
		
		$hourcount = 23;
		while($row = Database::next($result)){
			while(stringToInt($row['hour'])!=$hourcount && $hourcount > -1){
				$output.=	'<row link="#">'.
							'<cell>'.$hourcount.'</cell>'.
							'<cell>0</cell>'.
							'<cell>0</cell>'.
							'<cell>0</cell>'.
							'</row>';
				$hourcount--;			
			}
			$output.=	'<row link="#">'.
						'<cell>'.$row['hour'].'</cell>'.
						'<cell>'.$row['hits'].'</cell>'.
						'<cell>'.$row['uniquehits'].'</cell>'.
						'<cell>'.$row['networks'].'</cell>'.
						'</row>';
			$hourcount--;			
		}
		while($hourcount > -1){
			$output.=	'<row link="#">'.
						'<cell>'.$hourcount.'</cell>'.
						'<cell>0</cell>'.
						'<cell>0</cell>'.
						'<cell>0</cell>'.
						'</row>';
			$hourcount--;
		}
		/*
		$sql = 'SELECT count(distinct session) as uniquehits, count(distinct ip) as networks, count(*) as hits FROM statistics where date_format(time, "%Y-%m") = "'.$id.'" order by time DESC';
		$result = Database::select($sql);
		$row = Database::next($result);
		$output.=	'<row link="#">'.
					'<cell/>'.
					'<cell/>'.
					'<cell/>'.
					'<cell/>'.
					'</row>';
		$output.=	'<row link="#">'.
					'<cell>Total</cell>'.
					'<cell>'.$row['hits'].'</cell>'.
					'<cell>'.$row['uniquehits'].'</cell>'.
					'<cell>'.$row['networks'].'</cell>'.
					'</row>';*/
		Database::free($result);
		return $output;
	}
	
	function generateURLStatistics($id, $fromDate = "", $toDate="",$dateFormat="%Y-%m"){
		$month = getMonth($id);
		$sqlFromTo = ' where date_format(time,"'.$dateFormat.'") >"'.$fromDate.'" AND date_format(time,"'.$dateFormat.'") <="'.$toDate.'"';
		$sqlDefault = ' where date_format(time, "'.$dateFormat.'") = "'.$id.'"';
		$sql = 'SELECT count(distinct session) as uniquehits, count(distinct ip) as networks, count(*) as hits FROM statistics';
		if(strlen($id)> 0){
			if(strlen($fromDate)>0)
				$sql.= $sqlFromTo;
			else
				$sql.= $sqlDefault;
		}		
		$result = Database::select($sql);
		$totalHits = Database::next($result);
		$maxHits = $totalHits['uniquehits'];
		
		$output =	'<headergroup>'.
					'<header title="#"/>'.
					'<header title="Unikke hits"/>'.
					'<header title="%"/>'.
					'<header title="Hits"/>'.
					'<header title="Unikke ip adresser"/>'.	
					'<header title="Adresse"/>'.
					'<header title="Side"/>'.		
					'</headergroup>';
		$sql = 'SELECT count(distinct session) as uniquehits, count(distinct ip) as networks, count(*) as hits, uri, value, title FROM statistics LEFT JOIN page ON (statistics.value=page.id)';
		
		if(strlen($id)> 0){
			if(strlen($fromDate)>0)
				$sql.= $sqlFromTo;
			else
				$sql.= $sqlDefault;
		}			
		$sql.= ' group by uri order by uniquehits DESC';
		
		$result = Database::select($sql);	
		$count = 0;
		while($row = Database::next($result)){
			$count++;
			$percentage = round(($row['uniquehits']/$maxHits) * 100,2);
			$output.=	'<row>'.
						'<cell>'.$count.'</cell>'.						
						'<cell>'.$row['uniquehits'].'</cell>'.
						'<cell>'.$percentage.'</cell>'.
						'<cell>'.$row['hits'].'</cell>'.
						'<cell>'.$row['networks'].'</cell>'.
						'<cell>'.escapeHTML($row['uri']).'</cell>'.
						'<cell link="">'.$row['title'].'</cell>'.
						'</row>';
			
		}
		
		Database::free($result);
		return $output;
	
	
	}
	
	function generateCountryStatistics($id, $fromDate = "", $toDate="",$dateFormat="%Y-%m"){
		$sqlFromTo = ' where date_format(time,"'.$dateFormat.'") >"'.$fromDate.'" AND date_format(time,"'.$dateFormat.'") <="'.$toDate.'"';
		$sqlDefault = ' where date_format(time, "'.$dateFormat.'") = "'.$id.'"';
		$sql = 'SELECT count(distinct session) as uniquehits, count(distinct ip) as networks, count(*) as hits FROM statistics';
		
		if(strlen($id)> 0){
			if(strlen($fromDate)>0)
				$sql.= $sqlFromTo;
			else
				$sql.= $sqlDefault;
		}
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
		if(strlen($id)> 0){
			if(strlen($fromDate)>0)
				$sql.= $sqlFromTo;
			else
				$sql.= $sqlDefault;
		}	
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
	
	
	function generateBrowserStatistics($id){}
	
	function generateTotalStatistics($id){
		$year = getYear($id);
		$output =	'<headergroup>'.
					'<header title="Måned"/>'.
					'<header title="Hits"/>'.
					'<header title="Unikke hits"/>'.
					'<header title="Unikke ip adresser"/>'.	
					'</headergroup>';
		$sql = 'SELECT count(distinct session) as uniquehits, count(distinct ip) as networks, count(*) as hits, date_format(time, "%m") as month, date_format(time, "%Y") as year FROM statistics group by date_format(time, "%Y-%m") order by time DESC';
		$result = Database::select($sql);			
		while($row = Database::next($result)){
			$month = translateNumToMonth($row['month']);
			$output.=	'<row link="#">'.
						'<cell>'.$month.' '.$row['year'].'</cell>'.
						'<cell>'.$row['hits'].'</cell>'.
						'<cell>'.$row['uniquehits'].'</cell>'.
						'<cell>'.$row['networks'].'</cell>'.
						'</row>';
			}
			$sql = 'SELECT count(distinct session) as uniquehits, count(distinct ip) as networks, count(*) as hits FROM statistics order by time DESC';
			$result = Database::select($sql);
			$row = Database::next($result);
			$output.=	'<row link="#">'.
						'<cell/>'.
						'<cell/>'.
						'<cell/>'.
						'<cell/>'.
						'</row>';
			$output.=	'<row link="#">'.
						'<cell>Total</cell>'.
						'<cell>'.$row['hits'].'</cell>'.
						'<cell>'.$row['uniquehits'].'</cell>'.
						'<cell>'.$row['networks'].'</cell>'.
						'</row>';
						
			Database::free($result);
			
			return $output;				
	}
	
	

	

	$elements = array("List","Window","Frame","Form","Html");

	writeGui($xwg_skin,$elements,$gui);



?>