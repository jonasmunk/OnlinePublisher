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

$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../../"/>'.
'<interface background="Desktop">'.
'<window xmlns="uri:Window" width="400" align="center" top="30">'.
'<titlebar title="Redigering af ramme" icon="Web/Frame">'.
'<close link="Frames.php"/>'.
'</titlebar>'.
'<tabgroup size="Large" align="center">'.
'<tab title="Egenskaber" link="EditFrame.php?id='.$id.'"/>'.
'<tab title="Søgning" link="EditFrameSearch.php?id='.$id.'"/>'.
'<tab title="Links" link="EditFrameLinks.php?id='.$id.'"/>'.
'<tab title="Nyheder" style="Hilited"/>'.
'<tab title="Brugerstatus" link="EditFrameUserstatus.php?id='.$id.'"/>'.
'</tabgroup>'.
'<toolbar xmlns="uri:Toolbar" align="center">'.
'<tool title="Ny nyhedsblok" icon="Part/News" overlay="New" link="NewFrameNewsblock.php?id='.$id.'"/>'.
'</toolbar>'.
'<content padding="5" background="true">'.
'<list xmlns="uri:List" width="100%">'.
'<content>'.
'<headergroup>'.
'<header title="Titel" width="99%"/>'.
'<header title="" width="1%"/>'.
'</headergroup>';

$sql="select * from frame_newsblock where frame_id=".$id." order by `index`";
$result = Database::select($sql);
while ($row = Database::next($result)) {
	$gui.='<row link="FrameNewsblockProperties.php?id='.$row['id'].'">'.
	'<cell>'.
	'<icon icon="Part/News"/>'.
	'<text>'.StringUtils::escapeXML($row['title']).'</text>'.
	'</cell>'.
	'<cell>'.
	'<direction direction="Up" link="MoveFrameNewsblock.php?id='.$row['id'].'&amp;dir=-1"/>'.
	'<direction direction="Down" link="MoveFrameNewsblock.php?id='.$row['id'].'&amp;dir=1"/>'.
	'</cell>'.
	'</row>';
}
Database::free($result);
$gui.=
'</content>'.
'</list>'.
'<group xmlns="uri:Button" size="Large" align="right" top="5">'.
'<button title="Udgiv" link="PublishFrame.php?id='.$id.'&amp;return=news"/>'.
'</group>'.
'</content>'.
'</window>'.
'</interface>'.
'</xmlwebgui>';

$elements = array("Window","Toolbar","List","Button");
writeGui($xwg_skin,$elements,$gui);


function getPageTitle($id) {
	$output=NULL;
	$sql = "select title from page where id=".$id;
	if ($row = Database::selectFirst($sql)) {
		$output = $row['title'];
	}
	return $output;
}
?>