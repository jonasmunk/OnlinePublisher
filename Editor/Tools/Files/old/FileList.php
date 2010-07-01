<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Files
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Include/Functions.php';
require_once '../../Include/Session.php';
require_once '../../Classes/GuiUtils.php';
require_once 'Functions.php';

$group = getToolSessionVar('files','group');

$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../../"/>'.
'<interface>'.
'<form xmlns="uri:Form" action="RemoveFromGroup.php" method="post">'.
'<list xmlns="uri:List" width="100%" margin="3" sort="true">'.
'<content>'.
'<headergroup>'.
($group>0 ? '<header width="1%"/>' : '').
'<header title="Titel"/>'.
'<header title="Filnavn"/>'.
'<header title="Type"/>'.
'<header title="St&#248;rrelse" type="number" align="right"/>'.
'<header title="" width="1%"/>'.
'</headergroup>';



if ($group>0) {
	$sql="select file.*,object.id,object.title from file,filegroup_file,object where object.id=file.object_id and  filegroup_file.file_id=file.object_id and filegroup_file.filegroup_id=$group order by object.title";
}
else {
	$sql="SELECT object.title,object.id,file.* FROM file,object WHERE object.id=file.object_id order by title";
}
$result = Database::select($sql);
while ($row = Database::next($result)) {
	$icon = GuiUtils::getFileIcon($row['filename']);
	$gui.='<row link="File.php?id='.$row['id'].'" target="_parent">'.
	($group>0 ? '<cell><checkbox name="file[]" value="'.$row['id'].'"/></cell>' : '').
	'<cell>'.
	'<icon icon="'.$icon.'"/>'.
	'<text>'.encodeXML(shortenString($row['title'],40)).'</text>'.
	'</cell>'.
	'<cell>'.encodeXML($row['filename']).'</cell>'.
	'<cell>'.encodeXML(GuiUtils::mimeTypeToKind($row['type'])).'</cell>'.
	'<cell index="'.$row['size'].'" help="'.$row['size'].' bytes">'.GuiUtils::bytesToString($row['size']).'</cell>'.
	'<cell>'.
	'<icon icon="Basic/View" link="FileView.php?id='.$row['id'].'" target="_parent"/>'.
	'<icon icon="Basic/Download" link="DownloadFile.php?id='.$row['id'].'"/>'.
	'</cell>'.
	'</row>';
}
Database::free($result);

$gui.=
'</content>'.
'</list>'.
'</form>'.
'</interface>'.
'</xmlwebgui>';

$elements = array("List","Form");
writeGui($xwg_skin,$elements,$gui);
?>