<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Users
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';
require_once '../../Include/XmlWebGui.php';

$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../../"/>'.
'<interface background="Desktop">'.
'<window xmlns="uri:Window" width="500" align="center" top="30">'.
'<titlebar title="Brugere" icon="Tool/User"/>'.
'<tabgroup size="Large" align="center">'.
'<tab title="Brugere" style="Hilited"/>'.
'<tab title="Rettigheder" link="Rights.php"/>'.
'</tabgroup>'.
'<toolbar xmlns="uri:Toolbar" align="center">'.
'<tool title="Ny bruger" icon="Element/User" overlay="New" link="NewUser.php"/>'.
'</toolbar>'.
'<content>'.
'<list xmlns="uri:List" width="100%" margin="3">'.
'<content>'.
'<headergroup>'.
'<header title="Title"/>'.
'<header title="Brugernavn"/>'.
'<header title="E-mail"/>'.
'<header title="Intern" align="center"/>'.
'<header title="Ekstern" align="center"/>'.
'</headergroup>';

$sql="select * from user,object where user.object_id=object.id order by title";
$result = Database::select($sql);
while ($row = Database::next($result)) {
	$gui.='<row link="EditUser.php?id='.$row['id'].'">'.
	'<cell>'.
	'<icon size="1" icon="'.($row['administrator'] ? 'Role/Administrator' : 'Element/User').'"/>'.
	'<text>'.encodeXML($row['title']).'</text>'.
	'</cell>'.
	'<cell>'.encodeXML($row['username']).'</cell>'.
	'<cell>'.encodeXML($row['email']).'</cell>'.
	'<cell>'.($row['internal'] ? '<status type="Finished"/>' : '<status type="Stopped"/>').'</cell>'.
	'<cell>'.($row['external'] ? '<status type="Finished"/>' : '<status type="Stopped"/>').'</cell>'.
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