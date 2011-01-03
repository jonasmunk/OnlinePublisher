<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Security
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Classes/Database.php';
require_once '../../Classes/Securityzone.php';
require_once '../../Classes/Request.php';
require_once '../../Classes/Utilities/StringUtils.php';
require_once '../../Include/XmlWebGui.php';

$id = Request::getInt('id',0);

$zone = SecurityZone::load($id);

$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../../"/>'.
'<interface background="Desktop">'.
'<window xmlns="uri:Window" width="400" align="center" top="30">'.
'<titlebar title="Redigering af beskyttet område" icon="Zone/Security">'.
'<close link="index.php"/>'.
'</titlebar>'.
'<tabgroup size="Large">'.
'<tab title="Egenskaber" link="EditZone.php?id='.$id.'"/>'.
'<tab title="Brugere" style="Hilited"/>'.
'</tabgroup>'.
'<toolbar xmlns="uri:Toolbar" align="center">'.
'<tool icon="Element/User" title="Tilknyt brugere" overlay="Attach" link="NewZoneUser.php?id='.$id.'"/>'.
'</toolbar>'.
'<content>'.
'<list xmlns="uri:List" width="100%" margin="3">'.
'<content>'.
'<headergroup>'.
'<header title="Titel" width="100%"/>'.
'<header title="" width="1%"/>'.
'</headergroup>';


$sql="select * from securityzone_user,object where securityzone_user.user_id=object.id and securityzone_user.securityzone_id=".$id." order by title";
$result = Database::select($sql);
while ($row = Database::next($result)) {
	$gui.='<row>'.
	'<cell>'.
	'<icon size="1" icon="Element/User"/>'.
	'<text>'.StringUtils::escapeXML($row['title']).'</text>'.
	'</cell>'.
	'<cell>'.
	'<icon icon="Basic/Delete" link="DeleteZoneUser.php?zone='.$id.'&amp;user='.$row['id'].'"/>'.
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

$elements = array("Window","List","Toolbar");
writeGui($xwg_skin,$elements,$gui);
?>