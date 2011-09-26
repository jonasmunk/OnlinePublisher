<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Designer
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Classes/Core/Database.php';
require_once '../../Classes/Utilities/StringUtils.php';

$types = array(
    "home" => "Forside",
    "internalerror" => "Intern fejl"
    );

$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../../"/>'.
'<interface background="Desktop">'.
'<window xmlns="uri:Window" width="500" align="center">'.
'<titlebar title="Opsætning" icon="Tool/Setting">'.
'<close link="PagesFrame.php"/>'.
'</titlebar>'.
'<tabgroup size="Large">'.
'<tab title="Rammer" link="Frames.php"/>'.
'<tab title="Specielle sider" style="Hilited"/>'.
'<tab title="Hierarkier" link="Hierarchies.php"/>'.
'<tab title="Skabeloner" link="Blueprints.php"/>'.
'</tabgroup>'.
'<toolbar xmlns="uri:Toolbar" align="center">'.
'<tool title="Ny speciel side" icon="Web/Page" overlay="New" link="NewSpecialPage.php"/>'.
'</toolbar>'.
'<content>'.
'<list xmlns="uri:List" width="100%" margin="3" sort="true">'.
'<content>'.
'<headergroup>'.
'<header title="Type" width="40%"/>'.
'<header title="Side" width="60%"/>'.
'<header title="Sprog" align="center" width="1%"/>'.
'</headergroup>';

$sql="select specialpage.*,page.title from page,specialpage where specialpage.page_id=page.id";
$result = Database::select($sql);
while ($row = Database::next($result)) {
	$gui.='<row link="EditSpecialPage.php?id='.$row['id'].'">'.
	'<cell>'.
	'<icon size="1" icon="Web/Page"/>'.
	'<text>'.StringUtils::escapeXML($types[$row['type']]).'</text>'.
	'</cell>'.
	'<cell>'.StringUtils::escapeXML($row['title']).'</cell>'.
	'<cell>'.xwgBuildListLanguageIcon($row['language']).'</cell>'.
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