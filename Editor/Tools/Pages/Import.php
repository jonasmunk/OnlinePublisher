<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Designer
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Classes/Utilities/StringUtils.php';

$designs=buildDesigns();
$frames=buildFrames();

$close = 'PagesFrame.php';

$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../../"/>'.
'<interface background="Desktop">'.
'<window xmlns="uri:Window" width="500" align="center">'.
'<titlebar title="Avanceret" icon="Tool/System">'.
'<close link="'.$close.'"/>'.
'</titlebar>'.
'<tabgroup size="Large">'.
'<tab title="Importering" style="Hilited"/>'.
'</tabgroup>'.
'<content padding="5" background="true">'.
'<form xmlns="uri:Form" action="PerformImport.php" method="post" name="Formula" focus="title" enctype="multipart/form-data">'.
'<group size="Large">'.
'<textfield badge="Titel:" name="title"/>'.
'<file badge="Fil:" name="file"/>'.
'<select badge="Design:" name="design">'.
$designs.
'</select>'.
'<select badge="Opsætning:" name="frame">'.
$frames.
'</select>'.
'<buttongroup size="Large">'.
'<button title="Annuller" link="'.$close.'"/>'.
'<button title="Upload" submit="true" style="Hilited"/>'.
'</buttongroup>'.
'</group>'.
'</form>'.
'</content>'.
'</window>'.
'</interface>'.
'</xmlwebgui>';

$elements = array("Window","Toolbar","Form");
writeGui($xwg_skin,$elements,$gui);


function buildDesigns() {
	$output="";
	$sql="select id,name from design order by name";
	$result = Database::select($sql);
	while ($row = Database::next($result)) {
		$output.='<option title="'.StringUtils::escapeXML($row['name']).'" value="'.$row['id'].'"/>';
	}
	Database::free($result);
	return $output;
}

function buildFrames() {
	$output="";
	$sql="select id,name from frame order by name";
	$result = Database::select($sql);
	while ($row = Database::next($result)) {
		$output.='<option title="'.StringUtils::escapeXML($row['name']).'" value="'.$row['id'].'"/>';
	}
	Database::free($result);
	return $output;
}
?>