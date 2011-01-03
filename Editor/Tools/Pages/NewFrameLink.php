<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Designer
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Include/Functions.php';
require_once '../../Classes/Request.php';
require_once '../../Classes/Utilities/StringUtils.php';

$frameId = Request::getInt('id',0);
$position = Request::getString('position');

$pages=buildPages();
$files=buildFiles();

$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../../"/>'.
'<interface background="Desktop">'.
'<window xmlns="uri:Window" width="400" top="30" align="center">'.
'<parent title="Redigering af ramme" link="EditFrameLinks.php?id='.$frameId.'&amp;position='.$position.'"/>'.
'<titlebar title="Nyt link" icon="Web/Link">'.
'<close link="EditHierarchy.php"/>'.
'</titlebar>'.
'<content padding="5" background="true">'.
'<form xmlns="uri:Form" action="CreateFrameLink.php" method="post" name="Formula" focus="title">'.
'<hidden name="frame">'.$frameId.'</hidden>'.
'<hidden name="position">'.$position.'</hidden>'.
'<group size="Large">'.
'<textfield badge="Titel:" name="title"/>'.
'<textfield badge="Alternativ:" name="alternative"/>'.
'<indent>'.
'<box title="Link">'.
'<select badge="Side:" name="page">'.
'<radio name="type" value="page" selected="true"/>'.$pages.
'</select>'.
'<select badge="Fil:" name="file">'.
'<radio name="type" value="file"/>'.$files.
'</select>'.
'<textfield badge="Adresse:" name="url">'.
'<radio name="type" value="url"/>'.
'</textfield>'.
'<textfield badge="E-post:" name="email">'.
'<radio name="type" value="email"/>'.
'</textfield>'.
'</box>'.
'</indent>'.
'<buttongroup size="Large">'.
'<button title="Annuller" link="EditFrameLinks.php?id='.$frameId.'&amp;position='.$position.'"/>'.
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
		$output.='<option title="'.StringUtils::escapeXML($row['title']).'" value="'.$row['id'].'"/>';
	}
	Database::free($result);
	return $output;
}

function buildFiles() {
	$output="";
	$sql="select id,title from object where type='file' order by title";
	$result = Database::select($sql);
	while ($row = Database::next($result)) {
		$output.='<option title="'.StringUtils::escapeXML($row['title']).'" value="'.$row['id'].'"/>';
	}
	Database::free($result);
	return $output;
}
?>