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

$sql = "Select * from frame_newsblock where id=".$id;
$row = Database::selectFirst($sql);
$frame = $row['frame_id'];
$title = $row['title'];

$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../../"/>'.
'<interface background="Desktop">'.
'<window xmlns="uri:Window" width="400" top="30" align="center">'.
'<parent title="Redigering af ramme" link="FrameNews.php?id='.$frame.'"/>'.
'<titlebar title="Redigering af nyhedsblok" icon="Part/News">'.
'<close link="FrameNews.php?id='.$frame.'"/>'.
'</titlebar>'.
'<tabgroup align="center">'.
'<tab title="Egenskaber" link="FrameNewsblockProperties.php?id='.$id.'"/>'.
'<tab title="Nyhedsgrupper" style="Hilited"/>'.
'</tabgroup>'.
'<toolbar xmlns="uri:Toolbar" align="center">'.
'<tool title="Tilknyt gruppe" icon="Element/Folder" overlay="Attach" link="AddNewsgroupToNewsblock.php?id='.$id.'"/>'.
'</toolbar>'.
'<content padding="5" background="true">'.
'<list xmlns="uri:List" width="100%" margin="3" sort="true">'.
'<content>'.
'<headergroup>'.
'<header title="Titel" width="99%"/>'.
'<header align="center"/>'.
'</headergroup>';

$sql="select frame_newsblock_newsgroup.id,object.title from object,frame_newsblock_newsgroup where object.type='newsgroup' and frame_newsblock_newsgroup.newsgroup_id=object.id and frame_newsblock_newsgroup.frame_newsblock_id=".$id;
$result = Database::select($sql);
while ($row = Database::next($result)) {
	$gui.='<row>'.
	'<cell>'.
	'<icon size="1" icon="Element/Folder"/>'.
	'<text>'.StringUtils::escapeXML($row['title']).'</text>'.
	'</cell>'.
	'<cell><icon icon="Basic/Delete" link="RemoveNewsgroupFromNewsblock.php?id='.$row['id'].'&amp;newsblock='.$id.'"/></cell>'.
	'</row>';
}
Database::free($result);

$gui.=
'</content>'.
'</list>'.
'</content>'.
'</window>'.
'</interface>'.
'</xmlwebgui>';

$elements = array("Window","Toolbar","List");
writeGui($xwg_skin,$elements,$gui);
?>