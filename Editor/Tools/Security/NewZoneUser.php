<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Security
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Include/Functions.php';
require_once '../../Classes/Request.php';
require_once '../../Classes/Utilities/StringUtils.php';

$id=Request::getInt('id');

$users=buildUsers($id);

$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../../"/>'.
'<interface background="Desktop">'.
'<window xmlns="uri:Window" width="400" align="center" top="30">'.
'<titlebar title="Tilknyt brugere" icon="Element/User">'.
'<close link="EditZoneUsers.php?id='.$id.'"/>'.
'</titlebar>'.
'<content padding="5" background="true">'.
'<form xmlns="uri:Form" action="CreateZoneUser.php" method="post" name="Formula">'.
'<hidden name="id">'.$id.'</hidden>'.
'<group size="Large" badgeplacement="above">'.
'<select badge="Brugere:" name="user[]" lines="8" multiple="true">'.
$users.
'</select>'.
'<buttongroup size="Large">'.
'<button title="Annuller" link="EditZoneUsers.php?id='.$id.'"/>'.
'<button title="Tilknyt" submit="true" style="Hilited"/>'.
'</buttongroup>'.
'</group>'.
'</form>'.
'</content>'.
'</window>'.
'</interface>'.
'</xmlwebgui>';

$elements = array("Window","Toolbar","Form");
writeGui($xwg_skin,$elements,$gui);

function buildUsers($id) {
	$output='';
	$sql="select object.id,object.title, securityzone_user.securityzone_id from object left join securityzone_user on securityzone_user.user_id = object.id and securityzone_user.securityzone_id=".$id." where type='user' and securityzone_id is NULL order by title";
	$result = Database::select($sql);
	while ($row = Database::next($result)) {
		$output.='<option title="'.StringUtils::escapeXML($row['title']).'" value="'.$row['id'].'"/>';
	}
	Database::free($result);
	return $output;
}
?>