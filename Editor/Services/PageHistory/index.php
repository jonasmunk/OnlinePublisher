<?php
/**
 * @package OnlinePublisher
 * @subpackage Services.PageHistory
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Include/Functions.php';
require_once '../../Classes/UserInterface.php';
require_once '../../Classes/InternalSession.php';
require_once '../../Classes/Utilities/StringUtils.php';

$close = "../../Template/Edit.php";
$pageId = InternalSession::getPageId();

$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../../"/>'.
'<interface background="Desktop">'.
'<window xmlns="uri:Window" width="700" align="center" top="20">'.
'<titlebar title="Sidens historik" icon="Basic/Time">'.
'<close link="'.$close.'" target="Desktop" help="Luk vinduet"/>'.
'</titlebar>'.
'<toolbar xmlns="uri:Toolbar">'.
'<tool title="Luk" icon="Basic/Close" link="'.$close.'" target="Desktop"/>'.
'<divider/>'.
'<tool title="Gem version" icon="Basic/Save" link="SaveVersion.php?id='.$pageId.'"/>'.
'</toolbar>'.
'<statusbar text="Her vises en oversigt over tidligere versioner af siden..."/>'.
'<content>'.
'<list xmlns="uri:List" width="100%" margin="3" sort="true">'.
'<content>'.
'<headergroup>'.
'<header title="Tidspunkt" nowrap="true"/>'.
'<header title="Bruger" width="30%"/>'.
'<header title="Besked" width="70%"/>'.
'<header/>'.
'</headergroup>';

$sql="select page_history.id,UNIX_TIMESTAMP(page_history.time) as time,page_history.message,object.title".
" from page_history left join object on object.id=page_history.user_id where page_id=".$pageId." order by page_history.time desc";
$result = Database::select($sql);
while ($row = Database::next($result)) {
	$gui.='<row link="Viewer.php?id='.$row['id'].'">'.
	'<cell><icon icon="Basic/Time"/><text>'.UserInterface::presentDateTime($row['time']).'</text></cell>'.
	'<cell>'.
	(strlen($row['title'])>0 ? '<icon icon="Element/User"/>' : '').
	'<text>'.StringUtils::escapeXML($row['title']).'</text></cell>'.
	'<cell><text>'.StringUtils::escapeXML($row['message']).'</text><icon icon="Basic/Edit" link="EditHistory.php?id='.$row['id'].'"/></cell>'.
	'<cell>'.
	'<button title="Gendan" link="Reconstruct.php?id='.$row['id'].'" target="Desktop"/>'.
	'<button title="Vis" link="Viewer.php?id='.$row['id'].'"/>'.
	'</cell>'.
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