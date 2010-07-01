<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.News
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Include/Functions.php';
require_once '../../Classes/Newsgroup.php';
require_once 'NewsController.php';

$close = NewsController::getBaseWindow();
$groupId = NewsController::getGroupId();
$page = requestGetNumber('page');

$pages=buildPages();
$files=buildFiles();

$groups = NewsGroup::search();

$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../../"/>'.
'<interface background="Desktop">'.
'<window xmlns="uri:Window" width="500" align="center" top="20">'.
'<titlebar title="Ny nyhed" icon="Part/News">'.
'<close link="'.$close.'"/>'.
'</titlebar>'.
'<content padding="10" background="true">'.
'<form xmlns="uri:Form" action="CreateNews.php" method="post" name="Formula" focus="title" submit="true">'.
'<validation>
if (Title.isEmpty()) {
	Title.setError("Skal udfyldes!");
	Title.blinkError(1000);
	Title.focus();
	return false;
}
else {
	Title.setError("");
}
if (EndDateCheck.isSelected() &amp;&amp; StartDateCheck.isSelected()) {
	if (parseInt(StartDate.getValue()) &gt;= parseInt(EndDate.getValue())) {
		EndDate.setError("Slutdatoen skal være større end startdatoen!");
		EndDate.blinkError(1000);
		return false;
	}
	else {
		EndDate.setError("");
	}
}
else {
	EndDate.setError("");
}
return true;
</validation>'.
'<group size="Large" badgeplacement="above">'.
'<textfield badge="Titel:" name="title" object="Title"/>'.
'<textfield badge="Resume:" name="description" lines="6"/>'.
'<object badge="Billede:" name="image" empty="true">'.
	'<translation choose="Vælg" remove="Fjern" none="Intet valgt"/>'.
	'<source list="ImagePicker.php"/>'.
'</object>'.
'</group>'.
'<group size="Large">'.
'<space/>'.
'<datetime badge="Startdato:" name="startdate" object="StartDate" value="'.xwgTimeStamp2dateTime(mktime()).'" display="dmy">'.
'<check name="startdateCheck" selected="true" object="StartDateCheck" autoenable="StartDate"/>'.
'</datetime>'.
'<datetime badge="Slutdato:" name="enddate" object="EndDate" value="'.xwgTimeStamp2dateTime(mktime()+(7*60*60*24)).'" display="dmy">'.
'<check name="enddateCheck" selected="true" object="EndDateCheck" autoenable="EndDate"/>'.
'</datetime>'.
'<checkbox badge="Søgbar:" name="searchable" selected="true"/>'.
'<space/>'.
'<disclosure label="Link" expanded="'.($page>0 ? 'true' : 'false').'">'.
'<textfield badge="Titel:" name="linkTitle">'.($page>0 ? 'Læs mere...' : '').'</textfield>'.
'<textfield badge="Beskrivelse:" name="linkAlternative"/>'.
'<combo badge="Link til:" name="linkType">'.
	'<option title="Side:" value="page">'.
		'<select name="page" selected="'.$page.'">'.$pages.'</select>'.
	'</option>'.
	'<option title="Fil:" value="file">'.
		'<select name="file">'.
			$files.
		'</select>'.
	'</option>'.
	'<option title="Adresse:" value="url">'.
		'<textfield badge="Adresse:" name="url"/>'.
	'</option>'.
	'<option title="E-post:" value="email">'.
		'<textfield badge="E-post:" name="email"/>'.
	'</option>'.
'</combo>'.
'</disclosure>'.
'<space/>'.
'<disclosure label="Grupper">'.
'<select badge="Grupper:" lines="5" name="groups[]" multiple="true">';
foreach ($groups as $group) {
	$gui.='<option title="'.encodeXML($group->getTitle()).'" value="'.encodeXML($group->getId()).'" selected="'.($group->getId()==$groupId ? 'true' : 'false').'"/>';
}
$gui.='</select>'.
'<space/>'.
'</disclosure>'.
'<buttongroup size="Large">'.
'<button title="Annuller" link="'.$close.'"/>'.
'<button title="Opret" submit="true" style="Hilited"/>'.
'</buttongroup>'.
'</group>'.
'</form>'.
'</content>'.
'</window>'.
'</interface>'.
'</xmlwebgui>';

$elements = array("Window","Toolbar","Form");
writeGui($xwg_skin,$elements,$gui);

function buildPages() {
	$output="";
	$sql="select id,title from page order by title";
	$result = Database::select($sql);
	while ($row = Database::next($result)) {
		$output.='<option title="'.encodeXML($row['title']).'" value="'.$row['id'].'"/>';
	}
	Database::free($result);
	return $output;
}

function buildFiles() {
	$output="";
	$sql="select id,title from object where type='file' order by title";
	$result = Database::select($sql);
	while ($row = Database::next($result)) {
		$output.='<option title="'.encodeXML($row['title']).'" value="'.$row['id'].'"/>';
	}
	Database::free($result);
	return $output;
}
?>