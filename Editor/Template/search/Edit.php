<?php
/**
 * @package OnlinePublisher
 * @subpackage Templates.Search
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Classes/Request.php';
require_once 'Functions.php';

if (Request::exists('id')) {
	setSearchId(Request::getInt('id'));
}

$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../../"/>'.
'<interface xmlns="uri:Frame">'.
'<dock align="top" id="Root">'.
'<frame name="Toolbar" source="Toolbar.php" scrolling="false"/>'.
'<frame name="Editor" source="Properties.php"/>'.
'</dock>'.
'<script xmlns="uri:Script">
	if (window.parent!=window) {
		window.parent.baseController.changeSelection("service:edit");
	}
</script>'.
'</interface>'.
'</xmlwebgui>';

$elements = array("Frame","Script");
writeGui($xwg_skin,$elements,$gui);
?>
