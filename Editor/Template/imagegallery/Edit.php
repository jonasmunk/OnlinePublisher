<?php
/**
 * @package OnlinePublisher
 * @subpackage Templates.ImageGallery
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';
require_once '../../Include/XmlWebGui.php';
require_once 'Functions.php';

if (requestGetExists('id')) {
	setImageGalleryId(requestGetNumber('id',0));
}

$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../../"/>'.
'<interface xmlns="uri:Frame">'.
'<dock align="top" id="Root">'.
'<frame name="Toolbar" source="Toolbar.php" scrolling="false"/>'.
'<frame name="Editor" source="Text.php"/>'.
'</dock>'.
'</interface>'.
'</xmlwebgui>';

$elements = array("Frame");
writeGui($xwg_skin,$elements,$gui);
?>
