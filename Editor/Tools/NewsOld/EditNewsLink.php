<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.News
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Include/Functions.php';

$id = requestGetNumber('id',0);

$sql = "Select * from object_link where id=".$id;
$row = Database::selectFirst($sql);
$news = $row['object_id'];
$title = $row['title'];
$alternative = $row['alternative'];
$targetType = $row['target_type'];
$targetValue = $row['target_value'];
$pages=buildPages();
$files=buildFiles();

$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../../"/>'.
'<interface background="Desktop">'.
'<window xmlns="uri:Window" width="400" align="center" top="10">'.
'<parent title="Redigering af nyhed" link="NewsLinks.php?id='.$news.'"/>'.
'<titlebar title="Redigering af link" icon="Web/Link">'.
'<close link="NewsLinks.php?id='.$news.'"/>'.
'</titlebar>'.
'<content padding="5" background="true">'.
'<form xmlns="uri:Form" action="UpdateNewsLink.php" method="post" name="Formula" focus="title">'.
'<hidden name="id">'.$id.'</hidden>'.
'<hidden name="news">'.$news.'</hidden>'.
'<group size="Large">'.
'<textfield badge="Titel:" name="title">'.encodeXML($title).'</textfield>'.
'<textfield badge="Beskrivelse:" name="alternative">'.encodeXML($alternative).'</textfield>'.
'<combo badge="Link til:" name="type" selected="'.$targetType.'">'.
	'<option title="Side:" value="page">'.
		'<select name="page" selected="'.($targetType=='page' ? $targetValue : '0').'">'.
		$pages.
		'</select>'.
	'</option>'.
	'<option title="Fil:" value="file">'.
		'<select name="file" selected="'.($targetType=='file' ? $targetValue : '0').'">'.
		$files.
		'</select>'.
	'</option>'.
	'<option title="Adresse:" value="url">'.
		'<textfield name="url">'.
		($targetType=='url' ? encodeXML($targetValue) : '').
		'</textfield>'.
	'</option>'.
	'<option title="E-post:" value="email">'.
		'<textfield name="email">'.
		($targetType=='email' ? encodeXML($targetValue) : '').
		'</textfield>'.
	'</option>'.
'</combo>'.
'<buttongroup size="Large">'.
'<button title="Slet" link="DeleteNewsLink.php?id='.$id.'&amp;news='.$news.'"/>'.
'<button title="Annuller" link="NewsLinks.php?id='.$news.'"/>'.
'<button title="Opdater" submit="true" style="Hilited"/>'.
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