<?
/**
 * @package OnlinePublisher
 * @subpackage Templates.Sitemap
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Classes/Core/Database.php';
require_once '../../Classes/Core/InternalSession.php';
require_once '../../Classes/Utilities/StringUtils.php';

$id = InternalSession::getPageId();

$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../../"/>'.
'<interface background="Desktop">'.
'<window xmlns="uri:Window" width="500" align="center" top="20" height="250">'.
'<titlebar title="Oversigt" icon="Template/Generic">'.
'<close link="../../Tools/Pages/index.php" target="_parent"/>'.
'</titlebar>'.
'<tabgroup align="center" size="Large">'.
'<tab title="Egenskaber" link="Editor.php"/>'.
'<tab title="Hierarckier" style="Hilited"/>'.
'</tabgroup >'.
'<toolbar xmlns="uri:Toolbar" align="center">'.
'<tool title="Tilknyt hierarki" icon="Element/Structure" overlay="Attach" link="NewGroup.php"/>'.
'</toolbar>'.
'<content background="Window" valign="top">'.
'<list xmlns="uri:List" width="100%" margin="3">'.
'<content>'.
'<headergroup>'.
'<header title="Titel"/>'.
'<header title="Hierarki"/>'.
'<header width="1%"/>'.
'</headergroup>';


$sql="select sitemap_group.*,hierarchy.id as hier_id,hierarchy.name as hier_name from sitemap_group left join hierarchy on sitemap_group.hierarchy_id=hierarchy.id where page_id=".$id." order by sitemap_group.position";
$result = Database::select($sql);
while ($row = Database::next($result)) {
	$gui.=
	'<row>'.
	'<cell>'.StringUtils::escapeXML($row['title']).'</cell>'.
	'<cell>'.
	($row['hier_id']>0 ?
	'<icon icon="Element/Structure"/>'.
	'<text>'.StringUtils::escapeXML($row['hier_name']).'</text>'
	: '').
	'</cell>'.
	'<cell>'.
	'<direction direction="Up" link="MoveGroup.php?id='.$row['id'].'&amp;dir=-1"/>'.
	'<direction direction="Down" link="MoveGroup.php?id='.$row['id'].'&amp;dir=1"/>'.
	'<icon icon="Basic/Delete" link="DeleteGroup.php?id='.$row['id'].'"/>'.
	'</cell>'.
	'</row>';
}
Database::free($result);


$gui.=
'</content>'.
'</list>'.
'</content>'.
'</window>'.
'<script xmlns="uri:Script">parent.Toolbar.location="Toolbar.php?"+Math.random();</script>'.
'</interface>'.
'</xmlwebgui>';

$elements = array("Window","Toolbar","List","Script");
writeGui($xwg_skin,$elements,$gui);
?>