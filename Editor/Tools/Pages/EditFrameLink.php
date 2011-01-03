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

$id = Request::getInt('id',0);

$sql = "Select * from frame_link where id=".$id;
$row = Database::selectFirst($sql);
$frame = $row['frame_id'];
$position = $row['position'];
$title = $row['title'];
$alternative = $row['alternative'];
$targetType = $row['target_type'];
$targetId = $row['target_id'];
$targetValue = $row['target_value'];
$pages=buildPages();
$files=buildFiles();

$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../../"/>'.
'<interface background="Desktop">'.
'<window xmlns="uri:Window" width="400" top="30" align="center">'.
'<parent title="Redigering af ramme" link="EditFrameLinks.php?id='.$frame.'&amp;position='.$position.'"/>'.
'<titlebar title="Redigering af link" icon="Web/Link">'.
'<close link="EditHierarchy.php"/>'.
'</titlebar>'.
'<content padding="5" background="true">'.
'<form xmlns="uri:Form" action="UpdateFrameLink.php" method="post" name="Formula" focus="title">'.
'<hidden name="id">'.$id.'</hidden>'.
'<hidden name="frame">'.$frame.'</hidden>'.
'<hidden name="position">'.$position.'</hidden>'.
'<group size="Large">'.
'<textfield badge="Titel:" name="title">'.StringUtils::escapeXML($title).'</textfield>'.
'<textfield badge="Alternativ:" name="alternative">'.StringUtils::escapeXML($alternative).'</textfield>'.
'<indent>'.
'<box title="Link">'.
'<select badge="Side:" name="page" selected="'.($targetType=='page' ? $targetId : '0').'">'.
'<radio name="type" value="page" selected="'.($targetType=='page' ? 'true' : 'false').'"/>'.$pages.
'</select>'.
'<select badge="Fil:" name="file" selected="'.($targetType=='file' ? $targetId : '0').'">'.
'<radio name="type" value="file" selected="'.($targetType=='file' ? 'true' : 'false').'"/>'.$files.
'</select>'.
'<textfield badge="Adresse:" name="url">'.
'<radio name="type" value="url" selected="'.($targetType=='url' ? 'true' : 'false').'"/>'.
($targetType=='url' ? StringUtils::escapeXML($targetValue) : '').
'</textfield>'.
'<textfield badge="E-post:" name="email">'.
'<radio name="type" value="email" selected="'.($targetType=='email' ? 'true' : 'false').'"/>'.
($targetType=='email' ? StringUtils::escapeXML($targetValue) : '').
'</textfield>'.
'</box>'.
'</indent>'.
'<buttongroup size="Large">'.
'<button title="Slet" link="DeleteFrameLink.php?id='.$id.'"/>'.
'<button title="Annuller" link="EditFrameLinks.php?id='.$frame.'&amp;position='.$position.'"/>'.
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