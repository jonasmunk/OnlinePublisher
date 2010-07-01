<?php
/**
 * @package OnlinePublisher
 * @subpackage Services.Preview
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Classes/InternalSession.php';
require_once '../../Classes/In2iGui.php';
require_once '../../Classes/Request.php';
require_once 'Functions.php';

if (Request::exists('id')) {
	InternalSession::setPageId(Request::getInt('id'));
}
if (Request::exists('return')) {
	setPreviewReturn(Request::getString('return'));
}

$gui='
<frames xmlns="uri:In2iGui">
	<frame source="Toolbar.php" scrolling="false"/>
	<frame source="Frame.php"/>
</frames>';

In2iGui::render($gui);
exit;

$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../../"/>'.
'<interface xmlns="uri:Frame">'.
'<dock align="top" id="Root" tabs="true">'.
'<frame source="Toolbar.php" scrolling="false"/>'.
'<frame source="Frame.php" name="Bottom"/>'.
'</dock>'.
'</interface>'.
'</xmlwebgui>';

$elements = array("Frame");
writeGui($xwg_skin,$elements,$gui);
?>
