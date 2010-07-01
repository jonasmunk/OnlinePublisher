<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Images
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

$sql="select object.id,object.title,object.note,count(image.object_id) as imagecount,sum(image.size) as totalsize from imagegroup, imagegroup_image, image,object  where imagegroup_image.imagegroup_id=imagegroup.object_id and imagegroup_image.image_id = image.object_id and object.id=imagegroup.object_id group by imagegroup.object_id union select object.id,object.title,object.note,'0','0' from object left join imagegroup_image on imagegroup_image.imagegroup_id=object.id where object.type='imagegroup' and imagegroup_image.image_id is null order by title";
$result = Database::select($sql);
while ($row = Database::next($result)) {
	$gui.='<row link="Group.php?id='.$row['id'].'&amp;update=true" target="_parent">'.
	'<cell>'.
	'<icon size="1" icon="Element/Album"/>'.
	'<text>'.encodeXML($row['title']).'</text>'.
	'</cell>'.
	'<cell>'.encodeXML($row['note']).'</cell>'.
	'<cell>'.encodeXML($row['imagecount']).'</cell>'.
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