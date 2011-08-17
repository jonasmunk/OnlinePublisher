<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Files
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/XmlWebGui.php';
require_once 'ProjectsController.php';



$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../../"/>'.
'<interface background="Desktop">'.
'<layout xmlns="uri:Layout" width="100%" height="100%" spacing="10">'.
'<row><cell width="220">'.
'<area xmlns="uri:Area" width="100%" height="100%">'.
'<titlebar title="Oversigt"/>'.
'<content padding="3">'.
'<iframe xmlns="uri:Frame" source="Hierarchy.php"/>'.
'</content>'.
'</area>'.
'</cell><cell>'.
'<iframe xmlns="uri:Frame" source="Overview.php" name="Right"/>'.
'</cell></row>'.
'</layout>'.
'</interface>'.
'<script xmlns="uri:Script" type="text/javascript">
	if (window.parent!=window) {
		window.parent.baseController.changeSelection(\'tool:Projects\');
	}
</script>'.
'</xmlwebgui>';

$elements = array("Layout","Frame","Area","Script");
writeGui($xwg_skin,$elements,$gui);
?>