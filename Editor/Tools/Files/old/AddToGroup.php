<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Files
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';
require_once '../../Include/Session.php';
require_once '../../Include/XmlWebGui.php';
require_once 'Functions.php';

$id = getToolSessionVar('files','group');


$fileOptions='';
$sql="SELECT file.*,object.title,object.id FROM file,object LEFT JOIN filegroup_file ON filegroup_file.file_id=file.object_id and filegroup_file.filegroup_id=$id WHERE file.object_id=object.id and filegroup_file.file_id IS NULL order by title;";
$result = Database::select($sql);
while ($row = Database::next($result)) {
	$fileOptions.='<option title="'.encodeXML($row['title']).'" value="'.encodeXML($row['id']).'"/>';
}
Database::free($result);


$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../../"/>'.
'<interface background="Desktop">'.
'<window xmlns="uri:Window" width="400" top="20" align="center">'.
'<titlebar title="Tilf&#248;j filer til gruppe" icon="Basic/Add">'.
'<close link="Group.php"/>'.
'</titlebar>'.
'<content padding="5" background="true" valign="top">'.
'<form xmlns="uri:Form" action="InsertInGroup.php" method="post" name="Formula">'.
'<hidden name="id">'.$id.'</hidden>'.
'<group size="Large" badgewidth="20%" badgeplacement="above">'.
'<select badge="Filer:" name="file[]" lines="12" multiple="true">'.
$fileOptions.
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