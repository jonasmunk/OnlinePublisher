<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Images
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';
require_once '../../Include/XmlWebGui.php';
require_once 'ImagesController.php';

$id=ImagesController::getGroupId();

$imageOptions='';
$sql="SELECT object.* FROM object LEFT JOIN imagegroup_image ON imagegroup_image.image_id=object.id and imagegroup_image.imagegroup_id=$id WHERE object.type='image' and imagegroup_image.image_id IS NULL order by title;";
$result = Database::select($sql);
while ($row = Database::next($result)) {
	$imageOptions.='<option title="'.encodeXML($row['title']).'" value="'.encodeXML($row['id']).'"/>';
}
Database::free($result);

$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../../"/>'.
'<interface background="Desktop">'.
'<window xmlns="uri:Window" width="400" top="20" align="center">'.
'<titlebar title="Tilf&#248;j billeder til gruppe" icon="Basic/Attach">'.
'<close link="Group.php"/>'.
'</titlebar>'.
'<content padding="5" background="true" valign="top">'.
'<form xmlns="uri:Form" action="InsertInGroup.php" method="post" name="Formula">'.
'<hidden name="id">'.$id.'</hidden>'.
'<group size="Large" badgewidth="20%" badgeplacement="above">'.
'<select badge="Billeder:" name="image[]" lines="12" multiple="true">'.
$imageOptions.
'</select>'.
'<buttongroup size="Large">'.
'<button title="Annuller" link="Group.php"/>'.
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