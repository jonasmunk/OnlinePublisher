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
'<form xmlns="uri:Form" action="RemoveFromGroup.php" method="post">'.
'<list xmlns="uri:List" width="100%" margin="3" sort="true" selectable="'.($type=='group' ? 'image[]' : '').'">'.
'<content>'.
'<headergroup>'.
'<header title="Titel"/>'.
'<header title="Filnavn"/>'.
'<header title="Type"/>'.
'<header title="Bredde" type="number"/>'.
'<header title="H&#248;jde" type="number"/>'.
'<header title="St&#248;rrelse" type="number" align="right"/>'.
'<header title="" width="1%"/>'.
'</headergroup>';

$sql = ImagesController::buildSql($type);
$result = Database::select($sql);
while ($row = Database::next($result)) {
	$gui.='<row link="Image.php?id='.$row['id'].'" target="_parent" uid="'.$row['id'].'">'.
	'<cell>'.
	'<icon size="1" icon="Element/Image"/>'.
	'<text>'.encodeXML(shortenString($row['title'],30)).'</text>'.
	'</cell>'.
	'<cell>'.encodeXML(shortenString($row['filename'],30)).'</cell>'.
	'<cell>'.GuiUtils::mimeTypeToKind($row['type']).'</cell>'.
	'<cell>'.$row['width'].'</cell>'.
	'<cell>'.$row['height'].'</cell>'.
	'<cell index="'.$row['size'].'" help="'.$row['size'].' bytes">'.GuiUtils::bytesToString($row['size']).'</cell>'.
	'<cell>'.
	'<icon icon="Basic/View" link="ImageView.php?id='.$row['id'].'" target="_parent"/>'.
	'<icon icon="Basic/Download" link="DownloadImage.php?id='.$row['id'].'"/>'.
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