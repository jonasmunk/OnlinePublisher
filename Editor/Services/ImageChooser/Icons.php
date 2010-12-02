<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Images
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Include/Functions.php';
require_once($basePath.'Editor/Classes/Utilities/StringUtils.php');

require_once 'ImageChooserController.php';

if (requestGetExists('group')) {
	ImageChooserController::setViewType('group');
	ImageChooserController::setGroupId(requestGetNumber('group'));
} elseif (requestGetExists('type')) {
	ImageChooserController::setViewType(requestGetText('type'));
}
$type = ImageChooserController::getViewType();

$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../../"/>'.
'<interface>'.
'<group xmlns="uri:Icon" width="100%" spacing="12" size="2" cellwidth="20%" >'.
'<row>';


$sql = ImageChooserController::buildSql($type);
$result = Database::select($sql);
$counter=0;
while ($row = Database::next($result)) {
	$counter++;
	if ($counter==6) {
		$gui.='</row><row>';
		$counter=1;
	}
	$gui.=
	'<icon title="'.encodeXML(StringUtils::shortenString($row['title'],16)).'" help="'.encodeXML($row['title']).'" image="../../../util/images/?id='.$row['id'].'&amp;maxwidth=32&amp;maxheight=32&amp;timestamp='.$row['updated'].'" link="javascript:parent.selectImage('.$row['id'].')" target="_parent"/>';

}
Database::free($result);


$gui.=
'</row>'.
'</group>';
if ($selectImage = requestGetNumber('selectImage')) {
	$gui.='<script xmlns="uri:Script">
	parent.selectImage('.$selectImage.');
	</script>';
}
$gui.=
'</interface>'.
'</xmlwebgui>';

$elements = array("Icon","Script");
writeGui($xwg_skin,$elements,$gui);
?>