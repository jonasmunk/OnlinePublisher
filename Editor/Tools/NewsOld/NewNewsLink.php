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

$pages=buildPages();
$files=buildFiles();

$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../../"/>'.
'<interface background="Desktop">'.
'<window xmlns="uri:Window" width="400" align="center" top="10">'.
'<parent title="Redigering af nyhed" link="NewsLinks.php?id='.$id.'"/>'.
'<titlebar title="Nyt link" icon="Web/Link">'.
'<close link="NewsLinks.php?id='.$id.'"/>'.
'</titlebar>'.
'<content padding="5" background="true">'.
'<form xmlns="uri:Form" action="CreateNewsLink.php" method="post" name="Formula" focus="title" submit="true">'.
'<hidden name="news">'.$id.'</hidden>'.
'<group size="Large">'.
'<textfield badge="Titel:" name="title"/>'.
'<textfield badge="Beskrivelse:" name="alternative"/>'.
'<combo badge="Link til:" name="type">'.
	'<option title="Side:" value="page">'.
		'<select badge="Side:" name="page">'.
		$pages.
		'</select>'.
	'</option>'.
	'<option title="Fil:" value="file">'.
		'<select name="file">'.
		$files.
		'</select>'.
	'</option>'.
	'<option title="Adresse:" value="url">'.
		'<textfield name="url"/>'.
	'</option>'.
	'<option title="E-post:" value="email">'.
		'<textfield name="email"/>'.
	'</option>'.
'</combo>'.
'<buttongroup size="Large">'.
'<button title="Annuller" link="NewsLinks.php?id='.$id.'"/>'.
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