<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Designer
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Include/Functions.php';

$frameId = requestGetNumber('id',0);

$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../../"/>'.
'<interface background="Desktop">'.
'<window xmlns="uri:Window" width="400" top="30" align="center">'.
'<parent title="Redigering af ramme" link="FrameNews.php?id='.$frameId.'"/>'.
'<titlebar title="Ny nyhedsblok" icon="Part/News">'.
'<close link="FrameNews.php?id='.$frameId.'"/>'.
'</titlebar>'.
'<content padding="5" background="true">'.
'<form xmlns="uri:Form" action="CreateFrameNewsblock.php" method="post" name="Formula" focus="title">'.
'<validation>
if (TimeType.getValue()=="interval" &amp;&amp; StartDate.getValue()&gt;EndDate.getValue()) {
	EndDate.setError("Slutdatoen skal være højere eller lig med startdatoen!");
	EndDate.blinkError(1000);
	return false;
}
return true;
</validation>'.
'<hidden name="frame">'.$frameId.'</hidden>'.
'<group size="Large">'.
'<textfield badge="Titel:" name="title"/>'.
'<indent><box title="Visning">'.
'<select badge="Sorter efter:" name="sortby">'.
'<option title="Startdato" value="startdate"/>'.
'<option title="Slutdato" value="enddate"/>'.
'<option title="Titel" value="title"/>'.
'</select>'.
'<select badge="Retning:" name="sortdir">'.
'<option title="Stigende" value="ascending"/>'.
'<option title="Faldende" value="descending"/>'.
'</select>'.
'<select badge="Maksimalt antal:" name="maxitems">'.
'<option title="Uendeligt" value="0"/>';
for ($i=1;$i<=50;$i++) {
	$gui.='<option title="'.$i.'" value="'.$i.'"/>';
}
$gui.=
'</select>'.
'</box></indent>'.
'<indent><box title="Tid">'.
'<select badge="Tid:" name="timetype" object="TimeType" onchange="updateUI()">'.
'<option title="Altid" value="always"/>'.
'<option title="Lige nu" value="now"/>'.
'<option title="Interval" value="interval"/>'.
'<option title="Seneste timer..." value="hours"/>'.
'<option title="Seneste dage..." value="days"/>'.
'<option title="Seneste uger..." value="weeks"/>'.
'<option title="Seneste måneder..." value="months"/>'.
'<option title="Seneste år..." value="years"/>'.
'</select>'.
'<select badge="Antal:" name="timecount" object="TimeCount" disabled="true">';
for ($i=1;$i<=50;$i++) {
	$gui.='<option title="'.$i.'" value="'.$i.'"/>';
}
$gui.=
'</select>'.
'<datetime badge="Startdato" name="startdate" object="StartDate" disabled="true" value="'.xwgTimeStamp2dateTime(mktime()).'"/>'.
'<datetime badge="Slutdato" name="enddate" object="EndDate" disabled="true" value="'.xwgTimeStamp2dateTime(mktime()).'"/>'.
'</box></indent>'.'<buttongroup size="Large">'.
'<button title="Annuller" link="FrameNews.php?id='.$frameId.'"/>'.
'<button title="Opret" submit="true" style="Hilited"/>'.
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
</script>'.
'</interface>'.
'</xmlwebgui>';

$elements = array("Window","Script","Form");
writeGui($xwg_skin,$elements,$gui);

?>