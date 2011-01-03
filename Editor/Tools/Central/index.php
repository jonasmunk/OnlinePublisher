<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Users
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Classes/RemotePublisher.php';
require_once '../../Classes/Utilities/StringUtils.php';

$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../../"/>'.
'<interface background="Desktop">'.
'<window xmlns="uri:Window" width="500" align="center" top="30">'.
'<titlebar title="Andre systemer" icon="Web/Service"/>'.
'<toolbar xmlns="uri:Toolbar" align="center">'.
'<tool title="Nyt system" icon="Basic/Internet" overlay="New" link="NewSite.php"/>'.
'</toolbar>'.
'<content>'.
'<list xmlns="uri:List" width="100%" margin="3">'.
'<content>'.
'<headergroup>'.
'<header title="Title"/>'.
'<header title="Adresse"/>'.
'<header title="Version"/>'.
'</headergroup>';


$sql="select * from remotepublisher,object where remotepublisher.object_id=object.id order by title";
$result = Database::select($sql);
while ($row = Database::next($result)) {
	$site = RemotePublisher::load($row['id']);
	$gui.='<row link="EditSite.php?id='.$row['id'].'">'.
	'<cell>'.
	'<icon size="1" icon="Basic/Internet"/>'.
	'<text>'.StringUtils::escapeXML($row['title']).'</text>'.
	'</cell>'.
	'<cell>'.StringUtils::escapeXML($row['url']).'</cell>'.
	'<cell>'.StringUtils::escapeXML($site->getVersionNumber()).'</cell>'.
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