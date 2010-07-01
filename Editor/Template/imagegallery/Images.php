<?php
/**
 * @package OnlinePublisher
 * @subpackage Templates.ImageGallery
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Classes/GuiUtils.php';

$id = getPageId();

$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../../"/>'.
'<interface background="Desktop">'.
'<window xmlns="uri:Window" width="750" align="center" top="30">'.
'<titlebar title="Billedgalleri">'.
'<close link="../../Tools/Pages/index.php" target="Desktop"/>'.
'</titlebar>'.
'<tabgroup align="center" size="Large">'.
'<tab title="Indstillinger" link="Text.php"/>'.
'<tab title="Billeder" style="Hilited"/>'.
'</tabgroup >'.
'<toolbar xmlns="uri:Toolbar" align="left">'.
'<tool title="Tilf&#248;j billeder" icon="Tool/Images" overlay="Attach" link="ChooseImages.php"/>'.
'<tool title="Tilf&#248;j grupper" icon="Element/Album" overlay="Attach" link="ChooseGroups.php"/>'.
'</toolbar>'.
'<content background="Window">'.
'<overflow xmlns="uri:Layout" height="360">'.
'<list xmlns="uri:List" width="100%" margin="3">'.
'<content>'.
'<headergroup>'.
'<header title="" width="1%" align="center"/>'.
'<header title="Titel" width="25%"/>'.
'<header title="Beskrivelse" width="45%"/>'.
'<header title="Bredde/h&#248;jde" type="number" align="right" width="15%"/>'.
'<header title="St&#248;rrelse" type="number" align="right" width="15%"/>'.
'<header align="right" width="1%"/>'.
'</headergroup>';

$sql="select object.*,image.width,image.height,image.size,imagegallery_object.id as io_id".
",info.id as info_id,info.title as info_title,info.note as info_note".
" from imagegallery_object,object".
" left join image on object.id = image.object_id".
" left join imagegallery_custom_info as info on info.image_id = object.id".
" where (object.type='imagegroup' or object.type='image')".
" and object.id=imagegallery_object.object_id".
" and imagegallery_object.page_id=".$id.
" order by imagegallery_object.position";
error_log($sql);
$result = Database::select($sql);
while ($row = Database::next($result)) {
    if ($row['type']=='imagegroup') {
	    $gui.=
	    '<row>'.
        '<cell>'.
        '<icon size="3" icon="Element/Album"/>'.
        '</cell>'.
    	'<cell>'.
    	'<text>'.encodeXML($row['title']).'</text>'.
    	'</cell>'.
    	'<cell></cell>'.
    	'<cell></cell>'.
    	'<cell></cell>';
    } else {
        $title = ($row['info_id']!='' ? $row['info_title'] : $row['title']);
        $note = ($row['info_id']!='' ? $row['info_note'] : $row['note']);
    	$gui.=
	    '<row link="EditCustomInfo.php?id='.$row['id'].'">'.
    	'<cell>'.
        '<html xmlns="uri:Html">'.
    	'<td height="48" align="center">'.
    	'<img src="../../../util/images/?id='.$row['id'].'&amp;maxwidth=48&amp;maxheight=48"/>'.
    	'</td>'.
    	'</html>'.
    	'</cell>'.
    	'<cell>'.
    	'<text>'.encodeXML($title).'</text>'.
    	'</cell>'.
    	'<cell>'.encodeXML($note).'</cell>'.
    	'<cell>'.$row['width'].'x'.$row['height'].'</cell>'.
    	'<cell>'.GuiUtils::bytesToString($row['size']).'</cell>';
    }
	$gui.=
	'<cell>'.
	'<direction direction="Up" link="MoveObject.php?id='.$row['io_id'].'&amp;dir=-1"/>'.
	'<direction direction="Down" link="MoveObject.php?id='.$row['io_id'].'&amp;dir=1"/>'.
	'<icon icon="Basic/Delete" link="RemoveObject.php?id='.$row['io_id'].'"/>'.
	'</cell>'.
	'</row>';
	if ($row['type']=='imagegroup') {
	    buildGroupList($row['id'],$gui);
    }
}
Database::free($result);

$gui.=
'</content>'.
'</list>'.
'</overflow>'.
'</content>'.
'</window>'.
'<script xmlns="uri:Script">parent.Toolbar.location="Toolbar.php?"+Math.random();</script>'.
'</interface>'.
'</xmlwebgui>';

$elements = array("Window","Toolbar","List","Layout","Html","Script");
writeGui($xwg_skin,$elements,$gui);

function buildGroupList($id,&$gui) {
	$sql=
	"select object.title,object.note,object.id,image.*,info.id as info_id".
	",info.title as info_title,info.note as info_note".
	" from image,imagegroup_image,object".
	" left join imagegallery_custom_info as info on info.image_id = object.id".
	" where object.id=image.object_id and imagegroup_image.image_id=image.object_id".
	" and imagegroup_image.imagegroup_id=$id order by title";
    $result = Database::select($sql);
    while ($row = Database::next($result)) {
        $title = ($row['info_id']!='' ? $row['info_title'] : $row['title']);
        $note = ($row['info_id']!='' ? $row['info_note'] : $row['note']);
    	$gui.=
    	'<row link="EditCustomInfo.php?id='.$row['id'].'">'.
    	'<cell><html xmlns="uri:Html"><td height="48" align="center"><img src="../../../util/images/?id='.$row['id'].'&amp;maxwidth=48&amp;maxheight=48"/></td></html></cell>'.
    	'<cell>'.encodeXML($title).'</cell>'.
    	'<cell>'.encodeXML($note).'</cell>'.
    	'<cell>'.$row['width'].'x'.$row['height'].'</cell>'.
    	'<cell>'.GuiUtils::bytesToString($row['size']).'</cell>'.
    	'<cell></cell>'.
    	'</row>';
    }
    Database::free($result);    
}
?>