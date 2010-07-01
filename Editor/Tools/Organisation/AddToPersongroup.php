<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Organisation
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';
require_once '../../Include/XmlWebGui.php';
require_once 'Functions.php';

$id=getPersonGroup();

$personOptions='';
$sql="SELECT person.*, object.* FROM person, object LEFT JOIN persongroup_person ON persongroup_person.person_id=person.object_id and persongroup_person.persongroup_id=$id WHERE persongroup_person.person_id IS NULL AND person.object_id = object.id;";
$result = Database::select($sql);
while ($row = Database::next($result)) {
	$personOptions.='<option title="'.concatenatePersonName(encodeXML($row['firstname']),encodeXML($row['middlename']),encodeXML($row['surname'])).'" value="'.encodeXML($row['id']).'"/>';
}
Database::free($result);

$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../../"/>'.
'<interface background="Desktop">'.
'<window xmlns="uri:Window" width="400" top="20" align="center">'.
'<titlebar title="Tilf&#248;j personer til gruppe" icon="Basic/Add">'.
'<close link="Persongroup.php"/>'.
'</titlebar>'.
'<content padding="5" background="true" valign="top">'.
'<form xmlns="uri:Form" action="InsertInGroup.php" method="post" name="Formula">'.
'<hidden name="id">'.$id.'</hidden>'.
'<group size="Large" badgewidth="20%" badgeplacement="above">'.
'<select badge="Personer:" name="persons[]" lines="12" multiple="true">'.
$personOptions.
'</select>'.
'<buttongroup size="Large">'.
'<button title="Annuller" link="Persongroup.php"/>'.
'<button title="Tilf&#248;j" submit="true" style="Hilited"/>'.
'</buttongroup>'.
'</group>'.
'</form>'.
'</content>'.
'</window>'.
'</interface>'.
'</xmlwebgui>';

$elements = array("Window","Form");
writeGui($xwg_skin,$elements,$gui);
?>