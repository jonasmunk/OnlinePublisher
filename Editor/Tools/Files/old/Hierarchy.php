<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Files
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Classes/GuiUtils.php';


$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../../"/>'.
'<interface>'.
'<hierarchy xmlns="uri:Hierarchy" persistence="true" unique="tools-files-hierarchy">'.
'<element icon="Tool/Files" title="Bibliotek" link="Library.php" target="Right">';
$sql="SELECT object.id,object.title,file.filename FROM file,object WHERE object.id=file.object_id order by title";
$result = Database::select($sql);
while ($row = Database::next($result)) {
	$gui.='<element icon="'.GuiUtils::getFileIcon($row['filename']).'" title="'.encodeXML(shortenString($row['title'],20)).'" link="File.php?id='.$row['id'].'&amp;group=0" target="Right"/>';
}
Database::free($result);
$gui.='</element>'.
'<element icon="Element/Folders" title="Grupper" link="Groups.php" target="Right">';


$sql="select id,title from object where type='filegroup' order by title";
$result = Database::select($sql);
while ($row = Database::next($result)) {
	$gui.='<element icon="Element/Folder" title="'.encodeXML($row['title']).'" link="Group.php?id='.$row['id'].'" target="Right">';
	
	$sql="select object.id,object.title,file.filename from object,file,filegroup_file where object.id=file.object_id and filegroup_file.file_id=object.id and filegroup_file.filegroup_id=".$row['id']." order by title";
	$result_files = Database::select($sql);
	while ($files = Database::next($result_files)) {
		$gui.='<element icon="'.GuiUtils::getFileIcon($files['filename']).'" title="'.encodeXML(shortenString($files['title'],20)).'" link="File.php?id='.$files['id'].'&amp;group='.$row['id'].'" target="Right"/>';

	}
	$gui.='</element>';
	Database::free($result_files);
}
Database::free($result);

$gui.=
'<element icon="Basic/Add" title="Ny gruppe" link="NewGroup.php" target="Right"/>'.
'</element>'.
'</hierarchy>'.
'<refresh xmlns="uri:Script" source="HierarchyUpdateCheck.php" interval="3000"/>'.
'</interface>'.
'</xmlwebgui>';

$elements = array("Hierarchy","Script");
writeGui($xwg_skin,$elements,$gui);
?>