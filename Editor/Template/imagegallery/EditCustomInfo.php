<?php
/**
 * @package OnlinePublisher
 * @subpackage Templates.ImageGallery
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Classes/Image.php';

$imageId = requestGetNumber('id');
$pageId = getPageId();

$sql="select * from imagegallery_custom_info where page_id=".$pageId." and image_id=".$imageId;
if ($row = Database::selectFirst($sql)) {
    $title = $row['title'];
    $note = $row['note'];
    $custom = true;
} elseif ($image = Image::load($imageId)) {
    $title = $image->getTitle();
    $note = $image->getNote();
    $custom = false;
} else {
    die('Could not load image!');
}

$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../../"/>'.
'<interface background="Desktop">'.
'<window xmlns="uri:Window" width="400" align="center" top="30">'.
'<titlebar title="Billede" icon="Element/Image">'.
'<close link="Images.php"/>'.
'</titlebar>'.
'<content padding="5" background="true">'.
'<form xmlns="uri:Form" action="UpdateCustomInfo.php" method="post" name="Formula" focus="title">'.
'<hidden name="id">'.$imageId.'</hidden>'.
'<group size="Large" badgeplacement="above">'.
'<textfield badge="Titel:" name="title">'.encodeXML($title).'</textfield>'.
'<textfield badge="Beskrivelse:" name="note" lines="6">'.encodeXML($note).'</textfield>'.
'<buttongroup size="Large">'.
'<button title="Gendan"'.($custom ? ' link="RevertCustomInfo.php?id='.$imageId.'"' : ' style="Disabled"').'/>'.
'<button title="Annuller" link="Images.php"/>'.
'<button title="Opdater" submit="true" style="Hilited"/>'.
'</buttongroup>'.
'</group>'.
'</form>'.
'</content>'.
'</window>'.
'</interface>'.
'</xmlwebgui>';

$elements = array("Window","Toolbar","Form");
writeGui($xwg_skin,$elements,$gui);
?>