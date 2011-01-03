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

$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../../"/>'.
'<interface background="Desktop">'.
'<window xmlns="uri:Window" width="500" align="center">'.
'<titlebar title="Opsætning" icon="Tool/Setting">'.
'<close link="PagesFrame.php"/>'.
'</titlebar>'.
'<tabgroup size="Large">'.
'<tab title="Rammer" style="Hilited"/>'.
'<tab title="Specielle sider" link="SpecialPages.php"/>'.
'<tab title="Hierarkier" link="Hierarchies.php"/>'.
'<tab title="Skabeloner" link="Blueprints.php"/>'.
'</tabgroup>'.
'<toolbar xmlns="uri:Toolbar" align="center">'.
'<tool title="Ny ramme" icon="Web/Frame" overlay="New" link="NewFrame.php"/>'.
'</toolbar>'.
'<content>'.
'<list xmlns="uri:List" width="100%" margin="3" sort="true">'.
'<content>'.
'<headergroup>'.
'<header title="Navn" width="50%"/>'.
'<header title="Hierarki" width="50%"/>'.
'</headergroup>';

$sql="select frame.*,hierarchy.name as hierarchy from frame,hierarchy".
" where frame.hierarchy_id=hierarchy.id order by frame.name";
$result = Database::select($sql);
while ($row = Database::next($result)) {
	$gui.='<row link="EditFrame.php?id='.$row['id'].'">'.
	'<cell>'.
	'<icon size="1" icon="Web/Frame"/>'.
	'<text>'.StringUtils::escapeXML($row['name']).'</text>'.
	'</cell>'.
	'<cell>'.StringUtils::escapeXML($row['hierarchy']).'</cell>'.
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