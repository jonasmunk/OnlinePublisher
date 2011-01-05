<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Designer
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Classes/Database.php';
require_once '../../Classes/Request.php';
require_once '../../Classes/Utilities/StringUtils.php';

$id = Request::getInt('id',0);

$sql = "Select frame_id,title,sortby,sortdir,maxitems,timetype,timecount,date_format(startdate,'%Y%m%d%H%i%s') startdate,date_format(enddate,'%Y%m%d%H%i%s') enddate from frame_newsblock where id=".$id;
$row = Database::selectFirst($sql);
$frame = $row['frame_id'];
$title = $row['title'];
$sortby = $row['sortby'];
$sortdir = $row['sortdir'];
$maxitems = $row['maxitems'];
$timetype = $row['timetype'];
$timecount = $row['timecount'];
$startdate = $row['startdate'];
$enddate = $row['enddate'];
if (!is_numeric($startdate)) {
	$startdate = xwgTimeStamp2dateTime(mktime());
}
if (!is_numeric($enddate)) {
	$enddate = xwgTimeStamp2dateTime(mktime());
}

$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../../"/>'.
'<interface background="Desktop">'.
'<window xmlns="uri:Window" width="400" top="30" align="center">'.
'<parent title="Redigering af ramme" link="FrameNews.php?id='.$frame.'"/>'.
'<titlebar title="Redigering af nyhedsblok" icon="Part/News">'.
'<close link="FrameNews.php?id='.$frame.'"/>'.
'</titlebar>'.
'<tabgroup align="center">'.
'<tab title="Egenskaber" style="Hilited"/>'.
'<tab title="Nyhedsgrupper" link="FrameNewsblockNewsgroups.php?id='.$id.'"/>'.
'</tabgroup>'.
'<content padding="5" background="true">'.
'<form xmlns="uri:Form" action="UpdateFrameNewsblock.php" method="post" name="Formula" focus="title">'.
'<validation>
if (TimeType.getValue()=="interval" &amp;&amp; StartDate.getValue()&gt;EndDate.getValue()) {
	EndDate.setError("Slutdatoen skal være højere eller lig med startdatoen!");
	EndDate.blinkError(1000);
	return false;
}
return true;
</validation>'.
'<hidden name="id">'.$id.'</hidden>'.
'<hidden name="frame">'.$frame.'</hidden>'.
'<group size="Large">'.
'<textfield badge="Titel:" name="title">'.StringUtils::escapeXML($title).'</textfield>'.
'<indent><box title="Visning">'.
'<select badge="Sorter efter:" name="sortby" selected="'.$sortby.'">'.
'<option title="Startdato" value="startdate"/>'.
'<option title="Slutdato" value="enddate"/>'.
'<option title="Titel" value="title"/>'.
'</select>'.
'<select badge="Retning:" name="sortdir" selected="'.$sortdir.'">'.
'<option title="Stigende" value="ascending"/>'.
'<option title="Faldende" value="descending"/>'.
'</select>'.
'<select badge="Maksimalt antal:" name="maxitems" selected="'.$maxitems.'">'.
'<option title="Uendeligt" value="0"/>';
for ($i=1;$i<=50;$i++) {
	$gui.='<option title="'.$i.'" value="'.$i.'"/>';
}
$gui.=
'</select>'.
'</box></indent>'.
'<indent><box title="Tid">'.
'<select badge="Tid:" name="timetype" object="TimeType" onchange="updateUI()" selected="'.$timetype.'">'.
'<option title="Altid" value="always"/>'.
'<option title="Lige nu" value="now"/>'.
'<option title="Interval" value="interval"/>'.
'<option title="Seneste timer..." value="hours"/>'.
'<option title="Seneste dage..." value="days"/>'.
'<option title="Seneste uger..." value="weeks"/>'.
'<option title="Seneste måneder..." value="months"/>'.
'<option title="Seneste år..." value="years"/>'.
'</select>'.
'<select badge="Antal:" name="timecount" selected="'.$timecount.'" object="TimeCount">';
for ($i=1;$i<=50;$i++) {
	$gui.='<option title="'.$i.'" value="'.$i.'"/>';
}
$gui.=
'</select>'.
'<datetime badge="Startdato" name="startdate" object="StartDate" value="'.$startdate.'"/>'.
'<datetime badge="Slutdato" name="enddate" object="EndDate" value="'.$enddate.'"/>'.
'</box></indent>'.
'<buttongroup size="Large">'.
'<button title="Slet" link="DeleteFrameNewsblock.php?id='.$id.'"/>'.
'<button title="Annuller" link="FrameNews.php?id='.$frame.'"/>'.
'<button title="Opdater" submit="true" style="Hilited"/>'.
'</buttongroup>'.
'</group>'.
'</form>'.
'</content>'.
'</window>'.
'<script xmlns="uri:Script">
function updateUI() {
	var timeType=TimeType.getValue();
	if (timeType=="interval") {
		StartDate.enable();
		EndDate.enable();
	}
	else {
		StartDate.disable();
		EndDate.disable();
	}
	if (timeType=="hours" || timeType=="days" || timeType=="weeks" || timeType=="months" || timeType=="years") {
		TimeCount.enable();
	}
	else {
		TimeCount.disable();
	}
}
updateUI();
</script>'.
'</interface>'.
'</xmlwebgui>';

$elements = array("Window","Toolbar","Form","Script");
writeGui($xwg_skin,$elements,$gui);
?>