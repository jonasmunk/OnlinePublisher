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

require_once 'ImagesController.php';

$type = ImagesController::getViewType();

$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../../"/>'.
'<interface>'.
'<group xmlns="uri:Icon" width="100%" spacing="12" size="3" cellwidth="20%" object="Icons">'.
'<row>';

$groups = ImagesController::getGroups();

$sql = ImagesController::buildSql($type);
$result = Database::select($sql);
$counter=0;
while ($row = Database::next($result)) {
	$counter++;
	if ($counter==6) {
		$gui.='</row><row>';
		$counter=1;
	}
	$gui.=
	'<icon title="'.encodeXML(shortenString($row['title'],16)).'" help="'.encodeXML($row['title']).'" description="'.(round($row['width']*$row['height']/10000)/100).' megapixel, '.GuiUtils::bytesToString($row['size']).'" image="../../../util/images/?id='.$row['id'].'&amp;maxwidth=48&amp;maxheight=48&amp;format=jpg&amp;timestamp='.$row['updated'].'" link="Image.php?id='.$row['id'].'" target="_parent" unique="'.$row['id'].'"/>';

}
Database::free($result);

$gui.=
'</row>'.
'</group>'.
'<menu xmlns="uri:Menu" object="ContextMenu" width="130">'.
'<item title="Vis info" link="javascript: menuDelegate.imageInfo();"/>'.
'<item title="Rediger egenskaber" link="javascript: menuDelegate.imageProperties();"/>'.
'<item title="Vis fuld størrelse" link="javascript: menuDelegate.viewImage();"/>'.
'<item title="Download" link="javascript: menuDelegate.downloadImage();"/>'.
'<item title="Slet billedet" link="javascript: menuDelegate.deleteImage();"/>'.
'<separator/>'.
($type=='group'
? '<item title="Fjern fra gruppe" link="javascript: menuDelegate.removeFromGroup();"/>'
: '').
'<item title="Tilføj gruppe">'.
'<menu width="160">';
foreach ($groups as $group) {
	$gui.='<item title="'.encodeXML(shortenString($group['title'],20)).'" link="javascript: menuDelegate.addToGroup('.$group['id'].');"/>';
}
$gui.=
'</menu>'.
'</item>';
if ($type=='group') {
	$gui.=
	'<item title="Flyt til gruppe">'.
	'<menu width="160">';
	foreach ($groups as $group) {
		$gui.='<item title="'.encodeXML(shortenString($group['title'],20)).'" link="javascript: menuDelegate.moveToGroup('.$group['id'].');"/>';
	}
	$gui.=
	'</menu>'.
	'</item>';
}
$gui.=
'</menu>'.
'<script xmlns="uri:Script" source="js/IconView.js"/>'.
'</interface>'.
'</xmlwebgui>';

$elements = array("Icon","Script","Menu");
writeGui($xwg_skin,$elements,$gui);
?>