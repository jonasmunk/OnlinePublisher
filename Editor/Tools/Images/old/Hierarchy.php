<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Images
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';
require_once '../../Include/XmlWebGui.php';

$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../../"/>'.
'<interface>'.
'<config xmlns="uri:Drag" action="DragDrop.php?return=ImageIcons.php" method="post" proxy="Element/Image" target="IconContent">'.
'<frame name="parent.parent.Right.IconContent"/>'.
'</config>'.
'<hierarchy xmlns="uri:Hierarchy" persistence="true" unique="tools-images-hierarchy">'.
'<element icon="Tool/Images" title="Bibliotek" link="Library.php" target="Right">';

$sql="SELECT * FROM object where type='image' order by title;";
$result = Database::select($sql);
while ($row = Database::next($result)) {
	$gui.='<element icon="Element/Image" title="'.encodeXML(shortenString($row['title'],20)).'" link="Image.php?id='.$row['id'].'&amp;group=0" target="Right"/>';
}
Database::free($result);
$gui.='</element>'.

'<element icon="Element/Folders" title="Albums" link="Groups.php" target="Right">';

$sql="select * from object where type='imagegroup' order by title";
$result = Database::select($sql);
while ($row = Database::next($result)) {
	$gui.='<element icon="Element/Album" title="'.encodeXML($row['title']).'" link="Group.php?id='.$row['id'].'" target="Right" drop="group-'.$row['id'].'">';
	
	$sql="select object.* from object,imagegroup_image where object.type='image' and imagegroup_image.image_id=object.id and imagegroup_image.imagegroup_id=".$row['id']." order by title";
	$result_images = Database::select($sql);
	while ($images = Database::next($result_images)) {
		$gui.='<element icon="Element/Image" title="'.encodeXML(shortenString($images['title'],20)).'" link="Image.php?id='.$images['id'].'&amp;group='.$row['id'].'" target="Right"/>';

	}
	$gui.='</element>';
	Database::free($result_images);
}
Database::free($result);
$gui.='<element icon="Basic/Add" title="Nyt album" link="NewGroup.php" target="Right"/>';
$gui.='</element>';

$gui.=
'</hierarchy>'.
'<refresh xmlns="uri:Script" source="HierarchyUpdateCheck.php" interval="2000"/>'.
'</interface>'.
'</xmlwebgui>';

$elements = array("Hierarchy","Script","Drag");
writeGui($xwg_skin,$elements,$gui);
?>