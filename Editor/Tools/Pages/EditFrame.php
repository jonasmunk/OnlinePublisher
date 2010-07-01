<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Designer
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';
require_once '../../Include/XmlWebGui.php';

$id = requestGetNumber('id',0);

$sql="select * from frame where id=".$id;
$row = Database::selectFirst($sql);
$name=$row['name'];
$title=$row['title'];
$bottomtext=$row['bottomtext'];
$hierarchy=$row['hierarchy_id'];

$sql="select * from page where frame_id=".$id;
$canDelete=Database::isEmpty($sql);

$hiers=buildHierarchies();


$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../../"/>'.
'<interface background="Desktop">'.
'<window xmlns="uri:Window" width="400" align="center" top="30">'.
'<titlebar title="Redigering af ramme" icon="Web/Frame">'.
'<close link="Frames.php"/>'.
'</titlebar>'.
'<tabgroup size="Large" align="center">'.
'<tab title="Egenskaber" style="Hilited"/>'.
'<tab title="Søgning" link="EditFrameSearch.php?id='.$id.'"/>'.
'<tab title="Links" link="EditFrameLinks.php?id='.$id.'"/>'.
'<tab title="Nyheder" link="FrameNews.php?id='.$id.'"/>'.
'<tab title="Brugerstatus" link="EditFrameUserstatus.php?id='.$id.'"/>'.
'</tabgroup>'.
'<content padding="5" background="true">'.
'<form xmlns="uri:Form" action="UpdateFrame.php" method="post" name="Formula">'.
'<hidden name="id">'.$id.'</hidden>'.
'<group size="Large">'.
'<textfield badge="Navn:" name="name">'.encodeXML($name).'</textfield>'.
'<textfield badge="Titel:" name="title">'.encodeXML($title).'</textfield>'.
'<textfield badge="Bundtekst:" name="bottomtext" lines="3">'.encodeXML($bottomtext).'</textfield>'.
'<select badge="Hierarki:" name="hierarchy" selected="'.$hierarchy.'">'.
$hiers.
'</select>'.
'<buttongroup size="Large">'.
'<button title="Udgiv" link="PublishFrame.php?id='.$id.'&amp;return=properties"/>'.
($canDelete ? 
'<button title="Slet" link="DeleteFrame.php?id='.$id.'"/>'
:
'<button title="Slet" style="Disabled"/>'
).
'<button title="Annuller" link="Frames.php"/>'.
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

function buildHierarchies() {
	$output="";
	$sql="select id,name from hierarchy order by name";
	$result = Database::select($sql);
	while ($row = Database::next($result)) {
		$output.='<option title="'.encodeXML($row['name']).'" value="'.$row['id'].'"/>';
	}
	Database::free($result);
	return $output;
}
?>