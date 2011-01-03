<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Designer
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Classes/Request.php';
require_once '../../Classes/Utilities/StringUtils.php';

$id = Request::getInt('id',0);


$sql = "Select * from frame_newsblock where id=".$id;
$row = Database::selectFirst($sql);
$frame = $row['frame_id'];

$newsOptions='';
$sql="SELECT object.* FROM object LEFT JOIN frame_newsblock_newsgroup ON frame_newsblock_newsgroup.newsgroup_id=object.id and frame_newsblock_newsgroup.frame_newsblock_id=$id where object.type='newsgroup' and frame_newsblock_newsgroup.id IS NULL;";
$result = Database::select($sql);
while ($row = Database::next($result)) {
	$newsOptions.='<option title="'.StringUtils::escapeXML($row['title']).'" value="'.StringUtils::escapeXML($row['id']).'"/>';
}
Database::free($result);


$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../../"/>'.
'<interface background="Desktop">'.
'<window xmlns="uri:Window" width="400" top="20" align="center">'.
'<parent title="Redigering af ramme" link="FrameNews.php?id='.$frame.'"/>'.
'<parent title="Redigering af nyhedsblok" link="FrameNewsblockNewsgroups.php?id='.$id.'"/>'.
'<titlebar title="Tilf&#248;j nyhedsgrupper til nyhedsblok" icon="Basic/Add">'.
'<close link="FrameNewsblockNewsgroups.php?id='.$id.'"/>'.
'</titlebar>'.
'<content padding="5" background="true" valign="top">'.
'<form xmlns="uri:Form" action="InsertNewsgroupIntoNewsblock.php" method="post" name="Formula">'.
'<hidden name="id">'.$id.'</hidden>'.
'<group size="Large" badgewidth="20%" badgeplacement="above">'.
'<select badge="Nyheder:" name="newsgroups[]" lines="12" multiple="true">'.
$newsOptions.
'</select>'.
'<buttongroup size="Large">'.
'<button title="Annuller" link="FrameNewsblockNewsgroups.php?id='.$id.'"/>'.
'<button title="Tilf&#248;j" submit="true" style="Hilited"/>'.
'</buttongroup>'.
'</group>'.
'</form>'.
'</content>'.
'</window>'.
'</interface>'.
'</xmlwebgui>';

$elements = array("Window","Form");
writeGui($xwg_skin,$elements,$gui);
?>