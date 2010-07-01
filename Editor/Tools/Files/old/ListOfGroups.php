<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Files
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Include/Functions.php';
require_once '../../Classes/GuiUtils.php';

$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../../"/>'.
'<interface>'.
'<list xmlns="uri:List" width="100%" margin="3" sort="true">'.
'<content>'.
'<headergroup>'.
'<header title="Titel"/>'.
'<header title="Beskrivelse" width="50%"/>'.
'<header title="Antal" width="10%" align="center" type="number"/>'.
'<header title="St&#248;rrelse" width="10%" align="right" type="number"/>'.
'</headergroup>';

$sql="select distinct object.id,object.title,object.note,count(file.object_id) as filecount,sum(file.size) as totalsize from filegroup, filegroup_file, file,object  where filegroup_file.filegroup_id=filegroup.object_id and filegroup_file.file_id = file.object_id and object.id=filegroup.object_id group by filegroup.object_id union select object.id,object.title,object.note,'0','0' from object left join filegroup_file on filegroup_file.filegroup_id=object.id where object.type='filegroup' and filegroup_file.file_id is null order by title";
$result = Database::select($sql);
while ($row = Database::next($result)) {
	$gui.='<row link="Group.php?id='.$row['id'].'" target="_parent">'.
	'<cell>'.
	'<icon size="1" icon="Element/Folder"/>'.
	'<text>'.encodeXML($row['title']).'</text>'.
	'</cell>'.
	'<cell>'.encodeXML($row['note']).'</cell>'.
	'<cell>'.encodeXML($row['filecount']).'</cell>'.
	'<cell index="'.$row['totalsize'].'">'.GuiUtils::bytesToString($row['totalsize']).'</cell>'.
	'</row>';
}
Database::free($result);

$gui.=
'</content>'.
'</list>'.
'</interface>'.
'</xmlwebgui>';

$elements = array("List");
writeGui($xwg_skin,$elements,$gui);
?>