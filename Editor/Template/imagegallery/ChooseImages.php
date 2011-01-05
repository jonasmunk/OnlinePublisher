<?php
/**
 * @package OnlinePublisher
 * @subpackage Templates.ImageGallery
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Classes/Database.php';
require_once '../../Classes/InternalSession.php';
require_once '../../Classes/Utilities/StringUtils.php';

$id=InternalSession::getPageId();

$imageOptions='';
$sql="SELECT object.* FROM object LEFT JOIN imagegallery_object ON imagegallery_object.object_id=object.id and imagegallery_object.page_id=$id WHERE object.type='image' and imagegallery_object.object_id IS NULL order by object.title;";
$result = Database::select($sql);
while ($row = Database::next($result)) {
	$imageOptions.='<option title="'.StringUtils::escapeXML($row['title']).'" value="'.StringUtils::escapeXML($row['id']).'"/>';
}
Database::free($result);

$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../../"/>'.
'<interface background="Desktop">'.
'<window xmlns="uri:Window" width="400" top="20" align="center">'.
'<titlebar title="Tilf&#248;j billeder til galleriet" icon="Basic/Attach">'.
'<close link="Images.php"/>'.
'</titlebar>'.
'<content padding="5" background="true" valign="top">'.
'<form xmlns="uri:Form" action="AddObjects.php" method="post" name="Formula">'.
'<hidden name="id">'.$id.'</hidden>'.
'<group size="Large" badgewidth="20%" badgeplacement="above">'.
'<select badge="Billeder:" name="object[]" lines="12" multiple="true">'.
$imageOptions.
'</select>'.
'<buttongroup size="Large">'.
'<button title="Annuller" link="Images.php"/>'.
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